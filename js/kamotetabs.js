//############################################################################################
//####  Name:             kamotetabs.js                                                   ####
//####  Version:          2.0                                                             ####
//####  Copyright:        GOAutoDial Inc. - Januarius Manipol <januarius@goautodial.com>  ####
//####  License:          AGPLv2                                                          ####
//############################################################################################

var iframeids=["myframe","myframe1","myframe2","myframe3"]
var iframehide="yes"
var getFFVersion=navigator.userAgent.substring(navigator.userAgent.indexOf("Firefox")).split("/")[1]
var FFextraHeight=parseFloat(getFFVersion)>=0.1? 16 : 0

function killwithme() {
	document.getElementById("span_loader").innerHTML = '';
}

function resizeCaller() {
var dyniframe=new Array()
for (i=0; i<iframeids.length; i++){
if (document.getElementById)
resizeIframe(iframeids[i])
if ((document.all || document.getElementById) && iframehide=="no"){
var tempobj=document.all? document.all[iframeids[i]] : document.getElementById(iframeids[i])
tempobj.style.display="block"
}
}
}

function resizeIframe(frameid){
var currentfr=document.getElementById(frameid)
if (currentfr && !window.opera){
currentfr.style.display="block"
if (currentfr.contentDocument && currentfr.contentDocument.body.offsetHeight)
currentfr.height = currentfr.contentDocument.body.offsetHeight+FFextraHeight;
else if (currentfr.Document && currentfr.Document.body.scrollHeight)
currentfr.height = currentfr.Document.body.scrollHeight;
if (currentfr.addEventListener)
currentfr.addEventListener("load", readjustIframe, false)
else if (currentfr.attachEvent){
currentfr.detachEvent("onload", readjustIframe)
currentfr.attachEvent("onload", readjustIframe)
}
}
}

function readjustIframe(loadevt) {
var crossevt=(window.event)? event : loadevt
var iframeroot=(crossevt.currentTarget)? crossevt.currentTarget : crossevt.srcElement
if (iframeroot)
resizeIframe(iframeroot.id);
killwithme();
}

function loadintoIframe(iframeid, url, rtype){
	document.getElementById("span_loader").innerHTML = '<img src="ajaxlib/preloader.gif" >&nbsp;&nbsp;<span style="font-family: Helvetica; color: #4f6b72; font-size: 12px;">Loading...</span>';	
	var rrt  = rtype;
	if (rrt == 'arg_ast_rel'){}	
	if (rrt == 'arg_ast_res'){}		
	if (rrt == 'arg_ast_sta'){}	
	if (rrt == 'arg_ast_sto'){}	
	if (rrt == 'arg_ast_opt'){}	
	if (rrt == 'arg_net_res'){}	
	if (rrt == 'arg_net_sta'){}	
	if (rrt == 'arg_my_res'){}
	if (rrt == 'arg_my_backup'){}
	if (rrt == 'arg_fire_res'){}
	if (rrt == 'arg_fire_restore'){}
	if (rrt == 'arg_httpd_restore'){}
	if (rrt == 'arg_apa_rel'){}
	if (rrt == 'arg_apa_res'){
	setTimeout("document.getElementById('myframe1').src='g_apa_res.php?action=restart'",1000);
	setTimeout("document.getElementById('myframe1').src='g_apa_res.php?action=check'",1500);
//	setTimeout("document.getElementById('span_loader').innerHTML = '<FONT FACE=VERDANA COLOR=BLACK SIZE=2 style=\"font-weight:bold\">Apache Webserver is now being restarted!'",2000);
	setTimeout("document.getElementById('myframe1').src='g_apa_res.php?action=result'",3000);
	return;
	}	
	if (rrt == 'arg_sys_reb'){}		
	if (rrt == 'arg_ast_cti'){
	var varsn =  'status' + '=' + 'on';	
	}
	
	if (rrt == 'arg_ast_cti_stop'){
	var varsn =  'status' + '=' + 'off';	
	}

	if (rrt == 'arg_query_files'){
	var varsn =  document.getElementById("path").name + '=' + document.getElementById("path").value;	
	}
	
	if (rrt == 'arg_net_eth0_upd'){
	var varsn =  document.getElementById("old_ip").name + '=' + document.getElementById("old_ip").value
	+ '&' + document.getElementById("new_ip").name + '=' + document.getElementById("new_ip").value
	+ '&' + document.getElementById("net_mask").name + '=' + document.getElementById("net_mask").value
	+ '&' + document.getElementById("net_gw").name + '=' + document.getElementById("net_gw").value;
	}	

	if (rrt == 'arg_net_eth1_upd'){
	var varsn =  document.getElementById("old_ip").name + '=' + document.getElementById("old_ip").value
	+ '&' + document.getElementById("new_ip").name + '=' + document.getElementById("new_ip").value
	+ '&' + document.getElementById("net_mask").name + '=' + document.getElementById("net_mask").value
	+ '&' + document.getElementById("net_gw").name + '=' + document.getElementById("net_gw").value;
	}	

	if (rrt == 'arg_ast_upd_ip'){
	var varsn =  document.getElementById("old_ip").name + '=' + document.getElementById("old_ip").value
	+ '&' + document.getElementById("new_ip").name + '=' + document.getElementById("new_ip").value;	
	}	
	
	//if (rrt == 'cstatistics'){
	//var varsn =  document.getElementById("to_query_time").name + '=' + document.getElementById("to_query_time").value 
	//+ '&' + document.getElementById("from_query_time").name + '=' + document.getElementById("from_query_time").value
	//+ '&' + document.getElementById("group").name + '=' + document.getElementById("group").value
	//+ '&' + document.getElementById("dispo").name + '=' + document.getElementById("dispo").value		
	//+ '&' + document.getElementById("to_query_date").name + '=' + document.getElementById("to_query_date").value	
	//+ '&' + document.getElementById("from_query_date").name + '=' + document.getElementById("from_query_date").value;	
	//}	

	//if (rrt == 'atimesheet'){
	//var varsn =  document.getElementById("to_query_time").name + '=' + document.getElementById("to_query_time").value 
	//+ '&' + document.getElementById("from_query_time").name + '=' + document.getElementById("from_query_time").value
	//+ '&' + document.getElementById("user").name + '=' + document.getElementById("user").value	
	//+ '&' + document.getElementById("to_query_date").name + '=' + document.getElementById("to_query_date").value	
	//+ '&' + document.getElementById("from_query_date").name + '=' + document.getElementById("from_query_date").value;	
	//}		
	
	//if (rrt == 'recordings'){
	//var varsn =  document.getElementById("end_time").name + '=' + document.getElementById("end_time").value 
	//+ '&' + document.getElementById("start_time").name + '=' + document.getElementById("start_time").value
	//+ '&' + document.getElementById("user").name + '=' + document.getElementById("user").value
	//+ '&' + document.getElementById("phone_number").name + '=' + document.getElementById("phone_number").value
	//+ '&' + document.getElementById("to_query_date").name + '=' + document.getElementById("to_query_date").value	
	//+ '&' + document.getElementById("from_query_date").name + '=' + document.getElementById("from_query_date").value;	
	//}	
	
	if (document.getElementById)
		document.getElementById(iframeid).src=url + '?' + varsn	
}

if (window.addEventListener)
window.addEventListener("load", resizeCaller, false)
else if (window.attachEvent)
window.attachEvent("onload", resizeCaller)
else
window.onload=resizeCaller
