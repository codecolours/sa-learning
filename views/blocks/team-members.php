<?php 
/**
* Title: Team Members
* Description: Team Members
* Category: layout
* Icon: users
* Keywords: team-members
* SupportsAlign: false
* Mode: edit
* PostTypes: page
*/
?>

<?php 
$memberCategory = get_field( 'member_category' );
$selectedMembers = get_field( 'selected_members' );
$extraClassName = !empty($block['className']) ? ' ' . esc_attr($block['className']) : '';
?>


<div data-block-id="<?php echo esc_attr($block['id']); ?>" class="block-content team-members-block<?php echo $extraClassName; ?>">
    <div class="block-inner flex-container">
        <?php 
        // Display members by category
        if ($memberCategory && !empty($memberCategory)):
            $args = array(
                'post_type' => 'team-member',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'member-category',
                        'field' => 'term_id',
                        'terms' => $memberCategory,
                    ),
                ),
            );
            $query = new WP_Query($args);
            
            if ($query->have_posts()):
                while ($query->have_posts()):
                    $query->the_post();
                    set_query_var('memberID', get_the_ID());
                    get_template_part('views/loop-templates/team-member');
                endwhile;
                wp_reset_postdata();
            else: ?>
                <p class="no-members-found">No team members found in this category.</p>
            <?php endif;
            
        // Display manually selected members
        elseif ($selectedMembers && is_array($selectedMembers) && !empty($selectedMembers)):
            foreach ($selectedMembers as $member):
                // Handle both Post ID (int) and Post Object formats
                $member_id = null;
                
                if (is_numeric($member)) {
                    $member_id = intval($member);
                }
                
                // Skip if we couldn't get a valid ID
                if (!$member_id) {
                    continue;
                }
                
                set_query_var('memberID', $member_id);
                get_template_part('views/loop-templates/team-member');
            endforeach;
            
        // No selection made
        else: ?>
            <p class="no-members-found">No team members selected. Please configure the block settings.</p>
        <?php endif; ?>
    </div>
</div>
