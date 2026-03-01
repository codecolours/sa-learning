import jQuery from 'jquery';

/**
 * Initialize course filtering functionality with AJAX and client-side caching
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
        const filterCache = {};

        // Cache the initial "all" filter state - ensure we get the actual content
        const initialHtml = $itemsWrapper.html();
        if (initialHtml && initialHtml.trim().length > 0) {
            filterCache['all'] = initialHtml;
        }

        // Handle filter clicks
        $block.find('.filter-item a').on('click', function(e) {
            e.preventDefault();
            
            if (isLoading) return;
            
            const $this = $(this);
            const newFilter = $this.data('filter');
            
            // Don't reload if clicking the same filter
            if (newFilter === currentFilter) return;
            
            currentFilter = newFilter;
            
            // Update active state
            $this.closest('.filter-list').find('.filter-item').removeClass('active');
            $this.closest('.filter-item').addClass('active');
            
            // Load filtered courses
            loadCourses();
        });

        /**
         * Load courses via AJAX with client-side cache
         */
        function loadCourses(silent = false, filterOverride = null) {
            const targetFilter = filterOverride || currentFilter;
            
            // Check if this filter result is already cached and valid
            if (filterCache[targetFilter] && filterCache[targetFilter].trim().length > 0) {
                if (!silent) {
                    showLoadingState();
                    
                    // Small delay to show smooth transition even for cached results
                    setTimeout(() => {
                        const cachedHtml = filterCache[targetFilter];
                        $itemsWrapper.html(cachedHtml);
                        hideLoadingState();
                    }, 150);
                }
                return;
            }
            
            isLoading = true;
            
            // Show loading state only if not silent
            if (!silent) {
                showLoadingState();
            }

            $.ajax({
                url: window.saLearningAjax?.ajaxurl || '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'filter_courses',
                    category: targetFilter,
                    courses_per_page: coursesPerPage,
                    filter_by_categories: filterByCategories,
                    block_id: blockId
                },
                success: function(response) {
                    if (response.success) {
                        let html = response.data.html || '';
                        
                        // If no HTML returned, show "no courses found" message
                        if (!html || html.trim().length === 0) {
                            html = '<p class="no-courses-found">No courses found!</p>';
                        }
                        
                        // Cache the result with the correct filter key
                        filterCache[targetFilter] = html;
                        
                        // Only update display if this is for the current active filter and not silent
                        if (!silent && targetFilter === currentFilter) {
                            $itemsWrapper.html(html);
                            hideLoadingState();
                        }
                    } else {
                        if (!silent) {
                            console.error('Error loading courses:', response.data?.message || 'Unknown error');
                            hideLoadingState();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    if (!silent) {
                        console.error('AJAX error:', error);
                        hideLoadingState();
                    }
                },
                complete: function() {
                    isLoading = false;
                }
            });
        }

        /**
         * Show loading state with animation
         */
        function showLoadingState() {
            // Add loading class to wrapper
            $itemsWrapper.addClass('is-loading');
            
            // Create and show loading overlay if it doesn't exist
            if (!$block.find('.course-loading-overlay').length) {
                const loadingOverlay = `
                    <div class="course-loading-overlay">
                        <div class="loading-spinner">
                            <div class="spinner"></div>
                        </div>
                    </div>
                `;
                $itemsWrapper.append (loadingOverlay);
            }
            
            $block.find('.course-loading-overlay').fadeIn(200);
        }

        /**
         * Hide loading state
         */
        function hideLoadingState() {
            $itemsWrapper.removeClass('is-loading');
            $block.find('.course-loading-overlay').fadeOut(200, function() {
                $(this).remove();
            });
        }

    });
}
