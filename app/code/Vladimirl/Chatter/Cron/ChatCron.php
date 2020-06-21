<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Cron;

class ChatCron
{
    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatCollectionFactory $chatCollectionFactory
     */
    private $chatCollectionFactory;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $chatMessageCollectionFactory
     */
    private $chatMessageCollectionFactory;

    /**
     * @var \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    private $transactionFactory;

    /**
     * @var \Psr\Log\LoggerInterface $logger
     */
    private $logger;

    /**
     * ChatCron constructor.
     * @param \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatCollectionFactory $chatCollectionFactory
     * @param \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $chatMessageCollectionFactory
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatCollectionFactory $chatCollectionFactory,
        \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $chatMessageCollectionFactory,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->chatCollectionFactory = $chatCollectionFactory;
        $this->chatMessageCollectionFactory = $chatMessageCollectionFactory;
        $this->transactionFactory = $transactionFactory;
        $this->logger = $logger;
    }

    public function execute()
    {
        try {
            $chatCollection = $this->chatCollectionFactory->create();
            $transaction = $this->transactionFactory->create();

            foreach ($chatCollection as $chat) {
                if ((int) $chat->getIsActive() !== 1) {
                    $chat->setPriority('REGULAR');
                    $transaction->addObject($chat);
                } else {
                    $chatMessageCollection = $this->chatMessageCollectionFactory->create();
                    $chatId = (int) $chat->getChatId();
                    $lastItem = $chatMessageCollection->addChatIdFilter($chatId)->getLastItem();
                    $authorType = (string) $lastItem->getAuthorType();
                    $createdAt = (string) $lastItem->getCreatedAt();
                    if ($chat->getPriority() !== 'IMMEDIATE') {
                        if ($authorType !== 'Administrator' && strtotime($createdAt) <= time() - (60 * 30)) {
                            $chat->setPriority('WAITING');
                            $transaction->addObject($chat);
                        } else {
                            $chat->setPriority('REGULAR');
                            $transaction->addObject($chat);
                        }
                    }
                }
            }
            $transaction->save();
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
