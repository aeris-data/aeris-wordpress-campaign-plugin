<?php
/*

Plugin Name: Aeris-Widget-Campaign-Recent
GitHub Plugin URI:
Version: 0.0.1

*/

/* Creationd' une classe dérivée de WP_Widget : */
class CampaignRecents extends WP_Widget {
	
	// Constructeur
	function CampaignRecents() {
		parent::WP_Widget ( false, $name = 'Aeris-Campaign', array (
				'name' => 'Aeris-Campaign',
				'description' => 'Affichage des campagnes recentes'
		) );
	}
	
	
	function widget($args, $instance) {
		
		extract ( $args );
		$title = apply_filters ( 'widget_title', $instance ['title'] );
		$nb_posts = $instance ['nb_posts'];
		
		
		$lastposts = get_posts ( array (
				'numberposts' => $nb_posts,
				'post_type' => 'campaign',
				'meta_type' => 'DATE',
				'orderby' => 'meta_value',
				'meta_key' => 'campaign_date_start',
				'post__not_in' => array (
						get_the_ID ()
				)
		) );
		?>
		
		<?php 
		echo $before_widget;
		if ($title)
			echo $before_title . $title . $after_title;
		else
			echo $before_title . 'Campaign Récentes' . $after_title;
		
		echo "<ul style= 'padding-left:0px;!important' >";
		foreach ( $lastposts as $post ) :
			setup_postdata ( $post );
			$datebufferStart = strtotime($post->campaign_date_start);
			$value_campaign_date_start= date( 'd/m/Y', $datebufferStart);
			$datebufferEnd= strtotime( $post->campaign_date_end);
			$value_campaign_date_end = date( 'd/m/Y', $datebufferEnd);
			?>
			
			<li >
				<i class="fa fa fa-globe"></i><a href='<?php echo  get_post_permalink($post->ID);?>'> <?php echo get_the_title($post->ID); ?></a>&nbsp<a href='<?php echo  '#';?>'><?php ?></a>
            	<span style='font-size:13px;'>Du <?php echo  $value_campaign_date_start;?> </span>	<span style='font-size:13px;'> au <?php echo  $value_campaign_date_end;?> </span>	
            </li>
<?php
		
endforeach;	?>
		<li>
		<a href= <?php echo get_post_type_archive_link( 'campaign'); ?> >Voir toutes les campagnes</a>
		</li><?php
		echo '</ul>';
		echo $after_widget;
		
	}
	
	////////////////////////////////////////////////////////////
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		// Récupération des paramètres envoyés
		$instance ['title'] = strip_tags ( $new_instance ['title'] );
		$instance ['nb_posts'] = $new_instance ['nb_posts'];
		
		return $instance;
	}
	
	////////////////////////////////////////////////
	function form($instance) {
		$title = esc_attr ( $instance ['title'] );
		$nb_posts = esc_attr ( $instance ['nb_posts'] );
		$nb_posts = isset ( $instance ['nb_posts'] ) ? absint ( $instance ['nb_posts'] ) :5;
		
		$categories = get_categories( array(
				'orderby' => 'name',
				'parent'  => 0
		) );
		?>
		
		
		
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">
                <?php echo 'Titre:'; ?>
                <input class="widefat"
		id="<?php echo $this->get_field_id('title'); ?>"
		name="<?php echo $this->get_field_name('title'); ?>" type="text"
		value="<?php echo $title; ?>" />
	</label>
</p>
<p>
<p>
	<label for="<?php echo $this->get_field_id( 'nb_posts' ); ?>"><?php _e( 'Number of posts to show:' ); ?> 
            	<input style="width: 20%;"
		id="<?php echo $this->get_field_id( 'nb_posts' ); ?>"
		name="<?php echo $this->get_field_name( 'nb_posts' ); ?>"
		type="number" step="1" min="1" value="<?php echo $nb_posts; ?>" /> </label>

</p>


<?php
	}
}
function register_campaign_widget() {
	register_widget ( 'CampaignRecents' );
}
add_action ('widgets_init','register_campaign_widget');


function fontawesome_widget_campaign() {
	wp_enqueue_style ( 'fontawesome', 'http://netdna.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.css', '', '4.5.0', 'all' );
}

add_action ( 'admin_init','fontawesome_widget_campaign');
?>