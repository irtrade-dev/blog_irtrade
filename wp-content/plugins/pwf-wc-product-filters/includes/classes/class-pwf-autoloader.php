<?php
/**
 * Autoloader class.
 */
class Pwf_Autoloader {

	/**
	 * Path to the includes directory.
	 *
	 * @var string
	 */
	private $php_classes = '';

	/**
	 * The Constructor.
	 */
	public function __construct() {
		if ( function_exists( '__autoload' ) ) {
			spl_autoload_register( '__autoload' );
		}
		add_action( 'init', array( $this, 'wp_loaded' ) );
	}

	/**
	 * load_file( $path )
	 *
	 * @param  string $path File path.
	 * @return bool Successful or not.
	 */
	public static function load_file( $path ) {
		if ( $path && is_readable( $path ) ) {
			include_once $path;
			return true;
		}
		return false;
	}

	public function wp_loaded() {
		$this->classes = $this->get_autoload_classes();
		spl_autoload_register( array( $this, 'autoload' ) );
	}

	/**
	 * Auto-load WC classes on demand to reduce memory consumption.
	 *
	 * @param string $class Class name.
	 */
	public function autoload( $class ) {
		$path  = '';
		$class = strtolower( $class );
		if ( ! array_key_exists( $class, $this->classes ) ) {
			return;
		}

		$path = $this->classes[ $class ];
		if ( ! empty( $path ) ) {
			self::load_file( $path );
		}
	}

	private function get_autoload_classes() {
		$path    = PWF_WOO_FILTER_DIR;
		$classes = array(
			'pwf_filter_products'        => $path . 'includes/classes/woocommerce/class-pwf-filter-products.php',
			'pwf_hook_woocommerce_query' => $path . 'includes/classes/woocommerce/class-pwf-hook-woocommerce-query.php',
			'pwf_woo_utilities'          => $path . 'includes/classes/woocommerce/class-pwf-woo-utilities.php',
			'pwf_parse_query_vars'       => $path . 'includes/class-pwf-parse-query-vars.php',
			'pwf_render_filter_fields'   => $path . 'includes/render/class-pwf-render-filter-fields.php',
			'pwf_db_utilities'           => $path . 'includes/classes/class-pwf-db-utilities.php',
			'pwf_analytic_query'         => $path . 'includes/classes/class-pwf-analytic-query.php',
			'pwf_filter_manager'         => $path . 'includes/classes/class-pwf-filter-manager.php',
			'pwf_walker'                 => $path . 'includes/walker/class-pwf-walker.php',
			'pwf_walker_radio'           => $path . 'includes/walker/class-pwf-walker-radio.php',
			'pwf_walker_checkbox'        => $path . 'includes/walker/class-pwf-walker-checkbox.php',
			'pwf_walker_textlist'        => $path . 'includes/walker/class-pwf-walker-textlist.php',
			'pwf_walker_dropdown_list'   => $path . 'includes/walker/class-pwf-walker-dropdow-list.php',
			'pwf_clear_transients'       => $path . 'includes/classes/admin/class-pwf-clear-transients.php',
			'pwf_meta'                   => $path . 'includes/classes/admin/meta/class-pwf-meta.php',
			'pwf_meta_data'              => $path . 'includes/classes/admin/meta/class-pwf-meta-data.php',
			'pwf_meta_fields'            => $path . 'includes/classes/admin/meta/class-pwf-meta-fields.php',
			'pwf_database_query'         => $path . 'includes/classes/admin/meta/class-pwf-database-query.php',
			'pwf_analytic_page'          => $path . 'includes/classes/admin/analytic/class-pwf-analytic-page.php',
			'pwf_admin_analytic_query'   => $path . 'includes/classes/admin/analytic/class-pwf-admin-analytic-query.php',
		);

		return $classes;
	}
}

new Pwf_Autoloader();
