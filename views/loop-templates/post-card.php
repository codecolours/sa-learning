<?php
/**
 * Post Card
 *
 * @package sa-learning
 */
?>
<?php 
$post_id = get_query_var('postID');
if (!$post_id) {
    return;
}

?>
<div class="post-card col-4">
    <div class="item-img">
        <?php 
        $featuredImage = get_the_post_thumbnail_url($post_id);
        if ($featuredImage): ?>
            <a href="<?php echo esc_url(get_permalink($post_id)); ?>">
				<img src="<?php echo esc_url($featuredImage); ?>" alt="<?php echo esc_attr(get_the_title($post_id)); ?>">
			</a>
        <?php endif; 

		$postCategory = get_the_terms($post_id, 'category');

		if ($postCategory && !is_wp_error($postCategory) && !empty($postCategory)) : 
			$category_names = wp_list_pluck($postCategory, 'name');
			?>
			<div class="post-category">
				<?php echo esc_html(implode(' | ', $category_names)); ?>
			</div>
		<?php endif; ?>
    </div>
    <div class="item-info">
        <h4><?php echo esc_html(get_the_title($post_id)); ?></h4>
		<?php 
		$post_date = get_the_date('', $post_id);
		if ($post_date): ?>
			<div class="post-date">
				<?php echo date('j F Y', strtotime($post_date)); ?>
			</div>
		<?php endif; 
		$excerpt = get_the_excerpt($post_id);
		if ($excerpt): ?>
			<div class="item-det">
				<?php echo wp_kses_post($excerpt); ?>
			</div>
		<?php endif; ?>
        <a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="button button-secondary">Read More</a>
    </div>
</div>