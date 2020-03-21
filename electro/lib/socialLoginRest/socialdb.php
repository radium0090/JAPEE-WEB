<?php 
global $wpdb;

class socialDb {

	function __init() {
		$this->create_table();
	}
	
	function create_table() {
		
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();		
		$table_name = USER_SOCIAL_TABLE;
		
		if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
			$sql = "CREATE TABLE $table_name (
				`id` mediumint(9) NOT NULL AUTO_INCREMENT,
				created_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				social_id varchar(255) NOT NULL,
				wp_user_id INT(10) NOT NULL,
				UNIQUE KEY id (id)
			) $charset_collate;";
			
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$returnDB = dbDelta( $sql );
		}
	}
	
}