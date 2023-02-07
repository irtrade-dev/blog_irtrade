<?php
namespace ULTP;

defined('ABSPATH') || exit;

class Option_Blocks{
    public function __construct() {
        $this->create_admin_page();
    }

    public static function create_admin_page() { ?>
        <div class="ultp-dashboard-container ultp-block-option">
            
            <div class="ultp-dashboard-body">
                <?php
                $option_data = ultimate_post()->get_setting();
                $blocks_settings = ultimate_post()->get_blocks_settings();

                foreach ($blocks_settings as $blocks) { ?>
                    <h4 class="ultp-sm-heading ultp-mb25"><?php echo esc_html($blocks['label']); ?></h4>
                    <div class="ultp-block-grid-content">
                        <?php foreach ($blocks['attr'] as $key => $val) { ?>
                                <div class="ultp-card ultp-p15">
                                    <div class="ultp-control-meta">
                                        <img src="<?php echo esc_url($val['icon']); ?>" alt="overview content">
                                        <div><?php echo esc_html($val['label']); ?></div>
                                    </div>
                                    <div class="ultp-control-option">
                                        <?php 
                                        if (isset($val['docs'])) { ?>
                                            <a href="<?php echo esc_url($val['docs']); ?>"  target="_blank" class="ultp-option-tooltip">
                                                <span><?php esc_html_e('Documentation', 'ultimate-post'); ?></span>
                                                <div class="dashicons dashicons-book"></div>
                                            </a>
                                        <?php } ?>
                                        <?php 
                                        if (isset($val['live'])) { ?>
                                            <a href="<?php echo esc_url($val['live']); ?>" target="_blank" class="ultp-option-tooltip">
                                                <span><?php esc_html_e('Live View', 'ultimate-post'); ?></span>
                                                <div class="dashicons dashicons-visibility"></div>
                                            </a>
                                        <?php } ?>
                                        <?php
                                            $output = $val['default'] ? 'checked' : '';
                                            if (isset($option_data[$key])) {
                                                $output = $option_data[$key] == 'yes' ? 'checked' : '';
                                            }
                                        ?>
                                        <input type="checkbox" data-type="blocks" class="ultp-addons-enable" id="<?php echo esc_attr($key); ?>" <?php echo esc_attr($output); ?>/><label class="ultp-control__label" for="<?php echo esc_attr($key); ?>">Toggle</label>
                                    </div>
                                </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                    
            </div>
        </div>
        <?php
    }
}