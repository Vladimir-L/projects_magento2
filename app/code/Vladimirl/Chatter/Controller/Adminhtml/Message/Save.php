<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Controller\Adminhtml\Message;

use Magento\Framework\Controller\ResultFactory;

class Save extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = 'Message';

    /**
     * @var \Magento\Backend\Model\Auth\Session $authSession
     */
    protected $authSession;

    /**
     * @var \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory
     */
    protected $chatMessageFactory;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\ChatMessage $messageResourceModel
     */
    protected $messageResourceModel;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory
     * @param \Vladimirl\Chatter\Model\ResourceModel\ChatMessage $messageResourceModel
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory,
        \Vladimirl\Chatter\Model\ResourceModel\ChatMessage $messageResourceModel
    ) {
        $this->authSession = $authSession;
        $this->chatMessageFactory = $chatMessageFactory;
        $this->messageResourceModel = $messageResourceModel;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $chatMessage = $this->chatMessageFactory->create();
            $request = $this->getRequest();

            $authorType = 'Administrator';
            $authorId = $this->authSession->getUser()->getId();
            $authorName = $this->authSession->getUser()->getEmail();
            $chatId = $request->getParam('chat_id');
            $message = $request->getParam('input_message');
            $websiteId = $request->getParam('website_id');
            $chatHash = $request->getParam('chat_hash');

            $chatMessage->setAuthorType($authorType)
                ->setAuthorId($authorId)
                ->setAuthorName($authorName)
                ->setMessage($message)
                ->setWebsiteId($websiteId)
                ->setChatId($chatId)
                ->setChatHash($chatHash);
            $this->messageResourceModel->save($chatMessage);
        } catch (\Exception $e) {
            $e->getMessage();
        }
        $this->messageManager->addSuccessMessage(__('Message successfully sent!'));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/index/edit/id/' . $chatId);
    }
}
