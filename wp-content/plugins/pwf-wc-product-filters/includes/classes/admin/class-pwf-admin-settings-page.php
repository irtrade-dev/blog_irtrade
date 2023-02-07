<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Pwf_Admin_Settings_Page' ) ) {

	class Pwf_Admin_Settings_Page {

		public static function register() {
			$plugin = new self();
			add_action( 'init', array( $plugin, 'init' ) );
		}

		public function init() {
			add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
			add_action( 'admin_init', array( $this, 'setup_sections' ) );
			add_action( 'admin_init', array( $this, 'setup_fields' ) );

			$validate_options = array(
				'pwf_shop_pretty_urls_prefixed',
				'pwf_transient_time',
				'envato_purchase_code_28181010',
				'pwf_shop_enable_pretty_links',
				'pwf_shop_theme_compitable',
				'pwf_shop_analytics',
				'pwf_shop_analytics_save_user_id',
			);

			foreach ( $validate_options as $option ) {
				add_filter( 'sanitize_option_' . $option, array( $this, 'sanitize_fields' ), 10, 3 );
			}
		}

		/**
		 * Define Enable|Disable
		 */
		protected static function get_enable_disable_options() {
			return array(
				'enable'  => esc_html__( 'Enable', 'pwf-woo-filter' ),
				'disable' => esc_html__( 'Disable', 'pwf-woo-filter' ),
			);
		}

		/**
		 * Create settings page
		 *
		 * @since 1.6.6
		 */
		public function add_settings_page() {
			$submenu = add_submenu_page(
				'edit.php?post_type=pwf_woofilter',
				'Filters Settings',
				'Settings',
				'manage_options',
				'pwf-settings',
				array( $this, 'render_settings_page' ),
				10
			);

			add_action( 'admin_print_styles-' . $submenu, array( $this, 'enqueue_styles' ) );
		}

		/**
		 * @since 1.6.6
		 */
		public function enqueue_styles() {
			wp_enqueue_style( 'prowoofilteradmin-settings', PWF_WOO_FILTER_URI . '/assets/css/admin/settings.css', '', PWF_WOO_FILTER_VER );
		}

		public function render_settings_page() {
			// check user capabilities
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			settings_errors();
			?>
				<div class="wrap pwf-settings-page">
					<h2 id="pwf_filter_fields">PWF Settings</h2>
					<form method="POST" action="options.php">
						<?php
						settings_fields( 'pwf_filter_fields' );
						do_settings_sections( 'pwf_filter_fields' );
						submit_button();
						?>
					</form>

					<form method="post" name="pwf-button-settings">
					<p class="pwf-buttons">
						<input type="submit" name="generate-pretty-urls" id="generate-pretty-urls" class="button button-large" value="<?php esc_html_e( 'Regenerate pretty URLs', 'pwf-woo-filter' ); ?>" title="PRO" disabled>
						<input type="submit" name="delete-cache-transients" id="delete-cache-transients" class="button button-large" value="<?php esc_html_e( 'Clear cached terms', 'pwf-woo-filter' ); ?>" title="PRO" disabled>
						<a href="https://codecanyon.net/item/pwf-woocommerce-product-filters/28181010" class="add-new-rule button button-large">GO TO PRO</a>
					</p>
					</form>
				</div>
			<?php
		}

		public function setup_sections() {
			add_settings_section(
				'pwf_filters_main_section',
				'',
				'',
				'pwf_filter_fields'
			);
		}

		public function setup_fields() {
			$fields = array();

			$fields[] = array(
				'name'    => esc_html__( 'Enable Pretty URLs', 'pwf-woo-filter' ),
				'id'      => 'pwf_shop_enable_pretty_links',
				'default' => 'disable',
				'type'    => 'select',
				'options' => self::get_enable_disable_options(),
				'is_pro'  => true,
			);
			$fields[] = array(
				'name'        => esc_html__( 'Pretty URLs prefixed', 'pwf-woo-filter' ),
				'desc'        => esc_html__( 'Fixing the conflict between the plugin pretty URLs links and the website sitemap or product taxonomies link.', 'pwf-woo-filter' ),
				'id'          => 'pwf_shop_pretty_urls_prefixed',
				'default'     => '',
				'type'        => 'text',
				'placeholder' => 'filters',
				'is_pro'      => true,
			);
			$fields[] = array(
				'name'              => esc_html__( 'Transient time', 'pwf-woo-filter' ),
				'desc'              => esc_html__( 'Set transient time in seconds.', 'pwf-woo-filter' ),
				'id'                => 'pwf_transient_time',
				'autoload'          => false,
				'default'           => '',
				'type'              => 'number',
				'custom_attributes' => array(
					'min'  => 60,
					'step' => 60,
				),
				'is_pro'            => true,
			);

			$fields[] = array(
				'name'    => esc_html__( 'Theme Comitable', 'pwf-woo-filter' ),
				'id'      => 'pwf_shop_theme_compitable',
				'default' => 'disable',
				'type'    => 'select',
				'options' => self::get_enable_disable_options(),
				'desc'    => '',
				'is_pro'  => true,
			);
			$fields[] = array(
				'name'    => esc_html__( 'Analytics', 'pwf-woo-filter' ),
				'id'      => 'pwf_shop_analytics',
				'default' => 'enable',
				'type'    => 'select',
				'options' => self::get_enable_disable_options(),
			);
			$fields[] = array(
				'name'    => esc_html__( 'User ID', 'pwf-woo-filter' ),
				'desc'    => esc_html__( 'Save The ID for a login user in the analtics database.', 'pwf-woo-filter' ),
				'id'      => 'pwf_shop_analytics_save_user_id',
				'default' => 'disable',
				'type'    => 'select',
				'options' => self::get_enable_disable_options(),
				'is_pro'  => true,
			);

			foreach ( $fields as $field ) {

				add_settings_field(
					$field['id'],
					$field['name'],
					array( $this, 'field_callback' ),
					'pwf_filter_fields',
					'pwf_filters_main_section',
					$field
				);

				register_setting( 'pwf_filter_fields', $field['id'] );
			}
		}

		public function field_callback( $arguments ) {
			$value = get_option( $arguments['id'] );

			if ( ! $value && isset( $arguments['default'] ) ) {
				$value = $arguments['default'];
			}

			if ( isset( $arguments['is_pro'] ) ) {
				$value = $arguments['default'];
			}

			switch ( $arguments['type'] ) {
				case 'text':
				case 'password':
				case 'number':
					$disabled    = '';
					$placeholder = $arguments['placeholder'] ?? '';
					if ( isset( $arguments['is_pro'] ) ) {
						$placeholder = 'PRO';
						$disabled    = 'disabled';
					}
					printf(
						'<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" %5$s />',
						esc_attr( $arguments['id'] ),
						esc_attr( $arguments['type'] ),
						esc_attr( $placeholder ),
						esc_attr( $value ),
						esc_attr( $disabled ),
					);
					break;
				case 'textarea':
					printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['id'], $arguments['placeholder'], $value );
					break;
				case 'select':
				case 'multiselect':
					if ( ! empty( $arguments['options'] ) && is_array( $arguments['options'] ) ) {
						$attributes     = '';
						$options_markup = '';
						if ( ! is_array( $value ) ) {
							$value = array( $value );
						}
						foreach ( $arguments['options'] as $key => $label ) {
							$disabled = '';
							if ( isset( $arguments['is_pro'] ) && $arguments['is_pro'] && ! in_array( $key, $value, true ) ) {
								$disabled = 'disabled';
								$label    = $label . ' - PRO';
							}
							$options_markup .= sprintf(
								'<option value="%s" %s %s>%s</option>',
								esc_attr( $key ),
								selected( $value[ array_search( $key, $value, true ) ], $key, false ),
								esc_attr( $disabled ),
								esc_attr( $label )
							);
						}
						if ( 'multiselect' === $arguments['type'] ) {
							$attributes = ' multiple="multiple" ';
						}
						printf( '<select name="%1$s" id="%1$s" %2$s>%3$s</select>', $arguments['id'], $attributes, $options_markup );
					}
					break;
				case 'radio':
				case 'checkbox':
					if ( ! empty( $arguments['options'] ) && is_array( $arguments['options'] ) ) {
						$options_markup = '';
						$iterator       = 0;
						foreach ( $arguments['options'] as $key => $label ) {
							$iterator++;
							$options_markup .= sprintf(
								'<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>',
								esc_attr( $arguments['id'] ),
								esc_attr( $arguments['type'] ),
								esc_attr( $key ),
								checked( $value[ array_search( $key, $value, true ) ], $key, false ),
								esc_attr( $label ),
								esc_attr( $iterator )
							);
						}
						printf( '<fieldset>%s</fieldset>', $options_markup );
					}
					break;
			}

			if ( isset( $arguments['desc'] ) && ! empty( $arguments['desc'] ) ) {
				printf( '<p class="description">%s</p>', $arguments['desc'] );
			}
		}

		/**
		 * @param string $value
		 * @param string $option name
		 * @param string old value
		 */
		public function sanitize_fields( $value, $option, $old_value ) {

			switch ( $option ) {
				case 'pwf_shop_pretty_urls_prefixed':
					if ( ! empty( $value ) ) {
						$value = '';
					}
					break;
				case 'pwf_transient_time':
					if ( ! empty( $value ) ) {
						$value = '';
					}
					break;
				case 'pwf_shop_analytics':
					if ( ! empty( $value ) ) {
						$value = sanitize_key( $value );
					}
					break;
				case 'pwf_shop_enable_pretty_links':
				case 'pwf_shop_theme_compitable':
				case 'pwf_shop_analytics_save_user_id':
					if ( ! empty( $value ) ) {
						$value = sanitize_key( $value );
					}
					break;
			}

			return $value;
		}
	}

	Pwf_Admin_Settings_Page::register();
}
