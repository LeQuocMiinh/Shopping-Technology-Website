<?php
do_action('woocommerce_before_account_navigation');
$user_id = get_current_user_id();
$user = get_userdata($user_id);
?>
<div class="title-mobile col-12">
    <h3>
        Tài khoản
    </h3>
    <img id="close-menu-mobile-category" src="<?php echo get_template_directory_uri() . '/images/close.svg' ?>" alt="">
</div>
<nav class="woocommerce-MyAccount-navigation">
    <ul>
        <?php if (is_user_logged_in()) { ?>
            <li class="display-name-on-top-my-account-navigation">
                Tài khoản của
                <span>
                    <?php echo esc_attr($user->display_name); ?>
                </span>
            </li>
            <?php foreach (wc_get_account_menu_items() as $endpoint => $label): ?>
                <li
                    class="<?php echo wc_get_account_menu_item_classes($endpoint); ?> woocommerce-MyAccount-navigation-link--customer-page">
                    <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>">
                        <?php echo esc_html($label); ?>
                        <img src="<?php echo get_template_directory_uri() ?>/images/go-next.svg" alt="">
                    </a>
                </li>
            <?php endforeach; ?>
            <?php
        } else {
            ?>
            <div class="box-login">
                <button class="btn register"><a href="/dang-ky">Đăng ký</a></button>
                <button class="btn login"><a href="<?php echo get_permalink(wc_get_page_id('myaccount')); ?>">Đăng
                        nhập</a></button>
            </div>
            <?php
        }
        ?>
        <?php
        $account_values = get_field('choose_page_account', 'option');

        if ($account_values) {
            foreach ($account_values as $value) {

                $page_object = $value['page'];
                $page_title = $page_object->post_title;
                $page_link = get_permalink($page_object->ID);
                ?>
                <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--customer-page">
                    <a href="<?= $page_link ?>" class="sidebar-support-item">
                        <?= $page_title ?>
                        <img src="<?php echo get_template_directory_uri() ?>/images/go-next.svg" alt="">
                    </a>
                </li>
                <?php
            }
        }
        ?>
        <?php
        $phone_number = get_field('numnber_phone_buy', 'option');
        if ($phone_number) {
            ?>
            <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--customer-phone">
                Gọi mua hàng:
                <?php

                $formatted_phone = substr($phone_number, 0, 4) . '.' . substr($phone_number, 4, 3) . '.' . substr($phone_number, 7);
                ?>
                <a href="tel:<?php echo $phone_number ?>" class="sidebar-support-item">
                    <?php echo $formatted_phone; ?>
                </a>
            </li>
            <?php
        }
        ?>

        <?php if (is_user_logged_in()) { ?>
            <li
                class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--customer-logout active-mobile">
                <a href="<?php echo wp_logout_url(get_permalink(wc_get_page_id('myaccount'))); ?>">
                    <?php _e('Đăng xuất', 'woocommerce'); ?>
                </a>
            </li>
            <?php
        }
        ?>
    </ul>
</nav>

<?php do_action('woocommerce_after_account_navigation'); ?>