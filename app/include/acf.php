<?php
/*
 *  Advanced Custom Fields
 *  License Key: b3JkZXJfaWQ9NDAyNDF8dHlwZT1kZXZlbG9wZXJ8ZGF0ZT0yMDE0LTA5LTIxIDIyOjU2OjQw
 *  License type: only for this theme
 *  License account: cihy87@gmail.com
 */

// 1. customize ACF path
add_filter('acf/settings/path', 'my_acf_settings_path');

function my_acf_settings_path( $path ) {

    // update path

    $path = INC_PLUGIN_DIR . DS . 'advanced-custom-fields-pro' . DS;

    // return
    return $path;

}

// 2. customize ACF dir
add_filter('acf/settings/dir', 'my_acf_settings_dir');

function my_acf_settings_dir( $dir ) {

    // update path
    $dir = get_template_directory_uri() . DS . 'plugins' . DS . 'advanced-custom-fields-pro' . DS;

    // return
    return $dir;

}

if($_SERVER['REMOTE_ADDR'] != '127.0.0.1'){

    // 3. Hide ACF field group menu item
    add_filter('acf/settings/show_admin', '__return_false');

}


// 4. Include ACF
include_once( INC_PLUGIN_DIR . DS . 'advanced-custom-fields-pro' . DS . 'acf.php' );


/*
 * Customize field types
 */

if( function_exists('acf_add_options_sub_page') )
{

    // add parent
    $ammdomSettings = acf_add_options_page(array(
        'page_title' 	=> __( 'Ustawienia ogólne', PROJECT_SLUG),
        'menu_title' 	=> 'Ammdom',
        'redirect' 		=> false
    ));

    $ammdomSlider = acf_add_options_page(array(
        'page_title' 	=> __( 'Ustawienia slidera', PROJECT_SLUG),
        'menu_title' 	=> 'Slider',
        'redirect' 		=> false
    ));


}

/*
 * Define fields
 */

