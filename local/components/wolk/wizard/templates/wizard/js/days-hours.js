
/**
 * Функция добавления в корзину.
 */
window['oem-func-days-hours-cart'] = function($block) {
	var pid = 0;
	var quantity = 0;
	
    var $calendar = $block.find('.js-calendar-content').find('.calendar');
	var $popup    = $block.find('.js-calendar-popup');
	var $mode     = $block.find('.js-calendar-mode');
    var $note     = $block.find('.dates');
	
	var $timemin = $block.find('.js-days-hours-time-min option:selected');
    var $timemax = $block.find('.js-days-hours-time-max option:selected');
	
	
	// Получение ID продукции.
    if ($block.find('.js-product-select').length) {
        pid = $block.find('.js-product-select option:selected').val();
    } else {
        pid = $block.find('.js-product-select').data('pid');
    }
	
	
	// Время по часам.
    var hours = Date.getHoursBetween(new Date('2000-01-01 ' + $timemin.val()), new Date('2000-01-01 ' + $timemax.val()));
    
    if (hours < 0) {
        hours = Date.getHoursBetween(new Date('2000-01-01 ' + $timemin.val()), new Date('2000-01-02 ' + $timemax.val()));
    }
    if (hours != 0) {
        hours = Math.abs(hours);
    }
	
	
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
	
	// Закрытие календаря.
	CalendarClose($popup);
	
	console.log(days, hours);
	
	// Протсавление дополнительных параметров.
	$block.find('.js-product-days-hours-dates').val($note.text());
	$block.find('.js-product-days-hours-times').val($timemin.val() + ' - ' + $timemax.val());
	
	// Общее количество.
	quantity = quantity * hours;
	
    if (quantity < 0) {
        quantity = 0;
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
	$(document).on('click', '.js-block-days-hours .js-calendar-save', function(e) {
		var $block = $(this).closest('.js-product-block');
		
		window['oem-func-days-cart']($block);
	});
    
    // Выбор времени.
    $(document).on('change', '.js-days-hours-times', function(event) {
        var $block = $(this).closest('.js-product-block');
        
        window['oem-func-days-hours-cart']($block);
    });
    
});