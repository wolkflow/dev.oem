$(document).ready(function() {

	$('.itemCount__up').click(function() {
		var $input = $(this).parent().find('input');
		$input.val(parseInt($input.val()) + 1);
		$input.change();
		return false;
	});


	// 26
	/*$(document).on('click', '.saveButton', function(){
		$(this).parents('.serviceContainer').find('[data-module="pagesubtitle-dropdown"]').removeClass('open');
		$(this).parents('.serviceContainer').next('.serviceContainer').find('[data-module="pagesubtitle-dropdown"]').addClass('open')
        $('.styler').trigger('refresh');
	});
*/

	/**
	 * Обработка выпадалки
	 */
		/** Активация **/
		var Nether = 0;
		// Мышка попала на заголовок
		$(document).on('mouseenter', '.indexpage__choosestandtitle', function(){
			$('.indexpage__choosestand').removeClass('active');
			$(this).parents('.indexpage__choosestand').addClass('active');
			Nether = 0
		});

			/** Проверки **/
			// Введены данные
			$(document).on('keyup', '.indexpage__choosestandform input',function(){
				if($(this).val()>0)
					$(this).parents('.indexpage__choosestand').addClass('active');
					Nether = 1
			});
			// Инпут получил фокус
			$(document).on('focus', '.indexpage__choosestandform input',function(){
				$(this).parents('.indexpage__choosestand').addClass('active');
				Nether = 1
			});
			// Один из типов выбран
			$(document).on('click', '.indexpage__choosestandform label',function(){
				$(this).parents('.indexpage__choosestand').addClass('active');
				Nether = 1
			});

		/** Деактивация **/
		// Мышка пришла в блок 1
		$(document).on('mouseenter', '.system', function(){
			if(typeof stopAction !== 'undefined') clearTimeout(stopAction);
		});
		// Мышка ушла из блока 1
		$(document).on('mouseleave', '.system', function(){
			if(Nether == 0)
				stopAction = setTimeout(function(){
				$('.system').removeClass('active');
			}, 3000)
		});
		// Мышка пришла в блок 2
		$(document).on('mouseenter', '.individual', function(){
			if(typeof stopAction2 !== 'undefined') clearTimeout(stopAction2);
		});
		// Мышка ушла из блока 2
		$(document).on('mouseleave', '.individual', function(){
			if(Nether == 0)
			stopAction2 = setTimeout(function(){
				$('.individual').removeClass('active');
			}, 3000)
		});

		// Пользователь кликнул вне окна
		$(document).mouseup(function (e) {
			var container = $('.indexpage__choosestand.active');
			if (container.has(e.target).length === 0){
				container.removeClass('active');
				Nether = 0
			}
		});


	$('#general-info .generalInfoContent').slick({
		infinite: false,
		slidesToShow: 1,
		slidesToScroll: 1,
		appendArrows: '.windowNavigate',
		adaptiveHeight: true
	});
	// Modals
	$(document).on('click', '[data-modal]',function() {
		var sliderStatus = 0;
		var modal = $(this).data('modal');
		if(modal == '#general-info' && sliderStatus == 0) {
			$(modal).arcticmodal({
				beforeOpen: function (data, el) {
					$('.windowNavigate').html('')
				},
				afterOpen: function (data, el) {
					$('#general-info .generalInfoContent').slick('reinit')
				}
			});
			sliderStatus = 1;
		} else {
			$(modal).arcticmodal();
		}
		return false;
	});


	$('.itemColor__custom').click(function() {
		var modal = $(this).attr('data-modal');
		var vortex = $(this).attr('id');
		$(modal).arcticmodal();
		$(modal).attr('data-vortex', vortex);
		return false;
	});
	
	$('[data-module="pagesubtitle-dropdown"]').click(function() {
		$(this).toggleClass('open');
	});



	//window.onload = function() {
	//	$('.basketcontainer').airStickyBlock({
	//		stopBlock: '.main'
	//	});
	//};

    
    $(document).on('click', '.content-link', function(e) {
        e.preventDefault();
        $.arcticmodal({
            type: 'ajax',
            url: this.href
        });
    });

});

