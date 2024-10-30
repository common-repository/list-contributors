<?php
	
	include('../../../wp-config.php' ); 
	
	extract($_POST);
	
	global $wpdb;
	
	$table_name = $wpdb->prefix . "contributors";
	
	$fldtextArea_name = str_replace("<br>", "\n", $fldtextArea_name);  
	
	$id = mysql_real_escape_string($_POST['update_id']);
	$description = mysql_real_escape_string($_POST['update_description']);
	$description = str_replace("<br>", "\n", $description);
	$image = mysql_real_escape_string($_POST['update_upload_image']);
	
	$wpdb->query("UPDATE $table_name SET description='$description' WHERE ID='$id'"); 
	
	header( 'Location: '.get_option('siteurl').'/wp-admin/options-general.php?page=list_contributors_settings' ) ;
