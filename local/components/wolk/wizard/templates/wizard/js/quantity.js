
$(document).ready(function() {


    // Добавление нового поля.
    $(document).on('click', '.js-block-quantity .js-more-field', function(event) {
        var $wrapper = $(this).closest('.js-block-quantity');
        var $section = $wrapper.find('.js-product-section');
        var $block   = $wrapper.find('.js-product-block').first().clone();

        $block.attr('data-bid', '');
        $block.find('.js-quantity').val(0);
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
    });


    // Уменьшение количества товара.
    $(document).on('click', '.js-pricetype-quantity .js-quantity-dec', function(event) {
		var $parent  = $(this).parent();
		var $block   = $parent.closest('.js-product-block');
		var $input   = $parent.find('input');
        var quantity = parseInt($input.val()) - 1;
        
        if (quantity < 0) {
            quantity = 0;
        }

        // Сброс всех свойств товара.
        if (quantity == 0) {
            ResetParams($block);
        }

		$input.val(quantity);
		$input.change();
        
        var pid;
		if ($block.find('.js-product-select').length) {
		    pid = $block.find('.js-product-select option:selected').val();
        } else {
		    pid = $block.find('.js-product-element').data('pid');
        }
        
        PutBasket(pid, quantity, $(this).closest('.js-product-block'));
        
		return false;
	});


    // Увеличение количества товара.
    $(document).on('click', '.js-pricetype-quantity .js-quantity-inc', function(event) {
        var $parent  = $(this).parent();
        var $block   = $parent.closest('.js-product-block');
		var $input   = $parent.find('input');
        var quantity = parseInt($input.val()) + 1;
        
		$input.val(quantity);
		$input.change();

		var pid;
		if ($block.find('.js-product-select').length) {
		    pid = $block.find('.js-product-select option:selected').val();
        } else {
		    pid = $block.find('.js-product-element').data('pid');
        }

        // Добавление позиции в корзину.
		PutBasket(pid, quantity, $(this).closest('.js-product-block'));

		return false;
	});

});