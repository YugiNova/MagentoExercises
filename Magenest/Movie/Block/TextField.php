<?php
namespace Magenest\Movie\Block;

use Magento\Framework\Data\Form\Element\AbstractElement;

class TextField extends \Magento\Config\Block\System\Config\Form\Field
{
    protected function _getElementHtml(AbstractElement $element)
    {
        $element->setData('readonly',1);
        return $element->getElementHtml();
    }
}
