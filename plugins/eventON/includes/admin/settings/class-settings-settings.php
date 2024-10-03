<?php
/**
  * evo settings class
  * @version 4.6.3
  */
class evo_settings_settings{
	private $evcal_opt;
	function __construct($evcal_opt)	{		
		$this->evcal_opt = $evcal_opt;
	}

	function content(){

		$preset_data = EVO()->elements->get_def_css();

		$help = new evo_helper();

		// google maps styles description
		$gmaps_desc = '<span class="evo_gmap_styles" data-url="'.AJDE_EVCAL_URL.'/assets/images/ajde_backender/"></span>';

		return apply_filters('eventon_settings_tab1_arr_content', array(
			array(
				'id'=>'evcal_001',
				'name'=>__('General Calendar Settings','eventon'),
				'display'=>'show',
				'icon'=>'gears',
				'tab_name'=>__('General Settings','eventon'),
				'top'=>'4',
				'fields'=> apply_filters('eventon_settings_general', array(
					array('id'=>'evcal_cal_hide','type'=>'yesno','name'=>__('Hide Calendars from front-end','eventon'),),
					
					//array('id'=>'evcal_only_loggedin','type'=>'yesno','name'=>__('Show calendars only to logged-in Users','eventon'),),
					
									
					//array('id'=>'evcal_dis_conFilter','type'=>'yesno','name'=>__('Disable Content Filter','eventon'),'legend'=>__('This will disable to use of the_content filter on event details and custom field values.','eventon')),				
					

					array('id'=>'evcal_header_generator',
						'type'=>'yesno',
						'name'=>__('Remove eventon generator meta data from website header','eventon'), 
						'legend'=>__('Remove the meta data eventon place on your website header with eventon version number for debugging purposes','eventon')),
					
					array('id'=>'evo_rtl',
						'type'=>'yesno',
						'name'=>__('Enable RTL (right-to-left all eventon calendars)','eventon'), 
						'legend'=>__('This will make all your eventon calendars RTL.','eventon')),

					array('id'=>'evo_hide_shortcode_btn',
						'type'=>'yesno',
						'name'=>__('Hide add eventon shortcode generator button from wp-admin','eventon'), 
						'legend'=>__('This will remove the [] ADD EVENTON button that appear above text editor next to media button. This button allow you to open shortcode generator to create eventon shortcodes easily.','eventon')
					),
					array('id'=>'evo_lang_corresp',
						'type'=>'yesno',
						'name'=>__('Enable language corresponding events','eventon'), 
						'legend'=>__('This will allow you to create events only for L1, L2 etc. and show only those events in calendars specified as lang=L2 etc.','eventon')
					),
					
					array('id'=>'evo_login_link',
						'type'=>'text',
						'name'=>__('URL for custom login link','eventon'), 
						'legend'=>__('If provided this URL will be used instead of default wordpress URL for users to login where eventon access is restricted to only login users.','eventon','eventon'),
						'default'=>'https://'
					),
										
					array('type'=>'sub_section_open','name'=>__('Search Engine Structured Data' ,'eventon')),
						array('id'=>'evo_schema','type'=>'yesno','name'=>__('Remove schema data from calendar','eventon'), 'legend'=>__('Schema microdata helps in google and other search engines find events in special event data format. With this option you can remove those microdata from showing up on front-end calendar.','eventon'),'afterstatement'=>'evo_schema'),

							array('id'=>'evo_schema','type'=>'begin_afterstatement'),
							array('id'=>'evcal_schema_disable_section','type'=>'radio','name'=>__('Select where in your site you would like the schema data to be removed from','eventon'),'width'=>'full',
								'options'=>array(
									'everywhere'=>__('Everywhere in the site','eventon'),
									'single'=>__('Everywhere except single event pages','eventon'))
							),
							array('id'=>'evo_schema','type'=>'end_afterstatement'),
						array('id'=>'evo_remove_jsonld',
							'type'=>'yesno',
							'name'=>__('Remove JSON-LD data for events','eventon'), 
							'legend'=>__('This will remove JSON-LD structured data scripts added for each event.','eventon'),
							'afterstatement'=>'evo_remove_jsonld'
						),
							array('id'=>'evo_remove_jsonld','type'=>'begin_afterstatement'),
							array('id'=>'evo_remove_jsonld_section','type'=>'radio',
								'name'=>__('Select where in your site you would like the schema data to be removed from','eventon'),'width'=>'full',
								'options'=>array(
									'everywhere'=>__('Everywhere in the site','eventon'),
									'single'=>__('Everywhere except single event pages','eventon'))
							),
							array('id'=>'evo_remove_jsonld','type'=>'end_afterstatement'),

					array('type'=>'sub_section_close'),

					// no event settings
					array('type'=>'sub_section_open','name'=>__('No Event View Settings' ,'eventon')),

						array('id'=>'evo_noevent_set',
							'type'=>'dropdown',
							'name'=>__('Select display styles for no event view','eventon'), 
							'options'=>array(
								'default'=>__('No events text string','eventon'),
								'button'=>__('Clickable Button','eventon'),
								'button_sub'=>__('Clickable Button with subtitle','eventon')
							),
							'legend'=>__('Select how you would like the calendar to display when there are no events on the current month or date range. You can use this space to engage visitors in creative ways.','eventon')
						),
						array('id'=>'evo_noevent_btn_action',
							'type'=>'dropdown',
							'name'=>__('Optional button action','eventon'), 
							'options'=> apply_filters('evo_settings_noevent_btn_actions', array(
								'none'=>__('Not do anything','eventon'),
								'link'=>__('Link to custom URL','eventon')
							)),
							'legend'=>__('Select what action the clickable button should perform when clicked.','eventon')
						),
						array('id'=>'evo_noevent_link',
							'type'=>'text',
							'name'=>__('Optional no event button action custom URL','eventon'),
							'legend'=>__('Type in a complete URL for no event button action link.','eventon'),
							'default'=>'https://'
						),
						array('id'=>'evo_note',
							'type'=>'note',
							'name'=>sprintf(__('NOTE: Text used in the no event content can be translated via EvnetON Language Settings' ,'eventon'))
						),

					array('type'=>'sub_section_close'),
					
					// settings and data management
					array('type'=>'sub_section_open','name'=>__('Settings & Data Management' ,'eventon')),

						array('id'=>'evo_delete_settings',
							'type'=>'yesno',
							'name'=>__('Delete eventon settings & data when EventON is uninstalled','eventon'), 
							'legend'=>__('Enabling this will DELETE eventON settings and event post data when you uninstall eventON from this website. By default eventON settings and data are not deleted from database (when plugin is uninstalled)','eventon')),

					array('type'=>'sub_section_close'),
					
										
					array('type'=>'sub_section_open','name'=>__('Additional EventON Settings' ,'eventon')),

						array('id'=>'evcal_export',
							'type'=>'customcode',
							'code'=>$this->export()),

						array('id'=>'evo_disable_csv_formatting',
							'type'=>'yesno',
							'name'=>__('Disable CSV export event formatting','eventon'), 
							'legend'=>__('This will disable CSV export all event formatting characters..','eventon'),
						),
						array('id'=>'evo_dis_icshtmldecode',
							'type'=>'yesno',
							'name'=>__('Disable ICS file special character encoding','eventon'), 
							'legend'=>__('This will disable html special character dencoding for all ics downloaded files for events','eventon')
						),
						array('id'=>'_evo_email_encode','type'=>'dropdown','name'=>__('Select the email content type character encoding type','eventon'),'width'=>'full',
							'legend'=>__('Using a higher UTF encoding may slow things down.','eventon'),
							'options'=>array(
								'def'=>__('None/Default','eventon'),
								'utf8'=>__('UTF-8','eventon'),
								'utf16'=>__('UTF-16','eventon')
							),
						),
					array('type'=>'sub_section_close'),


					array('id'=>'evcal_additional',
						'type'=>'note',
						'name'=>sprintf(__('Looking for additional functionality including event tickets, frontend event submissions, RSVP to events, photo gallery and more? <br/><a href="%s" style="margin-top:5px;"target="_blank" class="evo_admin_btn btn_triad">Check out eventON addons</a>' ,'eventon'), 'http://www.myeventon.com/addons/')
					),
			))),
			
			array(
				'id'=>'evcal_005',
				'name'=>__('Google Maps API Settings','eventon'),
				'tab_name'=>__('Google Maps API','eventon'),
				'icon'=>'map-marker',
				'fields'=>array(
					array('id'=>'evcal_cal_gmap_api',
						'type'=>'yesno',
						'name'=>__('Disable Google Maps API','eventon'),
						'legend'=>'This will stop gmaps API from loading on frontend and will stop google maps from generating on event locations.',
						'afterstatement'=>'evcal_cal_gmap_api'),
					array('id'=>'evcal_cal_gmap_api','type'=>'begin_afterstatement'),
					array('id'=>'evcal_gmap_disable_section','type'=>'radio','name'=>__('Select which part of Google gmaps API to disable','eventon'),'width'=>'full',
						'options'=>array(
							'complete'=>__('Completely disable google maps','eventon'),
							'gmaps_js'=>__('Google maps javascript file only (If the API js file is already loaded with another gmaps program)','eventon'))
					),
					array('id'=>'evcal_cal_gmap_api','type'=>'end_afterstatement'),
					
					
					array('id'=>'evo_gmap_api_key','type'=>'text','name'=>__('Google maps API Key (Required)','eventon').
						' <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">'. __('How to get an API Key','eventon').'</a> / ' .
						' <a href="https://developers.google.com/maps/documentation/javascript/get-api-key#restrict_key" target="_blank">'. __('Find out how to restrict this API key','eventon').'</a>',
						'legend'=>__('Not required with Gmap API V3, but typing a google maps API key will append the key and will enable monitoring map loading activity from google.','eventon'),
						'afterstatement'=>'evcal_cal_gmap_api'),
					array('id'=>'evcal_gmap_scroll','type'=>'yesno',
						'name'=>__('Disable scrollwheel zooming on Google Maps','eventon'),
						'legend'=>__('This will stop google maps zooming when mousewheel scrolled.','eventon')
					),
					
					array('id'=>'evcal_gmap_format', 'type'=>'dropdown','name'=>__('Google maps display type:','eventon'),
						'options'=>array(
							'roadmap'=>__('ROADMAP Displays the normal default 2D','eventon'),
							'satellite'=>__('SATELLITE Displays photographic tiles','eventon'),
							'hybrid'=>__('HYBRID Displays a mix of photographic tiles and a tile layer','eventon'),
							'terrain'=>__('TERRAIN Displays a physical map based on terrain information','eventon'),
						)),
					array('id'=>'evcal_gmap_zoomlevel', 'type'=>'dropdown','name'=>__('Google starting zoom level:','eventon'),
						'desc'=>__('18 = zoomed in (See few roads), 7 = zoomed out. (See most of the country)','eventon'),
						'options'=>array(
							'18'=>'18',
							'16'=>'16',
							'14'=>'14',
							'12'=>'12',
							'10'=>'10',
							'8'=>'8',
							'7'=>'7',
						)),
					array('id'=>'evcal_gmap_style', 'type'=>'dropdown','name'=>__('Map Style','eventon'),
						'desc'=>$gmaps_desc,
						'options'=>apply_filters('evo_settings_map_styles_selection',array(
							'default'=>__('Default','eventon'),
							'apple'=>'Apple Maps-esque',
							'avacado'=>'Avacado World',
							'bentley'=>'Bentley',
							'blueessence'=>'Blue Essence',
							'bluewater'=>'Blue Water',
							'coolgrey'=>'Cool Grey',
							'hotpink'=>'Hot Pink',
							'muted'=>'Muted Monotone',
							'paleretrogold'=>'Pale Retro Gold',
							'richblack'=>'Rich Black',
							'shift'=>'Shift Worker',
							'vintageyellowlight'=>'Vintage Yellow Light',	
						))
					),
					array('id'=>'evo_gmap_iconurl','type'=>'text','name'=>__('Custom map marker icon complete http url','eventon'),
						'legend'=> __('Type a complete http:// url for a PNG image that can be used instead of the default red google map markers.','eventon'),'default'=>'eg. http://www.site.com/image.png'
					),

					array('id'=>'evo_hide_location',
						'type'=>'yesno',
						'name'=>__('Make all event location information visible only to logged-in users','eventon'), 
						'legend'=>__('This will make all the event location infor visible only to loggedin users. This option will override individual event values set for this feature.','eventon')),
					array('id'=>'evo_gen_map',
						'type'=>'yesno',
						'name'=>__('Enable generate google maps from address for all newly created events, by default','eventon'), 
						'legend'=>__('When you are creating a new event the option to generate google map from address will be turned on by default.','eventon')),
			)),

			array(
				'id'=>'evcal_001b',
				'name'=>__('Time & Date Related Settings','eventon'),
				'icon'=>'clock-o',
				'tab_name'=>__('Time Settings','eventon'),
				'fields'=> apply_filters('eventon_settings_time', array(
					
					array('type'=>'sub_section_open','name'=>__('General Time/Date Settings','eventon')),
						array('id'=>'evo_global_tzo','type'=>'dropdown','name'=>__('Default Event Timezone','eventon'),'width'=>'full',
							'options'=> $help->get_timezone_array(),
							'default'=>'UTC',
							'legend'=> __('Calendar will be created using this timezone and all the events will be added relative to this timezone','eventon')
						),
						array('id'=>'evo_tzo_all','type'=>'yesno',
							'name'=>__('Apply default timezone to all events','eventon'), 
							'legend'=>__('Setting this will apply the above set default timezone to all the events unless a different timezone is set via event','eventon')
						),
						
						array('id'=>'evcal_cal_hide_past','type'=>'yesno','name'=>__('Hide past events for default calendar(s)','eventon'),'afterstatement'=>'evcal_cal_hide_past'),	
												
						array('id'=>'evcal_cal_hide_past','type'=>'begin_afterstatement'),
						array('id'=>'evcal_past_ev','type'=>'radio','name'=>__('Select a precise timing for the cut off time for past events','eventon'),'width'=>'full',
							'options'=>array(
								'local_time'=>__('Hide events past current local time','eventon'),
								'today_date'=>__('Hide events past today\'s date','eventon'))
						),
						
						array('id'=>'evcal_cal_hide_past','type'=>'end_afterstatement'),	
						array('id'=>'evo_foot_tz','type'=>'yesno',
							'name'=>__('Show calendar timezone in footer','eventon'), 
							'legend'=>__('This will show calendar timezone at the bottom of each calendar.','eventon')
						),
					array('type'=>'sub_section_close'),
	

					array('type'=>'sub_section_open','name'=>__('Front-end Time/Date Settings','eventon')),
						array('id'=>'evcal_header_format',
							'type'=>'text',
							'name'=>__('Calendar Header month/year format. <i>(<b>Allowed values:</b> m = month name, Y = 4 digit year, y = 2 digit year)</i>','eventon') , 
							'default'=>'m, Y'
						),
						array('id'=>'evo_usewpdateformat','type'=>'yesno',
							'name'=>__('Use WP default Date format in eventON calendar (Excluding eventCard event date format)','eventon'), 
							'legend'=>__('Select this option to use the default WP Date format through out eventON calendar parts excluding eventCard main date format. Default format: yyyy/mm/dd','eventon')),
											
						array('id'=>'evo_timeF','type'=>'yesno','name'=>__('Enable custom event time format on eventCard','eventon'),
							'legend'=>__('This will change the time format on eventCard to be a universal set format regardless of the month events span for.','eventon'),
								'afterstatement'=>'evo_timeF'),
							array('id'=>'evo_timeF','type'=>'begin_afterstatement'),
							array('id'=>'evo_timeF_v','type'=>'text','name'=>__('Date Format','eventon'), 'default'=>'F j(l)'),
							array('id'=>'evo_timeF_tf','type'=>'text','name'=>__('Time Format','eventon').' (Still in work)', 'default'=> EVO()->calendar->time_format ),
							array('id'=>'evcal_api_mu_note','type'=>'note',
								'name'=>__('Refer to guide for acceptable date/time format values: php <a href="http://php.net/manual/en/function.date.php" target="_blank">date()</a> Use \ to add special characters eg. \a\t H:i','eventon')),
							array('id'=>'evo_timeF','type'=>'end_afterstatement'),

						array('id'=>'evo_show_localtime','type'=>'yesno',
							'name'=>__('Enable "View in my time" button on frontend events. So users can localize event time','eventon'), 
							'legend'=>__('This will add a button in eventtop and eventcard next to event time to allow users to view the event time in their local time. A correct event timezone must be set for this to work.','eventon')
						),
						array('id'=>'evo_gmt_hide','type'=>'yesno',
								'name'=>__('Hide GMT value from frontend','eventon'), 
								'legend'=>__('Setting this will hide GMT value from every where on the event calendar events','eventon')
							),

						array('id'=>'evo_eventtop_progress_hide','type'=>'yesno','name'=>__('Hide live event progress bar with time remaining','eventon'),'legend'=>__('Enabling this will hide the live event progress bar on event top','eventon')),
						array('id'=>'evo_hide_live','type'=>'yesno','name'=>__('Hide blinking "Live Now" icon from event top for current events','eventon'),'legend'=> __('This will hide the blinking live now icon, when events are live at current time.','eventon')),

					array('type'=>'sub_section_close'),


					array('type'=>'sub_section_open','name'=>__('Back-end Time/Date Settings','eventon')),
						array('id'=>'evo_minute_increment','type'=>'dropdown','name'=>__('Select minute increment for time select in event edit page','eventon'),'width'=>'full',
							'options'=>array(
								'60'=>'1','12'=>'5','6'=>'10','4'=>'15','2'=>'30'
							)
						),
						array('id'=>'evo_time_offset','type'=>'text',
							'name'=>__('Custom eventon only time offset value (in minutes)','eventon'), 
							'legend'=>__('If the iCS download time or add to calendar time is off by some time use this to fix that offset number. You can use +/- with time in minutes','eventon'),
							'default'=>'eg. +120'),
					array('type'=>'sub_section_close'),
					
			))),
			array(
				'id'=>'evcal_001a',
				'name'=>__('Calendar front-end Sorting and filtering options','eventon'),
				'tab_name'=>__('Sorting and Filtering','eventon'),
				'icon'=>'filter',
				'fields'=>array(
					array('id'=>'evcal_hide_sort','type'=>'yesno',
						'name'=>__('Globally Hide both Sort and Filter Icons and Bar on Calendar','eventon'),
						'legend'=> __('Hide both sort and filter capability on calendar. This will override hide_so value set via shortcode.','eventon')
					),
					array('id'=>'evcal_hide_filter_icons','type'=>'yesno',
						'name'=>__('Globally Hide Item Icons from Filter Dropdown Selection Menu','eventon'),
						'legend'=> __('This will hide the icons next to each items in filter dropdown menu.','eventon')
					),
					array('id'=>'evcal_sort_options', 'type'=>'checkboxes','name'=>__('Event sorting options to show on Calendar <i>(Note: Event Date is default sorting method.)</i>','eventon'),
						'options'=>array(
							'title'=>__('Event Main Title','eventon'),
							'color'=>__('Event Color','eventon'),
							'posted'=>__('Event Posted Date','eventon'),
						)),
					array('id'=>'evcal_filter_options', 'type'=>'checkboxes','name'=>__('Event filtering options to show on the calendar</i>','eventon'),
						'options'=>$this->event_type_options()
					),
			)),
			array(
				'id'=>'evcal_002',
				'name'=>__('General Frontend Calendar Appearance','eventon'),
				'tab_name'=>__('Appearance','eventon'),
				'icon'=>'eye',
				'fields'=>$this->appearance()
			),
			array(
				'id'=>'evcal_002sc',
				'name'=>__('Calendar Scripting and styles','eventon'),
				'tab_name'=>__('Scripts & Styling','eventon'),
				'icon'=>'eye',
				'fields'=>$this->scripts()
			),
			array(
				'id'=>'evcal_004',
				'name'=>__('Custom Icons for Calendar','eventon'),
				'tab_name'=>__('Icons','eventon'),
				'icon'=>'diamond',
				'fields'=> apply_filters('eventon_custom_icons', array(
					
					array('id'=>'evcal_sh001',
						'type'=>'subheader',
						'name'=>__('Icon Selections','eventon')),
					array('id'=>'fs_fonti2','type'=>'fontation','name'=>__('EventCard Icons','eventon'),
						'variations'=>array(
							array('id'=>'evcal__ecI', 'type'=>'color', 'default'=>$preset_data['evo_color_2']),
							array('id'=>'evcal__ecIz', 'type'=>'font_size', 'default'=>'18px'),
						)
					),

					
					
					array('id'=>'evcal__fai_001','type'=>'icon','name'=>__('Event Details Icon','eventon'),'default'=>'fa-align-justify'),
					array('id'=>'evcal__fai_002','type'=>'icon','name'=>__('Event Time Icon','eventon'),'default'=>'fa-clock'),
					array('id'=>'evcal__fai_repeats','type'=>'icon','name'=>__('Event Repeat Icon','eventon'),'default'=>'fa-repeat'),
					array('id'=>'evcal__fai_vir','type'=>'icon','name'=>__('Virtual Event Icon','eventon'),'default'=>'fa-globe'),
					array('id'=>'evcal__fai_health','type'=>'icon','name'=>__('Health Guidelines Icon','eventon'),'default'=>'fa-heartbeat'),
					array('id'=>'evcal__fai_003','type'=>'icon','name'=>__('Event Location Icon','eventon'),'default'=>'fa-map-marker'),
					array('id'=>'evcal__fai_004','type'=>'icon','name'=>__('Event Organizer Icon','eventon'),'default'=>'fa-microphone'),
					array('id'=>'evcal__fai_005','type'=>'icon','name'=>__('Event Capacity Icon','eventon'),'default'=>'fa-tachometer'),
					array('id'=>'evcal__fai_006','type'=>'icon','name'=>__('Event Learn More Icon','eventon'),'default'=>'fa-link'),
					array('id'=>'evcal__fai_relev','type'=>'icon','name'=>__('Related Events Icon','eventon'),'default'=>'fa-calendar-plus'),
					array('id'=>'evcal__fai_007','type'=>'icon','name'=>__('Event Ticket Icon','eventon'),'default'=>'fa-ticket'),
					array('id'=>'evcal__fai_008','type'=>'icon','name'=>__('Add to your calendar Icon','eventon'),'default'=>'fa-calendar-o'),
					array('id'=>'evcal__fai_008a','type'=>'icon','name'=>__('Get Directions Icon','eventon'),'default'=>'fa-route'),
				))
			)
			// event top
			,
			'evcal_004aa'=> array(
				'id'=>'evcal_004aa',
				'name'=>__('EventTop Settings (EventTop is the event row on calendar)','eventon'),
				'tab_name'=>__('EventTop','eventon'),
				'icon'=>'columns',
				'fields'=>array(
					array('type'=>'sub_section_open','name'=>__('EventTop Designer','eventon')),
					array('type'=>'note','name'=>__('NOTE: Custom Meta Field values will be formatted with wp_kses() to avoid breaking the structure of eventTop DOM. ','eventon')),
					array('id'=>'evcal__note','type'=>'customcode','code'=>$this->eventtop_meta_fields()),
					array('type'=>'sub_section_close'),

					array('id'=>'evcal_top_fields', 'type'=>'checkboxes','name'=>__('EventTop Main Day block fields','eventon'),
							'options'=> apply_filters('eventon_eventop_dayblock_fields', $this->eventtop_dayblock_settings()),
					),
					array('id'=>'evotop_location','type'=>'dropdown',
						'name'=>__('Select Event Top location display data','eventon'),
						'legend'=>'Set which event location information you would like to show in location data field',
						'options'=>array(
							'location'=>'Location Address',					
							'locationame'=>'Location Name',					
							'both'=>'Both',
						),'default'=>'both',		
					),
					array('id'=>'evo_widget_eventtop','type'=>'yesno','name'=>__('Display all these fields in widget as well','eventon'),'legend'=>__('By default only few of the data is shown in eventtop in order to make that calendar look nice on a widget where space is limited.','eventon')),
					array('id'=>'evcal_eventtop','type'=>'note','name'=>__('NOTE: Lot of these fields are NOT available in Tile layout. Reason: we dont want to potentially break the tile layout and over-crowd the clean design aspect of tile boxes.','eventon')),
										
					array('id'=>'evo_eventtop_customfield_icons','type'=>'yesno','name'=>__('Show event custom meta data icons on eventtop','eventon'),'legend'=>__('This will show event custom meta data icons next to custom data fields on eventtop, if those custom data fields are set to show on eventtop above and if they have data and icons set.','eventon')),
				
					array('id'=>'evo_showeditevent','type'=>'yesno','name'=>__('Show edit event button for each event','eventon'),'legend'=> __('This will show an edit event button on eventTop - only for admin - that will open in a new window edit event page. Works only for lightbox and slideDown interaction methods.','eventon')),

					

					array('id'=>'evo_eventtop_style_def','type'=>'dropdown',
						'name'=>__('Select Default Calendar EventTop Style','eventon'),
						'legend'=>'This will set this as the default eventTop style, if not set via shortcode var eventtop_style',
						'options'=>array(
							'_2'=>'Colorful with gap between events',					
							'_1'=>'Colorful EventTop',					
							'_3'=>'Colorful event date bubbles',
							'_0'=>'Clear with left border colors',
							'_4'=>'Clear with left border colors and gaps',
						),'default'=>'_2',		
					),
					array('id'=>'evo_eventtop_org_link','type'=>'dropdown',
						'name'=>__('Select EventTop Organizer click action','eventon'),
						'legend'=>'Set how you want the organizer name on eventtop to interact when clicked on.',
						'options'=>array(
							'0'=>'Open more details in lightbox',					
							'1'=>'Open organizer archive page',					
							'2'=>'Open organizer learn more link, if available',
							'x'=>'Do nothing',
						),'default'=>'0',	
						'ver'=> '4.5',	
					),
					array('id'=>'evo_etop_tags', 'type'=>'checkboxes',
						'name'=>__('Select below EventTop tags to HIDE (Tags you selected below will be hidden from view on frontend.)','eventon'),
						'options'=> apply_filters('eventon_eventop_tags', $this->eventtop_tags()),
					),
				)
			)

			// event card
			,array(
				'id'=>'evcal_004a',
				'name'=>__('EventCard Settings (EventCard is the full event details card)','eventon'),
				'tab_name'=>__('EventCard','eventon'),
				'icon'=>'list-alt',
				'fields'=>array(	

					array('id'=>'evcal__note','type'=>'customcode','code'=>$this->eventcard_designer_btn()),									

					array('type'=>'sub_section_open','name'=>__('Featured Image','eventon')),
						
						array('id'=>'evo_ftimg_height_sty','type'=>'dropdown','name'=>__('Feature image display style','eventon'), 'legend'=>'Select which display style you want to show the featured image on event card when event first load',
							'options'=> array(
								'def'=>__('Scaled cropped image to fit the full box','eventon'),
								'fit'=>__('Fit entire image resized to min height','eventon'),
								'full'=>__('Fit entire image, resized box height','eventon')
						)),
						/*array('id'=>'evo_ftimghover','type'=>'note','name'=>__('NOTE: Featured image display styles: Direct image style will show image as img element as oppose to the image as background image of a div element','eventon')),
						array('id'=>'evo_ftimghover','type'=>'yesno','name'=>__('Disable hover effect on featured image','eventon'),'legend'=>'Remove the hover moving animation effect from featured image on event. Hover effect is not available on Direct Image style'),
						array('id'=>'evo_ftimgclick','type'=>'yesno','name'=>__('Disable zoom effect on click','eventon'),'legend'=>'Remove the moving animation effect from featured image on click event. Zoom effect is not available in Direct Image style'),
						array('id'=>'evo_ftim_mag','type'=>'yesno','name'=>__('Show magnifying glass over featured image','eventon'),'legend'=>'This will convert the mouse cursor to a magnifying glass when hover over featured image. <br/><br/><img src="'.AJDE_EVCAL_URL.'/assets/images/admin/cursor_mag.jpg"/><br/>This is not available for Direct Image style'),
						*/

						array('id'=>'evo_ftimgheight','type'=>'text','name'=>__('Minimal height for featured image (value in pixels)','eventon'), 'default'=>'eg. 400'),
						
						array('id'=>'evcal_default_event_image_set','type'=>'yesno','name'=>__('Set default event image for events that doesnt have images','eventon'),'legend'=>__('Add a URL for the default event image URL that will be used on events that dont have featured images set.','eventon'),'afterstatement'=>'evcal_default_event_image_set'),
							array('id'=>'evcal_default_event_image_set','type'=>'begin_afterstatement'),
							array('id'=>'evcal_default_event_image','type'=>'text','name'=>__('Default event image URL','eventon') , 'default'=>'http://www.google.com/image.jpg'),
							array('id'=>'evcal_default_event_image_set','type'=>'end_afterstatement'),
					array('type'=>'sub_section_close'),

					array('type'=>'sub_section_open','name'=>__('Location Image','eventon')),
						array('id'=>'evo_locimgheight','type'=>'text','name'=>__('Set event location image height (value in pixels)','eventon'), 'default'=>'eg. 400'),
					array('type'=>'sub_section_close'),

					// Add to Calendar section
					array('type'=>'sub_section_open','name'=>__('Add to Calendar Options','eventon')),
						array('id'=>'evo_addtocal','type'=>'dropdown','name'=>__('Select which options to show for add to your calendar','eventon'),'legend'=>'Learn More & Add to your calendar field must be selected for these options to reflect on eventCard','options'=>array(
								'all'=>'All options',
								'gcal'=>'Only Google Add to Calendar',
								'ics'=>'Only ICS download event',
								'none'=>'Do not show any add to calendar options',
							)
						),
					array('type'=>'sub_section_close'),

					

					// Other EventCard Settings
					array('type'=>'sub_section_open','name'=>__('Other EventCard Settings','eventon')),
						array('id'=>'evo_ecard_lbs','type'=>'dropdown','name'=>__('EventCard Lightbox Scroll Style','eventon'),'legend'=>'Select the desired lightbox scroll style to display the eventcard.','options'=>array(
								'def'=>'Scroll entire lightbox page',
								'sc1'=>'Scroll entire eventcard within fixed page',
								'sc2'=>'Fixed eventtop & scroll eventcard within fixed page',
							)
						),				
						array('id'=>'evo_morelass','type'=>'yesno','name'=>__('Show full event description','eventon'),'legend'=>'If you select this option, you will not see More/less button on EventCard event description.'),
						
						array('id'=>'evo_opencard',
							'type'=>'yesno',
							'name'=>__('Open all eventCards by default (Except tile layout)','eventon'),
							'legend'=>'This option will load the calendar with all the eventCards open by default and will not need to be clicked to slide down and see details. This is disabled in tiles layout to maintain integrity of tile layout design.'
						),
						array('id'=>'evo_card_http_filter',
							'type'=>'yesno',
							'name'=>__('Disable location & organizer link filtering','eventon'),
							'legend'=>'Location and organizer link filter removes http & https from the link, disabling this will stop that filter from running'
						),
						array('id'=>'evo_card_loc_btn',
							'type'=>'yesno',
							'name'=>__('Hide location other events button','eventon'),
							'legend'=> __('This will hide the location other events button, on eventcard.','eventon')
						),
					array('type'=>'sub_section_close'),
				)
			)
			
			// custom meta fields
			,array(
				'id'=>'evcal_009',
				'name'=>__('Custom Meta Data fields for events','eventon'),
				'tab_name'=>__('Custom Meta Data','eventon'),
				'icon'=>'list-ul',
				'fields'=>$this->custom_meta_fields()
			)
			// event categories
			,array(
				'id'=>'evcal_010',
				'name'=>__('Event Categories','eventon'),
				'tab_name'=>__('Categories','eventon'),
				'icon'=>'sitemap',
				'fields'=>$this->event_type_categories()
			)
			// events paging
			,array(
				'id'=>'evcal_011',
				'name'=>__('Events Paging','eventon'),
				'tab_name'=>__('Events Paging','eventon'),
				'icon'=>'files-o',
				'fields'=>array(			
					array('id'=>'evcal__note','type'=>'note','name'=>__('This page will allow you to control templates and permalinks related to eventon event pages.','eventon')),
					
					array('id'=>'evo_event_archive_page_id',
						'type'=>'dropdown',
						'name'=>__('Select which page to get content from','eventon'), 
						'options'=>$this->event_pages(), 
						'desc'=>__('This will allow you to use this page with url slug /events/ as event archive page. Be sure to insert eventon shortcode in this page.','eventon')
					),
						
					array('id'=>'evo_event_slug',
						'type'=>'text',
						'name'=>__('EventOn Event Post Slug','eventon'), 
						'default'=>'events'
					),
					array('id'=>'evcal__note','type'=>'note',
						'name'=>__('NOTE: If you change the slug for events please be sure to refresh permalinks for the new single event pages to work properly..','eventon')),
					array('id'=>'evcal__note','type'=>'note',
						'name'=>__('PROTIP: If the /events page does not work due to theme/plugin conflicts, create a new page, call it <b>"Events Directory"</b> Insert eventon shortcode and use that as your main events page which will have a URL ending like /events-directory. This would be a perfect solution if you have conflicts with /events slug.','eventon')),
					array('id'=>'evo_ditch_sin_template','type'=>'yesno',
						'name'=>__('Stop using eventON single event template for single event pages','eventon'),
						'legend'=>'If you dont want eventON single events template been used for individual event pages you can enable this option to stop using single event template altogether and fall back to default theme template'),
						array('id'=>'evcal__note','type'=>'note',
							'name'=> sprintf(__('<a href="%s" target="_blank"class="evo_admin_btn btn_triad">Learn How to customize events archive page</a></br>' ,'eventon'), 'http://www.myeventon.com/documentation/how-to-customize-events-archive-page/') 
						),


					array('type'=>'sub_section_open',
						'name'=>__('Event Text String Settings','eventon'), 
						),
						array('id'=>'evo_label',
							'type'=>'note',
							'name'=>__('Below settings will allow you to change the event text strings for backend and frontend quickly. These text strings can also be translated using a translator for backend of your website.','eventon'), 
						),
						array('id'=>'evo_textstr_sin',
							'type'=>'text',
							'name'=>__('Event text string (singular text)','eventon'), 
							'default'=> __('Event','eventon'),
						),array('id'=>'evo_textstr_plu',
							'type'=>'text',
							'name'=>__('Event text string (plural text)','eventon'), 
							'default'=> __('Events','eventon'),
						),
					array('type'=>'sub_section_close'),
				)
			),array(
				'id'=>'evcal_012',
				'name'=>__('Shortcode Settings','eventon'),
				'tab_name'=>__('ShortCodes','eventon'),
				'icon'=>'code',
				'fields'=>array(			
					array('id'=>'evcal__note','type'=>'customcode','code'=>$this->content_shortcodes()),
				)
			),
			// Single Events
				array(
					'id'=>'eventon_social',
					'name'=> __('Settings for Single Event Page','eventon'),
					'display'=>'none',
					'tab_name'=> __('Single Events','eventon'),
					'icon'=>'calendar',
					'fields'=> $this->single_events()
				),

			// Advanced - moved since 4.4.1
				array(
					'id'=>'evcal_001_advance',
					'name'=>__('Advanced Settings','eventon'),
					'tab_name'=>__('Advanced Settings','eventon'),
					'icon'=>'laptop-code',
					'fields'=> apply_filters('evo_settings_advanced', array(
						array('type'=>'sub_section_open','name'=>__('WP EventON Core Settings' ,'eventon')),					
							array('id'=>'evo_content_filter','type'=>'dropdown','name'=>__('Select calendar event content filter type','eventon'),'legend'=>__('Select which method to use for processing event details and custom meta field data content for front-end.','eventon'), 'options'=>array( 
								'evo'=>__('EventON Content Filter','eventon'),
								'def'=>__('Default WordPress Filter','eventon'),
								'none'=>__('No Filter','eventon')
							)),
							array('id'=>'evo_settings_query_type',
								'type'=>'dropdown',
								'name'=>__('Select event calendar WP Query method','eventon'),
								'legend'=>__('This will set how the wp_query is run to load events. Depending on the method you choose, it may speed up calendar and restrict the events loaded into calendar based on date range. The date Event posts are created is not the same as event date.','eventon'),
								'options'=>array(
									'default'=>__('Query all the event posts','eventon'),
									'this_year'=>__('Query only the event posts created this year','eventon'),
									'12months'=>__('Query only the event posts created within last 12 months','eventon'),
									'6months'=>__('Query only the event posts created within last 6 months','eventon'),
									'last_5months'=>__('Query only the event posts created within last 5 months','eventon'),
									'last_4months'=>__('Query only the event posts created within last 4 months','eventon'),
									'last_3months'=>__('Query only the event posts created within last 3 months','eventon'),
									'last_2months'=>__('Query only the event posts created within last 2 months','eventon'),
									'this_month'=>__('Query only the event posts created this month','eventon')
								)
							),
							array('id'=>'evo_sanitize_html',
								'type'=>'yesno',
								'name'=>__('Enable sanitizing HTML content for event data','eventon'), 
								'legend'=>__('Enabling this will sanitize HTML content using evo_helper()->sanitize_html() function and remove unsupported html content. By default any HTML content is allowed.','eventon')
							),
							// communication method with server from client @4.5.5
							array('id'=>'evo_com_method',
								'type'=>'dropdown',
								'default'=>'endpoint',
								'name'=>__('Select which HTTP server communication method to use for calendar','eventon'),'legend'=>__('Endpoint only runs eventon needed codes for the most part. AJAX use admin-ajax.php url which loads most of admin codes. @since 4.5.5','eventon'), 'options'=>array( 
									'endpoint'=>__('Endpoint','eventon'),
									'ajax'=>__('AJAX','eventon'),
							)),
						array('type'=>'sub_section_close'),

						/* In the works still
						array('type'=>'sub_section_open','name'=>__('Event Indexing' ,'eventon')),
							array('id'=>'evcal__note','type'=>'customcode','code'=>$this->content_event_indexing()),
						array('type'=>'sub_section_close'),
						*/


						array('type'=>'sub_section_open','name'=>__('Autonomous Functions' ,'eventon')),

							array('id'=>'evcal_move_trash','type'=>'yesno','name'=>__('Auto move events to trash when the event date is past','eventon'), 'legend'=>__('This will move events to trash when the event end date is past current date. This action is performed daily via cron jobs. This will not be performed on repeat, month/year long events.','eventon')),
							array('id'=>'evcal_mark_completed','type'=>'yesno','name'=>__('Set all past events as completed','eventon'), 'legend'=>__('This will set all the past events as completed. This action is performed daily via cron jobs. This will not be performed on repeat, month/year long events.','eventon')),
						array('type'=>'sub_section_close'),

					))
				),

			// third party APIs
				array(
					'id'=>'evcal_003',
					'name'=>__('Third Party API Support for Event Calendar','eventon'),
					'tab_name'=>__('Third Party APIs','eventon'),
					'icon'=>'plug',
					'fields'=> apply_filters('eventon_settings_3rdparty', $this->third_party_apis() )
				),

			// search
				array(
					'id'=>'eventon_search',
					'name'=> __('Settings & Instructions for Event Search','eventon'),
					'display'=>'none','icon'=>'search',
					'tab_name'=> __('Search Events','eventon'),
					'fields'=> apply_filters('evo_sr_setting_fields', array(
						array('id'=>'evo_sr_001','type'=>'customcode',
								'code'=>$this->content_search()
						),
						array('id'=>'evosr_default_search_on',
							'type'=>'yesno',
							'name'=>'Enable Search on all calendars by default',
							'legend'=>'If you set this, search will be on calendars by default unless specify via shortcode search=no.'
						),
						array('id'=>'EVOSR_showfield',
							'type'=>'yesno',
							'name'=>'Show search text input field when calendar first load on page',
							'legend'=>'This will show the search field when the page first load instead of having to click on search button'
						),
						array('id'=>'EVOSR_advance_search',
							'type'=>'yesno',
							'name'=>'Enable additional search queries (may not work for all sites)',
							'legend'=>'This will include custom meta data, category values and comments into search query pool'
						),
						array('id'=>'EVOSR_default_search',
							'type'=>'yesno',
							'name'=>'Include events in default wordpress search results',
							'legend'=>'This will include events in default wordpress search results, be aware you may have to add custom styles to match the search results from events to rest of your results. You may also have to add custom codes to get all event information to show in event search result'
						),

					))
				),
			
			array(
				'id'=>'evcal_013',
				'name'=>__('Diagnose EventON Environment','eventon'),
				'tab_name'=>__('Diagnose','eventon'),
				'icon'=>'rocket',
				'fields'=>array(	
					array('id'=>'daig','type'=>'note',
						'name'=>__('The below options are for testing and debuging eventon environment. They can provide general guidance for verification of proper functionality of EventON features.','eventon'),
					),		
					array('id'=>'evo_label','type'=>'subheader','name'=>__('EventON & Your Website Environment Data','eventon'),),
					array('id'=>'evcal__note','type'=>'customcode','code'=>$this->environ_data()),
					array('id'=>'evo_label','type'=>'subheader','name'=>__('Emailing Functionality Testing','eventon'),),
					array('id'=>'evcal__note','type'=>'customcode','code'=>$this->content_diag()),
				)
			)
		)
		);	
	}

