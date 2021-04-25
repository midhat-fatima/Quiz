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
 * Class TCB_Utils
 */
class TCB_Utils {
	/**
	 * Wrap content in tag with id and/or class
	 *
	 * @param              $content
	 * @param string       $tag
	 * @param string       $id
	 * @param string|array $class
	 * @param array        $attr
	 *
	 * @return string
	 */
	public static function wrap_content( $content, $tag = '', $id = '', $class = '', $attr = array() ) {
		$class = is_array( $class ) ? implode( ' ', $class ) : $class;

		if ( empty( $tag ) && ! ( empty( $id ) && empty( $class ) ) ) {
			$tag = 'div';
		}

		$attributes = '';
		foreach ( $attr as $key => $value ) {
			/* if the value is null, only add the key ( this is used for attributes that have no value, such as 'disabled', 'checked', etc ) */
			if ( is_null( $value ) ) {
				$attributes .= ' ' . $key;
			} else {
				$attributes .= ' ' . $key . '="' . $value . '"';
			}
		}

		if ( ! empty( $tag ) ) {
			$content = '<' . $tag . ( empty( $id ) ? '' : ' id="' . $id . '"' ) . ( empty( $class ) ? '' : ' class="' . $class . '"' ) . $attributes . '>' . $content . '</' . $tag . '>';
		}

		return $content;
	}

	/**
	 * Get the image source for the id.
	 *
	 * @param        $image_id
	 * @param string $size
	 *
	 * @return mixed
	 */
	public static function get_image_src( $image_id, $size = 'full' ) {
		$image_info = wp_get_attachment_image_src( $image_id, $size );

		return empty( $image_info ) || empty( $image_info[0] ) ? '' : $image_info[0];
	}
}
