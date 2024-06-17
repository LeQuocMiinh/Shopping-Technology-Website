<?php

function loadmore_sidebar_news_outstanding()
{
    $number = isset($_POST['number']) ? intval($_POST['number']) : 5;
    $offset = 5;

    ob_start();

    $query = new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => $number,
        'offset' => $offset,
        'meta_key' => 'custom_post_views',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
    ]);

    $i = $offset + 1; // Biến đếm bắt đầu từ offset + 1
    while ($query->have_posts()) : $query->the_post(); ?>

        <a class="sidebar-news-outstanding-item" href="<?php the_permalink(); ?>">
            <div class="number"><?php echo $i; ?></div>
            <div class="title"><?php the_title(); ?></div>
        </a>

<?php
        $i++;
    endwhile;

    wp_reset_postdata();

    // Tính toán offset mới cho lần load tiếp theo
    $new_offset = $offset;

    // Trả về kết quả cùng với offset mới
    $result = ob_get_clean();
    wp_send_json(['status' => true, 'html' => $result, 'offset' => $new_offset]);
}

add_action('wp_ajax_loadmore_sidebar_news_outstanding', 'loadmore_sidebar_news_outstanding');
add_action('wp_ajax_nopriv_loadmore_sidebar_news_outstanding', 'loadmore_sidebar_news_outstanding');
