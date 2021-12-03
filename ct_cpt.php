<?php
//Adds Items and Quotes post types to store individual menu items
if ( ! function_exists('ct_event') ) {

// Creates Event Post Type
function ct_event() {

	$labels = array(
		'name'                  => _x( 'Events', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Events', 'text_domain' ),
		'name_admin_bar'        => __( 'Events', 'text_domain' ),
		'archives'              => __( 'Event Archives', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Events', 'text_domain' ),
		'add_new_item'          => __( 'Add New Event', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Event', 'text_domain' ),
		'edit_item'             => __( 'Edit Event', 'text_domain' ),
		'update_item'           => __( 'Update Event', 'text_domain' ),
		'view_item'             => __( 'View Event', 'text_domain' ),
		'search_items'          => __( 'Search Events', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into event', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this event', 'text_domain' ),
		'items_list'            => __( 'Event list', 'text_domain' ),
		'items_list_navigation' => __( 'Event list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter events list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Event', 'text_domain' ),
		'description'           => __( 'Catering Event / Menu', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => 'catered-to',
		'menu_position'         => 1,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'ct_event', $args );

}
add_action( 'init', 'ct_event', 0 );

}


if ( ! function_exists('ct_item_type') ) {

// Creates Item Post Type

function ct_item_type() {

	$labels = array(
		'name'                  => _x( 'Menu Items', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Menu Item', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Menu Items', 'text_domain' ),
		'name_admin_bar'        => __( 'Menu Items', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Items', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add Item', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Menu Item', 'text_domain' ),
		'description'           => __( 'Catered To Menu Item', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', ),
		'taxonomies'            => array(''),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => 'catered-to',
		'menu_position'         => 2,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'ct_item', $args );
}
add_action( 'init', 'ct_item_type', 0 );
}

if ( ! function_exists( 'ct_course_tax' ) ) {

// Registers Course Taxonomy

function ct_course_tax() {

	$labels = array(
		'name'                       => _x( 'Course', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Course', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Course', 'text_domain' ),
		'all_items'                  => __( 'All Courses', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Course', 'text_domain' ),
		'add_new_item'               => __( 'Add New Course', 'text_domain' ),
		'edit_item'                  => __( 'Edit Item', 'text_domain' ),
		'update_item'                => __( 'Update Item', 'text_domain' ),
		'view_item'                  => __( 'View Item', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Items', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No items', 'text_domain' ),
		'items_list'                 => __( 'Items list', 'text_domain' ),
		'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_in_menu'            =>'catered-to',
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'ct_course', array( 'ct_item' ), $args );

}
add_action( 'init', 'ct_course_tax', 0 );

}

if ( ! function_exists( 'ct_options_tax' ) ) {

//Registers Options Taxonomy

function ct_options_tax() {

	$labels = array(
		'name'                       => _x( 'Options', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Option', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Options', 'text_domain' ),
		'all_items'                  => __( 'All Options', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Option Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Option', 'text_domain' ),
		'edit_item'                  => __( 'Edit Option', 'text_domain' ),
		'update_item'                => __( 'Update Option', 'text_domain' ),
		'view_item'                  => __( 'View Option', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Items', 'text_domain' ),
		'search_items'               => __( 'Search Options', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No options', 'text_domain' ),
		'items_list'                 => __( 'Options List', 'text_domain' ),
		'items_list_navigation'      => __( 'Options list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'ct_options', array( 'ct_item' ), $args );

}
add_action( 'init', 'ct_options_tax', 0 );
}
if ( ! function_exists('ct_service_type') ) {

// Creates Service Post Type

function ct_service_type() {

	$labels = array(
		'name'                  => _x( 'Service Items', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Service Item', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Service Items', 'text_domain' ),
		'name_admin_bar'        => __( 'Service Items', 'text_domain' ),
		'archives'              => __( 'Service Archives', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Services', 'text_domain' ),
		'add_new_item'          => __( 'Add New Service', 'text_domain' ),
		'add_new'               => __( 'Add Service', 'text_domain' ),
		'new_item'              => __( 'New Service', 'text_domain' ),
		'edit_item'             => __( 'Edit Service', 'text_domain' ),
		'update_item'           => __( 'Update Service', 'text_domain' ),
		'view_item'             => __( 'View Service', 'text_domain' ),
		'search_items'          => __( 'Search Service', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into Service', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Service', 'text_domain' ),
		'items_list'            => __( 'Services list', 'text_domain' ),
		'items_list_navigation' => __( 'Services list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter Services list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Service Item', 'text_domain' ),
		'description'           => __( 'Catered To Service Item', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', ),
		'taxonomies'            => array(''),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => 'catered-to',
		'menu_position'         => 2,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'ct_service', $args );
}
add_action( 'init', 'ct_service_type', 0 );
}

?>