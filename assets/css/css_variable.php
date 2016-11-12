<style>
    .page-wrap{
        width: <?php the_field('page_width', 'option'); ?>px;
    }
    <?php $logoIMG = wp_get_attachment_image_src( get_field('logo', 'option'), 'full', false );?>
    .logo{
        background-image: url(<?php echo $logoIMG[0]; ?>);
        width: <?php echo ( $logoIMG[1] + 30 ); ?>px;
        height: <?php echo ( $logoIMG[2] + 20 ); ?>px;
    }
</style>

