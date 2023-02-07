<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Author_Meta {
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
                Post Author Meta  Settings
            ============================*/
            'authMetaIconColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-authMeta-count > .ultp-authMeta-name { color:{{authMetaIconColor}} }'
                    ],
                ],
            ],
            'authMetaHoverColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector' => '{{ULTP}} .ultp-authMeta-count > .ultp-authMeta-name:hover{color:{{authMetaHoverColor}} }'
                    ]
                ]
            ],
            'authMetaTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 0,'size' => (object)['lg' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                    'selector'=>'{{ULTP}} .ultp-authMeta-count .ultp-authMeta-name'
                    ],
                ],
            ],
            'authMetAvatar' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'authMetaIconShow' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'authMetaCountAlign' => [
                'type' => 'object',
                'default' => [],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-wrapper { text-align: {{authMetaCountAlign}};}'
                    ],
                ],
            ],
            

            /*============================
                Post Author Avatar Style
            ============================*/
            'authMetAvSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'30', 'unit' =>'px'],
                'style' => [
                        (object)[
                        'depends' => [
                            (object)['key'=>'authMetAvatar','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-authMeta-count .ultp-authMeta-avatar > img { width:{{authMetAvSize}}; height:{{authMetAvSize}} }'
                    ],
                ],
            ],
            'authMetAvSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'10', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authMetAvatar','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-authMeta-count .ultp-authMeta-avatar > img { margin-right: {{authMetAvSpace}} }'
                    ],
                ],
            ],
            'authMetAvRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'100', 'unit' =>'px'],
                'style' => [
                        (object)[
                        'depends' => [
                            (object)['key'=>'authMetAvatar','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-authMeta-count .ultp-authMeta-avatar > img { border-radius:{{authMetAvRadius}}; }'
                    ],
                ],
            ],
            'authMetaLabel' => [
                'type' => 'boolean',
                'default' => true,
            ],

            
            /*============================
                Post Author Icon Style
            ============================*/
            'authMetaIconStyle' => [
                'type' => 'string',
                'default' => 'author1',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authMetaIconShow','condition'=>'==','value'=> true],
                        ],
                    ],
                ], 
            ],
            'iconColor' => [
                'type' => 'string',
                'default' => '#656565',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authMetaIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-authMeta-count > svg, {{ULTP}} .ultp-authMeta-count > div > svg { fill:{{iconColor}}; stroke:{{iconColor}}}'
                    ],
                ],
            ],
            'authMetaIconSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'15', 'unit' =>'px'],
                'style' => [
                        (object)[
                            'depends' => [
                                (object)['key'=>'authMetaIconShow','condition'=>'==','value'=> true],
                            ],
                        'selector'=>'{{ULTP}} .ultp-authMeta-count > svg { width:{{authMetaIconSize}}; height:{{authMetaIconSize}} }'
                    ],
                ],
            ],
            'authMetaSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'10', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authMetaIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-authMeta-count > svg { margin-right: {{authMetaSpace}} }'
                    ],
                ],
            ],
            

            /*============================
                Post Author Label Style
            ============================*/
            'authMetaLabelText' => [
                'type' => 'string',
                'default' => 'By',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authMetaLabel','condition'=>'==','value'=> true],
                        ],
                    ],
                ], 
            ],
            'authMetaLabelColor' => [
                'type' => 'string',
                'default' => '#656565',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authMetaLabel','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-authMeta-label { color:{{authMetaLabelColor}} }'
                    ],
                ],  
            ],
            'authMetaLabelTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>15, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authMetaLabel','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-authMeta-label'
                    ],
                ],
            ],
            'authMetaLabelSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'8', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authMetaLabel','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-authMeta-label  { margin-right: {{authMetaLabelSpace}} }'
                    ],
                ],
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
        register_block_type( 'ultimate-post/post-author-meta',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );
    }

    public function content($attr, $noAjax) {
        $block_name = 'post-author-meta';
        $wrapper_before = $wrapper_after = $content = '';
        $author_id = get_post_field('post_author' , get_the_ID());
        
        if ($author_id) {
            $wrapper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').''.(isset($attr["align"])? ' align' .$attr["align"]:'').'">';
                $wrapper_before .= '<div class="ultp-block-wrapper">';
                    $content .= '<span class="ultp-authMeta-count">';
                        if ($attr["authMetaIconShow"] && ($attr["authMetaIconStyle"] != '')) {
                            $content .= ultimate_post()->svg_icon($attr["authMetaIconStyle"]); 
                        }
                        if ($attr["authMetAvatar"]) {
                            $content .= '<div class="ultp-authMeta-avatar">';
                                $content .= get_avatar( $author_id, 32 ); 
                            $content .= '</div>';
                        }
                        if ($attr["authMetaLabel"]) {
                            $content .= '<span class="ultp-authMeta-label">'.$attr["authMetaLabelText"].'</span>';
                        }   
                        $content .= '<a class="ultp-authMeta-name" href="'.get_author_posts_url( $author_id ).'">';
                            $content .= get_the_author_meta('display_name', $author_id);
                        $content .= '</a>';
                    $content .= '</span>';
                $wrapper_after .= '</div>';
            $wrapper_after .= '</div>';
        }
            
        return $wrapper_before.$content.$wrapper_after;
    }
}