<?php

/**
 * Template Name: Trung tâm hỗ trợ mobile
 */
?>
<?php
get_header();
$page_path = 'gioi-thieu';
$id = get_page_by_path($page_path);
?>
<section class="sidebar-support">
    <div class="sidebar-support-wrapper">
        <?php while (have_rows('page_select_support', $id)) :
            the_row();
            $page_id = url_to_postid(get_sub_field('page'));
            $page = get_post($page_id);
        ?>
            <div class="box-support">
                <a href="<?php the_sub_field('page'); ?>" data-id="title-page-<?php echo $page->ID ?>" class="sidebar-support-item <?php echo $page->ID === $id ?>">
                    <?php echo $page->post_title ?>
                    <img class="lazy" data-src="<?php echo get_template_directory_uri() ?>/images/go-next.svg" alt="">
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</section>
<?php get_footer() ?>