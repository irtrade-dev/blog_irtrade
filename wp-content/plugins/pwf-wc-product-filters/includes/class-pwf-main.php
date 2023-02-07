<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Main' ) ) {

	class Pwf_Main {

		/**
		* The unique instance of the plugin.
		*
		* @var Pwf_Main
		*/
		private static $instance;
		private static $is_network_plugin;
		private static $plugin_name = 'pwfwoofilter/pwfwoofilter.php';

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

		/**
		 * @since 1.0.0, 1.1.0
		 */
		private function __construct() {
			add_action( 'init', array( $this, 'init' ), 10 );
			add_action( 'init', array( $this, 'register_filter_post_type' ), 10 );

			add_filter( 'wp_kses_allowed_html', array( $this, 'wp_kses_allowed_html_tag' ), 10, 1 );

			add_action( 'wp_trash_post', array( $this, 'wp_trash_post' ), 10, 1 );
			add_action( 'delete_term', array( $this, 'delete_term' ), 10, 4 );
			add_action( 'wp_head', array( $this, 'add_meta_generator' ), 11 );
		}

		public function init() {
			load_plugin_textdomain( 'pwf-woo-filter', false, PWF_WOO_FILTER_DIR_DOMAIN . '/languages/' );
			add_shortcode( 'pwf_filter', array( $this, 'add_shortcode' ) );

			Pwf_Filter_Manager::set_pwf_global_variables();
		}

		public function register_filter_post_type() {
			$labels = array(
				'name'               => esc_html_x( 'Filter', 'post type general name', 'pwf-woo-filter' ),
				'singular_name'      => esc_html_x( 'Filter', 'post type singular name', 'pwf-woo-filter' ),
				'menu_name'          => esc_html_x( 'Filters', 'admin menu', 'pwf-woo-filter' ),
				'name_admin_bar'     => esc_html_x( 'Filter', 'add new on admin bar', 'pwf-woo-filter' ),
				'add_new'            => esc_html_x( 'Add new', 'Filter', 'pwf-woo-filter' ),
				'add_new_item'       => esc_html__( 'Add new Filter', 'pwf-woo-filter' ),
				'new_item'           => esc_html__( 'New Filter', 'pwf-woo-filter' ),
				'edit_item'          => esc_html__( 'Edit Filter', 'pwf-woo-filter' ),
				'view_item'          => esc_html__( 'View Filter', 'pwf-woo-filter' ),
				'all_items'          => esc_html__( 'Filters', 'pwf-woo-filter' ),
				'search_items'       => esc_html__( 'Search Filters', 'pwf-woo-filter' ),
				'parent_item_colon'  => esc_html__( 'Parent Filters:', 'pwf-woo-filter' ),
				'not_found'          => esc_html__( 'No Filters found.', 'pwf-woo-filter' ),
				'not_found_in_trash' => esc_html__( 'No Filters found in trash.', 'pwf-woo-filter' ),
			);

			$post_type_args = array(
				'public'              => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'show_ui'             => true,
				'show_in_nav_menus'   => true,
				'hierarchical'        => false,
				'supports'            => false,
				'capability_type'     => 'post',
				'rewrite'             => false,
				'query_var'           => false,
				'has_archive'         => false,
				'label'               => 'Filter',
				'labels'              => $labels,
				'show_in_rest'        => true,
				'menu_icon'           => 'dashicons-filter',
			);

			register_post_type( 'pwf_woofilter', $post_type_args );
		}

		public function add_shortcode( $atts ) {
			// @codingStandardsIgnoreLine
			extract( shortcode_atts( array( 'id' => '' ), $atts ) );
			if ( ! absint( $id ) ) {
				return;
			}

			return wp_kses_post( Pwf_Filter_Manager::excute_plugin_shortcode_widget( $id ) );
		}

		public function get_title( $object ) {
			return esc_attr( get_the_title( $object['id'] ) );
		}

		public function get_filter_items( $object ) {
			$render_filter = new Pwf_Render_Filter( $object['id'] );
			$filter_items  = new Pwf_Api_Prepare_Filter_Post( $render_filter->get_filter_items_data(), $render_filter->get_filter_setting() );
			$filter_items  = $filter_items->get_filter_items();

			return $filter_items;
		}

		public function wp_kses_allowed_html_tag( $tags ) {
			$div = array(
				'aria-required' => true,
			);

			$input = array(
				'id'            => true,
				'type'          => true,
				'name'          => true,
				'class'         => true,
				'value'         => true,
				'placeholder'   => true,
				'aria-required' => true,
				'checked'       => true,
				'disabled'      => true,
				'min'           => true,
				'max'           => true,
				'data-*'        => true,
			);

			$select = array(
				'id'                 => true,
				'name'               => true,
				'class'              => true,
				'aria-required'      => true,
				'data-default-value' => true,
				'multiple'           => true,
			);

			$option = array(
				'value'      => true,
				'selected'   => true,
				'data-slug'  => true,
				'data-title' => true,
				'disabled'   => true,
			);

			if ( isset( $tags['div'] ) ) {
				$tags['div'] = array_merge( $tags['div'], $div );
			} else {
				$tags['div'] = $div;
			}

			if ( isset( $tags['input'] ) ) {
				$tags['input'] = array_merge( $tags['input'], $input );
			} else {
				$tags['input'] = $input;
			}

			if ( isset( $tags['select'] ) ) {
				$tags['select'] = array_merge( $tags['select'], $select );
			} else {
				$tags['select'] = $select;
			}

			if ( isset( $tags['option'] ) ) {
				$tags['option'] = array_merge( $tags['option'], $option );
			} else {
				$tags['option'] = $option;
			}

			$tags['optgroup'] = array(
				'value' => true,
				'label' => true,
			);

			return $tags;
		}

		/**
		 * Delete shop archive or pages realted to filter
		 *
		 * @since 1.1.0
		 */
		public function wp_trash_post( $post_id ) {
			$post_ids = array();
			if ( isset( $_GET['post'] ) && is_array( $_GET['post'] ) ) {
				foreach ( $_GET['post'] as $post_id ) {
					array_push( $post_ids, absint( $post_id ) );
				}
			} else {
				$post_ids = array( absint( $post_id ) );
			}

			$post = get_post( $post_ids[0], 'ARRAY_A' );

			$is_option_change  = false;
			$filter_option_ids = get_option( 'pwf_woo_query_filters', '' );
			if ( ! empty( $filter_option_ids ) ) {
				if ( 'pwf_woofilter' === $post['post_type'] ) {
					$filter_value_ids = array_map( 'absint', array_values( $filter_option_ids ) );
					foreach ( $post_ids as $post_id ) {
						$post_id = absint( $post_id );
						if ( in_array( $post_id, $filter_value_ids, true ) ) {
							$is_option_change  = true;
							$filter_option_ids = Pwf_Meta::remove_filter_id_from_saved_option( $filter_option_ids, $post_id );
						}
					}
				} elseif ( 'page' === $post['post_type'] ) {
					$filter_key_ids = array_keys( $filter_option_ids );
					foreach ( $post_ids as $post_id ) {
						$post_id = 'page__' . $post_id;
						if ( in_array( $post_id, $filter_key_ids, true ) ) {
							$is_option_change = true;
							unset( $filter_option_ids[ $post_id ] );
						}
					}
				}

				if ( $is_option_change ) {
					update_option( 'pwf_woo_query_filters', $filter_option_ids, 'no' );
				}
			}
		}

		/**
		 * Delete term from option name pwf_woo_main_query_filters
		 *
		 * @since 1.1.0
		 */
		public function delete_term( $term, $tt_id, $taxonomy, $deleted_term ) {
			$filter_option_ids = get_option( 'pwf_woo_query_filters', '' );
			if ( ! empty( $filter_option_ids ) ) {
				$tax_object = get_taxonomy( $taxonomy );
				$tax_object = $tax_object->object_type;
				if ( isset( $tax_object[0] ) ) {
					$post_type      = $tax_object[0];
					$term_name      = $post_type . '__' . $taxonomy . '__' . $term;
					$filter_key_ids = array_keys( $filter_option_ids );
					if ( in_array( $term_name, $filter_key_ids, true ) ) {
						unset( $filter_option_ids[ $term_name ] );
						update_option( 'pwf_woo_query_filters', $filter_option_ids, 'no' );
					}
				}
			}
		}

		/**
		 * Add meta Generator
		 *
		 * @since 1.1.3
		 */
		public function add_meta_generator() {
			echo '<meta name="generator" content="PWF - WooCommerce Products Filter ' . esc_attr( PWF_WOO_FILTER_VER ) . '">';
		}
	}

	$pwf_core = Pwf_Main::get_instance();
}
