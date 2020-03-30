<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Model\ResourceModel\Collection;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Vladimirl\Chatter\Model\ChatMessage as Model;
use Vladimirl\Chatter\Model\ResourceModel\ChatMessage as ResourceModel;

class ChatMessageCollection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(Model::class, ResourceModel::class);
    }

    /**
     * @param $chatHash
     * @return $this
     */
    public function addChatHashFilter($chatHash): self
    {
        return $this->addFieldToFilter('chat_hash', $chatHash);
    }

    /**
     * @param int $customerId
     * @return $this
     */
    public function addCustomerIdFilter(int $customerId): self
    {
        return $this->addFieldToFilter('author_id', $customerId);
    }

    /**
     * @param $authorType
     * @return $this
     */
    public function addAuthorTypeFilter($authorType): self
    {
        return $this->addFieldToFilter('author_type', $authorType);
    }
}
