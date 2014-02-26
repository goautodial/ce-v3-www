<?php 
# AST_LISTS_campaign_stats.php
# 
# Copyright (C) 2013  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# This is a list inventory report, not a calling report. This report will show
# statistics for all of the lists in the selected campaigns
#
# CHANGES
# 100916-0928 - First build
# 110703-1815 - Added download option
# 120224-0910 - Added HTML display option with bar graphs
# 120524-1754 - Fixed status categories issue
# 130221-1928 - small change to remove nested SQL query
# 130414-0127 - Added report logging
#

$startMS = microtime();

header ("Content-type: text/html; charset=utf-8");

require("dbconnect.php");
require("functions.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["group"]))				{$group=$_GET["group"];}
	elseif (isset($_POST["group"]))		{$group=$_POST["group"];}
if (isset($_GET["DB"]))					{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))		{$DB=$_POST["DB"];}
if (isset($_GET["submit"]))				{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))	{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))				{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))	{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["file_download"]))				{$file_download=$_GET["file_download"];}
	elseif (isset($_POST["file_download"]))	{$file_download=$_POST["file_download"];}
if (isset($_GET["report_display_type"]))				{$report_display_type=$_GET["report_display_type"];}
	elseif (isset($_POST["report_display_type"]))	{$report_display_type=$_POST["report_display_type"];}

$PHP_AUTH_USER = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_USER);
$PHP_AUTH_PW = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_PW);

$report_name = 'Lists Campaign Statuses Report';
$db_source = 'M';
$JS_text="<script language='Javascript'>\n";
$JS_onload="onload = function() {\n";

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

$stmt="SELECT allowed_campaigns,allowed_reports from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {$MAIN.="|$stmt|\n";}
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

$NOW_DATE = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");
$STARTtime = date("U");
if (!isset($group)) {$group = '';}

$i=0;
$group_string='|';
$group_ct = count($group);
while($i < $group_ct)
	{
	$group_string .= "$group[$i]|";
	$i++;
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

$stmt="select campaign_id,campaign_name from vicidial_campaigns $whereLOGallowed_campaignsSQL order by campaign_id;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$campaigns_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $campaigns_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$groups[$i] =		$row[0];
	$group_names[$i] =	$row[1];
	if (ereg("-ALL",$group_string) )
		{$group[$i] = $groups[$i];}
	$i++;
	}

$rollover_groups_count=0;
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
if (strlen($group_drop_SQL) < 2)
	{$group_drop_SQL = "''";}
if ( (ereg("--ALL--",$group_string) ) or ($group_ct < 1) or (strlen($group_string) < 2) )
	{
	$group_SQL = "$LOGallowed_campaignsSQL";
	}
else
	{
	$group_SQL = eregi_replace(",$",'',$group_SQL);
	$both_group_SQLand = "and ( (campaign_id IN($group_drop_SQL)) or (campaign_id IN($group_SQL)) )";
	$both_group_SQL = "where ( (campaign_id IN($group_drop_SQL)) or (campaign_id IN($group_SQL)) )";
	$group_SQLand = "and campaign_id IN($group_SQL)";
	$group_SQL = "where campaign_id IN($group_SQL)";
	}

# Get lists to query to avoid using a nested query
$lists_id_str="";
$list_stmt="SELECT list_id from vicidial_lists where active IN('Y','N') $group_SQLand";
$list_rslt=mysql_query($list_stmt, $link);
while ($lrow=mysql_fetch_row($list_rslt)) {
	$lists_id_str.="'$lrow[0]',";
}
$lists_id_str=substr($lists_id_str,0,-1);

$stmt="select vsc_id,vsc_name from vicidial_status_categories;";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$statcats_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $statcats_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$vsc_id[$i] =	$row[0];
	$vsc_name[$i] =	$row[1];

	$category_statuses="";
	$status_stmt="select distinct status from vicidial_statuses where category='$row[0]' UNION select distinct status from vicidial_campaign_statuses where category='$row[0]' $group_SQLand";
	if ($DB) {echo "$status_stmt\n";}
	$status_rslt=mysql_query($status_stmt, $link);
	while ($status_row=mysql_fetch_row($status_rslt)) 
		{
		$category_statuses.="'$status_row[0]',";
        }
	$category_statuses=substr($category_statuses, 0, -1);

	$category_stmt="select count(*) from vicidial_list where status in ($category_statuses) and list_id IN($lists_id_str)";
	if ($DB) {echo "$category_stmt\n";}
	$category_rslt=mysql_query($category_stmt, $link);
	$category_row=mysql_fetch_row($category_rslt);
	$vsc_count[$i] = $category_row[0];
	$i++;
	}



### BEGIN gather all statuses that are in status flags  ###
$human_answered_statuses='';
$sale_statuses='';
$dnc_statuses='';
$customer_contact_statuses='';
$not_interested_statuses='';
$unworkable_statuses='';
$stmt="select status,human_answered,sale,dnc,customer_contact,not_interested,unworkable,status_name from vicidial_statuses;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$statha_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $statha_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$temp_status = $row[0];
	$statname_list["$temp_status"] = "$row[7]";
	if ($row[1]=='Y') {$human_answered_statuses .= "'$temp_status',";}
	if ($row[2]=='Y') {$sale_statuses .= "'$temp_status',";}
	if ($row[3]=='Y') {$dnc_statuses .= "'$temp_status',";}
	if ($row[4]=='Y') {$customer_contact_statuses .= "'$temp_status',";}
	if ($row[5]=='Y') {$not_interested_statuses .= "'$temp_status',";}
	if ($row[6]=='Y') {$unworkable_statuses .= "'$temp_status',";}
	$i++;
	}
$stmt="select status,human_answered,sale,dnc,customer_contact,not_interested,unworkable,status_name from vicidial_campaign_statuses where selectable IN('Y','N') $group_SQLand;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$statha_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $statha_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$temp_status = $row[0];
	$statname_list["$temp_status"] = "$row[7]";
	if ( ($row[1]=='Y') and (!preg_match("/'$temp_status'/",$human_answered_statuses)) ) {$human_answered_statuses .= "'$temp_status',";}
	if ($row[2]=='Y') {$sale_statuses .= "'$temp_status',";}
	if ($row[3]=='Y') {$dnc_statuses .= "'$temp_status',";}
	if ($row[4]=='Y') {$customer_contact_statuses .= "'$temp_status',";}
	if ($row[5]=='Y') {$not_interested_statuses .= "'$temp_status',";}
	if ($row[6]=='Y') {$unworkable_statuses .= "'$temp_status',";}
	$i++;
	}
