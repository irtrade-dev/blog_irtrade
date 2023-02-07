<?php
defined( 'ABSPATH' ) || exit;

add_filter('ultp_addons_config', 'ultp_new_builder_config');
function ultp_new_builder_config( $config ) {
	$configuration = array(
		'name' => __( 'Dynamic Site Builder', 'ultimate-post-pro' ),
		'desc' => __( 'Create dynamic websites using the PostX instead of old-school page builders.', 'ultimate-post-pro' ),
		'img' => ULTP_URL.'assets/img/addons/builder-icon.svg',
		'docs' => 'https://docs.wpxpo.com/docs/postx/add-on/archive-builder/',
        'live' => 'https://www.wpxpo.com/postx/addons/builder/',
		'is_pro' => false,
		'position' => 5
	);
	$config['ultp_builder'] = $configuration;
	return $config;
}

add_action('init', 'ultp_new_builder_init');
function ultp_new_builder_init(){
	$settings = isset($GLOBALS['ultp_settings']) ? $GLOBALS['ultp_settings'] : [];
	if ( isset($settings['ultp_builder']) ) {
		if ($settings['ultp_builder'] == 'true') {
			require_once ULTP_PATH.'/addons/builder/Builder.php';
			require_once ULTP_PATH.'/addons/builder/RequestAPI.php';
			new \ULTP\Builder();
			new \ULTP\RequestAPI();
		}
	}
}