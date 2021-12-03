<?php
//This is the shortcode registration for CtMenu

function ct_menu(){
	echo '<div id="ct-container">';
	ct_selector();
	ct_menus_gen();
	echo'</div>';	
	echo'<div id="ct-list" class="ct-hidden"></div>';
}

add_shortcode( 'ctMenu', 'ct_menu' );

//This is the menu generator it creates menus by course in html. My hope is it will cache better than my initial AJAX idea.

function ct_menus_gen(){
    $courses = get_terms( array('taxonomy'=>'ct_course','parent'=>0,'hierarchical'=>true,'child_of'=>0,'hide_empty'=>false));
    echo '<div id="ct-menu">';
	echo '<div id="all" class="ct-shown">';
	$ct_args = ct_course_args('all');
	ct_course_tables($ct_args);
	echo '</div>';
  	foreach ($courses as $course) {
  		echo '<div id="'.$course->slug.'" class="ct-hidden">';
		$ct_args = ct_course_args($course->slug);
		ct_course_tables($ct_args);
		echo '</div>';
		}
}

//Creates the tables for the menus

function ct_course_tables($ct_menu_args) {
	wp_reset_query();
	$loopy = new WP_Query($ct_menu_args);
	echo '<table><thead><tr><th>Name</th><th>Price</th><th>Add To Event</th></tr></thead>';
	while ( $loopy->have_posts()) { $loopy->the_post();
		$title = get_the_title();
		$id = get_the_ID();
		$price = get_post_meta($id,'ct_price' ,true);
		$ctClassString = ct_option_classes($id);
	echo '<tr class="ct-list-item '.$ctClassString.'"><td>'.$title.'</td><td> $ '.$price.'</td><td><input type="number" style="width:60px"><input type="button" value="Add"></td></tr>'."\n";}
	wp_reset_postdata();
	echo '</table>';
}

//Generates the course arguments for the WP_Query

function ct_course_args($ct_course){
		$ct_menu_args = array(
		  'post_type' => 'ct_item',
		  'orderby' => 'title',
		  'order' => 'ASC',
		);
		if($ct_course != "all"){
			$ct_tax_query = array ( 
				'taxonomy' => 'ct_course',
				'field'    => 'slug',
				'terms'    => $ct_course
			);
			$ct_menu_args['tax_query'] = array($ct_tax_query);		
		}
		 
  return $ct_menu_args;
}

function ct_selector(){
    $catloop = get_terms( array('taxonomy'=>'ct_course','parent'=>0,'hierarchical'=>true,'child_of'=>0,'hide_empty'=>false));
    echo '<div id="ct-menu-select"><ul class="ct-menu-nav">';
	echo '<li><input class="ct-course" type="button" value="All" name="all"></li>';
  	foreach ($catloop as $cata) {
  		echo '<li><input class="ct-course" type="button" value="'.$cata->name.'" name="'.$cata->slug.'"></li>';
		$subterms = get_terms(array('taxonomy'=>'ct_course','parent'=>$cata->term_id, 'hide_empty'=>false));
		if($subterms){
			echo '<ul style="display:none;" class="sub-course">';
			foreach ( $subterms as $subterm ) {
				echo '<li><input class="ct-course" type="button" value="'.$subterm->name.'" name="'.$subterm->slug.'"></li>';
			}
			echo '</ul>';
		}
	}
    echo '</ul><br />';
	echo '<ul class="ct-menu-opt">';
	$optloop = get_terms( array('taxonomy'=>'ct_options', 'hide_empty'=>false));
	foreach ( $optloop as $opt){
		echo '<li><input class="ct-option" type="checkbox" value="'.$opt->name.'" id="'.$opt->slug.'"name="'.$opt->slug.'"><label class="ct-check-label" for="'.$opt->slug.'">'.$opt->name.'</li>';
	}
    echo'</div>';
}

//Generates the options classes for each item 

function ct_option_classes($id){
	$options = wp_get_post_terms($id, 'ct_options');
	$class_options = implode(" ", wp_list_pluck($options, 'slug'));
	return $class_options;
}
?>