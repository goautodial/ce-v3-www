<?php 
# AST_CLOSERsummary_hourly.php
# 
# Copyright (C) 2013  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES
#
# 90801-0910 - First build
# 90809-0216 - Added Exclude Outbound Drop Group option
# 100214-1421 - Sort menu alphabetically
# 100216-0042 - Added popup date selector
# 100712-1324 - Added system setting slave server option
# 100802-2347 - Added User Group Allowed Reports option validation
# 100914-1326 - Added lookup for user_level 7 users to set to reports only which will remove other admin links
# 110703-1806 - Added download option
# 111103-2315 - Added user_group restrictions for selecting in-groups
# 120224-0910 - Added HTML display option with bar graphs
# 130414-0107 - Added report logging
#

$startMS = microtime();

require("dbconnect.php");
require("functions.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];

if (isset($_GET["print_calls"]))			{$print_calls=$_GET["print_calls"];}
	elseif (isset($_POST["print_calls"]))	{$print_calls=$_POST["print_calls"];}
if (isset($_GET["exclude_rollover"]))			{$exclude_rollover=$_GET["exclude_rollover"];}
	elseif (isset($_POST["exclude_rollover"]))	{$exclude_rollover=$_POST["exclude_rollover"];}
if (isset($_GET["inbound_rate"]))			{$inbound_rate=$_GET["inbound_rate"];}
	elseif (isset($_POST["inbound_rate"]))	{$inbound_rate=$_POST["inbound_rate"];}
if (isset($_GET["outbound_rate"]))			{$outbound_rate=$_GET["outbound_rate"];}
	elseif (isset($_POST["outbound_rate"]))	{$outbound_rate=$_POST["outbound_rate"];}
if (isset($_GET["bareformat"]))				{$bareformat=$_GET["bareformat"];}
	elseif (isset($_POST["bareformat"]))	{$bareformat=$_POST["bareformat"];}
if (isset($_GET["costformat"]))				{$costformat=$_GET["costformat"];}
	elseif (isset($_POST["costformat"]))	{$costformat=$_POST["costformat"];}
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
if (strlen($exclude_rollover)<2) {$exclude_rollover='NO';}

$report_name = 'Inbound Summary Hourly Report';
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

$exclude_rolloverSQL="$whereLOGadmin_viewable_groupsSQL";
if (eregi("YES",$exclude_rollover))
	{$exclude_rolloverSQL = " where group_id NOT IN(SELECT drop_inbound_group from vicidial_campaigns) $LOGadmin_viewable_groupsSQL";}
$stmt="select group_id,group_name from vicidial_inbound_groups $exclude_rolloverSQL order by group_id;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$groups_to_print = mysql_num_rows($rslt);
$i=0;
$groups_string='|';
#$LISTgroups[$i]='---NONE---';
#$i++;
#$groups_to_print++;
while ($i < $groups_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$LISTgroups[$i] =		$row[0];
	$LISTgroup_names[$i] =	$row[1];
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
$HEADER.="   .green {color: white; background-color: green}\n";
$HEADER.="   .red {color: white; background-color: red}\n";
$HEADER.="   .blue {color: white; background-color: blue}\n";
$HEADER.="   .purple {color: white; background-color: purple}\n";
$HEADER.="-->\n";
$HEADER.="</STYLE>\n";


$HEADER.="<script language=\"JavaScript\" src=\"calendar_db.js\"></script>\n";
$HEADER.="<link rel=\"stylesheet\" href=\"calendar.css\">\n";
$HEADER.="<link rel=\"stylesheet\" href=\"horizontalbargraph.css\">\n";

$HEADER.="<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";
$HEADER.="<TITLE>$report_name</TITLE></HEAD><BODY BGCOLOR=WHITE marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>\n";

if ($bareformat < 1)
	{
	$short_header=1;

	# require("admin_header.php");

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
	$MAIN.="<TABLE BORDER=0><TR><TD VALIGN=TOP>\n";
	$MAIN.="<INPUT TYPE=HIDDEN NAME=DB VALUE=\"$DB\">\n";
	$MAIN.="<INPUT TYPE=HIDDEN NAME=inbound_rate VALUE=\"$inbound_rate\">\n";
	$MAIN.="<INPUT TYPE=HIDDEN NAME=outbound_rate VALUE=\"$outbound_rate\">\n";
	$MAIN.="<INPUT TYPE=HIDDEN NAME=costformat VALUE=\"$costformat\">\n";
	$MAIN.="<INPUT TYPE=HIDDEN NAME=print_calls VALUE=\"$print_calls\">\n";
	$MAIN.="Date Range:<BR>\n";
	$MAIN.="<INPUT TYPE=TEXT NAME=query_date SIZE=10 MAXLENGTH=10 VALUE=\"$query_date\">";

	$MAIN.="	<script language=\"JavaScript\">\n";
	$MAIN.="	var o_cal = new tcal ({\n";
	$MAIN.="		// form name\n";
	$MAIN.="		'formname': 'vicidial_report',\n";
	$MAIN.="		// input name\n";
	$MAIN.="		'controlname': 'query_date'\n";
	$MAIN.="	});\n";
	$MAIN.="	o_cal.a_tpl.yearscroll = false;\n";
	$MAIN.="	// o_cal.a_tpl.weekstart = 1; // Monday week start\n";
	$MAIN.="	</script>\n";

	$MAIN.=" to <INPUT TYPE=TEXT NAME=end_date SIZE=10 MAXLENGTH=10 VALUE=\"$end_date\">";

	$MAIN.="	<script language=\"JavaScript\">\n";
	$MAIN.="	var o_cal = new tcal ({\n";
	$MAIN.="		// form name\n";
	$MAIN.="		'formname': 'vicidial_report',\n";
	$MAIN.="		// input name\n";
	$MAIN.="		'controlname': 'end_date'\n";
	$MAIN.="	});\n";
	$MAIN.="	o_cal.a_tpl.yearscroll = false;\n";
	$MAIN.="	// o_cal.a_tpl.weekstart = 1; // Monday week start\n";
	$MAIN.="	</script>\n";

	$MAIN.="</TD><TD VALIGN=TOP> &nbsp; \n";
	$MAIN.="</TD><TD ROWSPAN=2 VALIGN=TOP>\n";
	$MAIN.="Inbound Groups: <BR>\n";
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
	$MAIN.="<a href=\"$PHP_SELF?DB=$DB&inbound_rate=$inbound_rate&outbound_rate=$outbound_rate$groupQS&costformat=$costformat&print_calls=$print_calls&query_date=$query_date&end_date=$end_date&exclude_rollover=$exclude_rollover&SUBMIT=$SUBMIT&shift=$shift&file_download=1\">DOWNLOAD</a> | ";
	$MAIN.="<a href=\"./admin.php?ADD=3111&group_id=$group[0]\">MODIFY</a> | ";
	$MAIN.="<a href=\"./admin.php?ADD=999999\">REPORTS</a>";
	$MAIN.="</FONT>\n";
	$MAIN.="<BR> &nbsp; Display as:&nbsp;";
	$MAIN.="<select name='report_display_type'>";
	if ($report_display_type) {$MAIN.="<option value='$report_display_type' selected>$report_display_type</option>";}
	$MAIN.="<option value='TEXT'>TEXT</option><option value='HTML'>HTML</option></select>\n<BR>";
	$MAIN.=" &nbsp; Exclude Outbound Drop Groups: <BR>";
	$MAIN.=" &nbsp; <SELECT SIZE=1 NAME=exclude_rollover>\n";
	$MAIN.="<option selected value=\"$exclude_rollover\">$exclude_rollover</option>\n";
	$MAIN.="<option value=\"YES\">YES</option>\n";
	$MAIN.="<option value=\"NO\">NO</option>\n";
	$MAIN.="</SELECT>\n";
	$MAIN.="<BR> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ";
	$MAIN.="<INPUT TYPE=submit NAME=SUBMIT VALUE=SUBMIT>\n";

	$MAIN.="</TD></TR>\n";
	$MAIN.="<TR><TD>\n";

	$MAIN.="Call Time:<BR>\n";
	$MAIN.="<SELECT SIZE=1 NAME=shift>\n";
	$o=0;
	while ($times_to_print > $o)
		{
		if ($call_times[$o] == $shift) {$MAIN.="<option selected value=\"$call_times[$o]\">$call_times[$o] - $call_time_names[$o]</option>\n";}
		else {$MAIN.="<option value=\"$call_times[$o]\">$call_times[$o] - $call_time_names[$o]</option>\n";}
		$o++;
		}
	$MAIN.="</SELECT>\n";
	$MAIN.="</TD><TD>\n";
	$MAIN.="</TD></TR></TABLE>\n";
	$MAIN.="</FORM>\n\n";

	$MAIN.="<PRE><FONT SIZE=2>\n\n";
	}

if ($groups_to_print < 1)
	{
	$MAIN.="\n\n";
	$MAIN.="PLEASE SELECT AN IN-GROUP AND DATE RANGE ABOVE AND CLICK SUBMIT\n";
	}

else
	{
	if ($shift == 'ALL') 
		{
		$Gct_default_start = "0";
		$Gct_default_stop = "2400";
		}
	else 
		{
		$stmt="SELECT call_time_id,call_time_name,call_time_comments,ct_default_start,ct_default_stop,ct_sunday_start,ct_sunday_stop,ct_monday_start,ct_monday_stop,ct_tuesday_start,ct_tuesday_stop,ct_wednesday_start,ct_wednesday_stop,ct_thursday_start,ct_thursday_stop,ct_friday_start,ct_friday_stop,ct_saturday_start,ct_saturday_stop,ct_state_call_times FROM vicidial_call_times where call_time_id='$shift';";
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$MAIN.="$stmt\n";}
		$calltimes_to_print = mysql_num_rows($rslt);
		if ($calltimes_to_print > 0)
			{
			$row=mysql_fetch_row($rslt);
			$Gct_default_start =	$row[3];
			$Gct_default_stop =		$row[4];
			$Gct_sunday_start =		$row[5];
			$Gct_sunday_stop =		$row[6];
			$Gct_monday_start =		$row[7];
			$Gct_monday_stop =		$row[8];
			$Gct_tuesday_start =	$row[9];
			$Gct_tuesday_stop =		$row[10];
			$Gct_wednesday_start =	$row[11];
			$Gct_wednesday_stop =	$row[12];
			$Gct_thursday_start =	$row[13];
			$Gct_thursday_stop =	$row[14];
			$Gct_friday_start =		$row[15];
			$Gct_friday_stop =		$row[16];
			$Gct_saturday_start =	$row[17];
			$Gct_saturday_stop =	$row[18];
			}
		else
			{
			$Gct_default_start = "0";
			$Gct_default_stop = "2400";
			}
		}
	$h=0;
	while ($h < 24)
		{
		$H_test = $h . "00";
		if ( ($H_test >= $Gct_default_start) and ($H_test <= $Gct_default_stop) )
			{
			$Hcalltime[$h]++;
			}
		$h++;
		}

	$query_date_BEGIN = "$query_date 00:00:00";   
	$query_date_END = "$end_date 23:59:59";


	$MAIN .= "Inbound Summary Hourly Report: $group_string          $NOW_TIME\n";
	$CSV_main.="Inbound Summary Hourly Report:,$NOW_TIME\n";


	$JS_text.="<script language='Javascript'>\n";
	$JS_onload="onload = function() {\n";

	if ($group_ct > 0)
		{
		$ASCII_text .= "\n";
		$ASCII_text .= "---------- MULTI-GROUP BREAKDOWN:\n";
		$ASCII_text .= "+------------------------------------------+--------+--------+-----------+---------+-----------+---------+---------+--------+\n";
		$ASCII_text .= "|                                          |        |        |           |         | TOTAL     | AVERAGE | MAXIMUM | TOTAL  |\n";
		$ASCII_text .= "|                                          | TOTAL  | TOTAL  | TOTAL     | AVERAGE | QUEUE     | QUEUE   | QUEUE   | ABANDON|\n";
		$ASCII_text .= "| IN-GROUP                                 | CALLS  | ANSWER | TALK      | TALK    | TIME      | TIME    | TIME    | CALLS  |\n";
		$ASCII_text .= "+------------------------------------------+--------+--------+-----------+---------+-----------+---------+---------+--------+\n";

		$CSV_main.="\"MULTI-GROUP BREAKDOWN:\"\n";
		$CSV_main.="\"IN-GROUP\",\"TOTAL CALLS\",\"TOTAL ANSWER\",\" TOTAL TALK\",\" AVERAGE TALK\",\" TOTAL QUEUE TIME\",\" AVERAGE QUEUE TIME\",\" MAXIMUM QUEUE TIME\",\" TOTAL ABANDON CALLS\"\n";
		$CSV_subreports="";

		$graph_stats=array();
		$max_calls=1;
		$max_answer=1;
		$max_talk=1;
		$max_avgtalk=1;
		$max_queue=1;
		$max_avgqueue=1;
		$max_maxqueue=1;
		$max_totalabandons=1;
		$GRAPH="<BR><BR><a name='multigroup_graph'/><table border='0' cellpadding='0' cellspacing='2' width='800'>";
		$GRAPH.="<tr><th width='12%' class='grey_graph_cell' id='multigroup_graph1'><a href='#' onClick=\"DrawGraph('CALLS', '1'); return false;\">TOTAL CALLS</a></th><th width='12%' class='grey_graph_cell' id='multigroup_graph2'><a href='#' onClick=\"DrawGraph('ANSWER', '2'); return false;\">TOTAL ANSWER</a></th><th width='12%' class='grey_graph_cell' id='multigroup_graph3'><a href='#' onClick=\"DrawGraph('TALK', '3'); return false;\">TOTAL TALK</a></th><th width='12%' class='grey_graph_cell' id='multigroup_graph4'><a href='#' onClick=\"DrawGraph('AVGTALK', '4'); return false;\">AVERAGE TALK</a></th><th width='13%' class='grey_graph_cell' id='multigroup_graph5'><a href='#' onClick=\"DrawGraph('QUEUE', '5'); return false;\">TOTAL QUEUE TIME</a></th><th width='13%' class='grey_graph_cell' id='multigroup_graph6'><a href='#' onClick=\"DrawGraph('AVGQUEUE', '6'); return false;\">AVERAGE QUEUE TIME</a></th><th width='13%' class='grey_graph_cell' id='multigroup_graph7'><a href='#' onClick=\"DrawGraph('MAXQUEUE', '7'); return false;\">MAXIMUM QUEUE TIME</a></th><th width='13%' class='grey_graph_cell' id='multigroup_graph8'><a href='#' onClick=\"DrawGraph('TOTALABANDONS', '8'); return false;\">TOTAL ABANDON CALLS</a></th></tr>";
		$GRAPH.="<tr><td colspan='8' class='graph_span_cell'><span id='multigroup_stats_graph'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";
		$graph_header="<table cellspacing='0' cellpadding='0' class='horizontalgraph'><caption align='top'>INBOUND SERVICE LEVEL REPORT</caption><tr><th class='thgraph' scope='col'>IN-GROUP</th>";
		$CALLS_graph=$graph_header."<th class='thgraph' scope='col'>TOTAL CALLS </th></tr>";
		$ANSWER_graph=$graph_header."<th class='thgraph' scope='col'>TOTAL ANSWER </th></tr>";
		$TALK_graph=$graph_header."<th class='thgraph' scope='col'>TOTAL TALK </th></tr>";
		$AVGTALK_graph=$graph_header."<th class='thgraph' scope='col'>AVERAGE TALK </th></tr>";
		$QUEUE_graph=$graph_header."<th class='thgraph' scope='col'>TOTAL QUEUE TIME </th></tr>";
		$AVGQUEUE_graph=$graph_header."<th class='thgraph' scope='col'>AVERAGE QUEUE TIME </th></tr>";
		$MAXQUEUE_graph=$graph_header."<th class='thgraph' scope='col'>MAXIMUM QUEUE TIME </th></tr>";
		$TOTALABANDONS_graph=$graph_header."<th class='thgraph' scope='col'>TOTAL ABANDON CALLS </th></tr>";

		$i=0;
		$TOTcalls_count=0;
		$TOTanswer_count=0;
		$TOTtalk_sec=0;
		$TOTtalk_avg=0;
		$TOTqueue_seconds=0;
		$TOTqueue_avg=0;
		$TOTmax_queue_seconds=0;
		$TOTdrop_count=0;
		$SUBoutput='';

		while($i < $group_ct)
			{
			$stmt="select group_name,agent_alert_delay from vicidial_inbound_groups where group_id='$group[$i]';";
			$rslt=mysql_query($stmt, $link);
			if ($DB) {$ASCII_text.="$stmt\n";}
			$row=mysql_fetch_row($rslt);
			$group_name[$i] =			$row[0];
			$agent_alert_delay[$i] =	round($row[1] / 1000);

			$out_of_call_time=0;
			$length_in_sec[$i]=0;
			$queue_seconds[$i]=0;
			$talk_sec[$i]=0;
			$calls_count[$i]=0;
			$drop_count[$i]=0;
			$answer_count[$i]=0;
			$max_queue_seconds[$i]=0;
			$Hlength_in_sec=$MT;
			$Hqueue_seconds=$MT;
			$Htalk_sec=$MT;
			$Hcalls_count=$MT;
			$Hdrop_count=$MT;
			$Hanswer_count=$MT;
			$Hmax_queue_seconds=$MT;
			$hTOTALcalls =	0;
			$hANSWERcalls =	0;
			$hSUMtalk =		0;
			$hAVGtalk =		0;
			$hSUMqueue =	0;
			$hAVGqueue =	0;
			$hMAXqueue =	0;
			$hDROPcalls =	0;
			$hPRINT =		0;
			$hTOTcalls_count =			0;
			$hTOTanswer_count =			0;
			$hTOTtalk_sec =				0;
			$hTOTtalk_avg =				0;
			$hTOTqueue_seconds =		0;
			$hTOTqueue_avg =			0;
			$hTOTmax_queue_seconds =	0;
			$hTOTdrop_count =			0;

			$stmt = "SELECT status,length_in_sec,queue_seconds,call_date,UNIX_TIMESTAMP(call_date),phone_number,campaign_id from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='$group[$i]';";
			$rslt=mysql_query($stmt, $link);
			if ($DB) {$ASCII_text.="$stmt\n";}
			$calls_to_parse = mysql_num_rows($rslt);
			$p=0;
			while ($p < $calls_to_parse)
				{
				$row=mysql_fetch_row($rslt);
				$call_date = explode(" ", $row[3]);
				$call_time = ereg_replace("[^0-9]","",$call_date[1]);
				$epoch = $row[4];
				$Cwday = date("w", $epoch);

				$CTstart = $Gct_default_start . "00";
				$CTstop = $Gct_default_stop . "59";

				if ( ($Cwday == 0) and ( ($Gct_sunday_start > 0) and ($Gct_sunday_stop > 0) ) )
					{$CTstart = $Gct_sunday_start . "00";   $CTstop = $Gct_sunday_stop . "59";}
				if ( ($Cwday == 1) and ( ($Gct_monday_start > 0) and ($Gct_monday_stop > 0) ) )
					{$CTstart = $Gct_monday_start . "00";   $CTstop = $Gct_monday_stop . "59";}
				if ( ($Cwday == 2) and ( ($Gct_tuesday_start > 0) and ($Gct_tuesday_stop > 0) ) )
					{$CTstart = $Gct_tuesday_start . "00";   $CTstop = $Gct_tuesday_stop . "59";}
				if ( ($Cwday == 3) and ( ($Gct_wednesday_start > 0) and ($Gct_wednesday_stop > 0) ) )
					{$CTstart = $Gct_wednesday_start . "00";   $CTstop = $Gct_wednesday_stop . "59";}
				if ( ($Cwday == 4) and ( ($Gct_thursday_start > 0) and ($Gct_thursday_stop > 0) ) )
					{$CTstart = $Gct_thursday_start . "00";   $CTstop = $Gct_thursday_stop . "59";}
				if ( ($Cwday == 5) and ( ($Gct_friday_start > 0) and ($Gct_friday_stop > 0) ) )
					{$CTstart = $Gct_friday_start . "00";   $CTstop = $Gct_friday_stop . "59";}
				if ( ($Cwday == 6) and ( ($Gct_saturday_start > 0) and ($Gct_saturday_stop > 0) ) )
					{$CTstart = $Gct_saturday_start . "00";   $CTstop = $Gct_saturday_stop . "59";}

				$Chour = date("G", $epoch);
				if ( ($call_time > $CTstart) and ($call_time < $CTstop) )
					{
					$calls_count[$i]++;
					$length_in_sec[$i] =	($length_in_sec[$i] + $row[1]);
					$queue_seconds[$i] =	($queue_seconds[$i] + $row[2]);
					$TEMPtalk = ( ($row[1] - $row[2]) - $agent_alert_delay[$i]);
					if ($TEMPtalk < 0) {$TEMPtalk = 0;}
					$talk_sec[$i] =	($talk_sec[$i] + $TEMPtalk);
					if ($max_queue_seconds[$i] < $row[2])
						{$max_queue_seconds[$i] = $row[2];}
					if (eregi("DROP",$row[0]))
						{$drop_count[$i]++;}
					else
						{$answer_count[$i]++;}

					$Hcalls_count[$Chour]++;
					$Hlength_in_sec[$Chour] =	($Hlength_in_sec[$Chour] + $row[1]);
					$Hqueue_seconds[$Chour] =	($Hqueue_seconds[$Chour] + $row[2]);
					$Htalk_sec[$Chour] =	($Htalk_sec[$Chour] + $TEMPtalk);
					if ($Hmax_queue_seconds[$Chour] < $row[2])
						{$Hmax_queue_seconds[$Chour] = $row[2];}
					if (eregi("DROP",$row[0]))
						{$Hdrop_count[$Chour]++;}
					else
						{$Hanswer_count[$Chour]++;}
					$Hcalltime[$Chour]++;

					if ($print_calls > 0)
						{
						$ASCII_text.="$row[5]\t$row[6]\t$TEMPtalk\n";
						$PCtemptalk = ($PCtemptalk + $TEMPtalk);
						}
					$q++;
					}
				else
					{$out_of_call_time++;}
				if ($DB)
					{$ASCII_text.="$call_time > $CTstart | $call_time < $CTstop | $Cwday | $Chour | $Hcalltime[$Chour] | $talk_sec[$i]\n";}
				$p++;
				}
			if ( ($answer_count[$i] > 0) and ($talk_sec[$i] > 0) )
				{$talk_avg[$i] = ($talk_sec[$i] / $answer_count[$i]);}
			else
				{$talk_avg[$i] = 0;}
			if ( ($calls_count[$i] > 0) and ($queue_seconds[$i] > 0) )
				{$queue_avg[$i] = ($queue_seconds[$i] / $calls_count[$i]);}
			else
				{$queue_avg[$i] = 0;}

			if ($print_calls > 0)
				{
				$PCtemptalkmin = ($PCtemptalk  / 60);
				$ASCII_text.="$q\t$PCtemptalk\t$PCtemptalkmin\n";
				}

			$TOTcalls_count =			($TOTcalls_count + $calls_count[$i]);
			$TOTanswer_count =			($TOTanswer_count + $answer_count[$i]);
			$TOTtalk_sec =				($TOTtalk_sec + $talk_sec[$i]);
			$TOTqueue_seconds =			($TOTqueue_seconds + $queue_seconds[$i]);
			$TOTdrop_count =			($TOTdrop_count + $drop_count[$i]);
			if ($max_queue_seconds[$i] > $TOTmax_queue_seconds)
				{$TOTmax_queue_seconds = $max_queue_seconds[$i];}

			$graph_stats[$i][0]="$group[$i] - $group_name[$i]";
			$graph_stats[$i][1]=$calls_count[$i];
			$graph_stats[$i][2]=$answer_count[$i];
			$graph_stats[$i][3]=$talk_sec[$i];
			$graph_stats[$i][4]=$talk_avg[$i];
			$graph_stats[$i][5]=$queue_seconds[$i];
			$graph_stats[$i][6]=$queue_avg[$i];
			$graph_stats[$i][7]=$max_queue_seconds[$i];
			$graph_stats[$i][8]=$drop_count[$i];
			if ($calls_count[$i]>$max_calls) {$max_calls=$calls_count[$i];}
			if ($answer_count[$i]>$max_answer) {$max_answer=$answer_count[$i];}
			if ($talk_sec[$i]>$max_talk) {$max_talk=$talk_sec[$i];}
			if ($talk_avg[$i]>$max_avgtalk) {$max_avgtalk=$talk_avg[$i];}
			if ($queue_seconds[$i]>$max_queue) {$max_queue=$queue_seconds[$i];}
			if ($queue_avg[$i]>$max_avgqueue) {$max_avgqueue=$queue_avg[$i];}
			if ($max_queue_seconds[$i]>$max_maxqueue) {$max_maxqueue=$max_queue_seconds[$i];}
			if ($drop_count[$i]>$max_totalabandons) {$max_totalabandons=$drop_count[$i];}

			$talk_sec[$i] =				sec_convert($talk_sec[$i],'H'); 
			$talk_avg[$i] =				sec_convert($talk_avg[$i],'H'); 
			$queue_seconds[$i] =		sec_convert($queue_seconds[$i],'H'); 
			$queue_avg[$i] =			sec_convert($queue_avg[$i],'H'); 
			$max_queue_seconds[$i] =	sec_convert($max_queue_seconds[$i],'H'); 

			$groupDISPLAY =	sprintf("%-40s", "$group[$i] - $group_name[$i]");
			$gTOTALcalls =	sprintf("%6s", $calls_count[$i]);
			$gANSWERcalls =	sprintf("%6s", $answer_count[$i]);
			$gSUMtalk =		sprintf("%9s", $talk_sec[$i]);
			$gAVGtalk =		sprintf("%7s", $talk_avg[$i]);
			$gSUMqueue =	sprintf("%9s", $queue_seconds[$i]);
			$gAVGqueue =	sprintf("%7s", $queue_avg[$i]);
			$gMAXqueue =	sprintf("%7s", $max_queue_seconds[$i]);
			$gDROPcalls =	sprintf("%6s", $drop_count[$i]);

			while(strlen($groupDISPLAY)>40) {$groupDISPLAY = substr("$groupDISPLAY", 0, -1);}


			$ASCII_text .= "| $groupDISPLAY | $gTOTALcalls | $gANSWERcalls | $gSUMtalk | $gAVGtalk | $gSUMqueue | $gAVGqueue | $gMAXqueue | $gDROPcalls |";
			$CSV_main.="\"$groupDISPLAY\",\"$gTOTALcalls\",\"$gANSWERcalls\",\"$gSUMtalk\",\"$gAVGtalk\",\"$gSUMqueue\",\"$gAVGqueue\",\"$gMAXqueue\",\"$gDROPcalls\"\n";
			$ASCII_text .= "<!-- OUT OF CALLTIME: $out_of_call_time -->\n";

			### hour by hour sumaries
			$SUBoutput .= "\n---------- $group[$i] - $group_name[$i]     HOURLY BREAKDOWN:\n";
			$SUBoutput .= "+------+--------+--------+-----------+---------+-----------+---------+---------+--------+\n";
			$SUBoutput .= "|      |        |        |           |         | TOTAL     | AVERAGE | MAXIMUM | TOTAL  |\n";
			$SUBoutput .= "|      | TOTAL  | TOTAL  | TOTAL     | AVERAGE | QUEUE     | QUEUE   | QUEUE   | ABANDON|\n";
			$SUBoutput .= "| HOUR | CALLS  | ANSWER | TALK      | TALK    | TIME      | TIME    | TIME    | CALLS  |\n";
			$SUBoutput .= "+------+--------+--------+-----------+---------+-----------+---------+---------+--------+\n";

			$CSV_subreports.="\n\n\"$group[$i] - $group_name[$i]\"\n\"HOURLY BREAKDOWN:\"\n";
			$CSV_subreports.="\"HOUR\",\"TOTAL CALLS\",\"TOTAL ANSWER\",\" TOTAL TALK\",\" AVERAGE TALK\",\" TOTAL QUEUE TIME\",\" AVERAGE QUEUE TIME\",\" MAXIMUM QUEUE TIME\",\" TOTAL ABANDON CALLS\"\n";

			$sub_graph_stats=array();
			$sub_max_calls=1;
			$sub_max_answer=1;
			$sub_max_talk=1;
			$sub_max_avgtalk=1;
			$sub_max_queue=1;
			$sub_max_avgqueue=1;
			$sub_max_maxqueue=1;
			$sub_max_totalabandons=1;
			$sub_GRAPH.="<BR><BR><a name='".$group[$i]."_graph'/><table border='0' cellpadding='0' cellspacing='2' width='800'>";
			$sub_GRAPH.="<tr><th width='12%' class='grey_graph_cell' id='".$group[$i]."_graph1'><a href='#' onClick=\"Draw".$group[$i]."Graph('CALLS', '1'); return false;\">TOTAL CALLS</a></th><th width='12%' class='grey_graph_cell' id='".$group[$i]."_graph2'><a href='#' onClick=\"Draw".$group[$i]."Graph('ANSWER', '2'); return false;\">TOTAL ANSWER</a></th><th width='12%' class='grey_graph_cell' id='".$group[$i]."_graph3'><a href='#' onClick=\"Draw".$group[$i]."Graph('TALK', '3'); return false;\">TOTAL TALK</a></th><th width='12%' class='grey_graph_cell' id='".$group[$i]."_graph4'><a href='#' onClick=\"Draw".$group[$i]."Graph('AVGTALK', '4'); return false;\">AVERAGE TALK</a></th><th width='13%' class='grey_graph_cell' id='".$group[$i]."_graph5'><a href='#' onClick=\"Draw".$group[$i]."Graph('QUEUE', '5'); return false;\">TOTAL QUEUE TIME</a></th><th width='13%' class='grey_graph_cell' id='".$group[$i]."_graph6'><a href='#' onClick=\"Draw".$group[$i]."Graph('AVGQUEUE', '6'); return false;\">AVERAGE QUEUE TIME</a></th><th width='13%' class='grey_graph_cell' id='".$group[$i]."_graph7'><a href='#' onClick=\"Draw".$group[$i]."Graph('MAXQUEUE', '7'); return false;\">MAXIMUM QUEUE TIME</a></th><th width='13%' class='grey_graph_cell' id='".$group[$i]."_graph8'><a href='#' onClick=\"Draw".$group[$i]."Graph('TOTALABANDONS', '8'); return false;\">TOTAL ABANDON CALLS</a></th></tr>";
			$sub_GRAPH.="<tr><td colspan='8' class='graph_span_cell'><span id='".$group[$i]."_stats_graph'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";
			$sub_graph_header="<table cellspacing='0' cellpadding='0' class='horizontalgraph'><caption align='top'>$group[$i] - $group_name[$i] HOURLY BREAKDOWN</caption><tr><th class='thgraph' scope='col'>HOUR</th>";
			$sub_CALLS_graph=$sub_graph_header."<th class='thgraph' scope='col'>TOTAL CALLS </th></tr>";
			$sub_ANSWER_graph=$sub_graph_header."<th class='thgraph' scope='col'>TOTAL ANSWER </th></tr>";
			$sub_TALK_graph=$sub_graph_header."<th class='thgraph' scope='col'>TOTAL TALK </th></tr>";
			$sub_AVGTALK_graph=$sub_graph_header."<th class='thgraph' scope='col'>AVERAGE TALK </th></tr>";
			$sub_QUEUE_graph=$sub_graph_header."<th class='thgraph' scope='col'>TOTAL QUEUE TIME </th></tr>";
			$sub_AVGQUEUE_graph=$sub_graph_header."<th class='thgraph' scope='col'>AVERAGE QUEUE TIME </th></tr>";
			$sub_MAXQUEUE_graph=$sub_graph_header."<th class='thgraph' scope='col'>MAXIMUM QUEUE TIME </th></tr>";
			$sub_TOTALABANDONS_graph=$sub_graph_header."<th class='thgraph' scope='col'>TOTAL ABANDON CALLS </th></tr>";

			$h=0; $q=0;
			while ($h < 24)
				{
				if ($Hcalltime[$h] > 0)
					{
					if (strlen($Hcalls_count[$h]) < 1)			{$Hcalls_count[$h] = 0;}
					if (strlen($Hanswer_count[$h]) < 1)			{$Hanswer_count[$h] = 0;}
					if (strlen($Htalk_sec[$h]) < 1)				{$Htalk_sec[$h] = 0;}
					if (strlen($Hqueue_seconds[$h]) < 1)		{$Hqueue_seconds[$h] = 0;}
					if (strlen($Hmax_queue_seconds[$h]) < 1)	{$Hmax_queue_seconds[$h] = 0;}
					if (strlen($Hdrop_count[$h]) < 1)			{$Hdrop_count[$h] = 0;}

					$hTOTcalls_count =			($hTOTcalls_count + $Hcalls_count[$h]);
					$hTOTanswer_count =			($hTOTanswer_count + $Hanswer_count[$h]);
					$hTOTtalk_sec =				($hTOTtalk_sec + $Htalk_sec[$h]);
					$hTOTqueue_seconds =		($hTOTqueue_seconds + $Hqueue_seconds[$h]);
					$hTOTdrop_count =			($hTOTdrop_count + $Hdrop_count[$h]);
					if ($Hmax_queue_seconds[$h] > $hTOTmax_queue_seconds)
						{$hTOTmax_queue_seconds = $Hmax_queue_seconds[$h];}

					if ( ($Hanswer_count[$h] > 0) and ($Htalk_sec[$h] > 0) )
						{$Htalk_avg[$h] = ($Htalk_sec[$h] / $Hanswer_count[$h]);}
					else
						{$Htalk_avg[$h] = 0;}
					if ( ($Hcalls_count[$h] > 0) and ($Hqueue_seconds[$h] > 0) )
						{$Hqueue_avg[$h] = ($Hqueue_seconds[$h] / $Hcalls_count[$h]);}
					else
						{$Hqueue_avg[$h] = 0;}

					$sub_graph_stats[$q][0]=sprintf("%2s", $h);
					$sub_graph_stats[$q][1]=$Hcalls_count[$h];
					$sub_graph_stats[$q][2]=$Hanswer_count[$h];
					$sub_graph_stats[$q][3]=$Htalk_sec[$h];
					$sub_graph_stats[$q][4]=$Htalk_avg[$h];
					$sub_graph_stats[$q][5]=$Hqueue_seconds[$h];
					$sub_graph_stats[$q][6]=$Hqueue_avg[$h];
					$sub_graph_stats[$q][7]=$Hmax_queue_seconds[$h];
					$sub_graph_stats[$q][8]=$Hdrop_count[$h];
					if ($Hcalls_count[$h]>$sub_max_calls) {$sub_max_calls=$Hcalls_count[$h];}
					if ($Hanswer_count[$h]>$sub_max_answer) {$sub_max_answer=$Hanswer_count[$h];}
					if ($Htalk_sec[$h]>$sub_max_talk) {$sub_max_talk=$Htalk_sec[$h];}
					if ($Htalk_avg[$h]>$sub_max_avgtalk) {$sub_max_avgtalk=$Htalk_avg[$h];}
					if ($Hqueue_seconds[$h]>$sub_max_maxqueue) {$sub_max_maxqueue=$Hqueue_seconds[$h];}
					if ($Hqueue_avg[$h]>$sub_max_avgqueue) {$sub_max_avgqueue=$Hqueue_avg[$h];}
					if ($Hsub_max_queue_seconds[$h]>$sub_max_maxqueue) {$sub_max_maxqueue=$Hmax_queue_seconds[$h];}
					if ($Hdrop_count[$h]>$sub_max_totalabandons) {$sub_max_totalabandons=$Hdrop_count[$h];}
					$q++;

					$Htalk_sec[$h] =			sec_convert($Htalk_sec[$h],'H'); 
					$Htalk_avg[$h] =			sec_convert($Htalk_avg[$h],'H'); 
					$Hqueue_seconds[$h] =		sec_convert($Hqueue_seconds[$h],'H'); 
					$Hqueue_avg[$h] =			sec_convert($Hqueue_avg[$h],'H'); 
					$Hmax_queue_seconds[$h] =	sec_convert($Hmax_queue_seconds[$h],'H');
					
					$hTOTALcalls =	sprintf("%6s", $Hcalls_count[$h]);
					$hANSWERcalls =	sprintf("%6s", $Hanswer_count[$h]);
					$hSUMtalk =		sprintf("%9s", $Htalk_sec[$h]);
					$hAVGtalk =		sprintf("%7s", $Htalk_avg[$h]);
					$hSUMqueue =	sprintf("%9s", $Hqueue_seconds[$h]);
					$hAVGqueue =	sprintf("%7s", $Hqueue_avg[$h]);
					$hMAXqueue =	sprintf("%7s", $Hmax_queue_seconds[$h]);
					$hDROPcalls =	sprintf("%6s", $Hdrop_count[$h]);
					$hPRINT =		sprintf("%2s", $h);

					$SUBoutput .= "| $hPRINT   | $hTOTALcalls | $hANSWERcalls | $hSUMtalk | $hAVGtalk | $hSUMqueue | $hAVGqueue | $hMAXqueue | $hDROPcalls |\n";
					$CSV_subreports.="\"$hPRINT\",\"$hTOTALcalls\",\"$hANSWERcalls\",\"$hSUMtalk\",\"$hAVGtalk\",\"$hSUMqueue\",\"$hAVGqueue\",\"$hMAXqueue\",\"$hDROPcalls\"\n";
					
					}

				$h++;
				}			

			if ( ($hTOTanswer_count > 0) and ($hTOTtalk_sec > 0) )
				{$hTOTtalk_avg = ($hTOTtalk_sec / $hTOTanswer_count);}
			else
				{$hTOTtalk_avg = 0;}
			if ( ($hTOTcalls_count > 0) and ($hTOTqueue_seconds > 0) )
				{$hTOTqueue_avg = ($hTOTqueue_seconds / $hTOTcalls_count);}
			else
				{$hTOTqueue_avg = 0;}

			$hTOTtalk_sec =			sec_convert($hTOTtalk_sec,'H'); 
			$hTOTtalk_avg =			sec_convert($hTOTtalk_avg,'H'); 
			$hTOTqueue_seconds =		sec_convert($hTOTqueue_seconds,'H'); 
			$hTOTqueue_avg =			sec_convert($hTOTqueue_avg,'H'); 
			$hTOTmax_queue_seconds =	sec_convert($hTOTmax_queue_seconds,'H'); 

			$hTOTcalls_count =			sprintf("%6s", $hTOTcalls_count);
			$hTOTanswer_count =			sprintf("%6s", $hTOTanswer_count);
			$hTOTtalk_sec =				sprintf("%9s", $hTOTtalk_sec);
			$hTOTtalk_avg =				sprintf("%7s", $hTOTtalk_avg);
			$hTOTqueue_seconds =		sprintf("%9s", $hTOTqueue_seconds);
			$hTOTqueue_avg =			sprintf("%7s", $hTOTqueue_avg);
			$hTOTmax_queue_seconds =	sprintf("%7s", $hTOTmax_queue_seconds);
			$hTOTdrop_count =			sprintf("%6s", $hTOTdrop_count);


			for ($q=0; $q<count($sub_graph_stats); $q++) {
				if ($q==0) {$class=" first";} else if (($q+1)==count($sub_graph_stats)) {$class=" last";} else {$class="";}
				$sub_CALLS_graph.="  <tr><td class='chart_td$class'>".$sub_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$sub_graph_stats[$q][1]/$sub_max_calls)."' height='16' />".$sub_graph_stats[$q][1]."</td></tr>";
				$sub_ANSWER_graph.="  <tr><td class='chart_td$class'>".$sub_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$sub_graph_stats[$q][2]/$sub_max_answer)."' height='16' />".$sub_graph_stats[$q][2]."</td></tr>";
				$sub_TALK_graph.="  <tr><td class='chart_td$class'>".$sub_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$sub_graph_stats[$q][3]/$sub_max_talk)."' height='16' />".sec_convert($sub_graph_stats[$q][3],'H')."</td></tr>";
				$sub_AVGTALK_graph.="  <tr><td class='chart_td$class'>".$sub_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$sub_graph_stats[$q][4]/$sub_max_avgtalk)."' height='16' />".sec_convert($sub_graph_stats[$q][4],'H')."</td></tr>";
				$sub_QUEUE_graph.="  <tr><td class='chart_td$class'>".$sub_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$sub_graph_stats[$q][5]/$sub_max_queue)."' height='16' />".sec_convert($sub_graph_stats[$q][5],'H')."</td></tr>";
				$sub_AVGQUEUE_graph.="  <tr><td class='chart_td$class'>".$sub_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$sub_graph_stats[$q][6]/$sub_max_avgqueue)."' height='16' />".sec_convert($sub_graph_stats[$q][6],'H')."</td></tr>";
				$sub_MAXQUEUE_graph.="  <tr><td class='chart_td$class'>".$sub_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$sub_graph_stats[$q][7]/$sub_max_maxqueue)."' height='16' />".sec_convert($sub_graph_stats[$q][7],'H')."</td></tr>";
				$sub_TOTALABANDONS_graph.="  <tr><td class='chart_td$class'>".$sub_graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$sub_graph_stats[$q][8]/$sub_max_totalabandons)."' height='16' />".$sub_graph_stats[$q][8]."</td></tr>";
			}
			$sub_CALLS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTcalls_count)."</th></tr></table>";
			$sub_ANSWER_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTanswer_count)."</th></tr></table>";
			$sub_TALK_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTtalk_sec)."</th></tr></table>";
			$sub_AVGTALK_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTtalk_avg)."</th></tr></table>";
			$sub_QUEUE_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTqueue_seconds)."</th></tr></table>";
			$sub_AVGQUEUE_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTqueue_avg)."</th></tr></table>";
			$sub_MAXQUEUE_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTmax_queue_seconds)."</th></tr></table>";
			$sub_TOTALABANDONS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTdrop_count)."</th></tr></table>";
			$JS_onload.="\tDraw".$group[$i]."Graph('CALLS', '1');\n"; 
			$JS_text.="function Draw".$group[$i]."Graph(graph, th_id) {\n";
			$JS_text.="	var CALLS_graph=\"$sub_CALLS_graph\";\n";
			$JS_text.="	var ANSWER_graph=\"$sub_ANSWER_graph\";\n";
			$JS_text.="	var TALK_graph=\"$sub_TALK_graph\";\n";
			$JS_text.="	var AVGTALK_graph=\"$sub_AVGTALK_graph\";\n";
			$JS_text.="	var QUEUE_graph=\"$sub_QUEUE_graph\";\n";
			$JS_text.="	var AVGQUEUE_graph=\"$sub_AVGQUEUE_graph\";\n";
			$JS_text.="	var MAXQUEUE_graph=\"$sub_MAXQUEUE_graph\";\n";
			$JS_text.="	var TOTALABANDONS_graph=\"$sub_TOTALABANDONS_graph\";\n";
			$JS_text.="\n";
			$JS_text.="	for (var i=1; i<=8; i++) {\n";
			$JS_text.="		var cellID=\"".$group[$i]."_graph\"+i;\n";
			$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
			$JS_text.="	}\n";
			$JS_text.="	var cellID=\"".$group[$i]."_graph\"+th_id;\n";
			$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
			$JS_text.="	var graph_to_display=eval(graph+\"_graph\");\n";
			$JS_text.="	document.getElementById('".$group[$i]."_stats_graph').innerHTML=graph_to_display;\n";
			$JS_text.="}\n";


			$SUBoutput .= "+------+--------+--------+-----------+---------+-----------+---------+---------+--------+\n";
			$SUBoutput .= "|TOTALS| $hTOTcalls_count | $hTOTanswer_count | $hTOTtalk_sec | $hTOTtalk_avg | $hTOTqueue_seconds | $hTOTqueue_avg | $hTOTmax_queue_seconds | $hTOTdrop_count |\n";
			$SUBoutput .= "+------+--------+--------+-----------+---------+-----------+---------+---------+--------+\n";

			$CSV_subreports.="\"TOTALS\",\"$hTOTcalls_count\",\"$hTOTanswer_count\",\"$hTOTtalk_sec\",\"$hTOTtalk_avg\",\"$hTOTqueue_seconds\",\"$hTOTqueue_avg\",\"$hTOTmax_queue_seconds\",\"$hTOTdrop_count\"\n";

