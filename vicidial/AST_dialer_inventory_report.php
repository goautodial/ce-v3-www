<?php 
# AST_dialer_inventory_report.php
# 
# Copyright (C) 2013  Joe Johnson <freewermadmin@gmail.com>    LICENSE: AGPLv2
#                     Matt Florell <vicidial@gmail.com>
#
# NOTES:
# - For snapshots, the AST_dialer_inventory_snapshot.pl script should be put in
#    the crontab and run on a nightly basis after-hours
#
#
# CHANGES
# 111013-0054 - First build
# 111106-1327 - Reformatting, other minor changes
# 111230-1145 - Debugging additions and more minor changes
# 120221-2237 - Added totals, list options, other small changes
# 130414-0131 - Added report logging
#

$startMS = microtime();

error_reporting(0);

require("dbconnect.php");
require("functions.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["group"]))					{$group=$_GET["group"];}
	elseif (isset($_POST["group"]))			{$group=$_POST["group"];}
if (isset($_GET["report_type"]))			{$report_type=$_GET["report_type"];}
	elseif (isset($_POST["report_type"]))	{$report_type=$_POST["report_type"];}
if (isset($_GET["selected_list"]))			{$selected_list=$_GET["selected_list"];}
	elseif (isset($_POST["selected_list"]))	{$selected_list=$_POST["selected_list"];}
if (isset($_GET["SUBMIT"]))					{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))		{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["time_setting"]))			{$time_setting=$_GET["time_setting"];}
	elseif (isset($_POST["time_setting"]))	{$time_setting=$_POST["time_setting"];}
if (isset($_GET["file_download"]))			{$file_download=$_GET["file_download"];}
	elseif (isset($_POST["file_download"]))	{$file_download=$_POST["file_download"];}
if (isset($_GET["report_source"]))			{$report_source=$_GET["report_source"];}
	elseif (isset($_POST["report_source"]))	{$report_source=$_POST["report_source"];}
if (isset($_GET["DB"]))						{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))			{$DB=$_POST["DB"];}
if (isset($_GET["DBX"]))					{$DBX=$_GET["DBX"];}
	elseif (isset($_POST["DBX"]))			{$DBX=$_POST["DBX"];}
if (isset($_GET["snapshot_time"]))			{$snapshot_time=$_GET["snapshot_time"];}
	elseif (isset($_POST["snapshot_time"]))	{$snapshot_time=$_POST["snapshot_time"];}
if (isset($_GET["override_24hours"]))			{$override_24hours=$_GET["override_24hours"];}
	elseif (isset($_POST["override_24hours"]))	{$override_24hours=$_POST["override_24hours"];}

$MT[0]='';
$NOW_DATE = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");
$time_start = microtime(true);
$STARTtime = date("U");
if (!isset($group)) {$group = '';}
if (!isset($query_date)) {$query_date = $NOW_DATE;}
if (!isset($end_date)) {$end_date = $NOW_DATE;}


$report_name = 'Dialer Inventory Report';
$db_source = 'M';

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,outbound_autodial_active,slave_db_server,reports_use_slave_db FROM system_settings;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$HTML_header.="$stmt\n";}
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

$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 6 and view_reports='1' and active='Y';";
if ($DB) {$HTML_header.="|$stmt|\n";}
if ($non_latin > 0) { $rslt=mysql_query("SET NAMES 'UTF8'");}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$auth=$row[0];

$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level='7' and view_reports='1' and active='Y';";
if ($DB) {$HTML_header.="|$stmt|\n";}
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
	#$HTML_header.="<!-- Using slave server $slave_db_server $db_source -->\n";
	}

$stmt="SELECT user_group from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 6 and view_reports='1' and active='Y';";
if ($DB) {$HTML_header.="|$stmt|\n";}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$LOGuser_group =			$row[0];

$stmt="SELECT allowed_campaigns,allowed_reports from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {$HTML_header.="|$stmt|\n";}
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

$inventory_allow_realtime = 0;
if (file_exists('options.php'))
	{
	require('options.php');
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

$i=0;
$group_string='|';
$group_ct = count($group);
while($i < $group_ct)
	{
	$group_string .= "$group[$i]|";
	$i++;
	}

$stmt="SELECT campaign_id from vicidial_campaigns  where campaign_id in (SELECT distinct campaign_id from vicidial_lists where inventory_report='Y') $LOGallowed_campaignsSQL order by campaign_id;";
$rslt=mysql_query($stmt, $link);
if ($DB) { echo "$stmt\n";}
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
	$group_SQL = "and campaign_id IN($group_SQL)";
	}

$stmt="SELECT list_id, list_name from vicidial_lists where inventory_report='Y' $LOGallowed_campaignsSQL order by list_id, list_name;";
$rslt=mysql_query($stmt, $link);
if ($DB) {$HTML_header.="$stmt\n";}
$lists_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $lists_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$lists[$i] =		$row[0];
	$list_names[$i] =	$row[1];
	$i++;
	}

$campaign_span_txt="Campaigns:<BR>";
$campaign_span_txt.="<SELECT SIZE=5 NAME=group[] multiple>";
if  (eregi("--ALL--",$group_string))
	{$campaign_span_txt.="<option value=\"--ALL--\" selected>-- ALL CAMPAIGNS --</option>";}
else
	{$campaign_span_txt.="<option value=\"--ALL--\">-- ALL CAMPAIGNS --</option>";}
$o=0;
while ($campaigns_to_print > $o)
	{
	if (eregi("$groups[$o]\|",$group_string)) {$campaign_span_txt.="<option selected value=\"$groups[$o]\">$groups[$o]</option>";}
	  else {$campaign_span_txt.="<option value=\"$groups[$o]\">$groups[$o]</option>";}
	$o++;
	}
$campaign_span_txt.="</SELECT>";

$list_span_txt="Lists:<BR>";
$list_span_txt.="<SELECT NAME='selected_list'>";
$o=0;
if ($selected_list) 
	{
	$list_span_txt.="<option value=\"$selected_list\" selected>$selected_list</option>";
	}
while ($lists_to_print>$o) 
	{
	$list_span_txt.="<option value=\"$lists[$o]\">$lists[$o] - $list_names[$o]</option>";
	$o++;
	}
$list_span_txt.="</SELECT>";