	// single events
		function single_events(){
			$data[] = array('type'=>'sub_section_open','name'=> __('Single Event Page','eventon') );

			$data[] = array('id'=>'evosm_disable_ogs','type'=>'yesno',
				'name'=>__('Disable auto generated OG: meta data in the single event page header.','eventon'),
				'legend'=> __('Auto generated OG: meta tags will help your website share proper information via facebook.','eventon')
			);
			$data[] = array('id'=>'evosm_1','type'=>'yesno',
				'name'=>__('Create Single Events Page Sidebar','eventon'),
				'legend'=>__('This will create a sidebar for single event page, to which you can add widgets from Appearance > Widget','eventon')
						);
			$data[] = array('id'=>'evosm_loggedin','type'=>'yesno',
				'name'=>__('Restrict single event pages to logged-in users only','eventon'), 
				'legend'=>__('Setting this will allow only logged-in users to access this page. Otherwise will be redirected to login page link set via eventON custom login page','eventon')
			);

			$data[] = array('id'=>'evosm_comments_hide',
				'type'=>'yesno',
				'name'=>__('Disable comments section on single event template page','eventon'), 
				'legend'=>__('This will hide comments box from showing on single event page','eventon')
			);	

			$data[] = array('id'=>'evosm_hide_title',
				'type'=>'yesno',
				'name'=>__('Hide event title on single event page','eventon'), 
				'legend'=>__('This will hide event title from showing on single event page only','eventon')
			);	

			$data[] = array('id'=>'evosm_show_monthyear',
				'type'=>'yesno',
				'name'=>__('Show month, year header on single event header','eventon'), 
				'legend'=>__('This will display the month and year of the event above event header.','eventon')
			);
			$data[] = array('id'=>'evosm_etc_override',
				'type'=>'yesno',
				'name'=>__('Override event color with event type color','eventon'), 
				'legend'=>__('On single event page override the event color with event type 1 color.','eventon')
			);	

			$data[] = array('id'=>'evosm_eventtop_style','type'=>'dropdown',
				'name'=>__('Select eventTop style','eventon'),
				'legend'=>'This will set the single event page eventTop style only for single event page.',
				'options'=>array(
						'immersive'=>'Immersive Flow',
						'color'=>'Colorful',
						'white'=>'Clean White',
					)
				);
			$data[] = array('id'=>'evosm_ics_link',
				'type'=>'yesno',
				'name'=>__('Disable event link in ICS file','eventon'), 
				'legend'=>__('This will adding a event link into description section of ICS file for add to calendar.','eventon')
			);	


			$data[] = array('type'=>'sub_section_close');

			$data[] = array('id'=>'evosm','type'=>'sub_section_open',
				'name'=>__('Social Media Control','eventon'));
			$data[] = array('id'=>'evosm_som','type'=>'yesno',
				'name'=>__('Show social media share icons only on single events','eventon'), 
				'legend'=>__('Setting this to Yes will only add social media share link buttons to single event page and single event box you created','eventon'));	
			$data[] = array('id'=>'evosm_diencode','type'=>'yesno',
				'name'=>__('Disable social media event link encoding for special characters','eventon'), 
				'legend'=>__('Enabling this will stop encoding the URL of event on social share options including email share.','eventon')
			);			
			$data[] = array('type'=>'sub_section_close');	

			$data[] = array('id'=>'evosm','type'=>'sub_section_open','name'=>__('Shareable Options','eventon'));
			$data[] = array('id'=>'eventonsm_fbs','type'=>'yesno','name'=>__('Facebook Share','eventon'));
			$data[] = array('id'=>'eventonsm_tw','type'=>'yesno','name'=>__('Twitter','eventon'));
			$data[] = array('id'=>'eventonsm_ln','type'=>'yesno','name'=>__('LinkedIn','eventon'));
			$data[] = array('id'=>'eventonsm_wa','type'=>'yesno','name'=>'Whatsapp');
			//$data[] = array('id'=>'eventonsm_sms','type'=>'yesno','name'=>'SMS');
			$data[] =array('id'=>'eventonsm_pn','type'=>'yesno','name'=>__('Pinterest (Only shows if the event has featured image)','eventon'));
			$data[] =array('id'=>'eventonsm_rd','type'=>'yesno','name'=>__('Reddit','eventon'));
			$data[] =array('id'=>'eventonsm_copy','type'=>'yesno','name'=>__('Copy event link','eventon'));
			$data = apply_filters('evo_single_sharable', $data);

			$data[] =array('id'=>'eventonsm_email','type'=>'yesno',
				'name'=>__('Share Event via Email','eventon'),
				'legend'=>__('This will trigger a new email in the users device.','eventon'),
			);
			$data[] =array('id'=>'eventonsm_header','type'=>'yesno',
				'name'=>__('Show social share header text','eventon'),
				'legend'=>__('This will show social share header text next to icons. This text can be editted via Language Settings.','eventon'),
				'ver'=>'4.5.9'
			);

			$data[] =array('id'=>'eventonsm_note','type'=>'note',
				'name'=>__('NOTE: Go to "EventCard" and rearrange where you would like the social share icons to appear in the eventcard for an event.','eventon'));
			$data[] = array('type'=>'sub_section_close');

			$data[] = array('id'=>'evosm','type'=>'sub_section_open',
				'name'=>__('Repeat Settings','eventon'));
				$data[] = array('id'=>'evosm_rep_cur_def','type'=>'yesno',
				'name'=>__('Load current repeat event by default on single event page','eventon'), 
				'legend'=>__('Setting this will load the current event of a repeat event by default when the event page is loaded.','eventon'));	
			$data[] = array('type'=>'sub_section_close');

			return apply_filters('evo_se_setting_fields',$data);
		}

