<?php
/*
Template Name: Contact
*/
get_header(); ?>
<div class="tpl-contact-page">
    <?php if (have_posts()): ?>
        <?php while (have_posts()): the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; ?>
    <?php endif; ?>
</div>
<?php get_footer(); ?>