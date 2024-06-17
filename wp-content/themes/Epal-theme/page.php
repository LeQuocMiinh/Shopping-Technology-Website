<?php get_header();
get_template_part('sections/breadcrumb');
?>
<div id="primary" class="content-area p-25 fs-16">
    <div class="container">
        <div class="row">
            <div class="col-12 default-content">
                <?php while (have_posts()):
                    the_post();
                    the_content();
                endwhile; ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer() ?>