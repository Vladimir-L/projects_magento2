<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Vladimirl\Chatter\Api\Data\MessageInterface;
use Vladimirl\Chatter\Api\Data\MessageSearchResultInterface;

class MessageRepository implements \Vladimirl\Chatter\Api\MessageRepositoryInterface
{
    /**
     * @var \Magento\Framework\EntityManager\EntityManager $entityManager
     */
    private $entityManager;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $chatMessageCollectionFactory
     */
    private $chatMessageCollectionFactory;

    /**
     * @var \Vladimirl\Chatter\Api\Data\MessageSearchResultInterfaceFactory $searchResultsFactory
     */
    private $searchResultsFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     **/
    private $collectionProcessor;

    /**
     * @var \Vladimirl\Chatter\Api\Data\MessageInterfaceFactory $messageDataFactory
     */
    private $messageDataFactory;

    /**
     * MessageRepository constructor.
     * @param \Magento\Framework\EntityManager\EntityManager $entityManager
     * @param \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory
     * @param \Vladimirl\Chatter\Api\Data\MessageSearchResultInterfaceFactory $searchResultsFactory
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     * @param \Vladimirl\Chatter\Api\Data\MessageInterfaceFactory $messageDataFactory
     */
    public function __construct(
        \Magento\Framework\EntityManager\EntityManager $entityManager,
        \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $chatMessageCollectionFactory,
        \Vladimirl\Chatter\Api\Data\MessageSearchResultInterfaceFactory $searchResultsFactory,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor,
        \Vladimirl\Chatter\Api\Data\MessageInterfaceFactory $messageDataFactory
    ) {
        $this->entityManager = $entityManager;
        $this->chatMessageCollectionFactory = $chatMessageCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->messageDataFactory = $messageDataFactory;
    }

    /**
     * @param MessageInterface $message
     * @return MessageInterface
     * @throws CouldNotSaveException
     */
    public function save(MessageInterface $message): MessageInterface
    {
        try {
            $this->entityManager->save($message);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $message;
    }

    /**
     * @param int $messageId
     * @return MessageInterface
     */
    public function get(int $messageId): MessageInterface
    {
        $message = $this->messageDataFactory->create();
        return $this->entityManager->load($message, $messageId);
    }

    public function getList(SearchCriteriaInterface $searchCriteria): MessageSearchResultInterface
    {
        $chatMessageCollection = $this->chatMessageCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $chatMessageCollection);
        $chatMessages = [];

        /** @var ChatMessage $chatMessage */
        foreach ($chatMessageCollection as $chatMessage) {
            $chatMessages[] = $chatMessage->getData();
        }
        /** @var MessageSearchResultInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setTotalCount($chatMessageCollection->getSize());
        $searchResults->setItems($chatMessages);

        return $searchResults;
    }

    /**
     * @param MessageInterface $message
     * @return bool
     */
    public function delete(MessageInterface $message): bool
    {
        try {
            $this->entityManager->delete($message);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param int $messageId
     * @return bool
     */
    public function deleteById(int $messageId): bool
    {
        return $this->delete($this->get($messageId));
    }
}
