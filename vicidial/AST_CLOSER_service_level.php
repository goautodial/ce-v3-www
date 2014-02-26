<?php 
# AST_CLOSER_service_level.php
# 
# Copyright (C) 2013  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES
#
# 80509-0943 - First build
# 80510-1500 - Added fixed scale hold time graph
# 80519-0413 - rewrote time intervals code and stats gathering code
# 80528-2320 - fixed small calculation bugs and display bugs, added more shifts
# 90310-2117 - Added admin header
# 90508-0644 - Changed to PHP long tags
# 90801-0923 - Added in-group name to pulldown
# 100214-1421 - Sort menu alphabetically
# 100216-0042 - Added popup date selector
# 100712-1324 - Added system setting slave server option
# 100802-2347 - Added User Group Allowed Reports option validation
# 100914-1326 - Added lookup for user_level 7 users to set to reports only which will remove other admin links
# 110703-1756 - Added download option
# 111103-2300 - Added user_group restrictions for selecting in-groups
# 120224-0910 - Added HTML display option with bar graphs
# 130414-0104 - Added report logging
#

$startMS = microtime();

require("dbconnect.php");

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
if (isset($_GET["submit"]))				{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))		{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))				{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))		{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["file_download"]))				{$file_download=$_GET["file_download"];}
	elseif (isset($_POST["file_download"]))	{$file_download=$_POST["file_download"];}
if (isset($_GET["report_display_type"]))				{$report_display_type=$_GET["report_display_type"];}
	elseif (isset($_POST["report_display_type"]))	{$report_display_type=$_POST["report_display_type"];}

$PHP_AUTH_USER = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_USER);
$PHP_AUTH_PW = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_PW);

if (strlen($shift)<2) {$shift='ALL';}

$report_name = 'Inbound Service Level Report';
$db_source = 'M';

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

$stmt="INSERT INTO vicidial_report_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name |$group, $query_date, $end_date, $shift, $file_download, $report_display_type|', url='$LOGfull_url';";
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

$NOW_DATE = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");
$STARTtime = date("U");
if (!isset($group)) {$group = '';}
if (!isset($query_date)) {$query_date = $NOW_DATE;}
if (!isset($end_date)) {$end_date = $NOW_DATE;}

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

if (!preg_match("/\|$group\|/i",$groups_string))
	{
	$HEADER.="<!-- group not found: $group  $groups_string -->\n";
	$group='';
	}

$HEADER.="<script language=\"JavaScript\" src=\"calendar_db.js\"></script>\n";
$HEADER.="<link rel=\"stylesheet\" href=\"calendar.css\">\n";
$HEADER.="<link rel=\"stylesheet\" href=\"horizontalbargraph.css\">\n";

$HEADER.="<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";
$HEADER.="<TITLE>$report_name</TITLE></HEAD><BODY BGCOLOR=WHITE marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>\n";

$short_header=1;

# require("admin_header.php");

$MAIN.="<TABLE CELLPADDING=4 CELLSPACING=0><TR><TD>";

$MAIN.="<FORM ACTION=\"$PHP_SELF\" METHOD=GET name=vicidial_report id=vicidial_report>\n";
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

$MAIN.="<SELECT SIZE=1 NAME=group>\n";
	$o=0;
while ($groups_to_print > $o)
	{
	if ($groups[$o] == $group) {$MAIN.="<option selected value=\"$groups[$o]\">$groups[$o] - $group_names[$o]</option>\n";}
	else {$MAIN.="<option value=\"$groups[$o]\">$groups[$o] - $group_names[$o]</option>\n";}
	$o++;
	}
$MAIN.="</SELECT>\n";
$MAIN.="&nbsp;";
$MAIN.="<select name='report_display_type'>";
if ($report_display_type) {$MAIN.="<option value='$report_display_type' selected>$report_display_type</option>";}
$MAIN.="<option value='TEXT'>TEXT</option><option value='HTML'>HTML</option></select>\n";
$MAIN.=" &nbsp; ";
$MAIN.="<SELECT SIZE=1 NAME=shift>\n";
$MAIN.="<option selected value=\"$shift\">$shift</option>\n";
$MAIN.="<option value=\"\">--</option>\n";
$MAIN.="<option value=\"AM\">AM</option>\n";
$MAIN.="<option value=\"PM\">PM</option>\n";
$MAIN.="<option value=\"ALL\">ALL</option>\n";
$MAIN.="<option value=\"DAYTIME\">DAYTIME</option>\n";
$MAIN.="<option value=\"10AM-6PM\">10AM-6PM</option>\n";
$MAIN.="<option value=\"9AM-1AM\">9AM-1AM</option>\n";
$MAIN.="<option value=\"845-1745\">845-1745</option>\n";
$MAIN.="<option value=\"1745-100\">1745-100</option>\n";
$MAIN.="</SELECT>\n";
$MAIN.="<INPUT TYPE=submit NAME=SUBMIT VALUE=SUBMIT>\n";
$MAIN.="<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href=\"$PHP_SELF?DB=$DB&query_date=$query_date&end_date=$end_date&group=$group&shift=$shift&SUBMIT=$SUBMIT&file_download=1\">DOWNLOAD</a> | <a href=\"./admin.php?ADD=3111&group_id=$group\">MODIFY</a> | <a href=\"./admin.php?ADD=999999\">REPORTS</a>";
$MAIN.="</FONT>\n";
$MAIN.="</FORM>\n\n";

$MAIN.="<PRE><FONT SIZE=2>\n\n";


if (!$group)
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

if ($shift == 'AM') 
	{
#	$time_BEGIN=$AM_shift_BEGIN;
#	$time_END=$AM_shift_END;
#	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "03:45:00";}   
#	if (strlen($time_END) < 6) {$time_END = "15:15:00";}
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "00:00:00";}   
	if (strlen($time_END) < 6) {$time_END = "11:59:59";}
#	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "12:00:00";}   
#	if (strlen($time_END) < 6) {$time_END = "11:59:59";}
	}
if ($shift == 'PM') 
	{
#	$time_BEGIN=$PM_shift_BEGIN;
#	$time_END=$PM_shift_END;
#	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "15:15:00";}
#	if (strlen($time_END) < 6) {$time_END = "23:15:00";}
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "12:00:00";}
	if (strlen($time_END) < 6) {$time_END = "23:59:59";}
	}
if ($shift == 'ALL') 
	{
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "00:00:00";}
	if (strlen($time_END) < 6) {$time_END = "23:59:59";}
	}
if ($shift == 'DAYTIME') 
	{
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "08:45:00";}
	if (strlen($time_END) < 6) {$time_END = "00:59:59";}
	}
if ($shift == '10AM-6PM') 
	{
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "10:00:00";}
	if (strlen($time_END) < 6) {$time_END = "17:59:59";}
	}
if ($shift == '9AM-1AM') 
	{
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "09:00:00";}
	if (strlen($time_END) < 6) {$time_END = "00:59:59";}
	}
if ($shift == '845-1745') 
	{
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "08:45:00";}
	if (strlen($time_END) < 6) {$time_END = "17:44:59";}
	}
