<?php
/**
 * Function ajax for backend
 * @version   4.6.4
 */
class EVO_admin_ajax{
	public $helper, $post_data;
	
	public function __construct(){

		$ajax_events = array(
			'get_shortcode_generator'=>'get_shortcode_generator',	

			'deactivate_product'	=>'deactivate_product',	
			'validate_license'		=>'validate_license',					
			'revalidate_license'	=>'revalidate_license',					
			'export_events'			=>'export_events',					
			'get_addons_list'		=>'get_addons_list',

			'export_settings'		=>'export_settings',
			'get_import_settings'	=>'get_import_settings',
			'import_settings'		=>'import_settings',

			'admin_test_email'		=>'admin_test_email',
			'admin_get_environment'		=>'admin_get_environment',
			'admin_system_log'		=>'admin_system_log',
			'admin_system_log_flush'		=>'admin_system_log_flush',
			'admin_get_views'		=>'admin_get_views',
			'rel_event_list'		=>'rel_event_list',
			'get_latlng'				=>'get_latlng',

			'config_virtual_event'	=>'config_virtual_event',
			'select_virtual_moderator'	=>'select_virtual_moderator',
			'get_virtual_users'	=>'get_virtual_users',
			'save_virtual_mod_settings'	=>'save_virtual_mod_settings',
			'save_virtual_event_settings'	=>'save_virtual_event_settings',


			'eventedit_onload'	=>'evo_eventedit_onload',
			'eventedit_settings'	=>'evo_eventedit_settings', // 4.6

			// event card designer
			'load_ecard_designer'	=> 'load_ecard_designer', // 4.6
			'save_eventcard_designer'	=> 'save_eventcard_designer', // 4.6
		);
		foreach ( $ajax_events as $ajax_event => $class ) {

			$prepend = 'eventon_';
			add_action( 'wp_ajax_'. $prepend . $ajax_event, array( $this, $class ) );
			add_action( 'wp_ajax_nopriv_'. $prepend . $ajax_event, array( $this, $class ) );
		}

		add_action('wp_ajax_eventon-feature-event', array($this, 'eventon_feature_event'));

		$this->helper = new evo_helper();
		$this->post_data = $this->helper->sanitize_array( $_POST );

		// rest based functions
		//add_filter('evo_ajax_rest_eventon_eventedit_onload', array($this, 'evo_eventedit_onload'),10, 2);
	}

	// shortcode generator
		function get_shortcode_generator(){
			$sc = isset($this->post_data['sc']) ? stripslashes( $this->post_data['sc'] ): 'add_eventon';

			$content = EVO()->shortcode_gen->get_content();	

			echo json_encode(array(
				'status'=>'good',
				'content'=> $content,
				'sc'=> $sc,
				'type'=> isset($this->post_data['type']) ? $this->post_data['type']:'',
				'other_id'=> isset($this->post_data['other_id']) ? $this->post_data['other_id']:'',
			));exit;	
		}

	// on event edit page load
		function evo_eventedit_onload(){

			$EVENT = new EVO_Event($this->post_data['eid']);

			$id = isset( $this->post_data['id'] ) ? $this->post_data['id'] : false;

			$content_array = apply_filters('evo_eventedit_pageload_data',array(), $this->post_data, $EVENT, $id);
			$dom_id_array = apply_filters('evo_eventedit_pageload_dom_ids',array(), $this->post_data, $EVENT, $id);

			$response = array(
				'status'=>'good',
				'content_array'=> $content_array,
				'dom_ids'=> $dom_id_array
			);

			wp_send_json($response); wp_die();
		}

	// open event edit settings as lightbox
		function evo_eventedit_settings(){
			$EVENT = new EVO_Event($this->post_data['event_id']);

			ob_start();

			echo "<div class='evodfx evofx_dr_r'>";
			echo "<div class='evodfx evofx_dr_c' style='width:150px;'>
			<p>Subtitle</p>
			<p>Status</p>
			<p>Attendance</p>
			</div>
			<div class='evofx_1_1 evobr15' style='border:1px solid var(--evo_color_1); overflow:hidden;'>";
			include_once('post_types/class-meta_box_all.php');
			echo "</div></div>";

			$response = array(
				'status'=>'good',
				'content'=>  ob_get_clean(),
			);


			wp_send_json($response); wp_die();
		}

