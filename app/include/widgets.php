<?php

function freshitlab_widgets_init() {

    register_sidebar( array(
        'name' => 'Stopka strony kolumna lewa',
        'id' => 'footer_left',
        'before_widget' => '<div id="footer_left" class="footer_column_widget">',
        'after_widget' => '</div>',
        'before_title' => '<div class="f-title">',
        'after_title' => '</div>',
    ) );
    register_sidebar( array(
        'name' => 'Stopka strony kolumna lewa',
        'id' => 'footer_right',
        'before_widget' => '<div id="footer_right" class="footer_column_widget">',
        'after_widget' => '</div>',
        'before_title' => '<div class="f-title">',
        'after_title' => '</div>',
    ) );

}
//add_action( 'widgets_init', 'freshitlab_widgets_init' );