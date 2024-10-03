<?php
/**
 * EventON Ajax Handlers
 * Handles AJAX requests via wp_ajax hook (both admin and front-end events)
 *
 * @author 		AJDE
 * @category 	Core
 * @package 	EventON/Functions/AJAX
 * @version     4.6
 */

class EVO_AJAX{
	/**
	 * Hook into ajax events
	 */

	private $helper;

	public static function init(){

		add_action( 'init', array( __CLASS__, 'define_ajax' ), 0 );
		add_action( 'template_redirect', array( __CLASS__, 'do_evo_ajax' ), 0 );
		self::add_ajax_events();

		// deprecating in 4.5
		add_filter('evo_ajax_general_send_results',array(__CLASS__, 'general_send_req'), 10,2);
	}	

	// AJAX via endpoints @since 4.4
		
		// Get Ajax Endpoint.	
		public static function get_endpoint( $request = '' ) {
			return esc_url_raw( apply_filters( 'eventon_ajax_get_endpoint', add_query_arg( 'evo-ajax', $request, remove_query_arg( array( '_wpnonce' ), home_url( '/', 'relative' ) ) ), $request ) );
		}

		// Set AJAX constant and headers.
		public static function define_ajax() {
			// phpcs:disable
			if ( ! empty( $_GET['evo-ajax'] ) ) {
				evo_maybe_define_constant( 'DOING_AJAX', true );
				evo_maybe_define_constant( 'EVO_DOING_AJAX', true );
				if ( ! WP_DEBUG || ( WP_DEBUG && ! WP_DEBUG_DISPLAY ) ) {
					@ini_set( 'display_errors', 0 ); // Turn off display_errors during AJAX events to prevent malformed JSON.
				}
				$GLOBALS['wpdb']->hide_errors();
			}
			// phpcs:enable
		}

		// Send headers for Ajax Requests.
		private static function evo_ajax_headers() {
			if ( ! headers_sent() ) {
				send_origin_headers();
				send_nosniff_header();
				evo_nocache_headers();
				header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
				header( 'X-Robots-Tag: noindex' );
				status_header( 200 );
			} elseif ( Constants::is_true( 'WP_DEBUG' ) ) {
				headers_sent( $file, $line );
				trigger_error( "evo_ajax_headers cannot set headers - headers already sent by {$file} on line {$line}", E_USER_NOTICE ); // @codingStandardsIgnoreLine
			}
		}

		// Ajax request and fire action
		public static function do_evo_ajax(){
			global $wp_query;

			// phpcs:disable WordPress.Security.NonceVerification.Recommended
			if ( ! empty( $_GET['evo-ajax'] ) ) {
				$wp_query->set( 'evo-ajax', sanitize_text_field( wp_unslash( $_GET['evo-ajax'] ) ) );
			}

			$action = $wp_query->get( 'evo-ajax' );

			if ( $action ) {
				self::evo_ajax_headers();
				$action = sanitize_text_field( $action );
				do_action( 'evo_ajax_' . $action );
				wp_die();
			}
			// phpcs:enable
		}

	// AJAX events
		public static function add_ajax_events(){
			$ajax_events = array(
				'init_load'=>'init_load',			
				'ajax_events_data'=>'ajax_events_data',			
				'get_events'=>'main_ajax_call',			
				//'the_ajax_hook'=>'main_ajax_call',			
				'load_event_content'=>'load_event_content',
				'load_single_eventcard_content'=>'load_single_eventcard_content',
				'ics_download'=>'eventon_ics_download',			
				'export_events_ics'=>'export_events_ics',
				'search_evo_events'=>'search_evo_events',
				'get_local_event_time'=>'get_local_event_time',
				'refresh_now_cal'=>'refresh_now_cal',
				'record_mod_joined'=>'record_mod_joined',
				'refresh_elm'=>'refresh_elm',
				'gen_trig_ajax'=>'gen_trig_ajax',
				'get_tax_card_content'=>'get_tax_card_content',

			);
			foreach ( $ajax_events as $ajax_event => $class ) {
				$prepend = ( in_array($ajax_event, array('the_ajax_hook','evo_dynamic_css','the_post_ajax_hook_3','the_post_ajax_hook_2')) )? '': 'eventon_';
				add_action( 'wp_ajax_'. $prepend . $ajax_event, array( __CLASS__, $class ) );
				add_action( 'wp_ajax_nopriv_'. $prepend . $ajax_event, array( __CLASS__, $class ) );

				// EVO AJAX can be used for frontend ajax requests.
				add_action( 'evo_ajax_' . $prepend . $ajax_event, array( __CLASS__ , $class ) );
			}
		}

