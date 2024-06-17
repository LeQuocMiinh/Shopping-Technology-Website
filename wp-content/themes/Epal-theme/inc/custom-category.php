<?php 
/**
 * @return json
 * Hàm load sản phẩm
 */
function paginationProduct(){
    $current =  $_POST['current'];
    $id_category = $_POST['id_category'];
    $params = $_POST;
    $array_serise = $params['array_serise'];
    $array_demand = $params['array_demand'];
    $array_color = $params['array_color'];
    $array_source = $params['array_source'];
    $price_min = $params['price_min'];
    $price_max = $params['price_max'];
    $order = $params['order'];
    $meta_key = '';
    $order_by = 'date';
    $orders = 'DESC';
    $post__in = '';
    $discount_meta = '';
    if ($order == 'discount') {
        $order_by = 'meta_value_num';
        $orders = 'desc';
        $meta_key = '_discount_percentage';
        $discount_meta = array(
            array(
                'key'     => '_discount_percentage',
                'value'   => 0,
                'compare' => '>',
                'type'    => 'NUMERIC',
            )
        );
    }
    if ($order == 'date-desc') {
        $order_by = 'date';
        $orders = 'DESC';
    }
    if ($order == 'price-asc') {
        $order_by = 'meta_value_num';
        $orders = 'asc';
        $meta_key = '_price';
    }
    if ($order == 'price-desc') {
        $order_by = 'meta_value_num';
        $orders = 'desc';
        $meta_key = '_price';
    }
    if ($order == 'buy-desc') {
        $order_by = 'meta_value_num';
        $orders = 'desc';
        $meta_key = 'total_sales';
    }
    ob_start();
    $tax_serise = '';
    $tax_demand = '';
    $tax_color = '';
    $tax_source = '';
    $tax_price = '';
    $tax_category = '';
    if ($price_min != null && $price_max != null) {
        $tax_price = array(
            'relation' => 'AND',
            array(
                'key' => '_price',
                'value' => $price_min,
                'type' => 'NUMERIC',
                'compare' => '>='
            ),
            array(
                'key' => '_price',
                'value' => $price_max,
                'type' => 'NUMERIC',
                'compare' => '<='

            )
        );
    }
    if(!empty($array_serise)){
        $tax_serise = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    =>  $array_serise,
                'operator' => 'IN'
            ),
        );
    }

    if(!empty($array_demand)){
        $tax_demand = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    =>  $array_demand,
                'operator' => 'IN'
            ),
        );
    }

    if(!empty($id_category)){
        $tax_category = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    =>  $id_category,
                'operator' => 'IN'
            ),
        );
    }

    if(!empty($array_color)) {
        $tax_color = array(
            'taxonomy' => 'pa_mau-sac',
            'field' => 'id',
            'terms' => $array_color,
            'operator' => 'IN'
        );
    }

    if(!empty($array_source)) {
        $tax_source = array(
            'taxonomy' => 'pa_nguon-hang',
            'field' => 'id',
            'terms' => $array_source,
            'operator' => 'IN'
        );
    }

    $tax_query = array(
        'relation' => 'AND',
        $tax_serise,
        $tax_demand,
        $tax_color,
        $tax_source,
        $tax_category
    );
    $allProductsByCate = new WP_Query(
        array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'paged' => $current + 1,
            'posts_per_page' => 20,
            'order' => $orders,
            'orderby' => $order_by,
            'meta_key' => $meta_key,
            'post__in' => $post__in,
            'tax_query' => $tax_query,
            'meta_query' => array(
                'relation' => 'AND',
                $tax_price,
                $discount_meta,
                array(
                    'key' => '_backorders',
                    'value' => 'no'
                ),
            )
        )
    );
    while($allProductsByCate->have_posts()) : $allProductsByCate->the_post();
    global $product;
    $product = wc_get_product(get_the_ID());
    $price_regular = $product->get_regular_price();
    if( $product->is_on_sale() ) {
        $price_sale = $product->get_sale_price();
        $discount = devvn_presentage_bubble($product);
    }
    
    $total_posts = $allProductsByCate->found_posts;
    $posts_per_page = $allProductsByCate->query_vars['posts_per_page'];
    $current_page = $allProductsByCate->query_vars['paged'];
    $displayed_posts = $current_page * $posts_per_page;
    $remaining_posts = ($total_posts - $displayed_posts) > 0 ? $total_posts - $displayed_posts : 0;

    ?>

