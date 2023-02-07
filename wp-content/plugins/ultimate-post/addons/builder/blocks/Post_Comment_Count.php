<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Comment_Count {
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
                Post Comment Count Settings
            ============================*/
            'commentLabel' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'commentIconShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'commentColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-count { color:{{commentColor}} }'
                    ],
                ],  
            ],
            'commentTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 0,'size' => (object)['lg' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-count'
                    ],
                ],
            ],
            'commentCountAlign' => [
                'type' => 'object',
                'default' => [],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-count { justify-content: {{commentCountAlign}};}'
                    ],
                ],
            ], 


            /*============================
                Comment Count Label Settings
            ============================*/
            'commentLabelText' => [
                'type' => 'string',
                'default' => 'comment ',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'commentLabel','condition'=>'==','value'=> true],
                        ],
                    ],
                ], 
            ],
            "commentLabelAlign" => [
                "type" => "string",
                "default" => "after",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'commentLabelAlign','condition'=>'==','value'=> "before"],
                            (object)['key'=>'commentLabel','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-count .ultp-comment-label {order: -1;margin-right: 5px;}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'commentLabelAlign','condition'=>'==','value'=> "after"],
                            (object)['key'=>'commentLabel','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-count .ultp-comment-label {order: unset; margin-left: 5px;}'
                    ],
                ], 
            ],

            /*============================
                Comment Count Icon Settings
            ============================*/
            'iconColor' => [
                'type' => 'string',
                'default' => '#656565',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'commentIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-count > svg { fill:{{iconColor}}; stroke:{{iconColor}};}'
                    ],
                ],  
            ],
            'commentIconStyle' => [
                'type' => 'string',
                'default' => 'commentCount1',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'commentIconShow','condition'=>'==','value'=> true],
                        ],
                    ],
                ], 
            ],
            'commentIconSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'15', 'unit' =>'px'],
                'style' => [
                        (object)[
                        'depends' => [
                            (object)['key'=>'commentIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-count svg{ width:{{commentIconSize}}; height:{{commentIconSize}} }'
                    ],
                ],
            ],
            'commentSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'8', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'commentIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-count > svg { margin-right: {{commentSpace}} }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'commentIconShow','condition'=>'==','value'=> true],
                            (object)['key'=>'commentLabelAlign','condition'=>'==','value'=> "before"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-count > svg { margin: {{commentSpace}} } {{ULTP}} .ultp-comment-count .ultp-comment-label {margin: 0px !important;}'
                    ],
                ],
            ],

            //--------------------------
            //  Advanced Settings
            //--------------------------
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
        register_block_type( 'ultimate-post/post-comment-count',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );
    }

    public function content($attr, $noAjax) {
        $block_name = 'post-comment-count';
        $wrapper_before = $wrapper_after = $content = '';

        $comment_count = get_post_meta( get_the_ID(), 'post_comments_count', true );
        $wrapper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').''.(isset($attr["align"])? ' align' .$attr["align"]:'').'">';
                $wrapper_before .= '<div class="ultp-block-wrapper">';
                    $content .= '<span class="ultp-comment-count">';
                        if ($attr["commentIconShow"] && ($attr["commentIconStyle"] != '')) {
                            $content .= ultimate_post()->svg_icon($attr["commentIconStyle"]); 
                        }
                        $content .= '<span>'.($comment_count ? $comment_count : 0).'</span>';
                        if ($attr["commentLabel"]) {
                            $content .= '<span class="ultp-comment-label">'.$attr["commentLabelText"].'</span>';
                        }
                    $content .= '</span>';
            $wrapper_after .= '</div>';
            $wrapper_after .= '</div>';

        return $wrapper_before.$content.$wrapper_after;
    }
}