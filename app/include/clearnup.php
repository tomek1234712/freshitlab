<?php

/*
 * cleanup
 */

add_action( 'admin_menu', 'remove_menu_pages' );
function remove_menu_pages() {
    remove_menu_page('edit.php'); // Wpisy
//      remove_menu_page('upload.php'); // Media
	remove_menu_page('link-manager.php'); //Odnośniki
//    remove_menu_page('edit-comments.php'); // Komentarze
//	remove_menu_page('edit.php?post_type=page'); // Strony
//	remove_menu_page('plugins.php'); // Wtyczki
//	remove_menu_page('themes.php'); // Wygląd
//	remove_menu_page('users.php'); // Użytkownicy
//	remove_menu_page('tools.php'); // Narzędzia
//      remove_menu_page('options-general.php'); // Ustawienia
}