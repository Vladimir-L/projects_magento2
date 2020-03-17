define([
    'jquery',
    'jquery/ui',
    'Magento_Ui/js/modal/alert'
], function ($, mage, alert) {
    'use strict';

    $.widget('vladimirLChatter.formChatter', {
        options: {
            formChatterOpenbutton: '.vladimirl-chatter-open-button',
            closeChatterForm: '#close-chatter-button',
            sendMessage: '#send-message-button'
        },

        /**
         * @private
         */
        _create: function () {
            this.shouldShowMessage = true;
            $(document).on('vladimirL_chatter_openChatter.vladimirL_chatter', $.proxy(this.openFormChatter, this));
            $(this.options.closeChatterForm).on('click.vladimirL_chatter', $.proxy(this.closeFormChatter, this));
            $(this.element).on('submit.vladimirL_chatter', $.proxy(this.submitForm, this));
            $(this.element).show();
        },

        /**
         * Open chatter form
         */
        openFormChatter: function () {
            $(this.element).addClass('active');
        },

        /**
         * Close chatter form
         */
        closeFormChatter: function () {
            $(this.element).removeClass('active');
            $(this.options.formChatterOpenbutton).trigger('vladimirL_chatter_closeChatter');
        },

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

                // @TODO show success message and display message in message list
                /** @inheritdoc */
                success: function (response) {
                    $('body').trigger('processStop');
                    if (this.shouldShowMessage) {
                        alert({
                            title: $.mage.__('Hello!'),
                            content: $.mage.__(response.message),
                            actions: {
                                always: function () {
                                }
                            }
                        });
                        this.shouldShowMessage = false;
                    }
                    $('#messages-list').append('<p>' + response.messageOutput + '</p>');
                    $('#message-input').val('');
                }
            });
        }
    });
    return $.vladimirLChatter.formChatter;
});
