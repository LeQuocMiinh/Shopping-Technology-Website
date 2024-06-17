<?php

/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

defined('ABSPATH') || exit;

$notes = wc_get_order_notes(array(
	'order_id' => $order->get_id(),
));

?>

<section class="heading-order-details">
	<p>
		Chi tiết đơn hàng <?php echo "#" . $order->get_order_number() ?>
		<span>đặt <?php echo wc_format_datetime($order->get_date_created(), 'H:i d/m/Y') ?></span>
	</p>
	<div class="status-order-list">
		<ol class="woocommerce-OrderUpdates commentlist notes content">
			<?php
			$total_status = 0;
			foreach ($notes as $keyIndex => $note) : ?>
				<?php $total_status += $keyIndex; ?>
				<li class="woocommerce-OrderUpdate comment note <?php echo ($keyIndex == 0) ? 'single-status' : '' ?>" data-index="<?php echo $keyIndex ?>">
					<div class="woocommerce-OrderUpdate-inner comment_container">
						<div class="woocommerce-OrderUpdate-text comment-text">
							<p class="woocommerce-OrderUpdate-meta meta">
								<i class="fa fa-check-circle" aria-hidden="true"></i>
								<?php
								$timestamp = $note->date_created->getTimestamp();
								$formatted_datetime = date('H:i \n\g\à\y d/m/Y', $timestamp);
								echo $formatted_datetime;
								?>
							</p>
							<?php
							$status_now = $order->get_status();
							// Match Vietnamese status descriptions, adjust the pattern if needed.
							if (preg_match('/sang (.+).$/', $note->content, $matches)) {
								$status_to = trim($matches[1]);
								// Use the slug for the switch case
								$status_slug = strtolower(str_replace(' ', '-', $status_to));
								$color = '';
								$desc = '';
								$status_en = '';
								switch ($status_slug) {
									case 'tạm-giữ':
										$color = '#FD7237';
										$desc = 'Đơn hàng đã được tạm giữ';
										$status_en = "on-hold";
										break;
									case 'Đã-hoàn-thành':
										$color = '#12A143';
										$desc = 'Đã giao hàng';
										$status_en = "completed";
										break;
									case 'Đang-xử-lý':
										$color = '#0D6EFD';
										$desc = 'Đang chuẩn bị giao hàng';
										$status_en = "processing";
										break;
									case 'Đang-giao-hàng':
										$color = '#1435C3';
										$desc = 'Đơn hàng đã giao đơn vị vận chuyển, vui lòng chú ý điện thoại';
										$status_en = "delivering";
										break;
									case 'chờ-xử-lý':
										$color = '#FFC021';
										$desc = 'Đơn hàng đã được đặt';
										$status_en = "await-process";
										break;
									case 'chờ-thanh-toán':
										$color = '#1435C3';
										$desc = 'Chờ thanh toán';
										$status_en = "pending";
										break;
								}
							}
							?>
							<div class="woocommerce-OrderUpdate-description description">
								<?php if (!empty($color) && !empty($desc)) : ?>
									<span style="color: <?php echo ($status_now === $status_en) ? $color : '' ?>">
										<?php echo esc_html($status_to); ?>
									</span>
									<p style="color: <?php echo ($status_now === $status_en) ? $color : '' ?>" class=" desc"><?php echo $desc; ?></p>
								<?php endif; ?>
							</div>
							<div class="clear"></div>
						</div>

					</div>
				</li>
			<?php endforeach; ?>
		</ol>
		<div class="see-more-status <?php echo ($total_status < 1) ? 'hide' : ' ' ?>" id="see-more-status">
			<span class="text">Xem thêm</span>
			<img src="<?php echo get_template_directory_uri() . '/images/show.svg' ?>" alt="show" class="">
		</div>
	</div>


</section>
<?php do_action('woocommerce_view_order', $order_id); ?>