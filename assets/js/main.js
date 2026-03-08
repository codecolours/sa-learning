// Import the libraries
import jQuery from 'jquery';
import { initNumberCounters } from './number-counter';
import { initCourseFilter } from './course-filter';
import { initPostFilter } from './post-filter';
import { initEventListing } from './event-listing';
import { initFooterAccordion } from './footer-accordion';
import { initCategoryFeaturedSlider } from './category-featured-slider';
import { initVideoBox } from './video-box';

// Wait for DOM to be ready
jQuery(document).ready(function($) {
    // Initialize number counters if they exist on the page
    initNumberCounters();
    
    // Initialize course filtering if filter exists on the page
    initCourseFilter();
    
    // Initialize post filtering with AJAX if filter exists on the page
    initPostFilter();
    
    // Initialize event listing with AJAX if exists on the page
    initEventListing();
    
    // Initialize footer accordion for mobile devices
    initFooterAccordion();
    
    // Initialize category featured slider
    initCategoryFeaturedSlider();

    // Initialize video box
    initVideoBox();
});