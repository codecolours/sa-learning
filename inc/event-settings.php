<?php 

/**
 * Event Settings
 *
 * @package SA Learning
 */

/**
 * AJAX handler for loading more events
 */
function ajax_load_more_events() {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 6;
    $event_type = isset($_POST['event_type']) ? intval($_POST['event_type']) : 0;

    // Determine the date comparison based on event type
    $isPastEvents = (bool) $event_type;
    $dateCompare = $isPastEvents ? '<' : '>=';

    // Build query args
    $args = array(
        'post_type' => 'sa-event',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
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
    
    ob_start();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            set_query_var('event_id', get_the_ID());
            get_template_part('views/loop-templates/event-card');
        }
    }
    
    $html = ob_get_clean();
    wp_reset_postdata();

    // Check if there are more events
    $has_more = $page < $query->max_num_pages;
    
    wp_send_json_success(array(
        'html' => $html,
        'has_more' => $has_more,
        'total' => $query->found_posts,
        'max_pages' => $query->max_num_pages,
        'current_page' => $page,
    ));
}
add_action('wp_ajax_load_more_events', 'ajax_load_more_events');
add_action('wp_ajax_nopriv_load_more_events', 'ajax_load_more_events');