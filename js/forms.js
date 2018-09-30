$(document).ready(function () {
	let format = 'dd.mm.yyyy';

	$('.datepicker').flatpickr({
		'locale': 'cs',
		dateFormat: 'd.m.Y'
	});

	/**
	 * @return { boolean }
	 */
	Nette.validators.JanMotyckaFormsRulesFormRules_validateDate = function (elem, args, val) {
		return val !== "" ? moment(val, format).isValid() : true;
	};

	tinymce.init({
		selector: '.wysiwyg'
	});
});