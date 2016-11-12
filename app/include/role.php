<?php

remove_role( 'subscriber' );
remove_role( 'contributor' );
remove_role( 'editor' );
remove_role( 'author' );


add_role(
    'domwiedzy_user',
    __( 'Użytkownik', PROJECT_SLUG ),
    array(
        'read'         => true,
        'edit_posts'   => true,
        'delete_posts' => false,
    )
);
