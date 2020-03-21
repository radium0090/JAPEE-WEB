<?php 
function enqueue_mobile_menu_script() {
    global $electro_version;
    wp_enqueue_style( 'electro-dokan-style', get_template_directory_uri() . '/lib/css/responsive.css', '', $electro_version );
}


add_action( 'wp_enqueue_scripts','enqueue_mobile_menu_script',11 );

include get_template_directory() . '/lib/registerMenu.php';
/**
 * Query only category part
 */

function electro_display_wocommerce_main_category_list() {
    $blankImage = get_template_directory_uri().'/assets/images/no-image-icon.png';
    $args = array(
            'taxonomy'     => 'product_cat',
            'orderby'      => 'asc',
            'show_count'   => 0,
            'pad_counts'   => 0,
            'hierarchical' => 1,
            'title_li'     => '',
            'hide_empty'   => 1
    );
    $all_categories = get_categories( $args );
    $html = '<div class="main-category-container">';
    $html .= '<ul class="main-category-list">';
    foreach ($all_categories as $cat) {
        if($cat->category_parent == 0) {
            $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true ); 
            $image = wp_get_attachment_url( $thumbnail_id ); 
            $image = $image ? $image : $blankImage;
            $category_id = $cat->term_id;    

            $html .= sprintf( '<li> <a href="%1$s"><img src="%2$s" alt="%3$s"/><span>%3$s</span></a></li>',
                    get_term_link($cat->slug, 'product_cat'),
                    $image,
                    $cat->name
                );
        }       
    }
    $html .='</ul></div>';

    echo $html;
}



add_action( 'electro_product_category_list',  'electro_display_wocommerce_main_category_list',    99 );


include get_template_directory() . '/lib/gallaryPostype.php';
include get_template_directory() . '/lib/galleryAPI.php';
include get_template_directory() . '/lib/ecommerceAPI.php';
include get_template_directory() . '/lib/authAPI.php';
include get_template_directory() . '/lib/socialLoginRest/index.php';
include get_template_directory() . '/lib/shipping/index.php';
include get_template_directory() . '/lib/stripeAPI/stripe-php/init.php';
include get_template_directory() . '/lib/stripeAPI/index.php';

