<?php
########################################################################################################
####  Name:             	go_dashboard_header_jsloader.php         	                    ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$nameserver = $_SERVER['SERVER_NAME'];
?>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<? echo $base; ?>js/excanvas.min.js"><script><![endif]-->
<script src="<? echo $base; ?>js/jquery.main.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.ui.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.notify.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.flot.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.flot.resize.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.flot.pie.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery-validate/jquery.validate.min.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jstree/jquery.jstree.js"></script>
<script src="<? echo $base; ?>js/go_user/jquery.tinyscrollbar.min.js"></script>
<script src="<? echo $base; ?>js/tooltip/jquery.tipTip.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/msgbox/jquery.msgbox.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/msgbox/jquery.dragndrop.min.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.form.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.tablePagination.0.5.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.tablePagination.0.5_payment.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.redirect.min.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/joyride/jquery.joyride-2.0.3.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jmp3/jquery.jmp3.js" type="text/javascript"></script>

<!-- james -->
<script type="text/javascript" src="<?=$base?>js/datepicker/datepicker.js"></script> 
<script type="text/javascript" src="<?=$base?>js/datepicker/eye.js"></script>
<script type="text/javascript" src="<?=$base?>js/datepicker/utils.js"></script>  
<script type="text/javascript" src="<?=$base?>js/datepicker/layout.js?ver=1.0.2"></script>
<!-- james -->



<script type="text/javascript">

$.fn.enterKey = function (fnc) {
    return this.each(function () {
         $(this).keypress(function (ev) {
              var keycode = (ev.keyCode ? ev.keyCode : ev.which);
              if (keycode == '13') {
                      fnc.call(this, ev);
              }
         })
    })
}


function lookup(inputString)
		{

		if(inputString.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else {


		dataString =  '/' + inputString ;
		$.ajax({
		type: "POST",
		url: "<?=$base?>index.php/go_site/go_dashboard_search"+dataString,
		// data:  dataString,
		success: function(data)
		{
		var status = data;
		$("#suggestions").show();
		$("#autoSuggestionsList").fadeIn("fast").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');


		if (data != '')
		{


		$('#autoSuggestionsList').load('<?=$base?>index.php/go_site/go_dashboard_search'+dataString).fadeIn("fast");

		}
		else{
		$('#suggestions').hide();
		$('#autoSuggestionsList').html();

		}

   }
   });



}
		}


	function fill(thisValue) {
		$('#inputString').val(thisValue);
                window.location = "<?=$base?>index.php/search/"+thisValue+"/2";
		//setTimeout("$('#suggestions').hide();", 200);
	}
</script>


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
	//ajaxurl = 'http://demo002.gopredictive.com/admin-ajax.php',
	ajaxurl = '',
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
var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear();
var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds());
document.getElementById("clockbox").innerHTML=todaystring+", "+datestring+" "+timestring;
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
	//create("default", { title:'Hello <? echo $userfulname; ?>!', text:"Welcome back! I've just made a quick checked on the dialer system and it looks great! I'll be back soon for updates! Have a nice day!"});
	//create("sticky", { title:'Hello <? echo $userfulname; ?>!', text:"Welcome back! I've just made a quick check on your system and it looks great! I'll be back soon for more updates! Have a nice day!"});

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

	$('#notification-open-link').click(function()
	{
		$.post("<? echo $base; ?>index.php/go_site/get_notification/", function(result)
		{
			var strResult = result.split('!N!');
			var strNotify = $('#openNotification').text();
			
			for (i=0; i<strResult.length; i++)
			{
				var strOutput = strResult[i].split('|');
				if (strOutput[3]!==undefined)
				{
					if (strNotify.search("-"+strOutput[0]+"-") < 0)
					{
						$('#openNotification').append("-"+strOutput[0]+"-");
						create("sticky", { title: strOutput[3], text: strOutput[4], id: strOutput[0]},{ expires: false });
					}
				}
			}
		});
	});

	// GO GET NOTIFICATION
	$.post('<? echo $base; ?>index.php/go_site/check_notification/', function(result)
	{
		if (result > 0)
		{
			$(this).css('background-image','url("<? echo $base; ?>img/notification-red.png")');
			$('#notification-open-link').hover(function()
			{
				$(this).css('background-image','url("<? echo $base; ?>img/notification-red.png")');
			}, function()
			{
				$(this).css('background-image','url("<? echo $base; ?>img/notification-red.png")');
			});
// 			$('#notification-open-link-wrap').show();
		} else {
			$(this).css('background-image','url("<? echo $base; ?>img/notification.png")');
			$('#notification-open-link').hover(function()
			{
				$(this).css('background-image','url("<? echo $base; ?>img/notification-hover.png")');
			}, function()
			{
				$(this).css('background-image','url("<? echo $base; ?>img/notification.png")');
			});
// 			$('#notification-open-link-wrap').hide();
		}
	});

	// Reload every 30 seconds
	var refreshId = setInterval(function()
		{
			// GO GET NOTIFICATION
			$.post('<? echo $base; ?>index.php/go_site/check_notification/', function(result)
			{
				if (result > 0)
				{
					$('#notification-open-link').css('background-image','url("<?=$base?>img/notification-red.png")');
					if ($('#checkNotification').is(':visible'))
					{
						if (result > 1)
						{
							var s = "s";
						} else {
							var s = "";
						}
						create("themeroller", { title: "New Notification", text: "You have received "+result+" new notification"+s+".<br />Please click <img src=\"<? echo $base; ?>img/notification.png\" style=\"vertical-align:bottom\" /> above to read."});
						$('#checkNotification').hide();
					}
				} else {
					$('#notification-open-link').css('background-image','url("<?=$base?>img/notification.png")');
					$('#checkNotification').show();
				}
			});
			
		}, 5000);
});
</script>
<!--show: false-->

<script type="text/javascript" charset="utf-8">
/* CSS Browser Selector */
function css_browser_selector(u){var ua=u.toLowerCase(),is=function(t){return ua.indexOf(t)>-1},g='gecko',w='webkit',s='safari',o='opera',m='mobile',h=document.documentElement,b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+RegExp.$1):is('firefox/2')?g+' ff2':is('firefox/3.5')?g+' ff3 ff3_5':is('firefox/3.6')?g+' ff3 ff3_6':is('firefox/3')?g+' ff3':is('gecko/')?g:is('opera')?o+(/version\/(\d+)/.test(ua)?' '+o+RegExp.$1:(/opera(\s|\/)(\d+)/.test(ua)?' '+o+RegExp.$2:'')):is('konqueror')?'konqueror':is('blackberry')?m+' blackberry':is('android')?m+' android':is('chrome')?w+' chrome':is('iron')?w+' iron':is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:''):is('mozilla/')?g:'',is('j2me')?m+' j2me':is('iphone')?m+' iphone':is('ipod')?m+' ipod':is('ipad')?m+' ipad':is('mac')?'mac':is('darwin')?'mac':is('webtv')?'webtv':is('win')?'win'+(is('windows nt 6.0')?' vista':''):is('freebsd')?'freebsd':(is('x11')||is('linux'))?'linux':'','js']; c = b.join(' '); h.className += ' '+c; return c;}; css_browser_selector(navigator.userAgent);
$(function(){
   $("#walk").click(function(){
        var $val = '0';
        if($(this).prop('checked') === true){
             $val = '1';
        }
        var $data = {account:$("#compid").val(),newsignup:$val};
        $.post(
               "<?=$base?>index.php/go_site/walkupdate",
               $data,
               function(data){
                   location.reload();
               }
        );
   });

});
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






