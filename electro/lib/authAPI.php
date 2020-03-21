<?php 

/**
 * Login custom api 
 */
function customerLogin($params) {
    try{
        $email = $params->get_param('email');
        $username = $params->get_param('username');
        $password = $params->get_param('password');
        if(!$email && !$username) {
            throw new Exception("Usernamd or email required!");
        }
        $user = wp_authenticate( $username, $password );
        if ( is_wp_error( $user ) ) {
            throw new Exception("username or password incorrect!");
        }
        $role = in_array('customer', $user->roles); 
        return ['success'=>true, 'user_id'=>$user->ID, 'email'=>$user->user_email, 'username'=>$user->user_login, 'is_customer' => $role];
    }catch( Exception $e) {
        return ['success'=>false, 'message'=>$e->getMessage()];
    }
}
/**
 * Registration API
 */
function customerRegister($params) {
    try{
        $username = $params->get_param('username');
        $email = $params->get_param('email');
        $password = $params->get_param('password');

        if(empty($username))
            throw new Exception("username is required!");
        if(empty($email))
            throw new Exception("email is required!");
        if(empty($password))
            throw new Exception("password is required!");

        $user_id = username_exists($username); 
        if($user_id || email_exists($email) == true)
            throw new Exception("Username already exists!");
        
        $user_id = wp_create_user($username, $password, $email);
        if(is_wp_error($user_id)) {
            throw new Excpetion("User registration error found!");
        }
        $user = get_user_by('id', $user_id);

        $user->set_role('subscriber');
        // WooCommerce specific code
        if (class_exists('WooCommerce')) {
            $user->set_role('customer');
        }
        return [ 'success'=>true, 'user_id'=>$user_id, 'email'=>$email, 'username'=>$username];

    }catch( Exception $e) {
        return ['success'=> false, 'message'=>$e->getMessage()];
    }
}


/**
 * Login and get user information
 * url: wp-json/wc/v3/login
 */
add_action( 'rest_api_init', function () {
    register_rest_route( 'wc/v3', '/login/',
        array( array( 
            'methods' => 'POST',
            'callback' => 'customerLogin',
        )) 
    );
  } );

/**
 * Register user with username, passwrod and email
 * url: wp-json/wc/v3/register
 */
add_action( 'rest_api_init', function () {
    register_rest_route( 'wc/v3', '/register/',
        array( array( 
            'methods' => 'POST',
            'callback' => 'customerRegister',
        )) 
    );
  } );