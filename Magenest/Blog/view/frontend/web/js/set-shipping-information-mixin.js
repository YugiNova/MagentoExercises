/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'uiComponent'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();
            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }
            var attribute = shippingAddress.customAttributes.find(
                function (element) {
                    console.log(element)
                    return element.attribute_code === 'vn_region';
                }
            );
            shippingAddress['extension_attributes']['vn_region'] = attribute.value;
            // pass execution to original action ('Magento_Checkout/js/action/set-shipping-information')
            console.log(shippingAddress)
            return originalAction();
        });
    };
});
