<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Admin_Main' ) ) {

	/**
	 * Enahancement admin
	 * @since 1.5.5
	 */
	class Pwf_Admin_Main {

		/**
		 * @since 1.1.0
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'admin_init' ), 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'remove_auto_save_script' ) );
			add_action( 'manage_pwf_woofilter_posts_custom_column', array( $this, 'fill_filter_post_type_columns' ), 10, 2 );
			add_filter( 'manage_pwf_woofilter_posts_columns', array( $this, 'render_filter_post_columns' ) );

			// Edit filter post
			add_action( 'admin_enqueue_scripts', array( $this, 'filter_post_enqueue_scripts_styles' ), 10 );
			add_action( 'wp_ajax_get_hierarchy_taxonomies_using_ajax', array( $this, 'get_hierarchy_taxonomies_using_ajax' ) );
			add_action( 'wp_ajax_get_group_taxonomies_using_ajax', array( $this, 'get_group_taxonomies_using_ajax' ) );
			add_action( 'wp_ajax_get_taxonomies_using_ajax', array( $this, 'get_taxonomies_using_ajax' ) );
			add_action( 'wp_ajax_save_filter_post', array( $this, 'save_filter_post' ) );

			// For Analytic page
			add_action( 'admin_menu', array( $this, 'create_analytic_page' ), 100 );

			add_filter( 'plugin_action_links_' . PWF_WOO_FILTER_BASENAMEFILE, array( $this, 'go_to_pro' ), 10, 1 );

			add_action( 'admin_notices', array( $this, 'admin_notice_update_message' ), 10 );
			add_action( 'admin_init', array( $this, 'notice_dismissed' ), 10 );
		}

		public function filter_post_enqueue_scripts_styles() {
			global $pagenow, $post;

			if ( ! empty( $pagenow ) && 'post-new.php' === $pagenow || 'post.php' === $pagenow ) {
				if ( 'pwf_woofilter' === $post->post_type ) {
					new Pwf_Meta();
				}
			}
			if ( ! empty( $pagenow ) && 'plugins.php' === $pagenow ) {
				wp_enqueue_style( 'pwf-plugins-page', PWF_WOO_FILTER_URI . '/assets/css/admin/plugins-page.css', '', PWF_WOO_FILTER_VER );
			}
		}

		public function get_hierarchy_taxonomies_using_ajax() {
			$pwf_meta = new Pwf_Meta();
			$pwf_meta->get_hierarchy_taxonomies_using_ajax();
		}

		public function get_group_taxonomies_using_ajax() {
			$pwf_meta = new Pwf_Meta();
			$pwf_meta->get_group_taxonomies_using_ajax();
		}

		public function get_taxonomies_using_ajax() {
			$pwf_meta = new Pwf_Meta();
			$pwf_meta->get_taxonomies_using_ajax();
		}

		public function save_filter_post() {
			$pwf_meta = new Pwf_Meta();
			$pwf_meta->save_filter_post();
		}

		public function create_analytic_page() {
			$submenu = add_submenu_page(
				'edit.php?post_type=pwf_woofilter',
				'Anyalytic Filters',
				'Anyalytics',
				'manage_options',
				'pwf-anyalytics',
				array( $this, 'render_analytic_page' ),
				20
			);

			add_action( 'admin_print_styles-' . $submenu, array( $this, 'analytic_page_enqueue_styles' ) );
			add_action( 'admin_print_scripts-' . $submenu, array( $this, 'analytic_page_enqueue_scripts' ) );
		}

		public function render_analytic_page() {
			$analytic = new Pwf_Analytic_Page();
			$analytic->render_analytic_page();
		}

		public function analytic_page_enqueue_styles() {
			Pwf_Analytic_Page::admin_settings_enqueue_styles();
		}

		public function analytic_page_enqueue_scripts() {
			$analytic = new Pwf_Analytic_Page();
			$analytic->admin_settings_enqueue_scripts();
		}

		public function remove_auto_save_script() {
			switch ( get_post_type() ) {
				case 'pwf_woofilter':
					wp_dequeue_script( 'autosave' );
					break;
			}
		}

		public function fill_filter_post_type_columns( $column, $post_id ) {
			if ( 'pwfshortcode' === $column ) {
				echo '[pwf_filter id="' . absint( $post_id ) . '"]';
			}
			if ( 'pwfquerytype' === $column ) {
				$meta       = get_post_meta( absint( $post_id ), '_pwf_woo_post_filter', true );
				$settings   = $meta['setting'];
				$query_type = $settings['filter_query_type'] ?? '';
				if ( ! empty( $query_type ) ) {
					if ( 'main_query' === $query_type ) {
						$text = esc_html__( 'Main Query', 'pwf-woo-filter' );
					} else {
						$text = esc_html__( 'Custom Query', 'pwf-woo-filter' );
					}
					echo '<strong>' . $text . '</strong>';
				}
			}
			if ( 'pwfpages' === $column ) {
				$meta       = get_post_meta( absint( $post_id ), '_pwf_woo_post_filter', true );
				$settings   = $meta['setting'];
				$query_type = $settings['filter_query_type'] ?? '';
				if ( ! empty( $query_type ) ) {
					if ( 'main_query' === $query_type ) {
						$pages = self::get_filter_pages( $post_id );
						echo $pages;
					} else {
						$pages = self::get_filter_pages( $post_id );
						echo $pages;
					}
				}
			}
		}

		/**
		 * Get filter pages and display it in columns
		 * @param int $filter_id
		 *
		 * @since 1.1.0
		 *
		 * @return string
		 */
		protected static function get_filter_pages( $filter_id ) {
			$filter_pages = array();
			$pages        = get_option( 'pwf_woo_query_filters', '' );

			if ( ! empty( $pages ) ) {
				$results = array();

				foreach ( $pages as $page_type => $page_filter_id ) {
					if ( absint( $page_filter_id ) === $filter_id ) {
						array_push( $results, $page_type );
					}
				}

				if ( ! empty( $results ) ) {
					foreach ( $results as $page_type ) {
						if ( strpos( $page_type, '__archive' ) !== false ) {
							$type = 'archive';
						} elseif ( strpos( $page_type, 'page__' ) !== false ) {
							$type = 'page';
						} else {
							$type = 'taxonomy';
						}

						$split = explode( '__', $page_type );
						if ( 'archive' === $type ) {
							$label = '<strong>All ' . $split[0] . ' ' . $split[1] . '</strong>';
							array_push( $filter_pages, $label );
						} elseif ( 'taxonomy' === $type ) {
							if ( 'all' === $split[2] ) {
								$taxonomy = get_taxonomy( esc_attr( $split[1] ) );
								if ( ! is_wp_error( $taxonomy ) ) {
									$str = '<strong>All ' . $split[0] . ' ' . $taxonomy->label . '</strong>';
									array_push( $filter_pages, $str );
								}
							} else {
								$term = get_term( absint( $split[2] ), esc_attr( $split[1] ) );
								if ( ! is_wp_error( $term ) ) {
									array_push( $filter_pages, $term->name );
								}
							}
						} elseif ( 'page' === $type ) {
							$page = get_post( absint( $split[1] ) );
							if ( $page ) {
								array_push( $filter_pages, $page->post_title );
							}
						}
					}
				}
			}

			if ( ! empty( $filter_pages ) ) {
				$filter_pages = implode( ', ', $filter_pages );
			} else {
				$filter_pages = '';
			}

			return $filter_pages;
		}

		public function render_filter_post_columns( $columns ) {
			$date = $columns['date'];
			unset( $columns['date'] );
			$columns['pwfshortcode'] = 'Shortcode';
			$columns['pwfquerytype'] = 'Query Type';
			$columns['pwfpages']     = 'Pages';
			$columns['date']         = $date;

			return $columns;
		}

		public function admin_init() {
			self::plugin_version();
		}

		/**
		 * @since 1.1.0
		 */
		public static function plugin_version() {
			$old_option_version = get_option( 'pwf_woocommerce_version' );
			if ( false !== $old_option_version ) {
				delete_option( 'pwf_woocommerce_version' );
			}

			$version = get_option( 'pwf_woocommerce_free_version' );
			if ( false !== $version && version_compare( $version, PWF_WOO_FILTER_VER, '<' ) ) {
				self::do_update();
				update_option( 'pwf_woocommerce_free_version', PWF_WOO_FILTER_VER );
				update_option( 'pwf_woocommerce_free_version_update_message', 'false' );
			} elseif ( false === $version ) {
				update_option( 'pwf_woocommerce_free_version', PWF_WOO_FILTER_VER );
				update_option( 'pwf_woocommerce_free_version_update_message', 'true' );
			}
		}

		/**
		 * since 1.1.0
		 */
		protected static function do_update() {
			$update_message = get_option( 'pwf_woocommerce_free_version_update_message' );
			if ( false === $update_message ) {
				update_option( 'pwf_woocommerce_free_version_update_message', 'false' );
			}
		}

		public function go_to_pro( $links ) {
			$pro_link = array( '<a href="https://codecanyon.net/item/pwf-woocommerce-product-filters/28181010" class="pwf-go-to-pro">Go To PRO</a>' );
			$links    = array_merge( $links, $pro_link );

			return $links;
		}

		public function admin_notice_update_message() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$update_message = get_option( 'pwf_woocommerce_free_version_update_message' );
			if ( false === $update_message || 'false' === $update_message ) {

				$option_page = admin_url( 'admin.php?page=wc-settings&tab=products&section=pwfwoofilter' );

				echo '<div class="notice  notice-info  is-dismissible">
				<h2>PWF WooCommerce Product Filters</h2>
				<p>Thank you for updating PWF - WooCommerce Products Filter.</p>				
				<p><span style="display: block; margin: 0.5em 0.5em 0 0; clear: both;"><strong><a href="?pwf-woocommerce-plugin-dismissed">Dismiss this notice</a></strong></span></p></div>';
			}
		}

		public function notice_dismissed() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if ( isset( $_GET['pwf-woocommerce-plugin-dismissed'] ) ) {
				update_option( 'pwf_woocommerce_free_version_update_message', 'true' );
			}
		}
	}

	new Pwf_Admin_Main();
}
