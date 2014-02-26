<?php
########################################################################################################
####  Name:             	go_dashboard_body_jsloader.php   	                            ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Originated by:	        Rodolfo Januarius T. Manipol                                        ####
####  Written by:	        Christopher Lomuntad	                                            ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
?>

<script type="text/javascript">
//<![CDATA[
(function(){
var c = document.body.className;
c = c.replace(/no-js/, 'js');
document.body.className = c;
})();
//]]>
</script>


<script type="text/javascript">
/* <![CDATA[ */
var commonL10n = {
	warnDelete: "You are about to permanently delete the selected items.\n  \'Cancel\' to stop, \'OK\' to delete."
};
try{convertEntities(commonL10n);}catch(e){};
var wpAjax = {
	noPerm: "You do not have permission to do that.",
	broken: "An unidentified error has occurred."
};
try{convertEntities(wpAjax);}catch(e){};
var adminCommentsL10n = {
	hotkeys_highlight_first: "",
	hotkeys_highlight_last: ""
};
var thickboxL10n = {
	next: "Next &gt;",
	prev: "&lt; Prev",
	image: "Image",
	of: "of",
	close: "Close",
	noiframes: "This feature requires inline frames. You have iframes disabled or your browser does not support them."
};
try{convertEntities(thickboxL10n);}catch(e){};
var plugininstallL10n = {
	show: false,
	plugin_information: "Plugin Information:",
	ays: "Are you sure you want to install this plugin?"
};
try{convertEntities(plugininstallL10n);}catch(e){};
/* ]]> */
</script>
<script type="text/javascript" src="<? echo $base; ?>js/go_fload_scripts.js"></script>
<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>


