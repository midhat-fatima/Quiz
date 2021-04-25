<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Post_List_Content
 */
class TCB_Post_List_Content {

	/* the default read more is blank */
	public static $default_read_more = '';

	/**
	 * Some patterns to remove in the case of excerpt and words.
	 *
	 * @var array
	 */
	static public $patterns_to_remove = array(
		'/___TVE_SHORTCODE_RAW__(.+?)__TVE_SHORTCODE_RAW___/s',
		'#__CONFIG_group_edit__(.+?)__CONFIG_group_edit__#',
		'#__CONFIG_local_colors__(.+?)__CONFIG_local_colors__#',
		'#<p class="tve_more_tag(.+?)<\/p>#',
		/* some custom menus are saved without the __CONFIG_widget_menu key (in symbols, this is a problem) - we need to get rid of all json-encoded string from the content */
		'#{"menu_id"(.+)(true|false|null|"|\'|\d|]|})}#',
		/* Custom menu - REST API version - encoded json */
		'@{&#8220;menu_id&#8221;(.+)(true|false|null|&#8221;|\d|]|})}@',
		/* Remove more tag */
		'#<p class="tve_more_tag(.+?)<\/p>#',
	);

	/**
	 * Return the post content after calculating excerpt / word counts.
	 *
	 * @param $attr
	 *
	 * @return mixed
	 */
	public static function get_content( $attr ) {
		global $tcb_read_more_link;
		global $post;
		setup_postdata( $post );

		/* if the post content is not inside the post list, it means this is called from TTB -> return the regular full content without changing anything */
		if ( empty( $GLOBALS[ TCB_DO_NOT_RENDER_POST_LIST ] ) && is_editor_page() ) {
			return TCB_Post_List_Shortcodes::before_wrap( array(
				'content' => TCB_Post_List_Shortcodes::shortcode_function_content( 'the_content' ),
				'tag'     => 'section',
				'class'   => 'tcb-post-content' . ' ' . TCB_SHORTCODE_CLASS,
			), $attr );
		}

		$attr = array_merge( static::default_attr(), $attr );

		if ( is_editor_page_raw() ) {
			/* we don't add read more inside the editor so we can better handle the post_content and modify the read more text */
			$tcb_read_more_link = ' ';
		} else {
			$tcb_read_more_link = TCB_Utils::wrap_content( $attr['read_more'], 'a', '', 'more-link', array( 'href' => get_the_permalink() . '#more-' . get_the_ID() ) );
		}

		switch ( $attr['size'] ) {
			case 'excerpt':
				$content = static::get_excerpt( $tcb_read_more_link );
				break;
			case 'content':
				$content = TCB_Post_List_Shortcodes::shortcode_function_content( 'the_content', array( $attr['read_more'] ) );

				$content_text = trim( str_replace( $attr['read_more'], '', strip_tags( $content ) ) );
				if ( empty( $content_text ) && TCB_Editor()->is_inner_frame() ) {
					$content = TCB_Utils::wrap_content( __( 'No Post Content', 'thrive-cb' ), 'p' );
				}

				$queried_object = get_queried_object();
				$post_id        = get_the_ID();

				/* get the styles used by this post, but only if it isn't on the post with the post list that we're rendering */
				if ( ( ! empty( $queried_object ) && property_exists( $queried_object, 'ID' ) && $post_id !== $queried_object->ID ) || ! is_singular() ) {
					$content = static::get_post_styles( $post_id ) . $content;
				}
				break;
			case 'words':
			default:
				$content = static::get_words( $attr['words'], $tcb_read_more_link );
		}

		return TCB_Post_List_Shortcodes::before_wrap( array(
			'content' => $content,
			'tag'     => 'section',
			'class'   => 'tcb-post-content' . ' ' . TCB_SHORTCODE_CLASS,
		), $attr );
	}

