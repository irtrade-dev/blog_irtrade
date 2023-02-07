<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_View_Count {
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
                Post View Settings
            ============================*/
            'viewLabel' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'viewIconShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'viewColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-view-count { color:{{viewColor}} }'
                    ],
                ],  
            ],
            'viewTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 0,'size' => (object)['lg' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-view-count > span'
                    ],
                ],
            ],
            'viewCountAlign' => [
                'type' => 'object',
                'default' => [],
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-block-wrapper { text-align: {{viewCountAlign}};}'
                   ],
                ],
            ],
             /*============================
                Post View Label Style 
            ============================*/
            
            'viewLabelText' => [
                'type' => 'string',
                'default' => 'View',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'viewLabel','condition'=>'==','value'=> true],
                        ],
                    ],
                ], 
            ],
            "viewLabelAlign" => [
                "type" => "string",
                "default" => "after",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'viewLabelAlign','condition'=>'==','value'=> "before"],
                            (object)['key'=>'viewLabel','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-view-count .ultp-view-label {order: -1;margin-right: 5px;}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'viewLabelAlign','condition'=>'==','value'=> "after"],
                            (object)['key'=>'viewLabel','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-view-count .ultp-view-label {order: unset; margin-left: 5px;}'
                    ],
                ], 
            ],

            /*============================
                Post View Icon Style
            ============================*/
            'viewIconColor' => [
                'type' => 'string',
                'default' => '#656565',
                'style' => [
                    (object)[
                        'selector'=>'.ultp-view-count >span{ color:{{viewIconColor}} }'
                    ],
                ],
            ],
            'viewIconStyle' => [
                'type' => 'string',
                'default' => 'viewCount1',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'viewIconShow','condition'=>'==','value'=> true],
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
                            (object)['key'=>'viewIconShow','condition'=>'==','value'=> true],
                        ],
                       'selector'=>'{{ULTP}} .ultp-view-count > svg { fill:{{iconColor}}; stroke:{{iconColor}};} '
                   ],
               ],  
            ],
            'viewIconSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'15', 'unit' =>'px'],
                'style' => [
                        (object)[
                        'depends' => [
                            (object)['key'=>'viewIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-view-count svg{ width:{{viewIconSize}}; height:{{viewIconSize}} }'
                    ],
                ],
            ],
            'viewSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'8', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'viewIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-view-count > span.ultp-view-count-number { margin-left: {{viewSpace}} }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'viewIconShow','condition'=>'==','value'=> true],
                            (object)['key'=>'viewLabelAlign','condition'=>'==','value'=> "before"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-view-count > svg { margin: {{viewSpace}} } {{ULTP}} .ultp-view-count .ultp-view-label {margin: 0px !important;}'
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
        register_block_type( 'ultimate-post/post-view-count',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );
    }

    public function content($attr, $noAjax) {
        $block_name = 'post-view-count';
        $wrapper_before = $wrapper_after = $content = '';

        $count = get_post_meta( get_the_ID(), '__post_views_count', true );

        $wrapper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').''.(isset($attr["align"])? ' align' .$attr["align"]:'').'">';
            $wrapper_before .= '<div class="ultp-block-wrapper">';     
                $content .= '<span class="ultp-view-count">';
                    if ($attr["viewIconShow"] && $attr["viewIconStyle"]) {
                        $content .= ultimate_post()->svg_icon($attr["viewIconStyle"]); 
                    }
                    $content .= '<span class="ultp-view-count-number">';
                        $content .= $count ? $count : 0;
                    $content .= '</span>';
                    if ($attr["viewLabel"]) {
                        $content .= '<span class="ultp-view-label"> '.$attr["viewLabelText"].'</span>';
                    }
                $content .= '</span>';
            $wrapper_after .= '</div>';
        $wrapper_after .= '</div>';

        return $wrapper_before.$content.$wrapper_after;
    }
}