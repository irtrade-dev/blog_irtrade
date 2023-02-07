<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Category {
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
                Post Category Setting
            ============================*/
            'catLabelShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'catIconShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            
            'catColor' => [
                'type' => 'string',
                'default' => '#333',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .cat-builder-content a, {{ULTP}} .cat-builder-content {color:{{catColor}};}'
                    ],
                ],
            ],
            'catBgColor' => [
                'type' => 'object',
                'default' => [],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-category-list'
                    ],
                ],
            ],
            'catItemBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>1, 'width' =>(object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#e2e2e2','type' => 'solid'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-category-list'
                    ],
                ],
            ],
            'catRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'2', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-category-list { border-radius:{{catRadius}}; }'
                    ],
                ],
            ],
            'catHovColor' => [
                'type' => 'string',
                'default' => '#fff',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .cat-builder-content a:hover { color:{{catHovColor}}; }'
                    ],
                ],
            ],
            'catBgHovColor' => [
                'type' => 'object',
                'default' => (object)['openColor' => 1, 'type' => 'color', 'color' => '#e2e2e2'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-category-list:hover'
                    ],
                ],
            ],
            'catItemHoverBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' =>(object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#323232','type' => 'solid'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-category-list:hover'
                    ],
                ],
            ],
            'catHoverRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-category-list:hover { border-radius:{{catHoverRadius}}; }'
                    ],
                ],
            ],
            'catTypo' => [
                'type' => 'object',
                'default' =>  (object)['openTypography' => 0,'size' => (object)['lg' => '', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px'],'decoration' => 'none', 'transform' => '', 'family'=>'','weight'=>''],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-category-list'
                    ],
                ],
            ],
            'catSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'8', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-category-list:not(:first-child) {margin-left:{{catSpace}}}'
                    ],
                ],
            ],
            'catItemPad' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '3','bottom' => '3','left' => '7','right' => '7', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-category-list { padding:{{catItemPad}} }'
                    ],
                ],
            ],
            'catAlign' => [
                'type' => 'object',
                'default' => (object)[],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-category {justify-content:{{catAlign}}}'
                    ],
                ],
            ],
            

            /*============================
                Categories Label Settings
            ============================*/
            'catLabel' => [
                'type' => 'string',
                'default' => 'Category : ',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catLabelShow','condition'=>'==','value'=>true],
                        ],
                    ],
                ],
            ],
            'catLabelColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .cat-builder-label {color:{{catLabelColor}};}'
                    ],
                ],
            ],
            'catLabelTypo' => [
                'type' => 'object',
                'default' =>  [],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .cat-builder-label'
                    ],
                ],
            ],
            'catLabelSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'8', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .cat-builder-label {margin-right:{{catLabelSpace}}}'
                    ],
                ],
            ],
            'catLabelBgColor' => [
                'type' => 'object',
                'default' => [],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .cat-builder-label'
                    ],
                ],
            ],
            'catLabelBorder' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 0,'size' => (object)['lg' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .cat-builder-label'
                    ],
                ],
            ],
            'catLabelRadius' => [
                'type' => 'object',
                'default' => [],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .cat-builder-label { border-radius:{{catLabelRadius}}; }'
                    ],
                ],
            ],
            'catLabelPad' => [
                'type' => 'object',
                'default' => (object)[],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .cat-builder-label { padding:{{catLabelPad}} }'
                    ],
                ],
            ],


            /*============================
                Categories Icon Style
            ============================*/
            'catIconStyle' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catIconShow','condition'=>'==','value'=> true],
                        ],
                    ],
                ],
            ],
            'catIconColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-builder-category  svg { fill:{{catIconColor}}; stroke:{{catIconColor}} }'
                    ],
                ],  
            ],
            'catIconHovColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catIconShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-builder-category  svg:hover { fill:{{catIconHovColor}}; stroke:{{catIconHovColor}} }'
                    ],
                ],  
            ],
            'catIconSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'16', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-builder-category svg { height:{{catIconSize}}; width:{{catIconSize}} }'
                    ],
                ],
            ],
            'catIconSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'10', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-builder-category svg {margin-right:{{catIconSpace}} }'
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
        register_block_type( 'ultimate-post/post-category',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );
    }

    public function content($attr, $noAjax) {
        $block_name = 'post-category';
        $wrapper_before = $wrapper_after = $content = '';

        $categories = get_the_category();
        if (!empty($categories)) {
            $wrapper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').''.(isset($attr["align"])? ' align' .$attr["align"]:'').'">';
                $wrapper_before .= '<div class="ultp-block-wrapper">';
                    $content .= '<div class="ultp-builder-category">';
                        if($attr["catIconShow"]){
                            $content .= ultimate_post()->svg_icon(''.$attr["catIconStyle"].'');
                        }
                        if ($attr['catLabelShow'] ) { 
                            $content .= '<div class="cat-builder-label">'.$attr['catLabel'].'</div>';
                        }
                        $content .= '<div class="cat-builder-content">';
                            foreach ($categories as $key => $category) {
                                $content .= ($key>0 ?', ':'').'<a class="ultp-category-list" href="'.get_term_link($category->term_id).'">'.$category->name.'</a>';
                            }
                        $content .= '</div>';
                    $content .= '</div>';
                $wrapper_after .= '</div>';
            $wrapper_after .= '</div>';
        }

        return $wrapper_before.$content.$wrapper_after;
    }
}