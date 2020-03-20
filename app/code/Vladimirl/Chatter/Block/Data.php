<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Data extends Template
{
    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $customerSession;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory
     */
    protected $messageCollectionFactory;

    /**
     * Data constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $messageCollectionFactory
     * @param Context $context
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $messageCollectionFactory,
        Context $context
    ) {
        $this->customerSession = $customerSession;
        $this->messageCollectionFactory = $messageCollectionFactory;
        parent::__construct($context);
    }

    /**
     * @return array $message
     */
    public function getChatMessage(): array
    {
        $chatHash = $this->customerSession->getChatHash();
        $messageCollection = $this->messageCollectionFactory->create();
        $messageCollection->addChatHashFilter($chatHash);
        return array_reverse($messageCollection
            ->setOrder('message_id', 'DESC')
            ->setPageSize(10)
            ->getColumnValues('message'));
    }
}
