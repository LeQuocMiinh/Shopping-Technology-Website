<?php

/**
 * Template Name: Liên hệ
 */

get_header();

?>

<section id="contact-page">
    <div class="container">
        <?php get_template_part('sections/breadcrumb'); ?>
        <div class="row">
            <div class="contact-heading">
                <h2 class="title">
                    <?php the_field('title_contact') ?>
                </h2>
                <div class="desc">
                    <?php the_field('desc_contact') ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-4">
                <div id="contact-form" class="p-25">
                    <?php echo do_shortcode('[advanced_form form="form_66053cd893484" ajax="true" submit_text="Gửi thông tin"]') ?>
                </div>
            </div>
            <div class="col-12 col-md-8">
                <div class="google-map">
                    <iframe height="100%" src="<?php the_field('google_map') ?>" frameborder="0"></iframe>
                </div>
            </div>

        </div>

    </div>
</section>

<?php get_footer() ?>