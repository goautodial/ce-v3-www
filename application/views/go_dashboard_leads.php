<?php
########################################################################################################
####  Name:             	go_dashboard_leads.php                     	    		    ####
####  Type:             	ci views - administrator					    ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
?>
<script>
$(function()
{
    $('.toolTip').tipTip();

	$('.disableLink').click(function(e)
	{
		e.preventDefault();
	});
});
</script>

<style>
.campTDs
{
    text-align: right;
}
.tdcon3 {
    width: 200px;
    position: relative;
}
.tdcon4 {
    float: right;
    overflow: visible;
    text-align: right;
}
</style>


<p class="sub">Leads Resources</p> 							
<table>
	<tbody>
	<tr class="first">
			<td class="b"><a class="cur_hand"><? echo $total_hopper_leads; ?></a></td>
			<td class="t bold"><a class="cur_hand">Leads in Hopper</a></td>
	</tr>
	<tr>
			<td class="c"><a class="cur_hand"><? echo $total_dialable_leads; ?></a></td>
			<td class="r"><a class="cur_hand">Dialable Leads</a></td>
	</tr>
	<tr>
			<td class="c"><a class="cur_hand"><? echo $total_leads; ?></a></td>
			<td class="r "><a class="cur_hand">Total Leads</a></td>
	</tr>
	</tbody>
</table>

<br /><br />
<p class="subs">Campaigns Resources</p>
<table id='cpresource' class='cpresource'>
	<tbody>
		<tr class="first"></tr>
<br><br><br>
	
	
	

      
	<?
	
	$i=0;
	foreach($go_hopper_leads_warning_less_h_queryval as $item):
	$i++;
	
	$camp = $item->campaign_id;
	
		if($i > 5) {
	echo "<tr id='trid' style='display: none'>\n";	
		}
		else{
	echo "<tr>\n";	

}
	if ($item->mycnt > 0) {
	echo "<td class='dp campTDs'><div class='tdcon3'><div class='tdcon4'><a style='cursor:pointer;' class='disableLink toolTip' onclick=\"modify('".$item->campaign_id."')\" title='Campaign ID: $camp'>".$item->campaign_name."</a></div></div></td>";
	echo "<td class='r'>has <b style='font-size: 16px;'><</b> 100 leads</a></td>";
	}
	else{
	echo "<td class='dr campTDs'><div class='tdcon3'><div class='tdcon4'><a style='cursor:pointer' class='disableLink toolTip' onclick=\"modify('".$item->campaign_id."')\" title='Campaign ID: $camp'>".$item->campaign_name."</a></div></div></td>";
	echo "<td class='r'>has no leads</a></td>";	
	}
	echo "</tr>\n";
	
	

	
	endforeach;
	


	
	

if ($go_hopper_leads_warning_less_h_querycount > 0) {
?>
<table>
	<tbody>
		<br>

		<tr>

		 <td class='dp'>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
		 <td class='showhide'>
		 <a id='showmemore' class='showmore' onclick='javascript:showmemore()'>&nbsp;&raquo; Click here to show more... </a>
		 <a style='display: none' id='hidemore' class='hidemore'>&nbsp;&raquo; Click here to hide... </a>

		 </td>
		</tr>
	</tbody>
</table>
<?
}
?>

<script>

    $("#showmore").click(function () {
    $("#cpresource #trid").show("slow");
    $("#hidemore").show("slow");
    $("#showmore").hide("slow");
    
    });
    
    
    $("#hidemore").click(function () {
    $("#cpresource #trid").hide("slow");
    $("#showmore").show("slow");
    $("#hidemore").hide("slow");
    
    });
    
    
	function showmemore()
	{
		$('#overlay').fadeIn('fast');
		$('#box').css({'width': '770px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
		$('#box').animate({
			top: "70px",
			left: "14%",
			right: "14%"
		}, 500);
		$("html, body").animate({ scrollTop: 0 }, 500);
		$("#overlayContent").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
		$('#overlayContent').fadeOut("slow").load('<?php echo $base; ?>index.php/go_site/show_me_more/campaign/').fadeIn("slow");
	}
    </script>

	<?
	
	//if ($tot_hopper_leads_warning<=5){	
	//	$i=0;
	//	foreach($hopper_leads_warning_queryval as $item):
	//	$camp = $item->campaign_id;
	//	echo "<tr>\n";
	//	echo "<td class='dp'><a href=''>$camp</a></td>";
	//	echo "<td class='r'>has <b style='font-size: 16px;'><</b> 100 leads</a></td>";
	//	echo "</tr>\n";				
	//	$i++;
	//	endforeach;
	//	
	//	$o=0;
	//	foreach($hopper_leads_warning_zero_queryval as $itemi):
	//	$campo = $itemi->campaign_id;
	//	echo "<tr>\n";
	//	echo "<td class='dr'><a href=''>$campo</a></td>";
	//	echo "<td class='r'>has no leads</a></td>";
	//	echo "</tr>\n";				
	//	$i++;
	//	endforeach;	
	//}
	//else
	//{
	//	if ($hopper_leads_warning_querycount==5)
	//	{
	//	$i=0;
	//	foreach($hopper_leads_warning_queryval as $item):
	//	$camp = $item->campaign_id;
	//	echo "<tr>\n";
	//	echo "<td class='dp'><a href=''>$camp</a></td>";
	//	echo "<td class='r'>has <b style='font-size: 16px;'><</b> 100 leads</a></td>";
	//	echo "</tr>\n";				
	//	$i++;
	//	endforeach;
	//	}
	//	else
	//	{
	//	
	//	
	//	$overalllimit=6;
	//	$limit= $overalllimit - $hopper_leads_warning_querycount;		
	//	
	//	$i=0;
	//	foreach($hopper_leads_warning_queryval as $item):
	//	$camp = $item->campaign_id;
	//	echo "<tr>\n";
	//	echo "<td class='dp'><a href=''>$camp</a></td>";
	//	echo "<td class='r'>has <b style='font-size: 16px;'><</b> 100 leads</a></td>";
	//	echo "</tr>\n";				
	//	$i++;
	//	endforeach;
	//	
	//	$o=0;
	//	foreach($hopper_leads_warning_zero_queryval as $itemi):
	//	$o++;
	//	$campo = $itemi->campaign_id;
	//	echo "<tr>\n";
	//	echo "<td class='dr'><a href=''>$campo</a></td>";
	//	echo "<td class='r'>has no leads</a></td>";
	//	echo "</tr>\n";	
	//	if($o >= $limit) {
	//	break;
	//	} 
	//	endforeach;
	//	
	//	
	//	
	//	}
	//	
	//	
	//}
	?>
	</tbody>
</table>