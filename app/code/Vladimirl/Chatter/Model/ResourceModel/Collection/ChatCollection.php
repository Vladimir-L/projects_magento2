<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Model\ResourceModel\Collection;

use Vladimirl\Chatter\Model\Chat as Model;
use Vladimirl\Chatter\Model\ResourceModel\Chat as ResourceModel;

class ChatCollection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'chat_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
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

}
