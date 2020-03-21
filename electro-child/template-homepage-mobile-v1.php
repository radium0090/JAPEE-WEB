<?php
/**
 * Template name: Homepage Mobile v1
 * @package electro
 */
?>
<!doctype html>
<html lang="en">
  <head>
    <!--   Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/wp-content/themes/electro-child/css_sp/slideshow.css">
    <title>Japee Japanese online shopping mall</title>
  </head>
  <body>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" src="/wp-content/themes/electro-child/cap.PNG" alt="First slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="/wp-content/themes/electro-child/cap.PNG" alt="Second slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="/wp-content/themes/electro-child/cap.PNG" alt="Third slide">
    </div>
  </div>
 
</div>

<!-- 横スクロールメニューNav -->

        <div class="scrollmenu nav-icon">
        <a href="#home"><i class="fas fa-bars"></i><br><div class="nav-font">All Category</div></a>
        <a href="#home"><i class="fas fa-mobile"></i><br><div class="nav-font">1amen</div></a>
        <a href="#home"><i class="fas fa-mobile"></i><br><div class="nav-font">Ramen</div></a>
        <a href="#home"><i class="fas fa-mobile"></i><br><div class="nav-font">Ramen</div></a>
        <a href="#home"><i class="fas fa-mobile"></i><br><div class="nav-font">Ramen</div></a>
        <a href="#home"><i class="fas fa-mobile"></i><br><div class="nav-font">Ramen</div></a>
        <a href="#home"><i class="fas fa-mobile"></i><br><div class="nav-font">Ramen</div></a>
        <a href="#home"><i class="fas fa-mobile"></i><br><div class="nav-font">Ramen</div></a>
        <a href="#home"><i class="fas fa-mobile"></i><br><div class="nav-font">Ramen</div></a>
        <a href="#home"><i class="fas fa-mobile"></i><br><div class="nav-font">Ramen</div></a>
        </div>

<!-- スクロールメニュー -->
<?php
if ( ! function_exists( 'electro_home_mobile_v2_products_list_block_1' ) ) {
    /**
     * Dispaly Products list Block 1 in Home Mobile v2
     */
    function electro_home_mobile_v2_products_list_block_1() {

        if ( is_woocommerce_activated() ) {
            $home_mobile_v2    = electro_get_home_mobile_v2_meta();

            $is_enabled = isset( $home_mobile_v2['pl1']['is_enabled'] ) ? $home_mobile_v2['pl1']['is_enabled'] : 'no';

            if ( $is_enabled !== 'yes' ) {
                return;
            }

            $animation  = isset( $home_mobile_v2['pl1']['animation'] ) ? $home_mobile_v2['pl1']['animation'] : '';

            $args = array(
                'section_class'     => '',
                'animation'         => $animation,
                'section_title'     => isset( $home_mobile_v2['pl1']['section_title'] ) ? $home_mobile_v2['pl1']['section_title'] : esc_html__( 'Bestsellers', 'electro' ),
                'enable_categories' => isset( $home_mobile_v2['pl1']['enable_categories'] ) ? filter_var( $home_mobile_v2['pl1']['enable_categories'], FILTER_VALIDATE_BOOLEAN ) : false,
                'categories_title'  => isset( $home_mobile_v2['pl1']['categories_title'] ) ? $home_mobile_v2['pl1']['categories_title'] : esc_html__( 'Top 20', 'electro' ),
                'category_args'     => array(
                    'orderby'           => isset( $home_mobile_v2['pl1']['category_args']['orderby'] ) ? $home_mobile_v2['pl1']['category_args']['orderby'] : 'name',
                    'order'             => isset( $home_mobile_v2['pl1']['category_args']['order'] ) ? $home_mobile_v2['pl1']['category_args']['order'] : 'ASC',
                    'hide_empty'        => isset( $home_mobile_v2['pl1']['category_args']['hide_empty'] ) ? filter_var( $home_mobile_v2['pl1']['category_args']['hide_empty'], FILTER_VALIDATE_BOOLEAN ) : false,
                    'number'            => isset( $home_mobile_v2['pl1']['category_args']['number'] ) ? $home_mobile_v2['pl1']['category_args']['number'] : 3,
                    'slugs'             => isset( $home_mobile_v2['pl1']['category_args']['slugs'] ) ? $home_mobile_v2['pl1']['category_args']['slugs'] : '',
                ),
                'shortcode_tag'     => isset( $home_mobile_v2['pl1']['content']['shortcode'] ) ? $home_mobile_v2['pl1']['content']['shortcode'] : 'featured_products',
                'shortcode_atts'    => isset( $home_mobile_v2['pl1']['content'] ) ? electro_get_atts_for_shortcode( $home_mobile_v2['pl1']['content'] ) : array( 'per_page' => 6, 'columns' => 3 ),
                'type'              => 'v2',
                'action_text'       => isset( $home_mobile_v2['pl1']['action_text'] ) ? $home_mobile_v2['pl1']['action_text'] : esc_html__( 'See all Products', 'electro' ),
                'action_link'       => isset( $home_mobile_v2['pl1']['action_link'] ) ? $home_mobile_v2['pl1']['action_link'] : '#',
            );

            electro_products_list_block( $args );
        }
    }
}
?>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>


  <?php
get_footer( $footer_style ); ?>
</html>



