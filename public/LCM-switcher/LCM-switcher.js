$(document).ready(function () {


	var
		leafType = $('#this-leaf-type').text(),
		leaftTypeCondition = "leaf-type:is:" + leafType;

	if ($('[name=template]').length)
		var pageTemplateCondition = "page-template:is:" + $("[name=template]").val();

	switchLCM([]);

	function switchLCM(conditionsArray) {

		// Default conditions
		conditionsArray.push(leaftTypeCondition);
		if (pageTemplateCondition) conditionsArray.push(pageTemplateCondition);

		$('[data-condition]').each(function () {
			var currentConditions = $(this).attr('data-condition').trim();

			if (currentConditions) {
				currentConditions = currentConditions.split(" ");

				var matches = 0;
				currentConditions.forEach(function (item) {

					if (/is/.test(item)) {
						if (conditionsArray.indexOf(item) !== -1) matches++;

					} else if (/not/.test(item)) {

						item = item.replace('not', 'is');
						if (conditionsArray.indexOf(item) === -1) matches++;
					}
				});

				if (matches === 0) {
					$(this).attr('hidden', true);
					$(this).find('input, select, textarea').attr('disabled', true);
				} else {
					$(this).removeAttr('hidden');
					$(this).find('input, select, textarea').removeAttr('disabled');
				}
			} else {
				$(this).removeAttr('hidden');
				$(this).find('input, select, textarea').removeAttr('disabled');
			}
		});
	}


	/*** FOR PAGE TEMPLATES ***/
	$('[name=template]').on('change', function () {
		var condition = "page-template:is:" + $(this).val();
		switchLCM([condition]);
	});
});