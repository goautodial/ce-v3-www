<?php
############################################################################################
####  Name:             go_ingroup_list.php                                             ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Jerico James F. Milo                                            ####
####  Modified by:      Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();

if($permissions->ingroup_read == "N"){
	die("<br />Error: You do not have permission to view the list of in-groups.");
}
?>
<style type="text/css">
#selectAction,#selectDIDAction {
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

#mainTable th,#DIDTable th,#IVRTable th {
	text-align:left;
}

.buttones,.buttons {
	color:#7A9E22;
	cursor:pointer;
}

.buttones:hover,.buttons:hover{
	font-weight:bold;
}

.buttons {
	font-size: 12px;
}

.trview {
	background-color: #EFFBEF;
}

.tableadvace {
	display: none;
}

/* Table Sorter */
table.tablesorter thead tr .header {
/*	background-image: url(<? echo $base; ?>js/tablesorter/themes/blue/bg.gif);
	background-repeat: no-repeat;
	background-position: center right;*/
	cursor: pointer;
}
/*table.tablesorter thead tr .headerSortUp {
	background-image: url(<? echo $base; ?>js/tablesorter/themes/blue/asc.gif);
}
table.tablesorter thead tr .headerSortDown {
	background-image: url(<? echo $base; ?>js/tablesorter/themes/blue/desc.gif);
}*/
/* Table Sorter */

.modify-value {
  font-weight: bold;
  color: #7f7f7f;
}

/* Ingroup Tabs */
#tabSettings {
	border-radius: 5px 0px 0px 0px;
	border-top: 1px solid #DDDEDF;
	border-left: 1px solid #DDDEDF;
	border-right: 1px solid #DDDEDF;
}

#tabAgents {
	border-radius: 0px 5px 0px 0px;
	border-top: 1px solid #DDDEDF;
	border-right: 1px solid #DDDEDF;
}

.ingroupTabs {
	<?php
	if (preg_match("/Chrome/",$userBrowser['name']))
	{
	?>
	padding: 4px 10px 7px 10px;
	<?php
	} else {
	?>
	padding: 4px 10px 6px 10px;
	<?php
	}
	?>
	line-height: 28px;
	
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.tabOn {
	background: #FFFFFF;
	cursor: default;
}

.tabOff {
	background: -moz-linear-gradient(top,  rgba(239,251,239,1) 0%, rgba(239,251,239,0.99) 1%, rgba(221,222,223,0.8) 20%, rgba(229,229,229,0) 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(239,251,239,1)), color-stop(1%,rgba(239,251,239,0.99)), color-stop(20%,rgba(221,222,223,0.8)), color-stop(100%,rgba(229,229,229,0))); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  rgba(239,251,239,1) 0%,rgba(239,251,239,0.99) 1%,rgba(221,222,223,0.8) 20%,rgba(229,229,229,0) 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  rgba(239,251,239,1) 0%,rgba(239,251,239,0.99) 1%,rgba(221,222,223,0.8) 20%,rgba(229,229,229,0) 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  rgba(239,251,239,1) 0%,rgba(239,251,239,0.99) 1%,rgba(221,222,223,0.8) 20%,rgba(229,229,229,0) 100%); /* IE10+ */
	background: linear-gradient(to bottom,  rgba(239,251,239,1) 0%,rgba(239,251,239,0.99) 1%,rgba(221,222,223,0.8) 20%,rgba(229,229,229,0) 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#effbef', endColorstr='#00e5e5e5',GradientType=0 ); /* IE6-9 */

	cursor: pointer;
}

.tabOff:hover {
	background: #FFFFFF;
	<?php
	if ($userBrowser['name'] == "Google Chrome")
	{
	?>
	padding: 4px 10px 6px 10px;
	<?php
	} else {
	?>
	padding: 4px 10px 5px 10px;
	<?php
	}
	?>
	border-bottom: 1px solid #DDDEDF;
	cursor: pointer;
}
</style>
<script>
function modify(type,ingroup)
{
	if ('<?php echo $permissions->ingroup_update; ?>'!='N')
	{
		switch (type)
		{
			case "ingroup":
				var width = '860px';
				break;
			
			case "did":
				var width = '50%';
				break;
			
			case "ivr":
				var width = '800px';
				break;
		}
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': width, 'margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
		$('#box').animate({
			top: "70px"
		}, 500);
		$("html, body").animate({ scrollTop: 0 }, 500);
		
		$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_ingroup/go_get_settings/'+type+'/'+ingroup).fadeIn("slow");
	} else {
		alert("Error: You do not have permission to modify this in-group.");
	}
}

