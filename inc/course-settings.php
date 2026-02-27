<?php 

/**
 * Course Settings
 *
 * @package SA Learning
 */

/**
 * Add header banner after main header
 */
function sa_learning_header_banner() {
	// Only show on single sa-course pages
	if ( ! is_singular( 'sa-course' ) ) {
		return;
	}

	// Check if banner should be removed
	$remove_bnner = get_field( 'remove_bnner' );
	if ( $remove_bnner ) {
		return;
	}

	// Get all field values
	$page_banner        = get_field( 'page_banner' );
	$banner_title       = get_field( 'banner_title' );
	$title_tag          = get_field( 'title_tag' );
	$banner_description = get_field( 'banner_description' );
	$course_code        = get_field( 'course_code' );
	$course_highlight   = get_field( 'course_highlight' );

	// Check if at least one field has content
	if ( ! $page_banner && ! $banner_title && ! $banner_description && ! $course_code && ! $course_highlight ) {
		return;
	}

	// Validate and sanitize title tag for security
	$allowed_heading_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
	$title_tag            = ( $title_tag && in_array( strtolower( $title_tag ), $allowed_heading_tags, true ) ) ? strtolower( $title_tag ) : 'h1';
	?>
	<div class="course-banner-container">
		<?php if ( $page_banner ) : ?>
			<div class="course-banner">
				<?php
				echo wp_get_attachment_image(
					$page_banner,
					'full',
					false,
					array(
						'alt'     => $banner_title ? esc_attr( $banner_title ) : esc_attr( get_the_title() ),
						'loading' => 'eager',
					)
				);
				?>
			</div>
		<?php endif; ?>

		<?php if ( $course_code ) : ?>
			<div class="course-code">
				<?php echo esc_html( $course_code ); ?>
			</div>
		<?php endif; ?>

		<div class="course-banner-content-wrapper">
			<div class="course-banner-content">
				<?php if ( $banner_title ) : ?>
					<<?php echo esc_attr( $title_tag ); ?>><?php echo esc_html( $banner_title ); ?></<?php echo esc_attr( $title_tag ); ?>>
				<?php endif; ?>

				<?php if ( $banner_description ) : ?>
					<p><?php echo esc_html( $banner_description ); ?></p>
				<?php endif; ?>

				<div class="c-breadcrumb">
					<?php
					if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
						rank_math_the_breadcrumbs();
					} elseif ( function_exists( 'rank_math_get_breadcrumbs' ) ) {
						echo wp_kses_post( rank_math_get_breadcrumbs() );
					}
					?>
				</div>
			</div>
		</div>

		<?php if ( $course_highlight ) : ?>
			<div class="course-highlight">
				<?php echo esc_html( $course_highlight ); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
}
add_action( 'neve_after_header_hook', 'sa_learning_header_banner' );


/**
 * Add course events after content
 */
function sa_learning_course_events() {
	// Only show on single sa-course pages
	if ( ! is_singular( 'sa-course' ) ) {
		return;
	}

    $course_events = get_field( 'course_events' );
    if ( empty( $course_events ) ) {
        return;
    }

    $section_sub_title = get_field( 'section_sub_title' );
    $section_title = get_field( 'section_title' );

    ?>
    <div class="course-events-container pad-top">
        <?php if ( $section_sub_title ) : ?>
            <h5 class="smb-10"><?php echo esc_html( $section_sub_title ); ?></h5>
        <?php endif; ?>

        <?php if ( $section_title ) : ?>
            <h2><?php echo esc_html( $section_title ); ?></h2>
        <?php endif; ?>

        <div class="block-inner items-wrapper flex-container spt-25">
            <?php foreach ( $course_events as $event ) : ?>
                <?php 
                    set_query_var( 'event_id', $event );                    
                    get_template_part( 'views/loop-templates/event-card' ); ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

add_action( 'neve_after_content', 'sa_learning_course_events' );

