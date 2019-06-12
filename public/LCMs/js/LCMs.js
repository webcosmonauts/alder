$(document).ready(function () {

	function slugify(str) {

		str = str.toLowerCase().trim();
		str = str.replace(/\s{2,}/g, "_");
		str = str.replace(/\s/g, "_");

		return str;
	}

	// LCM SLUG
	$('#lcm_title').on('change', function () {

		$('#lcm_slug').val(slugify($(this).val()));
	});


	// LCM TABS
	$('.lcm-tabs').on('click', '.lcm-tabs__link', function (e) {
		e.preventDefault();

		$(".lcm-tabs__link").removeClass('active');
		$(this).addClass('active');

		$('.lcm-tabs-content').find('.content').removeClass('active');
		$($(this).attr('href')).addClass('active');
	});

	// LCM TAB DELETE
	$('.lcm-tabs').on('click', '.lcm-tabs__link em', function (e) {
		e.preventDefault();
		e.stopPropagation();

		$('#confirm-delete-tab').modal('show');
	});


	$('#confirm-delete-tab').find('.btn-primary').on('click', function (e) {

		var activeTab = $('.lcm-tabs__link.active');

		$(activeTab.attr('href')).remove();
		activeTab.prev().trigger('click');
		activeTab.remove();

		$('#confirm-delete-tab').modal('hide');
	});

	// LCM TAB EDIT
	$('.lcm-tabs').on('dblclick', '.lcm-tabs__link', function (e) {
		e.preventDefault();
		e.stopPropagation();

		if ($(this).attr('href') === "#main") return false;

		$("#lcm-tab-edit").val($(this).find('span').text()).css({
			'display': 'block',
			"left": $(this).position().left,
			"width": $(this).outerWidth()
		}).focus();
	});

	$("#lcm-tab-edit").on('change', function () {
		editTabText($(this));
	});

	$('#lcm-tab-edit').on('blur', function () {
		editTabText($(this));
	});

	$('#lcm-tab-edit').on('keydown', function (e) {

		if (e.keyCode === 13) {
			e.preventDefault();
			editTabText($(this))
		}
	});

	function editTabText(self) {
		var text = self.val(), tabSpan = $('.lcm-tabs__link.active').find('span');

		text ? tabSpan.text(text) : tabSpan.text('tab');
		self.css('display', 'none');
	}

	/****************************/

	// LCM TAB ADD
	var sectionsCounter = 0;
	$('#add-new-tab').on('click', function (e) {
		e.preventDefault();
		sectionsCounter++;

		var lcmLinkHtml = '<a href="#section' + sectionsCounter + '" class="lcm-tabs__link">\n' +
			'                <span>Tab</span>\n' +
			'                <em>&times;</em></a>';

		$(this).before(lcmLinkHtml);
		$('#add-new-field').before('<div id="section' + sectionsCounter + '" class="content mb-5"></div>');

		setTimeout(function () {
			var length = $('.lcm-tabs__link').length;
			$('.lcm-tabs__link').eq(length - 1).trigger('click');
			$('.lcm-tabs__link').eq(length - 1).trigger('dblclick');
		}, 100);
	});


	// ADD NEW FIELD
	$('#add-new-field').on('click', function (e) {
		e.preventDefault();

		var pattern = $('#field-pattern').html(), container = $('.lcm-tabs-content').find('.content.active');

		container.append(pattern);
		container.find('input, select, textarea').removeClass('disabled').removeAttr('disabled');

		container.find('.icheck').iCheck({
			checkboxClass: 'icheckbox_flat-red',
			radioClass: 'iradio_flat-red'
		});
	});

	// REMOVE FIELD
	$('body').on('click', '.field__delete', function (e) {
		e.preventDefault();
		e.stopPropagation();

		$(this).parent().remove();
	});


	// SLIDE TOGGLE FOR FIELD BODY
	$('body').on('click', '.field .card-header', function (e) {
		e.preventDefault();
		$(this).parent().find('.card-body').slideToggle();
	});


	// TYPE DEPENDENCE
	$('body').on('change', '[name=type]', function () {

		var thisValue = $(this).val();

		$(this).parents('.field').find('[data-dependence]').each(function () {

			$(this).attr('hidden', true);

			if (new RegExp(thisValue).test($(this).attr('data-dependence'))) {
				$(this).removeAttr('hidden');
			}
		});
	});


	$('#LCMs-form').on('keyup', 'input', function () {
		$(this).removeClass('invalid');
	});

	$('#LCMs-form').on('change', 'input', function () {
		$(this).removeClass('invalid');
	});

	/* FORM SUBMIT */
	$('#LCMs-form').submit(function (e) {
		e.preventDefault();

		var
			form = $(this),
			url = form.attr('action'),

			data = {
				_token: form.find('[name=_token]').val(),
				lcm_title: $('#lcm_title').val(),
				lcm_slug: slugify($('#lcm_slug').val()),

				lcm: {},
				bread: {
					browse: {
						table_columns: []
					}
				},

				conditions: []
			},

			fieldNamesArray = [],
			$requiredFields = form.find('[required]'),
			$tabs = $('.lcm-tabs-content').find('.content');

		/* VALIDATION */
		var validation = (function () {

			var invalidInputs = [], response = true;

			$requiredFields.each(function () {
				if ($(this).val()) $(this).removeClass('invalid');
				else {
					$(this).addClass('invalid');
					invalidInputs.push($(this));
					response = false;
				}
			});

			if (invalidInputs.length > 0) {
				var firstInvalid = invalidInputs[0];

				if (firstInvalid.parents('.lcm-tabs-content').length)
					$('.lcm-tabs__link[href="#' + firstInvalid.parents('.content').eq(0).attr('id') + '"]').trigger('click');

				$('body, html').animate({scrollTop: firstInvalid.offset().top}, 600);
			}

			return response;
		})();


		if (!validation) return false;

		function checkFieldName(fieldName) {

			if (fieldNamesArray.indexOf(fieldName) === -1) {
				fieldNamesArray.push(fieldName);

				return fieldName;
			}

			else {
				var match = fieldName.match(/\d+$/);
				if (match)
					fieldName = fieldName.replace(match[0], ++match[0]);
				else
					fieldName = fieldName + '_1';

				return checkFieldName(fieldName);
			}
		}

		$tabs.each(function () {

			var $fields = $(this).find('.field');

			// IF MAIN
			if ($(this).attr('id') === "main") {
				addFieldsToJSONObj($fields);

				// FOR OTHER TABS
			} else {
				addFieldsToJSONObj($fields, $(this));
			}
		});


		function addFieldsToJSONObj(fields, tab) {

			var obj = data.lcm;
			if (tab && tab.length) {
				var
					tabName = $('.lcm-tabs__link[href="#' + tab.attr('id') + '"]').find('span').text(),
					tabSlug = slugify(tabName);

				console.log('tabName', tabSlug);


				data.lcm[tabSlug] = {
					display_name: tabName,
					fields: {}
				};

				obj = data.lcm[tabSlug].fields;
			}

			fields.each(function () {

				var
					fieldObj = {
						display_name: ""
					},

					fieldName = $(this).find('[name=field_name]').val(),
					fieldType = $(this).find('[name=type]').val(),
					$inputs = $(this).find('input, select, textarea');

				if (fieldName) {

					fieldObj.display_name = fieldName;

					fieldName = slugify(fieldName);
					fieldName = checkFieldName(fieldName);

					$inputs.each(function () {
						var inputName = $(this).attr('name'), inputValue = $(this).val();

						switch (inputName) {
							case "field_name":
								break;

							case "options" :

								if (inputValue) {
									fieldObj[inputName] = {};

									inputValue = inputValue.split('\n');

									inputValue.forEach(function (item) {
										var line = item.split(":");

										line[0] = line[0].replace(/\s/g, "");
										line[1] = line[1].replace(/\s{2,}/g, "");

										fieldObj[inputName][line[0]] = line[1];
									});
								} else {
									fieldObj[inputName] = "";
								}

								break;


							case "relation_type":
							case "leaf_type":

								if (fieldType == "relation")
									fieldObj[inputName] = inputValue;
								else
									fieldObj[inputName] = "";
								break;

							case "browse":
								if ($(this).prop('checked'))
									data.bread.browse.table_columns.push(fieldName);
								break;

							default:
								fieldObj[inputName] = inputValue;
								break;
						}
					});


					obj[fieldName] = fieldObj;
				}
			});
		}


		console.log(data);
		data = JSON.stringify(data);

		$.ajax({
			method: "POST",
			url: url,
			data: data
		}).done(function (message) {
			console.log(message);
		});

	});
});