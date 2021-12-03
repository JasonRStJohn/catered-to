<?php
//Adds Catered To Menu Page
add_action('admin_menu', function(){add_menu_page( "Catered To", "Catered To", "manage_options","catered-to", "ct_menu_page", "dashicons-heart",7);});

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

function add_custom_meta_boxes() {
    add_meta_box("price-meta-box", "Price", "custom_meta_box_markup", array("ct_item","ct_service"), "normal", "high", null);
	add_meta_box("quote-add-meta-box", "Add Item", "ct_menu_add", "ct_event", "normal", "high", null);
	add_meta_box("event-meta-box","Event Details","ct_event_logistics", "ct_event","side");
	add_meta_box("quote-meta-box", "Quote", "ct_menu_page", "ct_event", "normal", "high", null);
	add_meta_box("ct_event_notes","Notes","ct_notes_page","ct_event","side","high",null);
	add_meta_box("ct_event_financials","Financials","ct_financials_page","ct_event","normal","high",null);
	add_meta_box("ct_event_service","Add Service","ct_service_page","ct_event","normal","high",null);
	add_meta_box("ct_item_category_change","Manage Categories","ct_cat_page","ct_item","normal","high",null);
	add_meta_box("ct_event_terms","Terms","ct_termsandcond","ct_event","normal","high", null);
	add_meta_box("event-contact-meta-box","Contact Details","ct_event_contact_details", "ct_event","side");
	add_meta_box("ct_event_schedule_meta","Add Scheduling Item","ct_event_scheduler","ct_event","normal","high",null);
//	add_meta_box("ct_event_schedule_display_meta","Schedule","ct_schedule_display","ct_event","normal","high",null);
	add_meta_box("ct_goog_test","Google Calendar","ctgoogtest","ct_event","side");
}
add_action("add_meta_boxes", "add_custom_meta_boxes");

//Saves Price data to post metadata ct_price

