<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Date_Meta {
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
                Post Date Meta Setting
            ============================*/
            "prefixEnable" => [
                "type" => "boolean",
                "default" => false,
            ],
            'metaDateIconShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            "dateFormat" => [
                "type" => "string",
                "default" => "updated"
            ],
            'metaDateFormat' => [
                'type' => 'string',
                'default' => 'M j, Y',
            ],
            'metaDateColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-date-meta-format { color:{{metaDateColor}} }'
                    ],
                ],  
            ],
            'metaDateTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 0,'size' => (object)['lg' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-date-meta'
                    ],
                ],
            ],
            'metaDateCountAlign' => [
                'type' => 'object',
                'default' => [],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-wrapper { text-align: {{metaDateCountAlign}};}'
                    ],
                ],
            ],


            /*============================
                Post Date Meta Label
            ============================*/
            'datePubLabel' => [
                'type' => 'string',
                'default' => 'Publish Date',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'prefixEnable','condition'=>'==','value'=> true],
                            (object)['key'=>'dateFormat','condition'=>'==','value'=> "publish"]
                        ],
                    ],
                ],
            ],
            'dateUpLabel' => [
                'type' => 'string',
                'default' => 'Updated Date',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'prefixEnable','condition'=>'==','value'=> true],
                            (object)['key'=>'dateFormat','condition'=>'==','value'=> "updated"]
                        ],
                    ],
                ],
            ],
            'datePrefixColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'prefixEnable','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-date-meta-prefix { color:{{datePrefixColor}} }'
                    ],
                ],  
            ],
            'datePrefixSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'12', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'prefixEnable','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-date-meta-prefix { margin-right: {{datePrefixSpace}} }'
                    ],
                ],
            ],

            /*============================
                Post Date Meta icon style
            ============================*/
            'metaDateIconStyle' => [
                'type' => 'string',
                'default' => 'date1',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaDateIconShow','condition'=>'==','value'=> true],
                        ],
                    ],
                ], 
            ],
            'iconColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaDateIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-date-meta-icon > svg { fill:{{iconColor}}; stroke:{{iconColor}};}'
                    ],
                ],  
            ],
            'metaDateIconSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'15', 'unit' =>'px'],
                'style' => [
                        (object)[
                        'depends' => [
                            (object)['key'=>'metaDateIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-date-meta-icon svg { width:{{metaDateIconSize}}; height:{{metaDateIconSize}} }'
                    ],
                ],
            ],
            'metaDateIconSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'8', 'unit' =>'px'],
                'style' => [
                    (object)[
                            'depends' => [
                                (object)['key'=>'metaDateIconShow','condition'=>'==','value'=> true],
                            ],
                            'selector'=>'{{ULTP}} .ultp-date-meta-icon > svg { margin-right: {{metaDateIconSpace}} }'
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
        register_block_type( 'ultimate-post/post-date-meta',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );
    }

    public function content($attr, $noAjax) {
        $block_name = 'post-date-meta';
        $wrapper_before = $wrapper_after = $content = '';

            $wrapper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class=" wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').''.(isset($attr["align"])? ' align' .$attr["align"]:'').'">';
                $wrapper_before .= '<div class="ultp-block-wrapper">';
                    $content .= '<div class="ultp-date-meta">';
                        if ($attr["prefixEnable"]) {
                            $content .= '<span class="ultp-date-meta-prefix">';   
                                if($attr['dateFormat'] == "publish"){
                                    $content .= $attr['datePubLabel']; 
                                } else {
                                    $content .= $attr['dateUpLabel']; 
                                }

                            $content .= '</span>';    
                        }
                        if ($attr["metaDateIconShow"] && $attr["metaDateIconStyle"]) {
                            $content .= '<span class="ultp-date-meta-icon">';   
                                $content .= ultimate_post()->svg_icon($attr["metaDateIconStyle"]); 
                            $content .= '</span>';
                        }
                        if ($attr['metaDateFormat']) {
                            $content .= '<span class="ultp-date-meta-format">';   
                                if ($attr['dateFormat'] == 'updated') {
                                    $content .= get_the_modified_date(ultimate_post()->get_format($attr["metaDateFormat"])); 
                                } else {
                                    $content .= get_the_date(ultimate_post()->get_format($attr["metaDateFormat"])); 
                                }
                                
                            $content .= '</span>';
                        }
                    $content .= '</div>';
                $wrapper_after .= '</div>';
            $wrapper_after .= '</div>';
        
        return $wrapper_before.$content.$wrapper_after;
    }
}