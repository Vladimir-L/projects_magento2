<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\CustomerData;

class ChatMessages implements \Magento\Customer\CustomerData\SectionSourceInterface
{
    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $customerSession;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $messageCollectionFactory
     */
    private $messageCollectionFactory;

    /**
     * ChatMessages constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $messageCollectionFactory
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory $messageCollectionFactory
    ) {
        $this->customerSession = $customerSession;
        $this->messageCollectionFactory = $messageCollectionFactory;
    }

    /**
     * @return array
     */
    public function getSectionData(): array
    {
        $chatHash = (string) $this->customerSession->getChatHash();
        $messageCollection = $this->messageCollectionFactory->create();
        $messageCollection->addChatHashFilter($chatHash);
        return array_reverse($messageCollection
            ->setOrder('message_id', 'DESC')
            ->setPageSize(10)
            ->getData());
    }
}
