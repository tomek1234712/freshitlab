<?php
function _addMenuLocations() {
    register_nav_menus( array(
        'general' => 'Menu główne'
    ) );
}
add_action('init', '_addMenuLocations');