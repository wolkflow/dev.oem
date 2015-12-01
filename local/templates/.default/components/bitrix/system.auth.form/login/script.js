$(document).on('ready', function(){
    $('#login_form').submit(function(e){
        //e.preventDefault();
        var $this = $(this);
        var $form = {
            action: $this.attr('action'),
            post: {'ajax_key': BX.message('secretKey')}
        };
        $.each($('input', $this), function(){
            if ($(this).attr('name').length) {
                $form.post[$(this).attr('name')] = $(this).val();
            }
        });
        $.post($form.action, $form.post, function(data){
            $('input', $this).removeAttr('disabled');
            if (data.type == 'error') {
                $($this).find('.errortext').html(data.message);
            } else {
                window.location = window.location;
            }
        }, 'json');
        return false;
    });
})