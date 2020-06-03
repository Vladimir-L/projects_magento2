define([
    'jquery',
    'jquery/ui',
    'vladimirL_chatter_formChatter'
], function ($) {
    'use strict';

    $.widget('vladimirLChatter.openButton', {
        options: {
            hideButton: true
        },

        /**
         * @private
         */
        _create: function () {
            $(this.element).on('click.vladimirL_chatter', $.proxy(this.openChatter, this));
            $(document).on('vladimirL_chatter_closeChatter.vladimirL_chatter', $.proxy(this.closeChatter, this));
        },

        /**
         * Open chatter form
         */
        openChatter: function () {
            $(document).trigger('vladimirL_chatter_openChatter');
            $(this.element).removeClass('active');
        },

        /**
         * Close chatter form
         */
        closeChatter: function () {
            $(this.element).addClass('active');
        }
    });

    return $.vladimirLChatter.openButton;
});
