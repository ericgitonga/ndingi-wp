<?php
/**
 * Template Name: Donate
 * Port of ndingi's src/app/donate/page.tsx.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<div class="wrap">
	<h1 class="page-title"><?php the_title(); ?></h1>
	<?php get_template_part( 'template-parts/donate-info' ); ?>
</div>
<?php get_footer(); ?>
