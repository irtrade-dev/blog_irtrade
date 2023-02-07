<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Next_Previous {
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
                Next Preview Settings
            ============================*/
            'layout' => [
                'type' => 'string',
                'default' => 'style1',
            ],
            'headingEnable' => [
                'type' => 'boolean',
                'default' => true,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'layout','condition'=>'!=','value'=='style1'],
                        ],
                    ],
                ],
            ],
            'imageShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'titleShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'dateShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'navDivider' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'iconShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'navItemBg' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-nav-block-prev,{{ULTP}} .ultp-nav-block-next { background:{{navItemBg}}; }'
                    ],
                ],
            ],
            'navItemHovBg' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-nav-block-prev:hover, {{ULTP}} .ultp-nav-block-next:hover { background:{{navItemHovBg}}; }'
                    ],
                ],
            ],
            'navItemPadd' => [
                'type' => 'object',
                'default' => (object)['lg' =>'15', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-nav-block-next, {{ULTP}} .ultp-nav-block-prev { padding:{{navItemPadd}}; }'
                    ],
                ],
            ],
            'navItemRad' => [
                'type' => 'object',
                'default' => (object)['lg' =>'4', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-nav-block-next ,{{ULTP}} .ultp-nav-block-prev { border-radius:{{navItemRad}}; }'
                    ],
                ],
            ],
            'navItemBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>1, 'width' =>(object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#e5e5e5','type' => 'solid'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-nav-block-next ,{{ULTP}} .ultp-nav-block-prev'
                    ],
                ],
            ],


            /*============================
                Navigation 
            ============================*/
            'titlePosition' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'prevContentAlign' => [
                'type' => 'string',
                'default' => "left",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'prevContentAlign','condition'=>'==','value'=>'left'],
                            (object)['key'=>'layout','condition'=>'!=','value'=>'style2'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-nav-block-prev  { text-align:{{prevContentAlign}}; justify-content:start;}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'prevContentAlign','condition'=>'==','value'=>'center'],
                            (object)['key'=>'layout','condition'=>'!=','value'=>'style2'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-nav-block-prev { text-align:{{prevContentAlign}}; justify-content:center;}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'prevContentAlign','condition'=>'==','value'=>'right'],
                            (object)['key'=>'layout','condition'=>'!=','value'=>'style2'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-nav-block-prev  { text-align:{{prevContentAlign}}; justify-content:end;}'
                    ],
                ],
            ],
            'nextContentAlign' => [
                'type' => 'string',
                'default' => "right",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'nextContentAlign','condition'=>'==','value'=>'left'],
                            (object)['key'=>'layout','condition'=>'!=','value'=>'style2'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-nav-block-next  { text-align:{{nextContentAlign}}; justify-content:start;}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'nextContentAlign','condition'=>'==','value'=>'center'],
                            (object)['key'=>'layout','condition'=>'!=','value'=>'style2'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-nav-block-next  { text-align:{{nextContentAlign}}; justify-content:center;}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'nextContentAlign','condition'=>'==','value'=>'right'],
                            (object)['key'=>'layout','condition'=>'!=','value'=>'style2'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-nav-block-next  { text-align:{{nextContentAlign}}; justify-content:end;}'
                    ],
                ],
            ],
            'prevHeadingSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'0', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-nav .ultp-prev-title, {{ULTP}} .ultp-block-nav .ultp-next-title  { margin:{{prevHeadingSpace}}; }'
                    ],
                ],
            ],
            // Previous
            'prevHeadText' => [
                'type' => 'string',
                'default' => 'Previous Post',
            ],
            'prevHeadColor' => [
                'type' => 'string',
                'default' => '#888',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-nav .ultp-prev-title, {{ULTP}} .ultp-block-nav .ultp-next-title { color:{{prevHeadColor}}; }'
                    ],
                ],
            ],
            'prevHeadHovColor' => [
                'type' => 'string',
                'default' => '#444',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-nav .ultp-prev-title:hover, {{ULTP}} .ultp-block-nav .ultp-next-title:hover { color:{{prevHeadHovColor}}; }'
                    ],
                ],
            ],
            'prevHeadTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => '','size' => (object)['lg' =>14, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px'], 'transform' => 'capitalize', 'decoration' => 'none','family'=>''],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-nav .ultp-prev-title, {{ULTP}} .ultp-block-nav .ultp-next-title'
                    ],
                ],
            ],
            // Next
            'nextHeadText' => [
                'type' => 'string',
                'default' => 'Next Post',
            ],
            'prevHeadAlign' => [
                'type' => 'string',
                'default' => "left",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'titlePosition','condition'=>'==','value'=>false],
                        ],
                        'selector'=>'{{ULTP}} .ultp-prev-title { text-align:{{prevHeadAlign}}; }'
                    ],
                ],
            ],
            'nextHeadAlign' => [
                'type' => 'string',
                'default' => "right",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'titlePosition','condition'=>'==','value'=>false],
                        ],
                        'selector'=>'{{ULTP}} .ultp-next-title { text-align:{{nextHeadAlign}}; }'
                    ],
                ],
            ],


            /*============================
                Title Settings 
            ============================*/
            'titleColor' => [
                'type' => 'string',
                'default' => '#333',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'titleShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-nav-inside .ultp-nav-text-content .ultp-nav-title { color:{{titleColor}}; }'
                    ],
                ],
            ],
            'titleHoverColor' => [
                'type' => 'string',
                'default' => '#000',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'titleShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-nav-inside .ultp-nav-text-content .ultp-nav-title:hover { color:{{titleHoverColor}}; }'
                    ],
                ],
            ],
            'titleTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>16, 'unit' =>'px'],'height' => (object)['lg' =>22, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'titleShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-nav-inside .ultp-nav-text-content .ultp-nav-title'
                    ],
                ],
            ],
            'titleSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'0', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-nav-inside .ultp-nav-text-content {gap:{{titleSpace}}}'
                    ],
                ],
            ],
            'titleSpaceX' => [
                'type' => 'object',
                'default' => (object)['lg' =>'15', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'imageShow','condition'=>'==','value'=>true]
                        ],
                        'selector'=>'{{ULTP}} .ultp-nav-block-next .ultp-nav-text-content {margin-right:{{titleSpaceX}}} {{ULTP}} .ultp-nav-block-prev .ultp-nav-text-content { margin-left:{{titleSpaceX}}}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'imageShow','condition'=>'==','value'=>true],
                            (object)['key'=>'layout','condition'=>'==','value'=>'style3'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-nav-text-content {margin-left:{{titleSpaceX}}} {{ULTP}} .ultp-nav-block-next .ultp-nav-text-content {margin-right:0}'
                    ],
                ],
            ],


            /*============================
                Date Settings
            ============================*/
            'dateColor' => [
                'type' => 'string',
                'default' => '#888',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'dateShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-nav-inside .ultp-nav-text-content .ultp-nav-date { color:{{dateColor}}; }'
                    ],
                ],
            ],
            'dateHoverColor' => [
                'type' => 'string',
                'default' => '#000',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'dateShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-nav-inside .ultp-nav-text-content .ultp-nav-date:hover { color:{{dateHoverColor}}; }'
                    ],
                ],
            ],
            'dateTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>14, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                            'depends' => [
                        (object)['key'=>'dateShow','condition'=>'==','value'=>true],
                    ],
                        'selector'=>'{{ULTP}} .ultp-nav-inside .ultp-nav-text-content .ultp-nav-date'
                    ],
                ],
            ],
            'datePosition' => [
                'type' => 'boolean',
                'default' => true,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'dateShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-nav-text-content .ultp-nav-date{ order:2; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'dateShow','condition'=>'==','value'=>true],
                            (object)['key'=>'layout','condition'=>'==','value'=>'style3'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-nav-text-content .ultp-nav-date{ order:0; }'
                    ],
                ], 
            ],


            /*============================
                Image Settings
            ============================*/
            'navImgWidth' => [
                'type' => 'object',
                'default' => (object)['lg' =>'75', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-nav-inside .ultp-nav-img img{width:{{navImgWidth}}}'
                    ],
                ],
            ],
            'navImgHeight' => [
                'type' => 'object',
                'default' => (object)['lg' =>'75', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-nav-inside .ultp-nav-img img{height:{{navImgHeight}}}'
                    ],
                ],
            ],
            'navImgBorderRad' => [
                'type' => 'object',
                'default' => (object)['lg' =>'4', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-nav-img img  { border-radius:{{navImgBorderRad}}; }'
                    ],
                ],
            ],

            /*============================
                Divider Setting/Style
            ============================*/
            'dividerColor' => [
                'type' => 'string',
                'default' => '#e5e5e5',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'dividerBorderShape','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-divider {background-color:{{dividerColor}};width:2px;}'
                    ],
                ],
            ],
            'dividerSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'10', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-nav {gap:{{dividerSpace}}}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'dividerBorderShape','condition'=>'==','value'=>true],
                        ],
                    'selector'=>'{{ULTP}} .ultp-block-nav {gap:{{dividerSpace}}}'
                    ],
                ]
            ],
            'dividerBorderShape' => [
                'type' => 'boolean',
                'default' => true,
            ],

            
            /*============================
                Arrow Icon
            ============================*/
            'arrowIconStyle' => [
                'type' => 'string',
                'default' => 'Angle2'
            ],
            'arrowColor' => [
                'type' => 'string',
                'default' => '#959595',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-icon >  svg{ fill:{{arrowColor}}; }'
                    ],
                ],
            ],
            'arrowHoverColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-icon svg:hover { fill:{{arrowHoverColor}}; }'
                    ],
                ],
            ],
            'arrowIconSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'20', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-icon svg{width:{{arrowIconSize}}}'
                    ],
                ],
            ],
            'arrowIconSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'20', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'layout','condition'=>'!=','value'=>"style2"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-nav-block-prev .ultp-icon svg{margin-right: {{arrowIconSpace}}} {{ULTP}} .ultp-nav-block-next .ultp-icon svg{margin-left: {{arrowIconSpace}}}'
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
        register_block_type( 'ultimate-post/next-previous',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );
    }
    public function content($attr, $noAjax) {
        $block_name = 'next-previous';
        $wrapper_before = $wrapper_after = $content = $next_prev_img = '';

        if($attr["imageShow"]){
            $next_prev_img .= "next-prev-img";
        }

        $arrowLeft = '<span class="ultp-icon ultp-icon-'.$attr['arrowIconStyle'].'">'.ultimate_post()->svg_icon('left'.$attr['arrowIconStyle']).'</span>';
        $arrowRight = '<span class="ultp-icon ultp-icon-'.$attr['arrowIconStyle'].'">'.ultimate_post()->svg_icon('right'.$attr['arrowIconStyle']).'</span>';

        $wrapper_before .= '<div'.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').''.(isset($attr["align"])? ' align' .$attr["align"]:'').'">';
            $wrapper_before .= '<div class="ultp-block-wrapper">';
                $content .= '<div class="ultp-block-nav '.$next_prev_img.'">';
                    $content .= $this->renderHtml($attr, $arrowLeft, $arrowRight, true);
                    if ($attr['navDivider'] && $attr['dividerBorderShape']) {
                        $content .= '<span class="ultp-divider"></span>';
                    }
                    $content .= $this->renderHtml($attr, $arrowLeft, $arrowRight, false);
                $content .= '</div>';
            $wrapper_after .= '</div>';
        $wrapper_after .= '</div>';

        return $wrapper_before.$content.$wrapper_after;
    }

    public function renderHtml($attr, $arrowLeft, $arrowRight, $left) {
        $output = '';
        $post_data = $left ? get_previous_post() : get_next_post();
        if ($post_data) {
            $imageData = '<div class="ultp-nav-img">';
                $imageData .= ($attr['iconShow'] && $attr['layout'] == 'style2') ? ($left ? $arrowLeft : $arrowRight) : '';
                if (has_post_thumbnail($post_data->ID)) {
                    $imageData .= $attr['imageShow'] ? get_the_post_thumbnail($post_data->ID) : '';
                }
            $imageData .= '</div>';
            $output .= '<a class="'.($left ? 'ultp-nav-block-prev ultp-nav-prev-'.$attr['layout'] : 'ultp-nav-block-next ultp-nav-next-'.$attr['layout']).'" href="'.get_permalink($post_data->ID).'">';
                if ($attr['headingEnable'] && !$attr['titlePosition'] && ( $attr['layout'] == 'style2' )) { 
                    $output .= '<div class='.($left ? "ultp-prev-title" : "ultp-next-title").' >'.($left ? $attr['prevHeadText'] : $attr['nextHeadText']).'</div>';
                }
                if ($left && $attr['iconShow'] && $attr['layout'] != 'style2') {
                    $output .= $arrowLeft;
                }
                $output .= '<div class="ultp-nav-inside">';
                    if ($attr['headingEnable'] && !$attr['titlePosition'] && $attr['layout'] != 'style2') {
                        $output .= '<div class='.($left ? "ultp-prev-title" : "ultp-next-title").' >'.($left ? $attr['prevHeadText'] : $attr['nextHeadText']).'</div>';
                    }
                    $output .= '<div class="ultp-nav-inside-container">';
                        if ($left) {
                            $output .= $imageData;
                        }
                        if ($left == false && $attr['layout'] == 'style3' ) {
                            $output .= $imageData;
                        }
                        $output .= '<div class="ultp-nav-text-content">';
                            if ($attr['headingEnable'] && $attr['titlePosition']) {
                                $output .= '<div class='.($left ? "ultp-prev-title" : "ultp-next-title").' >'.($left ? $attr['prevHeadText'] : $attr['nextHeadText']).'</div>';
                            }
                            if ($attr['dateShow']) {
                                $output .= '<div class="ultp-nav-date">'.get_the_date(get_option('date_format'), $post_data->ID).'</div>';
                            }
                            if ($attr['titleShow']) {
                                $output .= '<div class="ultp-nav-title">'.get_the_title($post_data->ID).'</div>';
                            }
                        $output .= '</div>';
                        if ($left == false && $attr['layout'] != 'style3' ) {
                            $output .= '<span>'.$imageData.'</span>';
                        }
                    $output .= '</div>';
                $output .= '</div>';
                if ($left == false && $attr['iconShow'] && $attr['layout'] != 'style2') {
                    $output .= $arrowRight;
                }
            $output .= '</a>';
        }
        return $output;
    }

}