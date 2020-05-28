<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Model;

use Vladimirl\Chatter\Model\ResourceModel\Chat as ResourceModel;

/**
 * @method $this setChatId (int $chatId)
 * @method string getChatId
 * @method string getChatHash
 * @method $this setChatHash(string $chatHash)
 * @method int getAuthorId
 * @method $this setAuthorId (int $customerId)
 * @method string getAuthorName
 * @method $this setAuthorName (string $customerName)
 */
class Chat extends \Magento\Framework\Model\AbstractModel
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
