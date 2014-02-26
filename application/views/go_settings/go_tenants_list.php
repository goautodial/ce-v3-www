<?php
####################################################################################################
####  Name:             	go_tenants_list.php                                                 ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1375243200                                                          ####
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
	$('#selectAllTenant').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delTenant[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delTenant[]"]').each(function()
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
			$('#go_action_menu').css('left',position.left-70);
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
		var selectedTenants = [];
		$('input:checkbox[id="delTenant[]"]:checked').each(function()
		{
			selectedTenants.push($(this).val());
		});

		$('#go_action_menu').slideUp('fast');
		$('#go_action_menu').hide();
		toggleAction = $('#go_action_menu').css('display');

		var action = $(this).attr('id');
		if (selectedTenants.length<1)
		{
			alert('Please select a Tenant.');
		}
		else
		{
			var s = '';
			if (selectedTenants.length>1)
				s = 's';

			if (action == 'delete')
			{
				var what = confirm("WARNING! Are you sure you want to delete the selected Tenant"+s+"?\n\nThis will DELETE ALL entries under the selected tenant"+s+".\n> Users\n> Campaigns\n> List IDs\n> Phones\n> Leads uploaded\n\nClick OK to continue.");
				if (what)
				{
					$('#table_container').load('<? echo $base; ?>index.php/go_tenants_ce/go_update_tenants_list/'+action+'/'+selectedTenants+'/');
				}
			}
			else
			{
				$('#table_container').load('<? echo $base; ?>index.php/go_tenants_ce/go_update_tenants_list/'+action+'/'+selectedTenants+'/');
			}
		}
	});

	if (<?php echo count($tenants['list']); ?> > 0 || $("#search_list").val().length > 0)
	{
		// Pagination
		//$('#mainTable').tablePagination();

		// Table Sorter
		<?php
		if ($this->config->item('VARSERVTYPE') == "public") {
		?>
		$("#mainTable").tablesorter({sortList:[[0,0]], headers: { 5: { sorter: false}, 6: {sorter: false} }, widgets: ['zebra']});
		<?php
		} else {
		?>
		$("#mainTable").tablesorter({sortList:[[0,0]], headers: { 4: { sorter: false}, 5: {sorter: false} }, widgets: ['zebra']});
		<?php
		}
		?>
	}
	else
	{
		addNewTenant();
	}
	
	// Tool Tip
	$(".toolTip").tipTip();
});
</script>
<table id="mainTable" class="tablesorter" style="width:100%;" cellpadding=0 cellspacing=0>
	<thead>
		<tr style="font-weight:bold;">
			<th style="white-space:nowrap">&nbsp;TENANT ID</th>
			<th style="white-space:nowrap">&nbsp;TENANT NAME</th>
			<th style="white-space:nowrap">&nbsp;NUMBER OF AGENTS</th>
			<?php
			if ($this->config->item('VARSERVTYPE') == "public") {
			?>
			<th style="white-space:nowrap">&nbsp;BALANCE</th>
			<?php
			}
			?>
			<th style="white-space:nowrap;width:20%;">&nbsp;STATUS</th>
			<th colspan="3" style="width:6%;text-align:center;" nowrap><span style="cursor:pointer;" id="selectAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
			<th style="width:2%;text-align:center;"><input type="checkbox" id="selectAllTenant" /></th>
		</tr>
	</thead>
	<tbody>
<?php
if (count($tenants['list']) > 0)
{
	$x = 0;
	foreach ($tenants['list'] as $idx => $list)
	{
		$class = ($x&1) ? "odd" : "even";
		$status = ($list['active']=='Y') ? "green'>ACTIVE" : "red'>INACTIVE";
		echo "<tr class='$class'>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<a onclick=\"modify('$idx')\">$idx</a></td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<a onclick=\"modify('$idx')\">{$list['tenant_name']}</a></td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list['cnt']}</td>";
		if ($this->config->item('VARSERVTYPE') == "public") {
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list['negative']}\${$list['balance']}</td>";
		}
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<span style='font-weight:bold;color:{$status}</span></td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><span onclick=\"modify('$idx')\" style='cursor:pointer;' class='toolTip' title='MODIFY TENANT ID<br />$idx'><img src='{$base}img/edit.png' style='cursor:pointer;width:12px;' /></span></td><td align='center' style='border-top:#D0D0D0 dashed 1px;'><span onclick=\"delTenant('$idx')\" style='cursor:pointer;' class='toolTip' title='DELETE TENANT ID<br />$idx'><img src='{$base}img/delete.png' style='cursor:pointer;width:12px;' /></span></td><td align='center' style='border-top:#D0D0D0 dashed 1px;'><span><img src='{$base}img/status_display_i_grayed.png' style='width:12px;' /></span></td>\n";
		echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><input type='checkbox' id='delTenant[]' value='$idx' /></td>\n";
		echo "</tr>";
		$x++;
	}
} else {
	echo "<tr class='odd'><td colspan='8' style='font-weight:bold;color:red;text-align:center;border-top:#D0D0D0 dashed 1px;'>No records found.</td></tr>";
}
?>
	</tbody>
</table>
<?=$tenants['pagelinks']['info'] ?>
<?=$tenants['pagelinks']['links'] ?>
