function ResetParams($block)
{
    // Общий сброс значений свойств.
    $block.find('input.js-param-value').val('');
    $block.find('textarea.js-param-value').html('');

    // Для своства "Цвет".
    var uniqid = (new Date()).getTime();

    $block.find('.js-button-param').css('background', '');
    $block.find('.js-button-param').attr('data-modal', '#js-color-popup-' + uniqid + '-id');
    $block.find('.modal .js-colors-palette li').removeClass('active');
    $block.find('.modal').attr('id', 'js-color-popup-' + uniqid + '-id');
}

function calendarClose(block) {
    block.animate({'top': '250%', 'opacity': 0}, 200, function () {
        block.fadeOut(1, function () {
            block.removeClass('open')
        });
    });
    console.log('close');
}

$(document).ready(function() {

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
                    $input.val(response.data['file']);
                } else {
                    // Ошибка загрузки файла.
                }
            }
        });
    });
	
	$(document).on('click', '.js-param-x-remove', function(event) {
        var $that  = $(this);
        var $block = $that.closest('.js-param-block');
		
		$block.find('.js-param-x-file').val('');
		$block.find('.js-param-x-upload').val('');
		$block.find('.js-param-x-upload .jq-file__name').html('');
		$block.find('.js-param-x-image').html('').hide();
	});
	


    // СВОЙСТВО: Выбор цвета.
    $(document).on('click', '.js-colors-palette .js-color-item', function() {
        var $that    = $(this);
        var $parent  = $that.parent('li');
        var $modal  = $that.closest('.modal');
        var $button  = $('[data-modal="#' + $modal.attr('id') + '"]')
        var $wrapper = $button.closest('.js-param-block');

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
        $button.css('background', $that.css('background'));

        setTimeout(function() {
            $('#' + $modal.attr('id')).arcticmodal('close');
        }, 200);
    });



    // УТИЛИТА: Показать календарь
    $(document).on('click', '.setDate', function () {
        var block = $(this).parent().find('.calendarPopupBlock');
        if(block.hasClass('open')) {
            calendarClose(block)
        } else {
            $(block).fadeIn(1, function () {
                $(block).animate({'top': '100%', 'opacity': 1}, 200, function () {
                    $(block).addClass('open')
                });
            });
        }
    });

    // Календарь
    // Первый запуск
    $('.calendarPopupBlock').each(function () {
        var $that = $(this);
        var calendar = $that.find('.calendar'),
            minDate = calendar.attr('data-min-date'),
            maxDate = calendar.data('data-max-date');

        calendar.multiDatesPicker({
            minDate: minDate,
            maxDate: maxDate
        });
    });

    // Смена типа выбора мульти, или ренж
    $(document).on('change', '.changeMode', function () {
        var changeMode = $(this),
            calendar = changeMode.parents('.calendarPopupBlock').find('.calendar'),
            minDate = calendar.attr('data-min-date'),
            maxDate = calendar.attr('data-max-date'),
            startDate = calendar.parent().find('.start-date'),
            endDate = calendar.parent().find('.end-date');

        if(changeMode.is(':checked')) {
            calendar.datepicker('destroy');
            // http://jsfiddle.net/sWbfk/
            $(calendar).datepicker({
                minDate: minDate,
                maxDate: maxDate,
                beforeShowDay: function(date) {
                    var date1 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, startDate.val());
                    var date2 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, endDate.val());
                    var isHightlight = date1 && ((date.getTime() == date1.getTime()) || (date2 && date >= date1 && date <= date2));
                    return [true, isHightlight ? "dp-highlight" : ""];
                },
                onSelect: function(dateText, inst) {
                    var date1 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, startDate.val());
                    var date2 = $.datepicker.parseDate($.datepicker._defaults.dateFormat, endDate.val());
                    var selectedDate = $.datepicker.parseDate($.datepicker._defaults.dateFormat, dateText);
                    if (!date1 || date2) {
                        startDate.val(dateText);
                        endDate.val("");
                    } else if (selectedDate < date1) {
                        endDate.val(startDate.val());
                        startDate.val(dateText);
                    } else {
                        endDate.val(dateText);
                    }
                    $(this).datepicker();
                }
            });
        }
        else {
            calendar.datepicker('destroy');
            calendar.multiDatesPicker({
                minDate: minDate,
                maxDate: maxDate
            });
        }
    });

    // Сбрасываем и прячем календарь
    $(document).on('click', '.calendarReset', function () {
        var calendar = $(this).parents('.calendarPopupContent').find('.calendar'),
            minDate = calendar.attr('data-min-date'),
            maxDate = calendar.attr('data-max-date'),
            block = $(this).parents('.calendarPopupWrapper').find('.calendarPopupBlock'),
            changeMode = $(this).parents('.calendarPopupContent').find('.changeMode');

        calendar.multiDatesPicker('resetDates');
        $.datepicker._clearDate(calendar);
        calendar.datepicker('destroy');
        calendar.multiDatesPicker({
            minDate: minDate,
            maxDate: maxDate
        });
        calendarClose(block)
        changeMode.prop('checked', false)
    });

    // Сохраняем результат и закрываем календарь
    $(document).on('click', '.calendarSave', function () {
        var block = $(this).parents('.calendarPopupWrapper').find('.calendarPopupBlock');

        calendarClose(block)
    });

    // Закрытие календаря
    $(document).keydown(function(eventObject){
        if (eventObject.which == 27 && $(".calendarPopupBlock").hasClass("open")) {
            calendarClose($(".calendarPopupBlock"));
        }
    });
    $(document).on("mouseup click tap", function (e) {
        var container = $(".calendarPopupBlock.open");
        if (container.length && container.has(e.target).length === 0){
            calendarClose($('.calendarPopupBlock.open'))
        }
    });
});