 // SITE - SLIDER Bootstrap - Page d'accueil

 function Slider(){

$('#SuggestionArticlesCarousel').carousel({
  interval: 4000
})

$('#SuggestionArticlesCarousel .carousel-inner .item').each(function(){
  var next = $(this).next();
  if (!next.length) {
    next = $(this).siblings(':first');
  }
  next.children(':first-child').clone().appendTo($(this));
  
  for (var i=0;i<2;i++) {
    next=next.next();
    if (!next.length) {
        next = $(this).siblings(':first');
    }
    
    next.children(':first-child').clone().appendTo($(this));
  }
});

 }

$(document).ready(function () {
    "use strict";
    /* === menu drop-down === */

    (function () {
        if (screen.width > 768) {
            var $dropdown = $(".nav .dropdown");
            $dropdown.mousemove(function () {
                $(this).addClass("open");
            });
            $dropdown.mouseleave(function () {
                $dropdown.removeClass("open");
            });
        }
    }());

}());