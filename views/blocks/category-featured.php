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
?>


<div data-block-id="<?php echo esc_attr($block['id']); ?>" class="block-content category-featured-block<?php echo $extraClassName; ?>">
    <div class="block-inner flex-container">
       <?php 
       
       if ($selectedCategories): 
            foreach ($selectedCategories as $category): 
                $categoryID = $category->term_id;
                $categoryName = $category->name;
                $categoryDescription = $category->description;
                $categoryImage = get_field('featured_image', 'course-category_' . $categoryID);
                $categoryLink = get_term_link($categoryID);
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
                    <?php 
                        $courses = get_posts(array(
                            'post_type' => 'sa-course',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'course-category',
                                    'field' => 'term_id',
                                    'terms' => $categoryID,
                                ),
                            ),
                        ));
                    if ($courses): ?>
                        <div class="courses-wrapper">
                            <h4>Courses</h4>
                            <ul class="courses-list">
                                <?php foreach ($courses as $course): ?>
                                    <li class="course-item">
                                        <a href="<?php echo esc_url(get_permalink($course->ID)); ?>"><?php echo esc_html($course->post_title); ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($categoryImage): ?>
                    <div class="featured-image">
                        <img src="<?php echo esc_url($categoryImage['url']); ?>" alt="<?php echo esc_attr($categoryName); ?>">
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




