<?php
namespace ULTP;

defined('ABSPATH') || exit;

class Option_Addons{
    public function __construct() {
        $this->create_admin_page();
    }


    /**
     * Settings page output
     */
    public function create_admin_page() { ?>
        <div class="ultp-dashboard-container">
            
             <!-- addons content -->
                <div class="ultp-dashboard-body ultp-addons-content">
                    
                    <!-- Pro Popup Container -->
                    <?php ultimate_post()->pro_popup_html(); ?>

                    <h3 class="ultp-md-heading ultp-mb25">
                        <?php _e('All Addons', 'ultimate-post'); ?>
                    </h3>
                    
                    <div class="ultp-addons-grid">
                        <?php
                            $settings = apply_filters('ultp_settings', []);
                            $option_value = ultimate_post()->get_setting();
                            $addons_data = ultimate_post()->all_addons();

                            $keys =[];
                            foreach ($addons_data as $key => $val) {
                                array_push($keys,  !empty($val['position']) ? $val['position'] : 100 );
                            }
                            array_multisort($keys, SORT_ASC, $addons_data);

                            foreach ($addons_data as $key => $val) {
                                $require_plugin = '';
                                if (isset($val['required'])) {
                                    $active_plugins = get_option( 'active_plugins', array() );
                                    if (is_multisite()) {
                                        $active_plugins = array_merge($active_plugins, array_keys(get_site_option( 'active_sitewide_plugins', array() )));
                                    }
                                    if ( !in_array($val['required']['slug'], apply_filters('active_plugins', $active_plugins)) ) {
                                        $require_plugin = $val['required']['name'];
                                    }
                                }
                                ?>
                                <div class="ultp-content-addon ultp-card ultp-p25">
                                    <div class="ultp-content-addon-container">
                                        <div class="ultp-content-meta">
                                            <img src="<?php echo esc_url($val['img']); ?>" alt="<?php echo esc_attr($val['name']); ?>">
                                        </div>
                                        <div class="ultp-addons-option-wrapper">
                                            <div class="ultp-addons-option-control">
                                                <h4 class="ultp-xs-heading"><?php echo esc_html($val['name']); ?></h4>
                                                <div class="ultp-control-option">
                                                    <?php 
                                                    if (isset($val['docs'])) { ?>
                                                        <a href="<?php echo esc_url($val['docs']); ?>" class="ultp-option-tooltip" target="_blank">
                                                            <span><?php esc_html_e('Documentation', 'ultimate-post'); ?></span>
                                                            <div class="dashicons dashicons-book"></div>
                                                        </a>
                                                    <?php } ?>
                                                    
                                                    <?php
                                                    if (isset($val['live'])) { ?>
                                                        <a href="<?php echo esc_url($val['live']); ?>" class="ultp-option-tooltip" target="_blank">
                                                            <span><?php esc_html_e('Live View', 'ultimate-post'); ?></span>
                                                            <div class="dashicons dashicons-visibility"></div>
                                                        </a>
                                                    <?php } ?>

                                                    <input type="checkbox" data-type="<?php echo esc_attr($key); ?>" class="ultp-addons-enable <?php echo (($val['is_pro'] && (!defined('ULTP_PRO_VER'))) ? 'disabled' : ''); ?>" id="<?php echo esc_attr($key); ?>" <?php echo ( isset($option_value[$key]) && $option_value[$key] == 'true' ? $val['is_pro'] && (!defined('ULTP_PRO_VER')) ? '' : 'checked' : '' ); ?>/>
                                                    <label for="<?php echo esc_attr($key); ?>" class="ultp-control__label">
                                                        <?php if ($val['is_pro'] && (!defined('ULTP_PRO_VER'))) { ?>
                                                            <span class="dashicons dashicons-lock"></span>
                                                        <?php } ?>
                                                    </label>

                                                    <?php 
                                                    if (isset($settings[$key])) { ?>
                                                        <span class="ultp-block-settings dashicons dashicons-admin-generic"></span>
                                                        <div class="ultp-popup-container blocks-settings">
                                                            <div class="ultp-unlock-popup">
                                                                <form action="">
                                                                    <?php
                                                                        require_once ULTP_PATH.'classes/options/Settings.php';
                                                                        $obj = new \ULTP\Option_Settings();
                                                                        $obj->get_settings_data($settings[$key]['attr']);
                                                                    ?>
                                                                    <div class="ultp-data-message"></div>
                                                                    <div>
                                                                        <button class="ultp-addons-setting-save"><?php esc_html_e('Save Settings', 'ultimate-post'); ?></button>
                                                                    </div>
                                                                </form>
                                                                <button class="ultp-popup-close"></button>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="ultp-addon-desc ultp-sm-text"><?php echo esc_html($val['desc']); ?></div>
                                        </div>
                                    </div>    
                                    <?php if ($require_plugin) { ?>
                                        <div class="ultp-plugin-required">
                                            <?php esc_html_e('This addon required this plugin:', 'ultimate-post'); ?> <b><?php echo wp_kses($require_plugin, 'post'); ?></b>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                    </div>
            </div>
        </div>
    <?php }
}