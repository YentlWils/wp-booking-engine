<?php
if ($rooms->have_posts()) {

?>
<!--	<h2 class="title-room-available">--><?php //echo __('Room available for you', 'inwavethemes'); ?><!--</h2>-->

	<div class="iw-rooms-available">
	<?php
    $ultility =  new iwBookingUtility();
    while ($rooms->have_posts()) : $rooms->the_post();
        $room_obj = $room_class->getRoomInfo(get_the_ID());
        $temp_checkin = isset($query_params['checkin']) ? strtotime($query_params['checkin']) : $checkin;
        $temp_checkout = isset($query_params['checkout']) ? strtotime($query_params['checkout']) : $checkout;

        $temp_nights = floor(($temp_checkout - $temp_checkin)/(60*60*24));


        ?>
        <div class="iw-room">
            <div class="room-inner">
                <div class="row">
                    <div class="col-md-3">
                        <img src="<?php echo esc_url(inwave_resize($room_obj->image_feature, 140, 95)); ?>" alt="" />
                    </div>

                    <div class="col-md-5">

                        <div class="title-block-room">
                            <h3 class="title"><?php echo get_the_title(); ?></h3>
                            <div class="room-message">
                                <strong><?php echo __('Checkin:', 'monalisa') ?></strong>&nbsp;<?php echo date('d-m-Y', $temp_checkin); ?>
                            </div>
                            <div class="room-message">
                                <strong><?php echo __('Checkout:', 'monalisa') ?></strong>&nbsp;<?php echo date('d-m-Y', $temp_checkout); ?>
                            </div>
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
                    </div>
                    <div class="col-md-4">
                        <div class="unit-price">
                            <span class="unit-price-label"><?php echo __('Price', 'inwavethemes'); ?></span>
                            <span class="unit-price-value"><?php echo wp_kses_post($ultility->state_1_price($room_obj->price, $adult, $temp_nights)); ?></span>
                            <div class="unit-price-text"><small><?php echo sprintf(__('(for %d guests and %d nights)', 'inwavethemes'), $adult, $temp_nights);?></small></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9 col-md-offset-3">
                        <div class="panel panel-default">
                            <div class="panel-heading"><?php echo __('Available options'); ?></div>
                            <table class="table">
                                <?php
                                if (!empty($room_obj->premium_services)) {
                                    foreach ($room_obj->premium_services as $premium_service) {
                                        if ($premium_service->getId()) {
                                            ?>
                                                <tr>
                                                    <td class="col-md-1"><input type="checkbox" id="<?php echo $premium_service->getId(); ?>" name="premium_service[]" value="<?php echo $premium_service->getId(); ?>"></td>
                                                    <td class="col-md-8"><label class="service-item" for="<?php echo $premium_service->getId(); ?>"><?php echo $premium_service->getName(); ?></label></td>
                                                    <td class="col-md-3"><label class="service-item" for="<?php echo $premium_service->getId(); ?>">
                                                            <?php
                                                            echo $ultility->price($premium_service->getPrice(), $premium_service->getRate(), $premium_service->getIncluded(), $temp_nights);
                                                            ?>
                                                        </label></td>
                                                </tr>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="bcart-quantity">
                        <a href="#" class="add-bcart iwb-room-selection" data-roomid="<?php echo esc_attr($room_obj->ID); ?>">
                            <?php
                            echo '<span>' . esc_html__('Continue the reservation', 'monalisa') . '</span>';
                            ?>
                        </a>
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
            <?php echo esc_html__('The Villa is not available for this period', 'monalisa') ?>
        </div>
    </div>
    <?php
}
