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
 * Class TCB_Post_List_Author_Image
 */
class TCB_Post_List_Author_Image {
	const PLACEHOLDER_URL = 'editor/css/images/author_image.png';

	/**
	 * Get the default author image url, or the author image url for a specific post
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	public static function get_default_url( $post_id = null ) {
		$url = tve_editor_url( static::PLACEHOLDER_URL );

		if ( ! empty( $post_id ) ) {
			$post = get_post( $post_id );

			if ( $post ) {
				$avatar_data = get_avatar_data( $post->post_author, array( 'size' => 256 ) );

				if ( ! empty( $avatar_data['url'] ) && ! is_wp_error( $avatar_data['url'] ) ) {
					$url = $avatar_data['url'];
				}
			}
		}

		return $url;
	}

	/**
	 * Returns post author avatar
	 *
	 * @return false|string
	 */
	public static function author_avatar() {
		global $post;

		return get_avatar_url( $post->post_author, array( 'size' => 256 ) );
	}
}
