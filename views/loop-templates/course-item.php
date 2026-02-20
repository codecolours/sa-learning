<?php
/**
 * Course Item
 *
 * @package sa-learning
 */
?>
<?php 
$course_id = get_query_var('courseID');
if (!$course_id) {
    return;
}

// Get course categories for filtering
$categories = get_the_terms($course_id, 'course-category');
$category_slugs = array();
if ($categories && !is_wp_error($categories)) {
    foreach ($categories as $category) {
        $category_slugs[] = $category->slug;
    }
}
$data_categories = !empty($category_slugs) ? implode(' ', $category_slugs) : 'uncategorized';
?>
<div class="course-item col-4" data-category="<?php echo esc_attr($data_categories); ?>">
    <div class="course-img">
        <?php 
        $featuredImage = get_the_post_thumbnail_url($course_id);
        if ($featuredImage): ?>
            <img src="<?php echo esc_url($featuredImage); ?>" alt="<?php echo esc_attr(get_the_title($course_id)); ?>">
        <?php endif; 
        
        $course_code = get_field('course_code', $course_id);
        if ($course_code): ?>
            <div class="course-code"><?php echo esc_html($course_code); ?></div>
        <?php endif; 

        $course_highlight = get_field('course_highlight', $course_id);
        if ($course_highlight): ?>
            <div class="c-highlight">
                <div class="course-highlight"><?php echo esc_html($course_highlight); ?></div>
            </div>
        <?php endif; ?>
    </div>
    <div class="course-info">
        <h4><?php echo esc_html(get_the_title($course_id)); ?></h4>
        <?php 
        $excerpt = get_the_excerpt($course_id);
        if ($excerpt): ?>
            <div class="course-det">
                <?php echo wp_kses_post($excerpt); ?>
            </div>
        <?php endif; ?>
        <a href="<?php echo esc_url(get_permalink($course_id)); ?>" class="button button-secondary">Discover More</a>
    </div>
</div>