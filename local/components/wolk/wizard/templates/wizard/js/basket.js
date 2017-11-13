$(document).ready(function() {
	
	// Увеличение количества к пощиции из корзины.
    $(document).on('click', '.js-basket-inc', function(e) {
		var $that    = $(this);
        var $block   = $that.closest('.js-product-block');
        var $wrapper = $('#js-wrapper-id');
		
		// Идентификатор корзины.
        var bid = $that.data('bid');
		var sid = $block.closest('.js-product-section').attr('data-sid');
        
        if (!bid.length) {
            return;
        }
		
		var quantity = parseInt($(this).data('quantity'));
		
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
				'template': $wrapper.data('template'),
            },
            dataType: 'json',
			beforeSend: function() {
				blockremote = true;
			},
            success: function(response) {
                if (response.status) {
                    switch (response.data['template']) {
						case ('order'):
							// quantity, price, total summ.
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
		var $that    = $(this);
        var $block   = $that.closest('.js-product-block');
        var $wrapper = $('#js-wrapper-id');
		
		// Идентификатор корзины.
        var bid = $that.data('bid');
		var sid = $block.closest('.js-product-section').attr('data-sid');
        
        if (!bid.length) {
            return;
        }
		
		var quantity = parseInt($(this).data('quantity'));
		
		if (quantity == 1) {
			RemoveBasket(bid, sid, $block);
            return;
		}
		
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
				'template': $wrapper.data('template'),
            },
            dataType: 'json',
			beforeSend: function() {
				blockremote = true;
			},
            success: function(response) {
                if (response.status) {
					switch (response.data['template']) {
						case ('order'):
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

});
