<?php 
/**
* Title: Number Counter
* Description: Number Counter
* Category: layout
* Icon: numbers
* Keywords: number-counter
* SupportsAlign: false
* Mode: edit
* PostTypes: page
*/
?>

<?php 
$setNumber = get_field( 'set_number' );
$incrementNumber = get_field( 'increment_number' );
$textToAdd = get_field( 'text_to_add' );
$extraClassName = !empty($block['className']) ? ' ' . esc_attr($block['className']) : '';
?>


<div data-block-id="<?php echo esc_attr($block['id']); ?>" class="block-content number-counter-block<?php echo $extraClassName; ?>">
    <div class="block-inner flex-container">
        <div class="number-wrapper">
            <span class="set-number" data-target="<?php echo esc_attr($setNumber); ?>" data-increment="<?php echo esc_attr($incrementNumber); ?>">0</span>
            <span class="number-text"><?php echo esc_html($textToAdd); ?></span>
        </div>
    </div>
</div>
