<?php
get_header();
?>
<section class="news-single" id="news-single">
    <div class="container">
        <?php get_template_part('sections/breadcrumb'); ?>
        <div class="news-single-wrapper">
            <div class="row">
                <div class="col-12 col-md-8">
                    <div class="post-content">
                        <?php while (have_posts()):
                        the_post(); ?>
                        <div class="news-single-title">
                            <?php the_title(); ?>
                        </div>
                        <div class="news-single-content">
                            <?php the_content(); ?>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <?php get_template_part('sidebar-news') ?>
                </div>
            </div>
            <?php get_template_part('sections/homepage/news-main') ?>
        </div>
    </div>
</section>

<?php
get_footer() ?>