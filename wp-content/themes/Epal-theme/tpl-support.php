<?php

/**
 * Template Name: Hỗ trợ - Chính sách
 */

get_header();
$page_id = url_to_postid(get_sub_field('page'));
$page = get_post($page_id);
?>

<section id="support" class="p-25 fs-17 single">
    <div class="container">
        <?php get_template_part('sections/breadcrumb') ?>
        <div class="row">
            <div class="col-lg-3 col-md-12 col-12 pl-0 item-sidebar-mb">
                <?php get_template_part('sidebar-support'); ?>
            </div>
            <div class="col-lg-9 col-md-12 col-12">
                <div class="post-content">

                    <h1 class="title bold fs-24 mb-4 text-uppercase">
                        <?php the_field('title_support', get_the_ID()); ?>
                    </h1>
                    <div class="content color-black-gray">
                        <?php while (have_posts()):
                        the_post();
                        the_content();
                    endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer() ?>