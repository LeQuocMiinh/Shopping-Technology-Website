<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        <?php wp_title('', true, 'right'); ?>
    </title>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <!--    -->
    <?php //echo get_field('script_header', 'option') 
    ?>
    <?php wp_head(); ?>
    <script>
        var url_home = "<?php echo get_home_url(); ?>"
        var url_template = "<?php echo get_template_directory_uri(); ?>"
        var url_orders = "<?php echo wc_get_endpoint_url( 'orders', '', wc_get_page_permalink( 'myaccount' ) ); ?>"
    </script>
</head>

<body <?php body_class(); ?>>
    <?php //echo get_field('script_body', 'option') 
    // WC()->session->set('chosen_shipping_methods', '');
    // wc()->session->set('cart_item_id_1', '');
    // foreach (WC()->cart->get_cart() as $item_key => $items) {
    //     if ($items['variation_id'] == $$cart[$item]['variation_id']) {
    //         WC()->cart->remove_cart_item($item_key);
    //     } else {
    //         $product_id = $cart[$items]['product_id'];
    //         $product_cart_id = WC()->cart->generate_cart_id($product_id);
    //         $cart_item_key = WC()->cart->find_product_in_cart($product_cart_id);
    //         if ($cart_item_key) WC()->cart->remove_cart_item($cart_item_key);
    //     }
    // }
    ?>
    <!--<div id="fb-root"></div>-->
    <!--<script async defer crossorigin="anonymous"-->
    <!--        src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v4.0"></script>-->
    <?php
    get_template_part('sections/menu-main');
    ?>
    <div class="title-mobile active-account col-12"><a href="javascript: history.back(1)" id="goBack"><img src="<?php echo get_template_directory_uri() ?>/images/go-back.svg"></a>
        <h3>
            <?php
            if (is_single() || is_category()) :
                echo "Tin tức";
            else :
                echo get_the_title();
            endif;
            ?>
        </h3>
    </div>

    <div class="toast" id="toast">
        <div class="toast-wrapper">
            <div class="toast-icon">
                <i class="fa"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title"></div>
                <div class="toast-message"></div>
            </div>
            <div class="toast-close">
                <i class="fa fa-times" style="color: gray;"></i>
            </div>
        </div>
    </div>

    <div class="loading d-none">
        <div class="loading-wrapper">
            <div class="spinner-border f-loading" role="status">
                <span class="sr-only"></span>
            </div>
        </div>
    </div>