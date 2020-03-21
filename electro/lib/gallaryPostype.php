<?php 
// Register Mobiel slider Custom Post Type
function mobile_slider_posttype_init() {
    $args = array(
      'label' => 'Mobile Slider',
        'public' => false,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'query_var' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-video-alt',
        'supports' => array(
            'title',
            'excerpt',
            'trackbacks',
            'custom-fields',
            'comments',
            'revisions',
            'thumbnail',
            'author',
            'page-attributes',)
        );
    register_post_type( 'mobile-slider', $args );
}
add_action( 'init', 'mobile_slider_posttype_init' );