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

	// QUILL INIT
	if ($('#quill').length) {

		var quill = new Quill('#quill', {
			theme: 'snow',
			modules: {
				toolbar: toolbarOptions
			}
		});


		$('.ql-toolbar').css('width', '100%');
		$('form').on('submit', function (e) {
			$('[name=content]').val(quill.container.firstChild.innerHTML);
		});
	}
});