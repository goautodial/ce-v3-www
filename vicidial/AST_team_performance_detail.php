<?php 
# AST_team_performance_detail.php
#
# This User-Group based report runs some very intensive SQL queries, so it is
# not recommended to run this on long time periods. This report depends on the
# QC statuses of QCFAIL, QCCANC and sales are defined by the Sale=Y status
# flags being set on those statuses.
#
# Copyright (C) 2013  Joe Johnson, Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES
#
# 110802-2041 - First build
# 110804-0049 - Added First Call Resolution
# 111104-1259 - Added user_group restrictions for selecting in-groups
# 120224-1424 - Added new colums and PRECAL to System Time
# 120307-1926 - Added additional statuses option and HTML display option
# 130414-0142 - Added report logging
#

$startMS = microtime();

require("dbconnect.php");
require("functions.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["query_date_D"]))			{$query_date_D=$_GET["query_date_D"];}
	elseif (isset($_POST["query_date_D"]))	{$query_date_D=$_POST["query_date_D"];}
if (isset($_GET["end_date_D"]))				{$end_date_D=$_GET["end_date_D"];}
	elseif (isset($_POST["end_date_D"]))	{$end_date_D=$_POST["end_date_D"];}
if (isset($_GET["query_date_T"]))			{$query_date_T=$_GET["query_date_T"];}
	elseif (isset($_POST["query_date_T"]))	{$query_date_T=$_POST["query_date_T"];}
if (isset($_GET["end_date_T"]))				{$end_date_T=$_GET["end_date_T"];}
	elseif (isset($_POST["end_date_T"]))	{$end_date_T=$_POST["end_date_T"];}
if (isset($_GET["group"]))					{$group=$_GET["group"];}
	elseif (isset($_POST["group"]))			{$group=$_POST["group"];}
if (isset($_GET["call_status"]))					{$call_status=$_GET["call_status"];}
	elseif (isset($_POST["call_status"]))			{$call_status=$_POST["call_status"];}
if (isset($_GET["user_group"]))				{$user_group=$_GET["user_group"];}
	elseif (isset($_POST["user_group"]))	{$user_group=$_POST["user_group"];}
if (isset($_GET["file_download"]))			{$file_download=$_GET["file_download"];}
	elseif (isset($_POST["file_download"]))	{$file_download=$_POST["file_download"];}
if (isset($_GET["DB"]))						{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))			{$DB=$_POST["DB"];}
if (isset($_GET["SUBMIT"]))					{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))		{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["report_display_type"]))				{$report_display_type=$_GET["report_display_type"];}
	elseif (isset($_POST["report_display_type"]))	{$report_display_type=$_POST["report_display_type"];}


$report_name = 'Team Performance Detail';
$db_source = 'M';
$JS_text.="<script language='Javascript'>\n";
$JS_onload="onload = function() {\n";

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,outbound_autodial_active,slave_db_server,reports_use_slave_db FROM system_settings;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$HTML_text.="$stmt\n";}
if ($archive_tbl) {$agent_log_table="vicidial_agent_log_archive";} else {$agent_log_table="vicidial_agent_log";}
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


$PHP_AUTH_USER = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_USER);
$PHP_AUTH_PW = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_PW);

$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 6 and view_reports='1' and active='Y';";
if ($DB) {$HTML_text.="|$stmt|\n";}
if ($non_latin > 0) { $rslt=mysql_query("SET NAMES 'UTF8'");}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$auth=$row[0];

$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level='7' and view_reports='1' and active='Y';";
if ($DB) {$HTML_text.="|$stmt|\n";}
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
	$HTML_text.="<!-- Using slave server $slave_db_server $db_source -->\n";
	}

$stmt="SELECT user_group from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 6 and view_reports='1' and active='Y';";
if ($DB) {$HTML_text.="|$stmt|\n";}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$LOGuser_group =			$row[0];

$stmt="SELECT allowed_campaigns,allowed_reports,admin_viewable_groups,admin_viewable_call_times from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {$HTML_text.="|$stmt|\n";}
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

$LOGallowed_campaignsSQL='';
$whereLOGallowed_campaignsSQL='';
if ( (!eregi("-ALL",$LOGallowed_campaigns)) )
	{
	$rawLOGallowed_campaignsSQL = preg_replace("/ -/",'',$LOGallowed_campaigns);
	$rawLOGallowed_campaignsSQL = preg_replace("/ /","','",$rawLOGallowed_campaignsSQL);
	$LOGallowed_campaignsSQL = "and campaign_id IN('$rawLOGallowed_campaignsSQL')";
	$whereLOGallowed_campaignsSQL = "where campaign_id IN('$rawLOGallowed_campaignsSQL')";
	}
$regexLOGallowed_campaigns = " $LOGallowed_campaigns ";

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

######################################

$MT[0]='';
$NOW_DATE = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");
$STARTtime = date("U");
if (!isset($group)) {$group = '';}
if (!isset($call_statuses)) {$call_statuses = '';}
if (!isset($query_date_D)) {$query_date_D=$NOW_DATE;}
if (!isset($end_date_D)) {$end_date_D=$NOW_DATE;}
if (!isset($query_date_T)) {$query_date_T="00:00:00";}
if (!isset($end_date_T)) {$end_date_T="23:59:59";}


$i=0;
$group_string='|';
$group_ct = count($group);
while($i < $group_ct)
	{
	$group_string .= "$group[$i]|";
	$i++;
	}

$stmt="select campaign_id from vicidial_campaigns $whereLOGallowed_campaignsSQL order by campaign_id;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$HTML_text.="$stmt\n";}
$campaigns_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $campaigns_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$groups[$i] =$row[0];
	if (ereg("-ALL",$group_string) )
		{$group[$i] = $groups[$i];}
	$i++;
	}

#######################################
$i=0;
$call_status_string='|';
$call_status_ct = count($call_status);
while($i < $call_status_ct)
	{
	$call_status_string .= "$call_status[$i]|";
	$i++;
	}
if (preg_match("/--NONE--/", $call_status_string))
	{
	$call_status_string="";
	$call_status=array();
	$call_status_ct=0;
	}

$stmt="select distinct status, status_name from vicidial_statuses where sale!='Y' UNION select distinct status, status_name from vicidial_campaign_statuses where sale!='Y' $LOGallowed_campaignsSQL order by status, status_name;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$HTML_text.="$stmt\n";}
$call_statuses_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $call_statuses_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$call_statuses[$i] =$row[0];
	$call_statuses_names[$i] =$row[1];
#	if (ereg("-ALL",$call_status_string) )
#		{$call_status[$i] = $call_statuses[$i];}
	$i++;
	}

#######################################
for ($i=0; $i<count($user_group); $i++) 
	{
	if (eregi("--ALL--", $user_group[$i])) {$all_user_groups=1; $user_group="";}
	}

$stmt="select user_group from vicidial_user_groups $whereLOGadmin_viewable_groupsSQL order by user_group;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$HTML_text.="$stmt\n";}
$user_groups_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $user_groups_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$user_groups[$i] =$row[0];
	if ($all_user_groups) {$user_group[$i]=$row[0];}
	$i++;
	}

$i=0;
$group_string='|';
$group_ct = count($group);
while($i < $group_ct)
	{
	if ( (preg_match("/ $group[$i] /",$regexLOGallowed_campaigns)) or (preg_match("/-ALL/",$LOGallowed_campaigns)) )
		{
		$group_string .= "$group[$i]|";
		$group_SQL .= "'$group[$i]',";
		$groupQS .= "&group[]=$group[$i]";
		}
	$i++;
	}

if ( (ereg("--ALL--",$group_string) ) or ($group_ct < 1) )
	{$group_SQL = "";}
else
	{
	$group_SQL = eregi_replace(",$",'',$group_SQL);
	$group_SQL_str=$group_SQL;
	$group_SQL = "and campaign_id IN($group_SQL)";
	}

$i=0;
$call_status_string='|';
$call_status_ct = count($call_status);
while($i < $call_status_ct)
	{
	$call_status_string .= "$call_status[$i]|";
	$call_status_SQL .= "'$call_status[$i]',";
	$CSVstatusheader.=",\"$call_status[$i]\"";
	$HTMLborderheader.="--------+";
	$HTMLstatusheader.=" ".sprintf("%6s", $call_status[$i])." |";
	$call_statusQS .= "&call_status[]=$call_status[$i]";
	$i++;
	}

if ( (ereg("--NONE--",$call_status_string) ) or ($call_status_ct < 1) )
	{$call_status_SQL = "";}
else
	{
	$call_status_SQL = eregi_replace(",$",'',$call_status_SQL);
	$call_status_SQL_str=$call_status_SQL;
	$call_status_SQL = "and status IN($call_status_SQL)";
	}


$i=0;
$user_group_string='|';
$user_group_ct = count($user_group);
while($i < $user_group_ct)
	{
	$user_group_string .= "$user_group[$i]|";
	$user_group_SQL .= "'$user_group[$i]',";
	$user_groupQS .= "&user_group[]=$user_group[$i]";
	$i++;
	}

if ( (ereg("--ALL--",$user_group_string) ) or ($user_group_ct < 1) )
	{$user_group_SQL = "";}
else
	{
	$user_group_SQL = eregi_replace(",$",'',$user_group_SQL);
	$user_group_SQL = "and vicidial_agent_log.user_group IN($user_group_SQL)";
	}
######################################
if ($DB) {$HTML_text.="$user_group_string|$user_group_ct|$user_groupQS|$i<BR>";}


###########################

$HTML_head.="<HTML>\n";
$HTML_head.="<HEAD>\n";
$HTML_head.="<STYLE type=\"text/css\">\n";
$HTML_head.="<!--\n";
$HTML_head.="   .green {color: white; background-color: green}\n";
$HTML_head.="   .red {color: white; background-color: red}\n";
$HTML_head.="   .blue {color: white; background-color: blue}\n";
$HTML_head.="   .purple {color: white; background-color: purple}\n";
$HTML_head.="-->\n";
$HTML_head.=" </STYLE>\n";

$query_date="$query_date_D $query_date_T";
$end_date="$end_date_D $end_date_T";

$HTML_head.="<script language=\"JavaScript\" src=\"calendar_db.js\"></script>\n";
$HTML_head.="<link rel=\"stylesheet\" href=\"calendar.css\">\n";
$HTML_head.="<link rel=\"stylesheet\" href=\"horizontalbargraph.css\">\n";

$HTML_head.="<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";
$HTML_head.="<TITLE>$report_name</TITLE></HEAD><BODY BGCOLOR=WHITE marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>$group_S\n";

$HTML_text.="<TABLE CELLPADDING=4 CELLSPACING=0><TR><TD>";

