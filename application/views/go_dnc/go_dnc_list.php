<?php
############################################################################################
####  Name:             go_dnc_list.php                                                 ####
####  Type:             ci views                                                        ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Christopher Lomuntad <chris@goautodial.com>   ####
####  License:          AGPLv2                                                          ####
############################################################################################
############################################################################################
#### WARNING/NOTICE: PRODUCTION                                                         ####
#### Current SVN Production                                                             ####
############################################################################################
$base = base_url();
?>
<script>
$(function()
{
	<?php
	if (count($dnc_list) > 0)
	{
	?>
	// Table Sorter
	$("#DNCTable").tablesorter({sortList:[[0,0],[1,0]], headers: { 2: { sorter: false}, 3: {sorter: false} }, widgets: ['zebra']});
	<?php
	}
	?>

	// Pagination
	//$('#DNCTable').tablePagination({rowsPerPage: 15, optionsForRows: [15,25,50,100,"ALL"]});

	var toggleAction = $('#go_dnc_menu').css('display');
	$('#selectActionDNC').click(function()
	{
		if (toggleAction == 'none')
		{
			var position = $(this).offset();
			$('#go_dnc_menu').css('left',position.left-98);
			$('#go_dnc_menu').css('top',position.top-118);
			$('#go_dnc_menu').slideDown('fast');
			toggleAction = $('#go_dnc_menu').css('display');
		}
		else
		{
			$('#go_dnc_menu').slideUp('fast');
			$('#go_dnc_menu').hide();
			toggleAction = $('#go_dnc_menu').css('display');
		}
	});

	$('#selectAllDNC').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delDNC[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delDNC[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).removeAttr('checked');
				}
			});
		}
	});

	$(document).mouseup(function (e)
	{
		var content = $('#go_dnc_menu');
		if (content.has(e.target).length === 0 && (e.target.id != 'selectAction'))
		{
			content.slideUp('fast');
			content.hide();
			toggleAction = $('#go_dnc_menu').css('display');
		}
	});
});

function changePage(pagenum)
{
	var search = $("#search_dnc").val();
	search = search.replace(/\s/g,"%20");
	$("#dnc_placeholder").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#dnc_placeholder').load('<? echo $base; ?>index.php/go_dnc_ce/go_search_dnc/'+pagenum+'/'+search);
}
</script>
<br />
<table id="DNCTable" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:100%;margin-left:auto;margin-right:auto;">
	<thead>
		<tr style="font-weight:bold;">
			<th class="thheader" style="width:25%;text-align: left;">&nbsp;&nbsp;PHONE NUMBER</th>
			<th class="thheader" style="width:67%;text-align: left;">&nbsp;&nbsp;CAMPAIGN</th>
			<th class="thheader" colspan="3" style="width:5%;text-align:center;" nowrap><span style="cursor:pointer;" id="selectActionDNC">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
			<th style="width:35px;text-align:center;"><input type="checkbox" id="selectAllDNC" /></th>
		</tr>
	</thead>
	<tbody>
	<?php
	if (count($dnc_list) > 0)
	{
		if (!$dnc_list['start'])
		{
			foreach ($dnc_list as $dnc)
			{
				if ($x==0) {
					$bgcolor = "#E0F8E0";
					$x=1;
				} else {
					$bgcolor = "#EFFBEF";
					$x=0;
				}
				
				if (strlen($dnc->campaign_id)>0)
					$camp_desc = "{$dnc->campaign_id} - {$dnc->campaign_name}";
				else
					$camp_desc = "INTERNAL DNC LIST";
			?>
				<tr style="background-color:<?php echo $bgcolor; ?>;"> 
				<!--<tr>-->
					<td style="border-top:#D0D0D0 dashed 1px;">&nbsp;&nbsp;<?php echo $dnc->phone_number; ?></td>
					<td style="border-top:#D0D0D0 dashed 1px;">&nbsp;&nbsp;<?php echo $camp_desc; ?></td>
					<td style="border-top:#D0D0D0 dashed 1px;text-align:center;"><img src="<? echo $base; ?>img/edit.png" style="cursor:default;width:12px;" class="desaturate" /></td><td style="border-top:#D0D0D0 dashed 1px;text-align:center;"><span onclick="delDNC('<?php echo "{$dnc->phone_number}-{$dnc->campaign_id}"; ?>')" style="cursor:pointer;" class="toolTip" title="DELETE DNC NUMBER <?php echo "\n{$dnc->phone_number}"; ?>"><img src="<? echo $base; ?>img/delete.png" style="cursor:pointer;width:12px;" /></span></td><td style="border-top:#D0D0D0 dashed 1px;text-align:center;"><img src="<? echo $base; ?>img/status_display_i.png" style="cursor:default;width:12px;" class="desaturate" /></td>
					<td style="border-top:#D0D0D0 dashed 1px;width:35px;text-align:center;"><input type="checkbox" id="delDNC[]" value="<?php echo "{$dnc->phone_number}-{$dnc->campaign_id}"; ?>" /></td>
				</tr>
			<?php
			}
		} else {
			echo "<tr style=\"background-color:#E0F8E0\"><td colspan=\"6\" style=\"border-top:#D0D0D0 dashed 1px;text-align:center;font-weight:bold;color:#f00;\">Type the number at the top right search box.</td></tr>";
		}
	} else {
		echo "<tr style=\"background-color:#E0F8E0\"><td colspan=\"6\" style=\"border-top:#D0D0D0 dashed 1px;text-align:center;font-weight:bold;color:#f00;\">No record(s) found.</td></tr>";
	}
	?>
	</tbody>
</table>
<?=$paginate['info']; ?>
<?=$paginate['links']; ?>