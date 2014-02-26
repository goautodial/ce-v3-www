<?php 
# AST_CLOSERstats.php
# 
# Copyright (C) 2013  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES:
# 60619-1714 - Added variable filtering to eliminate SQL injection attack threat
#            - Added required user/pass to gain access to this page
# 60905-1326 - Added queue time stats
# 71008-1436 - Added shift to be defined in dbconnect.php
# 71025-0021 - Added status breakdown
# 71218-1155 - Added end_date for multi-day reports
# 80430-1920 - Added Customer hangup cause stats
# 80709-0331 - Added time stats to call statuses
# 80722-2149 - Added Status Category stats
# 81015-0705 - Added IVR calls count
# 81024-0037 - Added multi-select inbound-groups
# 81105-2118 - Added Answered calls 15-minute breakdown
# 81109-2340 - Added custom indicators section
# 90116-1040 - Rewrite of the 15-minute sections to speed it up and allow multi-day calculations
# 90310-2037 - Admin header
# 90508-0644 - Changed to PHP long tags
# 90524-2231 - Changed to use functions.php for seconds to HH:MM:SS conversion
# 90801-0921 - Added in-group name to pulldown
# 91214-0955 - Added INITIAL QUEUE POSITION BREAKDOWN
# 100206-1454 - Fixed TMR(service level) calculation
# 100214-1421 - Sort menu alphabetically
# 100216-0042 - Added popup date selector
# 100709-1809 - Added system setting slave server option
# 100802-2347 - Added User Group Allowed Reports option validation
# 100913-1634 - Added DID option to select by DIDs instead of In-groups
# 100914-1326 - Added lookup for user_level 7 users to set to reports only which will remove other admin links
# 110703-1759 - Added download option
# 111103-0632 - Added MAXCAL as a drop status
# 111103-2003 - Added user_group restrictions for selecting in-groups
# 120224-0910 - Added HTML display option with bar graphs
# 120730-0724 - Small fix for HTML output
# 130124-1719 - Added email report support
# 130414-1429 - Added report logging
#

$startMS = microtime();

require("dbconnect.php");
require("functions.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["group"]))				{$group=$_GET["group"];}
	elseif (isset($_POST["group"]))		{$group=$_POST["group"];}
if (isset($_GET["query_date"]))				{$query_date=$_GET["query_date"];}
	elseif (isset($_POST["query_date"]))	{$query_date=$_POST["query_date"];}
if (isset($_GET["end_date"]))			{$end_date=$_GET["end_date"];}
	elseif (isset($_POST["end_date"]))	{$end_date=$_POST["end_date"];}
if (isset($_GET["shift"]))				{$shift=$_GET["shift"];}
	elseif (isset($_POST["shift"]))		{$shift=$_POST["shift"];}
if (isset($_GET["submit"]))				{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))	{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))				{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))	{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["DID"]))				{$DID=$_GET["DID"];}
	elseif (isset($_POST["DID"]))		{$DID=$_POST["DID"];}
if (isset($_GET["EMAIL"]))				{$EMAIL=$_GET["EMAIL"];}
	elseif (isset($_POST["EMAIL"]))		{$EMAIL=$_POST["EMAIL"];}
if (isset($_GET["DB"]))					{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))		{$DB=$_POST["DB"];}
if (isset($_GET["file_download"]))				{$file_download=$_GET["file_download"];}
	elseif (isset($_POST["file_download"]))	{$file_download=$_POST["file_download"];}
if (isset($_GET["report_display_type"]))				{$report_display_type=$_GET["report_display_type"];}
	elseif (isset($_POST["report_display_type"]))	{$report_display_type=$_POST["report_display_type"];}

$PHP_AUTH_USER = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_USER);
$PHP_AUTH_PW = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_PW);

$MT[0]='0';
if (strlen($shift)<2) {$shift='ALL';}

$report_name = 'Inbound Report';
$db_source = 'M';

# $test_table_name="vicidial_closer_log";

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


$stmt = "SELECT local_gmt FROM servers where active='Y' limit 1;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$gmt_conf_ct = mysql_num_rows($rslt);
$dst = date("I");
if ($gmt_conf_ct > 0)
	{
	$row=mysql_fetch_row($rslt);
	$local_gmt =		$row[0];
	$epoch_offset =		(($local_gmt + $dst) * 3600);
	}

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

$stmt="select group_id,group_name,8 from vicidial_inbound_groups where group_handling='PHONE' $LOGadmin_viewable_groupsSQL order by group_id;";
if ($DID=='Y')
	{
	$stmt="select did_pattern,did_description,did_id from vicidial_inbound_dids $whereLOGadmin_viewable_groupsSQL order by did_pattern;";
	}
if ($EMAIL=='Y')
	{
	$stmt="select email_account_id,email_account_name,email_account_id from vicidial_email_accounts $whereLOGadmin_viewable_groupsSQL order by email_account_id;";
	$stmt="select group_id,group_name,8 from vicidial_inbound_groups where group_handling='EMAIL' $LOGadmin_viewable_groupsSQL order by group_id;";
	}
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$groups_to_print = mysql_num_rows($rslt);
$i=0;
$LISTgroups[$i]='---NONE---';
$i++;
$groups_to_print++;
$groups_string='|';
while ($i < $groups_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$LISTgroups[$i] =		$row[0];
	$LISTgroup_names[$i] =	$row[1];
	$LISTgroup_ids[$i] =	$row[2];
	$groups_string .= "$LISTgroups[$i]|";
	$i++;
	}

$i=0;
$group_string='|';
$group_ct = count($group);
while($i < $group_ct)
	{
	if ( (strlen($group[$i]) > 0) and (preg_match("/\|$group[$i]\|/",$groups_string)) )
		{
		$group_string .= "$group[$i]|";
		$group_SQL .= "'$group[$i]',";
		$groupQS .= "&group[]=$group[$i]";
		}
	$i++;
	}
if ( (ereg("--NONE--",$group_string) ) or ($group_ct < 1) )
	{
	$group_SQL = "''";
#	$group_SQL = "group_id IN('')";
	}
else
	{
	$group_SQL = eregi_replace(",$",'',$group_SQL);
#	$group_SQL = "group_id IN($group_SQL)";
	}
if (strlen($group_SQL)<3) {$group_SQL="''";}

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

$AMP='&';
$QM='?';
$stmt="INSERT INTO vicidial_report_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name |$group[0], $query_date, $end_date, $shift, $DID, $EMAIL, $file_download, $report_display_type|', url='".$LOGfull_url."?DB=".$DB."&DID=".$DID."&EMAIL=".$EMAIL."&query_date=".$query_date."&end_date=".$end_date."&shift=".$shift."&report_display_type=".$report_display_type."$groupQS';";
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

$stmt="select vsc_id,vsc_name from vicidial_status_categories;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$statcats_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $statcats_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$vsc_id[$i] =	$row[0];
	$vsc_name[$i] =	$row[1];
	$vsc_count[$i] = 0;
	$i++;
	}

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


$HEADER.="<script language=\"JavaScript\" src=\"calendar_db.js\"></script>\n";
$HEADER.="<link rel=\"stylesheet\" href=\"calendar.css\">\n";
$HEADER.="<link rel=\"stylesheet\" href=\"horizontalbargraph.css\">\n";
$HEADER.="<link rel=\"stylesheet\" href=\"verticalbargraph.css\">\n";
$HEADER.="<script language=\"JavaScript\" src=\"wz_jsgraphics.js\"></script>\n";
$HEADER.="<script language=\"JavaScript\" src=\"line.js\"></script>\n";
$HEADER.="<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";
$HEADER.="<TITLE>$report_name</TITLE></HEAD><BODY BGCOLOR=WHITE marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>\n";

$short_header=1;

#require("admin_header.php");

$MAIN.="<TABLE CELLPADDING=4 CELLSPACING=0><TR><TD>";

if ($DB > 0)
	{
	$MAIN.="<BR>\n";
	$MAIN.="$group_ct|$group_string|$group_SQL\n";
	$MAIN.="<BR>\n";
	$MAIN.="$shift|$query_date|$end_date\n";
	$MAIN.="<BR>\n";
	}

$MAIN.="<FORM ACTION=\"$PHP_SELF\" METHOD=POST name=vicidial_report id=vicidial_report>\n";
$MAIN.="<TABLE BORDER=0><TR><TD VALIGN=TOP>\n";
$MAIN.="<INPUT TYPE=HIDDEN NAME=DB VALUE=\"$DB\">\n";
$MAIN.="<INPUT TYPE=HIDDEN NAME=DID VALUE=\"$DID\">\n";
$MAIN.="<INPUT TYPE=HIDDEN NAME=EMAIL VALUE=\"$EMAIL\">\n";
$MAIN.="Date Range:<BR>\n";
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

$MAIN.="</TD><TD ROWSPAN=2 VALIGN=TOP>\n";
if ($EMAIL=='Y')
	{$MAIN.="Email Accts: \n";}
else if ($DID=='Y')
	{$MAIN.="Inbound DIDs: \n";}
else
	{$MAIN.="Inbound Groups: \n";}
$MAIN.="</TD><TD ROWSPAN=2 VALIGN=TOP>\n";
$MAIN.="<SELECT SIZE=5 NAME=group[] multiple>\n";
$o=0;
while ($groups_to_print > $o)
	{
	if (ereg("\|$LISTgroups[$o]\|",$group_string)) 
		{$MAIN.="<option selected value=\"$LISTgroups[$o]\">$LISTgroups[$o] - $LISTgroup_names[$o]</option>\n";}
	else
		{$MAIN.="<option value=\"$LISTgroups[$o]\">$LISTgroups[$o] - $LISTgroup_names[$o]</option>\n";}
	$o++;
	}
$MAIN.="</SELECT>\n";
$MAIN.="</TD><TD ROWSPAN=2 VALIGN=TOP>\n";
$MAIN.="<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ";
if ($DID!='Y')
	{
	$MAIN.="<a href=\"./admin.php?ADD=3111&group_id=$group[0]\">MODIFY</a> | ";
	$MAIN.="<a href=\"./AST_IVRstats.php?query_date=$query_date&end_date=$end_date&shift=$shift$groupQS\">IVR REPORT</a> | \n";
	}
$MAIN.="<a href=\"./admin.php?ADD=999999\">REPORTS</a> | ";
$MAIN.="</FONT>\n";

$MAIN.="</TD></TR>\n";
$MAIN.="<TR><TD>\n";

#$MAIN.="<SELECT SIZE=1 NAME=group>\n";
#	$o=0;
#	while ($groups_to_print > $o)
#	{
#		if ($groups[$o] == $group) {$MAIN.="<option selected value=\"$groups[$o]\">$groups[$o]</option>\n";}
#		  else {$MAIN.="<option value=\"$groups[$o]\">$groups[$o]</option>\n";}
#		$o++;
#	}
#$MAIN.="</SELECT>\n";
$MAIN.="Shift: <SELECT SIZE=1 NAME=shift>\n";
$MAIN.="<option selected value=\"$shift\">$shift</option>\n";
$MAIN.="<option value=\"\">--</option>\n";
$MAIN.="<option value=\"AM\">AM</option>\n";
$MAIN.="<option value=\"PM\">PM</option>\n";
$MAIN.="<option value=\"ALL\">ALL</option>\n";
$MAIN.="</SELECT>\n";
$MAIN.="<BR>Display as:&nbsp; ";
$MAIN.="<select name='report_display_type'>";
if ($report_display_type) {$MAIN.="<option value='$report_display_type' selected>$report_display_type</option>";}
$MAIN.="<option value='TEXT'>TEXT</option><option value='HTML'>HTML</option></select>\n<BR>";
$MAIN.=" &nbsp; <INPUT TYPE=submit NAME=SUBMIT VALUE=SUBMIT>\n";
$MAIN.="</TD></TR></TABLE>\n";
$MAIN.="</FORM>\n\n";
$MAIN.="<PRE><FONT SIZE=2>\n\n";


if ($groups_to_print < 1)
	{
	$MAIN.="\n\n";
	if ($EMAIL=='Y')
		{$MAIN.="PLEASE SELECT AN EMAIL ACCOUNT AND DATE RANGE ABOVE AND CLICK SUBMIT\n";}
	if ($DID=='Y')
		{$MAIN.="PLEASE SELECT A DID AND DATE RANGE ABOVE AND CLICK SUBMIT\n";}
	else
		{$MAIN.="PLEASE SELECT AN IN-GROUP AND DATE RANGE ABOVE AND CLICK SUBMIT\n";}
	}

else
{
if ($shift == 'AM') 
	{
	$time_BEGIN=$AM_shift_BEGIN;
	$time_END=$AM_shift_END;
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "03:45:00";}   
	if (strlen($time_END) < 6) {$time_END = "15:15:00";}
	}
if ($shift == 'PM') 
	{
	$time_BEGIN=$PM_shift_BEGIN;
	$time_END=$PM_shift_END;
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "15:15:00";}
	if (strlen($time_END) < 6) {$time_END = "23:15:00";}
	}
if ($shift == 'ALL') 
	{
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "00:00:00";}
	if (strlen($time_END) < 6) {$time_END = "23:59:59";}
	}
$query_date_BEGIN = "$query_date $time_BEGIN";   
$query_date_END = "$end_date $time_END";

if ($EMAIL=='Y') 
	{
	$MAIN.="Inbound Email Stats: $group_string          $NOW_TIME        <a href=\"$PHP_SELF?DB=$DB&DID=$DID&query_date=$query_date&end_date=$end_date$groupQS&shift=$shift&SUBMIT=$SUBMIT&file_download=1\">DOWNLOAD</a>\n";
	$CSV_text1.="\"Inbound Call Stats:\",\"$group_string\",\"$NOW_TIME\"\n";
	}
else
	{
	$MAIN.="Inbound Call Stats: $group_string          $NOW_TIME        <a href=\"$PHP_SELF?DB=$DB&DID=$DID&query_date=$query_date&end_date=$end_date$groupQS&shift=$shift&SUBMIT=$SUBMIT&file_download=1\">DOWNLOAD</a>\n";
	$CSV_text1.="\"Inbound Call Stats:\",\"$group_string\",\"$NOW_TIME\"\n";
	}



if ($DID=='Y')
	{
	$stmt="select did_id from vicidial_inbound_dids where did_pattern IN($group_SQL) $LOGadmin_viewable_groupsSQL;";
	$rslt=mysql_query($stmt, $link);
	if ($DB) {$MAIN.="$stmt\n";}
	$dids_to_print = mysql_num_rows($rslt);
	$i=0;
	while ($i < $dids_to_print)
		{
		$row=mysql_fetch_row($rslt);
		$did_id[$i] = $row[0];
		$did_SQL .= "'$row[0]',";
		$i++;
		}
	$did_SQL = eregi_replace(",$",'',$did_SQL);
	if (strlen($did_SQL)<3) {$did_SQL="''";}

	$stmt="select uniqueid from vicidial_did_log where did_id IN($did_SQL);";
	$rslt=mysql_query($stmt, $link);
	if ($DB) {$MAIN.="$stmt\n";}
	$unids_to_print = mysql_num_rows($rslt);
	$i=0;
	while ($i < $unids_to_print)
		{
		$row=mysql_fetch_row($rslt);
		$unid_SQL .= "'$row[0]',";
		$i++;
		}
	$unid_SQL = eregi_replace(",$",'',$unid_SQL);
	if (strlen($unid_SQL)<3) {$unid_SQL="''";}

	if ($DB > 0)
		{$MAIN.="|$did_SQL|$unid_SQL|\n";}

	}

