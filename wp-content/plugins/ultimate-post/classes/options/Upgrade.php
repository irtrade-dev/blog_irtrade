<?php

namespace ULTP;

/**
 * The Upgrade Class
 */
class Upgrade {
    public function __construct() {
        add_submenu_page( 
            'ultp-settings', 
            __('Upgrade','ultimate-post'), 
            '<span class="ultp-dashboard-upgrade"><span class="dashicons dashicons-update"></span> '.__('Upgrade', 'ultimate-post').'</span>', 
            'manage_options', 
            'ultp-upgrade', 
            array( self::class, 'create_admin_page' ), 55 
        );
    }
    /**
     * Upgrade Page output
     */
    public static function create_admin_page() { ?>
        <style>
            /* upgrade overview start */
            .ultp-upgrade-overview-wrapper {
                padding: 10px;
            }
            .ultp-upgrade-overview{
                display: grid;
                grid-template-columns: 0.9fr 1fr;
                box-shadow: 0 5px 15px 0 rgba(0, 0, 0, 0.15);
                margin-top: 0;
                overflow: hidden;
                border: none;
            }
            .ultp-upgrade-overview-content{
                padding: 55px 40px 60px;
            }
            .ultp-upgrade-overview-content h3{
                font-weight: normal;
                margin: 0;
                margin-bottom: 25px;
                font-size: 32px;
                line-height: 38px;
                color: #000;
            }
            .ultp-upgrade-overview-content h4 {
                font-weight: normal;
                margin: 0;
                font-size: 20px;
                line-height: 28px;
            }
            .ultp-upgrade-overview-content h4 a {
                color: #037fff;
                text-decoration: none;
            }
            .ultp-upgrade-overview-content h4 strong {
                color: #000;
            }
            .ultp-upgrade-overview-content h3 span{
                color: #037fff;
            }
            .ultp-upgrade-overview-image{
                line-height: 0;
            }
            .ultp-upgrade-overview-image img{
                max-width: 100%;
                object-fit: cover;
                height: 100%;
            }
            /* upgrade overview start */

            /* upgrade compare table */
            .ultp-upgrade-compare-table{
                margin-top: 40px;
            }
            .ultp-upgrade-compare-table table{
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
            }
            .ultp-upgrade-compare-table td{
                border: 1px solid #DFDFDF;
            }
            .ultp-upgrade-compare-table table tr th{
                text-align: center;
                border-right: 1px solid #DFDFDF;
                padding: 40px;
                font-size: 24px;
                font-weight: 600;
                color: #000;
            }
            .ultp-upgrade-compare-table table tr th:nth-child(2) {
                background-color: #fff;
                border-top: 1px solid #DFDFDF;
            }
            .ultp-upgrade-compare-table table tr th:nth-child(3) {
                background-color: #fff;
                border-top: 1px solid #DFDFDF;
            }

            .ultp-upgrade-compare-table table td:first-child {
                font-weight: 600;
                padding: 25px;
                text-align: left;
                color: #000;
            }
            .ultp-upgrade-compare-table table td:nth-child(2) {
                text-align: center;
                color: #e51f1f;
                font-size: 36px;
            }
            .ultp-upgrade-compare-table table td:nth-child(3) {
                text-align: center;
                color: #18982c;
            }
            .ultp-upgrade-compare-table table tr .table-icon{
                font-size: 36px;
                padding-bottom: 13px;
            }

            .ultp-upgrade-compare-table table tr:nth-child(even) {
                background-color:#f7f7f7;
            }
            .ultp-upgrade-compare-table table tr:nth-child(odd) {
                background-color:#FFFFFF;
            }

            .ultp-upgrade-compare-table table tr td a{
                border-radius: 4px;
                display: inline-block;
                font-size: 18px;
                margin: 20px;
            }

            .ultp-admin-text{
                max-width: 680px;
                margin: 0 auto;
                font-size: 16px;
                line-height: 1.5;
                color: #292929;
            }
            .ultp-admin-text a {
                color: #037fff;
            }

            /** promo section */
            .ultp-promo-item{
                padding: 35px 24px;
                border-radius: 4px;
                box-shadow: 0 5px 15px 0 rgba(0, 0, 0, 0.15);
                border: none;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: 300ms;
            }
            .ultp-promo-item:hover {
                box-shadow: 0 15px 30px 0 rgba(0, 0, 0, 0.25);
            }
            .ultp-promo-item h4 {
                line-height: 1.4;
                font-size: 18px;
                text-align: center;
                font-weight: 600;
                color: #000;
            }
            /** promo section */

            /* facebook review section */
            .ultp-facebook-reivew{
                margin-top: 36px;
                padding: 20px;
                border: none;
                box-shadow: 0 5px 15px 0 rgba(0, 0, 0, 0.15);
            }
            .ultp-facebook-reivew img{
                max-width: 100%;
            }

            /* whats people say section end */
            .ultp-testimonial-items{
                margin-top: 49px;
            }
            .ultp-testimonial-item div {
                font-style: italic;
            }


            /*faq section*/
            .ultp-faq-items{
                display: grid;
                grid-template-columns: 1fr 1fr;
                grid-gap: 40px;
                max-width: 850px;
                margin: 50px auto 0;
            }

            .ultp-faq-heading{
                font-size: 20px;
                line-height: 1.3;
                margin-top: 0;
                margin-bottom: 12px;
            }
            .ultp-faq-description{
                margin: 0;
                font-size: 16px;
                line-height: 1.6;
                text-align: left;
            }
            .ultp-faq-description a {
                color: #037fff;
            }

            /* ---- Helps --- */
            .ultp-upgrade-help-items{
                display: grid;
                grid-template-columns: 1fr 1fr;
                grid-gap: 30px;
                max-width: 1000px;
                margin: 20px auto 0;
            }
            .ultp-upgrade-help-item{
                padding: 30px;
            }
            .ultp-upgrade-help-item img{
                max-width: 60px;
            }
            .ultp-upgrade-help-item h4 {
                margin: 20px 0 15px;
                font-size: 24px;
                color: #000;
            }
            .ultp-upgrade-help-item p {
                font-size: 16px;
                margin-bottom: 25px;
                margin-top: 0;
                line-height: 1.5;
            }
            .ultp-upgrade-help-item a {
                text-decoration: none;
                color: #037fff;
            }
            .ultp-upgrade-help-item a.ultp-btn{
                display: inline-block;
                padding: 12px 25px;
            }
            .ultp-margin-top20 {
                margin-top: 20px;
            }

            /* ----Responsive--- */
            @media (max-width: 1000px) {
                .ultp-upgrade-overview, .ultp-testimonial-items, .ultp-upgrade-help-items, .ultp-faq-items {
                    grid-template-columns: 1fr;
                }
                .ultp-upgrade-overview img{
                    max-width: 100%;
                }
                .ultp-facebook-reivew img{
                    max-width: 100%;
                }
                .ultp-admin-text{
                    max-width: 100%;
                    padding: 0;
                }
                .ultp-upgrade-compare-table table tr td a{
                    font-size: 14px;
                }
            }
        </style>

        <div class="ultp-option-body ultp-upgrade-overview-wrapper">
            <div class="ultp-content-wrap">
                <div class="ultp-upgrade-overview ultp-admin-card">
                    <div class="ultp-upgrade-overview-content">
                        <h3><?php _e( 'The Complete <strong><span>Solutions</span></strong> for your <strong>News, Magazine, and Blog</strong> website.', 'ultimate-post'); ?></h3>
                        <h4><?php _e( 'Purchase <a href="https://www.wpxpo.com/postx/pricing/">PostX Pro</a> and launch your website within few clicks.', 'ultimate-post'); ?></h4>
                    </div>
                    <div class="ultp-upgrade-overview-image">
                        <img loading="lazy" src="<?php echo ULTP_URL.'assets/img/admin/upgrade-overview-image.jpg'; ?>" alt="Filter Category">
                    </div>
                </div>
            </div>

            <div class="ultp-content-wrap ultp-margin-top20">
                <div class="ultp-text-center">
                    <h2 class="ultp-lg-heading"><?php _e('Why Do You Need PostX Pro?', 'ultimate-post'); ?></h2>
                    <p class="ultp-admin-text"><?php _e('PostX Pro removes all the barriers hindering your creativity. With all the unlocked features and customization options, you can be up and running with a fully functional website and offer a customized experience well-suited for you and your users.', 'ultimate-post'); ?></p>
                </div>
            </div>

            <div class="ultp-content-wrap">
                <div class="ultp-promo-items">
                    <div class="ultp-promo-item ultp-admin-card">
                        <h4 class="ultp-promo-single-line"><?php _e('10+ Addons Included', 'ultimate-post'); ?></h4>
                    </div>
                    <div class="ultp-promo-item ultp-admin-card">
                        <h4><?php _e('Advanced Query Builder', 'ultimate-post'); ?></h4>
                    </div>
                    <div class="ultp-promo-item ultp-admin-card">
                        <h4><?php _e('200+  Ready Design Libary', 'ultimate-post'); ?></h4>
                    </div>
                    <div class="ultp-promo-item ultp-admin-card">
                        <h4><?php _e('Exclusive Dynamic Site Builder', 'ultimate-post'); ?></h4>
                    </div>
                    <div class="ultp-promo-item ultp-admin-card">
                        <h4><?php _e('Unlimited Templates', 'ultimate-post'); ?></h4>
                    </div>
                </div>
            </div>

            <div class="ultp-content-wrap ultp-margin-top20">
                <div class="ultp-text-center">
                    <h2 class="ultp-lg-heading"><?php _e('⋆ ⋆ ⋆ 14-Day Moneyback Guarantee ⋆ ⋆ ⋆', 'ultimate-post'); ?></h2>
                    <p class="ultp-admin-text"><?php _e('Your satisfaction is our priority. For this reason, if you’re not happy with our product, we have a <strong> 14 days No Questions Asked </strong> refund policy in place. If we can’t deliver a proper experience, you’ll receive a full refund. <a href="https://www.wpxpo.com/refund-policy/">Learn More.</a>'); ?> </p>
                </div>

                <div class="ultp-upgrade-compare-table" style="overflow-x:auto;">
                    <table>
                        <tbody>
                            <tr style="background:none;">
                                <th colspan="2"></th>
                                <th colspan="2"><?php _e('Free', 'ultimate-post');?></th>
                                <th colspan="2"><?php _e('Premium', 'ultimate-post');?></th>
                            </tr>

                            <tr>
                                <td colspan="2"><?php _e('Advanced Starter Packs', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('Advanced Ready-block designs', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('Advanced Layout designs', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('Advanced Quick Query feature', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('Category Specific Color', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('Category Specific background-color', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('PostX Advanced Dynamic Site Builder Features', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('Unlimited Saved Templates', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('Yoast SEO Meta Addon', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('All-in-one SEO Meta Addon', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('RankMath SEO Meta Addon', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('SEOPress Meta Addon', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('Squirrly SEO Meta Addon', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('Content Animations', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('Content Background Color', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('Table of Contents advanced layouts', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('Table of Contents Advanced Block Designs', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('Table of Contents Hover Style', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('Sticky Table of Contents', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('Advanced Responsiveness Feature ', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php _e('Priority Support', 'ultimate-post');?></td>
                                <td colspan="2"><i class="dashicons dashicons-no-alt table-icon"></i></td>
                                <td colspan="2"><i class="dashicons dashicons-yes table-icon"></i></td>
                            </tr>

                            <tr style="background-color:#FFFFFF">
                                <td colspan="2" style="border-right:none;"></td>
                                <td colspan="2" style="border-right:none; border-left:none;"></td>
                                <td colspan="2" style="border-left: none"><a class="ultp-btn ultp-btn-primary" href="https://www.wpxpo.com/postx/pricing/"><?php _e('Upgrade Now', 'ultimate-post'); ?></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--/compare-->

            <div class="ultp-content-wrap ultp-margin-top20">

                <div class="ultp-text-center">
                    <h2 class="ultp-lg-heading"><?php _e('Encouragement from Our Users', 'ultimate-post'); ?></h2>
                    <p class="ultp-admin-text"><?php _e('Here’s what the Facebook community has to say about the PostX Gutenberg Blocks Plugin. We’re pumped because of the positive feedback we’ve received so far!', 'ultimate-post'); ?></p>
               </div>

                <div class="ultp-facebook-reivew ultp-admin-card">
                    <img src="<?php echo ULTP_URL.'assets/img/admin/facebook-testimonials.jpg'; ?>" alt="Testimonials">
                </div>
            </div>

            <div class="ultp-content-wrap ultp-margin-top20">
                <div class="ultp-text-center">
                    <h2 class="ultp-lg-heading"><?php _e('Words from the WordPress Community', 'ultimate-post'); ?></h2>
                    <p class="ultp-admin-text"><?php _e('With over 10,000+ active downloads and 70+ positive ratings, we’re growing at a rapid pace. It wouldn’t have been possible without a very supportive community admiring PostX. Here’s what they have to say:', 'ultimate-post'); ?></p>
                </div>
                <div class="ultp-testimonial-items">
                    <div class="ultp-testimonial-item">
                    <div class="ultp-admin-card">
                        <div><?php _e('One of the best Gutenberg post Grid. Really nice pre-made designs, Easy to access library, Easy to use, lots of settings enable you to customize your layouts as you wish. Professional designs and layouts.', 'ultimate-post'); ?></div>
                        <h3><a href="https://wordpress.org/support/topic/best-post-grid/" target="_blank"><?php _e('@nima78600 – Best post Grid', 'ultimate-post'); ?></a></h3>
                        <div class="ultp-reviews-rating"><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></div>
                    </div>
                    <div class="ultp-admin-card">
                        <div><?php _e('I’ve been hunting for a plugin that formats post and page links like this via blocks for ages. Super happy. On top of that, I found an issue that was resolved in a few minutes over live chat. Great after-sales support to boot.', 'ultimate-post'); ?></div>
                        <h3><a href="https://wordpress.org/support/topic/been-on-the-lookout-for-something-like-this-for-ages/" target="_blank"><?php _e('@rockyshark – Been on the lookout for something like this for ages', 'ultimate-post'); ?></a></h3>
                        <div class="ultp-reviews-rating"><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></div>
                    </div>
                </div>
                    <div class="ultp-testimonial-item">
                    <div class="ultp-admin-card">
                        <div><?php _e('I use this plugin on 14 sites and it is great at extending the Gutenberg Block Editor. Adds great flexibility to any site/theme. Good Support available!!', 'ultimate-post'); ?></div>
                        <h3><a href="https://wordpress.org/support/topic/great-plugin-great-support-1442/" target="_blank"><?php _e('@markvanjaarsveld – Great Plugin – Great Support', 'ultimate-post'); ?></a></h3>
                        <div class="ultp-reviews-rating"><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></div>
                    </div>
                    <div class="ultp-admin-card">
                        <div><?php _e('I like this plugin: it is simple, give a great experience to the user of your website and easy to use. I had some technical problems, which were resolved in only couple of hours! Great…', 'ultimate-post'); ?></div>
                        <h3><a href="https://wordpress.org/support/topic/great-plugin-and-very-good-service/" target="_blank"><?php _e('@tiddeman83 – Great plugin and very good service', 'ultimate-post'); ?></a></h3>
                        <div class="ultp-reviews-rating"><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></div>
                    </div>
                </div>
                </div>
            </div>

            <div class="ultp-content-wrap">
                <div class="ultp-text-center">
                    <h2 class="ultp-lg-heading"><?php _e('Frequently Asked Questions', 'ultimate-post'); ?></h2>
                    <p class="ultp-admin-text"><?php _e('Get proper answers to some common questions popping up in your mind. We’re always looking to make our products better to offer the best possible experience. For now, let’s get you started with a few simple answers.', 'ultimate-post'); ?></p>
                </div>
                <div class="ultp-faq-items">
                    <div class="ultp-faq-item">
                        <h3 class="ultp-faq-heading"><?php _e('Where do I get a license from?', 'ultimate-post'); ?></h3>
                        <p class="ultp-faq-description"> <?php _e( 'You’ll need to have an active <a href="https://www.wpxpo.com/">WPXPO account</a> to buy the PostX Pro License. Once you log in to your account, you can access your licenses from there.', 'ultimate-post' );?> </p>
                    </div>
                    <div class="ultp-faq-item">
                        <h3 class="ultp-faq-heading"><?php _e('How can I upgrade my license? ', 'ultimate-post'); ?></h3>
                        <p class="ultp-faq-description"> <?php _e( ' You can upgrade your product license using your WPXPO account. You can use the <a href="https://www.wpxpo.com/contact">contact support form</a> to get quick help from our team.', 'ultimate-post' );?> </p>
                    </div>
                    <div class="ultp-faq-item">
                        <h3 class="ultp-faq-heading"><?php _e(' If I purchase a single license, can I use it on a development site? ', 'ultimate-post'); ?></h3>
                        <p class="ultp-faq-description"> <?php _e( 'If you activate the product on a development site, then you’ll need to deactivate the PostX license on the development site and reactivate the license on your live website.', 'ultimate-post' );?> </p>
                    </div>
                    <div class="ultp-faq-item">
                        <h3 class="ultp-faq-heading"><?php _e('How do I manage licenses for multiple websites? ', 'ultimate-post'); ?></h3>
                        <p class="ultp-faq-description"> <?php _e( 'Once you log in to your WPXPO account, you will find a ‘Manage Licenses’ section from where you manage your Pro licenses for different sites. ', 'ultimate-post' );?> </p>
                    </div>
                    <div class="ultp-faq-item">
                        <h3 class="ultp-faq-heading"><?php _e('How do I contact support? ', 'ultimate-post'); ?></h3>
                        <p class="ultp-faq-description"> <?php _e( 'All of the support issues are resolved via our <a href="https://www.wpxpo.com/contact">contact support form</a>. Use it to get rapid support.', 'ultimate-post' );?> </p>
                    </div>
                    <div class="ultp-faq-item">
                        <h3 class="ultp-faq-heading"><?php _e('How do you accept payment?', 'ultimate-post'); ?></h3>
                        <p class="ultp-faq-description"> <?php _e( ' We accept payment via the <a href="https://paddle.com/support/which-payment-methods-do-you-support">paddle platform.</a> We do accept payment via PayPal as well.', 'ultimate-post' );?> </p>
                    </div>
                </div>
            </div>

            <div class="ultp-content-wrap">
                <div class="ultp-upgrade-help-items">
                    <div class="ultp-upgrade-help-item ultp-admin-card">
                        <img src="<?php echo ULTP_URL.'assets/img/admin/docs-icon.svg'; ?>" alt="Documentation">
                        <h4><?php _e('Documentation', 'ultimate-post'); ?></h4>
                        <p><?php _e('Visit the <a href="https://docs.wpxpo.com/docs/postx/" target="_blank">PostX documentation</a> to get quick answers on topics. We have extensive documentation to help you create your dream website by navigating through the different settings and controls.', 'ultimate-post'); ?></p>
                        <a class="ultp-btn ultp-btn-transparent" href="https://docs.wpxpo.com/docs/postx/" target="_blank"><?php _e('View Details', 'ultimate-post'); ?></a>

                    </div>
                    <div class="ultp-upgrade-help-item ultp-admin-card">
                        <img src="<?php echo ULTP_URL.'assets/img/admin/support-icon.svg'; ?>" alt="Support">
                        <h4><?php _e('Quick Support', 'ultimate-post'); ?></h4>
                        <p><?php _e('Send us your queries via the <a href="https://www.wpxpo.com/contact/" target="_blank">contact form</a> to get quick help. Our highly qualified team members are ready to help you with all your queries related to our products.', 'ultimate-post'); ?></p>
                        <a class="ultp-btn ultp-btn-transparent" href="https://www.wpxpo.com/contact/" target="_blank"><?php _e('Get Support', 'ultimate-post'); ?></a>
                    </div>
                    <div class="ultp-upgrade-help-item ultp-admin-card">
                        <img src="<?php echo ULTP_URL.'assets/img/admin/love-icon.svg'; ?>" alt="Love">
                        <h4><?php _e('Show Some Love', 'ultimate-post'); ?></h4>
                        <p><?php _e('We are always looking for ways to make our products offer a fulfilling experience. Leave your <a href="https://wordpress.org/support/plugin/ultimate-post/reviews/#new-post" target="-blank">valuable recommendations</a> so that we can make our products better.', 'ultimate-post'); ?></p>
                        <a class="ultp-btn ultp-btn-transparent" href="https://wordpress.org/support/plugin/ultimate-post/reviews/#new-post" target="_blank"><?php _e('Show Love', 'ultimate-post'); ?></a>
                    </div>
                    <div class="ultp-upgrade-help-item ultp-admin-card">
                        <img src="<?php echo ULTP_URL.'assets/img/admin/video-icon.svg'; ?>" alt="Video">
                        <h4><?php _e('Video Tutorials', 'ultimate-post'); ?></h4>
                        <p><?php _e('We have a variety of <a href="https://www.youtube.com/channel/UC9I7kzTtG31YlWdG3iL42Jg/videos" target="_blank">Video Assets</a> to help you out. Find out relevant product tutorials to create an awesome experience suited both to you and your users. ', 'ultimate-post'); ?></p>
                        <a class="ultp-btn ultp-btn-transparent" href="https://www.youtube.com/channel/UC9I7kzTtG31YlWdG3iL42Jg/videos" target="_blank"><?php _e('View Details', 'ultimate-post'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    <?php }
}