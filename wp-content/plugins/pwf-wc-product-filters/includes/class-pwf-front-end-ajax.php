<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Front_End_Ajax' ) ) {

	class Pwf_Front_End_Ajax {

		public static function register() {
			$plugin = new self();
			add_action( 'init', array( $plugin, 'init' ) );
		}

		function init() {
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 50 );
			add_action( 'wp_ajax_get_filter_result', array( $this, 'get_filter_result' ), 10 );
			add_action( 'wp_ajax_nopriv_get_filter_result', array( $this, 'get_filter_result' ), 10 );
		}

		function wp_enqueue_scripts() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style( 'select2', PWF_WOO_FILTER_URI . '/assets/select2/css/select2.min.css', '', '4.0.12' );
			wp_enqueue_style( 'jquery-ui', PWF_WOO_FILTER_URI . '/assets/css/frontend/jquery-ui/jquery-ui.min.css', '', '1.12.1' );
			wp_enqueue_style( 'pwf-woo-filter', PWF_WOO_FILTER_URI . '/assets/css/frontend/style' . $suffix . '.css', '', PWF_WOO_FILTER_VER );

			$upload_custom_css = apply_filters( 'pwf_upload_customize_css', true );
			if ( true === $upload_custom_css ) {
				$css_options = get_option( 'pwf_style', '' );
				if ( ! empty( $css_options ) ) {
					$css = new Pwf_Generate_Css();
					$css->css_file( $css_options );
				}
			}

			wp_register_script( 'select2', PWF_WOO_FILTER_URI . '/assets/select2/js/select2.full.min.js', array( 'jquery' ), '4.0.12', true );
			wp_register_script( 'nouislider', PWF_WOO_FILTER_URI . '/assets/js/frontend/nouislider.min.js', array( 'jquery' ), '14.2.0', true );
			wp_register_script(
				'pwf-woo-filter',
				PWF_WOO_FILTER_URI . '/assets/js/frontend/script' . $suffix . '.js',
				array( 'jquery' ),
				PWF_WOO_FILTER_VER,
				true
			);
		}

		// get filter results
		public function get_filter_result() {
			check_ajax_referer( 'pwf-woocommerce-filter-nonce', 'nonce' );

			if ( ! isset( $_POST['filter_id'] ) || ! is_int( absint( $_POST['filter_id'] ) ) ) {
				wp_send_json_success(
					array(
						'message' => esc_html__( 'Filer ID must be integer.', 'pwf-woo-filter' ),
					),
					200
				);
			}

			/**
			 * Not recomended to use apply_filters using pwf_filter_id
			 * When the filter id come form ajax
			 * because it is already change before created a page
			 */
			$filter_id = absint( $_POST['filter_id'] );

			$GLOBALS['pwf_main_query']['is_archive']        = sanitize_key( $_POST['is_archive'] );
			$GLOBALS['pwf_main_query']['taxonomy_id']       = absint( $_POST['taxonomy_id'] );
			$GLOBALS['pwf_main_query']['taxonomy_name']     = sanitize_key( $_POST['taxonomy_name'] );
			$GLOBALS['pwf_main_query']['page_type']         = sanitize_key( $_POST['page_type'] );
			$GLOBALS['pwf_main_query']['page_id']           = absint( $_POST['page_id'] );
			$GLOBALS['pwf_main_query']['filter_integrated'] = sanitize_key( $_POST['filter_integrated'] );

			if ( isset( $_POST['rule_hidden_items'] ) && is_array( $_POST['rule_hidden_items'] ) ) {
				$GLOBALS['rule_hidden_items'] = array_map( 'esc_attr', $_POST['rule_hidden_items'] );
			}

			// require to change to selected_options user_selected_options or selected_options
			$selected_options = array();
			if ( isset( $_POST['selected_options'] ) && is_array( $_POST['selected_options'] ) && ! empty( $_POST['selected_options'] ) ) {
				foreach ( $_POST['selected_options'] as $key => $values ) {
					if ( ! empty( $values ) ) {
						if ( ! is_array( $values ) ) {
							$values = array( $values );
						}
						$selected_options[ esc_attr( $key ) ] = array_map( 'esc_attr', $values );
					}
				}
			}

			$attributes = array();
			if ( isset( $_POST['attributes'] ) && is_array( $_POST['attributes'] ) && ! empty( $_POST['attributes'] ) ) {
				foreach ( $_POST['attributes'] as $key => $value ) {
					$attributes[ esc_attr( $key ) ] = esc_attr( $value );
				}
			}

			do_action( 'pwf_before_doing_ajax', $filter_id );

			$parse_query = new Pwf_Parse_Query_Vars( $filter_id, $selected_options );
			$orderby     = $parse_query->get_products_orderby();
			if ( ! empty( $orderby ) ) {
				$attributes['orderby'] = is_array( $orderby ) ? implode( ',', $orderby ) : $orderby;
			}

			$query      = new Pwf_Filter_Products( $parse_query, $attributes );
			$products   = $query->get_content();
			$ajax_attrs = $query->get_query_info();

			if ( isset( $_POST['get_products_only'] ) && 'true' === $_POST['get_products_only'] ) {
				$filter_items_html = '';
			} else {
				$render_filter     = new Pwf_Render_Filter( $filter_id, $parse_query );
				$filter_items_html = wp_kses_post( $render_filter->get_html() );
			}

			$results = array(
				'products'    => $products,
				'attributes'  => $ajax_attrs,
				'filter_html' => $filter_items_html,
			);

			// Doing analytic
			$anlaytic = get_option( 'pwf_shop_analytics', 'disable' );
			if ( 'enable' === $anlaytic && ! isset( $_POST['get_products_only'] ) ) {
				$selected_items = $parse_query->selected_items();

				// Add default Woocommerce order menu
				if ( empty( $orderby ) && isset( $attributes['orderby'] ) ) {
					$selected_items['orderby'] = array(
						'values' => array( $attributes['orderby'] ),
						'type'   => 'orderby',
					);
				}

				if ( ! empty( $selected_items ) ) {
					$filter_data = array(
						'filter_post_id' => $filter_id,
						'products_count' => $query->get_products_count(),
						'from'           => 1,
						'query_string'   => $parse_query->get_query_string(),
					);

					$analytic_data = array(
						'filter_data'     => $filter_data,
						'selected_values' => $selected_items,
					);

					$analytic = new Pwf_Analytic_Query( $analytic_data );
				}
			}

			wp_send_json_success( $results, 200 );
		}

		/**
		 * Remove pagination and pretty URLS for current URL
		 *
		 * @since 1.6.4
		 *
		 * @return string current URL
		 */
		protected static function get_current_page_url() {
			global $wp;

			$current_url = home_url( add_query_arg( array(), $wp->request ) );

			// get the position where '/page.. ' text start.
			$pos = strpos( $current_url, '/page/' );

			// remove string from the specific postion
			$url = ( $pos ) ? substr( $current_url, 0, $pos ) : $current_url;

			$has_slash = apply_filters( 'pwf_current_page_url_has_slash', true );

			if ( $has_slash ) {
				$url = trailingslashit( $url );
			}

			return $url;
		}

		protected static function add_localize_script() {
			$currency_symbol = get_woocommerce_currency_symbol();
			$currency_pos    = get_option( 'woocommerce_currency_pos', 'left' );
			if ( empty( $currency_symbol ) ) {
				$currency_symbol = '&#36;';
			}

			$localize_args = array(
				'ajaxurl'         => admin_url( 'admin-ajax.php' ),
				'nonce'           => wp_create_nonce( 'pwf-woocommerce-filter-nonce' ),
				'translated_text' => self::get_translated_text(),
				'currency_symbol' => $currency_symbol,
				'currency_pos'    => $currency_pos,
				'page_url'        => self::get_current_page_url(),
			);

			return $localize_args;
		}

		/**
		 * @since 1.0.0, 1.2.0
		 */
		protected static function append_filter_js( $args ) {
			$filter_setting = $args['filter_settings'];

			unset( $filter_setting['shortcode_string'] );

			$filter_js_variables = array(
				'filter_setting'    => $filter_setting,
				'filter_id'         => $args['filter_id'],
				'filter_integrated' => $GLOBALS['pwf_main_query']['filter_integrated'],
				'selected_items'    => $args['selected_items'],
				'page_type'         => $GLOBALS['pwf_main_query']['page_type'],
				'is_archive'        => $GLOBALS['pwf_main_query']['is_archive'],
				'taxonomy_id'       => $GLOBALS['pwf_main_query']['taxonomy_id'],
				'taxonomy_name'     => $GLOBALS['pwf_main_query']['taxonomy_name'],
				'page_id'           => $GLOBALS['pwf_main_query']['page_id'],
			);

			if ( ! empty( $args['rule_hidden'] ) ) {
				$filter_js_variables['rule_hidden_items'] = $args['rule_hidden'];
			}

			if ( is_shop() && absint( get_option( 'page_on_front' ) ) === absint( wc_get_page_id( 'shop' ) ) ) {
				// Add post type to url hash if home page == shop page, this force WordPress to use template woo archive
				$filter_js_variables['add_posttype'] = apply_filters( 'pwf_add_posttype_to_url_hash', 'false' );
			}
			$filter_js_variables = apply_filters( 'pwf_woo_filter_js_variables', $filter_js_variables );

			$script = 'var pwffilterVariables = ' . json_encode( $filter_js_variables ) . '; var pwfFilterJSItems = ' . json_encode( $args['filter_items'] ) . ';';

			return $script;
		}

		protected static function get_translated_text() {
			$text = array(
				'apply'     => esc_html__( 'Apply', 'pwf-woo-filter' ),
				'reset'     => esc_html__( 'Reset', 'pwf-woo-filter' ),
				'filter'    => esc_html__( 'Filter', 'pwf-woo-filter' ),
				'price'     => esc_html__( 'Price', 'pwf-woo-filter' ),
				'search'    => esc_html__( 'Search', 'pwf-woo-filter' ),
				'rate'      => esc_html__( 'Rated', 'pwf-woo-filter' ),
				'load_more' => esc_html__( 'Load more', 'pwf-woo-filter' ),
				'clearall'  => esc_html__( 'Clear all', 'pwf-woo-filter' ),
			);

			return apply_filters( 'pwf_frontend_translated_text', $text );
		}

		public static function enqueue_scripts( $args ) {
			wp_enqueue_script( 'pwf-woo-filter' );
			// Require to add  params
			wp_localize_script( 'pwf-woo-filter', 'pwf_woocommerce_filter', self::add_localize_script() );
			wp_add_inline_script( 'pwf-woo-filter', self::append_filter_js( $args ), 'before' );
		}
	}

	Pwf_Front_End_Ajax::register();
}
