<?php 
# AST_inbound_daily_report.php
# 
# Copyright (C) 2013  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES
#
# 111119-1234 - First build
# 120118-2116 - Changed headers on CSV download
# 120224-0910 - Added HTML display option with bar graphs
# 120601-2235 - Added group name to header, added status breakdown counts to page and CSV with option to display them
# 120611-2200 - Added ability to filter output by call time
# 120819-0118 - Formatting changes
# 121115-0621 - Changed to multi-select for in-group selection
# 130322-2008 - Added Unique Agents column
# 130414-0110 - Added report logging
#

$startMS = microtime();

require("dbconnect.php");

if (file_exists('options.php'))
        {
        require('options.php');
        }

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["group"]))				{$group=$_GET["group"];}
	elseif (isset($_POST["group"]))		{$group=$_POST["group"];}
if (isset($_GET["query_date"]))				{$query_date=$_GET["query_date"];}
	elseif (isset($_POST["query_date"]))	{$query_date=$_POST["query_date"];}
if (isset($_GET["end_date"]))				{$end_date=$_GET["end_date"];}
	elseif (isset($_POST["end_date"]))		{$end_date=$_POST["end_date"];}
if (isset($_GET["shift"]))				{$shift=$_GET["shift"];}
	elseif (isset($_POST["shift"]))		{$shift=$_POST["shift"];}
if (isset($_GET["file_download"]))			{$file_download=$_GET["file_download"];}
	elseif (isset($_POST["file_download"]))	{$file_download=$_POST["file_download"];}
if (isset($_GET["hourly_breakdown"]))			{$hourly_breakdown=$_GET["hourly_breakdown"];}
	elseif (isset($_POST["hourly_breakdown"]))	{$hourly_breakdown=$_POST["hourly_breakdown"];}
if (isset($_GET["show_disposition_statuses"]))			{$show_disposition_statuses=$_GET["show_disposition_statuses"];}
	elseif (isset($_POST["show_disposition_statuses"]))	{$show_disposition_statuses=$_POST["show_disposition_statuses"];}
if (isset($_GET["ignore_afterhours"]))			{$ignore_afterhours=$_GET["ignore_afterhours"];}
	elseif (isset($_POST["ignore_afterhours"]))	{$ignore_afterhours=$_POST["ignore_afterhours"];}
if (isset($_GET["submit"]))				{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))	{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))				{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))	{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["DB"]))				{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))	{$DB=$_POST["DB"];}
if (isset($_GET["report_display_type"]))				{$report_display_type=$_GET["report_display_type"];}
	elseif (isset($_POST["report_display_type"]))	{$report_display_type=$_POST["report_display_type"];}

$PHP_AUTH_USER = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_USER);
$PHP_AUTH_PW = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_PW);

if (strlen($shift)<2) {$shift='ALL';}

$report_name = 'Inbound Daily Report';
$db_source = 'M';

if ($ignore_afterhours=="checked") {$status_clause=" and status!='AFTHRS'";}

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,outbound_autodial_active,slave_db_server,reports_use_slave_db FROM system_settings;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$qm_conf_ct = mysql_num_rows($rslt);
if ($qm_conf_ct > 0)
	{
	$row=mysql_fetch_row($rslt);
	$non_latin =					$row[0];
	$outbound_autodial_active =		$row[1];
	$slave_db_server =				$row[2];
	$reports_use_slave_db =			$row[3];
	}
##### END SETTINGS LOOKUP #####
###########################################

$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level >= 7 and view_reports='1' and active='Y';";
if ($DB) {$MAIN.="|$stmt|\n";}
if ($non_latin > 0) {$rslt=mysql_query("SET NAMES 'UTF8'");}
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

$stmt="INSERT INTO vicidial_report_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name |$group[0], $query_date, $end_date, $shift, $file_download, $report_display_type|', url='$LOGfull_url';";
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

if ($IDR_calltime_available==1)
	{
	$LOGadmin_viewable_call_timesSQL='';
	$whereLOGadmin_viewable_call_timesSQL='';
	if ( (!eregi("--ALL--",$LOGadmin_viewable_call_times)) and (strlen($LOGadmin_viewable_call_times) > 3) )
		{
		$rawLOGadmin_viewable_call_timesSQL = preg_replace("/ -/",'',$LOGadmin_viewable_call_times);
		$rawLOGadmin_viewable_call_timesSQL = preg_replace("/ /","','",$rawLOGadmin_viewable_call_timesSQL);
		$LOGadmin_viewable_call_timesSQL = "and call_time_id IN('---ALL---','$rawLOGadmin_viewable_call_timesSQL')";
		$whereLOGadmin_viewable_call_timesSQL = "where call_time_id IN('---ALL---','$rawLOGadmin_viewable_call_timesSQL')";
		}

	$stmt="select call_time_id,call_time_name from vicidial_call_times $whereLOGadmin_viewable_call_timesSQL order by call_time_id;";
	$rslt=mysql_query($stmt, $link);
	if ($DB) {$MAIN.="$stmt\n";}
	$times_to_print = mysql_num_rows($rslt);
	$i=0;
	while ($i < $times_to_print)
		{
		$row=mysql_fetch_row($rslt);
		$call_times[$i] =		$row[0];
		$call_time_names[$i] =	$row[1];
		$i++;
		}
	}
else 
	{
	$shift="24hours";
	}

$NOW_DATE = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");
$STARTtime = date("U");
if (!isset($group)) {$group = '';}
if (!isset($query_date)) {$query_date = $NOW_DATE;}
if (!isset($end_date)) {$end_date = $NOW_DATE;}
$groups_selected = count($group);
$group_name_str="";
$groups_selected_str="";
$groups_selected_URLstr="";
for ($i=0; $i<$groups_selected; $i++) 
	{
	$selected_group_URLstr.="&group[]=$group[$i]";
	if ($group[$i]=="--ALL--") 
		{
		$group=array("--ALL--");
		$groups_selected=1;
		$group_name_str.="-- ALL INGROUPS --";
		$all_selected="selected";
		}
	else 
		{
		$groups_selected_str.="'$group[$i]', ";
		}
	}

$stmt="select group_id,group_name from vicidial_inbound_groups $whereLOGadmin_viewable_groupsSQL order by group_id;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$groups_to_print = mysql_num_rows($rslt);
$i=0;
$groups_string='|';
while ($i < $groups_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$groups[$i] =		$row[0];
	$group_names[$i] =	$row[1];
	$groups_string .= "$groups[$i]|";
	for ($j=0; $j<$groups_selected; $j++) {
		if ($group[$j] && $groups[$i]==$group[$j]) {$group_name_str.="$groups[$i] - $group_names[$i], ";}
		if ($group[$j]=="--ALL--") {$groups_selected_str.="'$groups[$i]', ";}
	}
	$i++;
	}

$groups_selected_str=preg_replace('/, $/', '', $groups_selected_str);
$group_name_str=preg_replace('/, $/', '', $group_name_str);

$stmt="select call_time_id,call_time_name from vicidial_call_times $whereLOGadmin_viewable_call_timesSQL order by call_time_id;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$times_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $times_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$call_times[$i] =		$row[0];
	$call_time_names[$i] =	$row[1];
	$i++;
	}

$HEADER.="<HTML>\n";
$HEADER.="<HEAD>\n";
$HEADER.="<STYLE type=\"text/css\">\n";
$HEADER.="<!--\n";
$HEADER.="   .green {color: black; background-color: #99FF99}\n";
$HEADER.="   .red {color: black; background-color: #FF9999}\n";
$HEADER.="   .orange {color: black; background-color: #FFCC99}\n";
$HEADER.="-->\n";
$HEADER.=" </STYLE>\n";

#if (!preg_match("/\|$group\|/i",$groups_string))
#	{
#	$HEADER.="<!-- group not found: $group  $groups_string -->\n";
#	$group='';
#	}

$HEADER.="<script language=\"JavaScript\" src=\"calendar_db.js\"></script>\n";
$HEADER.="<link rel=\"stylesheet\" href=\"calendar.css\">\n";
$HEADER.="<link rel=\"stylesheet\" href=\"horizontalbargraph.css\">\n";

$HEADER.="<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";
$HEADER.="<TITLE>$report_name</TITLE></HEAD><BODY BGCOLOR=WHITE marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>\n";

$short_header=1;

# require("admin_header.php");

$MAIN.="<FORM ACTION=\"$PHP_SELF\" METHOD=GET name=vicidial_report id=vicidial_report>\n";
$MAIN.="<TABLE CELLPADDING=4 CELLSPACING=0><TR valign='bottom'><TD colspan=2>";

$MAIN.="<INPUT TYPE=TEXT NAME=query_date SIZE=10 MAXLENGTH=10 VALUE=\"$query_date\">";

$MAIN.="<script language=\"JavaScript\">\n";
$MAIN.="var o_cal = new tcal ({\n";
$MAIN.="	// form name\n";
$MAIN.="	'formname': 'vicidial_report',\n";
$MAIN.="	// input name\n";
$MAIN.="	'controlname': 'query_date'\n";
$MAIN.="});\n";
$MAIN.="o_cal.a_tpl.yearscroll = false;\n";
$MAIN.="// o_cal.a_tpl.weekstart = 1; // Monday week start\n";
$MAIN.="</script>\n";

$MAIN.=" to <INPUT TYPE=TEXT NAME=end_date SIZE=10 MAXLENGTH=10 VALUE=\"$end_date\">";

$MAIN.="<script language=\"JavaScript\">\n";
$MAIN.="var o_cal = new tcal ({\n";
$MAIN.="	// form name\n";
$MAIN.="	'formname': 'vicidial_report',\n";
$MAIN.="	// input name\n";
$MAIN.="	'controlname': 'end_date'\n";
$MAIN.="});\n";
$MAIN.="o_cal.a_tpl.yearscroll = false;\n";
$MAIN.="// o_cal.a_tpl.weekstart = 1; // Monday week start\n";
$MAIN.="</script>\n";

$MAIN.="<SELECT SIZE=5 NAME=group[] multiple>\n";
$MAIN.="<option $all_selected value=\"--ALL--\">--ALL INGROUPS--</option>\n";
	$o=0;
while ($groups_to_print > $o)
	{
	$selected="";
	for ($i=0; $i<$groups_selected; $i++) {
		echo "<!-- $groups[$o] == $group[$i] //-->\n";
		if ($groups[$o] == $group[$i]) {$selected="selected";}
	}
	$MAIN.="<option $selected value=\"$groups[$o]\">$groups[$o] - $group_names[$o]</option>\n";
	$o++;
	}
$MAIN.="</SELECT>\n";
$MAIN.=" &nbsp;";
$MAIN.="<select name='report_display_type'>";
if ($report_display_type) {$MAIN.="<option value='$report_display_type' selected>$report_display_type</option>";}
$MAIN.="<option value='TEXT'>TEXT</option><option value='HTML'>HTML</option></select>&nbsp; ";


if ($IDR_calltime_available==1)
	{
	$MAIN.="<SELECT SIZE=1 NAME=shift>\n";
	$MAIN.="<option value=\"\">--</option>\n";
	$o=0;
	while ($times_to_print > $o)
		{
		if ($call_times[$o] == $shift) {$MAIN.="<option selected value=\"$call_times[$o]\">$call_times[$o] - $call_time_names[$o]</option>\n";}
		else {$MAIN.="<option value=\"$call_times[$o]\">$call_times[$o] - $call_time_names[$o]</option>\n";}
		$o++;
		}
	$MAIN.="</SELECT>\n";
	}

$MAIN.="<INPUT TYPE=submit NAME=SUBMIT VALUE=SUBMIT></TD></TR>\n";
$MAIN.="<TR><TD align='left'><FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2><INPUT TYPE=checkbox NAME=hourly_breakdown VALUE='checked' $hourly_breakdown>Show hourly results<BR><INPUT TYPE=checkbox NAME=show_disposition_statuses VALUE='checked' $show_disposition_statuses>Show disposition statuses<BR><INPUT TYPE=checkbox NAME=ignore_afterhours VALUE='checked' $ignore_afterhours>Ignore after-hours calls</FONT></TD><TD align='right'><a href=\"$PHP_SELF?DB=$DB&query_date=$query_date&end_date=$end_date$selected_group_URLstr&shift=$shift&hourly_breakdown=$hourly_breakdown&show_disposition_statuses=$show_disposition_statuses&SUBMIT=$SUBMIT&file_download=1\">DOWNLOAD</a> | <a href=\"./admin.php?ADD=3111&group_id=$group\">MODIFY</a> | <a href=\"./admin.php?ADD=999999\">REPORTS</a></TD></TR>\n";
$MAIN.="<TR><TD colspan=2>";
$MAIN.="<PRE><FONT SIZE=2>\n\n";


if ($groups_selected==0)
	{
	$MAIN.="\n\n";
	$MAIN.="PLEASE SELECT AN IN-GROUP AND DATE RANGE ABOVE AND CLICK SUBMIT\n";
	echo "$HEADER";
	require("admin_header.php");
	echo "$MAIN";
	}

