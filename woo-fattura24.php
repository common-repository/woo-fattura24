<?php

/*
 * Plugin Name: WooCommerce Fattura24
 * Plugin URI:  https://woofattura24.com/
 * Description: WooCommerce Fattura24 integration
 * Version:     0.9.2
 * Contributors: wpnetworkit, cristianozanca
 * Author:      WPNetwork
 * Author URI:  https://wpnetwork.it
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: woo-fattura24
 * Domain Path: /languages
 * WC requires at least: 4.0
 * WC tested up to: 4.7
 */




function woo_fattura24_textdomain()
{
    load_plugin_textdomain('woo-fattura24', false, basename(dirname(__FILE__)) . '/languages');
}

add_action('plugins_loaded', 'woo_fattura24_textdomain');


if (!class_exists('woo_fattura24')): {
    class woo_fattura24
    {

        public function check_fatt24()
        {


            if (in_array('Fattura24/fattura24.php', apply_filters('active_plugins', get_option('active_plugins')))) {


                deactivate_plugins(plugin_basename('Fattura24/fattura24.php'));

                $class = "error";
                $message = sprintf(
                    __(
                        '<b>WooCommerce Fattura24</b> activation caused %sFattura24%s to be <b>deactivated</b>!',
                        'woo-fattura24'
                    ),
                    '<a href="https://www.fattura24.com/woocommerce-plugin-fatturazione/">',
                    '</a>'
                );

                echo "<div class=\"$class\"> <p>$message</p></div>";
            }
        }

        public function __construct()
        {

            if (!defined('ABSPATH')) {

                exit; // Exit if accessed directly
            }


                add_action('admin_notices', array($this, 'check_fatt24'));

                include_once plugin_dir_path(__FILE__) . 'inc/menu_setup.php';

                include_once plugin_dir_path(__FILE__) . 'inc/setup_page_display.php';

                add_option('fattura24_send_choice', 'fattura');

                add_option('fattura24_partiva_codfisc', '1');

                add_option('fattura24_auto_save', 'nulla');

                add_action('admin_enqueue_scripts', array($this, 'register_woo_fattura24_styles_and_scripts'));

                add_action('admin_menu', 'woo_fattura24_setup_menu');

                add_action('wp_ajax_woo_fattura24_search', array($this, 'woo_fattura24_search'));

                add_action('woocommerce_order_status_completed', array(&$this, 'fattura24_order_completed'), 10, 1);

                add_filter('woocommerce_cart_hide_zero_taxes', '__return_false');

                add_filter('woocommerce_order_hide_zero_taxes', '__return_false');

                add_action('admin_notices', array($this, 'woo_fattura24_error_notice'));


            if (1 == get_option('fattura24_partiva_codfisc')) {

                include_once plugin_dir_path(__FILE__) . 'inc/vat_number.php';


                add_filter('woocommerce_admin_billing_fields',  'admin_billing_field_woofattura24');

                add_filter('woocommerce_billing_fields', 'billing_fields_woofattura24', 10, 1);
            }
        }


        function fattura24_order_completed($order_id)
        {

            global $description;

            if (1 == get_option('fattura24_auto_save')) {

                error_log("$order_id set to COMPLETED", 0);

                update_option('woo_fattura24_order_id', $order_id);


                include plugin_dir_path(__FILE__) . 'inc/setup-file.php';
                include plugin_dir_path(__FILE__) . 'inc/send_to_fattura24.php';


                if (strpos($description, 'wrong')) {

                    update_option('fattura24_autosent_id_fallito', $order_id);

                } elseif (strpos($description, 'Operation completed')) {


                    update_option('fattura24_autosent_id_riuscito', $order_id);
                }
            }
        }

            /**
             *
             *
             * Ajax Callback to Search Orders
             *
             *
             */

        public function woo_fattura24_search()
        {

            $q = filter_input(INPUT_GET, 'q');

            $args = array(
                'post_type' => 'shop_order',
                'posts_per_page' => 10,
                'post_status' => array_keys(wc_get_order_statuses()),
                'post__in' => array($q)
            );
            $response = array();
            $orders = new WP_Query($args);

            while ($orders->have_posts()): $orders->the_post();
                $id = get_the_id();
                $response[] = array('id' => $id, 'text' => '#order :' . $id);
            endwhile;

            wp_reset_postdata();

            wp_send_json($response);
        }



        function woo_fattura24_error_notice()
        {
            if (!is_plugin_active('woocommerce/woocommerce.php')) {

            ?>

        <div class="notice error is-dismissible">
            <p>
                <?php _e('Please install or activate WooCommerce Plugin, it is required for WooCommerce Fattura24 Plugin to work ', 'woo-discount-price'); ?>
            </p>
        </div>
        <?php

            }
        }


            /**
             *
             * Custom stylesheet to load image and js scripts only on backend page
             *
             */

        function register_woo_fattura24_styles_and_scripts($hook)
        {

            $current_screen = get_current_screen();

            if (strpos($current_screen->base, 'woo-fattura24') === false) {
                return;
            } else {

                wp_enqueue_style('boot_css', plugins_url('assets/css/woo_fattura24.css', __FILE__));
                wp_enqueue_style('woo-fattura24-select2-css', plugins_url('assets/css/select2.min.css', __FILE__));
                wp_enqueue_script('woo-fattura24-select2-js', plugins_url('assets/js/select2/select2.min.js', __FILE__));
                wp_enqueue_script('woo-fattura24-it-select2-js', plugins_url('assets/js/select2/i18n/it.js', __FILE__));
            }
        }
    }
}

//Creates a new instance
new woo_fattura24;

endif;
