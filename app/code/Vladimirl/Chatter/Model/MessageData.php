<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Model;

use Vladimirl\Chatter\Api\Data\MessageInterface;

class MessageData extends \Magento\Framework\Api\AbstractSimpleObject implements
    \Vladimirl\Chatter\Api\Data\MessageInterface
{
    /**
     * @return int
     */
    public function getMessageId(): int
    {
        return (int) $this->_get(MessageInterface::MESSAGE_ID);
    }

    /**
     * @param int $messageId
     * @return $this|MessageInterface
     */
    public function setMessageId(int $messageId): MessageInterface
    {
        $this->setData(self::MESSAGE_ID, $messageId);
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorType(): string
    {
        return (string) $this->_get(MessageInterface::AUTHOR_TYPE);
    }

    /**
     * @param string $authorType
     * @return $this|MessageInterface
     */
    public function setAuthorType(string $authorType): MessageInterface
    {
        $this->setData(self::AUTHOR_TYPE, $authorType);
        return $this;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return (int) $this->_get(MessageInterface::AUTHOR_ID);
    }

    /**
     * @param int $authorId
     * @return $this|MessageInterface
     */
    public function setAuthorId(int $authorId): MessageInterface
    {
        $this->setData(self::AUTHOR_ID, $authorId);
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorName(): string
    {
        return (string) $this->_get(MessageInterface::AUTHOR_NAME);
    }

    /**
     * @param string $authorName
     * @return $this|MessageInterface
     */
    public function setAuthorName(string $authorName): MessageInterface
    {
        $this->setData(self::AUTHOR_NAME, $authorName);
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return (string) $this->_get(MessageInterface::MESSAGE);
    }

    /**
     * @param string $message
     * @return $this|MessageInterface
     */
    public function setMessage(string $message): MessageInterface
    {
        $this->setData(self::MESSAGE, $message);
        return $this;
    }

    /**
     * @return int
     */
    public function getWebsiteId(): int
    {
        return (int) $this->_get(MessageInterface::WEBSITE_ID);
    }

    /**
     * @param int $websiteId
     * @return $this|MessageInterface
     */
    public function setWebsiteId(int $websiteId): MessageInterface
    {
        $this->setData(self::WEBSITE_ID, $websiteId);
        return $this;
    }

    /**
     * @return string
     */
    public function getChatHash(): string
    {
        return (string) $this->_get(MessageInterface::CHAT_HASH);
    }

    /**
     * @param string $chatHash
     * @return $this|MessageInterface
     */
    public function setChatHash(string $chatHash): MessageInterface
    {
        $this->setData(self::CHAT_HASH, $chatHash);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->_get(MessageInterface::CREATED_AT);
    }

    /**
     * @param string|null $createdAt
     * @return $this|MessageInterface
     */
    public function setCreatedAt(string $createdAt = null): MessageInterface
    {
        $this->setData(self::CREATED_AT, $createdAt);
        return $this;
    }
}
