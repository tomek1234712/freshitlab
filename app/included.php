<?php

require INCLUDE_SCRIPT_DIR . DS . 'support.php';
require INCLUDE_SCRIPT_DIR . DS . 'register_css_js.php';
//require INCLUDE_SCRIPT_DIR . DS . 'role.php';
require INCLUDE_SCRIPT_DIR . DS . 'acf.php';
require INCLUDE_SCRIPT_DIR . DS . 'custom_css_admin.php';
require INCLUDE_SCRIPT_DIR . DS . 'filters.php';
require INCLUDE_SCRIPT_DIR . DS . 'media.php';
require INCLUDE_SCRIPT_DIR . DS . 'menus.php';
require INCLUDE_SCRIPT_DIR . DS . 'other.php';
require INCLUDE_SCRIPT_DIR . DS . 'pagination.php';
require INCLUDE_SCRIPT_DIR . DS . 'widgets.php';
require INCLUDE_SCRIPT_DIR . DS . 'clearnup.php';
require INCLUDE_SCRIPT_DIR . DS . 'custom_css_frontend.php';

/*
 * Include metaboxes
 */

//require METABOXES_DIR . DS . 'training_date_place_trainer_relation.php';
//require METABOXES_DIR . DS . 'trainings_reports.php';
//require METABOXES_DIR . DS . 'companies_reports.php';
//require METABOXES_DIR . DS . 'member_reports.php';

/*
 * Save data override
 */

//require METABOXES_DIR . DS . 'after_save_company.php';
//require METABOXES_DIR . DS . 'after_save_member.php';

/*
 * Include actions
 */

//require ACTIONSDIR . DS . 'register.php';
//require ACTIONSDIR . DS . 'newsletter.php';
//require ACTIONSDIR . DS . 'contact_form.php';
//require ACTIONSDIR . DS . 'ask_form.php';
//require ACTIONSDIR . DS . 'tell_friend.php';
//require ACTIONSDIR . DS . 'report_generator.php';
//require ACTIONSDIR . DS . 'archiving_trainings.php';
//require ACTIONSDIR . DS . 'capitalize_titles.php';

/*
 *  Include plugins
 */

include_once( INC_PLUGIN_DIR . DS . 'codepress-admin-columns' . DS . 'codepress-admin-columns.php');
include_once( INC_PLUGIN_DIR . DS . 'intuitive-custom-post-order' . DS . 'intuitive-custom-post-order.php'); // ALTER TABLE wp_terms ADD `term_order` INT( 4 ) NULL DEFAULT '0';
include_once( INC_PLUGIN_DIR . DS . 'post-type-archive-links' . DS . 'post-type-archive-links.php');
include_once( INC_PLUGIN_DIR . DS . 'regenerate-thumbnails' . DS . 'regenerate-thumbnails.php');
include_once( INC_PLUGIN_DIR . DS . 'mayo-login-screen' . DS . 'mayo-login-screen.php');
