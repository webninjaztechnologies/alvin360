"use strict";function woof_init_checkboxes(){if(icheck_skin!='none'){jQuery('.woof_checkbox_term').iCheck('destroy');let icheck_selector='.woof_checkbox_term';let skin=jQuery(icheck_selector).parents('.woof_redraw_zone').eq(0).data('icheck-skin');if(skin){skin=skin.split('_');jQuery(icheck_selector).iCheck({checkboxClass:'icheckbox_'+skin[0]+'-'+skin[1]})}else{jQuery(icheck_selector).iCheck({checkboxClass:'icheckbox_'+icheck_skin.skin+'-'+icheck_skin.color})}
jQuery('.woof_checkbox_term').off('ifChecked');jQuery('.woof_checkbox_term').on('ifChecked',function(event){jQuery(this).attr("checked",!0);woof_checkbox_process_data(this,!0)});jQuery('.woof_checkbox_term').off('ifUnchecked');jQuery('.woof_checkbox_term').on('ifUnchecked',function(event){jQuery(this).attr("checked",!1);woof_checkbox_process_data(this,!1)});jQuery('.woof_checkbox_label').off();jQuery('label.woof_checkbox_label').on('click',function(){if(jQuery(this).prev().find('.woof_checkbox_term').is(':disabled')){return!1}
if(typeof jQuery(this).prev().find('.woof_checkbox_term').attr('checked')!='undefined'){jQuery(this).prev().find('.woof_checkbox_term').trigger('ifUnchecked');jQuery(this).prev().removeClass('checked')}else{jQuery(this).prev().find('.woof_checkbox_term').trigger('ifChecked');jQuery(this).prev().addClass('checked')}})}else{jQuery('.woof_checkbox_term').on('change',function(event){if(jQuery(this).is(':checked')){jQuery(this).attr("checked",!0);woof_checkbox_process_data(this,!0)}else{jQuery(this).attr("checked",!1);woof_checkbox_process_data(this,!1)}})}}
function woof_checkbox_process_data(_this,is_checked){var tax=jQuery(_this).data('tax');var name=jQuery(_this).attr('name');var term_id=jQuery(_this).data('term-id');woof_checkbox_direct_search(term_id,name,tax,is_checked)}
function woof_checkbox_direct_search(term_id,name,tax,is_checked){var values='';var checked=!0;if(is_checked){if(tax in woof_current_values){woof_current_values[tax]=woof_current_values[tax]+','+name}else{woof_current_values[tax]=name}
checked=!0}else{values=woof_current_values[tax];values=values.split(',');var tmp=[];jQuery.each(values,function(index,value){if(value!=name){tmp.push(value)}});values=tmp;if(values.length){woof_current_values[tax]=values.join(',')}else{delete woof_current_values[tax]}
checked=!1}
jQuery('.woof_checkbox_term_'+term_id).attr('checked',checked);woof_ajax_page_num=1;if(woof_autosubmit){woof_submit_link(woof_get_submit_link())}}
;