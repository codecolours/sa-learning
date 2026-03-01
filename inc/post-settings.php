<?php 

/**
 * Post Settings
 *
 * @package SA Learning
 */

/**
 * AJAX handler for filtering posts
 */
function ajax_filter_posts() {
    // Verify nonce if needed
    // check_ajax_referer('post_filter_nonce', 'nonce');

    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : 'all';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 6;

    // Build query args
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'post_status' => 'publish',
    );

    // Add category filter if not 'all'
    if ($category !== 'all') {
        $args['category_name'] = $category;
    }

    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            set_query_var('postID', get_the_ID());
            get_template_part('views/loop-templates/post-card');
        }
    }
    
    $html = ob_get_clean();
    wp_reset_postdata();

    // Check if there are more posts
    $has_more = $page < $query->max_num_pages;
    
    wp_send_json_success(array(
        'html' => $html,
        'has_more' => $has_more,
        'total' => $query->found_posts,
        'max_pages' => $query->max_num_pages,
        'current_page' => $page,
    ));
}
add_action('wp_ajax_filter_posts', 'ajax_filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'ajax_filter_posts');