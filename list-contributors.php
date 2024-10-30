<?php

/*
Plugin Name: List Contributors
Plugin URI: http://www.outtolunchproductions.com/list-contributors
Description: Display your contributing authors in style, complete with images and hideable descriptions.
Author: Carter Fort
Version: 1.0
Author URI: http://www.outtolunchproductions.com
*/
if (!defined('ABSPATH')) {
	exit("Sorry, you are not allowed to access this page directly.");
}

/* Set constant for plugin directory */
define( 'LC_URL', WP_PLUGIN_URL.'/list-contributors' );

$list_contributors_db_version = "1.0";

function list_contributors_install () {
   global $wpdb;
   global $slist_contributors_db_version;

   $table_name = $wpdb->prefix . "contributors";
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      
    $sql = "CREATE TABLE " . $table_name . " (
		ID int(11) NOT NULL auto_increment,
		name tinytext NOT NULL,
		nicename tinytext NOT NULL,
		description longtext NOT NULL,
		image mediumtext NOT NULL,
		UNIQUE KEY id (ID)
	);";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
 
      add_option("list_contributors_db_version", $list_contributors_db_version);

   }
}

register_activation_hook(__FILE__,'list_contributors_install');

function list_contributors_header_scripts(){
	if (get_option('lc_load_jquery')== 'yes'){
	echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>';
	}
	echo '	
	<script type="text/javascript" src="'.LC_URL.'/listing.jquery.js"></script>';
}

add_action("wp_head","list_contributors_header_scripts");

add_option('lc_load_jquery', 'yes');

function list_contributors_handler(){
?>
<div id="contributors">
	<?php global $wpdb;
	
	$table_name = $wpdb->prefix . "contributors";
	
		$query=mysql_query('SELECT ID, name, nicename, description, image FROM '.$table_name.' ORDER BY ID');
		
		$checker=mysql_query('SELECT ID FROM '.$table_name.' ORDER BY ID');
		
		if (mysql_fetch_array($checker) == 0){ 
		echo '<p>You haven\'t got any contributors yet. Why don\'t you <a href="'.get_option('siteurl').'/wp-admin/options-general.php?page=list_contributors_settings">add some?</a></p>'; } else {
	

						while($result = mysql_fetch_array($query)) { ?>
						<div class="contributor">
							<h3><a class="readbio contributor-name" href="#"><?php echo $result['name']; ?></a></h3>
							<div class="bio">
							<p>
							<?php if (!empty($result['image'])) { ?>
							<img class="alignleft" src="<?php echo $result['image']; ?>" alt="Author image for <?php echo $result['name']; ?>" /><?php } ?>
							
							
							<?php $bio = $result['description'];
							echo nl2br(stripslashes($bio));
							 ?>
							 </p>
								<p><a href="#" class="hidebio contributor-hide">Hide this bio</a></p>
								
							</div>
					<?php $authornicename = $result['nicename'];
					query_posts('author_name='.$authornicename.''); if (have_posts()) : while (have_posts()) : the_post(); ?>
						<p><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a> on <?php the_time('l, F jS, Y') ?></span></p>
					<?php endwhile; endif; ?>
						</div>
						<?php } }	?>
</div>

<?php
	} 


add_shortcode('list-contributors', 'list_contributors_handler');
add_action('admin_menu', 'list_contributors_plugin_menu',20);

add_action('admin_head', 'contributor_admin_scripts');

function contributor_admin_scripts(){?>
<style type="text/css">
img.current-image
{
	background: white;
	border: 1px solid grey;
	margin: 10px 30px;
	padding: 5px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
}

.peoples
{
	margin-bottom: 10px;
}

#intro
{
	float: left;
	width: auto;
}

#load_jquery
{
	background:none repeat scroll 0 0 lightgrey;
	border:1px solid darkgray;
	float:right;
	margin:5px;
	padding:5px 20px;
	width:300px;
}

#load_jquery h2
{
	float: left;
	width: auto;
	margin-top: -15px;
}

#load_jquery small
{
	float:right;
	font-style:italic;
	line-height:36px;
}

#credit
{
	background: #141414;
	border:1px solid #FFFFFF;
	color: #bfbfbf;
	float: right;
	font-size: 12px;
	margin-bottom: 10px;
	padding: 10px;
	text-align: center;
	width: 175px;
}

#credit a
{
	color: #fff;
	font-weight: bold;
}

#deleter
{
	float: left;
	width: auto;
}
.clr
{
	clear: both;
}
</style>
    <script type="text/javascript">
	jQuery(function(){
		jQuery('#authorname').change(function(){
			var t = jQuery('this');
			jQuery('#displayname').val(jQuery('#authorname').val());
			jQuery('#username').val(jQuery('#authorname :selected').text());
		});
		jQuery('#delete-button').click(function(){
		
			var x=window.confirm("Are you sure you want to delete these contributors? This cannot be undone.")
			if (x){
				jQuery('#deleter').submit();
			}
		return false;

		});
	jQuery('.instructions').click(function(){
		jQuery('#guide').slideToggle('600');
	}).css("cursor","pointer").css("font-style","italic");
	
	jQuery('.edit-info').click(function(){
		jQuery(this).next('div.information').slideToggle('600').siblings('div.information').slideUp('600');
	}).css("cursor","pointer");
	jQuery('.information').hide();
	
	jQuery('#load_jquery h2').click(function(){
		jQuery(this).next().next('form').slideToggle('600');
	}).css("cursor", "pointer");
	jQuery("#load_jquery form").hide();
	});
	
	
</script>
<?php }

