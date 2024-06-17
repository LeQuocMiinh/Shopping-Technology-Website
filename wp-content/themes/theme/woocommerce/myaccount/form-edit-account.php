<?php

/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_edit_account_form');
$user_id = get_current_user_id();
$user = get_userdata($user_id);
?>
<div class="title-mobile col-12"><a href="javascript: history.back(1)" id="goBack"><img
                    src="<?php echo get_template_directory_uri() ?>/images/go-back.svg"></a>
    <h3>
        Thông tin tài khoản
    </h3>
</div>
<h1 class="heading-edit-account" style="margin-bottom: 12px; font-weight: bold;">Thông tin tài khoản</h1>
<section class="edit-account">
    <div class="row box-account">
        <div class="col-12 col-lg-6">
            <div class="box-account-item">
                <div class="heading-left">Thông tin cá nhân</div>
                <div class="edit-account-personal-info">
                    <form class="woocommerce-EditAccountForm edit-account">
                        <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="account_display_name">
                                <?php esc_html_e('Họ và tên', 'woocommerce'); ?>
                            </label>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr($user->display_name); ?>" />
                        </div>

                        <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <input type="hidden" id="birthday_user_current" value="<?php echo esc_attr(get_user_meta($user_id, 'birthday', true)); ?>">
                            <label for="birthday">
                                <?php esc_html_e('Ngày sinh', 'woocommerce'); ?>
                            </label>
                            <div class="birthday-select">
                                <?php
                                $months = [
                                    1 => 'Tháng 1',
                                    2 => 'Tháng 2',
                                    3 => 'Tháng 3',
                                    4 => 'Tháng 4',
                                    5 => 'Tháng 5',
                                    6 => 'Tháng 6',
                                    7 => 'Tháng 7',
                                    8 => 'Tháng 8',
                                    9 => 'Tháng 9',
                                    10 => 'Tháng 10',
                                    11 => 'Tháng 11',
                                    12 => 'Tháng 12'
                                ];

                                $current_year = date('Y');
                                $years = range($current_year - 70, $current_year); ?>

                                <!-- Day selection -->
                                <select name="day" id="day-select">
                                    <option value="" selected disabled>Ngày</option>
                                </select>

                                <!-- Month selection -->
                                <select name="month" id="month-select">
                                    <option value="" selected disabled>Tháng</option>
                                    <?php foreach ($months as $key => $month) : ?>
                                        <option value="<?php echo $key; ?>">
                                            <?php echo $month; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <!-- Year selection -->
                                <select name="year" id="year-select">
                                    <option value="" selected disabled>Năm</option>
                                    <?php foreach ($years as $year) : ?>
                                        <option value="<?php echo $year; ?>">
                                            <?php echo $year; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <input type="hidden" hidden id="sex_user_current" value="<?php echo esc_attr(get_user_meta($user_id, 'sex', true)); ?>">
                            <label for="sex">
                                <?php esc_html_e('Giới tính', 'woocommerce'); ?>
                            </label>
                            <div class="group-radio-sex" style="width: 100%">
                                <div class="row">
                                    <div class="group-item">
                                        <input type="radio" id="Nữ" name="gender" value="Nữ">
                                        <label for="Nữ">Nữ</label>
                                    </div>

                                    <div class="group-item"><input type="radio" id="Nam" name="gender" value="Nam">
                                        <label for="Nam">Nam</label>
                                    </div>

                                    <div class="group-item">
                                        <input type="radio" id="Khác" name="gender" value="Khác">
                                        <label for="Khác">Khác</label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <button id="save-personal-info-account">Lưu thay đổi</button>
                    </div>
                </div>
                <div class="connect-social-network">
                    <div class="social-network-title">
                        <?php esc_html_e('Liên kết mạng xã hội', 'woocommerce'); ?>
                    </div>
                    <div class="social-network-list">
                        <?php
                        $user_current_id = get_current_user_id();
                        global $wpdb;
                        $wpdb_prefix = $wpdb->prefix;
                        $wpdb_tablename = $wpdb_prefix . 'epal_users_social_profile_details';
                        $query = "SELECT * FROM $wpdb_tablename WHERE user_id = $user_current_id";
                        $result = $wpdb->get_results($query);
                        $google_connected = false;
                        $facebook_connected = false;
                        foreach ($result as $keyIndex => $row) {
                            if ($row->provider_name == 'facebook') {
                                $facebook_connected = true;
                            }
                            if ($row->provider_name == 'google') {
                                $google_connected = true;
                            }
                        }
                        ?>

                        <div class="social-network-item">
                            <div class="icon">
                                <img src="<?php echo get_template_directory_uri() . '/images/Facebook.svg' ?>" alt="">
                                <span>Facebook</span>
                            </div>
                            <a href="<?php echo get_home_url() ?>/?epal_login_id=facebook_login" id="connect-facebook" class="<?php echo ($facebook_connected) ? 'connected' : '' ?>">
                                <?php echo ($facebook_connected) ? "Đã liên kết" : "Liên kết" ?>
                            </a>
                        </div>

                        <div class="social-network-item">
                            <div class="icon">
                                <img src="<?php echo get_template_directory_uri() . '/images/Google-connect.svg' ?>" alt="">
                                <span>Google</span>
                            </div>
                            <a href="<?php echo get_home_url() ?>/?epal_login_id=google_login" class="<?php echo ($google_connected) ? 'connected' : '' ?>" id="connect-google">
                                <?php echo ($google_connected) ? "Đã liên kết" : "Liên kết" ?>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="box-account-item">
                <div class="heading-left">Số điện thoại và email</div>
                <form class="woocommerce-EditAccountForm edit-account edit-account-phone-email">
                    <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="account_phone">
                            <img src="<?php echo get_template_directory_uri() . '/images/phone-edit-account.svg' ?>" alt="">
                            <span>
                                <?php esc_html_e('Số điện thoại', 'woocommerce'); ?></label></span>
                        <div class="show-info">
                            <div class="text">
                                <?php echo get_user_meta($user_id, 'phone', true) ? get_user_meta($user_id, 'phone', true) : 'Thêm số điện thoại' ?>
                            </div>
                            <input type="number" class="woocommerce-Input woocommerce-Input--email input-text hidden " name="account_phone" id="account_phone" value="<?php echo esc_attr((get_user_meta($user_id, 'phone', true)) ? get_user_meta($user_id, 'phone', true) : ''); ?>" />
                            <div class="btn-cancel-save-edit-account hidden">
                                <div class="cancel update-edit-account" id="cancel-update-edit-account">
                                    <?php esc_html_e('Huỷ', 'woocommerce'); ?>
                                </div>
                                <div class="save update-edit-account" id="save-update-edit-account">
                                    <?php esc_html_e('Lưu', 'woocommerce'); ?>
                                </div>
                            </div>
                            <div class="btn-update-edit-account " id="update-edit-account">
                                <?php esc_html_e('Cập nhật', 'woocommerce'); ?>
                            </div>
                        </div>

                    </div>

                    <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="account_email">
                            <img src="<?php echo get_template_directory_uri() . '/images/emai-edit-account.svg' ?>" alt="">
                            <span>
                                <?php esc_html_e('Địa chỉ email', 'woocommerce'); ?></label></span>
                        <div class="show-info">
                            <div class="text ">
                                <?php echo esc_attr($user->user_email); ?>
                            </div>
                            <input type="email" class="woocommerce-Input woocommerce-Input--email input-text hidden " name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr($user->user_email); ?>" />
                            <div class="btn-cancel-save-edit-account  hidden">
                                <div class="cancel update-edit-account" id="cancel-update-edit-account">
                                    <?php esc_html_e('Huỷ', 'woocommerce'); ?>
                                </div>
                                <div class="save update-edit-account" id="save-update-edit-account">
                                    <?php esc_html_e('Lưu', 'woocommerce'); ?>
                                </div>
                            </div>
                            <div class="btn-update-edit-account" id="update-edit-account">
                                <?php esc_html_e('Cập nhật', 'woocommerce'); ?>
                            </div>
                        </div>

                    </div>
                </form>
                <div class="heading-left" style="margin-bottom: 24px;">Bảo mật</div>
                <form class="woocommerce-EditAccountForm edit-account edit-account-password">

                    <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="password">
                            <img src="<?php echo get_template_directory_uri() . '/images/secure-edit-account.svg' ?>" alt="">
                            <span>
                                <?php esc_html_e('Đổi mật khẩu', 'woocommerce'); ?></label></span>
                        <div class="btn-update-edit-account" id="update-edit-account">
                            <?php esc_html_e('Cập nhật', 'woocommerce'); ?>
                        </div>
                    </div>
                    <div class="woocommerce-form-row-group hidden">
                        <div class="woocommerce-form-row-item">
                            <label for="password_current">
                                <?php esc_html_e('Mật khẩu hiện tại', 'woocommerce'); ?><span style="color: red;">*</span>
                            </label>
                            <input type="password" placeholder="Nhập mật khẩu hiện tại" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" />
                        </div>
                        <div class="woocommerce-form-row-item">
                            <label for="password_1">
                                <?php esc_html_e('Mật khẩu mới', 'woocommerce'); ?><span style="color: red;">*</span>
                            </label>
                            <input type="password" placeholder="Nhập mật khẩu mới" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" />
                            <img class="toggle-pw" src="<?php echo get_template_directory_uri() . '/images/eye.svg' ?>" alt="icon">
                        </div>
                        <div class="woocommerce-form-row-item">
                            <label for="password_2">
                                <?php esc_html_e('Nhập lại mật khẩu', 'woocommerce'); ?><span style="color: red;">*</span>
                            </label>
                            <input type="password" placeholder="Nhập lại mật khẩu" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" />
                            <img class="toggle-pw" src="<?php echo get_template_directory_uri() . '/images/eye.svg' ?>" alt="icon">
                        </div>
                        <div class="btn-cancel-save-edit-account ">
                            <div class="cancel update-edit-account" id="cancel-update-edit-account">
                                <?php esc_html_e('Huỷ', 'woocommerce'); ?>
                            </div>
                            <div class="save update-edit-account" id="save-update-edit-account">
                                <?php esc_html_e('Lưu', 'woocommerce'); ?>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </div>
</section>



<?php do_action('woocommerce_after_edit_account_form'); ?>