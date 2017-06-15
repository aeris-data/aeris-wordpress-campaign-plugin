<?php
get_header();


$post_id = get_the_ID();
$value_campaign_leader		= get_post_meta( $post->ID, 'campaign_leader',true );
$value_campaign_date_start	= get_post_meta( $post->ID, 'campaign_date_start',true );
$value_campaign_date_end    = get_post_meta( $post->ID, 'campaign_date_end', true );
$value_campaign_description = get_post_meta( $post->ID, 'campaign_description', true );

if(have_posts()) : while(have_posts()) : the_post(); 
get_template_part( 'template-parts/header-content', 'page' );

?>

<div id="content-area" class="wrapper sidebar" >
	<main id="main" class="site-main" role="main">
		


<!-- 	<section> -->
		
		<section class="wrapper-content">
		<?php echo $value_campaign_description; ?>
		</section>
		<footer></footer>
		</article>
<!-- 	</section> -->
	</main>
	<aside style="content:'content: "\A4 \ '; !important" >
		<section>
			
			<article>
			<h3>Responsable : <?php echo $value_campaign_leader; ?> </h3>
			</article>
		
	
		
			<ul>
			<?php 
			
			$date1 = new DateTime($value_campaign_date_start);
			$date2 = new DateTime($value_campaign_date_end);
			$diff = $date2->diff($date1)->format("%a") +1;
			$datebufferStart = strtotime( $value_campaign_date_start);
			$value_campaign_date_start= date( 'l d F Y', $datebufferStart);
			$datebufferEnd= strtotime( $value_campaign_date_end);
			$value_campaign_date_end = date( 'l d F Y', $datebufferEnd);
			?>
			
			<li><span style='font-weight:bold;'>Date de début</span> : <?php echo $value_campaign_date_start; ?> </li>
			<li><span style='font-weight:bold;'>Date de fin</span>   : <?php echo $value_campaign_date_end; ?> </li>
			<li><span style='font-weight:bold;'>Durée </span> : <?php echo $diff; ?> jours</li>
			
			</ul>
	
		</section>
		
	</aside>
<?php 
endwhile; endif;
?>

</div>
<?php
get_footer();
?>