$HTML_text.="<FORM ACTION=\"$PHP_SELF\" METHOD=GET name=vicidial_report id=vicidial_report>\n";
$HTML_text.="<TABLE CELLSPACING=3><TR><TD VALIGN=TOP> Dates:<BR>";
$HTML_text.="<INPUT TYPE=HIDDEN NAME=DB VALUE=\"$DB\">\n";
$HTML_text.="<INPUT TYPE=HIDDEN NAME=type VALUE=\"$type\">\n";
$HTML_text.="Date Range:<BR>\n";

$HTML_text.="<INPUT TYPE=hidden NAME=query_date ID=query_date VALUE=\"$query_date\">\n";
$HTML_text.="<INPUT TYPE=hidden NAME=end_date ID=end_date VALUE=\"$end_date\">\n";
$HTML_text.="<INPUT TYPE=TEXT NAME=query_date_D SIZE=11 MAXLENGTH=10 VALUE=\"$query_date_D\">";

$HTML_text.="<script language=\"JavaScript\">\n";
$HTML_text.="var o_cal = new tcal ({\n";
$HTML_text.="	// form name\n";
$HTML_text.="	'formname': 'vicidial_report',\n";
$HTML_text.="	// input name\n";
$HTML_text.="	'controlname': 'query_date_D'\n";
$HTML_text.="});\n";
$HTML_text.="o_cal.a_tpl.yearscroll = false;\n";
$HTML_text.="// o_cal.a_tpl.weekstart = 1; // Monday week start\n";
$HTML_text.="</script>\n";

$HTML_text.=" &nbsp; <INPUT TYPE=TEXT NAME=query_date_T SIZE=9 MAXLENGTH=8 VALUE=\"$query_date_T\">";

$HTML_text.="<BR> to <BR><INPUT TYPE=TEXT NAME=end_date_D SIZE=11 MAXLENGTH=10 VALUE=\"$end_date_D\">";

$HTML_text.="<script language=\"JavaScript\">\n";
$HTML_text.="var o_cal = new tcal ({\n";
$HTML_text.="	// form name\n";
$HTML_text.="	'formname': 'vicidial_report',\n";
$HTML_text.="	// input name\n";
$HTML_text.="	'controlname': 'end_date_D'\n";
$HTML_text.="});\n";
$HTML_text.="o_cal.a_tpl.yearscroll = false;\n";
$HTML_text.="// o_cal.a_tpl.weekstart = 1; // Monday week start\n";
$HTML_text.="</script>\n";

$HTML_text.=" &nbsp; <INPUT TYPE=TEXT NAME=end_date_T SIZE=9 MAXLENGTH=8 VALUE=\"$end_date_T\">";

$HTML_text.="</TD><TD VALIGN=TOP> Campaigns:<BR>";
$HTML_text.="<SELECT SIZE=5 NAME=group[] multiple>\n";
if  (eregi("--ALL--",$group_string))
	{$HTML_text.="<option value=\"--ALL--\" selected>-- ALL CAMPAIGNS --</option>\n";}
else
	{$HTML_text.="<option value=\"--ALL--\">-- ALL CAMPAIGNS --</option>\n";}
$o=0;
while ($campaigns_to_print > $o)
	{
	if (eregi("$groups[$o]\|",$group_string)) 
		{$HTML_text.="<option selected value=\"$groups[$o]\">$groups[$o]</option>\n";}
	else 
		{$HTML_text.="<option value=\"$groups[$o]\">$groups[$o]</option>\n";}
	$o++;
	}
$HTML_text.="</SELECT>\n";

$HTML_text.="</TD><TD VALIGN=TOP>Teams/User Groups:<BR>";
$HTML_text.="<SELECT SIZE=5 NAME=user_group[] multiple>\n";

if  (eregi("--ALL--",$user_group_string))
	{$HTML_text.="<option value=\"--ALL--\" selected>-- ALL USER GROUPS --</option>\n";}
else
	{$HTML_text.="<option value=\"--ALL--\">-- ALL USER GROUPS --</option>\n";}
$o=0;
while ($user_groups_to_print > $o)
	{
	if  (eregi("\|$user_groups[$o]\|",$user_group_string)) 
		{$HTML_text.="<option selected value=\"$user_groups[$o]\">$user_groups[$o]</option>\n";}
	else 
		{$HTML_text.="<option value=\"$user_groups[$o]\">$user_groups[$o]</option>\n";}
	$o++;
	}
$HTML_text.="</SELECT>\n";
$HTML_text.="</TD>\n";

$HTML_text.="<TD VALIGN=TOP> Show additional statuses:<BR>";
$HTML_text.="<SELECT SIZE=5 NAME=call_status[] multiple>\n";
if (!$call_status || $call_status_ct==0) 
	{$HTML_text.="<option selected value=\"--NONE--\">-- NO ADDITIONAL STATUSES --</option>\n";}
else
	{$HTML_text.="<option value=\"--NONE--\">-- NO ADDITIONAL STATUSES --</option>\n";}
$o=0;
while ($call_statuses_to_print > $o)
	{
	if (preg_match("/^$call_statuses[$o]\||\|$call_statuses[$o]\|/i",$call_status_string) && strlen($call_status_string)>0) 
		{$HTML_text.="<option selected value=\"$call_statuses[$o]\">$call_statuses[$o] - $call_statuses_names[$o]</option>\n";}
	else 
		{$HTML_text.="<option value=\"$call_statuses[$o]\">$call_statuses[$o] - $call_statuses_names[$o]</option>\n";}
	$o++;
	}
$HTML_text.="</SELECT></TD>\n";


$HTML_text.="<TD VALIGN=TOP>\n";
$HTML_text.="Display as:<BR>";
$HTML_text.="<select name='report_display_type'>";
if ($report_display_type) {$HTML_text.="<option value='$report_display_type' selected>$report_display_type</option>";}
$HTML_text.="<option value='TEXT'>TEXT</option><option value='HTML'>HTML</option></select>\n<BR><BR>";
$HTML_text.="<INPUT TYPE=SUBMIT NAME=SUBMIT VALUE=SUBMIT>\n";
$HTML_text.="</TD><TD VALIGN=TOP> &nbsp; &nbsp; &nbsp; &nbsp; ";

$HTML_text.="<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n";
$HTML_text.="<a href=\"$PHP_SELF?DB=$DB&query_date=$query_date&end_date=$end_date&query_date_D=$query_date_D&query_date_T=$query_date_T&end_date_D=$end_date_D&end_date_T=$end_date_T$groupQS$user_groupQS$call_statusQS&file_download=1&SUBMIT=$SUBMIT\">DOWNLOAD</a> |";
$HTML_text.=" <a href=\"./admin.php?ADD=999999\">REPORTS</a> </FONT>\n";
$HTML_text.="</FONT>\n";
$HTML_text.="</TD></TR></TABLE>";
$HTML_text.="</FORM>\n\n";

