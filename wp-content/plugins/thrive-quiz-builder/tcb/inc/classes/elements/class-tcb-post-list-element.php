<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Post_List_Element
 */
class TCB_Post_List_Element extends TCB_Cloud_Template_Element_Abstract {

	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Post List', 'thrive-cb' );
	}

	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'post-list';
	}

	/**
	 * This element is not a placeholder
	 *
	 * @return bool|true
	 */
	public function is_placeholder() {
		return false;
	}

	/**
	 * WordPress element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.' . TCB_POST_LIST_CLASS;
	}

	/**
	 * Hide this in other editors. (TL, TA, TQB, TU)
	 *
	 * @return bool
	 */
	public function hide() {
		$allowed_post_types = array( 'post', 'page' );

		$hide = ! in_array( get_post_type( get_the_ID() ), $allowed_post_types );

		return apply_filters( 'tcb_hide_post_list_element', $hide );
	}

	/**
	 * Override the parent implementation of this method in order to add more classes.
	 *
	 * Returns the HTML placeholder for an element (contains a wrapper, and a button with icon + element name)
	 *
	 * @param string $title Optional. Defaults to the name of the current element
	 *
	 * @return string
	 */
	public function html_placeholder( $title = null ) {
		if ( empty( $title ) ) {
			$title = $this->name();
		}
		$post_list_args = TCB_Post_List::default_args();

		$attr = array(
			'query'         => $post_list_args['query'],
			'ct'            => $this->tag() . '-0',
			'tcb-elem-type' => $this->tag(),
			'element-name'  => esc_attr( $this->name() ),
		);

		$extra_attr = '';

		foreach ( $attr as $key => $value ) {
			$extra_attr .= 'data-' . $key . '="' . $value . '" ';
		}

		return tcb_template( 'elements/element-placeholder', array(
			'icon'       => $this->icon(),
			'class'      => 'tcb-ct-placeholder tcb-compact-element tcb-selector-no_save',
			'title'      => $title,
			'extra_attr' => $extra_attr,
		), true );
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {

		$components = array(
			'animation'        => array( 'hidden' => true ),
			'styles-templates' => array( 'hidden' => true ),
			'typography'       => array( 'hidden' => true ),
			'layout'           => array(
				'disabled_controls' => array( 'MaxWidth', 'Float', 'hr', 'Position', 'PositionFrom', 'Display' ),
			),
			'post_list'        => array(
				'order'  => 1,
				'config' => array(
					'Type'            => array(
						'config'  => array(
							'default' => 'grid',
							'name'    => __( 'Display Type', 'thrive-cb' ),
							'buttons' => array(
								array(
									'icon'    => '',
									'text'    => 'LIST',
									'value'   => 'list',
									'default' => true,
								),
								array(
									'icon'  => '',
									'text'  => 'GRID',
									'value' => 'grid',
								),
								array(
									'icon'  => '',
									'text'  => 'MASONRY',
									'value' => 'masonry',
								),
							),
						),
						'extends' => 'ButtonGroup',
					),
					'ColumnsNumber'   => array(
						'config'  => array(
							'default' => '3',
							'min'     => '1',
							'max'     => '10',
							'label'   => __( 'Columns Number', 'thrive-cb' ),
							'um'      => array( '' ),
						),
						'extends' => 'Slider',
					),
					'VerticalSpace'   => array(
						'config'  => array(
							'min'   => '0',
							'max'   => '240',
							'label' => __( 'Vertical Space', 'thrive-cb' ),
							'um'    => array( 'px' ),
						),
						'extends' => 'Slider',
					),
					'HorizontalSpace' => array(
						'config'  => array(
							'min'   => '0',
							'max'   => '240',
							'label' => __( 'Horizontal Space', 'thrive-cb' ),
							'um'    => array( 'px' ),
						),
						'extends' => 'Slider',
					),
					'ContentSize'     => array(
						'config'  => array(
							'name'    => __( 'Content', 'thrive-cb' ),
							'buttons' => array(
								array(
									'icon'  => '',
									'text'  => 'Full',
									'value' => 'content',
								),
								array(
									'icon'  => '',
									'text'  => 'Excerpt',
									'value' => 'excerpt',
								),
								array(
									'icon'    => '',
									'text'    => 'Words',
									'value'   => 'words',
									'default' => true,
								),
							),
						),
						'extends' => 'ButtonGroup',
					),
					'WordsTrim'       => array(
						'config'  => array(
							'name'      => __( 'Word Count', 'thrive-cb' ),
							'default'   => 12,
							'maxlength' => 2,
							'min'       => 1,
						),
						'extends' => 'Input',
					),
					'ReadMoreText'    => array(
						'config'  => array(
							'label'       => __( 'Read More Text', 'thrive-cb' ),
							'label_col_x' => 0,
							'default'     => '',
							'placeholder' => __( 'e.g. Continue reading', 'thrive-cb' ),
						),
						'extends' => 'LabelInput',
					),
				),
			),
		);

		return $components;
	}

	/**
	 * Element category that will be displayed in the sidebar
	 *
	 * @return string
	 */
	public function category() {
		return $this->get_thrive_advanced_label();
	}
}
