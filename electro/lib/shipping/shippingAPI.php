<?php 
// use \WC_Shipping_Method;
use WbsVendors\Dgm\Shengine\Woocommerce\Converters\PackageConverter;
use WbsVendors\Dgm\Shengine\Woocommerce\Converters\RateConverter;
use WbsVendors\Dgm\WcTools\WcTools;
use Wbs\RulesMapper;
use WbsVendors\Dgm\Shengine\Interfaces\IProcessor;
use WbsVendors\Dgm\Shengine\Processing\Processor;
use WbsVendors\Dgm\Shengine\Units;

/**
 * japeecart REST API controller
 *
 * Handles requests to the cart endpoint.
 *
 * @author   Sébastien Dumont
 * @category API
 * @package  japeecart/API
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * japeecart REST API controller class.
 *
 * @package japeecart REST API/API
 */
class Shipping_API_Controller {

	/**
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


	public function __construct()
    {
		$this->instance_id = "wbs:5:";
		$this->title =  "Weight Based Shipping";
		$this->optionKey = "wbs_5_config";
		// $this->instance_id = "wbs:";
		// $this->title =  "Weight Based Shipping";
		// $this->optionKey = "wbs_config";
	}

	/**
	 * Register the routes for cart.
	 *
	 * @access public
	 */
	public function register_routes() {
		// Add Item - japeecart/v1/add-item (POST)
		register_rest_route( $this->namespace, '/' . $this->rest_base . '/add-item', array(
			'methods'  => WP_REST_Server::CREATABLE,
			'callback' => array( $this, 'add_to_cart' ),
			'args'     => array(
				'product_id' => array(
					'description'       => __( 'Unique identifier for the product ID.', 'cart-rest-api-for-woocommerce' ),
					'type'              => 'integer',
					'validate_callback' => function( $param, $request, $key ) {
						return is_numeric( $param );
					}
				),
				'quantity' => array(
					'description'       => __( 'The quantity amount of the item to add to cart.', 'cart-rest-api-for-woocommerce' ),
					'default'           => 1,
					'type'              => 'integer',
					'validate_callback' => function( $param, $request, $key ) {
						return is_numeric( $param );
					}
				),
				'variation_id' => array(
					'description'       => __( 'Unique identifier for the variation ID.', 'cart-rest-api-for-woocommerce' ),
					'type'              => 'integer',
					'validate_callback' => function( $param, $request, $key ) {
						return is_numeric( $param );
					}
				),
				'variation' => array(
					'validate_callback' => function( $param, $request, $key ) {
						return is_array( $param );
					}
				),
				'cart_item_data' => array(
					'validate_callback' => function( $param, $request, $key ) {
						return is_array( $param );
					}
				),
				'refresh_totals' => array(
					'description' => __( 'Re-calculates the totals once item has been added or the quantity of the item has increased.', 'cart-rest-api-for-woocommerce' ),
					'default'     => false,
					'type'        => 'boolean',
				)
			)
		) );

		// Calculate Cart Total - japeecart/v1/calculate (POST)
		register_rest_route( $this->namespace, '/' . $this->rest_base  . '/calculate', array(
			'methods'  => WP_REST_Server::CREATABLE,
			'callback' => array( $this, 'calculate_totals' ),
			'args'     => array(
				'return' => array(
					'default'     => false,
					'description' => __( 'Returns the cart totals once calculated.', 'cart-rest-api-for-woocommerce' ),
					'type'        => 'boolean',
				)
			)
		) );

		// Clear Cart - japeecart/v1/clear-cart (POST)
		register_rest_route( $this->namespace, '/' . $this->rest_base  . '/clear-cart', array(
			'methods'  => WP_REST_Server::CREATABLE,
			'callback' => array( $this, 'clear_cart' ),
			'args' => array()
		) );

		// Count Items in Cart - japeecart/v1/count-items (GET)
		register_rest_route( $this->namespace, '/' . $this->rest_base  . '/count-items', array(
			'methods'  => WP_REST_Server::READABLE,
			'callback' => array( $this, 'get_cart_contents_count' ),
			'args'     => array(
				'return' => array(
					'default' => 'numeric'
				),
			),
		) );


		// Get Cart - japeecart/v1/get-cart (GET)
		register_rest_route( $this->namespace, '/' . $this->rest_base . '/get-cart', array(
			'methods'  => WP_REST_Server::READABLE,
			'callback' => array( $this, 'get_cart' ),
			'args'     => array(
				'thumb' => array(
					'description' => __( 'Returns the URL of the product image thumbnail.', 'cart-rest-api-for-woocommerce' ),
					'default'     => false,
					'type'        => 'boolean',
				),
			),
		) );
 
		// Get Cart of a Customer - japeecart/v1/get-cart/1 (GET)
		register_rest_route( $this->namespace, '/' . $this->rest_base . '/get-cart_by/(?P<customer_id>[\d]+)', array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => array( $this, 'get_cart_customer' ),
			// 'permission_callback' => array( $this, 'get_permission_check' ),
			'args'                => array(
				'customer_id' => array(
					'required'    => true,
					'description' => __( 'Unique identifier for the customer.', 'cart-rest-api-for-woocommerce' ),
					'type'        => 'integer',
				),
				'thumb' => array(
					'description' => __( 'Returns the URL of the product image thumbnail.', 'cart-rest-api-for-woocommerce' ),
					'default'     => false,
					'type'        => 'boolean',
				),
			),
		) );

