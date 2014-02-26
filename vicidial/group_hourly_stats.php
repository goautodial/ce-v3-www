<?php
# group_hourly_stats.php
# 
# Copyright (C) 2013  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES
#
# 60620-1014 - Added variable filtering to eliminate SQL injection attack threat
#            - Added required user/pass to gain access to this page
# 90310-2138 - Added admin header
# 90508-0644 - Changed to PHP long tags
# 120221-0159 - Added User Group restrictions
# 120223-2135 - Removed logging of good login passwords if webroot writable is enabled
# 130414-0224 - Added report logging
#

$startMS = microtime();

$report_name='User Group Hourly Stats';

require("dbconnect.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["group"]))				{$group=$_GET["group"];}
	elseif (isset($_POST["group"]))		{$group=$_POST["group"];}
if (isset($_GET["status"]))				{$status=$_GET["status"];}
	elseif (isset($_POST["status"]))		{$status=$_POST["status"];}
if (isset($_GET["date_with_hour"]))				{$date_with_hour=$_GET["date_with_hour"];}
	elseif (isset($_POST["date_with_hour"]))		{$date_with_hour=$_POST["date_with_hour"];}
if (isset($_GET["submit"]))				{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))		{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))				{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))		{$SUBMIT=$_POST["SUBMIT"];}

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,webroot_writable,outbound_autodial_active,user_territories_active FROM system_settings;";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$qm_conf_ct = mysql_num_rows($rslt);
$i=0;
while ($i < $qm_conf_ct)
	{
	$row=mysql_fetch_row($rslt);
	$non_latin =					$row[0];
	$webroot_writable =				$row[1];
	$SSoutbound_autodial_active =	$row[2];
	$user_territories_active =		$row[3];
	$i++;
	}
##### END SETTINGS LOOKUP #####
###########################################

$PHP_AUTH_USER = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_USER);
$PHP_AUTH_PW = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_PW);

$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 6 and view_reports='1';";
if ($DB) {echo "|$stmt|\n";}
if ($non_latin > 0) { $rslt=mysql_query("SET NAMES 'UTF8'");}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$auth=$row[0];

