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
//Adds Items and Quotes post types to store individual menu items
add_action( 'init', 'create_post_type' );
function create_post_type() {
  register_post_type( 'ct_item',
    array(
      'labels' => array(
        'name' => __( 'Items' ),
        'singular_name' => __( 'Item' )
      ),
      'public' => true,
      'has_archive' => true,
      'menu_icon' => 'dashicons-products',
      'supports' => array('title'),
    )
);
    register_post_type( 'ct_event',
        array(
            'labels' => array(
               'name' => __('Events'),
               'singular_name' => __('Event'),
            ),
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-products',
            'supports' => array('title'),
        )
);
flush_rewrite_rules();
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
    add_meta_box("demo-meta-box", "Price", "custom_meta_box_markup", "item", "normal", "high", null);
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
add_filter( 'manage_item_posts_columns' , 'add_item_columns');
function add_item_columns($columns) {
    return array_merge($columns,
        array('price'=>__('Price')));
}
function custom_item_column($column, $post_id) {
   switch($column){
        case 'price':
            echo('$'.get_post_meta($post_id,'ct_price' ,true));
            break;
    }
}
add_action('manage_item_posts_custom_column','custom_item_column',10,2);
//creates Course custom taxonomy
function ct_create_course() {
    register_taxonomy('course','item', array(
        'label' => __('Course', 'textdomain'),
        'rewrite' => array('slug'=>'course'),
        'hierarchical' => true,
        'update_count_callback' => '_update_post_term_count'
    ) );
}
add_action( 'init', 'ct_create_course',0);
//
