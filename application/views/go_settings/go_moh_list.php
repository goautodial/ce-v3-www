<?php
########################################################################################################
####  Name:             	go_moh_list.php                                                     ####
####  Type:             	ci views for music on hold - administrator                          ####
####  Version:          	3.0                                                                 ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Christopher Lomuntad					            ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$base = base_url();
$hideFromTenant = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "display:none;" : "";
?>
<script>
$(function()
{
	$(".toolTip").tipTip();
	
	$('#selectAll').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delMOH[]"]').each(function()
			{
				if ($(this).is(':visible') && $(this).val() != 'default')
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delMOH[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).removeAttr('checked');
				}
			});
		}
	});
	
	var toggleAction = $('#go_action_menu').css('display');
	$('#selectAction').click(function()
	{
		if (toggleAction == 'none')
		{
			var position = $(this).offset();
			$('#go_action_menu').css('left',position.left-68);
			$('#go_action_menu').css('top',position.top+16);
			$('#go_action_menu').slideDown('fast');
			toggleAction = $('#go_action_menu').css('display');
		}
		else
		{
			$('#go_action_menu').slideUp('fast');
			$('#go_action_menu').hide();
			toggleAction = $('#go_action_menu').css('display');
		}
	});

	if (<?php echo $moh_list->num_rows(); ?> > 0 || <?php echo strlen($search_list); ?> > 0)
	{
		
		// Pagination
		//$('#mainTable').tablePagination();

		// Table Sorter
		$("#mainTable").tablesorter({headers: { 5: { sorter: false}, 6: { sorter: false} }});
	}
	else
	{
		addNewMOH();
	}
});
</script>
<table id="mainTable" class="tablesorter" style="width:100%;" cellpadding=0 cellspacing=0>
	<thead>
		<tr style="font-weight:bold;">
			<th>&nbsp;<? echo $this->lang->line("go_moh_id_caps"); ?></th>
			<th>&nbsp;<? echo $this->lang->line("go_moh_name_caps"); ?></th>
			<th>&nbsp;<? echo $this->lang->line("go_status_caps"); ?></th>
			<th>&nbsp;<? echo $this->lang->line("go_random_order_caps"); ?></th>
			<th style="<?php echo $hideFromTenant; ?>">&nbsp;<? echo $this->lang->line("go_group_caps"); ?></th>
			<th colspan="3" style="width:6%;text-align:center;" nowrap><span style="cursor:pointer;" id="selectAction">&nbsp;<? echo $this->lang->line("go_action_caps"); ?> &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
			<th style="width:2%;text-align:center;"><input type="checkbox" id="selectAll" /></th>
		</tr>
	</thead>
	<tbody>
<?php
if ($moh_list->num_rows() > 0) {
	$x = 0;
	foreach ($moh_list->result() as $list)
	{
		if ($x==0) {
			$bgcolor = "#E0F8E0";
			$x=1;
		} else {
			$bgcolor = "#EFFBEF";
			$x=0;
		}
		
		$status = ($list->active=="Y") ? "{$this->lang->line("go_active_caps")}" : "{$this->lang->line("go_inactive_caps")}";
		$acolor = ($status=="{$this->lang->line("go_active_caps")}") ? "green" : "#F00";
		$ugroup = ($list->user_group=="---{$this->lang->line("go_all_caps")}---") ? "{$this->lang->line("go_all_user_groups_caps")}" : $list->user_group;
		$random = ($list->random=="Y") ? "{$this->lang->line("go_yes")}" : "{$this->lang->line("go_no")}";
		
		$deleteMOH = "<span onclick=\"delMOH('{$list->moh_id}')\" style='cursor:pointer;' class='toolTip' title='{$this->lang->line("go_del_moh_id")}<br />{$list->moh_id}'>";
		$deleteMOHclass = "";
		$deleteMOHbox = "";
		if ($list->moh_id=="default")
		{
			$deleteMOH = "<span>";
			$deleteMOHclass = "class='grayedout'";
			$deleteMOHbox = "disabled";
		}
		
		echo "<tr style='background-color:$bgcolor;'>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<a onclick=\"modify('{$list->moh_id}')\">{$list->moh_id}</a></td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<a onclick=\"modify('{$list->moh_id}')\">{$list->moh_name}</a></td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;color:$acolor;font-weight:bold;width:10%;'>&nbsp;$status</td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;$random</td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;white-space:nowrap;width:6%;$hideFromTenant'>&nbsp;$ugroup&nbsp;&nbsp;&nbsp;</td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><span onclick=\"modify('{$list->moh_id}')\" style='cursor:pointer;' class='toolTip' title='{$this->lang->line("go_modify_moh_id")}<br />{$list->moh_id}'><img src='{$base}img/edit.png' style='cursor:pointer;width:12px;' /></span></td><td align='center' style='border-top:#D0D0D0 dashed 1px;'>{$deleteMOH}<img src='{$base}img/delete.png' style='width:12px;' {$deleteMOHclass} /></span></td><td align='center' style='border-top:#D0D0D0 dashed 1px;'><span><img src='{$base}img/status_display_i_grayed.png' style='width:12px;' /></span></td>\n";
		echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><input type='checkbox' id='delMOH[]' value='{$list->moh_id}' {$deleteMOHbox} /></td>\n";
		echo "</tr>";
	}
} else {
	echo "<tr style=\"background-color:#E0F8E0;\"><td style=\"border-top:#D0D0D0 dashed 1px;font-weight:bold;color:#FF0000;text-align:center;\" colspan=\"9\">{$this->lang->line("go_no_records_found")}</td></tr>\n";
}
?>
	</tbody>
</table>
<div style="padding-top:5px;text-align: right;"><span style="float: left"><?=$pagelinks['links'] ?></span><?=$pagelinks['info'] ?></div>
