<?php 
# AST_campaign_status_list_report.php
#
# This report is designed to show the breakdown by list_id of the calls and 
# their statuses for all lists within a campaign for a set time period
#
# Copyright (C) 2013  Joe Johnson, Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES
#
# 110815-2138 - First build
# 120224-0910 - Added HTML display option with bar graphs
# 130414-0129 - Added report logging
# 130425-2113 - Added status flag summaries and other formatting cleanup
# 130425-2353 - Fixed bug with subtracting unsigned columns in SQL
#

$startMS = microtime();

require("dbconnect.php");
require("functions.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["query_date_D"]))				{$query_date_D=$_GET["query_date_D"];}
	elseif (isset($_POST["query_date_D"]))	{$query_date_D=$_POST["query_date_D"];}
if (isset($_GET["end_date_D"]))				{$end_date_D=$_GET["end_date_D"];}
	elseif (isset($_POST["end_date_D"]))		{$end_date_D=$_POST["end_date_D"];}
if (isset($_GET["query_date_T"]))				{$query_date_T=$_GET["query_date_T"];}
	elseif (isset($_POST["query_date_T"]))	{$query_date_T=$_POST["query_date_T"];}
if (isset($_GET["end_date_T"]))				{$end_date_T=$_GET["end_date_T"];}
	elseif (isset($_POST["end_date_T"]))		{$end_date_T=$_POST["end_date_T"];}
if (isset($_GET["group"]))					{$group=$_GET["group"];}
	elseif (isset($_POST["group"]))			{$group=$_POST["group"];}
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

$report_name="Campaign Status List Report";
$NOW_DATE = date("Y-m-d");
if (!isset($query_date_D)) {$query_date_D=$NOW_DATE;}
if (!isset($end_date_D)) {$end_date_D=$NOW_DATE;}
if (!isset($query_date_T)) {$query_date_T="00:00:00";}
if (!isset($end_date_T)) {$end_date_T="23:59:59";}

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


$PHP_AUTH_USER = preg_replace("/[^0-9a-zA-Z]/","",$PHP_AUTH_USER);
$PHP_AUTH_PW = preg_replace("/[^0-9a-zA-Z]/","",$PHP_AUTH_PW);

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

$stmt="SELECT allowed_campaigns,allowed_reports from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {$HTML_text.="|$stmt|\n";}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$LOGallowed_campaigns = $row[0];
$LOGallowed_reports =	$row[1];

if ( (!preg_match("/$report_name/",$LOGallowed_reports)) and (!preg_match("/ALL REPORTS/",$LOGallowed_reports)) )
	{
    Header("WWW-Authenticate: Basic realm=\"VICI-PROJECTS\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo "You are not allowed to view this report: |$PHP_AUTH_USER|$report_name|\n";
    exit;
	}

$LOGallowed_campaignsSQL='';
$whereLOGallowed_campaignsSQL='';
if ( (!preg_match("/-ALL/",$LOGallowed_campaigns)) )
	{
	$rawLOGallowed_campaignsSQL = preg_replace("/ -/",'',$LOGallowed_campaigns);
	$rawLOGallowed_campaignsSQL = preg_replace("/ /","','",$rawLOGallowed_campaignsSQL);
	$LOGallowed_campaignsSQL = "and campaign_id IN('$rawLOGallowed_campaignsSQL')";
	$whereLOGallowed_campaignsSQL = "where campaign_id IN('$rawLOGallowed_campaignsSQL')";
	}
$regexLOGallowed_campaigns = " $LOGallowed_campaigns ";

$i=0;
$group_string='|';
$group_ct = count($group);
while($i < $group_ct)
	{
	$group_string .= "$group[$i]|";
	$i++;
	}

$stmt="SELECT campaign_id from vicidial_campaigns $whereLOGallowed_campaignsSQL order by campaign_id;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$HTML_text.="$stmt\n";}
$campaigns_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $campaigns_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$groups[$i] =$row[0];
	if (preg_match("/-ALL/",$group_string) )
		{$group[$i] = $groups[$i];}
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

if ( (preg_match("/--ALL--/",$group_string) ) or ($group_ct < 1) )
	{$group_SQL = "";}
else
	{
	$group_SQL = preg_replace("/,\$/",'',$group_SQL);
	$group_SQL_str=$group_SQL;
	$group_SQL = "and campaign_id IN($group_SQL)";
	}

$query_date="$query_date_D $query_date_T";
$end_date="$end_date_D $end_date_T";

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

$HTML_head.="<script language=\"JavaScript\" src=\"calendar_db.js\"></script>\n";
$HTML_head.="<link rel=\"stylesheet\" href=\"calendar.css\">\n";
$HTML_head.="<link rel=\"stylesheet\" href=\"horizontalbargraph.css\">\n";

$HTML_head.="<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";
$HTML_head.="<TITLE>$report_name</TITLE></HEAD><BODY BGCOLOR=WHITE marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>$group_S\n";
$short_header=1;

#	require("admin_header.php");

$HTML_text.="<TABLE CELLPADDING=4 CELLSPACING=0><TR><TD>";

$HTML_text.="<FORM ACTION=\"$PHP_SELF\" METHOD=GET name=vicidial_report id=vicidial_report>\n";
$HTML_text.="<TABLE CELLSPACING=3><TR><TD VALIGN=TOP> Dates:<BR>";
$HTML_text.="<INPUT TYPE=hidden NAME=DB VALUE=\"$DB\">\n";
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
if  (preg_match("/--ALL--/",$group_string))
	{$HTML_text.="<option value=\"--ALL--\" selected>-- ALL CAMPAIGNS --</option>\n";}
else
	{$HTML_text.="<option value=\"--ALL--\">-- ALL CAMPAIGNS --</option>\n";}
$o=0;
while ($campaigns_to_print > $o)
	{
	if (preg_match("/$groups[$o]\|/",$group_string)) {$HTML_text.="<option selected value=\"$groups[$o]\">$groups[$o]</option>\n";}
	else {$HTML_text.="<option value=\"$groups[$o]\">$groups[$o]</option>\n";}
	$o++;
	}
$HTML_text.="</SELECT>\n";

$HTML_text.="</TD><TD VALIGN=TOP>&nbsp;\n";
$HTML_text.="</TD><TD VALIGN=TOP>\n";
$HTML_text.="Display as:<BR>";
$HTML_text.="<select name='report_display_type'>";
if ($report_display_type) {$HTML_text.="<option value='$report_display_type' selected>$report_display_type</option>";}
$HTML_text.="<option value='TEXT'>TEXT</option><option value='HTML'>HTML</option></select>\n<BR><BR>";
$HTML_text.="<INPUT TYPE=SUBMIT NAME=SUBMIT VALUE=SUBMIT>\n";
$HTML_text.="</TD><TD VALIGN=TOP> &nbsp; &nbsp; &nbsp; &nbsp; ";

$HTML_text.="<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;\n";
$HTML_text.="<a href=\"$PHP_SELF?DB=$DB&query_date=$query_date&end_date=$end_date&query_date_D=$query_date_D&query_date_T=$query_date_T&end_date_D=$end_date_D&end_date_T=$end_date_T$groupQS&file_download=1&SUBMIT=$SUBMIT\">DOWNLOAD</a> |";
$HTML_text.=" <a href=\"./admin.php?ADD=999999\">REPORTS</a> </FONT>\n";
$HTML_text.="</FONT>\n";
$HTML_text.="</TD></TR></TABLE>";
$HTML_text.="</FORM>\n\n";

$HTML_text.="<PRE>";
$i=0;
$group_string='|';
$group_ct = count($group);
$JS_text.="<script language='Javascript'>\n";
$JS_onload="onload = function() {\n";

while($i < $group_ct)
	{
	$stmt="SELECT status, status_name, human_answered, sale, dnc, customer_contact, not_interested, unworkable, scheduled_callback, completed from vicidial_campaign_statuses where campaign_id='$group[$i]' UNION SELECT status, status_name, human_answered, sale, dnc, customer_contact, not_interested, unworkable, scheduled_callback, completed from vicidial_statuses order by status, status_name";
	$rslt=mysql_query($stmt, $link);
	while ($row=mysql_fetch_row($rslt)) 
		{
		$status_ary[$row[0]] = " - $row[1]";
		$HA_ary[$row[0]] =		$row[2];
		$SALE_ary[$row[0]] =	$row[3];
		$DNC_ary[$row[0]] =		$row[4];
		$CC_ary[$row[0]] =		$row[5];
		$NI_ary[$row[0]] =		$row[6];
		$UW_ary[$row[0]] =		$row[7];
		$SC_ary[$row[0]] =		$row[8];
		$COMP_ary[$row[0]] =	$row[9];
		}
	$ASCII_text.="<B>CAMPAIGN: $group[$i]</B>\n";
	$GRAPH.="<B>CAMPAIGN: $group[$i]</B>\n";
	$CSV_text.="\"CAMPAIGN: $group[$i]\"\n";

	$stmt="SELECT closer_campaigns from vicidial_campaigns where campaign_id='$group[$i]'";
	$rslt=mysql_query($stmt, $link);
	if (mysql_num_rows($rslt)>0) 
		{
		$row=mysql_fetch_row($rslt);
		$inbound_groups=preg_replace('/ -$/', '', trim($row[0]));
		if (strlen($inbound_groups)>0) 
			{
			$inbound_groups=preg_replace("/\s/", "', '", $inbound_groups);
			$inbound_SQL="and vicidial_closer_log.campaign_id in ('$inbound_groups')";
			} 
		else 
			{
			$inbound_SQL="";
			}
		}

	$stmt="SELECT distinct list_id, list_name, active from vicidial_lists where campaign_id='$group[$i]' order by list_id, list_name asc";
	$rslt=mysql_query($stmt, $link);
	while ($row=mysql_fetch_row($rslt)) 
		{
		$list_id=$row[0]; $list_name=$row[1]; $list_active=$row[2];
		$HA_count=0;
		$SALE_count=0;
		$DNC_count=0;
		$CC_count=0;
		$NI_count=0;
		$UW_count=0;
		$SC_count=0;
		$COMP_count=0;

		$dispo_ary="";
		$ASCII_text.="<FONT SIZE=2><B>List ID #$list_id: $list_name</B>\n";
		$GRAPH.="<FONT SIZE=2><B>List ID #$list_id: $list_name</B>\n";
		$CSV_text.="\"List ID #$list_id: $list_name\"\n";


		# 			$stat_stmt="SELECT vs.status_name, count(*), sum(pause_sec), sum(wait_sec), sum(talk_sec), sum(dispo_sec), sum(dead_sec) from vicidial_agent_log val, vicidial_list vl, vicidial_statuses vs where val.event_time>='$query_date' and val.event_time<='$end_date' and val.campaign_id='$group[$i]' and val.lead_id=vl.list_id and vl.list_id='$list_id' and val.status=vs.status group by vs.status_name order by vs.status_name";
		#$stat_stmt="SELECT val.status, count(*), sum(pause_sec), sum(wait_sec), sum(talk_sec), sum(dispo_sec), sum(dead_sec) from vicidial_agent_log val, vicidial_list vl where val.event_time>='$query_date' and val.event_time<='$end_date' and val.campaign_id='$group[$i]' and val.lead_id=vl.lead_id and vl.list_id='$list_id' group by val.status order by val.status";
		$stat_stmt="SELECT vicidial_log.status, vicidial_log.uniqueid, vicidial_log.length_in_sec as duration, cast(vicidial_agent_log.talk_sec-vicidial_agent_log.dead_sec as signed) as handle_time from vicidial_log LEFT OUTER JOIN vicidial_agent_log on vicidial_log.lead_id=vicidial_agent_log.lead_id and vicidial_log.uniqueid=vicidial_agent_log.uniqueid where vicidial_log.call_date>='$query_date' and vicidial_log.call_date<='$end_date' and vicidial_log.list_id='$list_id' UNION SELECT vicidial_closer_log.status, vicidial_closer_log.uniqueid, vicidial_closer_log.length_in_sec as duration, cast(vicidial_agent_log.talk_sec-vicidial_agent_log.dead_sec as signed) as handle_time from vicidial_closer_log LEFT OUTER JOIN vicidial_agent_log on vicidial_closer_log.lead_id=vicidial_agent_log.lead_id and vicidial_closer_log.uniqueid=vicidial_agent_log.uniqueid where call_date>='$query_date' and call_date<='$end_date' and list_id='$list_id' order by status";
		if ($DB) {$HTML_text.="|$stat_stmt|\n";}
		# $ASCII_text.=$stat_stmt."\n";
		$stat_rslt=mysql_query($stat_stmt, $link);
		if (mysql_num_rows($stat_rslt)>0) 
			{
			$GRAPH.="<a name='list_".$list_id."_graph'/><table border='0' cellpadding='0' cellspacing='2' width='800'>";
			$GRAPH.="<tr><th width='33%' class='grey_graph_cell' id='list_".$list_id."_graph1'><a href='#' onClick=\"Draw".$list_id."Graph('CALLS', '1'); return false;\">CALLS</a></th><th width='33%' class='grey_graph_cell' id='list_".$list_id."_graph2'><a href='#' onClick=\"Draw".$list_id."Graph('DURATION', '2'); return false;\">DURATION</a></th><th width='34%' class='grey_graph_cell' id='list_".$list_id."_graph3'><a href='#' onClick=\"Draw".$list_id."Graph('HANDLETIME', '3'); return false;\">HANDLE TIME</a></th></tr>";
			$GRAPH.="<tr><td colspan='3' class='graph_span_cell'><span id='stats_".$list_id."_graph'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";
			$graph_header="<table cellspacing='0' cellpadding='0' class='horizontalgraph'><caption align='top'>List ID #$list_id: $list_name</caption><tr><th class='thgraph' scope='col'>DISPOSITION</th>";
			$CALLS_graph=$graph_header."<th class='thgraph' scope='col'>CALLS </th></tr>";
			$DURATION_graph=$graph_header."<th class='thgraph' scope='col'>DURATION</th></tr>";
			$HANDLETIME_graph=$graph_header."<th class='thgraph' scope='col'>HANDLE TIME</th></tr>";

			$total_calls=0; $total_handle_time=0; $total_duration=0;

			$graph_stats=array();
			$max_calls=1;
			$max_duration=1;
			$max_handletime=1;
			
			$ASCII_text.="+------------------------------------------+--------+------------+-------------+\n";
			$ASCII_text.="| DISPOSITION                              | CALLS  | DURATION   | HANDLE TIME |\n";
			$ASCII_text.="+------------------------------------------+--------+------------+-------------+\n";
			$CSV_text.="\"DISPOSITION\",\"CALLS\",\"DURATION\",\"HANDLE TIME\"\n";
			while ($stat_row=mysql_fetch_row($stat_rslt)) 
				{
				#if ($stat_row[0]=="") {$stat_row[0]="(no dispo)";}
				#$handle_time=sec_convert(($stat_row[4]-$stat_row[6]), 'H');
				#$duration=sec_convert(($stat_row[3]+$stat_row[4]+$stat_row[5]), 'H');
				#$total_handle_time+=($stat_row[4]-$stat_row[6]);
				#$total_duration+=($stat_row[3]+$stat_row[4]+$stat_row[5]);
				$dispo_ary[$stat_row[0]][0]++;
				$dispo_ary[$stat_row[0]][1]+=$stat_row[2];
				$dispo_ary[$stat_row[0]][2]+=$stat_row[3];
				$total_calls++;
				$total_duration+=$stat_row[2];
				$total_handle_time+=$stat_row[3];
				if ($HA_ary[$stat_row[0]]=="Y") {$HA_count++;}
				if ($SALE_ary[$stat_row[0]]=="Y") {$SALE_count++;}
				if ($DNC_ary[$stat_row[0]]=="Y") {$DNC_count++;}
				if ($CC_ary[$stat_row[0]]=="Y") {$CC_count++;}
				if ($NI_ary[$stat_row[0]]=="Y") {$NI_count++;}
				if ($UW_ary[$stat_row[0]]=="Y") {$UW_count++;}
				if ($SC_ary[$stat_row[0]]=="Y") {$SC_count++;}
				if ($COMP_ary[$stat_row[0]]=="Y") {$COMP_count++;}
				}

			$d=0;
			while (list($key, $val)=each($dispo_ary)) 
				{
				$ASCII_text.="| ".sprintf("%-40s", $key.$status_ary[$key]);
				$ASCII_text.=" | ".sprintf("%6s", $val[0]);
				$ASCII_text.=" | ".sprintf("%10s", sec_convert($val[1], 'H'));
				$ASCII_text.=" | ".sprintf("%11s", sec_convert($val[2], 'H'))." |\n";
				$CSV_text.="\"".$key.$status_ary[$key]."\",\"$val[0]\",\"".sec_convert($val[1], 'H')."\",\"".sec_convert($val[2], 'H')."\"\n";

				if ($val[0]>$max_calls) {$max_calls=$val[0];}
				if ($val[1]>$max_duration) {$max_duration=$val[1];}
				if ($val[2]>$max_handletime) {$max_handletime=$val[2];}
				$graph_stats[$d][0]=$key.$status_ary[$key];
				$graph_stats[$d][1]=$val[0];
				$graph_stats[$d][2]=$val[1];
				$graph_stats[$d][3]=$val[2];
				$d++;
				}
			$ASCII_text.="+------------------------------------------+--------+------------+-------------+\n";
			$ASCII_text.="|                                  TOTALS:";
			$ASCII_text.=" | ".sprintf("%6s", $total_calls);
			$ASCII_text.=" | ".sprintf("%10s", sec_convert($total_duration, 'H'));
			$ASCII_text.=" | ".sprintf("%11s", sec_convert($total_handle_time, 'H'))." |\n";
			$ASCII_text.="+------------------------------------------+--------+------------+-------------+\n";
			$CSV_text.="\"TOTALS:\",\"$total_calls\",\"".sec_convert($total_duration, 'H')."\",\"".sec_convert($total_handle_time, 'H')."\"\n\n";


			$HA_percent =	sprintf("%6.2f", 100*($HA_count/$total_calls)); while(strlen($HA_percent)>6) {$HA_percent = substr("$HA_percent", 0, -1);}
			$SALE_percent =	sprintf("%6.2f", 100*($SALE_count/$total_calls)); while(strlen($SALE_percent)>6) {$SALE_percent = substr("$SALE_percent", 0, -1);}
			$DNC_percent =	sprintf("%6.2f", 100*($DNC_count/$total_calls)); while(strlen($DNC_percent)>6) {$DNC_percent = substr("$DNC_percent", 0, -1);}
			$CC_percent =	sprintf("%6.2f", 100*($CC_count/$total_calls)); while(strlen($CC_percent)>6) {$CC_percent = substr("$CC_percent", 0, -1);}
			$NI_percent =	sprintf("%6.2f", 100*($NI_count/$total_calls)); while(strlen($NI_percent)>6) {$NI_percent = substr("$NI_percent", 0, -1);}
			$UW_percent =	sprintf("%6.2f", 100*($UW_count/$total_calls)); while(strlen($UW_percent)>6) {$UW_percent = substr("$UW_percent", 0, -1);}
			$SC_percent =	sprintf("%6.2f", 100*($SC_count/$total_calls)); while(strlen($SC_percent)>6) {$SC_percent = substr("$SC_percent", 0, -1);}
			$COMP_percent =	sprintf("%6.2f", 100*($COMP_count/$total_calls)); while(strlen($COMP_percent)>6) {$COMP_percent = substr("$COMP_percent", 0, -1);}

			$HA_count =	sprintf("%9s", "$HA_count"); while(strlen($HA_count)>9) {$HA_count = substr("$HA_count", 0, -1);}
			$SALE_count =	sprintf("%9s", "$SALE_count"); while(strlen($SALE_count)>9) {$SALE_count = substr("$SALE_count", 0, -1);}
			$DNC_count =	sprintf("%9s", "$DNC_count"); while(strlen($DNC_count)>9) {$DNC_count = substr("$DNC_count", 0, -1);}
			$CC_count =	sprintf("%9s", "$CC_count"); while(strlen($CC_count)>9) {$CC_count = substr("$CC_count", 0, -1);}
			$NI_count =	sprintf("%9s", "$NI_count"); while(strlen($NI_count)>9) {$NI_count = substr("$NI_count", 0, -1);}
			$UW_count =	sprintf("%9s", "$UW_count"); while(strlen($UW_count)>9) {$UW_count = substr("$UW_count", 0, -1);}
			$SC_count =	sprintf("%9s", "$SC_count"); while(strlen($SC_count)>9) {$SC_count = substr("$SC_count", 0, -1);}
			$COMP_count =	sprintf("%9s", "$COMP_count"); while(strlen($COMP_count)>9) {$COMP_count = substr("$COMP_count", 0, -1);}

			if ($list_active=='Y') {$list_active = 'ACTIVE  ';} else {$list_active = 'INACTIVE';}
			$header_list_id = "$list_id - $list_name";
			$header_list_id =	sprintf("%-51s", $header_list_id); while(strlen($header_list_id)>51) {$header_list_id = substr("$header_list_id", 0, -1);}
			$header_list_count =	sprintf("%10s", $total_calls); while(strlen($header_list_count)>10) {$header_list_count = substr("$header_list_count", 0, -1);}
			$ASCII_text .= "\n";
			$ASCII_text .= "+--------------------------------------------------------------+\n";
			$ASCII_text .= "| $header_list_id $list_active |\n";
			$ASCII_text .= "|    TOTAL CALLS: $header_list_count                                   |\n";
			$ASCII_text .= "+--------------------------------------------------------------+\n";
			$ASCII_text .= "| STATUS FLAGS BREAKDOWN:  (and % of total leads in the list)  |\n";
			$ASCII_text .= "|   Human Answer:       $HA_count    $HA_percent%                   |\n";
			$ASCII_text .= "|   Sale:               $SALE_count    $SALE_percent%                   |\n";
			$ASCII_text .= "|   DNC:                $DNC_count    $DNC_percent%                   |\n";
			$ASCII_text .= "|   Customer Contact:   $CC_count    $CC_percent%                   |\n";
			$ASCII_text .= "|   Not Interested:     $NI_count    $NI_percent%                   |\n";
			$ASCII_text .= "|   Unworkable:         $UW_count    $UW_percent%                   |\n";
			$ASCII_text .= "|   Scheduled callbk:   $SC_count    $SC_percent%                   |\n";
			$ASCII_text .= "|   Completed:          $COMP_count    $COMP_percent%                   |\n";
			$ASCII_text .= "+--------------------------------------------------------------+\n";


			for ($d=0; $d<count($graph_stats); $d++) 
				{
				if ($d==0) {$class=" first";} else if (($d+1)==count($graph_stats)) {$class=" last";} else {$class="";}
				$CALLS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][1]/$max_calls)."' height='16' />".$graph_stats[$d][1]."</td></tr>";
				$DURATION_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][2]/$max_duration)."' height='16' />".sec_convert($graph_stats[$d][2], 'H')."</td></tr>";
				$HANDLETIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][3]/$max_handletime)."' height='16' />".sec_convert($graph_stats[$d][3], 'H')."</td></tr>";
				}
			$CALLS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($total_calls)."</th></tr></table>";
			$DURATION_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".sec_convert($total_duration, 'H')."</th></tr></table>";
			$HANDLETIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".sec_convert($total_handle_time, 'H')."</th></tr></table>";
			$JS_onload.="\tDraw".$list_id."Graph('CALLS', '1');\n"; 
			$JS_text.="function Draw".$list_id."Graph(graph, th_id) {\n";
			$JS_text.="	var CALLS_graph=\"$CALLS_graph\";\n";
			$JS_text.="	var DURATION_graph=\"$DURATION_graph\";\n";
			$JS_text.="	var HANDLETIME_graph=\"$HANDLETIME_graph\";\n";
			$JS_text.="\n";
			$JS_text.="	for (var i=1; i<=3; i++) {\n";
			$JS_text.="		var cellID=\"list_".$list_id."_graph\"+i;\n";
			$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
			$JS_text.="	}\n";
			$JS_text.="	var cellID=\"list_".$list_id."_graph\"+th_id;\n";
			$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
			$JS_text.="	var graph_to_display=eval(graph+\"_graph\");\n";
			$JS_text.="	document.getElementById('stats_".$list_id."_graph').innerHTML=graph_to_display;\n";
			$JS_text.="}\n";
			#$HTML_text.=$GRAPH;
			}
		else 
			{
			$ASCII_text.="<B>***NO CALLS FOUND FROM $query_date TO $end_date***</B>\n";
			$CSV_text.="\"***NO CALLS FOUND FROM $query_date TO $end_date***\"\n\n";
			$GRAPH.="<B>***NO CALLS FOUND FROM $query_date TO $end_date***</B>\n";
			}
		$ASCII_text.="</FONT>\n";
		$GRAPH.="</FONT>\n";
		}
	$i++;
	$ASCII_text.="\n\n";
	$GRAPH.="\n\n";
	$CSV_text.="\n\n";
	}
$JS_onload.="}\n";
$JS_text.=$JS_onload;
$JS_text.="</script>\n";

if ($report_display_type=="HTML")
	{
	$HTML_text.=$JS_text.$GRAPH;
	}
else
	{
	$HTML_text.=$ASCII_text;
	}

$HTML_text.="</PRE></BODY></HTML>";

if ($file_download>0) 
	{
	$FILE_TIME = date("Ymd-His");
	$CSVfilename = "AST_campaign_status_$US$FILE_TIME.csv";
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

	echo $HTML_head;
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
