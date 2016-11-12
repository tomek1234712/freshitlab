<?php

/*
 * hide admin bar
 */

add_filter('show_admin_bar', '__return_false');

/*
 * Email html
 */

function set_html_content_type() {
    return 'text/html';
}


//function forse_posts_orderby_title($orderby_statement) {
//    global $wpdb;
//    $orderby_statement = "{$wpdb->prefix}posts.post_title ASC";
//    return $orderby_statement;
//}
//
//function remove_posts_orderby_plugin($orderby_statement) {
//    global $wpdb;
//    $orderby_statement = str_replace("{$wpdb->prefix}posts.menu_order, ",'',$orderby_statement);
//    return $orderby_statement;
//}
//
//function orderdesc_posts_orderby_menuorder($orderby_statement) {
//    global $wpdb;
//    $orderby_statement = "{$wpdb->prefix}posts.menu_order ASC";
//    return $orderby_statement;
//}