add_action('save_post', 'save_price_meta');
function save_price_meta($post_id){
	$ct_post_type = get_post_type($post_id);
	$ct_is_nonce= ( isset( $_POST[ 'ct_price_nonce' ] ) && wp_verify_nonce( $_POST[ 'ct_price_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
	if($ct_post_type=='ct_service' || $ct_post_type=='ct_item'){
	if(isset($_POST['ct_price'])) {$price_input= $_POST['ct_price'];} else {$price_input=0;}
	if ($ct_is_nonce){
	update_post_meta($post_id,'ct_price',$price_input);
	}}
	if ($ct_post_type=='ct_event') {
		$ct_time = $_POST['ct_event_time'] ?: '';
		$ct_date = $_POST['ct_event_date'] ?: '';
		$ct_type = $_POST['ct_event_type'] ?: '';
		$ct_office_notes=$_POST['ct_office_notes'] ?: '';
		$ct_tax=$_POST['ct_event_tax'] ?: 0;
		$ct_grat=$_POST['ct_event_gratuity'] ?: 0;
		$ct_misc=$_POST['ct_event_miscfees'] ?: 0;
		$ct_end_time=$_POST['ct_event_end_time'] ?: '';
		$ct_terms=$_POST['ct_event_terms'] ?: '';
		$ct_contact=$_POST['ct_event_contact'] ?: '';
		$ct_email=$_POST['ct_event_email'] ?: '';
		$ct_homph=$_POST['ct_event_phone_home'] ?: '';
		$ct_mobph=$_POST['ct_event_phone_mob'] ?: '';
		$ct_address=$_POST['ct_event_address'] ?: '';
		$ct_venue = $_POST['ct_event_venue'] ?: '';
		$ct_guests = $_POST['ct_event_guests'] ?: '';
		$ct_customer_notes=$_POST['ct_customer_notes'] ?: '';
		$ct_kitchen_notes=$_POST['ct_kitchen_notes'] ?: '';
		$ct_equipment_list=$_POST['ct_equipment_list'] ?: '';
		$ct_event_status = $_POST['ct_event_status'] ?: 'Lead';
   if ($ct_is_nonce) {
	   update_post_meta($post_id,'ct_event_time',$ct_time);
	   update_post_meta($post_id,'ct_event_date',$ct_date);
	   update_post_meta($post_id,'ct_event_type',$ct_type);
	   update_post_meta($post_id,'ct_event_end_time',$ct_end_time);
	   update_post_meta($post_id,'ct_event_office_notes',$ct_office_notes);
	   update_post_meta($post_id,'ct_event_customer_notes',$ct_customer_notes);
	   update_post_meta($post_id,'ct_event_kitchen_notes',$ct_kitchen_notes);
	   update_post_meta($post_id,'ct_event_equipment_list',$ct_equipment_list);
	   update_post_meta($post_id,'ct_event_tax',$ct_tax);
	   update_post_meta($post_id,'ct_event_gratuity',$ct_grat);
	   update_post_meta($post_id,'ct_event_miscfees',$ct_misc);
	   update_post_meta($post_id,'ct_event_terms',$ct_terms);
	   update_post_meta($post_id,'ct_event_contact',$ct_contact);
	   update_post_meta($post_id,'ct_event_email',$ct_email);
	   update_post_meta($post_id,'ct_event_phone_home',$ct_homph);
	   update_post_meta($post_id,'ct_event_phone_mob',$ct_mobph);
	   update_post_meta($post_id,'ct_event_address',$ct_address);
	   update_post_meta($post_id,'ct_event_guests',$ct_guests);
	   update_post_meta($post_id,'ct_event_venue',$ct_venue);
	   update_post_meta($post_id,'ct_equipment_list',$ct_equipment_list);
	   update_post_meta($post_id,'ct_event_status',$ct_event_status);
   }
}
}
//Adds Price column to Items list

add_filter( 'manage_ct_item_posts_columns' , 'add_item_columns');
add_filter( 'manage_ct_service_posts_columns' , 'add_item_columns');

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
add_action('manage_ct_service_posts_custom_column','custom_ct_item_column',10,2);

//Jenn's Dev Menu Creation

function ct_menu_add($post) {
$ct_event_id = $_REQUEST['post'];
?>
	<div id="ct-order">
		<select id="ct-cat">
			<option disabled selected value> -- select an option -- </option>
			<?php ct_course_handler() ?>
		</select>
		<select id="ct-sub-cat">
			<option disabled selected value> -- select an option -- </option>
		</select>
		<select id="ct-item">
			<option disabled selected value> -- select an option -- </option>
		</select>
		<input id="ct-quantity" value="1"/>
		<span id="ct-price"></span>
		<button id="add"  name="Qty" <?php echo 'value="'.$ct_event_id.'"'; ?>>Add</button>
		<br />
		<label for="ct-item-notes">Notes (Optional): </label>
		<input id="ct-item-notes" name="ct-item-notes"/>
	</div>
	<?php
}

function ct_schedule_display() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'ct_sched_item';
	$event_id = $_REQUEST['post'] ?: 0;
	$initial_data = $wpdb->get_results('SELECT * FROM '.$table_name.' WHERE ct_event_id = '.$event_id, OBJECT);
	if($initial_data){
	$ct_sched_array = [];
	echo'<table id="ct-schedule-display"><h3 id="ct-event-sched-title">Schedule</h3>';
	foreach($initial_data as $initd){
		$time = $initd->ct_sched_time;
		$item = $initd->ct_sched_title;
		$sched_item_id = $initd->id;
		echo '<tr><td class="ct-sched-time">'.$time.'</td><td class="ct-sched-item">'.$item.'</td><td><button class="ct-sched-remove" id="'.$sched_item_id.'">X</button></td></tr>';
		$ct_sched_array[] = ['',$time,'',$item,''];
	}
	echo'</table>';
	echo '<script> var ct_json_sched_array = '.json_encode($ct_sched_array).'</script>';
	}
}

function ct_menu_page($post) {
$ct_event_title = $post->post_title;
$ct_event_venue = $post->ct_event_venue;
$ct_event_date = $post->ct_event_date;
$ct_event_time = $post->ct_event_time;
$ct_office_notes = $post->ct_event_office_notes;
$ct_kitchen_notes = $post->ct_event_kitchen_notes;
$ct_customer_notes = $post->ct_event_customer_notes;
$ct_equipment_list = $post->ct_equipment_list;
$ct_event_email= $post->ct_event_email;
$ct_event_contact= $post->ct_event_contact;
$ct_event_phone_home= $post->ct_event_phone_home;
$ct_event_phone_mob= $post->ct_event_phone_mob;
$ct_event_terms= $post->ct_event_terms ;
?>
	<style>canvas{display:none;}</style>
	<button id="officePDF">Office PDF</button>
	<button id="kitchenPDF">Kitchen PDF</button>
	<button id="customerPDF">Customer PDF</button>
	<div id="ct-quote">
		<br />
	<div id="ct-infotainer">
		<h1 id="ct-quote-header">
			<img src="http://jason.infinity.graphics/steve/wp-content/uploads/2017/03/samanthas-logo-e1490725818197.png"/>
		</h1>
		<br />
		<h3 id="ct-event-details-title">Event Details:</h3>
		<div id="ct-quote-log">
			<?php echo "<h4>".$ct_event_title."</h4><h4>".$ct_event_venue."</h4><h4>".$ct_event_date.", ".$ct_event_time."</h4>"; ?>
		</div>
	<br />
	<hr />
	<div id="ct-event-contact-details">
		<h3 id="ct-event-contacts-title">Contact Details:</h3>
		<?php echo "<h4>".$ct_event_contact."</h4><h4>".$ct_event_email."</h4><h4>h: ".$ct_event_phone_home."</h4><h4>m: ".$ct_event_phone_mob."</h4>"; ?>
	</div>
	<br />
	<hr />
	<div id="ct-office-notes">
		<h4 id="ct-event-notes-title">Office Notes:</h4>
		<?php echo "<p>".$ct_office_notes."</p>";  ?>
	</div>
	<div id="ct-kitchen-notes">
		<h4 id="ct-event-notes-title">Kitchen Notes:</h4>
		<?php echo "<p>".$ct_kitchen_notes."</p>";  ?>
	</div>
	<div id="ct-customer-notes">
		<h4 id="ct-event-notes-title">Customer Notes:</h4>
		<?php echo "<p>".$ct_customer_notes."</p>";  ?>
	</div>
	<div id="">
		<h4 id="ct-event-notes-title">Equipment List:</h4>
		<?php echo "<p>".$ct_equipment_list."</p>";  ?>
	</div>
	<div id="ct-event-terms">
		<h4 id="ct-event-terms-title">Terms:</h4>
		<?php echo "<p>".$ct_event_terms."</p>";  ?>
	</div>
	</div>
	<div id="ct-menu-cont">
		<h3 id="ct-event-menu-title">Menu</h3>
		<?php 
			ct_quote_cat_gen(); 
			ct_service_gen();
		?>
		<hr />
		<?php ct_gen_total($post); ?>
		<?php ct_schedule_display() ?>
	</div>
	</div>
	<hr />
<?php
}

$ct_quote_array = [];

function ct_quote_cat_gen(){
	global $wpdb;
	wp_reset_query();
	$catloop = get_terms( array('taxonomy'=>'ct_course','parent'=>0,'hierarchical'=>true,'child_of'=>0,'hide_empty'=>false));
	$table_name = $wpdb->prefix . 'ct_quote_item';
	$event_id = $_REQUEST['post'];

	foreach($catloop as $cata){
		$initial_data = $wpdb->get_results('SELECT * FROM '.$table_name.' WHERE ct_event_id = "'.$event_id.'" AND ct_cat ="'.$cata->slug.'"', OBJECT);
		if($initial_data){
			echo'<table id="ct-quote-'.$cata->slug.'"><h2>'.$cata->name.'</h2>';
			$ct_quote_array[] = [array('text'=>$cata->name, 'colSpan'=>2, 'decoration'=>'underline'),'','','',''];
			foreach($initial_data as $initd){
				$subcat = $initd->ct_subcat;
				$price = get_post_meta($initd->ct_item, 'ct_price',true);
				$quote_item_id = $initd->ct_item;
				$item = get_post($quote_item_id);
				$item_name = $item->post_title;
				$qty = $initd->ct_qty;
				$order_item_id = $initd->id;
				$cost = sprintf('%0.2f',($price * $qty));
				$notes = $initd->ct_notes;
				echo'<tr class="ct-add" value='.$price.'><td class="ct-subcat">'.$subcat.'</td><td class="ct-itemname">'.$item_name.' </td><td class="ct-qty-table"> <input class="ct-quant-changer" id="'.$order_item_id.'" value="'.$qty.'" /></td><td class="ct-cost"> $ '.$cost.' </td> <td><div class="ct-rem-cont"><button class="ct-remove" id="'.$order_item_id.'">X</button></div></td></tr>';
				$ct_quote_array[] = ['',$subcat,$item_name,$qty,array('text'=>'$ '.$cost, 'alignment'=>'right')];
				if($notes != NULL){
					echo'<tr><td class="ct-item-notes" colspan="3">'.$notes.'</td></tr>';
					$ct_quote_array[] =['','',array('text'=>$notes,'colSpan'=>2,'fontSize'=>9),'',''];
				}
			}	
			echo '</table>';
		}
	}
	echo '<script>var ct_json_menu_array= '.json_encode($ct_quote_array).'; </script>';
}

function ct_service_gen(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'ct_quote_item';
	$event_id = $_REQUEST['post'];
	$initial_data = $wpdb->get_results('SELECT * FROM '.$table_name.' WHERE ct_event_id = "'.$event_id.'" AND ct_cat="service"', OBJECT);
	$ct_service_array = [];
	if($initial_data){
	echo'<table id="ct-quote-services"><h2>Services</h2>';
	$ct_service_array[] = [array('text'=>'Services', 'colSpan'=>2, 'decoration'=>'underline'),'','','',''];
	foreach($initial_data as $initd){
		$qty =$initd->ct_qty;
		$price = get_post_meta($initd->ct_item, 'ct_price',true);
		$cost = sprintf('%0.2f',($price * $qty));
		$quote_item_id = $initd->ct_item;
		$item = get_post($quote_item_id);		
		$item_name = $item->post_title;
		$order_item_id = $initd->id;
		echo'<tr class="ct-add" value='.$price.'><td class="ct-subcat"></td><td class="ct-itemname">'.$item_name.' </td><td class="ct-qty-table"> <input class="ct-quant-changer" id="'.$order_item_id.'" value="'.$qty.'" /></td><td class="ct-cost"> $ '.$cost.' </td> <td><div class="ct-rem-cont"><button class="ct-remove" id="'.$order_item_id.'">X</button></div></td></tr>';
		$ct_service_array[] = ['','',$item_name,$qty,array('text'=>'$ '.$cost, 'alignment'=>'right')];
	}
	echo '</table>';
	}
	echo '<script> var ct_json_serv_array='.json_encode($ct_service_array).'; </script>';
	
}


function ct_course_handler($test=0){
$catloop = get_terms( array('taxonomy'=>'ct_course','parent'=>0,'hierarchical'=>true,'child_of'=>$test,'hide_empty'=>false));
foreach ($catloop as $cata) {
  		echo '<option class="ct-course" value='.$cata->slug.'>'.$cata->name.'</option>';
		
}
}
//Handles AJAX For Quote Select Option Generation

function ct_ajax_cathandle() {
	global $wpdb;
	$slug= $_POST['catslug'];
	$parentterm = get_term_by('slug', "$slug", 'ct_course');
	$parentid = $parentterm->term_id;
	var_dump($parentid);
	$subterms = get_terms(array('taxonomy'=>'ct_course','parent'=>$parentid, 'hide_empty'=>false));
		 if($subterms){
				$output = '<option disabled selected value> -- select an option -- </option>';
			foreach ( $subterms as $subterm ) {
				$output .= '<option value="'.$subterm->slug.'">'.$subterm->name.'</option>'; 
			} 
		echo $output;
		} 
wp_die();
}

function ct_ajax_subcathandle() {
	global $wpdb;
	$slug= $_POST['catslug'];
	wp_reset_query();
	$ct_menu_args = array(
		  'post_type' => 'ct_item',
		  'orderby' => 'title',
		  'order' => 'ASC',
		  'tax_query' => array( array ( 
				'taxonomy' => 'ct_course',
				'field'    => 'slug',
				'terms'    => $slug
			)));
	$loopy = new WP_Query($ct_menu_args);
	$output = '<option disabled selected value> -- select an option -- </option>';
	while ( $loopy->have_posts()) { $loopy->the_post();
		$itemname = get_the_title();
		$id = get_the_ID();
		$price = get_post_meta($id,'ct_price',true);
		$ctClassString = ct_option_classes($id);
		$output .= '<option id="item-'.$id.'"value="'.$price.'">'.$itemname.'</option>';}
	wp_reset_postdata();
	echo $output;
	wp_die();
	} 


add_action('wp_ajax_ct_ajax_subcathandle','ct_ajax_subcathandle');
add_action('wp_ajax_ct_ajax_cathandle','ct_ajax_cathandle');

// Creates Event Details MetaBox 
function ct_event_logistics($post) {
	?>
		<label for="ct_event_status">Status: </label>
		<select name="ct_event_status" id="ct_event_status">
			<option value="Lead" <?php if($post->ct_event_status=='Lead'){echo ' selected ="selected"';} ?> >Lead</option>
			<option value="Booked" <?php if($post->ct_event_status=='Booked'){echo ' selected ="selected"';} ?>>Booked</option>
			<option value="Cancelled/Not Booked" <?php if($post->ct_event_status=='Cancelled/Not Booked'){echo ' selected ="selected"';} ?>>Cancelled/Not Booked</option>
			<option value="Completed"  <?php if($post->ct_event_status=='Completed'){echo ' selected ="selected"';} ?>>Completed</option>
		</select>
		</br>
		<label for="ct_event_date" >Date: </label>
			<input type="text" name="ct_event_date" id="ct_event_date" value="<?php echo $post->ct_event_date; ?>"/>
		<br />
		<label for="ct_event_time" >Start Time: </label>
			<input type="text" name="ct_event_time" id="ct_event_time_s" value="<?php echo $post->ct_event_time; ?>"/>
		<br />		
		<label for="ct_event_end_time" >End Time: </label>
			<input type="text" name="ct_event_end_time" id="ct_event_time_e" value="<?php echo $post->ct_event_end_time; ?>"/>
		<br />
		<label for="ct_event_type" >Type: </label>
			<input type="text" name="ct_event_type" id="ct_event_type" value="<?php echo $post->ct_event_type; ?>" />
		<br />
		<label for="ct_event_venue" >Venue: </label>
			<input type="text" name="ct_event_venue" id="ct_event_venue" value="<?php echo $post->ct_event_venue; ?>" />
		<br />
		<label for="ct_event_guests" ># of Guests: </label>
			<input name="ct_event_guests" id="ct_event_guests" value="<?php echo $post->ct_event_guests; ?>" />
	<?php
		
}
// Creates Contact Details MetaBox On Event CPT Page

function ct_event_contact_details($post) {
	?>
		<label for="ct_event_contact">Contact Name: </label>
			<input type="text" name="ct_event_contact" id="ct_event_contact" value="<?php echo $post->ct_event_contact; ?>"/>
		<br />
		<label for="ct_event_email">Email: </label>
			<input type="text" name="ct_event_email" id="ct_event_email" value="<?php echo $post->ct_event_email; ?>"/>
		<br />
		<label for="ct_event_phone_home" >Home Phone: </label>
			<input type="text" name="ct_event_phone_home" id="ct_event_phone_home" value="<?php echo $post->ct_event_phone_home; ?>"/>
		<br />
		<label for="ct_event_phone_mob" >Mobile Phone: </label>
			<input type="text" name="ct_event_phone_mob" id="ct_event_phone_mob" value="<?php echo $post->ct_event_phone_mob; ?>"/>
		<br />
		<label for="ct_event_address" >Address: </label>
			<input type="text" name="ct_event_address" id="ct_event_address" value="<?php echo $post->ct_event_address; ?>"/>
	<?php
}
//Creates Notes MetaBox On Events CPT Page

function ct_notes_page($post){
	?>
	<p>Office Notes: </p>
		<textarea style="width:100%" name="ct_office_notes" id="ct_office_notes_data"><?php echo $post->ct_event_office_notes; ?></textarea>
	<p>Kitchen Notes: </p>
		<textarea style="width:100%" name="ct_kitchen_notes" id="ct_kitchen_notes_data"><?php echo $post->ct_event_kitchen_notes; ?></textarea>
	<p>Customer Notes: </p>
		<textarea style="width:100%" name="ct_customer_notes" id="ct_customer_notes_data"><?php echo $post->ct_event_customer_notes; ?></textarea>
	<p>Equipment List: </p>
		<textarea style="width:100%" name="ct_equipment_list" id="ct_equipment_list_data"><?php echo $post->ct_equipment_list; ?></textarea>
	<?php
}

//Creates Financials MetaBox On Events CPT Page

function ct_financials_page($post){
	?>
	<label for="ct_event_tax">Tax: </label>
		<input type="number" name="ct_event_tax" step=".1" value="<?php echo $post->ct_event_tax; ?>"/>
	<br />
	<label for="ct_event_gratuity">Gratuity: </label>
		<input type="number" name="ct_event_gratuity" step=".1" value="<?php echo $post->ct_event_gratuity; ?>"/>
	<br />
	<label for="ct_event_miscfees">Misc Fees: </label>
		<input type="number" name="ct_event_miscfees" step=".01" value="<?php echo $post->ct_event_miscfees; ?>"/>
	<br />
<?php
}

//Creates Services MetaBox On Events CPT

function ct_service_page($post){
	$ct_event_id = $_REQUEST['post'];
	?>
		<label for="ct_event_service">Service: </label>
			<select id="ct_event_service" name="ct_event_service">
				<?php ct_service_dropdown() ?>
			</select>
			<input type="number" id="ct_service_hours" name="ct_service_hours" value="<?php echo $post->ct_event_service_hours; ?>">
			<button id="service_add"  name="service_add"<?php echo ' value="'.$ct_event_id.'"'; ?>>Add</button>
	<?php
}

//Generates Totals Section Of Quote

function ct_gen_total($post) {
	$miscfees=sprintf('%0.2f',($post->ct_event_miscfees));
	$gratuity=$post->ct_event_gratuity;
$id = $_REQUEST['post'];
	$subtotal= sprintf('%0.2f',(get_cost($id) + $miscfees));
	$gratuity_total= sprintf('%0.2f',(round(($gratuity*.01)* $subtotal,2)));
	$tax = $post->ct_event_tax;
	$tax_total= sprintf('%0.2f',(round(($tax*.01) * $subtotal, 2)));
	$total = sprintf('%0.2f',($subtotal + $gratuity_total + $tax_total));
	$ct_totals_array = [];
	echo '<table id="ct-quote-totals">';
	if($miscfees != 0) { 
		echo '<tr class="ct-add"><td class="ct-subcat">Misc Fees</td><td class="ct-itemname"></td><td class="ct-qty-table"></td><td class="ct-cost"> $ '.$miscfees.' </td> <td></td></tr>'; 
		$ct_totals_array[] = ['','Misc Fees','','',array('text'=>'$ '.$miscfees, 'alignment'=>'right')];
	}
	echo '<tr class="ct-add"><td class="ct-subcat">Subtotal</td><td class="ct-itemname"></td><td class="ct-qty-table"></td><td class="ct-cost"> $ '.$subtotal.' </td> <td></td></tr>';
	$ct_totals_array[] = ['','Subtotal','','',array('text'=>'$ '.$subtotal, 'alignment'=>'right')];
	if($gratuity != 0) {	
		echo '<tr class="ct-add"><td class="ct-subcat">Gratuity</td><td class="ct-itemname">'.$gratuity.' % </td><td class="ct-qty-table"></td><td class="ct-cost"> $ '.$gratuity_total.' </td> <td></td></tr>';
		$ct_totals_array[] = ['','Gratuity','','',array('text'=>'$ '.$gratuity_total, 'alignment'=>'right')];
	}
	if($tax != 0) {	
		echo '<tr class="ct-add"><td class="ct-subcat">Tax</td><td class="ct-itemname">'.$tax.' % </td><td class="ct-qty-table"></td><td class="ct-cost"> $ '.$tax_total.' </td> <td></td></tr>';
		$ct_totals_array[] = ['','Tax','','',array('text'=>'$ '.$tax_total, 'alignment'=>'right')];
	}
	echo '<tr class="ct-add"><td class="ct-subcat">Total</td><td class="ct-itemname"></td><td class="ct-qty-table"></td><td class="ct-cost"> $ '.$total.' </td> <td></td></tr>';
	$ct_totals_array[] = ['','Total','','',array('text'=>'$ '.$total, 'alignment'=>'right')];
	echo '</table>';
	echo '<script> var ct_json_totals_array ='.json_encode($ct_totals_array).';</script>';
}

//Generates Cost Of Quote From Database Order Items

function get_cost($id){
	global $wpdb;
	if($id==NULL) {$id = 0;}
	$table_name = $wpdb->prefix . 'ct_quote_item';
	$order_items=$wpdb->get_results('SELECT * FROM '.$table_name.' WHERE ct_event_id ='.$id, OBJECT);
	$total = 0;
	foreach($order_items as $item){
		$item_id = $item->ct_item;
		$price = get_post_meta($item_id,'ct_price',true);
		$qty = $item->ct_qty;
		$cost = $price*$qty;
		$total += $cost;
	}
	return $total;
}

//Adds Link To Courses Taxonomy Page From Item Page LIKELY TEMP

function ct_cat_page(){
	?>
		<a href="http://samanthascatering.com/wp-admin/edit-tags.php?taxonomy=ct_course">Click Here To Manage Menu Item Categories And Subcategories (TEMP)</a>
	<?php
}

//Adds Terms And Conditions MetaBox to Event CPT
function ct_termsandcond($post){
	?>
	<p>Terms & Conditions </p>
		<textarea style="width:100%" name="ct_event_terms" id="ct_event_terms_data"><?php $ct_terms = $post->ct_event_terms; if($ct_terms =='' ){$ct_terms = "​BOOKING
$500 DEPOSIT
50% DUE 90 DAYS PRIOR TO EVENT
100% DUE 30 DAYS PRIOR TO EVENT

CANCELLATION 
91 DAYS PRIOR TO EVENT DATE: $500 NON-REFUNDABLE DEPOSIT
31-90 DAYS PRIOR TO EVENT DATE: ​25​% OF TOTAL
1-30 DAYS PRIOR TO EVENT: 100% OF TOTAL

THE FINE PRINT
Cancellation policies will still apply if event is not paid and cancelled within the penalty period. It is agreed that Patron will pay a deposit in the amount of $500 in order to guarantee the event date.  Until this deposit is paid, your event date is not guaranteed. The deposit is non-refundable but will be applied to the final bill. 50% of the approximate total, tentative headcount, and menu changes due 90 days prior to the event date. Payment in full and final headcount due ​3​0 days prior to the event date. Should the affair be held in a facility with or without a liquor license then all security and/or liquor shall in no way involve the caterer and the patron will be responsible for all such aspects of the event. Patron agrees to begin function promptly at the scheduled time and to release staff at the ending hour indicated. Staff and servers may agree to stay for additional time and Patron will be billed after the event for additional service hours. Patron assumes responsibility for any and all damages to venue property or caterer’s property caused by any guest, invitee, or other person attending function. In the event that the patron has rented or borrowed equipment from the caterer, it must be returned undamaged within 48 hours unless otherwise arranged. Damaged or lost equipment will be billed to the patron. In the event of breach of contract by Patron, the Caterer may keep deposit and patron shall be obliged to reimburse Caterer for any damage costs incurred by reason of breach thereof, including, but not limited to, lost profits, the cost of any supplies purchased in anticipation of the event and for the contract price of the event. "; }echo $ct_terms;?></textarea>
	<?php
}

//Creates Select Options For Services From Service Custom Post Type
function ct_service_dropdown(){	
	global $wpdb;
	wp_reset_query();
	$ct_menu_args = array(
		  'post_type' => 'ct_service',
		  'orderby' => 'title',
		  'order' => 'ASC',
			);
	$loopy = new WP_Query($ct_menu_args);
		$output = '<option disabled selected value> -- select an option -- </option>';
	while ( $loopy->have_posts()) { $loopy->the_post();
		$itemname = get_the_title();
		$id = get_the_ID();
		$price = get_post_meta($id,'ct_price',true);
		$ctClassString = ct_option_classes($id);
		$output .= '<option id="service-'.$id.'"value="'.$price.'">'.$itemname.'</option>';}
	wp_reset_postdata();
	echo $output;
}
//Handles MetaBox For Schedule Items
function ct_event_scheduler(){
	$ct_event_id = $_REQUEST['post'];
?>
	<div id="ct-sched">
		<input id="ct_sched_item"/>
		<input id="ct_sched_time" />
		<button id="schedule_add" name="schedule_add" <?php echo 'value="'.$ct_event_id.'"'; ?>> Add </button>
	</div>
	<?php
}
function ctgoogtest(){
	global $ct_quote_array;
	?>
    <!--Add buttons to initiate auth sequence and sign out-->
    <button id="authorize-button" type="button" style="display: none;">Authorize</button>
    <button id="signout-button" type="button" style="display: none;">Sign Out</button>
	<button id="ct-goog-update" type="button" style="display: none;">Update Calendar</button>

    <pre id="content"></pre>

    <script type="text/javascript">
      // Client ID and API key from the Developer Console
      var CLIENT_ID = '965833391296-3uq1vushm05i84s4s2r2ho50h6ejom0a.apps.googleusercontent.com';

      // Array of API discovery doc URLs for APIs used by the quickstart
      var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];

      // Authorization scopes required by the API; multiple scopes can be
      // included, separated by spaces.
      var SCOPES = "https://www.googleapis.com/auth/calendar";

      var authorizeButton = document.getElementById('authorize-button');
      var signoutButton = document.getElementById('signout-button');
	  var ctButton = document.getElementById('ct-goog-update');
      /**
       *  On load, called to load the auth2 library and API client library.
       */
      function handleClientLoad() {
        gapi.load('client:auth2', initClient);
      }

      /**
       *  Initializes the API client library and sets up sign-in state
       *  listeners.
       */
      function initClient() {
        gapi.client.init({
          discoveryDocs: DISCOVERY_DOCS,
          clientId: CLIENT_ID,
          scope: SCOPES
        }).then(function () {
          // Listen for sign-in state changes.
          gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);

          // Handle the initial sign-in state.
          updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
          authorizeButton.onclick = handleAuthClick;
          signoutButton.onclick = handleSignoutClick;
        });
      }

      /**
       *  Called when the signed in status changes, to update the UI
       *  appropriately. After a sign-in, the API is called.
       */
      function updateSigninStatus(isSignedIn) {
        if (isSignedIn) {
          authorizeButton.style.display = 'none';
          signoutButton.style.display = 'block';
		  ctButton.style.display = 'block';
		  //listUpcomingEvents();
		  ct_create_google_event();
        } else {
          authorizeButton.style.display = 'block';
          signoutButton.style.display = 'none';
        }
      }

      /**
       *  Sign in the user upon button click.
       */
      function handleAuthClick(event) {
        gapi.auth2.getAuthInstance().signIn();
      }

      /**
       *  Sign out the user upon button click.
       */
      function handleSignoutClick(event) {
        gapi.auth2.getAuthInstance().signOut();
      }

      /**
       * Append a pre element to the body containing the given message
       * as its text node. Used to display the results of the API call.
       *
       * @param {string} message Text to be placed in pre element.
       */
      function appendPre(message) {
        var pre = document.getElementById('content');
        var textContent = document.createTextNode(message + '\n');
        pre.appendChild(textContent);
      }
 /**
       * Print the summary and start datetime/date of the next ten events in
       * the authorized user's calendar. If no events are found an
       * appropriate message is printed.
       */

	  function ct_create_google_event() {
var title = jQuery('#title').val();
var venue = jQuery('#ct_event_venue').val();
var date = jQuery('#ct_event_date').val();
var startTime = jQuery('#ct_event_time_s').val();
var endTime = jQuery('#ct_event_time_e').val();
var startDateTime = new Date(date+" "+startTime);
var endDateTime = new Date(date+" "+endTime);
var event = {
  'summary': title,
  'location': venue,
  'description': 'testing',
  'start': {
    'dateTime': startDateTime.toISOString(),
    'timeZone': 'America/New_York'
  },
  'end': {
    'dateTime': endDateTime.toISOString(),
    'timeZone': 'America/New_York'
  }
};
ctButton.onclick = function(){
var request = gapi.client.calendar.events.insert({
  'calendarId': '0ggu7fu19vq2ubf97nto0t4f3g@group.calendar.google.com',
  'resource': event
});
request.execute(function(event) {
  appendPre('Event created: ' + event.htmlLink);
  console.log(event.id);
});
	  }}
</script>
    <script async defer src="https://apis.google.com/js/api.js"
      onload="this.onload=function(){};handleClientLoad()"
      onreadystatechange="if (this.readyState === 'complete') this.onload()">
    </script>
	<?php
}
?>