<?php

/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

session_start();

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}
?>
<div id="id-checkout-page" style="display: none;"></div>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
                <?php if ($checkout->get_checkout_fields()) : ?>

                    <?php do_action('woocommerce_checkout_before_customer_details'); ?>
                    <?php echo apply_filters('woocommerce_pay_order_button_html', '<button style="display: none;" type="submit" class="button alt' . esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : '') . '" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '">' . esc_html($order_button_text) . '</button>'); // @codingStandardsIgnoreLine       
                    ?>
                    <div class="billing-box">
                        <?php do_action('woocommerce_checkout_billing'); ?>
                        <?php do_action('woocommerce_checkout_province'); ?>
                    </div>
                    <div class="note-box">
                        <?php do_action('woocommerce_checkout_shipping'); ?>
                    </div>
                    <?php ?>

                    <div class="method-shipping-custom">
                        <h3 class="title">Phương thức vận chuyển</h3>
                        <?php
                        // Nhận tất cả ID khu vực vận chuyển hiện có của bạn
                        $zone_ids = array_keys(array('') + WC_Shipping_Zones::get_zones());
                        $i = 0;
                        ?>
                        <div class="method-list">
                            <?php
                            //Mô tả chi tiết cho phương thức giao hàng
                            $list_des = get_field('list_time_des_checkout', get_the_ID());
                            // Lặp lại các ID khu vực vận chuyển

                            $method = ($_SESSION['chosen_shipping_methods']) ? [$_SESSION['chosen_shipping_methods']] : WC()->session->get('chosen_shipping_methods');

                            foreach ($zone_ids as $zone_id) {
                                // Lấy đối tượng Vùng vận chuyển
                                $shipping_zone = new WC_Shipping_Zone($zone_id);

                                // Nhận tất cả các giá trị phương thức vận chuyển cho khu vực vận chuyển
                                $shipping_methods = $shipping_zone->get_shipping_methods(true, 'values');

                                // Lặp lại từng phương thức vận chuyển được đặt cho khu vực vận chuyển hiện tại
                                foreach ($shipping_methods as $instance_id => $shipping_method) {
                                    $cost = (float)$shipping_method->cost;
                                    $title = $shipping_method->title;
                                    $id = $shipping_method->id;
                                    $number = $i + 1;
                                    $flat_rate = 'flat_rate:' . $number;
                            ?>

                                    <div class="method-delivery" data-id="<?php echo $id; ?>">
                                        <div class="method-name method-item">
                                            <input <?php echo $method[0] == $flat_rate ? 'checked' : '' ?> type="radio" class="method-tieu-chuan" name="method-delivery" id="method_shipping_<?php echo $id . '_' . $i ?>" value="<?php echo $id . ':' . $i ?>" data-cost="<?php echo number_format($cost, 0, '.', '.') ?>">
                                            <label for="method_shipping_<?php echo $id . '_' . $i ?>" class="">
                                                <?php echo $title ?>
                                            </label>
                                        </div>
                                        <div class="method-time method-item">
                                            <p class="">
                                                <?php echo $list_des[$i]['des'] ?>
                                            </p>
                                        </div>
                                        <div class="method-cots method-item">
                                            <?php $cost = number_format($cost, 0, '.', '.') ?>
                                            <p class="">
                                                <?php echo $cost . ' ₫' ?>
                                            </p>
                                        </div>
                                    </div>
                            <?php
                                    $i++;
                                }
                            }
                            ?>
                            <?php if ($_SESSION['chosen_shipping_methods']) : ?>
                                <input type="hidden" id="shipping_method" name="shipping_method" hidden value="<?php echo $method[0] ?>">
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endif; ?>


                <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                <?php ?>
                <?php do_action('custom_payment_methods'); ?>
                <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
                <?php do_action('woocommerce_after_checkout_form', $checkout); ?>
                <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
            </form>
        </div>
        <div class="col-md-6">
            <div class="cart-custom">
                <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                    <?php $cart = WC()->cart ?>
                    <div class="top-cart">
                        <h3 class="title">
                            Giỏ hàng
                        </h3>
                        <p class="total-qty">
                            (
                            <?php echo $cart->get_cart_contents_count() . ' sản phẩm' ?>)
                        </p>
                    </div>
                    <div class="list-product-cart">
                        <div class="product-all checkbox-box">
                            <input checked type="checkbox" class="product-all-checkbox" id="product-all-checkbox" name="product-all-checkbox">
                            <label for="product-all-checkbox" class="">Tất cả</label>
                        </div>
                        <?php
                        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                            $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                            $quantity = $cart_item['quantity'];
                            if (isset($cart_item['variation_id']) && count($cart_item['variation']) > 0) {
                                $product_variation = $cart_item['variation_id'];
                            }
                            $color = $_product->get_attribute('pa_mau-sac');
                            //Sản phẩm từ id sản phẩm
                            $product = wc_get_product($product_id);
                            $attributes = $product->get_attributes();
                            $variation_id = $cart_item['variation_id'] == '0' ? '' : $cart_item['variation_id'];

                        ?>
                            <div class="item-cart item-cart-woo" id="id-product-<?php echo $product_id . '-' . $variation_id ?>" style="display: none">
                                <div class="chose checkbox-box">
                                    <input type="checkbox" checked class="product-checkbox" id="product-<?php echo $product_id ?>" name="selected_item" value="<?php echo isset($cart_item['variation_id']) && count($cart_item['variation']) > 0 ? $product_variation : $product_id ?>">
                                    <label for="product-<?php echo $product_id ?>" class=""></label>
                                </div>
                                <div class="item-cart-infor">
                                    <?php $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key); ?>
                                    <a href="<?php echo $product_permalink ?>" class="img">
                                        <?php echo $thumbnail ?>
                                    </a>
                                    <div class="title-att">
                                        <a href="<?php echo $product_permalink ?>" class="name">
                                            <?php ?>
                                            <?php echo $product_name ?>
                                        </a>
                                        <?php if (!empty($attributes)) : ?>
                                            <div class="attr">
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
                                        <?php if (!empty($color)) : ?>
                                            <div class="color">
                                                Màu sắc:
                                                <?php echo $color ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="box-qty qty-mobile">
                                            <?php
                                            $price_sale = $product->get_sale_price();
                                            $price_regular = $product->get_regular_price();
                                            ?>
                                            <div class="price">
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
                                                        if ($price_sale != '') : ?>
                                                            <div class="price-sale">
                                                                <span>
                                                                    <?php echo number_format((float) $price_sale, 0, '.', '.'); ?>đ
                                                                </span>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="price-regular">
                                                            <span class="<?php echo $price_sale == '' ? 'off-sale' : '' ?>">
                                                                <?php echo number_format((float) $price_regular, 0, '.', '.'); ?>đ
                                                            </span>
                                                        </div>
                                                    <?php else :
                                                        echo $product->get_price_html();
                                                    endif;
                                                else :
                                                    if ($price_sale != '') : ?>
                                                        <div class="price-sale">
                                                            <span>
                                                                <?php echo number_format((float) $price_sale, 0, '.', '.'); ?>đ
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="price-regular">
                                                        <span class="<?php echo $price_sale == '' ? 'off-sale' : '' ?>">
                                                            <?php echo number_format((float) $price_regular, 0, '.', '.'); ?>đ
                                                        </span>
                                                    </div>
                                                <?php endif;
                                                ?>
                                            </div>
                                            <div class="qty-delete">
                                                <div class="btn-qty">
                                                    <?php
                                                    if ($_product->is_sold_individually()) {
                                                        $min_quantity = 1;
                                                        $max_quantity = 1;
                                                    } else {
                                                        $min_quantity = 0;
                                                        $max_quantity = $_product->get_max_purchase_quantity();
                                                    }

                                                    $product_quantity = woocommerce_quantity_input(
                                                        array(
                                                            'input_name' => "cart[{$cart_item_key}][qty]",
                                                            'input_value' => $cart_item['quantity'],
                                                            'max_value' => $max_quantity,
                                                            'min_value' => $min_quantity,
                                                            'product_name' => $product_name,
                                                        ),
                                                        $_product,
                                                        false
                                                    );

                                                    echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                                                    ?>
                                                </div>
                                                <div class="remove-item">
                                                    <?php
                                                    echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                        'woocommerce_cart_item_remove_link',
                                                        sprintf(
                                                            '<a href="%s" class="hover pc-hover remove remove-custom" aria-label="%s" data-product_id="%s" data-product_sku="%s"><span>Xóa</span><i class="hover pc-hover fa fa-times-circle" aria-hidden="true"></i></a>',
                                                            esc_url(wc_get_cart_remove_url($cart_item_key)),
                                                            esc_html__('Remove this item', 'woocommerce'),
                                                            esc_attr($product_id),
                                                            esc_attr($_product->get_sku())
                                                        ),
                                                        $cart_item_key
                                                    );
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="qty-delete qty-delete-pc">
                                        <div class="btn-qty">
                                            <?php
                                            if ($_product->is_sold_individually()) {
                                                $min_quantity = 1;
                                                $max_quantity = 1;
                                            } else {
                                                $min_quantity = 0;
                                                $max_quantity = $_product->get_max_purchase_quantity();
                                            }

                                            $product_quantity = woocommerce_quantity_input(
                                                array(
                                                    'input_name' => "cart[{$cart_item_key}][qty]",
                                                    'input_value' => $cart_item['quantity'],
                                                    'max_value' => $max_quantity,
                                                    'min_value' => $min_quantity,
                                                    'product_name' => $product_name,
                                                ),
                                                $_product,
                                                false
                                            );

                                            echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                                            ?>
                                        </div>
                                        <div class="remove-item">
                                            <?php
                                            echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                'woocommerce_cart_item_remove_link',
                                                sprintf(
                                                    '<a href="%s" class="hover pc-hover remove remove-custom" aria-label="%s" data-product_id="%s" data-product_sku="%s"><span>Xóa</span><i class="hover pc-hover fa fa-times-circle" aria-hidden="true"></i></a>',
                                                    esc_url(wc_get_cart_remove_url($cart_item_key)),
                                                    esc_html__('Remove this item', 'woocommerce'),
                                                    esc_attr($product_id),
                                                    esc_attr($_product->get_sku())
                                                ),
                                                $cart_item_key
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $price_sale = $product->get_sale_price();
                                $price_regular = $product->get_regular_price();
                                ?>
                                <div class="price price-pc">
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
                                            if ($price_sale != '') : ?>
                                                <div class="price-sale">
                                                    <span>
                                                        <?php echo number_format((float) $price_sale, 0, '.', '.'); ?>đ
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            <div class="price-regular">
                                                <span class="<?php echo $price_sale == '' ? 'off-sale' : '' ?>">
                                                    <?php echo number_format((float) $price_regular, 0, '.', '.'); ?>đ
                                                </span>
                                            </div>
                                        <?php else :
                                            echo $product->get_price_html();
                                        endif;
                                    else :
                                        if ($price_sale != '') : ?>
                                            <div class="price-sale">
                                                <span>
                                                    <?php echo number_format((float) $price_sale, 0, '.', '.'); ?>đ
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="price-regular">
                                            <span class="<?php echo $price_sale == '' ? 'off-sale' : '' ?>">
                                                <?php echo number_format((float) $price_regular, 0, '.', '.'); ?>đ
                                            </span>
                                        </div>
                                    <?php endif;
                                    ?>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <?php
                        $cart_session = wc()->session->get('cart_item_id_1');
                        foreach ($cart_session as $key => $cart_item) {
                            $product_id = $cart_item['id_product'];
                            $_product = wc_get_product($product_id);
                            $product_permalink = $_product->get_permalink();
                            $product_name = $_product->get_name();
                            $quantity = $cart_item['quantity'];
                            if ($cart_item['variation_id'] != '') {
                                $variation_id = $cart_item['variation_id'];
                                $product_variation = wc_get_product($variation_id);
                                $color = $product_variation->get_attribute('pa_mau-sac');
                            }

                            //Sản phẩm từ id sản phẩm
                            $product = wc_get_product($product_id);
                            $attributes = $product->get_attributes();
                        ?>
                            <div class="item-cart item-cart-session" data-quantity="<?php echo $cart_item['quantity'] ?>" data-add_cart="<?php echo $cart_item['add_cart'] ?>" data-product_id="<?php echo $product_id ?>" data-variation_id="<?php $cart_item['variation_id'] ?>">
                                <div class="chose checkbox-box">
                                    <input type="checkbox" data-position="<?php echo $key ?>" checked class="product-checkbox selected_item_session" id="product-session-<?php echo $product_id ?>" name="selected_session_item_<?php echo $product_id ?>" value="<?php echo $product_id ?>" data-variation_id="<?php echo $cart_item['variation_id'] ?>">
                                    <label for="product-session-<?php echo $product_id ?>" class=""></label>
                                </div>
                                <div class="item-cart-infor">
                                    <?php $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key); ?>
                                    <a href="<?php echo $product_permalink ?>" class="img">
                                        <?php echo $thumbnail ?>
                                    </a>
                                    <div class="title-att">
                                        <a href="<?php echo $product_permalink ?>" class="name">
                                            <?php ?>
                                            <?php echo $product_name ?>
                                        </a>
                                        <?php if (!empty($attributes)) : ?>
                                            <div class="attr">
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
                                        <?php if (!empty($color)) : ?>
                                            <div class="color">
                                                Màu sắc:
                                                <?php echo $color ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="box-qty qty-mobile">
                                            <?php
                                            $price_sale = $product->get_sale_price();
                                            $price_regular = $product->get_regular_price();
                                            ?>
                                            <div class="price">
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
                                                        if ($price_sale != '') : ?>
                                                            <div class="price-sale">
                                                                <span>
                                                                    <?php echo number_format((float) $price_sale, 0, '.', '.'); ?>đ
                                                                </span>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="price-regular">
                                                            <span class="<?php echo $price_sale == '' ? 'off-sale' : '' ?>">
                                                                <?php echo number_format((float) $price_regular, 0, '.', '.'); ?>đ
                                                            </span>
                                                        </div>
                                                    <?php else :
                                                        echo $product->get_price_html();
                                                    endif;
                                                else :
                                                    if ($price_sale != '') : ?>
                                                        <div class="price-sale">
                                                            <span>
                                                                <?php echo number_format((float) $price_sale, 0, '.', '.'); ?>đ
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="price-regular">
                                                        <span class="<?php echo $price_sale == '' ? 'off-sale' : '' ?>">
                                                            <?php echo number_format((float) $price_regular, 0, '.', '.'); ?>đ
                                                        </span>
                                                    </div>
                                                <?php endif;
                                                ?>
                                            </div>
                                            <div class="qty-delete">
                                                <div class="btn-qty">
                                                    <?php
                                                    if ($_product->is_sold_individually()) {
                                                        $min_quantity = 1;
                                                        $max_quantity = 1;
                                                    } else {
                                                        $min_quantity = 0;
                                                        $max_quantity = $_product->get_max_purchase_quantity();
                                                    }

                                                    $product_quantity = woocommerce_quantity_input(
                                                        array(
                                                            'input_name' => "cart[{$cart_item_key}][qty]",
                                                            'input_value' => $cart_item['quantity'],
                                                            'max_value' => $max_quantity,
                                                            'min_value' => $min_quantity,
                                                            'product_name' => $product_name,
                                                        ),
                                                        $_product,
                                                        false
                                                    );

                                                    echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                                                    ?>
                                                </div>
                                                <div class="remove-item">
                                                    <a href="#" data-quantity="<?php echo $cart_item['quantity'] ?>" data-add_cart="<?php echo $cart_item['add_cart'] ?>" data-product_id="<?php echo $product_id ?>" data-variation_id="<?php $cart_item['variation_id'] ?>" class="btn-remove-product"><span class="">Xóa</span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="qty-delete qty-delete-pc">
                                        <div class="btn-qty" data-quantity="<?php echo $cart_item['quantity'] ?>" data-add_cart="<?php echo $cart_item['add_cart'] ?>" data-product_id="<?php echo $product_id ?>" data-variation_id="<?php $cart_item['variation_id'] ?>">
                                            <?php
                                            if ($_product->is_sold_individually()) {
                                                $min_quantity = 1;
                                                $max_quantity = 1;
                                            } else {
                                                $min_quantity = 0;
                                                $max_quantity = $_product->get_max_purchase_quantity();
                                            }

                                            $product_quantity = woocommerce_quantity_input(
                                                array(
                                                    'input_name' => "cart[{$cart_item_key}][qty]",
                                                    'input_value' => $cart_item['quantity'],
                                                    'max_value' => $max_quantity,
                                                    'min_value' => $min_quantity,
                                                    'product_name' => $product_name,
                                                ),
                                                $_product,
                                                false
                                            );

                                            echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                                            ?>
                                        </div>
                                        <div class="remove-item">
                                            <a href="#" data-quantity="<?php echo $cart_item['quantity'] ?>" data-add_cart="<?php echo $cart_item['add_cart'] ?>" data-product_id="<?php echo $product_id ?>" data-variation_id="<?php $cart_item['variation_id'] ?>" class="btn-remove-product"><span class="">Xóa</span></a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $price_sale = $product->get_sale_price();
                                $price_regular = $product->get_regular_price();
                                ?>
                                <div class="price price-pc">
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
                                            if ($price_sale != '') : ?>
                                                <div class="price-sale">
                                                    <span>
                                                        <?php echo number_format((float) $price_sale, 0, '.', '.'); ?>đ
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            <div class="price-regular">
                                                <span class="<?php echo $price_sale == '' ? 'off-sale' : '' ?>">
                                                    <?php echo number_format((float) $price_regular, 0, '.', '.'); ?>đ
                                                </span>
                                            </div>
                                        <?php else :
                                            echo $product->get_price_html();
                                        endif;
                                    else :
                                        if ($price_sale != '') : ?>
                                            <div class="price-sale">
                                                <span>
                                                    <?php echo number_format((float) $price_sale, 0, '.', '.'); ?>đ
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="price-regular">
                                            <span class="<?php echo $price_sale == '' ? 'off-sale' : '' ?>">
                                                <?php echo number_format((float) $price_regular, 0, '.', '.'); ?>đ
                                            </span>
                                        </div>
                                    <?php endif;
                                    ?>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <?php do_action('woocommerce_after_cart_table'); ?>
                        <div class="btn-action">
                            <button type="submit" class="button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>">
                                <?php esc_html_e('Update cart', 'woocommerce'); ?>
                            </button>
                        </div>
                        <?php do_action('woocommerce_cart_actions'); ?>

                        <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                        <div class="total-cart">
                            <div class="provisional">
                                Tạm tính (<span class="number">
                                    <?php echo $cart->get_cart_contents_count() ?> sản phẩm
                                </span>)
                            </div>
                            <div class="total-price">
                                <?php $total = $cart->get_cart_subtotal() ?>
                                <?php echo $total ?>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="list-coupon">
                    <p class="title-coupon">Sử dụng mã giảm giá</p>
                    <p class="btn-show-coupon">
                        <span class="">Danh sách mã ưu đãi </span>
                        <img src="<?php echo get_template_directory_uri() . '/images/coupon-list.svg' ?>" alt="show" class="icon">
                    </p>
                    <div class="enter-coupon">
                        <!-- <input type="text" class="" placeholder="Nhập mã ưu đãi">
                            <p class="btn-coupon">Áp dụng</p> -->
                        <div class="woocommerce-form-coupon-toggle">
                            <?php wc_print_notice(apply_filters('woocommerce_checkout_coupon_message', esc_html__('Have a coupon?', 'woocommerce') . ' <a href="#" class="showcoupon">' . esc_html__('Click here to enter your code', 'woocommerce') . '</a>'), 'notice'); ?>
                        </div>

                        <form class="checkout_coupon woocommerce-form-coupon" method="post">
                            <p class="form-row form-row-first">
                                <label for="coupon_code" class="screen-reader-text">
                                    <?php esc_html_e('Coupon:', 'woocommerce'); ?>
                                </label>
                                <input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" id="coupon_code" value="" />
                            </p>

                            <p class="form-row form-row-last">
                                <button type="submit" class="button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>">
                                    <?php esc_html_e('Apply coupon', 'woocommerce'); ?>
                                </button>
                            </p>

                            <div class="clear"></div>
                        </form>

                    </div>
                </div>
                <div id="order_review" class="woocommerce-checkout-review-order">
                    <?php do_action('woocommerce_checkout_order_review'); ?>
                </div>
            </div>
            <div class="custom-method-payment">
                <?php
                if (WC()->cart->needs_payment()) :
                    $available_gateways = WC()->payment_gateways->get_available_payment_gateways();

                    echo '<div class="wc_payment_methods payment_methods methods">';
                    echo '<h3 class="title">Thanh toán</h3>';

                    echo '<div class="list-payment-custom">';

                    if (!empty($available_gateways)) {
                        foreach ($available_gateways as $gateway) {
                ?>
                            <li class="wc_payment_method payment_method_<?php echo esc_attr($gateway->id); ?>">
                                <input id="custom_payment_method_<?php echo esc_attr($gateway->id); ?>" type="radio" class="input-radio" name="payment_method_custom" value="<?php echo esc_attr($gateway->id); ?>" <?php checked($gateway->chosen, true); ?> data-order_button_text="<?php echo esc_attr($gateway->order_button_text); ?>" />

                                <label for="custom_payment_method_<?php echo esc_attr($gateway->id); ?>">
                                    <?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
                                    <?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
                                </label>
                                <?php if ($gateway->has_fields() || $gateway->get_description()) : ?>
                                    <div class="payment_box payment_method_<?php echo esc_attr($gateway->id); ?>">
                                        <?php $gateway->payment_fields(); ?>
                                    </div>
                                <?php endif; ?>
                            </li>
                <?php
                        }
                    } else {
                        echo '<p>';
                        wc_print_notice(apply_filters('woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__('Sorry, it seems that there are no available payment methods. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce') : esc_html__('Please fill in your details above to see available payment methods.', 'woocommerce')), 'notice');
                        echo '</p>';
                    }

                    echo '</div>';

                    echo '</div>';

                endif;
                ?>
            </div>
            <div class="export-order">
                <div class="request-export">
                    <input type="checkbox" class="" id="request-export">
                    <label for="request-export" class="">Yêu cầu xuất hóa đơn</label>
                </div>
                <div class="form-export">
                    <div class="form-row">
                        <input type="text" class="" id="name_company" placeholder="Tên tổ chức/ cá nhân khai thuế">
                    </div>
                    <div class="form-row">
                        <input type="text" class="" id="tax_code" placeholder="Mã số thuế">
                    </div>
                    <div class="form-row">
                        <input type="text" class="" id="unit" placeholder="Đơn vị">
                    </div>
                    <div class="form-row">
                        <input type="text" class="" id="email_company" placeholder="Email nhận hóa đơn">
                    </div>
                </div>
            </div>
            <button class="order-btn">
                Đặt hàng
            </button>
        </div>
    </div>
</div>

<div class="popup-voucher">
    <div class="popup-overplay"></div>
    <div class="popup-body">
        <div class="popup-close">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
                <path d="M14.7247 1.64106C15.1445 1.2161 15.0776 0.584899 14.5724 0.231803C14.0671 -0.121293 13.3167 -0.065048 12.8969 0.359917L7.86683 5.43763L2.83675 0.359917C2.41696 -0.065048 1.66654 -0.121293 1.1613 0.231803C0.656064 0.584899 0.589194 1.2161 1.00899 1.64106L6.31769 7L1.00899 12.3589C0.589194 12.7839 0.656064 13.4151 1.1613 13.7682C1.66654 14.1213 2.41696 14.065 2.83675 13.6401L7.86683 8.56237L12.8969 13.6401C13.3167 14.065 14.0671 14.1213 14.5724 13.7682C15.0776 13.4151 15.1445 12.7839 14.7247 12.3589L9.41597 7L14.7247 1.64106Z" fill="#737C87" />
            </svg>
        </div>
        <div class="popup-header">
            <h3 class="title">Danh sách mã ưu đãi</h3>
        </div>
        <div class="popup-content">
            <div class="input">
                <input type="text" class="" id="voucher_popup" placeholder="Nhập mã ưu đãi">
                <button class="btn-coupon-popup">Áp dụng</button>
            </div>
            <div class="list-voucher">
                <?php
                $applied_coupons = [];
                if (WC()->cart->get_coupons()) {
                    foreach (WC()->cart->get_coupons() as $code => $coupon) {
                        $coupons = new WC_Coupon($coupon);
                        $code = $coupons->get_code();
                        $code = strtoupper($code);
                        array_push($applied_coupons, $code);
                    }
                }
                $args = array(
                    'post_type' => 'shop_coupon',
                    'numberposts' => -1,
                );
                $coupons = get_posts($args);
                foreach ($coupons as $coupon) {
                    $coupon_id = $coupon->ID;
                    $code = $coupon->post_title;
                    $coupon_date = new WC_Coupon($code);
                    $expiry_date = $coupon_date->expiry_date;
                    $current_date = date('Y-M-D');
                    $timestamp1 = strtotime($expiry_date);
                    $timestamp2 = strtotime($current_date);
                    if ($timestamp2 <= $timestamp1) :
                ?>
                        <div class="item-voucher">
                            <p class="title">
                                <?php echo $coupon->post_excerpt ?>
                            </p>
                            <div class="box-voucher">
                                <img src="<?php echo get_template_directory_uri() . '/images/voucher.svg' ?>" alt="icon" class="icon">
                                <div class="content">
                                    <div class="left">
                                        <div class="code-price-discount">
                                            <p class="code btn-copy-code">
                                                <?php echo $coupon->post_title ?>
                                            </p>
                                            <input type="text" class="copyText" id="copyText" value="<?php echo $coupon->post_title ?>" style="position: absolute; left: -9999px;">
                                            <?php
                                            $coupon_amount = get_post_meta($coupon_id, 'coupon_amount', true);
                                            ?>
                                            <p class="price-discount">Giảm
                                                <?php echo number_format($coupon_amount, 0, '.', '.'); ?>đ
                                            </p>
                                        </div>
                                        <div class="date-use">HSD:
                                            <?php echo date('d/m/Y', strtotime($expiry_date)) ?>
                                        </div>
                                    </div>
                                    <?php if (in_array($code, $applied_coupons)) : ?>
                                        <div class="right">
                                            <p class="btn-remove-voucher" data-code="<?php echo $coupon->post_title ?>">Hủy</p>
                                        </div>
                                    <?php else : ?>
                                        <div class="right">
                                            <p class="btn-auto-voucher" data-code="<?php echo $coupon->post_title ?>">Áp dụng</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                <?php
                    endif;
                }
                ?>
            </div>
        </div>
        <div class="popup-clock">
            Đóng
        </div>
    </div>
</div>

<?php
if (is_user_logged_in()) : ?>
    <input hidden type="text" class="check-logged">
<?php endif; ?>