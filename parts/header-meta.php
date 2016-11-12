<meta charset="<?php bloginfo( 'charset' ); ?>">
<!--[if 9]><meta content='IE=edge' http-equiv='X-UA-Compatible'/><![endif]-->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php wp_title('-'); ?></title>
<?php if(get_field('favicon', 'option')): ?><link rel="shortcut icon" type="image/png" href="<?php the_field('favicon', 'option'); ?>" /><?php endif; ?>
<?php wp_head(); ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/html5.js"></script>
<![endif]-->
<?php /*
<!-- Facebook OG -->
<meta property="og:title" content='<?php bloginfo('name'); echo " - ".get_the_title(get_the_ID()); ?>'/>
<meta property="og:site_name" content="<?php bloginfo('name'); ?>"/>
<meta property="og:type" content="article"/>
<meta property="og:image" content="<?php
if (has_post_thumbnail()){
    $FB_img = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full', false );
    if(isset($FB_img[0])) echo $FB_img[0]; else the_field('share_via_facebook_image', 'option');
} else {
    the_field('share_via_facebook_image', 'option');
} ?>" />
<meta property="og:url" content="<?php the_permalink(get_the_ID(get_the_ID())); ?>"/>
<meta property="og:description" content="<?php bloginfo('description'); ?>" />
<!-- END: Facebook OG -->

 */ ?>