<div class="product-item">
    <a href="<?php the_permalink() ?>">
        <div class="image">
            <img src="<?php the_post_thumbnail_url() ?>" alt="<?php the_post_thumbnail_caption() ?>">
        </div>
        <div class="infor">
            <div class="name">
                <p class="ellipsis"><?php the_title() ?></p>
            </div>
            <div class="price">
                <?php
                                                if($product->get_type() == 'variable'){
                                                    $default_attributes = $product->get_default_attributes();
                                                    foreach ($default_attributes as $default_attribute => $value){
                                                        $color_vr = $value;
                                                        $att = $default_attribute;
                                                    }
                                                    $id_vari = get_product_variation_id($color_vr,$product->get_id(),'attribute_' . $att);
                                                    $variation_obj = new WC_Product_variation($id_vari);
                                                    $stock = $variation_obj->get_stock_status();
                                                }
                                                else{
                                                    $stock = $product->get_stock_status();
                                                }
                                                ?>
                <?php if($stock == 'instock'): ?>
                <?php
                                            if($product->get_type() == 'variable'):
                                                $default_attributes = $product->get_default_attributes();
                                                $id_vari = '';
                                                if(!empty($default_attributes)){
                                                    foreach ($default_attributes as $default_attribute => $value){
                                                        $color_vr = $value;
                                                        $att = $default_attribute;
                                                    }
                                                    $id_vari = get_product_variation_id($color_vr,$product->get_id(),'attribute_' . $att);
                                                }
                                                if(count($default_attributes) > 0):
                                                    $price_regular = get_post_meta($id_vari, '_regular_price', true);
                                                    $price_sale = get_post_meta($id_vari,'_sale_price',true);
                                                    if($price_sale != ''){
                                                        $discount = devvn_presentage_bubble($product);
                                                    }
                                                    else{
                                                        $discount = '0%';
                                                    }
                                                    if($price_sale != ''): ?>
                <div class="price-sale">
                    <span><?php echo number_format((float)$price_sale, 0, '.', '.');  ?>đ</span>
                </div>
                <?php endif; ?>
                <div class="price-regular <?php echo $price_sale == '' ? 'off-sale' : 'on-sale line-through' ?>">
                    <span class="price-text"><?php echo number_format((float)$price_regular,0,'.','.'); ?>đ</span>
                    <?php if($price_sale != ''): ?>
                    <span class="discount"><?php echo $discount ?></span>
                    <?php endif; ?>
                </div>
                <?php else:
                                                    echo $product->get_price_html();
                                                endif;
                                            else:
                                            ?>
                <?php if($price_sale != ''): ?>
                <div class="price-sale">
                    <span><?php echo number_format((float)$price_sale, 0, '.', '.');  ?>đ</span>
                </div>
                <?php endif; ?>
                <div class="price-regular <?php echo $price_sale == '' ? 'off-sale' : 'on-sale line-through' ?>">
                    <span class="price-text"><?php echo number_format((float)$price_regular,0,'.','.'); ?>đ</span>
                    <?php if($price_sale != ''): ?>
                    <span class="discount"><?php echo $discount ?></span>
                    <?php endif; ?>
                </div>
                <?php endif;
                                            endif;
                                            ?>
                <?php if ($stock == 'outofstock'): ?>
                <div class="custom-stock">
                    <span>
                        <?php echo get_field('text_out_stock', 'option') ?>
                    </span>
                </div>

                <?php endif; ?>
                <?php if ($stock == 'onbackorder'): ?>
                <div class="custom-stock">
                    <span>
                        <?php echo get_field('text_stocking', 'option') ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </a>
    <div class="bot-content">
        <div class="add-pc">
            <a href="<?php the_permalink() ?>" class="detail">
                Chi tiết
                <img src="<?php echo get_template_directory_uri().'/images/arrow.svg' ?>" alt="icon">
            </a>
            <?php 
                                                if($product->get_type() == 'variable') {
                                                    $id_var = $id_vari;
                                                    $id_pr = get_the_ID();

                                                }
                                                else {
                                                    $id_pr = get_the_ID();
                                                    $id_var = '';
                                                }
                                            ?>
            <div class="add-to-cart add_cart_ajax_session" data-product_id="<?php echo $id_pr ?>"
                data-variation_id="<?php echo $id_var ?>">
                <img src="<?php echo get_template_directory_uri().'/images/add-cart.svg' ?>" alt="icon">
            </div>
        </div>
        <div class="add-mobile">
            <div class="add-to-cart add_cart_ajax_session" data-product_id="<?php echo $id_pr ?>"
                data-variation_id="<?php echo $id_var ?>">
                Thêm vào giỏ
                <img src="<?php echo get_template_directory_uri().'/images/cart.svg' ?>" alt="icon">
            </div>
        </div>
    </div>
