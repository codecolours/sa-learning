import jQuery from 'jquery';

/**
 * Initialize course filtering functionality with AJAX
 */
export function initCourseFilter() {
    const $ = jQuery;

    // Check if course listing block exists
    const $courseBlock = $('.course-listing-block');
    if (!$courseBlock.length) {
        return;
    }

    $courseBlock.each(function() {
        const $block = $(this);
        const $itemsWrapper = $block.find('.items-wrapper');
        const blockId = $block.data('block-id');
        const coursesPerPage = parseInt($block.data('courses-per-page')) || -1;
        const filterByCategories = $block.data('filter-by-categories') || '';
        let currentFilter = 'all';
        let isLoading = false;

        // Handle filter clicks
        $block.find('.filter-item a').on('click', function(e) {
            e.preventDefault();
            
            if (isLoading) return;
            
            const $this = $(this);
            currentFilter = $this.data('filter');
            
            // Update active state
            $this.closest('.filter-list').find('.filter-item').removeClass('active');
            $this.closest('.filter-item').addClass('active');
            
            // Load filtered courses
            loadCourses();
        });

        /**
         * Load courses via AJAX
         */
        function loadCourses() {
            isLoading = true;
            
            // Show loading state
            $itemsWrapper.css('opacity', '0.5');

            $.ajax({
                url: window.saLearningAjax?.ajaxurl || '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'filter_courses',
                    category: currentFilter,
                    courses_per_page: coursesPerPage,
                    filter_by_categories: filterByCategories,
                    block_id: blockId
                },
                success: function(response) {
                    if (response.success) {
                        $itemsWrapper.html(response.data.html);
                        $itemsWrapper.css('opacity', '1');

                        // Show/hide no courses message
                        if (response.data.total === 0) {
                            $itemsWrapper.html('<p class="no-courses-found">No courses found!</p>');
                        }
                    } else {
                        console.error('Error loading courses:', response.data.message);
                        $itemsWrapper.css('opacity', '1');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    $itemsWrapper.css('opacity', '1');
                },
                complete: function() {
                    isLoading = false;
                }
            });
        }
    });
}
