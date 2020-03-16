define([
    'jquery',
    'jquery/ui',
    'Magento_Ui/js/modal/alert'
], function ($, mage, alert) {
    'use strict';

    $.widget('vladimirLChatter.formChatter', {
        options: {
            formChatterOpenbutton: '.vladimirl-chatter-open-button',
            closeChatterForm: '#close-chatter-batton',
            sendMessage: '#send-message-button'
        },

        _create: function () {
            this.shouldShowMessage = true;
            $(document).on('vladimirL_chatter_openChatter.vladimirL_chatter', $.proxy(this.openFormChatter, this));
            $(this.options.closeChatterForm).on('click.vladimirL_chatter', $.proxy(this.closeFormChatter, this));
            $(this.element).on('submit.vladimirL_chatter', $.proxy(this.submitForm, this));
            $(this.element).show();
        },

        // _destroy: function () {
        // 	$(document).off('vladimirL_chatter_openChatter.vladimirL_chatter');
        // 	$(this.options.closeChatterForm).off('click.vladimirL_chatter_openChatter.vladimirL_chatter');
        // 	$(this.options.sendMessage).off('click.vladimirL_chatter');
        // },

        openFormChatter: function () {
            $(this.element).addClass('active');
        },
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
        validateForm: function () {
            return $(this.element).validation().valid();
        },
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

                beforeSend: function () {
                    $('body').trigger('processStart');
                },

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
                    $('#displayedForm').append('<p>' + response.textform + '</p>');
                    $('#entryText').val('');
                }
            });
        }
    });
    return $.vladimirLChatter.formChatter;
});