if ($group_ct > 1)
	{
	$ASCII_text.="\n";
	$ASCII_text.="---------- MULTI-GROUP BREAKDOWN:\n";

	$CSV_text1.="\n\"MULTI-GROUP BREAKDOWN:\"\n";

	if ($EMAIL=='Y')
		{
		$ASCII_text.="+----------------------+---------+---------+---------+---------+\n";
		$ASCII_text.="| EMAIL                | EMAILS  | DROPS   | DROP %  | IVR     |\n";
		$ASCII_text.="+----------------------+---------+---------+---------+---------+\n";
		$CSV_text1.="\"EMAIL\",\"CALLS\",\"DROPS\",\"DROP %\",\"IVR\"\n";
		}
	else if ($DID=='Y')
		{
		$ASCII_text.="+----------------------+---------+---------+---------+---------+\n";
		$ASCII_text.="| DID                  | CALLS   | DROPS   | DROP %  | IVR     |\n";
		$ASCII_text.="+----------------------+---------+---------+---------+---------+\n";
		$CSV_text1.="\"DID\",\"CALLS\",\"DROPS\",\"DROP %\",\"IVR\"\n";
		}
	else
		{
		$ASCII_text.="+----------------------+---------+---------+---------+---------+\n";
		$ASCII_text.="| IN-GROUP             | CALLS   | DROPS   | DROP %  | IVR     |\n";
		$ASCII_text.="+----------------------+---------+---------+---------+---------+\n";
		$CSV_text1.="\"IN-GROUP\",\"CALLS\",\"DROPS\",\"DROP %\",\"IVR\"\n";
		}

	$i=0;
	while($i < $group_ct)
		{
		$did_id[$i]='0';
		$DIDunid_SQL='';
		$stmt="select did_id from vicidial_inbound_dids where did_pattern='$group[$i]';";
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$ASCII_text.="$stmt\n";}
		$Sdids_to_print = mysql_num_rows($rslt);
		if ($Sdids_to_print > 0)
			{
			$row=mysql_fetch_row($rslt);
			$did_id[$i] = $row[0];
			}

		$stmt="select uniqueid from vicidial_did_log where did_id='$did_id[$i]';";
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$ASCII_text.="$stmt\n";}
		$DIDunids_to_print = mysql_num_rows($rslt);
		$k=0;
		while ($k < $DIDunids_to_print)
			{
			$row=mysql_fetch_row($rslt);
			$DIDunid_SQL .= "'$row[0]',";
			$k++;
			}
		$DIDunid_SQL = eregi_replace(",$",'',$DIDunid_SQL);
		if (strlen($DIDunid_SQL)<3) {$DIDunid_SQL="''";}

		$stmt="select count(*),sum(length_in_sec) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='$group[$i]';";
		if ($DID=='Y')
			{
			$stmt="select count(*),sum(length_in_sec) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($DIDunid_SQL);";
			}
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$ASCII_text.="$stmt\n";}
		$row=mysql_fetch_row($rslt);

		$stmt="select count(*) from live_inbound_log where start_time >= '$query_date_BEGIN' and start_time <= '$query_date_END' and comment_a='$group[$i]' and comment_b='START';";
		if ($DID=='Y')
			{
			$stmt="select count(*) from live_inbound_log where start_time >= '$query_date_BEGIN' and start_time <= '$query_date_END' and uniqueid IN($DIDunid_SQL);";
			}
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$ASCII_text.="$stmt\n";}
		$rowx=mysql_fetch_row($rslt);

		$stmt="select count(*),sum(length_in_sec) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='$group[$i]' and status IN('DROP','XDROP') and (length_in_sec <= 49999 or length_in_sec is null);";
		if ($DID=='Y')
			{
			$stmt="select count(*),sum(length_in_sec) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and status IN('DROP','XDROP') and (length_in_sec <= 49999 or length_in_sec is null) and uniqueid IN($DIDunid_SQL);";
			}
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$ASCII_text.="$stmt\n";}
		$rowy=mysql_fetch_row($rslt);
		if ($row[0]>$max_value) {$max_value=$row[0];}

		$groupDISPLAY =	sprintf("%20s", $group[$i]);
		$gTOTALcalls =	sprintf("%7s", $row[0]);
		$gIVRcalls =	sprintf("%7s", $rowx[0]);
		$gDROPcalls =	sprintf("%7s", $rowy[0]);
		if ( ($gDROPcalls < 1) or ($gTOTALcalls < 1) )
			{$gDROPpercent = '0';}
		else
			{
			$gDROPpercent = (($gDROPcalls / $gTOTALcalls) * 100);
			$gDROPpercent = round($gDROPpercent, 2);
			}
		$gDROPpercent =	sprintf("%6s", $gDROPpercent);

		$ASCII_text.="| $groupDISPLAY | $gTOTALcalls | $gDROPcalls | $gDROPpercent% | $gIVRcalls |\n";
		$CSV_text1.="\"$groupDISPLAY\",\"$gTOTALcalls\",\"$gDROPcalls\",\"$gDROPpercent%\",\"$gIVRcalls\"\n";
		$graph_data_ary[$i][0]=$groupDISPLAY;
		$graph_data_ary[$i][1]=$gTOTALcalls;
		$graph_data_ary[$i][2]=$gDROPcalls;
		$graph_data_ary[$i][3]=$gDROPpercent;
		$graph_data_ary[$i][4]=$gIVRcalls;

		$i++;
		}

	$ASCII_text.="+----------------------+---------+---------+---------+---------+\n";

	}

	if ($report_display_type=="HTML") {
		$max_value=1;
		$graph_height=240;
		$scale = 1;
		$w=0;
		for ($i=0; $i<count($graph_data_ary); $i++) {
			if ($graph_data_ary[$i][1]>$max_value) {$max_value=$graph_data_ary[$i][1];}
			if ($graph_data_ary[$i][1]>0) {$w++;}
		}
		$scale = $graph_height / $max_value;


		$GRAPH_text.="<table cellspacing='0' cellpadding='0'><caption align='top'>MULTI-GROUP BREAKDOWN</caption><tr height='25' valign='top'><th class='thgraph' scope='col'>GROUP</th><th class='thgraph' scope='col'>IVRS <img src='./images/bar_green.png' width='10' height='10'> / DROPS <img src='./images/bar_blue.png' width='10' height='10'> / CALLS <img src='./images/bar.png' width='10' height='10'></th></tr>";
		for ($d=0; $d<count($graph_data_ary); $d++) {
			if (strlen(trim($graph_data_ary[$d][0]))>0) {
				$graph_data_ary[$d][0]=preg_replace('/\s/', "", $graph_data_ary[$d][0]); 
				$GRAPH_text.="  <tr><td class='chart_td' width='50'>".$graph_data_ary[$d][0]."<BR>".$graph_data_ary[$d][3]."% drops<BR>&nbsp;</td><td nowrap class='chart_td value' width='600' valign='top'>\n";
				if ($graph_data_ary[$d][1]>0) {
					$GRAPH_text.="<ul class='overlap_barGraph'><li class=\"p1\" style=\"height: 12px; left: 0px; width: ".round(600*$graph_data_ary[$d][1]/$max_value)."px\"><font style='background-color: #900'>".$graph_data_ary[$d][1]."</font></li>";
					if ($graph_data_ary[$d][2]>0) {
						$GRAPH_text.="<li class=\"p2\" style=\"height: 12px; left: 0px; width: ".round(600*$graph_data_ary[$d][2]/$max_value)."px\"><font style='background-color: #009'>".$graph_data_ary[$d][2]."</font></li>";
					}
					if ($graph_data_ary[$d][4]>0) {
						$GRAPH_text.="<li class=\"p3\" style=\"height: 12px; left: 0px; width: ".round(600*$graph_data_ary[$d][4]/$max_value)."px\"><font style='background-color: #090'>".$graph_data_ary[$d][4]."</font></li>";
					}
					$GRAPH_text.="</ul>\n";
				} else {
					$GRAPH_text.="0";
				}
				$GRAPH_text.="</td></tr>\n";
			}
		}
		$GRAPH_text.="</table><BR/><BR/>";


		$MAIN.=$GRAPH_text;
		}
	else 
		{
		$MAIN.=$ASCII_text;
		}

$MAIN.="\n";
$MAIN.="Time range: $query_date_BEGIN to $query_date_END\n\n";
$MAIN.="---------- TOTALS\n";

$CSV_text1.="\n\"Time range:\",\"$query_date_BEGIN\",\"to\",\"$query_date_END\"\n\n";

$stmt="select count(*),sum(length_in_sec) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id IN($group_SQL);";
if ($DID=='Y')
	{
	$stmt="select count(*),sum(length_in_sec) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL);";
	}
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$row=mysql_fetch_row($rslt);

$stmt="select count(*),sum(queue_seconds) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id IN($group_SQL) and status NOT IN('DROP','XDROP','HXFER','QVMAIL','HOLDTO','LIVE','QUEUE','TIMEOT','AFTHRS','NANQUE','INBND','MAXCAL');";
if ($DID=='Y')
	{
	$stmt="select count(*),sum(queue_seconds) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and status NOT IN('DROP','XDROP','HXFER','QVMAIL','HOLDTO','LIVE','QUEUE','TIMEOT','AFTHRS','NANQUE','INBND','MAXCAL') and uniqueid IN($unid_SQL);";
	}
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$rowy=mysql_fetch_row($rslt);

$stmt="select count(*) from live_inbound_log where start_time >= '$query_date_BEGIN' and start_time <= '$query_date_END' and comment_a IN($group_SQL) and comment_b='START';";
if ($DID=='Y')
	{
	$stmt="select count(*) from live_inbound_log where start_time >= '$query_date_BEGIN' and start_time <= '$query_date_END' and uniqueid IN($unid_SQL);";
	}
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$rowx=mysql_fetch_row($rslt);

$TOTALcalls =	sprintf("%10s", $row[0]);
$IVRcalls =	sprintf("%10s", $rowx[0]);
$TOTALsec =		$row[1];
if ( ($row[0] < 1) or ($TOTALsec < 1) )
	{$average_call_seconds = '         0';}
else
	{
	$average_call_seconds = ($TOTALsec / $row[0]);
	$average_call_seconds = round($average_call_seconds, 0);
	$average_call_seconds =	sprintf("%10s", $average_call_seconds);
	}
$ANSWEREDcalls  =	sprintf("%10s", $rowy[0]);
if ( ($ANSWEREDcalls < 1) or ($TOTALcalls < 1) )
	{$ANSWEREDpercent = '0';}
else
	{
	$ANSWEREDpercent = (($ANSWEREDcalls / $TOTALcalls) * 100);
	$ANSWEREDpercent = round($ANSWEREDpercent, 0);
	}
if ( ($rowy[0] < 1) or ($ANSWEREDcalls < 1) )
	{$average_answer_seconds = '         0';}
else
	{
	$average_answer_seconds = ($rowy[1] / $rowy[0]);
	$average_answer_seconds = round($average_answer_seconds, 2);
	$average_answer_seconds =	sprintf("%10s", $average_answer_seconds);
	}

if ($EMAIL=='Y')
	{
	$MAIN.="Total Emails taken in to this In-Group:        $TOTALcalls\n";
	$MAIN.="Average Email Length for all Emails:            $average_call_seconds seconds\n";
	$MAIN.="Answered Emails:                               $ANSWEREDcalls  $ANSWEREDpercent%\n";
	$MAIN.="Average queue time for Answered Emails:        $average_answer_seconds seconds\n";
	$MAIN.="Emails taken into the IVR for this In-Group:   $IVRcalls\n";

	$CSV_text1.="\"Total Emails taken in to this In-Group:\",\"$TOTALcalls\"\n";
	$CSV_text1.="\"Average Email Length for all Emails:\",\"$average_call_seconds seconds\"\n";
	$CSV_text1.="\"Answered Emails:\",\"$ANSWEREDcalls\",\"$ANSWEREDpercent%\"\n";
	$CSV_text1.="\"Average queue time for Answered Emails:\",\"$average_answer_seconds seconds\"\n";
	$CSV_text1.="\"Emails taken into the IVR for this In-Group:\",\"$IVRcalls\"\n";
	}
else
	{
	$MAIN.="Total calls taken in to this In-Group:        $TOTALcalls\n";
	$MAIN.="Average Call Length for all Calls:            $average_call_seconds seconds\n";
	$MAIN.="Answered Calls:                               $ANSWEREDcalls  $ANSWEREDpercent%\n";
	$MAIN.="Average queue time for Answered Calls:        $average_answer_seconds seconds\n";
	$MAIN.="Calls taken into the IVR for this In-Group:   $IVRcalls\n";

	$CSV_text1.="\"Total calls taken in to this In-Group:\",\"$TOTALcalls\"\n";
	$CSV_text1.="\"Average Call Length for all Calls:\",\"$average_call_seconds seconds\"\n";
	$CSV_text1.="\"Answered Calls:\",\"$ANSWEREDcalls\",\"$ANSWEREDpercent%\"\n";
	$CSV_text1.="\"Average queue time for Answered Calls:\",\"$average_answer_seconds seconds\"\n";
	$CSV_text1.="\"Calls taken into the IVR for this In-Group:\",\"$IVRcalls\"\n";
	}

$MAIN.="\n";
$MAIN.="---------- DROPS\n";

$CSV_text1.="\n\"DROPS\"\n";

$stmt="select count(*),sum(length_in_sec) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id IN($group_SQL) and status IN('DROP','XDROP') and (length_in_sec <= 49999 or length_in_sec is null);";
if ($DID=='Y')
	{
	$stmt="select count(*),sum(length_in_sec) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and status IN('DROP','XDROP') and (length_in_sec <= 49999 or length_in_sec is null) and uniqueid IN($unid_SQL);";
	}
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$row=mysql_fetch_row($rslt);

$DROPcalls =	sprintf("%10s", $row[0]);
if ( ($DROPcalls < 1) or ($TOTALcalls < 1) )
	{$DROPpercent = '0';}
else
	{
	$DROPpercent = (($DROPcalls / $TOTALcalls) * 100);
	$DROPpercent = round($DROPpercent, 0);
	}

if ( ($row[0] < 1) or ($row[1] < 1) )
	{
	$average_hold_seconds = '         0';
	}
else
	{
	$average_hold_seconds = ($row[1] / $row[0]);
	$average_hold_seconds = round($average_hold_seconds, 0);
	$average_hold_seconds =	sprintf("%10s", $average_hold_seconds);
	}
if ( ($ANSWEREDcalls < 1) or ($DROPcalls < 1) )
	{$DROP_ANSWEREDpercent = '0';}
else
	{
	$DROP_ANSWEREDpercent = (($DROPcalls / $ANSWEREDcalls) * 100);
	$DROP_ANSWEREDpercent = round($DROP_ANSWEREDpercent, 0);
	}

if ($EMAIL=='Y')
	{
	$MAIN.="Total DROP Emails:                             $DROPcalls  $DROPpercent%               drop/answered: $DROP_ANSWEREDpercent%\n";
	$MAIN.="Average hold time for DROP Emails:             $average_hold_seconds seconds\n";

	$CSV_text1.="\"Total DROP Emails:\",\"$DROPcalls\",\"$DROPpercent%\",\"drop/answered:\",\"$DROP_ANSWEREDpercent%\"\n";
	$CSV_text1.="\"Average hold time for DROP Emails:\",\"$average_hold_seconds seconds\"\n";
	}
else
	{
	$MAIN.="Total DROP Calls:                             $DROPcalls  $DROPpercent%               drop/answered: $DROP_ANSWEREDpercent%\n";
	$MAIN.="Average hold time for DROP Calls:             $average_hold_seconds seconds\n";

	$CSV_text1.="\"Total DROP Calls:\",\"$DROPcalls\",\"$DROPpercent%\",\"drop/answered:\",\"$DROP_ANSWEREDpercent%\"\n";
	$CSV_text1.="\"Average hold time for DROP Calls:\",\"$average_hold_seconds seconds\"\n";
	}

if (strlen($group_SQL)>3)
	{
	if ($DID!='Y')
		{
		$stmt = "SELECT answer_sec_pct_rt_stat_one,answer_sec_pct_rt_stat_two from vicidial_inbound_groups where group_id IN($group_SQL) order by answer_sec_pct_rt_stat_one desc limit 1;";
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$MAIN.="$stmt\n";}
		$row=mysql_fetch_row($rslt);
		$Sanswer_sec_pct_rt_stat_one = $row[0];
		$Sanswer_sec_pct_rt_stat_two = $row[1];

		$stmt = "SELECT count(*) from vicidial_closer_log where campaign_id IN($group_SQL) and call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and queue_seconds <= $Sanswer_sec_pct_rt_stat_one and status NOT IN('DROP','XDROP','HXFER','QVMAIL','HOLDTO','LIVE','QUEUE','TIMEOT','AFTHRS','NANQUE','INBND','MAXCAL');";
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$MAIN.="$stmt\n";}
		$row=mysql_fetch_row($rslt);
		$answer_sec_pct_rt_stat_one = $row[0];

		$stmt = "SELECT count(*) from vicidial_closer_log where campaign_id IN($group_SQL) and call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and queue_seconds <= $Sanswer_sec_pct_rt_stat_two and status NOT IN('DROP','XDROP','HXFER','QVMAIL','HOLDTO','LIVE','QUEUE','TIMEOT','AFTHRS','NANQUE','INBND','MAXCAL');";
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$MAIN.="$stmt\n";}
		$row=mysql_fetch_row($rslt);
		$answer_sec_pct_rt_stat_two = $row[0];

		if ( ($ANSWEREDcalls > 0) and ($answer_sec_pct_rt_stat_one > 0) and ($answer_sec_pct_rt_stat_two > 0) )
			{
			$PCTanswer_sec_pct_rt_stat_one = (($answer_sec_pct_rt_stat_one / $ANSWEREDcalls) * 100);
			$PCTanswer_sec_pct_rt_stat_one = round($PCTanswer_sec_pct_rt_stat_one, 0);
			#$PCTanswer_sec_pct_rt_stat_one = sprintf("%10s", $PCTanswer_sec_pct_rt_stat_one);
			$PCTanswer_sec_pct_rt_stat_two = (($answer_sec_pct_rt_stat_two / $ANSWEREDcalls) * 100);
			$PCTanswer_sec_pct_rt_stat_two = round($PCTanswer_sec_pct_rt_stat_two, 0);
			#$PCTanswer_sec_pct_rt_stat_two = sprintf("%10s", $PCTanswer_sec_pct_rt_stat_two);
			}
		}
	}

