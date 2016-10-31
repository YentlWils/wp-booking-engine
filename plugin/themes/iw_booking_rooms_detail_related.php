
<?php
wp_enqueue_style('owl-carousel');
wp_enqueue_style('owl-theme');
wp_enqueue_style('owl-transitions');
wp_enqueue_script('owl-carousel');

$ultility = new iwBookingUtility();
$roomRelated = array(
    'navigation' => false, // Show next and prev buttons
	'navigationText' => array('<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'),
	'autoPlay'=> true,
    'pagination' => false,
	'items' => 3,
	'itemsDesktop' => [1199, 3],
	'itemsDesktopSmall' => [979, 2],
	'itemsTablet' => [768, 1],
	'itemsMobile' => [479, 1],
);
?>
<div class="iwb-rooms-related">

                <div class="owl-carousel" data-plugin-options="<?php echo htmlspecialchars(json_encode($roomRelated)); ?>">
                        <?php
                        while ($query->have_posts()) : $query->the_post();
                            $room = new iwBookingRooms();
                            $roomInfo = $room->getRoomInfo(get_the_ID());
                            $price = $roomInfo->price;
                            ?>
                            <div class="rooms-related-item">
								<div class="rooms-related-item-inner">
									<div class="room-img">
										<?php $large_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
                                        $large_image_url = inwave_resize($large_image_url[0], 750, 547, true);
										?>
										<img src="<?php echo esc_url($large_image_url); ?>" alt=""/>
									</div>
									<div class="room-related-detail">
										<h3 class="title"><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></h3>
										<?php 
											$rating_star = (($roomInfo->average_rating) / 5) * 100;
											$rating_star = number_format( $rating_star, 2, '.', '' );
										?>
											<div class="rating">
											<?php if ($roomInfo->average_rating){ ?>
												<div class="star-rating">
													<span class="rating" style="width: <?php echo esc_attr($rating_star).'%' ?>"></span>
												</div>
											<?php } ?>
											<?php echo (int)$roomInfo->review_count; ?><?php esc_html_e(' Review', 'monalisa'); ?>
											<div style="clear:both;"></div>
											</div>
										
										<div class="room-related-info-list">
											<div class="room-related-info-item">
												<span class="room-info-label"><?php esc_html_e('Status: ', 'monalisa'); ?></span>
												<?php 
													$room_avaiable = $roomInfo->room_amount;
													if ($room_avaiable >= 5){
														echo '<span class="available room-info-value green">Available</span>';
													} elseif ($room_avaiable < 5){
														echo '<span class="available room-info-value red">'.$room_avaiable.' room left!</span>';
													}
												?>
											</div>
											<div class="room-related-info-item">
												<span class="room-info-label"><?php esc_html_e('Deposit: ', 'monalisa'); ?></span>
												<span class="room-info-value">
													<?php 
														$room_deposit = $roomInfo->deposit;
														if ($room_deposit > 0){
															echo esc_html_e('Required ', 'monalisa').$room_deposit.'%';
														} else {
															echo esc_html_e('Not Required', 'monalisa');
														}
													?>
												</span>											
											</div>
											<div class="room-related-info-item">
												<span class="room-info-label"><?php esc_html_e('Beds: ', 'monalisa'); ?></span>
												<span class="room-info-value">
													<?php echo wp_kses_post($roomInfo->beds);	?>
												</span>											
											</div>
											<div class="room-related-info-item">
												<span class="room-info-label"><?php esc_html_e('Passenger: ', 'monalisa'); ?></span>
												<span class="room-info-value">
													<?php 
														echo wp_kses_post($roomInfo->people_amount);
													?>
												</span>											
											</div>
											
											<div style="clear:both;"></div>
											
										</div>
										<div class="room-related-footer">
											<div class="read-more">
												<a href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Details', 'monalisa'); ?></a>
											</div>
											<div class="price-starting">
												<?php esc_html_e('Starting at ', 'monalisa'); ?><span><?php echo wp_kses_post($ultility->getMoneyFormated($price)); ?></span><?php esc_html_e('/night', 'monalisa'); ?>
											</div>
											<div style="clear:both;"></div>
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