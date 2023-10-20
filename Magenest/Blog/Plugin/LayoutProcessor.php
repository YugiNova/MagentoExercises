<?php
namespace Magenest\Blog\Plugin;

use Magento\Checkout\Block\Checkout\LayoutProcessor as Layout;
use Magento\Eav\Model\Config as EavConfig;

class LayoutProcessor
{
    private $eavConfig;

    public function __construct(EavConfig $eavConfig)
    {
        $this->eavConfig = $eavConfig;
    }

    public function afterProcess(Layout $subject, $jsLayout)
    {
       $this->addFieldToShippingAddressForm($jsLayout);
       $this->addFieldToBillingAddress($jsLayout);

       return $jsLayout;
    }

    public function addFieldToShippingAddressForm(&$jsLayout)
    {
        $customAttributeCode = 'vn_region';
        $customField = [
            'component' => 'Magento_Ui/js/form/element/select',
            'config' => [
                // customScope is used to group elements within a single form (e.g. they can be validated separately)
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select',
//                'tooltip' => [
//                    'description' => 'Regions in Viet Nam',
//                ],
            ],
            'dataScope' => 'shippingAddress.custom_attributes' . '.' . $customAttributeCode,
            'label' => 'VN Region',
            'provider' => 'checkoutProvider',
            'sortOrder' => 0,
            'validation' => [
                'required-entry' => true
            ],
            'options' => $this->getVnRegionOptions(),
            'filterBy' => null,
            'visible' => true,
            'value' => 1 // value field is used to set a default value of the attribute
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'][$customAttributeCode] = $customField;
        return $jsLayout;
    }

    public function addFieldToBillingAddress(&$jsLayout)
    {
        // Loop all payment methods (because billing address is appended to the payments)
        $configuration = $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'];
        foreach ($configuration as $paymentGroup => $groupConfig) {
            if (isset($groupConfig['component']) AND $groupConfig['component'] === 'Magento_Checkout/js/view/billing-address') {

                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['payments-list']['children'][$paymentGroup]['children']['form-fields']['children']['vn_region'] = [
                    'component' => 'Magento_Ui/js/form/element/select',
                    'config' => [
                        'template' => 'ui/form/field',
                        'elementTmpl' => 'ui/form/element/select',
                        'id' => 'vn_region',
                    ],
                    'dataScope' => $groupConfig['dataScopePrefix'] . '.vn_region',
                    'label' => __('VN Region'),
                    'provider' => 'checkoutProvider',
                    'visible' => true,
                    'validation' => [
                        'required-entry' => true,
                    ],
                    'sortOrder' => 1,
                    'id' => 'vn_region',
                    'options' => $this->getVnRegionOptions(),
                ];
            }
        }

        return $jsLayout;
    }

    public function getVnRegionOptions()
    {
        $options = $this->eavConfig->getAttribute('customer_address','vn_region')->getSource()->getAllOptions();
        $result = [];
        foreach ($options as $option)
        {
            $result[] = [
                'label' => $option['label'],
                'value' => $option['value'],
            ];
        }
        return $result;
    }
}
