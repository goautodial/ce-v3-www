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
    	<td style="text-align:right;font-weight:bold;" nowrap><? echo lang('go_CampaignID_'); ?></td><td>&nbsp;<?php echo $campaign_id; ?></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap><? echo lang('go_CampaignName_'); ?></td><td nowrap class="toolTip" title="<?php echo $campinfo->campaign_name; ?>" style="cursor:pointer;">&nbsp;<?php echo (strlen($campinfo->campaign_name) > 20) ? substr($campinfo->campaign_name,0,20) : $campinfo->campaign_name; ?></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap><? echo lang('go_CampaignDescription_'); ?></td><td nowrap class="toolTip" title="<?php echo $campinfo->campaign_description; ?>" style="cursor:pointer;">&nbsp;<?php echo (strlen($campinfo->campaign_description) > 20) ? substr($campinfo->campaign_description,0,20) : $campinfo->campaign_description; ?></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap><? echo lang('go_AllowInboundAndBlended_'); ?></td><td>&nbsp;<?php echo $campinfo->campaign_allow_inbound; ?></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap><? echo lang('go_DialMethod_'); ?></td><td>&nbsp;<?php echo str_replace(' ','-',ucwords(strtolower(str_replace('_',' ',$dial_method)))); ?></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap><? echo lang('go_AutoDialLevel_'); ?></td><td>&nbsp;<?php echo ucwords(strtolower($auto_dial_level)); echo (isset($auto_dial_level_adv)) ? " ($auto_dial_level_adv)" : ""; ?></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap><? echo lang('go_AnsweringMachineDetection_'); ?></td><td>&nbsp;<?php echo ucwords(strtolower($campaign_vdad_exten)); ?></td>
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
    	<td style="text-align:right;font-weight:bold;width:130px;" nowrap><? echo lang('go_CampaignID_'); ?></td><td>&nbsp;<?php echo $campaign_id; ?></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;" nowrap><? echo lang('go_CampaignName_'); ?></td><td nowrap class="toolTip" title="<?php echo $campaign_name; ?>" style="cursor:pointer;">&nbsp;<?php echo (strlen($campaign_name) > 28) ? substr($campaign_name,0,28) : $campaign_name; ?></td>
    </tr>
    <tr>
    	<td style="text-align:right;font-weight:bold;vertical-align:top;" nowrap><? echo lang('go_Dispositions'); ?>:</td><td style="vertical-align:top;">
    	<?php
    	foreach (explode(' ',trim($camp_status[$campaign_id])) as $status)
    	{
	    if (isset($status) && strlen($status)>0)
	    {
		echo "&nbsp;$status - $status_name[$status]<br />";
	    }
	    else
	    {
		echo "&nbsp; ".lang('go_NONE')."";
	    }
    	}
    	?>
	</td>
    </tr>
</table>
<?php
}
?>
