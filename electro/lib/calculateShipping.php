<?php 
class WC_REST_Cart_Controller {
    public function get_cart_contents_count( $data = array() ) {
        $count = WC()->cart->get_cart_contents_count();
        $return = ! empty( $data['return'] ) ? $data['return'] : '';
        if ( $return != 'numeric' && $count <= 0 ) {
            return new WP_REST_Response( __( 'There are no items in the cart!', 'cart-rest-api-for-woocommerce' ), 200 );
        }
        return $count;
    } 
    
    public function getCart() {
        $cart = WC()->cart->get_cart();
        if ( $this->get_cart_contents_count( array( 'return' => 'numeric' ) ) <= 0 ) {
            return new WP_REST_Response( array(), 200 );
        }
        $show_thumb = ! empty( $data['thumb'] ) ? $data['thumb'] : false;
        foreach ( $cart as $item_key => $cart_item ) {
            $_product = apply_filters( 'wc_cart_rest_api_cart_item_product', $cart_item['data'], $cart_item, $item_key );
            // Adds the product name as a new variable.
            $cart[$item_key]['product_name'] = $_product->get_name();
            // If main product thumbnail is requested then add it to each item in cart.
            if ( $show_thumb ) {
                $thumbnail_id = apply_filters( 'wc_cart_rest_api_cart_item_thumbnail', $_product->get_image_id(), $cart_item, $item_key );
                $thumbnail_src = wp_get_attachment_image_src( $thumbnail_id, 'woocommerce_thumbnail' );
                // Add main product image as a new variable.
                $cart[$item_key]['product_image'] = esc_url( $thumbnail_src[0] );
            }
        }
        return new WP_REST_Response( $cart, 200 );
    }

    /**
	 * Calculate Cart Totals.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return WP_REST_Response
	 */
	public function calculate_totals() {
		if ( $this->get_cart_contents_count( array( 'return' => 'numeric' ) ) <= 0 ) {
			return new WP_REST_Response( __( 'No items in cart to calculate totals.', 'cart-rest-api-for-woocommerce' ), 200 );
		}

		WC()->cart->calculate_totals();

		return new WP_REST_Response( __( 'Cart totals have been calculated.', 'cart-rest-api-for-woocommerce' ), 200 );
	} // END calculate_totals()

	/**
	 * Returns all calculated totals.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return array
	 */
	public function get_totals() {
		$totals = WC()->cart->get_totals();

		return $totals;
	} // END get_totals()

    public function getShippingMethods($_package) {
        $package = PackageConverter::fromWoocommerceToCore($_package);
       
        $processor = new Processor();
        $rules = $this->loadRules($processor);
        $rates = $processor->process($rules, $package);
        $_rates = RateConverter::fromCoreToWoocommerce(
            $rates,
            $this->title,
            join(':', array_filter(array($this->id, @$this->instance_id))).':'
        );
    }
}
