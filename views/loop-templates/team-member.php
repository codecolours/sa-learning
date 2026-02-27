<?php
/**
 * Team Member Item
 *
 * @package sa-learning
 */

$member_id = get_query_var('memberID');
if (!$member_id) {
    return;
}

// Get member data
$featuredImage = get_the_post_thumbnail_url($member_id, 'medium');
$memberTitle = get_the_title($member_id);
$memberDesignation = get_field('member_designation', $member_id);
$memberBio = get_the_excerpt($member_id);
?>
<div class="member-item col-4">
    <div class="item-img">
        <?php if ($featuredImage): ?>
            <img src="<?php echo esc_url($featuredImage); ?>" alt="<?php echo esc_attr($memberTitle); ?>">
        <?php else: ?>
            <div class="no-image-placeholder">
                <span class="member-initials"><?php echo esc_html(substr($memberTitle, 0, 1)); ?></span>
            </div>
        <?php endif; ?>
    </div>
    <div class="item-info">
        <h4><?php echo esc_html($memberTitle); ?></h4>
        <?php if ($memberDesignation): ?>
            <div class="member-designation">
                <?php echo esc_html($memberDesignation); ?>
            </div>
        <?php endif; 
        
        if ($memberBio): ?>
            <div class="member-bio">
                <?php echo wp_kses_post($memberBio); ?>
            </div>
        <?php endif; ?>
    </div>
</div>