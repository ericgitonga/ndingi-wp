<?php
/**
 * "Partners & Advisors Settings" meta box for the Partners page — allows editing the
 * Professional Advisors intro, Advisors data, and Programme & Donor Partners content.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ndingi_default_professional_advisors() {
	return array(
		array(
			'category' => 'Bankers',
			'advisors' => array(
				array( 'name' => 'NIC Bank', 'detail' => array( 'The Mall, Westlands Branch, Nairobi' ) ),
				array( 'name' => 'Caritas Microfinance Bank', 'detail' => array( 'Cardinal Otunga Plaza Branch, Nairobi' ) ),
			),
		),
		array(
			'category' => 'Auditors',
			'advisors' => array(
				array(
					'name'   => 'Kigundu & Company',
					'detail' => array( 'Certified Public Accountants (Kenya)', 'P.O. Box 2309-00606, Sarit Centre, Nairobi' ),
				),
			),
		),
		array(
			'category' => 'Tax Consultants',
			'advisors' => array(
				array(
					'name'   => 'Taxwise Africa Consulting LLP',
					'detail' => array( '9th Floor Barclays Plaza, Loita Street', 'P.O. Box 9539-00100, Nairobi' ),
					'link'   => array( 'href' => 'https://www.taxwise-consulting.com', 'label' => 'www.taxwise-consulting.com' ),
				),
			),
		),
		array(
			'category' => 'Lawyers',
			'advisors' => array(
				array(
					'name'   => 'Nyiha, Mukoma & Company Advocates',
					'detail' => array( 'Old Mutual Building, 3rd Floor, Kimathi Street', 'P.O. Box 28491-00200, Nairobi' ),
				),
			),
		),
	);
}

function ndingi_get_professional_advisors( $post_id = 0 ) {
	if ( ! $post_id ) {
		$partners_page = get_page_by_path( 'partners' );
		$post_id       = $partners_page ? $partners_page->ID : 0;
	}
	if ( $post_id ) {
		$raw = get_post_meta( $post_id, 'ndingi_advisors_json', true );
		if ( $raw ) {
			$decoded = json_decode( $raw, true );
			if ( is_array( $decoded ) && ! empty( $decoded ) ) {
				return $decoded;
			}
		}
	}
	return ndingi_default_professional_advisors();
}

function ndingi_get_donor_partners( $post_id = 0 ) {
	if ( ! $post_id ) {
		$partners_page = get_page_by_path( 'partners' );
		$post_id       = $partners_page ? $partners_page->ID : 0;
	}
	if ( $post_id ) {
		$raw = get_post_meta( $post_id, 'ndingi_donor_partners_json', true );
		if ( $raw ) {
			$decoded = json_decode( $raw, true );
			if ( is_array( $decoded ) && ! empty( $decoded ) ) {
				return $decoded;
			}
		}
	}
	return array();
}

function ndingi_partners_page_metabox() {
	global $post;
	if ( ! $post || 'page' !== $post->post_type ) {
		return;
	}
	$template = get_post_meta( $post->ID, '_wp_page_template', true );
	if ( 'page-partners.php' !== $template && 'partners' !== $post->post_name ) {
		return;
	}

	add_meta_box(
		'ndingi_partners_settings',
		'Partners & Advisors Settings',
		'ndingi_render_partners_metabox',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'ndingi_partners_page_metabox' );

function ndingi_render_partners_metabox( $post ) {
	wp_nonce_field( 'ndingi_save_partners_meta', 'ndingi_partners_nonce' );

	$advisors_intro = get_post_meta( $post->ID, 'ndingi_advisors_intro', true );
	$donors_content = get_post_meta( $post->ID, 'ndingi_donor_partners_content', true );
	$advisors_json  = get_post_meta( $post->ID, 'ndingi_advisors_json', true );
	if ( empty( $advisors_json ) ) {
		$advisors_json = wp_json_encode( ndingi_default_professional_advisors(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
	}
	$donors_json = get_post_meta( $post->ID, 'ndingi_donor_partners_json', true );
	?>
	<p>
		<label for="ndingi_advisors_intro"><strong>Professional Advisors Intro Text</strong></label><br />
		<input type="text" id="ndingi_advisors_intro" name="ndingi_advisors_intro" value="<?php echo esc_attr( $advisors_intro ); ?>" placeholder="The professional service providers who support the Foundation's governance and operations." style="width:100%;" />
	</p>
	<p>
		<label for="ndingi_donor_partners_content"><strong>Programme &amp; Donor Partners Content</strong></label><br />
		<textarea id="ndingi_donor_partners_content" name="ndingi_donor_partners_content" rows="4" style="width:100%;" placeholder="Introductory narrative, partner overview, or notes..."><?php echo esc_textarea( $donors_content ); ?></textarea>
	</p>
	<p>
		<label for="ndingi_donor_partners_json"><strong>Programme &amp; Donor Partners (Structured JSON, Optional)</strong></label><br />
		<textarea id="ndingi_donor_partners_json" name="ndingi_donor_partners_json" rows="6" style="width:100%;font-family:monospace;" placeholder='[{"name":"Partner Organisation","logo_url":"https://.../logo.png","link":"https://...","blurb":"Short description"}]'><?php echo esc_textarea( $donors_json ); ?></textarea>
		<span class="description">Optional JSON array with name, logo_url, link, and blurb for each partner.</span>
	</p>
	<p>
		<label for="ndingi_advisors_json"><strong>Professional Advisors (Structured JSON)</strong></label><br />
		<textarea id="ndingi_advisors_json" name="ndingi_advisors_json" rows="12" style="width:100%;font-family:monospace;"><?php echo esc_textarea( $advisors_json ); ?></textarea>
		<span class="description">Edit categories (Bankers, Auditors, Tax Consultants, Lawyers), firm names, address lines, and optional website link.</span>
	</p>
	<?php
}

function ndingi_save_partners_meta( $post_id ) {
	if ( ! isset( $_POST['ndingi_partners_nonce'] ) || ! wp_verify_nonce( $_POST['ndingi_partners_nonce'], 'ndingi_save_partners_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['ndingi_advisors_intro'] ) ) {
		update_post_meta( $post_id, 'ndingi_advisors_intro', sanitize_text_field( wp_unslash( $_POST['ndingi_advisors_intro'] ) ) );
	}
	if ( isset( $_POST['ndingi_donor_partners_content'] ) ) {
		update_post_meta( $post_id, 'ndingi_donor_partners_content', wp_kses_post( wp_unslash( $_POST['ndingi_donor_partners_content'] ) ) );
	}
	if ( isset( $_POST['ndingi_donor_partners_json'] ) ) {
		$raw  = wp_unslash( $_POST['ndingi_donor_partners_json'] );
		$test = json_decode( $raw, true );
		if ( is_array( $test ) || empty( trim( $raw ) ) ) {
			update_post_meta( $post_id, 'ndingi_donor_partners_json', $raw );
		}
	}
	if ( isset( $_POST['ndingi_advisors_json'] ) ) {
		$raw  = wp_unslash( $_POST['ndingi_advisors_json'] );
		$test = json_decode( $raw, true );
		if ( is_array( $test ) ) {
			update_post_meta( $post_id, 'ndingi_advisors_json', $raw );
		}
	}
}
add_action( 'save_post_page', 'ndingi_save_partners_meta' );
