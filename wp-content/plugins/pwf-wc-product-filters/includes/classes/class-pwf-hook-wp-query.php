<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Hook_Wp_Query' ) ) {

	class Pwf_Hook_Wp_Query {

		/**
		* The unique instance of the plugin.
		*
		* @var Pwf_Hook_Wp_Query
		*/
		private static $instance;

		/**
		* The unique instance of the Pwf_Parse_Query_Vars.
		*
		* @var Pwf_Parse_Query_Vars
		*/
		private static $query_vars = null;

		private static $filter_id;
		private static $query_type;
		private static $filter_items;
		private static $filter_settings;
		private static $global_args;
		private static $filter_post_type;

		/**
		 * check if analtic add before
		 * On main query analytic can add more times
		 * This variable prevent form add twics
		 */
		private static $is_analytic_add = false;

		private static $page_number;

		/**
		 * @since 1.0.0, 1.1.0
		 */
		private function __construct() {
			add_action( 'pre_get_posts', array( $this, 'pre_get_posts_query' ), 20, 1 );
		}

		/**
		 * Gets an instance of our plugin.
		 *
		 * @return Pwf_Hook_Wp_Query
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Checking any filter post id integrated with current displaying page
		 * Extract filter post id from saved options
		 *
		 * @param array $args
		 *       $args = array(
		 *         'type'      => '', avaliable values archive | tax | page
		 *         'post_type' => '',
		 *         'tax_name'  => '',
		 *         'tax_id'    => '',
		 *         'page_id'   => '',
		 *       );
		 * @since 1.6.6
		 *
		 * @reuturn int|empty $filter_id
		 */
		protected static function extract_filter_id_from_options( $args ) {
			$filter_id         = '';
			$filter_option_ids = get_option( 'pwf_woo_query_filters', '' );

			if ( ! empty( $filter_option_ids ) && is_array( $filter_option_ids ) ) {
				if ( 'archive' === $args['page_type'] ) {
					$search_in  = array_keys( $filter_option_ids );
					$search_for = $args['post_type'] . '__archive';
					if ( in_array( $search_for, $search_in, true ) ) {
						$filter_id = $filter_option_ids[ $search_for ];
					}
				} elseif ( 'page' === $args['page_type'] ) {
					$all_pages        = array();
					$translated_pages = array();
					foreach ( $filter_option_ids as $page_id => $f_id ) {
						if ( strpos( $page_id, 'page__' ) !== false ) {
							$all_pages[ $page_id ] = $f_id;
						}
					}

					if ( ! empty( $all_pages ) ) {
						foreach ( $all_pages as $page_id => $f_id ) {
							$split = explode( '__', $page_id );
							if ( $args['page_id'] === $split[1] ) {
								$filter_id = $f_id;
							}
						}
					}
				} elseif ( 'taxonomy' === $args['page_type'] ) {
					$all_pages = array();
					// need to complete with translated term id do it like pages
					foreach ( $filter_option_ids as $page_id => $f_id ) {
						$split = explode( '__', $page_id );
						// Split must has three arguments
						if ( isset( $split[2] ) ) {
							if ( $args['taxonomy_name'] === $split[1] ) {
								$all_pages[ $page_id ] = $f_id;
							}
						}
					}

					if ( ! empty( $all_pages ) ) {
						$filter_id_for_current_tax = '';
						$filter_id_for_tax_all     = '';
						foreach ( $all_pages as $page_id => $f_id ) {
							$split = explode( '__', $page_id );
							if ( $args['taxonomy_name'] === $split[1] && absint( $split[2] ) === $args['taxonomy_id'] ) {
								$filter_id_for_current_tax = $f_id;
							} elseif ( $args['taxonomy_name'] === $split[1] && 'all' === $split[2] ) {
								$filter_id_for_tax_all = $f_id;
							}
						}

						if ( ! empty( $filter_id_for_current_tax ) || ! empty( $filter_id_for_tax_all ) ) {
							if ( ! empty( $filter_id_for_current_tax ) ) {
								$filter_id = $filter_id_for_current_tax;
							} elseif ( ! empty( $filter_id_for_tax_all ) ) {
								$filter_id = $filter_id_for_tax_all;
							}
						}
					}

					if ( empty( $filter_id ) ) {
						// Check post type archive if has a filter Ex, product__archive or post__archive
						$tax_object    = get_taxonomy( $args['taxonomy_name'] );
						$tax_object    = $tax_object->object_type;
						$tax_post_type = $tax_object[0];
						$search_for    = $tax_post_type . '__archive';
						$search_in     = array_keys( $filter_option_ids );

						if ( in_array( $search_for, $search_in, true ) ) {
							$filter_id = $filter_option_ids[ $search_for ];
						}
					}
				}
			}

			return $filter_id;
		}

		/**
		 * Dectecting is there any filter integrate with the current page
		 * This maybe post type archive or pages
		 *
		 * @param object $q WP_Query
		 *
		 * @since 1.6.5
		 *
		 * @return int|empty $filter_id
		 */
		protected function get_filter_id( $q ) {
			$filter_id = '';

			$args = array(
				'page_type'     => '',
				'post_type'     => '', // check if we can delete this
				'taxonomy_name' => '',
				'taxonomy_id'   => '',
				'page_id'       => '',
				'is_archive'    => 'false',
			);

			if ( $q->is_main_query() ) {
				if ( $q->is_archive() ) {
					if ( $q->is_post_type_archive() ) {
						if ( ! empty( $q->query_vars['post_type'] ) ) {
							$args['page_type'] = 'archive';
							$args['post_type'] = $q->query_vars['post_type'];
							if ( ( is_array( $args['post_type'] ) && in_array( 'product', $args['post_type'], true ) ) || 'product' === $args['post_type'] ) {
								$args['page_id'] = absint( get_option( 'woocommerce_shop_page_id' ) );
							}
							/**
							 * can add filter to get page id for other post type
							 * for blog get it from settings
							 */
						}
					} elseif ( $q->is_tax() || $q->is_category() || $q->is_tag() ) {
						$query_obj = $q->get_queried_object();

						$args['page_type']     = 'taxonomy';
						$args['taxonomy_id']   = $query_obj->term_id;
						$args['taxonomy_name'] = $query_obj->taxonomy;
					}

					if ( ! empty( $args['page_type'] ) ) {
						$args['is_archive'] = 'true';
					}
				} elseif ( $q->is_page() ) {
					$query_obj = $q->get_queried_object();
					if ( isset( $query_obj->ID ) ) {
						$args['page_type'] = 'page';
						$args['page_id']   = $query_obj->ID;
					} elseif ( isset( $q->query_vars ) && isset( $q->query_vars['page_id'] ) ) {
						$args['page_type'] = 'page';
						$args['page_id']   = $q->query_vars['page_id'];
					}
				} else {
					// Checking is this a blog page
					$is_blog_page = false;
					if ( is_front_page() && is_home() ) {
						$is_blog_page = true;
					} elseif ( is_front_page() ) {
						// static homepage
					} elseif ( is_home() ) {
						$is_blog_page = true;
					}

					if ( $is_blog_page ) {
						$args['page_type']  = 'archive';
						$args['post_type']  = 'post';
						$args['is_archive'] = 'true';
						$page_for_posts     = get_option( 'page_for_posts' );
						if ( $page_for_posts ) {
							$args['page_id'] = absint( $page_for_posts );
						}
					}
				}
			}

			if ( ! empty( $args['page_type'] ) ) {
				$filter_id = self::extract_filter_id_from_options( $args );
			}

			if ( ! empty( $filter_id ) ) {
				self::$global_args = $args;
			}

			return $filter_id;
		}

		/**
		 * Define all varaiables that are using the filter
		 *
		 * @param int $filter_id
		 *
		 * @since 1.6.6
		 */
		protected function set_filter_variables( $filter_id ) {
			$filter_id = Pwf_Filter_Manager::get_filter_id( $filter_id );
			$filter    = Pwf_Filter_Manager::get_filter_settings_and_items( $filter_id );

			if ( ! empty( $filter ) ) {
				self::$filter_id        = $filter_id;
				self::$filter_items     = $filter['items'];
				self::$filter_settings  = $filter['setting'];
				self::$query_type       = $filter['setting']['filter_query_type'];
				self::$filter_post_type = $filter['setting']['post_type'];
			}
		}

		/**
		 * Set plugin global variables after integrate with query hook
		 *
		 */
		protected function set_global_variables() {
			$args = array(
				'filter_id'     => self::$filter_id,
				'post_type'     => self::$filter_post_type,
				'query_vars'    => self::$query_vars,
				'is_archive'    => self::$global_args['is_archive'],
				'page_type'     => self::$global_args['page_type'],
				'taxonomy_name' => self::$global_args['taxonomy_name'],
				'taxonomy_id'   => self::$global_args['taxonomy_id'],
				'page_id'       => self::$global_args['page_id'],
			);

			Pwf_Filter_Manager::set_pwf_global_variables( $args );
		}

		/**
		 * Check if that the wp_query requires to hook.
		 * The page can contain many querys to display products
		 * On the forntend like widgets, shortcode, ...
		 * Our plugin check only by limit
		 *
		 * @param object $q wp_query
		 *
		 * @since 1.1.0
		 */
		protected static function is_that_exact_query_requires( $q ) {
			$current_post_type = $q->get( 'post_type' );

			if ( empty( $current_post_type ) ) {
				return false;
			} else {
				if ( ! is_array( $current_post_type ) ) {
					$current_post_type = array( $current_post_type );
				}
			}

			if ( ! in_array( self::$filter_post_type, $current_post_type, true ) ) {
				return false;
			}

			$result = false;
			$atts   = self::get_shortcode_atts();
			if ( ! empty( $atts ) && is_array( $atts ) ) {
				$limit    = $atts['limit'] ?? -1; // -1 mean display all products for shortcode
				$per_page = $q->get( 'posts_per_page' );
				if ( ! empty( $limit ) && ! empty( $per_page ) ) {
					if ( absint( $limit ) === absint( $per_page ) ) {
						$result = true;
					}
				}
			}

			/**
			 * Develop can do another check.
			 * To be sure this is the wp_query requires to hook
			 *
			 * @param bool $result
			 * @param int $filter_id
			 * @param object wp_query
			 * @param array shortcode attributes
			 */
			$result = apply_filters( 'pwf_check_is_that_taregt_custom_query', $result, self::$filter_id, $q, $atts );

			return $result;
		}

		/**
		 * Hook any WP Query maybe main or custom query.
		 * Checking if any filter post require to hook this query.
		 *
		 * Useful if clients go directly or using option no ajax
		 *
		 * @param object $q WP_Query
		 *
		 * @since 1.6.6
		 */
		public function pre_get_posts_query( $q ) {
			$filter_id = $this->get_filter_id( $q );

			if ( empty( $filter_id ) ) {
				return;
			}

			$this->set_filter_variables( $filter_id );

			if ( null === self::$filter_items ) {
				return;
			}

			$this->set_global_variables();

			// This hook doesn't require any more
			remove_action( 'pre_get_posts', array( $this, 'pre_get_posts_query' ), 20, 1 );

			if ( 'main_query' === self::$query_type ) {
				$this->prepare_target_query( $q );

			}
		}

		/**
		 * This use to hook main query and custom query
		 *
		 * @param $q wp_query
		 *
		 * @since 1.5.4, 1.6.6
		 *
		 */
		public function prepare_target_query( $q ) {
			/**
			 * check if selected options exists
			 * Fixed the issue when the filter displays before WP_Query specially when filter set to custom_query
			 */
			if ( ! empty( $GLOBALS['pwf_main_query']['query_vars'] ) ) {
				self::$query_vars = $GLOBALS['pwf_main_query']['query_vars'];
			} else {
				$selected_options = Pwf_Filter_Manager::get_user_selected_options( self::$filter_id, self::$filter_items );
				self::$query_vars = new Pwf_Parse_Query_Vars( self::$filter_id, $selected_options );
			}

			/**
			 * Useful with custom query if the filter items displays before WP_Query that displays posts/products
			 */
			$args = array(
				'filter_integrated' => 'yes',
			);
			if ( empty( $GLOBALS['pwf_main_query']['query_vars'] ) ) {
				$args['query_vars'] = self::$query_vars;
			}
			Pwf_Filter_Manager::set_pwf_global_variables( $args );

			$orderby    = self::$query_vars->get_products_orderby();
			$tax_query  = self::$query_vars->get_tax_query_filter_items();
			$meta_query = self::$query_vars->get_meta_query();

			if ( ! empty( $tax_query ) ) {
				if ( ! empty( $q->get( 'tax_query' ) ) ) {
					$tax_query = array_merge( $q->get( 'tax_query' ), $tax_query );
				}
				$q->set( 'tax_query', $tax_query );
			}

			if ( ! empty( $meta_query ) ) {
				$meta_query = array_merge( $q->get( 'meta_query' ), $meta_query );
				$q->set( 'meta_query', $meta_query );
			}

			if ( null !== self::$page_number ) {
				$q->set( 'paged', self::$page_number );
			}

			if ( 'product' === self::$filter_post_type ) {
				$hook_woo_query = new Pwf_Hook_Woocommerce_Query( self::$query_vars, $q, self::$query_type );
			} elseif ( ! empty( $orderby ) ) {
				$orderby = is_array( $orderby ) ? implode( '', $orderby ) : $orderby;
				$q->set( 'orderby', $orderby );
			}

			add_filter( 'the_posts', array( $this, 'remove_product_query_filters' ), 20, 1 );
		}

		public function remove_product_query_filters( $posts ) {
			self::add_anlaytic_data();

			return $posts;
		}

		protected static function add_anlaytic_data() {
			global $wp_query;

			if ( self::$is_analytic_add ) {
				return;
			}

			$selected_items = self::$query_vars->selected_items();
			if ( empty( $selected_items ) ) {
				return;
			}

			$anlaytic = get_option( 'pwf_shop_analytics', 'disable' );
			if ( 'disable' === $anlaytic ) {
				return;
			}

			$orderby = self::$query_vars->get_products_orderby();
			if ( ! empty( $orderby ) ) {
				$orderby = is_array( $orderby ) ? implode( ',', $orderby ) : $orderby;
			} elseif ( isset( $_GET['orderby'] ) ) {
				$orderby = esc_attr( $_GET['orderby'] );
			}

			if ( ! empty( $orderby ) ) {
				$selected_items['orderby'] = array(
					'values' => array( $orderby ),
					'type'   => 'orderby',
				);
			}

			$filter_data = array(
				'filter_post_id' => self::$filter_id,
				'products_count' => $wp_query->found_posts,
				'from'           => 1,
				'query_string'   => self::$query_vars->get_query_string(),
			);

			$analytic_data = array(
				'filter_data'     => $filter_data,
				'selected_values' => $selected_items,
			);

			self::$is_analytic_add = true;

			$analytic = new Pwf_Analytic_Query( $analytic_data );
		}
	}

	$pwf_hook_wp_query = Pwf_Hook_Wp_Query::get_instance();
}
