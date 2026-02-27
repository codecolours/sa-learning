import jQuery from 'jquery';

/**
 * Initialize post filtering functionality with AJAX
 */
export function initPostFilter() {
    const $ = jQuery;

    // Check if post listing block exists
    const $postBlock = $('.posts-listing-block');
    if (!$postBlock.length) {
        return;
    }

    $postBlock.each(function() {
        const $block = $(this);
        const $itemsWrapper = $block.find('.items-wrapper');
        const $loadMoreBtn = $block.find('.load-more-btn');
        const blockId = $block.data('block-id');
        const postsPerPage = parseInt($block.data('posts-per-page')) || 6;
        let currentPage = 1;
        let currentFilter = 'all';
        let isLoading = false;

        // Handle filter clicks
        $block.find('.filter-item a').on('click', function(e) {
            e.preventDefault();
            
            if (isLoading) return;
            
            const $this = $(this);
            currentFilter = $this.data('filter');
            currentPage = 1;
            
            // Update active state
            $this.closest('.filter-list').find('.filter-item').removeClass('active');
            $this.closest('.filter-item').addClass('active');
            
            // Load filtered posts
            loadPosts(true);
        });

        // Handle load more button click
        $loadMoreBtn.on('click', function(e) {
            e.preventDefault();
            
            if (isLoading) return;
            
            currentPage++;
            loadPosts(false);
        });

        /**
         * Load posts via AJAX
         * @param {boolean} replace - Whether to replace existing posts or append
         */
        function loadPosts(replace = false) {
            isLoading = true;
            
            // Show loading state
            if (replace) {
                $itemsWrapper.css('opacity', '0.5');
            } else {
                $loadMoreBtn.addClass('loading').text('Loading...');
            }

            $.ajax({
                url: window.saLearningAjax?.ajaxurl || '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'filter_posts',
                    category: currentFilter,
                    page: currentPage,
                    posts_per_page: postsPerPage,
                    block_id: blockId
                },
                success: function(response) {
                    if (response.success) {
                        if (replace) {
                            $itemsWrapper.html(response.data.html);
                            $itemsWrapper.css('opacity', '1');
                        } else {
                            $itemsWrapper.append(response.data.html);
                            $loadMoreBtn.removeClass('loading').html('Load More <span>+</span>');
                        }

                        // Hide/show load more button based on whether there are more posts
                        if (response.data.has_more) {
                            $loadMoreBtn.show();
                        } else {
                            $loadMoreBtn.hide();
                        }

                        // Show/hide no posts message
                        if (response.data.total === 0) {
                            $itemsWrapper.html('<p class="no-posts-found">No posts found!</p>');
                            $loadMoreBtn.hide();
                        }
                    } else {
                        console.error('Error loading posts:', response.data.message);
                        if (replace) {
                            $itemsWrapper.css('opacity', '1');
                        } else {
                            $loadMoreBtn.removeClass('loading').html('Load More <span>+</span>');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    if (replace) {
                        $itemsWrapper.css('opacity', '1');
                    } else {
                        $loadMoreBtn.removeClass('loading').html('Load More <span>+</span>');
                    }
                },
                complete: function() {
                    isLoading = false;
                }
            });
        }
    });
}
