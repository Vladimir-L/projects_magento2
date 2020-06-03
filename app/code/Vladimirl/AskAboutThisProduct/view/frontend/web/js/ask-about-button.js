define([
    'jquery',
    'vladimirl_askAbout_form'
], function ($) {
    'use strict';

    $.widget('vladimirlAskAboutThisProduct.button', {
        options: {
            form: '#vladimirl-ask-about-form'
        },

        /**
         * @private
         */
        _create: function () {
            $(this.element).on('click.vladimirl_aboutThis', $.proxy(this.openForm, this));
        },

        /**
         * Open preferences sidebar
         */
        openForm: function () {
            $(this.options.form).data('mage-modal').openModal();
        }
    });

    return $.vladimirlAskAboutThisProduct.button;
});
