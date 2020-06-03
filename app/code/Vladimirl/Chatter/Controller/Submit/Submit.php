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
     * @var \Vladimirl\Chatter\Api\Data\MessageInterfaceFactory
     */
    private $messageFactory;

    /**
     * @var \Vladimirl\Chatter\Model\MessageRepository $messageRepository
     */
    private $messageRepository;

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
     * @param \Vladimirl\Chatter\Api\Data\MessageInterfaceFactory $messageFactory
     * @param \Vladimirl\Chatter\Model\MessageRepository $messageRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Vladimirl\Chatter\Model\ChatFactory $chatsFactory,
        \Vladimirl\Chatter\Model\ResourceModel\Chat $chatsResourceModel,
        \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatCollectionFactory $chatsCollectionFactory,
        \Vladimirl\Chatter\Api\Data\MessageInterfaceFactory $messageFactory,
        \Vladimirl\Chatter\Model\MessageRepository $messageRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->chatFactory = $chatsFactory;
        $this->chatResourceModel = $chatsResourceModel;
        $this->chatCollectionFactory = $chatsCollectionFactory;
        $this->messageFactory = $messageFactory;
        $this->messageRepository = $messageRepository;
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
            $chatCollection = $this->chatCollectionFactory->create();
            $chatCollection->addChatHashFilter($this->customerSession->getChatHash());
            $chat = $this->chatFactory->create();
            if ($chatCollection->getFirstItem()->getChatHash() === null) {
                $chat->setAuthorId($customerId)
                    ->setAuthorName($authorName)
                    ->setWebsiteId($websiteId)
                    ->setChatHash($this->customerSession->getChatHash());
                $this->chatResourceModel->save($chat);
            }

            $chatCollection = $this->chatCollectionFactory->create();
            $chatId = (int) $chatCollection->addChatHashFilter($this->customerSession->getChatHash())
                ->setOrder('chat_id', 'DESC')
                ->getFirstItem()
                ->getChatId();
            $chatMessage = $this->messageFactory->create();
            $chatMessage->setAuthorType($authorType)
                ->setAuthorId($customerId)
                ->setAuthorName($authorName)
                ->setMessage($this->getChatMessage())
                ->setWebsiteId($websiteId)
                ->setChatId($chatId)
                ->setChatHash($this->customerSession->getChatHash());
            $this->messageRepository->save($chatMessage);

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
