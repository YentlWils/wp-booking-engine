<?php
wp_enqueue_script('isotope.pkgd.min');
wp_enqueue_script('imagesloaded');
wp_enqueue_script('filtering');
?>
<div class="iwb-rooms">
    <div id="iw-isotope-main" class="iwb-rooms-grid isotope">
        <div class="row">
            <?php
            $ultility = new iwBookingUtility();

            while ($query->have_posts()) : $query->the_post();
                $room = new iwBookingRooms();
                $roomInfo = $room->getRoomInfo(get_the_ID());
                $room_available = $room->getRoomEmpty(get_the_ID(), $time_start, $time_end);
                ?>
                <div class="iwb-room element-item col-sm-6 col-xs-12">
                    <div class="room-inner">
                        <?php $large_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
                        $large_image_url = inwave_resize($large_image_url[0], 750, 736);
                        ?>
                        <div class="img-wrap" style="background: url('<?php echo esc_url($large_image_url); ?>') no-repeat">
                        </div>
                        <div class="room-info">
                            <h3 class="title"><a href="<?php echo esc_url(get_the_permalink()); ?>"><?php the_title(); ?></a></h3>
                            <?php if ($roomInfo->average_rating) :
                                $rating_star = (($roomInfo->average_rating) / 5) * 100;
                                $rating_star = number_format( $rating_star, 2, '.', '' );
                            ?>
                            <div class="iwb-rating">
                                <div class="iw-star-rating">
                                    <span class="rating" style="width: <?php echo esc_attr($rating_star).'%' ?>"></span>
                                </div>
                                <div class="rating-count"><?php echo (int)$roomInfo->review_count; ?><?php esc_html_e(' Review', 'monalisa'); ?></div>
                                <div style="clear: both"></div>
                            </div>
                            <?php endif; ?>
                            <ul class="room-meta">
                                <li class="available"><?php esc_html_e('Status: ', 'monalisa'); ?><?php echo ($room_available > 5 ? '<span>'.esc_html__("Available", 'monalisa').'</span>' : '<span class="room-left">'.(sprintf(_n('%d Room left', '%d Rooms left',$room_available, 'monalisa'), $room_available))).'</span>'?></li>
                                <li class="deposit"><?php esc_html_e('Deposit: ', 'monalisa'); ?><span><?php echo ($roomInfo->deposit ? sprintf(esc_html__('Required %d%%', 'monalisa'), $roomInfo->deposit) : esc_html__('Not Required', 'monalisa')); ?></span></li>
                                <li class="beds"><?php esc_html_e('Beds: ', 'monalisa'); ?><span><?php echo str_pad($roomInfo->beds, 2, "0", STR_PAD_LEFT); ?></span></li>
                                <li class="passenger"><?php esc_html_e('Passenger: ', 'monalisa'); ?><span><?php echo str_pad($roomInfo->people_amount, 2, "0", STR_PAD_LEFT); ?></span></li>
                            </ul>
                            <div class="detail-price">
                                <a class="text" href="<?php echo esc_url(get_the_permalink()); ?>"><?php esc_html_e('Detail ', 'monalisa'); ?></a>
                                <span class="iwb-price"><?php esc_html_e('Starting at ', 'monalisa'); ?><?php echo '<span class="price">'.$ultility->getMoneyFormated($roomInfo->price).'</span>' ?><?php esc_html_e(' / Night', 'monalisa');  ?></span>
                            </div>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <div class="load-more-post">
        <?php
        $room = new iwBookingRooms();
        $query = $room->getRoomList($category, $ids, $order_by, $order_dir, $limit, 'category');
        $rs = $room -> load_more_room($query);
        echo (string)$rs;
        wp_reset_postdata();
        ?>
    </div>
</div>