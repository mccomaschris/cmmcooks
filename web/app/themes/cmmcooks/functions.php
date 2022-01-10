<?php
/**
 * CMMCooks functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package CMMCooks
 */

if ( ! defined( 'CMM_COOKS_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'CMM_COOKS_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function cmmcooks_setup() {
	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'cmmcooks_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function cmmcooks_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'cmmcooks_content_width', 640 );
}
add_action( 'after_setup_theme', 'cmmcooks_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function cmmcooks_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'cmmcooks' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'cmmcooks' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'cmmcooks_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function cmmcooks_scripts() {
	wp_enqueue_style( 'cmmcooks-style', get_theme_file_uri( '/css/main.css' ), array(), CMM_COOKS_VERSION );
}
add_action( 'wp_enqueue_scripts', 'cmmcooks_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

add_filter(
	'body_class',
	function ( $classes ) {
		return array_merge( $classes, array( 'font-sans antialiased bg-stone-100' ) );
	}
);

/**
 * Register a custom post type called 'recipe'
 *
 * @see get_post_type_labels() for label keys.
 */
function cmmcooks_post_type() {
	$labels = array(
		'name'                  => _x( 'Recipes', 'Post type general name', 'cmmcooks' ),
		'singular_name'         => _x( 'Recipe', 'Post type singular name', 'cmmcooks' ),
		'menu_name'             => _x( 'Recipes', 'Admin Menu text', 'cmmcooks' ),
		'name_admin_bar'        => _x( 'Recipe', 'Add New on Toolbar', 'cmmcooks' ),
		'add_new'               => __( 'Add New', 'cmmcooks' ),
		'add_new_item'          => __( 'Add New Recipe', 'cmmcooks' ),
		'new_item'              => __( 'New Recipe', 'cmmcooks' ),
		'edit_item'             => __( 'Edit Recipe', 'cmmcooks' ),
		'view_item'             => __( 'View Recipe', 'cmmcooks' ),
		'all_items'             => __( 'All Recipes', 'cmmcooks' ),
		'search_items'          => __( 'Search Recipes', 'cmmcooks' ),
		'parent_item_colon'     => __( 'Parent Recipes:', 'cmmcooks' ),
		'not_found'             => __( 'No Recipes found.', 'cmmcooks' ),
		'not_found_in_trash'    => __( 'No Recipes found in Trash.', 'cmmcooks' ),
		'featured_image'        => _x( 'Recipe Image', 'Overrides the "Featured Image" phrase for this post type. Added in 4.3', 'cmmcooks' ),
		'set_featured_image'    => _x( 'Set image', 'Overrides the "Set featured image" phrase for this post type. Added in 4.3', 'cmmcooks' ),
		'remove_featured_image' => _x( 'Remove image', 'Overrides the "Remove featured image" phrase for this post type. Added in 4.3', 'cmmcooks' ),
		'use_featured_image'    => _x( 'Use as image', 'Overrides the "Use as featured image" phrase for this post type. Added in 4.3', 'cmmcooks' ),
		'archives'              => _x( 'Recipe archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4', 'cmmcooks' ),
		'insert_into_item'      => _x( 'Insert into Recipe', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4', 'cmmcooks' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this Recipe', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4', 'cmmcooks' ),
		'filter_items_list'     => _x( 'Filter Recipes list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/"Filter pages list". Added in 4.4', 'cmmcooks' ),
		'items_list_navigation' => _x( 'Recipes list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/"Pages list navigation". Added in 4.4', 'cmmcooks' ),
		'items_list'            => _x( 'Recipes list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/"Pages list". Added in 4.4', 'cmmcooks' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'recipe' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'supports'           => array( 'title', 'custom-fields', 'page-attributes', 'revisions' ),
		'show_in_rest'       => true,
		'taxonomies'         => array( 'category' ),
		'menu_icon'          => 'dashicons-editor-ol',
	);

	register_post_type( 'recipe', $args );
}
add_action( 'init', 'cmmcooks_post_type' );

/**
 * Add 'search' to the acceptable URL parameters
 *
 * @param array $vars The array of acceptable URL parameters.
 * @return array
 */
function cmmcooks_url_parameters( $vars ) {
	$vars[] = 'search';
	return $vars;
}
add_filter( 'query_vars', 'cmmcooks_url_parameters' );

/**
 * Extend our where query to be able to get by first letter
 *
 * @param string $where The where string from WP_Query.
 * @param mixed  $wp_query The WP_Query.
 * @return string
 */
function extend_wp_query_where( $where, $wp_query ) {
	if ( $extend_where = $wp_query->get( 'extend_where' ) ) { // phpcs:ignore
		$where .= ' AND ' . $extend_where;
	}
	return $where;
}
add_filter( 'posts_where', 'extend_wp_query_where', 10, 2 );

/**
 * Search Results shortcode
 *
 * @return string
 */
function cmmcooks_search_shortcode() {

	if ( get_query_var( 'search' ) ) {
		$search_term = esc_attr( get_query_var( 'search' ) );
	} else {
		$search_term = '';
	}

	$search_query = new WP_Query(
		array(
			'post_type'      => 'recipe',
			'posts_per_page' => -1,
			'orderby'        => array(
				'date' => 'DESC',
			),
			'extend_where'   => "(post_title like '%" . $search_term . "%')",
		),
	);

	if ( $search_query->have_posts() ) {
		$output = '<div class="w-full grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-8">';

		while ( $search_query->have_posts() ) {
			$search_query->the_post();

			$output .= '<div class="h-full border border-stone-300 rounded flex flex-col bg-stone-50 shadow-sm transition-all duration-200 ease-out hover:shadow-lg">';

			if ( get_field( 'recipe_image' ) ) {
				$output .= '<div class="h-48 bg-cover bg-center" style="background: url(' . esc_url( get_field( 'recipe_image' ) ) . ')"></div>';
			} else {
				$output .= '<div class="bg-stone-700 h-48 flex items-center justify-center font-semibold text-stone-200">No image available.</div>';
			}

			$output .= '<div class="flex flex-col px-6 py-6">';
			$output .= '<a href="' . esc_url( get_the_permalink() ) . '" class="text-xl hover:text-green-700 hover:no-underline transition-all duration-100 ease-out">' . esc_attr( get_the_title() ) . '</a>';
			$output .= '<div class="my-2 flex-1">';
			$output .= wp_kses_post( get_field( 'recipe_description' ) );
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';

		}
		$output .= '</div>';
	} else {
		$output = '<p>No recipes found.</p>';
	}

	wp_reset_postdata();

	return $output;
}
add_shortcode( 'cmmcooks_search', 'cmmcooks_search_shortcode' );

/**
 * Make sure archive gets custom post type
 *
 * @param object $query The WordPress Query class.
 * @return object
 */
function cmmcooks_query_post_type( $query ) {
	$post_types = get_post_types();

	if ( is_category() || is_tag() ) {

		$post_type = get_query_var( 'recipe' );

		if ( $post_type ) {
			$post_type = $post_type;
		} else {
			$post_type = $post_types;
		}

		$query->set( 'post_type', $post_type );

		return $query;
	}
}
add_filter( 'pre_get_posts', 'cmmcooks_query_post_type' );
