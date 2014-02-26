<?php
####################################################################################################
####  Name:             	go_tenant_view.php                                                  ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                                 ####
####  Build:            	1375243200                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####      	                <community@goautodial.com>                                          ####
####  Written by:       	Christopher Lomuntad                                                ####
####  License:          	AGPLv2                                                              ####
####################################################################################################
$base = base_url();

foreach ($admin_list as $admin)
{
	$admin_array[$admin->user] = "{$admin->user} - {$admin->full_name}";
}

if (! $isAdvance)
	$isAdvance = 0;
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
	
	// Submit Settings
	$('#saveSettings').click(function()
	{
		var items = $('#modifyTenant').serialize();
		$.post("<?=$base?>index.php/go_tenants_ce/go_tenants_wizard", { items: items, action: "modify_tenant" },
		function(data){
			if (data=="SUCCESS")
			{
				alert(data);
			
				$('#box').animate({'top':'-2550px'},500);
				$('#overlay').fadeOut('slow');
				
				location.reload();
			}

		});

		//$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_modify_settings/'+campaign_id+'/modify/'+basicArray+'/'+advArray+'/'+isAdvance+'/'+allowed_inbgrp+'/'+notallowed_inbgrp).fadeIn("slow");
		//$('#table_reports').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/').fadeIn("slow");
	});
	
	// Pagination
	$('#agentList').tablePagination({rowsPerPage: 10, optionsForRows: [10, 25, 50, 100, "ALL"]});
	$('#campList').tablePagination({rowsPerPage: 10, optionsForRows: [10, 25, 50, 100, "ALL"]});
	$('#listIDs').tablePagination({rowsPerPage: 10, optionsForRows: [10, 25, 50, 100, "ALL"]});
	$('#phonesList').tablePagination({rowsPerPage: 10, optionsForRows: [10, 25, 50, 100, "ALL"]});

	// Table Sorter
	$("#agentList").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
	$("#campList").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
	$("#listIDs").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
	$("#phonesList").tablesorter({sortList:[[0,0]], widgets: ['zebra']});

	// For Campaigns
	$('.selectAudio').click(function()
	{
		$('#fileOverlay').fadeIn('fast');
		$('#fileBox').css({'width': '300px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '20px'});
		$('#fileBox').animate({
			top: "60px"
		}, 500);

		$('#audioButtonClicked').text($(this).attr('id'));
	});

	$('#fileClosebox').click(function()
	{
		$('#fileBox').animate({'top':'-2550px'},500);
		$('#fileOverlay').fadeOut('slow');
	});
});
</script>
<style>
.buttons,.otherLinks {
	color:#7A9E22;
	cursor:pointer;
}

.buttons:hover{
	font-weight:bold;
}

#agentList th, #campList th, #listIDs th, #phonesList th {
	text-align: left;
}
</style>
<div align="center" style="font-weight:bold; color:#333; font-size:16px;">MODIFY TENANT: <?php echo "{$tenant_info['tenant_id']}"; ?></div>
<br />
<form id="modifyTenant" method="POST">
<?=form_hidden('tenant_id',$tenant_info['tenant_id']) ?>
<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:90%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
    	<td style="text-align:right;" nowrap>Tenant Name:</td><td><?=form_input('tenant_name',$tenant_info['tenant_name'],'size="30" maxlength="40"') ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Status:</td><td><?=form_dropdown('active',array('N'=>'INACTIVE','Y'=>'ACTIVE'),$tenant_info['active']) ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Super Admin:</td><td><?=form_dropdown('admin',$admin_array,$tenant_info['tenant_name']) ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Number of Admins:</td><td>&nbsp;<?=$tenant_info['admin_cnt'] ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Number of Agents:</td><td>&nbsp;<?=$tenant_info['agent_cnt'] ?></td>
    </tr>
