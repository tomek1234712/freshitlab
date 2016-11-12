<?php

/*
 * Image sizes
 */

function _addImageSizes() {
    add_image_size('slider', 1200, 9999999);
    add_image_size('col33', 500, 9999999);
    add_image_size('col33crop', 500, 256, true);
    //add_image_size('product-single-related', 448, 544, array( 'center', 'top' ));
}

add_action('init', '_addImageSizes');