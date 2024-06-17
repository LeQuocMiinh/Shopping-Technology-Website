<section id="feature-cate" class="section-wrapper mt-60">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="content">
                    <div class="cate-title">
                        <?php echo get_field('cate_title', get_the_ID())?>
                    </div>
                    <div class="cate-list">
                        <?php $items = get_field('cate_items', get_the_ID()); ?>
                        <?php foreach($items as $item): ?>
                        <a class="cate-item" href="<?php echo get_term_link($item['cate']->term_id, 'product_cat') ?>">
                            <img src="<?php echo $item['image']['url']; ?>" alt="cate">
                            <div class="name">
                                <span class="ellipsis">
                                    <?php echo $item['cate']->name; ?>
                                </span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-60">
                <div class="banner-section cate-banner">
                    <?php $items = get_field('cate_banner', get_the_ID()); ?>
                    <?php foreach($items as $item): ?>
                    <a class="banner-item" href="<?php echo $item['link'] ?>">
                        <img src="<?php echo $item['image']['url']; ?>" alt="cate">
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>