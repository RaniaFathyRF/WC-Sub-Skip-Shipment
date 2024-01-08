<?php
/**
 * Plugin Name: WC Subscription Skip Shipment
 * Description: It is addon that enabled skip shipment to WC Subscription plugin
 * Version: 1.0
 * Author: rfmasters
 * Author URI: https://github.com/RaniaFathyRF
 * Text Domain: wc-sub-skip-shipment
 *
 * WC requires at least: 6.0.1
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once 'config.php';

// Check if WooCommerce is active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    add_action('admin_notices', function () {
        echo '<div class="error"><p>' . sprintf(__('You have not activated the base plugin %s. Please activate it to use WC Subscription Box Extra Actions plugin.',  'wc-sub-skip-shipment'), '<b>Woocommerce</b>') . '</p></div>';
    });
    return;
}
// Check if WooCommerce Subscription is active
if (!in_array('woocommerce-subscriptions/woocommerce-subscriptions.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    add_action('admin_notices', function () {
        echo '<div class="error"><p>' . sprintf(__('You have not activated the base plugin %s. Please activate it to use WC Subscription Box Extra Actions plugin.',  'wc-sub-skip-shipment'), '<b>Woocommerce Subscriptions</b>') . '</p></div>';
    });
    return;
}

include 'includes/index.php';
