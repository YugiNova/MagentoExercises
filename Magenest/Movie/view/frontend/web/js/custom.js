define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($,modal) {
    'use strict';
    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        title: 'Login Modal',
        buttons: []
    };

    modal(options, $('#popup-modal'));
    $("#modal-button").on('click',function(){
        $("#popup-modal").modal("openModal");
    });
});

