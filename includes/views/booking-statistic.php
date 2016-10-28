<?php

	/*
	* @package Inwave Booking
	* @version 1.0.0
	* @created 9/8/2015
	* @author Inwavethemes
	* @email inwavethemes@gmail.com
	* @website http://inwavethemes.com
	* @support Ticket https://inwave.ticksy.com/
	* @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
	* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	*
	*/

	/**
	 * Description of booking-statistic.php
	 *
	 * @developer duongca
	 */

	$service  = new iwBookingService();
	$customer = new iwBookingCustomer();
	$booking  = new iwBookingOrder();
	$discount = new iwBookingDiscount();
	$room     = new iwBookingRooms();
	$log      = new iwBookingLog();
?>
<div class="main-content">
	<div class="row">
		<div class="filter-control">
			<div class="field-control">
				<label for="filter-by"><?php _e('Filter By', IW_TEXT_DOMAIN); ?></label>
				<select name="filter-by" id="filter-by">
					<option value="day"><?php _e('Day', IW_TEXT_DOMAIN); ?></option>
					<option value="month"><?php _e('Month', IW_TEXT_DOMAIN); ?></option>
					<option value="year"><?php _e('Year', IW_TEXT_DOMAIN); ?></option>
				</select>
			</div>
			<div class="field-control day">
				<label for="day-number"><?php _e('Select day', IW_TEXT_DOMAIN); ?></label>
				<select name="day-number" id="day-number">
					<option value="5"><?php _e('Last 5 days', IW_TEXT_DOMAIN); ?></option>
					<option value="10"><?php _e('Last 10 days', IW_TEXT_DOMAIN); ?></option>
				</select>
			</div>
			<div class="field-control month">
				<label for="month-number"><?php _e('Select month', IW_TEXT_DOMAIN); ?></label>
				<select name="month-number" id="month-number">
					<option value="5"><?php _e('Last 5 months', IW_TEXT_DOMAIN); ?></option>
					<option value="10"><?php _e('Last 10 months', IW_TEXT_DOMAIN); ?></option>
				</select>
			</div>
			<div class="field-control year">
				<label for="year-number"><?php _e('Select year', IW_TEXT_DOMAIN); ?></label>
				<select name="year-number" id="year-number">
					<option value="5"><?php _e('Last 5 years', IW_TEXT_DOMAIN); ?></option>
					<option value="10"><?php _e('Last 10 years', IW_TEXT_DOMAIN); ?></option>
				</select>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="left">
			<div class="block-title">
				<?php _e('Statistic', IW_TEXT_DOMAIN); ?>
			</div>
			<div class="block-content">
				<div class="field-control">
					<label><?php _e('Orders', IW_TEXT_DOMAIN); ?></label>
					<?php
						echo $booking->getCountOrder();
					?>
				</div>
				<div class="field-control">
					<label><?php _e('Customers', IW_TEXT_DOMAIN); ?></label>
					<?php
						echo $customer->getCountCustomer();
					?>
				</div>
			</div>
		</div>
		<div class="right">
			<div class="block-title">
				<?php _e('New Content', IW_TEXT_DOMAIN); ?>
			</div>
			<div class="block-content">

			</div>
		</div>
	</div>
	<div class="row">
		<div class="new-message-notice">
			<?php
			$new_order = $booking->getNewOrders();
			if (!empty($new_order)) {
				foreach ($new_order as $order) {
					$member_info = unserialize($order->getMember()->getField_value());
					echo '<div><strong><a href="' . admin_url("edit.php?post_type=iw_booking&page=bookings/view&readed=1&id=" . $order->getId()) . '">' . $order->getOrderCode($order->getId()) . ' - ' . $member_info[0]['value'] . ' - ' . $order->getSum_price() . ' (' . $order->getCurrency() . ') </a ></strong ></div > ';
				}
			}
			?>
			<div class="new"
		</div>
	</div>
</div>
