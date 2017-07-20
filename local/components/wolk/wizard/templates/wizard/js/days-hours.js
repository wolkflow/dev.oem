$(document).ready(function() {
    
    
    
    
    $('.js-block-days-hours .js-days-hours-datepicker').each(function() {
    // $(document).on('click', '.js-days-hours-datepicker', function() {
        var $that = $(this);
        
        var date = new Date();
        
        $that.multiDatesPicker({
            dateFormat: 'dd.mm.yy',
            minDate:    0,
            autoclose:  true,
            altField:   '#alter',
            onSelect:   function(date) {
                var dates;
                //dates = $(dp).multiDatesPicker('getDates');
                //self.set({dateStart: dates[0], dateEnd: dates[1]});
            }
        });
    });




    // Добавление нового поля.
    $(document).on('click', '.js-block-days-hours .js-more-field', function(event) {
        
    });
    
    
    // Уменьшение количества товара.
    $(document).on('keyup', '.js-pricetype-days-hours .js-quantity', function(event) {
        
    });
    
    
});