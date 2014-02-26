<?php
#############################################################################################
####  Name:             go_calltimes_view.php                                            ####
####  Type:             ci views - calltimes modify                                      ####
####  Version:          3.0                                                              ####
####  Build:            1373515200                                                       #### 
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community ####
####  Written by:       Christopher Lomuntad <chris@goautodial.com>                      ####
####  License:          AGPLv2                                                           ####
#############################################################################################
$base = base_url();
?>
<script>
$(function()
{
    $('.toolTip').tipTip();
	
	// Submit Settings
	$('#saveCalltimes').click(function()
	{
		var isEmpty = 0;

		if(!isEmpty)
		{
			var items = $('#modifyCalltimes').serialize();
			var calltime_id = $("#call_time_id").val();
			$.post("<?=$base?>index.php/go_calltimes_ce/go_get_calltimes/update/"+calltime_id, { items: items, action: "update" },
				function(data){
				if (data=="SUCCESS")
				{
					alert("Success!\n\nCall time '<?=$calltime_info->call_time_id ?>' has been modified.");
					
					$('#box').animate({'top':'-2550px'},500);
					$('#overlay').fadeOut('slow');
					
					location.reload();
				} else {
					alert(data);
				}

			});
		}
	});
	
	$('#saveStateCalltimes').click(function()
	{
		var isEmpty = 0;

		if(!isEmpty)
		{
			var items = $('#modifyCalltimes').serialize();
			var calltime_id = $("#state_call_time_id").val();
			$.post("<?=$base?>index.php/go_calltimes_ce/go_get_calltimes/update/"+calltime_id+"/state/", { items: items, action: "update" },
				function(data){
				if (data=="SUCCESS")
				{
					alert("Success!\n\nState call time '<?=$calltime_info->state_call_time_id ?>' has been modified.");
					
					$('#box').animate({'top':'-2550px'},500);
					$('#overlay').fadeOut('slow');
					
					location.reload();
				} else {
					alert(data);
				}

			});
		}
	});
	
	$("#ct_default_start,#ct_sunday_start,#ct_monday_start,#ct_tuesday_start,#ct_wednesday_start,#ct_thursday_start,#ct_friday_start,#ct_saturday_start").datetimepicker({
		timeOnly: true,
		timeOnlyTitle: "Choose Start Time",
		timeText: "&nbsp;Time",
		hourText: "&nbsp;Hour",
		minuteText: "&nbsp;Minute",
		timeFormat: "Hmm",
		pickerTimeFormat: "HH:mm",
		hourMax: 24,
		stepMinute: 10,
		addSliderAccess: true,
		sliderAccessArgs: { touchonly: false }
	});
	
	$("#ct_default_stop,#ct_sunday_stop,#ct_monday_stop,#ct_tuesday_stop,#ct_wednesday_stop,#ct_thursday_stop,#ct_friday_stop,#ct_saturday_stop").datetimepicker({
		timeOnly: true,
		timeOnlyTitle: "Choose Stop Time",
		timeFormat: "Hmm",
		pickerTimeFormat: "HH:mm",
		hourMax: 24,
		stepMinute: 10,
		addSliderAccess: true,
		sliderAccessArgs: { touchonly: false }
	});
	
	$("#ct_default_start,#ct_sunday_start,#ct_monday_start,#ct_tuesday_start,#ct_wednesday_start,#ct_thursday_start,#ct_friday_start,#ct_saturday_start,#ct_default_stop,#ct_sunday_stop,#ct_monday_stop,#ct_tuesday_stop,#ct_wednesday_stop,#ct_thursday_stop,#ct_friday_stop,#ct_saturday_stop").change(function()
	{
		var pTime = $(".ui_tpicker_time").text().split(":");
		if (pTime[0] < 1 && pTime[1] < 1) {
			$(this).val("0");
		}
		
		if (pTime[0] < 1 && pTime[1] > 0) {
			$(this).val(pTime[1]);
		}
		
		if (pTime[0] > 23) {
			$(".ui_tpicker_minute,.ui_tpicker_minute_label").hide();
			$(".ui_tpicker_time").text(pTime[0]+':00');
			$(this).val(pTime[0]+"00");
		} else {
			$(".ui_tpicker_minute,.ui_tpicker_minute_label").show();
		}
	});
	
	$("#submit_rule").click(function()
	{
		var calltime_id = $("#call_time_id").val();
		var state_rule = $("#state_rule").val();
		
		if (state_rule.length > 0)
		{
			$.post("<?=$base?>index.php/go_calltimes_ce/go_get_calltimes/update/"+calltime_id+"/state_rule/"+state_rule,
				function(data){
				if (data=="SUCCESS")
				{
					alert("Success!\n\nState call time rule '"+state_rule+"' has been added.");
					
					$("#overlayContentCalltimes").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
					$('#overlayContentCalltimes').fadeOut("slow").load('<?php echo $base; ?>index.php/go_calltimes_ce/go_get_calltimes/view/'+calltime_id).fadeIn("slow");
				} else {
					alert(data);
				}
	
			});
		} else {
			alert("Please select a state call time rule.");
		}
	});

	$('.selectAudio').click(function()
	{
		$('#fileOverlay').fadeIn('fast');
		$('#fileBox').css({'width': '600px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '20px'});
		$('#fileBox').animate({
			top: "80px"
		}, 500);

		$('#audioButtonClicked').text($(this).attr('id'));
	});

	$('#fileClosebox').click(function()
	{
		$('#fileBox').animate({'top':'-2550px'},500);
		$('#fileOverlay').fadeOut('slow');
	});

	$('#closebox').click(function()
	{
		$('#box').animate({'top':'-2550px'},500);
		$('#overlay').fadeOut('slow');
	});
});

function delCalltime(cid,sid)
{
	var r=confirm("Are you sure you want to delete '"+sid+"' from the list?");
	if (r)
	{
		$.post("<?=$base?>index.php/go_calltimes_ce/go_get_calltimes/delete/"+cid+"/state_rule/"+sid,
			function(data){
			if (data=="SUCCESS")
			{
				alert("Success!\n\nState call time rule '"+sid+"' has been deleted.");
				
				$("#overlayContentCalltimes").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
				$('#overlayContentCalltimes').fadeOut("slow").load('<?php echo $base; ?>index.php/go_calltimes_ce/go_get_calltimes/view/'+cid).fadeIn("slow");
			} else {
				alert(data);
			}
	
		});
	}
}
</script>
<style>
.buttons,.selectAudio,.otherLinks {
	color:#7A9E22;
	cursor:pointer;
}

.buttons:hover{
	font-weight:bold;
}

.selectAudio {
	font-size:11px;
}

.selectAudio:hover,.otherLinks:hover {
	text-decoration:none;
}

.corner-all{
    border-width:1.5px;
    border-style:solid;
    border-color:#dfdfdf;
    -moz-border-radius:6px;
    -khtml-border-radius:6px;
    -webkit-border-radius:6px;
    border-radius:6px;
    padding:3px;
    background-color: #fff;
}
</style>
<div align="center" style="font-weight:bold; color:#333; font-size:16px; text-transform: uppercase;">MODIFY <?=(strlen($if_state) > 0) ? "$if_state " : ""; ?>CALL TIME: <?=(strlen($if_state) > 0) ? "{$calltime_info->state_call_time_id}" : "{$calltime_info->call_time_id}"; ?></div>
<br />
<form id="modifyCalltimes" method="POST">
<?php
if ($if_state!="state")
{
?>
<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:95%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
		<td style="text-align:right;width:25%;height:10px;font-weight:bold;">
		Call Time ID:
		</td>
		<td>
		&nbsp;<?=$calltime_info->call_time_id ?>
        <input type="hidden" id="call_time_id" value="<?=$calltime_info->call_time_id?>" name="call_time_id">
		<span id="aloading"></span>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:25%;height:10px;font-weight:bold;">
		Call Time Name:
		</td>
		<td>
		<?=form_input('call_time_name',$calltime_info->call_time_name,'id="call_time_name" maxlength="30" size="30"') ?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
		Call Time Comments:
		</td>
		<td>
		<?=form_input('call_time_comments',$calltime_info->call_time_comments,'id="call_time_comments" maxlength="255" size="40"') ?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:30%;height:10px;font-weight:bold;">
		User Group:
		</td>
		<td>
		<?php
		$groupArray = array("---ALL---"=>"ALL USER GROUPS");
		foreach ($user_groups as $group)
		{
			$groupArray[$group->user_group] = "{$group->user_group} - {$group->group_name}";
		}
		echo form_dropdown('user_group',$groupArray,$calltime_info->user_group,'id="user_group"');
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="0" cellpadding="0" cellspacing="0" style="width:95%; margin-left:auto; margin-right:auto;">
				<tr>
					<td style="font-weight:bold;width: 10%;">&nbsp;</td>
					<td style="font-weight:bold;">START</td>
					<td style="font-weight:bold;">STOP</td>
					<td style="font-weight:bold;">AFTER HOURS AUDIO</td>
				</tr>
				<tr>
					<td style="text-align: right;white-space: nowrap;">Default: &nbsp; &nbsp; </td>
					<td><?=form_input('ct_default_start',$calltime_info->ct_default_start,'id="ct_default_start" maxlength="4" size="5"') ?></td>
					<td><?=form_input('ct_default_stop',$calltime_info->ct_default_stop,'id="ct_default_stop" maxlength="4" size="5"') ?></td>
					<td style="width: 20%;white-space: nowrap;"><?=form_input('default_afterhours_filename_override',$calltime_info->default_afterhours_filename_override,'id="default_afterhours_filename_override" maxlength="255" size="30"') ?> <a class="selectAudio" id="default">[audio chooser]</a></td>
				</tr>
				<tr>
					<td style="text-align: right;white-space: nowrap;">Sunday: &nbsp; &nbsp; </td>
					<td><?=form_input('ct_sunday_start',$calltime_info->ct_sunday_start,'id="ct_sunday_start" maxlength="4" size="5"') ?></td>
					<td><?=form_input('ct_sunday_stop',$calltime_info->ct_sunday_stop,'id="ct_sunday_stop" maxlength="4" size="5"') ?></td>
					<td style="width: 20%;white-space: nowrap;"><?=form_input('sunday_afterhours_filename_override',$calltime_info->sunday_afterhours_filename_override,'id="sunday_afterhours_filename_override" maxlength="255" size="30"') ?> <a class="selectAudio" id="sunday">[audio chooser]</a></td>
				</tr>
				<tr>
					<td style="text-align: right;white-space: nowrap;">Monday: &nbsp; &nbsp; </td>
					<td><?=form_input('ct_monday_start',$calltime_info->ct_monday_start,'id="ct_monday_start" maxlength="4" size="5"') ?></td>
					<td><?=form_input('ct_monday_stop',$calltime_info->ct_monday_stop,'id="ct_monday_stop" maxlength="4" size="5"') ?></td>
					<td style="width: 20%;white-space: nowrap;"><?=form_input('monday_afterhours_filename_override',$calltime_info->monday_afterhours_filename_override,'id="monday_afterhours_filename_override" maxlength="255" size="30"') ?> <a class="selectAudio" id="monday">[audio chooser]</a></td>
				</tr>
				<tr>
					<td style="text-align: right;white-space: nowrap;">Tuesday: &nbsp; &nbsp; </td>
					<td><?=form_input('ct_tuesday_start',$calltime_info->ct_tuesday_start,'id="ct_tuesday_start" maxlength="4" size="5"') ?></td>
					<td><?=form_input('ct_tuesday_stop',$calltime_info->ct_tuesday_stop,'id="ct_tuesday_stop" maxlength="4" size="5"') ?></td>
					<td style="width: 20%;white-space: nowrap;"><?=form_input('tuesday_afterhours_filename_override',$calltime_info->tuesday_afterhours_filename_override,'id="tuesday_afterhours_filename_override" maxlength="255" size="30"') ?> <a class="selectAudio" id="tuesday">[audio chooser]</a></td>
				</tr>
				<tr>
					<td style="text-align: right;white-space: nowrap;">Wednesday: &nbsp; &nbsp; </td>
					<td><?=form_input('ct_wednesday_start',$calltime_info->ct_wednesday_start,'id="ct_wednesday_start" maxlength="4" size="5"') ?></td>
					<td><?=form_input('ct_wednesday_stop',$calltime_info->ct_wednesday_stop,'id="ct_wednesday_stop" maxlength="4" size="5"') ?></td>
					<td style="width: 20%;white-space: nowrap;"><?=form_input('wednesday_afterhours_filename_override',$calltime_info->wednesday_afterhours_filename_override,'id="wednesday_afterhours_filename_override" maxlength="255" size="30"') ?> <a class="selectAudio" id="wednesday">[audio chooser]</a></td>
				</tr>
				<tr>
					<td style="text-align: right;white-space: nowrap;">Thursday: &nbsp; &nbsp; </td>
					<td><?=form_input('ct_thursday_start',$calltime_info->ct_thursday_start,'id="ct_thursday_start" maxlength="4" size="5"') ?></td>
					<td><?=form_input('ct_thursday_stop',$calltime_info->ct_thursday_stop,'id="ct_thursday_stop" maxlength="4" size="5"') ?></td>
					<td style="width: 20%;white-space: nowrap;"><?=form_input('thursday_afterhours_filename_override',$calltime_info->thursday_afterhours_filename_override,'id="thursday_afterhours_filename_override" maxlength="255" size="30"') ?> <a class="selectAudio" id="thursday">[audio chooser]</a></td>
				</tr>
				<tr>
					<td style="text-align: right;white-space: nowrap;">Friday: &nbsp; &nbsp; </td>
					<td><?=form_input('ct_friday_start',$calltime_info->ct_friday_start,'id="ct_friday_start" maxlength="4" size="5"') ?></td>
					<td><?=form_input('ct_friday_stop',$calltime_info->ct_friday_stop,'id="ct_friday_stop" maxlength="4" size="5"') ?></td>
					<td style="width: 20%;white-space: nowrap;"><?=form_input('friday_afterhours_filename_override',$calltime_info->friday_afterhours_filename_override,'id="friday_afterhours_filename_override" maxlength="255" size="30"') ?> <a class="selectAudio" id="friday">[audio chooser]</a></td>
				</tr>
				<tr>
					<td style="text-align: right;white-space: nowrap;">Saturday: &nbsp; &nbsp; </td>
					<td><?=form_input('ct_saturday_start',$calltime_info->ct_saturday_start,'id="ct_saturday_start" maxlength="4" size="5"') ?></td>
					<td><?=form_input('ct_saturday_stop',$calltime_info->ct_saturday_stop,'id="ct_saturday_stop" maxlength="4" size="5"') ?></td>
					<td style="width: 20%;white-space: nowrap;"><?=form_input('saturday_afterhours_filename_override',$calltime_info->saturday_afterhours_filename_override,'id="saturday_afterhours_filename_override" maxlength="255" size="30"') ?> <a class="selectAudio" id="saturday">[audio chooser]</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
    	<td><td style="text-align:right;"><span id="saveCalltimes" class="buttons">SAVE SETTINGS</span><!--<input id="saveSettings" type="submit" value=" SAVE SETTINGS " style="cursor:pointer;" />--></td>
    </tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="0" cellpadding="0" cellspacing="0" style="width:100%; margin-left:auto; margin-right:auto;">
				<tr>
					<td style="font-weight:bold;text-align: center;" colspan="3">ACTIVE STATE CALL TIME FOR THIS RECORD</td>
				</tr>
				<tr>
					<td style="font-weight:bold;white-space:nowrap;">&nbsp;&nbsp;STATE CALL TIME ID</td>
					<td style="font-weight:bold;white-space:nowrap;">&nbsp;&nbsp;&nbsp;STATE CALL TIME DEFINITION</td>
					<td style="font-weight:bold;white-space:nowrap;">&nbsp;</td>
				</tr>
				<?php
				$x=0;
				if (strlen($calltime_info->ct_state_call_times) > 0)
				{
					$ct_state_call_times = explode("|",trim($calltime_info->ct_state_call_times,"|"));
					sort($ct_state_call_times);
					foreach($ct_state_call_times as $state)
					{
						if ($x==0) {
							$bgcolor = "#E0F8E0";
							$x=1;
						} else {
							$bgcolor = "#EFFBEF";
							$x=0;
						}
						$state_calltime_info = $this->go_calltimes->go_get_calltimes_info($state,'state');
						echo "<tr style='background-color:$bgcolor;'>\n";
						echo "<td style='border-top:#D0D0D0 dashed 1px;white-space:nowrap;'>&nbsp;&nbsp;<span class='otherLinks' onclick=\"modify('$state_calltime_info->state_call_time_id','state')\">{$state_calltime_info->state_call_time_id}</span></td>";
						echo "<td style='border-top:#D0D0D0 dashed 1px;white-space:nowrap;'>&nbsp;&nbsp;&nbsp;{$state_calltime_info->state_call_time_state} - {$state_calltime_info->state_call_time_name}</td>";
						echo "<td style='border-top:#D0D0D0 dashed 1px;text-align:right;'><span onclick=\"delCalltime('{$calltime_info->call_time_id}','{$state_calltime_info->state_call_time_id}')\" style='cursor:pointer;margin:5px;' class='toolTip' title='DELETE STATE CALLTIME<br />{$state_calltime_info->state_call_time_id}<br />FROM THIS RECORD'><img src='{$base}img/delete.png' style='cursor:pointer;width:12px;' /></span>&nbsp;</td>";
						echo "</tr>\n";
					}
				}
				?>
				<tr>
					<td colspan="3" style="font-size:5px;">&nbsp;</td>
				</tr>
				<tr>
					<td style="white-space:nowrap;width:20%;">&nbsp;&nbsp;ADD STATE CALL TIME RULE:</td>
					<td style="white-space:nowrap;width:20%;">&nbsp;&nbsp;
						<?php
						$state_call_time_list = $this->go_calltimes->go_get_calltimes_list('state',true);
						$states = array(''=>'--- Select a State Call Time Rule ---');
						$regex = (strlen($calltime_info->ct_state_call_times) > 0) ? "/".trim($calltime_info->ct_state_call_times,"|")."/" : "";
						foreach ($state_call_time_list['list'] as $state)
						{
							if (!preg_match($regex,$state->state_call_time_id))
								$states[$state->state_call_time_id] = "{$state->state_call_time_id} - {$state->state_call_time_name}";
						}
						
						echo form_dropdown('state_rule',$states,null,'id="state_rule"');
						?>
					</td>
					<td style="white-space:nowrap;text-align:right;">
						<span id="submit_rule" class="buttons">ADD RULE</span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php
} else {
?>
<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:95%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
		<td style="text-align:right;width:25%;height:10px;font-weight:bold;">
		State Call Time ID:
		</td>
		<td>
		&nbsp;<?=$calltime_info->state_call_time_id ?>
        <input type="hidden" id="state_call_time_id" value="<?=$calltime_info->state_call_time_id?>" name="state_call_time_id">
		<span id="aloading"></span>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:25%;height:10px;font-weight:bold;">
		State Call Time State:
		</td>
		<td>
		<?=form_input('state_call_time_state',$calltime_info->state_call_time_state,'id="state_call_time_state" maxlength="2" size="3"') ?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:25%;height:10px;font-weight:bold;">
		State Call Time Name:
		</td>
		<td>
		<?=form_input('state_call_time_name',$calltime_info->state_call_time_name,'id="state_call_time_name" maxlength="30" size="30"') ?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
		State Call Time Comments:
		</td>
		<td>
		<?=form_input('state_call_time_comments',$calltime_info->state_call_time_comments,'id="state_call_time_comments" maxlength="255" size="40"') ?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:30%;height:10px;font-weight:bold;">
		User Group:
		</td>
		<td>
		<?php
		$groupArray = array("---ALL---"=>"ALL USER GROUPS");
		foreach ($user_groups as $group)
		{
			$groupArray[$group->user_group] = "{$group->user_group} - {$group->group_name}";
		}
		echo form_dropdown('user_group',$groupArray,$calltime_info->user_group,'id="user_group"');
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="0" cellpadding="0" cellspacing="0" style="width:50%; margin-left:auto; margin-right:auto;">
				<tr>
					<td style="font-weight:bold;width: 10%;">&nbsp;</td>
					<td style="font-weight:bold;">START</td>
					<td style="font-weight:bold;">STOP</td>
				</tr>
				<tr>
					<td style="text-align: right;white-space: nowrap;">Default: &nbsp; &nbsp; </td>
					<td><?=form_input('sct_default_start',$calltime_info->sct_default_start,'id="ct_default_start" maxlength="4" size="5"') ?></td>
					<td><?=form_input('sct_default_stop',$calltime_info->sct_default_stop,'id="ct_default_stop" maxlength="4" size="5"') ?></td>
				</tr>
				<tr>
					<td style="text-align: right;white-space: nowrap;">Sunday: &nbsp; &nbsp; </td>
					<td><?=form_input('sct_sunday_start',$calltime_info->sct_sunday_start,'id="ct_sunday_start" maxlength="4" size="5"') ?></td>
					<td><?=form_input('sct_sunday_stop',$calltime_info->sct_sunday_stop,'id="ct_sunday_stop" maxlength="4" size="5"') ?></td>
				</tr>
				<tr>
					<td style="text-align: right;white-space: nowrap;">Monday: &nbsp; &nbsp; </td>
					<td><?=form_input('sct_monday_start',$calltime_info->sct_monday_start,'id="ct_monday_start" maxlength="4" size="5"') ?></td>
					<td><?=form_input('sct_monday_stop',$calltime_info->sct_monday_stop,'id="ct_monday_stop" maxlength="4" size="5"') ?></td>
				</tr>
				<tr>
					<td style="text-align: right;white-space: nowrap;">Tuesday: &nbsp; &nbsp; </td>
					<td><?=form_input('sct_tuesday_start',$calltime_info->sct_tuesday_start,'id="ct_tuesday_start" maxlength="4" size="5"') ?></td>
					<td><?=form_input('sct_tuesday_stop',$calltime_info->sct_tuesday_stop,'id="ct_tuesday_stop" maxlength="4" size="5"') ?></td>
				</tr>
				<tr>
					<td style="text-align: right;white-space: nowrap;">Wednesday: &nbsp; &nbsp; </td>
					<td><?=form_input('sct_wednesday_start',$calltime_info->sct_wednesday_start,'id="ct_wednesday_start" maxlength="4" size="5"') ?></td>
					<td><?=form_input('sct_wednesday_stop',$calltime_info->sct_wednesday_stop,'id="ct_wednesday_stop" maxlength="4" size="5"') ?></td>
				</tr>
				<tr>
					<td style="text-align: right;white-space: nowrap;">Thursday: &nbsp; &nbsp; </td>
					<td><?=form_input('sct_thursday_start',$calltime_info->sct_thursday_start,'id="ct_thursday_start" maxlength="4" size="5"') ?></td>
					<td><?=form_input('sct_thursday_stop',$calltime_info->sct_thursday_stop,'id="ct_thursday_stop" maxlength="4" size="5"') ?></td>
				</tr>
				<tr>
					<td style="text-align: right;white-space: nowrap;">Friday: &nbsp; &nbsp; </td>
					<td><?=form_input('sct_friday_start',$calltime_info->sct_friday_start,'id="ct_friday_start" maxlength="4" size="5"') ?></td>
					<td><?=form_input('sct_friday_stop',$calltime_info->sct_friday_stop,'id="ct_friday_stop" maxlength="4" size="5"') ?></td>
				</tr>
				<tr>
					<td style="text-align: right;white-space: nowrap;">Saturday: &nbsp; &nbsp; </td>
					<td><?=form_input('sct_saturday_start',$calltime_info->sct_saturday_start,'id="ct_saturday_start" maxlength="4" size="5"') ?></td>
					<td><?=form_input('sct_saturday_stop',$calltime_info->sct_saturday_stop,'id="ct_saturday_stop" maxlength="4" size="5"') ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
    	<td><td style="text-align:right;"><span id="saveStateCalltimes" class="buttons">SAVE SETTINGS</span><!--<input id="saveSettings" type="submit" value=" SAVE SETTINGS " style="cursor:pointer;" />--></td>
    </tr>
</table>
<?php
}
?>
</form>
<br />
<?php
if ($if_state!="state")
{
?>
<table border="0" cellpadding="0" cellspacing="0" style="color: #000;margin-left: auto;margin-right: auto;">
	<tr>
		<td colspan="2" style="font-weight:bold;text-align:center;">CAMPAIGNS USING THIS CALL TIME</td>
	</tr>
	<?php
	if (count($using_calltime['camp']) > 0)
	{
		$x=0;
		foreach($using_calltime['camp'] as $list)
		{
			if ($x==0) {
				$bgcolor = "#E0F8E0";
				$x=1;
			} else {
				$bgcolor = "#EFFBEF";
				$x=0;
			}
			echo "<tr style='background-color:$bgcolor;'>\n";
			echo "<td style='border-top:#D0D0D0 dashed 1px;white-space:nowrap;'>&nbsp;&nbsp;<a href='#top'class='otherLinks' onclick=\"modifyCampaign('$list->campaign_id')\">{$list->campaign_id}</a>&nbsp;&nbsp;</td>\n";
			echo "<td style='border-top:#D0D0D0 dashed 1px;white-space:nowrap;'>&nbsp;&nbsp;{$list->campaign_name}&nbsp;&nbsp;</td>\n";
			echo "</tr>\n";
		}
	}
	?>
</table>
<br />
<table border="0" cellpadding="0" cellspacing="0" style="color: #000;margin-left: auto;margin-right: auto;">
	<tr>
		<td colspan="2" style="font-weight:bold;text-align:center;">INBOUND GROUPS USING THIS CALL TIME</td>
	</tr>
	<?php
	if (count($using_calltime['inb']) > 0)
	{
		$x=0;
		foreach($using_calltime['inb'] as $list)
		{
			if ($x==0) {
				$bgcolor = "#E0F8E0";
				$x=1;
			} else {
				$bgcolor = "#EFFBEF";
				$x=0;
			}
			echo "<tr style='background-color:$bgcolor;'>\n";
			echo "<td style='border-top:#D0D0D0 dashed 1px;white-space:nowrap;'>&nbsp;&nbsp;<a href=\"#top\" class='otherLinks' onclick=\"modifyInbound('$list->group_id')\">{$list->group_id}</a>&nbsp;&nbsp;</td>\n";
			echo "<td style='border-top:#D0D0D0 dashed 1px;white-space:nowrap;'>&nbsp;&nbsp;{$list->group_name}&nbsp;&nbsp;</td>\n";
			echo "</tr>\n";
		}
	}
	?>
</table>
<?php
} else {
?>
<table border="0" cellpadding="0" cellspacing="0" style="color: #000;margin-left: auto;margin-right: auto;">
	<tr>
		<td colspan="2" style="font-weight:bold;text-align:center;">&nbsp; CALL TIMES USING THIS STATE CALL TIME &nbsp;</td>
	</tr>
	<?php
	if (count($using_calltime['list']) > 0)
	{
		$x=0;
		foreach($using_calltime['list'] as $list)
		{
			if ($x==0) {
				$bgcolor = "#E0F8E0";
				$x=1;
			} else {
				$bgcolor = "#EFFBEF";
				$x=0;
			}
			echo "<tr style='background-color:$bgcolor;'>\n";
			echo "<td style='border-top:#D0D0D0 dashed 1px;white-space:nowrap;'>&nbsp;&nbsp;<a href='#top'class='otherLinks' onclick=\"modify('$list->call_time_id')\">{$list->call_time_id}</a>&nbsp;&nbsp;</td>\n";
			echo "<td style='border-top:#D0D0D0 dashed 1px;white-space:nowrap;'>&nbsp;&nbsp;{$list->call_time_name}&nbsp;&nbsp;</td>\n";
			echo "</tr>\n";
		}
	}
	?>
</table>
<?php
}
?>
<br style="font-size:9px;" />
<br style="font-size:9px;" />
