<?php
/**
 * Common Functions.
 * 
 * @package ULTP\Functions
 * @since v.1.0.0
 */
namespace ULTP;

defined('ABSPATH') || exit;

/**
 * Functions class.
 */

class Functions{

    /**
	 * Setup class.
	 *
	 * @since v.1.0.0
	 */
    public function __construct() {
        if (!isset($GLOBALS['ultp_settings'])) {
            $GLOBALS['ultp_settings'] = get_option('ultp_options');
            $GLOBALS['ultp_settings']['date_format'] = get_option('date_format');
            $GLOBALS['ultp_settings']['time_format'] = get_option('time_format');
        }
    }

    /**
	 * ID for the Builder Post or Normal Post
     * 
     * @since v.2.3.1
	 * @return NUMBER | is Builder or not
	 */
    public function get_ID() {
        $id = $this->is_builder();
        return $id ? $id : (function_exists('is_shop') ? (is_shop() ? wc_get_page_id('shop') : get_the_ID()) : get_the_ID() );
    }
    
     /**
	 * Checking Statement of Dynamic Site Builder
     * 
     * @since v.2.3.1
	 * @return BOOLEAN | is Builder or not
	 */
    public function is_archive_builder() {
        $id = get_the_ID();
        return  get_post_type( $id ) == 'ultp_builder' ? get_post_meta( $id, '__ultp_builder_type', true ) : false;
    }


    /**
	 * Set Link with the Parameters
     * 
     * @since v.1.1.0
	 * @return STRING | URL with Arg
	 */
    public function get_premium_link( $url = '', $tag = 'go_premium' ) {
        $url = $url ? $url : 'https://www.wpxpo.com/postx/pricing/';
        $affiliate_id = apply_filters( 'ultp_affiliate_id', FALSE );
        $arg = array( 'utm_source' => $tag );
        if ( ! empty( $affiliate_id ) ) {
            $arg[ 'ref' ] = esc_attr( $affiliate_id );
        }
        return add_query_arg( $arg, $url );
    }


    /**
	 * Get Width and Height of the Image
     * 
     * @since v.1.1.0
	 * @return STRING | Image Size
	 */
    public function get_size($name = '') {
        global $_wp_additional_image_sizes;
        $image_size = $name ? ( isset($_wp_additional_image_sizes[$name]) ? $_wp_additional_image_sizes[$name] : array_values($_wp_additional_image_sizes)[0] ) : array_values($_wp_additional_image_sizes)[0];
        
        return ' width="'.$image_size['width'].'" height="'.$image_size['height'].'" ';
    }


