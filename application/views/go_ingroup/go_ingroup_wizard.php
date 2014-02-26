<?php
############################################################################################
####  Name:             go_ingroup_wizard.php                                           ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();
$NOW = date('Y-m-d');
?>
<style type="text/css">
#wizardTable td{
	padding:0px 5px 0px 5px;
}

#saveButtons{
	float:right;
	width:150px;
	text-align:right;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

#saveButtons span{
	text-align:center;
	color:#7A9E22;
	cursor:pointer;
	width:40px;
}

#saveButtons span:hover{
	font-weight:bold;
}

.modify-value {
	float: right;
}

#ivrMenuStep1 td:first-child,#ivrMenuStep2 td:first-child {
	text-align: right;
	padding-right: 5px;
	white-space: nowrap;
}
</style>

<script>
<?php
if ($wiztype=="ingroup") {
?>
// Color Picker
var myPicker = new jscolor.color(document.getElementById('group_color'), {})
myPicker.fromString('FFFFFF')  // now you can access API via 'myPicker' variable
<?php
}
?>

function launch_vm_chooser(fieldname,stage,vposition,defvalue) {
	$.post("<?=$base?>index.php/go_ingroup/chooser", { action: "vm_list",user: "<?=$gouser?>", format: "selectframe", stage: stage, comments: fieldname, defaultvalue: defvalue  },
	function(data){
		document.getElementById("div"+fieldname).innerHTML=data;
	});	
}

function setDivVal(divid,idval) {
	document.getElementById(divid).value=idval;
}

function launch_chooser(fieldname,stage,vposition,defvalue,showtable) {
	$.post("<?=$base?>index.php/go_ingroup/chooser", { action: "sounds_list",user: "<?=$gouser?>", format: "selectframe", stage: stage, comments: fieldname, defaultvalue: defvalue  },
	function(data){
		document.getElementById("div"+fieldname).innerHTML=data;
		if (showtable) {
			$('#tbl'+fieldname).show();
		}
	});	
}

