<?php
############################################################################################
####  Name:             go_carriers_list.php                                            ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();
?>
<style>
 
.sippy-info{
   position:absolute;
   z-index:950;
   top:-600px;
    border-width:1.5px;
    border-style:solid;
    border-color:#dfdfdf;
    -moz-border-radius:6px;
    -khtml-border-radius:6px;
    -webkit-border-radius:6px;
    border-radius:6px;
    padding:20px;
   background-color:#fff;
   margin:auto;
   left:14%;
   right:14%;
   color: #7f7f7f;
   width:250px;
    
}

</style>
<script>
$(function()
{
	$('#selectAll').click(function()
	{
		if ($(this).is(':checked'))
		{
			$('input:checkbox[id="delCarrier[]"]').each(function()
			{
				if ($(this).is(':visible'))
				{
					$(this).attr('checked',true);
				}
			});
		}
		else
		{
			$('input:checkbox[id="delCarrier[]"]').each(function()
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
		var selectedCarriers = [];
		$('input:checkbox[id="delCarrier[]"]:checked').each(function()
		{
			selectedCarriers.push($(this).val());
		});

		$('#go_action_menu').slideUp('fast');
		$('#go_action_menu').hide();
		toggleAction = $('#go_action_menu').css('display');

		var action = $(this).attr('id');
		if (selectedCarriers.length<1)
		{
			alert('Please select a Carrier.');
		}
		else
		{
			var s = '';
			if (selectedCarriers.length>1)
				s = 's';

			if (action == 'delete')
			{
				var what = confirm('Are you sure you want to delete the selected Carrier'+s+'?');
				if (what)
				{
					$('#table_container').load('<? echo $base; ?>index.php/go_carriers_ce/go_update_carrier_list/'+action+'/'+selectedCarriers+'/');
                                        setTimeout('location.reload();',3000);
				}
			}
			else
			{
				$('#table_container').load('<? echo $base; ?>index.php/go_carriers_ce/go_update_carrier_list/'+action+'/'+selectedCarriers+'/');
			}
		}
	});

   if (<?php echo count($carriers); ?> > 0 || $('#search_list').val().length > 0)
   {
      // Pagination
      //$('#mainTable').tablePagination();

      // Table Sorter
      $("#mainTable").tablesorter({sortList:[[0,0]], headers: { 7: { sorter: false}, 8: {sorter: false} }});
   }
   else
   {
      addNewCarriers();
   }
   
   // Tool Tip
   $(".toolTip").tipTip();
   
   $("#closebox").click(function(){
      $(".sippy-info").animate({top:"-6000px"});
      $("#overlay").fadeOut('fast');
   });

});

function sippyInfo(){
$("#overlay").fadeIn('fast');
$(".sippy-info").animate({top:60});

}


</script>
<table id="mainTable" class="tablesorter" style="width:100%;" cellpadding=0 cellspacing=0>
	<thead>
		<tr style="font-weight:bold;">
			<th style="white-space:nowrap">&nbsp;CARRIER ID</th>
			<th style="white-space:nowrap">&nbsp;CARRIER NAME</th>
			<th style="white-space:nowrap">&nbsp;SERVER IP</th>
			<th>&nbsp;PROTOCOL</th>
			<th>&nbsp;REGISTRATION</th>
			<th>&nbsp;STATUS</th>
			<th>&nbsp;GROUP</th>
			<th style="width:6%;text-align:center;" nowrap><span style="cursor:pointer;" id="selectAction">&nbsp;ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" />&nbsp;</span></th>
			<th style="width:2%;text-align:center;"><input type="checkbox" id="selectAll" /></th>
		</tr>
	</thead>
	<tbody>
<?php
if (count($carriers) > 0) {
   $x = 0;
   foreach ($carriers as $list)
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
	   $ugroup = ($list->user_group=="---ALL---") ? "ALL USER GROUPS" : $list->user_group;
	   
	   if (strlen($list->registration_string) > 40)
	   {
		   $registration = substr($list->registration_string,0,40);
	   } else {
		   $registration = $list->registration_string;
	   }
	   
	   echo "<tr style='background-color:$bgcolor;'>";
	   echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;<a onclick=\"modify('{$list->carrier_id}')\">{$list->carrier_id}</a></td>";
	   echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->carrier_name}</td>";
	   echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->server_ip}</td>";
	   echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;{$list->protocol}</td>";
	   echo "<td style='border-top:#D0D0D0 dashed 1px;cursor:pointer;' class='toolTip' title='{$list->registration_string}'>&nbsp;$registration</td>";
	   echo "<td style='border-top:#D0D0D0 dashed 1px;color:$acolor;font-weight:bold;width:10%;'>&nbsp;$status</td>";
	   echo "<td style='border-top:#D0D0D0 dashed 1px;white-space:nowrap;width:6%;'>&nbsp;$ugroup&nbsp;&nbsp;&nbsp;</td>";
	   echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><span onclick=\"modify('{$list->carrier_id}')\" style='cursor:pointer;margin:5px;' class='toolTip' title='MODIFY CARRIER<br />{$list->carrier_id}'><img src='{$base}img/edit.png' style='cursor:pointer;width:12px;' /></span>
		 <span onclick=\"delCarrier('{$list->carrier_id}')\" style='cursor:pointer;margin:5px;' class='toolTip' title='DELETE CARRIER<br />{$list->carrier_id}'><img src='{$base}img/delete.png' style='cursor:pointer;width:12px;' /></span>
		 <span style='margin:5px;'><img src='".(preg_match('/'.$goautodial[0]->carrier_id.'/',$list->carrier_id)?"{$base}img/status_display_i.png":"{$base}img/status_display_i_grayed.png")."' style='width:12px;cursor:pointer;' ".(preg_match('/'.$goautodial[0]->carrier_id.'/',$list->carrier_id)?"onclick='sippyInfo();'":"")." /></span></td>\n";
	   echo "<td style='border-top:#D0D0D0 dashed 1px;' align='center'><input type='checkbox' id='delCarrier[]' value='{$list->carrier_id}' /></td>\n";
	   echo "</tr>";
   }
} else {
	echo "<tr style=\"background-color:#E0F8E0;\"><td style=\"border-top:#D0D0D0 dashed 1px;font-weight:bold;color:#FF0000;text-align:center;\" colspan=\"8\">No record(s) found.</td></tr>\n";
}
?>
	</tbody>
</table>
<?=$pagelinks['info'] ?>
<?=$pagelinks['links'] ?>
<div class="sippy-info">
    <a id="closebox" class="toolTip" title="CLOSE"></a>
    <table width="100%">
        <tr>
           <td style="width:40%;text-align:center;">Web Login:</td><td><?=$goautodial[0]->username?></td>
        </tr>
        <tr>
           <td style="width:40%;text-align:center;">Web password:</td><td><?=$goautodial[0]->web_password?></td>
        </tr>
        <tr>
           <td style="width:40%;text-align:center;">SIP Login:</td><td><?=$goautodial[0]->authname?></td>
        </tr>
        <tr>
           <td style="width:40%;text-align:center;">SIP Password:</td><td><?=$goautodial[0]->voip_password?></td>
        </tr>
        <tr>
           <td style="width:40%;text-align:center;">VoIP Portal:</td><td><a href="https://dal.justgovoip.com/account.php" target="new" style="color:#7A9E22;">Login Here</a></td>
        </tr>
    </table>
</div>