    /**
	 * Image Placeholder
     * 
     * @since v.1.1.0
	 * @return STRING | Image Placeholder
	 */
    public function img_placeholder($type = 'small') {
        switch ($type) {
            case 'small':
                return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG4AAABLAQMAAACr9CA9AAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABZJREFUOI1jYMADmEe5o9xR7iiXQi4A4BsA388WUyMAAAAASUVORK5CYII=';
                break;

            case 'wide':
                return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAKCAYAAADVTVykAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAB9JREFUeNpi/P//P8NAAiaGAQajDhh1wKgDRh0AEGAAQTcDEcKDrpMAAAAASUVORK5CYII=';
                break;

            case 'square':
                return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAAApJREFUCJljYAAAAAIAAfRxZKYAAAAASUVORK5CYII=';
                break;

            case 'slider':
                return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKYAAABkCAMAAAA7drv6AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAAZQTFRF////AAAAVcLTfgAAAAF0Uk5TAEDm2GYAAAAqSURBVHja7MEBDQAAAMKg909tDjegAAAAAAAAAAAAAAAAAAAAAH5NgAEAQTwAAWZtItYAAAAASUVORK5CYII=';
                break;
            
            default:
                return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAYYAAADcAQMAAABOLJSDAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAACJJREFUaIHtwTEBAAAAwqD1T20ND6AAAAAAAAAAAAAA4N8AKvgAAUFIrrEAAAAASUVORK5CYII=';
                break;
        }
    }

    
    /**
	 * Quick Query
     * 
     * @since v.1.1.0
	 * @return ARRAY | Query Arg
	 */
    public function get_quick_query($prams, $args) {
        switch ($prams['queryQuick']) {
            case 'related_posts':
                global $post;
                $p_id = isset($post->ID) && $post->ID ? $post->ID : (isset($_POST['postId']) ? $_POST['postId'] : '');
                if ($p_id) {
                    $args['post__not_in'] = array($p_id);
                }
                break;
            case 'related_tag':
                global $post;
                $p_id = isset($post->ID) && $post->ID ? $post->ID : (isset($_POST['postId']) ? $_POST['postId'] : '');
                if ($p_id) {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'post_tag',
                            'terms'    => $this->get_terms_id($p_id, 'post_tag'),
                            'field'    => 'term_id',
                        )
                    );
                    $args['post__not_in'] = array($p_id);
                }
                break;
            case 'related_category':
                global $post;
                $p_id = isset($post->ID) && $post->ID ? $post->ID : (isset($_POST['postId']) ? $_POST['postId'] : '');
                if ($p_id) {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'category',
                            'terms'    => $this->get_terms_id($p_id, 'category'),
                            'field'    => 'term_id',
                        )
                    );
                    $args['post__not_in'] = array($p_id);
                }
                break;
            case 'related_cat_tag':
                global $post;
                $p_id = isset($post->ID) && $post->ID ? $post->ID : (isset($_POST['postId']) ? $_POST['postId'] : '');
                if ($p_id) {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'post_tag',
                            'terms'    => $this->get_terms_id($p_id, 'post_tag'),
                            'field'    => 'term_id',
                        ),
                        array(
                            'taxonomy' => 'category',
                            'terms'    => $this->get_terms_id($p_id, 'category'),
                            'field'    => 'term_id',
                        )
                    );
                    $args['post__not_in'] = array($p_id);
                }
                break;
            case 'sticky_posts':
                $sticky = get_option('sticky_posts');
                if (is_array($sticky)) { 
					rsort($sticky);
                    $sticky = array_slice($sticky, 0, $args['posts_per_page']);
                }
				$args['ignore_sticky_posts'] = 1;
                $args['post__in'] = $sticky;
                break;
            case 'latest_post_published':
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                $args['ignore_sticky_posts'] = 1;
                break;
            case 'latest_post_modified':
                $args['orderby'] = 'modified';
                $args['order'] = 'DESC';
                $args['ignore_sticky_posts'] = 1;
                break;
            case 'oldest_post_published':
                $args['orderby'] = 'date';
                $args['order'] = 'ASC';
                break;
            case 'oldest_post_modified':
                $args['orderby'] = 'modified';
                $args['order'] = 'ASC';
                break;
            case 'alphabet_asc':
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
                break;
            case 'alphabet_desc':
                $args['orderby'] = 'title';
                $args['order'] = 'DESC';
                break;
            case 'random_post':
                $args['orderby'] = 'rand';
                $args['order'] = 'ASC';
                break;
            case 'random_post_7_days':
                $args['orderby'] = 'rand';
                $args['order'] = 'ASC';
                $args['date_query'] = array( array( 'after' => '1 week ago') );
                break;
            case 'random_post_30_days':
                $args['orderby'] = 'rand';
                $args['order'] = 'ASC';
                $args['date_query'] = array( array( 'after' => '1 month ago') );
                break;
            case 'most_comment':
                $args['orderby'] = 'comment_count';
                $args['order'] = 'DESC';
                break;
            case 'most_comment_1_day':
                $args['orderby'] = 'comment_count';
                $args['order'] = 'DESC';
                $args['date_query'] = array( array( 'after' => '1 day ago') );
                break;
            case 'most_comment_7_days':
                $args['orderby'] = 'comment_count';
                $args['order'] = 'DESC';
                $args['date_query'] = array( array( 'after' => '1 week ago') );
                break;
            case 'most_comment_30_days':
                $args['orderby'] = 'comment_count';
                $args['order'] = 'DESC';
                $args['date_query'] = array( array( 'after' => '1 month ago') );
                break;
            case 'popular_post_1_day_view':
                $args['meta_key'] = '__post_views_count';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                $args['date_query'] = array( array( 'after' => '1 day ago') );
                break;
            case 'popular_post_7_days_view':
                $args['meta_key'] = '__post_views_count';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                $args['date_query'] = array( array( 'after' => '1 week ago') );
                break;
            case 'popular_post_30_days_view':
                $args['meta_key'] = '__post_views_count';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                $args['date_query'] = array( array( 'after' => '1 month ago') );
                break;
            case 'popular_post_all_times_view':
                $args['meta_key'] = '__post_views_count';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            default:
                # code...
                break;
        }
        return $args;
    }

    /**
	 * Get All Term ID as Array
     * 
     * @since v.2.4.12
	 * @return ARRAY | Query Arg
	 */
    public function get_terms_id($id, $type) {
        $data = array();
        $arr = get_the_terms($id, $type);
        if (is_array($arr)) {
            foreach ($arr as $key => $val) {
                $data[] = $val->term_id;
            }
        }
        return $data;
    }

    /**
	 * Get All Reusable ID
     * 
     * @since v.1.1.0
	 * @return ARRAY | Query Arg
	 */
    public function reusable_id($post_id) {
        $reusable_id = array();
        if ($post_id) {
            $post = get_post($post_id);
            if (has_blocks($post->post_content)) {
                $blocks = parse_blocks($post->post_content);
                foreach ($blocks as $key => $value) {
                    if (isset($value['attrs']['ref'])) {
                        $reusable_id[] = $value['attrs']['ref'];
                    }
                }
            }
        }
        return $reusable_id;
    }
    

    /**
	 * Set CSS Style
     * 
     * @since v.1.1.0
	 * @return ARRAY | Query Arg
	 */
    public function set_css_style($post_id, $shortcode = false) {
        if ($post_id) {
			$upload_dir_url = wp_get_upload_dir();
			$upload_css_dir_url = trailingslashit( $upload_dir_url['basedir'] );
            $css_dir_path = $upload_css_dir_url . "ultimate-post/ultp-css-{$post_id}.css";
            
            $css_dir_url = trailingslashit( $upload_dir_url['baseurl'] );
            if (is_ssl()) {
                $css_dir_url = str_replace('http://', 'https://', $css_dir_url);
            }
                
            // Reusable CSS
			$reusable_id = ultimate_post()->reusable_id($post_id);
			foreach ( $reusable_id as $id ) {
				$reusable_dir_path = $upload_css_dir_url."ultimate-post/ultp-css-{$id}.css";
				if (file_exists( $reusable_dir_path )) {
                    $css_url = $css_dir_url . "ultimate-post/ultp-css-{$id}.css";
				    wp_enqueue_style( "ultp-post-{$id}", $css_url, array(), ultimate_post()->get_setting('save_version'), 'all' );
				}else{
					$css = get_post_meta($id, '_ultp_css', true);
                    if( $css ) {
                        ultimate_post()->set_inline($css);
                    }
				}
            }
            
            if (isset($_GET['et_fb']) || (isset($_GET['action']) && sanitize_key($_GET['action']) == 'elementor') || $shortcode) {
                return ultimate_post()->set_inline(get_post_meta($post_id, '_ultp_css', true));
            } else {
                if (file_exists( $css_dir_path ) ) {
                    $css_url = $css_dir_url . "ultimate-post/ultp-css-{$post_id}.css";
                    wp_enqueue_style( "ultp-post-{$post_id}", $css_url, array(), ultimate_post()->get_setting('save_version'), 'all' );
                } else {
                    $css = get_post_meta($post_id, '_ultp_css', true);
                    if( $css ) {
                        ultimate_post()->set_inline($css);
                    }
                }
            }
		}
    }


    /**
	 * Get Global Plugin Settings
     * 
     * @since v.1.0.0
     * @param STRING | Key of the Option
	 * @return ARRAY | STRING
	 */
    public function get_setting($key = '') {
        $data = $GLOBALS['ultp_settings'];
        if ($key != '') {
            return isset($data[$key]) ? $data[$key] : '';
        } else {
            return $data;
        }
    }


    /**
	 * Set Option Settings
     * 
     * @since v.1.0.0
     * @param STRING | Key of the Option (STRING), Value (STRING)
	 * @return NULL
	 */
    public function set_setting($key = '', $val = '') {
        if ($key != '') {
            $data = $GLOBALS['ultp_settings'];
            $data[$key] = $val;
            update_option('ultp_options', $data);
            $GLOBALS['ultp_settings'] = $data;
        }
    }


    /**
	 * Get Image HTML
     * 
     * @since v.1.0.0
     * @param  | URL (STRING) | size (STRING) | class (STRING) | alt (STRING) 
	 * @return STRING
	 */
    public function get_image_html($url = '', $size = 'full', $class = '', $alt = '', $lazy = '') {
        $alt = $alt ? ' alt="'.$alt.'" ' : '';
        $lazy_data = $lazy ? ' loading="lazy"' : '';
        $class = $class ? ' class="'.$class.'" ' : '';
        return '<img '.$lazy_data.$class.$alt.' src="'.$url.'" />';
    }


    /**
	 * Get Image HTML
     * 
     * @since v.1.0.0
     * @param  | Attach ID (STRING) | size (STRING) | class (STRING) | alt (STRING) 
	 * @return STRING
	 */
    public function get_image($attach_id, $size = 'full', $class = '', $alt = '', $srcset = '', $lazy = '') {
        $alt = $alt ? ' alt="'.$alt.'" ' : '';
        $class = $class ? ' class="'.$class.'" ' : '';
        $size = ( ultimate_post()->get_setting('disable_image_size') == 'yes' && strpos($size, 'ultp_layout_') !== false ) ? 'full' : $size;
        $lazy_data = $lazy ? ' loading="lazy"' : '';
        $srcset_data = $srcset ? ' srcset="'.esc_attr(wp_get_attachment_image_srcset($attach_id)).'"' : '';
        return '<img '.$srcset_data.$lazy_data.$class.$alt.' src="'.wp_get_attachment_image_url( $attach_id, $size ).'" />';
    }

    
    /**
	 * Setup Initial Data Set
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function init_set_data() {
        $data = get_option( 'ultp_options', array() );
        $init_data = array(
            'css_save_as'       => 'wp_head',
            'preloader_style'   => 'style1',
            'preloader_color'   => '#037fff',
            'container_width'   => '1140',
            'hide_import_btn'   => '',
            'disable_image_size'=> '',
            'disable_view_cookies' => '',
            'disable_google_font' => '',
            'ultp_templates'    => 'true',
            'ultp_elementor'    => 'true',
            'ultp_table_of_content'=> 'true',
            'ultp_builder'      => 'true',
            'post_grid_1'       => 'yes',
            'post_grid_2'       => 'yes',
            'post_grid_3'       => 'yes',
            'post_grid_4'       => 'yes',
            'post_grid_5'       => 'yes',
            'post_grid_6'       => 'yes',
            'post_grid_7'       => 'yes',
            'post_list_1'       => 'yes',
            'post_list_2'       => 'yes',
            'post_list_3'       => 'yes',
            'post_list_4'       => 'yes',
            'post_module_1'     => 'yes',
            'post_module_2'     => 'yes',
            'post_slider_1'     => 'yes',
            'post_slider_2'     => 'yes',
            'heading'           => 'yes',
            'image'             => 'yes',
            'taxonomy'          => 'yes',
            'wrapper'           => 'yes',
            'news_ticker'       => 'yes',
            'builder_advance_post_meta' => 'yes',
            'builder_archive_title'     => 'yes',
            'builder_author_box'        => 'yes',
            'builder_post_next_previous'=> 'yes',
            'builder_post_author_meta'  => 'yes',
            'builder_post_breadcrumb'   => 'yes',
            'builder_post_category'     => 'yes',
            'builder_post_comment_count'=> 'yes',
            'builder_post_comments'     => 'yes',
            'builder_post_content'      => 'yes',
            'builder_post_date_meta'    => 'yes',
            'builder_post_excerpt'      => 'yes',
            'builder_post_featured_image'=> 'yes',
            'builder_post_reading_time' => 'yes',
            'builder_post_social_share' => 'yes',
            'builder_post_tag'          => 'yes',
            'builder_post_title'        => 'yes',
            'builder_post_view_count'   => 'yes',
            'save_version'      => rand(1, 1000)
        );
        if (empty($data)) {
            update_option('ultp_options', $init_data);
            $GLOBALS['ultp_settings'] = $init_data;
        } else {
            foreach ($init_data as $key => $single) {
                if (!isset($data[$key])) {
                    $data[$key] = $single;
                }
            }
            update_option('ultp_options', $data);
            $GLOBALS['ultp_settings'] = $data;
        }
    }

    
    /**
	 * Get Excerpt Text
     * 
     * @since v.1.0.0
     * @param  | Post ID (STRING) | Limit (NUMBER)
	 * @return STRING
	 */
    public function excerpt( $post_id, $limit = 55 ) {
        return apply_filters( 'the_excerpt', wp_trim_words( get_the_content( $post_id ) , $limit ) );
    }

    /**
	 * Builder Attributes
     * 
     * @since v.1.0.0
     * @param STRING type
	 * @return STRING
	 */
    public function get_builder_attr($type) {
        $builder_data = '';
        if ($type == 'archiveBuilder') {
            if (is_archive()) {
                if (is_date()) {
                    if (is_year()) {
                        $builder_data = 'date###'.get_the_date('Y');
                    } else if (is_month() ) {
                        $builder_data = 'date###'.get_the_date('Y-n');
                    } else if (is_day() ) {
                        $builder_data = 'date###'.get_the_date('Y-n-j');
                    }
                } else if (is_author()) {
                    $builder_data = 'author###'.get_the_author_meta('ID');
                } else {
                    $obj = get_queried_object();
                    if (isset($obj->taxonomy)) {
                        $builder_data = 'taxonomy###'.$obj->taxonomy.'###'.$obj->slug;
                    }
                }
            } else if (is_search()) {
                $builder_data = 'search###'.get_search_query(true);
            }
        }
        return $builder_data ? 'data-builder="'.$builder_data.'"' : '';
    }


    public function is_builder($builder = '') {
        $id = '';
        $page_id = ultimate_post()->conditions('return');
        if ($page_id && ultimate_post()->get_setting('ultp_builder')) {
            $id = $page_id;
        }
        return $id;
    }

    
    /**
	 * Get Post Number Depending On Device
     * 
     * @since v.2.5.4
     * @param MULTIPLE | Attribute of Posts
	 * @return STRING
	 */
    public function get_post_number($preDef, $prev, $current) {
        
        $current = is_object($current)?json_decode(json_encode($current), true):$current;
        if (['lg'=>$preDef,'sm'=>$preDef,'xs'=>$preDef] == $current) {
            if ($preDef != $prev) {
                return $prev;
            }
        }
        if ($this->isDevice() == 'mobile') {
            return $current['xs'];
        } else if ($this->isDevice() == 'tablet') {
            return $current['sm'];
        } else {
            return $current['lg'];
        }
    }


    /**
	 * Get Post Number Depending On Device
     * 
     * @since v.2.5.4
     * @param NULL
	 * @return STRING | Device Type
	 */
    public function isDevice(){
        $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_key($_SERVER['HTTP_USER_AGENT']) : '';
        if ($useragent) {
            if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
                return 'mobile';
            } else if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($useragent))) {
                return 'tablet';
            } else {
                return 'desktop';
            }
        }
        return 'desktop';
    }


    /**
	 * Get Raw Value from Objects
     * 
     * @since v.2.5.3
     * @param NULL
	 * @return STRING | Device Type
	 */
    public function get_value($attr) {
        $data = [];
        if (is_array($attr)) {
            foreach ($attr as $val) {
                $data[] = $val->value;
            }
        }
        return $data;
    }


    /**
	 * Query Builder 
     * 
     * @since v.1.0.0
     * @param ARRAY | Attribute of the Query
	 * @return ARRAY
	 */
    public function get_query($attr) {
        $builder = isset($attr['builder']) ? $attr['builder'] : '';
        $post_type = isset($attr['queryType']) ? $attr['queryType'] : 'post';
        if ( $post_type == 'archiveBuilder' && ($builder || $this->is_builder($builder)) ) {
            $archive_query = array();
            if ($builder) {
                $str = explode('###', $builder);
                if (isset($str[0])) {
                    if ($str[0] == 'taxonomy') {
                        if (isset($str[1]) && isset($str[2])) {
                            $archive_query['tax_query'] = array(
                                array(
                                    'taxonomy' => $str[1],
                                    'field' => 'slug',
                                    'terms' => $str[2]
                                )
                            );
                        }
                    } else if ($str[0] == 'author') {
                        if (isset($str[1])) {
                            $archive_query['author'] = $str[1];
                        }
                    } else if ($str[0] == 'search') {
                        if (isset($str[1])) {
                            $archive_query['s'] = $str[1];
                        }
                    } else if ($str[0] == 'date') {
                        if (isset($str[1])) {
                            $all_date = explode('-', $str[1]);
                            if (!empty($all_date)) {
                                $arg = array();
                                if (isset($all_date[0])) { $arg['year'] = $all_date[0]; }
                                if (isset($all_date[1])) { $arg['month'] = $all_date[1]; }
                                if (isset($all_date[2])) { $arg['day'] = $all_date[2]; }
                                $archive_query['date_query'][] = $arg;
                            }
                        }
                    }
                }
            } else {
                global $wp_query;
                $archive_query = $wp_query->query_vars;
            }
            $archive_query['posts_per_page'] = isset($attr['queryNumber']) ? $attr['queryNumber'] : 3;
            $archive_query['paged'] = isset($attr['paged']) ? $attr['paged'] : 1;
            if (isset($attr['queryOffset']) && $attr['queryOffset'] ) {
                $offset = $this->get_offset($attr['queryOffset'], $archive_query);
                $archive_query = array_merge($archive_query, $offset);
            }

            // Include Remove from Version 2.5.4
            if (isset($attr['queryInclude']) && $attr['queryInclude']) {
                $_include = explode(',', $attr['queryInclude']);
                if (is_array($_include) && count($_include)) {
                    $archive_query['post__in'] = isset($archive_query['post__in']) ? array_merge($archive_query['post__in'], $_include) : $_include;
                    $archive_query['ignore_sticky_posts'] = 1;
                    $archive_query['orderby'] = 'post__in';
                }
            }

            if (isset($attr['queryExclude']) && $attr['queryExclude']) {
                $_exclude = (substr($attr['queryExclude'], 0, 1) === "[") ? $this->get_value(json_decode($attr['queryExclude'])) : explode(',', $attr['queryExclude']);
                if (is_array($_exclude) && count($_exclude)) {
                    $archive_query['post__not_in'] = isset($archive_query['post__not_in']) ? array_merge($archive_query['post__not_in'], $_exclude) : $_exclude;
                }
            }


            if(isset($attr['querySticky']) && $attr['querySticky']) {
                if (filter_var($attr['querySticky'], FILTER_VALIDATE_BOOLEAN)) {
                    $sticky = get_option( 'sticky_posts', [] );
                    $archive_query['post__not_in'] = isset($archive_query['post__not_in']) ? array_merge($archive_query['post__not_in'], $sticky) : $sticky;
                }
            }

            $archive_query['post_status'] = 'publish';
            if (is_user_logged_in()) {
                if( current_user_can('editor') || current_user_can('administrator') ) {
                    $archive_query['post_status'] = array('publish', 'private');
                }
            }

            if(!isset($archive_query['orderby'])) {
                $archive_query['orderby'] = isset($attr['queryOrderBy']) ? $attr['queryOrderBy'] : 'date';
            }

            // Quick Query Support for Builder
            if (isset($attr['queryQuick'])) {
                if ($attr['queryQuick'] != '') {
                    $archive_query = ultimate_post()->get_quick_query($attr, $archive_query);
                }
            }

            if (!isset($attr['ajaxCall'])) {
                if(isset($attr['filterShow']) && $attr['filterShow']) {
                    if(isset($attr['filterType']) && isset($attr['filterValue'])) {
                        $filterValue = strlen($attr['filterValue']) > 2 ? $attr['filterValue'] : []; 
                        $filterValue = is_array($filterValue) ? $filterValue : json_decode($filterValue);
                        if (count($filterValue) > 0 && $attr['filterType']) {
                            $final = array('relation' => 'OR');
                            foreach ($filterValue as $key => $val) {
                                $final[] = array(
                                    'taxonomy' => $attr['filterType'],
                                    'field'    => 'slug',
                                    'terms'    => $val,
                                );
                            }
                            $archive_query['tax_query'] = $final;
                        }
                    }
                }
            }

            return apply_filters('ultp_archive_query', $archive_query);
        }

        $query_args = array(
            'posts_per_page'    => isset($attr['queryNumber']) ? $attr['queryNumber'] : 3,
            'post_type'         => $post_type == 'archiveBuilder' ? 'post' : $post_type,
            'orderby'           => isset($attr['queryOrderBy']) ? $attr['queryOrderBy'] : 'date',
            'order'             => isset($attr['queryOrder']) ? $attr['queryOrder'] : 'desc',
            'paged'             => isset($attr['paged']) ? $attr['paged'] : 1,
            'post_status'       => 'publish'
        );

        // For Private Post 'private'
        if (is_user_logged_in()) {
            if( current_user_can('editor') || current_user_can('administrator') ) {
                $query_args['post_status'] = array('publish', 'private');
            }
        }

        if ($attr['queryType'] == 'posts') {
            if (isset($attr['queryPosts']) && $attr['queryPosts']) {
                unset($query_args['post_type']);
                $data = json_decode(isset($attr['queryPosts'])?$attr['queryPosts']:'[]');
                $final = $this->get_value($data);
                if (count($final) > 0) {
                    $query_args['post__in'] = $final;
                    $query_args['posts_per_page'] = -1;
                }
                $query_args['ignore_sticky_posts'] = 1;
                return $query_args;
            }
        } else if ($attr['queryType'] == 'customPosts') {
            if (isset($attr['queryCustomPosts']) && $attr['queryCustomPosts']) {
                $query_args['post_type'] = $this->get_post_type();
                $data = json_decode(isset($attr['queryCustomPosts'])?$attr['queryCustomPosts']:'[]');
                $final = $this->get_value($data);
                if (count($final) > 0) {
                    $query_args['post__in'] = $final;
                    $query_args['posts_per_page'] = -1;
                }
                $query_args['ignore_sticky_posts'] = 1;
                return $query_args;
            }
        }

        if (isset($attr['queryExcludeAuthor']) && $attr['queryExcludeAuthor']) {
            $data = json_decode(isset($attr['queryExcludeAuthor'])?$attr['queryExcludeAuthor']:'[]');
            $final = $this->get_value($data);
            if (count($final) > 0) {
                $query_args['author__not_in'] = $final;
            }
        }
        

        if (isset($attr['queryOrderBy']) && isset($attr['metaKey'])) {
            if ($attr['queryOrderBy'] == 'meta_value_num') {
                $query_args['meta_key'] = $attr['metaKey'];
            }
        }

        // Include Remove from Version 2.5.4
        if (isset($attr['queryInclude']) && $attr['queryInclude']) {
            $_include = explode(',', $attr['queryInclude']);
            if (is_array($_include) && count($_include)) {
                $query_args['post__in'] = isset($query_args['post__in']) ? array_merge($query_args['post__in'], $_include) : $_include;
                $query_args['ignore_sticky_posts'] = 1;
                $query_args['orderby'] = 'post__in';
            }
        }


        if (isset($attr['queryTax'])) {
            if (isset($attr['queryTaxValue'])) {
                $tax_value = (strlen($attr['queryTaxValue']) > 2) ? $attr['queryTaxValue'] : [];
                $tax_value = is_array($tax_value) ? $tax_value : json_decode($tax_value);

                $tax_value = (isset($tax_value[0]) && is_object($tax_value[0])) ? $this->get_value($tax_value) : $tax_value;

                if (is_array($tax_value) && count($tax_value) > 0) {
                    $relation = isset($attr['queryRelation']) ? $attr['queryRelation'] : 'OR';
                    $var = array('relation'=>$relation);
                    foreach ($tax_value as $val) {
                        $tax_name = $attr['queryTax'];
                        // For Custom Terms
                        if ($attr['queryTax'] == 'multiTaxonomy') {
                            $temp = explode('###', $val);
                            if (isset($temp[1])) {
                                $val = $temp[1];
                                $tax_name = $temp[0];
                            }
                        }

                        $var[] = array('taxonomy'=> $tax_name, 'field' => 'slug', 'terms' => $val );
                    }
                    if (count($var) > 1) {
                        $query_args['tax_query'] = $var;
                    }
                }
            }
        }

        
        if (isset($attr['queryExcludeTerm']) && $attr['queryExcludeTerm']) {
            $temp = json_decode($attr['queryExcludeTerm']);
            $_term = [];
            foreach ($temp as $val) {
                $temp = explode('###', $val->value);
                if (isset($temp[1])) {
                    if (array_key_exists($temp[0], $_term)) {
                        $_term[$temp[0]][] = $temp[1];
                    } else {
                        $_term[$temp[0]] = array($temp[1]);
                    }
                }
            }
            if (count($_term) > 0) {
                $final = array('relation' => 'AND');
                foreach ($_term as $key => $val) {
                    $final[] = array(
                        'taxonomy' => $key,
                        'field'    => 'slug',
                        'terms'    => $val,
                        'operator' => 'NOT IN',
                    );
                }

                if (array_key_exists('tax_query', $query_args)) {
                    $query_args['tax_query'] = array(
                        'relation' => 'AND',
                        $query_args['tax_query']
                    );
                    $query_args['tax_query'][] = $final;
                } else {
                    $query_args['tax_query'] = $final;
                }
            }
        }

        if (isset($attr['queryExclude']) && $attr['queryExclude']) {
            $_exclude = (substr($attr['queryExclude'], 0, 1) === "[") ? $this->get_value(json_decode($attr['queryExclude'])) : explode(',', $attr['queryExclude']);
            if (is_array($_exclude) && count($_exclude)) {
                $query_args['post__not_in'] = isset($query_args['post__not_in']) ? array_merge($query_args['post__not_in'], $_exclude) : $_exclude;
            }
        }

        if (isset($attr['queryUnique']) && $attr['queryUnique']) {
            global $unique_ID;
            if (isset($unique_ID[$attr['queryUnique']])) {
                $query_args['post__not_in'] = isset($query_args['post__not_in']) ? array_merge($query_args['post__not_in'], $unique_ID[$attr['queryUnique']]) : $unique_ID[$attr['queryUnique']];
            }
        }

        if (isset($attr['queryQuick'])) {
            if ($attr['queryQuick'] != '' && $post_type != 'archiveBuilder') {
                $query_args = ultimate_post()->get_quick_query($attr, $query_args);
            }
        }

        if (isset($attr['queryOffset']) && $attr['queryOffset'] ) {
            $offset = $this->get_offset($attr['queryOffset'], $query_args);
            $query_args = array_merge($query_args, $offset);
        }

        if (isset($attr['queryAuthor']) && $attr['queryAuthor'] ) {
            $_include = (substr($attr['queryAuthor'], 0, 1) === "[") ? $this->get_value(json_decode($attr['queryAuthor'])) : explode(',', $attr['queryAuthor']);
            if (is_array($_include) && count($_include)) {
                $query_args['author__in'] = $_include;
            }
        }

        if(isset($attr['querySticky']) && $attr['querySticky']) {
            if (filter_var($attr['querySticky'], FILTER_VALIDATE_BOOLEAN)) {
                $sticky = get_option( 'sticky_posts', [] );
                $query_args['post__not_in'] = isset($query_args['post__not_in']) ? array_merge($query_args['post__not_in'], $sticky) : $sticky;
            }
        }

        if (!isset($attr['ajaxCall'])) {
            if(isset($attr['filterShow']) && $attr['filterShow']) {
                if(isset($attr['filterType']) && isset($attr['filterValue'])) {
                    $filterValue = strlen($attr['filterValue']) > 2 ? $attr['filterValue'] : []; 
                    $filterValue = is_array($filterValue) ? $filterValue : json_decode($filterValue);
                    if (is_array($filterValue) && count($filterValue) > 0 && $attr['filterType']) {
                        $final = array('relation' => 'OR');
                        foreach ($filterValue as $key => $val) {
                            $final[] = array(
                                'taxonomy' => $attr['filterType'],
                                'field'    => 'slug',
                                'terms'    => $val,
                                // 'operator' => '=',
                            );
                        }
                        $query_args['tax_query'] = $final;
                    }
                }
            }
        }

        $query_args['wpnonce'] = wp_create_nonce( 'ultp-nonce' );
        
        return apply_filters('ultp_frontend_query', $query_args);
    }

    /**
	 * Get Page Offset
     * 
     * @since v.1.0.0
     * @param | Offset Number(NUMBER) | Query Arg(ARRAY)
	 * @return ARRAY
	 */
    function get_offset($queryOffset, $query_args) {
        $query = array();
        if ($query_args['paged'] > 1) {
            $offset_post = wp_get_recent_posts($query_args, OBJECT);
            if (count($offset_post) > 0 ) {
                $offset = array();
                for($x = count($offset_post); $x > count($offset_post) - $queryOffset; $x--) {
                    $offset[] = $offset_post[$x-1]->ID;
                }
                $query['post__not_in'] = $offset;
            }
        } else {
            $query['offset'] = isset($queryOffset) ? $queryOffset : 0;
        }
        return $query;
    }


    /**
	 * Get Page Number
     * 
     * @since v.1.0.0
     * @param | Attribute of the Query(ARRAY) | Post Number(ARRAY)
	 * @return ARRAY
	 */
    public function get_page_number($attr, $post_number) {
        if ($post_number > 0) {
            if (isset($attr['queryOffset']) && $attr['queryOffset']) {
                $post_number = $post_number - (int)$attr['queryOffset'];
            }
            $post_per_page = isset($attr['queryNumber']) ? ($attr['queryNumber'] ? $attr['queryNumber'] : 1) : 3;
            $pages = ceil($post_number/$post_per_page);
            return $pages ? $pages : 1;
        }else{
            return 1;
        }
    }


    /**
	 * Get Image Size
     * 
     * @since v.1.0.0
     * @param | Attribute of the Query(ARRAY) | Post Number(ARRAY)
	 * @return ARRAY
	 */
    public function get_image_size() {
        $sizes = get_intermediate_image_sizes();
        $filter = array('full' => 'Full');
        foreach ($sizes as $value) {
            $title = ucwords(str_replace(array('_', '-'), array(' ', ' '), $value));
            switch ($value) {
                case 'thumbnail':
                    $title = $title.' [150x150]';
                    break;
                case 'medium':
                    $title = $title.' [300x300]';
                    break;
                case 'large':
                    $title = $title.' [1024x1024]';
                    break;
                case 'ultp_layout_landscape_large':
                    $title = $title.' [1200x800]';
                    break;
                case 'ultp_layout_landscape':
                    $title = $title.' [870x570]';
                    break;
                case 'ultp_layout_portrait':
                    $title = $title.' [600x900]';
                    break;
                case 'ultp_layout_square':
                    $title = $title.' [600x600]';
                    break;
                default:
                    break;
            }
            $filter[$value] = $title;
        }
        return $filter;
    }


    /**
	 * Get All PostType Registered
     * 
     * @since v.1.0.0
     * @param | Attribute of the Query(ARRAY) | Post Number(ARRAY)
	 * @return ARRAY
	 */
    public function get_post_type() {
        $filter = apply_filters('ultp_public_post_type', true);
        $post_type = get_post_types( ($filter ? ['public' => true] : ''), 'names' );
        return array_diff($post_type, array( 'attachment' ));
    }


    /**
	 * Get Pagination HTML
     * 
     * @since v.1.0.0
     * @param | pages (NUMBER) | Pagination Nav (STRING) | Pagination Text |
	 * @return STRING
	 */
    public function pagination($pages = '', $paginationNav = '', $paginationText = '') {
        $html = '';
        $showitems = 3;
        $paged = is_front_page() ? get_query_var('page') : get_query_var('paged');
        $paged = $paged ? $paged : 1;
        if ($pages == '') {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if (!$pages) {
                $pages = 1;
            }
        }
        $data = ($paged>=3?[($paged-1),$paged,$paged+1]:[1,2,3]);

        $paginationText = explode('|', $paginationText);

        $prev_text = isset($paginationText[0]) ? $paginationText[0] : __("Previous", "ultimate-post");
        $next_text = isset($paginationText[1]) ? $paginationText[1] : __("Next", "ultimate-post");
 
        if (1 != $pages) {
            $html .= '<ul class="ultp-pagination">';            
                $display_none = 'style="display:none"';
                if ($pages > 4) {
                    $html .= '<li class="ultp-prev-page-numbers" '.($paged==1?$display_none:"").'><a href="'.get_pagenum_link($paged-1).'">'.ultimate_post()->svg_icon('leftAngle2').' '.($paginationNav == 'textArrow' ? $prev_text : "").'</a></li>';
                }
                if ($pages > 4) {
                    $html .= '<li class="ultp-first-pages" '.($paged<2?$display_none:"").' data-current="1"><a href="'.get_pagenum_link(1).'">1</a></li>';
                }
                if ($pages > 4) {
                    $html .= '<li class="ultp-first-dot" '.($paged<2? $display_none : "").'><a href="#">...</a></li>';
                }
                foreach ($data as $i) {
                    if ($pages >= $i) {
                        $html .= ($paged == $i) ? '<li class="ultp-center-item pagination-active" data-current="'.$i.'"><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>':'<li class="ultp-center-item" data-current="'.$i.'"><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
                    }
                }
                if ($pages > 4) {
                    $html .= '<li class="ultp-last-dot" '.($pages<=$paged+1?$display_none:"").'><a href="#">...</a></li>';
                }
                if ($pages > 4) {
                    $html .= '<li class="ultp-last-pages" '.($pages<=$paged+1?$display_none:"").' data-current="'.$pages.'"><a href="'.get_pagenum_link($pages).'">'.$pages.'</a></li>';
                }
                if ($paged != $pages) {
                    $html .= '<li class="ultp-next-page-numbers"><a href="'.get_pagenum_link($paged + 1).'">'.($paginationNav == 'textArrow' ? $next_text : "").ultimate_post()->svg_icon('rightAngle2').'</a></li>';
                }
            $html .= '</ul>';
        }
        return $html;
    }

    /**
	 * Svg Icon Source
     * 
     * @since v.1.0.0
     * @param STRING | String Name
	 * @return STRING
	 */
    public function svg_icon($ultp_icons = '') {
        $ultp_icon_lists = array(
            'eye'               => file_get_contents(ULTP_PATH.'assets/img/setting/eye.svg'),
            'user'              => file_get_contents(ULTP_PATH.'assets/img/setting/user.svg'),
            'calendar'          => file_get_contents(ULTP_PATH.'assets/img/setting/calendar.svg'),
            'comment'           => file_get_contents(ULTP_PATH.'assets/img/setting/comment.svg'),
            'book'              => file_get_contents(ULTP_PATH.'assets/img/setting/book.svg'),
            'tag'               => file_get_contents(ULTP_PATH.'assets/img/setting/tag.svg'),
            'clock'             => file_get_contents(ULTP_PATH.'assets/img/setting/clock.svg'),
            'leftAngle'         => file_get_contents(ULTP_PATH.'assets/img/setting/leftAngle.svg'),
            'rightAngle'        => file_get_contents(ULTP_PATH.'assets/img/setting/rightAngle.svg'),
            'leftAngle2'        => file_get_contents(ULTP_PATH.'assets/img/setting/leftAngle2.svg'),
            'rightAngle2'       => file_get_contents(ULTP_PATH.'assets/img/setting/rightAngle2.svg'),
            'leftArrowLg'       => file_get_contents(ULTP_PATH.'assets/img/setting/leftArrowLg.svg'),
            'rightArrowLg'      => file_get_contents(ULTP_PATH.'assets/img/setting/rightArrowLg.svg'),
            'refresh'           => file_get_contents(ULTP_PATH.'assets/img/setting/refresh.svg'),
            'upArrow'           => file_get_contents(ULTP_PATH.'assets/img/setting/upArrow.svg'),
            'downArrow'         => file_get_contents(ULTP_PATH.'assets/img/setting/downArrow.svg'),
            'arrowUp2'          => file_get_contents(ULTP_PATH.'assets/img/setting/arrowUp2.svg'),
            'longArrowUp2'      => file_get_contents(ULTP_PATH.'assets/img/setting/longArrowUp2.svg'),
            'caretArrow'        => file_get_contents(ULTP_PATH.'assets/img/setting/caretArrow.svg'),
            'left'              => file_get_contents(ULTP_PATH.'assets/img/setting/left.svg'),
            'center'            => file_get_contents(ULTP_PATH.'assets/img/setting/center.svg'),
            'right'             => file_get_contents(ULTP_PATH.'assets/img/setting/right.svg'),
            'justify'           =>  file_get_contents(ULTP_PATH.'assets/img/setting/justify.svg'),
            'desktop'           => file_get_contents(ULTP_PATH.'assets/img/setting/desktop.svg'),
            'laptop'            => file_get_contents(ULTP_PATH.'assets/img/setting/laptop.svg'),
            'tablet'            => file_get_contents(ULTP_PATH.'assets/img/setting/tablet.svg'),
            'mobile'            => file_get_contents(ULTP_PATH.'assets/img/setting/mobile.svg'),
            'dimension'         => file_get_contents(ULTP_PATH.'assets/img/setting/dimension.svg'),
            'setting'           => file_get_contents(ULTP_PATH.'assets/img/setting/setting.svg'),
            'plus'              => file_get_contents(ULTP_PATH.'assets/img/setting/plus.svg'),
            // Builder
            'commentCount1'     => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/comments_meta/commentCount1.svg'),
            'commentCount2'     => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/comments_meta/commentCount2.svg'),
            'commentCount3'     => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/comments_meta/commentCount3.svg'),
            'commentCount4'     => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/comments_meta/commentCount4.svg'),
            'commentCount5'     => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/comments_meta/commentCount5.svg'),
            'commentCount6'     => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/comments_meta/commentCount6.svg'),
            'date1'             => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/date_meta/date1.svg'),
            'date2'             => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/date_meta/date2.svg'),
            'date3'             => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/date_meta/date3.svg'),
            'date4'             => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/date_meta/date4.svg'),
            'date5'             => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/date_meta/date5.svg'),
            'author1'           => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/author_meta/author1.svg'),
            'author2'           => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/author_meta/author2.svg'),
            'author3'           => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/author_meta/author3.svg'),
            'author4'           => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/author_meta/author4.svg'),
            'author5'           => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/author_meta/author5.svg'),
            'readingTime1'      => file_get_contents(ULTP_PATH.
            '/assets/img/blocks/builder/reading_time/readingTime1.svg'),
            'readingTime2'      => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/reading_time/readingTime2.svg'),
            'readingTime3'      => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/reading_time/readingTime3.svg'),
            'readingTime4'      => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/reading_time/readingTime4.svg'),
            'readingTime5'      => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/reading_time/readingTime5.svg'),
            'viewCount1'        => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/viewCount/viewCount1.svg'),
            'viewCount2'        => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/viewCount/viewCount2.svg'),
            'viewCount3'        => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/viewCount/viewCount3.svg'),
            'viewCount4'        => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/viewCount/viewCount4.svg'),
            'viewCount5'        => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/viewCount/viewCount5.svg'),
            'viewCount6'        => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/viewCount/viewCount6.svg'),
            'cat1'        => file_get_contents(ULTP_PATH.'assets/img/blocks/builder/category/cat1.svg'),
            'cat2'        =>  file_get_contents(ULTP_PATH.'assets/img/blocks/builder/category/cat2.svg'),
            'cat3'        =>  file_get_contents(ULTP_PATH.'assets/img/blocks/builder/category/cat3.svg'),
            'cat4'        =>  file_get_contents(ULTP_PATH.'assets/img/blocks/builder/category/cat4.svg'),
            'cat5'        =>  file_get_contents(ULTP_PATH.'assets/img/blocks/builder/category/cat5.svg'),
            'cat6'        =>  file_get_contents(ULTP_PATH.'assets/img/blocks/builder/category/cat6.svg'),
            'cat7'        =>  file_get_contents(ULTP_PATH.'assets/img/blocks/builder/category/cat7.svg'),
            'tag1'        =>  file_get_contents(ULTP_PATH.'assets/img/blocks/builder/tag/tag1.svg'),
            'tag2'        =>  file_get_contents(ULTP_PATH.'assets/img/blocks/builder/tag/tag2.svg'),
            'tag3'        =>  file_get_contents(ULTP_PATH.'assets/img/blocks/builder/tag/tag3.svg'),
            'tag4'        =>  file_get_contents(ULTP_PATH.'assets/img/blocks/builder/tag/tag4.svg'),
            'tag5'        =>  file_get_contents(ULTP_PATH.'assets/img/blocks/builder/tag/tag5.svg'),
            'tag6'        =>  file_get_contents(ULTP_PATH.'assets/img/blocks/builder/tag/tag6.svg'),
            'rightArrowLg'  => file_get_contents(ULTP_PATH.'assets/img/svg/rightArrowLg.svg'),
            'share'         => file_get_contents(ULTP_PATH.'assets/img/svg/share.svg'),
            'facebook'      => file_get_contents(ULTP_PATH.'assets/img/svg/facebookIcon.svg'),
            'linkedin'      => file_get_contents(ULTP_PATH.'assets/img/svg/linkedinIcon.svg'),
            'mail'          => file_get_contents(ULTP_PATH.'assets/img/svg/mailIcon.svg'),
            'messenger'     => file_get_contents(ULTP_PATH.'assets/img/svg/messengerIcon.svg'),
            'reddit'        => file_get_contents(ULTP_PATH.'assets/img/svg/redditIcon.svg'),
            'twitter'       => file_get_contents(ULTP_PATH.'assets/img/svg/twitterIcon.svg'),
            'whatsapp'      => file_get_contents(ULTP_PATH.'assets/img/svg/whatsappIcon.svg'),
            'pinterest'     => file_get_contents(ULTP_PATH.'assets/img/svg/pinIcon.svg'),
            'skype'         => file_get_contents(ULTP_PATH.'assets/img/svg/skype.svg'),
        ); 
        if ($ultp_icons) {
            return $ultp_icon_lists[ $ultp_icons ];
        }
    }


    /**
	 * Get Excerpt Word
     * 
     * @since v.1.0.0
     * @param NUMBER | Character Length
	 * @return STRING
	 */
    public function excerpt_word($charlength = 200) {
        $html = '';
        $charlength++;
        $excerpt = get_the_excerpt();
        if (mb_strlen( $excerpt ) > $charlength ) {
            $subex = mb_substr( $excerpt, 0, $charlength );
            $exwords = explode( ' ', $subex );
            $excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
            if ($excut < 0 ) {
                $html = mb_substr( $subex, 0, $excut );
            } else {
                $html = $subex;
            }
            $html .= '...';
        } else {
            $html = $excerpt;
        }
        return $html;
    }


    /**
	 * Get Taxonomy Lists
     * 
     * @since v.1.0.0
     * @param STRING | Taxonomy Slug
	 * @return ARRAY
	 */
    public function taxonomy( $prams = 'category' ) {
        $data = array();
        $terms = get_terms( $prams, array(
           'hide_empty' => false,
        ));
        if (!is_wp_error($terms)) {
            foreach ($terms as $val) {
                $data[urldecode_deep($val->slug)] = $val->name;
            }
        }
        return $data;
    }


    /**
	 * Get Taxonomy Data Lists
     * 
     * @since v.1.0.0
     * @param OBJECT | Taxonomy Object
	 * @return ARRAY
	 */
    public function get_tax_data($terms) {
        $temp = array();
        $image = '';
        $thumbnail_id = get_term_meta( $terms->term_id, 'ultp_category_image', true ); 
        if ($thumbnail_id) {
            $image = wp_get_attachment_url( $thumbnail_id ); 
        }
        $temp['url'] = get_term_link($terms);
        $temp['name'] = $terms->name;
        $temp['desc'] = $terms->description;
        $temp['count'] = $terms->count;
        $temp['image'] = $image;
        $color = get_term_meta($terms->term_id, 'ultp_category_color', true);
        $temp['color'] = $color ? $color : '#037fff';
        return $temp;
    }

    public function get_category_data($catSlug, $number = 40, $type = '', $tax_slug = 'category') {
        $data = array();
        if ($type == 'child') {
            $image = '';
            if (!empty($catSlug)) {
                foreach ($catSlug as $cat) {
                    $parent_term = get_term_by('slug', $cat, $tax_slug);
                    if (!empty($parent_term)) {
                        $term_data = get_terms($tax_slug, array( 
                            'hide_empty' => true,
                            'parent' => $parent_term->term_id
                        ));
                        if (!empty($term_data)) {
                            foreach ($term_data as $terms) {
                                $data[] = $this->get_tax_data($terms);
                            }
                        }
                    }
                }
            }
        } else if ($type == 'parent') {
            $term_data = get_terms( $tax_slug, array( 'hide_empty' => true, 'number' => $number, 'parent' => 0 ) );
            if (!empty($term_data)) {
                foreach ($term_data as $terms) {
                    $data[] = $this->get_tax_data($terms);
                }
            }
        } else if ($type == 'custom') {
            foreach ($catSlug as $cat) {
                $terms = get_term_by('slug', $cat, $tax_slug);
                if (!empty($terms)) {
                    $data[] = $this->get_tax_data($terms);
                }
            }
        } else {
            $term_data = get_terms($tax_slug, array('hide_empty' => true, 'number' => $number));
            if (!empty($term_data)) {
                foreach ($term_data as $terms) {
                    $data[] = $this->get_tax_data($terms);
                }
            }
        }
        return $data;
    }


    /**
	 * Get Next Previous HTML
     * 
     * @since v.1.0.0
     * @param OBJECT | Taxonomy Object
	 * @return STRING
	 */
    public function next_prev() {
        $html = '';
        $html .= '<ul>';
            $html .= '<li>';
                $html .= '<a class="ultp-prev-action ultp-disable" href="#">';
                    $html .= ultimate_post()->svg_icon('leftAngle2').'<span class="screen-reader-text">'.esc_html__("Previous", "ultimate-post").'</span>';
                $html .= '</a>';
            $html .= '</li>';
            $html .= '<li>';
                $html .= '<a class="ultp-next-action">';
                    $html .= ultimate_post()->svg_icon('rightAngle2').'<span class="screen-reader-text">'.esc_html__("Next", "ultimate-post").'</span>';
                $html .= '</a>';
            $html .= '</li>';
        $html .= '</ul>';
        return $html;
    }


    /**
	 * Get Loading HTML
     * 
     * @since v.1.0.0
     * @param NULL
	 * @return STRING
	 */
    public function loading() {
        $html = '';
        $style = ultimate_post()->get_setting('preloader_style');
        if ($style == 'style2') {
            $html .= '<div class="ultp-loading-spinner" style="width:100%;height:100%"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>';//ultp-block-items-wrap
        } else {
            $html .= '<div class="ultp-loading-blocks" style="width:100%;height:100%;"><div style="left: 0;top: 0;animation-delay:0s;"></div><div style="left: 21px;top: 0;animation-delay:0.125s;"></div><div style="left: 42px;top: 0;animation-delay:0.25s;"></div><div style="left: 0;top: 21px;animation-delay:0.875s;"></div><div style="left: 42px;top: 21px;animation-delay:0.375s;"></div><div style="left: 0;top: 42px;animation-delay:0.75s;"></div><div style="left: 42px;top: 42px;animation-delay:0.625s;"></div><div style="left: 21px;top: 42px;animation-delay:0.5s;"></div></div>';
        }
        return '<div class="ultp-loading">'.$html.'</div>';
    }


    /**
	 * Get Filter HTML
     * 
     * @since v.1.0.0
     * @param | Filter Text (STRING) | Filter Type (STRING) | Filter Value (ARRAY) | Filter Cat (ARRAY) | Filter Tag (ARRAY) |
	 * @return STRING
	 */
    public function filter($filterText = '', $filterType = '', $filterValue = '[]', $filterMobileText = '...', $filterMobile = true) {
        $html = '';
        $html .= '<ul '.($filterMobile ? 'class="ultp-flex-menu"' : '').' data-name="'.($filterMobileText ? $filterMobileText : '&nbsp;').'">';
            $cat = $this->taxonomy($filterType);
            if ($filterText) {
                $html .= '<li class="filter-item"><a class="filter-active" data-taxonomy="" href="#">'.$filterText.'</a></li>';
            }

            if ($filterValue) {
                $filterValue = strlen($filterValue) > 2 ? $filterValue : []; 
                $filterValue = is_array($filterValue) ? $filterValue : json_decode($filterValue);
                if (is_array($filterValue) && count($filterValue)) {
                    foreach ($filterValue as $val) {
                        $html .= '<li class="filter-item"><a data-taxonomy="'.$val.'" href="#">'.(isset($cat[$val]) ? $cat[$val] : $val).'</a></li>';
                    }
                }
            }
        $html .= '</ul>';
        return $html;
    }


    /**
	 * Check License Status
     * 
     * @since v.2.4.2
	 * @return BOOLEAN | Is pro license active or not
	 */
    public function is_lc_active() {
        if (function_exists('ultimate_post_pro')) {
            return get_option('edd_ultp_license_status') == 'valid' ? true : false;
        }
        if (get_transient( 'ulpt_theme_enable' ) == 'integration') {
            return true;
        }
        return false;
    }


    /**
	 * Get SEO Meta
     * 
     * @since v.2.4.3
     * @param NUMBER | Post ID
	 * @return STRING | SEO Meta Description or Excerpt
	 */
    public function get_excerpt($post_id = 0, $showSeoMeta = 0, $showFullExcerpt = 0, $excerptLimit = 55) {
        $html = '';
        if ($showSeoMeta) {
            $str = '';
            if (function_exists('ultimate_post_pro') ) {
                if (ultimate_post()->get_setting('ultp_yoast') == 'true') {
                    $str =  method_exists( ultimate_post_pro(), 'get_yoast_meta' ) ? ultimate_post_pro()->get_yoast_meta($post_id) : '';
                } else if (ultimate_post()->get_setting('ultp_rankmath') == 'true') {
                    $str = method_exists( ultimate_post_pro(), 'get_rankmath_meta' ) ? ultimate_post_pro()->get_rankmath_meta($post_id) : '';
                } else if (ultimate_post()->get_setting('ultp_aioseo') == 'true') {
                    $str = method_exists( ultimate_post_pro(), 'get_aioseo_meta' ) ? ultimate_post_pro()->get_aioseo_meta($post_id) : '';
                } else if (ultimate_post()->get_setting('ultp_seopress') == 'true') {
                    $str = method_exists( ultimate_post_pro(), 'get_seopress_meta' ) ? ultimate_post_pro()->get_seopress_meta($post_id) : '';
                } else if (ultimate_post()->get_setting('ultp_squirrly') == 'true') {
                    $str = method_exists( ultimate_post_pro(), 'get_squirrly_meta' ) ? ultimate_post_pro()->get_squirrly_meta($post_id) : '';
                }
            }
            $html = $str ? $str : ultimate_post()->excerpt($post_id, $excerptLimit);
        } else {
            if ($showFullExcerpt == 0 ) {
                $html = ultimate_post()->excerpt($post_id, $excerptLimit);
            } else {
                $html = get_the_excerpt();
            }
        }
        return $html;
    }

    /**
	 * Set Inline CSS
     * 
     * @since v.2.5.8
     * @param STRING | CSS
	 * @return STRING | CSS with Style
	 */
    public function set_inline($css) {
        return '<style type="text/css">'.wp_strip_all_tags($css).'</style>';
    }

     /**
	 * Array Sanitize Function
     * 
     * @since v.2.6.0
     * @param ARRAY
	 * @return ARRAY | Array of Sanitize
	 */
    public function recursive_sanitize_text_field($array) {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = $this->recursive_sanitize_text_field($value);
            } else {
                $value = sanitize_text_field($value);
            }
        }
        return $array;
    }
    

    public function get_embeded_video($url, $autoPlay, $loop, $mute, $playback, $preload, $poster, $inline, $size) {
        $vidAutoPlay = $vidloop = $vidloop = $vidmute = $vidplayback = $vidPoster = $vidInline = "";

        if($autoPlay){
            $vidAutoPlay = "autoplay";
        }
        if($poster){
            $vidPoster = 'poster="'.$poster["url"].'"';
        }
        if($loop){
            $vidloop = "loop";
        }
        if($mute){
            $vidmute = "muted";
        }
        if($playback){
            $vidplayback = "controls";
        }
        if($inline){
            $vidInline = "playsinline";
        }
        if (!empty($url)) {
            $embeded = wp_oembed_get($url, $size);
            if ($embeded == false) {
                $format = '';
                $url = strtolower($url);
                if (strpos($url, '.mp4')) {
                    $format = 'mp4';
                } elseif (strpos($url, '.ogg')) {
                    $format = 'ogg';
                } elseif (strpos($url, '.webm')) {
                    $format = 'WebM';
                }
                $embeded = '<video 
                '.$vidloop.'
                '.$vidmute.'
                '.$vidplayback.'
                preload="'.$preload.'"
                '.$vidAutoPlay.'
                '.$vidPoster.'
                '.$vidInline.'
            ><source src="' . $url . '" type="video/' . $format . '">' . __('Your browser does not support the video tag.', 'ultimate-post') . '</video>';
            return '<div class="ultp-video-wrapper">'.$embeded.'</div>';
            }
            return '<div class="ultp-video-wrapper ultp-embaded-video">'.$embeded.'</div>';
        } else {
            return false;
        }
    }

    /**
	 * Get Public taxonomy Lists
     * 
     * @since v.2.7.0
     * @param NULL
	 * @return ARRAY | Taxonomy Lists as array
	 */
    public function get_taxonomy_list() {
        $taxonomy = get_taxonomies(array('public' => true));
        return empty($taxonomy) ? [] : array_keys($taxonomy);
    }

    /**
	 * Content Print
     * 
     * @since v.2.7.0
     * @param NUMBER | Post ID
	 * @return STRING | Content of the Post
	 */ 
    public function content($post_id) {
        $content_post = get_post($post_id);
        $content = $content_post->post_content;
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);
        echo  $content;
    }


    /**
	 * String Part finder inside array
     * 
     * @since v.2.7.0
	 * @return ARRAY | String Part
	 */
    public function in_string_part($part, $data, $isValue = false) {
        $return = false;
        foreach ($data as $val) {
            if (strpos($val, $part) !== false) {
                $return = $isValue ? $val : true;
                break;
            }
        }
        return $return;
    }

    /**
	 * All Addons Data
     * 
     * @since v.2.7.0
	 * @return ARRAY | String Part
	 */
    public static function all_addons() {
        $all_addons = array(
            'ultp_category' => array(
                'name' => __( 'Category', 'ultimate-post' ),
                'desc' => __( 'Choose your desired color and Image for categories or any taxonomy.', 'ultimate-post' ),
                'img' => ULTP_URL.'/assets/img/addons/category-style.svg',
                'is_pro' => true,
                'docs' => 'https://docs.wpxpo.com/docs/postx/',
                'live' => 'https://www.wpxpo.com/postx/addons/category/',
                'position' => 15
            ),
            'ultp_progressbar' => array(
                'name' => __( 'Progressbar', 'ultimate-post' ),
                'desc' => __( 'Let the users see a graphical indicator to know the reading progress of a blog post.', 'ultimate-post' ),
                'img' => ULTP_URL.'/assets/img/addons/progressbar.svg',
                'is_pro' => true,
                'docs' => 'https://docs.wpxpo.com/docs/postx/add-on/progress-bar/',
                'live' => 'https://www.wpxpo.com/postx/progress-bar/',
                'position' => 30
            ),
            'ultp_yoast' => array(
                'name' => __( 'Yoast Meta', 'ultimate-post' ),
                'desc' => __( 'Show Yoast meta description in the excerpt.', 'ultimate-post' ),
                'img' => ULTP_URL.'/assets/img/addons/yoast.svg',
                'is_pro' => true,
                'docs' => 'https://docs.wpxpo.com/docs/postx/add-on/seo-meta/',
                'live' => 'https://www.wpxpo.com/postx/addons/yoast/',
                'required' => array(
                    'name' => 'Yoast',
                    'slug' => 'wordpress-seo/wp-seo.php'
                ),
                'position' => 55
                ),
            'ultp_aioseo' => array(
                'name' => __( 'All in One SEO Meta', 'ultimate-post' ),
                'desc' => __( 'Show All in One SEO meta description in the excerpt.', 'ultimate-post' ),
                'img' => ULTP_URL.'/assets/img/addons/aioseo.svg',
                'is_pro' => true,
                'docs' => 'https://docs.wpxpo.com/docs/postx/add-on/seo-meta/',
                'live' => 'https://www.wpxpo.com/postx/addons/all-in-one-seo-meta/',  
                'required' => array(
                    'name' => 'All in One SEO',
                    'slug' => 'all-in-one-seo-pack/all_in_one_seo_pack.php'
                ),
                'position' => 35
                ),
            'ultp_rankmath' => array(
                'name' => __( 'RankMath Meta', 'ultimate-post' ),
                'desc' => __( 'Show RankMath meta description in the excerpt.', 'ultimate-post' ),
                'img' => ULTP_URL.'/assets/img/addons/rankmath.svg',
                'is_pro' => true,
                'docs' => 'https://docs.wpxpo.com/docs/postx/add-on/seo-meta/', 
                'live' => 'https://www.wpxpo.com/postx/addons/rankmath/',
                'required' => array(
                    'name' => 'RankMath',
                    'slug' => 'seo-by-rank-math/rank-math.php'
                ),
                'position' => 40
                ),
            'ultp_seopress' => array(
                'name' => __( 'SEOPress Meta', 'ultimate-post' ),
                'desc' => __( 'Show SEOPress meta description in the excerpt.', 'ultimate-post' ),
                'img' => ULTP_URL.'/assets/img/addons/seopress.svg',
                'is_pro' => true,
                'docs' => 'https://docs.wpxpo.com/docs/postx/add-on/seo-meta/', 
                'live' => 'https://www.wpxpo.com/postx/addons/seopress/',
                'required' => array(
                    'name' => 'SEOPress',
                    'slug' => 'wp-seopress/seopress.php'
                ),
                'position' => 45
                ),
            'ultp_squirrly' => array(
                'name' => __( 'Squirrly Meta', 'ultimate-post' ),
                'desc' => __( 'Show Squirrly meta description in the excerpt.', 'ultimate-post' ),
                'img' => ULTP_URL.'/assets/img/addons/squirrly.svg',
                'is_pro' => true,
                'docs' => 'https://docs.wpxpo.com/docs/postx/add-on/seo-meta/',
                'live' => 'https://www.wpxpo.com/postx/addons/squirrly/',
                'required' => array(
                    'name' => 'Squirrly',
                    'slug' => 'squirrly-seo/squirrly.php'
                ),
                'position' => 50
            ),
            );
        return  apply_filters('ultp_addons_config', $all_addons);
    }

    /**
	 * All Block Data
     * 
     * @since v.2.7.0
	 * @return ARRAY | String Part
	 */
    public function get_blocks_settings() {
        $arr = array(
            'grid' => array(
                'label' => __('Post Grid Blocks', 'ultimate-post'),
                'attr' => array(
                    'post_grid_1' => array(
                        'label' => __('Post Grid #1', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6829',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/',
                        'icon' => ULTP_URL.'assets/img/blocks/post-grid-1.svg'
                    ),
                    'post_grid_2' => array(
                        'label' => __('Post Grid #2', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6830',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/',
                        'icon' => ULTP_URL.'assets/img/blocks/post-grid-2.svg'
                    ),
                    'post_grid_3' => array(
                        'label' => __('Post Grid #3', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6831',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/',
                        'icon' => ULTP_URL.'assets/img/blocks/post-grid-3.svg'
                    ),
                    'post_grid_4' => array(
                        'label' => __('Post Grid #4', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6832',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/',
                        'icon' => ULTP_URL.'assets/img/blocks/post-grid-4.svg'
                    ),
                    'post_grid_5' => array(
                        'label' => __('Post Grid #5', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6833',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/',
                        'icon' => ULTP_URL.'assets/img/blocks/post-grid-5.svg'
                    ),
                    'post_grid_6' => array(
                        'label' => __('Post Grid #6', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6834',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/',
                        'icon' => ULTP_URL.'assets/img/blocks/post-grid-6.svg'
                    ),
                    'post_grid_7' => array(
                        'label' => __('Post Grid #7', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6835',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/',
                        'icon' => ULTP_URL.'assets/img/blocks/post-grid-7.svg'
                    )
                )
            ),
            'list' => array(
                'label' => __('Post List Blocks', 'ultimate-post'),
                'attr' => array(
                    'post_list_1' => array(
                        'label' => __('Post List #1', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6836',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/',
                        'icon' => ULTP_URL.'assets/img/blocks/post-list-1.svg'
                    ),
                    'post_list_2' => array(
                        'label' => __('Post List #2', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6837',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/',
                        'icon' => ULTP_URL.'assets/img/blocks/post-list-2.svg'
                    ),
                    'post_list_3' => array(
                        'label' => __('Post List #3', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6838',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/',
                        'icon' => ULTP_URL.'assets/img/blocks/post-list-3.svg'
                    ),
                    'post_list_4' => array(
                        'label' => __('Post List #4', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6839',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/',
                        'icon' => ULTP_URL.'assets/img/blocks/post-list-4.svg'
                    ),
                    
                )
                ),
            'slider' => array(
                'label' => __('Post Slider', 'ultimate-post'),
                'attr' => array(
                    'post_slider_1' => array(
                        'label' => __('Post Slider #1', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6840',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/',
                        'icon' => ULTP_URL.'/assets/img/blocks/post-slider-1.svg'
                    ),
                    'post_slider_2' => array(
                        'label' => __('Post Slider #2', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid7487',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/all-blocks/post-slider-2/',
                        'icon' => ULTP_URL.'/assets/img/blocks/post-slider-2.svg'
                    ),
                )
            ),
            'other' => array(
                'label' => __('Others Post Blocks', 'ultimate-post'),
                'attr' => array(
                    'post_module_1' => array(
                        'label' => __('Post Module #1', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6825',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/',
                        'icon' => ULTP_URL.'/assets/img/blocks/post-module-1.svg'
                    ),
                    'post_module_2' => array(
                        'label' => __('Post Module #2', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6827',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/',
                        'icon' => ULTP_URL.'/assets/img/blocks/post-module-2.svg'
                    ),
                    'heading' => array(
                        'label' => __('Heading', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6842',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/',
                        'icon' => ULTP_URL.'/assets/img/blocks/heading.svg'
                    ),
                    'image' => array(
                        'label' => __('Image', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6843',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/all-blocks/image-blocks/',
                        'icon' => ULTP_URL.'/assets/img/blocks/image.svg'
                    ),
                    'taxonomy' => array(
                        'label' => __('Taxonomy', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6841',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/all-blocks/taxonomy-style-1/',
                        'icon' => ULTP_URL.'/assets/img/blocks/taxonomy.svg'
                    ),
                    'wrapper' => array(
                        'label' => __('Wrapper', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6844',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/add-on/wrapper-blocks/',
                        'icon' => ULTP_URL.'/assets/img/blocks/wrapper.svg'
                    ),
                    'news_ticker' => array(
                        'label' => __('News Ticker', 'ultimate-post'),
                        'default' => true,
                        'live' => 'https://www.wpxpo.com/postx/blocks/#demoid6845',
                        'docs' => 'https://docs.wpxpo.com/docs/postx/all-blocks/news-ticker-block/',
                        'icon' => ULTP_URL.'/assets/img/blocks/news-ticker.svg'
                    ),
                )
            ),
            'builder' => array(
                'label' => __('Site Builder Blocks', 'ultimate-post'),
                'attr' => array(
                    'builder_post_title' => array(
                        'label' => __('Post Title', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/post_title.svg'
                    ),
                    'builder_advance_post_meta' => array(
                        'label' => __('Advance Post Meta', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/post_meta.svg'
                    ),
                    'builder_archive_title' => array(
                        'label' => __('Archive Title', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/archive-title.svg'
                    ),
                    'builder_author_box' => array(
                        'label' => __('Post Author Box', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/author_box.svg'
                    ),
                    'builder_post_next_previous' => array(
                        'label' => __('Post Next Previous', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/next_previous.svg'
                    ),
                    'builder_post_author_meta' => array(
                        'label' => __('Post Author Meta', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/author.svg'
                    ),
                    'builder_post_breadcrumb' => array(
                        'label' => __('Post Breadcrumb', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/breadcrumb.svg'
                    ),
                    'builder_post_category' => array(
                        'label' => __('Post Category', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/category.svg'
                    ),
                    'builder_post_comment_count' => array(
                        'label' => __('Post Comment Count', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/comment_count.svg'
                    ),
                    'builder_post_comments' => array(
                        'label' => __('Post Comments', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/comments.svg'
                    ),
                    'builder_post_content' => array(
                        'label' => __('Post Content', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/content.svg'
                    ),
                    'builder_post_date_meta' => array(
                        'label' => __('Post Date Meta', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/post_date.svg'
                    ),
                    'builder_post_excerpt' => array(
                        'label' => __('Post Excerpt', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/excerpt.svg'
                    ),
                    'builder_post_featured_image' => array(
                        'label' => __('Post Featured Image', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/featured_img.svg'
                    ),
                    'builder_post_reading_time' => array(
                        'label' => __('Post Reading Time', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/reading_time.svg'
                    ),
                    'builder_post_social_share' => array(
                        'label' => __('Post Social Share', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/share.svg'
                    ),
                    'builder_post_tag' => array(
                        'label' => __('Post Tag', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/post_tag.svg'
                    ),
                    'builder_post_view_count' => array(
                        'label' => __('Post View Count', 'ultimate-post'),
                        'default' => true,
                        'docs' => 'https://docs.wpxpo.com/docs/postx/dynamic-site-builder/',
                        'icon' => ULTP_URL.'/assets/img/blocks/builder/view_count.svg'
                    ),
                )
            )
        );

        return $arr;
    }
    
    /**
	 * Builder Conditions
     * 
     * @since v.2.7.0
     * @param STRING | Type of Return
	 * @return MIXED || ID or Path
	 */
     public function conditions( $type = 'return' ) {
        $page_id = '';

        $conditions = get_option('ultp_builder_conditions', array());
        if (isset($conditions['archive'])) {
            if (!empty($conditions['archive'])) {
                $is_search = is_search();
                $is_archive = is_archive();
                $taxonomy = (is_category() || is_tag() || is_tax()) ? get_queried_object() : (object)[];
                
                    if (is_archive()) {
                        foreach ($conditions['archive'] as $key => $val) {
                            if (in_array('include/archive', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/archive', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                        }
                        if (is_category()) {
                            foreach ($conditions['archive'] as $key => $val) {
                            if (in_array('include/archive/category', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/archive/category', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                            if (in_array('include/archive/category/'.$taxonomy->term_id, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/archive/category/'.$taxonomy->term_id, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                            if ($this->in_string_part('any_child_of', $val)) {
                                if (in_array('include/archive/any_child_of_category', $val)) {
                                    if ('publish' == get_post_status($key)) {
                                        $page_id = $key;
                                    }
                                }
                                if (in_array('exclude/archive/any_child_of_category', $val)) {
                                    if ('publish' == get_post_status($key)) {
                                        $page_id = '';
                                    }
                                }
                                foreach ($val as $v) {
                                    if (strpos($v, '/archive/any_child_of_category/') !== false) {
                                        if ($v) {
                                            $data = explode("/", $v);
                                            if (isset($data[3]) && $data[3]) {
                                                if (term_is_ancestor_of($data[3], $taxonomy->term_id, 'category')) {
                                                    if ('publish' == get_post_status($key)) {
                                                        $page_id = $data[0] == 'exclude' ? '' : $key;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($this->in_string_part('child_of', $val)) {
                                    if (in_array('include/archive/child_of_category', $val)) {
                                        if ('publish' == get_post_status($key)) {
                                            $page_id = $key;
                                        }
                                    }
                                    if (in_array('exclude/archive/child_of_category', $val)) {
                                        if ('publish' == get_post_status($key)) {
                                            $page_id = '';
                                        }
                                    }
                                    if (in_array('include/archive/child_of_category/'.$taxonomy->parent, $val)) {
                                        if ('publish' == get_post_status($key)) {
                                            $page_id = $key;
                                        }
                                    }
                                    if (in_array('exclude/archive/child_of_category/'.$taxonomy->parent, $val)) {
                                        if ('publish' == get_post_status($key)) {
                                            $page_id = '';
                                        }
                                    }
                                }
                            }
                            }
                        } else if (is_tag()) {
                            foreach ($conditions['archive'] as $key => $val) {
                            if (in_array('include/archive/post_tag', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/archive/post_tag', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                            if (in_array('include/archive/post_tag/'.$taxonomy->term_id, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/archive/post_tag/'.$taxonomy->term_id, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                            }
                        } else if (is_tax()) {
                            foreach ($conditions['archive'] as $key => $val) {
                            if (in_array('include/archive/'.$taxonomy->taxonomy, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/archive/'.$taxonomy->taxonomy, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                            if (in_array('include/archive/'.$taxonomy->taxonomy.'/'.$taxonomy->term_id, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/archive/'.$taxonomy->taxonomy.'/'.$taxonomy->term_id, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                            if ($this->in_string_part('any_child_of', $val)) {
                                if (in_array('include/archive/any_child_of_'.$taxonomy->taxonomy, $val)) {
                                    if ('publish' == get_post_status($key)) {
                                        $page_id = $key;
                                    }
                                }
                                if (in_array('exclude/archive/any_child_of_'.$taxonomy->taxonomy, $val)) {
                                    if ('publish' == get_post_status($key)) {
                                        $page_id = '';
                                    }
                                }
                                foreach ($val as $v) {
                                    if (strpos($v, '/archive/any_child_of_'.$taxonomy->taxonomy.'/') !== false) {
                                        if ($v) {
                                            $data = explode("/", $v);
                                            if (isset($data[3]) && $data[3]) {
                                                if (term_is_ancestor_of($data[3], $taxonomy->term_id, $taxonomy->taxonomy)) {
                                                    if ('publish' == get_post_status($key)) {
                                                        $page_id = $data[0] == 'exclude' ? '' : $key;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($this->in_string_part('child_of', $val)) {
                                    if (in_array('include/archive/child_of_'.$taxonomy->taxonomy, $val)) {
                                        if ('publish' == get_post_status($key)) {
                                            $page_id = $key;
                                        }
                                    }
                                    if (in_array('exclude/archive/child_of_'.$taxonomy->taxonomy, $val)) {
                                        if ('publish' == get_post_status($key)) {
                                            $page_id = '';
                                        }
                                    }
                                    if (in_array('include/archive/child_of_'.$taxonomy->taxonomy.'/'.$taxonomy->parent, $val)) {
                                        if ('publish' == get_post_status($key)) {
                                            $page_id = $key;
                                        }
                                    }
                                    if (in_array('exclude/archive/child_of_'.$taxonomy->taxonomy.'/'.$taxonomy->parent, $val)) {
                                        if ('publish' == get_post_status($key)) {
                                            $page_id = '';
                                        }
                                    }
                                }
                            }
                            }
                        } else if (is_date()) {
                            foreach ($conditions['archive'] as $key => $val) {
                            if (in_array('include/archive/date', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/archive/date', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                            }
                        } else if (is_author()) {
                            foreach ($conditions['archive'] as $key => $val) {
                            if (in_array('include/archive/author', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/archive/author', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                            $author_id = get_the_author_meta('ID');
                            if (in_array('include/archive/author/'.$author_id, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/archive/author/'.$author_id, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                            }
                        }
                    } else if (is_search()) {
                        foreach ($conditions['archive'] as $key => $val) {
                        if (in_array('include/archive/search', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = $key;
                            }
                        }
                        if (in_array('exclude/archive/search', $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = '';
                            }
                        }
                        }
                    }
            }  
        }
        // Singular
        if (isset($conditions['singular'])) {
            if (!empty($conditions['singular']) && is_singular()) {
                $obj = get_queried_object();
                $tax_list = $this->get_taxonomy_list();
                foreach ($conditions['singular'] as $key => $val) {
                    if (get_post_status($key)) {
                        // Front Page
                        if (is_front_page()) {
                            if (in_array('include/singular/front_page', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/singular/front_page', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                        }
                        // Author check
                        if (in_array('singular/post_by_author', $val)) {
                            if (in_array('include/singular/post_by_author', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/singular/post_by_author', $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                            if (in_array('include/singular/post_by_author/'.$obj->post_author, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = $key;
                                }
                            }
                            if (in_array('exclude/singular/post_by_author/'.$obj->post_author, $val)) {
                                if ('publish' == get_post_status($key)) {
                                    $page_id = '';
                                }
                            }
                        }
                        // All Taxonomy
                        if($this->in_string_part('/singular/in_', $val)) {
                            foreach ($tax_list as $tax) { 
                                if ($this->in_string_part('singular/in_'.$tax.'_children', $val)) {
                                    // In Taxonomy Children
                                    if (in_array('include/singular/in_'.$tax.'_children', $val)) {
                                        if ('publish' == get_post_status($key)) {
                                            $page_id = $key;
                                        }
                                    }
                                    if (in_array('exclude/singular/in_'.$tax.'_children', $val)) {
                                        if ('publish' == get_post_status($key)) {
                                            $page_id = '';
                                        }
                                    }
                                    foreach ($val as $v) {
                                        if (strpos($v, '/singular/in_'.$tax.'_children/') !== false) {
                                            if ($v) {
                                                $data = explode("/", $v);
                                                if (isset($data[3]) && $data[3]) {
                                                    if (is_object_in_term($obj->ID , $tax, $data[3] )) {
                                                        if ('publish' == get_post_status($key)) {
                                                            $page_id = $data[0] == 'exclude' ? '' : $key;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    // IN Taxonomy 
                                    if (in_array('include/singular/in_'.$tax, $val)) {
                                        if ('publish' == get_post_status($key)) {
                                            $page_id = $key;
                                        }
                                    }
                                    if (in_array('exclude/singular/in_'.$tax, $val)) {
                                        if ('publish' == get_post_status($key)) {
                                            $page_id = '';
                                        }
                                    }
                                    foreach ($val as $v) {
                                        if (strpos($v, '/singular/in_'.$tax.'/') !== false) {
                                            if ($v) {
                                                $data = explode("/", $v);
                                                if (isset($data[3]) && $data[3]) {
                                                    if (is_object_in_term($obj->ID , $tax, $data[3] )) {
                                                        if ('publish' == get_post_status($key)) {
                                                            $page_id = $data[0] == 'exclude' ? '' : $key;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        // All Post Type
                        if (in_array('include/singular/'.$obj->post_type, $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = $key;
                            }
                        }
                        if (in_array('exclude/singular/'.$obj->post_type, $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = '';
                            }
                        }
                        if ($this->in_string_part('include/singular/'.$obj->post_type.'/'.$obj->ID, $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = $key;
                            }
                        }
                        if ($this->in_string_part('exclude/singular/'.$obj->post_type.'/'.$obj->ID, $val)) {
                            if ('publish' == get_post_status($key)) {
                                $page_id = '';
                            }
                        }
                    }
                }
            }
        }
        
        if ($type == 'return') {
            return $page_id;
        }
        if ($type == 'includes') {
            return $page_id ? ULTP_PATH.'addons/builder/Archive_Template.php' : '';
        }
    }

    
    /**
	 * Get Add Default Condition Data
     * 
     * @since v.2.7.0
	 * @return ARRAY | Default Data
	 */
    public function builder_data() {
        $archive_data = [
            [
                'label' => 'All Archive',
                'value' => '',
            ],
            [
                'label' => 'Author Archive',
                'value' => 'author',
                'search' => 'author###'
            ],
            [
                'label' => 'Date Archive',
                'value' => 'date',
            ],
            [
                'label' => 'Search Results',
                'value' => 'search',
            ],
        ];
        $single_data = [
            [
                'label' => 'Front Page', 
                'value' => 'front_page', 
            ]
        ];
        
        $post_type = get_post_types( ['public' => true], 'objects' );
        foreach ($post_type as $key => $type) {
            // Post Type
            $single_temp = [ 
                'label' => $type->label, 
                'value' => $type->name, 
                'search' => 'type###'.$type->name 
            ];
            $archive_temp = [];

            // Taxonomy
            $taxonomy = get_object_taxonomies( $type->name, 'objects' );
            if (!empty($taxonomy)) {
                $single_tax = $archive_tax = [];
                $single_tax[] = $single_temp;

                $archive_temp = [ 
                    'label' => $type->label . ' Archive', 
                    'value' => $type->name . '_archive',
                ];
                // $archive_tax[] = $archive_temp;
                foreach ($taxonomy as $key => $val) {
                    if ($val->public) {
                        $single_tax[] = [
                            'label' => 'In ' . $val->label, 
                            'value' => 'in_' . $val->name, 
                            'search' => 'term###'.$val->name
                        ];
                        $archive_tax[] = [
                            'label' => $val->label, 
                            'value' => $val->name, 
                            'search' => 'term###'.$val->name
                        ];

                        if ($val->hierarchical) {
                            // Hierarchical
                            $single_tax[] = [
                                'label' => 'In Child ' . $val->label,
                                'value' => 'in_' . $val->name . '_children',
                                'search' => 'term###'.$val->name
                            ];
                            $archive_tax[] = [
                                'label' => 'Direct Child ' . $val->label . ' Of',
                                'value' => 'child_of_' . $val->name,
                                'search' => 'term###'.$val->name
                            ];
                            $archive_tax[] = [
                                'label' => 'Any Child ' . $val->label . ' Of',
                                'value' => 'any_child_of_' . $val->name,
                                'search' => 'term###'.$val->name
                            ];
                        }
                    }
                }
                // Author
                $single_tax[] = [
                    'label' => 'Posts By Author',
                    'value' => 'post_by_author',
                    'search' => 'author###'
                ];
                $single_temp['attr'] = $single_tax;
                $archive_temp['attr'] = $archive_tax;
            }
            $single_data[] = $single_temp;
            if (!empty($archive_temp)) {
                $archive_data[] = $archive_temp;
            }
        }

        return ['singular' => $single_data, 'archive' => $archive_data];
    }

    /**
	 * Get Date Default Format
     * 
     * @since v.2.7.2
	 * @return ARRAY | Default Data
	 */
    public function get_format($format) {
        if ($format == 'default_date') {
            return $this->get_setting('date_format');
        } else if ($format == 'default_date_time') {
            return $this->get_setting('date_format').' '.$this->get_setting('time_format');
        } else {
            return $format;
        }
    }

    public function pro_popup_html() { ?>
        <div class="ultp-popup-container popup-center">
            <div class="ultp-unlock-popup ultp-unlock-modal">
                <img src="<?php echo esc_url(ULTP_URL . '/assets/img/admin/dashboard/unlock-super-icon.svg'); ?>" alt="lock icon">
                <h4 class="ultp-md-heading ultp-mt25 ultp-mb25"><?php esc_html_e('Unlock 11+ Addons', 'ultimate-post'); ?></h4>
                <div class="ultp-popup-desc">
                    <?php esc_html_e('We’re sorry, PostX Addons are not available on PostX  free. Please upgrade to a PRO plan to unlock the add  ons of your choice.', 'ultimate-post'); ?>
                </div>
                <a href="<?php echo esc_url(ultimate_post()->get_premium_link('', 'postx_dashboard_addons_popup')); ?>" class="ultp-popup-planbtn ultp-btn ultp-btn-warning ultp-mt25"><?php esc_html_e('Upgrade Plan', 'ultimate-post'); ?></a>
                <button class="ultp-popup-close"></button>
            </div>
        </div>
    <?php }

    /**
	 * Common Frontend and Backend CSS and JS Scripts
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function register_scripts_common() {
        wp_enqueue_style('ultp-style', ULTP_URL.'assets/css/style.min.css', array(), ULTP_VER );
        wp_enqueue_script('ultp-script', ULTP_URL.'assets/js/ultp.min.js', array('jquery'), ULTP_VER, true);
        wp_localize_script('ultp-script', 'ultp_data_frontend', array(
            'url' => ULTP_URL,
            'ajax' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('ultp-nonce')
        ));
    }

}
