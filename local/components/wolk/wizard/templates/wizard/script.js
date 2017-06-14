function PutBasket(pid, quantity, params, $self)
{
    if (pid <= 0) {
        return;
    }
    
    if (quantity <= 0) {
        return;
    }
    
    // Дополнительные параметра.
    // params = params || [];
    
    var $wrapper = $('#js-wrapper-id');
    var $section = $self.closest('.js-product-section');
    
    // Отправка продукции в корзину.
    $.ajax({
        url: '/remote/',
        type: 'post',
        data: {
            'action':   'put-basket',
            'pid':      pid,
            'bid':      $section.data('bid'),
            'eid':      $wrapper.data('eid'),
            'code':     $wrapper.data('code'),
            'type':     $wrapper.data('type'),
            'quantity': quantity,
            'kind':     'PRODUCTS', 
            'params':   params
        },
        dataType: 'json',
        success: function(response) {
            if (response.status) {
                $('#js-basket-wrapper-id').html(response.data['html']);
                
                // Сохранение идентификатора элемента корзины.
                $section.attr('data-bid', response.data['item']['id']);
            }
        }
    });
}


$(document).ready(function() {
    
    // Загрузка формы.
    $(document).on('click', '.js-submit', function(event) {
        $(this).closest('form').submit();
    });
    
    
    $(document).on('click', '.js-pricetype-quantity .js-quantity-dec', function() {
		var $wrapper = $(this).parent();
		var $input   = $wrapper.find('input');
        var quantity = parseInt($input.val()) - 1;
        
        if (quantity < 0) {
            quantity = 0;
        }
		$input.val(quantity);
		$input.change();
        
        PutBasket($wrapper.data('pid'), quantity, [], $(this));
        
		return false;
	});
    
    $(document).on('click', '.js-pricetype-quantity .js-quantity-inc', function() {
        var $wrapper = $(this).parent();
		var $input   = $wrapper.find('input');
        var quantity = parseInt($input.val()) + 1;
        
		$input.val(quantity);
		$input.change();
        
        PutBasket($wrapper.data('pid'), quantity, [], $(this));
        
		return false;
	});
    
    // Удаление из корзины.
    $(document).on('click', '.js-basket-remove', function(event) {
        var bid = $(this).data('bid');
        
        if (bid.length > 0) {
            var $wrapper = $('#js-wrapper-id');
            
            $.ajax({
                url: '/remote/',
                type: 'post',
                data: {
                    'action': 'remove-basket',
                    'bid':    bid,
                    'eid':    $wrapper.data('eid'),
                    'code':   $wrapper.data('code'),
                    'type':   $wrapper.data('type')
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        $('#js-basket-wrapper-id').html(response.data['html']);
                        $('.js-product-section[data-bid="' + bid + '"] .js-quantity').val(0);
                    }
                }
            });
        }
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