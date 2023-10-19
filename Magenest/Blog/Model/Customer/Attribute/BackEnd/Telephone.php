<?php
namespace Magenest\Blog\Model\Customer\Attribute\BackEnd;

use \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;

class Telephone extends AbstractBackend
{
    public function beforeSave($object)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();
        $phone = $object->getData($attrCode);
        if(substr($phone,0,3) == '+84')
        {
            $newPhone = str_replace('+84','0',$phone);
            $object->setData($attrCode,$newPhone);
        }
        return parent::beforeSave($object);
    }

    public function validate($object)
    {
        $attribute_code = $this->getAttribute()->getAttributeCode();
        $value = $object->getData($attribute_code);
        $vn_phone_pattern = '/^(0|\+84)[0-9]{9}$/i';
        if (!preg_match($vn_phone_pattern, $value)) {
            throw new \Magento\Framework\Exception\LocalizedException(__("Please enter correct VN phone number format"));
        }
        return true;
    }
}
