<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Controller\Submit;

use Magento\Framework\Controller\Result\Json as JsonResult;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class Submit extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpPostActionInterface
{
    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $customerSession;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     */
    private $formKeyValidator;

    /**
     * @var \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory
     */
    private $chatMessageFactory;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $messageCollectionFactory
     */
    private $messageCollectionFactory;

    /**
     * @var \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    private $transactionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * Submit constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory
     * @param \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $messageCollectionFactory
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory,
        \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $messageCollectionFactory,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->chatMessageFactory = $chatMessageFactory;
        $this->messageCollectionFactory = $messageCollectionFactory;
        $this->transactionFactory = $transactionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @return string
     */
    public function getChatMessage(): string
    {
        return $this->getRequest()->getParam('text_request');
    }

    /**
     * @return string
     */
    public function generateHash(): string
    {
        return uniqid('', false);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|JsonResult|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            if (!$this->formKeyValidator->validate($this->getRequest())) {
                throw new LocalizedException(__('Something went wrong!'));
            }

            $customerId = (int) $this->customerSession->getId();
            $websiteId = (int) $this->storeManager->getWebsite()->getId();

            if (!$chatHash = $this->customerSession->getChatHash()) {
                $this->customerSession->setChatHash($this->generateHash());
            }

            if ($customerId) {
                $transaction = $this->transactionFactory->create();
                $customerName = $this->customerSession->getCustomerData()->getEmail();
                $authorType = 'customer';

                $messageCollection = $this->messageCollectionFactory->create();
                $messageCollection->addCustomerIdFilter($customerId)
                    ->addAuthorTypeFilter($authorType);
                $oldChatHash = $messageCollection->getFirstItem()->getChatHash();
                if ($oldChatHash === null) {
                    $oldChatHash = $this->customerSession->getChatHash();
                }

                $messageCollection = $this->messageCollectionFactory->create();
                $messageCollection->addChatHashFilter($chatHash);

                foreach ($messageCollection as $existingMessage) {
                    if ($existingMessage->getAuthorId() !== $customerId) {
                        $existingMessage->setAuthorType($authorType)
                            ->setAuthorId($customerId)
                            ->setAuthorName($customerName)
                            ->setChatHash($oldChatHash);
                    }
                    $transaction->addObject($existingMessage);
                }
                $transaction->save();
                $this->customerSession->setChatHash($oldChatHash);

            } else {
                $customerName = 'anonymous';
                $authorType = 'unknown';
            }
            $transaction = $this->transactionFactory->create();
            $chatMessage = $this->chatMessageFactory->create();
            $chatMessage->setAuthorType($authorType)
                ->setAuthorId($customerId)
                ->setAuthorName($customerName)
                ->setMessage($this->getChatMessage())
                ->setWebsiteId($websiteId)
                ->setChatHash($this->customerSession->getChatHash());
            $transaction->addObject($chatMessage);
            $transaction->save();
            $message = __('Our administrator will contact you soon!');
        } catch (\Exception $e) {
            $message = __('Error!');
        }
        /** @var JsonResult $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData([
            'message' => $message,
            'messageOutput' => $this->getChatMessage()
        ]);
        return $response;
    }
}
