require(
    [
        'Magento_Ui/js/lib/validation/validator',
        'jquery',
        'mage/translate'
    ], function(validator, $){

        //Validate rating input in movie form
        validator.addRule(
            'validate-rating-input',
            function (value) {
                console.log(value)
                return parseInt(value) > 5;
            },
            $.mage.__('Rating value must higher than 5')
        );

        //Validate actor select in movie form
        validator.addRule(
            'validate-actor-input',
            function (value) {
                console.log(value)
                return Array.isArray(value) && value.length > 0;
            },
            $.mage.__('Movie must have actor(s)')
        );
    });
