<?php
namespace ULTP;

defined('ABSPATH') || exit;

class Builder {
    public function __construct(){
        $this->builder_post_type_callback(); // Post Type Register
        add_filter('template_include', array($this, 'include_builder_files'), 100);
        add_action('add_meta_boxes', array($this, 'init_metabox_callback'));
        add_action('save_post', array($this, 'metabox_save_data'));
        add_action('save_post', array($this, 'metabox_save_video_data'));
        add_action('delete_post', array($this, 'delete_option_meta_action'));
        add_action('admin_head', array($this, 'custom_head_templates'));
        add_action('load-post-new.php', array($this, 'disable_new_post_templates'));
    }

    public function custom_head_templates() {
        if( 'ultp_builder' == get_current_screen()->post_type && (!defined('ULTP_PRO_VER')) ) {
            $post_count = wp_count_posts('ultp_builder');
            $post_count = $post_count->publish + $post_count->draft;
            if ( $post_count > 0 ) { ?>
                <span class="ultp-pro-needed" style="display: none;"></span>
                <?php ultimate_post()->pro_popup_html(); ?>
            <?php }
        }
    }

    public function disable_new_post_templates() {
        if ( get_current_screen()->post_type == 'ultp_builder' && (!defined('ULTP_PRO_VER')) ){
            $post_count = wp_count_posts('ultp_builder');
            $post_count = $post_count->publish + $post_count->draft;
            if ($post_count > 0) {
                wp_die( 'You are not allowed to do that! Please <a target="_blank" href="'.esc_url(ultimate_post()->get_premium_link()).'">Upgrade Pro.</a>' );
            }
        }
    }

    public function delete_option_meta_action( $post_id ) {
        if (get_post_type( $post_id ) != 'ultp_builder') {
            return;
        }

        $conditions = get_option('ultp_builder_conditions', array());
        if($conditions){
            $has_change = false;
            if(isset($conditions['archive'][$post_id])) {
                $has_change = true;
                unset($conditions['archive'][$post_id]);
            }
            if(isset($conditions['singular'][$post_id])) {
                $has_change = true;
                unset($conditions['singular'][$post_id]);
            }
            if($has_change) {
                update_option('ultp_builder_conditions', $conditions);
            }
        }
        delete_post_meta($post_id, '_ultp_active');
    }


    public function include_builder_files($template) {
        $includes = ultimate_post()->conditions('includes');
        return $includes ? $includes : $template;
    }

    function init_metabox_callback() {
        add_meta_box(
            'postx-builder-id',
            __('PostX Builder Settings', 'ultimate-post-pro'), 
            array($this, 'container_width_callback'), 
            'ultp_builder', 
            'side'
        );
        add_meta_box(
            'ultp-feature-video',
            __('Feature Video', 'ultimate-post-pro'),
            array($this, 'video_source_callback'),
            'post',
            'side'
        );
    }

    function video_source_callback($post) {
        wp_nonce_field('video_meta_box', 'video_meta_box_nonce');
        $video = get_post_meta($post->ID, '__builder_feature_video', true); ?>
        <p>
            <label><?php _e('PostX Feature Video', 'ultimate-post-pro'); ?></label>
            <input type="text" name="feature-video" value="<?php echo $video; ?>"/>
            <button class="ultp-add-media"><?php _e('+ Media', 'ultimate-post-pro'); ?></button>
        </p>
    <?php }


    function metabox_save_video_data($post_id) {
        if (!isset($_POST['video_meta_box_nonce'])) { return; }
        if (!wp_verify_nonce(wp_unslash($_POST['video_meta_box_nonce']), 'video_meta_box')) { return; }
        if (!isset( $_POST['feature-video'])) { return; }

        update_post_meta($post_id, '__builder_feature_video', sanitize_text_field($_POST['feature-video']));
    }

    
    function container_width_callback($post) {
        wp_nonce_field('container_meta_box', 'container_meta_box_nonce');
        $width = get_post_meta($post->ID, '__container_width', true);
        $sidebar = get_post_meta($post->ID, '__builder_sidebar', true);
        $widget = get_post_meta($post->ID, '__builder_widget_area', true);
        $p_type = get_post_meta($post->ID, '__ultp_builder_type', true);
        $p_type = $p_type ? $p_type : 'archive';

        $widget_area = wp_get_sidebars_widgets();
        if (isset($widget_area['wp_inactive_widgets'])) { unset($widget_area['wp_inactive_widgets']); }
        if (isset($widget_area['array_version'])) { unset($widget_area['array_version']); }
        ?>

        <input type="hidden" name="postx-type" value="<?php echo (isset($_GET['postx_type']) ? $_GET['postx_type'] : $p_type); ?>"/>
        <p>
            <label><?php _e('Container Width', 'ultimate-post-pro'); ?></label>
            <input type="number" style="width:100%" name="container-width" value="<?php echo ($width ? $width : 1140); ?>"/>
        </p>
        <p class="postx-meta-sidebar-position">
            <label><?php _e('Sidebar', 'ultimate-post-pro'); ?></label>
            <select name="builder-sidebar" style="width:88%">
                <option <?php selected( $sidebar, '' ); ?> value=""><?php _e('- None -', 'ultimate-post-pro'); ?></option>
                <option <?php selected( $sidebar, 'left' ); ?> value="left"><?php _e('Left Sidebar', 'ultimate-post-pro'); ?></option>
                <option <?php selected( $sidebar, 'right' ); ?> value="right"><?php _e('Right Sidebar', 'ultimate-post-pro'); ?></option>
            </select>
        </p>
        <p class="postx-meta-sidebar-widget">
            <label><?php _e('Select Sidebar(Widget Area)', 'ultimate-post-pro'); ?></label>
            <select name="builder-widget-area" style="width:88%">
                <option <?php selected( $sidebar, '' ); ?> value=""><?php _e('- None -', 'ultimate-post-pro'); ?></option>
                <?php foreach ($widget_area as $key => $val) { ?>
                    <option <?php selected( $widget, $key ); ?> value="<?php echo $key; ?>"><?php echo ucwords(str_replace('-', ' ', $key)); ?></option>
                <?php } ?>
            </select>
        </p>
    <?php }
    
