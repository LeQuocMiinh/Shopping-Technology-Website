<!--Code Lấy bài Viết Của Taxonomy-->
<?php
$id = get_queried_object_id();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$list = new WP_Query([
    'post_type' => 'name',
    'posts_per_page' => 3,
    'paged' => $paged,
    'tax_query' => array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'taxonomy',
            'field' => 'id',
            'terms' => $id,
        )),
]);
while ($list->have_posts()) : $list->the_post();
endwhile;
wp_reset_query(); ?>