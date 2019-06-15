$(document).ready(function () {

	if ($('.datepicker').length) $('.datepicker').datepicker();

	if ($('.icheck').length) {
		$('.icheck').iCheck({
			checkboxClass: 'icheckbox_flat-red',
			radioClass: 'iradio_flat-red'
		});
	}


	/* dataTable */
	if ($('#browse-table').length) {
		var lastBrowseColumn = $('#browse-table th').length - 1;

		$('#browse-table').DataTable({
			"columnDefs": [
				{"orderable": false, "targets": lastBrowseColumn}
			]
		});
	}

	function slugify(str) {
		str = str.toLowerCase().trim();
		str = str.replace(/\s{2,}/g, "_");
		str = str.replace(/\s/g, "_");
		return str;
	}


	// for Slug
	$('[data-title]').on('change', function () {
		var slug = $('[data-slug=' + $(this).attr('data-title') + ']');
		slug.val(slugify($(this).val()));
	});

	// ADD NEW PRTR
	$('body').on('click', '.rptr-field__add', function (e) {
		e.preventDefault();

		var clone = $(this).parents('.rptr-field').clone(true);

		clone.find('input, textarea, select').each(function () {
			var name = $(this).attr('name'), match,
				id = $(this).attr('id'), matchID;

			match = name.match(/\d+$/);
			if (match)
				name = name.replace(match[0], ++match[0]);
			else
				name = name + '_1';

			matchID = id.match(/\d+$/);
			if (matchID)
				id = id.replace(matchID[0], ++matchID[0]);
			else
				id = id + '_1';

			$(this).attr('name', name);
			$(this).attr('id', id);
		});


		clone.find('label').each(function () {
			var id = $(this).attr('for'), match;

			match = name.match(/\d+$/);
			if (match)
				id = id.replace(match[0], ++match[0]);
			else
				id = id + '_1';

			$(this).attr('for', id);
		});

		clone.find('input, textarea, select').each(function () {
			if ($(this).attr("type") !== "radio" && $(this).attr("type") !== "checkbox") $(this).val("");
		});

		clone.find('input[type=checkbox], input[type=radio]').prop('checked', false);
		clone.find('.icheck').iCheck('destroy');

		clone.find('.icheck').iCheck({
			checkboxClass: 'icheckbox_flat-red',
			radioClass: 'iradio_flat-red'
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