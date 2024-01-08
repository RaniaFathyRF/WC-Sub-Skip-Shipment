<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!class_exists('WC_Sub_Skip_Shipment_General_Settings')) {

    class WC_Sub_Skip_Shipment_General_Settings
    {
        /**
         * @var WC_Sub_Skip_Shipment_General_Settings
         */
        public static $instance;

        const SECTION_TITLE_ID = 'wc_sub_skip_shipment_section';
        const SECTION_END_ID = 'wc_sub_skip_shipment_section';
        const SECTION_TITLE_KEY = 'section_title';
        const SECTION_END_KEY = 'section_end';

        private function __construct()
        {
            // add wc sub box general settings
            add_filter('woocommerce_settings_tabs_array', array($this, 'wc_sub_skip_shipment_add_settings_tab'), 99);
            add_action('woocommerce_settings_tabs_wc_sub_box_extra_actions', array($this, 'wc_sub_skip_shipment_settings_tab_content'));
            add_action('woocommerce_update_options_wc_sub_box_extra_actions', array($this, 'wc_sub_skip_shipment_update_settings'));

        }

        public function wc_sub_skip_shipment_add_settings_tab($tabs)
        {
            $tabs['wc_sub_box_extra_actions'] = __('WC Sub Box Extra Action', 'wc-sub-skip-shipment');

            return $tabs;
        }

        public function wc_sub_skip_shipment_settings_tab_content()
        {
            woocommerce_admin_fields($this->wc_sub_skip_shipment_get_settings());
        }

        public function wc_sub_skip_shipment_get_settings()
        {

            $settings[self::SECTION_TITLE_KEY] = array(
                'name' => __('WC Sub Box Extra Action Settings', 'wc-sub-skip-shipment'),
                'type' => 'title',
                'desc' => __('Configure your WC Sub Box Extra Action settings below:', 'wc-sub-skip-shipment'),
                'id' => self::SECTION_TITLE_ID
            );
            // add custom settings
            $settings = apply_filters('wc_sub_box_extra_actions_add_settings', $settings);

            $settings[self::SECTION_END_KEY] = array(
                'type' => 'sectionend',
                'id' => self::SECTION_END_ID
            );


            return $settings;
        }

        public function wc_sub_skip_shipment_update_settings()
        {
            woocommerce_update_options($this->wc_sub_skip_shipment_get_settings());
        }

        /**
         * @return WC_Sub_Skip_Shipment_General_Settings
         */
        public static function get_instance()
        {
            if (!isset(self::$instance) || is_null(self::$instance))
                self::$instance = new self();

            return self::$instance;
        }

    }

}
WC_Sub_Skip_Shipment_General_Settings::get_instance();

