$(document).ready(function() {

	// Увеличение количества к пощиции из корзины.
    $(document).on('click', '.js-basket-inc', function(e) {
		var $that     = $(this);
		var $wrapper  = $('#js-wrapper-id');
		var $block    = $that.closest('.js-product-block');
		var $quantity = $block.find('.js-product-quantity');
		
		// Идентификатор корзины.
        var bid = $that.data('bid');
		var sid = $block.closest('.js-product-section').attr('data-sid');
        
        if (!bid.length) {
            return;
        }
		
		var quantity = parseInt($quantity.data('quantity'));
		
		$.ajax({
            url: '/remote/',
            type: 'post',
            data: {
                'action':   'update-basket-quantity',
                'sessid':   BX.bitrix_sessid(),
                'bid':      bid,
                'eid':      $wrapper.data('eid'),
                'code':     $wrapper.data('code'),
                'type':     $wrapper.data('type'),
				'quantity': (quantity + 1),
				'template': $that.data('template'),
            },
            dataType: 'json',
			beforeSend: function() {
				blockremote = true;
			},
            success: function(response) {
                if (response.status) {
                    switch (response.data['template']) {
						case ('order'):
							$quantity.html(response.data['item']['quantity']);
							$quantity.data('quantity', response.data['item']['quantity']);
							$block.find('.js-product-cost').html(response.data['item']['cost-format']);
							$('#js-summary-wrapper-id').html(response.data['html']);
							break;
						default:
							$('#js-basket-wrapper-id').html(response.data['html']);
							break;
					}
                }
				blockremote = false;
            }
        });
	});
	
	
	// Уменьшение количества к пощиции из корзины.
    $(document).on('click', '.js-basket-dec', function(e) {
		var $that     = $(this);
		var $wrapper  = $('#js-wrapper-id');
        var $block    = $that.closest('.js-product-block');
		var $quantity = $block.find('.js-product-quantity');
		
		// Идентификатор корзины.
        var bid = $that.data('bid');
		var sid = $block.closest('.js-product-section').attr('data-sid');
        
        if (!bid.length) {
            return;
        }
		
		var quantity = parseInt($quantity.data('quantity'));
		
		// Удаляем позицию.
		// if (quantity == 1) {
		//	$block.find('.js-basket-delete').trigger('click'); // (bid, sid, $block);
        //    return;
		// }
		
		$.ajax({
            url: '/remote/',
            type: 'post',
            data: {
                'action':   'update-basket-quantity',
                'sessid':   BX.bitrix_sessid(),
                'bid':      bid,
                'eid':      $wrapper.data('eid'),
                'code':     $wrapper.data('code'),
                'type':     $wrapper.data('type'),
				'quantity': (quantity > 1) ? (quantity - 1) : (0),
				'template': $that.data('template'),
            },
            dataType: 'json',
			beforeSend: function() {
				blockremote = true;
			},
            success: function(response) {
                if (response.status) {
					switch (response.data['template']) {
						case ('order'):
							$quantity.html(response.data['item']['quantity']);
							$quantity.data('quantity', response.data['item']['quantity']);
							$block.find('.js-product-cost').html(response.data['item']['cost-format']);
							$('#js-summary-wrapper-id').html(response.data['html']);
							break;
						default:
							$('#js-basket-wrapper-id').html(response.data['html']);
							break;
					}
                }
				blockremote = false;
            }
        });
	});
	

	 // Удаление из корзины.
    $(document).on('click', '.js-basket-remove', function(e) {
        var bid = $(this).data('bid');
        var sid = $(this).data('sid');

        if (bid.length > 0) {
            RemoveBasket(bid, sid);
        }
    });


	// Удаление элемента корзины.
    $(document).on('click', '.js-basket-delete', function() {
        var $that    = $(this);
        var $block   = $that.closest('.js-product-block');
        var $wrapper = $('#js-wrapper-id');
        
        // Идентификатор корзины.
        var bid = $that.data('bid');
        
        if (!bid.length) {
            return;
        }
        
        $.ajax({
            url: '/remote/',
            type: 'post',
            data: {
                'action': 'remove-basket',
                'sessid': BX.bitrix_sessid(),
                'bid':    bid,
                'eid':    $wrapper.data('eid'),
                'code':   $wrapper.data('code'),
                'type':   $wrapper.data('type')
            },
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    $block.remove();
                }
            }
        });
    });
});
