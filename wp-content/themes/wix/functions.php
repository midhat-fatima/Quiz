<?php
/**
 * wix functions
 */
if ( ! function_exists( 'wix_setup' ) ) :
function wix_setup() {
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 900;
	}
	/*
	 * Make wix theme available for translation.
	 *
	 */
	load_theme_textdomain( 'wix', get_template_directory_uri() . '/languages' );
	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'css/editor-style.css', wix_font_url() ) );	
	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 672, 372, true );
	add_image_size( 'wix-full-width', 1038, 576, true );
	add_theme_support( "title-tag" );
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list') );
	// This theme allows users to set a custom background.
	add_theme_support( 'custom-background', apply_filters( 'wix_custom_background_args', array(
		'default-color' => 'f5f5f5',
	) ) );
	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary'   => __( 'Main menu', 'wix' ),
	) );
	add_theme_support( 'featured-content', array(
		'featured_content_filter' => 'wix_get_featured_posts',
		'max_posts' => 6,
	) );
	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
}
endif; // wix_setup
add_action( 'after_setup_theme', 'wix_setup' );
// thumbnail list 
function wix_thumbnail_image($content) {
if( has_post_thumbnail() )
         return the_post_thumbnail( 'thumbnail' );
}
function wix_font_url() {
	$wix_font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Istok Web, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Istok Web font: on or off', 'wix' ) ) {
		$wix_font_url = add_query_arg( 'family', urlencode( 'Lato:300,400,700,900,300italic,400italic,700italic' ), "//fonts.googleapis.com/css" );
	}
	return $wix_font_url;
}
//enqueue scripts and styles  
function wix_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri());
	wp_enqueue_style( 'style-bootstrap', get_stylesheet_directory_uri().'/css/bootstrap.min.css' );
	wp_enqueue_style( 'style-custom', get_stylesheet_directory_uri().'/css/custom.css' );
	wp_enqueue_style( 'style-media', get_stylesheet_directory_uri().'/css/media.css' );
	wp_enqueue_style( 'style-theme-setup', get_stylesheet_directory_uri().'/css/theme-setup.css' );
	wp_enqueue_style( 'basecss', get_stylesheet_directory_uri().'/css/base.css' );	
	wp_enqueue_script( 'script-bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array('jquery'), '1.0' );	
	wp_enqueue_script('jquery-masonry');
	wp_enqueue_script( 'base', get_template_directory_uri() . '/js/base.js', array('jquery'), '1.0' );	
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'wix_scripts');
//enqueue scripts and styles  
function wix_admin_scripts() {
	wp_enqueue_script( 'script-default', get_template_directory_uri() . '/js/default.js', array('jquery'), '1.0' );	
}
add_action( 'admin_enqueue_scripts', 'wix_admin_scripts');
// Implement wix Header features.
require_once ('functions/custom-header.php');

/*************** Theme option ***********************/
require_once('theme-option/fasterthemes.php');

/*************** post author and tag widget**********/
require_once('functions/wix-post-tag-widget.php');

/*************** Author Info widget ****************/
require_once('functions/wix-author-information.php');

if ( ! function_exists( 'wix_entry_meta' ) ) :
/**
 * Set up post entry meta.
 *
 * Meta information for current post: categories, tags, permalink, author, and date.
 **/
function wix_entry_meta() {
 
 	$wix_category_list = sprintf( '<li class="category">'.get_the_category_list( __( ', ', 'wix' ) ).'  </li>');

	$wix_tag_list = get_the_tag_list( '', __( ', ', 'wix' ) );

	$wix_date = sprintf( '<li class="date"><a href="%1$s" title="%2$s"><time datetime="%3$s">%4$s</time></a>/ </li>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date('d M') )
	);
	$wix_author = sprintf( '<li class="admin"><a href="%1$s" title="%2$s" >%3$s</a> / </li>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'wix' ), get_the_author() ) ),
		get_the_author()
	);
	if(comments_open()) { 
		$wix_comments = '<li>'.comments_number( '', '/ 1 Comment.', '/ % Comments.' ).'</li>'; 
	} else {
		$wix_comments = '';
	}
	printf('%1$s %2$s %3$s %4$s',
		$wix_date,
		$wix_author,
		$wix_category_list,
		$wix_comments		
		);
	}
endif;

/**
 * Register our sidebars and widgetized areas.
 *
 */
function wix_widgets_init() {
	register_sidebar( array(
		'name' => 'Main Sidebar',
		'id' => 'sidebar-1',
		'class' => 'nav-list',
		'before_widget' => '<aside class="wix-widget">',
		'after_widget' => '</aside>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	) );
}
add_action( 'widgets_init', 'wix_widgets_init' );

//fetch title
function wix_title() {
	  if (is_category() || is_single())
	  {
	   if(is_category())
		  the_category();
	   if (is_single())
		 the_title();
	   }
	   elseif (is_page()) 
		  the_title();
	   elseif (is_search())
		   echo the_search_query();
    }

/**
 * Filter the page title.
 **/
function wix_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$wix_site_description = get_bloginfo( 'description', 'display' );
	if ( $wix_site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $wix_site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'wix' ), max( $paged, $page ) );
	return $title;
}
add_filter( 'wp_title', 'wix_wp_title', 10, 2 );

function wix_excerpt($wix_excerpt,$wix_charlength) {
	$wix_charlength++;
	if ( mb_strlen( $wix_excerpt ) > $wix_charlength ) {
		$wix_subex = mb_substr( $wix_excerpt, 0, $wix_charlength - 5 );
		$wix_exwords = explode( ' ', $wix_subex );
		$wix_excut = - ( mb_strlen( $wix_exwords[ count( $wix_exwords ) - 1 ] ) );
		if ( $wix_excut < 0 ) {
			echo mb_substr( $wix_subex, 0, $wix_excut );
		} else {
			echo $wix_subex;
		}
		echo '...';
	} else {
		echo $wix_excerpt;
	}
}                
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own wix_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 */
 if ( ! function_exists( 'wix_comment' ) ) :
function wix_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
			?>
			<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
				<p>
				<?php _e( 'Pingback:', 'wix' ); ?>
				<?php comment_author_link(); ?>
				<?php edit_comment_link( __( '(Edit)', 'wix' ), '<span class="edit-link">', '</span>' ); ?>
				</p>
			</li>
			<?php
			break;
		default :
		// Proceed with normal comments.
		if($comment->comment_approved==1)
		{
			global $post;
		?>
			<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); 
			?>">
			<article id="comment-<?php comment_ID(); ?>">
			<figure class="comment-author"> <a href="#"><?php echo get_avatar( get_the_author_meta('ID'), '41'); ?></a> </figure>
			<div class="comment-content">
            <?php
				printf( '<div class="comment-author-name">%1$s',get_comment_author_link(),( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author ', 'wix' ) . '</span>' : '');
				$wix_date = "F j, Y g:i a";
				echo '</div><div class="comment-metadata">'.get_comment_date($wix_date).'</div>';?>
                <div class="comment-content-main">
                <?php comment_text(); ?>
                </div>
                <div class="reply">
                <?php
					echo comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'wix' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
					?>
               	</div>
		  <!-- .comment-content --> 
			</div>
			</article>
            </li>
	  <!-- #comment-## -->
	  <?php
			}
		break;
	endswitch; // end comment_type check
}
endif;
  
/*
 * Replace Excerpt [...] with Read More
 */
 
function wix_read_more( ) {
return ' ..<br /><a href="'. get_permalink() . '">Read More...</a>';
 }
add_filter( 'excerpt_more', 'wix_read_more' ); ?>