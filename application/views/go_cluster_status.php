<?php
########################################################################################################
####  Name:             	go_cluster_status.php   	                        	    ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Christopher Lomuntad                                                ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$base = base_url();
$web_u_time = date('U');
?>
<table border=0 cellpadding=0 cellspacing=0 style="width: 100%">
	<tr style="font-weight:bold;color:#777777;">
		<td style="white-space: nowrap; line-height: 20px;">&nbsp;SERVER ID&nbsp;</td>
		<td style="white-space: nowrap;display:none;">&nbsp;DESCRIPTION&nbsp;</td>
		<td style="white-space: nowrap;">&nbsp;SERVER IP&nbsp;</td>
		<td style="white-space: nowrap;">&nbsp;STATUS&nbsp;</td>
		<td style="white-space: nowrap;">&nbsp;LOAD&nbsp;</td>
		<td style="white-space: nowrap; text-align: center;">&nbsp;CHANNELS&nbsp;</td>
		<td style="white-space: nowrap;">&nbsp;DISK&nbsp;</td>
		<td style="white-space: nowrap;">&nbsp;TIME&nbsp;</td>
		<td style="white-space: nowrap;display:none;">VERSION</td>
	</tr>
<?php
foreach ($cluster_status as $key => $cluster)
{
	$cpu = (100 - $cluster['cpu_idle_percent']);
	$disk_ary = explode('|',$cluster['disk_usage']);
	$disk_ary_ct = count($disk_ary);
	$k=0;
	while ($k < $disk_ary_ct)
	{
		$disk_ary[$k] = preg_replace("/^\d* /","",$disk_ary[$k]);
		if ($k<1) {$disk = "$disk_ary[$k]";}
		else
		{
			if ($disk_ary[$k] > $disk) {$disk = "$disk_ary[$k]";}
		}
		$k++;
	}
	
	$s_time ='&nbsp;';
	$s_ver ='&nbsp;';
	$u_time =$web_u_time;
	$query = $this->go_dashboard->db->query("SELECT last_update as s_time,UNIX_TIMESTAMP(last_update) as u_time from server_updater where server_ip='{$cluster['server_ip']}';");
	if ($query->num_rows() > 0)
	{
		$s_time = $query->row()->s_time;
		$u_time = $query->row()->u_time + 5;
	} else {
		$s_time = "TIME SYNC";
		$u_time = 0;
	}
	
	$query = $this->go_dashboard->db->query("SELECT svn_revision from servers where server_ip='{$cluster['server_ip']}';");
	if ($query->num_rows() > 0)
	{
		$s_ver = $query->row()->svn_revision;
	}
	
	$status = ($cluster['active'] == 'Y') ? "<span style='color:green;'>ACTIVE</span>" : "<span style='color:#FF0000;'>INACTIVE</span>";
	$colors = ($key%2) ? "#EFFBEF;color:#777777" : "#E0F8E0;color:#777777";
	if ($web_u_time > $u_time) {
		$colors = "#FA5858;color:white";
		if ($cluster['active'] == 'N') {
			$status = "<span style='color:#FFFFFF;'>INACTIVE</span>";
		}
	}
	
	echo "<tr style='background-color:{$colors};cursor:default; line-height: 20px;'>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;' title='{$cluster['server_description']}' class='toolTip'>&nbsp;<a style='cursor:pointer;' onclick='modifyServer(\"{$cluster['server_id']}\",\"{$cluster['server_ip']}\");'>{$cluster['server_id']}</a>&nbsp;</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;display:none;' title='{$cluster['server_description']}' class='toolTip'>&nbsp;{$cluster['server_description']}&nbsp;</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;' title='{$cluster['server_description']}' class='toolTip'>&nbsp;{$cluster['server_ip']}&nbsp;</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;font-weight:bold;' title='{$cluster['server_description']}' class='toolTip'>&nbsp;{$status}&nbsp;</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;' title='{$cluster['server_description']}' class='toolTip'>&nbsp;{$cluster['sysload']} - {$cpu}%&nbsp;</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;text-align:center;' title='{$cluster['server_description']}' class='toolTip'>&nbsp;{$cluster['channels_total']}&nbsp;</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;text-align:right;' title='{$cluster['server_description']}' class='toolTip'>&nbsp;{$disk}%&nbsp;&nbsp;</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;' title='{$cluster['server_description']}' class='toolTip'>&nbsp;$s_time&nbsp;</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;text-align:center;display:none;' title='{$cluster['server_description']}' class='toolTip'>&nbsp;$s_ver&nbsp;</td>";
	echo "</tr>";
}
	$key++;
	$colors = ($key%2) ? "#EFFBEF" : "#E0F8E0";
	echo "<tr style='background-color:{$colors};cursor:default; line-height: 20px; color: #777777;'>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;' colspan='2'>&nbsp;</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;font-weight:bold;' colspan='2'>&nbsp;PHP Time&nbsp;</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;' colspan='2'>&nbsp;</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;".date("Y-m-d H:i:s")."&nbsp;</td>";
	echo "</tr>";
	
	$query = $this->go_dashboard->db->query("SELECT NOW() as dbtime, UNIX_TIMESTAMP(NOW()) as u_dbtime;");
	$dbtime = $query->row()->dbtime;
	$u_dbtime = $query->row()->u_dbtime + 5;
	$key++;
	$colors = ($key%2) ? "#EFFBEF" : "#E0F8E0";
	$fcolor = "#777777";
	if ($web_u_time > $u_dbtime) {
		$colors = "#FF0000;";
		$fcolor = "white";
	}
	echo "<tr style='background-color:{$colors};cursor:default; line-height: 20px; color: {$fcolor};'>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;' colspan='2'>&nbsp;</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;font-weight:bold;' colspan='2'>&nbsp;DB Time&nbsp;</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;' colspan='2'>&nbsp;</td>";
	echo "<td style='border-top:#D0D0D0 dashed 1px;'>&nbsp;$dbtime&nbsp;</td>";
	echo "</tr>";
?>
</table>