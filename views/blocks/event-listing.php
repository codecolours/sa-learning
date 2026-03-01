<?php 
/**
* Title: Event Listing
* Description: Event Listing
* Category: layout
* Icon: calendar
* Keywords: event-listing
* SupportsAlign: false
* Mode: edit
* PostTypes: page
*/
?>

<?php 
$noOfPosts      = get_field( 'number_of_posts' ) ?: 6;
$enPagination   = get_field( 'enable_pagination' );
$eventType      = get_field( 'event_type' ); // 0 = Upcoming, 1 = Past
$extraClassName = !empty($block['className']) ? ' ' . esc_attr($block['className']) : '';

// Always respect the number of posts field
// The pagination toggle only controls the "Load More" button visibility
$postsPerPage = $noOfPosts;

// Determine the date comparison and order based on event type
$isPastEvents = (bool) $eventType;
$dateCompare = $isPastEvents ? '<' : '>=';
$eventTypeClass = $isPastEvents ? ' past-events' : ' upcoming-events';
?>


<div data-block-id="<?php echo esc_attr($block['id']); ?>" 
     data-posts-per-page="<?php echo esc_attr($postsPerPage); ?>" 
     data-event-type="<?php echo esc_attr($eventType); ?>"
     class="block-content event-listing-block course-events-container<?php echo $extraClassName . $eventTypeClass; ?>">
    
    <div class="block-inner items-wrapper flex-container">
        <?php
        $args = array(
            'post_type' => 'sa-event',
            'posts_per_page' => $postsPerPage,
            'post_status' => 'publish',
            'meta_key' => 'event_date_from',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'meta_query' => array(
                array(
                    'key' => 'event_date_from',
                    'value' => date('Ymd'),
                    'compare' => $dateCompare,
                    'type' => 'DATE'
                )
            )
        );
        
        $query = new WP_Query($args);

        if ($query->have_posts()): 
            while ($query->have_posts()): 
                $query->the_post();
                set_query_var('event_id', get_the_ID());
                get_template_part('views/loop-templates/event-card');
            endwhile;
            wp_reset_postdata();
        else: ?>
            <p class="no-events-found">No events found!</p>
        <?php endif; ?>
    </div>
    
    <?php if ($enPagination && $query->max_num_pages > 1): ?>
        <div class="load-more-wrapper">
            <button class="button button-primary load-more-btn">Load More <span>+</span></button>
        </div>
    <?php endif; ?>
</div>