if ($shift == '1745-100') 
	{
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "17:45:00";}
	if (strlen($time_END) < 6) {$time_END = "00:59:59";}
	}

$query_date_BEGIN = "$query_date $time_BEGIN";   
$query_date_END = "$end_date $time_END";

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

$DURATIONsec = ($EQepoch - $SQepoch);
$DURATIONday = intval( ($DURATIONsec / 86400) + 1 );

if ( ($EQsec < $SQsec) and ($DURATIONday < 1) )
	{
	$EQepoch = ($SQepochDAY + ($EQsec + 86400) );
	$query_date_END = date("Y-m-d H:i:s", $EQepoch);
	$DURATIONday++;
	}

$MAIN.="Inbound Service Level Report                      $NOW_TIME\n";
$MAIN.="\n";
$MAIN.="Time range $DURATIONday days: $query_date_BEGIN to $query_date_END\n\n";
#echo "Time range day sec: $SQsec - $EQsec   Day range in epoch: $SQepoch - $EQepoch   Start: $SQepochDAY\n";
$CSV_text.="\"Inbound Service Level Report\",\"$NOW_TIME\"\n";
$CSV_text.="\n";
$CSV_text.="\"Time range $DURATIONday days:\",\"$query_date_BEGIN to $query_date_END\"\n\n";

$d=0;
while ($d < $DURATIONday)
	{
	$dSQepoch = ($SQepoch + ($d * 86400) );
	$dEQepoch = ($SQepochDAY + ($EQsec + ($d * 86400) ) );

	if ($EQsec < $SQsec)
		{
		$dEQepoch = ($dEQepoch + 86400);
		}

	$daySTART[$d] = date("Y-m-d H:i:s", $dSQepoch);
	$dayEND[$d] = date("Y-m-d H:i:s", $dEQepoch);

	$d++;
	}

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

$TOTintervals = $j;


