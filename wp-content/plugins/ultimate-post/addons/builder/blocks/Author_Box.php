<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Author_Box{
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function register() {
        register_block_type( 'ultimate-post/author-box',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );
    }
    public function get_attributes($default = false) {
        $attributes = array(
            'blockId' => [
                'type' => 'string',
                'default' => '',
            ],
            'layout' => [
                'type' => 'string',
                'default' => 'layout1',
            ],

            /*============================
                Author Box Settings
            ============================*/
            'imgShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'writtenByShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'authorBioShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'metaShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'allPostLinkShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'authorBoxAlign' => [
                'type' => 'object',
                'default' => 'center',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'layout','condition'=>'!=','value'=> "layout2"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "layout4"]
                        ],
                        'selector'=>'{{ULTP}} .ultp-author-box {text-align:{{authorBoxAlign}};}'
                    ],
                ],
            ],


            /*============================
                Container Style
            ============================*/
            'boxContentBg' => [
                'type' => 'object',
                'default' => (object)['openColor' => 1, 'type' => 'color', 'color' => '#f5f5f5'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-author-box'
                    ],
                ],
            ],
            'boxContentBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0 ],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-author-box'
                    ],
                ],
            ],
            'boxContentRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-author-box { border-radius:{{boxContentRadius}}; }'
                    ],
                ],
            ],
            'boxContentPad' => [
                'type' => 'object',
                'default' => (object)['lg' =>'20', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-author-box { padding:{{boxContentPad}}; }'
                    ],
                ],
            ],

            
            /*============================
                Author Image Settings
            ============================*/
            'imgWidth' => [
                'type' => 'object',
                'default' => (object)['lg' =>'100', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'imgShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-image { width: {{imgWidth}}; height: {{imgWidth}}; object-fit: cover; }'
                    ],
                ],
            ],
            'imgSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'20', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'imgShow','condition'=>'==','value'=>true],
                            (object)['key'=>'layout','condition'=>'==','value'=>'layout1'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-image { margin-bottom: {{imgSpace}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'imgShow','condition'=>'==','value'=>true],
                            (object)['key'=>'layout','condition'=>'==','value'=>'layout2'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-image-section { margin-right: {{imgSpace}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'imgShow','condition'=>'==','value'=>true],
                            (object)['key'=>'layout','condition'=>'==','value'=>'layout3'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-image { margin-bottom: {{imgSpace}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'imgShow','condition'=>'==','value'=>true],
                            (object)['key'=>'layout','condition'=>'==','value'=>'layout4'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-image-section { margin-left: {{imgSpace}}; }'
                    ],
                ],
            ],
            'imgUp' => [
                'type' => 'object',
                'default' => (object)['lg' =>'60', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'imgShow','condition'=>'==','value'=>true],
                            (object)['key'=>'layout','condition'=>'==','value'=>'layout3'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-author-box-layout3-content .ultp-post-author-image { margin-top: -{{imgUp}}; } {{ULTP}} .ultp-block-wrapper { margin-top: {{imgUp}}; }'
                    ],
                ],
            ],
            'imgBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' =>(object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#000','type' => 'solid'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'imgShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-image'
                    ],
                ],
            ],
            'imgRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'100', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'imgShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-image { border-radius:{{imgRadius}}; }'
                    ],
                ],
            ],
            

            /*============================
                Written by Settings
            ============================*/
            'writtenByText' => [
                'type' => 'string',
                'default' => 'Written by',
            ],
            'writtenByColor' => [
                'type' => 'string',
                'default' => '#656565',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'writtenByShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-written-by {color:{{writtenByColor}};}'
                    ],
                ],
            ],
            'writtenByTypo' => [
                'type' => 'object',
                'default' =>  (object)['openTypography' => 1,'size' => (object)['lg' => '20', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'writtenByShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-written-by'
                    ],
                ],
            ],

            /*============================
                Author Name Settings
            ============================*/
            'authorNameTag' => [
                'type' => 'string',
                'default' => 'h4',
            ],
            'authorNameColor' => [
                'type' => 'string',
                'default' => '#333',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-author-name a {color:{{authorNameColor}} !important; }'
                    ],
                ],
            ],
            'authorNameHoverColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-author-name a:hover { color:{{authorNameHoverColor}} !important; }'
                    ],
                ],
            ],
            'authorNameTypo' => [
                'type' => 'object',
                'default' =>  (object)['openTypography' => 1,'size' => (object)['lg' => '20', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-author-name'
                    ],
                ],
            ],


            /*============================
                Author Bio Settings
            ============================*/
            'authorBioColor' => [
                'type' => 'string',
                'default' => '#777',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authorBioShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-bio-meta {color:{{authorBioColor}};}'
                    ],
                ],
            ],
            'authorBioTypo' => [
                'type' => 'object',
                'default' =>  (object)['openTypography' => 1,'size' => (object)['lg' => '14', 'unit' => 'px'], 'height' => (object)['lg' => '22', 'unit' => 'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authorBioShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-bio'
                    ],
                ],
            ],
            'authorBioMargin' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '20','bottom' => '','left' => '', 'right' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authorBioShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-bio { margin:{{authorBioMargin}}; }'
                ],
                ]
            ],


            /*============================
                Meta Setting/Style Settings
            ============================*/
            'metaPosition' => [
                'type' => 'string',
                'default' => 'bottom',
            ],
            'metaColor' => [
                'type' => 'string',
                'default' => '#656565',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-total-post, {{ULTP}} .ultp-total-comment { color: {{metaColor}}; }'
                    ],
                ],
            ],
            'metaTypo' => [
                'type' => 'object',
                'default' =>  (object)['openTypography' => 0,'size' => (object)['lg' => '', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px'],'decoration' => 'none', 'transform' => '', 'family'=>'','weight'=>''],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-meta'
                    ],
                ],
            ],
            'metaMargin' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top'=> '12', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-meta { margin:{{metaMargin}}; }'
                    ],
                ],
            ],
            'metaPadding' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-meta { padding:{{metaPadding}}; }'
                    ],
                ],
            ],
            'metaBg' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-meta { background:{{metaBg}}; }'
                    ],
                ],
            ],
            'metaBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => '0', 'bottom' => '0', 'left' => '0'],'color' => '#009fd4','type' => 'solid'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-author-meta'
                    ],
                ],
            ],


            /*============================
                View all Post Button Settings
            ============================*/
            'viewAllPostText' => [
                'type' => 'string',
                'default' => 'View All Posts',
            ],
            'viewAllPostTypo' => [
                'type' => 'object',
                'default' =>  (object)['openTypography' => 1,'size' => (object)['lg' => '14', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px'],'decoration' => 'none', 'transform' => '', 'family'=>'','weight'=>''],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'allPostLinkShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-author-post-link-text'
                    ],
                ],
            ],
            'viewAllPostColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'allPostLinkShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-author-post-link a:not(.wp-block-button__link), {{ULTP}} .ultp-author-post-link-text {color:{{viewAllPostColor}};}'
                    ],
                ],
            ],
            'viewAllPostBg' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'allPostLinkShow','condition'=>'==','value'=>true],
                        ],
                    'selector'=>'{{ULTP}} .ultp-author-post-link-text'
                    ],
                ],
            ],
            'viewAllPostRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'allPostLinkShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-author-post-link-text { border-radius:{{viewAllPostRadius}}; }'
                    ],
                ],
            ],
            'viewAllPostHoverColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'allPostLinkShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-author-post-link .ultp-author-post-link-text:hover { color:{{viewAllPostHoverColor}}; }'
                    ],
                ],
            ],
            'viewAllPostBgHoverColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'allPostLinkShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-author-post-link-text:hover'
                    ],
                ],
            ],
            'viewAllPostHoverRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'allPostLinkShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-author-post-link-text:hover { border-radius:{{viewAllPostHoverRadius}}; }'
                    ],
                ],
            ],
            'viewAllPostPadding' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'allPostLinkShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-author-post-link-text { padding:{{viewAllPostPadding}}; }'
                ],
                ]
            ],
            'viewAllPostMargin' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '15','bottom' => '','left' => '', 'right' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'allPostLinkShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-author-post-link { margin:{{viewAllPostMargin}}; }'
                ],
                ]
            ],


            
            //--------------------------
            //  Advanced Settings
            //--------------------------
            'advanceId' => [
                'type' => 'string',
                'default' => '',
            ],
            'advanceZindex' => [
                'type' => 'number',
                'default' => '',
                'style' => [
                    (object)[
                        'selector' => '{{ULTP}} {z-index:{{advanceZindex}};}'
                    ],
                ],
            ],
            'wrapMargin' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '','bottom' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-wrapper { margin:{{wrapMargin}}; }'
                    ],
                ],
            ],
            'wrapOuterPadding' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '','bottom' => '','left' => '', 'right' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-wrapper { padding:{{wrapOuterPadding}}; }'
                    ],
                ],
            ],
            'wrapBg' => [
                'type' => 'object',
                'default' => (object)['openColor' => 0, 'type' => 'color', 'color' => '#f5f5f5'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-wrapper'
                    ],
                ],
            ],
            'wrapBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' =>(object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-wrapper'
                    ],
                ],
            ],
            'wrapShadow' => [
                'type' => 'object',
                'default' => (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-wrapper'
                    ],
                ],
            ],
            'wrapRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-wrapper { border-radius:{{wrapRadius}}; }'
                    ],
                ],
            ],
            'wrapHoverBackground' => [
                'type' => 'object',
                'default' => (object)['openColor' => 0, 'type' => 'color', 'color' => '#037fff'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-wrapper:hover'
                    ],
                ],
            ],
            'wrapHoverBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-wrapper:hover'
                    ],
                ],
            ],
            'wrapHoverRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-wrapper:hover { border-radius:{{wrapHoverRadius}}; }'
                    ],
                ],
            ],
            'wrapHoverShadow' => [
                'type' => 'object',
                'default' => (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-wrapper:hover'
                    ],
                ],
            ],
            'hideExtraLarge' => [
                'type' => 'boolean',
                'default' => false,
                'style' => [
                    (object)[
                        'selector' => '{{ULTP}} {display:none;}'
                    ],
                ],
            ],
            'hideDesktop' => [
                'type' => 'boolean',
                'default' => false,
                'style' => [
                    (object)[
                        'selector' => '{{ULTP}} {display:none;}'
                    ],
                ],
            ],
            'hideTablet' => [
                'type' => 'boolean',
                'default' => false,
                'style' => [
                    (object)[
                        'selector' => '{{ULTP}} {display:none;}'
                    ],
                ],
            ],
            'hideMobile' => [
                'type' => 'boolean',
                'default' => false,
                'style' => [
                    (object)[
                        'selector' => '{{ULTP}} {display:none;}'
                    ],
                ],
            ],
            'advanceCss' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector' => '{{ULTP}} {display:none;}'
                    ],
                ],
            ],
        );

        if ($default) {
            $temp = array();
            foreach ($attributes as $key => $value) {
                if (isset($value['default'])) {
                    $temp[$key] = $value['default'];
                }
            }
            return $temp;
        } else {
            return $attributes;
        }
    }

    public function content($attr, $noAjax) {
        $block_name = 'author_box';
        $author_bio = $wrapper_before = $wrapper_after = $content = '';

        $page_post_id = get_the_ID(); // ultimate_post()->get_ID();
        
        if($page_post_id){
            $_post = get_post( $page_post_id );
            $post_author = get_userdata( $_post->post_author );

            // Author Image
            $author_image = '<div class="ultp-post-author-image-section">';
            $author_image .= '<img src="' . get_avatar_url($post_author) . '" class="ultp-post-author-image" alt="author image" />';
            $author_image .= '</div>';

            
            // Author Meta
            $author_meta = '<div class="ultp-post-author-meta">';
            $author_meta .= '<span class="ultp-total-post">' . count_user_posts($_post->post_author, $post_type = 'post') . ' Posts</span>';
            $author_meta .= '<span class="ultp-total-comment">' . get_comments_number($page_post_id) . ' Comments</span>';
            $author_meta .= '</div>';

            // Author Description
            if ($post_author->description) {
                $author_bio .= '<div class="ultp-post-author-bio">';
                $author_bio .= '<span class="ultp-post-author-bio-meta">' . $post_author->description . '</span>';
                $author_bio .= '</div>';
            }

            // Author Url
            if (get_author_posts_url($_post->post_author)) {
                $all_post_link = '<div class="ultp-author-post-link">';
                $all_post_link .= '<a class="ultp-author-post-link-text" href="'.get_author_posts_url( $_post->post_author ).'">'.$attr['viewAllPostText'].'</a>';
                $all_post_link .= '</div>';
            }
            
            $wrapper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').''.(isset($attr["align"])? ' align' .$attr["align"]:'').'">';
                $wrapper_before .= '<div class="ultp-block-wrapper">';
                    $content .= '<div class="ultp-author-box ultp-author-box-'.$attr["layout"].'-content">';
                        $content .= ($attr['imgShow'] && $attr['layout'] !== 'layout4' ? $author_image : '');
                            $content .= '<div class="ultp-post-author-details">';
                                $content .= '<div class="ultp-post-author-title">';
                                    $content .= $attr["writtenByShow"] ? '<span class="ultp-post-author-written-by">'.$attr["writtenByText"].'</span>' : '';
                                    $content .= '<'.$attr['authorNameTag'].' class="ultp-post-author-name"><a href="'.get_author_posts_url( $_post->post_author ).'">'.$post_author->display_name.'</a></'.$attr['authorNameTag'].'>';
                                $content .= '</div>';
                                

                                $content .= ($attr["metaShow"] && $attr["metaPosition"] == 'top' ? $author_meta : '');
                                
                                
                                $content .= ($attr["authorBioShow"] && $author_bio  ? $author_bio : '');
                                $content .= ($attr["metaShow"] && $attr["metaPosition"] == 'bottom' ? $author_meta : '');
                                $content .= ($attr["allPostLinkShow"] ? $all_post_link : '');
                            $content .= '</div>';
                            $content .= ($attr['imgShow'] && $attr['layout'] === 'layout4' ? $author_image : '');
                        $content .= '</div>';
                $wrapper_after .= '</div>';
            $wrapper_after .= '</div>';
        }

        return $wrapper_before.$content.$wrapper_after;
    }
}