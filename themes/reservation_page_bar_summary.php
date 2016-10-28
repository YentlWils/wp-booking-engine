<div class="reservation-price-wrap">
    <div class="price-room-summary">
        <?php
        $room_class = new IwBookingRooms();
        foreach ($page_price_data['rooms'] as $i=>$room_data){
            $room_obj = $room_class->getRoomInfo($room_data['room_id']);
            echo '<div class="price-room">';
                echo '<h3 class="price-room-title"><span>'.sprintf(__('Room %d : ', 'monalisa'), $i +1 ).$room_obj->post_title.'</span><span class="room-price price">'.iwBookingUtility::getMoneyFormated($room_data['price'], $page_price_data['currency']).'</span></h3>';
                echo '<div class="price-room-meta">
                    <span>'.sprintf(__('Adult : %d', 'monalisa'), $room_data['adult']).'</span>
                    <span>'.sprintf(__('Children : %d', 'monalisa'), $room_data['children']).'</span>
                </div>';
                if($room_data['services']){
					$room_data_services_html = array();
                    echo '<div class="price-room-service">';
                    echo '<span class="room-service-title">'.__('Services : ', 'inwavethemes').'</span>';
					echo '<div class="room-services">';
                    foreach ($room_data['services'] as $service){
                        $room_data_services_html[] = '<span class="service-item"><span>'.$service['title'].'</span>'.($service['price'] == 0 ? __('<span class="price-free price">Free</span>', 'inwavethemes') : '<span class="price">'.iwBookingUtility::getMoneyFormated($service['price'], $page_price_data['currency'])).'</span></span>';
                    }
					echo implode('<br />', $room_data_services_html);
					echo '</div>';
                    echo '</div>';
                }
            echo '</div>';
        }
        ?>
    </div>
    <div class="price-summary-discount-tax">
        <div class="price-summary-discount-tax-total">
            <span><?php echo __('Total', 'monalisa'); ?></span>
            <span class="price"><?php echo iwBookingUtility::getMoneyFormated($page_price_data['total_price'], $page_price_data['currency']) ?></span>
        </div>
        <?php
        if($page_price_data['discount_price'] && $page_price_data['discount_price'] > 0){
            $discount = new iwBookingDiscount($page_price_data['discount_id']);
            ?>
            <div class="price-summary-discount">
                <?php if($discount->id && $discount->type == 'percent'){ ?>
                <span><?php echo sprintf(__('Discount %s%%', 'monalisa'), $discount->value); ?></span>
                <?php }else{ ?>
                <span><?php echo __('Discount price', 'monalisa'); ?></span>
                <?php } ?>
                <span class="price"><?php echo iwBookingUtility::getMoneyFormated($page_price_data['discount_price'], $page_price_data['currency']) ?></span>
            </div>
            <?php
        }
        ?>
        <?php
        if($page_price_data['tax_price'] && $page_price_data['tax_price'] > 0){
            ?>
            <div class="price-summary-tax">
                <span><?php echo sprintf(__('Tax %d%%', 'monalisa'), $page_price_data['tax']); ?></span>
                <span class="price"><?php echo iwBookingUtility::getMoneyFormated($page_price_data['tax_price'], $page_price_data['currency']) ?></span>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="price-summary-grand-total">
        <span><?php echo __('Grand Total', 'monalisa'); ?></span>
        <span class="price"><?php echo iwBookingUtility::getMoneyFormated($page_price_data['price'], $page_price_data['currency']) ?></span>
    </div>
    <?php
        if(isset($return['state']) && $return['state'] == 3){
            ?>
            <a href="#" class="edit-booking theme-bg" id="iwb-edit-booking-button"><?php echo __('Edit booking', 'inwavethemes'); ?></a>
            <?php
        }
    ?>
</div>