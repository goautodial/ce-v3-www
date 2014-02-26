<?php
############################################################################################
####  Name:             go_campaign_view.php                                            ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();
?>
<script>
$(function()
{
    $('.toolTip').tipTip();
});
</script>
<?php
if ($type=='info')
{
    if ($campinfo->campaign_vdad_exten=="8369" || $campinfo->campaign_vdad_exten=="8375") {
	    $campaign_vdad_exten ="ON";
    } else {
	    $campaign_vdad_exten ="OFF";
    }
?>
<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:95%; color:#000; margin-left:auto; margin-right:auto;">
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>Campaign ID:</td><td>&nbsp;<?php echo $campaign_id; ?></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>Campaign Name:</td><td nowrap class="toolTip" title="<?php echo $campinfo->campaign_name; ?>" style="cursor:pointer;">&nbsp;<?php echo (strlen($campinfo->campaign_name) > 20) ? substr($campinfo->campaign_name,0,20) : $campinfo->campaign_name; ?></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>Campaign Description:</td><td nowrap class="toolTip" title="<?php echo $campinfo->campaign_description; ?>" style="cursor:pointer;">&nbsp;<?php echo (strlen($campinfo->campaign_description) > 20) ? substr($campinfo->campaign_description,0,20) : $campinfo->campaign_description; ?></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>Allow Inbound and Blended:</td><td>&nbsp;<?php echo $campinfo->campaign_allow_inbound; ?></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>Dial Method:</td><td>&nbsp;<?php echo str_replace(' ','-',ucwords(strtolower(str_replace('_',' ',$dial_method)))); ?></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>Auto Dial Level:</td><td>&nbsp;<?php echo ucwords(strtolower($auto_dial_level)); echo (isset($auto_dial_level_adv)) ? " ($auto_dial_level_adv)" : ""; ?></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>Answering Machine Detection:</td><td>&nbsp;<?php echo ucwords(strtolower($campaign_vdad_exten)); ?></td>
    </tr>
</table>
<?php
}
else
{
    foreach ($campaign['list'] as $camp)
    {
	if ($camp->campaign_id == $campaign_id)
	{
	    $campaign_name = $camp->campaign_name;
	    break;
	}
    }
?>
<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:95%; color:#000; margin-left:auto; margin-right:auto;">
    <tr>
    	<td style="text-align:right;font-weight:bold;width:130px;" nowrap>Campaign ID:</td><td>&nbsp;<?php echo $campaign_id; ?></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap>Campaign Name:</td><td nowrap class="toolTip" title="<?php echo $campaign_name; ?>" style="cursor:pointer;">&nbsp;<?php echo (strlen($campaign_name) > 28) ? substr($campaign_name,0,28) : $campaign_name; ?></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;vertical-align:top;" nowrap>Disposition(s):</td><td style="vertical-align:top;">
    	<?php
    	foreach (explode(' ',trim($camp_status[$campaign_id])) as $status)
    	{
	    if (isset($status) && strlen($status)>0)
	    {
		echo "&nbsp;$status - $status_name[$status]<br />";
	    }
	    else
	    {
		echo "&nbsp;NONE";
	    }
    	}
    	?>
	</td>
    </tr>
</table>
<?php
}
?>