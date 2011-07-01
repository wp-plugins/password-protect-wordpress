<?php
/*
Plugin Name: SD Total Privacy
Plugin URI: http://www.spiders-design.co.uk/plugins/password-protect-wordpress/
Description: SD Total Privacy is a password protect wordpress plugin which allows you to password protect all of your wordpress blog including all posts and feeds.
Version: 4.0
Author: Daniel Chatfield
Author URI: http://www.spiders-design.co.uk
License: GPLv2
*/
?>
<?php
require_once( dirname( __file__ ) . '/framework/_init.php' );
require_once( dirname( __file__ ) . '/_class.php' );

global $sdTotalPrivacy;

$sdTotalPrivacy = new sdTotalPrivacy( 'SD Privacy', '4.0' );

$adminMenu = $sdTotalPrivacy->addMenuPage();
$settingsPage = $adminMenu->addSubmenuPage( "Settings" );



?>