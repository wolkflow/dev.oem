$(function() {

    Inputmask("email").mask('#comMail, #email_confirm');

    
    /*
	$('#comMail, #email_confirm').inputmask({
		mask:  "*{1,40}[.*{1,40}][.*{1,40}][.*{1,40}]@*{1,40}[.*{2,16}][.*{1,16}]",
		greedy: false,
		onBeforePaste: function (pastedValue, opts) {
			pastedValue = pastedValue.toLowerCase();
			return pastedValue.replace("mailto:", "");
		},
		definitions: {
			'*': {
				validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
				cardinality: 1,
				casing: "lower"
			}
		}
	});

	 chg: 10/06
	 $("#comMail").inputmask({
	 mask:  "*{1,40}[.*{1,40}][.*{1,40}][.*{1,40}]@*{1,40}[.*{2,16}][.*{1,16}]",
	 greedy: false
	 });
	 */
	/*
	$("#email_confirm, #comMail").inputmask('Regex', {
		regex: "[а-яА-Яa-zA-Z0-9._%-]+@[.а-яА-Яa-zA-Z0-9-]+?\\.[а-яА-Яa-zA-Z]{2,16}",
		greedy: false
	});
	*/
});