$snapshot_span_txt="Snapshot time:<BR>";
$snapshot_stmt="SELECT distinct snapshot_time from dialable_inventory_snapshots order by snapshot_time desc limit 100;";
if ($DBX > 0) {$HTML_header.= "|$snapshot_stmt|\n";}
$snapshot_rslt=mysql_query($snapshot_stmt, $link);
$snapshot_span_txt.="<SELECT NAME='snapshot_time'>\n";
if ($snapshot_time) {$snapshot_span_txt.="\t<option value=\"$snapshot_time\" selected>$snapshot_time</option>\n";}
while ($ss_row=mysql_fetch_row($snapshot_rslt)) 
	{
	$snapshot_span_txt.="\t<option value=\"$ss_row[0]\">$ss_row[0]</option>\n";
	}
$snapshot_span_txt.="</SELECT>\n";


$HTML_header.="<HTML>\n";
$HTML_header.="<HEAD>\n";
$HTML_header.="<STYLE type='text/css'>\n";
$HTML_header.="<!--\n";
$HTML_header.="   .green {color: white; background-color: green}\n";
$HTML_header.="   .red {color: white; background-color: red}\n";
$HTML_header.="   .blue {color: white; background-color: blue}\n";
$HTML_header.="   .purple {color: white; background-color: purple}\n";
$HTML_header.="-->\n";
$HTML_header.=" </STYLE>\n";

if ($report_type=='LIST') {$onload="onload=\"ToggleSpan('list_span', 'campaign_span')\"";}

$HTML_header.="<script language='Javascript'>\n";
$HTML_header.="function ToggleSpan(show_span, hide_span) {\n";
$HTML_header.="\n";
$HTML_header.="	if (show_span) {document.getElementById(show_span).style.display = 'block';}\n";
$HTML_header.="	if (hide_span) {document.getElementById(hide_span).style.display = 'none';}\n";
$HTML_header.="}\n";
$HTML_header.="</script>\n";
$HTML_header.="\n";
$HTML_header.="<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=utf-8'>\n";
$HTML_header.="<TITLE>$report_name</TITLE></HEAD><BODY $onload BGCOLOR=WHITE marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>\n";
$HTML_header.="<PRE>\n";

$rpt_header="";
####### STAT GENERATION #######
require("count_functions.inc");
function GetListCount($list_id, $inventory_ptnstr) 
	{
	global $list_start_inv;
	global $new_count;
	global $link;
	global $total_calls;
	$ct_stmt="SELECT status, called_count, count(*) From vicidial_list where list_id='$list_id' group by status, called_count order by status, called_count;";
	if ($DBX > 0) {$HTML_header.= "|$ct_stmt|\n";}
	$ct_rslt=mysql_query($ct_stmt, $link);
	$new_count=0; $total_calls=0;
	while ($ct_row=mysql_fetch_row($ct_rslt)) 
		{
		$list_start_inv+=$ct_row[2];
		$total_calls+=($ct_row[1]*$ct_row[2]);
		if (preg_match('/|$ct_row[0]|/', $inventory_ptnstr) && $ct_row[1]=="0") {$new_count+=$ct_row[2];} 
		}
	}

