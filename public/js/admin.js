$(document).ready(function () {

	if ($('.datepicker').length) $('.datepicker').datepicker();

	if ($('.icheck').length) {
		$('.icheck').iCheck({
			checkboxClass: 'icheckbox_flat-red',
			radioClass: 'iradio_flat-red'
		});
	}

	if($('#browse-table').length) $('#browse-table').DataTable();

	// ADD NEW PRTR
	$('body').on('click', '.rptr-field__add', function (e) {
		e.preventDefault();

		var clone = $(this).parents('.rptr-field').clone();
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

		$(this).parents('.rptr-field').eq(0).after(clone);
	});


	/* RPTR TOGGLE BODY */
	$('body').on('click', '.rptr-field .card-header', function (e) {
		$(this).next().slideToggle();
	});


	// REMOVE PRTR
	$('body').on('click', '.rptr-field__delete', function (e) {
		e.preventDefault();

		if ($(this).parents('.card-body').eq(0).find('.rptr-field').length > 1)
			$(this).parent().remove();
	});
});