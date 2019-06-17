$(document).ready(function () {


	$('#page-builder-components').on('click', '.btn', function (e) {
		e.preventDefault();

		var
			component = $('#page-builder-patterns').find('[data-component=' + $(this).attr('data-component') + ']'),
			thumbnail = component.attr('data-thumbnail'),
			componentHTML = component.html(),
			shortcodes = componentHTML.match(/\[[^\s\]]+\]/g);


		if (shortcodes) {
			shortcodes.forEach(function (item) {
				componentHTML = componentHTML.replace(item, buildFieldFromShortCode(item));
			});
		}

		componentHTML =
			"<div class=\"page-builder-content-item\" style=\"background-image: url(" + thumbnail + ")\">" +
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
			label = buildLabel(name);

			if (type !== "file") {
				return "<div class=\"mb-2\">" +
					"<label>" + label + "</label>" +
					"<input type=\"" + type + "\" name=\"" + name + "\" class=\"form-control\">" +
					"</div>";
			} else {
				return "<div class=\"mb-2\">" +
					"<label>" + label + "</label>" +
					"<div class=\"input-group\">\n" +
					"  <div class=\"custom-file\">\n" +
					"    <input type=\"file\" name=\"" + name + "\" class=\"custom-file-input\">\n" +
					"    <label class=\"custom-file-label\">Choose file</label>\n" +
					"  </div>\n" +
					"</div>" +
					"</div>";
			}

		} else if (shortcode[0] === "textarea") {

			name = shortcode[1];
			label = buildLabel(name);

			return "<div class=\"mb-2\"><label>" + label + "</label><textarea class=\"form-control\" name=\"" + name + "\"></textarea></div>"
		}


		function buildLabel(str) {
			var label = str.replace("_", " ");
			label = label[0].toUpperCase() + label.slice(1);

			return label;
		}
	}


	// Page builder item delete
	$('#page-builder-content').on("click", ".page-builder-content-item__delete", function (e) {
		e.stopPropagation();
		$(this).parent().remove();
	});

	// Page builder start edit
	$('#page-builder-content').on("click", ".page-builder-content-item", function (e) {

		var content = $(this).find("[hidden]").eq(0);
		filesBuffer = [];

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


		if (filesBuffer.length) {
			var i = 0;
			$("[data-editing]").find("input[type=file]").each(function () {
				$(this).prop("files", filesBuffer[i]);
				i++;
			});
		}

		$("[data-editing]").removeAttr("data-editing");

		$("#page-builder-modal .content").html("");
		$("#page-builder-modal").removeClass("visible");
	});


	$("#page-builder-modal").on("click", ".remove-modal", function (e) {
		$("#page-builder-modal").removeClass("visible");
	});


	var filesBuffer = [];

	/* Save value changes */
	$('#page-builder-modal').on("change", "input, textarea, select", function (e) {

		var type = $(this).prop("type");

		switch (type) {
			case "textarea":
				$(this).html($(this).val());
				break;

			case "file":
				$(this).next(".custom-file-label").text($(this).val());
				filesBuffer.push($(this).prop("files"));
				break;

			default:
				$(this).attr("value", $(this).val());
				break;
		}

	});
});