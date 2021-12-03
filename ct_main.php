<?php
/*
Plugin Name: Catered To
Plugin URI: http://jason.infinity.graphics/catered-to/
Description: A Catering Quote Generator
Version: 0.9.5
Author: Jason St. John
Author URI: http://jason.infinity.graphics
License: GPL2
*/
/*
Copyright 2016-2017  Jason St. John  (email : help@infinity.graphics)

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

//Custom Post Types and Taxonomies
require_once(dirname(__FILE__) . '/ct_cpt.php');
//Backend Admin Code
require_once(dirname(__FILE__) . '/ct_admin.php');
//Front End Menu Generator
require_once(dirname(__FILE__) . '/ct_menu.php');
//New DB Table Stuff
require_once(dirname(__FILE__) . '/ct_newtable.php');
register_activation_hook( __FILE__, 'ct_install' );
//Google Calendar Integration Code
//require_once(dirname(__FILE__) . '/ct_goog_int.php');
//Javascript And CSS Links
function ct_script_que() {
wp_register_script( 'pdfmin', '/wp-content/plugins/catered-to-0.9.5/js/pdfmake.min.js');
wp_enqueue_script('pdfmin');
wp_register_script( 'pdfstyle', '/wp-content/plugins/catered-to-0.9.5/js/vfs_fonts.js');
wp_enqueue_script('pdfstyle');
wp_register_script( 'timepicker', '/wp-content/plugins/catered-to-0.9.5/js/jquery.timepicker.min.js');
wp_enqueue_script('timepicker');
wp_register_script( 'ct-ajax-js', '/wp-content/plugins/catered-to-0.9.5/js/ct.js', array('jquery'));
wp_enqueue_script('ct-ajax-js');
wp_register_script( 'goog', 'https://apis.google.com/js/api.js');
wp_enqueue_script('goog');
wp_register_style( 'ct-styles', '/wp-content/plugins/catered-to-0.9.5/style/ct_style.css');
wp_enqueue_style('ct-styles');
wp_register_style( 'timepickercss', '/wp-content/plugins/catered-to-0.9.5/style/jquery.timepicker.min.css');
wp_enqueue_style('timepickercss');
wp_enqueue_script( 'jquery-ui-datepicker' );
}
add_action('admin_enqueue_scripts', 'ct_script_que');
?>