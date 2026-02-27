<?php 
/**
* Title: Course Information List
* Description: Course Information List Section
* Category: layout
* Icon: list-view
* Keywords: course-information-list
* SupportsAlign: false
* Mode: edit
* PostTypes: sa-course
*/
?>

<?php
// Post-level ACF field from Course Fields group
$course_info       = get_field( 'course_group', get_the_ID() );
$course_info_items = isset( $course_info['course_info'] ) ? $course_info['course_info'] : array();
$course_conditions = isset( $course_info['course_conditions'] ) ? $course_info['course_conditions'] : '';

$extra_class_name = ! empty( $block['className'] ) ? ' ' . esc_attr( $block['className'] ) : '';

// Define labels for info_selection dropdown options
$info_labels = array(
	'next-intake'          => 'Next Intake',
	'crs-duration'         => 'Course Duration',
	'crs-delivery'         => 'Delivery Method',
	'crs-funded-fee'       => 'Funded Course fee',
	'crs-full-fee'         => 'Full Fee',
	'crs-incidental-fee'   => 'Incidental Fees',
	'crs-fee-inc'          => 'Fee inclusion',
	'crs-unitsof comp'     => 'Units of Competency',
	'crs-contact-number'   => 'Contact Number',
	'crs-location'         => 'Location',
	'crs-custom'           => 'Custom Title',
);
?>

<div data-block-id="<?php echo esc_attr( $block['id'] ); ?>" class="block-content course-information-list-block<?php echo $extra_class_name; ?>">
	<div class="block-inner flex-container">
		<div class="course-information-list-content">
			<?php if ( ! empty( $course_info_items ) ) : ?>
				<table class="course-info-table">
					<tbody>
						<?php foreach ( $course_info_items as $item ) : ?>
							<?php
							$info_selection = isset( $item['info_selection'] ) ? $item['info_selection'] : '';
							$info_title     = isset( $item['info_title'] ) ? $item['info_title'] : '';
							$info_details   = isset( $item['info_details'] ) ? $item['info_details'] : '';

							// Skip if no details provided
							if ( empty( $info_details ) ) {
								continue;
							}

							// Determine the title to display
							$display_title = '';

							// If info_selection is an array (return_format: array), get the label
							if ( is_array( $info_selection ) ) {
								$selection_value = isset( $info_selection['value'] ) ? $info_selection['value'] : '';
								$selection_label = isset( $info_selection['label'] ) ? $info_selection['label'] : '';
							} else {
								$selection_value = $info_selection;
								$selection_label = isset( $info_labels[ $info_selection ] ) ? $info_labels[ $info_selection ] : '';
							}

							// For 'crs-custom', always use info_title
							if ( 'crs-custom' === $selection_value ) {
								$display_title = $info_title;
							} elseif ( ! empty( $info_title ) ) {
								$display_title = $info_title;
							} elseif ( ! empty( $selection_label ) ) {
								$display_title = $selection_label;
							}

							// Skip if no title to display
							if ( empty( $display_title ) ) {
								continue;
							}
							?>
							<tr>
								<th scope="row"><?php echo esc_html( $display_title ); ?></th>
								<td><?php echo nl2br( esc_html( $info_details ) ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<?php if ( ! empty( $course_conditions ) ) : ?>
					<div class="course-conditions">
						<?php echo esc_html( $course_conditions ); ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
</div>