### GRAB ALL RECORDS WITHIN RANGE FROM THE DATABASE ###
$stmt="select queue_seconds,UNIX_TIMESTAMP(call_date),length_in_sec,status from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and  campaign_id='" . mysql_real_escape_string($group) . "';";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$records_to_grab = mysql_num_rows($rslt);
$i=0;
while ($i < $records_to_grab)
	{
	$row=mysql_fetch_row($rslt);
	$qs[$i] = $row[0];
	$dt[$i] = 0;
	$ut[$i] = ($row[1] - $SQepochDAY);
	while($ut[$i] >= 86400) 
		{
		$ut[$i] = ($ut[$i] - 86400);
		$dt[$i]++;
		}
	if ( ($ut[$i] <= $EQsec) and ($EQsec < $SQsec) )
		{
		$dt[$i] = ($dt[$i] - 1);
		}
	$ls[$i] = $row[2];
	$st[$i] = $row[3];

#	echo "$qs[$i] $dt[$i] $ut[$i] $ls[$i] $st[$i]\n";

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
$j=0;
while ($j < $TOTintervals)
	{
	$jd__0[$j]=0; $jd_20[$j]=0; $jd_40[$j]=0; $jd_60[$j]=0; $jd_80[$j]=0; $jd100[$j]=0; $jd120[$j]=0; $jd121[$j]=0;
	$Phd__0[$j]=0; $Phd_20[$j]=0; $Phd_40[$j]=0; $Phd_60[$j]=0; $Phd_80[$j]=0; $Phd100[$j]=0; $Phd120[$j]=0; $Phd121[$j]=0;
	$qrtCALLS[$j]=0; $qrtCALLSsec[$j]=0; $qrtCALLSmax[$j]=0;
	$qrtDROPS[$j]=0; $qrtDROPSsec[$j]=0; $qrtDROPSmax[$j]=0;
	$qrtQUEUE[$j]=0; $qrtQUEUEsec[$j]=0; $qrtQUEUEmax[$j]=0;
	$i=0;
	while ($i < $records_to_grab)
		{
		if ( ($ut[$i] >= $HMSepoch[$j]) and ($ut[$i] <= $HMEepoch[$j]) )
			{
			$totCALLS++;
			$totCALLSsec = ($totCALLSsec + $ls[$i]);
			$totCALLSsecDATE[$dtt] = ($totCALLSsecDATE[$dtt] + $ls[$i]);
			$qrtCALLS[$j]++;
			$qrtCALLSsec[$j] = ($qrtCALLSsec[$j] + $ls[$i]);
			$dtt = $dt[$i];
			$totCALLSdate[$dtt]++;
			if ($totCALLSmax < $ls[$i]) {$totCALLSmax = $ls[$i];}
			if ($qrtCALLSmax[$j] < $ls[$i]) {$qrtCALLSmax[$j] = $ls[$i];}
			if (ereg('DROP',$st[$i])) 
				{
				$totDROPS++;
				$totDROPSsec = ($totDROPSsec + $ls[$i]);
				$totDROPSsecDATE[$dtt] = ($totDROPSsecDATE[$dtt] + $ls[$i]);
				$qrtDROPS[$j]++;
				$qrtDROPSsec[$j] = ($qrtDROPSsec[$j] + $ls[$i]);
				$totDROPSdate[$dtt]++;
				if ($totDROPSmax < $ls[$i]) {$totDROPSmax = $ls[$i];}
				if ($qrtDROPSmax[$j] < $ls[$i]) {$qrtDROPSmax[$j] = $ls[$i];}
				}
			if ($qs[$i] > 0) 
				{
				$totQUEUE++;
				$totQUEUEsec = ($totQUEUEsec + $qs[$i]);
				$totQUEUEsecDATE[$dtt] = ($totQUEUEsecDATE[$dtt] + $qs[$i]);
				$qrtQUEUE[$j]++;
				$qrtQUEUEsec[$j] = ($qrtQUEUEsec[$j] + $qs[$i]);
				$totQUEUEdate[$dtt]++;
				if ($totQUEUEmax < $qs[$i]) {$totQUEUEmax = $qs[$i];}
				if ($qrtQUEUEmax[$j] < $qs[$i]) {$qrtQUEUEmax[$j] = $qs[$i];}
				}

			if ($qs[$i] == 0) {$hd__0[$j]++;}
			if ( ($qs[$i] > 0) and ($qs[$i] <= 20) ) {$hd_20[$j]++;}
			if ( ($qs[$i] > 20) and ($qs[$i] <= 40) ) {$hd_40[$j]++;}
			if ( ($qs[$i] > 40) and ($qs[$i] <= 60) ) {$hd_60[$j]++;}
			if ( ($qs[$i] > 60) and ($qs[$i] <= 80) ) {$hd_80[$j]++;}
			if ( ($qs[$i] > 80) and ($qs[$i] <= 100) ) {$hd100[$j]++;}
			if ( ($qs[$i] > 100) and ($qs[$i] <= 120) ) {$hd120[$j]++;}
			if ($qs[$i] > 120) {$hd121[$j]++;}

			}
		
		$i++;
		}

	$j++;
	}




###################################################
### TOTALS SUMMARY SECTION ###
$ASCII_text.="+-------------------------------------------+--------+--------+--------+--------+--------+--------+--------+--------+----------+--------+\n";
$ASCII_text.="|                                           |        |        |        |        |        |  AVG   |  AVG   |        |  TOTAL   |  AVG   |\n";
$ASCII_text.="| SHIFT                                     |        |        |  AVG   |        |        | HOLD(s)| HOLD(s)|        | CALLTIME |CALLTIME|\n";
$ASCII_text.="| DATE-TIME RANGE                           | DROPS  | DROP % | DROP(s)| HOLD   | HOLD % |  HOLD  | TOTAL  | CALLS  | MIN:SEC  |SECONDS |\n";
$ASCII_text.="+-------------------------------------------+--------+--------+--------+--------+--------+--------+--------+--------+----------+--------+\n";
$CSV_text.="\"SHIFT DATE-TIME RANGE\",\"DROPS\",\" DROP %\",\" AVG DROP(s)\",\" HOLD\",\" HOLD %\",\" AVG HOLD(S) HOLD\",\" AVG HOLD(S) TOTAL\",\" CALLS\",\" TOTAL CALLTIME MIN:SEC\",\" AVG CALLTIME SECONDS\"\n";


$graph_stats=array();
$max_drops=1;
$max_droppct=1;
$max_avgdrops=1;
$max_hold=1;
$max_holdpct=1;
$max_avgholds=1;
$max_avgholdstotal=1;
$max_calls=1;
$max_totalcalltime=1;
$max_avgcalltime=1;

$GRAPH="<BR><BR><a name='inbound_graph'/><table border='0' cellpadding='0' cellspacing='2' width='800'>";
$GRAPH.="<tr><th width='10%' class='grey_graph_cell' id='inbound_graph1'><a href='#' onClick=\"DrawGraph('DROPS', '1'); return false;\">DROPS</a></th><th width='10%' class='grey_graph_cell' id='inbound_graph2'><a href='#' onClick=\"DrawGraph('DROPPCT', '2'); return false;\">DROP %</a></th><th width='10%' class='grey_graph_cell' id='inbound_graph3'><a href='#' onClick=\"DrawGraph('AVGDROPS', '3'); return false;\">AVG DROP(s)</a></th><th width='10%' class='grey_graph_cell' id='inbound_graph4'><a href='#' onClick=\"DrawGraph('HOLD', '4'); return false;\">HOLD</a></th><th width='10%' class='grey_graph_cell' id='inbound_graph5'><a href='#' onClick=\"DrawGraph('HOLDPCT', '5'); return false;\">HOLD %</a></th><th width='10%' class='grey_graph_cell' id='inbound_graph6'><a href='#' onClick=\"DrawGraph('AVGHOLDS', '6'); return false;\">AVG HOLD(s) HOLD</a></th><th width='10%' class='grey_graph_cell' id='inbound_graph7'><a href='#' onClick=\"DrawGraph('AVGHOLDSTOTAL', '7'); return false;\">AVG HOLD(s) TOTAL</a></th><th width='10%' class='grey_graph_cell' id='inbound_graph8'><a href='#' onClick=\"DrawGraph('CALLS', '8'); return false;\">CALLS</a></th><th width='10%' class='grey_graph_cell' id='inbound_graph9'><a href='#' onClick=\"DrawGraph('TOTALCALLTIME', '9'); return false;\">TOTAL CALLTIME MIN:SEC</a></th><th width='10%' class='grey_graph_cell' id='inbound_graph10'><a href='#' onClick=\"DrawGraph('AVGCALLTIME', '10'); return false;\">AVG CALLTIME SECONDS</a></th></tr>";
$GRAPH.="<tr><td colspan='10' class='graph_span_cell'><span id='inbound_stats_graph'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";
$graph_header="<table cellspacing='0' cellpadding='0' class='horizontalgraph'><caption align='top'>INBOUND SERVICE LEVEL REPORT</caption><tr><th class='thgraph' scope='col'>STATUS</th>";
$DROPS_graph=$graph_header."<th class='thgraph' scope='col'>DROPS </th></tr>";
$DROPPCT_graph=$graph_header."<th class='thgraph' scope='col'>DROP % </th></tr>";
$AVGDROPS_graph=$graph_header."<th class='thgraph' scope='col'>AVG DROP(s) </th></tr>";
$HOLD_graph=$graph_header."<th class='thgraph' scope='col'>HOLD </th></tr>";
$HOLDPCT_graph=$graph_header."<th class='thgraph' scope='col'>HOLD % </th></tr>";
$AVGHOLDS_graph=$graph_header."<th class='thgraph' scope='col'>AVG HOLD(s) HOLD </th></tr>";
$AVGHOLDSTOTAL_graph=$graph_header."<th class='thgraph' scope='col'>AVG HOLD(s) TOTAL </th></tr>";
$CALLS_graph=$graph_header."<th class='thgraph' scope='col'>CALLS </th></tr>";
$TOTALCALLTIME_graph=$graph_header."<th class='thgraph' scope='col'>TOTAL CALLTIME MIN:SEC </th></tr>";
$AVGCALLTIME_graph=$graph_header."<th class='thgraph' scope='col'>AVG CALLTIME SECONDS </th></tr>";
$d=0;
while ($d < $DURATIONday)
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
		$totTIME_MS='    0:00';
		}

	if (trim($totDROPSdate[$d])>$max_drops) {$max_drops=trim($totDROPSdate[$d]);}
	if (trim($totDROPSpctDATE[$d])>$max_droppct) {$max_droppct=trim($totDROPSpctDATE[$d]);}
	if (trim($totDROPSavgDATE[$d])>$max_avgdrops) {$max_avgdrops=trim($totDROPSavgDATE[$d]);}
	if (trim($totQUEUEdate[$d])>$max_hold) {$max_hold=trim($totQUEUEdate[$d]);}
	if (trim($totQUEUEpctDATE[$d])>$max_holdpct) {$max_holdpct=trim($totQUEUEpctDATE[$d]);}
	if (trim($totQUEUEavgDATE[$d])>$max_avgholds) {$max_avgholds=trim($totQUEUEavgDATE[$d]);}
	if (trim($totQUEUEtotDATE[$d])>$max_avgholdstotal) {$max_avgholdstotal=trim($totQUEUEtotDATE[$d]);}
	if (trim($totCALLSdate[$d])>$max_calls) {$max_calls=trim($totCALLSdate[$d]);}
	if (trim($totCALLSsecDATE[$d])>$max_totalcalltime) {$max_totalcalltime=trim($totCALLSsecDATE[$d]);}
	if (trim($totCALLSavgDATE[$d])>$max_avgcalltime) {$max_avgcalltime=trim($totCALLSavgDATE[$d]);}
	$graph_stats[$d][0]=trim("$daySTART[$d] - $dayEND[$d]");
	$graph_stats[$d][1]=trim($totDROPSdate[$d]);
	$graph_stats[$d][2]=trim(sprintf("%6.2f", $totDROPSpctDATE[$d]));
	$graph_stats[$d][3]=trim(sprintf("%7.2f", $totDROPSavgDATE[$d]));
	$graph_stats[$d][4]=trim($totQUEUEdate[$d]);
	$graph_stats[$d][5]=trim(sprintf("%6.2f", $totQUEUEpctDATE[$d]));
	$graph_stats[$d][6]=trim(sprintf("%7.2f", $totQUEUEavgDATE[$d]));
	$graph_stats[$d][7]=trim(sprintf("%7.2f", $totQUEUEtotDATE[$d]));
	$graph_stats[$d][8]=trim($totCALLSdate[$d]);
	$graph_stats[$d][9]=trim($totCALLSsecDATE[$d]);
	$graph_stats[$d][10]=trim($totTIME_MS);
	$graph_stats[$d][11]=trim(sprintf("%6.0f", $totCALLSavgDATE[$d]));

	$totCALLSavgDATE[$d] =	sprintf("%6.0f", $totCALLSavgDATE[$d]);
	$totDROPSavgDATE[$d] =	sprintf("%7.2f", $totDROPSavgDATE[$d]);
	$totQUEUEavgDATE[$d] =	sprintf("%7.2f", $totQUEUEavgDATE[$d]);
	$totQUEUEtotDATE[$d] =	sprintf("%7.2f", $totQUEUEtotDATE[$d]);
	$totDROPSpctDATE[$d] =	sprintf("%6.2f", $totDROPSpctDATE[$d]);
	$totQUEUEpctDATE[$d] =	sprintf("%6.2f", $totQUEUEpctDATE[$d]);
	$totDROPSdate[$d] =	sprintf("%6s", $totDROPSdate[$d]);
	$totQUEUEdate[$d] =	sprintf("%6s", $totQUEUEdate[$d]);
	$totCALLSdate[$d] =	sprintf("%6s", $totCALLSdate[$d]);

	$ASCII_text.="| $daySTART[$d] - $dayEND[$d] | $totDROPSdate[$d] | $totDROPSpctDATE[$d]%|$totDROPSavgDATE[$d] | $totQUEUEdate[$d] | $totQUEUEpctDATE[$d]%|$totQUEUEavgDATE[$d] |$totQUEUEtotDATE[$d] | $totCALLSdate[$d] | $totTIME_MS | $totCALLSavgDATE[$d] |\n";
	$CSV_text.="\"$daySTART[$d] - $dayEND[$d]\",\"$totDROPSdate[$d]\",\"$totDROPSpctDATE[$d]%\",\"$totDROPSavgDATE[$d]\",\"$totQUEUEdate[$d]\",\"$totQUEUEpctDATE[$d]%\",\"$totQUEUEavgDATE[$d]\",\"$totQUEUEtotDATE[$d]\",\"$totCALLSdate[$d]\",\"$totTIME_MS\",\"$totCALLSavgDATE[$d]\"\n";
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
	$FtotCALLS =	sprintf("%6s", $totCALLS);

