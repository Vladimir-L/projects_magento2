<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Model;

use Vladimirl\Chatter\Model\ResourceModel\ChatMessage as ResourceModelMessage;

/**
 * @method int getMessageId
 * @method $this setMessageId (int $messageId)
 * @method string getAuthorType
 * @method $this setAuthorType (string $authorType)
 * @method int getAuthorId
 * @method $this setAuthorId (int $customerId)
 * @method string getAuthorName
 * @method $this setAuthorName (string $customerName)
 * @method string getMessage
 * @method $this setMessage (string $messageId)
 * @method string getWebsiteId
 * @method $this setWebsiteId(int $websiteId)
 * @method string getChatId
 * @method $this setChatId(int $chatId)
 * @method string getChatHash
 * @method $this setChatHash(string $createdAt)
 * @method string getCreatedAt
 * @method $this setCreatedAt(int $createdAt)
 */
class ChatMessage extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->_init(ResourceModelMessage::class);
    }
}
