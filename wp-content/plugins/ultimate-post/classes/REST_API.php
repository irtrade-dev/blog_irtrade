<?php
/**
 * REST API Action.
 * 
 * @package ULTP\REST_API
 * @since v.1.0.0
 */
namespace ULTP;

defined('ABSPATH') || exit;

/**
 * Styles class.
 */
class REST_API{
    
    /**
	 * Setup class.
	 *
	 * @since v.1.0.0
	 */
    public function __construct() {
        add_action( 'rest_api_init', array($this, 'ultp_register_route') );
    }


    /**
	 * REST API Action
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function ultp_register_route() {
        register_rest_route( 'ultp', 'posts', array(
                'methods' => \WP_REST_Server::READABLE,
                'args' => array(),
                'callback' => array($this,'ultp_route_post_data'),
                'permission_callback' => '__return_true'
            )
        );
        register_rest_route( 'ultp', 'common', array(
                'methods' => \WP_REST_Server::READABLE,
                'args' => array('wpnonce' => []),
                'callback' => array($this,'ultp_route_common_data'),
                'permission_callback' => '__return_true'
            )
        );
        register_rest_route( 'ultp', 'specific_taxonomy', array(
                'methods' => \WP_REST_Server::READABLE,
                'args' => array('taxType' => [], 'taxSlug' => [], 'taxValue' => [], 'queryNumber' => [], 'wpnonce' => []),
                'callback' => array($this,'ultp_route_taxonomy_info_data'),
                'permission_callback' => '__return_true'
            )
        );
        register_rest_route(
			'ultp/v1',
			'/search/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'search_settings_action'),
					'permission_callback' => function () {
						return current_user_can('edit_posts');
					},
					'args' => array()
				)
			)
		);
        register_rest_route(
			'ultp/v2',
			'/template_page_insert/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'template_page_insert'),
					'permission_callback' => function () {
						return current_user_can('edit_posts');
					},
					'args' => array()
				)
			)
		);
    }

    /**
	 * Insert Post For Imported Template
     * 
     * @since v.2.6.7
     * @param STRING
	 * @return ARRAY | Inserted Post Url 
	 */
    public function template_page_insert($server) {
        $post = $server->get_params();
        $new_page = array(
            'post_title' => $post['title'],
            'post_type' => 'page',
            'post_content' => $post['blockCode'],	
            'post_status' => 'draft',
        );
        $post_id = wp_insert_post( $new_page );
        return array( 'success' => true, 'link' => get_edit_post_link($post_id));
    }


    public function search_settings_action($server) {
		global $wpdb;
        $post = $server->get_params();

        switch ($post['type']) {
            case 'posts':
            case 'allpost':
            case 'postExclude':
                $post_type = array('post');
                if ($post['type'] == 'allpost') {
                    $post_type = array_keys(ultimate_post()->get_post_type());
                } else if ($post['type'] == 'postExclude') {
                    $post_type = array($post['condition']);
                }
                $args = array(
                    'post_type'         => $post_type,
                    'post_status'       => 'publish',
                    'posts_per_page'    => 10,
                );
                if (is_numeric($post['term'])) {
                    $args['p'] = $post['term'];
                } else {
                    $args['s'] = $post['term'];
                }

                $post_results = new \WP_Query($args);
                $data = [];
                if (!empty($post_results)) {
                    while ( $post_results->have_posts() ) {
                        $post_results->the_post();
                        $id = get_the_ID();
                        $title = html_entity_decode(get_the_title());
                        $data[] = array('value'=>$id, 'title'=>($title?'[ID: '.$id.'] '.$title:('[ID: '.$id.']')));
                    }
                    wp_reset_postdata();
                }
                return ['success' => true, 'data' => $data];
                break;

            case 'author':
                $term = '%'. $wpdb->esc_like( $post['term'] ) .'%';
                $post_results = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT ID, display_name 
                        FROM $wpdb->users 
                        WHERE user_login LIKE '%s' OR ID LIKE '%s' OR user_nicename LIKE '%s' OR user_email LIKE '%s' OR display_name LIKE '%s' LIMIT 10", $term, $term, $term, $term, $term 
                    )
                );
                $data = [];
                if (!empty($post_results)) {
                    foreach ($post_results as $key => $val) {
                        $data[] = array('value'=>$val->ID, 'title'=>'[ID: '.$val->ID.'] '.$val->display_name);
                    }
                }
                return ['success' => true, 'data' => $data];
                break;