	// Initial load
		public static function init_load($return = false){
			
			$post_data = EVO()->helper->recursive_sanitize_array_fields( $_POST);	

			// init load calendar events
			$CALS = array();
			if(isset($post_data['cals']) && is_array($post_data['cals'])){
				foreach($post_data['cals'] as $calid=>$CD){
					if(!isset( $CD['sc'])) continue;

					$SC = EVO()->helper->sanitize_array( $CD['sc'] );

					$CALS[$calid]['sc'] = $SC;

					EVO()->calendar->process_arguments( $SC );

					// get events for the calendar
					$E = EVO()->calendar->_generate_events(
						'both',	apply_filters('evo_init_ajax_wparg_additions',array())
					);

					//print_r($$CD);
					$CALS[$calid]['json'] = $E['data'];
					$CALS[$calid]['html'] = $E['html'];
					//$CALS[$calid]['debug'] = date('Y-m-d H:i', $CD['sc']['focus_start_date_range']).'-'.date('Y-m-d H:i', $CD['sc']['focus_end_date_range']);
				}
			}


			$global = isset($post_data['global'])? 
				EVO()->helper->sanitize_array( $post_data['global'] ): array();



			$R =  apply_filters('evo_init_ajax_data', array(
				'cal_def'=> EVO()->calendar->helper->get_calendar_defaults(),
				'temp'=> EVO()->temp->get_init(),
				'dms' => array(
					'd'=> EVO()->cal->get_all_day_names(),
					'd3'=> EVO()->cal->get_all_day_names('three'),
					'd1'=> EVO()->cal->get_all_day_names('one'),
					'm'=> EVO()->cal->_get_all_month_names(),
					'm3'=> EVO()->cal->_get_all_month_names('short'),
				),		
				'cals'=> apply_filters('evo_init_ajax_cals',$CALS),		
				'txt'=> array(
					'no_events'=> evo_lang_get('evcal_lang_noeve','No Events'),
					'all_day'=> evo_lang_get('evcal_lang_allday','All Day'),
					'event_completed'=> evo_lang('Event Completed'),
				), 
				'html'=>array(
					'no_events' => EVO()->calendar->helper->get_no_event_content(),
				),	
				// language translated texts for client side
				'terms'=> array(),
			), $global);

			wp_send_json( $R );

		}

	// General ajax call - added 3.1
		public static function gen_trig_ajax(){

			// verify nonce
			if(empty( $_POST['nn'] ) || !wp_verify_nonce( wp_unslash( $_POST['nn'] ), 'eventon_nonce')) {
				wp_die( esc_html__( 'Action failed. Please refresh the page and retry.','eventon') ); 
			}
			
			$PP = $this->helper->recursive_sanitize_array_fields( $_POST );
		
			wp_send_json(
				apply_filters('evo_ajax_general_send_results', array('status'=>'good'), $PP)
			);			
		}

	// get taxonomy lightbox card details u 4.6
		public static function get_tax_card_content(){
			$post = EVO()->helper->sanitize_array( $_POST );

			evo_set_global_lang( isset($post['lang'])? $post['lang']:'L1' ); // 4.6.3
			
			// not supported tax
			if( !in_array( $post['tax'], array('event_organizer', 'event_location'))){
				wp_send_json(					
					array(
						'status'=>'bad',
						'content'=> __('This event category is not supported for more details')
					)
				); wp_die();
			}

			// supported tax
			$tax = new EVO_Tax();

			wp_send_json(					
				array(
					'status'=>'good',
					'content'=> $tax->get_lightbox_content( $post ) ,
				)
			);
			

			wp_die();
		}