if ($EMAIL=='Y')
	{
	$MAIN.="\n";
	$MAIN.="---------- CUSTOM INDICATORS\n";
	$MAIN.="GDE (Answered/Total emails taken in to this In-Group):  $ANSWEREDpercent%\n";
	$MAIN.="ACR (Dropped/Answered):                                $DROP_ANSWEREDpercent%\n";

	$CSV_text1.="\n\"CUSTOM INDICATORS\"\n";
	$CSV_text1.="\"GDE (Answered/Total emails taken in to this In-Group):\",\"$ANSWEREDpercent%\"\n";
	$CSV_text1.="\"ACR (Dropped/Answered):\",\"$DROP_ANSWEREDpercent%\"\n";
	}
else
	{
	$MAIN.="\n";
	$MAIN.="---------- CUSTOM INDICATORS\n";
	$MAIN.="GDE (Answered/Total calls taken in to this In-Group):  $ANSWEREDpercent%\n";
	$MAIN.="ACR (Dropped/Answered):                                $DROP_ANSWEREDpercent%\n";

	$CSV_text1.="\n\"CUSTOM INDICATORS\"\n";
	$CSV_text1.="\"GDE (Answered/Total calls taken in to this In-Group):\",\"$ANSWEREDpercent%\"\n";
	$CSV_text1.="\"ACR (Dropped/Answered):\",\"$DROP_ANSWEREDpercent%\"\n";
	}

if ($DID!='Y')
	{
	$MAIN.="TMR1 (Answered within $Sanswer_sec_pct_rt_stat_one seconds/Answered):            $PCTanswer_sec_pct_rt_stat_one%\n";
	$MAIN.="TMR2 (Answered within $Sanswer_sec_pct_rt_stat_two seconds/Answered):            $PCTanswer_sec_pct_rt_stat_two%\n";
	$CSV_text1.="\"TMR1 (Answered within $Sanswer_sec_pct_rt_stat_one seconds/Answered):\",\"$PCTanswer_sec_pct_rt_stat_one%\"\n";
	$CSV_text1.="\"TMR2 (Answered within $Sanswer_sec_pct_rt_stat_two seconds/Answered):\",\"$PCTanswer_sec_pct_rt_stat_two%\"\n";
	}


# GET LIST OF ALL STATUSES and create SQL from human_answered statuses
$q=0;
$stmt = "SELECT status,status_name,human_answered,category from vicidial_statuses;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$statuses_to_print = mysql_num_rows($rslt);
$p=0;
while ($p < $statuses_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$status[$q] =			$row[0];
	$status_name[$q] =		$row[1];
	$human_answered[$q] =	$row[2];
	$category[$q] =			$row[3];
	$statname_list["$status[$q]"] = "$status_name[$q]";
	$statcat_list["$status[$q]"] = "$category[$q]";
	$q++;
	$p++;
	}
$stmt = "SELECT status,status_name,human_answered,category from vicidial_campaign_statuses;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$statuses_to_print = mysql_num_rows($rslt);
$p=0;
while ($p < $statuses_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$status[$q] =			$row[0];
	$status_name[$q] =		$row[1];
	$human_answered[$q] =	$row[2];
	$category[$q] =			$row[3];
	$statname_list["$status[$q]"] = "$status_name[$q]";
	$statcat_list["$status[$q]"] = "$category[$q]";
	$q++;
	$p++;
	}

##############################
#########  CALL QUEUE STATS
$MAIN.="\n";
$MAIN.="---------- QUEUE STATS\n";

$CSV_text1.="\n\"QUEUE STATS\"\n";

$stmt="select count(*),sum(queue_seconds) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id IN($group_SQL) and (queue_seconds > 0);";
if ($DID=='Y')
	{
	$stmt="select count(*),sum(queue_seconds) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and (queue_seconds > 0) and uniqueid IN($unid_SQL);";
	}
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$row=mysql_fetch_row($rslt);

$QUEUEcalls =	sprintf("%10s", $row[0]);
if ( ($QUEUEcalls < 1) or ($TOTALcalls < 1) )
	{$QUEUEpercent = '0';}
else
	{
	$QUEUEpercent = (($QUEUEcalls / $TOTALcalls) * 100);
	$QUEUEpercent = round($QUEUEpercent, 0);
	}

if ( ($row[0] < 1) or ($row[1] < 1) )
	{$average_queue_seconds = '         0';}
else
	{
	$average_queue_seconds = ($row[1] / $row[0]);
	$average_queue_seconds = round($average_queue_seconds, 2);
	$average_queue_seconds = sprintf("%10.2f", $average_queue_seconds);
	}

if ( ($TOTALcalls < 1) or ($row[1] < 1) )
	{$average_total_queue_seconds = '         0';}
else
	{
	$average_total_queue_seconds = ($row[1] / $TOTALcalls);
	$average_total_queue_seconds = round($average_total_queue_seconds, 2);
	$average_total_queue_seconds = sprintf("%10.2f", $average_total_queue_seconds);
	}

if ($EMAIL=='Y')
	{
	$MAIN.="Total Emails That entered Queue:               $QUEUEcalls  $QUEUEpercent%\n";
	$MAIN.="Average QUEUE Length for queue emails:         $average_queue_seconds seconds\n";
	$MAIN.="Average QUEUE Length across all emails:        $average_total_queue_seconds seconds\n";

	$CSV_text1.="\"Total Emails That entered Queue:\",\"$QUEUEcalls\",\"$QUEUEpercent%\"\n";
	$CSV_text1.="\"Average QUEUE Length for queue emails:\",\"$average_queue_seconds seconds\"\n";
	$CSV_text1.="\"Average QUEUE Length across all emails:\",\"$average_total_queue_seconds seconds\"\n";
	}
else 
	{
	$MAIN.="Total Calls That entered Queue:               $QUEUEcalls  $QUEUEpercent%\n";
	$MAIN.="Average QUEUE Length for queue calls:         $average_queue_seconds seconds\n";
	$MAIN.="Average QUEUE Length across all calls:        $average_total_queue_seconds seconds\n";

	$CSV_text1.="\"Total Calls That entered Queue:\",\"$QUEUEcalls\",\"$QUEUEpercent%\"\n";
	$CSV_text1.="\"Average QUEUE Length for queue calls:\",\"$average_queue_seconds seconds\"\n";
	$CSV_text1.="\"Average QUEUE Length across all calls:\",\"$average_total_queue_seconds seconds\"\n";
	}

if ($EMAIL=='Y') {
	$rpt_type_verbiage='EMAIL';
	$rpt_type_verbiages='EMAILS';
} else {
	$rpt_type_verbiage='CALL ';
	$rpt_type_verbiages='CALLS ';
}

##############################
#########  CALL HOLD TIME BREAKDOWN IN SECONDS

$TOTALcalls = 0;

$ASCII_text="\n";
$ASCII_text.="---------- $rpt_type_verbiage HOLD TIME BREAKDOWN IN SECONDS       <a href=\"$PHP_SELF?DB=$DB&DID=$DID&query_date=$query_date&end_date=$end_date$groupQS&shift=$shift&SUBMIT=$SUBMIT&file_download=2\">DOWNLOAD</a>\n";
$ASCII_text.="+-------------------------------------------------------------------------------------------+------------+\n";
$ASCII_text.="|     0     5    10    15    20    25    30    35    40    45    50    55    60    90   +90 | TOTAL      |\n";
$ASCII_text.="+-------------------------------------------------------------------------------------------+------------+\n";

$CSV_text2.="\n\"$rpt_type_verbiage HOLD TIME BREAKDOWN IN SECONDS\"\n";
$CSV_text2.="\"\",\"0\",\"5\",\"10\",\"15\",\"20\",\"25\",\"30\",\"35\",\"40\",\"45\",\"50\",\"55\",\"60\",\"90\",\"+90\",\"TOTAL\"\n";


$stmt="select count(*),queue_seconds from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and  campaign_id IN($group_SQL) group by queue_seconds;";
if ($DID=='Y')
	{
	$stmt="select count(*),queue_seconds from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) group by queue_seconds;";
	}
$rslt=mysql_query($stmt, $link);
if ($DB) {$ASCII_text.="$stmt\n";}
$reasons_to_print = mysql_num_rows($rslt);
$i=0;
$hd_5=0; $hd10=0; $hd15=0; $hd20=0; $hd25=0; $hd30=0; $hd35=0; $hd40=0; $hd45=0; $hd50=0; $hd55=0; $hd60=0; $hd90=0; $hd99=0;

while ($i < $reasons_to_print)
	{
	$row=mysql_fetch_row($rslt);

	$TOTALcalls = ($TOTALcalls + $row[0]);

	if ($row[1] == 0) {$hd_0 = ($hd_0 + $row[0]);}
	if ( ($row[1] > 0) and ($row[1] <= 5) ) {$hd_5 = ($hd_5 + $row[0]);}
	if ( ($row[1] > 5) and ($row[1] <= 10) ) {$hd10 = ($hd10 + $row[0]);}
	if ( ($row[1] > 10) and ($row[1] <= 15) ) {$hd15 = ($hd15 + $row[0]);}
	if ( ($row[1] > 15) and ($row[1] <= 20) ) {$hd20 = ($hd20 + $row[0]);}
	if ( ($row[1] > 20) and ($row[1] <= 25) ) {$hd25 = ($hd25 + $row[0]);}
	if ( ($row[1] > 25) and ($row[1] <= 30) ) {$hd30 = ($hd30 + $row[0]);}
	if ( ($row[1] > 30) and ($row[1] <= 35) ) {$hd35 = ($hd35 + $row[0]);}
	if ( ($row[1] > 35) and ($row[1] <= 40) ) {$hd40 = ($hd40 + $row[0]);}
	if ( ($row[1] > 40) and ($row[1] <= 45) ) {$hd45 = ($hd45 + $row[0]);}
	if ( ($row[1] > 45) and ($row[1] <= 50) ) {$hd50 = ($hd50 + $row[0]);}
	if ( ($row[1] > 50) and ($row[1] <= 55) ) {$hd55 = ($hd55 + $row[0]);}
	if ( ($row[1] > 55) and ($row[1] <= 60) ) {$hd60 = ($hd60 + $row[0]);}
	if ( ($row[1] > 60) and ($row[1] <= 90) ) {$hd90 = ($hd90 + $row[0]);}
	if ($row[1] > 90) {$hd99 = ($hd99 + $row[0]);}
	$i++;
	}

$hd_0 =	sprintf("%5s", $hd_0);
$hd_5 =	sprintf("%5s", $hd_5);
$hd10 =	sprintf("%5s", $hd10);
$hd15 =	sprintf("%5s", $hd15);
$hd20 =	sprintf("%5s", $hd20);
$hd25 =	sprintf("%5s", $hd25);
$hd30 =	sprintf("%5s", $hd30);
$hd35 =	sprintf("%5s", $hd35);
$hd40 =	sprintf("%5s", $hd40);
$hd45 =	sprintf("%5s", $hd45);
$hd50 =	sprintf("%5s", $hd50);
$hd55 =	sprintf("%5s", $hd55);
$hd60 =	sprintf("%5s", $hd60);
$hd90 =	sprintf("%5s", $hd90);
$hd99 =	sprintf("%5s", $hd99);

$TOTALcalls =		sprintf("%10s", $TOTALcalls);

$ASCII_text.="| $hd_0 $hd_5 $hd10 $hd15 $hd20 $hd25 $hd30 $hd35 $hd40 $hd45 $hd50 $hd55 $hd60 $hd90 $hd99 | $TOTALcalls |\n";
$ASCII_text.="+-------------------------------------------------------------------------------------------+------------+\n";

$CSV_text2.="\"\",\"$hd_0\",\"$hd_5\",\"$hd10\",\"$hd15\",\"$hd20\",\"$hd25\",\"$hd30\",\"$hd35\",\"$hd40\",\"$hd45\",\"$hd50\",\"$hd55\",\"$hd60\",\"$hd90\",\"$hd99\",\"$TOTALcalls\"\n";


if ($report_display_type=="HTML") {
	$stmt="select count(*),round(queue_seconds) as rd_sec from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id IN($group_SQL) group by rd_sec order by rd_sec asc;";
	$ms_stmt="select queue_seconds from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id IN($group_SQL) order by queue_seconds desc limit 1;"; 
	$mc_stmt="select count(*) as ct from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and  campaign_id IN($group_SQL) group by queue_seconds order by ct desc limit 1;";
	if ($DID=='Y')
		{
		$stmt="select count(*),round(queue_seconds) as rd_sec from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) group by rd_sec;";
		$ms_stmt="select queue_seconds from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) order by queue_seconds desc limit 1;"; 
		$mc_stmt="select count(*) as ct from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) group by queue_seconds order by ct desc limit 1;";
		}
	if ($DB) {$GRAPH_text.=$stmt."\n";}
	$ms_rslt=mysql_query($ms_stmt, $link);
	$ms_row=mysql_fetch_row($ms_rslt);
	$max_seconds=$ms_row[0];
	if ($max_seconds>90) {$max_seconds=91;}
	for ($i=0; $i<=$max_seconds; $i++) {
		$sec_ary[$i]=0;
	}

	$mc_rslt=mysql_query($mc_stmt, $link);
	$mc_row=mysql_fetch_row($mc_rslt);
	$max_calls=$ms_row[0];
	if ($max_calls<=10) {
		while ($maxcalls%5!=0) {
			$maxcalls++;
		}
	} else if ($max_calls<=100) {
		while ($maxcalls%10!=0) {
			$maxcalls++;
		}
	} else if ($max_calls<=1000) {
		while ($maxcalls%50!=0) {
			$maxcalls++;
		}
	} else {
		while ($maxcalls%500!=0) {
			$maxcalls++;
		}
	}
	$rslt=mysql_query($stmt, $link);
	
	$GRAPH_text="<div id=\"QueueCanvas\" style=\"overflow: auto; position:relative;height:300px;width:1000px;\"></div>\n";
	$GRAPH_text.="<script type=\"text/javascript\">\n";
	$GRAPH_text.="var g = new line_graph(6);\n";
	$over90=0;
	while ($row=mysql_fetch_row($rslt)) {
		if ($row[1]<=90) {
			$sec_ary[$row[1]]=$row[0];
		} else {
			$over90+=$row[0];
		}
	}
	$sec_ary[91]=$over90;
	for ($i=0; $i<=$max_seconds; $i++) {
		if ($i<=90) {
			if ($i%5==0) {$int=$i;} else {$int="";}
			$GRAPH_text.="g.add('$int', ".$sec_ary[$i].");\n";
		} else {
			$GRAPH_text.="g.add('90+', ".$sec_ary[91].");\n";
		}
	}
	$GRAPH_text.="g.render(\"QueueCanvas\", \"Queue Seconds\");\n";
	$GRAPH_text.="</script>";
	$MAIN.=$GRAPH_text;
	}
else 
	{
	$MAIN.=$ASCII_text;
	}


##############################
#########  CALL DROP TIME BREAKDOWN IN SECONDS

$BDdropCALLS = 0;

$ASCII_text="\n";
$ASCII_text.="---------- $rpt_type_verbiage DROP TIME BREAKDOWN IN SECONDS\n";
$ASCII_text.="+-------------------------------------------------------------------------------------------+------------+\n";
$ASCII_text.="|     0     5    10    15    20    25    30    35    40    45    50    55    60    90   +90 | TOTAL      |\n";
$ASCII_text.="+-------------------------------------------------------------------------------------------+------------+\n";

$CSV_text2.="\n\"$rpt_type_verbiage DROP TIME BREAKDOWN IN SECONDS\"\n";
$CSV_text2.="\"\",\"0\",\"5\",\"10\",\"15\",\"20\",\"25\",\"30\",\"35\",\"40\",\"45\",\"50\",\"55\",\"60\",\"90\",\"+90\",\"TOTAL\"\n";

$stmt="select count(*),queue_seconds from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and  campaign_id IN($group_SQL) and status IN('DROP','XDROP') group by queue_seconds;";
if ($DID=='Y')
	{
	$stmt="select count(*),queue_seconds from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) and status IN('DROP','XDROP') group by queue_seconds;";
	}
$rslt=mysql_query($stmt, $link);
if ($DB) {$ASCII_text.="$stmt\n";}
$reasons_to_print = mysql_num_rows($rslt);
$i=0;
$dd_0=0; $dd_5=0; $dd10=0; $dd15=0; $dd20=0; $dd25=0; $dd30=0; $dd35=0; $dd40=0; $dd45=0; $dd50=0; $dd55=0; $dd60=0; $dd90=0; $dd99=0;

