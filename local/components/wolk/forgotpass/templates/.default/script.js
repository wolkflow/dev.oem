$(function() {

    Inputmask("email").mask('.js-email-mask');
	
	
	// Восстановление пароля.
	$(document).on('click', '#js-recover-pass-submit-id', function(e) {
		var $form = $(this).closest('form');
		
		$.ajax({
			url: '/remote/',
			data: $form.serialize(),
			type: 'post',
			dataType: 'json',
			success: function(response) {
				if (response.status) {
					
				} else {
					
				}
			}			
		});
	});
});