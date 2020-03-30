define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'Magento_Ui/js/modal/alert'
], function ($, ko, Component, customerData, alert) {
    'use strict';

    return Component.extend({
        defaults: {
            action: '',
            shouldShowMessage: true,
        },

        formChatterClass: ko.observable(''),
        text_request: ko.observable(''),

        /** @inheritdoc */
        initialize: function () {
            this._super();
            $(document).on(
                'vladimirL_chatter_openChatter.vladimirL_chatter',
                $.proxy(this.openFormChatter, this)
            );
            var chatMessages = customerData.get('vladimrl-chatter');
            this.messages = Object.values(chatMessages().list) || [];

        },

        /**
         * Open form chatter
         */
        openFormChatter: function () {
            this.formChatterClass('active');
        },

        /**
         * Close form chatter
         */
        closeFormChatter: function () {
            this.formChatterClass('');
            $(document).trigger('vladimirL_chatter_closeChatter');
        },

        /**
         * Clear text message after save
         */
        clearMessage: function () {
            this.text_request(null);
        },

        /**
         * Save message with ajax
         */
        saveChatMessage: function () {
            var payload = {
                text_request: this.text_request,
                'form_key': $.mage.cookies.get('form_key'),
                isAjax: 1
            };

            $.ajax({
                url: this.action,
                data: payload,
                type: 'post',
                dataType: 'json',
                context: this,

                /** @inheritdoc */
                beforeSend: function () {
                    $('body').trigger('processStart');
                },

                /** @inheritdoc */
                success: function (response) {
                    $('body').trigger('processStop');
                    if (this.shouldShowMessage) {
                        alert({
                            title: $.mage.__('Hello!'),
                            content: $.mage.__(response.message),
                            actions: {
                                always: function () {}
                            }
                        });
                        this.shouldShowMessage = false;
                    }
                    this.clearMessage();
                },
            });
        },
    });
});
