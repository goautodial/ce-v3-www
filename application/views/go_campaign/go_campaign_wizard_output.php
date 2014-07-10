<?php
############################################################################################
####  Name:             go_campaign_wizard_output.php                                   ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();
?>
<script>
$(document).ready(function()
{
    // Progress Bar
	var bar = $('.bar');
	var percent = $('.percent');
	var status = $('#status');
	
	$('#uploadform').ajaxForm({
		beforeSend: function() {
			status.empty();
			var percentVal = '0%';
			bar.width(percentVal);
			percent.html(percentVal);
		},
		uploadProgress: function(event, position, total, percentComplete) {
			var percentVal = percentComplete + '%';
			bar.width(percentVal);
			percent.html(percentVal);
		},
		complete: function(xhr) {
			var response = xhr.responseText.split('|');
			
			if (response[0]=='wav')
			{
				alert(response[1]);
				var fname = response[2].split("\n");
				var find = ' ';
				var re = new RegExp(find, 'g');
				document.getElementById('fileNames').innerHTML = response[3].replace(re,':');
				for (x in fname)
				{
					if (fname[x]!='')
						document.getElementById(fname[x]).value = '';
				}
			}
		}
	});

	$('#uploadleads').ajaxForm({
		beforeSend: function() {
			status.empty();
			var percentVal = '0%';
			bar.width(percentVal);
			percent.html(percentVal);
		},
		uploadProgress: function(event, position, total, percentComplete) {
			var percentVal = percentComplete + '%';
			bar.width(percentVal);
			percent.html(percentVal);
		},
		complete: function(xhr) {
			document.getElementById('custom_forms').innerHTML = xhr.responseText;
			$('.progressBar').hide();
			$('#processTable').show();
		}
	});

	$('#survey_ni_status option').each(function()
	{
		if ($(this).val() == '<?php echo $survey_info->survey_ni_status; ?>')
			$('#survey_ni_status').val('<?php echo $survey_info->survey_ni_status; ?>');
	});

	$('#number_of_lines option').each(function()
	{
		if ($(this).val() == '<?php echo $survey_info->number_of_lines; ?>')
			$('#number_of_lines').val('<?php echo $survey_info->number_of_lines; ?>');
	});
	
	// Others
	$('#submit_file').click(function()
	{
		var lead_file = $('#leadFile').val();
		var valid_extensions = /(\.xls|\.xlsx|\.csv|\.ods|\.sxc)$/i;
		
		if (lead_file.length < 1)
		{
			alert('Please include a lead file.');
		}
		else
		{
			if (valid_extensions.test(lead_file))
			{
				$('.progressBar').show();
				$('#uploadleads').submit();
				$('#processLeads').show();
				$('#box').css('position','absolute');
			}
			else
			{
				alert('Uploaded file is invalid: '+lead_file+'<br /><br />File must be in Excel format (xls, xlsx) or in Comma Separated Values (csv).');
			}
		}
	});
	
	// Upload Wav
	$('#upload').click(function()
	{
		var wav_file = $('#wavFile1').val();
		var valid_extensions = /(\.wav)$/i;
		
		if (wav_file.length < 1)
		{
			alert('Please include a .WAV file.');
		}
		else
		{
			if (valid_extensions.test(wav_file))
			{
				$('.progressBar').show();
				$('#uploadform').submit();
			}
			else
			{
				alert('Error uploading '+wav_file+'.<br />Please upload only audio files.<br />We strongly recommend <strong>.WAV</strong> files.');
			}
		}
	});

	var checkWavFile = function ()
	{
		var filename = $(this).val();
		if ( ! /\.wav/.test(filename)) {
			$(this).val('');
			alert('Please upload only audio files.\nWe strongly recommend .WAV files.');
		}
	}
	
	var wavFileNum = 1;
	$('#more').click(function()
	{
		wavFileNum++;
		if (wavFileNum<=10)
		{
			$('#survey_audio').append('<tr><td style="text-align:center"><input type="file" name="wavFile'+wavFileNum+'" id="wavFile'+wavFileNum+'" value="" accept="audio/*" /></td></tr>');
			$('#wavFile'+wavFileNum).change(checkWavFile);
		}
	});


	$('#wavFile1').change(checkWavFile);
	
	var campaign_id = '<?php echo $campaign_id; ?>';
	if (campaign_id.length > 0)
		$('#campaignIDSpan').text(campaign_id);

	var campaign_type = '<?php echo $campType; ?>';
	if (campaign_type.length > 0)
		$('#campaignTypeSpan').text(campaign_type);


	$('#showAdvance').click(function()
	{
		if ($('.advanced').is(':hidden'))
		{
			$('.advanced').show();
			$('#box').css('position','absolute');
			$(this).text('Basic Settings');
		}
		else
		{
			$('.advanced').hide();
			$('#box').css('position','fixed');
			$(this).text('Advance Settings');
		}
	});

	$('#processLeads').click(function()
	{
		var leadArray = [];
		$.each($('.uploadLeads'), function()
		{
			var value = $(this).val();
			if (value.length > 0)
				leadArray.push($(this).attr('name')+':'+value);
		});

		if (leadArray.length < 1)
		{
			alert('Please select field values.');
		}
		else
		{
			var go = 0;
			for (x in leadArray)
			{
				var field = leadArray[x].split(':');
				if (field[0]=='phone_number_field')
					go++;

				if (field[0]=='first_name_field')
					go++;

				if (field[0]=='last_name_field')
					go++;
			}

			if (go < 1)
			{
				alert('Please fill-in at least PHONE NUMBER, FIRST NAME and LAST NAME.');
			}
			else
			{
				var dupcheck = $('#checkDuplicates').val();
				var list_id = $('#list_id').val();
				var phone_code = $('#phone_code').val();
				var file_name = $('#file_name').val();
				var file_ext = $('#file_ext').val();
				var leadArray = $('.uploadLeads').serialize();

				$('#next').text('Next');
				$('#processLeads').hide();
				$('#box').css('position','fixed');
				
				$("#custom_forms").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
				$('#custom_forms').load('<?php echo $base; ?>index.php/go_campaign_ce/go_upload_leads/final/'+dupcheck+'/'+list_id+'/'+phone_code+'/'+leadArray+'/'+file_name+'/'+file_ext);
			}
		}
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
});
</script>
<style type="text/css">
.progress { position:relative; width:200px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
.bar { background-color: #B4F5B4; width:0%; height:15px; border-radius: 3px; }
.percent { position:absolute; display:inline-block; top:2px; left:45%; font-size:10px; }
.label { text-align:right;width:30%;height:10px;font-weight:bold;white-space:nowrap; line-height:24px; }
#more,#upload{
	color:#7A9E22;
}
#more:hover,#upload:hover{
	font-weight:bold;
}
.advanced{
	display:none;
	background-color:#EFFBEF;
}

#showAdvance{
	text-align:center;
	color:#7A9E22;
	cursor:pointer;
	width:40px;
}

#showAdvance:hover{
	font-weight:bold;
}

