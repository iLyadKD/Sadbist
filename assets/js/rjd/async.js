define(function(){var DEFAULT_PARAM_NAME='callback',_uid=0;function injectScript(src){var s,t;s=document.createElement('script');s.type='text/javascript';s.async=true;s.src=src;t=document.getElementsByTagName('script')[0];t.parentNode.insertBefore(s,t);}
function formatUrl(name,id){var paramRegex=/!(.+)/,url=name.replace(paramRegex,''),param=(paramRegex.test(name))?name.replace(/.+!/,''):DEFAULT_PARAM_NAME;url+=(url.indexOf('?')<0)?'?':'&';return url+param+'='+id;}
function uid(){_uid+=1;return'__async_req_'+_uid+'__';}
return{load:function(name,req,onLoad,config){if(config.isBuild){onLoad(null);}else{var id=uid();window[id]=onLoad;injectScript(formatUrl(req.toUrl(name),id));}}};});