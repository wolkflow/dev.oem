


/**
 * Функция добавления в корзину.
 */
window['oem-func-quantity-cart'] = function($block, quantity) {
    
    var pid      = 0;
    var $input   = $block.find('input');
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
window['oem-func-quantity-more'] = function($that) {
    var $wrapper = $that.closest('.js-block-quantity');
    var $section = $wrapper.find('.js-product-section');
    var $block   = $wrapper.find('.js-product-block').first().clone();

    $block.attr('data-bid', '');
    $block.find('.js-quantity').val(0).data('value', 0);
    $block.find('.jq-selectbox__select,.jq-selectbox__dropdown').remove();
    $block.find('.styler').styler();
    
    $block.find('.js-product-price').html('');
    $block.find('.js-product-descr').html('');
    $block.find('.js-product-select-price').hide();
    $block.find('.js-product-select-descr').hide();

    // Сброс всех свойств товара.
    ResetParams($block);

    $section.append($block);

    $block.find('.js-product-select .js-option-noselect').trigger('click');
}


$(document).ready(function() {
    
    // Добавление нового поля.
    $(document).on('click', '.js-block-quantity .js-more-field', function(event) {
        window['oem-func-quantity-more']($(this));
    });


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