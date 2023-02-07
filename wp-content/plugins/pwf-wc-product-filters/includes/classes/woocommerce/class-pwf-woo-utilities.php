<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Woo_Utilities' ) ) {

	/**
	 * Assign all WooCommerce functions at on place
	 *
	 * @since 1.1.0
	 */

	class Pwf_Woo_Utilities {

		public function __construct() {}


		protected static function get_tax_query_out_of_stock() {
			return array(
				'taxonomy'         => 'product_visibility',
				'terms'            => array( 'outofstock' ),
				'field'            => 'slug',
				'operator'         => 'NOT IN',
				'include_children' => true,
			);
		}

		public static function get_product_visibility() {
			$exclude_from_catalog = array(
				'taxonomy'         => 'product_visibility',
				'terms'            => array( 'exclude-from-catalog' ),
				'field'            => 'slug',
				'operator'         => 'NOT IN',
				'include_children' => true,
			);

			$tax_query[] = $exclude_from_catalog;

			/*
			 * usefule when get products using ajax
			 */
			if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
				$tax_query[] = self::get_tax_query_out_of_stock();
			}

			return $tax_query;
		}

	}
}
