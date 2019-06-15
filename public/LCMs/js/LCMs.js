$(document).ready(function () {

	function slugify(str) {

		str = str.toLowerCase().trim();
		str = str.replace(/\s{2,}/g, "_");
		str = str.replace(/\s/g, "_");

		return str;
	}


	// FOR ALL INPUTS AND TEXTAREA [^0-9a-zA-Z\s_:] MUST HAVE NORMAL TEXT
	$('body').on('keyup', 'input, textarea', function (e) {

		var text = $(this).val();
		$(this).val(text.replace(/[^0-9a-zA-Z\s_:]+/g, ""));
	});

	// LCM SLUG
	$('[data-title]').on('change', function () {
		var slug = $('[data-slug=' + $(this).attr('data-title') + ']');
		slug.val(slugify($(this).val()));
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
		$('#add-new-field').before('<div id="section' + sectionsCounter + '" class="content shadow mb-5"></div>');

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

		container.find('input[type=checkbox]').iCheck({
			checkboxClass: 'icheckbox_flat-red',
			radioClass: 'iradio_flat-red'
		});
	});


	// ADD NEW REPEATER FIELD
	$('body').on('click', '.add-new-field-repeater', function (e) {
		e.preventDefault();

		var pattern = $('#field-pattern').html(), container = $(this).parent();

		$(this).before(pattern);
		container.find('input, select, textarea').removeClass('disabled').removeAttr('disabled');

		container.find('input[type=checkbox]').iCheck({
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
		$(this).parent().find('.card-body').eq(0).slideToggle();
	});

	// FIELD NAME CHANGE
	$('body').on('change', "[name=field_name]", function () {
		$(this).parents('.field').eq(0).find('.card-header').eq(0).text($(this).val());
	});

	// TYPE DEPENDENCE
	$('body').on('change', '[name=type]', function () {

		var
			thisValue = $(this).val(),
			thisField = $(this).parents('.field').eq(0);

		/* Check for repeater fields */
		thisField.attr('current', 'true');
		var thisDependencies = thisField.find('[data-dependence]').filter(function () {
			if ($(this).parents('.field').eq(0).attr('current') === 'true') return true;
		});

		thisField.removeAttr('current');


		thisDependencies.each(function () {
			$(this).attr('hidden', true);

			if (new RegExp(thisValue).test($(this).attr('data-dependence'))) {
				$(this).removeAttr('hidden');
			}
		});
	});


	/******** Condition fields ******/
	$('body').on('change', 'select[name=parameter]', function (e) {

		var
			typeSelect = $(this).parents('.condition-field').find('select[name=value]'),
			value = $(this).val();

		typeSelect.find('option').addClass('d-none');
		typeSelect.find('option[data-group="' + value + '"]').removeClass('d-none');

		var index = 0, selected = false;
		typeSelect.find('option').each(function () {
			if ($(this).attr('data-group') == value && !selected) {
				typeSelect.prop('selectedIndex', index);
				selected = true;
			}

			index++;

		});
	});

	/* ADD NEW CONDITION*/
	$('#add-new-condition').on('click', function (e) {
		e.preventDefault();

		var pattern = $('.condition-field').eq(0).clone();
		pattern.find('select').prop('selectedIndex', 0);
		pattern.find('[data-group]').addClass('d-none');
		pattern.find('[data-group=page-template]').removeClass('d-none');

		$(this).before(pattern);
	});


	/*REMOVE CONDITION */
	$('body').on('click', '.condition-field__delete', function (e) {
		e.preventDefault();

		$(".condition-field").removeAttr('data-delete');
		$(this).parent().attr('data-delete', 'true');
		$('#confirm-delete-condition').modal('show');
	});

	$('#confirm-delete-condition').on('click', '.btn', function (e) {
		$('.condition-field[data-delete=true]').remove();
		$('#confirm-delete-condition').modal('hide');
	});


	/*********/


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
			$conditionFields = form.find('.condition-field'),
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


		/* Tabs */
		$tabs.each(function () {

			var $fields = $(this).find('.field').filter(function () {
				if (!$(this).parents('.repeater-field-container').length) return true;
			});

			// IF MAIN
			if ($(this).attr('id') === "main") {
				addFieldsToJSONObj($fields, data.lcm);

				// FOR OTHER TABS
			} else {

				var
					tabName = $('.lcm-tabs__link[href="#' + $(this).attr('id') + '"]').find('span').text(),
					tabSlug = slugify(tabName), obj;

				tabSlug = checkFieldName(tabSlug);

				data.lcm[tabSlug] = {
					display_name: tabName,
					fields: {}
				};

				obj = data.lcm[tabSlug].fields;

				addFieldsToJSONObj($fields, obj, $(this));
			}
		});


		/* Conditions */
		$conditionFields.each(function () {

			var obj = {};

			$(this).find('select').each(function () {
				obj[$(this).attr('name')] = $(this).val();
			});

			data.conditions.push(obj);
		});

		function addFieldsToJSONObj(fields, obj, tab) {


			fields.each(function () {

				var
					fieldObj = {
						display_name: ""
					},

					fieldName = $(this).find('[name=field_name]').eq(0).val(),
					fieldType = $(this).find('[name=type]').eq(0).val();

				/* Filtering inputs in current field */
				$(this).attr('current', 'true');
				var $inputs = $(this).find('input, select, textarea').filter(function () {
					if ($(this).parents('.field').eq(0).attr('current') === "true") return true;
				});

				$(this).removeAttr('current');


				if (fieldType === "repeater") {
					$(this).attr('current', 'true');

					var repeaterFields = $(this).find('.field').filter(function () {
						if ($(this).parents('.field').eq(0).attr('current') === "true") return true;
					});

					$(this).removeAttr('current');

					fieldObj.fields = {};
					var subfieldObj = fieldObj.fields;

					addFieldsToJSONObj(repeaterFields, subfieldObj, tab);
				}

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

								if (fieldType === "select" || fieldType === "select-multiple" || fieldType === "checkbox" || fieldType === "radio") {
									if (inputValue) {
										fieldObj[inputName] = {};

										inputValue = inputValue.split('\n');

										inputValue.forEach(function (item) {
											var line = item.split(":");

											if (line.length > 1) {
												line[0] = line[0].replace(/\s/g, "");
												line[1] = line[1].replace(/\s{2,}/g, "");
												line[1] = line[1].trim();

												fieldObj[inputName][line[0]] = line[1];
											}
										});
									} else {
										fieldObj[inputName] = [];
									}
								}

								break;


							case "relation_type":
							case "leaf_type":

								if (fieldType === "relation")
									fieldObj[inputName] = inputValue;

								break;

							case "browse":
								if ($(this).prop('checked'))
									data.bread.browse.table_columns.push(fieldName);
								break;

							case "default":
								if ($(this).val())
									fieldObj[inputName] = inputValue;
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


		data = JSON.stringify(data);

		var method = "POST";
		if (/edit/g.test(window.location.href)) method = "PUT";

		if ($('#response-message').length) $('#response-message').remove();

		$.ajax({
			method: method,
			url: url,
			data: {

				_token: form.find('[name=_token]').val(),
				data: data,

				title: $('#lcm_title').val(),
				slug: slugify($('#lcm_slug').val()),
				group_title: $('#lcm_group_title').val(),
				group_slug: slugify($('#lcm_group_slug').val()),
				leaf_type_id: $("#leaf_type_id").val()
			}

		}).done(function (response) {

			var messageHtml = "";

			if (response.redirect) {

				buildAndViewMessage();

				setTimeout(function () {
					window.location.href = response.redirect;
				}, 1500);
			} else {
				buildAndViewMessage();
			}


			function buildAndViewMessage() {
				messageHtml = "<div id='response-message' class='card mb-5 border-left-" + response['alert-type'] + " shadow h-100 py-2'><div class='card-body'>" + response.message + "</div></div>";

				$('.container-fluid').eq(0).prepend(messageHtml);
				$('body, html').animate({scrollTop: 0});
			}
		});

	});
});