	// event indexing - in the works still 4.5.5
		function content_event_indexing(){
			ob_start();
			?>
			<div class='evo_event_index_settings'>
				<p style='' class='evopadb10'><?php _e('Event indexing create a data field in wp_options with a list of events and respective event date range. This will be used to fetch events in the calendar range.','eventon');?></p>
				<p class=''>
					<?php 
					EVO()->elements->print_trigger_element(array(
						'title'=>__('View indexed events log','eventon'),
						'class_attr'=>'evo_admin_btn evolb_trigger',
						'dom_element'=> 'span',
						'uid'=>'evo_admin_index_log',
						'lb_class' =>'evoadmin_index_log',
						'lb_title'=> __('Indexed Events Log','eventon'),
						'ajax_data' =>array(
							'action'=>'eventon_admin_index_log'
						),
					), 'trig_lb');

					
					?>
				</p>
			</div>
			<?php
			return ob_get_clean();
		}

	// Search
		function content_search(){
			ob_start();?>
			<p>By default search icon and search bar are not visible in all calendars!<br/>You can <strong>enable search</strong> by enabling the search on all calendars by default option below or by adding the below shortcode variable into individual eventon calendar shortcode:
			<br/><br/><code>search="yes"</code> example within a shortcode <code>[add_eventon search="yes"]</code> 
			<br/><br/>The placeholder text that shows in the search bar can be edited from <strong>language</strong>.
			<br/>NOTE: In basic event list, search feature can only search for events in first month. If you want to allow search for multiple months on an event list, please check out <a href='https://www.myeventon.com/addons/event-lists-extended/' target='_blank'>Event Lists Ext Addon</a>.
			</p>
			<?php return ob_get_clean();
		}

