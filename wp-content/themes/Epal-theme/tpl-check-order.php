<?php
/**
 * Template Name: Check order by Phone
 */

get_header();
?>
<style>
    .woocommerce-orders-table_row-detail .flex{
        display: flex;
        flex-wrap: wrap;
    }
    .woocommerce-orders-table_row-detail .flex p{
        width: 50%;
    }
</style>
<section id="check-order-main" class="page-news single-news wrapper-content">
    <div class="container">
        <div class="check-order text-center woocommerce">
            <form class="woocommerce-form woocommerce-form-track-order track_order">
                <p>Để kiểm tra đơn hàng của bạn xin vui lòng nhập số diện thoại của bạn vào ô dưới đây và nhấn nút "Kiểm
                    tra". Số điện thoại là số bạn đã đặt hàng có trong biên lai hoặc có trong email xác nhận mà bạn nhận
                    được.
                </p>
                <p class="form-group">
                    <input id="text-order-phone" name="phone" type="number" style="min-width:250px"
                        placeholder="Nhập số điện thoại để kiểm tra">
                    <span id="submit-check-phone" class="btn btn-primary">Kiểm tra</span>
                </p>
            </form>
            <div class="entry-content" style="display: none;">
                <div class="woocommerce">
                    <section class="woocommerce-order-details">
                        <h2 class="woocommerce-order-details__title" style="display: none;">Các đơn hàng</h2>
                        <div class="entry-content-table">
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</section>
<?php wp_footer() ?>
</body>
<script>
jQuery(document).ready(function () {
    jQuery(document).on('click', '#submit-check-phone', function(e) {
        var phone = jQuery('#text-order-phone').val();
        jQuery.ajax({
            url: '<?php echo get_home_url() ?>/wp-admin/admin-ajax.php',
            type: 'POST',
            dataType: 'json',
            data: {
                phone: phone,
                action: 'check_order_phone'
            },
            success: function(response) {
                if (response) {
                    jQuery('#check-order-main .entry-content .entry-content-table').html(response.data);
                    jQuery('#check-order-main .entry-content').css('display', 'block');
                    let entry = jQuery('#check-order-main .entry-content .entry-content-table table');
                    if (entry.length > 0) {
                        jQuery('#check-order-main .entry-content .woocommerce-order-details__title')
                            .css('display', 'block');
                    }else{
                        jQuery('#check-order-main .entry-content .woocommerce-order-details__title')
                            .css('display', 'none');
                    }
                } else {
                    console.log(response)
                }
            },
            error: function(data) {
                console.log('fail');
            }
        })
    });
    jQuery(document).on('click', '.woo-button-more', function(e){
        e.preventDefault();
        if(jQuery(this).hasClass('active')){
            jQuery(this).removeClass('active');
            let href=jQuery(this).attr('href');
            jQuery(href).slideUp();
        }else{
            jQuery('.woo-button-more').removeClass('active');
            jQuery(this).addClass('active');
            jQuery('.woocommerce-orders-table_row-detail').slideUp();
            let href=jQuery(this).attr('href');
            jQuery(href).slideDown();
        }
    });
})
</script>