</div>
<?php endwhile;
    $result = ob_get_clean();
    wp_send_json(['result' => $result, 'remaining_posts' => $remaining_posts]);
}
add_action('wp_ajax_paginationProduct', 'paginationProduct');
add_action('wp_ajax_nopriv_paginationProduct', 'paginationProduct');

/**
 * @return json
 * Hàm lọc sản phẩm theo yêu
 */
function filterProduct(){
    $params = $_POST;
    $array_serise = $params['array_serise'];
    $array_demand = $params['array_demand'];
    $array_color = $params['array_color'];
    $array_source = $params['array_source'];
    $price_min = $params['price_min'];
    $price_max = $params['price_max'];
    $order = $params['order'];
    $id_category = $params['id_catgory'];
    $meta_key = '';
    $order_by = 'date';
    $orders = 'DESC';
    $post__in = '';
    $discount_meta = '';
    if ($order == 'discount') {
        $order_by = 'meta_value_num';
        $orders = 'desc';
        $meta_key = '_discount_percentage';
        $discount_meta = array(
            array(
                'key'     => '_discount_percentage',
                'value'   => 0,
                'compare' => '>=',
                'type'    => 'NUMERIC',
            )
        );
    }
    if ($order == 'date-desc') {
        $order_by = 'date';
        $orders = 'DESC';
    }
    if ($order == 'price-asc') {
        $order_by = 'meta_value_num';
        $orders = 'asc';
        $meta_key = '_price';
    }
    if ($order == 'price-desc') {
        $order_by = 'meta_value_num';
        $orders = 'desc';
        $meta_key = '_price';
    }
    if ($order == 'buy-desc') {
        $order_by = 'meta_value_num';
        $orders = 'desc';
        $meta_key = 'total_sales';
    }
    ob_start();
    $tax_serise = '';
    $tax_demand = '';
    $tax_color = '';
    $tax_source = '';
    $tax_price = '';
    $tax_category = '';
    if ($price_min != null && $price_max != null) {
        $tax_price = array(
            'relation' => 'AND',
            array(
                'key' => '_price',
                'value' => $price_min,
                'type' => 'NUMERIC',
                'compare' => '>='
            ),
            array(
                'key' => '_price',
                'value' => $price_max,
                'type' => 'NUMERIC',
                'compare' => '<='

            )
        );
    }
    if(!empty($array_serise)){
        $tax_serise = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    =>  $array_serise,
                'operator' => 'IN'
            ),
        );
    }

    if(!empty($array_demand)){
        $tax_demand = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    =>  $array_demand,
                'operator' => 'IN'
            ),
        );
    }

    if(!empty($id_category)){
        $tax_category = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    =>  $id_category,
                'operator' => 'IN'
            ),
        );
    }

    if(!empty($array_color)) {
        $tax_color = array(
            'taxonomy' => 'pa_mau-sac',
            'field' => 'id',
            'terms' => $array_color,
            'operator' => 'IN'
        );
    }

    if(!empty($array_source)) {
        $tax_source = array(
            'taxonomy' => 'pa_nguon-hang',
            'field' => 'id',
            'terms' => $array_source,
            'operator' => 'IN'
        );
    }

    $tax_query = array(
        'relation' => 'AND',
        $tax_serise,
        $tax_demand,
        $tax_color,
        $tax_source,
        $tax_category
    );
    $allProductsByCate = new WP_Query(
        array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'paged' => 1,
            'posts_per_page' => 20,
            'order' => $orders,
            'orderby' => $order_by,
            'meta_key' => $meta_key,
            'post__in' => $post__in,
            'tax_query' => $tax_query,
            'meta_query' => array(
                'relation' => 'AND',
                $tax_price,
                $discount_meta,
                array(
                    'key' => '_backorders',
                    'value' => 'no'
                ),
            )
        )
    );
    if($allProductsByCate->have_posts()):
    while($allProductsByCate->have_posts()) : $allProductsByCate->the_post();
    global $product;
    $product = wc_get_product(get_the_ID());
    $price_regular = $product->get_regular_price();
    if( $product->is_on_sale() ) {
        $price_sale = $product->get_sale_price();
        $discount = devvn_presentage_bubble($product);
    }

    $total_posts = $allProductsByCate->found_posts;
    ?>

