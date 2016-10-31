<div class="booking-payment-page">
	<!--	<h2 class="title-room-available"><?php echo __( 'Make a reservation', 'inwavethemes' ); ?></h2>-->
	<form action="#">
		<div class="row">
			<div class="reservation-bar-title"><?php echo __( 'Registration', 'inwavethemes' ); ?></div>
		</div>
		<?php
		global $iwb_settings;
		$newfields = array();
		foreach ( $iwb_settings['register_form_fields'] as $field ) {
			if ( $field['group'] ) {
				$newfields[ $field['group'] ][] = $field;
			} else {
				$newfields['default'][] = $field;
			}
		}

		?>
		<?php foreach ( $newfields as $key => $fields ) : ?>
			<?php if ( count( $newfields ) > 1 ): ?>
				<div class="pay-form">
				<legend><?php echo esc_html( $key ); ?></legend>
			<?php endif; ?>
			<div class="row">
				<?php foreach ( $fields as $field ) :
					$value = isset( $contact_data[ $field['name'] ] ) ? $contact_data[ $field['name'] ] : $field['default_value'];
					echo '<div class="item-field col-md-6 col-sm-6 col-xs-12">';
					echo '<div class="item-field-inner">';
					?>

					<?php
					switch ( $field['type'] ):
						case 'select':
							echo '<select name=' . esc_attr( $field['name'] ) . '>';
							foreach ( $field['values'] as $option ) {
								echo '<option value="' . $option['value'] . '" ' . esc_attr( isset( $customer_data[ $field['name'] ] ) ? ( $option['value'] == $value ? 'selected="selected"' : '' ) : '' ) . '>' . $option['text'] . '</option>';
							}
							echo '</select>';
							echo '</div>';
							echo '</div>';
							break;
						case 'textarea':
							echo '<textarea placeholder="' . esc_attr( $field['label'] ) . '" name="' . esc_attr( $field['name'] ) . '">' . $value . '</textarea>';
							echo '</div>';
							echo '</div>';
							break;
						case 'email':
							echo '<input placeholder="' . esc_attr( $field['label'] ) . '" type="email" value="' . esc_attr( $value ) . '" name="' . esc_attr( $field['name'] ) . '"/>';
							echo '</div>';
							echo '</div>';
							break;

						default:
							echo '<input placeholder="' . esc_attr( $field['label'] ) . '" type="text" value="' . esc_attr( $value ) . '" name="' . esc_attr( $field['name'] ) . '"/>';
							echo '</div>';
							echo '</div>';
							break;
					endswitch;
					?>
				<?php endforeach; ?>
				<div class="item-field col-md-6 col-sm-6 col-xs-12">
					<div class="item-field-inner">
						<textarea name="note"
						          placeholder="<?php echo esc_html__( 'Order Notes', 'monalisa' ); ?>"></textarea>
					</div>
				</div>
				<input type="hidden" name="coupon_code" value="<?php echo esc_attr( $coupon_code ) ?>"/>
			</div>
			<?php if ( count( $newfields ) > 1 ): ?>
				</div><!-- end pay form -->
			<?php endif; ?>
		<?php endforeach; ?>
		<!-- Ask for guest names -->
		<?php if ( $booking_info_data['adult-number'][0] > 1 ): ?>
			<div class="row">
				<div class="reservation-bar-title"><?php echo __( 'Guests', 'inwavethemes' ); ?></div>
			</div>
			<?php
			echo '<div class="row">';
			for ( $i = 1; $i < array_sum($booking_info_data['adult-number']); $i ++ ) {
				$fieldName = 'guest_' . $i;
				$value     = isset( $contact_data[ $fieldName ] ) ? $contact_data[ $fieldName ] : "";
				echo '<div class="item-field col-md-6 col-sm-6 col-xs-12">';
				echo '<div class="item-field-inner">';
				echo '<input placeholder="' . esc_attr( __( 'Guest name', 'inwavethemes' ) ) . '" type="text" value="' . esc_attr( $value ) . '" name="guests[]"/>';
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
			?>
		<?php endif; ?>
		<div class="iwb-error-message"></div>
		<div class="payment-button-field">
			<div class="payment-method">
				<?php
				global $iwb_settings;
				$using_paypal = isset( $iwb_settings['iwb_payment']['paypal']['email'] ) ? true : false;
				if ( $using_paypal ) {
					?>
					<label class="payment-item"><input type="radio" name="payment_method"
					                                   value="direct" <?php echo $contact_data['payment_method'] == 'direct' ? 'checked' : ''; ?>> <?php esc_html_e( 'Payment directly at hotel', 'monalisa' ); ?>
					</label>
					<label class="payment-item"><input type="radio" name="payment_method"
					                                   value="full" <?php echo $contact_data['payment_method'] == 'full' ? 'checked' : ''; ?>> <?php esc_html_e( 'Payment online full price', 'monalisa' ); ?>
					</label>
					<label
						class="payment-item payment-deposit <?php ( isset( $booking_info_data['deposit_price'] ) && $booking_info_data['deposit_price'] ) ? '' : 'hidden'; ?>"><input
							type="radio" name="payment_method"
							value="deposit" <?php echo $contact_data['payment_method'] == 'full' ? 'deposit' : ''; ?>> <?php echo sprintf( wp_kses( __( 'Payment online deposit price <span class="booking-deposit-price">%s</span>', 'monalisa' ), inwave_allow_tags( 'span' ) ), iwBookingUtility::getMoneyFormated( $booking_info_data['deposit_price'] ) ); ?>
					</label>
				<?php } else { ?>
					<input type="hidden" name="payment_method" value="direct">
				<?php } ?>
			</div>
			<div class="submit-field">
				<button type="button"
				        class="reservation-process-from pay-button iwb-button theme-bg"><?php echo $paypal ? __( 'Pay now', 'monalisa' ) : __( 'Contact now', '' ); ?></button>
			</div>
		</div>
	</form>
</div>
