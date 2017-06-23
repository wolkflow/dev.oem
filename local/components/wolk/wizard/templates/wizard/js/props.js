$(document).ready(function() {

    // СВОЙСТВО: Выбор цвета.
    $('.js-colors-palette .js-color-item').on('click', function() {
        var $that    = $(this);
        var $parent  = $that.parent();
        var $wrapper = $that.closest('.js-property-wrapper');
        var $button  = $wrapper.find('.js-button-property');

        if ($parent.hasClass('active')) {
            $parent.removeClass('active');
        } else {
            $parent.closest('.js-colors-palette').find('li').removeClass('active');
            $parent.addClass('active');
        }
        $button.css('background', $that.css('background'));

        setTimeout(function() {
            $('.arcticmodal-close').trigger('click');
        }, 200);
    });

});