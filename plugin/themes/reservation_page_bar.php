<?php
$has_active_class = false;
if($query_params['room-id']){
    foreach ($query_params['room-id'] as $i => $room_id){
        if($room_id){
            $roomObj = $room_class->getRoomInfo($room_id);
            if($roomObj){
                ?>
                <div class="reservation-room">
<!--                    <h3>--><?php //echo sprintf(__('Guests: %d', 'inwavethemes'), $query_params['adult-number'][$i]); ?><!--</h3>-->
<!--                    <div class="room-people">-->
<!--                        <span>--><?php //echo sprintf(__('Adult: %d', 'inwavethemes'), ($query_params['adult-number'][$i])); ?><!--</span>,&nbsp;-->
<!--                        <span>--><?php //echo sprintf(__('Children: %d', 'inwavethemes'), ($query_params['children-number'][$i])); ?><!--</span>-->
<!--                    </div>-->
                    <input type="hidden" name="room-id[]" value="<?php echo $roomObj->ID; ?>"/>
<!--                    <hr/>-->
                    <h3><?php echo __('Booking options', 'inwavethemes'); ?> <a href="#" class="change-room"><?php echo __('Change options', 'inwavethemes'); ?></a></h3>
                    <input type="hidden" name="room-service[]" value="<?php echo $query_params['room-service'][$i]; ?>"/>
                    <?php
                    if($query_params['room-service'][$i]){
                        $services = explode(',', $query_params['room-service'][$i]);
                        $services_title = array();
                        foreach ($services as $room_service){
                            if(isset($roomObj->premium_services[$room_service])){
                                $services_title[] = $roomObj->premium_services[$room_service]->getName();
                            }
                        }
                        if($services_title){
                            foreach ($services_title as $service){
                                echo '<div class="room-service">';
                                echo '<span>'.$service.'</span>';
                                echo '</div>';
                            }

                        }else{
                            echo '<div class="room-service">';
                            echo '<span><i>'.__('No options selected', 'inwavethemes').'</i></span>';
                            echo '</div>';
                        }
                    }else{
                        echo '<div class="room-service">';
                        echo '<span><i>'.__('No options selected', 'inwavethemes').'</i></span>';
                        echo '</div>';
                    }
                    ?>
                   <!-- <a href="#" class="change-room"><?php echo __('Change room', 'inwavethemes'); ?></a>-->
                </div>
                <?php
            }
        }
        else{
            ?>
            <div class="reservation-room <?php echo !$has_active_class ? 'iwb-active' : ''; ?> hidden">
                <h3><?php echo __('Guests', 'inwavethemes'); ?></h3>
                <div class="room-people">
                    <span><?php echo sprintf(__('Adult: %d', 'inwavethemes'), ($query_params['adult-number'][$i])); ?></span>,&nbsp;
                    <span><?php echo sprintf(__('Children: %d', 'inwavethemes'), ($query_params['children-number'][$i])); ?></span>
                </div>
                <input type="hidden" name="room-id[]" value=""/>
                <input type="hidden" name="room-service[]" value="" class="service-id-value"/>
            </div>
            <?php
            $has_active_class = true;
            $current_room_id = $i;
        }
    }
}
if(!$has_active_class && $current_room_id){
?>
    <div class="reservation-room <?php echo !$has_active_class ? 'iwb-active' : ''; ?> hidden">
        <h3><?php echo __('Guests', 'inwavethemes'); ?></h3>
        <div class="room-people">
            <span><?php echo sprintf(__('Adult: %d', 'inwavethemes'), ($query_params['adult-number'][$current_room_id - 1])); ?></span>,&nbsp;
            <span><?php echo sprintf(__('Children: %d', 'inwavethemes'), ($query_params['children-number'][$current_room_id - 1])); ?></span>
        </div>
        <input type="hidden" name="room-id[]" value=""/>
        <input type="hidden" name="room-service[]" value="" class="service-id-value"/>
    </div>
<?php
}
?>