<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
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

do_action('woocommerce_before_lost_password_form');
?>

<div id="register">
    <div class="u-columns register-form" id="customer-lost-password">
        <div id="authentication-account">
            <h4>Khôi phục mật khẩu</h4>
            <div class="lost-password-text">
                <p>Bạn quên mật khẩu?</p>
                <p>Nhập email để lấy lại mật khẩu.</p>
            </div>
            <form id="form-lost-password" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post"
                class="woocommerce-ResetPassword lost_reset_password">
                <label for="inputEmail">Email <span class="required">*</span></label>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" id="inputEmail" placeholder="abc@gmail.com"
                        required>
                    <button type="submit" class="btn btn-primary">Nhận mã</button>
                    <div class="spinner-border f-loading d-none" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
                <input type="hidden" name="type" value="lost_password">
                <?php wp_nonce_field('lost_password_nonce', 'lost_password_nonce_field'); ?>
                <div class="alert alert-danger d-none" role="alert"></div>
            </form>
            <form id="form-lost-password-accuracy" action="<?php echo admin_url('admin-ajax.php'); ?>" class="d-none">
                <label>Nhập mã xác thực</label>
                <div class="form-group d-flex">
                    <input type="number" id="number-1" name="number_1" data-number="1" max="9" required>
                    <input type="number" id="number-2" name="number_2" data-number="2" max="9" required>
                    <input type="number" id="number-3" name="number_3" data-number="3" max="9" required>
                    <input type="number" id="number-4" name="number_4" data-number="4" max="9" required>
                    <input type="number" id="number-5" name="number_5" data-number="5" max="9" required>
                    <input type="number" id="number-6" name="number_6" data-number="6" max="9" required>
                </div>
                <input name="email" type="hidden">
                <?php wp_nonce_field('verify_authentication_nonce', 'verify_authentication_nonce_field'); ?>
                <div class="form-group d-flex f-button">
                    <button type="submit" class="btn btn-primary">Lấy lại mật khẩu</button>
                    <div class="spinner-border f-loading d-none" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
                <div class="alert alert-danger d-none" role="alert"></div>
                <div class="form-group return">
                    <a href="<?php echo get_permalink(wc_get_page_id('myaccount')); ?>"><img
                            src="<?php echo get_template_directory_uri() ?>/images/chevron.svg" alt="chevron">Trở về</a>
                </div>
            </form>
            <div class="title-mobile col-12 back"><a href="javascript: history.back(1)" id="goBack"><img
                        src="<?php echo get_template_directory_uri() ?>/images/go-back.svg"></a>
                <p>Trở về</p>
            </div>
        </div>
        <div id="change-password" class="d-none">
            <div class="title-mobile col-12"><a href="javascript: history.back(1)" id="goBack"><img
                        src="<?php echo get_template_directory_uri() ?>/images/go-back.svg"></a>
                <h3>Khôi phục mật khẩu</h3>
            </div>
            <h4>Đổi mật khẩu</h4>
            <form id="form-change-password" action="<?php echo admin_url('admin-ajax.php'); ?>">
                <div class="form-group">
                    <label for="inputEmail">Mật khẩu <span class="required">*</span></label>
                    <input type="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>
                <div class="form-group">
                    <label for="inputEmail">Nhập lại mật khẩu <span class="required">*</span></label>
                    <input type="password" name="req_password" placeholder="Nhập lại mật khẩu" required>
                </div>
                <div class="form-group f-button">
                    <button type="submit" class="btn btn-primary">Đặt lại mật khẩu</button>
                    <div class="spinner-border f-loading d-none" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
                <input name="email" type="hidden">
                <input name="pin" type="hidden">
                <?php wp_nonce_field('change_password_nonce', 'change_password_nonce_field'); ?>
                <div class="alert alert-danger d-none" role="alert"></div>
            </form>
        </div>
        <div id="account-success" class="d-none">
            <div class="title-mobile col-12"><a href="javascript: history.back(1)" id="goBack"><img
                        src="<?php echo get_template_directory_uri() ?>/images/go-back.svg"></a>
                <h3>Khôi phục mật khẩu</h3>
            </div>
            <h4>Khôi phục mật khẩu thành công!</h4>
            <p>Chúc mừng bạn đã khôi phục mật khẩu thành công.</p>
            <div class="text-right"><a href="/"><img src="<?php echo get_template_directory_uri() ?>/images/chevron.svg"
                        alt="chevron"> Về trang chủ</a></div>
        </div>
    </div>
</div>
<?php
do_action('woocommerce_after_lost_password_form');
