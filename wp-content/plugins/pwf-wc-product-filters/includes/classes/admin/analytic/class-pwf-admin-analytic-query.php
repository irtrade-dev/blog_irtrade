<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Admin_Analytic_Query' ) ) {

	/**
	 * @ since 1.2.9
	 */
	class Pwf_Admin_Analytic_Query {

		/**
		* The unique instance of the plugin.
		*
		* @var Pwf_Admin_Analytic_Query
		*/
		private static $instance;

		/**
		 * Gets an instance of our plugin.
		 *
		 * @return WP_Kickass_Plugin
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		private function __construct() {

		}

		/**
		 * Counted define terms in the table wc_pwf_filters_terms
		 * depends on group_id in the table wc_pwf_filters
		 * If empty filter_group_ids counted all terms
		 *
		 * @param Array $filter_group_ids
		 *
		 * @return Array contain term_count_id => term_count
		 */
		public function get_counted_term_ids( array $filter_group_ids = array() ) {
			global $wpdb;

			$where = '';
			if ( ! empty( $filter_group_ids ) ) {
				$where = ' WHERE group_id IN (' . implode( ',', array_map( 'absint', $filter_group_ids ) ) . ') ';
			}

			$results = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT count(id) AS term_count, term_id AS term_count_id FROM %1s %2s GROUP BY term_count_id', // @codingStandardsIgnoreLine
					$wpdb->prefix . 'wc_pwf_filters_terms',
					$where,
				),
				'ARRAY_A',
			);

			$counts = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );

			return $counts;
		}

		public function get_counted_terms_to_rangeslider( int $term_id, $filter_group_ids = array() ) {
			global $wpdb;

			$where = 'WHERE term_id = ' . absint( $term_id );
			if ( ! empty( $filter_group_ids ) ) {
				$where .= ' AND group_id IN (' . implode( ',', array_map( 'absint', $filter_group_ids ) ) . ') ';
			}

			$results = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT count(id) AS term_count, term_id, min AS min_value, max AS max_value FROM %1s %2s GROUP BY min, max ORDER BY term_count DESC LIMIT 15', // @codingStandardsIgnoreLine
					$wpdb->prefix . 'wc_pwf_range_sliders',
					$where,
				),
				'ARRAY_A',
			);

			return $results;
		}

		public function get_counted_search( array $filter_group_ids = array() ) {
			global $wpdb;

			$where = '';
			if ( ! empty( $filter_group_ids ) ) {
				$where = ' WHERE group_id IN (' . implode( ',', array_map( 'absint', $filter_group_ids ) ) . ') ';
			}

			$results = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT count(id) AS term_count, search_for AS title FROM %1s %2s GROUP BY search_for ORDER BY term_count DESC LIMIT 15', // @codingStandardsIgnoreLine
					$wpdb->prefix . 'wc_pwf_searches',
					$where,
				),
				'ARRAY_A',
			);

			return $results;
		}

		/**
		 * Get all registered terms in the table wc_pwf_terms
		 *
		 * @ retrun Array
		 */
		public function get_registered_terms() {
			global $wpdb;

			$get_terms = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM %1s', // @codingStandardsIgnoreLine
					$wpdb->prefix . 'wc_pwf_terms',
				),
				'ARRAY_A',
			);

			return $get_terms;
		}

		/**
		 * Used to get how many clients interact with filters between two date
		 * Empty dates return all times
		 *
		 * @param Array dates contain start and end date
		 *
		 * @return array term_count and date count
		 */
		public function get_counted_filters_used_by_clients( array $date = array() ) {
			global $wpdb;

			$results = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT count(group_id) AS term_count, DAY(`date`) AS `day` FROM %1s WHERE `date` between "%2s" AND "%3s" GROUP BY `day` ORDER BY `day` ASC', // @codingStandardsIgnoreLine
					$wpdb->prefix . 'wc_pwf_filters',
					esc_attr( $date[0] ),
					esc_attr( $date[1] ) . ' 23:59:59',
				),
				'ARRAY_A',
			);

			return $results;
		}

		/**
		 * Get the filter group Ids depend on condition
		 * Filters group depend on date, language, from, page type or filter post id.
		 *
		 * @param Array $attr define the attributes used to to filter group of filters
		 *
		 * @return array contain group_id from the table wc_pwf_filters
		 */
		public function get_filter_group_ids( array $atts ) {
			global $wpdb;

			$defaults = array(
				'date'           => array(),
				'lang'           => '',
				'page_type'      => '',
				'from'           => '',
				'filter_post_id' => '',
			);

			$atts = wp_parse_args( $atts, $defaults );

			$query           = array();
			$query['select'] = "SELECT group_id FROM {$wpdb->prefix}wc_pwf_filters";
			$query['where']  = array();

			if ( ! empty( $atts['lang'] ) ) {
				$query['where'][] = 'lang = "' . esc_attr( $atts['lang'] ) . '"';
			}

			if ( ! empty( $atts['from'] ) ) {
				$query['where'][] = '`from` = ' . absint( $atts['from'] );
			}

			if ( ! empty( $atts['filter_post_id'] ) ) {
				$query['where'][] = 'filter_post_id = ' . absint( $atts['filter_post_id'] );
			}

			if ( ! empty( $atts['date'] ) ) {
				$query['where'][] = '`date` BETWEEN "' . esc_attr( $atts['date'][0] ) . ' 00:00:00" AND "' . esc_attr( $atts['date'][1] ) . ' 23:59:00"';
			}

			if ( ! empty( $query['where'] ) ) {
				$query['where'] = 'WHERE ' . implode( ' AND ', $query['where'] );
			}

			$query   = implode( ' ', $query );
			$results = $wpdb->get_results( $query, ARRAY_A ); // @codingStandardsIgnoreLine
			$results = array_column( $results, 'group_id' );

			return $results;
		}

		public function get_min_date() {
			global $wpdb;

			$min_date = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT MIN( `date` ) AS "min_date" FROM %1s ', // @codingStandardsIgnoreLine
					$wpdb->prefix . 'wc_pwf_filters',
				),
			);

			return $min_date;
		}

		public function get_languages() {
			global $wpdb;

			$results = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT lang FROM %1s GROUP BY lang', // @codingStandardsIgnoreLine
					$wpdb->prefix . 'wc_pwf_filters',
				),
				'ARRAY_A',
			);

			$results = array_column( $results, 'lang' );

			return $results;
		}
	}
}
