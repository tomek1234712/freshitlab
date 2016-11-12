<?php 
// Define some constants

if ( ! defined('THEME_DIR' ) ) {
    define('THEME_DIR', realpath(dirname(__FILE__)));
}

if ( ! defined('DS' ) ) {
    define('DS', DIRECTORY_SEPARATOR);
}

if ( ! defined('APP' ) ) {
    define('APP', THEME_DIR . DS . 'app');
}

if ( ! defined('INC_PLUGIN_DIR' ) ) {
    define('INC_PLUGIN_DIR', THEME_DIR . DS . 'plugins');
}

if ( ! defined('INCLUDE_SCRIPT_DIR' ) ) {
    define('INCLUDE_SCRIPT_DIR', APP . DS . 'include');
}

if ( ! defined('METABOXES_DIR' ) ) {
    define('METABOXES_DIR', APP . DS . 'metaboxes');
}

if ( ! defined('ACTIONSDIR' ) ) {
    define('ACTIONSDIR', THEME_DIR . DS . 'actions');
}

if ( ! defined('EMAILSSDIR' ) ) {
    define('EMAILSDIR', THEME_DIR . DS . 'emails');
}

if ( ! defined('PROJECT_SLUG' ) ) {
    define('PROJECT_SLUG', 'domwiedzy');
}

if ( ! defined('ASSETS_DIR' ) ) {
    define('ASSETS_DIR', get_template_directory_uri() . '/assets');
}

if ( ! defined('IMAGES_URI' ) ) {
    define('IMAGES_URI', get_template_directory_uri() . '/assets/images/');
}

if ( ! defined('EMAIL_HTML_HEADER' ) ) {
    define('EMAIL_HTML_HEADER', '<html style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"><head> <meta name="viewport" content="width=device-width"> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> <title>Dom Wiedzy</title></head><body bgcolor="#f6f6f6" style="font-family: \'verdana\', \'Trebuchet MS\', Helvetica, Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; -webkit-font-smoothing: antialiased; height: 100%; margin: 0px; padding: 0px; width: 100% !important; background-color: transparent;"><table class="body-wrap" bgcolor="#f6f6f6" style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: 100%; margin: 0;padding: 0px 20px 0px 20px;background-color: #FFF;color: #262858;"><tbody><tr style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"><td style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"></td><td class="container" bgcolor="#FFFFFF" style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; clear: both !important; display: block !important; Margin: 0 auto; border: 1px solid #FFF;background-color: #FFF;"> <div class="content" style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; display: block; margin: 0 auto; padding: 0;background-color: #FFF;padding: 10px;color: #262858;"> <table style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: 620px; margin: 0 auto; padding: 0;"><tbody> <tr><td style=" border-bottom: 6px solid #262858; padding-bottom: 9px; padding-top: 20px;"><img alt="" src="'.IMAGES_URI . 'logoemail.png'.'" style=""></td></tr><tr style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"> <td style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0;color: #262858;padding: 10px 0px 0 0px;">');
}

if ( ! defined('EMAIL_HTML_FOOTER' ) ) {
    define('EMAIL_HTML_FOOTER', '</td></tr></tbody></table> </div></td><td style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"></td></tr></tbody></table><table class="footer-wrap" style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; clear: both !important; width: 100%; margin: 0; padding: 0;"><tbody><tr style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"><td style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"></td><td class="container" style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; clear: both !important; display: block !important; max-width: 600px !important; margin: 0 auto; padding: 0;"> <div class="content" style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; display: block; max-width: 600px; margin: 0 auto; padding: 0;"> <table style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: 100%; margin: 0; padding: 0;"><tbody><tr style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"> <td align="center" style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"> <p style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 12px; line-height: 1.6em; color: #666666; font-weight: normal; margin: 0 0 0px; padding: 0;"></p></td></tr></tbody></table> </div></td><td style="font-family: \'verdana\', \'Trebuchet MS\', \'Helvetica\', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"></td></tr></tbody></table></body></html>');
}

require APP . DS . 'app.php';
require APP . DS . 'included.php';
?>

