<?php
function _addMenuSupport() {
    add_theme_support('post-thumbnails');
    add_theme_support( 'title-tag' );
    add_theme_support('menus');
    add_theme_support('html5');
    //add_theme_support( 'woocommerce' );
}
add_action('init', '_addMenuSupport');