<?php 

/**
 * Get all slider images from mobile-slider post type
 */
function get_all_slider_photos(){

    $args = [
        'post_type'=>'mobile-slider',
        'meta_query' => array(
            array(
             'key' => '_thumbnail_id',
             'compare' => 'EXISTS'
            ),
        )
      ];
      
      // we get an array of posts objects
    $posts = get_posts($args);
    $result = [];
    foreach($posts as $key=>$post){
        $imgUrl = get_the_post_thumbnail_url($post->ID,'full'); 
        if( $imgUrl != null ) {
            $data['title'] = $post->post_title;
            $data['image_url'] = get_the_post_thumbnail_url($post->ID,'full');
            array_push($result, $data);
        }
    }
    return $result;
}

/**
 * Register custom purl for slider photos
 */
add_action( 'rest_api_init', function () {
    register_rest_route( 
        'wc/v3',
		'/slider_photos/',
        array(
            array( 
            'methods' => 'GET',
            'callback' => 'get_all_slider_photos',
            )
        ) 
    );
  } );