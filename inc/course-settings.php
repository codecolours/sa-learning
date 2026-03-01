<?php 

/**
 * Course Settings
 *
 * @package SA Learning
 */

/**
 * Add header banner after main header
 */
function sa_learning_header_banner() {
	// Only show on single sa-course pages
	if ( ! is_singular( 'sa-course' ) ) {
		return;
	}

	// Check if banner should be removed
	$remove_bnner = get_field( 'remove_bnner' );
	if ( $remove_bnner ) {
		return;
	}

	// Get all field values
	$page_banner        = get_field( 'page_banner' );
	$banner_title       = get_field( 'banner_title' );
	$title_tag          = get_field( 'title_tag' );
	$banner_description = get_field( 'banner_description' );
	$course_code        = get_field( 'course_code' );
	$course_highlight   = get_field( 'course_highlight' );

	// Check if at least one field has content
	if ( ! $page_banner && ! $banner_title && ! $banner_description && ! $course_code && ! $course_highlight ) {
		return;
	}

	// Validate and sanitize title tag for security
	$allowed_heading_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
	$title_tag            = ( $title_tag && in_array( strtolower( $title_tag ), $allowed_heading_tags, true ) ) ? strtolower( $title_tag ) : 'h1';
	?>
	<div class="course-banner-container">
		<?php if ( $page_banner ) : ?>
			<div class="course-banner">
				<?php
				echo wp_get_attachment_image(
					$page_banner,
					'full',
					false,
					array(
						'alt'     => $banner_title ? esc_attr( $banner_title ) : esc_attr( get_the_title() ),
						'loading' => 'eager',
					)
				);
				?>
			</div>
		<?php endif; ?>

		<?php if ( $course_code ) : ?>
			<div class="course-code">
				<?php echo esc_html( $course_code ); ?>
			</div>
		<?php endif; ?>

		<div class="course-banner-content-wrapper">
			<div class="course-banner-content">
				<?php if ( $banner_title ) : ?>
					<<?php echo esc_attr( $title_tag ); ?>><?php echo esc_html( $banner_title ); ?></<?php echo esc_attr( $title_tag ); ?>>
				<?php endif; ?>

				<?php if ( $banner_description ) : ?>
					<p><?php echo esc_html( $banner_description ); ?></p>
				<?php endif; ?>

				<div class="c-breadcrumb">
					<?php
					if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
						rank_math_the_breadcrumbs();
					} elseif ( function_exists( 'rank_math_get_breadcrumbs' ) ) {
						echo wp_kses_post( rank_math_get_breadcrumbs() );
					}
					?>
				</div>
			</div>
		</div>

		<?php if ( $course_highlight ) : ?>
			<div class="course-highlight">
				<?php echo esc_html( $course_highlight ); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
}
add_action( 'neve_after_header_hook', 'sa_learning_header_banner' );


/**
 * Add course events after content
 */
