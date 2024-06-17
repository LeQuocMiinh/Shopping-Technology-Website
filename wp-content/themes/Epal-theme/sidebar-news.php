<?php $page_id = get_page_by_path('tin-tuc')->ID; ?>

<section class="sidebar-news">
    <div class="sidebar-news-outstanding">
        <div class="title"><?php the_field('title_sidebar_news', $page_id); ?></div>
        <div class="sidebar-news-outstanding-list">
            <?php
            $count_post = new WP_Query([
                'post_type' => 'post',
                'posts_per_page' => -1,
                'meta_key' => 'custom_post_views',
                'orderby' => 'meta_value_num',
                'order' => 'DESC',
            ]);

            $total_post_view_most = $count_post->found_posts;

            $query = new WP_Query([
                'post_type' => 'post',
                'posts_per_page' => 5,
                'meta_key' => 'custom_post_views',
                'orderby' => 'meta_value_num',
                'order' => 'DESC',
            ]);
            $total_post_view_most_current = $query->found_posts;
            $i = 1; ?>

            <div class="sidebar-news-outstanding-list-wrapper">
                <?php
                while ($query->have_posts()) : $query->the_post() ?>

                    <a class="sidebar-news-outstanding-item" href="<?php the_permalink(); ?>">
                        <div class="number"><?php echo $i; ?></div>
                        <div class="title"><?php the_title(); ?></div>
                    </a>

                <?php $i++;

                endwhile;

                ?>
            </div>
            <input type="hidden" id="total_post_view_most" value="<?php echo $total_post_view_most ?>">
            <input type="hidden" id="total_post_view_most_current" value="<?php echo $total_post_view_most_current ?>">
            <div class="load_more" id="load_more">Xem thêm <i class="fa fa-chevron-down"></i></div>
        </div>
    </div>
    <div class="sidebar-news-keyword">
        <div class="title"><?php the_field('title_sidebar_news_keyword', $page_id); ?></div>
        <div class="sidebar-news-keyword-list">
            <?php while (have_rows('list_of_keyword_outstanding', $page_id)) : the_row(); ?>
                <div class="keyword-item"><?php the_sub_field('keyword'); ?></div>
            <?php endwhile; ?>
        </div>
    </div>
    <div class="sidebar-news-image">
        <?php while (have_rows('img_sidebar_news', $page_id)) : the_row(); ?>
            <?php if (get_sub_field('img')) :
                $img_sidebar = get_sub_field('img'); ?>
                <a href="<?php the_sub_field('link') ?>">
                    <img src="<?php echo $img_sidebar['url'] ?>" alt="<?php echo $img_sidebar['alt'] ?>">
                </a>
        <?php endif;
        endwhile; ?>
    </div>

</section>