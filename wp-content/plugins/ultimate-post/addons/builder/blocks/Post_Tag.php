<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Tag {
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
                Post Tag Settings
            ============================*/
            'tagLabelShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'tagIconShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'tagColor' => [
                'type' => 'string',
                'default' => '#333',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-tag a, {{ULTP}} .tag-builder-content {color:{{tagColor}};}'
                    ],
                ],
            ],
            'tagBgColor' => [
                'type' => 'string',
                'default' => (object)['openColor' => 0, 'type' => 'color', 'color' => '#f5f5f5'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-tag a {background:{{tagBgColor}};}'
                    ],
                ],
            ],
            'tagItemBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>1, 'width' =>(object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#e2e2e2','type' => 'solid'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-tag a'
                    ],
                ],
            ],
            'tagRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'2', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-tag a { border-radius:{{tagRadius}}; }'
                    ],
                ],
            ],
            'tagHovColor' => [
                'type' => 'string',
                'default' => '#fff',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-tag a:hover { color:{{tagHovColor}}; }'
                    ],
                ],
            ],
            'tagBgHovColor' => [
                'type' => 'string',
                'default' => '#000',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-tag a:hover {background:{{tagBgHovColor}};}'
                    ],
                ],
            ],
            'tagItemHoverBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>1, 'width' =>(object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#000','type' => 'solid'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-tag a:hover'
                    ],
                ],
            ],
            'tagHoverRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-tag  a:hover{ border-radius:{{tagHoverRadius}}; }'
                    ],
                ],
            ],
            'tagTypo' => [
                'type' => 'object',
                'default' =>  (object)['openTypography' => 0,'size' => (object)['lg' => '', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px'],'decoration' => 'none', 'transform' => '', 'family'=>'','weight'=>''],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-tag a'
                    ],
                ],
            ],
            'tagSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'8', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-tag a:not(:last-child) {margin-right:{{tagSpace}}}'
                    ],
                ],
            ],
            'tagItemPad' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '2','bottom' => '2','left' => '7','right' => '7', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-tag a{ padding:{{tagItemPad}} }'
                    ],
                ],
            ],
            'tagAlign' => [
                'type' => 'object',
                'default' =>  (object)[],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-tag { justify-content:{{tagAlign}}; }'
                    ],
                ],
            ],


            /*============================
                Tag Label Settings
            ============================*/
            'tagLabel' => [
                'type' => 'string',
                'default' => 'Tags: ',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tagLabelShow','condition'=>'==','value'=>true],
                        ],
                    ],
                ],
            ],
            'labelColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tagLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-builder-tag .tag-builder-label {color:{{labelColor}};}'
                    ],
                ],
            ],
            'labelBgColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tagLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-builder-tag .tag-builder-label {background:{{labelBgColor}};}'
                    ],
                ],
            ],
            'labelTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 0,'size' => (object)['lg' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tagLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-builder-tag .tag-builder-label'
                    ],
                ],
            ],
            'tagLabelSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'8', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tagLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-builder-tag .tag-builder-label{margin-right:{{tagLabelSpace}}}'
                    ],
                ],
            ],
            'tagLabelBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tagLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-builder-tag .tag-builder-label'
                    ],
                ],
            ],
            'tagLabelRadius' => [
                'type' => 'object',
                'default' => (object)[],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-tag .tag-builder-label{ border-radius:{{tagLabelRadius}}; }'
                    ],
                ],
            ],
            'tagLabelPad' => [
                'type' => 'object',
                'default' => (object)[],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tagLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .tag-builder-label{ padding:{{tagLabelPad}} }'
                    ],
                ],
            ],
            /*============================
                Tag Icon Settings
            ============================*/
            'tagIconStyle' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tagIconShow','condition'=>'==','value'=> true],
                        ],
                    ],
                ],
            ],
            'tagIconColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tagIconShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-builder-tag svg {fill:{{tagIconColor}}; stroke:{{tagIconColor}} }'
                    ],
                ],  
            ],
            'tagIconHovColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tagIconShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-builder-tag svg:hover {fill:{{tagIconHovColor}}; stroke:{{tagIconHovColor}} }'
                    ],
                ],  
            ],
            'tagIconSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'16', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tagIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-builder-tag svg {height:{{tagIconSize}}; width:{{tagIconSize}};}'
                    ],
                ],
            ],
            'tagIconSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'10', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tagIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-builder-tag svg {margin-right:{{tagIconSpace}} }'
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
        }else{
            return $attributes;
        }
    }

    public function register() {
        register_block_type( 'ultimate-post/post-tag',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );
    }
    public function content($attr, $noAjax) {
        $block_name = 'post-tag';
        $wrapper_before = $wrapper_after = $content = '';

        $tag_list = get_the_tag_list('','');

        if ($tag_list) {
            $wrapper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').''.(isset($attr["align"])? ' align' .$attr["align"]:'').'">';
                $wrapper_before .= '<div class="ultp-block-wrapper">';
                    $content .= '<div class="ultp-builder-tag">';
                        if($attr["tagIconShow"]){
                            $content .= ultimate_post()->svg_icon(''.$attr["tagIconStyle"].'');
                        }
                        if ($attr['tagLabelShow']) {
                            $content .= '<div class="tag-builder-label">';
                                $content .= $attr['tagLabel'];
                            $content .= '</div>';
                        }
                        $content .= '<div class="tag-builder-content">';
                            $content .= $tag_list;
                        $content .= '</div>';
                    $content .= '</div>';
                $wrapper_after .= '</div>';
            $wrapper_after .= '</div>';
        }

        return $wrapper_before.$content.$wrapper_after;
    }
}