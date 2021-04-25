<?php

/**
 * Custom walker for the Menu element
 *
 * Class TCB_Menu_Walker
 */
class TCB_Menu_Walker extends Walker_Nav_Menu {
	/**
	 * Menu descriptions for Mega Menus
	 *
	 * @var string
	 */
	public static $mega_description_template = '<div class="thrv_text_element tve-no-drop">%s</div>';

	/**
	 * unlinked selector: <LI> element
	 */
	const UNLINKED_LI = '.menu-item-{ID}';

	/**
	 * unlinked selector: <UL> element
	 */
	const UNLINKED_UL = '.menu-item-{ID}-ul';

	/**
	 * unlinked identifier: <a> (megamenu item)
	 */
	const UNLINKED_A = '.menu-item-{ID}-a';

	/**
	 * unlinked identifier: megamenu dropdown column
	 */
	const UNLINKED_COL = '.menu-item-{ID}.lvl-1';

	/**
	 * unlinked identifier: megamenu dropdown
	 */
	const UNLINKED_DROP = '.menu-item-{ID}-drop';

	/**
	 * CSS class to add to unlinked items
	 */
	const CLS_UNLINKED = 'tcb-excluded-from-group-item';

	/**
	 * Active state for menu items
	 */
	const CLS_ACTIVE = 'tve-state-active';

	/**
	 * flag indicating where or not this is a editor page
	 *
	 * @var boolean
	 */
	protected $is_editor_page;

	/**
	 * @var WP_Post current menu item
	 */
	protected $current_item;

	/**
	 * Stores icon data
	 *
	 * @var array
	 */
	protected $icons = array();

	protected $positional_selectors = false;

	public function __construct() {
		$icons                      = $this->get_config( 'icon', array() );
		$this->positional_selectors = tcb_custom_menu_positional_selectors();

		$template = tcb_template( 'elements/menu-item-icon.phtml', null, true, 'backbone' );
		foreach ( (array) $icons as $k => $icon_id ) {
			if ( $icon_id ) {
				$this->icons[ $k ] = str_replace( '_ID_', $icon_id, $template );
			}
		}
	}

	/**
	 * Gets HTML for an icon corresponding to a <li>
	 *
	 * @param WP_Post $item
	 * @param int     $current_level
	 *
	 * @return string
	 */
	protected function icon( $item, $current_level ) {
		$parent_field = $this->db_fields['parent'];

		/* unlinked id */
		$id = '.menu-item-' . $item->ID;
		if ( $this->positional_selectors && ! empty( $item->_tcb_pos_selector ) ) {
			/* try unlinked positional selectors */
			$id = $item->_tcb_pos_selector;
		}

		if ( isset( $this->icons[ $id ] ) ) {
			return $this->icons[ $id ];
		}

		/* check top level */
		if ( empty( $item->$parent_field ) ) {
			return isset( $this->icons['top'] ) ? $this->icons['top'] : '';
		}

		/* check for mega menu icons */
		if ( $this->get_menu_type() === 'mega' && $current_level > 0 ) {
			$key = 1 === $current_level ? 'mega_main' : 'mega_sub';

			return isset( $this->icons[ $key ] ) ? $this->icons[ $key ] : '';
		}

		/**
		 * default : submenu item
		 */
		return isset( $this->icons['sub'] ) ? $this->icons['sub'] : '';
	}

	/**
	 * Display array of elements hierarchically.
	 *
	 * Does not assume any existing order of elements.
	 *
	 * $max_depth = -1 means flatly display every element.
	 * $max_depth = 0 means display all levels.
	 * $max_depth > 0 specifies the number of display levels.
	 *
	 * @param array $elements  An array of elements.
	 * @param int   $max_depth The maximum hierarchical depth.
	 *
	 * @return string The hierarchical item output.
	 * @since 2.1.0
	 *
	 */
	public function walk( $elements, $max_depth ) {
		$args = func_get_args();

		if ( $this->positional_selectors ) {
			$elements  = &$args[0];
			$parent    = $this->db_fields['parent'];
			$top_level = 0;
			$index     = 0;

			/* append some meta-information for each element */
			foreach ( $elements as $menu_item ) {
				if ( empty( $menu_item->$parent ) ) {
					$top_level ++;
					$menu_item->_tcb_index = ++ $index;
				}
			}

			foreach ( $elements as $menu_item ) {
				/**
				 * use positional selectors - in lp-build, these are used to style menu items
				 */
				if ( empty( $menu_item->$parent ) ) {
					if ( 1 === $menu_item->_tcb_index ) {
						$menu_item->_tcb_pos_selector = ':first-child';
					} elseif ( $menu_item->_tcb_index === $top_level ) {
						$menu_item->_tcb_pos_selector = ':last-child';
					} else {
						$menu_item->_tcb_pos_selector = ':nth-child(' . $menu_item->_tcb_index . ')';
					}
				}
			}
		}

		return call_user_func_array( array( $this, 'parent::' . __FUNCTION__ ), $args );
	}

