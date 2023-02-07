<?php
defined('ABSPATH') || exit;

$wraper_after .= '<div class="ultp-loadmore">';
    $wraper_after .= '<span class="ultp-loadmore-action" tabindex="0" role="button" data-pages="'.$pageNum.'" data-pagenum="1" data-blockid="'.$attr['blockId'].'" data-blockname="ultimate-post_'.$block_name.'" data-postid="'.$page_post_id.'" '.ultimate_post()->get_builder_attr($attr['queryType']).'>'.( isset($attr['loadMoreText']) ? $attr['loadMoreText'] : 'Load More' ).' <span class="ultp-spin">'.ultimate_post()->svg_icon('refresh').'</span></span>';
$wraper_after .= '</div>';