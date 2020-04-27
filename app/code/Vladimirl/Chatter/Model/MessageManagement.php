<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Model;

use Vladimirl\Chatter\Api\Data\MessageInterface;

class MessageManagement
{
    /**
     * @var \Vladimirl\Chatter\Model\MessageRepository $messageRepository
     */
    private $messageRepository;

    /**
     * @var \Magento\Framework\Api\FilterBuilder $filterBuilder
     */
    private $filterBuilder;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * ChatMessages constructor.
     * @param \Vladimirl\Chatter\Model\MessageRepository $messageRepository
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \Vladimirl\Chatter\Model\MessageRepository $messageRepository,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->messageRepository = $messageRepository;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param int $messageId
     * @param int $websiteId
     * @return MessageInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getChatMessages(int $messageId, int $websiteId): array
    {
        $this->searchCriteriaBuilder->addFilters([
            $this->filterBuilder
                ->setField('message_id')
                ->setValue($messageId)
                ->setConditionType('eq')
                ->create(),
            $this->filterBuilder
                ->setField('website_id')
                ->setValue($websiteId)
                ->setConditionType('eq')
                ->create()
        ]);

        return $this->messageRepository->getList($this->searchCriteriaBuilder->create())
            ->getItems();
    }
}
