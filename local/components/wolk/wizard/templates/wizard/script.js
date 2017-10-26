
/**
 * Показ ошибки.
 */
function ShowError(title, message)
{
    var $popup = $('#js-modal-error-id');

    $popup.find('.modalTitle').html(title);
    $popup.find('.modalContent').html(message);
    $popup.arcticmodal();
}


/**
 * Добавление позиции.
 */
function PutBasket(quantity, $block)
{
	if (blockremote == true) {
		return;
	}
	
	var pid = 0;
	
	// Получение ID продукции.
    if ($block.find('.js-product-select').length) {
        pid = $block.find('.js-product-select option:selected').val();
    } else {
        pid = $block.find('.js-product-element').val();
    }
	
    if (pid <= 0) {
        return;
    }
		
    var $wrapper = $('#js-wrapper-id');

    var bid    = $block.attr('data-bid');
    var sid    = $block.closest('.js-product-section').attr('data-sid');
    var action = (empty(bid)) ? ('put-basket') : ('update-basket');
    
	quantity = parseFloat(quantity);
	
    if (!empty(bid)) {
        if (quantity <= 0) {
            RemoveBasket(bid, sid, $block);
            return;
        }
        action = 'update-basket';
    } else {
        if (quantity == 0) {
            return;
        }
        action = 'put-basket';
    }

    // Проверка заполненности всех свойств товара.
    var params   = {};
    var reqprops = false;
    $block.find('.js-param-value').each(function() {
        var $that = $(this);
        if ($that.attr('name') == undefined) {
            return;
        }
        if ($that.hasClass('js-param-required') && $that.val().length == 0) {
            reqprops = true;
        }
        params[$that.attr('name')] = $that.val();
    });

    if (reqprops) {
        ShowError('Внимание!', 'Не заполнены все свойства товара');
        return;
    }
    
    // Отправка дополнительных полей.
    var fields = {};
    $block.find('.js-field-value').each(function() {
        var $that = $(this);
        if ($that.attr('name') == undefined) {
            return;
        }
        fields[$that.attr('name')] = $that.val();
    });
	
	
    
    // Отправка продукции в корзину.
    $.ajax({
        url: '/remote/',
        type: 'post',
        data: {
            'action':   action,
            'sessid':   BX.bitrix_sessid(),
            'pid':      pid,
            'bid':      bid,
            'eid':      $wrapper.data('eid'),
            'code':     $wrapper.data('code'),
            'type':     $wrapper.data('type'),
            'quantity': quantity,
            'kind':     'PRODUCTS', 
            'params':   params,
            'fields':   fields
        },
        dataType: 'json',
		beforeSend: function() {
			blockremote = true;
		},
        success: function(response) {
            if (response.status) {
                $('#js-basket-wrapper-id').html(response.data['html']);
                
                // Сохранение идентификатора элемента корзины.
                if (!empty(response.data['item'])) {
                    $block.attr('data-bid', response.data['item']['id']);
                }
            }
			blockremote = false;
        }
    });
}


/**
 * Удаление позиции.
 */
function RemoveBasket(bid, sid, $block)
{
	if (blockremote == true) {
		return;
	}
	
	if (bid.length <= 0) {
		return;
	}
	
    var $wrapper = $('#js-wrapper-id');

    if (empty($block)) {
        var $block = $('.js-product-block[data-bid="' + bid + '"]');
    }
    var $section = $block.closest('.js-product-section');

    $.ajax({
        url: '/remote/',
        type: 'post',
        data: {
            'action': 'remove-basket',
            'sessid': BX.bitrix_sessid(),
            'bid':    bid,
            'eid':    $wrapper.data('eid'),
            'code':   $wrapper.data('code'),
            'type':   $wrapper.data('type')
        },
        dataType: 'json',
		beforeSend: function() {
			blockremote = true;
		},
        success: function(response) {
            if (response.status) {
                $('#js-basket-wrapper-id').html(response.data['html']);

                if ($section.find('.js-product-block').length > 1) {
                    $block.remove();
                } else {
                    // Очистка данных в блоке.
                    window['oem-func-' + $block.data('price-type') + '-clear']($block);
                    
                    $block.attr('data-bid', '');
                    $block.find('.js-product-select .js-option-noselect').trigger('click');
                }

                // Сброс всех парамметров.
                ResetParams($block);
            }
			blockremote = false;
        }
    });
}


/**
 * Обновление свойтсва позиции.
 */
function UpdateBasketProps($prop)
{
	if (blockremote == true) {
		return;
	}
		
	var $wrapper = $('#js-wrapper-id');
	var $block   = $prop.closest('.js-product-block');
    
    var bid = $block.attr('data-bid');
	
	if (bid.length <= 0) {
		return
	}
	
	// Проверка заполненности всех свойств товара.
    var params   = {};
    var reqprops = false;
    $prop.find('.js-param-value').each(function() {
        var $that = $(this);
        if ($that.attr('name') == undefined) {
            return;
        }
        if ($that.hasClass('js-param-required') && $that.val().length == 0) {
            reqprops = true;
        }
        params[$that.attr('name')] = $that.val();
    });
		
	
	// Отправка свойства продукции.
    $.ajax({
        url: '/remote/',
        type: 'post',
        data: {
            'action':   'update-basket-property',
            'sessid':   BX.bitrix_sessid(),
            'bid':      bid,
            'eid':      $wrapper.data('eid'),
            'code':     $wrapper.data('code'),
            'type':     $wrapper.data('type'),
            'params':   params,
        },
        dataType: 'json',
		beforeSend: function() {
			blockremote = true;
		},
        success: function(response) {
            if (response.status) {
                $('#js-basket-wrapper-id').html(response.data['html']);
            }
			blockremote = false;
        }
    });
}


