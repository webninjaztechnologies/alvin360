<div class="uap-wrapper">
  <div class="uap-page-title"><?php esc_html_e( 'Manage Payouts', 'uap');?></div>
  <div class="uap-page-top-options">
  		<a href="<?php echo admin_url( 'admin.php?page=ultimate_affiliates_pro&tab=payments&subtab=new_payout' );?>" class="uap-add-new-like-wp"><i class="fa-uap fa-add-uap"></i><?php esc_html_e( 'Pay Affiliates', 'uap');?></a>
  		<span class="uap-top-message"><?php esc_html_e('...distribute earnings to your Affiliates', 'uap');?></span>
  </div>

  <?php if ( !empty($data['response']) ):?>
      <?php if ( $data['response']['status'] === 0 ):?>
          <div class="uap-danger-box" ><?php echo $data['response']['message'];?></div>
      <?php elseif ( $data['response']['status'] === -1 ): ?>
          <div class="uap-warning-box" ><?php echo $data['response']['message'];?></div>
      <?php elseif ( $data['response']['status'] === 1 ):?>
          <div class="uap-success-box" ><?php echo $data['response']['message'];?></div>
      <?php endif;?>
  <?php endif;?>

  <div class="">

    <!-- Start DataTable -->
 		<?php
 		// 1. Datatable - define table name. used in js.
 		$tableDataType = 'payouts';

 		// 2. Datatable - define columns
 		$columns = [
 									[
 												'data'				=> 'checkbox',
 												'title'				=> '<input type=checkbox class=uap-js-select-all-checkboxes />',
 												'orderable'		=> false,
 												'sortable'		=> false,
 									],
 									[
 												'data' 				=> 'id',
 												'title'				=> esc_html__('ID', 'uap'),
 												'orderable'   => true,
 												'sortable'		=> true,
 												'className'		=> 'uap-max-width-100',
 												'render'  		=> [
 																					'display'   => 'display',
 																					'sort'      => 'value',
 												]
 									],
 									[
 												'data' 				=> 'date_range',
 												'title'				=> esc_html__('Data Range', 'uap'),
 												'orderable'   => false,
 												'sortable'		=> false,
 												'className'		=> 'uap-max-width-250',
 									],
 									[
 												'data' 				=> 'method',
 												'title'				=> esc_html__('Payout Method', 'uap'),
 												'orderable'   => false,
 												'sortable'		=> false,
 												'className'		=> 'uap-max-width-250',
 									],
 									[
 												'data' 				=> 'amount',
 												'title'				=> esc_html__('Amount', 'uap'),
 												'orderable'   => true,
 												'sortable'		=> true,
 												'className'		=> 'uap-max-width-150',
 												'render'  		=> [
 																					'display'   => 'display',
 																					'sort'      => 'value',
 												]
 									],
 									[
 												'data' 				=> 'payment',
 												'title'				=> esc_html__('Payments', 'uap'),
 												'orderable'   => false,
 												'sortable'		=> false,
 												'className'		=> 'uap-max-width-100',
 												'render'  		=> [
 																					'display'   => 'display',
 																					'sort'      => 'value',
 												]
 									],
 									[
 												'data' 				=> 'progress',
 												'title'				=> esc_html__('Progress', 'uap'),
 												'orderable'   => false,
 												'sortable'		=> false,
 												'className'		=> 'uap-max-width-250',
 												'render'  		=> [
 																					'display'   => 'display',
 																					'sort'      => 'value',
 												]
 									],
 									[
 												'data' 				=> 'created_time',
 												'title'				=> esc_html__('Created Time', 'uap'),
 												'orderable'   => true,
 												'sortable'		=> true,
 												'className'		=> 'uap-max-width-250',
 												'render'  		=> [
 																					'display'   => 'display',
 																					'sort'      => 'value',
 												]
 									],
 									[
 												'data' 				=> 'status',
 												'title'				=> esc_html__('Status', 'uap'),
 												'orderable'   => false,
 												'sortable'		=> false,
 												'className'		=> 'uap-max-width-150',
 												'render'  		=> [
 																					'display'   => 'display',
 																					'sort'      => 'value',
 												]
 									],
 									[
 												'data' 				=> 'actions',
 												'title'				=> esc_html__('Actions', 'uap'),
 												'orderable'   => false,
 												'sortable'		=> false,
 												'className'		=> 'uap-max-width-150',
 									]
 		];
 		// End of 2. Datatable - define columns


 		// 3. Datatable - Js and CSS for datatable
 		\Indeed\Uap\Admin\DataTable::Scripts( $columns, $tableDataType );

 		?>

 		<!-- 4. Datatable - Js confirm messages -->
 		<div class="uap-js-messages-for-datatable"
 				data-remove_one_item="<?php esc_html_e('Are you sure you want to remove this payout?', 'uap');?>"
 				data-remove_many_items="<?php esc_html_e('Are you sure you want to remove selected payouts?', 'uap');?>" ></div>
 		<!-- End of 4. Datatable - Js confirm messages -->

 				<!-- 5. Datatable - Custom Search + Filter -->
 				<div class="uap-datatable-filters-wrapper">
 								<input type="text" value="" placeholder="<?php esc_html_e("Search Payouts", 'uap');?>" class="uap-js-search-phrase uap-max-width-300">

 								<!--label class="uap-label"><?php esc_html_e('Start:', 'uap');?></label-->
 								<input type="text" name="udf" value="" class="uap-general-date-filter uap-no-margin-right" placeholder="From - yyyy-mm-dd"/>
 								<!--label class="uap-label"><?php esc_html_e('Until:', 'uap');?></label--><span class="uap-date-line">-</span>
 								<input type="text" name="udu" value="" class="uap-general-date-filter" placeholder="To - yyyy-mm-dd"/>

 								<div class="uap-datatable-multiselect-wrapp uap-filter-status-select">
 									<select name="status_in[]" class="uap-js-datatable-items-status-types " multiple data-placeholder="<?php esc_html_e("Status", 'uap');?>">
 											<option value="1"><?php esc_html_e( 'Processing', 'uap' );?></option>
 											<option value="2"><?php esc_html_e( 'Completed', 'uap' );?></option>
 									</select>
 								</div>

 								<button class="uap-datatable-filter-bttn"><?php esc_html_e('Filter', 'uap');?></button>
 				</div>
 				<!-- End of 5. Datatable - Custom Search + Filter -->

 				<!-- 6. Datatable - the table html -->
 				<table id="uap-dashboard-table" class="display uap-dashboard-table" >
 				</table>
 				<!-- End of 6. Datatable - the table html -->

 				<!-- 7. Datatable - Bulk actions -->
 				<div class="uap-datatable-actions-wrapp-copy uap-display-none">
 						<select name="uap-action" class="uap-datatable-select-field uap-js-bulk-action-select">
 								<option value="" disabled selected ><?php esc_html_e( 'Bulk Actions', 'uap' );?></option>
 								<option value="remove"><?php esc_html_e('Remove', 'uap');?></option>
 						</select>
 						<input type="submit" name="uap-datatable-submit" value="<?php esc_html_e('Apply', 'uap');?>" class="button button-primary button-small uap-js-items-apply-bttn" />
 				</div>
 				<!-- End of 7. Datatable - Bulk actions -->

 				<!-- 8. Page State -->
 				<?php $pageState = get_option( 'uap_datatable_state_for-payouts', false );?>
 				<?php if ( $pageState !== false && !empty( $pageState )  ):?>
 						<div class="uap-js-datatable-state" data-value='<?php echo stripslashes( $pageState );?>' ></div>
 				<?php endif;?>
 				<!-- End of 8. Page State -->

 				<div class="uap-js-datatable-listing-delete-nonce" data-value="<?php echo wp_create_nonce( 'uap_admin_forms_nonce' );?>"></div>

  </div>

</div>