	// html for diagnosis content
		function content_diag(){
			ob_start();
			?>
			<div class="diagnosis_row">
				<p style='padding-bottom:10px;'>
					<label for=""><?php _e('Email address to send test email','eventon');?></label> 
					<span class='nfe_f_width'><input id='evo_admin_test_email_address' type="text"></span>
				</p>
				<p><a id='evo_send_test_email' class="evo_admin_btn btn_triad"><?php _e('Send Test Email','eventon');?></a></p>

				<p id="evodiagnose_message"></p>
				<p><?php _e('NOTE: The email send test use wordpress wp_mail() function. If you are having trouble sending emails via your website, we suggest using a SMTP plugin or using','eventon');?> <a href="https://wordpress.org/plugins/wp-ses/"><?php _e('Amazon SES','eventon');?></a></p>
			</div>
			<?php
			return ob_get_clean();
		}
		function environ_data(){
			ob_start();
			
				EVO()->elements->print_trigger_element(array(
					'title'=>__('Load Environment Stats','eventon'),
					'class_attr'=>'evo_admin_btn evolb_trigger btn_triad',
					'dom_element'=> 'span',
					'uid'=>'evoadmin_enviro',
					'lb_class' =>'evoadmin_enviro',
					'lb_title'=> __('EventON & Website Environment Stats','eventon'),
					'ajax_data' =>array(
						'action'=>'eventon_admin_get_environment'
					),
				), 'trig_lb');

				EVO()->elements->print_trigger_element(array(
					'title'=>__('View eventon system log','eventon'),
					'class_attr'=>'evo_admin_btn evolb_trigger btn_triad',
					'dom_element'=> 'span',
					'uid'=>'evo_admin_system_log',
					'lb_class' =>'evoadmin_system_log',
					'lb_title'=> __('EventON System Log','eventon'),
					'ajax_data' =>array(
						'action'=>'eventon_admin_system_log'
					),
				), 'trig_lb');

			return ob_get_clean();
		}

