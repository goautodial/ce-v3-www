<?php
# timeclock_status.php
# 
# Copyright (C) 2013  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES
#
# 80602-0201 - First Build
# 80603-1500 - formatting changes
# 90310-2103 - Added admin header
# 90508-0644 - Changed to PHP long tags
# 100214-1421 - Sort menu alphabetically
# 100712-1324 - Added system setting slave server option
# 100802-2347 - Added User Group Allowed Reports option validation
# 100914-1326 - Added lookup for user_level 7 users to set to reports only which will remove other admin links
# 110703-1833 - Added download option
# 111104-1315 - Added user_group restrictions for selecting in-groups
# 130414-0152 - Added report logging
#

#header ("Content-type: text/html; charset=utf-8");

$startMS = microtime();

require("dbconnect.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["begin_date"]))				{$begin_date=$_GET["begin_date"];}
	elseif (isset($_POST["begin_date"]))	{$begin_date=$_POST["begin_date"];}
if (isset($_GET["end_date"]))				{$end_date=$_GET["end_date"];}
	elseif (isset($_POST["end_date"]))		{$end_date=$_POST["end_date"];}
if (isset($_GET["user"]))					{$user=$_GET["user"];}
	elseif (isset($_POST["user"]))			{$user=$_POST["user"];}
if (isset($_GET["user_group"]))				{$user_group=$_GET["user_group"];}
	elseif (isset($_POST["user_group"]))	{$user_group=$_POST["user_group"];}
if (isset($_GET["DB"]))						{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))			{$DB=$_POST["DB"];}
if (isset($_GET["submit"]))					{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))		{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))					{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))		{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["file_download"]))					{$file_download=$_GET["file_download"];}
	elseif (isset($_POST["file_download"]))		{$file_download=$_POST["file_download"];}


$report_name = 'User Group Timeclock Status Report';
$db_source = 'M';

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,outbound_autodial_active,slave_db_server,reports_use_slave_db,webroot_writable,timeclock_end_of_day FROM system_settings;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$qm_conf_ct = mysql_num_rows($rslt);
if ($qm_conf_ct > 0)
	{
	$row=mysql_fetch_row($rslt);
	$non_latin =					$row[0];
	$SSoutbound_autodial_active =	$row[1];
	$slave_db_server =				$row[2];
	$reports_use_slave_db =			$row[3];
	$webroot_writable =				$row[4];
	$timeclock_end_of_day =			$row[5];
	}
##### END SETTINGS LOOKUP #####
###########################################

$PHP_AUTH_USER = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_USER);
$PHP_AUTH_PW = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_PW);

$STARTtime = date("U");
$TODAY = date("Y-m-d");
$HHMM = date("Hi");
$HHteod = substr($timeclock_end_of_day,0,2);
$MMteod = substr($timeclock_end_of_day,2,2);

if ($HHMM < $timeclock_end_of_day)
	{$EoD = mktime($HHteod, $MMteod, 10, date("m"), date("d")-1, date("Y"));}
else
	{$EoD = mktime($HHteod, $MMteod, 10, date("m"), date("d"), date("Y"));}

$EoDdate = date("Y-m-d H:i:s", $EoD);

$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 6 and view_reports='1' and active='Y';";
if ($non_latin > 0) { $rslt=mysql_query("SET NAMES 'UTF8'");}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$auth=$row[0];

$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level='7' and view_reports='1' and active='Y';";
if ($DB) {$MAIN.="|$stmt|\n";}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$reports_only_user=$row[0];

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

$stmt="INSERT INTO vicidial_report_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name |$user_group, $query_date, $end_date, $shift, $file_download, $report_display_type|', url='$LOGfull_url';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_query($stmt, $link);
$report_log_id = mysql_insert_id($link);
##### END log visit to the vicidial_report_log table #####

if ( (strlen($slave_db_server)>5) and (preg_match("/$report_name/",$reports_use_slave_db)) )
	{
	mysql_close($link);
	$use_slave_server=1;
	$db_source = 'S';
	require("dbconnect.php");
	$MAIN.="<!-- Using slave server $slave_db_server $db_source -->\n";
	}

$stmt="SELECT user_group from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 6 and view_reports='1' and active='Y';";
if ($DB) {$MAIN.="|$stmt|\n";}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$LOGuser_group =			$row[0];

