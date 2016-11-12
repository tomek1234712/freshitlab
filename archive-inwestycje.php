<?php get_header(); ?>
    <div class="tpl-text-page single-invest">
        <?php if (have_posts()): ?>
            <h1 class="col-title color-orange">Inwestycje</h1>
            <div class="col-content list-type-2">
                <div class="row">
                    <?php while (have_posts()): the_post(); ?>
                        <div class="col-md-4 col-xs-12">
                            <a href="<?php echo the_permalink(); ?>" title="<?php echo the_title(); ?>">
                                <span style="float: left;"><?php echo the_title(); ?></span>
                                <?php the_post_thumbnail('col33crop'); ?>
                                <span>Zobacz >></span>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php get_footer(); ?>