	// virtual events
		public function config_virtual_event(){

			// validate if user has permission
			if( !current_user_can('edit_eventons') ){
				wp_send_json(array(
					'status'=>'bad','msg'=> __('You do not have proper permission to access this','eventon')
				));	wp_die();
			}

			$post_data = $this->helper->sanitize_array( $_POST);

			$EVENT = new EVO_Event( $post_data['eid'] );

			ob_start();

			include_once('views/virtual_event_settings.php');

			wp_send_json(array(
				'status'=>'good','content'=> ob_get_clean()
			)); wp_die();
		}
		public function select_virtual_moderator(){
			
			ob_start();

			$eid = (int) $_POST['eid'];

			$EVENT = new EVO_Event( $eid);
			
			$set_user_role = $EVENT->get_prop('_evo_user_role');
			$set_mod = $EVENT->get_prop('_mod');

			global $wp_roles;
			?>
			<div style="padding:20px">
				<form class='evo_vir_select_mod'>
					<input type="hidden" name="action" value='eventon_save_virtual_mod_settings'>
					<input type="hidden" name="eid" value='<?php echo esc_attr($eid);?>'>

					<?php wp_nonce_field( 'evo_save_virtual_mod_settings', 'evo_noncename' );?>
					
					<p class='row'>
						<label><?php _e('Select a user role to find users');?></label>
						<select class='evo_select_more_field evo_virtual_moderator_role' name='_user_role' data-eid='<?php echo $eid;?>'>
							<option value=''> -- </option>
							<?php 
							
							foreach($wp_roles->roles as $role_slug=>$rr){
								$select = $set_user_role == $role_slug ? 'selected="selected"' :'';
								echo "<option value='". $role_slug. "' {$select}>". $rr['name'] .'</option>';
							}

						?></select>
					</p>
					<p class='row evo_select_more_field_2'>
						<label><?php _e('Select a user for above role');?></label>
						<select name='_mod' class='evo_virtual_moderator_users'>
							<?php
							if( $set_user_role ):
								echo $this->get_virtual_users_select_options($set_user_role, $set_mod );
							else:
							?>
								<option value=''>--</option>
							<?php endif;?>
						</select>
					</p>
					<p class='evo_save_changes' ><span class='evo_btn save_virtual_event_mod_config ' data-eid='<?php echo esc_attr($eid);?>' style='margin-right: 10px'><?php _e('Save Changes','eventon');?></span></p>
				</form>
			</div>

			<?php

			wp_send_json(array(
				'status'=>'good','content'=> ob_get_clean()
			));wp_die();
		}
		public function get_virtual_users_select_options($role_slug, $set_user_id=''){
			
			$users = get_users( array( 
				'role' => $role_slug,
				'fields'=> array('ID','user_email', 'display_name') 
			) );
			$output = false;
			
			if($users){
				foreach($users as $user){
					$select = ( !empty($set_user_id) && $set_user_id == $user->ID) ? "selected='selected'":'';
					$output .= "<option value='{$user->ID}' {$select}>{$user->display_name} ({$user->user_email})</option>";
				}
			}
			return $output;
		}
		public function get_virtual_users(){

			// validate if user has permission
			if( !current_user_can('edit_eventons') ){
				wp_send_json(array(
					'status'=>'bad','msg'=> __('You do not have proper permission to access this','eventon')
				));wp_die();
			}

			$user_role = sanitize_text_field( $_POST['_user_role']);

			wp_send_json(array(
				'status'=>'good',
				'content'=> empty($user_role) ? 
					"<option value=''>--</option>" : 
					$this->get_virtual_users_select_options($user_role)
			)); wp_die();
		}

		// @updated 4.5.2
		public function save_virtual_event_settings(){

			// validate if user has permission
			if( !current_user_can('edit_eventons') ){
				wp_send_json(array(
					'status'=>'bad','msg'=> __('You do not have proper permission to access this','eventon')
				));
				wp_die();
			}
			
			// nonce validation
			if( empty($_POST['evo_noncename']) || !wp_verify_nonce( $_POST['evo_noncename'], 'evo_save_virtual_event_settings' ) ){
				wp_send_json(array(
					'status'=>'bad','msg'=> __('Nonce validation failed','eventon')
				));	wp_die();
			}

			$post_data = $this->helper->sanitize_array( $_POST);

			$EVENT = new EVO_Event( $post_data['event_id']);


			foreach($post_data as $key=>$val){

				if( in_array($key, array( '_vir_url'))){
					$val = $post_data[$key];
				}

				// html content
				if( in_array($key, array( '_vir_after_content','_vir_pre_content','_vir_embed'))){
					$val = $this->helper->sanitize_html( $_POST[ $key ] );
				}

				$EVENT->save_meta($key, $val);
			}

			wp_send_json(array(
				'status'=>'good','msg'=> __('Virtual Event Data Saved Successfully','eventon')
			)); wp_die();
		}

		public function save_virtual_mod_settings(){

			// validate if user has permission
			if( !current_user_can('edit_eventons') ){
				wp_send_json(array(
					'status'=>'bad','msg'=> __('You do not have proper permission to access this','eventon')
				));	wp_die();
			}			

			// nonce validation
			if( empty( $_POST['evo_noncename'] ) || !wp_verify_nonce( wp_unslash( $_POST['evo_noncename'] ), 'evo_save_virtual_mod_settings' ) ){
				wp_send_json(array(
					'status'=>'bad','msg'=> __('Nonce validation failed','eventon')
				));	wp_die();
			}	

			$post_data = $this->helper->sanitize_array( $_POST);

			$EVENT = new EVO_Event( (int)$post_data['eid']);

			$EVENT->save_meta('_evo_user_role', $post_data['_user_role']);
			$EVENT->save_meta('_mod', $post_data['_mod']);

			wp_send_json(array(
				'status'=>'good','msg'=> __('Moderator Data Saved Successfully','eventon')
			)); wp_die();
			
		}

	// Related Events @4.5.9
		function rel_event_list(){

			// Check User Caps.
			if ( ! current_user_can( 'edit_eventons' ) ) {
				wp_send_json_error( 'missing_capabilities' );
				wp_die();
			}

			$post_data = $this->helper->sanitize_array( $_POST);


			$event_id = (int)$post_data['eventid'];
			$EVs = json_decode( stripslashes($post_data['EVs']), true );

			$wp_args = array(
				'posts_per_page'=>-1,
				'post_type'=>'ajde_events',
				'exclude'=> $event_id,
				'post_status'=>'publish'
			);
			$events = new WP_Query($wp_args );

			
			$content = '';

			$content .= "<div class='evo_rel_events_form' data-eventid='{$event_id}'>";

			$ev_count = 0;

			// each event
			if($events->have_posts()){	
				
					
				$events_list = array();

				foreach( $events->posts as $post ) {		

					$event_id = $post->ID;
					$EV = new EVO_Event($event_id);

					$time = $EV->get_formatted_smart_time();

					ob_start();
					?><span class='rel_event<?php echo (is_array($EVs) && array_key_exists($event_id.'-0', $EVs))?' select':'';?>' data-id="<?php echo $event_id.'-0';?>" data-n="<?php echo htmlentities($post->post_title, ENT_QUOTES)?>" data-t='<?php echo $time;?>'><b></b>
						<span class='o'>
							<span class='n evofz14'><?php echo $post->post_title;?></span>
							<span class='t'><?php echo $time;?></span>							
						</span>
					</span><?php

					$events_list[ $EV->get_start_time() . '_' . $event_id ] = ob_get_clean();
					$ev_count++;

					$repeats = $EV->get_repeats_count();
					if($repeats){
						for($x=1; $x<=$repeats; $x++){
							$EV->load_repeat($x);
							$time = $EV->get_formatted_smart_time($x);

							ob_start();

							$select = (is_array($EVs) && array_key_exists($event_id.'-'.$x, $EVs) ) ?' select':'';
							
							?><span class='rel_event<?php echo $select;?>' data-id="<?php echo $event_id.'-'.$x;?>" data-n="<?php echo htmlentities($post->post_title, ENT_QUOTES)?>" data-t='<?php echo $time;?>'><b></b>
								<span class='o'>
									<span class='n evofz14'><?php echo $post->post_title;?></span>
									<span class='t'><?php echo $time;?></span>									
								</span>
							</span><?php

							$events_list[ $EV->get_start_time() . '_' . $x ] = ob_get_clean();
							$ev_count++;
						}
					}
				}

				krsort($events_list);

				$content .= "<div class='evo_rel_search'>
					<span class='evo_rel_ev_count' data-t='".__('Events','eventon')."'>". $ev_count .' '. __('Events','eventon') ."</span>
					<input class='evo_rel_search_input' type='text' name='event' value='' placeholder='" . __('Search events by name','eventon'). " '/>
				</div>
				<div class='evo_rel_events_list'>";


				foreach($events_list as $ed=>$ee){
					$content .= $ee;
				}
				
				$content .= "</div><p style='text-align:center; padding-top:10px;'><span class='evo_btn evo_save_rel_events'>". __('Save Changes','eventon') ."</span></p>";
				
			}else{
				$content .= "<p>". __('You must create events first!','eventon') ."</p>";
			}

			$content .= "</div>";

			wp_send_json(array(
				'status'=>'good',
				'content'=> $content
			)); wp_die();
		}