$ASCII_text.="+-------------------------------------------+--------+--------+--------+--------+--------+--------+--------+--------+----------+--------+\n";
$ASCII_text.="|                                    TOTALS | $FtotDROPS | $FtotDROPSpct%|$FtotDROPSavg | $FtotQUEUE | $FtotQUEUEpct%|$FtotQUEUEavg |$FtotQUEUEtot | $FtotCALLS |$totTIME_MS | $FtotCALLSavg |\n";
$ASCII_text.="+-------------------------------------------+--------+--------+--------+--------+--------+--------+--------+--------+----------+--------+\n";
$CSV_text.="\"TOTALS\",\"$FtotDROPS\",\"$FtotDROPSpct%\",\"$FtotDROPSavg\",\"$FtotQUEUE\",\"$FtotQUEUEpct%\",\"$FtotQUEUEavg\",\"$FtotQUEUEtot\",\"$FtotCALLS\",\"$totTIME_MS\",\"$FtotCALLSavg\"\n";


	for ($d=0; $d<count($graph_stats); $d++) {
		if ($d==0) {$class=" first";} else if (($d+1)==count($graph_stats)) {$class=" last";} else {$class="";}
		$DROPS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][1]/$max_drops)."' height='16' />".$graph_stats[$d][1]."</td></tr>";
		$DROPPCT_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][2]/$max_droppct)."' height='16' />".$graph_stats[$d][2]."%</td></tr>";
		$AVGDROPS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][3]/$max_avgdrops)."' height='16' />".$graph_stats[$d][3]."</td></tr>";
		$HOLD_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][4]/$max_hold)."' height='16' />".$graph_stats[$d][4]."</td></tr>";
		$HOLDPCT_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][5]/$max_holdpct)."' height='16' />".$graph_stats[$d][5]."%</td></tr>";
		$AVGHOLDS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][6]/$max_avgholds)."' height='16' />".$graph_stats[$d][6]."</td></tr>";
		$AVGHOLDSTOTAL_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][7]/$max_avgholdstotal)."' height='16' />".$graph_stats[$d][7]."</td></tr>";
		$CALLS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][8]/$max_calls)."' height='16' />".$graph_stats[$d][8]."</td></tr>";
		$TOTALCALLTIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][9]/$max_totalcalltime)."' height='16' />".$graph_stats[$d][10]."</td></tr>";
		$AVGCALLTIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][11]/$max_avgcalltime)."' height='16' />".$graph_stats[$d][11]."</td></tr>";
	}
	$DROPS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotDROPS)."</th></tr></table>";
	$DROPPCT_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotDROPSpct)."%</th></tr></table>";
	$AVGDROPS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotDROPSavg)."</th></tr></table>";
	$HOLD_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotQUEUE)."</th></tr></table>";
	$HOLDPCT_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotQUEUEpct)."%</th></tr></table>";
	$AVGHOLDS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotQUEUEavg)."</th></tr></table>";
	$AVGHOLDSTOTAL_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotQUEUEtot)."</th></tr></table>";
	$CALLS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotCALLS)."</th></tr></table>";
	$TOTALCALLTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($totTIME_MS)."</th></tr></table>";
	$AVGCALLTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($FtotCALLSavg)."</th></tr></table>";
	$JS_text="<script language='Javascript'>\n";
	$JS_onload="onload = function() {\n";
	$JS_onload.="\tDrawGraph('DROPS', '1');\n"; 
	$JS_text.="function DrawGraph(graph, th_id) {\n";
	$JS_text.="	var DROPS_graph=\"$DROPS_graph\";\n";
	$JS_text.="	var DROPPCT_graph=\"$DROPPCT_graph\";\n";
	$JS_text.="	var AVGDROPS_graph=\"$AVGDROPS_graph\";\n";
	$JS_text.="	var HOLD_graph=\"$HOLD_graph\";\n";
	$JS_text.="	var HOLDPCT_graph=\"$HOLDPCT_graph\";\n";
	$JS_text.="	var AVGHOLDS_graph=\"$AVGHOLDS_graph\";\n";
	$JS_text.="	var AVGHOLDSTOTAL_graph=\"$AVGHOLDSTOTAL_graph\";\n";
	$JS_text.="	var CALLS_graph=\"$CALLS_graph\";\n";
	$JS_text.="	var TOTALCALLTIME_graph=\"$TOTALCALLTIME_graph\";\n";
	$JS_text.="	var AVGCALLTIME_graph=\"$AVGCALLTIME_graph\";\n";
	$JS_text.="\n";
	$JS_text.="	for (var i=1; i<=10; i++) {\n";
	$JS_text.="		var cellID=\"inbound_graph\"+i;\n";
	$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
	$JS_text.="	}\n";
	$JS_text.="	var cellID=\"inbound_graph\"+th_id;\n";
	$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
	$JS_text.="	var graph_to_display=eval(graph+\"_graph\");\n";
	$JS_text.="	document.getElementById('inbound_stats_graph').innerHTML=graph_to_display;\n";
	$JS_text.="}\n";
	# $MAIN.=$GRAPH;


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

