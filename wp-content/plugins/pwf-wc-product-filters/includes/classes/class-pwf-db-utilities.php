<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Db_Utilities' ) ) {

	/**
	 * Functions define here are use by many other classes
	 * @ since 1.4.8
	 */
	class Pwf_Db_Utilities {
		private function __construct() {}

		/**
		 * @return string sql where
		 */
		public static function get_price_where_sql( $min_price, $max_price ) {
			global $wpdb;

			/**
			 * Adjust if the store taxes are not displayed how they are stored.
			 * Kicks in when prices excluding tax are displayed including tax.
			 */
			if ( wc_tax_enabled() && 'incl' === get_option( 'woocommerce_tax_display_shop' ) && ! wc_prices_include_tax() ) {
				$tax_class = apply_filters( 'pwf_woocommerce_price_filter_tax_class', '' ); // Uses standard tax class.
				$tax_rates = WC_Tax::get_rates( $tax_class );

				if ( $tax_rates ) {
					$min_price -= WC_Tax::get_tax_total( WC_Tax::calc_inclusive_tax( $min_price, $tax_rates ) );
					$max_price -= WC_Tax::get_tax_total( WC_Tax::calc_inclusive_tax( $max_price, $tax_rates ) );
				}
			}

			$where = $wpdb->prepare(
				' AND wc_product_meta_lookup.min_price >= %f AND wc_product_meta_lookup.max_price <= %f ',
				$min_price,
				$max_price
			);

			return $where;
		}

		/**
		 * @return string sql join
		 */
		public static function get_price_join_sql() {
			global $wpdb;

			return " LEFT JOIN {$wpdb->wc_product_meta_lookup} wc_product_meta_lookup ON $wpdb->posts.ID = wc_product_meta_lookup.product_id ";
		}

		/**
		 * @return string sql where
		 */
		public static function get_authors_where_sql( $author_ids ) {
			global $wpdb;

			return " AND {$wpdb->posts}.post_author IN (" . implode( ',', array_map( 'absint', $author_ids ) ) . ')';
		}

		/**
		 * used by many functions on shortcode calss
		 *
		 * @return string sql where
		 */
		public static function get_product_ids_where_sql( $product_ids ) {
			global $wpdb;

			return " AND {$wpdb->posts}.ID IN (" . implode( ',', array_map( 'absint', $product_ids ) ) . ')';
		}

		/**
		 * Add a filter hook to post type
		 *
		 * @since 1.6.4
		 *
		 * @return array
		 */
		public static function get_post_type( $post_type, $filter_id ) {
			$post_types = apply_filters( 'pwf_woo_post_type', array( $post_type ), $filter_id );

			return $post_types;
		}

		/**
		 * used by many functions
		 *
		 * @since 1.6.4
		 *
		 * @return string sql where
		 */
		public static function get_post_type_where_sql( $post_type, $filter_id ) {
			global $wpdb;

			$post_types = self::get_post_type( $post_type, $filter_id );

			return "WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', $post_types ) ) . "') AND {$wpdb->posts}.post_status = 'publish'";
		}
	}
}
