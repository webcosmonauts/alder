$(document).ready(function () {


	function getContactFormWithParsedHTML($form) {

		var
			content = $form.html(),
			shortcodes = content.match(/\[[^\]]+]/gi),
			pastShortcodes = [];


		if (shortcodes && shortcodes.length > 0) {

			shortcodes.forEach(function (item) {

				var type, name, reqiuired = false, label, options, condition, filetypes, html = '';


				// TYPE
				type = item.split(' ')[0].replace("[", "");
				if (/\*/.test(type)) {
					reqiuired = true;
					type = type.replace("*", "");
				}


				// NAME
				name = item.match(/name:[^\]\s]+/i);
				if (name) {
					name = name[0].split(":")[1];
				}

				// OPTIONS
				options = item.match(/options:\"[^\"]+\"/i);
				if (options && options.length > 0) {
					options = options[0].replace("options:", "").replace(/\"/g, "");
					options = options.split(',');
				}

				switch (type) {

					case "text":
					case "email":
					case "tel":
					case "date":

						html = '<input type="' + type + '" name="' + name + '"';
						if (reqiuired) html += ' required';
						html += ' >';
						break;


					case "select":

						html = '<select name="' + name + '">';

						if (options.length) {
							options.forEach(function (item) {

								var value;

								if (/:/g.test(item)) {
									item = item.split(":");
									value = item[0].toLowerCase().trim().replace(/\s/g, '');
									item = item[1];
								} else {

									value = item.toLowerCase().trim().replace(/\s/g, '');
								}

								html += '<option value="' + value + '">' + item + '</option>';
							});
						}

						html += '</select>';
						break;


					case "checkbox":
					case "radio":

						if (options.length)
							options.forEach(function (item) {

								var value;
								if (/:/g.test(item)) {
									item = item.split(":");
									value = item[0].toLowerCase().trim().replace(/\s/g, '');
									item = item[1];
								} else {

									value = item.toLowerCase().trim().replace(/\s/g, '');
								}

								html += '<label>' + item + '<input type="' + type + '" name="' + name + '" value="' + value + '"></label>';
							});
						break;

					case "file":

						filetypes = item.match(/filetypes:[^\]\s]+/);
						html = '<input type="file" name="' + name + '"';

						if (filetypes) {
							filetypes = filetypes[0].split(":")[1].split("|");
							html += ' accept= "';

							var counter = 0;
							filetypes.forEach(function (item) {

								counter ? html += ',' + item : html += item;
								counter++;
							});

							html += '"';
						}

						html += '>';
						break;

					case "acceptance":

						condition = item.match(/condition:\"[^\"]+\"/);
						if (condition)
							condition = condition[0].split(":")[1].replace(/\"/g, "");

						html = '<label> <input type="checkbox" required> ' + condition + ' </label>';
						break;

					case "textarea":

						html = '<textarea name="' + name + '"';
						if (reqiuired) html += ' required';
						html += '></textarea>';
						break;

					case "submit":

						label = item.match(/\"[^\"]+\"/i)[0].replace(/\"/g, "");
						html = '<button type="submit" class="link-button">' + label + '</button>';
						break;
				}

				if (html) {

					if (pastShortcodes.indexOf(item) === -1) {
						content = content.replace(item, html);
						pastShortcodes.push(item);
					} else
						content = content.replace(item, '');
				}

			});


			$form.html(content);
			$form.removeAttr("hidden");

			$form.find("input[type=checkbox]").iCheck({
				checkboxClass: 'icheckbox_flat-red',
				radioClass: 'iradio_flat-red'
			});
		}


		$form.removeAttr('hidden');
	}


	if ($('.form-with-shortcode').length) {
		$('.form-with-shortcode').each(function () {
			getContactFormWithParsedHTML($(this));
		});
	}
});