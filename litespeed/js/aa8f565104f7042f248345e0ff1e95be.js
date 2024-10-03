(function($){$.fn.evo_cal_functions=function(O){el=this;switch(O.action){case 'load_shortcodes':return el.find('.evo_cal_data').data('sc');break;case 'update_json':el.find('.evo_cal_events').data('events',O.json);break;case 'update_shortcodes':el.find('.evo_cal_data').data('sc',O.SC);break}};$.fn.evo_get_global=function(opt){var defaults={S1:'',S2:''};var OPT=$.extend({},defaults,opt);var BUS=$('#evo_global_data').data('d');if(!(OPT.S1 in BUS))return!1;if(!(OPT.S2 in BUS[OPT.S1]))return!1;return BUS[OPT.S1][OPT.S2]}
$.fn.evo_get_txt=function(opt){var defaults={V:''}
var OPT=$.extend({},defaults,opt);var BUS=$('#evo_global_data').data('d');if(!('txt' in BUS))return!1;if(!(OPT.V in BUS.txt))return!1;return BUS.txt[OPT.V]}
$.fn.evo_lang=function(text){var t=text.toLowerCase().replace(/ /g,"_").replace(/[^\w-]+/g,"");var BUS=$('#evo_global_data').data('d');if(!('txt' in BUS))return text;if(!(t in BUS.txt))return text;return BUS.txt[t]}
$.fn.evo_get_cal_def=function(opt){var defaults={V:''}
var OPT=$.extend({},defaults,opt);var BUS=$('#evo_global_data').data('d');if(!('cal_def' in BUS))return!1;if(!(OPT.V in BUS.cal_def))return!1;return BUS.cal_def[OPT.V]}
$.fn.evo_get_dms_vals=function(opt){var defaults={type:'d',V:''}
var OPT=$.extend({},defaults,opt);var BUS=$('#evo_global_data').data('d');if(!('dms' in BUS))return!1;if(!(OPT.type in BUS.dms))return!1;return BUS.dms[OPT.type][OPT.V]}
$.fn.evo_admin_get_ajax=function(opt){var defs={'lightbox_key':'','lightbox_loader':!0,'load_new_content':!0,'load_new_content_id':'','hide_lightbox':!1,'hide_message':2000,'ajaxdata':{'load_lbcontent':'','load_new_content':''},'uid':'','end':'admin','loader_el':'','ajax_type':'ajax','ajax_action':'',}
var el=$(this);var OO=$.extend({},defs,opt);var ajaxdata=OO.ajaxdata;ajaxdata.nn=(OO.end=='client')?evo_general_params.n:evo_admin_ajax_handle.postnonce;if(OO.uid==''&&OO.ajax_action!='')OO.uid=OO.ajax_action;LB=!1;if(OO.lightbox_key!='')LB=$('body').find('.evo_lightbox.'+OO.lightbox_key);var returnvals='';if('a' in ajaxdata)ajaxdata.action=ajaxdata.a;ajaxdata.uid=OO.uid;var ajax_url=el.evo_get_ajax_url({a:OO.ajax_action,e:OO.end,type:OO.ajax_type});$.ajax({beforeSend:function(){$('body').trigger('evo_ajax_beforesend_'+OO.uid,[OO,el]);if(LB&&OO.lightbox_loader){LB.find('.ajde_popup_text').addClass('evoloading loading');LB.find('.evolb_content').addClass('evoloading loading')}
if(OO.loader_el){$(OO.loader_el).addClass('evoloading loading')}},type:'POST',url:ajax_url,data:ajaxdata,dataType:'json',success:function(data){if(LB){if('msg' in data&&data.msg!=''&&LB){LB.evo_lightbox_show_msg({'type':data.status,'message':data.msg,hide_lightbox:OO.hide_lightbox,hide_message:OO.hide_message})}
if(OO.ajaxdata.load_lbcontent||OO.ajaxdata.load_new_content||OO.load_new_content){if(OO.load_new_content_id!=''){$('body').find('#'+OO.load_new_content_id).replaceWith(data.content)}else{LB.evo_lightbox_populate_content({content:data.content})}}}else{if(OO.ajaxdata.load_lbcontent||OO.ajaxdata.load_new_content||OO.load_new_content){if(OO.load_new_content_id!=''){$('body').find('#'+OO.load_new_content_id).html(data.content)}}}
if('sp_content' in data){$("body").find('#evops_content').html(data.sp_content)}
if('sp_content_foot' in data){$("body").find('.evosp_foot').html(data.sp_content_foot)}
$('body').trigger('evo_elm_load_interactivity');$('body').trigger('evo_ajax_success_'+OO.uid,[OO,data,el])},complete:function(){$('body').trigger('evo_ajax_complete_'+OO.uid,[OO,el]);if(LB&&OO.lightbox_loader){LB.find('.ajde_popup_text').removeClass('evoloading loading');LB.find('.evolb_content').removeClass('evoloading loading')}
if(OO.loader_el){$(OO.loader_el).removeClass('evoloading loading')}}})}
$.fn.evo_ajax_lightbox_form_submit=function(opt){var defs={'lightbox_key':'','lightbox_loader':!0,'uid':'','end':'admin','hide_lightbox':!1,'hide_message':!1,'load_new_content':!1,'load_new_content_id':'','ajax_type':'ajax','ajax_action':'',}
var OO=$.extend({},defs,opt);const el=this;const form=this.closest('form');var LB=!1;if(OO.lightbox_key!='')LB=$('body').find('.evo_lightbox.'+OO.lightbox_key);if(LB)LB.evo_lightbox_hide_msg();var ajax_url=el.evo_get_ajax_url({a:OO.ajax_action,e:OO.end,type:OO.ajax_type});form.ajaxSubmit({beforeSubmit:function(opt,xhr){$('body').trigger('evo_ajax_beforesend_'+OO.uid,[OO,xhr,opt]);if(LB&&OO.lightbox_loader)LB.evo_lightbox_start_inloading()},dataType:'json',url:ajax_url,type:'POST',success:function(data){$('body').trigger('evo_ajax_success_'+OO.uid,[OO,data,el]);if(data.status=='good'){if(LB&&'msg' in data){LB.evo_lightbox_show_msg({'type':'good','message':data.msg,hide_lightbox:OO.hide_lightbox,hide_message:OO.hide_message})}
if(OO.load_new_content&&OO.load_new_content_id!=''){$('body').find('#'+OO.load_new_content_id).replaceWith(data.content)}else{if(OO.load_new_content)LB.evo_lightbox_populate_content({content:data.content})}
if('refresh_dom_content' in data){$.each(data.refresh_dom_content,function(domid,content){$('body').find('#'+domid).replaceWith(content)})}}else{LB.evo_lightbox_show_msg({'type':'bad','message':data.msg})}},complete:function(){$('body').trigger('evo_ajax_complete_'+OO.uid,[OO]);$('body').trigger('evo_ajax_form_complete_'+OO.uid,[OO,form]);if(LB&&OO.lightbox_loader)LB.evo_lightbox_stop_inloading()}})}
$('body').on('click','.evolb_trigger',function(event){if(event!==undefined){event.preventDefault();event.stopPropagation()}
$(this).evo_lightbox_open($(this).data('lbvals'))});$('body').on('click','.evolb_close_btn',function(){const LB=$(this).closest('.evo_lightbox');LB.evo_lightbox_close()});$('body').on('click','.evolb_trigger_save, .evo_submit_form',function(event){if(event!==undefined){event.preventDefault();event.stopPropagation()}
$(this).evo_ajax_lightbox_form_submit($(this).data('d'))});$('body').on('click','.evo_trigger_ajax_run',function(event){if(event!==undefined){event.preventDefault();event.stopPropagation()}
$(this).evo_admin_get_ajax($(this).data('d'))});$('body').on('evo_lightbox_trigger',function(event,data){$('body').evo_lightbox_open(data)});$.fn.evo_lightbox_open=function(opt){var defaults={'uid':'','t':'','lbc':'','lbac':'','lbsz':'','content':'','content_id':'','ajax':'no','ajax_url':'','d':'','end':'admin','other_data':'','lightbox_loader':!1,'preload_temp_key':'init','load_new_content':!0,'lb_padding':'evopad30','ajax_action':'','ajax_type':'ajax',};var OO=$.extend({},defaults,opt);const fl_footer=OO.end=='client'?'<div class="evolb_footer"></div>':'';var html='<div class="evo_lightbox '+OO.lbc+' '+OO.end+' '+OO.lbac+'" data-lbc="'+OO.lbc+'"><div class="evolb_content_in"><div class="evolb_content_inin"><div class="evolb_box '+OO.lbc+' '+OO.lbsz+'"><div class="evolb_header"><a class="evolb_backbtn" style="display:none"><i class="fa fa-angle-left"></i></a><p class="evolb_title">'+OO.t+'</p><span class="evolb_close_btn evolbclose "><i class="fa fa-xmark"><i></span></div><div class="evolb_content '+OO.lb_padding+'"></div><p class="message"></p>'+fl_footer+'</div></div></div></div>';$('#evo_lightboxes').append(html);LIGHTBOX=$('.evo_lightbox.'+OO.lbc);setTimeout(function(){$('#evo_lightboxes').show();LIGHTBOX.addClass('show');$('body').addClass('evo_overflow');$('html').addClass('evo_overflow')},300);LIGHTBOX.evo_lightbox_show_open_animation(OO);if(OO.content_id!=''){var content=$('#'+OO.content_id).html();LIGHTBOX.find('.evolb_content').html(content)}
if(OO.content!=''){LIGHTBOX.find('.evolb_content').html(OO.content)}
if(OO.ajax=='yes'&&OO.d!=''){var D={};D=OO.d;LB.evo_admin_get_ajax({ajaxdata:D,ajax_action:OO.ajax_action,ajax_type:OO.ajax_type,lightbox_key:OO.lbc,uid:(OO.uid!='')?OO.uid:OO.d.uid,end:OO.end,lightbox_loader:OO.lightbox_loader,load_new_content:OO.load_new_content,})}
if(OO.ajax_url!=''){$.ajax({beforeSend:function(){},url:OO.ajax_url,success:function(data){LIGHTBOX.find('.evolb_content').html(data)},complete:function(){}})}
$('body').trigger('evo_lightbox_processed',[OO,LIGHTBOX])}
$.fn.evo_lightbox_close=function(opt){var LB=this;var defaults={'delay':500,'remove_from_dom':!0,};if(!(LB.hasClass('show')))return;var OO=$.extend({},defaults,opt);var hide_delay=parseInt(OO.delay);complete_close=(LB.parent().find('.evo_lightbox.show').length==1)?true:!1;if(hide_delay>500){setTimeout(function(){LB.removeClass('show')},(hide_delay-500))}else{LB.removeClass('show')}
setTimeout(function(){if(complete_close){$('body').removeClass('evo_overflow');$('html').removeClass('evo_overflow')}
if(OO.remove_from_dom)LB.remove()},hide_delay)}
$.fn.evo_lightbox_populate_content=function(opt){LB=this;var defaults={'content':'',};var OO=$.extend({},defaults,opt);LB.find('.evolb_content').html(OO.content)}
$.fn.evo_lightbox_start_inloading=function(opt){LB=this;LB.find('.evolb_content').addClass('loading')}
$.fn.evo_lightbox_stop_inloading=function(opt){LB=this;LB.find('.evolb_content').removeClass('loading')}
$.fn.evo_lightbox_show_msg=function(opt){LB=this;var defaults={'type':'good','message':'','hide_message':!1,'hide_lightbox':!1,};var OO=$.extend({},defaults,opt);LB.find('.message').removeClass('bad good').addClass(OO.type).html(OO.message).fadeIn();if(OO.hide_message)setTimeout(function(){LB.evo_lightbox_hide_msg()},OO.hide_message);if(OO.hide_lightbox)LB.evo_lightbox_close({delay:OO.hide_lightbox})}
$.fn.evo_lightbox_hide_msg=function(opt){LB=this;LB.find('p.message').hide()}
$.fn.evo_lightbox_show_open_animation=function(opt){LB=this;var defaults={'animation_type':'initial','preload_temp_key':'init','end':'admin',};var OO=$.extend({},defaults,opt);if(OO.animation_type=='initial'){passed_data=OO.end=='admin'?evo_admin_ajax_handle:evo_general_params;html=passed_data.html.preload_general;if(OO.preload_temp_key!='init')html=passed_data.html[OO.preload_temp_key];LB.find('.evolb_content').html(html)}
if(OO.animation_type=='saving')
LB.find('.evolb_content').addClass('loading')}
$.fn.evo_cal_lightbox_trigger=function(SC_data,obj,CAL){const cancel_class=(obj.hasClass('cancel_event'))?' cancel_event':'';var extra_classes=' '+SC_data.calendar_type;var other_data={extra_classes:'evo_lightbox_body eventon_list_event evo_pop_body evcal_eventcard event_'+SC_data.event_id+'_'+SC_data.repeat_interval+cancel_class+extra_classes,CAL:CAL,obj:obj,et_data:obj.find('.evoet_data').data(),SC:SC_data};maximum=99;minimum=10;var randomnumber=Math.floor(Math.random()*(maximum-minimum+1))+minimum;lbac='';if(evo_general_params.cal.lbs=='sc1')lbac='within';if(evo_general_params.cal.lbs=='sc2')lbac='within ecSCR';if(SC_data.ux_val=='3a'){var new_content='';new_content+='<div class="evo_cardlb" style="padding:10px 10px 0 10px">';new_content+='<div style="margin-bottom:20px; width:100%; height:200px" class="evo_preloading"></div>';const box='<div style="display:flex;justify-content: space-between;margin-bottom:10px"><div style="width:40px;height:40px; margin-right:20px" class="evo_preloading"></div> <div style="flex:1 0 auto"> <div class="evo_preloading" style="width:70%; height:20px; margin-bottom:10px"></div><div class="evo_preloading" style="width:100%; height:80px; margin-bottom:10px"></div>  </div> </div>';new_content+=box+box+box;new_content+='</div>';var data_arg={};data_arg.event_id=SC_data.event_id;data_arg.ri=SC_data.repeat_interval;data_arg.SC=SC_data;data_arg.load_lbcontent=!0;data_arg.action='eventon_load_single_eventcard_content';data_arg.uid='load_single_eventcard_content_3a';if(CAL)data_arg.calid=CAL.attr('id');if(data_arg.SC.tile_style=='2')data_arg.SC.eventtop_style='0';data_arg.SC.tile_style='0';data_arg.SC.tile_bg='0';data_arg.SC.tiles='no';$('body').evo_lightbox_open({uid:'evo_open_eventcard_lightbox',lbc:'evo_eventcard_'+randomnumber,lbac:lbac,end:'client',content:new_content,ajax:'yes',ajax_type:'endpoint',ajax_action:'eventon_load_single_eventcard_content',d:data_arg,other_data:other_data})}else{var content=obj.closest('.eventon_list_event').find('.event_description').html();var _content=$(content).not('.evcal_close');clrW=obj.closest('.eventon_list_event').hasClass('clrW')?'clrW':'clrB';CAL.evo_lightbox_open({uid:'evo_open_eventcard_lightbox',lbc:'evo_eventcard_'+randomnumber,lbac:lbac,end:'client',content:'<div class="evopop_top '+clrW+'">'+obj.html()+'</div><div class="evopop_body">'+content+'</div>',other_data:other_data});return}}
$.fn.evo_cal_lb_listeners=function(){$('body').on('evo_lightbox_processed',function(event,OO,LIGHTBOX){if(OO.uid!='evo_open_eventcard_lightbox')return!1;var CAL=OO.other_data.CAL;LIGHTBOX.addClass('eventcard eventon_events_list');LIGHTBOX_content=LIGHTBOX.find('.evolb_content');LIGHTBOX_content.attr('class','evolb_content '+OO.other_data.extra_classes);var SC=OO.other_data.SC;var obj=OO.other_data.obj;const evoet_data=OO.other_data.et_data;bgcolor=bggrad='';if(evoet_data){bgcolor=evoet_data.bgc;bggrad=evoet_data.bggrad}
var show_lightbox_color=(SC.eventtop_style=='0'||SC.eventtop_style=='4')?false:!0;if((CAL&&CAL.hasClass('color')&&show_lightbox_color)||(!CAL&&show_lightbox_color)){LIGHTBOX_content.addClass('color');LIGHTBOX_content.find('.evopop_top').css({'background-color':bgcolor,'background-image':bggrad,})}else{LIGHTBOX_content.find('.evopop_top').css({'border-left':'3px solid '+bgcolor})}
if(obj.data('runjs')){$('body').trigger('evo_load_single_event_content',[SC.event_id,OO.other_data.obj])}
if(SC.evortl=='yes')LIGHTBOX.addClass('evortl');$('body').trigger('evolightbox_end',[LIGHTBOX,CAL,OO])}).on('evo_ajax_success_evo_open_eventcard_lightbox',function(event,OO,data){if(OO.ajaxdata.uid!="load_single_eventcard_content_3a")return!1;LIGHTBOX=$('.evo_lightbox.'+OO.lightbox_key);LIGHTBOX_content=LIGHTBOX.find('.evolb_content');CAL=$('body').find('#'+OO.ajaxdata.calid);$('body').trigger('evolightbox_end',[LIGHTBOX,CAL,OO])})}
$.fn.evo_get_ajax_url=function(opt){var defaults={a:'',e:'client',type:'ajax'};var OO=$.extend({},defaults,opt);if(OO.type=='endpoint'){var evo_ajax_url=(OO.e=='client')?evo_general_params.evo_ajax_url:evo_admin_ajax_handle.evo_ajax_url;return evo_ajax_url.toString().replace('%%endpoint%%',OO.a)}else if(OO.type=='rest'){var evo_ajax_url=(OO.e=='client')?evo_general_params.rest_url:evo_admin_ajax_handle.rest_url;return evo_ajax_url.toString().replace('%%endpoint%%',OO.a)}else{action_add=OO.a!=''?'?action='+OO.a:'';return(OO.e=='client')?evo_general_params.ajaxurl+action_add:evo_admin_ajax_handle.ajaxurl+action_add}}
$.fn.evo_countdown_get=function(opt){var defaults={gap:'',endutc:''};var OPT=$.extend({},defaults,opt);var gap=OPT.gap;if(gap==''){var Mnow=moment().utc();var M=moment();M.set('millisecond',OPT.endutc);gap=OPT.endutc-Mnow.unix()}
if(gap<0){return{'d':0,'h':0,'m':0,'s':0}}
distance=(gap*1000);var days=Math.floor(distance/(1000*60*60*24));var hours=Math.floor((distance%(1000*60*60*24))/(1000*60*60));var minutes=Math.floor((distance%(1000*60*60))/(1000*60));var seconds=Math.floor((distance%(1000*60))/1000);minutes=minutes<10?'0'+minutes:minutes;seconds=seconds<10?'0'+seconds:seconds;return{'d':days,'h':hours,'m':minutes,'s':seconds}};$.fn.evo_countdown=function(opt){var defaults={S1:''};var OPT=$.extend({},defaults,opt);var el=$(this);const day_text=(el.data('d')!==undefined&&el.data('d')!='')?el.data('d'):'Day';const days_text=(el.data('ds')!==undefined&&el.data('ds')!='')?el.data('ds'):'Days';var duration=el.data('dur');var endutc=parseInt(el.data('endutc'));var text=el.data('t');if(text===undefined)text='';if(el.hasClass('evo_cd_on'))return;var Mnow=moment().utc();var M=moment();M.set('millisecond',OPT.endutc);gap=endutc-Mnow.unix();if(gap>0){dd=el.evo_countdown_get({'gap':gap});el.html((dd.d>0?dd.d+' '+(dd.d>1?days_text:day_text)+" ":'')+dd.h+":"+dd.m+':'+dd.s+'  '+text);el.data('gap',(gap-1));el.addClass('evo_cd_on');var CD=setInterval(function(){gap=el.data('gap');duration=el.data('dur');const bar_elm=el.closest('.evo_event_progress').find('.evo_ep_bar');if(gap>0){if(duration!==undefined&&bar_elm.length){perc=((duration-gap)/duration)*100;bar_elm.find('b').css('width',perc+'%')}
dd=el.evo_countdown_get({'gap':gap});el.html((dd.d>0?dd.d+' '+(dd.d>1?days_text:day_text)+" ":'')+dd.h+":"+dd.m+':'+dd.s+' '+text);el.data('gap',(gap-1))}else{const expire_timer_action=el.data('exp_act');if(expire_timer_action!==undefined){$('body').trigger('runajax_refresh_now_cal',[el,el.data('n'),])}
const _complete_text=el.evo_get_txt({V:'event_completed'});if(bar_elm.length){bar_elm.addClass('evo_completed')}
if(el.closest('.evcal_desc').length){el.closest('.evcal_desc').find('.eventover').html(_complete_text);el.closest('.evcal_desc').find('.evo_live_now').remove()}
if(el.closest('.eventon_list_event').length){el.closest('.eventon_list_event').find('span.evo_live_now').hide()}
el.html('');clearInterval(CD)}},1000)}else{el.closest('.evo_event_progress').find('.evo_ep_bar').hide();clearInterval(CD)}};$.fn.evo_HB_process_template=function(opt){var defaults={TD:'',part:''}
var OPT=$.extend({},defaults,opt);BUS=$('#evo_global_data').data('d');template=Handlebars.compile(BUS.temp[OPT.part]);return template(OPT.TD)}
$.fn.evo_cal_events_in_range=function(opt){var defaults={S:'',E:'',hide:!0,closeEC:!0,showEV:!1,showEVL:!1,showAllEvs:!1};var OPT=$.extend({},defaults,opt);var CAL=$(this);var eJSON=CAL.find('.evo_cal_events').data('events');var SC=CAL.evo_shortcode_data();R={};html='';json={};show=0;if(eJSON&&eJSON.length>0){$.each(eJSON,function(ind,ED){eO=CAL.find('#event_'+ED._ID);if(eO===undefined||eO.length==0)return;if(OPT.hide)eO.hide();this_show=!1;if(ED.month_long||ED.year_long){this_show=!0}else{if(CAL.evo_is_in_range({'S':OPT.S,'E':OPT.E,'start':ED.unix_start,'end':ED.unix_end})){this_show=!0}}
if(OPT.showAllEvs)this_show=!0;if(this_show){if(OPT.showEV)eO.show();if(OPT.closeEC&&SC.evc_open=='no')eO.find('.event_description').hide().removeClass('open');html+=eO[0].outerHTML;json[ED._ID]=ED;show++}})}else{var cal_events=CAL.find('.eventon_list_event');cal_events.each(function(index,elm){var ED=$(elm).evo_cal_get_basic_eventdata();if(!ED)return;if(OPT.hide)$(elm).hide();this_show=!1;if($(elm).hasClass('month_long')||$(elm).hasClass('year_long')){this_show=!0}else{if(CAL.evo_is_in_range({'S':OPT.S,'E':OPT.E,'start':ED.unix_start,'end':ED.unix_end})){this_show=!0}}
if(OPT.showAllEvs)this_show=!0;if(this_show){if(OPT.showEV)$(elm).show();if(OPT.closeEC&&SC.evc_open=='no')
$(elm).find('.event_description').hide().removeClass('open');html+=$(elm)[0].outerHTML;json[ED.uID]=ED;show++}})}
if(OPT.showEV){no_event_content=CAL.evo_get_global({S1:'html',S2:'no_events'});tx_noevents=CAL.evo_get_txt({V:'no_events'});EL=CAL.find('.eventon_events_list');EL.find('.eventon_list_event.no_events').remove();if(show==0)
EL.append('<div class="eventon_list_event no_events">'+no_event_content+'</div>')}
if(OPT.showEVL){CAL.find('.eventon_events_list').show().removeClass('evo_hide')}
R.count=show;R.html=html;R.json=json;return R}
$.fn.evo_is_in_range=function(opt){var defaults={S:'',E:'',start:'',end:''}
var OPT=$.extend({},defaults,opt);S=parseInt(OPT.S);E=parseInt(OPT.E);start=parseInt(OPT.start);end=parseInt(OPT.end);return((start<=S&&end>=E)||(start<=S&&end>=S&&end<=E)||(start<=E&&end>=E)||(start>=S&&end<=E))?true:!1}
$.fn.evo_cal_hide_events=function(){CAL=$(this);CAL.find('.eventon_list_event').hide()}
$.fn.evo_cal_get_basic_eventdata=function(){var ELM=$(this);var _time=ELM.data('time');if(_time===undefined)return!1;const time=_time.split('-');const ri=ELM.data('ri').replace('r','');const eID=ELM.data('event_id');var _event_title=ELM.find('.evcal_event_title').text();_event_title=_event_title.replace(/'/g,'&apos;');var RR={'uID':eID+'_'+ri,'ID':eID,'event_id':eID,'ri':ri,'event_start_unix':parseInt(time[0]),'event_end_unix':parseInt(time[1]),'ux_val':ELM.find('.evcal_list_a').data('ux_val'),'event_title':_event_title,'hex_color':ELM.data('colr'),'hide_et':ELM.hasClass('no_et')?'y':'n','evcal_event_color':ELM.data('colr'),'unix_start':parseInt(time[0]),'unix_end':parseInt(time[1]),};RR.ett1={};ELM.find('.evoet_eventtypes.ett1 .evoetet_val').each(function(){RR.ett1[$(this).data('id')]=$(this).data('v')});const eventtop_data=ELM.find('.evoet_data').data('d');if('loc.n' in eventtop_data&&eventtop_data['loc.n']!=''){RR.location=eventtop_data['loc.n']}
if('orgs' in eventtop_data&&eventtop_data.orgs!==undefined){var org_names='';$.each(eventtop_data.orgs,function(index,value){org_names+=value+' '});RR.organizer=org_names}
if('tags' in eventtop_data&&eventtop_data.tags!==undefined){RR.event_tags=eventtop_data.tags}
return RR}
$.fn.evo_day_in_month=function(opt){var defaults={M:'',Y:''}
var OPT=$.extend({},defaults,opt);return new Date(OPT.Y,OPT.M,0).getDate()}
$.fn.evo_get_day_name_index=function(opt){var defaults={M:'',Y:'',D:''}
var OPT=$.extend({},defaults,opt);return new Date(Date.UTC(OPT.Y,OPT.M-1,OPT.D)).getUTCDay()}
$.fn.evo_prepare_lb=function(){$(this).find('.evo_lightbox_body').html('')}
$.fn.evo_show_lb=function(opt){var defaults={RTL:'',calid:''}
var OPT=$.extend({},defaults,opt);$(this).addClass('show '+OPT.RTL).attr('data-cal_id',OPT.calid);$('body').trigger('evolightbox_show')}
$.fn.evo_append_lb=function(opt){var defaults={C:'',CAL:''}
var OPT=$.extend({},defaults,opt);$(this).find('.evo_lightbox_body').html(OPT.C);if(OPT.CAL!=''&&OPT.CAL!==undefined&&OPT.CAL.hasClass('color')){const LIST=$(this).find('.eventon_events_list');if(LIST.length>0){LIST.find('.eventon_list_event').addClass('color')}}}
$.fn.eventon_check_img_size_on_lb=function(){LB=this;winH=parseInt($(window).height());winW=parseInt($(window).width());var pad=50;if(winW<650)pad=20;if(winW<500)pad=10;winH-=pad*2;winW-=pad*2;LB=$('body').find('.evolb_ft_img');WINratio=winH/winW;const IMG=LB.find('img');IMGratio=parseInt(IMG.data('h'))/parseInt(IMG.data('w'));img_relative_w=parseInt(winH/IMGratio);if(WINratio<1){if(IMGratio<1){if(img_relative_w>winW){IMG.css({'width':winW,'height':'auto'})}else{newIH=winH>parseInt(IMG.data('h'))?parseInt(IMG.data('h')):winH;newIW=(winH>parseInt(IMG.attr('h')))?'100%':img_relative_w;IMG.css({'width':newIW,'height':newIH})}}else{IMG.css({'width':'auto','height':winH})}}else{if(IMGratio<1){IMG.css({'width':winW,'height':'auto'})}else{if(img_relative_w>winW){IMG.css({'width':winW,'height':'auto'})}else{IMG.css({'width':'auto','height':winH})}}}}
$.fn.eventon_process_main_ft_img=function(OO){IMG=this;img_sty='def';if(IMG.hasClass('fit'))img_sty='fit';if(IMG.hasClass('full'))img_sty='full';box_width=IMG.width();box_height=IMG.height();img_height=parseInt(IMG.data('h'));img_width=parseInt(IMG.data('w'));img_ratio=IR=img_height/img_width;if(IMG.hasClass('fit')){new_width=box_height/img_ratio;new_height=box_height;if(new_width>box_width){if(IR<1){new_width=box_width;new_height=IR*new_width}else{new_height=box_height;new_width=new_height/IR}}
IMG.find('span').css({'width':new_width,'height':new_height})}
if(IMG.hasClass('full')){new_height=img_ratio*box_width;new_width=box_width;IMG.find('span').css({'width':new_width,'height':new_height});IMG.css({'height':new_height})}}
$.fn.evo_shortcode_data=function(){var ev_cal=$(this);return ev_cal.find('.evo_cal_data').data('sc')}
$.fn.evo_get_filter_data=function(){return $(this).find('.evo_cal_data').data('filter_data')}
$.fn.evo_cal_get_filter_sub_data=function(tax,key){newdata=$(this).evo_get_filter_data();return newdata[tax][key]}
$.fn.evo_cal_update_filter_data=function(tax,new_val,key){newdata=$(this).evo_get_filter_data();if(key===undefined)key='nterms';newdata[tax][key]=new_val;$(this).find('.evo_cal_data').data('filter_data',newdata)}
$.fn.evo_cal_get_footer_data=function(key){data=$(this).find('.evo_cal_data').data();if(data===undefined)return!1;if(key in data)return data[key];return!1}
$.fn.evo_cal_hide_data=function(){$(this).find('.evo_cal_data').attr({'data-sc':'','data-filter_data':'','data-nav_data':'',})}
$.fn.evo_update_sc_from_filters=function(){var el=$(this);SC=el.evo_shortcode_data();$.each(el.evo_get_filter_data(),function(index,value){var default_val=value.terms;var filter_val=value.nterms;const NOT_values=value.__notvals;filter_val=filter_val==''?'NOT-all':filter_val;var not_string='';if(NOT_values!==undefined&&NOT_values.length>0&&filter_val!=default_val){$.each(NOT_values,function(index,value){not_string+='NOT-'+value+','})}
SC[index]=not_string+filter_val});el.find('.evo_cal_data').data('sc',SC)}
$.fn.evo_get_sc_val=function(opt){var defaults={F:''}
var OPT=$.extend({},defaults,opt);var ev_cal=$(this);if(OPT.F=='')return!1;SC=ev_cal.find('.evo_cal_data').data('sc');if(!(SC[OPT.F]))return!1;return SC[OPT.F]}
$.fn.evo_update_cal_sc=function(opt){var defaults={F:'',V:''}
var OPT=$.extend({},defaults,opt);var ev_cal=$(this);SC=ev_cal.find('.evo_cal_data').data('sc');SC[OPT.F]=OPT.V;ev_cal.find('.evo_cal_data').data('sc',SC)}
$.fn.evo_update_all_cal_sc=function(opt){var defaults={SC:''}
var OPT=$.extend({},defaults,opt);var CAL=$(this);CAL.find('.evo_cal_data').data('sc',OPT.SC)}
$.fn.evo_is_hex_dark=function(opt){var defaults={hex:'808080'}
var OPT=$.extend({},defaults,opt);hex=OPT.hex;var c=hex.replace('#','');var is_hex=typeof c==='string'&&c.length===6&&!isNaN(Number('0x'+c));if(is_hex){var values=c.split('');r=parseInt(values[0].toString()+values[1].toString(),16);g=parseInt(values[2].toString()+values[3].toString(),16);b=parseInt(values[4].toString()+values[5].toString(),16)}else{var vals=c.substring(c.indexOf('(')+1,c.length-1).split(', ');var r=vals[0]
var g=vals[1];var b=vals[2]}
var luma=((r*299)+(g*587)+(b*114))/1000;return luma>155?true:!1}
$.fn.evo_rgb_process=function(opt){var defaults={data:'808080',type:'rgb',method:'rgb_to_val'}
var opt=$.extend({},defaults,opt);const color=opt.data;if(opt.method=='rgb_to_hex'){if(color=='1'){return}else{if(color!==''&&color){rgb=color.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);return"#"+("0"+parseInt(rgb[1],10).toString(16)).slice(-2)+("0"+parseInt(rgb[2],10).toString(16)).slice(-2)+("0"+parseInt(rgb[3],10).toString(16)).slice(-2)}}}
if(opt.method=='rgb_to_val'){if(opt.type=='hex'){var rgba=/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(color);var rgb=new Array();rgb.r=parseInt(rgba[1],16);rgb.g=parseInt(rgba[2],16);rgb.b=parseInt(rgba[3],16)}else{rgb=color}
return parseInt((rgb.r+rgb.g+rgb.b)/3)}}
$.fn.evo_get_OD=function(){var ev_cal=$(this);return ev_cal.find('.evo_cal_data').data('od')}
$.fn.evo_getevodata=function(){var ev_cal=$(this);var evoData={};ev_cal.find('.evo-data').each(function(){$.each(this.attributes,function(i,attrib){var name=attrib.name;if(attrib.name!='class'&&attrib.name!='style'){name__=attrib.name.split('-');evoData[name__[1]]=attrib.value}})});return evoData}
$.fn.evo_is_mobile=function(){return(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))?true:!1}
$.fn.evo_loader_animation=function(opt){var defaults={direction:'start'}
var OPT=$.extend({},defaults,opt);if(OPT.direction=='start'){$(this).find('#eventon_loadbar').slideDown()}else{$(this).find('#eventon_loadbar').slideUp()}}
$.fn.evo_item_shortcodes=function(){var OBJ=$(this);var shortcode_array={};OBJ.each(function(){$.each(this.attributes,function(i,attrib){var name=attrib.name;if(attrib.name!='class'&&attrib.name!='style'&&attrib.value!=''){name__=attrib.name.split('-');shortcode_array[name__[1]]=attrib.value}})});return shortcode_array}
$.fn.evo_shortcodes=function(){var ev_cal=$(this);var shortcode_array={};ev_cal.find('.cal_arguments').each(function(){$.each(this.attributes,function(i,attrib){var name=attrib.name;if(attrib.name!='class'&&attrib.name!='style'&&attrib.value!=''){name__=attrib.name.split('-');shortcode_array[name__[1]]=attrib.value}})});return shortcode_array}}(jQuery))
;