<?php
####################################################################################################
####  Name:             	go_state_calltimes_wizard.php                                       ####
####  Type:             	ci views for state calltime wizard - administrator                  ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Written by:       	Christopher Lomuntad                                                ####
####  License:          	AGPLv2                                                              ####
####################################################################################################

$base = base_url();
$NOW = date('Y-m-d');

for ($i=0;$i<10;$i++)
{
	$randPrefix .= rand(0,9);
}
?>
<style type="text/css">
#carrierTable input,
#carrierTable select,
#carrierTable textarea {
	border: 1px solid #DFDFDF;
}

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

.separator {
	display: none;
}

.selectAudio {
	font-size:11px;
}

.selectAudio:hover,.otherLinks:hover {
	text-decoration:none;
}

::-webkit-input-placeholder { /* WebKit browsers */
    color:     #777;
	font-size: 12px;
}
:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
    color:     #777;
	font-size: 12px;
}
::-moz-placeholder { /* Mozilla Firefox 19+ */
    color:     #777;
	font-size: 12px;
}
:-ms-input-placeholder { /* Internet Explorer 10+ */
    color:     #777;
	font-size: 12px;
}
</style>

<script>
$(function()
{
	$('#state_call_time_id').keyup(function(e)
	{
		if ($(this).val().length > 2)
		{
			$('#state_call_time_name').css('border','solid 1px #DFDFDF');
		
			//$('#aloading').empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
			$('#aloading').load('<? echo $base; ?>index.php/go_calltimes_ce/go_check_state_calltimes/'+$(this).val());
		} else {
			$('#aloading').html("<small style=\"color:red;\"><? echo $this->lang->line("go_min_3_char"); ?>.</small>");
		}
	});
	
	$('#state_call_time_id').keydown(function(event)
	{
		$(this).css('border','solid 1px #DFDFDF');
	});

    $('#state_call_time_id,#state_call_time_state,#state_call_time_name,#state_call_time_comments').keydown(function(event)
	{
		//console.log(event.keyCode);
		if ((event.keyCode == 32 && ($(this).attr('id') != 'state_call_time_name' && $(this).attr('id') != 'state_call_time_comments')) || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
			|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || (event.keyCode == 190 && (($(this).attr('id') != 'state_call_time_name' && $(this).attr('id') != 'state_call_time_comments') || event.shiftKey))
			|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
			return false;
		
		if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
			return false;
		
		if (!event.shiftKey && event.keyCode == 173 && $(this).attr('id') != 'state_call_time_id')
			return false;
		
		$(this).css('border','solid 1px #DFDFDF');
    });
	
	$('#next').click(function()
	{
		var isEmpty = 0;
		if ($('#state_call_time_id').val() === "" || $('#state_call_time_id').val().length < 3)
		{
			$('#state_call_time_id').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#state_call_time_state').val() === "")
		{
			$('#state_call_time_state').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#state_call_time_name').val() === "")
		{
			$('#state_call_time_name').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#aloading').html().match(/Not Available/))
		{
			alert("<? echo $this->lang->line("go_state_call_time_id_navailable"); ?>.");
			$('#state_call_time_id').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if (!isEmpty)
		{
			$('#stepOne').hide();
			$('#stepTwo').show();
			$('#back').show();
			$('#next').hide();
			$('#submit').show();
			$('.separator').show();
			$('#step_number > img').attr('src','<?php echo $base; ?>img/step2-trans.png');
			$('#small_step_number > img').attr('src','<?php echo $base; ?>img/step2of2-navigation-small.png');
		}
	});
	
	$('#back').click(function()
	{
		$('#stepOne').show();
		$('#stepTwo').hide();
		$('#back').hide();
		$('#next').show();
		$('#submit').hide();
		$('.separator').hide();
		$('#step_number > img').attr('src','<?php echo $base; ?>img/step1-trans.png');
		$('#small_step_number > img').attr('src','<?php echo $base; ?>img/step1of2-navigation-small.png');
	});
	
	$('#submit').click(function()
	{
		var isEmpty = 0;
		if ($('#state_call_time_id').val() === "" || $('#state_call_time_id').val().length < 3)
		{
			$('#state_call_time_id').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#state_call_time_name').val() === "")
		{
			$('#state_call_time_name').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#aloading').html().match(/Not Available/))
		{
			alert("<? echo $this->lang->line("go_state_call_not_avai"); ?>.");
			isEmpty = 1;
		}
		
		if (!isEmpty)
		{
			var items = $('#calltimesForm').serialize();
			$.post("<?=$base?>index.php/go_calltimes_ce/go_state_calltimes_wizard", { items: items, action: "create" },
			function(data){
				if (data=="SUCCESS")
				{
					alert("<? echo $this->lang->line("go_success_added_new_state_ct"); ?>.");
				
					$('#box').animate({'top':'-2550px'},500);
					$('#overlay').fadeOut('slow');
					
					location.reload();
				}
	
				if (data=="FAILED")
				{
					alert("<? echo $this->lang->line("go_state_Call_exist"); ?>");
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
	
	$("#state_call_time_state").keyup(function()
	{
		var goUp = $(this).val();
		$(this).val(goUp.toUpperCase());
	});
});
</script>

<div style="float:right;" id="small_step_number"><img src="<?php echo $base; ?>img/step1of2-navigation-small.png" /></div>
<div style="font-weight:bold;font-size:16px;color:#333;" id="header"><? echo $this->lang->line("go_state_ct_wizard"); ?> &raquo; <? echo $this->lang->line("go_add_new_state_ct"); ?></div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />

<table style="width:100%;">
	<tr>
		<td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step1-trans.png" /></div>
		</td>
		<td valign="top">
            <span id="wizardContent" style="height:100px; padding-top:10px;">
				<form id="calltimesForm" method="POST">
                <table id="stepOne" style="width:100%;">
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        <? echo $this->lang->line("go_state_ct_time_id"); ?>:
                        </td>
                        <td>
                        <?=form_input('state_call_time_id',null,'id="state_call_time_id" maxlength="10" size="12"') ?>
						<span id="aloading"></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        <? echo $this->lang->line("go_state_ct_state"); ?>:
                        </td>
                        <td>
                        <?=form_input('state_call_time_state',null,'id="state_call_time_state" maxlength="2" size="3"') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        <? echo $this->lang->line("go_state_ct"); ?>:
                        </td>
                        <td>
                        <?=form_input('state_call_time_name',null,'id="state_call_time_name" maxlength="30" size="25"') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;white-space:nowrap;">
                        <? echo $this->lang->line("go_state_ct_comments"); ?>:
                        </td>
                        <td>
                        <?=form_input('state_call_time_comments',null,'id="state_call_time_comments" maxlength="255" size="40"') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        <? echo $this->lang->line("go_user_group"); ?>:
                        </td>
                        <td>
                        <?php
						$groupArray = array("---{$this->lang->line("go_all")}---"=>strtoupper($this->lang->line("go_all_user_groups")));
						foreach ($user_groups as $group)
						{
							$groupArray[$group->user_group] = "{$group->user_group} - {$group->group_name}";
						}
						echo form_dropdown('user_group',$groupArray,null,'id="user_group"');
						?>
                        </td>
                    </tr>
                </table>
				<table id="stepTwo" style="width:100%;display:none;">
					<tr>
						<td colspan="2">
							<table border="0" cellpadding="0" cellspacing="0" style="width:50%; margin-left:auto; margin-right:auto;">
								<tr>
									<td style="font-weight:bold;width: 10%;">&nbsp;</td>
                                                                        <td style="font-weight:bold;"><? echo $this->lang->line("go_start"); ?></td>
                                                                        <td style="font-weight:bold;"><? echo $this->lang->line("go_stop"); ?></td>
								</tr>
								<tr>
                                                                        <td style="text-align: right;white-space: nowrap;"><? echo $this->lang->line("go_default"); ?>: &nbsp; </td>
                                                                        <td><?=form_input('sct_default_start','0','id="ct_default_start" maxlength="4" size="5"') ?></td>
                                                                        <td><?=form_input('sct_default_stop','0','id="ct_default_stop" maxlength="4" size="5"') ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td style="text-align: right;white-space: nowrap;"><? echo $this->lang->line("go_sun"); ?>: &nbsp; </td>
                                                                        <td><?=form_input('sct_sunday_start','0','id="ct_sunday_start" maxlength="4" size="5"') ?></td>
                                                                        <td><?=form_input('sct_sunday_stop','0','id="ct_sunday_stop" maxlength="4" size="5"') ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td style="text-align: right;white-space: nowrap;"><? echo $this->lang->line("go_mon"); ?>: &nbsp; </td>
                                                                        <td><?=form_input('sct_monday_start','0','id="ct_monday_start" maxlength="4" size="5"') ?></td>
                                                                        <td><?=form_input('sct_monday_stop','0','id="ct_monday_stop" maxlength="4" size="5"') ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td style="text-align: right;white-space: nowrap;"><? echo $this->lang->line("go_tues"); ?>: &nbsp; </td>
                                                                        <td><?=form_input('sct_tuesday_start','0','id="ct_tuesday_start" maxlength="4" size="5"') ?></td>
                                                                        <td><?=form_input('sct_tuesday_stop','0','id="ct_tuesday_stop" maxlength="4" size="5"') ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td style="text-align: right;white-space: nowrap;"><? echo $this->lang->line("go_wed"); ?>: &nbsp; </td>
                                                                        <td><?=form_input('sct_wednesday_start','0','id="ct_wednesday_start" maxlength="4" size="5"') ?></td>
                                                                        <td><?=form_input('sct_wednesday_stop','0','id="ct_wednesday_stop" maxlength="4" size="5"') ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td style="text-align: right;white-space: nowrap;"><? echo $this->lang->line("go_thurs"); ?>: &nbsp; </td>
                                                                        <td><?=form_input('sct_thursday_start','0','id="ct_thursday_start" maxlength="4" size="5"') ?></td>
                                                                        <td><?=form_input('sct_thursday_stop','0','id="ct_thursday_stop" maxlength="4" size="5"') ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td style="text-align: right;white-space: nowrap;"><? echo $this->lang->line("go_fri"); ?>: &nbsp; </td>
                                                                        <td><?=form_input('sct_friday_start','0','id="ct_friday_start" maxlength="4" size="5"') ?></td>
                                                                        <td><?=form_input('sct_friday_stop','0','id="ct_friday_stop" maxlength="4" size="5"') ?></td>
                                                                </tr>
                                                                <tr>
                                                                        <td style="text-align: right;white-space: nowrap;"><? echo $this->lang->line("go_sat"); ?>: &nbsp; </td>
									<td><?=form_input('sct_saturday_start','0','id="ct_saturday_start" maxlength="4" size="5"') ?></td>
									<td><?=form_input('sct_saturday_stop','0','id="ct_saturday_stop" maxlength="4" size="5"') ?></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				</form>
            </span>
		</td>
	</tr>
</table>
<hr style="border:#DFDFDF 1px solid;" />
<span id="saveButtons"><span id="back" style="white-space: nowrap;display: none;"><? echo $this->lang->line("go_back"); ?></span><span class="separator"> | </span><span id="next" style="white-space: nowrap;"><? echo $this->lang->line("go_next"); ?></span><span id="submit" style="white-space: nowrap;display: none;"><? echo $this->lang->line("go_submit"); ?></span></span>
