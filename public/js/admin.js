$(document).ready(function () {

	if ($('.datepicker').length) $('.datepicker').datepicker();

	if ($('.icheck').length) {
		$('.icheck').iCheck({
			checkboxClass: 'icheckbox_flat-red',
			radioClass: 'iradio_flat-red'
		});
	}


	$('body').on('click', '.rptr-field__add', function (e) {
		e.preventDefault();

		var clone = $(this).parent().clone();
		clone.find('input', 'textarea', 'select').val('').each(function () {
			var name = $(this).attr('name'), match;

			match = name.match(/\d+$/);
			if (match)
				name = name.replace(match[0], ++match[0]);
			else
				name = name + '_1';

			$(this).attr('name', name);
		});

		clone.find('input[type=checkbox], input[type=radio]').prop('checked', false);
		clone.find('.icheck').each(function () {
			$(this).iCheck('uncheck');
		});

		$(this).parent().after(clone);
	});


	$('body').on('click', '.rptr-field__delete', function (e) {
		e.preventDefault();

		if ($(this).parents('.card-body').eq(0).find('.rptr-field').length > 1)
			$(this).parent().remove();
	});
});