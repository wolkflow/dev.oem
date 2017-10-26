

/**
 * Функция добавления в корзину.
 */
window['oem-func-quantity-cart'] = function($block) {
    
    var $input   = $block.find('input.js-quantity');
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
window['oem-func-quantity-more'] = function($block) {
    var $wrapper = $block.closest('.js-product-wrapper');
    var $section = $wrapper.find('.js-product-section');
    var $clone   = $wrapper.find('.js-product-block').first().clone();
    
	$clone.find('.jq-selectbox__select, .jq-selectbox__dropdown').remove();
	
    // Сброс параметров.
    window['oem-func-quantity-clear']($clone);
    	
    // Добавление блока.
    $section.append($clone);

    $clone.find('.js-product-select .js-option-noselect').trigger('click');
}


/**
 * Функция очистки данных.
 */
window['oem-func-quantity-clear'] = function($block) {
    $block.attr('data-bid', '');
    $block.find('.js-quantity').val(0).data('value', 0);
    $block.find('.styler').styler();
    
    $block.find('.js-product-price').html('');
    $block.find('.js-product-descr').html('');
    $block.find('.js-product-select-price').hide();
    $block.find('.js-product-select-descr').hide();

    // Сброс всех свойств товара.
    ResetParams($block);
}


$(document).ready(function() {
    
    // Уменьшение количества товара.
    $(document).on('click', '.js-block-quantity .js-quantity-dec', function(event) {
		var $block = $(this).closest('.js-product-block');
		var $input = $block.find('input.js-quantity');
		var value  = parseInt($input.val());
        
		if (value > 0) {
			 $input.val(value - 1);
		}
        
        window['oem-func-quantity-cart']($block);
	});


    // Увеличение количества товара.
    $(document).on('click', '.js-block-quantity .js-quantity-inc', function(event) {
        var $block = $(this).closest('.js-product-block');
		var $input = $block.find('input.js-quantity');
		var value  = parseInt($input.val());
        
        $input.val(value + 1);
        
        window['oem-func-quantity-cart']($block);
	});

	
	// Ввод количества.
	$(document).on('click', '.js-block-quantity .js-quantity', function(event) {
		var $block = $(this).closest('.js-product-block');
		
        window['oem-func-quantity-cart']($block);
	});
});