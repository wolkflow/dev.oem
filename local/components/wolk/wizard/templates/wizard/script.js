$(document).ready(function() {
    
    // Загрузка формы.
    $(document).on('click', '.js-submit', function(event) {
        $(this).closest('form').submit();
    });
    
    
    
    // Выбор стенда.
    $(document).on('click', '.js-stand-choose-button', function(event) {
        var $that  = $(this);
        var $wrap  = $('#js-stands-wrapper-id');
        var $stand = $that.closest('.js-stand-block');
        
        // ID стенда.
        var sid = $that.data('id');
        
        // Выбранный стенд.
        var $prestand = $('#js-prestand-id');
        
        // Установка данных.
        $prestand.find('.js-stand-description').html($stand.find('.js-stand-description').html() || '');
        $prestand.find('.js-stand-includes').html($stand.find('.js-stand-includes').html() || '');
        $prestand.find('.js-stand-image').prop('src', $stand.find('.js-stand-image').prop('src'));
        
        // Установка кнопки выбрано.
        $wrap.find('.js-stand-choose-button').removeClass('current').html(jsvars.LANGS['CHOOSE']);
        $wrap.find('.js-stand-choose-button[data-id="' + sid + '"]').addClass('current').html(jsvars.LANGS['CHOOSEN']);
        
        // Установка текущего стенда.
        $('#js-form-input-stand-id').val($that.data('id'));
    });
});