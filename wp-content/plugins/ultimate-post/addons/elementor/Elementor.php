<?php
defined('ABSPATH') || exit;

final class Elementor_ULTP_Extension {

    private static $_instance = null;

    public static function instance() {
        if (is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        $this->init();
    }

    public function init() {
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );
    }

    public function widget_styles() {
        wp_register_style('ultp-style', ULTP_URL.'assets/css/style.min.css', array(), ultimate_post()->get_setting('save_version') );
        wp_enqueue_style('ultp-style');
    }

    public function widget_scripts() {
        wp_register_script('ultp-script', ULTP_URL.'assets/js/ultp.min.js', array('jquery'), ultimate_post()->get_setting('save_version'), true);
        wp_enqueue_script('ultp-script');
        wp_localize_script('ultp-script', 'ultp_data_frontend', array(
            'url' => ULTP_URL,
            'ajax' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('ultp-nonce')
        ));
    }

    public function includes() {
        require_once ULTP_PATH.'addons/elementor/Elementor_Widget.php';
    }

    public function register_widgets() {
        $this->includes();
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Gutenberg_Post_Blocks_Widget() );
    }
}