// apply fitvids functionality to .entry-content div
// https://github.com/davatron5000/FitVids.js
jQuery(document).ready(function($) {
    $(".entry-content").fitVids();
});
// modified version of flexnav, does not have drop down functionality
// https://github.com/indyplanets/flexnav
// http://webdeveloper2.com/2011/06/trigger-javascript-on-css3-media-query-change/

jQuery(document).ready(function($) {
    detector = jQuery('.js');
    compareWidth = detector.width();
    smallScreen = '768';

    $('.menu-button').click(function() {
        if (!$(".access-nav").is(":visible"))
            $('.menu-button').addClass("nav-open");
            $(".access-nav").slideToggle('slow', function() {
        if (!$(".access-nav").is(":visible"))
            $('.menu-button').removeClass("nav-open");
        });
    });

    $(window).resize(function(){
        if (detector.width()!=compareWidth) {
            compareWidth = detector.width();
            if (compareWidth >= smallScreen) {
                $('.access-nav').show();
            }
        }
    });    
});
