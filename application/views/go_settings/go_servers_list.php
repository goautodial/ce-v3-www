<?php
############################################################################################
####  Name:             go_servers_list.php                                             ####
####  Type: 		    ci views                                                        ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Christopher Lomuntad <chris@goautodial.com>   ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();
?>
<script>
$(function()
{
	$('#selectAll').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delServer[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delServer[]"]').each(function()
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

	$('li.go_action_submenu,li.go_status_submenu,li.go_camp_status_submenu').hover(function()
	{
		$(this).css('background-color','#ccc');
	},function()
	{
		$(this).css('background-color','#fff');
	});

	$('li.go_action_submenu').click(function () {
		var selectedServers = [];
		$('input:checkbox[id="delServer[]"]:checked').each(function()
		{
			selectedServers.push($(this).val());
		});

		$('#go_action_menu').slideUp('fast');
		$('#go_action_menu').hide();
		toggleAction = $('#go_action_menu').css('display');

		var action = $(this).attr('id');
		if (selectedServers.length<1)
		{
			alert('Please select a Server.');
		}
		else
		{
			var s = '';
			if (selectedServers.length>1)
				s = 's';

			if (action == 'delete')
			{
				var what = confirm('Are you sure you want to delete the selected Server'+s+'?');
				if (what)
				{
					$('#table_container').load('<? echo $base; ?>index.php/go_servers_ce/go_update_server_list/'+action+'/'+selectedServers+'/');
				}
			}
			else
			{
				$('#table_container').load('<? echo $base; ?>index.php/go_servers_ce/go_update_server_list/'+action+'/'+selectedServers+'/');
			}
		}
	});

	if (<?php echo count($servers); ?> > 0 || $("#search_list").val().length > 0)
	{
		// Pagination
		//$('#mainTable').tablePagination();

		// Table Sorter
		$("#mainTable").tablesorter({headers: { 2: {sorter:"ipAddress"}, 7: { sorter: false}, 8: {sorter: false} }});
	}
	else
	{
		addNewServers();
	}
	
	// Tool Tip
	$(".toolTip").tipTip();
});

function changePage(pagenum)
{
	var search = $("#search_list").val();
	search = search.replace(/\s/g,"%20");
	$("#table_container").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
	$('#table_container').load('<? echo $base; ?>index.php/go_servers_ce/go_update_server_list/search/'+pagenum);
}
</script>
<table id="mainTable" class="tablesorter" style="width:100%;" cellpadding=0 cellspacing=0>
	<thead>
		<tr style="font-weight:bold;">
			<th style="white-space:nowrap">&nbsp;SERVER ID</th>
			<th style="white-space:nowrap">&nbsp;NAME</th>
			<th style="white-space:nowrap">&nbsp;SERVER IP</th>
			<th>&nbsp;STATUS</th>
			<th>&nbsp;ASTERISK</th>
			<th>&nbsp;TRUNKS</th>
			<th>&nbsp;GMT</th>
			<th colspan="3" style="width:6%;text-align:center;" nowrap><span style="cursor:pointer;" id="selectAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
			<th style="width:2%;text-align:center;"><input type="checkbox" id="selectAll" /></th>
		</tr>
	</thead>
	<tbody>
<?php
if (count($servers) > 0) {
	$x = 0;
	foreach ($servers as $list)
	{		
		if ($x==0) {
			$bgcolor = "#E0F8E0";
			$x=1;
		} else {
			$bgcolor = "#EFFBEF";
			$x=0;
		}
		
		$status = ($list->active=="Y") ? "ACTIVE" : "INACTIVE";
		$acolor = ($status=="ACTIVE") ? "green" : "#F00";
		
		echo "<tr style='background-color:$bgcolor;'>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<a onclick=\"modify('{$list->server_id}','{$list->server_ip}')\">{$list->server_id}</a></td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<a onclick=\"modify('{$list->server_id}','{$list->server_ip}')\">{$list->server_description}</a></td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->server_ip}</td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;color:$acolor;font-weight:bold;width:10%;'>&nbsp;$status</td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->asterisk_version}</td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->max_vicidial_trunks}</td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->local_gmt}</td>";
		echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><span onclick=\"modify('{$list->server_id}','{$list->server_ip}')\" style='cursor:pointer;' class='toolTip' title='MODIFY SERVER<br />{$list->server_id}'><img src='{$base}img/edit.png' style='cursor:pointer;width:12px;' /></span></td><td align='center' style='border-top:#D0D0D0 dashed 1px;'><span onclick=\"delServer('{$list->server_id}','{$list->server_ip}')\" style='cursor:pointer;' class='toolTip' title='DELETE SERVER<br />{$list->server_id}'><img src='{$base}img/delete.png' style='cursor:pointer;width:12px;' /></span></td><td align='center' style='border-top:#D0D0D0 dashed 1px;'><span><img src='{$base}img/status_display_i_grayed.png' style='width:12px;' /></span></td>\n";
		echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><input type='checkbox' id='delServer[]' value='{$list->server_id}' /></td>\n";
		echo "</tr>";
	}
} else {
	echo "<tr style=\"background-color:#E0F8E0;\"><td style=\"border-top:#D0D0D0 dashed 1px;font-weight:bold;color:#FF0000;text-align:center;\" colspan=\"11\">No record(s) found.</td></tr>\n";
}
?>
	</tbody>
</table>
<?=$pagelinks['info'] ?>
<?=$pagelinks['links'] ?>