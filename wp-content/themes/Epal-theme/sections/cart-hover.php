<?php
$cart = wc()->session->get('cart_item_id_1'); ?>
<div class="cart-hover">
    <div class="cart-content">
        <div class="cart-top">
            <span class="count">
                <?php
                $number_item_cart = 0;
                foreach ($cart as $item_cart) {
                    $number_item_cart += (int) $item_cart['quantity'];
                }
                ?>
                <?php echo $number_item_cart ?> sản phẩm
            </span>
            <a href="<?php echo ($number_item_cart > 0) ? '/thanh-toan' : '/gio-hang' ?>" id="cart-link">
                Xem tất cả
                <img src="<?php echo get_template_directory_uri() . '/images/see-all.svg' ?>" alt="icon">
            </a>
        </div>
        <div class="cart-list">
            <?php
            foreach ($cart as $cart_item) :
                $quantity = $cart_item['quantity'];
                $product_id = $cart_item['id_product'];
                $product_att = wc_get_product($product_id);
                $link = $product_att->get_permalink();
                $attributes = $product_att->get_attributes();
                $price_sale = $product_att->get_sale_price();
                $price_regular = $product_att->get_regular_price();
                $variation_id = $cart_item['variation_id'];
                if ($variation_id) {
                    $product_var = wc_get_product($variation_id);
                    $color = $product_var->get_attribute('pa_mau-sac');
                }
                $img = get_the_post_thumbnail_url($product_id);
                $name = get_the_title($product_id);
                if ($price_sale != '') {
                    $discount = devvn_presentage_bubble($product_att);
                } else {
                    $discount = '0%';
                }
            ?>
                <div class="item">
                    <a href="<?php echo $link ?>" class="cart-sub-item">
                        <div class="image">
                            <?php $img = get_the_post_thumbnail_url($product_id); ?>
                            <img src="<?php echo $img ?>" alt="<?php echo $name ?>">
                        </div>
                        <div class="info-cart">
                            <div class="name">
                                <p>
                                    <?php echo $name ?>
                                </p>
                            </div>
                            <div class="text-grey">
                                <?php if (!empty($attributes)) : ?>
                                    <div>
                                        <?php
                                        foreach ($attributes as $attribute) {
                                            $visible = $attribute->get_visible();
                                            if ($visible != 0) {
                                                $name = $attribute->get_name();
                                                $terms = wp_get_post_terms($product_id, $name, 'all');
                                                foreach ($terms as $term) {
                                                    echo $term->name . ' ';
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($color) : ?>
                                    <div> Màu:
                                        <?php echo $color ?>
                                    </div>
                                <?php endif; ?>
                                <div> Số lượng:
                                    <?php echo $quantity ?>
                                </div>
                            </div>
                            <div class="price">
                                <?php
                                if ($product_att->get_type() == 'variable') {
                                    $variation_obj = new WC_Product_variation($variation_id);
                                    $stock = $variation_obj->get_stock_status();
                                } else {
                                    $stock = $product_att->get_stock_status();
                                }
                                ?>
                                <?php if ($stock == 'instock') : ?>
                                    <?php
                                    if ($product_att->get_type() == 'variable') :
                                        $default_attributes = $product_att->get_default_attributes();
                                        $id_vari = $variation_id;
                                        if (count($default_attributes) > 0) :
                                            $price_regular = get_post_meta($id_vari, '_regular_price', true);
                                            $price_sale = get_post_meta($id_vari, '_sale_price', true);
                                            if ($price_sale != '') {
                                                $discount = devvn_presentage_bubble($product_att);
                                            } else {
                                                $discount = '0%';
                                            }
                                            if ($price_sale != '') : ?>
                                                <div class="price-sale">
                                                    <span>
                                                        <?php echo number_format((float) $price_sale, 0, '.', '.'); ?>đ
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            <div class="price-regular">
                                                <span class="price <?php echo $price_sale == '' ? 'off-sale' : '' ?>">
                                                    <?php echo number_format((float) $price_regular, 0, '.', '.'); ?>đ
                                                </span>
                                                <?php if ($price_sale != '') : ?>
                                                    <span class="discount">
                                                        <?php echo $discount ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php else :
                                            echo $product_att->get_price_html();
                                        endif;
                                    else : ?>
                                        <?php if ($price_sale != '') : ?>
                                            <div class="price-sale">
                                                <span>
                                                    <?php echo number_format((float) $price_sale, 0, '.', '.'); ?>đ
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="price-regular">
                                            <span class="price <?php echo $price_sale == '' ? 'off-sale' : '' ?>">
                                                <?php echo number_format((float) $price_regular, 0, '.', '.'); ?>đ
                                            </span>
                                            <?php if ($price_sale != '') : ?>
                                                <span class="discount">
                                                    <?php echo $discount ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                <?php endif;
                                endif;
                                ?>
                                <?php if ($stock == 'outofstock') : ?>
                                    <div class="custom-stock">
                                        <span>
                                            <?php echo get_field('text_out_stock', 'option') ?>
                                        </span>
                                    </div>

                                <?php endif; ?>
                                <?php if ($stock == 'onbackorder') : ?>
                                    <div class="custom-stock">
                                        <span>
                                            <?php echo get_field('text_stocking', 'option') ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="cart-total">
            <span class="total_text">
                Tổng cộng
            </span>
            <span class="total_number">
                <?php $cart = WC()->cart; ?>
                <?php echo number_format($cart->total, 0, '.', '.'); ?>đ
            </span>
        </div>
    </div>
</div>