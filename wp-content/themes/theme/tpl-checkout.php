<?php
/**
 * Template Name: Thanh toán
 */
get_header();
?>
<section id="page-checkout">
    <section id="breadcrumb-nav">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-nav">
                        <a href="<?php echo get_home_url() ?>" class="link-home">Trang chủ</a>
                        <span class="text-line">/</span> <span class="text">Giỏ hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="primary-checkout">
        <?php while (have_posts()): the_post();
            the_content();
        endwhile; ?>
    </section>
</section>
<?php 
if(is_user_logged_in()) {
    get_template_part('sections/form-add-update-address');
}
 ?>
<?php get_footer() ?>