	// Primary function to load event data 
		public static function main_ajax_call(){
			$postdata = EVO()->helper->sanitize_array( $_POST );

			// nonce verification
			if(empty( $_POST['nonce'] ) || !wp_verify_nonce(wp_unslash( $_POST['nonce'] ), 'eventon_nonce')) {
				wp_die( esc_html__( 'Action failed. Please refresh the page and retry.','eventon') ); 
			}

			$shortcode_args = $focused_month_num = $focused_year = '';
			$status = 'GOOD';

			$SC = isset($postdata['shortcode']) ? $postdata['shortcode']: array();

			$ajaxtype = isset($postdata['ajaxtype'])? $postdata['ajaxtype']: '';

			extract($SC);

			if(empty($number_of_months)){ return false; exit; }
			if($number_of_months < 1){ return false; exit; }

			EVO()->calendar->shortcode_args = $SC;
							
			// date range calculation
				if( isset($SC['focus_start_date_range']) && isset($SC['focus_end_date_range']) ){
					$focus_start_date_range = (int)$SC['focus_start_date_range'];
					$focus_end_date_range = (int)$SC['focus_end_date_range'];
				}

				$calendar_type = 'default';

				// event list with more than one month
				if( $SC['number_of_months']==1){

					// calculate new date range if calendar direction is changing
					if($postdata['direction'] !='none'){
						if(!empty($fixed_month) && !empty($fixed_year)){

							$DD = new DateTime('now');
							$DD->setTimezone( EVO()->calendar->cal_tz );
							$DD->setDate($fixed_year,$fixed_month,1 );
							$DD->setTime(0,0,0);

							// get previous or next month
							( $postdata['direction']=='next' ) ?	
								$DD->modify('+1 month') : $DD->modify('-1 month');
							

							$SC['fixed_day'] = $DD->format('j');
							$SC['fixed_month'] = $DD->format('n');
							$SC['fixed_year'] = $DD->format('Y');
							$SC['focus_start_date_range'] = $DD->format('U');
							$DD->modify('last day of this month');
							$DD->setTime(23,59,59);
							$SC['focus_end_date_range'] = $DD->format('U');	

						}
					}else{ // not switching months

						// Going to today
						if($ajaxtype == 'today' || $ajaxtype == 'jumper'){
							$SC['focus_start_date_range']='';
							$SC['focus_end_date_range']='';	
							EVO()->calendar->shortcode_args = $SC;						
							EVO()->calendar->shell->set_calendar_range();	
							$SC = EVO()->calendar->shortcode_args;
						}
					}

				}else{	$calendar_type = 'list';	}
														

			// set calendar shortcode values
				$SC = apply_filters('eventon_ajax_arguments',$SC, $postdata, $ajaxtype);		
				ksort($SC);
				extract($SC);

				EVO()->calendar->shortcode_args = $SC; // set arguments to the calendar object
					
			// GET calendar header month year values
				$calendar_month_title = get_eventon_cal_title_month($fixed_month, $fixed_year, $lang);
						
			// Calendar content		
				$content = EVO()->evo_generator->_generate_events();

			// RETURN VALUES
				wp_send_json( 
					apply_filters('evo_ajax_query_returns', array(
					'status'=> 					$status,
					'json'=> 					$content['data'],	
					'html'=>					$content['html'],				
					'cal_month_title'=>			$calendar_month_title,
					'SC'=> 	$SC,
					'debug' => array(
						's' => date('y-m-d h:i:s', $focus_start_date_range),
						'e' => date('y-m-d h:i:s', $focus_end_date_range),
						'su'=> $focus_start_date_range, 
						'eu'=> $focus_end_date_range,
					),
					
				), 
				$SC, $content) );
		}

