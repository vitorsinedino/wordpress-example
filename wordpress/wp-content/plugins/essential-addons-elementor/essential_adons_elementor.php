<?php
/**
 * Plugin Name: Essential Addons for Elementor - Pro
 * Description: The Essential plugin you install after Elementor! 65+ Premium elements, including WooCommerce, Event Calender, Data Table, Post Grid
 * Plugin URI: https://essential-addons.com/elementor/
 * Author: WPDeveloper
 * Version: 3.6.0
 * Author URI: http://www.wpdeveloper.net
 * Text Domain: essential-addons-elementor
 * Domain Path: /languages
 * 
 * WC tested up to: 4.0.0
 */

if (!defined('WPINC')) {
    exit;
}
/**
 * Defining plugin constants.
 *
 * @since 3.0.0
 */
define('EAEL_PRO_PLUGIN_FILE', __FILE__);
define('EAEL_PRO_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('EAEL_PRO_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('EAEL_PRO_PLUGIN_URL', plugins_url('/', __FILE__));
define('EAEL_PRO_PLUGIN_VERSION', '3.6.0');
define('EAEL_STORE_URL', 'https://wpdeveloper.net/');
define('EAEL_SL_ITEM_ID', 4372);
define('EAEL_SL_ITEM_SLUG', 'essential-addons-elementor');
define('EAEL_SL_ITEM_NAME', 'Essential Addons for Elementor');
define('EA_DEV_MODE', false);

/**
 * Including autoloader.
 *
 * @since 3.0.0
 */
require_once EAEL_PRO_PLUGIN_PATH . 'autoload.php';

/**
 * Run plugin before lite version
 *
 * @since 3.0.0
 */
add_action('eael/before_init', function () {
    \Essential_Addons_Elementor\Pro\Classes\Bootstrap::instance();
});

/**
 * Plugin updater
 *
 * @since v3.0.4
 */
add_action('plugins_loaded', function () {
    if(EA_DEV_MODE === false ) {
        $migration = new \Essential_Addons_Elementor\Pro\Classes\Migration;
        $migration->plugin_updater();
    }
});



/**
 * Plugin migrator
 *
 * @since v3.0.0
 */
add_action('wp_loaded', function () {
    $migration = new \Essential_Addons_Elementor\Pro\Classes\Migration;
    $migration->migrator();
});

/**
 * Activation hook
 *
 * @since v3.0.0
 */
register_activation_hook(__FILE__, function () {
    $migration = new \Essential_Addons_Elementor\Pro\Classes\Migration;
    $migration->plugin_activation_hook();
});

/**
 * Deactivation hook
 *
 * @since v3.0.0
 */
register_deactivation_hook(__FILE__, function () {
    $migration = new \Essential_Addons_Elementor\Pro\Classes\Migration;
    $migration->plugin_deactivation_hook();
});

/**
 * Upgrade hook
 *
 * @since v3.0.0
 */
add_action('upgrader_process_complete', function ($upgrader_object, $options) {
    $migration = new \Essential_Addons_Elementor\Pro\Classes\Migration;
    $migration->plugin_upgrade_hook($upgrader_object, $options);
}, 10, 2);

/**
 * Admin Notices
 *
 * @since v3.0.0
 */
add_action('admin_notices', function () {
    $notice = new \Essential_Addons_Elementor\Pro\Classes\Notice;
    $notice->failed_to_load();
});
