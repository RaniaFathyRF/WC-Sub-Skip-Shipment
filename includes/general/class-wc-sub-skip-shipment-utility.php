<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WC_Sub_Skip_Shipment_Utility')) {

    class WC_Sub_Skip_Shipment_Utility
    {

        /**
         * @var WC_Sub_Skip_Shipment_Utility
         */
        public static $instance;

        const WC_SUB_SKIP_SHIPMENT_SUBSCRIPTIONS_TYPES = [
            'subscription',
            'variable-subscription',
            'subscription_variation',
        ];

        private function __construct()
        {
            add_action('wp_enqueue_scripts', array($this, 'wc_sub_skip_shipment_enqueue_scripts'));
            add_action('wp_footer', array($this, 'wc_sub_skip_shipment_add_loader'));
        }

        public function wc_sub_skip_shipment_enqueue_scripts()
        {
            wp_enqueue_style('wc_sub_skip_shipment_loader', WC_SUB_SKIP_SHIPMENT_URL . 'assets/css/wc-sub-skip-shipment-loader.css', false, WC_SUB_SKIP_SHIPMENT_ASSETS_VERSION);
        }

        public function wc_sub_skip_shipment_add_loader()
        {
            if (!wcs_is_view_subscription_page())
                return;
            if (!apply_filters('wc_sub_skip_shipment_display_loader_html', false))
                return;
            echo '<div class="wc-sub-box-extra-action-loader-wrapper" style="display:none;"><div class="wc-sub-box-extra-action-loader"></div></div>';
        }

        public static function enqueue_micromodal_scripts()
        {
            wp_enqueue_script('wc-sub-skip-shipment-micromodal', WC_SUB_SKIP_SHIPMENT_URL . 'assets/js/micromodal.js', array('jquery'), WC_SUB_SKIP_SHIPMENT_ASSETS_VERSION);
            wp_enqueue_style('wc-sub-skip-shipment-micromodal', WC_SUB_SKIP_SHIPMENT_URL . 'assets/css/micromodal.css', false, WC_SUB_SKIP_SHIPMENT_ASSETS_VERSION);
        }


        public static function wc_sub_skip_shipment_get_settings_options($key)
        {

            return get_option($key) ? get_option($key) : false;
        }


        /**
         * check if product is subscription
         * @param $product
         * @return bool
         */
        public static function is_subscription_product($product)
        {
            if (empty($product))
                return false;

            if (!is_object($product) && is_integer($product))
                $wc_product = wc_get_product($product);
            else
                $wc_product = $product;

            if (in_array($wc_product->get_type(), self::WC_SUB_SKIP_SHIPMENT_SUBSCRIPTIONS_TYPES))
                return true;

            return false;
        }

        public static function is_wcs_subscription($subscription)
        {
            if (empty($subscription))
                return false;
            foreach ($subscription->get_items() as $item_id => $item) {
                if (self::is_subscription_product($item->get_product()))
                    return true;
            }
            return false;
        }

        /**
         * only one subscription box message
         * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|\Illuminate\Foundation\Application|mixed|string|null
         */
        public static function wc_sub_skip_shipment_get_one_only_subscription_message()
        {

            return __('You already have a Subscription in your cart.', 'wc-sub-skip-shipment') . ' ' . __('Please remove any Subscription from your cart before adding additional Subscription boxes.', 'wc-sub-box-extra-actions');
        }

        /**
         * @return WC_Sub_Skip_Shipment_Utility
         */
        public static function get_instance()
        {
            if (!isset(self::$instance) || is_null(self::$instance))
                self::$instance = new self();

            return self::$instance;
        }
    }


    WC_Sub_Skip_Shipment_Utility::get_instance();
}
