<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $product;
if ( $related_products ) : ?>

<section class="related products">
    <?php 
		$taxonomy_product_cat = wp_get_post_terms($product->get_id(),'product_cat',array( 'order' => 'ASC','orderby'=>'id') );
		$category_laptop = get_terms('product_cat',
		array( 'parent' => 19,'hide_empty' => 0,'order' => 'ASc', 'orderby' => 'title' ));
		$id_related = '';
		foreach($taxonomy_product_cat as $item){
			if($category_laptop->term_id == $item->term_id);
			$id_related = $item->term_id;
		}
	?>
	<div class="top-related">
		<h2>Sản phẩm liên quan</h2>
		<a href="<?php echo get_category_link($id_related) ?>" class="see-related">
			Xem tất cả
		</a>
	</div>
	<div class="list-products program-releted-carousel owl-theme owl-carousel">
		<?php
		$tax_query = array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    =>  $id_related,
			),
		);
		$allProductsByCate = new WP_Query(
			array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => 5,
				'order' => 'DESC',
				'orderby' => 'date',
				'tax_query' => $tax_query,
			)
		);
		while($allProductsByCate->have_posts()) : $allProductsByCate->the_post();
		global $product;
		$product = wc_get_product(get_the_ID());
		$price_regular = $product->get_regular_price();
		if( $product->is_on_sale() ) {
			$price_sale = $product->get_sale_price();
			$discount = devvn_presentage_bubble($product);
		}
		?>

        <div class="product-item">
            <a href="<?php the_permalink() ?>">
                <div class="image">
                    <img src="<?php the_post_thumbnail_url() ?>" alt="<?php the_post_thumbnail_caption() ?>">
                </div>
                <div class="infor">
                    <div class="name">
                        <p class="ellipsis"><?php the_title() ?></p>
                    </div>
                    <div class="price">
                        <?php
						if($product->get_type() == 'variable'){
							$default_attributes = $product->get_default_attributes();
							foreach ($default_attributes as $default_attribute => $value){
								$color_vr = $value;
								$att = $default_attribute;
							}
							$id_vari = get_product_variation_id($color_vr,$product->get_id(),'attribute_' . $att);
							$variation_obj = new WC_Product_variation($id_vari);
							$stock = $variation_obj->get_stock_status();
						}
						else{
							$stock = $product->get_stock_status();
						}
						?>
                        <?php if($stock == 'instock'): ?>
                        <?php
					if($product->get_type() == 'variable'):
						$default_attributes = $product->get_default_attributes();
						$id_vari = '';
						if(!empty($default_attributes)){
							foreach ($default_attributes as $default_attribute => $value){
								$color_vr = $value;
								$att = $default_attribute;
							}
							$id_vari = get_product_variation_id($color_vr,$product->get_id(),'attribute_' . $att);
						}
						if(count($default_attributes) > 0):
							$price_regular = get_post_meta($id_vari, '_regular_price', true);
							$price_sale = get_post_meta($id_vari,'_sale_price',true);
							if($price_sale != ''){
								$discount = devvn_presentage_bubble($product);
							}
							else{
								$discount = '0%';
							}
							if($price_sale != ''): ?>
                        <div class="price-sale">
                            <span><?php echo number_format((float)$price_sale, 0, '.', '.');  ?>đ</span>
                        </div>
                        <?php endif; ?>
                        <div
                            class="price-regular <?php echo $price_sale == '' ? 'off-sale' : 'on-sale line-through' ?>">
                            <span
                                class="price-text"><?php echo number_format((float)$price_regular,0,'.','.'); ?>đ</span>
                            <?php if($price_sale != ''): ?>
                            <span class="discount"><?php echo $discount ?></span>
                            <?php endif; ?>
                        </div>
                        <?php else:
							echo $product->get_price_html();
						endif;
					else:
					?>
                        <?php if($price_sale != ''): ?>
                        <div class="price-sale">
                            <span><?php echo number_format((float)$price_sale, 0, '.', '.');  ?>đ</span>
                        </div>
                        <?php endif; ?>
                        <div
                            class="price-regular <?php echo $price_sale == '' ? 'off-sale' : 'on-sale line-through' ?>">
                            <span
                                class="price-text"><?php echo number_format((float)$price_regular,0,'.','.'); ?>đ</span>
                            <?php if($price_sale != ''): ?>
                            <span class="discount"><?php echo $discount ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endif;
					endif;
					?>
                        <?php if ($stock == 'outofstock'): ?>
                        <div class="custom-stock">
                            <span>
                                <?php echo get_field('text_out_stock', 'option') ?>
                            </span>
                        </div>

                        <?php endif; ?>
                        <?php if ($stock == 'onbackorder'): ?>
                        <div class="custom-stock">
                            <span>
                                <?php echo get_field('text_stocking', 'option') ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
			<div class="bot-content">
				<div class="add-pc">
					<a href="<?php the_permalink() ?>" class="detail">
						Chi tiết
						<img src="<?php echo get_template_directory_uri().'/images/arrow.svg' ?>"
							alt="icon">
					</a>
					<?php 
						if($product->get_type() == 'variable') {
							$id_var = $id_vari;
							$id_pr = get_the_ID();

						}
						else {
							$id_pr = get_the_ID();
							$id_var = '';
						}
					?>
					<div class="add-to-cart add_cart_ajax_session"
						data-product_id="<?php echo $id_pr ?>" data-variation_id="<?php echo $id_var ?>">
						<img src="<?php echo get_template_directory_uri().'/images/add-cart.svg' ?>"
							alt="icon">
					</div>
				</div>
				<div class="add-mobile">
					<div class="add-to-cart add_cart_ajax_session"
					data-product_id="<?php echo $id_pr ?>" data-variation_id="<?php echo $id_var ?>">
						Thêm vào giỏ
						<img src="<?php echo get_template_directory_uri().'/images/cart.svg' ?>"
							alt="icon">
					</div>
				</div>
			</div>
        </div>

        <?php endwhile;  wp_reset_query(); ?>
    </div>
</section>
<?php
endif;

wp_reset_postdata();