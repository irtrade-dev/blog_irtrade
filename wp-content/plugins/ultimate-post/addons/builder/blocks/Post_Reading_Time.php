<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Reading_Time {
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
                Post Reading Meta Settings
            ============================*/
            'readLabel' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'readIconShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'readColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-read-count { color:{{readColor}} }'
                    ],
                ],
            ],
            'readTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 0,'size' => (object)['lg' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-read-count'
                    ],
                ],
            ],
            'readCountAlign' => [
                'type' => 'object',
                'default' => [],
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-block-wrapper { text-align: {{readCountAlign}};}'
                   ],
                ],
            ],
            'readLabelText' => [
                'type' => 'string',
                'default' => 'Reading Time',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readLabel','condition'=>'==','value'=> true],
                        ],
                    ],
                ], 
            ],
            'readLabelAlign' => [
                'type' => 'object',
                'default' => [],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-read-count { justify-content: {{readLabelAlign}};}'
                    ],
                ],
            ],
            "readLabelAlign" => [
                "type" => "string",
                "default" => "after",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readLabelAlign','condition'=>'==','value'=> "before"],
                            (object)['key'=>'readLabel','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-read-count .ultp-read-label {order: -1; margin-right: 5px;}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'readLabelAlign','condition'=>'==','value'=> "after"],
                            (object)['key'=>'readLabel','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-read-count .ultp-read-label {order: unset; margin-left: 5px;}'
                    ],
                ], 
            ],
            /*============================
                Post Reading Icon Settings
            ============================*/
            'iconColor' => [
                'type' => 'string',
                'default' => '#656565',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-read-count > svg { fill:{{iconColor}}; stroke:{{iconColor}};}'
                    ],
                ],  
            ],
            'readIconStyle' => [
                'type' => 'string',
                'default' => 'readingTime1',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readIconShow','condition'=>'==','value'=> true],
                        ],
                    ],
                ], 
            ],
            'readIconSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'15', 'unit' =>'px'],
                'style' => [
                        (object)[
                        'depends' => [
                            (object)['key'=>'readIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-read-count svg{ width:{{readIconSize}}; height:{{readIconSize}} }'
                    ],
                ],
            ],
            'readSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'8', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-read-count > svg { margin-right: {{readSpace}} }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'readIconShow','condition'=>'==','value'=> true],
                            (object)['key'=>'readLabelAlign','condition'=>'==','value'=> "before"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-read-count > svg { margin: {{readSpace}} } {{ULTP}}  
                         .ultp-read-count .ultp-read-label {margin: 0px !important;}'
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
        register_block_type( 'ultimate-post/post-reading-time',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );
    }

    public function content($attr, $noAjax) {
        $block_name = 'post-reading-time';
        $wrapper_before = $wrapper_after = $content = '';

            $wrapper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').''.(isset($attr["align"])? ' align' .$attr["align"]:'').'">';
                $wrapper_before .= '<div class="ultp-block-wrapper">';
                    $content .= '<span class="ultp-read-count">';
                        if ($attr["readIconShow"] && ($attr["readIconStyle"] != '')) {
                            $content .= ultimate_post()->svg_icon($attr["readIconStyle"]); 
                        }
                        $content .= '<div>';
                            $content .= ceil(mb_strlen(strip_tags(get_the_content( null,  false, get_the_ID() )))/1200);
                        $content .= '</div>';
                        if ($attr["readLabel"]) {
                            $content .= '<span class="ultp-read-label">'.$attr["readLabelText"].'</span>';
                        }
                    $content .= '</span>';
                $wrapper_after .= '</div>';
            $wrapper_after .= '</div>';
        
        return $wrapper_before.$content.$wrapper_after;
    }
}