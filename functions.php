<?php 
/**
 * Main Functions File.
 *
 * @package sa-learning
*/

/*  loading all inc files */
$files = glob( dirname( __FILE__ ) . '/inc/*.php' );
foreach ( $files as $file ) {
    if ( ! file_exists( $file ) ) {
        continue; 
    }

    require_once $file;
}

// function custom_neve_copyright_text() {
//     return 'Your Custom Copyright Text © ' . date('Y') . ' | All Rights Reserved';
// }
// add_filter('hfg_footer_render', 'custom_neve_copyright_text');

// add_action('init', function() {
//     // This will help us identify the correct hook
//     add_action('wp_footer', function() {
//         global $wp_filter;
//         print_r(array_keys($wp_filter));
//     }, 999);
// });

function append_custom_copyright_text($copyright_text) {
    // Option 1: Append text to existing customizer text
    $custom_addition = ' | Your Additional Text Here';
    return $copyright_text . $custom_addition;
}
add_filter('neve_filter_copyright_text', 'append_custom_copyright_text');

// Alternative method if filter doesn't exist
function manual_copyright_text_append() {
    // Get the original text from theme customizer
    $original_text = get_theme_mod('neve_copyright_text', '');
    
    // Append your custom text
    $modified_text = $original_text . ' | Your Additional Text Here';
    
    echo $modified_text;
}
add_action('neve_footer_copyright', 'manual_copyright_text_append');

/**
 * Fix ThemeIsle SDK script URLs that may contain absolute Windows paths
 */
function sa_learning_fix_sdk_script_urls() {
    add_filter('script_loader_src', function($src, $handle) {
        if (strpos($handle, 'themeisle_sdk_') === 0 || strpos($src, 'themeisle-sdk') !== false) {
            $src = preg_replace('#/wp-content/plugins/[A-Za-z]:/.*?/wp-content/#', '/wp-content/', $src);
        }
        return $src;
    }, 10, 2);
}
add_action('init', 'sa_learning_fix_sdk_script_urls', 1);