            case 'taxvalue':
                $split = explode('###', $post['condition']);
                $condition = $split[1] != 'multiTaxonomy' ? array($split[1]) : get_object_taxonomies($split[0]);
                $args = array(
                    'taxonomy'  => $condition,
                    'fields'    => 'all',
                    'orderby'   => 'id', 
                    'order'     => 'ASC',
                    'name__like'=> $post['term']
                );
                if (is_numeric($post['term'])) {
                    unset($args['name__like']);
                    $args['include'] = array($post['term']);
                }

                $post_results = get_terms( $args );
                $data = [];
                if (!empty($post_results)) {
                    foreach ($post_results as $key => $val) {
                        if ($split[1] == 'multiTaxonomy') {
                            $data[] = array('value'=>$val->taxonomy.'###'.$val->slug, 'title'=> '[ID: '.$val->term_id.'] '.$val->taxonomy.': '.$val->name);
                        } else {
                            $data[] = array('value'=>$val->slug, 'title'=>'[ID: '.$val->term_id.'] '.$val->name);
                        }
                    }
                }
                return ['success' => true, 'data' => $data];
                break;

            case 'taxExclude':
                $condition = get_object_taxonomies($post['condition']);
                $args = array(
                    'taxonomy'  => $condition,
                    'fields'    => 'all',
                    'orderby'   => 'id', 
                    'order'     => 'ASC',
                    'name__like'=> $post['term']
                ); 
                if (is_numeric($post['term'])) {
                    unset($args['name__like']);
                    $args['include'] = array($post['term']);
                }
                $post_results = get_terms( $args );
                $data = [];
                if (!empty($post_results)) {
                    foreach ($post_results as $key => $val) {
                        $data[] = array('value'=>$val->taxonomy.'###'.$val->slug, 'title'=> '[ID: '.$val->term_id.'] '.$val->taxonomy.': '.$val->name);
                    }
                }
                return ['success' => true, 'data' => $data];
                break;
                