if ($SUBMIT) 
	{

	$total_list_start_inv=0;
	$total_dialable_count=0;
	$total_dialable_count_nofilter=0;
	$total_dialable_count_oneoff=0;
	$total_dialable_count_inactive=0;
	$total_total_calls=0;
	$total_average_call_count=0;
	$total_penetration=0;

	if ($snapshot_time && $report_source=="SNAPSHOT") 
		{
		$rpt_header="SNAPSHOT from $snapshot_time\n";
		$stmt="SELECT distinct shift_data from dialable_inventory_snapshots where snapshot_time='$snapshot_time' and time_setting='$time_setting' $time_clause order by campaign_id, list_id;";
		if ($DBX > 0) {$HTML_header.= "|$stmt|\n";}
		$rslt=mysql_query($stmt, $link);
		$shift_SQL_str="";
		while ($shift_row=mysql_fetch_array($rslt)) 
			{
			$shift_data1=explode("|", $shift_row["shift_data"]);
			for ($i=0; $i<count($shift_data1); $i++) 
				{
				$shift_data2=explode(",", $shift_data1);
				$shift_SQL_str.="'$shift_data2[0]',";
				}
			}
		$shift_SQL_str=substr($shift_SQL_str, 0, -1);

		$shift_stmt="SELECT shift_id from vicidial_shifts where report_option='Y' order by report_rank, shift_start_time asc;";
		if ($DBX > 0) {$HTML_header.= "|$shift_stmt|\n";}
		$shift_rslt=mysql_query($shift_stmt, $link);
		$c=0;
		while($shift_row=mysql_fetch_array($shift_rslt)) 
			{
			$rpt_header_SHIFTS.=" ".sprintf("%8s", substr($shift_row["shift_id"],0,8))." |";
			$CSV_header_SHIFTS.="\"$shift_row[shift_id]\",";
			$rpt_header_SHIFTS_lower.="          |";
			$rpt_header_BORDER.="----------+";
			$shift_ary[$c]=$shift_row["shift_id"];
			$total_varname="total_".$shift_row["shift_id"];
			$$total_varname=0;
			if ($DB > 0) {echo "<BR>-$c-$shift_ary[$c]-$shift_row[shift_id]-\n";}
			$c++;
			}
	
		if ($report_type=="CAMPAIGNS") {$time_clause=$group_SQL;}
		if ($report_type=="LIST") {$time_clause="and list_id='$selected_list'";}
		$stmt="SELECT snapshot_id,snapshot_time,list_id,list_name,list_description,campaign_id,list_lastcalldate,list_start_inv,dialable_count,dialable_count_nofilter,dialable_count_oneoff,dialable_count_inactive,average_call_count,penetration,shift_data,time_setting from dialable_inventory_snapshots where snapshot_time='$snapshot_time' and time_setting='$time_setting' $time_clause order by campaign_id, list_id;";
		if ($DBX > 0) {$HTML_header.= "|$stmt|\n";}
		$rslt=mysql_query($stmt, $link);
		while ($row=mysql_fetch_array($rslt)) 
			{
			$row["list_description"]=substr($row["list_description"], 0, 30);
			if (strlen($row["list_description"])>0) {$list_info=$row["list_description"];} else {$list_info=$row["list_name"];}
			$rpt_body.="| ".sprintf("%9s", $row["list_id"])." | ".sprintf("%-30s", $list_info)." | ".sprintf("%8s", $row["campaign_id"])." | ".$row["list_lastcalldate"]." | ".sprintf("%9s", $row["list_start_inv"])." | ".sprintf("%8s", $row["dialable_count"])." | ".sprintf("%8s", $row["dialable_count_nofilter"])." | ".sprintf("%8s", $row["dialable_count_oneoff"])." | ".sprintf("%8s", $row["dialable_count_inactive"])." | ".sprintf("%8s", $row["average_call_count"])." | ".sprintf("%6s", $row["penetration"])."% |";
			$CSV_body.="\"$row[list_id]\",\"$list_info\",\"$row[last_calldate]\",\"$row[campaign_id]\",\"$row[list_start_inv]\",\"$row[dialable_count]\",\"$row[dialable_count_nofilter]\",\"$row[dialable_count_oneoff]\",\"$row[dialable_count_inactive]\",\"$row[average_call_count]\",\"$row[penetration] %\"";

			$total_list_start_inv+=$row["list_start_inv"];
			$total_dialable_count+=$row["dialable_count"];
			$total_dialable_count_nofilter+=$row["dialable_count_nofilter"];
			$total_dialable_count_oneoff+=$row["dialable_count_oneoff"];
			$total_dialable_count_inactive+=$row["dialable_count_inactive"];
			$total_total_calls+=($row["average_call_count"]*$row["list_start_inv"]);

			$shift_data_a=explode("|", $row["shift_data"]);
			$b=0;
			while ($b < count($shift_ary))
				{
				$a=0;
				while ($a < count($shift_data_a))
					{
					$shift_data_b=explode(",", $shift_data_a[$a]);
					$line_match=0;
					if ($shift_ary[$b] == "$shift_data_b[0]")
						{
						$rpt_body.=" ".sprintf("%8s", $shift_data_b[1])." |";
						$CSV_body.=",\"$shift_data_b[1]\"";
						$total_varname="total_".$shift_data_b[0];
						$$total_varname+=$shift_data_b[1];
						$line_match++;
						}
					if ($DB > 0) {echo "<BR>-$a-$b-$shift_ary[$b]-$shift_data_b[0]($shift_data_b[1])-$line_match-\n";}
					$a++;
					}
				$b++;
				}
			$rpt_body.="\n";
			$CSV_body.="\n";
			}
		} 
	else 
		{
		$single_status=1;

		## If time setting is set to 'Local', compile a list of time zone offsets and make an array of those offsets with the amount of time that needs to be added or subtracted 
		if ($time_setting=="LOCAL") 
			{
			$gmt_stmt="SELECT default_local_gmt from system_settings;";
			$gmt_rslt=mysql_query($gmt_stmt, $link);
			$gmt_row=mysql_fetch_row($gmt_rslt);
			$local_offset=$gmt_row[0];
			
			if ($report_type=="CAMPAIGNS") {$time_clause="where list_id in (SELECT list_id from vicidial_lists where inventory_report='Y' ".substr($group_SQL,4).")";}
			if ($report_type=="LIST") {$time_clause="where list_id='$selected_list'";}
			$gmt_stmt="SELECT distinct gmt_offset_now, gmt_offset_now-($local_offset) from vicidial_list $time_clause ;";
			if ($DB) {$HTML_header.=$gmt_stmt."<BR>\n";}
			$gmt_rslt=mysql_query($gmt_stmt, $link);
			while ($gmt_row=mysql_fetch_row($gmt_rslt)) 
				{
				$gmt_row[1]=preg_replace('/25$/', '15', $gmt_row[1]);
				$gmt_row[1]=preg_replace('/75$/', '45', $gmt_row[1]);
				$gmt_row[1]=preg_replace('/\.5$/', '.30', $gmt_row[1]);
				$gmt_row[1]=preg_replace('/\./', ':', $gmt_row[1]);
				$gri = $gmt_row[0];
				$gmt_array[$gri]=$gmt_row[1];
				}
			}

		# Get shift information
		$shift_stmt="SELECT shift_id, shift_name, str_to_date(shift_start_time, '%H%i') as shift_start_time, addtime(str_to_date(shift_start_time, '%H%i'), shift_length) as shift_end_time, if(addtime(str_to_date(shift_start_time, '%H%i'), shift_length)>'23:59:59', '1', '0') as day_offset, shift_weekdays from vicidial_shifts where report_option='Y' order by report_rank, shift_start_time asc;";
		if ($DB) {$HTML_header.="$shift_stmt;\n";}
		$shift_rslt=mysql_query($shift_stmt, $link);
		while($shift_row=mysql_fetch_array($shift_rslt)) 
			{
			$rpt_header_SHIFTS.=" ".sprintf("%8s", substr($shift_row["shift_id"],0,8))." |";
			$rpt_header_SHIFTS_lower.="          |";
			$CSV_header_SHIFTS.="\"$shift_row[shift_id]\",";
			$rpt_header_BORDER.="----------+";
			$shift_ary[$shift_row["shift_id"]][0]=$shift_row["shift_name"];
			$shift_ary[$shift_row["shift_id"]][1]=$shift_row["shift_start_time"];
			$shift_ary[$shift_row["shift_id"]][2]=$shift_row["shift_end_time"];
			$shift_ary[$shift_row["shift_id"]][3]=$shift_row["day_offset"];
			$shift_ary[$shift_row["shift_id"]][4]=$shift_row["shift_weekdays"];
			}

		$rpt_body="";
		if ($group_ct>0 && $report_type=="CAMPAIGNS") 
			{
			for ($i=0; $i<$group_ct; $i++) 
				{
				$campaign_stmt="SELECT call_count_limit, call_count_target, dial_statuses, local_call_time, drop_lockout_time from vicidial_campaigns where campaign_id='$group[$i]' $LOGallowed_campaignsSQL;";
				if ($DB) {$HTML_header.="$campaign_stmt;\n";}
				$campaign_rslt=mysql_query($campaign_stmt, $link);	
				$campaign_row=mysql_fetch_row($campaign_rslt);
				$call_count_limit=$campaign_row[0];
				$call_count_target=$campaign_row[1];
				$active_dial_statuses=$campaign_row[2];
				$local_call_time=$campaign_row[3]; if ($override_24hours) {$local_call_time="24hours";}
				$drop_lockout_time=$campaign_row[4];

				$stmt="SELECT distinct status from vicidial_statuses where completed='N' UNION SELECT distinct status from vicidial_campaign_statuses where completed='N' and campaign_id='$group[$i]' $LOGallowed_campaignsSQL";
				if ($DB) {$HTML_header.="$stmt\n";}
				$rslt=mysql_query($stmt, $link);
				$dial_statuses=" ";
				$inventory_statuses=" ";
				$inventory_ptnstr="|";
				$inactive_dial_statuses=" ";
				while ($row=mysql_fetch_row($rslt)) 
					{
					$dial_statuses.="$row[0] ";
					$inventory_statuses.="'$row[0]',";
					$inventory_ptnstr.="$row[0]|";
					if (!preg_match("/ $row[0] /", "$active_dial_statuses")) {$inactive_dial_statuses.="$row[0] ";}
					}
				$inventory_statuses=substr($inventory_statuses, 0, -1);
				if ($DB) {$HTML_header.= "         CAMPAIGN DIAL STATUSES: |$active_dial_statuses|\n";}
				if ($DB) {$HTML_header.= "     INVENTORY STATUSES RESULTS: |$inventory_statuses|\n";}
				if ($DB) {$HTML_header.= "      INACTIVE STATUSES RESULTS: |$inactive_dial_statuses|\n";}

				$filter_stmt="SELECT lead_filter_sql from vicidial_campaigns v, vicidial_lead_filters vlf where v.campaign_id='$group[$i]' and v.lead_filter_id=vlf.lead_filter_id limit 1;";
				if ($DB) {$HTML_header.="$filter_stmt;\n";}
				$filter_rslt=mysql_query($filter_stmt, $link);	
				$filter_row=mysql_fetch_row($filter_rslt);
				if (strlen($filter_row[0])>0) {$filter_SQL=" and $filter_row[0]";} else {$filter_SQL="";}
				$filter_SQL = preg_replace("/\\\/",'',$filter_SQL);
				
				$lists_stmt="SELECT list_id, list_name, list_description, if(list_lastcalldate is null, '*** Not called *** ', list_lastcalldate) as list_lastcalldate from vicidial_lists where campaign_id='$group[$i]' and inventory_report='Y' $LOGallowed_campaignsSQL order by list_id asc;";
				if ($DB) {$HTML_header.="$lists_stmt;\n";}
				$lists_rslt=mysql_query($lists_stmt, $link);
				while ($lists_row=mysql_fetch_array($lists_rslt)) 
					{
					$list_id=$lists_row["list_id"];
					$list_name=$lists_row["list_name"];
					$lists_row["list_description"]=substr($lists_row["list_description"], 0, 30);
					$list_description=$lists_row["list_description"];
					$last_calldate=$lists_row["list_lastcalldate"];
					if (strlen($list_description)>0) {$list_info=$list_description;} else {$list_info=$list_name;}

					$list_start_inv=0;
					GetListCount($list_id, $inventory_ptnstr);
					if ($list_start_inv>0) {$average_calls=sprintf("%.1f", $total_calls/$list_start_inv);} else {$average_calls="0.0";}
					$Xdialable_count_nofilter = dialable_leads($DB,$link,$local_call_time,"$dial_statuses",$list_id,$drop_lockout_time,$call_count_limit,$single_status,"");
					if (strlen($inactive_dial_statuses)>1) 
						{
						$Xdialable_inactive_count = dialable_leads($DB,$link,$local_call_time,"$inactive_dial_statuses",$list_id,$drop_lockout_time,$call_count_limit,$single_status,"$filter_SQL");
						} 
					else 
						{
						$Xdialable_inactive_count = 0;
						}

					$oneoff_SQL=$filter_SQL." and (called_count < $call_count_limit-1) ";
					$oneoff_count = dialable_leads($DB,$link,$local_call_time,"$dial_statuses",$list_id,$drop_lockout_time,$call_count_limit,$single_status,"$oneoff_SQL");

					$full_dialable_SQL="";
					$Xdialable_count = dialable_leads($DB,$link,$local_call_time,"$dial_statuses",$list_id,$drop_lockout_time,$call_count_limit,$single_status,"$filter_SQL");

					if ($list_start_inv>0) {$penetration=sprintf("%.2f", (100*($list_start_inv-$Xdialable_count)/$list_start_inv));} else {$penetration="0.00";}

					$rpt_body.="| ".sprintf("%9s", $list_id)." | ".sprintf("%-30s", $list_info)." | ".sprintf("%8s", $group[$i])." | ".$last_calldate." | ".sprintf("%9s", $list_start_inv)." | ".sprintf("%8s", $Xdialable_count)." | ".sprintf("%8s", $Xdialable_count_nofilter)." | ".sprintf("%8s", $oneoff_count)." | ".sprintf("%8s", $Xdialable_inactive_count)." | ".sprintf("%8s", $average_calls)." | ".sprintf("%6s", $penetration)."% |";
					$CSV_body.="\"$list_id\",\"$list_info\",\"$last_calldate\",\"$group[$i]\",\"$list_start_inv\",\"$Xdialable_count\",\"$Xdialable_count_nofilter\",\"$oneoff_count\",\"$Xdialable_inactive_count\",\"$average_calls\",\"$penetration %\"";

					$total_list_start_inv+=$list_start_inv;
					$total_dialable_count+=$Xdialable_count;
					$total_dialable_count_nofilter+=$Xdialable_count_nofilter;
					$total_dialable_count_oneoff+=$oneoff_count;
					$total_dialable_count_inactive+=$Xdialable_inactive_count;
					$total_total_calls+=$total_calls;

					# $stat_stmt="SELECT call_date, dayofweek(call_date) from vicidial_log where lead_id in (SELECT lead_id from vicidial_list where list_id='$list_id' and called_count<='$called_count_limit' and status in ($inventory_statuses) $filter_SQL";
					# SELECT lead_id, count(*) as count from vicidial_log where extract(HOUR_MINUTE FROM call_date)>='900' and extract(HOUR_MINUTE FROM call_date)<='1700' and dayofweek(call_date) in (2,3,4,5,6) and lead_id in (SELECT lead_id from vicidial_list where list_id=41073) group by lead_id order by lead_id;

					$shift_ary2=$shift_ary;
					while (list($key, $val)=each($shift_ary2)) 
						{
						$total_shift_count=0;
						$gmt_stmt="SELECT distinct gmt_offset_now from vicidial_list where list_id='$list_id';";
						if ($DB) {$HTML_header.="<B>$gmt_stmt</B>\n";}
						$gmt_rslt=mysql_query($gmt_stmt, $link);
						while ($gmt_row=mysql_fetch_row($gmt_rslt)) 
							{
							if ($time_setting=="LOCAL") 
								{
								$gri = $gmt_row[0];
								$offset_hours=$gmt_array[$gri];
								} 
							else 
								{
								$offset_hours=0;
								}

							if (strlen($val[4])>0) 
								{
								if ($val[3]==0) 
									{
									$shift_days_SQL=" and time(addtime(call_date, '$offset_hours'))>='$val[1]' and time(addtime(call_date, '$offset_hours'))<='$val[2]' ";
									$day_str="";
									for ($j=0; $j<strlen($val[4]); $j++) 
										{
										$day=substr($val[4], $j, 1);
										$day++;
										$day_str.="$day,";
										}
									$day_str=substr($day_str, 0, -1);
									$shift_days_SQL.="and dayofweek(call_date) in ($day_str)";
									} 
								else 
									{
									$shift_days_SQL=" and (";
									for ($j=0; $j<strlen($val[4]); $j++) 
										{
										$day=substr($val[4], $j, 1);
										$day++;
										$next_day_hours=(substr("0".substr($val[2], 0, 2)%24, -2).substr($val[2], 2));
										$next_day=($day%7)+1;
										$shift_days_SQL.=" ((time(addtime(call_date, '$offset_hours'))>='$val[1]' and dayofweek(call_date)='$day') or (time(addtime(call_date, '$offset_hours'))<='$next_day_hours' and dayofweek(call_date)='$next_day')) or ";
										# call_date, time(call_date), addtime(call_date, '-1:00:00'), time(addtime(call_date, '-1:30')), time(call_date)>=addtime('09:00', '00:04:00')
										}
									$shift_days_SQL=substr($shift_days_SQL, 0, -3).")";
									}
								} 
							else 
								{
								$shift_days_SQL="";
								}

							$shift_stmt="SELECT count(*) from (SELECT lead_id, count(*) as count from vicidial_log where lead_id in (SELECT lead_id from vicidial_list where $full_dialable_SQL and gmt_offset_now='$gmt_row[0]' $filter_SQL) $shift_days_SQL group by lead_id) as count_table where count_table.count>='$call_count_target';";

							$shift_rslt=mysql_query($shift_stmt, $link);	
							$shift_row=mysql_fetch_row($shift_rslt);
							if ($DB) {$HTML_header.="<B>$shift_stmt<BR>$shift_row[0]</B>\n";}
							$total_shift_count+=$shift_row[0];
							}
						$shift_count=$Xdialable_count-$total_shift_count;
						$rpt_body.=" ".sprintf("%8s", $shift_count)." |";
						$total_varname="total_".$key;
						$$total_varname+=$shift_count;
						$CSV_body.=",\"$shift_count\"";
						}
					$rpt_body.="\n";
					$CSV_body.="\n";
					if (!$file_download) {echo $HTML_header; $HTML_header=""; flush();}
					}
				} 
			} 
		else if ($selected_list && $report_type=="LIST") 
			{
			$campaign_stmt="SELECT call_count_limit, call_count_target, dial_statuses, local_call_time, drop_lockout_time, v.campaign_id from vicidial_campaigns v, vicidial_lists vl where inventory_report='Y' and vl.list_id='$selected_list' and vl.campaign_id=v.campaign_id;";
			if ($DB) {$HTML_header.="$campaign_stmt;\n";}
			$campaign_rslt=mysql_query($campaign_stmt, $link);	
			$campaign_row=mysql_fetch_row($campaign_rslt);
			$call_count_limit=$campaign_row[0];
			$call_count_target=$campaign_row[1];
			$active_dial_statuses=$campaign_row[2];
			$local_call_time=$campaign_row[3];  if ($override_24hours) {$local_call_time="24hours";}
			$drop_lockout_time=$campaign_row[4];
			$campaign_id=$campaign_row[5];

			$stmt="SELECT distinct status from vicidial_statuses where completed='N' UNION SELECT distinct status from vicidial_campaign_statuses where completed='N' and campaign_id=(SELECT campaign_id from vicidial_lists where list_id='$selected_list' and inventory_report='Y' $LOGallowed_campaignsSQL);";
			if ($DB) {$HTML_header.="$stmt\n";}
			$rslt=mysql_query($stmt, $link);
			$inactive_dial_statuses=" ";
			$dial_statuses=" ";
			$inventory_statuses="";
			$inventory_ptnstr="|";
			while ($row=mysql_fetch_row($rslt)) 
				{
				$dial_statuses.="$row[0] ";
				$inventory_statuses.="'$row[0]',";
				$inventory_ptnstr.="$row[0]|";
				if (!preg_match("/ $row[0] /", "$active_dial_statuses")) {$inactive_dial_statuses.="$row[0] ";}
				}
			$inventory_statuses=substr($inventory_statuses, 0, -1);
			if ($DB) {$HTML_header.= "         CAMPAIGN DIAL STATUSES: |$active_dial_statuses|\n";}
			if ($DB) {$HTML_header.= "                  DIAL STATUSES: |$dial_statuses|\n";}
			if ($DB) {$HTML_header.= "     INVENTORY STATUSES RESULTS: |$inventory_statuses|\n";}
			if ($DB) {$HTML_header.= "      INACTIVE STATUSES RESULTS: |$inactive_dial_statuses|\n";}

			if ($DB) {$HTML_header.="<B>$campaign_stmt; - $dial_statuses</B>\n";}

			$filter_stmt="SELECT lead_filter_sql from vicidial_campaigns v, vicidial_lead_filters vlf where v.campaign_id='$campaign_id' and v.lead_filter_id=vlf.lead_filter_id limit 1;";
			if ($DB) {$HTML_header.="$filter_stmt;\n";}
			$filter_rslt=mysql_query($filter_stmt, $link);	
			$filter_row=mysql_fetch_row($filter_rslt);
			if (strlen($filter_row[0])>0) {$filter_SQL=" and $filter_row[0]";} else {$filter_SQL="";}
			$filter_SQL = preg_replace("/\\\/",'',$filter_SQL);

			$lists_stmt="SELECT list_id, list_name, list_description, if(list_lastcalldate is null, '*** Not called *** ', list_lastcalldate) as list_lastcalldate, campaign_id from vicidial_lists where list_id='$selected_list' and inventory_report='Y' $LOGallowed_campaignsSQL order by list_id asc;";
			if ($DB) {$HTML_header.="$lists_stmt;\n";}
			$lists_rslt=mysql_query($lists_stmt, $link);
			while ($lists_row=mysql_fetch_array($lists_rslt)) 
				{
				$list_id=$lists_row["list_id"];
				$list_name=$lists_row["list_name"];
				$lists_row["list_description"]=substr($lists_row["list_description"], 0, 30);
				$list_description=$lists_row["list_description"];
				$last_calldate=$lists_row["list_lastcalldate"];
				$campaign_id=$lists_row["campaign_id"];
				if (strlen($list_description)>0) {$list_info=$list_description;} else {$list_info=$list_name;}
				$list_call_inv=0;
				GetListCount($list_id, $inventory_ptnstr);
				if ($list_start_inv>0) {$average_calls=sprintf("%.1f", $total_calls/$list_start_inv);} else {$average_calls="0.0";}

				### For TOTAL counts, needs to be here instead of with other "total" variables further down in this particular report
				$total_total_calls+=$total_calls;

				$Xdialable_count_nofilter = dialable_leads($DB,$link,$local_call_time,"$dial_statuses",$selected_list,$drop_lockout_time,$call_count_limit,$single_status,"");
				if (strlen($inactive_dial_statuses)>1) 
					{
					$Xdialable_inactive_count = dialable_leads($DB,$link,$local_call_time,"$inactive_dial_statuses",$selected_list,$drop_lockout_time,$call_count_limit,$single_status,"$filter_SQL");
					} 
				else 
					{
					$Xdialable_inactive_count = 0;
					}

				$oneoff_SQL=$filter_SQL." and (called_count < $call_count_limit-1) ";
				$oneoff_count = dialable_leads($DB,$link,$local_call_time,"$dial_statuses",$selected_list,$drop_lockout_time,$call_count_limit,$single_status,"$oneoff_SQL");

				$full_dialable_SQL="";
				$Xdialable_count = dialable_leads($DB,$link,$local_call_time,"$dial_statuses",$selected_list,$drop_lockout_time,$call_count_limit,$single_status,"$filter_SQL");
				if ($DB > 0) {echo "FULL DIALABLE SQL: |$full_dialable_SQL|";}
				}

			if ($list_start_inv>0) {$penetration=sprintf("%.2f", (100*($list_start_inv-$Xdialable_count)/$list_start_inv));} else {$penetration="0.00";}

			$rpt_body.="| ".sprintf("%9s", $list_id)." | ".sprintf("%-30s", $list_info)." | ".sprintf("%8s", $campaign_id)." | ".$last_calldate." | ".sprintf("%9s", $list_start_inv)." | ".sprintf("%8s", $Xdialable_count)." | ".sprintf("%8s", $Xdialable_count_nofilter)." | ".sprintf("%8s", $oneoff_count)." | ".sprintf("%8s", $Xdialable_inactive_count)." | ".sprintf("%8s", $average_calls)." | ".sprintf("%6s", $penetration)."% |";
			$CSV_body.="\"$list_id\",\"$list_info\",\"$last_calldate\",\"$campaign_id\",\"$list_start_inv\",\"$Xdialable_count\",\"$Xdialable_count_nofilter\",\"$oneoff_count\",\"$Xdialable_inactive_count\",\"$average_calls\",\"$penetration %\"";

			$total_list_start_inv+=$list_start_inv;
			$total_dialable_count+=$Xdialable_count;
			$total_dialable_count_nofilter+=$Xdialable_count_nofilter;
			$total_dialable_count_oneoff+=$oneoff_count;
			$total_dialable_count_inactive+=$Xdialable_inactive_count;

			$shift_ary2=$shift_ary;
			while (list($key, $val)=each($shift_ary2)) 
				{
				$total_shift_count=0; 
				$total_nofilter_shift_count=0; 
				$total_oneoff_shift_count=0; 
				$total_undialable_shift_count=0; 
				$total_nofilter_undialable_shift_count=0;
				$gmt_stmt="SELECT distinct gmt_offset_now from vicidial_list where list_id='$list_id';";
				if ($DB) {$HTML_header.="$gmt_stmt\n";}
				$gmt_rslt=mysql_query($gmt_stmt, $link);
				while ($gmt_row=mysql_fetch_row($gmt_rslt)) 
					{
					if ($time_setting=="LOCAL") 
						{
						$gri = $gmt_row[0];
						$offset_hours=$gmt_array[$gri];
						} 
					else 
						{
						$offset_hours=0;
						}

					if (strlen($val[4])>0) 
						{
						if ($val[3]==0) 
							{
							$shift_days_SQL=" and time(addtime(call_date, '$offset_hours'))>='$val[1]' and time(addtime(call_date, '$offset_hours'))<='$val[2]' ";
							$day_str="";
							for ($i=0; $i<strlen($val[4]); $i++) 
								{
								$day=substr($val[4], $i, 1);
								$day++;
								$day_str.="$day,";
								}
							$day_str=substr($day_str, 0, -1);
							$shift_days_SQL.="and dayofweek(call_date) in ($day_str)";
							} 
						else 
							{
							$shift_days_SQL=" and (";
							for ($i=0; $i<strlen($val[4]); $i++) 
								{
								$day=substr($val[4], $i, 1);
								$day++;
								$next_day_hours=(substr("0".substr($val[2], 0, 2)%24, -2).substr($val[2], 2));
								$next_day=($day%7)+1;
								$shift_days_SQL.=" ((time(addtime(call_date, '$offset_hours'))>='$val[1]' and dayofweek(call_date)='$day') or (time(addtime(call_date, '$offset_hours'))<='$next_day_hours' and dayofweek(call_date)='$next_day')) or ";
								# call_date, time(call_date), addtime(call_date, '-1:00:00'), time(addtime(call_date, '-1:30')), time(call_date)>=addtime('09:00', '00:04:00')
								}
							$shift_days_SQL=substr($shift_days_SQL, 0, -3).")";
							}
						} 
					else 
						{
						$shift_days_SQL="";
						}

#					$dialable_stmt="SELECT count(*) from (SELECT lead_id, count(*) as count from vicidial_log where lead_id in (SELECT lead_id from vicidial_list where $full_dialable_SQL and gmt_offset_now='$gmt_row[0]' $filter_SQL) $shift_days_SQL group by lead_id) as count_table where count_table.count>='$call_count_target'";
#					$dialable_nofilter_stmt="SELECT count(*) from (SELECT lead_id, count(*) as count from vicidial_log where lead_id in (SELECT lead_id from vicidial_list where $full_dialable_SQL and gmt_offset_now='$gmt_row[0]') $shift_days_SQL group by lead_id) as count_table where count_table.count>='$call_count_target'";
#					$undialable_stmt="SELECT count(*) from (SELECT lead_id, count(*) as count from vicidial_log where lead_id in (SELECT lead_id from vicidial_list where $full_dialable_SQL and gmt_offset_now='$gmt_row[0]' $filter_SQL) $shift_days_SQL group by lead_id) as count_table where count_table.count>='$call_count_target'";
#					$undialable_nofilter_stmt="SELECT count(*) from (SELECT lead_id, count(*) as count from vicidial_log where lead_id in (SELECT lead_id from vicidial_list where $full_dialable_SQL and gmt_offset_now='$gmt_row[0]') $shift_days_SQL group by lead_id) as count_table where count_table.count>='$call_count_target'";
#					$oneoff_stmt="SELECT count(*) from (SELECT lead_id, count(*) as count from vicidial_log where lead_id in (SELECT lead_id from vicidial_list where $full_dialable_SQL and gmt_offset_now='$gmt_row[0]' $filter_SQL) $shift_days_SQL group by lead_id) as count_table where count_table.count>='($call_count_target-1)'";
					$shift_stmt="SELECT count(*) from (SELECT lead_id, count(*) as count from vicidial_log where lead_id in (SELECT lead_id from vicidial_list where $full_dialable_SQL and gmt_offset_now='$gmt_row[0]' $filter_SQL) $shift_days_SQL group by lead_id) as count_table where count_table.count>='$call_count_target';";

					$shift_rslt=mysql_query($shift_stmt, $link);	
					$shift_row=mysql_fetch_row($shift_rslt);
					if ($DB) {$HTML_header.="<B>$shift_stmt;<BR>$shift_row[0]</B>\n";}
					$total_shift_count+=$shift_row[0];
					}
				$shift_count=$Xdialable_count-$total_shift_count;
				$rpt_body.=" ".sprintf("%8s", $shift_count)." |";
				$total_varname="total_".$key;
				$$total_varname+=$shift_count;
				$CSV_body.=",\"$shift_count\"";
				}
			$rpt_body.="\n";
			$CSV_body.="\n";
			if (!$file_download) {echo $HTML_header; $HTML_header=""; flush();}
			}
		}

	$rpt_header.="Date: ".date("m/d/Y")." -- Time: ".date("H:i a")."\n\n";
	$rpt_header.="+-----------+--------------------------------+----------+---------------------+-----------+----------+----------+----------+----------+----------+---------+$rpt_header_BORDER\n";
	$rpt_header.="| Call list | List description               | Campaign | Last call date      | Start Inv | Call Inv | Call Inv | Call Inv | Call Inv | Dial Avg | Pen. %  |$rpt_header_SHIFTS\n";
	$rpt_header.="|           |                                |          |                     |           |  Total   | No filtr | One-off  | Inactive |          |         |$rpt_header_SHIFTS_lower\n";
	$rpt_header.="+-----------+--------------------------------+----------+---------------------+-----------+----------+----------+----------+----------+----------+---------+$rpt_header_BORDER\n";

	$CSV_header="\"$report_name\"\n";
	$CSV_header.="\"Date: ".date("m/d/Y")."\",\"\",\"Time: ".date("H:i a")."\"\n\n";
	$CSV_header.="\"Call list\",\"List description\",\"Campaign\",\"Last call date\",\"Start Inv\",\"Call Inv Total\",\"Call Inv - No filter\",\"Call Inv - One-offs\",\"Call Inv - Inactive dialable statuses\",\"Dial Avg\",\"Pen. %\",".substr($CSV_header_SHIFTS,0,-1)."\n";

	$rpt_footer ="+-----------+--------------------------------+----------+---------------------+-----------+----------+----------+----------+----------+----------+---------+$rpt_header_BORDER\n";

	#### PRINT TOTALS ####
	if ($total_list_start_inv>0) {$total_average_call_count=sprintf("%.1f", $total_total_calls/$total_list_start_inv);} else {$average_calls="0.0";}
	if ($total_list_start_inv>0) {$total_penetration=sprintf("%.2f", (100*($total_list_start_inv-$total_dialable_count)/$total_list_start_inv));} else {$total_penetration="0.00";}
	$rpt_footer.="|".sprintf("%76s", "TOTALS")." | ".sprintf("%9s", $total_list_start_inv)." | ".sprintf("%8s", $total_dialable_count)." | ".sprintf("%8s", $total_dialable_count_nofilter)." | ".sprintf("%8s", $total_dialable_count_oneoff)." | ".sprintf("%8s", $total_dialable_count_inactive)." | ".sprintf("%8s", $total_average_call_count)." | ".sprintf("%6s", $total_penetration)."% |";
	$CSV_body.="\"\",\"\",\"\",\"TOTALS\",\"$total_list_start_inv\",\"$total_dialable_count\",\"$total_dialable_count_nofilter\",\"$total_dialable_count_oneoff\",\"$total_dialable_count_inactive\",\"$total_average_call_count\",\"$total_penetration\"";
	$b=0;
	while ($b < count($shift_ary))
		{
		$total_varname="total_".$shift_ary[$b];
		$rpt_footer.=" ".sprintf("%8s", $$total_varname)." |";
		$CSV_body.=",\"".$$total_varname."\"";
		$b++;
		}
	$CSV_body.="\n";
	$rpt_footer.="\n";
	######################

	$rpt_footer.="+-----------+--------------------------------+----------+---------------------+-----------+----------+----------+----------+----------+----------+---------+$rpt_header_BORDER\n";
	}
