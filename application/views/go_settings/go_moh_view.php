<?php
########################################################################################################
####  Name:             	go_moh_view.php                                                     ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                                 ####
####  Build:            	1375243200                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####      	                <community@goautodial.com>                                          ####
####  Written by:       	Christopher Lomuntad                                                ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$base = base_url();
$hideFromTenant = ($this->commonhelper->checkIfTenant($moh_info->user_group)) ? "display:none;" : "";
?>
<script>
$(function()
{
	$('.toolTip').tipTip();
	
	// Submit Settings
	$('#saveSettings').click(function()
	{
		var items = $('#modifyMoH').serialize();
		$.post("<?=$base?>index.php/go_moh_ce/go_moh_wizard", { items: items, action: "modify" },
		function(data){
			if (data=="SUCCESS")
			{
				alert("<? echo $this->lang->line("go_success_caps"); ?>");
			
				$('#box').animate({'top':'-2550px'},500);
				$('#overlay').fadeOut('slow');
				
				location.reload();
			}

		});
	});
	
	// Pagination
	$('#fileList').tablePagination({rowsPerPage: 10, optionsForRows: [10, 25, 50, 100, "ALL"]});

	// Table Sorter
	$("#fileList").tablesorter({sortList:[[0,0]], widgets: ['zebra']});

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

#fileList th {
	text-align: left;
}
</style>
<div align="center" style="font-weight:bold; color:#333; font-size:16px;"><? echo $this->lang->line("go_modify_moh"); ?>: <?php echo $moh_info->moh_id; ?></div>
<br />
<form id="modifyMoH" method="POST">
<?=form_hidden('moh_id',$moh_info->moh_id) ?>
<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:90%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_moh_name"); ?>:</td><td><?=form_input('moh_name',$moh_info->moh_name,'size="50" maxlength="100"') ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_status"); ?>:</td><td><?=form_dropdown('active',array('N'=>''.$this->lang->line("go_inactive_caps").'','Y'=>''.$this->lang->line("go_active_caps").''),$moh_info->active) ?></td>
    </tr>
	<tr style="<?php echo $hideFromTenant; ?>">
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_user_group"); ?>:</td><td><?=form_dropdown('user_group',$user_group_array,$moh_info->user_group) ?></td>
    </tr>
	<tr>
    	<td style="text-align:right;" nowrap><? echo $this->lang->line("go_random_order"); ?>:</td><td><?=form_dropdown('random',array('N'=>''.$this->lang->line("go_no").'','Y'=>''.$this->lang->line("go_yes").''),$moh_info->random) ?></td>
    </tr>
	<tr>
    	<td>&nbsp;</td><td>&nbsp;</td>
    </tr>
	<tr>
    	<td colspan="2">
		<table border=0 cellpadding="0" cellspacing="0" style="width:70%; color:#000; margin-left:auto; margin-right:auto;">
			<tr>
				<td style="font-weight: bold; width: 60px;">&nbsp;<? echo $this->lang->line("go_rank_caps"); ?></td>
				<td style="font-weight: bold;"><? echo $this->lang->line("go_filename"); ?></td>
				<td style="font-weight: bold; width: 60px;"><? echo $this->lang->line("go_action_caps"); ?></td>
			</tr>
			<?php
			$x = 0;
			$k = 1;
			while (($moh_file_cnt + 2) > $k) {
				$moh_rank[$k] = $k;
				$k++;
			}
			
			foreach ($moh_files as $file) {
				$doNotDelete = "cursor:pointer;\" onClick=\"delMOHFile('{$moh_info->moh_id}','{$file->filename}')";
				if ($file->filename == "conf") {
					$doNotDelete = '" class="grayedout';
				}
				
				if ($x==0) {
					$bgcolor = "#E0F8E0";
					$x=1;
				} else {
					$bgcolor = "#EFFBEF";
					$x=0;
				}
				
				echo "<tr style='background-color:$bgcolor;'>\n";
				echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;".form_dropdown("{$file->filename}",$moh_rank,$file->rank,'style="font-size:10px;"')."</td>\n";
				echo "<td style='border-top:#D0D0D0 dashed 1px;'>{$file->filename}</td>\n";
				echo "<td style='border-top:#D0D0D0 dashed 1px;text-align:center;'><img src='{$base}img/delete.png' id='DEL_{$file->filename}' style=\"width:12px;height:12px;$doNotDelete\" /></td>\n";
				echo "</tr>\n";
			}
			?>
			<tr>
				<td colspan="3" style="font-size:5px;">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" style="white-space:nowrap;text-align:center;"><? echo $this->lang->line("go_add_audio_file"); ?>: <?php echo form_dropdown('filename',$moh_audio_list,null,'id="filename" style="width:300px;"'); ?></td>
			</tr>
		</table>
	</td>
    </tr>
	<tr>
    	<td>&nbsp;</td><td>&nbsp;</td>
    </tr>
	<tr>
    	<td>&nbsp;</td><td style="text-align:right;"><span id="saveSettings" class="buttons"><? echo strtoupper($this->lang->line("go_save_settings")); ?></span><!--<input id="saveSettings" type="submit" value=" SAVE SETTINGS " style="cursor:pointer;" />--></td>
    </tr>
</table>
</form>
<br style="font-size:9px;" />
