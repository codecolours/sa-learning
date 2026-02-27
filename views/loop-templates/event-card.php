<?php
/**
 * Event Card
 *
 * @package sa-learning
 */
?>
<?php 
$event_id = get_query_var('event_id');
if (!$event_id) {
    return;
}

?>
<div class="list-item col-4">
    <div class="item-img">
        <?php 
        $featuredImage = get_the_post_thumbnail_url($event_id);
        if ($featuredImage): ?>
            <img src="<?php echo esc_url($featuredImage); ?>" alt="<?php echo esc_attr(get_the_title($event_id)); ?>">
        <?php endif; ?>
    </div>
    <div class="item-info">
        <h4><?php echo esc_html(get_the_title($event_id)); ?></h4>
        <?php 
        $event_date_from = get_field('event_date_from', $event_id);
        $event_date_to = get_field('event_date_to', $event_id);
        $event_time_from = get_field('event_time_from', $event_id);
        $event_time_to = get_field('event_time_to', $event_id);
        $event_location = get_field('event_location', $event_id);
        $booking_link = get_field('booking_link', $event_id);
        
        if ($event_date_from || $event_date_to): ?>
            <div class="event-date">
                <?php if ($event_date_from): ?>
                    <?php echo date('j F Y', strtotime($event_date_from)); ?>
                <?php endif; ?>
                <?php if ($event_date_to): ?>
                    <?php echo ' - ' . date('j F Y', strtotime($event_date_to)); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>        
        <?php if ($event_location): ?>
            <div class="event-location">
                <?php echo esc_html($event_location); ?>
            </div>
        <?php endif; ?>
        <?php if ($event_time_from || $event_time_to): ?>
            <div class="event-time">
                <?php if ($event_time_from): ?>
                    <?php echo $event_time_from; ?>
                <?php endif; ?>
                <?php if ($event_time_to): ?>
                    <?php echo ' - ' . $event_time_from; ?>
                <?php endif; ?>
            </div>
        <?php endif;
        
        $excerpt = get_the_excerpt($event_id);
        if ($excerpt): ?>
            <div class="item-det">
                <?php echo wp_kses_post($excerpt); ?>
            </div>
        <?php endif; 
        
        if ($booking_link): ?>
            <a target="_blank" href="<?php echo esc_url($booking_link); ?>" class="button button-secondary">Book Now</a>
        <?php endif; ?>
    </div>
</div>