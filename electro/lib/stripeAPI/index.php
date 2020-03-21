<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// require_once('./stripe-php/init.php');


class StripeAPIController {


    /*
    * Endpoint namespace.
    *
    * @var string
    */
   protected $namespace = 'wc/v3';

   /**
    * Route base.
    *
    * @var string
    */
   protected $rest_base = '';


   protected $API_key = 'sk_test_UXbf1PqXXEPUw4QwcOz91gWt';
   
    public function __construct()
    {
        add_action( 'rest_api_init', array( $this, 'registerRoute' ), 10 );
    }
    
    public function registerRoute() {
        	// Calculate Cart Total - japeecart/v1/calculate (POST)
		register_rest_route( $this->namespace, '/' . $this->rest_base  . '/create_stripe_customer', array(
			'methods'  => "POST",
			'callback' => array( $this, 'create_stripe_customer' ),
			'args'     => array(
				'customer_id' => array(
					'required'    => true,
					'description' => __( 'Unique identifier for the customer.', 'stripe_api_register' ),
					'type'        => 'integer',
				),
			)
        ) );
        register_rest_route( $this->namespace, '/' . $this->rest_base  . '/empheral_key', array(
			'methods'  => "POST",
			'callback' => array( $this, 'empheralkey' ),
			'args'     => array()
        ) );
        register_rest_route( $this->namespace, '/' . $this->rest_base  . '/create_payment_intent', array(
			'methods'  => "POST",
			'callback' => array( $this, 'create_payment_intent' ),
			'args'     => array()
        ) );
        register_rest_route( $this->namespace, '/' . $this->rest_base  . '/delete_stripe_customer', array(
			'methods'  => "GET",
			'callback' => array( $this, 'delete_stripe_customer' ),
			'args'     => array()
        ) );
    }

    /**
     * Register or retrive stripe customer ID
     */
    public function create_stripe_customer($request) {
        try {
            if (!isset($request['customer_id'])) {
				throw new Exception("Customer ID required for get the payment getway!");
            }
            $customer_id = $request['customer_id'];
            
            $user = new WC_Customer( $customer_id );
            $email = $user->get_email();

            if (empty($email)) {
                throw new Exception("Customer not found with this customer ID!");
            }

            \Stripe\Stripe::setApiKey($this->API_key);

            $customer = $this->getSavedStripeCustomer($customer_id);
            $has_customer = false;

            if ($customer != null ) {
                $existing_customer = \Stripe\Customer::retrieve($customer->id);                
                if (isset($existing_customer['deleted']) && $existing_customer['deleted'] == true){
                    $has_customer = false;
                    $this->delete_stripe_customer($customer_id);
                } else {
                    $has_customer = true;
                }
            }
            if (!$has_customer){
                $email = $user->get_email();
                $phone = $user->get_billing_phone();
                $fullName = $user->get_first_name() . ' ' . $user->get_last_name();
                $newCustomer = [];
                $newCustomer['email'] = "$email";
                $newCustomer['name'] = "$fullName";
                if (!empty($phone)) {
                    $newCustomer['phone'] = "$phone";
                }
                $customer = \Stripe\Customer::create($newCustomer);
                $this->saveStripeCustomer($customer_id, $customer);
            }
        
            return ['stripe_customer_id' => $customer->id ] ;

        }catch(Exception $e) {
            return $e->getMessage();
        }
    }

    private function getSavedStripeCustomer($customer_id) {
        $saved_cart_meta = get_user_meta( $customer_id, '_stripe_customer_data', true );
		if ( isset( $saved_cart_meta['stripe'] ) ) {
			return $saved_cart_meta['stripe'];
        }
        return null;
    }

    private function saveStripeCustomer($customer_id, $customer){
		update_user_meta( $customer_id, '_stripe_customer_data', array( 'stripe' => $customer) );
    }

    private function deleteStripeCustomer($customer_id){
		delete_user_meta( $customer_id, '_stripe_customer_data');
    }

    /**
     * Empharel request;
     */
    public function empheralkey($request) {
        try{
            
            if (!isset($request['api_version'])) {
				throw new Exception("API Version must be required!");
            }

            \Stripe\Stripe::setApiKey($this->API_key);

            $key = \Stripe\EphemeralKey::create(
                ["stripe_version" => $request['api_version']]
            );
            return $key;
        }catch(Exception $e) {
            return $e->getMessage();
        }
    }

    private function checkStripeCustomer($customer_id){
        $customer = \Stripe\Customer::retrieve($customer_id);
    }

    /**
     * Create payment intent
     */
    public function create_payment_intent($request) {
        try {

            if (!isset($request['amount'])) {
				throw new Exception("Amount must be required!");
            }

            if (!isset($request['currency'])) {
				throw new Exception("Currency must be required!");
            }

            if (!isset($request['customer_id'])) {
				throw new Exception("Stripe Customer Id must be required!");
            }

            \Stripe\Stripe::setApiKey($this->API_key);
            
            $intent = \Stripe\PaymentIntent::create([
                'amount' => $request['amount'],
                'currency' => $request['currency'],
                'customer' => $request['customer_id']
            ]);
            return $intent;
            if (!$intent) {
                return 'invlid';
            }
            return $intent;

            return ['client_secret'=> $intent->client_secret];

        }catch(Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Delte stripe customer from meta data
     */
    public function delete_stripe_customer($request) {
        try{
            if (!isset($request['customer_id'])) {
				throw new Exception("Customer ID required for get the payment getway!");
            }

            $this->deleteStripeCustomer($request['customer_id']);

            return ['success'=>true];
        }catch(Exception $e) {
            return $e->getMessage();
        }
    }
    
}

new StripeAPIController();