	/**
	 * Starts the list before the elements are added.
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 *
	 * @see   Walker::start_lvl()
	 *
	 * @since 3.0.0
	 *
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = $this->get_menu_type() === 'regular' ? array( 'sub-menu' ) : array();

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @param array    $classes The CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 *
		 * @since 4.8.0
		 *
		 */
		$classes    = apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth );
		$classes [] = 'menu-item-' . $this->current_item->ID . '-ul';
		if ( $this->is_out_of_group_editing( $this->current_item->ID, self::UNLINKED_UL ) ) {
			$classes [] = self::CLS_UNLINKED;
		}
		$wrap_start = '';

		if ( 0 === $depth && $this->get_menu_type() === 'mega' ) {
			$drop_classes = array(
				'tcb-mega-drop-inner',
				'thrv_wrapper',
				'menu-item-' . $this->current_item->ID . '-drop',
			);
			if ( $this->is_out_of_group_editing( $this->current_item->ID, self::UNLINKED_DROP ) ) {
				$drop_classes [] = self::CLS_UNLINKED;
			}
			$wrap_start = '<div class="tcb-mega-drop"><div class="' . implode( ' ', $drop_classes ) . '">';

			/* check if this dropdown has masonry */
			/**
			 * masonry if:
			 *    unlinked and specific masonry set on the unlinked config
			 * OR masonry specified on the default config
			 */
			if ( $this->get_config( 'layout/default' ) === 'masonry' && $this->get_config( "layout/drop-{$this->current_item->ID}" ) !== 'grid' ) {
				$classes [] = 'tcb-masonry';
			}
		}

		$class_names = ' class="' . esc_attr( join( ' ', $classes ) ) . '"';

		$output .= "{$wrap_start}{$n}{$indent}<ul$class_names>{$n}";
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		parent::end_lvl( $output, $depth, $args );
		if ( 0 === $depth && $this->get_menu_type() === 'mega' ) {
			$output .= '</div></div>';
		}
	}

	/**
	 *
	 * Checks if an element has been unlocked from group editing ( is edited separately )
	 * Spec can be: .menu-item
	 *
	 * @param string $item_id
	 * @param string $spec
	 *
	 * @return bool
	 * @see self::UNLINKED_* constants
	 *
	 */
	public function is_out_of_group_editing( $item_id, $spec ) {
		$unlinked_class = str_replace( '{ID}', $item_id, $spec );

		return $this->get_config( "unlinked/{$unlinked_class}" ) !== null;
	}

	/**
	 * @inheritDoc
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
		} else {
			$t = "\t";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes      = empty( $item->classes ) ? array() : (array) $item->classes;
		$link_attr    = array();
		$link_classes = $this->get_menu_type() === 'mega' && $depth > 0 ? array( "menu-item menu-item-{$item->ID} menu-item-{$item->ID}-a" ) : array();
		if ( 0 !== $depth && $this->get_menu_type() !== 'regular' && $this->is_editor_page() ) {
			$link_classes[] = 'thrv_wrapper';
		}
		if ( $this->is_out_of_group_editing( $item->ID, self::UNLINKED_A ) ) {
			$link_classes [] = self::CLS_UNLINKED;
		}

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 *
		 * @since 4.4.0
		 *
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filters the CSS class(es) applied to a menu item's list item element.
		 *
		 * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 */
		$classes      = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth );
		$link_classes = $this->get_menu_type() === 'mega' && $depth > 0 ? array( "menu-item menu-item-{$item->ID} menu-item-{$item->ID}-a" ) : array();

		// make sure these are always included
		$classes[] = 'menu-item-' . $item->ID;
		$classes[] = 'lvl-' . $depth;
		if ( ! empty( $GLOBALS['tve_menu_font_class'] ) ) {
			$classes[] = $GLOBALS['tve_menu_font_class'];
		}
		if ( $this->is_editor_page() && ( 0 === $depth || $this->get_menu_type() === 'regular' ) ) {
			$classes[] = 'thrv_wrapper';
		}
		if ( $this->is_out_of_group_editing( $item->ID, self::UNLINKED_LI ) || $this->is_out_of_group_editing( $item->ID, self::UNLINKED_COL ) ) {
			$classes [] = self::CLS_UNLINKED;
		}

		$top_cls = (array) $this->get_config( 'top_cls', array() );
		if ( 0 === $depth && ! empty( $top_cls ) ) {
			$unlinked_key = ! empty( $item->_tcb_pos_selector ) ? $item->_tcb_pos_selector : '.menu-item-' . $item->ID;
			if ( ! empty( $top_cls[ $unlinked_key ] ) ) {
				$classes [] = $top_cls[ $unlinked_key ];
			} elseif ( ! empty( $top_cls['main'] ) ) {
				$classes [] = $top_cls['main'];
			}
		}
		if ( ! $this->is_editor_page() && in_array( 'current-menu-item', $classes ) ) {
			$classes []      = self::CLS_ACTIVE;
			$link_classes [] = self::CLS_ACTIVE;
		}

		/* event actions */
		$events = $this->get_config( "actions/{$item->ID}" );
		if ( empty( $events ) ) {
			$events = ! empty( $item->thrive_events ) ? $item->thrive_events : '';
		}

		if ( $events ) {
			$link_classes [] = 'tve_evt_manager_listen tve_et_click';
		}

		$class_names = ' class="' . esc_attr( join( ' ', $classes ) ) . '"';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . ' data-id="' . $item->ID . '">';

		$link_attr['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$link_attr['target'] = ! empty( $item->target ) ? $item->target : '';
		$link_attr['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$link_attr['href']   = ! empty( $item->url ) ? $item->url : '';

		if ( 0 !== $depth && $this->get_menu_type() !== 'regular' && $this->is_editor_page() ) {
			$link_classes[] = 'thrv_wrapper';
		}
		if ( $this->is_out_of_group_editing( $item->ID, self::UNLINKED_A ) ) {
			$link_classes [] = self::CLS_UNLINKED;
		}

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @param array    $link_attr {
		 *                            The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 * @type string    $title     Title attribute.
		 * @type string    $target    Target attribute.
		 * @type string    $rel       The rel attribute.
		 * @type string    $href      The href attribute.
		 * }
		 *
		 * @param WP_Post  $item      The current menu item.
		 * @param stdClass $args      An object of wp_nav_menu() arguments.
		 * @param int      $depth     Depth of menu item. Used for padding.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 */
		$link_attr = apply_filters( 'nav_menu_link_attributes', $link_attr, $item, $args, $depth );

		$link_attr['class'] = isset( $link_attr['class'] ) ? $link_attr['class'] : '';
		$link_attr['class'] .= ( $link_attr['class'] ? ' ' : '' ) . implode( ' ', $link_classes );
		if ( $events ) {
			$link_attr['data-tcb-events'] = $events;
		}

		$attributes = '';
		foreach ( $link_attr as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value      = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 *
		 * @since 4.4.0
		 *
		 */
		$title       = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>' . $this->icon( $item, $depth );
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		/**
		 * Append megamenu descriptions stored in config
		 */
		if ( 1 === $depth && $this->get_menu_type() === 'mega' ) {
			$mega_description = $this->get_config( 'mega_desc' );
			if ( $mega_description ) {
				$mega_description = json_decode( base64_decode( $mega_description ), true );
				$mega_description = isset( $mega_description[ $item->ID ] ) ? $mega_description[ $item->ID ] : '';

				$item_output .= ! empty( $mega_description ) ? sprintf( self::$mega_description_template, $mega_description ) : '';
			}
		}

		if ( 1 === $depth && ! $this->has_children && $this->get_menu_type() === 'mega' && $this->is_editor_page() ) {
			$item_output .= tcb_template( 'elements/megamenu-no-items.phtml', null, true, 'backbone' );
		}

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 *
		 * @since 3.0.0
		 *
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

		/* keep a reference to the current menu itemID */
		$this->current_item = $item;
	}

	/**
	 * Checks if the current page is the editor page
	 * Also handles the case where the CM is rendered via ajax
	 *
	 * @return boolean
	 */
	protected function is_editor_page() {
		if ( ! isset( $this->is_editor_page ) ) {
			$this->is_editor_page = ( ( wp_doing_ajax() && ! empty( $_REQUEST['action'] ) && 'tcb_editor_ajax' === $_REQUEST['action'] ) || is_editor_page() );
		}

		return $this->is_editor_page;
	}

	/**
	 * Get the menu type. empty means regular WP menu
	 *
	 * @return string
	 */
	protected function get_menu_type() {
		return $this->get_config( 'type', 'regular' );
	}

	/**
	 * @param string $key allows "/" to split fields
	 * @param null   $default
	 *
	 * @return mixed
	 */
	protected function get_config( $key, $default = null ) {
		$fields = explode( '/', $key );
		$target = $GLOBALS['tcb_wp_menu'];

		while ( $fields ) {
			/* make sure this is always an array */
			$target = (array) $target;
			$field  = array_shift( $fields );
			if ( ! isset( $target[ $field ] ) ) {
				return $default;
			}
			$target = $target[ $field ];
		}

		return $target;
	}
}
