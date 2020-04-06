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
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $customerSession;

    /**
     * Data constructor.
     * @param \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $chatMessageCollection
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\View\Element\Template\Context $context
     */
    public function __construct(
        \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $chatMessageCollection,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Element\Template\Context $context
    ) {
        $this->chatMessageCollection = $chatMessageCollection;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    /**
     * @return \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollection
     */
    public function getChatMessage()
    {
        $chatHash = (string) $this->customerSession->getChatHash();
        $messageCollection = $this->chatMessageCollection->create();
        $messageCollection->addChatHashFilter($chatHash);
        return $messageCollection
            ->setOrder('message_id', 'DESC')
            ->setPageSize(10);
    }
}
