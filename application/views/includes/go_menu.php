<?php
########################################################################################################
####  Name:             	go_menu.php            	                    	    		    ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$base = base_url();
//$das = "wp-has-current-submenu";
$users_level = $this->session->userdata('users_level');
$hideMenu = ($this->config->item('VARSERVTYPE') == "private") ? "display:none;" : "";
$menuLast = ($this->config->item('VARSERVTYPE') == "private") ? "menu-top-last" : "";
$permissions = $this->commonhelper->getPermissions("multi-tenant",$this->session->userdata("user_group"));
?>
<script>
$(function()
{
	$('#menu-dialer').hover(function(e)
	{
// 		var offset = $(this).offset();
		e.stopPropagation();
		$("#tiptip_holder").hide();
		$('.go_analytics_menu').hide();
		$('.go_system_menu').hide();
// 		$('#go_admin_menu').css('left',offset.left+30);
// 		$('#go_admin_menu').css('top',offset.top);
// 		$('.go_admin_menu').slideDown();
        var $pos = $(this).offset();
		$('.go_admin_menu').slideDown(50).offset({top:$pos.top,left:$pos.left + 30});
	},function()
	{
        var $pos = $(this).offset();
		$('.go_admin_menu').offset({top:$pos.top,left:$pos.left + 30}).hide();
// 		$('.go_admin_menu').hide();
	});

	$('.go_admin_menu').hover(function()
	{
		$(this).show();
	},
	function()
	{
		//nothing to do
	});
	
	$('#menu-reports').hover(function(e)
	{
// 		var offset = $(this).offset();
		e.stopPropagation();
		$("#tiptip_holder").hide();
		$('.go_admin_menu').hide();
		$('.go_system_menu').hide();
// 		$('#go_analytics_menu').css('left',offset.left+30);
// 		$('#go_analytics_menu').css('top',offset.top);
// 		$('.go_analytics_menu').slideDown();
        var $pos = $(this).offset();
		$('.go_analytics_menu').slideDown(50).offset({top:$pos.top,left:$pos.left + 30});
	},function()
	{
        var $pos = $(this).offset();
		$('.go_analytics_menu').offset({top:$pos.top,left:$pos.left + 30}).hide();
// 		$('.go_analytics_menu').hide();
	});

	$('.go_analytics_menu').hover(function()
	{
		$(this).show();
	},
	function()
	{
		//nothing to do
	});
	
	$('#menu-admin').hover(function(e)
	{
// 		var offset = $(this).offset();
		e.stopPropagation();
		$("#tiptip_holder").hide();
		$('.go_admin_menu').hide();
		$('.go_analytics_menu').hide();
// 		$('#go_analytics_menu').css('left',offset.left+30);
// 		$('#go_analytics_menu').css('top',offset.top);
// 		$('.go_analytics_menu').slideDown();
        var $pos = $(this).offset();
		$('.go_system_menu').slideDown(50).offset({top:$pos.top,left:$pos.left + 30});
	},function()
	{
        var $pos = $(this).offset();
		$('.go_system_menu').offset({top:$pos.top,left:$pos.left + 30}).hide();
// 		$('.go_analytics_menu').hide();
	});

	$('.go_system_menu').hover(function()
	{
		$(this).show();
	},
	function()
	{
		//nothing to do
	});
	
	$(document).mouseup(function (e)
	{
		var content = $('#go_admin_menu,#go_analytics_menu,#go_system_menu');
		if (content.has(e.target).length === 0)
		{
			content.slideUp('fast');
			content.hide();
		}
	});
	
	$('li.go_admin_submenu,li.go_analytics_submenu,li.go_system_submenu').hover(function()
	{
		$(this).css('background-color','#ccc');
	},function()
	{
		$(this).css('background-color','#fff');
	});
	
	$('li.go_admin_submenu,li.go_analytics_submenu,li.go_system_submenu').click(function()
	{
		var submenuLink = $(this).attr('id');
		$('#go_analytics_menu').hide();
		$('#go_admin_menu').hide();
		$('#go_system_menu').hide();
		window.location.href = "<? echo $base; ?>"+submenuLink;
	});

// 	$('.toolTip').tipTip();
});
</script>
<style type="text/css">
.go_admin_menu,.go_analytics_menu,.go_system_menu{
	z-index:999999;
	position:fixed;
	top:188px;
	border:#CCC 1px solid;
	background-color:#FFF;
	display:none;
	cursor:pointer;
	-webkit-border-top-right-radius: 5px;
	-moz-border-radius-topright: 5px;
	border-top-right-radius: 5px;
}

