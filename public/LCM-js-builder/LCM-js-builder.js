$(document).ready(function () {

	/* Elements changes LCM fields */
	$('select#tags, select#categories, #template').on("change", function () {
		imitate();
	});


	function imitate() {
		var response = "{\"lcm\":{\"test_tab\":{\"display_name\":\"Test tab\",\"fields\":{\"icon\":{\"display_name\":\"Icon\",\"type\":\"text\",\"panel\":\"right\",\"default\":\"ellipsis-h\"},\"text_test\":{\"display_name\":\"Text test\",\"type\":\"text\",\"default\":\"ellipsis-h\"},\"image_test\":{\"display_name\":\"Image test\",\"type\":\"file\",\"default\":\"\"},\"test_sub_relation\":{\"display_name\":\"Test sub relation\",\"type\":\"relation\",\"relation_type\":\"belongsToMany\",\"leaf_type\":\"admin-menu-item\",\"nullable\":true},\"slider\":{\"display_name\":\"Slider\",\"type\":\"repeater\",\"fields\":{\"slide_text\":{\"display_name\":\"Slide text\",\"type\":\"text\",\"default\":\"ellipsis-h\"},\"slide_bg\":{\"display_name\":\"Slide BG\",\"type\":\"file\"},\"slide_options\":{\"display_name\":\"slide Options\",\"type\":\"radio\",\"options\":{\"only_text\":\"Only text\",\"only_img\":\"Only image\",\"both\":\"Both\"}}},\"nullable\":true},\"select_opt\":{\"type\":\"select\",\"display_name\":\"Slider Options\",\"options\":{\"only_text\":\"Only text\",\"only_img\":\"Only image\",\"both\":\"Both\"}},\"select_multiple_options\":{\"type\":\"select-multiple\",\"display_name\":\"Slider Options Multi\",\"options\":{\"only_text\":\"Only text\",\"only_img\":\"Only image\",\"both\":\"Both\"}},\"password\":{\"type\":\"password\",\"display_name\":\"Password\"},\"date\":{\"type\":\"date\",\"display_name\":\"DATE\"},\"datetime-local\":{\"type\":\"datetime-local\",\"display_name\":\"DATETIME\"},\"time\":{\"type\":\"time\",\"display_name\":\"TIME\"},\"month\":{\"type\":\"month\",\"display_name\":\"Month\"},\"color\":{\"type\":\"color\",\"display_name\":\"COLOR\"}}},\"test_relation\":{\"display_name\":\"Test relation\",\"type\":\"relation\",\"relation_type\":\"belongsTo\",\"leaf_type\":\"admin-menu-item\",\"panel\":\"right\",\"nullable\":true},\"text_img\":{\"display_name\":\"Image\",\"type\":\"image\",\"default\":\"ellipsis-h\",\"panel\":\"right\"},\"order\":{\"display_name\":\"Order\",\"type\":\"number\",\"nullable\":true,\"panel\":\"right\"},\"parent_id\":{\"display_name\":\"Parent id\",\"type\":\"relation\",\"relation_type\":\"belongsTo\",\"leaf_type\":\"pages\",\"nullable\":true,\"panel\":\"right\"},\"is_active\":{\"display_name\":\"Is active\",\"type\":\"checkbox\",\"options\":{\"yes\":\"Yes\"},\"default\":true}},\"bread\":{\"browse\":{\"table_columns\":[\"title\",\"icon\"]}},\"conditions\":[{\"parameter\":\"leaf_type\",\"operator\":\"=\",\"value\":\"tests\"}]}";

		response = JSON.parse(response);

		var lcm = response.lcm;

		removeUnnecessaryFields();
		buildTabs(lcm);

		for (var k in lcm) {
			var field = lcm[k], panel, container;

			// IF Main fields
			if (field.type) {

				panel = field.panel;

				if ($('#main-section').find('.col-lg-9').length) {

					if (!panel || panel === "left")
						container = $('#main-section').find('.col-lg-9').eq(0).find('.card-body').eq(0);
					else if (panel && panel === "right")
						container = $('#main-section').find('.col-lg-3').eq(0).find('.card-body').eq(0);

				} else {
					container = $('#main-section').find('.col-lg-12').eq(0).find('.card-body').eq(0);
				}

				container.append(getLCMFieldHTML(field, k));

				// IF TABS
			} else {

				var tab = $('#myTab').find('.nav-link').filter(function () {
					if ($(this).text() === field.display_name) return true;
				});

				var tabPane = $(tab.attr('href')), rightPanelCounter = 0;

				/**/
				for (var j in field.fields) {
					if (field.fields[j].panel && field.fields[j].panel === "right") rightPanelCounter++;
				}

				rightPanelCounter > 0 ? tabPane.find('.row').eq(0).html('<div class="col-lg-9"><div class="card-body"></div></div> <div class="col-lg-3"><div class="card-body"></div></div>') : tabPane.find('.row').eq(0).html('<div class="col-lg-12"></div>');

				for (var j in field.fields) {

					panel = field.fields[j].panel;

					if (rightPanelCounter > 0) {
						if (!panel || panel === "left")
							container = tabPane.find('.col-lg-9').eq(0).find('.card-body').eq(0);
						else if (panel && panel === "right")
							container = tabPane.find('.col-lg-3').eq(0).find('.card-body').eq(0);
					} else {
						container = tabPane.find('.col-lg-12').eq(0).find('.card-body').eq(0);
					}

					container.append(getLCMFieldHTML(field.fields[j], j));
				}
			}
		}


		/* INIT ICHECK AND DATEPICKER */
		$('.icheck').iCheck({
			checkboxClass: 'icheckbox_flat-red',
			radioClass: 'iradio_flat-red'
		});

		if ($('.datepicker').length) $('.datepicker').datepicker();
	}

	function queryToGetLCM(url, data) {
		$.ajax({
			url: url,
			method: "POST",
			data: data,

			success: function (response) {
				console.log(response);

				removeUnnecessaryFields();
				buildTabs();
			},

			error: function (response) {
				console.log(response);
			}
		});
	}

	function removeUnnecessaryFields() {
		// Removing tabs and tab-panes
		$('#myTab').find('.nav-link:not(#main-section-tab)').remove();
		$('.tab-pane:not(#main-section)').remove();

		// Removing repeaters
		$('#main-section').find('.repeater').remove();

		// Removing unnecessary fields in #main-section
		$('#main-section').find('input, select, textarea').each(function () {

			var necessaryFieldsNames = ['title', 'slug', 'status_id', 'user_id', 'content', 'parent', 'template', 'tags[]', 'categories[]'],
				name = $(this).attr('name');

			if (/ql/g.test($(this).attr('class'))) return;
			if ($(this).parents('#quill').length) return;

			if (necessaryFieldsNames.indexOf(name) === -1) {

				$(this).parents('.mb-2').eq(0).prev().remove();
				$(this).parents('.mb-2').eq(0).remove();
			}

		});
	}


	function buildTabs(lcmObj) {

		var counter = 1, tabs = "", tabPanes = "";

		for (var key in lcmObj) {

			if (!lcmObj[key].type) {
				tabs += "<li class=\"nav-item\">\n" +
					"                        <a class=\"nav-link\" id=\"section-" + counter + "-tab\" data-toggle=\"tab\"\n" +
					"                           href=\"#section-" + counter + "\" role=\"tab\">" + lcmObj[key].display_name + "</a>\n" +
					"                    </li>";

				tabPanes += "<div class=\"tab-pane card shadow fade show\" id=\"section-" + counter + "\" role=\"tabpanel\"><div class='row'></div></div>";
			}
			counter++;
		}

		$('#myTab').append(tabs);
		$('#myTabContent').append(tabPanes);
	}

	function getLCMFieldHTML(fieldObj, field_name) {

		var
			html = "",
			label = fieldObj.display_name,
			options = fieldObj.options,
			type = fieldObj.type;


		switch (type) {
			case('password'):
			case('time'):
			case('color'):
			case('month'):
			case('number'):
			case('text'):
				html = "<label for=\"" + field_name + "\">" + label + "</label>\n" +
					"    <div class=\"input-group mb-2\">\n" +
					"        <input type=\"" + type + "\" name=\"" + field_name + "\" id=\"" + field_name + "\"\n" +
					"               class=\"form-control\"\n" +
					"               placeholder=\"" + field_name + "\"\n" +
					"               aria-label=\"" + field_name + "\">" +
					"    </div>";
				break;

			// REPEATER
			case "repeater":
				html = "<div class=\"repeater card shadow mb-5\">\n" +
					"        <div class=\"card-header\"><h5 class=\"text-primary font-weight-bold\">" + label + "</h5></div>\n" +
					"\n" +
					"        <div class=\"card-body\">\n" +
					"            <div class=\"rptr-field card shadow\">\n" +
					"\n" +
					"                <div class=\"rptr-field__delete delete-icon\">&times;</div>\n" +
					"\n" +
					"\n" +
					"                <div class=\"card-header text-primary font-weight-bold\">\n" +
					"                    Fields Row" +
					"                </div>\n" +
					"\n" +
					"                <div class=\"card-body\">\n";

				for (var subfield in fieldObj.fields) {
					html += getLCMFieldHTML(fieldObj.fields[subfield], subfield);
				}

				html += "<div class=\"rptr-field__add btn btn-sm btn-primary btn-icon-split\">\n" +
					"                    <span class=\"icon text-white-50\">\n" +
					"                             <i class=\"fas fa-plus\"></i>\n" +
					"                    </span>\n" +
					"                        <span class=\"text\"> Add row </span>\n" +
					"                    </div>\n" +
					"                </div>\n" +
					"            </div>\n" +
					"        </div>\n" +
					"    </div>";
				break;

			// DATE
			case "date":
				html = "<label for=\"" + field_name + "\">" + label + "</label>\n" +
					"    <div class=\"input-group mb-2\">\n" +
					"        <input type=\"text\" name=\"" + field_name + "\" id=\"" + field_name + "\"\n" +
					"               class=\"form-control datepicker\"\n" +
					"               placeholder=\"" + field_name + "\"\n" +
					"               aria-label=\"" + field_name + "\">" +
					"    </div>";
				break;

			// DATETIME
			case "datetime-local":
				html = "<label for=\"" + field_name + "\">" + label + "</label>\n" +
					"    <div class=\"input-group mb-2\">\n" +
					"        <input type=\"datetime-local\" name=\"" + field_name + "\" id=\"" + field_name + "\"\n" +
					"               class=\"form-control datetimepicker\"\n" +
					"               placeholder=\"" + field_name + "\"\n" +
					"               aria-label=\"" + field_name + "\">" +
					"    </div>";
				break;

			// RADIO CHECKBOX
			case "checkbox":
			case "radio":
				html = "<label>" + label + "</label>\n" +
					"        <div class=\" mb-2\">\n";

				if (options) {
					for (var opt in options) {
						html += "<div>" +
							"<label for=\"\" class=\"mr-2\"> " + options[opt] + " </label>  " +
							"<input type=\"" + type + "\" name=\"" + field_name + "\" id=\"" + opt + "\" value=\"" + opt + "\" class=\"icheck\">" +
							"</div>";
					}
				}

				html += "</div>";
				break;


			// FILE, FILE MULTIPLE
			case "file-multiple":
			case "file":
				html = "<div>" + field_name + "</div>\n" +
					"    <div class=\"input-group mb-2\">\n" +
					"        <div class=\"custom-file\">\n" +
					"            <input type=\"file\" name=\"" + field_name + "\"\n";
				if (type === "file-multiple") html += " multiple ";
				html += "        			class=\"custom-file-input\" id=\"" + field_name + "\" >" +
					"            <label class=\"custom-file-label\" for=\"" + field_name + "\"> Choose file </label>\n" +
					"        </div>\n" +
					"    </div>";
				break;


			case "select":
			case "select-multiple":
				html = "<label for=\"" + field_name + "\"> " + label + " </label>\n" +
					"    <div class=\"input-group mb-2\">\n" +
					"        <select name=\"" + field_name + "\" id=\"" + field_name + "\"\n";

				if (type === "select-multiple") html += " multiple ";
				html += "class=\"custom-select\">\n";

				for (var opt in options) {
					html += "<option value=\"" + opt + "\"> " + options[opt] + " </option>";
				}

				html += "</select>\n" +
					"    </div>";
				break;
		}

		return html;
	}
});