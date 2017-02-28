<div class="reservation-completed">
<!--	<h2 class="title-room-available"><?php echo __('Reservation Completed!', 'inwavethemes'); ?></h2>-->
	<?php global $iwb_settings; ?>
	<div class="reservation-completed-content">
		<h3 class="title"><?php echo __('Reservation Completed!', 'inwavethemes'); ?></h3>
		<p><?php echo sprintf(__('Your reservation details have just been sent to %s. If you have any question, please do not hesitate to contact us. Thank you!', 'inwavethemes'), $booking->getMember()->email); ?></p>
		<div class="contact">
			<span><?php echo isset($iwb_settings['general']['reservation_completed_contact_phone']) ? stripslashes($iwb_settings['general']['reservation_completed_contact_phone']) : ''; ?></span>
			<span><?php echo isset($iwb_settings['general']['reservation_completed_contact_email']) ? stripslashes($iwb_settings['general']['reservation_completed_contact_email']) : ''; ?></span>
		</div>
	</div>
</div>
</div>