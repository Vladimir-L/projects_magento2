<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Controller\Submit;

use Magento\Framework\Controller\Result\Json as JsonResult;
use Magento\Framework\Controller\ResultFactory;

class Submit extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpPostActionInterface
{
    /**
     * @var \Vladimirl\Chatter\Model\ChatMessage $chatMessageFactory
     */
    private $chatMessageFactory;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\ChatMessage $resourceModel
     */
    private $resourceModel;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * Submit constructor.
     * @param \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory
     * @param \Vladimirl\Chatter\Model\ResourceModel\ChatMessage $resourceModel
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory,
        \Vladimirl\Chatter\Model\ResourceModel\ChatMessage $resourceModel,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->chatMessageFactory = $chatMessageFactory;
        $this->resourceModel = $resourceModel;
        $this->storeManager = $storeManager;
    }

    /**
     * @return string
     */
    public function generateHash(): string
    {
        return uniqid('', false);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|JsonResult|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $request = $this->getRequest();
            $websiteId = (int)$this->storeManager->getWebsite()->getId();
            $authorType = 'customer';
            $authorName = 'anonymous';
            $chatMessage = $this->chatMessageFactory->create();
            $chatMessage->setAuthorType($authorType)
                ->setAuthorId(1)
                ->setAuthorName($authorName)
                ->setMessage($request->getParam('text_request'))
                ->setWebsiteId($websiteId)
                ->setChatHash($this->generateHash());
            $this->resourceModel->save($chatMessage);
            $message = __('Our administrator will contact you soon!');
        } catch (\Exception $e) {
            $message = __('Error!');
        }
        /** @var JsonResult $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData([
            'message' => $message,
            'messageOutput' => $request->getParam('text_request'),
            'createdAt' => date("Y-m-d H:i:s"),
            'authorType' => $authorType
        ]);
        return $response;
    }
}
