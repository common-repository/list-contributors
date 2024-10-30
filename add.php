<?php
	
	include('../../../wp-config.php' ); 
	
	extract($_POST);
	
	global $wpdb;
	
	$table_name = $wpdb->prefix . "contributors";
	
	$fldtextArea_name = str_replace("<br>", "\n", $fldtextArea_name);  
	
	$name = mysql_real_escape_string($_POST['username']);
	$nicename = mysql_real_escape_string($_POST['displayname']);
	$description = mysql_real_escape_string($_POST['description']);
	$description = str_replace("<br>", "\n", $description);
	$image = mysql_real_escape_string($_POST['upload_image']);
	
	$wpdb->query("INSERT INTO $table_name (id,name,nicename,description,image) VALUES ('NULL', '$name', '$displayname', '$description', '$image')"); 
	
	header( 'Location: '.get_option('siteurl').'/wp-admin/options-general.php?page=list_contributors_settings' ) ;
