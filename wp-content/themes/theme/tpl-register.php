<?php

/**
 * Template Name: Đăng ký
 */

get_header();
?>
<section id="register" class="register-mb">
    <div class="register-form">
    <?php get_template_part('sections/breadcrumb') ?>
        <div id="authentication-account">
            <div class="text-right pd-mbile">Bạn đã có tài khoản? <a href="<?php echo get_permalink(wc_get_page_id('myaccount')); ?>">Đăng nhập ngay</a></div>
            <h4>Đăng ký tài khoản với</h4>
            <div class="social-network d-flex gap-9">
                <a href="<?php echo get_home_url() ?>/?epal_login_id=google_login" class="social google"><img src="<?php echo get_template_directory_uri() ?>/images/google.svg"> Google</a>
                <a href="<?php echo get_home_url() ?>/?epal_login_id=facebook_login" class="social fb"><img src="<?php echo get_template_directory_uri() ?>/images/fb.svg"> Facebook</a>
            </div>
            <div class="other d-flex gap-9">
                <hr />Hoặc
                <hr />
            </div>
            <form id="form-register" action="<?php echo admin_url('admin-ajax.php'); ?>">
                <label for="inputEmail">Email <span class="required">*</span></label>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" id="inputEmail" placeholder="abc@gmail.com" required>
                    <button type="submit" class="btn btn-primary">Nhận mã</button>
                    <div class="spinner-border f-loading d-none" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
                <?php wp_nonce_field('authentication_nonce', 'authentication_nonce_field'); ?>
                <div class="alert alert-danger d-none" role="alert"></div>
            </form>
            <form id="form-register-accuracy" action="<?php echo admin_url('admin-ajax.php'); ?>" class="d-none">
                <label class="form-group">
                    <img src="<?php echo get_template_directory_uri() ?>/images/check.svg" alt="check"> Mã xác thực đã
                    gửi về địa chỉ email <span id="email"></span>
                </label>
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
                    <button type="submit" class="btn btn-primary">Xác thực</button>
                    <div class="spinner-border f-loading d-none" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
                <div class="alert alert-danger d-none" role="alert"></div>
            </form>
        </div>
        <div id="create-account" class="d-none">
            <form action="<?php echo admin_url('admin-ajax.php'); ?>" id="form-create">
                <div class="form-group">
                    <label for="inputEmail">Họ và tên <span class="required">*</span></label>
                    <input type="text" name="name" placeholder="Nhập họ và tên" required>
                </div>
                <div class="form-group">
                    <label for="inputEmail">Mật khẩu <span class="required">*</span></label>
                    <input type="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>
                <div class="form-group">
                    <label for="inputEmail">Nhập lại mật khẩu <span class="required">*</span></label>
                    <input type="password" name="req_password" placeholder="Nhập lại mật khẩu" required>
                </div>
                <div class="form-group f-button">
                    <button type="submit" class="btn btn-primary">Đăng ký</button>
                    <div class="spinner-border f-loading d-none" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
                <input name="email" type="hidden">
                <?php wp_nonce_field('create_account_nonce', 'create_account_nonce_field'); ?>
                <div class="alert alert-danger d-none" role="alert"></div>
            </form>
        </div>
        <div id="account-success" class="d-none">
            <h4>Đăng ký thành công!</h4>
            <p>Chúc mừng bạn đã tạo tài khoản thành công qua email <span id="success-email"></span></p>
            <div class="text-right"><a href="/"><img src="<?php echo get_template_directory_uri() ?>/images/chevron.svg" alt="chevron"> Về trang chủ</a></div>
        </div>
    </div>
</section>
<?php
get_footer();
?>