<?php 
/**
* Title: Posts Listing
* Description: Posts Listing
* Category: layout
* Icon: admin-generic
* Keywords: posts-listing
* SupportsAlign: false
* Mode: edit
* PostTypes: page
*/
?>

<?php 
$noOfPosts      = get_field( 'number_of_posts' ) ?: 6;
$addFilters     = get_field( 'add_filters' );
$enPagination   = get_field( 'enable_pagination' );
$extraClassName = !empty($block['className']) ? ' ' . esc_attr($block['className']) : '';

// Always respect the number of posts field
// The pagination toggle only controls the "Load More" button visibility
$postsPerPage = $noOfPosts;
?>


<div data-block-id="<?php echo esc_attr($block['id']); ?>" 
     data-posts-per-page="<?php echo esc_attr($postsPerPage); ?>" 
     class="block-content posts-listing-block<?php echo $extraClassName; ?>">
    
    <?php if ($addFilters): ?>
        <div class="filters-wrapper">
            <div class="filter-item">
                <ul class="filter-list">
                    <li class="filter-item active">
                        <a data-filter="all">All</a>
                    </li>
                    <?php 
                    $categories = get_terms(array(
                        'taxonomy' => 'category',
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
    
    <div class="block-inner items-wrapper flex-container">
        <?php
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $postsPerPage,
            'post_status' => 'publish',
        );
        
        $query = new WP_Query($args);

        if ($query->have_posts()): 
            while ($query->have_posts()): 
                $query->the_post();
                set_query_var('postID', get_the_ID());
                get_template_part('views/loop-templates/post-card');
            endwhile;
            wp_reset_postdata();
        else: ?>
            <p class="no-posts-found">No posts found!</p>
        <?php endif; ?>
    </div>
    
    <?php if ($enPagination && $query->max_num_pages > 1): ?>
        <div class="load-more-wrapper">
            <button class="button button-primary load-more-btn">Load More <span>+</span></button>
        </div>
    <?php endif; ?>
</div>




