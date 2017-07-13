function showError(title, message)
{
    var $popup = $('#js-modal-error-id');

    $popup.find('.modalTitle').html(title);
    $popup.find('.modalContent').html(message);
    $popup.arcticmodal();
}

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
        $block.find('.js-product-select .js-option-noselect').trigger('click');

        // Сброс всех свойств товара.
        ResetParams($block);

        $section.append($block);
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
        
        PutBasket($parent.data('pid'), quantity, $(this).closest('.js-product-block'));
        
		return false;
	});


    // Увеличение количества товара.
    $(document).on('click', '.js-pricetype-quantity .js-quantity-inc', function(event) {
        var $parent  = $(this).parent();
        var $product = $parent.closest('.js-product-block');
		var $input   = $parent.find('input');
        var quantity = parseInt($input.val()) + 1;
        
		$input.val(quantity);
		$input.change();

		PutBasket($parent.data('pid'), quantity, $(this).closest('.js-product-block'));

		return false;
	});


    // Выбор товара из выпадающего списка.
    $(document).on('change', '.js-product-select', function(event) {
        var $that   = $(this);
        var $block  = $that.closest('.js-product-block');
        var $option = $that.find('option:selected');

        if (!empty($option.val())) {
            $block.find('.js-product-price').html($option.data('price'));
            $block.find('.js-product-descr').html($option.data('descr'));
            $block.find('.js-product-select-price').show();
            $block.find('.js-product-select-descr').show();
        } else {
            $block.find('.js-product-select-price').hide();
            $block.find('.js-product-select-descr').hide();
        }
    });
});