<?php

if( ! defined( 'ABSPATH' ) ) {
	return;
}

// Register the GiveASAP CPT
function giveasap_cpt() {

	$labels = array(
		'name'                  => _x( 'Simple Giveaways', 'Post Type General Name', 'giveasap' ),
		'singular_name'         => _x( 'Giveaway', 'Post Type Singular Name', 'giveasap' ),
		'menu_name'             => __( 'Giveaways', 'giveasap' ),
		'name_admin_bar'        => __( 'Giveaway', 'giveasap' ),
		'archives'              => __( 'Giveaway Archives', 'giveasap' ),
		'parent_item_colon'     => __( 'Parent Giveaway:', 'giveasap' ),
		'all_items'             => __( 'All Giveaways', 'giveasap' ),
		'add_new_item'          => __( 'Add New Giveaway', 'giveasap' ),
		'add_new'               => __( 'Add New', 'giveasap' ),
		'new_item'              => __( 'New Giveaway', 'giveasap' ),
		'edit_item'             => __( 'Edit Giveaway', 'giveasap' ),
		'update_item'           => __( 'Update Giveaway', 'giveasap' ),
		'view_item'             => __( 'View Giveaway', 'giveasap' ),
		'search_items'          => __( 'Search Giveaway', 'giveasap' ),
		'not_found'             => __( 'Not found', 'giveasap' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'giveasap' ),
		'featured_image'        => __( 'Featured Image', 'giveasap' ),
		'set_featured_image'    => __( 'Set featured image', 'giveasap' ),
		'remove_featured_image' => __( 'Remove featured image', 'giveasap' ),
		'use_featured_image'    => __( 'Use as featured image', 'giveasap' ),
		'insert_into_item'      => __( 'Insert into GiveASAP', 'giveasap' ),
		'uploaded_to_this_item' => __( 'Uploaded to this GiveASAP', 'giveasap' ),
		'items_list'            => __( 'Giveaway list', 'giveasap' ),
		'items_list_navigation' => __( 'Giveaway list navigation', 'giveasap' ),
		'filter_items_list'     => __( 'Filter Giveaway list', 'giveasap' ),
		'featured_image'        => __( 'Background Image', 'giveasap' ),
		'set_featured_image'    => __( 'Set background image', 'giveasap' ),
		'remove_featured_image' => __( 'Remove background image', 'giveasap' ),
		'use_featured_image'    => __( 'Use as background image', 'giveasap' ),
	);
	$args = array(
		'label'                 => __( 'Simple Giveaways', 'giveasap' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 10,
		'menu_icon'             => 'dashicons-products',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => false,
		'has_archive'           => true,		
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'rewrite' => array( 'slug' => 'giveaway' )
	);
	register_post_type( 'giveasap', $args );

}
add_action( 'init', 'giveasap_cpt', 0 );