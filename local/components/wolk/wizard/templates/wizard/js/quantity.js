

/**
 * Функция добавления в корзину.
 */
window['oem-func-quantity-cart'] = function($block) {
    
    var pid      = 0;
    var $input   = $block.find('input.js-quantity');
    var rawvalue = parseInt($input.val());
    var quantity = parseInt($input.data('value'));
    
    if (quantity < 0) {
        quantity = 0;
    }

    // Сброс всех свойств товара.
    if (quantity == 0) {
        if (rawvalue == 0) {
            return;
        }
        ResetParams($block);
    }

    $input.val(quantity);
    $input.change();
    
    if ($block.find('.js-product-select').length) {
        pid = $block.find('.js-product-select option:selected').val();
    } else {
        pid = $block.find('.js-product-element').data('pid');
    }
    $input.val(quantity);
    
    // Сохранение в корзине.
    PutBasket(pid, quantity, $block);
}


/**
 * Функция добавления товарной позиции.
 */
window['oem-func-quantity-more'] = function($block) {
    var $wrapper = $block.closest('.js-product-wrapper');
    var $section = $wrapper.find('.js-product-section');
    var $clone   = $wrapper.find('.js-product-block').first().clone();
    
    // Сброс параметров.
    window['oem-func-quantity-clear']($clone);
    
	$clone.find('.jq-selectbox__select:first').remove();
	
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
    $(document).on('click', '.js-pricetype-quantity .js-quantity-dec', function(event) {
		var $block   = $(this).closest('.js-product-block');
		var $input   = $block.find('input');
        
        $input.data('value', parseInt($input.val()) - 1);
        
        window['oem-func-quantity-cart']($block);
        
		return false;
	});


    // Увеличение количества товара.
    $(document).on('click', '.js-pricetype-quantity .js-quantity-inc', function(event) {
        var $block   = $(this).closest('.js-product-block');
		var $input   = $block.find('input');
        
        $input.data('value', parseInt($input.val()) + 1);
        
        window['oem-func-quantity-cart']($block);

		return false;
	});

});