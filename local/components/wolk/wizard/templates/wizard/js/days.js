
/**
 * Функция добавления в корзину.
 */
window['oem-func-days-cart'] = function($block) {

	var quantity = 0;
	
    var $calendar = $block.find('.js-calendar-content').find('.calendar');
	var $popup    = $block.find('.js-calendar-popup');
	var $mode     = $block.find('.js-calendar-mode');
    var $note     = $block.find('.dates');
	
	
	// Выбор диапазона или конкретных дат.
	if ($mode.is(':checked')) {
		var daymin = $block.find('.min-date').val();
		var daymax = $block.find('.max-date').val();
		
		// Комментарий.
		$note.text(Date.getDateFormat(new Date(daymin)) + ' - ' + Date.getDateFormat(new Date(daymax)));
		
		// Общее количество.
		quantity = Date.getDaysBetween(new Date(daymin), new Date(daymax), true);
	} else {
		var days = $calendar.multiDatesPicker('getDates');
		
		for (let i in days) {
			days[i] = Date.getDateFormat(new Date(days[i]));
		}
		
		// Комментарий.
		$note.text(days.join(', '));
		
		// Общее количество.
		quantity = days.length;
	}
	
	// Протсавление дополнительных параметров.
	$block.find('.js-product-days-dates').val($note.text());
	
	// Закрытие календаря.
	CalendarClose($popup);
	
    if (quantity < 0) {
        quantity = 0;
    }	
	
    // Сохранение в корзине.
    PutBasket(quantity, $block);
}


/**
 * Функция добавления товарной позиции.
 */
window['oem-func-days-more'] = function($block) {
    var $wrapper = $block.closest('.js-product-wrapper');
    var $section = $wrapper.find('.js-product-section');
    var $clone   = $wrapper.find('.js-product-block').first().clone();
    var mindate  = $clone.find('.calendar').attr('data-date-min'),
        maxdate  = $clone.find('.calendar').attr('data-date-max');
		
	$clone.find('.jq-selectbox__select, .jq-selectbox__dropdown').remove();

    // Сброс параметров.
    window['oem-func-days-clear']($clone);

    // Добавление блока.
    $section.append($clone);
    $clone.find('.calendar').remove();
    $clone.find('.calendarPopupContent').append('<div class="calendar" data-date-min="' + mindate + '" data-date-max="' + maxdate+ '" />');
    $clone.find('.calendar').multiDatesPicker({
        minDate: new Date(mindate),
        maxDate: new Date(maxdate)
    });
    $clone.find('.js-product-select .js-option-noselect').trigger('click');
}


/**
 * Функция очистки данных.
 */
window['oem-func-days-clear'] = function($block) {
	var mindate  = $block.find('.calendar').attr('data-date-min'),
        maxdate  = $block.find('.calendar').attr('data-date-max');
	
    $block.attr('data-bid', '');
    //$block.find('.jq-selectbox__select, .jq-selectbox__dropdown').remove();
    $block.find('.styler').styler();
    $block.find('.js-product-select .js-option-noselect').trigger('click');
    
	$block.find('input.js-param-value').val('');
	$block.find('textarea.js-param-value').html('');
	
    $block.find('.js-product-price').html('');
    $block.find('.js-product-descr').html('');
    $block.find('.js-product-select-price').hide();
    $block.find('.js-product-select-descr').hide();
    
	$block.find('.calendar').remove();
    $block.find('.calendarPopupContent').append('<div class="calendar" data-date-min="' + mindate + '" data-date-max="' + maxdate+ '" />');
    $block.find('.calendar').multiDatesPicker({
        minDate: new Date(mindate),
        maxDate: new Date(maxdate)
    });
	
	$block.find('.js-calendar-reset').trigger('click');
	
    // Сброс всех свойств товара.
    ResetParams($block);
}


$(document).ready(function() {
    
    // Выбор даты.
	$(document).on('click', '.js-block-days .js-calendar-save', function(e) {
		var $block = $(this).closest('.js-product-block');
		
		window['oem-func-days-cart']($block);
	});
});
