<?php
############################################################################################
####  Name:             go_campaign_statuses.php                                        ####
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
$(function()
{
	$(".toolTip").tipTip();
	
	$('#selectAllCampStatus').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delCampStatus[]"]').each(function()
			{
				$(this).attr('checked',true);
			});
		}
		else
		{
			$('input:checkbox[id="delCampStatus[]"]').each(function()
			{
				$(this).removeAttr('checked');
			});
		}
	});
	
	$('#selectAllCampRecycling').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delCampLeadRecycling[]"]').each(function()
			{
				$(this).attr('checked',true);
			});
		}
		else
		{
			$('input:checkbox[id="delCampLeadRecycling[]"]').each(function()
			{
				$(this).removeAttr('checked');
			});
		}
	});
	
	$('#selectAllCampPauseCodes').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delCampPauseCodes[]"]').each(function()
			{
				$(this).attr('checked',true);
			});
		}
		else
		{
			$('input:checkbox[id="delCampPauseCodes[]"]').each(function()
			{
				$(this).removeAttr('checked');
			});
		}
	});
	
	$('#selectAllCampHotKeys').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delCampHotKeys[]"]').each(function()
			{
				$(this).attr('checked',true);
			});
		}
		else
		{
			$('input:checkbox[id="delCampHotKeys[]"]').each(function()
			{
				$(this).removeAttr('checked');
			});
		}
	});
	
	$('#addNewCampStatus').click(function()
	{
		var campaign_id = '<?php echo "$campaign_id"; ?>';
		var err_msg = ' <? echo lang('go_Pleasefillinthefollowing'); ?><br/><br/>';
		var err = 0;
		
		if ($('#status').val() == '')
		{
			err_msg += '<? echo lang('go_STATUS'); ?><br />';
			err++;
		}
			
		if ($('#status_name').val() == '')
		{
			err_msg += '<? echo lang('go_STATUSNAME'); ?><br />';
			err++;
		}
		
		if (err > 0)
		{
			alert(err_msg);
		}
		else
		{
			var str = $('.addStatus').serialize();
			$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_get_campaign_statuses/'+campaign_id+'/add_status/'+str).fadeIn("slow");
		}
	});
	
	$('#saveModifiedStatus').click(function()
	{
		var campaign_id = '<?php echo "$campaign_id"; ?>';
		var err_msg = '<? echo lang('go_Pleasefillinthefollowing'); ?><br /><br />';
		var err = 0;

		if ($('#status_name_mod').val() == '')
		{
			err_msg += '<? echo lang('go_STATUSNAME'); ?><br />';
			err++;
		}		

		if (err > 0)
		{
			alert(err_msg);
		}
		else
		{
			$('#statusBox').animate({'top':'-2550px'},500);
			$('#statusOverlay').fadeOut('slow');
		
			var str = $('.addStatusMod').serialize();
			$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_get_campaign_statuses/'+campaign_id+'/save_status/'+str).fadeIn("slow");
		}
	});
	
	$('#status, #status_name, #status_name_mod').bind("keydown keypress", function(event)
	{
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.type == "keydown") {
			if ((event.keyCode == 32 && ($(this).attr('id') != "status_name" && $(this).attr('id') != "status_name_mod")) || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
				|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
				|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
				return false;
			
			if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
				return false;
			
			if (!event.shiftKey && event.keyCode == 173)
				return false;
		} else {
			// For ASCII Key Codes
			if ((event.which > 32 && event.which < 48) || (event.which == 32 && ($(this).attr('id') != "status_name" && $(this).attr('id') != "status_name_mod")) || (event.which > 57 && event.which < 65)
				|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
				return false;
		}
	});
	
	var toggleCampStatus = $('#go_camp_status_menu').css('display');
	$('#selectCampStatusAction').click(function()
	{
		if (toggleCampStatus == 'none')
		{
			var position = $(this).offset();
			$('#go_camp_status_menu').css('left',position.left-42);
			$('#go_camp_status_menu').css('top',position.top+16);
			$('#go_camp_status_menu').slideDown('fast');
			toggleCampStatus = $('#go_camp_status_menu').css('display');
		}
		else
		{
			$('#go_camp_status_menu').slideUp('fast');
			$('#go_camp_status_menu').hide();
			toggleCampStatus = $('#go_camp_status_menu').css('display');
		}
	});
	
	var toggleCampRecycle = $('#go_camp_lead_recycle_menu').css('display');
	$('#selectCampRecycleAction').click(function()
	{
		if (toggleCampRecycle == 'none')
		{
			var position = $(this).offset();
			$('#go_camp_lead_recycle_menu').css('left',position.left-42);
			$('#go_camp_lead_recycle_menu').css('top',position.top+16);
			$('#go_camp_lead_recycle_menu').slideDown('fast');
			toggleCampRecycle = $('#go_camp_lead_recycle_menu').css('display');
		}
		else
		{
			$('#go_camp_lead_recycle_menu').slideUp('fast');
			$('#go_camp_lead_recycle_menu').hide();
			toggleCampRecycle = $('#go_camp_lead_recycle_menu').css('display');
		}
	});
	
	var toggleCampPauseCodes = $('#go_camp_pausecodes_menu').css('display');
	$('#selectCampPauseCodeAction').click(function()
	{
		if (toggleCampPauseCodes == 'none')
		{
			var position = $(this).offset();
			$('#go_camp_pausecodes_menu').css('left',position.left-42);
			$('#go_camp_pausecodes_menu').css('top',position.top+16);
			$('#go_camp_pausecodes_menu').slideDown('fast');
			toggleCampPauseCodes = $('#go_camp_pausecodes_menu').css('display');
		}
		else
		{
			$('#go_camp_pausecodes_menu').slideUp('fast');
			$('#go_camp_pausecodes_menu').hide();
			toggleCampPauseCodes = $('#go_camp_pausecodes_menu').css('display');
		}
	});
	
	var toggleCampHotKeys = $('#go_camp_hotkeys_menu').css('display');
	$('#selectCampHotKeysAction').click(function()
	{
		if (toggleCampHotKeys == 'none')
		{
			var position = $(this).offset();
			$('#go_camp_hotkeys_menu').css('left',position.left-42);
			$('#go_camp_hotkeys_menu').css('top',position.top+16);
			$('#go_camp_hotkeys_menu').slideDown('fast');
			toggleCampHotKeys = $('#go_camp_hotkeys_menu').css('display');
		}
		else
		{
			$('#go_camp_hotkeys_menu').slideUp('fast');
			$('#go_camp_hotkeys_menu').hide();
			toggleCampHotKeys = $('#go_camp_hotkeys_menu').css('display');
		}
	});
	
	var update = <?php echo ($update>0) ? $update : 0; ?>;
	if (update>0)
	{
		switch (update)
		{
			case 1:
				var err_msg = '<? echo lang('go_Thereisalreadyaglobalstatusinthesystemwiththisname_'); ?><br /><br /><?php echo $status; ?>';
				break;
			case 2:
				var err_msg = '<? echo lang('go_Thereisalreadyacampaignstatusinthesystemwiththisname_'); ?><br /><br /><?php echo $status; ?>';
				break;
		}
		
		alert(err_msg);
	}
});

