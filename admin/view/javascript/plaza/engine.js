$(document).ready(function () {
    var top_bar_offset = $('.top-menu-bar').offset();
    var top_bar_gap = top_bar_offset.top + $('.top-menu-bar').outerHeight();

    $(window).scroll(function () {
        if ($(this).scrollTop() > top_bar_gap) {
            $('.top-menu-bar').addClass("bar-fixed");
        } else {
            $('.top-menu-bar').removeClass("bar-fixed");
        }
    });
});
