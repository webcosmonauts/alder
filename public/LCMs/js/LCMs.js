$(document).ready(function () {

	// ADD NEW FIELD
	$('#add-new-field').on('click', function (e) {
		e.preventDefault();

		var pattern = $('#field-pattern').html();

		$('#fields-container').append(pattern);
		$('#fields-container').find('input, select, textarea').removeClass('disabled').removeAttr('disabled');
	});


	// REMOVE FIELD
	$('body').on('click', '.field__delete', function (e) {
		e.preventDefault();
		$(this).parent().remove();
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


	/* FORM SUBMIT */
	$('#LCMs-form').submit(function (e) {
		e.preventDefault();

		var
			form = $(this),
			url = form.attr('action'),

			data = {
				_token: form.find('[name=_token]').val(),
				fields: {},
				bread: {
					browse: {
						table_columns: []
					}
				}
			},

			fieldNamesArray = [],
			$fields = $('.field').filter(function () {
				if (!$(this).parent('#field-pattern').length) return true;
			});


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

		$fields.each(function () {

			var
				fieldObj = {},
				fieldName = $(this).find('[name=field_name]').val(),
				fieldType = $(this).find('[name=type]').val(),
				$inputs = $(this).find('input, select, textarea');

			if (fieldName) {

				fieldName = fieldName.toLowerCase().trim().replace(/\s/g, '_');
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


				data.fields[fieldName] = fieldObj;
			}
		});


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