function delIngroup(groupid)
{
	if ('<?php echo $permissions->ingroup_delete; ?>'!='N')
	{
		var what = confirm('Are you sure you want to delete this In-group?\n\n'+groupid);
		if (what)
		{
			$.post("<?php echo $base; ?>index.php/go_ingroup/deletesubmit/", { listid_delete: groupid, action: "deletelist" }, function(data)
			{
				//alert(data);
				var datas = data.split(":");
				var i=0;
				if (datas[0] == "SUCCESS"){
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_ingroup/go_ingroup_list/');
				} else {
					alert(data);
				}
			});
		}
	} else {
			alert("Error: You do not have permission to delete in-group.");
	}
}

function delDID(didid)
{
	if ('<?php echo $permissions->ingroup_delete; ?>'!='N')
	{
		var what = confirm('Are you sure you want to delete this DID?\n\n'+didid);
		if (what)
		{
			$.post("<?php echo $base; ?>index.php/go_ingroup/deletesubmit/", { didid_delete: didid, action: "deletedid" }, function(data)
			{
				//alert(data);
				var datas = data.split(":");
				var i=0;
				if (datas[0] == "SUCCESS"){
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_ingroup/go_ingroup_list/');
				} else {
					alert(data);
				}
			});
		}
	} else {
			alert("Error: You do not have permission to delete DID.");
	}
}

function delIVR(callmenu)
{
	if ('<?php echo $permissions->ingroup_delete; ?>'!='N')
	{
		var what = confirm('Are you sure you want to delete this Call Menu / IVR?\n\n'+callmenu);
		if (what)
		{
			$.post("<?php echo $base; ?>index.php/go_ingroup/deletesubmit/", { callmenu_delete: callmenu, action: "deletecallmenu" }, function(data)
			{
				//alert(data);
				var datas = data.split(":");
				var i=0;
				if (datas[0] == "SUCCESS"){
					$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
					$('#table_reports').load('<? echo $base; ?>index.php/go_ingroup/go_ingroup_list/');
				} else {
					alert(data);
				}
			});
		}
	} else {
			alert("Error: You do not have permission to delete Call Menu / IVR.");
	}
}