while ($i < $reasons_to_print)
	{
	$row=mysql_fetch_row($rslt);

	$BDdropCALLS = ($BDdropCALLS + $row[0]);

	if ($row[1] == 0) {$dd_0 = ($dd_0 + $row[0]);}
	if ( ($row[1] > 0) and ($row[1] <= 5) ) {$dd_5 = ($dd_5 + $row[0]);}
	if ( ($row[1] > 5) and ($row[1] <= 10) ) {$dd10 = ($dd10 + $row[0]);}
	if ( ($row[1] > 10) and ($row[1] <= 15) ) {$dd15 = ($dd15 + $row[0]);}
	if ( ($row[1] > 15) and ($row[1] <= 20) ) {$dd20 = ($dd20 + $row[0]);}
	if ( ($row[1] > 20) and ($row[1] <= 25) ) {$dd25 = ($dd25 + $row[0]);}
	if ( ($row[1] > 25) and ($row[1] <= 30) ) {$dd30 = ($dd30 + $row[0]);}
	if ( ($row[1] > 30) and ($row[1] <= 35) ) {$dd35 = ($dd35 + $row[0]);}
	if ( ($row[1] > 35) and ($row[1] <= 40) ) {$dd40 = ($dd40 + $row[0]);}
	if ( ($row[1] > 40) and ($row[1] <= 45) ) {$dd45 = ($dd45 + $row[0]);}
	if ( ($row[1] > 45) and ($row[1] <= 50) ) {$dd50 = ($dd50 + $row[0]);}
	if ( ($row[1] > 50) and ($row[1] <= 55) ) {$dd55 = ($dd55 + $row[0]);}
	if ( ($row[1] > 55) and ($row[1] <= 60) ) {$dd60 = ($dd60 + $row[0]);}
	if ( ($row[1] > 60) and ($row[1] <= 90) ) {$dd90 = ($dd90 + $row[0]);}
	if ($row[1] > 90) {$dd99 = ($dd99 + $row[0]);}
	$i++;
	}

$dd_0 =	sprintf("%5s", $dd_0);
$dd_5 =	sprintf("%5s", $dd_5);
$dd10 =	sprintf("%5s", $dd10);
$dd15 =	sprintf("%5s", $dd15);
$dd20 =	sprintf("%5s", $dd20);
$dd25 =	sprintf("%5s", $dd25);
$dd30 =	sprintf("%5s", $dd30);
$dd35 =	sprintf("%5s", $dd35);
$dd40 =	sprintf("%5s", $dd40);
$dd45 =	sprintf("%5s", $dd45);
$dd50 =	sprintf("%5s", $dd50);
$dd55 =	sprintf("%5s", $dd55);
$dd60 =	sprintf("%5s", $dd60);
$dd90 =	sprintf("%5s", $dd90);
$dd99 =	sprintf("%5s", $dd99);

$BDdropCALLS =		sprintf("%10s", $BDdropCALLS);

$ASCII_text.="| $dd_0 $dd_5 $dd10 $dd15 $dd20 $dd25 $dd30 $dd35 $dd40 $dd45 $dd50 $dd55 $dd60 $dd90 $dd99 | $BDdropCALLS |\n";
$ASCII_text.="+-------------------------------------------------------------------------------------------+------------+\n";


$CSV_text2.="\"\",\"$dd_0\",\"$dd_5\",\"$dd10\",\"$dd15\",\"$dd20\",\"$dd25\",\"$dd30\",\"$dd35\",\"$dd40\",\"$dd45\",\"$dd50\",\"$dd55\",\"$dd60\",\"$dd90\",\"$dd99\",\"$BDdropCALLS\"\n";

if ($report_display_type=="HTML") {
	$stmt="select count(*),round(queue_seconds) as rd_sec from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id IN($group_SQL) and status IN('DROP','XDROP') group by rd_sec order by rd_sec asc;";
	$ms_stmt="select queue_seconds from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id IN($group_SQL) and status IN('DROP','XDROP') order by queue_seconds desc limit 1;"; 
	$mc_stmt="select count(*) as ct from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and  campaign_id IN($group_SQL) and status IN('DROP','XDROP') group by queue_seconds order by ct desc limit 1;";
	if ($DID=='Y')
		{
		$stmt="select count(*),round(queue_seconds) as rd_sec from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) and status IN('DROP','XDROP') group by rd_sec;";
		$ms_stmt="select queue_seconds from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) and status IN('DROP','XDROP') order by queue_seconds desc limit 1;"; 
		$mc_stmt="select count(*) as ct from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) and status IN('DROP','XDROP') group by queue_seconds order by ct desc limit 1;";
		}
	if ($DB) {$GRAPH_text.=$stmt."\n";}
	$ms_rslt=mysql_query($ms_stmt, $link);
	$ms_row=mysql_fetch_row($ms_rslt);
	$max_seconds=$ms_row[0];
	if ($max_seconds>90) {$max_seconds=91;}
	for ($i=0; $i<=$max_seconds; $i++) {
		$sec_ary[$i]=0;
	}

	$mc_rslt=mysql_query($mc_stmt, $link);
	$mc_row=mysql_fetch_row($mc_rslt);
	$max_calls=$ms_row[0];
	if ($max_calls<=10) {
		while ($maxcalls%5!=0) {
			$maxcalls++;
		}
	} else if ($max_calls<=100) {
		while ($maxcalls%10!=0) {
			$maxcalls++;
		}
	} else if ($max_calls<=1000) {
		while ($maxcalls%50!=0) {
			$maxcalls++;
		}
	} else {
		while ($maxcalls%500!=0) {
			$maxcalls++;
		}
	}
	$rslt=mysql_query($stmt, $link);
	
	$GRAPH_text="<div id=\"DropCanvas\" style=\"overflow: auto; position:relative;height:300px;width:1000px;\"></div>\n";
	$GRAPH_text.="<script type=\"text/javascript\">\n";
	$GRAPH_text.="var g = new line_graph(6);\n";
	$over90=0;
	while ($row=mysql_fetch_row($rslt)) {
		if ($row[1]<=90) {
			$sec_ary[$row[1]]=$row[0];
		} else {
			$over90+=$row[0];
		}
	}
	$sec_ary[91]=$over90;
	for ($i=0; $i<=$max_seconds; $i++) {
		if ($i<=90) {
			if ($i%5==0) {$int=$i;} else {$int="";}
			$GRAPH_text.="g.add('$int', ".$sec_ary[$i].");\n";
		} else {
			$GRAPH_text.="g.add('90+', ".$sec_ary[91].");\n";
		}
	}
	$GRAPH_text.="g.render(\"DropCanvas\", \"Drop Time Breakdown in Seconds\");\n";
	$GRAPH_text.="</script>";
	$MAIN.=$GRAPH_text;
	}
else
	{
	$MAIN.=$ASCII_text;
	}



##############################
#########  CALL ANSWERED TIME AND PERCENT BREAKDOWN IN SECONDS

$BDansweredCALLS = 0;

$ASCII_text="\n";
$ASCII_text.="           $rpt_type_verbiage ANSWERED TIME AND PERCENT BREAKDOWN IN SECONDS\n";
$ASCII_text.="          +-------------------------------------------------------------------------------------------+------------+\n";
$ASCII_text.="          |     0     5    10    15    20    25    30    35    40    45    50    55    60    90   +90 | TOTAL      |\n";
$ASCII_text.="----------+-------------------------------------------------------------------------------------------+------------+\n";

$CSV_text2.="\n\"$rpt_type_verbiage ANSWERED TIME AND PERCENT BREAKDOWN IN SECONDS\"\n";
$CSV_text2.="\"\",\"0\",\"5\",\"10\",\"15\",\"20\",\"25\",\"30\",\"35\",\"40\",\"45\",\"50\",\"55\",\"60\",\"90\",\"+90\",\"TOTAL\"\n";

$stmt="select count(*),queue_seconds from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and  campaign_id IN($group_SQL) and status NOT IN('DROP','XDROP','HXFER','QVMAIL','HOLDTO','LIVE','QUEUE','TIMEOT','AFTHRS','NANQUE','INBND','MAXCAL') group by queue_seconds;";
if ($DID=='Y')
	{
	$stmt="select count(*),queue_seconds from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) and status NOT IN('DROP','XDROP','HXFER','QVMAIL','HOLDTO','LIVE','QUEUE','TIMEOT','AFTHRS','NANQUE','INBND','MAXCAL') group by queue_seconds;";
	}
$rslt=mysql_query($stmt, $link);
if ($DB) {$ASCII_text.="$stmt\n";}
$reasons_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $reasons_to_print)
	{
	$row=mysql_fetch_row($rslt);

	$BDansweredCALLS = ($BDansweredCALLS + $row[0]);
	
	### Get interval totals
	if ($row[1] == 0) {$ad_0 = ($ad_0 + $row[0]);}
	if ( ($row[1] > 0) and ($row[1] <= 5) ) {$ad_5 = ($ad_5 + $row[0]);}
	if ( ($row[1] > 5) and ($row[1] <= 10) ) {$ad10 = ($ad10 + $row[0]);}
	if ( ($row[1] > 10) and ($row[1] <= 15) ) {$ad15 = ($ad15 + $row[0]);}
	if ( ($row[1] > 15) and ($row[1] <= 20) ) {$ad20 = ($ad20 + $row[0]);}
	if ( ($row[1] > 20) and ($row[1] <= 25) ) {$ad25 = ($ad25 + $row[0]);}
	if ( ($row[1] > 25) and ($row[1] <= 30) ) {$ad30 = ($ad30 + $row[0]);}
	if ( ($row[1] > 30) and ($row[1] <= 35) ) {$ad35 = ($ad35 + $row[0]);}
	if ( ($row[1] > 35) and ($row[1] <= 40) ) {$ad40 = ($ad40 + $row[0]);}
	if ( ($row[1] > 40) and ($row[1] <= 45) ) {$ad45 = ($ad45 + $row[0]);}
	if ( ($row[1] > 45) and ($row[1] <= 50) ) {$ad50 = ($ad50 + $row[0]);}
	if ( ($row[1] > 50) and ($row[1] <= 55) ) {$ad55 = ($ad55 + $row[0]);}
	if ( ($row[1] > 55) and ($row[1] <= 60) ) {$ad60 = ($ad60 + $row[0]);}
	if ( ($row[1] > 60) and ($row[1] <= 90) ) {$ad90 = ($ad90 + $row[0]);}
	if ($row[1] > 90) {$ad99 = ($ad99 + $row[0]);}
	$i++;
	}

### Calculate cumulative totals
$Cad_0 =$ad_0;
$Cad_5 =($Cad_0 + $ad_5);
$Cad10 =($Cad_5 + $ad10);
$Cad15 =($Cad10 + $ad15);
$Cad20 =($Cad15 + $ad20);
$Cad25 =($Cad20 + $ad25);
$Cad30 =($Cad25 + $ad30);
$Cad35 =($Cad30 + $ad35);
$Cad40 =($Cad35 + $ad40);
$Cad45 =($Cad40 + $ad45);
$Cad50 =($Cad45 + $ad50);
$Cad55 =($Cad50 + $ad55);
$Cad60 =($Cad55 + $ad60);
$Cad90 =($Cad60 + $ad90);
$Cad99 =($Cad90 + $ad99);

### Calculate interval percentages
$pad_0=0; $pad_5=0; $pad10=0; $pad15=0; $pad20=0; $pad25=0; $pad30=0; $pad35=0; $pad40=0; $pad45=0; $pad50=0; $pad55=0; $pad60=0; $pad90=0; $pad99=0; 
$pCad_0=0; $pCad_5=0; $pCad10=0; $pCad15=0; $pCad20=0; $pCad25=0; $pCad30=0; $pCad35=0; $pCad40=0; $pCad45=0; $pCad50=0; $pCad55=0; $pCad60=0; $pCad90=0; $pCad99=0; 
if ( ($BDansweredCALLS > 0) and ($TOTALcalls > 0) )
	{
	if ($ad_0 > 0) {$pad_0 = (($ad_0 / $TOTALcalls) * 100);	$pad_0 = round($pad_0, 0);}
	if ($ad_5 > 0) {$pad_5 = (($ad_5 / $TOTALcalls) * 100);	$pad_5 = round($pad_5, 0);}
	if ($ad10 > 0) {$pad10 = (($ad10 / $TOTALcalls) * 100);	$pad10 = round($pad10, 0);}
	if ($ad15 > 0) {$pad15 = (($ad15 / $TOTALcalls) * 100);	$pad15 = round($pad15, 0);}
	if ($ad20 > 0) {$pad20 = (($ad20 / $TOTALcalls) * 100);	$pad20 = round($pad20, 0);}
	if ($ad25 > 0) {$pad25 = (($ad25 / $TOTALcalls) * 100);	$pad25 = round($pad25, 0);}
	if ($ad30 > 0) {$pad30 = (($ad30 / $TOTALcalls) * 100);	$pad30 = round($pad30, 0);}
	if ($ad35 > 0) {$pad35 = (($ad35 / $TOTALcalls) * 100);	$pad35 = round($pad35, 0);}
	if ($ad40 > 0) {$pad40 = (($ad40 / $TOTALcalls) * 100);	$pad40 = round($pad40, 0);}
	if ($ad45 > 0) {$pad45 = (($ad45 / $TOTALcalls) * 100);	$pad45 = round($pad45, 0);}
	if ($ad50 > 0) {$pad50 = (($ad50 / $TOTALcalls) * 100);	$pad50 = round($pad50, 0);}
	if ($ad55 > 0) {$pad55 = (($ad55 / $TOTALcalls) * 100);	$pad55 = round($pad55, 0);}
	if ($ad60 > 0) {$pad60 = (($ad60 / $TOTALcalls) * 100);	$pad60 = round($pad60, 0);}
	if ($ad90 > 0) {$pad90 = (($ad90 / $TOTALcalls) * 100);	$pad90 = round($pad90, 0);}
	if ($ad99 > 0) {$pad99 = (($ad99 / $TOTALcalls) * 100);	$pad99 = round($pad99, 0);}

	if ($Cad_0 > 0) {$pCad_0 = (($Cad_0 / $TOTALcalls) * 100);	$pCad_0 = round($pCad_0, 0);}
	if ($Cad_5 > 0) {$pCad_5 = (($Cad_5 / $TOTALcalls) * 100);	$pCad_5 = round($pCad_5, 0);}
	if ($Cad10 > 0) {$pCad10 = (($Cad10 / $TOTALcalls) * 100);	$pCad10 = round($pCad10, 0);}
	if ($Cad15 > 0) {$pCad15 = (($Cad15 / $TOTALcalls) * 100);	$pCad15 = round($pCad15, 0);}
	if ($Cad20 > 0) {$pCad20 = (($Cad20 / $TOTALcalls) * 100);	$pCad20 = round($pCad20, 0);}
	if ($Cad25 > 0) {$pCad25 = (($Cad25 / $TOTALcalls) * 100);	$pCad25 = round($pCad25, 0);}
	if ($Cad30 > 0) {$pCad30 = (($Cad30 / $TOTALcalls) * 100);	$pCad30 = round($pCad30, 0);}
	if ($Cad35 > 0) {$pCad35 = (($Cad35 / $TOTALcalls) * 100);	$pCad35 = round($pCad35, 0);}
	if ($Cad40 > 0) {$pCad40 = (($Cad40 / $TOTALcalls) * 100);	$pCad40 = round($pCad40, 0);}
	if ($Cad45 > 0) {$pCad45 = (($Cad45 / $TOTALcalls) * 100);	$pCad45 = round($pCad45, 0);}
	if ($Cad50 > 0) {$pCad50 = (($Cad50 / $TOTALcalls) * 100);	$pCad50 = round($pCad50, 0);}
	if ($Cad55 > 0) {$pCad55 = (($Cad55 / $TOTALcalls) * 100);	$pCad55 = round($pCad55, 0);}
	if ($Cad60 > 0) {$pCad60 = (($Cad60 / $TOTALcalls) * 100);	$pCad60 = round($pCad60, 0);}
	if ($Cad90 > 0) {$pCad90 = (($Cad90 / $TOTALcalls) * 100);	$pCad90 = round($pCad90, 0);}
	if ($Cad99 > 0) {$pCad99 = (($Cad99 / $TOTALcalls) * 100);	$pCad99 = round($pCad99, 0);}

	if ($Cad_0 > 0) {$ApCad_0 = (($Cad_0 / $BDansweredCALLS) * 100);	$ApCad_0 = round($ApCad_0, 0);}
	if ($Cad_5 > 0) {$ApCad_5 = (($Cad_5 / $BDansweredCALLS) * 100);	$ApCad_5 = round($ApCad_5, 0);}
	if ($Cad10 > 0) {$ApCad10 = (($Cad10 / $BDansweredCALLS) * 100);	$ApCad10 = round($ApCad10, 0);}
	if ($Cad15 > 0) {$ApCad15 = (($Cad15 / $BDansweredCALLS) * 100);	$ApCad15 = round($ApCad15, 0);}
	if ($Cad20 > 0) {$ApCad20 = (($Cad20 / $BDansweredCALLS) * 100);	$ApCad20 = round($ApCad20, 0);}
	if ($Cad25 > 0) {$ApCad25 = (($Cad25 / $BDansweredCALLS) * 100);	$ApCad25 = round($ApCad25, 0);}
	if ($Cad30 > 0) {$ApCad30 = (($Cad30 / $BDansweredCALLS) * 100);	$ApCad30 = round($ApCad30, 0);}
	if ($Cad35 > 0) {$ApCad35 = (($Cad35 / $BDansweredCALLS) * 100);	$ApCad35 = round($ApCad35, 0);}
	if ($Cad40 > 0) {$ApCad40 = (($Cad40 / $BDansweredCALLS) * 100);	$ApCad40 = round($ApCad40, 0);}
	if ($Cad45 > 0) {$ApCad45 = (($Cad45 / $BDansweredCALLS) * 100);	$ApCad45 = round($ApCad45, 0);}
	if ($Cad50 > 0) {$ApCad50 = (($Cad50 / $BDansweredCALLS) * 100);	$ApCad50 = round($ApCad50, 0);}
	if ($Cad55 > 0) {$ApCad55 = (($Cad55 / $BDansweredCALLS) * 100);	$ApCad55 = round($ApCad55, 0);}
	if ($Cad60 > 0) {$ApCad60 = (($Cad60 / $BDansweredCALLS) * 100);	$ApCad60 = round($ApCad60, 0);}
	if ($Cad90 > 0) {$ApCad90 = (($Cad90 / $BDansweredCALLS) * 100);	$ApCad90 = round($ApCad90, 0);}
	if ($Cad99 > 0) {$ApCad99 = (($Cad99 / $BDansweredCALLS) * 100);	$ApCad99 = round($ApCad99, 0);}
	}

