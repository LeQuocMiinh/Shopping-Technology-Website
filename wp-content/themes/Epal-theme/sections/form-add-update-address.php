<!--form address-->
<div class="form-address form-add-address">
	<div class="form-add-address-wrapper">
		<div class="fields-address" id="fields-address">
			<div class="heading-form-add-address">
				<h1>Thêm địa chỉ nhận hàng</h1>
				<img class="close-form-add-address"
					src="<?php echo get_template_directory_uri() . '/images/close.svg' ?>" alt="">
			</div>
			<div class="custom-row row-1">
				<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
					<label for="new_first_name">
						<?php esc_html_e('Họ và tên: ', 'woocommerce'); ?><span class="required-input"> *</span>
					</label>
					<input type="text" placeholder="Họ tên" class="woocommerce-Input woocommerce-Input--text input-text"
						name="new_name" id="new_name" autocomplete="given-name" value="" />
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="new_phone">
						<?php esc_html_e('Số điện thoại: ', 'woocommerce'); ?><span class="required-input"> *</span>
					</label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="new_phone"
						id="new_phone" value="" />
				</p>
			</div>

			<div class="custom-row row-2">
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="new_address">
						<?php esc_html_e('Địa chỉ: ', 'woocommerce'); ?><span class="required-input"> *</span>
					</label>
					<input placeholder="Địa chỉ" type="address"
						class="woocommerce-Input woocommerce-Input--address input-text" name="new_address"
						id="new_address" autocomplete="address" value="" />
				</p>
			</div>

			<div class="custom-row row-3" id="single-input">
				<p class="form-row form-row-first" id="city-option_field">
					<label for="city-option">
						<?php esc_html_e('Thành phố: ', 'woocommerce'); ?><span class="required-input"> *</span>
					</label>
					<span class="woocommerce-input-wrapper">
						<select name="city-option" id="city-option">
						</select>
					</span>
				</p>

				<p class="form-row form-row-first" id="district-option_field">
					<label for="district-option">
						<?php esc_html_e('Quận/huyện: ', 'woocommerce'); ?><span class="required-input"> *</span>
					</label>
					<span class="woocommerce-input-wrapper">
						<select name="district-option" id="district-option">

						</select>
					</span>
				</p>

				<p class="form-row form-row-first" id="wards-option_field">
					<label for="wards-option">
						<?php esc_html_e('Phường/xã: ', 'woocommerce'); ?><span class="required-input"> *</span>
					</label>
					<span class="woocommerce-input-wrapper">
						<select name="wards-option" id="wards-option">
						</select>
					</span>
				</p>
			</div>

			<div class="custom-row row-4">
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="note">
						<?php esc_html_e('Ghi chú: ', 'woocommerce'); ?>
					</label>
					<textarea placeholder="Ghi chú" name="note" id="note" cols="30" rows="4"></textarea>
				</p>
			</div>

			<div class="custom-row row-5 group-checkbox-default-address">
				<input type="checkbox" name="setting_default_address" id="setting_default_address">
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
				<img class="close-form-add-address"
					src="<?php echo get_template_directory_uri() . '/images/close.svg' ?>" alt="">
			</div>
			<div class="custom-row row-1">
				<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
					<label for="new_first_name">
						<?php esc_html_e('Họ và tên: ', 'woocommerce'); ?><span class="required-input"> *</span>
					</label>
					<input type="text" placeholder="Họ và tên *"
						class="woocommerce-Input woocommerce-Input--text input-text" name="new_name" id="new_name"
						autocomplete="given-name" value="" />
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="new_phone">
						<?php esc_html_e('Số điện thoại: ', 'woocommerce'); ?><span class="required-input"> *</span>
					</label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="new_phone"
						id="new_phone" value="" />
				</p>
			</div>

			<div class="custom-row row-2">
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="new_address">
						<?php esc_html_e('Địa chỉ: ', 'woocommerce'); ?><span class="required-input"> *</span>
					</label>
					<input placeholder="Địa chỉ *" type="address"
						class="woocommerce-Input woocommerce-Input--address input-text" name="new_address"
						id="new_address" autocomplete="address" value="" />
				</p>
			</div>

			<div class="custom-row row-3" id="single-input">
				<p class="form-row form-row-first" id="city-option_field">
					<label for="city-option">
						<?php esc_html_e('Thành phố: ', 'woocommerce'); ?><span class="required-input"> *</span>
					</label>
					<span class="woocommerce-input-wrapper">
						<select name="city-option" id="city-option">
						</select>
					</span>
				</p>

				<p class="form-row form-row-first" id="district-option_field">
					<label for="district-option">
						<?php esc_html_e('Quận/huyện: ', 'woocommerce'); ?><span class="required-input"> *</span>
					</label>
					<span class="woocommerce-input-wrapper">
						<select name="district-option" id="district-option">

						</select>
					</span>
				</p>

				<p class="form-row form-row-first" id="wards-option_field">
					<label for="wards-option">
						<?php esc_html_e('Phường/xã: ', 'woocommerce'); ?><span class="required-input"> *</span>
					</label>
					<span class="woocommerce-input-wrapper">
						<select name="wards-option" id="wards-option">
						</select>
					</span>
				</p>
			</div>

			<div class="custom-row row-4">
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="note">
						<?php esc_html_e('Ghi chú: ', 'woocommerce'); ?>
					</label>
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