function viewInfo(camp, type, h)
{
	$('#statusOverlay').fadeIn('fast');
	$('#statusBox').css({'width': '400px','height': h,'margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '20px', 'position': 'fixed'});
	$('#statusBox').animate({
// 		top: Math.max(0, (($(window).height() - $('#statusBox').outerHeight()) / 2) + $(window).scrollTop()) + "px"
		top: "70px"
	}, 500);
	
	$("#statusOverlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#statusOverlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_view_info/'+camp+'/'+type).fadeIn("slow");		
}

function changePage(type,pagenum)
{
	$("html, body").animate({ scrollTop: 0 }, 500);
	var search = $("#search_ingroup").val();
	$("#table_reports").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
	$('#table_reports').load('<? echo $base; ?>index.php/go_ingroup/go_change_page/'+type+'/'+pagenum+'/'+search);
}

function changeAgentRankPage(group,pagenum)
{
	$("html, body").animate({ scrollTop: 0 }, 500);
	var search = $("#search_user").val();
	$("#agentRanks").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
	$('#agentRanks').load('<? echo $base; ?>index.php/go_ingroup/go_change_ranks_page/'+group+'/'+pagenum+'/'+search);
}

$(function ()
{
	var request = $('#request').html();
	var tabName = '';
	var cntIngroup = <?php echo count($ingrouplists); ?>;
	$('.tabtoggle').each(function()
	{
		tabName = $(this).attr('id');
		if (tabName == request)
		{
			$('#' + tabName + '_div').show();
		}
		else
		{
			$('#' + tabName + '_div').hide();
		}
	});
	
	$('#selectAll').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delIngroup[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delIngroup[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).removeAttr('checked');
				}
			});
		}
	});
	
	$('#selectAllDIDs').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delDID[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delDID[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).removeAttr('checked');
				}
			});
		}
	});
	
	$('#selectAllIVRs').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delIVR[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delIVR[]"]').each(function()
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
	
	var toggleDIDAction = $('#go_did_menu').css('display');
	$('#selectDIDAction').click(function()
	{
		if (toggleDIDAction == 'none')
		{
			var position = $(this).offset();
			$('#go_did_menu').css('left',position.left-68);
			$('#go_did_menu').css('top',position.top+16);
			$('#go_did_menu').slideDown('fast');
			toggleDIDAction = $('#go_did_menu').css('display');
		}
		else
		{
			$('#go_did_menu').slideUp('fast');
			$('#go_did_menu').hide();
			toggleDIDAction = $('#go_did_menu').css('display');
		}
	});
	
	var toggleIVRAction = $('#go_ivr_menu').css('display');
	$('#selectIVRAction').click(function()
	{
		if (toggleIVRAction == 'none')
		{
			var position = $(this).offset();
			$('#go_ivr_menu').css('left',position.left-38);
			$('#go_ivr_menu').css('top',position.top+16);
			$('#go_ivr_menu').slideDown('fast');
			toggleIVRAction = $('#go_ivr_menu').css('display');
		}
		else
		{
			$('#go_ivr_menu').slideUp('fast');
			$('#go_ivr_menu').hide();
			toggleIVRAction = $('#go_ivr_menu').css('display');
		}
	});
	
	$(document).mouseup(function (e)
	{
		var content = $('#go_action_menu');
		if (content.has(e.target).length === 0 && (e.target.id != 'selectAction' && e.target.id != 'selectDIDAction' && e.target.id != 'selectIVRAction'))
		{
			content.slideUp('fast');
			content.hide();
			toggleAction = $('#go_action_menu').css('display');
			toggleDID = $('#go_status_menu').css('display');
			toggleIVR = $('#go_camp_status_menu').css('display');
		}
	});
	
	$('.hoverIngroupID').each(function()
	{
		$(this).hover(function()
		{
			$(this).css('color','red');
		},
		function()
		{
			$(this).css('color','black');
		});
	});
	
	// Tool Tip
	$(".toolTip").tipTip();

	if (cntIngroup > 0)
	{
		// Pagination
		//$('#mainTable').tablePagination();

		// Table Sorter 
		$("#mainTable").tablesorter({headers: { 6: { sorter: false}, 7: {sorter: false} }});
	}
	else
	{
		addNewIngroup();
	}
	
	$('#attempt_delay').bind("keydown keypress", function(event)
	{
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.type == "keydown") {
			// For normal key press
			// Allow: backspace, delete, tab, escape, and enter
			if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: Ctrl+X
            (event.keyCode == 88 && event.ctrlKey === true) || 
             // Allow: Ctrl+C
            (event.keyCode == 67 && event.ctrlKey === true) || 
             // Allow: Ctrl+V
            (event.keyCode == 86 && event.ctrlKey === true) || 
             // Allow: Ctrl+Z
            (event.keyCode == 90 && event.ctrlKey === true) || 
             // Allow: Ctrl+Y
            (event.keyCode == 89 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return true;
			}
			else {
				// Ensure that it is a number and stop the keypress
				if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
					event.preventDefault(); 
				}   
			}
		} else {
			// For ASCII Key Codes
			if ((event.which > 31 && event.which < 48) || event.which > 57)
				return false;
		}
	});

});

function checkLeadRecycle()
{
	var campid = $('#leadCampID').val();
	var statusid = $('#leadStatusID').val();
	
	if (campid.length > 0 && statusid.length > 0)
		$('#lloading').load('<? echo $base; ?>index.php/go_campaign_ce/go_lead_recycle/check/'+campid+'/'+statusid);
}
</script>
<div id="showList_div">
<table id="mainTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;">
	<thead>
        <tr style="font-weight:bold;">
            <th style="width:12%">&nbsp;&nbsp;IN-GROUP</th>
            <th>&nbsp;&nbsp;<?php echo lang('go_DESCRIPTIONS'); ?></th>
            <th>&nbsp;&nbsp;<?php echo lang('go_PRIORITY'); ?></th>
            <th>&nbsp;&nbsp;<?php echo lang('go_STATUS'); ?></th>
            <th>&nbsp;&nbsp;<?php echo lang('go_TIME'); ?></th>
            <th colspan="3" style="width:6%;text-align:center;" nowrap>
				<span style="cursor:pointer;" id="selectAction">&nbsp;<?php echo lang('go_ACTION'); ?> &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
            <th style="width:2%;text-align:center;"><input type="checkbox" id="selectAll" /></th>
        </tr>
    </thead>
    <tbody>
