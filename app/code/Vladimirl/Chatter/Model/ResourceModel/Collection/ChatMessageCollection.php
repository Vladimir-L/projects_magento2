<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Model\ResourceModel\Collection;

use Vladimirl\Chatter\Model\ChatMessage as Model;
use Vladimirl\Chatter\Model\ResourceModel\ChatMessage as ResourceModel;

class ChatMessageCollection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(Model::class, ResourceModel::class);
    }

    /**
     * @param string $chatHash
     * @return $this
     */
    public function addChatHashFilter(string $chatHash): self
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
     * @param string $authorType
     * @return $this
     */
    public function addAuthorTypeFilter(string $authorType): self
    {
        return $this->addFieldToFilter('author_type', $authorType);
    }
}
