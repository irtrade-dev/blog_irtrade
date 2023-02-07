<?php
namespace ULTP;

defined('ABSPATH') || exit;

class Option_Settings{
    /**
     * Sanitization callback
     */
    public function sanitize( $options ) {
        if ($options) {
            $settings = $this->get_option_settings_keys();
            foreach ($settings as $key) {
                $options[$key] = isset($options[$key]) ? $options[$key] : '';
            }
            $old_data = ultimate_post()->get_setting();
            $options = array_merge($old_data, $options);
        }
        return $options;
    }


    /**
     * Settings Fields Key Return
     */
    public function get_option_settings_keys() {
        $attr = array();
        $data = $this->get_option_settings();
        if (!empty($data)) {
            foreach ($data as $key => $inner_data) {
                if (isset($inner_data['attr'])) {
                    foreach ($inner_data['attr'] as $k => $val) {
                        $attr[] = $k;
                    }
                }
            }
        }
        return $attr;
    }


    /**
     * Settings Field Return
     */
    public function get_option_settings() {
        return apply_filters('ultp_settings', array(
            'general' => array(
                'label' => __('General Settings', 'ultimate-post'),
                'attr' => array(
                    'css_save_as' => array(
                        'type' => 'select',
                        'label' => __('CSS Add Via', 'ultimate-post'),
                        'options' => array(
                            'wp_head'   => __( 'Header - (Internal)','ultimate-post' ),
                            'filesystem' => __( 'File System - (External)','ultimate-post' ),
                        ),
                        'default' => 'wp_head',
                        'desc' => __('Select where you want to save CSS.', 'ultimate-post')
                    ),
                    'preloader_style' => array(
                        'type' => 'select',
                        'label' => __('Preloader Style', 'ultimate-post'),
                        'options' => array(
                            'style1' => __( 'Preloader Style 1','ultimate-post' ),
                            'style2' => __( 'Preloader Style 2','ultimate-post' ),
                        ),
                        'default' => 'style1',
                        'desc' => __('Select Preloader Style.', 'ultimate-post')
                    ),
                    'container_width' => array(
                        'type' => 'number',
                        'label' => __('Container Width', 'ultimate-post'),
                        'default' => '1140',
                        'desc' => __('Change Container Width of the Page Template(PostX Template).', 'ultimate-post')
                    ),
                    'hide_import_btn' => array(
                        'type' => 'switch',
                        'label' => __('Hide Import Button', 'ultimate-post'),
                        'default' => '',
                        'desc' => __('Hide Import Layout Button from the Gutenberg Editor.', 'ultimate-post')
                    ),
                    'disable_image_size' => array(
                        'type' => 'switch',
                        'label' => __('Disable Image Size', 'ultimate-post'),
                        'default' => '',
                        'desc' => __('Disable Image Size of the Plugins.', 'ultimate-post')
                    ),
                    'disable_view_cookies' => array(
                        'type' => 'switch',
                        'label' => __('Disable All Cookies', 'ultimate-post'),
                        'default' => '',
                        'desc' => __('Disable All Frontend Cookies (Cookies Used for Post View Count).', 'ultimate-post')
                    ),
                    'disable_google_font' => array(
                        'type' => 'switchButton',
                        'label' => __('Disable All Google Fonts', 'ultimate-post'),
                        'default' => '',
                        'desc' => __('Disable All Google Fonts From Frontend and Backend PostX Blocks.', 'ultimate-post')
                    ),
                )
            )
        ));
    }


