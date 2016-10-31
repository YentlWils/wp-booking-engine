<?php
/*
 * @package Inwave Event
 * @version 1.0.0
 * @created May 15, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of payment
 *
 * @developer duongca
 */
if (isset($_SESSION['bt_message'])) {
	echo $_SESSION['bt_message'];
	unset($_SESSION['bt_message']);
}
?>
<div class="iwe-wrap wrap">
	<h2 class="in-title"><?php echo __('Logs', 'inwavethemes'); ?>
		<a class="bt-button add-new-h2" href="javascript:void(0);" onclick="document.getElementById('payment-form').submit();
                return false;"><?php echo __("Delete"); ?></a>
		<a class="bt-button add-new-h2"
		   href="<?php echo admin_url('admin-post.php?action=iwBookingClearLog'); ?>"><?php echo __("Clear Logs", 'inwavethemes'); ?></a>
	</h2>

	<form id="payment-form" action="<?php echo admin_url(); ?>admin-post.php" method="post">
		<input type="hidden" name="action" value="iwBookingDeleteLogs"/>
		<table class="iwbooking-list-table wp-list-table widefat fixed posts">
			<thead>
			<tr>
				<th class="manage-column column-cb check-column">
					<label class="screen-reader-text"
						   for="cb-select-all-1"><?php echo __('Select All', 'inwavethemes'); ?></label>
					<input id="cb-select-all-1" type="checkbox">
				</th>
				<th class="manage-column column-cb check-column">
				</th>
				<th class="manage-column column-description"><?php echo __('Message', 'inwavethemes'); ?></th>
				<th><?php echo __('Time', 'inwavethemes'); ?></th>
				<th><?php echo __('Link', 'inwavethemes'); ?></th>
			</tr>
			</thead>

			<tbody id="the-list">
			<?php
			if (!empty($logOnPage)) {
				foreach ($logOnPage as $log) {
					?>
					<tr>
						<th scope="row" class="check-column">
							<input id="cb-select-1" type="checkbox" name="fields[]"
								   value="<?php echo $log->getId(); ?>"/>

							<div class="locked-indicator"></div>
						</th>
						<th scope="row" class="check-column">

							<div class="locked-indicator"></div>
						</th>
						<td>
							<a href="<?php echo admin_url('edit.php?post_type=iw_booking&page=log/view&id=' . $log->getId()); ?>"
							   title="<?php echo __('View item', 'inwavethemes'); ?>">
								<strong><?php echo $log->getMessage(); ?></strong>
							</a>

							<div class="row-actions">
								<a href="<?php echo admin_url('edit.php?post_type=iw_booking&page=log/view&id=' . $log->getId()); ?>"
								   title="<?php echo __('View item', 'inwavethemes'); ?>"><?php echo __('View', 'inwavethemes'); ?></a>
								|
								<a class="submitdelete" title="<?php echo __('Delete log', 'inwavethemes'); ?>"
								   href="<?php echo admin_url("admin-post.php?action=iwBookingDeleteLog&id=" . $log->getId()); ?>"><?php echo __('Delete', 'inwavethemes'); ?></a>
							</div>
						</td>
						<td><?php echo date('m/d/Y H:i:s', $log->getTimestamp()); ?></td>
						<td><?php
							if ($log->getLink()) {
								$link = explode('|', $log->getLink());
								echo '<a href="' . $link[0] . '">' . $link[1] . '</a>';
							}
							?></td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan="5">
						<?php
						$page_list = $paging->pageList($_GET['pagenum'], $pages);
						echo $page_list;
						?>
					</td>
				</tr>
				<?php
			} else {
				?>
				<tr>
					<td colspan="5"><?php echo __('No result', IW_TEXT_DOMAIN); ?></td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
	</form>
</div>