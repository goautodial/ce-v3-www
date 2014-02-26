<?php
############################################################################################
####  Name:             go_campaign_settings.php                                        ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();

if (! $isAdvance)
	$isAdvance = 0;

$camp_script_list[''] = "--- NONE ---";
foreach ($campscripts as $script)
{
	$camp_script_list[$script->script_id] = "{$script->script_id} - {$script->script_name}";
}

if ($campinfo->campaign_vdad_exten=="8366" || $campinfo->campaign_vdad_exten=="8373") {
	$isSurvey = true;
	$camp_vdad_option ="<option value=\"8366\">OFF</option><option value=\"8373\">ON</option>";
} else {
	$isSurvey = false;
	$camp_vdad_option ="<option value=\"8368\">OFF</option><option value=\"8369\">ON</option>";
}

foreach ($campcalltimes as $call_times)
{
	$local_call_times .= '<option value="'.$call_times->call_time_id.'">'.$call_times->call_time_id.' - '.$call_times->call_time_name.'</option>';
}

for ($i=1;$i<=20;$i+=.5)
{
	$num = number_format($i,1);
	$auto_dial_num .= "<option>$num</option>";
}

foreach ($dial_statuses as $status)
{
	if (! preg_match("/\b".$status->status."\b/", $campinfo->dial_statuses))
	{
		$dial_statuses_list .= '<option value="'.$status->status.'">'.$status->status.' - '.$status->status_name.'</option>';
	}
	$dial_status[$status->status] = $status->status_name;
}

