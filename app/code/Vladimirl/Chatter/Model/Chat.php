<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Model;

use Vladimirl\Chatter\Model\ResourceModel\Chat as ResourceModelChat;

/**
 * @method int getChatId
 * @method $this setChatId (int $chatId)
 * @method int getAuthorId
 * @method $this setAuthorId (int $customerId)
 * @method string getAuthorName
 * @method $this setAuthorName (string $customerName)
 * @method string getPriority
 * @method $this setPriority(string $priority)
 * @method int getIsActive
 * @method $this setIsActive (int $status)
 * @method int getWebsiteId
 * @method $this setWebsiteId (int $websiteId)
 * @method string getChatHash
 * @method $this setChatHash(string $chatHash)
 * @method string getCreatedAt
 * @method $this setCreatedAt(int $createdAt)
 */
class Chat extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->_init(ResourceModelChat::class);
    }
}