else
	{
	### FOR SHIFTS IT IS BEST TO STICK TO 15-MINUTE INCREMENTS FOR START TIMES ###
	if ($shift && $shift!="ALL") {
		# call_time_id | call_time_name              | call_time_comments | ct_default_start | ct_default_stop | ct_sunday_start | ct_sunday_stop | ct_monday_start | ct_monday_stop | ct_tuesday_start | ct_tuesday_stop | ct_wednesday_start | ct_wednesday_stop | ct_thursday_start | ct_thursday_stop | ct_friday_start | ct_friday_stop | ct_saturday_start | ct_saturday_stop
		$big_shift_time_SQL_clause ="";
		$shift_stmt="select * from vicidial_call_times where call_time_id='$shift'";
		$shift_rslt=mysql_query($shift_stmt, $link);
		$shift_row=mysql_fetch_array($shift_rslt);
		$default_start_time=substr("0000".$shift_row["ct_default_start"], -4);
		$default_stop_time=substr("0000".$shift_row["ct_default_stop"], -4);
		$ct_default_start=substr($default_start_time,0,2).":".substr($default_start_time,2,2).":00";
		$ct_default_stop=substr($default_stop_time,0,2).":".substr($default_stop_time,2,2).":00";

		if ($shift_row["ct_sunday_start"]!=$shift_row["ct_sunday_stop"]) {
			$sunday_start_time=substr("0000".$shift_row["ct_sunday_start"], -4);
			$sunday_stop_time=substr("0000".$shift_row["ct_sunday_stop"], -4);
			$ct_sunday_start=substr($sunday_start_time,0,2).":".substr($sunday_start_time,2,2).":00";
			$ct_sunday_stop=substr($sunday_stop_time,0,2).":".substr($sunday_stop_time,2,2).":00";
		} else if ($shift_row["ct_sunday_start"]!="2400") {
			$ct_sunday_start=$ct_default_start;
			$ct_sunday_stop=$ct_default_stop;
		} else {
			$ct_sunday_start="00:00:00";
			$ct_sunday_stop=1;
		}
		$stop_time_stmt="select TIMEDIFF('$ct_sunday_stop', 1)";  # subtract one second - don't allow the actual final time - this can cause an extra row to print
		$stop_time_rslt=mysql_query($stop_time_stmt, $link);
		$strow=mysql_fetch_row($stop_time_rslt);
		$ct_sunday_stop=$strow[0];
		$start_time_ary[0]=$ct_sunday_start;
		$stop_time_ary[0]=$ct_sunday_stop;
		$big_shift_time_SQL_clause.="(date_format(call_date, '%w')=0 and date_format(call_date, '%H:%i:%s')>='$ct_sunday_start' and date_format(call_date, '%H:%i:%s')<='$ct_sunday_stop') or ";

		if ($shift_row["ct_monday_start"]!=$shift_row["ct_monday_stop"]) {
			$monday_start_time=substr("0000".$shift_row["ct_monday_start"], -4);
			$monday_stop_time=substr("0000".$shift_row["ct_monday_stop"], -4);
			$ct_monday_start=substr($monday_start_time,0,2).":".substr($monday_start_time,2,2).":00";
			$ct_monday_stop=substr($monday_stop_time,0,2).":".substr($monday_stop_time,2,2).":00";
		} else if ($shift_row["ct_monday_start"]!="2400") {
			$ct_monday_start=$ct_default_start;
			$ct_monday_stop=$ct_default_stop;
		} else {
			$ct_monday_start="00:00:00";
			$ct_monday_stop=1;
		}
		$stop_time_stmt="select TIMEDIFF('$ct_monday_stop', 1)";  # subtract one second - don't allow the actual final time - this can cause an extra row to print
		$stop_time_rslt=mysql_query($stop_time_stmt, $link);
		$strow=mysql_fetch_row($stop_time_rslt);
		$ct_monday_stop=$strow[0];
		$start_time_ary[1]=$ct_monday_start;
		$stop_time_ary[1]=$ct_monday_stop;
		$big_shift_time_SQL_clause.="(date_format(call_date, '%w')=1 and date_format(call_date, '%H:%i:%s')>='$ct_monday_start' and date_format(call_date, '%H:%i:%s')<='$ct_monday_stop') or ";

		if ($shift_row["ct_tuesday_start"]!=$shift_row["ct_tuesday_stop"]) {
			$tuesday_start_time=substr("0000".$shift_row["ct_tuesday_start"], -4);
			$tuesday_stop_time=substr("0000".$shift_row["ct_tuesday_stop"], -4);
			$ct_tuesday_start=substr($tuesday_start_time,0,2).":".substr($tuesday_start_time,2,2).":00";
			$ct_tuesday_stop=substr($tuesday_stop_time,0,2).":".substr($tuesday_stop_time,2,2).":00";
		} else if ($shift_row["ct_tuesday_start"]!="2400") {
			$ct_tuesday_start=$ct_default_start;
			$ct_tuesday_stop=$ct_default_stop;
		} else {
			$ct_tuesday_start="00:00:00";
			$ct_tuesday_stop=1;
		}
		$stop_time_stmt="select TIMEDIFF('$ct_tuesday_stop', 1)";  # subtract one second - don't allow the actual final time - this can cause an extra row to print
		$stop_time_rslt=mysql_query($stop_time_stmt, $link);
		$strow=mysql_fetch_row($stop_time_rslt);
		$ct_tuesday_stop=$strow[0];
		$start_time_ary[2]=$ct_tuesday_start;
		$stop_time_ary[2]=$ct_tuesday_stop;
		$big_shift_time_SQL_clause.="(date_format(call_date, '%w')=2 and date_format(call_date, '%H:%i:%s')>='$ct_tuesday_start' and date_format(call_date, '%H:%i:%s')<='$ct_tuesday_stop') or ";

		if ($shift_row["ct_wednesday_start"]!=$shift_row["ct_wednesday_stop"]) {
			$wednesday_start_time=substr("0000".$shift_row["ct_wednesday_start"], -4);
			$wednesday_stop_time=substr("0000".$shift_row["ct_wednesday_stop"], -4);
			$ct_wednesday_start=substr($wednesday_start_time,0,2).":".substr($wednesday_start_time,2,2).":00";
			$ct_wednesday_stop=substr($wednesday_stop_time,0,2).":".substr($wednesday_stop_time,2,2).":00";
		} else if ($shift_row["ct_wednesday_start"]!="2400") {
			$ct_wednesday_start=$ct_default_start;
			$ct_wednesday_stop=$ct_default_stop;
		} else {
			$ct_wednesday_start="00:00:00";
			$ct_wednesday_stop=1;
		}
		$stop_time_stmt="select TIMEDIFF('$ct_wednesday_stop', 1)";  # subtract one second - don't allow the actual final time - this can cause an extra row to print
		$stop_time_rslt=mysql_query($stop_time_stmt, $link);
		$strow=mysql_fetch_row($stop_time_rslt);
		$ct_wednesday_stop=$strow[0];
		$start_time_ary[3]=$ct_wednesday_start;
		$stop_time_ary[3]=$ct_wednesday_stop;
		$big_shift_time_SQL_clause.="(date_format(call_date, '%w')=3 and date_format(call_date, '%H:%i:%s')>='$ct_wednesday_start' and date_format(call_date, '%H:%i:%s')<='$ct_wednesday_stop') or ";

		if ($shift_row["ct_thursday_start"]!=$shift_row["ct_thursday_stop"]) {
			$thursday_start_time=substr("0000".$shift_row["ct_thursday_start"], -4);
			$thursday_stop_time=substr("0000".$shift_row["ct_thursday_stop"], -4);
			$ct_thursday_start=substr($thursday_start_time,0,2).":".substr($thursday_start_time,2,2).":00";
			$ct_thursday_stop=substr($thursday_stop_time,0,2).":".substr($thursday_stop_time,2,2).":00";
		} else if ($shift_row["ct_thursday_start"]!="2400") {
			$ct_thursday_start=$ct_default_start;
			$ct_thursday_stop=$ct_default_stop;
		} else {
			$ct_thursday_start="00:00:00";
			$ct_thursday_stop=1;
		}
		$stop_time_stmt="select TIMEDIFF('$ct_thursday_stop', 1)";  # subtract one second - don't allow the actual final time - this can cause an extra row to print
		$stop_time_rslt=mysql_query($stop_time_stmt, $link);
		$strow=mysql_fetch_row($stop_time_rslt);
		$ct_thursday_stop=$strow[0];
		$start_time_ary[4]=$ct_thursday_start;
		$stop_time_ary[4]=$ct_thursday_stop;
		$big_shift_time_SQL_clause.="(date_format(call_date, '%w')=4 and date_format(call_date, '%H:%i:%s')>='$ct_thursday_start' and date_format(call_date, '%H:%i:%s')<='$ct_thursday_stop') or ";

		if ($shift_row["ct_friday_start"]!=$shift_row["ct_friday_stop"]) {
			$friday_start_time=substr("0000".$shift_row["ct_friday_start"], -4);
			$friday_stop_time=substr("0000".$shift_row["ct_friday_stop"], -4);
			$ct_friday_start=substr($friday_start_time,0,2).":".substr($friday_start_time,2,2).":00";
			$ct_friday_stop=substr($friday_stop_time,0,2).":".substr($friday_stop_time,2,2).":00";
		} else if ($shift_row["ct_friday_start"]!="2400") {
			$ct_friday_start=$ct_default_start;
			$ct_friday_stop=$ct_default_stop;
		} else {
			$ct_friday_start="00:00:00";
			$ct_friday_stop=1;
		}
		$stop_time_stmt="select TIMEDIFF('$ct_friday_stop', 1)";  # subtract one second - don't allow the actual final time - this can cause an extra row to print
		$stop_time_rslt=mysql_query($stop_time_stmt, $link);
		$strow=mysql_fetch_row($stop_time_rslt);
		$ct_friday_stop=$strow[0];
		$start_time_ary[5]=$ct_friday_start;
		$stop_time_ary[5]=$ct_friday_stop;
		$big_shift_time_SQL_clause.="(date_format(call_date, '%w')=5 and date_format(call_date, '%H:%i:%s')>='$ct_friday_start' and date_format(call_date, '%H:%i:%s')<='$ct_friday_stop') or ";

		if ($shift_row["ct_saturday_start"]!=$shift_row["ct_saturday_stop"]) {
			$saturday_start_time=substr("0000".$shift_row["ct_saturday_start"], -4);
			$saturday_stop_time=substr("0000".$shift_row["ct_saturday_stop"], -4);
			$ct_saturday_start=substr($saturday_start_time,0,2).":".substr($saturday_start_time,2,2).":00";
			$ct_saturday_stop=substr($saturday_stop_time,0,2).":".substr($saturday_stop_time,2,2).":00";
		} else if ($shift_row["ct_saturday_start"]!="2400") {
			$ct_saturday_start=$ct_default_start;
			$ct_saturday_stop=$ct_default_stop;
		} else {
			$ct_saturday_start="00:00:00";
			$ct_saturday_stop=1;
		}
		$stop_time_stmt="select TIMEDIFF('$ct_saturday_stop', 1)";  # subtract one second - don't allow the actual final time - this can cause an extra row to print
		$stop_time_rslt=mysql_query($stop_time_stmt, $link);
		$strow=mysql_fetch_row($stop_time_rslt);
		$ct_saturday_stop=$strow[0];
		$start_time_ary[6]=$ct_saturday_start;
		$stop_time_ary[6]=$ct_saturday_stop;
		$big_shift_time_SQL_clause.="(date_format(call_date, '%w')=6 and date_format(call_date, '%H:%i:%s')>='$ct_saturday_start' and date_format(call_date, '%H:%i:%s')<='$ct_saturday_stop') or ";

		$query_time_stmt="select date_format('$query_date', '%w'), date_format('$end_date', '%w')";
		$query_time_rslt=mysql_query($query_time_stmt, $link);
		$qrow=mysql_fetch_row($query_time_rslt);
		$time_BEGIN=$start_time_ary[$qrow[0]];
		$time_END=$stop_time_ary[$qrow[0]];
		#$time_BEGIN="00:00:00"; # Need this so the $SQepoch value can be tweaked per day (i.e. only adding the hours for the start time);

		$query_date_BEGIN = "$query_date ".$time_BEGIN;   
		$query_date_END = "$end_date ".$time_END;
		RecalculateHPD($query_date_BEGIN, $query_date_END, $time_BEGIN, $time_END); # Only calling it here to get the DURATIONday, EQepoch, and intial SQepoch
		# Will be called repeatedly for each day

		$big_shift_time_SQL_clause=preg_replace('/\sor\s$/', '', $big_shift_time_SQL_clause);
		$big_shift_time_SQL_clause=" and ($big_shift_time_SQL_clause)";
		#echo $big_shift_time_SQL_clause;
		#print_r($start_time_ary); echo "<BR>";
		#print_r($stop_time_ary); echo "<BR>";
	} else {

		if ($shift == 'ALL') 
			{
			if (strlen($time_BEGIN) < 6) {$time_BEGIN = "00:00:00";}
			if (strlen($time_END) < 6) {$time_END = "23:59:59";}
			}

		$query_date_BEGIN = "$query_date $time_BEGIN";   
		$query_date_END = "$end_date $time_END";

		RecalculateHPD($query_date_BEGIN, $query_date_END, $time_BEGIN, $time_END);
	}

	$MAIN.="Inbound Daily Report                      $NOW_TIME\n";
	$MAIN.="Selected in-groups: $group_name_str\n";
	if ($shift && $IDR_calltime_available) {$MAIN.="Selected shift: $shift\n";}
	$MAIN.="Time range $DURATIONday days: $query_date_BEGIN to $query_date_END";
	if ($shift && $IDR_calltime_available) {$MAIN.="for $shift shift";}
	$MAIN.="\n\n";
	#echo "Time range day sec: $SQsec - $EQsec   Day range in epoch: $SQepoch - $EQepoch   Start: $SQepochDAY\n";
	$CSV_text.="\"Inbound Daily Report\",\"$NOW_TIME\"\n";
	$CSV_text.="Selected in-groups: $group_name_str\n";
	if ($shift && $IDR_calltime_available) {$CSV_text.="Selected shift: $shift\n";}
	$CSV_text.="\"Time range $DURATIONday days:\",\"$query_date_BEGIN to $query_date_END\"";
	if ($shift && $IDR_calltime_available) {$CSV_text.="for $shift shift";}
	$CSV_text.="\n\n";

	if ($show_disposition_statuses) {
		$dispo_stmt="select distinct status from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and  campaign_id in (" . $groups_selected_str . ") $big_shift_time_SQL_clause order by status;";
		#echo $dispo_stmt."<BR>";
		$dispo_rslt=mysql_query($dispo_stmt, $link);
		$dispo_str="";
		$s=0;
		while($dispo_row=mysql_fetch_row($dispo_rslt)) {
			$status_array[$s][0]="$dispo_row[0]";
			$status_array[$s][1]="";
			$dispo_str.="'$dispo_row[0]',";
			$stat_stmt="select distinct status, status_name from vicidial_statuses where status='$dispo_row[0]' UNION select distinct status, status_name from vicidial_campaign_statuses where status='$dispo_row[0]' order by status;";
			#echo $stat_stmt."<BR>";
			$stat_rslt=mysql_query($stat_stmt, $link);
			while ($stat_row=mysql_fetch_array($stat_rslt)) {
				$status_array[$s][1]=$stat_row["status_name"];
			}
			$s++;
		}
		$dispo_str=substr($dispo_str,0,-1);
		asort($status_array);
		#print_r($status_array);
#		if (strlen($dispo_str)>0) {
#		}
	}

	$d=0; $q=0; $hr=0; $shift_hrs=0;
	while ($d < $DURATIONday)
		{
		$dSQepoch = ($SQepoch + ($d * 86400) + ($hr * 3600));
		
		if ($shift && $shift!="ALL" && $RECALC==1) 
			{
			# Need to get current day, hours for that day (hpd)
			$current_dayofweek=date("w", $dSQepoch);
			$time_BEGIN=$start_time_ary[$current_dayofweek];
			$time_END=$stop_time_ary[$current_dayofweek];
			HourDifference($time_BEGIN, $time_END); # new hpd
			$query_date_BEGIN = "$query_date $time_BEGIN";   
			$query_date_END = "$end_date $time_END";
			RecalculateEpochs($query_date_BEGIN, $query_date_END);
			$dSQepoch = ($SQepoch + ($d * 86400) + ($hr * 3600) + ($shift_hrs * 3600) );
			#echo " --- NEW DAY: $query_date_BEGIN / $query_date_END --- <BR>";
			$RECALC=0;
			}


		if ($hourly_breakdown) 
			{
			$dEQepoch = $dSQepoch+3599;
			}
			else
			{
			$dEQepoch = ($SQepochDAY + ($EQsec + ($d * 86400) + ($hr * 3600)) );
			if ($EQsec < $SQsec)
				{
				$dEQepoch = ($dEQepoch + 86400);
				}
			}

		#echo "$dSQepoch - $dEQepoch, ".date("Y-m-d H:i:s", $dSQepoch)." - ".date("Y-m-d H:i:s", $dEQepoch).", ".date("D", $dSQepoch)." - $hpd hours - shift starts at $shift_hrs:00:00<BR>";
		$daySTART[$q] = date("Y-m-d H:i:s", $dSQepoch);
		$dayEND[$q] = date("Y-m-d H:i:s", $dEQepoch);

		if ($hr>=($hpd-1) || !$hourly_breakdown) 
			{
			$d++;
			$hr=0;
			if (date("H:i:s", $dEQepoch)>$time_END) 
				{
				$dayEND[$q] = date("Y-m-d ", $dEQepoch).$time_END;
				}
			$RECALC=1;
			}
			else
			{
			$hr++;
			}
		#$MAIN.="$daySTART[$q] - $dayEND[$q] | $SQepochDAY,".date("Y-m-d H:i:s",$SQepochDAY)."\n";
		$q++;

		}
	$prev_week=$daySTART[0];
	$prev_month=$daySTART[0];
	$prev_qtr=$daySTART[0];
	##########################################################################
	#########  CALCULATE ALL OF THE 15-MINUTE PERIODS NEEDED FOR ALL DAYS ####

	### BUILD HOUR:MIN DISPLAY ARRAY ###
	$i=0;
	$h=4;
	$j=0;
	$Zhour=1;
	$active_time=0;
	$hour =		($SQtime_ARY[0] - 1);
	$startSEC = ($SQsec - 900);
	$endSEC =	($SQsec - 1);
	if ($SQtime_ARY[1] > 14) 
		{
		$h=1;
		$hour++;
		if ($hour < 10) {$hour = "0$hour";}
		}
	if ($SQtime_ARY[1] > 29) {$h=2;}
	if ($SQtime_ARY[1] > 44) {$h=3;}
	while ($i < 96)
		{
		$startSEC = ($startSEC + 900);
		$endSEC = ($endSEC + 900);
		$time = '      ';
		if ($h >= 4)
			{
			$hour++;
			if ($Zhour == '00') 
				{
				$startSEC=0;
				$endSEC=899;
				}
			$h=0;
			if ($hour < 10) {$hour = "0$hour";}
			$Stime="$hour:00";
			$Etime="$hour:15";
			$time = "+$Stime-$Etime+";
			}
		if ($h == 1)
			{
			$Stime="$hour:15";
			$Etime="$hour:30";
			$time = " $Stime-$Etime ";
			}
		if ($h == 2)
			{
			$Stime="$hour:30";
			$Etime="$hour:45";
			$time = " $Stime-$Etime ";
			}
		if ($h == 3)
			{
			$Zhour=$hour;
			$Zhour++;
			if ($Zhour < 10) {$Zhour = "0$Zhour";}
			if ($Zhour == 24) {$Zhour = "00";}
			$Stime="$hour:45";
			$Etime="$Zhour:00";
			$time = " $Stime-$Etime ";
			if ($Zhour == '00') 
				{$hour = ($Zhour - 1);}
			}

		if ( ( ($startSEC >= $SQsec) and ($endSEC <= $EQsec) and ($EQsec > $SQsec) ) or 
			( ($startSEC >= $SQsec) and ($EQsec < $SQsec) ) or 
			( ($endSEC <= $EQsec) and ($EQsec < $SQsec) ) )
			{
			$HMdisplay[$j] =	$time;
			$HMstart[$j] =		$Stime;
			$HMend[$j] =		$Etime;
			$HMSepoch[$j] =		$startSEC;
			$HMEepoch[$j] =		$endSEC;

			$j++;
			}

		$h++;
		$i++;
		}

	$TOTintervals = $q;


	### GRAB ALL RECORDS WITHIN RANGE FROM THE DATABASE ###
	$stmt="select queue_seconds,UNIX_TIMESTAMP(call_date),length_in_sec,status,term_reason,call_date,user from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and  campaign_id in (" . $groups_selected_str . ");";
	$rslt=mysql_query($stmt, $link);
	if ($DB) {$ASCII_text.="$stmt\n";}
	$records_to_grab = mysql_num_rows($rslt);
	$i=0;
	$fTOTAL_agents=array();
	if($hourly_breakdown) {$epoch_interval=3600;} else {$epoch_interval=86400;}
	while ($i < $records_to_grab)
		{
		$row=mysql_fetch_row($rslt);
		$qs[$i] = $row[0];
		$dt[$i] = 0;
		$ut[$i] = ($row[1] - $SQepochDAY);
		while($ut[$i] >= $epoch_interval) 
			{
			$ut[$i] = ($ut[$i] - $epoch_interval);
			$dt[$i]++;
			}
		if ( ($ut[$i] <= $EQsec) and ($EQsec < $SQsec) )
			{
			$dt[$i] = ($dt[$i] - 1);
			}
		$ls[$i] = $row[2];
		$st[$i] = $row[3];
		$tr[$i] = $row[4];
		$at[$i] = $row[5]; # Actual time
		if ($row[6]!="VDCL" && $row[6]!="") {$ag[$i] = $row[6];} # User
		# $fTOTAL_agents["$row[6]"]++;
		# $ASCII_text.= "$qs[$i] | $dt[$i] - $row[1] | $ut[$i] | $ls[$i] | $st[$i] | $tr[$i] | $at[$i]\n";

		$i++;
		}

	### PARSE THROUGH ALL RECORDS AND GENERATE STATS ###
	$MT[0]='0';
	$totCALLS=0;
	$totDROPS=0;
	$totQUEUE=0;
	$totCALLSsec=0;
	$totDROPSsec=0;
	$totQUEUEsec=0;
	$totCALLSmax=0;
	$totDROPSmax=0;
	$totQUEUEmax=0;
	$totCALLSdate=$MT;
	$totDROPSdate=$MT;
	$totQUEUEdate=$MT;
	$qrtCALLS=$MT;
	$qrtDROPS=$MT;
	$qrtQUEUE=$MT;
	$qrtCALLSsec=$MT;
	$qrtDROPSsec=$MT;
	$qrtQUEUEsec=$MT;
	$qrtCALLSavg=$MT;
	$qrtDROPSavg=$MT;
	$qrtQUEUEavg=$MT;
	$qrtCALLSmax=$MT;
	$qrtDROPSmax=$MT;
	$qrtQUEUEmax=$MT;

	$totABANDONSdate=$MT;
	$totANSWERSdate=$MT;

	$totANSWERS=0;
	$totAGENTS=0;
	$totABANDONS=0;
	$totANSWERSsec=0;
	$totABANDONSsec=0;
	$totANSWERSspeed=0;
	$totSTATUSES=array();

	$FtotANSWERS=0;
	$FtotAGENTS=count(array_count_values($ag));
	$FtotABANDONS=0;
	$FtotANSWERSsec=0;
	$FtotABANDONSsec=0;
	$FtotANSWERSspeed=0;

	$j=0;
	while ($j < $TOTintervals)
		{
	#	$jd__0[$j]=0; $jd_20[$j]=0; $jd_40[$j]=0; $jd_60[$j]=0; $jd_80[$j]=0; $jd100[$j]=0; $jd120[$j]=0; $jd121[$j]=0;
	#	$Phd__0[$j]=0; $Phd_20[$j]=0; $Phd_40[$j]=0; $Phd_60[$j]=0; $Phd_80[$j]=0; $Phd100[$j]=0; $Phd120[$j]=0; $Phd121[$j]=0;
	#	$qrtCALLS[$j]=0; $qrtCALLSsec[$j]=0; $qrtCALLSmax[$j]=0;
	#	$qrtDROPS[$j]=0; $qrtDROPSsec[$j]=0; $qrtDROPSmax[$j]=0;
	#	$qrtQUEUE[$j]=0; $qrtQUEUEsec[$j]=0; $qrtQUEUEmax[$j]=0;
		$totABANDONSdate[$j]=0;
		$totABANDONSsecdate[$j]=0;
		$totANSWERSdate[$j]=0;
		$totANSWERSsecdate[$j]=0;
		$totANSWERSspeeddate[$j]=0;
		$i=0;
		$agents_array=array();
		while ($i < $records_to_grab)
			{
			if ( ($at[$i] >= $daySTART[$j]) and ($at[$i] <= $dayEND[$j]) )
				{
				$totCALLS++;
				$totCALLSsec = ($totCALLSsec + $ls[$i]);
				$totCALLSsecDATE[$j] = ($totCALLSsecDATE[$j] + $ls[$i]);
	#			$qrtCALLS[$j]++;
	#			$qrtCALLSsec[$j] = ($qrtCALLSsec[$j] + $ls[$i]);
	#			$dtt = $dt[$i];
				$totCALLSdate[$j]++;
				# $totAGENTSdate[$j]=$ag[$i];
				if ($ag[$i]!="VDCL" && $ag[$i]!="") {$totAGENTSdate[$j][$ag[$i]]++;}

				$totSTATUSES[$st[$i]]++;
				$totSTATUSESdate[$j][$st[$i]]++;

				if ($totCALLSmax < $ls[$i]) {$totCALLSmax = $ls[$i];}
				if ($qrtCALLSmax[$j] < $ls[$i]) {$qrtCALLSmax[$j] = $ls[$i];}
				if (ereg('ABANDON|NOAGENT|QUEUETIMEOUT|AFTERHOURS|MAXCALLS', $tr[$i])) 
					{
					$totABANDONSdate[$j]++;
					$totABANDONSsecdate[$j]+=$ls[$i];
					$FtotABANDONS++;
					$FtotABANDONSsec+=$ls[$i];
					}
					else 
					{
					$totANSWERSdate[$j]++;
					if (($ls[$i]-$qs[$i]-15)>0) {  ## Patch by Joe J - can cause negative time values if removed.
						$totANSWERSsecdate[$j]+=($ls[$i]-$qs[$i]-15);
						$FtotANSWERSsec+=($ls[$i]-$qs[$i]-15);
					}
					$totANSWERSspeeddate[$j]+=$qs[$i];
					$FtotANSWERS++;
					$FtotANSWERSspeeddate+=$qs[$i];
					}
				if (ereg('DROP',$st[$i])) 
					{
					$totDROPS++;
					$totDROPSsec = ($totDROPSsec + $ls[$i]);
					$totDROPSsecDATE[$j] = ($totDROPSsecDATE[$j] + $ls[$i]);
	#				$qrtDROPS[$j]++;
	#				$qrtDROPSsec[$j] = ($qrtDROPSsec[$j] + $ls[$i]);
					$totDROPSdate[$j]++;
	#				if ($totDROPSmax < $ls[$i]) {$totDROPSmax = $ls[$i];}
	#				if ($qrtDROPSmax[$j] < $ls[$i]) {$qrtDROPSmax[$j] = $ls[$i];}
					}
				if ($qs[$i] > 0) 
					{
					$totQUEUE++;
					$totQUEUEsec = ($totQUEUEsec + $qs[$i]);
					$totQUEUEsecDATE[$j] = ($totQUEUEsecDATE[$j] + $qs[$i]);
	#				$qrtQUEUE[$j]++;
	#				$qrtQUEUEsec[$j] = ($qrtQUEUEsec[$j] + $qs[$i]);
					$totQUEUEdate[$j]++;
	#				if ($totQUEUEmax < $qs[$i]) {$totQUEUEmax = $qs[$i];}
	#				if ($qrtQUEUEmax[$j] < $qs[$i]) {$qrtQUEUEmax[$j] = $qs[$i];}
					}
	/*
				if ($qs[$i] == 0) {$hd__0[$j]++;}
				if ( ($qs[$i] > 0) and ($qs[$i] <= 20) ) {$hd_20[$j]++;}
				if ( ($qs[$i] > 20) and ($qs[$i] <= 40) ) {$hd_40[$j]++;}
				if ( ($qs[$i] > 40) and ($qs[$i] <= 60) ) {$hd_60[$j]++;}
				if ( ($qs[$i] > 60) and ($qs[$i] <= 80) ) {$hd_80[$j]++;}
				if ( ($qs[$i] > 80) and ($qs[$i] <= 100) ) {$hd100[$j]++;}
				if ( ($qs[$i] > 100) and ($qs[$i] <= 120) ) {$hd120[$j]++;}
				if ($qs[$i] > 120) {$hd121[$j]++;}
	*/
				}
			
			$i++;
			}

		$j++;
		}


	###################################################
	### TOTALS SUMMARY SECTION ###
	$MAINH="+-------------------------------------------+---------+----------+----------+-----------+---------+---------+--------+--------+------------+------------+------------+";
	$MAIN1="|                                           | TOTAL   | TOTAL    | TOTAL    | TOTAL     | TOTAL   | AVG     | AVG    | AVG    | TOTAL      | TOTAL      | TOTAL      |";
	$MAIN2="| SHIFT                                     | CALLS   | CALLS    | AGENTS   | CALLS     | ABANDON | ABANDON | ANSWER | TALK   | TALK       | WRAP       | CALL       |";
	$MAIN3="| DATE-TIME RANGE                           | OFFERED | ANSWERED | ANSWERED | ABANDONED | PERCENT | TIME    | SPEED  | TIME   | TIME       | TIME       | TIME       |";
	$CSV_text1="";
	$CSV_text2="";
	$CSV_text3="";

	for ($s=0; $s<count($status_array); $s++) {
		$status_name=explode(" ", $status_array[$s][1]);
		for ($j=2; $j<count($status_name); $j++) {
			$status_name[1].=" $status_name[$j]";
		}
		$MAINH.="------------+";
		$MAIN1.=" ".sprintf("%-10s", strtoupper($status_array[$s][0]))." |";
		$MAIN2.=" ".sprintf("%-10s", substr($status_name[0],0,10))." |";
		$MAIN3.=" ".sprintf("%-10s", substr($status_name[1],0,10))." |";
		$CSV_text1.=",\"".strtoupper($status_array[$s][0])."\"";
		$CSV_text2.=",\"$status_name[0]\"";
		$CSV_text3.=",\"$status_name[1]\"";
	}
	
	$ASCII_text.="$MAINH\n";
	$ASCII_text.="$MAIN1\n";
	$ASCII_text.="$MAIN2\n";
	$ASCII_text.="$MAIN3\n";
	$ASCII_text.="$MAINH\n";

	$CSV_text.="\"\",\"TOTAL\",\"TOTAL\",\"TOTAL\",\"TOTAL\",\"TOTAL\",\"AVG\",\"AVG\",\"AVG\",\"TOTAL\",\"TOTAL\",\"TOTAL\"$CSV_text1\n";
	$CSV_text.="\"\",\"CALLS\",\"CALLS\",\"AGENTS\",\"CALLS\",\"ABANDON\",\"ABANDON\",\"ANSWER\",\"TALK\",\"TALK\",\"WRAP\",\"CALL\"$CSV_text2\n";
	$CSV_text.="\"SHIFT DATE-TIME RANGE\",\"OFFERED\",\"ANSWERED\",\"ANSWERED\",\"ABANDONED\",\"PERCENT\",\"TIME\",\"SPEED\",\"TIME\",\"TIME\",\"TIME\",\"TIME\"$CSV_text3\n";

	##########################
	$JS_text="<script language='Javascript'>\n";
	$JS_onload="onload = function() {\n";
	$graph_stats=array();
	$mtd_graph_stats=array();
	$wtd_graph_stats=array();
	$qtd_graph_stats=array();
	$da=0; $wa=0; $ma=0; $qa=0;
	$max_offered=1;
	$max_answered=1;
	$max_agents=1;
	$max_abandoned=1;
	$max_abandonpct=1;
	$max_avgabandontime=1;
	$max_avganswerspeed=1;
	$max_avgtalktime=1;
	$max_totaltalktime=1;
	$max_totalwraptime=1;
	$max_totalcalltime=1;
	$max_wtd_offered=1;
	$max_wtd_answered=1;
	$max_wtd_agents=1;
	$max_wtd_abandoned=1;
	$max_wtd_abandonpct=1;
	$max_wtd_avgabandontime=1;
	$max_wtd_avganswerspeed=1;
	$max_wtd_avgtalktime=1;
	$max_wtd_totaltalktime=1;
	$max_wtd_totalwraptime=1;
	$max_wtd_totalcalltime=1;
	$max_mtd_offered=1;
	$max_mtd_answered=1;
	$max_mtd_abandoned=1;
	$max_mtd_agents=1;
	$max_mtd_abandonpct=1;
	$max_mtd_avgabandontime=1;
	$max_mtd_avganswerspeed=1;
	$max_mtd_avgtalktime=1;
	$max_mtd_totaltalktime=1;
	$max_mtd_totalwraptime=1;
	$max_mtd_totalcalltime=1;
	$max_qtd_offered=1;
	$max_qtd_answered=1;
	$max_qtd_abandoned=1;
	$max_qtd_agents=1;
	$max_qtd_abandonpct=1;
	$max_qtd_avgabandontime=1;
	$max_qtd_avganswerspeed=1;
	$max_qtd_avgtalktime=1;
	$max_qtd_totaltalktime=1;
	$max_qtd_totalwraptime=1;
	$max_qtd_totalcalltime=1;
	$GRAPH="<a name='multigroup_graph'/><table border='0' cellpadding='0' cellspacing='2' width='800'><tr><th width='10%' class='grey_graph_cell' id='multigroup_graph1'><a href='#' onClick=\"DrawGraph('OFFERED', '1'); return false;\">TOTAL CALLS OFFERED</a></th><th width='10%' class='grey_graph_cell' id='multigroup_graph2'><a href='#' onClick=\"DrawGraph('ANSWERED', '2'); return false;\">TOTAL CALLS ANSWERED</a></th><th width='10%' class='grey_graph_cell' id='multigroup_graph11'><a href='#' onClick=\"DrawGraph('AGENTS', '11'); return false;\">TOTAL AGENTS ANSWERED</a></th><th width='10%' class='grey_graph_cell' id='multigroup_graph3'><a href='#' onClick=\"DrawGraph('ABANDONED', '3'); return false;\">TOTAL CALLS ABANDONED</a></th><th width='10%' class='grey_graph_cell' id='multigroup_graph4'><a href='#' onClick=\"DrawGraph('ABANDONPCT', '4'); return false;\">TOTAL ABANDON PERCENT</a></th><th width='10%' class='grey_graph_cell' id='multigroup_graph5'><a href='#' onClick=\"DrawGraph('AVGABANDONTIME', '5'); return false;\">AVG ABANDON TIME</a></th><th width='10%' class='grey_graph_cell' id='multigroup_graph6'><a href='#' onClick=\"DrawGraph('AVGANSWERSPEED', '6'); return false;\">AVG ANSWER SPEED</a></th><th width='10%' class='grey_graph_cell' id='multigroup_graph7'><a href='#' onClick=\"DrawGraph('AVGTALKTIME', '7'); return false;\">AVG TALK TIME</a></th><th width='10%' class='grey_graph_cell' id='multigroup_graph8'><a href='#' onClick=\"DrawGraph('TOTALTALKTIME', '8'); return false;\">TOTAL TALK TIME</a></th><th width='10%' class='grey_graph_cell' id='multigroup_graph9'><a href='#' onClick=\"DrawGraph('TOTALWRAPTIME', '9'); return false;\">TOTAL WRAP TIME</a></th><th width='10%' class='grey_graph_cell' id='multigroup_graph10'><a href='#' onClick=\"DrawGraph('TOTALCALLTIME', '10'); return false;\">TOTAL CALL TIME</a></th></tr><tr><td colspan='11' class='graph_span_cell' align='center'><span id='stats_graph'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";
	$MTD_GRAPH="<BR><BR><a name='MTD_graph'/><table border='0' cellpadding='0' cellspacing='2' width='800'><tr><th width='10%' class='grey_graph_cell' id='MTD_graph1'><a href='#' onClick=\"DrawMTDGraph('OFFERED', '1'); return false;\">TOTAL CALLS OFFERED</a></th><th width='10%' class='grey_graph_cell' id='MTD_graph2'><a href='#' onClick=\"DrawMTDGraph('ANSWERED', '2'); return false;\">TOTAL CALLS ANSWERED</a></th><th width='10%' class='grey_graph_cell' id='MTD_graph11'><a href='#' onClick=\"DrawMTDGraph('AGENTS', '11'); return false;\">TOTAL AGENTS ANSWERED</a></th><th width='10%' class='grey_graph_cell' id='MTD_graph3'><a href='#' onClick=\"DrawMTDGraph('ABANDONED', '3'); return false;\">TOTAL CALLS ABANDONED</a></th><th width='10%' class='grey_graph_cell' id='MTD_graph4'><a href='#' onClick=\"DrawMTDGraph('ABANDONPCT', '4'); return false;\">TOTAL ABANDON PERCENT</a></th><th width='10%' class='grey_graph_cell' id='MTD_graph5'><a href='#' onClick=\"DrawMTDGraph('AVGABANDONTIME', '5'); return false;\">AVG ABANDON TIME</a></th><th width='10%' class='grey_graph_cell' id='MTD_graph6'><a href='#' onClick=\"DrawMTDGraph('AVGANSWERSPEED', '6'); return false;\">AVG ANSWER SPEED</a></th><th width='10%' class='grey_graph_cell' id='MTD_graph7'><a href='#' onClick=\"DrawMTDGraph('AVGTALKTIME', '7'); return false;\">AVG TALK TIME</a></th><th width='10%' class='grey_graph_cell' id='MTD_graph8'><a href='#' onClick=\"DrawMTDGraph('TOTALTALKTIME', '8'); return false;\">TOTAL TALK TIME</a></th><th width='10%' class='grey_graph_cell' id='MTD_graph9'><a href='#' onClick=\"DrawMTDGraph('TOTALWRAPTIME', '9'); return false;\">TOTAL WRAP TIME</a></th><th width='10%' class='grey_graph_cell' id='MTD_graph10'><a href='#' onClick=\"DrawMTDGraph('TOTALCALLTIME', '10'); return false;\">TOTAL CALL TIME</a></th></tr><tr><td colspan='11' class='graph_span_cell' align='center'><span id='MTD_stats_graph'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";
	$WTD_GRAPH="<BR><BR><a name='WTD_graph'/><table border='0' cellpadding='0' cellspacing='2' width='800'><tr><th width='10%' class='grey_graph_cell' id='WTD_graph1'><a href='#' onClick=\"DrawWTDGraph('OFFERED', '1'); return false;\">TOTAL CALLS OFFERED</a></th><th width='10%' class='grey_graph_cell' id='WTD_graph2'><a href='#' onClick=\"DrawWTDGraph('ANSWERED', '2'); return false;\">TOTAL CALLS ANSWERED</a></th><th width='10%' class='grey_graph_cell' id='WTD_graph11'><a href='#' onClick=\"DrawWTDGraph('AGENTS', '11'); return false;\">TOTAL AGENTS ANSWERED</a></th><th width='10%' class='grey_graph_cell' id='WTD_graph3'><a href='#' onClick=\"DrawWTDGraph('ABANDONED', '3'); return false;\">TOTAL CALLS ABANDONED</a></th><th width='10%' class='grey_graph_cell' id='WTD_graph4'><a href='#' onClick=\"DrawWTDGraph('ABANDONPCT', '4'); return false;\">TOTAL ABANDON PERCENT</a></th><th width='10%' class='grey_graph_cell' id='WTD_graph5'><a href='#' onClick=\"DrawWTDGraph('AVGABANDONTIME', '5'); return false;\">AVG ABANDON TIME</a></th><th width='10%' class='grey_graph_cell' id='WTD_graph6'><a href='#' onClick=\"DrawWTDGraph('AVGANSWERSPEED', '6'); return false;\">AVG ANSWER SPEED</a></th><th width='10%' class='grey_graph_cell' id='WTD_graph7'><a href='#' onClick=\"DrawWTDGraph('AVGTALKTIME', '7'); return false;\">AVG TALK TIME</a></th><th width='10%' class='grey_graph_cell' id='WTD_graph8'><a href='#' onClick=\"DrawWTDGraph('TOTALTALKTIME', '8'); return false;\">TOTAL TALK TIME</a></th><th width='10%' class='grey_graph_cell' id='WTD_graph9'><a href='#' onClick=\"DrawWTDGraph('TOTALWRAPTIME', '9'); return false;\">TOTAL WRAP TIME</a></th><th width='10%' class='grey_graph_cell' id='WTD_graph10'><a href='#' onClick=\"DrawWTDGraph('TOTALCALLTIME', '10'); return false;\">TOTAL CALL TIME</a></th></tr><tr><td colspan='11' class='graph_span_cell' align='center'><span id='WTD_stats_graph'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";
	$QTD_GRAPH="<BR><BR><a name='QTD_graph'/><table border='0' cellpadding='0' cellspacing='2' width='800'><tr><th width='10%' class='grey_graph_cell' id='QTD_graph1'><a href='#' onClick=\"DrawQTDGraph('OFFERED', '1'); return false;\">TOTAL CALLS OFFERED</a></th><th width='10%' class='grey_graph_cell' id='QTD_graph2'><a href='#' onClick=\"DrawQTDGraph('ANSWERED', '2'); return false;\">TOTAL CALLS ANSWERED</a></th><th width='10%' class='grey_graph_cell' id='QTD_graph11'><a href='#' onClick=\"DrawQTDGraph('AGENTS', '11'); return false;\">TOTAL AGENTS ANSWERED</a></th><th width='10%' class='grey_graph_cell' id='QTD_graph3'><a href='#' onClick=\"DrawQTDGraph('ABANDONED', '3'); return false;\">TOTAL CALLS ABANDONED</a></th><th width='10%' class='grey_graph_cell' id='QTD_graph4'><a href='#' onClick=\"DrawQTDGraph('ABANDONPCT', '4'); return false;\">TOTAL ABANDON PERCENT</a></th><th width='10%' class='grey_graph_cell' id='QTD_graph5'><a href='#' onClick=\"DrawQTDGraph('AVGABANDONTIME', '5'); return false;\">AVG ABANDON TIME</a></th><th width='10%' class='grey_graph_cell' id='QTD_graph6'><a href='#' onClick=\"DrawQTDGraph('AVGANSWERSPEED', '6'); return false;\">AVG ANSWER SPEED</a></th><th width='10%' class='grey_graph_cell' id='QTD_graph7'><a href='#' onClick=\"DrawQTDGraph('AVGTALKTIME', '7'); return false;\">AVG TALK TIME</a></th><th width='10%' class='grey_graph_cell' id='QTD_graph8'><a href='#' onClick=\"DrawQTDGraph('TOTALTALKTIME', '8'); return false;\">TOTAL TALK TIME</a></th><th width='10%' class='grey_graph_cell' id='QTD_graph9'><a href='#' onClick=\"DrawQTDGraph('TOTALWRAPTIME', '9'); return false;\">TOTAL WRAP TIME</a></th><th width='10%' class='grey_graph_cell' id='QTD_graph10'><a href='#' onClick=\"DrawQTDGraph('TOTALCALLTIME', '10'); return false;\">TOTAL CALL TIME</a></th></tr><tr><td colspan='11' class='graph_span_cell' align='center'><span id='QTD_stats_graph'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";
	
	$graph_header="<table cellspacing='0' cellpadding='0' class='horizontalgraph'><caption align='top'>DAILY RPT - $query_date_BEGIN to $query_date_END</caption><tr><th class='thgraph' scope='col'>DATE/TIME RANGE</th>";
	$OFFERED_graph=$graph_header."<th class='thgraph' scope='col'>TOTAL CALLS OFFERED</th></tr>";
	$ANSWERED_graph=$graph_header."<th class='thgraph' scope='col'>TOTAL CALLS ANSWERED </th></tr>";
	$AGENTS_graph=$graph_header."<th class='thgraph' scope='col'>TOTAL AGENTS ANSWERED </th></tr>";
	$ABANDONED_graph=$graph_header."<th class='thgraph' scope='col'>TOTAL CALLS ABANDONED</th></tr>";
	$ABANDONPCT_graph=$graph_header."<th class='thgraph' scope='col'>TOTAL ABANDON PERCENT</th></tr>";
	$AVGABANDONTIME_graph=$graph_header."<th class='thgraph' scope='col'>AVG ABANDON TIME</th></tr>";
	$AVGANSWERSPEED_graph=$graph_header."<th class='thgraph' scope='col'>AVG ANSWER SPEED</th></tr>";
	$AVGTALKTIME_graph=$graph_header."<th class='thgraph' scope='col'>AVG TALK TIME</th></tr>";
	$TOTALTALKTIME_graph=$graph_header."<th class='thgraph' scope='col'>TOTAL TALK TIME</th></tr>";
	$TOTALWRAPTIME_graph=$graph_header."<th class='thgraph' scope='col'>TOTAL WRAP TIME</th></tr>";
	$TOTALCALLTIME_graph=$graph_header."<th class='thgraph' scope='col'>TOTAL CALL TIME</th></tr>";
	##########################

	$totCALLSwtd=0;
	$totANSWERSwtd=0;
	$totAGENTSwtd=0;  $AGENTS_wtd_array=array();
	$totANSWERSsecwtd=0;
	$totANSWERSspeedwtd=0;
	$totABANDONSwtd=0;
	$totABANDONSsecwtd=0;
	$totSTATUSESwtd=array();

	$totCALLSmtd=0;
	$totANSWERSmtd=0;
	$totAGENTSmtd=0;  $AGENTS_mtd_array=array();
	$totANSWERSsecmtd=0;
	$totANSWERSspeedmtd=0;
	$totABANDONSmtd=0;
	$totABANDONSsecmtd=0;
	$totSTATUSESmtd=array();

	$totCALLSqtd=0;
	$totANSWERSqtd=0;
	$totAGENTSqtd=0;  $AGENTS_qtd_array=array();
	$totANSWERSsecqtd=0;
	$totANSWERSspeedqtd=0;
	$totABANDONSqtd=0;
	$totABANDONSsecqtd=0;
	$totSTATUSESqtd=array();

	$d=0;
	while ($d < $TOTintervals)
		{
		if ($totDROPSdate[$d] < 1) {$totDROPSdate[$d]=0;}
		if ($totQUEUEdate[$d] < 1) {$totQUEUEdate[$d]=0;}
		if ($totCALLSdate[$d] < 1) {$totCALLSdate[$d]=0;}

		if ($totDROPSdate[$d] > 0)
			{$totDROPSpctDATE[$d] = ( ($totDROPSdate[$d] / $totCALLSdate[$d]) * 100);}
		else {$totDROPSpctDATE[$d] = 0;}
		$totDROPSpctDATE[$d] = round($totDROPSpctDATE[$d], 2);
		if ($totQUEUEdate[$d] > 0)
			{$totQUEUEpctDATE[$d] = ( ($totQUEUEdate[$d] / $totCALLSdate[$d]) * 100);}
		else {$totQUEUEpctDATE[$d] = 0;}
		$totQUEUEpctDATE[$d] = round($totQUEUEpctDATE[$d], 2);

		if ($totDROPSsecDATE[$d] > 0)
			{$totDROPSavgDATE[$d] = ($totDROPSsecDATE[$d] / $totDROPSdate[$d]);}
		else {$totDROPSavgDATE[$d] = 0;}
		if ($totQUEUEsecDATE[$d] > 0)
			{$totQUEUEavgDATE[$d] = ($totQUEUEsecDATE[$d] / $totQUEUEdate[$d]);}
		else {$totQUEUEavgDATE[$d] = 0;}
		if ($totQUEUEsecDATE[$d] > 0)
			{$totQUEUEtotDATE[$d] = ($totQUEUEsecDATE[$d] / $totCALLSdate[$d]);}
		else {$totQUEUEtotDATE[$d] = 0;}

		if ($totCALLSsecDATE[$d] > 0)
			{
			$totCALLSavgDATE[$d] = ($totCALLSsecDATE[$d] / $totCALLSdate[$d]);

			$totTIME_M = ($totCALLSsecDATE[$d] / 60);
			$totTIME_M_int = round($totTIME_M, 2);
			$totTIME_M_int = intval("$totTIME_M");
			$totTIME_S = ($totTIME_M - $totTIME_M_int);
			$totTIME_S = ($totTIME_S * 60);
			$totTIME_S = round($totTIME_S, 0);
			if ($totTIME_S < 10) {$totTIME_S = "0$totTIME_S";}
			$totTIME_MS = "$totTIME_M_int:$totTIME_S";
			$totTIME_MS =		sprintf("%8s", $totTIME_MS);
			}
		else 
			{
			$totCALLSavgDATE[$d] = 0;
			$totTIME_MS='        ';
			}
		$totCALLSdate[$d] =	sprintf("%7s", $totCALLSdate[$d]);


		if ($totCALLSdate[$d]>0)
			{
			$totABANDONSpctDATE[$d] =	sprintf("%7.2f", (100*$totABANDONSdate[$d]/$totCALLSdate[$d]));
			}
		else
			{
			$totCALLSdate[$d]="      0";
			$totABANDONSpctDATE[$d] = "    0.0";
			}
		if ($totABANDONSdate[$d]>0)
			{
			$totABANDONSavgTIME[$d] =	sprintf("%7s", date("i:s", mktime(0, 0, round($totABANDONSsecdate[$d]/$totABANDONSdate[$d]))));
			if (round($totABANDONSsecdate[$d]/$totABANDONSdate[$d])>$max_avgabandontime) {$max_avgabandontime=round($totABANDONSsecdate[$d]/$totABANDONSdate[$d]);}
			$graph_stats[$d][11]=round($totABANDONSsecdate[$d]/$totABANDONSdate[$d]);
			}
		else
			{
			$totABANDONSdate[$d]="0";
			$totABANDONSavgTIME[$d] = "  00:00";
			$graph_stats[$d][11]=0;
			}
		if ($totANSWERSdate[$d]>0)
			{
			$totANSWERSavgspeedTIME[$d] =	sprintf("%6s", date("i:s", mktime(0, 0, round($totANSWERSspeeddate[$d]/$totANSWERSdate[$d]))));
			$totANSWERSavgTIME[$d] =	sprintf("%6s", date("i:s", mktime(0, 0, round($totANSWERSsecdate[$d]/$totANSWERSdate[$d]))));
			if (round($totANSWERSspeeddate[$d]/$totANSWERSdate[$d])>$max_avganswerspeed) {$max_avganswerspeed=round($totANSWERSspeeddate[$d]/$totANSWERSdate[$d]);}
			$graph_stats[$d][12]=round($totANSWERSspeeddate[$d]/$totANSWERSdate[$d]);
			$graph_stats[$d][16]=round($totANSWERSsecdate[$d]/$totANSWERSdate[$d]);
			}
		else
			{
			$totANSWERSdate[$d]="0";
			$totANSWERSavgspeedTIME[$d] = " 00:00";
			$totANSWERSavgTIME[$d] = " 00:00";
			$graph_stats[$d][12]=0;
			$graph_stats[$d][16]=0;
			}
		$totANSWERStalkTIME[$d] =	sprintf("%10s", floor($totANSWERSsecdate[$d]/3600).date(":i:s", mktime(0, 0, $totANSWERSsecdate[$d])));
		$totANSWERSwrapTIME[$d] =	sprintf("%10s", floor(($totANSWERSdate[$d]*15)/3600).date(":i:s", mktime(0, 0, ($totANSWERSdate[$d]*15))));
		if (($totANSWERSdate[$d]*15)>$max_totalwraptime) {$max_totalwraptime=($totANSWERSdate[$d]*15);}
		$graph_stats[$d][13]=($totANSWERSdate[$d]*15);
		$graph_stats[$d][14]=($totANSWERSsecdate[$d]+($totANSWERSdate[$d]*15));
		$graph_stats[$d][15]=$totANSWERSsecdate[$d];

		$totANSWERStotTIME[$d] =	sprintf("%10s", floor(($totANSWERSsecdate[$d]+($totANSWERSdate[$d]*15))/3600).date(":i:s", mktime(0, 0, ($totANSWERSsecdate[$d]+($totANSWERSdate[$d]*15)))));
		$totANSWERSdate[$d] =	sprintf("%8s", $totANSWERSdate[$d]);
		$totABANDONSdate[$d] =	sprintf("%9s", $totABANDONSdate[$d]);

		if (date("w", strtotime($daySTART[$d]))==0 && date("w", strtotime($daySTART[$d-1]))!=0 && $d>0) 
			{  # 2nd date/"w" check is for DST
			$totAGENTSwtd=count(array_count_values($AGENTS_wtd_array));
			if ($totCALLSwtd>0)
				{
				$totABANDONSpctwtd =	sprintf("%7.2f", (100*$totABANDONSwtd/$totCALLSwtd));
				}
			else
				{
				$totABANDONSpctwtd = "    0.0";
				}
			if ($totABANDONSwtd>0)
				{
				$totABANDONSavgTIMEwtd =	sprintf("%7s", date("i:s", mktime(0, 0, round($totABANDONSsecwtd/$totABANDONSwtd))));
				if (round($totABANDONSsecwtd/$totABANDONSwtd)>$max_wtd_avgabandontime) {$max_wtd_avgabandontime=round($totABANDONSsecwtd/$totABANDONSwtd);}
				$wtd_graph_stats[$wa][11]=round($totABANDONSsecwtd/$totABANDONSwtd);
				}
			else
				{
				$totABANDONSavgTIMEwtd = "  00:00";
				$wtd_graph_stats[$wa][11]=0;
				}
			if ($totANSWERSwtd>0)
				{
				$totANSWERSavgspeedTIMEwtd =	sprintf("%6s", date("i:s", mktime(0, 0, round($totANSWERSspeedwtd/$totANSWERSwtd))));
				$totANSWERSavgTIMEwtd =	sprintf("%6s", date("i:s", mktime(0, 0, round($totANSWERSsecwtd/$totANSWERSwtd))));
				if (round($totANSWERSspeedwtd/$totANSWERSwtd)>$max_wtd_avganswerspeed) {$max_wtd_avganswerspeed=round($totANSWERSspeedwtd/$totANSWERSwtd);}
				$wtd_graph_stats[$wa][12]=round($totANSWERSspeedwtd/$totANSWERSwtd);
				$wtd_graph_stats[$wa][16]=round($totANSWERSsecwtd/$totANSWERSwtd);
				}
			else
				{
				$totANSWERSavgspeedTIMEwtd = " 00:00";
				$totANSWERSavgTIMEwtd = " 00:00";
				$wtd_graph_stats[$wa][12]=0;
				$wtd_graph_stats[$wa][16]=0;
				}
			$totANSWERStalkTIMEwtd =	sprintf("%10s", floor($totANSWERSsecwtd/3600).date(":i:s", mktime(0, 0, $totANSWERSsecwtd)));
			$totANSWERSwrapTIMEwtd =	sprintf("%10s", floor(($totANSWERSwtd*15)/3600).date(":i:s", mktime(0, 0, ($totANSWERSwtd*15))));
			if (($totANSWERSwtd*15)>$max_wtd_totalwraptime) {$max_wtd_totalwraptime=($totANSWERSwtd*15);}
			$wtd_graph_stats[$wa][13]=($totANSWERSwtd*15);
			$wtd_graph_stats[$wa][14]=($totANSWERSsecwtd+($totANSWERSwtd*15));
			$wtd_graph_stats[$wa][15]=$totANSWERSsecwtd;
			$totANSWERStotTIMEwtd =	sprintf("%10s", floor(($totANSWERSsecwtd+($totANSWERSwtd*15))/3600).date(":i:s", mktime(0, 0, ($totANSWERSsecwtd+($totANSWERSwtd*15)))));
			# $totAGENTSwtd =	sprintf("%8s", $totAGENTSwtd);
			$totAGENTSwtd=count(array_count_values($AGENTS_wtd_array));
			$totAGENTSwtd =	sprintf("%8s", $totAGENTSwtd);
			$totANSWERSwtd =	sprintf("%8s", $totANSWERSwtd);
			$totABANDONSwtd =	sprintf("%9s", $totABANDONSwtd);
			$totCALLSwtd =	sprintf("%7s", $totCALLSwtd);		

			if (trim($totCALLSwtd)>$max_wtd_offered) {$max_wtd_offered=trim($totCALLSwtd);}
			if (trim($totANSWERSwtd)>$max_wtd_answered) {$max_wtd_answered=trim($totANSWERSwtd);}
			if (trim($totAGENTSwtd)>$max_wtd_agents) {$max_wtd_agents=trim($totAGENTSwtd);}
			if (trim($totABANDONSwtd)>$max_wtd_abandoned) {$max_wtd_abandoned=trim($totABANDONSwtd);}
			if (trim($totABANDONSpctwtd)>$max_wtd_abandonpct) {$max_wtd_abandonpct=trim($totABANDONSpctwtd);}

			if (round($totANSWERSsecwtd/$totANSWERSwtd)>$max_wtd_avgtalktime) {$max_wtd_avgtalktime=round($totANSWERSsecwtd/$totANSWERSwtd);}
			if (trim($totANSWERSsecwtd)>$max_wtd_totaltalktime) {$max_wtd_totaltalktime=trim($totANSWERSsecwtd);}
			if (trim($totANSWERSsecwtd+($totANSWERSwtd*15))>$max_wtd_totalcalltime) {$max_wtd_totalcalltime=trim($totANSWERSsecwtd+($totANSWERSwtd*15));}
			$week=date("W", strtotime($dayEND[$d-1]));
			$year=substr($dayEND[$d-1],0,4);
			$wtd_graph_stats[$wa][0]="Week $week, $year";
			$wtd_graph_stats[$wa][1]=trim($totCALLSwtd);
			$wtd_graph_stats[$wa][2]=trim($totANSWERSwtd);
			$wtd_graph_stats[$wa][3]=trim($totABANDONSwtd);
			$wtd_graph_stats[$wa][4]=trim($totABANDONSpctwtd);
			$wtd_graph_stats[$wa][5]=trim($totABANDONSavgTIMEwtd);
			$wtd_graph_stats[$wa][6]=trim($totANSWERSavgspeedTIMEwtd);
			$wtd_graph_stats[$wa][7]=trim($totANSWERSavgTIMEwtd);
			$wtd_graph_stats[$wa][8]=trim($totANSWERStalkTIMEwtd);
			$wtd_graph_stats[$wa][9]=trim($totANSWERSwrapTIMEwtd);
			$wtd_graph_stats[$wa][10]=trim($totANSWERStotTIMEwtd);
			$wtd_graph_stats[$wa][17]=trim($totAGENTSwtd);
			$wa++;

			$ASCII_text.="$MAINH\n";
			$ASCII_text.="|                                       WTD | $totCALLSwtd | $totANSWERSwtd | $totAGENTSwtd | $totABANDONSwtd | $totABANDONSpctwtd%| $totABANDONSavgTIMEwtd | $totANSWERSavgspeedTIMEwtd | $totANSWERSavgTIMEwtd | $totANSWERStalkTIMEwtd | $totANSWERSwrapTIMEwtd | $totANSWERStotTIMEwtd |";
			$CSV_text.="\"WTD\",\"$totCALLSwtd\",\"$totANSWERSwtd\",\"$totAGENTSwtd\",\"$totABANDONSwtd\",\"$totABANDONSpctwtd%\",\"$totABANDONSavgTIMEwtd\",\"$totANSWERSavgspeedTIMEwtd\",\"$totANSWERSavgTIMEwtd\",\"$totANSWERStalkTIMEwtd\",\"$totANSWERSwrapTIMEwtd\",\"$totANSWERStotTIMEwtd\"";
			for ($s=0; $s<count($status_array); $s++) {
				$ASCII_text.=" ".sprintf("%10s", ($totSTATUSESwtd[$status_array[$s][0]]+0))." |";
				$CSV_text.=",\"".sprintf("%10s", ($totSTATUSESwtd[$status_array[$s][0]]+0))."\"";
			}
			$ASCII_text.="\n";
			$CSV_text.="\n";
			$ASCII_text.="$MAINH\n";

			$totCALLSwtd=0;
			$totANSWERSwtd=0;
			$AGENTS_wtd_array=array();
			$totANSWERSsecwtd=0;
			$totANSWERSspeedwtd=0;
			$totABANDONSwtd=0;
			$totABANDONSsecwtd=0;
			$totSTATUSESwtd=array();
			}

		if (date("d", strtotime($daySTART[$d]))==1 && $d>0 && date("d", strtotime($daySTART[$d-1]))!=1) 
			{
			$totAGENTSmtd=count(array_count_values($AGENTS_mtd_array));
			if ($totCALLSmtd>0)
				{
				$totABANDONSpctmtd =	sprintf("%7.2f", (100*$totABANDONSmtd/$totCALLSmtd));
				}
			else
				{
				$totABANDONSpctmtd = "    0.0";
				}
			if ($totABANDONSmtd>0)
				{
				$totABANDONSavgTIMEmtd =	sprintf("%7s", date("i:s", mktime(0, 0, round($totABANDONSsecmtd/$totABANDONSmtd))));
				if (round($totABANDONSsecmtd/$totABANDONSmtd)>$max_mtd_avgabandontime) {$max_mtd_avgabandontime=round($totABANDONSsecmtd/$totABANDONSmtd);}
				$mtd_graph_stats[$ma][11]=round($totABANDONSsecmtd/$totABANDONSmtd);
				}
			else
				{
				$totABANDONSavgTIMEmtd = "  00:00";
				$mtd_graph_stats[$ma][11]=0;
				}
			if ($totANSWERSmtd>0)
				{
				$totANSWERSavgspeedTIMEmtd =	sprintf("%6s", date("i:s", mktime(0, 0, round($totANSWERSspeedmtd/$totANSWERSmtd))));
				$totANSWERSavgTIMEmtd =	sprintf("%6s", date("i:s", mktime(0, 0, round($totANSWERSsecmtd/$totANSWERSmtd))));
				if (round($totANSWERSspeedmtd/$totANSWERSmtd)>$max_mtd_avganswerspeed) {$max_mtd_avganswerspeed=round($totANSWERSspeedmtd/$totANSWERSmtd);}
				$mtd_graph_stats[$ma][12]=round($totANSWERSspeedmtd/$totANSWERSmtd);
				$mtd_graph_stats[$ma][16]=round($totANSWERSsecmtd/$totANSWERSmtd);
				}
			else
				{
				$totANSWERSavgspeedTIMEmtd = " 00:00";
				$totANSWERSavgTIMEmtd = " 00:00";
				$mtd_graph_stats[$ma][12]=0;
				$mtd_graph_stats[$ma][16]=0;
				}
			$totANSWERStalkTIMEmtd =	sprintf("%10s", floor($totANSWERSsecmtd/3600).date(":i:s", mktime(0, 0, $totANSWERSsecmtd)));
			$totANSWERSwrapTIMEmtd =	sprintf("%10s", floor(($totANSWERSmtd*15)/3600).date(":i:s", mktime(0, 0, ($totANSWERSmtd*15))));
			if (($totANSWERSmtd*15)>$max_mtd_totalwraptime) {$max_mtd_totalwraptime=($totANSWERSmtd*15);}
			$mtd_graph_stats[$ma][13]=($totANSWERSmtd*15);
			$mtd_graph_stats[$ma][14]=($totANSWERSsecmtd+($totANSWERSmtd*15));
			$mtd_graph_stats[$ma][15]=$totANSWERSsecmtd;
			$totANSWERStotTIMEmtd =	sprintf("%10s", floor(($totANSWERSsecmtd+($totANSWERSmtd*15))/3600).date(":i:s", mktime(0, 0, ($totANSWERSsecmtd+($totANSWERSmtd*15)))));
			$totAGENTSmtd=count(array_count_values($AGENTS_mtd_array));
			$totAGENTSmtd =	sprintf("%8s", $totAGENTSmtd);
			$totANSWERSmtd =	sprintf("%8s", $totANSWERSmtd);
			$totABANDONSmtd =	sprintf("%9s", $totABANDONSmtd);
			$totCALLSmtd =	sprintf("%7s", $totCALLSmtd);		

			if (trim($totCALLSmtd)>$max_mtd_offered) {$max_mtd_offered=trim($totCALLSmtd);}
			if (trim($totANSWERSmtd)>$max_mtd_answered) {$max_mtd_answered=trim($totANSWERSmtd);}
			if (trim($totABANDONSmtd)>$max_mtd_abandoned) {$max_mtd_abandoned=trim($totABANDONSmtd);}
			if (trim($totAGENTSmtd)>$max_mtd_agents) {$max_mtd_agents=trim($totAGENTSmtd);}
			if (trim($totABANDONSpctmtd)>$max_mtd_abandonpct) {$max_mtd_abandonpct=trim($totABANDONSpctmtd);}
			if (round($totANSWERSsecmtd/$totANSWERSmtd)>$max_mtd_avgtalktime) {$max_mtd_avgtalktime=round($totANSWERSsecmtd/$totANSWERSmtd);}
			if (trim($totANSWERSsecmtd)>$max_mtd_totaltalktime) {$max_mtd_totaltalktime=trim($totANSWERSsecmtd);}
			if (trim($totANSWERSsecmtd+($totANSWERSmtd*15))>$max_mtd_totalcalltime) {$max_mtd_totalcalltime=trim($totANSWERSsecmtd+($totANSWERSmtd*15));}
			$month=date("F", strtotime($dayEND[$d-1]));
			$year=substr($dayEND[$d-1], 0, 4);
			$mtd_graph_stats[$ma][0]="$month $year";
			$mtd_graph_stats[$ma][1]=trim($totCALLSmtd);
			$mtd_graph_stats[$ma][2]=trim($totANSWERSmtd);
			$mtd_graph_stats[$ma][3]=trim($totABANDONSmtd);
			$mtd_graph_stats[$ma][4]=trim($totABANDONSpctmtd);
			$mtd_graph_stats[$ma][5]=trim($totABANDONSavgTIMEmtd);
			$mtd_graph_stats[$ma][6]=trim($totANSWERSavgspeedTIMEmtd);
			$mtd_graph_stats[$ma][7]=trim($totANSWERSavgTIMEmtd);
			$mtd_graph_stats[$ma][8]=trim($totANSWERStalkTIMEmtd);
			$mtd_graph_stats[$ma][9]=trim($totANSWERSwrapTIMEmtd);
			$mtd_graph_stats[$ma][10]=trim($totANSWERStotTIMEmtd);
			$mtd_graph_stats[$ma][17]=trim($totAGENTSmtd);
			$ma++;

			$ASCII_text.="$MAINH\n";
			$ASCII_text.="|                                       MTD | $totCALLSmtd | $totANSWERSmtd | $totAGENTSmtd | $totABANDONSmtd | $totABANDONSpctmtd%| $totABANDONSavgTIMEmtd | $totANSWERSavgspeedTIMEmtd | $totANSWERSavgTIMEmtd | $totANSWERStalkTIMEmtd | $totANSWERSwrapTIMEmtd | $totANSWERStotTIMEmtd |";
			$CSV_text.="\"MTD\",\"$totCALLSmtd\",\"$totANSWERSmtd\",\"$totAGENTSmtd\",\"$totABANDONSmtd\",\"$totABANDONSpctmtd%\",\"$totABANDONSavgTIMEmtd\",\"$totANSWERSavgspeedTIMEmtd\",\"$totANSWERSavgTIMEmtd\",\"$totANSWERStalkTIMEmtd\",\"$totANSWERSwrapTIMEmtd\",\"$totANSWERStotTIMEmtd\"";
			for ($s=0; $s<count($status_array); $s++) {
				$ASCII_text.=" ".sprintf("%10s", ($totSTATUSESmtd[$status_array[$s][0]]+0))." |";
				$CSV_text.=",\"".sprintf("%10s", ($totSTATUSESmtd[$status_array[$s][0]]+0))."\"";
			}
			$ASCII_text.="\n";
			$CSV_text.="\n";
			$ASCII_text.="$MAINH\n";

			$totCALLSmtd=0;
			$totANSWERSmtd=0;
			$AGENTS_mtd_array=array();
			$totANSWERSsecmtd=0;
			$totANSWERSspeedmtd=0;
			$totABANDONSmtd=0;
			$totABANDONSsecmtd=0;
			$totSTATUSESmtd=array();

			if (date("m", strtotime($daySTART[$d]))==1 || date("m", strtotime($daySTART[$d]))==4 || date("m", strtotime($daySTART[$d]))==7 || date("m", strtotime($daySTART[$d]))==10) # Quarterly line
				{
				$totAGENTSqtd=count(array_count_values($AGENTS_qtd_array));
				if ($totCALLSqtd>0)
					{
					$totABANDONSpctqtd =	sprintf("%7.2f", (100*$totABANDONSqtd/$totCALLSqtd));
					}
				else
					{
					$totABANDONSpctqtd = "    0.0";
					}
				if ($totABANDONSqtd>0)
					{
					$totABANDONSavgTIMEqtd =	sprintf("%7s", date("i:s", mktime(0, 0, round($totABANDONSsecqtd/$totABANDONSqtd))));
					if (round($totABANDONSsecqtd/$totABANDONSqtd)>$max_qtd_avgabandontime) {$max_qtd_avgabandontime=round($totABANDONSsecqtd/$totABANDONSqtd);}
					$qtd_graph_stats[$qa][11]=round($totABANDONSsecqtd/$totABANDONSqtd);
					}
				else
					{
					$totABANDONSavgTIMEqtd = "  00:00";
					$qtd_graph_stats[$qa][11]=0;
					}
				if ($totANSWERSqtd>0)
					{
					$totANSWERSavgspeedTIMEqtd =	sprintf("%6s", date("i:s", mktime(0, 0, round($totANSWERSspeedqtd/$totANSWERSqtd))));
					$totANSWERSavgTIMEqtd =	sprintf("%6s", date("i:s", mktime(0, 0, round($totANSWERSsecqtd/$totANSWERSqtd))));
					if (round($totANSWERSspeedqtd/$totANSWERSqtd)>$max_qtd_avganswerspeed) {$max_qtd_avganswerspeed=round($totANSWERSspeedqtd/$totANSWERSqtd);}
					$qtd_graph_stats[$qa][12]=round($totANSWERSspeedqtd/$totANSWERSqtd);
					$qtd_graph_stats[$qa][16]=round($totANSWERSsecqtd/$totANSWERSqtd);
					}
				else
					{
					$totANSWERSavgspeedTIMEqtd = " 00:00";
					$totANSWERSavgTIMEqtd = " 00:00";
					$qtd_graph_stats[$qa][12]=0;
					$qtd_graph_stats[$qa][16]=0;
					}
				$totANSWERStalkTIMEqtd =	sprintf("%10s", floor($totANSWERSsecqtd/3600).date(":i:s", mktime(0, 0, $totANSWERSsecqtd)));
				$totANSWERSwrapTIMEqtd =	sprintf("%10s", floor(($totANSWERSqtd*15)/3600).date(":i:s", mktime(0, 0, ($totANSWERSqtd*15))));
				if (($totANSWERSqtd*15)>$max_qtd_totalwraptime) {$max_qtd_totalwraptime=($totANSWERSqtd*15);}
				$qtd_graph_stats[$qa][13]=($totANSWERSqtd*15);
				$qtd_graph_stats[$qa][14]=($totANSWERSsecqtd+($totANSWERSqtd*15));
				$qtd_graph_stats[$qa][15]=$totANSWERSsecqtd;
				$totANSWERStotTIMEqtd =	sprintf("%10s", floor(($totANSWERSsecqtd+($totANSWERSqtd*15))/3600).date(":i:s", mktime(0, 0, ($totANSWERSsecqtd+($totANSWERSqtd*15)))));
				$totAGENTSqtd=count(array_count_values($AGENTS_qtd_array));
				$totAGENTSqtd =	sprintf("%8s", $totAGENTSqtd);
				$totANSWERSqtd =	sprintf("%8s", $totANSWERSqtd);
				$totABANDONSqtd =	sprintf("%9s", $totABANDONSqtd);
				$totCALLSqtd =	sprintf("%7s", $totCALLSqtd);		

				if (trim($totCALLSqtd)>$max_qtd_offered) {$max_qtd_offered=trim($totCALLSqtd);}
				if (trim($totANSWERSqtd)>$max_qtd_answered) {$max_qtd_answered=trim($totANSWERSqtd);}
				if (trim($totABANDONSqtd)>$max_qtd_abandoned) {$max_qtd_abandoned=trim($totABANDONSqtd);}
				if (trim($totAGENTSqtd)>$max_qtd_answers) {$max_qtd_answered=trim($totAGENTSqtd);}
				if (trim($totABANDONSpctqtd)>$max_qtd_abandonpct) {$max_qtd_abandonpct=trim($totABANDONSpctqtd);}
				if (round($totANSWERSsecqtd/$totANSWERSqtd)>$max_qtd_avgtalktime) {$max_qtd_avgtalktime=round($totANSWERSsecqtd/$totANSWERSqtd);}
				if (trim($totANSWERSsecqtd)>$max_qtd_totaltalktime) {$max_qtd_totaltalktime=trim($totANSWERSsecqtd);}
				if (trim($totANSWERSsecqtd+($totANSWERSqtd*15))>$max_qtd_totalcalltime) {$max_qtd_totalcalltime=trim($totANSWERSsecqtd+($totANSWERSqtd*15));}
				$month=date("m", strtotime($dayEND[$d]));
				$year=substr($dayEND[$d], 0, 4);
				$qtr4=array(01,02,03);
				$qtr1=array(04,05,06);
				$qtr2=array(07,08,09);
				$qtr3=array(10,11,12);
				if(in_array($month,$qtr1)) {
					$qtr="1st";
				} else if(in_array($month,$qtr2)) {
					$qtr="2nd";
				}  else if(in_array($month,$qtr3)) {
					$qtr="3rd";
				}  else if(in_array($month,$qtr4)) {
					$qtr="4th";
				}
				$qtd_graph_stats[$qa][0]="$qtr quarter, $year";
				$qtd_graph_stats[$qa][1]=trim($totCALLSqtd);
				$qtd_graph_stats[$qa][2]=trim($totANSWERSqtd);
				$qtd_graph_stats[$qa][3]=trim($totABANDONSqtd);
				$qtd_graph_stats[$qa][4]=trim($totABANDONSpctqtd);
				$qtd_graph_stats[$qa][5]=trim($totABANDONSavgTIMEqtd);
				$qtd_graph_stats[$qa][6]=trim($totANSWERSavgspeedTIMEqtd);
				$qtd_graph_stats[$qa][7]=trim($totANSWERSavgTIMEqtd);
				$qtd_graph_stats[$qa][8]=trim($totANSWERStalkTIMEqtd);
				$qtd_graph_stats[$qa][9]=trim($totANSWERSwrapTIMEqtd);
				$qtd_graph_stats[$qa][10]=trim($totANSWERStotTIMEqtd);
				$qtd_graph_stats[$qa][17]=trim($totAGENTSqtd);
				$qa++;

				$ASCII_text.="|                                       QTD | $totCALLSqtd | $totANSWERSqtd | $totAGENTSqtd | $totABANDONSqtd | $totABANDONSpctqtd%| $totABANDONSavgTIMEqtd | $totANSWERSavgspeedTIMEqtd | $totANSWERSavgTIMEqtd | $totANSWERStalkTIMEqtd | $totANSWERSwrapTIMEqtd | $totANSWERStotTIMEqtd |";
				$CSV_text.="\"QTD\",\"$totCALLSqtd\",\"$totANSWERSqtd\",\"$totAGENTSqtd\",\"$totABANDONSqtd\",\"$totABANDONSpctqtd%\",\"$totABANDONSavgTIMEqtd\",\"$totANSWERSavgspeedTIMEqtd\",\"$totANSWERSavgTIMEqtd\",\"$totANSWERStalkTIMEqtd\",\"$totANSWERSwrapTIMEqtd\",\"$totANSWERStotTIMEqtd\"";
				for ($s=0; $s<count($status_array); $s++) {
					$ASCII_text.=" ".sprintf("%10s", ($totSTATUSESqtd[$status_array[$s][0]]+0))." |";
					$CSV_text.=",\"".sprintf("%10s", ($totSTATUSESqtd[$status_array[$s][0]]+0))."\"";
				}
				$ASCII_text.="\n";
				$CSV_text.="\n";
				$ASCII_text.="$MAINH\n";

				$totCALLSqtd=0;
				$totANSWERSqtd=0;
				$AGENTS_qtd_array=array();
				$totANSWERSsecqtd=0;
				$totANSWERSspeedqtd=0;
				$totABANDONSqtd=0;
				$totABANDONSsecqtd=0;
				$totSTATUSESqtd=array();
				}
			}

		$totAGENTSday=sprintf("%8s", count($totAGENTSdate[$d]));

		$temp_agent_array=array_keys($totAGENTSdate[$d]);
		for ($x=0; $x<count($temp_agent_array); $x++) {
			if ($temp_agent_array[$x]!="") {
				array_push($AGENTS_wtd_array, $temp_agent_array[$x]);
				array_push($AGENTS_mtd_array, $temp_agent_array[$x]);
				array_push($AGENTS_qtd_array, $temp_agent_array[$x]);
			}
		}
		$totCALLSwtd+=$totCALLSdate[$d];
		$totANSWERSwtd+=$totANSWERSdate[$d];
		$totANSWERSsecwtd+=$totANSWERSsecdate[$d];
		$totANSWERSspeedwtd+=$totANSWERSspeeddate[$d];
		$totABANDONSwtd+=$totABANDONSdate[$d];
		$totABANDONSsecwtd+=$totABANDONSsecdate[$d];
		$totCALLSmtd+=$totCALLSdate[$d];
		$totANSWERSmtd+=$totANSWERSdate[$d];
		$totANSWERSsecmtd+=$totANSWERSsecdate[$d];
		$totANSWERSspeedmtd+=$totANSWERSspeeddate[$d];
		$totABANDONSmtd+=$totABANDONSdate[$d];
		$totABANDONSsecmtd+=$totABANDONSsecdate[$d];
		$totCALLSqtd+=$totCALLSdate[$d];
		$totANSWERSqtd+=$totANSWERSdate[$d];
		$totANSWERSsecqtd+=$totANSWERSsecdate[$d];
		$totANSWERSspeedqtd+=$totANSWERSspeeddate[$d];
		$totABANDONSqtd+=$totABANDONSdate[$d];
		$totABANDONSsecqtd+=$totABANDONSsecdate[$d];

		if (trim($totCALLSdate[$d])>$max_offered) {$max_offered=trim($totCALLSdate[$d]);}
		if (trim($totANSWERSdate[$d])>$max_answered) {$max_answered=trim($totANSWERSdate[$d]);}
		if (trim($totAGENTSday)>$max_agents) {$max_agents=trim($totAGENTSday);}
		if (trim($totABANDONSdate[$d])>$max_abandoned) {$max_abandoned=trim($totABANDONSdate[$d]);}
		if (trim($totABANDONSpctDATE[$d])>$max_abandonpct) {$max_abandonpct=trim($totABANDONSpctDATE[$d]);}

		if (round($totANSWERSsecdate[$d]/$totANSWERSdate[$d])>$max_avgtalktime) {$max_avgtalktime=round($totANSWERSsecdate[$d]/$totANSWERSdate[$d]);}
		if (trim($totANSWERSsecdate[$d])>$max_totaltalktime) {$max_totaltalktime=trim($totANSWERSsecdate[$d]);}
		if (trim($totANSWERSsecdate[$d]+($totANSWERSdate[$d]*15))>$max_totalcalltime) {$max_totalcalltime=trim($totANSWERSsecdate[$d]+($totANSWERSdate[$d]*15));}
		$graph_stats[$d][0]="$daySTART[$d] - $dayEND[$d]";
		$graph_stats[$d][1]=trim($totCALLSdate[$d]);
		$graph_stats[$d][2]=trim($totANSWERSdate[$d]);
		$graph_stats[$d][3]=trim($totABANDONSdate[$d]);
		$graph_stats[$d][4]=trim($totABANDONSpctDATE[$d]);
		$graph_stats[$d][5]=trim($totABANDONSavgTIME[$d]);
		$graph_stats[$d][6]=trim($totANSWERSavgspeedTIME[$d]);
		$graph_stats[$d][7]=trim($totANSWERSavgTIME[$d]);
		$graph_stats[$d][8]=trim($totANSWERStalkTIME[$d]);
		$graph_stats[$d][9]=trim($totANSWERSwrapTIME[$d]);
		$graph_stats[$d][10]=trim($totANSWERStotTIME[$d]);
		$graph_stats[$d][17]=trim($totAGENTSday);

		$ASCII_text.="| $daySTART[$d] - $dayEND[$d] | $totCALLSdate[$d] | $totANSWERSdate[$d] | $totAGENTSday | $totABANDONSdate[$d] | $totABANDONSpctDATE[$d]%| $totABANDONSavgTIME[$d] | $totANSWERSavgspeedTIME[$d] | $totANSWERSavgTIME[$d] | $totANSWERStalkTIME[$d] | $totANSWERSwrapTIME[$d] | $totANSWERStotTIME[$d] |";
		$CSV_text.="\"$daySTART[$d] - $dayEND[$d]\",\"$totCALLSdate[$d]\",\"$totANSWERSdate[$d]\",\"$totAGENTSday\",\"$totABANDONSdate[$d]\",\"$totABANDONSpctDATE[$d]%\",\"$totABANDONSavgTIME[$d]\",\"$totANSWERSavgspeedTIME[$d]\",\"$totANSWERSavgTIME[$d]\",\"$totANSWERStalkTIME[$d]\",\"$totANSWERSwrapTIME[$d]\",\"$totANSWERStotTIME[$d]\"";
		for ($s=0; $s<count($status_array); $s++) {
			$ASCII_text.=" ".sprintf("%10s", ($totSTATUSESdate[$d][$status_array[$s][0]]+0))." |";
			$CSV_text.=",\"".sprintf("%10s", ($totSTATUSESdate[$d][$status_array[$s][0]]+0))."\"";
			$totSTATUSESwtd[$status_array[$s][0]]+=$totSTATUSESdate[$d][$status_array[$s][0]];
			$totSTATUSESmtd[$status_array[$s][0]]+=$totSTATUSESdate[$d][$status_array[$s][0]];
			$totSTATUSESqtd[$status_array[$s][0]]+=$totSTATUSESdate[$d][$status_array[$s][0]];
		}
		$ASCII_text.="\n";
		$CSV_text.="\n";

		$d++;
		}

	if ($totDROPS > 0)
		{$totDROPSpct = ( ($totDROPS / $totCALLS) * 100);}
	else {$totDROPSpct = 0;}
	$totDROPSpct = round($totDROPSpct, 2);
	if ($totQUEUE > 0)
		{$totQUEUEpct = ( ($totQUEUE / $totCALLS) * 100);}
	else {$totQUEUEpct = 0;}
	$totQUEUEpct = round($totQUEUEpct, 2);

	if ($totDROPSsec > 0)
		{$totDROPSavg = ($totDROPSsec / $totDROPS);}
	else {$totDROPSavg = 0;}
	if ($totQUEUEsec > 0)
		{$totQUEUEavg = ($totQUEUEsec / $totQUEUE);}
	else {$totQUEUEavg = 0;}
	if ($totQUEUEsec > 0)
		{$totQUEUEtot = ($totQUEUEsec / $totCALLS);}
	else {$totQUEUEtot = 0;}

	if ($totCALLSsec > 0)
		{
		$totCALLSavg = ($totCALLSsec / $totCALLS);

		$totTIME_M = ($totCALLSsec / 60);
		$totTIME_M_int = round($totTIME_M, 2);
		$totTIME_M_int = intval("$totTIME_M");
		$totTIME_S = ($totTIME_M - $totTIME_M_int);
		$totTIME_S = ($totTIME_S * 60);
		$totTIME_S = round($totTIME_S, 0);
		if ($totTIME_S < 10) {$totTIME_S = "0$totTIME_S";}
		$totTIME_MS = "$totTIME_M_int:$totTIME_S";
		$totTIME_MS =		sprintf("%9s", $totTIME_MS);
		}
	else 
		{
		$totCALLSavg = 0;
		$totTIME_MS='         ';
		}


		$FtotCALLSavg =	sprintf("%6.0f", $totCALLSavg);
		$FtotDROPSavg =	sprintf("%7.2f", $totDROPSavg);
		$FtotQUEUEavg =	sprintf("%7.2f", $totQUEUEavg);
		$FtotQUEUEtot =	sprintf("%7.2f", $totQUEUEtot);
		$FtotDROPSpct =	sprintf("%6.2f", $totDROPSpct);
		$FtotQUEUEpct =	sprintf("%6.2f", $totQUEUEpct);
		$FtotDROPS =	sprintf("%6s", $totDROPS);
		$FtotQUEUE =	sprintf("%6s", $totQUEUE);
		$FtotCALLS =	sprintf("%7s", $totCALLS);

		if ($FtotCALLS>0) 
			{
			$FtotABANDONSpct =	sprintf("%7.2f", (100*$FtotABANDONS/$FtotCALLS));
			}
		else
			{
			$FtotABANDONSpct =	"    0.0";
			}
		if ($FtotABANDONS>0) 
			{
			$FtotABANDONSavgTIME =	sprintf("%7s", date("i:s", mktime(0, 0, round($FtotABANDONSsec/$FtotABANDONS))));
			}
		else 
			{
			$FtotABANDONSavgTIME =	sprintf("%7s", "00:00");
			}
		if ($FtotANSWERS>0) 
			{
			$FtotANSWERSavgspeedTIME =	sprintf("%6s", date("i:s", mktime(0, 0, round($FtotANSWERSspeed/$FtotANSWERS))));
			$FtotANSWERSavgTIME =	sprintf("%6s", date("i:s", mktime(0, 0, round($FtotANSWERSsec/$FtotANSWERS))));
			}
		else 
			{
			$FtotANSWERSavgspeedTIME =	sprintf("%6s", "00:00");
			$FtotANSWERSavgTIME =	sprintf("%6s", "00:00");
			}
		$FtotANSWERStalkTIME =	sprintf("%10s", floor($FtotANSWERSsec/3600).date(":i:s", mktime(0, 0, $FtotANSWERSsec)));
		$FtotANSWERSwrapTIME =	sprintf("%10s", floor(($FtotANSWERS*15)/3600).date(":i:s", mktime(0, 0, ($FtotANSWERS*15))));
		$FtotANSWERStotTIME =	sprintf("%10s", floor(($FtotANSWERSsec+($FtotANSWERS*15))/3600).date(":i:s", mktime(0, 0, ($FtotANSWERSsec+($FtotANSWERS*15)))));
		$FtotANSWERS =	sprintf("%8s", $FtotANSWERS);
		$FtotABANDONS =	sprintf("%9s", $FtotABANDONS);

		if (date("w", strtotime($daySTART[$d]))>0) 
			{
			$totAGENTSwtd=count(array_count_values($AGENTS_wtd_array));
				if ($totCALLSwtd>0)
				{
				$totABANDONSpctwtd =	sprintf("%7.2f", (100*$totABANDONSwtd/$totCALLSwtd));
				}
			else
				{
				$totABANDONSpctwtd = "    0.0";
				}
			if ($totABANDONSwtd>0)
				{
				$totABANDONSavgTIMEwtd =	sprintf("%7s", date("i:s", mktime(0, 0, round($totABANDONSsecwtd/$totABANDONSwtd))));
				if (round($totABANDONSsecwtd/$totABANDONSwtd)>$max_wtd_avgabandontime) {$max_wtd_avgabandontime=round($totABANDONSsecwtd/$totABANDONSwtd);}
				$wtd_graph_stats[$wa][11]=round($totABANDONSsecwtd/$totABANDONSwtd);
				}
			else
				{
				$totABANDONSavgTIMEwtd = "  00:00";
				$wtd_graph_stats[$wa][11]=0;
				}
			if ($totANSWERSwtd>0)
				{
				$totANSWERSavgspeedTIMEwtd =	sprintf("%6s", date("i:s", mktime(0, 0, round($totANSWERSspeedwtd/$totANSWERSwtd))));
				$totANSWERSavgTIMEwtd =	sprintf("%6s", date("i:s", mktime(0, 0, round($totANSWERSsecwtd/$totANSWERSwtd))));
				if (round($totANSWERSspeedwtd/$totANSWERSwtd)>$max_wtd_avganswerspeed) {$max_wtd_avganswerspeed=round($totANSWERSspeedwtd/$totANSWERSwtd);}
				$wtd_graph_stats[$wa][12]=round($totANSWERSspeedwtd/$totANSWERSwtd);
				$wtd_graph_stats[$wa][16]=round($totANSWERSsecwtd/$totANSWERSwtd);
				}
			else
				{
				$totANSWERSavgspeedTIMEwtd = " 00:00";
				$totANSWERSavgTIMEwtd = " 00:00";
				$wtd_graph_stats[$wa][12]=0;
				$wtd_graph_stats[$wa][16]=0;
				}
			$totANSWERStalkTIMEwtd =	sprintf("%10s", floor($totANSWERSsecwtd/3600).date(":i:s", mktime(0, 0, $totANSWERSsecwtd)));
			$totANSWERSwrapTIMEwtd =	sprintf("%10s", floor(($totANSWERSwtd*15)/3600).date(":i:s", mktime(0, 0, ($totANSWERSwtd*15))));
			if (($totANSWERSwtd*15)>$max_wtd_totalwraptime) {$max_wtd_totalwraptime=($totANSWERSwtd*15);}
			$wtd_graph_stats[$wa][13]=($totANSWERSwtd*15);
			$wtd_graph_stats[$wa][14]=($totANSWERSsecwtd+($totANSWERSwtd*15));
			$wtd_graph_stats[$wa][15]=$totANSWERSsecwtd;
			$totANSWERStotTIMEwtd =	sprintf("%10s", floor(($totANSWERSsecwtd+($totANSWERSwtd*15))/3600).date(":i:s", mktime(0, 0, ($totANSWERSsecwtd+($totANSWERSwtd*15)))));
			$totAGENTSwtd=count(array_count_values($AGENTS_wtd_array));
			$totAGENTSwtd =	sprintf("%8s", $totAGENTSwtd);
			$totANSWERSwtd =	sprintf("%8s", $totANSWERSwtd);
			$totABANDONSwtd =	sprintf("%9s", $totABANDONSwtd);
			$totCALLSwtd =	sprintf("%7s", $totCALLSwtd);		

			if (trim($totCALLSwtd)>$max_wtd_offered) {$max_wtd_offered=trim($totCALLSwtd);}
			if (trim($totANSWERSwtd)>$max_wtd_answered) {$max_wtd_answered=trim($totANSWERSwtd);}
			if (trim($totAGENTSwtd)>$max_wtd_agents) {$max_wtd_agents=trim($totAGENTSwtd);}
			if (trim($totABANDONSwtd)>$max_wtd_abandoned) {$max_wtd_abandoned=trim($totABANDONSwtd);}
			if (trim($totABANDONSpctwtd)>$max_wtd_abandonpct) {$max_wtd_abandonpct=trim($totABANDONSpctwtd);}

			if (trim($totANSWERSavgTIMEwtd)>$max_wtd_avgtalktime) {$max_wtd_avgtalktime=trim($totANSWERSavgTIMEwtd);}
			if (trim($totANSWERSsecwtd)>$max_wtd_totaltalktime) {$max_wtd_totaltalktime=trim($totANSWERSsecwtd);}
			if (trim($totANSWERSsecwtd+($totANSWERSwtd*15))>$max_wtd_totalcalltime) {$max_wtd_totalcalltime=trim($totANSWERSsecwtd+($totANSWERSwtd*15));}

			$week=date("W", strtotime($dayEND[$d-1]));
			$year=substr($dayEND[$d-1], 0, 4);
			$wtd_graph_stats[$wa][0]="Week $week, $year";
			$wtd_graph_stats[$wa][1]=trim($totCALLSwtd);
			$wtd_graph_stats[$wa][2]=trim($totANSWERSwtd);
			$wtd_graph_stats[$wa][3]=trim($totABANDONSwtd);
			$wtd_graph_stats[$wa][4]=trim($totABANDONSpctwtd);
			$wtd_graph_stats[$wa][5]=trim($totABANDONSavgTIMEwtd);
			$wtd_graph_stats[$wa][6]=trim($totANSWERSavgspeedTIMEwtd);
			$wtd_graph_stats[$wa][7]=trim($totANSWERSavgTIMEwtd);
			$wtd_graph_stats[$wa][8]=trim($totANSWERStalkTIMEwtd);
			$wtd_graph_stats[$wa][9]=trim($totANSWERSwrapTIMEwtd);
			$wtd_graph_stats[$wa][10]=trim($totANSWERStotTIMEwtd);
			$wtd_graph_stats[$wa][17]=trim($totAGENTSwtd);
			$wtd_OFFERED_graph=preg_replace('/DAILY/', 'WEEK-TO-DATE', $OFFERED_graph);
			$wtd_ANSWERED_graph=preg_replace('/DAILY/', 'WEEK-TO-DATE',$ANSWERED_graph);
			$wtd_AGENTS_graph=preg_replace('/DAILY/', 'WEEK-TO-DATE', $AGENTS_graph);
			$wtd_ABANDONED_graph=preg_replace('/DAILY/', 'WEEK-TO-DATE',$ABANDONED_graph);
			$wtd_ABANDONPCT_graph=preg_replace('/DAILY/', 'WEEK-TO-DATE',$ABANDONPCT_graph);
			$wtd_AVGABANDONTIME_graph=preg_replace('/DAILY/', 'WEEK-TO-DATE',$AVGABANDONTIME_graph);
			$wtd_AVGANSWERSPEED_graph=preg_replace('/DAILY/', 'WEEK-TO-DATE',$AVGANSWERSPEED_graph);
			$wtd_AVGTALKTIME_graph=preg_replace('/DAILY/', 'WEEK-TO-DATE',$AVGTALKTIME_graph);
			$wtd_TOTALTALKTIME_graph=preg_replace('/DAILY/', 'WEEK-TO-DATE',$TOTALTALKTIME_graph);
			$wtd_TOTALWRAPTIME_graph=preg_replace('/DAILY/', 'WEEK-TO-DATE',$TOTALWRAPTIME_graph);
			$wtd_TOTALCALLTIME_graph=preg_replace('/DAILY/', 'WEEK-TO-DATE',$TOTALCALLTIME_graph);
			for ($q=0; $q<count($wtd_graph_stats); $q++) {
				if ($q==0) {$class=" first";} else if (($q+1)==count($wtd_graph_stats)) {$class=" last";} else {$class="";}
				$wtd_OFFERED_graph.="  <tr><td class='chart_td$class'>".$wtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$wtd_graph_stats[$q][1]/$max_wtd_offered)."' height='16' />".$wtd_graph_stats[$q][1]."</td></tr>";
				$wtd_ANSWERED_graph.="  <tr><td class='chart_td$class'>".$wtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$wtd_graph_stats[$q][2]/$max_wtd_answered)."' height='16' />".$wtd_graph_stats[$q][2]."</td></tr>";
				$wtd_AGENTS_graph.="  <tr><td class='chart_td$class'>".$wtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$wtd_graph_stats[$q][17]/$max_wtd_agents)."' height='16' />".$wtd_graph_stats[$q][17]."</td></tr>";
				$wtd_ABANDONED_graph.="  <tr><td class='chart_td$class'>".$wtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$wtd_graph_stats[$q][3]/$max_wtd_abandoned)."' height='16' />".$wtd_graph_stats[$q][3]."</td></tr>";
				$wtd_ABANDONPCT_graph.="  <tr><td class='chart_td$class'>".$wtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$wtd_graph_stats[$q][4]/$max_wtd_abandonpct)."' height='16' />".$wtd_graph_stats[$q][4]."% </td></tr>";
				$wtd_AVGABANDONTIME_graph.="  <tr><td class='chart_td$class'>".$wtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$wtd_graph_stats[$q][11]/$max_wtd_avgabandontime)."' height='16' />".$wtd_graph_stats[$q][5]."</td></tr>";
				$wtd_AVGANSWERSPEED_graph.="  <tr><td class='chart_td$class'>".$wtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$wtd_graph_stats[$q][12]/$max_wtd_avganswerspeed)."' height='16' />".$wtd_graph_stats[$q][6]."</td></tr>";
				$wtd_AVGTALKTIME_graph.="  <tr><td class='chart_td$class'>".$wtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$wtd_graph_stats[$q][16]/$max_wtd_avgtalktime)."' height='16' />".$wtd_graph_stats[$q][7]."</td></tr>";
				$wtd_TOTALTALKTIME_graph.="  <tr><td class='chart_td$class'>".$wtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$wtd_graph_stats[$q][15]/$max_wtd_totaltalktime)."' height='16' />".$wtd_graph_stats[$q][8]."</td></tr>";
				$wtd_TOTALWRAPTIME_graph.="  <tr><td class='chart_td$class'>".$wtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$wtd_graph_stats[$q][13]/$max_wtd_totalwraptime)."' height='16' />".$wtd_graph_stats[$q][9]."</td></tr>";
				$wtd_TOTALCALLTIME_graph.="  <tr><td class='chart_td$class'>".$wtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$wtd_graph_stats[$q][14]/$max_wtd_totalcalltime)."' height='16' />".$wtd_graph_stats[$q][10]."</td></tr>";
			}

			$ASCII_text.="$MAINH\n";
			$ASCII_text.="|                                       WTD | $totCALLSwtd | $totANSWERSwtd | $totAGENTSwtd | $totABANDONSwtd | $totABANDONSpctwtd%| $totABANDONSavgTIMEwtd | $totANSWERSavgspeedTIMEwtd | $totANSWERSavgTIMEwtd | $totANSWERStalkTIMEwtd | $totANSWERSwrapTIMEwtd | $totANSWERStotTIMEwtd |";
			$CSV_text.="\"WTD\",\"$totCALLSwtd\",\"$totANSWERSwtd\",\"$totAGENTSwtd\",\"$totABANDONSwtd\",\"$totABANDONSpctwtd%\",\"$totABANDONSavgTIMEwtd\",\"$totANSWERSavgspeedTIMEwtd\",\"$totANSWERSavgTIMEwtd\",\"$totANSWERStalkTIMEwtd\",\"$totANSWERSwrapTIMEwtd\",\"$totANSWERStotTIMEwtd\"";
			for ($s=0; $s<count($status_array); $s++) {
				$ASCII_text.=" ".sprintf("%10s", ($totSTATUSESwtd[$status_array[$s][0]]+0))." |";
				$CSV_text.=",\"".sprintf("%10s", ($totSTATUSESwtd[$status_array[$s][0]]+0))."\"";
			}
			$ASCII_text.="\n";
			$CSV_text.="\n";

			$totCALLSwtd=0;
			$totANSWERSwtd=0;
			$AGENTS_wtd_array=array();
			$totANSWERSsecwtd=0;
			$totANSWERSspeedwtd=0;
			$totABANDONSwtd=0;
			$totABANDONSsecwtd=0;
			}

		if (date("d", strtotime($daySTART[$d]))!=1) 
			{
			$totAGENTSmtd=count(array_count_values($AGENTS_mtd_array));
			if ($totCALLSmtd>0)
				{
				$totABANDONSpctmtd =	sprintf("%7.2f", (100*$totABANDONSmtd/$totCALLSmtd));
				}
			else
				{
				$totABANDONSpctmtd = "    0.0";
				}
			if ($totABANDONSmtd>0)
				{
				$totABANDONSavgTIMEmtd =	sprintf("%7s", date("i:s", mktime(0, 0, round($totABANDONSsecmtd/$totABANDONSmtd))));
				if (round($totABANDONSsecmtd/$totABANDONSmtd)>$max_mtd_avgabandontime) {$max_mtd_avgabandontime=round($totABANDONSsecmtd/$totABANDONSmtd);}
				$mtd_graph_stats[$ma][11]=round($totABANDONSsecmtd/$totABANDONSmtd);
				}
			else
				{
				$totABANDONSavgTIMEmtd = "  00:00";
				$mtd_graph_stats[$ma][11]=0;
				}
			if ($totANSWERSmtd>0)
				{
				$totANSWERSavgspeedTIMEmtd =	sprintf("%6s", date("i:s", mktime(0, 0, round($totANSWERSspeedmtd/$totANSWERSmtd))));
				$totANSWERSavgTIMEmtd =	sprintf("%6s", date("i:s", mktime(0, 0, round($totANSWERSsecmtd/$totANSWERSmtd))));
				if (round($totANSWERSspeedmtd/$totANSWERSmtd)>$max_mtd_avganswerspeed) {$max_mtd_avganswerspeed=round($totANSWERSspeedmtd/$totANSWERSmtd);}
				$mtd_graph_stats[$ma][12]=round($totANSWERSspeedmtd/$totANSWERSmtd);
				$mtd_graph_stats[$ma][16]=round($totANSWERSsecmtd/$totANSWERSmtd);
				}
			else
				{
				$totANSWERSavgspeedTIMEmtd = " 00:00";
				$totANSWERSavgTIMEmtd = " 00:00";
				$mtd_graph_stats[$ma][12]=0;
				$mtd_graph_stats[$ma][16]=0;
				}
			$totANSWERStalkTIMEmtd =	sprintf("%10s", floor($totANSWERSsecmtd/3600).date(":i:s", mktime(0, 0, $totANSWERSsecmtd)));
			$totANSWERSwrapTIMEmtd =	sprintf("%10s", floor(($totANSWERSmtd*15)/3600).date(":i:s", mktime(0, 0, ($totANSWERSmtd*15))));
			if (($totANSWERSmtd*15)>$max_mtd_totalwraptime) {$max_mtd_totalwraptime=($totANSWERSmtd*15);}
			$mtd_graph_stats[$ma][13]=($totANSWERSmtd*15);
			$mtd_graph_stats[$ma][14]=($totANSWERSsecmtd+($totANSWERSmtd*15));
			$mtd_graph_stats[$ma][15]=$totANSWERSsecmtd;
			$totANSWERStotTIMEmtd =	sprintf("%10s", floor(($totANSWERSsecmtd+($totANSWERSmtd*15))/3600).date(":i:s", mktime(0, 0, ($totANSWERSsecmtd+($totANSWERSmtd*15)))));
			$totAGENTSmtd=count(array_count_values($AGENTS_mtd_array));
			$totAGENTSmtd =	sprintf("%8s", $totAGENTSmtd);
			$totANSWERSmtd =	sprintf("%8s", $totANSWERSmtd);
			$totABANDONSmtd =	sprintf("%9s", $totABANDONSmtd);
			$totCALLSmtd =	sprintf("%7s", $totCALLSmtd);		

			if (trim($totCALLSmtd)>$max_mtd_offered) {$max_mtd_offered=trim($totCALLSmtd);}
			if (trim($totANSWERSmtd)>$max_mtd_answered) {$max_mtd_answered=trim($totANSWERSmtd);}
			if (trim($totAGENTSmtd)>$max_mtd_agents) {$max_mtd_agents=trim($totAGENTSmtd);}
			if (trim($totABANDONSmtd)>$max_mtd_abandoned) {$max_mtd_abandoned=trim($totABANDONSmtd);}
			if (trim($totABANDONSpctmtd)>$max_mtd_abandonpct) {$max_mtd_abandonpct=trim($totABANDONSpctmtd);}

			if (round($totANSWERSsecmtd/$totANSWERSmtd)>$max_mtd_avgtalktime) {$max_mtd_avgtalktime=round($totANSWERSsecmtd/$totANSWERSmtd);}
			if (trim($totANSWERSsecmtd)>$max_mtd_totaltalktime) {$max_mtd_totaltalktime=trim($totANSWERSsecmtd);}
			if (trim($totANSWERSsecmtd+($totANSWERSmtd*15))>$max_mtd_totalcalltime) {$max_mtd_totalcalltime=trim($totANSWERSsecmtd+($totANSWERSmtd*15));}

			$month=date("F", strtotime($dayEND[$d-1]));
			$year=substr($dayEND[$d-1], 0, 4);
			$mtd_graph_stats[$ma][0]="$month $year";
			$mtd_graph_stats[$ma][1]=trim($totCALLSmtd);
			$mtd_graph_stats[$ma][2]=trim($totANSWERSmtd);
			$mtd_graph_stats[$ma][3]=trim($totABANDONSmtd);
			$mtd_graph_stats[$ma][4]=trim($totABANDONSpctmtd);
			$mtd_graph_stats[$ma][5]=trim($totABANDONSavgTIMEmtd);
			$mtd_graph_stats[$ma][6]=trim($totANSWERSavgspeedTIMEmtd);
			$mtd_graph_stats[$ma][7]=trim($totANSWERSavgTIMEmtd);
			$mtd_graph_stats[$ma][8]=trim($totANSWERStalkTIMEmtd);
			$mtd_graph_stats[$ma][9]=trim($totANSWERSwrapTIMEmtd);
			$mtd_graph_stats[$ma][10]=trim($totANSWERStotTIMEmtd);
			$wtd_graph_stats[$ma][17]=trim($totAGENTSmtd);
			$mtd_OFFERED_graph=preg_replace('/DAILY/', 'MONTH-TO-DATE',$OFFERED_graph);
			$mtd_ANSWERED_graph=preg_replace('/DAILY/', 'MONTH-TO-DATE',$ANSWERED_graph);
			$mtd_AGENTS_graph=preg_replace('/DAILY/', 'MONTH-TO-DATE', $AGENTS_graph);
			$mtd_ABANDONED_graph=preg_replace('/DAILY/', 'MONTH-TO-DATE',$ABANDONED_graph);
			$mtd_ABANDONPCT_graph=preg_replace('/DAILY/', 'MONTH-TO-DATE',$ABANDONPCT_graph);
			$mtd_AVGABANDONTIME_graph=preg_replace('/DAILY/', 'MONTH-TO-DATE',$AVGABANDONTIME_graph);
			$mtd_AVGANSWERSPEED_graph=preg_replace('/DAILY/', 'MONTH-TO-DATE',$AVGANSWERSPEED_graph);
			$mtd_AVGTALKTIME_graph=preg_replace('/DAILY/', 'MONTH-TO-DATE',$AVGTALKTIME_graph);
			$mtd_TOTALTALKTIME_graph=preg_replace('/DAILY/', 'MONTH-TO-DATE',$TOTALTALKTIME_graph);
			$mtd_TOTALWRAPTIME_graph=preg_replace('/DAILY/', 'MONTH-TO-DATE',$TOTALWRAPTIME_graph);
			$mtd_TOTALCALLTIME_graph=preg_replace('/DAILY/', 'MONTH-TO-DATE',$TOTALCALLTIME_graph);
			for ($q=0; $q<count($mtd_graph_stats); $q++) {
				if ($q==0) {$class=" first";} else if (($q+1)==count($mtd_graph_stats)) {$class=" last";} else {$class="";}
				$mtd_OFFERED_graph.="  <tr><td class='chart_td$class'>".$mtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$mtd_graph_stats[$q][1]/$max_mtd_offered)."' height='16' />".$mtd_graph_stats[$q][1]."</td></tr>";
				$mtd_ANSWERED_graph.="  <tr><td class='chart_td$class'>".$mtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$mtd_graph_stats[$q][2]/$max_mtd_answered)."' height='16' />".$mtd_graph_stats[$q][2]."</td></tr>";
				$mtd_AGENTS_graph.="  <tr><td class='chart_td$class'>".$mtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$mtd_graph_stats[$q][17]/$max_mtd_agents)."' height='16' />".$mtd_graph_stats[$q][17]."</td></tr>";
				$mtd_ABANDONED_graph.="  <tr><td class='chart_td$class'>".$mtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$mtd_graph_stats[$q][3]/$max_mtd_abandoned)."' height='16' />".$mtd_graph_stats[$q][3]."</td></tr>";
				$mtd_ABANDONPCT_graph.="  <tr><td class='chart_td$class'>".$mtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$mtd_graph_stats[$q][4]/$max_mtd_abandonpct)."' height='16' />".$mtd_graph_stats[$q][4]."%</td></tr>";
				$mtd_AVGABANDONTIME_graph.="  <tr><td class='chart_td$class'>".$mtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$mtd_graph_stats[$q][11]/$max_mtd_avgabandontime)."' height='16' />".$mtd_graph_stats[$q][5]."</td></tr>";
				$mtd_AVGANSWERSPEED_graph.="  <tr><td class='chart_td$class'>".$mtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$mtd_graph_stats[$q][12]/$max_mtd_avganswerspeed)."' height='16' />".$mtd_graph_stats[$q][6]."</td></tr>";
				$mtd_AVGTALKTIME_graph.="  <tr><td class='chart_td$class'>".$mtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$mtd_graph_stats[$q][16]/$max_mtd_avgtalktime)."' height='16' />".$mtd_graph_stats[$q][7]."</td></tr>";
				$mtd_TOTALTALKTIME_graph.="  <tr><td class='chart_td$class'>".$mtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$mtd_graph_stats[$q][15]/$max_mtd_totaltalktime)."' height='16' />".$mtd_graph_stats[$q][8]."</td></tr>";
				$mtd_TOTALWRAPTIME_graph.="  <tr><td class='chart_td$class'>".$mtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$mtd_graph_stats[$q][13]/$max_mtd_totalwraptime)."' height='16' />".$mtd_graph_stats[$q][9]."</td></tr>";
				$mtd_TOTALCALLTIME_graph.="  <tr><td class='chart_td$class'>".$mtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$mtd_graph_stats[$q][14]/$max_mtd_totalcalltime)."' height='16' />".$mtd_graph_stats[$q][10]."</td></tr>";
				$graph_totCALLSmtd+=$mtd_graph_stats[$q][1];
				$graph_totANSWERSmtd+=$mtd_graph_stats[$q][2];
				$graph_totAGENTSmtd+=$mtd_graph_stats[$q][17];
				$graph_totABANDONSmtd+=$mtd_graph_stats[$q][3];
				$graph_totABANDONSpctmtd+=$mtd_graph_stats[$q][4];
				$graph_totABANDONSavgTIMEmtd+=$mtd_graph_stats[$q][5];
				$graph_totANSWERSavgspeedTIMEmtd+=$mtd_graph_stats[$q][6];
				$graph_totANSWERSavgTIMEmtd+=$mtd_graph_stats[$q][7];
				$graph_totANSWERStalkTIMEmtd+=$mtd_graph_stats[$q][8];
				$graph_totANSWERSwrapTIMEmtd+=$mtd_graph_stats[$q][9];
				$graph_totANSWERStotTIMEmtd+=$mtd_graph_stats[$q][10];
			}			

			$ASCII_text.="$MAINH\n";
			$ASCII_text.="|                                       MTD | $totCALLSmtd | $totANSWERSmtd | $totAGENTSmtd | $totABANDONSmtd | $totABANDONSpctmtd%| $totABANDONSavgTIMEmtd | $totANSWERSavgspeedTIMEmtd | $totANSWERSavgTIMEmtd | $totANSWERStalkTIMEmtd | $totANSWERSwrapTIMEmtd | $totANSWERStotTIMEmtd |";
			$CSV_text.="\"MTD\",\"$totCALLSmtd\",\"$totANSWERSmtd\",\"$totAGENTSmtd\",\"$totABANDONSmtd\",\"$totABANDONSpctmtd%\",\"$totABANDONSavgTIMEmtd\",\"$totANSWERSavgspeedTIMEmtd\",\"$totANSWERSavgTIMEmtd\",\"$totANSWERStalkTIMEmtd\",\"$totANSWERSwrapTIMEmtd\",\"$totANSWERStotTIMEmtd\"";
			for ($s=0; $s<count($status_array); $s++) {
				$ASCII_text.=" ".sprintf("%10s", ($totSTATUSESmtd[$status_array[$s][0]]+0))." |";
				$CSV_text.=",\"".sprintf("%10s", ($totSTATUSESmtd[$status_array[$s][0]]+0))."\"";
			}
			$ASCII_text.="\n";
			$CSV_text.="\n";
			
			$totCALLSmtd=0;
			$totANSWERSmtd=0;
			$AGENTS_mtd_array=array();
			$totANSWERSsecmtd=0;
			$totANSWERSspeedmtd=0;
			$totABANDONSmtd=0;
			$totABANDONSsecmtd=0;

	#		if (date("m", strtotime($daySTART[$d]))==1 || date("m", strtotime($daySTART[$d]))==4 || date("m", strtotime($daySTART[$d]))==7 || date("m", strtotime($daySTART[$d]))==10) # Quarterly line
	#			{
			$totAGENTSqtd=count(array_count_values($AGENTS_qtd_array));
				if ($totCALLSqtd>0)
					{
					$totABANDONSpctqtd =	sprintf("%7.2f", (100*$totABANDONSqtd/$totCALLSqtd));
					}
				else
					{
					$totABANDONSpctqtd = "    0.0";
					}
				if ($totABANDONSqtd>0)
					{
					$totABANDONSavgTIMEqtd =	sprintf("%7s", date("i:s", mktime(0, 0, round($totABANDONSsecqtd/$totABANDONSqtd))));
					if (round($totABANDONSsecqtd/$totABANDONSqtd)>$max_qtd_avgabandontime) {$max_qtd_avgabandontime=round($totABANDONSsecqtd/$totABANDONSqtd);}
					$qtd_graph_stats[$qa][11]=round($totABANDONSsecqtd/$totABANDONSqtd);
					}
				else
					{
					$totABANDONSavgTIMEqtd = "  00:00";
					$qtd_graph_stats[$qa][11]=0;
					}
				if ($totANSWERSqtd>0)
					{
					$totANSWERSavgspeedTIMEqtd =	sprintf("%6s", date("i:s", mktime(0, 0, round($totANSWERSspeedqtd/$totANSWERSqtd))));
					$totANSWERSavgTIMEqtd =	sprintf("%6s", date("i:s", mktime(0, 0, round($totANSWERSsecqtd/$totANSWERSqtd))));
					if (round($totANSWERSspeedqtd/$totANSWERSqtd)>$max_qtd_avganswerspeed) {$max_qtd_avganswerspeed=round($totANSWERSspeedqtd/$totANSWERSqtd);}
					$qtd_graph_stats[$qa][12]=round($totANSWERSspeedqtd/$totANSWERSqtd);
					$qtd_graph_stats[$qa][16]=round($totANSWERSsecqtd/$totANSWERSqtd);
				}
				else
					{
					$totANSWERSavgspeedTIMEqtd = " 00:00";
					$totANSWERSavgTIMEqtd = " 00:00";
					$qtd_graph_stats[$qa][12]=0;
					$qtd_graph_stats[$qa][16]=0;
					}
				$totANSWERStalkTIMEqtd =	sprintf("%10s", floor($totANSWERSsecqtd/3600).date(":i:s", mktime(0, 0, $totANSWERSsecqtd)));
				$totANSWERSwrapTIMEqtd =	sprintf("%10s", floor(($totANSWERSqtd*15)/3600).date(":i:s", mktime(0, 0, ($totANSWERSqtd*15))));
				if (($totANSWERSqtd*15)>$max_qtd_totalwraptime) {$max_qtd_totalwraptime=($totANSWERSqtd*15);}
				$qtd_graph_stats[$qa][13]=($totANSWERSqtd*15);
				$qtd_graph_stats[$qa][14]=($totANSWERSsecqtd+($totANSWERSqtd*15));
				$qtd_graph_stats[$qa][15]=$totANSWERSsecqtd;
				$totANSWERStotTIMEqtd =	sprintf("%10s", floor(($totANSWERSsecqtd+($totANSWERSqtd*15))/3600).date(":i:s", mktime(0, 0, ($totANSWERSsecqtd+($totANSWERSqtd*15)))));
				$totAGENTSqtd=count(array_count_values($AGENTS_qtd_array));
				$totAGENTSqtd =	sprintf("%8s", $totAGENTSqtd);
				$totANSWERSqtd =	sprintf("%8s", $totANSWERSqtd);
				$totABANDONSqtd =	sprintf("%9s", $totABANDONSqtd);
				$totCALLSqtd =	sprintf("%7s", $totCALLSqtd);		

				if (trim($totCALLSqtd)>$max_qtd_offered) {$max_qtd_offered=trim($totCALLSqtd);}
				if (trim($totANSWERSqtd)>$max_qtd_answered) {$max_qtd_answered=trim($totANSWERSqtd);}
				if (trim($totAGENTSqtd)>$max_qtd_agents) {$max_qtd_agents=trim($totAGENTSqtd);}
				if (trim($totABANDONSqtd)>$max_qtd_abandoned) {$max_qtd_abandoned=trim($totABANDONSqtd);}
				if (trim($totABANDONSpctqtd)>$max_qtd_abandonpct) {$max_qtd_abandonpct=trim($totABANDONSpctqtd);}

				if (round($totANSWERSsecqtd/$totANSWERSqtd)>$max_qtd_avgtalktime) {$max_qtd_avgtalktime=round($totANSWERSsecqtd/$totANSWERSqtd);}
				if (trim($totANSWERSsecqtd)>$max_qtd_totaltalktime) {$max_qtd_totaltalktime=trim($totANSWERSsecqtd);}
				if (trim($totANSWERSsecqtd+($totANSWERSqtd*15))>$max_qtd_totalcalltime) {$max_qtd_totalcalltime=trim($totANSWERSsecqtd+($totANSWERSqtd*15));}

				$month=date("m", strtotime($dayEND[$d-1]));
				$year=substr($dayEND[$d-1], 0, 4);
				$qtr1=array(01,02,03);
				$qtr2=array(04,05,06);
				$qtr3=array(07,08,09);
				$qtr4=array(10,11,12);
				if(in_array($month,$qtr1)) {
					$qtr="1st";
				} else if(in_array($month,$qtr2)) {
					$qtr="2nd";
				}  else if(in_array($month,$qtr3)) {
					$qtr="3rd";
				}  else if(in_array($month,$qtr4)) {
					$qtr="4th";
				}
				$qtd_graph_stats[$qa][0]="$qtr quarter, $year";
				$qtd_graph_stats[$qa][1]=trim($totCALLSqtd);
				$qtd_graph_stats[$qa][2]=trim($totANSWERSqtd);
				$qtd_graph_stats[$qa][3]=trim($totABANDONSqtd);
				$qtd_graph_stats[$qa][4]=trim($totABANDONSpctqtd);
				$qtd_graph_stats[$qa][5]=trim($totABANDONSavgTIMEqtd);
				$qtd_graph_stats[$qa][6]=trim($totANSWERSavgspeedTIMEqtd);
				$qtd_graph_stats[$qa][7]=trim($totANSWERSavgTIMEqtd);
				$qtd_graph_stats[$qa][8]=trim($totANSWERStalkTIMEqtd);
				$qtd_graph_stats[$qa][9]=trim($totANSWERSwrapTIMEqtd);
				$qtd_graph_stats[$qa][10]=trim($totANSWERStotTIMEqtd);
				$qtd_graph_stats[$qa][17]=trim($totAGENTSqtd);
				$qtd_OFFERED_graph=preg_replace('/DAILY/', 'QUARTER-TO-DATE',$OFFERED_graph);
				$qtd_ANSWERED_graph=preg_replace('/DAILY/', 'QUARTER-TO-DATE',$ANSWERED_graph);
				$qtd_AGENTS_graph=preg_replace('/DAILY/', 'QUARTER-TO-DATE',$AGENTS_graph);
				$qtd_ABANDONED_graph=preg_replace('/DAILY/', 'QUARTER-TO-DATE',$ABANDONED_graph);
				$qtd_ABANDONPCT_graph=preg_replace('/DAILY/', 'QUARTER-TO-DATE',$ABANDONPCT_graph);
				$qtd_AVGABANDONTIME_graph=preg_replace('/DAILY/', 'QUARTER-TO-DATE',$AVGABANDONTIME_graph);
				$qtd_AVGANSWERSPEED_graph=preg_replace('/DAILY/', 'QUARTER-TO-DATE',$AVGANSWERSPEED_graph);
				$qtd_AVGTALKTIME_graph=preg_replace('/DAILY/', 'QUARTER-TO-DATE',$AVGTALKTIME_graph);
				$qtd_TOTALTALKTIME_graph=preg_replace('/DAILY/', 'QUARTER-TO-DATE',$TOTALTALKTIME_graph);
				$qtd_TOTALWRAPTIME_graph=preg_replace('/DAILY/', 'QUARTER-TO-DATE',$TOTALWRAPTIME_graph);
				$qtd_TOTALCALLTIME_graph=preg_replace('/DAILY/', 'QUARTER-TO-DATE',$TOTALCALLTIME_graph);
				for ($q=0; $q<count($qtd_graph_stats); $q++) {
					if ($q==0) {$class=" first";} else if (($q+1)==count($qtd_graph_stats)) {$class=" last";} else {$class="";}
					$qtd_OFFERED_graph.="  <tr><td class='chart_td$class'>".$qtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$qtd_graph_stats[$q][1]/$max_qtd_offered)."' height='16' />".$qtd_graph_stats[$q][1]."</td></tr>";
					$qtd_ANSWERED_graph.="  <tr><td class='chart_td$class'>".$qtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$qtd_graph_stats[$q][2]/$max_qtd_answered)."' height='16' />".$qtd_graph_stats[$q][2]."</td></tr>";
					$qtd_AGENTS_graph.="  <tr><td class='chart_td$class'>".$qtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$qtd_graph_stats[$q][17]/$max_qtd_agents)."' height='16' />".$qtd_graph_stats[$q][17]."</td></tr>";
					$qtd_ABANDONED_graph.="  <tr><td class='chart_td$class'>".$qtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$qtd_graph_stats[$q][3]/$max_qtd_abandoned)."' height='16' />".$qtd_graph_stats[$q][3]."</td></tr>";
					$qtd_ABANDONPCT_graph.="  <tr><td class='chart_td$class'>".$qtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$qtd_graph_stats[$q][4]/$max_qtd_abandonpct)."' height='16' />".$qtd_graph_stats[$q][4]."%</td></tr>";
					$qtd_AVGABANDONTIME_graph.="  <tr><td class='chart_td$class'>".$qtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$qtd_graph_stats[$q][11]/$max_qtd_avgabandontime)."' height='16' />".$qtd_graph_stats[$q][5]."</td></tr>";
					$qtd_AVGANSWERSPEED_graph.="  <tr><td class='chart_td$class'>".$qtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$qtd_graph_stats[$q][12]/$max_qtd_avganswerspeed)."' height='16' />".$qtd_graph_stats[$q][6]."</td></tr>";
					$qtd_AVGTALKTIME_graph.="  <tr><td class='chart_td$class'>".$qtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$qtd_graph_stats[$q][16]/$max_qtd_avgtalktime)."' height='16' />".$qtd_graph_stats[$q][7]."</td></tr>";
					$qtd_TOTALTALKTIME_graph.="  <tr><td class='chart_td$class'>".$qtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$qtd_graph_stats[$q][15]/$max_qtd_totaltalktime)."' height='16' />".$qtd_graph_stats[$q][8]."</td></tr>";
					$qtd_TOTALWRAPTIME_graph.="  <tr><td class='chart_td$class'>".$qtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$qtd_graph_stats[$q][13]/$max_qtd_totalwraptime)."' height='16' />".$qtd_graph_stats[$q][9]."</td></tr>";
					$qtd_TOTALCALLTIME_graph.="  <tr><td class='chart_td$class'>".$qtd_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$qtd_graph_stats[$q][14]/$max_qtd_totalcalltime)."' height='16' />".$qtd_graph_stats[$q][10]."</td></tr>";
					$graph_totCALLSqtd+=$qtd_graph_stats[$q][1];
					$graph_totANSWERSqtd+=$qtd_graph_stats[$q][2];
					$graph_totAGENTSqtd+=$qtd_graph_stats[$q][17];
					$graph_totABANDONSqtd+=$qtd_graph_stats[$q][3];
					$graph_totABANDONSpctqtd+=$qtd_graph_stats[$q][4];
					$graph_totABANDONSavgTIMEqtd+=$qtd_graph_stats[$q][5];
					$graph_totANSWERSavgspeedTIMEqtd+=$qtd_graph_stats[$q][6];
					$graph_totANSWERSavgTIMEqtd+=$qtd_graph_stats[$q][7];
					$graph_totANSWERStalkTIMEqtd+=$qtd_graph_stats[$q][8];
					$graph_totANSWERSwrapTIMEqtd+=$qtd_graph_stats[$q][9];
					$graph_totANSWERStotTIMEqtd+=$qtd_graph_stats[$q][10];
				}

				$ASCII_text.="$MAINH\n";
				$ASCII_text.="|                                       QTD | $totCALLSqtd | $totANSWERSqtd | $totAGENTSqtd | $totABANDONSqtd | $totABANDONSpctqtd%| $totABANDONSavgTIMEqtd | $totANSWERSavgspeedTIMEqtd | $totANSWERSavgTIMEqtd | $totANSWERStalkTIMEqtd | $totANSWERSwrapTIMEqtd | $totANSWERStotTIMEqtd |";
				$CSV_text.="\"QTD\",\"$totCALLSqtd\",\"$totANSWERSqtd\",\"$totAGENTSqtd\",\"$totABANDONSqtd\",\"$totABANDONSpctqtd%\",\"$totABANDONSavgTIMEqtd\",\"$totANSWERSavgspeedTIMEqtd\",\"$totANSWERSavgTIMEqtd\",\"$totANSWERStalkTIMEqtd\",\"$totANSWERSwrapTIMEqtd\",\"$totANSWERStotTIMEqtd\"";
				for ($s=0; $s<count($status_array); $s++) {
					$ASCII_text.=" ".sprintf("%10s", ($totSTATUSESqtd[$status_array[$s][0]]+0))." |";
					$CSV_text.=",\"".sprintf("%10s", ($totSTATUSESqtd[$status_array[$s][0]]+0))."\"";
				}
				$ASCII_text.="\n";
				$CSV_text.="\n";

				$totCALLSqtd=0;
				$totANSWERSqtd=0;
				$AGENTS_qtd_array=array();
				$totANSWERSsecqtd=0;
				$totANSWERSspeedqtd=0;
				$totABANDONSqtd=0;
				$totABANDONSsecqtd=0;
	#			}
		}

			for ($q=0; $q<count($graph_stats); $q++) {
				if ($q==0) {$class=" first";} else if (($q+1)==count($graph_stats)) {$class=" last";} else {$class="";}
				$OFFERED_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][1]/$max_offered)."' height='16' />".$graph_stats[$q][1]."</td></tr>";
				$ANSWERED_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][2]/$max_answered)."' height='16' />".$graph_stats[$q][2]."</td></tr>";
				$AGENTS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][17]/$max_agents)."' height='16' />".$graph_stats[$q][17]."</td></tr>";
				$ABANDONED_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][3]/$max_abandoned)."' height='16' />".$graph_stats[$q][3]."</td></tr>";
				$ABANDONPCT_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][4]/$max_abandonpct)."' height='16' />".$graph_stats[$q][4]."%</td></tr>";
				$AVGABANDONTIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][11]/$max_avgabandontime)."' height='16' />".$graph_stats[$q][5]."</td></tr>";
				$AVGANSWERSPEED_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][12]/$max_avganswerspeed)."' height='16' />".$graph_stats[$q][6]."</td></tr>";
				$AVGTALKTIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][16]/$max_avgtalktime)."' height='16' />".$graph_stats[$q][7]."</td></tr>";
				$TOTALTALKTIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][15]/$max_totaltalktime)."' height='16' />".$graph_stats[$q][8]."</td></tr>";
				$TOTALWRAPTIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][13]/$max_totalwraptime)."' height='16' />".$graph_stats[$q][9]."</td></tr>";
				$TOTALCALLTIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][14]/$max_totalcalltime)."' height='16' />".$graph_stats[$q][10]."</td></tr>";
			}
			$OFFERED_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotCALLS)."</th></tr></table>";
			$ANSWERED_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERS)."</th></tr></table>";
			$AGENTS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotAGENTS)."</th></tr></table>";
			$ABANDONED_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotABANDONS)."</th></tr></table>";
			$ABANDONPCT_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotABANDONSpct)."%</th></tr></table>";
			$AVGABANDONTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotABANDONSavgTIME)."</th></tr></table>";
			$AVGANSWERSPEED_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERSavgspeedTIME)."</th></tr></table>";
			$AVGTALKTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERSavgTIME)."</th></tr></table>";
			$TOTALTALKTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERStalkTIME)."</th></tr></table>";
			$TOTALWRAPTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERSwrapTIME)."</th></tr></table>";
			$TOTALCALLTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERStotTIME)."</th></tr></table>";
			$JS_onload.="\tDrawGraph('OFFERED', '1');\n"; 
			$JS_text.="function DrawGraph(graph, th_id) {\n";
			$JS_text.="	var OFFERED_graph=\"$OFFERED_graph\";\n";
			$JS_text.="	var ANSWERED_graph=\"$ANSWERED_graph\";\n";
			$JS_text.="	var AGENTS_graph=\"$AGENTS_graph\";\n";
			$JS_text.="	var ABANDONED_graph=\"$ABANDONED_graph\";\n";
			$JS_text.="	var ABANDONPCT_graph=\"$ABANDONPCT_graph\";\n";
			$JS_text.="	var AVGABANDONTIME_graph=\"$AVGABANDONTIME_graph\";\n";
			$JS_text.="	var AVGANSWERSPEED_graph=\"$AVGANSWERSPEED_graph\";\n";
			$JS_text.="	var AVGTALKTIME_graph=\"$AVGTALKTIME_graph\";\n";
			$JS_text.="	var TOTALTALKTIME_graph=\"$TOTALTALKTIME_graph\";\n";
			$JS_text.="	var TOTALWRAPTIME_graph=\"$TOTALWRAPTIME_graph\";\n";
			$JS_text.="	var TOTALCALLTIME_graph=\"$TOTALCALLTIME_graph\";\n";
			$JS_text.="\n";
			$JS_text.="	for (var i=1; i<=10; i++) {\n";
			$JS_text.="		var cellID=\"multigroup_graph\"+i;\n";
			$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
			$JS_text.="	}\n";
			$JS_text.="	var cellID=\"multigroup_graph\"+th_id;\n";
			$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
			$JS_text.="	var graph_to_display=eval(graph+\"_graph\");\n";
			$JS_text.="	document.getElementById('stats_graph').innerHTML=graph_to_display;\n";
			$JS_text.="}\n";
			$wtd_OFFERED_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotCALLS)."</th></tr></table>";
			$wtd_ANSWERED_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERS)."</th></tr></table>";
			$wtd_AGENTS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotAGENTS)."</th></tr></table>";
			$wtd_ABANDONED_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotABANDONS)."</th></tr></table>";
			$wtd_ABANDONPCT_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotABANDONSpct)."%</th></tr></table>";
			$wtd_AVGABANDONTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotABANDONSavgTIME)."</th></tr></table>";
			$wtd_AVGANSWERSPEED_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERSavgspeedTIME)."</th></tr></table>";
			$wtd_AVGTALKTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERSavgTIME)."</th></tr></table>";
			$wtd_TOTALTALKTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERStalkTIME)."</th></tr></table>";
			$wtd_TOTALWRAPTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERSwrapTIME)."</th></tr></table>";
			$wtd_TOTALCALLTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERStotTIME)."</th></tr></table>";
			$JS_onload.="\tDrawWTDGraph('OFFERED', '1');\n"; 
			$JS_text.="function DrawWTDGraph(graph, th_id) {\n";
			$JS_text.="	var OFFERED_graph=\"$wtd_OFFERED_graph\";\n";
			$JS_text.="	var ANSWERED_graph=\"$wtd_ANSWERED_graph\";\n";
			$JS_text.="	var AGENTS_graph=\"$wtd_AGENTS_graph\";\n";
			$JS_text.="	var ABANDONED_graph=\"$wtd_ABANDONED_graph\";\n";
			$JS_text.="	var ABANDONPCT_graph=\"$wtd_ABANDONPCT_graph\";\n";
			$JS_text.="	var AVGABANDONTIME_graph=\"$wtd_AVGABANDONTIME_graph\";\n";
			$JS_text.="	var AVGANSWERSPEED_graph=\"$wtd_AVGANSWERSPEED_graph\";\n";
			$JS_text.="	var AVGTALKTIME_graph=\"$wtd_AVGTALKTIME_graph\";\n";
			$JS_text.="	var TOTALTALKTIME_graph=\"$wtd_TOTALTALKTIME_graph\";\n";
			$JS_text.="	var TOTALWRAPTIME_graph=\"$wtd_TOTALWRAPTIME_graph\";\n";
			$JS_text.="	var TOTALCALLTIME_graph=\"$wtd_TOTALCALLTIME_graph\";\n";
			$JS_text.="\n";
			$JS_text.="	for (var i=1; i<=10; i++) {\n";
			$JS_text.="		var cellID=\"WTD_graph\"+i;\n";
			$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
			$JS_text.="	}\n";
			$JS_text.="	var cellID=\"WTD_graph\"+th_id;\n";
			$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
			$JS_text.="	var graph_to_display=eval(graph+\"_graph\");\n";
			$JS_text.="	document.getElementById('WTD_stats_graph').innerHTML=graph_to_display;\n";
			$JS_text.="}\n";
			$mtd_OFFERED_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotCALLS)."</th></tr></table>";
			$mtd_ANSWERED_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERS)."</th></tr></table>";
			$mtd_AGENTS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotAGENTS)."</th></tr></table>";
			$mtd_ABANDONED_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotABANDONS)."</th></tr></table>";
			$mtd_ABANDONPCT_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotABANDONSpct)."%</th></tr></table>";
			$mtd_AVGABANDONTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotABANDONSavgTIME)."</th></tr></table>";
			$mtd_AVGANSWERSPEED_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERSavgspeedTIME)."</th></tr></table>";
			$mtd_AVGTALKTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERSavgTIME)."</th></tr></table>";
			$mtd_TOTALTALKTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERStalkTIME)."</th></tr></table>";
			$mtd_TOTALWRAPTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERSwrapTIME)."</th></tr></table>";
			$mtd_TOTALCALLTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERStotTIME)."</th></tr></table>";
			$JS_onload.="\tDrawMTDGraph('OFFERED', '1');\n"; 
			$JS_text.="function DrawMTDGraph(graph, th_id) {\n";
			$JS_text.="	var OFFERED_graph=\"$mtd_OFFERED_graph\";\n";
			$JS_text.="	var ANSWERED_graph=\"$mtd_ANSWERED_graph\";\n";
			$JS_text.="	var AGENTS_graph=\"$mtd_AGENTS_graph\";\n";
			$JS_text.="	var ABANDONED_graph=\"$mtd_ABANDONED_graph\";\n";
			$JS_text.="	var ABANDONPCT_graph=\"$mtd_ABANDONPCT_graph\";\n";
			$JS_text.="	var AVGABANDONTIME_graph=\"$mtd_AVGABANDONTIME_graph\";\n";
			$JS_text.="	var AVGANSWERSPEED_graph=\"$mtd_AVGANSWERSPEED_graph\";\n";
			$JS_text.="	var AVGTALKTIME_graph=\"$mtd_AVGTALKTIME_graph\";\n";
			$JS_text.="	var TOTALTALKTIME_graph=\"$mtd_TOTALTALKTIME_graph\";\n";
			$JS_text.="	var TOTALWRAPTIME_graph=\"$mtd_TOTALWRAPTIME_graph\";\n";
			$JS_text.="	var TOTALCALLTIME_graph=\"$mtd_TOTALCALLTIME_graph\";\n";
			$JS_text.="\n";
			$JS_text.="	for (var i=1; i<=10; i++) {\n";
			$JS_text.="		var cellID=\"MTD_graph\"+i;\n";
			$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
			$JS_text.="	}\n";
			$JS_text.="	var cellID=\"MTD_graph\"+th_id;\n";
			$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
			$JS_text.="	var graph_to_display=eval(graph+\"_graph\");\n";
			$JS_text.="	document.getElementById('MTD_stats_graph').innerHTML=graph_to_display;\n";
			$JS_text.="}\n";
			$qtd_OFFERED_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotCALLS)."</th></tr></table>";
			$qtd_ANSWERED_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERS)."</th></tr></table>";
			$qtd_AGENTS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotAGENTS)."</th></tr></table>";
			$qtd_ABANDONED_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotABANDONS)."</th></tr></table>";
			$qtd_ABANDONPCT_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotABANDONSpct)."%</th></tr></table>";
			$qtd_AVGABANDONTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotABANDONSavgTIME)."</th></tr></table>";
			$qtd_AVGANSWERSPEED_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERSavgspeedTIME)."</th></tr></table>";
			$qtd_AVGTALKTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERSavgTIME)."</th></tr></table>";
			$qtd_TOTALTALKTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERStalkTIME)."</th></tr></table>";
			$qtd_TOTALWRAPTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERSwrapTIME)."</th></tr></table>";
			$qtd_TOTALCALLTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotANSWERStotTIME)."</th></tr></table>";
			$JS_onload.="\tDrawQTDGraph('OFFERED', '1');\n"; 
			$JS_text.="function DrawQTDGraph(graph, th_id) {\n";
			$JS_text.="	var OFFERED_graph=\"$qtd_OFFERED_graph\";\n";
			$JS_text.="	var ANSWERED_graph=\"$qtd_ANSWERED_graph\";\n";
			$JS_text.="	var AGENTS_graph=\"$qtd_AGENTS_graph\";\n";
			$JS_text.="	var ABANDONED_graph=\"$qtd_ABANDONED_graph\";\n";
			$JS_text.="	var ABANDONPCT_graph=\"$qtd_ABANDONPCT_graph\";\n";
			$JS_text.="	var AVGABANDONTIME_graph=\"$qtd_AVGABANDONTIME_graph\";\n";
			$JS_text.="	var AVGANSWERSPEED_graph=\"$qtd_AVGANSWERSPEED_graph\";\n";
			$JS_text.="	var AVGTALKTIME_graph=\"$qtd_AVGTALKTIME_graph\";\n";
			$JS_text.="	var TOTALTALKTIME_graph=\"$qtd_TOTALTALKTIME_graph\";\n";
			$JS_text.="	var TOTALWRAPTIME_graph=\"$qtd_TOTALWRAPTIME_graph\";\n";
			$JS_text.="	var TOTALCALLTIME_graph=\"$qtd_TOTALCALLTIME_graph\";\n";
			$JS_text.="\n";
			$JS_text.="	for (var i=1; i<=10; i++) {\n";
			$JS_text.="		var cellID=\"QTD_graph\"+i;\n";
			$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
			$JS_text.="	}\n";
			$JS_text.="	var cellID=\"QTD_graph\"+th_id;\n";
			$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
			$JS_text.="	var graph_to_display=eval(graph+\"_graph\");\n";
			$JS_text.="	document.getElementById('QTD_stats_graph').innerHTML=graph_to_display;\n";
			$JS_text.="}\n";
			$FtotAGENTS =	sprintf("%8s", $FtotAGENTS);

	$ASCII_text.="$MAINH\n";
	$ASCII_text.="|                                    TOTALS | $FtotCALLS | $FtotANSWERS | $FtotAGENTS | $FtotABANDONS | $FtotABANDONSpct%| $FtotABANDONSavgTIME | $FtotANSWERSavgspeedTIME | $FtotANSWERSavgTIME | $FtotANSWERStalkTIME | $FtotANSWERSwrapTIME | $FtotANSWERStotTIME |";
	$CSV_text.="\"TOTALS\",\"$FtotCALLS\",\"$FtotANSWERS\",\"$FtotAGENTS\",\"$FtotABANDONS\",\"$FtotABANDONSpct%\",\"$FtotABANDONSavgTIME\",\"$FtotANSWERSavgspeedTIME\",\"$FtotANSWERSavgTIME\",\"$FtotANSWERStalkTIME\",\"$FtotANSWERSwrapTIME\",\"$FtotANSWERStotTIME\"";
	for ($s=0; $s<count($status_array); $s++) {
		$ASCII_text.=" ".sprintf("%10s", ($totSTATUSES[$status_array[$s][0]]+0))." |";
		$CSV_text.=",\"".sprintf("%10s", ($totSTATUSES[$status_array[$s][0]]+0))."\"";
	}
	$ASCII_text.="\n";
	$CSV_text.="\n\n\n";
	$ASCII_text.="$MAINH\n\n\n";

	if ($show_disposition_statuses) 
		{
		$total_count=0;
		$ASCII_text.="+--------+----------------------+------------+\n";
		$ASCII_text.="| STATUS | DESCRIPTION          | CALLS      |\n";
		$ASCII_text.="+--------+----------------------+------------+\n";
		$CSV_text.="\"STATUS\",\"DISPOSITION\",\"CALLS\"\n";
		for ($s=0; $s<count($status_array); $s++) {
			$status_code=$status_array[$s][0];
			$status_name=$status_array[$s][1];
			$status_count=$totSTATUSES[$status_array[$s][0]]+0;
			#$MAIN.=" ".sprintf("%8s", ($totSTATUSES[$status_array[$s][0]]+0))." |";
			#$CSV_text.=",\"".sprintf("%8s", ($totSTATUSES[$status_array[$s][0]]+0))."\"";
			$ASCII_text.="| ".sprintf("%-6s", substr($status_code,0,6))." | ".sprintf("%-20s", substr($status_name,0,20))." | ".sprintf("%10s", $status_count)." |\n";
			$CSV_text.="\"$status_code\",\"$status_name\",\"$status_count\"\n";
			$total_count+=$status_count;
		}
		$ASCII_text.="+--------+----------------------+------------+\n";
		$ASCII_text.="| TOTAL:                        | ".sprintf("%10s", $total_count)." |\n";
		$ASCII_text.="+-------------------------------+------------+\n";
		$CSV_text.="\"\",\"TOTAL:\",\"$total_count\"\n";
		}

	## FORMAT OUTPUT ##
	$i=0;
	$hi_hour_count=0;
	$hi_hold_count=0;

	while ($i < $TOTintervals)
		{
		if ($qrtCALLS[$i] > 0)
			{$qrtCALLSavg[$i] = ($qrtCALLSsec[$i] / $qrtCALLS[$i]);}
		else {$qrtCALLSavg[$i] = 0;}
		if ($qrtDROPS[$i] > 0)
			{$qrtDROPSavg[$i] = ($qrtDROPSsec[$i] / $qrtDROPS[$i]);}
		else {$qrtDROPSavg[$i] = 0;}
		if ($qrtQUEUE[$i] > 0)
			{$qrtQUEUEavg[$i] = ($qrtQUEUEsec[$i] / $qrtQUEUE[$i]);}
		else {$qrtQUEUEavg[$i] = 0;}

		if ($qrtCALLS[$i] > $hi_hour_count) {$hi_hour_count = $qrtCALLS[$i];}
		if ($qrtQUEUEavg[$i] > $hi_hold_count) {$hi_hold_count = $qrtQUEUEavg[$i];}

		$qrtQUEUEavg[$i] = round($qrtQUEUEavg[$i], 0);
		if (strlen($qrtQUEUEavg[$i])<1) {$qrtQUEUEavg[$i]=0;}
		$qrtQUEUEmax[$i] = round($qrtQUEUEmax[$i], 0);
		if (strlen($qrtQUEUEmax[$i])<1) {$qrtQUEUEmax[$i]=0;}

		$i++;
		}

	$JS_onload.="}\n";
	$JS_text.=$JS_onload;
	$JS_text.="</script>\n";

	if ($report_display_type=="HTML") 
		{
		$MAIN.=$JS_text.$GRAPH.$WTD_GRAPH.$MTD_GRAPH.$QTD_GRAPH;
		}
	else
		{
		$MAIN.=$ASCII_text;
		}

	if ($hi_hour_count < 1)
		{$hour_multiplier = 0;}
	else
		{$hour_multiplier = (20 / $hi_hour_count);}
	if ($hi_hold_count < 1)
		{$hold_multiplier = 0;}
	else
		{$hold_multiplier = (20 / $hi_hold_count);}


	$ENDtime = date("U");
	$RUNtime = ($ENDtime - $STARTtime);
	$MAIN.="\nRun Time: $RUNtime seconds|$db_source\n";
	$MAIN.="</PRE>\n";
	$MAIN.="</TD></TR></TABLE>\n";
	$MAIN.="</FORM>\n\n";
	$MAIN.="</BODY></HTML>\n";

	if ($file_download > 0)
		{
		$FILE_TIME = date("Ymd-His");
		$CSVfilename = "Inbound_Daily_Report_$US$FILE_TIME.csv";
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
		echo "$HEADER";
		require("admin_header.php");
		echo "$MAIN";
		}
	}

