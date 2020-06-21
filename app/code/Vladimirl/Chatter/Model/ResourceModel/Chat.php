<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Model\ResourceModel;

class Chat extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init('vladimirl_chatter_chats', 'chat_id');
    }
}
