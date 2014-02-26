<?php 
# AST_email_log_report.php
# 
# Copyright (C) 2013  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# report of emails handled by the system
# 
# CHANGELOG:
# 130221-2117 - First build
# 130414-0132 - Added report logging
#

$startMS = microtime();

$version = '2.6-2';
$build = '130414-0132';

header ("Content-type: text/html; charset=utf-8");

require("dbconnect.php");
require("functions.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["server_ip"]))			{$server_ip=$_GET["server_ip"];}
	elseif (isset($_POST["server_ip"]))	{$server_ip=$_POST["server_ip"];}
if (isset($_GET["RR"]))					{$RR=$_GET["RR"];}
	elseif (isset($_POST["RR"]))		{$RR=$_POST["RR"];}
if (isset($_GET["inbound"]))			{$inbound=$_GET["inbound"];}
	elseif (isset($_POST["inbound"]))	{$inbound=$_POST["inbound"];}
if (isset($_GET["group"]))				{$group=$_GET["group"];}
	elseif (isset($_POST["group"]))		{$group=$_POST["group"];}
if (isset($_GET["groups"]))				{$groups=$_GET["groups"];}
	elseif (isset($_POST["groups"]))	{$groups=$_POST["groups"];}
if (isset($_GET["usergroup"]))			{$usergroup=$_GET["usergroup"];}
	elseif (isset($_POST["usergroup"]))	{$usergroup=$_POST["usergroup"];}
if (isset($_GET["DB"]))					{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))		{$DB=$_POST["DB"];}
if (isset($_GET["file_download"]))				{$file_download=$_GET["file_download"];}
	elseif (isset($_POST["file_download"]))	{$file_download=$_POST["file_download"];}
if (isset($_GET["submit"]))				{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))	{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))				{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))	{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["user_group_filter"]))				{$user_group_filter=$_GET["user_group_filter"];}
	elseif (isset($_POST["user_group_filter"]))	{$user_group_filter=$_POST["user_group_filter"];}
if (isset($_GET["email_type"]))				{$email_type=$_GET["email_type"];}
	elseif (isset($_POST["email_type"]))	{$email_type=$_POST["email_type"];}
if (isset($_GET["date_type"]))				{$date_type=$_GET["date_type"];}
	elseif (isset($_POST["date_type"]))	{$date_type=$_POST["date_type"];}
if (isset($_GET["query_date"]))				{$query_date=$_GET["query_date"];}
	elseif (isset($_POST["query_date"]))	{$query_date=$_POST["query_date"];}
if (isset($_GET["end_date"]))				{$end_date=$_GET["end_date"];}
	elseif (isset($_POST["end_date"]))	{$end_date=$_POST["end_date"];}


