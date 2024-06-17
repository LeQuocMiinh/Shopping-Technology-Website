<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
	<div class="container" id="header-single">
		<div class="row box-row">
			<div class="img-col">
				<?php
				/**
				 * Hook: woocommerce_before_single_product_summary.
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action('woocommerce_before_single_product_summary');
				?>
			</div>
			<div class="content-col">
				<div class="summary entry-summary">
					<?php
					/**
					 * Hook: woocommerce_single_product_summary.
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_rating - 10
					 * @hooked woocommerce_template_single_price - 10
					 * @hooked woocommerce_template_single_excerpt - 20
					 * @hooked woocommerce_template_single_add_to_cart - 30
					 * @hooked woocommerce_template_single_meta - 40
					 * @hooked woocommerce_template_single_sharing - 50
					 * @hooked WC_Structured_Data::generate_product_data() - 60
					 */
					do_action('woocommerce_single_product_summary');
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="container" id="content-single">
		<div class="row justify-content-between">
			<?php
			/**
			 * Hook: woocommerce_after_single_product_summary.
			 *
			 * @hooked woocommerce_output_product_data_tabs - 10
			 * @hooked woocommerce_upsell_display - 15
			 * @hooked woocommerce_output_related_products - 20
			 */
			do_action('woocommerce_after_single_product_summary');
			?>
		</div>
	</div>
</div>

<?php if(!is_user_logged_in()): ?>
<div class="popup-address not-logged">
	<div class="popup-overplay"></div>
	<div class="popup-content">
		<div class="close-popup">
			<svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
				<path d="M14.7247 1.64106C15.1445 1.2161 15.0776 0.584899 14.5724 0.231803C14.0671 -0.121293 13.3167 -0.065048 12.8969 0.359917L7.86683 5.43763L2.83675 0.359917C2.41696 -0.065048 1.66654 -0.121293 1.1613 0.231803C0.656064 0.584899 0.589194 1.2161 1.00899 1.64106L6.31769 7L1.00899 12.3589C0.589194 12.7839 0.656064 13.4151 1.1613 13.7682C1.66654 14.1213 2.41696 14.065 2.83675 13.6401L7.86683 8.56237L12.8969 13.6401C13.3167 14.065 14.0671 14.1213 14.5724 13.7682C15.0776 13.4151 15.1445 12.7839 14.7247 12.3589L9.41597 7L14.7247 1.64106Z" fill="#737C87" />
			</svg>
		</div>
		<div class="popup-header">
			<h3 class="title">
				Chọn địa chỉ nhận hàng
			</h3>
		</div>
		<div class="popup-body">
			<div class="select-option">
				<select name="city-option" id="city-option" class="">
					<option value="0">Chọn Tỉnh/ Thành Phố</option>
				</select>
			</div>
			<div class="select-option">
				<select name="district-option" id="district-option" class="">
					<option value="0">Chọn Quận/ Huyện</option>
				</select>
			</div>
			<div class="select-option">
				<select name="wards-option" id="wards-option" class="">
					<option value="0" class="">Chọn Phường/ Xã</option>
				</select>
			</div>
		</div>
		<div class="popup-btn-apply">
			<button class="btn-apply">
				Áp dụng
			</button>
		</div>
	</div>