	// Get Location Cordinates
		public function get_latlng(){
			$gmap_api = EVO()->cal->get_prop('evo_gmap_api_key', 'evcal_1');

			if( !isset($_POST['address'])){
				echo json_encode(array(
				'status'=>'bad','m'=> __('Address Missing','eventon'))); exit;
			}

			$address = sanitize_text_field($_POST['address']);
			
			$address = str_replace(" ", "+", $address);
			$address = urlencode($address);


			
			$url = "https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&key=".$gmap_api;

			$response = wp_remote_get($url);

			$response = wp_remote_retrieve_body( $response );
			if(!$response){ 
				wp_send_json(array(
				'status'=>'bad','m'=> __('Could not connect to google maps api','eventon'))); wp_die();
			}

			$RR = json_decode($response);

			if( !empty( $RR->error_message)){
				wp_send_json(array(
				'status'=>'bad','m'=> $RR->error_message )); wp_die();
			}

		    wp_send_json(array(
				'status'=>'good',
				'lat' => $RR->results[0]->geometry->location->lat,
		        'lng' => $RR->results[0]->geometry->location->lng,
			)); wp_die();
		}

	// get HTML views
		function admin_get_views(){

			$post_data = $this->helper->sanitize_array( $_POST);

			if(!isset($_POST['type'])){
				echo 'failed'; exit;
			} 

			$type = $_POST['type'];
			$data = isset($_POST['data'])? $_POST['data']: array();

			$views = new EVO_Views();

			wp_send_json(array(
				'status'=>'good','html'=>$views->get_html($type, $data)
			)); wp_die();
		}

	// event card designer 4.6
		function load_ecard_designer(){
			$designer = new EVO_Desginer();

			wp_send_json(array(
				'status'=>'good','content'=> $designer->get_eventcard_designer()
			)); wp_die();
		}
		function save_eventcard_designer(){

			// validate if user has permission
			if( !current_user_can('edit_eventons') ){
				wp_send_json(array(
					'status'=>'bad','msg'=> __('You do not have proper permission to access this','eventon')
				));
				wp_die();
			}

			// nonce validation
			if( empty($_POST['evo_noncename']) || !wp_verify_nonce( wp_unslash( $_POST['evo_noncename'] ), 'evo_evard_save' ) ){
				wp_send_json(array(
					'status'=>'bad','msg'=> __('Nonce validation failed','eventon')
				));	wp_die();
			}

			$post_data = $this->helper->sanitize_array( $_POST);

			EVO()->cal->set_cur('evcal_1');
			EVO()->cal->set_prop( 'evo_ecl' , $post_data['evo_ecl']);

			wp_send_json(array(
				'status'=>'good','msg'=> __('EventCard Design Saved Successfully')
			)); wp_die();

		}

	// export eventon settings
		function export_settings(){
			// validate if user has permission
			if( !current_user_can('edit_eventons') ){
				wp_die( __('User not loggedin','eventon'));
			}

			// verify nonce
			if(empty( $_REQUEST['nonce'] ) || !wp_verify_nonce( wp_unslash( $_REQUEST['nonce'] ), 'evo_export_settings')) {
				wp_die( __('Security Check Failed','eventon'));
			} 

			header('Content-type: text/plain');
			header("Content-Disposition: attachment; filename=Evo_settings__".date("d-m-y").".json");
			
			$json = array();
			$evo_options = get_option('evcal_options_evcal_1');
			foreach($evo_options as $field=>$option){
				// skip fields
				if(in_array($field, array('option_page','action','_wpnonce','_wp_http_referer'))) continue;
				$json[$field] = $option;
			}

			wp_send_json($json); wp_die();
		}

