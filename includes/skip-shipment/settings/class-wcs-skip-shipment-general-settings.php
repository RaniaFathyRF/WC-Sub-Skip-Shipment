<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WCS_Skip_Shipment_Settings')) {
    class WCS_Skip_Shipment_Settings
    {
        private static $instance;
        const SKIP_SHIPMENT_ID = 'wc_sub_enable_skip_shipment';
        const SKIP_SHIPMENT_BOX_ENABLE_KEY = 'enable_skip_box';

        /**
         *WC_Sub_Box_Skip_Shipment_Settings Constructor
         */
        public function __construct()
        {
            add_filter('wc_sub_box_extra_actions_add_settings', array($this, 'add_skip_shipment_option'), 10);

        }

        /**
         * Add skip shipment option in WooCommerce settings admin panel
         * @param $settings
         * @return mixed
         */
        public function add_skip_shipment_option($settings)
        {
            $settings[self::SKIP_SHIPMENT_BOX_ENABLE_KEY] = array(
                'name' => __('Enable Skip Shipment Feature', 'wc-sub-skip-shipment'),
                'id' => self::SKIP_SHIPMENT_ID,
                'type' => 'checkbox',
                'default' => ''
            );

            return $settings;
        }

        /**
         * Check if skip shipment box is checked and enabled
         * @return bool
         */
        public static function is_skip_shipment_box_enabled_in_settings()
        {
            return (WC_Sub_Skip_Shipment_Utility::wc_sub_skip_shipment_get_settings_options(self::SKIP_SHIPMENT_ID) != "no") ? true : false;

        }

        /**
         * Get instance
         * @return WCS_Skip_Shipment_Settings
         */
        public static function get_instance()
        {
            if (!isset(self::$instance) || is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }
    }
}

WCS_Skip_Shipment_Settings::get_instance();