function slider(){

$(function() {

  var count = $(".ui-tabs-nav li").length;
  var slideSpeed = 25000;
  var fadingSpeed = 300;
  var i = 1;
  var a = 0;
  var $slider = $('#slider-top');

  var interval;
/*$('#nav-fragment-1 a:first-child').remove();*/
  function startSlider() {
    interval = setInterval(function() {

      $("#fragment-" + i).fadeOut(fadingSpeed, function() {
        $(this).removeClass("ui-tabs-activated");
        $('#nav-fragment-' + i).removeClass("ui-tabs-active");

        a = i + 1;

        console.log("fadeout (i): " + i);

        if (i == count) {
          a = 1;
          i = 0;
        }

        console.log("fadein (a): " + a);

        $('#nav-fragment-' + a).addClass("ui-tabs-active");
        $("#fragment-" + a).fadeIn(fadingSpeed, function() {
          $(this).addClass("ui-tabs-activated");
        });

        i++;

      });

    }, slideSpeed); // End setInterval function
  }

  function stopSlider() {
    clearInterval(interval);
  }


  $('.ui-tabs-nav-item > a').click(function(evt) {

    evt.preventDefault();
    stopSlider();
    i = Number($(this).attr('href')); // href's are values from 1, 2, 3, 4, or 5. Make it a number otherwise next time   a = i + 1 will result i = 21, 31, etc

    var id = $(".ui-tabs-active > a").attr('href');

    console.log("after click fadein (i): " + i);
    console.log("after click fadeout (id)" + id);
    console.log("value of a: " + a);

    $("#fragment-" + id).fadeOut(fadingSpeed, function() {
      $(this).removeClass("ui-tabs-activated");
      $('#nav-fragment-' + id).removeClass("ui-tabs-active");

      $('#nav-fragment-' + i).addClass("ui-tabs-active");
      $("#fragment-" + i).fadeIn(fadingSpeed, function() {
        $(this).addClass("ui-tabs-activated");
      });

      // i++;
      a = i + 1;
      if (a >= count) a = a - count;

    });

    startSlider(); // Start slider again


  });

  startSlider();

}); // jQuery function

}