if( (strlen($PHP_AUTH_USER)<2) or (strlen($PHP_AUTH_PW)<2) or (!$auth))
	{
    Header("WWW-Authenticate: Basic realm=\"VICI-PROJECTS\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo "Invalid Username/Password: |$PHP_AUTH_USER|$PHP_AUTH_PW|\n";
    exit;
	}

##### BEGIN log visit to the vicidial_report_log table #####
$LOGip = getenv("REMOTE_ADDR");
$LOGbrowser = getenv("HTTP_USER_AGENT");
$LOGscript_name = getenv("SCRIPT_NAME");
$LOGserver_name = getenv("SERVER_NAME");
$LOGserver_port = getenv("SERVER_PORT");
$LOGrequest_uri = getenv("REQUEST_URI");
$LOGhttp_referer = getenv("HTTP_REFERER");
if (preg_match("/443/i",$LOGserver_port)) {$HTTPprotocol = 'https://';}
  else {$HTTPprotocol = 'http://';}
if (($LOGserver_port == '80') or ($LOGserver_port == '443') ) {$LOGserver_port='';}
else {$LOGserver_port = ":$LOGserver_port";}
$LOGfull_url = "$HTTPprotocol$LOGserver_name$LOGserver_port$LOGrequest_uri";

$stmt="INSERT INTO vicidial_report_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name |$group, $status|', url='$LOGfull_url';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_query($stmt, $link);
$report_log_id = mysql_insert_id($link);
##### END log visit to the vicidial_report_log table #####

$STARTtime = date("U");
$TODAY = date("Y-m-d");
$date_with_hour_default = date("Y-m-d H");
$date_no_hour_default = $TODAY;

if (!isset($date_with_hour)) {$date_with_hour = $date_with_hour_default;}
	$date_no_hour = $date_with_hour;
	$date_no_hour = eregi_replace(" ([0-9]{2})",'',$date_no_hour);
if (!isset($begin_date)) {$begin_date = $TODAY;}
if (!isset($end_date)) {$end_date = $TODAY;}

$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 7;";
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$auth=$row[0];

$fp = fopen ("./project_auth_entries.txt", "a");
$date = date("r");
$ip = getenv("REMOTE_ADDR");
$browser = getenv("HTTP_USER_AGENT");

  if( (strlen($PHP_AUTH_USER)<2) or (strlen($PHP_AUTH_PW)<2) or (!$auth))
	{
    Header("WWW-Authenticate: Basic realm=\"VICI-PROJECTS\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo "Invalid Username/Password: |$PHP_AUTH_USER|$PHP_AUTH_PW|\n";
    exit;
	}
  else
	{
	header ("Content-type: text/html; charset=utf-8");

	if($auth>0)
		{
		$office_no=strtoupper($PHP_AUTH_USER);
		$password=strtoupper($PHP_AUTH_PW);
		$stmt="SELECT full_name,user_group from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW'";
		$rslt=mysql_query($stmt, $link);
		$row=mysql_fetch_row($rslt);
		$LOGfullname =		$row[0];
		$LOGuser_group =	$row[1];

		if ($webroot_writable > 0)
			{
			fwrite ($fp, "VICIDIAL|GOOD|$date|$PHP_AUTH_USER|XXXX|$ip|$browser|$LOGfullname|\n");
			fclose($fp);
			}
		}
	else
		{
		if ($webroot_writable > 0)
			{
			fwrite ($fp, "VICIDIAL|FAIL|$date|$PHP_AUTH_USER|XXXX|$ip|$browser|\n");
			fclose($fp);
			}
		exit;
		}

#	$stmt="SELECT full_name from vicidial_users where user='$user';";
#	$rslt=mysql_query($stmt, $link);
#	$row=mysql_fetch_row($rslt);
#	$full_name = $row[0];

	}

$stmt="SELECT allowed_campaigns,allowed_reports,admin_viewable_groups,admin_viewable_call_times from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {$HTML_text.="|$stmt|\n";}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$LOGallowed_campaigns =			$row[0];
$LOGallowed_reports =			$row[1];
$LOGadmin_viewable_groups =		$row[2];
$LOGadmin_viewable_call_times =	$row[3];

$LOGadmin_viewable_groupsSQL='';
$whereLOGadmin_viewable_groupsSQL='';
if ( (!eregi("--ALL--",$LOGadmin_viewable_groups)) and (strlen($LOGadmin_viewable_groups) > 3) )
	{
	$rawLOGadmin_viewable_groupsSQL = preg_replace("/ -/",'',$LOGadmin_viewable_groups);
	$rawLOGadmin_viewable_groupsSQL = preg_replace("/ /","','",$rawLOGadmin_viewable_groupsSQL);
	$LOGadmin_viewable_groupsSQL = "and user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
	$whereLOGadmin_viewable_groupsSQL = "where user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
	}



?>
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<title>ADMINISTRATION: Group Hourly Stats
<?php

##### BEGIN Set variables to make header show properly #####
$ADD =					'311111';
$hh =					'usergroups';
$LOGast_admin_access =	'1';
$ADMIN =				'admin.php';
$page_width='770';
$section_width='750';
$header_font_size='3';
$subheader_font_size='2';
$subcamp_font_size='2';
$header_selected_bold='<b>';
$header_nonselected_bold='';
$usergroups_color =		'#FFFF99';
$usergroups_font =		'BLACK';
$usergroups_color =		'#E6E6E6';
$subcamp_color =	'#C6C6C6';
##### END Set variables to make header show properly #####

require("admin_header.php");

?>


<CENTER>
<TABLE WIDTH=620 BGCOLOR=#D9E6FE cellpadding=2 cellspacing=0><TR BGCOLOR=#015B91><TD ALIGN=LEFT><FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B> &nbsp; Group Hourly Stats <?php echo $group ?></TD><TD ALIGN=RIGHT><FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B> &nbsp; </TD></TR>




<?php 

if ( ($group) and ($status) and ($date_with_hour) )
{
$stmt="SELECT user,full_name from vicidial_users where user_group = '" . mysql_real_escape_string($group) . "' $LOGadmin_viewable_groupsSQL order by full_name desc;";
	if ($DB) {echo "$stmt\n";}
$rslt=mysql_query($stmt, $link);
$tsrs_to_print = mysql_num_rows($rslt);
	$o=0;
	while($o < $tsrs_to_print)
		{
		$row=mysql_fetch_row($rslt);
		$VDuser[$o] = "$row[0]";
		$VDname[$o] = "$row[1]";
		$o++;
		}

	$o=0;
	while($o < $tsrs_to_print)
		{
		$stmt="select count(*) from vicidial_log where call_date >= '" . mysql_real_escape_string($date_with_hour) . ":00:00' and  call_date <= '" . mysql_real_escape_string($date_with_hour) . ":59:59' and user='$VDuser[$o]' $LOGadmin_viewable_groupsSQL;";
			if ($DB) {echo "$stmt\n";}
		$rslt=mysql_query($stmt, $link);
		$row=mysql_fetch_row($rslt);
		$VDtotal[$o] = "$row[0]";

		$stmt="select count(*) from vicidial_log where call_date >= '" . mysql_real_escape_string($date_no_hour) . " 00:00:00' and  call_date <= '" . mysql_real_escape_string($date_no_hour) . " 23:59:59' and user='$VDuser[$o]' and status='" . mysql_real_escape_string($status) . "' $LOGadmin_viewable_groupsSQL;";
			if ($DB) {echo "$stmt\n";}
		$rslt=mysql_query($stmt, $link);
		$row=mysql_fetch_row($rslt);
		$VDday[$o] = "$row[0]";

		$stmt="select count(*) from vicidial_log where call_date >= '" . mysql_real_escape_string($date_with_hour) . ":00:00' and  call_date <= '" . mysql_real_escape_string($date_with_hour) . ":59:59' and user='$VDuser[$o]' and status='" . mysql_real_escape_string($status) . "' $LOGadmin_viewable_groupsSQL;";
			if ($DB) {echo "$stmt\n";}
		$rslt=mysql_query($stmt, $link);
		$row=mysql_fetch_row($rslt);
		$VDcount[$o] = "$row[0]";
		$o++;
		}

echo "<TR><TD ALIGN=LEFT COLSPAN=2>\n";

echo "<br><center>\n";

echo "<B>TSR HOUR COUNTS: <a href=\"./admin.php?ADD=3111&group_id=$group\">$group</a> | $status | $date_with_hour | $date_no_hour</B>\n";

echo "<center><TABLE width=600 cellspacing=0 cellpadding=1>\n";
echo "<tr><td><font size=2>TSR </td><td align=left><font size=2>ID </td><td align=right><font size=2> &nbsp; $status</td><td align=right><font size=2> &nbsp; TOTAL CALLS</td><td align=right><font size=2> &nbsp; $status DAY</td><td align=right><font size=2> &nbsp; &nbsp; </td></tr>\n";

	$day_calls=0;
	$hour_calls=0;
	$total_calls=0;
	$o=0;
	while($o < $tsrs_to_print)
		{
		if (eregi("1$|3$|5$|7$|9$", $o))
			{$bgcolor='bgcolor="#B9CBFD"';} 
		else
			{$bgcolor='bgcolor="#9BB9FB"';}
		echo "<tr $bgcolor><td><font size=2>$VDuser[$o]</td>";
		echo "<td align=left><font size=2> $VDname[$o]</td>\n";
		echo "<td align=right><font size=2> $VDcount[$o]</td>\n";
		echo "<td align=right><font size=2> $VDtotal[$o]</td>\n";
		echo "<td align=right><font size=2> $VDday[$o]</td>\n";
		echo "<td align=right><font size=1><a href=\"./admin.php?ADD=3&user=$VDuser[$o]\">MODIFY</a> | <a href=\"./user_stats.php?user=$VDuser[$o]\">STATS</a></td></tr>\n";
		$total_calls = ($total_calls + $VDtotal[$o]);
		$hour_calls = ($hour_calls + $VDcount[$o]);
		$day_calls = ($day_calls + $VDday[$o]);

		$o++;
		}

	echo "<tr><td><font size=2>TOTAL </td><td align=right><font size=2> $status </td><td align=right><font size=2> $hour_calls</td><td align=right><font size=2> $total_calls</td><td align=right><font size=2> $day_calls</td></tr>\n";


	}

echo "</TABLE></center>\n";
echo "<br><br>\n";


echo "<br>Please enter the group you want to get hourly stats for: <form action=$PHP_SELF method=GET>\n";
echo "<input type=hidden name=DB value=$DB>\n";
echo "group: <select size=1 name=group>\n";

$stmt="SELECT user_group,group_name from vicidial_user_groups $whereLOGadmin_viewable_groupsSQL order by user_group";
$rslt=mysql_query($stmt, $link);
$groups_to_print = mysql_num_rows($rslt);
$o=0;
$groups_list='';
while ($groups_to_print > $o) 
	{
	$rowx=mysql_fetch_row($rslt);
	if ($group == $group)
		{$groups_list .= "<option selected value=\"$rowx[0]\">$rowx[0] - $rowx[1]</option>\n";}
	else
		{$groups_list .= "<option value=\"$rowx[0]\">$rowx[0] - $rowx[1]</option>\n";}
	$o++;
	}
echo "$groups_list</select><br>\n";
echo "status: <input type=text name=status size=10 maxlength=10 value=\"$status\"> &nbsp; (example: XFER)<br>\n";
echo "date with hour: <input type=text name=date_with_hour size=14 maxlength=13 value=\"$date_with_hour\"> &nbsp; (example: 2004-06-25 14)<br>\n";
echo "<input type=submit name=submit value=SUBMIT>\n";
echo "<BR><BR><BR>\n";


$ENDtime = date("U");

$RUNtime = ($ENDtime - $STARTtime);

echo "\n\n\n<br><br><br>\n\n";


echo "<font size=0>\n\n\n<br><br><br>\nscript runtime: $RUNtime seconds</font>";


?>


</TD></TR></TABLE>
</body>
</html>

<?php

if ($db_source == 'S')
	{
	mysql_close($link);
	$use_slave_server=0;
	$db_source = 'M';
	require("dbconnect.php");
	}

$endMS = microtime();
$startMSary = explode(" ",$startMS);
$endMSary = explode(" ",$endMS);
$runS = ($endMSary[0] - $startMSary[0]);
$runM = ($endMSary[1] - $startMSary[1]);
$TOTALrun = ($runS + $runM);

$stmt="UPDATE vicidial_report_log set run_time='$TOTALrun' where report_log_id='$report_log_id';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_query($stmt, $link);

exit; 

?>