### Format variables
$ad_0 = sprintf("%5s", $ad_0);
$ad_5 = sprintf("%5s", $ad_5);
$ad10 = sprintf("%5s", $ad10);
$ad15 = sprintf("%5s", $ad15);
$ad20 = sprintf("%5s", $ad20);
$ad25 = sprintf("%5s", $ad25);
$ad30 = sprintf("%5s", $ad30);
$ad35 = sprintf("%5s", $ad35);
$ad40 = sprintf("%5s", $ad40);
$ad45 = sprintf("%5s", $ad45);
$ad50 = sprintf("%5s", $ad50);
$ad55 = sprintf("%5s", $ad55);
$ad60 = sprintf("%5s", $ad60);
$ad90 = sprintf("%5s", $ad90);
$ad99 = sprintf("%5s", $ad99);
$Cad_0 = sprintf("%5s", $Cad_0);
$Cad_5 = sprintf("%5s", $Cad_5);
$Cad10 = sprintf("%5s", $Cad10);
$Cad15 = sprintf("%5s", $Cad15);
$Cad20 = sprintf("%5s", $Cad20);
$Cad25 = sprintf("%5s", $Cad25);
$Cad30 = sprintf("%5s", $Cad30);
$Cad35 = sprintf("%5s", $Cad35);
$Cad40 = sprintf("%5s", $Cad40);
$Cad45 = sprintf("%5s", $Cad45);
$Cad50 = sprintf("%5s", $Cad50);
$Cad55 = sprintf("%5s", $Cad55);
$Cad60 = sprintf("%5s", $Cad60);
$Cad90 = sprintf("%5s", $Cad90);
$Cad99 = sprintf("%5s", $Cad99);
$pad_0 = sprintf("%4s", $pad_0) . '%';
$pad_5 = sprintf("%4s", $pad_5) . '%';
$pad10 = sprintf("%4s", $pad10) . '%';
$pad15 = sprintf("%4s", $pad15) . '%';
$pad20 = sprintf("%4s", $pad20) . '%';
$pad25 = sprintf("%4s", $pad25) . '%';
$pad30 = sprintf("%4s", $pad30) . '%';
$pad35 = sprintf("%4s", $pad35) . '%';
$pad40 = sprintf("%4s", $pad40) . '%';
$pad45 = sprintf("%4s", $pad45) . '%';
$pad50 = sprintf("%4s", $pad50) . '%';
$pad55 = sprintf("%4s", $pad55) . '%';
$pad60 = sprintf("%4s", $pad60) . '%';
$pad90 = sprintf("%4s", $pad90) . '%';
$pad99 = sprintf("%4s", $pad99) . '%';
$pCad_0 = sprintf("%4s", $pCad_0) . '%';
$pCad_5 = sprintf("%4s", $pCad_5) . '%';
$pCad10 = sprintf("%4s", $pCad10) . '%';
$pCad15 = sprintf("%4s", $pCad15) . '%';
$pCad20 = sprintf("%4s", $pCad20) . '%';
$pCad25 = sprintf("%4s", $pCad25) . '%';
$pCad30 = sprintf("%4s", $pCad30) . '%';
$pCad35 = sprintf("%4s", $pCad35) . '%';
$pCad40 = sprintf("%4s", $pCad40) . '%';
$pCad45 = sprintf("%4s", $pCad45) . '%';
$pCad50 = sprintf("%4s", $pCad50) . '%';
$pCad55 = sprintf("%4s", $pCad55) . '%';
$pCad60 = sprintf("%4s", $pCad60) . '%';
$pCad90 = sprintf("%4s", $pCad90) . '%';
$pCad99 = sprintf("%4s", $pCad99) . '%';
$ApCad_0 = sprintf("%4s", $ApCad_0) . '%';
$ApCad_5 = sprintf("%4s", $ApCad_5) . '%';
$ApCad10 = sprintf("%4s", $ApCad10) . '%';
$ApCad15 = sprintf("%4s", $ApCad15) . '%';
$ApCad20 = sprintf("%4s", $ApCad20) . '%';
$ApCad25 = sprintf("%4s", $ApCad25) . '%';
$ApCad30 = sprintf("%4s", $ApCad30) . '%';
$ApCad35 = sprintf("%4s", $ApCad35) . '%';
$ApCad40 = sprintf("%4s", $ApCad40) . '%';
$ApCad45 = sprintf("%4s", $ApCad45) . '%';
$ApCad50 = sprintf("%4s", $ApCad50) . '%';
$ApCad55 = sprintf("%4s", $ApCad55) . '%';
$ApCad60 = sprintf("%4s", $ApCad60) . '%';
$ApCad90 = sprintf("%4s", $ApCad90) . '%';
$ApCad99 = sprintf("%4s", $ApCad99) . '%';

$BDansweredCALLS =		sprintf("%10s", $BDansweredCALLS);

### Format and output
$answeredTOTALs = "$ad_0 $ad_5 $ad10 $ad15 $ad20 $ad25 $ad30 $ad35 $ad40 $ad45 $ad50 $ad55 $ad60 $ad90 $ad99 | $BDansweredCALLS |";
$answeredCUMULATIVE = "$Cad_0 $Cad_5 $Cad10 $Cad15 $Cad20 $Cad25 $Cad30 $Cad35 $Cad40 $Cad45 $Cad50 $Cad55 $Cad60 $Cad90 $Cad99 | $BDansweredCALLS |";
$answeredINT_PERCENT = "$pad_0 $pad_5 $pad10 $pad15 $pad20 $pad25 $pad30 $pad35 $pad40 $pad45 $pad50 $pad55 $pad60 $pad90 $pad99 |            |";
$answeredCUM_PERCENT = "$pCad_0 $pCad_5 $pCad10 $pCad15 $pCad20 $pCad25 $pCad30 $pCad35 $pCad40 $pCad45 $pCad50 $pCad55 $pCad60 $pCad90 $pCad99 |            |";
$answeredCUM_ANS_PERCENT = "$ApCad_0 $ApCad_5 $ApCad10 $ApCad15 $ApCad20 $ApCad25 $ApCad30 $ApCad35 $ApCad40 $ApCad45 $ApCad50 $ApCad55 $ApCad60 $ApCad90 $ApCad99 |            |";
$ASCII_text.="INTERVAL  | $answeredTOTALs\n";
$ASCII_text.="INT %     | $answeredINT_PERCENT\n";
$ASCII_text.="CUMULATIVE| $answeredCUMULATIVE\n";
$ASCII_text.="CUM %     | $answeredCUM_PERCENT\n";
$ASCII_text.="CUM ANS % | $answeredCUM_ANS_PERCENT\n";
$ASCII_text.="----------+-------------------------------------------------------------------------------------------+------------+\n";


$CSV_text2.="\"INTERVAL\",\"$ad_0\",\"$ad_5\",\"$ad10\",\"$ad15\",\"$ad20\",\"$ad25\",\"$ad30\",\"$ad35\",\"$ad40\",\"$ad45\",\"$ad50\",\"$ad55\",\"$ad60\",\"$ad90\",\"$ad99\",\"$BDansweredCALLS\"\n";
$CSV_text2.="\"INT %\",\"$pad_0\",\"$pad_5\",\"$pad10\",\"$pad15\",\"$pad20\",\"$pad25\",\"$pad30\",\"$pad35\",\"$pad40\",\"$pad45\",\"$pad50\",\"$pad55\",\"$pad60\",\"$pad90\",\"$pad99\"\n";
$CSV_text2.="\"CUMULATIVE\",\"$Cad_0\",\"$Cad_5\",\"$Cad10\",\"$Cad15\",\"$Cad20\",\"$Cad25\",\"$Cad30\",\"$Cad35\",\"$Cad40\",\"$Cad45\",\"$Cad50\",\"$Cad55\",\"$Cad60\",\"$Cad90\",\"$Cad99\",\"$BDansweredCALLS\"\n";
$CSV_text2.="\"CUM %\",\"$pCad_0\",\"$pCad_5\",\"$pCad10\",\"$pCad15\",\"$pCad20\",\"$pCad25\",\"$pCad30\",\"$pCad35\",\"$pCad40\",\"$pCad45\",\"$pCad50\",\"$pCad55\",\"$pCad60\",\"$pCad90\",\"$pCad99\"\n";
$CSV_text2.="\"CUM ANS %\",\"$ApCad_0\",\"$ApCad_5\",\"$ApCad10\",\"$ApCad15\",\"$ApCad20\",\"$ApCad25\",\"$ApCad30\",\"$ApCad35\",\"$ApCad40\",\"$ApCad45\",\"$ApCad50\",\"$ApCad55\",\"$ApCad60\",\"$ApCad90\",\"$ApCad99\"\n";

if ($report_display_type=="HTML") {
	$stmt="select count(*),round(queue_seconds) as rd_sec from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id IN($group_SQL) and status NOT IN('DROP','XDROP','HXFER','QVMAIL','HOLDTO','LIVE','QUEUE','TIMEOT','AFTHRS','NANQUE','INBND','MAXCAL') group by rd_sec order by rd_sec asc;";
	$ms_stmt="select queue_seconds from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id IN($group_SQL) and status NOT IN('DROP','XDROP','HXFER','QVMAIL','HOLDTO','LIVE','QUEUE','TIMEOT','AFTHRS','NANQUE','INBND','MAXCAL') order by queue_seconds desc limit 1;"; 
	$mc_stmt="select count(*) as ct from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and  campaign_id IN($group_SQL) and status NOT IN('DROP','XDROP','HXFER','QVMAIL','HOLDTO','LIVE','QUEUE','TIMEOT','AFTHRS','NANQUE','INBND','MAXCAL') group by queue_seconds order by ct desc limit 1;";
	if ($DID=='Y')
		{
		$stmt="select count(*),round(queue_seconds) as rd_sec from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) and status NOT IN('DROP','XDROP','HXFER','QVMAIL','HOLDTO','LIVE','QUEUE','TIMEOT','AFTHRS','NANQUE','INBND','MAXCAL') group by rd_sec;";
		$ms_stmt="select queue_seconds from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) and status NOT IN('DROP','XDROP','HXFER','QVMAIL','HOLDTO','LIVE','QUEUE','TIMEOT','AFTHRS','NANQUE','INBND','MAXCAL') order by queue_seconds desc limit 1;"; 
		$mc_stmt="select count(*) as ct from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) and status NOT IN('DROP','XDROP','HXFER','QVMAIL','HOLDTO','LIVE','QUEUE','TIMEOT','AFTHRS','NANQUE','INBND','MAXCAL') group by queue_seconds order by ct desc limit 1;";
		}
	if ($DB) {$GRAPH_text.=$stmt."\n";}
	$ms_rslt=mysql_query($ms_stmt, $link);
	$ms_row=mysql_fetch_row($ms_rslt);
	$max_seconds=$ms_row[0];
	if ($max_seconds>90) {$max_seconds=91;}
	for ($i=0; $i<=$max_seconds; $i++) {
		$sec_ary[$i]=0;
	}

	$mc_rslt=mysql_query($mc_stmt, $link);
	$mc_row=mysql_fetch_row($mc_rslt);
	$max_calls=$ms_row[0];
	if ($max_calls<=10) {
		while ($maxcalls%5!=0) {
			$maxcalls++;
		}
	} else if ($max_calls<=100) {
		while ($maxcalls%10!=0) {
			$maxcalls++;
		}
	} else if ($max_calls<=1000) {
		while ($maxcalls%50!=0) {
			$maxcalls++;
		}
	} else {
		while ($maxcalls%500!=0) {
			$maxcalls++;
		}
	}
	$rslt=mysql_query($stmt, $link);
	
	$GRAPH_text="<div id=\"CallTimeCanvas\" style=\"overflow: auto; position:relative;height:300px;width:1000px;\"></div>\n";
	$GRAPH_text.="<script type=\"text/javascript\">\n";
	$GRAPH_text.="var g = new line_graph(6);\n";
	$over90=0;
	while ($row=mysql_fetch_row($rslt)) {
		if ($row[1]<=90) {
			$sec_ary[$row[1]]=$row[0];
		} else {
			$over90+=$row[0];
		}
	}
	$sec_ary[91]=$over90;
	for ($i=0; $i<=$max_seconds; $i++) {
		if ($i<=90) {
			if ($i%5==0) {$int=$i;} else {$int="";}
			$GRAPH_text.="g.add('$int', ".$sec_ary[$i].");\n";
		} else {
			$GRAPH_text.="g.add('90+', ".$sec_ary[91].");\n";
		}
	}
	$GRAPH_text.="g.render(\"CallTimeCanvas\", \"CALL ANSWERED TIME BREAKDOWN\");\n";
	$GRAPH_text.="</script>";
	$MAIN.=$GRAPH_text;
	}
else
	{
	$MAIN.=$ASCII_text;
	}

##############################
#########  CALL HANGUP REASON STATS

$TOTALcalls = 0;

$ASCII_text="\n";
$ASCII_text.="---------- $rpt_type_verbiage HANGUP REASON STATS       <a href=\"$PHP_SELF?DB=$DB&DID=$DID&query_date=$query_date&end_date=$end_date$groupQS&shift=$shift&SUBMIT=$SUBMIT&file_download=3\">DOWNLOAD</a>\n";
$ASCII_text.="+----------------------+------------+\n";
$ASCII_text.="| HANGUP REASON        | $rpt_type_verbiages      |\n";
$ASCII_text.="+----------------------+------------+\n";

$CSV_text3.="\n\"$rpt_type_verbiage HANGUP REASON STATS\"\n";
$CSV_text3.="\"HANGUP REASON\",\"$rpt_type_verbiages\"\n";

$stmt="select count(*),term_reason from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and  campaign_id IN($group_SQL) group by term_reason order by term_reason;";
if ($DID=='Y')
	{
	$stmt="select count(*),term_reason from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) group by term_reason order by term_reason;";
	}
$rslt=mysql_query($stmt, $link);
if ($DB) {$ASCII_text.="$stmt\n";}
$reasons_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $reasons_to_print)
	{
	$row=mysql_fetch_row($rslt);

	$TOTALcalls = ($TOTALcalls + $row[0]);

	$REASONcount =	sprintf("%10s", $row[0]);
	while(strlen($REASONcount)>10) {$REASONcount = substr("$REASONcount", 0, -1);}
	$reason =	sprintf("%-20s", $row[1]);while(strlen($reason)>20) {$reason = substr("$reason", 0, -1);}
#	if (ereg("NONE",$reason)) {$reason = 'NO ANSWER           ';}

	$ASCII_text.="| $reason | $REASONcount |\n";
	$CSV_text3.="\"$reason\",\"$REASONcount\"\n";

	$i++;
	}

$TOTALcalls =		sprintf("%10s", $TOTALcalls);

$ASCII_text.="+----------------------+------------+\n";
$ASCII_text.="| TOTAL:               | $TOTALcalls |\n";
$ASCII_text.="+----------------------+------------+\n";

$CSV_text3.="\"TOTAL:\",\"$TOTALcalls\"\n";

if ($report_display_type=="HTML") 
	{
	$ct_ary=array();
	$rslt=mysql_query($stmt, $link);
	$high_ct=0; $i=0;
	while ($row=mysql_fetch_row($rslt)) {
		if ($row[0]>$high_ct) {$high_ct=$row[0];}
		$ct_ary[$i][0]=$row[0];
		$ct_ary[$i][1]=$row[1];
		$i++;
	}
	$GRAPH_text="</PRE>\n";
	$GRAPH_text.="<table cellspacing=\"0\" cellpadding=\"0\" summary=\"$rpt_type_verbiage HANGUP REASON STATS\" class=\"horizontalgraph\">\n";
	$GRAPH_text.="  <caption align=\"top\">$rpt_type_verbiage HANGUP REASON STATS</caption>\n";
	$GRAPH_text.="  <tr>\n";
	$GRAPH_text.="	<th class=\"thgraph\" scope=\"col\">REASON </th>\n";
	$GRAPH_text.="	<th class=\"thgraph\" scope=\"col\">$rpt_type_verbiages </th>\n";
	$GRAPH_text.="  </tr>\n";
	for ($i=0; $i<count($ct_ary); $i++) {
		if ($i==0) {$class=" first";} else if (($i+1)==count($ct_ary)) {$class=" last";} else {$class="";}
		$GRAPH_text.="  <tr>\n";
		$GRAPH_text.="	<td class=\"chart_td$class\">".$ct_ary[$i][1]."</td>\n";
		$GRAPH_text.="	<td class=\"chart_td value$class\"><img src=\"images/bar.png\" alt=\"\" width=\"".round(200*$ct_ary[$i][0]/$high_ct)."\" height=\"16\" />".$ct_ary[$i][0]."</td>\n";
		$GRAPH_text.="  </tr>\n";
	}
	$GRAPH_text.="  <tr>\n";
	$GRAPH_text.="	<th class=\"thgraph\" scope=\"col\">TOTAL ".$rpt_type_verbiage.":</th>\n";
	$GRAPH_text.="	<th class=\"thgraph\" scope=\"col\">".trim($TOTALcalls)."</th>\n";
	$GRAPH_text.="  </tr>\n";
	$GRAPH_text.="</table>\n";
	$GRAPH_text.="<PRE>\n";
	$MAIN.=$GRAPH_text;
	}
