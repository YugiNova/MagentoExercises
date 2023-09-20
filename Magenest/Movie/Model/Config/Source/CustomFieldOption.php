<?php

namespace Magenest\Movie\Model\Config\Source;

class CustomFieldOption implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Retrieve Custom Option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Show')],
            ['value' => 2, 'label' => __('Not Show')]
        ];
    }
}
