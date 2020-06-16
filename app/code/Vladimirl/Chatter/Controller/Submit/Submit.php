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
     * @var \Vladimirl\Chatter\Model\ChatFactory $chatsFactory
     */
    private $chatFactory;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\Chat $chatsResourceModel
     */
    private $chatResourceModel;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatCollectionFactory $chatsCollectionFactory
     */
    private $chatCollectionFactory;

    /**
     * @var \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    private $transactionFactory;

    /**
     * @var \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory
     */
    private $chatMessageFactory;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\ChatMessage $chatMessageResourceModel
     */
    private $chatMessageResourceModel;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * Submit constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Vladimirl\Chatter\Model\ChatFactory $chatsFactory
     * @param \Vladimirl\Chatter\Model\ResourceModel\Chat $chatsResourceModel
     * @param \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatCollectionFactory $chatsCollectionFactory
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory
     * @param \Vladimirl\Chatter\Model\ResourceModel\ChatMessage $chatMessageResourceModel
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Vladimirl\Chatter\Model\ChatFactory $chatsFactory,
        \Vladimirl\Chatter\Model\ResourceModel\Chat $chatsResourceModel,
        \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatCollectionFactory $chatsCollectionFactory,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory,
        \Vladimirl\Chatter\Model\ResourceModel\ChatMessage $chatMessageResourceModel,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->chatFactory = $chatsFactory;
        $this->chatResourceModel = $chatsResourceModel;
        $this->chatCollectionFactory = $chatsCollectionFactory;
        $this->transactionFactory = $transactionFactory;
        $this->chatMessageFactory = $chatMessageFactory;
        $this->chatMessageResourceModel = $chatMessageResourceModel;
        $this->storeManager = $storeManager;
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

            if (!$chatHash = $this->customerSession->getChatHash()) {
                $this->customerSession->setChatHash($this->generateHash());
            }

            $customerId = (int) $this->customerSession->getId();
            if ($customerId) {
                $authorType = (string) $this->customerSession->getAuthorType();
                $authorName = $this->customerSession->getCustomerData()->getEmail();
            } else {
                $authorType = 'guest';
                $authorName = 'anonymous';
            }

            $websiteId = (int) $this->storeManager->getWebsite()->getId();
            $createdAt = time();
            $chatCollection = $this->chatCollectionFactory->create();
            $chatCollection->addChatHashFilter($this->customerSession->getChatHash());
            $chat = $this->chatFactory->create();
            if ($chatCollection->getFirstItem()->getChatHash() === null) {
                $chat->setAuthorId($customerId)
                    ->setAuthorName($authorName)
                    ->setPriority($this->checkPriority())
                    ->setIsActive(1)
                    ->setWebsiteId($websiteId)
                    ->setChatHash($this->customerSession->getChatHash())
                    ->setCreatedAt($createdAt);
                $this->chatResourceModel->save($chat);
            } else {
                $transaction = $this->transactionFactory->create();
                foreach ($chatCollection as $collectionChat) {
                    $collectionChat->setPriority($this->checkPriority())
                        ->setIsActive(1);
                    $transaction->addObject($collectionChat);
                }
                $transaction->save();
            }

            $chatCollection = $this->chatCollectionFactory->create();
            $chatId = (int) $chatCollection->addChatHashFilter($this->customerSession->getChatHash())
                ->setOrder('chat_id', 'DESC')
                ->getFirstItem()
                ->getChatId();

            $firstItem = $chatCollection->addChatHashFilter($this->customerSession->getChatHash())
                ->setOrder('chat_id', 'DESC')
                ->getFirstItem();
            $getTime = (string) $firstItem->getCreatedAt();
            $timeGet = strtotime($getTime);
            $foo = false;


            $chatMessage = $this->chatMessageFactory->create();
            $chatMessage->setAuthorType($authorType)
                ->setAuthorId($customerId)
                ->setAuthorName($authorName)
                ->setMessage($this->getChatMessage())
                ->setWebsiteId($websiteId)
                ->setChatId($chatId)
                ->setChatHash($this->customerSession->getChatHash())
                ->setCreatedAt($createdAt);
            $this->chatMessageResourceModel->save($chatMessage);

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
     * @return string
     */
    public function checkPriority(): string
    {
        if ($this->customerSession->getPageAction() !== 'checkout_index_index') {
            $priority = 'REGULAR';
        } else {
            $priority = 'IMMEDIATE';
        }
        return $priority;
    }
}
