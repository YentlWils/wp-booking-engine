<?php

$ultility = new iwBookingUtility();
$room = new iwBookingRooms();
$service = new iwBookingService();
$roominfo = $room->getRoomInfo(get_the_ID());
$all_base_services = $service->getServices(0, 0, 1, 'basic');

$galleries = $roominfo->images;
$galleryConfig = array(
    'navigation' => true, // Show next and prev buttons
	'navigationText' => array('<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'),
    'singleItem' => true,
	'autoPlay'=> false,
    'pagination' => true,
);
wp_enqueue_style('owl-carousel');
wp_enqueue_style('owl-theme');
wp_enqueue_style('owl-transitions');
wp_enqueue_script('owl-carousel');
?>
<?php get_header(); ?>
<div class="room-detail">

		<div class="room-avaiable-check">
			<div class="container">
				<div class="">
					<?php echo do_shortcode('[inwave_check_availability]'); ?>
				</div>
			</div>
		</div>
		<div class="galleries">
			<div class="container">
			<?php if ($galleries){ ?>
				<div class="room-gallery">
					<div class="room-gallery-inner">
						<div class="owl-carousel" data-plugin-options="<?php echo htmlspecialchars(json_encode($galleryConfig)); ?>">
							<?php
								foreach ($galleries as $gallery){
								?>
									<div class="gallery-item">
                                        <div class="img-bg" style="background: url('<?php echo esc_url($gallery); ?>') no-repeat"></div>
									</div>
								<?php
								}
							?>

						</div>
					</div>
				</div>
				<?php } ?>
				<div class="room-price-info">
					<div class="room-price-wrap">
						<?php esc_html_e('Start from:', 'monalisa'); ?>
						<span class="price"><?php echo wp_kses_post($ultility->getMoneyFormated($roominfo->price)); ?></span><span><?php esc_html_e('/night', 'monalisa'); ?></span>
					</div>
					<div class="passenger-number">
						<?php esc_html_e('Passenger: ', 'monalisa'); ?><span><?php echo wp_kses_post($roominfo->people_amount); ?></span>,
						<?php esc_html_e('Beds: ', 'monalisa'); ?><span><?php echo wp_kses_post($roominfo->beds); ?></span>
					</div>
				</div>
			</div>
		</div>
		
		<div class="room-content">
			<div class="container">
				<div class="room-desc">
					<h4 class="room-content-title"><?php esc_html_e('Description', 'monalisa'); ?></h4>
					<div class="room-desc-content">
						<?php echo wp_kses_post($roominfo->post_content); ?>
					</div>
				</div>
				<div class="room-service">
					<h4 class="room-content-title"><?php esc_html_e('Amenities', 'monalisa'); ?></h4>
					<div class="room-service-content">
						<div class="row">
						<?php
							$room_services = $roominfo->basic_services;
							foreach ($all_base_services as $service){
						?>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<div class="room-service-item">
									<?php if(in_array($service, $room_services)) echo '<i class="ion-checkmark-circled"></i>'; else echo '<i class="ion-close-circled"></i>';?>
									<span><?php echo wp_kses_post($service->getName()); ?></span>
								</div>
							</div>
						<?php 
						}
						?>
						</div>
					</div>
				</div>
				<div class="room-comment">
					<?php
						// If comments are open or we have at least one comment, load up the comment template
						if (comments_open() || get_comments_number()) :
							comments_template();
						endif;
						?>
				
				</div>
			</div>
			
		</div>

		<div class="room-related">	
			<div class="container">
				<div class="room-related-inner">
					<h4 class="room-content-title"><?php esc_html_e('Related rooms', 'monalisa'); ?></h4>
					<div class="room-related-content">
						<?php echo do_shortcode('[iwb_list_rooms style="detail_related"]'); ?>
					</div>
				</div>
			</div>
		</div>
</div>
<?php get_footer(); ?>