<script>
$(document).ready(function()
	{
		var isExpanded = $('#isExpanded').html();
		var orderByRealtime = $('#orderByRealtime').html();
		var realtimeInterval = $('#realtimeInterval').html();
		var selectedCampaign = $('#selectedCampaign').html();


		//sippy                
		$("#sippydiv").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
                $('#sippydiv').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/sippyinfo').fadeIn("slow");
		//end sippy


		$("#table_sales").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		//$("#table_sales").fadeIn("slow").load("<? echo $base; ?>index.php/go_site/go_widget_today");
		$('#table_sales').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_sales_today').fadeIn("slow");

		$("#table_calls").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_calls').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_calls_today').fadeIn("slow");

		$("#table_drops").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_drops').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_drops_today').fadeIn("slow");

		
		$("#table_clusters").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_clusters').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_cluster_status').fadeIn("slow");

		$("#table_vitals").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_vitals').fadeOut("slow").load('<? echo $base; ?>application/views/phpsysinfo/vitals.php').fadeIn("slow");

		$("#table_hardware").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_hardware').fadeOut("slow").load('<? echo $base; ?>application/views/phpsysinfo/hardware.php').fadeIn("slow");

		$("#table_memory").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_memory').fadeOut("slow").load('<? echo $base; ?>application/views/phpsysinfo/memory.php').fadeIn("slow");

		$("#table_filesystems").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_filesystems').fadeOut("slow").load('<? echo $base; ?>application/views/phpsysinfo/filesystems.php').fadeIn("slow");

		$("#table_agents").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_agents').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_agents').fadeIn("slow");

		$("#table_leads").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_leads').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_leads').fadeIn("slow");

		$("#table_analytics_in").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_analytics_in').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_analytics_in').fadeIn("slow");

		$("#table_analytics_out").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_analytics_out').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_analytics_out').fadeIn("slow");

		$("#rss_widget").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#rss_widget').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_rssview').fadeIn("slow");

		$("#rss_widget_others").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#rss_widget_others').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_rssview_others').fadeIn("slow");

		$("#table_num_seats").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_num_seats').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_account_get_num_seats').fadeIn("slow");

		$("#table_url_resources").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_url_resources').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_account_get_urls').fadeIn("slow");

		$("#table_logins").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_logins').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_account_get_logins/list_agents').fadeIn("slow");

		$("#table_phones").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_phones').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_account_get_logins/list_phones').fadeIn("slow");

		$("#asterisk_status").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$("#mysql_status").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$("#httpd_status").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$("#nic_status").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$("#ftp_status").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$("#ssh_status").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');


		<?php
		if (strtolower($bannertitle) == "dashboard")
		{
		?>
                $.post(
                       "<?=$base?>index.php/go_site/control_panel",
                       function(data){
                            var $results = JSON.parse(data);
                            $("#asterisk_status").empty().css("color",(($results.asterisk === 'Running')?'green':'#FF0000')).append($results.asterisk+"<input type='button' onclick='javascript:cpanel(this);' style='float:right;cursor:pointer;' id='asterisk' value='"+($results.asterisk==="Running"?'Reload':'Start')+"' />");
                            $("#mysql_status").empty().css("color",(($results.mysql === 'Running')?'green':'#FF0000')).append($results.mysql+"<input type='button' onclick='javascript:cpanel(this);' style='float:right;cursor:pointer;' id='mysql' value='"+($results.mysql==="Running"?'Reload':'Start')+"' />");
                            $("#httpd_status").empty().css("color",(($results.httpd === 'Running')?'green':'#FF0000')).append($results.httpd+"<input type='button' onclick='javascript:cpanel(this);' style='float:right;cursor:pointer;' id='httpd' value='"+($results.httpd==="Running"?'Reload':'Start')+"' />");
                            $("#nic_status").empty().css("color",(($results.nic === 'Running')?'green':'#FF0000')).append($results.nic+"<input type='button' onclick='javascript:cpanel(this);' style='float:right;cursor:pointer;' id='nic' value='"+($results.nic==="Running"?'Reload':'Start')+"' />");
                            $("#ftp_status").empty().css("color",(($results.ftp === 'true')?'green':'#707070')).append(($results.ftp === true)?'Running':'<span style="font-style:italic">Disabled</span>');
                            $("#ssh_status").empty().css("color",(($results.sshd === 'Running')?'green':'#FF0000')).append($results.sshd+"<input type='button' onclick='javascript:cpanel(this);' style='float:right;cursor:pointer;' id='sshd' value='"+($results.nic==="Running"?'Reload':'Start')+"' />");

//                             $("#asterisk_status").empty().css("color",(($results.asterisk === 'Running')?'green':'#FF0000')).append($results.asterisk+"<a onclick='cpanel(this)' style='float:right;cursor:pointer;' id='asterisk'>"+($results.asterisk==="Running"?'Reload':'Start')+"</a>");
//                             $("#mysql_status").empty().css("color",(($results.mysql === 'Running')?'green':'#FF0000')).append($results.mysql+"<a onclick='cpanel(this)' style='float:right;cursor:pointer;' id='mysql'>"+($results.mysql==="Running"?'Reload':'Start')+"</a>");
//                             $("#httpd_status").empty().css("color",(($results.httpd === 'Running')?'green':'#FF0000')).append($results.httpd+"<a onclick='cpanel(this)' style='float:right;cursor:pointer;' id='httpd'>"+($results.httpd==="Running"?'Reload':'Start')+"</a>");
//                             $("#nic_status").empty().css("color",(($results.nic === 'Running')?'green':'#FF0000')).append($results.nic+"<a onclick='cpanel(this)' style='float:right;cursor:pointer;' id='nic'>"+($results.nic==="Running"?'Reload':'Start')+"</a>");
//                             $("#ftp_status").empty().css("color",(($results.ftp === 'true')?'green':'#707070')).append(($results.ftp === true)?'Running':'<span style="font-style:italic">Disabled</span>');
//                             $("#ssh_status").empty().css("color",(($results.sshd === 'Running')?'green':'#FF0000')).append($results.sshd);
                            }
                );
                var refreshId = setInterval(function(){
                         $.post(
                                "<?=$base?>index.php/go_site/control_panel",
                                function(data){
									var $results = JSON.parse(data);
									$("#asterisk_status").empty().css("color",(($results.asterisk === 'Running')?'green':'#FF0000')).append($results.asterisk+"<input type='button' onclick='cpanel(this)' style='float:right;cursor:pointer;' id='asterisk' value='"+($results.asterisk==="Running"?'Reload':'Start')+"' />");
									$("#mysql_status").empty().css("color",(($results.mysql === 'Running')?'green':'#FF0000')).append($results.mysql+"<input type='button' onclick='cpanel(this)' style='float:right;cursor:pointer;' id='mysql' value='"+($results.mysql==="Running"?'Reload':'Start')+"' />");
									$("#httpd_status").empty().css("color",(($results.httpd === 'Running')?'green':'#FF0000')).append($results.httpd+"<input type='button' onclick='cpanel(this)' style='float:right;cursor:pointer;' id='httpd' value='"+($results.httpd==="Running"?'Reload':'Start')+"' />");
									$("#nic_status").empty().css("color",(($results.nic === 'Running')?'green':'#FF0000')).append($results.nic+"<input type='button' onclick='cpanel(this)' style='float:right;cursor:pointer;' id='nic' value='"+($results.nic==="Running"?'Reload':'Start')+"' />");
									$("#ftp_status").empty().css("color",(($results.ftp === 'true')?'green':'#707070')).append(($results.ftp === true)?'Running':'<span style="font-style:italic">Disabled</span>');
									$("#ssh_status").empty().css("color",(($results.sshd === 'Running')?'green':'#FF0000')).append($results.sshd+"<input type='button' onclick='cpanel(this)' style='float:right;cursor:pointer;' id='sshd' value='"+($results.nic==="Running"?'Reload':'Start')+"' />");


//                                      $("#asterisk_status").empty().css("background-color",(($results.asterisk === 'Running')?'#E0F8E0':'#E0F8E0')).append($results.asterisk+"<a onclick='cpanel(this)' style='float:right;cursor:pointer;' id='asterisk'>"+($results.asterisk==="Running"?'Reload':'Start')+"</a>");
//                                      $("#mysql_status").empty().css("background-color",(($results.mysql === 'Running')?'#E0F8E0':'#E0F8E0')).append($results.mysql+"<a onclick='cpanel(this)' style='float:right;cursor:pointer;' id='mysql'>"+($results.mysql==="Running"?'Reload':'Start')+"</a>");
//                                      $("#httpd_status").empty().css("background-color",(($results.httpd === 'Running')?'#E0F8E0':'#E0F8E0')).append($results.httpd+"<a onclick='cpanel(this)' style='float:right;cursor:pointer;' id='httpd'>"+($results.httpd==="Running"?'Reload':'Start')+"</a>");
//                                      $("#nic_status").empty().css("background-color",(($results.nic === 'Running')?'#E0F8E0':'#E0F8E0')).append($results.nic+"<a onclick='cpanel(this)' style='float:right;cursor:pointer;' id='nic'>"+($results.nic==="Running"?'Reload':'Start')+"</a>");
//                                      $("#ftp_status").empty().css("background-color",(($results.ftp === 'true')?'#E0F8E0':'#E0F8E0')).append($results.ftp);
                                }
                         );  
                },60000);
		<?php
		}
		?>


		if ($('#boxRealTime').is(':visible'))
		{
			$("#overlayContentRealTime").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
			if ($('#toggleMonitoring').text() == 'agents')
			{
				$('#overlayContentRealTime').load('<? echo $base; ?>index.php/go_site/go_monitoring/'+isExpanded+'/'+orderByRealtime+'/agents/'+selectedCampaign);
			} else {
				$('#overlayContentRealTime').load('<? echo $base; ?>index.php/go_site/go_monitoring/'+isExpanded+'/'+orderByRealtime+'/calls/'+selectedCampaign);
			}
		}

		// Reloads every 5 seconds
		var refreshId = setInterval(function()
			{
				// $("#table_sales").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
				//$('#table_sales').slideUp(300).delay(800).fadeIn(400);
				//$("#table_sales").effect("shake", { times:3 }, 300);
				//$("#table_sales").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
				//$('#table_sales').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_widget_today').fadeIn("slow");
				$('#table_sales').load('<? echo $base; ?>index.php/go_site/go_dashboard_sales_today');
				$('#table_calls').load('<? echo $base; ?>index.php/go_site/go_dashboard_calls_today');
				$('#table_drops').load('<? echo $base; ?>index.php/go_site/go_dashboard_drops_today');

				$('#table_agents').load('<? echo $base; ?>index.php/go_site/go_dashboard_agents');
// 				$('#table_analytics_in').load('<? echo $base; ?>index.php/go_site/go_dashboard_analytics_in');
// 				$('#table_analytics_out').load('<? echo $base; ?>index.php/go_site/go_dashboard_analytics_out');



				$('#table_vitals').load('<? echo $base; ?>application/views/phpsysinfo/vitals.php');
				$('#table_memory').load('<? echo $base; ?>application/views/phpsysinfo/memory.php');
				$('#table_filesystems').load('<? echo $base; ?>application/views/phpsysinfo/filesystems.php');
			}, 5000);
		
		//sippy reloads every 5 minutes
                var refreshId = setInterval(function()
                        {
                                $('#sippydiv').load('<? echo $base; ?>index.php/go_site/sippyinfo');
                        }, 300000);
		
		//reloads every 10 seconds
		var refreshId = setInterval(function()
			{
				$('#table_clusters').load('<? echo $base; ?>index.php/go_site/go_cluster_status');
			}, 10000);



		var refreshId = setInterval(function()
			{
				// GO MONITORING
				isExpanded = $('#isExpanded').html();
				orderByRealtime = $('#orderByRealtime').html();
				selectedCampaign = $('#selectedCampaign').html();
				selectedTenant = $('#selectedTenant').html();
				if ($('#boxRealTime').is(':visible'))
				{
					if ($('#toggleMonitoring').text() == 'agents')
					{
						$('#overlayContentRealTime').load('<? echo $base; ?>index.php/go_site/go_monitoring/'+isExpanded+'/'+orderByRealtime+'/agents/'+selectedCampaign+'/'+selectedTenant);
					} else {
						$('#overlayContentRealTime').load('<? echo $base; ?>index.php/go_site/go_monitoring/'+isExpanded+'/'+orderByRealtime+'/calls/'+selectedCampaign);
					}
				}
			}, (realtimeInterval*1000));


		var refreshId = setInterval(function()
			{
				$('#rss_widget').load('<? echo $base; ?>index.php/go_site/go_rssview').fadeIn("slow");
				$('#rss_widget_others').load('<? echo $base; ?>index.php/go_site/go_rssview_others').fadeIn("slow");



			}, 1800000);


		//var intres = 20000;
		var refreshId = setInterval(function()
			{
				$('#table_leads').load('<? echo $base; ?>index.php/go_site/go_dashboard_leads');
			}, 30000);


		// Reloads every 5 minutes
		var refreshId = setInterval(function()
			{
				$('#table_analytics_in').load('<? echo $base; ?>index.php/go_site/go_dashboard_analytics_in').fadeIn("slow");
				$('#table_analytics_out').load('<? echo $base; ?>index.php/go_site/go_dashboard_analytics_out').fadeIn("slow");

			}, 300000);
});

