// apply fitvids functionality to .entry-content div
// https://github.com/davatron5000/FitVids.js
jQuery(".entry-content").fitVids();


// modified version of flexnav, does not have drop down functionality
// https://github.com/indyplanets/flexnav
// http://webdeveloper2.com/2011/06/trigger-javascript-on-css3-media-query-change/

detector = jQuery('.js');
compareWidth = detector.width();
smallScreen = '768';

jQuery('.menu-button').click(function(){
    if (!jQuery(".access-nav").is(":visible"))
        jQuery('.menu-button').addClass("nav-open");
        jQuery(".access-nav").slideToggle('fast', function() {
    if (!jQuery(".access-nav").is(":visible"))
        jQuery('.menu-button').removeClass("nav-open");
    });
});

jQuery(window).resize(function(){
    if(detector.width()!=compareWidth){
        compareWidth = detector.width();
        if (compareWidth >= smallScreen) {
            jQuery('.access-nav').show();
        }
    }
});