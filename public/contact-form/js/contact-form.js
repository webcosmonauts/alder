$(document).ready(function () {

	new ContactFormTemplate($('#contact-form-template-builder'));
});


function randomInteger(min, max) {
	var rand = min - 0.5 + Math.random() * (max - min + 1)
	rand = Math.round(rand);
	return rand;
}


function ContactFormTemplate($el) {
	this.$mainElement = $el;
	this.$modal = $('#contact-form-template-modal');
	this.$templateContent = $el.find('[name=template-content]');

	this.itemType = '';

	this.init();
}


(function () {

	this.init = function () {

		var self = this;

		this.$mainElement.on('click', '[data-component]', function (e) {

			e.preventDefault();

			var dataCompoment = $(this).attr('data-component');
			self.itemType = dataCompoment;

			self.$modal.find('[data-component]').removeAttr('hidden');
			self.$modal.find('[data-component]').each(function () {

				if (!new RegExp(dataCompoment, 'gi').test($(this).attr('data-component')))
					$(this).attr('hidden', true);
			});

			self.$modal.find('input, textarea').val('');
			self.$modal.find('input[type=checkbox]').prop('checked', false);
			self.$modal.addClass('visible');
		});

		this.$modal.on('click', function (e) {
			$(this).removeClass('visible');
		});

		this.$modal.on('click', '.alder-modal-content', function (e) {
			e.stopPropagation();
		});

		this.$modal.on('click', '.alder-modal-close', function (e) {
			e.preventDefault();
			self.$modal.removeClass('visible');
		});

		this.$modal.on('click', 'button', function () {
			self.$modal.removeClass('visible');
			self.buildItemShortcode();
		});
	};

	this.buildItemShortcode = function () {

		var
			html = '[' + this.itemType,
			name = this.$modal.find('#contact-form-item-name').val().trim(),
			label = this.$modal.find('#contact-form-item-label').val().trim(),
			required = this.$modal.find('#contact-form-item-required').prop('checked'),
			options = this.$modal.find('#contact-form-item-options').val(),
			optionsStr = '',

			allowedFileTypesStr = '',
			allowedFileTypes = this.$modal.find('#contact-form-item-allowed-file-types').val().trim(),

			condition = this.$modal.find('#contact-form-item-condition').val().trim(),
			counter,
			contentVal = this.$templateContent.val();


		if (options) {
			options = options.split('\n');

			counter = 0;
			options.forEach(function (item) {

				if (item)
					counter ? optionsStr += ',' + item.trim() : optionsStr += item.trim();

				counter++;
			});
		}

		if (allowedFileTypes) {
			allowedFileTypes = allowedFileTypes.split(' ');

			counter = 0;
			allowedFileTypes.forEach(function (item) {

				if (item)
					counter ? allowedFileTypesStr += '|' + item.trim() : allowedFileTypesStr += item.trim();

				counter++;
			});
		}

		// Removing spaces in name
		name = name.replace(/ /gi, "");

		switch (this.itemType) {
			case "text":
			case "email":
			case "tel":
			case "date":
			case "textarea":
				if (required) html += '*';
				name ? html += ' name:' + name : html += ' name:' + this.itemType + '-' + randomInteger(1, 999);
				break;


			case "select":
			case "checkbox":
			case "radio":
				if (required) html += '*';
				name ? html += ' name:' + name : html += ' name:' + this.itemType + '-' + randomInteger(1, 999);
				if (optionsStr) html += ' options:"' + optionsStr + '"';
				break;


			case "file" :
				if (required) html += '*';
				name ? html += ' name:' + name : html += ' name:' + this.itemType + '-' + randomInteger(1, 999);
				if (allowedFileTypesStr) html += ' filetypes:' + allowedFileTypesStr;
				break;


			case "acceptance":
				name ? html += ' name:' + name : html += ' name:' + this.itemType + '-' + randomInteger(1, 999);
				if (condition) html += ' condition:"' + condition + '"';
				break;


			case "submit" :
				label ? html += ' "' + label + '"' : html += ' "Submit"';
				break;
		}

		html += ']';

		contentVal ? this.$templateContent.val(contentVal + '\n' + html) : this.$templateContent.val(html);
	};

	this.constructor = ContactFormTemplate;
}).call(ContactFormTemplate.prototype);