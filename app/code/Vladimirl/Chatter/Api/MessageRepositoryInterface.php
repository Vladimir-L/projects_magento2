<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Api;

use Vladimirl\Chatter\Api\Data\MessageInterface;
use Vladimirl\Chatter\Api\Data\MessageSearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface MessageRepositoryInterface
{
    /**
     * @param MessageInterface $message
     * @return \Vladimirl\Chatter\Api\Data\MessageInterface
     */
    public function save(MessageInterface $message): MessageInterface;

    /**
     * @param int $messageId
     * @return MessageInterface
     */
    public function get(int $messageId): MessageInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Vladimirl\Chatter\Api\Data\MessageSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): MessageSearchResultInterface;

    /**
     * @param MessageInterface $message
     * @return bool
     */
    public function delete(MessageInterface $message): bool;

    /**
     * @param int $messageId
     * @return bool
     */
    public function deleteById(int $messageId): bool;
}
