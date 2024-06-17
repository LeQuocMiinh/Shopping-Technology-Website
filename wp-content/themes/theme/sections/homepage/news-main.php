<?php $id_page = get_page_by_path('trang-chu')->ID; ?>

<section id="news-main" class="section-wrapper mt-60">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="content">
                    <div class="header">
                        <div class="text"><?php echo get_field('news_title', $id_page) ?></div>
                        <div class="sub-cate">
                            <?php $items = get_field('sub_cates', $id_page); ?>
                            <?php foreach ($items as $key=>$item) : ?>
                            <span class="item owl-control <?php echo ($key == 0) ? 'active' : '' ?>"
                                data-cate="<?php echo $item['cate']->term_id ?>">
                                <?php echo $item['cate']->name; ?> </span>
                            <?php endforeach; ?>
                            <a class="item see-all-pc" href="/tin-tuc">Xem tất cả</a>
                        </div>
                    </div>
                    <div id="slide-wrapper">
                        <div class="default news-list news-carousel owl-carousel owl-theme">
                            <?php
                            $allPosts = new WP_Query(
                                array(
                                    'post_type' => 'post',
                                    'posts_per_page' => -1,
                                    'offset' => 1,
                                    'post_status' => 'publish',
                                    'order' => 'DESC',
                                    'orderby' => 'date',
                                    'category__not_in' => array(1)
                                )
                            );
                            while ($allPosts->have_posts()) : $allPosts->the_post();
                            ?>
                            <div class="post-item">
                            <a class="ellipsis" href="<?php the_permalink() ?>">
                                <div class="thumbnail">
                                    <img src="<?php echo get_the_post_thumbnail_url() ?>"
                                        alt="<?php echo get_the_post_thumbnail_caption() ?>">
                                </div>
                            </a>
                                <div class="post-title">
                                    <a class="ellipsis" href="<?php the_permalink() ?>">
                                        <?php the_title() ?>
                                    </a>
                                </div>
                            </div>
                            <?php endwhile;
                            wp_reset_query(); ?>
                        </div>

                        <?php
                        $items = get_field('sub_cates', $id_page);
                        foreach ($items as $item) :
                            $cate_posts = new WP_Query(
                                array(
                                    'post_type' => 'post',
                                    'posts_per_page' => -1,
                                    'post_status' => 'publish',
                                    'order' => 'DESC',
                                    'orderby' => 'date',
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'category',
                                            'field'    => 'slug',
                                            'terms'    => $item['cate']->slug
                                        )
                                    )
                                )
                            );
                        ?>
                        <div class="hidden-slide news-list news-carousel owl-carousel owl-theme"
                            data-cate=<?php echo $item['cate']->term_id ?>>
                            <?php while ($cate_posts->have_posts()) : $cate_posts->the_post();  ?>
                            <div class="post-item">
                            <a class="ellipsis" href="<?php the_permalink() ?>">
                                <div class="thumbnail">
                                    <img src="<?php echo get_the_post_thumbnail_url() ?>"
                                        alt="<?php echo get_the_post_thumbnail_caption() ?>">
                                </div>
                            </a>
                                <div class="post-title">
                                    <a class="ellipsis" href="<?php the_permalink() ?>">
                                        <?php the_title() ?>
                                    </a>
                                </div>
                            </div>
                            <?php endwhile;
                                wp_reset_query(); ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <a class="item see-all-mb" href="/tin-tuc">
                        Xem tất cả
                        <img src="<?php echo get_template_directory_uri() . '/images/arrow-white.svg' ?>" alt="icon">
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>