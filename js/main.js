jQuery(document).ready(function($){
  $(document).ready(function(){
    $("#neck-nav").sticky({topSpacing:100});
  });

  $('.nav-link').on('click',function(e){
    e.preventDefault();
    var navId = $(this).attr('id');
    if($(this).hasClass('active-dropdown')){
      $(this).removeClass('active-dropdown');
      $('.nav-link').removeClass('active-dropdown');
    } else{
      $('.nav-link').removeClass('active-dropdown');
      $(this).addClass('active-dropdown');
    }
    if($(this).hasClass('active-dropdown')){
      $('.nav-dropdown').hide();
      $('#nav-dropdown-' + navId).show();
    } else{
      $('#nav-dropdown-' + navId).hide();
    }
  });

$('a').smoothScroll({
  offset: -120,

  // one of 'top' or 'left'
  direction: 'top',

  // only use if you want to override default behavior
  scrollTarget: null,

  // string to use as selector for event delegation (Requires jQuery >=1.4.2)
  delegateSelector: null,

  // fn(opts) function to be called before scrolling occurs.
  // `this` is the element(s) being scrolled
  beforeScroll: function() {},

  // fn(opts) function to be called after scrolling occurs.
  // `this` is the triggering element
  afterScroll: function() {},
  easing: 'swing',

  // speed can be a number or 'auto'
  // if 'auto', the speed will be calculated based on the formula:
  // (current scroll position - target scroll position) / autoCoeffic
  speed: 1000,

  // autoCoefficent: Only used when speed set to "auto".
  // The higher this number, the faster the scroll speed
  autoCoefficient: 2,

  // $.fn.smoothScroll only: whether to prevent the default click action
  preventDefault: true

});

})