// Блокировка отправки данных.
var blockremote = false;


$(document).ready(function() {
	
	// Стилизация.
    $('.styler').styler();

	
    // Загрузка формы.
    $(document).on('click', '.js-submit', function(e) {
        $(this).closest('form').submit();
    });
	
	
	// Добавление нового поля.
    $(document).on('click', '.js-more-field', function(e) {
		var $block = $(this).closest('.js-product-wrapper').find('.js-product-block');
		
        window['oem-func-' + $block.data('price-type') + '-more']($block);
    });
	

    // Удаление из корзины.
    $(document).on('click', '.js-basket-remove', function(e) {
        var bid = $(this).data('bid');
        var sid = $(this).data('sid');

        if (bid.length > 0) {
            RemoveBasket(bid, sid);
        }
    });
    
    
    // Выбор товара из выпадающего списка.
    $(document).on('change', 'select.js-product-select', function(e) {
        var $that   = $(this);
        var $block  = $that.closest('.js-product-block');
        var $option = $that.find('option:selected');
		
		$block.find('.js-product-include').hide();
		
        if (!empty($option.val())) {
            $block.find('.js-product-price').html($option.data('price'));
            $block.find('.js-product-descr').html($option.data('descr'));
            $block.find('.js-product-select-price').show();
            $block.find('.js-product-select-descr').show();
			
			// Включенное оборедование.
			var include = parseInt($option.data('include'));
			if (include > 0) {
				$block.find('.js-product-include b').html(include);
				$block.find('.js-product-include').show();
			}
        } else {
            $block.find('.js-product-select-price').hide();
            $block.find('.js-product-select-descr').hide();
        }
        
        // Обновление данных в корзине.
        window['oem-func-' + $block.data('price-type') + '-cart']($block);
    });
    
    
    // Окно входа и регистрации - соглашение.
    $('#js-order-place-checkbox-auth-id').on('change', function(event) {
        var $block = $(this).closest('.js-modal-block');
        if ($(this).is(':checked')) {
            $block.find('#js-order-place-block-auth-id').removeClass('hide');
        } else {
            $block.find('#js-order-place-block-auth-id').addClass('hide');
        }
    });
    
	
    // Окно входа и регистрации - соглашение.
    $('#js-order-place-checkbox-unauth-id').on('change', function() {
        var $block = $(this).closest('.js-modal-block');
        if ($(this).is(':checked')) {
            $block.find('#js-order-place-block-unauth-id').removeClass('hide');
        } else {
            $block.find('#js-order-place-block-unauth-id').addClass('hide');
        }
    });
    
    
    // Удаление элемента корзины.
    $('.js-basket-delete').on('click', function() {
        var $that    = $(this);
        var $block   = $that.closest('.js-product-block');
        var $wrapper = $('#js-wrapper-id');
        
        // Идентификатор корзины.
        var bid = $that.data('bid');
        
        if (!bid.length) {
            return;
        }
        
        $.ajax({
            url: '/remote/',
            type: 'post',
            data: {
                'action': 'remove-basket',
                'sessid': BX.bitrix_sessid(),
                'bid':    bid,
                'eid':    $wrapper.data('eid'),
                'code':   $wrapper.data('code'),
                'type':   $wrapper.data('type')
            },
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    $block.remove();
                }
            }
        });
    });
    
    
	// Создание заказа.
	var orderblock = false;
    $('.js-remote-order-form').on('submit', function(e) {
        e.preventDefault();
		
		if (orderblock) {
			return;
		}
        orderblock = true;
		
        var $wrapper = $('#js-wrapper-id');
        var $form = $(this);
        
        var data = $form.objectize();
        
        data['sessid'] = BX.bitrix_sessid();
        data['action'] = 'place-order';
        data['eid']    = $wrapper.data('eid');
        data['code']   = $wrapper.data('code');
        data['type']   = $wrapper.data('type');
        
        $wrapper.find('.js-form-remote-input').each(function() {
            data[$(this).prop('name')] = $(this).val();
        });
        
        $.ajax({
            url: '/remote/',
            type: 'post',
            data: data,
            dataType: 'json',
            beforeSend: function() {
                $form.find('.errortext').html();
            },
            success: function(response) {
                if (response.status) {
                    $('#modal-order-success').arcticmodal({closeOnOverlayClick: false});
                } else {
                    $form.find('.errortext').html(response.message);
                }
				orderblock = false;
            },
			error: function(xhr, status, error) {
				orderblock = false;
			}
        });
        return false;
    });
});