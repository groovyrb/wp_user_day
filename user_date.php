<?php 
/*
Plugin Name: User date picker
Plugin URI: https://pluginurl.com/
Description: Used for displaying current date on each post 
Author: Rahul Rajoira
*/


/* Checking function exit or not */
if(!function_exists(user_date_menu)){
	die();
}


add_action( 'admin_menu', 'user_date_menu' );

/* Register Admin Menu */
function user_date_menu() {
	add_options_page( 'Date Picker', 'User date', 'manage_options', 'user_date_picker', 'user_date_status' );
}


/* Checking user permission for page access*/
function user_date_status() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}


if(!empty($_POST)){
/* Here We will sava the user date message and status */
$data =array("message"=>$_POST['message'],"status"=>$_POST['status'],"post_option"=>$_POST['post_option']);
update_option( 'user_message',$data); 


}

$data = get_option( 'user_message' ); 
	?>

	<div class="container">
  <h2>User Date Setting </h2>
  <form method='POST' name='user_date'>
    <div class="form-group">
      <label for="message">Message</label>
      <input type="text" class="form-control" name='message' id="message" placeholder="Please enter display message" value='<?php echo $data['message']  ?>' required>
    </div>
    <div class="checkbox">
    	<label>
    <input type="checkbox" name='status' data-toggle="toggle" <?php if($data['status']=='on'){ echo 'checked'; } else{} ; ?> />
    Message Display Option </label>
    </div>
    <div class="checkbox">
    	 <label>
    <input type="checkbox" name='post_option' data-toggle="toggle" <?php if($data['post_option']=='on'){ echo 'checked'; } else{} ; ?> />
    All single Post Display Option <?php echo $data['post_option'] ; ?>
  		</label>
   
    </div>
    <input type="submit" class="btn btn-default" name='submit' value='Submit '>
  </form>
</div>

	<?php }


// add script to the footer and break out of PHP
function display_option($string){ 
	$data = get_option( 'user_message' ); 
	$my_custom_text = "<p><span style='color:green' id='message'>  ".$data['message'] ;
	$status = $data['status'];
	
	if($status=='on'){
	return $my_custom_text ." " . date ('l') . "<span></p>";
	}else{ 
		if ( is_user_logged_in() ) {
        $my_custom_text = "<p><span style='color:red' id='message'>Message display option is Inactive in plugin setting</span></p>";
          } else {
       
    	}

	
	return $my_custom_text ; }
}
/* Creting a short code for display only day */
function display_day($string){ 
	 $day = "<span style='color:red' id='message'> " .date ('l'). " </span>" ;
	return $day ; 
}


add_action('wp_footer','add_bootstrap_footer');

function add_bootstrap_footer (){

wp_register_style('bootstraping', plugins_url('css/bootstrap.min.css',__FILE__ ),false, '1.0.0', false);
wp_enqueue_style('bootstraping');

}

/* register css */
function bootstraping() {

wp_register_style('bootstraping', plugins_url('css/bootstrap.min.css',__FILE__ ),false, '1.0.0', false);
wp_enqueue_style('bootstraping');
//wp_register_script( 'bootstraping', plugins_url('js/script.js',__FILE__ ),false, '1.0.0', false);
wp_register_script( 'bootstraping', plugins_url('js/bootstrap-toggle.min.js',__FILE__ ),false, '1.0.0', false);
wp_enqueue_script('bootstraping');

}

add_action( 'admin_init','bootstraping');
/* creating short code for our function */
add_shortcode( 'user_date', 'display_option' );

add_shortcode( 'user_day', 'display_day' );

/* adding content to each post */
add_filter('the_content','add_code_post');

function add_code_post($content) {

$data = get_option( 'user_message' ); 
$my_custom_text = "<p><span style='color:green' id='message'>  ".$data['message'];
$option = $data['post_option'];
$status = $data['status'];
	/* Checking  for all post visibility */
	if($option=='on' && $status=='on'){
	return $content ." " . $my_custom_text." " . date ('l') . "<span></p>";;
	}else{ 
	
	return $content ; }


if(is_single() && !is_home()) {
$content .= $my_custom_text;
}
return $content;
}

?>