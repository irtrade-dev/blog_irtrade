<?php
/**
 * Options Action.
 * 
 * @package ULTP\Notice
 * @since v.1.0.0
 */
namespace ULTP;

defined('ABSPATH') || exit;

/**
 * Options class.
 */
class Options{

    /**
	 * Setup class.
	 *
	 * @since v.1.0.0
	 */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'handle_external_redirects' ) );
        add_action( 'admin_menu', array( $this, 'menu_page_callback' ) );
        add_action( 'in_admin_header', array($this, 'remove_all_notices') );
        add_filter( 'plugin_row_meta', array( $this, 'plugin_settings_meta' ), 10, 2 );
        add_filter( 'plugin_action_links_'.ULTP_BASE, array( $this, 'plugin_action_links_callback' ) );
    }
    
    
    /**
	 * Plugin Page Menu Add
     * 
     * @since v.1.0.0
	 * @return ARRAY
	 */
    public function plugin_settings_meta( $links, $file ) {
        if (strpos( $file, 'ultimate-post.php' ) !== false ) {
            $new_links = array(
                'ultp_docs' =>  '<a href="https://docs.wpxpo.com/" target="_blank">'.esc_html__('Docs', 'ultimate-post').'</a>',
                'ultp_tutorial' =>  '<a href="https://www.youtube.com/watch?v=JZxIflYKOuM&list=PLPidnGLSR4qcAwVwIjMo1OVaqXqjUp_s4" target="_blank">'.esc_html__('Tutorials', 'ultimate-post').'</a>',
                'ultp_support' =>  '<a href="'.esc_url(ultimate_post()->get_premium_link( 'https://www.wpxpo.com/contact/', 'postx_plugins_support')).'" target="_blank">'.esc_html__('Support', 'ultimate-post').'</a>'
            );
            $links = array_merge( $links, $new_links );
        }
        return $links;
    }


    /**
	 * Settings Pro Update Link
     * 
     * @since v.1.0.0
	 * @return ARRAY
	 */
    public function plugin_action_links_callback( $links ) {
        $upgrade_link = array();
        $setting_link = array();
        if (!defined('ULTP_PRO_VER')) {
            $upgrade_link = array(
                'ultp_pro' => '<a href="'.esc_url(ultimate_post()->get_premium_link('', 'postx_plugins_pro')).'" target="_blank"><span style="color: #e83838; font-weight: bold;">'.esc_html__('Go Pro', 'ultimate-post').'</span></a>'
            );
        }
        $setting_link['ultp_settings'] = '<a href="'. esc_url(admin_url('admin.php?page=ultp-settings#settings')) .'">'. esc_html__('Settings', 'wp-megamenu') .'</a>';
        return array_merge( $setting_link, $links, $upgrade_link);
    }


    /**
	 * Admin Menu Option Page
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public static function menu_page_callback() {
        
        $ultp_menu_icon = 'data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgZGF0YS1uYW1lPSJMYXllciAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MCA0OC4zIj4NCiAgPGRlZnM+DQogICAgPHN0eWxlPg0KICAgICAgLmNscy0xIHsNCiAgICAgICAgZmlsbDogI2E3YWFhZDsNCiAgICAgIH0NCiAgICA8L3N0eWxlPg0KICA8L2RlZnM+DQogIDx0aXRsZT5Qb3N0eCBJY29uIGNtcHJzc2QgU1ZHPC90aXRsZT4NCiAgPGc+DQogICAgPHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMTguODEsOXY4LjlIOC4xOUE2LjE5LDYuMTksMCwwLDAsMiwyMy43N2EzLjE2LDMuMTYsMCwwLDEtMi0yLjk0VjRBMy4xNiwzLjE2LDAsMCwxLDMuMTUuODVIMjBhMy4xOCwzLjE4LDAsMCwxLDMsMi4zMUE2LjIxLDYuMjEsMCwwLDAsMTguODEsOVoiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTAuODUpIi8+DQogICAgPHBhdGggY2xhc3M9ImNscy0xIiBkPSJNNDUsOVYyM0gzMS4xYTYuMjMsNi4yMywwLDAsMC00LjkzLTQuOTNBNS41NCw1LjU0LDAsMCwwLDI1LDE3Ljk0SDIxLjg1VjlBMy4xNSwzLjE1LDAsMCwxLDIzLjEzLDYuNWEzLjEyLDMuMTIsMCwwLDEsMS40My0uNThsLjA5LDBINDEuODNBMy4xNCwzLjE0LDAsMCwxLDQ1LDlaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIC0wLjg1KSIvPg0KICAgIDxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTUwLDI5LjE3VjQ2YTMuMTYsMy4xNiwwLDAsMS0zLjE1LDMuMTVIMzBhMy4xOCwzLjE4LDAsMCwxLTMtMi4zMUE2LjIyLDYuMjIsMCwwLDAsMzEuMjEsNDFWMjZINDYuODVhMy4zLDMuMywwLDAsMSwxLjE0LjIxQTMuMTYsMy4xNiwwLDAsMSw1MCwyOS4xN1oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTAuODUpIi8+DQogICAgPHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMjguMTYsMjQuMTNWNDFhMy4xMywzLjEzLDAsMCwxLTEuMjksMi41NCwzLDMsMCwwLDEtMS40Ny41OGwwLDBIOC4xOUEzLjE1LDMuMTUsMCwwLDEsNSw0MVYyNGEzLjE3LDMuMTcsMCwwLDEsMy4xNS0zSDI1YTMuMTIsMy4xMiwwLDAsMSwxLjE0LjIyLDMuMjQsMy4yNCwwLDAsMSwxLjksMi4xQTMuNjMsMy42MywwLDAsMSwyOC4xNiwyNC4xM1oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTAuODUpIi8+DQogIDwvZz4NCjwvc3ZnPg0K';
        add_menu_page(
            esc_html__( 'PostX', 'ultimate-post' ),
            esc_html__( 'PostX', 'ultimate-post' ),
            'manage_options',
            'ultp-settings',
            array(self::class, 'tab_page_content'),
            $ultp_menu_icon,
            58.5
        );

        add_submenu_page(
            'ultp-settings', 
            esc_html__( 'Getting Started', 'ultimate-post' ),
            esc_html__( 'Getting Started', 'ultimate-post' ),
            'manage_options',
            'ultp-settings'
        );

        if (ultimate_post()->get_setting('ultp_templates') == 'true') {
            add_submenu_page(
                'ultp-settings',
                esc_html__( 'Saved Templates', 'ultimate-post' ),
                esc_html__( 'Saved Templates', 'ultimate-post' ),
                'manage_options',
                'edit.php?post_type=ultp_templates',
                ''
            );
        }
        
        if (ultimate_post()->get_setting('ultp_builder') == 'true') {
            add_submenu_page(
                'ultp-settings',
                esc_html__( 'Site Builder', 'ultimate-post' ),
                esc_html__( 'Site Builder', 'ultimate-post' ),
                'manage_options',
                'admin.php?page=ultp-settings#builder',
                ''
            );
        }

        $menu_lists = array(
            array(
                'name' => esc_html__( 'Template Kits', 'ultimate-post' ),
                'slug' => 'templatekit'
            ),
            array(
                'name' => esc_html__( 'Addons', 'ultimate-post' ),
                'slug' => 'addons'
            ),
            array(
                'name' => esc_html__( 'Blocks', 'ultimate-post' ),
                'slug' => 'blocks'
            ),
            array(
                'name' => esc_html__( 'Settings', 'ultimate-post' ),
                'slug' => 'settings'
            ),
            array(
                'name' => esc_html__( 'Tutorials', 'ultimate-post' ),
                'slug' => 'tutorials'
            ),
        );
        foreach ($menu_lists as $key => $val) {
            add_submenu_page(
                'ultp-settings',
                $val['name'],
                $val['name'],
                'manage_options',
                'ultp-settings#' . $val['slug'],
                array(__CLASS__, 'render_main')
            );
        }

        if (!ultimate_post()->is_lc_active()) {
            add_submenu_page(
                'ultp-settings',
                '',
                '<span class="dashicons dashicons-star-filled" style="font-size: 17px"></span> ' . esc_html__( 'Go Pro', 'ultimate-post' ),
                'manage_options',
                'go_postx_pro',
                array(self::class, 'handle_external_redirects')
            );
        }

        if (ultimate_post()->get_setting('init_setup') != 'yes') {
            add_submenu_page( 
                'ultp-settings',
                esc_html__('Initital Setup', 'ultimate-post'),  
                esc_html__('Initital Setup', 'ultimate-post'), 
                'manage_options',
                'ultp-initial-setup-wizard',
                array(__CLASS__, 'initial_setup')
            );
        }
    }

    /**
     * Initial Plugin Setting
     *
     * * @since v.2.4.4
     * @return STRING
     */
    public static function initial_setup() { ?>
        <div class="ultp-initial-setting-wrap" id="ultp-initial-setting"></div>
    <?php }


    public function handle_external_redirects() {
        if ( empty( $_GET['page'] ) ) {
            return;
        }

        if ( 'go_postx_pro' === $_GET['page'] ) {
            wp_redirect( esc_url(ultimate_post()->get_premium_link('', 'postx_dashboard_menu')) );
            die();
        }
    }

    public static function tab_page_content() {
        $current = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
        if ($current) {
            $pro_active = ultimate_post()->is_lc_active();
            echo '<div class="ultp-settings-tab-wrap">';

                if (date('U') < strtotime('03-12-2022')) { // Black Friday
                    echo '<div class="ultp-setting-hellobar"><span class="dashicons dashicons-bell ultp-ring"></span><strong> Black Friday Sale: </strong> Up to <b>50% Off.</b> Unlock all premium features - <a href="'.esc_url(ultimate_post()->get_premium_link( 'https://www.wpxpo.com/postx/pricing/', 'dashboard-topbar')).'" target="_blank">'.esc_html__('Get Offer', 'ultimate-post').'</a></div>';
                } else {
                    if (!$pro_active) {
                        echo '<div class="ultp-setting-hellobar"><span class="dashicons dashicons-bell ultp-ring"></span><strong> New : </strong> PostX Dynamic Site Builder is Live - <a href="'.esc_url(ultimate_post()->get_premium_link( 'https://www.wpxpo.com/postx-dynamic-site-builder/', 'dashboard-topbar')).'" target="_blank">'.esc_html__('See what’s new', 'ultimate-post').'</a></div>';
                    }
                }
                
                echo '<div class="ultp-setting-header">';
                    echo '<div class="ultp-setting-logo">';
                        echo '<img class="ultp-setting-header-img" loading="lazy" src="'.esc_url(ULTP_URL.'assets/img/logo-option.svg').'" alt="'.esc_html__('PostX', 'ultimate-post').'">';
                        echo '<span>v'. esc_html(ULTP_VER).'</span>';
                    echo '</div>';
                    echo '<ul class="ultp-settings-tab">';
                        echo '<li class="current"><a href="#home">'.esc_html__('Getting Started', 'ultimate-post').'</a></li>';
                        if (ultimate_post()->get_setting('ultp_templates') != 'false') {
                            echo '<li><a href="'.esc_url(admin_url( 'edit.php?post_type=ultp_templates' )).'">'.esc_html__('Saved Template', 'ultimate-post').'</a></li>';
                        }
                        if (ultimate_post()->get_setting('ultp_builder') != 'false') {
                            echo '<li><a href="#builder">'.esc_html__('Site Builder', 'ultimate-post').'</a></li>';
                        }
                        echo '<li><a href="#templatekit">'.esc_html__('Template kits', 'ultimate-post').'</a></li>';
                        echo '<li><a href="#addons">'.esc_html__('Addons', 'ultimate-post').'</a></li>';
                        echo '<li><a href="#blocks">'.esc_html__('Blocks', 'ultimate-post').'</a></li>';
                        echo '<li><a href="#settings">'.esc_html__('Settings', 'ultimate-post').'</a></li>';
                        echo '<li><a href="#tutorials">'.esc_html__('Tutorials', 'ultimate-post').'</a></li>';
                    echo '</ul>';
                echo '</div>';
                echo '<ul class="ultp-settings-content">';
                    echo '<li class="current" data-tab="#home">';
                        require_once ULTP_PATH . 'classes/options/Getting_Started.php';
                    echo '</li>';
                    echo '<li data-tab="#addons">';
                        require_once ULTP_PATH . 'classes/options/Addons.php';
                        new \ULTP\Option_Addons();
                    echo '</li>';
                    echo '<li data-tab="#blocks">';
                        require_once ULTP_PATH . 'classes/options/Blocks.php';
                        new \ULTP\Option_Blocks();
                    echo '</li>';
                    if (ultimate_post()->get_setting('ultp_builder') != 'false') {
                        echo '<li data-tab="#builder">';
                            echo '<div id="ultp-builder-condition"></div>';
                        echo '</li>';
                    }
                    echo '<li data-tab="#templatekit">';
                        echo '<div id="ultp-dashboard-templatekit"></div>';
                    echo '</li>';
                
                    echo '<li data-tab="#settings">';
                        require_once ULTP_PATH . 'classes/options/Settings.php';
                        $obj = new \ULTP\Option_Settings();
                        $obj->create_admin_page();
                    echo '</li>';
                    echo '<li data-tab="#tutorials">';
                        require_once ULTP_PATH . 'classes/options/Tutorials.php';
                    echo '</li>';
                echo '<ul>';
            echo '</div>';
        }
    }

    /**
	 * Remove All Notification From Menu Page
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public static function remove_all_notices() {
        if ( isset($_GET['page']) ) {
            $page = sanitize_key($_GET['page']);
            if ( $page === 'ultp-settings' ||  
                $page === 'ultp-license' ||  
                $page === 'ultp-initial-setup-wizard'  ) {
                remove_all_actions( 'admin_notices' );
                remove_all_actions( 'all_admin_notices' );
            }
        }
    }
}