<?php
############################################################################################
####  Name:             go_reports_output.php                                           ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();
$dateNOW = date('Y-m-d');
?>

<script type="text/javascript">
//<![CDATA[
$(document).ready(function()
	{	
	// On page load
	var campaign_id = $('#select_campaign').html();
	var date_range = $('#widgetDate span').html().split(' to ');
	var from_date = date_range[0];
	var to_date = date_range[1];
	if (campaign_id == '<? echo $this->lang->line("go_sel_camp"); ?>') {
		campaign_id = null;
	}
	$('#reports_output_table').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/go_reports_output/<? echo $pagetitle; ?>/' + from_date + '/' + to_date + '/' + campaign_id + '/<? echo $request; ?>/').fadeIn("slow");

	});
//]]>
</script>

<br />
<table style="width:100%;">
	<tbody>
	<tr class="first">
			<td class="t bold" align="center">

<div id="reports_output_table">
</div>
            
            </td>
	</tr>
	</tbody>
</table>
