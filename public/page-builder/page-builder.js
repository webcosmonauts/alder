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


	$('#page-builder-components').on('click', '.btn', function (e) {
		e.preventDefault();

		/* FOR ALLOWED COMPONENTS */
		if (ALLOWED_COMPONENTS[$("[name=template]").val()]) {
			var allowedComponentAmount = ALLOWED_COMPONENTS[$("[name=template]").val()][$(this).attr("data-component")];

			console.log(allowedComponentAmount);
			if (allowedComponentAmount) {
				if (allowedComponentAmount <= $('.page-builder-content-item[data-component=' + $(this).attr("data-component") + ']').length) {
					return false;
				}
			}
		}

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


		var actions =
			"<div class=\"page-builder-content-item__actions\">" +
			"<div class=\"circle-icon\" title=\"W górę\" data-action=\"up\"><em class='fa fa-angle-up'></em></div>" +
			"<div class=\"circle-icon\" title=\"Na dół\" data-action=\"down\"><em class='fa fa-angle-down'></em></div>" +
			"</div>";


		componentHTML =
			"<div class=\"page-builder-content-item\" data-component=\"" + $(this).attr('data-component') + "\" style=\"background-image: url(" + thumbnail + ")\">" +
			"<div class=\"page-builder-content-item__delete delete-icon\">&times;</div>" + actions +
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
			label = shortcode[3] || "";

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
					"            <button class=\"btn btn-sm mb-0 mt-0 btn-outline-secondary button-image\" style=\"height: 35px;\"  type=\"button\">Wybierz</button>\n" +
					"        </div>\n" +
					"    </div>" +
					"    </div>";
			}

		} else if (shortcode[0] === "textarea") {

			name = shortcode[1];
			label = shortcode[2] || "";

			return "<div class=\"mb-2\"><label>" + label + "</label><textarea class=\"form-control\" name=\"" + name + "\"></textarea></div>"
		}
	}


	// Page builder item delete
	$('#page-builder-content').on("click", ".page-builder-content-item__delete", function (e) {
		e.stopPropagation();

		var item = $(this).parent();
		item.css({"opacity": "0"});

		setTimeout(function () {
			item.remove();
		}, 600);
	});


	// Page builder item move
	$('#page-builder-content').on("click", ".page-builder-content-item__actions .circle-icon", function (e) {
		e.stopPropagation();

		if ($('.page-builder-content-item').length === 1) return false;
		var
			action = $(this).attr('data-action'),
			currentItem = $(this).parents('.page-builder-content-item').eq(0),
			clone = currentItem.clone();


		if (action === "up") {
			if (currentItem.prev('.page-builder-content-item').length) {
				currentItem.prev().before(clone);
				currentItem.remove();
				animateAndScroll();
			}

		} else if (action === "down") {

			if (currentItem.next('.page-builder-content-item').length) {
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

	// Page builder start edit
	$('#page-builder-content').on("click", ".page-builder-content-item", function (e) {
		var content = $(this).find("[hidden]").eq(0);

		$("[data-editing]").removeAttr("data-editing");
		content.attr("data-editing", true);

		$("#page-builder-modal").find(".content").html(content.html());


		/* TRIGGER SELECTS*/
		$("#page-builder-modal").find("select").each(function () {

			var attrValue = $(this).attr("value");

			if (attrValue) {
				$(this).prop("selectedIndex", $(this).find("option[value=" + attrValue + "]").index());
				$(this).change();
			}
		});


		/* init Quill */
		$('#page-builder-modal').find("textarea").each(function () {
			$(this).attr("hidden", true);
			$(this).after("<div class=\"quill\" style=\"height: 300px; width: 100%\"></div>");
			initQuill($(this).next()[0], $(this).text());
		});

		// Show modal
		$("#page-builder-modal").addClass("visible");
		$('body').css("overflow", "hidden");
	});

	// Save changes
	$("#page-builder-modal-save").on("click", function (e) {
		e.preventDefault();
		e.stopPropagation();


		/*store content form QUILL*/
		$("#page-builder-modal").find(".ql-editor").each(function () {

			var content = $(this).html();

			$(this).parent().prev().prev('textarea').html(content).removeAttr("hidden");
			$(this).parent().prev(".ql-toolbar").remove();
			$(this).parent().remove();
		});


		$("[data-editing]").html($("#page-builder-modal .content").html());

		/* TRIGGER SELECTS*/
		$("[data-editing]").find("select").each(function () {

			var attrValue = $(this).attr("value");

			if (attrValue) {
				$(this).prop("selectedIndex", $(this).find("option[value=" + attrValue + "]").index());
				$(this).change();
			}
		});

		$("[data-editing]").removeAttr("data-editing");

		$("#page-builder-modal .content").html("");
		$("#page-builder-modal").removeClass("visible");
		$('body').css("overflow", "auto");
	});

	// Close modal
	$("#page-builder-modal").on("click", ".close-modal", function (e) {
		$("#page-builder-modal").removeClass("visible");
		$('body').css("overflow", "auto");
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
				$fields = content.find("input, select, textarea").filter(function () {
					if (!$(this).parents('.rptr-field').length) return true;
				}),

				$repeaters = content.find(".repeater"),
				componentObj = {
					component: componentType,
					fields: {}
				};


			/*Repeaters */
			if ($repeaters.length) {
				var counter = 1;
				$repeaters.each(function () {
					componentObj.fields["repeater_" + counter] = [];

					$(this).find('.rptr-field').each(function () {
						var obj = {};

						$(this).find("input, select, textarea").each(function () {
							obj[$(this).attr("name")] = $(this).val();
							$(this).attr("disabled", true);
						});

						componentObj.fields["repeater_" + counter].push(obj);
					});
				});
			}

			/**/
			$fields.each(function () {
				componentObj.fields[$(this).attr("name")] = $(this).val();
				$(this).attr("disabled", true);
			});


			//
			contentHTMLJSON.push(componentObj);
		});


		contentHTMLJSON = JSON.stringify(contentHTMLJSON);
		$("[name=content]").val(contentHTMLJSON);
	});


	/* ALLOWED COMPONENTS FOR TEMPLATE */

	var ALLOWED_COMPONENTS = {
		"kontakt": {
			"text_with_map": 1
		},

		"o-prokecie": {
			"html": 1
		},

		"dynamic-raport": {},
		"dynamic-raport1": {},
		"dynamic-raport2": {},
		"dynamic-raport3": {},
		"to-download": {}
	};


	function chooseAllowedComponents(template) {
		if (ALLOWED_COMPONENTS[template]) {

			$('#page-builder-components').find("[data-component]").attr("hidden", true);
			for (var key in ALLOWED_COMPONENTS[template]) {
				$('#page-builder-components').find("[data-component=" + key + "]").removeAttr('hidden');
			}

			// delete not allowed components
			var unnecessaryItems = $("#page-builder-content").find(".page-builder-content-item").filter(function () {
				if (!ALLOWED_COMPONENTS[template][$(this).attr("data-component")]) return true;
			});
			unnecessaryItems.remove();

			// delete allowed components if they are more then need
			for (var key in ALLOWED_COMPONENTS[template]) {
				if ($(".page-builder-content-item[data-component=" + key + "]").length > ALLOWED_COMPONENTS[template][key]) {

					var counter = 1;
					$(".page-builder-content-item[data-component=" + key + "]").each(function () {
						if (counter > ALLOWED_COMPONENTS[template][key]) $(this).remove();
						counter++;
					});
				}
			}

		} else {
			$('#page-builder-components').find("[data-component]").removeAttr("hidden");
		}
	}


	chooseAllowedComponents($("[name=template]").val());
	$("[name=template]").on("change", function () {
		chooseAllowedComponents($(this).val());
	});
});


