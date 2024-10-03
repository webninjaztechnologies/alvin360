/**
 * Javascript code that is associated with the front end of the calendar
 * @version 4.6.8
 */

jQuery(document).ready(function($){

	//return;
	var BODY = $('body');
	var BUS = ''; // initial eventon calendar data
	var ajax_url = evo_general_params.ajaxurl;


// EventON calendar main function
	
	// to run before cal ajax is performed @4.6
	$.fn.evo_pre_cal = function(options){
		var el = this;
		var cal = {};
		var OO = $.extend({}, options);

		var run = function(){
			cal_width_assoc();
			cal_load_nav_html();
		}
		var cal_width_assoc = function(){
			cal_w = el.width();

			if( cal_w <300) el.addClass('szS');
			if( cal_w >300 && cal_w <600) el.addClass('szM');
			if( cal_w >600 && cal_w <900) el.addClass('szL');
			if( cal_w >900) el.addClass('szX');
		}

		// load nav html into cal
		var cal_load_nav_html = function(){
			nav_data = el.evo_cal_get_footer_data('nav_data');
			
			if( nav_data && 'month_title' in nav_data){

				__html = nav_data.month_title;
				if( 'arrows' in nav_data ) __html += nav_data.arrows;

				el.find('.evo_header_mo').html( __html );
				el.find('.evo_footer_nav').html( __html );
			}
		}
		run();
	}

	// Calendar processing 2.8.6 u 4.6
	$.fn.evo_calendar = function (options) {

		var el = this;
		var cal = this;
		var cal = {};
		var calO = $.extend({
			'SC': {},
			'json':{},
			'type':'init' ,
			map_delay:0
		}, options);
		var SC = el.evo_shortcode_data();

		
		// load calendar eventcard and eventtop interactions
		this.find('.eventon_list_event').each(function(){
			evo_cal_eventcard_interactions( $(this) );
		});

		var init = function(){

			// change IDs for map section for eventon widgets
			if( $(el).hasClass('evcal_widget')){
				$(el).find('.evcal_gmaps').each(function(){
					var gmap_id = obj.attr('id');
					var new_gmal_id =gmap_id+'_widget'; 
					obj.attr({'id':new_gmal_id})
				});
			}

			// load maps on calendar
			_evo_run_eventcard_map_load();

			// initial actions on calendar
			$(el).evo_cal_filtering();	
			
			el.evo_cal_hide_data();	

			live_now_cal();
			counters();
		};
		
		// support	
			var live_now_cal = function(){
				$(el).find('.evo_img_time').each(function(){
					if( $(this).closest('a.desc_trig').find('em.evcal_time').length ){
						_html = $(this).closest('a.desc_trig').find('em.evcal_time')[0].outerHTML;
						$(this).html( _html );
					}				
				});
			}

			var counters = function(){
				$(el).find('.evo_countdowner').each(function(){
					$(this).evo_countdown();
				});
			}

		init();	
	};

      
// Event Card handling v4.6.1
	var evo_eventcard_listeners = function(){

		BODY
		// localize time
			.on('click','.tzo_trig',function(event){
				event.preventDefault();
				event.stopPropagation();

				localize_time( $(this) );
				//return;
			})
		// event images
			.on('click','.evo_event_more_img',function(){
				var O = $(this);
				var box = O.closest('.evcal_eventcard');
				const gal = O.closest('.evocard_fti_in');

				if( box.length == 0 ) return;

				O.siblings('span').removeClass('select');
				O.addClass('select');

				mainIMG = box.find('.evocard_main_image');
				mainIMG.data({
						'h': O.data('h'), 
						w: O.data('w'),
						f: O.data('f')
					});

				if( mainIMG.hasClass('def')){
					mainIMG.css('background-image', 'url('+ O.data('f') +')' );
				}else{
					mainIMG.html('<span style="background-image:url('+ O.data('f') +')"></span>' );
					mainIMG.eventon_process_main_ft_img( );
				}
			})
			// open lightbox from main image click
			.on('click','.evocard_main_image',function(){
				O = $(this);

				// if already in lightbox 4.6.4
				if( O.hasClass('inlb') ) return;

				__ac = parseInt(O.data('w')) >= parseInt( O.data('h') ) ? 'iW':'iH';
				O.evo_lightbox_open({
					uid:'evocard_ft_img',
					lbc:'evolb_ft_img',lbac:'within evocard_img '+ __ac,
					content: "<img class='evocard_main_image inlb' src='" + O.data('f') +"' data-w='"+O.data('w')+"' data-h='"+O.data('h')+"' class=''/>",
					end:'client',
					lb_padding:'',
					d: {event_id: O.data('event_id'), ri: O.data('ri')}
				});
			})			
		// repeat series
			.on('click','.evo_repeat_series_date',function(){

				if( !($(this).parent().hasClass('clickable')) ) return;

				ux = $(this).data('ux');
				URL =  $(this).data('l');
				if( ux == 'def' ) window.location = URL;
				if( ux == 'defA' )window.open( URL, '_blank');
			})

		// copy event link			
			.on('click','.copy.evo_ss', function(event){
				const OBJ = $(this);
				const ROW = OBJ.closest('.evcal_evdata_row');
				var link = decodeURIComponent( OBJ.data('l') );
				navigator.clipboard.writeText( link );

				evo_card_socialshare_html = ROW.html();
				ROW.html( "<p style='display:flex'><i class='fa fa-check marr10'></i> " + $(this).data("t") + "</p>");

				setTimeout(function(){
					ROW.html( evo_card_socialshare_html);
				},3000);
			})

		// location image more
			.on('click','.evo_locimg_more',function(){
				$(this).closest('.evo_metarow_locImg').toggleClass('vis');
			})

		// general gallery image changing 4.6
			.on('click','.evo_gal_icon',function(){
				const O = $(this);
				if( O.hasClass('on') ) return;
				O.siblings('div').removeClass('on');
				O.addClass('on');

				O.closest('.evo_gal_box').find('.evo_gal_main_img').css('background-image', 'url('+ O.data('u') +')');
			})
		// show more/less event details
			.on('click','.evobtn_details_show_more',function(){		
				control_more_less( $(this));	
			})

		// close eventcard 4.6.1
			.on('click','.evcal_close',function(){
				$(this).closest('.evcal_eventcard').slideUp().removeClass('open');
			})

		// when light box is processed
			.on('evo_lightbox_processed',function(event, OO, LIGHTBOX){
				if( !(LIGHTBOX.hasClass('evolb_ft_img'))) return;
				LIGHTBOX.eventon_check_img_size_on_lb( );
			})

		// event top buttons
			.on('click','.evocmd_button', function(event){
				event.preventDefault();
				event.stopPropagation();

				href = $(this).data('href');			
				if( $(this).data('target')=='yes'){
					window.open(href,'_blank');
				}else{
					window.location = href;
				}
			})
		// organizer links from eventtop @4.5
			.on('click','.evo_org_clk_link',function(){
				window.open( $(this).data('link') , "_blank");
			})

		// edit event button redirect
			.on('click','.editEventBtnET', function(event){
				event.stopPropagation();
				href = $(this).attr('href');
				window.open(href);
			})

		;

		// localize time @4.6.7
		var localize_time = function( OBJ ){
			const eventcard = OBJ.closest('.eventon_list_event');

			var hide_end = eventcard.hasClass('no_et') ? true: false;

			eventcard.find('.evo_mytime').each(function(){
				const obj = $(this);
				var time  = obj.data('times');
				var time_format = obj.data('__f');

				new_time = time.split('-');
				
				utc_offset = $(this).data('tzo');
				utc_offset = 0;

				start = parseInt(new_time[0]);
				end = parseInt(new_time[1]);
				offset_start = start + utc_offset;

				// end
				var Me = moment.unix( end ).utc().local();
				var M1 = moment.unix( start ).utc().local();

				var _html = '';

				// same month 
				if( Me.format('YYYY/M') == M1.format('YYYY/M')){

					// same date
					if( Me.format('DD') == M1.format('DD')){
						_html = M1.format( time_format ) + ( !hide_end ? ' - ' + Me.format( obj.data('__tf') ) :'' ) ;
					// dif date
					}else{
						_html = M1.format( time_format ) + ( !hide_end ? ' - '+ Me.format( time_format ) :'' );
					}
				// dif month
				}else{
					_html = M1.format( time_format ) + ( !hide_end ? ' - '+ Me.format( time_format ) :'' );				
				}

				new_html = "<span class='evo_newmytime'>" +_html + "</span>";

				obj.replaceWith( new_html );				
			});
		}

		// actual animation/function for more/less button
		var control_more_less = function(obj){
			var content = obj.attr('content');
			var current_text = obj.find('.ev_more_text').html();
			var changeTo_text = obj.find('.ev_more_text').attr('data-txt');
			const cell = obj.closest('.evcal_evdata_cell');
				
			// show more
			if(content =='less'){			
				
				obj.closest('.evcal_evdata_cell').removeClass('shorter_desc');		
				obj.attr({'content':'more'});
				obj.find('.ev_more_arrow').removeClass('ard');
				obj.find('.ev_more_text').attr({'data-txt':current_text}).html(changeTo_text);
				
			}else{
				obj.closest('.evcal_evdata_cell').addClass('shorter_desc');				
				obj.attr({'content':'less'});
				obj.find('.ev_more_arrow').addClass('ard');
				obj.find('.ev_more_text').attr({'data-txt':current_text}).html(changeTo_text);
			}
		}

	}
	var evo_cal_eventcard_interactions = function( EC , load_maps ){

		// process featured image sizes
		EC.find(".evocard_main_image").eventon_process_main_ft_img(  );		

		// process content sliders
		EC.find('.evo_elm_HCS').each(function(){
			$(this).evo_process_content_slider();
		});	

		// countdown
		EC.find('.evo_countdowner').each(function(){
			var obj = $(this);
			obj.removeClass('evo_cd_on');
			obj.evo_countdown();
		});
		
		$(window).on('resize',function(){
			EC.find(".evocard_main_image").eventon_process_main_ft_img(  );	
		});
	}
	$.fn._evo_cal_eventcard_interactions = function( EC, load_maps){
		evo_cal_eventcard_interactions( EC , load_maps);
	}

	// run all map waiting map @4.6.1
	function _evo_run_eventcard_map_load(){
		BODY.evo_run_eventcard_map_load();
	}
	$.fn.evo_run_eventcard_map_load = function(){

		time = 600;

		BODY.find('.evo_metarow_gmap').each(function(index){	
			O = $(this);
			if( !(O.is(":visible")) ) return;
			O.evo_load_gmap({
				map_canvas_id: O.attr('id'),
				trigger_point:'evo_calendar',
				delay: time
			});
			time += 600;
		});			
	}

// EventTop Interactions v4.6
	var evo_cal_eventtop_interactions = function( ET ){	}
	
// RUN on Page load
	init();
	function init(){

		init_load_cal_data();

		handlebar_additional_arguments();
		
		evo_cal_body_listeners();

		jitsi();

		// run basic countdown timers
		BODY.find('.evo_countdowner').each(function(){
			$(this).evo_countdown();
		});
	}

// Initial load data via ajax u4.6
	function init_load_cal_data(){

		evo_eventcard_listeners();

		// check if calendars are present in the page
			var run_initload = false;

			if( $('body').find('.ajde_evcal_calendar').length > 0 ) run_initload = true;
			if( $('body').find('.ajax_loading_cal').length > 0 ) run_initload = true;
			if( $('body').find('.eventon_single_event').length > 0 ) run_initload = true;

			if(run_initload == false) return false;

			var data_arg = {};	

			BODY.trigger('evo_global_page_run');

			data_arg['global'] = $('#evo_global_data').data('d');
			data_arg['cals'] ={};			

		// run through all the calendars on page
			BODY.find('.ajde_evcal_calendar').each(function(){
				const CAL = $(this);
				var SC = CAL.evo_shortcode_data();

				CAL.evo_pre_cal();

				if( CAL.hasClass('ajax_loading_cal')){
					data_arg['cals'][ CAL.attr('id')] = {};
					data_arg['cals'][ CAL.attr('id')]['sc'] = SC;

					BODY.trigger('evo_global_page_run_after', CAL , SC );// @4.6.1
				}
			});
			
		
		$.ajax({
			beforeSend: function(){},
			type: 'POST',
			url: get_ajax_url('eventon_init_load'), 
			data: data_arg,dataType:'json',
			success:function(data){
				$('#evo_global_data').data('d', data);

				BUS = data;

				// append html to calendars if present
				if('cals' in data){
					var time = 300;
					$.each(data.cals, function(i,v){

						setTimeout( function(){

							CAL = BODY.find('#'+ i);
							if(CAL.length === 0) return;
							
							if('html' in v){						
								CAL.find('#evcal_list').html( v.html );
								CAL.removeClass('ajax_loading_cal');
								CAL.find('.evo_ajax_load_events').remove();
							}	

							// load SC and JSON to calendar
							CAL.evo_cal_functions({action:'update_shortcodes',SC: v.sc});
							CAL.evo_cal_functions({action:'update_json',json: v.json});
							
							$('body').trigger('evo_init_ajax_success_each_cal', [data, i, v, CAL]);

						}, time);
						time += 300;
					});
				}

				$('body').trigger('evo_init_ajax_success', [data]);

				// after timeout based cal loading, process all cals
				setTimeout( function(){
					BODY.find('.ajde_evcal_calendar').each(function(){
						if( $(this).hasClass('.ajax_loading_cal') ) return;					
						$(this).evo_calendar({'type':'complete'});
					});
				}, time );

			},complete:function(data){				
				$('body').trigger('evo_init_ajax_completed', [data]);
			}
		});
	}
	

// GENERALIZED functions
// General AJAX trigger - added 3.1
	$(document).on('click','.evo_trig_ajax',function(event ){
		var ajax_data = {};
		const obj = $(this);

		ajax_data = obj.data();
		$(document).data( 'evo_data', ajax_data );

		$(document).trigger('evo_before_trig_ajax', [obj]);

		var new_ajax_data = $(document).data( 'evo_data');
		new_ajax_data['nn'] = the_ajax_script.postnonce;

		$.ajax({
			beforeSend: function(){
				$(document).trigger('evo_beforesend_trig_ajax', [obj, new_ajax_data]);
			},
			type: 'POST',url: get_ajax_url('eventon_gen_trig_ajax') ,data: new_ajax_data,dataType:'json',
			success:function(return_data){
				$(document).trigger('evo_success_trig_ajax', [obj, new_ajax_data, return_data]);
			},complete:function(){
				$(document).trigger('evo_complete_trig_ajax', [obj, new_ajax_data]);
			}
		});

	});

// Virtual Events & jitsi
	function jitsi(mod_refresh){
		
		const domain = 'meet.jit.si';
	    const api = [];

	    jQuery('.evo-jitsi-wrapper').each(function(index, element) {
	    	const O = $(this);
	    	const eventO = O.closest('.eventon_list_event');

	    	// check if mod refresh is set or no
	    	if( mod_refresh != '' && mod_refresh == 'mod_refresh_no' && O.hasClass('mod')) return;

	        var roomName = jQuery(element).data('n'),
	            width = jQuery(element).data('width'),
	            height = jQuery(element).data('height'),
	            audioMuted = jQuery(element).data('audiomute'),
	            videoMuted = jQuery(element).data('videomute'),
	            screenSharing = jQuery(element).data('screen');
	           

	        const myOverwrite =
			{
			 	'TOOLBAR_BUTTONS': $(element).data('d'),
			    "DEFAULT_BACKGROUND": '#494a4e',
			    'MOBILE_APP_PROMO': false,
			    'SETTINGS_SECTIONS':['devices', 'language', 'profile', 'calendar'],
			};


	        const options = {
	            roomName,
	            width,
	            height,
	            parentNode: element,	            
	            configOverwrite: { 
	            	startWithAudioMuted: audioMuted,
	                startWithVideoMuted: videoMuted,
	                startScreenSharing: false,	  
	                disableInviteFunctions: false,             
	            },
	            interfaceConfigOverwrite: myOverwrite,     
	        };


	        const api = new JitsiMeetExternalAPI(domain, options);      

	        api.addEventListener('participantRoleChanged', function(event){

	        	// record moderator joined
	        	if (event.role === "moderator"){	        		
	        		_record_moderator_join( 'yes', eventO.data('event_id'), eventO.data('ri'));
	        	}

	        	const pp = jQuery(element).data('p');
	        	if (event.role === "moderator" && pp != '__') {
	        		ppp = pp.replace('_','');
			        api.executeCommand('password', ppp);
			    }	        	
	        });	

	        // moderator leave	        
	        api.addEventListener('videoConferenceLeft', function(event){
	        	if( eventO.find('.evo_vir_data').data('ismod') =='y'){
	        		_record_moderator_join( 'no', eventO.data('event_id'), eventO.data('ri'));
	        		O.siblings('.evo_vir_mod_left').show();
	        		O.hide();
	        	}
	        });
	    });
	}
	// record moderator logins for jitsi
		function _record_moderator_join(joined, eid, ri){
			var data_arg = {
				'action': 'eventon_record_mod_joined',
				'eid': eid,
				'ri': ri,
				'joined': joined,
				'nonce': evo_general_params.n,				
			};

			$.ajax({
				beforeSend: function(){},
				type: 'POST',url: ajax_url,data: data_arg,dataType:'json',
				success:function(data){	}
			});
		}
	

	// refresh event card elements  - evo_reload_virtual_events
	// @+ 3.1
		$('body').on('evo_refresh_elements',function(event, send_data ){

			if( send_data.length <= 0 || !send_data) return;

			send_data['nonce'] = evo_general_params.n;

			$.ajax({
				beforeSend: function(){	
					if( 'evo_data' in send_data){
						$.each(send_data.evo_data, function(ekey, eclasses){
							$.each(eclasses, function(classnm, val){	
								if(val && 'loader' in val && val['loader'] && 'loader_class' in val){
									$('#event_'+ekey).find('.'+val['loader_class']).addClass('evoloading');	
								}
							});
						});	
					}
				},
				type: 'POST',url: get_ajax_url('eventon_refresh_elm'),data: send_data,dataType:'json',
				success:function(data){
					if( data.status == 'good' ){
						evo_apply_refresh_content( data );
					}
				},complete: function(){	
					if( 'evo_data' in send_data){
						$.each(send_data.evo_data, function(ekey, eclasses){
							$.each(eclasses, function(classnm, val){
								if(val && 'loader' in val && val['loader'] && 'loader_class' in val){
									$('#event_'+ekey).find('.'+val['loader_class']).removeClass('evoloading');	
								}
							});
						});	
					}
				}
			});
		});

		// refresh the closest hearbeat run parent
		$('body').on('evo_refresh_designated_elm', function(ee, elm, elm_class, extra_data){

			//get closest event object
			const event = $(elm).closest('.eventon_list_event');

			if( !event ) return;
			if( event.find('.'+elm_class).length == 0 ) return;

			const refresh_elm = event.find('.'+elm_class);

			var send_data = {};		

			send_data['evo_data'] = build_elm_refresh_data( refresh_elm , extra_data);
			
			$('body').trigger('evo_refresh_elements',[ send_data ]);
		});

	// record sign in - virtual plus
		// @+3.1
		$('body').on('click','.evo_vir_signin_btn',function(){
						
			extra_data = {};
			extra_data['signin'] = 'y';
			extra_data['refresh_main'] = 'y';
			extra_data['loader'] = true;
			extra_data['loader_class'] = 'evo_vir_main_content';

			$('body').trigger('evo_refresh_designated_elm',[ $(this) , 'evo_vir_data',extra_data]);
		});


	// apply refresh event element content with matching data that is sent
		function evo_apply_refresh_content(data){

			if( 'evo_data' in data ){

				$.each(data.evo_data, function(eclass, boxes){
					// if event exists in the page

					var vir_data_vals = false;
					if( 'evo_vir_data' in boxes) vir_data_vals = boxes.evo_vir_data.data;

					$('body').find('.'+eclass).each(function(){
						const event_elm = $(this);

						// set html
						$.each(boxes, function(boxclass, boxdata){
							if( !('html' in boxdata) ) return;
							if( boxdata.html == '' ) return;
							if( event_elm.find('.'+boxclass).legnth <= 0 ) return;

							event_elm.find( '.'+boxclass ).html( boxdata.html );
						});

						// only for virtual event update
						if( vir_data_vals ){

							// reload jitsi for main content - if main content html is sent it will refresh
								if( vir_data_vals && ('vir_type' in vir_data_vals) 
									&& vir_data_vals.vir_type == 'jitsi' 
									&& ('evo_vir_main_content' in boxes) 
									&& ('html' in boxes.evo_vir_main_content)  
									&& boxes.evo_vir_main_content.html != ''
								){
									jitsi('mod_refresh_no');
								}

							// update data for sent object
								$.each(boxes, function(boxclass, boxdata){
									if( boxdata.data == '' || boxdata.data === undefined) return;

									// for jitsi if mod left --> force refresh main
									if( boxdata !== undefined && vir_data_vals.vir_type == 'jitsi' && vir_data_vals.mod_joined =='left'){
										
										// force refresh main
										boxdata.data['refresh_main'] = 'yy';
									}	

									event_elm.find( '.'+boxclass ).data( boxdata.data );
								});
						}

					});
				});
			}
		}
	// get refresh data for specified elem
		function build_elm_refresh_data( elm , extra_data){

			dataObj = {};

			// get closest event element and event id/ri from it
				const event = $(elm).closest('.eventon_list_event');

				dataObj = {}
				const ekey = event.data('event_id')+'_'+ parseInt(event.data('ri'));
				dataObj[ ekey ] = {};

			const key2 = elm.data('key');

			// append new data to the element
			dataObj[ ekey ][ key2 ] = elm.data();

			// check if awaitmod need checked
				if( elm.data('check_awaitmod')){

					// if waiting for mod element is on page -> set as user awaiting mod
					if( ( event.find('.evo_vir_jitsi_waitmod').length>0) )
						dataObj[ ekey ][ key2 ]['refresh_main'] = 'yy';

					// if jitsi is loaded on page & mod is still in --> stop refreshing main
					if( event.find('.evo-jitsi-wrapper').length>0 && dataObj[ ekey ][ key2 ]['mod_joined'] !='left') 
						dataObj[ ekey ][ key2 ]['refresh_main'] =  '';
				}

			// append extra data
			if( extra_data && extra_data !== undefined){
				$.each( extra_data, function (index,val){
					dataObj[ ekey ][ key2 ][index] = val;
				});
			}

			return dataObj;
		}	

	// mark as virtual event ended
		$(document)
		.on('evo_before_trig_ajax',function(event, obj){
			if(!obj.hasClass('evo_trig_vir_end')) return;
			
			var new_ajax_data = $(document).data( 'evo_data');
			new_ajax_data['fnct'] = 'mark_event_ended';
			$(document).data( 'evo_data', new_ajax_data );
			
		})
		.on('evo_beforesend_trig_ajax',function( event, obj, new_ajax_data){
			if(!obj.hasClass('evo_trig_vir_end')) return;

			obj.closest('.evo_vir_mod_box').addClass('evoloading');
		})
		.on('evo_success_trig_ajax',function( event, obj, new_ajax_data, return_data){
			if(!obj.hasClass('evo_trig_vir_end')) return;

			// if virtual events were marked as ended
			if(!('_vir_ended' in return_data)) return;

			// refresh the virtual main content
			extra_data = {};
			extra_data['refresh_main'] = 'yy';
			extra_data['loader'] = true;
			extra_data['loader_class'] = 'evo_vir_main_content';

			//console.log(extra_data);

			$('body').trigger('evo_refresh_designated_elm',[ obj , 'evo_vir_data',extra_data]);
		})
		.on('evo_complete_trig_ajax',function( event, obj, new_ajax_data){

			if(!obj.hasClass('evo_trig_vir_end')) return;
			obj.closest('.evo_vir_mod_box').removeClass('evoloading');
		})
		;

// Heartbeat - added 3.1
	//hook into heartbeat-send
	jQuery(document).on('heartbeat-send', function(e, data) {

		// if there is run heartbeat items
		if( $('body').find('.evo_refresh_on_heartbeat').length>0 ){
			
			$('body').find('.evo_refresh_on_heartbeat').each(function(){
				if( $(this).closest('.eventon_list_event').length <= 0) return;
				if( $(this).data('refresh')!== undefined && !$(this).data('refresh') ) return;

				data['evo_data'] = build_elm_refresh_data( $(this) );
			});			
		}
	});
	
	//hook into heartbeat-tick
	jQuery(document).on('heartbeat-tick', function(e, data) {	
		evo_apply_refresh_content( data );
	});

// Schedule View - 4.0 / u 4.6
	$('body')
	.on('evo_init_ajax_success_each_cal',function(event, data, i, v, CAL){
		$('body').find('.ajde_evcal_calendar.evoSV').each(function(){
			evosv_populate( $(this) );
		});
	})
	.on('evo_main_ajax_before_fnc', function(event, CAL,  ajaxtype, data_arg){
		SC = data_arg.shortcode;
		if( SC.calendar_type == 'schedule'){
			CAL.find('#evcal_list').removeClass('evo_hide').show();
		}
	}).on('evo_main_ajax_success', function(event, CAL,  ajaxtype, data , data_arg){
		SC = data_arg.shortcode;
		if( SC.calendar_type == 'schedule'){
			CAL.find('#evcal_list').addClass('evo_hide').hide();
		}
	}).on('evo_main_ajax_complete', function(event, CAL,  ajaxtype, data , data_arg){
		SC = data_arg.shortcode;
		if( SC.calendar_type == 'schedule'){
			evosv_populate( CAL );
		}
	})
	// view switching
	.on('evo_vSW_clicked_before_ajax',function(event, O, CAL, DD, reload_cal_data){
		if(!(O.hasClass('evosv'))) return;
		var SC = CAL.evo_shortcode_data();

		CAL.evo_update_cal_sc({F:'calendar_type', V: 'schedule'});
		CAL.evo_update_cal_sc({F:'fixed_day', V: SC.fixed_day });

	})
	.on('evo_vSW_clicked',function(event, OBJ, CAL, DD, reload_cal_data){
		if(!(OBJ.hasClass('evosv'))) return;
				
		CAL.evo_update_cal_sc({F:'calendar_type', V: 'schedule'});

	})
	.on('evo_vSW_clicked_noajax',function(event, OBJ, CAL, DD, reload_cal_data){
		if(!(OBJ.hasClass('evosv'))) return;
				
		evosv_populate( CAL );		
	})
	// open events from schedule view
		.on('click','.evosv_items',function(event, elm){
			O = $(this);
			CAL = O.closest('.ajde_evcal_calendar');
			var e_cl = 'event_'+O.data('id');
			
			const clicked_event_uxval = O.data('uxval');

			// if event is set to slide down .. switch to lightbox
			if( clicked_event_uxval == '1' ){
				CAL.find('.'+e_cl).find('.desc_trig').data('ux_val', 3);
			}

			CAL.find('.'+e_cl).find('.desc_trig').trigger('click');
		});

	// populate the schedule view data @4.5.8
	function evosv_populate(CAL){
		//console.log('s');
		var SC = CAL.evo_shortcode_data();
		OD = CAL.evo_get_OD(); // calendar other data 

		var cal_events = CAL.find('.eventon_list_event');
		days_in_month = CAL.evo_day_in_month({M: SC.fixed_month, Y: SC.fixed_year});
		time_format = CAL.evo_get_global({S1:'cal_def',S2:'wp_time_format'});

		// text strings
			_txt = CAL.evo_get_txt({V:'no_events'});
			_txt2 = CAL.evo_get_txt({V:'until'});
			_txt3 = CAL.evo_get_txt({V:'from'});
			_txt4 = CAL.evo_get_txt({V:'all_day'});
		
		CAL.find('#evcal_list').addClass('evo_hide');

		var has_events = false;
		var html = '';
		var template_data = {};
		var processed_ids = {};

		// Set initial date - date values
			var SU = parseInt( SC.focus_start_date_range);	var EU = '';
			var M = moment.unix( SU ).tz( OD.cal_tz );

		// go through each day in month
		for(var x=1; x<= days_in_month; x++){
			
			var month_name = CAL.evo_get_dms_vals({ V: (M.get('month') +1), type:'m3'});
			var day_name = CAL.evo_get_dms_vals({ V: M.day(), type:'d3'});
			
			// set event unix into moment
				SU = M.unix();	M.endOf('day');
				EU = M.unix();	M.startOf('day');
			
			// run through each event and get events in this date
				var events = {};

				cal_events.each(function(index, elm){
					ED = $(elm).evo_cal_get_basic_eventdata();
					if( !ED) return;

					processed_ids[ED.uID] = ED.uID;
					ESU = ED.unix_start; EEU = ED.unix_end;

					// check for date range
						var inrange = CAL.evo_is_in_range({
							'S': SU,	'E': EU,	'start': ESU,	'end':EEU
						});
						if(!inrange) return; // skip if no in range

					has_events = true;

					// event time relative to calendar tz
					m = moment.unix( ESU ).tz( OD.cal_tz );
					me = moment.unix( end ).tz( OD.cal_tz );

					var all_day = $(elm).find('a.desc_trig').hasClass('allday') ? true: false;

					// get event time correct for all day
					if( all_day ){
						ED['t'] = _txt4;
					}else{
						if( ESU <= SU ){
							if( EEU >= EU) ED['t'] = _txt4;
							if( EEU < EU ) ED['t'] = _txt2+' ' + me.format( time_format);		
						}else if(ESU > SU){
							if( EEU >= EU)  ED['t'] = _txt3+' '+ m.format( time_format);
							if( EEU < EU ) ED['t'] = m.format( time_format) +' - '+ me.format( time_format);
						}	
					}						

					// hide end time
					if( ED.hide_et == 'y')		ED['t'] = m.format( time_format);

					events[index] = ED;
				});			

			// if there are events in this date
				if( events && Object.keys(events).length > 0){
					
					template_data[ x ] = {};
					template_data[ x ]['date'] = '<b>' + M.get('date')+'</b> '+ month_name+' '+ day_name;
					template_data[ x ]['d'] =  M.format('YYYY-M-D');
					template_data[ x ]['SU'] = SU;
					template_data[ x ]['events'] = {}

					$.each(events, function(index, item){		

						location_data = organizer_data = event_tags = '';

						// location 
						if( SC.show_location == 'yes' && 'location' in item){
							location_data = "<div class='evosv_subdata evosv_location'><i class='fa fa-location-pin marr5'></i>" +item.location+"</div>";
						}

						// organizer					
						if( SC.show_organizer == 'yes' && 'organizer' in item){
							organizer_data = "<div class='evosv_subdata evosv_org'>" +item.organizer+"</div>";
						}

						// event tags
						if( SC.show_tags == 'yes' && 'event_tags' in item){
							event_tags = "<div class='evosv_subdata evosv_tags'>";
							$.each( item.event_tags, function(index, val){
								event_tags += "<span class='evosv_tag " + index +"'>" + val+"</span>";
							});
							event_tags += "</div>";
						}

						template_data[ x ]['events'][ item.uID ] = {
							'time': item.t,
							'ux_val': item.ux_val,
							'title': item.event_title,
							'color':item.hex_color,
							'tag': event_tags,
							'loc': location_data,
							'org': organizer_data,
							'i': item
						}

					});					
				}

			// next date
			M.add(1, 'd');
		}

		var html_ = "<div class='evosv_grid evoADDS'>";
		
		// if no events
		if( !has_events){
			no_event_content = CAL.evo_get_global({S1: 'html', S2:'no_events'});			
			html_ += "<div class='date_row'><div class='row no_events evosv'>"+no_event_content+"</div></div>";
		}else{
			html_ += CAL.evo_HB_process_template({
				TD:template_data, part:'evosv_grid'
			});
		}

		html_ += '</div>';

		if( CAL.find('.evosv_grid').length > 0){
			CAL.find('.evosv_grid').replaceWith( html_ );
		}else{
			ELM = CAL.find('#eventon_loadbar_section');
			ELM.after( html_ );
		}
		
	}

// ELEMENTS u4.6
	// tooltips
		$('body').on('mouseover','.ajdeToolTip',function(event){
			event.stopPropagation();
			if($(this).hasClass('show')) return;

			const t = $(this).data('d');
			var p = $(this).position();
			
			var cor = getCoords(event.target);

			$('.evo_tooltip_box').removeClass('show').removeClass('L').html(t);
			var box_height = $('.evo_tooltip_box').height();
			var box_width = $('.evo_tooltip_box').width();

			$('.evo_tooltip_box').css({'top': (cor.top - 55 - box_height), 'left': ( cor.left + 5 ) })
				.addClass('show');

			// left align
			if( $(this).hasClass('L')){
				$('.evo_tooltip_box').css({'left': (cor.left - box_width - 15) }).addClass('L');			
			}
		})
		.on('mouseout','.ajdeToolTip',function(){	
			$('.evo_tooltip_box').removeClass('show');
		});

		function getCoords(elem) { // crossbrowser version
		    var box = elem.getBoundingClientRect();
		    //console.log(box);

		    var body = document.body;
		    var docEl = document.documentElement;

		    var scrollTop = window.pageYOffset || docEl.scrollTop || body.scrollTop;
		    var scrollLeft = window.pageXOffset || docEl.scrollLeft || body.scrollLeft;

		    var clientTop = docEl.clientTop || body.clientTop || 0;
		    var clientLeft = docEl.clientLeft || body.clientLeft || 0;

		    var top  = box.top +  scrollTop - clientTop;
		    var left = box.left + scrollLeft - clientLeft;

		    return { top: Math.round(top), left: Math.round(left) };
		}
	// yes no button		
		$('body').on('click','.ajde_yn_btn ', function(event){

			// stop this code from working on wp-admin
			if($('body').hasClass('wp-admin')) return false; 
			
			var obj = $(this);
			var afterstatement = obj.attr('afterstatement');
				afterstatement = (afterstatement === undefined)? obj.attr('data-afterstatement'): afterstatement;	
			var uid = '';

			// yes
			if(obj.hasClass('NO')){					
				obj.removeClass('NO');
				obj.siblings('input').val('yes');

				// afterstatment
				if(afterstatement!=''){
					var type = (obj.attr('as_type')=='class')? '.':'#';
					if( obj.data('uid') !== undefined) uid = obj.data('uid');
					$(type+ afterstatement).slideDown('fast');						
				}

			}else{//no
				obj.addClass('NO');
				obj.siblings('input').val('no');
				
				if(afterstatement!=''){
					var type = (obj.attr('as_type')=='class')? '.':'#';
					$(type+ afterstatement ).slideUp('fast');
				}
			}
		});

	// content slider v4.6
		BODY.on('click','.evo_elmHCS_nav.content_slide_trig',function(){
			O = $(this);

			var _HCS = O.closest('.evo_elm_HCS'),
			_HCS_in = _HCS.find('.evo_elm_HCS_in');
			_line_width = _HCS_in[0].scrollWidth;
			_container_width = parseInt( _HCS.width() ) + 0;
			_leftPos = _HCS_in.scrollLeft();
			_scrollable_legth = _line_width - _container_width;

			//console.log(_line_width+' '+_container_width+' '+_leftPos+' '+_scrollable_legth);
			//return;

			const scroll_length = _container_width /2;
			
			// move right
			if( O.hasClass('HCSnavR') ){
														
				_HCS_in.animate({scrollLeft:_leftPos + scroll_length},200);
				_HCS.find('.HCSnavL').addClass('vis');
			// move left
			}else{
				sleft = (_leftPos - scroll_length < scroll_length) ? 0 :  _leftPos - scroll_length;
				_HCS_in.animate({scrollLeft: sleft },200);											
			}

			setTimeout(function(){
				var _leftPos = _HCS_in.scrollLeft();
				//console.log(_leftPos);
				if( _leftPos < 10){
					_HCS.find('.HCSnavL').removeClass('vis');
					_HCS.find('.HCSnavR').addClass('vis');
				}

				if( _leftPos > ( _scrollable_legth - 5 ) ){
					_HCS.find('.HCSnavR').removeClass('vis');
				}
			},200);
								
		});
		$.fn.evo_process_content_slider = function(){

			_HCS = this;
			_HCS_in = _HCS.find('.evo_elm_HCS_in');
			_line_width = _HCS_in[0].scrollWidth;
			_container_width = parseInt( _HCS.width() ) + 3;
			var leftPos = _HCS_in.scrollLeft();

			// console.log(_container_width+' '+_line_width);

			// content is not showing full
			if( _line_width > _container_width ){

				// if some of content is hidden on right
				if( ( _container_width + leftPos  ) < _line_width )
					_HCS.find('.HCSnavR').addClass('vis');

				// if content has been scrolled
				if( leftPos > 0 ){
					_HCS.find('.HCSnavL').addClass('vis');
				}else{
					_HCS.find('.HCSnavR').addClass('vis');
				}
			}else{
				_HCS.find('.HCSnavL').removeClass('vis');
				_HCS.find('.HCSnavR').removeClass('vis');
			}
		}


			$(window).on('resize',function(){
				BODY.find('.evo_elm_HCS').each( function(event){
					$(this).evo_process_content_slider();
				});
			});

// CAL BODY Listeners
	function evo_cal_body_listeners(){
		BODY.evo_cal_lb_listeners();

		BODY
		// after both eventcard lightbox content is loaded
		.on('evolightbox_end',function(event, LB, CAL){
			setTimeout(function(){
				//console.log('e');
				LB.find('.eventon_list_event').each(function(){
					evo_cal_eventcard_interactions( $(this) , true );
				});

				// load maps
				_evo_run_eventcard_map_load();

			}, 1000);
		})

		// load eventon event anywhere via lightbox ajax u4.6
			.on('click','.eventon_anywhere.evoajax', function(event){
				event.preventDefault();

				var obj = $(this);
				var data = obj.data('sc');

				if( data.ev_uxval == '4') return;
			
				// NOTE: repeat_interval is already in SC
				data['evortl'] = 'no';
				if( 'id' in data ) data['event_id'] = data.id;
				data['ux_val'] = '3a';
				data['ajax_eventtop_show_content'] = false;

				obj.evo_cal_lightbox_trigger( data, obj, false);
			})

		// click on no events - @v 4.2
			.on('click','.evo_no_events_btn', function (e){
				BODY.trigger('click_on_no_event_btn', [$(this) ] );
			})
		// MONTH switch
			.on('click','.evcal_arrows', function(){

				const CAL = $(this).closest('.ajde_evcal_calendar');

				dir = $(this).hasClass('evcal_btn_prev') ? 'prev': 'next';
				var cal_id = CAL.attr('id');

				if( $(this).closest('.ajde_evcal_calendar').hasClass('evortl') ){
					dir = ( dir == 'next') ? 'prev': 'next';
				}

				// if its from footer or header 4.6
				if( $(this).closest('.evo_footer_nav').length > 0){

					BOX = $(this).closest('.evo_footer_nav');

					offset = BOX.offset();
					scrolltop = $(window).scrollTop();
					viewport_top = offset.top - scrolltop;

					
											
					CAL.addClass('nav_from_foot').data('viewport_top', viewport_top );
				}

				// run the cal action
				run_cal_ajax( cal_id, dir ,'switchmonth');
			})

		// Show more events on list
			.on('click','.evoShow_more_events',  function(){
				CAL = $(this).closest('.ajde_evcal_calendar');
				SC = CAL.evo_shortcode_data();

				OBJ = $(this);

				// redirect to an external link 
					if(SC.show_limit_redir !== ''){
						window.location = SC.show_limit_redir;	return false;
					}

				// ajax pagination
				if( SC.show_limit_ajax =='yes'){
					CURRENT_PAGED = parseInt(SC.show_limit_paged);				
					CAL.evo_update_cal_sc({F:'show_limit_paged', V: CURRENT_PAGED+1});
					run_cal_ajax( CAL.attr('id'), 'none','paged');

				}else{
					var event_count = parseInt( SC.event_count );
					
					var eventList = OBJ.parent();
					var allEvents = eventList.find('.eventon_list_event').length;

					var currentShowing = eventList.find('.eventon_list_event:visible').length;

					for(x=1; x<=event_count ; x++ ){
						var inde = currentShowing+x-1;
						eventList.find('.eventon_list_event:eq('+ inde+')').slideDown();
					}

					// hide view more button
					if(allEvents > currentShowing && allEvents<=  (currentShowing+event_count)){
						$(this).fadeOut();
					}
				}
			})

		// refresh event top
			.on('runajax_refresh_eventtop',function(e, OBJ, nonce){})

		// when event card is slided done @4.6.1
			.on('evo_slidedown_eventcard_complete',function(event, event_id, obj, __is_slide_down){

				if( !__is_slide_down  ) return;

				setTimeout(function(){

					OO = obj.closest('.eventon_list_event');
					evo_cal_eventcard_interactions( OO , true );
				},300);
			})
		// JUMPER switch v4.6
			.on('calendar_month_changed',function(event, CAL){
				SC = CAL.evo_shortcode_data();
				B = CAL.find('.evo-gototoday-btn');

				var O = CAL.find('.evo_j_container');
				O.find('.evo_j_months a').removeClass('set');
				O.find('.evo_j_months a[data-val="'+ SC.fixed_month +'"]').addClass('set');

				O.find('.evo_j_years a').removeClass('set');
				O.find('.evo_j_years a[data-val="'+ SC.fixed_year +'"]').addClass('set');

				// show go to today 				
				if( SC.fixed_month != B.data('mo') || SC.fixed_year != B.data('yr')){
					//B.show();
					BODY.trigger('show_cal_head_btn', [B]);
				}else{
					//B.hide();
					BODY.trigger('hide_cal_head_btn', [B]);
				}
			})
		// click on go to today
			.on('click','.evo-gototoday-btn', function(){
				var obj = $(this);
				CAL = obj.closest('.ajde_evcal_calendar');			
				var calid = CAL.attr('id');

				CAL.evo_update_cal_sc({F:'fixed_month', V: obj.data('mo')});
				CAL.evo_update_cal_sc({F:'fixed_year', V: obj.data('yr')});
				
				run_cal_ajax( calid,'none','today');
				BODY.trigger('hide_cal_head_btn', [obj]);
			})
					
		// refresh now calendar
			.on('runajax_refresh_now_cal',function(e, OBJ, nonce){

				const section = OBJ.closest('.evo_eventon_live_now_section');
				const CAL = section.find('.ajde_evcal_calendar').eq(0);

				var dataA = {
					nonce: nonce,
					other: OBJ.data(),
					SC: CAL.evo_shortcode_data()
				};

				

				$.ajax({
					beforeSend: function(){
						section.addClass('evoloading');
					},
					type: 'POST',url: get_ajax_url('eventon_refresh_now_cal'), data: dataA,dataType:'json',
					success:function(data){
						if( data.status == 'good'){
							section.html( data.html);

							$('body').trigger('evo_refresh_designated_elm',[ OBJ, 'evo_vir_data']);
						}

					},complete:function(data){
						section.removeClass('evoloading');

						BODY.find('.evo_countdowner').each(function(){
							$(this).evo_countdown();
						});
					}
				});
			})

		// cal header buttons
			.on('click','.cal_head_btn',function(){

				if( $(this).hasClass('vis')){
					BODY.trigger('hide_cal_head_btn',[ $(this)]);	
				}else{
					BODY.trigger('show_cal_head_btn',[ $(this)]);	
				}
				
			})

			.on('show_cal_head_btn',function(event, obj ){
				//CAL = obj.closest('.ajde_evcal_calendar');
				if( obj.hasClass('evo-gototoday-btn') ){	}else{
					obj.siblings(':not(.evo-gototoday-btn)').removeClass('show vis');
				}
				obj.addClass('show vis');
				CAL = obj.closest('.ajde_evcal_calendar');

				BODY.trigger('evo_cal_header_btn_clicked', [ obj , CAL ,'show']);	

			})
			.on('hide_cal_head_btn',function(event , obj){

				CAL = obj.closest('.ajde_evcal_calendar');
				//obj.siblings().removeClass('show vis');
				obj.removeClass('show vis');

				BODY.trigger('evo_cal_header_btn_clicked', [ obj , CAL ,'hide']);	

			})

		// OPENING event card -- USER INTREACTION and loading google maps
			.on('click','.eventon_list_event .desc_trig', function(event){

				var obj = $(this);					
				
				var attr = obj.closest('.evo_lightbox').attr('data-cal_id');
				if(typeof attr !== typeof undefined && attr !== false){
					var cal_id = attr;
					var CAL = cal = $('#'+cal_id);
				}else{
					var CAL = cal = obj.closest('.ajde_evcal_calendar');
				}

				SC = CAL.evo_shortcode_data();

				var evodata = cal.find('.evo-data');

				click_sinev_box = (obj.closest('.eventon_single_event').length>0 && evodata.data('exturl')) ? true: false;
				const event_id = obj.closest('.eventon_list_event').data('event_id');
				var event_list = obj.closest('.eventon_events_list');
				const event_box = obj.closest('.eventon_list_event');
							
				// event specific values
				var ux_val = obj.data('ux_val');
				var exlk = obj.data('exlk');	
				
				// override overall calendar user intereaction OVER individual event UX
				if('ux_val' in SC && SC.ux_val!='' && SC.ux_val!== undefined && SC.ux_val!='0'){
					ux_val = SC.ux_val;
				}

				// special mobile only user interaction 
					if( SC.ux_val_mob !== undefined && SC.ux_val_mob != '' && 
						SC.ux_val_mob != '-' && SC.ux_val_mob != ux_val){
						if( CAL.evo_is_mobile() ) ux_val = SC.ux_val_mob;
					}


				// open as lightbox
				if(ux_val=='3' || ux_val == '3a'){
					event.preventDefault();

					repeat_interval = parseInt(obj.closest('.eventon_list_event').data('ri'));
					repeat_interval = (repeat_interval)? repeat_interval: '0';
					
					var alt_SC_data = {};


					alt_SC_data['repeat_interval'] = repeat_interval;
					alt_SC_data['ux_val'] = ux_val;
					alt_SC_data['evortl'] = event_list.hasClass('evortl')? 'yes':'no';
					alt_SC_data['event_id'] = parseInt(event_id);
					alt_SC_data['ajax_eventtop_show_content'] = true;

					var new_SC_data = $.extend( {}, SC , alt_SC_data );

					// since 4.6
					CAL.evo_cal_lightbox_trigger( new_SC_data, obj, CAL);
					
					return false;

				// open in single events page 
				}else if(ux_val=='4'){		
					
					var url = obj.attr('href');
					
					if( url =='' ||  url === undefined){

						url = obj.parent().siblings('.evo_event_schema').find('a').attr('href');
						window.open(url, '_self');		
					}

					if(obj.attr('target') != '_blank')	window.open(url, '_self');

					return;

				// open in single events page  in new window
				}else if(ux_val=='4a'){
					
					if( obj.attr('href')!='' &&  obj.attr('href')!== undefined){
						return;
					}else{
						var url = obj.parent().siblings('.evo_event_schema').find('a').attr('href');
						window.open(url);
						return false;
					}

				// open as external link
				}else if(ux_val=='2'){
					//var url = obj.parent().siblings('.evo_event_schema').find('a').attr('href');
					var url = obj.attr('href');

					// if the click is coming from single event box
					if( click_sinev_box ){
						event.preventDefault();
						return false;
					}

					//console.log(url);
					if(url !== undefined && url != ''){
						if(obj.attr('target') == '_blank'){  
							var win = window.open(url, '_blank');
							win.focus();
						}else{
							window.open(url, '_self');
						}	

						event.preventDefault();				
					}
					return true;

				// do not do anything
				}else if(ux_val=='X'){
					return false;
				}else if(ux_val=='none'){
					return false;
				}else{
					
					// redirecting to external link
					if(exlk=='1' && ux_val!='1'){
						// if there is no href
						if( obj.attr('href')!='' &&  obj.attr('href')!== undefined){
							return;
						}else{
							var url = obj.siblings('.evo_event_schema').find('a').attr('href');
							if(obj.attr('target') == '_blank'){  window.open(url);}else{ window.open(url, '_self');}

							event.preventDefault();								
							return false;
						}
					// SLIDE DOWN eventcard
					}else{

						const click_item = event_box.find('.event_description');
						var __is_slide_down = false;

						if(click_item.hasClass('open')){
							event_box.removeClass('open');
							click_item.slideUp().removeClass('open');
						}else{
							// accordion
							if( SC.accord == 'yes'){
								cal.find('.eventon_list_event').removeClass('open');
								cal.find('.event_description').slideUp().removeClass('open');
							}
							event_box.addClass('open');
							click_item.slideDown().addClass('open');
							__is_slide_down = true;						
						}
						
						// load google maps
						if( event_box.find('.evo_metarow_gmap').length > 0){
							event_box.find('.evo_metarow_gmap').evo_load_gmap({trigger_point:'slideDownCard'});
						}

						
						// trigger 
						if( obj.data('runjs')){
							$('body').trigger('evo_load_single_event_content',[ event_id, obj]);
						}	

						$('body').trigger('evo_slidedown_eventcard_complete',[ event_id, obj, __is_slide_down ]);			

						return false;
					}
				}
			})

		;
	}


// Other	
		
	// Calendar Interaction
		// event bubbles
			$('.ajde_evcal_calendar.bub').on('mouseover','.eventon_list_event', function(){
				O = $(this);
				LIST = O.closest('.eventon_events_list');
				title = O.find('.evoet_dayblock').data('bub');

				p = O.position();

				LIST.append('<span class="evo_bub_box" style="">'+ title +"</span>");
				B = LIST.find('.evo_bub_box');

				l = p.left;
				t = p.top- B.height() -30;

				// adjust bubble to left if event on right edge
				LM = LIST.width();
				tl = p.left + B.width() + O.width();
				if(   tl > LM){
					l = l - B.width() +O.width()-20;
				}

				B.css({'top':t, 'left':l});

				LIST.find('.evo_bub_box').addClass('show');
			}).on('mouseout',function(){
				B = $(this).find('.evo_bub_box').remove();
			});

	// MONTH jumper
		$('.ajde_evcal_calendar').on('click','.evo-jumper-btn', function(){
			$(this).closest('.calendar_header').find('.evo_j_container').toggle();
			$(this).toggleClass('vis');
		});

		// select a new time from jumper
		$('.evo_j_dates').on('click','a',function(){
			var val = $(this).attr('data-val'),
				type = $(this).parent().parent().attr('data-val'),
				CAL = $(this).closest('.ajde_evcal_calendar');
				SC = CAL.evo_shortcode_data();

			if(type=='m'){ // change month
				CAL.evo_update_cal_sc({F:'fixed_month', V: val });
			}else{
				CAL.evo_update_cal_sc({F:'fixed_year', V: val });
			}

			run_cal_ajax( CAL.attr('id') ,'none','jumper');
			
			// hide month jumper if not set to leave expanded
			if(SC.expj =='no')	container.delay(2000).slideUp();
		});

	// RESET general calendar
		// @U 2.8.9
		BODY.on('evo_trigger_cal_reset', function(event, cal){
			cal_resets( cal );
		});
		function cal_resets(calOBJ){
			calargs = $(calOBJ).find('.cal_arguments');
			calargs.attr('data-show_limit_paged', 1 );

			calOBJ.evo_update_cal_sc({
				F:'show_limit_paged',V:'1'
			});
		}
	
	// Tab view switcher
		$('body').find('.evo_tab_container').each(function(){
			$(this).find('.evo_tab_section').each(function(){
				if(!$(this).hasClass('visible')){
					$(this).addClass('hidden');
				}
			});
		});
		$('body').on('click','.evo_tab',function(){
			tab = $(this).data('tab');
			tabsection = $(this).closest('.evo_tab_view').find('.evo_tab_container');
			tabsection.find('.evo_tab_section').addClass('hidden').removeClass('visible');
			tabsection.find('.'+tab).addClass('visible').removeClass('hidden');

			$(this).parent().find('.evo_tab').removeClass('selected');
			$(this).addClass('selected');

			$('body').trigger('evo_tabs_newtab_selected',[ $(this)]);
		});

	// layout view changer - legacy
		if($('body').find('.evo_layout_changer').length>0){
			// menu button focus adjust
			$('body').find('.evo_layout_changer').each(function(item){
				if($(this).parent().hasClass('boxy')){
					$(this).find('.fa-th-large').addClass('on');
				}else{
					$(this).find('.fa-reorder').addClass('on');
				}
			});

			// interaction
			$('.evo_layout_changer').on('click','i',function(){
				const CAL = $(this).closest('.ajde_evcal_calendar');
				TYPE = $(this).data('type');
				$(this).parent().find('i').removeClass('on');
				$(this).addClass('on');

				//console.log(TYPE);
				
				if(TYPE=='row'){
					CAL.attr('class','ajde_evcal_calendar');
					// set tile colors
					CAL.find('.eventon_list_event').each(function(){
						$(this).find('.desc_trig').css('background-color',  '');
						$(this).find('.desc_trig_outter').css('background-color',  '');
					});
				}else if(TYPE =='bar'){
					CAL.attr('class','ajde_evcal_calendar  box_2 sev cev');
					
					// set tile colors
					CAL.find('.eventon_list_event').each(function(){
						const color = $(this).data('colr');
						$(this).find('.desc_trig').css('background-color',  color);
					});
				}else{

					// set tile colors
					CAL.find('.eventon_list_event').each(function(){
						const color = $(this).data('colr');
						$(this).find('.desc_trig_outter').css('background-color',  color);
					});

					CAL.attr('class','ajde_evcal_calendar boxy boxstyle0 box_2');
				}				
			});
		}
	

	// v4.0 view switcher
		BODY.on('click', '.evo_vSW',function(){
			O = $(this);
			var DATA = O.data('d');
			if(O.hasClass('focusX')) return;

			//console.log(DATA);
			CAL = O.closest('.ajde_evcal_calendar');

			// remove other additions from other views
			CAL.find('.evoADDS').hide().delay(200).queue(function(){
				$(this).remove();
			});

			var SC = CAL.evo_shortcode_data();
			const cal_tz = CAL.evo_get_global({S1:'cal_def',S2:'cal_tz'});
			var reload_cal_data = false;
			
			// Create date object
				_M1 = moment().set({'year': SC.fixed_year, 'month': ( SC.fixed_month -1 ), 'date':SC.fixed_day}).tz( cal_tz );
				_M1.set('date',1).startOf('date');
				_start = _M1.unix();
				_M1.endOf('month').endOf('date'); // move to end of month
				_end = _M1.unix();

			// DEP
				var DD = new Date(SC.fixed_year,SC.fixed_month -1 , SC.fixed_day, 0,0,0 );
				DD.setUTCHours(0);
				DD.setUTCFullYear( SC.fixed_year );
				DD.setUTCMonth( SC.fixed_month -1 );
				DD.setUTCDate( SC.fixed_day );

			// switch to normal
				O.siblings('.evo_vSW').removeClass('focusX select');					
				O.addClass('focusX select');
				CAL.find('.evo-viewswitcher-btn em').html( O.html() );
				O.closest('.evo_cal_view_switcher').removeClass('show');

			// ux_val for specific cal
				if( DATA && 'ux_val' in DATA)	CAL.evo_update_cal_sc({F:'ux_val', V: DATA.ux_val });

			// calendar class toggling
				O.siblings('.evo_vSW').each(function(){
					var _d = $(this).data('d');
					if( _d && 'c' in _d )	CAL.removeClass( _d['c'] ); // remove other cls
				});
				if( DATA && 'c' in DATA)	CAL.addClass( DATA.c );
		

			// process date times block
				CAL.find('.evoet_dayblock span').hide();
				CAL.find('.evoet_dayblock span.evo_start').show();
				CAL.find('.evoet_dayblock span.evo_end').show();
				CAL.find('.evoet_dayblock span.evo_end.only_time').hide();

			// if current date range is not a month load those unix
			if( SC.focus_start_date_range != _start && SC.focus_end_date_range != _end ){
				reload_cal_data = true;
				CAL.evo_update_cal_sc({F:'focus_start_date_range',V: _start });
				CAL.evo_update_cal_sc({F:'focus_end_date_range', V: _end });
			}

			// treating events list based on dif preferences--  vals el_visibility = hide_events, show_events
				if( 'el_visibility' in DATA){
					el_visibility = DATA.el_visibility;

					if( el_visibility =='show_events') CAL.find('.eventon_list_event').show();
					if( el_visibility =='hide_events') CAL.find('.eventon_list_event').hide();
					if( el_visibility =='hide_list') CAL.find('#evcal_list').addClass('evo_hide').hide();
					if( el_visibility =='show_all'){
						CAL.find('#evcal_list').removeClass('evo_hide').show();
						CAL.find('.eventon_list_event').show();
					} 
				}				

			CAL.evo_update_cal_sc({F:'calendar_type', V: 'default'});
			
			$('body').trigger('evo_vSW_clicked_before_ajax', [ O, CAL, DD, reload_cal_data ]);

			// run ajax to load new events in the range
				if( reload_cal_data ){
					$('body').trigger('evo_run_cal_ajax',[CAL.attr('id'),'none','filering']);
				}else{
					$('body').trigger('evo_vSW_clicked_noajax', [ O, CAL ]); // @s4.6
				}
									
			$('body').trigger('evo_vSW_clicked', [ O, CAL, DD, reload_cal_data]);

			// switching to and from tiles view
				if( O.hasClass('evoti')){
					CAL.find('.eventon_list_event').each(function(){
						color = $(this).data('colr');
						$(this).find('a.desc_trig').css({'background-color': color});
					});
					CAL.addClass('color').removeClass('sev').data('oC', 'sev');
				}else{
					if( CAL.hasClass('esty_0') || CAL.hasClass('esty_4') ){
						CAL.removeClass('color');
						CAL.find('.eventon_list_event').each(function(){
							$(this).find('a.desc_trig').css({'background-color': ''});
						});
						if( CAL.data('oC') !== undefined) CAL.addClass( CAL.data('oC'));
					}
				}

		});
	
	// SORTING & FILTERING		
		// Sorting	
			// update calendar based on the sorting selection
				$('body').on('click', '.evo_sort_option',function(){
					O = $(this);
					var CAL = O.closest('.ajde_evcal_calendar');
					var sort_by = O.data('val');
					
					// update new values everywhere
					CAL.evo_update_cal_sc({F:'sort_by',V:sort_by});

					O.parent().find('p').removeClass('select');
					O.addClass('select');	

					run_cal_ajax(CAL.attr('id'),'none','sorting');						
				});		

		// close filter menus on click outside
			BODY.on('clicked_on_page',function( ev, obj, ee){

				// hide filter menu when clicked outside 4.6.2
				if( !(obj.hasClass('eventon_filter')) && 
					!(obj.hasClass('filtering_set_val')) &&
					!(obj.hasClass('evo_filter_val')) &&
					!(obj.hasClass('evofp_filter_search_i')) &&
					obj.parents('.filtering_set_val').length == 0 
				){
					//console.log(obj);
					BODY.find('.evo_filter_menu').html('');
					BODY.find('.evo_filter_tax_box.vis').removeClass('vis');
				}
			});

		// Filtering
			$.fn.evo_cal_filtering = function(O){
				
				var opt = $.extend({}, O);

				var el = this; // cal
				const sortbox = el.find('.eventon_sorting_section'),
					filter_container = sortbox.find('.evo_filter_container_in'),
					filter_line = sortbox.find('.eventon_filter_line'),
					fmenu = sortbox.find('.evo_filter_menu'),
					all_cal_filter_data = el.evo_get_filter_data(),
					SC = el.evo_shortcode_data();
				var tterms = [];


				var init = function(){

					if( el.hasClass('filters_go'))	return;

					el.addClass('filters_go');
					
					draw_filter_bar();
					filter_actions();
					run_filter_nav_check();
				}

				// draw the filter bar
				var draw_filter_bar = function(){
					//console.log(all_cal_filter_data);

					BODY.trigger('evo_filter_before_draw', [ el ]);

					html = '';
					$.each( all_cal_filter_data , function( index, value){

						// skip fast filter items
						if( SC.fast_filter == 'yes' && SC.ff_tax != '' && SC.ff_tax !== undefined ){
							__t = SC.ff_tax.split(',');
							if( __t.includes( index ) ) return;
						}

						html += "<div class='eventon_filter evo_filter_tax_box evo_hideshow_st "+index+"' data-tax='"+ value.__tax +"' data-filter_type='"+ value.__filter_type +"'>";
						html += "<div class='eventon_filter_selection'>";
							html += "<p class='filtering_set_val'><i class='fa fa-check'></i> "+ value.__name +"<em class='fa fa-caret-down'></em></p>";							
						html += "</div>";
						html += "</div>";
					});

					filter_line.html( html );

					BODY.trigger('evo_filter_drawn', [ el ]);
				}


				// filter all actions
				var filter_actions = function(){

					// show/hide filter bar
						el.on('click','.evo-filter-btn',function(){
							
							BODY.trigger('evo_filter_btn_trig', [ el , O ]);

							if( el.hasClass('fp_lb')) return; // PLUG 4.6.4

							if( !( $(this).hasClass('vis') ) ){
								sortbox.addClass('vis');
								run_filter_nav_check();
							}else{
								sortbox.removeClass('vis');
							}

						});

						// close filter when other sibling buttons clicked 4.6.4
						BODY.on('evo_cal_header_btn_clicked',function(event, O){							
							if( O.hasClass('evo-sort-btn') || O.hasClass('evo-search')  ){
								const CAL = O.closest('.ajde_evcal_calendar');
								CAL.find('.eventon_sorting_section').removeClass('vis');
							}
						});
						
						
					// show hide menu
					el.on('click','.filtering_set_val',function(){

						O = $(this);
						const filterbox = O.closest('.evo_filter_tax_box'),
						filter_tax = filterbox.data('tax');
						selected_terms = el.evo_cal_get_filter_sub_data( filter_tax , 'tterms' );

						// close sort menu
							el.find('.eventon_sort_line').hide();

						// hide already opened menus
							if( filterbox.hasClass('vis')){
								filterbox.removeClass('vis');							
								close_filter_menu();
								return;
							}
							if( fmenu.data('tax') == filter_tax ){	
								filterbox.removeClass('vis');							
								close_filter_menu();
								return;
							}else{
								sortbox.find('.filtering_set_val').removeClass('show');
								sortbox.find('.evo_filter_tax_box').removeClass('vis');
								filterbox.addClass('vis');	
							}

						// build the filter menu from data
							var filter_item_data = all_cal_filter_data[ filter_tax ].__list;

							//console.log(all_cal_filter_data);
							//console.log(selected_terms);
							
							var __menu_html = '<div class="evo_filter_inside evo_filter_menu_in" data-tax="'+filter_tax+'"><div class="eventon_filter_dropdown">';
							
							// each term
							$.each(filter_item_data, function (index, val){

								var icon_html = '';
								var _class = filter_tax+'_'+ val[0] + ' '+ val[0];
								
								// parent or child term
								if( val[3] !== undefined && val[3] != '' && val[3] == 'n') _class += ' np';

								//console.log(selected_terms);
								// select or not
								if( selected_terms == 'all' )  _class += ' select';
								if( selected_terms.includes( val[0] ) )  _class += ' select';

								// icon
								if( val[2] != '' && val[2] !== undefined ){
									_class += ' has_icon'; icon_html = val[2];
								} 

								__menu_html += '<p class="evo_filter_val '+ _class +'" data-id="'+ val[0] +'">'+ icon_html + val[1]+'</p>'
							});
							__menu_html += "</div></div>";

							// 4.6.4
							BODY.trigger('evo_filter_menu_html_ready', [ el , __menu_html , O , filterbox, filter_tax]);


							if( el.hasClass('fp_side')) return; // PLUG
							

							
						// set new menu with correct location
							const scrolled_width = filter_container.scrollLeft();
							fmenu.html( __menu_html );

							// pluggable
							BODY.trigger('evo_filter_menu_built', [ el , fmenu , filter_tax ]);
							

							__left_margin = filterbox.position().left + 10 - scrolled_width;
							__menu_width = fmenu.find('.evo_filter_inside').width();

							__cal_left_margin = el.position().left;

							//console.log(__cal_left_margin + ' '+ __left_margin +' '+ __menu_width + ' '+ $(window).width());

							if( __left_margin + __menu_width + __cal_left_margin > $(window).width()  ){
								
								if(  ( __left_margin + __menu_width ) > el.width() ){
									new_left = el.width() - __menu_width - 10;
								}else{
									new_left = ( el.width() - __menu_width ) / 2;
								}								
								fmenu.css('left', new_left );
							}else{
								fmenu.css('left', __left_margin);
							}
					});

					// select a static filter menu item
						el.on('click','p.filtering_static_val',function(){

							BODY.trigger('evo_filter_static_clicked', [ el , $(this) ]);
												
						});

					// select terms in filter menu
						el.on('click','p.evo_filter_val',function (){

							var O = $(this);
							const filter_menuIN = O.closest('.evo_filter_inside'),
								filter_tax = filter_menuIN.data('tax'),
								filterbox = sortbox.find('.evo_filter_tax_box.'+ filter_tax),
								all_terms_obj = filter_menuIN.find('p'),
								new_term_id = O.data('id'),
								old_terms = el.evo_cal_get_filter_sub_data( filter_tax , 'terms' )
								;
							var tterms = el.evo_cal_get_filter_sub_data( filter_tax , 'nterms' );

							var new_terms = [];


							// select filter type
							if( SC.filter_type == 'select' ){

								// all value
								if( new_term_id == 'all' ){
									if( O.hasClass('select') ){
										all_terms_obj.removeClass('select');
									}else{
										all_terms_obj.addClass('select');
										new_terms.push('all');
									}
								}else{
									// unselect all value
									filter_menuIN.find('p.all').removeClass('select');
									O.toggleClass('select');	

									var unselect_count = 0;
									all_terms_obj.each(function(){
										if( $(this).hasClass('select')){
											new_terms.push( $(this).data('id') )
										}else{
											// not select
											if(!$(this).hasClass('all')) unselect_count++;
										}
									});	

									// all selected
									if(unselect_count == 0){
										filter_menuIN.find('p.all').addClass('select');
										new_terms.push('all');
									}	

									// if all field is not visible; nothing selected = all
									if( new_terms.length == 0 && O.parent().find('p.all').length == 0) 
										new_terms.push('all');
								}							

							// non select type
							}else{ 
								// all value
								if( new_term_id == 'all' ){
									// if all is already selected
									if( O.hasClass('select')){
										new_terms.push('NOT-all');
										all_terms_obj.removeClass('select');
									}else{
										all_terms_obj.addClass('select');
										new_terms.push( new_term_id );
									}	
								}else{
									all_terms_obj.removeClass('select');
									O.addClass('select');
									new_terms.push( new_term_id );
								}

								update_filter_data( filter_tax, new_terms );

								// process selection @4.6.6
									if( tterms == new_terms ){
										close_filter_menu();
									}else{					
										cal_resets( el );
												
										el.evo_update_sc_from_filters();					

										run_cal_ajax( el.attr('id') ,'none','filering');
																								
										close_filter_menu();
										O.removeClass('show');
									}

								close_filter_menu();
								// mark hide of menu
								filterbox.removeClass('vis');
							}
							
							// show and hide apply filter button
								// if new terms = temp terms
									if( compare_terms( new_terms, tterms) ){
										filterbox.removeClass('chg');										
									}else{
										filterbox.addClass('chg');			
									}
									if( compare_terms( old_terms, new_terms) ){
										filterbox.removeClass('set');	
									}else{
										filterbox.addClass('set');
									}								
									

								// changed filters
								var chg_filters = sortbox.find('.evo_filter_tax_box.chg').length;
								var set_count = sortbox.find('.evo_filter_tax_box.set').length;
								if( SC.filter_type == 'select' ) 
									( chg_filters > 0 ) ? show_apply_btns() : hide_apply_btns();


							// Filter highlighted indicator 
								if( !( el.hasClass('flhi') ) ){

									const filter_btn = el.find('.evo-filter-btn');	

									if( set_count > 0){
										filter_btn.find('em').html( set_count ).addClass('o');	
									}else{
										filter_btn.find('em').removeClass('o');			
									}			
								}



							update_filter_data( filter_tax, new_terms , 'tterms');

							run_filter_nav_check();

						});
													
					// apply filters button
						el.on('click','.evo_filter_submit',function(){

							el.evo_filters_update_from_temp( filter_line, el );
							
							cal_resets( el);

							close_filter_menu(); // hide filter menu

							// update filter item button
							sortbox.find('.filtering_set_val').removeClass('show');

							el.evo_update_sc_from_filters();	// update shortcode from filters
							
							run_cal_ajax( el.attr('id'),'none','filering');

							run_filter_nav_check();
						});

					// clear filters
						el.on('click','.evo_filter_clear',function(){
								
							el.find('.evo_filter_tax_box').each(function(){
								const O = $(this),
									tax = O.data('tax'),
									terms = O.data('terms');

								O.removeClass('set');
								O.find('.filtering_set_val').removeClass('set show');
								el.find('.evo-filter-btn em').removeClass('o');

								close_filter_menu();
							});

							// update all filters with default/ onload values @4.6.1
							$.each( all_cal_filter_data, function( tax, tdata){
								update_filter_data( tax, tdata.terms );
							} );


							hide_apply_btns(); // hide filter action buttons
							
							// update shortcode and run new ajax for events
							el.evo_update_sc_from_filters();					
							run_cal_ajax( el.attr('id'),'none','filering');

							run_filter_nav_check();
						});

					// click on filter navs
						el.on('click','.evo_filter_nav',function(){
							O = $(this);

							_filter_bar = O.closest('.evo_filter_bar');
							_filter_container = _filter_bar.find('.evo_filter_container_in');
							_filter_line_width = _filter_bar.find('.eventon_filter_line')[0].scrollWidth;
							_filter_container_width = parseInt( _filter_container.width() ) + 0;
							_leftPos = _filter_container.scrollLeft();
							_scrollable_legth = _filter_line_width - _filter_container_width;

							const scroll_length = _filter_container_width /2;
							
							// move right
							if( O.hasClass('evo_filter_r') ){
																		
								_filter_container.animate({scrollLeft:_leftPos + scroll_length},200);
								_filter_bar.find('.evo_filter_l').addClass('vis');
							// move left
							}else{
								sleft = (_leftPos - scroll_length < scroll_length) ? 0 :  _leftPos - scroll_length;
								_filter_container.animate({scrollLeft: sleft },200);											
							}

							close_filter_menu();

							setTimeout(function(){
								var _leftPos = _filter_container.scrollLeft();
								//console.log(_leftPos);
								if( _leftPos < 10){
									_filter_bar.find('.evo_filter_l').removeClass('vis');
									_filter_bar.find('.evo_filter_r').addClass('vis');
								}

								if( _leftPos > ( _scrollable_legth - 5 ) ){
									_filter_bar.find('.evo_filter_r').removeClass('vis');
								}
							},200);
												
						});

					// on window size change
					$(window).on('resize',function(){
						run_filter_nav_check();
					});
				}

				var compare_terms = function(a, b){
					if (a === b) return true;
					if (a == null || b == null) return false;
					if (a.length !== b.length) return false;

					for (var i = 0; i < a.length; ++i) {
					   if (a[i] !== b[i]) return false;
					}
					return true;
				}
				var close_filter_menu = function(){
					fmenu.html('').data('tax','');
				}

				var show_apply_btns = function(){
					sortbox.find('.evo_filter_aply_btns').addClass('vis');	
				}
				var hide_apply_btns = function(){
					sortbox.find('.evo_filter_aply_btns').removeClass('vis');		
				}

				var update_filter_data = function(tax, new_val, key){

					el.evo_cal_update_filter_data( tax , new_val , key );					
				}


				// adjust and position filter nav buttons
				var run_filter_nav_check = function(){

					$.each( el.find('.evo_filter_bar') , function(event){
						_filter_bar = $(this);
						_filter_container = _filter_bar.find('.evo_filter_container_in');
						_filter_line_width = _filter_bar.find('.eventon_filter_line')[0].scrollWidth;
						_filter_container_width = parseInt( _filter_container.width() ) + 3;
						var leftPos = _filter_container.scrollLeft();

						//console.log(_filter_line_width +' '+ _filter_container_width +' '+leftPos);

						// filter line is not showing full
						if( _filter_line_width > _filter_container_width ){

							// if some of filter line is hidden on right
							if( ( _filter_container_width + leftPos  ) < _filter_line_width )
								_filter_bar.find('.evo_filter_r').addClass('vis');

							// if filter has been scrolled
							if( leftPos > 0 ){
								_filter_bar.find('.evo_filter_l').addClass('vis');
							}else{
								_filter_bar.find('.evo_filter_r').addClass('vis');
							}
						}else{
							_filter_bar.find('.evo_filter_l').removeClass('vis');
							_filter_bar.find('.evo_filter_r').removeClass('vis');
						}

					});

					//console.log(filter_line_width +' '+ filter_container_width +' '+leftPos);					
				}

				init();
			}

			// for each tax move tterms value to nterms / before sending ajax
			$.fn.evo_filters_update_from_temp = function(filter_line, cal){
				// move temp term values into new terms
					filter_line.find('.evo_filter_tax_box').each(function(){
						var taxonomy = $(this).data('tax');
						const tterms = cal.evo_cal_get_filter_sub_data( taxonomy , 'tterms');

						cal.evo_cal_update_filter_data( taxonomy , tterms, 'nterms');

						$(this).removeClass('chg');
					});
			}
				

	// PRIMARY hook to get content	 
		// MAIN AJAX for calendar events v2.8
		function run_cal_ajax( cal_id, direction, ajaxtype){
			
			// identify the calendar and its elements.
			var CAL = ev_cal = $('#'+cal_id); 

			// check if ajax post content should run for this calendar or not			
			if(CAL.attr('data-runajax')!='0'){

				// category filtering for the calendar
				var cat = CAL.find('.evcal_sort').attr('cat');

				// reset paged values for switching months
				if(ajaxtype=='switchmonth'){
					CAL.find('.cal_arguments').attr('data-show_limit_paged',1);
					CAL.evo_update_cal_sc({F:'show_limit_paged', V: '1'});
				}	

				SC = CAL.evo_cal_functions({action:'load_shortcodes'});

				$('body').trigger('evo_main_ajax_before', [CAL, ajaxtype, direction, SC]);		

				var data_arg = {
					//action: 		'eventon_get_events',
					direction: 		direction,
					shortcode: 		SC,
					ajaxtype: 		ajaxtype,
					nonce: 			evo_general_params.n
				};	

				EVENTS_LIST = CAL.find('.eventon_events_list');

				$.ajax({
					// preload actions
					beforeSend: function(){
						CAL.addClass('evo_loading');

						// paged -- adding events to end
						if(ajaxtype == 'paged'){
							txt = EVENTS_LIST.find('.evoShow_more_events').html();
							EVENTS_LIST.find('.evoShow_more_events').html('. . .').data('txt',txt);
						}else{

							html = evo_general_params.html.preload_events;
							if( SC.tiles == 'yes') html = evo_general_params.html.preload_event_tiles;

							EVENTS_LIST.html( html );
							//EVENTS_LIST.slideUp('fast');
						}	

						// maintain scrolltop location 4.6
						if( CAL.hasClass('nav_from_foot')){
							
							scrolltop = (CAL.find('.evo_footer_nav').offset().top) - CAL.data('viewport_top');
							$('html, body').animate({	scrollTop: scrolltop	},20);
						}	

						$('body').trigger('evo_main_ajax_before_fnc',[CAL, ajaxtype, data_arg ]);	//s4.6			
					},
					type: 'POST', url: get_ajax_url('eventon_get_events'),data: data_arg,dataType:'json',
					success:function(data){
						if(!data) return false;

						// paged calendar
						if(ajaxtype == 'paged'){	
							EVENTS_LIST.find('.evoShow_more_events').remove();
							EVENTS_LIST.find('.clear').remove();


							EVENTS_LIST.append( data.html + "<div class='clear'></div>");

							// hide show more events if all events loaded
							var events_in_list = EVENTS_LIST.find('.eventon_list_event').length;
							if( 'total_events' in data && data.total_events == events_in_list){
								EVENTS_LIST.find('.evoShow_more_events').hide();
							}	

							// for month lists duplicate headers // @+2.8.1
							var T = {};
							EVENTS_LIST.find('.evcal_month_line').each(function(){
								d = $(this).data('d');
								if( T[d]) 
									$(this).remove();
								else
									T[d] = true;
							});

							var T = {};
							EVENTS_LIST.find('.sep_month_events').each(function(){
								d = $(this).data('d');
								if( T[d]){
									var H = $(this).html();
									EVENTS_LIST.find('.sep_month_events[data-d="'+d+'"]').append( H );
									$(this).remove();
								}else{T[d] = true;}
							});
							
						}else{
							EVENTS_LIST.html(data.html);
						}

						
						// update calendar data
						animate_month_switch(data.cal_month_title, CAL.find('.evo_month_title'));
						CAL.evo_cal_functions({action:'update_shortcodes',SC: data.SC});
						CAL.evo_cal_functions({action:'update_json',json: data.json});

						// run cal process code
						CAL.evo_calendar({
							SC: data.SC,
							json: data.json
						});
							

						$('body').trigger('calendar_month_changed',[CAL, data]);
						
						$('body').trigger('evo_main_ajax_success', [CAL, ajaxtype, data, data_arg]);
															
					},complete:function(data){

						// show events list events if not set to hide on load
						if(! EVENTS_LIST.hasClass('evo_hide')) EVENTS_LIST.delay(300).slideDown('slow');
						
						// maintain scrolltop location 4.6
						if( CAL.hasClass('nav_from_foot')){
							
							setTimeout(function(){
								scrolltop = (CAL.find('.evo_footer_nav').offset().top) - CAL.data('viewport_top');
								$('html, body').animate({	scrollTop: scrolltop	},20);
								CAL.removeClass('nav_from_foot');
							},302);														
						}					

						// pluggable
						$('body').trigger('evo_main_ajax_complete', [CAL, ajaxtype, data.responseJSON , data_arg]);
						CAL.removeClass('evo_loading');
					}
				});
			}			
		}

		$('body').on('evo_run_cal_ajax',function(event,cal_id, direction, ajaxtype){
			run_cal_ajax( cal_id, direction, ajaxtype);
		});

		// deprecated bridge function for sortby value 
		function ajax_post_content(sortby, cal_id, direction, ajaxtype){
			run_cal_ajax( cal_id, direction, ajaxtype);
		}

	// Click events listener - 4.6
		$(document).on('click', function(event) {
			//event.stopPropagation(); 
			//console.log($(event.target));
			BODY.trigger('clicked_on_page', [ $(event.target) , event ]);
		    
		    //console.log(event);
		});

	// subtle animation when switching months
		function animate_month_switch(new_data, title_element){			
			var current_text = title_element.html();
			var CAL = title_element.closest('.ajde_evcal_calendar');

			title_element.html(new_data);
			return;
		}
	
	// event location archive card page
		// @u 2.8.6
		$('body').find('.evo_location_map').each(function(){			
			$(this).evo_load_gmap();
		});

		// on event card lightbox load -> taxonomy details @since 4.2 u4.6
		$('body').on('evo_ajax_complete_eventon_get_tax_card_content', function(event,  OO){
			
			LB = $('body').find('.'+ OO.lightbox_key);

			// run map load
			if( LB.find('.evo_trigger_map').length > 0 ){
				map_id_elm = LB.find('.evo_trigger_map');			
				map_id_elm.evo_load_gmap();
			}
			
			// run countdown timers
			LB.find('.evo_countdowner').each(function(){
				$(this).evo_countdown();
			});


			// run calendar filtering function
			CAL = LB.find('.ajde_evcal_calendar');
			CAL.evo_cal_filtering();
			
		});
		
	// SINGLE EVENTS
		// Loading single event json based content
			$('body').on('evo_load_single_event_content', function(event, eid, obj){
				var ajaxdataa = {};
				ajaxdataa['eid'] = eid;
				ajaxdataa['nonce'] = the_ajax_script.postnonce;	

				// pass on other event values
				if(obj.data('j')){
					$.each(obj.data('j'), function(index,val){
						ajaxdataa[ index] = val;
					});
				}			
				
				$.ajax({
					beforeSend: function(){ 	},	
					url:	get_ajax_url('eventon_load_event_content'),
					data: 	ajaxdataa,	dataType:'json', type: 	'POST',
					success:function(data){
						$('body').trigger('evo_single_event_content_loaded', [data, obj]);
					},complete:function(){ 	}
				});
			});
	
		if(BODY.evo_is_mobile()){
			if($('body').find('.fb.evo_ss').length != 0){
				$('body').find('.fb.evo_ss').each(function(){
					obj = $(this);
					obj.attr({'href':'http://m.facebook.com/sharer.php?u='+obj.attr('data-url')});
				});
			}
		}

		// on single event page
		if($('body').find('.evo_sin_page').length>0){
			$('.evo_sin_page').each(function(){
				$('body').trigger('evo_load_single_event_content',[ $(this).data('eid'), $(this)]);
				$(this).find('.desc_trig ').attr({'data-ux_val':'none'});
			});
		}
		
		// Single events box
			// Click on single event box
				$('.eventon_single_event').on('click', '.evcal_list_a',function(event){
					var obj = $(this);				
					var CAL = obj.closest('.ajde_evcal_calendar');
					var SC = CAL.evo_shortcode_data();

					event.preventDefault();

					// open in event page
					if(SC.ux_val == 4){ 
						var url = obj.parent().siblings('.evo_event_schema').find('[itemprop=url]').attr('href');
						window.location.href= url;
					}else if(SC.ux_val == '2'){ // External Link
						var url = SC.exturl;
						window.location.href= url;
					}else if(SC.ux_val == 'X'){ // do not do anything
						return false;
					}
				});
			// each single event box
				$('body').find('.eventon_single_event').each(function(){
					var _this = $(this);

					var CAL = _this.closest('.ajde_evcal_calendar');
					var SC = CAL.evo_shortcode_data();	
					var evObj = CAL.find('.eventon_list_event');									

					// show expanded eventCard
					if( SC.expanded =='yes'){
						_this.find('.evcal_eventcard').show();
						var idd = _this.find('.evcal_gmaps');						

						// close button
						_this.find('.evcal_close').parent().css({'padding-right':0});
						_this.find('.evcal_close').hide();

						//console.log(idd);
						var obj = _this.find('.desc_trig');

						// Google Map
						_this.find('.evo_metarow_gmap').evo_load_gmap();
					
						// mark as eventcard open @since 4.4
						evObj.find('.event_description').addClass('open');

					// open eventBox and lightbox	
					}else if(SC.uxval =='3'){

						var obj = _this.find('.desc_trig');
						// remove other attr - that cause to redirect
						obj.removeAttr('data-exlk').attr({'data-ux_val':'3'});
					}

					// show event excerpt
					var ev_excerpt = CAL.find('.event_excerpt').html();
					
					if(ev_excerpt!='' && ev_excerpt!== undefined && SC.excerpt =='yes' ){
						var appendation = '<div class="event_excerpt_in">'+ev_excerpt+'</div>'
						evObj.append(appendation);
					}

					// trigger support @since 4.4
					var obj = evObj.find('.desc_trig');
					var event_id = evObj.data('event_id');


					$('body').trigger('evo_slidedown_eventcard_complete',[ event_id, obj]);	
				});


// Search Scripts
	// Enter key detection for pc
		$.fn.evo_enterKey = function (fnc) {
		    return this.each(function () {
		        $(this).keypress(function (ev) {
		            var keycode = (ev.keyCode ? ev.keyCode : ev.which);
		            if (keycode == '13') {
		                fnc.call(this, ev);
		            }
		        })
		    })
		}

	BODY.on('evo_cal_header_btn_clicked',function(event, O, CAL){

		if( O.hasClass('evo-search')){
			if( O.hasClass('vis')){
				CAL.find('.evo_search_bar').show(1, function(){
					$(this).find('input').focus();
				});
			}else{
				CAL.find('.evo_search_bar').hide();
			}
		}

		if( O.hasClass('evo-sort-btn') || O.hasClass('evo-filter-btn')){
			CAL.find('.evo_search_bar').hide();
		}

	});
		


	// Submit search from search box u 4.5.8
		$('body').on('click','.evo_do_search',function(){
			do_search_box( $(this) );
		});

		// dynamic enter key press on the search input field @4.2
		$(".evo_search_field").evo_enterKey(function () {
			do_search_box( $(this).siblings('.evo_do_search') );
		});
			

		// primary search function
		function do_search_box(OBJ){
			SearchVal = OBJ.closest('.evosr_search_box').find('input').val();
			Evosearch = OBJ.closest('.EVOSR_section');
			OBJ.closest('.evo_search_entry').find('.evosr_msg').hide();

			//console.log(SearchVal);

			if( SearchVal === undefined || SearchVal == ''){
				OBJ.closest('.evo_search_entry').find('.evosr_msg').show();
				return false;
			}

			$('body')
			.on('evo_ajax_beforesend_evo_get_search_results', function (event, uid){
				Evosearch.find('.evo_search_results_count').hide();
				Evosearch.addClass('searching');
			}).on('evo_ajax_complete_evo_get_search_results', function (event, uid){
				Evosearch.removeClass('searching');
			}).on('evo_ajax_success_evo_get_search_results', function (event, uid, data){
				Evosearch.find('.evo_search_results').html( data.content);

				if(Evosearch.find('.no_events').length==0){
					// find event count
					Events = Evosearch.find('.evo_search_results').find('.eventon_list_event').length;
					Evosearch.find('.evo_search_results_count span').html( Events);
					Evosearch.find('.evo_search_results_count').fadeIn();
				}
			});

			var ajax_results = OBJ.evo_admin_get_ajax({
				'ajaxdata': {
					//action: 		'eventon_search_evo_events',
					search: 		SearchVal,
					shortcode:  	Evosearch.find('span.data').data('sc'),
					nonce: 			evo_general_params.n				
				},
				ajax_type:'endpoint',
				ajax_action:'eventon_search_evo_events',
				'uid':'evo_get_search_results',
				end: 'client'
			});

		}

	// submit search from calendar
		$('body').on('click','.evosr_search_btn',function(){	
			search_within_calendar( $(this).siblings('input') );		});
		$(".evo_search_bar_in input").evo_enterKey(function () {	
			search_within_calendar( $(this) );		});

		function search_within_calendar(obj){

			var ev_cal = obj.closest('.ajde_evcal_calendar');
			
			ev_cal.evo_update_cal_sc({F:'show_limit_paged',V: '1' });
			ev_cal.evo_update_cal_sc({F:'s',V: obj.val() });

			run_cal_ajax( ev_cal.attr('id'),'none','search');
			
		   	return false;	
		}	

		// reset search field @since 4.5
		$('body').on('evo_main_ajax_complete',function(event,CAL, ajaxtype, responseJSON , data_arg ){

			if(ajaxtype == 'search' ){
				if( data_arg.shortcode['s'] != '' ){
					CAL.find('.evosr_search_clear_btn').addClass('show');
				}					
			}
		}).on('click','.evosr_search_clear_btn',function(event){
			event.preventDefault();
			const obj = $(this);
			var ev_cal = obj.closest('.ajde_evcal_calendar');
			ev_cal.evo_update_cal_sc({F:'s',V: '' });
			run_cal_ajax( ev_cal.attr('id'),'none','search');

			obj.removeClass('show');
			obj.siblings('input').val('');
		});


// supportive
	// ajax url function  @u 4.5.5
		function get_ajax_url(action){
			var ajax_type = 'endpoint';
			if('ajax_method' in evo_general_params ) ajax_type = evo_general_params.ajax_method;
			return $('body').evo_get_ajax_url({a:action, type: 	ajax_type });
		}
	// handlebar additions
		function handlebar_additional_arguments(){
			Handlebars.registerHelper('ifE',function(v1, options){
				return (v1 !== undefined && v1 != '' && v1)
                    ? options.fn(this)
                    : options.inverse(this);
			});

			Handlebars.registerHelper('ifEQ',function(v1, v2, options){
				return ( v1 == v2)? options.fn(this): options.inverse(this);
			});
			Handlebars.registerHelper('ifNEQ',function(v1, v2, options){
				return ( v1 != v2)? options.fn(this): options.inverse(this);
			});
			Handlebars.registerHelper('BUStxt',function(V, options){	
				if( !( V in BUS.txt) ) return V;
				return BUS.txt[V];
			});
			Handlebars.registerHelper('GetDMnames',function(V, U, options){				
				return BUS.dms[U][ V ];
			});
			// get total of increments
			Handlebars.registerHelper('forAdds',function(count, add_val, options){	
				O = '';
				for(x=1; x<= count; x++){	O += add_val;	}			
				return O;
			});
			Handlebars.registerHelper('GetEvProp',function(EID, PROP, CALID){
				EID = EID.split('-');	
				EV = $('#'+ CALID).find('.evo_cal_events').data('events');
				
				var O = '';
				$.each(EV, function(i,d){
					if( d.ID == EID[0] && d.ri == EID[1]){
						if( !(PROP in d.event_pmv)) return;
						O = d.event_pmv[PROP][0];
					}
				});
				return O;
			});
			Handlebars.registerHelper('GetEvV',function(EID, PROP, CALID){
				EID = EID.split('-');	
				EV = $('#'+ CALID).find('.evo_cal_events').data('events');
				
				var O = '';
				$.each(EV, function(i,d){
					if( d.ID == EID[0] && d.ri == EID[1]){
						O = d[PROP];
					}
				});
				return O;
			});
			Handlebars.registerHelper('COUNT',function( V){		
				return Object.keys(V).length;
			});
			Handlebars.registerHelper('CountlimitLess',function( AR, C,options){		
				var L= Object.keys(AR).length;
				return ( L < C)? options.inverse(this): options.fn(this);
			});
			Handlebars.registerHelper('ifCOND',function(v1, operator, v2, options){
				return checkCondition(v1, operator, v2)
	                ? options.fn(this)
	                : options.inverse(this);
			});
			Handlebars.registerHelper('toJSON', function(obj) {
			    return new Handlebars.SafeString(JSON.stringify(obj));
			});
			Handlebars.registerHelper('Cal_def_check',function(V, options){		
				if( BUS.cal_def && BUS.cal_def[V] ) return options.fn(this);
				return options.inverse(this);
			});
			Handlebars.registerHelper('TypeCheck',function(V, options){		
				if( options.type == V ) return options.fn(this);
				return options.inverse(this);
			});
		}
		function checkCondition(v1, operator, v2) {
	        switch(operator) {
	            case '==':
	                return (v1 == v2);
	            case '===':
	                return (v1 === v2);
	            case '!==':
	                return (v1 !== v2);
	            case '<':
	                return (v1 < v2);
	            case '<=':
	                return (v1 <= v2);
	            case '>':
	                return (v1 > v2);
	            case '>=':
	                return (v1 >= v2);
	            case '&&':
	                return (v1 && v2);
	            case '||':
	                return (v1 || v2);
	            default:
	                return false;
	        }
	    }
	// @2.9.1
	// increase and reduce quantity
	    $('body').on('click','.evo_qty_change', function(event){
	        var OBJ = $(this);
	        var QTY = oQTY = parseInt(OBJ.siblings('em').html());
	        var MAX = OBJ.siblings('input').attr('max');
	        var BOX = OBJ.closest('.evo_purchase_box');

	        var pfd = BOX.find('.evo_purchase_box_data').data('pfd');
	        

	        (OBJ.hasClass('plu'))?  QTY++: QTY--;

	        QTY =(QTY==0)? 1: QTY;
	        QTY = (MAX!='' && QTY > MAX)? MAX: QTY;

	        // new total price
	        var sin_price = OBJ.parent().data('p');
	        new_price = sin_price * QTY;

	        new_price = get_format_price( new_price, pfd);

	        BOX.find('.total .value').html( new_price);

	        OBJ.siblings('em').html(QTY);
	        OBJ.siblings('input').val(QTY);

	        $('body').trigger('evo_qty_changed',[QTY,oQTY, new_price,OBJ ]);
	    });

    // Total formating
        function get_format_price(price, data){

            // price format data
            PF = data;
           
            totalPrice = price.toFixed(PF.numDec); // number of decimals
            htmlPrice = totalPrice.toString().replace('.', PF.decSep);

            if(PF.thoSep.length > 0) {
                htmlPrice = _addThousandSep(htmlPrice, PF.thoSep);
            }
            if(PF.curPos == 'right') {
                htmlPrice = htmlPrice + PF.currencySymbol;
            }
            else if(PF.curPos == 'right_space') {
                htmlPrice = htmlPrice + ' ' + PF.currencySymbol;
            }
            else if(PF.curPos == 'left_space') {
                htmlPrice = PF.currencySymbol + ' ' + htmlPrice;
            }
            else {
                htmlPrice = PF.currencySymbol + htmlPrice;
            }
            return htmlPrice;
        }
        function _addThousandSep(n, thoSep){
            var rx=  /(\d+)(\d{3})/;
            return String(n).replace(/^\d+/, function(w){
                while(rx.test(w)){
                    w= w.replace(rx, '$1'+thoSep+'$2');
                }
                return w;
            });
        };

// DEPRECATING 
	// LIGHTBOX		
		// since 4.2 moving to functions
		// open lightbox @2.9
			BODY.on('evo_open_lightbox',function(event, lb_class, content){
				const LIGHTBOX = $('.evo_lightbox.'+lb_class).eq(0);

				// if already open
				if(LIGHTBOX.is("visible")===true) return false;

				if( content != ''){
					LIGHTBOX.find('.evo_lightbox_body').html( content );
				}
				BODY.trigger('evolightbox_show', [ lb_class ]);
			});

		// click outside close LB
			BODY.on('clicked_on_page', function(event, obj, ev ){
				if( obj.hasClass('evo_content_inin')){
					closing_lightbox( obj.closest('.evo_lightbox') );
				}
			});

		// close popup
			BODY.on('click','.evolbclose', function(){	
				if( $(this).hasClass('evolb_close_btn')) return;
				LIGHTBOX = 	$(this).closest('.evo_lightbox');
				closing_lightbox( LIGHTBOX );				
			});

		// close with click outside popup box when pop is shown						
			function closing_lightbox( lightboxELM){
				
				if(! lightboxELM.hasClass('show')) return false;
				Close = (lightboxELM.parent().find('.evo_lightbox.show').length == 1)? true: false;
				lightboxELM.removeClass('show');

				$('body').trigger('lightbox_before_event_closing', [lightboxELM]);

				setTimeout( function(){ 
					lightboxELM.find('.evo_lightbox_body').html('');
					
					if(Close){
						$('body').removeClass('evo_overflow');
						$('html').removeClass('evo_overflow');
					}
					
					// trigger action to hook in at this stage
						$('body').trigger('lightbox_event_closing', [lightboxELM]);
				}, 100);
			}

		// when lightbox open triggered
			$('body').on('evolightbox_show',function(event, lb_class){
				$('.evo_lightboxes').show();
				$('body').addClass('evo_overflow');
				$('html').addClass('evo_overflow');

				$('body').trigger('evolightbox_opened',[ lb_class ]);
			});


});