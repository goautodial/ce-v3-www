<?php
####################################################################################################
####  Name:             	go_usergroup_view.php                                               ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Originated by:        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Christopher Lomuntad                                         	    ####
####  License:          	AGPLv2                                                              ####
####################################################################################################
$base = base_url();

if (! $isAdvance)
	$isAdvance = 0;

$UGreports = 'ALL REPORTS, NONE, Real-Time Main Report, Real-Time Campaign Summary , Inbound Report, Inbound Service Level Report, Inbound Summary Hourly Report, Inbound Daily Report, Inbound DID Report, Inbound IVR Report, Outbound Calling Report, Outbound Summary Interval Report, Outbound IVR Report, Fronter - Closer Report, Lists Campaign Statuses Report, Campaign Status List Report, Export Calls Report , Export Leads Report , Agent Time Detail, Agent Status Detail, Agent Performance Detail, Team Performance Detail, Single Agent Daily , User Timeclock Report, User Group Timeclock Status Report, User Timeclock Detail Report , Server Performance Report, Administration Change Log, List Update Stats, User Stats, User Time Sheet, Download List, Dialer Inventory Report, Custom Reports Links, CallCard Search, Maximum System Stats, Maximum Stats Detail, Search Leads Logs';
?>
<script>
$(function()
{
	var isAdvance = <?php echo $isAdvance; ?>;
	if (isAdvance)
	{
		$('.advance_settings').show();
		$('#advance_link').html('[ - ADVANCE SETTINGS ]');
		$('#isAdvance').val('1');
	}
	
    $('.toolTip').tipTip();

	$('#advance_link').click(function()
	{
		if ($('.advance_settings').is(':hidden'))
		{
			$('.advance_settings').show();
			$('#advance_link').html('[ - ADVANCE SETTINGS ]');
			$('#isAdvance').val('1');
		} else {
			$('.advance_settings').hide();
			$('#advance_link').html('[ + ADVANCE SETTINGS ]');
			$('#isAdvance').val('0');
		}
	});

	$('#advance_link').hover(function()
	{
		$(this).css('color','#F00');
	},
	function()
	{
		$(this).css('color','#000');
	});
	
	$('#group_name').keydown(function(event)
	{
		$(this).css('border','solid 1px #999');
	});
	
	// Submit Settings
	$('#saveSettings').click(function()
	{
		var isEmpty = 0;
		if ($('#group_name').val() === "")
		{
			$('#group_name').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#aloading').html().match(/Not Available/))
		{
			alert("User Group Not Available.");
			isEmpty = 1;
		}
		
		if (!isEmpty)
		{
			//var items = $('#modifyUserGroup').serialize();

                        var items = '',$permissions={};
                        
                        $(".modify-usergroup").each(function(){

                            if($(this).is("input:checkbox")){
                                if($(this).prop("checked")){
                                     items += $(this).attr("name") + "=" + $(this).val() + "&"; 
                                }
                            }else{
                                items += $(this).attr("name") + "=" + $(this).val() + "&"; 
                            }
                        });

                         $(".permissions").each(function(){
                             var $container = {};
                             $(this).children("td:nth-child(2)").children("input.permission-box").each(function(){
                                  $container[$(this).attr("name")] = (($(this).prop("checked")===true)?$(this).val():"N");
                             });
                             if($(this).children("td:nth-child(1)").text() !== "Group Level:"){
                                 $permissions[$(this).children("td:nth-child(1)").text().toLowerCase().replace("&","").replace(":","").replace(/ /g,"")] = $container;
                             }
                        });

			$.post("<?=$base?>index.php/go_usergroup_ce/go_usergroup_wizard", { items: items, action: "modify_usergroup", permiso : JSON.stringify($permissions), group_level : $("#group_level").val() },
			function(data){
				if (data=="SUCCESS")
				{
					alert(data);
					
					$('#box').animate({'top':'-2550px'},500);
					$('#overlay').fadeOut('slow');
					
					location.reload();
				}
	
			});
		}
	});

        getpermissions(<?=$permission?>);
        
});
</script>
<style>
.buttons {
	color:#7A9E22;
	cursor:pointer;
}

.buttons:hover{
	font-weight:bold;
}
</style>
<?php
switch ($type)
{
	case "modify":
		break;
	
	default:
?>
<div align="center" style="font-weight:bold; color:#333; font-size:16px;">MODIFY USER GROUP: <?php echo "{$group_info->user_group}"; ?></div>
<br />
<form id="modifyUserGroup" method="POST">
<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:98%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
		<td style="text-align:right;width:30%;height:10px;">
		User Group:
		</td>
		<td>
		&nbsp;<span><?=$group_info->user_group ?></span>
		<?#=form_hidden('user_group',$group_info->user_group,'id="user_group" class="modify-usergroup"') ?>
                <?='<input type="hidden" name="user_group" value="'.$group_info->user_group.'" id="user_group" class="modify-usergroup">'?>
		<span id="aloading"></span>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:30%;height:10px;">
		Group Name:
		</td>
		<td>
		<?=form_input('group_name',$group_info->group_name,'id="group_name" maxlength="40" size="40" class="modify-usergroup"') ?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:30%;height:10px;">
		Force Timeclock Login:
		</td>
		<td>
		<?=form_dropdown('forced_timeclock_login',array('Y'=>'YES','N'=>'NO','ADMIN_EXEMPT'=>'ADMIN EXEMPT'),$group_info->forced_timeclock_login,'id="forced_timeclock_login" class="modify-usergroup"') ?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:30%;height:10px;">
		Shift Enforcement:
		</td>
		<td>
		<?=form_dropdown('shift_enforcement',array('OFF'=>'OFF','START'=>'START','ALL'=>'ALL','ADMIN_EXEMPT'=>'ADMIN EXEMPT'),$group_info->shift_enforcement,'id="shift_enforcement" class="modify-usergroup"') ?>
		</td>
	</tr>
        <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Group Level:</td>
                       <td>
                            <?php
                                  $levels = array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9);
                                  echo form_dropdown("group_level",$levels,null,"id='group_level'");
                            ?>
                       </td> 
        </tr>
        <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Dashboard:</td>
                       <td>
                            <?php
                                 echo form_checkbox('dashboard_todays_status',"Y",null,"id ='dashboard_todays_status' class='permission-box'")." Todays Status " .
                                      form_checkbox('dashboard_account_info',"Y",null,"id='dashboard_account_info' class='permission-box'") . " Account Info " . 
                                      form_checkbox('dashboard_agent_lead_status',"Y",null,"id='dashboard_agent_lead_status' class='permission-box'"). " Agent Lead Status ".
                                      form_checkbox('dashboard_server_settings',"Y",null,"id='dashboard_server_settings' class='permission-box'") . " Server Settings <br/>" .
                                      form_checkbox('dashboard_go_analytics',"Y",null,"id='dashboard_go_analytics' class='permission-box'") . " Go Analytics " .
                                      form_checkbox('dashboard_system_service',"Y",null,"id='dashboard_system_service' class='permission-box'") . " System Service " .
                                      form_checkbox('dashboard_cluster_status',"Y",null,"id='dashboard_cluster_status' class='permission-box'") . " Cluster Status ";
                            ?>
                       </td> 
        </tr>
        <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">User:</td>
                       <td>
                            <?php
                                 echo form_checkbox('user_create',"C",null,"id='user_create' class='permission-box'")." Create " .
                                      form_checkbox('user_read',"R",null,"id='user_read' class='permission-box'") . " Read " . 
                                      form_checkbox('user_update',"U",null,"id='user_update' class='permission-box'"). " Update ".
                                      form_checkbox('user_delete',"D",null,"id='user_delete' class='permission-box'") . " Delete ";

                            ?>
                       </td> 
        </tr>
        <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Campaign:</td>
                       <td>
                            <?php
                                 echo form_checkbox('campaign_create',"C",null,"id='campaign_create' class='permission-box'")." Create " .
                                      form_checkbox('campaign_read',"R",null,"id='campaign_read' class='permission-box'") . " Read " . 
                                      form_checkbox('campaign_update',"U",null,"id='campaign_update' class='permission-box'"). " Update ".
                                      form_checkbox('campaign_delete',"D",null,"id='campaign_delete' class='permission-box'") . " Delete ";
                            ?>
                       </td> 
        </tr>
        <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">List:</td>
                       <td>
                            <?php
                                 echo form_checkbox('list_create',"C",null,"id='list_create' class='permission-box'")." Create " .
                                      form_checkbox('list_read',"R",null,"id='list_read' class='permission-box'") . " Read " . 
                                      form_checkbox('list_update',"U",null,"id='list_update' class='permission-box'"). " Update ".
                                      form_checkbox('list_delete',"D",null,"id='list_delete' class='permission-box'") . " Delete ";
                            ?>
                       </td> 
        </tr>
        <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Custom Fields:</td>
                       <td>
                            <?php
                                 echo form_checkbox('customfields_create',"C",null,"id='customfields_create' class='permission-box'")." Create " .
                                      form_checkbox('customfields_read',"R",null,"id='customfields_read' class='permission-box'") . " Read " . 
                                      form_checkbox('customfields_update',"U",null,"id='customfields_update' class='permission-box'"). " Update ".
                                      form_checkbox('customfields_delete',"D",null,"id='customfields_delete' class='permission-box'") . " Delete ";
                            ?>
                       </td> 
        </tr>
        <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Load Leads:</td>
                       <td>
                            <?php
                                 echo form_checkbox('loadleads_read',"R",null,"id='loadleads_read' class='permission-box'") . " Read ";
                            ?>
                       </td> 
        </tr>
        <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Script:</td>
                       <td>
                            <?php
                                 echo form_checkbox('script_create',"C",null,"id='script_create' class='permission-box'")." Create " .
                                      form_checkbox('script_read',"R",null,"id='script_read' class='permission-box'") . " Read " . 
                                      form_checkbox('script_update',"U",null,"id='script_update' class='permission-box'"). " Update ".
                                      form_checkbox('script_delete',"D",null,"id='script_delete' class='permission-box'") . " Delete ";
                            ?>
                       </td> 
        </tr> 
        <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Inbound:</td>
                       <td>
                            <?php
                                 echo form_checkbox('inbound_create',"C",null,"id='inbound_create' class='permission-box'")." Create " .
                                      form_checkbox('inbound_read',"R",null,"id='inbound_read' class='permission-box'") . " Read " . 
                                      form_checkbox('inbound_update',"U",null,"id='inbound_update' class='permission-box'"). " Update ".
                                      form_checkbox('inbound_delete',"D",null,"id='inbound_delete' class='permission-box'") . " Delete ";
                            ?>
                       </td> 
        </tr>
        <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Voice Files:</td>
                       <td>
                            <?php
                                 echo form_checkbox('voicefile_upload',"C",null,"id='voicefile_upload' class='permission-box'")." Upload " .
                                      form_checkbox('voicefile_delete',"D",null,"id='voicefile_delete' class='permission-box'") . " Delete ";
                            ?>
                       </td> 
        </tr>
        <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Reports & Analytics:</td>
                       <td>
                            <?php
                                 echo form_checkbox('reportsanalytics_statistical_report',"Y",null,"id='reportsanalytics_statistical_report' class='permission-box'")." Statistical Report " .
                                      form_checkbox('reportsanalytics_agent_time_detail',"Y",null,"id='reportsanalytics_agent_time_detail' class='permission-box'") . " Agent Time Detail " . 
                                      form_checkbox('reportsanalytics_agent_performance_detail',"Y",null,"id='reportsanalytics_agent_performance_detail' class='permission-box'"). " Agent Performance Detail <br/>".
                                      form_checkbox('reportsanalytics_dial_status_summary',"Y",null,"id='reportsanalytics_dial_status_summary' class='permission-box'") . " Dial Status Summary  " .
                                      form_checkbox('reportsanalytics_sales_per_agent',"Y",null,"id='reportsanalytics_sales_per_agent' class='permission-box'") . " Sales Per Agent " .
                                      form_checkbox('reportsanalytics_sales_tracker',"Y",null,"id='reportsanalytics_sales_tracker' class='permission-box'") . " Sales Tracker <br/>" .
                                      form_checkbox('reportsanalytics_inbound_call_report',"Y",null,"id='reportsanalytics_inbound_call_report' class='permission-box'") . " Inbound Call Report " .
                                      form_checkbox('reportsanalytics_export_call_report',"Y",null,"id='reportsanalytics_export_call_report' class='permission-box'") . " Export Call Report " .
                                      form_checkbox('reportsanalytics_dashboard',"Y",null,"id='reportsanalytics_dashboard' class='permission-box'") . " Dashboard <br/>" .
                                      form_checkbox('reportsanalytics_advance_script',"Y",null,"id='reportsanalytics_advance_script' class='permission-box'") . " Advance Script " 
                                      ;
                            ?>
                       </td> 
        </tr>
        <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Recording:</td>
                       <td>
                            <?php
                                  echo  form_checkbox('recordings_display',"Y",null,"id='recordings_display' class='permission-box'") . " Allowed Recording View ";
                            ?>
                       </td> 
        </tr>
        <tr class="permissions">
                       <td style="text-align:right;width:15%;height:10px;font-weight:bold;">Support:</td>
                       <td>
                            <?php
                                  echo  form_checkbox('support_display',"Y",null,"id='support_display' class='permission-box'") . " Allowed Support ";
                            ?>
                       </td> 
        </tr>


	<tr class="advance_settings">
		<td style="text-align:right;width:30%;height:10px;">
		Allowed Campaigns:
		</td>
		<td>
		<?php
		$checked = FALSE;
		if (preg_match("/-ALL-CAMPAIGNS-/",$group_info->allowed_campaigns))
			$checked = TRUE;
			echo form_checkbox('allowed_campaigns[]',"-ALL-CAMPAIGNS-", $checked,' class="modify-usergroup"')." <b>ALL-CAMPAIGNS - USERS CAN VIEW ANY CAMPAIGN</b><br />\n";
		
		foreach ($campaign_list as $list)
		{
			$checked = FALSE;
			if (preg_match("/ {$list->campaign_id}/",$group_info->allowed_campaigns))
				$checked = TRUE;
				
			echo form_checkbox('allowed_campaigns[]',$list->campaign_id, $checked, ' class="modify-usergroup"')." {$list->campaign_id} - {$list->campaign_name}<br />\n";
		}
		?>
		</td>
	</tr>
	<tr style="display: none;">
		<td style="font-size:6px;">&nbsp;</td><td style="font-size:6px;">&nbsp;</td>
	</tr>
	<tr style="display: none;">
		<td style="text-align:right;width:30%;height:10px;">
		Group Shifts:
		</td>
		<td>
		<?php
		foreach ($shift_list as $list)
		{
			$checked = FALSE;
			if (preg_match("/ {$list->shift_id}/",$group_info->group_shifts))
				$checked = TRUE;
				
			echo form_checkbox('group_shifts[]',$list->shift_id, $checked, ' class="modify-usergroup"')." {$list->shift_id} - {$list->shift_name}<br />\n";
		}
		?>
		</td>
	</tr>
	<tr>
		<td style="font-size:6px;">&nbsp;</td><td style="font-size:6px;">&nbsp;</td>
	</tr>
	<tr class="advance_settings">
		<td style="text-align:right;width:30%;height:10px;">
		Agent Status Viewable Groups:
		</td>
		<td>
		<?php
		$checked = FALSE;
		if (preg_match("/--ALL-GROUPS--/",$group_info->agent_status_viewable_groups))
			$checked = TRUE;
			echo form_checkbox('agent_status_viewable_groups[]',"--ALL-GROUPS--", $checked, ' class="modify-usergroup"')." <b>ALL-GROUPS</b> - All user groups in the system<br />\n";

		$checked = FALSE;
		if (preg_match("/--CAMPAIGN-AGENTS--/",$group_info->agent_status_viewable_groups))
			$checked = TRUE;
			echo form_checkbox('agent_status_viewable_groups[]',"--CAMPAIGN-AGENTS--", $checked, ' class="modify-usergroup"')." <b>CAMPAIGN-AGENTS</b> - All users logged into the same campaign<br />\n";

		$checked = FALSE;
		if (preg_match("/--NOT-LOGGED-IN-AGENTS--/",$group_info->agent_status_viewable_groups))
			$checked = TRUE;
			echo form_checkbox('agent_status_viewable_groups[]',"--NOT-LOGGED-IN-AGENTS--", $checked, ' class="modify-usergroup"')." <b>NOT-LOGGED-IN-AGENTS</b> - All users in the system, even not logged-in<br />\n";

		foreach ($group_list as $list)
		{
			$checked = FALSE;
			if (preg_match("/ {$list->user_group}/",$group_info->agent_status_viewable_groups))
				$checked = TRUE;
				
			echo form_checkbox('agent_status_viewable_groups[]',$list->user_group, $checked, ' class="modify-usergroup"')." {$list->user_group} - {$list->group_name}<br />\n";
		}
		?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:30%;height:10px;">
		Agent Status View Time:
		</td>
		<td>
		<?=form_dropdown('agent_status_view_time',array('Y'=>'YES','N'=>'NO'),$group_info->agent_status_view_time,'id="agent_status_view_time" class="modify-usergroup"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:30%;height:10px;">
		Agent Call Log View:
		</td>
		<td>
		<?=form_dropdown('agent_call_log_view',array('Y'=>'YES','N'=>'NO'),$group_info->agent_call_log_view,'id="agent_call_log_view" class="modify-usergroup"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:30%;height:10px;">
		Agent Allow Consultative Xfer:
		</td>
		<td>
		<?=form_dropdown('agent_xfer_consultative',array('Y'=>'YES','N'=>'NO'),$group_info->agent_xfer_consultative,'id="agent_xfer_consultative" class="modify-usergroup"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:30%;height:10px;">
		Agent Allow Dial Override Xfer:
		</td>
		<td>
		<?=form_dropdown('agent_xfer_dial_override',array('Y'=>'YES','N'=>'NO'),$group_info->agent_xfer_dial_override,'id="agent_xfer_dial_override" class="modify-usergroup"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:30%;height:10px;">
		Agent Allow Voicemail Message Xfer:
		</td>
		<td>
		<?=form_dropdown('agent_xfer_vm_transfer',array('Y'=>'YES','N'=>'NO'),$group_info->agent_xfer_vm_transfer,'id="agent_xfer_vm_transfer" class="modify-usergroup"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:30%;height:10px;">
		Agent Allow Blind Xfer:
		</td>
		<td>
		<?=form_dropdown('agent_xfer_blind_transfer',array('Y'=>'YES','N'=>'NO'),$group_info->agent_xfer_blind_transfer,'id="agent_xfer_blind_transfer" class="modify-usergroup"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:30%;height:10px;">
		Agent Allow Dial With Customer Xfer:
		</td>
		<td>
		<?=form_dropdown('agent_xfer_dial_with_customer',array('Y'=>'YES','N'=>'NO'),$group_info->agent_xfer_dial_with_customer,'id="agent_xfer_dial_with_customer" class="modify-usergroup"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:30%;height:10px;">
		Agent Allow Park Customer Dial Xfer:
		</td>
		<td>
		<?=form_dropdown('agent_xfer_park_customer_dial',array('Y'=>'YES','N'=>'NO'),$group_info->agent_xfer_park_customer_dial,'id="agent_xfer_park_customer_dial" class="modify-usergroup"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:30%;height:10px;">
		Agent Fullscreen:
		</td>
		<td>
		<?=form_dropdown('agent_fullscreen',array('Y'=>'YES','N'=>'NO'),$group_info->agent_fullscreen,'id="agent_fullscreen" class="modify-usergroup"') ?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:30%;height:10px;">
		Allowed Reports:
		</td>
		<td>
		<?php
		$UGreportsArray = explode(",",$UGreports);
		$UGreportsList = array();
		$UGreportsSelected = explode(",",$group_info->allowed_reports);
		foreach ($UGreportsArray as $list)
		{
			$UGreportsList[$list] = "$list";
		}
		echo form_dropdown('allowed_reports[]',$UGreportsList,$UGreportsSelected,'size="8" multiple="multiple" class="modify-usergroup"');
		?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:30%;height:10px;">
		Allowed User Groups:
		</td>
		<td>
		<?php
		$checked = FALSE;
		if (preg_match("/---ALL---/",$group_info->admin_viewable_groups))
			$checked = TRUE;
			echo form_checkbox('admin_viewable_groups[]',"---ALL---", $checked,'  class="modify-usergroup"')." <b>ALL-GROUPS</b> - All user groups in the system<br />\n";

		foreach ($group_list as $list)
		{
			$checked = FALSE;
			if (preg_match("/ {$list->user_group}/",$group_info->admin_viewable_groups))
				$checked = TRUE;
				
			echo form_checkbox('admin_viewable_groups[]',$list->user_group, $checked, ' class="modify-usergroup"')." {$list->user_group} - {$list->group_name}<br />\n";
		}
		?>
		</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="font-size:6px;">&nbsp;</td><td style="font-size:6px;">&nbsp;</td>
	</tr>
	<tr class="advance_settings" style="display: none;">
		<td style="text-align:right;width:30%;height:10px;">
		Allowed Call Times:
		</td>
		<td>
		<?php
		$checked = FALSE;
		if (preg_match("/---ALL---/",$group_info->admin_viewable_call_times))
			$checked = TRUE;
			echo form_checkbox('admin_viewable_call_times[]',"---ALL---", $checked,' class="modify-usergroup"' )." <b>ALL-CALLTIMES</b> - All call times in the system<br />\n";

		foreach ($calltime_list as $list)
		{
			$checked = FALSE;
			if (preg_match("/{$list->call_time_id}/",$group_info->admin_viewable_call_times))
				$checked = TRUE;
				
			echo form_checkbox('admin_viewable_call_times[]',$list->call_time_id, $checked, ' class="modify-usergroup"')." {$list->call_time_id} - {$list->call_time_name}<br />\n";
		}
		?>
		</td>
	</tr>


	<tr>
		<td>&nbsp;</td><td>&nbsp;</td>
	</tr>
	<tr>
    	<td><span id="advance_link" style="cursor:pointer;font-size:9px;">[ + ADVANCE SETTINGS ]</span><input type="hidden" id="isAdvance" value="0" /></td><td style="text-align:right;"><span id="saveSettings" class="buttons">SAVE SETTINGS</span><!--<input id="saveSettings" type="submit" value=" SAVE SETTINGS " style="cursor:pointer;" />--></td>
    </tr>
</table>
</form>
<?php
		break;
}
?>
<br style="font-size:9px;" />
