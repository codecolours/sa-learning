<?php 
/**
* Title: Course Elective Units List
* Description: Course Elective Units List Section
* Category: layout
* Icon: list-view
* Keywords: course-elective-units-list
* SupportsAlign: false
* Mode: edit
* PostTypes: sa-course
*/
?>

<?php
$block_title = get_field( 'block_title' );

// Post-level ACF field from Course Fields group
$course_info   = get_field( 'course_group', get_the_ID() );
$elective_unit = isset( $course_info['elective_unit'] ) ? $course_info['elective_unit'] : array();

// Count total elective unit groups
$total_count = ! empty( $elective_unit ) ? count( $elective_unit ) : 0;

$extra_class_name = ! empty( $block['className'] ) ? ' ' . esc_attr( $block['className'] ) : '';
?>

<div data-block-id="<?php echo esc_attr( $block['id'] ); ?>" class="block-content course-elective-units-list-block<?php echo $extra_class_name; ?>">
	<div class="block-inner flex-container">
		<div class="course-elective-units-list-content">
			<?php if ( $block_title ) : ?>
				<h4><?php echo esc_html( $block_title ); ?> <?php echo $total_count > 0 ? '(' . esc_html( $total_count ) . ')' : ''; ?></h4>
			<?php endif; ?>

			<?php if ( ! empty( $elective_unit ) ) : ?>
				<div class="elective-units-wrapper">
					<?php foreach ( $elective_unit as $unit ) : ?>
						<?php
						$elective_unit_title = isset( $unit['elective_unit_title'] ) ? $unit['elective_unit_title'] : '';
						$elective_unit_items = isset( $unit['elective_unit_items'] ) ? $unit['elective_unit_items'] : array();
						
						// Skip if no title or no items
						if ( empty( $elective_unit_title ) || empty( $elective_unit_items ) ) {
							continue;
						}
						?>
						<div class="elective-unit-group">
							<div class="elective-unit-title"><?php echo esc_html( $elective_unit_title ); ?></div>
							<ul class="elective-units-list">
								<?php foreach ( $elective_unit_items as $item ) : ?>
									<?php
									$elective_unit_item = isset( $item['elective_unit_item'] ) ? $item['elective_unit_item'] : '';
									
									// Skip empty items
									if ( empty( $elective_unit_item ) ) {
										continue;
									}
									?>
									<li><?php echo esc_html( $elective_unit_item ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