	// import settings
		public function get_import_settings(){
			$output = array('status'=>'bad','msg'=>'');

			// verify nonce
			if(empty( $_REQUEST['nn'] ) || !wp_verify_nonce( wp_unslash( $_REQUEST['nn'] ), 'eventon_admin_nonce')) {
				$output['msg'] = __('Security Check Failed!','eventon');
				wp_send_json($output); wp_die();
			}

			// check if admin and loggedin
			if(!is_admin() && !is_user_logged_in()){
				$output['msg'] = __('User not loggedin!','eventon');
				wp_send_json($output); wp_die();
			} 

			// validate if user has permission
			if( !current_user_can('edit_eventons') ){
				$output['msg'] = __('Required permission missing!','eventon');
				wp_send_json($output); wp_die();
			}

			ob_start();

			EVO()->elements->print_import_box_html(array(
				'box_id'=>'evo_settings_upload',
				'title'=>__('Upload JSON Settings File Form'),
				'message'=>__('NOTE: You can only upload settings data as .json file'),
				'file_type'=>'.json',
				'type'		=> 'inlinebox'
			));

			$output['status'] = 'good';
			$output['content'] = ob_get_clean();

			wp_send_json($output); wp_die();
			

		}
		function import_settings(){
			$output = array('status'=>'','msg'=>'');
			
			// verify nonce				
				if(empty( $_POST['nonce'] ) || !wp_verify_nonce($_POST['nonce'], 'eventon_admin_nonce')){ 
					$output['msg'] = __('Security Check Failed!','eventon');
					wp_send_json($output); 
					wp_die();
				}

			// check if admin and loggedin
				if(!is_admin() && !is_user_logged_in()){
					$output['msg'] = __('User not loggedin!','eventon');
					wp_send_json($output); wp_die();
				}

			// admin permission
				if( !current_user_can('edit_eventons')){
					$output['msg'] = __('Required permission missing','eventon');

					wp_send_json($output); wp_die();
				}

			$post_data = $this->helper->sanitize_array( $_POST);
			$JSON_data = $post_data['jsondata'];

			// check if json array present
			if( $JSON_data && !is_array($JSON_data)){
				$output['msg'] = __('Uploaded file is not a json format!','eventon');
				wp_send_json($output); wp_die();
			} 

			// if all good
			if( empty($output['msg'])){
				
				update_option('evcal_options_evcal_1', $JSON_data);
				$output['success'] = 'good';
				$output['msg'] = __('Successfully updated settings! This page will refresh with new settings.','eventon');
			}
			
			wp_send_json($output); wp_die();

		}

