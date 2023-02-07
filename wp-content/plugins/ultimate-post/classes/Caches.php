<?php
/**
 * Plugin Cache.
 * 
 * @package ULTP\Caches
 * @since v.1.0.0
 */

namespace ULTP;

defined('ABSPATH') || exit;

/**
 * Caches class.
 */
class Caches{

    /**
	 * API Endpoint
	 *
	 * @since v.1.0.0
	 */
    private $api_endpoint = 'https://ultp.wpxpo.com/wp-json/restapi/v2/';
    

    /**
	 * Setup class.
	 *
	 * @since v.1.0.0
	 */
    public function __construct(){
        add_action('rest_api_init', array($this, 'get_template_data'));
    }


    /**
	 * Get Template or Desing from the API Action
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
	public function get_template_data() {
		register_rest_route(
			'ultp/v2', 
			'/get_pattern_n_template/',
			array(
				array(
					'methods'  => 'POST', 
					'callback' => array( $this, 'get_pattern_n_template_callback'),
					'permission_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
					'args' => array()
				)
			)
        );
        register_rest_route(
			'ultp/v2', 
			'/get_single/',
			array(
				array(
					'methods'  => 'POST', 
					'callback' => array( $this, 'get_single_callback'),
					'permission_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
					'args' => array()
				)
			)
        );
        register_rest_route(
			'ultp/v2', 
			'/fetch_all_data/',
			array(
				array(
					'methods'  => 'POST', 
					'callback' => array( $this, 'fetch_all_data_callback'),
					'permission_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
					'args' => array()
				)
			)
        );
        register_rest_route(
			'ultp/v2', 
			'/get_premade/',
			array(
				array(
					'methods'  => 'POST', 
					'callback' => array( $this, 'get_premade_callback'),
					'permission_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
					'args' => array()
				)
			)
        );
    }

    
    /**
	 * Premade Template Data
     * 
     * @since v.2.7.0
     * @param ARRAY
	 * @return ARRAY | Data of the Premade
	 */
    public function get_premade_callback($request) {
		try{
			global $wp_filesystem;
			if (! $wp_filesystem ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
            
			$upload_dir_url = wp_upload_dir();
			$dir 			= trailingslashit($upload_dir_url['basedir']) . 'ultp/';
            $file_path      = $dir . "premade.json";
            
            if (file_exists( $file_path )) {
                return array( 'success' => true, 'data' => file_get_contents($file_path) );
            } else {
                $this->get_source_data('premade');
            }
			
		}catch(Exception $e) {
			return [ 'success'=> false, 'message'=> $e->getMessage() ];
        }
    }
    

    /**
	 * ResetData from API
     * 
     * @since v.2.4.4
     * @param ARRAY
	 * @return ARRAY | Data of the Design
	 */
    public function fetch_all_data_callback($request) {
        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        $upload_dir = $upload_dir . '/ultp';
        if ( file_exists($upload_dir . '/template_nd_design.json') ) {
            wp_delete_file($upload_dir . '/template_nd_design.json');
        }
        if ( file_exists($upload_dir . '/design.json') ) {
            wp_delete_file($upload_dir . '/design.json');
        }
        if (file_exists($upload_dir . '/premade.json') ) {
            wp_delete_file($upload_dir . '/premade.json');
        }
        $this->get_source_data('all');
        return array('success' => true, 'data' => __('Data Fetched!!!', 'ultimate-post'));
    }
    
    /**
	 * Get Design Data from API
     * 
     * @since v.1.0.0
     * @param ARRAY
	 * @return ARRAY | Data of the Design
	 */
	public function  get_single_callback($request){
		try{
			global $wp_filesystem;
			if (! $wp_filesystem ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
            
			$upload_dir_url = wp_upload_dir();
			$dir 			= trailingslashit($upload_dir_url['basedir']) . 'ultp/';
            $file_path      = $dir . "design.json";
            
            if (file_exists( $file_path )) {
                return array( 'success' => true, 'data' => file_get_contents($file_path) );
            } else {
                return array( 'success' => true, 'data' => $this->get_source_data('design') );
            }
			
		}catch(Exception $e) {
			return [ 'success'=> false, 'message'=> $e->getMessage() ];
        }
    }
    

    /**
	 * Get Template Data from API
     * 
     * @since v.1.0.0
     * @param ARRAY
	 * @return ARRAY | Data of the Design
	 */
	public function  get_pattern_n_template_callback($request){
		try{
			global $wp_filesystem;
			if (! $wp_filesystem ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}

			$upload_dir_url = wp_upload_dir();
			$dir 			= trailingslashit($upload_dir_url['basedir']) . 'ultp/';
            $file_path      = $dir . "template_nd_design.json";
            
            if (file_exists( $file_path )) {
                return array( 'success' => true, 'data' => file_get_contents($file_path) );
            } else {
                return array( 'success' => true, 'data' => $this->get_source_data('templates') );
            }
		}catch(Exception $e){
			return [ 'success'=> false, 'message'=> $e->getMessage() ];
        }
    }
    

    /**
	 * Create a Directory in Upload Folder
     * 
     * @since v.1.0.0
     * @param NULL
	 * @return STRING | Directory Path
	 */
    public function create_directory($type = 'all') {
        try{
			global $wp_filesystem;
			if (! $wp_filesystem ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
            $upload_dir_url = wp_upload_dir();
			$dir = trailingslashit($upload_dir_url['basedir']) . 'ultp/';
            WP_Filesystem( false, $upload_dir_url['basedir'], true );
            if (! $wp_filesystem->is_dir( $dir ) ) {
                $wp_filesystem->mkdir( $dir );
            }
            if($type == 'templates' || $type == 'all'){
                if (!file_exists($dir . 'template_nd_design.json')) {
                    fopen( $dir . 'template_nd_design.json', "w" );
                }
            }
            if($type == 'all' || $type == 'design'){
                if (!file_exists($dir . 'design.json')) {
                    fopen( $dir . 'design.json', "w" );
                }
            }
            if($type == 'all' || $type == 'premade'){
                if (!file_exists($dir . 'premade.json')) {
                    fopen( $dir . 'premade.json', "w" );
                }
            }
            return $dir;
        } catch(Exception $e) {
			return [ 'success'=> false, 'message'=> $e->getMessage() ];
        }
    }


    /**
	 * Get Source Data from the file or API
     * 
     * @since v.1.0.0
     * @param STRING | Type (STRING)
	 * @return ARRAY | Exception Message
	 */
    public function get_source_data($type = 'all') {
        if($type == 'templates' || $type == 'design' || $type == 'premade'){
            return $this->download_source($type);
        } else if($type == 'all'){
            try{
                global $wp_filesystem;
                if ( ! $wp_filesystem ) {
                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                }
                $upload_dir_url = wp_upload_dir();
                $dir 			= trailingslashit($upload_dir_url['basedir']) . 'ultp/';
                if (!file_exists( $dir . "template_nd_design.json" )) {
                    $this->download_source($type);
                }
                if (!file_exists( $dir . "design.json" )) {
                    $this->download_source($type);
                }
                if (!file_exists( $dir . "premade.json" )) {
                    $this->download_source($type);
                }
            }catch(Exception $e){
                return false;
            }
        }
    }


    /**
	 * Download Source from the Server API
     * 
     * @since v.1.0.0
     * @param STRING | Type (STRING)
	 * @return ARRAY | Message from the API
	 */
    public function download_source($type) {
        $data = '';
        if($type == 'all' || $type == 'templates'){
            $response = wp_remote_post( 
                $this->api_endpoint.'templates', 
                array(
                    'method' => 'POST',
                    'timeout' => 120,
                    'body' => array( 'type' => 'layouts', 'design' => 'all' )
                )
            );
    
            if ( !is_wp_error( $response ) ) {
                $path_url = $this->create_directory($type);
                $data = $response['body'];
                file_put_contents($path_url.'template_nd_design.json', $data);   
            }
        }
        if($type == 'all' || $type == 'design'){
            $response = wp_remote_post( 
                $this->api_endpoint.'design', 
                array(
                    'method' => 'POST',
                    'timeout' => 120 
                )
            );
            if ( !is_wp_error( $response ) ) {
                $path_url = $this->create_directory($type);
                $data = $response['body'];
                file_put_contents($path_url.'design.json', $data);
            }
        }
        if ($type == 'all' || $type == 'premade') {
            $response = wp_remote_post( 
                $this->api_endpoint.'premade', 
                array(
                    'method' => 'POST',
                    'timeout' => 120
                )
            );
            if ( !is_wp_error( $response ) ) {
                $path_url = $this->create_directory($type);
                $data = $response['body'];
                file_put_contents($path_url.'premade.json', $data);
            }
        }
        return $data;
    }

}   