            default:
                return ['success' => true, 'data' => [['value'=>'', 'title'=>'- Select -']]];
                break;
        }
	}

    /**
	 * Post Data Response of REST API
     * 
     * @since v.1.0.0
     * @param MIXED | Pram (ARRAY), Local (BOOLEAN)
	 * @return ARRAY | Response Image Size as Array
	 */
    public function ultp_route_post_data($prams,$local=false) {
        if (!wp_verify_nonce(wp_unslash($_REQUEST['wpnonce']), 'ultp-nonce') && $local){
            return ;
        }

        $prams = $prams->get_params();
        
        $data = [];
        $loop = new \WP_Query( ultimate_post()->get_query($prams) );
        
        if ($loop->have_posts()) {
            while($loop->have_posts()) {
                $loop->the_post(); 
                $var                = array();
                $post_id            = get_the_ID();
                $user_id            = get_the_author_meta('ID');
                $content_data       = get_the_content();
                $var['ID']          = $post_id;
                $var['title']       = get_the_title();
                $var['permalink']   = get_permalink();
                $var['seo_meta']    = ultimate_post()->get_excerpt($post_id, 1);
                $var['excerpt']     = strip_tags($content_data);
                $var['excerpt_full']= strip_tags(get_the_excerpt());
                $var['time']        = (int)get_the_date('U')*1000;
                $var['timeModified']= (int)get_the_modified_date('U')*1000;
                $var['post_time']   = human_time_diff(get_the_time('U'),current_time('U'));
                $var['view']        = get_post_meta(get_the_ID(),'__post_views_count', true);
                $var['comments']    = get_comments_number();
                $var['author_link'] = get_author_posts_url($user_id);
                $var['avatar_url']  = get_avatar_url($user_id);
                $var['display_name']= get_the_author_meta('display_name');
                $var['reading_time']= ceil(strlen($content_data)/1200);
                
                // image
                if (has_post_thumbnail()) {
                    $thumb_id = get_post_thumbnail_id($post_id);
                    $image_sizes = ultimate_post()->get_image_size();
                    $image_src = array();
                    foreach ($image_sizes as $key => $value) {
                        $image_src[$key] = wp_get_attachment_image_src($thumb_id, $key, false)[0];
                    }
                    $var['image'] = $image_src;
                }

                // tag
                $tag = get_the_terms($post_id, (isset($prams['tag'])?esc_attr($prams['tag']):'post_tag'));
                if (!empty($tag)) {
                    $v = array();
                    foreach ($tag as $val) {
                        $v[] = array('slug' => $val->slug, 'name' => $val->name, 'url' => get_term_link($val->term_id));
                    }
                    $var['tag'] = $v;
                }

                // Taxonomy
                $cat = get_the_terms($post_id, (isset($prams['taxonomy'])?esc_attr($prams['taxonomy']):'category'));
                if(!empty($cat)){
                    $v = array();
                    foreach ($cat as $val) {
                        $v[] = array('slug' => $val->slug, 'name' => $val->name, 'url' => get_term_link($val->term_id), 'color' => get_term_meta($val->term_id, 'ultp_category_color', true));
                    }
                    $var['category'] = $v;
                }
                $data[] = $var;
            }
            wp_reset_postdata();
        }

        return rest_ensure_response( $data );
    }


    /**
	 * Taxonomy Data Response of REST API
     * 
     * @since v.1.0.0
     * @param ARRAY | Parameter (ARRAY)
	 * @return ARRAY | Response Taxonomy List as Array
	 */
    public function ultp_route_common_data($prams) {
        if (!wp_verify_nonce(wp_unslash($_REQUEST['wpnonce']), 'ultp-nonce')){
            return rest_ensure_response([]);
        }
        
        $all_post_type = ultimate_post()->get_post_type();
        $data = array();
        foreach ($all_post_type as $post_type_slug => $post_type ) {
            $data_term = array();
            $taxonomies = get_object_taxonomies($post_type_slug);
            foreach ($taxonomies as $key => $taxonomy_slug) {
                $taxonomy_value = get_terms(array(
                    'taxonomy' => $taxonomy_slug,
                    'hide_empty' => false
                ));
                if (!is_wp_error($taxonomy_value)) {
                    $data_tax = array();
                    foreach ($taxonomy_value as $k => $taxonomy) {
                        $data_tax[urldecode_deep($taxonomy->slug)] = $taxonomy->name;
                    }
                    if (count($data_tax) > 0) {
                        $data_term[$taxonomy_slug] = $data_tax;
                    }
                }
            }
            $data[$post_type_slug] = $data_term;
        }
        // Global Customizer
        $global = get_option('postx_global', []);
        // Image Size
        $image_sizes = ultimate_post()->get_image_size();

        return rest_ensure_response(['taxonomy' => $data, 'global' => $global, 'image' => json_encode($image_sizes), 'posttype' => json_encode($all_post_type)]);
    }

    /**
	 * Specific Taxonomy Data Response of REST API
     * 
     * @since v.1.0.0
     * @param ARRAY | Parameter (ARRAY)
	 * @return ARRAY | Response Taxonomy List as Array
	 */
    public function ultp_route_taxonomy_info_data($prams) {
        if (!wp_verify_nonce(wp_unslash($_REQUEST['wpnonce']), 'ultp-nonce')) {
            return rest_ensure_response([]);
        }

        return rest_ensure_response( ultimate_post()->get_category_data(json_decode($prams['taxValue']), $prams['queryNumber'], $prams['taxType'], $prams['taxSlug']) );
    }
}