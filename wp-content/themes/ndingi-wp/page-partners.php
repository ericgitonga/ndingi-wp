<?php
/**
 * Template Name: Partners
 * Port of ndingi's src/app/partners/page.tsx. The professional advisors
 * list was a hardcoded TS array there (not CMS content) — kept the same
 * way here rather than second-guessing that call; it's reference
 * information (bank branches, auditors) that changes by developer
 * request, not day-to-day editorial content.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$professional_advisors = array(
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
?>
<div class="wrap">
	<h1 class="page-title"><?php the_title(); ?></h1>

	<section style="margin-top:2rem;">
		<h2 class="subsection-title" style="margin-top:0;">Professional Advisors</h2>
		<p style="margin-top:0.5rem;">The professional service providers who support the Foundation&rsquo;s governance and operations.</p>

		<div class="advisor-grid">
			<?php foreach ( $professional_advisors as $group ) : ?>
				<div class="advisor-card">
					<h3><?php echo esc_html( $group['category'] ); ?></h3>
					<ul>
						<?php foreach ( $group['advisors'] as $advisor ) : ?>
							<li>
								<p class="advisor-name"><?php echo esc_html( $advisor['name'] ); ?></p>
								<?php foreach ( $advisor['detail'] as $line ) : ?>
									<p class="advisor-detail"><?php echo esc_html( $line ); ?></p>
								<?php endforeach; ?>
								<?php if ( ! empty( $advisor['link'] ) ) : ?>
									<a href="<?php echo esc_url( $advisor['link']['href'] ); ?>"><?php echo esc_html( $advisor['link']['label'] ); ?></a>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section style="margin-top:2.5rem;">
		<h2 class="subsection-title" style="margin-top:0;">Programme &amp; Donor Partners</h2>
		<p style="margin-top:0.5rem;">Additional partner organisations the Foundation works with will be listed here once their authorisation to be named and display their logo has been received.</p>
	</section>
</div>
<?php get_footer(); ?>
