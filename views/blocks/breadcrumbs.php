<?php 
/**
* Title: Breadcrumbs
* Description: Breadcrumbs
* Category: layout
* Icon: editor-ul
* Keywords: breadcrumbs
* SupportsAlign: false
* Mode: edit
* PostTypes: page post
*/
?>

<?php 
$extraClassName = !empty($block['className']) ? ' ' . esc_attr($block['className']) : '';
?>


<div data-block-id="<?php echo esc_attr($block['id']); ?>" class="block-content breadcrumbs-block<?php echo $extraClassName; ?>">
    <div class="block-inner flex-container">
        <?php 
        if (function_exists('rank_math_the_breadcrumbs')) {
            rank_math_the_breadcrumbs();
        } elseif (function_exists('rank_math_get_breadcrumbs')) {
            echo wp_kses_post(rank_math_get_breadcrumbs());
        } else {
            // Fallback if Rank Math is not active
            echo '<p class="breadcrumb-notice">Breadcrumbs require Rank Math SEO plugin to be active.</p>';
        }
        ?>
    </div>
</div>




