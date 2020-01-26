define ([
	'jquery',
	'jquery/ui',
], function ($) {
	'use strict';

	$.widget('vladimirLChatter.formChatter', {
		options: {
			formChatterOpenbutton: '.vladimirl-chatter-open-button',
			closeChatterForm: '#vladimirl-chatter-close-chatter-batton',
			sendMessage: '#send-message-button'
		},

		_create: function () {
			$(document).on('vladimirL_chatter_openChatter.vladimirL_chatter', $.proxy(this.openFormChatter, this));
			$(this.options.closeChatterForm).on('click.vladimirL_chatter', $.proxy(this.closeFormChatter, this));
			$(this.options.sendMessage).on('click.vladimirL_chatter', $.proxy(this.sendMessageToForm, this));
			$(this.element).show();
		},

		_destroy:function () {
			$(document).off('vladimirL_chatter_openChatter.vladimirL_chatter');
			$(this.options.closeChatterForm).off('click.vladimirL_chatter');
		},

		openFormChatter: function () {
			$(this.element).addClass('active');
		},

		closeFormChatter: function () {
			$(this.element).removeClass('active');
			$(this.options.formChatterOpenbutton).trigger('vladimirL_chatter_closeChatter');
		}
		sendMessageToForm: function () {
			var entrytext1 = document.getElementById('entryTex').value;
			document.getElementById('displayedText').value = entrytext1;	
		}
	});
	return $.vladimirLChatter.formChatter;
});