<?php

/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

defined('ABSPATH') || exit;

$customer_id = get_current_user_id();

if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __('Billing address', 'woocommerce'),
			'shipping' => __('Shipping address', 'woocommerce'),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __('Billing address', 'woocommerce'),
		),
		$customer_id
	);
}

$oldcol = 1;
$col = 1;
?>
<div class="title-mobile col-12"><a href="javascript: history.back(1)" id="goBack"><img src="<?php echo get_template_directory_uri() ?>/images/go-back.svg"></a>
	<h3>
		Sổ địa chỉ
	</h3>
</div>
<div class="my-address-container">

	<div class="heading-address">

		<h1 class="title">
			Sổ địa chỉ
		</h1>
		<div class="button-add-address" id="button-add-address">
			<span class="plus">+</span><span>
				<?php esc_html_e('Thêm địa chỉ mới ', 'woocommerce'); ?>
			</span>
		</div>

		<!--form address-->
		<div class="form-address form-add-address">
			<div class="form-add-address-wrapper">
				<div class="fields-address" id="fields-address">
					<div class="heading-form-add-address">
						<h1>Thêm địa chỉ nhận hàng</h1>
						<img class="close-form-add-address" src="<?php echo get_template_directory_uri() . '/images/close.svg' ?>" alt="">
					</div>
					<div class="custom-row row-1">
						<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
							<label for="new_first_name">
								<?php esc_html_e('Họ và tên: ', 'woocommerce'); ?><span class="required-input"> *</span>
							</label>
							<input type="text" placeholder="Họ và tên" class="woocommerce-Input woocommerce-Input--text input-text" name="new_name" id="new_name" autocomplete="given-name" value="" />
						</p>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="new_phone">
								<?php esc_html_e('Số điện thoại: ', 'woocommerce'); ?><span class="required-input"> *</span>
							</label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="new_phone" id="new_phone" value="" />
						</p>
					</div>

					<div class="custom-row row-2">
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<input placeholder="Địa chỉ *" type="address" class="woocommerce-Input woocommerce-Input--address input-text" name="new_address" id="new_address" autocomplete="address" value="" />
						</p>
					</div>

					<div class="custom-row row-3 check-logged" id="single-input">
						<p class="form-row form-row-first" id="city-option_field">
							<span class="woocommerce-input-wrapper">
								<select name="city-option" id="city-option">
								</select>
							</span>
						</p>

						<p class="form-row form-row-first" id="district-option_field">
							<span class="woocommerce-input-wrapper">
								<select name="district-option" id="district-option">

								</select>
							</span>
						</p>

						<p class="form-row form-row-first" id="wards-option_field">
							<span class="woocommerce-input-wrapper">
								<select name="wards-option" id="wards-option">
								</select>
							</span>
						</p>
					</div>

					<div class="custom-row row-4">
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<textarea placeholder="Ghi chú" name="note" id="note" cols="30" rows="4"></textarea>
						</p>
					</div>
					<?php global $wpdb;
					$wpdb_prefix = $wpdb->prefix;
					$wpdb_tablename = $wpdb_prefix . 'users_address';
					$id_user = get_current_user_id();
					$query = "SELECT * FROM $wpdb_tablename WHERE user_id = $id_user";
					$result = $wpdb->get_results($query);
					?>
					<div class="custom-row row-5 group-checkbox-default-address <?php echo (empty($result)) ? 'checked' : '' ?>">
						<input <?php echo (empty($result)) ? 'checked' : '' ?> type="checkbox" name="setting_default_address " id="setting_default_address">
						<label for="setting_default_address">Đặt làm địa chỉ mặc
							định</label>
					</div>
				</div>
				<div class="button-apply">
					<button class="btn-apply-address" id="btn-apply-address">Áp dụng</button>
				</div>
			</div>

		</div>

		<div class="form-address form-update-address">
			<div class="form-update-address-wrapper">
				<input type="hidden" name="idRow" id="idRow" value="">
				<div class="fields-address" id="fields-address">
					<div class="heading-form-update-address">
						<h1>Sửa địa chỉ nhận hàng</h1>
						<img class="close-form-add-address" src="<?php echo get_template_directory_uri() . '/images/close.svg' ?>" alt="">
					</div>
					<div class="custom-row row-1">
						<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
							<label for="new_first_name">
								<?php esc_html_e('Họ và tên: ', 'woocommerce'); ?><span class="required-input"> *</span>
							</label>
							<input type="text" placeholder="Họ và tên *" class="woocommerce-Input woocommerce-Input--text input-text" name="new_name" id="new_name" autocomplete="given-name" value="" />
						</p>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="new_phone">
								<?php esc_html_e('Số điện thoại: ', 'woocommerce'); ?><span class="required-input"> *</span>
							</label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="new_phone" id="new_phone" value="" />
						</p>
					</div>

					<div class="custom-row row-2">
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<input placeholder="Địa chỉ" type="address" class="woocommerce-Input woocommerce-Input--address input-text" name="new_address" id="new_address" autocomplete="address" value="" />
						</p>
					</div>

					<div class="custom-row row-3 check-logged" id="single-input">
						<p class="form-row form-row-first" id="city-option_field">
							<span class="woocommerce-input-wrapper">
								<select name="city-option" id="city-option">
								</select>
							</span>
						</p>

						<p class="form-row form-row-first" id="district-option_field">
							<span class="woocommerce-input-wrapper">
								<select name="district-option" id="district-option">

								</select>
							</span>
						</p>

						<p class="form-row form-row-first" id="wards-option_field">
							<span class="woocommerce-input-wrapper">
								<select name="wards-option" id="wards-option">
								</select>
							</span>
						</p>
					</div>

					<div class="custom-row row-4">
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<textarea placeholder="Ghi chú" name="note" id="note" cols="30" rows="4"></textarea>
						</p>
					</div>

					<div class="custom-row row-5 group-checkbox-default-address">
						<input type="checkbox" name="setting_default_address" id="setting_default_address_update">
						<label for="setting_default_address_update">Đặt làm địa chỉ mặc
							định</label>
					</div>
				</div>
				<div class="button-update">
					<button class="btn-update-address" id="btn-update-address">Cập nhật</button>
				</div>
			</div>

		</div>
	</div>

	<?php if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) : ?>
		<div class="my-address-body">
		<?php endif; ?>
		<?php


		global $wpdb;
		$wpdb_prefix = $wpdb->prefix;
		$wpdb_tablename = $wpdb_prefix . 'users_address';
		$id_user = get_current_user_id();

		// Số lượng item trên mỗi trang
		$items_per_page = 2;

		// Trang hiện tại
		$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
		$current_url = $protocol . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$path = parse_url($current_url, PHP_URL_PATH);
		$parts = explode('/', rtrim($path, '/'));
		$current_page = end($parts) === 'edit-address' ? 1 : end($parts);

		// Tính offset
		$offset = ($current_page - 1) * $items_per_page;

		$query = "SELECT * FROM $wpdb_tablename WHERE user_id = $id_user ORDER BY default_value DESC LIMIT $items_per_page OFFSET $offset";

		$result = $wpdb->get_results($query);


		if ($result) : ?>
			<div class="list-address">
				<?php foreach ($result as $keyIndex => $row) : ?>
					<div class="form-item-address" data-id="<?php echo $row->ID ?>">
						<div class="form-item-address-wrapper">
							<div class="heading-form-item-address">
								<input type="radio" <?php echo ($row->default_value == 1) ? 'checked' : '' ?> name="setting_default_address_item" id="setting_default_address_item-<?php echo $row->ID ?>">
								<label id="user_name" for="setting_default_address_item-<?php echo $row->ID ?>">
									<?php echo $row->user_name ?>
								</label>
								<?php if ($row->default_value == 1) : ?>
									<p>
										<?php echo ($row->default_value == 1) ? 'Mặc định' : '' ?>
									</p>
								<?php endif; ?>
							</div>
							<div class="body-form-item-address">
								<div id="user_address" class="item" data-id-province="<?php echo $row->user_province_id ?>" data-id-district="<?php echo $row->user_district_id ?>" data-id-ward="<?php echo $row->user_ward_id ?>" data-address="<?php echo $row->user_address ?>" data-name-province="<?php echo $row->user_province_name ?>" data-name-district="<?php echo $row->user_district_name ?>" data-name-ward="<?php echo $row->user_ward_name ?>">
									<div class="address">
										Địa chỉ:
										<?php echo $row->user_address . ', ' . $row->user_province_name . ', ' . $row->user_district_name . ', ' . $row->user_ward_name ?>
									</div>
									<div class="phone">
										Số điện thoại: <span id="user_phone">
											<?php echo $row->user_phone; ?>
										</span>
									</div>
									<textarea style="display: none" name="user_note" id="user_note" cols="30" rows="10"><?php echo $row->user_note ?></textarea>
								</div>
								<div class="button-config-address">
									<button class="btn-edit-address" id="btn-edit-address">Chỉnh sửa</button>
									<?php if ($row->default_value == 0) : ?>
										<button class="btn-remove-address" id="btn-remove-address">Xoá</button>
									<?php endif ?>
								</div>
							</div>
						</div>

					</div>
				<?php endforeach; ?>

				<!-- Phân trang -->
				<?php
				$total_items = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb_tablename WHERE user_id = $id_user");
				$total_pages = ceil($total_items / $items_per_page);
				$big = 999999999; // Define big variable for str_replace function
				if ($total_pages > 1) {
					echo '<div class="pagenavi">';
					echo paginate_links(
						array(
							'base' => add_query_arg('paged', '%#%'),
							'format' => '',
							'current' => max(1, $current_page),
							'total' => $total_pages,
							'prev_text' => __('<i class="fa fa-angle-left"></i>', 'devvn'),
							'next_text' => __('<i class="fa fa-angle-right"></i>', 'devvn'),
						)
					);

					echo '</div>';
				}
				?>
				<!-- Kết thúc phân trang -->

			</div>
		<?php endif; ?>
		<?php if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) : ?>
		</div>
	<?php
		endif; ?>
</div>