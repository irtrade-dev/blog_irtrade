<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Filter_Products' ) ) {

	class Pwf_Filter_Products {

		protected $filter_id;

		/**
		 * @since 1.3.3
		 */
		protected static $query_parse;

		/**
		 * @since 1.0.0
		 * @var   array
		 */
		protected $attributes;

		/**
		 * hold database query results.
		 * Like total, total_pages, per_page, current_page.
		 *
		 * @since 1.0.0
		 * @var   array
		 */
		protected $query_info = array();

		/**
		 *
		 * @since 1.0.0
		 * @var   array
		 */
		protected $query_args = array();

		protected $has_price_item = false;

		protected $current_min_price;
		protected $current_max_price;

		/**
		 * check if query has products use with analytic class
		 *
		 * @since 1.2.8
		 */
		protected $products_count = 0;

		/**
		 * @since 1.0.0, 1.1.3
		 */
		public function __construct( Pwf_Parse_Query_Vars $query_vars, $attributes = array() ) {
			self::$query_parse    = $query_vars;
			$this->filter_id      = $query_vars->get_filter_id();
			$this->has_price_item = $query_vars->has_price_item();

			if ( $this->has_price_item ) {
				$min_max_price           = $query_vars->get_current_min_max_price();
				$this->current_min_price = $min_max_price[0];
				$this->current_max_price = $min_max_price[1];
			}

			$this->attributes = $this->parse_attributes( $attributes );
			$this->query_args = $this->parse_query_args(); // build query taxonomy
		}

		protected function set_query_info( $results ) {
			$next_page = '';
			if ( $results->total_pages > $results->current_page ) {
				$next_page = $results->current_page + 1;
			}
			$data = array(
				'total'             => $results->total,
				'total_pages'       => $results->total_pages,
				'per_page'          => $results->per_page,
				'current_page'      => $results->current_page,
				'html_result_count' => $this->get_html_result_count( $results ),
				'pagination'        => $this->get_html_woocommerce_pagination( $results ),
				'next_page'         => $next_page,
			);

			$this->query_info = $data;
		}

		public function get_query_info() {
			return $this->query_info;
		}

		public function get_products_count() {
			return $this->products_count;
		}
		/**
		 * Get attributes.
		 *
		 * @since  1.0.0
		 * @return array
		 */
		public function get_attributes() {
			return $this->attributes;
		}

		/**
		 * Get products.
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function get_content() {
			return $this->product_loop();
		}

		protected function get_html_result_count( $results ) {
			$args = array(
				'total'    => $results->total,
				'per_page' => $results->per_page,
				'current'  => $results->current_page,
			);

			ob_start();
			wc_get_template( 'loop/result-count.php', $args );

			return apply_filters( 'pwf_html_result_count', ob_get_clean(), $this->filter_id, $args );
		}

		protected function get_html_woocommerce_pagination( $results ) {
			$args = array(
				'total'   => $results->total_pages,
				'current' => $results->current_page,
				'base'    => str_replace( 999999999, '%#%', '/page/%#%/' ),
			);

			ob_start();
			wc_get_template( 'loop/pagination.php', $args );

			return apply_filters( 'pwf_html_pagination', ob_get_clean(), $this->filter_id, $args );
		}

		/**
		 * Parse attributes.
		 *
		 * @since  1.0.0, 1.1.7, 1.2.8
		 * @param  array $attributes attributes.
		 * @return array
		 */
		protected function parse_attributes( $attributes ) {
			$filter_setting = self::$query_parse->get_filter_setting();

			if ( isset( $attributes['per_page'] ) && $attributes['per_page'] > 0 ) {
				$posts_per_page = absint( $attributes['per_page'] );
			} elseif ( ! empty( $filter_setting['posts_per_page'] ) ) {
				$posts_per_page = $filter_setting['posts_per_page'];
			} else {
				$posts_per_page = get_option( 'posts_per_page' );
			}

			$defaults = apply_filters(
				'pwf_woo_filter_loop_products_attributes',
				array(
					'orderby'        => '',
					'order'          => '',
					'page'           => 1,
					'paginate'       => true,
					'posts_per_page' => $posts_per_page,
					'author__in'     => array(),
					'columns'        => 4,
				),
				$this->filter_id
			);

			// Fix shortcode orderby
			foreach ( $attributes as $key => $value ) {
				if ( empty( $attributes[ $key ] ) ) {
					unset( $attributes[ $key ] );
				}
			}

			$attributes = wp_parse_args( $attributes, $defaults );

			return $attributes;
		}

		/**
		 * Parse query args.
		 *
		 * @since  1.0.0, 1.1.3, 1.6.4
		 * @return array
		 */
		protected function parse_query_args() {
			$query_args = array(
				'post_type'           => Pwf_Db_Utilities::get_post_type( 'product', $this->filter_id ),
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
				'fields'              => 'ids',
				'no_found_rows'       => false === wc_string_to_bool( $this->attributes['paginate'] ),
				'orderby'             => $this->attributes['orderby'],
			);

			if ( wc_string_to_bool( $this->attributes['paginate'] ) && 1 < $this->attributes['page'] ) {
				$query_args['paged'] = absint( $this->attributes['page'] );
			}

			$orderby_value         = explode( '-', $query_args['orderby'] );
			$orderby               = esc_attr( $orderby_value[0] );
			$order                 = ! empty( $orderby_value[1] ) ? $orderby_value[1] : strtoupper( $this->attributes['order'] );
			$query_args['orderby'] = $orderby;
			$query_args['order']   = $order;

			$ordering_args         = WC()->query->get_catalog_ordering_args( $query_args['orderby'], $query_args['order'] );
			$query_args['orderby'] = $ordering_args['orderby'];
			$query_args['order']   = $ordering_args['order'];
			if ( $ordering_args['meta_key'] ) {
				$query_args['meta_key'] = $ordering_args['meta_key'];
			}

			if ( ! empty( $this->attributes['author__in'] ) ) {
				$query_args['author__in'] = $this->attributes['author__in'];
			}

			$query_args['posts_per_page'] = intval( $this->attributes['posts_per_page'] );

			$query_args['tax_query']  = self::$query_parse->get_tax_query();
			$query_args['meta_query'] = self::$query_parse->get_meta_query();

			return $query_args;
		}

		/**
		 * Loop over found products.
		 *
		 * @since  1.0.0
		 * @return string
		 */
		protected function product_loop() {
			$products = $this->get_query_results();

			ob_start();

			if ( $products && $products->ids ) {

				$this->products_count = $products->total;

				// Setup the loop.
				$loop_args = apply_filters(
					'pwf_wc_setup_loop_args',
					array(
						'columns'      => $this->attributes['columns'],
						'name'         => 'pwf_filter',
						'is_shortcode' => false,
						'is_search'    => false,
						'is_paginated' => wc_string_to_bool( $this->attributes['paginate'] ),
						'total'        => $products->total,
						'total_pages'  => $products->total_pages,
						'per_page'     => $products->per_page,
						'current_page' => $products->current_page,
					),
					$this->filter_id
				);

				wc_setup_loop( $loop_args );

				do_action( 'pwf_before_shop_loop', $this->filter_id );

				foreach ( $products->ids as $product_id ) {
					$GLOBALS['post'] = get_post( $product_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

					setup_postdata( $GLOBALS['post'] );

					do_action( 'pwf_before_shop_loop_item', $this->filter_id, self::$query_parse );

					/**
					 * Hook: woocommerce_shop_loop.
					 */
					do_action( 'woocommerce_shop_loop' );

					$content_template = apply_filters( 'pwf_woo_filter_product_loop_template', array( 'content', 'product' ), $this->filter_id );

					wc_get_template_part( esc_attr( $content_template[0] ), esc_attr( $content_template[1] ) );

					do_action( 'pwf_after_shop_loop_item', $this->filter_id );
				}

				wp_reset_postdata();
				wc_reset_loop();

				do_action( 'pwf_after_shop_loop', $this->filter_id );
			} else {
				do_action( 'woocommerce_no_products_found' );
			}

			return ob_get_clean();
		}

		/**
		 * Join wc_product_meta_lookup to posts if not already joined.
		 *
		 * @param string $sql SQL join.
		 * @return string
		 */
		private function append_product_sorting_table_join( $sql ) {
			if ( ! strstr( $sql, 'wc_product_meta_lookup' ) ) {
				$sql .= Pwf_Db_Utilities::get_price_join_sql();
			}

			return $sql;
		}

		public function price_filter_post_clauses( $args, $wp_query ) {
			$args['join']   = $this->append_product_sorting_table_join( $args['join'] );
			$args['where'] .= Pwf_Db_Utilities::get_price_where_sql( $this->current_min_price, $this->current_max_price );

			return $args;
		}

		/**
		 * Run the query and return an array of data, including queried ids and pagination information.
		 *
		 * @since  1.0.0, 1.2.2
		 * @return object Object with the following props; ids, per_page, found_posts, max_num_pages, current_page
		 */
		public function get_query_results() {
			$selected_items   = self::$query_parse->selected_items();
			$this->query_args = apply_filters( 'pwf_woo_products_loop', $this->query_args, $this->filter_id, $selected_items, $this->attributes );

			do_action( 'pwf_woo_products_before_loop_query', $this->query_args, $this->filter_id, $selected_items, $this->attributes );

			$this->before_products_loop();

			$query     = new WP_Query( $this->query_args );
			$paginated = ! $query->get( 'no_found_rows' );
			$results   = (object) array(
				'ids'          => wp_parse_id_list( $query->posts ),
				'total'        => $paginated ? (int) $query->found_posts : count( $query->posts ),
				'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
				'per_page'     => (int) $query->get( 'posts_per_page' ),
				'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
			);

			$this->after_products_loop();

			do_action( 'pwf_woo_products_after_loop_query' );

			$this->set_query_info( $results );

			return $results;
		}

		/**
		 * filter wp_query before get posts
		 * @since 1.4.7
		 */
		protected function before_products_loop() {
			if ( $this->has_price_item ) {
				add_filter( 'posts_clauses', array( $this, 'price_filter_post_clauses' ), 10, 2 );
			}
		}

		/**
		 * remove custom filters that used by wp_query
		 * @since 1.4.7
		 */
		protected function after_products_loop() {
			if ( $this->has_price_item ) {
				remove_filter( 'posts_where', array( $this, 'price_filter_post_clauses' ), 10, 2 );
			}

			// Remove ordering query arguments which may have been added by get_catalog_ordering_args.
			WC()->query->remove_ordering_args();
		}
	}
}
