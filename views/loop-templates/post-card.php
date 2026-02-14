<?php
/**
 * Post Block Card
 *
 * @package sa-learning
*/
?>
<?php 
$pstID    = get_the_ID();
?>
<div class="post-item">
	<div class="post-img">
		<?php 
		$postCats = get_the_category( $pstID );
		if ($postCats): ?>
			<div class="post-cs">
				<?php 
				foreach ( $postCats as $cat ): ?>
					<a href="<?php echo esc_url(get_category_link($cat->cat_ID)); ?>">
						<?php echo esc_html($cat->name); ?>
					</a>                        
				<?php 
				endforeach;
				?>
			</div>
		<?php endif; ?>
		<a href="<?php echo esc_url(get_permalink( $pstID )); ?>" title="<?php echo esc_attr( the_title() ) ?>">
			<?php the_post_thumbnail(); ?>
		</a>
	</div>
	<h4>
		<a href="<?php echo esc_url(get_permalink( $pstID )); ?>" title="<?php echo esc_attr( the_title() ) ?>">
			<?php echo the_title(); ?>
		</a>
	</h4>
	<?php 
	$event_date = get_field( "event_date", $pstID ); 
	if ($event_date): ?>
			<div class="ev-date"><?php echo esc_html($event_date); ?></div>
		<?php 
	endif; ?>

	<div class="excerpt"><?php echo site_excerpt( $pstID ); ?></div>
	
	<div class="post-links">
		<?php 
		$event_booking_link = get_field( "event_booking_link", $pstID );
		if ($event_booking_link): ?>
			<a class="button button-secondary" target="_blank" href="<?php echo esc_url($event_booking_link); ?>">Book Now</a>
		<?php 
		endif; ?>
		<a class="button button-secondary" href="<?php echo esc_url(get_permalink( $pstID )); ?>" title="<?php echo esc_attr( the_title() ) ?>">Read More</a>
	</div>               
</div>