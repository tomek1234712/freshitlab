<?php
/*
Template Name: Home page
*/
get_header();

$col1 = get_field('w_sprzedaży');
$col2 = get_field('zakonczone');
$col3 = get_field('kontakt');

?>

<div class="row">
    <div class="col-md-4 col-xs-12">
        <?php if(!empty($col1)): ?>
            <h2 class="col-title color-orange">Inwestycje w sprzedaży</h2>
            <div class="col-content list-type-1">
                <?php $loop = 0; foreach($col1 as $itemId): $loop++ ?>
                <a style="background-color: rgba(82, 89, 89, 0.<?php echo 100 - $loop * 10; ?>);" href="<?php echo get_permalink($itemId)?>" title="<?php echo get_the_title($itemId); ?>"><?php echo get_the_title($itemId); ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-md-4 col-xs-12">
        <?php if(!empty($col1)): ?>
            <h2 class="col-title">Inwestycje zakończone</h2>
            <div class="col-content list-type-2">
                <?php $loop = 0; foreach($col2 as $itemId): $loop++ ?>
                    <a href="<?php echo get_permalink($itemId)?>" title="<?php echo get_the_title($itemId); ?>">
                        <?php echo get_the_post_thumbnail($itemId, 'col33'); ?>
                        <span>Zobacz >></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-md-4 col-xs-12">
        <?php if(!empty($col1)): ?>
            <h2 class="col-title">Kontakt</h2>
            <div class="col-content col-contact">
                <?php echo $col3; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php get_footer(); ?>