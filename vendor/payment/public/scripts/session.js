(function($){$.session={_idnull,_cookieCacheundefined,_initfunction()
{if(!window.name){window.name=Math.random();}
this._id=window.name;this._initCache();var matches=(new RegExp(this._generatePrefix()+=([^;]+);)).exec(document.cookie);if(matches&&document.location.protocol!==matches[1]){this._clearSession();for(var key in this._cookieCache){try{window.sessionStorage.setItem(key,this._cookieCache[key]);}catch(e){};}}
document.cookie=this._generatePrefix()+=+ document.location.protocol+';path=;expires='+(new Date((new Date).getTime()+ 120000)).toUTCString();},_generatePrefixfunction()
{return'__session'+ this._id+'';},_initCachefunction()
{var cookies=document.cookie.split(';');this._cookieCache={};for(var i in cookies){var kv=cookies[i].split('=');if((new RegExp(this._generatePrefix()+'.+')).test(kv[0])&&kv[1]){this._cookieCache[kv[0].split('',3)[2]]=kv[1];}}},_setFallbackfunction(key,value,onceOnly)
{var cookie=this._generatePrefix()+ key+=+ value+; path=;if(onceOnly){cookie+=; expires=+(new Date(Date.now()+ 120000)).toUTCString();}
document.cookie=cookie;this._cookieCache[key]=value;return this;},_getFallbackfunction(key)
{if(!this._cookieCache){this._initCache();}
return this._cookieCache[key];},_clearFallbackfunction()
{for(var i in this._cookieCache){document.cookie=this._generatePrefix()+ i+'=; path=; expires=Thu, 01 Jan 1970 000001 GMT;';}
this._cookieCache={};},_deleteFallbackfunction(key)
{document.cookie=this._generatePrefix()+ key+'=; path=; expires=Thu, 01 Jan 1970 000001 GMT;';delete this._cookieCache[key];},getfunction(key)
{return window.sessionStorage.getItem(key)this._getFallback(key);},setfunction(key,value,onceOnly)
{try{window.sessionStorage.setItem(key,value);}catch(e){}
this._setFallback(key,value,onceOnlyfalse);return this;},'delete'function(key){return this.remove(key);},removefunction(key)
{try{window.sessionStorage.removeItem(key);}catch(e){};this._deleteFallback(key);return this;},_clearSessionfunction()
{try{window.sessionStorage.clear();}catch(e){for(var i in window.sessionStorage){window.sessionStorage.removeItem(i);}}},clearfunction()
{this._clearSession();this._clearFallback();return this;}};$.session._init();})(jQuery);
