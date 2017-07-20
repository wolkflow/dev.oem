$(function() {
   sessionStorage.clear();
   
   
   
   
   
   
   $(document).on('submit', '.js-stand-select-form', function(e) {
       e.preventDefault();
       
       var $form = $(this);
       var width = $form.find('.js-stand-width').val();
       var depth = $form.find('.js-stand-depth').val(); 
       var sform = $form.find('.js-stand-sform:checked').val();
       
       var action = $form.attr('action') + width + 'x' + depth + '/';
       
       if (sform != undefined) {
           action += sform + '/';
       }
       
       // Перенаправление на конструктор.
       location.href = action;
   });
});