$stmt="SELECT allowed_campaigns,allowed_reports,admin_viewable_groups,admin_viewable_call_times from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {$MAIN.="|$stmt|\n";}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$LOGallowed_campaigns =			$row[0];
$LOGallowed_reports =			$row[1];
$LOGadmin_viewable_groups =		$row[2];
$LOGadmin_viewable_call_times =	$row[3];

if ( (!preg_match("/$report_name/",$LOGallowed_reports)) and (!preg_match("/ALL REPORTS/",$LOGallowed_reports)) )
	{
    Header("WWW-Authenticate: Basic realm=\"VICI-PROJECTS\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo "You are not allowed to view this report: |$PHP_AUTH_USER|$report_name|\n";
    exit;
	}

$LOGadmin_viewable_groupsSQL='';
$whereLOGadmin_viewable_groupsSQL='';
if ( (!eregi("--ALL--",$LOGadmin_viewable_groups)) and (strlen($LOGadmin_viewable_groups) > 3) )
	{
	$rawLOGadmin_viewable_groupsSQL = preg_replace("/ -/",'',$LOGadmin_viewable_groups);
	$rawLOGadmin_viewable_groupsSQL = preg_replace("/ /","','",$rawLOGadmin_viewable_groupsSQL);
	$LOGadmin_viewable_groupsSQL = "and user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
	$whereLOGadmin_viewable_groupsSQL = "where user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
	}

$LOGadmin_viewable_call_timesSQL='';
$whereLOGadmin_viewable_call_timesSQL='';
if ( (!eregi("--ALL--",$LOGadmin_viewable_call_times)) and (strlen($LOGadmin_viewable_call_times) > 3) )
	{
	$rawLOGadmin_viewable_call_timesSQL = preg_replace("/ -/",'',$LOGadmin_viewable_call_times);
	$rawLOGadmin_viewable_call_timesSQL = preg_replace("/ /","','",$rawLOGadmin_viewable_call_timesSQL);
	$LOGadmin_viewable_call_timesSQL = "and call_time_id IN('---ALL---','$rawLOGadmin_viewable_call_timesSQL')";
	$whereLOGadmin_viewable_call_timesSQL = "where call_time_id IN('---ALL---','$rawLOGadmin_viewable_call_timesSQL')";
	}

$stmt="select user_group from vicidial_user_groups $whereLOGadmin_viewable_groupsSQL order by user_group;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$user_groups_to_print = mysql_num_rows($rslt);
	$i=0;
	$user_groups_to_print++;
while ($i < $user_groups_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$LISTuser_groups[$i] =$row[0];
	if ($row[0]==$user_group)
		{$FORMuser_groups.="<option value=\"$row[0]\" SELECTED>$row[0]</option>";}
	else
		{$FORMuser_groups.="<option value=\"$row[0]\">$row[0]</option>";}
	$i++;
	}

if (strlen($user_group) > 0)
	{
	$stmt="SELECT group_name from vicidial_user_groups where user_group='$user_group' $LOGadmin_viewable_groupsSQL;";
	$rslt=mysql_query($stmt, $link);
	$row=mysql_fetch_row($rslt);
	$group_name = $row[0];
	}

$HEADER.="<html>\n";
$HEADER.="<head>\n";
$HEADER.="<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";
$HEADER.="<title>ADMINISTRATION: \n";
$HEADER.="$report_name";

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

# require("admin_header.php");



$MAIN.="<CENTER>\n";
$MAIN.="<TABLE WIDTH=750 BGCOLOR=#D9E6FE cellpadding=2 cellspacing=0><TR BGCOLOR=#015B91><TD ALIGN=LEFT>\n";
$MAIN.="<FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE SIZE=2><B>Timeclock Status for $user_group</TD><TD ALIGN=RIGHT> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n";
$MAIN.="<a href=\"./timeclock_report.php\"><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE SIZE=2><B>TIMECLOCK REPORT</a> | ";
$MAIN.="<a href=\"./admin.php?ADD=311111&user_group=$user_group\"><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE SIZE=2><B>USER GROUP</a>\n";
$MAIN.="</TD></TR>\n";

$MAIN.="<TR BGCOLOR=\"#F0F5FE\"><TD ALIGN=LEFT COLSPAN=2><FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2><B> &nbsp; \n";

$MAIN.="<form action=$PHP_SELF method=GET>\n";
$MAIN.="<input type=hidden name=DB value=\"$DB\">\n";
$MAIN.="<select size=1 name=user_group>$FORMuser_groups</select>";
$MAIN.="<input type=submit name=submit value=submit>\n";

$MAIN.="</B></TD></TR>\n";
$MAIN.="<TR><TD ALIGN=LEFT COLSPAN=2>\n";
$MAIN.="<br><center>\n";

if (strlen($user_group) < 1)
	{
	header ("Content-type: text/html; charset=utf-8");
	echo "$HEADER";
	require("admin_header.php");
	echo "$MAIN";

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
	}


##### grab all users in this user_group #####
$stmt="SELECT user,full_name from vicidial_users where user_group='" . mysql_real_escape_string($user_group) . "' $LOGadmin_viewable_groupsSQL order by full_name;";
if ($DB>0) {$MAIN.="|$stmt|";}
$rslt=mysql_query($stmt, $link);
$users_to_print = mysql_num_rows($rslt);
$o=0;
while ($users_to_print > $o) 
	{
	$row=mysql_fetch_row($rslt);
	$users[$o] =		$row[0];
	$full_name[$o] =	$row[1];
	$Vevent_time[$o] =	'';
	$Vevent_epoch[$o] =	0;
	$Vcampaign[$o] =	'';
	$Tevent_epoch[$o] =	'';
	$Tevent_date[$o] =	'';
	$Tstatus[$o] =		'';
	$Tip_address[$o] =	'';
	$Tlogin_time[$o] =	'';
	$Tlogin_sec[$o] =	0;

	$o++;
	}

$o=0;
while ($users_to_print > $o) 
	{
	$total_login_time = 0;
	##### grab timeclock status record for this user #####
	$stmt="SELECT event_epoch,event_date,status,ip_address from vicidial_timeclock_status where user='$users[$o]' and event_epoch >= '$EoD';";
	if ($DB>0) {$MAIN.="|$stmt|";}
	$rslt=mysql_query($stmt, $link);
	$stats_to_print = mysql_num_rows($rslt);
	if ($stats_to_print > 0) 
		{
		$row=mysql_fetch_row($rslt);
		$Tevent_epoch[$o] =	$row[0];
		$Tevent_date[$o] =	$row[1];
		$Tstatus[$o] =		$row[2];
		$Tip_address[$o] =	$row[3];

		if ( ($row[2]=='START') or ($row[2]=='LOGIN') )
			{$bgcolor[$o]='bgcolor="#B9CBFD"';} 
		else
			{$bgcolor[$o]='bgcolor="#9BB9FB"';}
		}

	##### grab timeclock logged-in time for each user #####
	$stmt="SELECT event,event_epoch,login_sec from vicidial_timeclock_log where user='$users[$o]' and event_epoch >= '$EoD';";
	if ($DB>0) {$MAIN.="|$stmt|";}
	$rslt=mysql_query($stmt, $link);
	$logs_to_parse = mysql_num_rows($rslt);
	$p=0;
	while ($logs_to_parse > $p) 
		{
		$row=mysql_fetch_row($rslt);
		if ( (ereg("LOGIN", $row[0])) or (ereg("START", $row[0])) )
			{
			$login_sec='';
			$Tevent_time[$o] = date("Y-m-d H:i:s", $row[1]);
			}
		if (ereg("LOGOUT", $row[0]))
			{
			$login_sec = $row[2];
			$total_login_time = ($total_login_time + $login_sec);
			}
		$p++;
		}
	if ( (strlen($login_sec)<1) and ($logs_to_parse > 0) )
		{
		$login_sec = ($STARTtime - $row[1]);
		$total_login_time = ($total_login_time + $login_sec);
		}
	if ($logs_to_parse > 0)
		{
		$total_login_hours = ($total_login_time / 3600);
		$total_login_hours_int = round($total_login_hours, 2);
		$total_login_hours_int = intval("$total_login_hours");
		$total_login_minutes = ($total_login_hours - $total_login_hours_int);
		$total_login_minutes = ($total_login_minutes * 60);
		$total_login_minutes_int = round($total_login_minutes, 0);
		if ($total_login_minutes_int < 10) {$total_login_minutes_int = "0$total_login_minutes_int";}

		$Tlogin_time[$o] = "$total_login_hours_int:$total_login_minutes_int";
		$Tlogin_sec[$o] = $total_login_time;
		}
	else
		{
		$total_login_time = 0;
		$Tlogin_time[$o] = "0:00";
		$Tlogin_sec[$o] = $total_login_time;
		}

	if ($DB>0) {$MAIN.="|$Tlogin_sec[$o]|$Tlogin_time[$o]|";}

	##### grab vicidial_agent_log records in this user_group #####
	$stmt="SELECT event_time,UNIX_TIMESTAMP(event_time),campaign_id from vicidial_agent_log where user='$users[$o]' and event_time >= '$EoDdate' order by agent_log_id desc limit 1;";
	if ($DB>0) {$MAIN.="|$stmt|";}
	$rslt=mysql_query($stmt, $link);
	$vals_to_print = mysql_num_rows($rslt);
	if ($vals_to_print > 0) 
		{
		$row=mysql_fetch_row($rslt);
		$Vevent_time[$o] =	$row[0];
		$Vevent_epoch[$o] =	$row[1];
		$Vcampaign[$o] =	$row[2];
		}

	$o++;
	}


##### print each user that has any activity for today #####
$MAIN.="<br>\n";
$MAIN.="<center>\n";

$MAIN.="<TABLE width=720 cellspacing=0 cellpadding=1>\n";
$MAIN.="<TR>\n";
$MAIN.="<TD bgcolor=\"#99FF33\"> &nbsp; &nbsp; </TD><TD align=left> TC Logged in and VICI active</TD>\n"; # bright green
$MAIN.="<TD bgcolor=\"#FFFF33\"> &nbsp; &nbsp; </TD><TD align=left> TC Logged in only</TD>\n"; # bright yellow
$MAIN.="<TD bgcolor=\"#FF6666\"> &nbsp; &nbsp; </TD><TD align=left> VICI active only</TD>\n"; # bright red
$MAIN.="</TR><TR>\n";
$MAIN.="<TD bgcolor=\"#66CC66\"> &nbsp; &nbsp; </TD><TD align=left> TC Logged out and VICI active</TD>\n"; # dull green
$MAIN.="<TD bgcolor=\"#CCCC00\"> &nbsp; &nbsp; </TD><TD align=left> TC Logged out only</TD>\n"; # dull yellow
$MAIN.="<TD> &nbsp; &nbsp; </TD><TD align=left> &nbsp; </TD>\n";
$MAIN.="</TR></TABLE><BR>\n";

$MAIN.="<B>USER STATUS FOR USER GROUP: $user_group &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='$PHP_SELF?DB=$DB&user_group=$user_group&submit=$submit&file_download=1'>[DOWNLOAD]</a></B>\n";
$MAIN.="<TABLE width=700 cellspacing=0 cellpadding=1>\n";
$MAIN.="<tr><td><font size=2># </td><td><font size=2>USER </td><td align=left><font size=2>NAME </td><td align=right><font size=2> IP ADDRESS</td><td align=right><font size=2> TC TIME</td><td align=right><font size=2>TC LOGIN</td><td align=right><font size=2> VICI LAST LOG</td><td align=right><font size=2> VICI CAMPAIGN</td></tr>\n";

$CSV_text.="\"USER STATUS FOR USER GROUP: $user_group\"\n";
$CSV_text.="\"\",\"#\",\"USER\",\"NAME\",\"STATUS\",\"IP ADDRESS\",\"TC TIME\",\"TC LOGIN\",\"VICI LAST LOG\",\"VICI CAMPAIGN\"\n";

$o=0;
$s=0;
while ($users_to_print > $o) 
	{
	if ( ($Tlogin_sec[$o] > 0) or (strlen($Vevent_time[$o]) > 0) )
		{
		if ( ($Tstatus[$o]=='START') or ($Tstatus[$o]=='LOGIN') )
			{
			if ($Tlogin_sec[$o] > 0)
				{$bgcolor[$o]='bgcolor="#FFFF33"'; $CSV_status="TC Logged in only";} # yellow
			if ( ($Tlogin_sec[$o] > 0) and (strlen($Vevent_time[$o]) > 0) )
				{$bgcolor[$o]='bgcolor="#99FF33"'; $CSV_status="TC Logged in and VICI active";} # green
			}
		else
			{
			if ($Tlogin_sec[$o] > 0)
				{$bgcolor[$o]='bgcolor="#CCCC00"'; $CSV_status="TC Logged out only";} # yellow
			if (strlen($Vevent_time[$o]) > 0)
				{$bgcolor[$o]='bgcolor="#FF6666"'; $CSV_status="VICI active only";} # red
			if ( ($Tlogin_sec[$o] > 0) and (strlen($Vevent_time[$o]) > 0) )
				{$bgcolor[$o]='bgcolor="#66CC66"'; $CSV_status="TC Logged out and VICI active";} # green
			}

		$s++;
		$MAIN.="<tr $bgcolor[$o]>";
		$MAIN.="<td><font size=1>$s</td>";
		$MAIN.="<td><font size=2><a href=\"./user_status.php?user=$users[$o]\">$users[$o]</a></td>";
		$MAIN.="<td><font size=2>$full_name[$o]</td>";
		$MAIN.="<td><font size=2>$Tip_address[$o]</td>";
		$MAIN.="<td align=right><font size=2>$Tlogin_time[$o]</td>";
		$MAIN.="<td align=right><font size=2>$Tevent_time[$o]</td>";
		$MAIN.="<td align=right><font size=2>$Vevent_time[$o]</td>";
		$MAIN.="<td align=right><font size=2>$Vcampaign[$o]</td>";
		$MAIN.="</tr>";

		$CSV_text.="\"\",\"$s\",\"$users[$o]\",\"$full_name[$o]\",\"$CSV_status\",\"$Tip_address[$o]\",\"$Tlogin_time[$o]\",\"$Tevent_time[$o]\",\"$Vevent_time[$o]\",\"$Vcampaign[$o]\"\n";

		if (strlen($Tstatus[$o])>0)
			{$TOTlogin_sec = ($TOTlogin_sec + $Tlogin_sec[$o]);}
		}
	$o++;
	}



$total_login_hours = ($TOTlogin_sec / 3600);
$total_login_hours_int = round($total_login_hours, 2);
$total_login_hours_int = intval("$total_login_hours");
$total_login_minutes = ($total_login_hours - $total_login_hours_int);
$total_login_minutes = ($total_login_minutes * 60);
$total_login_minutes_int = round($total_login_minutes, 0);
if ($total_login_minutes_int < 10) {$total_login_minutes_int = "0$total_login_minutes_int";}

$MAIN.="<tr bgcolor=white>";
$MAIN.="<td colspan=4><font size=2>TOTALS</td>";
$MAIN.="<td align=right><font size=2>$total_login_hours_int:$total_login_minutes_int</td>";
$MAIN.="<td align=right><font size=2></td>";
$MAIN.="<td align=right><font size=2></td>";
$MAIN.="<td align=right><font size=2></td>";
$MAIN.="</tr>";
$MAIN.="</table>";

$CSV_text.="\"\",\"TOTALS\",\"\",\"\",\"\",\"\",\"$total_login_hours_int:$total_login_minutes_int\"\n";


$ENDtime = date("U");

$RUNtime = ($ENDtime - $STARTtime);

$MAIN.="\n\n\n<br><br><br>\n\n";


$MAIN.="<font size=0>\n\n\n<br><br><br>\nscript runtime: $RUNtime seconds|$db_source</font>";

$MAIN.="</TD></TR><TABLE>\n";
$MAIN.="</body>\n";
$MAIN.="</html>\n";

if ($file_download > 0)
	{
	$FILE_TIME = date("Ymd-His");
	$CSVfilename = "timeclock_status_$US$FILE_TIME.csv";
	$CSV_text=preg_replace('/ +\"/', '"', $CSV_text);
	$CSV_text=preg_replace('/\" +/', '"', $CSV_text);
	// We'll be outputting a TXT file
	header('Content-type: application/octet-stream');

	// It will be called LIST_101_20090209-121212.txt
	header("Content-Disposition: attachment; filename=\"$CSVfilename\"");
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	ob_clean();
	flush();

	echo "$CSV_text";
	}
else 
	{
	header ("Content-type: text/html; charset=utf-8");
	echo "$HEADER";
	require("admin_header.php");
	echo "$MAIN";
	}

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











##### vicidial_timeclock log records for user #####

$SQday_ARY =	explode('-',$begin_date);
$EQday_ARY =	explode('-',$end_date);
$SQepoch = mktime(0, 0, 0, $SQday_ARY[1], $SQday_ARY[2], $SQday_ARY[0]);
$EQepoch = mktime(23, 59, 59, $EQday_ARY[1], $EQday_ARY[2], $EQday_ARY[0]);

$MAIN.="<br><br>\n";

$MAIN.="<center>\n";

$MAIN.="<B>TIMECLOCK LOGIN/LOGOUT TIME:</B>\n";
$MAIN.="<TABLE width=550 cellspacing=0 cellpadding=1>\n";
$MAIN.="<tr><td><font size=2>ID </td><td><font size=2>EDIT </td><td align=right><font size=2>EVENT </td><td align=right><font size=2> DATE</td><td align=right><font size=2> IP ADDRESS</td><td align=right><font size=2> GROUP</td><td align=right><font size=2>HOURS:MINUTES</td></tr>\n";

	$stmt="SELECT event,event_epoch,user_group,login_sec,ip_address,timeclock_id,manager_user from vicidial_timeclock_log where user='" . mysql_real_escape_string($user) . "' and event_epoch >= '$SQepoch'  and event_epoch <= '$EQepoch';";
	if ($DB>0) {$MAIN.="|$stmt|";}
	$rslt=mysql_query($stmt, $link);
	$events_to_print = mysql_num_rows($rslt);

	$total_logs=0;
	$o=0;
	while ($events_to_print > $o) {
		$row=mysql_fetch_row($rslt);
		if ( ($row[0]=='START') or ($row[0]=='LOGIN') )
			{$bgcolor='bgcolor="#B9CBFD"';} 
		else
			{$bgcolor='bgcolor="#9BB9FB"';}

		$TC_log_date = date("Y-m-d H:i:s", $row[1]);

		$manager_edit='';
		if (strlen($row[6])>0) {$manager_edit = ' * ';}

		if (ereg("LOGIN", $row[0]))
			{
			$login_sec='';
			$MAIN.="<tr $bgcolor><td><font size=2>$row[5]</td>";
			$MAIN.="<td align=right><font size=2>$manager_edit</td>";
			$MAIN.="<td align=right><font size=2>$row[0]</td>";
			$MAIN.="<td align=right><font size=2> $TC_log_date</td>\n";
			$MAIN.="<td align=right><font size=2> $row[4]</td>\n";
			$MAIN.="<td align=right><font size=2> $row[2]</td>\n";
			$MAIN.="<td align=right><font size=2> </td></tr>\n";
			}
		if (ereg("LOGOUT", $row[0]))
			{
			$login_sec = $row[3];
			$total_login_time = ($total_login_time + $login_sec);
			$event_hours = ($login_sec / 3600);
			$event_hours_int = round($event_hours, 2);
			$event_hours_int = intval("$event_hours_int");
			$event_minutes = ($event_hours - $event_hours_int);
			$event_minutes = ($event_minutes * 60);
			$event_minutes_int = round($event_minutes, 0);
			if ($event_minutes_int < 10) {$event_minutes_int = "0$event_minutes_int";}
			$MAIN.="<tr $bgcolor><td><font size=2>$row[5]</td>";
			$MAIN.="<td align=right><font size=2>$manager_edit</td>";
			$MAIN.="<td align=right><font size=2>$row[0]</td>";
			$MAIN.="<td align=right><font size=2> $TC_log_date</td>\n";
			$MAIN.="<td align=right><font size=2> $row[4]</td>\n";
			$MAIN.="<td align=right><font size=2> $row[2]</td>\n";
			$MAIN.="<td align=right><font size=2> $event_hours_int:$event_minutes_int</td></tr>\n";
			}
		$o++;
	}
if (strlen($login_sec)<1)
	{
	$login_sec = ($STARTtime - $row[1]);
	$total_login_time = ($total_login_time + $login_sec);
	}
$total_login_hours = ($total_login_time / 3600);
$total_login_hours_int = round($total_login_hours, 2);
$total_login_hours_int = intval("$total_login_hours");
$total_login_minutes = ($total_login_hours - $total_login_hours_int);
$total_login_minutes = ($total_login_minutes * 60);
$total_login_minutes_int = round($total_login_minutes, 0);
if ($total_login_minutes_int < 10) {$total_login_minutes_int = "0$total_login_minutes_int";}

$MAIN.="<tr><td align=right><font size=2> </td>";
$MAIN.="<td align=right><font size=2> </td>\n";
$MAIN.="<td align=right><font size=2> </td>\n";
$MAIN.="<td align=right><font size=2> </td>\n";
$MAIN.="<td align=right><font size=2><font size=2>TOTAL </td>\n";
$MAIN.="<td align=right><font size=2> $total_login_hours_int:$total_login_minutes_int  </td></tr>\n";

$MAIN.="</TABLE></center>\n";







$ENDtime = date("U");

$RUNtime = ($ENDtime - $STARTtime);

$MAIN.="\n\n\n<br><br><br>\n\n";


$MAIN.="<font size=0>\n\n\n<br><br><br>\nscript runtime: $RUNtime seconds|$db_source</font>";

$MAIN.="</TD></TR><TABLE>\n";
$MAIN.="</body>\n";
$MAIN.="</html>\n";

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





