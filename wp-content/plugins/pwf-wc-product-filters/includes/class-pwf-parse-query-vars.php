<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Parse_Query_Vars' ) ) {

	class Pwf_Parse_Query_Vars {
		protected $filter_id;
		protected $filter_setting;
		protected $filter_items;
		protected $tax_query         = array();
		protected $meta_query        = array();
		protected $has_price_item    = false;
		protected $price_item_values = '';
		protected $selected_items    = array(); // hold url key as key and selected values for item selected
		protected $orderby           = '';
		protected $authors_id        = array();
		protected $custom_tax_query  = array();
		protected $custom_meta_query = array();
		protected $tax_query_items   = array();
		protected $filter_items_key  = array(); // this include date and price hold active filter items keys used in api
		protected $query_string      = array();
		protected $post_type         = 'product';

		/**
		 * @since 1.0.0, 1.1.0
		 */
		public function __construct( $filter_id, $selected_options_by_user ) {
			$this->filter_id = $filter_id;

			$meta = Pwf_Filter_Manager::get_filter_settings_and_items( $this->filter_id );

			if ( empty( $meta ) ) {
				return;
			}

			$this->filter_items   = $meta['items'];
			$this->filter_setting = $meta['setting'];
			$this->post_type      = $this->filter_setting['post_type'];

			do_action( 'pwf_init_parse_query', $this->filter_id, $meta );

			$this->parse_query_vars( $selected_options_by_user );
			$this->set_global_variables();
		}

		/**
		 * Set plugin global variables after integrate with query hook
		 *
		 * @since 1.1.0
		 */
		protected function set_global_variables() {
			$args = array(
				'filter_id'  => $this->filter_id,
				'post_type'  => $this->post_type,
				'query_vars' => $this,
			);

			Pwf_Filter_Manager::set_pwf_global_variables( $args );
		}

		/**
		 * Reterive post type
		 *
		 * @since 1.1.0
		 */
		public function get_post_type() {
			return $this->post_type;
		}

		public function get_filter_items_key() {
			return $this->filter_items_key;
		}
		public function get_filter_id() {
			return $this->filter_id;
		}

		public function get_tax_query() {
			return $this->tax_query;
		}

		public function get_meta_query() {
			return $this->meta_query;
		}

		public function get_filter_items() {
			return $this->filter_items;
		}

		public function get_filter_setting() {
			return $this->filter_setting;
		}

		public function selected_items() {
			return $this->selected_items;
		}

		public function has_price_item() {
			return $this->has_price_item;
		}

		public function get_current_min_max_price() {
			return $this->price_item_values;
		}

		public function get_products_orderby() {
			return $this->orderby;
		}

		/**
		 * Use to get tax_query for selected options by user on frontend
		 * Without add taxonomy archive page
		 */
		public function get_tax_query_filter_items() {
			return $this->tax_query_items;
		}

		/**
		 * Used to get tax query with product visibilty
		 * and add current archive product page like category, tag, taxonomy
		 */
		public function get_custom_tax_query() {
			return $this->custom_tax_query;
		}

		public function get_custom_meta_query() {
			return $this->custom_meta_query;
		}

		public function get_query_string() {
			return implode( '&', $this->query_string );
		}

		/**
		 * check if the current page is_tax()
		 * True is mean add current_taxonomy to to filter items ( parse_query )
		 * Usefule if the front-end user brwosing the categoty page so we counted terms for that page
		 *
		 * @since 1.0.0, 1.1.0
		 *
		 * @return array tax_query | Empty array
		 */
		private function get_current_page_tax_query() {
			$tax_query = array();
			if ( ! empty( $GLOBALS['pwf_main_query']['taxonomy_name'] ) && ! empty( $GLOBALS['pwf_main_query']['taxonomy_id'] ) ) {
				$tax_query[] = array(
					'taxonomy'         => sanitize_key( $GLOBALS['pwf_main_query']['taxonomy_name'] ),
					'field'            => 'term_id',
					'terms'            => absint( $GLOBALS['pwf_main_query']['taxonomy_id'] ),
					'operator'         => 'IN',
					'include_children' => true,
				);
			}

			return $tax_query;
		}

		private function append_custom_tax_query( $filter_tax_query ) {
			$product_visibility = array();
			if ( 'product' === $this->post_type ) {
				$product_visibility = Pwf_Woo_Utilities::get_product_visibility();
			}

			$current_shop_archive = $this->get_current_page_tax_query();

			$this->custom_tax_query = apply_filters( 'pwf_parse_taxonomy_query', array_merge( $product_visibility, $current_shop_archive ), $this->filter_id );

			$tax_query = array_merge( $this->custom_tax_query, $filter_tax_query );

			if ( ! isset( $tax_query['relation'] ) ) {
				$tax_query['relation'] = 'AND';
			}

			return $tax_query;
		}

		private function append_custom_meta_query( $filter_meta_query ) {
			$meta_query = array();
			$meta_query = apply_filters( 'pwf_parse_meta_query', $meta_query, $this->filter_id );
			if ( ! empty( $meta_query ) ) {
				$meta_query['relation'] = 'AND';
			}
			$this->custom_meta_query = $meta_query;
			$filter_meta_query       = array_merge( $this->custom_meta_query, $filter_meta_query );
			if ( ! empty( $filter_meta_query ) ) {
				if ( ! isset( $filter_meta_query['relation'] ) ) {
					$filter_meta_query['relation'] = 'AND';
				}
			}

			return $filter_meta_query;
		}

		/**
		 * parse query get by frontend user check selected options by frontend user
		 *
		 * set $meta_query
		 * set $tax_query
		 * set price_query
		 *
		 * @since 1.0.0, 1.1.0
		 */
		private function parse_query_vars( $selected_options_by_user ) {
			$query_vars   = $selected_options_by_user;
			$tax_query    = array();
			$meta_query   = array();
			$filter_items = Pwf_Filter_Manager::get_filter_items_without_columns( $this->filter_items );

			if ( ! empty( $query_vars ) ) {
				foreach ( $filter_items as $item ) {
					if ( ! isset( $item['url_key'] ) || empty( $item['url_key'] ) ) {
						continue;
					}

					// used if request come from api
					$is_price_item = false;
					if ( 'priceslider' === $item['item_type'] && 'two' === $item['price_url_format'] ) {
						if ( array_key_exists( $item['url_key_min_price'], $query_vars ) || array_key_exists( $item['url_key_max_price'], $query_vars ) ) {
							$is_price_item = true;
						}
					}

					if ( ( array_key_exists( $item['url_key'], $query_vars ) && ! empty( $query_vars[ $item['url_key'] ] ) ) ) {
						$url_key = $item['url_key'];
						if ( 'priceslider' !== $item['item_type'] ) {
							$values = $query_vars[ $url_key ];
						}

						if ( 'priceslider' === $item['item_type'] ) {
							if ( 'two' === $item['price_url_format'] ) {
								if ( array_key_exists( $item['url_key_min_price'], $query_vars ) || array_key_exists( $item['url_key_max_price'], $query_vars ) ) {
									$values    = array();
									$min_price = ( $query_vars[ $item['url_key_min_price'] ] ) ?? 0;
									$max_price = ( $query_vars[ $item['url_key_max_price'] ] ) ?? PHP_INT_MAX;
									$values    = array( $min_price, $max_price );
								} elseif ( ! is_array( $query_vars[ $url_key ] ) ) {
									$values = explode( ',', $query_vars[ $url_key ] );
								} else {
									$values = $query_vars[ $url_key ];
								}
							} else {
								if ( ! is_array( $query_vars[ $url_key ] ) ) {
									$values = explode( '-', $query_vars[ $url_key ] );
								} else {
									$values = $query_vars[ $url_key ];
								}
							}
						} elseif ( ! is_array( $values ) ) {
							$values = explode( ',', $query_vars[ $url_key ] );
						}

						/**
						 * check item price slider with dash split it into array
						 */
						$values = array_map( 'esc_attr', $values );

						if ( 'priceslider' === $item['item_type'] ) {
							if ( count( $values ) === 2 ) {
								$this->has_price_item             = true;
								$values                           = array_map( 'absint', $values );
								$this->price_item_values          = $values;
								$this->selected_items[ $url_key ] = array(
									'values' => $values,
									'type'   => 'price',
								);
								if ( 'two' === $item['price_url_format'] ) {
									array_push( $this->filter_items_key, $item['url_key_min_price'] );
									array_push( $this->filter_items_key, $item['url_key_max_price'] );
									$this->build_query_string( $item['url_key_min_price'], $values[0] );
									$this->build_query_string( $item['url_key_max_price'], $values[1] );
								} else {
									array_push( $this->filter_items_key, $url_key );
									$this->build_query_string( $url_key, $values[0] . '-' . $values[1] );
								}
							}
						} elseif ( 'rating' === $item['item_type'] ) {
							$terms           = array();
							$selected_values = $values;
							if ( 'on' === $item['up_text'] ) {
								$selected_values = explode( '-', $values[0] );
								$selected_values = range( $selected_values[0], $selected_values[1] );
								if ( 1 === count( $selected_values ) ) {
									continue;
								}
							}

							$selected_values = array_map( 'absint', $selected_values );
							$terms           = $this->get_rating_term_ids( $selected_values );

							$tax = array(
								'taxonomy'      => 'product_visibility',
								'field'         => 'term_taxonomy_id',
								'terms'         => $terms,
								'operator'      => 'IN',
								'rating_filter' => true,
							);
							array_push( $tax_query, $tax );

							if ( 'on' === $item['up_text'] ) {
								$values = array( esc_attr( $values[0] ) );
							} else {
								$values = array_map( 'absint', $values );
							}

							$this->selected_items[ $url_key ] = array(
								'values'   => $values,
								'term_ids' => $terms,
								'key'      => 'product_visibility',
								'type'     => 'rating',
							);
							array_push( $this->filter_items_key, $url_key );
							$this->build_query_string( $url_key, implode( ',', $values ) );
						} elseif ( 'orderby' === $item['source_of_options'] ) {
							$this->orderby                    = $values;
							$this->selected_items[ $url_key ] = array(
								'values' => $values,
								'type'   => 'orderby',
							);
							array_push( $this->filter_items_key, $url_key );
							$this->build_query_string( $url_key, implode( ',', $values ) );
						} elseif ( 'featured' === $item['source_of_options'] ) {
							if ( is_int( $values[0] ) ) {
								$values = array_map( 'absint', $values );
							} else {
								// if values come from url directly
								$product_visibility_term_ids = wc_get_product_visibility_term_ids();
								$values                      = array( absint( $product_visibility_term_ids['featured'] ) );
							}

							$tax = array(
								'taxonomy' => 'product_visibility',
								'field'    => 'term_taxonomy_id',
								'terms'    => $values,
								'operator' => 'IN',
							);

							$this->selected_items[ $url_key ] = array(
								'values' => $values,
								'key'    => 'product_visibility',
								'type'   => 'taxonomy',
							);
							array_push( $tax_query, $tax );
							array_push( $this->filter_items_key, $url_key );
							$this->build_query_string( $url_key, 'yes' );
						} else {
							$operator = 'IN';
							if ( isset( $item['query_type'] ) && 'or' !== $item['query_type'] ) {
								$operator = 'AND';
							}

							if ( 'category' === $item['source_of_options'] ) {
								$taxonomy = 'product_cat';
							} elseif ( 'attribute' === $item['source_of_options'] ) {
								$taxonomy = $item['item_source_attribute'];
							} elseif ( 'taxonomy' === $item['source_of_options'] ) {
								$taxonomy = $item['item_source_taxonomy'];
							} elseif ( 'tag' === $item['source_of_options'] ) {
								$taxonomy = 'product_tag';
							}

							$values = $this->check_is_multiselect( $item, $values );
							$values = array_map( 'absint', $this->convert_terms_slug_to_id( $values, $taxonomy ) );
							$tax    = array(
								'taxonomy'         => $taxonomy,
								'field'            => 'term_id',
								'terms'            => $values,
								'operator'         => $operator,
								'include_children' => true,
							);

							$this->selected_items[ $url_key ] = array(
								'values' => $values,
								'key'    => $taxonomy,
								'type'   => 'taxonomy',
							);
							array_push( $this->filter_items_key, $url_key );
							array_push( $tax_query, $tax );
							$this->build_query_string( $url_key, implode( ',', $this->convert_term_ids_to_slug( $values, $taxonomy ) ) );
						}
					}
				}
			}

			$this->tax_query_items = $tax_query;
			$this->tax_query       = $this->append_custom_tax_query( $tax_query );
			$this->meta_query      = $this->append_custom_meta_query( $meta_query );
		}

		/**
		 * Count number of values and return one or more depend on field type and multi select
		 * Reutn values array depend on filter type and multi select
		 */
		private function check_is_multiselect( $item, $values ) {
			if ( 'radiolist' === $item['item_type'] ) {
				// return array contain one value
				if ( is_array( $values ) ) {
					if ( 1 === count( $values ) ) {
						return $values;
					} else {
						return array( $values[0] );
					}
				} else {
					return array( $values );
				}
			}

			return $values;
		}

		private function convert_terms_slug_to_id( $terms, $taxonomy ) {
			$the_terms = array();
			if ( ! is_numeric( $terms[0] ) ) {
				foreach ( $terms as $term ) {
					$the_term = get_term_by( 'slug', $term, $taxonomy );
					if ( false !== $the_term ) {
						$the_terms[] = $the_term->term_id;
					}
				}
				$terms = $the_terms;
			} else {
				// check if the term slug is number not string useful for size taxonomy is number
				$check_term_exist = get_term_by( 'slug', $terms[0], $taxonomy );
				if ( false !== $check_term_exist ) {
					foreach ( $terms as $term ) {
						$the_term = get_term_by( 'slug', $term, $taxonomy );
						if ( false !== $the_term ) {
							$the_terms[] = $the_term->term_id;
						}
					}
					$terms = $the_terms;
				}
			}

			return $terms;
		}

		/**
		 * Get ids for the rating
		 * If meta or range slider is rating
		 * manipulate it as taxonomy for analytic data
		 *
		 * @since 1.1.0
		 *
		 * @return Array For rating term ID
		 */
		private function get_rating_term_ids( $values ) {
			$terms                    = array();
			$product_visibility_terms = wc_get_product_visibility_term_ids();
			foreach ( $values as $value ) {
				array_push( $terms, $product_visibility_terms[ 'rated-' . $value ] );
			}

			return $terms;
		}

		protected function build_query_string( $url_key, $value ) {
			$this->query_string[] = $url_key . '=' . $value;
		}

		protected function convert_term_ids_to_slug( $values, $taxonomy_name ) {
			$slugs = get_terms(
				array(
					'taxonomy' => $taxonomy_name,
					'include'  => $values,
					'fields'   => 'slugs',
				)
			);

			return $slugs;
		}
	}
}
