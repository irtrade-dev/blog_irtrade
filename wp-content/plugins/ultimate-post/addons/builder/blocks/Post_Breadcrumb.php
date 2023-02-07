<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Breadcrumb {
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function get_attributes($default = false) {

        $attributes = array(
            'blockId' => [
                'type' => 'string',
                'default' => '',
            ],

            /*============================
                Breadcrumb Settings
            ============================*/
            'bcrumbSeparator' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'breadcrumbColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-breadcrumb li {color:{{breadcrumbColor}}}'
                    ]
                ],
            ],
            'breadcrumbLinkColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-breadcrumb li > a{color:{{breadcrumbLinkColor}}}'
                    ]
                ],
            ],
            'bcrumbLinkHoverColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-breadcrumb li a:hover {color:{{bcrumbLinkHoverColor}}}'
                    ]
                ],
            ],
            'bcrumbTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 0,'size' => (object)['lg' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-breadcrumb li , .ultp-builder-breadcrumb li a'
                    ],
                ],
            ],      
            'bcrumbSpace' => [
                'type' => 'string',
                'default' => 12,
                'style' => [
                    (object)[
                        'selector'=> "{{ULTP}} li:not(.ultp-breadcrumb-separator) {margin: 0 {{bcrumbSpace}}px;}",
                    ]
                ] 
            ],
            'bcrumbAlign' => [
                'type' => 'object',
                'default' => (object)[],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-breadcrumb { justify-content:{{bcrumbAlign}}; }'
                    ],
                ],
            ],
            

            /*============================
                Separator Style
            ============================*/
            'bcrumbSeparatorIcon' => [
                'type' => 'string',
                'default' => 'dash',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'bcrumbSeparator','condition'=>'==','value'=>true],
                        ],
                    ],
                ],
            ], 
            'bcrumbSeparatorColor' => [
                'type' => 'string',
                'default' => '#a9a9a9',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'bcrumbSeparator','condition'=>'==','value'=>true],
                        ],
                        'selector'=> "{{ULTP}} .ultp-builder-breadcrumb .ultp-breadcrumb-separator {color:{{bcrumbSeparatorColor}};}",
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'bcrumbSeparator','condition'=>'==','value'=>true],
                            (object)['key'=>'bcrumbSeparatorIcon','condition'=>'==','value'=> "dot"],
                        ],
                        'selector'=> "{{ULTP}} .ultp-builder-breadcrumb .ultp-breadcrumb-separator {background:{{bcrumbSeparatorColor}};}",
                    ],
                ], 
            ],
            'bcrumbSeparatorSize' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'bcrumbSeparatorIcon','condition'=>'==','value'=> 'dot'],
                            (object)['key'=>'bcrumbSeparator','condition'=>'==','value'=>true],
                        ],
                        'selector'=> "{{ULTP}} .ultp-builder-breadcrumb li.ultp-breadcrumb-separator:after {height:{{bcrumbSeparatorSize}}px; width:{{bcrumbSeparatorSize}}px;}",
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'bcrumbSeparator','condition'=>'==','value'=>true],
                        ],
                        'selector'=> "{{ULTP}} .ultp-builder-breadcrumb li.ultp-breadcrumb-separator:after {font-size:{{bcrumbSeparatorSize}}px;}",
                    ]
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

    public function register() {
        register_block_type( 'ultimate-post/post-breadcrumb',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );
    }


    public function content($attr, $noAjax) {
        global $post;
        $block_name = 'post-breadcrumb';
        $wrapper_before = $wrapper_after = $content = '';

        $wrapper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').''.(isset($attr["align"])? ' align' .$attr["align"]:'').'">';
            $wrapper_before .= '<div class="ultp-block-wrapper">';
                $content .= '<ul class="ultp-builder-breadcrumb ultp-breadcrumb-'.$attr['bcrumbSeparatorIcon'].'">';
                    $seperator = '';
                    if ($attr['bcrumbSeparator']) {
                        $seperator .= '<li class="ultp-breadcrumb-separator"></li>';
                    }
                    $content .= '<li><a href="'.esc_url(home_url('/')).'">'.__('Home', 'ultimate-post').'</a></li>';
                    if (is_category() || is_single() || is_tag() || is_author()) {
                        if (is_category()) {
                            $cat = get_queried_object();
                            $parent_cat_id = $cat->parent;
                            if($parent_cat_id) {
                                $content .= $seperator.'<li><a href="'.get_term_link( $parent_cat_id).'">'.get_the_category_by_ID($parent_cat_id).'</a></li>';
                            }
                            $content .= $seperator.'<li>'.single_cat_title('', false).'</li>';
                        }
                        if (is_tag()) {
                            $content .= $seperator.'<li>'.single_tag_title('', false).'</li>';
                        }
                        if (is_author()) {
                            $content .= $seperator.'<li>'.get_the_author_meta('display_name', false).'</li>';
                        }
                        if (is_single()) {
                            $cat = get_the_category();
                            if (isset($cat[0])) {
                                $content .= $seperator.'<li><a href="'.get_term_link($cat[0]->term_id).'">'.$cat[0]->name.'</a></li>';
                            }
                            $content .= $seperator.'<li>'.get_the_title().'</li>';
                        }
                    } elseif (is_page()) {
                        if ($post->post_parent) {
                            $ancestor = get_post_ancestors($post->post_parent);
                            if (isset($ancestor[0])) {
                                $content = $seperator.'<li><a href="'.get_permalink($ancestor[0]).'">'.get_the_title($ancestor[0]).'</a></li>';
                            }
                        }
                        $content .= $seperator.'<li>'.get_the_title().'</li>';
                    } elseif (is_search()) {
                        $content .= $seperator.'<li>'.get_search_query().'</li>';
                    }
                $content .= '</ul>';
            $wrapper_after .= '</div>';
        $wrapper_after .= '</div>';

        return $wrapper_before.$content.$wrapper_after;
    }
}