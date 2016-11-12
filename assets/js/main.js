var $ = jQuery;

$(window).load(function(){
    var constElems = $('.topbar').outerHeight() + $('.top-slider-bar').outerHeight() + $('footer').outerHeight() + 25;
    $('.page-content').css('min-height', ($(window).outerHeight() - constElems) + 'px');
});
$(window).resize(function(){
    var constElems = $('.topbar').outerHeight() + $('.top-slider-bar').outerHeight() + $('footer').outerHeight() + 25;
    $('.page-content').css('min-height', ($(window).outerHeight() - constElems) + 'px');
});

$(document).ready(function(){


    $('#topslider ul').bxSlider({
        auto: false,
        controls: true,
        pager: true

    });

    $('#slider_prezentacja').bxSlider({
        mode: 'fade',
        pagerCustom: '#slider_pager_prezentacja',
        controls: false
    });

    $(document).on('click', '.openPreview[data-popup]', function(){
        var popupId = $(this).attr('data-popup');
        var clonePopup = $('.popup[data-popup="'+popupId+'"]').clone();
        clonePopup.insertAfter('footer').fadeIn();
    });

    $(document).on('click', '.popup-close[data-popup]', function(){
        var popup = $(this).parent();
        popup.fadeOut().promise().done(function() {
            popup.remove();
        });
    });

    $('#nav-icon3').click(function(){
        $(this).toggleClass('open');
        $('#menu-generalne-menu').slideToggle();
    });

    if( jQuery.browser.mobile ){

        $(document).on('click', 'ul#menu-generalne-menu > li.menu-item-has-children a', function (e) {
            e.preventDefault();
            $(this).next('ul').slideToggle();
        });

    }

});