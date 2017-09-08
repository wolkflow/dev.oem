
/**
 * Функция добавления в корзину.
 */
window['oem-func-days-cart'] = function($block) {
    var pid = 0;
    
    var dates = $block.find('.js-days-datepicker').multiDatesPicker('getDates');

    // Общее количество часов.
    var quantity = dates.length;
    
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
window['oem-func-days-more'] = function($that) {
    var $wrapper = $that.closest('.js-product-wrapper');
    var $section = $wrapper.find('.js-product-section');
    var $block   = $wrapper.find('.js-product-block').first().clone();

    // Сброс параметров.
    window['oem-func-days-clear']($block);

    // Добавление блока.
    $section.append($block);

    $block.find('.js-product-select .js-option-noselect').trigger('click');
}


/**
 * Функция очистки данных.
 */
window['oem-func-days-clear'] = function($block) {
    $block.attr('data-bid', '');
    $block.find('.jq-selectbox__select,.jq-selectbox__dropdown').remove();
    $block.find('.styler').styler();
    $block.find('.js-product-select .js-option-noselect').trigger('click');
    
    $block.find('.js-product-price').html('');
    $block.find('.js-product-descr').html('');
    $block.find('.js-product-select-price').hide();
    $block.find('.js-product-select-descr').hide();
    
    $block.find('.js-days-times option:selected').attr('selected', null);
    $block.find('.js-days-datepicker').multiDatesPicker({
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
    $(document).on('click', '.js-block-days .js-more-field', function(event) {
        window['oem-func-days-more']($(this));
    });
    
    // Выбор даты.
    $('.js-block-days .js-days-datepicker').each(function() {
        var $that  = $(this);
        var $block = $that.closest('.js-product-block');
        
        $that.multiDatesPicker({
            dateFormat: 'dd.mm.yy',
            minDate: 0,
            autoclose: true,
            onSelect: function(date) {
                window['oem-func-days-cart']($block);
            }
        });
    });
    
});