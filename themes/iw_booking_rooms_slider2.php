<?php
$theme_info = wp_get_theme();
$ultility = new iwBookingUtility();
wp_enqueue_style('slick');
wp_enqueue_style('slick-theme');
wp_enqueue_script('slick');
?>
<div class="iwb-rooms-slider2">
	<?php if ($sub_title || $title){ ?>
		<div class="title-block">
			<?php if ($sub_title){ ?>
				<div class="sub-title"><?php echo wp_kses_post($sub_title); ?></div>
			<?php } ?>
			<?php if ($title){ ?>
				<h3 class="title"><?php echo wp_kses_post($title) ?></h3>
			<?php } ?>
		</div>
	<?php } ?>
	
	<div class="slider-main">
		<div class="slider-content">
			<?php
			while ($query->have_posts()) : $query->the_post();
				$room = new iwBookingRooms();
				$roomInfo = $room->getRoomInfo(get_the_ID());
				$price = $roomInfo->price;
			?>
				<div class="slider-item">
					<div class="slider-item-inner">
						<div class="image">
							<?php $large_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
							?>
							<img src="<?php echo esc_url(inwave_resize($large_image_url[0], 970, 615, true)); ?>" alt=""/>
						</div>
						<h3 class="post-title"><a href="<?php echo esc_url(get_permalink()); ?>" title=""><?php the_title(); ?></a></h3>
					</div>
				</div>
			<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</div>