else
	{
	$MAIN.=$ASCII_text;
	}

##############################
#########  CALL STATUS STATS

$TOTALcalls = 0;

$ASCII_text="\n";
$ASCII_text.="---------- $rpt_type_verbiage STATUS STATS       <a href=\"$PHP_SELF?DB=$DB&DID=$DID&query_date=$query_date&end_date=$end_date$groupQS&shift=$shift&SUBMIT=$SUBMIT&file_download=4\">DOWNLOAD</a>\n";
$ASCII_text.="+--------+----------------------+----------------------+------------+------------+----------+-----------+\n";
$ASCII_text.="| STATUS | DESCRIPTION          | CATEGORY             | $rpt_type_verbiages     | TOTAL TIME | AVG TIME |$rpt_type_verbiages/HOUR|\n";
$ASCII_text.="+--------+----------------------+----------------------+------------+------------+----------+-----------+\n";

$GRAPH="<BR><BR><a name='cssgraph'><table border='0' cellpadding='0' cellspacing='2' width='800'>";
$GRAPH.="<tr><th width='25%' class='grey_graph_cell' id='cssgraph1'><a href='#' onClick=\"DrawCSSGraph('CALLS', '1'); return false;\">CALLS</a></th><th width=25% class='grey_graph_cell' id='cssgraph2'><a href='#' onClick=\"DrawCSSGraph('TOTALTIME', '2'); return false;\">TOTAL TIME</a></th><th width=25% class='grey_graph_cell' id='cssgraph3'><a href='#' onClick=\"DrawCSSGraph('AVGTIME', '3'); return false;\">AVG TIME</a></th><th width=25% class='grey_graph_cell' id='cssgraph4'><a href='#' onClick=\"DrawCSSGraph('CALLSHOUR', '4'); return false;\">CALLS/HR</a></th></tr>";
$GRAPH.="<tr><td colspan='4' class='graph_span_cell'><span id='call_status_stats_graph'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";

$CSV_text4.="\n\"$rpt_type_verbiage STATUS STATS\"\n";
$CSV_text4.="\"STATUS\",\"DESCRIPTION\",\"CATEGORY\",\"$rpt_type_verbiages\",\"TOTAL TIME\",\"AVG TIME\",\"$rpt_type_verbiages/HOUR\"\n";



## get counts and time totals for all statuses in this campaign
$stmt="select count(*),status,sum(length_in_sec) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and  campaign_id IN($group_SQL) group by status;";
if ($DID=='Y')
	{
	$stmt="select count(*),status,sum(length_in_sec) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) group by status;";
	}
$rslt=mysql_query($stmt, $link);
if ($DB) {$ASCII_text.="$stmt\n";}
$statuses_to_print = mysql_num_rows($rslt);
$i=0;

######## GRAPHING #########
$max_calls=1;
$max_total_time=1;
$max_avg_time=1;
$max_callshr=1;
$graph_header="<table cellspacing='0' cellpadding='0' summary='STATUS' class='horizontalgraph'><caption align='top'>CALL STATUS STATS</caption><tr><th class='thgraph' scope='col'>STATUS</th>";
$CALLS_graph=$graph_header."<th class='thgraph' scope='col'>CALLS </th></tr>";
$TOTALTIME_graph=$graph_header."<th class='thgraph' scope='col'>TOTAL TIME</th></tr>";
$AVGTIME_graph=$graph_header."<th class='thgraph' scope='col'>AVG TIME</th></tr>";
$CALLSHOUR_graph=$graph_header."<th class='thgraph' scope='col'>CALLS/HR</th></tr>";
###########################

while ($i < $statuses_to_print)
	{
	$row=mysql_fetch_row($rslt);

	$STATUScount =	$row[0];
	$RAWstatus =	$row[1];
	$r=0;  $foundstat=0;
	while ($r < $statcats_to_print)
		{
		if ( ($statcat_list[$RAWstatus] == "$vsc_id[$r]") and ($foundstat < 1) )
			{
			$vsc_count[$r] = ($vsc_count[$r] + $STATUScount);
			}
		$r++;
		}

	$TOTALcalls =	($TOTALcalls + $row[0]);
	if ( ($STATUScount < 1) or ($TOTALsec < 1) )
		{$STATUSrate = 0;}
	else
		{$STATUSrate =	($STATUScount / ($TOTALsec / 3600) );}
	$STATUSrate =	sprintf("%.2f", $STATUSrate);

	$STATUShours =		sec_convert($row[2],'H'); 
	$STATUSavg_sec =	($row[2] / $STATUScount); 
	$STATUSavg =		sec_convert($STATUSavg_sec,'H'); 

	if ($row[0]>$max_calls) {$max_calls=$row[0];}
	if ($row[2]>$max_total_time) {$max_total_time=$row[2];}
	if ($STATUSavg_sec>$max_avg_time) {$max_avg_time=$STATUSavg_sec;}
	if ($STATUSrate>$max_callshr) {$max_callshr=$STATUSrate;}
	$graph_stats[$i][1]=$row[0];
	$graph_stats[$i][2]=$row[2];
	$graph_stats[$i][3]=$STATUSavg_sec;
	$graph_stats[$i][4]=$STATUSrate;


	$STATUScount =	sprintf("%10s", $row[0]);while(strlen($STATUScount)>10) {$STATUScount = substr("$STATUScount", 0, -1);}
	$status =	sprintf("%-6s", $row[1]);while(strlen($status)>6) {$status = substr("$status", 0, -1);}
	$STATUShours =	sprintf("%10s", $STATUShours);while(strlen($STATUShours)>10) {$STATUShours = substr("$STATUShours", 0, -1);}
	$STATUSavg =	sprintf("%8s", $STATUSavg);while(strlen($STATUSavg)>8) {$STATUSavg = substr("$STATUSavg", 0, -1);}
	$STATUSrate =	sprintf("%8s", $STATUSrate);while(strlen($STATUSrate)>8) {$STATUSrate = substr("$STATUSrate", 0, -1);}

	if ($non_latin < 1)
		{
		$status_name =	sprintf("%-20s", $statname_list[$RAWstatus]); 
		while(strlen($status_name)>20) {$status_name = substr("$status_name", 0, -1);}	
		$statcat =	sprintf("%-20s", $statcat_list[$RAWstatus]); 
		while(strlen($statcat)>20) {$statcat = substr("$statcat", 0, -1);}	
		}
	else
		{
		$status_name =	sprintf("%-60s", $statname_list[$RAWstatus]); 
		while(mb_strlen($status_name,'utf-8')>20) {$status_name = mb_substr("$status_name", 0, -1,'utf-8');}	
		$statcat =	sprintf("%-60s", $statcat_list[$RAWstatus]); 
		while(mb_strlen($statcat,'utf-8')>20) {$statcat = mb_substr("$statcat", 0, -1,'utf-8');}	
		}
	$graph_stats[$i][0]="$status - $status_name - $statcat";


	$ASCII_text.="| $status | $status_name | $statcat | $STATUScount | $STATUShours | $STATUSavg | $STATUSrate |\n";
	$CSV_text4.="\"$status\",\"$status_name\",\"$statcat\",\"$STATUScount\",\"$STATUShours\",\"$STATUSavg\",\"$STATUSrate\"\n";

	$i++;
	}

if ($TOTALcalls < 1)
	{
	$TOTALhours =	'0:00:00';
	$TOTALavg =		'0:00:00';
	$TOTALrate =	'0.00';
	}
else
	{
	if ( ($TOTALcalls < 1) or ($TOTALsec < 1) )
		{$TOTALrate = 0;}
	else
		{$TOTALrate =	($TOTALcalls / ($TOTALsec / 3600) );}
	$TOTALrate =	sprintf("%.2f", $TOTALrate);

	$TOTALhours =		sec_convert($TOTALsec,'H'); 
	$TOTALavg_sec =		($TOTALsec / $TOTALcalls);
	$TOTALavg =			sec_convert($TOTALavg_sec,'H'); 
	}
$TOTALcalls =	sprintf("%10s", $TOTALcalls);
$TOTALhours =	sprintf("%10s", $TOTALhours);while(strlen($TOTALhours)>10) {$TOTALhours = substr("$TOTALhours", 0, -1);}
$TOTALavg =	sprintf("%8s", $TOTALavg);while(strlen($TOTALavg)>8) {$TOTALavg = substr("$TOTALavg", 0, -1);}
$TOTALrate =	sprintf("%8s", $TOTALrate);while(strlen($TOTALrate)>8) {$TOTALrate = substr("$TOTALrate", 0, -1);}

$ASCII_text.="+--------+----------------------+----------------------+------------+------------+----------+----------+\n";
$ASCII_text.="| TOTAL:                                               | $TOTALcalls | $TOTALhours | $TOTALavg | $TOTALrate |\n";
$ASCII_text.="+------------------------------------------------------+------------+------------+----------+----------+\n";

#######
	$JS_onload="onload = function() {\n";
	$JS_onload.="\tDrawCSSGraph('CALLS', '1');\n"; 
	$JS_onload.="\tDrawGraph('CALLS', '1');\n"; 

	for ($d=0; $d<count($graph_stats); $d++) {
		if ($d==0) {$class=" first";} else if (($d+1)==count($graph_stats)) {$class=" last";} else {$class="";}
		$CALLS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][1]/$max_calls)."' height='16' />".$graph_stats[$d][1]."</td></tr>";
		$TOTALTIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][2]/$max_total_time)."' height='16' />".sec_convert($graph_stats[$d][2], 'H')."</td></tr>";
		$AVGTIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][3]/$max_avg_time)."' height='16' />".sec_convert($graph_stats[$d][3], 'H')."</td></tr>";
		$CALLSHOUR_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][4]/$max_callshr)."' height='16' />".$graph_stats[$d][4]."</td></tr>";
	}
	$CALLS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTALcalls)."</th></tr></table>";
	$TOTALTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTALhours)."</th></tr></table>";
	$AVGTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTALavg)."</th></tr></table>";
	$CALLSHOUR_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTALrate)."</th></tr></table>";
	$JS_text.="<script language='Javascript'>\n";
	$JS_text.="function DrawCSSGraph(graph, th_id) {\n";
	$JS_text.="	var CALLS_graph=\"$CALLS_graph\";\n";
	$JS_text.="	var TOTALTIME_graph=\"$TOTALTIME_graph\";\n";
	$JS_text.="	var AVGTIME_graph=\"$AVGTIME_graph\";\n";
	$JS_text.="	var CALLSHOUR_graph=\"$CALLSHOUR_graph\";\n";
	$JS_text.="\n";
	$JS_text.="	for (var i=1; i<=4; i++) {\n";
	$JS_text.="		var cellID=\"cssgraph\"+i;\n";
	$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
	$JS_text.="	}\n";
	$JS_text.="	var cellID=\"cssgraph\"+th_id;\n";
	$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
	$JS_text.="	var graph_to_display=eval(graph+\"_graph\");\n";
	$JS_text.="	document.getElementById('call_status_stats_graph').innerHTML=graph_to_display;\n";
	$JS_text.="}\n";
	$JS_text.="</script>\n";
########


$CSV_text4.="\"TOTAL:\",\"\",\"\",\"$TOTALcalls\",\"$TOTALhours\",\"$TOTALavg\",\"$TOTALrate\"\n";
if ($report_display_type=="HTML")
	{
	$MAIN.=$GRAPH;
	}
else
	{
	$MAIN.=$ASCII_text;
	}


##############################
#########  STATUS CATEGORY STATS

$ASCII_text="\n";
$ASCII_text.="---------- CUSTOM STATUS CATEGORY STATS       <a href=\"$PHP_SELF?DB=$DB&DID=$DID&query_date=$query_date&end_date=$end_date$groupQS&shift=$shift&SUBMIT=$SUBMIT&file_download=5\">DOWNLOAD</a>\n";
$ASCII_text.="+----------------------+------------+--------------------------------+\n";
$ASCII_text.="| CATEGORY             | $rpt_type_verbiages     | DESCRIPTION                    |\n";
$ASCII_text.="+----------------------+------------+--------------------------------+\n";

$CSV_text5.="\n\"CUSTOM STATUS CATEGORY STATS\"\n";
$CSV_text5.="\"CATEGORY\",\"$rpt_type_verbiages\",\"DESCRIPTION\"\n";

$TOTCATcalls=0;
$r=0;
while ($r < $statcats_to_print)
	{
	if ($vsc_id[$r] != 'UNDEFINED')
		{
		$TOTCATcalls = ($TOTCATcalls + $vsc_count[$r]);
		$category =	sprintf("%-20s", $vsc_id[$r]); while(strlen($category)>20) {$category = substr("$category", 0, -1);}
		$CATcount =	sprintf("%10s", $vsc_count[$r]); while(strlen($CATcount)>10) {$CATcount = substr("$CATcount", 0, -1);}
		$CATname =	sprintf("%-30s", $vsc_name[$r]); while(strlen($CATname)>30) {$CATname = substr("$CATname", 0, -1);}

		$ASCII_text.="| $category | $CATcount | $CATname |\n";
		$CSV_text5.="\"$category\",\"$CATcount\",\"$CATname\"\n";
		}

	$r++;
	}

$TOTCATcalls =	sprintf("%10s", $TOTCATcalls); while(strlen($TOTCATcalls)>10) {$TOTCATcalls = substr("$TOTCATcalls", 0, -1);}

$ASCII_text.="+----------------------+------------+--------------------------------+\n";
$ASCII_text.="| TOTAL                | $TOTCATcalls |\n";
$ASCII_text.="+----------------------+------------+\n";

$CSV_text5.="\"TOTAL\",\"$TOTCATcalls\"\n";

if ($report_display_type=="HTML") 
	{
	$ct_ary=array();
	$r=0; $i=0;
	$high_ct=0;
	while ($r < $statcats_to_print)
	{
	if ($vsc_id[$r] != 'UNDEFINED')
		{
		if ($vsc_count[$r]>$high_ct) {$high_ct=$vsc_count[$r];}
		$ct_ary[$i][0]=$vsc_count[$r];
		$ct_ary[$i][1]=$vsc_id[$r]."<br />".$vsc_name[$r];
		$i++;
		}
		$r++;
	}
	
	$GRAPH_text="</PRE>\n";
	$GRAPH_text.="<table cellspacing=\"0\" cellpadding=\"0\" summary=\"CUSTOM STATUS CATEGORY STATS\" class=\"horizontalgraph\">\n";
	$GRAPH_text.="  <caption align=\"top\">CUSTOM STATUS CATEGORY STATS</caption>\n";
	$GRAPH_text.="  <tr>\n";
	$GRAPH_text.="	<th class=\"thgraph\" scope=\"col\">CATEGORY </th>\n";
	$GRAPH_text.="	<th class=\"thgraph\" scope=\"col\">CALLS </th>\n";
	$GRAPH_text.="  </tr>\n";
	for ($i=0; $i<count($ct_ary); $i++) {
		if ($i==0) {$class=" first";} else if (($i+1)==count($ct_ary)) {$class=" last";} else {$class="";}
		if ($high_ct>0) {$bar_width=round((300*$ct_ary[$i][0])/$high_ct);} else {$bar_width=0;}
		$GRAPH_text.="  <tr>\n";
		$GRAPH_text.="	<td class=\"chart_td$class\">".$ct_ary[$i][1]."</td>\n";
		$GRAPH_text.="	<td class=\"chart_td value$class\"><img src=\"images/bar.png\" alt=\"\" width=\"".$bar_width."\" height=\"16\" />".$ct_ary[$i][0]."</td>\n";
		$GRAPH_text.="  </tr>\n";
	}
	$GRAPH_text.="  <tr>\n";
	$GRAPH_text.="	<th class=\"thgraph\" scope=\"col\">TOTAL:</th>\n";
	$GRAPH_text.="	<th class=\"thgraph\" scope=\"col\">".trim($TOTCATcalls)."</th>\n";
	$GRAPH_text.="  </tr>\n";
	$GRAPH_text.="</table>\n";
	$GRAPH_text.="<PRE>\n";
	$MAIN.=$GRAPH_text;
	}
else 
	{
	$MAIN.=$ASCII_text;
	}


##############################
#########  CALL INITIAL QUEUE POSITION BREAKDOWN

$TOTALcalls = 0;

