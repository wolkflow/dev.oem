$(function() {
	
    $("#email_confirm, #comMail").inputmask({
        mask:  "*{1,40}[.*{1,40}][.*{1,40}][.*{1,40}]@*{1,40}[.*{2,16}][.*{1,16}]",
        greedy: false
    });
	
	/*
	$("#email_confirm, #comMail").inputmask('Regex', {
		regex: "[а-яА-Яa-zA-Z0-9._%-]+@[.а-яА-Яa-zA-Z0-9-]+?\\.[а-яА-Яa-zA-Z]{2,16}",
		greedy: false
	});
	*/
});