	// HTML code for export events in csv and ics format
		function export(){

			$nonce = wp_create_nonce('eventon_download_events');
			
			// CSV format
			$exportURL = add_query_arg(array(
			    'action' => 'eventon_export_events',
			    'nonce'=>$nonce
			), admin_url('admin-ajax.php'));

			// ICS format
			$exportICS_URL = add_query_arg(array(
			    'action' => 'eventon_export_events_ics',
			    'nonce'=>$nonce
			), admin_url('admin-ajax.php'));

			ob_start(); ?>
			<p><a href="<?php admin_url();?>options-permalink.php" class="evo_admin_btn btn_secondary"><?php _e('Reset Permalinks','eventon');?></a></p>
			
			<p><?php _e('Download all eventON events.','eventon');?></p>
			<p><a class='evo_admin_btn btn_triad' href="<?php echo $exportURL;?>"><?php _e('CSV Format','eventon');?></a>  <a class='evo_admin_btn btn_triad' href="<?php echo $exportICS_URL;?>"><?php _e('ICS format','eventon');?></a></p>
			<?php 
			return  ob_get_clean();
		}

		function eventtop_dayblock_settings(){
						
			$arr = array();
			$arr['dayname']=__('Event Day Name','eventon');
			$arr['eventyear']=__('Event Start Year','eventon');
			$arr['eventendyear']=__('Event End Year (If different than start year)','eventon');			

			return $arr;
		}

