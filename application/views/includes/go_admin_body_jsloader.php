<?php
########################################################################################################
####  Name:             	go_admin_body_jsloader.php   	                        	    ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  Modified by:	        Jerico James Milo	                                            ####
####				Christopher Lomuntad						    ####
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


<script type="text/javascript">
$(document).ready(function()
	{
		//sippy                
		$("#sippydiv").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
                $('#sippydiv').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/sippyinfo').fadeIn("slow");
		//end sippy

		$("#table_sales").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		//$("#table_sales").fadeIn("slow").load("<? echo $base; ?>index.php/go_site/go_widget_today");
		$('#table_sales').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_sales_today').fadeIn("slow");
		
		$("#table_calls").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_calls').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_dashboard_calls_today').fadeIn("slow");
		
		$("#table_vitals").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_vitals').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_server_vitals').fadeIn("slow");

		$("#table_hardware").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_hardware').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_server_hardware').fadeIn("slow");
		
		$("#table_memory").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_memory').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_server_memory').fadeIn("slow");

		$("#table_filesystems").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_filesystems').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_server_filesystems').fadeIn("slow");

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

		var refreshId = setInterval(function()
			{
				// $("#table_sales").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');	
				//$('#table_sales').slideUp(300).delay(800).fadeIn(400);	
				//$("#table_sales").effect("shake", { times:3 }, 300);
				//$("#table_sales").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
				//$('#table_sales').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_widget_today').fadeIn("slow");
				$('#table_sales').load('<? echo $base; ?>index.php/go_site/go_dashboard_sales_today');
				$('#table_calls').load('<? echo $base; ?>index.php/go_site/go_dashboard_calls_today');

				$('#table_agents').load('<? echo $base; ?>index.php/go_site/go_dashboard_agents');
				$('#table_leads').load('<? echo $base; ?>index.php/go_site/go_dashboard_leads');
// 				$('#table_analytics_in').load('<? echo $base; ?>index.php/go_site/go_dashboard_analytics_in');
// 				$('#table_analytics_out').load('<? echo $base; ?>index.php/go_site/go_dashboard_analytics_out');



				$('#table_vitals').load('<? echo $base; ?>index.php/go_site/go_server_vitals');
				$('#table_memory').load('<? echo $base; ?>index.php/go_site/go_server_memory');
				$('#table_filesystems').load('<? echo $base; ?>index.php/go_site/go_server_filesystems');
				



			}, 5000);
		

                //sippy reloads every 5 minutes
                var refreshId = setInterval(function()
                        {
                                $('#sippydiv').load('<? echo $base; ?>index.php/go_site/sippyinfo');
                        }, 300000);

		
		var refreshId = setInterval(function()
			{
				$('#rss_widget').load('<? echo $base; ?>index.php/go_site/go_rssview').fadeIn("slow");
				$('#rss_widget_others').load('<? echo $base; ?>index.php/go_site/go_rssview_others').fadeIn("slow");

			}, 50000);


		var refreshId = setInterval(function()
			{
				$('#table_analytics_in').load('<? echo $base; ?>index.php/go_site/go_dashboard_analytics_in').fadeIn("slow");
				$('#table_analytics_out').load('<? echo $base; ?>index.php/go_site/go_dashboard_analytics_out').fadeIn("slow");

			}, 300000);
});
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
		
		//Apply Margin to Popup
		$('#' + popID).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		//Fade in Background
		$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
		$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer 
                
	});
        
        
	
	//Close Popups and Fade Layer
	$('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...
	  	$('#fade , .popup_block').fadeOut(function() {
			$('#fade, a.close').remove();  
	
        
                
        }); //fade them both out
			

                                                        history.back(0);	
                        });
	
});
</script>



