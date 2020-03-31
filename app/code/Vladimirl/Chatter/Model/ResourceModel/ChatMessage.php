<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Model\ResourceModel;

class ChatMessage extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init('vladimirl_chatter', 'message_id');
    }
}
