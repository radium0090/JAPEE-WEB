<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

electro_get_header(); ?>



    <?php
        /**
         * woocommerce_before_main_content hook.
         *
         * @hooked electro_before_wc_content - 10 (outputs opening divs for the content)
         * @hooked electro_before_product_archive_content - 20
         */
        do_action( 'woocommerce_before_main_content' );
    ?>

        <?php
            /**
             * woocommerce_archive_description hook.
             *
             * @hooked woocommerce_taxonomy_archive_description - 10
             * @hooked woocommerce_product_archive_description - 10
             */
            do_action( 'woocommerce_archive_description' );
        ?>

        <?php if ( ( function_exists( 'woocommerce_product_loop' ) && woocommerce_product_loop() ) || have_posts() ) : ?>

<div class="widget woocommerce widget_product_search" id="search-custom">
  <form role="search" method="get" class="woocommerce-product-search" action="https://japee.tokyo/">
    <label class="screen-reader-text" for="woocommerce-product-search-field-0">Search for:</label>
    <span class="twitter-typeahead" style="position: relative; display: inline-block;">
      <input type="search" id="woocommerce-product-search-field-0" class="search-field tt-input" placeholder="Search products…" value="" name="s" spellcheck="false" dir="auto" aria-activedescendant="" aria-owns="woocommerce-product-search-field-0_listbox" role="combobox" aria-readonly="true" aria-autocomplete="list" style="position: relative; vertical-align: top;">

      <span role="status" aria-live="polite" style="position: absolute; padding: 0px; border: 0px; height: 1px; width: 1px; margin-bottom: -1px; margin-right: -1px; overflow: hidden; clip: rect(0px, 0px, 0px, 0px); white-space: nowrap;">
      </span>
      <pre aria-hidden="true" style="position: absolute; visibility: hidden; white-space: pre; font-family: &quot;Open Sans&quot;, HelveticaNeue-Light, &quot;Helvetica Neue Light&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Lucida Grande&quot;, sans-serif; font-size: 14px; font-style: normal; font-variant: normal; font-weight: 400; word-spacing: 0px; letter-spacing: 0px; text-indent: 0px; text-rendering: optimizelegibility; text-transform: none;"></pre>
      <div role="listbox" class="tt-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none;">
        <div role="presentation" class="tt-dataset tt-dataset-search"></div>
      </div>
    </span>
    <button type="submit" value="Search"></button>
    <input type="hidden" name="post_type" value="product">
  </form>
</div>
            <?php
                /**
                 * woocommerce_before_shop_loop hook.
                 *
                 * @hooked electro_product_subcategories - 0
                 * @hooked electro_wc_loop_title - 10
                 * @hooked electro_shop_control_bar - 10
                 * @hooked electro_reset_woocommerce_loop - 90
                 */
                do_action( 'woocommerce_before_shop_loop' );
            ?>
            
            <?php
                /**
                 * woocommerce_shop_loop hook
                 *
                 * @hooked electro_shop_loop
                 */
                do_action( 'woocommerce_shop_loop' );
            ?>

            <?php
                /**
                 * woocommerce_after_shop_loop hook.
                 *
                 * @hooked woocommerce_pagination - 10
                 */
                do_action( 'woocommerce_after_shop_loop' );
            ?>

        <?php else : ?>

            <?php
                /**
                 * Hook: woocommerce_no_products_found.
                 *
                 * @hooked wc_no_products_found - 10
                 */
                do_action( 'woocommerce_no_products_found' );
            ?>

        <?php endif; ?>

    <?php
        /**
         * woocommerce_after_main_content hook.
         *
         * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
         */
        do_action( 'woocommerce_after_main_content' );
    ?>

    <?php
        /**
         * woocommerce_sidebar hook.
         *
         * @hooked woocommerce_get_sidebar - 10
         */
        do_action( 'woocommerce_sidebar' );
    ?>

<?php get_footer(); ?>