    /**
     * Settings page output
     */
    public function get_settings_data( $data ) {
        $option_data = ultimate_post()->get_setting();
        foreach ($data as $key => $value) {
            if ($value['type'] == 'hidden') {
                echo '<input type="hidden" name="'.esc_attr($key).'" value="'.esc_attr($value['value']).'"/>';
            } else {
                if ($value['type'] == 'heading') {
                    echo '<h2 class="ultp-settings-heading">'.esc_html($value['label']).'</h2>';
                    if ( isset($value['desc']) ) {
                        echo '<div class="ultp-settings-subheading">'.esc_html($value['desc']).'</div>';
                    }
                }
                if($value['type'] != 'heading' && $value['type'] != 'hidden'){
                    echo '<div class="ultp-settings-wrap">';
                        if (isset($value['label'])) {
                            echo '<strong>'.esc_html($value['label']).'</strong>';
                        }
                        echo '<div class="ultp-settings-field-wrap">';
                            switch ($value['type']) {
                                case 'select':
                                    echo '<div class="ultp-settings-field">';
                                        $val = isset($option_data[$key]) ? $option_data[$key] : (isset($value['default']) ? $value['default'] : '');
                                        echo '<select name="'.esc_attr($key).'">';
                                            foreach ( $value['options'] as $id => $label ) {
                                                echo '<option value="'.esc_attr($id).'" '.( $val == $id ? ' selected="selected"':'').'>';
                                                echo esc_html( $label );
                                                echo '</option>';
                                            }
                                            echo '</select>';
                                        echo '<p class="description">'.esc_html($value['desc']).'</p>';
                                    echo '</div>';
                                    break;

                                case 'color':
                                    echo '<div class="ultp-settings-field">';
                                        $val = isset($option_data[$key]) ? $option_data[$key] : (isset($value['default']) ? $value['default'] : '');
                                        echo '<input value="'.esc_attr($val).'" class="ultp-color-picker" />';
                                        echo '<span class="ultp-settings-input-field"><input name="'.esc_attr($key).'" class="ultp-color-code" value="'.esc_attr($val).'"/></span>';
                                        echo '<p class="description">'.esc_html($value['desc']).'</p>';
                                    echo '</div>';
                                    break;

                                case 'number':
                                    echo '<div class="ultp-settings-field ultp-settings-input-field">';
                                        $val = isset($option_data[$key]) ? $option_data[$key] : (isset($value['default']) ? $value['default'] : '');
                                        echo '<input type="number" name="'.esc_attr($key).'" value="'.esc_attr($val).'"/>';
                                        echo '<p class="description">'.esc_html($value['desc']).'</p>';
                                    echo '</div>';
                                    break;

                                case 'switch':
                                case 'switchButton':
                                    echo '<div class="ultp-settings-field ultp-settings-field-inline">';
                                        $val = isset($option_data[$key]) ? $option_data[$key] : (isset($value['default']) ? $value['default'] : '');
                                        echo '<input type="checkbox" value="yes" name="'.esc_attr($key).'" '.($val == 'yes' ? 'checked' : '').' />';
                                        echo '<p class="description">'.esc_html($value['desc']).'</p>';
                                        if ($value['type'] == 'switchButton') {
                                            echo '<div><span id="postx-regenerate-css" class="ultp-btn ultp-btn-warning '.($val=='yes'?'active':'').'"><span class="dashicons dashicons-admin-generic"></span><span class="ultp-text">';
                                            echo __('Re-Generate Font Files', 'ultimate-post');
                                            echo '</span></span></div>';
                                        }
                                    echo '</div>';
                                    break;

                                case 'multiselect':
                                    echo '<div class="ultp-settings-field">';
                                    $saved_val = isset($option_data[$key]) ? $option_data[$key] : (isset($value['default']) ? $value['default'] : []);
                                        echo '<select name="'.esc_attr($key).'[]" multiple>';
                                        if (!empty($value['options'])) {
                                            foreach ($value['options'] as $val) {
                                                if (!empty($saved_val) && is_array($saved_val)) {
                                                    echo '<option value="'.esc_attr($val).'" '.(in_array( $val , $saved_val ) ? 'selected' : '' ).'>'.esc_html($val).'</option>';
                                                } else {
                                                    echo '<option value="'.esc_attr($val).'">'.esc_html($val).'</option>';
                                                }
                                            }
                                        }
                                        echo ' </select>';
                                    echo '</div>';
                                    break;

                                default:
                                    # code...
                                    break;
                            };
                        echo '</div>';
                    echo '</div>';
                }
            }
        }
    }

    /**
     * Settings page output
     */
    public function create_admin_page() { 
        $data = self::get_option_settings();?>
        <div class="ultp-dashboard-container">    
            <div class="ultp-dashboard-body ultp-card ultp-getstart-body">
                <div class="ultp-container-grid">
                    <form method="post" action="#">
                        <div class="ultp-settings ultp-submit-button ultp-card ultp-p40">
                            <?php 
                                if (isset($data['general']['attr'])) {
                                    $this->get_settings_data( $data['general']['attr'] );
                                }
                            ?> 
                            <div class="ultp-data-message"></div>
                            <button class="button button-primary button-large"><?php esc_html_e('Save Settings', 'ultimate-post'); ?></button>
                        </div>
                    </form>
                    <div>
                        <?php
                            require_once ULTP_PATH.'classes/options/Banner.php';
                            $obj = new \ULTP\Banner();
                            $obj->premium_feature_sidebar();
                        ?>
                        <div class="ultp-aside-content ultp-card ultp-p25 ultp-aside-content-rate">
                            <div class="ultp-aside-heading ultp-sm-heading">
                            <span class="dashicons dashicons-star-filled"></span>
                                <?php esc_html_e('Show your love', 'ultimate-post'); ?>
                            </div>
                            <p class="ultp-support-desc">
                                <?php esc_html_e('Enjoying PostX? Give us a review to support our endless work.', 'ultimate-post'); ?>
                            </p>
                            <a href="https://wordpress.org/plugins/ultimate-post/#reviews" class="ultp-btn ultp-btn-warning" target="_blank">
                                <?php esc_html_e('Rate it now', 'ultimate-post'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php }

}