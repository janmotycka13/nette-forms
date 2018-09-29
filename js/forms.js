let format = 'dd.mm.yyyy';

$(document).ready(function () {
	$('.datepicker').datepicker({
		language: 'cs',
		autoclose: true,
		format: format
	});
});

/**
 * @return { boolean }
 */
Nette.validators.JanMotyckaFormsRulesFormRules_validateDate = function(elem, args, val) {
	return val !== "" ? moment(val, format).isValid() : true;
};

tinymce.init({
	selector: '.wysiwyg'
});