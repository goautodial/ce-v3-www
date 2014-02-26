<?php
########################################################################################################
####  Name:             	go_admin_header_jsloader.php   	                        	    ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
?>

<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<? echo $base; ?>js/excanvas.min.js"></script><![endif]-->
<script src="<? echo $base; ?>js/jquery.main.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.ui.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.notify.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.flot.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.flot.resize.js" type="text/javascript"></script>

<script language="JavaScript">
window.onbeforeunload = confirmExit;
window.onbeforeunload = logout;

function logout()
		{
		window.location = 'closebrowser';
		}

function confirmExit()
		{
		return "Click OK to exit, Click CANCEL to stay.";
		}
</script>

<script type="text/javascript">
//<![CDATA[
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
var userSettings = {
		'url': '/',
		'uid': '1',
		'time':'1309277858'
	},
	ajaxurl = 'http://demo002.gopredictive.com/admin-ajax.php',
	pagenow = 'dashboard',
	typenow = '',
	adminpage = 'index-php',
	thousandsSeparator = ',',
	decimalPoint = '.',
	isRtl = 0;
//]]>
</script>

<script type="text/javascript">

//var currenttime = '<!--#config timefmt="%B %d, %Y %H:%M:%S"--><!--#echo var="DATE_LOCAL" -->' //SSI method of getting server date
var currenttime = '<? print date("F d, Y H:i:s", time())?>' //PHP method of getting server date

var todayarray=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
var serverdate=new Date(currenttime)

function padlength(what){
var output=(what.toString().length==1)? "0"+what : what
return output
}

function displaytime(){
serverdate.setSeconds(serverdate.getSeconds()+1)
var todaystring=todayarray[serverdate.getDay()]
var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear()
var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds())
document.getElementById("clockbox").innerHTML=todaystring+", "+datestring+"<br>"+timestring
}

$(document).ready(function()
{
setInterval("displaytime()", 1000)
});

</script>


<script type="text/javascript">
function create( template, vars, opts ){
	return $container.notify("create", template, vars, opts);
}

$(function(){
	// initialize widget on a container, passing in all the defaults.
	// the defaults will apply to any notification created within this
	// container, but can be overwritten on notification-by-notification
	// basis.
	$container = $("#container").notify();
	
	
	// create two when the pg loads
	//create("default", { title:'Hello <? echo $uname; ?>!', text:"Welcome back! I've just made a quick checked on the dialer system and it looks great! I'll be back soon for updates! Have a nice day!"});
	create("sticky", { title:'Hello <? echo $uname; ?>!', text:"Welcome back! I've just made a quick check on your system and it looks great! I'll be back soon for more updates! Have a nice day!"});
	
	//create("sticky", { title:'Sticky Notification', text:'Example of a "sticky" notification.  Click on the X above to close me.'},{ expires:false });
	
	// bindings for the examples
	//$("#default").click(function(){
	//	create("default", { title:'Default Notification', text:'Example of a default notification.  I will fade out after 5 seconds'});
	//});
	
	//$("#sticky").click(function(){
	//	create("sticky", { title:'Sticky Notification', text:'Example of a "sticky" notification.  Click on the X above to close me.'},{ expires:false });
	//});
	
	//$("#warning").click(function(){
	//	create("withIcon", { title:'Warning!', text:'OMG the quick brown fox jumped over the lazy dog.  You\'ve been warned. <a href="#" class="ui-notify-close">Close me.</a>', icon:'alert.png' },{ 
	//		expires:false
	//	});
	//});
	//
	//$("#themeroller").click(function(){
	//	create("themeroller", { title:'Warning!', text:'The <code>custom</code> option is set to false for this notification, which prevents the widget from imposing it\'s own coloring.  With this option off, you\'re free to style however you want without changing the original widget\'s CSS.' },{
	//		custom: true,
	//		expires: false
	//	});
	//});
	//
	//$("#clickable").click(function(){
	//	create("default", { title:'Clickable Notification', text:'Click on me to fire a callback. Do it quick though because I will fade out after 5 seconds.'}, {
	//		click: function(e,instance){
	//			alert("Click triggered!\n\nTwo options are passed into the click callback: the original event obj and the instance object.");
	//		}
	//	});
	//});
	
	//$("#buttons").click(function(){
	//	var n = create("buttons", { title:'Confirm some action', text:'This template has a button.' },{ 
	//		expires:false
	//	});
	//	
	//	n.widget().delegate("input","click", function(){
	//		n.close();
	//	});
	//});
	// second
	var container = $("#container-bottom").notify({ stack:'above' });
	container.notify("create", { 
		title:'Look ma, two containers!', 
		text:'This container is positioned on the bottom of the screen.  Notifications will stack on top of each other with the <code>position</code> attribute set to <code>above</code>.' 
	},{ expires:false });
	
	container.notify("widget").find("input").bind("click", function(){
		container.notify("create", 1, { title:'Another Notification!', text:'The quick brown fox jumped over the lazy dog.' });
	});
});
</script>
<!--show: false-->

<script type="text/javascript" charset="utf-8">
/* CSS Browser Selector */
function css_browser_selector(u){var ua=u.toLowerCase(),is=function(t){return ua.indexOf(t)>-1},g='gecko',w='webkit',s='safari',o='opera',m='mobile',h=document.documentElement,b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+RegExp.$1):is('firefox/2')?g+' ff2':is('firefox/3.5')?g+' ff3 ff3_5':is('firefox/3.6')?g+' ff3 ff3_6':is('firefox/3')?g+' ff3':is('gecko/')?g:is('opera')?o+(/version\/(\d+)/.test(ua)?' '+o+RegExp.$1:(/opera(\s|\/)(\d+)/.test(ua)?' '+o+RegExp.$2:'')):is('konqueror')?'konqueror':is('blackberry')?m+' blackberry':is('android')?m+' android':is('chrome')?w+' chrome':is('iron')?w+' iron':is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:''):is('mozilla/')?g:'',is('j2me')?m+' j2me':is('iphone')?m+' iphone':is('ipod')?m+' ipod':is('ipad')?m+' ipad':is('mac')?'mac':is('darwin')?'mac':is('webtv')?'webtv':is('win')?'win'+(is('windows nt 6.0')?' vista':''):is('freebsd')?'freebsd':(is('x11')||is('linux'))?'linux':'','js']; c = b.join(' '); h.className += ' '+c; return c;}; css_browser_selector(navigator.userAgent);
</script>

<script type="text/javascript">
/* <![CDATA[ */
var quicktagsL10n = {
	quickLinks: "(Quick Links)",
	wordLookup: "Enter a word to look up:",
	dictionaryLookup: "Dictionary lookup",
	lookup: "lookup",
	closeAllOpenTags: "Close all open tags",
	closeTags: "close tags",
	enterURL: "Enter the URL",
	enterImageURL: "Enter the URL of the image",
	enterImageDescription: "Enter a description of the image"
};
try{convertEntities(quicktagsL10n);}catch(e){};
/* ]]> */
</script>
<script type="text/javascript" src="<? echo $base; ?>js/go_hload_scripts.js"></script>






