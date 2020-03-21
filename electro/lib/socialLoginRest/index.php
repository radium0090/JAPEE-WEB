<?php 
global $wpdb;

define( 'USER_SOCIAL_TABLE', $wpdb->prefix . 'social_user' );

require 'socialdb.php';
require 'wp_auth.php';


class WPApiSocialLogin {
	 
	function __init()  {
		
		/** DB CREATION **/
		$social_db = new socialDb();
		$social_db->__init();
		
		/** SOCIAL AUTH ROUTES **/
		new SocialUserRegistration();
	}
	 
}

$SocialLogin = new WPApiSocialLogin();
$SocialLogin->__init();