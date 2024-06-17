<?php

/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

use Automattic\Jetpack\Constants;

defined('ABSPATH') || exit;
?>
<table class="shop_table woocommerce-checkout-review-order-table">
    <tfoot>

        <tr class="cart-subtotal">
            <th>
                <?php esc_html_e('Tạm tính', 'woocommerce'); ?>
            </th>
            <td>
                <?php wc_cart_totals_subtotal_html(); ?>
            </td>
        </tr>

        <?php if (WC()->cart->get_coupons()) : ?>
            <tr class="cart-discount coupon-">
                <th>Giảm giá</th>
                <td>
                    <?php
                    $discount = 0;
                    foreach (WC()->cart->get_coupons() as $code => $coupon) {
                        $coupons = new WC_Coupon($coupon);
                        $cost = $coupons->get_amount();
                        $discount += $cost;
                    }
                    echo number_format($discount, 0, '.', '.') . ' ₫';
                    ?>
                </td>
            </tr>
            <?php
            $i = 1;
            foreach (WC()->cart->get_coupons() as $code => $coupon) :
            ?>
                <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                    <?php
                    $coupons = new WC_Coupon($coupon);
                    $code = $coupons->get_code();
                    $code = strtoupper($code);
                    $html_remove = '<img src="' . get_template_directory_uri() . '/images/remove-coupon.svg ?>"
            alt="remove" class="icon">';

                    $coupon_html = ' <div class="woocommerce-remove-coupon"
                data-coupon="' . esc_attr($coupons->get_code()) . '">' . __(
                        $code,
                        'woocommerce'
                    ) . '<span>' . $html_remove . '</span></div>';
                    ?>
                    <th><?php echo $coupon_html ?></th>
                    <td><?php wc_cart_totals_coupon_html($coupon); ?></td>
                </tr>
            <?php $i++;
            endforeach; ?>
        <?php else : ?>
            <tr class="cart-discount coupon-">
                <th>Giảm giá</th>
                <td>--</td>
            </tr>
        <?php endif; ?>


        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

            <?php do_action('woocommerce_review_order_before_shipping'); ?>

            <?php wc_cart_totals_shipping_html(); ?>

            <?php do_action('woocommerce_review_order_after_shipping'); ?>

        <?php endif; ?>

        <tr class="fee">
            <th>
                <!-- <?php
                        $coupon_html = '';
                        $cost_coupon = 0;
                        ?>
                <?php $i = 1;
                foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                <?php
                    $coupons = new WC_Coupon($coupon);
                    $cost_coupon = $coupons->get_amount();
                    $code = $coupons->get_code();
                    $code = strtoupper($code);
                    $html_remove = '<img src="' . get_template_directory_uri() . '/images/remove-coupon.svg ?>" alt="remove" class="icon">';
                    $coupon_html = 'Phí giao hàng' . ' <a href="' . esc_url(add_query_arg('remove_coupon', rawurlencode($coupons->get_code()), Constants::is_defined('WOOCOMMERCE_CHECKOUT') ? wc_get_checkout_url() : wc_get_cart_url())) . '" class="woocommerce-remove-coupon" data-coupon="' . esc_attr($coupons->get_code()) . '">' . __($code, 'woocommerce') . '<span>' . $html_remove . '</span></a>';
                endforeach;
                ?>
                <?php echo WC()->cart->get_coupons() ? $coupon_html : 'Phí giao hàng' ?> -->
                Phí giao hàng
            </th>
            <td>
                <?php
                $method = ($_SESSION['chosen_shipping_methods']) ? [$_SESSION['chosen_shipping_methods']] : WC()->session->get('chosen_shipping_methods');
                $zone_ids = array_keys(array('') + WC_Shipping_Zones::get_zones());
                $i = 1;
                if ($method[0] == 'local_pickup:4') {
                    echo "0 ₫";
                } else {
                    // Lặp lại các ID khu vực vận chuyển
                    foreach ($zone_ids as $zone_id) {
                        // Lấy đối tượng Vùng vận chuyển
                        $shipping_zone = new WC_Shipping_Zone($zone_id);

                        // Nhận tất cả các giá trị phương thức vận chuyển cho khu vực vận chuyển
                        $shipping_methods = $shipping_zone->get_shipping_methods(true, 'values');

                        // Lặp lại từng phương thức vận chuyển được đặt cho khu vực vận chuyển hiện tại
                        foreach ($shipping_methods as $instance_id => $shipping_method) {
                            $flat_rate = 'flat_rate:' . $i;
                            if ($method[0] == $flat_rate) {
                                $cost = (int) $shipping_method->cost - (int) $cost_coupon;
                                echo number_format($shipping_method->cost, 0, '.', '.') . ' ₫';
                            }
                            $i++;
                        }
                    }
                }
                ?>
                <input type="hidden" id="chosen_shipping_methods" value="<?php echo !empty($method) ? $method[0] : '' ?>">
            </td>
        </tr>

        <?php do_action('woocommerce_review_order_before_order_total'); ?>

        <tr class="order-total">
            <th>
                <?php esc_html_e('Tổng', 'woocommerce'); ?>
            </th>
            <td>
                <?php wc_cart_totals_order_total_html(); ?>
            </td>
        </tr>

        <?php do_action('woocommerce_review_order_after_order_total'); ?>

    </tfoot>
</table>