<?php
if ($this->config->item('VARSERVTYPE') == "public") {
?>
	<tr>
    	<td style="text-align:right;" nowrap>Remaining Balance:</td><td>&nbsp;<?="{$tenant_info['negative']}\${$tenant_info['balance']}" ?></td>
    </tr>
<?php
}
?>
	<tr style="display:none;">
    	<td style="text-align:right;" nowrap>Access Call Times:</td><td><?=form_dropdown('access_call_times',array('Y'=>'Yes','N'=>'No'),$tenant_info['access_call_times']) ?></td>
    </tr>
	<tr style="display:none;">
    	<td style="text-align:right;" nowrap>Access Carriers:</td><td><?=form_dropdown('access_carriers',array('Y'=>'Yes','N'=>'No'),$tenant_info['access_carriers']) ?></td>
    </tr>
	<tr style="display:none;">
    	<td style="text-align:right;" nowrap>Access Phones:</td><td><?=form_dropdown('access_phones',array('Y'=>'Yes','N'=>'No'),$tenant_info['access_phones']) ?></td>
    </tr>
	<tr style="display:none;">
    	<td style="text-align:right;" nowrap>Access Voicemails:</td><td><?=form_dropdown('access_voicemails',array('Y'=>'Yes','N'=>'No'),$tenant_info['access_voicemails']) ?></td>
    </tr>
	<tr>
    	<td>&nbsp;</td><td>&nbsp;</td>
    </tr>
	<tr>
    	<td><span id="advance_link" style="cursor:pointer;font-size:9px;display:none;">[ + ADVANCE SETTINGS ]</span><input type="hidden" id="isAdvance" value="0" /></td><td style="text-align:right;"><span id="saveSettings" class="buttons">SAVE SETTINGS</span><!--<input id="saveSettings" type="submit" value=" SAVE SETTINGS " style="cursor:pointer;" />--></td>
    </tr>
</table>
</form>
<br />
<table border=0 cellpadding="0" cellspacing="0" style="width:100%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
		<td colspan="3" style="font-weight:bold;text-align:center;">LIST OF AGENTS</td>
	</tr>
</table>
<table id="agentList" border=0 class="tablesorter" cellpadding="0" cellspacing="0" style="width:100%; color:#000; margin-left:auto; margin-right:auto;">
	<thead>
		<tr style="font-weight:bold;">
			<th>&nbsp;AGENT ID</th>
			<th>&nbsp;AGENT NAME</th>
			<th>&nbsp;STATUS</th>
		</tr>
	</thead>
	<tbody>
	<?php
	if (count($agent_list) > 0)
	{
		$x=0;
		foreach ($agent_list as $agent)
		{
			$class = ($x&1) ? "odd" : "even";
			$active = ($agent->active == 'Y') ? "green;'>ACTIVE" : "red;'>INACTIVE";
			echo "<tr class='$class'>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;width:20%;'>&nbsp;<a class='otherLinks' onclick=\"modifyAgentTenant('$agent->user')\">{$agent->user}</a></td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$agent->full_name}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;width:15%;'>&nbsp;<span style='font-weight:bold;color:{$active}</span></td>";
			echo "</tr>";
			$x++;
		}
	} else {
			echo "<tr class='$class'>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;color:red;font-weight:bold;text-align:center;' colspan='3'>No record(s) found.</td>";
			echo "</tr>";
	}
	?>
	</tbody>