function launch_moh_chooser(fieldname,stage,vposition,defvalue) {
	$.post("<?=$base?>index.php/go_ingroup/chooser", { action: "moh_list",user: "<?=$gouser?>", format: "selectframe", stage: stage, comments: fieldname, defaultvalue: defvalue  },
	function(data){
		document.getElementById("div"+fieldname).innerHTML=data;
	});
}

	
$('#did_pattern,#did_description,#extension').bind("keydown keypress", function(event)
{
	//console.log(event.type + " -- " + event.altKey + " -- " + event.which + " -- " + $(this).attr('id'));
	if (event.type == "keydown") {
		// For normal key press
		if ((event.keyCode == 32 && ($(this).attr('id') != 'did_description' && $(this).attr('id') != 'menu_name')) || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
			|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
			|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
			return false;
		
		if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
			return false;
		
		if (!event.shiftKey && event.keyCode == 173)
			return false;
		
		if (event.keyCode > 64 && event.keyCode < 91 && $(this).attr('id') != 'did_description')
			return false;
	} else {
		// For ASCII Key Codes
		if ((event.which == 32 && $(this).attr('id') != 'did_description') || (event.which > 32 && event.which < 48)
			|| (event.which > 57 && event.which < 65)
			|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
			return false;
	}
});
</script>

<?php
if ($wiztype=="ingroup") {
?>
<div style="float:right;" id="small_step_number"><img src="<?php echo $base; ?>img/step1-nav-small.png" /></div>
<div style="font-weight:bold;font-size:16px;color:#333;">In-Group Wizard &raquo; Create New In-Group</div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />

<table style="width:100%;">
	<tr>
		<td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step1-trans.png" /></div>
		</td>
		<td valign="top">
            <span id="wizardContent" style="height:100px; padding-top:10px;">
				<form  method="POST" id="go_listfrm" name="go_listfrm">
				<input type="hidden" id="selectval" name="selectval" value="">
				<input type="hidden" id="addSUBMIT" name="addSUBMIT" value="addSUBMIT">
				<table id="wizardTable" width="100%">
					<tr>
						<td style="width:100px"><label class="modify-value">Group ID:</label></td>
						<td><input type="text" name="group_id" id="group_id" size="20" maxlength="20" onkeydown="return isAlphaNumericwospace(event.keyCode);" onkeyup="KeyUp(event.keyCode);"><br>
							<font color="red" size="1">
							*(no spaces). 2 and 20 characters in length
							</font>
						</td>
					</tr>
					<tr>
						<td><label class="modify-value">Group Name:</label></td>
						<td><input type="text" name="group_name" id="group_name" size="30" maxlength="30" onkeydown="return isAlphaNumericwspace(event.keyCode);" onkeyup="KeyUp(event.keyCode);"><br>
							<font color="red" size="1">
							*2 and 20 characters in length
							</font>
						</td>
					</tr>
					<tr>
					   <td><label class="modify-value">Group Color:</label></td>
						<td><input class="color" type="text" name="group_color" id="group_color" size="7" maxlength="7" value="66ff00">
						</td>
					</tr>
					<tr style="<?=($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "display:none" : "" ?>">
					   <td><label class="modify-value">User Group:</label></td>
						<td>
							<?php
							foreach ($usergroups as $group)
							{
									$groupArray[$group->user_group] = "{$group->user_group} - {$group->group_name}";
							}
							echo form_dropdown('user_group',$groupArray,null,'id="user_group"');
							?>
						</td>
					</tr>
					<tr>
						<td><label class="modify-value">Active:</label></td>
						<td>
							<select size="1" name="active" id="active">
								<option SELECTED>Y</option>
								<option>N</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label class="modify-value">Web Form:</label></td>
						<td>
							<input type="text" name="web_form_address" size="50" maxlength="500">
						</td>
					</tr>
					<tr>
						<td><label class="modify-value">Voicemail:</label></td>
						<td>
							<input type="text" name="voicemail_ext" id="iWizvoicemail_ext" size=12 maxlength=10 readonly="readonly">
							<a href="javascript:launch_vm_chooser('iWizvoicemail_ext','vm',500,document.getElementById('iWizvoicemail_ext').value);"><FONT color="blue" size="1">[ Voicemail Chooser ]</a><div id="diviWizvoicemail_ext"></div>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;"><label class="modify-value">Next Agent Call:</label></td>
						<td>
							<select size="1" name="next_agent_call" id="next_agent_call">
								<option>random</option>
								<option>oldest_call_start</option>
								<option>oldest_call_finish</option>
								<option>overall_user_level</option>
								<option>inbound_group_rank</option>
								<option>campaign_rank</option>
								<option>fewest_calls</option>
								<option>fewest_calls_campaign</option>
								<option>longest_wait_time</option>
								<option>ring_all</option>
							</select>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;"><label class="modify-value">Fronter Display:</label></td>
						<td>
							<select size="1" name="fronter_display">
								<option SELECTED>Y</option>
								<option>N</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label class="modify-value">Script:</label></td>
						<td>
							<select size="1" name="script_id" id="script_id" style="width:300px">
								<?php
									echo '<option value="">---NONE---</option>';
									foreach($script_list as $scriptlistsInfo){
										$script_id = $scriptlistsInfo->script_id;
										$script_name = $scriptlistsInfo->script_name;
										echo '<option value="'.$script_id.'">'.$script_id.'---'.$script_name.'</option>';
									}
								?>
							</select>
						</td>
					</tr>              
					<tr>
						<td style="white-space: nowrap;"><label class="modify-value">Get Call Launch:</label></td>
						<td> <select name="get_call_launch" id="get_call_launch">
								<option selected="">NONE</option>
								<option>SCRIPT</option>
								<option>WEBFORM</option>
								<option>FORM</option>
							  </select> 
						</td>   	           	
					</tr>
				</table>
				</form>
            </span>
		</td>
	</tr>
</table>
<hr style="border:#DFDFDF 1px solid;" />
<span id="saveButtons"><span id="submitForm" style="white-space: nowrap;" onclick="return formsubmitlist();">Submit</span></span>
<?php
}

if ($wiztype=="did") {
?>
<div style="float:right;" id="small_step_number"><img src="<?php echo $base; ?>img/step1-nav-small.png" /></div>
<div style="font-weight:bold;font-size:16px;color:#333;">DID Wizard &raquo; Create New DID</div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />

<table style="width:100%;">
	<tr>
		<td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step1-trans.png" /></div>
		</td>
		<td valign="top">
            <span id="wizardContent" style="height:100px; padding-top:10px;">
				<form  method="POST" id="go_didfrm" name="go_didfrm">
				<input type="hidden" id="selectval" name="selectval" value="">
				<input type="hidden" id="addDID" name="addDID" value="addDID">
				<table width="100%">
					<tr>
						<td><label class="modify-value">DID Extension:</label></td>
						<td><input type="text" name="did_pattern" id="did_pattern" size="30" maxlength="50">
						</td>
					</tr>
					<tr>
						<td><label class="modify-value">DID Description:</label></td>
						<td>
							<input type="text" name="did_description" id="did_description" size="30" maxlength="50" onkeydown="return isAlphaNumericwspace(event.keyCode);" onkeyup="KeyUp(event.keyCode);">
						</td>
					</tr>
					<tr>
						<td><label class="modify-value">Active:</label></td>
						<td><?php echo form_dropdown('active',array('Y'=>'Y','N'=>'N'),null,'id="active"'); ?>
						</td>
					</tr>
					<tr>
						<td><label class="modify-value">DID Route:</label></td>
						<td><?php echo form_dropdown('did_route',array('AGENT'=>'Agent','IN_GROUP'=>'In-group','PHONE'=>'Phone','CALLMENU'=>'Call Menu / IVR','VOICEMAIL'=>'Voicemail','EXTEN'=>'Custom Extension'),null,'id="did_route" onchange="showRouteOptions(document.getElementById(\'did_route\').value,\'Wizard\')"'); ?>
						</td>
					</tr>
					<tr style="<?=($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "display:none" : "" ?>">
					   <td><label class="modify-value">User Group:</label></td>
						<td>
							<?php
							foreach ($usergroups as $group)
							{
								$groupArray[$group->user_group] = "{$group->user_group} - {$group->group_name}";
							}
							echo form_dropdown('user_group',$groupArray,null,'id="user_group"');
							?>
						</td>
					</tr>
					<?php
					if (count($agent_list))
					{
						$Agent_menu = "<option value=\"\">--NONE--</option>";
						foreach ($agent_list as $agent)
						{
							$Agent_menu .= "<option value=\"{$agent->user}\">{$agent->user} - {$agent->full_name}</option>\n";
						}
					} else {
						$Agent_menu = "<option value=\"\">--NONE--</option>";
					}
					?>
					<tr class="didAgentWizard">
						<td><label class="modify-value">Agent ID:</label></td>
						<td><select id="user" name="user" style="width:350px;">
						<?php echo "$Agent_menu"; ?>
						</select>
						</td>
					</tr>
					<tr class="didAgentWizard">
						<td><label class="modify-value">Agent Unavailable Action:</label></td>
						<td><?php echo form_dropdown('user_unavailable_action',array('VOICEMAIL'=>'Voicemail','PHONE'=>'Phone','IN_GROUP'=>'In-group','EXTEN'=>'Custom Extension'),null,'id="user_unavailable_action"'); ?>
						</td>
					</tr>
					<tr class="" style="display: none">
						<td style="white-space: nowrap;"><label class="modify-value">Agent Route Settings In-Group:</label></td>
						<td>
						<?php
						foreach ($active_ingroups as $ingroup)
						{
							$ingroupArray[$ingroup->group_id] = "{$ingroup->group_id} - {$ingroup->group_name}";
						}
						echo form_dropdown('user_route_settings_ingroup',$ingroupArray,'AGENTDIRECT','id="user_route_settings_ingroup" style="width:300px"');
						?>
						</td>
					</tr>
					<tr class="didExtensionWizard" style="display: none">
						<td><label class="modify-value">Extension:</label></td>
						<td><?php echo form_input('extension','9998811112','id="extension" size="30" maxlength="50"'); ?>
						</td>
					</tr>
					<tr class="didExtensionWizard" style="display: none">
						<td><label class="modify-value">Extension Context:</label></td>
						<td><?php echo form_input('exten_context','default','id="exten_context" size="30" maxlength="50" onkeydown="return isAlphaNumericwospace(event.keyCode);" onkeyup="KeyUp(event.keyCode);"'); ?>
						</td>
					</tr>
					<tr class='didVoicemailWizard' style='display:none'>
						<td><label class="modify-value">Voicemail Box:</label></td>
						<td align=left><input type=text name=voicemail_ext id=wizvoicemail_ext size=12 maxlength=10 readonly="readonly"> <a href="javascript:launch_vm_chooser('wizvoicemail_ext','vm',500,document.getElementById('wizvoicemail_ext').value);"><FONT color="blue" size="1">[ Voicemail Chooser ]</a><div id="divwizvoicemail_ext"></div>
						</td>
					</tr>
					<tr class="didPhoneWizard" style="display: none">
						<td style="white-space: nowrap;"><label class="modify-value">Phone Extension:</label></td>
						<td>
						<?php
						$phoneArray[''] = "---NONE---";
						foreach ($phone_list as $phone)
						{
							$phoneArray[$phone->extension] = "{$phone->extension} - {$phone->server_ip} - {$phone->dialplan_number}";
						}
						echo form_dropdown('phone',$phoneArray,null,'id="phone" style="width:350px"');
						?>
						</td>
					</tr>
					<tr class="didPhoneWizard" style="display: none">
						<td><label class="modify-value">Server IP:</label></td>
						<td>
						<?php
						$serverArray[''] = "---NONE---";
						foreach ($server_list as $server)
						{
							$serverArray[$server->server_id] = "{$server->server_id} - {$server->server_ip}";
						}
						echo form_dropdown('server_ip',$serverArray,null,'id="server_ip" style="width:350px"');
						?>
						</td>
					</tr>
					<tr class='didInboundWizard' style='display:none'>
						<td><label class="modify-value">In-Group ID:</label></td>
						<td>
						<?php
						foreach ($active_ingroups as $ingroup)
						{
							$ingroupArray[$ingroup->group_id] = "{$ingroup->group_id} - {$ingroup->group_name}";
						}
						echo form_dropdown('group_id',$ingroupArray,'AGENTDIRECT','id="group_id" style="width:350px"');
						?>
						</td>
					</tr>										
					<tr class='' style='display:none'>
						<td><label class="modify-value">In-Group List ID:</label></td>
						<td><input type=text name=list_id id=wizlist_id size=14 maxlength=14 value="999" >
						</td>
					</tr>
					<?php
					$countcampaigns_list = count($campaigns_list);
					if($countcampaigns_list > 0) {
						foreach($campaigns_list as $campaigns_listInfo){
							$campaign_id = $campaigns_listInfo->campaign_id;
							$campaign_name = $campaigns_listInfo->campaign_name;
							$campaigns_list .= "<option value=\"$campaign_id\">$campaign_id - $campaign_name</option>\n";
						}
					}
					?>
					<tr class='' style='display:none'>
						<td style="white-space: nowrap"><label class="modify-value">In-Group Campaign ID:</label></td>
						<td><select size=1 name=campaign_id id=wizcampaign_id style="width:300px">
						<option value=\"\">--NONE--</option>
						<?php echo "$campaigns_list"; ?>
						</select>
						</td>
					</tr>
					<tr class='didCallMenuWizard' style='display:none'>
						<td><label class="modify-value">Call Menu:</label></td>
						<td>
						<?php
						$menuArray[''] = "---NONE---";
						foreach ($call_menus as $menu)
						{
							$menuArray[$menu->menu_id] = "{$menu->menu_id} - {$menu->menu_name}";
						}
						echo form_dropdown('menu_id',$menuArray,null,'id="menu_id" style="width:350px"');
						?>
						</td>
					</tr>
				</table>
				</form>
            </span>
		</td>
	</tr>
</table>
<hr style="border:#DFDFDF 1px solid;" />
<span id="saveButtons"><span id="submitForm" style="white-space: nowrap;" onclick="return formsubmitdid();">Submit</span></span>
<?php
}

if ($wiztype=="ivr") {
?>
<script>
$(function()
{
	$("#nextStepCallMenu").click(function()
	{
		var isEmpty = 0;
		if ($("#menu_id").val().length > 0)
		{
			if ($('#err_menu_id').html().match(/Not Available/))
			{
				alert("Menu ID Not Available.");
				isEmpty = 1;
			}
			
			if (!isEmpty)
			{
				$("#ivrMenuStep1").hide();
				$("#nextStepCallMenu").hide();
				$("#ivrMenuStep2").show();
				$("#backCallMenu").show();
				$("#submitCallMenu").show();
				$(".divider").show();
				$("#wizardHeader").html("Call Menu Wizard » Create New Call Menu » Call Menu Options");
				$("#small_step_number > img").attr('src','<?=$base?>img/step2of2-navigation-small.png');
				$("#step_number > img").attr('src','<?=$base?>img/step2-trans.png')
				$("#box").css('width','930px');
				$("#box").css('left','10%');
			}
		} else {
			alert('Menu ID should NOT be empty.');
		}
	});
	
	$("#backCallMenu").click(function()
	{
		$("#ivrMenuStep1").show();
		$("#nextStepCallMenu").show();
		$("#ivrMenuStep2").hide();
		$("#backCallMenu").hide();
		$("#submitCallMenu").hide();
		$(".divider").hide();
		$("#wizardHeader").html("Call Menu Wizard » Create New Call Menu");
		$("#small_step_number > img").attr('src','<?=$base?>img/step1of2-navigation-small.png');
		$("#step_number > img").attr('src','<?=$base?>img/step1-trans.png')
		$("#box").css('width','760px');
		$("#box").css('left','20%');
	});
	
	$("#submitCallMenu").click(function()
	{
		var isEmpty = 0;
		if ($("#menu_id").val().length > 0)
		{
			if ($('#err_menu_id').html().match(/Not Available/))
			{
				alert("Menu ID Not Available.");
				isEmpty = 1;
			}
			
			if (!isEmpty)
			{
				alert('Call Menu ID '+$("#menu_id").val()+' Created');
				$("#go_callmenufrm").submit();
			}
		} else {
			alert('Menu ID should NOT be empty.');
		}
	});
	
	$('#menu_id').keyup(function(e)
	{		
		if ($(this).val().length > 3)
		{
			$('#err_menu_id').load('<? echo $base; ?>index.php/go_ingroup/go_check_ingroup/'+$(this).val());
		} else {
			$('#err_menu_id').html("<small style=\"color:red;\">Minimum of 4 digits.</small>");
		}
	});
});
	
function showoptionpostval(menuid,optionval,route,ctr)
{
	$.post("<?=$base?>index.php/go_ingroup/showoption", { menuid: menuid, optionval: optionval, route: route, ctr: ctr, action: "showoption" }, function(data){
		if (data.length > 0)
		{
			$('.option_hidden_'+ctr).slideDown('slow');
			$('.option_display_'+ctr).html(data);
		} else {
			$('.option_hidden_'+ctr).slideUp('slow');
			$('.option_display_'+ctr).html('');
		}
	});
}
</script>
<div style="float:right;" id="small_step_number"><img src="<?php echo $base; ?>img/step1of2-navigation-small.png" /></div>
<div style="font-weight:bold;font-size:16px;color:#333;" id="wizardHeader">Call Menu Wizard &raquo; Create New Call Menu</div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />

<table style="width:100%;">
	<tr>
		<td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step1-trans.png" /></div>
		</td>
		<td valign="top">
            <span id="wizardContent" style="height:100px; padding-top:10px;">
				<form  method="POST" id="go_callmenufrm" name="go_callmenufrm">
				<input type="hidden" id="selectval" name="selectval" value="">
				<input type="hidden" id="addCALLMENU" name="addCALLMENU" value="addCALLMENU">
				<table width="100%" id="ivrMenuStep1">
					<tr>
						<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Menu ID: </td>
						<td><input type="text" name="menu_id" id="menu_id" size="25" maxlength="50" onkeydown="return isAlphaNumericwospace(event.keyCode);" onkeyup="KeyUp(event.keyCode);" /> <span id="err_menu_id"></span>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Menu Name: </td>
						<td>
							<input type="text" name="menu_name" id="menu_name" size="30" maxlength="100" onkeydown="return isAlphaNumericwspace(event.keyCode);" onkeyup="KeyUp(event.keyCode);" />
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Menu Greeting: </td>
						<td style="white-space: nowrap;">
							<input type="text" name="menu_prompt" id="menu_prompt" size="30" maxlength="255" readonly="readonly" /> 
							 <a href="javascript:launch_chooser('menu_prompt','date',1200,document.getElementById('menu_prompt').value,1);"><font color="blue" size="1">[ audio chooser ]</font></a>
						</td>
					</tr>
					<tr style="display:none;" id="tblmenu_prompt">
						<td style="white-space: nowrap;">&nbsp;</td>
						<td>
							<div id="divmenu_prompt"></div>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Menu Timeout: </td>
						<td>
							<input type="text" name="menu_timeout" id="menu_timeout" size="10" maxlength="5" value="10" />
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Menu Timeout Greeting: </td>
						<td style="white-space: nowrap;">
							<input type="text" name="menu_timeout_prompt" id="menu_timeout_prompt" size="30" maxlength="255" readonly="readonly" /> 
							 <a href="javascript:launch_chooser('menu_timeout_prompt','date',1200,document.getElementById('menu_timeout_prompt').value,1);"><font color="blue" size="1">[ audio chooser ]</font></a>
						</td>
					</tr>
					<tr style="display:none;" id="tblmenu_timeout_prompt">
						<td style="white-space: nowrap;">&nbsp;</td>
						<td>
							<div id="divmenu_timeout_prompt"></div>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Menu Invalid Greeting: </td>
						<td style="white-space: nowrap;">
							<input type="text" name="menu_invalid_prompt" id="menu_invalid_prompt" size="30" maxlength="255" readonly="readonly" /> 
							 <a href="javascript:launch_chooser('menu_invalid_prompt','date',1200,document.getElementById('menu_invalid_prompt').value,1);"><font color="blue" size="1">[ audio chooser ]</font></a>
						</td>
					</tr>
					<tr style="display:none;" id="tblmenu_invalid_prompt">
						<td style="white-space: nowrap;">&nbsp;</td>
						<td>
							<div id="divmenu_invalid_prompt"></div>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Menu Repeat: </td>
						<td>
							<input type="text" name="menu_repeat" id="menu_repeat" size="3" maxlength="3" value="1" />
						</td>
					</tr>
					<tr style="display:none">
						<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Menu Time Check: </td>
						<td>
							<?php
							$options = array('0 - No Time Check','1 - Time Check');
							echo form_dropdown('menu_time_check',$options,'1','id="menu_time_check"');
							?>
						</td>
					</tr>
					<tr style="display:none">
						<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Call Time: </td>
						<td>
							<?php
							$calltimeArray = array();
							foreach ($call_times as $calltime)
							{
							   $calltimeArray[$calltime->call_time_id] = "{$calltime->call_time_id} - {$calltime->call_time_name}";
							}
							echo form_dropdown('call_time_id',$calltimeArray,'24hours','id="call_time_id"');
							?>
						</td>
					</tr>
					<tr style="display:none">
						<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Track Calls in<br />Real-Time Report: </td>
						<td>
							<?php
							$options = array('0 - No Realtime Tracking','1 - Realtime Tracking');
							echo form_dropdown('track_in_vdac',$options,'1','id="track_in_vdac"');
							?>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;font-weight:bold;color:#7f7f7f;">Tracking Group: </td>
						<td>
							<?php
							foreach ($active_ingroups as $ingroup)
							{
								$ingroupArray[$ingroup->group_id] = "{$ingroup->group_id} - {$ingroup->group_name}";
							}
							$ingroupMerge = array_merge(array('CALLMENU'=>'CALLMENU - default'),$ingroupArray);
							echo form_dropdown('tracking_group',$ingroupMerge,$group_settings->tracking_group,'id="tracking_group" style="width:400px;"');
							?>
						</td>
					</tr>
					<tr style="<?=($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "display:none" : "" ?>">
					   <td><label class="modify-value">User Group:</label></td>
						<td>
							<?php
							foreach ($usergroups as $group)
							{
									$groupArray[$group->user_group] = "{$group->user_group} - {$group->group_name}";
							}
							echo form_dropdown('user_group',$groupArray,null,'id="user_group"');
							?>
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
				</table>
				<table id="ivrMenuStep2" style="display:none">
				<?php
				echo "<tr>";
				echo "<td colspan='6' style='font-weight:bold'><div style='border-bottom:2px solid #DFDFDF; padding-bottom: 5px; text-align: center;'>Default Call Menu Entry</div></td>";
				echo "</tr><tr>";
				echo "<td style='padding-left:10px'>Option:</td><td>".form_dropdown('',array('TIMEOUT'=>'TIMEOUT','TIMECHECK'=>'TIMECHECK'),'TIMEOUT','disabled')."</td>";
				echo "<td>Description:</td><td>".form_input('','Hangup','maxlength="255" size="30" disabled')."</td>";
				echo "<td>Route:</td><td>".form_dropdown('',array('HANGUP'=>'Hangup','EXTENSION'=>'Custom Extension'),'HANGUP','disabled')."</td>";
				echo "</tr>\n";
				echo "<tr>";
				echo "<td colspan=\"6\" style=\"text-align:center;\">Audio File: ".form_input('','vm-goodbye','size="30" disabled')."</td>";
				echo "</tr>";
				echo "<tr><td colspan='6' style='font-weight:bold'><div style='border-bottom:2px solid #DFDFDF;padding:10px 0 5px 0;'>Add New Call Menu Options</div></td></tr>";
				$ctr = 0;
				while ($ctr < 10)
				{
					// onChange="javascript:showoptionpostval(this.options[this.selectedIndex].value,'.$ctr.');"
					$optionDD = form_dropdown('option_value_'.$ctr,array(''=>'','0','1','2','3','4','5','6','7','8','9','#'=>'#','*'=>'*','TIMECHECK'=>'TIMECHECK','INVALID'=>'INVALID'),'','id="option_value_'.$ctr.'" onChange="javascript:checkoptionval(this.options[this.selectedIndex].value,'.$ctr.');"');
					$optionRoute = form_dropdown('option_route_'.$ctr,array(''=>'','CALLMENU'=>'Call Menu / IVR','INGROUP'=>'In-group','DID'=>'DID','HANGUP'=>'Hangup','EXTENSION'=>'Custom Extension','PHONE'=>'Phone','VOICEMAIL'=>'Voicemail','AGI'=>'AGI'),'','id="option_route_'.$ctr.'" onChange="javascript:showoptionpostval(\''.$dataval.'\',document.getElementById(\'option_value_'.$ctr.'\').options[document.getElementById(\'option_value_'.$ctr.'\').selectedIndex].value,this.options[this.selectedIndex].value,'.$ctr.');"');
					echo "<tr class=\"trview\">";
					echo "<td style='padding-left:10px'>Option:</td><td>$optionDD</td>";
					echo "<td>Description:</td><td>".form_input('option_description_'.$ctr,'','maxlength="255" size="30"')."</td>";
					echo "<td>Route:</td><td>$optionRoute</td>";
					echo "</tr>\n";
					echo "<tr class=\"trview option_hidden_$ctr\" style=\"display:none;\">";
					echo "<td colspan=\"6\" style=\"text-align:center;\" class=\"option_display_$ctr\"></td>";
					echo "</tr>";
					$ctr++;
				}
				?>
				</table>
				</form>
            </span>
		</td>
	</tr>
</table>
<hr style="border:#DFDFDF 1px solid;" />
<div style="float: right"><a id="backCallMenu" style="cursor: pointer;display:none;color:#7A9E22;">Back</a><span class="divider" style="display:none"> | </span><a id="nextStepCallMenu" style="cursor: pointer;color:#7A9E22;">Next</a><a id="submitCallMenu" style="cursor: pointer;color:#7A9E22;display:none;">Finish</a></div>
<?php
}
?>
