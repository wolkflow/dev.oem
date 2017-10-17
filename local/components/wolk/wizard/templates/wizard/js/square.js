

/**
 * Функция добавления в корзину.
 */
window['oem-func-square-cart'] = function($block) {

    var $input   = $block.find('input.js-square');
    var quantity = parseInt($input.val());
    
    if (quantity < 0) {
        quantity = 0;
    }

    // Сброс всех свойств товара.
    if (quantity == 0) {
        ResetParams($block);
    }
	
    // Сохранение в корзине.
    PutBasket(quantity, $block);
}


/**
 * Функция добавления товарной позиции.
 */
window['oem-func-square-more'] = function($block) {
    var $wrapper = $block.closest('.js-product-wrapper');
    var $section = $wrapper.find('.js-product-section');
    var $clone   = $wrapper.find('.js-product-block').first().clone();
    
	$clone.find('.jq-selectbox__select, .jq-selectbox__dropdown').remove();
	
    // Сброс параметров.
    window['oem-func-square-clear']($clone);
    	
    // Добавление блока.
    $section.append($clone);

    $clone.find('.js-product-select .js-option-noselect').trigger('click');
}


/**
 * Функция очистки данных.
 */
window['oem-func-square-clear'] = function($block) {
    $block.attr('data-bid', '');
    $block.find('.js-square').val(0).data('value', 0);
    $block.find('.styler').styler();
    
    $block.find('.js-product-price').html('');
    $block.find('.js-product-descr').html('');
    $block.find('.js-product-select-price').hide();
    $block.find('.js-product-select-descr').hide();

    // Сброс всех свойств товара.
    ResetParams($block);
}


$(document).ready(function() {
    
    // Выбор количества товара.
    $(document).on('keyup', '.js-block-square .js-square', function(event) {
		var $block = $(this).closest('.js-product-block');
		
        window['oem-func-square-cart']($block);
	});

});