function list_contributors_admin_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_register_script('my-upload', LC_URL.'/uploader.js', array('jquery','media-upload','thickbox'));
wp_enqueue_script('my-upload');
}

function list_contributors_styles() {
wp_enqueue_style('thickbox');
}

if (isset($_GET['page']) && $_GET['page'] == 'list_contributors_settings') {

add_action('admin_print_scripts', 'list_contributors_admin_scripts');
add_action('admin_print_styles', 'list_contributors_styles');

}

function list_contributors_plugin_menu() {
    // Add a new submenu under Options:
    add_options_page('List Contributors', 'List Contributors', 5, 'list_contributors_settings', 'list_contributors_options_page');


}

function list_contributors_options_page(){
	
	 echo '<div class="wrap">';
	 
	 $lc_load_jquery = 'lc_load_jquery';
	 $lc_load_jquery_hidden_field_name = 'lc_load_jquery_submit_hidden';
	 
	  $lc_load_jquery_val = get_option($lc_load_jquery);

     if( $_POST[ $lc_load_jquery_hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $lc_load_jquery_val  = $_POST[$lc_load_jquery];

        // Save the posted value in the database
        update_option( $lc_load_jquery, $lc_load_jquery_val );

        // Put an options updated message on the screen
}
   

    // header

    echo "<div id='intro'><h2>List Contributors</h2><em>A WordPress plugin for giving credit where credit is due.</em><br /><small>by <a href='http://www.outtolunchproductions.com' target='_blank'>Out to Lunch Productions.</a></small></div> ";?>
    <div id="load_jquery">
    <h2>Loading jQuery</h2>
    <small>Advanced users only</small>
    <form class="clr" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

	    <p>This plugin loads the jQuery library from the Google CDN. If you're already loading jQuery, you might not want to load it twice. Turn it off here.</p>
			    	<label><input type="radio"<?php if(get_option($lc_load_jquery) == 'yes') { ?> checked="checked"<?php } ?> name="<?php echo $lc_load_jquery; ?>" value="yes" /> : Yes, load jQuery.</label><br/>
			<label><input type="radio"<?php if(get_option($lc_load_jquery) == 'no') { ?> checked="checked"<?php } ?> name="<?php echo $lc_load_jquery; ?>" value="no" /> : No, I'm having conflict issues. </label><br/>
	    <p class="submit">
	    <input type="submit" class="button-primary" value="<?php _e('Save Option', 'lc_trasn_domain' ) ?>" />
	 
	    <input type="hidden" name="<?php echo $lc_load_jquery_hidden_field_name; ?>" value="Y">

    </form>
    </div>
	<div class="clr"></div>
    <h2>Add an Author to your Contributors</h2>
    <span class="instructions"><small>Click here for instructions</small></span>
    <div id="guide" style="display:none;">
    	<h4>Step 1</h4>
    	<p><a href="<?php bloginfo('url'); ?>/wp-admin/user-new.php">Add a new author</a> in the traditional WordPress manner.</p>
    	<h4>Step 2</h4>
    	<p>Come back to this page. Click on the Select an Author dropdown menu and find the author you just added.<br/><small>HINT: The contributor's name will appear on your site exactly as it does in the Display Name dropdown menu on the edit user page.</small></p>
    	<h4>Step 3</h4>
    	<p>Add a description. HTML tags are okay, but you don't have to use them if you don't want to.</p>
    	<h4>Step 4</h4>
    	<p>If you have an author image, enter the URL or upload it. Once you've uploaded the image, scroll down to the "Insert Image into Post" button. This is an optional step; if they don't want an image, you don't have to upload one.</p>
    	<h4>Step 5</h4>
    	<p>That's it. Click Add This Contributor. Their name, description and posts will appear on the Team page.</p>
    	<h3>Editing Contributors</h3>
    	<p>Make changes to the appropriate fields and click "Update."</p>
    	<h3>Removing Contributors</h3>
    	<p>Select the box under their name and click Delete Selected People. <span style="color:red;font-style:italic;">This cannot be undone.</span></p>
    	<p class="instructions">Hide these instructions.</p>
    </div>
    <form method="post" action="<?php echo LC_URL; ?>/add.php">
    	<fieldset>
    	<h3>Select Author Name</h3>
    		<select id="authorname" name="authorname">
    			<option value="">Select an Author</option>
    			<?php global $wpdb;
				$table_name = $wpdb->prefix . "users";
				$query=mysql_query('SELECT display_name, user_nicename FROM '.$table_name.' ORDER BY display_name');
    			while($result = mysql_fetch_array($query)) { ?>
    			<option value="<?php echo $result['user_nicename']; ?>"><?php echo $result['display_name']; ?>
    			<?php } ?>
    			
    		</select>
    		<input type="hidden" name="displayname" id="displayname" />
    		<input type="hidden" name="username" id="username" />
    <h3>Enter Description</h3>
    	<textarea id="description" name="description" cols="80" rows="10"></textarea>
    <h3>Upload Image</h3>
    	<input id="upload_image" type="text" size="36" name="upload_image" value="" />
		<input id="upload_image_button" type="button" value="Upload Image" />
		<br />Enter a URL or upload an image for this contributor.
		
		<p class="submit"> 
	<input type="submit" name="submit" class="button-primary" value="Add This Contributor" /> 
</p> 
    	</fieldset>
    </form>
    <h2>Edit Contributor Info</h2>
    <small>Click to expand</small>
    <?php global $wpdb;

				$table_name = $wpdb->prefix . "contributors";
				$query=mysql_query('SELECT ID, name, description, image FROM '.$table_name.'');
    			while($result = mysql_fetch_array($query)) { ?>
    			<form class="peoples" method="post" action="<?php echo LC_URL; ?>/update.php">
    				<strong class="edit-info"><?php echo $result['name']; ?></strong>
    				<div class="information">
    				<input type="hidden" name="update_id" value="<?php echo $result['ID']; ?>" />
    				<textarea name="update_description" cols="80" rows="10"><?php echo stripslashes($result['description']); ?></textarea><br />
    				<?php if (!empty($result['image'])) { ?>
							<img class="current-image" src="<?php echo $result['image']; ?>" alt="Author image for <?php echo $result['name']; ?>" /><?php } ?>
 <br />
    				Image URL: <input type="text" size="100" name="update_upload_image" value="<?php echo $result['image']; ?>" />
    				<br/>
    				<p>If you need to upload an image for this person, use the <a href="<?php bloginfo('url'); ?>/wp-admin/media-new.php">Media Uploader</a> then copy and paste the URL of the image in this field.</p>
    				<p class="submit"> 
						<input type="submit" name="submit" class="button-primary" value="Update" /> 
					</p> 
					</div>
    			</form>
    			<?php } ?>
    <h2>Remove Contributors</h2>
    <?php
    $table_name = $wpdb->prefix . "contributors";
    
    $checker=mysql_query('SELECT ID FROM '.$table_name.' ORDER BY ID');
		
		if (mysql_fetch_array($checker) == 0){ 
		echo '<p>You haven\'t got any contributors yet. Why don\'t you add some?</p>'; } else { ?>
    <form id="deleter" method="post" action="<?php echo LC_URL; ?>/delete.php">
    <ul>
        	<?php global $wpdb;

				$table_name = $wpdb->prefix . "contributors";
				$query=mysql_query('SELECT ID, name FROM '.$table_name.'');
    			while($result = mysql_fetch_array($query)) { ?>
    			<li><?php echo $result['name']; ?> - <small style="color:red">Delete this Person: <input type="checkbox" name="delete[]" value="<?php echo $result['ID'] ?>" /></small></li>
    			<?php } ?>
    </ul>
    <input type="submit" class="button" style="color:red" id="delete-button" value="Delete Selected People" />
    </form>
    
    <div id="credit"><img src="<?php echo LC_URL; ?>/otl-footer.png" height="78" width="100" /> <br />This plugin was developed by <a href="http://www.outtolunchproductions.com" target='_blank'>Out to Lunch Productions</a>. Silly name. <em>Serious websites.</em></div>
    
    <?php
    }
    
    echo '</div>';
}
?>