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
	return moment(val, format).isValid();
};