function ResetParams($block)
{
    // Общий сброс значений свойств.
    $block.find('.js-param-value').val('');

    // Для своства "Цвет".
    var uniqid = (new Date()).getTime();

    $block.find('.js-button-param').css('background', '');
    $block.find('.js-button-param').attr('data-modal', '#js-color-popup-' + uniqid + '-id');
    $block.find('.modal').attr('id', 'js-color-popup-' + uniqid + '-id');
}


$(document).ready(function() {

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
            $('.arcticmodal-close').trigger('click');
        }, 200);
    });

});