$report_name = 'Email Log Report';
$db_source = 'M';

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,outbound_autodial_active,slave_db_server,reports_use_slave_db,email_enabled FROM system_settings;";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$qm_conf_ct = mysql_num_rows($rslt);
if ($qm_conf_ct > 0)
	{
	$row=mysql_fetch_row($rslt);
	$non_latin =					$row[0];
	$outbound_autodial_active =		$row[1];
	$slave_db_server =				$row[2];
	$reports_use_slave_db =			$row[3];
	$email_enabled =				$row[4];
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
	echo "<!-- Using slave server $slave_db_server $db_source -->\n";
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

$NOW_DATE = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");
$STARTtime = date("U");
if (!isset($group)) {$group = '';}
if (!isset($query_date)) {$query_date = $NOW_DATE;}
if (!isset($end_date)) {$end_date = $NOW_DATE;}

$stmt="select group_id,group_name,8 from vicidial_inbound_groups where group_handling='EMAIL' $LOGadmin_viewable_groupsSQL order by group_id;";
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
$HEADER.="<script language=\"JavaScript\" src=\"line.js\"></script>\n";
$HEADER.="<script language=\"JavaScript\">\n";
$HEADER.="function OpenWindow(URL) {\n";
$HEADER.="\tvar ns = navigator.appName == \"Netscape\";\n";
$HEADER.="\tif (ns)\n";
$HEADER.="\t\t{\n";
$HEADER.="\t\tBrowseWidth = window.innerWidth;\n";
$HEADER.="\t\tBrowseHeight = window.innerHeight;\n";
$HEADER.="\t\t}\n";
$HEADER.="\telse\n";
$HEADER.="\t\t{\n";
$HEADER.="\t\tBrowseWidth = document.body.clientWidth;\n";
$HEADER.="\t\tBrowseHeight = document.body.clientHeight;\n";
$HEADER.="\t\t}\n";
$HEADER.="\t\tvar params='width='+BrowseWidth+',height='+BrowseHeight+',menubar=1,resizable=1,toolbar=1,scrollbars=1';\n";
$HEADER.="\t\tEMAILWindow=window.open(URL,params);\n";
$HEADER.="\t\tEMAILWindow.moveTo(0,0);\n";
$HEADER.="\t\tEMAILWindow.resizeTo(500,500);\n";
$HEADER.="}\n";
$HEADER.="</script>\n";
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

$MAIN.="<FORM ACTION=\"$PHP_SELF\" METHOD=GET name=vicidial_report id=vicidial_report>\n";
$MAIN.="<TABLE BORDER=0><TR><TD VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2>\n";
$MAIN.="<INPUT TYPE=HIDDEN NAME=DB VALUE=\"$DB\">\n";
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

$MAIN.="</FONT></TD><TD VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2>Email groups:<BR>\n";
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
$MAIN.="</FONT></TD><TD VALIGN=TOP>\n";
$MAIN.="<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ";
if ($DID!='Y')
	{
	$MAIN.="<a href=\"./admin.php?ADD=3111&group_id=$group[0]\">MODIFY</a> | ";
	$MAIN.="<a href=\"./AST_IVRstats.php?query_date=$query_date&end_date=$end_date&shift=$shift$groupQS\">IVR REPORT</a> | \n";
	}
$MAIN.="<a href=\"./admin.php?ADD=999999\">REPORTS</a> | ";
$MAIN.="<BR><BR><BR><CENTER><INPUT TYPE=submit NAME=SUBMIT VALUE=SUBMIT></CENTER>\n";
$MAIN.="</FONT>\n";


$MAIN.="</TD></TR>\n";
$MAIN.="<TR><TD><FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2>\n";
$MAIN.="<BR>Select date criteria:&nbsp;<BR>";
if (!$date_type || $date_type=="email_date") {$emailed="checked"; $date_title="received";}
if ($date_type=="call_date") {$called="checked"; $date_title="viewed";}
if ($date_type=="date_answered") {$answrd="checked"; $date_title="answered";}
$rpt_title.="from $query_date_BEGIN to $query_date_END ";
$MAIN.="<input type='radio' name='date_type' value='email_date' $emailed>Date email received<BR>\n";
$MAIN.="<input type='radio' name='date_type' value='call_date' $called>Date email viewed<BR>\n";
$MAIN.="<input type='radio' name='date_type' value='date_answered' $answrd>Date email answered<BR>\n";
$MAIN.="</FONT></TD><TD><FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2>\n";
$MAIN.="<BR>Select report type to display:&nbsp;<BR>";
if (!$email_type || $email_type=="received") {$rcvd="checked"; $date_type="email_date"; $email_title="received";}
if ($email_type=="viewed") {$view="checked"; $email_title="viewed";}
if ($email_type=="answered") {$answ="checked"; $email_title="answered";}
$MAIN.="<input type='radio' name='email_type' value='received' $rcvd>Emails received<BR>\n";
$MAIN.="<input type='radio' name='email_type' value='viewed' $view>Emails viewed<BR>\n";
$MAIN.="<input type='radio' name='email_type' value='answered' $answ>Emails answered<BR>\n";
$MAIN.="</FONT></TD></TR></TABLE>\n";
$MAIN.="</FORM>\n\n";
$MAIN.="<PRE><FONT SIZE=2>\n\n";

if ($groups_to_print < 1)
	{
	$MAIN.="\n\n";
	$MAIN.="PLEASE SELECT AN EMAIL ACCOUNT AND DATE RANGE ABOVE AND CLICK SUBMIT\n";
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
$query_date_BEGIN = "$query_date 00:00:00";   
$query_date_END = "$end_date 23:59:59";

$rpt_title="Showing emails $email_title that were $date_title from $query_date_BEGIN to $query_date_END\n\n";
$MAIN.=$rpt_title;
$MAIN.="Email log results: $group_string          $NOW_TIME        <a href=\"$PHP_SELF?DB=$DB&query_date=$query_date&end_date=$end_date$groupQS&shift=$shift&SUBMIT=$SUBMIT&file_download=1&date_type=$date_type&email_type=$email_type\">DOWNLOAD</a>\n";
$CSV_text1.="\"Showing emails $email_title that were $date_title from $query_date_BEGIN to $query_date_END\"\n\n";

if ($email_type=="received") 
	{
	$rpt_border="+---------------------+--------------------------------+----------------------+----------------------------------------------------+------------+\n";
	$rpt_header="| DATE EMAIL RECEIVED | ".sprintf("%-30s", "ADDRESS FROM")." | ".sprintf("%-20s", "SENDER NAME")." | ".sprintf("%-50s", "MESSAGE")." | ".sprintf("%-10s", "STATUS")." |\n";
	$CSV_text1.="\"DATE EMAIL RECEIVED\",\"ADDRESS FROM\",\"SENDER NAME\",\"MESSAGE\",\"STATUS\"\n";
	if ($date_type=="email_date") 
		{
	#	$stmt="select vel.* from vicidial_email_list vel where $date_type>='$query_date_BEGIN' and $date_type<='$query_date_END' and group_id in ($group_SQL) order by $date_type asc";
		$stmt="select vel.email_row_id, vel.lead_id, vel.email_date, vel.email_from, vel.email_from_name, convert(vel.message using 'utf8') as message, vel.status from vicidial_email_list vel where vel.$date_type>='$query_date_BEGIN' and vel.$date_type<='$query_date_END' and vel.group_id in ($group_SQL) order by vel.$date_type asc";
		} 
	else if ($date_type=="call_date") 
		{
	#	$stmt="select vel.*, vcl.call_date, from vicidial_email_list vel, vicidial_closer_log vcl where vcl.$date_type>='$query_date_BEGIN' and vcl.$date_type<='$query_date_END' and vcl.uniqueid=vel.uniqueid and vel.group_id in ($group_SQL) order by vcl.$date_type asc";
		$stmt="select vel.email_row_id, vel.lead_id, vel.email_date, vel.email_from, vel.email_from_name, convert(vel.message using 'utf8') as message, vel.status from vicidial_email_list vel where vcl.$date_type>='$query_date_BEGIN' and vcl.$date_type<='$query_date_END' and vcl.uniqueid=vel.uniqueid and vel.group_id in ($group_SQL) and vel.email_row_id=vl.email_row_id order by vcl.$date_type asc";
		} 
	else if ($date_type=="date_answered") 
		{
		$stmt="select vel.email_row_id, vel.lead_id, vel.email_date, vel.email_from, vel.email_from_name, convert(vel.message using 'utf8') as message, vel.status, vcl.call_date, vl.email_date as date_response_sent, vl.user as sending_user, vl.message as sent_message from vicidial_email_list vel, vicidial_closer_log vcl, vicidial_email_log vl where vel.$date_type>='$query_date_BEGIN' and vel.$date_type<='$query_date_END' and vcl.uniqueid=vel.uniqueid and vel.group_id in ($group_SQL) and vel.email_row_id=vl.email_row_id order by vel.$date_type asc";
		}
	}
else if ($email_type=="viewed") 
	{
	$rpt_border="+---------------------+--------------------------------+----------------------+----------------------------------------------------+---------------------+------------+\n";
	$rpt_header="| DATE EMAIL RECEIVED | ".sprintf("%-30s", "ADDRESS FROM")." | ".sprintf("%-20s", "SENDER NAME")." | ".sprintf("%-50s", "MESSAGE")." | DATE EMAIL VIEWED   | ".sprintf("%-10s", "STATUS")." |\n";
	$CSV_text1.="\"DATE EMAIL RECEIVED\",\"ADDRESS FROM\",\"SENDER NAME\",\"MESSAGE\",\"DATE EMAIL VIEWED\",\"STATUS\"\n";
	if ($date_type=="email_date") 
		{
		$stmt="select vel.email_row_id, vel.lead_id, vel.email_date, vel.email_from, vel.email_from_name, convert(vel.message using 'utf8') as message, vel.status, vcl.call_date from vicidial_email_list vel, vicidial_closer_log vcl where vel.$date_type>='$query_date_BEGIN' and vel.$date_type<='$query_date_END' and vcl.uniqueid=vel.uniqueid and vel.group_id in ($group_SQL) order by vel.$date_type asc";
		} 
	else if ($date_type=="call_date") 
		{
		$stmt="select vel.email_row_id, vel.lead_id, vel.email_date, vel.email_from, vel.email_from_name, convert(vel.message using 'utf8') as message, vel.status, vcl.call_date from vicidial_email_list vel, vicidial_closer_log vcl where vcl.$date_type>='$query_date_BEGIN' and vcl.$date_type<='$query_date_END' and vcl.uniqueid=vel.uniqueid and vel.group_id in ($group_SQL) order by vcl.$date_type asc";
		} 
	else if ($date_type=="date_answered") 
		{
		$stmt="select vel.email_row_id, vel.lead_id, vel.email_date, vel.email_from, vel.email_from_name, convert(vel.message using 'utf8') as message, vel.status, vcl.call_date from vicidial_email_list vel, vicidial_closer_log vcl where vel.$date_type>='$query_date_BEGIN' and vel.$date_type<='$query_date_END' and vcl.uniqueid=vel.uniqueid and vel.group_id in ($group_SQL) order by vel.$date_type asc";
		}
	}
else if ($email_type=="answered") 
	{
	$rpt_border="+---------------------+--------------------------------+----------------------+----------------------------------------------------+---------------------+---------------------+----------------------+----------------------------------------------------+------------+\n";
	$rpt_header="| DATE EMAIL RECEIVED | ".sprintf("%-30s", "ADDRESS FROM")." | ".sprintf("%-20s", "SENDER NAME")." | ".sprintf("%-50s", "MESSAGE (click to view full text)")." | DATE EMAIL VIEWED   | DATE EMAIL ANSWERED | ".sprintf("%-20s", "USER")." | ".sprintf("%-50s", "RESPONSE (click to view full text)")." | ".sprintf("%-10s", "STATUS")." |\n";
	$CSV_text1.="\"DATE EMAIL RECEIVED\",\"ADDRESS FROM\",\"SENDER NAME\",\"MESSAGE\",\"DATE EMAIL VIEWED\",\"DATE EMAIL ANSWERED\",\"USER\",\"RESPONSE\",\"STATUS\"\n";
	if ($date_type=="email_date") 
		{
		$stmt="select vel.email_row_id, vel.lead_id, vel.email_date, vel.email_from, vel.email_from_name, convert(vel.message using 'utf8') as message, vel.status, vcl.call_date, vl.email_date as date_response_sent, vl.email_log_id, vl.user as sending_user, vl.message as sent_message from vicidial_email_list vel, vicidial_closer_log vcl, vicidial_email_log vl where vel.$date_type>='$query_date_BEGIN' and vel.$date_type<='$query_date_END' and vcl.uniqueid=vel.uniqueid and vel.group_id in ($group_SQL) and vel.email_row_id=vl.email_row_id order by vel.$date_type asc";
		} 
	else if ($date_type=="call_date") 
		{
		$stmt="select vel.email_row_id, vel.lead_id, vel.email_date, vel.email_from, vel.email_from_name, convert(vel.message using 'utf8') as message, vel.status, vcl.call_date, vl.email_date as date_response_sent, vl.email_log_id, vl.user as sending_user, vl.message as sent_message from vicidial_email_list vel, vicidial_closer_log vcl, vicidial_email_log vl where vcl.$date_type>='$query_date_BEGIN' and vcl.$date_type<='$query_date_END' and vcl.uniqueid=vel.uniqueid and vel.group_id in ($group_SQL) and vel.email_row_id=vl.email_row_id order by vcl.$date_type asc";
		} 
	else if ($date_type=="date_answered") 
		{
		$stmt="select vel.email_row_id, vel.lead_id, vel.email_date, vel.email_from, vel.email_from_name, convert(vel.message using 'utf8') as message, vel.status, vcl.call_date, vl.email_date as date_response_sent, vl.email_log_id, vl.user as sending_user, vl.message as sent_message from vicidial_email_list vel, vicidial_closer_log vcl, vicidial_email_log vl where vel.$date_type>='$query_date_BEGIN' and vel.$date_type<='$query_date_END' and vcl.uniqueid=vel.uniqueid and vel.group_id in ($group_SQL) and vel.email_row_id=vl.email_row_id order by vel.$date_type asc";
		}
	}
#echo $stmt."\n";
$rslt=mysql_query($stmt, $link);
if (mysql_num_rows($rslt)>0) {
	$i=0;
	$rpt_str=$rpt_border;
	$rpt_str.=$rpt_header;
	$rpt_str.=$rpt_border;

	while($row=mysql_fetch_array($rslt)) {
		$email_row_id=$row["email_row_id"];
		$email_log_id=$row["email_log_id"];
		$lead_id=$row["lead_id"];
		$email_date=$row["email_date"];

		$email_from=$row["email_from"];
		if (mb_strlen($email_from,'UTF-8')>27) {$email_from=mb_substr($email_from,0,27,'UTF-8')."...";}

		$sender_name=$row["email_from_name"];
		if (mb_strlen($sender_name,'UTF-8')>17) {$sender_name=mb_substr($sender_name,0,17,'UTF-8')."...";}

		$message=preg_replace('/\r|\n/', '', strip_tags($row["message"]));	
		if (mb_strlen($message,'UTF-8')>47) {$message=mb_substr($message,0,47,'UTF-8')."...";}

		$call_date=$row["call_date"];
		$date_answered=$row["date_response_sent"];
		
		$sent_message=preg_replace('/\r|\n/', ' ', strip_tags($row["sent_message"]));
		if (mb_strlen($sent_message,'UTF-8')>47) {$sent_message=mb_substr($sent_message,0,47,'UTF-8')."...";}
		
		$user=$row["sending_user"];
		$status=$row["status"];

		if ($email_type=="received") {
			$rpt_line="| $email_date | ".sprintf("%-30s", "$email_from")." | ".sprintf("%-20s", "$sender_name")." | <a href='#' onClick=\"OpenWindow('./AST_email_log_display.php?email_row_id=$email_row_id')\">".sprintf("%-50s", "$message")."</a> | ".sprintf("%-10s", "$status")." |\n";
			$CSV_text1.="\"$email_date\",\"$email_from\",\"$sender_name\",\"$message\",\"$status\"\n";
		} else if ($email_type=="viewed") {
			$rpt_line="| $email_date | ".sprintf("%-30s", "$email_from")." | ".sprintf("%-20s", "$sender_name")." | <a href='#' onClick=\"OpenWindow('./AST_email_log_display.php?email_row_id=$email_row_id')\">".sprintf("%-50s", "$message")."</a> | $call_date | ".sprintf("%-10s", "$status")." |\n";
			$CSV_text1.="\"$email_date\",\"$email_from\",\"$sender_name\",\"$message\",\"$call_date\",\"$status\"\n";
		} else if ($email_type=="answered") {
			$rpt_line="| $email_date | ".sprintf("%-30s", "$email_from")." | ".sprintf("%-20s", "$sender_name")." | <a href='#' onClick=\"OpenWindow('./AST_email_log_display.php?email_row_id=$email_row_id')\">".sprintf("%-50s", "$message")."</a> | $call_date | $date_answered | ".sprintf("%-20s", $user)." | <a href='#' onClick=\"OpenWindow('./AST_email_log_display.php?email_log_id=$email_log_id')\">".sprintf("%-50s", "$sent_message")."</a> | ".sprintf("%-10s", "$status")." |\n";
			$CSV_text1.="\"$email_date\",\"$email_from\",\"$sender_name\",\"$message\",\"$call_date\",\"$date_answered\",\"$user\",\"$sent_message\",\"$status\"\n";
		}

		$rpt_str.=$rpt_line;
	}
	$rpt_str.=$rpt_border;
} else {
	$rpt_str="**NO RESULTS FOUND**\n\n";
}
	$MAIN.=$rpt_str;

$ENDtime = date("U");
$RUNtime = ($ENDtime - $STARTtime);
$MAIN.="\nRun Time: $RUNtime seconds|$db_source\n";
$MAIN.="</PRE>";
$MAIN.="</TD></TR></TABLE>";

$MAIN.="</BODY></HTML>";

if ($file_download>0) {
	$FILE_TIME = date("Ymd-His");
	$CSVfilename = "AST_email_log_report_$US$FILE_TIME.csv";
	$CSV_var="CSV_text1";
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

	echo "$CSV_text1";

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
