// Import the libraries
import jQuery from 'jquery';
import { initNumberCounters } from './number-counter';
import { initCourseFilter } from './course-filter';
import { initPostFilter } from './post-filter';
import { initFooterAccordion } from './footer-accordion';


// Wait for DOM to be ready
jQuery(document).ready(function($) {
    // Initialize number counters if they exist on the page
    initNumberCounters();
    
    // Initialize course filtering if filter exists on the page
    initCourseFilter();
    
    // Initialize post filtering with AJAX if filter exists on the page
    initPostFilter();
    
    // Initialize footer accordion for mobile devices
    initFooterAccordion();
});