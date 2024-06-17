<?php $id = get_queried_object_id(); ?>

<section class="sidebar-support">
    <div class="sidebar-support-wrapper">
        <?php while (have_rows('page_select_support', $id)) : the_row();
            $page_id = url_to_postid(get_sub_field('page'));
            $page = get_post($page_id);
        ?>
            <a href="<?php the_sub_field('page'); ?>" data-id="title-page-<?php echo $page->ID ?>" class="sidebar-support-item <?php echo ($page->ID === $id) ? 'active' : '' ?>">
                <?php echo $page->post_title ?></a>
        <?php endwhile; ?>
    </div>
</section>