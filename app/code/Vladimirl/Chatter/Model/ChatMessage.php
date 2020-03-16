<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Model;

use Magento\Framework\Model\AbstractModel;
use Vladimirl\Chatter\Model\ResourceModel\ChatMessage as ResourceModel;

class ChatMessage extends AbstractModel
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->_init(ResourceModel::class);
    }
}
