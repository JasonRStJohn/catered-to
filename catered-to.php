<?php
/*
Plugin Name: Catered To
Plugin URI: http://jason.infinity.graphics/catered-to/
Description: A Catering Quote Generator
Version: 0.2
Author: Jason St. John
Author URI: http://jason.infinity.graphics
License: GPL2
*/
/*
Copyright 2016  Jason St. John  (email : help@infinity.graphics)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function enqueue_ct_ajax_scripts() {
    wp_register_script( 'ct-ajax-js', 'http://jason.infinity.graphics/steve/wp-content/plugins/catered-to/js/ct.js', array('jquery'));
    wp_localize_script( 'ct-ajax-js', 'ajax_genre_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    wp_enqueue_script( 'ct-ajax-js' );
}
add_action('wp_enqueue_scripts', 'enqueue_ct_ajax_scripts');


//Adds Items and Quotes post types to store individual menu items


if ( ! function_exists('ct_item_type') ) {

// Register Custom Post Type
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
		'show_in_menu'          => true,
		'menu_position'         => 5,
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
//Creates a callback funtion for custom Price metabox in the edit screen of the Items post type
function custom_meta_box_markup($post)
{
   wp_nonce_field(basename(__FILE__), 'ct_price_nonce');
   $price_value = $post->ct_price;
   $title_value = $post->post_title;
   $with_tax_value = $price_value*1.07;
   echo('$<input type="number" name="ct_price"
           pattern="[0-9]+([\.,][0-9]+)?" step="0.01" value="'.$price_value.'"
            title="This should be a number with up to 2 decimal places.">');
   echo('<br />With tax a '.$title_value.' is $'.round($with_tax_value, 2));
}
//Adds the Price Metabox to Items using the above callback
function add_custom_meta_box()
{
    add_meta_box("demo-meta-box", "Price", "custom_meta_box_markup", "ct_item", "normal", "high", null);
}

add_action("add_meta_boxes", "add_custom_meta_box");
//Saves Price data to post metadata ct_price
add_action('save_post', 'save_price_meta');
function save_price_meta($post_id){
   $price_input= $_POST['ct_price'];
   $ct_is_nonce= ( isset( $_POST[ 'ct_price_nonce' ] ) && wp_verify_nonce( $_POST[ 'ct_price_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
   if ($ct_is_nonce){
   update_post_meta($post_id,'ct_price',$price_input);
}
}
//Adds Price column to Items list
add_filter( 'manage_ct_item_posts_columns' , 'add_item_columns');
function add_item_columns($columns) {
    return array_merge($columns,
        array('price'=>__('Price')));
}
function custom_ct_item_column($column, $post_id) {
   switch($column){
        case 'price':
            echo('$'.get_post_meta($post_id,'ct_price' ,true));
            break;
    }
}
add_action('manage_ct_item_posts_custom_column','custom_ct_item_column',10,2);
// Creates Course Taxonomy
if ( ! function_exists( 'ct_course_tax' ) ) {
// Register Custom Taxonomy
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
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'ct_course', array( 'ct_item' ), $args );

}
add_action( 'init', 'ct_course_tax', 0 );

}

function ajax_ct_course_select(){
    $catloop = get_terms( array('taxonomy'=>'ct_course'));
    echo '<div id="ct-menu-select"><ul class="ct-menu-nav">';
  	foreach ($catloop as $cata) {
  		echo '<li><input type="button" value="'.$cata->name.'" name="'.$cata->slug.'"></li>';
  	}
    echo '</ul><br />';

    echo'</div>';
}

//AJAX handler for regenerating the menu table
function ajax_ct_course(){
	$query_data = $_GET;
   $ctCourse = ($query_data['course']);
    $ct_tax_query = array ( array(
  	'taxonomy' => 'ct_course',
  	'field'    => 'slug',
  	'terms'    => $ctCourse
  )
  );
  $ct_menu_args = array(
      'post_type' => 'ct_item',
      'orderby' => 'title',
      'order' => 'ASC',
  		'tax_query' => $ct_tax_query
    );
  ct_course_query($ct_menu_args);
}
//generic WP_Query for menu by category
function ct_course_query($ct_menu_args) {
	$loop = new WP_Query($ct_menu_args);
		echo '<table><thead><tr><th>Name</th><th>Price</th><th>Add To Event</th></tr></thead>';
	while ( $loop->have_posts()) : $loop->the_post();
	 $title = get_the_title();
   $price = get_post_meta(get_the_ID(),'ct_price' ,true);
   echo '<tr><td>'.$title.'</td><td> $ '.$price.'</td><td><input type="number" style="width:60px"><input type="button" value="Add"></tr>';
	endwhile; echo '</table>';
  echo '</div>';
  exit();
}
//initializes menu with all Courses
function ct_course_init(){
  $ct_menu_args = array(
      'post_type' => 'ct_item',
      'orderby' => 'title',
      'order' => 'ASC',
    );
  ct_course_query($ct_menu_args);
}

// Handler for Shortcode
function ajax_ct_course_page(){
  ajax_ct_course_select();
  echo '<div id="ct-menu">';
  ct_course_init();
  get_footer();
  echo'</div></div>';
}


add_shortcode( 'ctMenu', 'ajax_ct_course_page' );
//Add Ajax Actions
add_action('wp_ajax_course_select_filter', 'ajax_ct_course');
add_action('wp_ajax_nopriv_course_select_filter', 'ajax_ct_course');
?>
