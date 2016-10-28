<?php
if ($rooms->have_posts()) {

?>
	<!--<h2 class="title-room-available"><?php echo __('Room available for you', 'inwavethemes'); ?></h2>-->
	
	<div class="iw-rooms-available">
	<?php
    $ultility =  new iwBookingUtility();
    while ($rooms->have_posts()) : $rooms->the_post();
        $room_obj = $room_class->getRoomInfo(get_the_ID());
        ?>
        <div class="iw-room">
            <div class="room-inner">
                <div class="img-wrap">
					<img src="<?php echo esc_url(inwave_resize($room_obj->image_feature, 140, 95)); ?>" alt="" />
                </div>
				<div class="quantity-room">
						<div class="unit-price">
							<span class="unit-price-label"><?php echo __('Price', 'inwavethemes'); ?></span>
							<span class="unit-price-value"><?php echo wp_kses_post($ultility->unit_price($room_obj->price)); ?></span>
						</div>
						<div class="bcart-quantity">
                            <a href="#" class="add-bcart iwb-room-selection" data-roomid="<?php echo esc_attr($room_obj->ID); ?>">
                                <?php
                                echo '<span>' . esc_html__('Select', 'monalisa') . '</span>';
                                ?>
                            </a>
                        </div>
				</div>
                <div class="room-info">
                    <div class="room-message">

                    </div>
                    <div class="title-block-room">
                        <h3 class="title"><a class="theme-color-hover" href="<?php echo esc_url(get_the_permalink()); ?>" title="" target="_blank"><?php echo get_the_title(); ?></a></h3>
                        <?php
                        if ($room_obj->average_rating) {
                            $rating_star = (($room_obj->average_rating) / 5) * 100;
                            $rating_star = number_format($rating_star, 2, '.', '');
                            ?>
                            <div class="rating">
                                <div class="star-rating">
                                    <span class="rating" style="width: <?php echo esc_attr($rating_star) . '%' ?>"></span>
                                </div>
                                <?php echo (int) $room_obj->review_count; ?><?php esc_html_e(' Review', 'monalisa'); ?>
                            <!--<div class="clear"></div>-->
                            </div>
                        <?php } ?>
					</div>
					<div class="room-meta-box">
						<div class="col-left">
							<ul class="room-meta">
								<li class="deposit"><?php esc_html_e('Deposit: ', 'monalisa'); ?><span><?php echo ($room_obj->deposit ? sprintf(esc_html__('Required %d%%', 'monalisa'), $room_obj->deposit) : esc_html__('Not Required', 'monalisa')); ?></span></li>
                                <?php if($room_obj->room_available !== false) ?>
                                <li class="available"><?php esc_html_e('Available: ', 'monalisa'); ?><span><?php echo $room_obj->room_available === true ? __('Unlimited', 'monalisa') : str_pad($room_obj->room_available, 2, "0", STR_PAD_LEFT); ?></span></li>
                                <?php ?>
								<li class="beds"><?php esc_html_e('Passenger/Beds: ', 'monalisa'); ?><span><?php echo $room_obj->people_amount.'/'.$room_obj->beds; ?></span></li>
							</ul>
						</div>
						<div class="col-right room-services">
							<?php
							if (!empty($room_obj->premium_services)) {
								echo '<div class="premium_services">';
								foreach ($room_obj->premium_services as $premium_service) {
									if ($premium_service->getId()) {
										echo '<label class="service-item"><input type="checkbox" name="premium_service[]" value="' . $premium_service->getId() . '"> ' . $premium_service->getName() . ' ' . $ultility->price($premium_service->getPrice()) . '</label>';
									}
								}
								echo '<div class="clear"></div>';
								echo '</div>';
							}
							?>
						</div>
						<div class="clear"></div>
					</div>
                </div>
            </div>
        </div>
        <?php
    endwhile;
    ?>
	</div>
    <div class="load-more-post">
        <?php
        iwBookingUtility::iwbDisplayPagination($rooms, $paged);
        wp_reset_postdata();
        ?>
    </div>
    <?php
} else {
    ?>
    <div class="iw-room not-available">
        <div class="room-inner">
            <?php echo esc_html__('Room is not available', 'monalisa') ?>
        </div>
    </div>
    <?php
}
