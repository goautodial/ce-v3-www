<?php
####################################################################################################
####  Name:             	go_widget_show_me_more.php                                          ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Written by:           Chris Lomuntad                                                      ####
####  License:          	AGPLv2                                                              ####
####################################################################################################
$base = base_url();
$hideThis = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "display:none;" : "";
?>
<style>
.odd {background-color: #EFFBEF}
.even {background-color: #E0F8E0}

#showPhonesPass,#showUsersPass{
	font-family: monospace;
}
#showPhonesPass:hover,#showUsersPass:hover{
	color:red;
}
</style>
<script>
$(function()
{
    $('.toolTip').tipTip();

	$('.disableLink').click(function(e)
	{
		e.preventDefault();
	});
	
	$('#showPhonesPass').click(function()
	{
		if ($(this).text() == '[+]')
		{
			$('.showPhonesPass').show();
			$('.hiddenPhonesPass').hide();
			$(this).text('[-]');
		}
		else
		{
			$('.showPhonesPass').hide();
			$('.hiddenPhonesPass').show();
			$(this).text('[+]');
		}
	});
	
	$('#showUsersPass').click(function()
	{
		if ($(this).text() == '[+]')
		{
			$('.showUsersPass').show();
			$('.hiddenUsersPass').hide();
			$(this).text('[-]');
		}
		else
		{
			$('.showUsersPass').hide();
			$('.hiddenUsersPass').show();
			$(this).text('[+]');
		}
	});
	
    $('#showCampTable').tablePagination({rowsPerPage: 20, optionsForRows: [20, 50, 100, "ALL"]});
    $("#showCampTable").tablesorter({headers: {1: {sorter:"text"}}, widgets: ['zebra']});
    $('#showUsersTable').tablePagination({rowsPerPage: 20, optionsForRows: [20, 50, 100, "ALL"]});
    $("#showUsersTable").tablesorter({headers: {0: {sorter:"text"}}, widgets: ['zebra']});
    $('#showPhonesTable').tablePagination({rowsPerPage: 20, optionsForRows: [20, 50, 100, "ALL"]});
    $("#showPhonesTable").tablesorter({headers: {1: {sorter:false}}, widgets: ['zebra']});
	
	$('#selectTenant').change(function() {
		var tenantid = $(this).val();
		$("#overlayContent").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_site/show_me_more/<?=$show_me_more['type'] ?>/'+tenantid).fadeIn("slow");
	});
});
</script>
<?php
if ($show_me_more['type']=="campaign") {
	$usergroups["---ALL---"] = "- - - SHOW ALL CAMPAIGNS - - -";
	ksort($usergroups);
	$showHTML = "<div style='float:right;$hideThis'>User Group: ".form_dropdown('selectTenant',$usergroups,$groupSelected,'id="selectTenant"')."</div><div style='font-size:16px;font-weight:bold;color:#000;width:100%;text-align:left;'>CAMPAIGN RESOURCES</div><br />\n";
	$showHTML .="<table id='showCampTable' class='tablesorter' border='0' cellpadding='1' cellspacing='0' style='width:100%;'>";
	$showHTML .="<thead>\n";
	$showHTML .="	<tr style=\"font-weight:bold;text-align:left;\">\n";
	$showHTML .="		<th style=\"width:12%;cursor:pointer;\">&nbsp;&nbsp;CAMPAIGN ID</th>\n";
	$showHTML .="		<th style=\"cursor:pointer;\">&nbsp;&nbsp;CAMPAIGN NAME</th>\n";
	$showHTML .="		<th style=\"cursor:pointer;\">&nbsp;&nbsp;LEADS ON HOPPER</th>\n";
	$showHTML .="		<th style=\"cursor:pointer;\">&nbsp;&nbsp;CALL TIMES</th>\n";
	$showHTML .="		<th style=\"cursor:pointer;$hideThis\">&nbsp;&nbsp;TENANT ID</th>\n";
	$showHTML .="	</tr>\n";
	$showHTML .="</thead>\n";
	$showHTML .="<tbody>\n";
	if (count($show_me_more['list']) > 0)
	{
		foreach ($show_me_more['list'] as $row) {
		   $usergroup = ($row->user_group == "---ALL---") ? "ALL USER GROUP" : $row->user_group;
		   $showHTML .= "<tr>\n";
		   $showHTML .= "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;&nbsp;<a style='cursor:pointer;color:#7F7F7F;' class='disableLink' onclick=\"modify('{$row->campaign_id}')\">{$row->campaign_id}</a></td>\n";
		   $showHTML .= "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;&nbsp;{$row->campaign_name}</td>\n";
		   $showHTML .= "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;&nbsp;{$row->mycnt}</td>\n";
		   $showHTML .= "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;&nbsp;{$row->local_call_time}</td>\n";
		   $showHTML .= "<td style='border-top:#D0D0D0 dashed 1px;$hideThis'>&nbsp;&nbsp;{$usergroup}</td>\n";
		   $showHTML .= "</tr>\n";
		}
	} else {
		   $showHTML .= "<tr>\n";
		   $showHTML .= "<td colspan='5' style='border-top:#D0D0D0 dashed 1px;text-align:center;color:red;font-weight:bold;'>No record(s) found.</td>\n";
		   $showHTML .= "</tr>\n";
	}
	$showHTML .="</tbody>\n";
	$showHTML .="</table>";
	
	echo $showHTML;
	?>
	<br />
	<div>* List shows only the campaign that are active, within dialing hours and is <strong>< 100</strong> leads on the hopper.</div>
<?php
} else {
	$usergroups["---ALL---"] = "- - - SHOW ALL LOGINS - - -";
	ksort($usergroups);
	$showHTML = "<div style='float:right;$hideThis'>User Group: ".form_dropdown('selectTenant',$usergroups,$groupSelected,'id="selectTenant"')."</div><div style='font-size:16px;font-weight:bold;color:#000;width:100%;text-align:left;'>AGENTS & PHONES</div><br />\n";
	$showHTML .="<table border='0' cellpadding='0' cellspacing='0' style='width:100%'><tr><td style='padding-right:10px;vertical-align:top;'>\n";
	$showHTML .="<table id='showUsersTable' class='tablesorter' border='0' cellpadding='1' cellspacing='0' style='width:100%;'>";
	$showHTML .="<thead>\n";
	$showHTML .="	<tr style=\"font-weight:bold;text-align:left;\">\n";
	$showHTML .="		<th style=\"width:12%;cursor:pointer;\">&nbsp;&nbsp;NAME</th>\n";
	$showHTML .="		<th style=\"cursor:pointer;white-space:nowrap;\">&nbsp;&nbsp;PASSWORD <span id='showUsersPass' class='toolTip' title='Show/Hide Users Passwords' style='cursor:pointer;font-size:10px;'>[+]</span></th>\n";
	$showHTML .="		<th style=\"cursor:pointer;\">&nbsp;&nbsp;STATUS</th>\n";
	$showHTML .="		<th style=\"cursor:pointer;$hideThis\">&nbsp;&nbsp;TENANT ID</th>\n";
	$showHTML .="	</tr>\n";
	$showHTML .="</thead>\n";
	$showHTML .="<tbody>\n";
	if (count($show_me_more['list']['users']) > 0)
	{
		foreach ($show_me_more['list']['users'] as $row) {
		   $usergroup = ($row->user_group == "---ALL---") ? "ALL USER GROUP" : $row->user_group;
		   $isActive  = ($row->active == "Y") ? "<span style='color:green;'>ACTIVE</span>" : "<span style='color:red;'>INACTIVE</span>";
		   $showHTML .= "<tr>\n";
		   $showHTML .= "<td style='border-top:#D0D0D0 dashed 1px;white-space:nowrap;'>&nbsp;&nbsp;<a style='cursor:pointer;color:#7F7F7F;' class='disableLink toolTip' title=\"{$row->user} - {$row->full_name}\" onclick=\"getAgentInfo('{$row->user}')\">".substr($row->full_name,0,20)."</a></td>\n";
		   $showHTML .= "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;&nbsp;<span class='hiddenUsersPass'>**********</span><span class='showUsersPass' style='display:none;'>{$row->pass}</span></td>\n";
		   $showHTML .= "<td style='border-top:#D0D0D0 dashed 1px;font-weight:bold;'>&nbsp;&nbsp;{$isActive}</td>\n";
		   $showHTML .= "<td style='border-top:#D0D0D0 dashed 1px;$hideThis'>&nbsp;&nbsp;{$usergroup}</td>\n";
		   $showHTML .= "</tr>\n";
		}
	} else {
		   $showHTML .= "<tr>\n";
		   $showHTML .= "<td colspan='4' style='border-top:#D0D0D0 dashed 1px;text-align:center;color:red;font-weight:bold;'>No user record(s) found.</td>\n";
		   $showHTML .= "</tr>\n";
	}
	$showHTML .="</tbody>\n";
	$showHTML .="</table>\n";
	$showHTML .="</td><td style='padding-left:10px;vertical-align:top;'>\n";
	$showHTML .="<table id='showPhonesTable' class='tablesorter' border='0' cellpadding='1' cellspacing='0' style='width:100%;'>";
	$showHTML .="<thead>\n";
	$showHTML .="	<tr style=\"font-weight:bold;text-align:left;\">\n";
	$showHTML .="		<th style=\"width:12%;cursor:pointer;\">&nbsp;&nbsp;LOGIN</th>\n";
	$showHTML .="		<th style=\"cursor:pointer;white-space:nowrap;\">&nbsp;&nbsp;PASSWORD <span id='showPhonesPass' class='toolTip' title='Show/Hide Phone Passwords' style='cursor:pointer;font-size:10px;'>[+]</span></th>\n";
	$showHTML .="		<th style=\"cursor:pointer;\">&nbsp;&nbsp;STATUS</th>\n";
	$showHTML .="		<th style=\"cursor:pointer;$hideThis\">&nbsp;&nbsp;TENANT ID</th>\n";
	$showHTML .="	</tr>\n";
	$showHTML .="</thead>\n";
	$showHTML .="<tbody>\n";
	if (count($show_me_more['list']['phones']) > 0)
	{
		foreach ($show_me_more['list']['phones'] as $row) {
		   $usergroup = ($row->user_group == "---ALL---") ? "ALL USER GROUP" : $row->user_group;
		   $isActive  = ($row->active == "Y") ? "<span style='color:green;'>ACTIVE</span>" : "<span style='color:red;'>INACTIVE</span>";
		   $showHTML .= "<tr>\n";
		   $showHTML .= "<td style='border-top:#D0D0D0 dashed 1px;white-space:nowrap;'>&nbsp;&nbsp;<a style='cursor:pointer;color:#7F7F7F;' class='disableLink'>{$row->login}</a></td>\n";
		   $showHTML .= "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;&nbsp;<span class='hiddenPhonesPass'>**********</span><span class='showPhonesPass' style='display:none;'>{$row->pass}</span></td>\n";
		   $showHTML .= "<td style='border-top:#D0D0D0 dashed 1px;font-weight:bold;'>&nbsp;&nbsp;{$isActive}</td>\n";
		   $showHTML .= "<td style='border-top:#D0D0D0 dashed 1px;$hideThis'>&nbsp;&nbsp;{$usergroup}</td>\n";
		   $showHTML .= "</tr>\n";
		}
	} else {
		   $showHTML .= "<tr>\n";
		   $showHTML .= "<td colspan='4' style='border-top:#D0D0D0 dashed 1px;text-align:center;color:red;font-weight:bold;'>No phone record(s) found.</td>\n";
		   $showHTML .= "</tr>\n";
	}
	$showHTML .="</tbody>\n";
	$showHTML .="</table>\n";
	$showHTML .="</td></tr></table>\n";
	
	echo $showHTML;
	?>
	<br />
<?php
}
?>