<?php
/**
 * Theme main configs
 *
 * @package sa-learning
*/

add_theme_support( 'editor-styles' );
add_editor_style( 'assets/dist/css/editor-style.css' );

function site_enqueue_scripts() {

	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'sa-learning-theme', get_stylesheet_directory_uri() . '/assets/dist/css/style.css', null, '1.0.0', 'all' );
    wp_enqueue_script( 'sa-learning-script', get_stylesheet_directory_uri() . '/assets/dist/js/main.js', array( 'jquery' ), '1.0.', true );    

    // Enqueue Font Awesome (check if not already loaded by Otter plugin)
    if ( ! wp_style_is( 'font-awesome-5', 'enqueued' ) ) {
        wp_enqueue_style( 'font-awesome-5', '/wp-content/plugins/otter-blocks/assets/fontawesome/css/all.min.css', array(), null );
    }

    // Localize the script with AJAX URL
    wp_localize_script( 'sa-learning-script', 'saLearningAjax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
	));

}
add_action( 'wp_enqueue_scripts', 'site_enqueue_scripts', 20 );

//Links & Buttons
function siteButton( $button, $class = 'button-primary') {
    if ( !empty( $button['url'] ) && !empty( $button['title'] ) ) {
        $link_target = !empty( $button['target'] ) ? esc_attr( $button['target'] ) : '_self';
        ?>
        <a class="button <?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo $link_target; ?>">
            <?php echo esc_html( $button['title'] ); ?>
        </a>
        <?php
    }
}

function append_website_by() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var copyrightElements = document.querySelectorAll('.site-footer .footer-bottom .builder-item--footer_copyright');
        copyrightElements.forEach(function(copyrightElement) {
            if (copyrightElement) {
                copyrightElement.innerHTML += '<div class="siteby">Website by <a target="_blank" href="https://www.boylen.com.au">Boylen</a></div>';
            }
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'append_website_by');

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

/**
 * AJAX handler for filtering courses
 */
function ajax_filter_courses() {
    // Verify nonce if needed
    // check_ajax_referer('course_filter_nonce', 'nonce');

    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : 'all';
    $courses_per_page = isset($_POST['courses_per_page']) ? intval($_POST['courses_per_page']) : -1;
    $filter_by_categories = isset($_POST['filter_by_categories']) ? sanitize_text_field($_POST['filter_by_categories']) : '';

    // Build query args
    $args = array(
        'post_type' => 'sa-course',
        'posts_per_page' => $courses_per_page,
        'post_status' => 'publish',
    );

    // Build tax_query array
    $tax_query = array();

    // Add category filter if not 'all'
    if ($category !== 'all') {
        $tax_query[] = array(
            'taxonomy' => 'course-category',
            'field' => 'slug',
            'terms' => $category,
        );
    }

    // Add pre-filtered categories if specified
    if (!empty($filter_by_categories)) {
        $filter_category_ids = array_map('intval', explode(',', $filter_by_categories));
        if (!empty($filter_category_ids)) {
            $tax_query[] = array(
                'taxonomy' => 'course-category',
                'field' => 'term_id',
                'terms' => $filter_category_ids,
            );
        }
    }

    // Apply tax_query if we have conditions
    if (!empty($tax_query)) {
        if (count($tax_query) > 1) {
            $tax_query['relation'] = 'AND';
        }
        $args['tax_query'] = $tax_query;
    }

    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            set_query_var('courseID', get_the_ID());
            get_template_part('views/loop-templates/course-item');
        }
    }
    
    $html = ob_get_clean();
    wp_reset_postdata();
    
    wp_send_json_success(array(
        'html' => $html,
        'total' => $query->found_posts,
    ));
}
add_action('wp_ajax_filter_courses', 'ajax_filter_courses');
add_action('wp_ajax_nopriv_filter_courses', 'ajax_filter_courses');
