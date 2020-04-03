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
}
