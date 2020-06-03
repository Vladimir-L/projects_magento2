define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/modal/modal'
], function ($, alert) {
    'use strict';

    $.widget('vladimirlAskAboutThisProduct.form', {
        options: {
            action: ''
        },

        /**
         * @private
         */
        _create: function () {
            this.modal = $(this.element).modal({
                buttons: []
            });
            $(this.element).on('submit.vladimirl_aboutThis', $.proxy(this.submitForm, this));
        },

        /**
         * Check validation
         */
        submitForm: function () {
            if (!this.validateForm()) {
                return;
            }
            this.ajaxSubmit();
        },

        /**
         * Validate chatter form
         */
        validateForm: function () {
            return $(this.element).validation().valid();
        },

        /**
         * Close and clear modal popup
         */
        closeForm: function () {
            this.modal.modal('closeModal');
            document.getElementById('vladimirl-ask-about-form').reset();
        },

        /**
         * Submit message via AJAX
         */
        ajaxSubmit: function () {
            var formData = new FormData($(this.element).get(0));

            formData.append('form_key', $.mage.cookies.get('form_key'));
            formData.append('isAjax', 1);

            $.ajax({
                url: $(this.element).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
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
                    alert({
                        title: $.mage.__('Hello!'),
                        content: $.mage.__(response.message)
                    });
                    this.closeForm();
                },

                /** @inheritdoc */
                error: function () {
                    $('body').trigger('processStop');
                    alert({
                        title: $.mage.__('Error'),
                        content: $.mage.__('Something went wrong!')
                    });
                    this.closeForm();
                }
            });
        }
    });

    return $.vladimirlAskAboutThisProduct.form;
});
