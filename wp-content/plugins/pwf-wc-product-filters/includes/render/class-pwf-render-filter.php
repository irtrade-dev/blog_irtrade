<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Render_Filter' ) ) {

	class Pwf_Render_Filter {

		protected static $query_parse;
		protected $current_min_price; // if price item is active
		protected $current_max_price; // if price item is active
		protected $filter_id;
		protected $filter_setting;
		protected $filter_items;
		protected $has_price_item    = false; // if price slider is active
		protected $tax_query         = array(); // this depend on user filter on front end
		protected $meta_query        = array();
		protected $selected_items    = array();
		protected $custom_tax_query  = array();
		protected $custom_meta_query = array();
		protected $post_type;

		protected $current_item = null;

		/**
		 * hold tax_query and meta_query when is_only_one_filter true
		 * @sine 1.1.0
		 */
		protected $one_filter_args = array();

		/**
		 * @since 1.1.0
		 */
		protected $author_query_sql        = '';
		protected $authors_where_query_sql = '';
		protected $price_query_sql         = array(
			'join'  => '',
			'where' => '',
		);

		protected $output_html = array();

		/**
		 * @since 1.1.0
		 */
		public function __construct( $filter_id, Pwf_Parse_Query_Vars $query_parse ) {
			$this->filter_id = absint( $filter_id );

			self::$query_parse = $query_parse;

			$this->set_query_parse( $query_parse );
		}

		public function get_html() {
			$output = '';

			if ( ! wp_doing_ajax() ) {
				$css       = '';
				$css_class = '';

				if ( ! empty( $this->filter_setting['cssclass'] ) ) {
					$css_class = ' ' . esc_attr( $this->filter_setting['cssclass'] );
				}

				if ( 'custom_query' === $this->filter_setting['filter_query_type'] ) {
					$css_class .= ' pwf-woo-shortcode';
				}

				if ( 'button' === $this->filter_setting['display_filter_as'] ) {
					$css = ' pwf-filter-as-button-container';
				}

				$output .= '<div class="pwf-filter-container' . $css . '">';

				if ( 'button' === $this->filter_setting['display_filter_as'] ) {
					if ( 'hide' === $this->filter_setting['filter_button_state'] ) {
						$css_class .= ' pwf-hidden';
						$css_btn    = ' pwf-btn-closed';
					} else {
						$css_btn = ' pwf-btn-opened';
					}

					$output .= '<div class="pwf-filter-as-button-header' . $css_btn . '">';
					$output .= '<div class="pwf-filter-as-button-title">';
					$output .= '<span class="pwf-filter-as-button-icon"></span>';
					$output .= '<span class="pwf-filter-as-button-text">' . esc_html__( 'Filter', 'pwf-woo-filter' ) . '</span>';
					$output .= '</div>';
					$output .= '<div class="button-more-wrap"></div>';
					$output .= '</div>';
				}

				$output .= '<div id="filter-id-' . $this->filter_id . '" class="pwf-woo-filter filter-id-' . $this->filter_id . $css_class . '">';
				$output .= '<div class="pwf-woo-filter-notes pwf-filter-notes-' . $this->filter_id . '"><div class="pwf-note-list"></div></div>';

				$inner_css = 'pwf-woo-filter-inner';
				if ( isset( $this->filter_setting['title_toggle_icon'] ) && 'arrow' === $this->filter_setting['title_toggle_icon'] ) {
					$inner_css .= ' title-toggle-icon-arrow';
				}
				if ( isset( $this->filter_setting['term_toggle_icon'] ) && 'arrow' === $this->filter_setting['term_toggle_icon'] ) {
					$inner_css .= ' term-toggle-icon-arrow';
				}

				$output .= '<div class="' . $inner_css . '">';
			}

			$this->get_filter_items_html( $this->filter_items );

			if ( ! empty( $this->output_html ) ) {
				$output .= implode( '', array_values( $this->output_html ) );
			}

			if ( ! wp_doing_ajax() && ! empty( $output ) ) {
				$output .= '</div>'; // End of pro-woo-filter inner
				$output .= '</div>'; // End of pro-woo-filter
				$output .= '</div>'; // End of pwf-filter-container

				add_action( 'wp_footer', array( $this, 'enqueue_script' ), 10 );
			}

			return $output;
		}

		protected static function check_item_require_js( $item ) {
			$scripts = array();
			if ( 'dropdownlist' === $item['item_type'] ) {
				if ( 'plugin' === $item['dropdown_style'] ) {
					wp_enqueue_script( 'select2' );
				}
			}

			if ( in_array( $item['item_type'], array( 'priceslider' ), true ) ) {
				wp_enqueue_script( 'nouislider' );
			}
		}

		public function enqueue_script() {
			$args = array(
				'filter_id'       => $this->filter_id,
				'filter_settings' => $this->filter_setting,
				'filter_items'    => $this->filter_items,
				'selected_items'  => $this->selected_items,
				'rule_hidden'     => '',
			);

			Pwf_Front_End_Ajax::enqueue_scripts( $args );
		}

		/**
		 * used in class Pwf_MAIN and Pwf_Api
		 * display filter post meta in API
		 *
		 * @return array
		 */
		public function get_filter_items_data() {
			return $this->prepare_filter_items_for_api( $this->filter_items );
		}

		/**
		 *
		 * @since 1.1.0
		 */
		protected function set_query_parse( Pwf_Parse_Query_Vars $query_parse ) {
			$this->filter_items      = $query_parse->get_filter_items();
			$this->filter_setting    = $query_parse->get_filter_setting();
			$this->tax_query         = $query_parse->get_tax_query();
			$this->meta_query        = $query_parse->get_meta_query();
			$this->selected_items    = $this->prepare_selected_items( $query_parse->selected_items() );
			$this->custom_tax_query  = $query_parse->get_custom_tax_query();
			$this->custom_meta_query = $query_parse->get_custom_meta_query();
			$this->post_type         = $this->filter_setting['post_type'] ?? 'product';

			$this->has_price_item = $query_parse->has_price_item();
			if ( $this->has_price_item ) {
				$min_max_price           = $query_parse->get_current_min_max_price();
				$this->current_min_price = $min_max_price[0];
				$this->current_max_price = $min_max_price[1];

				$this->price_query_sql['join']  = Pwf_Db_Utilities::get_price_join_sql();
				$this->price_query_sql['where'] = Pwf_Db_Utilities::get_price_where_sql( $this->current_min_price, $this->current_max_price );
			}
		}

		private function get_filter_items_html( $filter_items, $index = 0 ) {
			foreach ( $filter_items as $item ) {
				if ( 'column' === $item['item_type'] ) {
					if ( ! empty( $item['children'] ) ) {
						$width = absint( $item['width'] ) ?? 100;
						$unit  = $item['width_unit'];
						$css   = $item['css_class'];
						if ( ! empty( $css ) ) {
							$css = ' ' . $css;
						}
						$width = ' style="width:' . absint( $width ) . esc_attr( $unit ) . '"';

						$this->output_html[ 'a' . $index ] = '<div class="pwf-column pwf-column-' . $index . esc_attr( $css ) . '"' . $width . '>';
						$this->output_html[ 'b' . $index ] = $this->get_filter_items_html( $item['children'], ++$index );
						$this->output_html[ 'c' . $index ] = '</div>';
					}
				} else {
					$this->current_item = $item;
					self::check_item_require_js( $item );
					$url_key = ( $item['url_key'] ) ?? "d-{$index}";

					$this->output_html[ $url_key ] = $this->get_filter_item_html( $index );
				}

				$this->current_item = null;
				$index++;
			}
		}

		/**
		 * @since 1.1.0
		 */
		protected function get_filter_item_html( $index = 0 ) {

			if ( null === $this->current_item ) {
				return;
			}

			$args  = array(); // used to add more data like min and max price
			$item  = $this->current_item;
			$terms = $this->get_filter_item_data_display();

			if ( 'priceslider' === $item['item_type'] ) {
				$item['min_max_price'] = $terms;
			}

			$render_item = new Pwf_Render_Filter_Fields( $item, $index, $terms, $this->get_selected_values( $item ), $args );

			return $render_item->get_html_template();
		}

		private function prepare_filter_items_for_api( $filter_items ) {
			$result = array();

			foreach ( $filter_items as $key => $item ) {
				if ( 'column' === $item['item_type'] ) {
					if ( ! empty( $item['children'] ) ) {
						if ( 'on' === $this->filter_setting['api_remove_columns_layout'] ) {
							$childern = $this->prepare_filter_items_for_api( $item['children'] );
							$result   = array_merge( $result, $childern );
						} else {
							$item['children'] = $this->prepare_filter_items_for_api( $item['children'] );
							array_push( $result, $item );
						}
					}
				} else {
					$this->current_item    = $item;
					$item['data_display']  = $this->get_filter_item_data_display();
					$item['data_selected'] = $this->get_selected_values( $item );

					array_push( $result, $item );
					$this->current_item = null;
				}
			}

			return $result;
		}

		/**
		 * since 1.1.0
		 */
		private function get_filter_item_data_display() {
			$terms = array();
			$item  = $this->current_item;

			switch ( $item['item_type'] ) {
				case 'priceslider':
					$terms = $this->get_min_max_price();
					break;
				case 'rating':
					$terms = $this->get_rating();
					break;
				default:
					$fields = array( 'checkboxlist', 'radiolist', 'dropdownlist' );
					if ( in_array( $item['item_type'], $fields, true ) ) {
						$terms = $this->get_filter_item_terms();

						if ( 'radiolist' === $item['item_type'] || 'dropdownlist' === $item['item_type'] ) {
							if ( ! empty( $terms ) && 'orderby' !== $item['source_of_options'] ) {
								$terms = array_merge( $this->get_show_all_text(), $terms );
							}
						}
					}
			}

			return $terms;
		}

		/**
		 * Get selected values for current item
		 *
		 * @since 1.1.0
		 *
		 * @return array
		 */
		protected function get_selected_values( $item ) {
			$selected = array();
			if ( isset( $item['url_key'] ) ) {
				if ( array_key_exists( $item['url_key'], $this->selected_items ) ) {
					$selected = $this->selected_items[ $item['url_key'] ];
				}
			}
			return $selected;
		}

		/**
		 * check if only one filter item is active
		 * Used to display all elements in this filter item with count
		 *
		 * @return bool
		 */
		private function is_only_one_filter_item_active() {
			if ( empty( $this->selected_items ) || $this->has_price_item ) {
				return false;
			}

			/**
			 * get first key on the selected_items
			 * this code replace array_key_first( $this->selected_items )
			 */
			$selected_items = $this->selected_items;
			reset( $selected_items );
			$first_key = key( $selected_items );

			if ( 1 === count( $this->selected_items ) && $first_key === $this->current_item['url_key'] ) {
				return true;
			}

			return false;
		}

		/**
		 * @since 1.0.0, 1.1.3
		 */
		protected function get_filter_item_terms() {
			if ( null === $this->current_item ) {
				return;
			}

			$args = $this->get_database_query_args();
			if ( empty( $args ) ) {
				return array();
			}

			/**
			 * only display all terms for current term
			 * if there is one item filter
			 * and this item not price filter
			 */
			$this->before_query();

			$terms = $this->get_counted_terms( $args );

			$this->after_query();

			return $terms;
		}

		/**
		 * get database query for count terms and get terms
		 */
		protected function get_database_query_args() {
			if ( null === $this->current_item ) {
				return array();
			}

			$args              = array();
			$return_empty      = false;
			$item              = $this->current_item;
			$is_exclude        = false;
			$args['orderby']   = $item['order_by'] ?? '';
			$item_display      = $item['item_display'] ?? '';
			$source_of_options = $item['source_of_options'];

			switch ( $source_of_options ) {
				case 'category':
					$args['taxonomy'] = 'product_cat';
					break;
				case 'attribute':
					$args['taxonomy'] = $item['item_source_attribute'];
					break;
				case 'tag':
					$args['taxonomy'] = 'product_tag';
					break;
				case 'taxonomy':
					$args['taxonomy'] = $item['item_source_taxonomy'];
					break;
			}

			if ( in_array( $source_of_options, array( 'category', 'taxonomy' ), true ) && is_taxonomy_hierarchical( $args['taxonomy'] ) ) {
				/**
				* Related to current Page Taxonomy Tag, Taxonomy, category & ex clothing
				*/
				$taxonomy_name = $GLOBALS['pwf_main_query']['taxonomy_name'];
				$taxonomy_id   = $GLOBALS['pwf_main_query']['taxonomy_id'];

				if ( 'category' === $source_of_options ) {
					$item_source = esc_attr( $item['item_source_category'] );
				} else {
					$item_source = esc_attr( $item['item_source_taxonomy_sub'] );
				}

				if ( 'all' === $item_display && 'all' === $item_source ) {
					if ( $taxonomy_name === $args['taxonomy'] ) {
						$args['child_of'] = $taxonomy_id;
					}
				} elseif ( 'all' === $item_display && 'all' !== $item_source ) {
					if ( $taxonomy_name === $args['taxonomy'] ) {
						if ( absint( $item_source ) === $taxonomy_id ) {
							$args['child_of'] = $item_source;
						} else {
							$return_empty = true;
						}
					} else {
						$args['child_of'] = $item_source;
					}
				} elseif ( 'parent' === $item_display ) {
					if ( $taxonomy_name === $args['taxonomy'] ) {
						if ( 'all' === $item_source || absint( $item_source ) === $taxonomy_id ) {
							$args['parent'] = $taxonomy_id;
						} else {
							$return_empty = true;
						}
					} else {
						if ( 'all' === $item_source ) {
							$args['parent'] = 0;
						} else {
							$args['parent'] = $item_source;
						}
					}
				} elseif ( 'selected' === $item_display && ! empty( $item['include'] ) ) {
					$item_includes = array_map( 'absint', $item['include'] );
					if ( $taxonomy_name === $args['taxonomy'] ) {
						$include_ids = array();
						$term_ids    = get_terms(
							array(
								'taxonomy'   => esc_attr( $args['taxonomy'] ),
								'hide_empty' => false,
								'fields'     => 'ids',
								'child_of'   => absint( $taxonomy_id ),
							)
						);
						foreach ( $term_ids as $id ) {
							if ( in_array( $id, $item_includes, true ) ) {
								array_push( $include_ids, $id );
							}
						}

						if ( empty( $include_ids ) ) {
							$return_empty = true;
						} else {
							$args['include'] = $include_ids;
						}
					} else {
						$args['include'] = $item_includes;
					}
				} elseif ( 'except' === $item_display && ! empty( $item['exclude'] ) ) {
					$item['exclude'] = array_map( 'absint', $item['exclude'] );
					if ( $taxonomy_name === $args['taxonomy'] ) {
						$term_ids = get_terms(
							array(
								'taxonomy'   => $args['taxonomy'],
								'hide_empty' => false,
								'fields'     => 'ids',
								'child_of'   => absint( $taxonomy_id ),
							)
						);
						foreach ( $term_ids as $key => $term_id ) {
							if ( in_array( $term_id, $item['exclude'], true ) ) {
								unset( $term_ids[ $key ] );
							}
						}
						if ( empty( $term_ids ) ) {
							$return_empty = true;
						} else {
							$args['include'] = $term_ids;
						}
					} else {
						// If not work good
						$term_args = array(
							'taxonomy'   => $args['taxonomy'],
							'hide_empty' => false,
							'fields'     => 'ids',
						);
						if ( 'all' !== $item_source ) {
							$term_args['child_of'] = $item_source;
						}
						$term_ids = get_terms( $term_args );
						foreach ( $term_ids as $key => $term_id ) {
							if ( in_array( $term_id, $item['exclude'], true ) ) {
								unset( $term_ids[ $key ] );
							}
						}
						$args['include'] = $term_ids;
					}
				}
			} else {
				if ( 'selected' === $item_display && ! empty( $item['include'] ) ) {
					$args['include'] = array_map( 'absint', $item['include'] );
				} elseif ( 'except' === $item_display && ! empty( $item['exclude'] ) ) {
					$exclude_ids = array_map( 'absint', $item['exclude'] );

					$term_ids = get_terms(
						array(
							'taxonomy'   => $args['taxonomy'],
							'hide_empty' => false,
							'fields'     => 'ids',
						)
					);
					foreach ( $term_ids as $key => $term_id ) {
						if ( in_array( $term_id, $exclude_ids, true ) ) {
							unset( $term_ids[ $key ] );
						}
					}
					$args['include'] = $term_ids;
				}
			}

			if ( $return_empty ) {
				$args = array();
			}

			return $args;
		}

		protected function get_show_all_text() {
			$show_all_text = esc_html__( 'Show all', 'pwf-woo-filter' );

			$item = $this->current_item;
			$type = $item['source_of_options'];
			if ( 'stock_status' === $type || 'orderby' === $type || 'meta' === $type || 'author' === $type ) {

				$args = array(
					'slug'  => 'showall',
					'label' => $show_all_text,
					'value' => 'showall',
					'count' => 0,
				);
				if ( 'author' === $type ) {
					$args['ID']            = 'showall';
					$args['user_nicename'] = $args['slug'];
					$args['display_name']  = $args['label'];
				}

				return array( $args );
			} else {
				return array(
					(object) array(
						'term_id'          => 'showall',
						'slug'             => 'showall',
						'parent'           => 0,
						'name'             => $show_all_text,
						'count'            => '',
						'term_taxonomy_id' => -1,
					),
				);
			}
		}

		/**
		 * @since 1.1.0
		 */
		protected function get_min_max_price() {
			$this->before_only_one_filter();
			$min_max = (array) $this->get_filtered_price();
			$this->after_only_one_filter();

			$active_price          = (array) $this->get_filtered_price();
			$min_max['min_price']  = floatval( $min_max['min_price'] );
			$min_max['max_price']  = floatval( $min_max['max_price'] );
			$min_max['active_min'] = floatval( $active_price['min_price'] );
			$min_max['active_max'] = floatval( $active_price['max_price'] );

			return $min_max;
		}

		/**
		 * Get filtered min and max price for current products.
		 *
		 * @since 1.1.0
		 */
		protected function get_filtered_price() {
			global $wpdb;

			$query           = array();
			$query['select'] = 'SELECT MIN( min_price ) as min_price, MAX( max_price ) as max_price';
			$query['from']   = "FROM {$wpdb->wc_product_meta_lookup}";
			$query['where']  = 'WHERE product_id IN (' . $this->get_sub_query_for_posts() . ')';

			$query   = apply_filters( 'pwf_woo_price_filter_sql', $query, $this->filter_id, self::$query_parse );
			$query   = implode( ' ', $query );
			$results = $wpdb->get_row( $query ); // @codingStandardsIgnoreLine

			return $results;
		}

		/**
		 * Special count for terms depend on filter items
		 *
		 * @since 1.1.0
		 */
		protected function get_counted_terms( $query_args ) {
			if ( empty( $query_args ) ) {
				return array();
			}
			$source_of_options = $this->current_item['source_of_options'];

			$defaults = array(
				'hide_empty' => false,
				'orderby'    => 'name',
				'count'      => true,
			);

			$query_args = wp_parse_args( $query_args, $defaults );

			if ( 'order' === $query_args['orderby'] ) {
				unset( $query_args['orderby'] );
			}

			/*
			 * Using is_taxonomy_hierarchical is more powferfull than filter_item['display_hierarchical']
			 * This get exactly parent term count
			 */
			$terms = get_terms( $query_args );
			if ( is_wp_error( $terms ) ) {
				return array();
			}

			$term_ids    = wp_list_pluck( $terms, 'term_id' );
			$term_counts = array();
			if ( is_taxonomy_hierarchical( $query_args['taxonomy'] ) ) {
				$query_args['fields'] = 'ids'; // used inside loop only
				$t_has_children       = array();
				$t_no_children        = array();
				foreach ( $term_ids as $id ) {
					$children = get_term_children( $id, $query_args['taxonomy'] );
					if ( ! empty( $children ) ) {
						// this require special count
						$t_has_children[ $id ] = $children;
					} else {
						array_push( $t_no_children, $id );
					}
				}
				unset( $query_args['fields'] );

				$parent_count   = array();
				$children_count = array();
				if ( ! empty( $t_has_children ) ) {
					foreach ( $t_has_children as $key => $children_term ) {
						array_push( $children_term, absint( $key ) );
						$get_counted          = $this->get_filtered_term_product_count( $children_term, $query_args['taxonomy'] );
						$parent_count[ $key ] = $get_counted;
					}
				}

				if ( ! empty( $t_no_children ) ) {
					$children_count = $this->get_filtered_term_product_counts( $t_no_children, $query_args['taxonomy'] );
				}

				$term_counts = $parent_count + $children_count;
			} else {
				$term_counts = $this->get_filtered_term_product_counts( $term_ids, $query_args['taxonomy'] );
			}

			foreach ( $terms as $key => $term ) {
				$terms[ $key ]->count = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;
			}

			if ( isset( $query_args['orderby'] ) && 'count' === $query_args['orderby'] ) {
				usort( $terms, array( $this, 'sort_term_counts' ) );
			}

			return $terms;
		}

		private function sort_term_counts( $term1, $term2 ) {
			return $term1->count < $term2->count;
		}

		/**
		* Count products within certain terms, taking the main WP query into consideration.
		*
		* This query allows counts to be generated based on the viewed products, not all products.
		*
		* see class-wc-widget-layered-nav
		* @since 1.1.0
		*
		* @param  array  $term_ids Term IDs.
		* @param  string $taxonomy Taxonomy.
		* @param  string $query_type Query Type.
		* @return array
		*/
		protected function get_filtered_term_product_counts( $term_ids, $taxonomy ) {
			global $wpdb;

			$meta_query     = new WP_Meta_Query( $this->meta_query );
			$tax_query      = self::get_tax_query_class( $this->tax_query, $this->post_type );
			$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

			// Generate query.
			$query           = array();
			$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
			$query['from']   = "FROM {$wpdb->posts}";
			$query['join']   = "
				INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
				INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
				INNER JOIN {$wpdb->terms} AS terms USING( term_id )
				" . $this->price_query_sql['join'] . $tax_query_sql['join'] . $meta_query_sql['join'];

			$query['where'] =
				Pwf_Db_Utilities::get_post_type_where_sql( $this->post_type, $this->filter_id )
				. $this->append_where_sql_to_count_query()
				. $tax_query_sql['where'] . $meta_query_sql['where']
				. ' AND terms.term_id IN (' . implode( ',', array_map( 'absint', $term_ids ) ) . ')';

			$query['group_by'] = 'GROUP BY terms.term_id';

			$query   = apply_filters( 'pwf_woo_get_filter_term_product_counts_query', $query, $this->filter_id, self::$query_parse );
			$query   = implode( ' ', $query );
			$results = $wpdb->get_results( $query, ARRAY_A ); // @codingStandardsIgnoreLine
			$counts  = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );

			return $counts;
		}

		// only count term with childrens
		/**
		 * @since 1.1.0
		 */
		protected function get_filtered_term_product_count( $term_ids, $taxonomy ) {
			global $wpdb;

			$meta_query     = new WP_Meta_Query( $this->meta_query );
			$tax_query      = self::get_tax_query_class( $this->tax_query, $this->post_type );
			$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

			// Generate query.
			$query           = array();
			$query['select'] = "SELECT count( DISTINCT {$wpdb->posts}.ID )";
			$query['from']   = "FROM {$wpdb->posts}";
			$query['join']   = "
				INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
				INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
				INNER JOIN {$wpdb->terms} AS terms USING( term_id )
				" . $this->price_query_sql['join'] . $tax_query_sql['join'] . $meta_query_sql['join'];

			$query['where'] =
				Pwf_Db_Utilities::get_post_type_where_sql( $this->post_type, $this->filter_id )
				. $this->append_where_sql_to_count_query()
				. $tax_query_sql['where'] . $meta_query_sql['where']
				. ' AND terms.term_id IN (' . implode( ',', array_map( 'absint', $term_ids ) ) . ')';

			$query   = apply_filters( 'pwf_woo_get_filter_term_product_sum_query', $query, $this->filter_id, self::$query_parse );
			$query   = implode( ' ', $query );
			$results = $wpdb->get_var( $query ); // @codingStandardsIgnoreLine

			return $results;
		}

		/**
		 * Use to SELECT ID from posts
		 *
		 * @since 1.1.0
		 *
		 * @return string sql
		 */
		public function get_sub_query_for_posts() {
			global $wpdb;

			$meta_query     = new WP_Meta_Query( $this->meta_query );
			$tax_query      = self::get_tax_query_class( $this->tax_query, $this->post_type );
			$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

			$sql = "
					SELECT ID FROM {$wpdb->posts} "
					. $tax_query_sql['join'] . $meta_query_sql['join'] . $this->price_query_sql['join']
					. Pwf_Db_Utilities::get_post_type_where_sql( $this->post_type, $this->filter_id )
					. $this->append_where_sql_to_count_query()
					. $tax_query_sql['where']
					. $meta_query_sql['where'];

			$sql = apply_filters( 'pwf_woo_sub_query_for_posts', $sql, $this->filter_id, self::$query_parse );

			return $sql;
		}

		/**
		 *
		 * @since 1.2.2
		 */
		protected function get_rating() {

			$product_visibility_terms = wc_get_product_visibility_term_ids();

			$rating = array(
				array(
					'label' => esc_html__( 'Rate 5', 'pwf-woo-filter' ),
				),
				array(
					'label' => esc_html__( 'Rate 4', 'pwf-woo-filter' ),
				),
				array(
					'label' => esc_html__( 'Rate 3', 'pwf-woo-filter' ),
				),
				array(
					'label' => esc_html__( 'Rate 2', 'pwf-woo-filter' ),
				),
				array(
					'label' => esc_html__( 'Rate 1', 'pwf-woo-filter' ),
				),
			);

			for ( $index = 0, $i = 5; $i >= 1; $index++, $i-- ) {
				$rating[ $index ]['rate']  = $i;
				$rating[ $index ]['count'] = 0;

				if ( 'on' !== $this->current_item['up_text'] ) {
					$rating[ $index ]['value']   = $i;
					$rating[ $index ]['slug']    = $i;
					$rating[ $index ]['term_id'] = $product_visibility_terms[ 'rated-' . $i ];
				} else {
					$rate_terms   = array();
					$rating_index = $i;
					for ( $rating_index; $rating_index <= 5; $rating_index++ ) {
						array_push( $rate_terms, $product_visibility_terms[ 'rated-' . $rating_index ] );
					}

					$rating[ $index ]['term_id'] = $rate_terms;
					$rating[ $index ]['slug']    = $i . '-' . 5;
					$rating[ $index ]['value']   = $i . '-' . 5;
				}
			}

			if ( 'on' === $this->current_item['up_text'] ) {
				array_shift( $rating );
			}

			$this->before_query();

			if ( 'on' === $this->current_item['up_text'] ) {
				foreach ( $rating as $key => $rate ) {
					$rating[ $key ]['count'] = $this->get_filtered_term_product_count( $rate['term_id'], 'rating-' . $key . '-up-text' );
				}
			} else {
				$rate_values = array_column( $rating, 'term_id' );
				$terms       = $this->get_filtered_term_product_counts( $rate_values, 'rating' );

				foreach ( $rating as $key => $rate ) {
					if ( isset( $terms[ $rate['term_id'] ] ) ) {
						$rating[ $key ]['count'] = $terms[ $rate['term_id'] ];
					}
				}
			}

			$this->after_query();

			return $rating;
		}

		/**
		 * @since 1.1.0
		 */
		protected function before_query() {
			if ( $this->is_only_one_filter_item_active() ) {
				$this->before_only_one_filter();
			}
		}

		/**
		 * @since 1.1.0
		 */
		protected function after_query() {
			if ( $this->is_only_one_filter_item_active() ) {
				$this->after_only_one_filter();
			}
		}
		/**
		 * @since 1.1.0
		 */
		protected function before_only_one_filter() {
			$this->one_filter_args['tax_query']        = $this->tax_query;
			$this->one_filter_args['meta_query']       = $this->meta_query;
			$this->one_filter_args['price_query_sql']  = $this->price_query_sql;
			$this->one_filter_args['author_query_sql'] = $this->author_query_sql;

			$this->tax_query        = $this->custom_tax_query;
			$this->meta_query       = $this->custom_meta_query;
			$this->author_query_sql = '';
			$this->price_query_sql  = array(
				'join'  => ' ',
				'where' => ' ',
			);
		}

		/**
		 * @since 1.1.0
		 */
		protected function after_only_one_filter() {
			$this->tax_query        = $this->one_filter_args['tax_query'];
			$this->meta_query       = $this->one_filter_args['meta_query'];
			$this->price_query_sql  = $this->one_filter_args['price_query_sql'];
			$this->author_query_sql = $this->one_filter_args['author_query_sql'];
			$this->one_filter_args  = array();
		}

		/**
		 * Remove unused code in the selected items that used with analytic class
		 * @param Array contain selected items
		 * @since 1.1.0
		 *
		 * @return Array url_key => values
		 */
		protected function prepare_selected_items( $selected_items ) {
			$selected = array();
			if ( ! empty( $selected_items ) ) {
				foreach ( $selected_items as $key => $item ) {
					$selected[ $key ] = $item['values'];
				}
			}

			return $selected;
		}

		/**
		 * Group append sql statements in one place and exclude sql clause that doesn't require foreach DB query
		 * we can path one of them to exclude on_sale, variations, search, date, author, price
		 *
		 * @param array $exclude_sql contain what when need to exclude
		 * @since 1.1.0
		 *
		 * @return string
		 */
		protected function append_where_sql_to_count_query( $exclude_sql = array( 'none' ) ) {
			$sql = '';
			if ( ! in_array( 'price', $exclude_sql, true ) ) {
				$sql .= $this->price_query_sql['where'];
			}

			return $sql;
		}

		/**
		 * Set All new tax Query at one place
		 * Used to check if the site is multi language
		 *
		 * @param array $tax_query
		 * @param string $post_type
		 *
		 * @since 1.1.0
		 *
		 * @return Object new WP_Tax_Query
		 */
		public static function get_tax_query_class( $tax_query, $post_type ) {
			$tax_query = new WP_Tax_Query( $tax_query );
			return $tax_query;
		}
	}
}
