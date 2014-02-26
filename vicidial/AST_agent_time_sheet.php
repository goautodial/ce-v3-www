<?php 
# AST_agent_time_sheet.php
# 
# Copyright (C) 2013  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES
#
# 60619-1729 - Added variable filtering to eliminate SQL injection attack threat
#            - Added required user/pass to gain access to this page
# 80624-0132 - Added vicidial_timeclock entries
# 90310-0745 - Added admin header
# 90508-0644 - Changed to PHP long tags
# 90524-2231 - Changed to use functions.php for seconds to HH:MM:SS conversion
# 100712-1324 - Added system setting slave server option
# 100802-2347 - Added User Group Allowed Reports option validation
# 100914-1326 - Added lookup for user_level 7 users to set to reports only which will remove other admin links
# 110703-1848 - Added download option
# 111104-1308 - Added user_group restrictions for selecting in-groups
# 130414-0148 - Added report logging
#

$startMS = microtime();

require("dbconnect.php");
require("functions.php");

$report_name = 'User Time Sheet';
$db_source = 'M';

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,outbound_autodial_active,slave_db_server,reports_use_slave_db,user_territories_active FROM system_settings;";
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
	$user_territories_active =		$row[4];
	}
##### END SETTINGS LOOKUP #####
###########################################


$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["agent"]))				{$agent=$_GET["agent"];}
	elseif (isset($_POST["agent"]))		{$agent=$_POST["agent"];}
if (isset($_GET["query_date"]))				{$query_date=$_GET["query_date"];}
	elseif (isset($_POST["query_date"]))	{$query_date=$_POST["query_date"];}
if (isset($_GET["calls_summary"]))			{$calls_summary=$_GET["calls_summary"];}
	elseif (isset($_POST["calls_summary"]))	{$calls_summary=$_POST["calls_summary"];}
if (isset($_GET["submit"]))				{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))	{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))				{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))	{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["file_download"]))					{$file_download=$_GET["file_download"];}
	elseif (isset($_POST["file_download"]))		{$file_download=$_POST["file_download"];}

$user=$agent;

$PHP_AUTH_USER = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_USER);
$PHP_AUTH_PW = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_PW);

$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 6 and view_reports='1' and active='Y';";
if ($DB) {$MAIN.="|$stmt|\n";}
if ($non_latin > 0) { $rslt=mysql_query("SET NAMES 'UTF8'");}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$auth=$row[0];

$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level='7' and view_reports='1' and active='Y';";
if ($DB) {$MAIN.="|$stmt|\n";}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$reports_only_user=$row[0];

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

