<?php 
add_filter( 'woocommerce_available_payment_gateways', 's_japee_unset_gateway_by_category' );

function s_japee_unset_gateway_by_category($available_gateways){
    if ( is_admin() ) return $available_gateways;
    if ( ! is_checkout() ) return $available_gateways;
    foreach ( WC()->cart->get_cart_contents() as $key => $values ) {

        $terms = get_the_terms($values['product_id'], 'product_cat');

        if( is_array($terms) && count($terms)){
            foreach( $terms as $term){
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
    
    return $available_gateways;
}