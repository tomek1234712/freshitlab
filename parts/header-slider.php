<?php
$slider = get_field('slider', 'option');
?>
<?php if(!empty($slider)): ?>
    <div class="top-slider-bar">
        <div class="page-wrap">
            <div id="topslider">
                <ul>
                    <?php foreach($slider as $slide):?>
                    <li>
                        <div class="slide-box<?php echo ($slide['link_slajdu'] != "") ? ' pointer" onclick="javascript:location.href=\''.$slide['link_slajdu'].'\'"' : '""'; ?>>
                            <div class="img-slide-box">
                                <img src="<?php get_my_image($slide['grafika'], 'slider'); ?>" alt="" />
                            </div>
                            <?php if( !empty($slide['opis']) ): ?>
                            <div class="label"><?php echo $slide['opis']; ?></div>
                            <?php endif; ?>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>