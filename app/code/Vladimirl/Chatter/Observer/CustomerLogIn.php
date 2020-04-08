<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Observer;

class CustomerLogIn implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $customerSession;

    /**
     * @var \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    private $transactionFactory;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $messageCollectionFactory
     */
    private $messageCollectionFactory;

    /**
     * CustomerLogIn constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $messageCollectionFactory
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $messageCollectionFactory
    ) {
        $this->customerSession = $customerSession;
        $this->transactionFactory = $transactionFactory;
        $this->messageCollectionFactory = $messageCollectionFactory;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Exception
     */
    public function execute(\Magento\Framework\Event\Observer $observer):void
    {
        if ($this->customerSession->isLoggedIn()) {

            if (!$chatHash = $this->customerSession->getChatHash()) {
                $this->customerSession->setChatHash($this->generateHash());
            }

            $customerId = (int) $this->customerSession->getId();
            $this->customerSession->setAuthorType('customer');
            $authorType = (string) $this->customerSession->getAuthorType();
            $authorName = $this->customerSession->getCustomerData()->getEmail();

            $messageCollection = $this->messageCollectionFactory->create();
            $messageCollection->addCustomerIdFilter($customerId)
                ->addAuthorTypeFilter($authorType);

            $oldChatHash = $messageCollection->getFirstItem()->getChatHash();
            if ($oldChatHash === null) {
                $oldChatHash = $this->customerSession->getChatHash();
            }

            $messageCollection = $this->messageCollectionFactory->create();
            $messageCollection->addChatHashFilter($chatHash);

            $transaction = $this->transactionFactory->create();
            foreach ($messageCollection as $existingMessage) {
                if ((int)$existingMessage->getAuthorId() !== $customerId) {
                    $existingMessage->setAuthorType($authorType)
                        ->setAuthorId($customerId)
                        ->setAuthorName($authorName)
                        ->setChatHash($oldChatHash);
                }
                $transaction->addObject($existingMessage);
            }
            $transaction->save();
            $this->customerSession->setChatHash($oldChatHash);
        }
    }
}
