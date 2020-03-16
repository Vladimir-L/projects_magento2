<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Data extends Template
{
    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory
     */
    protected $chatMessageCollection;

    /**
     * Data constructor.
     * @param \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $messageFactory
     * @param Context $context
     */
    public function __construct(
        \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $messageFactory,
        Context $context
    ) {
        $this->chatMessageCollection = $messageFactory;
        parent::__construct($context);
    }

    /**
     * @return \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollection
     */
    public function getChatMessage()
    {
        $_messageCollection = $this->chatMessageCollection->create();
        $_messageCollection->setOrder('message_id', 'DESC')->setPageSize(10);
//        $_messageCollection->getSelect()->limit(10)->order()
        return $_messageCollection;
    }
}