$stmt="INSERT INTO vicidial_report_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name |$user, $query_date, $end_date, $shift, $file_download, $report_display_type|', url='$LOGfull_url';";
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
$vuLOGadmin_viewable_groupsSQL='';
$whereLOGadmin_viewable_groupsSQL='';
if ( (!eregi("--ALL--",$LOGadmin_viewable_groups)) and (strlen($LOGadmin_viewable_groups) > 3) )
	{
	$rawLOGadmin_viewable_groupsSQL = preg_replace("/ -/",'',$LOGadmin_viewable_groups);
	$rawLOGadmin_viewable_groupsSQL = preg_replace("/ /","','",$rawLOGadmin_viewable_groupsSQL);
	$LOGadmin_viewable_groupsSQL = "and user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
	$whereLOGadmin_viewable_groupsSQL = "where user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
	$vuLOGadmin_viewable_groupsSQL = "and vicidial_users.user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
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

$NOW_DATE = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");
$STARTtime = date("U");
if (!isset($query_date)) {$query_date = $NOW_DATE;}


$HEADER.="<HTML>\n";
$HEADER.="<HEAD>\n";
$HEADER.="<STYLE type=\"text/css\">\n";
$HEADER.="<!--\n";
$HEADER.="   .green {color: white; background-color: green}\n";
$HEADER.="   .red {color: white; background-color: red}\n";
$HEADER.="   .blue {color: white; background-color: blue}\n";
$HEADER.="   .purple {color: white; background-color: purple}\n";
$HEADER.="-->\n";
$HEADER.=" </STYLE>\n";

$HEADER.="<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";
$HEADER.="<TITLE>$report_name";


##### BEGIN Set variables to make header show properly #####
$ADD =					'3';
$hh =					'users';
$LOGast_admin_access =	'1';
$ADMIN =				'admin.php';
$page_width='770';
$section_width='750';
$header_font_size='3';
$subheader_font_size='2';
$subcamp_font_size='2';
$header_selected_bold='<b>';
$header_nonselected_bold='';
$users_color =		'#FFFF99';
$users_font =		'BLACK';
$users_color =		'#E6E6E6';
$subcamp_color =	'#C6C6C6';
##### END Set variables to make header show properly #####

#require("admin_header.php");

$MAIN.="<TABLE WIDTH=$page_width BGCOLOR=\"#F0F5FE\" cellpadding=2 cellspacing=0><TR BGCOLOR=\"#F0F5FE\"><TD>\n";

$MAIN.="Agent Time Sheet for: $user\n";
$MAIN.="<BR>\n";
$MAIN.="<FORM ACTION=\"$PHP_SELF\" METHOD=GET> &nbsp; \n";
$MAIN.="Date: <INPUT TYPE=TEXT NAME=query_date SIZE=19 MAXLENGTH=19 VALUE=\"$query_date\">\n";
$MAIN.="User ID: <INPUT TYPE=TEXT NAME=agent SIZE=10 MAXLENGTH=20 VALUE=\"$agent\">\n";
$MAIN.="<INPUT TYPE=SUBMIT NAME=SUBMIT VALUE=SUBMIT>\n";
$MAIN.="</FORM>\n\n";

$MAIN.="<PRE><FONT SIZE=3>\n";


if (!$agent)
{
$MAIN.="\n";
$MAIN.="PLEASE SELECT AN AGENT ID AND DATE-TIME ABOVE AND CLICK SUBMIT\n";
$MAIN.=" NOTE: stats taken from available agent log data\n";
}

else
{
$query_date_BEGIN = "$query_date 00:00:00";   
$query_date_END = "$query_date 23:59:59";
$time_BEGIN = "00:00:00";   
$time_END = "23:59:59";

$stmt="select full_name from vicidial_users where user='$agent' $vuLOGadmin_viewable_groupsSQL;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$row=mysql_fetch_row($rslt);
$full_name = $row[0];

$MAIN.="Agent Time Sheet                             $NOW_TIME\n";

$MAIN.="Time range: $query_date_BEGIN to $query_date_END\n\n";
$MAIN.="---------- AGENT TIME SHEET: $agent - $full_name -------------\n\n";

$CSV_text_header.="\"Agent Time Sheet - $NOW_TIME\"\n";
$CSV_text_header.="\"Time range: $query_date_BEGIN to $query_date_END\"\n";
$CSV_text_header.="\"AGENT TIME SHEET: $agent - $full_name\"\n\n";


if ($calls_summary)
	{
	$stmt="select count(*) as calls,sum(talk_sec) as talk,avg(talk_sec),sum(pause_sec),avg(pause_sec),sum(wait_sec),avg(wait_sec),sum(dispo_sec),avg(dispo_sec) from vicidial_agent_log where event_time <= '" . mysql_real_escape_string($query_date_END) . "' and event_time >= '" . mysql_real_escape_string($query_date_BEGIN) . "' and user='" . mysql_real_escape_string($agent) . "' and pause_sec<48800 and wait_sec<48800 and talk_sec<48800 and dispo_sec<48800 limit 1;";
	$rslt=mysql_query($stmt, $link);
	if ($DB) {$MAIN.="$stmt\n";}
	$row=mysql_fetch_row($rslt);

	$TOTAL_TIME = ($row[1] + $row[3] + $row[5] + $row[7]);

	$TOTAL_TIME_HMS =		sec_convert($TOTAL_TIME,'H'); 
	$TALK_TIME_HMS =		sec_convert($row[1],'H'); 
	$PAUSE_TIME_HMS =		sec_convert($row[3],'H'); 
	$WAIT_TIME_HMS =		sec_convert($row[5],'H'); 
	$WRAPUP_TIME_HMS =		sec_convert($row[7],'H'); 
	$TALK_AVG_MS =			sec_convert($row[2],'H'); 
	$PAUSE_AVG_MS =			sec_convert($row[4],'H'); 
	$WAIT_AVG_MS =			sec_convert($row[6],'H'); 
	$WRAPUP_AVG_MS =		sec_convert($row[8],'H'); 

	$pfTOTAL_TIME_HMS =		sprintf("%8s", $TOTAL_TIME_HMS);
	$pfTALK_TIME_HMS =		sprintf("%8s", $TALK_TIME_HMS);
	$pfPAUSE_TIME_HMS =		sprintf("%8s", $PAUSE_TIME_HMS);
	$pfWAIT_TIME_HMS =		sprintf("%8s", $WAIT_TIME_HMS);
	$pfWRAPUP_TIME_HMS =	sprintf("%8s", $WRAPUP_TIME_HMS);
	$pfTALK_AVG_MS =		sprintf("%6s", $TALK_AVG_MS);
	$pfPAUSE_AVG_MS =		sprintf("%6s", $PAUSE_AVG_MS);
	$pfWAIT_AVG_MS =		sprintf("%6s", $WAIT_AVG_MS);
	$pfWRAPUP_AVG_MS =		sprintf("%6s", $WRAPUP_AVG_MS);

	$MAIN.="TOTAL CALLS TAKEN: $row[0]     <a href='$PHP_SELF?calls_summary=$calls_summary&agent=$agent&query_date=$query_date&file_download=1'>[DOWNLOAD]</a>\n";
	$MAIN.="TALK TIME:               $pfTALK_TIME_HMS     AVERAGE: $pfTALK_AVG_MS\n";
	$MAIN.="PAUSE TIME:              $pfPAUSE_TIME_HMS     AVERAGE: $pfPAUSE_AVG_MS\n";
	$MAIN.="WAIT TIME:               $pfWAIT_TIME_HMS     AVERAGE: $pfWAIT_AVG_MS\n";
	$MAIN.="WRAPUP TIME:             $pfWRAPUP_TIME_HMS     AVERAGE: $pfWRAPUP_AVG_MS\n";
	$MAIN.="----------------------------------------------------------------\n";
	$MAIN.="TOTAL ACTIVE AGENT TIME: $pfTOTAL_TIME_HMS\n\n";
	$CSV_text1.=$CSV_text_header;
	$CSV_text1.="\"\",\"TOTAL CALLS TAKEN: $row[0]\"\n";
	$CSV_text1.="\"\",\"TALK TIME:\",\"$pfTALK_TIME_HMS\",\"AVERAGE:\",\"$pfTALK_AVG_MS\"\n";
	$CSV_text1.="\"\",\"PAUSE TIME:\",\"$pfPAUSE_TIME_HMS\",\"AVERAGE:\",\"$pfPAUSE_AVG_MS\"\n";
	$CSV_text1.="\"\",\"WAIT TIME:\",\"$pfWAIT_TIME_HMS\",\"AVERAGE:\",\"$pfWAIT_AVG_MS\"\n";
	$CSV_text1.="\"\",\"WRAPUP TIME:\",\"$pfWRAPUP_TIME_HMS\",\"AVERAGE:\",\"$pfWRAPUP_AVG_MS\"\n";
	$CSV_text1.="\"\",\"TOTAL ACTIVE AGENT TIME:\",\"$pfTOTAL_TIME_HMS\"\n\n";
	}
else
	{
	$MAIN.="<a href=\"$PHP_SELF?calls_summary=1&agent=$agent&query_date=$query_date\">Call Activity Summary</a>\n\n";

	}

$stmt="select event_time,UNIX_TIMESTAMP(event_time) from vicidial_agent_log where event_time <= '" . mysql_real_escape_string($query_date_END) . "' and event_time >= '" . mysql_real_escape_string($query_date_BEGIN) . "' and user='" . mysql_real_escape_string($agent) . "' order by event_time limit 1;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$row=mysql_fetch_row($rslt);

$MAIN.="FIRST LOGIN:          $row[0]\n";
$start = $row[1];

$CSV_login.="\"\",\"FIRST LOGIN:\",\"$row[0]\"\n";

$stmt="select event_time,UNIX_TIMESTAMP(event_time) from vicidial_agent_log where event_time <= '" . mysql_real_escape_string($query_date_END) . "' and event_time >= '" . mysql_real_escape_string($query_date_BEGIN) . "' and user='" . mysql_real_escape_string($agent) . "' order by event_time desc limit 1;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$row=mysql_fetch_row($rslt);

$MAIN.="LAST LOG ACTIVITY:    $row[0]\n";
$end = $row[1];

$CSV_login.="\"\",\"LAST LOG ACTIVITY:\",\"$row[0]\"\n";

$login_time = ($end - $start);
$LOGIN_TIME_HMS =		sec_convert($login_time,'H'); 
$pfLOGIN_TIME_HMS =		sprintf("%8s", $LOGIN_TIME_HMS);

$MAIN.="-----------------------------------------\n";
$MAIN.="TOTAL LOGGED-IN TIME:            $pfLOGIN_TIME_HMS\n";

$CSV_login.="\"\",\"TOTAL LOGGED-IN TIME:\",\"$pfLOGIN_TIME_HMS\"\n\n";
$CSV_text1.=$CSV_login;

### timeclock records


##### vicidial_timeclock log records for user #####

$total_login_time=0;
$SQday_ARY =	explode('-',$query_date_BEGIN);
$EQday_ARY =	explode('-',$query_date_END);
$SQepoch = mktime(0, 0, 0, $SQday_ARY[1], $SQday_ARY[2], $SQday_ARY[0]);
$EQepoch = mktime(23, 59, 59, $EQday_ARY[1], $EQday_ARY[2], $EQday_ARY[0]);

$MAIN.="\n";

$MAIN.="<B>TIMECLOCK LOGIN/LOGOUT TIME:     <a href='$PHP_SELF?calls_summary=$calls_summary&agent=$agent&query_date=$query_date&file_download=2'>[DOWNLOAD]</a></B>\n";
$MAIN.="<TABLE width=550 cellspacing=0 cellpadding=1>\n";
$MAIN.="<tr><td><font size=2>ID </td><td><font size=2>EDIT </td><td align=right><font size=2>EVENT </td><td align=right><font size=2> DATE</td><td align=right><font size=2> IP ADDRESS</td><td align=right><font size=2> GROUP</td><td align=right><font size=2>HOURS:MINUTES</td></tr>\n";

$CSV_text2.=$CSV_text_header;
$CSV_text2.=$CSV_login;
$CSV_text2.="\"TIMECLOCK LOGIN/LOGOUT TIME:\"\n";
$CSV_text2.="\"\",\"ID\",\"EDIT\",\"EVENT\",\"DATE\",\"IP ADDRESS\",\"GROUP\",\"HOURS:MINUTES\"\n";

	$stmt="SELECT event,event_epoch,user_group,login_sec,ip_address,timeclock_id,manager_user from vicidial_timeclock_log where user='$agent' and event_epoch >= '$SQepoch'  and event_epoch <= '$EQepoch';";
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
			$MAIN.="<tr $bgcolor><td><font size=2><A HREF=\"./timeclock_edit.php?timeclock_id=$row[5]\">$row[5]</A></td>";
			$MAIN.="<td align=right><font size=2>$manager_edit</td>";
			$MAIN.="<td align=right><font size=2>$row[0]</td>";
			$MAIN.="<td align=right><font size=2> $TC_log_date</td>\n";
			$MAIN.="<td align=right><font size=2> $row[4]</td>\n";
			$MAIN.="<td align=right><font size=2> $row[2]</td>\n";
			$MAIN.="<td align=right><font size=2> </td></tr>\n";
			$CSV_text2.="\"\",\"$row[5]\",\"$manager_edit\",\"$row[0]\",\"$TC_log_date\",\"$row[4]\",\"$row[2]\",\"\"\n";
			}
		if (ereg("LOGOUT", $row[0]))
			{
			$login_sec = $row[3];
			$total_login_time = ($total_login_time + $login_sec);
			$event_hours_minutes =		sec_convert($login_sec,'H'); 

			$MAIN.="<tr $bgcolor><td><font size=2><A HREF=\"./timeclock_edit.php?timeclock_id=$row[5]\">$row[5]</A></td>";
			$MAIN.="<td align=right><font size=2>$manager_edit</td>";
			$MAIN.="<td align=right><font size=2>$row[0]</td>";
			$MAIN.="<td align=right><font size=2> $TC_log_date</td>\n";
			$MAIN.="<td align=right><font size=2> $row[4]</td>\n";
			$MAIN.="<td align=right><font size=2> $row[2]</td>\n";
			$MAIN.="<td align=right><font size=2> $event_hours_minutes";
			if ($DB) {$MAIN.=" - $total_login_time - $login_sec";}
			$MAIN.="</td></tr>\n";
			$CSV_text2.="\"\",\"$row[5]\",\"$manager_edit\",\"$row[0]\",\"$TC_log_date\",\"$row[4]\",\"$row[2]\",\"$event_hours_minutes\"\n";
			}
		$o++;
	}