		// Update, Remove or Restore Item - japeecart/v1/item (GET, POST, DELETE)
		register_rest_route( $this->namespace, '/' . $this->rest_base . '/item', array(
			'args' => array(
				'cart_item_key' => array(
					'description' => __( 'Unique identifier for the item in the cart.', 'cart-rest-api-for-woocommerce' ),
					'type'        => 'string',
				),
				'refresh_totals' => array(
					'description' => __( 'Re-calculates the totals once item has been updated.', 'cart-rest-api-for-woocommerce' ),
					'default'     => false,
					'type'        => 'boolean',
				),
				'return_cart' => array(
					'description' => __( 'Returns the whole cart to reduce requests.', 'cart-rest-api-for-woocommerce' ),
					'default'     => false,
					'type'        => 'boolean',
				)
			),
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'restore_item' ),
			),
			array(
				'methods'  => WP_REST_Server::CREATABLE,
				'callback' => array( $this, 'update_item' ),
				'args'     => array(
					'quantity' => array(
						'default'           => 1,
						'type'              => 'integer',
						'validate_callback' => function( $param, $request, $key ) {
							return is_numeric( $param );
						}
					),
				),
			),
			array(
				'methods'  => WP_REST_Server::DELETABLE,
				'callback' => array( $this, 'remove_item' ),
			),
		) );

		// Get Cart Totals - japeecart/v1/totals (GET)
		register_rest_route( $this->namespace, '/' . $this->rest_base  . '/totals', array(
			'methods'  => WP_REST_Server::READABLE,
			'callback' => array( $this, 'get_totals' ),
			'args'     => array(
				'html' => array(
					'description' => __( 'Returns the totals pre-formatted.', 'cart-rest-api-for-woocommerce' ),
					'default' => false,
					'type'    => 'boolean',
				),
			),
        ) );

        register_rest_route( $this->namespace, '/' . $this->rest_base  . '/get-shipping', array(
			'methods'  => WP_REST_Server::READABLE,
			'callback' => array( $this, '__get_shipping' ),
			'args'     => array(
				'html' => array(
					'description' => __( 'Returns the shipping methods.', 'cart-rest-api-for-woocommerce' ),
					'default' => false,
					'type'    => 'boolean',
				),
			),
		) );
		
		register_rest_route( $this->namespace, '/' . $this->rest_base  . '/get-user', array(
			'methods'  => WP_REST_Server::READABLE,
			'callback' => array( $this, 'get_user_by_id' ),
			'args'     => array(
				'html' => array(
					'description' => __( 'Returns the shipping methods.', 'cart-rest-api-for-woocommerce' ),
					'default' => false,
					'type'    => 'boolean',
				),
			),
		) );
		
		register_rest_route( $this->namespace, '/' . $this->rest_base  . '/get-payment-getway', array(
			'methods'  => WP_REST_Server::READABLE,
			'callback' => array( $this, 'get_payment_getway' ),
			'args'     => array(
				'customer_id' => array(
					'required'    => true,
					'description' => __( 'Unique identifier for the customer.', 'cart-rest-api-for-woocommerce' ),
					'type'        => 'integer',
				),
			),
		) );
        
	} // register_routes()

	public function get_user_by_id($data) {
		$user = get_user_by( 'id', $data['id'] );
		return $user;
	}

	/**
	 * Check if a given request can read the cart.
	 *
	 * @access public
	 * @since  2.0.0
	 * @return bool|WP_Error
	 */
	public function get_permission_check() {
		if ( ! current_user_can( 'administrator' ) ) {
			return new WP_Error( 'japeecart_cannot_read_cart', __( 'Cannot read cart!', 'cart-rest-api-for-woocommerce' ), array( 'status' => 500 ) );
		}

		return true;
	} // END get_permission_check()

	/**
	 * Get cart.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @version 2.0.0
	 * @param   array  $data
	 * @param   string $cart_item_key
	 * @return  array|WP_REST_Response
	 */
	public function get_cart( $data = array(), $cart_item_key = '' ) {
		$cart_contents = $this->get_cart_contents( $data, $cart_item_key );

		do_action( 'japeecart_get_cart', $cart_contents );

		$show_raw = ! empty( $data['raw'] ) ? $data['raw'] : false;

		// Return cart contents raw if requested.
		if ( $show_raw ) {
			return $cart_contents;
		}

		return new WP_REST_Response( $cart_contents, 200 );
	} // END get_cart()

	/**
	 * Get cart for a specific customer.
	 *
	 * @access public
	 * @since  2.0.0
	 * @param  array  $data
	 * @param  string $cart_item_key
	 * @return array|WP_Error
	 */
	public function get_cart_customer( $data = array(), $cart_item_key = '' ) {
		if ( empty( $data['customer_id'] ) ) {
			return new WP_Error( 'japeecart_customer_missing', __( 'Customer ID is required!', 'cart-rest-api-for-woocommerce' ), array( 'status' => 500 ) );
		}
		
		$user = get_user_by( 'id', $data['customer_id'] );

		$saved_cart = $this->get_saved_cart( $data['customer_id'] );
		
		// If a saved cart exists then replace the carts content.
		// if ( ! empty( $saved_cart ) ) {
		// 	return $saved_cart;
		// } else {
		// 	return $saved_cart;
		// }

		return $saved_cart;

		// return $this->get_cart_contents( $data, $cart_item_key );
	} // END get_cart_customer()

	/**
	 * Gets the cart contents.
	 *
	 * @access public
	 * @since  2.0.0
	 * @param  array  $data
	 * @param  string $cart_item_key
	 * @return array  $cart_contents
	 */
	public function get_cart_contents( $data = array(), $cart_item_key = '' ) {
		$cart_contents = isset( WC()->cart ) ? WC()->cart->get_cart() : WC()->session->cart;

		return $this->return_cart_contents( $cart_contents, $data, $cart_item_key );
	} // END get_cart_contents()

	/**
	 * Return cart contents.
	 *
	 * @access public
	 * @since  2.0.0
	 * @param  array  $cart_contents
	 * @param  array  $data
	 * @param  string $cart_item_key
	 * @return array  $cart_contents
	 */
	public function return_cart_contents( $cart_contents, $data = array(), $cart_item_key = '' ) {
		if ( $this->get_cart_contents_count( array( 'return' => 'numeric' ) ) <= 0 || empty( $cart_contents ) ) {
			return array();
		}

		$show_thumb = ! empty( $data['thumb'] ) ? $data['thumb'] : false;

		// Find the cart item key in the existing cart.
		if ( ! empty( $cart_item_key ) ) {
			$cart_item_key = $this->find_product_in_cart( $cart_item_key );

			return $cart_contents[$cart_item_key];
		}

		foreach ( $cart_contents as $item_key => $cart_item ) {
			// If product data is missing then get product data and apply.
			if ( ! isset( $cart_item['data'] ) ) {
				$cart_item['data'] = wc_get_product( $cart_item['variation_id'] ? $cart_item['variation_id'] : $cart_item['product_id'] );
				$cart_contents[$item_key]['data'] = $cart_item['data'];
			}

			$_product = apply_filters( 'japeecart_item_product', $cart_item['data'], $cart_item, $item_key );

			// Adds the product name and title as new variables.
			$cart_contents[$item_key]['product_name']  = $_product->get_name();
			$cart_contents[$item_key]['product_title'] = $_product->get_title();

			// Add product price as a new variable.
			$cart_contents[$item_key]['product_price'] = html_entity_decode( strip_tags( wc_price( $_product->get_price() ) ) );

			// If main product thumbnail is requested then add it to each item in cart.
			if ( $show_thumb ) {
				$thumbnail_id = apply_filters( 'japeecart_item_thumbnail', $_product->get_image_id(), $cart_item, $item_key );

				$thumbnail_src = wp_get_attachment_image_src( $thumbnail_id, apply_filters( 'japeecart_item_thumbnail_size', 'woocommerce_thumbnail' ) );

				// Add main product image as a new variable.
				$cart_contents[$item_key]['product_image'] = esc_url( $thumbnail_src[0] );
			}

			// This filter allows additional data to be returned for a specific item in cart.
			$cart_contents = apply_filters( 'japeecart_cart_contents', $cart_contents, $item_key, $cart_item, $_product );
		}

		// The cart contents is returned and can be filtered.
		return apply_filters( 'japeecart_return_cart_contents', $cart_contents );
	} // END return_cart_contents()

	/**
	 * Get cart contents count.
	 *
	 * @access  public
	 * @static
	 * @since   1.0.0
	 * @version 2.0.1
	 * @param   array $data
	 * @return  string|WP_REST_Response
	 */
	public static function get_cart_contents_count( $data = array() ) {
		$count = WC()->cart->get_cart_contents_count();

		$return = ! empty( $data['return'] ) ? $data['return'] : '';

		if ( $return != 'numeric' && $count <= 0 ) {
			return new WP_REST_Response( __( 'There are no items in the cart!', 'cart-rest-api-for-woocommerce' ), 200 );
		}

		return $count;
	} // END get_cart_contents_count()

	/**
	 * Returns a customers saved cart from the database if one exists.
	 *
	 * @access public
	 * @since  2.0.0
	 * @param  array $data       The customer ID is a required variable.
	 * @return array $saved_cart Returns the cart content from the database.
	 */
	public function get_saved_cart( $customer_id ) {
		$saved_cart = array();

		$customer_id = ! empty( $customer_id ) ? $customer_id : 0;

		$saved_cart_meta = get_user_meta( $customer_id, '_woocommerce_persistent_cart_' . get_current_blog_id(), true );
		if ( isset( $saved_cart_meta['cart'] ) ) {
			$saved_cart = array_filter( (array) $saved_cart_meta['cart'] );
		}

		return $saved_cart;
	} // END get_saved_cart()

	public function update_persistent_cart($user_id, $cart_item) {
		$cart_contents = $this->get_saved_cart($user_id);
		$cart_contents[$cart_item['key']] = $cart_item;
		$this->saveCustomerCart($user_id, $cart_contents);
	}

	private function saveCustomerCart($user_id, $cart_contents){
		update_user_meta( $user_id, '_woocommerce_persistent_cart_'.get_current_blog_id(), array( 'cart' => $cart_contents) );
	}

	private function clear_persistent_cart($user_id){
		update_user_meta( $user_id, '_woocommerce_persistent_cart_'.get_current_blog_id(), array( 'cart' => null ) );	
	}

	/**
	 * Clear cart.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @version 2.0.0
	 * @return  WP_Error|WP_REST_Response
	 */
	public function clear_cart($request) {
		$data = $request->get_params();
		if (!isset($data['customer_id'])) {
			return new WP_Error( 'japeecart_clear_cart_failed', __( 'Customer Id required!', 'cart-rest-api-for-woocommerce' ), array( 'status' => 500 ) );
			
		}
		$cart_contents = $this->get_saved_cart($data['customer_id']);

		WC()->cart->empty_cart();
		WC()->session->set('cart', array()); // Empty the session cart data

		if (isset($data['customer_id'])) {
			$this->clear_persistent_cart($data['customer_id']);
		}

		if ( WC()->cart->is_empty() ) {
			do_action( 'japeecart_cart_cleared' );

			return new WP_REST_Response( $cart_contents, 200 );
		} else {
			return new WP_Error( 'japeecart_clear_cart_failed', __( 'Clearing the cart failed!', 'cart-rest-api-for-woocommerce' ), array( 'status' => 500 ) );
		}
	} // END clear_cart()

	private function _clear_cart($data) {

		WC()->cart->empty_cart();
		WC()->session->set('cart', array()); // Empty the session cart data

		if (isset($data['customer_id'])) {
			$this->clear_persistent_cart($data['customer_id']);
		}

		if ( WC()->cart->is_empty() ) {
			return true;
		}
		return false;
	}

	/**
	 * Validate the product id argument.
	 *
	 * @access protected
	 * @since  1.0.0
	 * @param  int $product_id
	 * @return WP_Error
	 */
	protected function validate_product_id( $product_id ) {
		if ( $product_id <= 0 ) {
			return new WP_Error( 'japeecart_product_id_required', __( 'Product ID number is required!', 'cart-rest-api-for-woocommerce' ), array( 'status' => 500 ) );
		}

		if ( ! is_numeric( $product_id ) ) {
			return new WP_Error( 'japeecart_product_id_not_numeric', __( 'Product ID must be numeric!', 'cart-rest-api-for-woocommerce' ), array( 'status' => 500 ) );
		}
	} // END validate_product_id()

	/**
	 * Validate the product quantity argument.
	 *
	 * @access protected
	 * @since  1.0.0
	 * @param  int $quantity
	 * @return WP_Error
	 */
	protected function validate_quantity( $quantity ) {
		if ( ! is_numeric( $quantity ) ) {
			return new WP_Error( 'japeecart_quantity_not_numeric', __( 'Quantity must be numeric!', 'cart-rest-api-for-woocommerce' ), array( 'status' => 500 ) );
		}
	} // END validate_quantity()

	/**
	 * Validate product before it is added to the cart, updated or removed.
	 *
	 * @access protected
	 * @since  1.0.0
	 * @param  int $product_id
	 * @param  int $quantity
	 * @return WP_Error
	 */
	protected function validate_product( $product_id = null, $quantity = 1 ) {
		$this->validate_product_id( $product_id );
		$this->validate_quantity( $quantity );
	} // END validate_product()

	/**
	 * Check if product is in the cart and return cart item key if found.
	 *
	 * Cart item key will be unique based on the item and its properties, such as variations.
	 *
	 * @access public
	 * @since  2.0.0
	 * @param  string $cart_item_key of product to find in the cart.
	 * @return string Returns the same cart item key if valid.
	 */
	public function find_product_in_cart( $cart_item_key = '', $customer_id ) {
		if ( ! empty( $cart_item_key ) ) {
			if ( is_array( self::get_cart() ) && null !== self::get_cart( array(), $cart_item_key ) ) {
				return $cart_item_key;
			}
		}

		// $cartItems = $this->get_saved_cart($customer_id);

		// if (! empty( $cart_item_key ) && count($cartItems) && isset($cartItems[$cart_item_key])) {
		// 	return $cart_item_key;
		// }	
		return '';
	} // END find_product_in_cart()

	/**
	 * Checks if the product in the cart has enough stock 
	 * before updating the quantity.
	 * 
	 * @access  protected
	 * @since   1.0.6
	 * @version 2.0.0
	 * @param   array   $current_data
	 * @param   integer $quantity
	 * @return  bool|WP_Error
	 */
	protected function has_enough_stock( $current_data = array(), $quantity = 1 ) {
		$product_id      = ! isset( $current_data['product_id'] ) ? 0 : absint( $current_data['product_id'] );
		$variation_id    = ! isset( $current_data['variation_id'] ) ? 0 : absint( $current_data['variation_id'] );
		$current_product = wc_get_product( $variation_id ? $variation_id : $product_id );

		if ( ! $current_product->has_enough_stock( $quantity ) ) {
			/* translators: 1: quantity requested, 2: product name 3: quantity in stock */
			return new WP_Error( 'japeecart_not_enough_in_stock', sprintf( __( 'You cannot add a quantity of %1$s for "%2$s" to the cart because there is not enough stock. - only %3$s remaining!', 'cart-rest-api-for-woocommerce' ), $quantity, $current_product->get_name(), wc_format_stock_quantity_for_display( $current_product->get_stock_quantity(), $current_product ) ), array( 'status' => 500 ) );
		}

		return true;
	} // END has_enough_stock()

	private function load_cart($customer_id){
		$cartItems = $this->get_saved_cart($customer_id);
		if (count($cartItems)) {
			WC()->cart->cart_contents = $cartItems;
			WC()->session->cart = $cartItems;
		}
	}

	/**
	 * Add to Cart.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @version 2.0.0
	 * @param   array $data
	 * @return  WP_Error|WP_REST_Response
	 */
	public function add_to_cart( $data = array() ) {
		$customer_id 	= ! isset( $data['customer_id'] ) ? 0 : absint( $data['customer_id'] );
		$product_id     = ! isset( $data['product_id'] ) ? 0 : absint( $data['product_id'] );
		$quantity       = ! isset( $data['quantity'] ) ? 1 : absint( $data['quantity'] );
		$variation_id   = ! isset( $data['variation_id'] ) ? 0 : absint( $data['variation_id'] );
		$variation      = ! isset( $data['variation'] ) ? array() : $data['variation'];
		$cart_item_data = ! isset( $data['cart_item_data'] ) ? array() : $data['cart_item_data'];

		$item_added = array();
		
		$this->load_cart($customer_id);

		$this->validate_product( $product_id, $quantity );

		// Ensure we don't add a variation to the cart directly by variation ID.
		if ( 'product_variation' === get_post_type( $product_id ) ) {
			$variation_id = $product_id;
			$product_id   = wp_get_post_parent_id( $variation_id );
		}

		$product_data = wc_get_product( $variation_id ? $variation_id : $product_id );

		if ( $quantity <= 0 || ! $product_data || 'trash' === $product_data->get_status() ) {
			return new WP_Error( 'japeecart_product_does_not_exist', __( 'Warning: This product does not exist!', 'cart-rest-api-for-woocommerce' ), array( 'status' => 500 ) );
		}

		// Generate a ID based on product ID, variation ID, variation data, and other cart item data.
		$cart_id = WC()->cart->generate_cart_id( $product_id, $variation_id, $variation, $cart_item_data );

		// Find the cart item key in the existing cart.
		$cart_item_key = $this->find_product_in_cart( $cart_id, $customer_id );

		// Force quantity to 1 if sold individually and check for existing item in cart.
		if ( $product_data->is_sold_individually() ) {
			$quantity = 1;

			$cart_contents = $this->get_cart();

			$found_in_cart = apply_filters( 'japeecart_add_to_cart_sold_individually_found_in_cart', $cart_item_key && $cart_contents[ $cart_item_key ]['quantity'] > 0, $product_id, $variation_id, $cart_item_data, $cart_id );

			if ( $found_in_cart ) {
				/* translators: %s: product name */
				return new WP_Error( 'japeecart_product_sold_individually', sprintf( __( 'You cannot add another "%s" to your cart.', 'cart-rest-api-for-woocommerce' ), $product_data->get_name() ), array( 'status' => 500 ) );
			}
		}

		// Product is purchasable check.
		if ( ! $product_data->is_purchasable() ) {
			return new WP_Error( 'japeecart_cannot_be_purchased', __( 'Sorry, this product cannot be purchased.', 'cart-rest-api-for-woocommerce' ), array( 'status' => 500 ) );
		}

		// Stock check - only check if we're managing stock and backorders are not allowed.
		if ( ! $product_data->is_in_stock() ) {
			/* translators: %s: product name */
			return new WP_Error( 'japeecart_product_out_of_stock', sprintf( __( 'You cannot add "%s" to the cart because the product is out of stock.', 'cart-rest-api-for-woocommerce' ), $product_data->get_name() ), array( 'status' => 500 ) );
		}

		if ( ! $product_data->has_enough_stock( $quantity ) ) {
			/* translators: 1: quantity requested, 2: product name, 3: quantity in stock */
			return new WP_Error( 'japeecart_not_enough_in_stock', sprintf( __( 'You cannot add a quantity of %1$s for "%2$s" to the cart because there is not enough stock. - only %3$s remaining!', 'cart-rest-api-for-woocommerce' ), $quantity, $product_data->get_name(), wc_format_stock_quantity_for_display( $product_data->get_stock_quantity(), $product_data ) ), array( 'status' => 500 ) );
		}

		// Stock check - this time accounting for whats already in-cart.
		if ( $product_data->managing_stock() ) {
			$products_qty_in_cart = WC()->cart->get_cart_item_quantities();

			if ( isset( $products_qty_in_cart[ $product_data->get_stock_managed_by_id() ] ) && ! $product_data->has_enough_stock( $products_qty_in_cart[ $product_data->get_stock_managed_by_id() ] + $quantity ) ) {
				/* translators: 1: quantity in stock, 2: quantity in cart */
				return new WP_Error(
					'japeecart_not_enough_stock_remaining',
					sprintf(
						__( 'You cannot add that amount to the cart &mdash; we have %1$s in stock and you already have %2$s in your cart.', 'cart-rest-api-for-woocommerce' ),
						wc_format_stock_quantity_for_display( $product_data->get_stock_quantity(), $product_data ),
						wc_format_stock_quantity_for_display( $products_qty_in_cart[ $product_data->get_stock_managed_by_id() ], $product_data )
					),
					array( 'status' => 500 )
				);
			}
		}

		$response  = apply_filters( 'japeecart_ok_to_add_response', '', $product_data );
		$ok_to_add = apply_filters( 'japeecart_ok_to_add', true, $product_data );

		// If it is not OK to add the item, return an error response.
		if ( ! $ok_to_add ) {
			$error_msg = empty( $response ) ? __( 'This item can not be added to the cart.', 'cart-rest-api-for-woocommerce' ) : $response;

			return new WP_Error(
				'japeecart_not_ok_to_add_item', 
				$error_msg, 
				array( 'status' => 500 )
			);
		}

		// If cart_item_key is set, then the item is already in the cart so just update the quantity.
		if ( $cart_item_key ) {
			$cart_contents = $this->get_cart( array( 'raw' => true ) );

			$new_quantity  = $quantity + $cart_contents[ $cart_item_key ]['quantity'];

			WC()->cart->set_quantity( $cart_item_key, $new_quantity, $data['refresh_totals'] );

			$item_added = WC()->cart->get_cart_item( $cart_item_key );
		} else {
			// Add item to cart.
			$item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $cart_item_data );

			// Return response to added item to cart or return error.
			if ( $item_key ) {
				// Re-calculate cart totals once item has been added.
				if ( $data['refresh_totals'] ) {
					WC()->cart->calculate_totals();
				}

				$item_added = WC()->cart->get_cart_item( $item_key );

				do_action( 'japeecart_item_added_to_cart', $item_key, $item_added );
			} else {
				/* translators: %s: product name */
				return new WP_Error( 'japeecart_cannot_add_to_cart', sprintf( __( 'You cannot add "%s" to your cart.', 'cart-rest-api-for-woocommerce' ), $product_data->get_name() ), array( 'status' => 500 ) );
			}
		}

		WC()->cart->empty_cart();
		WC()->session->set('cart', array()); // Empty the session cart data
		$this->update_persistent_cart($customer_id, $item_added);

		return new WP_REST_Response( $item_added, 200 );
	} // END add_to_cart()

	/**
	 * Remove Item in Cart.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @version 2.0.0
	 * @param   array $data
	 * @return  WP_Error|WP_REST_Response
	 */
	public function remove_item( $data = array() ) {
		$cart_item_key = ! isset( $data['cart_item_key'] ) ? '0' : wc_clean( $data['cart_item_key'] );
		$this->load_cart($data['customer_id']);
		// Checks to see if the cart is empty before attempting to remove item.
		if ( WC()->cart->is_empty() ) {
			return new WP_Error( 'japeecart_no_items', __( 'No items in cart.', 'cart-rest-api-for-woocommerce' ), array( 'status' => 500 ) );
		}

		if ( $cart_item_key != '0' ) {
			// Check item exists in cart before fetching the cart item data to update.
			$current_data = WC()->cart->get_cart_item( $cart_item_key );

			// If item does not exist in cart return response.
			if ( empty( $current_data ) ) {
				return new WP_Error( 'japeecart_item_not_in_cart', __( 'Item specified does not exist in cart.', 'cart-rest-api-for-woocommerce' ), array( 'status' => 404 ) );
			}

			if ( WC()->cart->remove_cart_item( $cart_item_key ) ) {
				do_action( 'japeecart_item_removed', $current_data );

				// Was it requested to return the whole cart once item removed?
				if ( $data['return_cart'] ) {
					$cart_contents = $this->get_cart_contents( $data );

					return new WP_REST_Response( $cart_contents, 200 );
				}

				$cart_content = WC()->cart->get_cart();

				$this->saveCustomerCart($data['customer_id'], $cart_content);

				return new WP_REST_Response( __( 'Item has been removed from cart.', 'cart-rest-api-for-woocommerce' ), 200 );
			} else {
				return new WP_Error( 'japeecart_can_not_remove_item', __( 'Unable to remove item from cart.', 'cart-rest-api-for-woocommerce' ), array( 'status' => 500 ) );
			}
		} else {
			return new WP_Error( 'japeecart_cart_item_key_required', __( 'Cart item key is required!', 'cart-rest-api-for-woocommerce' ), array( 'status' => 500 ) );
		}
	} // END remove_item()

	/**
	 * Restore Item in Cart.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @version 2.0.0
	 * @param   array $data
	 * @return  WP_Error|WP_REST_Response
	 */
	public function restore_item( $data = array() ) {
		$cart_item_key = ! isset( $data['cart_item_key'] ) ? '0' : wc_clean( $data['cart_item_key'] );

		if ( $cart_item_key != '0' ) {
			if ( WC()->cart->restore_cart_item( $cart_item_key ) ) {
				$current_data = WC()->cart->get_cart_item( $cart_item_key ); // Fetches the cart item data once it is restored.

				do_action( 'japeecart_item_restored', $current_data );

				// Was it requested to return the whole cart once item restored?
				if ( $data['return_cart'] ) {
					$cart_contents = $this->get_cart_contents( $data );

					return new WP_REST_Response( $cart_contents, 200 );
				}

				return new WP_REST_Response( __( 'Item has been restored to the cart.', 'cart-rest-api-for-woocommerce' ), 200 );
			} else {
				return new WP_Error( 'japeecart_can_not_restore_item', __( 'Unable to restore item to the cart.', 'cart-rest-api-for-woocommerce' ), array( 'status' => 500 ) );
			}
		} else {
			return new WP_Error( 'japeecart_cart_item_key_required', __( 'Cart item key is required!', 'cart-rest-api-for-woocommerce' ), array( 'status' => 500 ) );
		}
	} // END restore_item()

	/**
	 * Update Item in Cart.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @version 2.0.1
	 * @param   array $data
	 * @return  WP_Error|WP_REST_Response
	 */
	public function update_item( $data = array() ) {
		$cart_item_key = ! isset( $data['cart_item_key'] ) ? '0' : wc_clean( $data['cart_item_key'] );
		$quantity      = ! isset( $data['quantity'] ) ? 1 : absint( $data['quantity'] );
		$customer_id      = ! isset( $data['customer_id'] ) ? 1 : absint( $data['customer_id'] );
		 
		$this->load_cart($customer_id);
		 
		// Allows removing of items if quantity is zero should for example the item was with a product bundle.
		if ( $quantity === 0 ) {
			return $this->remove_item( $data );
		}

		$this->validate_quantity( $quantity );

		if ( $cart_item_key != '0' ) {
			// Check item exists in cart before fetching the cart item data to update.
			$current_data = WC()->cart->get_cart_item( $cart_item_key );
			
			// If item does not exist in cart return response.
			if ( empty( $current_data ) ) {
				return new WP_Error( 'japeecart_item_not_in_cart', __( 'Item specified does not exist in cart.', 'cart-rest-api-for-woocommerce' ), array( 'status' => 404 ) );
			}

			$this->has_enough_stock( $current_data, $quantity ); // Checks if the item has enough stock before updating.

			if ( WC()->cart->set_quantity( $cart_item_key, $quantity, $data['refresh_totals'] ) ) {
				$new_data = WC()->cart->get_cart_item( $cart_item_key );

				$product_id   = ! isset( $new_data['product_id'] ) ? 0 : absint( $new_data['product_id'] );
				$variation_id = ! isset( $new_data['variation_id'] ) ? 0 : absint( $new_data['variation_id'] );

				$product_data = wc_get_product( $variation_id ? $variation_id : $product_id );

				if ( $quantity != $new_data['quantity'] ) {
					do_action( 'japeecart_item_quantity_changed', $cart_item_key, $new_data );
				}

				// Was it requested to return the whole cart once item updated?
				if ( $data['return_cart'] ) {
					$cart_contents = $this->get_cart_contents( $data );

					return new WP_REST_Response( $cart_contents, 200 );
				}

				$response = array();

				// Return response based on product quantity increment.
				if ( $quantity > $current_data['quantity'] ) {
					/* translators: 1: product name, 2: new quantity */
					$response = array(
						'message'  => sprintf( __( 'The quantity for "%1$s" has increased to "%2$s".', 'cart-rest-api-for-woocommerce' ), $product_data->get_name(), $new_data['quantity'] ),
						'quantity' => $new_data['quantity']
					);
				} else if ( $quantity < $current_data['quantity'] ) {
					/* translators: 1: product name, 2: new quantity */
					$response = array(
						'message'  => sprintf( __( 'The quantity for "%1$s" has decreased to "%2$s".', 'cart-rest-api-for-woocommerce' ), $product_data->get_name(), $new_data['quantity'] ),
						'quantity' => $new_data['quantity']
					);
				} else {
					/* translators: %s: product name */
					$response = array(
						'message'  => sprintf( __( 'The quantity for "%s" has not changed.', 'cart-rest-api-for-woocommerce' ), $product_data->get_name() ),
						'quantity' => $quantity
					);
				}

				WC()->cart->empty_cart();
				WC()->session->set('cart', array()); // Empty the session cart data
				$this->update_persistent_cart($customer_id, $new_data);

				return new WP_REST_Response( apply_filters( 'japeecart_update_item', $response, $new_data, $quantity ), 200 );
			} else {
				return new WP_Error( 'japeecart_can_not_update_item', __( 'Unable to update item quantity in cart.', 'cart-rest-api-for-woocommerce' ), array( 'status' => 500 ) );
			}
		} else {
			return new WP_Error( 'japeecart_cart_item_key_required', __( 'Cart item key is required!', 'cart-rest-api-for-woocommerce' ), array( 'status' => 500 ) );
		}
	} // END update_item()

	/**
	 * Calculate Cart Totals.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @version 2.0.0
	 * @param   array $data
	 * @return  WP_REST_Response
	 */
	public function calculate_totals( $data = array() ) {
		if ( $this->get_cart_contents_count( array( 'return' => 'numeric' ) ) <= 0 ) {
			return new WP_REST_Response( __( 'No items in cart to calculate totals.', 'cart-rest-api-for-woocommerce' ), 200 );
		}

		WC()->cart->calculate_totals();

		// Was it requested to return all totals once calculated?
		if ( isset($data['return']) &&  $data['return']) {
			return $this->get_totals( $data );
		}

		return new WP_REST_Response( __( 'Cart totals have been calculated.', 'cart-rest-api-for-woocommerce' ), 200 );
	} // END calculate_totals()

	/**
	 * Returns all calculated totals.
	 *
	 * @access  public
	 * @since   1.0.0
	 * @version 2.0.1
	 * @param   array $data
	 * @return  WP_REST_Response
	 */
	public function get_totals( $data = array() ) {

		$this->load_cart($data['customer_id']); 

		if ( ! empty( WC()->cart->totals ) ) {
			$totals = WC()->cart->get_totals();
		} else {
			$totals = WC()->session->get( 'cart_totals' );
		}

		$pre_formatted = ! empty( $data['html'] ) ? $data['html'] : false;

		if ( $pre_formatted ) {
			$new_totals = array();

			$ignore_convert = array(
				'shipping_taxes',
				'cart_contents_taxes',
				'fee_taxes'
			);

			foreach( $totals as $type => $sum ) {
				if ( in_array( $type, $ignore_convert ) ) {
					$new_totals[$type] = $sum;
				} else {
					if ( is_string( $sum ) ) {
						$new_totals[$type] = html_entity_decode( strip_tags( wc_price( $sum ) ) );
					}
					else {
						$new_totals[$type] = html_entity_decode( strip_tags( wc_price( strval( $sum ) ) ) );
					}
				}
			}

			$totals = $new_totals;
		}

		return new WP_REST_Response( $totals, 200 );
	} // END get_totals()
	
	public function get_shipping($data) {
		WC()->cart->calculate_totals();
		$packages = WC()->shipping->get_packages();
		if (count($packages) && isset($packages[0]['rates'])) { 
			$rates = $packages[0]['rates'];
			foreach( $rates as $rate) {
				$newRate = [
					'id' => $rate->id,
					'method_id' => $rate->method_id,
					'instance_id' => $rate->instance_id,
					'label' => $rate->label,
					'cost' => $rate->cost,
					'taxes' => $rate->taxes,
				];
				array_push($_rates, $newRate);
			}
		}
        return new WP_REST_Response( $_rates, 200 );
	}

	public function __get_shipping($data) {

		try {
			if (!isset($data['country'])) {
				throw new Exception("Destination country must be requried");
			}

			if (!isset($data['customer_id'])) {
				throw new Exception("Customer id requried");
			}

			$_package = array();

			$_package['contents'] = $this->get_cart_customer(['customer_id'=>$data['customer_id']]);

			$_package['user'] = array("ID"=>$data['customer_id']);

			$_package['destination'] = array( "country" => $data['country'] );
			

			foreach($_package['contents'] as $key=>$product) {
				$_package['contents'][$key]['data'] = new WC_Product_Simple($product['product_id']);
			}
			
			$package = PackageConverter::fromWoocommerceToCore($_package);

			$processor = new Processor();
			$rules = $this->loadRules($processor);
			$rates = $processor->process($rules, $package);
			$_rates = RateConverter::fromCoreToWoocommerce(
				$rates,
				$this->title,
				$this->instance_id
			);

			return $_rates;

		} catch( Exception $e) {
			return  $e->getMessage();
		}
		
	}
	
	
	private function loadRules(IProcessor $processor)
    {
        $config = $this->config();
        $_rules =  array();
        if (isset($config['rules'])) {
            $_rules = $config['rules'];
        }

        $mapper = new RulesMapper(
            Units::fromPrecisions(
                pow(10, wc_get_price_decimals()),
                1000,
                1000
            ),
            $processor
        );

        $rules = $mapper->read($_rules);

        return $rules;
    }

    public function config($config = null)
    {
        $optionKey = $this->optionKey;
     
        if (func_num_args()) {
            $updated = update_option($optionKey, $config);
            if ($updated) {
                WcTools::purgeShippingCache();
            }
        } else {
            $config = get_option($optionKey, null);
            $config['enabled'] = WcTools::yesNo2Bool(isset($config['enabled']) ? $config['enabled'] : true);
        }
        return $config;
	}
	
	/**
	 * Get Payment methods based on product Id
	 */
	public function get_payment_getway($request) {
		try {
			if (!isset($request['customer_id'])) {
				throw new Exception("Customer ID required for get the payment getway!");
			}
			
			$cart_contents = $this->get_saved_cart($request['customer_id']); 
			$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
			if (count($cart_contents)) {
				foreach( $cart_contents as $cart_content) {
					$terms = get_the_terms($cart_content['product_id'], 'product_cat');
					if (count($terms)) {
						foreach( $terms as $term) {
							$wc_selected_payment_getway = get_term_meta( $term->term_id, 'wc_payment_getway', true );
							if( is_array($wc_selected_payment_getway) && count($wc_selected_payment_getway)){
								foreach( $available_gateways as $getway ){
									if( !in_array($getway->id, $wc_selected_payment_getway)){ 
										unset( $available_gateways[$getway->id]);
									}
								}
							}
						}
					}
					
				}
			}
			$selectedGetway = array();
			foreach($available_gateways as $getway){
				if (isset($getway->supports)){
					unset($getway->supports);
				}
				if ( isset($getway->payment_request)) {
					unset($getway->payment_request);
				}
				if (isset($getway->form_fields)) {
					unset($getway->form_fields);
				}
				array_push($selectedGetway, $getway);
			}
			return $selectedGetway;
		}catch(Exception $e) {
			return $e->getMessage();
		}
	}

} // END class