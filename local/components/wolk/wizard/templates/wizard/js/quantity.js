$(document).ready(function() {

    // Добавление нового поля.
    $(document).on('click', '.js-block-quantity .js-more-field', function() {
        var $wrapper = $(this).closest('.js-block-quantity');
        var $section = $wrapper.find('.js-product-section');
        var $block   = $wrapper.find('.js-product-block').first().clone();

        $block.attr('data-bid', '');
        $block.find('.js-quantity').val(0);
        $block.find('.js-property-wrapper input[type="hidden"]').val('');
        $block.find('.jq-selectbox__select,.jq-selectbox__dropdown').remove();
        $block.find('.styler').styler();

        $section.append($block);
    });
    
    // Уменьшение количества товара.
    $(document).on('click', '.js-pricetype-quantity .js-quantity-dec', function() {
		var $wrapper = $(this).parent();
		var $input   = $wrapper.find('input');
        var quantity = parseInt($input.val()) - 1;
        
        if (quantity < 0) {
            quantity = 0;
        }
		$input.val(quantity);
		$input.change();
        
        PutBasket($wrapper.data('pid'), quantity, [], $(this).closest('.js-product-block'));
        
		return false;
	});
    
    // Увеличение количества товара.
    $(document).on('click', '.js-pricetype-quantity .js-quantity-inc', function() {
        var $wrapper = $(this).parent();
		var $input   = $wrapper.find('input');
        var quantity = parseInt($input.val()) + 1;
        
		$input.val(quantity);
		$input.change();
        
        PutBasket($wrapper.data('pid'), quantity, [], $(this).closest('.js-product-block'));
        
		return false;
	});


    // Выбор товара из выпадающего списка.
    $(document).on('change', '.js-product', function() {
        var $that   = $(this);
        var $block  = $that.closest('.js-product-block');
        var $option = $that.find('option:selected');

        if (!empty($option.val())) {
            $block.find('.js-product-price').html($option.data('price'));
            $block.find('.js-product-select-price').show();
        } else {
            $block.find('.js-product-select-price').hide();
        }
    });
});