if ($hi_hour_count < 1)
	{$hour_multiplier = 0;}
else
	{$hour_multiplier = (20 / $hi_hour_count);}
if ($hi_hold_count < 1)
	{$hold_multiplier = 0;}
else
	{$hold_multiplier = (20 / $hi_hold_count);}

if ($report_display_type=="HTML")
	{
	$MAIN.=$GRAPH;
	}
else
	{
	$MAIN.=$ASCII_text;
	}


###################################################################
#########  HOLD TIME, CALL AND DROP STATS 15-MINUTE INCREMENTS ####

$MAIN.="\n";
$MAIN.="---------- HOLD TIME, CALL AND DROP STATS\n";

$MAIN.="<FONT SIZE=0>";

$MAIN.="<!-- HICOUNT CALLS: $hi_hour_count|$hour_multiplier -->";
$MAIN.="<!-- HICOUNT HOLD:  $hi_hold_count|$hold_multiplier -->\n";
$MAIN.="GRAPH IN 15 MINUTE INCREMENTS OF AVERAGE HOLD TIME FOR CALLS TAKEN INTO THIS IN-GROUP\n";

$CSV_text.="\n\n\"HOLD TIME; CALL AND DROP STATS\"\n";
$CSV_text.="\"GRAPH IN 15 MINUTE INCREMENTS\"\n\"OF AVERAGE HOLD TIME FOR CALLS\"\n\"TAKEN INTO THIS IN-GROUP\"\n";

$k=1;
$Mk=0;
$call_scale = '0';
while ($k <= 22) 
	{
	if ( ($k < 1) or ($hour_multiplier <= 0) )
		{$scale_num = 20;}
	else
		{
		$TMPscale_num=(23 / $hour_multiplier);
		$TMPscale_num = round($TMPscale_num, 0);
		$scale_num=($k / $hour_multiplier);
		$scale_num = round($scale_num, 0);
		}
	$tmpscl = "$call_scale$TMPscale_num";

	if ( ($Mk >= 4) or (strlen($tmpscl)==23) )
		{
		$Mk=0;
		$LENscale_num = (strlen($scale_num));
		$k = ($k + $LENscale_num);
		$call_scale .= "$scale_num";
		}
	else
		{
		$call_scale .= " ";
		$k++;   $Mk++;
		}
	}
$k=1;
$Mk=0;
$hold_scale = '0';
while ($k <= 22) 
	{
	if ( ($k < 1) or ($hold_multiplier <= 0) )
		{$scale_num = 20;}
	else
		{
		$TMPscale_num=(23 / $hold_multiplier);
		$TMPscale_num = round($TMPscale_num, 0);
		$scale_num=($k / $hold_multiplier);
		$scale_num = round($scale_num, 0);
		}
	$tmpscl = "$hold_scale$TMPscale_num";

	if ( ($Mk >= 4) or (strlen($tmpscl)==23) )
		{
		$Mk=0;
		$LENscale_num = (strlen($scale_num));
		$k = ($k + $LENscale_num);
		$hold_scale .= "$scale_num";
		}
	else
		{
		$hold_scale .= " ";
		$k++;   $Mk++;
		}
	}


$ASCII_text.="+-------------+-----------------------+-------+-------+  +-----------------------+-------+-------+\n";
$ASCII_text.="|    TIME     |  AVG HOLD TIME (sec)  | (in seconds)  |  |    CALLS HANDLED      |       |       |\n";
$ASCII_text.="| 15 MIN INT  |$hold_scale| AVG   | MAX   |  |$call_scale| DROPS | TOTAL |\n";
$ASCII_text.="+-------------+-----------------------+-------+-------+  +-----------------------+-------+-------+\n";

$max_avg_hold_time=1;
$max_calls=1;
$graph_stats=array();
$GRAPH="<BR><BR><a name='holdcalldropgraph'/><table border='0' cellpadding='0' cellspacing='2' width='800'>";
$GRAPH.="<tr><th width='50%' class='grey_graph_cell' id='holdcalldropgraph1'><a href='#' onClick=\"DrawHCDGraph('AVGHOLD', '1'); return false;\">AVERAGE HOLD TIME</a></th><th width=50% class='grey_graph_cell' id='holdcalldropgraph2'><a href='#' onClick=\"DrawHCDGraph('CALLSHANDLED', '2'); return false;\">CALLS HANDLED</a></th></tr>";
$GRAPH.="<tr><td colspan='5' class='graph_span_cell'><span id='holdcalldrop_stats_graph'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";
$AVGHOLD_graph="<table cellspacing='0' cellpadding='0' class='horizontalgraph'><caption align='top'>AVERAGE HOLD TIME</caption><tr><th class='thgraph' scope='col'>TIME 15-MIN INT</th><th class='thgraph' scope='col'>AVG HOLD TIME</th><th class='thgraph' scope='col'>AVG</th><th class='thgraph' scope='col'>MAX</th></tr>";
$CALLSHANDLED_graph="<table cellspacing='0' cellpadding='0' class='horizontalgraph'><caption align='top'>CALLS HANDLED</caption><tr><th class='thgraph' scope='col'>TIME 15-MIN INT</th><th class='thgraph' scope='col'>DROPS <img src='./images/bar_blue.png' width='10' height='10'> / CALLS <img src='./images/bar.png' width='10' height='10'></th></tr>";


$CSV_text.="\"TIME - 15 MIN INT\",\"AVG SECS\",\"MAX SECS\",\"\",\"DROPS\",\"TOTAL\"\n";