function cpanel(obj){
 
    var type=$(obj).attr("id"),action=$(obj).val();

    $(obj).hide();
    $(obj).parent().append('<img src="<? echo $base; ?>img/loading.gif" style="float:right;" />');
    $.post(
           "<?=$base?>index.php/go_site/cpanel/"+type+'/'+action,
           function(data){
                 alert(data);
                 systemsreload();
           } 
    );

}
</script>


<script>
$(document).ready(function()
	{



	$('a.poplight[href^=#]').click(function() {
		var popID = $(this).attr('rel'); //Get Popup Name
		var popURL = $(this).attr('href'); //Get Popup href to define size

		//Pull Query & Variables from href URL
		var query= popURL.split('?');
		var dim= query[1].split('&');
		var popWidth = dim[0].split('=')[1]; //Gets the first query string value

		//Fade in the Popup and add close button


//		$('#' + popID).effect("size", { to: {height: 200} }, 1000);


		$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="<? echo base_url(); ?>img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>');

		$('#go_dashboard_analytics_in_popup_cmonth_daily').empty().html('<img src="<? echo $base; ?>img/loading.gif" />');

		$('#go_dashboard_analytics_in_popup_cmonth_daily').fadeIn("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_analytics_in_popup_cmonth_daily');

		$('#go_dashboard_analytics_in_popup_weekly').empty().html('<img src="<? echo $base; ?>img/loading.gif" />');

		$('#go_dashboard_analytics_in_popup_weekly').fadeIn("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_analytics_in_popup_weekly');

		$('#go_dashboard_analytics_in_popup_hourly').empty().html('<img src="<? echo $base; ?>img/loading.gif" />');

		$('#go_dashboard_analytics_in_popup_hourly').fadeIn("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_analytics_in_popup_hourly');


		//Define margin for center alignment (vertical + horizontal) - we add 80 to the height/width to accomodate for the padding + border width defined in the css
		var popMargTop = ($('#' + popID).height() + 80) / 2;
		var popMargLeft = ($('#' + popID).width() + 80) / 2;
		var popPosition = 'relative';
		var popValign = 'middle';
		var popDisplay = 'table-cell';
		var popMinh = '600px';






		//Apply Margin to Popup
		$('#' + popID).css({
			'margin-top' : -75 + '%',

			'left' : -25 + '%',

			'position' : popPosition,
			'min-height': popMinh
			//'vertical-align': popValign,
			//'display': popDisplay

		});

		//Fade in Background
		$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
		$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer

	});



	//Close Popups and Fade Layer
    if (/[1-9]\.[7-9].[0-9]/.test($.fn.jquery)) {
		$(document).on('click', 'a.close, #fade', function() { //When clicking on the close or fade layer...
			$('#fade , .popup_block').fadeOut(function() {
				$('#fade, a.close').remove();
			}); //fade them both out
			history.back(0);
		});
	} else {
		$('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...
			$('#fade , .popup_block').fadeOut(function() {
				$('#fade, a.close').remove();
			}); //fade them both out
			history.back(0);
		});
	}

});







</script>
