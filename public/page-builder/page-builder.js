$(document).ready(function () {


	$('#page-builder-components').on('click', '.btn', function (e) {
		e.preventDefault();

		var
			component = $('#page-builder-patterns').find('[data-component=' + $(this).attr('data-component') + ']'),
			thumbnail = component.attr('data-thumbnail'),
			componentHTML = component.html(),
			shortcodes = componentHTML.match(/\[[^\]\[]+\]/g);


		if (shortcodes) {
			shortcodes.forEach(function (item) {
				componentHTML = componentHTML.replace(item, buildFieldFromShortCode(item));
			});
		}

		componentHTML =
			"<div class=\"page-builder-content-item\" data-component=\"" + $(this).attr('data-component') + "\" style=\"background-image: url(" + thumbnail + ")\">" +
			"<div class=\"page-builder-content-item__delete delete-icon\">&times;</div>" +
			"<div hidden>" + componentHTML + "</div>" +
			"</div>";

		$('#page-builder-content').append(componentHTML);
	});


	function buildFieldFromShortCode(shortcode) {

		shortcode = shortcode.replace(/[\[\]]/g, "");
		shortcode = shortcode.split(":");

		var type, name, label;

		if (shortcode[0] === "input") {

			type = shortcode[1];
			name = shortcode[2];
			label = shortcode[3];

			if (type !== "file") {
				return "<div class=\"mb-2\">" +
					"<label>" + label + "</label>" +
					"<input type=\"" + type + "\" name=\"" + name + "\" class=\"form-control\">" +
					"</div>";
			} else {
				return "<div class=\"mb-2\">" +
					"<label>" + label + "</label>" +
					"<div class=\"input-group\">\n" +
					"        <input type=\"text\" class=\"image_label form-control\" name=\"" + name + "\"\n" +
					"               aria-label=\"Image\" aria-describedby=\"button-image\">\n" +
					"        <div class=\"input-group-append\">\n" +
					"            <button class=\"btn btn-outline-secondary button-image\"  type=\"button\">Select</button>\n" +
					"        </div>\n" +
					"    </div>" +
					"    </div>";
			}

		} else if (shortcode[0] === "textarea") {

			name = shortcode[1];
			label = shortcode[2];

			return "<div class=\"mb-2\"><label>" + label + "</label><textarea class=\"form-control\" name=\"" + name + "\"></textarea></div>"
		}
	}


	$('body').on("click", '.button-image', function (e) {
		e.preventDefault();
		$("[data-current-file]").removeAttr("data-current-file");
		$(this).parent().prev().attr("data-current-file", true);
		window.open('/file-manager/fm-button', 'fm', 'width=1000,height=600,top=200,left=400');
	});


	// Page builder item delete
	$('#page-builder-content').on("click", ".page-builder-content-item__delete", function (e) {
		e.stopPropagation();
		$(this).parent().remove();
	});

	// Page builder start edit
	$('#page-builder-content').on("click", ".page-builder-content-item", function (e) {

		var content = $(this).find("[hidden]").eq(0);

		$("[data-editing]").removeAttr("data-editing");
		content.attr("data-editing", true);

		$("#page-builder-modal").find(".content").html(content.html());
		$("#page-builder-modal").addClass("visible");
	});

	// Save changes
	$("#page-builder-modal-save").on("click", function (e) {
		e.preventDefault();
		e.stopPropagation();

		$("[data-editing]").html($("#page-builder-modal .content").html());
		$("[data-editing]").removeAttr("data-editing");

		$("#page-builder-modal .content").html("");
		$("#page-builder-modal").removeClass("visible");
	});

	// Close modal
	$("#page-builder-modal").on("click", ".close-modal", function (e) {
		$("#page-builder-modal").removeClass("visible");
	});

	/* Save value changes when we edit component */
	$('#page-builder-modal').on("change", "input, textarea, select", function (e) {

		var type = $(this).prop("type");
		switch (type) {
			case "textarea":
				$(this).html($(this).val());
				break;

			default:
				$(this).attr("value", $(this).val());
				break;
		}
	});


	/* PARSE PAGE BUILDER INTO HTML */
	$("#edit-form").on("submit", function (e) {

		var contentHTMLJSON = [];

		$('#page-builder-content').find(".page-builder-content-item").each(function () {

			var
				content = $(this).find("[hidden]"),
				componentType = $(this).attr("data-component"),
				$fields = content.find("input, select, textarea"),
				$rptrFields = content.find(".rptr-field"),
				componentObj = {
					component: componentType,
					fields: {}
				};

			switch (componentType) {
				case "circle_diagram":
				case "partners":
				case "tiles":
				case "slider":
					componentObj.fields = [];

					$rptrFields.each(function () {
						var obj = {};
						$(this).find("input, select, textarea").each(function () {
							obj[$(this).attr("name")] = $(this).val();
							$(this).attr("disabled", true);
						});

						componentObj.fields.push(obj);
					});
					break;

				case "center_text_tile":
				case "img_left_text_right":
					$fields.each(function () {
						componentObj.fields[$(this).attr("name")] = $(this).val();
						$(this).attr("disabled", true);
					});
					break;

				case "center_image":
					var bigImgUrl = content.find("[name=image]").val();
					content.find("[name=image]").attr("disabled", "true");
					componentObj.fields.image = bigImgUrl;
					break;
			}

			contentHTMLJSON.push(componentObj);
		});


		contentHTMLJSON = JSON.stringify(contentHTMLJSON);
		$("[name=content]").val(contentHTMLJSON);
	});
});


// set file link
function fmSetLink($url) {
	$('.image_label[data-current-file]').val($url).attr("value", $url);
	$("[data-current-file]").removeAttr("data-current-file");
}