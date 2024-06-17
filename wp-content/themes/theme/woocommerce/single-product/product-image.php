<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;
?>
<div class="woocommerce-product-gallery woocommerce-product-gallery--with-images images">
	<div thumbsSlider="" class="swiper mySwiper">
		<div class="swiper-wrapper">
			<div class="swiper-slide">
				<img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" alt="chi-tiet-san-pham" />
			</div>
			<?php
			$i = 2;
			$attachment_ids = $product->get_gallery_image_ids();
			foreach( $attachment_ids as $attachment_id ):
				$image_link = wp_get_attachment_url( $attachment_id );?>
				<div class="swiper-slide">
					<img src="<?php echo $image_link ?>" alt="image">
				</div>
				<?php if($i == 4){
					break;
				} ?>
				<?php $i++;endforeach;
			?>
		</div>
		<div class="swiper-scrollbar"></div>
	</div>
	<div class="swiper mySwiper2">
		<div class="swiper-wrapper">
			<div class="swiper-slide">
				<img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" alt="chi-tiet-san-pham" />
			</div>
			<?php
			$i = 2;
			$attachment_ids = $product->get_gallery_image_ids();
			foreach( $attachment_ids as $attachment_id ):
				$image_link = wp_get_attachment_url( $attachment_id );?>
				<div class="swiper-slide">
					<img src="<?php echo $image_link ?>" alt="image">
				</div>
				<?php if($i == 4){
					break;
				} ?>
				<?php $i++;endforeach;
			?>
		</div>
		<div class="swiper-pagination"></div>
	</div>
	<div class="box-dots">
		<div class="swiper-button-prev swiper-btn">
			<img src="<?php echo get_template_directory_uri() . '/images/prev.svg' ?>" alt="prev" class="prev-btn btn-dots">
		</div>
		<div class="swiper-button-next swiper-btn">
			<img src="<?php echo get_template_directory_uri() . '/images/next.svg' ?>" alt="next" class="next-btn btn-dots">
		</div>
	</div>
</div>