	// AJAX Events data
		function ajax_events_data(){
			$postdata = EVO()->helper->sanitize_array( $_POST );

			$SC = isset($postdata['shortcode']) ? $postdata['shortcode']: array();
			EVO()->calendar->shortcode_args = $SC;
		}

	// Now Calendar
		public static function refresh_now_cal(){

			// nonce verification
			if(empty( $_POST['nonce'] ) || !wp_verify_nonce( wp_unslash( $_POST['nonce'] ), 'eventon_nonce')) {
				wp_send_json_error( 'bad_nonce' );
				wp_die();
			}

			$post_data = EVO()->helper->sanitize_array( $_POST );

			$calnow = new Evo_Calendar_Now();

			$SC = isset($post_data['SC']) ? $post_data['SC']: array();
			
			$defA = isset($post_data['defA']) ? $post_data['defA'] : array();

			$args = array_merge($defA, $SC);

			$calnow->process_a( $args );

			ob_start();
			$calnow->get_body( true );
			$html = ob_get_clean();

			wp_send_json(
				array(
					'status'=>'good',
					'html'=> $html,
				)
			);
		}

	// refresh elements
		public static function refresh_elm(){

			// nonce verification
			if(empty( $_POST['nonce'] ) || !wp_verify_nonce( wp_unslash( $_POST['nonce'] ), 'eventon_nonce')) {
				wp_send_json_error( 'bad_nonce' );
				wp_die();
			}

			$PP = EVO()->helper->sanitize_array( $_POST );
			wp_send_json( self::get_refresh_elm_data( $PP ));	
		}

		//get ajax refresh element's data array
		public static function get_refresh_elm_data($PP, $type ='ajax'){
			$response = array();

			if(isset($PP['evo_data']) && is_array($PP['evo_data']) ){
				
				foreach($PP['evo_data'] as $ekey=>$classes){

					$ee = explode('_', $ekey);
					$EVENT = new EVO_Event($ee[0], '', (int)$ee[1] );

					foreach( $classes as $classnm=>$classdata){

						$response['evo_data'][ 'event_'.$EVENT->ID.'_'.$EVENT->ri][$classnm] = apply_filters('evo_ajax_refresh_elm', 
							array(
								'html'=> '',
								'data'=> $classdata,
							), $EVENT, $classnm, $classdata, $type, $PP
						);

						$response['status'] = 'good';
					}

					$response['evo_data'][ 'event_'.$EVENT->ID.'_'.$EVENT->ri] = apply_filters('evo_ajax_refresh_event_elms', 
						$response['evo_data'][ 'event_'.$EVENT->ID.'_'.$EVENT->ri],
						$EVENT , $classes, $type, $PP
					);
					
				}									
			}
			return $response;
		}

	// record moderator joined for virtual events
		public static function record_mod_joined(){			

			if(!isset($_POST['eid'])) return false;
			if(!isset($_POST['ri'])) return false;
			if(!isset($_POST['nonce'])) return false;
			if(!isset($_POST['joined'])) return false;

			if(!wp_verify_nonce($_POST['nonce'], 'eventon_nonce')) {echo 'nonce failed'; exit;}

			$postdata = EVO()->helper->sanitize_array( $_POST );

			$EVENT = new EVO_Event( (int)$postdata['eid'], (int)$postdata['eid'] );

			// joined in or left
			$joined = ($postdata['joined'] == 'yes') ? 'in': 'left';

			$EVENT->record_mod_joined($joined);

			wp_send_json(array(
				'status'=>'good',
				'msg'=> ( $joined =='in' ? __('Recorded as moderator joined','eventon') : 
						__('Recorded as moderator left','eventon') )
			));
		}