if (strlen($human_answered_statuses)>2)		{$human_answered_statuses = substr("$human_answered_statuses", 0, -1);}
else {$human_answered_statuses="''";}
if (strlen($sale_statuses)>2)				{$sale_statuses = substr("$sale_statuses", 0, -1);}
else {$sale_statuses="''";}
if (strlen($dnc_statuses)>2)				{$dnc_statuses = substr("$dnc_statuses", 0, -1);}
else {$dnc_statuses="''";}
if (strlen($customer_contact_statuses)>2)	{$customer_contact_statuses = substr("$customer_contact_statuses", 0, -1);}
else {$customer_contact_statuses="''";}
if (strlen($not_interested_statuses)>2)		{$not_interested_statuses = substr("$not_interested_statuses", 0, -1);}
else {$not_interested_statuses="''";}
if (strlen($unworkable_statuses)>2)			{$unworkable_statuses = substr("$unworkable_statuses", 0, -1);}
else {$unworkable_statuses="''";}

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
$HEADER.="<link rel=\"stylesheet\" href=\"horizontalbargraph.css\">\n";

$HEADER.="<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";
$HEADER.="<TITLE>$report_name</TITLE></HEAD><BODY BGCOLOR=WHITE marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>\n";

$short_header=1;

$MAIN.="<TABLE CELLPADDING=4 CELLSPACING=0><TR><TD>";

$MAIN.="<FORM ACTION=\"$PHP_SELF\" METHOD=GET name=vicidial_report id=vicidial_report>\n";
$MAIN.="<TABLE CELLSPACING=3><TR><TD VALIGN=TOP>";
$MAIN.="<INPUT TYPE=HIDDEN NAME=DB VALUE=\"$DB\">\n";

$MAIN.="</TD><TD VALIGN=TOP> Campaigns:<BR>";
$MAIN.="<SELECT SIZE=5 NAME=group[] multiple>\n";
if  (eregi("--ALL--",$group_string))
	{$MAIN.="<option value=\"--ALL--\" selected>-- ALL CAMPAIGNS --</option>\n";}
else
	{$MAIN.="<option value=\"--ALL--\">-- ALL CAMPAIGNS --</option>\n";}
$o=0;
while ($campaigns_to_print > $o)
	{
	if (eregi("$groups[$o]\|",$group_string)) {$MAIN.="<option selected value=\"$groups[$o]\">$groups[$o] - $group_names[$o]</option>\n";}
	  else {$MAIN.="<option value=\"$groups[$o]\">$groups[$o] - $group_names[$o]</option>\n";}
	$o++;
	}
$MAIN.="</SELECT>\n";
$MAIN.="</TD><TD VALIGN=TOP>";
$MAIN.="Display as:<BR/>";
$MAIN.="<select name='report_display_type'>";
if ($report_display_type) {$MAIN.="<option value='$report_display_type' selected>$report_display_type</option>";}
$MAIN.="<option value='TEXT'>TEXT</option><option value='HTML'>HTML</option></select>&nbsp; ";
$MAIN.="<BR><BR>\n";
$MAIN.="<INPUT type=submit NAME=SUBMIT VALUE=SUBMIT>\n";
$MAIN.="</TD><TD VALIGN=TOP> &nbsp; &nbsp; &nbsp; &nbsp; ";
$MAIN.="<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2>";
if (strlen($group[0]) > 1)
	{
	$MAIN.=" <a href=\"./admin.php?ADD=34&campaign_id=$group[0]\">MODIFY</a> | \n";
	$MAIN.=" <a href=\"./admin.php?ADD=999999\">REPORTS</a> </FONT>\n";
	}
else
	{
	$MAIN.=" <a href=\"./admin.php?ADD=10\">CAMPAIGNS</a> | \n";
	$MAIN.=" <a href=\"./admin.php?ADD=999999\">REPORTS</a> </FONT>\n";
	}
$MAIN.="</TD></TR></TABLE>";
$MAIN.="</FORM>\n\n";

$MAIN.="<PRE><FONT SIZE=2>\n\n";


if (strlen($group[0]) < 1)
	{
	$MAIN.="\n\n";
	$MAIN.="PLEASE SELECT A CAMPAIGN AND DATE ABOVE AND CLICK SUBMIT\n";
	}

