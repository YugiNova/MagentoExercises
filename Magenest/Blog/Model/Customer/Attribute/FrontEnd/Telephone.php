<?php

namespace Magenest\Blog\Model\Customer\Attribute\FrontEnd;

use Magento\Eav\Model\Entity\Attribute\Frontend\AbstractFrontend;
use Magento\Config\Block\System\Config\Form\Field;

class Telephone extends AbstractFrontend
{
    public function getValue(\Magento\Framework\DataObject $object)
    {
        $attribute_code = $this->getAttribute()->getAttributeCode();
        $value = $object->getData($attribute_code);
        return nl2br(htmlspecialchars($value));
    }
}
