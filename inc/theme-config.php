<?php
/**
 * Theme main configs
 *
 * @package sa-learning
*/

add_theme_support( 'editor-styles' );
add_editor_style( 'assets/dist/css/editor-style.css' );

function site_enqueue_scripts() {

	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'sa-learning-theme', get_stylesheet_directory_uri() . '/assets/dist/css/style.css', null, '1.0.0', 'all' );
    wp_enqueue_script( 'sa-learning-script', get_stylesheet_directory_uri() . '/assets/dist/js/main.js', array( 'jquery' ), '1.0.', true );    

    // Enqueue Font Awesome (check if not already loaded by Otter plugin)
    if ( ! wp_style_is( 'font-awesome-5', 'enqueued' ) ) {
        wp_enqueue_style( 'font-awesome-5', '/wp-content/plugins/otter-blocks/assets/fontawesome/css/all.min.css', array(), null );
    }

    // Localize the script with new data
    // wp_localize_script( 'sa-learning-script', 'tmeAjaxURL', array(
	// 	'ajaxurl' => esc_url(get_admin_url()) . 'admin-ajax.php', 
	// ));

}
add_action( 'wp_enqueue_scripts', 'site_enqueue_scripts', 20 );

//Links & Buttons
function siteButton( $button, $class = 'button-primary') {
    if ( !empty( $button['url'] ) && !empty( $button['title'] ) ) {
        $link_target = !empty( $button['target'] ) ? esc_attr( $button['target'] ) : '_self';
        ?>
        <a class="button <?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo $link_target; ?>">
            <?php echo esc_html( $button['title'] ); ?>
        </a>
        <?php
    }
}

function append_website_by() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var copyrightElement = document.querySelector('.builder-item--footer_copyright .component-wrap div');
        if (copyrightElement) {
            copyrightElement.innerHTML += ' | Website by <a target="_blank" href="https://www.boylen.com.au">Boylen</a>';
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'append_website_by');