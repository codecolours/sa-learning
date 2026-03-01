import jQuery from 'jquery';

/**
 * Initialize event listing functionality with AJAX
 */
export function initEventListing() {
    const $ = jQuery;

    // Check if event listing block exists
    const $eventBlock = $('.event-listing-block');
    if (!$eventBlock.length) {
        return;
    }

    $eventBlock.each(function() {
        const $block = $(this);
        const $itemsWrapper = $block.find('.items-wrapper');
        const $loadMoreBtn = $block.find('.load-more-btn');
        const blockId = $block.data('block-id');
        const postsPerPage = parseInt($block.data('posts-per-page')) || 6;
        const eventType = parseInt($block.data('event-type')) || 0;
        let currentPage = 1;
        let isLoading = false;

        // Handle load more button click
        $loadMoreBtn.on('click', function(e) {
            e.preventDefault();
            
            if (isLoading) return;
            
            currentPage++;
            loadEvents();
        });

        /**
         * Load events via AJAX
         */
        function loadEvents() {
            isLoading = true;
            
            // Show loading state
            $loadMoreBtn.addClass('loading').text('Loading...');

            $.ajax({
                url: window.saLearningAjax?.ajaxurl || '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'load_more_events',
                    page: currentPage,
                    posts_per_page: postsPerPage,
                    event_type: eventType,
                    block_id: blockId
                },
                success: function(response) {
                    if (response.success) {
                        $itemsWrapper.append(response.data.html);
                        $loadMoreBtn.removeClass('loading').html('Load More <span>+</span>');

                        // Hide load more button if no more events
                        if (!response.data.has_more) {
                            $loadMoreBtn.hide();
                        }
                    } else {
                        console.error('Error loading events:', response.data.message);
                        $loadMoreBtn.removeClass('loading').html('Load More <span>+</span>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    $loadMoreBtn.removeClass('loading').html('Load More <span>+</span>');
                },
                complete: function() {
                    isLoading = false;
                }
            });
        }
    });
}
