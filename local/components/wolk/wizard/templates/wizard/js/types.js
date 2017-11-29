$(function() {
   sessionStorage.clear();
   
   /*
   $(document).on('submitsdsd', '.js-stand-select-form', function(e) {
       e.preventDefault();
       
       var $form = $(this);
       var width = $form.find('.js-stand-width').val().replace(',', '.');
       var depth = $form.find('.js-stand-depth').val().replace(',', '.'); 
       var sform = $form.find('.js-stand-sform:checked').val();
	   
	   width = (Math.ceil(width / 0.5)) * 0.5;
	   depth = (Math.ceil(depth / 0.5)) * 0.5;
       
       var action = $form.attr('action') + width + 'x' + depth + '/';
       
       if (sform != undefined) {
           action += sform + '/';
       }
       
       // Перенаправление на конструктор.
       location.href = action;
   });
   */
});