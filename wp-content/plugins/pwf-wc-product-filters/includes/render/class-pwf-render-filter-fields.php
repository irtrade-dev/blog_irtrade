<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Render_Filter_Fields' ) ) {

	class Pwf_Render_Filter_Fields {

		protected $filter_item;
		protected $filter_item_index;
		protected $terms;
		protected $filter_item_type;
		protected $selected_values;

		public function __construct( $filter_item, $filter_item_index, $terms, $selected_values = array(), $args = array() ) {
			$this->filter_item       = $filter_item;
			$this->filter_item_index = $filter_item_index;
			$this->terms             = $terms;
			$this->filter_item_type  = $this->filter_item['item_type'];
			$this->selected_values   = $selected_values;
		}

		public function get_html_template() {
			$output = '';
			switch ( $this->filter_item_type ) {
				case 'checkboxlist':
					$output = $this->get_checkboxlist();
					break;
				case 'radiolist':
					$output = $this->render_radiolist();
					break;
				case 'dropdownlist':
					$output = self::render_dropdownlist();
					break;
				case 'button':
					$output = self::render_button();
					break;
				case 'priceslider':
					$output .= self::render_priceslider();
					break;
				case 'rating':
					$output .= self::render_rating();
					break;
			}

			return $output;
		}

		protected function get_filter_item_name() {
			return $this->filter_item['url_key'];
		}

		protected function get_hierarchy_args() {
			$css_class    = '';
			$is_hierarchy = false;
			$source       = $this->filter_item['source_of_options'];

			$args = array(
				'css_class'    => $css_class,
				'is_hierarchy' => $is_hierarchy,
			);

			return $args;
		}

		protected function get_html_filter_item( $css_class, $inner_content ) {
			$output  = $this->get_html_filter_item_header( $css_class );
			$output .= $this->get_filter_item_title();
			$output .= $this->get_html_filter_item_container();
			$output .= $inner_content;
			$output .= $this->get_html_filter_item_container_end();
			$output .= $this->get_html_filter_item_footer();

			return $output;
		}

		protected function get_html_filter_item_header( string $css_class ) {
			$data_item_key = '';
			if ( 'button' !== $this->filter_item_type ) {
				$data_item_key = ' data-item-key="' . $this->get_filter_item_name() . '"';
			}

			if ( isset( $this->filter_item['display_tooltip'] ) && 'on' === $this->filter_item['display_tooltip'] ) {
				$css_class .= ' range-slider-has-tooltip';
			}

			return '<div class="pwf-field-item pwf-item-id-' . $this->filter_item_index . ' pwf-field-item-' . $this->filter_item_type . esc_attr( $css_class ) . '"' . $data_item_key . '><div class="pwf-field-inner">';
		}

		protected function get_html_filter_item_footer() {
			return '</div></div>';
		}

		protected function get_html_filter_item_container() {
			$output = '<div class="pwf-field-item-container">';

			return $output;
		}

		protected function get_html_filter_item_container_end() {
			$output = '</div>';

			return $output;
		}

		protected function get_custom_css_class() {
			$css_class = '';

			if ( ! empty( $this->selected_values ) ) {
				$css_class .= ' pwf-has-selected-option';
			}

			if ( 'button' !== $this->filter_item_type && 'column' !== $this->filter_item_type ) {
				if ( 'on' === $this->filter_item['display_title'] && 'on' === $this->filter_item['display_toggle_content'] ) {
					$css_class .= ( 'show' === $this->filter_item['default_toggle_state'] ) ? ' pwf-collapsed-open' : ' pwf-collapsed-close';
				}
			}

			if ( ! empty( $this->filter_item['css_class'] ) ) {
				$css_class .= ' ' . esc_attr( $this->filter_item['css_class'] );
			}

			return $css_class;
		}

		protected function get_filter_item_title() {
			$output = '';

			if ( 'on' === $this->filter_item['display_title'] ) {
				$output .= '<div class="pwf-field-item-title"><span class="text-title">';
				$output .= $this->filter_item['title'] . '</span>';
				if ( 'on' === $this->filter_item['display_toggle_content'] ) {
					$output .= '<span class="pwf-toggle pwf-toggle-widget-title"></span>';
				}
				$output .= '</div>';
			}

			return $output;
		}

		protected function get_checkboxlist() {
			$css_class      = '';
			$output         = '';
			$hierarchy_args = $this->get_hierarchy_args();
			$is_hierarchy   = $hierarchy_args['is_hierarchy'];
			$css_class     .= $hierarchy_args['css_class'] . $this->get_custom_css_class();

			$walker  = new Pwf_Walker_Checkbox();
			$output .= $walker->start_walk( $this->filter_item, $this->terms, $is_hierarchy, $this->selected_values );

			if ( '' !== $output ) {
				$output = $this->get_html_filter_item( $css_class, $output );
			}
			return $output;
		}

		protected function render_radiolist() {
			$hierarchy_args = $this->get_hierarchy_args();
			$is_hierarchy   = $hierarchy_args['is_hierarchy'];
			$css_class      = $hierarchy_args['css_class'] . $this->get_custom_css_class();
			$output         = '';

			$walker  = new Pwf_Walker_Radio();
			$output .= $walker->start_walk( $this->filter_item, $this->terms, $is_hierarchy, $this->selected_values );

			if ( '' !== $output ) {
				$output = $this->get_html_filter_item( $css_class, $output );
			}

			return $output;
		}

		protected function render_dropdownlist() {
			$css_class      = '';
			$multi_select   = $this->filter_item['multi_select'] ?? '';
			$hierarchy_args = $this->get_hierarchy_args();
			$is_hierarchy   = $hierarchy_args['is_hierarchy'];
			$css_class      = $hierarchy_args['css_class'] . ' ' . $this->get_custom_css_class();
			$output         = '';

			$walker  = new Pwf_Walker_Dropdown_List();
			$output .= $walker->start_walk( $this->filter_item, $this->terms, $is_hierarchy, $this->selected_values );

			if ( '' !== $output ) {
				$select_css = ' pwf-dropdownlist-item-default';
				$start_select = '<div class="pwf-select"><select name="' . esc_attr( $this->get_filter_item_name() ) . '" class="pwf-item pwf-dropdownlist-item' . $select_css . '"' . '>';
				$end_select   = '</select></div>';
				$output       = $start_select . $output . $end_select;
				$output       = $this->get_html_filter_item( $css_class, $output );
			}

			return $output;
		}

		/**
		 * @since 1.1.0
		 */
		protected function render_priceslider() {
			if ( empty( $this->filter_item['min_max_price'] ) ) {
				return;
			}

			$output    = '';
			$limit     = '';
			$css_class = $this->get_custom_css_class();
			$random_id = rand( 1, 1000 );

			$min_price = $this->filter_item['min_max_price']['min_price'];
			$max_price = $this->filter_item['min_max_price']['max_price'];

			// Check to see if we should add taxes to the prices if store are excl tax but display incl.
			$tax_display_mode = get_option( 'woocommerce_tax_display_shop' );

			if ( wc_tax_enabled() && ! wc_prices_include_tax() && 'incl' === $tax_display_mode ) {
				$tax_class = apply_filters( 'pwf_woocommerce_price_filter_tax_class', '' ); // Uses standard tax class.
				$tax_rates = WC_Tax::get_rates( $tax_class );

				if ( $tax_rates ) {
					$min_price += WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $min_price, $tax_rates ) );
					$max_price += WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $max_price, $tax_rates ) );
				}
			}

			$min_price = apply_filters( 'pwf_woocommerce_price_filter_min_amount', floor( $min_price ) );
			$max_price = apply_filters( 'pwf_woocommerce_price_filter_max_amount', ceil( $max_price ) );

			// If both min and max are equal, we don't need a slider.
			if ( $min_price === $max_price ) {
				return;
			}

			if ( ! empty( $this->selected_values ) ) {
				$current_min_price = absint( $this->selected_values[0] );
				$current_max_price = absint( $this->selected_values[1] );
			} else {
				$current_min_price = ( $this->filter_item['price_start'] > 0 ) ? $this->filter_item['price_start'] : $min_price;
				$current_max_price = ( $this->filter_item['price_end'] > 0 ) ? $this->filter_item['price_end'] : $max_price;
			}

			$limit       = '';
			$interactive = $this->filter_item['interaction'] ?? '';
			if ( empty( $this->selected_values ) && 'on' === $interactive ) {
				$active_min = floor( $this->filter_item['min_max_price']['active_min'] );
				$active_max = ceil( $this->filter_item['min_max_price']['active_max'] );

				/**
				 * If active_min Equal to active_max and this item is interactive
				 * Don't display this item
				 */
				if ( $active_min === $active_max ) {
					return;
				}

				if ( ! ( $min_price === $active_min && $max_price === $active_max ) ) {
					$current_min_price = $active_min;
					$current_max_price = $active_max;

					$limit .= ' data-limit="' . ( $active_max - $active_min ) . '"';
				}
			}

			if ( ! empty( $this->filter_item['price_step'] ) && $this->filter_item['price_step'] > 0 ) {
				$step = $this->filter_item['price_step'];
			} else {
				$step = 1;
			}

			$tooltip = 'false';
			if ( 'on' === $this->filter_item['display_tooltip'] ) {
				$tooltip = 'true';
			}

			$output .= '<div class="pwf-range-slider-wrap pwf-price-slider-wrap">';
			$output .= '<div class="pwf-wrap-range-slider"><div id="pwf-range-slider-' . absint( $this->filter_item_index ) . '-' . $random_id . '" class="pwf-range-slider pwf-price-slider"';
			$output .= ' data-current-min="' . $current_min_price . '" data-current-max="' . $current_max_price . '"';
			$output .= ' data-min="' . $min_price . '" data-max="' . $max_price . '"';
			$output .= ' data-step="' . absint( $step ) . '"';
			$output .= $limit;
			$output .= ' data-tooltip="' . $tooltip . '"';
			$output .= '></div></div>';

			if ( 'on' === $this->filter_item['display_max_min_inputs'] ) {
				$output .= '<div class="pwf-price-slider-min-max-inputs">';
				$output .= '<input type="number" id="pwf-min-price-' . $random_id . '" class="pwf-min-value" value="' . $current_min_price . '" min="' . $min_price . '" max="' . $max_price . '" data-min="' . $min_price . '" placeholder="' . esc_attr__( 'Min price', 'pwf-woo-filter' ) . '" />';
				$output .= '<input type="number" id="pwf-max-price-' . $random_id . '" class="pwf-max-value" value="' . $current_max_price . '" min="' . $min_price . '" max="' . $max_price . '" data-max="' . $max_price . '" placeholder="' . esc_attr__( 'Max price', 'pwf-woo-filter' ) . '" />';
				$output .= '</div>';
			}

			if ( 'on' === $this->filter_item['display_price_label'] ) {
				$currency_symbol = get_woocommerce_currency_symbol();
				$price_format    = get_woocommerce_price_format();
				if ( empty( $currency_symbol ) ) {
					$currency_symbol = '&#36;';
				}
				$output .= '<div class="pwf-range-slider-labels pwf-price-labels">';
				$output .= '<span class="text-title">' . esc_html__( 'Price:', 'woocommerce' ) . '</span> ';
				$output .= '<span class="pwf-wrap-price">' . sprintf( $price_format, '<span class="pwf-currency-symbol">' . $currency_symbol . '</span>', '<span id="pwf-from-' . absint( $this->filter_item_index ) . '" class="pwf-from">' . $current_min_price . '</span>' ) . '</span>';
				$output .= '<span class="price-delimiter"> &mdash; </span>';
				$output .= '<span class="pwf-wrap-price">' . sprintf( $price_format, '<span class="pwf-currency-symbol">' . $currency_symbol . '</span>', '<span id="pwf-to-' . absint( $this->filter_item_index ) . '" class="pwf-to">' . $current_max_price . '</span>' ) . '</span>';
				$output .= '</div>';
			}

			$output .= '</div>';
			$output  = $this->get_html_filter_item( $css_class, $output );

			return $output;
		}

		protected function render_button() {
			$css_class  = '';
			$css_class .= $this->get_custom_css_class();
			$output     = $this->get_html_filter_item_header( $css_class );

			if ( 'reset' === $this->filter_item['button_action'] ) {
				$css = ' pwf-reset-button';
			} else {
				$css = ' pwf-filter-button';
			}

			$output .= '<button class="pwf-item pwf-item-button' . esc_attr( $css ) . '"><span class="button-text">' . esc_attr( $this->filter_item['title'] ) . '</span></button>';
			$output .= $this->get_html_filter_item_footer();

			return $output;
		}

		/**
		 * @since 1.1.0
		 */
		protected function render_rating() {
			$output    = '';
			$css_class = $this->get_custom_css_class();

			if ( 'on' === $this->filter_item['up_text'] ) {
				$css_class .= ' pwf-rating-radio-type rating-has-up-text';
			} else {
				$css_class .= ' pwf-rating-checkbox-type';
			}

			foreach ( $this->terms as $term ) {
				$css       = '';
				$visibilty = true;
				$up_text   = apply_filters( 'pwf_up_text', esc_html__( 'up', 'pwf-woo-filter' ) );

				if ( 'on' === $this->filter_item['up_text'] ) {
					$term['label'] = $term['label'] . ' ' . esc_html__( 'and', 'pwf-woo-filter' ) . ' ' . $up_text;
				}

				if ( 'hide' === $this->filter_item['action_for_empty_options'] && 1 > $term['count'] ) {
					$visibilty = false;
				} elseif ( 'markasdisable' === $this->filter_item['action_for_empty_options'] && 1 > $term['count'] ) {
					$css .= ' pwf-disabled';
				}

				if ( in_array( $term['value'], $this->selected_values, true ) ) {
					$css .= ' checked';
				}

				if ( $visibilty ) {
					$output .= '<div data-slug="' . $term['slug'] . '" data-item-value="' . $term['value'] . '" class="pwf-item pwf-star-rating-item' . $css . '" title="' . esc_attr( $term['label'] ) . '">';

					$output .= '<div class="pwf-input-container"></div>';

					$output .= '<span class="pwf-star-rating star-rating">' . $this->get_star_rating_html( $term['rate'] ) . '</span>';
					if ( 'on' === $this->filter_item['up_text'] ) {
						$output .= '<span class="pwf-up-text">& ' . $up_text . '</span>';
					}
					if ( 'on' === $this->filter_item['display_product_counts'] && $term['count'] > 0 ) {
						$output .= self::get_html_template_item_count( $term['count'] );
					}
					$output .= '</div>';
				}
			}

			if ( '' !== $output ) {
				$output = $this->get_html_filter_item( $css_class, $output );
			}

			return $output;
		}

		/**
		 * @since 1.1.0
		 */
		protected function get_star_rating_html( $rating ) {
			$html = '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%">';

			if ( 'on' !== $this->filter_item['up_text'] ) {
				$html .= esc_html__( 'Rated out of', 'pwf-woo-filter' ) . ' <strong>' . absint( $rating ) . '</strong>';
			} else {
				$html .= esc_html__( 'Rated', 'pwf-woo-filter' ) . ' <strong>' . absint( $rating ) . '</strong>';
				$html .= ' ' . esc_html__( 'and up', 'pwf-woo-filter' );
			}

			$html .= '</span>';

			return $html;
		}

		public static function get_html_template_item_count( $count ) {
			return '<span class="pwf-product-counts"><span class="pwf-wrap-count">' . absint( $count ) . '</span></span>';
		}
	}
}
