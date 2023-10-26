var config = {
    map: {
        '*': {
            'Magento_Checkout/js/view/minicart': 'Magenest_Blog/js/minicart'
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-shipping-information': {
                'Magenest_Blog/js/set-shipping-information-mixin': true
            },
        }
    }
};
