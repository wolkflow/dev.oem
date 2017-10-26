$(document).ready(function() {
	
	// Выбор стенда.
    $(document).on('click', '.js-stand-choose-button', function(event) {
        var $that  = $(this);
        var $wrap  = $('#js-stands-wrapper-id');
        var $stand = $that.closest('.js-stand-block');

        // ID стенда.
        var sid = $that.data('id');

		// Контейнер выбранного стенда.
		var $preselect = $('#js-preselect-wrapper-id');
		var $noselect  = $('#js-noselect-wrapper-id');
		var standtype  = $preselect.data('type');
		
        // Выбранный стенд.
        var $prestand = $('#js-prestand-id');
		
		if (standtype == 'individual') {
			// выбор конфигурации стен стенда.
			$('#js-standtype-button-id').attr('data-sid', sid);
			$('#js-standtype-id').arcticmodal({closeOnOverlayClick: false, closeOnEsc: false});
		} else {
		
			// Установка данных.
			$prestand.find('.js-stand-description').html($stand.find('.js-stand-description').html() || '');
			$prestand.find('.js-stand-includes').html($stand.find('.js-stand-includes').html() || '');
			$prestand.find('.js-stand-image').prop('src', $stand.find('.js-stand-image').prop('src'));

			// Установка кнопки выбрано.
			$wrap.find('.js-stand-choose-button').removeClass('current').html(jsvars.LANGS['CHOOSE']);
			$wrap.find('.js-stand-choose-button[data-id="' + sid + '"]').addClass('current').html(jsvars.LANGS['CHOOSEN']);
			
			$preselect.show();
			$noselect.hide();
			
			// Установка текущего стенда.
			$('#js-form-input-stand-id').val(sid);
		}
    });
	
	
	// Выбор конфигурации стен стенда.
	$(document).on('change', '.js-standtype-label input[type="radio"]', function(event) {
		$('#js-standtype-button-id').attr('disabled', false);
	});
	
	
	// Сохранение выбора конфигурации стен стенда.
	$(document).on('click', '#js-standtype-button-id', function(event) {
		var $that = $(this);
        var $wrap = $('#js-stands-wrapper-id');
        
		// Контейнер выбранного стенда.
		var $preselect = $('#js-preselect-wrapper-id');
		var $noselect  = $('#js-noselect-wrapper-id');
		var standtype  = $preselect.data('type');
		
		// Выбранный стенд.
        var $prestand = $('#js-prestand-id');
		
        // ID стенда.
        var sid   = $that.attr('data-sid');
		var sform = $that.closest('.js-standtype-form-id').find('.js-standtype-label input:checked').val();
		
		// Выбранный стенд.
		var $stand = $wrap.find('.js-stand-block-' + sid);
		
		// Установка данных.
		$prestand.find('.js-stand-description').html($stand.find('.js-stand-description').html() || '');
		$prestand.find('.js-stand-includes').html($stand.find('.js-stand-includes').html() || '');
		$prestand.find('.js-stand-image').prop('src', $stand.find('.js-stand-image').prop('src'));
		
		
		
		// Установка кнопки выбрано.
		$wrap.find('.js-stand-choose-button').removeClass('current').html(jsvars.LANGS['CHOOSE']);
		$wrap.find('.js-stand-choose-button[data-id="' + sid + '"]').addClass('current').html(jsvars.LANGS['CHOOSEN']);
		
		// Установка текущего стенда.
		$('#js-form-input-stand-id').val(sid);
		$('#js-form-input-sform-id').val(sform);
		
		$preselect.show();
		$noselect.hide();
		
		$.arcticmodal('close');
	});
	
});