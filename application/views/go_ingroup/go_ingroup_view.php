<?php
############################################################################################
####  Name:             go_ingroup_view.php                                             ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();

$ingroupArray["---NONE---"] = "---NONE---";
foreach ($active_ingroups as $ingroup)
{
	$ingroupArray[$ingroup->group_id] = "{$ingroup->group_id} - {$ingroup->group_name}";
	$ingroupSelect .= '<option value="'.$ingroup->group_id.'">'.$ingroup->group_id.' - '.$ingroup->group_name.'</option>';
}

$menuArray[''] = "---NONE---";
foreach ($call_menus as $menu)
{
	$menuArray[$menu->menu_id] = "{$menu->menu_id} - {$menu->menu_name}";
	$menuSelect .= '<option value="'.$menu->menu_id.'">'.$menu->menu_id.' - '.$menu->menu_name.'</option>';
}

foreach ($call_times as $time)
{
	$timeArray[$time->call_time_id] = "{$time->call_time_id} - {$time->call_time_name}";
	$timeSelect .= '<option value="'.$time->call_time_id.'">'.$time->call_time_id.' - '.$time->call_time_name.'</option>';
}

foreach ($active_campaigns as $camp)
{
	$campArray[$camp->campaign_id] = "{$camp->campaign_id} - {$camp->campaign_name}";
	$campSelect .= '<option value="'.$camp->campaign_id.'">'.$camp->campaign_id.' - '.$camp->campaign_name.'</option>';
}

foreach ($active_dids as $did)
{
	$didArray[$did->did_pattern] = "{$did->did_pattern} - {$did->did_description} - {$did->did_route}";
	$didSelect .= '<option value="'.$did->did_pattern.'">'.$did->did_pattern.' - '.$did->did_description.' - '.$did->did_route.'</option>';
}

$userArray[''] = "---NONE---";
foreach ($user_list as $user)
{
	$userArray[$user->user] = "{$user->user} - {$user->full_name}";
}

$phoneArray[''] = "---NONE---";
foreach ($phone_list as $phone)
{
	$phoneArray[$phone->extension] = "{$phone->extension} - {$phone->server_ip} - {$phone->dialplan_number}";
}

$serverArray[''] = "---NONE---";
foreach ($server_list as $server)
{
	$serverArray[$server->server_id] = "{$server->server_ip} - {$server->server_description}";
}

$filterPhoneArray[''] = "---NONE---";
foreach ($filter_phone_groups as $filter)
{
	$filterPhoneArray[$filter->filter_phone_group_id] = "{$filter->filter_phone_group_id} - {$filter->filter_phone_group_name}";
}

$handleArray = array('CID'=>'CID','CIDLOOKUP'=>'CIDLOOKUP','CIDLOOKUPRL'=>'CIDLOOKUPRL','CIDLOOKUPRC'=>'CIDLOOKUPRC','CIDLOOKUPALT'=>'CIDLOOKUPALT',
					 'CIDLOOKUPRLALT'=>'CIDLOOKUPRLALT','CIDLOOKUPRCALT'=>'CIDLOOKUPRCALT','CIDLOOKUPADDR3'=>'CIDLOOKUPADDR3',
					 'CIDLOOKUPRLADDR3'=>'CIDLOOKUPRLADDR3','CIDLOOKUPRCADDR3'=>'CIDLOOKUPRCADDR3','CIDLOOKUPALTADDR3'=>'CIDLOOKUPALTADDR3',
					 'CIDLOOKUPRLALTADDR3'=>'CIDLOOKUPRLALTADDR3','CIDLOOKUPRCALTADDR3'=>'CIDLOOKUPRCALTADDR3','ANI'=>'ANI','ANILOOKUP'=>'ANILOOKUP',
					 'ANILOOKUPRL'=>'ANILOOKUPRL','VIDPROMPT'=>'VIDPROMPT','VIDPROMPTLOOKUP'=>'VIDPROMPTLOOKUP','VIDPROMPTLOOKUPRL'=>'VIDPROMPTLOOKUPRL',
					 'VIDPROMPTLOOKUPRC'=>'VIDPROMPTLOOKUPRC','CLOSER'=>'CLOSER','3DIGITID'=>'3DIGITID','4DIGITID'=>'4DIGITID','5DIGITID'=>'5DIGITID',
					 '10DIGITID'=>'10DIGITID');
?>
<script>
$(function()
{
    $('.toolTip').tipTip();
	
	$(".scrollTop").click(function()
	{
		$("html, body").animate({ scrollTop: 0 }, 500);
	});
});

function launch_chooser(fieldname,stage,vposition,defvalue,showtable) {
	$.post("<?=$base?>index.php/go_ingroup/chooser", { action: "sounds_list",user: "<?=$gouser?>", format: "selectframe", stage: stage, comments: fieldname, defaultvalue: defvalue  },
	function(data){
		document.getElementById("div"+fieldname).innerHTML=data;
		if (showtable) {
			$('#tbl'+fieldname).show();
		}
	});	
}

function setDivVal(divid,idval) {
	document.getElementById(divid).value=idval;
}

function launch_vm_chooser(fieldname,stage,vposition,defvalue) {
	$.post("<?=$base?>index.php/go_ingroup/chooser", { action: "vm_list",user: "<?=$gouser?>", format: "selectframe", stage: stage, comments: fieldname, defaultvalue: defvalue  },
	function(data){
		document.getElementById("div"+fieldname).innerHTML=data;
	});	
}

function launch_moh_chooser(fieldname,stage,vposition,defvalue) {
	$.post("<?=$base?>index.php/go_ingroup/chooser", { action: "moh_list",user: "<?=$gouser?>", format: "selectframe", stage: stage, comments: fieldname, defaultvalue: defvalue  },
	function(data){
		document.getElementById("div"+fieldname).innerHTML=data;
	});	

}
	
