<?php
/**
 * Opt-in sibling to seed.php: replaces the local preview's placeholder
 * "Sample …" team members with the real roster pulled live from
 * ndingi-foundation's public Sanity dataset (same project/dataset ndingi's
 * Next.js site reads — see that repo's src/lib/sanity/client.ts). Not run
 * automatically by setup.sh, since seed.php is deliberately offline-friendly;
 * run this by hand when a demo/screenshot needs to show real content instead
 * of placeholders:
 *
 *   php .wp-runtime/wp-cli.phar --path=.wp-runtime eval-file local-preview/import-real-people.php
 *
 * Safe to re-run: deletes every existing team_member post first, so it never
 * duplicates.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$SANITY_PROJECT_ID = 'g0i8edrt';
$SANITY_DATASET    = 'production';

function ndingi_import_sanity_image_url( $ref, $project_id, $dataset ) {
	// "image-<hash>-<w>x<h>-<ext>" -> https://cdn.sanity.io/images/<project>/<dataset>/<hash>-<w>x<h>.<ext>
	if ( ! preg_match( '/^image-([a-f0-9]+)-(\d+x\d+)-(\w+)$/', $ref, $m ) ) {
		return null;
	}
	return "https://cdn.sanity.io/images/{$project_id}/{$dataset}/{$m[1]}-{$m[2]}.{$m[3]}";
}

function ndingi_import_portable_text_to_html( $blocks ) {
	if ( empty( $blocks ) || ! is_array( $blocks ) ) {
		return '';
	}
	$html = '';
	foreach ( $blocks as $block ) {
		if ( ( $block['_type'] ?? '' ) !== 'block' ) {
			continue;
		}
		$text = '';
		foreach ( $block['children'] ?? array() as $child ) {
			$text .= $child['text'] ?? '';
		}
		if ( '' !== trim( $text ) ) {
			$html .= '<p>' . esc_html( $text ) . "</p>\n";
		}
	}
	return $html;
}

echo "Fetching people from Sanity ({$SANITY_PROJECT_ID}/{$SANITY_DATASET})…\n";
$query = '*[_type == "person"] | order(order asc) {_id,name,title,rosters,order,"photoRef": photo.asset._ref, bio}';
$url   = "https://{$SANITY_PROJECT_ID}.apicdn.sanity.io/v2025-01-01/data/query/{$SANITY_DATASET}?" . http_build_query( array( 'query' => $query ) );

$response = wp_remote_get( $url, array( 'timeout' => 30 ) );
if ( is_wp_error( $response ) ) {
	echo 'Failed to fetch from Sanity: ' . $response->get_error_message() . "\n";
	exit( 1 );
}
$body   = json_decode( wp_remote_retrieve_body( $response ), true );
$people = $body['result'] ?? array();
if ( empty( $people ) ) {
	echo "No people returned — aborting without touching existing content.\n";
	exit( 1 );
}
echo 'Fetched ' . count( $people ) . " people.\n";

echo "Removing existing team_member posts…\n";
$existing = get_posts( array( 'post_type' => 'team_member', 'posts_per_page' => -1, 'post_status' => 'any' ) );
foreach ( $existing as $post ) {
	wp_delete_post( $post->ID, true );
}
echo 'Removed ' . count( $existing ) . " existing team members.\n";

foreach ( $people as $person ) {
	$post_id = wp_insert_post(
		array(
			'post_type'    => 'team_member',
			'post_title'   => $person['name'],
			'post_content' => ndingi_import_portable_text_to_html( $person['bio'] ?? null ),
			'post_status'  => 'publish',
			'menu_order'   => (int) ( $person['order'] ?? 0 ),
		)
	);

	if ( ! empty( $person['title'] ) ) {
		update_post_meta( $post_id, 'ndingi_role', $person['title'] );
	}

	if ( ! empty( $person['rosters'] ) ) {
		wp_set_object_terms( $post_id, $person['rosters'], 'ndingi_roster' );
	}

	if ( ! empty( $person['photoRef'] ) ) {
		$image_url = ndingi_import_sanity_image_url( $person['photoRef'], $SANITY_PROJECT_ID, $SANITY_DATASET );
		if ( $image_url ) {
			$attachment_id = media_sideload_image( $image_url, $post_id, $person['name'], 'id' );
			if ( ! is_wp_error( $attachment_id ) ) {
				set_post_thumbnail( $post_id, $attachment_id );
			} else {
				echo "  Warning: photo download failed for {$person['name']}: " . $attachment_id->get_error_message() . "\n";
			}
		}
	}

	echo "  Imported: {$person['name']} (" . implode( ', ', $person['rosters'] ?? array() ) . ")\n";
}

echo "Done.\n";
