<?php
namespace Vladimirl\Chatter\Ui\Component\DataProvider\Chat\Form;

use Vladimirl\Chatter\Model\ResourceModel\Collection\ChatCollectionFactory;
use Vladimirl\Chatter\Model\Chat;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $_loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ChatCollectionFactory $chatCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $chatCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $value) {
            $this->_loadedData[$value->getId()] = $value->getData();
        }
        return $this->_loadedData;
    }
}
