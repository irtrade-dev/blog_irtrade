<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Filter_Manager' ) ) {

	/**
	 * Manage Filter on the frontend
	 *
	 * This is one place to control all related to filter
	 * Use to fix if the filter items code start before post code
	 *
	 * @since 1.1.0
	 */
	class Pwf_Filter_Manager {

		public function __construct() {}

		/**
		 * Make apply filter id at one place
		 */
		public static function get_filter_id( $filter_id ) {
			return absint( apply_filters( 'pwf_filter_id', $filter_id ) );
		}

		/**
		 * Set the global variable used in many places
		 * @param array $vars key => value
		 *
		 * @since 1.1.0
		 */
		public static function set_pwf_global_variables( $vars = array() ) {
			if ( empty( $vars ) ) {
				$GLOBALS['pwf_main_query'] = array(
					'filter_id'         => '',
					'post_type'         => '',
					'query_vars'        => '',
					'filter_integrated' => 'no',
					'page_type'         => '',
					'is_archive'        => 'false',
					'taxonomy_id'       => '',
					'taxonomy_name'     => '',
					'page_id'           => '',
					'user_id'           => '',
					'lang'              => '',
				);
			} else {
				foreach ( $vars as $key => $value ) {
					$GLOBALS['pwf_main_query'][ $key ] = $value;
				}
			}
		}

		/**
		 * Use this function only on frontend
		 * @param int $filter_id
		 *
		 * @since 1.1.0
		 *
		 * return array ex array( 'items' => array(), 'setting' => array() )
		 */
		public static function get_filter_settings_and_items( $filter_id ) {
			$meta_data = array();
			$meta_data = get_post_meta( absint( $filter_id ), '_pwf_woo_post_filter', true );
			if ( ! is_array( $meta_data ) ) {
				$meta_data = array();
			}

			return $meta_data;
		}

		/**
		 * Excute pwf shortcode && widget
		 * @param int $filter_id
		 *
		 * @since 1.1.0
		 *
		 * @return string HTML filter items
		 */
		public static function excute_plugin_shortcode_widget( $filter_id ) {
			$results      = '';
			$filter_id    = self::get_filter_id( $filter_id );
			$filter_data  = self::get_filter_settings_and_items( $filter_id );
			$filter_items = $filter_data['items'] ?? array();

			if ( ! empty( $filter_items ) ) {
				if ( ! empty( $GLOBALS['pwf_main_query']['query_vars'] ) ) {
					$query_vars = $GLOBALS['pwf_main_query']['query_vars'];
				} else {
					$selected_options = self::get_user_selected_options( $filter_id, $filter_items );
					$query_vars       = new Pwf_Parse_Query_Vars( $filter_id, $selected_options );

					$GLOBALS['pwf_main_query']['query_vars'] = $query_vars;
				}

				$render_filter = new Pwf_Render_Filter( $filter_id, $query_vars );
				$results       = $render_filter->get_html();
			}

			return $results;
		}

		/**
		 * Get all filter items inside filter post without columns or buttons
		 *
		 * @since 1.1.0
		 *
		 * @return array
		 */
		public static function get_filter_items_without_columns( $filter_items ) {
			$items = array();
			foreach ( $filter_items as $item ) {
				if ( 'column' === $item['item_type'] ) {
					if ( ! empty( $item['children'] ) ) {
						$children = self::get_filter_items_without_columns( $item['children'] );
						$items    = array_merge( $items, $children );
					}
				} elseif ( 'button' !== $item['item_type'] ) {
					array_push( $items, $item );
				}
			}
			return $items;
		}

		/**
		 * Return selected option by user on frontend
		 *
		 * @param int $filter_id
		 * @param array $filter_items
		 *
		 * @since 1.1.0
		 *
		 * @return array selected options
		 */
		public static function get_user_selected_options( $filter_id, $filter_items ) {
			$selected_options = array();
			$filter_items     = self::get_filter_items_without_columns( $filter_items );

			if ( empty( $filter_items ) ) {
				return $selected_options;
			}

			// check what item is active
			foreach ( $filter_items as $item ) {
				if ( 'priceslider' === $item['item_type'] && 'two' === $item['price_url_format'] ) {
					if ( isset( $_GET[ $item['url_key_min_price'] ] ) && isset( $_GET[ $item['url_key_max_price'] ] ) ) {
						$selected_options[ $item['url_key'] ] = array( $_GET[ $item['url_key_min_price'] ], $_GET[ $item['url_key_max_price'] ] );
					}
				} elseif ( isset( $_GET[ $item['url_key'] ] ) ) {
					$selected_options[ $item['url_key'] ] = $_GET[ $item['url_key'] ];
				}
			}

			return $selected_options;
		}
	}
}
