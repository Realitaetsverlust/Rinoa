$(document).ready(function() {
    var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
    console.log(width);
    $(window).scroll(function() {
        if(width <= 640) {
            if($(window).scrollTop() !== 0) {
                $("#sidebar").hide();
            } else {
                $("#sidebar").show();
            }
        }
    });
});