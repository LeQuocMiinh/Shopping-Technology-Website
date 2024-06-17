<section id="feature-program">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php $program_items =  get_field('feature_program', get_the_ID());
                $color_text = '';
                ?>
                <?php foreach ($program_items as $p_key => $content) : ?>
                    <div class="section-wrapper" style="background: url(<?php echo $content['bg_img']['url'] ?>);
                         background-repeat: no-repeat; background-size: cover;">
                        <div class="program-header">
                            <?php $color_text = $content['text_color']; ?>
                            <input id="text_color_active_tab" type="hidden" hidden value="<?php echo $content['text_color']; ?>">
                            <?php foreach ($content['program_item'] as $key => $program) : ?>
                                <div class="item-header <?php echo $key == 0 ? 'active' : '' ?>" data-parent-index=<?php echo $p_key ?> data-index=<?php echo $key ?>>
                                    <div class="name" style="color: <?php echo $key == 0 ? $color_text : '' ?>"><?php echo $program['cate_item']->name ?></div>
                                    <div class="description" style="color: <?php echo $key == 0 ? $color_text : '' ?>"><?php echo $program['content'] ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="program-slider">
                            <?php foreach ($content['program_item'] as $key => $program) : ?>
                                <div class="slider-wrapper">
                                    <div class="<?php echo $key == 0 ? '' : 'hidden-slide' ?> product-list program-feature-carousel owl-carousel owl-theme
                                <?php echo $program['cate_item']->slug ?>" data-parent-index=<?php echo $p_key ?> data-index=<?php echo $key ?>>
                                        <?php
                                        $tax_query = array(
                                            array(
                                                'taxonomy' => 'product_cat',
                                                'field'    => 'term_id',
                                                'terms'    =>  $program['cate_item']->term_id,
                                            ),
                                        );
                                        $allProductsByCate = new WP_Query(
                                            array(
                                                'post_type' => 'product',
                                                'post_status' => 'publish',
                                                'posts_per_page' => -1,
                                                'order' => 'DESC',
                                                'orderby' => 'date',
                                                'tax_query' => $tax_query,
                                            )
                                        );
                                        while ($allProductsByCate->have_posts()) : $allProductsByCate->the_post();
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
                                                        <img src="<?php the_post_thumbnail_url() ?>" alt="<?php the_post_thumbnail_caption() ?>">
                                                    </div>
                                                    <div class="infor">
                                                        <div class="name">
                                                            <div class="name-ellipsi">
                                                                <p class="ellipsi"> 
                                                                    <?php the_title() ?>
                                                                </p>             
                                                            </div>                                                           
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
                                                            <?php if ($stock == 'instock') : ?>
                                                                <?php
                                                                if ($product->get_type() == 'variable') :
                                                                    $default_attributes = $product->get_default_attributes();
                                                                    $id_vari = '';
                                                                    if (!empty($default_attributes)) {
                                                                        foreach ($default_attributes as $default_attribute => $value) {
                                                                            $color_vr = $value;
                                                                            $att = $default_attribute;
                                                                        }
                                                                        $id_vari = get_product_variation_id($color_vr, $product->get_id(), 'attribute_' . $att);
                                                                    }
                                                                    if (count($default_attributes) > 0) :
                                                                        $price_regular = get_post_meta($id_vari, '_regular_price', true);
                                                                        $price_sale = get_post_meta($id_vari, '_sale_price', true);
                                                                        if ($price_sale != '') {
                                                                            $discount = devvn_presentage_bubble($product);
                                                                        } else {
                                                                            $discount = '0%';
                                                                        }
                                                                        if ($price_sale != '') : ?>
                                                                            <div class="price-sale">
                                                                                <span><?php echo number_format((float)$price_sale, 0, '.', '.');  ?>đ</span>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                        <div class="price-regular <?php echo $price_sale == '' ? 'off-sale' : 'on-sale line-through' ?>">
                                                                            <span class="price-text"><?php echo number_format((float)$price_regular, 0, '.', '.'); ?>đ</span>
                                                                            <?php if ($price_sale != '') : ?>
                                                                                <span class="discount"><?php echo $discount ?></span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    <?php else :
                                                                        echo $product->get_price_html();
                                                                    endif;
                                                                else :
                                                                    ?>
                                                                    <?php if ($price_sale != '') : ?>
                                                                        <div class="price-sale">
                                                                            <span><?php echo number_format((float)$price_sale, 0, '.', '.');  ?>đ</span>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div class="price-regular <?php echo $price_sale == '' ? 'off-sale' : 'on-sale line-through' ?>">
                                                                        <span class="price-text"><?php echo number_format((float)$price_regular, 0, '.', '.'); ?>đ</span>
                                                                        <?php if ($price_sale != '') : ?>
                                                                            <span class="discount"><?php echo $discount ?></span>
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
                                                <div class="bot-content">
                                                    <div class="add-pc">
                                                        <a href="<?php the_permalink() ?>" class="detail">
                                                            Chi tiết
                                                            <img src="<?php echo get_template_directory_uri() . '/images/arrow.svg' ?>" alt="icon">
                                                        </a>
                                                        <?php
                                                        if ($product->get_type() == 'variable') {
                                                            $id_var = $id_vari;
                                                            $id_pr = get_the_ID();
                                                        } else {
                                                            $id_pr = get_the_ID();
                                                            $id_var = '';
                                                        }
                                                        ?>
                                                        <div class="add-to-cart add_cart_ajax_session" data-product_id="<?php echo $id_pr ?>" data-variation_id="<?php echo $id_var ?>">
                                                            <img src="<?php echo get_template_directory_uri() . '/images/add-cart.svg' ?>" alt="icon">
                                                        </div>
                                                    </div>
                                                    <div class="add-mobile">
                                                        <div class="add-to-cart add_cart_ajax_session" data-product_id="<?php echo $id_pr ?>" data-variation_id="<?php echo $id_var ?>">
                                                            Thêm vào giỏ
                                                            <img src="<?php echo get_template_directory_uri() . '/images/cart.svg' ?>" alt="icon">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php endwhile;
                                        wp_reset_query(); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
</section>