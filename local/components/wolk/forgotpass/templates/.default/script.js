$(function() {

    Inputmask("email").mask('.js-email-mask');
	
	
	// Восстановление пароля.
	$(document).on('click', '#js-recover-pass-submit-id', function(e) {
		e.preventDefault();
		
		var $form = $(this).closest('form');
		
		$.ajax({
			url: '/remote/',
			data: $form.serialize(),
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				$form.find('.js-alert').addClass('hide');
			},
			success: function(response) {
				if (response.status) {
					$form.find('.js-restore-success').removeClass('hide');
				} else {
					$form.find('.js-restore-error-' + response.data['error']).removeClass('hide');
				}
			}
		});
		
		return false;
	});
});