		// html eventtop designer
		function eventtop_meta_fields(){
			ob_start();
			$saved_eventtop_fields = isset($this->evcal_opt[1]['evcal_top_fields']) ? 
				$this->evcal_opt[1]['evcal_top_fields']: array();


			$cal_help = new evo_cal_help();

			// event top layout
			$evo_etl = $cal_help->get_eventtop_structure_array( $this->evcal_opt[1] );

			$eventtop_fields = $cal_help->get_eventtop_fields_array();
			//print_r($eventtop_fields['layout']);

			?>
			<div class='evotop_designer' >
				<div class='evotop_design_holder'>
				<?php 
					foreach($eventtop_fields['layout'] as $cs => $cdata){

						echo "<div class='evotop_design_col ". ($cs == 'c0'? 'fw':'') ."' data-c='{$cs}'>";
						$field_num = 1;
						foreach($cdata as $ind=>$fields){
							if( !isset($fields['f'])) continue;
							if( $fields['f'] == 'undefined') continue;

							$name = isset($eventtop_fields['all'][ $fields['f'] ]) ? $eventtop_fields['all'][ $fields['f'] ] : $fields['f'];
							
							echo "<span class='evotop_design_field' data-f='{$fields['f']}' data-num='{$field_num}'>
							<em>{$name}</em>
								<span class='ectd_act'><i class='fa fa-minus-circle'></i></span>
							</span>";
							$field_num++;
						}

						echo "<span class='evotop_add_field_trig'><b>+</b></span>";
						echo "</div>";
					}

					$unused_fields = array_diff($eventtop_fields['alla'] , $eventtop_fields['used']);

				?>
				</div>

				<input type='hidden' id='evotop_fields' value='<?php echo json_encode($evo_etl);?>' name='evo_etl'/>
				
				<div id='evotop_field_selector' class=''>
					<h4 style='margin:0 0 10px'><?php _e('Unused Event Top Fields','eventon');?></h4>
					<div id='evotop_field_selector_f'>
					<?php
						if( is_array($unused_fields) && count($unused_fields)>0){
							foreach($unused_fields as $ff){
								echo "<span data-f='{$ff}'>". $eventtop_fields['all'][$ff] ."</span>";
							}
						}
					?>
					</div>
					<p class='nothing' style='<?php echo count($unused_fields) > 0 ? "display:none":'';?>'><?php _e('You are using all the available fields','eventon');?>!</p>
					<span style='margin-top:10px' id='evotop_field_selector_c' class='evo_admin_btn btn_triad'><?php _e('Cancel','eventon');?></span>
				</div>
			</div>
			
			<?php 

			return ob_get_clean();
		}

