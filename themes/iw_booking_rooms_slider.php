<?php
wp_enqueue_style('flexslider');
wp_enqueue_script('flexslider');
wp_enqueue_script('jquery-scroll');
?>
<div class="iwb-rooms-slider">
    <div class="row">
        <div class="col-md-5">
            <div class="nav-thumbnail">
                <?php if ($sub_title) : ?>
                    <div class="sub-title-block"><?php echo wp_kses_post($sub_title); ?></div>
                <?php endif; ?>
                <?php if ($title) : ?>
                    <h3 class="title-block"><?php echo wp_kses_post($title) ?></h3>
                <?php endif; ?>
                <div id="scrollbox3" class="iw-carousel flexslider left_menu">
                    <ul class="slides">
                        <?php
                        $ultility = new iwBookingUtility();
                        while ($query->have_posts()) : $query->the_post();
                            $room = new iwBookingRooms();
                            $roomInfo = $room->getRoomInfo(get_the_ID());
                            $price = $roomInfo->price;
                            ?>
                            <li>
                                <div class="img-wrap">
                                    <?php $thumb_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
                                    $thumb_image_url = inwave_resize($thumb_image_url[0], 170, 110);
                                    ?>
                                    <img src="<?php echo esc_url($thumb_image_url); ?>" alt=""/>
                                </div>
                                <div class="info-wrap">
                                    <h3 class="post-title"><?php the_title(); ?></h3>
                                    <div class="price-room">
                                        <span><?php echo esc_html__('Starting from ', 'monalisa') ?></span>
                                        <?php echo sprintf(esc_html__('%s / Night', 'monalisa'), $ultility->getMoneyFormated($roomInfo->price , $roomInfo->currency)); ?>
                                    </div>
                                </div>
                                <div style="clear: both"></div>
                            </li>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="iw-slider flexslider">
                <ul class="slides">
                    <?php
                    while ($query->have_posts()) : $query->the_post();
                    $room = new iwBookingRooms();
                    $roomInfo = $room->getRoomInfo(get_the_ID());
                    ?>
                        <li>
                            <?php $large_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
                            $large_image_url = inwave_resize($large_image_url[0], 1075, 870);
                            ?>
                            <div class="img" style="background: url('<?php echo esc_url($large_image_url); ?>') no-repeat"></div>
                        </li>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>