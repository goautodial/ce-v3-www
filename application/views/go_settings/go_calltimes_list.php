<?php
####################################################################################################
####  Name:             	go_calltimes_list.php                  	                            ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1373515200                                                          ####
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
			$('input:checkbox[id="delCalltimes[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delCalltimes[]"]').each(function()
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
	
	var toggleStateAction = $('#go_state_action_menu').css('display');
	$('#selectStateAction').click(function()
	{
		if (toggleStateAction == 'none')
		{
			var position = $(this).offset();
			$('#go_state_action_menu').css('left',position.left-40);
			$('#go_state_action_menu').css('top',position.top+16);
			$('#go_state_action_menu').slideDown('fast');
			toggleStateAction = $('#go_state_action_menu').css('display');
		}
		else
		{
			$('#go_state_action_menu').slideUp('fast');
			$('#go_state_action_menu').hide();
			toggleStateAction = $('#go_state_action_menu').css('display');
		}
	});

	$('li.go_action_submenu,li.go_state_submenu').hover(function()
	{
		$(this).css('background-color','#ccc');
	},function()
	{
		$(this).css('background-color','#fff');
	});

	$('li.go_action_submenu').click(function () {
		var selectedCalltimes = [];
		$('input:checkbox[id="delCalltimes[]"]:checked').each(function()
		{
			selectedCalltimes.push($(this).val());
		});

		$('#go_action_menu').slideUp('fast');
		$('#go_action_menu').hide();
		toggleAction = $('#go_action_menu').css('display');

		var action = $(this).attr('id');
		if (selectedCalltimes.length<1)
		{
			alert('Please select a Call Time.');
		}
		else
		{
			var s = '';
			if (selectedCalltimes.length>1)
				s = 's';

			if (action == 'delete')
			{
				var what = confirm('Are you sure you want to delete the selected Call Time'+s+'?');
				if (what)
				{
					$.post("<?=$base?>index.php/go_calltimes_ce/go_get_calltimes/"+action+"/"+selectedCalltimes+"/",
						function(data){
						if (data=="SUCCESS")
						{
							alert("Success!\n\nSelected call times has been deleted.");
							
							location.reload();
						} else {
							alert(data);
						}
				
					});
				}
			}
			else
			{
				$('#table_container').load('<? echo $base; ?>index.php/go_calltimes_ce/go_get_calltimes/'+action+'/'+selectedCalltimes+'/');
			}
		}
	});
	
	$('li.go_state_action_submenu').click(function () {
		var selectedStateCalltimes = [];
		$('input:checkbox[id="delStateCalltimes[]"]:checked').each(function()
		{
			selectedStateCalltimes.push($(this).val());
		});

		$('#go_state_action_menu').slideUp('fast');
		$('#go_state_action_menu').hide();
		toggleStateAction = $('#go_state_action_menu').css('display');

		var action = $(this).attr('id');
		if (selectedStateCalltimes.length<1)
		{
			alert('Please select a State Call Time.');
		}
		else
		{
			var s = '';
			if (selectedStateCalltimes.length>1)
				s = 's';

			if (action == 'delete')
			{
				var what = confirm('Are you sure you want to delete the selected State Call Time'+s+'?');
				if (what)
				{
					$.post("<?=$base?>index.php/go_calltimes_ce/go_get_calltimes/"+action+"/"+selectedStateCalltimes+"/state/",
						function(data){
						if (data=="SUCCESS")
						{
							alert("Success!\n\nSelected state call times has been deleted.");
							
							location.reload();
						} else {
							alert(data);
						}
				
					});
				}
			}
			else
			{
				$('#table_container').load('<? echo $base; ?>index.php/go_calltimes_ce/go_get_calltimes/'+action+'/'+selectedCalltimes+'/state/');
			}
		}
	});

	if (<?php echo count($calltimes['list']); ?> > 0 || <?=strlen($type) ?> > 0)
	{
		// Pagination
		//$('#mainTable').tablePagination();

		// Table Sorter
		$("#mainTable").tablesorter({sortList:[[0,0]], headers: { 5: { sorter: false}, 6: {sorter: false} }});
	}
	else
	{
		addNewCalltimes();
	}
	
	if ('<?=$type ?>'=='showState') {
		$("#showList_div").hide();
		$("#showState_div").show();
	} else {
		$("#showList_div").show();
		$("#showState_div").hide();
	}
	
	// Tool Tip
	$(".toolTip").tipTip();
	
	$("#closebox").click(function(){
		$(".sippy-info").animate({top:"-6000px"});
		$("#overlay").fadeOut('fast');
	});

});

