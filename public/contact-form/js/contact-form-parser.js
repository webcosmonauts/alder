$(document).ready(function () {


	function getContactFormWithParsedHTML($form) {

		var
			content = $form.html(),
			shortcodes = content.match(/\[[^\]]+]/gi);


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
					options = options[0].split(":")[1].replace(/\"/g, "");
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

						if (options.length)
							options.forEach(function (item) {
								html += '<option value="' + item + '">' + item + '</option>';
							});


						html += '</select>';
						break;


					case "checkbox":

						if (options.length)
							options.forEach(function (item) {
								html += '<label>' + item + '<input type="checkbox" name="' + name + '" value="' + item + '"></label>';
							});
						break;


					case "radio":

						if (options.length)
							options.forEach(function (item) {
								html += '<label>' + item + '<input type="radio" name="' + name + '" value="' + item + '"></label>';
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
						html = '<input type="submit" value="' + label + '">';
						break;
				}

				if (html) content = content.replace(item, html);

				console.log('item - ', item, ' type = ', type, ' required = ', reqiuired);
				console.log(item, html, content);
				console.log('*************** **************** **************');
			});


			$form.html(content);
		}


		$form.removeAttr('hidden');
	}


	getContactFormWithParsedHTML($('.form'));
});