function checkdatas(groupID) {
	if (groupID != undefined) {
		var itemdatas = $('#agentrankform').serialize();
		$('input:checkbox[id^="CHECK"]').each(function() {     
			if (!this.checked) {
				itemdatas += '&'+this.name+'=NO';
			}
		});
		//alert(itemdatas);
		$.post("<?=$base?>index.php/go_ingroup/checkagentrank", { itemrank: itemdatas, action: "getcheckagentrank", idgroup: groupID  }, function(data){
			alert("SUCCESS: Updated agent ranks for this Inbound Group.");
			changeAgentRankPage(groupID,$("#currentPage").val());
			//location.reload();
		});
	} else {
		if ($("#selectAllAgents").is(':checked'))
		{
			$('input:checkbox[id^="CHECK"]').each(function()
			{
					$(this).attr('checked',true);
			});
		}
		else
		{
			$('input:checkbox[id^="CHECK"]').each(function()
			{
					$(this).removeAttr('checked');
			});
		}
	}
}
</script>
<?php
if ($type=='ingroup')
{
?>
<script>
$(function() {
	$(".ingroupTabs").click(function()
	{
		var request = $(this).attr('id');
		var currentTab = '';
		$('.ingroupTabs').each(function() {
			currentTab = $(this).attr('id');
			//console.log(currentTab);
			if (request == $(this).attr('id')) {
				$(this).addClass('tabOn');
				$(this).removeClass('tabOff');
				$("#"+currentTab+"_div").show();
				if (currentTab=="tabAgents")
					$("#searchUserSpan").show();
			} else {
				$(this).addClass('tabOff');
				$(this).removeClass('tabOn');
				$("#"+currentTab+"_div").hide();
				if (currentTab=="tabAgents")
					$("#searchUserSpan").hide();
			}
		});
	});
	
	$("#showAdvanceOptions").click(function()
	{
		if ($("#advanceid").is(":visible"))
		{
			$("#advanceid").hide();
			$("#plus_minus").html('+');
		} else {
			$("#advanceid").show();
			$("#plus_minus").html('-');
		}
	});
	
	$("#timer_action").change(function()
	{
		if ($(this).val() != "NONE") {
			$(".timerAction").show();
		} else {
			$(".timerAction").hide();
		}
	});
	
	$("#drop_action").change(function()
	{
		switch($(this).val())
		{
			case "MESSAGE":
				$(".dropActionExten").show();
				$(".dropActionVoicemail").hide();
				$(".dropActionTransferGroup").hide();
				$(".dropActionCallMenu").hide();
				break;
			case "VOICEMAIL":
				$(".dropActionExten").hide();
				$(".dropActionVoicemail").show();
				$(".dropActionTransferGroup").hide();
				$(".dropActionCallMenu").hide();
				break;
			case "IN_GROUP":
				$(".dropActionExten").hide();
				$(".dropActionVoicemail").hide();
				$(".dropActionTransferGroup").show();
				$(".dropActionCallMenu").hide();
				break;
			case "CALLMENU":
				$(".dropActionExten").hide();
				$(".dropActionVoicemail").hide();
				$(".dropActionTransferGroup").hide();
				$(".dropActionCallMenu").show();
				break;
			default:
				$(".dropActionExten").hide();
				$(".dropActionVoicemail").hide();
				$(".dropActionTransferGroup").hide();
				$(".dropActionCallMenu").hide();
		}
	});
	
	$("#no_agent_action").change(function()
	{
		var action_values_array = [];
		switch($(this).val())
		{
			case "CALLMENU":
				action_values_array[0] = "<table border=0 style='width:100%'><tr class=trview><td style='width:355px;' align=right>Call Menu: </td><td align=left>";
				action_values_array[1] = '<select style="width:300px;" id="no_agent_action_value" name="no_agent_action_value"><option value="">---NONE---</option><?=$menuSelect ?></select>';
				action_values_array[2] = "</td></tr></table>";
				var action_values = action_values_array.join(" ");
				$(".noAgentActionTR").html(action_values);
				$("#no_agent_action_value").val('<?=$group_settings->no_agent_action_value ?>');
				break;
			
			case "INGROUP":
				var no_agent_action_value = '<?=$group_settings->no_agent_action_value ?>';
				if ($(this).val()!='<?=$group_settings->no_agent_action ?>')
					no_agent_action_value = 'SALESLINE,CID,LB,998,TESTCAMP,1,,,,';
				var action_value = no_agent_action_value.split(",");
				action_values_array[0] = "<table border=0 style='width:100%'><tr class=trview><td style='width:355px;' align=right>In-Group: </td><td align=left>";
				action_values_array[1] = '<select id="action_value_group_id" style="width:400px;"><?=$ingroupSelect ?></select>';
				action_values_array[2] = "</td></tr>";
				action_values_array[3] = "<tr class=trview><td style='width:355px;' align=right>Handle Method: </td><td align=left>";
				action_values_array[4] = '<select id="action_value_handle_method"><option value="CID">CID</option><option value="CIDLOOKUP">CIDLOOKUP</option><option value="CIDLOOKUPRL">CIDLOOKUPRL</option><option value="CIDLOOKUPRC">CIDLOOKUPRC</option><option value="CIDLOOKUPALT">CIDLOOKUPALT</option><option value="CIDLOOKUPRLALT">CIDLOOKUPRLALT</option><option value="CIDLOOKUPRCALT">CIDLOOKUPRCALT</option><option value="CIDLOOKUPADDR3">CIDLOOKUPADDR3</option><option value="CIDLOOKUPRLADDR3">CIDLOOKUPRLADDR3</option><option value="CIDLOOKUPRCADDR3">CIDLOOKUPRCADDR3</option><option value="CIDLOOKUPALTADDR3">CIDLOOKUPALTADDR3</option><option value="CIDLOOKUPRLALTADDR3">CIDLOOKUPRLALTADDR3</option><option value="CIDLOOKUPRCALTADDR3">CIDLOOKUPRCALTADDR3</option><option value="ANI">ANI</option><option value="ANILOOKUP">ANILOOKUP</option><option value="ANILOOKUPRL">ANILOOKUPRL</option><option value="VIDPROMPT">VIDPROMPT</option><option value="VIDPROMPTLOOKUP">VIDPROMPTLOOKUP</option><option value="VIDPROMPTLOOKUPRL">VIDPROMPTLOOKUPRL</option><option value="VIDPROMPTLOOKUPRC">VIDPROMPTLOOKUPRC</option><option value="CLOSER">CLOSER</option><option value="3DIGITID">3DIGITID</option><option value="4DIGITID">4DIGITID</option><option value="5DIGITID">5DIGITID</option><option value="10DIGITID">10DIGITID</option></select>';
				action_values_array[5] = "</td></tr>";
				action_values_array[6] = "<tr class=trview><td style='width:355px;' align=right>Search Method: </td><td align=left>";
				action_values_array[7] = '<select id="action_value_search_method"><option value="LB">LB - Load Balanced</option><option value="LO">LO - Load Balanced Overflow</option><option value="SO">SO - Server Only</option></select>';
				action_values_array[8] = "</td></tr>";
				action_values_array[9] = "<tr class=trview><td style='width:355px;' align=right>List ID: </td><td align=left>";
				action_values_array[10] = '<input type="text" maxlength="14" size="5" id="action_value_list_id" value="'+action_value[3]+'">';
				action_values_array[11] = "</td></tr>";
				action_values_array[12] = "<tr class=trview><td style='width:355px;' align=right>Campaign ID: </td><td align=left>";
				action_values_array[13] = '<select id="action_value_campaign_id"><?=$campSelect ?></select>';
				action_values_array[14] = "</td></tr>";
				action_values_array[15] = "<tr class=trview><td style='width:355px;' align=right>Phone Code: </td><td align=left>";
				action_values_array[16] = '<input type="text" maxlength="14" size="5" id="action_value_phone_code" value="'+action_value[5]+'">';
				action_values_array[17] = "</td></tr>";
				action_values_array[18] = "<tr style='display:none;'><td align=right>Value: </td><td align=left>";
				action_values_array[19] = '<input type="text" id="no_agent_action_value" name="no_agent_action_value" value="">';
				action_values_array[20] = "</td></tr></table>";
				var action_values = action_values_array.join(" ");
				$(".noAgentActionTR").html(action_values);
				$("#action_value_group_id").val(action_value[0]);
				$("#action_value_handle_method").val(action_value[1]);
				$("#action_value_search_method").val(action_value[2]);
				$("#action_value_list_id").val(action_value[3]);
				$("#action_value_campaign_id").val(action_value[4]);
				$("#action_value_phone_code").val(action_value[5]);
				break;
			
			case "DID":
				action_values_array[0] = "<table border=0 style='width:100%'><tr class=trview><td style='width:355px;' align=right>DID: </td><td align=left>";
				action_values_array[1] = '<select style="width:350px;" id="no_agent_action_value" name="no_agent_action_value"><?=$didSelect ?></select>';
				action_values_array[2] = "</td></tr></table>";
				var action_values = action_values_array.join(" ");
				$(".noAgentActionTR").html(action_values);
				$("#no_agent_action_value").val('<?=$group_settings->no_agent_action_value ?>');
				break;
			
			case "MESSAGE":
				var no_agent_action_value = '<?=$group_settings->no_agent_action_value ?>';
				if ($(this).val()!='<?=$group_settings->no_agent_action ?>')
					no_agent_action_value = 'nbdy-avail-to-take-call|vm-goodbye';
				action_values_array[0] = "<table border=0 style='width:100%'><tr class=trview><td style='width:355px;' align=right>Audio File: </td><td align=left>";
				action_values_array[1] = '<input type="text" value="'+no_agent_action_value+'" maxlength="255" size="40" id="no_agent_action_value" name="no_agent_action_value"> <a href="javascript:launch_chooser(\'no_agent_action_value\',\'date\',800,document.getElementById(\'no_agent_action_value\').value);"><FONT color="blue">[Audio Chooser]</font></a><div id="divno_agent_action_value"></div>';
				action_values_array[2] = "</td></tr></table>";
				var action_values = action_values_array.join(" ");
				$(".noAgentActionTR").html(action_values);
				break;
				
			case "EXTENSION":
				var no_agent_action_value = '<?=$group_settings->no_agent_action_value ?>';
				if ($(this).val()!='<?=$group_settings->no_agent_action ?>')
					no_agent_action_value = '8304,default';
				var action_value = no_agent_action_value.split(",");
				action_values_array[0] = "<table border=0 style='width:100%'><tr class=trview><td style='width:355px;' align=right>Extension: </td><td align=left>";
				action_values_array[1] = '<input type="text" value="'+action_value[0]+'" maxlength="255" size="20" id="action_value_extension">';
				action_values_array[2] = "</td></tr>";
				action_values_array[3] = "<tr class=trview><td style='width:355px;' align=right>Context: </td><td align=left>";
				action_values_array[4] = '<input type="text" value="'+action_value[1]+'" maxlength="255" size="20" id="action_value_context">';
				action_values_array[5] = "</td></tr>";
				action_values_array[6] = "<tr style='display:none;'><td align=right>Value: </td><td align=left>";
				action_values_array[7] = '<input type="text" id="no_agent_action_value" name="no_agent_action_value" value="">';
				action_values_array[8] = "</td></tr></table>";
				var action_values = action_values_array.join(" ");
				$(".noAgentActionTR").html(action_values);
				break;
			
			case "VOICEMAIL":
				var no_agent_action_value = '<?=$group_settings->no_agent_action_value ?>';
				if ($(this).val()!='<?=$group_settings->no_agent_action ?>')
					no_agent_action_value = '101';
				action_values_array[0] = "<table border=0 style='width:100%'><tr class=trview><td style='width:355px;' align=right>Voicemail Box: </td><td align=left>";
				action_values_array[1] = '<input type="text" value="'+no_agent_action_value+'" maxlength="10" size="12" id="no_agent_action_value" name="no_agent_action_value"> <a href="javascript:launch_vm_chooser(\'no_agent_action_value\',\'date\',600,document.getElementById(\'no_agent_action_value\').value);"><FONT color="blue">[ '+ lang('go_VoicemailChooser') +' ]</font></a><div id="divno_agent_action_value"></div>';
				action_values_array[2] = "</td></tr></table>";
				var action_values = action_values_array.join(" ");
				$(".noAgentActionTR").html(action_values);
				break;
		}
	});
	
	$("#search_user_button").click(function()
	{
		var group = '<?php echo $group_settings->group_id; ?>';
		var search = $("#search_user").val()
		if (search.length > 2) {
			$('#showAllUsers').show();
			$("#agentRanks").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
			$('#agentRanks').load('<? echo $base; ?>index.php/go_ingroup/go_change_ranks_page/'+group+'/1/'+search);
		} else {
			alert("Please enter at least 3 alphanumeric characters to search.");
		}
	});
	
	$('#search_user').bind("keydown keypress", function(event)
	{
		if (event.type == "keydown") {
			// For normal key press
			if (event.keyCode == 32 || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
				|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
				|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
				return false;
			
			if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
				return false;
			
			if (!event.shiftKey && event.keyCode == 173)
				return false;
		} else {
			// For ASCII Key Codes
			if ((event.which > 32 && event.which < 48) || (event.which == 32 && $(this).attr('id') != "statusName") || (event.which > 57 && event.which < 65)
				|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
				return false;
		}
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.which == 13) {
			$('#showAllUsers').show();
			var group = '<?php echo $group_settings->group_id; ?>';
			var search = $("#search_user").val();
			if (search.length > 2) {
				$("#agentRanks").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
				$('#agentRanks').load('<? echo $base; ?>index.php/go_ingroup/go_change_ranks_page/'+group+'/1/'+search);
			} else {
				alert("Please enter at least 3 alphanumeric characters to search.");
			}
		}
	});
	
	$("#showAllUsers").click(function()
	{
		$(this).hide();
		var group = '<?php echo $group_settings->group_id; ?>';
		$("#search_user").val('');
		$("#agentRanks").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		$('#agentRanks').load('<? echo $base; ?>index.php/go_ingroup/go_change_ranks_page/'+group+'/1/');
	});
	
	
	// put start ups below
	if (<?=strlen($group_settings->drop_inbound_group) ?> > 0)
	{
		var dropInboundGroup = '<?=$group_settings->drop_inbound_group ?>';
		switch(dropInboundGroup)
		{
			case "MESSAGE":
				$(".dropActionExten").show();
				$(".dropActionVoicemail").hide();
				$(".dropActionTransferGroup").hide();
				$(".dropActionCallMenu").hide();
				break;
			case "VOICEMAIL":
				$(".dropActionExten").hide();
				$(".dropActionVoicemail").show();
				$(".dropActionTransferGroup").hide();
				$(".dropActionCallMenu").hide();
				break;
			case "IN_GROUP":
				$(".dropActionExten").hide();
				$(".dropActionVoicemail").hide();
				$(".dropActionTransferGroup").show();
				$(".dropActionCallMenu").hide();
				break;
			case "CALLMENU":
				$(".dropActionExten").hide();
				$(".dropActionVoicemail").hide();
				$(".dropActionTransferGroup").hide();
				$(".dropActionCallMenu").show();
				break;
			default:
				$(".dropActionExten").hide();
				$(".dropActionVoicemail").hide();
				$(".dropActionTransferGroup").hide();
				$(".dropActionCallMenu").hide();
		}
	}
	
	if ('<?=$group_settings->timer_action ?>' != "NONE") {
		$(".timerAction").show();
	} else {
		$(".timerAction").hide();
	}
});

// Color Picker
var myPicker = new jscolor.color(document.getElementById('group_color'), {})
//myPicker.fromString('FFFFFF')  // now you can access API via 'myPicker' variable
	
function editIngroup(groupID) {
	$("html, body").animate({ scrollTop: 0 }, 500);
	var no_agent_action_value = '';
	switch ($("#no_agent_action").val()) {
		case "INGROUP":
			no_agent_action_value = $("#action_value_group_id").val() + ',' + $("#action_value_handle_method").val() + ',' + $("#action_value_search_method").val() + ',' + $("#action_value_list_id").val() + ',' + $("#action_value_campaign_id").val() + ',' + $("#action_value_phone_code").val() + ',,,,';
			break;
		case "EXTENSION":
			no_agent_action_value = $("#action_value_extension").val() + ',' + $("#action_value_context").val();
			break;
		default:
			no_agent_action_value = $("#no_agent_action_value").val();
	}
	$("#no_agent_action_value").val(no_agent_action_value);
	var itemsumit = $('#go_editingroup').serialize();
	//alert($("#no_agent_action_value").val());
	$.post("<?=$base?>index.php/go_ingroup/editsubmit", { itemsumit: itemsumit, action: "editlistfinal" },
	function(data){
		var datas = data.split(":");
		var i=0;
		var count_listid = datas.length;
		
		for (i=0;i<count_listid;i++) {
	
				if(datas[i]=="") {
						datas[i]=" ";
				}
		}
		
		if(datas[0]=="SUCCESS") {
			alert(data);
			location.reload();
		}
	});
}
</script>
<div align="center" style="font-weight:bold; color:#333; font-size:16px;"><?php echo lang('go_MODIFYINGROUP'); ?>: <?php echo $group_settings->group_id; ?></div>
<br />
<table id="ingroupTable" border=0 cellpadding="0" cellspacing="0" style="width:95%; color:#000; margin-left:auto; margin-right:auto;">
    <tr>
    	<td style="width:30px;border-top:0px;"><span class="ingroupTabs tabOn" id="tabSettings"><?php echo lang('go_Settings'); ?></span></td><td style="width:30px;border-top:0px;"><span class="ingroupTabs tabOff" id="tabAgents"><?php echo lang('go_Agents'); ?></span></td><td style="border-top:0px;width:90%;"><span id="searchUserSpan" style="float:right;display:none;"><span id="showAllUsers" style="display: none">[<?php echo lang('go_ClearSearch'); ?>]</span>&nbsp;<?=form_input('search_user',null,'id="search_user" size="15" maxlength="100" placeholder="Search User"'); ?>&nbsp;<img src="<?=base_url()."img/spotlight-black.png"; ?>" id="search_user_button" style="cursor: pointer;" /></span></td>
    </tr>
	<tr>
		<td colspan=3>
			<?=form_open('ingroups',array('id'=>'go_editingroup','name'=>'go_editingroup'),array('editlist'=>'editlist','editval'=>'','showvaledit'=>"{$group_settings->group_id}")) ?>
			<table id="tabSettings_div" border=0 cellpadding="3" cellspacing="3" style="width:100%;border: 1px solid #DDDEDF;border-radius: 0px 5px 0px 0px;padding-top:5px;">
				<tr>
					<td style="text-align:right;font-weight:bold;" nowrap><?php echo lang('go_Description_'); ?></td><td nowrap>&nbsp;<?=form_input('group_name',$group_settings->group_name,'id="group_name" size="30"'); ?></td>
				</tr>
				<tr>
					<td style="text-align:right;font-weight:bold;" nowrap><?php echo lang('go_Color'); ?>:</td><td nowrap>&nbsp;<?=form_input('group_color',$group_settings->group_color,'id="group_color" size="7" maxlength="7"'); ?></td>
				</tr>
				<tr>
					<td style="text-align:right;font-weight:bold;" nowrap><?php echo lang('go_Status_'); ?></td><td nowrap>&nbsp;<?=form_dropdown('active',array('N'=>'N','Y'=>'Y'),$group_settings->active,'id="active"'); ?></td>
				</tr>
				<tr>
					<td style="text-align:right;font-weight:bold;" nowrap><?php echo lang('go_WebForm_'); ?></td><td nowrap>&nbsp;<?=form_input('web_form_address',$group_settings->web_form_address,'id="web_form_address" size="70" maxlength="500"'); ?></td>
				</tr>
				<tr>
					<td style="text-align:right;font-weight:bold;" nowrap><?php echo lang('go_NextAgentCall'); ?>:</td><td nowrap>&nbsp;<?=form_dropdown('next_agent_call',array('random'=>'random','oldest_call_start'=>'oldest_call_start','oldest_call_finish'=>'oldest_call_finish','overall_user_level'=>'overall_user_level','inbound_group_rank'=>'inbound_group_rank','campaign_rank'=>'campaign_rank','fewest_calls'=>'fewest_calls','fewest_calls_campaign'=>'fewest_calls_campaign','longest_wait_time'=>'longest_wait_time','ring_all'=>'ring_all'),$group_settings->next_agent_call,'id="next_agent_call"'); ?></td>
				</tr>
				<tr>
					<?php
					$ctr = 99;
					while ($ctr >= -99)
					{
						if ($ctr > 0)
							$highORlow = "Higher";
						elseif ($ctr < 0)
							$highORlow = "Lower";
						else
							$highORlow = "Even";
						
						$queueArray[$ctr] = "$ctr - $highORlow";
						$ctr--;
					}
					?>
					<td style="text-align:right;font-weight:bold;" nowrap><?php echo lang('go_QueuePriority'); ?>:</td><td nowrap>&nbsp;<?=form_dropdown('queue_priority',$queueArray,$group_settings->queue_priority,'id="queue_priority"'); ?></td>
				</tr>
				<tr>
					<td style="text-align:right;font-weight:bold;" nowrap><?php echo lang('go_FronterDisplay'); ?>:</td><td nowrap>&nbsp;<?=form_dropdown('fronter_display',array('N'=>'N','Y'=>'Y'),$group_settings->fronter_display,'id="fronter_display"'); ?></td>
				</tr>
				<tr>
					<?php
					$scriptArray[''] = "NONE";
					foreach ($script_list as $script)
					{
						$scriptArray[$script->script_id] = "{$script->script_id} - {$script->script_name}";
					}
					?>
					<td style="text-align:right;font-weight:bold;" nowrap><?php echo lang('go_script'); ?>:</td><td nowrap>&nbsp;<?=form_dropdown('ingroup_script',$scriptArray,$group_settings->ingroup_script,'id="ingroup_script"'); ?></td>
				</tr>
				<tr>
					<td><span id="showAdvanceOptions" style="cursor:pointer;font-size:10px;color:#7A9E22;">[ <span id="plus_minus" style="white-space: pre;font-family: monospace;">+</span> <?php echo lang('go_ADVANCESETTINGS'); ?> ]</span></td>
					<td align=right><span onclick="editIngroup('<?php echo $group_settings->group_id; ?>')" style='font-size:12px;color:#7A9E22;cursor:pointer;'><?php echo lang('go_SUBMIT'); ?> &nbsp;</span></td>
				</tr>
				<tr>
					<td colspan="2">
						<?php
						echo "<TABLE width=\"95%\" class=\"tableadvace\" id=\"advanceid\">\n";
						echo "<tr class=trview><td align=right>".lang('go_OnHookRingTime').": </td><td align=left>".form_input('on_hook_ring_time',$group_settings->on_hook_ring_time,'id="on_hook_ring_time" size=5 maxlength=4')."</td></tr>\n";
						echo "<tr class=trview><td align=right>".lang('go_IgnoreListScriptOverride').": </td><td align=left>".form_dropdown('ignore_list_script_override',array('N'=>'N','Y'=>'Y'),$group_settings->ignore_list_script_override,'id="ignore_list_script_override"')."</td></tr>\n";
		 
						echo "<tr class=trview><td align=right>".lang('go_GetCallLaunch').": </td><td align=left>".form_dropdown('get_call_launch',array('NONE'=>'NONE','SCRIPT'=>'SCRIPT','WEBFORM'=>'WEBFORM','FORM'=>'FORM'),$group_settings->get_call_launch,'id="get_call_launch"')."</td></tr>";
		 
						echo "<tr class=trview><td align=right>".lang('go_TransferConfDTMF')." 1: </td><td align=left>".form_input('xferconf_a_dtmf',$group_settings->xferconf_a_dtmf,'id="xferconf_a_dtmf" size=20 maxlength=50')."</td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_TransferConfNumber')." 1: </td><td align=left>".form_input('xferconf_a_number',$group_settings->xferconf_a_number,'id="xferconf_a_number" size=20 maxlength=50')."</td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_TransferConfDTMF')." 2: </td><td align=left>".form_input('xferconf_b_dtmf',$group_settings->xferconf_b_dtmf,'id="xferconf_b_dtmf" size=20 maxlength=50')."</td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_TransferConfNumber')." 2: </td><td align=left>".form_input('xferconf_b_number',$group_settings->xferconf_b_number,'id="xferconf_b_number" size=20 maxlength=50')."</td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_TransferConfNumber')." 3: </td><td align=left>".form_input('xferconf_c_number',$group_settings->xferconf_c_number,'id="xferconf_c_number" size=20 maxlength=50')."</td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_TransferConfNumber')." 4: </td><td align=left>".form_input('xferconf_d_number',$group_settings->xferconf_d_number,'id="xferconf_d_number" size=20 maxlength=50')."</td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_TransferConfNumber')." 5: </td><td align=left>".form_input('xferconf_e_number',$group_settings->xferconf_e_number,'id="xferconf_e_number" size=20 maxlength=50')."</td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_TimerAction')." : </td><td align=left>".form_dropdown('timer_action',array('NONE'=>'NONE','D1_DIAL'=>'D1 DIAL','D2_DIAL'=>'D2 DIAL','D3_DIAL'=>'D3 DIAL','D4_DIAL'=>'D4 DIAL','D5_DIAL'=>'D5 DIAL','MESSAGE_ONLY'=>'MESSAGE ONLY','WEBFORM'=>'WEBFORM','HANGUP'=>'HANGUP','CALLMENU'=>'CALLMENU','EXTENSION'=>'EXTENSION','IN_GROUP'=>'IN GROUP'),$group_settings->timer_action,'id="timer_action"')."</td></tr>\n";
		
						echo "<tr class='trview timerAction' style='display:none;'><td align=right>".lang('go_TimerActionMessage')." : </td><td align=left>".form_input('timer_action_message',$group_settings->timer_action_message,'id="timer_action_message" size=50 maxlength=255')."</td></tr>\n";
		
						echo "<tr class='trview timerAction' style='display:none;'><td align=right>".lang('go_TimerActionSeconds')." : </td><td align=left>".form_input('timer_action_seconds',$group_settings->timer_action_seconds,'id="timer_action_seconds" size=10 maxlength=10')."</td></tr>\n";
		
						echo "<tr class='trview timerAction' style='display:none;'><td align=right>".lang('go_TimerActionDestination')." : </td><td align=left>".form_input('timer_action_destination',$group_settings->timer_action_destination,'id="timer_action_destination" size=25 maxlength=30')."</td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_DropCallSeconds')." : </td><td align=left>".form_input('drop_call_seconds',$group_settings->drop_call_seconds,'id="drop_call_seconds" size=5 maxlength=4')."</td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_DropAction')." : </td><td align=left>".form_dropdown('drop_action',array('HANGUP'=>'HANGUP','MESSAGE'=>'MESSAGE','VOICEMAIL'=>'VOICEMAIL','IN_GROUP'=>'IN GROUP','CALLMENU'=>'CALLMENU'),$group_settings->drop_action,'id="drop_action"')."</td></tr>\n";
		
						echo "<tr class='trview dropActionExten'><td align=right>".lang('go_DropExten')." : </td><td align=left>".form_input('drop_exten',$group_settings->drop_exten,'id="drop_exten" size=10 maxlength=20')."</td></tr>\n";
				
						echo "<tr class='trview dropActionVoicemail'><td align=right>".lang('go_Voicemail')." : </td><td align=left>".form_input('voicemail_ext',$group_settings->voicemail_ext,'id="voicemail_ext" size=12 maxlength=10')." <a href=\"javascript:launch_vm_chooser('voicemail_ext','vm',500,document.getElementById('voicemail_ext').value);\"><FONT color=\"blue\">[ ".lang('go_VoicemailChooser')." ]</a><div id=\"divvoicemail_ext\"></div></td></tr>\n";
		
						echo "<tr class='trview dropActionTransferGroup'><td align=right>".lang('go_DropTransferGroup')." : </td><td align=left>";
						echo form_dropdown('drop_inbound_group',$ingroupArray,$group_settings->drop_inbound_group,'id="drop_inbound_group" style="width:400px;"');
						echo "</td></tr>\n";
		
						echo "<tr class='trview dropActionCallMenu'><td align=right>".lang('go_DropCallMenu')." : </td><td align=left>";
						echo form_dropdown('drop_callmenu',$menuArray,$group_settings->drop_callmenu,'id="drop_callmenu" style="width:300px;"');
						echo "</td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_CallTime').": </td><td align=left>";
						echo form_dropdown('call_time_id',$timeArray,$group_settings->call_time_id,'id="call_time_id"');
						echo "</td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_AfterHoursAction')." : </td><td align=left>".form_dropdown('after_hours_action',array('HANGUP'=>'HANGUP','MESSAGE'=>'MESSAGE','EXTENSION'=>'EXTENSION','VOICEMAIL'=>'VOICEMAIL','IN_GROUP'=>'IN GROUP'),$group_settings->after_hours_action,'id="after_hours_action"')."</td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_AfterHoursMessageFilename')." : </td><td align=left>".form_input('after_hours_message_filename',$group_settings->after_hours_message_filename,'id="after_hours_message_filename" size=30 maxlength=255')." <a href=\"javascript:launch_chooser('after_hours_message_filename','date',600,document.getElementById('after_hours_message_filename').value);\"><FONT color=\"blue\"> ".lang('go_AudioChooser')." </font></a><div id=\"divafter_hours_message_filename\"></div> </td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_AfterHoursExtension')." : </td><td align=left>".form_input('after_hours_exten',$group_settings->after_hours_exten,'id="after_hours_exten" size=10 maxlength=20')."</td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_AfterHoursVoicemail')." : </td><td align=left>".form_input('after_hours_voicemail',$group_settings->after_hours_voicemail,'id="after_hours_voicemail" size=12 maxlength=10')." <a href=\"javascript:launch_vm_chooser('after_hours_voicemail','vm',700,document.getElementById('after_hours_voicemail').value);\"><FONT color=\"blue\">[ ".lang('go_VoicemailChooser')." ]</font></a><div id=\"divafter_hours_voicemail\"></div></td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_AfterHoursTransferGroup')." : </td><td align=left>";
						echo form_dropdown('afterhours_xfer_group',$ingroupArray,$group_settings->afterhours_xfer_group,'id="afterhours_xfer_group" style="width:400px;"');
						echo "</td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_NoAgentsNoQueueing')." : </td><td align=left>".form_dropdown('no_agent_no_queue',array('N'=>'N','Y'=>'Y','NO_PAUSED'=>'NO PAUSED'),$group_settings->no_agent_no_queue,'id="no_agent_no_queue"')."</td></tr>\n";
		
						echo "<tr class=trview><td align=right>".lang('go_NoAgentNoQueueAction')." : </td><td align=left>".form_dropdown('no_agent_action',array('CALLMENU'=>'CALLMENU','INGROUP'=>'INGROUP','DID'=>'DID','MESSAGE'=>'MESSAGE','EXTENSION'=>'EXTENSION','VOICEMAIL'=>'VOICEMAIL'),$group_settings->no_agent_action,'id="no_agent_action"')."</td></tr>\n";
						//put no agent action here
						echo "<tr><td colspan=2>";
						echo "<div class='noAgentActionTR'><table border=0 style='width:100%'><tr class=trview>";
						switch($group_settings->no_agent_action)
						{
							case "CALLMENU":
								echo "<td style='width:355px;' align=right>".lang('go_CallMenu')." : </td><td align=left>";
								echo form_dropdown('no_agent_action_value',$menuArray,$group_settings->no_agent_action_value,'id="no_agent_action_value" style="width:300px;"');
								echo "</td>";
								break;
							
							case "INGROUP":
								$no_agent_action_value = $group_settings->no_agent_action_value;
								if (strlen($no_agent_action_value) < 1)
									$no_agent_action_value = 'SALESLINE,CID,LB,998,TESTCAMP,1,,,,';
								$action_value = explode(',',$no_agent_action_value);
								echo "<td style='width:355px;' align=right>".lang('go_InGroup')." : </td><td align=left>";
								echo form_dropdown('',$ingroupArray,$action_value[0],'id="action_value_group_id" style="width:400px;"');
								echo "</td></tr>";
								echo "<tr class=trview><td align=right>".lang('go_HandleMethod')." : </td><td align=left>";
								echo form_dropdown('',$handleArray,$action_value[1],'id="action_value_handle_method"');
								echo "</td></tr>";
								echo "<tr class=trview><td align=right>".lang('go_SearchMethod').": </td><td align=left>";
								$seachArray = array('LB'=>'LB - Load Balance','LO'=>'LB - Load Balance Overflow','SO'=>'SO - Server Only');
								echo form_dropdown('',$seachArray,$action_value[2],'id="action_value_search_method"');
								echo "</td></tr>";
								echo "<tr class=trview><td align=right>".lang('go_ListID_')." </td><td align=left>";
								echo form_input('',$action_value[3],'id="action_value_list_id" size="5" maxlength="14"');
								echo "</td></tr>";
								echo "<tr class=trview><td align=right>".lang('go_CampaignID_')." </td><td align=left>";
								echo form_dropdown('',$campArray,$action_value[4],'id="action_value_campaign_id"');
								echo "</td></tr>";
								echo "<tr class=trview><td align=right>".lang('go_PhoneCode')." : </td><td align=left>";
								echo form_input('',$action_value[5],'id="action_value_phone_code" size="5" maxlength="14"');
								echo "</td></tr>";
								echo "<tr style='display:none;'><td align=right>".lang('go_Value')." : </td><td align=left>";
								echo form_input('no_agent_action_value',null,'id="no_agent_action_value"');
								echo "</td>";
								break;
							
							case "DID":
								echo "<td style='width:355px;' align=right>".lang('go_CallMenu')." : </td><td align=left>";
								echo form_dropdown('no_agent_action_value',$didArray,$group_settings->no_agent_action_value,'id="no_agent_action_value" style="width:400px;"');
								echo "</td>";
								break;
							
							case "MESSAGE":
								$no_agent_action_value = $group_settings->no_agent_action_value;
								if (strlen($no_agent_action_value) < 1)
									$no_agent_action_value = 'nbdy-avail-to-take-call|vm-goodbye';
								echo "<td style='width:355px;' align=right>".lang('go_AudioFile_')."</td><td align=left>";
								echo form_input('no_agent_action_value',$no_agent_action_value,'id="no_agent_action_value" size=40 maxlength=255');
								echo ' <a href="javascript:launch_chooser(\'no_agent_action_value\',\'date\',800,document.getElementById(\'no_agent_action_value\').value);"><FONT color="blue">[Audio Chooser]</font></a><div id="divno_agent_action_value"></div>';
								echo "</td>";
								break;
							
							case "EXTENSION":
								$no_agent_action_value = $group_settings->no_agent_action_value;
								if (strlen($no_agent_action_value) < 1)
									$no_agent_action_value = '8304,default';
								$action_value = explode(',',$no_agent_action_value);
								echo "<td style='width:355px;' align=right>".lang('go_Extension')." : </td><td align=left>";
								echo form_input('',$action_value[0],'id="action_value_extension" size=20 maxlength=255');
								echo "</td></tr>";
								echo "<tr class=trview><td style='width:355px;' align=right>".lang('go_Context').": </td><td align=left>";
								echo form_input('',$action_value[1],'id="action_value_context" size=20 maxlength=255');
								echo "</td>";
								echo "<tr style='display:none;'><td align=right>".lang('go_Value').": </td><td align=left>";
								echo form_input('no_agent_action_value',null,'id="no_agent_action_value"');
								echo "</td>";
								break;
							
							case "VOICEMAIL":
								$no_agent_action_value = $group_settings->no_agent_action_value;
								if (strlen($no_agent_action_value) < 1)
									$no_agent_action_value = '101';
								echo "<td style='width:355px;' align=right>".lang('go_VoicemailBox').": </td><td align=left>";
								echo form_input('no_agent_action_value',$no_agent_action_value,'id="no_agent_action_value" size=10 maxlength=12');
								echo ' <a href="javascript:launch_vm_chooser(\'no_agent_action_value\',\'date\',600,document.getElementById(\'no_agent_action_value\').value);"><FONT color="blue">[ '.lang('go_VoicemailChooser').' ]</font></a><div id="divno_agent_action_value"></div>';
								echo "</td>";
								break;
						}
						echo "</tr></table></div>";
						echo "</td></tr>\n";
						
						echo "<tr class=trview><td align=right>".lang('go_MaxCallsMethod')." : </td><td align=left>".form_dropdown('max__calls_method',array('TOTAL'=>'TOTAL','IN_QUEUE'=>'IN QUEUE','DISABLED'=>'DISABLED'),$group_settings->max_calls_method,'id="max_calls_method"')."</td></tr>\n";

						echo "<tr class='trview showMaxCallsMethod'><td align=right>".lang('go_MaxCallsCount')." : </td><td align=left>".form_input('max_calls_count',$group_settings->max_calls_count,'id="max_calls_count" size=5 maxlength=6')."</td></tr>\n";

						echo "<tr class='trview showMaxCallsMethod'><td align=right>".lang('go_MaxCallsAction')." : </td><td align=left>".form_dropdown('max_calls_action',array('DROP'=>'DROP','AFTERHOURS'=>'AFTERHOURS','NO_AGENT_NO_QUEUE'=>'NO AGENT NO QUEUE'),$group_settings->max_calls_action,'id="max_calls_action"')."</td></tr>\n";
						
						echo "<tr class=trview><td align=right>".lang('go_WelcomeMessageFilename')." : </td><td align=left>".form_input('welcome_message_filename',$group_settings->welcome_message_filename,'id="welcome_message_filename" size=30 maxlength=255')." <a href=\"javascript:launch_chooser('welcome_message_filename','date',800,document.getElementById('welcome_message_filename').value);\"><FONT color=\"blue\">".lang('go_AudioChooser')."</font></a> <div id=\"divwelcome_message_filename\"></div> </td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_PlayWelcomeMessage')." : </td><td align=left>".form_dropdown('play_welcome_message',array('ALWAYS'=>'ALWAYS','NEVER'=>'NEVER','IF_WAIT_ONLY'=>'IF WAIT ONLY','YES_UNLESS_NODELAY'=>'YES UNLESS NODELAY'),$group_settings->play_welcome_message,'id="play_welcome_message"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_MusicOnHoldContext')." : </td><td align=left>".form_input('moh_context',$group_settings->moh_context,'id="moh_context" size=30 maxlength=50')." <a href=\"javascript:launch_moh_chooser('moh_context','moh',800,document.getElementById('moh_context').value);\"><FONT color=\"blue\">[ Moh Chooser ]</font></a> <div id=\"divmoh_context\"></div></td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_OnHoldPromptFilename')." : </td><td align=left>".form_input('onhold_prompt_filename',$group_settings->onhold_prompt_filename,'id="onhold_prompt_filename" size=30 maxlength=255')." <a href=\"javascript:launch_chooser('onhold_prompt_filename','date',800,document.getElementById('onhold_prompt_filename').value);\"><FONT color=\"blue\">".lang('go_AudioChooser')."</font></a> <div id=\"divonhold_prompt_filename\"></div></td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_OnHoldPromptInterval')." : </td><td align=left>".form_input('prompt_interval',$group_settings->prompt_interval,'id="prompt_interval" size=5 maxlength=5')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_OnHoldPromptNoBlock')." : </td><td align=left>".form_dropdown('onhold_prompt_no_block',array('N'=>'N','Y'=>'Y'),$group_settings->onhold_prompt_no_block,'id="onhold_prompt_no_block"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_OnHoldPromptSeconds')." : </td><td align=left>".form_input('onhold_prompt_seconds',$group_settings->onhold_prompt_seconds,'id="onhold_prompt_seconds" size=5 maxlength=5')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_PlayPlaceinLine')." : </td><td align=left>".form_dropdown('play_place_in_line',array('N'=>'N','Y'=>'Y'),$group_settings->play_place_in_line,'id="play_place_in_line"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_PlayEstimatedHoldTime')." : </td><td align=left>".form_dropdown('play_estimate_hold_time',array('N'=>'N','Y'=>'Y'),$group_settings->play_estimate_hold_time,'id="play_estimate_hold_time"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_CalculateEstimatedHoldSeconds')." : </td><td align=left>".form_input('calculate_estimated_hold_seconds',$group_settings->calculate_estimated_hold_seconds,'id="calculate_estimated_hold_seconds" size=5 maxlength=5')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_EstimatedHoldTimeMinimumFilename')." : </td><td align=left>".form_input('eht_minimum_prompt_filename',$group_settings->eht_minimum_prompt_filename,'id="eht_minimum_prompt_filename" size=30 maxlength=255')." <a href=\"javascript:launch_chooser('eht_minimum_prompt_filename','date',800,document.getElementById('eht_minimum_prompt_filename').value);\"><FONT color=\"blue\">".lang('go_AudioChooser')."</font></a> <div id=\"diveht_minimum_prompt_filename\"></div> </td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_EstimatedHoldTimeMinimumPromptNoBlock')." : </td><td align=left>".form_dropdown('eht_minimum_prompt_no_block',array('N'=>'N','Y'=>'Y'),$group_settings->eht_minimum_prompt_no_block,'id="eht_minimum_prompt_no_block"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_EstimatedHoldTimeMinimumPromptSeconds')." : </td><td align=left>".form_input('eht_minimum_prompt_seconds',$group_settings->eht_minimum_prompt_seconds,'id="eht_minimum_prompt_seconds" size=5 maxlength=5')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_WaitTimeOption')." : </td><td align=left>".form_dropdown('wait_time_option',array('NONE'=>'NONE','PRESS_STAY'=>'PRESS STAY','PRESS_VMAIL'=>'PRESS VMAIL','PRESS_EXTEN'=>'PRESS EXTEN','PRESS_CALLMENU'=>'PRESS CALLMENU','PRESS_CID_CALLBACK'=>'PRESS CID CALLBACK','PRESS_INGROUP'=>'PRESS INGROUP'),$group_settings->wait_time_option,'id="wait_time_option"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_WaitTimeSecondOption')." : </td><td align=left>".form_dropdown('wait_time_second_option',array('NONE'=>'NONE','PRESS_STAY'=>'PRESS STAY','PRESS_VMAIL'=>'PRESS VMAIL','PRESS_EXTEN'=>'PRESS EXTEN','PRESS_CALLMENU'=>'PRESS CALLMENU','PRESS_CID_CALLBACK'=>'PRESS CID CALLBACK','PRESS_INGROUP'=>'PRESS INGROUP'),$group_settings->wait_time_second_option,'id="wait_time_second_option"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_WaitTimeThirdOption')." : </td><td align=left>".form_dropdown('wait_time_third_option',array('NONE'=>'NONE','PRESS_STAY'=>'PRESS STAY','PRESS_VMAIL'=>'PRESS VMAIL','PRESS_EXTEN'=>'PRESS EXTEN','PRESS_CALLMENU'=>'PRESS CALLMENU','PRESS_CID_CALLBACK'=>'PRESS CID CALLBACK','PRESS_INGROUP'=>'PRESS INGROUP'),$group_settings->wait_time_third_option,'id="wait_time_third_option"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_WaitTimeOptionSeconds')." : </td><td align=left>".form_input('wait_time_option_seconds',$group_settings->wait_time_option_seconds,'id="wait_time_option_seconds" size=5 maxlength=5')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_WaitTimeOptionExtension')." : </td><td align=left>".form_input('wait_time_option_exten',$group_settings->wait_time_option_exten,'id="wait_time_option_exten" size=20 maxlength=20')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_WaitTimeOptionCallmenu')." : </td><td align=left>";
						echo form_dropdown('wait_time_option_callmenu',$menuArray,$group_settings->wait_time_option_callmenu,'id="wait_time_option_callmenu" style="width:300px;"');
						echo "</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_WaitTimeOptionVoicemail')." : </td><td align=left>".form_input('wait_time_option_voicemail',$group_settings->wait_time_option_voicemail,'id="wait_time_option_voicemail" size=12 maxlength=10')." <a href=\"javascript:launch_vm_chooser('wait_time_option_voicemail','vm',1100,document.getElementById('wait_time_option_voicemail').value);\"><FONT color=\"blue\">[ ".lang('go_VoicemailChooser')." ]</font></a><div id=\"divwait_time_option_voicemail\"></div> </td></tr>\n";
						
						echo "<tr class=trview><td align=right>Wait Time Option Transfer In-Group: </td><td align=left>";
						echo form_dropdown('wait_time_option_xfer_group',$ingroupArray,$group_settings->wait_time_option_xfer_group,'id="wait_time_option_xfer_group" style="width:400px;"');
						echo "</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_WaitTimeOptionPressFilename')." : </td><td align=left>".form_input('wait_time_option_press_filename',$group_settings->wait_time_option_press_filename,'id="wait_time_option_press_filename" size=30 maxlength=255')." <a href=\"javascript:launch_chooser('wait_time_option_press_filename','date',1200,document.getElementById('wait_time_option_press_filename').value);\"><FONT color=\"blue\">".lang('go_AudioChooser')."</font></a> <div id=\"divwait_time_option_press_filename\"></div> </td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_WaitTimeOptionPressNoBlock')." : </td><td align=left>".form_dropdown('wait_time_option_no_block',array('N'=>'N','Y'=>'Y'),$group_settings->wait_time_option_no_block,'id="wait_time_option_no_block"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_WaitTimeOptionPressFilenameSeconds')." : </td><td align=left>".form_input('wait_time_option_prompt_seconds',$group_settings->wait_time_option_prompt_seconds,'id="wait_time_option_prompt_seconds" size=5 maxlength=5')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_WaitTimeOptionAfterPressFilename')." : </td><td align=left>".form_input('wait_time_option_callback_filename',$group_settings->wait_time_option_callback_filename,'id="wait_time_option_callback_filename" size=30 maxlength=255')." <a href=\"javascript:launch_chooser('wait_time_option_callback_filename','date',1300,document.getElementById('wait_time_option_callback_filename').value);\"><FONT color=\"blue\">".lang('go_AudioChooser')."</font></a> <div id=\"divwait_time_option_callback_filename\"></div></td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_WaitTimeOptionCallbackListID')." : </td><td align=left>".form_input('wait_time_option_callback_list_id',$group_settings->wait_time_option_callback_list_id,'id="wait_time_option_callback_list_id" size=14 maxlength=14')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_WaitHoldOptionPriority')." : </td><td align=left>".form_dropdown('wait_hold_option_priority',array('WAIT'=>'WAIT','BOTH'=>'BOTH'),$group_settings->wait_hold_option_priority,'id="wait_hold_option_priority"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_EstimatedHoldTimeOption')." : </td><td align=left>".form_dropdown('hold_time_option',array('NONE'=>'NONE','EXTENSION'=>'EXTENSION','CALL_MENU'=>'CALL_MENU','VOICEMAIL'=>'VOICEMAIL','IN_GROUP'=>'IN GROUP','CALLERID_CALLBACK'=>'CALLERID CALLBACK','DROP_ACTION'=>'DROP ACTION','PRESS_STAY'=>'PRESS STAY','PRESS_VMAIL'=>'PRESS VMAIL','PRESS_EXTEN'=>'PRESS EXTEN','PRESS_CALLMENU'=>'PRESS CALLMENU','PRESS_CID_CALLBACK'=>'PRESS CID CALLBACK','PRESS_INGROUP'=>'PRESS INGROUP'),$group_settings->hold_time_option,'id="hold_time_option"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_HoldTimeSecondOption')." : </td><td align=left>".form_dropdown('hold_time_second_option',array('NONE'=>'NONE','PRESS_STAY'=>'PRESS STAY','PRESS_VMAIL'=>'PRESS VMAIL','PRESS_EXTEN'=>'PRESS EXTEN','PRESS_CALLMENU'=>'PRESS CALLMENU','PRESS_CID_CALLBACK'=>'PRESS CID CALLBACK','PRESS_INGROUP'=>'PRESS INGROUP'),$group_settings->hold_time_second_option,'id="hold_time_second_option"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_HoldTimeThirdOption')." : </td><td align=left>".form_dropdown('hold_time_third_option',array('NONE'=>'NONE','PRESS_STAY'=>'PRESS STAY','PRESS_VMAIL'=>'PRESS VMAIL','PRESS_EXTEN'=>'PRESS EXTEN','PRESS_CALLMENU'=>'PRESS CALLMENU','PRESS_CID_CALLBACK'=>'PRESS CID CALLBACK','PRESS_INGROUP'=>'PRESS INGROUP'),$group_settings->hold_time_third_option,'id="hold_time_third_option"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_HoldTimeOptionSeconds')." : </td><td align=left>".form_input('hold_time_option_seconds',$group_settings->hold_time_option_seconds,'id="hold_time_option_seconds" size=5 maxlength=5')."</td></tr>\n";

						echo "<tr class=trview><td align=right>".lang('go_HoldTimeOptionMinimum')." : </td><td align=left>".form_input('hold_time_option_minimum',$group_settings->hold_time_option_minimum,'id="hold_time_option_minimum" size=5 maxlength=5')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_HoldTimeOptionExtension')." : </td><td align=left>".form_input('hold_time_option_exten',$group_settings->hold_time_option_exten,'id="hold_time_option_exten" size=20 maxlength=20')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_HoldTimeOptionCallmenu')." : </td><td align=left>";
						echo form_dropdown('hold_time_option_callmenu',$menuArray,$group_settings->hold_time_option_callmenu,'id="hold_time_option_callmenu" style="width:300px;"');
						echo "</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_HoldTimeOptionVoicemail')." : </td><td align=left>".form_input('hold_time_option_voicemail',$group_settings->hold_time_option_voicemail,'id="hold_time_option_voicemail" size=12 maxlength=10')." <a href=\"javascript:launch_vm_chooser('hold_time_option_voicemail','vm',1100,document.getElementById('hold_time_option_voicemail').value);\"><FONT color=\"blue\">[ Voicemail Chooser ]</font></a><div id=\"divhold_time_option_voicemail\"></div> </td></tr>\n";
						
						echo "<tr class=trview><td align=right>".lang('go_HoldTimeOptionTransferInGroup')." : </td><td align=left>";
						echo form_dropdown('hold_time_option_xfer_group',$ingroupArray,$group_settings->hold_time_option_xfer_group,'id="hold_time_option_xfer_group" style="width:400px;"');
						echo "</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_HoldTimeOptionPressFilename')." : </td><td align=left>".form_input('hold_time_option_press_filename',$group_settings->hold_time_option_press_filename,'id="hold_time_option_press_filename" size=30 maxlength=255')." <a href=\"javascript:launch_chooser('hold_time_option_press_filename','date',1200,document.getElementById('hold_time_option_press_filename').value);\"><FONT color=\"blue\"><FONT color=\"blue\">".lang('go_AudioChooser')."</font></a> <div id=\"divhold_time_option_press_filename\"></div></td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_HoldTimeOptionPressNoBlock')." : </td><td align=left>".form_dropdown('hold_time_option_no_block',array('N'=>'N','Y'=>'Y'),$group_settings->hold_time_option_no_block,'id="hold_time_option_no_block"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_HoldTimeOptionPressFilenameSeconds')." : </td><td align=left>".form_input('hold_time_option_prompt_seconds',$group_settings->hold_time_option_prompt_seconds,'id="hold_time_option_prompt_seconds" size=5 maxlength=5')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_HoldTimeOptionAfterPressFilename')." : </td><td align=left>".form_input('hold_time_option_callback_filename',$group_settings->hold_time_option_callback_filename,'id="hold_time_option_callback_filename" size=30 maxlength=255')." <a href=\"javascript:launch_chooser('hold_time_option_callback_filename','date',1300,document.getElementById('hold_time_option_callback_filename').value);\"><FONT color=\"blue\">".lang('go_AudioChooser')."</font></a><div id=\"divhold_time_option_callback_filename\"></div> </td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_HoldTimeOptionCallbackListID')." : </td><td align=left>".form_input('hold_time_option_callback_list_id',$group_settings->hold_time_option_callback_list_id,'id="hold_time_option_callback_list_id" size=14 maxlength=14')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_AgentAlertFilename')." : </td><td align=left>".form_input('agent_alert_exten',$group_settings->agent_alert_exten,'id="agent_alert_exten" size=30 maxlength=100')." <a href=\"javascript:launch_chooser('agent_alert_exten','date',1500,document.getElementById('agent_alert_exten').value);\"><FONT color=\"blue\">".lang('go_AudioChooser')."</font></a> <div id=\"divagent_alert_exten\"></div></td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_AgentAlertDelay')." : </td><td align=left>".form_input('agent_alert_delay',$group_settings->agent_alert_delay,'id="agent_alert_delay" size=6 maxlength=6')."</td></tr>\n";
						
						echo "<tr class=trview><td align=right>".lang('go_DefaultTransferInGroup')." : </td><td align=left>";
						echo form_dropdown('default_xfer_group',$ingroupArray,$group_settings->default_xfer_group,'id="default_xfer_group" style="width:400px;"');
						echo "</td></tr>\n";
						
						echo "<tr class=trview><td align=right>".lang('go_DefaultGroupAlias')." : </td><td align=left>";
						echo form_dropdown('default_group_alias',array(''=>'NONE'),$group_settings->default_group_alias,'id="default_group_alias"');
						echo "</td></tr>\n";
						
						echo "<tr class=trview><td align=right>".lang('go_DialInGroupCID')." : </td><td align=left>".form_input('dial_ingroup_cid',$group_settings->dial_ingroup_cid,'id="dial_ingroup_cid" size=20 maxlength=20')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_HoldRecallTransferInGroup')." : </td><td align=left>";
						echo form_dropdown('hold_recall_xfer_group',$ingroupArray,$group_settings->hold_recall_xfer_group,'id="hold_recall_xfer_group" style="width:400px;"');
						echo "</td></tr>\n";

						echo "<tr class=trview><td align=right>".lang('go_NoDelayCallRoute')." : </td><td align=left>".form_dropdown('no_delay_call_route',array('N'=>'N','Y'=>'Y'),$group_settings->no_delay_call_route,'id="no_delay_call_route"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_InGroupRecordingOverride')." : </td><td align=left>".form_dropdown('ingroup_recording_override',array('DISABLED'=>'DISABLED','NEVER'=>'NEVER','ONDEMAND'=>'ONDEMAND','ALLCALLS'=>'ALLCALLS','ALLFORCE'=>'ALLFORCE'),$group_settings->ingroup_recording_override,'id="ingroup_recording_override"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_InGroupRecordingFilename')." : </td><td align=left>".form_input('ingroup_rec_filename',$group_settings->ingroup_rec_filename,'id="ingroup_rec_filename" size=50 maxlength=50')."</td></tr>\n";
						
						echo "<tr class=trview><td align=right style='white-space:nowrap'>&nbsp;".lang('go_StatsPercentofCallsAnsweredWithinXseconds')." 1: </td><td align=left>".form_input('answer_sec_pct_rt_stat_one',$group_settings->answer_sec_pct_rt_stat_one,'id="answer_sec_pct_rt_stat_one" size=5 maxlength=5')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right style='white-space:nowrap'>&nbsp;".lang('go_StatsPercentofCallsAnsweredWithinXseconds')." 2: </td><td align=left>".form_input('answer_sec_pct_rt_stat_one',$group_settings->answer_sec_pct_rt_stat_one,'id="answer_sec_pct_rt_stat_one" size=5 maxlength=5')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_StartCallURL')." : </td><td align=left>".form_input('start_call_url',$group_settings->start_call_url,'id="start_call_url" size=60 maxlength=2000')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_DispoCallURL')." : </td><td align=left>".form_input('dispo_call_url',$group_settings->dispo_call_url,'id="dispo_call_url" size=60 maxlength=2000')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_AddLeadURL')." : </td><td align=left>".form_input('add_lead_url',$group_settings->add_lead_url,'id="add_lead_url" size=60 maxlength=2000')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_NoAgentCallURL')." : </td><td align=left>".form_input('na_call_url',$group_settings->na_call_url,'id="na_call_url" size=60 maxlength=2000')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_ExtensionAppendCID')." : </td><td align=left>".form_dropdown('extension_appended_cidname',array('N'=>'N','Y'=>'Y'),$group_settings->extension_appended_cidname,'id="extension_appended_cidname"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_UniqueidStatusDisplay')." : </td><td align=left>".form_dropdown('uniqueid_status_display',array('DISABLED'=>'DISABLED','ENABLED'=>'ENABLED','ENABLED_PREFIX'=>'ENABLED PREFIX','ENABLED_PRESERVE'=>'ENABLED PRESERVE'),$group_settings->uniqueid_status_display,'id="uniqueid_status_display"')."</td></tr>\n";
				
						echo "<tr class=trview><td align=right>".lang('go_UniqueidStatusPrefix')." : </td><td align=left>".form_input('uniqueid_status_prefix',$group_settings->uniqueid_status_prefix,'id="uniqueid_status_prefix" size=10 maxlength=50')."</td></tr>\n";
						
						echo "<tr><td><span style='font-size:10px;color:#7A9E22;cursor:pointer;' class='scrollTop'>TOP</span></td><td align=right><span onclick=\"editIngroup('{$group_settings->group_id}')\" style='font-size:12px;color:#7A9E22;cursor:pointer;'>".lang('go_SUBMIT')." &nbsp;</span></td></tr>\n";

						echo "</TABLE>";
						?>
					</td>
				</tr>
			</table>
			<?=form_close() ?>
			<form  method="POST" id="agentrankform" name="agentrankform">
			<table id="tabAgents_div" border=0 cellpadding="3" cellspacing="3" style="display: none;width:100%;border: 1px solid #DDDEDF;border-radius: 0px 5px 0px 0px;padding-top:5px;">
				<tr>
					<td id="agentRanks"><?=$agent_ranks ?></td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
