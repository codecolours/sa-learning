<?php 
/**
* Title: Course Core Units List
* Description: Course Core Units List Section
* Category: layout
* Icon: list-view
* Keywords: course-core-units-list
* SupportsAlign: false
* Mode: edit
* PostTypes: sa-course
*/
?>

<?php
$block_title = get_field( 'block_title' );

// Post-level ACF field from Course Fields group
$course_info = get_field( 'course_group', get_the_ID() );
$core_units  = isset( $course_info['core_units'] ) ? $course_info['core_units'] : array();

// Count total items
$total_count = ! empty( $core_units ) ? count( $core_units ) : 0;

$extra_class_name = ! empty( $block['className'] ) ? ' ' . esc_attr( $block['className'] ) : '';
?>

<div data-block-id="<?php echo esc_attr( $block['id'] ); ?>" class="block-content course-core-units-list-block<?php echo $extra_class_name; ?>">
	<div class="block-inner flex-container">
		<div class="course-core-units-list-content">
			<?php if ( $block_title ) : ?>
				<h4><?php echo esc_html( $block_title ); ?> <?php echo $total_count > 0 ? '(' . esc_html( $total_count ) . ')' : ''; ?></h4>
			<?php endif; ?>

			<?php if ( ! empty( $core_units ) ) : ?>
				<ul class="core-units-list">
					<?php foreach ( $core_units as $unit ) : ?>
						<?php
						$core_unit_item = isset( $unit['core_unit_item'] ) ? $unit['core_unit_item'] : '';
						
						// Skip empty items
						if ( empty( $core_unit_item ) ) {
							continue;
						}
						?>
						<li><?php echo esc_html( $core_unit_item ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</div>