	// Load single event content
	// @2.6.13 
		public static function load_event_content(){

			// nonce verification
			if(empty( $_POST['nonce'] ) || !wp_verify_nonce( wp_unslash( $_POST['nonce'] ), 'eventon_nonce')) {
				wp_send_json_error( 'bad_nonce' );
				wp_die();
			} 

			$post_data = EVO()->helper->recursive_sanitize_array_fields( $_POST );

			if(!isset($post_data['eid'])) return false;
			if(!isset($post_data['nonce'])) return false;			

			$EVENT = new EVO_Event( (int)$post_data['eid']);

			wp_send_json(
				apply_filters('evo_single_event_content_data',array(), $EVENT)
			);
		}

	// load single eventcard content
	// @ 2.9.2 @u 4.4.4
		public static function load_single_eventcard_content(){

			// nonce verification
			if(empty( $_POST['nn'] ) || !wp_verify_nonce( wp_unslash( $_POST['nn'] ), 'eventon_nonce')) {
				wp_send_json_error( 'bad_nonce' );
				wp_die();
			} 

			$postdata = EVO()->helper->sanitize_array( $_POST );

			$event_id = (int) $postdata['event_id'];
			$ri = (int) $postdata['ri'];

			$SC = array();
			if( isset($postdata['SC']) ) $SC = $postdata['SC'];
			if( isset($postdata['sc']) ) $SC = $postdata['sc'];

			$lang = isset($SC['lang'])? $SC['lang']:'L1';

			$SC['show_exp_evc'] = 'yes';
			
			$event_data = EVO()->calendar->get_single_event_data( $event_id, $lang, $ri, $SC);

			if($event_data && is_array($event_data)) $event_data = $event_data[0];

			$event_html_content = $event_data['content'];

			unset($event_data['content']);

			wp_send_json(
				apply_filters('evoajax_single_eventcard_data',
					array(
						'status'=>'good',
						'content'=> $event_html_content, 
						'data'=> $event_data
					),
					$event_data, $postdata
				)
			); 
		}

	// OUTPUT: json headers
		private function json_headers() {		header( 'Content-Type: application/json; charset=utf-8' );	}



	// ICS file generation for add to calendar buttons
	// @updated 4.3
		public static function eventon_ics_download(){

			if( !isset( $_GET['event_id'])) return false;

			// verify nonce - temp commented in 4.4.1
				//if(!wp_verify_nonce($_REQUEST['nonce'], 'eventon_ics_oneevent')) die('Security Check Failed!');

			$event_id = (int)( sanitize_text_field( $_GET['event_id']) );
			$ri = isset($_GET['ri'])? (int)( sanitize_text_field($_GET['ri']) ) : 0;

			$EVENT = new EVO_Event($event_id,'',$ri);
			$EVENT->get_event_post();
			
			// validations
				// check post type
				if( 'ajde_events' !== $EVENT->post_type ) die('Not a valid Event!');

				// check event exists
				if( $EVENT->post_status != 'publish' && !is_user_logged_in() ) die('Not a valid Event!');

				// check password protected event
				if( $EVENT->is_password_required() ) die('Password Protected Event!');		
			
			$slug = $EVENT->post_name;
						
			header("Content-Type: text/Calendar; charset=utf-8");
			header("Content-Disposition: inline; filename={$slug}.ics");

			echo "BEGIN:VCALENDAR\r\n";
			echo "VERSION:2.0\r\n";
			echo "PRODID:-//eventon.com NONSGML v1.0//EN\n";

			echo $EVENT->get_ics_content();

			echo "END:VCALENDAR";
			
			wp_die();

		}

	// download all event data as ICS
	// @updated 4.3
		public static function export_events_ics(){
			
			if(!wp_verify_nonce($_REQUEST['nonce'], 'eventon_download_events')) die('Nonce Security Failed.');

			$events = EVO()->calendar->get_all_event_data(array(
				'hide_past'=>'yes'
			));
			
			if(!empty($events)):

				$slug = 'eventon_events';
				header("Content-Type: text/Calendar; charset=utf-8");
				header("Content-Disposition: inline; filename={$slug}.ics");
				echo "BEGIN:VCALENDAR\n";
				echo "VERSION:2.0\n";
				echo "PRODID:-//eventon.com NONSGML v1.0//EN\n";
				echo "CALSCALE:GREGORIAN\n";
				echo "METHOD:PUBLISH\n";

				// EACH EVENT
				foreach($events as $event_id=>$event){

					$EVENT = new EVO_Event( $event_id, $event['pmv'], 0, true, false);

					echo $EVENT->get_ics_content();

				}
				echo "END:VCALENDAR";
				wp_die();

			endif;
		}