function sa_learning_course_events() {
	// Only show on single sa-course pages
	if ( ! is_singular( 'sa-course' ) ) {
		return;
	}

	$course_events = get_field( 'course_events' );
	if ( empty( $course_events ) ) {
		return;
	}

	// Filter out past events
	$upcoming_events = array();
	$current_time    = current_time( 'timestamp' );

	foreach ( $course_events as $event ) {
		// Get event dates
		$event_date_to   = get_field( 'event_date_to', $event );
		$event_date_from = get_field( 'event_date_from', $event );

		// Use event_date_to if available, otherwise fall back to event_date_from
		$event_date = $event_date_to ? $event_date_to : $event_date_from;

		// Skip if no date is set
		if ( empty( $event_date ) ) {
			continue;
		}

		// Convert date string to timestamp
		$event_timestamp = strtotime( $event_date );

		// Only include future or today's events
		if ( $event_timestamp && $event_timestamp >= strtotime( 'today', $current_time ) ) {
			$upcoming_events[] = $event;
		}
	}

	// Don't show section if no upcoming events
	if ( empty( $upcoming_events ) ) {
		return;
	}

	$section_sub_title = get_field( 'section_sub_title' );
	$section_title     = get_field( 'section_title' );

	?>
	<div class="course-events-container pad-top">
		<?php if ( $section_sub_title ) : ?>
			<h5 class="smb-10"><?php echo esc_html( $section_sub_title ); ?></h5>
		<?php endif; ?>

		<?php if ( $section_title ) : ?>
			<h2><?php echo esc_html( $section_title ); ?></h2>
		<?php endif; ?>

		<div class="block-inner items-wrapper flex-container spt-25">
			<?php foreach ( $upcoming_events as $event ) : ?>
				<?php
					set_query_var( 'event_id', $event );
					get_template_part( 'views/loop-templates/event-card' );
				?>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

add_action( 'neve_after_content', 'sa_learning_course_events' );


/**
 * Helper function to prepare course data with all necessary fields
 */
function sa_learning_prepare_courses_data($courseIds) {
    if (empty($courseIds)) {
        return array();
    }
    
    // Ensure we have IDs, not post objects
    $courseIds = array_map('intval', (array) $courseIds);
    
    // Batch load all posts at once
    $posts = get_posts(array(
        'post_type' => 'sa-course',
        'include' => $courseIds,
        'posts_per_page' => -1,
        'orderby' => 'post__in',
        'no_found_rows' => true,
    ));
    
    if (empty($posts)) {
        return array();
    }
    
    $coursesData = array();
    
    foreach ($posts as $post) {
        $courseId = $post->ID;
        
        $coursesData[] = array(
            'id' => $courseId,
            'title' => $post->post_title,
            'excerpt' => get_the_excerpt($post),
            'permalink' => get_permalink($courseId),
            'thumbnail' => get_the_post_thumbnail_url($courseId, 'full'),
            'course_code' => get_field('course_code', $courseId),
            'course_highlight' => get_field('course_highlight', $courseId),
        );
    }
    
    return $coursesData;
}

/**
 * Helper function to render a course item from prepared data
 * Uses the course-item.php template for consistency
 */
function sa_learning_render_course_item($courseData) {
    set_query_var('courseData', $courseData);
    get_template_part('views/loop-templates/course-item');
    set_query_var('courseData', null); // Clean up
}

/**
 * AJAX handler for filtering courses (optimized with HTML caching)
 */
function ajax_filter_courses() {
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : 'all';
    $courses_per_page = isset($_POST['courses_per_page']) ? intval($_POST['courses_per_page']) : -1;
    $filter_by_categories = isset($_POST['filter_by_categories']) ? sanitize_text_field($_POST['filter_by_categories']) : '';

    $cacheKey = 'course_html_' . md5(serialize(array(
        'posts_per_page' => $courses_per_page,
        'filter_cats' => $filter_by_categories,
        'filter' => $category
    )));
    
    $cachedResponse = get_transient($cacheKey);
    
    if (false !== $cachedResponse) {
        wp_send_json_success($cachedResponse);
        return;
    }
    
    $args = array(
        'post_type' => 'sa-course',
        'posts_per_page' => $courses_per_page,
        'post_status' => 'publish',
        'fields' => 'ids',
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    );

    $tax_query = array();

    if ($category !== 'all') {
        $tax_query[] = array(
            'taxonomy' => 'course-category',
            'field' => 'slug',
            'terms' => $category,
        );
    }

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

    if (!empty($tax_query)) {
        if (count($tax_query) > 1) {
            $tax_query['relation'] = 'AND';
        }
        $args['tax_query'] = $tax_query;
    }

    $query = new WP_Query($args);
    $courseIds = $query->posts;
    
    $coursesData = sa_learning_prepare_courses_data($courseIds);
    
    ob_start();
    
    if (!empty($coursesData)) {
        foreach ($coursesData as $courseData) {
            sa_learning_render_course_item($courseData);
        }
    }
    
    $html = ob_get_clean();
    
    $response = array(
        'html' => $html,
        'total' => count($coursesData),
    );
    
    set_transient($cacheKey, $response, 6 * HOUR_IN_SECONDS);
    
    wp_send_json_success($response);
}
add_action('wp_ajax_filter_courses', 'ajax_filter_courses');
add_action('wp_ajax_nopriv_filter_courses', 'ajax_filter_courses');

/**
 * Clear category featured cache
 */
function sa_learning_clear_category_featured_cache($post_id) {
    if (get_post_type($post_id) === 'sa-course') {
        delete_transient('category_featured_courses_' . md5(serialize($categoryIds)));
    }
}
add_action('save_post_sa-course', 'sa_learning_clear_category_featured_cache');
add_action('edited_course-category', 'sa_learning_clear_category_featured_cache');


/**
 * Clear course listing cache when courses are updated
 */
function sa_learning_clear_course_cache($post_id = null) {
    if ($post_id && get_post_type($post_id) !== 'sa-course') {
        return;
    }
    
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_course_listing_%' OR option_name LIKE '_transient_timeout_course_listing_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_course_html_%' OR option_name LIKE '_transient_timeout_course_html_%'");
}
add_action('save_post_sa-course', 'sa_learning_clear_course_cache');
add_action('delete_post', 'sa_learning_clear_course_cache');

/**
 * Clear course cache when course categories are updated
 */
function sa_learning_clear_course_category_cache() {
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_course_listing_%' OR option_name LIKE '_transient_timeout_course_listing_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_course_html_%' OR option_name LIKE '_transient_timeout_course_html_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_course_categories_%' OR option_name LIKE '_transient_timeout_course_categories_%'");
}
add_action('edited_course-category', 'sa_learning_clear_course_category_cache');
add_action('create_course-category', 'sa_learning_clear_course_category_cache');
add_action('delete_course-category', 'sa_learning_clear_course_category_cache');

/**
 * Manual cache clearing function
 * To clear all course caches, run in WordPress console or add to functions.php temporarily:
 * sa_learning_clear_all_course_caches();
 */
function sa_learning_clear_all_course_caches() {
    global $wpdb;
    $deleted = $wpdb->query("DELETE FROM {$wpdb->options} WHERE 
        option_name LIKE '_transient_course_listing_%' OR 
        option_name LIKE '_transient_timeout_course_listing_%' OR
        option_name LIKE '_transient_course_html_%' OR 
        option_name LIKE '_transient_timeout_course_html_%' OR
        option_name LIKE '_transient_course_categories_%' OR 
        option_name LIKE '_transient_timeout_course_categories_%'
    ");
    return $deleted;
}