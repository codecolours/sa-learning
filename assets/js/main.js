// Import the libraries
import jQuery from 'jquery';
import { initNumberCounters } from './number-counter';


// Wait for DOM to be ready
jQuery(document).ready(function($) {
    // Initialize number counters if they exist on the page
    initNumberCounters();
});