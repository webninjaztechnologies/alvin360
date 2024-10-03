(function(){this.IQUtils=function(params){}
this.IQUtils.saveCookieStorage=function(key,value){window.sessionStorage.setItem(key,value)}
this.IQUtils.getSessionStorage=function(key){return window.sessionStorage.getItem(key)}
this.IQUtils.removeSessionStorage=function(key){window.sessionStorage.removeItem(key)}
this.IQUtils.saveLocalStorage=function(key,value){window.localStorage.setItem(key,value)}
this.IQUtils.getLocalStorage=function(key){return window.localStorage.getItem(key)}
this.IQUtils.removeLocalStorage=function(key){window.localStorage.removeItem(key)}
this.IQUtils.getCookie=function(cname){var name=cname+"=";var ca=document.cookie.split(';');for(var i=0;i<ca.length;i++){var c=ca[i];while(c.charAt(0)==' '){c=c.substring(1)}
if(c.indexOf(name)==0){return c.substring(name.length,c.length)}}
return""}
this.IQUtils.setCookie=function(cname,cvalue,exdays=1){let date=new Date();date.setTime(date.getTime()+(exdays*24*60*60*1000));const expires="expires="+date.toUTCString();let domain="domain="+!IQUtils.isSubdomain()?"."+window.location.hostname:""+window.location.hostname+";";document.cookie=cname+"="+cvalue+"; "+expires+"; Path=/;"+domain}
this.IQUtils.isSubdomain=function(url=window.location.hostname){var regex=new RegExp(/^([a-z]+\:\/{2})?([\w-]+\.[\w-]+\.\w+)$/);return!!url.match(regex)}
this.IQUtils.removeCookie=function(cname){document.cookie=cname+'=; expires=Thu, 01 Jan 1970 00:00:01 GMT;'}
this.IQUtils.checkStorageKey=function(key){if(window.localStorage.getItem(key)===null){return!1}else{return!0}}
this.IQUtils.checkSessionStorageKey=function(key){if(window.sessionStorage.getItem(key)===null){return!1}else{return!0}}
this.IQUtils.checkCookieKey=function(key){if(this.getCookie(key)===""){return!1}else{return!0}}
this.IQUtils.checkValue=function(value){if(value===null||value===undefined||value===""){return!1}else{return!0}}
this.IQUtils.checkAllStorageKey=function(key){if(this.checkStorageKey(key)||this.checkSessionStorageKey(key)||this.checkCookieKey(key)){return!0}else{return!1}}
this.IQUtils.checkStorageArray=function(key,storages){let result=!0;let obj={}
for(var i=0;i<storages.length;i++){switch(storages[i]){case 'localStorage':if(this.checkStorageKey(key)){result=!1;obj.storage='localStorage';obj.key=this.getLocalStorage(key)}
break;case 'sessionStorage':if(this.checkSessionStorageKey(key)){result=!1;obj.storage='sessionStorage';obj.key=this.getSessionStorage(key)}
break;case 'cookieStorage':if(this.checkCookieKey(key)){result=!1;obj.cookie=!1}
break;default:break}}
obj.result=result;return obj}
this.IQUtils.getUrlParameter=function(name){name=name.replace(/[\[]/,'\\[').replace(/[\]]/,'\\]');const regex=new RegExp('[\\?&]'+name+'=([^&#]*)');const results=regex.exec(location.search);return results===null?'':decodeURIComponent(results[1].replace(/\+/g,' '))}
this.IQUtils.getQueryString=function(name){name=name.replace(/[\[]/,'\\[').replace(/[\]]/,'\\]');const regex=new RegExp('[\\?&]'+name+'=([^&#]*)');const results=regex.exec(location.search);return results===null?'':decodeURIComponent(results[1].replace(/\+/g,' '))}
this.IQUtils.getElem=function(selector,elem=document){return elem.querySelector(selector)}
this.IQUtils.getElems=function(selector,elem=document){return elem.querySelectorAll(selector)}
this.IQUtils.setContent=function(selector,content){let _newElem=selector
if(_.isString(_newElem)){_newElem=IQUtils.getElems(selector)}
if(typeof _newElem.length!==typeof undefined){_.forEach(_newElem,function(elem){const leftJoin=elem.getAttribute('data-leftJoin')!==null?elem.getAttribute('data-leftJoin'):'';const rightJoin=elem.getAttribute('data-rightJoin')!==null?elem.getAttribute('data-rightJoin'):'';elem.innerHTML=leftJoin+content+rightJoin})}}
this.IQUtils.addClass=function(elem,...className){let _newElem=elem
if(_.isString(_newElem)){_newElem=IQUtils.getElems(elem)}
if(_newElem.length!==undefined){_.forEach(_newElem,function(elem){_.forEach(className,function(className){elem.classList.add(className)})})}else{_.forEach(className,function(className){_newElem.classList.add(className)})}}
this.IQUtils.removeClass=function(elem,...className){let _newElem=elem
if(_.isString(_newElem)){_newElem=IQUtils.getElems(elem)}
if(_newElem.length!==undefined){_.forEach(_newElem,function(elem){_.forEach(className,function(className){elem.classList.remove(className)})})}else{_.forEach(className,function(className){_newElem.classList.remove(className)})}}
this.IQUtils.toggleClass=function(elem,className){elem.classList.toggle(className)}
this.IQUtils.hasClass=function(elem,className){return elem.classList.contains(className)}
this.IQUtils.getAttr=function(elem,attr){return elem.getAttribute(attr)}
this.IQUtils.setAttr=function(elems,object){let _newElem=elems
if(_.isString(_newElem)){_newElem=IQUtils.getElems(elems)}
_.forEach(_newElem,function(elem){elem.setAttribute(object.prop,object.value)})}
this.IQUtils.removeAttr=function(elem,attr){elem.removeAttribute(attr)}
this.IQUtils.setStyle=function(elems,object){for(var key in object){let _newElem=elems
if(_.isString(_newElem)){_newElem=IQUtils.getElems(elems);}
_.forEach(_newElem,function(elem){elem.style[key]=object[key]})}}
this.IQUtils.getPosition=function(elem){var xPosition=0;var yPosition=0;while(elem){xPosition+=(elem.offsetLeft-elem.scrollLeft+elem.clientLeft);yPosition+=(elem.offsetTop-elem.scrollTop+elem.clientTop);elem=elem.offsetParent}
return{x:xPosition,y:yPosition}}
this.IQUtils.getWidth=function(elem){return elem.offsetWidth}
this.IQUtils.getHeight=function(elem){return elem.offsetHeight}
this.IQUtils.createEvent=(eventName,eventData)=>{return new Event(eventName,eventData)}
this.IQUtils.mergeDeep=(target,...sources)=>{if(!sources.length)return target;const source=sources.shift();if(_.isObject(target)&&_.isObject(source)){for(const key in source){if(_.isObject(source[key])){if(!target[key])Object.assign(target,{[key]:{}});IQUtils.mergeDeep(target[key],source[key])}else{Object.assign(target,{[key]:source[key]})}}}
return IQUtils.mergeDeep(target,...sources)}
this.IQUtils.getRootVars=(property,elem=document.body)=>{let _newElem=elem
if(_.isString(_newElem)){_newElem=IQUtils.getElems(elems)}
return getComputedStyle(elem).getPropertyValue(property).trim()||null}
this.IQUtils.setRootVariables=(variables)=>{const _root=document.documentElement;const _variables=variables;_.forEach(_variables,function(value,key){_root.style.setProperty(key,value)})}
this.IQUtils.removeRootVariables=(variables)=>{const _root=document.documentElement;const _variables=variables;_.forEach(_variables,function(value,key){_root.style.removeProperty(key)})}
this.IQUtils.colorMix=(color_1,color_2,weight)=>{function d2h(d){return d.toString(16)}
function h2d(h){return parseInt(h,16)}
weight=(typeof(weight)!=='undefined')?weight:50;var color="#";for(var i=0;i<=5;i+=2){var v1=h2d(color_1.substr(i,2))
var v2=h2d(color_2.substr(i,2))
var val=d2h(Math.floor(v2+(v1-v2)*(weight/100.0)));while(val.length<2){val='0'+val}
color+=val}
return color}
this.IQUtils.tintColor=(color,weight)=>{weight=(typeof(weight)!=='undefined')?weight:50;var color=color;var color_1=color.substr(1);var color_2='FFFFFF';return IQUtils.colorMix(color_2,color_1,weight)}
this.IQUtils.shadeColor=(color,weight)=>{weight=(typeof(weight)!=='undefined')?weight:50;var color=color;var color_1=color.substr(1);var color_2='000000';return IQUtils.colorMix(color_2,color_1,weight)}
this.IQUtils.hexToRgb=(hex)=>{var result=/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);var result=/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);return `${parseInt(result[1], 16)}, ${parseInt(result[2], 16)}, ${parseInt(result[3], 16)}`}
this.IQUtils.getColorShadeTint=(color,value,dark)=>{let colors={}
if(dark){colors[`${color}-dark`]=IQUtils.shadeColor(value,40);colors[`${color}-light`]=IQUtils.shadeColor(value,70);colors[color]=value}else{colors[`${color}-dark`]=IQUtils.shadeColor(value,10);colors[`${color}-light`]=IQUtils.tintColor(value,90);colors[color]=value}
return colors}
this.IQUtils.debounce=(func,wait,immediate)=>{let timeout
return function(){const context=this,args=arguments
const later=function(){timeout=null
if(!immediate)func.apply(context,args)}
const callNow=immediate&&!timeout
clearTimeout(timeout)
timeout=setTimeout(later,wait)
if(callNow)func.apply(context,args)}}
this.IQUtils.getVariableColor=()=>{let prefix=getComputedStyle(document.body).getPropertyValue('--prefix')||'color-theme-';if(prefix){prefix=prefix.trim()}
const color1=getComputedStyle(document.body).getPropertyValue(`--${prefix}primary`);return{primary:color1.trim(),}}
return this.IQUtils})()
;