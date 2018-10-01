$(document).ready(function () {
	let dateFormat = 'dd.mm.yyyy';
	let timeFormat = 'HH:mm';

	$('.datepicker').flatpickr({
		'locale': 'cs',
		dateFormat: 'd.m.Y'
	});

	$('.timepicker').flatpickr({
		'locale': 'cs',
		enableTime: true,
		noCalendar: true,
		dateFormat: 'H:i',
		time_24hr: true
	});

	/**
	 * @return { boolean }
	 */
	Nette.validators.JanMotyckaFormsRulesFormRules_validateDate = function (elem, args, val) {
		return val !== "" ? moment(val, dateFormat).isValid() : true;
	};

	/**
	 * @return { boolean }
	 */
	Nette.validators.JanMotyckaFormsRulesFormRules_validateTime = function (elem, args, val) {
		return val !== "" ? moment(val, timeFormat).isValid() : true;
	};

	tinymce.init({
		selector: '.wysiwyg'
	});
});