<?php $id_term = get_queried_object_id(); ?>
<?php $terms = get_term($id_term); ?>
<input type="text" hidden value='<?php echo $id_term ?>' id="id-category" class="id-category" />
<div class="category-qualyti">
    <h1 class="title">
        <?php echo $terms->name ?>
    </h1>
    <?php $qty = get_product_count_by_category($id_term); ?>
    <span class="qty">
        (
        <?php echo $qty ?> sản phẩm)
    </span>
</div>
<?php if ($terms->parent == '0'): ?>
    <ul class="list-category-child owl-carousel owl-theme">

        <?php
        $category_child = get_terms(
            'product_cat',
            array('parent' => 53, 'hide_empty' => 0, 'order' => 'ASc', 'orderby' => 'title')
        );
        ?>
        <?php foreach ($category_child as $child): ?>
            <?php $id_child = $child->term_id; ?>
            <?php $slug = get_category_link($id_child); ?>
            <?php $name = $child->name; ?>
            <li class="item-child">
                <a href="<?php echo $slug ?>" class="<?php echo $name ?>">
                    <?php echo $name ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<div class="order-type">
    <p class="title-order">Sắp xếp theo</p>
    <ul class="list-order">
        <li class="item-order" data-type="discount">
            Khuyến mãi tốt nhất
        </li>
        <li class="item-order" data-type="price-asc">
            Giá tăng dần
        </li>
        <li class="item-order" data-type="price-desc">
            Giá giảm dần
        </li>
        <li class="item-order" data-type="date-desc">
            Sản phẩm mới nhất
        </li>
        <li class="item-order" data-type="buy-desc">
            Sản phẩm bán chạy nhất
        </li>
    </ul>
</div>
<div class="row box-fillter">
    <div class="col-6">
        <button class="btn-fillter">Bộ lọc <img
                src="<?php echo get_template_directory_uri() ?>/images/fillter.svg"></button>
    </div>
    <div class="col-6">
        <div class="order-type-mobile">
            <select class="list-order">
                <option selected disabled>Sắp xếp theo</option>
                <option class="item-order" data-type="discount">Khuyến mãi tốt nhất</option>
                <option class="item-order" data-type="price-asc">Giá tăng dần</option>
                <option class="item-order" data-type="price-desc">Giá giảm dần</option>
                <option class="item-order" data-type="date-desc">Sản phẩm mới nhất</option>
                <option class="item-order" data-type="buy-desc">Sản phẩm bán chạy nhất</option>
            </select>
        </div>
    </div>
</div>