
/**
 * Функция добавления в корзину.
 */
window['oem-func-days-cart'] = function($block) {
    var pid = 0;
    
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
	
	
	var $that     = $(this);
	var $block    = $that.closest('.js-product-block');
	
	var $calendar = $that.parents('.js-calendar-content').find('.calendar');
	var $wrapper  = $that.closest('.js-calendar-wrap');
	var $popup    = $wrapper.find('.js-calendar-popup');
	var $mode     = $wrapper.find('.js-calendar-mode');
			
	if ($mode.is(':checked')) {
		$daymin = $calendar.parent().find('.date-min').val();
		$daymax = $calendar.parent().find('.date-max').val();
	} else {
		$days = $calendar.multiDatesPicker('getDates');
	}
	
	CalendarClose($popup);
	
	
	
	
    
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
	$(document).on('click', '.js-block-days .js-calendar-save', function(e) {
		window['oem-func-days-cart']($block);
	});
	
	/*
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
	*/
});
