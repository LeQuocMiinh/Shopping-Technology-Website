<?php
// Thêm số lượng sản phâm có trong giỏ hàng
function display_cart_count()
{
    $count = WC()->cart->get_cart_contents_count();
    echo '<span class="cart-count">Có <b>' . esc_html($count) . '</b> sản phẩm</span>';
}
add_action('woocommerce_before_cart', 'display_cart_count');


//THêm phương thức thanh toán
function add_custom_payment_methods()
{
    if (WC()->cart->needs_payment()):
        $available_gateways = WC()->payment_gateways->get_available_payment_gateways();

        echo '<div class="custom-hidden wc_payment_methods payment_methods methods">';
        echo '<h3 class="title">Thanh toán</h3>';

        echo '<div class="list-payment">';

        if (!empty($available_gateways)) {
            foreach ($available_gateways as $gateway) {
                wc_get_template('checkout/payment-method.php', array('gateway' => $gateway));
            }
        } else {
            echo '<p>';
            wc_print_notice(apply_filters('woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__('Sorry, it seems that there are no available payment methods. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce') : esc_html__('Please fill in your details above to see available payment methods.', 'woocommerce')), 'notice');
            echo '</p>';
        }

        echo '</div>';

        echo '</div>';

    endif;
}

add_action('custom_payment_methods', 'add_custom_payment_methods');


//Ẩn thanh nhập mã giảm giá trong woocommer

add_filter('woocommerce_coupons_enabled', 'disable_coupon_field_on_checkout');

function disable_coupon_field_on_checkout($enabled)
{
    if (is_checkout()) {
        $enabled = false;
    }
    return $enabled;
}