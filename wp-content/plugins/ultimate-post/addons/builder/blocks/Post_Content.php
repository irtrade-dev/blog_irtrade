<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Content {
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
                Post Content Settings
            ============================*/
            'inheritWidth' => [
                'type' => 'boolean',
                'default' => false,
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-wrapper .ultp-builder-content { margin:0 auto; max-width: 700px; width:100%;}'
                    ],
                ],
            ],
            'contentWidth' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'ulg' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'inheritWidth','condition'=>'!=','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-wrapper .ultp-builder-content {margin: 0 auto; max-width:{{contentWidth}}; width:100%}'
                    ],
                ],
            ],
            'descColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-wrapper .ultp-builder-content, {{ULTP}} .ultp-block-wrapper .ultp-builder-content p {color:{{descColor}} !important;}'
                    ],
                ],
            ],
            'descTypo' => [
                'type' => 'object',
                'default' =>  (object)['openTypography' => 0,'size' => (object)['lg' => '14', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px'],'decoration' => 'none', 'transform' => '', 'family'=>'','weight'=>''],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-wrapper .ultp-builder-content, html :where(.editor-styles-wrapper) {{ULTP}} .ultp-block-wrapper .ultp-builder-content p'
                    ],
                ],
            ],
            "contentAlign" => [
                "type" => "string",
                "default" => "0 auto",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'inheritWidth','condition'=>'!=','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-wrapper .ultp-builder-content {margin:{{contentAlign}} }'
                    ],
                ], 
            ],

            
            /*============================
                Drop Cap Settings
            ============================*/
            'showCap' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'firstCharSize' => [
                'type' => 'string',
                'default' => '110',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'showCap','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-wrapper > .ultp-builder-content > p:first-child::first-letter {font-size:{{firstCharSize}}px;float:left;}'
                    ],
                ],
            ],
            'firstCharXSpace' => [
                'type' => 'string',
                'default' => '25',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'showCap','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-wrapper > .ultp-builder-content > p:first-child::first-letter {margin-right:{{firstCharXSpace}}px;}'
                    ],
                ],
            ],
            'firstCharYSpace' => [
                'type' => 'string',
                'default' => '100',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'showCap','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-wrapper > .ultp-builder-content > p:first-child::first-letter {line-height:{{firstCharYSpace}}px;}'
                    ],
                ],
            ],
            'firstLatterColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'showCap','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-wrapper > .ultp-builder-content > p:first-child::first-letter {color:{{firstLatterColor}};}'
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
        register_block_type( 'ultimate-post/post-content',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );
    }
    public function content($attr, $noAjax) {
        $block_name = 'post-content';
        $wrapper_before = $wrapper_after = $content = '';
        
        $post_id = get_the_ID();
        $post_content = get_the_content();
        $post_type = get_post_type();
        if ($post_type != 'ultp_builder' && $post_type != 'revision') {
            $post_content = apply_filters('the_content', $post_content);
            $post_content = str_replace(']]>', ']]&gt;', $post_content);
        }
        if ($post_content) {
            $wrapper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').''.(isset($attr["align"])? ' align' .$attr["align"]:'').'">';
                $wrapper_before .= '<div class="ultp-block-wrapper">';
                    $content .= '<div class="ultp-builder-content" data-postid="'.$post_id.'">';
                        $content .= $post_content;
                    $content .= '</div>';
                $wrapper_after .= '</div>';
            $wrapper_after .= '</div>';
        }
        return $wrapper_before.$content.$wrapper_after;
    }
}