<?php

class SocialUserRegistration {
	
	public function __construct(){
		add_action('rest_api_init', array($this, 'register_api_hook'));		
	}

	public function register_api_hook( $routes ) {
		// For update global options
		register_rest_route(
			'wc/v3',
			'/social_login_or_registration/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, '__socialLoginOrRegistration'),
					'args' => array()
				)
			)
		);
		// Just for demo check
		register_rest_route(
			'wc/v3',
			'/get_user_details/',
			array(
				array(
					'methods'  => 'GET',
					'callback' => array($this, 'getUserDetails'),
					'args' => array()
				)
			)
		);
	}

	// Just for demo check
	public function getUserDetails($request) {
		try{
			$data = $request->get_params();
			if (!isset($data['user_email'])) {
				throw new Exception("User email need!");
			}
			$email_check = email_exists( $data['user_email'] );
			if(!$email_check) { 
				throw new Exception("User not found!");
			}
			global $woocommerce; 

			 // WC()->customer(); //$woocommerce->get('customers/'+$user->ID);  //WC_API_Customers::get_customer($user->ID);

			$user = get_user_by( 'id', $email_check );			

		}catch(Exception $e) {
			return ['message'=>$e->getMessage(), 'success'=>false];
		}
	}

	public function getCustomerData($userId) {
		$customer  = new WC_Customer( $userId );
		$last_order    = $customer->get_last_order();
		$customer_data = array(
			'id'               => $customer->get_id(),
			'email'            => $customer->get_email(),
			'first_name'       => $customer->get_first_name(),
			'last_name'        => $customer->get_last_name(),
			'username'         => $customer->get_username(),
			'role'             => $customer->get_role(),
			'last_order_id'    => is_object( $last_order ) ? $last_order->get_id() : null,
			'last_order_date'  => is_object( $last_order ) ? $this->server->format_datetime( $last_order->get_date_created() ? $last_order->get_date_created()->getTimestamp() : 0 ) : null, // API gives UTC times.
			'orders_count'     => $customer->get_order_count(),
			'total_spent'      => wc_format_decimal( $customer->get_total_spent(), 2 ),
			'avatar_url'       => $customer->get_avatar_url(),
			'billing'  => array(
			  'first_name' => $customer->get_billing_first_name(),
			  'last_name'  => $customer->get_billing_last_name(),
			  'company'    => $customer->get_billing_company(),
			  'address_1'  => $customer->get_billing_address_1(),
			  'address_2'  => $customer->get_billing_address_2(),
			  'city'       => $customer->get_billing_city(),
			  'state'      => $customer->get_billing_state(),
			  'postcode'   => $customer->get_billing_postcode(),
			  'country'    => $customer->get_billing_country(),
			  'email'      => $customer->get_billing_email(),
			  'phone'      => $customer->get_billing_phone(),
			),
			'shipping' => array(
			  'first_name' => $customer->get_shipping_first_name(),
			  'last_name'  => $customer->get_shipping_last_name(),
			  'company'    => $customer->get_shipping_company(),
			  'address_1'  => $customer->get_shipping_address_1(),
			  'address_2'  => $customer->get_shipping_address_2(),
			  'city'       => $customer->get_shipping_city(),
			  'state'      => $customer->get_shipping_state(),
			  'postcode'   => $customer->get_shipping_postcode(),
			  'country'    => $customer->get_shipping_country(),
			),
		  );

		return $customer_data;
	}

	public function __socialLoginOrRegistration($request) {
		try{
			$data = $request->get_params();
			$user = $this->__user_exists_check( $data );
			if( !$user['user'] ) {
				// Register this user and set userinfo into the $user variable
				$user_id = $this->__create_user( $data );

				if( is_wp_error( $user_id ) ) {
					throw new Exception($user_id->get_error_message());
				}
				
				$user = get_user_by( 'id', $user_id );

				if ( class_exists( 'WooCommerce' ) ) {
					$user->set_role('customer');
				}
			} 
		
			$user = is_array($user) ? $user['user'] : $user;

			return $this->getCustomerData($user->ID);

		}catch( Exception $e) {
			return ['message'=>$e->getMessage(), 'success'=>false];
		}
	}
	 
	
	
	private function __user_exists_check( $data ) {
		// check if user exists in WP or DB
		$return = array('user' => false );
		if( isset( $data['user_email'] ) ) {
			$email_check = email_exists( $data['user_email'] );
			if( $email_check ) {
				$db_user = $this->__user_db_check( $data['social_id'] );
				if( !$db_user ) {
					$this->__create_user_db( $email_check, $data['social_id'] );	
				}				
				$return['user'] = get_user_by( 'id', $email_check );
			}
		} 
		
		if( isset( $data['social_id'] ) && $return['user'] == false ) {
			$db_user = $this->__user_db_check( $data['social_id'] );
			if( $db_user ) {
				$return['user'] = get_user_by( 'id', $db_user->wp_user_id );	
			}
		} 
		
		if( !isset( $data['social_id'] ) && !isset( $data['user_email'] ) ) {
			return new WP_Error( 'No Data', __( 'Expecting social_id or user_email' ), array( 'status' => 400 ) );
		}
		
		return $return;
		
	}
	
	private function __user_db_check( $social_id ) {
		// Check wp_social_api table for user
		global $wpdb;
		$table_name = USER_SOCIAL_TABLE;
		
		$db_user_row = $wpdb->get_row( $wpdb->prepare(
			"SELECT * FROM $table_name WHERE social_id = %s ", 
			$social_id 
		));

		return $db_user_row;
		
	}
	/**
	 * $data {Object} 
	 * username, nickname, user_email, social_id, first_name, last_name, description
	 */
	public function __create_user( $data ) {
		$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
			
		if(isset($data['username']) ) {
			$username = $data['username'];
		}elseif( isset( $data['nickname'] ) ) {
			$username = str_replace( ' ', '_', $data['nickname'] );	
		} else {
			$username = 'social_'.$data['social_id'];
		}

		$username = strtolower( $username );
		
		if( isset( $data['user_email'] ) ) {
			$user_email = $data['user_email'];
		} else {
			$user_email = $data['social_id'].'@'.$_SERVER['SERVER_NAME'];
		}
		
		$user_id = wp_create_user( $username, $random_password, $user_email );
		
		if( is_wp_error( $user_id ) ) {
			return new WP_Error( 'Create Error', __( $user_id->get_error_message() ), array( 'status' => 401 ) ); 
		}
		
		$user_update = array( 'ID' => $user_id );
		
		if( isset( $data['first_name'] ) ) {
			$user_update['first_name'] = $data['first_name'];
		}
		
		if( isset( $data['last_name'] ) ) {
			$user_update['last_name'] = $data['last_name'];
		}
		
		if( isset( $data['description'] ) ) {
			$user_update['description'] = $data['description'];
		}
		
		if( isset( $data['nickname'] ) ) {
			$user_update['user_nicename'] = $data['nickname'];
		}
		
		$update_user = wp_update_user( $user_update );
		
		if( is_wp_error( $update_user ) ) {
			return new WP_Error( 'Update Error', __( $update_user->get_error_message() ), array( 'status' => 400 ) );
		}
		$this->__create_user_db( $user_id, $data['social_id'] );
		
		return $user_id;
	}
	
	private function __create_user_db( $user_id, $social_id ) {
		global $wpdb;
		$table_name = USER_SOCIAL_TABLE;
		
		$db = $wpdb->insert(
			$table_name,
			array(
				'created_time' 	=> current_time( 'mysql' ),
				'social_id' 	=> $social_id,
				'wp_user_id' 	=> $user_id
			),
			array(
				'%s',
				'%s',
				'%d'
		));
	}
	
	public function __user_delete( $user_id ) {
		global $wpdb;
		$table_name = USER_SOCIAL_TABLE;
		
		$db = $wpdb->delete(
			$table_name,
			array(
				'wp_user_id' 	=> $user_id
			),
			array(
				'%d',
		));
		
	}
}