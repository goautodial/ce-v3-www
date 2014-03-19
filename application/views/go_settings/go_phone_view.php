<?php
############################################################################################
####  Name:             go_campaign_view.php                                            ####
####  Type: 		    ci views                                                    ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Christopher Lomuntad <chris@goautodial.com>   ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();

if (! $isAdvance)
	$isAdvance = 0;
	
$hideMe = "";
if ($this->session->userdata('user_group') != "ADMIN") {
	$hideMe = "style='display:none;'";
}
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
		var items = $('#modifyPhone').serialize();
		$.post("<?=$base?>index.php/go_phones_ce/go_phone_wizard", { items: items, action: "modify_phone" },
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
<div align="center" style="font-weight:bold; color:#333; font-size:16px;">MODIFY PHONE: <?php echo "{$phone_info->extension}"; ?></div>
<br />
<form id="modifyPhone" method="POST">
<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:95%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
    	<td style="text-align:right;width: 40%;" nowrap>Phone Extension/Login:</td><td>&nbsp;<?php echo "{$phone_info->extension}"; ?><?=form_hidden('extension',$phone_info->extension) ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Phone Password:</td><td><?=form_input('pass',$phone_info->pass,'size="10" maxlength="10"') ?></td>
    </tr>
	<tr <?=$hideMe?>>
    	<td style="text-align:right;" nowrap>Dial Plan Number:</td><td><?=form_input('dialplan_number',$phone_info->dialplan_number,'size="20" maxlength="20"') ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Voicemail ID:</td><td><?=form_input('voicemail_id',$phone_info->voicemail_id,'size="15" maxlength="10"') ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Outbound CallerID:</td><td><?=form_input('outbound_cid',$phone_info->outbound_cid,'size="15" maxlength="20"') ?></td>
    </tr>
	<tr style="display:none;">
    	<td style="text-align:right;" nowrap>Phone IP Address:</td><td><?=form_input('phone_ip',$phone_info->phone_ip,'size="20" maxlength="15"') ?></td>
    </tr>
	<tr style="display:none;">
    	<td style="text-align:right;" nowrap>Computer IP Address:</td><td><?=form_input('computer_ip',$phone_info->computer_ip,'size="20" maxlength="15"') ?></td>
    </tr>
	<tr <?=$hideMe?>>
    	<td style="text-align:right;" nowrap>Server IP:</td><td>
		<?php
		$serverArray = array();
		foreach ($servers as $info)
		{
			$serverArray[$info->server_ip] = "{$info->server_ip} - {$info->server_description}";
		}
		
		echo form_dropdown('server_ip',$serverArray,$phone_info->server_ip);
		?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Agent Screen Login:</td><td><?=form_input('login',$phone_info->login,'size="15" maxlength="15"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>SIP Registration Password:</td><td><?=form_input('conf_secret',$phone_info->conf_secret,'size="20" maxlength="20"') ?></td>
    </tr>
	<tr style="display:none;">
    	<td style="text-align:right;" nowrap>Set As Webphone:</td><td><?=form_hidden('is_webphone',$phone_info->is_webphone) ?></td>
    </tr>
	<tr style="display:none;">
    	<td style="text-align:right;" nowrap>Webphone Dialpad:</td><td><?=form_hidden('webphone_dialpad',$phone_info->webphone_dialpad) ?></td>
    </tr>
	<tr style="display:none;">
    	<td style="text-align:right;" nowrap>Use External Server IP:</td><td><?=form_hidden('use_external_server_ip',$phone_info->use_external_server_ip) ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Status:</td><td>
			<?php
			$statusArray = array('ACTIVE'=>'ACTIVE','SUSPENDED'=>'SUSPENDED','CLOSED'=>'CLOSED','PENDING'=>'PENDING','ADMIN'=>'ADMIN');
			
			echo form_dropdown('status',$statusArray,$phone_info->status);
			?>
		</td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Active Account:</td><td>
			<?=form_dropdown('active',array('Y'=>'Y','N'=>'N'),$phone_info->active) ?>
		</td>
    </tr>
	<tr style="display:none;">
    	<td style="text-align:right;" nowrap>Phone Type:</td><td><?=form_hidden('phone_type',$phone_info->phone_type) ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Full Name:</td><td><?=form_input('fullname',$phone_info->fullname,'size="20" maxlength="50"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Email:</td><td><?=form_input('email',$phone_info->email,'size="50" maxlength="100"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Delete Voicemail After Email:</td><td>
			<?=form_dropdown('delete_vm_after_email',array('Y'=>'Y','N'=>'N'),$phone_info->delete_vm_after_email) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Company:</td><td><?=form_input('company',$phone_info->company,'size="10" maxlength="10"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Picture:</td><td><?=form_input('picture',$phone_info->picture,'size="20" maxlength="19"') ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>New Messages:</td><td>&nbsp;<b><?php echo $phone_info->messages; ?></b></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Old Messages:</td><td>&nbsp;<b><?php echo $phone_info->old_messages; ?></b></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Client Protocol:</td><td>
			<?php
			$protocolArray = array('EXTERNAL'=>'EXTERNAL','SIP'=>'SIP','Zap'=>'Zap','IAX2'=>'IAX2');
			echo form_dropdown('protocol',$protocolArray,$phone_info->protocol);
			?>
		</td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap>Local GMT:</td><td>
			<?php
			$gmtArray = array(
				'12.75'=>'12.75','12.00'=>'12.00','11.00'=>'11.00','10.00'=>'10.00',
				'9.50'=>'9.50','9.00'=>'9.00','8.00'=>'8.00','7.00'=>'7.00',
				'6.50'=>'6.50','6.00'=>'6.00','5.75'=>'5.75','5.50'=>'5.50',
				'5.00'=>'5.00','4.50'=>'4.50','4.00'=>'4.00','3.50'=>'3.50',
				'3.00'=>'3.00','2.00'=>'2.00','1.00'=>'1.00','0.00'=>'0.00',
				'-1.00'=>'-1.00','-2.00'=>'-2.00','-3.00'=>'-3.00','-3.50'=>'-3.50',
				'-4.00'=>'-4.00','-5.00'=>'-5.00','-6.00'=>'-6.00','-7.00'=>'-7.00',
				'-8.00'=>'-8.00','-9.00'=>'-9.00','-10.00'=>'-10.00','-11.00'=>'-11.00','-12.00'=>'-12.00'
			);
			echo form_dropdown('local_gmt',$gmtArray,$phone_info->local_gmt);
			?>
			(Do NOT adjust for DST)
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Phone Ring Timeout:</td><td><?=form_input('phone_ring_timeout',$phone_info->phone_ring_timeout,'size="4" maxlength="5"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>On-Hook Agent:</td><td>
			<?=form_dropdown('on_hook_agent',array('Y'=>'Y','N'=>'N'),$phone_info->on_hook_agent) ?>
		</td>
    </tr>
	<!-- Space for Manager Login -->
	<!-- Space for Manager Secret -->
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Default User:</td><td><?=form_input('login_user',$phone_info->login_user,'size="20" maxlength="20"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Default Pass:</td><td><?=form_input('login_pass',$phone_info->login_pass,'size="20" maxlength="20"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Default Campaign:</td><td><?=form_input('login_campaign',$phone_info->login_campaign,'size="10" maxlength="10"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Park Exten:</td><td><?=form_input('park_on_extension',$phone_info->park_on_extension,'size="10" maxlength="10"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Conf Exten:</td><td><?=form_input('conf_on_extension',$phone_info->conf_on_extension,'size="10" maxlength="10"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Monitor Prefix:</td><td><?=form_input('monitor_prefix',$phone_info->monitor_prefix,'size="10" maxlength="10"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Recording Exten:</td><td><?=form_input('recording_exten',$phone_info->recording_exten,'size="10" maxlength="10"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Voicemail Exten:</td><td><?=form_input('voicemail_exten',$phone_info->voicemail_exten,'size="10" maxlength="10"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Voicemail Dump Exten:</td><td><?=form_input('voicemail_dump_exten',$phone_info->voicemail_dump_exten,'size="20" maxlength="20"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Exten Context:</td><td><?=form_input('ext_context',$phone_info->ext_context,'size="20" maxlength="20"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Phone Context:</td><td><?=form_input('phone_context',$phone_info->phone_context,'size="20" maxlength="20"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Call Logging:</td><td>
			<?=form_dropdown('AGI_call_logging_enabled',array('0','1'),$phone_info->AGI_call_logging_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>User Switching:</td><td>
			<?=form_dropdown('user_switching_enabled',array('0','1'),$phone_info->user_switching_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Conferencing:</td><td>
			<?=form_dropdown('conferencing_enabled',array('0','1'),$phone_info->conferencing_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Admin Hang Up:</td><td>
			<?=form_dropdown('admin_hangup_enabled',array('0','1'),$phone_info->admin_hangup_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Admin Hijack:</td><td>
			<?=form_dropdown('admin_hijack_enabled',array('0','1'),$phone_info->admin_hijack_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Admin Monitor:</td><td>
			<?=form_dropdown('admin_monitor_enabled',array('0','1'),$phone_info->admin_monitor_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Call Park:</td><td>
			<?=form_dropdown('call_parking_enabled',array('0','1'),$phone_info->call_parking_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Updater Check:</td><td>
			<?=form_dropdown('updater_check_enabled',array('0','1'),$phone_info->updater_check_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>AF Logging:</td><td>
			<?=form_dropdown('AFLogging_enabled',array('0','1'),$phone_info->AFLogging_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Queue Enabled:</td><td>
			<?=form_dropdown('QUEUE_ACTION_enabled',array('0','1'),$phone_info->QUEUE_ACTION_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>CallerID Popup:</td><td>
			<?=form_dropdown('CallerID_popup_enabled',array('0','1'),$phone_info->CallerID_popup_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Voicemail Button:</td><td>
			<?=form_dropdown('voicemail_button_enabled',array('0','1'),$phone_info->voicemail_button_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Fast Refresh:</td><td>
			<?=form_dropdown('enable_fast_refresh',array('0','1'),$phone_info->enable_fast_refresh) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Fast Refresh Rate:</td><td><?=form_input('fast_refresh_rate',$phone_info->fast_refresh_rate,'size="5"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Persistant MySQL:</td><td>
			<?=form_dropdown('enable_persistant_mysql',array('0','1'),$phone_info->enable_persistant_mysql) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Auto Dial Next Number:</td><td>
			<?=form_dropdown('auto_dial_next_number',array('0','1'),$phone_info->auto_dial_next_number) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Stop Recording After Each Call:</td><td>
			<?=form_dropdown('VDstop_rec_after_each_call',array('0','1'),$phone_info->VDstop_rec_after_each_call) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Enable SIPSAK Messages:</td><td>
			<?=form_dropdown('enable_sipsak_messages',array('0','1'),$phone_info->enable_sipsak_messages) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Template ID:</td><td>
			<?php
			$tempArray = array(''=>'--NONE--');
			foreach ($templates as $temp)
			{
				$tempArray[$temp->id] = "{$temp->id} - {$temp->name}";
			}
			
			echo form_dropdown('template_id',$tempArray,$phone_info->template_id);
			?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap>Conf Override:</td><td>
			<?php
			$options = array(
              'name'        => 'conf_override',
              'value'       => "{$phone_info->conf_override}",
              'cols'		=> '50',
              'rows'        => '10',
              'style'       => 'resize: none;',
            );
			echo form_textarea($options);
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
