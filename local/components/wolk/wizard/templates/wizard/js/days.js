
/**
 * Функция добавления в корзину.
 */
window['oem-func-days-cart'] = function($wrapper) {
    var pid = 0;
	var quantity = 0;
	
	var $block    = $wrapper.closest('.js-product-block');
    var $calendar = $wrapper.find('.js-calendar-content').find('.calendar');
	var $popup    = $wrapper.find('.js-calendar-popup');
	var $mode     = $wrapper.find('.js-calendar-mode');
    
	
	// Получение ID продукции.
    if ($block.find('.js-product-select').length) {
        pid = $block.find('.js-product-select option:selected').val();
    } else {
        pid = $block.find('.js-product-select').data('pid');
    }
	
	// Выбор диапазона или конкретных дат.
	if ($mode.is(':checked')) {
		var daymin = $calendar.parent().find('.date-min').val();
		var daymax = $calendar.parent().find('.date-max').val();
		
		quantity = getDaysBetween(daymin, daymax, true);
	} else {
		$days = $calendar.multiDatesPicker('getDates');
		
		quantity = $days.length;
	}
	
	// Закрытие календаря.
	CalendarClose($popup);
	
    if (quantity < 0) {
        quantity = 0;
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
    var minDate = $block.find('.calendar').attr('date-min'),
        maxDate = $block.find('.calendar').attr('date-max');

    // Сброс параметров.
    window['oem-func-days-clear']($block);

    // Добавление блока.
    $section.append($block);
    $block.find('.calendar').remove();
    $block.find('.calendarPopupContent').append('<div class="calendar" date-min="'+minDate+'" date-max="'+maxDate+'" />');
    $block.find('.calendar').multiDatesPicker({
        minDate: minDate,
        maxDate: maxDate
    });
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
    
	$block.find('.js-calendar-reset').trigger('click');
	
    // Сброс всех свойств товара.
    ResetParams($block);
}


$(document).ready(function() {
    
    // Добавление нового поля.
    $(document).on('click', '.js-block-days .js-more-field', function(e) {
        window['oem-func-days-more']($(this));
    });
    
    // Выбор даты.
	$(document).on('click', '.js-block-days .js-calendar-save', function(e) {
		var $block = $(this).closest('.js-days-wrapper');
		
		window['oem-func-days-cart']($block);
	});
});
