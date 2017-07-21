
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
 * Количество часов между датами.
 */
Date.getHoursBetween = function (date1, date2) {
    var one_hour = 1000 * 60 * 60;

    var date1_ms = date1.getTime();
    var date2_ms = date2.getTime();
	
    var difference_ms = parseInt(date2_ms - date1_ms);
	
    return Math.round(difference_ms / one_hour);
};


/**
 * Количество дней между датами.
 */
Date.getDaysBetween = function (date1, date2, including) {
    var one_day = 1000 * 60 * 60 * 24;
	
    var date1_ms = date1.getTime();
    var date2_ms = date2.getTime();

    var difference_ms = parseInt(date2_ms - date1_ms);

    if (including) {
        return Math.round(difference_ms / one_day) + 1;
    } else {
        return Math.round(difference_ms / one_day);
    }
};


/**
 * Добавление позиции.
 */
function PutBasket(pid, quantity, $block)
{
    if (pid <= 0) {
        return;
    }
    var $wrapper = $('#js-wrapper-id');

    var bid    = $block.attr('data-bid');
    var sid    = $block.closest('.js-product-section').attr('data-sid');
    var action = (empty(bid)) ? ('put-basket') : ('update-basket');
    
    
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
        showError('Внимание!', 'Не заполнены все свойства товара');
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
        success: function(response) {
            if (response.status) {
                $('#js-basket-wrapper-id').html(response.data['html']);
                
                // Сохранение идентификатора элемента корзины.
                if (!empty(response.data['item'])) {
                    $block.attr('data-bid', response.data['item']['id']);
                }
            }
        }
    });
}


/**
 * Удаление позиции.
 */
function RemoveBasket(bid, sid, $block)
{
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
        success: function(response) {
            if (response.status) {
                $('#js-basket-wrapper-id').html(response.data['html']);

                if ($section.find('.js-product-block').length > 1) {
                    $block.remove();
                } else {
                    $block.find('.js-quantity').val(0);
                    $block.attr('data-bid', '');
                    $block.find('.js-product-select .js-option-noselect').trigger('click');
                }

                // Сброс всех парамметров.
                ResetParams($block);
            }
        }
    });
}



$(document).ready(function() {

    // Стилизация.
    $('.styler').styler();

    // Загрузка формы.
    $(document).on('click', '.js-submit', function(event) {
        $(this).closest('form').submit();
    });


    // Удаление из корзины.
    $(document).on('click', '.js-basket-remove', function(event) {
        var bid = $(this).data('bid');
        var sid = $(this).data('sid');

        if (bid.length > 0) {
            RemoveBasket(bid, sid);
        }
    });
    
    
    // Выбор товара из выпадающего списка.
    $(document).on('change', '.js-product-select', function(event) {
        var $that   = $(this);
        var $block  = $that.closest('.js-product-block');
        var $option = $that.find('option:selected');

        if (!empty($option.val())) {
            $block.find('.js-product-price').html($option.data('price'));
            $block.find('.js-product-descr').html($option.data('descr'));
            $block.find('.js-product-select-price').show();
            $block.find('.js-product-select-descr').show();
        } else {
            $block.find('.js-product-select-price').hide();
            $block.find('.js-product-select-descr').hide();
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