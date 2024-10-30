<?php
include('../../../wp-config.php' ); 
global $wpdb;

extract($_POST);
$table_name = $wpdb->prefix . "contributors";

if(isset($_POST['delete'])){

 foreach ($delete as $deleted) {
 
	$remove  = "DELETE FROM $table_name WHERE ID='$deleted'";
    mysql_query($remove) or die(mysql_error());

  }

}

header( 'Location: '.get_option('siteurl').'/wp-admin/options-general.php?page=list_contributors_settings' ) ;


?>