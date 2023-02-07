<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Advance_Post_Meta {
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
                Advanced Post Meta Settings
            ============================*/
            //  META ITEM ENABLE 
            "authorShow" => [
                "type" => "boolean",
                "default" => true,
            ],
            "dateShow" => [
                "type" => "boolean",
                "default" => true,
            ],
            "cmtCountShow" => [
                "type" => "boolean",
                "default" => true,
            ],
            "viewCountShow" => [
                "type" => "boolean",
                "default" => false,
            ],
            "readTimeShow" => [
                "type" => "boolean",
                "default" => false,
            ], 
            "catShow" => [
                'type' => 'boolean',
                'default' => false,
            ],
            "tagShow" => [
                "type" => "boolean",
                "default" => false,
            ],
            'metaSpacing' => [
                'type' => 'object',
                'default' => (object)['lg' =>'15', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-meta-separator::after {margin: 0 {{metaSpacing}};}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaSeparator','condition'=>'==','value'=>'emptyspace'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-meta-separator:not(:first-child):not(:last-child) {margin: 0 {{metaSpacing}}; }'
                    ],
                ],
            ],
            'metaAlign' => [
                'type' => 'string',
                'default' => 'left',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaAlign','condition'=>'==','value'=>"right"],
                            (object)['key'=>'authAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'dateAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'cmntAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'viewAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'readAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'catAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'tagAlign','condition'=>'==','value'=> false],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-wrapper .ultp-advance-post-meta, {{ULTP}} .ultp-contentMeta > div { justify-content:{{metaAlign}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaAlign','condition'=>'==','value'=>"center"],
                            (object)['key'=>'authAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'dateAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'cmntAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'viewAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'readAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'catAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'tagAlign','condition'=>'==','value'=> false],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-wrapper .ultp-advance-post-meta, {{ULTP}} .ultp-contentMeta > div { justify-content:{{metaAlign}}; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaAlign','condition'=>'==','value'=>"left"],
                            (object)['key'=>'authAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'dateAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'cmntAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'viewAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'readAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'catAlign','condition'=>'==','value'=> false],
                            (object)['key'=>'tagAlign','condition'=>'==','value'=> false],
                        ],
                        'selector'=>'{{ULTP}} .ultp-block-wrapper .ultp-advance-post-meta, {{ULTP}} .ultp-contentMeta > div { justify-content:{{metaAlign}}; }'
                    ],
                ],
            ],
            'metaSeparator' => [
                'type' => 'string',
                'default' => 'dot',
            ],
            'separatorColor' => [
                'type' => 'string',
                'default' => '#000',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-meta-separator::after { color: {{separatorColor}};}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'metaSeparator','condition'=>'==','value'=>'dot'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-meta-separator::after { background:{{separatorColor}};}'
                    ],
                ],
            ],
            'metaItemSort' => [
                'type' => 'array',
                'default' => ["author", "date", "cmtCount", "viewCount", "readTime", "cat", "tag"],
            ],

            /*============================
                Post Author Style
            ============================*/
            'authColor' => [
                'type' => 'string',
                'default' => '#6b6b6b',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-auth-name { color:{{authColor}} }'
                    ],
                ],
            ],
            'authHovColor' => [
                'type' => 'string',
                'default' => '#ddd',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-auth-name:hover { color:{{authHovColor}} }'
                    ],
                ],
            ],
            'authTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>15, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-auth-name, {{ULTP}} .ultp-auth-heading'
                    ],
                ],
            ],
            // Avatar
            'authImgShow' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'authImgSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'18', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authImgShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-auth-heading  img { width:{{authImgSize}}; height:{{authImgSize}} }'
                    ],
                ],
            ],
            'authImgRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'50', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authImgShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-auth-heading  img { border-radius:{{authImgRadius}} }'
                    ],
                ],
            ],
            'authImgSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'5', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authImgShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-auth-heading  img { margin-right:{{authImgSpace}} }'
                    ],
                ],
            ],
            // Author Label
            'authLabelShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'authLabel' => [
                'type' => 'string',
                'default' => 'Author',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authLabelShow','condition'=>'==','value'=>true],
                        ],
                    ],
                ],
            ],
            'authLabelColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-auth-heading { color:{{authLabelColor}} }'
                    ],
                ],
            ],
            'authLabelTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>15, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-auth-heading .ultp-auth-label'
                    ],
                ],
            ],
            'authLabelSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'5', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-auth-heading .ultp-auth-label { margin-right:{{authLabelSpace}};   }'
                    ],
                ],
            ],
            // Auth Icon
            'authIconShow' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'authIconStyle' => [
                'type' => 'string',
                'default' => 'author1',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authIconShow','condition'=>'==','value'=>true],
                        ],
                    ],
                ],
            ],
            'authIconColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authIconShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-auth-heading svg { fill:{{authIconColor}}; stroke:{{authIconColor}} }'
                    ],
                ],
            ],
            'authIconSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'16', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authIconShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-auth-heading svg { height:{{authIconSize}}; width:{{authIconSize}} }'
                    ],
                ],
            ],
            'authIconSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'10', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-auth-heading svg { margin-right:{{authIconSpace}} }'
                    ],
                ],
            ],
            "authAlign" => [
                "type" => "boolean",
                "default" => false,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authorShow','condition'=>'==','value'=> true],
                        ],
                    ],
                ],
            ],

            /*============================
                Post Publish Time Style
            ============================*/
            'dateFormat' => [
                'type' => 'string',
                'default' => 'updated'
            ],
            'metaDateFormat' => [
                'type' => 'string',
                'default' => 'M j, Y'
            ],
            'dateColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'dateFormat','condition'=>'==','value'=>"publish"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-date .ultp-post-date__val { color:{{dateColor}} }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'dateFormat','condition'=>'==','value'=>"updated"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-update .ultp-post-date__val { color:{{dateColor}} }'
                    ],
                ],  
            ],
            'dateTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>15, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'dateFormat','condition'=>'==','value'=> "publish"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-date'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'dateFormat','condition'=>'==','value'=> "updated"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-post-update'
                    ],
                ],
            ],
            // Prefix
            'enablePrefix' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'datePubText' => [
                'type' => 'string',
                'default' => 'Publish Update',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'enablePrefix','condition'=>'==','value'=> true],
                            (object)['key'=>'dateFormat','condition'=>'==','value'=> "publish"],
                        ],
                    ],
                ],  
            ],
            'dateText' => [
                'type' => 'string',
                'default' => 'Latest Update',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'dateFormat','condition'=>'==','value'=>"updated"],
                            (object)['key'=>'enablePrefix','condition'=>'==','value'=> true],
                        ],
                    ],
                ],  
            ],
            'datePrefixColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'enablePrefix','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-date-prefix {color:{{datePrefixColor}}}'
                    ],
                ],  
            ],
            // Icon
            'DateIconShow' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'dateIconStyle' => [
                'type' => 'string',
                'default' => 'date1',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'DateIconShow','condition'=>'==','value'=> true],
                        ],
                    ],
                ],
            ],
            'dateIconColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'DateIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-date-icon svg { fill:{{dateIconColor}}; stroke:{{dateIconColor}}; }'
                    ],
                ],  
            ],
            'dateIconSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'16', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'DateIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-date-icon svg{ width:{{dateIconSize}}; height:{{dateIconSize}} }'
                    ],
                ],
            ],
            'dateIconSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'10', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'DateIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-date-icon svg { margin-right:{{dateIconSpace}} }'
                    ],
                ],
            ],
            "dateAlign" => [
                "type" => "boolean",
                "default" => false,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'dateShow','condition'=>'==','value'=> true],
                        ],
                    ],
                ],
            ],

            /*============================
                Comment Style
            ============================*/
            'commentColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-count { color:{{commentColor}} }'
                    ],
                ],  
            ],
            'commentHovColor' => [
                'type' => 'string',
                'default' => '#ddd',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-count:hover { color:{{commentHovColor}} }'
                    ],
                ],  
            ],
            'commentTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>15, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-count, {{ULTP}} .ultp-comment-label'
                    ],
                ],
            ],
            // Prefix
            'cmtLabelShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'cmtLabel' => [
                'type' => 'string',
                'default' => 'Comment',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'cmtLabelShow','condition'=>'==','value'=>true],
                        ],
                    ],
                ],
            ],
            'cmtLabelColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'cmtLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-label { color:{{cmtLabelColor}} }'
                    ],
                ],  
            ],
            "commentLabelAlign" => [
                "type" => "string",
                "default" => "after",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'commentLabelAlign','condition'=>'==','value'=> "before"],
                            (object)['key'=>'cmtLabelShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-label {order: -1;margin-right: 5px;}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'commentLabelAlign','condition'=>'==','value'=> "after"],
                            (object)['key'=>'cmtLabelShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-label {order: 0; margin-left: 5px;}'
                    ],
                ], 
            ],
            //  Icon
            'cmtIconShow' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'cmntIconStyle' => [
                'type' => 'string',
                'default' => 'commentCount1',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'cmtIconShow','condition'=>'==','value'=> true],
                        ],
                    ],
                ],
            ],
            'cmntIconColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'cmtIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-count svg { fill:{{cmntIconColor}}; stroke:{{cmntIconColor}}}'
                    ],
                ],  
            ],
            'commentIconSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'16', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'cmtIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-count svg{ width:{{commentIconSize}}; height:{{commentIconSize}} }'
                    ],
                ],
            ],
            'cmntIconSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'10', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'cmtIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-count svg { margin-right:{{cmntIconSpace}} }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'cmtIconShow','condition'=>'==','value'=> true],
                            (object)['key'=>'cmtLabelShow','condition'=>'==','value'=> true],
                            (object)['key'=>'commentLabelAlign','condition'=>'==','value'=> "before"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-count svg { margin:0px {{cmntIconSpace}} } {{ULTP}} .ultp-comment-label {margin:0px !important;}'
                    ],
                ],
            ],
            "cmntAlign" => [
                "type" => "boolean",
                "default" => false,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'cmtCountShow','condition'=>'==','value'=>true],
                        ],
                    ],
                ],
            ],
            /*============================
                View Style
            ============================*/
            'viewColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-view-count { color:{{viewColor}} }'
                    ],
                ],  
            ],
            'viewHovColor' => [
                'type' => 'string',
                'default' => '#ddd',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-view-count:hover { color:{{viewHovColor}} }'
                    ],
                ],  
            ],
            
            'viewTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>15, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-view-count, {{ULTP}} .ultp-view-label'
                    ],
                ],
            ],
            // Prefix Style
            'viewLabelShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'viewLabel' => [
                'type' => 'string',
                'default' => 'View',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'viewLabelShow','condition'=>'==','value'=>true],
                        ],
                    ],
                ], 
            ],
            'viewLabelColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'viewLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-view-label { color:{{viewLabelColor}} }'
                    ],
                ],  
            ],
            "viewLabelAlign" => [
                "type" => "string",
                "default" => "after",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'viewLabelAlign','condition'=>'==','value'=> "before"],
                            (object)['key'=>'viewLabelShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-view-label {order: -1;margin-right: 5px;}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'viewLabelAlign','condition'=>'==','value'=> "after"],
                            (object)['key'=>'viewLabelShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-view-label {order: 0; margin-left: 5px;}'
                    ],
                ], 
            ],
            //  Icon
            'viewIconShow' => [
                'type' => 'boolean',
                'default' => false,
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
            'viewIconColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'viewIconShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-view-count svg { fill:{{viewIconColor}}; stroke:{{viewIconColor}} }'
                    ],
                ],  
            ],
            'viewIconSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'16', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'viewIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-view-count svg{ width:{{viewIconSize}}; height:{{viewIconSize}} }'
                    ],
                ],
            ],
            'viewIconSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'10', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'viewIconShow','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-view-count svg {margin-right:{{viewIconSpace}}}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'viewIconShow','condition'=>'==','value'=> true],
                            (object)['key'=>'viewLabelShow','condition'=>'==','value'=> true],
                            (object)['key'=>'viewLabelAlign','condition'=>'==','value'=> 'before'],
                        ],
                        'selector'=>'{{ULTP}} .ultp-view-count svg { margin:0px {{viewIconSpace}} } {{ULTP}} .ultp-view-label {margin:0px !important;}'
                    ],
                ],
            ],
            "viewAlign" => [
                "type" => "boolean",
                "default" => false,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'viewCountShow','condition'=>'==','value'=> true],
                        ],
                    ],
                ],
            ],

            /*============================
                Reading Time Style
            ============================*/
            'readColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-readTime-wrap { color:{{readColor}} }'  
                    ],
                ],  
            ],
            'readHovColor' => [
                'type' => 'string',
                'default' => '#ddd',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-readTime-wrap:hover { color:{{readHovColor}} }'
                    ],
                ],  
            ],
            'readTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>15, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-readTime-wrap'
                    ],
                ],
            ],
            // Prefix
            'readTimePrefix' => [
                'type' => 'boolean' ,
                'default' => true,
            ],
            'readTimeText' => [
                'type' => 'string',
                'default' => 'Minute Read',
                (object)[
                    'depends' => [
                        (object)['key'=>'readTimePrefix','condition'=>'==','value'=>true],
                    ],
                ],
            ],
            "readPrefixAlign" => [
                "type" => "string",
                "default" => "after",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readPrefixAlign','condition'=>'==','value'=> "before"],
                            (object)['key'=>'readTimePrefix','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-read-label {order: -1;margin-right: 5px;}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'readPrefixAlign','condition'=>'==','value'=> "after"],
                            (object)['key'=>'readTimePrefix','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-read-label {order: 0; margin-left: 5px;}'
                    ],
                ], 
            ],
            //  Icon
            'readTimeIcon' => [
                'type' => 'boolean' ,
                'default' => false,
            ],
            'readIconStyle' => [
                'type' => 'string',
                'default' => 'readingTime2',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readTimeIcon','condition'=>'==','value'=>true],
                        ],
                    ],
                ],
            ],
            'readIconColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readTimeIcon','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-readTime-wrap svg{ fill:{{readIconColor}}; stroke:{{readIconColor}}; }'
                    ],
                ],  
            ],
            'readIconSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'16', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readTimeIcon','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-readTime-wrap svg{ width:{{readIconSize}}; height:{{readIconSize}} }'
                    ],
                ],
            ],
            'readIconSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'10', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readTimeIcon','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-readTime-wrap svg { margin-right:{{readIconSpace}} }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'readTimeIcon','condition'=>'==','value'=> true],
                            (object)['key'=>'readTimePrefix','condition'=>'==','value'=> true],
                            (object)['key'=>'readPrefixAlign','condition'=>'==','value'=> "before"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-readTime-wrap svg { margin:0px {{readIconSpace}} } {{ULTP}} .ultp-read-label {margin:0px !important;}'
                    ],
                ],
            ],
            "readAlign" => [
                "type" => "boolean",
                "default" => false,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'readTimeShow','condition'=>'==','value'=>true],
                        ],
                    ],
                ],
            ],
            
            /*============================
                Categories Style
            ============================*/
            'catColor' => [
                'type' => 'string',
                'default' => '#545454',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-cat a { color:{{catColor}} }'
                    ],
                ],  
            ],
            'catHovColor' => [
                'type' => 'string',
                'default' => '#ddd',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-cat a:hover { color:{{catHovColor}} }'
                    ],
                ],  
            ],
            'catTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1, 'decoration' => 'none', 'size' => (object)['lg' =>15, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-cat a, {{ULTP}} .ultp-cat-label'
                    ],
                ],
            ],
            'catSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'7', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-cat a:not(:first-child) { margin-left:{{catSpace}} }'
                    ],
                ],
            ],
            'catLabelShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'catLabel' => [
                'type' => 'string',
                'default' => 'Category',
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
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-cat-label { color:{{catLabelColor}} }'
                    ],
                ],  
            ],
            'catLabelSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'15', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-cat-label { margin-right:{{catLabelSpace}};}'
                    ],
                ],
            ],
            'catIconShow' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'catIconStyle' => [
                'type' => 'string',
                'default' => 'cat2',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catIconShow','condition'=>'==','value'=> true],
                        ],
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
                        'selector'=>'{{ULTP}} .ultp-cat-wrap svg { height:{{catIconSize}}; width:{{catIconSize}} }'
                    ],
                ],
            ],
            'catIconColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catIconShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-cat-wrap svg, {{ULTP}} .ultp-cat-wrap svg path, {{ULTP}} .ultp-cat-wrap svg rect{ fill:{{catIconColor}}; stroke:{{catIconColor}} }'
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
                        'selector'=>'{{ULTP}} .ultp-cat-wrap  svg {margin-right:{{catIconSpace}} }'
                    ],
                ],
            ],
            "catAlign" => [
                "type" => "boolean",
                "default" => false,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'catShow','condition'=>'==','value'=>true],
                        ],
                    ],
                ],
            ],

            /*============================
                Tag Style
            ============================*/
            'tagColor' => [
                'type' => 'string',
                'default' => '#545454',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-tag a { color:{{tagColor}} }'
                    ],
                ],  
            ],
            'tagHovColor' => [
                'type' => 'string',
                'default' => '#ddd',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-tag a:hover { color:{{tagHovColor}} }'
                    ],
                ],  
            ],
            'tagTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1, 'decoration' => 'none', 'size' => (object)['lg' =>15, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-tag a, {{ULTP}} .ultp-tag-label'
                    ],
                ],
            ],
            'tagSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'7', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-post-tag a:not(:first-child) { margin-left:{{tagSpace}};}'
                    ],
                ],
            ],
            'tagLabelShow' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'tagLabel' => [
                'type' => 'string',
                'default' => 'Tag - ',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tagLabelShow','condition'=>'==','value'=>true],
                        ],
                    ],
                ],  
            ],
            'tagLabelColor' => [
                'type' => 'string',
                'default' => '#a4a4a4',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tagLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-tag-label { color:{{tagLabelColor}} }'
                    ],
                ],  
            ],
            'tagLabelSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'15', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tagLabelShow','condition'=>'==','value'=>true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-tag-label { margin-right:{{tagLabelSpace}};}'
                    ],
                ],
            ],
            'tagIconShow' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'tagIconStyle' => [
                'type' => 'string',
                'default' => 'tag2',
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
                        'selector'=>'{{ULTP}} .ultp-tag-wrap svg, {{ULTP}} .ultp-tag-wrap svg path {fill:{{tagIconColor}}; stroke:{{tagIconColor}} }'
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
                        'selector'=>'{{ULTP}} .ultp-tag-wrap svg {height:{{tagIconSize}}; width:{{tagIconSize}}}'
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
                        'selector'=>'{{ULTP}} .ultp-tag-wrap svg {margin-right:{{tagIconSpace}} }'
                    ],
                ],
            ],
            "tagAlign" => [
                "type" => "boolean",
                "default" => false,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'tagShow','condition'=>'==','value'=>true],
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
        register_block_type( 'ultimate-post/advance-post-meta',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );
    }


    public function content($attr, $noAjax) {
        $block_name = 'post_meta';
        $wrapper_before = $wrapper_after = $wrapper_content = $authContent = $updateLabel = $dateLabel = "";


        $post_id = get_the_ID();
        $contentAlign = ($attr["catAlign"] || $attr["tagAlign"] || $attr["cmntAlign"] || $attr["viewAlign"] || $attr["readAlign"] || $attr["authAlign"] || $attr["dateAlign"]) ? 'ultp-contentMeta-align' : 'ultp-contentMeta';



        // Author Content
        if ($attr["authorShow"] ) {
            $author_id = get_post_field('post_author' , $post_id);
            $authContent .= '<span class="ultp-post-auth ultp-meta-separator">';
                $authContent .= '<span class="ultp-auth-heading">';
                    if ($attr["authIconShow"]) {
                        $authContent .= ultimate_post()->svg_icon(''.$attr["authIconStyle"].'');
                    } 
                    if ($attr["authImgShow"]) {
                        $authContent .= get_avatar( $author_id, 32 );
                    }
                    if ($attr["authLabelShow"] ) {
                        $authContent .= '<span class="ultp-auth-label">'.$attr["authLabel"].'</span>';
                    } 
                $authContent .= ' </span>';
                $authContent .= '<a  href="'.get_author_posts_url( $author_id ).'" class="ultp-auth-name">';
                    $authContent .= get_the_author_meta('display_name', $author_id);
                $authContent .= '</a>';
            $authContent .= '</span>';
        }  

        // Date Content
        if($attr["enablePrefix"]){
            $dateLabel .= '<span class="ultp-date-prefix">'.$attr["dateText"].'</span>';
        }
        if($attr["enablePrefix"]){
            $updateLabel .= '<span class="ultp-date-prefix">'.$attr["datePubText"].'</span>';
        }
        $dateContent = "";
        if ($attr["dateShow"] ) {
            $dateContent .= '<span class="ultp-date-meta ultp-meta-separator">';
                if ($attr["DateIconShow"] ) {
                    $dateContent .='<span class="ultp-date-icon">'.ultimate_post()->svg_icon(''.$attr["dateIconStyle"].'').'</span>';
                }
                if ($attr["dateFormat"] == "updated" ) {
                    $dateContent .='<span class="ultp-post-update">'.$dateLabel.'<span class="ultp-post-date__val">'.get_the_modified_date(ultimate_post()->get_format($attr["metaDateFormat"]), $post_id).'</span></span>';
                }
                if ($attr["dateFormat"] == "publish") {
                    $dateContent .= '<span class="ultp-post-date">'.$updateLabel.' <span class="ultp-post-date__val">'.get_the_date(ultimate_post()->get_format($attr["metaDateFormat"]), $post_id).'</span></span>';
                }
            $dateContent .= '</span>';
        }

        // Main Content
        $wrapper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').''.(isset($attr["align"])? ' align' .$attr["align"]:'').'">';
            $wrapper_before .= '<div class="ultp-block-wrapper">';
                $wrapper_content .= '<div class="ultp-advance-post-meta '.$contentAlign.' ultp-post-meta-'.$attr["metaSeparator"].'">'; 
                    $wrapper_content .= '<div>'; 
                        foreach($attr["metaItemSort"] as $val) {
                            if ($val == "author" && $attr["authorShow"] && $attr["authAlign"] == false) {
                                $wrapper_content .= $authContent;
                            }
                            if ($val == "date" && $attr["dateShow"] && $attr["dateAlign"] == false) {
                                $wrapper_content .= $dateContent;
                            }
                            if ($val == "cmtCount" && $attr["cmtCountShow"] && $attr["cmntAlign"] == false) {
                                $wrapper_content .= $this->renderPostCount("comment", get_post_field('comment_count' , ''), $attr["cmtLabelShow"], $attr["cmtLabel"], $attr["cmtIconShow"],ultimate_post()->svg_icon(''.$attr["cmntIconStyle"].''), $post_id);
                            }
                            if ($val == "viewCount" && $attr["viewCountShow"] && $attr["viewAlign"] == false) {
                                $wrapper_content .= $this->renderPostCount("view", get_post_meta( $post_id, 'post_views_count', true ), $attr["viewLabelShow"], $attr["viewLabel"], $attr["viewIconShow"], ultimate_post()->svg_icon(''.$attr["viewIconStyle"].''), $post_id);
                            }
                            if ($val == "readTime" && $attr["readTimeShow"] && $attr["readAlign"] == false) {
                                $wrapper_content .= $this->renderPostCount("readTime", 12, $attr["readTimePrefix"] , $attr["readTimeText"], $attr["readTimeIcon"], ultimate_post()->svg_icon(''.$attr["readIconStyle"].''), $post_id );
                            }
                            if ($val == "cat" && $attr["catShow"] && $attr["catAlign"] == false) {
                                $wrapper_content .= $this->renderPostCount("cat", get_the_category(), $attr["catLabelShow"], $attr["catLabel"], $attr["catIconShow"], ultimate_post()->svg_icon(''.$attr["catIconStyle"].''), $post_id);
                            }
                            if ($val == "tag" && $attr["tagShow"] && $attr["tagAlign"] == false) {
                                $wrapper_content .= $this->renderPostCount("tag",get_the_tags(), $attr["tagLabelShow"], $attr["tagLabel"], $attr["tagIconShow"], ultimate_post()->svg_icon(''.$attr["tagIconStyle"].''), $post_id);
                            }
                        }
                    $wrapper_content .= '</div>';
                    $wrapper_content .= '<div>'; 
                        foreach($attr["metaItemSort"] as $content) {
                            if ($content == "author" && $attr["authorShow"] && $attr["authAlign"]) {
                                $wrapper_content .= $authContent;
                            }
                            if ($content == "date" && $attr["dateShow"] && $attr["dateAlign"]) {
                                $wrapper_content .= $dateContent;
                            }
                            if ($content == "cmtCount" && $attr["cmtCountShow"] && $attr["cmntAlign"]) {
                                $wrapper_content .= $this->renderPostCount("comment", get_post_field('comment_count' , ''), $attr["cmtLabelShow"], $attr["cmtLabel"], $attr["cmtIconShow"], ultimate_post()->svg_icon(''.$attr["cmntIconStyle"].''), $post_id);
                            }
                            if ($content == "viewCount" && $attr["viewCountShow"] && $attr["viewAlign"]) {
                                $wrapper_content .= $this->renderPostCount("view", get_post_meta( get_the_ID(), 'post_views_count', true ), $attr["viewLabelShow"], $attr["viewLabel"], $attr["viewIconShow"], ultimate_post()->svg_icon(''.$attr["viewIconStyle"].''), $post_id);
                            }
                            if ($content == "readTime" && $attr["readTimeShow"] && $attr["readAlign"]) {
                                $wrapper_content .= $this->renderPostCount("readTime", 12, $attr["readTimePrefix"], $attr["readTimeText"], $attr["readTimeIcon"], ultimate_post()->svg_icon(''.$attr["readIconStyle"].''), $post_id);
                            }
                            if ($content == "cat" && $attr["catShow"] && $attr["catAlign"]) {
                                $wrapper_content .= $this->renderPostCount("cat", get_the_category(), $attr["catLabelShow"], $attr["catLabel"], $attr["catIconShow"], ultimate_post()->svg_icon(''.$attr["catIconStyle"].''), $post_id);
                            }
                            if ($content == "tag" && $attr["tagShow"] && $attr["tagAlign"]) {
                                $wrapper_content .= $this->renderPostCount("tag",get_the_tags(), $attr["tagLabelShow"], $attr["tagLabel"], $attr["tagIconShow"], ultimate_post()->svg_icon(''.$attr["tagIconStyle"].''), $post_id);
                            }
                        }
                    $wrapper_content .= '</div>'; 
                $wrapper_content .= '</div>'; 
            $wrapper_after .= '</div>';
        $wrapper_after .= '</div>';

        return $wrapper_before.$wrapper_content.$wrapper_after;
    }

    public function renderPostCount($title, $data, $labelEnable, $labelText, $iconEnable, $icon, $post_id = null) {
        $content = "";
        $content .= '<span class="ultp-'.$title.'-wrap ultp-meta-separator">';
            if (($title == "tag" || $title == "cat") && $iconEnable) {
                $content .='<span>'.$icon.'</span>';
            }
            if (($title == "tag" || $title == "cat") &&  ( $labelEnable) ) {
                $content .= '<span class="ultp-'.$title.'-label">'.$labelText.'</span>';
            }
            if ($title == "tag" || $title == "cat" ) {
                $content .= '<span class="ultp-post-'.$title.'">';
                if (is_array($data) && count($data) > 0) {
                    if (is_array($data)) {
                        foreach($data as $dt) {
                            $content .=  '<a href="'.get_category_link($dt->term_taxonomy_id).'">'.$dt->name.'</a>'; 
                        }
                    }
                } else { 
                    $content .= "<a>No Taxonomy Found</a>";
                }
                $content .=  '</span>';
            } elseif (($title != "tag" && $title != "cat" && $title != "readTime" )) {
                $content .= '<span class="ultp-'.$title.'-count">';
                    if ($iconEnable ) { $content .= $icon; }
                    $content .= $data ? $data : 0; 
                $content .= '</span>';
            } 
            if(( $title != "tag" && $title != "cat" && $title != "readTime" )  && $labelEnable ) {
                $content .= '<span class="ultp-'.$title.'-label">'.$labelText.'</span>';
            } 
            if ($title == "readTime" ) {
                if ($iconEnable ) { $content .= $icon; }

                $content .= '<div>'.ceil(mb_strlen(strip_tags(get_the_content( null,  false, $post_id )))/1200).'</div>';
                $content .=  $labelEnable ? '<span class="ultp-read-label">'.$labelText.'</span>' : '' ;
                $content .= '</span>';
            }
        $content .= '</span>';
        return $content;
    }
}