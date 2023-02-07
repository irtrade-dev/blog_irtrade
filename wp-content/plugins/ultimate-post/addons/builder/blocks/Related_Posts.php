<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Related_Posts {
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function get_attributes($default = false) {
        
        $attributes = array(
            'blockId' => [
                'type' => 'string',
                'default' => '',
            ],
            'layout' => [
                'type' => 'string',
                'default' => '',
            ],
            //--------------------------
            //  Related Post Style
            //--------------------------
            'checkboxTest' => [
                'type' => 'string',
                'default' => '[]', 
            ],
            'radioImage' => [
                'type' => 'string',
                'default' => '',
            ],
            'checkbox' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'relSelectPostType' => [
                'type' => 'string',
                'default' => "",
            ],
            'relNumberItems' => [
                'type' => 'object',
                'default' => (object)['lg' =>'8', 'unit' =>'px'],
                'style' => [
                     (object)[
                         'depends' => [
                             (object)['key'=>'viewIconShow','condition'=>'==','value'=> true],
                            ],
                            'selector'=>'{{ULTP}} .ultp-view-count > span{ margin-left: {{viewSpace}} }'
                    ],
                ],
            ],
            'relOrderBy' => [
                'type' => 'string',
                'default' => '',
            ],
            'relPastPost' => [
                'type' => 'number',
                'default' => '', 
            ],
            'viewIconStyle' => [
                'type' => 'string',
                'default' => '',
            ],
            'disCurrentPost' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'disPastPost' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'relExcludeCat' => [
                'type' => 'string',
                'default' => ''
            ],
            'relExcludeTag' => [
                'type' => 'string',
                'default' => '',
            ],
            'relExcludePost' => [
                'type' => 'string',
                'default' => '',
            ],
            'enAutoScroll' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'relColumns' => [
                'type' => 'object',
                'default' => (object)['lg' =>'8', 'unit' =>'px'],
                'style' => [
                     (object)[
                         'depends' => [
                             (object)['key'=>'viewIconShow','condition'=>'==','value'=> true],
                            ],
                            'selector'=>'{{ULTP}}  { margin-left: {{viewSpace}} }'
                    ],
                ],
            ],
            'relRowGap' => [
                'type' => 'object',
                'default' => (object)['lg' =>'8', 'unit' =>'px'],
                'style' => [
                     (object)[
                            'selector'=>'{{ULTP}}  { margin-left: {{viewSpace}} }'
                    ],
                ],
            ],
            'relColumnsGap' => [
                'type' => 'object',
                'default' => (object)['lg' =>'8', 'unit' =>'px'],
                'style' => [
                     (object)[
                            'selector'=>'{{ULTP}}  { margin-left: {{viewSpace}} }'
                    ],
                ],
            ],
            'relHeading' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'relExcerpt' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'relTitle' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'relCategory' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'relMeta' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'relImage' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'relAlign' => [
                'type' => 'object',
                'default' => '', 
            ],

            //--------------------------
            //  Post View Style
            //--------------------------
            'viewTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>14, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-view-count > span'
                    ],
                ],
            ],
            'viewIconColor' => [
                'type' => 'string',
                'default' => '#656565',
                'style' => [
                    (object)[
                        'selector'=>'.ultp-view-count >span{ color:{{viewIconColor}} }'
                    ],
                ],
            ],
            'viewIconShow' => [
                'type' => 'boolean',
                'default' => true,
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
            'viewCountAlign' => [
                'type' => 'object',
                'default' => ['lg' => 'left'],
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-block-wrapper { text-align: {{viewCountAlign}};}'
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
                            'selector'=>'{{ULTP}} .ultp-view-count { margin-left: {{viewSpace}} }'
                        ],
                    ],
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
            'viewLabel' => [
                'type' => 'boolean',
                'default' => false,
            ],
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
        register_block_type( 'ultimate-post/related-posts',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );
    }

    public function content($attr, $noAjax) {
        $block_name = 'related Posts';
        $wrapper_before = $wrapper_after = $wrapper_content = $attachment_title = '';

        $wrapper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').'">';
            $wrapper_before .= '<div class="ultp-block-wrapper">';     
                $wrapper_content .= $attr['checkbox'];
            $wrapper_after .= '</div>';
        $wrapper_after .= '</div>';

        return $wrapper_before.$wrapper_content.$wrapper_after;
    }
}