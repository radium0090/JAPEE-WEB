
<?php 
/**
* Add menu position for mobile docker menu
*/
if (!function_exists('register_custom_menus')) { 
   function register_custom_menus() {
       register_nav_menus(
           array( 
               'mobile-footer-docker-menu' => __( 'Footer Docker Menu' )
           ));
       }
   
   add_action( 'init', 'register_custom_menus' );
}

/**
* Add extra class to body for product category page
*/
add_filter('body_class', function($classes) {
   if (is_page('product-category') ){
       return array_merge( $classes, array( 'product-category-page' ) );
   }
   return $classes;
});

include get_template_directory() . '/lib/DockerMenuWalker.php';


if (!function_exists('electro_display_mobile_footer_menu')) {
   /**
    * Display mobile footer only for mobile devices
    */
   function electro_display_mobile_footer_menu() {
       
       ?>
           <div class="elctro-footer-docker-container hidden-xl-up">
           <?php 
               wp_nav_menu( array( 
                   'theme_location' => 'mobile-footer-docker-menu', 
                   'container' => '', 
                   'walker' => new DockerMenuWalker()
               ));
           ?>
           </div>  
       <?php
   }   
}


add_action( 'electro_footer',  'electro_display_mobile_footer_menu',    99 );
add_action( 'electro_footer_v2',  'electro_display_mobile_footer_menu',    99 );
add_action( 'electro_mobile_footer_v1',  'electro_display_mobile_footer_menu',    99 );
add_action( 'electro_mobile_footer_v2',  'electro_display_mobile_footer_menu',    99 );