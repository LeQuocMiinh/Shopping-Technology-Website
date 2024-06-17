<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 *
 * @var bool   $readonly If the input should be set to readonly mode.
 * @var string $type     The input type attribute.
 */

defined('ABSPATH') || exit;

/* translators: %s: Quantity. */
$label = !empty($args['product_name']) ? sprintf(esc_html__('%s quantity', 'woocommerce'), wp_strip_all_tags($args['product_name'])) : esc_html__('Quantity', 'woocommerce');

?>
<div class="quantity">
	<?php
	/**
	 * Hook to output something before the quantity input field.
	 *
	 * @since 7.2.0
	 */
	do_action('woocommerce_before_quantity_input_field');
	?>
	<button class="qty-minus" <?php echo $input_value > 1 ? '' : 'disabled' ?>>
		<svg width="15" height="3" viewBox="0 0 15 3" fill="#212B36" xmlns="http://www.w3.org/2000/svg">
			<path d="M1.5 1.5H13.5" stroke="<?php echo $input_value > 1 ? '#212B36' : '#E3E3E3' ?>" stroke-width="1.5" stroke-linecap="round"
				stroke-linejoin="round" />
		</svg>
	</button>
	<label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>">
		<?php echo esc_attr($label); ?>
	</label>
	<input type="<?php echo esc_attr($type); ?>" <?php echo $readonly ? 'readonly="readonly"' : ''; ?>
		id="<?php echo esc_attr($input_id); ?>" class="<?php echo esc_attr(join(' ', (array) $classes)); ?>"
		name="<?php echo esc_attr($input_name); ?>" value="<?php echo esc_attr($input_value); ?>"
		aria-label="<?php esc_attr_e('Product quantity', 'woocommerce'); ?>" size="4"
		min="<?php echo esc_attr($min_value); ?>" max="<?php echo esc_attr(0 < $max_value ? $max_value : ''); ?>"
		<?php if (!$readonly): ?> step="<?php echo esc_attr($step); ?>"
			placeholder="<?php echo esc_attr($placeholder); ?>" inputmode="<?php echo esc_attr($inputmode); ?>"
			autocomplete="<?php echo esc_attr(isset($autocomplete) ? $autocomplete : 'on'); ?>" <?php endif; ?> />
	<?php
	/**
	 * Hook to output something after quantity input field
	 *
	 * @since 3.6.0
	 */

	do_action('woocommerce_after_quantity_input_field');
	?>
	<button class="qty-plus">
		<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path
				d="M11.75 5.75H7.25V1.25C7.25 1.05109 7.17098 0.860322 7.03033 0.71967C6.88968 0.579018 6.69891 0.5 6.5 0.5C6.30109 0.5 6.11032 0.579018 5.96967 0.71967C5.82902 0.860322 5.75 1.05109 5.75 1.25V5.75H1.25C1.05109 5.75 0.860322 5.82902 0.71967 5.96967C0.579018 6.11032 0.5 6.30109 0.5 6.5C0.5 6.69891 0.579018 6.88968 0.71967 7.03033C0.860322 7.17098 1.05109 7.25 1.25 7.25H5.75V11.75C5.75 11.9489 5.82902 12.1397 5.96967 12.2803C6.11032 12.421 6.30109 12.5 6.5 12.5C6.69891 12.5 6.88968 12.421 7.03033 12.2803C7.17098 12.1397 7.25 11.9489 7.25 11.75V7.25H11.75C11.9489 7.25 12.1397 7.17098 12.2803 7.03033C12.421 6.88968 12.5 6.69891 12.5 6.5C12.5 6.30109 12.421 6.11032 12.2803 5.96967C12.1397 5.82902 11.9489 5.75 11.75 5.75Z"
				fill="#212B36" />
		</svg>
	</button>
</div>
<?php
