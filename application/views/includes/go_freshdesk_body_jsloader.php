<?php
############################################################################################
####  Name:             go_freshdesk_body_jsloader.php                                  ####
####  Type: 		    ci views                                                        ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Januarius Manipol <januarius@goautodial.com>  ####
####                                      Christopher Lomuntad  <chris@goautodial.com>  ####
####  License:          AGPLv2                                                          ####
############################################################################################
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
<?php
 
    if(!empty($acct_email)){
        echo "var freshdeskaccount = '$acct_email';";
    }else{
        echo 'var freshdeskaccount = "";';
    }

?>
$(document).ready(function()
	{
		$("#table_submitted_tickets").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
		$('#table_submitted_tickets').fadeOut("slow").load('<? echo $base; ?>index.php/go_support_ce/go_curl_view_tickets/'+freshdeskaccount).fadeIn("slow");


//		// Reloads every 5 seconds
//		var refreshId = setInterval(function()
//			{
//				$('#table_account').load('<? //echo $base; ?>index.php/go_site/go_account_check_info/accnt_num');
//				$('#table_info').load('<? //echo $base; ?>index.php/go_site/go_account_check_info/accnt_info');
//			}, 5000);
//
//		// Reloads every 3 minutes
//		var refreshId = setInterval(function()
//			{
//				$('#table_num_seats').load('<? //echo $base; ?>index.php/go_site/go_account_get_num_seats');
//				$('#table_agents').load('<? //echo $base; ?>index.php/go_site/go_account_get_logins/list_agents');
//				$('#table_phones').load('<? //echo $base; ?>index.php/go_site/go_account_get_logins/list_phones');
//			}, 180000);
//
//		// Reloads every 5 minutes
//		var refreshId = setInterval(function()
//			{
//				$('#table_balance').load('<? //echo $base; ?>index.php/go_site/go_account_check_balance');
//			}, 300000);
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
	$('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...
	  	$('#fade , .popup_block').fadeOut(function() {
			$('#fade, a.close').remove();  
	
        
                
        }); //fade them both out
			

                                                        history.back(0);	
                        });
	
});







</script>
