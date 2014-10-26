/*
 * Made by WebDesignCrowd
 * http://webdesigncrowd.com
 *
 */
 
(function($){
	$(function(){
	
	
    // collapse active class on icons
    $(".collapse").collapse({ toggle: false })
    $(".navbar-header a.icon").click(function() { 
      $(this).toggleClass("active");
      $(this).siblings("a.icon").each(function() {
        $(this).removeClass("active");
        var target = $(this).data("target");
        $(target).collapse("hide");
      })
    });
    
    // MixItUp Grid
    $(function(){
      $('.gallery').mixitup({
        easing: 'snap',
        showOnLoad: 'icons',
        resizeContainer: true
      });
		});
    
    // client tab init
    $(document).off('click.tab.data-api');
    $('a.tab').hover(function () { $(this).tab('show'); });
    
    
        
    $(window).scroll(function() {
      if ($(window).width() > 600) {
        // slide in images in #Service
        var top = $(window).scrollTop();
        $("#services img.slide-in").each(function () {
          var thisTop = $(this).offset().top;
          var height = $(this).height();
          if ((top > (thisTop - height)) && !$(this).hasClass("slid")) {
            $(this).addClass("slid");
          }
        });   

        // slide in meet the team
        $("#team .slide-in").each(function () {
          var thisTop = $(this).offset().top;
          var height = $(this).height();
          if ((top > (thisTop - height)) && !$(this).hasClass("slid")) {
            $(this).addClass("slid");
          }
        });               
      }
    });
    
    
    
    // Dynamic positioning of dropdown menus (CSS Arrow)
    $(".arrow-up").each(function() {
      var hash = "#" + $(this).parent().attr("id");
      var icon = $("a.icon[data-target='" + hash + "']");
      var left = icon.children("i").first().position().left;
      var offset = icon.parent().width() - left - icon.children("i").first().width();
      $(this).css("margin-right", offset);
    });
    
    // Tooltip init
    $(".service-icon i").tooltip();
    $(".social-media a").tooltip();

    // Smooth Scrolling
    $("#menu ul li a").click(function(e) {
       $('html, body').animate({ scrollTop: $(this.hash).offset().top }, 400);
    });
    
    // Small Navbar closes Open toggle menus 
    $("ul.nav li a[href^='#']").click(function () {
      $(".navbar-collapse.in").collapse('hide');
    });
    
    // Collapsible Active Toggling 
    $("a[data-toggle='collapse']").click(function() {
      $(this).parent().parent(".panel-heading").toggleClass("active");
    });
  

	}); // end of document ready
})(jQuery); // end of jQuery name space