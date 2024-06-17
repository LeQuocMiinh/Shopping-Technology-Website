<?php

/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined('ABSPATH') || exit;
$order_items = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));
$show_purchase_note = $order->has_status(apply_filters('woocommerce_purchase_note_order_statuses', array('completed', 'processing')));
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads = $order->get_downloadable_items();
$show_downloads = $order->has_downloadable_item() && $order->is_download_permitted();
?>
<?php
$cart_session = wc()->session->get('cart_item_id_1');
if ($cart_session) {
?>
    <div id="thankyou-ajax-update-cart"></div>
<?php
}
?>
<div class="container">
    <div class="woocommerce-order" id="thank-you">
        <?php
        if ($order) :
            do_action('woocommerce_before_thankyou', $order->get_id());
        ?>

            <?php if ($order->has_status('failed')) : ?>

                <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
                    <?php esc_html_e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?>
                </p>

                <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
                    <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay">
                        <?php esc_html_e('Pay', 'woocommerce'); ?>
                    </a>
                    <?php if (is_user_logged_in()) : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button pay">
                            <?php esc_html_e('My account', 'woocommerce'); ?>
                        </a>
                    <?php endif; ?>
                </p>

            <?php else : ?>
                <?php $user_id = get_current_user_id();
                $user = get_userdata($user_id); ?>
                <div class="heading-order-overview">
                    Xin chào <?php echo esc_attr($user->display_name); ?>,
                </div>
                <?php wc_get_template('checkout/order-received.php', array('order' => $order)); ?>

                <div class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

                    <div class="woocommerce-order-overview__order order">
                        <label for="">
                            <?php esc_html_e('Order number:', 'woocommerce'); ?>
                        </label>
                        <a href="<?php echo esc_url($order->get_view_order_url()); ?>">
                            <?php echo "#" . $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                            ?>
                        </a>
                        <a href="/" class="back-home">Về trang chủ <i class="fa fa-chevron-right"></i></a>
                    </div>

                    <div class="woocommerce-order-overview__date date">
                        <label for=""> <?php esc_html_e('Ngày mua hàng:', 'woocommerce'); ?></label>
                        <strong>
                            <?php echo date("H:i d/m/Y", strtotime($order->get_date_created())); ?>
                        </strong>
                    </div>
                </div>

            <?php endif; ?>

            <!-- <?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?> -->
            <?php do_action('woocommerce_thankyou', $order->get_id()); ?>

        <?php else : ?>

            <?php wc_get_template('checkout/order-received.php', array('order' => false)); ?>

        <?php endif; ?>
        <section class="woocommerce-order-details-thankyou">
            <?php do_action('woocommerce_order_details_before_order_table', $order); ?>

            <ul class="woocommerce_order_details_thankyou">

                <?php
                do_action('woocommerce_order_details_before_order_table_items', $order);

                foreach ($order_items as $item_id => $item) :
                    $product = $item->get_product(); ?>
                    <?php
                    wc_get_template(
                        'order/order-details-item-thankyou.php',
                        array(
                            'order' => $order,
                            'item_id' => $item_id,
                            'item' => $item,
                            'show_purchase_note' => $show_purchase_note,
                            'purchase_note' => $product ? $product->get_purchase_note() : '',
                            'product' => $product,
                        )
                    ); ?>
                <?php endforeach;

                do_action('woocommerce_order_details_after_order_table_items', $order);
                ?>

            </ul>
            <div class="footer-table-wrapper">
                <?php
                if ($order->get_customer_note()) :
                ?>
                    <div class="item">
                        <label>
                            <?php esc_html_e('Ghi chú:', 'woocommerce'); ?>
                        </label>
                        <div class="text">
                            <?php echo wp_kses_post(nl2br(wptexturize($order->get_customer_note()))); ?>
                        </div>
                    </div>
                <?php
                endif;

                foreach ($order->get_order_item_totals() as $key => $total) {
                ?>
                    <div class="item row">
                        <label class="col-6">
                            <?php
                            if ('order_total' === $key) {
                                echo esc_html__('Tổng thanh toán', 'your-text-domain');
                            } else {
                                echo esc_html($total['label']);
                            }
                            ?>
                        </label>
                        <div class="text col-6">
                            <?php echo ('payment_method' === $key) ? esc_html($total['value']) : wp_kses_post($total['value']); ?>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>

            <?php do_action('woocommerce_order_details_after_order_table', $order); ?>
        </section>
        <section class="woocommerce-customer-details">

            <section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
                <div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address ">
                    <h2 class="woocommerce-column__title">
                        <?php esc_html_e('Thông tin nhận hàng', 'woocommerce'); ?>
                    </h2>
                    <div class="fullname-receive row">
                        <?php $name_other = get_post_meta($order->id, 'billing_name_other', true) ?>
                        <label for="" class="col-6">Tên người nhận</label>
                        <p class="woocommerce-customer-details--name col-6">
                            <?php
                            if ($name_other) {
                                echo $name_other;
                            } else {
                                echo esc_html($order->get_billing_first_name());
                            }
                            ?>
                        </p>

                    </div>
                    <div class="phone-receive row">
                        <label class="col-6">
                            <?php esc_html_e('Số điện thoại ', 'woocommerce'); ?>
                        </label>
                        <p class="woocommerce-customer-details--phone col-6">
                            <?php $phone_other = get_post_meta($order->id, 'billing_phone_other', true);
                            if ($phone_other) {
                                echo $phone_other;
                            } else {
                                echo get_post_meta($order->id, 'billing_custom_phone', true);
                            }
                            ?>
                        </p>
                    </div>
                    <div class="address-receive row">
                        <label class="col-6">
                            <?php esc_html_e('Địa chỉ ', 'woocommerce'); ?>
                        </label>
                        <p class="text col-6">
                            <?php echo  get_post_meta($order->id, 'address_detail', true) . ', ' . get_post_meta($order->id, 'address_wards', true) . ', ' . get_post_meta($order->id, 'address_district', true) . ', ' . get_post_meta($order->id, 'address_city', true);  ?>
                        </p>
                    </div>

                </div>

                <div class="woocommerce-column woocommerce-column--2 woocommerce-column--payment">
                    <h2 class="woocommerce-column__title">
                        <?php esc_html_e('Thông tin thanh toán', 'woocommerce'); ?>
                    </h2>
                    <div class="footer-table-wrapper">
                        <?php
                        foreach ($order->get_order_item_totals() as $key => $total) {
                        ?>
                            <div class="item row">
                                <label class="col-6">
                                    <?php
                                    if ('order_total' === $key) {
                                        echo esc_html__('Tổng thanh toán:', 'your-text-domain');
                                    } else {
                                        echo esc_html($total['label']);
                                    }
                                    ?>
                                </label>
                                <div class="text col-6">
                                    <?php echo ('payment_method' === $key) ? esc_html($total['value']) : wp_kses_post($total['value']); ?>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>

            </section>
            <?php do_action('woocommerce_order_details_after_customer_details', $order); ?>

        </section>
        <div class="note-thankyou">
            <p class="line-1">Trân trọng,</p>
            <p class="line-2">Đội ngũ Max Smart</p>
            <p class="line-3">Bạn có thắc mắc? Liên hệ chúng tôi <a href="/lien-he">tại đây</a>.</p>
        </div>
        <!-- <div class="button flex-wrap">
        <a class="back-home" href="<php echo home_url(); ?>">
            Quay lại trang chủ
        </a>
        <a class="continue" href="<php echo esc_url(wc_get_page_permalink('shop')); ?>">
            Tiếp tục mua sắm
        </a>

    </div> -->

    </div>
</div>