function changePage(pagenum)
{
	var search = $("#search_list").val();
	search = search.replace(/\s/g,"%20");
	var type = $("#request").html();
	$("#table_container").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
	$('#table_container').load('<? echo $base; ?>index.php/go_calltimes_ce/go_get_calltimes/search/'+type+'/'+pagenum+'/'+search);
}
</script>
<div id="showList_div">
<table id="mainTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;">
	<thead>
		<tr style="font-weight:bold;">
			<th style="white-space:nowrap">&nbsp;CALLTIME ID</th>
			<th style="white-space:nowrap">&nbsp;CALLTIME NAME</th>
			<th style="white-space:nowrap">&nbsp;DEFAULT START</th>
			<th style="white-space:nowrap">&nbsp;DEFAULT STOP</th>
			<th>&nbsp;GROUP</th>
			<th style="width:6%;text-align:center;" nowrap><span style="cursor:pointer;" id="selectAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
			<th style="width:2%;text-align:center;"><input type="checkbox" id="selectAll" /></th>
		</tr>
	</thead>
	<tbody>
	<?php
	if (count($calltimes['list']) > 0) {
		$x = 0;
		foreach ($calltimes['list'] as $list)
		{
			if ($x==0) {
				$bgcolor = "#E0F8E0";
				$x=1;
			} else {
				$bgcolor = "#EFFBEF";
				$x=0;
			}
			
			$ugroup = ($list->user_group=="---ALL---") ? "ALL USER GROUPS" : $list->user_group;
			$default_start = $list->ct_default_start;
			$default_stop = $list->ct_default_stop;
			//if (strlen($default_start) < 4 && $default_start != 0) { $default_start = "0".$default_start; }
			//else if ($default_start == 0) { $default_start = "000".$default_start; }
			//if (strlen($default_stop) < 4 && $default_stop != 0) { $default_stop = "0".$default_stop; }
			//else if ($default_stop == 0) { $default_stop = "000".$default_stop; }
			
			echo "<tr style='background-color:$bgcolor;'>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<a onclick=\"modify('{$list->call_time_id}')\">{$list->call_time_id}</a></td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->call_time_name}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$default_start}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$default_stop}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;white-space:nowrap;width:6%;'>&nbsp;$ugroup&nbsp;&nbsp;&nbsp;</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><span onclick=\"modify('{$list->call_time_id}')\" style='cursor:pointer;margin:5px;' class='toolTip' title='MODIFY CALLTIME<br />{$list->call_time_id}'><img src='{$base}img/edit.png' style='cursor:pointer;width:12px;' /></span>
			      <span onclick=\"delCalltimes('{$list->call_time_id}')\" style='cursor:pointer;margin:5px;' class='toolTip' title='DELETE CALLTIME<br />{$list->call_time_id}'><img src='{$base}img/delete.png' style='cursor:pointer;width:12px;' /></span>
			      <span style='margin:5px;'><img src='{$base}img/status_display_i_grayed.png' style='width:12px;cursor:pointer;' /></span></td>\n";
			echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><input type='checkbox' id='delCalltimes[]' value='{$list->call_time_id}' /></td>\n";
			echo "</tr>";
		}
	} else {
		echo "<tr style=\"background-color:#E0F8E0;\"><td style=\"border-top:#D0D0D0 dashed 1px;font-weight:bold;color:#FF0000;text-align:center;\" colspan=\"8\">No record(s) found.</td></tr>\n";
	}
	?>
	</tbody>
