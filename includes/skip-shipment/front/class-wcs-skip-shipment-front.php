<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('WCS_Skip_Shipment_Front')) {
    class WCS_Skip_Shipment_Front
    {
        const SKIP_SHIPMENT = 'skip_shipment';
        private static $instance;

		/**
		 * WC_Sub_Box_Skip_Shipment_Front constructor.
		 */
		public function __construct() {
			// Enqueue scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ),15 );
			// Add skip shipment button
			add_filter( 'wcs_view_subscription_actions', array( $this, 'add_skip_shipment_button' ), 15, 2 );
			// Add skip shipment popup content
			add_action( 'woocommerce_subscription_after_actions', array($this,'add_skip_shipment_popup_content'), 15 );
			// Add AJAX handler for skipping shipment confirmation
			add_action( 'wp_ajax_handle_skip_shipment_confirmation', array( $this, 'handle_skip_shipment_confirmation' ) );
			// Add filter to skip shipment display loader
            add_filter('wc_sub_box_extra_action_display_loader_html', array($this, 'wcs_skip_shipment_display_loader'));
      }

        /**
         * Enqueue scripts
         * @return void
         */
        function enqueue_script()
        {
            if (!WCS_Skip_Shipment_Settings::is_skip_shipment_box_enabled_in_settings())
                return;
            $subscription_id = get_query_var('view-subscription');
            $subscription = wcs_get_subscription($subscription_id);
            if (!WC_Sub_Skip_Shipment_Utility::is_wcs_subscription($subscription))
                return;
            //Enqueue micromodal script
            WC_Sub_Skip_Shipment_Utility::enqueue_micromodal_scripts();
            // Enqueue popup skip shipment script
            wp_enqueue_style('wcs-skip-shipment-popup-style', WCS_SKIP_SHIPMENT_URL . 'assets/css/wcs-skip-shipment-popup.css', array(), WCS_SKIP_SHIPMENT_ASSETS_VERSION);
            wp_enqueue_script('wcs-skip-shipment-popup-script', WCS_SKIP_SHIPMENT_URL . 'assets/js/wcs-skip-shipment-popup.js', array(
                'jquery',
                'wc-sub-skip-shipment-micromodal'
            ), WCS_SKIP_SHIPMENT_ASSETS_VERSION, true);

			// Pass necessary data to JavaScript
			wp_localize_script( 'wcs-skip-shipment-popup-script', 'ajax_object', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'skip_shipment_nonce' ),
			) );
		}

		/**
		 * Add skip shipment button
		 *
		 * @param $actions
		 * @param $subscription
		 *
		 * @return mixed
		 */
		public function add_skip_shipment_button( $actions, $subscription ) {
            // If the checkbox is checked, show the button
            if (!WCS_Skip_Shipment_Settings::is_skip_shipment_box_enabled_in_settings())
                return $actions;

            if (!WC_Sub_Skip_Shipment_Utility::is_wcs_subscription($subscription))
                return $actions;

            $url = esc_url(wc_get_account_endpoint_url('subscriptions'));

            $actions[self::SKIP_SHIPMENT] = array(
                'url' => $url,
                'name' => __('Skip Shipment', 'wc-sub-skip-shipment'),
            );


            return $actions;
        }
		/**
		 * Add skip shipment popup content
		 *
		 * @param $subscription
		 *
		 * @return void
		 */
		public function add_skip_shipment_popup_content( $subscription ) {
			// If the checkbox is checked and enabled, show popup content
			if ( ! WCS_Skip_Shipment_Settings::is_skip_shipment_box_enabled_in_settings() )
				return;

            if (!WC_Sub_Skip_Shipment_Utility::is_wcs_subscription($subscription))
                return;

			$return_set_next_date = WCS_Skip_Shipment_Helper::get_next_shipment_date( $subscription, 'd/m/Y' );
			include WCS_SKIP_SHIPMENT_PATH . 'templates/wcs-skip-shipment-popup.php';
		}

		/**
		 * Handle skip shipment popup confirm button
		 * @return void
		 */
		function handle_skip_shipment_confirmation() {
			if ( ! WCS_Skip_Shipment_Settings::is_skip_shipment_box_enabled_in_settings() ) {
				return;
			}
            // Security check
            check_ajax_referer('skip_shipment_nonce', 'nonce');
            $subscription_id = $_POST['subscription_id'] ?? '';
            WCS_Skip_Shipment_Helper::skip_shipment($subscription_id);
            // Return the response
            wp_send_json_success("success");
        }

        public function wcs_skip_shipment_display_loader($display_loader = false)
        {
            if (WCS_Skip_Shipment_Settings::is_skip_shipment_box_enabled_in_settings())
                return true;

            return $display_loader;
        }
		/**
		 * Get instance
		 * @return WCS_Skip_Shipment_Front
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

WCS_Skip_Shipment_Front::get_instance();