<?php
	$x=0;
	foreach ($ingrouplists as $row)
	{
		if ($x==0) {
			$bgcolor = "#E0F8E0";
			$x=1;
		} else {
			$bgcolor = "#EFFBEF";
			$x=0;
		}

		if ($row->active == 'Y')
		{
			$active = '<span style="color:green;font-weight:bold;">ACTIVE</span>';
		} else {
			$active = '<span style="color:#F00;font-weight:bold;">INACTIVE</span>';
		}
		
			$donotdelete = "grayedout";
		
		echo "<tr style=\"background-color:$bgcolor;\">\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modify('ingroup','".$row->group_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverIngroupID\" title=\"".lang('go_MODIFYINGROUP')."<br />".$row->group_id."\">".$row->group_id."</span></td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modify('ingroup','".$row->group_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverIngroupID\" title=\"".lang('go_MODIFYINGROUP')."<br />".$row->group_id."\">".str_replace("-","&#150;",$row->group_name)."</span></td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;{$row->queue_priority}</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;$active</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;{$row->call_time_id}</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modify('ingroup','".$row->group_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"".lang('go_MODIFYINGROUP')."<br />".$row->group_id."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td>";
		if ($row->group_id=="AGENTDIRECT") {
			echo "<td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><img src=\"{$base}img/delete.png\" style=\"width:13px;\" class=\"grayedout toolTip\" title=\"".lang('go_CannotdeleteAGENTDIRECT').".\" /></td>";
		} else {
			echo "<td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delIngroup('".$row->group_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"".lang('go_DELETEINGROUP')."<br />".$row->group_id."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td>";
		}
		echo "<td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"modify('ingroup','".$row->group_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"".lang('go_VIEWINFOFORINGROUP')."<br />".$row->group_id."\"><img src=\"{$base}img/status_display_i.png\" style=\"cursor:pointer;width:12px;\" /></span></td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delIngroup[]\" value=\"".$row->group_id."\" /></td>\n";
		echo "</tr>\n";
	}
?>
        <tr>
            <td colspan="9"><?=$ingpagelinks['info'] ?><?=$ingpagelinks['links'] ?></td>
        </tr>
	</tbody>
</table>
</div>

<div id="showDIDs_div" class="hideSpan" align="center">
<table id="DIDTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;">
	<thead>
        <tr style="font-weight:bold;">
            <th style="width:16%">&nbsp;&nbsp;<?php echo lang('goInbound_phoneNumbers'); ?></th>
            <th>&nbsp;&nbsp;<?php echo lang('goInbound_description'); ?></th>
            <th>&nbsp;&nbsp;<?php echo lang('goInbound_status'); ?></th>
            <th>&nbsp;&nbsp;<?php echo lang('goInbound_route'); ?></th>
            <th style="width:6%;text-align:center;" colspan="3" nowrap><span style="cursor:pointer;" id="selectDIDAction">&nbsp;<?php echo lang('goInbound_action'); ?> &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
            <th style="width:2%;text-align:center;"><input type="checkbox" id="selectAllDIDs" /></th>
        </tr>
    </thead>
    <tbody>
	<?php
	$x=0;
	foreach ($getdids as $row)
	{
		if ($x==0) {
			$bgcolor = "#E0F8E0";
			$x=1;
		} else {
			$bgcolor = "#EFFBEF";
			$x=0;
		}

		if ($row->did_active == 'Y')
		{
			$active = '<span style="color:green;font-weight:bold;">ACTIVE</span>';
		} else {
			$active = '<span style="color:#F00;font-weight:bold;">INACTIVE</span>';
		}
		
		echo "<tr style=\"background-color:$bgcolor;\">\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modify('did','".$row->did_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverIngroupID\" title=\"".lang('goInbound_modifyDID')."<br />".$row->did_pattern."\">".$row->did_pattern."</span>&nbsp;&nbsp;</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" nowrap>&nbsp;&nbsp;<span onclick=\"modify('did',".$row->did_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverIngroupID\" title=\"".lang('goInbound_modifyDID')."<br />".$row->did_pattern."\">".str_replace("-","&#150;",$row->did_description)."</span>&nbsp;&nbsp;</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;$active&nbsp;&nbsp;</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;{$row->did_route}&nbsp;&nbsp;</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modify('did','".$row->did_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"".lang('goInbound_modifyDID')."<br />".$row->did_pattern."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delDID('".$row->did_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"".lang('goInbound_deleteDID')."<br />".$row->did_pattern."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><img src=\"{$base}img/status_display_i.png\" class=\"grayedout\" style=\"width:13px;\" /></td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delDID[]\" value=\"".$row->did_id."\" /></td>\n";
		echo "</tr>\n";
	}
        ?>
        <tr>
            <td colspan="8"><?=$didpagelinks['info'] ?><?=$didpagelinks['links'] ?></td>
        </tr>
    </tbody>
</table>
</div>

<div id="showIVRs_div" class="hideSpan" align="center">
<table id="IVRTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;">
	<thead>
        <tr style="font-weight:bold;">
            <th style="width:12%">&nbsp;&nbsp;<? echo strtoupper($this->lang->line('go_menu_id')); ?></th>
            <th style="width:20%">&nbsp;&nbsp;<? echo $this->lang->line('go_descriptions'); ?></th>
            <th>&nbsp;&nbsp;<? echo $this->lang->line('go_prompt'); ?></th>
            <th>&nbsp;&nbsp;<? echo $this->lang->line('go_timeout'); ?></th>
            <th style="width:6%;text-align:center;" colspan="3" nowrap><span style="cursor:pointer;" id="selectIVRAction">&nbsp;<? echo $this->lang->line('go_action'); ?> &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
            <th style="width:2%;text-align:center;"><input type="checkbox" id="selectAllIVRs" /></th>
        </tr>
    </thead>
    <tbody>
	<?php
	$x=0;
	foreach ($getallcallmenus as $row)
	{
		if ($x==0) {
			$bgcolor = "#E0F8E0";
			$x=1;
		} else {
			$bgcolor = "#EFFBEF";
			$x=0;
		}
					
		echo "<tr style=\"background-color:$bgcolor;\">\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<span onclick=\"modify('ivr','".$row->menu_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"{$this->lang->line('go_modify_ivr')}<br />".$row->menu_id."\">".$row->menu_id."</span>&nbsp;&nbsp;</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" nowrap>&nbsp;&nbsp;<span onclick=\"modify('ivr','".$row->menu_id."')\" style=\"cursor:pointer;\" class=\"toolTip hoverCampID\" title=\"{$this->lang->line('go_modify_ivr')}<br />".$row->menu_id."\">".str_replace("-","&#150;",$row->menu_name)."</span>&nbsp;&nbsp;</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;{$row->menu_prompt}&nbsp;&nbsp;</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$row->menu_timeout."&nbsp;&nbsp;</td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><span onclick=\"modify('ivr','".$row->menu_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"{$this->lang->line('go_modify_ivr')}<br />".$row->menu_id."\"><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><span onclick=\"delIVR('".$row->menu_id."')\" style=\"cursor:pointer;\" class=\"toolTip\" title=\"{$this->lang->line('go_delete_ivr')}<br />".$row->menu_id."\"><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></span></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><img src=\"{$base}img/status_display_i_grayed.png\" style=\"cursor:default;width:12px;\" /></td>\n";
		echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><input type=\"checkbox\" id=\"delIVR[]\" value=\"".$row->menu_id."\" /></td>\n";
		echo "</tr>\n";
	}
	?>
        <tr>
            <td colspan="8"><?=$ivrpagelinks['info'] ?><?=$ivrpagelinks['links'] ?></td>
        </tr>
    </tbody>
</table>
</div>

<div id="table_pagelinks" style="padding-top:10px;text-align: center;"><span style="float: left"><?=$pagelinks['links'] ?></span><span style="float: right"><?=$pagelinks['info'] ?></span></div>
