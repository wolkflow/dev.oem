

/**
 * Функция добавления в корзину.
 */
window['oem-func-symbols-cart'] = function($block) {
    
    var $input = $block.find('input.js-text');
	var string = $input.val();
    var limit  = parseInt($block.find('.js-symbols-wrapper').data('limit'));
	
	quantity = string.replace(/\s/gi, '').length; // - limit;
	
    if (quantity < 0) {
        quantity = 0;
    }
	
    // Сохранение в корзине.
    PutBasket(quantity, $block);
}


/**
 * Функция добавления товарной позиции.
 */
window['oem-func-symbols-more'] = function($block) {
    var $wrapper = $block.closest('.js-product-wrapper');
    var $section = $wrapper.find('.js-product-section');
    var $clone   = $wrapper.find('.js-product-block').first().clone();
    
	$clone.find('.jq-selectbox__select, .jq-selectbox__dropdown').remove();
	$clone.find('.jq-file__name, .jq-file__browse').remove();
	$clone.find('.js-param-x-image').removeClass('changed').hide();
	
	$clone.attr('data-bid', '');
	
    // Сброс параметров.
    window['oem-func-symbols-clear']($clone);
    	
    // Добавление блока.
    $section.append($clone);

    $clone.find('.js-product-select .js-option-noselect').trigger('click');
}


/**
 * Функция очистки данных.
 */
window['oem-func-symbols-clear'] = function($block, full) {
	
	full = (typeof full !== 'undefined') ?  (full) : (true);
	
    $block.find('.styler').styler();
	
	if (full) {
		$block.find('.js-product-select .js-option-noselect').trigger('click');
	}
    
	$block.find('.js-text').val('');
	
    $block.find('.js-product-price').html('');
    $block.find('.js-product-descr').html('');
    $block.find('.js-product-select-price').hide();
    $block.find('.js-product-select-descr').hide();
	
    // Сброс всех свойств товара.
	if (full) {
		ResetParams($block);
	}
}


$(document).ready(function() {
    
    // Уменьшение количества товара.
    $(document).on('keyup', '.js-pricetype-symbols input.js-text', function(event) {
		var $block = $(this).closest('.js-product-block');
		
        window['oem-func-symbols-cart']($block);
	});

});


