<section id="header-top">
    <div class="container">
        <div class="row">
            <div class="menu-active col-12">
                <ul class="right-menu">
                    <?php while (have_rows('menu_header_bar', 'option')) :
                        the_row() ?>
                        <li class="sub-right">
                            <a href="<?php echo get_sub_field('link') ?>">
                                <?php echo get_sub_field('page') ?>
                            </a>
                        </li>
                    <?php endwhile;
                    wp_reset_query(); ?>
                </ul>
                <div class="address">
                    <img class="icon" src="<?php echo get_template_directory_uri() . '/images/location.svg' ?>" alt="icon">
                    <span class="text">Địa chỉ cửa hàng</span>
                    <div class="box-shop">
                        <div class="comp_infor">
                            <div class="name">
                                <?php echo get_field('company_name', 'option') ?>
                            </div>
                            <div class="text-normal">
                                Địa chỉ:
                                <?php echo get_field('company_address', 'option') ?>
                            </div>
                            <div class="text-normal">
                                Email:
                                <a href="mailto:<?php echo get_field('company_email', 'option') ?>">
                                    <?php echo get_field('company_email', 'option') ?>
                                </a>
                            </div>
                            <div>
                                <div class="status text-main">
                                    <?php echo get_field('company_status', 'option') ?>
                                </div>
                                <div class="time">
                                    <div>
                                        <?php
                                        $times = get_field('company_time', 'option');
                                        foreach ($times as $time) :
                                        ?>
                                            <span>
                                                <?php echo $time['content']; ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="map">
                                        <a target="_blank" href="<?php echo get_field('gg_map', 'option'); ?>">
                                            Chỉ đường
                                            <span><img src="<?php echo get_template_directory_uri() . '/images/map.svg' ?>" alt="icon"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="order-advise d-flex">
                                <?php $order_advise = get_field('order_info', 'option') ?>
                                <img src="<?php echo $order_advise['icon']['url'] ?>" alt="icon">
                                <div class="text">
                                    <div class="text-main">
                                        <?php echo $order_advise['title'] ?>
                                    </div>
                                    <a href="tel:<?php echo $order_advise['phone_number'] ?>">
                                        <?php echo $order_advise['phone_number'] ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="menu-main">
    <div class="container">
        <div class="row">
            <div class="col-12 header-wrapper">
                <div class="logo">
                    <a class="logo-img" href="/"><img src="<?php echo get_field('logo_main', 'option')['url'] ?>" alt="logo-main"></a>
                    <a class="logo-img-fixed d-none" href="/"><img src="<?php echo get_field('logo_scroll', 'option')['url'] ?>" alt="logo-main"></a>
                    <div class="box-category-scroll">
                        <img class="icon" src="<?php echo get_template_directory_uri() . '/images/bars-solid.svg' ?>" alt="icon">
                        Danh mục sản phẩm
                    </div>
                </div>
                <div class="search">
                    <form role="search" method="post" class="woocommerce-product-search " action="<?php echo esc_url(home_url('/')); ?>">
                        <input class="search-input" id="woocommerce-product-search-field-0" type="text" placeholder="Nhập từ khóa cần tìm" autocomplete="off" value="" name="s">
                        <img class="icon icon-search" src="<?php echo get_template_directory_uri() . '/images/search.svg' ?>">
                        <img class="icon icon-close d-none" src="<?php echo get_template_directory_uri() . '/images/close.svg' ?>">
                        <input type="hidden" name="post_type" value="post">
                        <button type="submit" id="submit-search"></button>
                    </form>
                    <?php
                    $product_categories = get_terms(
                        array(
                            'taxonomy' => 'product_cat',
                            'hide_empty' => false,
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'number' => 6,
                            'parent' => 0,
                        )
                    );
                    ?>
                    <div class="keywords d-flex">
                        <?php foreach ($product_categories as $category) { ?>
                            <a href="<?php echo get_term_link($category) ?>">
                                <?php
                                $words = explode(" ", $category->name);
                                $limitedWords = array_slice($words, 0, 2);

                                ?>
                                <span>
                                    <?php echo implode(" ", $limitedWords); ?>
                                </span>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="popup-keyword d-none">
                        <p>Từ khóa phổ biến</p>
                        <div class="words">
                            <?php $words = get_field('search_keywords', 'option'); ?>
                            <?php foreach ($words as $word) : ?>
                                <span class="popup-keyword-item">
                                    <?php echo $word['keyword']; ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="options d-flex">
                    <?php if (is_user_logged_in()) : ?>
                        <div class="option-item d-flex account">
                            <a href="<?php echo get_permalink(wc_get_page_id('myaccount')); ?>">
                                <img class="icon" src="<?php echo get_template_directory_uri() . '/images/user-solid.svg' ?>" alt="icon">
                                <span class="user-name">
                                    <?php
                                    $current_user = wp_get_current_user();
                                    $display_name = esc_html($current_user->display_name);

                                    if (strlen($display_name) > 15) {
                                        echo substr($display_name, 0, 15) . '...';
                                    } else {
                                        echo $display_name;
                                    }
                                    ?>
                                </span>
                            </a>
                            <div class="box-account logged-in">
                                <span>
                                    Tài khoản của <b>
                                        <?php echo wp_get_current_user()->display_name; ?>
                                    </b>
                                </span>
                                <?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
                                    <a class="<?php echo wc_get_account_menu_item_classes($endpoint); ?>" href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>">
                                        <?php echo esc_html($label); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="option-item d-flex account">
                            <a href="<?php echo get_permalink(wc_get_page_id('myaccount')); ?>">
                                <img class="icon" src="<?php echo get_template_directory_uri() . '/images/user-solid.svg' ?>" alt="icon">
                                <span>Tài khoản</span>
                            </a>
                            <div class="box-account">
                                <a href="<?php echo get_permalink(wc_get_page_id('myaccount')); ?>">Đăng nhập</a>
                                <a href="/dang-ky">Đăng ký</a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="option-item d-flex cart">
                        <?php
                        $total_cart = WC()->cart->total;
                        ?>
                        <a class="cart-header" href="<?php echo $total_cart == 0 ? '/gio-hang' : '/thanh-toan' ?>">
                            <span class="cart">
                                <img class="icon" src="<?php echo get_template_directory_uri() . '/images/cart.svg' ?>" alt="icon">
                                <span class="amount number-cart">
                                    <?php echo sprintf(_n('%d', '%d', WC()->cart->cart_contents_count, 'woothemes'), WC()->cart->cart_contents_count); ?>
                                </span>
                            </span>
                            <span>Giỏ hàng</span>
                        </a>
                        <?php get_template_part('sections/cart-hover'); ?>
                    </div>
                    <div class="option-item tel-lg">
                        <img class="icon" src="<?php echo get_template_directory_uri() . '/images/phone.svg' ?>" alt="icon">
                        <span>
                            <a href="tel:<?php echo get_field('telephone', 'option') ?>">
                                <?php echo get_field('telephone', 'option') ?>
                            </a>
                        </span>
                    </div>
                    <div class="option-item tel-md">
                        <span>
                            <a href="tel:<?php echo get_field('telephone', 'option') ?>">
                                <img class="icon" src="<?php echo get_template_directory_uri() . '/images/phone.svg' ?>" alt="icon">
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="menu-mobile-top">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="content">
                    <div class="top d-flex">
                        <div class="logo">
                            <a class="logo-img" href="javascript: history.back(1)">
                                <img class="logo-back" id="goBack" src="<?php echo get_template_directory_uri() ?>/images/angle-left.svg">
                                <img class="logo-main" src="<?php echo get_field('logo_mobile', 'option')['url'] ?>" alt="logo-main">
                            </a>
                        </div>
                        <div class="search md">
                            <form role="search" method="post" class="woocommerce-product-search " action="<?php echo esc_url(home_url('/')); ?>">
                                <input class="search-input" id="woocommerce-product-search-field-0" type="text" placeholder="Nhập từ khóa cần tìm" autocomplete="off" value="" name="s">
                                <img class="icon icon-search" src="<?php echo get_template_directory_uri() . '/images/search.svg' ?>">
                                <img class="icon icon-close d-none" src="<?php echo get_template_directory_uri() . '/images/close.svg' ?>">
                            </form>

                            <div class="popup-keyword d-none">
                                <p>Từ khóa phổ biến</p>
                                <div class="words">
                                    <?php $words = get_field('search_keywords', 'option'); ?>
                                    <?php foreach ($words as $word) : ?>
                                        <span class="popup-keyword-item">
                                            <?php echo $word['keyword']; ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="socials">
                            <div class="item d-flex">
                                <?php $zalo = get_field('zalo_info', 'option') ?>
                                <a href="<?php echo $zalo['zalo_link'] ?>">
                                    <img src="<?php echo $zalo['zalo_icon']['url'] ?>" alt="icon">
                                    <span>
                                        <?php echo $zalo['zalo_title'] ?>
                                    </span>
                                </a>
                            </div>
                            <div class="item d-flex">
                                <?php $shopee = get_field('shopee_info', 'option') ?>
                                <a href="<?php echo $shopee['shopee_link'] ?>">
                                    <img src="<?php echo $shopee['shopee_icon']['url'] ?>" alt="icon">
                                    <span>
                                        <?php echo $shopee['shopee_title'] ?>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="search sm">
                        <form role="search" method="post" class="woocommerce-product-search " action="<?php echo esc_url(home_url('/')); ?>">
                            <input class="search-input" id="woocommerce-product-search-field-0" type="text" placeholder="Nhập từ khóa cần tìm" autocomplete="off" value="" name="s">
                            <img class="icon icon-search" src="<?php echo get_template_directory_uri() . '/images/search.svg' ?>">
                            <img class="icon icon-close d-none" src="<?php echo get_template_directory_uri() . '/images/close.svg' ?>">
                        </form>

                        <div class="popup-keyword d-none">
                            <p>Từ khóa phổ biến</p>
                            <div class="words">
                                <?php $words = get_field('search_keywords', 'option'); ?>
                                <?php foreach ($words as $word) : ?>
                                    <span class="popup-keyword-item">
                                        <?php echo $word['keyword']; ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="menu-mobile-bottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="menu-content">
                    <div class="menu-item menu-item-1" data-id="menu-item-1">
                        <a href="/">
                            <div class="icon">
                                <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20.5847 8.98242C20.5847 9.61523 20.0591 10.1109 19.4634 10.1109H18.3421L18.3666 15.743C18.3666 15.8379 18.3596 15.9328 18.3491 16.0277V16.5938C18.3491 17.3707 17.7219 18 16.9475 18H16.3868C16.3483 18 16.3097 18 16.2712 17.9965C16.2221 18 16.1731 18 16.124 18H14.9852H14.1442C13.3698 18 12.7426 17.3707 12.7426 16.5938V15.75V13.5C12.7426 12.8777 12.2415 12.375 11.6213 12.375H9.37866C8.75844 12.375 8.25736 12.8777 8.25736 13.5V15.75V16.5938C8.25736 17.3707 7.63012 18 6.85572 18H6.01474H4.89694C4.84438 18 4.79181 17.9965 4.73925 17.993C4.6972 17.9965 4.65516 18 4.61311 18H4.05245C3.27805 18 2.65082 17.3707 2.65082 16.5938V12.6562C2.65082 12.6246 2.65082 12.5895 2.65432 12.5578V10.1109H1.52951C0.898775 10.1109 0.408203 9.61875 0.408203 8.98242C0.408203 8.66602 0.513326 8.38477 0.758612 8.13867L9.74309 0.28125C9.98837 0.0351562 10.2687 0 10.514 0C10.7593 0 11.0396 0.0703125 11.2498 0.246094L20.1993 8.13867C20.4796 8.38477 20.6198 8.66602 20.5847 8.98242Z" fill="#737C87" />
                                </svg>
                            </div>
                            <div class="text">
                                Trang chủ
                            </div>
                        </a>
                    </div>
                    <div class="menu-item menu-item-2" data-id="menu-item-2">

                        <div class="icon">
                            <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.5 0.5C0.5 0.223858 0.723858 0 1 0H8C8.27614 0 8.5 0.223858 8.5 0.5V7.5C8.5 7.77614 8.27614 8 8 8H1C0.723858 8 0.5 7.77614 0.5 7.5V0.5ZM0.5 10.5C0.5 10.2239 0.723858 10 1 10H8C8.27614 10 8.5 10.2239 8.5 10.5V17.5C8.5 17.7761 8.27614 18 8 18H1C0.723858 18 0.5 17.7761 0.5 17.5V10.5ZM10.5 0.5C10.5 0.223858 10.7239 0 11 0H18C18.2761 0 18.5 0.223858 18.5 0.5V7.5C18.5 7.77614 18.2761 8 18 8H11C10.7239 8 10.5 7.77614 10.5 7.5V0.5ZM10.5 10.5C10.5 10.2239 10.7239 10 11 10H18C18.2761 10 18.5 10.2239 18.5 10.5V17.5C18.5 17.7761 18.2761 18 18 18H11C10.7239 18 10.5 17.7761 10.5 17.5V10.5Z" fill="#737C87" />
                            </svg>
                        </div>
                        <div class="text">
                            Danh mục
                        </div>

                    </div>
                    <div class="menu-item menu-item-3" data-id="menu-item-3">
                        <?php
                        $total_cart = WC()->cart->total;
                        ?>
                        <a class="cart-header <?php echo $total_cart == 0 ? 'disabled' : '' ?>" href="<?php echo $total_cart == 0 ? '/gio-hang' : '/thanh-toan ' ?>">
                            <div class="icon">
                                <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.90511 1.23393C9.11956 0.81908 8.95784 0.309313 8.54651 0.094859C8.13518 -0.119595 7.6219 0.0421244 7.40745 0.453454L4.13439 6.74996H1.125C0.502736 6.74996 0 7.2527 0 7.87496C0 8.49723 0.502736 8.99997 1.125 8.99997L2.94962 16.2949C3.19923 17.2969 4.09923 18 5.13283 18H15.1172C16.1508 18 17.0508 17.2969 17.3005 16.2949L19.1251 8.99997C19.7473 8.99997 20.2501 8.49723 20.2501 7.87496C20.2501 7.2527 19.7473 6.74996 19.1251 6.74996H16.1157L12.8426 0.453454C12.6282 0.0421244 12.1184 -0.119595 11.7036 0.094859C11.2887 0.309313 11.1305 0.81908 11.345 1.23393L14.2137 6.74996H6.03635L8.90511 1.23393ZM6.75002 10.6875V14.0625C6.75002 14.3719 6.4969 14.625 6.18752 14.625C5.87815 14.625 5.62502 14.3719 5.62502 14.0625V10.6875C5.62502 10.3781 5.87815 10.125 6.18752 10.125C6.4969 10.125 6.75002 10.3781 6.75002 10.6875ZM10.125 10.125C10.4344 10.125 10.6875 10.3781 10.6875 10.6875V14.0625C10.6875 14.3719 10.4344 14.625 10.125 14.625C9.81566 14.625 9.56253 14.3719 9.56253 14.0625V10.6875C9.56253 10.3781 9.81566 10.125 10.125 10.125ZM14.6251 10.6875V14.0625C14.6251 14.3719 14.3719 14.625 14.0625 14.625C13.7532 14.625 13.5 14.3719 13.5 14.0625V10.6875C13.5 10.3781 13.7532 10.125 14.0625 10.125C14.3719 10.125 14.6251 10.3781 14.6251 10.6875Z" fill="#99A4AF" />
                                </svg>
                            </div>
                            <div class="text">
                                <span class="cart">
                                    <span class="amount number-cart">
                                        <?php echo sprintf(_n('%d', '%d', WC()->cart->cart_contents_count, 'woothemes'), WC()->cart->cart_contents_count); ?>
                                    </span>
                                </span>
                                <span>Giỏ hàng</span>
                            </div>
                        </a>
                    </div>
                    <div class="menu-item menu-item-4" data-id="menu-item-4">
                        <a href="/lien-he">
                            <div class="icon">
                                <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.1248 2.16784C10.6845 2.11211 10.1227 1.92495 10.0236 2.5089C9.91357 3.11478 10.5411 3.07117 10.9487 3.15908C13.7246 3.76535 14.6717 4.7562 15.1344 7.44537C15.1559 7.55566 15.1676 7.69853 15.1676 7.8197C15.2005 8.13908 15.2775 8.42519 15.762 8.33767C16.0045 8.30432 16.0926 8.1725 16.1036 7.98533C16.1367 7.8197 16.1036 7.59919 16.1258 7.44537C16.1367 4.85546 13.8786 2.5089 11.1248 2.16784ZM11.3785 4.29396C11.0921 4.29396 10.8055 4.32692 10.6955 4.63572C10.5301 5.07643 10.8719 5.1973 11.2129 5.25225C12.3476 5.41788 12.9426 6.0791 13.0637 7.18056C13.0746 7.33477 13.13 7.4561 13.1958 7.55574C13.295 7.66479 13.4161 7.70956 13.5703 7.6986C13.6693 7.6986 13.7464 7.66479 13.8122 7.63223C14 7.48898 14.0328 7.22455 14.0108 6.94901C14.0328 5.70469 12.6009 4.26062 11.3785 4.29396ZM17.1279 13.0197C16.5663 12.5786 15.9492 12.1822 15.3766 11.7745C14.1982 10.981 13.1188 10.9158 12.2486 12.2042C11.753 12.9317 11.0587 12.9646 10.343 12.6448C8.34933 11.7745 6.81828 10.4194 5.91526 8.42519C5.78281 8.11677 5.69498 7.8197 5.68371 7.54424C5.6506 7.01523 5.87088 6.553 6.46595 6.14486C6.96145 5.82548 7.44592 5.43972 7.41304 4.72356C7.36913 3.78727 5.05569 0.669201 4.13098 0.327362C3.7561 0.195072 3.38162 0.217457 2.99617 0.327362C0.848288 1.04306 -0.0328156 2.76268 0.815177 4.80043C1.2998 5.94673 1.81761 7.01523 2.43437 8.01751C5.06696 12.4247 8.94386 15.6421 13.9448 17.7462C14.2861 17.8894 14.6717 17.9557 14.8808 17.9997C16.2798 18.0217 17.9097 16.6886 18.3835 15.3881C18.8462 14.1215 17.8772 13.637 17.1279 13.0197ZM10.2441 0.956089C14.7265 1.63845 16.7864 3.73161 17.3589 8.15019C17.3589 8.1945 17.3589 8.22753 17.3589 8.25971C17.3923 8.66738 17.3154 9.19678 17.8548 9.19678C18.3288 9.20781 18.3507 8.85532 18.3507 8.51356C18.3288 8.3927 18.3288 8.29344 18.3288 8.19457C18.3836 4.01819 14.6718 0.140741 10.3762 0.00806225C10.0347 0.0630147 9.37368 -0.212446 9.31889 0.503723C9.30754 0.978086 9.86927 0.911319 10.2441 0.956089Z" fill="#737C87" />
                                </svg>
                            </div>
                            <div class="text">
                                Liên hệ
                            </div>
                        </a>
                    </div>

                    <div class="menu-item menu-item-5 popup-account" data-id="menu-item-5">
                        <div class="icon">
                            <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.5 8C8.56087 8 9.57828 7.57857 10.3284 6.82843C11.0786 6.07828 11.5 5.06087 11.5 4C11.5 2.93913 11.0786 1.92172 10.3284 1.17157C9.57828 0.421427 8.56087 0 7.5 0C6.43913 0 5.42172 0.421427 4.67157 1.17157C3.92143 1.92172 3.5 2.93913 3.5 4C3.5 5.06087 3.92143 6.07828 4.67157 6.82843C5.42172 7.57857 6.43913 8 7.5 8ZM6.07188 9.5C2.99375 9.5 0.5 11.9937 0.5 15.0719C0.5 15.5844 0.915625 16 1.42813 16H13.5719C14.0844 16 14.5 15.5844 14.5 15.0719C14.5 11.9937 12.0063 9.5 8.92813 9.5H6.07188Z" fill="#737C87" />
                            </svg>
                        </div>
                        <div class="text">
                            Tài khoản
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="menu-mobile-custom-section menu-mobile-category hide" data-id="menu-item-2">
    <?php get_template_part('sections/menu-mobile-category'); ?>
</section>
<section class="menu-mobile-custom-section page-template-tpl-account-mobile hide" data-id="menu-item-5">
    <?php get_template_part('sections/menu-mobile-account'); ?>
</section>