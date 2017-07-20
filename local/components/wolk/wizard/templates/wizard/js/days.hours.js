$(document).ready(function() {
    
    
    
    
    
    
    
    //$('.js-days-hours-datepicker').each(function() {
    $(document).on('click', '.js-days-hours-datepicker', function() {
        var $that = $(this);
        
        console.log('Кликнул.', $that);
        
        $that.each(function() {
            $(this).multiDatesPicker({
                dateFormat: 'mm-dd-yy',
                minDate: 0,
                maxPicks: 2,
                autoclose: true,
                onSelect: function (date) {
                    var dates;
                    //dates = $(dp).multiDatesPicker('getDates');
                    //self.set({dateStart: dates[0], dateEnd: dates[1]});
                }
            });
        });
    });




    // Добавление нового поля.
    $(document).on('click', '.js-block-days.hours .js-more-field', function(event) {
    });
    
    
    // Уменьшение количества товара.
    $(document).on('keyup', '.js-pricetype-days.hours .js-quantity', function(event) {
    });
    
    
});