	// get event time based on local time on browswr
		public static function get_local_event_time(){
			$post_data = EVO()->helper->recursive_sanitize_array_fields( $_POST );
			
			$datetime = new evo_datetime();
			$offset = $datetime->get_UTC_offset();
			$brosweroffset = (int)$post_data['browser_offset'] *60;
			echo $brosweroffset.' '.$offset.' '.$object->evvals['evcal_srow'][0];

			$newunix = $object->evvals['evcal_srow'][0] + ($offset + $brosweroffset);
			echo date('Y-m-d h:ia', $newunix);
			wp_die();
		}


	// Search results for ajax search of events from search box
	// u 4.5.8
		public static function search_evo_events(){

			// nonce verification
			if(empty( $_POST['nonce'] ) || !wp_verify_nonce( wp_unslash( $_POST['nonce'] ), 'eventon_nonce')) {
				wp_send_json_error( 'bad_nonce' );
				wp_die();
			}
			
			$postdata = EVO()->helper->sanitize_array( $_POST );

			$searchfor = isset($postdata['search']) ? $postdata['search'] :'';
			$shortcode = isset($postdata['shortcode']) ? $postdata['shortcode']: array();
		
			$searchfor = str_replace("'","\'", $searchfor);

			
			// if search all events regardless of date
				if( !empty($shortcode['search_all'] ) && $shortcode['search_all']=='yes'){
					$DD = EVO()->calendar->DD;
					$DD->modify('first day of this month'); $DD->setTime(0,0,0);
					$DD->modify('-15 years');
					
					$__focus_start_date_range = $DD->format('U');
					
					$DD->modify('+30 years');
					$__focus_end_date_range = $DD->format('U');
				
				}else{	

					$DD = new DateTime();
					$DD->setTimezone( EVO()->calendar->cal_tz );
					$DD->setTimestamp( EVO()->calendar->current_time );

					$__focus_start_date_range = $DD->format('U');


					$current_timestamp = current_time('timestamp');

					// send end range based on number of months
						$number_of_months = !empty($shortcode['number_of_months'])? $shortcode['number_of_months']:12;

						$DD->modify( '+' . $number_of_months . 'months' );

						$__focus_end_date_range = $DD->format( 'U' );
				}
			

			// Add extra arguments to shortcode arguments			
				$new_arguments = array(
					'focus_start_date_range'=>$__focus_start_date_range,
					'focus_end_date_range'=>$__focus_end_date_range,
					's'=>$searchfor,
					'search_all'=> (isset($shortcode['search_all'])? $shortcode['search_all']:'no')
				);

				$args = (!empty($args) && is_array($args))? 
					wp_parse_args($new_arguments, $args): $new_arguments;

				// merge passed shortcode values
					if(!empty($shortcode))
						$args= wp_parse_args($shortcode, $args);

				EVO()->calendar->process_arguments($args);

				$content = EVO()->calendar->body->get_calendar_header(
					array(
						'date_header'=>false,
						'sortbar'=>true,
						'range_start'=>$__focus_start_date_range,
						'range_end'=>$__focus_end_date_range,
						'header_title'=>'',
						'send_unix'=>true
					)
				);

				$content .= EVO()->calendar->_generate_events('html');
				
				$content .= EVO()->calendar->body->get_calendar_footer();
				
				wp_send_json(array(
					'content'=>$content,
					'range'=> date('Y-m-d', $__focus_start_date_range).' '.date('Y-m-d', $__focus_end_date_range)
				));
				wp_die();
		}

	// deprecating
	public static function general_send_req($a1, $post){
		return $a1;
	}

}

EVO_AJAX::init();
