<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Social_Share {
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function get_attributes($default = false) {
        $attributes = array(
            'blockId' => [
                'type' => 'string',
                'default' => '',
            ],
            'repetableField' => [
                'type'=> 'array',
                'fields' => [
                    'type' => [
                        'type' => 'string',
                        'default' => 'facebook',
                    ],
                    'enableLabel' => [
                        'type' => 'boolean',
                        'default' => true,
                    ],
                    'label' => [
                        'type' => 'string',
                        'default' => 'Share',
                    ],
                    'iconColor' => [
                        'type' => 'string',
                        'default' => '#fff',
                        'style' => [
                            (object)[
                                'selector'=>'{{ULTP}} {{REPEAT_CLASS}}.ultp-post-share-item a .ultp-post-share-item-icon svg { fill:{{iconColor}} !important; } {{ULTP}} {{REPEAT_CLASS}}.ultp-post-share-item  .ultp-post-share-item-label { color:{{iconColor}} }'
                            ],
                        ],
                    ],
                    'iconColorHover' => [
                        'type' => 'string',
                        'default' => '#d2d2d2',
                        'style' => [
                            (object)[
                                'selector'=>'{{ULTP}} {{REPEAT_CLASS}}.ultp-post-share-item:hover .ultp-post-share-item-icon svg { fill:{{iconColorHover}} !important; } {{ULTP}} {{REPEAT_CLASS}}.ultp-post-share-item:hover  .ultp-post-share-item-label{ color:{{iconColorHover}} }'
                            ],
                        ],
                    ],
                    'shareBg' => [
                        'type' => 'string',
                        'default' => '#7a49ff',
                        'style' => [
                            (object)[
                                'selector'=>'{{ULTP}} {{REPEAT_CLASS}}.ultp-post-share-item a { background-color: {{shareBg}}; }'
                            ],
                        ],
                    ],
                    'shareBgHover' => [
                        'type' => 'object',
                        'default' => '#7a49ff',
                        'style' => [
                            (object)[
                                'selector'=>'{{ULTP}} {{REPEAT_CLASS}}.ultp-post-share-item a:hover { background-color:{{shareBgHover}}; }'
                            ],
                        ],
                    ],
                ],
                'default' => [
                    [
                        'type' => 'facebook',
                        'enableLabel' => true,
                        'label' => 'Facebook',
                        'iconColor' => '#fff',
                        'iconColorHover' => '#d2d2d2',
                        'shareBg' => '#4267B2',
                        'bgHoverColor' => '#f5f5f5'
                    ],
                    [
                        'type' => 'twitter',
                        'enableLabel' => true,
                        'label' => 'Twitter', 
                        'iconColor' => '#fff',
                        'iconColorHover' => '#d2d2d2',
                        'shareBg' => '#1DA1F2',
                        'bgHoverColor' => '#f5f5f5'
                        
                    ],
                    [
                        'type' => 'pinterest',
                        'enableLabel' => true,
                        'label' => 'Pinterest', 
                        'iconColor' => '#fff',
                        'iconColorHover' => '#d2d2d2',
                        'shareBg' => '#E60023',
                        'bgHoverColor' => '#f5f5f5'
                    ],
                    [
                        'type' => 'linkedin',
                        'enableLabel' => true,
                        'label' => 'Linkedin', 
                        'iconColor' => '#fff',
                        'iconColorHover' => '#d2d2d2',
                        'shareBg' => '#0A66C2',
                        'bgHoverColor' => '#f5f5f5'
                    ],
                    [
                        'type' => 'mail',
                        'enableLabel' => true,
                        'label' => 'Mail', 
                        'iconColor' => '#fff',
                        'iconColorHover' => '#d2d2d2',
                        'shareBg' => '#EA4335',
                        'bgHoverColor' => '#f5f5f5'
                    ],
                ]
            ],
            /*============================
                Social Share item style 
            ============================*/
            'shareItemTypo' => [
                'type' => 'object',
                'default' =>  (object)['openTypography' => 1,'size' => (object)['lg' => '18', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px'],'decoration' => 'none', 'transform' => '', 'family'=>'','weight'=>''],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-share-item a .ultp-post-share-item-label'
                    ],
                ],
            ],
            'shareIconSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'20', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-share-item .ultp-post-share-item-icon svg { height:{{shareIconSize}} !important; width:{{shareIconSize}} !important;}'
                    ],
                ],
            ],
            'shareBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#c3c3c3','type' => 'solid' ],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-share-item a'
                    ],
                ],
            ],
            'shareRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-share-item a { border-radius:{{shareRadius}}; }'
                    ],
                ],
            ],
            'disInline' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'itemPadding' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '15','bottom' => '15', 'left'=>'15','right'=>'15', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-share-item-inner-block .ultp-post-share-item a { padding:{{itemPadding}} !important; }'
                    ],

                ],
            ],
            'itemSpacing' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '5','bottom' => '5', 'left'=>'5','right'=>'5', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-share-item-inner-block .ultp-post-share-item {margin:{{itemSpacing}} !important; }'
                    ],
                ],
            ],
            "itemContentAlign" => [
                "type" => "string",
                "default" => "flex-start",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'disInline','condition'=>'==','value'=> true],
                            (object)['key'=>'enableSticky','condition'=>'==','value'=> false],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share-layout {justify-content:{{itemContentAlign}}; width: 100%;}'
                    ],
                ], 
            ],
            'itemAlign' => [
                'type' => 'string',
                'default' =>  'left',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'disInline','condition'=>'==','value'=> false],
                            (object)['key'=>'enableSticky','condition'=>'==','value'=> false],
                        ],
                        'selector' => '{{ULTP}} .ultp-post-share { text-align:{{itemAlign}}; }'
                    ],
                ]
            ],
            
            /*============================
                Post Social Share Label Style
            ============================*/
            'shareLabelShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'labelIconSize' => [
                'type' => 'object',
                'default' => (object)['lg' => '24'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share-icon-section svg { height: {{labelIconSize}}px; width: {{labelIconSize}}px; }'
                    ],
                ],
            ],
            'labelIconSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '0','bottom' => '0', 'left'=>'0','right'=>'15', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-share-count-section { margin:{{labelIconSpace}} }'
                    ],
                ],
            ],
            'shareLabelIconColor' => [
                'type' => 'string',
                'default' => '#002dff',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                            (object)['key'=>'shareLabelStyle','condition'=>'==','value'=>"style2"],
                        ],
                        'selector' => '{{ULTP}} .ultp-post-share-icon-section svg{ fill:{{shareLabelIconColor}}; }'
                    ],
                ],
            ],
            'shareLabelStyle' => [
                'type' => 'string',
                'default' => 'style1',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                            (object)['key'=>'shareLabelStyle','condition'=>'==','value'=>"style1"],
                        ],
                        'selector' => '{{ULTP}} .ultp-post-share-layout{display: flex; align-items:center;}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                            (object)['key'=>'shareLabelStyle','condition'=>'==','value'=>"style2"],
                        ],
                        'selector' => '{{ULTP}} .ultp-post-share-layout{display: flex; align-items:center;}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                            (object)['key'=>'shareLabelStyle','condition'=>'==','value'=>"style3"],
                        ],
                        'selector' => '{{ULTP}} .ultp-post-share-layout{display: inline-block}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                            (object)['key'=>'shareLabelStyle','condition'=>'==','value'=>"style4"],
                        ],
                        'selector' => '{{ULTP}} .ultp-post-share-layout{display: flex}'
                    ],
                ],
            ],
            'shareCountShow' => [
                'type' => 'boolean',
                'default' => true,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelStyle','condition'=>'==','value'=>'style1'],
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                        ],
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelStyle','condition'=>'==','value'=>'style3'],
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                        ],
                    ],
                ],
            ],
            'Labels1BorderColor' => [
                'type' => 'string',
                'default' => '#',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=> true],
                            (object)['key'=>'shareLabelStyle','condition'=>'==','value'=>'style1'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share-count-section-style1, {{ULTP}} .ultp-post-share-count-section-style1:after {border-color:{{Labels1BorderColor}} !important; }'
                    ],
                ],
            ],
            'shareCountColor' => [
                'type' => 'string',
                'default' => '#',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelStyle','condition'=>'==','value'=>'style1'],
                            (object)['key'=>'shareCountShow','condition'=>'==','value'=>true],
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share-count {color:{{shareCountColor}} !important; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelStyle','condition'=>'==','value'=>'style3'],
                            (object)['key'=>'shareCountShow','condition'=>'==','value'=>true],
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share-count {color:{{shareCountColor}} !important; }'
                    ],

                ],
            ],
            'shareCountTypo' => [
                'type' => 'object',
                'default' =>  (object)['openTypography' => 1,'size' => (object)['lg' => '14', 'unit' => 'px'], 'height' => (object)['lg' => '20', 'unit' => 'px'],'decoration' => 'none', 'transform' => '', 'family'=>'','weight'=>''],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelStyle','condition'=>'==','value'=>'style1'],
                            (object)['key'=>'shareCountShow','condition'=>'==','value'=>true],
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share-count'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelStyle','condition'=>'==','value'=>'style3'],
                            (object)['key'=>'shareCountShow','condition'=>'==','value'=>true],
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share-count'
                    ],
                ],
            ],
            'shareCountLabel' => [
                'type' => 'string',
                'default' => 'Shares',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=> true],
                            (object)['key'=>'shareLabelStyle','condition'=>'!=','value'=>'style2'],
                        ],
                    ],
                ],
            ],
            'shareLabelColor' => [
                'type' => 'string',
                'default' => '#',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelStyle','condition'=>'!=','value'=>'style2'],
                            (object)['key'=>'shareCountLabel','condition'=>'!=','value'=>''],
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share-count-section .ultp-post-share-label {color:{{shareLabelColor}}; }'
                    ],
                ],
            ],
            'shareLabelTypo' => [
                'type' => 'object',
                'default' =>  (object)['openTypography' => 0,'size' => (object)['lg' => '12', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px'],'decoration' => 'none', 'transform' => '', 'family'=>'','weight'=>''],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelStyle','condition'=>'!=','value'=>'style2'],
                            (object)['key'=>'shareCountLabel','condition'=>'!=','value'=>''],
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share-label'
                    ],
                ],
            ],
            'shareLabelBackground' => [
                'type' => 'string',
                'default' => '#fff',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share-count-section, {{ULTP}} .ultp-post-share-count-section-style1::after {background-color:{{shareLabelBackground}}; }'
                    ],
                ],
            ],
            'shareLabelBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>1, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#c3c3c3','type' => 'solid' ],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                            (object)['key'=>'shareLabelStyle','condition'=>'!=','value'=>'style1'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share-count-section, {{ULTP}} .ultp-post-share-count-section-style1::after'
                    ],
                ],
            ],
            'shareLabelRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share-count-section { border-radius:{{shareLabelRadius}}; }'
                    ],
                ],
            ],
            'shareLabelPadding' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '10','bottom' => '10', 'left'=>'25','right'=>'25', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'shareLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share-count-section { padding:{{shareLabelPadding}}; }'
                    ],
                ],
            ],


            /*============================
                Post Social Share sticky position Style
            ============================*/
            'enableSticky' => [
                'type' => 'boolean',
                'default' => false,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'enableSticky','condition'=>'==','value'=> false],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share-layout {display: flex; align-items:center;}'
                    ],
                ]
            ],
            'itemPosition' => [
                'type' => 'string',
                'default' => 'bottom',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'enableSticky','condition'=>'==','value'=> true],
                        ],
                    ],
                ]
            ],
            'stickyLeftOffset' => [
                'type' => 'string',
                'default' => "20",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'enableSticky','condition'=>'==','value'=>true],
                            (object)['key'=>'itemPosition','condition'=>'!=','value'=>"right"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share .ultp-post-share-layout { position:fixed;left:{{stickyLeftOffset}}px;}'
                    ],
                ],
            ],
            'stickyRightOffset' => [
                'type' => 'string',
                'default' => "20",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'enableSticky','condition'=>'==','value'=>true],
                            (object)['key'=>'itemPosition','condition'=>'==','value'=>"right"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share .ultp-post-share-layout { position:fixed; right:{{stickyRightOffset}}px;}'
                    ],
                ],
            ],
            
            'stickyTopOffset' => [
                'type' => 'string',
                'default' => "20",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'enableSticky','condition'=>'==','value'=>true],
                            (object)['key'=>'itemPosition','condition'=>'!=','value'=>'bottom'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share .ultp-post-share-layout { position:fixed;top:{{stickyTopOffset}}px;z-index:9999999;}'
                    ],
                ],
            ],
            'stickyBottomOffset' => [
                'type' => 'string',
                'default' => "20",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'itemPosition','condition'=>'==','value'=>'bottom'],
                            (object)['key'=>'enableSticky','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-share .ultp-post-share-layout { position:fixed;bottom:{{stickyBottomOffset}}px; z-index:9999999;}'
                    ],
                ],
            ],
            'resStickyPost' => [
                'type' => 'boolean',
                'default' => false,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'enableSticky','condition'=>'==','value'=>true],
                        ],
                    ],
                ],
            ],
            'floatingResponsive' => [
                'type' => 'string',
                'default' => "600",
                'style' => [
                    // (object)[
                    //     'depends' => [
                    //         (object)['key'=>'enableSticky','condition'=>'==','value'=>true],
                    //         (object)['key'=>'resStickyPost','condition'=>'==','value'=>true],
                    //     ],
                    //     'selector'=>'@media only screen and (max-width: {{floatingResponsive}}px) {
                    //         .ultp-post-share-layout {
                    //             bottom: 0px !important; top: auto !important; left: 0px !important; right: auto !important;
                    //         }
                    //     }'
                    // ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'resStickyPost','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'@media only screen and (max-width: {{floatingResponsive}}px) {
                            .ultp-post-share-layout {
                                position: unset !important;
                                display: flex !important;
                                justify-content: center;
                            }
                            .ultp-post-share-item-inner-block {
                                display: flex !important;
                            }
                            .ultp-post-share-count-section-style1:after {
                                bottom: auto !important;
                                transform: rotate(44deg) !important;
                                top: 40% !important;
                                right: -8px !important;
                            }}
                            '
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
        register_block_type( 'ultimate-post/post-social-share',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );
    }

    public function share_link($key = 'facebook', $post_link = '') {
        $shareLink = [
            'facebook' => 'http://www.facebook.com/sharer.php?u='.$post_link,
            'twitter' => 'http://twitter.com/share?url='.$post_link,
            'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url='.$post_link,
            'pinterest' => 'http://pinterest.com/pin/create/link?url='.$post_link,
            'whatsapp' => 'https://api.whatsapp.com/send?text='.$post_link,
            'messenger' => 'https://www.facebook.com/dialog/send?app_id=1904103319867886&amp;link='.$post_link.'&amp;redirect_uri='.$post_link,
            'mail' => 'mailto:?body='.$post_link,
            'reddit' => 'https://www.reddit.com/submit?url='.$post_link,
            'skype' => 'https://web.skype.com/share?url='.$post_link,
        ];
        return $shareLink[$key];
    }
    
    public function content($attr, $noAjax) {
        $block_name = 'post_share';
        $wrapper_before = $wrapper_after = $wrapper_content = '';

        $post_id = get_the_ID();
        $post_link = esc_url(home_url($_SERVER['REQUEST_URI']));
        $total_share = get_post_meta($post_id, 'share_count', true);
        $total_share = $total_share ? $total_share : 0;
        
        $wrapper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').''.(isset($attr["align"])? ' align' .$attr["align"]:'').'">';
            $wrapper_before .= '<div class="ultp-block-wrapper">';
                $wrapper_content .= '<div class="ultp-post-share">';
                    $wrapper_content .= '<div class="ultp-post-share-layout ultp-inline-'.($attr["disInline"]?'true':'false').'">';
                        if ($attr["shareLabelShow"]) {
                            $wrapper_content .= '<div class="ultp-post-share-count-section ultp-post-share-count-section-'.$attr["shareLabelStyle"].'">';
                                if ($attr["shareLabelStyle"] != 'style2' && $attr["shareCountShow"]) {
                                    $wrapper_content .= '<span class="ultp-post-share-count">'.$total_share.'</span>';
                                }
                                if ($attr["shareLabelStyle"] == 'style2') {
                                    $wrapper_content .= '<span class="ultp-post-share-icon-section">'.ultimate_post()->svg_icon('share').'</span>';
                                }
                                if ($attr["shareLabelStyle"] != 'style2' && $attr["shareCountLabel"]) {
                                    $wrapper_content .= '<span class="ultp-post-share-label">'.$attr["shareCountLabel"].'</span>';
                                }
                            $wrapper_content .= '</div>';
                        }
                        $wrapper_content .= '<div class="ultp-post-share-item-inner-block" postId="'.$post_id.'" count="'.$total_share.'">';

                            foreach ($attr["repetableField"] as $key => $value) {
                                $wrapper_content .= '<div class="ultp-post-share-item ultp-repeat-'.$key.' ultp-social-'.$value["type"].'">';
                                    $wrapper_content .= '<a href="javascript:" class="ultp-post-share-item-'.$value["type"].'" url="'.$this->share_link($value['type'], $post_link).'">';
                                        $wrapper_content .= '<span class="ultp-post-share-item-icon">'.ultimate_post()->svg_icon($value['type']).'</span>';
                                        $wrapper_content .= ''.$value['enableLabel'] ? '<span class="ultp-post-share-item-label">'.$value['label'].'</span>' : "".' ';
                                    $wrapper_content .= '</a>';
                                $wrapper_content .= '</div>';
                            }
                        $wrapper_content .= '</div>';
                    $wrapper_content .= '</div>';
                $wrapper_content .= '</div>';
            $wrapper_after .= '</div>';
        $wrapper_after .= '</div>';

        return $wrapper_before.$wrapper_content.$wrapper_after;
    }
}