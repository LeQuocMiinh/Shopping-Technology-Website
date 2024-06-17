<?php

/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */


get_header();
?>
<input type="text" id="single-input" hidden class="">
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php get_template_part('sections/breadcrumb') ?>
        </div>
    </div>
</div>
<section id="single-product" class="pt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                do_action('woocommerce_before_main_content');
                while (have_posts()) : the_post();
                    wc_get_template_part('content', 'single-product');
                endwhile;
                do_action('woocommerce_after_main_content'); ?>
            </div>
        </div>
    </div>
</section>
<?php
get_footer('shop'); ?>

<!-- Nút add giỏ hàng trang chi tiết -->
<!-- <script>
    $(document).on('click', '.single_add_to_cart_button', function(e) {
        e.preventDefault();
        if ($(this).hasClass('disabled')) {
            return false;
        }
        var $thisbutton = $(this),
            product_id = $('input[name="product_id"]').val(),
            product_qty = $('input[name="quality"]').val(),
            variation_id = $('input[name="attribute_pa_mau-sac"]:checked').attr('data-id');
        id_fee = $('.item-delivery.active').data('id');
        var data = {
            action: 'woocommerce_ajax_add_to_cart',
            product_id: product_id,
            product_sku: '',
            quantity: product_qty,
            variation_id: variation_id,
            id_fee: id_fee
        };
        console.log(product_id, product_qty, variation_id);
        $(document.body).trigger('adding_to_cart', [$thisbutton, data]);
        $.ajax({
            type: 'post',
            url: wc_add_to_cart_params.ajax_url,
            data: data,
            beforeSend: function(response) {
                $(".loading").removeClass('d-none');
                //    $thisbutton.addClass('loading')
            },
            complete: function(response) {
                $(".loading").addClass('d-none');
                //    $thisbutton.removeClass('loading')
            },
            success: function(response) {
                if (response.error & response.product_url) {
                    window.location = response.product_url;
                    return;
                } else {
                    $('.cart-header').attr('href', '/thanh-toan')
                    $('.popup-add-cart-success').addClass('active');
                    setTimeout(function() {
                        $('.popup-add-cart-success').removeClass('active');
                    }, 2000)
                    const numberCart = $(response.fragments['.cart-item'])[0].innerText.trim();
                    $('.number-cart').html(numberCart);
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                }
            },
        });
        return false;
    });
</script> -->

<!-- Nút mua ngay trang chi tiết sản phẩm -->
<!-- <script>
    $(document).on('click', '.btn-buy-add-cart-single', function(e) {
        e.preventDefault();
        var $thisbutton = $(this),
            product_id = $('input[name="product_id"]').val(),
            product_qty = $('input[name="quality"]').val(),
            variation_id = $('input[name="attribute_pa_mau"]:checked').attr('data-id');
        id_fee = $('.item-delivery.active').data('id');
        var data = {
            action: 'woocommerce_ajax_add_to_cart',
            product_id: product_id,
            product_sku: '',
            quantity: product_qty,
            variation_id: variation_id,
            id_fee: id_fee
        };
        $(document.body).trigger('adding_to_cart', [$thisbutton, data]);
        $.ajax({
            type: 'post',
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            data: data,
            beforeSend: function(response) {
                // $thisbutton.addClass('loading');
                // $('.load-img').addClass('active');
                $(".loading").removeClass('d-none');
            },
            complete: function(response) {
                // $thisbutton.removeClass('loading');
                // $('.load-img').removeClass('active');
                $(".loading").addClass('d-none');
            },
            success: function(response) {
                if (response.error & response.product_url) {
                    window.location = response.product_url;
                    return;
                } else {
                    $('.cart-header').attr('href', '/thanh-toan')
                    $(location).attr('href', url_home + '/thanh-toan');
                    $('.cart-ajaxs').html(response.fragments['.cart-item']);
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                }
            },
        });
        return false;
    });
</script> -->