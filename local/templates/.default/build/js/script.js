(function($){
	$.fn.objectize = function(){
		var self = this,
			json = {},
			push_counters = {},
			patterns = {
				"validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
				"key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
				"push":     /^$/,
				"fixed":    /^\d+$/,
				"named":    /^[a-zA-Z0-9_]+$/
			};
		
		this.build = function(base, key, value) {
			base[key] = value;
			return base;
		};
 
		this.push_counter = function(key) {
			if (push_counters[key] === undefined) {
				push_counters[key] = 0;
			}
			return push_counters[key]++;
		};
 
		$.each($(this).serializeArray(), function() {
			if (!patterns.validate.test(this.name)) {
				return;
			}
			var k,
				keys = this.name.match(patterns.key),
				merge = this.value,
				reverse_key = this.name;
 
			while ((k = keys.pop()) !== undefined) {
				reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');
 
				if (k.match(patterns.push)) {
					merge = self.build([], self.push_counter(reverse_key), merge);
				} else if(k.match(patterns.fixed)) {
					merge = self.build([], k, merge);
				} else if(k.match(patterns.named)) {
					merge = self.build({}, k, merge);
				}
			}
			json = $.extend(true, json, merge);
		});
		return json;
	};
})(jQuery);

$(document).ready(function() {

/*
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
        Nether = 0;
    });

    /** Проверки **/
    // Введены данные
    $(document).on('keyup', '.indexpage__choosestandform input',function(){
        if ($(this).val() > 0) {
            $(this).parents('.indexpage__choosestand').addClass('active');
        }
        Nether = 1;
    });
    
    // Инпут получил фокус.
    $(document).on('focus', '.indexpage__choosestandform input',function(){
        $(this).parents('.indexpage__choosestand').addClass('active');
        Nether = 1;
    });
    
    // Один из типов выбран.
    $(document).on('click', '.indexpage__choosestandform label',function(){
        $(this).parents('.indexpage__choosestand').addClass('active');
        Nether = 1;
    });

    /** Деактивация **/
    // Мышка пришла в блок 1
    $(document).on('mouseenter', '.system', function(){
        if (typeof stopAction !== 'undefined') {
            clearTimeout(stopAction);
        }
    });
    
    // Мышка ушла из блока 1
    $(document).on('mouseleave', '.system', function(){
        if (Nether == 0) {
            stopAction = setTimeout(function() {
                $('.system').removeClass('active');
            }, 3000);
        }
    });
    
    // Мышка пришла в блок 2.
    $(document).on('mouseenter', '.individual', function(){
        if (typeof stopAction2 !== 'undefined') {
            clearTimeout(stopAction2);
        }
    });
    
    // Мышка ушла из блока 2.
    $(document).on('mouseleave', '.individual', function(){
        if (Nether == 0) {
            stopAction2 = setTimeout(function() {
                $('.individual').removeClass('active');
            }, 3000);
        }
    });

    // Пользователь кликнул вне окна.
    $(document).mouseup(function (e) {
        var container = $('.indexpage__choosestand.active');
        if (container.has(e.target).length === 0){
            container.removeClass('active');
            Nether = 0;
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
		if (modal == '#general-info' && sliderStatus == 0) {
			$(modal).arcticmodal({
				beforeOpen: function (data, el) {
					$('.windowNavigate').html('');
				},
				afterOpen: function (data, el) {
					$('#general-info .generalInfoContent').slick('reinit');
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

