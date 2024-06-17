<?php

/**
 * Template Name: Trang chủ
 */

get_header();
get_template_part('sections/homepage/banner');
get_template_part('sections/homepage/feature-categories');
get_template_part('sections/homepage/feature-program'); ?>
<?php
get_template_part('sections/homepage/main-content');
get_template_part('sections/homepage/news-main');
get_footer();
wp_footer()
?>
<!--// ajax cart-->
<!--<script>-->
<!--    (function ($) {-->
<!--        $(document).on('click', '.product_type_simple', function (e) {-->
<!--            e.preventDefault();-->
<!--            var $thisbutton = $(this),-->
<!--                product_qty = $thisbutton.data('quantity') || 1,-->
<!--                product_id = $thisbutton.data('product_id'),-->
<!--                variation_id = 0;-->
<!--            var data = {-->
<!--                action: 'woocommerce_ajax_add_to_cart',-->
<!--                product_id: product_id,-->
<!--                product_sku: '',-->
<!--                quantity: product_qty,-->
<!--                variation_id: variation_id,-->
<!--            };-->
<!--            $(document.body).trigger('adding_to_cart', [$thisbutton, data]);-->
<!--            $.ajax({-->
<!--                type: 'post',-->
<!--                url: 'wp-admin/admin-ajax.php',-->
<!--                data: data,-->
<!--                beforeSend: function (response) {-->
<!--                    $thisbutton.addClass('loading')-->
<!--                },-->
<!--                complete: function (response) {-->
<!--                    $thisbutton.removeClass('loading')-->
<!--                },-->
<!--                success: function (response) {-->
<!--                    if (response.error & response.product_url) {-->
<!--                        window.location = response.product_url;-->
<!--                        return;-->
<!--                    } else {-->
<!--                        $('.cart-ajax').html(response.fragments['.cart-item']);-->
<!--                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);-->
<!--                    }-->
<!--                },-->
<!--            });-->
<!--            return false;-->
<!--        });-->
<!--    })(jQuery);-->
<!--</script>-->
</body>

</html>