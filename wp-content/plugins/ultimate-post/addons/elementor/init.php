<?php
defined( 'ABSPATH' ) || exit;

add_filter('ultp_addons_config', 'ultp_elementor_config');
function ultp_elementor_config( $config ) {
	$configuration = array(
		'name' => __( 'Elementor Addons', 'ultimate-post' ),
		'desc' => __( 'Use Gutenberg blocks inside Elementor via Saved Template addons.', 'ultimate-post' ),
		'img' => ULTP_URL.'/assets/img/addons/elementor-icon.svg',
		'is_pro' => false,
		'live' => 'https://www.wpxpo.com/postx/addons/elementor/',
		'docs' => 'https://docs.wpxpo.com/docs/postx/add-on/elementor-addon/', 
		'position' => 20
	);
	$arr['ultp_elementor'] = $configuration;
	return $arr + $config;
}

add_action('plugins_loaded', 'ultp_elementor_init');
function ultp_elementor_init() {
	$settings = ultimate_post()->get_setting('ultp_elementor');
	if ($settings == 'true') {
		if (did_action( 'elementor/loaded' )) {
			require_once ULTP_PATH.'/addons/elementor/Elementor.php';
			Elementor_ULTP_Extension::instance();
		}
	}
}

// Elementor Backend Compatiblity
add_action( 'elementor/editor/after_register_scripts', 'postx_elementor_widget_scripts' );
add_action( 'elementor/editor/after_enqueue_styles', 'postx_elementor_widget_styles' );

function postx_elementor_widget_styles() {
	wp_register_style('ultp-style-editor', ULTP_URL.'assets/css/style.min.css', array(), ultimate_post()->get_setting('save_version') );
	wp_enqueue_style('ultp-style-editor');
}

function postx_elementor_widget_scripts() {
	wp_register_script('ultp-script-editor', ULTP_URL.'assets/js/ultp.min.js', array('jquery'), ultimate_post()->get_setting('save_version'), true);
	wp_enqueue_script('ultp-script-editor');
	wp_localize_script('ultp-script-editor', 'ultp_data_frontend', array(
		'url' => ULTP_URL,
		'ajax' => admin_url('admin-ajax.php'),
		'security' => wp_create_nonce('ultp-nonce')
	));
}