if ($db_source == 'S')
	{
	mysql_close($link);
	$use_slave_server=0;
	$db_source = 'M';
	require("dbconnect.php");
	if ($file_download < 1) 
		{echo "<!-- Switching back to Master server to log report run time $VARDB_server $db_source -->\n";}
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





function HourDifference($time_BEGIN, $time_END) {
	global $hpd;
	$TB_array =	explode(':',$time_BEGIN);
	$TE_array =	explode(':',$time_END);
	$TBoffset=0;
	$TEoffset=0;

	if ($TB_array[0]>24) {
		while($TB_array[0]>24) {
			$TB_array[0]--;
			$TBoffset+=3600;
		}
		$time_BEGIN="$TB_array[0]:$TB_array[1]:$TB_array[2]";
	}
	if ($TE_array[0]>24) {
		while($TE_array[0]>24) {
			$TE_array[0]--;
			$TEoffset+=3600;
		}
		$time_END="$TE_array[0]:$TE_array[1]:$TE_array[2]";
	}
	$time1 = strtotime($time_BEGIN)+$TBoffset;
	$time2 = strtotime($time_END)+1+$TEoffset;
	$hpd = ceil(($time2 - $time1) / 3600);
	if ($hpd<0) {$hpd+=24;}
}

function RecalculateEpochs($query_date_BEGIN, $query_date_END) {
	global $query_date_BEGIN, $query_date_END, $SQepoch, $EQepoch, $SQepochDAY, $SQsec, $EQsec;
	$SQdate_ARY =	explode(' ',$query_date_BEGIN);
	$SQday_ARY =	explode('-',$SQdate_ARY[0]);
	$SQtime_ARY =	explode(':',$SQdate_ARY[1]);
	#$EQdate_ARY =	explode(' ',$query_date_END);
	#$EQday_ARY =	explode('-',$EQdate_ARY[0]);
	#$EQtime_ARY =	explode(':',$EQdate_ARY[1]);

	$SQepochDAY = mktime(0, 0, 0, $SQday_ARY[1], $SQday_ARY[2], $SQday_ARY[0]);
	$SQepoch = mktime($SQtime_ARY[0], $SQtime_ARY[1], $SQtime_ARY[2], $SQday_ARY[1], $SQday_ARY[2], $SQday_ARY[0]);
	#$EQepoch = mktime($EQtime_ARY[0], $EQtime_ARY[1], $EQtime_ARY[2], $EQday_ARY[1], $EQday_ARY[2], $EQday_ARY[0]);

	$SQsec = ( ($SQtime_ARY[0] * 3600) + ($SQtime_ARY[1] * 60) + ($SQtime_ARY[2] * 1) );
	#$EQsec = ( ($EQtime_ARY[0] * 3600) + ($EQtime_ARY[1] * 60) + ($EQtime_ARY[2] * 1) );
}

function RecalculateHPD($query_date_BEGIN, $query_date_END, $time_BEGIN, $time_END) {
	global $hpd, $query_date_BEGIN, $query_date_END, $time_BEGIN, $time_END;
	global $DURATIONday, $SQepoch, $EQepoch, $SQepochDAY, $SQsec, $EQsec;

	$TB_array =	explode(':',$time_BEGIN);
	$TE_array =	explode(':',$time_END);
	$TBoffset=0;
	$TEoffset=0;

	if ($TB_array[0]>24) {
		while($TB_array[0]>24) {
			$TB_array[0]--;
			$TBoffset+=3600;
		}
		$time_BEGIN="$TB_array[0]:$TB_array[1]:$TB_array[2]";
	}
	if ($TE_array[0]>24) {
		while($TE_array[0]>24) {
			$TE_array[0]--;
			$TEoffset+=3600;
		}
		$time_END="$TE_array[0]:$TE_array[1]:$TE_array[2]";
	}
	$time1 = strtotime($time_BEGIN)+$TBoffset;
	$time2 = strtotime($time_END)+1+$TEoffset;
	$hpd = ceil(($time2 - $time1) / 3600);
	if ($hpd<0) {$hpd+=24;}

	$SQdate_ARY =	explode(' ',$query_date_BEGIN);
	$SQday_ARY =	explode('-',$SQdate_ARY[0]);
	$SQtime_ARY =	explode(':',$SQdate_ARY[1]);
	$EQdate_ARY =	explode(' ',$query_date_END);
	$EQday_ARY =	explode('-',$EQdate_ARY[0]);
	$EQtime_ARY =	explode(':',$EQdate_ARY[1]);

	$SQepochDAY = mktime(0, 0, 0, $SQday_ARY[1], $SQday_ARY[2], $SQday_ARY[0]);
	$SQepoch = mktime($SQtime_ARY[0], $SQtime_ARY[1], $SQtime_ARY[2], $SQday_ARY[1], $SQday_ARY[2], $SQday_ARY[0]);
	$EQepoch = mktime($EQtime_ARY[0], $EQtime_ARY[1], $EQtime_ARY[2], $EQday_ARY[1], $EQday_ARY[2], $EQday_ARY[0]);

	$SQsec = ( ($SQtime_ARY[0] * 3600) + ($SQtime_ARY[1] * 60) + ($SQtime_ARY[2] * 1) );
	$EQsec = ( ($EQtime_ARY[0] * 3600) + ($EQtime_ARY[1] * 60) + ($EQtime_ARY[2] * 1) );

	if (!$DURATIONday) 
		{
		$DURATIONsec = ($EQepoch - $SQepoch);
		$DURATIONday = intval( ($DURATIONsec / 86400) + 1 );

		if ( ($EQsec < $SQsec) and ($DURATIONday < 1) )
			{
			$EQepoch = ($SQepochDAY + ($EQsec + 86400) );
			$query_date_END = date("Y-m-d H:i:s", $EQepoch);
			$DURATIONday++;
			}
		}
}
?>