	// export events as CSV
	// @update 4.6.1
		function export_events($event_id = ''){

			// check if admin and loggedin
				if( !current_user_can('edit_eventons') ){
					wp_die( __('User not loggedin','eventon'));
				}

			// verify nonce
				if( empty( $_REQUEST['nonce'] ) || !wp_verify_nonce( wp_unslash( $_REQUEST['nonce'] ), 'eventon_download_events')) {
					wp_die('Security Check Failed!');
				}

			$run_process_content = false;
			$wp_args = array();

			// if event ID was passed
				if( isset($_REQUEST['eid']) ){
					$wp_args = array('p' => (int)$_REQUEST['eid']);
				}

			header('Content-Encoding: UTF-8');
        	header('Content-type: text/csv; charset=UTF-8');
			header("Content-Disposition: attachment; filename=Eventon_events_".date("d-m-y").".csv");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo "\xEF\xBB\xBF"; // UTF-8 BOM
			
			$evo_opt = get_option('evcal_options_evcal_1');
			$event_type_count = evo_get_ett_count($evo_opt);
			$cmd_count = evo_calculate_cmd_count($evo_opt);

			$run_iconv = EVO()->cal->check_yn('evo_disable_csv_formatting','evcal_1') ? false : true;

			$fields = $this->get_event_csv_fields();

			// Print out the CSV file header
				$csvHeader = '';
				foreach( $fields as $var=>$val){	$csvHeader.= $val.',';	}

				// event types
					for($y=1; $y<=$event_type_count;  $y++){
						$_ett_name = ($y==1)? 'event_type': 'event_type_'.$y;
						$csvHeader.= $_ett_name.',';
						$csvHeader.= $_ett_name.'_slug,';
					}
				// for event custom meta data
					for($z=1; $z<=$cmd_count;  $z++){
						$_cmd_name = 'cmd_'.$z;
						$csvHeader.= $_cmd_name.",";
					}

				$csvHeader = apply_filters('evo_export_events_csv_header',$csvHeader);
				$csvHeader.= "\n";
				
				echo (function_exists('iconv'))? iconv("UTF-8", "ISO-8859-2", $csvHeader): $csvHeader;
 		

			// using calendar function
				$events = EVO()->calendar->get_all_event_data(array(
					'hide_past'=>'no',
					'wp_args'=> $wp_args
				));
				
				if(!empty($events)):

					// allow processing content for html readability
					$process_html_content = true;

					$DD = new DateTime('now', EVO()->calendar->cal_tz);

					// EACH EVENT
					foreach($events as $event_id=>$event):

						$pmv = isset($event['pmv'] ) ? $event['pmv'] : '';

						$EVENT = new EVO_Event( $event_id, $pmv, 0, true, false);

						$DD->setTimezone( $EVENT->tz ); // adjust time to event tz

						if(empty($pmv ) ) $pmv = $EVENT->get_data();

						// Initial values
						$csvRow = '';
						$csvRow.= ( $event['post_status'] ?? '').",";
						$csvRow.= $event_id.",";
						$csvRow.= ( $EVENT->get_hex() ).",";

						$csvRow.= '"'. $event['name'].'",';

						
						// summary for the ICS file
							$event_content = ( $event['content'] ?? '');
								$event_content = str_replace('"', "'", $event_content);
								$event_content = str_replace(',', "\,", $event_content);
								if( $run_process_content){
									$event_content = $this->html_process_content( $event_content, $process_html_content);
								}
							$csvRow.= '"'. sanitize_text_field($event_content).'",';


						// start time
							if( isset($event['start'])){
								$DD->setTimestamp( $event['start'] );
								// date and time as separate columns
								$csvRow.= '"'. $DD->format( apply_filters('evo_csv_export_dateformat','m/d/Y') ) .'",';
								$csvRow.= '"'. $DD->format( apply_filters('evo_csv_export_timeformat','h:i:A') ) .'",';
							}else{ $csvRow.= "'','',";	}

						// end time
							if( isset($event['end'])){
								$DD->setTimestamp( $event['end'] );
								// date and time as separate columns
								$csvRow.= '"'. $DD->format( apply_filters('evo_csv_export_dateformat','m/d/Y') ) .'",';
								$csvRow.= '"'. $DD->format( apply_filters('evo_csv_export_timeformat','h:i:A') ) .'",';
							}else{ $csvRow.= ",,";	}



						// FOR EACH field					
						foreach($fields as $var=>$val){
							// skip already added fields
								if(in_array($val, array('publish_status',	
									'event_id',			
									'color',
									'event_name',				
									'event_description','event_start_date','event_start_time','event_end_date','event_end_time',))){
									continue;
								}
							
							// yes no values
								if(in_array($val, array('featured','all_day','hide_end_time','event_gmap','evo_year_long','_evo_month_long','repeatevent'))){

									$csvRow.= ( (!empty($pmv[$var]) && $pmv[$var][0]=='yes') ? 'yes': 'no').',';
									continue;
								}

							// organizer field
								$continue = false;

								switch($val){
									case 'evo_organizer_id':
										if(isset($event['organizer_tax']) ){
											$csvRow .= '"'. $event['organizer_tax'] .'",';
										}else{	$csvRow.= ",";	}$continue = true;
									break;
									case 'event_organizer':
										if( isset($event['organier_name']) ){
											$csvRow.= '"'. $this->html_process_content( $event['organier_name'], $process_html_content) . '",';	
										
										}else{	$csvRow.= ",";	}$continue = true;
									break;
									case 'organizer_description':
										if( isset($event['organizer_desc']) ){
											$csvRow.= '"'. $this->html_process_content($event['organizer_desc'], $process_html_content) . '",';
										
										}else{	$csvRow.= ",";	}$continue = true;
									break;
									case 'evcal_org_contact':
										if( isset($event['evcal_org_contact']) ){
											$csvRow.= '"'. $this->html_process_content($event['evcal_org_contact'], $process_html_content) . '",';
										}else{	$csvRow.= ",";	}$continue = true;
									break;
									case 'evcal_org_address':
										if( isset($event['organizer_address']) ){
											$csvRow.= '"'. $this->html_process_content($event['organizer_address'], $process_html_content) . '",';
										}else{	$csvRow.= ",";	}$continue = true;
									break;
									case 'evcal_org_exlink':
										if( isset($event['organizer_link']) ){
											$csvRow.= '"'. $this->html_process_content($event['organizer_link'], $process_html_content) . '",';
										}else{	$csvRow.= ",";	}$continue = true;
									break;
									case 'evo_org_img':
										if( isset($event['organizer_img']) ){
											$csvRow.= '"'. $event['organizer_img'] . '",';
										}else{	$csvRow.= ",";	}$continue = true;
									break;
								}
								if($continue) continue;

							// location tax field
								$continue = false;
								switch ($val){
									case 'location_description':
										if(isset($event['location_desc']) ){
											$csvRow .= '"'. $this->html_process_content( $event['location_desc'], $process_html_content ) .'",';
										}else{	$csvRow.= ",";	}$continue = true;

									break;
									case 'evo_location_id':
										if(isset($event['location_tax']) ){
											$csvRow .= '"'. $event['location_tax'] .'",';
										}else{	$csvRow.= ",";	}$continue = true;
									break;
									case 'location_name':
										if(isset($event['location_name']) ){
											$csvRow .= '"'. $this->html_process_content( $event['location_name'], $process_html_content ) .'",';
										}else{	$csvRow.= ",";	}$continue = true;
									break;
									case 'event_location':

										if(isset($event['location_address']) ){
											$csvRow .= '"'. $this->html_process_content( $event['location_address'] ,$process_html_content ) .'",';
										}else{	$csvRow.= ",";	}$continue = true;
									break;
									case 'location_latitude':
										if(isset($event['location_lat']) ){
											$csvRow .= '"'. $event['location_lat']  .'",';
										}else{	$csvRow.= ",";	}$continue = true;								
									break;
									case 'location_longitude':
										if(isset($event['location_lon']) ){
											$csvRow .= '"'. $event['location_lon']  .'",';
										}else{	$csvRow.= ",";	}$continue = true;									
									break;
									case 'location_link':
										if(isset($event['location_link']) ){
											$csvRow .= '"'. $event['location_link']  .'",';
										}else{	$csvRow.= ",";	}$continue = true;									
									break;
									case 'location_img':
										if(isset($event['location_img']) ){
											$csvRow .= '"'. $event['location_img']  .'",';
										}else{	$csvRow.= ",";	}$continue = true;	
																	
									break;
								}

								if($continue) continue;

							// skip fields
								if(in_array($val, array('featured','all_day','hide_end_time','event_gmap','evo_year_long','_evo_month_long','repeatevent','color','publish_status','event_name','event_description','event_start_date','event_start_time','event_end_date','event_end_time','evo_organizer_id', 'evo_location_id'
									)
								)) continue;

							// image
								if($val =='image_url'){

									if( isset($event['image_url'])){
										$csvRow.= $event['image_url'].",";
									}else{
										$csvRow.= ",";
									} 
									
							// all other fields
								}else{
									if(!empty($pmv[$var])){
										$value = $this->html_process_content(
											$pmv[$var][0], 
											$process_html_content
										);
										$csvRow.= '"'.$value.'"';
									}else{ $csvRow.= '';}
									$csvRow.= ',';
								}

						}
					
					// event types
						for($y=1; $y<=$event_type_count;  $y++){
							$_ett_name = ($y==1)? 'event_type': 'event_type_'.$y;
								
							if( isset($event[$_ett_name])){
								$term_ids = $term_names = '';
								
								foreach ( $event[$_ett_name] as $termid=>$termname ) {
									$term_ids .= $termid.',';
									$term_names .= $termname.',';
								}

								$csvRow.= '"'. $term_ids. '",';
								$csvRow.= '"'. $termname. '",';
							}else{	$csvRow.= ",,";	}	// no event type					
						}
					// for event custom meta data
						for($z=1; $z<=$cmd_count;  $z++){
							$cmd_name = '_evcal_ec_f'.$z.'a1_cus';
							$csvRow.= (!empty($pmv[$cmd_name])? 
								'"'.str_replace('"', "'", $this->html_process_content($pmv[$cmd_name][0], $process_html_content) ) .'"'
								:'');
							$csvRow.= ",";
						}

					// closing
						$csvRow = apply_filters('evo_export_events_csv_row',$csvRow, $event_id, $pmv);
						$csvRow.= "\n";

						if( $run_iconv ){
							echo (function_exists('iconv'))? iconv("UTF-8", "ISO-8859-2", $csvRow): $csvRow;
							//echo $csvRow;
						}else{
							echo $csvRow;
							
						}

					endforeach;
				endif;

				wp_die();


 			
		}

