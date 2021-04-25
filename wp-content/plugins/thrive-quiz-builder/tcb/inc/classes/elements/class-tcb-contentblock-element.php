<?php
/**
 * Created by PhpStorm.
 * User: Ovidiu
 * Date: 2/7/2019
 * Time: 9:40 AM
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Contentblock_Element
 *
 * Element Class
 */
class TCB_Contentblock_Element extends TCB_Cloud_Template_Element_Abstract {
	/**
	 * TCB_Contentblock_Element constructor.
	 *
	 * @param string $tag
	 */
	public function __construct( $tag = '' ) {
		parent::__construct( $tag );

		add_filter( 'tcb_alter_cloud_template_meta', array( $this, 'alter_tpl_meta' ), 10, 2 );

		add_filter( 'tcb_filter_cloud_template_data', array( $this, 'filter_tpl_data' ), 10, 2 );
	}

	/**
	 * @return bool
	 */
	public function promoted() {
		return true;
	}

	/**
	 * Modifies the template meta for the content block element
	 *
	 * Works both in storing the meta values inside DB or outputing the values to the user
	 *
	 * Used in inc/classes/content-templates/class-tcb-content-templates-api.php
	 *
	 * @param array $return
	 * @param array $template_data
	 *
	 * @return array
	 */
	public function alter_tpl_meta( $return = array(), $template_data = array() ) {

		if ( $template_data['type'] === $this->_tag ) {
			$return['pack'] = $template_data['pack'];
		}

		return $return;
	}

	/**
	 * Filters the data that comes from the user database for content block element
	 *
	 * Returns only the templates and ignores the packs and categories
	 *
	 * @param array  $return
	 * @param string $tag
	 *
	 * @return array
	 */
	public function filter_tpl_data( $return = array(), $tag = '' ) {
		if ( $tag === $this->_tag ) {
			$return = $return['tpls'];
		}

		return $return;
	}

	/**
	 * Fetches a list of cloud templates for an element
	 *
	 * @param array $args allows controlling aspects of the method:
	 *                    $nocache - do not use caching (transients)
	 *
	 * @return array|WP_Error
	 */
	public function get_cloud_templates( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'nocache' => false,
		) );

		$pack_id = isset( $_GET['pack'] ) ? $_GET['pack'] : '';
		$return  = array();

		$do_not_use_cache = ( defined( 'TCB_TEMPLATE_DEBUG' ) && TCB_TEMPLATE_DEBUG ) || $args['nocache'];

		$transient_tpls       = 'tcb_cloud_templates_' . $this->tag() . '_tpls_from_pack_' . ( ! empty( $pack_id ) ? $pack_id : '' );
		$transient_packs      = 'tcb_cloud_templates_' . $this->tag() . '_packs';
		$transient_categories = 'tcb_cloud_templates_' . $this->tag() . '_categories';

		$return['tpls']       = get_transient( $transient_tpls );
		$return['packs']      = get_transient( $transient_packs );
		$return['categories'] = get_transient( $transient_categories );


		if ( $do_not_use_cache || empty( $return['tpls'] ) || empty( $return['packs'] ) || empty( $return['categories'] ) || empty( $pack_id ) ) {

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'content-templates/class-tcb-content-templates-api.php';

			try {
				$return = tcb_content_templates_api()->get_all( $this->tag(), array( 'pack' => $pack_id ) );

				if ( empty( $pack_id ) ) {
					$transient_tpls .= $return['from_pack'];
					$pack_id        = $return['from_pack'];
				}

				set_transient( $transient_tpls, $return['tpls'], 8 * HOUR_IN_SECONDS );
				set_transient( $transient_packs, $return['packs'], 8 * HOUR_IN_SECONDS );
				set_transient( $transient_categories, $return['categories'], 8 * HOUR_IN_SECONDS );

			} catch ( Exception $e ) {
				return new WP_Error( 'tcb_error', $e->getMessage(), 501 );
			}
		}

		/**
		 * Favorites Blocks
		 */
		$favorites = get_option( 'thrv_fav_content_blocks', array() );
		foreach ( $return['tpls'] as $index => $tpl ) {
			$return['tpls'][ $index ]['fav'] = intval( ! empty( $favorites[ $pack_id ] ) && is_array( $favorites[ $pack_id ] ) && in_array( $tpl['id'], $favorites[ $pack_id ] ) );
		}

		return $return;
	}


	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Content Block', 'thrive-cb' );
	}

	/**
	 * Get element alternate
	 *
	 * @return string
	 */
	public function alternate() {
		return 'content block';
	}

	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'content_block';
	}

	/**
	 * Element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.thrv-content-block';
	}

	/**
	 * Element category that will be displayed in the sidebar
	 *
	 * @return string
	 */
	public function category() {
		return self::get_thrive_advanced_label();
	}

	/**
	 * HTML layout of the element for when it's dragged in the canvas
	 *
	 * @return string
	 */
	protected function html() {
		return tcb_template( 'elements/' . $this->tag() . '.php', $this, true );
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		$contentblock = array(
			'contentblock' => array(
				'config' => array(
					'ModalPicker' => array(
						'config' => array(
							'label' => __( 'Template', 'thrive-cb' ),
						),
					),
				),
			),
		);

		return array_merge( $contentblock, $this->group_component() );
	}

	public function is_placeholder() {
		return false;
	}
}
