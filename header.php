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
<body <?php post_class(); ?>>
    <?php get_template_part('parts/header','menusection'); ?>
    <?php get_template_part('parts/header','slider'); ?>
    <div class="page-content">
        <div class="page-wrap">