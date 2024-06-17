<?php

/**
 * Template Name: Tin tức
 */

get_header();
?>
<section class="news" id="news">
    <div class="container">
        <?php get_template_part('sections/breadcrumb'); ?>
        <div class="news-wrapper">
            <div class="news-category d-flex ">
                <?php
                $categories = get_categories();

                foreach ($categories as $category) {
                    // Kiểm tra xem danh mục có bài đăng hay không
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 1, // Chỉ cần 1 bài đăng để kiểm tra
                        'category__in' => $category->term_id,
                    );
                    $query = new WP_Query($args);

                    if ($query->have_posts()) {
                        echo '<a class="category-link" href="' . get_category_link($category->term_id) . '">' . $category->name . '</a><br>';
                    }

                    wp_reset_postdata(); // Đặt lại dữ liệu bài đăng
                }
                ?>

            </div>
            <div class="news-outstanding">
                <div class="row-1 row">
                    <?php $query = new WP_Query([
                        'post_type' => 'post',
                        'posts_per_page' => 2,
                        'orderby' => 'date',
                        'order' => 'DESC',
                    ]);
                    while ($query->have_posts()) :
                        $query->the_post() ?>
                        <div class="col-12 col-sm-6">
                            <a class="news-outstanding-item" href="<?php echo get_the_permalink(); ?>">
                                <div class="img">
                                    <img class="lazy" data-src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
                                </div>
                                <div class="content">
                                    <?php the_title(); ?>
                                </div>
                            </a>
                        </div>

                    <?php endwhile; ?>
                </div>
                <div class="row-2 row">
                    <?php $query = new WP_Query([
                        'post_type' => 'post',
                        'posts_per_page' => 3,
                        'offset' => 2,
                        'orderby' => 'date',
                        'order' => 'DESC',
                    ]);
                    while ($query->have_posts()) :
                        $query->the_post() ?>
                        <div class="col-12 col-sm-4">
                            <a class="news-outstanding-item" href="<?php echo get_the_permalink(); ?>">
                                <div class="img">
                                    <img class="lazy" data-src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
                                </div>
                                <div class="content">
                                    <?php the_title(); ?>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="news-container row">
                <div class="col-12 col-md-8">
                    <div class="news-list ">
                        <?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        $query = new WP_Query([
                            'post_type' => 'post',
                            'posts_per_page' => 6,
                            'offset' => 5,
                            'paged' => $paged,
                        ]);
                        while ($query->have_posts()) :
                            $query->the_post()
                        ?>
                            <div class="news-item">

                                <div class="img">
                                    <a href="<?php the_permalink(); ?>">
                                        <img class="lazy" data-src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
                                    </a>
                                </div>

                                <div class="content">
                                    <a href="<?php the_permalink(); ?>">
                                        <div class="title">
                                            <?php the_title(); ?>
                                        </div>
                                        <div class="desc">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    </a>
                                </div>

                            </div>

                        <?php endwhile;
                        wp_reset_query(); ?>
                        <?php if (function_exists('devvn_wp_corenavi'))
                            devvn_wp_corenavi($query); ?>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <?php get_template_part('sidebar-news') ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php


?>




<?php
get_footer() ?>