function modifyCampStatus(status,camp)
{
	$('#statusOverlay').fadeIn('fast');
	$('#statusBox').css({'width': '460px', 'height': '350px', 'margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
	$('#statusBox').animate({
		top: "70px",
		left: "14%",
		right: "14%"
	}, 500);
	$('#statusClosebox').attr('rel',camp)
	
	$("#statusOverlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#statusOverlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_get_campaign_statuses/'+camp+'/modify_status/'+status).fadeIn("slow");
}

function delCampStatus(status,camp)
{
	var what = confirm("Are you sure you want to delete this Status?\n\n"+status);
	if (what)
	{
		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_campaign_ce/go_get_campaign_statuses/'+camp+'/delete_status/'+status).fadeIn("slow");
	}
}
</script>
<style type="text/css">
.campaign_statuses td {
	text-align:center;
	padding:0px 2px 0px 2px;
	color:#555;
}

input, select {
	font-size:10px;
}

.buttons {
	color:#7A9E22;
	cursor:pointer;
	font-size:10px;
}

.buttons:hover{
	font-weight:bold;
}

.hiddenRecyclingTable{
	display: none;
}
</style>
<?php
if ($action!='modify_status' && $action!='modify_recycle' && $action!='modify_pausecode' && $action!='modify_hotkeys')
{
?>
<input type="hidden" value="<?php echo $campaign_id; ?>" id="campaign_id_mod" />
<div align="center" style="font-weight:bold; color:#333; font-size:16px;"><?php echo lang('go_CUSTOMSTATUSESWITHINTHISCAMPAIGN'); ?> <?php echo "$campaign_id"; ?></div>
<br />
<table id="statusesTable" border="0" cellpadding="1" cellspacing="1" style="width:100%;color:#555;">
	<tr>
    	<td>&nbsp;</td>
    	<td>&nbsp;</td>
    	<td style="display:none;">&nbsp;</td>
    	<td style="width:5%;background-color:#eee;" rowspan="2" align="center" valign="bottom"><table class="campaign_statuses"><tr><td>S</td></tr><tr><td>E</td></tr><tr><td>L</td></tr><tr><td>E</td></tr><tr><td>C</td></tr><tr><td>T</td></tr><tr><td>A</td></tr><tr><td>B</td></tr><tr><td>L</td></tr><tr><td>E</td></tr></table></td>
    	<td style="width:5%;background-color:#eee;" rowspan="2" align="center" valign="bottom"><table class="campaign_statuses"><tr><td>&nbsp;</td><td>A</td></tr><tr><td>&nbsp;</td><td>N</td></tr><tr><td>&nbsp;</td><td>S</td></tr><tr><td>H</td><td>W</td></tr><tr><td>U</td><td>E</td></tr><tr><td>M</td><td>R</td></tr><tr><td>A</td><td>E</td></tr><tr><td>N</td><td>D</td></tr></table></td>
    	<td style="width:5%;background-color:#eee;" rowspan="2" align="center" valign="bottom"><table class="campaign_statuses"><tr><td>S</td></tr><tr><td>A</td></tr><tr><td>L</td></tr><tr><td>E</td></tr></table></td>
    	<td style="width:5%;background-color:#eee;" rowspan="2" align="center" valign="bottom"><table class="campaign_statuses"><tr><td>D</td></tr><tr><td>N</td></tr><tr><td>C</td></tr></table></td>
    	<td style="width:5%;background-color:#eee;" rowspan="2" align="center" valign="bottom"><table class="campaign_statuses"><tr><td>C</td><td>&nbsp;</td></tr><tr><td>U</td><td>C</td></tr><tr><td>S</td><td>O</td></tr><tr><td>T</td><td>N</td></tr><tr><td>O</td><td>T</td></tr><tr><td>M</td><td>A</td></tr><tr><td>E</td><td>C</td></tr><tr><td>R</td><td>T</td></tr></table></td>
    	<td style="width:5%;background-color:#eee;" rowspan="2" align="center" valign="bottom"><table class="campaign_statuses"><tr><td>&nbsp;</td><td>I</td></tr><tr><tr><td>&nbsp;</td><td>N</td></tr><tr><td>&nbsp;</td><td>T</td></tr><tr><td>&nbsp;</td><td>E</td></tr><tr><td>&nbsp;</td><td>R</td></tr><tr><td>&nbsp;</td><td>E</td></tr><tr><td>&nbsp;</td><td>S</td></tr><tr><td>N</td><td>T</td></tr><tr><td>O</td><td>E</td></tr><tr><td>T</td><td>D</td></tr></table></td>
    	<td style="width:5%;background-color:#eee;" rowspan="2" align="center" valign="bottom"><table class="campaign_statuses"><tr><td>U</td></tr><tr><td>N</td></tr><tr><td>W</td></tr><tr><td>O</td></tr><tr><td>R</td></tr><tr><td>K</td></tr><tr><td>A</td></tr><tr><td>B</td></tr><tr><td>L</td></tr><tr><td>E</td></tr></table></td>
    	<td style="width:5%;background-color:#eee;" rowspan="2" align="center" valign="bottom"><table class="campaign_statuses"><tr><td>S</td><td>&nbsp;</td></tr><tr><td>C</td><td>C</td></tr><tr><td>H</td><td>A</td></tr><tr><td>E</td><td>L</td></tr><tr><td>D</td><td>L</td></tr><tr><td>U</td><td>B</td></tr><tr><td>L</td><td>A</td></tr><tr><td>E</td><td>C</td></tr><tr><td>D</td><td>K</td></tr></table></td>
     	<td colspan="2">&nbsp;</td>
   		<td>&nbsp;</td>
    </tr>
	<tr style="font-weight:bold;">
    	<td style="width:20%" valign="bottom">&nbsp;&nbsp;<? echo lang('go_STATUS'); ?></td>
        <td valign="bottom">&nbsp;&nbsp;<? echo lang('go_STATUSNAME'); ?></td>
        <td style="display:none;" valign="bottom">&nbsp;&nbsp;<? echo lang('go_CATEGORY'); ?></td>
        <td style="width:5%" align="center" valign="bottom" colspan="2" nowrap><span style="cursor:pointer;" id="selectCampStatusAction">&nbsp;<? echo lang('go_ACTION'); ?> &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></td>
        <td style="width:2%" align="center" valign="bottom"><input type="checkbox" id="selectAllCampStatus" /></td>
    </tr>
    
    <?php
    foreach ($status_list as $status)
	{	
		if ($status->campaign_id==$campaign_id)
		{
			if ($x==0) {
				$bgcolor = "#E0F8E0";
				$x=1;
			} else {
				$bgcolor = "#EFFBEF";
				$x=0;
			}
			
			echo "<tr style=\"background-color:$bgcolor;\">\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;width:5%;\">&nbsp;&nbsp;".$status->status."</td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;width:30%;\">&nbsp;&nbsp;".$status->status_name."</td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;display:none;\">&nbsp;&nbsp;".$status->category."</td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span style=\"color:".(($status->selectable=='Y') ? 'green">YES' : 'red">NO')."</span></td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span style=\"color:".(($status->human_answered=='Y') ? 'green">YES' : 'red">NO')."</td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span style=\"color:".(($status->sale=='Y') ? 'green">YES' : 'red">NO')."</td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span style=\"color:".(($status->dnc=='Y') ? 'green">YES' : 'red">NO')."</td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span style=\"color:".(($status->customer_contact=='Y') ? 'green">YES' : 'red">NO')."</td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span style=\"color:".(($status->not_interested=='Y') ? 'green">YES' : 'red">NO')."</td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span style=\"color:".(($status->unworkable=='Y') ? 'green">YES' : 'red">NO')."</td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span style=\"color:".(($status->scheduled_callback=='Y') ? 'green">YES' : 'red">NO')."</td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modifyCampStatus('".$status->status."','$campaign_id')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"".lang('go_MODIFYCAMPAIGNSTATUS')."<br />".$status->status."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delCampStatus('".$status->status."','$campaign_id')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"".lang('go_DELETECAMPAIGNSTATUS')."<br />".$status->status."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td>\n";
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delCampStatus[]\" value=\"".$status->status."\" /></td>\n";
			echo "</tr>\n";
		}
	}
	
	echo "<tr>\n";
	echo "<td style=\"border-top:#D0D0D0 dashed 1px;width:5%;\">&nbsp;<input type=\"text\" id=\"status\" name=\"status\" maxlength=\"6\" size=\"10\" class=\"addStatus\" placeholder=\"".lang('go_Status_')."\" /></td>\n";
	echo "<td style=\"border-top:#D0D0D0 dashed 1px;width:30%;\">&nbsp;<input type=\"text\" id=\"status_name\" name=\"status_name\" maxlength=\"25\" size=\"25\" class=\"addStatus\" placeholder=\"".lang('go_StatusName_')."\" /></td>\n";
	echo "<td style=\"border-top:#D0D0D0 dashed 1px;display:none;width:30%;\">&nbsp;<select id=\"category\" name=\"category\" class=\"addStatus\"><option value=\"UNDEFINED\">UNDEFINED</option>$categories</select></td>\n";
	echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><select id=\"selectable\" name=\"selectable\" class=\"addStatus\"><option value=\"Y\">YES</option><option value=\"N\">NO</option></select></span></td>\n";
	echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><select id=\"human_answered\" name=\"human_answered\" class=\"addStatus\"><option value=\"Y\">YES</option><option value=\"N\" selected>NO</option></select></td>\n";
	echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><select id=\"sale\" name=\"sale\" class=\"addStatus\"><option value=\"Y\">YES</option><option value=\"N\" selected>NO</option></select></td>\n";
	echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><select id=\"dnc\" name=\"dnc\" class=\"addStatus\"><option value=\"Y\">YES</option><option value=\"N\" selected>NO</option></select></td>\n";
	echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><select id=\"customer_contact\" name=\"customer_contact\" class=\"addStatus\"><option value=\"Y\">YES</option><option value=\"N\" selected>NO</option></select></td>\n";
	echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><select id=\"not_interested\" name=\"not_interested\" class=\"addStatus\"><option value=\"Y\">YES</option><option value=\"N\" selected>NO</option></select></td>\n";
	echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><select id=\"unworkable\" name=\"unworkable\" class=\"addStatus\"><option value=\"Y\">YES</option><option value=\"N\" selected>NO</option></select></td>\n";
	echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><select id=\"scheduled_callback\" name=\"scheduled_callback\" class=\"addStatus\"><option value=\"Y\">YES</option><option value=\"N\" selected>NO</option></select></td>\n";
//	echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$status->category."</td>\n";
// 	echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\" colspan=\"3\"><input type=\"button\" id=\"addNewCampStatus\" value=\"ADD STATUS\" style=\"cursor:pointer;\" /></td>\n";
	echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\" colspan=\"3\"><span id=\"addNewCampStatus\" class=\"buttons\">".lang('go_ADDSTATUS')."</span></td>\n";
	echo "</tr>\n";
	?>
</table>
<?php
}

if ($action=='modify_status')
{
?>
<script>
$(function()
{
	$('#selectable_mod option').each(function()
	{
		if ($(this).val() == '<?php echo $status_view->selectable; ?>')
		{
			$('#selectable_mod').val('<?php echo $status_view->selectable; ?>');
		}
	});
	
	$('#human_answered_mod option').each(function()
	{
		if ($(this).val() == '<?php echo $status_view->human_answered; ?>')
			$('#human_answered_mod').val('<?php echo $status_view->human_answered; ?>');
	});
	
	$('#sale_mod option').each(function()
	{
		if ($(this).val() == '<?php echo $status_view->sale; ?>')
			$('#sale_mod').val('<?php echo $status_view->sale; ?>');
	});
	
	$('#dnc_mod option').each(function()
	{
		if ($(this).val() == '<?php echo $status_view->dnc; ?>')
			$('#dnc_mod').val('<?php echo $status_view->dnc; ?>');
	});
	
	$('#customer_contact_mod option').each(function()
	{
		if ($(this).val() == '<?php echo $status_view->customer_contact; ?>')
			$('#customer_contact_mod').val('<?php echo $status_view->customer_contact; ?>');
	});
	
	$('#not_interested_mod option').each(function()
	{
		if ($(this).val() == '<?php echo $status_view->not_interested; ?>')
			$('#not_interested_mod').val('<?php echo $status_view->not_interested; ?>');
	});
	
	$('#unworkable_mod option').each(function()
	{
		if ($(this).val() == '<?php echo $status_view->unworkable; ?>')
			$('#unworkable_mod').val('<?php echo $status_view->unworkable; ?>');
	});
	
	$('#scheduled_callback_mod option').each(function()
	{
		if ($(this).val() == '<?php echo $status_view->scheduled_callback; ?>')
			$('#scheduled_callback_mod').val('<?php echo $status_view->scheduled_callback; ?>');
	});
});
</script>
<div align="center" style="font-weight:bold; color:#333; font-size:16px;"><? echo lang('go_MODIFYSTATUS_'); ?> <?php echo $status_view->status; ?></div>
<br />
<table border="0" cellpadding="1" cellspacing="1" style="width:100%;color:#555;">
	<tr>
    	<td style="text-align:right;"><? echo lang('go_STATUS'); ?>:&nbsp;</td>
        <td><input type="text" id="status_mod" name="status_mod" maxlength="6" size="10" class="addStatusMod" value="<?php echo $status_view->status; ?>" readonly="readonly" /></td>
    </tr>
	<tr>
    	<td style="text-align:right;"><? echo lang('go_STATUSNAME'); ?>:&nbsp;</td>
        <td><input type="text" id="status_name_mod" name="status_name_mod" maxlength="25" size="25" class="addStatusMod" value="<?php echo $status_view->status_name; ?>" /></td>
    </tr>
	<tr>
    	<td style="text-align:right;"><? echo lang('go_SELECTABLE_'); ?>:&nbsp;</td>
        <td><select id="selectable_mod" name="selectable_mod" class="addStatusMod"><option value="Y"><? echo lang('go_YES');?></option><option value="N"><? echo lang('go_NO');?></option></select></td>
   </tr>
	<tr>
    	<td style="text-align:right;"><? echo lang('go_HUMANANSWERED_'); ?>:&nbsp;</td>
        <td><select id="human_answered_mod" name="human_answered_mod" class="addStatusMod"><option value="Y"><? echo lang('go_YES');?></option><option value="N"><? echo lang('go_NO');?></option></select></td>
    </tr>
	<tr style="display:none;">
    	<td style="text-align:right;"><? echo lang('go_CATEGORY'); ?>:&nbsp;</td>
        <td><select id="category_mod" name="category_mod" class="addStatusMod"><option value="UNDEFINED"><? echo lang('go_UNDEFINED'); ?></option><?php $categories; ?></select></td>
    </tr>
	<tr>
    	<td style="text-align:right;"><? echo lang('go_SALE'); ?>:&nbsp;</td>
        <td><select id="sale_mod" name="sale_mod" class="addStatusMod"><option value="Y"><? echo lang('go_YES');?></option><option value="N"><? echo lang('go_NO');?></option></select></td>
    </tr>
	<tr>
    	<td style="text-align:right;"><? echo lang('go_DNCfile'); ?>:&nbsp;</td>
        <td><select id="dnc_mod" name="dnc_mod" class="addStatusMod"><option value="Y"><? echo lang('go_YES');?></option><option value="N"><? echo lang('go_NO');?></option></select></td>
    </tr>
	<tr>
    	<td style="text-align:right;"><? echo lang('go_CUSTOMERCONTACT'); ?>:&nbsp;</td>
        <td><select id="customer_contact_mod" name="customer_contact_mod" class="addStatusMod"><option value="Y"><? echo lang('go_YES');?></option><option value="N"><? echo lang('go_NO');?></option></select></td>
    </tr>
	<tr>
    	<td style="text-align:right;"><? echo lang('go_NOTINTERESTED'); ?>:&nbsp;</td>
        <td><select id="not_interested_mod" name="not_interested_mod" class="addStatusMod"><option value="Y"><? echo lang('go_YES');?></option><option value="N"><? echo lang('go_NO');?></option></select></td>
    </tr>
	<tr>
    	<td style="text-align:right;"><? echo lang('go_UNWORKABLE'); ?>:&nbsp;</td>
        <td><select id="unworkable_mod" name="unworkable_mod" class="addStatusMod"><option value="Y"><? echo lang('go_YES');?></option><option value="N"><? echo lang('go_NO');?></option></select></td>
    </tr>
	<tr>
    	<td style="text-align:right;"><? echo lang('go_SCHEDULEDCALLBACK'); ?>:&nbsp;</td>
        <td><select id="scheduled_callback_mod" name="scheduled_callback_mod" class="addStatusMod"><option value="Y"><? echo lang('go_YES');?></option><option value="N"><? echo lang('go_NO');?></option></select></td>
    </tr>
	<tr>
    	<td style="text-align:right;">&nbsp;</td>
        <td align="right"><span id="saveModifiedStatus" class="buttons" style="font-size:12px;"><? echo lang('go_SAVE');?></span><!--<input type="button" id="saveModifiedStatus" value="SAVE" style="font-size:12px;cursor:pointer;" />--></td>
    </tr>
</table>
<?php
}

if ($action=='modify_recycle')
{
?>
<script>
$(function()
{
	$('#saveModifiedLeadRecycling').click(function(e)
	{
		var camp = $('#campaign_id_mod').val();
		var string = "status="+$('#leadStatus').val()+"&attempt_delay="+$('#attemptDelay').val()+"&attempt_maximum="+$('#attemptMaximum').val()+"&active="+$('#isActive').val();

		$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/save/"+camp+"/"+string, function(data)
		{			
			alert('SETTINGS SAVED.');
			$('.hiddenRecyclingTable').slideUp(1000, function()
			{
				$('#spanLeadStatus').html('');
				$('#leadStatus').val('');
				$('#attemptDelay').val('');
				$('#attemptMaximum').val('');
				$('#isActive').val('');
				$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
				$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/modify/'+camp).fadeIn("slow");
			});
		});
	});
	
	$('#attemptDelay').keyup(function(e)
	{
		if (parseInt($(this).val()) < 2)
		{
			$(this).css('border','solid 1px red');
		} else {
			$(this).css('border','solid 1px #dfdfdf');
		}
		
		if (parseInt($(this).val()) > 720)
			$(this).val('720');
	});
});
</script>
<input type="hidden" value="<?php echo $campaign_id; ?>" id="campaign_id_mod" />
<div align="center" style="font-weight:bold; color:#333; font-size:16px;"><? echo lang('go_LEADRECYCLINGWITHINTHISCAMPAIGN'); ?>: <?php echo "$campaign_id"; ?></div>
<br />
<table id="leadRecyclingTable" border="0" cellpadding="1" cellspacing="1" style="width:100%;color:#555;">
	<tr style="font-weight:bold;">
    	<td style="width:20%" valign="bottom">&nbsp;&nbsp;<? echo lang('go_STATUS'); ?></td>
        <td style="white-space: nowrap;">&nbsp;&nbsp;<? echo lang('go_ATTEMPTDELAY'); ?></td>
        <td style="white-space: nowrap;">&nbsp;&nbsp;<? echo lang('go_MAXIMUMATTEMPTS'); ?></td>
        <td style="white-space: nowrap;">&nbsp;&nbsp;<? echo lang('go_LEADSATLIMIT'); ?></td>
        <td style="white-space: nowrap;">&nbsp;&nbsp;<? echo lang('go_ACTIVE') ?></td>
        <td style="width:5%" align="center" valign="bottom" colspan="3" nowrap><span style="cursor:pointer;" id="selectCampRecycleAction">&nbsp;<? echo  lang('go_ACTION'); ?> &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></td>
        <td style="width:2%" align="center" valign="bottom"><input type="checkbox" id="selectAllCampRecycling" /></td>
    </tr>
    
    <?php
    foreach ($recycle_list as $idx => $list)
	{
		if ($x==0) {
			$bgcolor = "#E0F8E0";
			$x=1;
		} else {
			$bgcolor = "#EFFBEF";
			$x=0;
		}
		
		echo "<tr style=\"background-color:$bgcolor;\">\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$list['status']."</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".number_format($list['attempt_delay'] / 60)." mins</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$list['attempt_maximum']."</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\">&nbsp;&nbsp;".$list['leads_limit']."</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span style=\"color:".(($list['active']=='Y') ? 'green">YES' : 'red">NO')."</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modifyLeadRecycling('".$list['status']."','$campaign_id')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"".lang('go_MODIFYCAMPAIGN')."<br>". lang('go_LEADRECYCLESTATUS')." <br>".$list['status']."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delLeadRecycling('".$list['status']."','$campaign_id')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"".lang('go_DELETECAMPAIGN')."<br>".lang('go_LEADRECYCLESTATUS')."<br>".$list['status']."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><img src=\"{$base}img/status_display_i_grayed.png\" style=\"cursor:default;width:12px;\" /></span></td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delCampLeadRecycling[]\" value=\"".$list['status']."\" /></td>\n";
		echo "</tr>\n";
	}
	?>
</table>
<div class="hiddenRecyclingTable" style="display: none;width: 100%;">
<br />
<table style="margin-left:auto;margin-right:auto;">
	<tr>
		<td style="font-weight: bold;"><? echo lang('go_Status_'); ?>&nbsp;&nbsp;</td>
		<td>&nbsp;<span id="spanLeadStatus"></span><input type="hidden" id="leadStatus" value="" /></td>
	</tr>
	<tr>
		<td style="font-weight: bold;"><? echo lang('go_AttemptDelay'); ?>:&nbsp;&nbsp;</td>
		<td><input type="text" id="attemptDelay" value="" size="6" maxlength="3" /></td>
		<td style="white-space: nowrap;"><font size="1" color="red"><? echo lang('go_Shouldbefrom2to720mins12hrs'); ?>.</font></td>
	</tr>
	<tr>
		<td style="font-weight: bold;"><? echo lang('go_MaximumAttempts'); ?>:&nbsp;&nbsp;</td>
		<td>
			<select id="attemptMaximum">
		<?php
		for ($i=1;$i<=10;$i++)
		{
			echo "<option value=\"$i\">$i</option>";
		}
		?>
			</select>
		</td>
	</tr>
	<tr>
		<td style="font-weight: bold;"><? echo lang('go_Active_'); ?>&nbsp;&nbsp;</td>
		<td><select id="isActive"><option value="Y"><? echo lang('go_YES'); ?> </option><option value="N"><? echo lang('go_NO'); ?></option></select></td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: right;"><a id="saveModifiedLeadRecycling" class="buttons" style="cursor: pointer"><? echo lang('go_SAVE'); ?></a></td>
	</tr>
</table>
</div>
<br />
<?php
}

if ($action=='modify_pausecode')
{
?>
<script>
$(function()
{
	$('#saveModifiedPauseCodes').click(function(e)
	{
		var camp = $('#campaign_id_mod').val();
		var string = "pause_code="+$('#pauseCodeID').val()+"&pause_code_name="+$('#pauseCodeName').val()+"&billable="+$('#isBillable').val();

		$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_pause_codes/save/"+camp+"/"+string, function(data)
		{			
			alert('SETTINGS SAVED.');
			$('.hiddenPauseCodesTable').slideUp(1000, function()
			{
				$('#spanPauseCodes').html('');
				$('#pauseCode').val('');
				$('#pauseCodeName').val('');
				$('#isBillable').val('');
				$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
				$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_pause_codes/modify/'+camp).fadeIn("slow");
			});
		});
	});
	
	$('#pause_code').keyup(function(e)
	{
		if (parseInt($(this).val()) < 2)
		{
			$(this).css('border','solid 1px red');
		} else {
			$(this).css('border','solid 1px #dfdfdf');
		}
	});
});
</script>
<input type="hidden" value="<?php echo $campaign_id; ?>" id="campaign_id_mod" />
<div align="center" style="font-weight:bold; color:#333; font-size:16px;"><?  echo lang('go_PAUSECODESWITHINTHISCAMPAIGN'); ?>: <?php echo "$campaign_id"; ?></div>
<br />
<table id="pauseCodesTable" border="0" cellpadding="1" cellspacing="1" style="width:100%;color:#555;">
	<tr style="font-weight:bold;">
    	<td style="width:20%" valign="bottom">&nbsp;&nbsp;<? echo lang('go_PAUSECODE'); ?></td>
        <td style="white-space: nowrap;">&nbsp;&nbsp;<? echo lang('go_PAUSECODENAME'); ?></td>
        <td style="white-space: nowrap;">&nbsp;&nbsp;<? echo lang('go_BILLABLE'); ?></td>
        <td style="width:5%" align="center" valign="bottom" colspan="3" nowrap><span style="cursor:pointer;" id="selectCampPauseCodeAction">&nbsp;<? echo lang('go_ACTION'); ?> &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></td>
        <td style="width:2%" align="center" valign="bottom"><input type="checkbox" id="selectAllCampPauseCodes" /></td>
    </tr>
    
    <?php
    foreach ($pause_list as $idx => $list)
	{
		if ($x==0) {
			$bgcolor = "#E0F8E0";
			$x=1;
		} else {
			$bgcolor = "#EFFBEF";
			$x=0;
		}
		
		echo "<tr style=\"background-color:$bgcolor;\">\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$list['pause_code']."</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".str_replace("+"," ",$list['pause_code_name'])."</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span style=\"color:".(($list['billable']=='HALF' ? 'red">HALF' : ($list['billable']=='YES' ? 'green">YES' : 'red">NO')))."</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modifyCampPauseCodes('".$list['pause_code']."','$campaign_id')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"".lang('go_MODIFYCAMPAIGN')." <br>".lang('go_PAUSECODESTATUS')." <br>".$list['pause_code']."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delCampPauseCodes('".$list['pause_code']."','$campaign_id')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"".lang('go_DELETECAMPAIGN')." <br>".lang('go_PAUSECODESTATUS')."<br>".$list['pause_code']."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><img src=\"{$base}img/status_display_i_grayed.png\" style=\"cursor:default;width:12px;\" /></span></td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delCampPauseCodes[]\" value=\"".$list['pause_code']."\" /></td>\n";
		echo "</tr>\n";
	}
	?>
</table>
<div class="hiddenPauseCodesTable" style="display: none;width: 100%;">
<br />
<table style="margin-left:auto;margin-right:auto;">
	<tr>
		<td style="font-weight: bold;"><? echo lang('go_PauseCode'); ?>:&nbsp;&nbsp;</td>
		<td>&nbsp;<span id="spanPauseCode"></span><input type="hidden" id="pauseCodeID" value="" /></td>
	</tr>
	<tr>
		<td style="font-weight: bold;"><? echo lang('go_PauseCodeName_'); ?>&nbsp;&nbsp;</td>
		<td><input type="text" id="pauseCodeName" value="" size="25" maxlength="30" /></td>
	</tr>
	<tr>
		<td style="font-weight: bold;"><? echo lang('go_Billable_'); ?>&nbsp;&nbsp;</td>
		<td>
			<select id="isBillable">
				<option value="YES"><? echo lang('go_YES'); ?></option>
				<option value="NO"><? echo lang('go_NO'); ?></option>
				<option value="HALF"><? echo lang('go_HALF'); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: right;"><a id="saveModifiedPauseCodes" class="buttons" style="cursor: pointer">SAVE</a></td>
	</tr>
</table>
</div>
<br />
<?php
}

if ($action=='modify_hotkeys')
{
?>
<script>
$(function()
{
	$('#saveModifiedHotKeys').click(function(e)
	{
		var camp = $('#campaign_id_mod').val();
		var string = "status="+$('#').val()+"&hotkey="+$('#hotKeys').val()+"&status_name="+$('#statusNameHotKeys').val();

		$.post("<?php echo $base; ?>index.php/go_campaign_ce/go_hot_keys/save/"+camp+"/"+string, function(data)
		{			
			alert('SETTINGS SAVED.');
			$('.hiddenHotKeysTable').slideUp(1000, function()
			{
				$('#spanHotKeys').html('');
				$('#hotKeysID').val('');
				$('#statusNameHotKeys').val('');
				$('#statusHotKeys').val('');
				$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
				$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_hot_keys/modify/'+camp).fadeIn("slow");
			});
		});
	});
});
</script>
<input type="hidden" value="<?php echo $campaign_id; ?>" id="campaign_id_mod" />
<div align="center" style="font-weight:bold; color:#333; font-size:16px;"><? echo lang('go_HOTKEYSWITHINTHISCAMPAIGN'); ?>: <?php echo "$campaign_id"; ?></div>
<br />
<table id="hotKeysTable" border="0" cellpadding="1" cellspacing="1" style="width:100%;color:#555;">
	<tr style="font-weight:bold;">
    	<td style="width:20%" valign="bottom">&nbsp;&nbsp;<? echo lang('go_HOTKEY'); ?></td>
        <td style="white-space: nowrap;">&nbsp;&nbsp;<? echo lang('go_STATUS'); ?></td>
        <td style="white-space: nowrap;">&nbsp;&nbsp;<? echo lang('go_DESCRIPTION'); ?></td>
        <td style="width:5%" align="center" valign="bottom" colspan="3" nowrap><span style="cursor:pointer;" id="selectCampHotKeysAction">&nbsp;<? echo lang('go_ACTION');?> &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></td>
        <td style="width:2%" align="center" valign="bottom"><input type="checkbox" id="selectAllCampHotKeys" /></td>
    </tr>
    
    <?php
    foreach ($hotkeys_list as $idx => $list)
	{
		if ($x==0) {
			$bgcolor = "#E0F8E0";
			$x=1;
		} else {
			$bgcolor = "#EFFBEF";
			$x=0;
		}
		
		echo "<tr style=\"background-color:$bgcolor;\">\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$list['hotkey']."</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$list['status']."</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".str_replace("+"," ",$list['status_name'])."</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><img src=\"{$base}img/edit.png\" class=\"grayedout\" style=\"cursor:default;width:12px;\" /></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delCampHotKeys('".$list['hotkey']."','$campaign_id')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"".lang('go_DELETECAMPAIGN')." <br>". lang('go_HOTKEYSTATUS')."<br>".$list['status']."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><img src=\"{$base}img/status_display_i_grayed.png\" style=\"cursor:default;width:12px;\" /></span></td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delCampHotKeys[]\" value=\"".$list['hotkey']."\" /></td>\n";
		echo "</tr>\n";
	}
	?>
</table>
<br />
<?php
}
?>
