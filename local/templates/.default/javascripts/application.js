$(function(){
  initModules();
})

function initModules() {
  $('[data-module]:not(.moduleinited)').each(function(){
      var name = $(this).addClass('moduleinited').attr('data-module');
      if (!__modules[name]) throw "Data module "+name+" is not EXISTS!!";
      __modules[name].call(this, $(this));
  })
}

function __module(path, fn) {
  if (!window.__modules) window.__modules = {};
  window.__modules[path] = fn;
}

module('pagesubtitle-dropdown', function(module){
  module.click(function(){
    module.toggleClass('open')
  })
})

__module('profilepage', function(module){
  module.find('.pagetitle__button[rel="personal"]').click(function(){
    $(this).removeClass('active')
    module.find('.pagetitle__button[rel="history"]').addClass('active')
    module.find('.profilecontainer[rel="history"]').removeClass('active')
    module.find('.profilecontainer[rel="personal"]').addClass('active')
  })
  module.find('.pagetitle__button[rel="history"]').click(function(){
    $(this).removeClass('active')
    module.find('.pagetitle__button[rel="personal"]').addClass('active')
    module.find('.profilecontainer[rel="personal"]').removeClass('active')
    module.find('.profilecontainer[rel="history"]').addClass('active')
  })
    module.find('.profilecontainer__changebutton').click(function() {
        var closest = $(this).closest('div.profilecontainer__item'),
            name = $(closest).find('input').attr('name');
        $(closest).find('input').show();
        $(closest).find('textarea').show();
        $(closest).find('span').hide();
        $(this).hide();
        if (name == 'NEW_PASSWORD') {
            $('.password_confirm').show();
            $('.password_confirm input').show();
        }
    })
})
