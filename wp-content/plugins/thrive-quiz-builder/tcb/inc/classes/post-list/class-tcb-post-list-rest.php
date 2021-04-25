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
 * Class TCB_Post_List_REST
 */
class TCB_Post_List_REST {

	public static $namespace = 'tcb/v1';
	public static $route = '/posts';
	public static $term_per_page = 20;

	public function __construct() {
		$this->register_routes();
	}

	public function register_routes() {
		register_rest_route( self::$namespace, self::$route, array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'get_posts' ),
			),
		) );

		register_rest_route( self::$namespace, self::$route . '/html', array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'get_html' ),
			),
		) );

		register_rest_route( self::$namespace, self::$route . '/terms', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_terms' ),
				'permission_callback' => array( $this, 'route_permission' ),
			),
		) );

		register_rest_route( self::$namespace, self::$route . '/authors', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_authors' ),
				'permission_callback' => array( $this, 'route_permission' ),
			),
		) );

		register_rest_route( self::$namespace, self::$route . '/taxonomies', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_taxonomies' ),
				'permission_callback' => array( $this, 'route_permission' ),
			),
		) );
	}

	/**
	 * Check if a given request has access to route
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|bool
	 */
	public function route_permission( $request ) {
		return TCB_Product::has_access();
	}

	/**
	 * Get terms from taxonomy
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_terms( $request ) {

		$taxonomy = $request->get_param( 'taxonomy' );
		$search   = $request->get_param( 'search' );

		$terms = array();

		if ( ! empty( $taxonomy ) ) {

			$args = array(
				'number'     => 20,
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			);

			if ( ! empty( $search ) ) {
				$args['search'] = $search;
			}

			$specific = $request->get_param( 'specific' );
			if ( ! empty( $specific ) ) {
				$args = array(
					'number'     => 0,
					'hide_empty' => false,
					'include'    => $request->get_param( 'terms' ),
				);
			}

			$all = get_terms( $args );

			$terms = array_map( function ( $item ) {
				return array(
					'value' => $item->term_id,
					'label' => $item->name,
				);
			}, $all );
		}

		$terms = array_values( $terms );

		return new WP_REST_Response( $terms );
	}

	/**
	 * Get terms from taxonomy
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_posts( $request ) {

		$post_type = $request->get_param( 'post_type' );
		$search    = $request->get_param( 'search' );

		$args = array(
			'posts_per_page' => self::$term_per_page,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_type'      => $post_type,
		);

		if ( ! empty( $search ) ) {
			$args['s'] = $search;
		}

		/* this is for when we just want some specifc results returned */
		$specific = $request->get_param( 'specific' );
		if ( ! empty( $specific ) ) {
			$args = array(
				'number'  => 0,
				'include' => $request->get_param( 'terms' ),
			);
		}

		$all = get_posts( $args );

		$posts = array_map( function ( $item ) {
			return array(
				'value' => $item->ID,
				'label' => $item->post_title,
			);
		}, $all );

		$posts = array_values( $posts );

		return new WP_REST_Response( $posts );

	}

	/**
	 * Get authors of the blog
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_authors( $request ) {

		$search = $request->get_param( 'search' );

		$args = array( 'number' => 20 );

		if ( ! empty( $search ) ) {
			$args['search'] = '*' . $search . '*';
		}
		/* this is for when we just want some specifc results returned */
		$specific = $request->get_param( 'specific' );
		if ( ! empty( $specific ) ) {
			$args = array(
				'number'  => 0,
				'include' => $request->get_param( 'terms' ),
			);
		}

		$all = get_users( $args );

		$authors = array_map( function ( $item ) {
			return array(
				'value' => $item->ID,
				'label' => $item->display_name,
			);
		}, $all );

		$authors = array_values( $authors );

		return new WP_REST_Response( $authors );
	}

	/**
	 * Get post type taxonomies
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_taxonomies( $request ) {

		$post_type = $request->get_param( 'post_type' );

		if ( empty( $post_type ) ) {
			$taxonomies = array();
		} else {
			$all = get_object_taxonomies( $post_type, 'object' );

			$taxonomies = array_map( function ( $item ) {
				return array(
					'value' => $item->name,
					'label' => $item->label,
				);
			}, $all );

			$taxonomies = array_filter( $taxonomies, function ( $taxonomy ) {
				$terms = get_terms( array(
					'taxonomy'   => $taxonomy['value'],
					'hide_empty' => false,
				) );

				/* we only return taxonomies that have terms inside them */

				return count( $terms ) > 0;
			} );
		}

		$taxonomies = array_values( $taxonomies );

		return new WP_REST_Response( $taxonomies );
	}

	/**
	 * Get posts filtered by args for the post list
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_html( $request ) {
		/* if we send a template parameter, we're going to print the post list after that one */
		$content = $request->get_param( 'content' );
		$id      = $request->get_param( 'ID' );
		$args    = $request->get_param( 'args' );

		$args = array_merge( array(
			'attr'       => array(),
			'query'      => array(),
			'identifier' => '',
		), empty( $args ) ? array() : $args );

		$query_args = array_merge( TCB_Post_List::prepare_wp_query_args( $args['attr'] ), $args['query'] );

		$post_list = new TCB_Post_List( $args['attr'], $content );

		/* if the 'get_initial_posts' flag is not active, get the posts normally */
		if ( empty( $args['attr']['get_initial_posts'] ) ) {
			$posts = get_posts( $query_args );
		} else {
			/* if the flag is active, use the default query to get the post info we need */
			$post_ids = empty( $args['attr']['post_ids'] ) ? array() : $args['attr']['post_ids'];

			$posts = $this->get_existing_posts( $post_ids );
		}

		global $post;

		$GLOBALS[ TCB_DO_NOT_RENDER_POST_LIST ] = true;

		$results = array();
		/* posts are sent with key value, because it's easier to find them, but we send a parameter of order so we know how to display them */
		foreach ( $posts as $key => $post ) {
			if ( empty( $content ) ) {
				$results[ get_the_ID() ] = TCB_Post_List::post_info( $key + 1 );
			} else {
				$results[ $key + 1 ] = $post_list->article_content();
			}
		}

		$GLOBALS[ TCB_DO_NOT_RENDER_POST_LIST ] = false;

		return new WP_REST_Response( array(
			'posts' => $results,
			'count' => count( $results ),
		) );
	}

	/**
	 * Get the first 7 posts and all the posts that exist in the current page.
	 *
	 * @param $post_ids
	 *
	 * @return array
	 */
	public function get_existing_posts( $post_ids ) {
		$default_query = TCB_Post_List::get_default_query();

		$default_query['offset']         = 0;
		$default_query['posts_per_page'] = 7;

		/* get the first 7 posts (  6 + 1 to take into account excluding current post )*/
		$first_posts = get_posts( $default_query );

		if ( ! empty( $post_ids ) ) {
			$existing_posts_query = array(
				'posts_per_page' => count( $post_ids ),
				'post__in'       => $post_ids,
				'post_status'    => 'any',
				/* these can also be pages or custom post types */
				'post_type'      => 'any',
			);

			/* also get the posts that are already in the page ( we have their IDs in 'get_initial_posts' ) */
			$first_posts = array_merge( $first_posts, get_posts( $existing_posts_query ) );
		}

		return $first_posts;
	}
}

new TCB_Post_List_REST();