	/**
	 * Get the styles used by the current post.
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	public static function get_post_styles( $post_id ) {
		$custom_css = tve_get_post_meta( $post_id, 'tve_custom_css', true );

		return TCB_Utils::wrap_content( $custom_css, 'style', '', 'tve_custom_style', array( 'type' => 'text/css' ) );
	}

	/**
	 * Get the excerpt.
	 *
	 * @param $tcb_read_more_link
	 *
	 * @return mixed|string|string[]|null
	 */
	public static function get_excerpt( $tcb_read_more_link ) {
		/* we add this filter so functions will use our read more text and we remove it later */
		add_filter( 'excerpt_more', array( __CLASS__, 'excerpt_more_filter' ) );
		/* if the post has a tcb read more element, display the content only until there. filter is called in functions.php */
		add_filter( 'tcb_force_excerpt', '__return_true' );

		$content = TCB_Post_List_Shortcodes::shortcode_function_content( 'the_excerpt' );

		$tcb_enabled = (int) get_post_meta( get_the_ID(), 'tcb_editor_enabled', true );

		/* if there's no content for this post, and it is a WP Post, and we're in the editor, take the original WP content */
		if ( empty( $content ) && is_editor_page() && ! $tcb_enabled ) {
			global $post;
			$content = $post->post_content;
		}

		$content = static::replace_tve_shortcodes( $content );

		$content = str_replace( ']]>', ']]&gt;', $content );

		$content = static::filter_content( $content );

		/* replace these manually in case preg_replace only found the start or the end _config_ */
		$content = str_replace( array( '___TVE_SHORTCODE_RAW__', '__TVE_SHORTCODE_RAW___' ), '', $content );

		/* more tag needs to be removed from the excerpt */
		$content = str_replace( 'More&#8230;', '', $content );

		$content = strip_shortcodes( $content );

		/* make sure we always display the read more link */
		if ( strpos( $content, $tcb_read_more_link ) === false ) {
			/* we insert the read more before the closing of the last paragraph tag */
			$content = preg_replace( '/<\/p>$/', " {$tcb_read_more_link}$0", trim( $content ) );
		}

		remove_filter( 'excerpt_more', array( __CLASS__, 'excerpt_more_filter' ) );
		$tcb_read_more_link = null;

		remove_filter( 'tcb_force_excerpt', '__return_true' );

		return $content;
	}

	/**
	 * Get the first x words of the content.
	 *
	 * @param $words
	 * @param $tcb_read_more_link
	 *
	 * @return mixed|string
	 */
	public static function get_words( $words, $tcb_read_more_link ) {
		add_filter( 'the_content_more_link', array( __CLASS__, 'the_content_more_link_filter' ) );

		$content = TCB_Post_List_Shortcodes::shortcode_function_content( 'the_content' );

		remove_filter( 'the_content_more_link', array( __CLASS__, 'the_content_more_link_filter' ) );

		$content = static::replace_tve_shortcodes( $content );

		$content = str_replace( ']]>', ']]&gt;', $content );

		$content = static::filter_content( $content );

		$content = wp_trim_words( $content, $words, '' ) . ' ' . $tcb_read_more_link;

		$content = TCB_Utils::wrap_content( $content, 'p' );

		return $content;
	}

	/**
	 * Remove some config text.
	 *
	 * @param $content
	 *
	 * @return string|string[]|null
	 */
	public static function filter_content( $content ) {

		foreach ( static::$patterns_to_remove as $pattern ) {
			$content = preg_replace( $pattern, '', $content );
		}

		return $content;
	}

	/**
	 * The TVE shortcodes have no brackets (they are stored with __CONFIG__ around them), so they cant be modified with the wordpress shortcode functions.
	 * (the tve_thrive_shortcodes() function cannot be used because the regex only works if the config is wrapped in something, and that doesn't happen in the editor)
	 *
	 * @param $content
	 *
	 * @return string|string[]|null
	 */
	public static function replace_tve_shortcodes( $content ) {
		global $tve_thrive_shortcodes;

		foreach ( $tve_thrive_shortcodes as $shortcode => $callback ) {
			$content = preg_replace( '/__CONFIG_' . $shortcode . '__(.+?)__CONFIG_' . $shortcode . '__/', '', $content );
		}

		return $content;
	}

	/**
	 * Add read more text to the excerpt
	 *
	 * @param $read_more
	 *
	 * @return mixed
	 */
	public static function excerpt_more_filter( $read_more ) {
		global $tcb_read_more_link;

		if ( $tcb_read_more_link !== null ) {
			$read_more = ' ' . $tcb_read_more_link;
		}

		return $read_more;
	}

	/**
	 * Remove the WP more tag. (it is added in post-template.php, line 349)
	 *
	 *
	 * @return string
	 */
	public static function the_content_more_link_filter() {
		return '';
	}

	/**
	 * Default attributes for post list available with filter
	 *
	 * @return mixed|void
	 */
	private static function default_attr() {
		return apply_filters( 'tcb_post_list_content_default_attr', array(
			'size'      => 'words',
			'read_more' => static::$default_read_more,
			'words'     => 12,
		) );
	}

}
