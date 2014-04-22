<?php
############################################################################################
####  Name:             go_campaign_wizard.php                                          ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();
$NOW = date('Y-m-d');

foreach ($campaigns as $camp)
{
	if ($camp->campaign_vdad_exten != '8373' && $camp->campaign_vdad_exten != '8366')
	    $camp_opt .= "<option value=\"{$camp->campaign_id}\">{$camp->campaign_id} - {$camp->campaign_name}</option>\n";
}
?>
<style type="text/css">
#campTable td{
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
</style>

<script>
// Color Picker
var myPicker = new jscolor.color(document.getElementById('group_color'), {})
myPicker.fromString('FFFFFF')  // now you can access API via 'myPicker' variable

$(function()
{
	$('#campaign_type').change(function()
	{
		var steps = 0;
		$('#campTitle').html('Campaign Wizard &raquo; '+$(this).val());
		$('#call_route').val('NONE');
		$('#did_pattern').val('');
		$('.showAgent').hide();
		$('.showEmail').hide();
		$('.showFirst').hide();
		$('.showSecond').hide();
		$('#email_address').val('');
		$('#agent').val('NONE');
		myPicker.fromString('FFFFFF');
		
		if ($(this).val() != 'Copy Campaign')
			$('#campaign_name').val($(this).val()+' Campaign - <?=$NOW?>');
		else
			$('#campaign_name').val('Copy Campaign '+$('#copy_from').val()+' - <?=$NOW?>');

		switch($(this).val())
		{
			case 'Inbound':
				steps = 2;
				$('.showFirst').show();
				$('.callRoute').show();
				$('.survey').hide();
				$('.copyCampaign').hide();
				$('#next').attr('disabled','disabled');
				break;
			case 'Survey':
				steps = 4;
				$('.callRoute').hide();
				$('.survey').show();
				$('.copyCampaign').hide();
				break;
			default:
				steps = 3;
				$('.survey').hide();
				if ($(this).val() == 'Blended')
				{
					$('.showFirst').show();
					$('.callRoute').show();
					$('.copyCampaign').hide();
					$('#next').attr('disabled','disabled');
				}
				else
				{
					$('.callRoute').hide();
					if ($(this).val() == 'Copy Campaign')
					{
						steps = 2;
					    $('.copyCampaign').show();
					}
					else
					{
					    $('.copyCampaign').hide();
					}
				}
		}

		$('#small_step_number img').attr('src','<?php echo $base; ?>img/step1of'+steps+'-navigation-small.png');
	});
	
	$('#copy_from').change(function()
	{
		$('#campaign_name').val('Copy Campaign '+$(this).val()+' - <?=$NOW?>');
	});

	$('#call_route').change(function()
	{
		$('.showSecond').show();
		if ($(this).val() != 'NONE')
			$('#next').removeAttr('disabled');

		$(this).each(function()
		{
			switch ($(this).val())
			{
				case 'AGENT':
					$('.showAgent').show();
					$('.showEmail').hide();
					$('#email_address').val('');
					break;
				case 'VOICEMAIL':
					$('.showAgent').hide();
					$('.showEmail').show();
					$('#agent').val('NONE');
					break;
				default:
					$('.showAgent').hide();
					$('.showEmail').hide();
					$('#email_address').val('');
					$('#agent').val('NONE');
			}
		});
	});

	var alreadyclicked=false;
	$('#next').click(function()
	{
		if (alreadyclicked)
		{
			alreadyclicked=false;
			clearTimeout(alreadyclickedTimeout);
			return false;
		} else {
			alreadyclicked=true;
			var alreadyclickedTimeout=setTimeout(function()
			{
				alreadyclicked=false;
			},200);
		}
		var campaign_type = $('#campaign_type').val();
		var campaign_id = $('#campaignIDSpan').text();
		var campaign_name = '';
		var did_pattern = $('#did_pattern').val();
		var call_route = $('#call_route').val();
		var step = $('#stepNumber').text();
		var isBack = $('#isBack').text();
		var steps = 0;
		var args = '';
		var copy_from = '';
		var isEmpty = 0;
		$('#backNumber').text(step);

		if (campaign_type=='Copy Campaign')
		    campaign_type='Copy';
			
		if (step < 2 && (campaign_type == 'Inbound' || campaign_type == 'Blended') && (did_pattern === 'undefined' || did_pattern == '0000000000' || did_pattern.length < 1))
		{
			$('#did_pattern').css('border','1px solid red');
			$('#did_pattern').focus();
			alert('Please input the your DID/TFN Extension.\nShould not be empty.');
			isEmpty = 1;
		}
		
		if (step < 2 && $('#dloading').html().match(/Not Available/))
		{
			alert("DID/TFN Not Available.");
			isEmpty = 1;
		}
		
		if (step < 2 && $('#campaign_name').val().length < 6)
		{
			alert("Campaign Name should be at least 6 characters in length.");
			isEmpty = 1;
		}

		if (!isEmpty)
		{
			if (step < 2)
			{
				if ($('#campaign_id').val().length > 0)
					campaign_id = $('#campaign_id').val();
					
				if ($('#campaign_name').val().length > 0)
				{
					campaign_name = $('#campaign_name').val();
					campaign_name = campaign_name.replace(/ /g,'+');
				}
			}
	
			if ($(this).text() != 'Finish')
			{
				step++;
				$('#stepNumber').text(step);
				$('#wizardSpan').text('true');
	
				switch(campaign_type)
				{
					case 'Inbound':
						steps = 2;
						if (step==2)
						{
							$('#campTitle').append(' &raquo; Information');
	// 						$('#wizardSpan').text('false');
						}
						break;
					case 'Survey':
						steps = 4;
						if (step==3)
						{
							$('#campTitle').append(' &raquo; Load Leads');
						}
						if (step==4)
						{
							$('#campTitle').append(' &raquo; Information');
	// 						$('#wizardSpan').text('false');
						}
						break;
					case 'Copy':
						steps = 2;
	// 					if (step==3)
	// 					{
	// 						$('#campTitle').append(' &raquo; Load Leads');
	// 					}
						if (step==2)
						{
							$('#campTitle').append(' &raquo; Information');
	// 						$('#wizardSpan').text('false');
						}
						break;
					default:
						steps = 3;
						if (step==2)
						{
							$('#campTitle').append(' &raquo; Load Leads');
						}
						if (step==3)
						{
							$('#campTitle').append(' &raquo; Information');
	// 						$('#wizardSpan').text('false');
						}
				}
	
				$('#small_step_number img').attr('src','<?php echo $base; ?>img/step'+step+'of'+steps+'-navigation-small.png');
				$('#step_number img').attr('src','<?php echo $base; ?>img/step'+step+'-trans.png');
	
				if ((step==2 && (campaign_type=='Outbound' || campaign_type=='Blended')) || (step==2 && campaign_type=='Survey') || (step==3 && campaign_type=='Copy'))
				{
					$(this).html('Next');
					$('#back').css('display','inline');
					$('#back_pipe').css('display','inline');
				}
	
	// 			if (step==3 && campaign_type=='Survey')
	// 			{
	// 				$('#back').css('display','none');
	// 				$('#back_pipe').css('display','none');
	// 			}
	
				if ((step==3 && (campaign_type=='Outbound' || campaign_type=='Blended')) || (step==2 && campaign_type=='Inbound') || (step==4 && campaign_type=='Survey' || campaign_type=='Copy'))
				{
					$(this).html('Finish');
					$(this).css('width', '50px');
	// 				$('#back').css('display','none');
	// 				$('#back_pipe').css('display','none');
					$('#back').css('display','inline');
					$('#back_pipe').css('display','inline');
					$('#modify').css('display','inline');
					$('#modify_pipe').css('display','inline');
				}
	
				if (step==2 && campaign_type=='Copy')
				{
					copy_from = $('#copy_from').val();
	// 				$(this).css('width', '50px');
	// 				$('#saveButtons').css({'text-align': 'center', 'width': '160px'});
	// 				$('#saveButtons').prepend('<span id="back" style="width:60px;">Back</span> | ');
				}
	
				if (step==2 && (campaign_type!='Outbound' || campaign_type!='Copy'))
				{
					if (call_route != 'NONE' && call_route.length > 0)
					{
						switch (call_route)
						{
							case "AGENT":
								args = '/' + $('#did_pattern').val() + '/' + $('#group_color').val() + '/' + $('#agent').val();
								break;
							case "VOICEMAIL":
								if ($('#email_address').val()!='')
								{
									var email_address = $('#email_address').val();
								} else {
									var email_address = 'undefined';
								}
								args = '/' + $('#did_pattern').val() + '/' + $('#group_color').val() + '/' + email_address;
								break;
							default:
								args = '/' + $('#did_pattern').val() + '/' + $('#group_color').val() + '/undefined';
						}
					}
				}
	
				if (campaign_type == 'Survey')
				{
					var fileNum = '/NULL';
					if (step==3 && $('#fileNames').text()!='')
						fileNum = '/' + $('#fileNames').text();
	
					if ($('#survey_type').val()!='')
					{
						var surveyType = $('#survey_type').val();
						$('#surveyType').text(surveyType);
					}
	
					if ($('#num_channels').val()!='')
					{
						var numChannels = $('#num_channels').val();
						$('#numChannels').text(numChannels);
					}
	
					args = '/' + surveyType + '/' + numChannels + fileNum + '/' + isBack;
				} else {
					args = args + '/' + isBack;
				}
				if (copy_from.length > 0)
					args = args + '/' + copy_from;
				
				if (campaign_id.length < 1)
					campaign_id = 'undefined';
				
				if (campaign_name.length > 0)
					args = args + '/' + campaign_name;
					
				if (call_route !== 'undefined')
					args = args + '/' + call_route;
	
				$('#wizardContent').load('<?php echo $base; ?>index.php/go_campaign_ce/go_campaign_wizard/'+campaign_type+'/'+step+'/'+campaign_id + args);
			}
			else
			{
				if (campaign_type!='Survey' || campaign_type!='Copy')
				{
					var previewVars = $('.previewEdit').serialize();
					$.post('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_preview/',previewVars);
				}
				
				$('#box').animate({'top':'-2550px'},500);
				$('#overlay').fadeOut('slow');
	
				$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
				$('#table_reports').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/');
			}
		}
	});

	$('#modify').click(function()
	{
		var campaign_type = $('#campaign_type').val();
		if (campaign_type!='Survey' || campaign_type!='Copy')
		{
			var previewVars = $('.previewEdit').serialize();
			$.post('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_preview/',previewVars);
		}
		
		if (alreadyclicked)
		{
			alreadyclicked=false;
			clearTimeout(alreadyclickedTimeout);
			return false;
		} else {
			alreadyclicked=true;
			var alreadyclickedTimeout=setTimeout(function()
			{
				alreadyclicked=false;
			},200);
		}
		$('#box').animate({'top':'-2550px'},500);
		$('#overlay').fadeOut('slow');

		var campaign_id = $('#campaignIDSpan').text();
		$('#table_reports').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_list/').fadeIn("slow");
		var modifyCampTimer=setTimeout(function()
		{
			modify(campaign_id);
		},500);
	});

	$('#back').click(function()
	{
		if (alreadyclicked)
		{
			alreadyclicked=false;
			clearTimeout(alreadyclickedTimeout);
			return false;
		} else {
			alreadyclicked=true;
			var alreadyclickedTimeout=setTimeout(function()
			{
				alreadyclicked=false;
			},200);
		}
		var campaign_type = $('#campaignTypeSpan').text();
		var campaign_id = $('#campaignIDSpan').text();
		var step = $('#backNumber').text();
		var surveyType = $('#surveyType').text();
		var numChannels = $('#numChannels').text();
		$('#stepNumber').text(step);
		$('#backNumber').text(step - 1);
		$('#isBack').text('true');

		switch(campaign_type)
		{
			case 'Survey':
				if (step==2)
				{
					$('#campTitle').html($('#campTitle').html().replace(' » Load Leads',''));
				}
				if (step==3)
				{
					$('#campTitle').html($('#campTitle').html().replace(' » Information',''));
				}
				break;
			default:
				if (step==2)
				{
					$('#campTitle').html($('#campTitle').html().replace(' » Information',''));
				}
		}

		if (step==1)
		{
			$('#wizardContent').load('<?php echo $base; ?>index.php/go_campaign_ce/go_campaign_wizard/back/'+step+'/'+campaign_id, function() {
				$('#overlayContent').load('<? echo $base; ?>index.php/go_campaign_ce/go_campaign_wizard/');
			});
		} else {
			if (campaign_type == 'Survey')
			{
				var fileNum = '/NULL';
				var isBack = $('#isBack').text();
				if (step==3 && $('#fileNames').text()!='')
					fileNum = '/' + $('#fileNames').text();

				var args = '/' + surveyType + '/' + numChannels + fileNum + '/' + isBack;
				$('#wizardContent').load('<?php echo $base; ?>index.php/go_campaign_ce/go_campaign_wizard/'+campaign_type+'/'+step+'/'+campaign_id + args);
			} else {
				var isBack = $('#isBack').text();
				$('#wizardContent').load('<?php echo $base; ?>index.php/go_campaign_ce/go_campaign_wizard/'+campaign_type+'/'+step+'/'+campaign_id+'/'+isBack);
			}

			if ((step==2 && (campaign_type=='Outbound' || campaign_type=='Blended')) || (step==3 && campaign_type=='Survey' || campaign_type=='Copy'))
			{
				$('#next').html('Skip');
				$('#modify').css('display','none');
				$('#modify_pipe').css('display','none');
			}
		}
	});
	
	$('#did_pattern').keyup(function(event) {
		if ($(this).val().length > 0 && event.keyCode != 8)
		{
			$(this).css('border','1px solid #DFDFDF');
			$('#dloading').load('<? echo $base; ?>index.php/go_campaign_ce/go_check_did/'+$(this).val());
		} else {
			$(this).css('border','1px solid red');
			$('#dloading').empty();
		}
		
		if ($(this).val().length < 1 && event.keyCode == 8)
		{
			$(this).css('border','1px solid red');
			$('#dloading').empty();
		}
	});

	$('#did_pattern').keydown(function(event) {
        // Allow: backspace, delete, tab, escape, and enter
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) ||
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
				// let it happen, don't do anything
				return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault();
            }
        }
    });
	
	$('#autoCampID').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('#campaign_id').removeAttr('readonly');
			$('#campaign_name').removeAttr('readonly');
		} else {
			$('#campaign_id').attr('readonly','true');
			$('#campaign_name').attr('readonly','true');
		}
	});
	
	$('#campaign_id').blur(function()
	{
		if ($(this).val().length < 3)
		{
			alert('Campaign ID must be between 3 to 8 characters.');
		}
	});
	
	$('#campaign_name').keyup(function(e)
	{
		if(e.which === 13)
			return false;
		
		if ($(this).val().length < 1)
		{
			alert('Campaign Name should not be empty.');
		}
	});
	
	$('#campaign_id').keyup(function(event)
	{
		
		if (event.keyCode != 17)
		{
			if ($(this).val().length > 2)
			{
				$(this).css('border','solid 1px #999');
			
				//$('#aloading').empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
				$('#aloading').load('<? echo $base; ?>index.php/go_campaign_ce/go_check_campaign/'+$(this).val());
			} else {
				$('#aloading').html("<small style=\"color:red;\">Minimum of 3 characters.</small>");
			}
		}
	});
	
	$('#campaign_id,#campaign_name').bind("keydown keypress", function(event)
	{
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.type == "keydown") {
			// For normal key press
			if ((event.keyCode == 32 && $(this).attr('id') != "campaign_name") || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
				|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
				|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
				return false;
			
			if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
				return false;
			
			if (!event.shiftKey && event.keyCode == 173 && $(this).attr('id') != "campaign_name")
				return false;
		} else {
			// For ASCII Key Codes
			if ((event.which > 32 && event.which < 45) || (event.which == 45 && $(this).attr('id') != "campaign_name") || (event.which > 45 && event.which < 48)
				|| (event.which == 32 && $(this).attr('id') != "campaign_name") || (event.which > 57 && event.which < 65)
				|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
				return false;
		}
		
		$(this).css('border','solid 1px #999');
	});
});
</script>

