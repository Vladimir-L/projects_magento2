<?php
declare(strict_types=1);

namespace Vladimirl\Chatter\Ui\Component\Listing\Column;

class ChatStatus implements \Magento\Framework\Data\OptionSourceInterface
{
    const ACTIVE = 1;
    const INACTIVE  = 0;

    public static function getOptionArray()
    {
        return [
            self::ACTIVE => __('Active'),
            self::INACTIVE => __('Inactive')
        ];
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $res = [];

        foreach (self::getOptionArray() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
        }

        return $res;
    }
}
