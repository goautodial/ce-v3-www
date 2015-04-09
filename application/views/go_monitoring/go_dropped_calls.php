<?php
########################################################################################################
####  Name:             	go_dropped_calls.php                                                ####
####  Type:             	ci views - administrator					    ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Christopher P. Lomuntad                                             ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$base = base_url();
?>
<style>
.odd {background-color: #EFFBEF}
.even {background-color: #E0F8E0}
</style>
<script>
$(function()
{
    $('#droppedCallsTableOut').tablePagination();
    $("#droppedCallsTableOut").tablesorter({widgets: ['zebra']}); 
    $('#droppedCallsTableIn').tablePagination();
    $("#droppedCallsTableIn").tablesorter({widgets: ['zebra']});
});
</script>
<div align="center" style="font-weight:bold; color:#333; font-size:16px;"><? echo lang('go_ACTIVECAMPAIGNSTATISTICS'); ?></div>
<br />
<table id="droppedCallsTableOut" class="tablesorter" cellpadding="1" cellspacing="0" border="0" style="width:100%">
<?
echo $dropped_calls_table_out;
?>
</table>
<br style="font-size:20px;" />
<div align="center" style="font-weight:bold; color:#333; font-size:16px;"><? echo lang('go_ACTIVEINGROUPSTATISTICS'); ?></div>
<br />
<table id="droppedCallsTableIn" class="tablesorter" cellpadding="1" cellspacing="0" border="0" style="width:100%">
<?
echo $dropped_calls_table_in;
?>
</table>
<br style="font-size:5px;" />
<!--* this doesn't include stats on in-groups and agent to agent transfers-->
<br />