$ASCII_text="\n";
$ASCII_text.="---------- $rpt_type_verbiage INITIAL QUEUE POSITION BREAKDOWN       <a href=\"$PHP_SELF?DB=$DB&DID=$DID&query_date=$query_date&end_date=$end_date$groupQS&shift=$shift&SUBMIT=$SUBMIT&file_download=6\">DOWNLOAD</a>\n";
$ASCII_text.="+-------------------------------------------------------------------------------------+------------+\n";
$ASCII_text.="|     1     2     3     4     5     6     7     8     9    10    15    20    25   +25 | TOTAL      |\n";
$ASCII_text.="+-------------------------------------------------------------------------------------+------------+\n";

$CSV_text6.="\n\"$rpt_type_verbiage INITIAL QUEUE POSITION BREAKDOWN\"\n";
$CSV_text6.="\"\",\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\",\"10\",\"15\",\"20\",\"25\",\"+25\",\"TOTAL\"\n";


$stmt="select count(*),queue_position from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and  campaign_id IN($group_SQL) group by queue_position;";
if ($DID=='Y')
	{
	$stmt="select count(*),queue_position from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) group by queue_position;";
	}
$rslt=mysql_query($stmt, $link);
if ($DB) {$ASCII_text.="$stmt\n";}
$positions_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $positions_to_print)
	{
	$row=mysql_fetch_row($rslt);

	$TOTALcalls = ($TOTALcalls + $row[0]);

	if ( ($row[1] > 0) and ($row[1] <= 1) ) {$qp_1 = ($qp_1 + $row[0]);}
	if ( ($row[1] > 1) and ($row[1] <= 2) ) {$qp_2 = ($qp_2 + $row[0]);}
	if ( ($row[1] > 2) and ($row[1] <= 3) ) {$qp_3 = ($qp_3 + $row[0]);}
	if ( ($row[1] > 3) and ($row[1] <= 4) ) {$qp_4 = ($qp_4 + $row[0]);}
	if ( ($row[1] > 4) and ($row[1] <= 5) ) {$qp_5 = ($qp_5 + $row[0]);}
	if ( ($row[1] > 5) and ($row[1] <= 6) ) {$qp_6 = ($qp_6 + $row[0]);}
	if ( ($row[1] > 6) and ($row[1] <= 7) ) {$qp_7 = ($qp_7 + $row[0]);}
	if ( ($row[1] > 7) and ($row[1] <= 8) ) {$qp_8 = ($qp_8 + $row[0]);}
	if ( ($row[1] > 8) and ($row[1] <= 9) ) {$qp_9 = ($qp_9 + $row[0]);}
	if ( ($row[1] > 9) and ($row[1] <= 10) ) {$qp10 = ($qp10 + $row[0]);}
	if ( ($row[1] > 10) and ($row[1] <= 15) ) {$qp15 = ($qp15 + $row[0]);}
	if ( ($row[1] > 15) and ($row[1] <= 20) ) {$qp20 = ($qp20 + $row[0]);}
	if ( ($row[1] > 20) and ($row[1] <= 25) ) {$qp25 = ($qp25 + $row[0]);}
	if ($row[1] > 25) {$qp99 = ($qp99 + $row[0]);}
	$i++;
	}

$qp_1 =	sprintf("%5s", $qp_1);
$qp_2 =	sprintf("%5s", $qp_2);
$qp_3=	sprintf("%5s", $qp_3);
$qp_4 =	sprintf("%5s", $qp_4);
$qp_5 =	sprintf("%5s", $qp_5);
$qp_6 =	sprintf("%5s", $qp_6);
$qp_7 =	sprintf("%5s", $qp_7);
$qp_8 =	sprintf("%5s", $qp_8);
$qp_9 =	sprintf("%5s", $qp_9);
$qp10 =	sprintf("%5s", $qp10);
$qp15 =	sprintf("%5s", $qp15);
$qp20 =	sprintf("%5s", $qp20);
$qp25 =	sprintf("%5s", $qp25);
$qp99 =	sprintf("%5s", $qp99);

$TOTALcalls =		sprintf("%10s", $TOTALcalls);

$ASCII_text.="| $qp_1 $qp_2 $qp_3 $qp_4 $qp_5 $qp_6 $qp_7 $qp_8 $qp_9 $qp10 $qp15 $qp20 $qp25 $qp99 | $TOTALcalls |\n";
$ASCII_text.="+-------------------------------------------------------------------------------------+------------+\n";

$CSV_text6.="\"\",\"$qp_1\",\"$qp_2\",\"$qp_3\",\"$qp_4\",\"$qp_5\",\"$qp_6\",\"$qp_7\",\"$qp_8\",\"$qp_9\",\"$qp10\",\"$qp15\",\"$qp20\",\"$qp25\",\"$qp99\",\"$TOTALcalls\"\n";

if ($report_display_type=="HTML") 
	{
	$stmt="select count(*),queue_position as qp from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id IN($group_SQL) group by qp order by qp asc;";
	$ms_stmt="select queue_position from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id IN($group_SQL) order by queue_position desc limit 1;"; 
	$mc_stmt="select count(*) as ct from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id IN($group_SQL) group by queue_position order by ct desc limit 1;";
	if ($DID=='Y')
		{
		$stmt="select count(*),queue_position as qp from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) group by qp order by qp asc;";
		$ms_stmt="select queue_position from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) order by queue_position desc limit 1;"; 
		$mc_stmt="select count(*) as ct from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) group by queue_position order by ct desc limit 1;";
		}
	if ($DB) {$GRAPH_text.=$stmt."\n";}
	$ms_rslt=mysql_query($ms_stmt, $link);
	$ms_row=mysql_fetch_row($ms_rslt);
	$max_position=$ms_row[0];
	if ($max_position>25) {$max_position=26;}
	for ($i=1; $i<=$max_position; $i++) {
		$sec_ary[$i]=0;
	}

	$mc_rslt=mysql_query($mc_stmt, $link);
	$mc_row=mysql_fetch_row($mc_rslt);
	$max_calls=$ms_row[0];
	if ($max_calls<=10) {
		while ($maxcalls%5!=0) {
			$maxcalls++;
		}
	} else if ($max_calls<=100) {
		while ($maxcalls%10!=0) {
			$maxcalls++;
		}
	} else if ($max_calls<=1000) {
		while ($maxcalls%50!=0) {
			$maxcalls++;
		}
	} else {
		while ($maxcalls%500!=0) {
			$maxcalls++;
		}
	}
	$rslt=mysql_query($stmt, $link);
	
	$GRAPH_text="<div id=\"InitialQueueCanvas\" style=\"overflow: auto; position:relative;height:300px;width:1000px;\"></div>\n";
	$GRAPH_text.="<script type=\"text/javascript\">\n";
	$GRAPH_text.="var g = new line_graph(25);\n";
	$over25=0;
	while ($row=mysql_fetch_row($rslt)) {
		if ($row[1]<=25) {
			$sec_ary[$row[1]]=$row[0];
		} else {
			$over25+=$row[0];
		}
	}
	$sec_ary[26]=$over25;
	for ($i=1; $i<=$max_position; $i++) {
		if ($i<=25) {
			$GRAPH_text.="g.add('$i', ".$sec_ary[$i].");\n";
		} else {
			$GRAPH_text.="g.add('25+', ".$sec_ary[26].");\n";
		}
	}
	$GRAPH_text.="g.render(\"InitialQueueCanvas\", \"CALL INITIAL QUEUE POSITION BREAKDOWN\");\n";
	$GRAPH_text.="</script>";
	$MAIN.=$GRAPH_text;
	}
else
	{
	$MAIN.=$ASCII_text;
	}

##############################
#########  USER STATS

$TOTagents=0;
$TOTcalls=0;
$TOTtime=0;
$TOTavg=0;

$ASCII_text="\n";
$ASCII_text.="---------- AGENT STATS       <a href=\"$PHP_SELF?DB=$DB&DID=$DID&query_date=$query_date&end_date=$end_date$groupQS&shift=$shift&SUBMIT=$SUBMIT&file_download=7\">DOWNLOAD</a>\n";
$ASCII_text.="+--------------------------+------------+------------+--------+\n";
$ASCII_text.="| AGENT                    | $rpt_type_verbiages     | TIME H:M:S |AVERAGE |\n";
$ASCII_text.="+--------------------------+------------+------------+--------+\n";

$CSV_text7.="\n\"AGENT STATS\"\n";
$CSV_text7.="\"AGENT\",\"$rpt_type_verbiages\",\"TIME H:M:S\",\"AVERAGE\"\n";


$max_calls=1;
$max_timehms=1;
$max_average=1;
$graph_stats=array();
$GRAPH="<BR><BR><a name='agent_stats_graph'/><table border='0' cellpadding='0' cellspacing='2' width='800'>";
$GRAPH.="<tr><th width='34%' class='grey_graph_cell' id='agent_stats_graph1'><a href='#' onClick=\"DrawGraph('CALLS', '1'); return false;\">CALLS</a></th><th width=33% class='grey_graph_cell' id='agent_stats_graph2'><a href='#' onClick=\"DrawGraph('TIMEHMS', '2'); return false;\">TIME H:M:S</a></th><th width=33% class='grey_graph_cell' id='agent_stats_graph3'><a href='#' onClick=\"DrawGraph('AVERAGE', '3'); return false;\">AVERAGE</a></th></tr>";
$GRAPH.="<tr><td colspan='4' class='graph_span_cell'><span id='agentstats_graph'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";
$graph_header="<table cellspacing='0' cellpadding='0' class='horizontalgraph'><caption align='top'>AGENT STATS</caption><tr><th class='thgraph' scope='col'>AGENT</th>";
$CALLS_graph=$graph_header."<th class='thgraph' scope='col'>$rpt_type_verbiages </th></tr>";
$TIMEHMS_graph=$graph_header."<th class='thgraph' scope='col'>TIME H:M:S</th></tr>";
$AVERAGE_graph=$graph_header."<th class='thgraph' scope='col'>AVERAGE</th></tr>";

$stmt="select vicidial_closer_log.user,full_name,count(*),sum(length_in_sec),avg(length_in_sec) from vicidial_closer_log,vicidial_users where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and  campaign_id IN($group_SQL) and vicidial_closer_log.user is not null and length_in_sec is not null and length_in_sec > 0 and vicidial_closer_log.user=vicidial_users.user group by vicidial_closer_log.user;";
if ($DID=='Y')
	{
	$stmt="select vicidial_closer_log.user,full_name,count(*),sum(length_in_sec),avg(length_in_sec) from vicidial_closer_log,vicidial_users where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL) and vicidial_closer_log.user is not null and length_in_sec is not null and length_in_sec > 0 and vicidial_closer_log.user=vicidial_users.user group by vicidial_closer_log.user;";
	}
$rslt=mysql_query($stmt, $link);
if ($DB) {$ASCII_text.="$stmt\n";}
$users_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $users_to_print)
	{
	$row=mysql_fetch_row($rslt);

	$TOTcalls = ($TOTcalls + $row[2]);
	$TOTtime = ($TOTtime + $row[3]);

	$user =			sprintf("%-6s", $row[0]);
	if ($non_latin < 1)
		{
		$full_name =	sprintf("%-15s", $row[1]); while(strlen($full_name)>15) {$full_name = substr("$full_name", 0, -1);}	
		}
	else
		{
		$full_name =	sprintf("%-45s", $row[1]); while(mb_strlen($full_name,'utf-8')>15) {$full_name = mb_substr("$full_name", 0, -1,'utf-8');}	
		}
	$USERcalls =	sprintf("%10s", $row[2]);
	$USERtotTALK =	$row[3];
	$USERavgTALK =	$row[4];

	if ($row[2]>$max_calls) {$max_calls=$row[2];}
	if ($row[3]>$max_timehms) {$max_timehms=$row[3];}
	if ($row[4]>$max_average) {$max_average=$row[4];}
	$graph_stats[$i][0]="$row[0] - $row[1]";
	$graph_stats[$i][1]=$row[2];
	$graph_stats[$i][2]=$row[3];
	$graph_stats[$i][3]=$row[4];

	$USERtotTALK_MS =	sec_convert($USERtotTALK,'H'); 
	$USERavgTALK_MS =	sec_convert($USERavgTALK,'H'); 

	$USERtotTALK_MS =	sprintf("%9s", $USERtotTALK_MS);
	$USERavgTALK_MS =	sprintf("%6s", $USERavgTALK_MS);

	$ASCII_text.="| $user - $full_name | $USERcalls |  $USERtotTALK_MS | $USERavgTALK_MS |\n";
	$CSV_text7.="\"$user - $full_name\",\"$USERcalls\",\"$USERtotTALK_MS\",\"$USERavgTALK_MS\"\n";

	$i++;
	}

if ($TOTcalls < 1) {$TOTcalls = 0; $TOTavg=0;}
else
	{
	$TOTavg = ($TOTtime / $TOTcalls);
	$TOTavg_MS =	sec_convert($TOTavg,'H'); 
	$TOTavg =		sprintf("%6s", $TOTavg_MS);
	}

$TOTtime_MS =	sec_convert($TOTtime,'H'); 
$TOTtime =		sprintf("%10s", $TOTtime_MS);

$TOTagents =		sprintf("%10s", $i);
$TOTcalls =			sprintf("%10s", $TOTcalls);
$TOTtime =			sprintf("%8s", $TOTtime);
$TOTavg =			sprintf("%6s", $TOTavg);

$ASCII_text.="+--------------------------+------------+------------+--------+\n";
$ASCII_text.="| TOTAL Agents: $TOTagents | $TOTcalls | $TOTtime | $TOTavg |\n";
$ASCII_text.="+--------------------------+------------+------------+--------+\n";

for ($d=0; $d<count($graph_stats); $d++) {
	if ($d==0) {$class=" first";} else if (($d+1)==count($graph_stats)) {$class=" last";} else {$class="";}
	$CALLS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][1]/$max_calls)."' height='16' />".$graph_stats[$d][1]."</td></tr>";
	$TIMEHMS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][2]/$max_timehms)."' height='16' />".sec_convert($graph_stats[$d][2], 'H')."</td></tr>";
	$AVERAGE_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][3]/$max_average)."' height='16' />".sec_convert($graph_stats[$d][3], 'H')."</td></tr>";
}
$CALLS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTALcalls)."</th></tr></table>";
$TIMEHMS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTtime)."</th></tr></table>";
$AVERAGE_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTavg)."</th></tr></table>";
$JS_text.="<script language='Javascript'>\n";
$JS_text.="function DrawGraph(graph, th_id) {\n";
$JS_text.="	var CALLS_graph=\"$CALLS_graph\";\n";
$JS_text.="	var TIMEHMS_graph=\"$TIMEHMS_graph\";\n";
$JS_text.="	var AVERAGE_graph=\"$AVERAGE_graph\";\n";
$JS_text.="\n";
$JS_text.="	for (var i=1; i<=3; i++) {\n";
$JS_text.="		var cellID=\"agent_stats_graph\"+i;\n";
$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
$JS_text.="	}\n";
$JS_text.="	var cellID=\"agent_stats_graph\"+th_id;\n";
$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
$JS_text.="	var graph_to_display=eval(graph+\"_graph\");\n";
$JS_text.="	document.getElementById('agentstats_graph').innerHTML=graph_to_display;\n";
$JS_text.="}\n";

$JS_onload.="}\n";
$JS_text.=$JS_onload;

$JS_text.="</script>\n";
$GRAPH_text=$GRAPH;

if ($report_display_type=="HTML")
	{
	$MAIN.=$GRAPH_text;
	}
else
	{
	$MAIN.=$ASCII_text;
	}

$CSV_text7.="\"TOTAL Agents: $TOTagents\",\"$TOTcalls\",\"$TOTtime\",\"$TOTavg\"\n";

##############################
#########  TIME STATS

$MAIN.="\n";
$MAIN.="---------- TIME STATS       <a href=\"$PHP_SELF?DB=$DB&DID=$DID&query_date=$query_date&end_date=$end_date$groupQS&shift=$shift&SUBMIT=$SUBMIT&file_download=9\">DOWNLOAD</a>\n";

$CSV_text9.="\"TIME STATS\"\n\n";

$MAIN.="<FONT SIZE=0>\n";


##############################
#########  15-minute increment breakdowns of total calls and drops, then answered table
$BDansweredCALLS = 0;
$stmt="SELECT status,queue_seconds,UNIX_TIMESTAMP(call_date),call_date from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id IN($group_SQL);";
if ($DID=='Y')
	{
	$stmt="SELECT status,queue_seconds,UNIX_TIMESTAMP(call_date),call_date from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and uniqueid IN($unid_SQL);";
	}
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$calls_to_print = mysql_num_rows($rslt);
$j=0;
while ($j < $calls_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$Cstatus[$j] =	$row[0];
	$Cqueue[$j] =	$row[1];
	$Cepoch[$j] =	$row[2];
	$Cdate[$j] =	$row[3];
	$Crem[$j] = ( ($Cepoch[$j] + $epoch_offset) % 86400); # find the remainder(Modulus) of seconds since start of the day
#	$MAIN.="|$Cepoch[$j]|$Crem[$j]|$Cdate[$j]|\n";
	$j++;
	}

