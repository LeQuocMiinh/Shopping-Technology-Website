<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="logo">
                    <a href="/"><img class="lazy" data-src="<?php echo get_field('logo_footer', 'option')['url'] ?>" alt="logo-main"></a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="comp_infor">
                    <div class="name"><?php echo get_field('company_name', 'option') ?></div>
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
                            <?php
                            $times = get_field('company_time', 'option');
                            foreach ($times as $time) :
                            ?>
                                <span> <?php echo $time['content']; ?> </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="order-advise d-flex">
                        <?php $order_advise = get_field('order_info', 'option') ?>
                        <img class="lazy" data-src="<?php echo $order_advise['icon']['url'] ?>" alt="icon">
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
            <div class="col-lg-7 cols">
                <div class="col-item">
                    <div class="col-title">
                        <?php echo get_field('col2_title', 'option') ?>
                    </div>
                    <div class="col-content">
                        <?php $items = get_field('col2_content', 'option'); ?>
                        <?php foreach ($items as $item) : ?>
                            <a href="<?php echo $item['col_link']; ?>">
                                <?php echo $item['col_name']; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-item">
                    <div class="col-title">
                        <?php echo get_field('col3_title', 'option') ?>
                    </div>
                    <div class="col-content">
                        <?php $items = get_field('col3_content', 'option'); ?>
                        <?php foreach ($items as $item) : ?>
                            <a href="<?php echo $item['col_link']; ?>">
                                <?php echo $item['col_name']; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-12 socials">
                <?php $items = get_field('socials', 'option'); ?>
                <?php foreach ($items as $item) : ?>
                    <a href="<?php echo $item['link']; ?>">
                        <img class="icon lazy" data-src="<?php echo $item['icon']['url'] ?>" alt="icon">
                        <?php echo $item['name']; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</footer>
<div class="copyright bg-main fs-16">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <a href="https://epal.vn" target="blank" class="color-black"> Thiết kế web</a> bởi Epal Solution.
            </div>
        </div>
    </div>
</div>
<div id="backToTop" class="scroll-up radius-50 text-center text-white fz-20">
    <i class="fa fa-angle-up"></i>
</div>
<div class="popup-add-cart-success">
    <div class="body-popup">
        <div class="icon-success">
            <img src="<?php echo get_template_directory_uri() . '/images/add-cart-success.svg' ?>" alt="icon">
        </div>
        <div class="info-success">
            Thêm vào giỏ hàng thành công
        </div>
        <div class="close-popup-modal">
            <img class="icon icon-close" src="<?php echo get_template_directory_uri() . '/images/close.svg' ?>">
        </div>
    </div>
</div>

<div class="popup-menu">
    <div class="popup-oveplay"></div>
    <div class="container menu-category">
        <?php get_template_part('sections/menu-category'); ?>
    </div>
</div>

<div class="load-img">
    <img class="icon" src="<?php echo get_template_directory_uri() . '/images/loading.svg' ?>">
</div>
<div class="overlay-search"></div>
<!-- <script>
    (function($) {
        //Add sản phẩm trang danh mục sản phẩm
        $(document).on('click', '.add_cart_ajax_cate', function(e) {
            e.preventDefault();
            var addCartButton = $(this);
            var product_id = $(this).data("id");
            var product_qty = 1;
            var id_fee = '';
            var data = {
                action: 'woocommerce_ajax_add_to_cart',
                product_id: product_id,
                product_sku: '',
                quantity: product_qty,
                id_fee: id_fee
            };
            $(document.body).trigger('adding_to_cart', [addCartButton, data]);
            $.ajax({
                type: 'post',
                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                data: data,
                beforeSend: function(response) {
                    $('.load-img').addClass('active');
                },
                complete: function(response) {
                    $('.load-img').removeClass('active');
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
                        $(document.body).trigger('added_to_cart', [response.fragments, response
                            .cart_hash, addCartButton
                        ]);
                    }
                },
            });
            return false;
        });
    })(jQuery);
</script> -->

<?php wp_footer() ?>
</body>

</html>