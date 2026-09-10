<?php
/**
 * Homepage — port of ndingi's src/app/page.tsx. Copy comes from the front
 * page's own post meta (see the plugin's front-page-meta.php) with the
 * original site's text as the fallback default, so the page renders
 * correctly even before an editor has filled the fields in.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$front_id = get_the_ID();

$hero_heading  = get_post_meta( $front_id, 'ndingi_hero_heading', true ) ?: 'Empowering Communities Through Lasting Impact';
$hero_subtext  = get_post_meta( $front_id, 'ndingi_hero_subtext', true ) ?: "The Archbishop Ndingi Mwana\u{2019}a Nzeki Foundation transforms lives across Kenya through education, sustainable community development, and climate-resilient water and ecosystem management.";
$intro_heading = get_post_meta( $front_id, 'ndingi_intro_heading', true ) ?: "Upholding Archbishop Ndingi\u{2019}s Vision";
$intro_body    = get_post_meta( $front_id, 'ndingi_intro_body', true );
$intro_paragraphs = $intro_body ? array_filter( array_map( 'trim', explode( "\n", $intro_body ) ) ) : array(
	"The Archbishop Ndingi Mwana\u{2019}a Nzeki Foundation is committed to fostering education, community growth, and charitable endeavours inspired by Archbishop Ndingi\u{2019}s enduring legacy.",
	"Our mission is to create lasting positive change guided by his principles and compassion, delivered through three strategic programme areas.",
);
$cta_heading = get_post_meta( $front_id, 'ndingi_cta_heading', true ) ?: "Honouring Archbishop Ndingi\u{2019}s Legacy";
$cta_body    = get_post_meta( $front_id, 'ndingi_cta_body', true ) ?: "Join us in advancing education and community growth inspired by Archbishop Ndingi\u{2019}s work \u{2014} discover how you can make a difference today.";

$programme_slugs = array( 'education', 'sustainable-livelihoods', 'water-ecosystem-management' );
?>

<section class="hero">
	<div class="hero__inner">
		<h1><?php echo esc_html( $hero_heading ); ?></h1>
		<p><?php echo esc_html( $hero_subtext ); ?></p>
		<div class="hero__actions">
			<a class="btn btn-inverse" href="<?php echo esc_url( home_url( '/who-we-are/' ) ); ?>">Who We Are</a>
			<a class="btn btn-outline-inverse" href="<?php echo esc_url( home_url( '/our-work/' ) ); ?>">Our Work</a>
		</div>
	</div>
</section>

<section class="wrap">
	<div class="prose" style="margin-top:0;">
		<h2 class="section-title"><?php echo esc_html( $intro_heading ); ?></h2>
		<?php foreach ( $intro_paragraphs as $paragraph ) : ?>
			<p><?php echo esc_html( $paragraph ); ?></p>
		<?php endforeach; ?>
	</div>
</section>

<section class="section section--tint">
	<div class="wrap-5xl">
		<h2 class="page-title" style="text-align:center;">Our Work</h2>
		<p class="section__intro">Three programme areas guided by Archbishop Ndingi&rsquo;s enduring legacy.</p>

		<div class="home-card-grid">
			<?php foreach ( $programme_slugs as $slug ) :
				$page = get_page_by_path( $slug );
				if ( ! $page ) {
					continue;
				}
				$icon  = get_post_meta( $page->ID, 'ndingi_card_icon', true ) ?: $slug;
				$blurb = get_post_meta( $page->ID, 'ndingi_card_blurb', true );
				?>
				<a class="home-card" href="<?php echo esc_url( get_permalink( $page ) ); ?>">
					<?php ndingi_icon( $icon ); ?>
					<h3 class="card-tile__title"><?php echo esc_html( $page->post_title ); ?></h3>
					<p class="card-tile__blurb"><?php echo esc_html( $blurb ); ?></p>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section--secondary">
	<div class="section__cta">
		<h2 class="page-title" style="color:inherit;"><?php echo esc_html( $cta_heading ); ?></h2>
		<p><?php echo esc_html( $cta_body ); ?></p>
		<a class="btn btn-inverse" href="<?php echo esc_url( home_url( '/who-we-are/' ) ); ?>">Discover More</a>
	</div>
</section>

<section class="section section--center">
	<div class="section__cta">
		<h2 class="page-title">Get In Touch</h2>
		<p>Have a question, a partnership idea, or want to support our work? We&rsquo;d love to hear from you.</p>
		<button type="button" class="btn btn-primary" data-open-modal="ndingi-contact-modal">Contact Us</button>
	</div>
</section>

<?php get_footer(); ?>