$i=0;
while ($i < $TOTintervals)
	{
	$char_counter=0;
	### BEGIN HOLD TIME TOTALS GRAPH ###
		$Ghour_count = $qrtCALLS[$i];
	if ($Ghour_count > 0) {$no_lines_yet=0;}

	$Gavg_hold = $qrtQUEUEavg[$i];
	if ($Gavg_hold < 1) 
		{
		if ($i < 0)
			{
			$do_nothing=1;
			}
		else
			{
			$TOT_lines++;
			$qrtQUEUEavg[$i] =	sprintf("%5s", $qrtQUEUEavg[$i]);
			$qrtQUEUEmax[$i] =	sprintf("%5s", $qrtQUEUEmax[$i]);
			$ASCII_text.="|$HMdisplay[$i]|";
			$k=0;   while ($k <= 22) {$ASCII_text.=" ";   $k++;}
			$ASCII_text.="| $qrtQUEUEavg[$i] | $qrtQUEUEmax[$i] |";

			$graph_stats[$i][0]="$HMdisplay[$i]";
			$graph_stats[$i][1]=trim($qrtQUEUEavg[$i]);
			$graph_stats[$i][2]=trim($qrtQUEUEmax[$i]);
			if (trim($qrtQUEUEavg[$i])>$max_avg_hold_time) {$max_avg_hold_time=trim($qrtQUEUEavg[$i]);}
			}
		}
	else
		{
		$TOT_lines++;
		$no_lines_yet=0;
		$Xavg_hold = ($Gavg_hold * $hold_multiplier);
		$Yavg_hold = (19 - $Xavg_hold);

		$qrtQUEUEavg[$i] =	sprintf("%5s", $qrtQUEUEavg[$i]);
		$qrtQUEUEmax[$i] =	sprintf("%5s", $qrtQUEUEmax[$i]);

		$ASCII_text.="|$HMdisplay[$i]|<SPAN class=\"orange\">";
		$k=0;   while ($k <= $Xavg_hold) {$ASCII_text.="*";   $k++;   $char_counter++;}
		if ($char_counter >= 22) {$ASCII_text.="H</SPAN>";   $char_counter++;}
		else {$ASCII_text.="*H</SPAN>";   $char_counter++;   $char_counter++;}
		$k=0;   while ($k <= $Yavg_hold) {$ASCII_text.=" ";   $k++;   $char_counter++;}
			while ($char_counter <= 22) {$ASCII_text.=" ";   $char_counter++;}
		$ASCII_text.="| $qrtQUEUEavg[$i] | $qrtQUEUEmax[$i] |";

		}
	### END HOLD TIME TOTALS GRAPH ###

	$char_counter=0;
 	### BEGIN CALLS TOTALS GRAPH ###
	$Ghour_count = $qrtCALLS[$i];
	if ($Ghour_count < 1) 
		{
		if ($i < 0)
			{
			$do_nothing=1;
			}
		else
			{
			$qrtCALLS[$i] =	sprintf("%5s", $qrtCALLS[$i]);
			$ASCII_text.="  |";
			$k=0;   while ($k <= 22) {$ASCII_text.=" ";   $k++;}
			$ASCII_text.="| $qrtCALLS[$i] |     0 |\n";
			}
		}
	else
		{
		$no_lines_yet=0;
		$Xhour_count = ($Ghour_count * $hour_multiplier);
		$Yhour_count = (19 - $Xhour_count);

		$Gdrop_count = $qrtDROPS[$i];
		if ($Gdrop_count < 1) 
			{
			$qrtCALLS[$i] =	sprintf("%5s", $qrtCALLS[$i]);

			$ASCII_text.="  |<SPAN class=\"green\">";
			$k=0;   while ($k <= $Xhour_count) {$ASCII_text.="*";   $k++;   $char_counter++;}
			if ($char_counter > 21) {$ASCII_text.="C</SPAN>";   $char_counter++;}
			else {$ASCII_text.="*C</SPAN>";   $char_counter++;   $char_counter++;}
			$k=0;   while ($k <= $Yhour_count) {$ASCII_text.=" ";   $k++;   $char_counter++;}
				while ($char_counter <= 22) {$ASCII_text.=" ";   $char_counter++;}
			$ASCII_text.="|     0 | $qrtCALLS[$i] |\n";
			}
		else
			{
			$Xdrop_count = ($Gdrop_count * $hour_multiplier);

		#	if ($Xdrop_count >= $Xhour_count) {$Xdrop_count = ($Xdrop_count - 1);}

			$XXhour_count = ( ($Xhour_count - $Xdrop_count) - 1 );

			$qrtCALLS[$i] =	sprintf("%5s", $qrtCALLS[$i]);
			$qrtDROPS[$i] =	sprintf("%5s", $qrtDROPS[$i]);

			$ASCII_text.="  |<SPAN class=\"red\">";
			$k=0;   while ($k <= $Xdrop_count) {$ASCII_text.=">";   $k++;   $char_counter++;}
			$ASCII_text.="D</SPAN><SPAN class=\"green\">";   $char_counter++;
			$k=0;   while ($k <= $XXhour_count) {$ASCII_text.="*";   $k++;   $char_counter++;}
			$ASCII_text.="C</SPAN>";   $char_counter++;
			$k=0;   while ($k <= $Yhour_count) {$ASCII_text.=" ";   $k++;   $char_counter++;}
				while ($char_counter <= 22) {$ASCII_text.=" ";   $char_counter++;}
			$ASCII_text.="| $qrtDROPS[$i] | $qrtCALLS[$i] |\n";
			}
		}
	### END CALLS TOTALS GRAPH ###
	$CSV_text.="\"$HMdisplay[$i]\",\"$qrtQUEUEavg[$i]\",\"$qrtQUEUEmax[$i]\",\"\",\"$qrtDROPS[$i]\",\"$qrtCALLS[$i]\"\n";

	$graph_stats[$i][0]="$HMdisplay[$i]";
	$graph_stats[$i][1]=trim($qrtQUEUEavg[$i]);
	$graph_stats[$i][2]=trim($qrtQUEUEmax[$i]);
	$graph_stats[$i][3]=trim($qrtCALLS[$i]);
	$graph_stats[$i][4]=trim($qrtDROPS[$i]);
	if (trim($qrtQUEUEavg[$i])>$max_avg_hold_time) {$max_avg_hold_time=trim($qrtQUEUEavg[$i]);}
	if (trim($qrtCALLS[$i])>$max_calls) {$max_calls=trim($qrtCALLS[$i]);}

	$i++;
	}


if ($totQUEUEsec > 0)
	{$totQUEUEavgRAW = ($totCALLS / $totQUEUEsec);}
else
	{$totQUEUEavgRAW = 0;}
$totQUEUEavg =	sprintf("%5s", $totQUEUEavg); 
	while (strlen($totQUEUEavg)>5) {$totQUEUEavg = ereg_replace(".$",'',$totQUEUEavg);}
$totQUEUEmax =	sprintf("%5s", $totQUEUEmax);
	while (strlen($totQUEUEmax)>5) {$totQUEUEmax = ereg_replace(".$",'',$totQUEUEmax);}
$totDROPS =	sprintf("%5s", $totDROPS);
$totCALLS =	sprintf("%5s", $totCALLS);


$ASCII_text.="+-------------+-----------------------+-------+-------+  +-----------------------+-------+-------+\n";
$ASCII_text.="| TOTAL                               | $totQUEUEavg | $totQUEUEmax |  |                       | $totDROPS | $totCALLS |\n";
$ASCII_text.="+-------------------------------------+-------+-------+  +-----------------------+-------+-------+\n";
$CSV_text.="\"TOTAL\",\"$totQUEUEavg\",\"$totQUEUEmax\",\"\",\"$totDROPS\",\"$totCALLS\"\n\n";

