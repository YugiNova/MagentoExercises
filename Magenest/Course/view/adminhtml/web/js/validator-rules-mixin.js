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
        $.validator.addMethod(
            'validate-url-format',
            function (value) {
                return /(https:\/\/www\.|http:\/\/www\.|https:\/\/|http:\/\/)?[a-zA-Z]{2,}(\.[a-zA-Z]{2,})(\.[a-zA-Z]{2,})?\/[a-zA-Z0-9]{2,}|((https:\/\/www\.|http:\/\/www\.|https:\/\/|http:\/\/)?[a-zA-Z]{2,}(\.[a-zA-Z]{2,})(\.[a-zA-Z]{2,})?)|(https:\/\/www\.|http:\/\/www\.|https:\/\/|http:\/\/)?[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}(\.[a-zA-Z0-9]{2,})?/i.test(value)
            },
            $.mage.__('Please enter right format of URL')
        );
        return target;
    };
});
