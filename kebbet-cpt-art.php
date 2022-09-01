<?php
/**
 * Plugin Name: Kebbet plugins - Custom Post Type: Art
 * Plugin URI: https://github.com/kebbet/kebbet-cpt-art
 * Description: Registers a Custom Post Type.
 * Version: 1.2.1
 * Author: Erik Betshammar
 * Author URI: https://verkan.se
 *
 * @package kebbet-cpt-art
 */

namespace kebbet\cpt\art;

const POSTTYPE  = 'art';
const SLUG      = 'konst';
const ICON      = 'portfolio';
const MENUPOS   = 5;
const THUMBNAIL = true;

/**
 * Link to ICONS
 *
 * @link https://developer.wordpress.org/resource/dashicons/
 */

/**
 * Hook into the 'init' action
 */
function init() {
	load_textdomain();
	register();
	if ( true === THUMBNAIL ) {
		add_theme_support( 'post-thumbnails' );
	}
	add_action( 'admin_enqueue_scripts', __NAMESPACE__ .'\enqueue_scripts' );
}
add_action( 'init', __NAMESPACE__ . '\init', 0 );

/**
 * Flush rewrite rules on registration.
 *
 * @link https://codex.wordpress.org/Function_Reference/register_post_type
 */
function rewrite_flush() {
	// First, we "add" the custom post type via the above written function.
	// Note: "add" is written with quotes, as CPTs don't get added to the DB,
	// They are only referenced in the post_type column with a post entry,
	// when you add a post of this CPT.
	register();

	// ATTENTION: This is *only* done during plugin activation hook in this example!
	// You should *NEVER EVER* do this on every page load!!
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\rewrite_flush' );

/**
 * Load plugin textdomain.
 */
function load_textdomain() {
	load_plugin_textdomain( 'kebbet-cpt-art', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * Register Custom Post Type
 */
function register() {
	$labels_args   = array(
		'name'                     => _x( 'Art', 'Post Type General Name', 'kebbet-cpt-art' ),
		'singular_name'            => _x( 'Art', 'Post Type Singular Name', 'kebbet-cpt-art' ),
		'menu_name'                => __( 'Art', 'kebbet-cpt-art' ),
		'name_admin_bar'           => __( 'Art-post', 'kebbet-cpt-art' ),
		'parent_item_colon'        => __( 'Parent post:', 'kebbet-cpt-art' ),
		'all_items'                => __( 'All posts', 'kebbet-cpt-art' ),
		'add_new_item'             => __( 'Add new', 'kebbet-cpt-art' ),
		'add_new'                  => __( 'Add new post', 'kebbet-cpt-art' ),
		'new_item'                 => __( 'New post', 'kebbet-cpt-art' ),
		'edit_item'                => __( 'Edit post', 'kebbet-cpt-art' ),
		'update_item'              => __( 'Update post', 'kebbet-cpt-art' ),
		'view_item'                => __( 'View post', 'kebbet-cpt-art' ),
		'view_items'               => __( 'View posts', 'kebbet-cpt-art' ),
		'search_items'             => __( 'Search posts', 'kebbet-cpt-art' ),
		'not_found'                => __( 'Not found', 'kebbet-cpt-art' ),
		'not_found_in_trash'       => __( 'No posts found in Trash', 'kebbet-cpt-art' ),
		'featured_image'           => __( 'Featured image', 'kebbet-cpt-art' ),
		'set_featured_image'       => __( 'Set featured image', 'kebbet-cpt-art' ),
		'remove_featured_image'    => __( 'Remove featured image', 'kebbet-cpt-art' ),
		'use_featured_image'       => __( 'Use as featured image', 'kebbet-cpt-art' ),
		'insert_into_item'         => __( 'Insert into item', 'kebbet-cpt-art' ),
		'uploaded_to_this_item'    => __( 'Uploaded to this post', 'kebbet-cpt-art' ),
		'items_list'               => __( 'Items list', 'kebbet-cpt-art' ),
		'items_list_navigation'    => __( 'Items list navigation', 'kebbet-cpt-art' ),
		'filter_items_list'        => __( 'Filter items list', 'kebbet-cpt-art' ),
		'archives'                 => __( 'Art-posts archive', 'kebbet-cpt-art' ),
		'attributes'               => __( 'Art-post attributes', 'kebbet-cpt-art' ),
		'item_published'           => __( 'Post published', 'kebbet-cpt-art' ),
		'item_published_privately' => __( 'Post published privately', 'kebbet-cpt-art' ),
		'item_reverted_to_draft'   => __( 'Post reverted to Draft', 'kebbet-cpt-art' ),
		'item_scheduled'           => __( 'Post scheduled', 'kebbet-cpt-art' ),
		'item_updated'             => __( 'Post updated', 'kebbet-cpt-art' ),
		// 5.7 + 5.8
		'filter_by_date'           => __( 'Filter posts by date', 'kebbet-cpt-art' ),
		'item_link'                => __( 'Art post link', 'kebbet-cpt-art' ),
		'item_link_description'    => __( 'A link to an art post', 'kebbet-cpt-art' ),
	);
	$supports_args = array(
		'author',
		'title',
		'editor',
		'page-attributes',
	);

	if ( true === THUMBNAIL ) {
		$supports_args = array_merge( $supports_args, array( 'thumbnail' ) );
	}

	$rewrite_args      = array(
		'slug'       => SLUG,
		'with_front' => false,
		'pages'      => false,
		'feeds'      => true,
	);
	$capabilities_args = \cpt\kebbet\art\roles\capabilities();
	$post_type_args    = array(
		'label'               => __( 'Art post type', 'kebbet-cpt-art' ),
		'description'         => __( 'Custom post type for Art', 'kebbet-cpt-art' ),
		'labels'              => $labels_args,
		'supports'            => $supports_args,
		'taxonomies'          => array(),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => MENUPOS,
		'menu_icon'           => 'dashicons-' . ICON,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => SLUG,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite_args,
		'capabilities'        => $capabilities_args,
		// Adding map_meta_cap will map the meta correctly.
		'show_in_rest'        => true,
		'map_meta_cap'        => true,
	);
	register_post_type( POSTTYPE, $post_type_args );
}

/**
 * Enqueue plugin scripts and styles.
 *
 * @since 1.2.1
 *
 * @param string $page The page/file name.
 * @return void
 */
function enqueue_scripts( $page ) {
	$assets_pages = array(
		'index.php',
	);
	if ( in_array( $page, $assets_pages, true ) ) {
		wp_enqueue_style( POSTTYPE . '_scripts', plugin_dir_url( __FILE__ ) . 'assets/style.css', array(), '1.2.1' );
	}
}

/**
 * Add the content to the `At a glance`-widget.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/at-a-glance.php';

/**
 * Adds and modifies the admin columns for the post type.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/admin-columns.php';

/**
 * Adds admin messages for the post type.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/admin-messages.php';

/**
 * Adjust roles and capabilities for post type
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/roles.php';

