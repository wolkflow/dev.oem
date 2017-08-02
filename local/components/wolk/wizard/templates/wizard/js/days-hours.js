
/**
 * Функция добавления в корзину.
 */
window['oem-func-days-hours-cart'] = function($block) {
    var pid = 0;
    
    var $timemin = $block.find('.js-days-hours-time-min option:selected');
    var $timemax = $block.find('.js-days-hours-time-max option:selected');
    
    var dates = $block.find('.js-days-hours-datepicker').multiDatesPicker('getDates');
    var hours = Date.getHoursBetween(new Date('2000-01-01 ' + $timemin.val()), new Date('2000-01-01 ' + $timemax.val()));
    
    if (hours < 0) {
        hours = Date.getHoursBetween(new Date('2000-01-01 ' + $timemin.val()), new Date('2000-01-02 ' + $timemax.val()));
    }
    if (hours != 0) {
        hours = Math.abs(hours);
    }
    
    // Общее количество часов.
    var quantity = dates.length * hours;
    
    if (quantity < 0) {
        quantity = 0;
    }
    
    if ($block.find('.js-product-select').length) {
        pid = $block.find('.js-product-select option:selected').val();
    } else {
        pid = $block.find('.js-product-select').data('pid');
    }
    
    // Сохранение в корзине.
    PutBasket(pid, quantity, $block);
}


/**
 * Функция добавления товарной позиции.
 */
window['oem-func-days-hours-more'] = function($that) {
    var $wrapper = $that.closest('.js-product-wrapper');
    var $section = $wrapper.find('.js-product-section');
    var $block   = $wrapper.find('.js-product-block').first().clone();

    // Сброс параметров.
    window['oem-func-days-hours-clear']($block);

    // Добавление блока.
    $section.append($block);

    $block.find('.js-product-select .js-option-noselect').trigger('click');
}


/**
 * Функция очистки данных.
 */
window['oem-func-days-hours-clear'] = function($block) {
    $block.attr('data-bid', '');
    $block.find('.jq-selectbox__select,.jq-selectbox__dropdown').remove();
    $block.find('.styler').styler();
    $block.find('.js-product-select .js-option-noselect').trigger('click');
    
    $block.find('.js-product-price').html('');
    $block.find('.js-product-descr').html('');
    $block.find('.js-product-select-price').hide();
    $block.find('.js-product-select-descr').hide();
    
    $block.find('.js-days-hours-times option:selected').attr('selected', null);
    $block.find('.js-days-hours-datepicker').multiDatesPicker({
        dateFormat: 'dd.mm.yy',
        minDate:    0,
        autoclose:  true,
        onSelect:   function(date) {
            var dates;
        }
    });
    
    // Сброс всех свойств товара.
    ResetParams($block);
}


$(document).ready(function() {
    
    
    // Добавление нового поля.
    $(document).on('click', '.js-block-days-hours .js-more-field', function(event) {
        window['oem-func-days-hours-more']($(this));
    });
    
    
    // Выбор даты.
    $('.js-block-days-hours .js-days-hours-datepicker').each(function() {
        var $that  = $(this);
        var $block = $that.closest('.js-product-block');
        
        $that.multiDatesPicker({
            dateFormat: 'dd.mm.yy',
            minDate: 0,
            autoclose: true,
            onSelect: function(date) {
                window['oem-func-days-hours-cart']($block);
            }
        });
    });
    
    
    // Выбор времени.
    $(document).on('change', '.js-days-hours-times', function(event) {
        var $block = $(this).closest('.js-product-block');
        
        window['oem-func-days-hours-cart']($block);
    });
    
});