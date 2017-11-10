

/**
 * Функция добавления в корзину.
 */
window['oem-func-width-height-cart'] = function($block) {
	var pid    = 0;
	var width  = parseInt($block.find('input.js-width').val())  || 0;
	var height = parseInt($block.find('input.js-height').val()) || 0;
    
    var quantity = width * height;
    if (quantity < 0) {
        quantity = 0;
    }
	
    // Нулевое количество.
    if (quantity == 0) {
        ResetParams($block);
    }
	
    // Сохранение в корзине.
    PutBasket(quantity, $block);
}


/**
 * Функция добавления товарной позиции.
 */
window['oem-func-width-height-more'] = function($block) {
    var $wrapper = $block.closest('.js-product-wrapper');
    var $section = $wrapper.find('.js-product-section');
    var $clone   = $wrapper.find('.js-product-block').first().clone();
    
	$clone.find('.jq-selectbox__select, .jq-selectbox__dropdown').remove();
	
    // Сброс параметров.
    window['oem-func-width-height-clear']($clone);
    	
    // Добавление блока.
    $section.append($clone);

    $clone.find('.js-product-select .js-option-noselect').trigger('click');
}


/**
 * Функция очистки данных.
 */
window['oem-func-width-height-clear'] = function($block, full) {
    
	full = (typeof full !== 'undefined') ?  (full) : (true);
    
	$block.find('.styler').styler();
	
	if (full) {
		$block.find('.js-product-select .js-option-noselect').trigger('click');
	}
    
	$block.find('input.js-width').val(0).data('value', 0);
    $block.find('input.js-height').val(0).data('value', 0);
	
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
	
	// Ширина.
    $(document).on('keyup', '.js-block-width-height .js-width', function(event) {
		var $block = $(this).closest('.js-product-block');
		
		window['oem-func-width-height-cart']($block);
	});
	
	
	// Высота.
    $(document).on('keyup', '.js-block-width-height .js-height', function(event) {
		var $block = $(this).closest('.js-product-block');
		
		window['oem-func-width-height-cart']($block);
	});
});
