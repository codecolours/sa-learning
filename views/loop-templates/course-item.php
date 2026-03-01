<?php
/**
 * Course Item
 *
 * @package sa-learning
 */
?>
<?php 
// Check if we have prepared data (for cached/optimized rendering)
$courseData = get_query_var('courseData');

if ($courseData) {
    // Use prepared data from cache
    $course_id = $courseData['id'];
    $course_title = $courseData['title'];
    $course_excerpt = $courseData['excerpt'];
    $course_permalink = $courseData['permalink'];
    $course_thumbnail = $courseData['thumbnail'];
    $course_code = $courseData['course_code'];
    $course_highlight = $courseData['course_highlight'];
} else {
    // Fallback to fetching from database (traditional WordPress loop)
    $course_id = get_query_var('courseID');
    if (!$course_id) {
        return;
    }
    
    $course_title = get_the_title($course_id);
    $course_excerpt = get_the_excerpt($course_id);
    $course_permalink = get_permalink($course_id);
    $course_thumbnail = get_the_post_thumbnail_url($course_id);
    $course_code = get_field('course_code', $course_id);
    $course_highlight = get_field('course_highlight', $course_id);
}
?>
<div class="list-item col-4">
    <div class="item-img">
        <?php if ($course_thumbnail): ?>
            <a href="<?php echo esc_url($course_permalink); ?>">
                <img src="<?php echo esc_url($course_thumbnail); ?>" 
                     alt="<?php echo esc_attr($course_title); ?>" 
                     class="img-fluid"
                     loading="lazy">
            </a>
        <?php endif; 
        
        if ($course_code): ?>
            <div class="course-code"><?php echo esc_html($course_code); ?></div>
        <?php endif; 

        if ($course_highlight): ?>
            <div class="c-highlight">
                <div class="course-highlight"><?php echo esc_html($course_highlight); ?></div>
            </div>
        <?php endif; ?>
    </div>
    <div class="item-info">
        <h4><?php echo esc_html($course_title); ?></h4>
        <?php if ($course_excerpt): ?>
            <div class="item-det">
                <?php echo wp_kses_post($course_excerpt); ?>
            </div>
        <?php endif; ?>
        <a href="<?php echo esc_url($course_permalink); ?>" class="button button-secondary">Discover More</a>
    </div>
</div>