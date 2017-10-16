

/**
 * Функция добавления в корзину.
 */
window['oem-func-width-height-cart'] = function($block) {
	var pid      = 0;
	var $width   = $block.find('input.js-width');
	var $height  = $block.find('input.js-height');
   
    var quantity = parseInt($height.val()) * parseInt($quanty.val());
    if (quantity < 0) {
        quantity = 0;
    }

    // Нулевое количество.
    if (quantity == 0) {
        ResetParams($block);
    }
	    
    if ($block.find('.js-product-select').length) {
        pid = $block.find('.js-product-select option:selected').val();
    } else {
        pid = $block.find('.js-product-element').data('pid');
    }
    //$input.val(quantity);
    
    // Сохранение в корзине.
    PutBasket(pid, quantity, $block);
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
window['oem-func-width-height-clear'] = function($block) {
    $block.attr('data-bid', '');
    $block.find('input.js-width').val(0).data('value', 0);
    $block.find('input.js-height').val(0).data('value', 0);
	$block.find('.styler').styler();
    
    $block.find('.js-product-price').html('');
    $block.find('.js-product-descr').html('');
    $block.find('.js-product-select-price').hide();
    $block.find('.js-product-select-descr').hide();

    // Сброс всех свойств товара.
    ResetParams($block);
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
