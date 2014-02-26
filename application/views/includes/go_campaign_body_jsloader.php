<?php
########################################################################################################
####  Name:             	go_campaign_body_jsloader.php   	                            ####
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
//<![CDATA[
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
//]]>
</script>
<script type="text/javascript" src="<? echo $base; ?>js/go_fload_scripts.js"></script>
<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>


<script>
$(document).ready(function()
{
	$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
	$('#table_reports').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list').fadeIn("slow");


	if (!$.browser.opera) {

	    $('select.select').each(function(){
		var title = $(this).attr('title');
		if( $('option:selected', this).val() != ''  ) title = $('option:selected',this).text();
		$(this)
		    .css({'z-index':10,'opacity':0,'-khtml-appearance':'none'})
		    .after('<span class="select">' + title + '</span>')
		    .change(function(){
			val = $('option:selected',this).text();
			$(this).next().text(val);
			})
	    });

	};

});
</script>