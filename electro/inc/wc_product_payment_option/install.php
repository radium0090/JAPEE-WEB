<?php 

class Woocommerce_Product_Payment_Install {

    protected $loader;

    public function __construct() {
        $this->load_dependencies();
    }

    private function load_dependencies(){
        require_once get_template_directory() . '/inc/wc_product_payment_option/loader.php';
        $this->loader = new Woocommerce_Product_Payment_Loader();
        $this->define_hooks();
    }   

    private function define_hooks(){

        function s_japee_product_payments_enqueue() {
            wp_enqueue_style('s_japee_pd_payments_enqueue', get_template_directory_uri() . '/inc/wc_product_payment_option/style.css');
        }

        $this->loader->add_action('admin_enqueue_scripts', null, 's_japee_product_payments_enqueue');
        
        function product_payments_submenu_page() {
            add_submenu_page('woocommerce', __('Product Payments', 's_japee'), __('Product Payments', 's_japee'), 'manage_options', 's_japee-product-payments', 'dfm_wcpgpp_product_payments_settings');
        }

        $this->loader->add_action('admin_menu', null, 'product_payments_submenu_page');

        add_action('save_post', 'wpp_meta_box_save', 10, 2);

        $this->loader->add_action('add_meta_boxes', null, 'wpp_meta_box_add');
        //$this->loader->add_action('admin_menu', null, 'wppg_menu');
        $this->loader->add_action("admin_enqueue_scripts", null, "wppg_admin_scripts");

        $this->loader->add_filter('woocommerce_available_payment_gateways', null, 'wpppayment_gateway_disable_country');
        
    }

    public function run() {
        $this->loader->run();
    }
}