		// @4.6.1
		private function get_event_csv_fields(){
			return apply_filters('evo_csv_export_fields',array(
				'publish_status',	
				'event_id',			
				'evcal_event_color'=>'color',
				'event_name',				
				'event_description','event_start_date','event_start_time','event_end_date','event_end_time',

				//'evcal_allday'=>'all_day',
				'_evo_tz'=>'evo_tz',
				'_time_ext_type'=>'time_ext_type',
				'evo_hide_endtime'=>'hide_end_time',
				'evcal_gmap_gen'=>'event_gmap',
				'evo_year_long'=>'yearlong',
				'_featured'=>'featured',

				'evo_location_id'=>'evo_location_id',
				'evcal_location_name'=>'location_name',	// location name			
				'evcal_location'=>'event_location',	// address		
				'location_desc'=>'location_description',	
				'location_lat'=>'location_latitude',	
				'location_lon'=>'location_longitude',	
				'location_link'=>'location_link',	
				'location_img'=>'location_img',	
				
				'evo_organizer_id'=>'evo_organizer_id',
				'evcal_organizer'=>'event_organizer',
				'organizer_description'=>'organizer_description',
				'organizer_contact'=>'evcal_org_contact',
				'organizer_address'=>'evcal_org_address',
				'organizer_link'=>'evcal_org_exlink',
				'organizer_img'=>'evo_org_img',

				'evcal_subtitle'=>'evcal_subtitle',
				'evcal_lmlink'=>'learnmore link',
				'image_url',

				'evcal_repeat'=>'repeatevent',
				'evcal_rep_freq'=>'frequency',
				'evcal_rep_num'=>'repeats',
				'evp_repeat_rb'=>'repeatby',
			));
		}
		function html_process_content($content, $process = true){
			//$content = iconv('UTF-8', 'Windows-1252', $content);
			return ($process)? htmlentities($content, ENT_QUOTES): $content;
		}

	// Validation of eventon products
		function validate_license(){
			
			$post_data = $this->helper->sanitize_array( $_POST);

			$status = 'bad'; 
			$error_code = 11; 
			$error_msg_add = $html = $email = $msg = '';
			
			// check for required information
				if(empty($post_data['type']) && isset($post_data['key']) && isset($post_data['slug']) ){ 
					wp_send_json(array('status'=>'bad','error_msg'=> EVO_Error()->error_code(14) ));		
					wp_die();
				}

			// Initial values
			$type = $post_data['type'];
			$license_key = $post_data['key'];
			$slug = $post_data['slug'];

			$PROD = new EVO_Product_Lic($slug);
			
			// check for key format validation
			$verifyformat = $PROD->purchase_key_format($license_key );
			if(!$verifyformat) $error_code = '02';	

			// check if email provided for eventon addons
			if( $post_data['slug'] != 'eventon'){
				if(empty($post_data['email'])){
					$status = 'bad';
					$msg = 'Email address not provided!';
					$verifyformat = false;
				}else{
					$email = str_replace(' ','',$post_data['email']);
				}
			}
			
			// if license key format is validated
			if($verifyformat){


				// save eventon data
				if($type=='main') $PROD->save_license_data();

				$status = 'good';
				$msg = ($slug=='eventon')?
					'Excellent! Purchase key verified and saved. Thank you for activating EventON!':
					'Excellent! License key verified and saved. Thank you for activating EventON addon!';

				$data_args = array(
					'type'		=>(!empty($post_data['type'])?$post_data['type']:'main'),
					'key'		=> addslashes( str_replace(' ','',$license_key) ),
					'email'		=> $email,
					'product_id'=>(!empty($post_data['product_id'])?$post_data['product_id']:''),
				);
				$validation = $PROD->remote_validation($data_args);

				// Other update tasks
				if($type=='addon'){	
					// update other addon fields
					foreach(array(
						'email','product_id','instance','key'
					) as $field){
						if(!empty($post_data[$field])){
							$PROD->set_prop( $field, $post_data[$field], false);
						}
					}
					$PROD->save();
				}

				$results = $this->get_remote_validation_results($validation, $PROD, $type);
	
				if(isset($results['error_code'])) $error_code = $results['error_code'];

				$status = $results['status'];
					
				if($error_code != 11){
					$msg = EVO_Error()->error_code( $error_code);
				}

				if($results['status'] == 'bad' && in_array( $error_code, array(11,21,23) )){
					$msg = EVO_Error()->error_code( 120 );
				}

			}else{
				// Invalid license key format
				$status = 'bad';
				if(empty($msg)) $msg = 'License Key format is not a valid format!';
			}

			$return_content = array(
				'status'=>	$status,
				'msg'=> 	$msg,				
				'code'=> 	$error_code,
				'html'=>	$this->get_html_view( $type,$slug),
				'debug'=> $validation
			);

			wp_send_json($return_content);	wp_die();
		}