if ($SUBMIT=="SUBMIT") 
	{
	# Sale counts per rep 
	$stmt="select max(event_time), vicidial_agent_log.user, vicidial_agent_log.lead_id, vicidial_list.status as current_status from vicidial_agent_log, vicidial_list where event_time>='$query_date' and event_time<='$end_date' $group_SQL and vicidial_agent_log.status in (select status from vicidial_campaign_statuses where sale='Y' $group_SQL UNION select status from vicidial_statuses where sale='Y') and vicidial_agent_log.lead_id=vicidial_list.lead_id group by vicidial_agent_log.user, vicidial_agent_log.lead_id";
	if ($DB) {$ASCII_text.="$stmt\n";}
	$rslt=mysql_query($stmt, $link);
	while ($row=mysql_fetch_array($rslt)) 
		{
		$lead_id=$row["lead_id"];
		$user=$row["user"];
		$current_status=$row["current_status"];
		if (eregi("QCCANC", $current_status)) 
			{
			$cancel_array[$row["user"]]++;
			} 
		else if (eregi("QCFAIL", $current_status)) 
			{
			$incomplete_array[$row["user"]]++;
			} 
		else 
			{
			$sale_array[$row["user"]]++;

			# Get actual talk time for all calls made by the user for this particular lead. If cancelled and incomplete sales are to have their times 
			# counted towards sales talk time, move the below lines OUTSIDE the curly bracket below, so the query runs regardless of what "type" of 
			# sale it is.
			$sale_time_stmt="select sum(talk_sec)-sum(dead_sec) from vicidial_agent_log where user='$user' and lead_id='$lead_id' $group_SQL";
			if ($DB) {$ASCII_text.="$sale_time_stmt\n";}
			$sale_time_rslt=mysql_query($sale_time_stmt, $link);
			$sale_time_row=mysql_fetch_row($sale_time_rslt);
			$sales_talk_time_array[$row["user"]]+=$sale_time_row[0];
			}
		}

	$HTML_text.="<PRE><FONT SIZE=2>";
	$total_average_sale_time=0;
	$total_average_contact_time=0;
	$total_talk_time=0; 
	$total_system_time=0; 
	$total_calls=0;	
	$total_leads=0;
	$total_contacts=0;
	$total_sales=0;
	$total_inc_sales=0;
	$total_cnc_sales=0;
	$total_callbacks=0;
	$total_stcall=0;
	for ($q=0; $q<count($call_status); $q++) {
		$call_status_totals_grand_total[$q]=0;
		$GRAPH2.="<th class='column_header grey_graph_cell' id='teamTotalgraph".($q+17)."'><a href='#' onClick=\"DrawTotalGraph('".$call_status[$q]."', '".($q+17)."'); return false;\">".$call_status[$q]."</a></th>";
	}
	$total_graph_stats[]="";
	$max_totalcalls=1;
	$max_totalleads=1;
	$max_totalcontacts=1;
	$max_totalcontactratio=1;
	$max_totalsystemtime=1;
	$max_totaltalktime=1;
	$max_totalsales=1;
	$max_totalsalesleadsratio=1;
	$max_totalsalescontactsratio=1;
	$max_totalsalesperhour=1;
	$max_totalincsales=1;
	$max_totalcancelledsales=1;
	$max_totalcallbacks=1;
	$max_totalfirstcall=1;
	$max_totalavgsaletime=1;
	$max_totalavgcontacttime=1;
	$TOTALGRAPH="<BR><BR><a name='teamTotalgraph'/><table border='0' cellpadding='0' cellspacing='2' width='800'>";
	$TOTALGRAPH2="<tr><th class='column_header grey_graph_cell' id='teamTotalgraph1'><a href='#' onClick=\"DrawTotalGraph('CALLS', '1'); return false;\">CALLS</a></th><th class='column_header grey_graph_cell' id='teamTotalgraph2'><a href='#' onClick=\"DrawTotalGraph('LEADS', '2'); return false;\">LEADS</a></th><th class='column_header grey_graph_cell' id='teamTotalgraph3'><a href='#' onClick=\"DrawTotalGraph('CONTACTS', '3'); return false;\">CONTACTS</a></th><th class='column_header grey_graph_cell' id='teamTotalgraph4'><a href='#' onClick=\"DrawTotalGraph('CONTACTRATIO','4'); return false;\">CONTACT RATIO</a></th><th class='column_header grey_graph_cell' id='teamTotalgraph5'><a href='#' onClick=\"DrawTotalGraph('SYSTEMTIME', '5'); return false;\">SYSTEM TIME</a></th><th class='column_header grey_graph_cell' id='teamTotalgraph6'><a href='#' onClick=\"DrawTotalGraph('TALKTIME', '6'); return false;\">TALK TIME</a></th><th class='column_header grey_graph_cell' id='teamTotalgraph7'><a href='#' onClick=\"DrawTotalGraph('SALES', '7'); return false;\">SALES</a></th><th class='column_header grey_graph_cell' id='teamTotalgraph8'><a href='#' onClick=\"DrawTotalGraph('SALESLEADSRATIO', '8'); return false;\">SALES TO LEADS RATIO</a></th><th class='column_header grey_graph_cell' id='teamTotalgraph9'><a href='#' onClick=\"DrawTotalGraph('SALESCONTACTSRATIO', '9'); return false;\">SALES TO CONTACTS RATIO</a></th><th class='column_header grey_graph_cell' id='teamTotalgraph10'><a href='#' onClick=\"DrawTotalGraph('SALESPERHOUR', '10'); return false;\">SALES PER HOUR</a></th><th class='column_header grey_graph_cell' id='teamTotalgraph11'><a href='#' onClick=\"DrawTotalGraph('INCSALES', '11'); return false;\">INCOMPLETE SALES</a></th><th class='column_header grey_graph_cell' id='teamTotalgraph12'><a href='#' onClick=\"DrawTotalGraph('CANCELLEDSALES', '12'); return false;\">CANCELLED SALES</a></th><th class='column_header grey_graph_cell' id='teamTotalgraph13'><a href='#' onClick=\"DrawTotalGraph('CALLBACKS', '13'); return false;\">CALLBACKS</a></th><th class='column_header grey_graph_cell' id='teamTotalgraph14'><a href='#' onClick=\"DrawTotalGraph('FIRSTCALLS', '14'); return false;\">FIRST CALLS</a></th><th class='column_header grey_graph_cell' id='teamTotalgraph15'><a href='#' onClick=\"DrawTotalGraph('AVGSALETIME', '15'); return false;\">AVG SALE TIME</a></th><th class='column_header grey_graph_cell' id='teamTotalgraph16'><a href='#' onClick=\"DrawTotalGraph('AVGCONTACTTIME', '16'); return false;\">AVG CONTACT TIME</a></th>".$GRAPH2."</TR>";
	$TOTALGRAPH3="<tr><td colspan='".(16+count($call_status))."' class='graph_span_cell'><span id='team_Total_graph'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";
	$TOTALGRAPH_header="<table cellspacing='0' cellpadding='0' class='horizontalgraph'><caption align='top'>CALL CENTER TOTAL</caption><tr><th class='thgraph' scope='col'>USER</th>";
	$TOTALCALLS_graph=$TOTALGRAPH_header."<th class='thgraph' scope='col'>CALLS</th></tr>";
	$TOTALLEADS_graph=$TOTALGRAPH_header."<th class='thgraph' scope='col'>LEADS</th></tr>";
	$TOTALCONTACTS_graph=$TOTALGRAPH_header."<th class='thgraph' scope='col'>CONTACTS</th></tr>";
	$TOTALCONTACTRATIO_graph=$TOTALGRAPH_header."<th class='thgraph' scope='col'>CONTACT RATIO</th></tr>";
	$TOTALSYSTEMTIME_graph=$TOTALGRAPH_header."<th class='thgraph' scope='col'>SYSTEM TIME</th></tr>";
	$TOTALTALKTIME_graph=$TOTALGRAPH_header."<th class='thgraph' scope='col'>TALK TIME</th></tr>";
	$TOTALSALES_graph=$TOTALGRAPH_header."<th class='thgraph' scope='col'>SALES</th></tr>";
	$TOTALSALESLEADSRATIO_graph=$TOTALGRAPH_header."<th class='thgraph' scope='col'>SALES TO LEADS RATIO</th></tr>";
	$TOTALSALESCONTACTSRATIO_graph=$TOTALGRAPH_header."<th class='thgraph' scope='col'>SALES TO CONTACTS RATIO</th></tr>";
	$TOTALSALESPERHOUR_graph=$TOTALGRAPH_header."<th class='thgraph' scope='col'>SALES PER HOUR</th></tr>";
	$TOTALINCSALES_graph=$TOTALGRAPH_header."<th class='thgraph' scope='col'>INCOMPLETE SALES</th></tr>";
	$TOTALCANCELLEDSALES_graph=$TOTALGRAPH_header."<th class='thgraph' scope='col'>CANCELLED SALES</th></tr>";
	$TOTALCALLBACKS_graph=$TOTALGRAPH_header."<th class='thgraph' scope='col'>CALLBACKS</th></tr>";
	$TOTALFIRSTCALLS_graph=$TOTALGRAPH_header."<th class='thgraph' scope='col'>FIRST CALLS</th></tr>";
	$TOTALAVGSALETIME_graph=$TOTALGRAPH_header."<th class='thgraph' scope='col'>AVG SALE TIME</th></tr>";
	$TOTALAVGCONTACTTIME_graph=$TOTALGRAPH_header."<th class='thgraph' scope='col'>AVG CONTACT TIME</th></tr>";
	for ($q=0; $q<count($call_status); $q++) {
		$totalvar_name="TOTAL".$call_status[$q]."_graph";
		$$totalvar_name=$TOTALGRAPH_header."<th class='thgraph' scope='col'>".$call_status[$q]."</th></tr>";
	}

	for($i=0; $i<$user_group_ct; $i++) 
		{
		$group_average_sale_time=0;
		$group_average_contact_time=0;
		$group_talk_time=0; 
		$group_system_time=0; 
		$group_nonpause_time=0;
		$group_calls=0;	
		$group_leads=0;
		$group_contacts=0;
		$group_sales=0;
		$group_inc_sales=0;
		$group_cnc_sales=0;
		$group_callbacks=0;
		$group_stcall=0;
		$name_stmt="select group_name from vicidial_user_groups where user_group='$user_group[$i]'";
		$name_rslt=mysql_query($name_stmt, $link);
		$name_row=mysql_fetch_row($name_rslt);
		$group_name=$name_row[0];
		for ($q=0; $q<count($call_status); $q++) {
			$call_status_group_totals[$q]=0;
		}

		$ASCII_text.="--- <B>TEAM: $user_group[$i] - $group_name</B>\n";
		$CSV_text.="\"\",\"TEAM: $user_group[$i] - $group_name\"\n";
		$GRAPH_text.="<B>TEAM: $user_group[$i] - $group_name</B>";

		#### USER COUNTS
		$user_stmt="select distinct vicidial_users.full_name, vicidial_users.user from vicidial_users, vicidial_agent_log where vicidial_users.user_group='$user_group[$i]' and vicidial_users.user=vicidial_agent_log.user and vicidial_agent_log.user_group='$user_group[$i]'  and vicidial_agent_log.event_time>='$query_date' and vicidial_agent_log.event_time<='$end_date' and vicidial_agent_log.campaign_id in ($group_SQL_str) order by full_name, user";
		if ($DB) {$ASCII_text.="$user_stmt\n";}
		$user_rslt=mysql_query($user_stmt, $link);
		if (mysql_num_rows($user_rslt)>0) 
			{
			$graph_stats=array();
			$max_calls=1;
			$max_leads=1;
			$max_contacts=1;
			$max_contactratio=1;
			$max_systemtime=1;
			$max_talktime=1;
			$max_sales=1;
			$max_salesleadsratio=1;
			$max_salescontactsratio=1;
			$max_salesperhour=1;
			$max_incsales=1;
			$max_cancelledsales=1;
			$max_callbacks=1;
			$max_firstcall=1;
			$max_avgsaletime=1;
			$max_avgcontacttime=1;
			$GRAPH="<BR><BR><a name='team".$user_group[$i]."graph'/><table border='0' cellpadding='0' cellspacing='2' width='800'>";
			$STATGRAPH="";
			for ($q=0; $q<count($call_status); $q++) {
				$STATGRAPH.="<th class='column_header grey_graph_cell' width='6%' id='team".$user_group[$i]."graph".($q+17)."'><a href='#' onClick=\"Draw".$user_group[$i]."Graph('".$call_status[$q]."', '".($q+17)."'); return false;\">".$call_status[$q]."</a></th>";
				$max_varname="max_".$call_status[$q];
				$$max_varname=1;
			}

			$GRAPH2="<tr><th class='column_header grey_graph_cell' width='6%' id='team".$user_group[$i]."graph1'><a href='#' onClick=\"Draw".$user_group[$i]."Graph('CALLS', '1'); return false;\">CALLS</a></th><th class='column_header grey_graph_cell' width='6%'  id='team".$user_group[$i]."graph2'><a href='#' onClick=\"Draw".$user_group[$i]."Graph('LEADS', '2'); return false;\">LEADS</a></th><th class='column_header grey_graph_cell' width='6%' id='team".$user_group[$i]."graph3' ><a href='#' onClick=\"Draw".$user_group[$i]."Graph('CONTACTS', '3'); return false;\">CONTACTS</a></th><th class='column_header grey_graph_cell' width='6%'  id='team".$user_group[$i]."graph4'><a href='#' onClick=\"Draw".$user_group[$i]."Graph('CONTACTRATIO', '4'); return false;\">CONTACT RATIO</a></th><th class='column_header grey_graph_cell' width='6%'  id='team".$user_group[$i]."graph5'><a href='#' onClick=\"Draw".$user_group[$i]."Graph('SYSTEMTIME', '5'); return false;\">SYSTEM TIME</a></th><th class='column_header grey_graph_cell' width='6%' id='team".$user_group[$i]."graph6'><a href='#' onClick=\"Draw".$user_group[$i]."Graph('TALKTIME', '6'); return false;\">TALK TIME</a></th><th class='column_header grey_graph_cell' width='6%' id='team".$user_group[$i]."graph7'><a href='#' onClick=\"Draw".$user_group[$i]."Graph('SALES', '7'); return false;\">SALES</a></th><th class='column_header grey_graph_cell' width='6%' id='team".$user_group[$i]."graph8'><a href='#' onClick=\"Draw".$user_group[$i]."Graph('SALESLEADSRATIO', '8'); return false;\">SALES TO LEADS RATIO</a></th><th class='column_header grey_graph_cell' width='7%' id='team".$user_group[$i]."graph9'><a href='#' onClick=\"Draw".$user_group[$i]."Graph('SALESCONTACTSRATIO', '9'); return false;\">SALES TO CONTACTS RATIO</a></th><th class='column_header grey_graph_cell' width='6%' id='team".$user_group[$i]."graph10'><a href='#' onClick=\"Draw".$user_group[$i]."Graph('SALESPERHOUR', '10'); return false;\">SALES PER HOUR</a></th><th class='column_header grey_graph_cell' width='7%' id='team".$user_group[$i]."graph11'><a href='#' onClick=\"Draw".$user_group[$i]."Graph('INCSALES', '11'); return false;\">INCOMPLETE SALES</a></th><th class='column_header grey_graph_cell' width='7%' id='team".$user_group[$i]."graph12'><a href='#' onClick=\"Draw".$user_group[$i]."Graph('CANCELLEDSALES', '12'); return false;\">CANCELLED SALES</a></th><th class='column_header grey_graph_cell' width='7%' id='team".$user_group[$i]."graph13'><a href='#' onClick=\"Draw".$user_group[$i]."Graph('CALLBACKS', '13'); return false;\">CALLBACKS</a></th><th class='column_header grey_graph_cell' width='6%' id='team".$user_group[$i]."graph14'><a href='#' onClick=\"Draw".$user_group[$i]."Graph('FIRSTCALLS', '14'); return false;\">FIRST CALLS</a></th><th class='column_header grey_graph_cell' width='6%' id='team".$user_group[$i]."graph15'><a href='#' onClick=\"Draw".$user_group[$i]."Graph('AVGSALETIME', '15'); return false;\">AVG SALE TIME</a></th><th class='column_header grey_graph_cell' width='6%' id='team".$user_group[$i]."graph16'><a href='#' onClick=\"Draw".$user_group[$i]."Graph('AVGCONTACTTIME', '16'); return false;\">AVG CONTACT TIME</a></th>".$STATGRAPH."</TR>";
			$GRAPH3="<tr><td colspan='".(16+count($call_status))."' class='graph_span_cell'><span id='team_".$user_group[$i]."_graph'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";
			$graph_header="<table cellspacing='0' cellpadding='0' class='horizontalgraph'><caption align='top'>TEAM: $user_group[$i] - $group_name</caption><tr><th class='thgraph' scope='col'>USER</th>";
			$CALLS_graph=$graph_header."<th class='thgraph' scope='col'>CALLS</th></tr>";
			$LEADS_graph=$graph_header."<th class='thgraph' scope='col'>LEADS</th></tr>";
			$CONTACTS_graph=$graph_header."<th class='thgraph' scope='col'>CONTACTS</th></tr>";
			$CONTACTRATIO_graph=$graph_header."<th class='thgraph' scope='col'>CONTACT RATIO</th></tr>";
			$SYSTEMTIME_graph=$graph_header."<th class='thgraph' scope='col'>SYSTEM TIME</th></tr>";
			$TALKTIME_graph=$graph_header."<th class='thgraph' scope='col'>TALK TIME</th></tr>";
			$SALES_graph=$graph_header."<th class='thgraph' scope='col'>SALES</th></tr>";
			$SALESLEADSRATIO_graph=$graph_header."<th class='thgraph' scope='col'>SALES TO LEADS RATIO</th></tr>";
			$SALESCONTACTSRATIO_graph=$graph_header."<th class='thgraph' scope='col'>SALES TO CONTACTS RATIO</th></tr>";
			$SALESPERHOUR_graph=$graph_header."<th class='thgraph' scope='col'>SALES PER HOUR</th></tr>";
			$INCSALES_graph=$graph_header."<th class='thgraph' scope='col'>INCOMPLETE SALES</th></tr>";
			$CANCELLEDSALES_graph=$graph_header."<th class='thgraph' scope='col'>CANCELLED SALES</th></tr>";
			$CALLBACKS_graph=$graph_header."<th class='thgraph' scope='col'>CALLBACKS</th></tr>";
			$FIRSTCALLS_graph=$graph_header."<th class='thgraph' scope='col'>FIRST CALLS</th></tr>";
			$AVGSALETIME_graph=$graph_header."<th class='thgraph' scope='col'>AVG SALE TIME</th></tr>";
			$AVGCONTACTTIME_graph=$graph_header."<th class='thgraph' scope='col'>AVG CONTACT TIME</th></tr>";
			for ($q=0; $q<count($call_status); $q++) {
				$varname=$call_status[$q]."_graph";
				$$varname=$graph_header."<th class='thgraph' scope='col'>".$call_status[$q]."</th></tr>";
			}

			$j=0;
			$ASCII_text.="+------------------------------------------+------------+-------+-------+----------+---------------+---------------+-------------+-----------+-------+------------------------+----------------------+-------------------------+----------------+------------------+-----------------+-----------+-----------------------+-------------------+----------------------+$HTMLborderheader\n";
			$ASCII_text.="| Agent Name                               | Agent ID   | Calls | Leads | Contacts | Contact Ratio | Nonpause Time | System Time | Talk Time | Sales | Sales per Working Hour | Sales to Leads Ratio | Sales to Contacts Ratio | Sales Per Hour | Incomplete Sales | Cancelled Sales | Callbacks | First Call Resolution | Average Sale Time | Average Contact Time |$HTMLstatusheader\n";
			$ASCII_text.="+------------------------------------------+------------+-------+-------+----------+---------------+---------------+-------------+-----------+-------+------------------------+----------------------+-------------------------+----------------+------------------+-----------------+-----------+-----------------------+-------------------+----------------------+$HTMLborderheader\n";
			$CSV_text.="\"\",\"Agent Name\",\"Agent ID\",\"Calls\",\"Leads\",\"Contacts\",\"Contact Ratio\",\"Nonpause Time\",\"System Time\",\"Talk Time\",\"Sales\",\"Sales per Working Hour\",\"Sales to Leads Ratio\",\"Sales to Contacts Ratio\",\"Sales Per Hour\",\"Incomplete Sales\",\"Cancelled Sales\",\"Callbacks\",\"First Call Resolution\",\"Average Sale Time\",\"Average Contact Time\"$CSVstatusheader\n";
			while ($user_row=mysql_fetch_array($user_rslt)) 
				{
				$j++;
				$contacts=0;
				$callbacks=0;
				$stcall=0;
				$calls=0;
				$leads=0;
				$system_time=0;
				$talk_time=0;
				$nonpause_time=0;
				# For each user
				$user=$user_row["user"];
				$sale_array[$user]+=0;  # For agents with no sales logged
				$incomplete_array[$user]+=0;  # For agents with no QCFAIL logged
				$cancel_array[$user]+=0;  # For agents with no QCCANC logged

				# Leads 
				$lead_stmt="select count(distinct lead_id) from vicidial_agent_log where lead_id is not null and event_time>='$query_date' and event_time<='$end_date' $group_SQL and user='$user' and user_group='$user_group[$i]'";
				if ($DB) {$ASCII_text.="$lead_stmt\n";}
				$lead_rslt=mysql_query($lead_stmt, $link);
				$lead_row=mysql_fetch_row($lead_rslt);
				$leads=$lead_row[0];

				# Callbacks 
				$callback_stmt="select count(*) from vicidial_callbacks where status in ('ACTIVE', 'LIVE') $group_SQL and user='$user' and user_group='$user_group[$i]'";
				if ($DB) {$ASCII_text.="$callback_stmt\n";}
				$callback_rslt=mysql_query($callback_stmt, $link);
				$callback_row=mysql_fetch_row($callback_rslt);
				$callbacks=$callback_row[0];

				$stat_stmt="select val.status, val.sub_status, vs.customer_contact, sum(val.talk_sec), sum(val.pause_sec), sum(val.wait_sec), sum(val.dispo_sec), sum(val.dead_sec), count(*) from vicidial_agent_log val, vicidial_statuses vs where val.user='$user' and val.user_group='$user_group[$i]' and val.event_time>='$query_date' and val.event_time<='$end_date' and val.status=vs.status and vs.status in (select status from vicidial_statuses) and val.campaign_id in ($group_SQL_str) group by status, customer_contact UNION select val.status, val.sub_status, vs.customer_contact, sum(val.talk_sec), sum(val.pause_sec), sum(val.wait_sec), sum(val.dispo_sec), sum(val.dead_sec), count(*) from vicidial_agent_log val, vicidial_campaign_statuses vs where val.campaign_id in ($group_SQL_str) and val.user='$user' and val.user_group='$user_group[$i]' and val.event_time>='$query_date' and val.event_time<='$end_date' and val.status=vs.status and val.campaign_id=vs.campaign_id and vs.status in (select distinct status from vicidial_campaign_statuses where ".substr($group_SQL, 4).") group by status, customer_contact";
				if ($DB) {$ASCII_text.="$stat_stmt\n";}
				$stat_rslt=mysql_query($stat_stmt, $link);
				while ($stat_row=mysql_fetch_row($stat_rslt)) 
					{
					if ($stat_row[2]=="Y") 
						{
						$contacts+=$stat_row[8]; 
						$contact_talk_time+=($stat_row[3]-$stat_row[7]);

						$group_contact_talk_time+=($stat_row[3]-$stat_row[7]);
						}
					# if ($stat_row[2]=="Y") {$callbacks+=$stat_row[8];}
					$calls+=$stat_row[8];
					$talk_time+=($stat_row[3]-$stat_row[7]);
					$system_time+=($stat_row[3]+$stat_row[5]+$stat_row[6]);
					$nonpause_time+=($stat_row[3]+$stat_row[5]+$stat_row[6]);
					if ($stat_row[1]=="PRECAL") 
						{
						$nonpause_time+=$stat_row[4];
						}
					}
				$user_talk_time =		sec_convert($talk_time,'H'); 
				$group_talk_time+=$talk_time;
				$user_system_time =		sec_convert($system_time,'H'); 
				$talk_hours=$talk_time/3600;
				$group_system_time+=$system_time;
				$user_nonpause_time =		sec_convert($nonpause_time,'H'); 
				$group_nonpause_time+=$nonpause_time;

				if ($sale_array[$user]>0) {$average_sale_time=sec_convert(round($sales_talk_time_array[$user]/$sale_array[$user]), 'H');} else {$average_sale_time="00:00";}
				$group_sales_talk_time+=$sales_talk_time_array[$user];
				if ($contacts>0) {$average_contact_time=sec_convert(round($contact_talk_time/$contacts), 'H');} else {$average_contact_time="00:00";}

				$ASCII_text.="| ".sprintf("%-40s", $user_row["full_name"]);
				$ASCII_text.=" | <a href='user_stats.php?user=$user&begin_date=$query_date_D&end_date=$end_date_D'>".sprintf("%10s", "$user")."</a>";
				$ASCII_text.=" | ".sprintf("%5s", $calls);	$group_calls+=$calls;
				$ASCII_text.=" | ".sprintf("%5s", $leads);	$group_leads+=$leads;
				$ASCII_text.=" | ".sprintf("%8s", $contacts);  $group_contacts+=$contacts;
				if ($leads>0) 
					{
					$contact_ratio=sprintf("%.2f", (100*$contacts/$leads));
					}
				else 
					{
					$contact_ratio="0.00";
					}
				$ASCII_text.=" | ".sprintf("%12s", $contact_ratio)."%";
				$ASCII_text.=" | ".sprintf("%13s", $user_nonpause_time);
				$ASCII_text.=" | ".sprintf("%11s", $user_system_time);
				$ASCII_text.=" | ".sprintf("%9s", $user_talk_time);
				$ASCII_text.=" | ".sprintf("%5s", $sale_array[$user]);	$group_sales+=$sale_array[$user];
				if ($nonpause_time>0) 
					{
					$sales_per_working_hours=sprintf("%.2f", ($sale_array[$user]/($nonpause_time/3600)));
					}
				else
					{
					$sales_per_working_hours="0.00";
					}
				$ASCII_text.=" | ".sprintf("%22s", $sales_per_working_hours);
				if ($leads>0) 
					{
					$sales_ratio=sprintf("%.2f", (100*$sale_array[$user]/$leads));
					}
				else 
					{
					$sales_ratio="0.00";
					}
				$ASCII_text.=" | ".sprintf("%19s", $sales_ratio)."%";
				if ($contacts>0) 
					{
					$sale_contact_ratio=sprintf("%.2f", (100*$sale_array[$user]/$contacts));
					}
				else 
					{
					$sale_contact_ratio=0;
					}
				$ASCII_text.=" | ".sprintf("%22s", $sale_contact_ratio)."%";
				if ($talk_hours>0) 
					{
					$sales_per_hour=sprintf("%.2f", ($sale_array[$user]/$talk_hours));
					}
				else 
					{
					$sales_per_hour="0.00";
					}
				if ( ($calls>0) and ($leads>0) )
					{
					$stcall=sprintf("%.2f", ($calls/$leads));
					}
				else 
					{
					$stcall="0.00";
					}

				if ($sale_array[$user]>0) {$avg_sale_time=round($sales_talk_time_array[$user]/$sale_array[$user]);} else {$avg_sale_time=0;}
				if ($contacts>0) {$avg_contact_time=round($contact_talk_time/$contacts);} else {$avg_contact_time=0;}
				$graph_stats[$j][0]=$user_row["full_name"]." - $user";
				$graph_stats[$j][1]=trim($calls);
				$graph_stats[$j][2]=trim($leads);
				$graph_stats[$j][3]=trim($contacts);
				$graph_stats[$j][4]=trim($contact_ratio);
				$graph_stats[$j][5]=trim($system_time);
				$graph_stats[$j][6]=trim($talk_time);
				$graph_stats[$j][7]=trim($sale_array[$user]);
				$graph_stats[$j][8]=trim($sales_ratio);
				$graph_stats[$j][9]=trim($sale_contact_ratio);
				$graph_stats[$j][10]=trim($sales_per_hour);
				$graph_stats[$j][11]=trim($incomplete_array[$user]);
				$graph_stats[$j][12]=trim($cancel_array[$user]);
				$graph_stats[$j][13]=trim($callbacks);
				$graph_stats[$j][14]=trim($stcall);
				$graph_stats[$j][15]=trim($avg_sale_time);
				$graph_stats[$j][16]=trim($avg_contact_time);

				if (trim($calls)>$max_calls) {$max_calls=trim($calls);}
				if (trim($leads)>$max_leads) {$max_leads=trim($leads);}
				if (trim($contacts)>$max_contacts) {$max_contacts=trim($contacts);}
				if (trim($contact_ratio)>$max_contactratio) {$max_contactratio=trim($contact_ratio);}
				if (trim($system_time)>$max_systemtime) {$max_systemtime=trim($system_time);}
				if (trim($talk_time)>$max_talktime) {$max_talktime=trim($talk_time);}
				if (trim($sale_array[$user])>$max_sales) {$max_sales=trim($sale_array[$user]);}
				if (trim($sales_ratio)>$max_salesleadsratio) {$max_salesleadsratio=trim($sales_ratio);}
				if (trim($sale_contact_ratio)>$max_salescontactsratio) {$max_salescontactsratio=trim($sale_contact_ratio);}
				if (trim($sales_per_hour)>$max_salesperhour) {$max_salesperhour=trim($sales_per_hour);}
				if (trim($incomplete_array[$user])>$max_incsales) {$max_incsales=trim($incomplete_array[$user]);}
				if (trim($cancel_array[$user])>$max_cancelledsales) {$max_cancelledsales=trim($cancel_array[$user]);}
				if (trim($callbacks)>$max_callbacks) {$max_callbacks=trim($callbacks);}
				if (trim($stcall)>$max_firstcall) {$max_firstcall=trim($stcall);}
				if (trim($avg_sale_time)>$max_avgsaletime) {$max_avgsaletime=trim($avg_sale_time);}
				if (trim($avg_contact_time)>$max_avgcontacttime) {$max_avgcontacttime=trim($avg_contact_time);}

				$ASCII_text.=" | ".sprintf("%14s", $sales_per_hour);
				$ASCII_text.=" | ".sprintf("%16s", $incomplete_array[$user]);  $group_inc_sales+=$incomplete_array[$user];
				$ASCII_text.=" | ".sprintf("%15s", $cancel_array[$user]);  $group_cnc_sales+=$cancel_array[$user];
				$ASCII_text.=" | ".sprintf("%9s", $callbacks);  $group_callbacks+=$callbacks;
				$ASCII_text.=" | ".sprintf("%21s", $stcall);	# first call resolution
				$ASCII_text.=" | ".sprintf("%17s", $average_sale_time);
				$ASCII_text.=" | ".sprintf("%20s", $average_contact_time)." |";

				$CSV_status_text="";
				for ($q=0; $q<count($call_status); $q++) {
					$stat_stmt="select sum(stat_ct) from (select count(distinct uniqueid) as stat_ct From vicidial_agent_log val, vicidial_statuses vs where val.user='$user' and val.user_group='$user_group[$i]' and val.event_time>='$query_date' and val.event_time<='$end_date' and val.status=vs.status and vs.status='$call_status[$q]' and val.campaign_id in ($group_SQL_str) UNION select count(distinct uniqueid) as stat_ct From vicidial_agent_log val, vicidial_campaign_statuses vs where val.user='$user' and val.user_group='$user_group[$i]' and val.event_time>='$query_date' and val.event_time<='$end_date' and val.status=vs.status and vs.status='$call_status[$q]' and val.campaign_id in ($group_SQL_str)) as counts";
					$stat_rslt=mysql_query($stat_stmt, $link);
					$stat_row=mysql_fetch_row($stat_rslt);
					$ASCII_text.=" ".sprintf("%6s", $stat_row[0])." |";
					$CSV_status_text.=",\"$stat_row[0]\"";
					$call_status_group_totals[$q]+=$stat_row[0];
					$graph_stats[$j][(17+$q)]=$stat_row[0];
					
					$varname=$Sstatus."_graph";
					$$varname=$graph_header."<th class='thgraph' scope='col'>$Sstatus</th></tr>";

					$max_varname="max_".$call_status[$q];
					if ($stat_row[0]>$$max_varname) {$$max_varname=$stat_row[0];}
				}
				$ASCII_text.="\n";

				$CSV_text.="\"$j\",\"$user_row[full_name]\",\"$user\",\"$calls\",\"$leads\",\"$contacts\",\"$contact_ratio %\",\"$user_nonpause_time\",\"$user_system_time\",\"$user_talk_time\",\"$sale_array[$user]\",\"$sales_per_working_hours\",\"$sales_ratio\",\"$sale_contact_ratio\",\"$sales_per_hour\",\"$incomplete_array[$user]\",\"$cancel_array[$user]\",\"$callbacks\",\"$stcall\",\"$average_sale_time\",\"$average_contact_time\"$CSV_status_text\n";
				}

			##### GROUP TOTALS #############
			if ($group_sales>0) 
				{
				$group_average_sale_time=sec_convert(round($group_sales_talk_time/$group_sales), 'H');
				} 
			else 
				{
				$group_average_sale_time="00:00:00";
				}
			if ($group_contacts>0) 
				{
				$group_average_contact_time=sec_convert(round($group_contact_talk_time/$group_contacts), 'H');
				} 
			else 
				{
				$group_average_contact_time="00:00:00";
				}
			$group_talk_hours=$group_talk_time/3600;

			$GROUP_text.="| ".sprintf("%40s", "$group_name");
			$GROUP_text.=" | ".sprintf("%10s", "$user_group[$i]");
			$total_graph_stats[$i][0]="$user_group[$i] - $group_name";

			$ASCII_text.="+------------------------------------------+------------+-------+-------+----------+---------------+---------------+-------------+-----------+-------+------------------------+----------------------+-------------------------+----------------+------------------+-----------------+-----------+-----------------------+-------------------+----------------------+$HTMLborderheader\n";
			$ASCII_text.="| ".sprintf("%40s", "");
			$ASCII_text.=" | ".sprintf("%10s", "TOTALS:");

			$TOTAL_text=" | ".sprintf("%5s", $group_calls);	
			$TOTAL_text.=" | ".sprintf("%5s", $group_leads);
			$TOTAL_text.=" | ".sprintf("%8s", $group_contacts);
			if ($group_leads>0) 
				{
				$group_contact_ratio=sprintf("%.2f", (100*$group_contacts/$group_leads));
				} 
			else 
				{
				$group_contact_ratio="0.00";
				}
			$TOTAL_text.=" | ".sprintf("%12s", $group_contact_ratio)."%";
			$TOTAL_text.=" | ".sprintf("%13s", sec_convert($group_nonpause_time,'H'));
			$TOTAL_text.=" | ".sprintf("%11s", sec_convert($group_system_time,'H'));
			$TOTAL_text.=" | ".sprintf("%9s", sec_convert($group_talk_time,'H'));
			$TOTAL_text.=" | ".sprintf("%5s", $group_sales);
			if ($group_nonpause_time>0) 
				{
				$sales_per_working_hours=sprintf("%.2f", ($group_sales/($group_nonpause_time/3600)));
				}
			else
				{
				$sales_per_working_hours="0.00";
				}
			$TOTAL_text.=" | ".sprintf("%22s", $sales_per_working_hours);
			if ($group_leads>0) 
				{
				$group_sales_ratio=sprintf("%.2f", (100*$group_sales/$group_leads));
				} 
			else 
				{
				$group_sales_ratio="0.00";
				}	
			$TOTAL_text.=" | ".sprintf("%19s", $group_sales_ratio)."%";
			if ($group_contacts>0) 
				{
				$group_sale_contact_ratio=sprintf("%.2f", (100*$group_sales/$group_contacts));
				} 
			else 
				{
				$group_sale_contact_ratio=0;
				}
			$TOTAL_text.=" | ".sprintf("%22s", $group_sale_contact_ratio)."%";
			if ($group_talk_hours>0) 
				{
				$group_sales_per_hour=sprintf("%.2f", ($group_sales/$group_talk_hours));
				} 
			else 
				{
				$group_sales_per_hour="0.00";
				}
			if ( ($group_calls>0) and ($group_leads>0) )
				{
				$group_stcall=sprintf("%.2f", ($group_calls/$group_leads));
				} 
			else 
				{
				$group_stcall="0.00";
				}
			$TOTAL_text.=" | ".sprintf("%14s", $group_sales_per_hour);
			$TOTAL_text.=" | ".sprintf("%16s", $group_inc_sales);
			$TOTAL_text.=" | ".sprintf("%15s", $group_cnc_sales);
			$TOTAL_text.=" | ".sprintf("%9s", $group_callbacks);
			$TOTAL_text.=" | ".sprintf("%21s", $group_stcall); 	# first call resolution
			$TOTAL_text.=" | ".sprintf("%17s", $group_average_sale_time);
			$TOTAL_text.=" | ".sprintf("%20s", $group_average_contact_time)." |";

			$CSV_status_text="";
			for ($q=0; $q<count($call_status_group_totals); $q++) {
				$TOTAL_text.=" ".sprintf("%6s", $call_status_group_totals[$q])." |";
				$call_status_totals_grand_total[$q]+=$call_status_group_totals[$q];
				$CSV_status_text.=",\"$call_status_group_totals[$q]\"";
				$total_var=$call_status[$q]."_total";
				$$total_var=$call_status_group_totals[$q];
				$total_graph_stats[$i][(17+$q)]=$call_status_group_totals[$q];
				$max_varname="max_total".$call_status[$q];
				if ($call_status_group_totals[$q]>$$max_varname) {$$max_varname=$call_status_group_totals[$q];}
			}
			$TOTAL_text.="\n";

			if (trim($group_calls)>$max_totalcalls) {$max_totalcalls=trim($group_calls);}
			if (trim($group_leads)>$max_totalleads) {$max_totalleads=trim($group_leads);}
			if (trim($group_contacts)>$max_totalcontacts) {$max_totalcontacts=trim($group_contacts);}
			if (trim($group_contact_ratio)>$max_totalcontactratio) {$max_totalcontactratio=trim($group_contact_ratio);}
			if (trim($group_system_time)>$max_totalsystemtime) {$max_totalsystemtime=trim($group_system_time);}
			if (trim($group_talk_time)>$max_totaltalktime) {$max_totaltalktime=trim($group_talk_time);}
			if (trim($group_sales)>$max_totalsales) {$max_totalsales=trim($group_sales);}
			if (trim($group_sales_ratio)>$max_totalsalesleadsratio) {$max_totalsalesleadsratio=trim($group_sales_ratio);}
			if (trim($group_sale_contact_ratio)>$max_totalsalescontactsratio) {$max_totalsalescontactsratio=trim($group_sale_contact_ratio);}
			if (trim($group_sales_per_hour)>$max_totalsalesperhour) {$max_totalsalesperhour=trim($group_sales_per_hour);}
			if (trim($group_inc_sales)>$max_totalincsales) {$max_totalincsales=trim($group_inc_sales);}
			if (trim($group_cnc_sales)>$max_totalcancelledsales) {$max_totalcancelledsales=trim($group_cnc_sales);}
			if (trim($group_callbacks)>$max_totalcallbacks) {$max_totalcallbacks=trim($group_callbacks);}
			if (trim($group_stcall)>$max_totalfirstcall) {$max_totalfirstcall=trim($group_stcall);}
			if (trim($group_avg_sale_time)>$max_totalavgsaletime) {$max_totalavgsaletime=trim($group_avg_sale_time);}
			if (trim($group_avg_contact_time)>$max_totalavgcontacttime) {$max_totalavgcontacttime=trim($group_avg_contact_time);}
			$total_graph_stats[$i][1]=$group_calls;
			$total_graph_stats[$i][2]=$group_leads;
			$total_graph_stats[$i][3]=$group_contacts;
			$total_graph_stats[$i][4]=$group_contact_ratio;
			$total_graph_stats[$i][5]=$group_system_time;
			$total_graph_stats[$i][6]=$group_talk_time;
			$total_graph_stats[$i][7]=$group_sales;
			$total_graph_stats[$i][8]=$group_sales_ratio;
			$total_graph_stats[$i][9]=$group_sale_contact_ratio;
			$total_graph_stats[$i][10]=$group_sales_per_hour;
			$total_graph_stats[$i][11]=$group_inc_sales;
			$total_graph_stats[$i][12]=$group_cnc_sales;
			$total_graph_stats[$i][13]=$group_callbacks;
			$total_graph_stats[$i][14]=$group_stcall;
			$total_graph_stats[$i][15]=$group_avg_sale_time;
			$total_graph_stats[$i][16]=$group_avg_contact_time;

			$ASCII_text.=$TOTAL_text;
			$GROUP_text.=$TOTAL_text;

			$ASCII_text.="+------------------------------------------+------------+-------+-------+----------+---------------+---------------+-------------+-----------+-------+------------------------+----------------------+-------------------------+----------------+------------------+-----------------+-----------+-----------------------+-------------------+----------------------+$HTMLborderheader\n";
			$ASCII_text.="\n\n";

			$CSV_text.="\"\",\"\",\"TOTALS:\",\"$group_calls\",\"$group_leads\",\"$group_contacts\",\"$group_contact_ratio %\",\"".sec_convert($group_nonpause_time,'H')."\",\"".sec_convert($group_system_time,'H')."\",\"".sec_convert($group_talk_time,'H')."\",\"$group_sales\",\"$sales_per_working_hours\",\"$group_sales_ratio\",\"$group_sale_contact_ratio\",\"$group_sales_per_hour\",\"$group_inc_sales\",\"$group_cnc_sales\",\"$group_callbacks\",\"$group_stcall\",\"$group_average_sale_time\",\"$group_average_contact_time\"$CSV_status_text\n";
			$GROUP_CSV_text.="\"$i\",\"$group_name\",\"$user_group[$i]\",\"$group_calls\",\"$group_leads\",\"$group_contacts\",\"$group_contact_ratio %\",\"".sec_convert($group_nonpause_time,'H')."\",\"".sec_convert($group_system_time,'H')."\",\"".sec_convert($group_talk_time,'H')."\",\"$group_sales\",\"$sales_per_working_hours\",\"$group_sales_ratio\",\"$group_sale_contact_ratio\",\"$group_sales_per_hour\",\"$group_inc_sales\",\"$group_cnc_sales\",\"$group_callbacks\",\"$group_stcall\",\"$group_average_sale_time\",\"$group_average_contact_time\"$CSV_status_text\n";
			$CSV_text.="\n\n";

			$total_calls+=$group_calls;
			$total_leads+=$group_leads;
			$total_contacts+=$group_contacts;
			$total_system_time+=$group_system_time;
			$total_nonpause_time+=$group_nonpause_time;
			$total_talk_time+=$group_talk_time;
			$total_sales+=$group_sales;
			$total_inc_sales+=$group_inc_sales;
			$total_cnc_sales+=$group_cnc_sales;
			$total_callbacks+=$group_callbacks;
			$total_stcall+=$group_stcall; 	# first call resolution
			$total_sales_talk_time+=$group_sales_talk_time;
			$total_contact_talk_time+=$group_contact_talk_time;

			for ($d=1; $d<=count($graph_stats); $d++) {
				if ($d==1) {$class=" first";} else if ($d==count($graph_stats)) {$class=" last";} else {$class="";}
				$CALLS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][1]/$max_calls)."' height='16' />".$graph_stats[$d][1]."</td></tr>";
				$LEADS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][2]/$max_leads)."' height='16' />".$graph_stats[$d][2]."</td></tr>";
				$CONTACTS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][3]/$max_contacts)."' height='16' />".$graph_stats[$d][3]."</td></tr>";
				$CONTACTRATIO_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][4]/$max_contactratio)."' height='16' />".$graph_stats[$d][4]."%</td></tr>";
				$SYSTEMTIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][5]/$max_systemtime)."' height='16' />".sec_convert($graph_stats[$d][5], 'H')."</td></tr>";
				$TALKTIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][6]/$max_talktime)."' height='16' />".sec_convert($graph_stats[$d][6], 'H')."</td></tr>";
				$SALES_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][7]/$max_sales)."' height='16' />".$graph_stats[$d][7]."</td></tr>";
				$SALESLEADSRATIO_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][8]/$max_salesleadsratio)."' height='16' />".$graph_stats[$d][8]."%</td></tr>";
				$SALESCONTACTSRATIO_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][9]/$max_salescontactsratio)."' height='16' />".$graph_stats[$d][9]."%</td></tr>";
				$SALESPERHOUR_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][10]/$max_salesperhour)."' height='16' />".$graph_stats[$d][10]."</td></tr>";
				$INCSALES_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][11]/$max_incsales)."' height='16' />".$graph_stats[$d][11]."</td></tr>";
				$CANCELLEDSALES_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][12]/$max_cancelledsales)."' height='16' />".$graph_stats[$d][12]."</td></tr>";
				$CALLBACKS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][13]/$max_callbacks)."' height='16' />".$graph_stats[$d][13]."</td></tr>";
				$FIRSTCALLS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][14]/$max_firstcall)."' height='16' />".$graph_stats[$d][14]."</td></tr>";
				$AVGSALETIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][15]/$max_avgsaletime)."' height='16' />".sec_convert($graph_stats[$d][15], 'H')."</td></tr>";
				$AVGCONTACTTIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][16]/$max_avgcontacttime)."' height='16' />".sec_convert($graph_stats[$d][16], 'H')."</td></tr>";			
				for ($e=0; $e<count($call_status); $e++) {
					$Sstatus=$call_status[$e];
					$varname=$Sstatus."_graph";
					$max_varname="max_".$Sstatus;
					# $max.= "<!-- $max_varname => ".$$max_varname." //-->\n";
					
					$$varname.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][($e+17)]/$$max_varname)."' height='16' />".$graph_stats[$d][($e+17)]."</td></tr>";
				}
			}

			$CALLS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($group_calls)."</th></tr></table>";
			$LEADS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($group_leads)."</th></tr></table>";
			$CONTACTS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($group_contacts)."</th></tr></table>";
			$CONTACTRATIO_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($group_contact_ratio)."%</th></tr></table>";
			$SYSTEMTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".sec_convert($group_system_time,'H')."</th></tr></table>";
			$TALKTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".sec_convert($group_talk_time,'H')."</th></tr></table>";
			$SALES_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($group_sales)."</th></tr></table>";
			$SALESLEADSRATIO_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($group_sales_ratio)."%</th></tr></table>";
			$SALESCONTACTSRATIO_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($group_sale_contact_ratio)."%</th></tr></table>";
			$SALESPERHOUR_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($group_sales_per_hour)."</th></tr></table>";
			$INCSALES_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($group_inc_sales)."</th></tr></table>";
			$CANCELLEDSALES_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($group_cnc_sales)."</th></tr></table>";
			$CALLBACKS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($group_callbacks)."</th></tr></table>";
			$FIRSTCALLS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($group_stcall)."</th></tr></table>";
			$AVGSALETIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($group_average_sale_time)."</th></tr></table>";
			$AVGCONTACTTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($group_average_contact_time)."</th></tr></table>";
			for ($e=0; $e<count($call_status); $e++) {
				$Sstatus=$call_status[$e];
				$total_var=$Sstatus."_total";
				$graph_var=$Sstatus."_graph";
				$$graph_var.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($$total_var)."</th></tr></table>";
			}
			$JS_onload.="\tDraw".$user_group[$i]."Graph('CALLS', '1');\n"; 
			$JS_text.="function Draw".$user_group[$i]."Graph(graph, th_id) {\n";
			$JS_text.="	var CALLS_graph=\"$CALLS_graph\";\n";
			$JS_text.="	var LEADS_graph=\"$LEADS_graph\";\n";
			$JS_text.="	var CONTACTS_graph=\"$CONTACTS_graph\";\n";
			$JS_text.="	var CONTACTRATIO_graph=\"$CONTACTRATIO_graph\";\n";
			$JS_text.="	var SYSTEMTIME_graph=\"$SYSTEMTIME_graph\";\n";
			$JS_text.="	var TALKTIME_graph=\"$TALKTIME_graph\";\n";
			$JS_text.="	var SALES_graph=\"$SALES_graph\";\n";
			$JS_text.="	var SALESLEADSRATIO_graph=\"$SALESLEADSRATIO_graph\";\n";
			$JS_text.="	var SALESCONTACTSRATIO_graph=\"$SALESCONTACTSRATIO_graph\";\n";
			$JS_text.="	var SALESPERHOUR_graph=\"$SALESPERHOUR_graph\";\n";
			$JS_text.="	var INCSALES_graph=\"$INCSALES_graph\";\n";
			$JS_text.="	var CANCELLEDSALES_graph=\"$CANCELLEDSALES_graph\";\n";
			$JS_text.="	var CALLBACKS_graph=\"$CALLBACKS_graph\";\n";
			$JS_text.="	var FIRSTCALLS_graph=\"$FIRSTCALLS_graph\";\n";
			$JS_text.="	var AVGSALETIME_graph=\"$AVGSALETIME_graph\";\n";
			$JS_text.="	var AVGCONTACTTIME_graph=\"$AVGCONTACTTIME_graph\";\n";
			$JS_text.="\n";
			for ($e=0; $e<count($call_status); $e++) {
				$Sstatus=$call_status[$e];
				$graph_var=$Sstatus."_graph";
				$JS_text.="	var ".$Sstatus."_graph=\"".$$graph_var."\";\n";
			}
			$JS_text.="	for (var i=1; i<=".(16+count($call_status))."; i++) {\n";
			$JS_text.="		var cellID=\"team".$user_group[$i]."graph\"+i;\n";
			$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
			$JS_text.="	}\n";
			$JS_text.="	var cellID=\"team".$user_group[$i]."graph\"+th_id;\n";
			$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
			$JS_text.="	var graph_to_display=eval(graph+\"_graph\");\n";
			$JS_text.="	document.getElementById('team_".$user_group[$i]."_graph').innerHTML=graph_to_display;\n";
			$JS_text.="}\n";

			$GRAPH_text.=$GRAPH.$GRAPH2.$GRAPH3;
			#flush();
			} 
		else 
			{
			$ASCII_text.="    **** NO AGENTS FOUND UNDER THESE REPORT PARAMETERS ****\n\n";
			$CSV_text.="\"\",\"**** NO AGENTS FOUND UNDER THESE REPORT PARAMETERS ****\"\n\n";
			$GRAPH_text.="    **** NO AGENTS FOUND UNDER THESE REPORT PARAMETERS ****<BR/><BR/>\n\n";
			}
		}

	$ASCII_text.="--- <B>CALL CENTER TOTAL</B>\n";
	$ASCII_text.="+------------------------------------------+------------+-------+-------+----------+---------------+---------------+-------------+-----------+-------+------------------------+----------------------+-------------------------+----------------+------------------+-----------------+-----------+-----------------------+-------------------+----------------------+$HTMLborderheader\n";
	$ASCII_text.="| Team Name                                | Team ID    | Calls | Leads | Contacts | Contact Ratio | Nonpause Time | System Time | Talk Time | Sales | Sales per Working Hour | Sales to Leads Ratio | Sales to Contacts Ratio | Sales Per Hour | Incomplete Sales | Cancelled Sales | Callbacks | First Call Resolution | Average Sale Time | Average Contact Time |$HTMLstatusheader\n";
	$ASCII_text.="+------------------------------------------+------------+-------+-------+----------+---------------+---------------+-------------+-----------+-------+------------------------+----------------------+-------------------------+----------------+------------------+-----------------+-----------+-----------------------+-------------------+----------------------+$HTMLborderheader\n";
	$ASCII_text.=$GROUP_text;
	$ASCII_text.="+------------------------------------------+------------+-------+-------+----------+---------------+---------------+-------------+-----------+-------+------------------------+----------------------+-------------------------+----------------+------------------+-----------------+-----------+-----------------------+-------------------+----------------------+$HTMLborderheader\n";

	if ($total_sales>0) 
		{
		$total_average_sale_time=sec_convert(round($total_sales_talk_time/$total_sales), 'H');
		} 
	else 
		{
		$total_average_sale_time="00:00:00";
		}
	if ($total_contacts>0) 
		{
		$total_average_contact_time=sec_convert(round($total_contact_talk_time/$total_contacts), 'H');
		} 
	else 
		{
		$total_average_contact_time="00:00:00";
		}
	$total_talk_hours=$total_talk_time/3600;

	$ASCII_text.="| ".sprintf("%40s", "");
	$ASCII_text.=" | ".sprintf("%10s", "TOTALS:");
	$ASCII_text.=" | ".sprintf("%5s", $total_calls);	
	$ASCII_text.=" | ".sprintf("%5s", $total_leads);
	$ASCII_text.=" | ".sprintf("%8s", $total_contacts);
	if ($total_leads>0) 
		{
		$total_contact_ratio=sprintf("%.2f", (100*$total_contacts/$total_leads));
		} 
	else 
		{
		$total_contact_ratio="0.00";
		}
	$ASCII_text.=" | ".sprintf("%12s", $total_contact_ratio)."%";
	$ASCII_text.=" | ".sprintf("%13s", sec_convert($total_nonpause_time,'H'));
	$ASCII_text.=" | ".sprintf("%11s", sec_convert($total_system_time,'H'));
	$ASCII_text.=" | ".sprintf("%9s", sec_convert($total_talk_time,'H'));
	$ASCII_text.=" | ".sprintf("%5s", $total_sales);
	if ($total_nonpause_time>0) 
		{
		$sales_per_working_hours=sprintf("%.2f", ($total_sales/($total_nonpause_time/3600)));
		}
	else
		{
		$sales_per_working_hours="0.00";
		}
	$ASCII_text.=" | ".sprintf("%22s", $sales_per_working_hours);
	if ($total_leads>0) 
		{
		$total_sales_ratio=sprintf("%.2f", (100*$total_sales/$total_leads));
		} 
	else 
		{
		$total_sales_ratio="0.00";
		}	
	$ASCII_text.=" | ".sprintf("%19s", $total_sales_ratio)."%";
	if ($total_contacts>0) 
		{
		$total_sale_contact_ratio=sprintf("%.2f", (100*$total_sales/$total_contacts));
		} 
	else 
		{
		$total_sale_contact_ratio=0;
		}
	$ASCII_text.=" | ".sprintf("%22s", $total_sale_contact_ratio)."%";
	if ($total_talk_hours>0) 
		{
		$total_sales_per_hour=sprintf("%.2f", ($total_sales/$total_talk_hours));
		} 
	else 
		{
		$total_sales_per_hour="0.00";
		}
	if ( ($total_calls>0) and ($total_leads>0) )
		{
		$total_stcall=sprintf("%.2f", ($total_calls/$total_leads));
		} 
	else 
		{
		$total_stcall="0.00";
		}
	$ASCII_text.=" | ".sprintf("%14s", $total_sales_per_hour);
	$ASCII_text.=" | ".sprintf("%16s", $total_inc_sales);
	$ASCII_text.=" | ".sprintf("%15s", $total_cnc_sales);
	$ASCII_text.=" | ".sprintf("%9s", $total_callbacks);
	$ASCII_text.=" | ".sprintf("%21s", $total_stcall); 	# first call resolution
	$ASCII_text.=" | ".sprintf("%17s", $total_average_sale_time);
	$ASCII_text.=" | ".sprintf("%20s", $total_average_contact_time)." |";

	$CSV_status_text="";
	for ($q=0; $q<count($call_status_totals_grand_total); $q++) {
		$ASCII_text.=" ".sprintf("%6s", $call_status_totals_grand_total[$q])." |";
		$CSV_status_text.=",\"$call_status_totals_grand_total[$q]\"";
	}
	$ASCII_text.="\n";


	$ASCII_text.="+------------------------------------------+------------+-------+-------+----------+---------------+---------------+-------------+-----------+-------+------------------------+----------------------+-------------------------+----------------+------------------+-----------------+-----------+-----------------------+-------------------+----------------------+$HTMLborderheader\n";
	$ASCII_text.="</FONT></PRE>";
	$ASCII_text.="</BODY>\n";
	$ASCII_text.="</HTML>\n";

	$CSV_text.="\"\",\"CALL CENTER TOTAL\"\n";
	$CSV_text.="\"\",\"Team Name\",\"Team ID\",\"Calls\",\"Leads\",\"Contacts\",\"Contact Ratio\",\"Nonpause Time\",\"System Time\",\"Talk Time\",\"Sales\",\"Sales per Working Hour\",\"Sales to Leads Ratio\",\"Sales to Contacts Ratio\",\"Sales Per Hour\",\"Incomplete Sales\",\"Cancelled Sales\",\"Callbacks\",\"First Call Resolution\",\"Average Sale Time\",\"Average Contact Time\"$CSVstatusheader\n";
	$CSV_text.=$GROUP_CSV_text;
	$CSV_text.="\"\",\"\",\"TOTALS:\",\"$total_calls\",\"$total_leads\",\"$total_contacts\",\"$total_contact_ratio %\",\"".sec_convert($total_nonpause_time,'H')."\",\"".sec_convert($total_system_time,'H')."\",\"".sec_convert($total_talk_time,'H')."\",\"$total_sales\",\"$sales_per_working_hours\",\"$total_sales_ratio\",\"$total_sale_contact_ratio\",\"$total_sales_per_hour\",\"$total_inc_sales\",\"$total_cnc_sales\",\"$total_callbacks\",\"$total_stcall\",\"$total_average_sale_time\",\"$total_average_contact_time\"$CSV_status_text\n";
	for ($d=0; $d<count($total_graph_stats); $d++) {
		if ($d==0) {$class=" first";} else if (($d+1)==count($total_graph_stats)) {$class=" last";} else {$class="";}
		$TOTALCALLS_graph.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][1]/$max_totalcalls)."' height='16' />".$total_graph_stats[$d][1]."</td></tr>";
		$TOTALLEADS_graph.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][2]/$max_totalleads)."' height='16' />".$total_graph_stats[$d][2]."</td></tr>";
		$TOTALCONTACTS_graph.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][3]/$max_totalcontacts)."' height='16' />".$total_graph_stats[$d][3]."</td></tr>";
		$TOTALCONTACTRATIO_graph.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][4]/$max_totalcontactratio)."' height='16' />".$total_graph_stats[$d][4]."%</td></tr>";
		$TOTALSYSTEMTIME_graph.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][5]/$max_totalsystemtime)."' height='16' />".sec_convert($total_graph_stats[$d][5], 'H')."</td></tr>";
		$TOTALTALKTIME_graph.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][6]/$max_totaltalktime)."' height='16' />".sec_convert($total_graph_stats[$d][6], 'H')."</td></tr>";
		$TOTALSALES_graph.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][7]/$max_totalsales)."' height='16' />".$total_graph_stats[$d][7]."</td></tr>";
		$TOTALSALESLEADSRATIO_graph.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][8]/$max_totalsalesleadsratio)."' height='16' />".$total_graph_stats[$d][8]."%</td></tr>";
		$TOTALSALESCONTACTSRATIO_graph.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][9]/$max_totalsalescontactsratio)."' height='16' />".$total_graph_stats[$d][9]."%</td></tr>";
		$TOTALSALESPERHOUR_graph.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][10]/$max_totalsalesperhour)."' height='16' />".$total_graph_stats[$d][10]."</td></tr>";
		$TOTALINCSALES_graph.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][11]/$max_totalincsales)."' height='16' />".$total_graph_stats[$d][11]."</td></tr>";
		$TOTALCANCELLEDSALES_graph.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][12]/$max_totalcancelledsales)."' height='16' />".$total_graph_stats[$d][12]."</td></tr>";
		$TOTALCALLBACKS_graph.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][13]/$max_totalcallbacks)."' height='16' />".$total_graph_stats[$d][13]."</td></tr>";
		$TOTALFIRSTCALLS_graph.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][14]/$max_totalfirstcall)."' height='16' />".$total_graph_stats[$d][14]."</td></tr>";
		$TOTALAVGSALETIME_graph.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][15]/$max_totalavgsaletime)."' height='16' />".sec_convert($total_graph_stats[$d][15], 'H')."</td></tr>";
		$TOTALAVGCONTACTTIME_graph.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][16]/$max_totalavgcontacttime)."' height='16' />".sec_convert($total_graph_stats[$d][16], 'H')."</td></tr>";			

		for ($e=0; $e<count($call_status); $e++) {
			$Sstatus=$call_status[$e];
			$varname="TOTAL".$Sstatus."_graph";
			$max_varname="max_total".$Sstatus;
			# $max.= "<!-- $max_varname => ".$$max_varname." //-->\n";
			
			$$varname.="  <tr><td class='chart_td$class'>".$total_graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$total_graph_stats[$d][($e+17)]/$$max_varname)."' height='16' />".$total_graph_stats[$d][($e+17)]."</td></tr>";
		}

	}

	$TOTALCALLS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($total_calls)."</th></tr></table>";
	$TOTALLEADS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($total_leads)."</th></tr></table>";
	$TOTALCONTACTS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($total_contacts)."</th></tr></table>";
	$TOTALCONTACTRATIO_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($total_contact_ratio)."%</th></tr></table>";
	$TOTALSYSTEMTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".sec_convert($total_system_time,'H')."</th></tr></table>";
	$TOTALTALKTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".sec_convert($total_talk_time,'H')."</th></tr></table>";
	$TOTALSALES_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($total_sales)."</th></tr></table>";
	$TOTALSALESLEADSRATIO_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($total_sales_ratio)."%</th></tr></table>";
	$TOTALSALESCONTACTSRATIO_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($total_sale_contact_ratio)."%</th></tr></table>";
	$TOTALSALESPERHOUR_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($total_sales_per_hour)."</th></tr></table>";
	$TOTALINCSALES_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($total_inc_sales)."</th></tr></table>";
	$TOTALCANCELLEDSALES_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($total_cnc_sales)."</th></tr></table>";
	$TOTALCALLBACKS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($total_callbacks)."</th></tr></table>";
	$TOTALFIRSTCALLS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($total_stcall)."</th></tr></table>";
	$TOTALAVGSALETIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($total_average_sale_time)."</th></tr></table>";
	$TOTALAVGCONTACTTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($total_average_contact_time)."</th></tr></table>";
	for ($e=0; $e<count($call_status); $e++) {
		$Sstatus=$call_status[$e];
		$total_var=$Sstatus."_total";
		$graph_var="TOTAL".$Sstatus."_graph";
		$$graph_var.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($$total_var)."</th></tr></table>";
	}
	$JS_onload.="\tDrawTotalGraph('CALLS', '1');\n"; 
	$JS_text.="function DrawTotalGraph(graph, th_id) {\n";
	$JS_text.="	var CALLS_graph=\"$TOTALCALLS_graph\";\n";
	$JS_text.="	var LEADS_graph=\"$TOTALLEADS_graph\";\n";
	$JS_text.="	var CONTACTS_graph=\"$TOTALCONTACTS_graph\";\n";
	$JS_text.="	var CONTACTRATIO_graph=\"$TOTALCONTACTRATIO_graph\";\n";
	$JS_text.="	var SYSTEMTIME_graph=\"$TOTALSYSTEMTIME_graph\";\n";
	$JS_text.="	var TALKTIME_graph=\"$TOTALTALKTIME_graph\";\n";
	$JS_text.="	var SALES_graph=\"$TOTALSALES_graph\";\n";
	$JS_text.="	var SALESLEADSRATIO_graph=\"$TOTALSALESLEADSRATIO_graph\";\n";
	$JS_text.="	var SALESCONTACTSRATIO_graph=\"$TOTALSALESCONTACTSRATIO_graph\";\n";
	$JS_text.="	var SALESPERHOUR_graph=\"$TOTALSALESPERHOUR_graph\";\n";
	$JS_text.="	var INCSALES_graph=\"$TOTALINCSALES_graph\";\n";
	$JS_text.="	var CANCELLEDSALES_graph=\"$TOTALCANCELLEDSALES_graph\";\n";
	$JS_text.="	var CALLBACKS_graph=\"$TOTALCALLBACKS_graph\";\n";
	$JS_text.="	var FIRSTCALLS_graph=\"$TOTALFIRSTCALLS_graph\";\n";
	$JS_text.="	var AVGSALETIME_graph=\"$TOTALAVGSALETIME_graph\";\n";
	$JS_text.="	var AVGCONTACTTIME_graph=\"$TOTALAVGCONTACTTIME_graph\";\n";
	for ($e=0; $e<count($call_status); $e++) {
		$Sstatus=$call_status[$e];
		$graph_var="TOTAL".$Sstatus."_graph";
		$JS_text.="	var ".$Sstatus."_graph=\"".$$graph_var."\";\n";
	}
	$JS_text.="\n";
	$JS_text.="	for (var i=1; i<=".(16+count($call_status))."; i++) {\n";
	$JS_text.="		var cellID=\"teamTotalgraph\"+i;\n";
	$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
	$JS_text.="	}\n";
	$JS_text.="	var cellID=\"teamTotalgraph\"+th_id;\n";
	$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
	$JS_text.="	var graph_to_display=eval(graph+\"_graph\");\n";
	$JS_text.="	document.getElementById('team_Total_graph').innerHTML=graph_to_display;\n";
	$JS_text.="}\n";

	$GRAPH_text.=$TOTALGRAPH.$TOTALGRAPH2.$TOTALGRAPH3;	
	$GRAPH_text.="</FONT></PRE>";
	$GRAPH_text.="</BODY>\n";
	$GRAPH_text.="</HTML>\n";
	
	}

if ($file_download>0) 
	{
	$FILE_TIME = date("Ymd-His");
	$CSVfilename = "AST_team_performance_detail_$US$FILE_TIME.csv";
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
	}
else
	{
	header("Content-type: text/html; charset=utf-8");
	$JS_onload.="}\n";
	$JS_text.=$JS_onload;
	$JS_text.="</script>\n";

	if ($report_display_type=="HTML")
		{
		$HTML_text.=$GRAPH_text;
		}
	else
		{
		$HTML_text.=$ASCII_text;
		}

	echo $HTML_head;
	echo $JS_text;
	$short_header=1;
	require("admin_header.php");
	echo $HTML_text;
	flush();
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
