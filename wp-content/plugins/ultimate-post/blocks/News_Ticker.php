<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class News_Ticker{
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
            
            //--------------------------
            //      Label Style
            //--------------------------
            'tickLabelColor' => [
                'type' => 'string',
                'default' => '#fff',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-news-ticker-label { color:{{tickLabelColor}}; } '
                    ],
                ],
            ],
            'tickLabelBg' => [
                'type' => 'string',
                'default' => '#1974d2',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-news-ticker-label{background-color:{{tickLabelBg}};}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'tickShapeStyle','condition'=>'!=','value'=>'normal'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-label::after{border-color:transparent transparent transparent {{tickLabelBg}} !important}'
                    ],
                ],
            ],
            'tickLabelTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography'=>1,'size'=>(object)['lg'=>'16', 'unit'=>'px'], 'spacing'=>(object)[ 'lg'=>'0', 'unit'=>'px'], 'height'=>(object)[ 'lg'=>'27', 'unit'=>'px'],'decoration'=>'none','transform' => '','family'=>'','weight'=>'500'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-news-ticker-label'
                    ],
                ],
            ],
            'tickLabelPadding' => [
                'type' => 'string',
                'default' => '15',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-news-ticker-label { padding:0px {{tickLabelPadding}}px; }'
                    ],
                ],
            ],
            'tickLabelSpace' => [
                'type' => 'object',
                'default' => (object)['lg'=>'160'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-news-ticker-box { padding-left:{{tickLabelSpace}}px; } .rtl {{ULTP}} .ultp-news-ticker-box { padding-right:{{tickLabelSpace}}px !important; }'
                    ],
                ],
            ],
            'tickShapeStyle' => [
                'type' => 'string',
                'default' => 'normal',
                'style' => [

                    (object)[
                        'depends' => [
                            (object)['key'=>'tickShapeStyle','condition'=>'==','value'=>'large'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-label::after {border-top: 23px solid; border-left: 24px solid; border-bottom: 23px solid; right: -24px;border-color:transparent; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'tickShapeStyle','condition'=>'==','value'=>'medium'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-label::after { border-top: 17px solid; border-left: 20px solid;border-bottom: 17px solid;border-color:transparent;right:-20px;}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'tickShapeStyle','condition'=>'==','value'=>'small'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-label::after { border-top: 12px solid;border-left: 15px solid;
                            border-bottom: 12px solid;border-color: transparent;right: -15px; }'
                        ],
                        (object)[
                            'depends' => [
                                (object)['key'=>'tickShapeStyle','condition'=>'==','value'=>'normal'],
                            ],
                            'selector'=>'{{ULTP}} .ultp-news-ticker-label::after {display:none !important;}' 
                        ],    
                ],
            ],
            //--------------------------
            //      Ticker body  Style
            //--------------------------
            'tickerContentHeight' => [
                'type' => 'string',
                'default' =>'45',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-news-ticker li ,.editor-styles-wrapper {{ULTP}} .ultp-newsTicker-wrap ul li ,{{ULTP}} .ultp-news-ticker-label ,{{ULTP}} .ultp-newsTicker-wrap ,{{ULTP}} .ultp-news-ticker-controls ,{{ULTP}} .ultp-news-ticker-controls button { height:{{tickerContentHeight}}px; }'
                    ],
                ],
            ],
            'tickBodyColor' => [
                'type' => 'string',
                'default' => '#444',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-news-ticker li a, {{ULTP}} .ultp-news-ticker li div a, {{ULTP}} .ultp-news-ticker li div a span{ color:{{tickBodyColor}}; }'
                    ],
                ],
            ],
            'tickBodyHovColor' => [
                'type' => 'string',
                'default' => '#7d7d7d',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-news-ticker li a:hover, {{ULTP}} .ultp-news-ticker li div:hover,  {{ULTP}} .ultp-news-ticker li div a:hover span { color:{{tickBodyHovColor}}; }'
                    ],
                ],
            ],
            'tickBodyListColor' => [
                'type' => 'string',
                'default' => '#037fff',
                'style' => [
                    (object)[
                        'depends' => [
                                 (object)['key'=>'tickTxtStyle','condition'=>'==','value'=>'box'],
                              ],
                        'selector'=>'{{ULTP}} .ultp-list-box::before  { background-color:{{tickBodyListColor}};}'
                    ],
                    (object)[
                        'depends' => [
                                 (object)['key'=>'tickTxtStyle','condition'=>'==','value'=>'circle'],
                              ],
                        'selector'=>'{{ULTP}} .ultp-list-circle::before  { background-color:{{tickBodyListColor}};}'
                    ],
                    (object)[
                        'depends' => [
                                 (object)['key'=>'tickTxtStyle','condition'=>'==','value'=>'hand'],
                              ],
                        'selector'=>'{{ULTP}} .ultp-list-hand::before  { color:{{tickBodyListColor}};}'
                    ],
                    (object)[
                        'depends' => [
                                 (object)['key'=>'tickTxtStyle','condition'=>'==','value'=>'right-sign'],
                              ],
                        'selector'=>'{{ULTP}} .ultp-list-right-sign::before  { color:{{tickBodyListColor}};}'
                    ],
                    (object)[
                        'depends' => [
                                 (object)['key'=>'tickTxtStyle','condition'=>'==','value'=>'right-bold'],
                              ],
                        'selector'=>'{{ULTP}} .ultp-list-right-bold::before  { color:{{tickBodyListColor}};}'
                    ],
                ],
            ],
            'tickerBodyBg' => [
                'type' => 'string',
                'default' => '#eeeeee',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-newsTicker-wrap .ultp-news-ticker-box , {{ULTP}} .ultp-news-ticker-controls {background-color:{{tickerBodyBg}}}'
                    ],
                ],
            ],
            'tickBodyTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography'=>1,'size'=>(object)['lg'=>'16', 'unit'=>'px'], 'spacing'=>(object)[ 'lg'=>'0', 'unit'=>'px'], 'height'=>(object)[ 'lg'=>'16', 'unit'=>'px'],'decoration'=>'none','transform' => '','family'=>'','weight'=>'500'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-news-ticker li a'
                    ],
                ],
            ],
            'tickBodyBorderColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-newsTicker-wrap { background-color:{{tickBodyBorderColor}};border-color:{{tickBodyBorderColor}} }'
                    ],
                ],
            ],
            'tickBodyBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0,'disableColor'=> true, 'width' => (object)[ 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0],'type' => 'solid' ],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-block-items-wrap .ultp-newsTicker-wrap'
                    ],
                ],
            ],
            'tickBodyRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                     (object)[
                        'selector'=>'{{ULTP}} .ultp-newsTicker-wrap ,{{ULTP}} .ultp-news-ticker-label ,{{ULTP}} .ultp-news-ticker-box { border-radius:{{tickBodyRadius}}; }{{ULTP}} .ultp-news-ticker-prev { border-top-right-radius:0px !important; border-bottom-right-radius:0px !important; border-radius:  {{tickBodyRadius}};  }'
                    ],
                ],
            ],
            'tickTxtStyle' => [
                'type' => 'string',
                'default' => 'normal',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-news-ticker li { list-style-type:none; }'
                    ],
                ],
            ],
            'tickImgWidth' => [
                'type' => 'string',
                'default' => '30',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tickImageShow','condition'=>'==','value'=>true],
                     ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker li div img {width:{{tickImgWidth}}px}'
                    ],
                ],
            ],
            'tickImgSpace' => [
                'type' => 'string',
                'default' => '10',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tickImageShow','condition'=>'==','value'=>true],
                     ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker li div img {margin-right: {{tickImgSpace}}px} .rtl {{ULTP}} .ultp-news-ticker li div img {margin-left: {{tickImgSpace}}px !important;}'
                    ],
                ],
            ],
            'tickImgRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => "30",'bottom' => "30",'left' => "30",'right' => "30", 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-news-ticker li div img { border-radius:{{tickImgRadius}}; }'
                    ],
                ],
            ],
            'tickBodySpace' => [
                'type' => 'object',
                'default' => (object)['lg'=>'21'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tickerType','condition'=>'==','value'=> 'marquee'],
                     ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker { gap:{{tickBodySpace}}px; }'
                    ],
                ],
            ],
            //--------------------------
            //      body time badge style
            //--------------------------
            'tickTimeBadge' => [
                'type' => 'string',
                'default' => 'Time Badge',
                'style' => [
                    (object)[
                        'depends' => [
                              (object)['key'=>'tickTimeShow','condition'=>'==','value'=>true],
                              (object)['key'=>'tickerType','condition'=>'!=','value'=>'typewriter'],
                       ],
                    ],
                ],
            ],
            'timeBadgeColor' => [
                'type' => 'string',
                'default' => '#fff',
                'style' => [
                    (object)[
                    'depends' => [
                            (object)['key'=>'tickTimeShow','condition'=>'==','value'=>true],
                            (object)['key'=>'tickerType','condition'=>'!=','value'=>'typewriter'],
                     ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker li .ultp-ticker-timebadge { color:{{timeBadgeColor}}; }'
                    ],
                ],
            ],
            'timeBadgeBg' => [
                'type' => 'object',
                'default' => (object)['openColor' => 1, 'type' => 'color', 'color' => '#444'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tickTimeShow','condition'=>'==','value'=>true],
                            (object)['key'=>'tickerType','condition'=>'!=','value'=>'typewriter'],
                     ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker li .ultp-ticker-timebadge'
                    ],
                ],
            ],
            'timeBadgeTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography'=>1,'size'=>(object)['lg'=>'12', 'unit'=>'px'], 'spacing'=>(object)[ 'lg'=>'0', 'unit'=>'px'], 'height'=>(object)[ 'lg'=>'16', 'unit'=>'px'],'decoration'=>'none','transform' => '','family'=>'','weight'=>'500'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tickTimeShow','condition'=>'==','value'=>true],
                            (object)['key'=>'tickerType','condition'=>'!=','value'=>'typewriter'],
                     ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker li .ultp-ticker-timebadge'
                    ],
                ],
            ],
            'timeBadgeRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => "100",'bottom' => "100",'left' => "100",'right' => "100", 'unit' =>'px']],
                'style' => [
                     (object)[
                        'depends' => [
                            (object)['key'=>'tickTimeShow','condition'=>'==','value'=>true],
                            (object)['key'=>'tickerType','condition'=>'!=','value'=>'typewriter'],
                     ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker li .ultp-ticker-timebadge { border-radius:{{timeBadgeRadius}}; }'
                    ],
                ],
            ],
            'timeBadgePadding' => [
                'type' => 'object',
                'default' => (object)['lg'=>(object)['top'=>3,'bottom'=>3, 'left'=>6, 'right'=>6, 'unit'=>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-news-ticker li .ultp-ticker-timebadge { padding: {{timeBadgePadding}} }'
                    ],
                ],
            ],
            //--------------------------
            //      Icon Navigator 
            //--------------------------
            'TickNavStyle' => [
                'type' => 'string',
                'default' => 'nav1',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'navControlToggle','condition'=>'==','value'=> true],
                        ],
                    ],
                ],
            ],
            'TickNavIconStyle' => [
                'type' => 'string',
                'default' => 'Angle2',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'navControlToggle','condition'=>'==','value'=> true],
                        ],
                    ],
                ],
            ],
            'TickNavColor' => [
                'type' => 'string',
                'default' => '#fff',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-news-ticker-controls button.ultp-news-ticker-arrow:after{border-color:{{TickNavColor}}} {{ULTP}} .ultp-news-ticker-controls button.ultp-news-ticker-arrow svg { fill:{{TickNavColor}}}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'TickNavIconStyle','condition'=>'==','value'=>'icon2'],
                       
                     ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-controls button.ultp-news-ticker-arrow:after , {{ULTP}} button.ultp-news-ticker-arrow:before{ border-right-color:{{TickNavColor}};border-left-color: {{TickNavColor}}; } '
                    ],
                
                ],
            ],
            'TickNavBg' => [
                'type' => 'object',
                'default' => (object)['openColor' => 1, 'type' => 'color', 'color' => '#037fff'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'TickNavStyle','condition'=>'==','value'=>'nav1'],
                     ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-controls button.ultp-news-ticker-arrow'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'TickNavStyle','condition'=>'==','value'=>'nav2'],
                     ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-controls button.ultp-news-ticker-arrow'
                    ],
                    (object)[
                            'depends' => [
                                (object)['key'=>'TickNavStyle','condition'=>'!=','value'=>'nav1'],
                                (object)['key'=>'TickNavStyle','condition'=>'!=','value'=>'nav2'],
                         ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-controls'
                    ],
                ],
            ],
            'TickNavHovColor' => [
                'type' => 'string',
                'default' => '#d0d0d0f4',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-news-ticker-controls button.ultp-news-ticker-arrow:hover:after{border-color:{{TickNavHovColor}}} {{ULTP}} .ultp-news-ticker-controls button.ultp-news-ticker-arrow svg:hover { fill:{{TickNavHovColor}}}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'TickNavIconStyle','condition'=>'==','value'=>'icon2'],
                     ],
                        'selector'=>'{{ULTP}} button.ultp-news-ticker-arrow:hover:after
                        , {{ULTP}} button.ultp-news-ticker-arrow:hover:before{ border-right-color:{{TickNavHovColor}};border-left-color:{{TickNavHovColor}}; }
                        '
                    ],
              
                ],
            ],
            'TickNavHovBg' => [
                'type' => 'object',
                'default' => (object)['openColor' => 1, 'type' => 'color', 'color' => '#53a0ff'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'TickNavStyle','condition'=>'==','value'=>'nav1'],
                     ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-controls button.ultp-news-ticker-arrow:hover'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'TickNavStyle','condition'=>'==','value'=>'nav2'],
                     ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-controls button.ultp-news-ticker-arrow:hover'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'TickNavStyle','condition'=>'==','value'=>'nav3'],
                     ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-controls button.ultp-news-ticker-arrow:hover'
                    ],
                    (object)[
                            'depends' => [
                                (object)['key'=>'TickNavStyle','condition'=>'!=','value'=>'nav1'],
                                (object)['key'=>'TickNavStyle','condition'=>'!=','value'=>'nav2'],
                                (object)['key'=>'TickNavStyle','condition'=>'!=','value'=>'nav3'],
                         ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-controls:hover'
                    ],
                ],
            ],
            // Pause Style
            'TickNavPause' => [
                'type' => 'string',
                'default' => 'Pause Icon Style',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'TickNavStyle','condition'=>'!=','value'=>'nav2'],
                            (object)['key'=>'controlToggle','condition'=>'==','value'=>true],
                        ],
                    ],
                ],
            ],
            'PauseColor' => [
                'type' => 'string',
                'default' => '#fff',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'TickNavStyle','condition'=>'!=','value'=>'nav2'],
                            (object)['key'=>'controlToggle','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-controls button.ultp-news-ticker-pause:before , {{ULTP}} .ultp-news-ticker-controls button.ultp-news-ticker-pause:before , {{ULTP}} .ultp-news-ticker-nav4 button.ultp-news-ticker-pause:before { border-color:{{PauseColor}};}'
                    ],
                ],
            ],
            'PauseHovColor' => [
                'type' => 'string',
                'default' => '#d0d0d0f4',
                'style' => [
                        (object)[
                            'depends' => [
                                (object)['key'=>'TickNavIconStyle','condition'=>'!=','value'=>'icon2'],
                                (object)['key'=>'controlToggle','condition'=>'==','value'=> true],
                         ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-controls button.ultp-news-ticker-pause:hover:before { border-color:{{PauseHovColor}};}'
                    ],
                ] 
            ],
            'PauseBg' => [
                'type' => 'object',
                'default' => (object)['openColor' => 1, 'type' => 'color', 'color' => '#2163ff'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'TickNavStyle','condition'=>'==','value'=>'nav1'],
                            (object)['key'=>'controlToggle','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-controls button.ultp-news-ticker-pause'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'TickNavStyle','condition'=>'==','value'=>'nav3'],
                            (object)['key'=>'controlToggle','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-controls button.ultp-news-ticker-pause'
                    ],
                ],
            ],
            
            'PauseHovBg' => [
                'type' => 'object',
                'default' =>  (object)['openColor' => 1, 'type' => 'color', 'color' => '#53a0ff'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'TickNavStyle','condition'=>'==','value'=>'nav1'],
                            (object)['key'=>'controlToggle','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-controls .ultp-news-ticker-pause:hover'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'TickNavStyle','condition'=>'==','value'=>'nav3'],
                            (object)['key'=>'controlToggle','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-news-ticker-controls .ultp-news-ticker-pause:hover'
                    ],
                ],
            ],
            //--------------------------
            //      Query Setting
            //--------------------------
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
                'default' => (object)['lg'=>4,'sm'=>4,'xs'=>4],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key' => 'queryType','condition' => '!=','value' => array('customPosts', 'posts')]
                        ]
                    ],
                ],
            ],
            'queryNumber' => [ // for compatibility since v.2.5.4
                'type' => 'string',
                'default' =>4,
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
            // 'queryInclude' => [
            //     'type' => 'string',
            //     'default' => '',
            // ],
            'queryExclude' => [
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
            //--------------------------
            //      General Setting
            //--------------------------
            'tickerType' => [
                'type' => 'string',
                'default' => 'vertical',
            ],
            'tickerPositionEna' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'tickerPosition' => [
                'type' => 'string',
                'default' => 'up',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tickerPositionEna','condition'=>'==','value'=> true],
                            (object)['key'=>'tickerPosition','condition'=>'==','value'=> 'up'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-news-sticky .ultp-newsTicker-wrap { position: fixed;width: 100%;top: 0;z-index: 101; left: 0; } .admin-bar .ultp-news-sticky .ultp-newsTicker-wrap {
                            top: 32px !important; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'tickerPositionEna','condition'=>'==','value'=> true],
                            (object)['key'=>'tickerPosition','condition'=>'==','value'=> 'down'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-news-sticky .ultp-newsTicker-wrap { position: fixed;width: 100%;bottom: 0; z-index: 9999999;left: 0; }'
                    ],
                ],
            ],
            'tickerHeading' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'tickTimeShow' => [
                'type' => 'boolean',
                'default' => true,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tickerType','condition'=>'!=','value'=>'typewriter'],
                        ],
                    ],
                ],
            ],
            'tickImageShow' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'navControlToggle' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'controlToggle' => [
                'type' => 'boolean',
                'default' => true,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'navControlToggle','condition'=>'==','value'=>true],
                            (object)['key'=>'TickNavStyle','condition'=>'!=','value'=>'nav2'],
                        ],
                    ],
                ],
            ],
            'pauseOnHover' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'tickerDirectionVer' => [
                'type' => 'string',
                'default' => 'up',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tickerType','condition'=>'==','value'=>'vertical'],
                            (object)['key'=>'tickerAnimation','condition'=>'==','value'=>'slide']
                        ],
                    ],
                ],
            ],
            'tickerDirectionHorizon' => [
                'type' => 'string',
                'default' => 'left',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tickerType','condition'=>'==','value'=>'horizontal'],
                            (object)['key'=>'tickerAnimation','condition'=>'==','value'=>'slide']
                        ],
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'tickerType','condition'=>'==','value'=>'marquee'],
                        ],
                    ],
                ],
            ],
            'tickerSpeed' => [
                'type' => 'string',
                'default' => '4000',
                'style' => [
                    (object)[
                        'depends' => [(object)['key' => 'tickerType','condition' => '!=','value' => "marquee"]]
                    ],
                ],
            ],
            'marqueSpeed' => [
                'type' => 'string',
                'default' => '10',
                'style' => [
                    (object)[
                        'depends' => [(object)['key' => 'tickerType','condition' => '==','value' => "marquee"]]
                    ],
                ],
            ],
            'tickerSpeedTypewriter' => [
                'type' => 'string',
                'default' => '50',
                'style' => [
                    (object)[
                        'depends' => [(object)['key' => 'tickerType','condition' => '==','value' => 'typewriter']]
                    ],
                ],
            ],
            'tickerAnimation' => [
                'type' => 'string',
                'default' => 'slide',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tickerType','condition'=>'!=','value'=>'marquee'],
                            (object)['key'=>'tickerType','condition'=>'!=','value'=>'typewriter'],
                        ],
                    ],
                ],
            ],
            'typeAnimation' => [
                'type' => 'string',
                'default' => 'fadein',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tickerType','condition'=>'==','value'=>'typewriter'],
                        ],
                    ],
                ],
            ],
            'openInTab' => [
                'type' => 'boolean',
                'default' => false,
            ],

            //--------------------------
            //      Heading Setting/Style
            //--------------------------
            'headingText' => [
                'type' => 'string',
                'default' => 'News Ticker',
            ],



            //--------------------------
            //  Advanced
            //--------------------------
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
        register_block_type( 'ultimate-post/news-ticker',
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

        $block_name = 'news-ticker';
        $page_post_id = ultimate_post()->get_ID();
        $wraper_before = $wraper_after = $post_loop = '';

        $attr['queryNumber'] = ultimate_post()->get_post_number(4, $attr['queryNumber'], $attr['queryNumPosts']);
        $recent_posts = new \WP_Query( ultimate_post()->get_query( $attr ) );
        $arrowLeft = "";
        $arrowRight = "";
        if ($attr['TickNavIconStyle'] != "icon2") {
            $arrowLeft .=  ultimate_post()->svg_icon('left'.$attr['TickNavIconStyle']);
            $arrowRight .= ultimate_post()->svg_icon('right'.$attr['TickNavIconStyle']);
        }


        if ($recent_posts->have_posts()) {
            $wraper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].''.(isset($attr["className"])?' '.$attr["className"]:'').'">';
            $wraper_before .= '<div class="ultp-block-wrapper ultp-news-sticky">';
                    $wraper_before .= '<div class="ultp-block-items-wrap">';

                        $post_loop .= '<div class="ultp-news-ticker-'.$attr['TickNavStyle'].' ultp-nav-'.$attr['TickNavIconStyle'].'  ultp-newsTicker-wrap ultp-newstick-'.$attr['tickerType'].' ">';
                            if ($attr['tickerHeading'] && $attr['headingText']) {
                                $post_loop .= '<div class="ultp-news-ticker-label">'.$attr['headingText'].'</div>';
                            }

                            $post_loop .= '<div class="ultp-news-ticker-box ultp-animation-'.($attr['tickerType'] == 'typewriter' ? $attr['typeAnimation'] : $attr['tickerAnimation'] ).' ultp-sliderDir-'.($attr['tickerType'] == 'vertical' ? $attr['tickerDirectionVer'] : $attr['tickerDirectionHorizon'] ).'">';
                                $speed = $attr['tickerType'] != 'marquee' ? $attr['tickerSpeed'] : $attr['marqueSpeed'];
                                $post_loop .= '<ul class="ultp-news-ticker" data-type="'.$attr['tickerType'].'" data-hover="'.$attr['pauseOnHover'].'" data-direction="'.($attr['tickerType'] == 'vertical' ? $attr['tickerDirectionVer'] : $attr['tickerDirectionHorizon'] ).'" data-speed="'.$speed.'">';
                                while ( $recent_posts->have_posts() ): $recent_posts->the_post();                  
                                    $post_id        = get_the_ID();
                                    $title          = get_the_title( $post_id );
                                    $titlelink      = get_permalink( $post_id );
                                    if ($attr['queryUnique']) {
                                        $unique_ID[$attr['queryUnique']][] = $post_id;
                                    }
                                    $typeStyle = $style = '';
                                    if ($attr['tickerType'] != 'marquee' && $attr['tickerType'] != 'typewriter' && $attr['tickerAnimation'] == "fadeout") {
                                        $style .= 'animation-delay:'.($speed - 1000).'ms';
                                    }
                                    if ($attr['tickerType'] == 'typewriter' && $attr['typeAnimation'] == "fadeout") {
                                        $style .= 'animation-delay:'.($speed - 1000).'ms';
                                    }
                                    if ($attr['tickerType'] == 'typewriter') {
                                        $typeStyle .= ' animation-duration:'.($speed - 1000).'ms';
                                    }
                                    $post_loop .= '<li style='.$style.'>';
                                        $post_loop .= '<div class="ultp-list-'.$attr['tickTxtStyle'].'">';
                                            $post_loop .= '<a '.($attr['openInTab'] ? 'target="_blank"' : '').' href="'.$titlelink.'">';
                                                if ($attr['tickImageShow'] && has_post_thumbnail()) {
                                                    $post_loop .= ultimate_post()->get_image( get_post_thumbnail_id( $post_id ), 'thumbnail', '', $title, '' , '' );
                                                }
                                                $post_loop .= '<span style="'.$typeStyle.'" class="title-text" data-text="'.$title.'">'.$title.'</span>';
                                                $post_loop .= '</a>';
                                            if ($attr['tickTimeShow'] && ($attr['tickerType'] != 'typewriter' ) ) {
                                                $post_loop .= '<span class="ultp-ticker-timebadge">'.human_time_diff(get_the_time('U'),current_time('U')).' ago</span>';
                                            }
                                        $post_loop .= '</div>';
                                    $post_loop .= '</li>';
                                endwhile;
                                $post_loop .= '</ul>';
                            $post_loop .= '</div>';

                            if ($attr['navControlToggle'] &&  $attr['TickNavStyle']  ) {
                                $post_loop .= '<div class="ultp-news-ticker-controls ultp-news-ticker-vertical-controls">';
                                        $post_loop .= '<button aria-label="'.__("Show Previous Post", "ultimate-post").'" class="ultp-news-ticker-arrow ultp-news-ticker-prev prev">'.$arrowLeft.'</button>';
                                        if ($attr['controlToggle'] && ( $attr['TickNavStyle']  == "nav1" ||  $attr['TickNavStyle']  == "nav3" ||  $attr['TickNavStyle']  == "nav4" )) {
                                            $post_loop .= '<button aria-label="'.__("Pause Current Post", "ultimate-post").'" id="ultp-pause-btn" class="ultp-news-ticker-pause pause"></button>';
                                        }
                                        $post_loop .= '<button aria-label="'.__("Show Next Post", "ultimate-post").'" class="ultp-news-ticker-arrow ultp-news-ticker-next next">'.$arrowRight.'</button>';
                                $post_loop .= '</div>';
                            }
                        $post_loop .= '</div>';
                    $wraper_after .= '</div>';
                $wraper_after .= '</div>';
            $wraper_after .= '</div>';

            wp_reset_query();
        }

        return $noAjax ? $post_loop : $wraper_before.$post_loop.$wraper_after;
    }

}