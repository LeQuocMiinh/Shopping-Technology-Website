<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

get_header();
?>
<section id="category-products">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php get_template_part('sections/breadcrumb'); ?>
            </div>
            <div class="col-md-12 products-row">
                <div class="sidebar-products">
                    <?php get_template_part('sidebar-product'); ?>
                </div>
                <div class="col-product-right">
                    <div class="header-content-product">
                        <?php echo get_template_part('sections/archive-product/top-archive') ?>
                    </div>
                    <div class="center-content-product">
                    <?php echo get_template_part('sections/archive-product/product-archive') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="loading">
    <img src="<?php echo get_template_directory_uri() . '/images/loading.svg' ?>" alt="loading" class="">
</section>
<?php
get_footer('shop'); ?>
