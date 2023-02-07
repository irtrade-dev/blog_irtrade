<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Slider_2 {

    public function __construct() {
        add_action('init', array($this, 'register'));
    }

    public function get_attributes($default = false) {

        $attributes = array(
            'blockId' => [
                'type' => 'string',
                'default' => '',
            ],
            'previewImg' => [
                'type' => 'string',
                'default' => '',
            ],

            
            /*============================
                General Setting
            ============================*/
            'slidesToShow' => [
                'type' => 'object',
                'default' => (object)['lg' =>'1', 'sm' =>'1', 'xs' =>'1'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'fade','condition'=>'!=','value'=> true],
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide2"],
                        ],
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide3"],
                        ],
                    ],
                ],
            ],
            'autoPlay' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'height' => [
                'type' => 'object',
                'default' => (object)['lg' =>'550', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-items-wrap .ultp-block-slider-wrap { height: {{height}}; }'
                    ],
                ],
            ],
            'slidesCenterPadding' => [
                'type' => 'object',
                'default' => (object)['lg'=>'160', 'sm'=>'100', 'xs'=>'50',],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide2"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide3"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide5"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide6"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide8"],
                        ],
                    ],
                ]
            ],
            'allItemScale' => [
                'type' => 'object',
                'default' => (object)['lg'=>'.9'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide4"],
                        ],
                        'selector' => '{{ULTP}} .slick-slide { transition: .3s; transform: scale({{allItemScale}}) }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide7"],
                        ],
                        'selector' => '{{ULTP}} .slick-slide { transition: .3s; transform: scale({{allItemScale}}) }'
                    ],
                ]  
            ],
            'centerItemScale' => [
                'type' => 'object',
                'default' => (object)['lg'=>'1.12'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide4"],
                        ],
                        'selector' => '{{ULTP}} .slick-center { transition: .3s; transform: scale({{centerItemScale}}) !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide7"],
                        ],
                        'selector' => '{{ULTP}} .slick-center { transition: .3s; transform: scale({{centerItemScale}}) !important; }'
                    ],
                ]  
            ],
            'slideSpeed' => [
                'type' => 'string',
                'default' => '3000',
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'autoPlay','condition'=>'==','value'=>true],
                        ]
                    ],
                ]
            ],
            'sliderGap' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'layout','condition' => '!=','value' => 'slide2']
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-items-wrap .slick-slide>div{ padding: 0 {{sliderGap}}px; }{{ULTP}} .ultp-block-items-wrap .slick-list{ margin: 0 -{{sliderGap}}px; }'
                    ],
                ],
            ],
            'dots' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'arrows' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'preLoader' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'fade' => [
                'type' => 'boolean',
                'default' => false,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'layout','condition' => '==','value' => 'slide2'],
                        ],
                    ],
                    (object)[
                        'depends' => [
                            (object)['key' => 'layout','condition' => '==','value' => 'slide5'],
                        ],
                    ],
                    (object)[
                        'depends' => [
                            (object)['key' => 'layout','condition' => '==','value' => 'slide6'],
                        ],
                    ],
                    (object)[
                        'depends' => [
                            (object)['key' => 'layout','condition' => '==','value' => 'slide8'],
                        ],
                    ],
                ],
            ],
            'titleShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'titleStyle' => [ 
                'type' => 'string',
                'default' => 'none',
            ],
            'headingShow' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'excerptShow' => [
                'type' => 'boolean',
                'default' => false
            ],
            'catShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'metaShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'readMore' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'contentTag' => [
                'type' => 'string',
                'default' => 'div',
            ],
            'openInTab' => [
                'type' => 'boolean',
                'default' => false,
            ],


            /*============================
                Query Setting
            ============================*/
            'layout' => [
                'type' => 'string',
                'default' => 'slide1',
            ],
            'queryQuick' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryType','condition' => '!=','value' => array('customPosts', 'posts', 'archiveBuilder')]
                        ]
                    ],
                ],
            ],
            'queryNumPosts' => [
                'type' => 'object',
                'default' => (object)['lg'=>5,'sm'=>5,'xs'=>5],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryType','condition' => '!=','value' => array('customPosts', 'posts')]
                        ]
                    ],
                ],
            ],
            'queryNumber' => [
                'type' => 'string',
                'default' => 5,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryType','condition' => '!=','value' => array('customPosts', 'posts', 'archiveBuilder')]
                        ]
                    ],
                ],
            ],
            'queryType' => [
                'type' => 'string',
                'default' => 'post'
            ],
            'queryTax' => [
                'type' => 'string',
                'default' => 'category',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryType','condition' => '!=','value' => array('customPosts', 'posts', 'archiveBuilder')]
                        ]
                    ],
                ],
            ],
            'queryTaxValue' => [
                'type' => 'string',
                'default' => '[]',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryTax','condition' => '!=','value' => ''],
                            (object)['key' => 'queryType','condition' => '!=','value' => array('customPosts', 'posts', 'archiveBuilder')]
                        ]
                    ],
                ],
            ],
            'queryRelation' => [
                'type' => 'string',
                'default' => 'OR',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryTaxValue','condition' => '!=','value' => '[]'],
                            (object)['key' => 'queryType','condition' => '!=','value' => array('customPosts', 'posts', 'archiveBuilder')]
                        ]
                    ],
                ],
            ],
            'queryOrderBy' => [
                'type' => 'string',
                'default' => 'date',
            ],
            'metaKey' => [
                'type' => 'string',
                'default' => 'custom_meta_key',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryOrderBy','condition' => '==','value' => 'meta_value_num'],
                            (object)['key' => 'queryType','condition' => '!=','value' => array('customPosts', 'posts', 'archiveBuilder')]
                        ]
                    ],
                ],
            ],
            'queryOrder' => [
                'type' => 'string',
                'default' => 'desc',
            ],
            // Include Remove from Version 2.5.4
            'queryInclude' => [
                'type' => 'string',
                'default' => '',
            ],
            'queryExclude' => [
                'type' => 'string',
                'default' => '[]',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryType','condition' => '!=','value' => array('customPosts', 'posts', 'archiveBuilder')]
                        ]
                    ],
                ],
            ],
            'queryAuthor' => [
                'type' => 'string',
                'default' => '[]',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryType','condition' => '!=','value' => array('customPosts', 'posts', 'archiveBuilder')]
                        ]
                    ],
                ],
            ],
            'queryOffset' => [
                'type' => 'string',
                'default' => '0',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryType','condition' => '!=','value' => array('customPosts', 'posts', 'archiveBuilder')]
                        ]
                    ],
                ],
            ],
            'queryExcludeTerm' => [
                'type' => 'string',
                'default' => '[]',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryType','condition' => '!=','value' => array('customPosts', 'posts', 'archiveBuilder')]
                        ]
                    ],
                ],
            ],
            'queryExcludeAuthor' => [
                'type' => 'string',
                'default' => '[]',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryType','condition' => '!=','value' => array('customPosts', 'posts', 'archiveBuilder')]
                        ]
                    ],
                ],
            ],
            'querySticky' => [
                'type' => 'boolean',
                'default' => true,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryType','condition' => '!=','value' => array('customPosts', 'posts', 'archiveBuilder')]
                        ]
                    ],
                ],
            ],
            'queryUnique' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryType','condition' => '!=','value' => array('customPosts', 'posts', 'archiveBuilder')]
                        ]
                    ],
                ],
            ],
            'queryPosts' => [
                'type' => 'string',
                'default' => '[]',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryType','condition' => '==','value' => 'posts']
                        ]
                    ],
                ],
            ],
            'queryCustomPosts' => [
                'type' => 'string',
                'default' => '[]',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryType','condition' => '==','value' => 'customPosts']
                        ]
                    ],
                ],
            ],


            /*============================
                Arrow Style
            ============================*/
            'arrowStyle' => [
                'type' => 'string',
                'default' => 'leftAngle2#rightAngle2',
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                        ],
                    ],
                ]
            ],
            'arrowSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'80', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                        ],
                        'selector' => '{{ULTP}} .slick-next svg, {{ULTP}} .slick-prev svg { width:{{arrowSize}}; }'
                    ],
                ]
            ],
            'arrowWidth' => [
                'type' => 'object',
                'default' => (object)['lg' =>'60', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                        ],
                        'selector' => '{{ULTP}} .slick-arrow { width:{{arrowWidth}}; }'
                    ],
                ]
            ],
            'arrowHeight' => [
                'type' => 'object',
                'default' => (object)['lg' =>'60', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                        ],
                        'selector' => '{{ULTP}} .slick-arrow { height:{{arrowHeight}}; } {{ULTP}} .slick-arrow { line-height:{{arrowHeight}}; }'
                    ],
                ]
            ],
            'arrowPos' => [
                'type' => 'string',
                'default' => 'left',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide6"],
                        ],
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide5"],
                        ],
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide8"],
                        ],
                    ],
                ],
            ],
            'arrowVartical' => [
                'type' => 'object',
                'default' => (object)['lg' =>'45', 'sm' =>'16', 'xs' =>'0','unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide5"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide8"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide6"],
                        ],
                        'selector' => '{{ULTP}} .slick-next { right:{{arrowVartical}}; } {{ULTP}} .slick-prev { left:{{arrowVartical}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide5"],
                        ],
                        'selector' => '{{ULTP}} .slick-next { top:{{arrowVartical}}; } {{ULTP}} .slick-prev { top:{{arrowVartical}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide6"],
                        ],
                        'selector' => '{{ULTP}} .slick-next { top:{{arrowVartical}}; } {{ULTP}} .slick-prev { top:{{arrowVartical}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide7"],
                        ],
                        'selector' => '{{ULTP}} .slick-next { top:{{arrowVartical}}; } {{ULTP}} .slick-prev { top:{{arrowVartical}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide8"],
                        ],
                        'selector' => '{{ULTP}} .slick-next { top:{{arrowVartical}}; } {{ULTP}} .slick-prev { top:{{arrowVartical}}; }'
                    ],
                ]
            ],
            'prevArrowPos' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> 'slide5'],
                            (object)['key'=>'arrowPos','condition'=>'==','value'=> 'right'],
                        ],
                        'selector' => '{{ULTP}} .slick-prev { left: unset !important; right: {{prevArrowPos}} !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> 'slide6'],
                            (object)['key'=>'arrowPos','condition'=>'==','value'=> 'right'],
                        ],
                        'selector' => '{{ULTP}} .slick-prev { left: unset !important; right: {{prevArrowPos}} !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> 'slide8'],
                            (object)['key'=>'arrowPos','condition'=>'==','value'=> 'right'],
                        ],
                        'selector' => '{{ULTP}} .slick-prev { left: unset !important; right: {{prevArrowPos}} !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> 'slide7'],
                        ],
                        'selector' => '{{ULTP}} .slick-prev { right: unset !important; left: {{prevArrowPos}} !important; }'
                    ],

                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> 'slide7'],
                        ],
                        'selector' => '{{ULTP}} .slick-prev { right: unset !important; left: {{prevArrowPos}} !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> 'slide5'],
                            (object)['key'=>'arrowPos','condition'=>'==','value'=> 'left'],
                        ],
                        'selector' => '{{ULTP}} .slick-prev { right: unset !important; left: {{prevArrowPos}} !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> 'slide6'],
                            (object)['key'=>'arrowPos','condition'=>'==','value'=> 'left'],
                        ],
                        'selector' => '{{ULTP}} .slick-prev { right: unset !important; left: {{prevArrowPos}} !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> 'slide8'],
                            (object)['key'=>'arrowPos','condition'=>'==','value'=> 'left'],
                        ],
                        'selector' => '{{ULTP}} .slick-prev { right: unset !important; left: {{prevArrowPos}} !important; }'
                    ],
                    // (object)[
                    //     'depends' => [ 
                    //         (object)['key'=>'layout','condition'=>'==','value'=> 'slide7'],
                    //         (object)['key'=>'arrowPos','condition'=>'==','value'=> 'left'],
                    //     ],
                    //     'selector' => '{{ULTP}} .slick-prev { right: unset !important; left: {{prevArrowPos}} !important; }'
                    // ],
                ]
            ],
            'nextArrowPos' => [
                'type' => 'object',
                'default' => (object)['lg' =>'60', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> 'slide5'],
                            (object)['key'=>'arrowPos','condition'=>'==','value'=> 'right'],
                        ],
                        'selector' => '{{ULTP}} .slick-next { left: unset !important; right: {{nextArrowPos}} !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> 'slide6'],
                            (object)['key'=>'arrowPos','condition'=>'==','value'=> 'right'],
                        ],
                        'selector' => '{{ULTP}} .slick-next { left: unset !important; right: {{nextArrowPos}} !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> 'slide8'],
                            (object)['key'=>'arrowPos','condition'=>'==','value'=> 'right'],
                        ],
                        'selector' => '{{ULTP}} .slick-next { left: unset !important; right: {{nextArrowPos}} !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> 'slide7'],
                        ],
                        'selector' => '{{ULTP}} .slick-next { left: unset !important; right: {{nextArrowPos}} !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> 'slide5'],
                            (object)['key'=>'arrowPos','condition'=>'==','value'=> 'left'],
                        ],
                        'selector' => '{{ULTP}} .slick-next { right: unset !important; left: {{nextArrowPos}} !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> 'slide6'],
                            (object)['key'=>'arrowPos','condition'=>'==','value'=> 'left'],
                        ],
                        'selector' => '{{ULTP}} .slick-next { right: unset !important; left: {{nextArrowPos}} !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> 'slide8'],
                            (object)['key'=>'arrowPos','condition'=>'==','value'=> 'left'],
                        ],
                        'selector' => '{{ULTP}} .slick-next { right: unset !important; left: {{nextArrowPos}} !important; }'
                    ],
                    // (object)[
                    //     'depends' => [ 
                    //         (object)['key'=>'layout','condition'=>'==','value'=> 'slide7'],
                    //         (object)['key'=>'arrowPos','condition'=>'==','value'=> 'left'],
                    //     ],
                    //     'selector' => '{{ULTP}} .slick-next { right: unset !important; left: {{nextArrowPos}} !important; }'
                    // ],
                ]
            ],
            'arrowColor' => [
                'type' => 'string',
                'default' => '#000',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-arrow:before { color:{{arrowColor}}; } {{ULTP}} .slick-arrow svg { fill:{{arrowColor}}; }'
                    ],
                ],
            ],
            'arrowHoverColor' => [
                'type' => 'string',
                'default' => '#000',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-arrow:hover:before { color:{{arrowHoverColor}}; } {{ULTP}} .slick-arrow:hover svg { fill:{{arrowHoverColor}}; }'
                    ],
                ],
            ],
            'arrowBg' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-arrow { background:{{arrowBg}}; }'
                    ],
                ],
            ],
            'arrowHoverBg' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-arrow:hover { background:{{arrowHoverBg}}; }'
                    ],
                ],
            ],
            'arrowBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid' ],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-arrow'
                    ],
                ],
            ],
            'arrowHoverBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid' ],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-arrow:hover'
                    ],
                ],
            ],
            'arrowRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '0','bottom' => '50', 'left' => '50','right' => '50', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-arrow { border-radius: {{arrowRadius}}; }'
                    ],
                ],
            ],
            'arrowHoverRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '50','bottom' => '50', 'left' => '50','right' => '50', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-arrow:hover{ border-radius: {{arrowHoverRadius}}; }'
                    ],
                ],
            ],
            'arrowShadow' => [
                'type' => 'object',
                'default' => (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-arrow'
                    ],
                ],
            ],
            'arrowHoverShadow' => [
                'type' => 'object',
                'default' => (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'arrows','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-arrow:hover'
                    ],
                ],
            ],

            
            /*============================
                Heading Style
            ============================*/
            'headingText' => [
                'type' => 'string',
                'default' => 'Post Slider #1',
            ],
            'headingURL' => [
                'type' => 'string',
                'default' => '',
            ],
            'headingBtnText' => [
                'type' => 'string',
                'default' =>  'View More',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style11'],
                        ],
                    ],
                ],
            ],
            'headingStyle' => [
                'type' => 'string',
                'default' => 'style9',
            ],
            'headingTag' => [
                'type' => 'string',
                'default' => 'h2',
            ],
            'headingAlign' => [
                'type' => 'string',
                'default' =>  'left',
                'style' => [(object)['selector' => '{{ULTP}} .ultp-heading-inner, {{ULTP}} .ultp-sub-heading-inner { text-align:{{headingAlign}}; }']]
            ],
            'headingTypo' => [
                'type' => 'object',
                'default' =>  (object)['openTypography' => 1,'size' => (object)['lg' => '20', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px'],'decoration' => 'none', 'transform' => '', 'family'=>'','weight'=>'700'],
                'style' => [(object)['selector' => '{{ULTP}} .ultp-heading-wrap .ultp-heading-inner']]
            ],
            'headingColor' => [
                'type' => 'string',
                'default' =>  '#0e1523',
                'style' => [(object)['selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner span { color:{{headingColor}}; }']],
            ],
            'headingBorderBottomColor' => [
                'type' => 'string',
                'default' =>  '#0e1523',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style3'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner { border-bottom-color:{{headingBorderBottomColor}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style4'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner { border-color:{{headingBorderBottomColor}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style6'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner span:before { background-color: {{headingBorderBottomColor}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style7'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner span:before, {{ULTP}} .ultp-heading-inner span:after { background-color: {{headingBorderBottomColor}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style8'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:after { background-color:{{headingBorderBottomColor}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style9'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:before { background-color:{{headingBorderBottomColor}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style10'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:before { background-color:{{headingBorderBottomColor}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style13'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner { border-color:{{headingBorderBottomColor}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style14'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:before { background-color:{{headingBorderBottomColor}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style15'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner span:before { background-color:{{headingBorderBottomColor}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style16'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner span:before { background-color:{{headingBorderBottomColor}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style17'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:before { background-color:{{headingBorderBottomColor}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style18'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:after { background-color:{{headingBorderBottomColor}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style19'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:before { border-color:{{headingBorderBottomColor}}; }'
                    ],
                ],
            ],
            'headingBorderBottomColor2' => [
                'type' => 'string',
                'default' =>  '#e5e5e5',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style8'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:before { background-color:{{headingBorderBottomColor2}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style10'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:after { background-color:{{headingBorderBottomColor2}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style14'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:after { background-color:{{headingBorderBottomColor2}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style19'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:after { background-color:{{headingBorderBottomColor2}}; }'
                    ],
                ],
            ],
            'headingBg' => [
                'type' => 'string',
                'default' =>  '#037fff',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style5'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-style5 .ultp-heading-inner span:before { border-color:{{headingBg}} transparent transparent; } {{ULTP}} .ultp-heading-inner span { background-color:{{headingBg}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style2'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner span { background-color:{{headingBg}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style21'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner span, {{ULTP}} .ultp-heading-inner span:after { background-color:{{headingBg}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style3'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner span { background-color:{{headingBg}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style12'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner span { background-color:{{headingBg}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style13'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner span { background-color:{{headingBg}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style18'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner { background-color:{{headingBg}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style20'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner span:before { border-color:{{headingBg}} transparent transparent; } {{ULTP}} .ultp-heading-inner { background-color:{{headingBg}}; }'
                    ],
                ],
            ],
            'headingBg2' => [
                'type' => 'string',
                'default' =>  '#e5e5e5',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style12'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner { background-color:{{headingBg2}}; }'
                    ],
                ],
            ],
            'headingBtnTypo' => [
                'type' => 'object',
                'default' =>  (object)['openTypography' => 1,'size' => (object)['lg' => '14', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px'],'decoration' => 'none','family'=>''],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style11'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-btn'
                    ],
                ],
            ],
            'headingBtnColor' => [
                'type' => 'string',
                'default' =>  '#037fff',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style11'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-btn { color:{{headingBtnColor}}; } {{ULTP}} .ultp-heading-wrap .ultp-heading-btn svg { fill:{{headingBtnColor}}; }'
                    ],
                ],
            ],
            'headingBtnHoverColor' => [
                'type' => 'string',
                'default' =>  '#0a31da',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style11'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-btn:hover { color:{{headingBtnHoverColor}}; } {{ULTP}} .ultp-heading-wrap .ultp-heading-btn:hover svg { fill:{{headingBtnHoverColor}}; }'
                    ],
                ],
            ],
            
            'headingBorder' => [
                'type' => 'string',
                'default' => '3',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style3'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner { border-bottom-width:{{headingBorder}}px; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style4'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner { border-width:{{headingBorder}}px; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style6'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner span:before { width:{{headingBorder}}px; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style7'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner span:before, {{ULTP}} .ultp-heading-inner span:after { height:{{headingBorder}}px; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style8'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:before, {{ULTP}} .ultp-heading-inner:after { height:{{headingBorder}}px; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style9'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:before { height:{{headingBorder}}px; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style10'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:before, {{ULTP}} .ultp-heading-inner:after { height:{{headingBorder}}px; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style13'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner { border-width:{{headingBorder}}px; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style14'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:before, {{ULTP}} .ultp-heading-inner:after { height:{{headingBorder}}px; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style15'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner span:before { height:{{headingBorder}}px; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style16'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner span:before { height:{{headingBorder}}px; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style17'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:before { height:{{headingBorder}}px; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style19'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:after { width:{{headingBorder}}px; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style18'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner:after { width:{{headingBorder}}px; }'
                    ],
                ],
            ],
            'headingSpacing' => [
                'type' => 'object',
                'default' => (object)['lg'=>20, 'unit'=>'px'],
                'style' => [(object)['selector'=>'{{ULTP}} .ultp-heading-wrap {margin-top:0; margin-bottom:{{headingSpacing}}; }']]
            ],

            'headingRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '','bottom' => '','left' => '', 'right' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style2'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner span { border-radius:{{headingRadius}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style3'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner span { border-radius:{{headingRadius}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style4'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner { border-radius:{{headingRadius}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style5'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner span { border-radius:{{headingRadius}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style12'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner { border-radius:{{headingRadius}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style13'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner { border-radius:{{headingRadius}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style18'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner { border-radius:{{headingRadius}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style20'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner { border-radius:{{headingRadius}}; }'
                    ],
                ]
            ],
            'headingPadding' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style2'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner span { padding:{{headingPadding}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style3'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner span { padding:{{headingPadding}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style4'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner span { padding:{{headingPadding}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style5'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner span { padding:{{headingPadding}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style6'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner span { padding:{{headingPadding}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style12'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner span { padding:{{headingPadding}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style13'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner span { padding:{{headingPadding}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style18'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner span { padding:{{headingPadding}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style19'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner span { padding:{{headingPadding}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style20'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-wrap .ultp-heading-inner span { padding:{{headingPadding}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'headingStyle','condition'=>'==','value'=>'style21'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-heading-inner span { padding:{{headingPadding}}; }'
                    ],
                ]
            ],
            'subHeadingShow' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'subHeadingText' => [
                'type' => 'string',
                'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut sem augue. Sed at felis ut enim dignissim sodales.',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'subHeadingShow','condition'=>'==','value'=>true],
                        ],
                    ],
                ],
            ],
            'subHeadingTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography'=>1,'size'=>(object)['lg'=>'16', 'unit'=>'px'], 'spacing'=>(object)[ 'lg'=>'0', 'unit'=>'px'], 'height'=>(object)[ 'lg'=>'27', 'unit'=>'px'],'decoration'=>'none','transform' => '','family'=>'','weight'=>'500'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'subHeadingShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-sub-heading div'
                    ],
                ],
            ],
            'subHeadingColor' => [
                'type' => 'string',
                'default' =>  '#989898',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'subHeadingShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-sub-heading div{ color:{{subHeadingColor}}; }'
                    ],
                ],
            ],
            'subHeadingSpacing' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '8', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'subHeadingShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} div.ultp-sub-heading-inner{ margin:{{subHeadingSpacing}}; }'
                    ],
                ],
            ],


            /*============================
                Content Wrap Style
            ============================*/
            'contentVerticalPosition' => [
                'type' => 'string',
                'default' => 'bottomPosition',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'contentVerticalPosition','condition'=>'==','value'=>'topPosition'],
                            (object)['key'=>'layout','condition'=>'!=','value'=>'slide3'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-topPosition { align-items:flex-start; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'contentVerticalPosition','condition'=>'==','value'=>'middlePosition'],
                            (object)['key'=>'layout','condition'=>'!=','value'=>'slide3'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-middlePosition { align-items:center; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'contentVerticalPosition','condition'=>'==','value'=>'bottomPosition'],
                            (object)['key'=>'layout','condition'=>'!=','value'=>'slide3'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-bottomPosition { align-items:flex-end; }'
                    ],
                ]
            ],
            'contentHorizontalPosition' => [
                'type' => 'string',
                'default' => 'centerPosition',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'contentHorizontalPosition','condition'=>'==','value'=>'leftPosition'],
                            (object)['key'=>'layout','condition'=>'!=','value'=>'slide2'],
                            (object)['key'=>'layout','condition'=>'!=','value'=>'slide3'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-leftPosition { justify-content:flex-start; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'contentHorizontalPosition','condition'=>'==','value'=>'centerPosition'],
                            (object)['key'=>'layout','condition'=>'!=','value'=>'slide2'],
                            (object)['key'=>'layout','condition'=>'!=','value'=>'slide3'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-centerPosition { justify-content:center; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'contentHorizontalPosition','condition'=>'==','value'=>'rightPosition'],
                            (object)['key'=>'layout','condition'=>'!=','value'=>'slide2'],
                            (object)['key'=>'layout','condition'=>'!=','value'=>'slide3'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-rightPosition { justify-content:flex-end; }'
                    ],
                ]
            ],
            'contentAlign' => [
                'type' => 'string',
                'default' => "center",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'contentAlign','condition'=>'==','value'=>'left'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-inner { text-align:{{contentAlign}}; } {{ULTP}} .ultp-block-meta {justify-content: flex-start;}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'contentAlign','condition'=>'==','value'=>'center'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-inner { text-align:{{contentAlign}}; } {{ULTP}} .ultp-block-meta {justify-content: center;}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'contentAlign','condition'=>'==','value'=>'right'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-inner { text-align:{{contentAlign}}; } {{ULTP}} .ultp-block-meta {justify-content: flex-end;}'
                    ],
                ],
            ],

            'contenWraptWidth' => [
                'type' => 'object',
                'default' => (object)['lg'=>'100','unit' =>'%'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide2"],
                        ],
                        'selector' => '{{ULTP}} .ultp-block-slider-wrap > .ultp-block-content { width:{{contenWraptWidth}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide3"],
                        ],
                        'selector' => '{{ULTP}} .ultp-block-slider-wrap > .ultp-block-content { width:{{contenWraptWidth}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide2"],
                        ],
                        'selector' => '{{ULTP}}  .ultp-block-content .ultp-block-content-inner { width:{{contenWraptWidth}}; }'
                    ],
                ]
            ],
            'contenWraptHeight' => [
                'type' => 'object',
                'default' => (object)['lg'=>''],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide2"],
                        ],
                        'selector' => '{{ULTP}} .ultp-block-slider-wrap > .ultp-block-content { height:{{contenWraptHeight}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide3"],
                        ],
                        'selector' => '{{ULTP}} .ultp-block-slider-wrap > .ultp-block-content { height:{{contenWraptHeight}} !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide2"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide3"],
                        ],
                        'selector' => '{{ULTP}} .ultp-block-slider-wrap .ultp-block-content .ultp-block-content-inner { height:{{contenWraptHeight}} !important; }'
                    ],
                ]
            ],
            'slideBgBlur' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide2"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide2 .ultp-block-content { backdrop-filter: blur({{slideBgBlur}}px); }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide3"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide3 .ultp-block-content { backdrop-filter: blur({{slideBgBlur}}px); }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide2"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide3"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-inner { backdrop-filter: blur({{slideBgBlur}}px);}'
                    ],
                ]  
            ],
            'contentWrapBg' => [
                'type' => 'string',
                'default' => '#00000069',
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide2"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide2 .ultp-block-content { background:{{contentWrapBg}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide3"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide3 .ultp-block-content { background:{{contentWrapBg}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide2"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide3"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-inner { background:{{contentWrapBg}}; }'
                    ],
                ],
            ],
            'contentWrapHoverBg' => [
                'type' => 'string',
                'default' => '#00000069',
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide2"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide2 .ultp-block-content:hover { background:{{contentWrapHoverBg}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide3"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide3 .ultp-block-content:hover { background:{{contentWrapHoverBg}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide2"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide3"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-inner:hover { background:{{contentWrapHoverBg}}; }'
                    ],
                ],
            ],
            'contentWrapBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid' ],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide2"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide2 .ultp-block-content'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide3"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide3 .ultp-block-content'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide2"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide3"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-inner'
                    ],
                ],
            ],
            'contentWrapHoverBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid' ],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide2"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide2 .ultp-block-content:hover'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide3"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide3 .ultp-block-content:hover'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide2"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide3"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-inner:hover'
                    ],
                ],
            ],
            'contentWrapRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '6','bottom' => '6', 'left' => '6', 'right' => '6', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide2"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide2 .ultp-block-content { border-radius:{{contentWrapRadius}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide3"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide3 .ultp-block-content { border-radius:{{contentWrapRadius}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide2"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide3"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-inner { border-radius: {{contentWrapRadius}}; }'
                    ],
                ],
            ],
            'contentWrapHoverRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '','bottom' => '', 'left' => '', 'right' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide2"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide2 .ultp-block-content:hover{ border-radius: {{contentWrapHoverRadius}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide3"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide3 .ultp-block-content:hover{ border-radius: {{contentWrapHoverRadius}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide2"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide3"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-inner:hover{ border-radius: {{contentWrapHoverRadius}}; }'
                    ],
                ],
            ],
            'contentWrapShadow' => [
                'type' => 'object',
                'default' => (object)['openShadow' => 0, 'width' => (object)['top' => 0, 'right' => 5, 'bottom' => 15, 'left' => 0],'color' => 'rgba(0,0,0,0.15)'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide2"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide2 .ultp-block-content'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide3"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide3 .ultp-block-content'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide2"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide3"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-inner'
                    ],
                ],
            ],
            'contentWrapHoverShadow' => [
                'type' => 'object',
                'default' => (object)['openShadow' => 0, 'width' => (object)['top' => 0, 'right' => 10, 'bottom' => 25, 'left' => 0],'color' => 'rgba(0,0,0,0.25)'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide2"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide2 .ultp-block-content'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide3"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide3 .ultp-block-content'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide2"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide3"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-inner:hover'
                    ],
                ],
            ],

            'contentWrapPadding' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '27','bottom' => '27', 'left'=>'','right'=>'', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide2"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide2 .ultp-block-content { padding: {{contentWrapPadding}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide3"],
                        ],
                        'selector' => '{{ULTP}} .ultp-slide-slide3 .ultp-block-content { padding: {{contentWrapPadding}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide2"],
                            (object)['key'=>'layout','condition'=>'!=','value'=> "slide3"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content-inner { padding: {{contentWrapPadding}}; }'
                    ],
                ],
            ],
            'slideWrapMargin' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '50','bottom' => '50', 'left'=>'50','right'=>'50', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide6"],
                        ],
                        'selector' => '{{ULTP}} .ultp-block-content { margin: {{slideWrapMargin}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide7"],
                        ],
                        'selector' => '{{ULTP}} .ultp-block-content { margin: {{slideWrapMargin}}; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide8"],
                        ],
                        'selector' => '{{ULTP}} .ultp-block-content { margin: {{slideWrapMargin}}; }'
                    ],
                ],
            ],


            /*============================
                Image Style
            ============================*/
            'imageShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'imgCrop' => [
                'type' => 'string',
                'default' => 'full',
            ],
            'imgOverlay' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'imgBgbrightness' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector' => '{{ULTP}} .ultp-block-image { filter: brightness({{imgBgbrightness}}); }'
                    ],
                ]
            ],
            'imgBgBlur' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector' => '{{ULTP}} .ultp-block-image > a { filter: blur({{imgBgBlur}}px);}'
                    ],
                ]
            ],
            'imgOverlayType' => [
                'type' => 'string',
                'default' => 'default',
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'imgOverlay','condition'=>'==','value'=>true],
                        ]
                    ],
                ]         
            ],
            'overlayColor' => [
                'type' => 'object',
                'default' => (object)['openColor' => 1, 'type' => 'color', 'color' => '#0e1523'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'imgOverlayType','condition'=>'==','value'=>'custom'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-image-custom > a::before'
                    ],
                ],
            ],
            'imgOpacity' => [
                'type' => 'string',
                'default' => .7,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'imgOverlayType','condition'=>'==','value'=>'custom'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-image-custom > a::before { opacity: {{imgOpacity}}; }'
                    ],
                ],
            ],
            'imgHeight' => [
                'type' => 'object',
                'default' => (object)['lg'=>''],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide3"],
                        ],
                        'selector' => '{{ULTP}} .ultp-block-slider-wrap > .ultp-block-image-inner { height:{{imgHeight}} !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide6"],
                        ],
                        'selector' => '{{ULTP}} .ultp-block-item .ultp-block-slider-wrap > .ultp-block-image-inner { height:{{imgHeight}} !important; }'
                    ],
                ]
            ],
            'imgWidth' => [
                'type' => 'object',
                'default' => (object)['lg'=>'', 'unit'=>'%'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide2"],
                        ],
                        'selector' => '{{ULTP}} .ultp-block-slider-wrap > .ultp-block-image-inner { width:{{imgWidth}} !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide7"],
                        ],
                        'selector' => '{{ULTP}} .ultp-block-slider-wrap > .ultp-block-image-inner { width:{{imgWidth}} !important; }'
                    ],
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'layout','condition'=>'==','value'=> "slide6"],
                        ],
                        'selector' => '{{ULTP}} .ultp-block-slider-wrap > .ultp-block-image-inner { width:{{imgWidth}} !important; }'
                    ],
                ]
            ],
            'imgGrayScale' => [
                'type' => 'object',
                'default' => (object)['lg' =>'0', 'ulg' =>'%', 'unit' =>'%'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-image img { filter: grayscale({{imgGrayScale}}); }'
                    ],
                ],
            ],
            'imgHoverGrayScale' => [
                'type' => 'object',
                'default' => (object)['lg' =>'0', 'unit' =>'%'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-item:hover .ultp-block-image img { filter: grayscale({{imgHoverGrayScale}}); }'
                    ],
                ],
            ],
            'imgRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-image { border-radius:{{imgRadius}}; }'
                    ],
                ],
            ],
            'imgHoverRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-item:hover .ultp-block-image { border-radius:{{imgHoverRadius}}; }'
                    ],
                ],
            ],
            'imgShadow' => [
                'type' => 'object',
                'default' => (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-image'
                    ],
                ],
            ],
            'imgHoverShadow' => [
                'type' => 'object',
                'default' => (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-item:hover .ultp-block-image'
                    ],
                ],
            ],
            'imgSrcset' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'imgLazy' => [
                'type' => 'boolean',
                'default' => false,
            ],


            /*============================
                Dot Setting/Style
            ============================*/
            'dotWidth' => [
                'type' => 'object',
                'default' => (object)['lg' =>'5', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'dots','condition'=>'==','value'=>true],
                        ],
                        'selector' => '{{ULTP}} .slick-dots li button  { width:{{dotWidth}}; }'
                    ],
                ]
            ],
            'dotHeight' => [
                'type' => 'object',
                'default' => (object)['lg' =>'5', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'dots','condition'=>'==','value'=>true],
                        ],
                        'selector' => '{{ULTP}} .slick-dots li button  { height:{{dotHeight}}; }'
                    ],
                ]
            ],
            'dotHoverWidth' => [
                'type' => 'object',
                'default' => (object)['lg' =>'8', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'dots','condition'=>'==','value'=>true],
                        ],
                        'selector' => '{{ULTP}} .slick-dots li.slick-active button { width:{{dotHoverWidth}}; }'
                    ],
                ]
            ],
            'dotHoverHeight' => [
                'type' => 'object',
                'default' => (object)['lg' =>'8', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'dots','condition'=>'==','value'=>true],
                        ],
                        'selector' => '{{ULTP}} .slick-dots li.slick-active button { height:{{dotHoverHeight}}; }'
                    ],
                ]
            ],
            'dotSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'4', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'dots','condition'=>'==','value'=>true],
                        ],
                        'selector' => '{{ULTP}} .slick-dots { padding: 0 {{dotSpace}}; } {{ULTP}} .slick-dots li button { margin: 0 {{dotSpace}}; }'
                    ],
                ]
            ],
            'dotVartical' => [
                'type' => 'object',
                'default' => (object)['lg' =>'-20', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'dots','condition'=>'==','value'=>true],
                        ],
                        'selector' => '{{ULTP}} .slick-dots { bottom:{{dotVartical}}; }'
                    ],
                ]
            ],
            'dotHorizontal' => [
                'type' => 'object',
                'default' => (object)['lg'=>''],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'dots','condition'=>'==','value'=>true],
                        ],
                        'selector' => '{{ULTP}} .slick-dots { left:{{dotHorizontal}}; }'
                    ],
                ]
            ],
            'dotBg' => [
                'type' => 'string',
                'default' => '#9b9b9b',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'dots','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-dots li button { background:{{dotBg}}; }'
                    ],
                ],
            ],
            'dotHoverBg' => [
                'type' => 'string',
                'default' => '#9b9b9b',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'dots','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-dots li button:hover, {{ULTP}} .slick-dots li.slick-active button { background:{{dotHoverBg}}; }'
                    ],
                ],
            ],
            'dotBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid' ],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'dots','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-dots li button'
                    ],
                ],
            ],
            'dotHoverBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid' ],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'dots','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-dots li button:hover, {{ULTP}} .slick-dots li.slick-active button'
                    ],
                ],
            ],
            'dotRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '50','bottom' => '50','left' => '50','right' => '50', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'dots','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-dots li button { border-radius: {{dotRadius}}; }'
                    ],
                ],
            ],
            'dotHoverRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '50','bottom' => '50','left' => '50','right' => '50', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'dots','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-dots li button:hover, {{ULTP}} .slick-dots li.slick-active button { border-radius: {{dotHoverRadius}}; }'
                    ],
                ],
            ],
            'dotShadow' => [
                'type' => 'object',
                'default' => (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'dots','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-dots li button'
                    ],
                ],
            ],
            'dotHoverShadow' => [
                'type' => 'object',
                'default' => (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'dots','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .slick-dots li button:hover, {{ULTP}} .slick-dots li.slick-active button'
                    ],
                ],
            ],


            /*============================
                Read more Setting/Style
            ============================*/
            'readMoreText' => [
                'type' => 'string',
                'default' => ''
            ],
            'readMoreIcon' => [
                'type' => 'string',
                'default' => 'rightArrowLg',
            ],
            'readMoreTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1, 'size' => (object)['lg' =>12, 'unit' =>'px'], 'height' => (object)['lg' =>'', 'unit' =>'px'], 'spacing' => (object)['lg' =>1, 'unit' =>'px'], 'transform' => '', 'weight' => '500', 'decoration' => 'none','family'=>'' ],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readMore','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-items-wrap .ultp-block-item .ultp-block-readmore a'
                    ],
                ],
            ],
            'readMoreIconSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'readMore','condition'=>'==','value'=>true],
                        ],
                        'selector' => '{{ULTP}} .ultp-block-readmore svg{ width:{{readMoreIconSize}}; }'
                    ],
                ]
            ],
            'readMoreColor' => [
                'type' => 'string',
                'default' => '#000',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readMore','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-items-wrap .ultp-block-readmore a { color:{{readMoreColor}}; } {{ULTP}} .ultp-block-readmore a svg { fill:{{readMoreColor}}; }'
                    ],
                ],
            ],
            'readMoreBgColor' => [
                'type' => 'object',
                'default' => (object)['openColor' => 0,'type' => 'color', 'color' => '#037fff'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readMore','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-readmore a'
                    ],
                ],
            ],
            'readMoreBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid' ],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readMore','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-readmore a'
                    ],
                ],
            ],
            'readMoreRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readMore','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-items-wrap .ultp-block-readmore a { border-radius:{{readMoreRadius}}; }'
                    ],
                ],
            ],
            'readMoreHoverColor' => [
                'type' => 'string',
                'default' => '#037fff',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readMore','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-items-wrap .ultp-block-readmore a:hover { color:{{readMoreHoverColor}}; } {{ULTP}} .ultp-block-readmore a:hover svg { fill:{{readMoreHoverColor}}; }'
                    ],
                ],
            ],
            'readMoreBgHoverColor' => [
                'type' => 'object',
                'default' => (object)['openColor' => 0, 'type' => 'color', 'color' => '#0c32d8'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readMore','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-readmore a:hover'
                    ],
                ],
            ],
            'readMoreHoverBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readMore','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-readmore a:hover'
                    ],
                ],
            ],
            'readMoreHoverRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readMore','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-readmore a:hover { border-radius:{{readMoreHoverRadius}}; }'
                    ],
                ],
            ],
            'readMoreSacing' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => 30,'bottom' => '','left' => '','right' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readMore','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-readmore { margin:{{readMoreSacing}}; }'
                    ],
                ],
            ],
            'readMorePadding' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '','bottom' => '','left' => '','right' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readMore','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-readmore a { padding:{{readMorePadding}}; }'
                    ],
                ],
            ],


            /*============================
                Title Setting/Style
            ============================*/
            'titleTag' => [
                'type' => 'string',
                'default' => 'h3',
            ],
            'titlePosition' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'titleColor' => [
                'type' => 'string',
                'default' => '#fff',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'titleShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-items-wrap .ultp-block-item .ultp-block-title a { color:{{titleColor}} !important; }'
                    ],
                ],
            ],
            'titleHoverColor' => [
                'type' => 'string',
                'default' => 'rgba(107,107,107,1)',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'titleShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-items-wrap .ultp-block-item .ultp-block-title a:hover { color:{{titleHoverColor}} !important; }'
                    ],
                ],
            ],
            'titleTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography'=>1,'size'=>(object)['lg'=>'24', 'unit'=>'px'], 'height'=>(object)['lg'=>'36', 'unit'=>'px'], 'decoration'=>'none','family'=>'','weight'=>'300', 'transform' => 'uppercase'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'titleShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-item .ultp-block-content .ultp-block-title, {{ULTP}} .ultp-block-item .ultp-block-content .ultp-block-title a'
                    ],
                ],
            ],
            'titlePadding' => [
                'type' => 'object',
                'default' => (object)['lg'=>(object)['top'=> 0,'bottom'=> 0, 'unit'=>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'titleShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-content .ultp-block-title { padding:{{titlePadding}}; }'
                    ],
                ],
            ],
            'titleLength' => [
                'type' => 'string',
                'default' => 0,
            ],
            'titleBackground' => [
                'type' => 'string',
                'default' =>  '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'titleShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-item .ultp-block-title a{ padding: 2px 7px; -webkit-box-decoration-break: clone; box-decoration-break: clone; background-color:{{titleBackground}}; }'
                    ],
                ],
            ], 

            'titleAnimColor' => [ 
                'type' => 'string',
                'default' => 'black',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'titleStyle','condition'=>'==','value'=>'style1']
                        ],
                        'selector'=>'{{ULTP}} .ultp-title-style1 a { 
                            cursor: pointer;
                            text-decoration: none;
                            display: inline;
                            padding-bottom: 2px;
                            transition: all 0.35s linear !important;
                            background: linear-gradient(
                            to bottom,
                            {{titleAnimColor}} 0%,
                            {{titleAnimColor}} 98%
                            );      
                            background-size: 0px 2px;
                            background-repeat: no-repeat;
                            background-position: left 100%;
                        }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'titleStyle','condition'=>'==','value'=>'style2']
                        ],
                        'selector'=>'{{ULTP}} .ultp-title-style2 a:hover { 
                            border-bottom:none;
                            padding-bottom: 2px;
                            background-position:0 100%;
                            background-repeat: repeat;
                            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg id=\'squiggle-link\' xmlns=\'http://www.w3.org/2000/svg\' xmlns:xlink=\'http://www.w3.org/1999/xlink\' xmlns:ev=\'http://www.w3.org/2001/xml-events\' viewBox=\'0 0 10 18\'%3E%3Cstyle type=\'text/css\'%3E.squiggle%7Banimation:shift .5s linear infinite;%7D@keyframes shift %7Bfrom %7Btransform:translateX(-10px);%7Dto %7Btransform:translateX(0);%7D%7D%3C/style%3E%3Cpath fill=\'none\' stroke=\'{{titleAnimColor}}\' stroke-width=\'1\' class=\'squiggle\' d=\'M0,17.5 c 2.5,0,2.5,-1.5,5,-1.5 s 2.5,1.5,5,1.5 c 2.5,0,2.5,-1.5,5,-1.5 s 2.5,1.5,5,1.5\' /%3E%3C/svg%3E");
                        }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'titleStyle','condition'=>'==','value'=>'style3']
                        ],
                        'selector'=>'{{ULTP}} .ultp-title-style3 a { 
                            text-decoration: none;
                            $thetransition: all 1s cubic-bezier(1,.25,0,.75) 0s;
                            position: relative;
                            transition: all 0.35s ease-out;
                            padding-bottom: 3px;
                            border-bottom:none;
                            padding-bottom: 2px;
                            background-position:0 100%;
                            background-repeat: repeat;
                            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg id=\'squiggle-link\' xmlns=\'http://www.w3.org/2000/svg\' xmlns:xlink=\'http://www.w3.org/1999/xlink\' xmlns:ev=\'http://www.w3.org/2001/xml-events\' viewBox=\'0 0 10 18\'%3E%3Cstyle type=\'text/css\'%3E.squiggle%7Banimation:shift .5s linear infinite;%7D@keyframes shift %7Bfrom %7Btransform:translateX(-10px);%7Dto %7Btransform:translateX(0);%7D%7D%3C/style%3E%3Cpath fill=\'none\' stroke=\'{{titleAnimColor}}\' stroke-width=\'1\' class=\'squiggle\' d=\'M0,17.5 c 2.5,0,2.5,-1.5,5,-1.5 s 2.5,1.5,5,1.5 c 2.5,0,2.5,-1.5,5,-1.5 s 2.5,1.5,5,1.5\' /%3E%3C/svg%3E");
                        }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'titleStyle','condition'=>'==','value'=>'style4']
                        ],
                        'selector'=>'{{ULTP}} .ultp-title-style4 a {
                            cursor: pointer;
                            font-weight: 600;
                            text-decoration: none;
                            display: inline;
                            padding-bottom: 2px;
                            transition: all 0.3s linear !important;
                            background: linear-gradient(
                              to bottom,
                              {{titleAnimColor}} 0%,
                              {{titleAnimColor}} 98%
                            );      
                            background-size: 100% 2px;
                            background-repeat: no-repeat;
                            background-position: left 100%;
                        }'
                        ]  ,
                    (object)[
                        'depends' => [
                            (object)['key'=>'titleStyle','condition'=>'==','value'=>'style5']
                        ],
                        'selector'=>'{{ULTP}} .ultp-title-style5 a:hover{
                            text-decoration: none;
                            transition: all 0.35s ease-out;
                            border-bottom:none;
                            padding-bottom: 2px;
                            background-position:0 100%;
                            background-repeat: repeat;
                            background-size:auto;
                            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg id=\'squiggle-link\' xmlns=\'http://www.w3.org/2000/svg\' xmlns:xlink=\'http://www.w3.org/1999/xlink\' xmlns:ev=\'http://www.w3.org/2001/xml-events\' viewBox=\'0 0 10 18\'%3E%3Cstyle type=\'text/css\'%3E.squiggle%7Banimation:shift .5s linear infinite;%7D@keyframes shift %7Bfrom %7Btransform:translateX(-10px);%7Dto %7Btransform:translateX(0);%7D%7D%3C/style%3E%3Cpath fill=\'none\' stroke=\'{{titleAnimColor}}\' stroke-width=\'1\' class=\'squiggle\' d=\'M0,17.5 c 2.5,0,2.5,-1.5,5,-1.5 s 2.5,1.5,5,1.5 c 2.5,0,2.5,-1.5,5,-1.5 s 2.5,1.5,5,1.5\' /%3E%3C/svg%3E");
                         } '
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'titleStyle','condition'=>'==','value'=>'style5']
                        ],
                        'selector'=>'{{ULTP}} .ultp-title-style5 a {
                            cursor: pointer;
                            text-decoration: none;
                            display: inline;
                            padding-bottom: 2px;
                            transition: all 0.3s linear !important;
                            background: linear-gradient(
                              to bottom,
                              {{titleAnimColor}} 0%,
                              {{titleAnimColor}} 98%
                            );      
                            background-size: 100% 2px;
                            background-repeat: no-repeat;
                            background-position: left 100%;
                        }'
                        ],
                        (object)[
                            'depends' => [
                                (object)['key'=>'titleStyle','condition'=>'==','value'=>'style6']
                            ],
                            'selector'=>'{{ULTP}} .ultp-title-style6  a{
                                background-image: linear-gradient(120deg, {{titleAnimColor}} 0%, {{titleAnimColor}} 100%);
                                background-repeat: no-repeat;
                                background-size: 100% 2px;
                                background-position: 0 88%;
                                transition: background-size 0.15s ease-in;
                                padding: 5px 5px;
                            }'
                            ],
                            (object)[
                                'depends' => [
                                    (object)['key'=>'titleStyle','condition'=>'==','value'=>'style7']
                                ],
                                'selector'=>'{{ULTP}} .ultp-title-style7  a {
                                    cursor: pointer;
                                    text-decoration: none;
                                    display: inline;
                                    padding-bottom: 2px;
                                    transition: all 0.3s linear !important;
                                    background: linear-gradient(
                                      to bottom,
                                      {{titleAnimColor}} 0%,
                                      {{titleAnimColor}} 98%
                                    );      
                                    background-size: 0px 2px;
                                    background-repeat: no-repeat;
                                    background-position: right 100%;
                                 } '
                            ],
                            (object)[
                                'depends' => [
                                    (object)['key'=>'titleStyle','condition'=>'==','value'=>'style8']
                                ],
                                'selector'=>'{{ULTP}} .ultp-title-style8  a {
                                    cursor: pointer;
                                    text-decoration: none;
                                    display: inline;
                                    padding-bottom: 2px;
                                    transition: all 0.3s linear !important;
                                    background: linear-gradient(
                                      to bottom,
                                      {{titleAnimColor}} 0%,
                                      {{titleAnimColor}} 98%
                                    );      
                                    background-size: 0px 2px;
                                    background-repeat: no-repeat;
                                    background-position: center 100%;
                                 } '
                            ],
                            (object)[
                                'depends' => [
                                    (object)['key'=>'titleStyle','condition'=>'==','value'=>'style9']
                                ],
                                'selector'=>'{{ULTP}} .ultp-title-style9  a {
                                    background-image: linear-gradient(120deg,{{titleAnimColor}}  0%, {{titleAnimColor}} 100%);
                                    background-repeat: no-repeat;
                                    background-size: 100% 10px;
                                    background-position: 0 88%;
                                    transition: 0.3s ease-in;
                                    padding: 3px 5px;
                                 } '
                            ]
                    ],
            ],


            /*============================
                Meta Setting/Style
            ============================*/
            'metaPosition' => [
                'type' => 'string',
                'default' => 'top',
            ],
            'metaStyle' => [
                'type' => 'string',
                'default' => 'noIcon',
            ],
            'authorLink' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'metaSeparator' => [
                'type' => 'string',
                'default' => 'dot',
            ],
            'metaList' => [
                'type' => 'string',
                'default' => '["metaAuthor","metaDate"]',
            ],
            'metaMinText' => [
                'type' => 'string',
                'default' => 'min read',
            ],
            'metaAuthorPrefix' => [
                'type' => 'string',
                'default' => '',
            ],
            'metaDateFormat' => [
                'type' => 'string',
                'default' => 'j M Y',
            ],
            'metaTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>12, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px'], 'weight' => '300','decoration' => 'none','family'=>''],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-meta span, {{ULTP}} .ultp-block-item .ultp-block-meta span a'
                    ],
                ],
            ],
            'metaColor' => [
                'type' => 'string',
                'default' => '#FFFFFFA8',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-items-wrap .ultp-block-meta span { color: {{metaColor}}; } {{ULTP}} .ultp-block-items-wrap .ultp-block-meta span svg { fill: {{metaColor}}; }  {{ULTP}} .ultp-block-items-wrap .ultp-block-meta span a { color: {{metaColor}}; }{{ULTP}} .ultp-block-meta-dot span:after { background:{{metaColor}}; }  {{ULTP}} .ultp-block-meta span:after { color:{{metaColor}}; }'
                    ],
                ],
            ],
            'metaHoverColor' => [
                'type' => 'string',
                'default' => '#a5a5a5',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-items-wrap .ultp-block-meta span:hover , {{ULTP}} .ultp-block-items-wrap .ultp-block-meta span:hover a { color: {{metaHoverColor}}; } {{ULTP}} .ultp-block-items-wrap .ultp-block-meta span:hover svg { fill: {{metaHoverColor}}; }'
                    ],
                ],
            ],
            'metaSpacing' => [
                'type' => 'object',
                'default' => (object)['lg' =>'10', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-meta span { margin-right:{{metaSpacing}}; } {{ULTP}} .ultp-block-meta span { padding-left: {{metaSpacing}}; }'
                    ],
                ],
            ],
            'metaMargin' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '5','bottom' => '', 'left'=>'','right'=>'', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-meta { margin:{{metaMargin}}; }'
                    ],
                ],
            ],
            'metaPadding' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '5','bottom' => '5', 'left'=>'','right'=>'', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-meta { padding:{{metaPadding}}; }'
                    ],
                ],
            ],
            'metaBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => '0', 'bottom' => '0', 'left' => '0'],'color' => '#009fd4','type' => 'solid'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-meta'
                    ],
                ],
            ],
            'metaBg' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-meta { background:{{metaBg}}; }'
                    ],
                ],
            ],


            /*============================
                Category Setting/Style
            ============================*/
            'taxonomy' => [
                'type' => 'string',
                'default' => 'category'
            ],
            'catStyle' => [
                'type' => 'string',
                'default' => 'classic',
            ],
            'catPosition' => [
                'type' => 'string',
                'default' => 'aboveTitle',
            ],
            'customCatColor' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'seperatorLink' => [
                'type' => 'string',
                'default' => admin_url( 'edit-tags.php?taxonomy=category' ),
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'customCatColor','condition'=>'==','value'=>true],
                        ]
                    ]
                ]
            ],
            'onlyCatColor' => [
                'type' => 'boolean',
                'default' => false,
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'customCatColor','condition'=>'==','value'=>true],
                        ]
                    ]
                ]
            ],
            'catLineWidth' => [
                'type' => 'object',
                'default' => (object)['lg'=>'20'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'catStyle','condition'=>'!=','value'=>'classic'],
                        ],
                        'selector' => '{{ULTP}} .ultp-category-borderRight .ultp-category-in:before, {{ULTP}} .ultp-category-borderBoth .ultp-category-in:before, {{ULTP}} .ultp-category-borderBoth .ultp-category-in:after, {{ULTP}} .ultp-category-borderLeft .ultp-category-in:before  { width:{{catLineWidth}}px; }'
                    ],
                ]
            ],
            'catLineSpacing' => [
                'type' => 'object',
                'default' => (object)['lg'=>'30'],
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'catStyle','condition'=>'!=','value'=>'classic'],
                        ],
                        'selector' => '{{ULTP}} .ultp-category-borderBoth .ultp-category-in { padding-left: {{catLineSpacing}}px; padding-right: {{catLineSpacing}}px; }
                        {{ULTP}} .ultp-category-borderLeft .ultp-category-in { padding-left: {{catLineSpacing}}px; }
                        {{ULTP}} .ultp-category-borderRight .ultp-category-in { padding-right:{{catLineSpacing}}px; }'
                    ],
                ]
            ],
            'catLineColor' => [
                'type' => 'string',
                'default' => '#000',
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'catStyle','condition'=>'!=','value'=>'classic'],
                        ],
                        'selector' => '{{ULTP}} .ultp-category-borderRight .ultp-category-in:before, {{ULTP}} .ultp-category-borderLeft .ultp-category-in:before, {{ULTP}} .ultp-category-borderBoth .ultp-category-in:after, {{ULTP}} .ultp-category-borderBoth .ultp-category-in:before { background:{{catLineColor}}; }'
                    ],
                ]
            ],
            'catLineHoverColor' => [
                'type' => 'string',
                'default' => '#037fff',
                'style' => [
                    (object)[
                        'depends' => [ 
                            (object)['key'=>'catStyle','condition'=>'!=','value'=>'classic'],
                        ],
                        'selector' => '{{ULTP}} .ultp-category-borderBoth:hover .ultp-category-in:before, {{ULTP}} .ultp-category-borderBoth:hover .ultp-category-in:after, {{ULTP}} .ultp-category-borderLeft:hover .ultp-category-in:before, {{ULTP}} .ultp-category-borderRight:hover .ultp-category-in:before { background:{{catLineHoverColor}}; }'
                    ],
                ]
            ],
            'catTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1, 'size' => (object)['lg' =>12, 'unit' =>'px'], 'height' => (object)['lg' =>15, 'unit' =>'px'], 'spacing' => (object)['lg' => 7.32, 'unit' =>'px'], 'transform' => 'uppercase', 'weight' => '300', 'decoration' => 'none','family'=>'' ],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-items-wrap .ultp-block-item .ultp-category-grid a'
                    ],
                ],
            ],
            'catColor' => [
                'type' => 'string',
                'default' => '#fff',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catShow','condition'=>'==','value'=>true],
                            (object)['key'=>'customCatColor','condition'=>'!=','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-items-wrap .ultp-block-item .ultp-category-grid a { color:{{catColor}}; }'
                    ],
                ],
            ],
            'catBgColor' => [
                'type' => 'object',
                'default' => (object)['openColor' => 1,'type' => 'color', 'color' => ''],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catShow','condition'=>'==','value'=>true],
                            (object)['key'=>'customCatColor','condition'=>'!=','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-category-grid a'
                    ],
                ],
            ],
            'catBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid' ],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catShow','condition'=>'==','value'=>true],
                            (object)['key'=>'onlyCatColor','condition'=>'!=','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-category-grid a'
                    ],
                ],
            ],
            'catRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'2', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catShow','condition'=>'==','value'=>true],
                            (object)['key'=>'onlyCatColor','condition'=>'!=','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-category-grid a { border-radius:{{catRadius}}; }'
                    ],
                ],
            ],
            'catHoverColor' => [
                'type' => 'string',
                'default' => '#a5a5a5',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catShow','condition'=>'==','value'=>true],
                            (object)['key'=>'customCatColor','condition'=>'!=','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-items-wrap .ultp-category-grid a:hover { color:{{catHoverColor}}; }'
                    ],
                ],
            ],
            'catBgHoverColor' => [
                'type' => 'object',
                'default' => (object)['openColor' => 1, 'type' => 'color', 'color' => ''],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catShow','condition'=>'==','value'=>true],
                            (object)['key'=>'customCatColor','condition'=>'!=','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-category-grid a:hover'
                    ],
                ],
            ],
            'catHoverBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catShow','condition'=>'==','value'=>true],
                            (object)['key'=>'onlyCatColor','condition'=>'!=','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-category-grid a:hover'
                    ],
                ],
            ],
            'catSacing' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => 0,'bottom' => 0,'left' => 0,'right' => 0, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-category-grid { margin:{{catSacing}}; }'
                    ],
                ],
            ],
            'catPadding' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => 8,'bottom' => 6,'left' => 16,'right' => 16, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catShow','condition'=>'==','value'=>true],
                            (object)['key'=>'onlyCatColor','condition'=>'!=','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-category-grid a { padding:{{catPadding}}; }'
                    ],
                ],
            ],


            /*============================
                Excerpt Style
            ============================*/
            'showSeoMeta' => [
                'type' => 'boolean',
                'default'=> false
            ],
            'showFullExcerpt' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'excerptLimit' => [
                'type' => 'string',
                'default' => 40,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'showFullExcerpt','condition'=>'==','value'=>false]
                        ],
                    ],
                ],
            ],
            'excerptColor' => [
                'type' => 'string',
                'default' => '#fff8',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'excerptShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-excerpt { color:{{excerptColor}}; }'
                    ],
                ],
            ],
            'excerptTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>14, 'unit' =>'px'],'height' => (object)['lg' =>26, 'unit' =>'px'], 'decoration' => 'none','family'=>''],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'excerptShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-excerpt, {{ULTP}} .ultp-block-excerpt p'
                    ],
                ],
            ],
            'excerptPadding' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => 10,'bottom' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'excerptShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-excerpt{ padding: {{excerptPadding}}; }'
                    ],
                ],
            ],

            
            /*============================
                Wrapper Style
            ============================*/
            'loadingColor' => [
                'type' => 'string',
                'default' => '#000',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-loading .ultp-loading-blocks div , {{ULTP}} .ultp-loading .ultp-loading-spinner div { --loading-block-color: {{loadingColor}}; }'
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
            'wrapInnerPadding' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-wrapper { padding:{{wrapInnerPadding}}; }'
                    ],
                ],
            ],
            'advanceId' => [
                'type' => 'string',
                'default' => '',
            ],
            'advanceZindex' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector' => '{{ULTP}} .ultp-block-wrapper{z-index:{{advanceZindex}};}'
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
                'style' => [(object)['selector' => '']],
            ]

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
        register_block_type( 'ultimate-post/post-slider-2',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );
    }

    public function content($attr, $noAjax) {
        global $unique_ID;
        if (!$noAjax) {
            $paged = is_front_page() ? get_query_var('page') : get_query_var('paged');
            $attr['paged'] = $paged ? $paged : 1;
        }
        $is_active = ultimate_post()->is_lc_active(); 
        if($is_active){
            $block_name = 'post-slider-2';
        $page_post_id = ultimate_post()->get_ID();
        $wraper_before = $wraper_after = $post_loop = '';
        $attr['queryNumber'] = ultimate_post()->get_post_number(5, $attr['queryNumber'], $attr['queryNumPosts']);
        $recent_posts = new \WP_Query( ultimate_post()->get_query( $attr ) );
        $pageNum = ultimate_post()->get_page_number($attr, $recent_posts->found_posts);

        $slides = is_object($attr['slidesToShow']) ? json_decode(json_encode($attr['slidesToShow']),true) : $attr['slidesToShow'];

        $centerPadd = is_object($attr['slidesCenterPadding']) ? json_decode(json_encode($attr['slidesCenterPadding']),true) : $attr['slidesCenterPadding'];
        if ($recent_posts->have_posts() ) {
            $wraper_before .= '<div id="'.$attr["blockId"].'" class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].''.(isset($attr["align"])? ' align' .$attr["align"]:'').''.(isset($attr["className"])?' '.$attr["className"]:'').'">';
                $wraper_before .= '<div class="ultp-block-wrapper">';
                    if ($attr['headingShow']) {
                        $wraper_before .= '<div class="ultp-heading-filter">';
                            $wraper_before .= '<div class="ultp-heading-filter-in">';
                                include ULTP_PATH.'blocks/template/heading.php';
                            $wraper_before .= '</div>';
                        $wraper_before .= '</div>';
                    }
                    $wraper_before .= '<div class="ultp-block-items-wrap ultp-slide-'.$attr['layout'].'" data-layout="'.$attr['layout'].'"  data-arrows="'.$attr['arrows'].'" data-dots="'.$attr['dots'].'" data-autoplay="'.$attr['autoPlay'].'" data-slidespeed="'.$attr['slideSpeed'].'" data-fade="'.$attr['fade'].'" data-slidelg="'.(isset($slides['lg'])?$slides['lg']:1).'" data-slidesm="'.(isset($slides['sm'])?$slides['sm']:1).'" data-slidexs="'.(isset($slides['xs']) ? $slides['xs']:1).'" 
                    data-paddlg="'.(isset($centerPadd["lg"]) ? $centerPadd["lg"] : 100 ).'" data-paddsm="'.(isset($centerPadd["sm"]) ? $centerPadd["sm"] : 100 ).'" data-paddxs="'.(isset($centerPadd["xs"]) ? $centerPadd['xs'] : 50).'">';
                        $idx = $noAjax ? 1 : 0;
                        while ( $recent_posts->have_posts() ): $recent_posts->the_post();
                            
                            include ULTP_PATH.'blocks/template/data.php';

                            if ($attr['queryUnique']) {
                                $unique_ID[$attr['queryUnique']][] = $post_id;
                            }

                            $post_loop .= '<'.$attr['contentTag'].' class="ultp-block-item post-id-'.$post_id.'">';
                                if($attr['preLoader']) {
                                    $post_loop .= '<div class="ultp-post-slider-loader-container">';
                                        $post_loop .= ultimate_post()->loading();
                                    $post_loop .= '</div>';
                                }   
                                $post_loop .= '<div>';
                                $post_loop .= '<div class="ultp-block-slider-wrap">';

                                    $post_loop .= '<div class="ultp-block-image-inner">';
                                        if ($attr['imageShow']) {
                                            if (has_post_thumbnail()) {
                                                $post_loop .= '<div class="ultp-block-image '.($attr["imgOverlay"] ? ' ultp-block-image-overlay ultp-block-image-'.$attr["imgOverlayType"].' ultp-block-image-'.$attr["imgOverlayType"].$idx : '' ).'">';
                                                    $post_loop .= '<a href="'.$titlelink.'" '.($attr['openInTab'] ? 'target="_blank"' : '').'>'.ultimate_post()->get_image($post_thumb_id, $attr['imgCrop'], '', $title, $attr['imgSrcset'], $attr['imgLazy']).'</a>';
                                                $post_loop .= '</div>'; //.ultp-block-image    
                                            }
                                        }
                                    $post_loop .= '</div>'; //.ultp-block-image-inner                  

                                    $post_loop .= '<div class="ultp-block-content ultp-block-content-'.$attr['contentVerticalPosition'].' ultp-block-content-'.$attr['contentHorizontalPosition'].'">';
                                        $post_loop .= '<div class="ultp-block-content-inner">';
                                            
                                            include ULTP_PATH.'blocks/template/category.php';
                                            $post_loop .= $category;

                                            if ($title && $attr['titleShow'] && $attr['titlePosition']) {
                                                include ULTP_PATH.'blocks/template/title.php';
                                            }
                                            
                                            if ($attr['metaPosition'] =='top' ) {
                                                include ULTP_PATH.'blocks/template/meta.php';
                                            }

                                            if ($title && $attr['titleShow'] && !$attr['titlePosition']) {
                                                include ULTP_PATH.'blocks/template/title.php';
                                            }

                                            if ($attr['excerptShow']) {
                                                $post_loop .= '<div class="ultp-block-excerpt">'.ultimate_post()->get_excerpt($post_id, $attr['showSeoMeta'], $attr['showFullExcerpt'], $attr['excerptLimit']).'</div>';
                                            }

                                            if ($attr['readMore']) {
                                                $post_loop .= '<div class="ultp-block-readmore"><a aria-label="'.$title.'" href="'.$titlelink.'" '.($attr['openInTab'] ? 'target="_blank"' : '').'>'.($attr['readMoreText'] ? $attr['readMoreText'] : esc_html__( "Read More", "ultimate-post" )).ultimate_post()->svg_icon($attr['readMoreIcon']).'</a></div>';
                                            }

                                            if ($attr['metaPosition'] =='bottom' ) {
                                                include ULTP_PATH.'blocks/template/meta.php';
                                            }
                                            
                                        $post_loop .= '</div>'; //.ultp-block-content-inner
                                    $post_loop .= '</div>'; //.ultp-block-content

                                $post_loop .= '</div>'; //.ultp-block-slider-wrap
                                $post_loop .= '</div>'; //div
                            $post_loop .= '</'.$attr['contentTag'].'>'; //.ultp-block-item

                        endwhile;

                    $wraper_after .= '</div>'; //.ultp-block-items-wrap

                    if ($attr['arrows']) {
                        $wraper_after .= '<div class="ultp-slick-nav" style="display:none">';
                            $nav = explode('#', $attr['arrowStyle']);
                            $wraper_after .= '<div class="ultp-slick-prev"><div class="slick-arrow slick-prev">'.ultimate_post()->svg_icon($nav[0]).'</div></div>';
                            $wraper_after .= '<div class="ultp-slick-next"><div class="slick-arrow slick-next">'.ultimate_post()->svg_icon($nav[1]).'</div></div>';
                        $wraper_after .= '</div>';
                    }

                $wraper_after .= '</div>'; //.ultp-block-wrapper
            $wraper_after .= '</div>'; //.wp-block-ultimate-post-post-slider-2

            wp_reset_query();
        }
        
        return $noAjax ? $post_loop : $wraper_before.$post_loop.$wraper_after;
        }
    }

}