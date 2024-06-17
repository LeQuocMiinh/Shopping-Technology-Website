<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

do_action('woocommerce_before_customer_login_form'); ?>


<div id="register">
    <div class="u-columns register-form" id="customer_login">
        <div class="u-column">
            <div class="text-right pd-mbile">Bạn chưa có tài khoản? <a href="/dang-ky">Đăng ký ngay</a></div>
            <h4>Đăng nhập tài khoản với</h4>
            <div class="social-network d-flex gap-9">
                <a href="<?php echo get_home_url() ?>/?epal_login_id=google_login" class="social google"><img
                        src="<?php echo get_template_directory_uri() ?>/images/google.svg"> Google</a>
                <a href="<?php echo get_home_url() ?>/?epal_login_id=facebook_login" class="social fb"><img
                        src="<?php echo get_template_directory_uri() ?>/images/fb.svg"> Facebook</a>
            </div>
            <div class="other d-flex gap-9">
                <hr />Hoặc
                <hr />
            </div>

            <form id="form-woocommerce-login" action="<?php echo admin_url('admin-ajax.php'); ?>"
                class="woocommerce-form woocommerce-form-login login" method="post">

                <?php do_action('woocommerce_login_form_start'); ?>

                <p class="form-group woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="username">
                        <?php esc_html_e('Email', 'woocommerce'); ?>&nbsp;<span class="required">*</span>
                    </label>
                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email"
                        id="username" autocomplete="username" placeholder="Nhập email" required
                        value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
                    <?php // @codingStandardsIgnoreLine    ?>
                </p>
                <p
                    class="form-group woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide woocommerce-form-row-password">
                    <label for="password">
                        <?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required">*</span>
                    </label>
                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password"
                        id="lo_password" autocomplete="current-password" placeholder="Nhập mật khẩu" required />
                    <i id="login-password" class="fa fa-eye" onclick="showHideLogin()"></i>
                </p>

                <?php do_action('woocommerce_login_form'); ?>
                <p class="woocommerce-LostPassword lost_password form-group text-right">
                    <a href="<?php echo esc_url(wp_lostpassword_url()); ?>">
                        <?php esc_html_e('Lost your password?', 'woocommerce'); ?>
                    </a>
                </p>
                <?php wp_nonce_field('login_account_nonce', 'login_account_nonce_field'); ?>
                <div class="form-row form-group text-center justify-content-center f-button">
                    <button type="submit"
                        class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"
                        name="login" value="<?php esc_attr_e('Log in', 'woocommerce'); ?>">
                        <?php esc_html_e('Log in', 'woocommerce'); ?>
                    </button>
                    <div class="spinner-border f-loading d-none" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
                <div class="alert alert-danger d-none" role="alert"></div>
                <?php do_action('woocommerce_login_form_end'); ?>
            </form>
        </div>
    </div>
</div>

<?php do_action('woocommerce_after_customer_login_form'); ?>