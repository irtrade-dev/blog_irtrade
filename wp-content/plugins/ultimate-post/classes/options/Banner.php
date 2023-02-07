<?php
/**
 * Notice Action.
 * 
 * @package ULTP\Notice
 * @since v.2.6.0
 */
namespace ULTP;

defined('ABSPATH') || exit;

/**
 * Functions class.
 */
class Banner{

    public function premium_feature_banner() { 
        if (!ultimate_post()->is_lc_active()) { ?>
            <div class="ultp-getstart-unlock">
                <div>
                    <?php printf(esc_html__('Unlock %s Premium %s features %s and all addons', 'ultimate-post'), '<span>', '</span>', '<br/>'); ?>
                </div>
                <a href="<?php echo esc_url(ultimate_post()->get_premium_link('', 'postx_dashboard_top_banner')); ?>" target="_blank">
                    <?php esc_html_e('Upgrade Pro', 'ultimate-post'); ?>
                </a>
            </div>
        <?php }
    }

    public function premium_feature_sidebar() { ?>
        <div class="ultp-aside-content ultp-card ultp-p25">
            <h3 class="ultp-sm-heading"><?php esc_html_e('Plugins Feature', 'ultimate-post'); ?></h3>
            <p><?php esc_html_e('PostX plugin helps to create beautiful post grid, post listing, dynamic post slider and post carousel within a few seconds.', 'ultimate-post'); ?></p>
            <ul class="ultp-sidebar-list">
                <li><?php esc_html_e('Dynamic Site Builder.', 'ultimate-post'); ?></li>
                <li><?php esc_html_e('Custom Taxonomy Support.', 'ultimate-post'); ?></li>
                <li><?php esc_html_e('Block Layout Options.', 'ultimate-post'); ?></li>
                <li><?php esc_html_e('Ready-made Start Packs.', 'ultimate-post'); ?></li>
                <li><?php esc_html_e('Advanced Quick Query Feature.', 'ultimate-post'); ?></li>
                <li><?php esc_html_e('Get Category Specific Color and Image Feature.', 'ultimate-post'); ?></li>
                <li><?php esc_html_e('Get Fast & Quick Support.', 'ultimate-post'); ?></li>
            </ul>
            <?php if(!ultimate_post()->is_lc_active()) { ?>
                <a href="<?php echo esc_url(ultimate_post()->get_premium_link('', 'postx_dashboard_sidebar')); ?>" target="_blank" class="ultp-btn ultp-btn-primary ultp-btn-pro"><?php esc_html_e('Upgrade Pro  ➤', 'ultimate-post'); ?></a>
            <?php } ?>
        </div>
    <?php }
    
}