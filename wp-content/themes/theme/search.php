<?php
get_header();

?>

<section id="category-products">
    <div class="container">
    <?php get_template_part('sections/breadcrumb'); ?>
        <div class="col-product-right">
            <div class="center-content-product">
                <div class="list-products list-search">
                    <?php
                    $s = get_search_query();
                    $args = array(
                        'post_type' => 'product',
                        'post_status' => 'publish',
                        'paged' => $paged,
                        'posts_per_page' => 20,
                        'order' => 'DESC',
                        'orderby' => 'date',
                        's' => $s
                    );
                    $the_query = new WP_Query($args);
                    if ($the_query->have_posts()) {

                        while ($the_query->have_posts()) {
                            $the_query->the_post();
                            global $product;
                            $product = wc_get_product(get_the_ID());
                            $price_regular = $product->get_regular_price();
                            if ($product->is_on_sale()) {
                                $price_sale = $product->get_sale_price();
                                $discount = devvn_presentage_bubble($product);
                            }
                            ?>
                            <div class="product-item">
                                <a href="<?php the_permalink() ?>">
                                    <div class="image">
                                        <img src="<?php the_post_thumbnail_url() ?>"
                                            alt="<?php the_post_thumbnail_caption() ?>">
                                    </div>
                                    <div class="infor">
                                        <div class="name">
                                            <p class="ellipsis">
                                                <?php the_title() ?>
                                            </p>
                                        </div>
                                        <div class="price">
                                            <?php
                                            if ($product->get_type() == 'variable') {
                                                $default_attributes = $product->get_default_attributes();
                                                foreach ($default_attributes as $default_attribute => $value) {
                                                    $color_vr = $value;
                                                    $att = $default_attribute;
                                                }
                                                $id_vari = get_product_variation_id($color_vr, $product->get_id(), 'attribute_' . $att);
                                                $variation_obj = new WC_Product_variation($id_vari);
                                                $stock = $variation_obj->get_stock_status();
                                            } else {
                                                $stock = $product->get_stock_status();
                                            }
                                            ?>
                                            <?php if ($stock == 'instock'): ?>
                                                <?php
                                                if ($product->get_type() == 'variable'):
                                                    $default_attributes = $product->get_default_attributes();
                                                    $id_vari = '';
                                                    if (!empty($default_attributes)) {
                                                        foreach ($default_attributes as $default_attribute => $value) {
                                                            $color_vr = $value;
                                                            $att = $default_attribute;
                                                        }
                                                        $id_vari = get_product_variation_id($color_vr, $product->get_id(), 'attribute_' . $att);
                                                    }
                                                    if (count($default_attributes) > 0):
                                                        $price_regular = get_post_meta($id_vari, '_regular_price', true);
                                                        $price_sale = get_post_meta($id_vari, '_sale_price', true);
                                                        if ($price_sale != '') {
                                                            $discount = devvn_presentage_bubble($product);
                                                        } else {
                                                            $discount = '0%';
                                                        }
                                                        if ($price_sale != ''): ?>
                                                            <div class="price-sale">
                                                                <span>
                                                                    <?php echo number_format((float) $price_sale, 0, '.', '.'); ?>đ
                                                                </span>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div
                                                            class="price-regular <?php echo $price_sale == '' ? 'off-sale' : 'on-sale line-through' ?>">
                                                            <span class="price-text">
                                                                <?php echo number_format((float) $price_regular, 0, '.', '.'); ?>đ
                                                            </span>
                                                            <?php if ($price_sale != ''): ?>
                                                                <span class="discount">
                                                                    <?php echo $discount ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php else:
                                                        echo $product->get_price_html();
                                                    endif;
                                                else:
                                                    ?>
                                                    <?php if ($price_sale != ''): ?>
                                                        <div class="price-sale">
                                                            <span>
                                                                <?php echo number_format((float) $price_sale, 0, '.', '.'); ?>đ
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div
                                                        class="price-regular <?php echo $price_sale == '' ? 'off-sale' : 'on-sale line-through' ?>">
                                                        <span class="price-text">
                                                            <?php echo number_format((float) $price_regular, 0, '.', '.'); ?>đ
                                                        </span>
                                                        <?php if ($price_sale != ''): ?>
                                                            <span class="discount">
                                                                <?php echo $discount ?>
                                                            </span>
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
                                            <img src="<?php echo get_template_directory_uri() . '/images/arrow.svg' ?>"
                                                alt="icon">
                                        </a>
                                        <div class="add-to-cart add_cart_ajax_cate" data-id=<?php echo get_the_ID() ?>>
                                            <img src="<?php echo get_template_directory_uri() . '/images/add-cart.svg' ?>"
                                                alt="icon">
                                        </div>
                                    </div>
                                    <div class="add-mobile">
                                        <div class="add-to-cart add_cart_ajax_cate" data-id=<?php echo get_the_ID() ?>>
                                            Thêm vào giỏ
                                            <img src="<?php echo get_template_directory_uri() . '/images/cart.svg' ?>"
                                                alt="icon">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } ?>
                        <?php
                    } else {
                        ?>
                        <h3>Không tìm thấy kết quả</h3>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>


<?php
get_footer()
    ?>