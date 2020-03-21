<?php
/**
 * The template for displaying the woocomerce main category with images.
 * Specially for mobile devices
 *
 * Template name: Product Category Page
 *
 * @package electro
 */

remove_action( 'electro_content_top', 'electro_breadcrumb', 10 );

do_action( 'electro_before_homepage_v7' );

$home_v7 		= electro_get_home_v7_meta();
$header_style 	= isset( $home_v7['header_style'] ) ? $home_v7['header_style'] : 'v8';

electro_get_header( $header_style ); 
?>
    <?php
        do_action('electro_product_category_list');
    ?>
<?php
	
get_footer();
