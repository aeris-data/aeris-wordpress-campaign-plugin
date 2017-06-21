<?php get_header();?>

	<div id="content-area" class="wrapper " >
		<main id="main" class="site-main" role="main">
<?php 
	    echo '<h1>Récapitulatif des campagnes</h1>';
		$yearly = new WP_Query(array('post_type'=>'campaign', 
									 'meta_key' => 'campaign_date_start',
									 'orderby'   => 'meta_value',
									 'posts_per_page' => -1));

		$prev_year = null;
		$prev_month = null;

		if( $yearly->have_posts() ) : while( $yearly->have_posts() ) : $yearly->the_post();

		$value_campaign_date_start	= get_post_meta( $post->ID, 'campaign_date_start',true );
			
		$time = strtotime($value_campaign_date_start);
		 
			$this_year =date('Y', $time);
			$this_month =  date('m', $time);
			
			if ($prev_year != $this_year) {
				echo '</ul>';
				echo '<h2>' . $this_year.'</h2>';
				
			}

			if ($prev_month!= $this_month) {
				if (!is_null($prev_month)) {
					echo '</ul>';
				}
				$dateObj   = DateTime::createFromFormat('!m', $this_month);
				$monthName = $dateObj->format('F'); // March
				echo "<h4 style='font-weight:bold;'>".$monthName.'</h4>';
				echo '<ul>';
			}
			
			echo '<li>';
			echo "<a href='".get_post_permalink($post->ID)."' alt='toto'>".get_the_title($post->ID)." </a>"; 
			echo '</li>';

			$prev_year = $this_year;
			$prev_month= $this_month;
			endwhile;
			
			echo '</ul>';
			endif;?>
		</main>
	</div>

<?php get_footer();?>
