<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Comment
 * Handle comment (reviews and order notes).
 *
 */
class iwBookingComment {

	/**
	 * Hook in methods.
	 */
	public static function init() {

		add_action( 'transition_comment_status', array( __CLASS__, 'comment_change_status' ) , 10, 3);

		//add_action( 'wp_update_comment_count', array( __CLASS__, 'clear_transients' ) );

		// Add fields after default fields above the comment box, always visible
		add_action( 'comment_form_top', array( __CLASS__, 'custom_fields' ) );

		// Save the comment meta data along with comment
		add_action( 'comment_post', array( __CLASS__, 'save_comment_meta_data' ));

		// Add the filter to check if the comment meta data has been filled or not
		add_filter( 'preprocess_comment', array( __CLASS__, 'verify_comment_meta_data'));

		//Add an edit option in comment edit screen
		add_action( 'add_meta_boxes_comment', array( __CLASS__, 'extend_comment_add_meta_box'));

		// Update comment meta data from comment edit screen
		add_action( 'edit_comment', array( __CLASS__, 'extend_comment_edit_metafields'));

		// Add the comment meta (saved earlier) to the comment text
		// You can also output the comment meta values directly in comments template
		//add_filter( 'comment_text', array( __CLASS__, 'modify_comment'));
	}

	/**
	 * Ensure product average rating and review count is kept up to date.
	 * @param int $post_id
	 */
	public static function clear_transients( $post_id ) {
		delete_post_meta( $post_id, 'iwbooking_average_rating' );
		delete_post_meta( $post_id, 'iwbooking_rating_count' );
		delete_post_meta( $post_id, 'iwbooking_review_count' );
		iwBookingRooms::sync_average_rating( $post_id );
	}

	public static function comment_change_status( $new_status, $old_status, $comment  ) {
		if(get_post_type($comment->comment_post_ID) == 'iw_booking' && ($new_status === 'approved' || $old_status === 'approved')){
			self::clear_transients($comment->comment_post_ID);
		}
	}

	public static function custom_fields () {
		global $post;
		$html = '';
		if('iw_booking' === get_post_type( $post)){
			wp_enqueue_style('jquery-rating');
			wp_enqueue_script('jquery-rating');
			$html .= '<p class="comment-form-rating">'.
				'<label for="rating">'. __('Tap a star to rate ', 'inwavethemes') . '</label>
				<span class="commentratingbox">';
				for( $i=1; $i <= 5; $i++ ){
					$html .= '<input type="radio" name="rating" id="rating" class="rating" value="'. $i .'"/>';
				}
			$html .= '</span></p>';
		}

		echo $html;
	}

	public static function save_comment_meta_data( $comment_id ) {

		if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != '') && 'iw_booking' === get_post_type( $_POST['comment_post_ID']) && (!isset($_POST['comment_parent']) || $_POST['comment_parent'] == 0)){
			$rating = wp_filter_nohtml_kses($_POST['rating']);
			add_comment_meta( $comment_id, 'rating', $rating );
			if(wp_get_comment_status($comment_id) == 'approved'){
				self::clear_transients($_POST['comment_post_ID']);
			}
		}
	}

	public static function verify_comment_meta_data( $commentdata ) {
		if (
			! is_admin()
			&& 'tour' === get_post_type( $_POST['comment_post_ID'] )
			&& empty( $_POST['rating'] )
			&& '' === $commentdata['comment_type']
		) {
			wp_die( __( 'Please rate the room.', 'inwavethemes' ) );
			exit;
		}

		return $commentdata;
	}

	public static function extend_comment_add_meta_box() {
		add_meta_box( 'title', __( 'Room Rating', 'inwavethemes'), array(__CLASS__, 'extend_comment_meta_box'), 'comment', 'normal', 'high' );
	}

	public static function extend_comment_meta_box ( $comment ) {
		$rating = get_comment_meta( $comment->comment_ID, 'rating', true );
		wp_nonce_field( 'extend_comment_update', 'extend_comment_update', false );
		?>
		<p>
			<label for="rating"><?php _e( 'Rating: ' , 'inwavethemes'); ?></label>
			<span class="commentratingbox">
			<?php for( $i=1; $i <= 5; $i++ ) {
				echo '<span class="commentrating"><input type="radio" name="rating" id="rating" value="'. $i .'"';
				if ( $rating == $i ) echo ' checked="checked"';
				echo ' />'. $i .' </span>';
			}
			?>
			</span>
		</p>
		<?php
	}

	public static function extend_comment_edit_metafields( $comment_id ) {
		if( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) return;

		if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != '') ):

			if('iw_booking' == get_post_type( $_POST['comment_post_ID']) && (!isset($_POST['comment_parent']) || $_POST['comment_parent'] == 0)):

				$rating = wp_filter_nohtml_kses($_POST['rating']);
				$old_rating = get_comment_meta($comment_id, 'rating', true);
				update_comment_meta($comment_id, 'rating', $rating);
				if($old_rating != $rating && wp_get_comment_status($comment_id) == 'approved'){
					self::clear_transients($_POST['comment_post_ID']);
				}
			endif;
		else :
			delete_comment_meta( $comment_id, 'rating');
		endif;
	}

	public static function modify_comment( $text ){
		$plugin_url_path = WP_PLUGIN_URL;

		if( $commentrating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
			$commentrating = '<p class="comment-rating">Rating: <strong>'. $commentrating .' / 5</strong></p>';
			$text = $text . $commentrating;
			return $text;
		} else {
			return $text;
		}
	}
}

iwBookingComment::init();
