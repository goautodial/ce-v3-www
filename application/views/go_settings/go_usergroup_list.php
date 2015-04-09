<?php
####################################################################################################
####  Name:             	go_usergroup_list.php                                               ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Written by:      		Christopher Lomuntad                                         	    ####
####  License:          	AGPLv2                                                              ####
####################################################################################################
$base = base_url();
?>
<script>
$(function()
{
	$('#selectAll').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delUserGroup[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delUserGroup[]"]').each(function()
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
			$('#go_action_menu').css('left',position.left-40);
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

	$('li.go_action_submenu,li.go_status_submenu,li.go_camp_status_submenu').hover(function()
	{
		$(this).css('background-color','#ccc');
	},function()
	{
		$(this).css('background-color','#fff');
	});

	$('li.go_action_submenu').click(function () {
		var selectedUserGroups = [];
		$('input:checkbox[id="delUserGroup[]"]:checked').each(function()
		{
			selectedUserGroups.push($(this).val());
		});

		$('#go_action_menu').slideUp('fast');
		$('#go_action_menu').hide();
		toggleAction = $('#go_action_menu').css('display');

		var action = $(this).attr('id');
		if (selectedUserGroups.length<1)
		{
			alert("<? echo $this->lang->line("go_pls_sel_ug"); ?>");
		}
		else
		{
			var s = '';
			if (selectedUserGroups.length>1)
				s = 's';

			if (action == 'delete')
			{
				var what = confirm("<? echo $this->lang->line("go_del_con_ug"); ?>"+s+"?");
				if (what)
				{
					$('#table_container').load('<? echo $base; ?>index.php/go_usergroup_ce/go_update_usergroup_list/'+action+'/'+selectedUserGroups+'/');
				}
			}
			else
			{
				$('#table_container').load('<? echo $base; ?>index.php/go_usergroup_ce/go_update_usergroup_list/'+action+'/'+selectedUserGroups+'/');
			}
		}
	});

	if (<?php echo count($usergroups['list']); ?> > 0 || <?=strlen($usergroups['search']) ?> > 0)
	{
		// Pagination
		//$('#mainTable').tablePagination();

		// Table Sorter
		$("#mainTable").tablesorter({sortList:[[0,0]], headers: { 4: { sorter: false}, 5: {sorter: false} }});
	}
	else
	{
		addNewUserGroup();
	}
	
	// Tool Tip
	$(".toolTip").tipTip();
});

function changePage(pagenum)
{
	var search = $("#search_list").val();
	search = search.replace(/\s/g,"%20");
	$("#table_container").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
	$('#table_container').load('<? echo $base; ?>index.php/go_usergroup_ce/go_update_usergroup_list/search/'+pagenum+'/'+search);
}
</script>
<table id="mainTable" class="tablesorter" style="width:100%;" cellpadding=0 cellspacing=0>
	<thead>
		<tr style="font-weight:bold;">
			<th style="white-space:nowrap">&nbsp;<? echo strtoupper($this->lang->line("go_user_group")); ?></th>
			<th style="white-space:nowrap">&nbsp;<? echo strtoupper($this->lang->line("go_group_name")); ?></th>
			<th style="white-space:nowrap">&nbsp;<? echo strtoupper($this->lang->line("go_type")); ?></th>
			<th style="white-space:nowrap">&nbsp;<? echo strtoupper($this->lang->line("go_forced_timeclock")); ?></th>
			<th colspan="3" style="width:6%;text-align:center;" nowrap><span style="cursor:pointer;" id="selectAction">&nbsp;<? echo strtoupper($this->lang->line("go_action")); ?> &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
			<th style="width:2%;text-align:center;"><input type="checkbox" id="selectAll" /></th>
		</tr>
	</thead>
	<tbody>
<?php
if (count($usergroups['list']) > 0) {
	$x = 0;
	foreach ($usergroups['list'] as $list)
	{		
		if ($x==0) {
			$bgcolor = "#E0F8E0";
			$x=1;
		} else {
			$bgcolor = "#EFFBEF";
			$x=0;
		}
		
		switch($list->forced_timeclock_login)
		{
			case "Y":
				$forced_timeclock = "{$this->lang->line('go_yes')}";
				break;
			case "N":
				$forced_timeclock = "{$this->lang->line('go_no')}";
				break;
			default:
				$forced_timeclock = "{$this->lang->line('go_admin_exempt')}";
		}
		
		$acolor = ($status=="{$this->lang->line('go_active')}") ? "green" : "#F00";
		$group_type = ($this->commonhelper->checkIfTenant($list->user_group)) ? "{$this->lang->line('go_multi_tenant')}" : "{$this->lang->line('go_default')}";
		
		echo "<tr style='background-color:$bgcolor;'>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<a onclick=\"modify('{$list->user_group}')\">{$list->user_group}</a></td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<a onclick=\"modify('{$list->user_group}')\">{$list->group_name}</a></td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;$group_type</td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;$forced_timeclock</td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><span onclick=\"modify('{$list->user_group}')\" style='cursor:pointer;' class='toolTip' title='{$this->lang->line("go_modify_ug")}<br />{$list->user_group}'><img src='{$base}img/edit.png' style='cursor:pointer;width:12px;' /></span></td><td align='center' style='border-top:#D0D0D0 dashed 1px;'><span onclick=\"delUserGroup('{$list->user_group}')\" style='cursor:pointer;' class='toolTip' title='{$this->lang->line("go_del_ug")}<br />{$list->user_group}'><img src='{$base}img/delete.png' style='cursor:pointer;width:12px;' /></span></td><td align='center' style='border-top:#D0D0D0 dashed 1px;'><span><img src='{$base}img/status_display_i_grayed.png' style='width:12px;' /></span></td>\n";
		echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><input type='checkbox' id='delUserGroup[]' value='{$list->user_group}' /></td>\n";
		echo "</tr>";
	}
} else {
	echo "<tr style=\"background-color:#E0F8E0;\"><td style=\"border-top:#D0D0D0 dashed 1px;font-weight:bold;color:#FF0000;text-align:center;\" colspan=\"8\">{$this->lang->line('no_record_found')}.</td></tr>\n";
}
?>
	</tbody>
</table>
<?=$usergroups['pagelinks']['info'] ?>
<?=$usergroups['pagelinks']['links'] ?>
