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
     * @return $message array
     */
    public function getChatMessage()
    {
        $messageCollection = $this->chatMessageCollection->create();
        return array_reverse($messageCollection
            ->setOrder('message_id', 'DESC')
            ->setPageSize(10)
            ->getColumnValues('message'));
    }
}
