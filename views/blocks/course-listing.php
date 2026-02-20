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
?>


<div data-block-id="<?php echo esc_attr($block['id']); ?>" class="block-content course-listing-block<?php echo $extraClassName; ?>">
    
    <?php if ($addFilters): ?>
        <div class="filters-wrapper">
            <div class="filter-item">
                <ul class="filter-list">
                    <li class="filter-item active">
                        <a data-filter="all">All</a>
                    </li>
                    <?php 
                    $categories = get_terms(array(
                        'taxonomy' => 'course-category',
                        'hide_empty' => true,
                    ));
                    foreach ($categories as $category): ?>
                        <li class="filter-item">
                            <a data-filter="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="block-inner courses-wrapper flex-container">
        <?php
        $args = array(
            'post_type' => 'sa-course',
            'posts_per_page' => $noOfCourses,
        );

        // Apply category filter if specified
        if ($filterByCategories && !empty($filterByCategories)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'course-category',
                    'field' => 'term_id',
                    'terms' => $filterByCategories,
                ),
            );
        }

        $courses = get_posts($args);

        if ($courses): 
            foreach ($courses as $course):     
                set_query_var('courseID', $course->ID);
                get_template_part('views/loop-templates/course-item');
            endforeach;
            wp_reset_postdata();
        else: ?>
            <p class="no-courses-found">No courses found.</p>
        <?php endif; ?>
    </div>
</div>