<div style="float:right;" id="small_step_number"><img src="<?php echo $base; ?>img/step1of3-navigation-small.png" /></div>
<div id="campTitle" style="font-weight:bold;font-size:16px;color:#333;">Campaign Wizard &raquo; Outbound</div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />

<table style="width:100%;">
	<tr>
		<td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step1-trans.png" /></div>
		</td>
		<td valign="top">
            <span id="wizardContent" style="height:100px; padding-top:10px;">
                <table id="campTable" style="width:100%;">
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        Campaign Type:
                        </td>
                        <td>
                        <select id="campaign_type">
                            <option>Outbound</option>
                            <option>Inbound</option>
                            <option>Blended</option>
                            <option>Survey</option>
                            <option>Copy Campaign</option>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        Campaign ID:
                        </td>
                        <td>
                        <input type="text" id="campaign_id" maxlength="8" size="15" readonly="readonly" value="<?=$campaign_id?>" />
						<input type="checkbox" id="autoCampID" /> <small><font size="1" color="red">(check to edit campaign id and name)</font></small><br />
						<span id="aloading"></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        Campaign Name:
                        </td>
                        <td>
                        <input type="text" id="campaign_name" maxlength="40" size="35" readonly="readonly" value="Outbound Campaign - <?=$NOW?>" />
                        </td>
                    </tr>
                    <tr class="showFirst" style="display:none;">
                        <td style="text-align:right;width:250px;font-weight:bold;">
                        DID/TFN Extension:
                        </td>
                        <td>
                        <input type="text" id="did_pattern" size="20" value="" /> <span id="dloading"></span> <small style="color:red">(accepts only numbers)</small>
                        </td>
                    </tr>
                    <tr class="callRoute" style="display:none;">
                        <td style="text-align:right;width:250px;">
                        Call Route:
                        </td>
                        <td>
                        <select id="call_route">
                            <option value="NONE"></option>
                            <option value="INGROUP">INGROUP (campaign)</option>
                            <option value="IVR">IVR (call menu)</option>
                            <option value="AGENT">AGENT</option>
                            <option value="VOICEMAIL">VOICEMAIL</option>
                        </select>
                        </td>
                    </tr>
                    <tr class="copyCampaign" style="display:none;">
                        <td style="text-align:right;width:250px;">
                        Copy From:
                        </td>
                        <td>
                        <select id="copy_from">
                            <?php echo $camp_opt; ?>
                        </select>
                        </td>
                    </tr>
                    <tr class="survey" style="display:none;">
                        <td style="text-align:right;width:250px;">
                        Survey Type:
                        </td>
                        <td>
                        <select id="survey_type">
                            <option value="BROADCAST">VOICE BROADCAST</option>
                            <option value="PRESS1">SURVEY PRESS1</option>
                        </select>
                        </td>
                    </tr>
                    <tr class="survey" style="display:none;">
                        <td style="text-align:right;width:250px;">
                        Number of Channels:
                        </td>
                        <td>
                        <select id="num_channels">
                            <option>1</option>
                            <option>5</option>
                            <option>10</option>
                            <option>15</option>
                            <option>20</option>
                            <option>30</option>
                        </select>
                        </td>
                    </tr>
                    <tr class="showAgent" style="display:none;">
                        <td style="text-align:right;width:250px;">
                        Agent:
                        </td>
                        <td>
                        <select id="agent">
                        	<option value="NONE">NONE</option>
							<?php
                            foreach ($list_agents as $agent)
                            {
                                echo "<option value=\"".$agent->phone_login."\">".$agent->user." - ".$agent->full_name."</option>\n";
                            }
                            ?>
                        </select>
                        </td>
                    </tr>
                    <tr class="showEmail" style="display:none;">
                        <td style="text-align:right;width:250px;">
                        Email:
                        </td>
                        <td>
                        <input type="text" id="email_address" name="email_address" value="" />
                        </td>
                    </tr>
                    <tr class="showSecond" style="display:none;">
                        <td style="text-align:right;width:250px;">
                        Group Color:
                        </td>
                        <td>
                        <input type="text" id="group_color" name="group_color" class="color" value="FFFFFF" />
                        </td>
                    </tr>
                </table>
            </span>
		</td>
	</tr>
    <tr>
        <td valign="bottom" colspan="2" style="text-align:right;">
        <span id="stepNumber" style="display:none;">1</span>
        <span id="backNumber" style="display:none;">1</span>
        <span id="campaignIDSpan" style="display:none;"></span>
        <span id="campaignTypeSpan" style="display:none;"></span>
        <span id="surveyType" style="display:none;"></span>
        <span id="numChannels" style="display:none;"></span>
        <span id="fileNames" style="display:none;"></span>
        <span id="isBack" style="display:none;">false</span>
        </td>
    </tr>
</table>
<hr style="border:#DFDFDF 1px solid;" />
<span id="saveButtons"><span id="back" style="display:none;white-space: nowrap;">Back</span><span id="back_pipe" style="display:none;white-space: nowrap;"> | </span><span id="next" style="white-space: nowrap;">Next</span><span id="modify_pipe" style="display:none;white-space: nowrap;"> | </span><span id="modify" style="display:none;white-space: nowrap;">Modify</span></span>
