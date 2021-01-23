$(document).ready(function () {
    var top_bar = $('.top-menu-bar');
    if(top_bar.length) {
        var top_bar_offset = $('.top-menu-bar').offset();
        var top_bar_gap = top_bar_offset.top + $('.top-menu-bar').outerHeight();

        $(window).scroll(function () {
            if ($(this).scrollTop() > top_bar_gap) {
                $('.top-menu-bar').addClass("bar-fixed");
                $('.header-engine').addClass("bar-fixed");
            } else {
                $('.top-menu-bar').removeClass("bar-fixed");
                $('.header-engine').removeClass("bar-fixed");
            }
        });
    }
});
