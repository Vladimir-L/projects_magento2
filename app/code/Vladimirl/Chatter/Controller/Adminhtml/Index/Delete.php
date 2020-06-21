<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\DB\Transaction;

class Delete extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = 'Index';

    /**
     * @var \Magento\Ui\Component\MassAction\Filter $filter
     */
    private $filter;

    /**
     * @var \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatCollectionFactory $collectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    private $transactionFactory;

    /**
     * Delete constructor.
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatCollectionFactory $collectionFactory
     * @param \Magento\Framework\DB\TransactionFactory $transaction
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Vladimirl\Chatter\Model\ResourceModel\Collection\ChatCollectionFactory $collectionFactory,
        \Magento\Framework\DB\TransactionFactory $transaction,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->transactionFactory = $transaction;
        parent::__construct($context);
    }

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    public function execute()
    {
        $transaction = $this->transactionFactory->create();
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->count();

        foreach ($collection as $message) {
            $transaction->addObject($message);
        }

        $transaction->delete();
        $this->messageManager->addSuccessMessage(__('%1 chat(s) have been deleted.', $collectionSize));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');
    }
}
