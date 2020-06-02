<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Api\Data;

/**
 * Must redefine the interface methods for \Magento\Framework\Reflection\DataObjectProcessor::buildOutputDataArray()
 * Must not declare return types to keep the interface consistent with the parent interface
 */
interface MessageSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \Vladimirl\Chatter\Api\Data\MessageInterface[]
     */
    public function getItems();

    /**
     * @param \Vladimirl\Chatter\Api\Data\MessageInterface[] $items
     * @return $this
     */
    public function setItems(array $items = null);
}
