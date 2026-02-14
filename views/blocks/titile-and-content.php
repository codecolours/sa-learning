<?php 
/**
* Title: Title and Content
* Description: Title and Content
* Category: layout
* Icon: text-page
* Keywords: title-content
* SupportsAlign: false
* Mode: edit
* PostTypes: page post
*/
?>

<?php 
$sectionTitle   = get_field( 'section_title' );
$titleType      = get_field( 'title_type' );
$blockContent   = get_field( 'block-content' );
$extraClassName = !empty($block['className']) ? ' ' . esc_attr($block['className']) : '';
?>


<div data-block-id="<?php echo esc_attr($block['id']); ?>" class="block-content title-content-block<?php echo $extraClassName; ?>">
    <div class="block-inner flex-container">
        <div class="item">
            <?php 
            if ( $sectionTitle ): ?>
                <div class="title-wrapper">
                    <?php echo ($titleType ? "<h3>" : "<h2>") . esc_html( $sectionTitle ) . ($titleType ? "</h3>" : "</h2>"); ?>
                </div>
            <?php
            endif; ?>
            <?php 
            if ($blockContent): ?>
                <div class="content-wrapper">
                    <?php echo $blockContent; ?>
                </div>
            <?php 
            endif;  ?>
        </div>
    </div>
</div>




