<div class="booking-room">
    <?php $image_url = wp_get_attachment_url(get_post_thumbnail_id($booking_room['room_info']->ID)); ?>
	<div class="row">
		
		<div class="col-md-10 col-sm-10 col-xs-9">
			<div class="img-wrap">
				<img src="<?php echo esc_url(inwave_resize($image_url, 137, 93)); ?>" alt="" />
			</div>
			<div class="room-info">				
				<div class="booking-room-head">
					<h3 class="title">
						<a href="<?php echo esc_url(get_the_permalink($booking_room['room_info']->ID)); ?>" title="">
							<?php echo get_the_title($booking_room['room_info']->ID); ?>
						</a>
					</h3>
					<?php if ($booking_room['room_info']->average_rating){
						$rating_star = (($booking_room['room_info']->average_rating) / 5) * 100;
						$rating_star = number_format( $rating_star, 2, '.', '' );
						$review_count = $booking_room['room_info']->review_count;
					?>
						<div class="rating">
						<div class="star-rating">
							<span class="rating" style="width: <?php echo esc_attr($rating_star).'%' ?>"></span>
						</div>
						<?php echo sprintf(_n('%d Review', '%d Reviews', $review_count, 'monalisa'), $review_count); ?><?php ; ?>
						<div style="clear:both;"></div>
						</div>
					<?php } ?>
				</div>
				<div class="room-meta">
						<div class="meta-col-left">
							<div class="services"><?php echo sprintf(esc_html__('Includes: %s', 'monalisa'), '<span>'.implode(', ', $booking_room['service_titles']).'</span>'); ?></div>
							<div class="deposit"><?php echo sprintf(esc_html__('Deposit: %s', 'monalisa'), '<span>'.($booking_room['room_info']->deposit ? sprintf(esc_html__('Required %d%%', 'monalisa'), $booking_room['room_info']->deposit) : esc_html__('Not Required', 'monalisa')).'</span>'); ?></div>
						</div>
						<div class="meta-col-right">
							<div class="quantity"><?php echo sprintf(esc_html__('Rooms: %s', 'monalisa'), '<span>'.str_pad($booking_room['quantity'], 2, "0", STR_PAD_LEFT).'</span>'); ?></div>
							<div class="passenger"><?php echo sprintf(esc_html__('Passenger: %s', 'monalisa'), '<span>'.$booking_room['room_info']->people_amount.'</span>'); ?></div>
						</div>
				</div>
				<div class="remove">
					<a href="#" class="remove-booking-room" data-room="<?php echo esc_attr($booking_room['room_info']->ID); ?>"><?php esc_html_e('Remove', 'monalisa'); ?></a>
				</div>
				
			</div>
		</div>
		<div class="col-md-2 col-sm-2 col-xs-3">
			<div class="price">
				<span><?php esc_html_e('Price', 'monalisa'); ?></span><br />
				<strong><?php echo ($booking_room['price'] ? $ultility->getMoneyFormated($booking_room['price']) : esc_html__('Free', 'monalisa')); ?></strong>
			</div>
		</div>
	</div>
</div>