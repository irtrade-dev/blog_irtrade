<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Analytic_Page' ) ) {

	class Pwf_Analytic_Page {

		private static $meta_data       = null;
		private static $meta_range_data = null;

		private $registered_terms;
		private $registered_terms_id;
		protected $terms_to_display = array();

		public function __construct() {}

		public static function admin_settings_enqueue_styles() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style( 'pwf-open-sans', 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&display=swap' );
			wp_enqueue_style( 'jquery-ui', PWF_WOO_FILTER_URI . '/assets/css/frontend/jquery-ui/jquery-ui.min.css', '', '1.12.1' );
			wp_enqueue_style( 'prowoofilteradmin', PWF_WOO_FILTER_URI . '/assets/css/admin/admin' . $suffix . '.css', '', PWF_WOO_FILTER_VER );
			wp_enqueue_style( 'pwf-admin-analytic', PWF_WOO_FILTER_URI . '/assets/css/admin/analytic' . $suffix . '.css', '', PWF_WOO_FILTER_VER );
		}

		public function admin_settings_enqueue_scripts() {
			wp_register_script( 'apexcharts', PWF_WOO_FILTER_URI . '/assets/js/admin/apexcharts.min.js', '', '3.26.0', true );
			wp_enqueue_script( 'pwf-woo-analytic', PWF_WOO_FILTER_URI . '/assets/js/admin/analytic.js', array( 'jquery', 'jquery-ui-datepicker', 'apexcharts' ), PWF_WOO_FILTER_VER, true );

			$localize_args = array(
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( 'pwf-woocommerce-analytic-nonce' ),
				'analytic_data' => wp_json_encode( $this->add_analytic_data_to_js() ),
			);

			wp_localize_script( 'pwf-woo-analytic', 'pwf_woocommerce_analytic', $localize_args );
		}

		public function render_analytic_page() {
			echo '<div class="wrap"><div class="pwf-layout">';
			echo '<div class="pwf-layout__header">';
			echo '<h1 class="pwf-layout__header-heading">' . esc_html__( 'Overview', 'pwf-woo-filter' ) . '</h1>';
			echo '</div>';
			echo '<div class="pwf-layout-primary">';
			self::get_html_filter_component();
			echo '<div class="pwf-wrap-dashboards"><div class="pwf-general-dash"></div><div class="pwf-dashboards"></div></div>';
			echo '<div class="pwf-anlytic-page-pro-message"></div>';
			echo '</div>';
			echo '</div></div>';
		}

		protected function get_html_filter_component() {
			Pwf_Analytic_Query::check_db_tables();

			echo '<div class="pwf-component-filters">';
			echo wp_kses_post( $this->get_html_date_range_component() );
			echo wp_kses_post( $this->get_html_filter_posts_component() );
			echo wp_kses_post( $this->get_html_filter_button() );

			echo '</div>';
		}

		protected function get_html_date_range_component() {
			$analytic_query = Pwf_Admin_Analytic_Query::get_instance();
			$current_time   = current_time( 'timestamp' ); // @codingStandardsIgnoreLine
			$min_date       = $analytic_query->get_min_date();
			$max_date       = gmdate( 'Y-m-d', $current_time );
			$current_day    = gmdate( 'd', $current_time );

			$date_text = esc_html__( 'Month to date', 'pwf-woo-filter' ) . ' (';
			if ( 01 === $current_day ) {
				$date_text .= gmdate( 'M', $current_time ) . ' 1, ' . gmdate( 'YY', $current_time );
			} else {
				$date_text .= gmdate( 'M', $current_time ) . ' 1 - ' . $current_day . ', ' . gmdate( 'Y', $current_time );
			}
			$date_text .= ')';

			if ( empty( $min_date ) ) {
				return;
			} else {
				$min_date = new DateTime( $min_date );
				$min_date = $min_date->format( 'Y-m-d' );
			}

			$output  = '<div class="pwf-filters-filter pwf-field-filter-date-range">';
			$output .= '<span class="pwf-filter-label">' . esc_html__( 'Date Range', 'pwf-woo-filter' ) . ':</span>';

			$output .= '<div class="pwf-filter-content">';

			$output .= '<div class="pwf-btn-range-date">';

			$output .= '<div class="pwf-range-date-lable"><span class="label-text">' . $date_text . '</span></div>';
			$output .= '</div>';

			$output .= '<div class="pwf-date-range-popover"><div class="popover-inner">';
			$output .= '<div id="pwf-input-date-range" class="pwf-input-date-range" data-min-date="' . esc_attr( $min_date ) . '" data-max-date="' . esc_attr( $max_date ) . '">';
			$output .= '<input type="text" id="pwf-date-from" class="pwf-date-from" value="" placeholder="mm-dd-yyyy"/>';
			$output .= '<input type="text" id="pwf-date-to" class="pwf-date-to" value="" placeholder="mm-dd-yyyy"/>';
			$output .= '</div>';
			$output .= '<div class="date-range-filter-button"><input type="submit" name="pwf_filter_date" id="pwf-filter-date" class="button pwf-filter-date-btn" value="Filter - PRO" disabled></div>';
			$output .= '</div></div>';

			$output .= '</div></div>';

			return $output;
		}

		protected function get_html_filter_posts_component() {
			$filter_posts = self::get_filter_posts();
			if ( empty( $filter_posts ) ) {
				return '';
			}

			$output  = '<div class="pwf-filters-filter pwf-field-filter-posts">';
			$output .= '<span class="pwf-filter-label">' . esc_html__( 'Filter posts', 'pwf-woo-filter' ) . ':</span>';
			$output .= '<div class="pwf-filter-content"><select name="pwf_filter_posts_id" class="pwf-select pwf-filters-post-id">';
			$output .= '<option value="all">' . esc_html__( 'All', 'pwf-woo-filter' ) . '</option>';
			foreach ( $filter_posts as $post ) {
				$output .= '<option value="' . absint( $post['id'] ) . '" disabled>' . esc_attr( $post['title'] ) . '- PRO</option>';
			}
			$output .= '</select></div></div>';

			return $output;
		}

		public function get_html_filter_button() {
			$output  = '<div class="pwf-filters-filter pwf-field-filter-button">';
			$output .= '<input type="submit" name="pwf_filter" id="pwf-filter-button" class="button pwf-filter-button" value="Filter - PRO" disabled>';
			$output .= '</div>';

			return $output;
		}

		public function add_analytic_data_to_js() {
			$atts = array( 'date' => $this->get_month_to_date() );

			return $this->get_analytic_data( $atts );
		}

		/**
		 * Get date start from first day on the month to current day
		 *
		 * @return Array
		 */
		protected function get_month_to_date() {
			$first_date = new DateTime( 'first day of this month' );
			$date       = array(
				$first_date->format( 'Y-m-d' ),
				gmdate( 'Y-m-d', current_time( 'timestamp' ) ), // @codingStandardsIgnoreLine
			);

			return $date;
		}

		protected function get_analytic_data( array $atts ) {
			$analytic_query = Pwf_Admin_Analytic_Query::get_instance();

			if ( ! isset( $atts['date'] ) || empty( $atts['date'] ) ) {
				$atts['date'] = $this->get_month_to_date();
			}

			$group_filter_ids = $analytic_query->get_filter_group_ids( $atts );

			if ( empty( $group_filter_ids ) ) {
				$results = array(
					'counted_filters'  => '',
					'items'            => '',
					'filters_used_per' => '',
					'no_data_text'     => esc_html__( 'No data for the selected filter', 'pwf-woo-filter' ),
				);
			} else {
				$results = array(
					'counted_filters'  => count( $group_filter_ids ),
					'items'            => $this->get_counted_terms( $group_filter_ids ),
					'filters_used_per' => $this->get_number_of_filters_used_by_clients( $atts['date'] ),
					'terms_to_display' => $this->terms_to_display,
				);
			}

			return $results;
		}

		protected function get_number_of_filters_used_by_clients( $date ) {
			$results        = array();
			$analytic_query = Pwf_Admin_Analytic_Query::get_instance();
			$filters_used   = $analytic_query->get_counted_filters_used_by_clients( $date );

			if ( ! empty( $filters_used ) ) {
				$first_date  = DateTime::createFromFormat( 'Y-m-d', $date[0] );
				$second_date = DateTime::createFromFormat( 'Y-m-d', $date[1] );

				if ( $first_date->format( 'Y-m' ) === $second_date->format( 'Y-m' ) ) {
					$month    = $first_date->format( 'M' );
					$num_dyas = cal_days_in_month( CAL_GREGORIAN, $first_date->format( 'm' ), $first_date->format( 'Y' ) );
					$series   = array();
					$labels   = array();
					for ( $day = 1; $day <= $num_dyas; $day++ ) {
						//phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
						$id_exist = array_search( $day, array_column( $filters_used, 'day' ) );
						if ( false !== $id_exist ) {
							array_push( $series, $filters_used[ $id_exist ]['term_count'] );
						} else {
							array_push( $series, 0 );
						}
						array_push( $labels, $day . ' ' . $month );
					}

					$results = array(
						'title'  => esc_html__( 'Month to date', 'pwf-woo-filter' ),
						'type'   => 'onemonth',
						'series' => $series,
						'labels' => $labels,
					);
				}
			}

			return ! empty( $results ) ? $results : '';
		}

		protected function get_counted_terms( array $group_filter_ids = array() ) {

			$analytic_query            = Pwf_Admin_Analytic_Query::get_instance();
			$this->registered_terms    = $analytic_query->get_registered_terms();
			$this->registered_terms_id = array_column( $this->registered_terms, 'id' ); // term_ids
			$this->registered_terms    = $this->group_by( 'term_type', $this->registered_terms ); // $grouped_terms_by_type

			$price = $this->remove_price_from_registered_terms();
			$a     = $this->remove_range_sliders_from_registered_terms(); // group_range_sliders

			$count_registered_terms = $analytic_query->get_counted_term_ids( $group_filter_ids );
			$this->add_count_to_registered_terms( $count_registered_terms );
			$this->order_taxonomies_by_count();
			$this->append_price_to_registered_terms( $price );

			$registered_terms = $this->prepare_registered_terms_to_js();

			$index_allowed = 0;
			$new_terms     = array();
			$not_allowed   = array();
			foreach ( $registered_terms as $key => $term ) {
				if ( 'taxonomy' === $term['type'] && 2 > $index_allowed ) {
					array_push( $new_terms, $registered_terms[ $key ] );
				} elseif ( 'taxonomy' === $term['type'] ) {
					array_push( $not_allowed, $registered_terms[ $key ]['title'] );
				}
				if ( 'taxonomy' !== $term['type'] ) {
					array_push( $new_terms, $registered_terms[ $key ] );
				}
				if ( 'taxonomy' === $term['type'] ) {
					$index_allowed++;
				}
			}

			if ( ! empty( $not_allowed ) ) {
				$this->terms_to_display = $not_allowed;
			}

			return $new_terms;
		}

		protected function remove_price_from_registered_terms() {
			$price = array();

			if ( isset( $this->registered_terms['price'] ) ) {
				$price = $this->registered_terms['price'][0];
				unset( $this->registered_terms['price'] );

				// Remove the price term_id from term_ids
				$key = array_search( $price['id'], $this->registered_terms_id, true );
				if ( false !== $key ) {
					unset( $this->registered_terms_id [ $key ] );
				}
			}

			return $price;
		}

		protected function remove_range_sliders_from_registered_terms() {
			$range_sliders = array();

			if ( isset( $this->registered_terms['rangeslider'] ) ) {
				$range_sliders = $this->registered_terms['rangeslider'];
				unset( $this->registered_terms['rangeslider'] );

				// Remove range sliders term_ids from term_ids
				$range_slider_ids = array_column( $range_sliders, 'id' );
				foreach ( $range_slider_ids as $id ) {
					$key = array_search( $id, $this->registered_terms_id, true );
					if ( false !== $key ) {
						unset( $this->registered_terms_id[ $key ] );
					}
				}
			}

			return $range_sliders;
		}

		protected function order_taxonomies_by_count() {
			// Display product category and product tag at top
			foreach ( $this->registered_terms as $key => $terms ) {
				if ( 'taxonomy' === $key ) {
					$this->registered_terms[ $key ] = $this->group_by( 'term_key', $terms );

					$cat_tag = array();
					if ( isset( $this->registered_terms[ $key ]['product_cat'] ) ) {
						$cat_tag['product_cat'] = $this->registered_terms[ $key ]['product_cat'];
						unset( $this->registered_terms[ $key ]['product_cat'] );
					}
					if ( isset( $this->registered_terms[ $key ]['product_tag'] ) ) {
						$cat_tag['product_tag'] = $this->registered_terms[ $key ]['product_tag'];
						unset( $this->registered_terms[ $key ]['product_tag'] );
					}

					$this->registered_terms[ $key ] = array_merge( $cat_tag, $this->registered_terms[ $key ] );
				}
			}

			foreach ( $this->registered_terms as $group_key => $group ) {
				if ( 'taxonomy' === $group_key ) {
					foreach ( $group as $key => $terms ) {
						array_multisort( array_column( $terms, 'term_count' ), SORT_DESC, SORT_NUMERIC, $terms );
						$this->registered_terms[ $group_key ][ $key ] = $terms;
					}
				}
			}
		}

		protected function append_price_to_registered_terms( $price ) {
			if ( ! empty( $price ) ) {
				$analytic_query = Pwf_Admin_Analytic_Query::get_instance();

				$this->registered_terms['price'] = $analytic_query->get_counted_terms_to_rangeslider( $price['id'] );
			}
		}

		/**
		 * Function that groups an array of associative arrays by some key.
		 *
		 * @param {String} $key Property to sort by.
		 * @param {Array} $data Array that stores multiple associative arrays.
		 */
		protected function group_by( $key, $data ) {
			$result = array();

			foreach ( $data as $val ) {
				if ( array_key_exists( $key, $val ) ) {
					$result[ $val[ $key ] ][] = $val;
				}
			}

			return $result;
		}

		/**
		 * Add the count number to each term
		 *
		 * @param Array $counted_terms term_id > count
		 */
		protected function add_count_to_registered_terms( array $counted_terms ) {
			foreach ( $this->registered_terms as $key => $groubs ) {
				foreach ( $groubs as $key_item => $item ) {
					if ( isset( $counted_terms[ $item['id'] ] ) ) {
						$this->registered_terms[ $key ][ $key_item ]['term_count'] = $counted_terms[ $item['id'] ];
					} else {
						unset( $this->registered_terms[ $key ][ $key_item ] );
					}
				}
			}

		}

		/**
		 * Prepare registered terms to display on admin analytic page by JS.
		 *
		 * @return Array
		 */
		protected function prepare_registered_terms_to_js() {
			$results = array();

			foreach ( $this->registered_terms as $group_key => $group ) {

				if ( is_array( $group_key ) ) {
					continue;
				}

				if ( 'taxonomy' === $group_key ) {
					foreach ( $group as $key => $terms ) {
						$title  = '';
						$labels = array();

						if ( 'product_cat' === $key ) {
							$title = esc_html__( 'Product Categories', 'pwf-woo-filter' );
						} elseif ( 'product_tag' === $key ) {
							$title = esc_html__( 'Product tags', 'pwf-woo-filter' );
						} else {
							$taxonomy_name = get_taxonomy( $key );
							if ( false !== $taxonomy_name ) {
								$title = $taxonomy_name->labels->name;
							} else {
								$title = false;
							}
						}

						if ( $title ) {
							$labels = get_terms(
								array(
									'taxonomy' => $key,
									'orderby'  => 'include',
									'include'  => array_map( 'absint', array_column( $terms, 'term_value' ) ),
									'fields'   => 'names',
								)
							);

							$results[] = array(
								'title'  => $title,
								'type'   => $group_key,
								'series' => array_column( $terms, 'term_count' ),
								'labels' => $labels,
							);
						}
					}
				} else {
					$title  = '';
					$labels = array();
					switch ( $group_key ) {
						case 'rating':
							$title  = esc_html__( 'Rating', 'pwf-woo-filter' );
							$series = array_column( $group, 'term_count' );
							$labels = array();
							$names  = get_terms(
								array(
									'taxonomy' => 'product_visibility',
									'orderby'  => 'include',
									'include'  => array_map( 'absint', array_column( $group, 'term_value' ) ),
									'fields'   => 'names',
								)
							);
							foreach ( $names as $name ) {
								$labels[] = esc_html__( 'Rating', 'pwf-woo-filter' ) . ' ' . substr( $name, -1 );
							}
							break;
						case 'price':
							$title  = esc_html__( 'Price', 'pwf-woo-filter' );
							$series = array_map( 'absint', array_column( $group, 'term_count' ) );
							$labels = $this->prepare_range_slider_labels_to_js( $group );
							break;
					}

					if ( ! empty( $series ) ) {
						$results[] = array(
							'title'  => $title,
							'type'   => $group_key,
							'series' => $series,
							'labels' => $labels,
						);
					}
				}
			}

			return $results;
		}

		protected function get_stock_status_label( string $slug ) {
			$label            = '';
			$get_data_options = new Pwf_Meta_Data();
			$stock_status     = $get_data_options->stock_status();

			foreach ( $stock_status as $status ) {
				if ( $slug === $status['id'] ) {
					$label = $status['text'];
				}
			}

			return $label;
		}

		protected function get_order_by_label( string $slug ) {
			$orderby = array(
				'menu_order' => esc_html__( 'Default sorting', 'pwf-woo-filter' ),
				'rating'     => esc_html__( 'Rating', 'pwf-woo-filter' ),
				'popularity' => esc_html__( 'Popularity', 'pwf-woo-filter' ),
				'date'       => esc_html__( 'Date', 'pwf-woo-filter' ),
				'price'      => esc_html__( 'Price: low to high', 'pwf-woo-filter' ),
				'price-desc' => esc_html__( 'Price: high to low', 'pwf-woo-filter' ),
			);

			return $orderby[ $slug ] ?? '';
		}

		protected function get_vendors_display_name( $user_ids ) {
			$display_names = array();

			$users = get_users(
				array(
					'orderby' => 'include',
					'include' => array_map( 'absint', $user_ids ),
					'fields'  => array( 'display_name' ),
				)
			);

			if ( $users ) {
				foreach ( $users as $user ) {
					array_push( $display_names, $user->display_name );
				}
			}

			return $display_names;
		}

		protected function get_meta_title( $meta_key ) {
			$title = $meta_key;

			if ( null === self::$meta_data ) {
				self::$meta_data = get_option( 'pwf_woocommerce_analytic_meta_labels', array() );
			}

			if ( ! empty( self::$meta_data ) ) {
				if ( isset( self::$meta_data[ $meta_key ] ) ) {
					$title = self::$meta_data[ $meta_key ]['title'];
				}
			}

			return $title;
		}

		protected function get_meta_title_range_slider( $meta_key ) {
			$title = $meta_key;

			if ( null === self::$meta_range_data ) {
				self::$meta_range_data = get_option( 'pwf_woocommerce_analytic_range_slider_meta_labels', array() );
			}

			if ( ! empty( self::$meta_range_data ) ) {
				$title = self::$meta_range_data[ $meta_key ] ?? $meta_key;
			}

			return $title;
		}

		protected function get_meta_option_title( $meta_key, $value ) {
			$title = $value;

			if ( null === self::$meta_data ) {
				self::$meta_data = get_option( 'pwf_woocommerce_analytic_meta_labels', array() );
			}

			if ( ! empty( self::$meta_data ) ) {
				if ( isset( self::$meta_data[ $meta_key ] ) ) {
					$data = self::$meta_data[ $meta_key ]['data'] ?? '';
					if ( ! empty( $data ) && isset( $data[ $value ] ) ) {
						$title = $data[ $value ];
					}
				}
			}

			return $title;
		}

		protected function prepare_range_slider_labels_to_js( array $items ) {
			$labels   = array();
			$min_text = esc_html__( 'Min', 'pwf-woo-filter' );
			$max_text = esc_html__( 'Max', 'pwf-woo-filter' );
			foreach ( $items as $item ) {
				$min_value = number_format( $item['min_value'], 0 );
				$max_value = number_format( $item['max_value'], 0 );
				$labels[]  = $min_text . ': ' . $min_value . ' & ' . $max_text . ': ' . $max_value;
			}

			return $labels;
		}

		private static function get_filter_posts() {
			$query_args = array(
				'post_type'           => 'pwf_woofilter',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => -1,
			);

			$data    = array();
			$filters = get_posts( $query_args );
			if ( $filters ) {
				foreach ( $filters as $filter ) {
					$data[] = array(
						'id'    => $filter->ID,
						'title' => $filter->post_title,
					);
				}
			}

			return $data;
		}

		protected function get_languages_list() {
			$langs_list = get_transient( 'pwf_woo_filter_analytic_languages_list', array() );
			if ( false === $langs_list ) {
				$analytic_query = Pwf_Admin_Analytic_Query::get_instance();
				$languages      = $analytic_query->get_languages();

				require_once ABSPATH . 'wp-admin/includes/translation-install.php';
				$translations = wp_get_available_translations();

				foreach ( $languages as $lang ) {
					if ( 'en_US' === $lang ) {
						$langs_list['en_US'] = esc_html__( 'English', 'pwf-woo-filter' );
					} else {
						$langs_list[ esc_attr( $lang ) ] = esc_attr( $translations[ $lang ]['english_name'] );
					}
				}
				set_transient( 'pwf_woo_filter_analytic_languages_list', $langs_list, DAY_IN_SECONDS );
			}

			// check if is multilanguage
			if ( ( is_array( $langs_list ) && count( $langs_list ) < 2 ) || empty( $langs_list ) || 'true' === $langs_list ) {
				$langs_list = array();
			}

			return $langs_list;
		}
	}

	$analytic = new Pwf_Analytic_Page();
}
