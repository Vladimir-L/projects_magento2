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
     * @var \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    private $transactionFactory;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $messageCollectionFactory
     */
    private $messageCollectionFactory;

    /**
     * @var \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory
     */
    private $chatMessageFactory;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\ChatMessage $resourceModel
     */
    private $resourceModel;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * Submit constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $messageCollectionFactory
     * @param \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory
     * @param \Vladimirl\Chatter\Model\ResourceModel\ChatMessage $resourceModel
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $messageCollectionFactory,
        \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory,
        \Vladimirl\Chatter\Model\ResourceModel\ChatMessage $resourceModel,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->transactionFactory = $transactionFactory;
        $this->messageCollectionFactory = $messageCollectionFactory;
        $this->chatMessageFactory = $chatMessageFactory;
        $this->resourceModel = $resourceModel;
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
                $authorType = 'customer';
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

                foreach ($messageCollection as $existingMessage) {
                    if ($existingMessage->getAuthorId() !== $customerId) {
                        $existingMessage->setAuthorType($authorType)
                            ->setAuthorId($customerId)
                            ->setAuthorName($authorName)
                            ->setChatHash($oldChatHash);
                    }
                    $transaction->addObject($existingMessage);
                }
                $transaction->save();
                $this->customerSession->setChatHash($oldChatHash);

            } else {
                $authorType = 'unknown';
                $authorName = 'anonymous';
            }
            $chatMessage = $this->chatMessageFactory->create();
            $chatMessage->setAuthorType($authorType)
                ->setAuthorId($customerId)
                ->setAuthorName($authorName)
                ->setMessage($this->getChatMessage())
                ->setWebsiteId($websiteId)
                ->setChatHash($this->customerSession->getChatHash());
            $this->resourceModel->save($chatMessage);
            $message = __('Our administrator will contact you soon!');
        } catch (\Exception $e) {
            $message = __('Error!');
        }
        /** @var JsonResult $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData([
            'message' => $message,
            'messageOutput' => $this->getChatMessage(),
            'createdAt' => date("Y-m-d H:i:s"),
            'authorType' => $authorType
        ]);
        return $response;
    }
}
