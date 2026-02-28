import jQuery from 'jquery';

export function initFooterAccordion() {
    const $ = jQuery;
    
    // Only run on mobile devices (under 781px)
    if ($(window).width() < 781) {
        $('.site-footer .widget .widget-title').on('click', function() {
            const $title = $(this);
            const $widget = $title.closest('.widget');
            const $menuContainer = $widget.find('> div[class*="menu-"]');
            
            // Toggle active class on title
            $title.toggleClass('active');
            
            // Slide toggle the menu container
            $menuContainer.slideToggle(300);
        });
    }
    
    // Handle window resize to reset accordion behavior
    $(window).on('resize', function() {
        const windowWidth = $(window).width();
        
        if (windowWidth >= 781) {
            // Show all menus on desktop
            $('.site-footer .widget > div[class*="menu-"]').show();
            $('.site-footer .widget .widget-title').removeClass('active');
        } else {
            // Hide all menus on mobile (unless already toggled)
            $('.site-footer .widget > div[class*="menu-"]').each(function() {
                const $container = $(this);
                const $title = $container.siblings('.widget-title');
                
                if (!$title.hasClass('active')) {
                    $container.hide();
                }
            });
        }
    });
}