$HTML_header.="</PRE>\n";
###############################

$HTML_text="<TABLE CELLPADDING=4 CELLSPACING=0><TR><TD>\n";
$HTML_text.="\n";
$HTML_text.="<FORM ACTION='$PHP_SELF' METHOD=GET name=vicidial_report id=vicidial_report>\n";
$HTML_text.="<INPUT TYPE=hidden NAME=DB VALUE='$DB'>\n";
$HTML_text.="<TABLE CELLSPACING=3><TR height='200'><TD VALIGN=TOP>\n";
$HTML_text.="	<table width='*' align='center'>\n";
$HTML_text.="	<tr><td>Report type:</td></tr>\n";
if ($report_type=='LIST') {$cmp_checked=""; $list_checked="checked";} else {$cmp_checked="checked"; $list_checked="";}
$HTML_text.="	<tr><td><input type='radio' name='report_type' value='CAMPAIGNS' onClick=\"ToggleSpan('campaign_span', 'list_span')\" $cmp_checked>Campaigns</td></tr>\n";
$HTML_text.="	<tr><td><input type='radio' name='report_type' value='LIST' onClick=\"ToggleSpan('list_span', 'campaign_span')\" $list_checked>List</td></tr>\n";
$HTML_text.="	</table>\n";
$HTML_text.="</TD>\n";
$HTML_text.="<td width='30'>&nbsp;</td>\n";
$HTML_text.="<TD VALIGN=TOP width=250>\n";
$HTML_text.="<span id='campaign_span' style='display:block;'>$campaign_span_txt</span>\n";
$HTML_text.="<span id='list_span' style='display:none;'>$list_span_txt</span>\n";
$HTML_text.="</TD>\n";
$HTML_text.="<TD VALIGN=TOP>\n";
$HTML_text.="	<table width='*' align='center'>\n";
$HTML_text.="	<tr><td>Time setting:</td></tr>\n";
if ($time_setting=='LOCAL') {$svr_checked=""; $local_checked="checked";} else {$svr_checked="checked"; $local_checked="";}
if ($override_24hours) {$override_checked="checked";}
$HTML_text.="	<tr><td><input type='radio' name='time_setting' value='SERVER' $svr_checked>Server</td></tr>\n";
$HTML_text.="	<tr><td><input type='radio' name='time_setting' value='LOCAL' $local_checked>Local</td></tr>\n";
$HTML_text.="	<tr><td><input type='checkbox' name='override_24hours' value='OVERRIDE' $override_checked>Ignore local campaign call time<BR>(default to 24 hours)</td></tr>\n";
$HTML_text.="	</table>\n";
$HTML_text.="</TD>\n";
$HTML_text.="<TD VALIGN=TOP width='150'>\n";
$HTML_text.="	<table width='*' align='center'>\n";
$HTML_text.="	<tr><td>Report source:</td></tr>\n";
if ($report_source=='REALTIME') {$ss_checked=""; $rt_checked="checked";} else {$ss_checked="checked"; $rt_checked="";}
$HTML_text.="	<tr><td width='150' align='left'><input type='radio' name='report_source' value='SNAPSHOT' onClick=\"ToggleSpan('snapshot_span', '')\" $ss_checked>Snapshot</td></tr>\n";

