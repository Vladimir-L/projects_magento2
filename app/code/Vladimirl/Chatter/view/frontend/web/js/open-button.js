define ([
	'jquery',
	'jquery/ui',
	'vladimirL_chatter_formChatter'
], function ($) {
	'use strict';

	$.widget('vladimirLChatter.openButton', {
		options: {
			hideButton: true
		},

		_create: function () {
			$(this.element).on('click.vladimirL_chatter', $.proxy(this.openChatter, this));
			$(this.element).on('vladimirL_chatter_closeChatter.vladimirL_chatter', $.proxy(this.closeChatter, this));
		},
		// _destroy: function () {
		// 	$(this.element).off('click.vladimirL_chatter');
		// 	$(this.element).off('vladimirL_chatter_closeChatter.vladimirL_chatter');
		// },
		openChatter: function () {
			$(document).trigger('vladimirL_chatter_openChatter');
			$(this.element).removeClass('active');
		},
		closeChatter: function () {
			$(this.element).addClass('active');
		}
	});
	return $.vladimirLChatter.openButton;
});