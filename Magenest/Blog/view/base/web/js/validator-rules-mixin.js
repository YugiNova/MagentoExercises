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

        $.validator.addMethod(
            'validate-cronjob-expression',
            function (value) {
                return /^(\*|([0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])|\*\/([0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])) (\*|([0-9]|1[0-9]|2[0-3])|\*\/([0-9]|1[0-9]|2[0-3])) (\*|([1-9]|1[0-9]|2[0-9]|3[0-1])|\*\/([1-9]|1[0-9]|2[0-9]|3[0-1])) (\*|([1-9]|1[0-2])|\*\/([1-9]|1[0-2])) (\*|([0-6])|\*\/([0-6]))$/i.test(value)
            },
            $.mage.__('Please enter right format of cron expression')
        );
        return target;
    };
});
