<?php 
/**
* Title: Course Featured Header
* Description: Course Featured Header Section
* Category: layout
* Icon: text-page
* Keywords: course-featured-section
* SupportsAlign: false
* Mode: edit
* PostTypes: sa-course
*/
?>

<?php
// Block-level ACF fields
$hide_course_code = get_field( 'hide_course_code' );
$title_tag        = get_field( 'title_tag' );
$title            = get_field( 'title' );

// Post-level ACF field from Course Fields group
$course_code = get_field( 'course_code', get_the_ID() );

$extra_class_name = ! empty( $block['className'] ) ? ' ' . esc_attr( $block['className'] ) : '';

// Validate and sanitize title tag for security
$allowed_heading_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
$title_tag            = ( $title_tag && in_array( strtolower( $title_tag ), $allowed_heading_tags, true ) ) ? strtolower( $title_tag ) : 'h2';
?>

<div data-block-id="<?php echo esc_attr( $block['id'] ); ?>" class="block-content course-featured-header-block<?php echo $extra_class_name; ?>">
	<div class="block-inner flex-container">
		<div class="course-featured-header-content">
			<?php if ( ! $hide_course_code && $course_code ) : ?>
				<div class="course-code">
					<?php echo esc_html( $course_code ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $title ) : ?>
				<<?php echo esc_attr( $title_tag ); ?>><?php echo esc_html( $title ); ?></<?php echo esc_attr( $title_tag ); ?>>
			<?php endif; ?>
		</div>
	</div>
</div>