		// RE-VALIDATE
			function revalidate_license(){
				$post_data = $this->helper->sanitize_array( $_POST);
				$slug = $post_data['slug'];

				$PROD = new EVO_Product_Lic($slug);

				//echo $PROD->get_prop('key');

				if( !$PROD->get_prop('key') || !$PROD->get_prop('email')){
					echo json_encode(array(
						'status'=>'bad',
						'msg'=>'Required fields for remote validation are missing! try deactivating and reactivating again.'
					));		
					exit;
				}else{

					$ERR = new EVO_Error();
					$ERR->record_gen_log('Re-activating', $slug,'','',false);

					$data_args = array(
						'type'		=>(!empty($post_data['type'])?$post_data['type']:'main'),
						'key'		=> $PROD->get_prop('key'),
						'email'		=> $PROD->get_prop('email'),
						'product_id'=>(!empty($post_data['product_id'])?$post_data['product_id']:''),						
						'instance'	=> md5(get_site_url()),
					);
					$validation = $PROD->remote_validation($data_args);
					
					$results = $this->get_remote_validation_results( $validation, $PROD , $post_data['type']);
					$output_error_code = isset($results['error_code'])? (int) $results['error_code']: false;

					if($results['status'] == 'bad'){
						$ERR->record_gen_log('Re-activating failed', $slug, $results['error_code'],'',false);
					}
					
					$ERR->save();

					// Message intepretation
					$msg = ($results['status']=='bad'? EVO_Error()->error_code(15): EVO_Error()->error_code(16));
					if($results['status'] == 'bad' && $output_error_code && in_array( $output_error_code, array(11,21,23) )){
						$msg = EVO_Error()->error_code( 121 );
					}

					if($output_error_code && in_array( $output_error_code, array(100,101,102,103)) ){
						$msg = EVO_Error()->error_code( $output_error_code );

						if( $output_error_code == 103){
							$msg = EVO_Error()->error_code( '103r' );
							EVO_Error()->record_deactivation_loc($slug);
							$PROD->deactivate();
						}
					}

					wp_send_json(array(
						'status'=> $results['status'],
						'msg'=> $msg,
						//'error_msg'=> EVO_Error()->error_code( $results['error_code']),
						'html'=> $this->get_html_view( 'addon',$slug),					
					));	 wp_die();
				}
			}

	// REMOTE RESULTS
		function get_remote_validation_results($validation, $PROD, $type){
			// validation contain // status, error_remote_msg, error_code, api_url
			// invalid remote validation
			$output = array();
			$error_code = false;
			if($validation['status'] =='good'){
				$output['status'] = 'good';
				EVO_Prods()->get_remote_prods_data();
				$PROD->evo_kriyathmaka_karanna();
				EVO_Error()->record_activation_rem();

			}else{
				$output['status'] = 'bad';	
				if(!empty($validation['error_code'])) $error_code =  (int)$validation['error_code'];
				
				$output['error_code'] = $error_code;
				$output['error_msg'] = isset($validation['error_remote_msg'])? $validation['error_remote_msg']: '';

				// local kriyathmaka karala nehe
				if(!$PROD->kriyathmaka_localda() && $error_code && in_array( $error_code, array(11,21,23) ) ){
					$PROD->evo_kriyathmaka_karanna_athulen();
					EVO_Error()->record_activation_loc($error_code);
				}
			}

			return $output;
		}

	// Deactivate EventON Products
		function deactivate_product(){
			$post_data = $this->helper->sanitize_array( $_POST);
			$error_msg = $status = $html = '';
			$error_code = '00';
			
			if($post_data['type'] == 'main'){
				$PROD = new EVO_Product_Lic('eventon');
				$status = $PROD->deactivate();

				$slug = 'eventon';

				// not able to deactivate
				if(!$status){
				 	$error_code = '07';	
				}else{ // deactivated
					EVO_Error()->record_deactivation_loc($slug);
					$html = $this->get_html_view('main',$slug);
					$error_code = 32;
				}
				
			}else{// for addons

				if(!isset($post_data['slug'])){
					echo json_encode(array(
						'status'=>'bad',
						'error_msg'=> EVO_Error()->error_code(14)
					)); exit;
				}

				$PROD = new EVO_Product_Lic($post_data['slug']);
			
				// passing data
					$remote_data = array(
						'key'		=> addslashes( str_replace(' ','',$post_data['key']) ),
						'email'		=>(!empty($post_data['email'])? $post_data['email']: null),
						'product_id'=>(!empty($post_data['product_id'])? $post_data['product_id']: null),
					);

				// deactivate addon from remote server
					$deactive_remotely = $PROD->remote_deactivate($remote_data);

					$returned_error_code = isset($deactive_remotely['error_code'])? (int)$deactive_remotely['error_code']:false;

					if($returned_error_code && in_array( $returned_error_code, array(30,31) ) ){
						
						EVO_Error()->record_deactivation_fail($returned_error_code);
						EVO_Error()->record_deactivation_loc($post_data['slug']);
						$PROD->deactivate();
						$error_code = 32;
					}else{
						$error_code = 33;
						EVO_Error()->record_deactivation_rem();
						$PROD->deactivate();
					}


					$html = $this->get_html_view('addon',$post_data['slug']);
					$status = 'success';
			}

			$return_content = array(
				'status'=> ($status?'success':'bad'),
				'msg'=>EVO_Error()->error_code($error_code),
				'html'=> $html,					
			);
			
			wp_send_json($return_content);	wp_die();
		}

		function get_html_view($type,$slug){
			$views = new EVO_Views();
			$var = ($type=='main')? 'evo_box': 'evo_addon_box';
			return $views->get_html(	$var,array('slug'	=>$slug) );
		}
		
	// get all addon details
		public function get_addons_list(){

			// verifications
			if(!is_admin()) return false;

			$active_plugins = get_option( 'active_plugins' );

			ob_start();
			// installed addons		

				$addons_list = new EVO_Addons_List();

				$count=1;
				// EACH ADDON
				foreach($addons_list->get_list() as $slug=>$product){

					if($slug=='eventon') continue; // skip for eventon
					$_has_addon = false;

					$views = new EVO_Views();

					echo $views->get_html(
						'evo_addon_box',
						array(
							'slug'				=>$slug,
							'product'			=>$product,
							'active_plugins'	=>$active_plugins
						)
					);
					
					$count++;
				} //endforeach

			$content = ob_get_clean();

			$return_content = array(
				'content'=> $content,
				'status'=>true
			);			
			wp_send_json($return_content);	wp_die();
		}

