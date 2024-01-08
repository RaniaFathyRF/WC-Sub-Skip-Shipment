<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'WCS_Skip_Shipment_Helper' ) ) {
	class WCS_Skip_Shipment_Helper {
		private static $instance;

		/**
		 * Constructor
		 */
		public function __construct() {

		}

		/**
		 * Skip shipment logic handling
		 * @param $subscription_id
		 * @return void
		 */
		public static function skip_shipment( $subscription_id ) {
			if ( ! WCS_Skip_Shipment_Settings::is_skip_shipment_box_enabled_in_settings() ) {
				return;
			}
			$set_next_date = self::get_next_shipment_date( $subscription_id );
			$subscription  = wcs_get_subscription( $subscription_id );
			// Set the next payment date for the subscription
			$subscription->update_dates( array( 'next_payment' => $set_next_date ) );
			$note = sprintf( __( 'Shipment skipped. Next payment date updated to %s', 'wc-sub-skip-shipment' ), $set_next_date );
			$subscription->add_order_note( $note );
			// Save changes
			$subscription->save();
		}

		/**
		 * Get next shipment date based on subscription
		 * @param $subscription_id
		 * @param $format
		 * @return string
		 */
		public static function get_next_shipment_date( $subscription_id, $format = 'Y-m-d H:i:s' ) {

			$set_next_date = '';
			if ( empty( $subscription_id ) ) {
				return $set_next_date;
			}

			if ( ! WCS_Skip_Shipment_Settings::is_skip_shipment_box_enabled_in_settings() ) {
				return $set_next_date;
			}


			if ( ! is_object( $subscription_id ) && is_numeric( $subscription_id ) ) {
				$subscription = wcs_get_subscription( $subscription_id );
			} else {
				$subscription = $subscription_id;
			}

			if ( empty( $subscription ) ) {
				return $set_next_date;
			}
			// current next payment date
			$current_next_payment_date = $subscription->get_date( 'next_payment' );
			foreach ( $subscription->get_items() as $item ) {
                if ($item->get_product()->is_type('subscription') || $item->get_product()->is_type('subscription_variation')) {
                    $product = $item->get_product();
                    $period = WC_Subscriptions_Product::get_period($product);
                    $interval = WC_Subscriptions_Product::get_interval($product);
                }
            }

			// Calculate the new next shipment date
			$set_next_date = date( $format, strtotime( "+$interval $period", strtotime( $current_next_payment_date ) ) );

			return $set_next_date;

		}

		/**
		 * Get instance
		 * @return self
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) || is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

WCS_Skip_Shipment_Helper::get_instance();