$(document).ready(function () {

	$('#edit-form').on('submit', function (e) {

		var
			lcm = {},
			defaultFieldsNames = ['title', 'slug', 'status_id', 'user_id', 'content'],
			tabPanes = $('.tab-pane');

		tabPanes.each(function () {

			var
				object = "",
				fields = $(this).find('input, textarea, select'),
				repeaters = $(this).find('.repeater');

			if (repeaters.length)
				repeaters = repeaters.filter(function () {
					if (!$(this).parents('.repeater').length) return true;
				});

			// MAIN SECTION
			if ($(this).attr('id') === "main-section") {

				object = lcm;

				fields = fields.filter(function () {
					if ($(this).parents('.repeater').length) return false;
					if (/ql/g.test($(this).attr('class'))) return false;
					if ($(this).parents('#quill').length) return false;
					if (defaultFieldsNames.indexOf($(this).attr('name')) === -1) return true;
				});


				// OTHER TABS
			} else {

				var tabName = $('.nav-link[href="#' + $(this).attr('id') + '"]').attr("data-tab-name");
				lcm[tabName] = {};

				object = lcm[tabName];

				fields = fields.filter(function () {
					if (!$(this).parents('.repeater').length) return true;
				});
			}

			fields.each(function () {
				addFieldToObject($(this), object);
			});

			if (repeaters.length)
				repeaters.each(function () {
					addRepeaterToObject($(this), object);
				});
		});


		function addRepeaterToObject($repeater, object) {

			$repeater.attr('current', 'true');

			var rows = $repeater.find('.rptr-field').filter(function () {
				if ($(this).parents('.repeater').eq(0).attr('current') === "true") return true;
			});

			$repeater.removeAttr('current');

			var repeaterName = $repeater.attr('data-name');
			object[repeaterName] = [];

			rows.each(function () {

				$(this).attr('current', 'true');

				var obj = {},
					fields = $(this).find('input, textarea, select').filter(function () {
						if ($(this).parents('.rptr-field').eq(0).attr('current') === "true") return true;
					}),

					subRepeaters = $(this).find('.repeater').filter(function () {
						if ($(this).parents('.rptr-field').eq(0).attr('current') === "true") return true;
					});

				$(this).removeAttr('current');

				fields.each(function () {
					addFieldToObject($(this), obj);
				});

				if(subRepeaters.length > 0 ){
					subRepeaters.each(function () {
						addRepeaterToObject($(this), obj);
					});
				}

				object[repeaterName].push(obj);
			});

		}

		function addFieldToObject($field, object) {
			var name = $field.attr('name'), type = $field.attr('type');

			if (type === "radio") {
				if (!object[name]) object[name] = "";
				if ($field.prop('checked')) object[name] = $field.val();
			}

			else if (type === "checkbox") {
				object[name] = [];
				if ($field.prop('checked')) object[name].push($field.val());
			} else if (type === "file") {
				object[name] = $field.prop('files');
			}
			else {
				object[name] = $field.val();
			}
		}

		$('[name=lcm]').val(JSON.stringify(lcm));
	});
});