    function metabox_save_data($post_id) {
        // For Feature Video
        if (isset($_POST['video_meta_box_nonce'])) {
            if (wp_verify_nonce(wp_unslash($_POST['video_meta_box_nonce']), 'video_meta_box')) {
                if (isset($_POST['feature-video'])) {
                    update_post_meta($post_id, '__builder_feature_video', sanitize_text_field($_POST['feature-video']));
                }
            }
        }
        
        // For Container and Sidebar Information
        if (!isset($_POST['container_meta_box_nonce'])){ return; }
        if (!wp_verify_nonce( wp_unslash($_POST['container_meta_box_nonce']), 'container_meta_box')) { return; }
        if (isset($_POST['container-width'])) {
            update_post_meta($post_id, '__container_width', sanitize_text_field($_POST['container-width']));
        }
        if (isset($_POST['builder-sidebar'])) {
            update_post_meta($post_id, '__builder_sidebar', sanitize_text_field($_POST['builder-sidebar']));
        }
        if (isset($_POST['builder-widget-area'])) {
            update_post_meta($post_id, '__builder_widget_area', sanitize_text_field($_POST['builder-widget-area']));
        }

        // Save Conditions Data
        if (get_post_type($post_id) == 'ultp_builder') {
            if (get_post_type($post_id) == 'ultp_builder') {
                $settings = get_option('ultp_builder_conditions', array());
                $p_type = isset($_POST['postx-type']) ? sanitize_text_field($_POST['postx-type']) : 'singular';
                switch ($p_type) {
                    case 'singular':
                        update_post_meta($post_id, '__ultp_builder_type', 'singular');
                        if (isset($settings['singular'])) {
                            if (!isset($settings['singular'][$post_id])) {
                                $settings['singular'][$post_id] = ['include/singular/post'];
                            }
                        } else {
                            $settings['singular'][$post_id] = ['include/singular/post'];
                        }
                        update_option('ultp_builder_conditions', $settings);
                        break;
                    case 'post_tag':
                    case 'date':
                    case 'search':
                    case 'author':
                    case 'archive':
                    case 'category':
                        update_post_meta($post_id, '__ultp_builder_type', 'archive');
                        $extra = $p_type != 'archive' ? '/'.$p_type : '';
                        if (isset($settings['archive'])) {
                            if (!isset($settings['archive'][$post_id])) {  
                                $settings['archive'][$post_id] = ['include/archive'.$extra];
                            }
                        } else {
                            $settings['archive'][$post_id] = ['include/archive'+$extra];
                        }
                        update_option('ultp_builder_conditions', $settings);
                        break;
                    default:
                        break;
                }
            }
        }
    }

    // Builder Post Type Register
    public function builder_post_type_callback() {
        $labels = array(
            'name'                => _x( 'Builder', 'Builder', 'ultimate-post-pro' ),
            'singular_name'       => _x( 'Builder', 'Builder', 'ultimate-post-pro' ),
            'menu_name'           => __( 'Builder', 'ultimate-post-pro' ),
            'parent_item_colon'   => __( 'Parent Builder', 'ultimate-post-pro' ),
            'all_items'           => __( 'Builder', 'ultimate-post-pro' ),
            'view_item'           => __( 'View Builder', 'ultimate-post-pro' ),
            'add_new_item'        => __( 'Add New', 'ultimate-post-pro' ),
            'add_new'             => __( 'Add New', 'ultimate-post-pro' ),
            'edit_item'           => __( 'Edit Builder', 'ultimate-post-pro' ),
            'update_item'         => __( 'Update Builder', 'ultimate-post-pro' ),
            'search_items'        => __( 'Search Builder', 'ultimate-post-pro' ),
            'not_found'           => __( 'No Builder Found', 'ultimate-post-pro' ),
            'not_found_in_trash'  => __( 'Not Builder found in Trash', 'ultimate-post-pro' ),
        );
        $args = array(
            'labels'              => $labels,
            'show_in_rest'        => true,
            'supports'            => array( 'title', 'editor', 'comments' ),
            'hierarchical'        => false,
            'public'              => false,
            'rewrite'             => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => true,
            'capability_type'     => 'page',
        );
        register_post_type( 'ultp_builder', $args );
    }
}