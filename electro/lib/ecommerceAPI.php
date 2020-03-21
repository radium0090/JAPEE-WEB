<?php 
/**
 * Get top selling products from wocommerce
 */
function get_top_selling_products( $params ) {
    try{
        $limit = $params->get_param('limit');
        if (!$limit)
            $limit = 10;
        $data = [];
        global $woocommerce;
        $args = [
            'post_type'=>'product',
            'meta_key'=>'total_sales',
            'orderby'=>'meta_value_num',
            'posts_per_page'=> $limit
        ];
       
        $products = get_posts($args);
        foreach ($products as $p) {
            $product = wc_get_product($p->ID);
            $result['id'] = $p->ID;
            $result['name'] = $product->get_name();
            $image = wp_get_attachment_image_src($product->get_image_id(),'full');
            if (count($image) ) {
                $result['image'] = $image[0];
            }
            $result['price'] = $product->get_price();
            $result['regular_price'] = $product->get_regular_price();
            $result['sale_price'] = $product->get_sale_price();
            $result['total_seles'] = $product->get_total_sales();
            $result['sku'] = $product->get_sku();
            $result['type'] = $product->get_type();
            $result['stock_quantity'] = $product->get_stock_quantity();
            $result['stock_status'] = $product->get_stock_status();
            $result['type'] = $product->get_type();
            $result['status'] = $product->get_status();
            array_push($data, $result);
        }
        return $data;
    }catch(Exception $e) {
        return [];
    }
}

 
function get_product_specification($params) {
   
    try{
        $product_id = $params->get_param('id');
        $specifications = get_post_meta( $product_id, '_specifications', true );
        $specifications_display_attributes = get_post_meta( $product_id, '_specifications_display_attributes', true );
        $data = apply_filters( 'the_content', wp_kses_post( $specifications ) );
        return ['specifications'=>$data ];
    }catch(Exception $e) {
        return [];
    }
}
 
/**
 * Get all top sellting products
 */
add_action( 'rest_api_init', function () {
    register_rest_route( 
        'wc/v3',
		'/top_selling_products/',
        array(
            array( 
            'methods' => 'GET',
            'callback' => 'get_top_selling_products',
            )
        ) 
    );
  } );

  /**
 * Get all top sellting products
 */
add_action( 'rest_api_init', function () {
    register_rest_route( 
        'wc/v3',
		'/product_specification/',
        array(
            array( 
            'methods' => 'GET',
            'callback' => 'get_product_specification',
            )
        ) 
    );
  } );