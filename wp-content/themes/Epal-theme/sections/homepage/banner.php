<section id="banner-main">
    <div class="banner-carousel owl-carousel owl-theme">
        <?php
        $images = get_field('sliders', get_the_id()) ? get_field('sliders', get_the_id()) : [];
        foreach($images as $image):
        ?>
        <div class="item">
            <a href="<?php echo $image['slide_link']; ?>">
                <img src="<?php echo $image['slide_img']['url']; ?>" alt="<?php echo $image['slide_img']['alt']; ?>">
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="container banner-menu">
        <div class="row">
            <div class="col-md-12">
                <div class="menu-category">
                    <div class="bg-overplay"></div>
                    <?php get_template_part('sections/menu-category'); ?>
                </div>
            </div>
        </div>
    </div>
</section>