<?php 
/**
* Title: Course Listing
* Description: Course Listing
* Category: layout
* Icon: list-view
* Keywords: course-listing
* SupportsAlign: false
* Mode: edit
* PostTypes: page
*/
?>

<?php 
$noOfCourses        = get_field( 'number_of_courses' ) ?: -1;
$addFilters         = get_field( 'add_filters' );
$filterByCategories = get_field( 'filter_by_categories' );
$extraClassName = !empty($block['className']) ? ' ' . esc_attr($block['className']) : '';

// Prepare filter categories for data attribute (for AJAX)
$filterCategoriesData = '';
if ($filterByCategories && is_array($filterByCategories)) {
    $filterCategoriesData = implode(',', $filterByCategories);
}
?>


<div data-block-id="<?php echo esc_attr($block['id']); ?>" 
     data-courses-per-page="<?php echo esc_attr($noOfCourses); ?>"
     data-filter-by-categories="<?php echo esc_attr($filterCategoriesData); ?>"
     class="block-content course-listing-block<?php echo $extraClassName; ?>">
    
    <?php if ($addFilters): 
        $cacheKeyCat = 'course_categories_' . md5('course-category');
        $categories = get_transient($cacheKeyCat);
        
        if (false === $categories) {
            $categories = get_terms(array(
                'taxonomy' => 'course-category',
                'hide_empty' => true,
            ));
            set_transient($cacheKeyCat, $categories, 6 * HOUR_IN_SECONDS);
        }
    ?>
        <div class="filters-wrapper">
            <div class="filter-item">
                <ul class="filter-list">
                    <li class="filter-item active">
                        <a data-filter="all">All</a>
                    </li>
                    <?php foreach ($categories as $category): ?>
                        <li class="filter-item">
                            <a data-filter="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="block-inner items-wrapper flex-container">
        <?php
        $cacheKey = 'course_html_' . md5(serialize(array(
            'posts_per_page' => $noOfCourses,
            'filter_cats' => $filterByCategories,
            'filter' => 'all'
        )));
        
        $cachedHtml = get_transient($cacheKey);
        
        if (false === $cachedHtml) {
            $args = array(
                'post_type' => 'sa-course',
                'posts_per_page' => $noOfCourses,
                'post_status' => 'publish',
                'fields' => 'ids',
                'no_found_rows' => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
            );

            if ($filterByCategories && !empty($filterByCategories)) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'course-category',
                        'field' => 'term_id',
                        'terms' => $filterByCategories,
                    ),
                );
            }

            $query = new WP_Query($args);
            $courseIds = $query->posts;
            
            $coursesData = sa_learning_prepare_courses_data($courseIds);
            
            ob_start();
            if (!empty($coursesData)) {
                foreach ($coursesData as $courseData) {
                    sa_learning_render_course_item($courseData);
                }
            } else {
                echo '<p class="no-courses-found">No courses found!</p>';
            }
            $cachedHtml = ob_get_clean();
            
            set_transient($cacheKey, $cachedHtml, 6 * HOUR_IN_SECONDS);
        }

        echo $cachedHtml;
        ?>
    </div>
</div>




