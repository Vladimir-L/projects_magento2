<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Block;

class Data extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $chatMessageCollection
     */
    protected $chatMessageCollection;

    /**
     * Data constructor.
     * @param \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $chatMessageCollection
     * @param \Magento\Framework\View\Element\Template\Context $context
     */
    public function __construct(
        \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $chatMessageCollection,
        \Magento\Framework\View\Element\Template\Context $context
    ) {
        $this->chatMessageCollection = $chatMessageCollection;
        parent::__construct($context);
    }

    /**
     * @return \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollection
     */
    public function getChatMessage()
    {
        $messageCollection = $this->chatMessageCollection->create();
        return $messageCollection
            ->setOrder('message_id', 'DESC')
            ->setPageSize(10);
    }
}