#go_admin_menu ul,#go_analytics_menu ul,#go_system_menu ul{
	list-style-type:none;
	padding: 1px;
	margin: 0px;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.go_admin_submenu,.go_analytics_submenu,.go_header_submenu,.go_system_submenu{
	padding: 3px 10px 3px 5px;
	margin: 0px;
	font-size:12px;
	line-height:16px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}

.go_admin_submenu:first-child,.go_analytics_submenu:first-child,.go_header_submenu:first-child,.go_system_submenu:first-child{
	-webkit-border-top-right-radius: 5px;
	-moz-border-radius-topright: 5px;
	border-top-right-radius: 5px;
}

.go_header_submenu{
	color: #fff;
	text-shadow: 0 0 2px #000;
	background-color: rgb(25,25,25);
	background-color: rgba(25,25,25,0.92);
	background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(transparent), to(#000));
	box-shadow: 0 0 3px #555;
	-webkit-box-shadow: 0 0 3px #555;
	-moz-box-shadow: 0 0 3px #555;
}
</style>

<div id="wpbody">
<div id="middle">
	
<ul id="adminmenu">

<!-- DASHBOARD MENU -->

	<? echo $foldlink; ?>
	<li class="wp-first-item wp-has-submenu <? echo $das; ?> menu-top menu-top-first menu-icon-dashboard menu-top-first" id="menu-dashboard" title="<? echo $this->lang->line("go_dashboard"); ?>">
	<div class="wp-menu-image"><a href="<?=$base?>dashboard"><br></a></div><div class="wp-menu-toggle"><br></div>
        <a href="../go_site/go_dashboard" class="wp-first-item wp-has-submenu wp-has-current-submenu menu-top menu-top-first menu-icon-dashboard" tabindex="1"><? echo $this->lang->line("go_dashboard"); ?></a>
	<div class="wp-submenu"><div class="wp-submenu-head"><? echo $this->lang->line("go_dashboard"); ?></div>
		<ul>
		<li><a href="" tabindex="1"><? echo $this->lang->line("go_updates"); ?> <span class="update-plugins count-2" title="<? echo $this->lang->line("go_1_gad_plugin_update"); ?>"><span class="update-count">2</span></span></a></li>
		<li><a href="" tabindex="1"><? echo $this->lang->line("go_site_map"); ?></a></li>
		<li><a href="" tabindex="1"><? echo $this->lang->line("go_help"); ?></a></li>
		</ul>
	</div>
	</li>

  	<li class="wp-has-submenu <? echo $adm; ?> open-if-no-js menu-top menu-icon-dialer " id="menu-dialer">
	<div class="wp-menu-image"><a href="<? echo $base; ?>telephony"><br></a></div><div class="wp-menu-toggle"><br></div>
	<a class="wp-has-submenu  menu-top menu-icon-media menu-top-first" tabindex="1"><? echo $this->lang->line("go_telephony_admin"); ?></a>
	<div class="wp-submenu"><div class="wp-submenu-head"><? echo $this->lang->line("go_dialer"); ?></div>
		<ul>
		<li class="wp-first-item">   <? echo $this->lang->line("go_users"); ?></li>
		<li><a href="" tabindex="1"><? echo $this->lang->line("go_groups"); ?></a></li>
		<li><a href="" tabindex="1"><? echo $this->lang->line("go_campaigns"); ?></a></li>
		<li><a href="" tabindex="1"><? echo $this->lang->line("go_leads"); ?></a></li>
		<li><a href="" tabindex="1"><? echo $this->lang->line("go_inbound"); ?></a></li>
		</ul>
	</div>
	</li>
      
	<?php if ($users_level > 8) { ?>
	<li class="wp-has-submenu <? echo $sys; ?> open-if-no-js menu-top menu-icon-admin" id="menu-admin">
	<div class="wp-menu-image"><a href="<? echo $base; ?>adminsettings"><br></a></div><div class="wp-menu-toggle"><br></div>
	<a href="" class="wp-has-submenu menu-top menu-icon-admin" tabindex="1"><? echo $this->lang->line("go_system_settings"); ?></a>
	<div class="wp-submenu"><div class="wp-submenu-head"><? echo $this->lang->line("go_system_settings"); ?></div>
		<ul>
		<li class="wp-first-item"><a href="" class="wp-first-item" tabindex="1"><? echo $this->lang->line("go_telephony"); ?></a></li>
		<li><a href="" tabindex="1"><? echo $this->lang->line("go_database"); ?></a></li>
                <li><a href="" tabindex="1"><? echo $this->lang->line("go_web"); ?></a></li>
		</ul>
	</div>
	</li>
    <?php } ?>
	
	<li class="wp-has-submenu <? echo $rep; ?> menu-top <?=$menuLast ?> menu-icon-reports" id="menu-reports">
	<div class="wp-menu-image"><a href="<? echo $base; ?>reports"><br></a></div><div class="wp-menu-toggle"><br></div>
	<a class="wp-has-submenu menu-top menu-icon-reports" tabindex="1"><? echo $this->lang->line("go_reports_analytics"); ?></a>
	<div class="wp-submenu"><div class="wp-submenu-head"><? echo $this->lang->line("go_reports_analytics"); ?></div>
		<ul>
		<li class="wp-first-item"><a href="" class="wp-first-item" tabindex="1"><? echo $this->lang->line("go_telephony"); ?></a></li>
		<li><a href="" tabindex="1"><? echo $this->lang->line("go_database"); ?></a></li>
                <li><a href="" tabindex="1"><? echo $this->lang->line("go_web"); ?></a></li>
		</ul>
	</div>
	</li>
	
	<li class="wp-has-submenu <? echo $rec; ?> menu-top menu-icon-recordings" id="menu-links" style="display:none;" title="<? echo $this->lang->line("go_recordings"); ?>">
	<div class="wp-menu-image"><a href="<?=$base?>search"><br></a></div><div class="wp-menu-toggle"><br></div>
	<a href="" class="wp-has-submenu menu-top menu-icon-recordings" tabindex="1"><? echo $this->lang->line("go_recordings"); ?></a>
	<div class="wp-submenu"><div class="wp-submenu-head"><? echo $this->lang->line("go_recordings"); ?> </div>
		<ul>
		<li class="wp-first-item"><a href="" class="wp-first-item" tabindex="1"><? echo $this->lang->line("go_inbound"); ?></a></li>
		<li><a href="" tabindex="1"><? echo $this->lang->line("go_outbound"); ?></a></li>
		<li><a href="" tabindex="1"><? echo $this->lang->line("go_statistics"); ?></a></li>
		<li><a href="" tabindex="1"><? echo $this->lang->line("go_analytics"); ?></a></li>
		</ul>
	</div>
	</li>
      
      
	<li class="wp-has-submenu <? //echo $sys; ?> open-if-no-js menu-top menu-icon-settings" id="menu-settings" style="display:none;">
	<div class="wp-menu-image"><a href="<? echo $base; ?>systems"><br></a></div><div class="wp-menu-toggle"><br></div>
	<a href="" class="wp-has-submenu menu-top menu-icon-settings" tabindex="1"><? echo $this->lang->line("go_system_settings"); ?></a>
	<div class="wp-submenu"><div class="wp-submenu-head"><? echo $this->lang->line("go_system_settings"); ?></div>
		<ul>
		<li class="wp-first-item"><a href="" class="wp-first-item" tabindex="1"><? echo $this->lang->line("go_telephony"); ?></a></li>
		<li><a href="" tabindex="1"><? echo $this->lang->line("go_database"); ?></a></li>
                <li><a href="" tabindex="1"><? echo $this->lang->line("go_web"); ?></a></li>
		</ul>
	</div>
	</li>

	<li class="wp-has-submenu <? //echo $doc; ?> open-if-no-js menu-top menu-icon-doc" id="menu-doc" style="display:none;">
	<div class="wp-menu-image"><a href="<? echo $base; ?>documentations"><br></a></div><div class="wp-menu-toggle"><br></div>
	<a href="" class="wp-has-submenu menu-top menu-icon-doc" tabindex="1"><? echo $this->lang->line("go_documentations"); ?></a>
	<div class="wp-submenu"><div class="wp-submenu-head"><? echo $this->lang->line("go_documentations"); ?></div>
		<ul>
		<li class="wp-first-item"><a href="" class="wp-first-item" tabindex="1"><? echo $this->lang->line("go_telephony"); ?></a></li>
		<li><a href="" tabindex="1"><? echo $this->lang->line("go_database"); ?></a></li>
                <li><a href="" tabindex="1"><? echo $this->lang->line("go_web"); ?></a></li>
		</ul>
	</div>
	</li>	
	
	<li class="wp-has-submenu <? //echo $sup; ?> open-if-no-js menu-top menu-top-last menu-icon-support toolTip" title="<? echo $this->lang->line("go_support"); ?>" id="menu-support" style="<?=$hideMenu ?>">
	 <div class="wp-menu-image"><a href="http://goautodial.org/projects/goautodialce/boards" target="_new"><br></a></div><div class="wp-menu-toggle"><br></div> 
	<!--<div class="wp-menu-image"><a href="<?=$base?>support" ><br></a></div><div class="wp-menu-toggle"><br></div>-->
	<a href="" class="wp-has-submenu menu-top menu-icon-support" tabindex="1"><? echo $this->lang->line("go_support"); ?></a>
	<div class="wp-submenu"><div class="wp-submenu-head"><? echo $this->lang->line("go_support"); ?></div>
		<ul>
		<li class="wp-first-item"><a href="" class="wp-first-item" tabindex="1"><? echo $this->lang->line("go_telephony"); ?></a></li>
		<li><a href="" tabindex="1"><? echo $this->lang->line("go_database"); ?></a></li>
                <li><a href="" tabindex="1"><? echo $this->lang->line("go_web"); ?></a></li>
		</ul>
	</div>
	</li>
	
	<li class="wp-has-submenu <? //echo $agn; ?> open-if-no-js menu-top menu-top-last menu-icon-agent" id="menu-agent" style="display:none;">
	<div class="wp-menu-image"><a href="<? echo $base; ?>agent"><br></a></div><div class="wp-menu-toggle"><br></div>
	<a href="" class="wp-has-submenu menu-top menu-icon-agent menu-top-last" tabindex="1"><? echo $this->lang->line("go_agent"); ?></a>
	<div class="wp-submenu"><div class="wp-submenu-head"><? echo $this->lang->line("go_agent"); ?></div>
		<ul>
		<li class="wp-first-item"><a href="" class="wp-first-item" tabindex="1"><? echo $this->lang->line("go_telephony"); ?></a></li>
		<li><a href="" tabindex="1"><? echo $this->lang->line("go_database"); ?></a></li>
                <li><a href="" tabindex="1"><? echo $this->lang->line("go_web"); ?></a></li>
		</ul>
	</div>
	</li>	
        
        </li>        
</ul>

</div>


<!-- Administration Sub Menu -->
<div id='go_admin_menu' class='go_admin_menu'>
<ul>
<li class="go_header_submenu"><? echo $this->lang->line("go_telephony"); ?></li>
<li class="go_admin_submenu" title="<? echo $this->lang->line("go_campaigns"); ?>" id="campaigns">&raquo; <? echo $this->lang->line("go_campaigns"); ?></li>
<li class="go_admin_submenu" title="<? echo $this->lang->line("go_inbound"); ?>" id="ingroups">&raquo; <? echo $this->lang->line("go_inbound"); ?></li>
<li class="go_admin_submenu" title="<? echo $this->lang->line("go_lists"); ?>" id="go_list">&raquo; <? echo $this->lang->line("go_lists"); ?></li>
<li class="go_admin_submenu" title="<? echo $this->lang->line("go_moh"); ?>" id="moh">&raquo; <? echo $this->lang->line("go_moh"); ?></li>
<li class="go_admin_submenu" title="<? echo $this->lang->line("go_scripts"); ?>" id="scripts">&raquo; <? echo $this->lang->line("go_scripts"); ?></li>
<li class="go_admin_submenu" title="<? echo $this->lang->line("go_users"); ?>" id="users">&raquo; <? echo $this->lang->line("go_users"); ?></li>
<li class="go_admin_submenu" title="<? echo $this->lang->line("go_voice_files"); ?>" id="audiostore">&raquo; <? echo $this->lang->line("go_voice_files"); ?></li>
</ul>
</div>

<!-- Reports Sub Menu -->
<div id='go_analytics_menu' class='go_analytics_menu'>
<ul>
<li class="go_header_submenu"><? echo $this->lang->line("go_call_reports"); ?></li>
<li class="go_analytics_submenu" title="<? echo $this->lang->line("go_reports"); ?> &amp; <? echo $this->lang->line("go_analytics"); ?>" id="reports">&raquo; <? echo $this->lang->line("go_reports"); ?> &amp; <? echo $this->lang->line("go_analytics"); ?></li>
<!--<li class="go_analytics_submenu" title="Call Costs &amp; History" id="callhistory">&raquo; Call Costs &amp; History</li>-->
</ul>
</div>

<!-- System Settings Sub Menu -->
<div id='go_system_menu' class='go_system_menu'>
<ul>
<li class="go_header_submenu"><? echo $this->lang->line("go_admin_settings"); ?></li>
<li class="go_system_submenu" title="<? echo $this->lang->line("go_admin_logs"); ?>" id="logs">&raquo; <? echo $this->lang->line("go_admin_logs"); ?></li>
<li class="go_system_submenu" title="<? echo $this->lang->line("go_call_times"); ?>" id="calltimes">&raquo; <? echo $this->lang->line("go_call_times"); ?></li>
<?php if (!$this->commonhelper->checkIfTenant($this->session->userdata('user_group')) && $this->session->userdata('user_group') === "ADMIN") { ?>
<li class="go_system_submenu" title="<? echo $this->lang->line("go_carrier_banner"); ?>" id="carriers">&raquo; <? echo $this->lang->line("go_carrier_banner"); ?></li>
<?php } ?>
<li class="go_system_submenu" title="<? echo $this->lang->line("go_phone_banner"); ?>" id="phones">&raquo; <? echo $this->lang->line("go_phone_banner"); ?></li>
<?php if (!$this->commonhelper->checkIfTenant($this->session->userdata('user_group')) && $this->session->userdata('user_group') === "ADMIN") { ?>
<li class="go_system_submenu" title="<? echo $this->lang->line("go_server_banner"); ?>" id="servers">&raquo; <? echo $this->lang->line("go_server_banner"); ?></li>
<li class="go_system_submenu" title="<? echo $this->lang->line("go_system_settings"); ?>" id="settings">&raquo; <? echo $this->lang->line("go_system_settings"); ?></li>
<li class="go_system_submenu" title="<? echo $this->lang->line("go_user_groups"); ?>" id="usergroups">&raquo; <? echo $this->lang->line("go_user_groups"); ?></li>
<?php } ?>
<li class="go_system_submenu" title="<? echo $this->lang->line("go_voicemails"); ?>" id="voicemails">&raquo; <? echo $this->lang->line("go_voicemails"); ?></li>
</ul>
</div>



  <script>
$(document).ready(function()
{

//    $("li").click(function () {
//	
//	
//	var match = $(this).attr('id').match(/menu-support/);
//	
//	if(match){
//
//      }
//      else{
//		$(this).addClass("current");
//
//      }
//
//      
//    });


    }); 

  
  
  
  
  
 
  </script>
