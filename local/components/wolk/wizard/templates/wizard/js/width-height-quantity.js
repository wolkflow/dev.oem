

/**
 * Функция добавления в корзину.
 */
window['oem-func-width-height-quantity-cart'] = function($block) {
	var pid      = 0;
	var $width   = $block.find('input.js-width');
	var $height  = $block.find('input.js-height');
    var $quanty  = $block.find('input.js-quantity');
   
    var quantity = parseInt($width.val()) * parseInt($height.val()) * parseInt($quanty.val());
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
window['oem-func-width-height-quantity-more'] = function($block) {
    var $wrapper = $block.closest('.js-product-wrapper');
    var $section = $wrapper.find('.js-product-section');
    var $clone   = $wrapper.find('.js-product-block').first().clone();
    
	$clone.find('.jq-selectbox__select, .jq-selectbox__dropdown').remove();
	
    // Сброс параметров.
    window['oem-func-width-height-quantity-clear']($clone);
    	
    // Добавление блока.
    $section.append($clone);

    $clone.find('.js-product-select .js-option-noselect').trigger('click');
}


/**
 * Функция очистки данных.
 */
window['oem-func-width-height-quantity-clear'] = function($block, full) {
    
	full = (typeof full !== 'undefined') ?  (full) : (true);
	
	if (full) {
		$block.find('.js-product-select .js-option-noselect').trigger('click');
	}
	
	$block.find('.styler').styler();
	
    $block.find('input.js-width').val(0).data('value', 0);
    $block.find('input.js-height').val(0).data('value', 0);
	$block.find('input.js-quantity').val(0).data('value', 0);
	
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
    $(document).on('keyup', '.js-block-width-height-quantity .js-width', function(event) {
		var $block = $(this).closest('.js-product-block');
		
		window['oem-func-width-height-quantity-cart']($block);
	});
	
	
	// Высота.
    $(document).on('keyup', '.js-block-width-height-quantity .js-height', function(event) {
		var $block = $(this).closest('.js-product-block');
		
		window['oem-func-width-height-quantity-cart']($block);
	});
    
	
    // Уменьшение количества товара.
    $(document).on('click', '.js-block-width-height-quantity .js-quantity-dec', function(event) {
		var $block = $(this).closest('.js-product-block');
		var $input = $block.find('input.js-quantity');
		var value  = parseInt($input.val());
        
		if (value > 0) {
			 $input.val(value - 1);
		}
        
        window['oem-func-width-height-quantity-cart']($block);
	});


    // Увеличение количества товара.
    $(document).on('click', '.js-block-width-height-quantity .js-quantity-inc', function(event) {
        var $block = $(this).closest('.js-product-block');
		var $input = $block.find('input.js-quantity');
		var value  = parseInt($input.val());
        
		$input.val(value + 1);
        
        window['oem-func-width-height-quantity-cart']($block);
	});
	
	
	// Ввод количества.
	$(document).on('click', '.js-block-width-height-quantity .js-quantity', function(event) {
		var $block = $(this).closest('.js-product-block');
		
        window['oem-func-width-height-quantity-cart']($block);
	});
});
