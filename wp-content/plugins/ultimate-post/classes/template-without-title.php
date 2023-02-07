<?php
defined('ABSPATH') || exit;

if( wp_is_block_theme() ) {
    block_template_part('header');
	wp_head();
} else {
	get_header();
}

$width = ultimate_post()->get_setting('container_width');
$width = $width ? $width : '1140';
?>
<div class="ultp-template-container" style="margin:0 auto;max-width:<?php echo esc_attr($width); ?>px; padding: 0 15px; width: -webkit-fill-available; width: -moz-available;">
	<?php
		while ( have_posts() ) : the_post();
			the_content();

			if (comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
	?>
</div>

<?php
if( wp_is_block_theme() ) {
    wp_footer();
	block_template_part('footer');
} else {
	get_footer();
}