<?php
####################################################################################################
####  Name:             	go_voicemail_view.php                                               ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Originated by:        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Christopher Lomuntad                                         	    ####
####  License:          	AGPLv2                                                              ####
####################################################################################################
$base = base_url();
?>
<script>
$(function()
{
    $('.toolTip').tipTip();
	
	$('#fullname').keydown(function(event)
	{
		$(this).css('border','solid 1px #999');
	});
	
	// Submit Settings
	$('#saveSettings').click(function()
	{
		var isEmpty = 0;
		if ($('#fullname').val() === "")
		{
			$('#fullname').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#aloading').html().match(/Not Available/))
		{
			alert("<? $this->lang->line("go_vm_not_available"); ?>");
			isEmpty = 1;
		}
		
		if (!isEmpty)
		{
			var items = $('#modifyVoicemail').serialize();
			$.post("<?=$base?>index.php/go_voicemail_ce/go_voicemail_wizard", { items: items, action: "modify_voicemail" },
			function(data){
				if (data=="SUCCESS")
				{
					alert("<? echo $this->lang->line("go_success_caps"); ?>");
					
					$('#box').animate({'top':'-2550px'},500);
					$('#overlay').fadeOut('slow');
					
					location.reload();
				}
	
			});
		}
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
<div align="center" style="font-weight:bold; color:#333; font-size:16px;"><? echo strtoupper($this->lang->line("go_modify_voicemail_box")); ?>: <?php echo "{$vmail_info->voicemail_id}"; ?></div>
<br />
<form id="modifyVoicemail" method="POST">
<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:98%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
		<td style="text-align:right;width:40%;height:10px;">
                <? echo $this->lang->line("go_voicemail_id"); ?>:
                </td>
                <td>
                &nbsp;<span><?=$vmail_info->voicemail_id ?></span>
                <?=form_hidden('voicemail_id',$vmail_info->voicemail_id,'id="voicemail_id"') ?>
                <span id="aloading"></span>
                </td>
        </tr>
        <tr>
                <td style="text-align:right;width:40%;height:10px;">
                <? echo $this->lang->line("go_pass"); ?>:
                </td>
                <td>
                <?=form_input('pass',$vmail_info->pass,'id="pass" maxlength="10" size="15"') ?>
                </td>
        </tr>
        <tr>
                <td style="text-align:right;width:40%;height:10px;">
                <? echo $this->lang->line("go_name"); ?>:
                </td>
                <td>
                <?=form_input('fullname',$vmail_info->fullname,'id="fullname" maxlength="100" size="40"') ?>
                </td>
        </tr>
        <tr>
                <td style="text-align:right;width:40%;height:10px;">
                <? echo $this->lang->line("go_email"); ?>:
		</td>
		<td>
		<?=form_input('email',$vmail_info->email,'id="email" maxlength="100" size="30"') ?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:40%;height:10px;">
                <? echo $this->lang->line("go_active"); ?>:
                </td>
                <td>
                <?=form_dropdown('active',array('Y'=>'YES','N'=>'NO'),$vmail_info->active,'id="active"') ?>
                </td>
        </tr>
        <tr>
                <td style="text-align:right;width:40%;height:10px;white-space:nowrap;">
                <? echo $this->lang->line("go_del_voicemail_after_email"); ?>:
                </td>
                <td>
                <?=form_dropdown('delete_vm_after_email',array('Y'=>'YES','N'=>'NO'),$vmail_info->delete_vm_after_email,'id="delete_vm_after_email"') ?>
                </td>
        </tr>
        <tr>
                <td style="text-align:right;width:40%;height:10px;white-space:nowrap;">
                <? echo $this->lang->line("go_new_msg"); ?>:
                </td>
                <td style="font-weight:bold;">
                &nbsp;<?=$vmail_info->messages ?>
                </td>
        </tr>
        <tr>
                <td style="text-align:right;width:40%;height:10px;white-space:nowrap;">
                <? echo $this->lang->line("go_old_msg"); ?>:
		</td>
		<td style="font-weight:bold;">
		&nbsp;<?=$vmail_info->old_messages ?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td><td>&nbsp;</td>
	</tr>
	<tr>
    	<td></td><td style="text-align:right;"><span id="saveSettings" class="buttons"><? echo strtoupper($this->lang->line("go_save_settings")); ?></span><!--<input id="saveSettings" type="submit" value=" SAVE SETTINGS " style="cursor:pointer;" />--></td>
    </tr>
</table>
</form>
<?php
		break;
}
?>
<br style="font-size:9px;" />
