<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <?php get_template_part('parts/header','meta'); ?>
</head>
<body <?php post_class(array('error-404-page')); ?>>
    <?php get_template_part('parts/header','menusection'); ?>
    <div class="page-wrap">
        <div class="e404box">
            <strong>404</strong>
            <span>Nie powinieneś się tutaj<br />znaleźć. przepraszamy.</span>
            <a href="<?php echo site_url(); ?>" class="custom-icon-home large dw-button navy-blue">WRÓĆ DO STRONY GŁÓWNEJ</a>
        </div>
    </div>
    <?php wp_footer(); ?>
</body>
</html>