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

});