</div>
<?php else: ?>
<input hidden type="text" class="check-logged">
<div class="popup-address logged">
	<div class="popup-overplay"></div>
	<div class="popup-content">
		<div class="close-popup">
			<svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none">
				<path d="M14.7247 1.64106C15.1445 1.2161 15.0776 0.584899 14.5724 0.231803C14.0671 -0.121293 13.3167 -0.065048 12.8969 0.359917L7.86683 5.43763L2.83675 0.359917C2.41696 -0.065048 1.66654 -0.121293 1.1613 0.231803C0.656064 0.584899 0.589194 1.2161 1.00899 1.64106L6.31769 7L1.00899 12.3589C0.589194 12.7839 0.656064 13.4151 1.1613 13.7682C1.66654 14.1213 2.41696 14.065 2.83675 13.6401L7.86683 8.56237L12.8969 13.6401C13.3167 14.065 14.0671 14.1213 14.5724 13.7682C15.0776 13.4151 15.1445 12.7839 14.7247 12.3589L9.41597 7L14.7247 1.64106Z" fill="#737C87" />
			</svg>
		</div>
		<div class="popup-header">
			<h3 class="title">
				Chọn địa chỉ nhận hàng
			</h3>
		</div>
		<div class="popup-body">
			<?php

			global $wpdb;
			$wpdb_prefix = $wpdb->prefix;
			$wpdb_tablename = $wpdb_prefix . 'users_address';
			$id_user = get_current_user_id();

			$query = "SELECT * FROM $wpdb_tablename WHERE user_id = $id_user ORDER BY default_value DESC";

			$result = $wpdb->get_results($query);

			if ($result) : ?>
				<div class="list-address">
					<div class="list-address-wrapper">
						<?php foreach ($result as $keyIndex => $row) : ?>
							<div class="form-item-address" data-id="<?php echo $row->ID ?>">
								<div class="form-item-address-wrapper">
									<div class="body-form-item-address">
										<div class="heading-form-item-address-single-product">
											<input type="radio" <?php echo ($row->default_value == 1) ? 'checked' : '' ?> name="setting_default_address_item" id="setting_default_address_item-<?php echo $row->ID ?>">
											<label id="user_name" for="setting_default_address_item-<?php echo $row->ID ?>"><?php echo $row->user_name ?></label>|<span style="font-size: 16px;" id="user_phone"><?php echo $row->user_phone; ?></span>
										</div>
										<div class="button-config-address">
											<button class="btn-edit-address" id="btn-edit-address">Sửa</button>
										</div>
									</div>
									<div id="user_address" class="item" data-id-province="<?php echo $row->user_province_id ?>" data-id-district="<?php echo $row->user_district_id ?>" data-id-ward="<?php echo $row->user_ward_id ?>" data-address="<?php echo $row->user_address ?>" data-name-province="<?php echo $row->user_province_name ?>" data-name-district="<?php echo $row->user_district_name ?>" data-name-ward="<?php echo $row->user_ward_name ?>">
										<div class="address">
											<?php echo $row->user_address . ', ' . $row->user_province_name . ', ' . $row->user_district_name . ', ' . $row->user_ward_name ?>
										</div>
										<textarea style="display: none" name="user_note" id="user_note" cols="30" rows="10"><?php echo $row->user_note ?></textarea>
									</div>
									<?php if ($row->default_value == 1) :  ?>
										<p><?php echo ($row->default_value == 1) ? 'Mặc định' : '' ?> </p>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="form-item-address-other" data-id="<?php echo $row->ID ?>">
						<div class="form-item-address-other-wrapper">
							<div class="body-form-item-address-other">
								<div class="heading-form-item-address_other">
									<input type="radio" name="setting_default_address_item" id="address_item_other">
									<label for="address_item_other">Khác</label>
								</div>
							</div>
							<div class="item-address_other" id="address_other">
								<div class="select-option">
									<select name="city-option" id="city-option" class="">
										<option value="0">Chọn Tỉnh/ Thành Phố</option>
									</select>
								</div>
								<div class="select-option">
									<select name="district-option" id="district-option" class="">
										<option value="0">Chọn Quận/ Huyện</option>
									</select>
								</div>
								<div class="select-option">
									<select name="wards-option" id="wards-option" class="">
										<option value="0" class="">Chọn Phường/ Xã</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>

			<?php endif; ?>
			<button class="btn-add-address-in-single-product" id="button-add-address"><i class="fa fa-plus-circle" aria-hidden="true"></i>Thêm địa chỉ mới</button>
			<div class="popup-btn-apply-address-in-single-product ">
				<button class="btn-apply-address-in-single-product">
					Áp dụng
				</button>
			</div>

		</div>
	</div>
</div>
<?php get_template_part('sections/form-add-update-address') ?>
<?php endif; ?>
<?php do_action('woocommerce_after_single_product'); ?>