		public function eventtop_tags(){
			$arr = array(
				'virtual'=>__('Virtual Event','eventon'),
				'virtual_physical'=>__('Virtual/ Physical Event','eventon'),
				'status'=>__('Event Status eg. cancelled, reschedule etc.','eventon'),			
				'featured'=>__('Featured','eventon'),			
				'completed'=>__('Completed','eventon'),			
			);

			return $arr;
		}

		function event_type_options(){
			$event_type_names = evo_get_ettNames($this->evcal_opt[1]);
			// event types category names		
			$ett_verify = evo_get_ett_count($this->evcal_opt[1] );

			$event_type_options = array();
			
			$event_type_options['event_past_future'] = __('Past & Future Event Filtering','eventon');
			$event_type_options['event_virtual'] = __('Virtual Event Filtering','eventon');
			$event_type_options['event_status'] = __('Event Status Filtering','eventon');

			for($x=1; $x< ($ett_verify+1); $x++){
				$ab = ($x==1)? '':'_'.$x;
				$event_type_options['event_type'.$ab] = $event_type_names[$x];
			}

			$event_type_options['event_location'] = __('Event Location','eventon');
			$event_type_options['event_organizer'] = __('Event Organizer','eventon');

			$event_type_options['event_tag'] = __('Event Tags','eventon');
			
			return apply_filters('evo_settings_filtering_taxes',$event_type_options);
		}

	// rearrange fields
		function rearrange_code(){	
			$HH = new evo_cal_help();
			return $HH->get_eventcard_fields( true);
		}

	// HTML for eventcard designer
		function eventcard_designer_btn(){
			ob_start();
			EVO()->elements->print_trigger_element(array(
				'id'=>'evo_ecard_designer',
				'lb_class'=>'evo_ecard_designer',
				'lb_title'=>'EventCard Designer',
				'uid'=>'evo_ecard_designer',
				'ajax_action'=>'eventon_load_ecard_designer',
				'title'=>'Open EventCard Designer',
				'extra_classes'=>'',
				'ajax_data'=>array('a'=>'eventon_load_ecard_designer')
			),'trig_lb');
			return ob_get_clean();
		}

	// custom meta fields
		function custom_meta_fields(){
			// reused array parts
			$__additions_009_1 = apply_filters('eventon_cmd_field_types', array(
				'text'=>__('Single line Text','eventon'),
				'textarea'=>__('Multiple lines of text (Editor)','eventon'), 
				'textarea_trumbowig'=>__('Multiple lines of text (Trumbowig Editor)','eventon'), 
				'textarea_basic'=>__('Multiple lines of text (Text Field)','eventon'), 
				'button'=>__('Button','eventon')
			) );

			// additional custom data fields
			for($cm=1; $cm<evo_max_cmd_count(); $cm++){
				$__additions_009_a[$cm]= $cm;
			}

			// fields for each custom field
			$cmf_count = !empty($this->evcal_opt[1]['evcal_cmf_count'])? $this->evcal_opt[1]['evcal_cmf_count']: 3;
			
			$cmf_addition_x= array(
				array('id'=>'evcal__note','type'=>'note',
					'name'=> '<b>'. __('NOTE','eventon'). ': </b>'. 
						__('Once new data field is activated go to <b>myEventon> Settings> EventCard</b> and rearrange the order of this new field and save changes for it to show on front-end. Custom field types Textarea is not supported for showing on eventtop.','eventon').'<br/>'.
						__('If you change field name for custom fields make sure it is updated in <b>myEventon > Language</b> as well.','eventon').
						'<br/>(* '. __('Required values','eventon'). ')'
				),
				array('id'=>'evcal__note','type'=>'note',
					'name'=> '<b>'. __('NOTE','eventon'). ': </b>'. 
						__('Meta data field support dynamic values via event edit page. {startdate} {enddate} {eventid} {startunix} {endunix}.','eventon')
				),
				
				array('id'=>'evcal_cmf_count','type'=>'dropdown','name'=>__('Number of Additional Custom Data Fields','eventon'), 'options'=>$__additions_009_a, 'default'=>3)
			);

			for($cmf=0; $cmf< $cmf_count; $cmf++){
				$num = $cmf+1;

				$cmf_addition = array( 
					array('id'=>'evcal_af_'.$num,'type'=>'yesno',
						'name'=>__('Activate Additional Field #','eventon').$num,
						'legend'=> __('This will activate additional event meta field.','eventon'),
						'afterstatement'=>'evcal_af_'.$num.''),
					array('id'=>'evcal_af_'.$num,'type'=>'begin_afterstatement'),
					array('id'=>'evcal_ec_f'.$num.'a1','type'=>'text','name'=>__('Field Name*','eventon')),
					array('id'=>'evcal_ec_f'.$num.'a2','type'=>'dropdown','name'=>__('Content Type','eventon'), 'options'=>$__additions_009_1),
					array('id'=>'evcal__fai_00c'.$num.'','type'=>'icon','name'=>__('Icon','eventon'),'default'=>'fa-asterisk'),
					array('id'=>'evcal_ec_f'.$num.'a3','type'=>'yesno','name'=>__('Hide this field from front-end calendar','eventon')),
					array('id'=>'evcal_ec_f'.$num.'a4','type'=>'dropdown','name'=>__('Visibility Type','eventon'), 
						'options'=>array('all'=>'Everyone','admin'=>'Admin Only','loggedin'=>'Logged-in Users Only')
						),
					array('id'=>'evcal_ec_f'.$num.'a5','type'=>'yesno','name'=>__('Show login required message, if visibility type is Logged-in users only','eventon'),'legend'=>__('This will show the data row in eventcard but instead of the actual data it will show a message asking the user to login to see the date for users that are not logged into the site.','eventon')),
					array('id'=>'evcal_af_'.$num,'type'=>'end_afterstatement')
				);

				$cmf_addition_x = array_merge($cmf_addition_x, $cmf_addition);
			}

			$cmf_addition_x[] = array('id'=>'evcal_note','type'=>'note','name'=>'<a href="http://www.myeventon.com/documentation/get-custom-event-data-fields/" target="_blank" class="evo_admin_btn btn_triad">'.__('Want more custom fields? ','eventon') . "</a>");
			return $cmf_addition_x;
		}
	// event type categories
		function event_type_categories(){
			
			$etc = array(	
				array('id'=>'evcal_fcx','type'=>'note','name'=>__('Use this to assign custom names for the event type taxonomies which you can use to categorize events. Note: Once you update these custom taxonomies refresh the page for the values to show up.','eventon')),
				array('id'=>'evcal_eventt','type'=>'text','name'=>__('Custom name for Event Type Category #1','eventon')),
				array('id'=>'evcal_eventt2','type'=>'text','name'=>__('Custom name for Event Type Category #2','eventon')),
				array('id'=>'evcal_fcx','type'=>'note','name'=>__('In order to add additional event type categories make sure you activate them in order. eg. Activate #4 after you activate #3','eventon')),
			);
			
			for($x=3; $x<= evo_max_ett_count(); $x++){
				$etcx = array(
					array('id'=>'evcal_ett_'.$x,'type'=>'yesno',
						'name'=>__('Activate Event Type Category #','eventon').$x,
						'legend'=> __('This will activate additional event type category.','eventon'),
						'afterstatement'=>'evcal_ett_'.$x),
					array('id'=>'evcal_ett_'.$x,'type'=>'begin_afterstatement'),
						array('id'=>'evcal_eventt'.$x,'type'=>'text',
							'name'=>__('Category Type Name','eventon')),
					array('id'=>'evcal_ett_'.$x,'type'=>'end_afterstatement'),
				);
				$etc = array_merge($etc, $etcx);
			}

			// Note
				$etc[] = array('id'=>'evo_note','type'=>'note','name'=>sprintf(__('Want more than 5 event categories? <br/><br/><a href="%s" target="_blank"class="evo_admin_btn btn_triad">Extend categories using pluggable functions</a>' ,'eventon'), 'http://www.myeventon.com/documentation/increase-event-type-count/') );
			

			// for each Multi Data Types
			$etc[] = array('id'=>'evo_subheader','type'=>'subheader','name'=>__('EventCard Multi Data Types','eventon'));
			$etc[] = array('id'=>'evo_note','type'=>'note','name'=>__('This allow you to create multiple items of data for a type and select more than one of these data to show in eventCard. And they are accessible across all events. Please bare in mind multi data types are at a very basic level and if you think of features that can make this better please feel free to submit feature request via <a href="http://helpdesk.ashanjay.com/" target="_blank">helpdesk.</a>','eventon'));
			
			// for each multi data field
			for( $y=1; $y <= EVO()->mdt->evo_max_mdt_count(); $y++){		
				
				$etc[] = array('id'=>'evcal_mdt_'.$y,'type'=>'yesno',
					'name'=>__('Activate Multi Data Type #','eventon').$y,
					'legend'=> __('This will activate additional event type category.','eventon'),
					'afterstatement'=>'evcal_mdt_'.$y);
				$etc[] = array('id'=>'evcal_mdt_'.$y,'type'=>'begin_afterstatement');
				$etc[] = array('id'=>'evcal_mdt_name_'.$y,'type'=>'text',
					'name'=>__('Multi Data Type Name','eventon'));
				$etc[] = array('id'=>'evo_note','type'=>'note',
					'name'=>__('NOTE: Each individual data support name & description fields, below are additional fields to enable for use.','eventon'));
				$etc[] = array('id'=>'evcal_mdt_img'.$y,'type'=>'yesno',
					'name'=>__('Allow images','eventon'));

					for( $z=1; $z <= EVO()->mdt->evo_max_mdt_addfield_count(); $z++){
						$etc[] = array('id'=>'evcal_mdta_'.$y.'_'.$z,'type'=>'yesno','name'=>__('Enable Additional Data Field #'.$z,'eventon'),
						'legend'=>'This will activate additional data field for this data type',
						'afterstatement'=>'evcal_mdta_'.$y.'_'.$z);
						$etc[] = array('id'=>'evcal_mdta_'.$y.'_'.$z,'type'=>'begin_afterstatement');
						$etc[] = array('id'=>'evcal_mdta_name_'.$y.'_'.$z,'type'=>'text','name'=>__('Data Field Name','eventon'));
						$etc[] = array('id'=>'evcal_mdta_'.$y.'_'.$z,'type'=>'end_afterstatement');
					}

				$etc[] = array('id'=>'evcal_mdt_'.$y,'type'=>'end_afterstatement');
			}
			
			return $etc;
		}

