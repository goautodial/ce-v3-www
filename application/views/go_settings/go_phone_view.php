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
if ($this->session->userdata('user_group') != $this->lang->line('go_admin')) {
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
		$('#advance_link').html('[ - <? echo strtoupper($this->lang->line("go_adv_settings")); ?> ]');
		$('#isAdvance').val('1');
	}
	
    $('.toolTip').tipTip();

	$('#advance_link').click(function()
	{
		if ($('.advance_settings').is(':hidden'))
		{
			$('.advance_settings').show();
			$('#advance_link').html('[ - <? echo strtoupper($this->lang->line("go_adv_settings")); ?> ]');
			$('#isAdvance').val('1');
		} else {
			$('.advance_settings').hide();
			$('#advance_link').html('[ + <? echo strtoupper($this->lang->line("go_adv_settings")); ?> ]');
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
				alert("<? echo $this->lang->line("go_success_caps"); ?>");
			
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
<div align="center" style="font-weight:bold; color:#333; font-size:16px;"><? echo $this->lang->line("go_modify_phone"); ?>: <?php echo "{$phone_info->extension}"; ?></div>
<br />
<form id="modifyPhone" method="POST">
<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:95%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
    	<td style="text-align:right;width: 40%;" nowrap><? echo $this->lang->line("go_phone_ext_login"); ?>:</td><td>&nbsp;<?php echo "{$phone_info->extension}"; ?><?=form_hidden('extension',$phone_info->extension) ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_phone_pass"); ?>:</td><td><?=form_input('pass',$phone_info->pass,'size="10" maxlength="10"') ?></td>
    </tr>
	<tr <?=$hideMe?>>
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_dial_plan_num"); ?>:</td><td><?=form_input('dialplan_number',$phone_info->dialplan_number,'size="20" maxlength="20"') ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_voicemail_id"); ?>:</td><td><?=form_input('voicemail_id',$phone_info->voicemail_id,'size="15" maxlength="10"') ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_outbound_callerid"); ?>:</td><td><?=form_input('outbound_cid',$phone_info->outbound_cid,'size="15" maxlength="20"') ?></td>
    </tr>
	<tr style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_phone_ip_address"); ?>:</td><td><?=form_input('phone_ip',$phone_info->phone_ip,'size="20" maxlength="15"') ?></td>
    </tr>
	<tr style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_comp_ip_address"); ?>:</td><td><?=form_input('computer_ip',$phone_info->computer_ip,'size="20" maxlength="15"') ?></td>
    </tr>
	<tr <?=$hideMe?>>
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_server_ip"); ?>:</td><td>
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
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_agent_screen_login"); ?>:</td><td><?=form_input('login',$phone_info->login,'size="15" maxlength="15"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_sip_reg_pass"); ?>:</td><td><?=form_input('conf_secret',$phone_info->conf_secret,'size="20" maxlength="20"') ?></td>
    </tr>
	<tr style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_set_webphone"); ?>:</td><td><?=form_hidden('is_webphone',$phone_info->is_webphone) ?></td>
    </tr>
	<tr style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_webphone_dialpad"); ?>:</td><td><?=form_hidden('webphone_dialpad',$phone_info->webphone_dialpad) ?></td>
    </tr>
	<tr style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_use_external_server_ip"); ?>:</td><td><?=form_hidden('use_external_server_ip',$phone_info->use_external_server_ip) ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_status"); ?>:</td><td>
			<?php
			$statusArray = array('ACTIVE' => strtoupper($this->lang->line("go_active")),'SUSPENDED' => strtoupper($this->lang->line("go_suspended")),'CLOSED' =>strtoupper($this->lang->line("go_closed")),'PENDING' =>strtoupper($this->lang->line("go_pending")),strtoupper($this->lang->line("go_admin"))=>strtoupper($this->lang->line("go_admin")));
			
			echo form_dropdown('status',$statusArray,$phone_info->status);
			?>
		</td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_active_account"); ?>:</td><td>
			<?=form_dropdown('active',array('Y'=>'Y','N'=>'N'),$phone_info->active) ?>
		</td>
    </tr>
	<tr style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_phone_type"); ?>:</td><td><?=form_hidden('phone_type',$phone_info->phone_type) ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_full_name"); ?>:</td><td><?=form_input('fullname',$phone_info->fullname,'size="20" maxlength="50"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_email"); ?>:</td><td><?=form_input('email',$phone_info->email,'size="50" maxlength="100"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_del_voicemail_after_email"); ?>:</td><td>
			<?=form_dropdown('delete_vm_after_email',array('Y'=>'Y','N'=>'N'),$phone_info->delete_vm_after_email) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_company"); ?>:</td><td><?=form_input('company',$phone_info->company,'size="10" maxlength="10"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_picture"); ?>:</td><td><?=form_input('picture',$phone_info->picture,'size="20" maxlength="19"') ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_new_msg"); ?>:</td><td>&nbsp;<b><?php echo $phone_info->messages; ?></b></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_old_msg"); ?>:</td><td>&nbsp;<b><?php echo $phone_info->old_messages; ?></b></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_client_protocol"); ?>:</td><td>
			<?php
			$protocolArray = array('EXTERNAL'=> strtoupper($this->lang->line("go_external")),'SIP'=>'SIP','Zap'=>'Zap','IAX2'=>'IAX2');
			echo form_dropdown('protocol',$protocolArray,$phone_info->protocol);
			?>
		</td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_local_gmt"); ?>:</td><td>
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
			( <? echo $this->lang->line("go_do_not_adjust_DST"); ?>)
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_phone_ring_timeout"); ?>:</td><td><?=form_input('phone_ring_timeout',$phone_info->phone_ring_timeout,'size="4" maxlength="5"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_onhook_agent"); ?>:</td><td>
			<?=form_dropdown('on_hook_agent',array('Y'=>'Y','N'=>'N'),$phone_info->on_hook_agent) ?>
		</td>
    </tr>
	<!-- Space for Manager Login -->
	<!-- Space for Manager Secret -->
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_default_user"); ?>:</td><td><?=form_input('login_user',$phone_info->login_user,'size="20" maxlength="20"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_default_pass"); ?>:</td><td><?=form_input('login_pass',$phone_info->login_pass,'size="20" maxlength="20"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_default_campaign"); ?>:</td><td><?=form_input('login_campaign',$phone_info->login_campaign,'size="10" maxlength="10"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_park_exten"); ?>:</td><td><?=form_input('park_on_extension',$phone_info->park_on_extension,'size="10" maxlength="10"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_conf_exten"); ?>:</td><td><?=form_input('conf_on_extension',$phone_info->conf_on_extension,'size="10" maxlength="10"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_monitor_prefix"); ?>:</td><td><?=form_input('monitor_prefix',$phone_info->monitor_prefix,'size="10" maxlength="10"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_recording_exten"); ?>:</td><td><?=form_input('recording_exten',$phone_info->recording_exten,'size="10" maxlength="10"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_voicemail_exten"); ?>:</td><td><?=form_input('voicemail_exten',$phone_info->voicemail_exten,'size="10" maxlength="10"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_voicemail_dump_exten"); ?>:</td><td><?=form_input('voicemail_dump_exten',$phone_info->voicemail_dump_exten,'size="20" maxlength="20"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_exten_context"); ?>:</td><td><?=form_input('ext_context',$phone_info->ext_context,'size="20" maxlength="20"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_phone_context"); ?>:</td><td><?=form_input('phone_context',$phone_info->phone_context,'size="20" maxlength="20"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_call_logging"); ?>:</td><td>
			<?=form_dropdown('AGI_call_logging_enabled',array('0','1'),$phone_info->AGI_call_logging_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_user_switching"); ?>:</td><td>
			<?=form_dropdown('user_switching_enabled',array('0','1'),$phone_info->user_switching_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_conferencing"); ?>:</td><td>
			<?=form_dropdown('conferencing_enabled',array('0','1'),$phone_info->conferencing_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_admin_hang_up"); ?>:</td><td>
			<?=form_dropdown('admin_hangup_enabled',array('0','1'),$phone_info->admin_hangup_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_admin_hijack"); ?>:</td><td>
			<?=form_dropdown('admin_hijack_enabled',array('0','1'),$phone_info->admin_hijack_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_admin_monitor"); ?>:</td><td>
			<?=form_dropdown('admin_monitor_enabled',array('0','1'),$phone_info->admin_monitor_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_call_park"); ?>:</td><td>
			<?=form_dropdown('call_parking_enabled',array('0','1'),$phone_info->call_parking_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_updater_check"); ?>:</td><td>
			<?=form_dropdown('updater_check_enabled',array('0','1'),$phone_info->updater_check_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_af_logging"); ?>:</td><td>
			<?=form_dropdown('AFLogging_enabled',array('0','1'),$phone_info->AFLogging_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_queue_enabled"); ?>:</td><td>
			<?=form_dropdown('QUEUE_ACTION_enabled',array('0','1'),$phone_info->QUEUE_ACTION_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_callerid_popup"); ?>:</td><td>
			<?=form_dropdown('CallerID_popup_enabled',array('0','1'),$phone_info->CallerID_popup_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_voicemail_button"); ?>:</td><td>
			<?=form_dropdown('voicemail_button_enabled',array('0','1'),$phone_info->voicemail_button_enabled) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_fast_refresh"); ?>:</td><td>
			<?=form_dropdown('enable_fast_refresh',array('0','1'),$phone_info->enable_fast_refresh) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_fast_refresh_rate"); ?>:</td><td><?=form_input('fast_refresh_rate',$phone_info->fast_refresh_rate,'size="5"') ?></td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_persistant_mysql"); ?>:</td><td>
			<?=form_dropdown('enable_persistant_mysql',array('0','1'),$phone_info->enable_persistant_mysql) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_auto_dial_next_num"); ?>:</td><td>
			<?=form_dropdown('auto_dial_next_number',array('0','1'),$phone_info->auto_dial_next_number) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_stop_recording_after_each_call"); ?>:</td><td>
			<?=form_dropdown('VDstop_rec_after_each_call',array('0','1'),$phone_info->VDstop_rec_after_each_call) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_enable_sipsak_msg"); ?>:</td><td>
			<?=form_dropdown('enable_sipsak_messages',array('0','1'),$phone_info->enable_sipsak_messages) ?>
		</td>
    </tr>
	<tr class="advance_settings" style="display:none;">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_template_id"); ?>:</td><td>
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
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_conf_override"); ?>:</td><td>
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
    	<td><span id="advance_link" style="cursor:pointer;font-size:9px;">[ + <? echo strtoupper($this->lang->line("go_adv_settings")); ?> ]</span><input type="hidden" id="isAdvance" value="0" /></td><td style="text-align:right;"><span id="saveSettings" class="buttons"> <? echo strtoupper($this->lang->line("go_save_settings")); ?></span><!--<input id="saveSettings" type="submit" value=" SAVE SETTINGS " style="cursor:pointer;" />--></td>
    </tr>
</table>
</form>
<?php
		break;
}
?>
<br style="font-size:9px;" />