	/** Feature an event from admin */
		function eventon_feature_event() {

			if ( ! is_admin() ) wp_die( __( 'Only available in admin side.', 'eventon' ) );

			if ( ! current_user_can('edit_eventons') ) wp_die( __( 'You do not have sufficient permissions to access this page.', 'eventon' ) );

			if ( ! check_admin_referer('eventon-feature-event')) wp_die( __( 'You have taken too long. Please go back and retry.', 'eventon' ) );

			$post_id = isset( $_GET['eventID'] ) && (int) $_GET['eventID'] ? (int) $_GET['eventID'] : '';

			if (!$post_id) wp_die( __( 'Event id is missing!', 'eventon' ) );

			$post = get_post($post_id);

			if(!$post) wp_die( __( 'Event post doesnt exists!'),'eventon');
			if( $post->post_type !== 'ajde_events' ) wp_die( __('Post type is not an event', 'eventon' ) );

			$featured = get_post_meta( $post->ID, '_featured', true );

			wp_safe_redirect( remove_query_arg( array('trashed', 'untrashed', 'deleted', 'ids'), wp_get_referer() ) );
			
			if( $featured == 'yes' )
				update_post_meta($post->ID, '_featured', 'no');
			else
				update_post_meta($post->ID, '_featured', 'yes'); 

			wp_safe_redirect( remove_query_arg( array('trashed', 'untrashed', 'deleted', 'ids'), wp_get_referer() ) );
			exit;
		}
	
	// Diagnose
		// send test email
		function admin_test_email(){
			$post_data = $this->helper->sanitize_array( $_POST);
			$email_address = $post_data['email'];

			$result = wp_mail($email_address, 'This is a Test Email', 'Test Email Body', array('Content-Type: text/html; charset=UTF-8') );
			
			$ts_mail_errors = array();
			if(!$result){
				global $ts_mail_errors;
				global $phpmailer;

				if (!isset($ts_mail_errors)) $ts_mail_errors = array();

				if (isset($phpmailer)) {
					$ts_mail_errors[] = $phpmailer->ErrorInfo;
				}
			}

			echo json_encode(array(
				'msg'=> ($result?'Email Sent': 'Email was not sent'),
				'error'=>$ts_mail_errors
			));		
			exit;
		}

		// system log
		function admin_system_log(){
			
			$html = '';
			ob_start();

			echo EVO_Error()->_get_html_log_view();

			echo "<div class='evopadt20'>";

				EVO()->elements->print_trigger_element(array(
					'extra_classes'=>'',
					'title'=>__('Flush Log','eventon'),
					'dom_element'=> 'span',
					'uid'=>'evo_admin_flush_log',
					'lb_class' =>'evoadmin_system_log',
					'lb_load_new_content'=> true,	
					'ajax_data' =>array('action'=>'eventon_admin_system_log_flush'),
				), 'trig_ajax');

			echo "</div>";


			$html = ob_get_clean();

			wp_send_json(array(
				'status'=>'good',
				'content'=> $html
			));
			wp_die();
		}
		function admin_system_log_flush(){
			EVO_Error()->_flush_all_logs();

			$html = EVO_Error()->_get_html_log_view();
			
			wp_send_json(array(
				'status'=>'good',
				'msg'=> __('All system logs flushed'),
				'content'=> $html
			));
			wp_die();
		}

		// environment @u 4.5.5
		function admin_get_environment(){

			// check if admin and loggedin
				if( !current_user_can('edit_eventons') ){
					wp_send_json_error(  __('User does not have permission','eventon') );
					wp_die();
				}
			
			$data = array(); $html = ''; global $wpdb;

			// event count
			$event_posts_r = $wpdb->get_results( "SELECT ID FROM {$wpdb->posts} WHERE post_type='ajde_events'" );
			$events_count = ($event_posts_r && is_array($event_posts_r) )? count($event_posts_r):0;

			// event post meta count
			$pm_cunt_r = $wpdb->get_results( "SELECT pm.meta_id FROM {$wpdb->posts} p INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id WHERE p.post_type = 'ajde_events'" );
			$pm_count = ($pm_cunt_r && is_array($pm_cunt_r) )? count($pm_cunt_r):0;

			$data['EventON_version'] = EVO()->version;
			$data['WordPress_version'] = get_bloginfo( 'version' );
			$data['is_multisite'] = is_multisite()?'Yes':'No';

			$data['WordPress_memory_limit'] =  WP_MEMORY_LIMIT;
			$data['WordPress_Debug_mode'] = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'Yes':'No';
			$data['WordPress_Cron'] = ! ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) ? 'Yes':'No';
						
			$data['shead1'] = __('Server Environment');
			$data['PHP_version'] = phpversion();
			$data['PHP_max_input_vars'] = ini_get( 'max_input_vars' ) . ' '. __('Characters');
			$data['Maximum_update_size'] = size_format( wp_max_upload_size() );
			$data['CURL_enabled'] = in_array  ('curl', get_loaded_extensions() ) ? 'Yes':'No';

			$data['shead2'] = __('Post Data');
			$data['Events_count'] = $events_count;
			$data['Total_event_postmeta_DB_entries'] = $pm_count;

			// database information
			if ( defined( 'DB_NAME' ) ) {	}

			$html = '<div class="evo_environment">';

			foreach($data as $D=>$V){

				if( strpos($D, 'shead') !==  false ){ 
					$html .= "<p class='shead'>". $V ."</p>"; continue;
				}

				$D = str_replace('_', ' ', $D);
				$html .= "<p><span>".$D."</span><span class='data'>". $V ."</span></p>";
			}


			$html .= "</div>";
				
			wp_send_json(array(
				'status'=>'good',
				'content'=> $html,
			)); wp_die();
		}
}
new EVO_admin_ajax();