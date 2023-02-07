<?php
/*------------------------------------------------------------------------------
Plugin Name: PWF - Products Filter for WooCommerce
Description: Filter WooCommerce products and WordPress post types. Filter by any criteria including categories, tags, taxonomies, price, and custom fields.
Version:     1.1.4
Author:      Mostafa
Author URI:  https://mostafaa.net/
text domain: pwf-woo-filter
Domain Path: /languages/
Requires at least: 5.6.0
Tested up to: 6.0.3

Requires PHP: 7.4
WC requires at least: 4.3.0
WC tested up to: 7.1.0
------------------------------------------------------------------------------*/

defined( 'ABSPATH' ) || exit; // exit if file is called directly

const PWF_WOO_FILTER_VER        = '1.1.4';
const PWF_WOO_FILTER_DB_VERSION = '1.0.1';

define( 'PWF_WOO_FILTER_URI', plugins_url( '', __FILE__ ) );
define( 'PWF_WOO_FILTER_DIR', plugin_dir_path( __FILE__ ) );
define( 'PWF_WOO_FILTER_DIR_DOMAIN', dirname( plugin_basename( __FILE__ ) ) );
define( 'PWF_WOO_FILTER_BASENAMEFILE', plugin_basename( __FILE__ ) );


require_once 'includes/classes/class-pwf-autoloader.php';
require_once 'includes/class-pwf-main.php';
require_once 'includes/widgets/class-pwf-filter-widget.php';
require_once 'includes/render/class-pwf-render-filter.php';
require_once 'includes/class-pwf-front-end-ajax.php';

if ( ! is_admin() ) {
	require_once 'includes/classes/class-pwf-hook-wp-query.php';
}

if ( is_admin() && ! is_plugin_ajax() ) {
	require_once 'includes/classes/admin/class-pwf-admin-main.php';
	require_once 'includes/classes/admin/class-pwf-admin-settings-page.php';
}

/**
 * Checking if the URL request comes form frontfend Ajax.
 */
function is_plugin_ajax() {
	$result      = false;
	$post_action = $_POST['action'] ?? '';
	$get_action  = $_GET['action'] ?? '';
	if ( 'get_filter_result' === $post_action || 'get_filter_result' === $get_action ) {
		$result = true;
	}

	return apply_filters( 'is_plugin_ajax', $result );
}
