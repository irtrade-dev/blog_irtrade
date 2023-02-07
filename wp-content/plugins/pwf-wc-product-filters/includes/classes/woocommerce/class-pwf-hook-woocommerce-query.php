<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Hook_Woocommerce_Query' ) ) {

	/**
	 * Hook Woocommerce Query Main|Custom query
	 *
	 * Most methods moved from class hook wp_query
	 *
	 * @since 1.6.6
	 */
	class Pwf_Hook_Woocommerce_Query {

		/**
		* The unique instance of the Pwf_Parse_Query_Vars.
		*
		* @var Pwf_Parse_Query_Vars
		*/
		private static $query_vars;
		private static $has_orderby;
		private static $has_price;

		public function __construct( Pwf_Parse_Query_Vars $query_vars, $q, $query_type ) {
			self::$query_vars = $query_vars;

			if ( self::$query_vars->has_price_item() ) {
				if ( ! isset( $_GET['min_price'] ) && ! isset( $_GET['max_price'] ) ) {
					self::$has_price = true;
					add_filter( 'posts_clauses', array( $this, 'price_filter_post_clauses' ), 10, 2 );
				}
			}

			$orderby = self::$query_vars->get_products_orderby();

			if ( ! empty( $orderby ) && ! isset( $_GET['orderby'] ) ) {
				self::$has_orderby = true;

				$orderby  = is_array( $orderby ) ? implode( '', $orderby ) : $orderby;
				$ordering = WC()->query->get_catalog_ordering_args( $orderby );

				$q->set( 'orderby', $ordering['orderby'] );
				$q->set( 'order', $ordering['order'] );
				if ( isset( $ordering['meta_key'] ) ) {
					$q->set( 'meta_key', $ordering['meta_key'] );
				}
			}

			add_filter( 'the_posts', array( $this, 'remove_product_query_filters' ), 10, 1 );
		}

		/**
		 * Custom query used to filter products by price.
		 *
		 * @since 3.6.0
		 *
		 * @param array    $args Query args.
		 * @param WC_Query $wp_query WC_Query object.
		 *
		 * @return array
		 */
		public function price_filter_post_clauses( $args, $wp_query ) {
			$price     = self::$query_vars->get_current_min_max_price();
			$min_price = floatval( wp_unslash( $price[0] ) );
			$max_price = floatval( wp_unslash( $price[1] ) );

			$args['join']   = self::append_product_sorting_table_join( $args['join'] );
			$args['where'] .= Pwf_Db_Utilities::get_price_where_sql( $min_price, $max_price );

			return $args;
		}

		/**
		 * Join wc_product_meta_lookup to posts if not already joined.
		 *
		 * @param string $sql SQL join.
		 * @return string
		 */
		protected static function append_product_sorting_table_join( $sql ) {
			if ( ! strstr( $sql, 'wc_product_meta_lookup' ) ) {
				$sql .= Pwf_Db_Utilities::get_price_join_sql();
			}
			return $sql;
		}

		public function remove_product_query_filters( $posts ) {

			if ( isset( self::$has_price ) && self::$has_price ) {
				remove_filter( 'posts_clauses', array( $this, 'price_filter_post_clauses' ), 10, 2 );
			}

			if ( isset( self::$has_orderby ) && self::$has_orderby ) {
				WC()->query->remove_ordering_args();
			}

			return $posts;
		}
	}
}
