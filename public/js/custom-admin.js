$(document).ready(function () {

	if ($('.datepicker').length) $('.datepicker').datetimepicker({
		format: "L"
	});

	if ($('.timepicker').length) $('.timepicker').datetimepicker({
		format: 'LT'
	});

	if ($('.datetimepicker').length) $('.datetimepicker').datetimepicker();

	if ($('.icheck').length) {
		$('.icheck').iCheck({
			checkboxClass: 'icheckbox_flat-red',
			radioClass: 'iradio_flat-red'
		});
	}


	$("select[multiple]").select2();


	$('body').on("click", '.button-image', function (e) {
		e.preventDefault();
		$("[data-current-file]").removeAttr("data-current-file");
		$(this).parent().prev().attr("data-current-file", true);
		window.open('/file-manager/fm-button', 'fm', 'width=1000,height=600,top=200,left=400');
	});


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


	// BROWSE REMOVE ITEM
	$("[data-target=\"#confirm-delete\"]").on("click", function () {
		$("#confirm-delete").find("form").attr("action", $(this).attr("href"));
	});

	// for Slug
	$('[data-title]').on('change', function () {
		var slug = $('[data-slug=' + $(this).attr('data-title') + ']');
		slug.val(slugify($(this).val()));
	});


	// FILE ONCHAGE
	$("body").on("change", ".custom-file-input", function () {
		$(this).next(".custom-file-label").text($(this).val());
	});

	// ADD NEW PRTR
	$('body').on('click', '.rptr-field__add', function (e) {
		e.preventDefault();

		var clone = $(this).parents('.rptr-field').clone(true);

		clone.find('input, textarea, select').each(function () {
			var id = $(this).attr('id'), matchID;

			if (id) {

				matchID = id.match(/\d+$/);
				if (matchID)
					id = id.replace(matchID[0], ++matchID[0]);
				else
					id = id + '_1';
				$(this).attr('id', id);
			}

			if ($(this).prop("type") === "radio" || $(this).prop("type") === "checkbox") {
				var name = $(this).attr("name"), matchName;

				if (name) {
					matchName = name.match(/\d+$/);
					if (matchName)
						name = name.replace(matchName[0], ++matchName[0]);
					else
						name = name + '_1';
					$(this).attr('name', name);
				}
			}
		});


		clone.find('label').each(function () {
			var id = $(this).attr('for'), match;
			if (id) {
				match = name.match(/\d+$/);
				if (match)
					id = id.replace(match[0], ++match[0]);
				else
					id = id + '_1';

				$(this).attr('for', id);
			}
		});

		clone.find('input, textarea, select').each(function () {
			if ($(this).attr("type") !== "radio" && $(this).attr("type") !== "checkbox") {
				$(this).val("");
				$(this).attr("value", "");
			}

			if ($(this).prop("type") === "textarea") $(this).html("");
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


// set file link
function fmSetLink($url) {
	$('.image_label[data-current-file]').val($url).attr("value", $url);
	$("[data-current-file]").removeAttr("data-current-file");
}