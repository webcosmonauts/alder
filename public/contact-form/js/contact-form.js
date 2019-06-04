$(document).ready(function () {

	new ContactFormTemplate($('#contact-form-template-builder'));
});


function ContactFormTemplate($el) {
	this.$mainElement = $el;
	this.$modal = $('#contact-form-template-modal');
	this.$content = $el.find('[name=content]');

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

			self.$modal.find('input').val('');
			self.$modal.find('input[type=checkbox]').prop('checked', false);
			self.$modal.addClass('visible');
		});

		this.$modal.on('click', function (e) {
			$(this).removeClass('visible');
		});

		this.$modal.on('click', '.alder-modal-content', function (e) {
			e.stopPropagation();
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
			label = this.$modal.find('#contact-form-item-label').val(),
			required = this.$modal.find('#contact-form-item-required').prop('checked'),

			contentVal = this.$content.val();

		name = name.replace(/ /gi, "");

		switch (this.itemType) {
			case "text":
			case "email":

				if (required) html += '*';
				if (name) html += ' name:' + name;

				break;

			case "submit" :

				label ? html += ' "' + label + '"' : html += ' "Submit"';
				break;
		}

		html += ']';

		contentVal ? this.$content.val(contentVal + '\n' + html) : this.$content.val(html);
	};

	this.constructor = ContactFormTemplate;
}).call(ContactFormTemplate.prototype);