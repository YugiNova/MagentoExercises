define([
    'jquery'
], function ($) {
    'use strict';
    return function (target) {
        $.validator.addMethod(
            'validate-phone-vn',
            function (value) {
                return /^(0|\+84)[0-9]{9}$/i.test(value)
            },
            $.mage.__('Please enter right format of VN phone number')
        );
        return target;
    };
});
