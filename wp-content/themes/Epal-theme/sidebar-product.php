<div class="filter-using filter-box">
    <h3 class="title">
        Bộ lọc đã dùng
    </h3>
    <ul class="filter-using-list">
    </ul>
    <button class="btn-delete-filter-using d-none">
        Xóa tất cả
    </button>
    <div class="line-bottom">

    </div>
</div>
<div class="filter-price filter-box">
    <h3 class="title">Khoảng giá</h3>

    <div class="input-range">
        <input type="text" readonly min=0 max="99000000" value='0' id="min_price" data-number=''
            class="price-range-field" />
        <input type="text" readonly min=0 max="100000000" value='1000000000' id="max_price" data-number=''
            class="price-range-field max-value" />
    </div>

    <div id="slider-range" class="price-filter-range" name="rangeInput"></div>
    <div id="slider-range" class="price-filter-range"></div>
    <div class="line-bottom"></div>
</div>
<div class="filter-series filter-box">
    <?php $id_term = get_queried_object_id(); ?>
    <?php $terms = get_term($id_term); ?>
    <?php 
        $text = 'Thương hiệu';
        if($terms->parent != '0'){
            $text = 'Serise';
            $id_series = $id_term;
        }
        else {
            $id_series = 53;
        }
    ?>
    <h3 class="title title-show">
        <?php echo $text; ?>
        <img src="<?php echo get_template_directory_uri() . '/images/show-title.svg' ?>" alt="" class="">
    </h3>
    <div class="box-show">
        <ul class="list-series filter-list">
            <?php 
                $category_child = get_terms('product_cat',
                    array( 'parent' => $id_series,'hide_empty' => 0,'order' => 'ASc', 'orderby' => 'title' )
                );
            ?>
            <?php foreach($category_child as $child): ?>
            <li class="item-series item-filter <?php echo $terms->parent != '0' ? 'w-100' : '' ?>">
                <input type="checkbox" value="<?php echo $child->term_id ?>" data-name="<?php echo $child->name ?>"
                    class="checkbox" id="<?php echo 'item-' . $child->term_id ?>" name="checkbox-serise">
                <label for="<?php echo 'item-' . $child->term_id ?>" class="">
                    <span><?php echo $child->name ?></span>
                </label>
            </li>
            <?php endforeach; ?>
        </ul>
        <button class="btn-see-more see-more-series">
            <span class="text">Xem thêm</span>
            <img src="<?php echo get_template_directory_uri() . '/images/show.svg' ?>" alt="" class="">
        </button>
    </div>
    <div class="line-bottom"></div>
</div>
<div class="filter-demand filter-box">
    <h3 class="title title-show">
        Nhu cầu
        <img src="<?php echo get_template_directory_uri() . '/images/show-title.svg' ?>" alt="" class="">
    </h3>
    <div class="box-show">
        <ul class="list-demand filter-list">
            <?php 
                $id_demand = 79;
                $category_child = get_terms('product_cat',
                    array( 'parent' => $id_demand,'hide_empty' => 0,'order' => 'ASc', 'orderby' => 'title' )
                );
            ?>
            <?php foreach($category_child as $child): ?>
            <li class="item-demand item-filter">
                <input type="checkbox" value="<?php echo $child->term_id ?>" data-name="<?php echo $child->name ?>"
                    class="checkbox" id="<?php echo 'item-' . $child->term_id; ?>" name="checkbox-demand">
                <label for="<?php echo 'item-' . $child->term_id; ?>" class=""><?php echo $child->name ?></label>
            </li>
            <?php endforeach ?>
        </ul>
        <button class="btn-see-more see-more-demand">
            <span class="text">Xem thêm</span>
            <img src="<?php echo get_template_directory_uri() . '/images/show.svg' ?>" alt="" class="">
        </button>
    </div>

    <div class="line-bottom"></div>
</div>
<div class="filter-color filter-box">
    <h3 class="title title-show">
        Màu sắc
        <img src="<?php echo get_template_directory_uri() . '/images/show-title.svg' ?>" alt="" class="">
    </h3>
    <div class="box-show">
        <ul class="list-color filter-list">
            <?php 
                $terms_color = get_terms([
                    'taxonomy' => 'pa_mau-sac',
                    'hide_empty' => false,
                ]);
            ?>
            <?php foreach($terms_color as $color): ?>
            <li class="item-color item-filter">
                <input type="checkbox" value="<?php echo $color->term_id ?>" data-name="<?php echo $color->name ?>"
                    class="checkbox" id="<?php echo 'item-' . $color->term_id  ?>" name="checkbox-color">
                <label for="<?php echo 'item-' . $color->term_id  ?>" class=""><?php echo $color->name ?></label>
            </li>
            <?php endforeach; ?>
        </ul>
        <button class="btn-see-more see-more-color">
            <span class="text">Xem thêm</span>
            <img src="<?php echo get_template_directory_uri() . '/images/show.svg' ?>" alt="" class="">
        </button>
    </div>

    <div class="line-bottom"></div>
</div>
<div class="filter-source filter-box">
    <h3 class="title title-show">
        Nguồn hàng
        <img src="<?php echo get_template_directory_uri() . '/images/show-title.svg' ?>" alt="" class="">
    </h3>
    <ul class="list-source filter-list">
        <?php 
            $terms_source = get_terms([
                'taxonomy' => 'pa_nguon-hang',
                'hide_empty' => false,
            ]);
        ?>
        <?php foreach($terms_source as $source): ?>
        <li class="item-source item-filter">
            <input type="checkbox" value="<?php echo $source->term_id ?>" data-name="<?php echo $source->name ?>"
                class="checkbox" id="<?php echo 'item-' . $source->term_id  ?>" name="checkbox-source">
            <label for="<?php echo 'item-' . $source->term_id  ?>" class=""><?php echo $source->name ?></label>
        </li>
        <?php endforeach; ?>
    </ul>
</div>