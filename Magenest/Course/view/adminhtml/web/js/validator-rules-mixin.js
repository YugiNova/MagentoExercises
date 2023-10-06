define([
    'jquery'
], function ($) {
    'use strict';
    return function (target) {
        let message = '';
        $.validator.addMethod(
            'validate-time-format',
            function (value) {
                return /^(([0-1][0-9])|(2[0-3])):[0-5][0-9]:[0-5][0-9]$/i.test(value)
            },
            $.mage.__('Please enter right format of time')
        );
        return target;
    };
});
