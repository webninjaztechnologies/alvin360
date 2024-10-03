window.mpp=window.mpp||{};(function(exports,$){var Uploader;if(typeof _mppUploadSettings==='undefined'){return}
Uploader=function(options){var self=this,elements={container:'container',browser:'browse_button',dropzone:'drop_element'},key,error;this.supports={upload:Uploader.browser.supported};this.supported=this.supports.upload;if(!this.supported){return}
this.plupload=$.extend(!0,{multipart_params:{}},Uploader.defaults);this.container=document.body;$.extend(!0,this,options);for(key in this){if($.isFunction(this[key])){this[key]=$.proxy(this[key],this)}}
for(key in elements){if(!this[key]){continue}
this[key]=$(this[key]).first();if(!this[key].length){delete this[key];continue}
if(!this[key].prop('id')){this[key].prop('id','__mpp-uploader-id-'+Uploader.uuid++)}
this.plupload[elements[key]]=this[key].prop('id')}
if(!(this.browser&&this.browser.length)&&!(this.dropzone&&this.dropzone.length)){return}
this.uploader=new plupload.Uploader(this.plupload);delete this.plupload;this.param(this.params||{});delete this.params;error=function(message,data,file){console.log("MPP Error: "+message);if(file.attachment){file.attachment.destroy()}
Uploader.errors.unshift({message:message||pluploadL10n.default_error,data:data,file:file});self.error(message,data,file);$(document).trigger('mpp:uploader:error',[self,message,data,file])};this.uploader.init();this.supports.dragdrop=this.uploader.features.dragdrop&&!Uploader.browser.mobile;(function(dropzone,supported){var timer,active;if(!dropzone){return}
dropzone.toggleClass('supports-drag-drop',!!supported);if(!supported){return dropzone.unbind('.mpp-uploader')}
dropzone.bind('dragover.mpp-uploader',function(){if(timer){clearTimeout(timer)}
if(active){return}
dropzone.trigger('dropzone:enter').addClass('drag-over');active=!0});dropzone.bind('dragleave.mpp-uploader, drop.mpp-uploader',function(){timer=setTimeout(function(){active=!1;dropzone.trigger('dropzone:leave').removeClass('drag-over')},0)})}(this.dropzone,this.supports.dragdrop));if(this.browser){this.browser.on('mouseenter',this.refresh)}else{this.uploader.disableBrowse(!0);$('#'+this.uploader.id+'_html5_container').hide()}
this.uploader.bind('FilesAdded',function(up,files){_.each(files,function(file){var attributes,image;if(plupload.FAILED===file.status){return}
var original_file=file;attributes=_.extend({file:file,uploading:!0,date:new Date(),filename:file.name,menuOrder:0,uploadedTo:wp.media.model.settings.post.id},_.pick(file,'loaded','size','percent'));image=/(?:jpe?g|png|gif)$/i.exec(file.name);if(image){attributes.type='image';attributes.subtype=('jpg'===image[0])?'jpeg':image[0]}
file.attachment=wp.media.model.Attachment.create(attributes);Uploader.queue.add(file.attachment);self.added(original_file);$(document).trigger('mpp:uploader:file:added',[self,file])});self.allFilesAdded(up);$(document).trigger('mpp:uploader:files:added',[self,up]);up.refresh();up.start()});this.uploader.bind('UploadProgress',function(up,file){file.attachment.set(_.pick(file,'loaded','percent'));self.progress(file.attachment);$(document).trigger('mpp:uploader:upload:progress',[self,file.attachment])});this.uploader.bind('FileUploaded',function(up,file,response){var complete;try{response=JSON.parse(response.response)}catch(e){return error(pluploadL10n.default_error,e,file)}
if(!_.isObject(response)){return error(pluploadL10n.default_error,null,file)}else if(_.isUndefined(response.success)||!response.success){return error(response.data.message,response.data.message,file)}
_.each(['loaded','size','percent'],function(key){file.attachment.unset(key)});file.attachment.set(_.extend(response.data,{uploading:!1}));complete=Uploader.queue.all(function(attachment){return!attachment.get('uploading')});if(complete){Uploader.queue.reset()}
self.success(file.attachment);$(document).trigger('mpp:uploader:upload:success',[self,file.attachment])});this.uploader.bind('UploadComplete',function(up,files){self.complete(up,files);$(document).trigger('mpp:uploader:upload:complete',[self,up,files])});this.uploader.bind('BeforeUpload',function(up,file){if(self.isRestricted(up,file)){up.stop();return}
$(document).trigger('mpp:uploader:before:upload',[self,up,file])});this.uploader.bind('Error',function(up,pluploadError){var message=pluploadL10n.default_error,key;for(key in Uploader.errorMap){if(pluploadError.code===plupload[key]){message=Uploader.errorMap[key];if(_.isFunction(message)){message=message(pluploadError.file,pluploadError)}
break}}
error(message,pluploadError,pluploadError.file);up.refresh()});this.init()};$.extend(Uploader,_mppUploadSettings);Uploader.uuid=0;Uploader.errorMap={'FAILED':pluploadL10n.upload_failed,'FILE_EXTENSION_ERROR':pluploadL10n.invalid_filetype,'IMAGE_FORMAT_ERROR':pluploadL10n.not_an_image,'IMAGE_MEMORY_ERROR':pluploadL10n.image_memory_exceeded,'IMAGE_DIMENSIONS_ERROR':pluploadL10n.image_dimensions_exceeded,'GENERIC_ERROR':pluploadL10n.upload_failed,'IO_ERROR':pluploadL10n.io_error,'HTTP_ERROR':pluploadL10n.http_error,'SECURITY_ERROR':pluploadL10n.security_error,'FILE_SIZE_ERROR':function(file){return pluploadL10n.file_exceeds_size_limit.replace('%s',file.name)}};$.extend(Uploader.prototype,{feedback:'#mpp-upload-feedback-activity',media_list:'#mpp-uploaded-media-list-activity',uploading_media_list:_.template("<li id='<%= id %>'><span class='mpp-attached-file-name'><%= name %></span>(<span class='mpp-attached-file-size'><%= size %></spa>)<span class='mpp-remove-file-attachment'>x</span> <b></b></li>"),uploaded_media_list:_.template("<li class='mpp-uploaded-media-item' id='mpp-uploaded-media-item-<%= id %>' data-media-id='<%= id %>'><img src='<%= url %>' /><a href='#' class='mpp-delete-uploaded-media-item'>x</a></li>"),param:function(key,value){if(arguments.length===1&&typeof key==='string'){return this.uploader.settings.multipart_params[key]}
if(arguments.length>1){this.uploader.settings.multipart_params[key]=value}else{$.extend(this.uploader.settings.multipart_params,key)}},success:function(file){var sizes=file.get('sizes');var original_url=file.get('url');var id=file.get('id');var file_obj=file.get('file');var thumbnail='';if(sizes!==undefined){thumbnail=sizes.thumbnail}else if(file.get('thumb')){thumbnail=file.get('thumb')}
var html='';html=this.uploaded_media_list({id:id,url:thumbnail.url});$(this.feedback).find('li#'+file_obj.id).remove();if(this.media_list){$('ul',this.media_list).append(html)}},error:function(reason,data,file){if(data&&data.code=='-601'&&mpp.notify!=undefined&&_mppData.current_type){mpp.notify(_mppData.type_errors[_mppData.current_type],'error');return}
if(this.feedback&&jq('ul li#'+file.id,this.feedback).get(0)){jq('ul li#'+file.id,this.feedback).addClass('mpp-upload-fail').find('b').html('<span>'+reason+"</span>")}else{mpp.notify(reason,'error')}},added:function(file){var html='';html=this.uploading_media_list({id:file.id,name:file.name,size:plupload.formatSize(file.size)});if(this.feedback){$('ul',this.feedback).append(html)}
if(this.onAddFile){this.onAddFile(file)}},allFilesAdded:function(up){},progress:function(file){if(!filename,this.feedback){return}
var filename,percent;filename=file.get('file').id;percent=file.get('percent');$('ul li#'+filename,this.feedback).find('b').html('<span>'+percent+"%</span>")},complete:function(){if(!this.media_list){return}
jq('.mpp-loader',this.media_list).hide()},removeFileFeedback:function(file){if(file.id===undefined){return}
if(this.feedback){jQuery(this.feedback).find('ul li#'+file.id).remove()}},clear_media_list:function(){jq('ul',this.media_list).empty();jq('ul',this.media_list).append(jq('#mpp-loader-wrapper').clone())},clear_feedback:function(){jq('ul',this.feedback).empty()},hide_dropzone:function(){jq(this.dropzone).hide()},hide_ui:function(){this.clear_media_list();this.clear_feedback();this.hide_dropzone()},onAddFile:function(file){if(!this.media_list){return}
jq('.mpp-loader',this.media_list).show()},init:function(){if(!this.feedback){return}
this.clear_media_list()},refresh:function(){var node,attached,container,id;if(this.browser){node=this.browser[0];while(node){if(node===document.body){attached=!0;break}
node=node.parentNode}
if(!attached){id='mpp-uploader-browser-'+this.uploader.id;container=$('#'+id);if(!container.length){container=$('<div class="mpp-uploader-browser" />').css({position:'fixed',top:'-1000px',left:'-1000px',height:0,width:0}).attr('id','mpp-uploader-browser-'+this.uploader.id).appendTo('body')}
container.append(this.browser)}}
this.uploader.refresh()},isRestricted:function(up,file){return!1}});Uploader.queue=new wp.media.model.Attachments([],{query:!1});Uploader.errors=new Backbone.Collection();exports.Uploader=Uploader})(mpp,jQuery);var jq=jQuery;if(jq.cookie===undefined){jQuery.cookie=function(name,value,options){if(typeof value!=='undefined'){options=options||{};if(value===null){value='';options.expires=-1}
var expires='';if(options.expires&&(typeof options.expires==='number'||options.expires.toUTCString)){var date;if(typeof options.expires==='number'){date=new Date();date.setTime(date.getTime()+(options.expires*24*60*60*1000))}else{date=options.expires}
expires='; expires='+date.toUTCString()}
var path=options.path?'; path='+(options.path):'';var domain=options.domain?'; domain='+(options.domain):'';var secure=options.secure?'; secure':'';document.cookie=[name,'=',encodeURIComponent(value),expires,path,domain,secure].join('')}else{var cookieValue=null;if(document.cookie&&document.cookie!=''){var cookies=document.cookie.split(';');for(var i=0;i<cookies.length;i++){var cookie=jQuery.trim(cookies[i]);if(cookie.substring(0,name.length+1)==(name+'=')){cookieValue=decodeURIComponent(cookie.substring(name.length+1));break}}}
return cookieValue}}}
function mpp_setup_uploader_file_types(mpp_uploader,type){if(!_mppData||!_mppData.types){return}
if(type===undefined&&_mppData.current_type!==undefined){type=_mppData.current_type}
if(type===undefined||!type){return}
var settings;try{settings=mpp_uploader.uploader.getOption('filters')}catch(e){settings={};console.log(e)}
settings.mime_types=[_mppData.types[type]];mpp_uploader.uploader.setOption('filters',settings);if(mpp_uploader.dropzone){jQuery(mpp_uploader.dropzone).find('.mpp-uploader-allowed-file-type-info').html(_mppData.allowed_type_messages[type]);jQuery(mpp_uploader.dropzone).find('.mpp-uploader-allowed-max-file-size-info').html(_mppData.max_allowed_file_size)}}
function mpp_get_attached_media(){return jQuery('body').data('mpp-attached-media')}
function mpp_add_attached_media(media_id){var $body=jQuery('body');var attached_media=$body.data('mpp-attached-media');if(!attached_media){attached_media=[]}else{attached_media=attached_media.split(',')}
attached_media.push(media_id);attached_media=attached_media.join(',');$body.data('mpp-attached-media',attached_media)}
function mpp_remove_attached_media(media_id){var $body=jQuery('body');var attached_media=$body.data('mpp-attached-media');if(!attached_media){return!1}else{attached_media=attached_media.split(',');attached_media=_.without(attached_media,''+media_id);attached_media=attached_media.join(',')}
$body.data('mpp-attached-media',attached_media)}
function mpp_reset_attached_media(){jQuery('body').data('mpp-attached-media','')}
;