$i=1;
foreach (explode(' ',$campinfo->dial_statuses) as $status)
{
	if ($dial_status[$status] != '')
	{
		$enabled_dial_statuses .= '<tr class="advance_settings"><td style="text-align:right;">Active Dial Status '.$i.':</td><td>&nbsp;<strong>'.$status.'</strong> - '.$dial_status[$status].' <span id="removeStatus" class="toolTip" style="float:right;font-size:10px;cursor:pointer;" onclick="delStatus(\''.$status.'\')" title="REMOVE STATUS: '.$status.'">REMOVE&nbsp;&nbsp;&nbsp;</span></td></tr>';
		$i++;
	}
}
?>
<script>
$(document).ready(function()
{
	var isAdvance = <?php echo $isAdvance; ?>;
	var testVar = jQuery.parseJSON('<?php echo str_replace("'","\'",json_encode($campinfo)); ?>');
	$('#addStatus').attr('readonly','true');

	if (isAdvance)
	{
		$('.advance_settings').show();
		$('.webFormSpan').show();
		$('#advance_link').html('[ - ADVANCE SETTINGS ]');
		$('#isAdvance').val('1');
	}

	$.each($('.advanceSettings'), function()
	{
		for (name in testVar)
		{
			if ($(this).attr('id') == name)
			{
				$(this).val(testVar[name]);
			}
		}
	});

	$('#dial_method option').each(function()
	{
		//if ($(this).text() == '<?php echo $dial_method; ?>')
		//	$('#dial_method').val('<?php echo $dial_method; ?>');

		if ($('#dial_method option:selected').val() != 'AUTO_DIAL')
		{
			$('#auto_dial_level').attr('disabled','');
		} else {
			$('#auto_dial_level').removeAttr('disabled');
		}
	});

	$('#auto_dial_level option').each(function()
	{
		if ($(this).text() == '<?php echo $auto_dial_level; ?>')
			$('#auto_dial_level').val('<?php echo $auto_dial_level; ?>');

		if ($('#auto_dial_level option:selected').text() == 'ADVANCE')
		{
			$('#auto_dial_level_adv option').each(function()
			{
				$('#auto_dial_level_adv').show();
				if ($(this).val() == '<?php echo $auto_dial_level_adv; ?>')
					$('#auto_dial_level_adv').val('<?php echo $auto_dial_level_adv; ?>');
			});
		}
	});

	$('#campaign_recording option').each(function()
	{
		if ($(this).val() == '<?php echo $campinfo->campaign_recording; ?>')
			$('#campaign_recording').val('<?php echo $campinfo->campaign_recording; ?>');
	});

	$('#campaign_vdad_exten option').each(function()
	{
		if ($(this).val() == '<?php echo $campinfo->campaign_vdad_exten; ?>')
			$('#campaign_vdad_exten').val('<?php echo $campinfo->campaign_vdad_exten; ?>');
	});

	$('#local_call_time option').each(function()
	{
		if ($(this).val() == '<?php echo $campinfo->local_call_time; ?>')
			$('#local_call_time').val('<?php echo $campinfo->local_call_time; ?>');
	});

	$('#active option').each(function()
	{
		if ($(this).val() == '<?php echo $campinfo->active; ?>')
			$('#active').val('<?php echo $campinfo->active; ?>');
	});

	$('#lead_order option').each(function()
	{
		if ($(this).val() == '<?php echo $campinfo->lead_order; ?>')
			$('#lead_order').val('<?php echo $campinfo->lead_order; ?>');
	});

	$('#hopper_level option').each(function()
	{
		if ($(this).val() == '<?php echo $campinfo->hopper_level; ?>')
			$('#hopper_level').val('<?php echo $campinfo->hopper_level; ?>');
	});

	$('#adaptive_intensity option').each(function()
	{
		if ($(this).val() == '<?php echo $campinfo->adaptive_intensity; ?>')
			$('#adaptive_intensity').val('<?php echo $campinfo->adaptive_intensity; ?>');
	});

	$('#get_call_launch option').each(function()
	{
		if ($(this).val() == '<?php echo $campinfo->get_call_launch; ?>')
			$('#get_call_launch').val('<?php echo $campinfo->get_call_launch; ?>');
	});

	$('#campaign_allow_inbound option').each(function()
	{
		if ($(this).val() == '<?php echo $campinfo->campaign_allow_inbound; ?>')
			$('#campaign_allow_inbound').val('<?php echo $campinfo->campaign_allow_inbound; ?>');
	});

	$('#survey_ni_status option').each(function()
	{
		if ($(this).val() == '<?php echo $campinfo->survey_ni_status; ?>')
			$('#survey_ni_status').val('<?php echo $campinfo->survey_ni_status; ?>');
	});

	$('#number_of_lines option').each(function()
	{
		if ($(this).val() == '<?php echo $remoteinfo->number_of_lines; ?>')
			$('#number_of_lines').val('<?php echo $remoteinfo->number_of_lines; ?>');
	});

	$('#status option').each(function()
	{
		if ($(this).val() == '<?php echo $remoteinfo->status; ?>')
			$('#status').val('<?php echo $remoteinfo->status; ?>');
	});

	$('#survey_method option').each(function()
	{
		if ($(this).val() == '<?php echo $campinfo->survey_method; ?>')
			$('#survey_method').val('<?php echo $campinfo->survey_method; ?>');
	});

	$('#auto_dial_level').change(function()
	{
		$('#auto_dial_level option:selected').each(function()
		{
			if ($(this).val() == "ADVANCE")
			{
				$('#auto_dial_level_adv').show();
			} else {
				$('#auto_dial_level_adv').hide();
			}
		});
	});

	$('#dial_method').change(function()
	{
		$('#dial_method option:selected').each(function()
		{
			if ($(this).val() == "MANUAL")
			{
				$('#auto_dial_level').val('OFF');
				$('#auto_dial_level_adv').hide();
				$('#auto_dial_level').attr('disabled','');
			}

			if ($(this).val() == "AUTO_DIAL")
			{
				if ($('#auto_dial_level option:selected').val() == 'OFF')
				{
					$('#auto_dial_level').val('SLOW');
					$('#auto_dial_level_adv').hide();
				}
				$('#auto_dial_level').removeAttr('disabled');
			}

			if ($(this).val() == "PREDICTIVE" || $(this).val() == "INBOUND_MAN")
			{
				$('#auto_dial_level').val('SLOW');
				$('#auto_dial_level_adv').hide();
				$('#auto_dial_level').attr('disabled','');
			}
		});
	});

	$('#advance_link').click(function()
	{
		if ($('.advance_settings').is(':hidden'))
		{
			$('.advance_settings').show();
			$('#web_form_address').show();
			$('.webFormSpan').show();
			$('#advance_link').html('[ - ADVANCE SETTINGS ]');
			$('#isAdvance').val('1');
		} else {
			$('.advance_settings').hide();
			$('#web_form_address').hide();
			$('.webFormSpan').hide();
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

	$(".toolTip").tipTip();

	// Submit Settings
	$('#saveSettings').click(function()
	{
		var isAdvance = $('#isAdvance').val();

		if ($('#campaign_vdad_exten').val() != '8366' && $('#campaign_vdad_exten').val() != '8373')
		{
			//Basic Variables
			var campaign_id = $('#campaign_id').val();
			var campaign_name = $('#campaign_name').val().replace(/ /g, "%20");
			var dial_method = $('#dial_method').val();
			var auto_dial_level = $('#auto_dial_level').val();
			var auto_dial_level_adv = $('#auto_dial_level_adv').val();
			var campaign_script = $('#campaign_script').val();
			var campaign_cid = $('#campaign_cid').val();
			var campaign_recording = $('#campaign_recording').val();
			var campaign_vdad_exten = $('#campaign_vdad_exten').val();
			var local_call_time = $('#local_call_time').val();
			var web_form_address = $('#web_form_address').val().replace(/\/\//g,'dslash');
			var web_form_address = web_form_address.replace(/\?/g,'qmark');
			var repweb_form_address = web_form_address.replace(/\//g,'sslash');
			var dial_prefix = $('#dial_prefix').val();
			var active = $('#active').val();
			
			if (dial_prefix == "--CUSTOM--")
				dial_prefix = "CUSTOM_"+$('#custom_prefix').val();

			if ($('#campaign_description').val().length > 0)
			{
				var campaign_description = $('#campaign_description').val().replace(/ /g, "%20");
			}
			else
			{
				var campaign_description = null;
			}

			var basicArray = [campaign_name, dial_method, auto_dial_level, auto_dial_level_adv, campaign_script, campaign_cid, campaign_recording, campaign_vdad_exten, local_call_time, campaign_description, repweb_form_address, dial_prefix, active];
		}
		else
		{
			//Basic Variables
			var campaign_id = $('#campaign_id').val();
			var survey_first_audio_file = $('#survey_first_audio_file').val();
			var survey_method = $('#survey_method').val();
			var active = $('#active').val();
			var status = $('#active').val();
			//var status = $('#status').val();
			var number_of_lines = $('#number_of_lines').val();
			var campaign_cid = $('#campaign_cid').val();
			var local_call_time = $('#local_call_time').val();
			var campaign_vdad_exten = $('#campaign_vdad_exten').val();
			var web_form_address = $('#web_form_address').val();
			var dial_prefix = $('#dial_prefix').val();
			var survey_menu_id = $('#survey_menu_id').val();
			
			if (dial_prefix == "--CUSTOM--")
				dial_prefix = "CUSTOM_"+$('#custom_prefix').val();

			var basicArray = [survey_first_audio_file, survey_method, status, number_of_lines, campaign_cid, local_call_time, '', campaign_vdad_exten, active, repweb_form_address, dial_prefix, survey_menu_id];
		}

		//Advanced variables
		var advArray = [];
		var allowed_inbgrp = [];
		var notallowed_inbgrp = [];
		var advArraytrans = [];
		var allowed_transgrp = [];
		var notallowed_transgrp = [];
		if (isAdvance > 0)
		{
			$.each($('.advanceSettings'), function()
			{
				if ($(this).attr('id') != 'allowed_groups')
				{
					var value = $(this).val();
					var encoded = value
								.replace(/ /g,'_')
								.replace(/,/g, '+');
					advArray.push($(this).attr('id')+':'+encoded);
				}
				
				if ($(this).attr('id') != 'tranfer_groups')
				{
					var value = $(this).val();
					advArraytrans.push($(this).attr('id')+':'+value.replace(/ /g,'_'));
				}
			});

			$("input[name='closer_campaigns[]']").each(function ()
			{
				if ($(this).is(':checked'))
				{
					allowed_inbgrp.push($(this).val());
				} else {
					notallowed_inbgrp.push($(this).val());
				}
			});

			if (allowed_inbgrp.length < 1)
				allowed_inbgrp = 0;

			if (notallowed_inbgrp.length < 1)
				notallowed_inbgrp = 0;

			$("input[name='xfer_groups[]']").each(function ()
			{
					if ($(this).is(':checked'))
					{
							allowed_transgrp.push($(this).val());
					} else {
							notallowed_transgrp.push($(this).val());
					}
			});

			if (allowed_transgrp.length < 1)
					allowed_transgrp = 0;

			if (notallowed_transgrp.length < 1)
					notallowed_transgrp = 0;

		}

//		$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_modify_settings/'+campaign_id+'/modify/'+basicArray+'/'+advArray+'/'+isAdvance+'/'+allowed_inbgrp+'/'+notallowed_inbgrp).fadeIn("slow");

                $('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_modify_settings/'+campaign_id+'/modify/'+basicArray+'/'+advArray+'/'+isAdvance+'/'+allowed_inbgrp+'/'+notallowed_inbgrp+'/'+advArraytrans+'/'+allowed_transgrp+'/'+notallowed_transgrp).fadeIn("slow");

		//$('#table_reports').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/').fadeIn("slow");
	});

	function replaceURLWithHTMLLinks(text) {
    		var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
    		return text.replace(exp,"<a href='$1'>$1</a>");
  	}

	$('#saveListIDs').click(function()
	{
		var isAdvance = $('#isAdvance').val();
		var campaign_id = $('#campaign_id').val();
		var active_list_ids = [];
		var inactive_list_ids = [];

		$("input[name='active[]']").each(function ()
		{
			if ($(this).is(':checked'))
			{
				active_list_ids.push($(this).val());
			} else {
				inactive_list_ids.push($(this).val());
			}
		});

		if (active_list_ids.length < 1)
			active_list_ids = 0;

		if (inactive_list_ids.length < 1)
			inactive_list_ids = 0;

		$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_modify_settings/'+campaign_id+'/update_listid/'+active_list_ids+'/'+inactive_list_ids+'/'+isAdvance).fadeIn("slow");
	});

	$('#addStatus').click(function()
	{
		var isAdvance = $('#isAdvance').val();
		var campaign_id = $('#campaign_id').val();
		var status_to_add = $('#dial_status').val();

		if (status_to_add != 'NONE')
		{
			$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_modify_settings/'+campaign_id+'/add_status/'+status_to_add+'/'+isAdvance).fadeIn("slow");
		}
	});

	$('#dial_status').change(function()
	{
		if ($(this).val() == 'NONE')
		{
			$('#addStatus').attr('readonly','true');
		}
		else
		{
			$('#addStatus').removeAttr('readonly');
		}
	});

	$('#descTruncate').hover(function()
	{
		$(this).css('color','red');
	},function()
	{
		$(this).css('color','blue');
	});

	$('.selectAudio').click(function()
	{
		$('#fileOverlay').fadeIn('fast');
		$('#fileBox').css({'width': '100%', 'height': '90%', 'left': '-20px', 'padding-bottom': '20px'});
		$('#fileBox').animate({
			top: "10px"
		}, 500);

		$('#audioButtonClicked').text($(this).attr('id'));
	});

	$('#fileClosebox').click(function()
	{
		$('#fileBox').animate({'top':'-2550px'},500);
		$('#fileOverlay').fadeOut('slow');
	});

	$('#view_hopper').click(function()
	{
		$('#hopperOverlay').fadeIn('fast');
		$('#hopperBox').css({'width': '900px','margin-left': 'auto', 'margin-right': 'auto', 'left': '14%', 'right': '14%', 'padding-bottom': '20px'});
		$('#hopperBox').animate({
			top: "75px"
		}, 500);

		var campaign_id = $('#campaign_id').val();
		$("#hopperOverlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#hopperOverlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_view_hopper_list/'+campaign_id).fadeIn("slow");
	});

	$('#hopperClosebox').click(function()
	{
		$('#hopperBox').animate({'top':'-2550px'},500);
		$('#hopperOverlay').fadeOut('slow');
	});
	
	$('#dial_prefix').change(function()
	{
		if ($(this).val() == "--CUSTOM--")
		{
			$('#custom_prefix').show();
		} else {
			$('#custom_prefix').hide();
		}
	});
	
	$('#custom_manual_prefix').change(function()
	{
		if ($(this).val() == "--CUSTOM--")
		{
			$('#manual_dial_prefix').show();
		} else {
			$('#manual_dial_prefix').val($(this).val());
			$('#manual_dial_prefix').hide();
		}
	});
	
	$('#logout_agents').click(function()
	{
		var campaign_id = $('#campaign_id').val();
		$.post("<?=$base?>index.php/go_campaign_ce/emergencylogout", { campaign: campaign_id },
		function(data){
			alert(data);
		});
	});
});

function delStatus(status_to_remove)
{
	var isAdvance = $('#isAdvance').val();
	var campaign_id = $('#campaign_id').val();

	$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_modify_settings/'+campaign_id+'/remove_status/'+status_to_remove+'/'+isAdvance).fadeIn("slow");
}

function modifyListID(list_id)
{
	var isAdvance = $('#isAdvance').val();
	var campaign_id = $('#campaign_id').val();
	$('#statusOverlay').fadeIn('fast');
	$('#statusBox').css({'width': '660px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
	$('#statusBox').animate({
		top: "70px",
		left: "14%",
		right: "14%"
	}, 500);

	$('#statusOverlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_get_listid/'+list_id+'/'+isAdvance).fadeIn("slow");
}

function launch_chooser(fieldname,stage,vposition,defvalue) {
	$.post("<?=$base?>index.php/go_ingroup/chooser", { action: "sounds_list",user: "<?=$gouser?>", format: "selectframe", stage: stage, comments: fieldname, defaultvalue: defvalue  },
	function(data){
		document.getElementById("div"+fieldname).innerHTML=data;
	});
}

function setDivVal(divid,idval) {
	document.getElementById(divid).value=idval;
}
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
<div align="center" style="font-weight:bold; color:#333; font-size:16px;">MODIFY CAMPAIGN: <?php echo "$campaign_id - ".$campinfo->campaign_name; ?></div>
<br />
<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:95%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
    	<td style="text-align:right;" nowrap>Campaign ID:</td><td>&nbsp;<?php echo $campaign_id; ?><input id="campaign_id" type="hidden" value="<?php echo $campaign_id; ?>" /></td>
    </tr>
<?php
if (!$isSurvey)
{
?>
	<tr>
    	<td style="text-align:right;" nowrap>Campaign Name:</td><td><input id="campaign_name" type="text" value="<?php echo $campinfo->campaign_name; ?>" size="50" maxlength="40" /></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Campaign Description:</td><td><input id="campaign_description" type="text" value="<?php echo $campinfo->campaign_description; ?>" size="50" maxlength="255" /></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Campaign Change Date:</td><td>&nbsp;<?php echo $campinfo->campaign_changedate; ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Campaign Login Date:</td><td>&nbsp;<?php echo $campinfo->campaign_logindate; ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Campaign Call Date:</td><td>&nbsp;<?php echo $campinfo->campaign_calldate; ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Active:</td><td><select id="active"><option>Y</option><option>N</option></select></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Park Music-on-Hold:</td><td><input id="park_ext" class="advanceSettings" type="text" value="<?php echo $campinfo->park_ext; ?>" size="10" maxlength="10" /></td>
    </tr>
	<tr class="webFormSpan" style="display:none;">
    	<td style="text-align:right;" nowrap>Web Form:</td><td><input id="web_form_address" type="text" value="<?php echo $campinfo->web_form_address; ?>" size="55" /></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Web Form Target:</td><td><input type="text" id="web_form_target" class="advanceSettings" value="<?php echo $campinfo->web_form_target; ?>" size="25" maxlength="255" /></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Allow Inbound and Blended:</td><td><select id="campaign_allow_inbound" class="advanceSettings"><option>Y</option><option>N</option></select></td>
    </tr>
    <?php echo $enabled_dial_statuses; ?>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Add A Dial Status to Call:</td><td><select id="dial_status"><option value="NONE">NONE</option><?php echo $dial_statuses_list; ?></select> &nbsp;<input type="submit" style="cursor:pointer;" id="addStatus" value=" ADD " /></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>List Order:</td><td>
			<select size=1 name="lead_order" id="lead_order" class="advanceSettings">
            <option>DOWN</option>
            <option>UP</option>
            <option>DOWN PHONE</option>
            <option>UP PHONE</option>
            <option>DOWN LAST NAME</option>
            <option>UP LAST NAME</option>
            <option>DOWN COUNT</option>
            <option>UP COUNT</option>
            <option>RANDOM</option>
            <option>DOWN LAST CALL TIME</option>
            <option>UP LAST CALL TIME</option>
            <option>DOWN RANK</option>
            <option>UP RANK</option>
            <option>DOWN OWNER</option>
            <option>UP OWNER</option>
            <option>DOWN TIMEZONE</option>
            <option>UP TIMEZONE</option>
            <option>DOWN 2nd NEW</option>
            <option>DOWN 3rd NEW</option>
            <option>DOWN 4th NEW</option>
            <option>DOWN 5th NEW</option>
            <option>DOWN 6th NEW</option>
            <option>UP 2nd NEW</option>
            <option>UP 3rd NEW</option>
            <option>UP 4th NEW</option>
            <option>UP 5th NEW</option>
            <option>UP 6th NEW</option>
            <option>DOWN PHONE 2nd NEW</option>
            <option>DOWN PHONE 3rd NEW</option>
            <option>DOWN PHONE 4th NEW</option>
            <option>DOWN PHONE 5th NEW</option>
            <option>DOWN PHONE 6th NEW</option>
            <option>UP PHONE 2nd NEW</option>
            <option>UP PHONE 3rd NEW</option>
            <option>UP PHONE 4th NEW</option>
            <option>UP PHONE 5th NEW</option>
            <option>UP PHONE 6th NEW</option>
            <option>DOWN LAST NAME 2nd NEW</option>
            <option>DOWN LAST NAME 3rd NEW</option>
            <option>DOWN LAST NAME 4th NEW</option>
            <option>DOWN LAST NAME 5th NEW</option>
            <option>DOWN LAST NAME 6th NEW</option>
            <option>UP LAST NAME 2nd NEW</option>
            <option>UP LAST NAME 3rd NEW</option>
            <option>UP LAST NAME 4th NEW</option>
            <option>UP LAST NAME 5th NEW</option>
            <option>UP LAST NAME 6th NEW</option>
            <option>DOWN COUNT 2nd NEW</option>
            <option>DOWN COUNT 3rd NEW</option>
            <option>DOWN COUNT 4th NEW</option>
            <option>DOWN COUNT 5th NEW</option>
            <option>DOWN COUNT 6th NEW</option>
            <option>UP COUNT 2nd NEW</option>
            <option>UP COUNT 3rd NEW</option>
            <option>UP COUNT 4th NEW</option>
            <option>UP COUNT 5th NEW</option>
            <option>UP COUNT 6th NEW</option>
            <option>RANDOM 2nd NEW</option>
            <option>RANDOM 3rd NEW</option>
            <option>RANDOM 4th NEW</option>
            <option>RANDOM 5th NEW</option>
            <option>RANDOM 6th NEW</option>
            <option>DOWN LAST CALL TIME 2nd NEW</option>
            <option>DOWN LAST CALL TIME 3rd NEW</option>
            <option>DOWN LAST CALL TIME 4th NEW</option>
            <option>DOWN LAST CALL TIME 5th NEW</option>
            <option>DOWN LAST CALL TIME 6th NEW</option>
            <option>UP LAST CALL TIME 2nd NEW</option>
            <option>UP LAST CALL TIME 3rd NEW</option>
            <option>UP LAST CALL TIME 4th NEW</option>
            <option>UP LAST CALL TIME 5th NEW</option>
            <option>UP LAST CALL TIME 6th NEW</option>
            <option>DOWN RANK 2nd NEW</option>
            <option>DOWN RANK 3rd NEW</option>
            <option>DOWN RANK 4th NEW</option>
            <option>DOWN RANK 5th NEW</option>
            <option>DOWN RANK 6th NEW</option>
            <option>UP RANK 2nd NEW</option>
            <option>UP RANK 3rd NEW</option>
            <option>UP RANK 4th NEW</option>
            <option>UP RANK 5th NEW</option>
            <option>UP RANK 6th NEW</option>
            <option>DOWN OWNER 2nd NEW</option>
            <option>DOWN OWNER 3rd NEW</option>
            <option>DOWN OWNER 4th NEW</option>
            <option>DOWN OWNER 5th NEW</option>
            <option>DOWN OWNER 6th NEW</option>
            <option>UP OWNER 2nd NEW</option>
            <option>UP OWNER 3rd NEW</option>
            <option>UP OWNER 4th NEW</option>
            <option>UP OWNER 5th NEW</option>
            <option>UP OWNER 6th NEW</option>
            <option>DOWN TIMEZONE 2nd NEW</option>
            <option>DOWN TIMEZONE 3rd NEW</option>
            <option>DOWN TIMEZONE 4th NEW</option>
            <option>DOWN TIMEZONE 5th NEW</option>
            <option>DOWN TIMEZONE 6th NEW</option>
            <option>UP TIMEZONE 2nd NEW</option>
            <option>UP TIMEZONE 3rd NEW</option>
            <option>UP TIMEZONE 4th NEW</option>
            <option>UP TIMEZONE 5th NEW</option>
            <option>UP TIMEZONE 6th NEW</option>
            </select>
        </td>
    </tr>
<?php
// 	<tr class="advance_settings" style="display:none;">
//     	<td style="text-align:right;" nowrap>Minimum Hopper Level:</td><td><select size=1 name="hopper_level" id="hopper_level" class="advanceSettings"><option>1</option><option>5</option><option>10</option><option>20</option><option>50</option><option>100</option><option>200</option><option>500</option><option>700</option><option>1000</option><option>2000</option></select></td>
//     </tr>
}
?>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Lead Filter:</td><td><?=form_dropdown("lead_filter_id",$lead_filters,$campinfo->lead_filter_id,'id="lead_filter_id" class="advanceSettings"'); ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Force Reset Leads on Hopper:</td><td><select id="force_reset_hopper" name="force_reset_hopper" class="advanceSettings"><option>N</option><option>Y</option></select></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Dial Timeout:</td><td><input type="text" value="<?php echo $campinfo->dial_timeout; ?>" size="3" id="dial_timeout" name="dial_timeout" class="advanceSettings" /> <em>in seconds</em></td>
    </tr>
<?php
if (!$isSurvey)
{
?>
	<tr>
    	<td style="text-align:right;" nowrap>Dial Method:</td><td><?=form_dropdown('dial_method',array('MANUAL'=>'MANUAL','AUTO_DIAL'=>'AUTO DIAL','PREDICTIVE'=>'PREDICTIVE','INBOUND_MAN'=>'INBOUND MAN'),$dial_method,'id="dial_method"'); ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Auto Dial Level:</td><td><select id="auto_dial_level"><option>OFF</option><option>SLOW</option><option>NORMAL</option><option>HIGH</option><option>MAX</option><option>ADVANCE</option></select><select id="auto_dial_level_adv" style="display:none;"><?php echo $auto_dial_num; ?></select></td>
    </tr>
<?php
}
?>
	<tr style="<?php echo ($this->commonhelper->checkIfTenant($accountNum)) ? 'display:none' : ''?>">
<?php
//if (!$this->commonhelper->checkIfTenant($accountNum))
$dial_prefix["--CUSTOM--"] = "CUSTOM DIAL PREFIX";
$manual_dial_prefix["--CUSTOM--"] = "CUSTOM DIAL PREFIX";
$selected_prefix = "";
$selected_manual_prefix = "";
$show_custom_prefix = "style=\"display:none;\"";
$show_custom_manual_prefix = "style=\"display:none;\"";
foreach ($carrier_info as $id => $carrier)
{
	$prefix = str_replace("N","",str_replace("X","",$carrier['prefix']));
	if (strlen($prefix) > 0)
	{
		$dial_prefix[$id] = "$id - $prefix - {$carrier['carrier_name']}";
		$manual_dial_prefix[$prefix] = "$id - $prefix - {$carrier['carrier_name']}";
		
		if ($prefix == $campinfo->dial_prefix)
			$selected_prefix = $id;
		
		if ($prefix == $campinfo->manual_dial_prefix)
			$selected_manual_prefix = $prefix;
	}
}
if (strlen($selected_prefix) == 0)
{
	$selected_prefix = "--CUSTOM--";
	$show_custom_prefix = "";
}
if (strlen($selected_manual_prefix) == 0)
{
	$selected_manual_prefix = "--CUSTOM--";
	$show_custom_manual_prefix = "";
}
?>
    	<td style="text-align:right;" nowrap>Carrier to use for this Campaign:</td><td><?=form_dropdown('dial_prefix',$dial_prefix,$selected_prefix,'id="dial_prefix" style="width:250px;"') ?> <?=form_input('custom_prefix',$campinfo->dial_prefix,'id="custom_prefix" size="12" maxlength="20" '.$show_custom_prefix) ?></td>
    </tr>
<?php
if (!$isSurvey)
{
	if (!$this->commonhelper->checkIfTenant($this->session->userdata('user_group')))
	{
?>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Manual Dial Prefix:</td><td><?=form_dropdown('custom_manual_prefix',$manual_dial_prefix,$selected_manual_prefix,'id="custom_manual_prefix" style="width:250px;"') ?>  <?=form_input('manual_dial_prefix',$campinfo->manual_dial_prefix,'id="manual_dial_prefix" size="12" maxlength="20" class="advanceSettings" '.$show_custom_manual_prefix) ?></td>
    </tr>
<?php
	}
// 	<tr class="advance_settings" style="display:none;">
//     	<td style="text-align:right;" nowrap>Adapt Intensity Modifier:</td><td>
//         	<select id="adaptive_intensity" class="adaptive_intensity" class="advanceSettings">
//             <?php
// 			for ($n=40;$n>=-40;$n--)
// 			{
// 				$dtl = 'Balanced';
// 				if ($n<0) {$dtl = 'Less Intense';}
// 				if ($n>0) {$dtl = 'More Intense';}
// 				echo "<option value=\"$n\">$n - $dtl</option>\n";
// 			}
// 			? >
//             </select>
//         </td>
//     </tr>
?>
	<tr class="advance_settings" style="display:none;">
    <?php
	$eswOpt=''; $cfwOpt='';
	if ($enable_second_webform > 0)
		{$eswOpt = '<option>WEBFORMTWO</option>';}
	if ($custom_fields_enabled > 0)
		{$cfwOpt = '<option>FORM</option>';}
	?>
    	<td style="text-align:right;" nowrap>Get Call Launch:</td><td><select size=1 name="get_call_launch" id="get_call_launch" class="advanceSettings"><option selected>NONE</option><option>SCRIPT</option><?php echo "$eswOpt$cfwOpt"; ?></select></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Script:</td><td><?php echo form_dropdown('campaign_script',$camp_script_list,$campinfo->campaign_script,'id="campaign_script"'); ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Answering Machine Message:</td><td><?php echo form_input('am_message_exten',$campinfo->am_message_exten,'id="am_message_exten" class="advanceSettings" maxlength="100" size="50"'); ?> <a href="javascript:launch_chooser('am_message_exten','date',1200,document.getElementById('am_message_exten').value);"><FONT color="blue">[ Audio Chooser ]</a><div id="divam_message_exten"></div></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>WaitForSilence Options:</td><td><?php echo form_input('waitforsilence_options',$campinfo->waitforsilence_options,'id="waitforsilence_options" class="advanceSettings" maxlength="25" size="20"'); ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>AMD Send to VM exten:</td><td><?php echo form_dropdown('amd_send_to_vmx',array('Y'=>'Y','N'=>'N'),$campinfo->amd_send_to_vmx,'id="amd_send_to_vmx" class="advanceSettings"'); ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>CPD AMD Action:</td><td><?php echo form_dropdown('cpd_amd_action',array('DISABLED'=>'DISABLED','DISPO'=>'DISPO','MESSAGE'=>'MESSAGE'),$campinfo->cpd_amd_action,'id="cpd_amd_action" class="advanceSettings"'); ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Pause Codes Active:</td><td><?php echo form_dropdown('agent_pause_codes_active',array('N'=>'NO','FORCE'=>'YES'),$campinfo->agent_pause_codes_active,'id="agent_pause_codes_active" class="advanceSettings"'); ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Available Only Tally:</td><td><?php echo form_dropdown('available_only_ratio_tally',array('N'=>'NO','Y'=>'YES'),$campinfo->available_only_ratio_tally,'id="available_only_ratio_tally" class="advanceSettings"'); ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Manual Dial Filter:</td><td><?php echo form_dropdown('manual_dial_filter',array('NONE'=>'NONE','DNC_ONLY'=>'DNC ONLY','CAMPLISTS_ONLY'=>'CAMPLISTS ONLY','CAMPLISTS_ALL'=>'CAMPLISTS ALL','DNC_AND_CAMPLISTS'=>'DNC AND CAMPLISTS','DNC_AND_CAMPLISTS_ALL'=>'DNC AND CAMPLISTS ALL'),$campinfo->manual_dial_filter,'id="manual_dial_filter" class="advanceSettings"'); ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Agent Lead Search:</td><td><?php echo form_dropdown('agent_lead_search',array('ENABLED'=>'ENABLED','DISABLED'=>'DISABLED'),$campinfo->agent_lead_search,'id="agent_lead_search" class="advanceSettings"'); ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Agent Lead Search Method:</td><td><?php echo form_dropdown('agent_lead_search_method',array('SYSTEM'=>'SYSTEM','CAMPAIGNLISTS'=>'CAMPAIGNLISTS','CAMPLISTS_ALL'=>'CAMPLISTS ALL','LIST'=>'LIST','USER_CAMPLISTS_ALL'=>'USER CAMPLISTS ALL','USER_LIST'=>'USER LIST','GROUP_SYSTEM'=>'GROUP SYSTEM','GROUP_CAMPAIGNLISTS'=>'GROUP CAMPAIGNLISTS','GROUP_CAMPLISTS_ALL'=>'GROUP CAMPLISTS ALL','GROUP_LIST'=>'GROUP LIST','TERRITORY_SYSTEM'=>'TERRITORY SYSTEM','TERRITORY_CAMPAIGNLISTS'=>'TERRITORY CAMPAIGNLISTS','TERRITORY_CAMPLISTS_ALL'=>'TERRITORY CAMPLISTS ALL','TERRITORY_LIST'=>'TERRITORY LIST'),$campinfo->agent_lead_search_method,'id="agent_lead_search_method" class="advanceSettings"'); ?></td>
    </tr>
<?php
} else {
?>
	<tr>
		<td style="text-align:right;">Active:</td>
		<td><select id="active" name="status"><option value="N">INACTIVE</option><option value="Y">ACTIVE</option></select></td>
	</tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Available Only Tally:</td><td><?php echo form_dropdown('available_only_ratio_tally',array('N'=>'NO','Y'=>'YES'),$campinfo->available_only_ratio_tally,'id="available_only_ratio_tally" class="advanceSettings"'); ?></td>
    </tr>
	<!--<tr>
    	<td style="text-align:right;" nowrap>Activesssss:</td><td><select id="active"><option>Y</option><option>N</option></select></td>
    </tr> -->
<?php
}
?>
	<tr>
    	<td style="text-align:right;" nowrap>Campaign CallerID:</td><td><input id="campaign_cid" type="text" value="<?php echo $campinfo->campaign_cid; ?>" size="15" /></td>
    </tr>
<?php
if ($campinfo->campaign_allow_inbound == 'Y')
{
?>
	<tr>
    	<td style="text-align:right;" nowrap>Assigned DID/TFN:</td><td>
    	<a href="<?php echo $base; ?>index.php/ingroups#tabs-2" class="toolTip" title="View All DID Numbers" style="text-decoration:none">
<?php
foreach ($inbound_dids as $did)
{
	$did_description = str_replace("$accountNum - ",'',$did->did_description);
	echo "&nbsp; {$did->did_pattern}<br />";
}
?>
		</a>
    	</td>
    </tr>
<?php
}

if (!$isSurvey)
{
?>
	<tr>
    	<td style="text-align:right;" nowrap>Campaign Recording:</td><td><select id="campaign_recording"><option value="NEVER">OFF</option><option value="ALLFORCE">ON</option><option value="ONDEMAND">ONDEMAND</option></select></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Campaign Rec Filename:</td><td><input type=text name="campaign_rec_filename" id="campaign_rec_filename" class="advanceSettings" size=50 maxlength=50 value="<?php echo $campinfo->campaign_rec_filename; ?>"></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Answering Machine Detection:</td><td><select id="campaign_vdad_exten"><?php echo $camp_vdad_option; ?></select></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Next Agent Call:</td><td><?=form_dropdown('next_agent_call',array('random'=>'Random','oldest_call_start'=>'Oldest Call Start','oldest_call_finish'=>'Oldest Call Finish','overall_user_level'=>'Overall User Level','campaign_rank'=>'Campaign Rank','campaign_grade_random'=>'Campaign Grade Random','fewest_calls'=>'Fewest Calls','longest_wait_time'=>'Longest Wait Time'),$campinfo->next_agent_call,'id="next_agent_call" class="advanceSettings"'); ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Transfer-Conf Number 1:</td><td><input type=text name="xferconf_a_number" id="xferconf_a_number" class="advanceSettings" size=20 maxlength=50 value="<?php echo $campinfo->xferconf_a_number; ?>"></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Transfer-Conf Number 2:</td><td><input type=text name="xferconf_b_number" id="xferconf_b_number" class="advanceSettings" size=20 maxlength=50 value="<?php echo $campinfo->xferconf_b_number; ?>"></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>3-Way Call Outbound CallerID:</td><td><?php echo form_dropdown('three_way_call_cid',array('CAMPAIGN'=>'CAMPAIGN','CUSTOMER'=>'CUSTOMER','AGENT_PHONE'=>'AGENT PHONE','AGENT_CHOOSE'=>'AGENT CHOOSE','CUSTOM_CID'=>'CUSTOM CID'),$campinfo->three_way_call_cid,'id="three_way_call_cid" class="advanceSettings"'); ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>3-Way Call Dial Prefix:</td><td><?php echo form_input('three_way_dial_prefix',$campinfo->three_way_dial_prefix,'id="three_way_dial_prefix" maxlength="20" size="15" class="advanceSettings"'); ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Customer 3-Way Hangup Logging:</td><td><?php echo form_dropdown('customer_3way_hangup_logging',array('DISABLED'=>'DISABLED','ENABLED'=>'ENABLED'),$campinfo->customer_3way_hangup_logging,'id="customer_3way_hangup_logging" class="advanceSettings"'); ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Customer 3-Way Hangup Seconds:</td><td><?php echo form_input('customer_3way_hangup_seconds',$campinfo->customer_3way_hangup_seconds,'id="customer_3way_hangup_seconds" maxlength="5" size="5" class="advanceSettings"'); ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Customer 3-Way Hangup Action:</td><td><?php echo form_dropdown('customer_3way_hangup_action',array('NONE'=>'NONE','DISPO'=>'DISPO'),$campinfo->customer_3way_hangup_action,'id="customer_3way_hangup_action" class="advanceSettings"'); ?></td>
    </tr>
<?php
}
if ($isSurvey)
{
?>
	<tr>
		<td style="text-align:right;">Audio File:</td>
		<td><input id="survey_first_audio_file" name="survey_first_audio_file" type="text" maxlength="50" size="25" value="<?php echo $campinfo->survey_first_audio_file; ?>" /><input type="button" value="Audio" style="cursor:pointer;" class="selectAudio" id="first_audio" /></td>
	</tr>
	<tr>
		<td style="text-align:right;">Survey Method:</td>
		<td>
			<select id="survey_method" name="survey_method">
				<option value="AGENT_XFER">CAMPAIGN</option>
				<option value="EXTENSION">DID</option>
				<option value="CALLMENU">CALLMENU</option>
			</select>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;">Survey Call Menu:</td>
		<td>
			<?php
			$callMenuArray[''] = "---NONE---";
			if (count($call_menus) > 0) {
				foreach ($call_menus as $menu) {
					$callMenuArray[$menu->menu_id] = "{$menu->menu_id} - {$menu->menu_name}";
				}
			}
			echo form_dropdown('survey_menu_id',$callMenuArray,$campinfo->survey_menu_id,'id="survey_menu_id" style="width:400px;"');
			?>
		</td>
	</tr>
<?php
	if ($campinfo->campaign_vdad_exten=='8366')
	{
?>
	<tr class="advance_settings" style="display:none;">
		<td style="text-align:right;">Survey DTMF Digits:</td>
		<td><input id="survey_dtmf_digits" name="survey_dtmf_digits" class="advanceSettings" type="text" maxlength="16" size="16" placeholder="eg. 0123456789*#" value="<?php echo $campinfo->survey_dtmf_digits; ?>" /> <small>* customer define key press e.g.0123456789*#</small></td>
	</tr>
	<tr class="advance_settings" style="display:none;">
		<td style="text-align:right;">DID:</td>
		<td><input id="survey_xfer_exten" name="survey_xfer_exten" class="advanceSettings" type="text" maxlength="25" size="20" value="<?php echo $campinfo->survey_xfer_exten; ?>" /></td>
	</tr>
	<tr class="advance_settings" style="display:none;">
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr class="advance_settings" style="display:none;">
		<td style="text-align:right;">Press 8 Not Interested Digit:</td>
		<td><input id="survey_ni_digit" name="survey_ni_digit" class="advanceSettings" type="text" maxlength="10" size="5" value="<?php echo $campinfo->survey_ni_digit; ?>" /></td>
	</tr>
	<tr class="advance_settings" style="display:none;">
		<td style="text-align:right;">Press 8 Not Interested Audio File:</td>
		<td><input id="survey_ni_audio_file" name="survey_ni_audio_file" class="advanceSettings" type="text" maxlength="50" size="25" value="<?php echo $campinfo->survey_ni_audio_file; ?>" /><input type="button" value="Audio" style="cursor:pointer;" class="selectAudio" id="ni_audio" /></td>
	</tr>
	<tr class="advance_settings" style="display:none;">
		<td style="text-align:right;">Press 8 Not Interested Status:</td>
		<td>
			<select id="survey_ni_status" name="survey_ni_status" class="advanceSettings">
				<option value="NI">NI - Not Interested</option>
				<option value="DNC">DNC - Do Not Call</option>
			</select>
		</td>
	</tr>
	<tr class="advance_settings" style="display:none;">
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr class="advance_settings" style="display:none;">
		<td style="text-align:right;">Press 3 Digit:</td>
		<td><input id="survey_third_digit" name="survey_third_digit" class="advanceSettings" type="text" maxlength="10" size="5" value="<?php echo $campinfo->survey_third_digit; ?>" /></td>
	</tr>
	<tr class="advance_settings" style="display:none;">
		<td style="text-align:right;">Press 3 Audio File:</td>
		<td><input id="survey_third_audio_file" name="survey_third_audio_file" class="advanceSettings" type="text" maxlength="50" size="25" value="<?php echo $campinfo->survey_third_audio_file; ?>" /><input type="button" value="Audio" style="cursor:pointer;" class="selectAudio" id="third_audio" /></td>
	</tr>
	<tr class="advance_settings" style="display:none;">
		<td style="text-align:right;">Press 3 Status:</td>
		<td><input id="survey_third_status" name="survey_third_status" class="advanceSettings" type="text" maxlength="6" size="10" value="<?php echo $campinfo->survey_third_status; ?>" /></td>
	</tr>
	<tr class="advance_settings" style="display:none;">
		<td style="text-align:right;">Press 3 DID:</td>
		<td><input id="survey_third_exten" name="survey_third_exten" class="advanceSettings" type="text" maxlength="25" size="20" value="<?php echo $campinfo->survey_third_exten; ?>" /></td>
	</tr>
	<tr class="advance_settings" style="display:none;">
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr class="advance_settings" style="display:none;">
		<td style="text-align:right;">Press 4 Digit:</td>
		<td><input id="survey_fourth_digit" name="survey_fourth_digit" class="advanceSettings" type="text" maxlength="10" size="5" value="<?php echo $campinfo->survey_fourth_digit; ?>" /></td>
	</tr>
	<tr class="advance_settings" style="display:none;">
		<td style="text-align:right;">Press 4 Audio File:</td>
		<td><input id="survey_fourth_audio_file" name="survey_fourth_audio_file" class="advanceSettings" type="text" maxlength="50" size="25" value="<?php echo $campinfo->survey_fourth_audio_file; ?>" /><input type="button" value="Audio" style="cursor:pointer;" class="selectAudio" id="fourth_audio" /></td>
	</tr>
	<tr class="advance_settings" style="display:none;">
		<td style="text-align:right;">Press 4 Status:</td>
		<td><input id="survey_fourth_status" name="survey_fourth_status" class="advanceSettings" type="text" maxlength="6" size="10" value="<?php echo $campinfo->survey_fourth_status; ?>" /></td>
	</tr>
	<tr class="advance_settings" style="display:none;">
		<td style="text-align:right;">Press 4 DID:</td>
		<td><input id="survey_fourth_exten" name="survey_fourth_exten" class="advanceSettings" type="text" maxlength="25" size="20" value="<?php echo $campinfo->survey_fourth_exten; ?>" /></td>
	</tr>
	<tr class="advance_settings" style="display:none;">
		<td colspan="2">&nbsp;</td>
	</tr>
<?php
	}
?>
	<!--<tr>
		<td style="text-align:right;">Status:</td>
		<td><select id="status" name="status"><option>INACTIVE</option><option>ACTIVE</option></select></td>
	</tr> -->
	<tr>
		<td style="text-align:right;">Number of Lines:</td>
		<td>
			<select id="number_of_lines" name="number_of_lines">
				<option>1</option>
				<option>5</option>
				<option>10</option>
				<option>15</option>
				<option>20</option>
				<option>30</option>
				<option>50</option>
				<option>100</option>
				<option>150</option>
				<option>200</option>
			</select>
			<input type="hidden" id="campaign_vdad_exten" value="<?php echo $campinfo->campaign_vdad_exten; ?>" />
		</td>
	</tr>
<?php
}
?>
	<tr>
    	<td style="text-align:right;" nowrap>Local Call Time:</td><td><select id="local_call_time"><?php echo $local_call_times; ?></select></td>
    </tr>
<?php
if ($campinfo->campaign_allow_inbound=="Y")
{
?>
	<tr class="advance_settings" style="display:none;">
		<td style="text-align:right;">Inbound Groups:</td>
		<td>
<?php
	foreach ($inbound_groups as $inb_grp)
	{
		$checked = "";
//		if (eregi($inb_grp->group_id, $closer_campaigns))
//			$checked = "checked";
                $explode_cl_camp = explode(" ", $closer_campaigns);
                if (in_array($inb_grp->group_id, $explode_cl_camp))
                        $checked = "checked";



		echo "<input type='checkbox' id='allowed_groups' name='closer_campaigns[]' value='{$inb_grp->group_id}' $checked /> {$inb_grp->group_id} - {$inb_grp->group_name}<br />";
	}
?>
		</td>
	</tr>

        <tr class="advance_settings" style="display:none;">
                <td style="text-align:right;">Allowed Transfer Group:</td>
                <td>
<?php

        foreach ($allowed_trans as $allowed_trans_grp)
        {
                $xfergrps = $allowed_trans_grp->xfer_groups;
        }

        foreach ($inbound_groups as $inb_grp)
        {
                $checked = "";
                $explode_cl_camp = explode(" ", $xfergrps);
                if (in_array($inb_grp->group_id, $explode_cl_camp))
                        $checked = "checked";

                echo "<input type='checkbox' id='tranfer_groups' name='xfer_groups[]' value='{$inb_grp->group_id}' $checked /> {$inb_grp->group_id} - {$inb_grp->group_name}<br />";
        }
?>
                </td>
        </tr>
<?php
}
?>
	<tr style="display:none;">
    	<td colspan="2">testing<br />testing<br />testing<br />testing<br />testing<br />testing<br />testing<br />testing<br />testing</td>
    </tr>
<?php
if ($isSurvey && $campinfo->campaign_vdad_exten=='8373')
{
	$hideAdvanceLink = 'display:none;';
}
?>
	<tr>
    	<td><span id="advance_link" style="cursor:pointer;font-size:9px;<?php echo $hideAdvanceLink; ?>">[ + ADVANCE SETTINGS ]</span><input type="hidden" id="isAdvance" value="0" /></td><td style="text-align:right;"><span id="saveSettings" class="buttons">SAVE SETTINGS</span><!--<input id="saveSettings" type="submit" value=" SAVE SETTINGS " style="cursor:pointer;" />--></td>
    </tr>
</table>
<br />
<div align="center" style="font-weight:bold; color:#000; font-size:16px;">LISTS WITHIN THIS CAMPAIGN</div>
<br />
<table id="list_within_campaign" border=0 cellpadding="1" cellspacing="1" style="margin-left:auto; margin-right:auto; width:95%; border:#D0D0D0 solid 1px; -moz-border-radius:5px; -khtml-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;">
	<tr style="font-weight:bold;color:#000;">
    	<td style="white-space: nowrap">&nbsp;&nbsp;LIST ID&nbsp;</td>
    	<td style="white-space: nowrap">&nbsp;LIST NAME&nbsp;</td>
    	<td>&nbsp;DESCRIPTION&nbsp;</td>
    	<td style="text-align:center;white-space: nowrap" nowrap>&nbsp;LEADS COUNT&nbsp;</td>
    	<td style="text-align:center">&nbsp;ACTIVE&nbsp;</td>
    	<td style="text-align:center;white-space: nowrap" nowrap>&nbsp;LAST CALL DATE&nbsp;</td>
    	<td style="text-align:center">&nbsp;MODIFY&nbsp;</td>
    </tr>
<?php
	$active_list_id = 0;
	$inactive_list_id = 0;
	if (count($camplist['camplist']))
	{
		foreach ($camplist['camplist'] as $row)
		{
			if ($row['list_id'] != '')
			{
				if ($x==0) {
					$bgcolor = "#E0F8E0";
					$x=1;
				} else {
					$bgcolor = "#EFFBEF";
					$x=0;
				}

				$ischecked = ($row['active']=='Y') ? "checked" : "";

				if ($row['active'] == 'Y')
					$active_list_id++;
				if ($row['active'] == 'N')
					$inactive_list_id++;

				$description = (strlen($row['list_description']) > 36) ? substr($row['list_description'],0,36) . "... &nbsp;<span id=\"descTruncate\" style=\"cursor:pointer;color:blue;font-size:10px;line-height:20px;\" class=\"toolTip\" title=\"".$row['list_description']."\">more</span>" : $row['list_description'];

				echo "<tr style=\"background-color:$bgcolor;color:#000;\">\n";
				echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$row['list_id']."&nbsp;</td>\n";
				echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" nowrap>&nbsp;".$row['list_name']."&nbsp;</td>\n";
				echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" nowrap>&nbsp;".$description."&nbsp;</td>\n";
				echo "<td style=\"border-top:#D0D0D0 dashed 1px;text-align:center\">".$row['leads']->count."</td>\n";
				echo "<td style=\"border-top:#D0D0D0 dashed 1px;text-align:center\">".$row['active']." &nbsp; <input name=\"active[]\" value=\"".$row['list_id']."\" type=\"checkbox\" ".$ischecked." /></td>\n";
				echo "<td style=\"border-top:#D0D0D0 dashed 1px;text-align:center;white-space: nowrap\">&nbsp;".$row['list_lastcalldate']."&nbsp;</td>\n";
				echo "<td style=\"border-top:#D0D0D0 dashed 1px;text-align:center\"><img src=\"{$base}img/edit.gif\" onclick=\"modifyListID('".$row['list_id']."');\" style=\"cursor:pointer;width:16px;\" class=\"toolTip\" title=\"MODIFY LIST ID ".$row['list_id']."\" /></td>\n";
				echo "</tr>\n";
			}
		}
	}
?>
</table>
<br style="font-size:8px;" />
<div align="center" style="font-size:14px;"><span id="saveListIDs" class="buttons">SAVE ACTIVE LIST CHANGES</span><!--<input id="saveListIDs" type="submit" value=" SAVE ACTIVE LIST CHANGES " style="cursor:pointer;" />--></div>
<br /><br />
<div align="center" style="color:#000;">This campaign has <?php echo $active_list_id; ?> active lists and <?php echo $inactive_list_id; ?> inactive lists</div>
<br style="font-size:8px;" />
<div align="center" style="color:#000;">This campaign has <?php echo $leads_on_hopper->count; ?> leads in the queue (dial hopper)</div>
<br style="font-size:8px;" />
<div align="center" style="color:#000;"><span id="view_hopper" class="buttons">View leads in the hopper for this campaign</span></div>
<br style="font-size:8px;" />
<?php
if (! $isSurvey) {
?>
<div align="center" style="color:#000;"><span id="logout_agents" class="buttons" style="color:red;">Logout all agents within this campaign</span></div>
<br style="font-size:8px;" />
<?php
}
?>


<!-- File Overlay -->
<span id="audioButtonClicked" style="display:none;"></span>
<div id="fileOverlay" style="display:none;"></div>
<div id="fileBox">
<a id="fileClosebox" class="toolTip" title="CLOSE"></a>
<div id="fileOverlayContent" style="overflow-x: hidden; overflow-y: auto; height: 98%;">
<table>
	<tr>
		<td>List of Files Uploaded:</td>
	</tr>
<?php
$WeBServeRRooT = '/var/lib/asterisk';
$storage = 'sounds';
if(is_dir($WeBServeRRooT.'/'.$storage)){
	if ($handle = opendir($WeBServeRRooT.'/'.$storage)) {
		$ctr = 0;
		while (false !== ($file = readdir($handle))) {
			if($file != '..' && $file != "."){
				$prefix = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "go_".$this->session->userdata('user_group')."_" : "go_";
				if(preg_match("/^$prefix/",$file)){
					$fname = str_replace(".wav","",$file);
					$cols = $ctr % 2;
					if (! $cols) {
						echo "<tr><td style='white-space:nowrap;'>&raquo; <a id='$ctr' href='#'>$fname</a>\n";
					} else {
						echo "<td style='white-space:nowrap;'>&raquo; <a id='$ctr' href='#'>$fname</a>\n";
					}
					echo "<script>
							$('#$ctr').click(function (){
								switch($('#audioButtonClicked').text()){
									case 'first_audio':
										$('input#survey_first_audio_file').val($('#$ctr').text());
									break;
									case 'opt_audio':
										$('input#survey_opt_in_audio_file').val($('#$ctr').text());
									break;
									case 'ni_audio':
										$('input#survey_ni_audio_file').val($('#$ctr').text());
									break;
									case 'third_audio':
										$('input#survey_third_audio_file').val($('#$ctr').text());
									break;
									case 'fourth_audio':
										$('input#survey_fourth_audio_file').val($('#$ctr').text());
									break;
									case 'voicemail_ext':
										$('input#voicemail_ext').val($('#$ctr').text());
									break;
								}
							});
						</script>";
					if (! $cols) {
						echo "</td><td>\n";
					} else {
						echo "</td></tr>\n";
					}
					$ctr++;
				}
			}
		}
		closedir($handle);
	}
}
?>
</table>
</div>
</div>
