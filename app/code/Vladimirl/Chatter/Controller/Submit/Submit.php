<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Controller\Submit;

use Magento\Framework\Controller\Result\Json as JsonResult;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;

class Submit extends Action implements
    \Magento\Framework\App\Action\HttpPostActionInterface
{
    /**
     * @var \Vladimirl\Chatter\Model\ChatMessage
     */
    private $chatMessageFactory;
    /**
     * @var \Magento\Framework\DB\TransactionFactory
     */
    private $transactionFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * Submit constructor.
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Vladimirl\Chatter\Model\ChatMessageFactory $chatMessageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->chatMessageFactory = $chatMessageFactory;
        $this->transactionFactory = $transactionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|JsonResult|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $transaction = $this->transactionFactory->create();
        try {
            $chatMessage = $this->chatMessageFactory->create();
            $request = $this->getRequest()->getParam('text_request');
            $websiteId = (int)$this->storeManager->getWebsite()->getId();
            $authorType = 'customer';
            $autorName = 'anonimus';
            $chatMessage->setAuthorType($authorType)
                ->setAuthorId(1)
                ->setAuthorName($autorName)
                ->setMessage($request)
                ->setWebsiteId($websiteId)
                ->setChatHash($request);
            $transaction->addObject($chatMessage);
            $transaction->save();
            $message = __('Our administrator will contact you soon!');
        } catch (\Exception $e) {
            $message = __('Error!');
        }
        /** @var JsonResult $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData([
            'message' => $message,
            'messageOutput' => $request
        ]);
        return $response;
    }
}