else
	{
	$OUToutput = '';
	$OUToutput .= "Lists Campaign Status Stats                             $NOW_TIME\n";

	$OUToutput .= "\n";

	##############################
	#########  LIST ID BREAKDOWN STATS

	$TOTALleads = 0;

	$OUToutput .= "\n";
	$OUToutput .= "---------- LIST ID SUMMARY     <a href=\"$PHP_SELF?DB=$DB$groupQS&SUBMIT=$SUBMIT&file_download=1\">DOWNLOAD</a>\n";
	$OUToutput .= "+------------------------------------------+------------+----------+\n";
	$OUToutput .= "| LIST                                     | LEADS      | ACTIVE   |\n";
	$OUToutput .= "+------------------------------------------+------------+----------+\n";

	$CSV_text1.="\"LIST ID SUMMARY\"\n";
	$CSV_text1.="\"LIST\",\"LEADS\",\"ACTIVE\"\n";

	$max_calls=1; $graph_stats=array();
	$GRAPH="</PRE><table cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"white\" summary=\"DID Summary\" class=\"horizontalgraph\">\n";
	$GRAPH.="<caption align='top'>LIST ID SUMMARY</caption>";
	$GRAPH.="<tr>\n";
	$GRAPH.="<th class=\"thgraph\" scope=\"col\">LIST</th>\n";
	$GRAPH.="<th class=\"thgraph\" scope=\"col\">LEADS</th>\n";
	$GRAPH.="</tr>\n";

	$lists_id_str="";
	$list_stmt="SELECT list_id from vicidial_lists where active IN('Y','N') $group_SQLand";
	$list_rslt=mysql_query($list_stmt, $link);
	while ($lrow=mysql_fetch_row($list_rslt)) {
		$lists_id_str.="'$lrow[0]',";
	}
	$lists_id_str=substr($lists_id_str,0,-1);

	$stmt="select count(*),list_id from vicidial_list where list_id IN($lists_id_str) group by list_id;";
	$rslt=mysql_query($stmt, $link);
	if ($DB) {$MAIN.="$stmt\n";}
	$listids_to_print = mysql_num_rows($rslt);
	$i=0;
	while ($i < $listids_to_print)
		{
		$row=mysql_fetch_row($rslt);
		$LISTIDcalls[$i] =	$row[0];
		$LISTIDlists[$i] =	$row[1];
		$list_id_SQL .=		"'$row[1]',";
		if ($row[0]>$max_calls) {$max_calls=$row[0];}
		$graph_stats[$i][0]=$row[0];
		$graph_stats[$i][1]=$row[1];
		$i++;
		}
	if (strlen($list_id_SQL)>2)		{$list_id_SQL = substr("$list_id_SQL", 0, -1);}
	else {$list_id_SQL="''";}

	$i=0;
	while ($i < $listids_to_print)
		{
		$stmt="select list_name,active from vicidial_lists where list_id='$LISTIDlists[$i]';";
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$MAIN.="$stmt\n";}
		$list_name_to_print = mysql_num_rows($rslt);
		if ($list_name_to_print > 0)
			{
			$row=mysql_fetch_row($rslt);
			$LISTIDlist_names[$i] =	$row[0];
			$graph_stats[$i][1].=" - $row[0]";
			if ($row[1]=='Y')
				{$LISTIDlist_active[$i] = 'ACTIVE  '; $graph_stats[$i][1].=" (ACTIVE)";}
			else
				{$LISTIDlist_active[$i] = 'INACTIVE'; $graph_stats[$i][1].=" (INACTIVE)";}
			}

		$TOTALleads = ($TOTALleads + $LISTIDcalls[$i]);

		$LISTIDcount =	sprintf("%10s", $LISTIDcalls[$i]);while(strlen($LISTIDcount)>10) {$LISTIDcount = substr("$LISTIDcount", 0, -1);}
		$LISTIDname =	sprintf("%-40s", "$LISTIDlists[$i] - $LISTIDlist_names[$i]");while(strlen($LISTIDname)>40) {$LISTIDname = substr("$LISTIDname", 0, -1);}

		$OUToutput .= "| $LISTIDname | $LISTIDcount | $LISTIDlist_active[$i] |\n";
		$CSV_text1.="\"$LISTIDname\",\"$LISTIDcount\",\"$LISTIDlist_active[$i]\"\n";

		$i++;
		}

	$TOTALleads =		sprintf("%10s", $TOTALleads);

	$OUToutput .= "+------------------------------------------+------------+----------+\n";
	$OUToutput .= "| TOTAL:                                   | $TOTALleads |\n";
	$OUToutput .= "+------------------------------------------+------------+\n";
	$CSV_text1.="\"TOTAL\",\"$TOTALleads\"\n";

	for ($d=0; $d<count($graph_stats); $d++) {
		if ($d==0) {$class=" first";} else if (($d+1)==count($graph_stats)) {$class=" last";} else {$class="";}
		$GRAPH.="  <tr>\n";
		$GRAPH.="	<td class=\"chart_td$class\">".$graph_stats[$d][1]."</td>\n";
		$GRAPH.="	<td nowrap class=\"chart_td value$class\"><img src=\"images/bar.png\" alt=\"\" width=\"".round(400*$graph_stats[$d][0]/$max_calls)."\" height=\"16\" />".$graph_stats[$d][0]."</td>\n";
		$GRAPH.="  </tr>\n";
	}
	$GRAPH.="  <tr>\n";
	$GRAPH.="	<th class=\"thgraph\" scope=\"col\">TOTAL:</th>\n";
	$GRAPH.="	<th class=\"thgraph\" scope=\"col\">".trim($TOTALleads)."</th>\n";
	$GRAPH.="  </tr>\n";
	$GRAPH.="</table><PRE>\n";
	# $OUToutput.=$GRAPH;


	##############################
	#########  STATUS FLAGS STATS

	$HA_count=0;
	$HA_percent=0;
	$SALE_count=0;
	$SALE_percent=0;
	$DNC_count=0;
	$DNC_percent=0;
	$CC_count=0;
	$CC_percent=0;
	$NI_count=0;
	$NI_percent=0;
	$UW_count=0;
	$UW_percent=0;

	$max_calls=1; $graph_stats=array();
	$GRAPH.="</PRE><table cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"white\" summary=\"DID Summary\" class=\"horizontalgraph\">\n";
	$GRAPH.="<caption align='top'>STATUS FLAG SUMMARY</caption>";
	$GRAPH.="<tr>\n";
	$GRAPH.="<th class=\"thgraph\" scope=\"col\">STATUS FLAG</th>\n";
	$GRAPH.="<th class=\"thgraph\" scope=\"col\">CALLS</th>\n";
	$GRAPH.="</tr>\n";

	$stmt="select count(*) from vicidial_list where status IN($human_answered_statuses) and list_id IN($list_id_SQL);";
	$rslt=mysql_query($stmt, $link);
	if ($DB) {$MAIN.="$stmt\n";}
	$HA_results = mysql_num_rows($rslt);
	if ($HA_results > 0)
		{
		$row=mysql_fetch_row($rslt);
		$HA_count = $row[0];
		$flag_count+=$row[0];
		if ($HA_count > 0)
			{
			if ($HA_count>$max_calls) {$max_calls=$HA_count;}
			$HA_percent = ( ($HA_count / $TOTALleads) * 100);
			}
		}
	$stmt="select count(*) from vicidial_list where status IN($sale_statuses) and list_id IN($list_id_SQL);";
	$rslt=mysql_query($stmt, $link);
	if ($DB) {$MAIN.="$stmt\n";}
	$SALE_results = mysql_num_rows($rslt);
	if ($SALE_results > 0)
		{
		$row=mysql_fetch_row($rslt);
		$SALE_count = $row[0];
		$flag_count+=$row[0];
		if ($SALE_count > 0)
			{
			if ($SALE_count>$max_calls) {$max_calls=$SALE_count;}
			$SALE_percent = ( ($SALE_count / $TOTALleads) * 100);
			}
		}
	$stmt="select count(*) from vicidial_list where status IN($dnc_statuses) and list_id IN($list_id_SQL);";
	$rslt=mysql_query($stmt, $link);
	if ($DB) {$MAIN.="$stmt\n";}
	$DNC_results = mysql_num_rows($rslt);
	if ($DNC_results > 0)
		{
		$row=mysql_fetch_row($rslt);
		$DNC_count = $row[0];
		$flag_count+=$row[0];
		if ($DNC_count > 0)
			{
			if ($DNC_count>$max_calls) {$max_calls=$DNC_count;}
			$DNC_percent = ( ($DNC_count / $TOTALleads) * 100);
			}
		}
	$stmt="select count(*) from vicidial_list where status IN($customer_contact_statuses) and list_id IN($list_id_SQL);";
	$rslt=mysql_query($stmt, $link);
	if ($DB) {$MAIN.="$stmt\n";}
	$CC_results = mysql_num_rows($rslt);
	if ($CC_results > 0)
		{
		$row=mysql_fetch_row($rslt);
		$CC_count = $row[0];
		$flag_count+=$row[0];
		if ($CC_count > 0)
			{
			if ($C_count>$max_calls) {$max_calls=$CC_count;}
			$CC_percent = ( ($CC_count / $TOTALleads) * 100);
			}
		}
	$stmt="select count(*) from vicidial_list where status IN($not_interested_statuses) and list_id IN($list_id_SQL);";
	$rslt=mysql_query($stmt, $link);
	if ($DB) {$MAIN.="$stmt\n";}
	$NI_results = mysql_num_rows($rslt);
	if ($NI_results > 0)
		{
		$row=mysql_fetch_row($rslt);
		$NI_count = $row[0];
		$flag_count+=$row[0];
		if ($NI_count > 0)
			{
			if ($NI_count>$max_calls) {$max_calls=$NI_count;}
			$NI_percent = ( ($NI_count / $TOTALleads) * 100);
			}
		}
	$stmt="select count(*) from vicidial_list where status IN($unworkable_statuses) and list_id IN($list_id_SQL);";
	$rslt=mysql_query($stmt, $link);
	if ($DB) {$MAIN.="$stmt\n";}
	$UW_results = mysql_num_rows($rslt);
	if ($UW_results > 0)
		{
		$row=mysql_fetch_row($rslt);
		$UW_count = $row[0];
		$flag_count+=$row[0];
		if ($UW_count > 0)
			{
			if ($UW_count>$max_calls) {$max_calls=$UW_count;}
			$UW_percent = ( ($UW_count / $TOTALleads) * 100);
			}
		}

	$HA_percent =	sprintf("%6.2f", "$HA_percent"); while(strlen($HA_percent)>6) {$HA_percent = substr("$HA_percent", 0, -1);}
	$SALE_percent =	sprintf("%6.2f", "$SALE_percent"); while(strlen($SALE_percent)>6) {$SALE_percent = substr("$SALE_percent", 0, -1);}
	$DNC_percent =	sprintf("%6.2f", "$DNC_percent"); while(strlen($DNC_percent)>6) {$DNC_percent = substr("$DNC_percent", 0, -1);}
	$CC_percent =	sprintf("%6.2f", "$CC_percent"); while(strlen($CC_percent)>6) {$CC_percent = substr("$CC_percent", 0, -1);}
	$NI_percent =	sprintf("%6.2f", "$NI_percent"); while(strlen($NI_percent)>6) {$NI_percent = substr("$NI_percent", 0, -1);}
	$UW_percent =	sprintf("%6.2f", "$UW_percent"); while(strlen($UW_percent)>6) {$UW_percent = substr("$UW_percent", 0, -1);}

	$HA_count =	sprintf("%10s", "$HA_count"); while(strlen($HA_count)>10) {$HA_count = substr("$HA_count", 0, -1);}
	$SALE_count =	sprintf("%10s", "$SALE_count"); while(strlen($SALE_count)>10) {$SALE_count = substr("$SALE_count", 0, -1);}
	$DNC_count =	sprintf("%10s", "$DNC_count"); while(strlen($DNC_count)>10) {$DNC_count = substr("$DNC_count", 0, -1);}
	$CC_count =	sprintf("%10s", "$CC_count"); while(strlen($CC_count)>10) {$CC_count = substr("$CC_count", 0, -1);}
	$NI_count =	sprintf("%10s", "$NI_count"); while(strlen($NI_count)>10) {$NI_count = substr("$NI_count", 0, -1);}
	$UW_count =	sprintf("%10s", "$UW_count"); while(strlen($UW_count)>10) {$UW_count = substr("$UW_count", 0, -1);}

	$OUToutput .= "\n";
	$OUToutput .= "\n";
	$OUToutput .= "---------- STATUS FLAGS SUMMARY:    (and % of leads in selected lists)     <a href=\"$PHP_SELF?DB=$DB$groupQS&SUBMIT=$SUBMIT&file_download=2\">DOWNLOAD</a>\n";
	$OUToutput .= "+------------------+------------+----------+\n";
	$OUToutput .= "| Human Answer     | $HA_count |  $HA_percent% |\n";
	$OUToutput .= "| Sale             | $SALE_count |  $SALE_percent% |\n";
	$OUToutput .= "| DNC              | $DNC_count |  $DNC_percent% |\n";
	$OUToutput .= "| Customer Contact | $CC_count |  $CC_percent% |\n";
	$OUToutput .= "| Not Interested   | $NI_count |  $NI_percent% |\n";
	$OUToutput .= "| Unworkable       | $UW_count |  $UW_percent% |\n";
	$OUToutput .= "+------------------+------------+----------+\n";
	$OUToutput .= "\n";

	$CSV_text2.="\"STATUS FLAGS SUMMARY:\"\n";
	$CSV_text2 .= "\"Human Answer\",\"$HA_count\",\"$HA_percent%\"\n";
	$CSV_text2 .= "\"Sale\",\"$SALE_count\",\"$SALE_percent%\"\n";
	$CSV_text2 .= "\"DNC\",\"$DNC_count\",\"$DNC_percent%\"\n";
	$CSV_text2 .= "\"Customer Contact\",\"$CC_count\",\"$CC_percent%\"\n";
	$CSV_text2 .= "\"Not Interested\",\"$NI_count\",\"$NI_percent%\"\n";
	$CSV_text2 .= "\"Unworkable\",\"$UW_count\",\"$UW_percent%\"\n";

	$GRAPH.="  <tr>\n";
	$GRAPH.="	<td class=\"chart_td first\">Human Answer</td>\n";
	$GRAPH.="	<td nowrap class=\"chart_td value first\"><img src=\"images/bar.png\" alt=\"\" width=\"".round(400*$HA_count/$max_calls)."\" height=\"16\"/>".$HA_count." ($HA_percent%)</td>\n";
	$GRAPH.="  </tr>\n";
	$GRAPH.="  <tr>\n";
	$GRAPH.="	<td class=\"chart_td\">Sale</td>\n";
	$GRAPH.="	<td nowrap class=\"chart_td value\"><img src=\"images/bar.png\" alt=\"\" width=\"".round(400*$SALE_count/$max_calls)."\" height=\"16\" />".$SALE_count." ($SALE_percent%)</td>\n";
	$GRAPH.="  </tr>\n";
	$GRAPH.="  <tr>\n";
	$GRAPH.="	<td class=\"chart_td\">DNC</td>\n";
	$GRAPH.="	<td nowrap class=\"chart_td value\"><img src=\"images/bar.png\" alt=\"\" width=\"".round(400*$DNC_count/$max_calls)."\" height=\"16\" />".$DNC_count." ($DNC_percent%)</td>\n";
	$GRAPH.="  </tr>\n";
	$GRAPH.="  <tr>\n";
	$GRAPH.="	<td class=\"chart_td\">Customer Contact</td>\n";
	$GRAPH.="	<td nowrap class=\"chart_td value\"><img src=\"images/bar.png\" alt=\"\" width=\"".round(400*$CC_count/$max_calls)."\" height=\"16\" />".$CC_count." ($CC_percent%)</td>\n";
	$GRAPH.="  </tr>\n";
	$GRAPH.="  <tr>\n";
	$GRAPH.="	<td class=\"chart_td\">Not Interested</td>\n";
	$GRAPH.="	<td nowrap class=\"chart_td value\"><img src=\"images/bar.png\" alt=\"\" width=\"".round(400*$NI_count/$max_calls)."\" height=\"16\" />".$NI_count." ($NI_percent%)</td>\n";
	$GRAPH.="  </tr>\n";
	$GRAPH.="  <tr>\n";
	$GRAPH.="	<td class=\"chart_td last\">Unworkable</td>\n";
	$GRAPH.="	<td nowrap class=\"chart_td value last\"><img src=\"images/bar.png\" alt=\"\" width=\"".round(400*$UW_count/$max_calls)."\" height=\"16\" />".$UW_count." ($UW_percent%)</td>\n";
	$GRAPH.="  </tr>\n";
	$GRAPH.="  <tr>\n";
	$GRAPH.="	<th class=\"thgraph\" scope=\"col\">TOTAL:</th>\n";
	$GRAPH.="	<th class=\"thgraph\" scope=\"col\">".trim($flag_count)."</th>\n";
	$GRAPH.="  </tr>\n";
	$GRAPH.="</table><PRE>\n";
	# $OUToutput.=$GRAPH;

	##############################
	#########  STATUS CATEGORY STATS

	$OUToutput .= "\n";
	$OUToutput .= "---------- CUSTOM STATUS CATEGORY STATS     <a href=\"$PHP_SELF?DB=$DB$groupQS&SUBMIT=$SUBMIT&file_download=3\">DOWNLOAD</a>\n";
	$OUToutput .= "+----------------------+------------+--------------------------------+\n";
	$OUToutput .= "| CATEGORY             | CALLS      | DESCRIPTION                    |\n";
	$OUToutput .= "+----------------------+------------+--------------------------------+\n";

	$CSV_text3.="\"CUSTOM STATUS CATEGORY STATS\"\n";
	$CSV_text3.="\"CATEGORY\",\"CALLS\",\"DESCRIPTION\"\n";

	$max_calls=1; $graph_stats=array();
	$GRAPH.="</PRE><table cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"white\" summary=\"DID Summary\" class=\"horizontalgraph\">\n";
	$GRAPH.="<caption align='top'>CUSTOM STATUS CATEGORY STATS</caption>";
	$GRAPH.="<tr>\n";
	$GRAPH.="<th class=\"thgraph\" scope=\"col\">CATEGORY</th>\n";
	$GRAPH.="<th class=\"thgraph\" scope=\"col\">CALLS</th>\n";
	$GRAPH.="</tr>\n";

	$TOTCATcalls=0;
	$r=0; $i=0;
	while ($r < $statcats_to_print)
		{
		if ($vsc_id[$r] != 'UNDEFINED')
			{
			$TOTCATcalls = ($TOTCATcalls + $vsc_count[$r]);
			$category =	sprintf("%-20s", $vsc_id[$r]); while(strlen($category)>20) {$category = substr("$category", 0, -1);}
			$CATcount =	sprintf("%10s", $vsc_count[$r]); while(strlen($CATcount)>10) {$CATcount = substr("$CATcount", 0, -1);}
			$CATname =	sprintf("%-30s", $vsc_name[$r]); while(strlen($CATname)>30) {$CATname = substr("$CATname", 0, -1);}

			if ($vsc_count[$r]>$max_calls) {$max_calls=$vsc_count[$r];}
			$graph_stats[$i][0]=$vsc_count[$r];
			$graph_stats[$i][1]=$vsc_id[$r];
			$i++;

			$OUToutput .= "| $category | $CATcount | $CATname |\n";
			$CSV_text3.="\"$category\",\"$CATcount\",\"$CATname\"\n";
			}
		$r++;
		}

	$TOTCATcalls =	sprintf("%10s", $TOTCATcalls); while(strlen($TOTCATcalls)>10) {$TOTCATcalls = substr("$TOTCATcalls", 0, -1);}

	$OUToutput .= "+----------------------+------------+--------------------------------+\n";
	$OUToutput .= "| TOTAL                | $TOTCATcalls |\n";
	$OUToutput .= "+----------------------+------------+\n";
	$CSV_text3.="\"TOTAL\",\"$TOTCATcalls\"\n";

	for ($d=0; $d<count($graph_stats); $d++) {
		if ($d==0) {$class=" first";} else if (($d+1)==count($graph_stats)) {$class=" last";} else {$class="";}
		$GRAPH.="  <tr>\n";
		$GRAPH.="	<td class=\"chart_td$class\">".$graph_stats[$d][1]."</td>\n";
		$GRAPH.="	<td nowrap class=\"chart_td value$class\"><img src=\"images/bar.png\" alt=\"\" width=\"".round(400*$graph_stats[$d][0]/$max_calls)."\" height=\"16\" />".$graph_stats[$d][0]."</td>\n";
		$GRAPH.="  </tr>\n";
	}
	$GRAPH.="  <tr>\n";
	$GRAPH.="	<th class=\"thgraph\" scope=\"col\">TOTAL:</th>\n";
	$GRAPH.="	<th class=\"thgraph\" scope=\"col\">".trim($TOTCATcalls)."</th>\n";
	$GRAPH.="  </tr>\n";
	$GRAPH.="</table><PRE>\n";
	#$OUToutput.=$GRAPH;


	##############################
	#########  PER LIST DETAIL STATS


	$TOTALleads = 0;
	$OUToutput .= "\n";
	$OUToutput .= "---------- PER LIST DETAIL STATS     <a href=\"$PHP_SELF?DB=$DB$groupQS&SUBMIT=$SUBMIT&file_download=4\">DOWNLOAD</a>\n";
	$OUToutput .= "\n";

	$CSV_text4.="\"PER LIST DETAIL STATS\"\n\n";

	$i=0;
	while ($i < $listids_to_print)
		{
		$TOTALleads=0;
		$header_list_id = "$LISTIDlists[$i] - $LISTIDlist_names[$i]";
		$header_list_id =	sprintf("%-51s", $header_list_id); while(strlen($header_list_id)>51) {$header_list_id = substr("$header_list_id", 0, -1);}
		$header_list_count =	sprintf("%10s", $LISTIDcalls[$i]); while(strlen($header_list_count)>10) {$header_list_count = substr("$header_list_count", 0, -1);}

		$OUToutput .= "\n";
		$OUToutput .= "+--------------------------------------------------------------+\n";
		$OUToutput .= "| $header_list_id $LISTIDlist_active[$i] |\n";
		$OUToutput .= "|    TOTAL LEADS: $header_list_count                                   |\n";
		$OUToutput .= "+--------------------------------------------------------------+\n";

		$max_flags=1; 
		$max_status=1;
		$graph_stats=array();
		$GRAPH.="<BR><BR><a name='graph".$LISTIDlists[$i]."'/><table border='0' cellpadding='0' cellspacing='2' width='800'>";
		$GRAPH.="<tr><th width='50%' class='grey_graph_cell' id='graph".$LISTIDlists[$i]."1'><a href='#' onClick=\"Draw".$LISTIDlists[$i]."Graph('FLAGS', '1'); return false;\">STATUS FLAG BREAKDOWN</a></th><th width='50%' class='grey_graph_cell' id='graph".$LISTIDlists[$i]."2'><a href='#' onClick=\"Draw".$LISTIDlists[$i]."Graph('STATUS', '2'); return false;\">STATUS BREAKDOWN</a></th></tr>";
		$GRAPH.="<tr><td colspan='4' class='graph_span_cell'><span id='status_".$LISTIDlists[$i]."_graph'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";
		$graph_header="<table cellspacing='1' cellpadding='0' bgcolor='white' class='horizontalgraph'>";
		$graph_header.="<caption align='top'>$LISTIDlists[$i] - $LISTIDlist_names[$i] ($LISTIDlist_active[$i])<br>TOTAL LEADS: $LISTIDcalls[$i]</caption>";
		$FLAGS_graph=$graph_header."<tr><th class='thgraph' scope='col'>FLAG</th><th class='thgraph' scope='col'>COUNT / %</th></tr>";
		$STATUS_graph=$graph_header."<tr><th class='thgraph' scope='col'>STATUS</th><th class='thgraph' scope='col'>COUNT</th></tr>";

		$CSV_text4.="\"LIST ID: $LISTIDlists[$i]\",\"$LISTIDlist_names[$i]\",\"$LISTIDlist_active[$i]\"\n";
		$CSV_text4.="\"TOTAL LEADS:\",\"$header_list_count\"\n\n";

		$HA_count=0;
		$HA_percent=0;
		$SALE_count=0;
		$SALE_percent=0;
		$DNC_count=0;
		$DNC_percent=0;
		$CC_count=0;
		$CC_percent=0;
		$NI_count=0;
		$NI_percent=0;
		$UW_count=0;
		$UW_percent=0;

		$stmt="select count(*) from vicidial_list where list_id='$LISTIDlists[$i]' and status IN($human_answered_statuses);";
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$MAIN.="$stmt\n";}
		$HA_results = mysql_num_rows($rslt);
		if ($HA_results > 0)
			{
			$row=mysql_fetch_row($rslt);
			$HA_count = $row[0];
			if ($HA_count > 0)
				{
				$HA_percent = ( ($HA_count / $LISTIDcalls[$i]) * 100);
				}
			}
		if ($HA_count>$max_flags) {$max_flags=$HA_count;}
		$stmt="select count(*) from vicidial_list where list_id='$LISTIDlists[$i]' and status IN($sale_statuses);";
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$MAIN.="$stmt\n";}
		$SALE_results = mysql_num_rows($rslt);
		if ($SALE_results > 0)
			{
			$row=mysql_fetch_row($rslt);
			$SALE_count = $row[0];
			if ($SALE_count > 0)
				{
				$SALE_percent = ( ($SALE_count / $LISTIDcalls[$i]) * 100);
				}
			}
		if ($SALE_count>$max_flags) {$max_flags=$SALE_count;}
		$stmt="select count(*) from vicidial_list where list_id='$LISTIDlists[$i]' and status IN($dnc_statuses);";
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$MAIN.="$stmt\n";}
		$DNC_results = mysql_num_rows($rslt);
		if ($DNC_results > 0)
			{
			$row=mysql_fetch_row($rslt);
			$DNC_count = $row[0];
			if ($DNC_count > 0)
				{
				$DNC_percent = ( ($DNC_count / $LISTIDcalls[$i]) * 100);
				}
			}
		if ($DNC_count>$max_flags) {$max_flags=$DNC_count;}
		$stmt="select count(*) from vicidial_list where list_id='$LISTIDlists[$i]' and status IN($customer_contact_statuses);";
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$MAIN.="$stmt\n";}
		$CC_results = mysql_num_rows($rslt);
		if ($CC_results > 0)
			{
			$row=mysql_fetch_row($rslt);
			$CC_count = $row[0];
			if ($CC_count > 0)
				{
				$CC_percent = ( ($CC_count / $LISTIDcalls[$i]) * 100);
				}
			}
		if ($CC_count>$max_flags) {$max_flags=$CC_count;}
		$stmt="select count(*) from vicidial_list where list_id='$LISTIDlists[$i]' and status IN($not_interested_statuses);";
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$MAIN.="$stmt\n";}
		$NI_results = mysql_num_rows($rslt);
		if ($NI_results > 0)
			{
			$row=mysql_fetch_row($rslt);
			$NI_count = $row[0];
			if ($NI_count > 0)
				{
				$NI_percent = ( ($NI_count / $LISTIDcalls[$i]) * 100);
				}
			}
		if ($NI_count>$max_flags) {$max_flags=$NI_count;}
		$stmt="select count(*) from vicidial_list where list_id='$LISTIDlists[$i]' and status IN($unworkable_statuses);";
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$MAIN.="$stmt\n";}
		$UW_results = mysql_num_rows($rslt);
		if ($UW_results > 0)
			{
			$row=mysql_fetch_row($rslt);
			$UW_count = $row[0];
			if ($UW_count > 0)
				{
				$UW_percent = ( ($UW_count / $LISTIDcalls[$i]) * 100);
				}
			}
		if ($UW_count>$max_flags) {$max_flags=$UW_count;}

		$HA_percent =	sprintf("%6.2f", "$HA_percent"); while(strlen($HA_percent)>6) {$HA_percent = substr("$HA_percent", 0, -1);}
		$SALE_percent =	sprintf("%6.2f", "$SALE_percent"); while(strlen($SALE_percent)>6) {$SALE_percent = substr("$SALE_percent", 0, -1);}
		$DNC_percent =	sprintf("%6.2f", "$DNC_percent"); while(strlen($DNC_percent)>6) {$DNC_percent = substr("$DNC_percent", 0, -1);}
		$CC_percent =	sprintf("%6.2f", "$CC_percent"); while(strlen($CC_percent)>6) {$CC_percent = substr("$CC_percent", 0, -1);}
		$NI_percent =	sprintf("%6.2f", "$NI_percent"); while(strlen($NI_percent)>6) {$NI_percent = substr("$NI_percent", 0, -1);}
		$UW_percent =	sprintf("%6.2f", "$UW_percent"); while(strlen($UW_percent)>6) {$UW_percent = substr("$UW_percent", 0, -1);}

		$HA_count =	sprintf("%9s", "$HA_count"); while(strlen($HA_count)>9) {$HA_count = substr("$HA_count", 0, -1);}
		$SALE_count =	sprintf("%9s", "$SALE_count"); while(strlen($SALE_count)>9) {$SALE_count = substr("$SALE_count", 0, -1);}
		$DNC_count =	sprintf("%9s", "$DNC_count"); while(strlen($DNC_count)>9) {$DNC_count = substr("$DNC_count", 0, -1);}
		$CC_count =	sprintf("%9s", "$CC_count"); while(strlen($CC_count)>9) {$CC_count = substr("$CC_count", 0, -1);}
		$NI_count =	sprintf("%9s", "$NI_count"); while(strlen($NI_count)>9) {$NI_count = substr("$NI_count", 0, -1);}
		$UW_count =	sprintf("%9s", "$UW_count"); while(strlen($UW_count)>9) {$UW_count = substr("$UW_count", 0, -1);}

		$OUToutput .= "| STATUS FLAGS BREAKDOWN:  (and % of total leads in the list)  |\n";
		$OUToutput .= "|   Human Answer:       $HA_count    $HA_percent%                   |\n";
		$OUToutput .= "|   Sale:               $SALE_count    $SALE_percent%                   |\n";
		$OUToutput .= "|   DNC:                $DNC_count    $DNC_percent%                   |\n";
		$OUToutput .= "|   Customer Contact:   $CC_count    $CC_percent%                   |\n";
		$OUToutput .= "|   Not Interested:     $NI_count    $NI_percent%                   |\n";
		$OUToutput .= "|   Unworkable:         $UW_count    $UW_percent%                   |\n";
		$OUToutput .= "+----+--------------------------------------------+------------+\n";
		$OUToutput .= "     |    STATUS BREAKDOWN:                       |    COUNT   |\n";
		$OUToutput .= "     +--------+-----------------------------------+------------+\n";

		$FLAGS_graph.="  <tr><td class='chart_td first'>HUMAN ANSWER</td><td nowrap class='chart_td value first'><img src='images/bar.png' alt='' width='".round(400*$HA_count/$max_flags)."' height='16' />$HA_count ($HA_percent%)</td></tr>";
		$FLAGS_graph.="  <tr><td class='chart_td'>SALE</td><td nowrap class='chart_td value'><img src='images/bar.png' alt='' width='".round(400*$SALE_count/$max_flags)."' height='16' />$SALE_count ($SALE_percent%)</td></tr>";
		$FLAGS_graph.="  <tr><td class='chart_td'>DNC</td><td nowrap class='chart_td value'><img src='images/bar.png' alt='' width='".round(400*$DNC_count/$max_flags)."' height='16' />$DNC_count ($DNC_percent%)</td></tr>";
		$FLAGS_graph.="  <tr><td class='chart_td'>CUSTOMER CONTACT</td><td nowrap class='chart_td value'><img src='images/bar.png' alt='' width='".round(400*$CC_count/$max_flags)."' height='16' />$CC_count ($CC_percent%)</td></tr>";
		$FLAGS_graph.="  <tr><td class='chart_td'>NOT INTERESTED</td><td nowrap class='chart_td value'><img src='images/bar.png' alt='' width='".round(400*$NI_count/$max_flags)."' height='16' />$NI_count ($NI_percent%)</td></tr>";
		$FLAGS_graph.="  <tr><td class='chart_td last'>UNWORKABLE</td><td nowrap class='chart_td value last><img src='images/bar.png' alt='' width='".round(400*$UW_count/$max_flags)."' height='16' />$UW_count ($UW_percent%)</td></tr>";

		$CSV_text4.="\"STATUS FLAGS BREAKDOWN:\",\"(and % of total leads in the list)\"\n";
		$CSV_text4.="\"Human Answer:\",\"$HA_count\",\"$HA_percent%\"\n";
		$CSV_text4.="\"Sale:\",\"$SALE_count\",\"$SALE_percent%\"\n";
		$CSV_text4.="\"DNC:\",\"$DNC_count\",\"$DNC_percent%\"\n";
		$CSV_text4.="\"Customer Contact:\",\"$CC_count\",\"$CC_percent%\"\n";
		$CSV_text4.="\"Not Interested:\",\"$NI_count\",\"$NI_percent%\"\n";
		$CSV_text4.="\"Unworkable:\",\"$UW_count\",\"$UW_percent%\"\n\n";
		$CSV_text4.="\"STATUS BREAKDOWN:\",\"\",\"COUNT\"\n";

		$stmt="select status,count(*) from vicidial_list where list_id='$LISTIDlists[$i]' group by status order by status;";
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$MAIN.="$stmt\n";}
		$liststatussum_to_print = mysql_num_rows($rslt);
		$r=0;
		while ($r < $liststatussum_to_print)
			{
			$row=mysql_fetch_row($rslt);
			$LISTIDstatus[$r] =	$row[0];
			$LISTIDcounts[$r] =	$row[1];
			$graph_stats[$r][0]=$row[0];
			$graph_stats[$r][1]=$row[1];
			if ($row[1]>$max_status) {$max_status=$row[1];}
				if ($DB) {$MAIN.="$r|$LISTIDstatus[$r]|$LISTIDcounts[$r]|    |$row[0]|$row[1]|<BR>\n";}
			$r++;
			}

		$r=0;
		while ($r < $liststatussum_to_print)
			{
			$LIDstatus = $LISTIDstatus[$r];
			$LIDstatus_format = sprintf("%6s", $LIDstatus);
			$TOTALleads = ($TOTALleads + $LISTIDcounts[$r]);

			$LISTID_status_count =	sprintf("%10s", $LISTIDcounts[$r]); while(strlen($LISTID_status_count)>10) {$LISTID_status_count = substr("$LISTID_status_count", 0, -1);}
			$LISTIDname =	sprintf("%-42s", "$LIDstatus_format | $statname_list[$LIDstatus]"); while(strlen($LISTIDname)>42) {$LISTIDname = substr("$LISTIDname", 0, -1);}
			$graph_stats[$r][0].=" - $statname_list[$LIDstatus]";

			$OUToutput .= "     | $LISTIDname | $LISTID_status_count |\n";
			$CSV_text4.="\"".trim($LIDstatus_format)."\",\"$statname_list[$LIDstatus]\",\"$LISTID_status_count\"\n";
			$r++;
			}
		$TOTALleads =		sprintf("%10s", $TOTALleads);

		$OUToutput .= "     +--------+-----------------------------------+------------+\n";
		$OUToutput .= "     | TOTAL:                                     | $TOTALleads |\n";
		$OUToutput .= "     +--------------------------------------------+------------+\n";

		$CSV_text4.="\"TOTAL:\",\"\",\"$TOTALleads\"\n\n\n";

		for ($d=0; $d<count($graph_stats); $d++) {
			if ($d==0) {$class=" first";} else if (($d+1)==count($graph_stats)) {$class=" last";} else {$class="";}
			$STATUS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][1]/$max_status)."' height='16' />".$graph_stats[$d][1]."</td></tr>";
		}
		$STATUS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTALleads)."</th></tr></table>";
		$JS_onload.="\tDraw".$LISTIDlists[$i]."Graph('FLAGS', '1');\n"; 
		$JS_text.="function Draw".$LISTIDlists[$i]."Graph(graph, th_id) {\n";
		$JS_text.="	var FLAGS_graph=\"$FLAGS_graph\";\n";
		$JS_text.="	var STATUS_graph=\"$STATUS_graph\";\n";
		$JS_text.="\n";
		$JS_text.="	for (var i=1; i<=2; i++) {\n";
		$JS_text.="		var cellID=\"graph".$LISTIDlists[$i]."\"+i;\n";
		$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
		$JS_text.="	}\n";
		$JS_text.="	var cellID=\"graph".$LISTIDlists[$i]."\"+th_id;\n";
		$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
		$JS_text.="	var graph_to_display=eval(graph+\"_graph\");\n";
		$JS_text.="	document.getElementById('status_".$LISTIDlists[$i]."_graph').innerHTML=graph_to_display;\n";
		$JS_text.="}\n";
		# $OUToutput.=$GRAPH;

		$i++;
		}




	if ($report_display_type=="HTML")
		{
		$MAIN.=$GRAPH;
		}
	else
		{
		$MAIN.="$OUToutput";
		}



	$ENDtime = date("U");
	$RUNtime = ($ENDtime - $STARTtime);
	$MAIN.="\nRun Time: $RUNtime seconds|$db_source\n";
	$MAIN.="</PRE>\n";
	$MAIN.="</TD></TR></TABLE>\n";
	$MAIN.="</BODY></HTML>\n";

	}

	if ($file_download>0) {
		$FILE_TIME = date("Ymd-His");
		$CSVfilename = "AST_LISTS_campaign_stats_$US$FILE_TIME.csv";
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
		$JS_onload.="}\n";
		$JS_text.=$JS_onload;
		$JS_text.="</script>\n";

		echo $HEADER;
		echo $JS_text;
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