<div class="product-item">
    <a href="<?php the_permalink() ?>">
        <div class="image">
            <img src="<?php the_post_thumbnail_url() ?>" alt="<?php the_post_thumbnail_caption() ?>">
        </div>
        <div class="infor">
            <div class="name">
                <p class="ellipsis"><?php the_title() ?></p>
            </div>
            <div class="price">
                <?php
                                                if($product->get_type() == 'variable'){
                                                    $default_attributes = $product->get_default_attributes();
                                                    foreach ($default_attributes as $default_attribute => $value){
                                                        $color_vr = $value;
                                                        $att = $default_attribute;
                                                    }
                                                    $id_vari = get_product_variation_id($color_vr,$product->get_id(),'attribute_' . $att);
                                                    $variation_obj = new WC_Product_variation($id_vari);
                                                    $stock = $variation_obj->get_stock_status();
                                                }
                                                else{
                                                    $stock = $product->get_stock_status();
                                                }
                                                ?>
                <?php if($stock == 'instock'): ?>
                <?php
                                            if($product->get_type() == 'variable'):
                                                $default_attributes = $product->get_default_attributes();
                                                $id_vari = '';
                                                if(!empty($default_attributes)){
                                                    foreach ($default_attributes as $default_attribute => $value){
                                                        $color_vr = $value;
                                                        $att = $default_attribute;
                                                    }
                                                    $id_vari = get_product_variation_id($color_vr,$product->get_id(),'attribute_' . $att);
                                                }
                                                if(count($default_attributes) > 0):
                                                    $price_regular = get_post_meta($id_vari, '_regular_price', true);
                                                    $price_sale = get_post_meta($id_vari,'_sale_price',true);
                                                    if($price_sale != ''){
                                                        $discount = devvn_presentage_bubble($product);
                                                    }
                                                    else{
                                                        $discount = '0%';
                                                    }
                                                    if($price_sale != ''): ?>
                <div class="price-sale">
                    <span><?php echo number_format((float)$price_sale, 0, '.', '.');  ?>đ</span>
                </div>
                <?php endif; ?>
                <div class="price-regular <?php echo $price_sale == '' ? 'off-sale' : 'on-sale line-through' ?>">
                    <span class="price-text"><?php echo number_format((float)$price_regular,0,'.','.'); ?>đ</span>
                    <?php if($price_sale != ''): ?>
                    <span class="discount"><?php echo $discount ?></span>
                    <?php endif; ?>
                </div>
                <?php else:
                                                    echo $product->get_price_html();
                                                endif;
                                            else:
                                            ?>
                <?php if($price_sale != ''): ?>
                <div class="price-sale">
                    <span><?php echo number_format((float)$price_sale, 0, '.', '.');  ?>đ</span>
                </div>
                <?php endif; ?>
                <div class="price-regular <?php echo $price_sale == '' ? 'off-sale' : 'on-sale line-through' ?>">
                    <span class="price-text"><?php echo number_format((float)$price_regular,0,'.','.'); ?>đ</span>
                    <?php if($price_sale != ''): ?>
                    <span class="discount"><?php echo $discount ?></span>
                    <?php endif; ?>
                </div>
                <?php endif;
                                            endif;
                                            ?>
                <?php if ($stock == 'outofstock'): ?>
                <div class="custom-stock">
                    <span>
                        <?php echo get_field('text_out_stock', 'option') ?>
                    </span>
                </div>

                <?php endif; ?>
                <?php if ($stock == 'onbackorder'): ?>
                <div class="custom-stock">
                    <span>
                        <?php echo get_field('text_stocking', 'option') ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </a>
    <div class="bot-content">
        <div class="add-pc">
            <a href="<?php the_permalink() ?>" class="detail">
                Chi tiết
                <img src="<?php echo get_template_directory_uri().'/images/arrow.svg' ?>" alt="icon">
            </a>
            <?php 
                                                if($product->get_type() == 'variable') {
                                                    $id_var = $id_vari;
                                                    $id_pr = get_the_ID();

                                                }
                                                else {
                                                    $id_pr = get_the_ID();
                                                    $id_var = '';
                                                }
                                            ?>
            <div class="add-to-cart add_cart_ajax_session" data-product_id="<?php echo $id_pr ?>"
                data-variation_id="<?php echo $id_var ?>">
                <img src="<?php echo get_template_directory_uri().'/images/add-cart.svg' ?>" alt="icon">
            </div>
        </div>
        <div class="add-mobile">
            <div class="add-to-cart add_cart_ajax_session" data-product_id="<?php echo $id_pr ?>"
                data-variation_id="<?php echo $id_var ?>">
                Thêm vào giỏ
                <img src="<?php echo get_template_directory_uri().'/images/cart.svg' ?>" alt="icon">
            </div>
        </div>
    </div>
</div>
<?php endwhile;else:
    ?>
<?php endif;
    $max = $allProductsByCate->max_num_pages;
    $result = ob_get_clean();
    wp_send_json(['result' => $result,'max_page' => $max, 'total' => $total_posts]);
}
add_action('wp_ajax_filterProduct', 'filterProduct');
add_action('wp_ajax_nopriv_filterProduct', 'filterProduct');