</table>
<?=$calltimes['pagelinks']['info'] ?>
<?=$calltimes['pagelinks']['links'] ?>
</div>

<div id="showState_div" class="hideSpan">
<table id="stateTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;">
	<thead>
        <tr style="font-weight:bold;">
            <th style="width:12%">&nbsp;CALLTIME ID</th>
            <th style="width:12%">&nbsp;CALLTIME STATE</th>
            <th style="width:25%">&nbsp;CALLTIME NAME</th>
			<th style="white-space:nowrap">&nbsp;DEFAULT START</th>
			<th style="white-space:nowrap">&nbsp;DEFAULT STOP</th>
			<th>&nbsp;GROUP</th>
			<th style="width:6%;text-align:center;" nowrap><span style="cursor:pointer;" id="selectStateAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
            <th style="width:2%;text-align:center;"><input type="checkbox" id="selectAllState" /></th>
        </tr>
    </thead>
    <tbody>
	<?php
	if (count($states['list']) > 0) {
		$x=0;
		foreach ($states['list'] as $list)
		{
			if ($x==0) {
				$bgcolor = "#E0F8E0";
				$x=1;
			} else {
				$bgcolor = "#EFFBEF";
				$x=0;
			}
			
			$ugroup = ($list->user_group=="---ALL---") ? "ALL USER GROUPS" : $list->user_group;
			$default_start = $list->sct_default_start;
			$default_stop = $list->sct_default_stop;
			//if (strlen($default_start) < 4 && $default_start != 0) { $default_start = "0".$default_start; }
			//else if ($default_start == 0) { $default_start = "000".$default_start; }
			//if (strlen($default_stop) < 4 && $default_stop != 0) { $default_stop = "0".$default_stop; }
			//else if ($default_stop == 0) { $default_stop = "000".$default_stop; }
			
			echo "<tr style='background-color:$bgcolor;'>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<a onclick=\"modify('{$list->state_call_time_id}','state')\">{$list->state_call_time_id}</a></td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->state_call_time_state}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->state_call_time_name}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$default_start}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$default_stop}</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;white-space:nowrap;width:6%;'>&nbsp;$ugroup&nbsp;&nbsp;&nbsp;</td>";
			echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><span onclick=\"modify('{$list->state_call_time_id}','state')\" style='cursor:pointer;margin:5px;' class='toolTip' title='MODIFY STATE CALLTIME<br />{$list->state_call_time_id}'><img src='{$base}img/edit.png' style='cursor:pointer;width:12px;' /></span>
					  <span onclick=\"delCalltimes('{$list->state_call_time_id}','state')\" style='cursor:pointer;margin:5px;' class='toolTip' title='DELETE STATE CALLTIME<br />{$list->state_call_time_id}'><img src='{$base}img/delete.png' style='cursor:pointer;width:12px;' /></span>
					  <span style='margin:5px;'><img src='{$base}img/status_display_i_grayed.png' style='width:12px;cursor:pointer;' /></span></td>\n";
			echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><input type='checkbox' id='delStateCalltimes[]' value='{$list->state_call_time_id}' /></td>\n";
			echo "</tr>";
		}
	} else {
		echo "<tr style=\"background-color:#E0F8E0;\"><td style=\"border-top:#D0D0D0 dashed 1px;font-weight:bold;color:#FF0000;text-align:center;\" colspan=\"8\">No record(s) found.</td></tr>\n";
	}
	?>
    </tbody>
</table>
<?=$states['pagelinks']['info'] ?>
<?=$states['pagelinks']['links'] ?>
</div>