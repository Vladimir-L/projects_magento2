<?php
namespace Vladimirl\Chatter\Ui\Component\DataProvider\Message\Listing;

use Vladimirl\Chatter\Model\ResourceModel\Collection\ChatMessageCollectionFactory;
use Magento\Framework\App\RequestInterface;
use Vladimirl\Chatter\Model\ChatMessage;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;

    /**
     * @var RequestInterface
     */
    protected $request;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ChatMessageCollectionFactory $chatMessageCollectionFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $chatMessageCollectionFactory->create();
        $this->request = $request;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection->addFieldToFilter('chat_id', $this->request->getParam('chat_id'));
    }
}