<br />
<?php
}

if ($type=='did')
{
?>
<script>
$(function() {
	$("#edid_route").change(function() {
		switch ($(this).val()) {
			case "AGENT":
				$(".didAgentGroup").show();
				$(".didExtensionGroup,.didVoicemailGroup,.didPhoneGroup,.didInboundGroup,.didCallMenuGroup").hide();
				break;
			case "EXTEN":
				$(".didExtensionGroup").show();
				$(".didAgentGroup,.didVoicemailGroup,.didPhoneGroup,.didInboundGroup,.didCallMenuGroup").hide();
				break;
			case "VOICEMAIL":
				$(".didVoicemailGroup").show();
				$(".didAgentGroup,.didExtensionGroup,.didPhoneGroup,.didInboundGroup,.didCallMenuGroup").hide();
				break;
			case "PHONE":
				$(".didPhoneGroup").show();
				$(".didAgentGroup,.didVoicemailGroup,.didExtensionGroup,.didInboundGroup,.didCallMenuGroup").hide();
				break;
			case "IN_GROUP":
				$(".didInboundGroup").show();
				$(".didAgentGroup,.didVoicemailGroup,.didPhoneGroup,.didExtensionGroup,.didCallMenuGroup").hide();
				break;
			case "CALLMENU":
				$(".didCallMenuGroup").show();
				$(".didAgentGroup,.didVoicemailGroup,.didPhoneGroup,.didInboundGroup,.didExtensionGroup").hide();
				break;
		}
		
		if ($(".didAdvanceSettings").is(":visible")) {
			showAdvanceOptions($(this).val(),'show');
		}
	});
	
	$("#efilter_inbound_number").change(function() {
		var route = $("#efilter_action").val();
		switch ($(this).val()) {
			case "GROUP":
				$(".filterGroup").show();
				$(".filterAction").show();
				$(".filterURL").hide();
				showRouteOptions(route,'Filter');
				break;
			case "URL":
				$(".filterURL").show();
				$(".filterAction").show();
				$(".filterGroup").hide();
				showRouteOptions(route,'Filter');
				break;
			default:
				$(".filterGroup,.filterURL,.filterAction").hide();
				showRouteOptions('DISABLE','Filter');
		}
	});
	
	$("#advDIDLink").click(function()
	{
		var route = $('#edid_route').val();
		if ($(".didAdvanceSettings").is(":hidden"))
		{
			$('#advDIDLinkCross').html('-');
			$('.didAdvanceSettings').show();
			showAdvanceOptions(route,'show');
		} else {
			$('#advDIDLinkCross').html('+');
			$('.didAdvanceSettings').hide();
			showAdvanceOptions(route,'hide');
		}
	});
	
	if ('<?=$group_settings->did_id ?>' != "")
	{
		var did_route = '<?=$group_settings->did_route ?>';
		switch (did_route) {
			case "AGENT":
				$(".didAgentGroup").show();
				$(".didExtensionGroup,.didVoicemailGroup,.didPhoneGroup,.didInboundGroup,.didCallMenuGroup").hide();
				break;
			case "EXTEN":
				$(".didExtensionGroup").show();
				$(".didAgentGroup,.didVoicemailGroup,.didPhoneGroup,.didInboundGroup,.didCallMenuGroup").hide();
				break;
			case "VOICEMAIL":
				$(".didVoicemailGroup").show();
				$(".didAgentGroup,.didExtensionGroup,.didPhoneGroup,.didInboundGroup,.didCallMenuGroup").hide();
				break;
			case "PHONE":
				$(".didPhoneGroup").show();
				$(".didAgentGroup,.didVoicemailGroup,.didExtensionGroup,.didInboundGroup,.didCallMenuGroup").hide();
				break;
			case "IN_GROUP":
				$(".didInboundGroup").show();
				$(".didAgentGroup,.didVoicemailGroup,.didPhoneGroup,.didExtensionGroup,.didCallMenuGroup").hide();
				break;
			case "CALLMENU":
				$(".didCallMenuGroup").show();
				$(".didAgentGroup,.didVoicemailGroup,.didPhoneGroup,.didInboundGroup,.didExtensionGroup").hide();
				break;
		}
		
		if ($(".didAdvanceSettings").is(":visible")) {
			showAdvanceOptions('<?=$group_settings->did_route ?>','show');
		}
		
		//showRouteOptions('<?=$group_settings->did_route ?>','Group');
		//showRouteOptions('<?=$group_settings->filter_action ?>','Filter');
	}
});

function showAdvanceOptions(route, type) {
    if (type == "hide") {
		$(".didCallMenuAdvance,.didAgentAdvance,.didVoicemailAdvance,.didPhoneAdvance,.didInboundAdvance,.didExtensionAdvance").hide();
		$(".filterGroup,.filterURL,.filterAction").hide();
		showRouteOptions('DISABLED','Filter');
    } else {
		switch (route) {
			case "AGENT":
				$(".didAgentAdvance").show();
				$(".didInboundAdvance").hide();
				break;
			case "IN_GROUP":
				$(".didInboundAdvance").show();
				$(".didAgentAdvance").hide();
				break;
			default:
				$(".didAgentAdvance,.didInboundAdvance").hide();
		}
	
		var filterRoute = $("#efilter_inbound_number").val();
		showFilterOptions(filterRoute);
    }
}
	
function editpostdid(didid) {
	document.getElementById('didvals').value=didid;

	var itemsumit = $('#go_editdidfrm').serialize();
	$.post("<?=$base?>index.php/go_ingroup/editsubmit", { itemsumit: itemsumit, action: "editdidfinal" },
	function(data){
		var datas = data.split(":");
		var i=0;
		var count_listid = datas.length;
		
		for (i=0;i<count_listid;i++) {
				if(datas[i]=="") {
						datas[i]=" ";
				}
		}
		
		if(datas[0]=="SUCCESS") {
			alert(data);
			location.reload();
		}
	});
}
</script>
<div align="center" style="font-weight:bold; color:#333; font-size:16px;"><?php echo lang('goInbound_modifyDIDRecord'); ?> <?php echo $group_settings->did_id; ?></div>
<br>
<form  method="POST" id="go_editdidfrm" name="go_editdidfrm" method="POST">
	<input type="hidden" id="selectval" name="selectval" value="">
	<input type="hidden" id="editDID" name="editDID" value="editDID">	
	<input type="hidden" id="didvals" name="didvals">
<?php
	echo "<center><TABLE width='100%' style='color:#333;'>\n";
	echo "<tr><td align=right width='35%' style='font-weight:bold;'>".lang('goInbound_didExtension').": </td><td align=left><input type=text name=did_pattern id=edid_pattern size=30 maxlength=50 value='{$group_settings->did_pattern}' onkeydown=\"return isAlphaNumericwospace(event.keyCode);\" onkeyup=\"KeyUp(event.keyCode);\"></td></tr>\n";
	
	echo "<tr><td align=right style='font-weight:bold;'>".lang('goInbound_didDescription').": </td><td align=left><input type=text name=did_description id=edid_description size=40 maxlength=50 value='{$group_settings->did_description}' onkeydown=\"return isAlphaNumericwspace(event.keyCode);\" onkeyup=\"KeyUp(event.keyCode);\"></td></tr>\n";
	
	echo "<tr><td align=right style='font-weight:bold;'>".lang('goInbound_active').": </td><td align=left>".form_dropdown('did_active',array('N'=>'N','Y'=>'Y'),$group_settings->did_active,'id="edid_active"')."</td></tr>\n";
	
	//echo "<tr style='display:none'><td align=right>Record Call: </td><td align=left><select size=1 name=record_call id=erecord_call><option>N</option><option>Y_QUEUESTOP</option><option>Y</option></select></td></tr>\n";
	
	echo "<tr><td align=right style='font-weight:bold;'>".lang('goInbound_didRoute').": </td><td align=left>".form_dropdown('did_route',array('AGENT'=>'Agent','IN_GROUP'=>'In-group','PHONE'=>'Phone','CALLMENU'=>'Call Menu / IVR','VOICEMAIL'=>'Voicemail','EXTEN'=>'Custom Extension'),$group_settings->did_route,'id="edid_route"')."</td></tr>\n";
	
	echo "<tr class='trview didExtensionGroup' style='display:none'><td align=right>Extension: </td><td align=left><input type=text name=extension id=eextension size=40 maxlength=50 value='{$group_settings->extension}'></td></tr>\n";
	
	echo "<tr class='trview didExtensionGroup' style='display:none'><td align=right>Extension Context: </td><td align=left><input type=text name=exten_context id=eexten_context size=40 maxlength=50 value='{$group_settings->exten_context}'></td></tr>\n";
	
	echo "<tr class='trview didVoicemailGroup' style='display:none'><td align=right>Voicemail Box: </td><td align=left><input type=text name=voicemail_ext id=didvoicemail_ext size=12 maxlength=10 value='{$group_settings->voicemail_ext}'> <a href=\"javascript:launch_vm_chooser('didvoicemail_ext','vm',500,document.getElementById('didvoicemail_ext').value);\"><FONT color=\"blue\" size=\"1\">[ ".lang('go_VoicemailChooser')." ]</a><div id=\"divdidvoicemail_ext\"></div></td></tr>\n";
	
	echo "<tr class='trview didPhoneGroup' style='display:none'><td align=right>Phone Extension: </td><td align=left>";
	echo form_dropdown('phone',$phoneArray,$group_settings->phone,'id="ephone" style="width:400px;"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview didPhoneGroup' style='display:none'><td align=right>Server IP: </td><td align=left>\n";
	echo form_dropdown('server_ip',$serverArray,$group_settings->server_ip,'id="server_ip"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview didCallMenuGroup' style='display:none'><td align=right>Call Menu: </td><td align=left>";
	echo form_dropdown('menu_id',$menuArray,$group_settings->menu_id,'id="emenu_id" style="width:400px"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview didAgentGroup' style='display:none'><td align=right>".lang('goInbound_agentId').": </td><td align=left>";
	echo form_dropdown('user',$userArray,$group_settings->user,'id="euser"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview didAgentAdvance' style='display:none'><td align=right>".lang('goInbound_agentUnavailableAction').": </td><td align=left>";
	echo form_dropdown('user_unavailable_action',array('VOICEMAIL'=>'Voicemail','PHONE'=>'Phone','IN_GROUP'=>'In-group','EXTEN'=>'Custom Extension'),$group_settings->user_unavailable_action,'id="user_unavailable_action"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview didAgentAdvance' style='display:none'><td align=right>".lang('goInbound_agentRouteSettings').": </td><td align=left>";
	echo form_dropdown('user_route_settings_ingroup',$ingroupArray,$group_settings->user_route_settings_ingroup,'id="euser_route_settings_ingroup"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview didInboundGroup' style='display:none'><td align=right>In-Group ID: </td><td align=left>";
	echo form_dropdown('group_id',$ingroupArray,$group_settings->group_id,'id="egroup_id2" style="width:350px;"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview didInboundAdvance' style='display:none'><td align=right>In-Group Call Handle Method: </td><td align=left>";
	echo form_dropdown('call_handle_method',$handleArray,$group_settings->call_handle_method,'id="call_handle_method"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview didInboundAdvance' style='display:none'><td align=right>In-Group Agent Search Method: </td><td align=left>";
	echo form_dropdown('agent_search_method',array('LB'=>'LB - Load Balanced','LO'=>'LO -Load Balanced Overflow','SO'=>'SO -Server Only'),$group_settings->agent_search_method,'id="agent_search_method"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview didInboundAdvance' style='display:none'><td align=right>In-Group List ID: </td><td align=left><input type=text name=list_id id=elist_id size=14 maxlength=14 value=\"{$group_settings->list_id}\" ></td></tr>\n";
	
	echo "<tr class='trview didInboundAdvance' style='display:none'><td align=right>In-Group Campaign ID: </td><td align=left>\n";
	echo form_dropdown('campaign_id',$campArray,$group_settings->campaign_id,'id="ecampaign_id"');
	echo "</td></tr>\n";
	
	
	
	echo "<tr class='trview didInboundAdvance' style='display:none'><td align=right>In-Group Phone Code: </td><td align=left><input type=text name=phone_code id=ephone_code size=14 maxlength=14 value=\"{$group_settings->phone_code}\"></td></tr>\n";
	
	
	echo "<tr class='didAdvanceSettings' style='display:none'><td align=right>".lang('goInbound_cleanCIDNumber').": </td><td align=left><input type=text name=filter_clean_cid_number id=efilter_clean_cid_number size=20 maxlength=20 value=\"{$group_settings->filter_clean_cid_number}\"></td></tr>\n";
	
	echo "<tr class='didAdvanceSettings' style='display:none'><td align=right>".lang('goInbound_filterInboundNumber').": </td><td align=left>";
	echo form_dropdown('filter_inbound_number',array('DISABLED'=>'DISABLED','GROUP'=>'GROUP','URL'=>'URL'),$group_settings->filter_inbound_number,'id="efilter_inbound_number"');
	echo "</td></tr>\n";
	
	
	echo "<tr class='trview filterGroup' style='display:none'><td align=right>Filter Phone Group ID: </td><td align=left>";
	echo form_dropdown('filter_phone_group_id',$filterPhoneArray,$group_settings->filter_phone_group_id,'id="efilter_phone_group_id"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview filterURL' style='display:none'><td align=right>Filter URL: </td><td align=left><input type=text name=filter_url id=efilter_url size=30 maxlength=1000 value=\"{$group_settings->filter_url}\"></td></tr>\n";
	
	echo "<tr class='trview filterAction' style='display:none'><td align=right>Filter Action: </td><td align=left>";
	echo form_dropdown('filter_action',array('AGENT'=>'Agent','IN_GROUP'=>'In-group','PHONE'=>'Phone','CALLMENU'=>'Call Menu / IVR','VOICEMAIL'=>'Voicemail','EXTEN'=>'Custom Extension'),$group_settings->filter_action,'id="efilter_action" onchange="showRouteOptions(document.getElementById(\'efilter_action\').value,\'Filter\')"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview didExtensionFilter' style='display:none'><td align=right>Filter Extension: </td><td align=left><input type=text name=filter_extension id=efilter_extension size=40 maxlength=50 value=\"{$group_settings->filter_extension}\"></td></tr>\n";
	
	echo "<tr class='trview didExtensionFilter' style='display:none'><td align=right>Filter Extension Context: </td><td align=left><input type=text name=filter_exten_context id=efilter_exten_context size=40 maxlength=50 value=\"{$group_settings->filter_exten_context}\"></td></tr>\n";
	
	echo "<tr class='trview didVoicemailFilter' style='display:none'><td align=right>Filter Voicemail Box: </td><td align=left><input type=text name=filter_voicemail_ext id=efilter_voicemail_ext size=12 maxlength=10 value=\"{$group_settings->filter_voicemail_ext}\"> <a href=\"javascript:launch_vm_chooser('efilter_voicemail_ext','vm',500,document.getElementById('efilter_voicemail_ext').value);\"><FONT color=\"blue\" size=\"1\">[ ".lang('go_VoicemailChooser')." ]</a><div id=\"divefilter_voicemail_ext\"></div></td></tr>\n";
	
	echo "<tr class='trview didPhoneFilter' style='display:none'><td align=right>Filter Phone Extension: </td><td align=left><input type=text name=filter_phone id=efilter_phone size=20 maxlength=100 value=\"{$group_settings->filter_phone}\"></td></tr>\n";
	
	echo "<tr class='trview didPhoneFilter' style='display:none'><td align=right>Filter Server IP: </td><td align=left>\n";
	echo form_dropdown('filter_server_ip',$serverArray,$group_settings->filter_server_ip,'id="efilter_server_ip"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview didCallMenuFilter' style='display:none'><td align=right>Filter Call Menu: </td><td align=left>";
	echo form_dropdown('filter_menu_id',$menuArray,$group_settings->filter_menu_id,'id="efilter_menu_id" style="width:400px"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview didAgentFilter' style='display:none'><td align=right>Filter Agent ID: </td><td align=left><input type=text name=filter_user id=efilter_user size=20 maxlength=20 value=\"{$group_settings->filter_user}\"></td></tr>\n";
	
	echo "<tr class='trview didAgentFilter' style='display:none'><td align=right>Filter Agent Unavailable Action: </td><td align=left>";
	echo form_dropdown('filter_user_unavailable_action',array('VOICEMAIL'=>'Voicemail','PHONE'=>'Phone','IN_GROUP'=>'In-group','EXTEN'=>'Custom Extension'),$group_settings->filter_user_unavailable_action,'id="efilter_user_unavailable_action"');
	echo "</td></tr>\n";
		
	echo "<tr class='trview didAgentFilter' style='display:none'><td align=right style=\"white-space:nowrap;\">&nbsp;Filter Agent Route Settings In-Group: </td><td align=left>";
	echo form_dropdown('filter_user_route_settings_ingroup',$ingroupArray,$group_settings->filter_user_route_settings_ingroup,'id="efilter_user_route_settings_ingroup" style="width:350px;"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview didInboundFilter' style='display:none'><td align=right>Filter In-Group ID: </td><td align=left>";
	echo form_dropdown('filter_group_id',$ingroupArray,$group_settings->filter_group_id,'id="efilter_group_id" style="width:350px;"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview' style='display:none'><td align=right>Filter In-Group Call Handle Method: </td><td align=left>";
	echo form_dropdown('filter_call_handle_method',$handleArray,$group_settings->filter_call_handle_method,'id="efilter_call_handle_method"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview' style='display:none'><td align=right>Filter In-Group Agent Search Method: </td><td align=left>";
	echo form_dropdown('filter_agent_search_method',array('LB'=>'LB - Load Balanced','LO'=>'LO -Load Balanced Overflow','SO'=>'SO -Server Only'),$group_settings->filter_agent_search_method,'id="efilter_agent_search_method"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview didInboundFilter' style='display:none'><td align=right>Filter In-Group List ID: </td><td align=left><input type=text name=filter_list_id id=efilter_list_id size=14 maxlength=14 value=\"{$group_settings->filter_list_id}\"></td></tr>\n";
		
	echo "<tr class='trview didInboundFilter' style='display:none'><td align=right>Filter In-Group Campaign ID: </td><td align=left>\n";
	echo form_dropdown('filter_campaign_id',$campArray,$group_settings->filter_campaign_id,'id="efilter_campaign_id"');
	echo "</td></tr>\n";
	
	echo "<tr class='trview' style='display:none'><td align=right>Filter In-Group Phone Code: </td><td align=left><input type=text name=filter_phone_code id=efilter_phone_code size=14 maxlength=14 value=\"{$group_settings->filter_phone_code}\"></td></tr>\n";
	
	echo "<tr><td colspan=2></td></tr>";
	echo "<tr><td colspan=2></td></tr>";
	echo "<tr><td colspan=2></td></tr>";
	echo "<tr>";
	echo "<td align=\"right\" colspan=\"2\">";
	echo "<div style=\"text-align:left;padding: 15px 0;font-size: 10px;cursor: pointer;color: #7A9E22;\" id=\"advDIDLink\">[ <pre id=\"advDIDLinkCross\" style='display:inline;'>+</pre>". lang('goInbound_advanceSettings')." ]</div>";
	echo "<div style=\"border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;\" align=\"right\">";
?>
			<a id="searchcallhistory" style="cursor: pointer;" onclick="editpostdid('<?=$group_settings->did_id ?>');"><font color="#7A9E22"><?php echo lang('goInbound_saveSettings'); ?></font></a>
		</div>
	</td>			  
</tr>
</table>
</center>
</form>
<?php
}

if ($type=='ivr')
{
?>
<script>
$(function() {
	$("#submitCallMenuEdit").click(function()
	{
		if ($("#menuvals").val().length > 0)
		{
			editcallmenupost($("#menuvals").val());
		} else {
			alert('<? echo $this->lang->line('go_menu_id_should_nempty'); ?>');
		}
	});
	
	$("#finishCallMenuEdit").click(function(){
		$('.advance_settings').hide();
		$('#advance_link').html('[ + <? echo $this->lang->line('go_adv_settings'); ?> ]');
		$('#box').animate({'top':'-3550px'},500);
		$('#overlay').fadeOut('slow');
		$("html, body").animate({ scrollTop: 0 }, 500);

		if ($('#request').text() == 'showList' || $('#request').text() == 'showDIDs' || $('#request').text() == 'showIVRs')
		{
		    $("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		    $('#table_reports').load('<? echo $base; ?>index.php/go_ingroup/go_ingroup_list/');
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

function checkoptionval(option,ctr)
{
	var isExist = 0;
	if (option.length > 0)
	{
		for (x=0;x<15;x++)
		{
			if (option == $('#option_value_'+x).val() && x != ctr)
			{
				$('#option_value_'+x).css('border','1px solid #FF0000');
				isExist = 1;
			} else {
				if (isExist && x != ctr)
				{
					$('#option_value_'+x).attr('disabled','true');
				}
				else if (isExist && x == ctr)
				{
					$('#option_value_'+x).css('border','1px solid #FF0000');
				}
				else
				{
					$('#option_value_'+x).removeAttr('disabled');
					$('#option_value_'+x).css('border','1px solid #DFDFDF');
				}
			}
		}
	}
	
	if (isExist)
	{
		alert('<? echo $this->lang->line('go_option_sel_already_use'); ?>');
	}
}
	
function editcallmenupost(callmenu)
{
	var itemsubmit = $('#go_editcallmenufrm').serialize();
	$.post("<?=$base?>index.php/go_ingroup/editsubmit", { itemsubmit: itemsubmit, action: "editcallmenufinal" },
	function(data){
		var datas = data.split(":");
		var i=0;
		var count_listid = datas.length;
		
		if(data.indexOf("SUCCESS") !== -1){
			alert(datas);
			modify('ivr',callmenu);
		}
		
		for (i=0;i<count_listid;i++) {
			if(datas[i]=="") {
				datas[i]=" ";
			}
		}
	});
}
</script>
<div align="center" style="font-weight:bold; color:#333; font-size:16px;"><? echo $this->lang->line('go_modify_callmenu'); ?>: <?php echo $group_settings->menu_id; ?></div>
<br>
<form  method="POST" id="go_editcallmenufrm" name="go_editcallmenufrm" method="POST">
	<input type="hidden" id="menuvals" name="menuvals" value="<?=$group_settings->menu_id ?>">
	<input type="hidden" id="selectval" name="selectval" value="">
	<input type="hidden" id="editCALLMENU" name="editCALLMENU" value="editCALLMENU">	
	<table class="tableedit" width="100%" >
		<tr>
			<td valign="top" colspan="2" align="center">
				<table width="100%" style="color:#333">
					<tr>
						<td style="white-space: nowrap;text-align: right;font-weight:bold;"><? echo $this->lang->line('go_menu_id'); ?>: </td>
						<td style="padding: 5px 0 5px 3px;">
							<?=$group_settings->menu_id ?>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;text-align: right;font-weight:bold;"><? echo $this->lang->line('go_menu_name'); ?>: </td>
						<td>
							<?=form_input('menu_name',$group_settings->menu_name,'id="edit_menu_name" size="30" maxlength="100"'); ?>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;text-align: right;font-weight:bold;"><? echo $this->lang->line('go_menu_prompt'); ?>: </td>
						<td>
							<?=form_input('menu_prompt',$group_settings->menu_prompt,'id="edit_menu_prompt" size="30" maxlength="255"'); ?> 
							<a href="javascript:launch_chooser('edit_menu_prompt','date',1200,document.getElementById('edit_menu_prompt').value,1);"><font color="blue" size="1">[ <? echo $this->lang->line('go_audio_chooser'); ?> ]</font></a>
						</td>
					</tr>
					<tr style="display:none;" id="tbledit_menu_prompt">
						<td style="white-space: nowrap;">&nbsp;</td>
						<td>
							<div id="divedit_menu_prompt"></div>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;text-align: right;font-weight:bold;"><? echo $this->lang->line('go_menu_timeout'); ?>: </td>
						<td>
							<?=form_input('menu_timeout',$group_settings->menu_timeout,'id="edit_menu_timeout" size="5" maxlength="5"'); ?>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;text-align: right;font-weight:bold;"><? echo $this->lang->line('go_menu_timeout_prompt'); ?>: </td>
						<td>
							<?=form_input('menu_timeout_prompt',$group_settings->menu_timeout_prompt,'id="edit_menu_timeout_prompt" size="30" maxlength="255"'); ?> 
							<a href="javascript:launch_chooser('edit_menu_timeout_prompt','date',1200,document.getElementById('edit_menu_timeout_prompt').value,1);"><font color="blue" size="1">[ <? echo $this->lang->line('go_audio_chooser'); ?> ]</font></a>
						</td>
					</tr>
					<tr style="display:none;" id="tbledit_menu_timeout_prompt">
						<td style="white-space: nowrap;">&nbsp;</td>
						<td>
							<div id="divedit_menu_timeout_prompt"></div>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;text-align: right;font-weight:bold;"><? echo $this->lang->line('go_menu_invalid_prompt'); ?>: </td>
						<td>
							<?=form_input('menu_invalid_prompt',$group_settings->menu_invalid_prompt,'id="edit_menu_invalid_prompt" size="30" maxlength="255"'); ?> 
							<a href="javascript:launch_chooser('edit_menu_invalid_prompt','date',1200,document.getElementById('edit_menu_invalid_prompt').value,1);"><font color="blue" size="1">[ <? echo $this->lang->line('go_audio_chooser'); ?> ]</font></a>
						</td>
					</tr>
					<tr style="display:none;" id="tbledit_menu_invalid_prompt">
						<td style="white-space: nowrap;">&nbsp;</td>
						<td>
							<div id="divedit_menu_invalid_prompt"></div>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;text-align: right;font-weight:bold;"><? echo $this->lang->line('go_menu_repeat'); ?>: </td>
						<td>
							<?=form_input('menu_repeat',$group_settings->menu_repeat,'id="edit_menu_repeat" size="3" maxlength="3"'); ?>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;text-align: right;font-weight:bold;"><? echo $this->lang->line('go_menu_time_check'); ?>: </td>
						<td>
							<?php
							$options = array('0 - No Time Check','1 - Time Check');
							echo form_dropdown('menu_time_check',$options,$group_settings->menu_time_check,'id="edit_menu_time_check"');
							?>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;text-align: right;font-weight:bold;"><? echo $this->lang->line('go_call_time'); ?>: </td>
						<td>
							<?php
							echo form_dropdown('call_time_id',$timeArray,$group_settings->call_time_id,'id="edit_call_time_id"');
							?>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;text-align: right;font-weight:bold;"><? echo $this->lang->line('go_track_calls_real_time_report'); ?>: </td>
						<td>
							<?php
							$options = array('0 - No Realtime Tracking','1 - Realtime Tracking');
							echo form_dropdown('track_in_vdac',$options,$group_settings->track_in_vdac,'id="edit_track_in_vdac"');
							?>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;text-align: right;font-weight:bold;"><? echo $this->lang->line('go_tracking_group'); ?>: </td>
						<td>
							<?php
							unset($ingroupArray['---NONE---']);
							$ingroupMerge = array_merge(array('CALLMENU'=>'CALLMENU - default'),$ingroupArray);
							echo form_dropdown('tracking_group',$ingroupMerge,$group_settings->tracking_group,'id="edit_tracking_group" style="width:400px;"');
							?>
						</td>
					</tr>
					<tr>
						<td style="white-space: nowrap;text-align: right;font-weight:bold;"><? echo $this->lang->line('go_custom_dialplan_entry'); ?>: </td>
						<td>
							<textarea id="edit_custom_dialplan_entry" name="custom_dialplan_entry" cols="60" rows="5" style="resize: none"><?=$group_settings->custom_dialplan_entry ?></textarea>
						</td>
					</tr>
					<tr><td colspan="2" style="font-size:6px;">&nbsp;</td></tr>
					<tr class="trview">
						<td colspan="2" style="white-space: nowrap;text-align: center;font-weight:bold;padding: 5px 0 5px 3px;"><? echo $this->lang->line('go_call_menu_options_'); ?></td>
					</tr>
					<tr>
						<td colspan="2">
							<div id="tblcallmenuoptions"><?=$call_menu_options ?></div>
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
				</table>																	
			</td>
		</tr>
		<tr>
			<td align="right" colspan="4">
				<div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">
				<a id="submitCallMenuEdit" style="cursor: pointer;"><font color="#7A9E22"><? echo $this->lang->line('go_save'); ?></font></a> | <a id="finishCallMenuEdit" style="cursor: pointer;"><font color="#7A9E22"><? echo $this->lang->line('go_finish'); ?></font></a>
				</div>		
						
			</td>			  
		</tr>
			
	</table>
</form>
<?php
}
?>
