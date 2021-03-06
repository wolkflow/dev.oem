/**
 * Получение даты в формате dd.mm.yyyy.
 */
Date.getDateFormat = function (date) {
	return ('0' + date.getDate()).slice(-2)  + "." + ('0' + (date.getMonth() + 1)).slice(-2) + "." + date.getFullYear();
}

/**
 * Количество часов между датами.
 */
Date.getHoursBetween = function (time1, time2) {
    var hour = 60 * 60 * 1000;

	var date1 = new Date('2000-01-01 ' + time1);
	var date2 = new Date('2000-01-01 ' + time2);
	
	if (parseInt(date2.getTime() - date1.getTime()) < 0) {
		date1 = new Date('2000-01-01 ' + time1);
		date2 = new Date('2000-01-02 ' + time2);
	}
	
    var difference = parseInt(date2.getTime() - date1.getTime());
	
	difference = Math.abs(difference);
	difference = Math.round(difference / hour);
	
    return difference;
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


function ResetParams($block)
{
    // Общий сброс значений свойств.
    $block.find('input.js-param-value').val('');
    $block.find('textarea.js-param-value').val('').html('');

    // Для своства "Цвет".
    var uniqid = (new Date()).getTime();
	
	$block.find('.js-param-x-remove').trigger('click'); // Удаление файла.
	
    $block.find('.js-button-param').css('background', '');
	$block.find('.js-color-indicator').css('background', '');
	$block.find('.js-color-title').html('');
	
    $block.find('.js-button-param').attr('data-modal', '#js-color-popup-' + uniqid + '-id');
    $block.find('.js-color-indicator').attr('data-indicator', '#js-color-popup-' + uniqid + '-id');
    $block.find('.js-color-title').attr('data-title', '#js-color-popup-' + uniqid + '-id');
	
    $block.find('.modal .js-colors-palette li').removeClass('active');
    $block.find('.modal').attr('id', 'js-color-popup-' + uniqid + '-id');
}

function CalendarClose($block)
{
    $block.animate({'top': '250%', 'opacity': 0}, 200, function() {
        $block.fadeOut(1, function() {
            $block.removeClass('open');
        });
    });
}

$(document).ready(function() {
	
	$(document).on('keyup', '.js-property-block .js-param-link', function(e) {
		// Обновление данных о свойстве продукции.
		UpdateBasketProps($(this).closest('.js-param-block'));
	});
	
	$(document).on('keyup', '.js-property-block .js-param-text', function(e) {
		// Обновление данных о свойстве продукции.
		UpdateBasketProps($(this).closest('.js-param-block'));
	});
	
	$(document).on('keyup', '.js-property-block .js-param-comment', function(e) {
		// Обновление данных о свойстве продукции.
		UpdateBasketProps($(this).closest('.js-param-block'));
	});
	
	$(document).on('keyup', '.js-property-block .js-param-form_hanging_structure', function(e) {
		// Обновление данных о свойстве продукции.
		UpdateBasketProps($(this).closest('.js-param-block'));
	});
	

    // СВОЙСТВО: Файл.
    $(document).on('change', '.js-param-x-upload', function(event) {
        if ($(this).get(0).files == undefined) {
            return;
        }
        var $that  = $(this);
        var $block = $that.closest('.js-param-block');
        var $image = $block.find('.js-param-x-image');
        var $input = $block.find('.js-param-x-file');
        var data   = new FormData();

        data.append('upload', $that.get(0).files[0]);
        data.append('action', 'file-upload');
        data.append('sessid', BX.bitrix_sessid());

        $.ajax({
            url: '/remote/',
            type: 'post',
            data: data,
            dataType: 'json',
            async: true,
            cache: false,
            context: this,
            contentType: false, // важно - убираем форматирование данных по умолчанию
            processData: false, // важно - убираем преобразование строк по умолчанию
            beforeSend: function() {
				$image.removeClass('changed');
                $image.html('').hide();
            },
            success: function(response) {
                if (response.status) {
                    if (response.data['isimg']) {
                        $image.append('<img width="56" height="56" src="' + response.data['path'] + '" />');
						$image.append('<span class="file-remove js-param-x-remove">&times;</span>');
                        $image.show();
                    } else {
                        $image.append('<a href="' + response.data['path'] + '" target="_blank"><img width="56" height="56" src="/local/templates/.default/build/images/download.png" /></a>');
                        $image.append('<span class="file-remove js-param-x-remove">&times;</span>');
						$image.show();
                    }
					$image.addClass('changed')
                    $input.val(response.data['file']);
					
					
					// Обновление данных о свойстве продукции.
					UpdateBasketProps($block);
                } else {
                    // Ошибка загрузки файла.
                }
            }
        });
    });
	
	
	// СВОЙСТВО: Файл - удаление файла.
	$(document).on('click', '.js-param-x-remove', function(event) {
        var $that  = $(this);
        var $block = $that.closest('.js-param-block');
		var $image = $block.find('.js-param-x-image');
		
		$block.find('.js-param-x-file').val('');
		$block.find('.js-param-x-upload').val('');
		$block.find('.js-param-x-upload .jq-file__name').html('');
		$block.find('.js-param-x-image').html('').hide();
		
		$image.removeClass('changed');
		
		// Обновление данных о свойстве продукции.
		UpdateBasketProps($(this).closest('.js-param-block'));
	});
	
	
	// СВОЙСТВО: Форма подвесной конструкции.
	$(document).on('click', '.js-form-opener', function() {
		var $that  = $(this);
		var $block = $that.closest('.js-param-block');
		var $wrap  = $block.find('.js-form-wrapper');
		
		$.when($wrap.slideToggle()).then(function() {
			$(document.body).trigger('sticky_kit:recalc');
		});
	});
	
	
    // СВОЙСТВО: Выбор цвета.
    $(document).on('click', '.js-colors-palette .js-color-item', function() {
        var $that      = $(this);
        var $parent    = $that.parent('li');
        var $modal     = $that.closest('.modal');
        var $button    = $('[data-modal="#' + $modal.attr('id') + '"]');
		var $title     = $('[data-title="#' + $modal.attr('id') + '"]');
		var $indicator = $('[data-indicator="#' + $modal.attr('id') + '"]');
		var $wrapper   = $button.closest('.js-param-block');
		
        // Данные свойства для корзины.
        var $input_value = $wrapper.find('.js-param-x-value');
        var $input_color = $wrapper.find('.js-param-x-color');
		
        if ($parent.hasClass('active')) {
            $parent.removeClass('active');
            $input_value.val('');
            $input_color.val('');
        } else {
            $parent.closest('.js-colors-palette').find('li').removeClass('active');
            $parent.addClass('active');
            $input_value.val($that.data('id'));
            $input_color.val($that.css('background'));
        }
        $indicator.css('background', $that.css('background'));
        $title.html($that.data('title'));
		console.log($that.data('title'), '[data-title="#' + $modal.attr('id') + '"]');
        setTimeout(function() {
            $('#' + $modal.attr('id')).arcticmodal('close');
        }, 200);
		
		// Обновление данных о свойстве продукции.
		UpdateBasketProps($wrapper);
    });


    // УТИЛИТА: Показать календарь
    $(document).on('click', '.setDate', function () {
        var block = $(this).parent().find('.calendarPopupBlock');
        if(block.hasClass('open')) {
            CalendarClose(block)
        } else {
            $(block).fadeIn(1, function () {
                $(block).animate({'top': '100%', 'opacity': 1}, 200, function () {
                    $(block).addClass('open')
                });
            });
        }
    });

	
    // СВОЙСТВО: Календраь - первый запуск.
    $('.js-calendar-popup').each(function() {
        var $that = $(this);
		var $wrap = $that.closest('.js-product-block');
		var $mode = $that.find('.js-calendar-mode');
        var calendar = $that.find('.calendar'),
            minDate  = new Date(calendar.attr('data-date-min')),
            maxDate  = new Date(calendar.attr('data-date-max'));
		
		var dates_field = $wrap.find('.js-calendar-dates').data('field');
		var dates_array = $wrap.find('.js-calendar-dates').data('dates');
		
		var rangeMin = calendar.parent().find('.min-date');
		var rangeMax = calendar.parent().find('.max-date');
				
		if ($mode.attr('data-checked') === '1') {
			$mode.prop('checked', true);
			
            $(calendar).datepicker({
                minDate: minDate,
                maxDate: maxDate,
                beforeShowDay: function(date) {
                    var date1 = $.datepicker.parseDate('mm/dd/yy', dates_array[0]);
                    var date2 = $.datepicker.parseDate('mm/dd/yy', dates_array[1]);
                    var isHightlight = (date1 && ((date.getTime() == date1.getTime()) || (date2 && date >= date1 && date <= date2)));
					
                    return [true, isHightlight ? "dp-highlight" : ""];
                },
                onSelect: function(dateText, inst) {
                    var date1 = $.datepicker.parseDate('mm/dd/yy', rangeMin.val());
                    var date2 = $.datepicker.parseDate('mm/dd/yy', rangeMax.val());
                    var selectedDate = $.datepicker.parseDate('mm/dd/yy', dateText);
                    if (!date1 || date2) {
                        rangeMin.val(dateText);
                        rangeMax.val("");
                    } else if (selectedDate < date1) {
                        rangeMax.val(rangeMin.val());
                        startDate.val(dateText);
                    } else {
                        rangeMax.val(dateText);
                    }
                    $(this).datepicker();
                }
            });
		} else {
            calendar.multiDatesPicker({
                minDate: minDate,
                maxDate: maxDate
            });
            calendar.multiDatesPicker('value', dates_field);
            calendar.multiDatesPicker();
        }
    });
	

    // Смена типа выбора мульти, или ренж.
    $(document).on('change', '.js-calendar-mode', function() {
        var changeMode = $(this),
            calendar   = changeMode.parents('.js-calendar-popup').find('.calendar'),
            minDate    = new Date(calendar.attr('data-date-min')),
            maxDate    = new Date(calendar.attr('data-date-max')),
            rangeMin   = calendar.parent().find('.min-date'),
            rangeMax   = calendar.parent().find('.max-date'),
            dates      = calendar.parents('.setDateBlock').find('.dates');
		
        if (changeMode.is(':checked')) {
            calendar.datepicker('destroy');
            // http://jsfiddle.net/sWbfk/
            $(calendar).datepicker({
                minDate: minDate,
                maxDate: maxDate,
                beforeShowDay: function(date) {
                    var date1 = $.datepicker.parseDate('mm/dd/yy', rangeMin.val());
                    var date2 = $.datepicker.parseDate('mm/dd/yy', rangeMax.val());
                    var isHightlight = date1 && ((date.getTime() == date1.getTime()) || (date2 && date >= date1 && date <= date2));
                    return [true, isHightlight ? "dp-highlight" : ""];
                },
                onSelect: function(dateText, inst) {
                    var date1 = $.datepicker.parseDate('mm/dd/yy', rangeMin.val());
                    var date2 = $.datepicker.parseDate('mm/dd/yy', rangeMax.val());
                    var selectedDate = $.datepicker.parseDate('mm/dd/yy', dateText);
                    if (!date1 || date2) {
                        rangeMin.val(dateText);
                        rangeMax.val("");
                    } else if (selectedDate < date1) {
                        rangeMax.val(rangeMin.val());
                        rangeMin.val(dateText);
                    } else {
                        rangeMax.val(dateText);
                    }
                    $(this).datepicker();
                }
            });
        } else {
            calendar.datepicker('destroy');
            calendar.multiDatesPicker({
				minDate: minDate,
                maxDate: maxDate
            });
        }
    });
	
	
    // Сбрасываем и прячем календарь.
    $(document).on('click', '.js-calendar-reset', function() {
		var $calendar = $(this).parents('.js-calendar-content').find('.calendar');
		var $wrapper  = $(this).parents('.js-calendar-wrap');
		var $popup    = $wrapper.find('.js-calendar-popup');
        var $dates    = $wrapper.find('.js-calendar-dates');
		
		$dates.html('');
		$dates.attr('data-field', '');
		$dates.attr('data-dates', '');
		
        CalendarClose($popup);
    });
	
	
    // Закрытие календаря.
    $(document).keydown(function(e) {
        if (e.which == 27 && $('.js-calendar-popup').hasClass('open')) {
            CalendarClose($('.js-calendar-popup'));
        }
    });
});