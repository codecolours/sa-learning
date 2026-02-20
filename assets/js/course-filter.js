import jQuery from 'jquery';

/**
 * Initialize course filtering functionality
 */
export function initCourseFilter() {
    const $ = jQuery;

    // Check if course listing block exists
    const $courseBlock = $('.course-listing-block');
    if (!$courseBlock.length) {
        return;
    }

    // Handle filter clicks
    $('.course-listing-block .filter-item a').on('click', function(e) {
        e.preventDefault();
        
        const $this = $(this);
        const filter = $this.data('filter');
        const $courseWrapper = $this.closest('.course-listing-block').find('.courses-wrapper');
        const $courses = $courseWrapper.find('.course-item');
        
        // Update active state
        $this.closest('.filter-list').find('.filter-item').removeClass('active');
        $this.closest('.filter-item').addClass('active');
        
        // Filter courses
        if (filter === 'all') {
            $courses.fadeIn(300);
        } else {
            $courses.each(function() {
                const $course = $(this);
                const categories = $course.data('category').toString().split(' ');
                
                if (categories.includes(filter)) {
                    $course.fadeIn(300);
                } else {
                    $course.fadeOut(300);
                }
            });
        }
    });
}
