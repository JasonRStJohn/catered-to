<?php
//installs custom database table registered in ct_main.php
function ct_install() {
	global $wpdb;
	$collate = '';
	if ( $wpdb->has_cap( 'collation' ) ) {
		$collate = $wpdb->get_charset_collate();
	}
	$table_quote = $wpdb->prefix . 'ct_quote_item';
	$table_sched = $wpdb->prefix.'ct_sched_item';
	$sql= "
	CREATE TABLE $table_quote (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	ct_item INT(7) NOT NULL,
	ct_event_id INT(7) NOT NULL,
	ct_cat varchar(30) NOT NULL,
	ct_subcat varchar(30) NOT NULL,
	ct_qty INT(6) NOT NULL,
	ct_notes varchar(90),
	PRIMARY KEY  (id),
	UNIQUE KEY id (id)
	) $collate;
	CREATE TABLE $table_sched (
	id bigint(20) NOT NULL AUTO_INCREMENT,
	ct_event_id INT(7) NOT NULL,
	ct_sched_title varchar(30) NOT NULL,
	ct_sched_time varchar(10) NOT NULL,
	PRIMARY KEY  (id),
	UNIQUE KEY id (id)
	) $collate;" ;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta( $sql );
}

function ct_quote_item_alter(){
	global $wpdb;
	$ct_type = $_POST['ct_action_type'];
	$table_name = $wpdb->prefix . 'ct_quote_item';
	switch($ct_type){
		case "add":
			$event_id = get_the_ID();
			$data = [
				'ct_item'=>$_POST["item_id"],
				'ct_event_id'=>$_POST["event_id"],
				'ct_cat'=>$_POST["item_cat"],
				'ct_subcat'=>$_POST["item_subcat"],
				'ct_qty'=>$_POST["item_qty"],
				'ct_notes'=>$_POST["item_notes"],
			];
			$wpdb->insert($table_name,$data,array(
				'%s',
				'%d',
				'%s',
				'%s',
				'%d',
				'%s',
			));
			$output = $wpdb->insert_id;
			break;
		case "change":
			$ct_qty= intval($_POST['ct_qty']);
			$item_id = intval($_POST['ct_id']);
			$wpdb->update($table_name,array('ct_qty'=>$ct_qty),array('ID'=>$item_id),array('%d'), array('%d'));
			break;
		case "delete":
			$item_id = $_POST['ct_id'];
			$wpdb->delete($table_name, array('ID'=>$item_id));
			break;
		echo $output;
		wp_die();
}}
add_action('wp_ajax_ct_quote_item_alter','ct_quote_item_alter');

function ct_schedule_item_alter(){
	global $wpdb;
	$ct_type = $_POST['ct_action_type'];
	$table_name = $wpdb->prefix . 'ct_sched_item';
	switch($ct_type){
		case "add":
			$event_id = get_the_ID();
			$data = [
				'ct_sched_title'=>$_POST["sched_item"],
				'ct_event_id'=>$_POST["event_id"],
				'ct_sched_time'=>$_POST["sched_time"],
			];
			$ctdb = $wpdb->insert($table_name,$data,array(
				'%s',
				'%d',
				'%s',
			));
			PC::debug($ctdb);
			PC::debug($data);
			$output = $wpdb->insert_id;
			break;
		case "change":
			break;
		case "delete":
			$item_id = $_POST['ct_id'];
			$wpdb->delete($table_name, array('ID'=>$item_id));
			break;
		echo $output;
		wp_die();
}}
add_action('wp_ajax_ct_schedule_item_alter','ct_schedule_item_alter');
?>