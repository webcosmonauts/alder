$(document).ready(function () {

	var toolbarOptions = [
		['bold', 'italic', 'underline'],        // toggled buttons
		[{'list': 'ordered'}, {'list': 'bullet'}],
		['blockquote', 'code-block'],

		//[{'header': 1}, {'header': 2}, {'header': 3}, {'header': 4}],               // custom button values

		[{'script': 'sub'}, {'script': 'super'}],      // superscript/subscript
		[{'indent': '-1'}, {'indent': '+1'}],          // outdent/indent
		[{'direction': 'rtl'}],                         // text direction

		[{'header': [1, 2, 3, 4, 5, 6, false]}],

		[{'color': []}, {'background': []}],          // dropdown with defaults from theme
		[{'font': []}],
		[{'align': []}],


		['image', 'video'],  // Embeds

		['clean']                                         // remove formatting button
	];


	function initQuill(quillSelector, content) {
		var quill = new Quill(quillSelector, {
			theme: 'snow',
			modules: {
				toolbar: toolbarOptions
			}
		});

		if (content) {
			var delta = quill.clipboard.convert(content);
			quill.setContents(delta, 'silent');
		}

		$('.ql-toolbar').css('width', '100%');
	}

	if ($('.datepicker').length) $('.datepicker').datetimepicker({
		format: "DD/MM/YYYY"
	});

	if ($('.timepicker').length) $('.timepicker').datetimepicker({
		format: "HH:mm"
	});

	if ($('.datetimepicker').length) $('.datetimepicker').datetimepicker({
		format: "DD/MM/YYYY HH:mm"
	});

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
		lang = $('html').attr('lang') == 'pl' ? 'Polish' : 'English';
		var lastBrowseColumn = $('#browse-table th').length - 1;

		$('#browse-table').DataTable({
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/"+lang+".json"
			},
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

		var clone = $(this).parents('.rptr-field').eq(0).clone(true);

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


		/* for Quill */
		clone.find(".quill").each(function () {
			$(this).prev(".ql-toolbar").remove();
			$(this).remove();
		});

		clone.find("textarea[hidden]").each(function () {
			$(this).after("<div class=\"quill\" style=\"height: 300px; width: 100%\"></div>");
			initQuill($(this).next()[0], $(this).text());
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
		$(this).parent().toggleClass('closed');
	});


	/* MOVE RPTR*/
	$("body").on("click", ".rptr-field__actions div", function (e) {
		e.preventDefault();

		var
			action = $(this).attr('data-action'),
			currentItem = $(this).parents('.rptr-field').eq(0),
			clone = currentItem.clone();


		if (action === "up") {
			if (currentItem.prev('.rptr-field').length) {
				currentItem.prev().before(clone);
				currentItem.remove();
				animateAndScroll();
			}

		} else if (action === "down") {

			if (currentItem.next('.rptr-field').length) {
				currentItem.next().after(clone);
				currentItem.remove();
				animateAndScroll();
			}
		}


		function animateAndScroll() {
			clone.css({"background-color": "#ddd"});
			$('body, html').animate({scrollTop: clone.offset().top - 15}, 800);
			setTimeout(function () {
				clone.css({"background-color": "#fff"});
			}, 500);
		}

	});


	// REMOVE PRTR
	$('body').on('click', '.rptr-field__delete', function (e) {
		e.preventDefault();
		e.stopPropagation();

		$(this).parents('.card-body').eq(0).attr("current", "true");

		var fields = $(this).parents('.card-body').eq(0).find(".rptr-field").filter(function () {
			if ($(this).parents('.card-body').eq(0).attr("current") === "true") return true;
		});

		$(this).parents('.card-body').eq(0).removeAttr("current");

		if (fields.length > 1)
			$(this).parent().remove();
	});
});


// set file link
function fmSetLink($url) {
	$url = $url.replace(/^.*\/\/[^\/]+\//, '');
	$('.image_label[data-current-file]').val($url).attr("value", $url);
	$("[data-current-file]").change();
	$("[data-current-file]").removeAttr("data-current-file");
}