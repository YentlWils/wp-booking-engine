<div class="reservation-price-wrap">
    <div class="price-room-summary">
        <?php
        $room_class = new IwBookingRooms();
        foreach ($page_price_data['rooms'] as $i=>$room_data){
            $room_obj = $room_class->getRoomInfo($room_data['room_id']);
            echo '<div class="reservation-room" style="margin-bottom: 0px; padding-bottom: 0px;">';

                echo '<h3>'.__('Reservation details', 'monalisa').'</h3>';
                echo '<div class="room-people">
                        <div><strong>'.__('Check-in:', 'monalisa').'</strong> '.date('d-m-Y', strtotime($checkin)).'</div>
                    </div>';
                echo '<div class="room-people">
                        <div><strong>'.__('Check-out:', 'monalisa').'</strong> '.date('d-m-Y', strtotime($checkout)).'</div>
                    </div>';
                echo '<div class="room-people">
                        <div><strong>'.__('Guests:', 'monalisa').'</strong> '.$room_data['adult'].'</div>
                    </div><br/>';

//                echo '<h3>'.__('Guests', 'monalisa').'</h3>';
//                echo '<div class="room-people">
//                    <span>'.sprintf(__('Adult : %d', 'monalisa'), $room_data['adult']).'</span>,&nbsp;
//                    <span>'.sprintf(__('Children : %d', 'monalisa'), $room_data['children']).'</span>
//                </div>';

                echo '<hr/>';
                echo '<h3 class="price-room-title"><span>'.__('Basic price', 'monalisa').'</span><span class="room-price price">'.iwBookingUtility::getMoneyFormated($room_data['price'], $page_price_data['currency']).'</span></h3>';

                if($room_data['services']){
					$room_data_services_html = array();
                    echo '<div class="price-room-service room-people">';
                    foreach ($room_data['services'] as $service){
                        echo '<div class="item"><span>'.$service['title'].'</span>'.($service['price'] == 0 ? __('<span class="price-free price">Free</span>', 'inwavethemes') : '<span class="price">'.iwBookingUtility::getMoneyFormated($service['price'], $page_price_data['currency'])).'</span></div>';
                    }
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