$AVGHOLD_graph="<table cellspacing='0' cellpadding='0' class='horizontalgraph'><caption align='top'>AVERAGE HOLD TIME</caption><tr><th class='thgraph' scope='col'>TIME 15-MIN INT</th><th class='thgraph' scope='col'>AVG HOLD TIME</th><th class='thgraph' scope='col'>AVG</th><th class='thgraph' scope='col'>MAX</th></tr>";
$CALLSHANDLED_graph="<table cellspacing='0' cellpadding='0'><caption align='top'>CALLS HANDLED</caption><tr><th class='thgraph' scope='col'>TIME 15-MIN INT</th><th class='thgraph' scope='col'>DROPS <img src='./images/bar_blue.png' width='10' height='10'> / CALLS <img src='./images/bar.png' width='10' height='10'></th></tr>";

for ($d=0; $d<count($graph_stats); $d++) {
	if ($d==0) {$class=" first";} else if (($d+1)==count($graph_stats)) {$class=" last";} else {$class="";}
	$AVGHOLD_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][1]/$max_avg_hold_time)."' height='16' />".$graph_stats[$d][1]."</td><td class='chart_td$class'>".$graph_stats[$d][1]."</td><td class='chart_td$class'>".$graph_stats[$d][2]."</td></tr>";

	$CALLSHANDLED_graph.="  <tr><td class='chart_td' width='50'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value' width='600' valign='bottom'>";
	if ($graph_stats[$d][3]>0) {
		$CALLSHANDLED_graph.="<ul class='overlap_barGraph'><li class='p1' style='height: 12px; left: 0px; width: ".round(600*$graph_stats[$d][3]/$max_calls)."px'><font style='background-color: #900'>".$graph_stats[$d][3]."</font></li>";
		if ($graph_stats[$d][4]>0) {
			$CALLSHANDLED_graph.="<li class='p2' style='height: 12px; left: 0px; width: ".round(600*$graph_stats[$d][4]/$max_calls)."px'><font style='background-color: #009'>".$graph_stats[$d][4]."</font></li>";
		}
		$CALLSHANDLED_graph.="</ul>";
	} else {
		$CALLSHANDLED_graph.="0";
	}
	$CALLSHANDLED_graph.="</td></tr>";
}
$AVGHOLD_graph.="<tr><th class='thgraph' colspan='2' scope='col'>TOTALS:</th><th class='thgraph' scope='col'>".trim($totQUEUEavg)."&nbsp;</th><th class='thgraph' scope='col'>&nbsp;".trim($totQUEUEmax)."</th></tr></table>";
$CALLSHANDLED_graph.="<tr><th class='thgraph' scope='col'>TOTALS:</th><th class='thgraph' scope='col'>DROPS: ".trim($totDROPS)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CALLS: ".trim($totCALLS)."</th></tr></table>";
$JS_text.="function DrawHCDGraph(graph, th_id) {\n";
$JS_text.="	var AVGHOLD_graph=\"$AVGHOLD_graph\";\n";
$JS_text.="	var CALLSHANDLED_graph=\"$CALLSHANDLED_graph\";\n";
$JS_text.="\n";
$JS_text.="	for (var i=1; i<=2; i++) {\n";
$JS_text.="		var cellID=\"holdcalldropgraph\"+i;\n";
$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
$JS_text.="	}\n";
$JS_text.="	var cellID=\"holdcalldropgraph\"+th_id;\n";
$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
$JS_text.="	var graph_to_display=eval(graph+\"_graph\");\n";
$JS_text.="	document.getElementById('holdcalldrop_stats_graph').innerHTML=graph_to_display;\n";
$JS_text.="}\n";
$JS_onload.="\tDrawHCDGraph('AVGHOLD','1');\n"; 
$JS_onload.="}\n";
$JS_text.=$JS_onload;
$JS_text.="</script>\n";

if ($report_display_type=="HTML")
	{
	$MAIN.=$GRAPH;
	}
else
	{
	$MAIN.=$ASCII_text;
	}



##############################
#########  CALL HOLD TIME BREAKDOWN IN SECONDS, 15-MINUTE INCREMENT


$MAIN.="\n";
$MAIN.="---------- CALL HOLD TIME BREAKDOWN IN SECONDS\n";
$MAIN.="+-------------+-------+-----------------------------------------+ +------+--------------------------------+\n";
$MAIN.="|    TIME     |       |  % OF CALLS GROUPED BY HOLD TIME (SEC)  | |   AVERAGE TIME BEFORE ANSWER (SEC)    |\n";
$MAIN.="| 15 MIN INT  | CALLS |    0   20   40   60   80  100  120 120+ | | AVG  |0   20   40   60   80  100  120 |\n";
$MAIN.="+-------------+-------+-----------------------------------------+ +------+--------------------------------+\n";

$CSV_text.="\"CALL HOLD TIME BREAKDOWN IN SECONDS\"\n";
$CSV_text.="\"TIME 15-MIN INT\",\"CALLS\",\"0 (seconds)\",\"20\",\"40\",\"60\",\"80\",\"100\",\"120\",\"120+\",\"AVG TIME BEFORE ANSWER(SEC)\"\n";