### Loop through all call records and gather stats for total call/drop report and answered report
$j=0;
while ($j < $calls_to_print)
	{
	$i=0; $sec=0; $sec_end=900;
	while ($i <= 96)
		{
		if ( ($Crem[$j] >= $sec) and ($Crem[$j] < $sec_end) ) 
			{
			$Ftotal[$i]++;
			if (ereg("DROP",$Cstatus[$j])) {$Fdrop[$i]++;}
			if (!ereg("DROP|XDROP|HXFER|QVMAIL|HOLDTO|LIVE|QUEUE|TIMEOT|AFTHRS|NANQUE|INBND|MAXCAL",$Cstatus[$j]))
				{
				$BDansweredCALLS++;
				$Fanswer[$i]++;

				if ($Cqueue[$j] == 0)								{$adB_0[$i]++;}
				if ( ($Cqueue[$j] > 0) and ($Cqueue[$j] <= 5) )		{$adB_5[$i]++;}
				if ( ($Cqueue[$j] > 5) and ($Cqueue[$j] <= 10) )	{$adB10[$i]++;}
				if ( ($Cqueue[$j] > 10) and ($Cqueue[$j] <= 15) )	{$adB15[$i]++;}
				if ( ($Cqueue[$j] > 15) and ($Cqueue[$j] <= 20) )	{$adB20[$i]++;}
				if ( ($Cqueue[$j] > 20) and ($Cqueue[$j] <= 25) )	{$adB25[$i]++;}
				if ( ($Cqueue[$j] > 25) and ($Cqueue[$j] <= 30) )	{$adB30[$i]++;}
				if ( ($Cqueue[$j] > 30) and ($Cqueue[$j] <= 35) )	{$adB35[$i]++;}
				if ( ($Cqueue[$j] > 35) and ($Cqueue[$j] <= 40) )	{$adB40[$i]++;}
				if ( ($Cqueue[$j] > 40) and ($Cqueue[$j] <= 45) )	{$adB45[$i]++;}
				if ( ($Cqueue[$j] > 45) and ($Cqueue[$j] <= 50) )	{$adB50[$i]++;}
				if ( ($Cqueue[$j] > 50) and ($Cqueue[$j] <= 55) )	{$adB55[$i]++;}
				if ( ($Cqueue[$j] > 55) and ($Cqueue[$j] <= 60) )	{$adB60[$i]++;}
				if ( ($Cqueue[$j] > 60) and ($Cqueue[$j] <= 90) )	{$adB90[$i]++;}
				if ($Cqueue[$j] > 90)								{$adB99[$i]++;}
				}

			}
		$sec = ($sec + 900);
		$sec_end = ($sec_end + 900);
		$i++;
		}
	$j++;
	}	##### END going through all records







##### 15-minute total and drops graph
$hi_hour_count=0;
$last_full_record=0;
$i=0;
$h=0;
while ($i <= 96)
	{
	$hour_count[$i] = $Ftotal[$i];
	if ($hour_count[$i] > $hi_hour_count) {$hi_hour_count = $hour_count[$i];}
	if ($hour_count[$i] > 0) {$last_full_record = $i;}
	$drop_count[$i] = $Fdrop[$i];
	$i++;
	}

if ($hi_hour_count < 1)
	{$hour_multiplier = 0;}
else
	{
	$hour_multiplier = (100 / $hi_hour_count);
	#$hour_multiplier = round($hour_multiplier, 0);
	}

$ASCII_text.="<!-- HICOUNT: $hi_hour_count|$hour_multiplier -->\n";
$ASCII_text.="GRAPH IN 15 MINUTE INCREMENTS OF TOTAL $rpt_type_verbiages TAKEN INTO THIS IN-GROUP\n";


$k=1;
$Mk=0;
$call_scale = '0';
while ($k <= 102) 
	{
	if ($Mk >= 5) 
		{
		$Mk=0;
		if ( ($k < 1) or ($hour_multiplier <= 0) )
			{$scale_num = 100;}
		else
			{
			$scale_num=($k / $hour_multiplier);
			$scale_num = round($scale_num, 0);
			}
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


$ASCII_text.="+------+-------------------------------------------------------------------------------------------------------+-------+-------+\n";
#$ASCII_text.="| HOUR | GRAPH IN 15 MINUTE INCREMENTS OF TOTAL INCOMING CALLS FOR THIS GROUP                                  | DROPS | TOTAL |\n";
$ASCII_text.="| HOUR |$call_scale| DROPS | TOTAL |\n";
$ASCII_text.="+------+-------------------------------------------------------------------------------------------------------+-------+-------+\n";

$CSV_text9.="\"HOUR\",\"DROPS\",\"TOTAL\"\n";

$max_calls=1;
$graph_stats=array();
$GRAPH_text="<table cellspacing='0' cellpadding='0'><caption align='top'>GRAPH IN 15 MINUTE INCREMENTS OF TOTAL INCOMING CALLS FOR THIS GROUP</caption><tr><th class='thgraph' scope='col'>HOUR</th><th class='thgraph' scope='col'>DROPS <img src='./images/bar_blue.png' width='10' height='10'> / CALLS <img src='./images/bar.png' width='10' height='10'></th></tr>";


$ZZ = '00';
$i=0;
$h=4;
$hour= -1;
$no_lines_yet=1;

while ($i <= 96)
	{
	$char_counter=0;
	$time = '      ';
	if ($h >= 4) 
		{
		$hour++;
		$h=0;
		if ($hour < 10) {$hour = "0$hour";}
		$time = "+$hour$ZZ+";
		#$CSV_text9.="$hour$ZZ,";
		}
	if ($h == 1) {$time = "   15 ";}
	if ($h == 2) {$time = "   30 ";}
	if ($h == 3) {$time = "   45 ";}
	$Ghour_count = $hour_count[$i];
	if ($Ghour_count < 1) 
		{
		if ( ($no_lines_yet) or ($i > $last_full_record) )
			{
			$do_nothing=1;
			}
		else
			{
			$hour_count[$i] =	sprintf("%-5s", $hour_count[$i]);
			$ASCII_text.="|$time|";
			$CSV_text9.="\"$time\",";
			$k=0;   while ($k <= 102) {$ASCII_text.=" ";   $k++;}
			$ASCII_text.="| $hour_count[$i] |\n";
			$CSV_text9.="\"0\",\"0\"\n";

			}
		}
	else
		{
		$no_lines_yet=0;
		$Xhour_count = ($Ghour_count * $hour_multiplier);
		$Yhour_count = (99 - $Xhour_count);

		$Gdrop_count = $drop_count[$i];
		if ($Gdrop_count < 1) 
			{
			$hour_count[$i] =	sprintf("%-5s", $hour_count[$i]);

			$ASCII_text.="|$time|<SPAN class=\"green\">";
			$CSV_text9.="\"$time\",";
			$k=0;   while ($k <= $Xhour_count) {$ASCII_text.="*";   $k++;   $char_counter++;}
			$ASCII_text.="*X</SPAN>";   $char_counter++;
			$k=0;   while ($k <= $Yhour_count) {$ASCII_text.=" ";   $k++;   $char_counter++;}
				while ($char_counter <= 101) {$ASCII_text.=" ";   $char_counter++;}
			$ASCII_text.="| 0     | $hour_count[$i] |\n";
			$CSV_text9.="\"0\",\"$hour_count[$i]\"\n";

			}
		else
			{
			$Xdrop_count = ($Gdrop_count * $hour_multiplier);

		#	if ($Xdrop_count >= $Xhour_count) {$Xdrop_count = ($Xdrop_count - 1);}

			$XXhour_count = ( ($Xhour_count - $Xdrop_count) - 1 );

			$hour_count[$i]+=0;
			$drop_count[$i]+=0;

			$hour_count[$i] =	sprintf("%-5s", $hour_count[$i]);
			$drop_count[$i] =	sprintf("%-5s", $drop_count[$i]);

			$ASCII_text.="|$time|<SPAN class=\"red\">";
			$CSV_text9.="\"$time\",\"\"";
			$k=0;   while ($k <= $Xdrop_count) {$ASCII_text.=">";   $k++;   $char_counter++;}
			$ASCII_text.="D</SPAN><SPAN class=\"green\">";   $char_counter++;
			$k=0;   while ($k <= $XXhour_count) {$ASCII_text.="*";   $k++;   $char_counter++;}
			$ASCII_text.="X</SPAN>";   $char_counter++;
			$k=0;   while ($k <= $Yhour_count) {$ASCII_text.=" ";   $k++;   $char_counter++;}
				while ($char_counter <= 102) {$ASCII_text.=" ";   $char_counter++;}
			$ASCII_text.="| $drop_count[$i] | $hour_count[$i] |\n";
			$CSV_text9.="\"$drop_count[$i]\",\"$hour_count[$i]\"\n";

			}
		}
	
	$graph_stats[$i][0]="$time";
	$graph_stats[$i][1]=trim($hour_count[$i]);
	$graph_stats[$i][2]=trim($drop_count[$i]);
	if (trim($hour_count[$i])>$max_calls) {$max_calls=trim($hour_count[$i]);}
	
	$i++;
	$h++;
	}

$ASCII_text.="+------+-------------------------------------------------------------------------------------------------------+-------+-------+\n\n";

for ($d=0; $d<count($graph_stats); $d++) {
	if (strlen(trim($graph_stats[$d][0]))) {
		$graph_stats[$d][0]=preg_replace('/\s/', "", $graph_stats[$d][0]); 
		$GRAPH_text.="  <tr><td class='chart_td' width='50'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value' width='600' valign='bottom'>\n";
		if ($graph_stats[$d][1]>0) {
			$GRAPH_text.="<ul class='overlap_barGraph'><li class=\"p1\" style=\"height: 12px; left: 0px; width: ".round(600*$graph_stats[$d][1]/$max_calls)."px\"><font style='background-color: #900'>".$graph_stats[$d][1]."</font></li>";
			if ($graph_stats[$d][2]>0) {
				$GRAPH_text.="<li class=\"p2\" style=\"height: 12px; left: 0px; width: ".round(600*$graph_stats[$d][2]/$max_calls)."px\"><font style='background-color: #009'>".$graph_stats[$d][2]."</font></li>";
			}
			$GRAPH_text.="</ul>\n";
		} else {
			$GRAPH_text.="0";
		}
		$GRAPH_text.="</td></tr>\n";
	}
}
$GRAPH_text.="</table><BR/><BR/>";


if ($report_display_type=="HTML")
	{
	$MAIN.=$GRAPH_text;
	}
else
	{
	$MAIN.=$ASCII_text;
	}




##### Answered wait time breakdown
$MAIN.="\n";
$MAIN.="---------- $rpt_type_verbiage ANSWERED TIME BREAKDOWN IN SECONDS       <a href=\"$PHP_SELF?DB=$DB&DID=$DID&query_date=$query_date&end_date=$end_date$groupQS&shift=$shift&SUBMIT=$SUBMIT&file_download=8\">DOWNLOAD</a>\n";
$MAIN.="+------+-------------------------------------------------------------------------------------------+------------+\n";
$MAIN.="| HOUR |     0     5    10    15    20    25    30    35    40    45    50    55    60    90   +90 | TOTAL      |\n";
$MAIN.="+------+-------------------------------------------------------------------------------------------+------------+\n";

$CSV_text8.="\n\"$rpt_type_verbiage ANSWERED TIME BREAKDOWN IN SECONDS\"\n";
$CSV_text8.="\"HOUR\",\"0\",\"5\",\"10\",\"15\",\"20\",\"25\",\"30\",\"35\",\"40\",\"45\",\"50\",\"55\",\"60\",\"90\",\"+90\",\"TOTAL\"\n";

$ZZ = '00';
$i=0;
$h=4;
$hour= -1;
$no_lines_yet=1;
while ($i <= 96)
	{
	$char_counter=0;
	$time = '      ';
	if ($h >= 4) 
		{
		$hour++;
		$h=0;
		if ($hour < 10) {$hour = "0$hour";}
		$time = "+$hour$ZZ+";
		$SQLtime = "$hour:$ZZ:00";
		$SQLtimeEND = "$hour:15:00";
		}
	if ($h == 1) {$time = "   15 ";   $SQLtime = "$hour:15:00";   $SQLtimeEND = "$hour:30:00";}
	if ($h == 2) {$time = "   30 ";   $SQLtime = "$hour:30:00";   $SQLtimeEND = "$hour:45:00";}
	if ($h == 3) 
		{
		$time = "   45 ";
		$SQLtime = "$hour:45:00";
		$hourEND = ($hour + 1);
		if ($hourEND < 10) {$hourEND = "0$hourEND";}
		if ($hourEND > 23) {$SQLtimeEND = "23:59:59";}
		else {$SQLtimeEND = "$hourEND:00:00";}
		}


	if (strlen($adB_0[$i]) < 1)  {$adB_0[$i]='-';}
	if (strlen($adB_5[$i]) < 1)  {$adB_5[$i]='-';}
	if (strlen($adB10[$i]) < 1)  {$adB10[$i]='-';}
	if (strlen($adB15[$i]) < 1)  {$adB15[$i]='-';}
	if (strlen($adB20[$i]) < 1)  {$adB20[$i]='-';}
	if (strlen($adB25[$i]) < 1)  {$adB25[$i]='-';}
	if (strlen($adB30[$i]) < 1)  {$adB30[$i]='-';}
	if (strlen($adB35[$i]) < 1)  {$adB35[$i]='-';}
	if (strlen($adB40[$i]) < 1)  {$adB40[$i]='-';}
	if (strlen($adB45[$i]) < 1)  {$adB45[$i]='-';}
	if (strlen($adB50[$i]) < 1)  {$adB50[$i]='-';}
	if (strlen($adB55[$i]) < 1)  {$adB55[$i]='-';}
	if (strlen($adB60[$i]) < 1)  {$adB60[$i]='-';}
	if (strlen($adB90[$i]) < 1)  {$adB90[$i]='-';}
	if (strlen($adB99[$i]) < 1)  {$adB99[$i]='-';}
	if (strlen($Fanswer[$i]) < 1)  {$Fanswer[$i]='0';}

	$adB_0[$i] = sprintf("%5s", $adB_0[$i]);
	$adB_5[$i] = sprintf("%5s", $adB_5[$i]);
	$adB10[$i] = sprintf("%5s", $adB10[$i]);
	$adB15[$i] = sprintf("%5s", $adB15[$i]);
	$adB20[$i] = sprintf("%5s", $adB20[$i]);
	$adB25[$i] = sprintf("%5s", $adB25[$i]);
	$adB30[$i] = sprintf("%5s", $adB30[$i]);
	$adB35[$i] = sprintf("%5s", $adB35[$i]);
	$adB40[$i] = sprintf("%5s", $adB40[$i]);
	$adB45[$i] = sprintf("%5s", $adB45[$i]);
	$adB50[$i] = sprintf("%5s", $adB50[$i]);
	$adB55[$i] = sprintf("%5s", $adB55[$i]);
	$adB60[$i] = sprintf("%5s", $adB60[$i]);
	$adB90[$i] = sprintf("%5s", $adB90[$i]);
	$adB99[$i] = sprintf("%5s", $adB99[$i]);
	$Fanswer[$i] = sprintf("%10s", $Fanswer[$i]);

	$MAIN.="|$time| $adB_0[$i] $adB_5[$i] $adB10[$i] $adB15[$i] $adB20[$i] $adB25[$i] $adB30[$i] $adB35[$i] $adB40[$i] $adB45[$i] $adB50[$i] $adB55[$i] $adB60[$i] $adB90[$i] $adB99[$i] | $Fanswer[$i] |\n";
	$CSV_text8.="\"$time\",\"$adB_0[$i]\",\"$adB_5[$i]\",\"$adB10[$i]\",\"$adB15[$i]\",\"$adB20[$i]\",\"$adB25[$i]\",\"$adB30[$i]\",\"$adB35[$i]\",\"$adB40[$i]\",\"$adB45[$i]\",\"$adB50[$i]\",\"$adB55[$i]\",\"$adB60[$i]\",\"$adB90[$i]\",\"$adB99[$i]\",\"$Fanswer[$i]\"\n";

	$i++;
	$h++;
	}

$BDansweredCALLS =		sprintf("%10s", $BDansweredCALLS);

$MAIN.="+------+-------------------------------------------------------------------------------------------+------------+\n";
$MAIN.="|TOTALS|                                                                                           | $BDansweredCALLS |\n";
$MAIN.="+------+-------------------------------------------------------------------------------------------+------------+\n";

$CSV_text8.="\"TOTALS\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"$BDansweredCALLS\"\n";


$ENDtime = date("U");
$RUNtime = ($ENDtime - $STARTtime);
$MAIN.="\nRun Time: $RUNtime seconds|$db_source\n";
$MAIN.="</PRE>";
$MAIN.="</TD></TR></TABLE>";

$MAIN.="</BODY></HTML>";

if ($file_download>0) {
	$FILE_TIME = date("Ymd-His");
	$CSVfilename = "AST_CLOSERstats_$US$FILE_TIME.csv";
	$CSV_var="CSV_text".$file_download;
	$CSV_text=preg_replace('/^\s+/', '', $$CSV_var);
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
	echo $HEADER;
	echo $JS_text;
	require("admin_header.php");
	echo $MAIN;
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
