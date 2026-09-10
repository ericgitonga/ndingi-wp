<?php
/**
 * Template Name: Team Roster
 * Assigned to Management Team / Trustees / Board of Directors — port of
 * those three near-identical ndingi route files, collapsed into one shared
 * template that derives which roster to query from the page's slug.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$slug_to_roster = array(
	'management-team'    => 'management',
	'trustees'           => 'trustees',
	'board-of-directors' => 'board',
);
$roster  = $slug_to_roster[ get_post_field( 'post_name' ) ] ?? 'management';
$members = ndingi_get_roster( $roster );
?>
<div class="wrap">
	<h1 class="page-title"><?php the_title(); ?></h1>
	<?php get_template_part( 'template-parts/team-grid', null, array( 'members' => $members, 'grid_id' => 'roster-' . $roster ) ); ?>
</div>
<?php get_footer(); ?>