$APhd__0=0; $APhd_20=0; $APhd_40=0; $APhd_60=0; $APhd_80=0; $APhd100=0; $APhd120=0; $APhd121=0;
$h=0;
while ($h < $TOTintervals)
	{
	$Aavg_hold[$h] = $qrtQUEUEavg[$h]; 
	if ($hd__0[$h] > 0) {$Phd__0[$h] = round( ( ($hd__0[$h] / $qrtCALLS[$h]) * 100) );}
		else {$Phd__0[$h]=0;}
	if ($hd_20[$h] > 0) {$Phd_20[$h] = round( ( ($hd_20[$h] / $qrtCALLS[$h]) * 100) );}
		else {$Phd_20[$h]=0;}
	if ($hd_40[$h] > 0) {$Phd_40[$h] = round( ( ($hd_40[$h] / $qrtCALLS[$h]) * 100) );}
		else {$Phd_40[$h]=0;}
	if ($hd_60[$h] > 0) {$Phd_60[$h] = round( ( ($hd_60[$h] / $qrtCALLS[$h]) * 100) );}
		else {$Phd_60[$h]=0;}
	if ($hd_80[$h] > 0) {$Phd_80[$h] = round( ( ($hd_80[$h] / $qrtCALLS[$h]) * 100) );}
		else {$Phd_80[$h]=0;}
	if ($hd100[$h] > 0) {$Phd100[$h] = round( ( ($hd100[$h] / $qrtCALLS[$h]) * 100) );}
		else {$Phd100[$h]=0;}
	if ($hd120[$h] > 0) {$Phd120[$h] = round( ( ($hd120[$h] / $qrtCALLS[$h]) * 100) );}
		else {$Phd120[$h]=0;}
	if ($hd121[$h] > 0) {$Phd121[$h] = round( ( ($hd121[$h] / $qrtCALLS[$h]) * 100) );}
		else {$Phd121[$h]=0;}
		while (strlen($qrtQUEUEavg[$h])>4) {$qrtQUEUEavg[$h] = ereg_replace(".$",'',$qrtQUEUEavg[$h]);}
	$hd__0[$h] =	sprintf("%4s", $hd__0[$h]);
	$hd_20[$h] =	sprintf("%4s", $hd_20[$h]);
	$hd_40[$h] =	sprintf("%4s", $hd_40[$h]);
	$hd_60[$h] =	sprintf("%4s", $hd_60[$h]);
	$hd_80[$h] =	sprintf("%4s", $hd_80[$h]);
	$hd100[$h] =	sprintf("%4s", $hd100[$h]);
	$hd120[$h] =	sprintf("%4s", $hd120[$h]);
	$hd121[$h] =	sprintf("%4s", $hd121[$h]);
	$Phd__0[$h] =	sprintf("%4s", $Phd__0[$h]);
	$Phd_20[$h] =	sprintf("%4s", $Phd_20[$h]);
	$Phd_40[$h] =	sprintf("%4s", $Phd_40[$h]);
	$Phd_60[$h] =	sprintf("%4s", $Phd_60[$h]);
	$Phd_80[$h] =	sprintf("%4s", $Phd_80[$h]);
	$Phd100[$h] =	sprintf("%4s", $Phd100[$h]);
	$Phd120[$h] =	sprintf("%4s", $Phd120[$h]);
	$Phd121[$h] =	sprintf("%4s", $Phd121[$h]);

	$ALLcalls = ($ALLcalls + $qrtCALLS[$h]);
	$ALLhd__0 = ($ALLhd__0 + $hd__0[$h]);
	$ALLhd_20 = ($ALLhd_20 + $hd_20[$h]);
	$ALLhd_40 = ($ALLhd_40 + $hd_40[$h]);
	$ALLhd_60 = ($ALLhd_60 + $hd_60[$h]);
	$ALLhd_80 = ($ALLhd_80 + $hd_80[$h]);
	$ALLhd100 = ($ALLhd100 + $hd100[$h]);
	$ALLhd120 = ($ALLhd120 + $hd120[$h]);
	$ALLhd121 = ($ALLhd121 + $hd121[$h]);

	if ( ($Aavg_hold[$h] < 1) or ($Aavg_hold[$h] > 119) )
		{
		if ($Aavg_hold[$h] < 1)		{$qrtQUEUEavg_scale[$h] = '                                ';}
		if ($Aavg_hold[$h] > 119)	{$qrtQUEUEavg_scale[$h] = '<SPAN class="orange">xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</SPAN>';}
		}
	else
		{
		$qrtQUEUEavg_val[$h] = ( (32 / 120) * $Aavg_hold[$h] );
		$k=0;
		$blank=0;
		while ($k < 32)
			{
			if ($k <= $qrtQUEUEavg_val[$h]) 
				{
				if ($k < 1) {$qrtQUEUEavg_scale[$h] .= '<SPAN class="orange">';}
				$qrtQUEUEavg_scale[$h] .= 'x';
				}
			else 
				{
				if ( ($k > 0) and ($blank < 1) ) {$qrtQUEUEavg_scale[$h] .= '</SPAN>';}
				$qrtQUEUEavg_scale[$h] .= ' ';
				$blank++;
				}
			$k++;
			if ( ($k > 31) and ($blank < 1) ) {$qrtQUEUEavg_scale[$h] .= '</SPAN>';}
			}
		}

	$Aavg_hold[$h] = sprintf("%4s", $Aavg_hold[$h]);
	while (strlen($Aavg_hold[$h])>4) {$Aavg_hold[$h] = ereg_replace("^.",'',$Aavg_hold[$h]);}

	$MAIN.="|$HMdisplay[$h]| $qrtCALLS[$h] | $Phd__0[$h] $Phd_20[$h] $Phd_40[$h] $Phd_60[$h] $Phd_80[$h] $Phd100[$h] $Phd120[$h] $Phd121[$h] | | $Aavg_hold[$h] |$qrtQUEUEavg_scale[$h]|\n";
	$CSV_text.="\"$HMdisplay[$h]\",\"$qrtCALLS[$h]\",\"$Phd__0[$h]\",\"$Phd_20[$h]\",\"$Phd_40[$h]\",\"$Phd_60[$h]\",\"$Phd_80[$h]\",\"$Phd100[$h]\",\"$Phd120[$h]\",\"$Phd121[$h]\",\"$Aavg_hold[$h]\"\n";
	$h++;
	}

if ($ALLhd__0 > 0) {$APhd__0 = round( ( ($ALLhd__0 / $ALLcalls) * 100) );}
if ($ALLhd_20 > 0) {$APhd_20 = round( ( ($ALLhd_20 / $ALLcalls) * 100) );}
if ($ALLhd_40 > 0) {$APhd_40 = round( ( ($ALLhd_40 / $ALLcalls) * 100) );}
if ($ALLhd_60 > 0) {$APhd_60 = round( ( ($ALLhd_60 / $ALLcalls) * 100) );}
if ($ALLhd_80 > 0) {$APhd_80 = round( ( ($ALLhd_80 / $ALLcalls) * 100) );}
if ($ALLhd100 > 0) {$APhd100 = round( ( ($ALLhd100 / $ALLcalls) * 100) );}
if ($ALLhd120 > 0) {$APhd120 = round( ( ($ALLhd120 / $ALLcalls) * 100) );}
if ($ALLhd121 > 0) {$APhd121 = round( ( ($ALLhd121 / $ALLcalls) * 100) );}

$ALLcalls =	sprintf("%5s", $ALLcalls);
$APhd__0 =	sprintf("%4s", $APhd__0);
$APhd_20 =	sprintf("%4s", $APhd_20);
$APhd_40 =	sprintf("%4s", $APhd_40);
$APhd_60 =	sprintf("%4s", $APhd_60);
$APhd_80 =	sprintf("%4s", $APhd_80);
$APhd100 =	sprintf("%4s", $APhd100);
$APhd120 =	sprintf("%4s", $APhd120);
$APhd121 =	sprintf("%4s", $APhd121);

	while (strlen($totQUEUEavg)>4) {$totQUEUEavg = ereg_replace(".$",'',$totQUEUEavg);}

$MAIN.="+-------------+-------+-----------------------------------------+ +------+--------------------------------+\n";
$MAIN.="| TOTAL       | $ALLcalls | $APhd__0 $APhd_20 $APhd_40 $APhd_60 $APhd_80 $APhd100 $APhd120 $APhd121 | | $totQUEUEavg |\n";
$MAIN.="+-------------+-------+-----------------------------------------+ +------+\n";
$CSV_text.="\" TOTAL\",\"$ALLcalls\",$APhd__0\",\"$APhd_20\",\"$APhd_40\",\"$APhd_60\",\"$APhd_80\",\"$APhd100\",\"$APhd120\",\"$APhd121\",\"$totQUEUEavg\"\n";

$ENDtime = date("U");
$RUNtime = ($ENDtime - $STARTtime);
$MAIN.="\nRun Time: $RUNtime seconds|$db_source\n";
$MAIN.="</PRE>\n";
$MAIN.="</TD></TR></TABLE>\n";
$MAIN.="</BODY></HTML>\n";

if ($file_download > 0)
	{
	$FILE_TIME = date("Ymd-His");
	$CSVfilename = "AST_CLOSER_service_level_$US$FILE_TIME.csv";
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
	echo $JS_text;
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
