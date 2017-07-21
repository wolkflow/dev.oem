$(document).ready(function() {
    
    
    
    
    
    
    // Добавление нового поля.
    $(document).on('click', '.js-block-days-hours .js-more-field', function(event) {
        var $wrapper = $(this).closest('.js-block-days-hours');
        var $section = $wrapper.find('.js-product-section');
        var $block   = $wrapper.find('.js-product-block').first().clone();

        $block.attr('data-bid', '');
        $block.find('.jq-selectbox__select,.jq-selectbox__dropdown').remove();
        $block.find('.styler').styler();
        $block.find('.js-product-select .js-option-noselect').trigger('click');
        
        $block.find('.js-product-price').html('');
        $block.find('.js-product-descr').html('');
        $block.find('.js-product-select-price').hide();
        $block.find('.js-product-select-descr').hide();
        
        $block.find('.js-days-hours-times option:selected').attr('selected', null);
        $block.find('.js-days-hours-datepicker').multiDatesPicker({
            dateFormat: 'dd.mm.yy',
            minDate:    0,
            autoclose:  true,
            onSelect:   function(date) {
                var dates;
                //dates = $(dp).multiDatesPicker('getDates');
                //self.set({dateStart: dates[0], dateEnd: dates[1]});
            }
        });
        
        // Сброс всех свойств товара.
        ResetParams($block);

        $section.append($block);
    });
    
    
    // Выбор даты.
    $('.js-block-days-hours .js-days-hours-datepicker').each(function() {
        var $that  = $(this);
        var $block = $that.closest('.js-product-block');
        
        $that.multiDatesPicker({
            dateFormat: 'dd.mm.yy',
            minDate: 0,
            autoclose: true,
            onSelect: function(date) {
                var $timemin = $block.find('.js-days-hours-time-min option:selected');
                var $timemax = $block.find('.js-days-hours-time-max option:selected');
                
                var dates = $(this).multiDatesPicker('getDates');
                var hours = Date.getHoursBetween(new Date('2000-01-01 ' + $timemin.val()), new Date('2000-01-01 ' + $timemax.val()));
                
                if (hours < 0) {
                    hours = Date.getHoursBetween(new Date('2000-01-01 ' + $timemin.val()), new Date('2000-01-02 ' + $timemax.val()));
                }
                if (hours != 0) {
                    hours = Math.abs(hours);
                }
                
                // Общее количество часов.
                var quantity = dates.length * hours;
                
                var pid;
                if ($block.find('.js-product-select').length) {
                    pid = $block.find('.js-product-select option:selected').val();
                } else {
                    pid = $block.find('.js-product-select').data('pid');
                }
                
                PutBasket(pid, quantity, $block);
            }
        });
    });
    
    
    // Выбор времени.
    $(document).on('change', '.js-days-hours-times', function(event) {
        var $block = $(this).closest('.js-product-block');
        
        var $timemin = $block.find('.js-days-hours-time-min option:selected');
        var $timemax = $block.find('.js-days-hours-time-max option:selected');
        
        var dates = $block.find('.js-days-hours-datepicker').multiDatesPicker('getDates');
        var hours = Date.getHoursBetween(new Date('2000-01-01 ' + $timemin.val()), new Date('2000-01-01 ' + $timemax.val()));
        
        if (hours < 0) {
            hours = Date.getHoursBetween(new Date('2000-01-01 ' + $timemin.val()), new Date('2000-01-02 ' + $timemax.val()));
        }
        if (hours != 0) {
            hours = Math.abs(hours);
        }
        
        // Общее количество часов.
        var quantity = dates.length * hours;
        
        var pid;
        if ($block.find('.js-product-select').length) {
            pid = $block.find('.js-product-select option:selected').val();
        } else {
            pid = $block.find('.js-product-select').data('pid');
        }
        
        PutBasket(pid, quantity, $block);
    });
    
    
    function setQuantityDaysHours()
    {
        
        
        //PutBasket($parent.data('pid'), quantity, $(this).closest('.js-product-block'));
    }
});