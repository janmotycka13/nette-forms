$(document).ready(function () {
	$('.datepicker').datepicker({
		language: 'cs',
		autoclose: true,
		format: 'dd.mm.yyyy'
	});
});

/**
 * @return { boolean }
 */
Nette.validators.JanMotyckaFormsRulesFormRules_validateDate = function(elem, args, val) {
	return moment(val).isValid();
};