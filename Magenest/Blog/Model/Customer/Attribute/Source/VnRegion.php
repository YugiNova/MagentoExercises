<?php

namespace Magenest\Blog\Model\Customer\Attribute\Source;

class VnRegion extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['label' => __('Mien Bac'), 'value' => 1],
                ['label' => __('Mien Trung'), 'value' => 2],
                ['label' => __('Mien Nam'), 'value' => 3]
            ];
        }
        return $this->_options;
    }
}
