<?php

function _enqueueScripts() {

    $RegisterPrefix = "FreshItLab_";

    /*
     *  CSS
     */

    wp_enqueue_style($RegisterPrefix.'stylesheet', get_stylesheet_uri() );
    wp_enqueue_style($RegisterPrefix.'font', ASSETS_DIR . '/fonts/nexa/stylesheet.css' );
    wp_enqueue_style($RegisterPrefix.'normalize', ASSETS_DIR . '/css/normalize.css' );
    wp_enqueue_style($RegisterPrefix.'bootstrap', ASSETS_DIR . '/css/bootstrap.min.css' );
    wp_enqueue_style($RegisterPrefix.'bxcss', ASSETS_DIR . '/css/jquery.bxslider.css' );
    wp_enqueue_style($RegisterPrefix.'main', ASSETS_DIR . '/css/main.css');
    wp_enqueue_style($RegisterPrefix.'rwd', ASSETS_DIR . '/css/responsive.css' );

    /*
     *  JavaScripts
     */

    wp_enqueue_script('jquery', false , array(), false, true);
    //wp_enqueue_script($RegisterPrefix.'google-map-api', "https://maps.googleapis.com/maps/api/js?v=3.exp");
    //wp_enqueue_script($RegisterPrefix.'bootstrap', ASSETS_DIR . '/js/bootstrap.min.js', array('jquery'), false, true);
    wp_enqueue_script($RegisterPrefix.'Jsbxslider', ASSETS_DIR . '/js/jquery.bxslider.min.js', array('jquery'), false, true);
    wp_enqueue_script($RegisterPrefix.'modernizr', ASSETS_DIR . '/js/modernizr.custom.js', array('jquery'), false, true);
    //wp_enqueue_script($RegisterPrefix.'lightbox', ASSETS_DIR . '/js/lightbox.min.js', array('jquery'), false, true);
    wp_enqueue_script($RegisterPrefix.'browserDetect', ASSETS_DIR . '/js/browserDetect.js', array('jquery'), false, true);
    wp_enqueue_script($RegisterPrefix.'baguetteBoxJs', ASSETS_DIR . '/js/baguetteBox.js', array(), false, true);
    wp_enqueue_script($RegisterPrefix.'baguetteBoxPlugins', ASSETS_DIR . '/js/plugins.js', array($RegisterPrefix.'baguetteBoxJs'), false, true);
    wp_enqueue_script($RegisterPrefix.'baguetteBoxJsInit', ASSETS_DIR . '/js/baguetteBoxInit.js', array($RegisterPrefix.'baguetteBoxJs'), false, true);
    wp_enqueue_script($RegisterPrefix.'main-js', ASSETS_DIR . '/js/main.js', array('jquery'), '1.0.1', true);

    wp_localize_script(
        $RegisterPrefix.'main-js',
        'myLocalized',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'images' => ASSETS_DIR . DS . 'images' . DS,
        )
    );
}

add_action('wp_enqueue_scripts', '_enqueueScripts');