	// Third party APIs
		function third_party_apis(){
			$data = array();

			// paypal
			$data[] = array('type'=>'sub_section_open','name'=>__('Paypal','eventon'));
			$data[] = array('id'=>'evcal_paypal_pay','type'=>'yesno','name'=>__('Enable PayPal event ticket payments','eventon'),'afterstatement'=>'evcal_paypal_pay', 'legend'=>'This will allow you to add a paypal direct link to each event that will allow visitors to pay for event via paypal.');
			$data[] = array('id'=>'evcal_paypal_pay','type'=>'begin_afterstatement');
			$data[] = array('id'=>'evcal_pp_email','type'=>'text','name'=>__('Your paypal email address to receive payments','eventon'));				
			$data[] = array('id'=>'evcal_pp_cur','type'=>'dropdown','name'=>__('Select your currency','eventon'), 'options'=> array(
					'AUD'=>'Australian Dollar',
					'BRL'=>'Brazilian Real',
					'CAD'=>'Canadian Dollar',
					'CZK'=>'Czech Koruna',
					'DKK'=>'Danish Krone',
					'EUR'=>'Euro',
					'HKD'=>'Hong Kong Dollar',
					'HUF'=>'Hungarian Forint',
					'ILS'=>'Israeli New Sheqel',
					'JPY'=>'Japanese Yen',
					'MYR'=>'Malaysian Ringgit',
					'MXN'=>'Mexican Peso',
					'NOK'=>'Norwegian Krone',
					'NZD'=>'New Zealand Dollar',
					'PHP'=>'Philippine Peso',
					'PLN'=>'Polish Zloty',
					'GBP'=>'Pound Sterling',
					'RUB'=>'Russian Ruble',
					'SGD'=>'Singapore Dollar',
					'SEK'=>'Swedish Krona',
					'CHF'=>'Swiss Franc',
					'TWD'=>'Taiwan New Dollar',
					'THB'=>'Thai Baht',
					'TRY'=>'Turkish Lira',
					'USD'=>'U.S. Dollar',
				),
				'legend'=> __('PayPal Currently supports 25 currencies','eventon') 
			);				
			$data[] = array('id'=>'evcal_paypal_pay','type'=>'end_afterstatement');

			$data[] = array('type'=>'sub_section_close');
			return $data;
		}
	/**
	 * theme pages and templates
	 * @version 4.6.8
	 */
		public function event_pages(){

			global $wpdb;

			// Define a cache key
			$cache_key = 'evo_admin_all_pages_ids';

			// Try to get the cached results
			$ids = wp_cache_get($cache_key, 'all_pages');

			if ($ids === false) {
			    // If no cached results, perform the query
			    $pages = $wpdb->get_results(apply_filters('evo_admin_all_pagesids_wpquery', "
			        SELECT $wpdb->posts.post_title, $wpdb->posts.ID 
			        FROM $wpdb->posts 
			        WHERE $wpdb->posts.post_type ='page' 
			        AND $wpdb->posts.post_status = 'publish'
			        ORDER BY $wpdb->posts.post_title ASC
			    ", $wpdb));

			    // Initialize the array
			    $ids = array();
			    $ids['na'] = '--';
			    
			    if ($pages) {
			        foreach ($pages as $page) {
			            $ids[$page->ID] = $page->post_title . ' (' . $page->ID . ')';
			        }
			    }

			    // Cache the results
			    wp_cache_set($cache_key, $ids, 'all_pages', 3600); // Cache for 1 hour
			}

			
			return $ids;

		}

	// shortcode content	
		function content_shortcodes(){
			
			ob_start();
			?>
				<p><?php _e('Use the "Generate shortcode" button to open lightbox shortcode generator to create your desired calendar shortcode.','eventon');?></p><br/>
				
				<a id="evo_shortcode_btn" class="evo_admin_btn btn_prime evolb_trigger_shortcodegenerator" >[ ] <?php _e('Generate shortcode','eventon');?></a><br/>
				<p id='evo_set_shortcodes'></p>

				<p style='padding-top:10px'><b><?php _e('Frequently Used Shortcodes','eventon');?></b></p>
				<p><?php _e('[add_eventon] -- Default month calendar','eventon');?></p>
				<p><?php _e('[add_eventon_list number_of_months="5" hide_empty_months="yes" ] -- 5 months events list with empty months hidden from view','eventon');?></p>
				<p><?php _e('[add_eventon_list number_of_months="5" month_incre="-5" ] -- Show list of 5 past months','eventon');?></p>
				<p><i><?php _e('NOTE: For more shortcode examples and usage please visit demo.myeventon.com','eventon');?></i></p>

			<?php

			
			return ob_get_clean();
			
		}
			
	// Appearnace class
		public function appearance(){
			$appearance = new evoadmin_set_appearance($this->evcal_opt);
			return $appearance->get( );
		}
	// scripts class
		public function scripts(){
			$SCR = new Evo_Admin_Settings_Scripts();
			return $SCR->get();
		}
}
