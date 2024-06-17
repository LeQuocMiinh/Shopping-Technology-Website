<?php

/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.6.0
 */

defined('ABSPATH') || exit;

$show_shipping = !wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>
<section class="woocommerce-customer-details">

	<section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
		<div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address ">
			<h2 class="woocommerce-column__title">
				<?php esc_html_e('Thông tin nhận hàng', 'woocommerce'); ?>
			</h2>
			<div class="fullname-receive">
				<?php $name_other = get_post_meta($order->id, 'billing_name_other', true) ?>

				<p class="woocommerce-customer-details--name">
					<?php
					if ($name_other) {
						echo $name_other;
					} else {
						echo esc_html($order->get_billing_first_name());
					}
					?>
				</p>

			</div>
			<div class="address-receive">
				<label>
					<?php esc_html_e('Địa chỉ: ', 'woocommerce'); ?>
				</label>
				<p class="text">
					<?php echo  get_post_meta($order->id, 'address_detail', true) . ', ' . get_post_meta($order->id, 'address_wards', true) . ', ' . get_post_meta($order->id, 'address_district', true) . ', ' . get_post_meta($order->id, 'address_city', true);  ?>
				</p>
			</div>
			<div class="phone-receive">
				<label>
					<?php esc_html_e('Số điện thoại: ', 'woocommerce'); ?>
				</label>
				<p class="woocommerce-customer-details--phone">
					<?php $phone_other = get_post_meta($order->id, 'billing_phone_other', true);
					if ($phone_other) {
						echo $phone_other;
					} else {
						echo get_post_meta($order->id, 'billing_custom_phone', true);
					}
					?>
				</p>
			</div>
		</div>

		<div class="woocommerce-column woocommerce-column--2 woocommerce-column--payment">
			<h2 class="woocommerce-column__title">
				<?php esc_html_e('Thông tin thanh toán', 'woocommerce'); ?>
			</h2>
			<div class="fullname-receive">
				<?php $name_other = get_post_meta($order->id, 'billing_name_other', true) ?>

				<p class="woocommerce-customer-details--name">
					<?php
					if ($name_other) {
						echo $name_other;
					} else {
						echo esc_html($order->get_billing_first_name());
					}
					?>
				</p>

			</div>
			<div class="phone-receive">
				<label>
					<?php esc_html_e('Số điện thoại: ', 'woocommerce'); ?>
				</label>

				<p class="woocommerce-customer-details--phone">
					<?php $phone_other = get_post_meta($order->id, 'billing_phone_other', true);
					if ($phone_other) {
						echo $phone_other;
					} else {
						echo get_post_meta($order->id, 'billing_custom_phone', true);
					}
					?>
				</p>

			</div>
		</div>

	</section>
	<?php do_action('woocommerce_order_details_after_customer_details', $order); ?>

</section>