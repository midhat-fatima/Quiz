<?php
/**
 * Implemention of Custom Header 
 * Set up the WordPress core custom header settings.
 *
 */
function wix_custom_header_setup() {

	add_theme_support( 'custom-header', apply_filters( 'wix_custom_header_setup', array(
		'default-text-color'     => 'fff',
		'width'                  => 1260,
		'height'                 => 240,
		'flex-height'            => true,
		'wp-head-callback'       => 'wix_header_style',
		'admin-head-callback'    => 'wix_admin_header_style',
		'admin-preview-callback' => 'wix_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'wix_custom_header_setup' );

if ( ! function_exists( 'wix_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see wix_custom_header_setup().
 *
 */
function wix_header_style() {
	$wix_text_color = get_header_textcolor();

	// If no custom color for text is set, let's bail.
	if ( display_header_text() && $wix_text_color === get_theme_support( 'custom-header', 'default-text-color' ) )
		return;

	// If we get this far, we have custom styles. ?>
	<style type="text/css" id="wix-header-css">
	<?php // Has the text been hidden?
		if ( ! display_header_text() ) : ?>
		.site-title,
		.site-description {
			clip: rect(1px 1px 1px 1px); /* IE7 */
			clip: rect(1px, 1px, 1px, 1px);
			position: absolute;
		}
	<?php // If the user has set a custom color for the text, use that.
		elseif ( $wix_text_color != get_theme_support( 'custom-header', 'default-text-color' ) ) : ?>
		.site-title a {
			color: #<?php echo esc_attr( $wix_text_color ); ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // wix_header_style
if ( ! function_exists( 'wix_admin_header_style' ) ) :
/**
 * Style the header image displayed on the Appearance > Header screen.
 *
 * @see wix_custom_header_setup()
 */
function wix_admin_header_style() { ?>
	<style type="text/css" id="wix-admin-header-css">
	.appearance_page_custom-header #headimg {
		background-color: #000;
		border: none;
		max-width: 1260px;
		min-height: 48px;
	}
	#headimg h1 {
		font-family: Lato, sans-serif;
		font-size: 18px;
		line-height: 48px;
		margin: 0 0 0 30px;
	}
	#headimg h1 a {
		color: #fff;
		text-decoration: none;
	}
	#headimg img {
		vertical-align: middle;
	}
	</style>
<?php
}
endif; // wix_admin_header_style
if ( ! function_exists( 'wix_admin_header_image' ) ) :
/**
 * Create the custom header image markup displayed on the Appearance > Header screen.
 *
 * @see wix_custom_header_setup()
 */
function wix_admin_header_image() { ?>
	<div id="headimg">
		<?php if ( get_header_image() ) : ?>
		<img src="<?php header_image(); ?>" alt="">
		<?php endif; ?>
		<h1 class="displaying-header-text"><a id="name"<?php echo sprintf( ' style="color:#%s;"', get_header_textcolor() ); ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
	</div>
<?php
}
endif; // wix_admin_header_image ?>