if (strlen($login_sec)<1)
	{
	$login_sec = ($STARTtime - $row[1]);
	$total_login_time = ($total_login_time + $login_sec);
		if ($DB) {$MAIN.="LOGIN ONLY - $total_login_time - $login_sec";}
	}
$total_login_hours_minutes =		sec_convert($total_login_time,'H'); 

	if ($DB) {$MAIN.=" - $total_login_time - $login_sec";}

$MAIN.="<tr><td align=right><font size=2> </td>";
$MAIN.="<td align=right><font size=2> </td>\n";
$MAIN.="<td align=right><font size=2> </td>\n";
$MAIN.="<td align=right><font size=2> </td>\n";
$MAIN.="<td align=right colspan=2><font size=2><font size=2>TOTAL </td>\n";
$MAIN.="<td align=right><font size=2> $total_login_hours_minutes  </td></tr>\n";
$CSV_text2.="\"\",\"\",\"\",\"\",\"\",\"\",\"TOTAL\",\"$total_login_hours_minutes\"\n";

$MAIN.="</TABLE><BR>$db_source\n";
$MAIN.="</BODY></HTML>\n";
}
	if ($file_download>0) {
		$FILE_TIME = date("Ymd-His");
		$CSVfilename = "AST_agent_time_sheet_$US$FILE_TIME.csv";
		$CSV_var="CSV_text".$file_download;
		$CSV_text=preg_replace('/^ +/', '', $$CSV_var);
		$CSV_text=preg_replace('/\n +,/', ',', $CSV_text);
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

	} else {
		header ("Content-type: text/html; charset=utf-8");
		echo $HEADER;
		require("admin_header.php");
		echo $MAIN;
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


?>

