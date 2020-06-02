<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\CustomerData;

use Vladimirl\Chatter\Model\MessageManagement;

class ChatMessages implements \Magento\Customer\CustomerData\SectionSourceInterface
{
    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $customerSession;

    /**
     * @var \Vladimirl\Chatter\Model\MessageManagement $messageManagement
     */
    private $messageManagement;

    /**
     * ChatMessages constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Vladimirl\Chatter\Model\MessageManagement $messageManagement
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Vladimirl\Chatter\Model\MessageManagement $messageManagement
    ) {
        $this->customerSession = $customerSession;
        $this->messageManagement = $messageManagement;
    }

    /**
     * @return array
     */
    public function getSectionData(): array
    {
        $chatHash = (string) $this->customerSession->getChatHash();
        $messageList = $this->messageManagement->getChatMessages($chatHash);
        $messages = array_slice($messageList, -10, 10, false);
        return ['list' => $messages];
    }
}
