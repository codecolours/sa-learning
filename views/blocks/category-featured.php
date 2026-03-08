<?php 
/**
* Title: Featured Categories
* Description: Featured Categories
* Category: layout
* Icon: category
* Keywords: featured-categories
* SupportsAlign: false
* Mode: edit
* PostTypes: page
*/
?>

<?php 
$selectedCategories = get_field( 'selected_categories' );
$extraClassName = !empty($block['className']) ? ' ' . esc_attr($block['className']) : '';

$coursesByCategory = [];
$maxCoursesPerCategory = 5;

if ($selectedCategories) {
    $categoryIds = array_map(function($cat) { return $cat->term_id; }, $selectedCategories);
    
    $cacheKey = 'category_featured_courses_' . md5(serialize($categoryIds));
    $cachedData = get_transient($cacheKey);
    
    if (false === $cachedData) {
        $allCourses = new WP_Query(array(
            'post_type' => 'sa-course',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'no_found_rows' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => true,
            'tax_query' => array(
                array(
                    'taxonomy' => 'course-category',
                    'field' => 'term_id',
                    'terms' => $categoryIds,
                ),
            ),
        ));
        
        if ($allCourses->have_posts()) {
            foreach ($allCourses->posts as $courseId) {
                $courseTerms = wp_get_post_terms($courseId, 'course-category', array('fields' => 'ids'));
                foreach ($courseTerms as $termId) {
                    if (in_array($termId, $categoryIds)) {
                        if (!isset($coursesByCategory[$termId])) {
                            $coursesByCategory[$termId] = [];
                        }
                        if (count($coursesByCategory[$termId]) < $maxCoursesPerCategory) {
                            $coursesByCategory[$termId][] = $courseId;
                        }
                    }
                }
            }
        }
        
        $cachedData = array(
            'courses' => $coursesByCategory,
            'course_data' => array()
        );
        
        $allCourseIds = array_unique(call_user_func_array('array_merge', array_values($coursesByCategory) ?: [[]]));
        if (!empty($allCourseIds)) {
            $coursePosts = get_posts(array(
                'post_type' => 'sa-course',
                'include' => $allCourseIds,
                'posts_per_page' => -1,
                'no_found_rows' => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
            ));
            
            foreach ($coursePosts as $coursePost) {
                $cachedData['course_data'][$coursePost->ID] = array(
                    'title' => $coursePost->post_title,
                    'permalink' => get_permalink($coursePost->ID)
                );
            }
        }
        
        set_transient($cacheKey, $cachedData, 12 * HOUR_IN_SECONDS);
    }
    
    $coursesByCategory = $cachedData['courses'];
    $courseData = $cachedData['course_data'];
}
?>


<div data-block-id="<?php echo esc_attr($block['id']); ?>" class="block-content category-featured-block<?php echo $extraClassName; ?>">
    <div class="block-inner">
        <div class="category-slider-wrapper">
            <div class="category-slider-container">
                <div class="flex-container">
                    <?php 
                    
                    if ($selectedCategories): 
                        foreach ($selectedCategories as $category): 
                            $categoryID = $category->term_id;
                            $categoryName = $category->name;
                            $categoryDescription = $category->description;
                            $categoryImage = get_field('featured_image', 'course-category_' . $categoryID);
                            $categoryLink = get_term_link($categoryID);
                            $categoryCourses = isset($coursesByCategory[$categoryID]) ? $coursesByCategory[$categoryID] : array();
                        ?>
                        <div class="featured-item">
                            <h3 class="featured-title"><?php echo esc_html($categoryName); ?></h3>
                            <div class="featured-content">
                                <div class="content-info">
                                    <?php echo esc_html($categoryDescription); ?>
                                    <div class="btn-wrapper">
                                        <a href="<?php echo esc_url($categoryLink); ?>" class="button button-primary">More Info</a>
                                        <a href="<?php echo esc_url($categoryLink); ?>" class="button button-secondary">Get in Touch</a>
                                    </div>
                                </div>
                                <?php if (!empty($categoryCourses)): ?>
                                    <div class="courses-wrapper">
                                        <h4>Courses</h4>
                                        <ul class="courses-list">
                                            <?php foreach ($categoryCourses as $courseId): 
                                                if (isset($courseData[$courseId])): ?>
                                                    <li class="course-item">
                                                        <a href="<?php echo esc_url($courseData[$courseId]['permalink']); ?>"><?php echo esc_html($courseData[$courseId]['title']); ?></a>
                                                    </li>
                                                <?php endif;
                                            endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                <div class="btn-wrapper mb-btn-wrapper">
                                    <a href="<?php echo esc_url($categoryLink); ?>" class="button button-primary">More Info</a>
                                    <a href="/get-in-touch/" class="button button-secondary">Get in Touch</a>
                                </div>
                            </div>
                            <?php if ($categoryImage): ?>
                                <div class="featured-image">
                                    <img src="<?php echo esc_url($categoryImage['url']); ?>" alt="<?php echo esc_attr($categoryName); ?>" loading="lazy">
                                </div>
                            <?php endif; 
                            if ($category->count): ?>
                                <span class="post-count">
                                    <?php echo esc_html($category->count); ?> Courses
                                </span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; 
                    endif; ?>
                </div>
            </div>
            
            <div class="slider-nav">
                <button class="slider-btn slider-prev" aria-label="Previous slide">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 18l-6-6 6-6"/>
                    </svg>
                </button>
                <button class="slider-btn slider-next" aria-label="Next slide">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>




