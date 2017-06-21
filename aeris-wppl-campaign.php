<?php

/*

Plugin Name: Aeris-Campaign
GitHub Plugin URI: https://github.com/aeris-data/aeris-wordpress-campaign-plugin
Version: 0.0.5

*/


add_action('init', 'Campaign_init');
add_action('admin_init', 'Campaign_meta_box');
add_action('save_post', 'Campaign_save');
add_action('admin_head','Campaign_check_post_type_and_remove_media_buttons');

add_filter('archive_template','Campaign_archive');
add_filter('single_template','Campaign_single');


	//Creation du custom post
	function Campaign_init() {
		$labels = array(
				'name'               => _x( 'Campaigns', 'post type general name', 'your-plugin-textdomain' ),
				'singular_name'      => _x( 'Campaigns', 'post type singular name', 'your-plugin-textdomain' ),
				'menu_name'          => _x( 'Campaigns', 'admin menu', 'your-plugin-textdomain' ),
				'name_admin_bar'     => _x( 'Campaigns', 'add new on admin bar', 'your-plugin-textdomain' ),
				'add_new'            => _x( 'Add Campaign', 'Campaign', 'your-plugin-textdomain' ),
				'add_new_item'       => __( ' ' ),
				'new_item'           => __( 'New Campaign', 'your-plugin-textdomain' ),
				'edit_item'          => __( 'Edit Campaign', 'your-plugin-textdomain' ),
				'view_item'          => __( 'View Campaign', 'your-plugin-textdomain' ),
				'all_items'          => __( 'All Campaign', 'your-plugin-textdomain' ),
				'search_items'       => __( 'Search Campaign', 'your-plugin-textdomain' ),
				'not_found'          => __( 'No Campaign found.', 'your-plugin-textdomain' ),
				'not_found_in_trash' => __( 'No Campaign found in Trash.', 'your-plugin-textdomain' )
		);
		
		$args = array(
				'labels'             => $labels,
				'public'             => true,
				'has_archive' 		 => true,
				'menu_icon'   		 => 'dashicons-admin-site',
				'capability_type'    => 'post',
				'menu_position'      => 4,
				'supports'           => array( 'title')
		);
		
		register_post_type('campaign', $args);
		
	}

	// Création de la métabox
	function Campaign_meta_box() {
		add_meta_box('campaign_meta', "Création d'une campagne", 'Campaign_fields_meta_box','campaign');
	}

	//Création des custom field de  la méta box
	function Campaign_fields_meta_box() {
	
	global $post;
	
	$value_campaign_leader		= get_post_meta( $post->ID, 'campaign_leader',true );
	$value_campaign_date_start	= get_post_meta( $post->ID, 'campaign_date_start',true );
	$value_campaign_date_end    = get_post_meta( $post->ID, 'campaign_date_end', true );
	$value_campaign_description = get_post_meta( $post->ID, 'campaign_description', true );
	wp_enqueue_media();
	
	?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
	    	jQuery('.campaign_date').datepicker({
	        	dateFormat : 'yy-mm-dd'
	    	});
		});
		</script>
		
		<p>
			<input id="campaign_leader" name="campaign_leader" type="text" style="width: 20%" placeholder ="Ajouter le nom du responsable " value="<?php echo $value_campaign_leader;?>"/>	
		</p>
		
		<p>
			<input type="text" class="campaign_date" style="width: 20%"name="campaign_date_start" id ="campaign_date_start" placeholder ="Date de début de la campagne" value="<?php echo $value_campaign_date_start;?>">
		</p>
		
		<p>
			<input type="text" class="campaign_date" style="width: 20%"name="campaign_date_end" id ="campaign_date_end" placeholder ="Date de fin de la campagne" value="<?php echo $value_campaign_date_end;?>">
		</p>
	
		<div style="width: 70%;">
		
		<?php 
			$settings = array( 'media_buttons' => false,'quicktags' => false );
			$content = $value_campaign_description;                     
			$editor_id = 'campaign_description';
			wp_editor( $content, $editor_id,$settings );
		?>
		
		</div>

	<?php
	}

	// Sauvegarde des custom field
	function Campaign_save ($post_id) {
	
 		if(isset($_POST['campaign_leader'])){
   	  	update_post_meta($post_id, 'campaign_leader',$_POST['campaign_leader'] );
 		}  	
 		
		if(isset($_POST['campaign_date_start'])){
  			update_post_meta($post_id, 'campaign_date_start',$_POST['campaign_date_start'] );
  		}

		if(isset($_POST['campaign_date_end'])){
  			update_post_meta($post_id, 'campaign_date_end',$_POST['campaign_date_end'] );
  		}
  		
  		if(isset($_POST['campaign_description'])){
  			update_post_meta($post_id, 'campaign_description',$_POST['campaign_description'] );
  		}
  	
  
	} 
	
	
	
	// Routage vers la single page du custom post
	function Campaign_single($single_template){
		global $post;
		
		if($post->post_type == 'campaign' ){
			$single_template = plugin_dir_path(__FILE__).'/single-campaign.php';
		}
		return $single_template;
	}
	
	// Routage vers la page archive du custom post 
	function Campaign_archive($template){
		
		if(is_post_type_archive('campaign')){
			$theme_files = array('archive-campaign.php');
			$exists_in_theme = locate_template($theme_files, false);
			if($exists_in_theme == ''){
				return plugin_dir_path(__FILE__) . 'archive-campaign.php';
			}
		}
		return $template;
	}
	
	// Retirer le bouton média de l'éditeur wysiwyg
	function Campaign_check_post_type_and_remove_media_buttons() {
		global $current_screen;
		if( 'debriefing' == $current_screen->post_type ) remove_action('media_buttons', 'media_buttons');
	}
	
	//ajoute de différent script
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('https://code.jquery.com/jquery-1.12.4.js');
	wp_enqueue_script('https://code.jquery.com/ui/1.12.1/jquery-ui.js');
	wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

	
	
	
	function my_admin_menu() {
		add_submenu_page( 'edit.php?post_type=campaign', 'doc', 'Documentation', 'manage_options', 'team_member_doc', 'create_doc' );
		
	}function create_doc() {    global $markdown_content;
	
	$markdown_content = file_get_contents(dirname(__FILE__).'/readme.md');
	//$content_html = Markdown($markdown_content);
	
	?>
    <div class='content'>
    
    <?php
    
    
    echo $markdown_content;    
    ?>
    </div>
    <?php
}

?>