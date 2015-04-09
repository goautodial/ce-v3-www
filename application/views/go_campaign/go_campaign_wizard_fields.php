<?php
############################################################################################
####  Name:             go_campaign_wizard_fields.php                                   ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();

if ($final != 'final')
{
	echo "<div align=\"center\" style=\"font-weight:bold;\"><br />"lang('go_Processing') "$delim_name file...<br /><br/>"lang('go_LISTIDFORTHISFILE') ": $list_id<br /><br />"lang('go_COUNTRYCODEFORTHISFILE')": $phone_code<br /><br /></div>";
	echo "<table style=\"width:100%;\">\n";
	echo "$columns";
	echo "</table>\n";
	echo "<input type=\"hidden\" id=\"file_name\" value=\"$file_name\" /><input type=\"hidden\" id=\"file_ext\" value=\"$file_ext\" />\n";
}
else
{
?>
<br />
<div align="center" style="font-wieght:bold;font-size:16px;"><? echo lang('go_LeadFileSuccessfullyLoaded'); ?>.</div>
<br />
<table border=0 width=100% cellpadding=10 cellspacing=0 valign=top><tr><th colspan=2><font face="arial, helvetica" size=3><? echo lang('go_Currentfilestatus'); ?>:</font></th></tr><tr><td align=right width="50%"><font face="arial, helvetica" size=2><B><? echo lang('go_Good_'); ?></B></font></td><td align=left width="50%"><font face="arial, helvetica" size=2><B><?php echo $good; ?></B></font></td></tr><tr><td align=right><font face="arial, helvetica" size=2><B><? echo lang('go_Bad_'); ?></B></font></td><td align=left><font face="arial, helvetica" size=2><B><?php echo $bad; ?></B></font></td></tr><tr><td align=right><font face="arial, helvetica" size=2><B><? echo lang('go_Total_'); ?></B></font></td><td align=left><font face="arial, helvetica" size=2><B><?php echo $total; ?></B></font></td></tr><tr><td align=right><font face="arial, helvetica" size=2><B> &nbsp; </B></font></td><td align=left><font face="arial, helvetica" size=2><B> &nbsp; </B></font></td></tr><tr><td align=right><font face="arial, helvetica" size=2><B><? echo lang('go_Duplicate_'); ?></B></font></td><td align=left><font face="arial, helvetica" size=2><B><?php echo $dup; ?></B></font></td></tr><tr style="display: none;"><td align=right><font face="arial, helvetica" size=2><B><? echo lang('go_PostalMatch_'); ?></B></font></td><td align=left><font face="arial, helvetica" size=2><B><?php echo $post; ?></B></font></td></tr></table>

<table border=0 width=100% cellpadding=10 cellspacing=0 valign=top><tr><td style="text-align:center;width:50%;"><span id="showDivResult" style="display:none;"><br /><?=form_checkbox('showResult','show',FALSE,'id="showResult"'); ?> <small style="color: #ff0000;"><? echo lang('go_Checkthisboxifyouwanttoshowtheresult'); ?></small></span></td></tr></table>

<table border=0 width=100% cellpadding=10 cellspacing=0 valign=top id="resultHTML" style="display:none;"><tr><td style="text-align:center;"><?=$resultHTML?></td></tr></table>

<script>
$(function()
{
	$('#processLeads').hide();
	$('#showDivResult').show();
	$('#showResult').prop('checked', false);
	$('#showResult').click(function() {
		$('#resultHTML').toggle(this.checked);
	});
});
</script>
<?php
}
?>