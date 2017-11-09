$(document).ready(function() {
	
	// Добавление количества к пощиции из корзины.
    $(document).on('click', '.js-basket-inc', function(e) {
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