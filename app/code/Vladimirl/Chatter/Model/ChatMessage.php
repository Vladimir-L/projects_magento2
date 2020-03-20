<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Model;

use Magento\Framework\Model\AbstractModel;
use Vladimirl\Chatter\Model\ResourceModel\ChatMessage as ResourceModel;

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
 * @method string getChatHash
 * @method $this setChatHash(string $chatHash)
 */
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
