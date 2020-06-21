<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Helper;

class MessageGenerator
{
    /**
     * @var \Vladimirl\Chatter\Model\ChatFactory
     */
    protected $chatFactory;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\Chat
     */
    protected $chatResourceModel;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatCollectionFactory $chatCollectionFactory
     */
    protected $chatCollectionFactory;

    /**
     * @var \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory
     */
    protected $chatMessageFactory;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\ChatMessage $chatMessageResourceModel
     */
    protected $chatMessageResourceModel;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * @var \Psr\Log\LoggerInterface $logger
     */
    protected $logger;

    /**
     * GenerateMessages constructor.
     * @param \Vladimirl\Chatter\Model\ChatFactory $chatFactory
     * @param \Vladimirl\Chatter\Model\ResourceModel\Chat $chatResourceModel
     * @param \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatCollectionFactory $chatCollectionFactory
     * @param \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory
     * @param \Vladimirl\Chatter\Model\ResourceModel\ChatMessage $chatMessageResourceModel
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Vladimirl\Chatter\Model\ChatFactory $chatFactory,
        \Vladimirl\Chatter\Model\ResourceModel\Chat $chatResourceModel,
        \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatCollectionFactory $chatCollectionFactory,
        \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory,
        \Vladimirl\Chatter\Model\ResourceModel\ChatMessage $chatMessageResourceModel,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->chatFactory = $chatFactory;
        $this->chatResourceModel = $chatResourceModel;
        $this->chatCollectionFactory = $chatCollectionFactory;
        $this->chatMessageFactory = $chatMessageFactory;
        $this->chatMessageResourceModel = $chatMessageResourceModel;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * generate chat messages
     */
    public function generateMessage(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            try {
                $chat = $this->chatFactory->create();
                $websiteId = (int)$this->storeManager->getWebsite()->getId();
                $minTime = time() - (60 * 120);
                $maxTime = time();
                $chat->setAuthorId(0)
                    ->setAuthorName('RandomName')
                    ->setPriority('REGULAR')
                    ->setIsActive(1)
                    ->setWebsiteId($websiteId)
                    ->setChatHash($this->generateHash())
                    ->setCreatedAt(random_int($minTime, $maxTime));
                $this->chatResourceModel->save($chat);

                $chatCollection = $this->chatCollectionFactory->create();
                $chatId = (int) $chatCollection->getLastItem()->getChatId();
                $chatHash = (string) $chatCollection->getLastItem()->getChatHash();
                $createdAt = (string) $chatCollection->getLastItem()->getCreatedAt();
                $chatMessage = $this->chatMessageFactory->create();
                $chatMessage->setAuthorType('Guest')
                    ->setAuthorId(0)
                    ->setAuthorName('RandomName')
                    ->setMessage('Lorem ipsum dolor sit amet')
                    ->setWebsiteId($websiteId)
                    ->setChatId($chatId)
                    ->setChatHash($chatHash)
                    ->setCreatedAt(strtotime($createdAt));
                $this->chatMessageResourceModel->save($chatMessage);
            } catch (\Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        }
    }

    /**
     * @return string
     */
    public function generateHash(): string
    {
        return uniqid('', false);
    }
}