#			$SUBoutput.=$sub_GRAPH;

			$i++;
			}

		$rawTOTtalk_sec = $TOTtalk_sec;
		$rawTOTtalk_min = round($rawTOTtalk_sec / 60);

		if ( ($TOTanswer_count > 0) and ($TOTtalk_sec > 0) )
			{$TOTtalk_avg = ($TOTtalk_sec / $TOTanswer_count);}
		else
			{$TOTtalk_avg = 0;}
		if ( ($TOTcalls_count > 0) and ($TOTqueue_seconds > 0) )
			{$TOTqueue_avg = ($TOTqueue_seconds / $TOTcalls_count);}
		else
			{$TOTqueue_avg = 0;}


		for ($q=0; $q<count($graph_stats); $q++) {
			if ($q==0) {$class=" first";} else if (($q+1)==count($graph_stats)) {$class=" last";} else {$class="";}
			$CALLS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][1]/$max_calls)."' height='16' />".$graph_stats[$q][1]."</td></tr>";
			$ANSWER_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][2]/$max_answer)."' height='16' />".$graph_stats[$q][2]."</td></tr>";
			$TALK_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][3]/$max_talk)."' height='16' />".sec_convert($graph_stats[$q][3],'H')."</td></tr>";
			$AVGTALK_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][4]/$max_avgtalk)."' height='16' />".sec_convert($graph_stats[$q][4],'H')."</td></tr>";
			$QUEUE_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][5]/$max_queue)."' height='16' />".sec_convert($graph_stats[$q][5],'H')."</td></tr>";
			$AVGQUEUE_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][6]/$max_avgqueue)."' height='16' />".sec_convert($graph_stats[$q][6],'H')."</td></tr>";
			$MAXQUEUE_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][7]/$max_maxqueue)."' height='16' />".sec_convert($graph_stats[$q][7],'H')."</td></tr>";
			$TOTALABANDONS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$q][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$q][8]/$max_totalabandons)."' height='16' />".$graph_stats[$q][8]."</td></tr>";
		}
		$CALLS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTcalls_count)."</th></tr></table>";
		$ANSWER_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTanswer_count)."</th></tr></table>";
		$TALK_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTtalk_sec)."</th></tr></table>";
		$AVGTALK_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTtalk_avg)."</th></tr></table>";
		$QUEUE_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTqueue_seconds)."</th></tr></table>";
		$AVGQUEUE_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTqueue_avg)."</th></tr></table>";
		$MAXQUEUE_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTmax_queue_seconds)."</th></tr></table>";
		$TOTALABANDONS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTdrop_count)."</th></tr></table>";
		$JS_onload.="\tDrawGraph('CALLS', '1');\n"; 
		$JS_text.="function DrawGraph(graph, th_id) {\n";
		$JS_text.="	var CALLS_graph=\"$CALLS_graph\";\n";
		$JS_text.="	var ANSWER_graph=\"$ANSWER_graph\";\n";
		$JS_text.="	var TALK_graph=\"$TALK_graph\";\n";
		$JS_text.="	var AVGTALK_graph=\"$AVGTALK_graph\";\n";
		$JS_text.="	var QUEUE_graph=\"$QUEUE_graph\";\n";
		$JS_text.="	var AVGQUEUE_graph=\"$AVGQUEUE_graph\";\n";
		$JS_text.="	var MAXQUEUE_graph=\"$MAXQUEUE_graph\";\n";
		$JS_text.="	var TOTALABANDONS_graph=\"$TOTALABANDONS_graph\";\n";
		$JS_text.="\n";
		$JS_text.="	for (var i=1; i<=8; i++) {\n";
		$JS_text.="		var cellID=\"multigroup_graph\"+i;\n";
		$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
		$JS_text.="	}\n";
		$JS_text.="	var cellID=\"multigroup_graph\"+th_id;\n";
		$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
		$JS_text.="	var graph_to_display=eval(graph+\"_graph\");\n";
		$JS_text.="	document.getElementById('multigroup_stats_graph').innerHTML=graph_to_display;\n";
		$JS_text.="}\n";
	
		
		
		$TOTtalk_sec =			sec_convert($TOTtalk_sec,'H'); 
		$TOTtalk_avg =			sec_convert($TOTtalk_avg,'H'); 
		$TOTqueue_seconds =		sec_convert($TOTqueue_seconds,'H'); 
		$TOTqueue_avg =			sec_convert($TOTqueue_avg,'H'); 
		$TOTmax_queue_seconds =	sec_convert($TOTmax_queue_seconds,'H'); 

		$i =					sprintf("%4s", $i);
		$TOTcalls_count =		sprintf("%6s", $TOTcalls_count);
		$TOTanswer_count =		sprintf("%6s", $TOTanswer_count);
		$TOTtalk_sec =			sprintf("%9s", $TOTtalk_sec);
		$TOTtalk_avg =			sprintf("%7s", $TOTtalk_avg);
		$TOTqueue_seconds =		sprintf("%9s", $TOTqueue_seconds);
		$TOTqueue_avg =			sprintf("%7s", $TOTqueue_avg);
		$TOTmax_queue_seconds =	sprintf("%7s", $TOTmax_queue_seconds);
		$TOTdrop_count =		sprintf("%6s", $TOTdrop_count);

		$ASCII_text .= "+------------------------------------------+--------+--------+-----------+---------+-----------+---------+---------+--------+\n";
		$ASCII_text .= "| TOTALS       In-Groups: $i             | $TOTcalls_count | $TOTanswer_count | $TOTtalk_sec | $TOTtalk_avg | $TOTqueue_seconds | $TOTqueue_avg | $TOTmax_queue_seconds | $TOTdrop_count |\n";
		$ASCII_text .= "+------------------------------------------+--------+--------+-----------+---------+-----------+---------+---------+--------+\n";

		# $MAIN.=$GRAPH;
		
		$CSV_main.="\"TOTALS       In-Groups: $i\",\"$TOTcalls_count\",\"$TOTanswer_count\",\"$TOTtalk_sec\",\"$TOTtalk_avg\",\"$TOTqueue_seconds\",\"$TOTqueue_avg\",\"$TOTmax_queue_seconds\",\"$TOTdrop_count\"\n";
		}

		$JS_onload.="}\n";
		$JS_text.=$JS_onload;
		$JS_text.="</script>\n";

	if ($costformat > 0)
		{
		$ASCII_text.="</PRE>\n<B>";
		$inbound_cost = ($rawTOTtalk_min * $inbound_rate);
		$inbound_cost =		sprintf("%8.2f", $inbound_cost);

		$ASCII_text.="INBOUND $query_date to $end_date, &nbsp; $rawTOTtalk_min minutes at \$$inbound_rate = \$$inbound_cost\n";

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


if ($file_download > 0)
	{
	$FILE_TIME = date("Ymd-His");
	$CSVfilename = "AST_CLOSERsummary_hourly_$US$FILE_TIME.csv";
	$CSV_text=$CSV_main.$CSV_subreports;
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

	if ($report_display_type=="HTML")
		{
		$MAIN.=$GRAPH;
		$MAIN.=$sub_GRAPH;
		}
	else
		{
		$MAIN.=$ASCII_text;
		$MAIN.=$SUBoutput;
		}

	echo "$HEADER";
	echo $JS_text;
	require("admin_header.php");
	echo "$MAIN";
#	echo "$SUBoutput";



	$ENDtime = date("U");
	$RUNtime = ($ENDtime - $STARTtime);
	echo "\n\nRun Time: $RUNtime seconds|$db_source\n";
	echo "</PRE>";
	echo "</TD></TR></TABLE>";

	echo "</BODY></HTML>";

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


?>
