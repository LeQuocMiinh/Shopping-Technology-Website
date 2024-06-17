<?php
get_header();
?>
<section class="news" id="news">
    <div class="container">
        <?php get_template_part('sections/breadcrumb'); ?>
        <div class="news-wrapper">
            <div class="news-category d-flex ">
                <?php
                $categories = get_categories();
                $id = get_queried_object_id();
                foreach ($categories as $category):
                    // Kiểm tra xem danh mục có bài đăng hay không
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 1, // Chỉ cần 1 bài đăng để kiểm tra
                        'category__in' => $category->term_id,
                    );
                    $query = new WP_Query($args);

                    if ($query->have_posts()): ?>
                        <a href="<?php echo get_category_link($category->term_id) ?> "
                            class="category-link <?php echo ($category->term_id === $id) ? 'active' : '' ?>">
                            <?php echo $category->name ?>
                        </a>
                    <?php endif;
                endforeach;
                ?>

            </div>
            <div class="news-outstanding">
                <div class="row-1 row">
                    <?php
                    $id = get_queried_object_id();
                    $query = new WP_Query([
                        'post_type' => 'post',
                        'posts_per_page' => 2,
                        'cat' => $id,
                        'orderby' => 'date',
                        'order' => 'DESC',
                    ]);
                    while ($query->have_posts()):
                        $query->the_post() ?>
                        <div class="col-12 col-sm-6">
                            <a class="news-outstanding-item" href="<?php echo get_the_permalink(); ?>">
                                <div class="img">
                                    <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
                                </div>
                                <div class="content">
                                    <?php the_title(); ?>
                                </div>
                            </a>
                        </div>

                    <?php endwhile; ?>
                </div>
                <div class="row-2 row">
                    <?php
                    $id = get_queried_object_id();
                    $query = new WP_Query([
                        'post_type' => 'post',
                        'posts_per_page' => 3,
                        'offset' => 2,
                        'cat' => $id,
                        'orderby' => 'date',
                        'order' => 'DESC',
                    ]);
                    while ($query->have_posts()):
                        $query->the_post() ?>
                        <div class="col-12 col-sm-4">
                            <a class="news-outstanding-item" href="<?php echo get_the_permalink(); ?>">
                                <div class="img">
                                    <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
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
                        <?php
                        $id = get_queried_object_id();
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        $query = new WP_Query([
                            'post_type' => 'post',
                            'posts_per_page' => 6,
                            'offset' => 5,
                            'cat' => $id,
                            'paged' => $paged,
                        ]);
                        while ($query->have_posts()):
                            $query->the_post()
                                ?>
                            <div class="news-item">

                                <div class="img">
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
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