.selectAudio{
	cursor:pointer;
}
</style>
<table id="campTable" style="width:100%;">
    <tr>
        <td>
<?php
		if (($step==2 && ($campType=='Outbound' || $campType=='Blended')) || ($step==3 && $campType=='Survey'))
		{
?>
            <form action="<?php echo $base; ?>index.php/go_campaign_ce/go_upload_leads/" id="uploadleads" name="uploadleads" method="post" enctype="multipart/form-data">
            <input type="hidden" name='account_num' id="account_num" value="<?php echo $accountNum ?>">
            <input type="hidden" id="campaign_type" value="<?php echo $campType; ?>" />
            <input type="hidden" id="num_channels" value="<?php echo $num_channels; ?>" />
            <input type="hidden" id="survey_type" value="<?php echo $survey_type; ?>" />
            <table style="width:100%;">
                <tr>
                    <td class="label">Lead File:</td>
                    <td><input type="file" name="leadFile" id="leadFile" value="<?php echo $lead_file ?>">
                    </td>
                </tr>
                <tr class="progressBar" style="display:none;">
                    <td>&nbsp;</td>
                    <td>
                        <div class="progress">
                            <div class="bar"></div >
                            <div class="percent">0%</div >
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="label">List ID:</td>
                    <td>
                        <span style="font-weight:bold;font-size:11px;color:#000; line-height:12px;">&nbsp;<?php echo "$list_id &raquo; $list_name"; ?></span>
                        <input type="hidden" id="list_id" name="list_id" value="<?php echo $list_id; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="label">Country:</td>
                    <td>
                    <select name="phone_code" id="phone_code">
                        <?php
                            foreach($phonecodes as $listcodes)
                            {
                                $country_code = $listcodes->country_code;
                                $country = $listcodes->country;
                                echo '<option value="'.$country_code.'">'.$country_code.' &raquo; '.$country.'</option>';
                            }
                        ?>
                    </select>
                    </td>
                 </tr>
                 <tr>
                    <td class="label">Check for Duplicates:</td>
                    <td>
                        <select size="1" name="checkDuplicates" id="checkDuplicates">
                            <option value="NONE">NO DUPLICATE CHECK</option>
                            <option value="CHECKLIST">CHECK FOR DUPLICATES BY PHONE IN LIST ID</option>
                            <option value="CHECKCAMP">CHECK FOR DUPLICATES BY PHONE IN ALL CAMPAIGN LISTS</option>
                            <?php if ($admin_level > 8) { ?>
                            <option value="CHECKSYS">CHECK FOR DUPLICATES BY PHONE IN ENTIRE SYSTEM</option>
                            <option value="CHECKALTPHONELIST">CHECK FOR DUPLICATES BY ALT-PHONE IN LIST ID</option>
                            <option value="CHECKALTPHONESYS">CHECK FOR DUPLICATES BY ALT-PHONE IN ENTIRE SYSTEM</option>
                            <?php } ?>
                        </select>
                    </td>
                 </tr>
                 <?php if ($admin_level > 8) { ?>
                 <tr>
                 <td class="label">Time Zone Lookup:</td>
                    <td>
                        <select size="1" name="timeZone" id="timeZone">
                            <option value="AREA" selected>COUNTRY CODE AND AREA CODE ONLY</option>
                            <option value="POSTAL">POSTAL CODE FIRST</option>
                            <option value="TZCODE">OWNER TIME ZONE CODE FIRST</option>
                        </select>
                    </td>
                 </tr>
                 <?php } ?>
                 <tr>
                    <tr>
                    <td></td>
                    <td>
                            <input type="button" value="UPLOAD LEADS" name="submit_file" id="submit_file" style="cursor:pointer;">
                    </td>
                    
                 </tr>
                 </form>
            </table>
            <div id="custom_forms">
            </div>
			<table style="width:100%;display:none;" id="processTable">
			<tr><td style="text-align:center;width:50%;"><input type="button" id="processLeads" value="OK TO PROCESS" style="cursor:pointer;" /></td><td style="width:50%;display:none;"><input type="button" value="START OVER" style="display:none;" /></td></tr>
			</table>
<?php
		}
		
		if (($step==3 && $campType!='Survey') || ($step==2 && $campType=='Inbound') || ($step==4 && $campType!='Survey'))
		{
			switch ($dial_method)
			{
				case "RATIO":
					$dialMethod = "Auto-Dial";
					break;
				case "ADAPT_AVERAGE":
					$dialMethod = "Predictive";
					break;
				case "MANUAL":
					$dialMethod = "Manual";
					break;
				default:
			}
			
			switch ($auto_dial_level)
			{
				case "0":
					$autoDialLevel = "OFF";
					break;
				case "1.0":
					$autoDialLevel = "SLOW";
					break;
				case "2.0":
					$autoDialLevel = "NORMAL";
					break;
				case "4.0":
					$autoDialLevel = "HIGH";
					break;
				case "6.0":
					$autoDialLevel = "MAX";
					break;
				default:
					$autoDialLevel = "ADVANCE";
			}
			
			switch ($campaign_vdad_exten)
			{
				case "8373":
					$campaignVdadExten = "ON";
					break;
				case "8369":
					$campaignVdadExten = "ON";
					break;
				default:
					$campaignVdadExten = "OFF";
			}
			
			if ($campType!='Survey')
			{
				$vdadOptions = array('8369'=>'ON','8368'=>'OFF');
			} else {
				$vdadOptions = array('8373'=>'ON','8366'=>'OFF');
			}
?>
			<table style="width:100%;">
            	<tr>
                	<td class="label">Campaign ID:</td>
                    <td><?php echo $campaign_id; ?><input type="hidden" value="<?php echo $campaign_id; ?>" name="campaign_id" id="campaign_id" class="previewEdit" /></td>
                </tr>
            	<tr>
                	<td class="label">Campaign Name:</td>
                    <td><?php echo $campaign_name; ?></td>
                </tr>
            	<tr>
                	<td class="label">Dial Method:</td>
                    <td><?php echo form_dropdown('dial_method',array('MANUAL'=>'Manual','RATIO'=>'Auto-Dial','ADAPT_AVERAGE'=>'Predictive'),$dial_method,'id="dial_method" class="previewEdit"'); ?></td>
                </tr>
            	<tr>
                	<td class="label">Auto-Dial Level:</td>
                    <td><?php echo form_dropdown('auto_dial_level',array('0'=>'OFF','1.0'=>'SLOW','2.0'=>'NORMAL','4.0'=>'HIGH','6.0'=>'MAX'),$auto_dial_level,'id="auto_dial_level" class="previewEdit"'); ?></td>
                </tr>
				<tr style="<?php echo ($this->commonhelper->checkIfTenant($accountNum)) ? 'display:none' : ''?>">
					<?php
					if (!$this->commonhelper->checkIfTenant($accountNum))
						$dial_prefix["--CUSTOM--"] = "CUSTOM DIAL PREFIX";
					$selected_prefix = "";
					$show_custom_prefix = 'style="display:none;"';
					foreach ($carrier_info as $id => $carrier)
					{
						$prefix = str_replace("N","",str_replace("X","",$carrier['prefix']));
						if (strlen($prefix) > 0)
						{
							$dial_prefix[$id] = "$id - $prefix - {$carrier['carrier_name']}";
							
							if ($prefix == $dialPrefix)
								$selected_prefix = $id;
						}
					}
					if (strlen($selected_prefix) == 0)
					{
						$selected_prefix = "--CUSTOM--";
						$show_custom_prefix = "";
					}
					?>
					<td style="text-align:right;white-space: nowrap;font-weight: bold;">Carrier to use for this Campaign:</td><td style="white-space: nowrap;"><?=form_dropdown('dial_prefix',$dial_prefix,$selected_prefix,'id="dial_prefix" style="width:200px;" class="previewEdit"') ?> <?=form_input('custom_prefix',$dialPrefix,'id="custom_prefix" class="previewEdit" size="8" maxlength="20" '.$show_custom_prefix) ?></td>
				</tr>
				<?php
				if ($campType!='Inbound' && $campType!='Survey' && $campType!='Copy') {
				?>
				<tr style="display:none;">
					<td style="text-align:right;width:250px;font-weight:bold;">
					Manual Dial Prefix:
					</td>
					<td>
					<input type="text" id="manual_dial_prefix" name="manual_dial_prefix" value="" class="previewEdit" />
					</td>
				</tr>
				<?php
				}
				
				if ($campType!='Outbound' && $campType!='Survey' && $campType!='Copy') {
				?>
            	<tr>
                	<td class="label">DID/TFN Extension:</td>
                    <td><?php
					if (strlen($did_pattern) > 0 || $did_pattern != false) {
						echo $did_pattern;
					} else {
						echo "No DID/TFN set";
					}
					?></td>
                </tr>
            	<tr>
                	<td class="label">Campaign Recording:</td>
                    <td><?php
					echo form_dropdown('campaign_recording',array('NEVER'=>'OFF','ALLFORCE'=>'ON','ONDEMAND'=>'ONDEMAND'),$campaign_recording,'id="campaign_recording" class="previewEdit"');
					?></td>
                </tr>
				<?php
				} else {
				?>
            	<tr>
                	<td class="label">Answering Machine Detection:</td>
                    <td><?php echo form_dropdown('campaign_vdad_exten',$vdadOptions,$campaign_vdad_exten,'id="campaign_vdad_exten" class="previewEdit"'); ?></td>
                </tr>
				<?php
				}
				?>
            </table>
<?php
		}

		if ($step==4 && $campType=='Survey')
		{
?>
			<input type="hidden" value="<?php echo $campType; ?>" name="campaign_type" class="previewEdit" />
			<table style="width:100%;">
            	<tr>
                	<td class="label">Campaign ID:</td>
                    <td><?php echo $campaign_id; ?><input type="hidden" value="<?php echo $campaign_id; ?>" name="campaign_id" id="campaign_id" class="previewEdit" /></td>
                </tr>
            	<tr>
                	<td class="label">Campaign Name:</td>
                    <td><?php echo $campaign_name; ?></td>
                </tr>
<?php
			if ($survey_type == "PRESS1")
			{
?>
            	<tr>
                	<td class="label">Audio File:</td>
                    <td><?php echo $survey_info->survey_first_audio_file; ?><input id="survey_first_audio_file" type="text" maxlength="50" size="25" style="display:none;" value="<?php echo $survey_info->survey_first_audio_file; ?>" /><input type="button" value="Audio" style="display:none;" class="selectAudio" /></td>
                </tr>
                <tr>
                	<td class="label">Survey Method:</td>
                    <td><?php echo $survey_info->survey_method; ?>
                        <select id="survey_method" style="display:none;">
                            <option value="AGENT_XFER">CAMPAIGN</option>
                            <option value="EXTENSION">DID</option>
                            <option value="CALLMENU">CALLMENU</option>
                        </select>
                    </td>
                </tr>
                <tr>
                	<td class="label">Survey Call Menu:</td>
                    <td>
			<?php
			$callMenuArray[''] = "---NONE---";
			if (count($call_menus) > 0) {
				foreach ($call_menus as $menu) {
					$callMenuArray[$menu->menu_id] = "{$menu->menu_id} - {$menu->menu_name}";
				}
			}
			echo form_dropdown('survey_menu_id',$callMenuArray,null,'id="survey_menu_id" style="width:300px;"');
			?>
                    </td>
                </tr>
            	<tr class="advanced">
                	<td class="label">Survey DTMF Digits:</td>
                    <td><?php echo $survey_info->survey_dtmf_digits; ?><input id="survey_dtmf_digits" type="text" maxlength="16" size="16" placeholder="eg. 0123456789*#" style="display:none;" value="<?php echo $survey_info->survey_dtmf_digits; ?>" /> <small>* customer define key press e.g.0123456789*#</small></td>
                </tr>
            	<tr class="advanced">
                	<td class="label">DID:</td>
                    <td><?php echo $survey_info->survey_xfer_exten; ?><input id="survey_xfer_exten" type="text" maxlength="25" size="20" style="display:none;" value="<?php echo $survey_info->survey_xfer_exten; ?>" /></td>
                </tr>
                <tr class="advanced">
					<td colspan="2">&nbsp;</td>
                </tr>
            	<tr class="advanced">
                	<td class="label">Press 8 Not Interested Digit:</td>
                    <td><?php echo $survey_info->survey_ni_digit; ?><input id="survey_ni_digit" type="text" maxlength="10" size="5" style="display:none;" value="<?php echo $survey_info->survey_ni_digit; ?>" /></td>
                </tr>
            	<tr class="advanced">
                	<td class="label">Press 8 Not Interested Audio File:</td>
                    <td><?php echo $survey_info->survey_ni_audio_file; ?><input id="survey_ni_audio_file" type="text" maxlength="50" size="25" style="display:none;" value="<?php echo $survey_info->survey_ni_audio_file; ?>" /><input type="button" style="display:none;" value="Audio" class="selectAudio" /></td>
                </tr>
            	<tr class="advanced">
                	<td class="label">Press 8 Not Interested Status:</td>
                    <td><?php echo $survey_info->survey_ni_status; ?>
                        <select id="survey_ni_status" style="display:none;">
                            <option value="NI">NI - Not Interested</option>
                            <option value="DNC">DNC - Do Not Call</option>
                        </select>
					</td>
                </tr>
                <tr class="advanced">
					<td colspan="2">&nbsp;</td>
                </tr>
            	<tr class="advanced">
                	<td class="label">Press 3 Digit:</td>
                    <td><?php echo $survey_info->survey_third_digit; ?><input id="survey_third_digit" type="text" maxlength="10" size="5" style="display:none;" value="<?php echo $survey_info->survey_third_digit; ?>" /></td>
                </tr>
            	<tr class="advanced">
                	<td class="label">Press 3 Audio File:</td>
                    <td><?php echo $survey_info->survey_third_audio_file; ?><input id="survey_third_audio_file" type="text" maxlength="50" size="25" style="display:none;" value="<?php echo $survey_info->survey_third_audio_file; ?>" /><input type="button" style="display:none;" value="Audio" class="selectAudio" /></td>
                </tr>
            	<tr class="advanced">
                	<td class="label">Press 3 Status:</td>
                    <td><?php echo $survey_info->survey_third_status; ?><input id="survey_third_status" type="text" maxlength="6" size="10" style="display:none;" value="<?php echo $survey_info->survey_third_status; ?>" /></td>
                </tr>
            	<tr class="advanced">
                	<td class="label">Press 3 DID:</td>
                    <td><?php echo $survey_info->survey_third_exten; ?><input id="survey_third_exten" type="text" maxlength="25" size="20" style="display:none;" value="<?php echo $survey_info->survey_third_exten; ?>" /></td>
                </tr>
                <tr class="advanced">
					<td colspan="2">&nbsp;</td>
                </tr>
            	<tr class="advanced">
                	<td class="label">Press 4 Digit:</td>
                    <td><?php echo $survey_info->survey_fourth_digit; ?><input id="survey_fourth_digit" type="text" maxlength="10" size="5" style="display:none;" value="<?php echo $survey_info->survey_fourth_digit; ?>" /></td>
                </tr>
            	<tr class="advanced">
                	<td class="label">Press 4 Audio File:</td>
                    <td><?php echo $survey_info->survey_fourth_audio_file; ?><input id="survey_fourth_audio_file" type="text" maxlength="50" size="25" style="display:none;" value="<?php echo $survey_info->survey_fourth_audio_file; ?>" /><input type="button" style="display:none;" value="Audio" class="selectAudio" /></td>
                </tr>
            	<tr class="advanced">
                	<td class="label">Press 4 Status:</td>
                    <td><?php echo $survey_info->survey_fourth_status; ?><input id="survey_fourth_status" type="text" maxlength="6" size="10" style="display:none;" value="<?php echo $survey_info->survey_fourth_status; ?>" /></td>
                </tr>
            	<tr class="advanced">
                	<td class="label">Press 4 DID:</td>
                    <td><?php echo $survey_info->survey_fourth_exten; ?><input id="survey_fourth_exten" type="text" maxlength="25" size="20" style="display:none;" value="<?php echo $survey_info->survey_fourth_exten; ?>" /></td>
                </tr>
                <tr class="advanced">
					<td colspan="2">&nbsp;</td>
                </tr>
<?php
			}
?>
				<tr style="<?php echo ($this->commonhelper->checkIfTenant($accountNum)) ? 'display:none' : ''?>">
					<?php
					if (!$this->commonhelper->checkIfTenant($accountNum))
						$dial_prefix["--CUSTOM--"] = "CUSTOM DIAL PREFIX";
					$selected_prefix = "";
					$show_custom_prefix = 'style="display:none;"';
					foreach ($carrier_info as $id => $carrier)
					{
						$prefix = str_replace("N","",str_replace("X","",$carrier['prefix']));
						if (strlen($prefix) > 0)
						{
							$dial_prefix[$id] = "$id - $prefix - {$carrier['carrier_name']}";
							
							if ($prefix == $dialPrefix)
								$selected_prefix = $id;
						}
					}
					if (strlen($selected_prefix) == 0)
					{
						$selected_prefix = "--CUSTOM--";
						$show_custom_prefix = "";
					}
					?>
					<td style="text-align:right;white-space: nowrap;font-weight: bold;">Carrier to use for this Campaign:</td><td style="white-space: nowrap;"><?=form_dropdown('dial_prefix',$dial_prefix,$selected_prefix,'id="dial_prefix" style="width:200px;" class="previewEdit"') ?> <?=form_input('custom_prefix',$dialPrefix,'id="custom_prefix" class="previewEdit" size="8" maxlength="20" '.$show_custom_prefix) ?></td>
				</tr>
            	<tr>
                	<td class="label">Status:</td>
                    <td>INACTIVE<select id="remote_agent_status" style="display:none;"><option>INACTIVE</option><option>ACTIVE</option></select></td>
                </tr>
            	<tr>
                	<td class="label">Number of Lines:</td>
                    <td><?php echo $survey_info->number_of_lines; ?>
                        <select id="number_of_lines" style="display:none;">
                            <option>1</option>
                            <option>5</option>
                            <option>10</option>
                            <option>15</option>
                            <option>20</option>
                            <option>30</option>
                        </select>
                    </td>
                </tr>
<?php
			if ($survey_type == "PRESS1")
			{
?>
                <tr>
					<td colspan="2" style="text-align:right;"><span id="showAdvance">Advance Settings</span></td>
                </tr>
<?php
			}
?>
            </table>
<?php
		}

		if ($step<4 && $campType=='Survey')
		{
?>
            <form action="<?php echo $base; ?>index.php/go_campaign_ce/go_upload_wav" id="uploadform" name="uploadform" method="post" enctype="multipart/form-data">
            <input type="hidden" name='account_num' id="account_num" value="<?php echo $accountNum ?>">
            <input type="hidden" name='campaign_type' id="campaign_type" value="<?php echo $campType; ?>" />
            <input type="hidden" name='num_channels' id="num_channels" value="<?php echo $num_channels; ?>" />
            <input type="hidden" name='survey_type' id="survey_type" value="<?php echo $survey_type; ?>" />
            <input type="hidden" name='campaign_id' id="campaign_id" value="<?php echo $campaign_id; ?>" />
<?php
			if ($step==2)
			{
?>
	    <table id="survey_audio" style="width:100%;">
            	<tr>
                	<td style="text-align:center;font-weight:bold;">Please upload .wav file (16bit Mono 8k PCM WAV audio files only)</td>
                </tr>
            	<tr>
                	<td style="text-align:center"><input type="file" name="wavFile1" id="wavFile1" value="" accept="audio/*" /></td>
                </tr>
            </table>
            </form>
            <br />
            <div style="text-align:center"><span id="more" style="cursor:pointer;">More+</span> | <span id="upload" style="cursor:pointer;">Upload</span></div>
            <div style="text-align:center" id="uploadResult"></div>
<?php
			}
		}

		if ($step==2 && $campType=='Copy')
		{
			switch ($dial_method)
			{
				case "RATIO":
					$dial_method = "Auto-Dial";
					break;
				case "ADAPT_AVERAGE":
					$dial_method = "Predictive";
					break;
				case "MANUAL":
					$dial_method = "Manual";
					break;
				default:
			}

			switch ($auto_dial_level)
			{
				case "0":
					$auto_dial_level = "OFF";
					break;
				case "1.0":
					$auto_dial_level = "SLOW";
					break;
				case "2.0":
					$auto_dial_level = "NORMAL";
					break;
				case "4.0":
					$auto_dial_level = "HIGH";
					break;
				case "6.0":
					$auto_dial_level = "MAX";
					break;
				default:
					$auto_dial_level = "ADVANCE";
			}

			switch ($campaign_vdad_exten)
			{
				case "8375":
					$campaign_vdad_exten = "ON";
					break;
				case "8369":
					$campaign_vdad_exten = "ON";
					break;
				default:
					$campaign_vdad_exten = "OFF";
			}
?>
            <input type="hidden" name='campaign_type' id="campaign_type" value="<?php echo $campType; ?>" />
			<table style="width:100%;">
            	<tr>
                	<td class="label">Campaign ID:</td>
                    <td><?php echo $campaign_id; ?></td>
                </tr>
            	<tr>
                	<td class="label">Campaign Name:</td>
                    <td><?php echo $campaign_name; ?></td>
                </tr>
            	<tr>
                	<td class="label">Dial Method:</td>
                    <td><?php echo $dial_method; ?></td>
                </tr>
            	<tr>
                	<td class="label">Auto-Dial Level:</td>
                    <td><?php echo $auto_dial_level; ?></td>
                </tr>
            	<tr>
                	<td class="label">Campaign CallerID:</td>
                    <td><?php echo $campaign_cid; ?></td>
                </tr>
            	<tr>
                	<td class="label">Campaign Recording:</td>
                    <td><?php echo $campaign_recording; ?></td>
                </tr>
            	<tr>
                	<td class="label">Answering Machine Detection:</td>
                    <td><?php echo $campaign_vdad_exten; ?></td>
                </tr>
            </table>
<?php
		}
?>
        </td>
    </tr>
</table>

<div id="upload_result">
            <?php echo $columns; ?>
</div>
