<?php
namespace ULTP\blocks;

defined('ABSPATH') || exit;

class Post_Comments{
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
                'default' => 'layout1',
            ],

            /*============================
                Post Comment Settings
            ============================*/
            //      Comments Form Heading
            'replyHeading' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'leaveRepText' => [
                'type' => 'string',
                'default' => 'Leave a Reply',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'replyHeading','condition'=>'==','value'=> true],
                        ],
                    ],
                ],
            ], 
            'HeadingColor' => [
                'type' => 'string',
                'default' => '#333',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'replyHeading','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comments-title { color:{{HeadingColor}} }'
                    ],
                ],
            ],
            'HeadingTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>24, 'unit' =>'px'],'height' => (object)['lg' => 26, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'replyHeading','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comments-title'
                    ],
                ],
            ],
            'subHeadingColor' => [
                'type' => 'string',
                'default' => '#888',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'replyHeading','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comments-subtitle,{{ULTP}} .logged-in-as, {{ULTP}} .comment-notes { color:{{subHeadingColor}} }'
                    ],
                ],
            ],
            'headingSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'5', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'replyHeading','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comments-title { margin-bottom:{{headingSpace}} !important; }'
                    ],
                ],
            ],
            

            /*============================
                Comments Form Input
            ============================*/
            "inputPlaceHolder" => [
                "type" => "string",
                "default" => "Express your thoughts, idea or write a feedback by clicking here & start an awesome comment"
            ],
            'inputPlaceValueColor' => [
                'type' => 'string',
                'default' => '#555',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-form ::placeholder { color:{{inputPlaceValueColor}} }'
                    ],
                ],
            ],
            'inputValueColor' => [
                'type' => 'string',
                'default' => '#555',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-form input,{{ULTP}} .ultp-comment-form textarea { color:{{inputValueColor}} }'
                    ],
                ],
            ],
            'inputValueBg' => [
                'type' => 'string',
                'default' => '#eeee',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-form input, {{ULTP}} .ultp-comment-form textarea {background-color:{{inputValueBg}}}'
                    ],
                ],
            ],
            'inputValueTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>15, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-form input, {{ULTP}} .ultp-comment-form textarea'
                    ],
                ],
            ],
            'inputValuePad' => [
                'type' => 'object',
                'default' => (object)['lg' =>'15', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-form input, {{ULTP}} .ultp-comment-form textarea { padding:{{inputValuePad}} }'
                    ],
                ],
            ],
            'inputBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>1, 'width' =>(object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#e2e2e2','type' => 'solid'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-form input, {{ULTP}} .ultp-comment-input textarea'
                    ],
                ],
            ],
            'inputHovBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>1, 'width' =>(object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#777','type' => 'solid'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-form input:hover,{{ULTP}} .ultp-comment-form input:focus,{{ULTP}} .ultp-comment-form textarea:hover, {{ULTP}} .ultp-comment-form textarea:focus'
                    ],
                ],
            ],
            'inputRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'0', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-form input, {{ULTP}} .ultp-comment-form textarea{ border-radius:{{inputRadius}} }'
                    ],
                ],
            ],
            'inputHovRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-form input:hover, {{ULTP}} .ultp-comment-form textarea:hover{ border-radius:{{inputHovRadius}} }'
                    ],
                ],
            ],
            'inputSpacing' => [
                'type' => 'object',
                'default' => (object)['lg' =>'10', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'inputLabel','condition'=>'==','value'=> true],
                            (object)['key'=>'layout','condition'=>'==','value'=> "layout1"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-form > div > label, {{ULTP}} .ultp-comment-form div > p, {{ULTP}} .ultp-comment-input,{{ULTP}} .ultp-comment-form > p,{{ULTP}} .ultp-comment-form > input { margin:{{inputSpacing}} 0px 0px !important;}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'inputLabel','condition'=>'==','value'=> true],
                            (object)['key'=>'layout','condition'=>'==','value'=> "layout2"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-form > div > label, {{ULTP}} .ultp-comment-form   div > p, {{ULTP}} .ultp-comment-input,{{ULTP}} .ultp-comment-form > p, {{ULTP}} .ultp-comment-form > input { margin:{{inputSpacing}} 0px 0px !important} ;'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'inputLabel','condition'=>'==','value'=> true],
                            (object)['key'=>'layout','condition'=>'==','value'=> "layout3"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-form > div > label, {{ULTP}} .ultp-comment-form div > p, {{ULTP}} .ultp-comment-input, {{ULTP}} .ultp-comment-form > p, {{ULTP}} .ultp-comment-form > input { margin:{{inputSpacing}} 0px 0px !important;}'
                    ],
                ],
            ], 

            /*============================
                Comments Form label style
            ============================*/
            'inputLabel' => [
                'type' => 'boolean',
                'default' => true,
            ],

            "cmntInputText" => [
                'type' => 'string',
                'default' => "Comment's",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'inputLabel','condition'=>'==','value'=> true],
                        ],
                    ],
                ],
            ],
            "nameInputText" => [
                'type' => 'string',
                'default' => "Name",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'inputLabel','condition'=>'==','value'=> true],
                        ],
                    ],
                ],
            ],
            "emailInputText" => [
                'type' => 'string',
                'default' => "Email",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'inputLabel','condition'=>'==','value'=> true],
                        ],
                    ],
                ],
            ],
            "webInputText" => [
                'type' => 'string',
                'default' => "Website Url",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'inputLabel','condition'=>'==','value'=> true],
                        ],
                    ],
                ],
            ],
            'inputLabelColor' => [
                'type' => 'string',
                'default' => '#5a5a5a',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'inputLabel','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-form label { color:{{inputLabelColor}} }'
                    ],
                ],
            ],
            'inputLabelTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>16, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'inputLabel','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-form label'
                    ],
                ],
            ],
            'cookiesEnable' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'cookiesText' => [
                'type' => 'string',
                'default' => "Save my name, email, and website in this browser for the next time I comment.",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'cookiesEnable','condition'=>'==','value'=> true],
                        ],
                    ],
                ],
            ],
            'cookiesColor' => [
                'type' => 'string',
                'default' => "#777",
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'cookiesEnable','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .comment-form-cookies-consent label { color:{{cookiesColor}} }'
                    ],
                ],
            ],

            /*============================
                Submit Button Style
            ============================*/
            'subBtnText' => [
                'type' => 'string',
                'default' => 'Post Comment',
            ], 
            'subBtnTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>15, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-btn, {{ULTP}} .form-submit > input#submit'
                    ],
                ],
            ],
            'submitButton' => [
                'type' => 'string',
                'default' => 'normal',
            ], 
            'subBtnColor' => [
                'type' => 'string',
                'default' => '#fff',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-btn,{{ULTP}} .form-submit > input#submit { color:{{subBtnColor}} }'
                    ],
                ],
            ],
            'subBtnBg' => [
                'type' => 'object',
                'default' => (object)['openColor' => 1, 'type' => 'color', 'color' => '#333'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-btn, {{ULTP}} .form-submit > input#submit'
                    ],
                ],
            ],
            'subBtnBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=> 1, 'width' =>(object)['top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0],'color' => '#333','type' => 'solid'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-btn, {{ULTP}} .form-submit > input#submit'
                    ],
                ],
            ],
            'subBtnRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'0', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-btn, {{ULTP}} .form-submit > input#submit { border-radius:{{subBtnRadius}} }'
                    ],
                ],
            ],
            'subBtnHovColor' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-btn:hover, {{ULTP}} .form-submit > input#submit:hover { color:{{subBtnHovColor}} }'
                    ],
                ],
            ],
            'subBtnHovBg' => [
                'type' => 'object',
                'default' => (object)['openColor' => 0, 'type' => 'color', 'color' => '#151515'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-btn:hover, {{ULTP}} .form-submit > input#submit:hover'
                    ],
                ],
            ],
            'subBtnHovBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=> 1, 'width' =>(object)['top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0],'color' => '#151515','type' => 'solid'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-btn:hover, {{ULTP}} .form-submit > input#submit:hover'
                    ],
                ],
            ],
            
            'subBtnHovRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'0', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-btn:hover, {{ULTP}} .form-submit > input#submit:hover { border-radius:{{subBtnHovRadius}} }'
                    ],
                ],
            ],
            'subBtnPad' => [
                'type' => 'object',
                'default' => (object)['lg' =>'10', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-btn, {{ULTP}} .form-submit > input#submit { padding:{{subBtnPad}} }'
                    ],
                ],
            ],
            'subBtnSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'20', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-btn, {{ULTP}} .form-submit > input#submit { margin:{{subBtnSpace}} 0px 0px}'
                    ],
                ],
            ],
            'subBtnAlign' => [
                'type' => 'string',
                'default' => 'left',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'subBtnAlign','condition'=>'==','value'=> "left"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-btn, {{ULTP}} .form-submit > input#submit,  {{ULTP}} .ultp-comment-btn, {{ULTP}} .form-submit { display: block !important;  margin-right: auto !important; }'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'subBtnAlign','condition'=>'==','value'=> "center"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-btn, {{ULTP}} .form-submit > input#submit,  {{ULTP}} .ultp-comment-btn, {{ULTP}} .form-submit { display: block !important; margin-left: auto !important; margin-right: auto !important}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'subBtnAlign','condition'=>'==','value'=> "right"],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-btn, {{ULTP}} .form-submit > input#submit, {{ULTP}} .ultp-comment-btn, {{ULTP}} .form-submit { display: block !important; margin-left:auto  !important;}'
                    ],
                ],
            ], 

            /*============================
                Comment Reply Style
            ============================*/
            // Title and total Comment Count
            'authColor' => [
                'type' => 'string',
                'default' => '#333',
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-meta div, {{ULTP}} .comment-author a.url,{{ULTP}} .comment-author .fn  {color:{{authColor}} }'
                   ],
               ],
            ],
            'authHovColor' => [
                'type' => 'string',
                'default' => '#000',
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-comment-meta div:hover ,{{ULTP}} .comment-author a.url:hover, {{ULTP}} .comment-author b:hover  {color: {{authHovColor}} }'
                   ],
               ],
            ],
            'authorTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>18, 'unit' =>'px'],'height' => (object)['lg' =>25, 'unit' =>'px']],
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-comment-meta div ,{{ULTP}} .comment-author a.url, {{ULTP}} .comment-author b'
                   ],
               ],
            ],
            'commentSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '15','bottom' => '0','left' => '30', 'right' => '0', 'unit' =>'px']],
                'style' => [
                     (object)[
                        'selector'=>'{{ULTP}} .ultp-reply-wrapper, {{ULTP}} .children { margin: {{commentSpace}} }'
                    ],
                ],
            ],
            'replyText' => [
                'type' => 'string',
                'default' => 'Comments Text',
            ],
            'commentCount' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'commentCountColor' => [
                'type' => 'string',
                'default' => '#055553',
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-comment-reply-heading {color:{{commentCountColor}} }'
                   ],
               ],
            ],
            'commentCountTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' => 30, 'unit' =>'px'],'height' => (object)['lg' =>28, 'unit' =>'px'],'weight'=>'600'],
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-comment-reply-heading'
                   ],
               ],
            ],
            'commentCountSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'30', 'unit' =>'px'],
                'style' => [
                     (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-reply-heading { margin:0px 0px {{commentCountSpace}} }'
                    ],
                ],
            ],
            'authMetaSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'7', 'unit' =>'px'],
                'style' => [
                     (object)[
                        'selector'=>'{{ULTP}} .ultp-comment-content-heading, {{ULTP}} .comment-meta { margin:0px 0px {{authMetaSpace}} }'
                    ],
                ],
            ],

            
            /*============================
                Comment Reply Button
            ============================*/
            'replyButton' => [
                'type' => 'string',
                'default' => 'normal',
            ],
            'replyBtnTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>15, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-reply-btn, {{ULTP}} .comment-reply-link, {{ULTP}} .comment-body .reply a'
                   ],
               ],
            ], 
            'replyBtnColor' => [
                'type' => 'string',
                'default' => '#333',
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-reply-btn, {{ULTP}} .comment-reply-link, {{ULTP}} .comment-body .reply a {color: {{replyBtnColor}} }'
                   ],
               ],
            ],
            'replyBtnBg' => [
                'type' => 'object',
                'default' => (object)['openColor' => 0, 'type' => 'color', 'color' => '#f5f5f5'],
                'style' => [
                     (object)[
                        'selector'=>'{{ULTP}} .ultp-reply-btn, {{ULTP}} .comment-reply-link, {{ULTP}} .comment-body .reply a'
                    ],
                ],
            ],
            'replyBtnHovColor' => [
                'type' => 'string',
                'default' => '#333',
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-reply-btn:hover, {{ULTP}} .comment-body .reply a:hover, {{ULTP}} .comment-reply-link:hover  {color: {{replyBtnHovColor}} }'
                   ],
               ],
            ],
            'replyBtnBgHov' => [
                'type' => 'object',
                'default' => (object)['openColor' => 0, 'type' => 'color', 'color' => '#f5f5f5'],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-reply-btn:hover, {{ULTP}} .comment-reply-link:hover, {{ULTP}} .comment-body .reply a:hover'
                    ],
                ],
            ],
            'replyBtnBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' =>(object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-reply-btn, {{ULTP}} .comment-reply-link, {{ULTP}} .comment-body .reply a'
                    ],
                ],
            ], 
            'replyBtnRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '','bottom' => '','left' => '', 'right' => '', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-reply-btn, {{ULTP}} .comment-reply-link, {{ULTP}}   .comment-body .reply a { border-radius:{{replyBtnRadius}}; }'
                    ],
                ],
            ],
            'replyBtnPad' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '5','bottom' => '5','left' => '5', 'right' => '5', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{ULTP}} .ultp-reply-btn, {{ULTP}} .comment-reply-link, {{ULTP}} .comment-body .reply a { padding:{{replyBtnPad}}; }'
                    ],
                ],
            ],
            'replyBtnSpace' => [
                'type' => 'object',
                'default' => (object)['lg' =>'7', 'unit' =>'px'],
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-reply-btn, {{ULTP}} .comment-reply-link, {{ULTP}} .comment-body .reply a { margin: {{replyBtnSpace}} 0px}'
                    ],
                ],
            ],


            /*============================
                Comment Author Meta
            ============================*/
            'authMeta' => [
                'type' => 'boolean',
                'default' => true,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authMeta','condition'=>'==','value'=> true],
                        ],
                    'selector'=>'{{ULTP}} .ultp-comment-meta span, {{ULTP}} .comment-metadata , {{ULTP}} .comment-meta {display:block}'
                    ],
                    (object)[
                        'depends' => [
                            (object)['key'=>'authMeta','condition'=>'==','value'=> false],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-meta span, {{ULTP}} .comment-metadata , {{ULTP}} .comment-meta {display:none}'
                    ],
               ],
            ],
            'authMetaColor' => [
                'type' => 'string',
                'default' => '#9f9f9f',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authMeta','condition'=>'==','value'=> true],
                        ],
                       'selector'=>'{{ULTP}} .ultp-comment-meta span ,{{ULTP}} .comment-metadata a ,{{ULTP}} .comment-meta a { color:{{authMetaColor}} }'
                   ],
               ],
            ],
            'authMetaHovColor' => [
                'type' => 'string',
                'default' => '#000',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authMeta','condition'=>'==','value'=> true],
                        ],
                       'selector'=>'{{ULTP}} .ultp-comment-meta span:hover , {{ULTP}} .comment-metadata a:hover ,{{ULTP}} .comment-meta a:hover { color:{{authMetaHovColor}} }'
                   ],
               ],
            ],
            'authMetaTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>12, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'authMeta','condition'=>'==','value'=> true],
                        ],
                       'selector'=>'{{ULTP}} .ultp-comment-meta span, {{ULTP}} .comment-metadata a, {{ULTP}} .comment-meta a'
                   ],
               ],
            ],


            /*============================
                Comment Author Image Style
            ============================*/
            'authImg' => [
                'type' => 'boolean',
                'default' => true,
                'style' => [
                    (object)[
                       'depends' => [
                           (object)['key'=>'authImg','condition'=>'==','value'=> true],
                       ],
                       'selector'=>'{{ULTP}} .ultp-comment-wrapper .ultp-comment-content-heading > img,{{ULTP}} .comment-author img  {display: inline }'
                   ],
                   (object)[
                    'depends' => [
                        (object)['key'=>'authImg','condition'=>'==','value'=> false],
                    ],
                    'selector'=>'{{ULTP}} .ultp-comment-content-heading > img,{{ULTP}} .comment-author img  {display: none }'
                ],
               ],
            ],
            'authImgRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'50', 'unit' =>'px'],
                'style' => [
                     (object)[
                        'depends' => [
                            (object)['key'=>'authImg','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-comment-content-heading > img,{{ULTP}} .comment-author img  {border-radius: {{authImgRadius}} }'
                    ],
                ],
            ],

            
            /*============================
                Comment Reply Message Text Style
            ============================*/
            'replyColor' => [
                'type' => 'string',
                'default' => '#6c6c6c',
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-comment-desc,{{ULTP}} .comment-content, {{ULTP}} .comment-body .reply ,{{ULTP}} .comment-body > p { color:{{replyColor}} }'
                   ],
               ],
            ],
            'replyHovColor' => [
                'type' => 'string',
                'default' => '#000',
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-comment-desc:hover, {{ULTP}} .comment-content:hover, {{ULTP}} .comment-body .reply:hover ,{{ULTP}} .comment-body > p:hover {color: {{replyHovColor}} }'
                   ],
               ],
            ],
            'replyTypo' => [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' =>15, 'unit' =>'px'],'height' => (object)['lg' =>20, 'unit' =>'px']],
                'style' => [
                    (object)[
                       'selector'=>'{{ULTP}} .ultp-comment-desc, {{ULTP}} .comment-content, {{ULTP}} .comment-body .reply, {{ULTP}} .comment-body > p'
                   ],
               ],
            ],
            
            
            /*============================
                reply separator Style
            ============================*/
            'replySeparator' => [
                'type' => 'boolean',
                'default' => true,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'replySeparator','condition'=>'==','value'=> true],
                        ],
                       'selector'=>'{{ULTP}} .ultp-builder-comment-reply > li:after { display: block }'
                   ],
                   (object)[
                    'depends' => [
                        (object)['key'=>'replySeparator','condition'=>'==','value'=> false],
                    ],
                   'selector'=>'{{ULTP}} .ultp-builder-comment-reply > li:after { display: none }'
               ],
               ],
            ],
            'replySepColor' => [
                'type' => 'string',
                'default' => '#c1c1c1',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'replySeparator','condition'=>'==','value'=> true],
                        ],
                       'selector'=>'{{ULTP}} .ultp-builder-comment-reply > li:after { background-color:{{replySepColor}} }'
                   ],
               ],
            ], 
            'replySepSpace'  => [
                'type' => 'object',
                'default' => (object)['lg' =>'15', 'unit' =>'px'],
                'style' => [
                     (object)[
                        'depends' => [
                            (object)['key'=>'replySeparator','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{ULTP}} .ultp-builder-comment-reply > li:after { margin:{{replySepSpace}}  0px }'
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
        register_block_type( 'ultimate-post/post-comments',
            array(
                'editor_script' => 'ultp-blocks-editor-script',
                'editor_style'  => 'ultp-blocks-editor-css',
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );    
    }    
    
    public function content($attr, $noAjax) {
        $block_name = 'post-comments';
        $wrapper_before = $wrapper_after = $wrapper_content = '';

        if(is_single()){
            $commenter = wp_get_current_commenter();
            $req = get_option( 'require_name_email' );
            $aria_req = ( $req ? " aria-required='true'" : '' );

            $auth_label = $attr['inputLabel'] ? '<label for="author">' . __( ''.$attr["nameInputText"].'' ) . '' .( $req ? '<span class="required">*</span>' : '' )  . '</label>' : '';
            $email_label = $attr['inputLabel'] ? '<label for="email">' . __( ''.$attr["emailInputText"].'' ) . '' . ( $req ? '<span class="required">*</span>' : '' ).'</label>'  : '';
            $url_label = $attr['inputLabel'] ? '<label for="url">' . __( ''.$attr["webInputText"].'', 'domainreference' ) . '</label>' : '';
            $comment_label = $attr['inputLabel'] ? '<label for="comment">' . __( ''.$attr["cmntInputText"].'' ) . '</label>' : '';
            $cookies_label = $attr['cookiesEnable'] ? '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"/><label for="wp-comment-cookies-consent">'.$attr["cookiesText"].'</label></p>' : '';

            $comments_args = array(
                'comment_field' => '<div class="comment-form-comment ultp-comment-input ultp-field-control">' .$comment_label.'<textarea class="hi" id="comment" name="comment" placeholder="'.$attr["inputPlaceHolder"].'" cols="45" rows="8" aria-required="true"></textarea></div>',
                'fields' => apply_filters( 'comment_form_default_fields', array(
                        'author' =>'<div class="ultp-field-control ultp-comment-name">'.$auth_label.'<input id="author" placeholder="Your Name (No Keywords)" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>',
                        'email'  => '<div class="ultp-field-control ultp-comment-email">'.$email_label.'<input id="email" placeholder="your-real-email@example.com" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ).'" size="30"' . $aria_req . ' /></div>',
                        'url'    => '<div class="ultp-field-control ultp-comment-website">'.$url_label.'<input id="url" name="url" placeholder="http://your-site-name.com" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div>',
                        'cookies'=> $cookies_label
                    )
                ),
                'class_submit'          => 'ultp-comment-btn',
                'comment_notes_after'   => '',
                'submit_button'         => '<input name="%1$s" type="submit" id="%2$s" className="%3$s ultp-comment-btn" value="'.$attr['subBtnText'].'" />',
                'class_form'            => 'ultp-comment-form',
                'title_reply'           => ($attr['replyHeading'] ? '<div class="crunchify-text ultp-comments-title">'.$attr['leaveRepText'].'</div>' : ''),
                'class_container'       => 'ultp-comment-form-container'
            );

            $arg = array(
                'walker'            => null,
                'max_depth'         => '',
                'style'             => 'ul',
                'callback'          => null,
                'end-callback'      => null,
                'type'              => 'comment',
                'page'              => '',
                'per_page'          => '',
                'avatar_size'       => 32,
                'reverse_top_level' => true,
                'reverse_children'  => '',
                'format'            => current_theme_supports( 'html5', 'comment-list' ) ? 'html5' : 'xhtml',
                'short_ping'        => false,
                'echo'              => true,
            );

            $comments = get_comments(array( 'post_id' => get_the_ID() ));

            $wrapper_before .= '<div '.($attr['advanceId']?'id="'.$attr['advanceId'].'" ':'').' class="wp-block-ultimate-post-'.$block_name.' ultp-block-'.$attr["blockId"].(isset($attr["className"])?' '.$attr["className"]:'').''.(isset($attr["align"])? ' align' .$attr["align"]:'').'">';
                $wrapper_before .= '<div class="ultp-block-wrapper  ultp-block-comments ultp-comments-'.$attr['layout'].'">';
                    if ($attr["commentCount"] && count($comments) > 0) {
                        $wrapper_content .= '<div class="ultp-comment-reply-heading">';    
                            $wrapper_content .= count($comments).' '.$attr['replyText'];
                        $wrapper_content .= '</div>';
                    }
                    $wrapper_content.= '<div class="ultp-builder-comment-reply">';
                        ob_start();
                        wp_list_comments($arg, $comments);
                        $wrapper_content .=  ob_get_clean();                
                    $wrapper_content .= '</div>';
                    $wrapper_content .= '<div class="ultp-builder-comments">';
                        ob_start();
                        comment_form( $comments_args );
                        $wrapper_content .= ob_get_clean();
                    $wrapper_content .= '</div>';
                $wrapper_after .= '</div>';
            $wrapper_after .= '</div>';
        }

        return $wrapper_before.$wrapper_content.$wrapper_after;
    }
}