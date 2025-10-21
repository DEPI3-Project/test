(function($){
  $(function(){
    if (window.AdminEase && AdminEase.compact) {
      $('body').addClass('adminease-compact');
    }
    if (window.AdminEase && AdminEase.colorScheme === 'dark') {
      // prefer dark regardless of system
      $('meta[name="color-scheme"]').remove();
      $('head').append('<meta name="color-scheme" content="dark">');
    } else if (window.AdminEase && AdminEase.colorScheme === 'light') {
      $('meta[name="color-scheme"]').remove();
      $('head').append('<meta name="color-scheme" content="light">');
    }

    // Smooth scroll to anchors in settings page
    $(document).on('click', 'a[href^="#"]', function(e){
      var target = $($(this).attr('href'));
      if(target.length){
        e.preventDefault();
        $('html, body').animate({scrollTop: target.offset().top - 80}, 250);
      }
    });
  });
})(jQuery);
