<?php
########################################################################################################
####  Name:             	go_dashboard_drop_percentage.php                                    ####
####  Type:             	ci views - administrator					    ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Christopher P. Lomuntad                                             ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$base = base_url();

$dropped_percentage = ( ($dropped_calls_today / $answered_calls_today) * 100);
$dropped_percentage = ($dropped_percentage > 0) ? round($dropped_percentage,2) : "0";
?>
<style>
.dropTD
{
    text-align: right;
}
.tdcon1 {
    width: 35px;
    position: relative;
}
.tdcon2 {
    float: right;
    overflow: visible;
    text-align: right;
}
</style>
<p class="sub">Dropped Call Percentage</p>
<table id="dropCallsTable">
	<tbody>
	<tr  class="first">

			<td class="o dropTD"><div class="tdcon1"><div class="tdcon2"><a class="toolTip" style="cursor:pointer;font-size:50px;" onclick="droppedCalls()" title="Click to see the list of campaign dropped percentage"><? echo $dropped_percentage; ?></a></div></div></td>
			<td class="t bold"><a class="toolTip" style="cursor:pointer" onclick="droppedCalls()" title="Click to see the list of campaign dropped percentage">% Dropped Percentage</a></td>


	</tr>
	<tr>


			<td class="c dropTD"><div class="tdcon1"><div class="tdcon2"><a class="toolTip" style="cursor:pointer" onclick="droppedCalls()" title="Click to see the list of campaign dropped calls"><? echo ($dropped_calls_today > 0) ? $dropped_calls_today : "0"; ?></a></div></div></td>
			<td class="t "><a class="toolTip" style="cursor:pointer" onclick="droppedCalls()" title="Click to see the list of campaign dropped calls">Dropped Calls</a></td>


	</tr>
	<tr>


			<td class="c dropTD"><div class="tdcon1"><div class="tdcon2"><a class="toolTip" style="cursor:pointer" onclick="droppedCalls()" title="Click to see the list of campaign answered calls"><? echo ($answered_calls_today > 0) ? $answered_calls_today : "0"; ?></a></div></div></td>
			<td class="t "><a class="toolTip" style="cursor:pointer" onclick="droppedCalls()" title="Click to see the list of campaign answered calls">Answered Calls</a></td>


	</tr>
	</tbody>
</table>

<!--<table>
	<tbody>
		<br>
		<br class="clear">
		<br class="clear">

		<tr>

		 <td class='dp'>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </td>
		 <td class='today_showmore'>
		 
		 <a id='today_showmore' class='today_showmore'>&nbsp;&raquo; Click here to show more... </a>
		 <a style='display: none' id='today_showmore' class='today_showmore'>&nbsp;&raquo; Click here to hide... </a>
		
		 </td>
		</tr>
	</tbody>
</table>-->