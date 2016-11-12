<?php
/*
 * Style for ACF settings
 * Printed in wp_footer
 */

function dynamic_frontend_css() {
     ?>
    <style type="text/css">
        .ellipse.first .ellipse-body{
            background-image: url(<?php echo get_field('tlo_naglowka', 'option'); ?>);
        }
    </style><?php
}
//add_action('wp_footer', 'dynamic_frontend_css');


/*
 * Fonts
 * Printed in wp_footer
 */

function dynamic_frontend_js() {
    ?><script src="https://use.typekit.net/nmj8gyt.js"></script><script>try{Typekit.load({ async: true });}catch(e){}</script><?php
}
//add_action('wp_footer', 'dynamic_frontend_js');