</table>
<br />
<table id="campList" border=0 class="tablesorter" cellpadding="0" cellspacing="0" style="width:100%; color:#000; margin-left:auto; margin-right:auto;">
	<thead>
		<tr style="font-weight:bold;">
			<th>&nbsp;CAMPAIGN ID</th>
			<th>&nbsp;CAMPAIGN NAME</th>
			<th>&nbsp;STATUS</th>
		</tr>
	</thead>
	<tbody>
	<?php
	if (count($camp_list) > 0)
	{
		$x=0;
		foreach ($camp_list as $camp)
		{
			$class = ($x&1) ? "odd" : "even";
			$active = ($camp->active == 'Y') ? "green;'>ACTIVE" : "red;'>INACTIVE";
			echo "<tr class='$class'>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;width:20%;'>&nbsp;<a class='otherLinks' onclick=\"modifyCampaignTenant('$camp->campaign_id')\">{$camp->campaign_id}</a></td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$camp->campaign_name}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;width:15%;'>&nbsp;<span style='font-weight:bold;color:{$active}</span></td>";
			echo "</tr>";
			$x++;
		}
	} else {
			echo "<tr class='$class'>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;color:red;font-weight:bold;text-align:center;' colspan='3'>No record(s) found.</td>";
			echo "</tr>";
	}
	?>
	</tbody>
</table>
<br />
<table border=0 cellpadding="0" cellspacing="0" style="width:100%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
		<td colspan="3" style="font-weight:bold;text-align:center;">LIST IDS WITHIN THIS TENANT</td>
	</tr>
</table>
<table id="listIDs" border=0 class="tablesorter" cellpadding="0" cellspacing="0" style="width:100%; color:#000; margin-left:auto; margin-right:auto;">
	<thead>
		<tr style="font-weight:bold;">
			<th>&nbsp;LIST ID</th>
			<th>&nbsp;LIST NAME</th>
			<th>&nbsp;STATUS</th>
		</tr>
	</thead>
	<tbody>
	<?php
	if (count($list_ids) > 0)
	{
		$x=0;
		foreach ($list_ids as $list)
		{
			$class = ($x&1) ? "odd" : "even";
			$active = ($list->active == 'Y') ? "green;'>ACTIVE" : "red;'>INACTIVE";
			echo "<tr class='$class'>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;width:20%;'>&nbsp;<a class='otherLinks' onclick=\"modifyListIDTenant('$list->list_id')\">{$list->list_id}</a></td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->list_name}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;width:15%;'>&nbsp;<span style='font-weight:bold;color:{$active}</span></td>";
			echo "</tr>";
			$x++;
		}
	} else {
			echo "<tr class='$class'>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;color:red;font-weight:bold;text-align:center;' colspan='3'>No record(s) found.</td>";
			echo "</tr>";
	}
	?>
	</tbody>
</table>
<br />
<table border=0 cellpadding="0" cellspacing="0" style="width:100%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
		<td colspan="3" style="font-weight:bold;text-align:center;">PHONES EXTEN USED BY THIS TENANT</td>
	</tr>
</table>
<table id="phonesList" border=0 class="tablesorter" cellpadding="0" cellspacing="0" style="width:100%; color:#000; margin-left:auto; margin-right:auto;">
	<thead>
		<tr style="font-weight:bold;">
			<th>&nbsp;EXTENSION</th>
			<th>&nbsp;PROTOCOL</th>
			<th>&nbsp;SERVER IP</th>
			<th>&nbsp;STATUS</th>
		</tr>
	</thead>
	<tbody>
	<?php
	if (count($phones) > 0)
	{
		$x=0;
		foreach ($phones as $phone)
		{
			$class = ($x&1) ? "odd" : "even";
			$active = ($phone->active == 'Y') ? "green;'>ACTIVE" : "red;'>INACTIVE";
			echo "<tr class='$class'>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;width:20%;'>&nbsp;<a class='otherLinks' onclick=\"modifyPhonesTenant('$phone->extension')\">{$phone->extension}</a></td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$phone->protocol}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$phone->server_ip}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;width:15%;'>&nbsp;<span style='font-weight:bold;color:{$active}</span></td>";
			echo "</tr>";
			$x++;
		}
	} else {
			echo "<tr class='$class'>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;color:red;font-weight:bold;text-align:center;' colspan='4'>No record(s) found.</td>";
			echo "</tr>";
	}
	?>
	</tbody>
</table>
<br style="font-size:9px;" />