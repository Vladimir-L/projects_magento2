<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ChatMessage extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init('vladimirl_chatter', 'message_id');
    }
}