if ($inventory_allow_realtime > 0)
	{
	$HTML_text.="	<tr><td align='left'><input type='radio' name='report_source' value='REALTIME' onClick=\"ToggleSpan('', 'snapshot_span')\" $rt_checked>Real-time</td></tr>\n";
	}
else
	{
	$HTML_text.="	<tr><td align='left'> &nbsp; </td></tr>\n";
	}

$HTML_text.="	<tr><td align='left'><span id='snapshot_span' style='display:block;'>$snapshot_span_txt</span>\n";
$HTML_text.="	</table>\n";
$HTML_text.="</TD>\n";
$HTML_text.="<TD VALIGN='top' align='center'>\n";
$HTML_text.="<font color='BLACK' face='ARIAL,HELVETICA' size='2'> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href='$PHP_SELF?DB=$DB$groupQS&selected_list=$selected_list&report_type=$report_type&time_setting=$time_setting&report_source=$report_source&snapshot_time=$snapshot_time&file_download=1&SUBMIT=$SUBMIT'>DOWNLOAD</a> | <a href='./admin.php?ADD=999999'>REPORTS</a> </font><BR><BR>\n";
$HTML_text.="<input name='SUBMIT' value='SUBMIT' type='submit'>\n";
$HTML_text.="</TD>\n";
$HTML_text.="</TR>\n";
$HTML_text.="</TABLE>\n";
$HTML_text.="<PRE><FONT SIZE=2>\n\n";

$HTML_text.="$rpt_header";
$HTML_text.="$rpt_body";
$HTML_text.="$rpt_footer";

$time_end = microtime(true);
$ENDtime = date("U");
$time = $ENDtime - $STARTtime;

$HTML_text.="Executed in $time seconds\n";
$HTML_text.="</FONT></PRE>\n";
$HTML_text.="</BODY>\n";
$HTML_text.="</HTML>\n";


if ($file_download>0) 
	{
	$FILE_TIME = date("Ymd-His");
	$CSVfilename = "AST_DIALERinventory_$US$FILE_TIME.csv";
	$CSV_text=$CSV_header.$CSV_body;
	$CSV_text=preg_replace('/^\s+/', '', $CSV_text);
	$CSV_text=preg_replace('/\s+,/', ',', $CSV_text);
	$CSV_text=preg_replace('/,\s+/', ',', $CSV_text);
	// We'll be outputting a TXT file
	header('Content-type: application/octet-stream');

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
	$short_header=1;

	echo $HTML_header;
	require("admin_header.php");
	echo $HTML_text;
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


