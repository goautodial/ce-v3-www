<?php 
# AST_OUTBOUNDsummary_interval.php
# 
# Copyright (C) 2013  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES
#
# 091128-0311 - First build
# 091129-0017 - Added Sales-type and DNC-type tallies
# 100214-1421 - Sort menu alphabetically
# 100216-0042 - Added popup date selector
# 100712-1324 - Added system setting slave server option
# 100802-2347 - Added User Group Allowed Reports option validation
# 100914-1326 - Added lookup for user_level 7 users to set to reports only which will remove other admin links
# 110703-1825 - Added download option
# 111104-1205 - Added user_group and calltime restrictions
# 120224-0910 - Added HTML display option with bar graphs
# 130414-0119 - Added report logging
#

$startMS = microtime();

require("dbconnect.php");
require("functions.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];

if (isset($_GET["time_interval"]))			{$time_interval=$_GET["time_interval"];}
	elseif (isset($_POST["time_interval"]))	{$time_interval=$_POST["time_interval"];}
if (isset($_GET["print_calls"]))			{$print_calls=$_GET["print_calls"];}
	elseif (isset($_POST["print_calls"]))	{$print_calls=$_POST["print_calls"];}
if (isset($_GET["include_rollover"]))			{$include_rollover=$_GET["include_rollover"];}
	elseif (isset($_POST["include_rollover"]))	{$include_rollover=$_POST["include_rollover"];}
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
if (strlen($include_rollover)<2) {$include_rollover='NO';}

$report_name = 'Outbound Summary Interval Report';
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

$auth=0;
$stmt="SELECT full_name,user_level,user_group from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level >= 7 and view_reports='1' and active='Y';";
if ($DB) {$MAIN.="|$stmt|\n";}
if ($non_latin > 0) {$rslt=mysql_query("SET NAMES 'UTF8'");}
$rslt=mysql_query($stmt, $link);
$records_to_print = mysql_num_rows($rslt);
if ($records_to_print > 0)
	{
	$row=mysql_fetch_row($rslt);
	$full_name =	$row[0];
	$user_level =	$row[1];
	$user_group =	$row[2];
	$auth++;
	}

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

$LOGallowed_campaignsSQL='';
$whereLOGallowed_campaignsSQL='';
$stmt="SELECT allowed_campaigns,allowed_reports,admin_viewable_call_times from vicidial_user_groups where user_group='$user_group';";
$rslt=mysql_query($stmt, $link);
$records_to_print = mysql_num_rows($rslt);
if ($records_to_print > 0)
	{
	$row=mysql_fetch_row($rslt);
	$LOGallowed_reports =	$row[1];
	$LOGadmin_viewable_call_times =	$row[2];
	if ( (!eregi("ALL-CAMPAIGNS",$row[0])) )
		{
		$rawLOGallowed_campaignsSQL = eregi_replace(' -','',$row[0]);
		$rawLOGallowed_campaignsSQL = eregi_replace(' ',"','",$rawLOGallowed_campaignsSQL);
		$LOGallowed_campaignsSQL = "and campaign_id IN('$rawLOGallowed_campaignsSQL')";
		$whereLOGallowed_campaignsSQL = "where campaign_id IN('$rawLOGallowed_campaignsSQL')";
		}
	if ( (!preg_match("/$report_name/",$LOGallowed_reports)) and (!preg_match("/ALL REPORTS/",$LOGallowed_reports)) )
		{
		echo "You are not allowed to view this report: |$PHP_AUTH_USER|$report_name|\n";
		exit;
		}
	}
else
	{
	echo "Campaigns Permissions Error: |$PHP_AUTH_USER|$user_group|\n";
	exit;
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

$i=0;
$group_string='|';
$group_ct = count($group);
while($i < $group_ct)
	{
	$group_string .= "$group[$i]|";
	$i++;
	}

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
	if (eregi('--ALL--',$group_string))
		{$group[$i] =	$row[0];}
	$i++;
	}

if ($DB) {$MAIN.="$group_string|$i\n";}

$rollover_groups_count=0;
$i=0;
$group_string='|';
$group_ct = count($group);
while($i < $group_ct)
	{
	$stmt="select campaign_name from vicidial_campaigns where campaign_id='$group[$i]' $LOGallowed_campaignsSQL;";
	$rslt=mysql_query($stmt, $link);
	$campaign_names_to_print = mysql_num_rows($rslt);
	if ($campaign_names_to_print > 0)
		{
		$row=mysql_fetch_row($rslt);
		$group_cname[$i] =	$row[0];
		$group_string .= "$group[$i]|";
		$group_SQL .= "'$group[$i]',";
		$groupQS .= "&group[]=$group[$i]";
		}

	if (eregi("YES",$include_rollover))
		{
		$stmt="select drop_inbound_group from vicidial_campaigns where campaign_id='$group[$i]' $LOGallowed_campaignsSQL and drop_inbound_group NOT LIKE \"%NONE%\" and drop_inbound_group is NOT NULL and drop_inbound_group != '';";
		$rslt=mysql_query($stmt, $link);
		if ($DB) {$MAIN.="$stmt\n";}
		$in_groups_to_print = mysql_num_rows($rslt);
		if ($in_groups_to_print > 0)
			{
			$row=mysql_fetch_row($rslt);
			$group_drop_SQL .= "'$row[0]',";

			$rollover_groups_count++;
			}
		}

	$i++;
	}
if ( (ereg("--ALL--",$group_string) ) or ($group_ct < 1) )
	{
	$group_SQL = "";
	$group_drop_SQL = "";
	}
else
	{
	$group_SQL = eregi_replace(",$",'',$group_SQL);
	$group_drop_SQL = eregi_replace(",$",'',$group_drop_SQL);
	$both_group_SQLand = "and ( (campaign_id IN($group_drop_SQL)) or (campaign_id IN($group_SQL)) )";
	$both_group_SQL = "where ( (campaign_id IN($group_drop_SQL)) or (campaign_id IN($group_SQL)) )";
	$group_SQLand = "and campaign_id IN($group_SQL)";
	$group_SQL = "where campaign_id IN($group_SQL)";
	$group_drop_SQLand = "and campaign_id IN($group_drop_SQL)";
	$group_drop_SQL = "where campaign_id IN($group_drop_SQL)";
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

$customer_interactive_statuses='';
$stmt="select status from vicidial_statuses where human_answered='Y';";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$statha_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $statha_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$customer_interactive_statuses .= "'$row[0]',";
	$i++;
	}
$stmt="select status from vicidial_campaign_statuses where human_answered='Y';";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$statha_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $statha_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$customer_interactive_statuses .= "'$row[0]',";
	$i++;
	}
if (strlen($customer_interactive_statuses)>2)
	{$customer_interactive_statuses = substr("$customer_interactive_statuses", 0, -1);}
else
	{$customer_interactive_statuses="''";}

$stmt="select status from vicidial_statuses where sale='Y';";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$statsale_to_print = mysql_num_rows($rslt);
$i=0;
$sale_ct=0;
while ($i < $statsale_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$sale_statusesLIST[$sale_ct] = $row[0];
	$sale_ct++;
	$i++;
	}
$stmt="select status from vicidial_campaign_statuses where sale='Y';";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$statsale_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $statsale_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$sale_statusesLIST[$sale_ct] = $row[0];
	$sale_ct++;
	$i++;
	}

$stmt="select status from vicidial_statuses where dnc='Y';";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$statdnc_to_print = mysql_num_rows($rslt);
$i=0;
$dnc_ct=0;
while ($i < $statdnc_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$dnc_statusesLIST[$dnc_ct] = $row[0];
	$dnc_ct++;
	$i++;
	}
$stmt="select status from vicidial_campaign_statuses where dnc='Y';";
$rslt=mysql_query($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
$statdnc_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $statdnc_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$dnc_statusesLIST[$dnc_ct] = $row[0];
	$dnc_ct++;
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

$HEADER.="<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";
$HEADER.="<TITLE>$report_name</TITLE></HEAD><BODY BGCOLOR=WHITE marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>\n";

if ($bareformat < 1)
	{
	$short_header=1;


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

	$MAIN.="</TD><TD VALIGN=TOP ROWSPAN=2> Campaigns:<BR>";
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
	$MAIN.="</TD><TD VALIGN=TOP ROWSPAN=2>";
	$MAIN.="Include Drop &nbsp; <BR>Rollover:<BR>";
	$MAIN.="<SELECT SIZE=1 NAME=include_rollover>\n";
	$MAIN.="<option selected value=\"$include_rollover\">$include_rollover</option>\n";
	$MAIN.="<option value=\"YES\">YES</option>\n";
	$MAIN.="<option value=\"NO\">NO</option>\n";
	$MAIN.="</SELECT><BR>\n";
	$MAIN.="Time Interval:<BR>";
	$MAIN.="<SELECT SIZE=1 NAME=time_interval>\n";
	if ($time_interval <= 900)
		{
		$interval_count = 96;
		$hf=45;
		$MAIN.="<option selected value=\"900\">15 Minutes</option>\n";
		}
	else
		{$MAIN.="<option value=\"900\">15 Minutes</option>\n";}
	if ( ($time_interval > 900) and ($time_interval <= 1800) )
		{
		$interval_count = 48;
		$hf=30;
		$MAIN.="<option selected value=\"1800\">30 Minutes</option>\n";
		}
	else
		{$MAIN.="<option value=\"1800\">30 Minutes</option>\n";}
	if ($time_interval > 1800)
		{
		$interval_count = 24;
		$MAIN.="<option selected value=\"3600\">1 Hour</option>\n";
		}
	else
		{$MAIN.="<option value=\"3600\">1 Hour</option>\n";}


	$MAIN.="</SELECT>\n";
	$MAIN.="</TD><TD VALIGN=TOP ALIGN=LEFT ROWSPAN=2>\n";
	$MAIN.="<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2> &nbsp; &nbsp; &nbsp; &nbsp; ";
	$MAIN.="<a href=\"$PHP_SELF?DB=$DB&costformat=$costformat&print_calls=$print_calls&query_date=$query_date&end_date=$end_date$groupQS&include_rollover=$include_rollover&time_interval=$time_interval&SUBMIT=$SUBMIT&shift=$shift&file_download=1\">DOWNLOAD</a> | ";
	$MAIN.="<a href=\"./admin.php?ADD=3111&group_id=$group[0]\">MODIFY</a> | ";
	$MAIN.="<a href=\"./admin.php?ADD=999999\">REPORTS</a>";
	$MAIN.="</FONT><BR><BR>\n";
	$MAIN.="Display as:<BR>";
	$MAIN.="<select name='report_display_type'>";
	if ($report_display_type) {$MAIN.="<option value='$report_display_type' selected>$report_display_type</option>";}
	$MAIN.="<option value='TEXT'>TEXT</option><option value='HTML'>HTML</option></select>\n<BR>";
	$MAIN.="<BR> &nbsp; &nbsp; &nbsp; &nbsp; ";
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

if ($group_ct < 1)
	{
	$MAIN.="\n\n";
	$MAIN.="PLEASE SELECT A CAMPAIGN AND DATE RANGE ABOVE AND CLICK SUBMIT\n";
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
	$hh=0;
	while ($h < $interval_count)
		{
		if ($interval_count>=96)
			{
			if ($hf < 45)
				{
				$hf = ($hf + 15);
				}
			else
				{
				$hf = "00";
				if ($h > 0)
					{$hh++;}
				}
			$H_test = "$hh$hf";
			}
		if ($interval_count==48)
			{
			if ($hf < 30)
				{
				$hf = ($hf + 30);
				}
			else
				{
				$hf = "00";
				if ($h > 0)
					{$hh++;}
				}
			$H_test = "$hh$hf";
			}
		if ($interval_count<=24)
			{
			$H_test = $h . "00";
			}
		if ( ($H_test >= $Gct_default_start) and ($H_test <= $Gct_default_stop) )
			{
			$Hcalltime[$h]++;
			$Hcalltime_HHMM[$h] = "$H_test";
			}
		if ($DB)
			{$MAIN.="( ($H_test >= $Gct_default_start) and ($H_test <= $Gct_default_stop) ) $hh $hf\n";}
		$h++;
		}

	$query_date_BEGIN = "$query_date 00:00:00";   
	$query_date_END = "$end_date 23:59:59";


	$MAIN .= "Outbound Summary Interval Report: $group_string          $NOW_TIME\n";

	$CSV_main.="\"Outbound Summary Interval Report:\"\n\"$group_string\"\n\"$NOW_TIME\"\n\n";


	##### Loop through each campaign and gether stats
	if ($group_ct > 0)
		{
		$ASCII_text .= "\n";
		$ASCII_text .= "---------- MULTI-CAMPAIGN BREAKDOWN:\n";
		$ASCII_text .= "+------------------------------------------+--------+--------+--------+--------+--------+--------+--------+------------+------------+\n";
		$ASCII_text .= "|                                          |        | SYSTEM | AGENT  |        |        | NO     |        | AGENT      | AGENT      |\n";
		$ASCII_text .= "|                                          | TOTAL  | RELEASE| RELEASE| SALE   | DNC    | ANSWER | DROP   | LOGIN      | PAUSE      |\n";
		$ASCII_text .= "| CAMPAIGN                                 | CALLS  | CALLS  | CALLS  | CALLS  | CALLS  | PERCENT| PERCENT| TIME(H:M:S)| TIME(H:M:S)|\n";
		$ASCII_text .= "+------------------------------------------+--------+--------+--------+--------+--------+--------+--------+------------+------------+\n";

		$CSV_main.="\"MULTI-CAMPAIGN BREAKDOWN:\"\n";
		$CSV_main.="\"CAMPAIGN\",\"TOTAL CALLS\",\"SYSTEM RELEASE CALLS\",\"AGENT RELEASE CALLS\",\"SALE CALLS\",\"DNC CALLS\",\"NO ANSWER PERCENT\",\"DROP PERCENT\",\"AGENT LOGIN TIME(H:M:S)\",\"AGENT PAUSE TIME(H:M:S)\"\n";
		$CSV_subreports="";
		
		######## GRAPHING #########
		$max_calls=1;
		$max_system_release=1;
		$max_agent_release=1;
		$max_sales=1;
		$max_dncs=1;
		$max_nas=1;
		$max_drops=1;
		$max_login_time=1;
		$max_pause_time=1;

		$GRAPH="<BR><BR><a name='campbreakdown'/><table border='0' cellpadding='0' cellspacing='2' width='800'>";
		$GRAPH.="<tr><th width='11%' class='grey_graph_cell' id='campbreakdown1'><a href='#' onClick=\"DrawMultiCampaignGraph('CALLS', '1'); return false;\">CALLS</a></th><th width='11%' class='grey_graph_cell' id='campbreakdown2'><a href='#' onClick=\"DrawMultiCampaignGraph('SYSTEMRELEASE', '2'); return false;\">SYSTEM RELEASE CALLS</a></th><th width='11%' class='grey_graph_cell' id='campbreakdown3'><a href='#' onClick=\"DrawMultiCampaignGraph('AGENTRELEASE', '3'); return false;\">AGENT RELEASE CALLS</a></th><th width='11%' class='grey_graph_cell' id='campbreakdown4'><a href='#' onClick=\"DrawMultiCampaignGraph('SALES', '4'); return false;\">SALE CALLS</a></th><th width='11%' class='grey_graph_cell' id='campbreakdown5'><a href='#' onClick=\"DrawMultiCampaignGraph('DNCS', '5'); return false;\">DNC CALLS</a></th><th width='11%' class='grey_graph_cell' id='campbreakdown6'><a href='#' onClick=\"DrawMultiCampaignGraph('NAS', '6'); return false;\">NO ANSWER PERCENT</a></th><th width='11%' class='grey_graph_cell' id='campbreakdown7'><a href='#' onClick=\"DrawMultiCampaignGraph('DROPS', '7'); return false;\">DROP PERCENT</a></th><th width='11%' class='grey_graph_cell' id='campbreakdown8'><a href='#' onClick=\"DrawMultiCampaignGraph('LOGINTIME', '8'); return false;\">AGENT LOGIN TIME</a></th><th width='12%' class='grey_graph_cell' id='campbreakdown9'><a href='#' onClick=\"DrawMultiCampaignGraph('PAUSETIME', '9'); return false;\">AGENT PAUSE TIME</a></th></tr>";
		$GRAPH.="<tr><td colspan='9' class='graph_span_cell'><span id='multi_campaign_breakdown'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";
		$graph_header="<table cellspacing='0' cellpadding='0' summary='STATUS' class='horizontalgraph'><caption align='top'>MULTI-CAMPAIGN BREAKDOWN</caption><tr><th class='thgraph' scope='col'>CAMPAIGNS</th>";
		$CALLS_graph=$graph_header."<th class='thgraph' scope='col'>TOTAL CALLS</th></tr>";
		$SYSTEMRELEASE_graph=$graph_header."<th class='thgraph' scope='col'>SYSTEM RELEASE CALLS</th></tr>";
		$AGENTRELEASE_graph=$graph_header."<th class='thgraph' scope='col'>AGENT RELEASE CALLS</th></tr>";
		$SALES_graph=$graph_header."<th class='thgraph' scope='col'>SALE CALLS</th></tr>";
		$DNCS_graph=$graph_header."<th class='thgraph' scope='col'>DNC CALLS</th></tr>";
		$NAS_graph=$graph_header."<th class='thgraph' scope='col'>NO ANSWER PERCENT</th></tr>";
		$DROPS_graph=$graph_header."<th class='thgraph' scope='col'>DROP PERCENT</th></tr>";
		$LOGINTIME_graph=$graph_header."<th class='thgraph' scope='col'>AGENT LOGIN TIME (H:M:S)</th></tr>";
		$PAUSETIME_graph=$graph_header."<th class='thgraph' scope='col'>AGENT PAUSE TIME (H:M:S)</th></tr>";
		###########################

		$i=0;
		$TOTcalls_count=0;
		$TOTsystem_count=0;
		$TOTagent_count=0;
		$TOTptp_count=0;
		$TOTrtp_count=0;
		$TOTna_count=0;
		$TOTdrop_count=0;
		$TOTagent_login_sec=0;
		$TOTagent_pause_sec=0;
		$SUBoutput='';

		while($i < $group_ct)
			{
			$u=0;

			##### Gather Agent time records
			$stmt="select event_time,UNIX_TIMESTAMP(event_time),campaign_id,pause_sec,wait_sec,talk_sec,dispo_sec from vicidial_agent_log where event_time >= '$query_date_BEGIN' and event_time <= '$query_date_END' and campaign_id IN('$group_drop[$i]','$group[$i]');";
			$rslt=mysql_query($stmt, $link);
			if ($DB) {$ASCII_text.="$stmt\n";}
			$AGENTtime_to_print = mysql_num_rows($rslt);
			$s=0;
			while ($s < $AGENTtime_to_print)
				{
				$row=mysql_fetch_row($rslt);
				$inTOTALsec =		($row[3] + $row[4] + $row[5] + $row[6]);	
				$ATcall_date[$s] =		$row[0];
				$ATepoch[$s] =			$row[1];
				$ATcampaign_id[$s] =	$row[2];
				$ATpause_sec[$s] =		$row[3];
				$ATagent_sec[$s] =		$inTOTALsec;
				$s++;
				}

			##### Gather outbound calls
			$stmt = "SELECT status,length_in_sec,call_date,UNIX_TIMESTAMP(call_date),phone_number,campaign_id,uniqueid,lead_id from vicidial_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='$group[$i]';";
			$rslt=mysql_query($stmt, $link);
			if ($DB) {$ASCII_text.="$stmt\n";}
			$calls_to_parse = mysql_num_rows($rslt);
			$p=0;
			while ($p < $calls_to_parse)
				{
				$row=mysql_fetch_row($rslt);
				$CPstatus[$u] =			$row[0];
				$CPlength_in_sec[$u] =	$row[1];
				$CPcall_date[$u] =		$row[2];
				$CPepoch[$u] =			$row[3];
				$CPphone_number[$u] =	$row[4];
				$CPcampaign_id[$u] =	$row[5];
				$CPvicidial_id[$u] =	$row[6];
				$CPlead_id[$u] =		$row[7];
				$TESTlead_id[$u] =		$row[7];
				$TESTuniqueid[$u] =		$row[6];
				$CPin_out[$u] =			'OUT';
				$p++;
				$u++;
				}

			$group_drop[$i]='';
			if (eregi("YES",$include_rollover))
				{
				##### Gather inbound calls from drop inbound group if selected
				$stmt="select drop_inbound_group from vicidial_campaigns where campaign_id='$group[$i]' $LOGallowed_campaignsSQL and drop_inbound_group NOT LIKE \"%NONE%\" and drop_inbound_group is NOT NULL and drop_inbound_group != '';";
				$rslt=mysql_query($stmt, $link);
				if ($DB) {$ASCII_text.="$stmt\n";}
				$in_groups_to_print = mysql_num_rows($rslt);
				if ($in_groups_to_print > 0)
					{
					$row=mysql_fetch_row($rslt);
					$group_drop[$i] = $row[0];
					$rollover_groups_count++;
					}

				$length_in_secZ=0;
				$queue_secondsZ=0;
				$agent_alert_delayZ=0;
				$stmt="select status,length_in_sec,queue_seconds,agent_alert_delay,call_date,UNIX_TIMESTAMP(call_date),phone_number,campaign_id,closecallid,lead_id,uniqueid from vicidial_closer_log,vicidial_inbound_groups where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and group_id=campaign_id and campaign_id='$group_drop[$i]';";
				$rslt=mysql_query($stmt, $link);
				if ($DB) {$ASCII_text.="$stmt\n";}
				$INallcalls_to_printZ = mysql_num_rows($rslt);
				$y=0;
				while ($y < $INallcalls_to_printZ)
					{
					$row=mysql_fetch_row($rslt);

					$k=0;
					$front_call_found=0;
					while($k < $p)
						{
						if ($TESTuniqueid[$k] == $row[10])
							{$front_call_found++;}
						$k++;
						}
					if ($front_call_found > 0)
						{
						$length_in_secZ = $row[1];
						$queue_secondsZ = $row[2];
						$agent_alert_delayZ = $row[3];

						$TOTALdelay =		round($agent_alert_delayZ / 1000);
						$thiscallsec = (($length_in_secZ - $queue_secondsZ) - $TOTALdelay);
						if ($thiscallsec < 0)
							{$thiscallsec = 0;}
						$inTOTALsec =	($inTOTALsec + $thiscallsec);	

						$CPstatus[$u] =			$row[0];
						$CPlength_in_sec[$u] =	$inTOTALsec;
						$CPcall_date[$u] =		$row[4];
						$CPepoch[$u] =			$row[5];
						$CPphone_number[$u] =	$row[6];
						$CPcampaign_id[$u] =	$row[7];
						$CPvicidial_id[$u] =	$row[8];
						$CPlead_id[$u] =		$row[9];
						$CPin_out[$u] =			'IN';
						$u++;
						}
					$y++;
					}
				}


			$out_of_call_time=0;
			$length_in_sec[$i]=0;
			$queue_seconds[$i]=0;
			$agent_sec[$i]=0;
			$pause_sec[$i]=0;
			$talk_sec[$i]=0;
			$calls_count[$i]=0;
			$calls_count_IN[$i]=0;
			$drop_count[$i]=0;
			$drop_count_OUT[$i]=0;
			$system_count[$i]=0;
			$agent_count[$i]=0;
			$ptp_count[$i]=0;
			$rtp_count[$i]=0;
			$na_count[$i]=0;
			$answer_count[$i]=0;
			$max_queue_seconds[$i]=0;
			$Hlength_in_sec=$MT;
			$Hqueue_seconds=$MT;
			$Hagent_sec=$MT;
			$Hpause_sec=$MT;
			$Htalk_sec=$MT;
			$Hcalls_count=$MT;
			$Hcalls_count_IN=$MT;
			$Hdrop_count=$MT;
			$Hdrop_count_OUT=$MT;
			$Hsystem_count=$MT;
			$Hagent_count=$MT;
			$Hptp_count=$MT;
			$Hrtp_count=$MT;
			$Hna_count=$MT;
			$Hanswer_count=$MT;
			$Hmax_queue_seconds=$MT;
			$hTOTALcalls =	0;
			$hANSWERcalls =	0;
			$hSUMagent =	0;
			$hSUMpause =	0;
			$hSUMtalk =		0;
			$hAVGtalk =		0;
			$hSUMqueue =	0;
			$hAVGqueue =	0;
			$hMAXqueue =	0;
			$hDROPcalls =	0;
			$hPRINT =		0;
			$hTOTcalls_count =			0;
			$hTOTsystem_count =			0;
			$hTOTagent_count =			0;
			$hTOTptp_count =			0;
			$hTOTrtp_count =			0;
			$hTOTna_count =				0;
			$hTOTanswer_count =			0;
			$hTOTagent_sec =			0;
			$hTOTpause_sec =			0;
			$hTOTtalk_sec =				0;
			$hTOTtalk_avg =				0;
			$hTOTqueue_seconds =		0;
			$hTOTqueue_avg =			0;
			$hTOTmax_queue_seconds =	0;
			$hTOTdrop_count =			0;

			##### Parse through the agent time records to tally the time
			$p=0;
			while ($p < $s)
				{
				$call_date = explode(" ", $ATcall_date[$p]);
				$call_time = ereg_replace("[^0-9]","",$call_date[1]);
				$epoch = $ATepoch[$p];
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
				$Cmin = date("i", $epoch);
				if ($interval_count==96)
					{
					$ChourX = ($Chour * 4);
					if ($Cmin < 15) {$Cmin = "00"; $CminX = 0;}
					if ( ($Cmin >= 15) and ($Cmin < 30) ) {$Cmin = "15"; $CminX = 1;}
					if ( ($Cmin >= 30) and ($Cmin < 45) ) {$Cmin = "30"; $CminX = 2;}
					if ($Cmin >= 45) {$Cmin = "45"; $CminX = 3;}
					$Chour = ($ChourX + $CminX);
					}
				if ($interval_count==48)
					{
					$ChourX = ($Chour * 2);
					if ($Cmin < 30) {$Cmin = "00"; $CminX = 0;}
					if ($Cmin >= 30) {$Cmin = "30"; $CminX = 1;}
					$Chour = ($ChourX + $CminX);
					}

				if ( ($call_time > $CTstart) and ($call_time < $CTstop) )
					{
					$agent_sec[$i] = ($agent_sec[$i] + $ATagent_sec[$p]);
					$Hagent_sec[$Chour] = ($Hagent_sec[$Chour] + $ATagent_sec[$p]);
					$pause_sec[$i] = ($pause_sec[$i] + $ATpause_sec[$p]);
					$Hpause_sec[$Chour] = ($Hpause_sec[$Chour] + $ATpause_sec[$p]);

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
					{$ASCII_text.="$Hcalltime[$Chour] | AGENT: $agent_sec[$i] PAUSE: $pause_sec[$i]\n";}
				$p++;
				}






			##### Parse through call records to tally the counts
			$p=0;
			while ($p < $u)
				{
				$call_date = explode(" ", $CPcall_date[$p]);
				$call_time = ereg_replace("[^0-9]","",$call_date[1]);
				$epoch = $CPepoch[$p];
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
				$Cmin = date("i", $epoch);
				if ($interval_count==96)
					{
					$ChourX = ($Chour * 4);
					if ($Cmin < 15) {$Cmin = "00"; $CminX = 0;}
					if ( ($Cmin >= 15) and ($Cmin < 30) ) {$Cmin = "15"; $CminX = 1;}
					if ( ($Cmin >= 30) and ($Cmin < 45) ) {$Cmin = "30"; $CminX = 2;}
					if ($Cmin >= 45) {$Cmin = "45"; $CminX = 3;}
					$Chour = ($ChourX + $CminX);
					}
				if ($interval_count==48)
					{
					$ChourX = ($Chour * 2);
					if ($Cmin < 30) {$Cmin = "00"; $CminX = 0;}
					if ($Cmin >= 30) {$Cmin = "30"; $CminX = 1;}
					$Chour = ($ChourX + $CminX);
					}

				if ( ($call_time > $CTstart) and ($call_time < $CTstop) )
					{
					$calls_count[$i]++;
					$length_in_sec[$i] =	($length_in_sec[$i] + $CPlength_in_sec[$p]);
					$Hlength_in_sec[$Chour] =	($Hlength_in_sec[$Chour] + $row[1]);
					$Hqueue_seconds[$Chour] =	($Hqueue_seconds[$Chour] + $row[2]);
					$TEMPtalk = $CPlength_in_sec[$p];
					if ($TEMPtalk < 0) {$TEMPtalk = 0;}
					$talk_sec[$i] =	($talk_sec[$i] + $TEMPtalk);
					$Htalk_sec[$Chour] =	($Htalk_sec[$Chour] + $TEMPtalk);

					$Hcalls_count[$Chour]++;
					if (eregi("DROP",$CPstatus[$p]))
						{
						if ($CPin_out[$p] == 'OUT')
							{
							$drop_count_OUT[$i]++;
							$Hdrop_count_OUT[$Chour]++;
							}
						$drop_count[$i]++;
						$Hdrop_count[$Chour]++;
						}
					else
						{
						$answer_count[$i]++;
						$Hanswer_count[$Chour]++;
						}
					if (eregi("\|$CPstatus[$p]\|",'|NA|NEW|QUEUE|INCALL|DROP|XDROP|AA|AM|AL|AFAX|AB|ADC|DNCL|DNCC|PU|PM|SVYEXT|SVYHU|SVYVM|SVYREC|QVMAIL|'))
						{
						$system_count[$i]++;
						$Hsystem_count[$Chour]++;
						}
					else
						{
						$agent_count[$i]++;
						$Hagent_count[$Chour]++;
						}
					if ($CPstatus[$p] == 'NA')
						{
						$na_count[$i]++;
						$Hna_count[$Chour]++;
						}
					if ($CPin_out[$p] == 'IN')
						{
						$calls_count_IN[$i]++;
						$Hcalls_count_IN[$Chour]++;
						}

					$k=0;
					while($k < $sale_ct)
						{
						if ($sale_statusesLIST[$k] == $CPstatus[$p])
							{
							$ptp_count[$i]++;
							$Hptp_count[$Chour]++;
							}
						$k++;
						}

					$k=0;
					while($k < $dnc_ct)
						{
						if ($dnc_statusesLIST[$k] == $CPstatus[$p])
							{
							$rtp_count[$i]++;
							$Hrtp_count[$Chour]++;
							}
						$k++;
						}

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

			if ( ($calls_count_IN[$i] > 0) and ($drop_count_OUT[$i] > 0) )
				{
				$drop_count[$i] = ($drop_count[$i] - $calls_count_IN[$i]);
				$calls_count[$i] = ($calls_count[$i] - $calls_count_IN[$i]);
				$system_count[$i] = ($system_count[$i] - $calls_count_IN[$i]);
				if ($drop_count[$i] < 0)
					{$drop_count[$i] = 0;}
				}
			$TOTcalls_count =			($TOTcalls_count + $calls_count[$i]);
			$TOTsystem_count =			($TOTsystem_count + $system_count[$i]);
			$TOTagent_count =			($TOTagent_count + $agent_count[$i]);
			$TOTptp_count =				($TOTptp_count + $ptp_count[$i]);
			$TOTrtp_count =				($TOTrtp_count + $rtp_count[$i]);
			$TOTna_count =				($TOTna_count + $na_count[$i]);
			$TOTanswer_count =			($TOTanswer_count + $answer_count[$i]);
			$TOTagent_sec =				($TOTagent_sec + $agent_sec[$i]);
			$TOTpause_sec =				($TOTpause_sec + $pause_sec[$i]);
			$TOTtalk_sec =				($TOTtalk_sec + $talk_sec[$i]);
			$TOTqueue_seconds =			($TOTqueue_seconds + $queue_seconds[$i]);
			$TOTdrop_count =			($TOTdrop_count + $drop_count[$i]);
			if ($max_queue_seconds[$i] > $TOTmax_queue_seconds)
				{$TOTmax_queue_seconds = $max_queue_seconds[$i];}

			if ($calls_count[$i]>$max_calls) {$max_calls=$calls_count[$i];}
			if ($system_count[$i]>$max_system_release) {$max_system_release=$system_count[$i];}
			if ($agent_count[$i]>$max_agent_release) {$max_agent_release=$agent_count[$i];}
			if ($ptp_count[$i]>$max_sales) {$max_sales=$ptp_count[$i];}
			if ($rtp_count[$i]>$max_dncs) {$max_dncs=$rtp_count[$i];}
			if ($agent_sec[$i]>$max_login_time) {$max_login_time=$agent_sec[$i];}
			if ($pause_sec[$i]>$max_pause_time) {$max_pause_time=$pause_sec[$i];}
			$graph_stats[$i][0]="$group[$i] - $group_cname[$i]";
			$graph_stats[$i][1]=$calls_count[$i];
			$graph_stats[$i][2]=$system_count[$i];
			$graph_stats[$i][3]=$agent_count[$i];
			$graph_stats[$i][4]=$ptp_count[$i];
			$graph_stats[$i][5]=$rtp_count[$i];
			$graph_stats[$i][8]=$agent_sec[$i];
			$graph_stats[$i][9]=$pause_sec[$i];

			$agent_sec[$i] =			sec_convert($agent_sec[$i],'H'); 
			$pause_sec[$i] =			sec_convert($pause_sec[$i],'H'); 
			$talk_sec[$i] =				sec_convert($talk_sec[$i],'H'); 
			$talk_avg[$i] =				sec_convert($talk_avg[$i],'H'); 
			$queue_seconds[$i] =		sec_convert($queue_seconds[$i],'H'); 
			$queue_avg[$i] =			sec_convert($queue_avg[$i],'H'); 
			$max_queue_seconds[$i] =	sec_convert($max_queue_seconds[$i],'H'); 


			$groupDISPLAY =	sprintf("%-40s", "$group[$i] - $group_cname[$i]");
			$gTOTALcalls =	sprintf("%6s", $calls_count[$i]);
			$gSYSTEMcalls =	sprintf("%6s", $system_count[$i]);
			$gAGENTcalls =	sprintf("%6s", $agent_count[$i]);
			$gPTPcalls =	sprintf("%6s", $ptp_count[$i]);
			$gRTPcalls =	sprintf("%6s", $rtp_count[$i]);
			if ( ($calls_count[$i] < 1) or ($na_count[$i] < 1) )
				{$gNApercent=0;}
			else
				{$gNApercent = ( ($na_count[$i] / $calls_count[$i]) * 100);}
			$gNApercent =	sprintf("%6.2f",$gNApercent);
			$gNAcalls =		sprintf("%6s", $na_count[$i]);
			$gANSWERcalls =	sprintf("%6s", $answer_count[$i]);
			$gSUMagent =	sprintf("%10s", $agent_sec[$i]);
			$gSUMpause =	sprintf("%10s", $pause_sec[$i]);
			$gSUMtalk =		sprintf("%9s", $talk_sec[$i]);
			$gAVGtalk =		sprintf("%7s", $talk_avg[$i]);
			$gSUMqueue =	sprintf("%9s", $queue_seconds[$i]);
			$gAVGqueue =	sprintf("%7s", $queue_avg[$i]);
			$gMAXqueue =	sprintf("%7s", $max_queue_seconds[$i]);
			if ( ($calls_count[$i] < 1) or ($drop_count[$i] < 1) )
				{$gDROPpercent=0;}
			else
				{$gDROPpercent = ( ($drop_count[$i] / $calls_count[$i]) * 100);}
			$gDROPpercent =		sprintf("%6.2f",$gDROPpercent);
			$gDROPcalls =	sprintf("%6s", $drop_count[$i]);

			if (trim($gNApercent)>$max_nas) {$max_nas=trim($gNApercent);}
			if (trim($gDROPpercent)>$max_drops) {$max_drops=trim($gDROPpercent);}
			$graph_stats[$i][6]=trim($gNApercent);
			$graph_stats[$i][7]=trim($gDROPpercent);


			while(strlen($groupDISPLAY)>40) {$groupDISPLAY = substr("$groupDISPLAY", 0, -1);}

			$ASCII_text .= "| $groupDISPLAY | $gTOTALcalls | $gSYSTEMcalls | $gAGENTcalls | $gPTPcalls | $gRTPcalls | $gNApercent%| $gDROPpercent%| $gSUMagent | $gSUMpause |";
			$CSV_main.="\"$groupDISPLAY\",\"$gTOTALcalls\",\"$gSYSTEMcalls\",\"$gAGENTcalls\",\"$gPTPcalls\",\"$gRTPcalls\",\"$gNApercent%\",\"$gDROPpercent%\",\"$gSUMagent\",\"$gSUMpause\"\n";
			if ($DB) {$ASCII_text .= " $gDROPcalls($calls_count_IN[$i]/$drop_count_OUT[$i]) |";}
			$ASCII_text .= "<!-- OUT OF CALLTIME: $out_of_call_time -->\n";

			### hour by hour sumaries
			$SUB_ASCII_text .= "\n---------- $group[$i] - $group_cname[$i]\nINTERVAL BREAKDOWN:\n";
			$SUB_ASCII_text .= "+---------------------+--------+--------+--------+--------+--------+--------+--------+------------+------------+\n";
			$SUB_ASCII_text .= "|                     |        | SYSTEM | AGENT  |        |        | NO     |        | AGENT      | AGENT      |\n";
			$SUB_ASCII_text .= "|                     | TOTAL  | RELEASE| RELEASE| SALE   | DNC    | ANSWER | DROP   | LOGIN      | PAUSE      |\n";
			$SUB_ASCII_text .= "| INTERVAL            | CALLS  | CALLS  | CALLS  | CALLS  | CALLS  | PERCENT| PERCENT| TIME(H:M:S)| TIME(H:M:S)|\n";
			$SUB_ASCII_text .= "+---------------------+--------+--------+--------+--------+--------+--------+--------+------------+------------+\n";

			$CSV_subreports.="\n\n\"$group[$i] - $group_cname[$i]\"\n\"INTERVAL BREAKDOWN:\"\n";
			$CSV_subreports.="\"INTERVAL\",\"TOTAL CALLS\",\"SYSTEM RELEASE CALLS\",\"AGENT RELEASE CALLS\",\"SALE CALLS\",\"DNC CALLS\",\"NO ANSWER PERCENT\",\"DROP PERCENT\",\"AGENT LOGIN TIME (H:M:S)\",\"AGENT PAUSE TIME(H:M:S)\"\n";

			######## GRAPHING #########
			$SUBmax_calls=1;
			$SUBmax_system_release=1;
			$SUBmax_agent_release=1;
			$SUBmax_sales=1;
			$SUBmax_dncs=1;
			$SUBmax_nas=1;
			$SUBmax_drops=1;
			$SUBmax_login_time=1;
			$SUBmax_pause_time=1;
			$SUBgraph_stats="";
			$SUBrpt_campaign=$group[$i];

			$SUBGRAPH="<BR><BR><a name='campbreakdown".$SUBrpt_campaign."'/><table border='0' cellpadding='0' cellspacing='2' width='800'>";
			$SUBGRAPH.="<tr><th width='11%' class='grey_graph_cell' id='campbreakdown".$SUBrpt_campaign."1'><a href='#' onClick=\"Draw".$SUBrpt_campaign."CampaignGraph('CALLS', '1'); return false;\">CALLS</a></th><th width='11%' class='grey_graph_cell' id='campbreakdown".$SUBrpt_campaign."2'><a href='#' onClick=\"Draw".$SUBrpt_campaign."CampaignGraph('SYSTEMRELEASE', '2'); return false;\">SYSTEM RELEASE CALLS</a></th><th width='11%' class='grey_graph_cell' id='campbreakdown".$SUBrpt_campaign."3'><a href='#' onClick=\"Draw".$SUBrpt_campaign."CampaignGraph('AGENTRELEASE', '3'); return false;\">AGENT RELEASE CALLS</a></th><th width='11%' class='grey_graph_cell' id='campbreakdown".$SUBrpt_campaign."4'><a href='#' onClick=\"Draw".$SUBrpt_campaign."CampaignGraph('SALES', '4'); return false;\">SALE CALLS</a></th><th width='11%' class='grey_graph_cell' id='campbreakdown".$SUBrpt_campaign."5'><a href='#' onClick=\"Draw".$SUBrpt_campaign."CampaignGraph('DNCS', '5'); return false;\">DNC CALLS</a></th><th width='11%' class='grey_graph_cell' id='campbreakdown".$SUBrpt_campaign."6'><a href='#' onClick=\"Draw".$SUBrpt_campaign."CampaignGraph('NAS', '6'); return false;\">NO ANSWER PERCENT</a></th><th width='11%' class='grey_graph_cell' id='campbreakdown".$SUBrpt_campaign."7'><a href='#' onClick=\"Draw".$SUBrpt_campaign."CampaignGraph('DROPS', '7'); return false;\">DROP PERCENT</a></th><th width='11%' class='grey_graph_cell' id='campbreakdown".$SUBrpt_campaign."8'><a href='#' onClick=\"Draw".$SUBrpt_campaign."CampaignGraph('LOGINTIME', '8'); return false;\">AGENT LOGIN TIME</a></th><th width='12%' class='grey_graph_cell' id='campbreakdown".$SUBrpt_campaign."9'><a href='#' onClick=\"Draw".$SUBrpt_campaign."CampaignGraph('PAUSETIME', '9'); return false;\">AGENT PAUSE TIME</a></th></tr>";
			$SUBGRAPH.="<tr><td colspan='9' class='graph_span_cell'><span id='campaign_breakdown_".$SUBrpt_campaign."'><BR>&nbsp;<BR></span></td></tr></table><BR><BR>";
			$SUBgraph_header="<table cellspacing='0' cellpadding='0' summary='STATUS' class='horizontalgraph'><caption align='top'>$SUBrpt_campaign - $group_cname[$i] INTERVAL BREAKDOWN:</caption><tr><th class='thgraph' scope='col'>INTERVAL</th>";
			$SUBCALLS_graph=$SUBgraph_header."<th class='thgraph' scope='col'>TOTAL CALLS</th></tr>";
			$SUBSYSTEMRELEASE_graph=$SUBgraph_header."<th class='thgraph' scope='col'>SYSTEM RELEASE CALLS</th></tr>";
			$SUBAGENTRELEASE_graph=$SUBgraph_header."<th class='thgraph' scope='col'>AGENT RELEASE CALLS</th></tr>";
			$SUBSALES_graph=$SUBgraph_header."<th class='thgraph' scope='col'>SALE CALLS</th></tr>";
			$SUBDNCS_graph=$SUBgraph_header."<th class='thgraph' scope='col'>DNC CALLS</th></tr>";
			$SUBNAS_graph=$SUBgraph_header."<th class='thgraph' scope='col'>NO ANSWER PERCENT</th></tr>";
			$SUBDROPS_graph=$SUBgraph_header."<th class='thgraph' scope='col'>DROP PERCENT</th></tr>";
			$SUBLOGINTIME_graph=$SUBgraph_header."<th class='thgraph' scope='col'>AGENT LOGIN TIME (H:M:S)</th></tr>";
			$SUBPAUSETIME_graph=$SUBgraph_header."<th class='thgraph' scope='col'>AGENT PAUSE TIME (H:M:S)</th></tr>";
			###########################

			$h=0; $z=0;
			while ($h < $interval_count)
				{
				if ($Hcalltime[$h] > 0)
					{
					if (strlen($Hcalls_count[$h]) < 1)			{$Hcalls_count[$h] = 0;}
					if (strlen($Hsystem_count[$h]) < 1)			{$Hsystem_count[$h] = 0;}
					if (strlen($Hagent_count[$h]) < 1)			{$Hagent_count[$h] = 0;}
					if (strlen($Hptp_count[$h]) < 1)			{$Hptp_count[$h] = 0;}
					if (strlen($Hrtp_count[$h]) < 1)			{$Hrtp_count[$h] = 0;}
					if (strlen($Hna_count[$h]) < 1)				{$Hna_count[$h] = 0;}
					if (strlen($Hanswer_count[$h]) < 1)			{$Hanswer_count[$h] = 0;}
					if (strlen($Hagent_sec[$h]) < 1)			{$Hagent_sec[$h] = 0;}
					if (strlen($Hpause_sec[$h]) < 1)			{$Hpause_sec[$h] = 0;}
					if (strlen($Htalk_sec[$h]) < 1)				{$Htalk_sec[$h] = 0;}
					if (strlen($Hqueue_seconds[$h]) < 1)		{$Hqueue_seconds[$h] = 0;}
					if (strlen($Hmax_queue_seconds[$h]) < 1)	{$Hmax_queue_seconds[$h] = 0;}
					if (strlen($Hdrop_count[$h]) < 1)			{$Hdrop_count[$h] = 0;}

					if ( ($Hcalls_count_IN[$h] > 0) and ($Hdrop_count_OUT[$h] > 0) )
						{
						$Hdrop_count[$h] = ($Hdrop_count[$h] - $Hcalls_count_IN[$h]);
						$Hcalls_count[$h] = ($Hcalls_count[$h] - $Hcalls_count_IN[$h]);
						$Hsystem_count[$h] = ($Hsystem_count[$h] - $Hcalls_count_IN[$h]);
						if ($Hdrop_count[$h] < 0)
							{$Hdrop_count[$h] = 0;}
						}
					$hTOTcalls_count =			($hTOTcalls_count + $Hcalls_count[$h]);
					$hTOTsystem_count =			($hTOTsystem_count + $Hsystem_count[$h]);
					$hTOTagent_count =			($hTOTagent_count + $Hagent_count[$h]);
					$hTOTptp_count =			($hTOTptp_count + $Hptp_count[$h]);
					$hTOTrtp_count =			($hTOTrtp_count + $Hrtp_count[$h]);
					$hTOTna_count =				($hTOTna_count + $Hna_count[$h]);
					$hTOTanswer_count =			($hTOTanswer_count + $Hanswer_count[$h]);
					$hTOTagent_sec =			($hTOTagent_sec + $Hagent_sec[$h]);
					$hTOTpause_sec =			($hTOTpause_sec + $Hpause_sec[$h]);
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

					if ($Hcalls_count[$h]>$SUBmax_calls) {$SUBmax_calls=$Hcalls_count[$h];}
					if ($Hsystem_count[$h]>$SUBmax_system_release) {$SUBmax_system_release=$Hsystem_count[$h];}
					if ($Hagent_count[$h]>$SUBmax_agent_release) {$SUBmax_agent_release=$Hagent_count[$h];}
					if ($Hptp_count[$h]>$SUBmax_sales) {$SUBmax_sales=$Hptp_count[$h];}
					if ($Hrtp_count[$h]>$SUBmax_dncs) {$SUBmax_dncs=$Hrtp_count[$h];}
					if (trim($hNApercent)>$SUBmax_nas) {$SUBmax_nas=trim($hNApercent);}
					if (trim($hDROPpercent)>$SUBmax_drops) {$SUBmax_drops=trim($hDROPpercent);}
					if ($Hagent_sec[$h]>$SUBmax_login_time) {$SUBmax_login_time=$Hagent_sec[$h];}
					if ($Hpause_sec[$h]>$SUBmax_pause_time) {$SUBmax_pause_time=$Hpause_sec[$h];}
					$SUBgraph_stats[$z][0]="$Hcalltime_HHMM[$h]";
					$SUBgraph_stats[$z][1]=$Hcalls_count[$h];
					$SUBgraph_stats[$z][2]=$Hsystem_count[$h];
					$SUBgraph_stats[$z][3]=$Hagent_count[$h];
					$SUBgraph_stats[$z][4]=$Hptp_count[$h];
					$SUBgraph_stats[$z][5]=$Hrtp_count[$h];
					$SUBgraph_stats[$z][8]=$Hagent_sec[$h];
					$SUBgraph_stats[$z][9]=$Hpause_sec[$h];

					$Hagent_sec[$h] =			sec_convert($Hagent_sec[$h],'H'); 
					$Hpause_sec[$h] =			sec_convert($Hpause_sec[$h],'H'); 
					$Htalk_sec[$h] =			sec_convert($Htalk_sec[$h],'H'); 
					$Htalk_avg[$h] =			sec_convert($Htalk_avg[$h],'H'); 
					$Hqueue_seconds[$h] =		sec_convert($Hqueue_seconds[$h],'H'); 
					$Hqueue_avg[$h] =			sec_convert($Hqueue_avg[$h],'H'); 
					$Hmax_queue_seconds[$h] =	sec_convert($Hmax_queue_seconds[$h],'H');
					
					$hTOTALcalls =	sprintf("%6s", $Hcalls_count[$h]);
					$hSYSTEMcalls =	sprintf("%6s", $Hsystem_count[$h]);
					$hAGENTcalls =	sprintf("%6s", $Hagent_count[$h]);
					$hPTPcalls =	sprintf("%6s", $Hptp_count[$h]);
					$hRTPcalls =	sprintf("%6s", $Hrtp_count[$h]);
					if ( ($Hcalls_count[$h] < 1) or ($Hna_count[$h] < 1) )
						{$hNApercent=0;}
					else
						{$hNApercent = ( ($Hna_count[$h] / $Hcalls_count[$h]) * 100);}
					$hNApercent =		sprintf("%6.2f",$hNApercent);
					$hNAcalls =		sprintf("%6s", $Hna_count[$h]);
					$hANSWERcalls =	sprintf("%6s", $Hanswer_count[$h]);
					$hSUMagent =	sprintf("%10s", $Hagent_sec[$h]);
					$hSUMpause =	sprintf("%10s", $Hpause_sec[$h]);
					$hSUMtalk =		sprintf("%9s", $Htalk_sec[$h]);
					$hAVGtalk =		sprintf("%7s", $Htalk_avg[$h]);
					$hSUMqueue =	sprintf("%9s", $Hqueue_seconds[$h]);
					$hAVGqueue =	sprintf("%7s", $Hqueue_avg[$h]);
					$hMAXqueue =	sprintf("%7s", $Hmax_queue_seconds[$h]);
					if ( ($Hcalls_count[$h] < 1) or ($Hdrop_count[$h] < 1) )
						{$hDROPpercent=0;}
					else
						{$hDROPpercent = ( ($Hdrop_count[$h] / $Hcalls_count[$h]) * 100);}
					$hDROPpercent =		sprintf("%6.2f",$hDROPpercent);
					$hDROPcalls =	sprintf("%6s", $Hdrop_count[$h]);
					$hPRINT =		sprintf("%19s", $Hcalltime_HHMM[$h]);

					$SUB_ASCII_text .= "| $hPRINT | $hTOTALcalls | $hSYSTEMcalls | $hAGENTcalls | $hPTPcalls | $hRTPcalls | $hNApercent%| $hDROPpercent%| $hSUMagent | $hSUMpause |\n";
					$CSV_subreports.="\"$hPRINT\",\"$hTOTALcalls\",\"$hSYSTEMcalls\",\"$hAGENTcalls\",\"$hPTPcalls\",\"$hRTPcalls\",\"$hNApercent%\",\"$hDROPpercent%\",\"$hSUMagent\",\"$hSUMpause\"\n";
					if ($DB) {$SUB_ASCII_text .= " $hDROPcalls($Hcalls_count_IN[$h]/$Hdrop_count_OUT[$h]) |\n";}

					$SUBgraph_stats[$z][6]=trim($hNApercent);
					$SUBgraph_stats[$z][7]=trim($hDROPpercent);
					$z++;
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

			$hTOTagent_sec =			sec_convert($hTOTagent_sec,'H'); 
			$hTOTpause_sec =			sec_convert($hTOTpause_sec,'H'); 
			$hTOTtalk_sec =				sec_convert($hTOTtalk_sec,'H'); 
			$hTOTtalk_avg =				sec_convert($hTOTtalk_avg,'H'); 
			$hTOTqueue_seconds =		sec_convert($hTOTqueue_seconds,'H'); 
			$hTOTqueue_avg =			sec_convert($hTOTqueue_avg,'H'); 
			$hTOTmax_queue_seconds =	sec_convert($hTOTmax_queue_seconds,'H'); 

			$hTOTcalls_count =			sprintf("%6s", $hTOTcalls_count);
			$hTOTsystem_count =			sprintf("%6s", $hTOTsystem_count);
			$hTOTagent_count =			sprintf("%6s", $hTOTagent_count);
			$hTOTptp_count =			sprintf("%6s", $hTOTptp_count);
			$hTOTrtp_count =			sprintf("%6s", $hTOTrtp_count);
			if ( ($hTOTcalls_count < 1) or ($hTOTna_count < 1) )
				{$hTOTna_percent=0;}
			else
				{$hTOTna_percent = ( ($hTOTna_count / $hTOTcalls_count) * 100);}
			$hTOTna_percent =			sprintf("%6.2f",$hTOTna_percent);
			$hTOTna_count =				sprintf("%6s", $hTOTna_count);
			$hTOTanswer_count =			sprintf("%6s", $hTOTanswer_count);
			$hTOTagent_sec =			sprintf("%10s", $hTOTagent_sec);
			$hTOTpause_sec =			sprintf("%10s", $hTOTpause_sec);
			$hTOTtalk_sec =				sprintf("%9s", $hTOTtalk_sec);
			$hTOTtalk_avg =				sprintf("%7s", $hTOTtalk_avg);
			$hTOTqueue_seconds =		sprintf("%9s", $hTOTqueue_seconds);
			$hTOTqueue_avg =			sprintf("%7s", $hTOTqueue_avg);
			$hTOTmax_queue_seconds =	sprintf("%7s", $hTOTmax_queue_seconds);
			if ( ($hTOTcalls_count < 1) or ($hTOTdrop_count < 1) )
				{$hTOTdrop_percent=0;}
			else
				{$hTOTdrop_percent = ( ($hTOTdrop_count / $hTOTcalls_count) * 100);}
			$hTOTdrop_percent =			sprintf("%6.2f",$hTOTdrop_percent);
			$hTOTdrop_count =			sprintf("%6s", $hTOTdrop_count);

			$SUB_ASCII_text .= "+---------------------+--------+--------+--------+--------+--------+--------+--------+------------+------------+\n";
			$SUB_ASCII_text .= "| TOTALS              | $hTOTcalls_count | $hTOTsystem_count | $hTOTagent_count | $hTOTptp_count | $hTOTrtp_count | $hTOTna_percent%| $hTOTdrop_percent%| $hTOTagent_sec | $hTOTpause_sec |\n";
			$SUB_ASCII_text .= "+---------------------+--------+--------+--------+--------+--------+--------+--------+------------+------------+\n";
			$CSV_subreports.="\"TOTALS\",\"$hTOTcalls_count\",\"$hTOTsystem_count\",\"$hTOTagent_count\",\"$hTOTptp_count\",\"$hTOTrtp_count\",\"$hTOTna_percent%\",\"$hTOTdrop_percent%\",\"$hTOTagent_sec\",\"$hTOTpause_sec\"\n";
			$i++;

			for ($d=0; $d<count($SUBgraph_stats); $d++) {
				if ($d==0) {$class=" first";} else if (($d+1)==count($SUBgraph_stats)) {$class=" last";} else {$class="";}
				$SUBCALLS_graph.="  <tr><td class='chart_td$class'>".$SUBgraph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$SUBgraph_stats[$d][1]/$SUBmax_calls)."' height='16' />".$SUBgraph_stats[$d][1]."</td></tr>";
				$SUBSYSTEMRELEASE_graph.="  <tr><td class='chart_td$class'>".$SUBgraph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$SUBgraph_stats[$d][2]/$SUBmax_system_release)."' height='16' />".$SUBgraph_stats[$d][2]."</td></tr>";
				$SUBAGENTRELEASE_graph.="  <tr><td class='chart_td$class'>".$SUBgraph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$SUBgraph_stats[$d][3]/$SUBmax_agent_release)."' height='16' />".$SUBgraph_stats[$d][3]."</td></tr>";
				$SUBSALES_graph.="  <tr><td class='chart_td$class'>".$SUBgraph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$SUBgraph_stats[$d][4]/$SUBmax_sales)."' height='16' />".$SUBgraph_stats[$d][4]."</td></tr>";
				$SUBDNCS_graph.="  <tr><td class='chart_td$class'>".$SUBgraph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$SUBgraph_stats[$d][5]/$SUBmax_dncs)."' height='16' />".$SUBgraph_stats[$d][5]."</td></tr>";
				$SUBNAS_graph.="  <tr><td class='chart_td$class'>".$SUBgraph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$SUBgraph_stats[$d][6]/$SUBmax_nas)."' height='16' />".$SUBgraph_stats[$d][6]."%</td></tr>";
				$SUBDROPS_graph.="  <tr><td class='chart_td$class'>".$SUBgraph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$SUBgraph_stats[$d][7]/$SUBmax_drops)."' height='16' />".$SUBgraph_stats[$d][7]."%</td></tr>";
				$SUBLOGINTIME_graph.="  <tr><td class='chart_td$class'>".$SUBgraph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$SUBgraph_stats[$d][8]/$SUBmax_login_time)."' height='16' />".sec_convert($SUBgraph_stats[$d][8], 'H')."</td></tr>";
				$SUBPAUSETIME_graph.="  <tr><td class='chart_td$class'>".$SUBgraph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$SUBgraph_stats[$d][9]/$SUBmax_pause_time)."' height='16' />".sec_convert($SUBgraph_stats[$d][9], 'H')."</td></tr>";
			}
			$SUBCALLS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTcalls_count)."</th></tr></table>";
			$SUBSYSTEMRELEASE_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTsystem_count)."</th></tr></table>";
			$SUBAGENTRELEASE_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTagent_count)."</th></tr></table>";
			$SUBSALES_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTptp_count)."</th></tr></table>";
			$SUBDNCS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTrtp_count)."</th></tr></table>";
			$SUBNAS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTna_percent)."%</th></tr></table>";
			$SUBDROPS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTdrop_percent)."%</th></tr></table>";
			$SUBLOGINTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTagent_sec)."</th></tr></table>";
			$SUBPAUSETIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($hTOTpause_sec)."</th></tr></table>";
			$JS_onload.="\tDraw".$SUBrpt_campaign."CampaignGraph('CALLS', '1');\n"; 
			$JS_text.="function Draw".$SUBrpt_campaign."CampaignGraph(graph, th_id) {\n";
			$JS_text.="	var CALLS_graph=\"$SUBCALLS_graph\";\n";
			$JS_text.="	var SYSTEMRELEASE_graph=\"$SUBSYSTEMRELEASE_graph\";\n";
			$JS_text.="	var AGENTRELEASE_graph=\"$SUBAGENTRELEASE_graph\";\n";
			$JS_text.="	var SALES_graph=\"$SUBSALES_graph\";\n";
			$JS_text.="	var DNCS_graph=\"$SUBDNCS_graph\";\n";
			$JS_text.="	var NAS_graph=\"$SUBNAS_graph\";\n";
			$JS_text.="	var DROPS_graph=\"$SUBDROPS_graph\";\n";
			$JS_text.="	var LOGINTIME_graph=\"$SUBLOGINTIME_graph\";\n";
			$JS_text.="	var PAUSETIME_graph=\"$SUBPAUSETIME_graph\";\n";
			$JS_text.="\n";
			$JS_text.="	for (var i=1; i<=9; i++) {\n";
			$JS_text.="		var cellID=\"campbreakdown".$SUBrpt_campaign."\"+i;\n";
			$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
			$JS_text.="	}\n";
			$JS_text.="	var cellID=\"campbreakdown".$SUBrpt_campaign."\"+th_id;\n";
			$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
			$JS_text.="	var graph_to_display=eval(graph+\"_graph\");\n";
			$JS_text.="	document.getElementById('campaign_breakdown_".$SUBrpt_campaign."').innerHTML=graph_to_display;\n";
			$JS_text.="}\n";
			$SUB_HTML_text.=$SUBGRAPH;
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

		$TOTagent_sec =			sec_convert($TOTagent_sec,'H'); 
		$TOTpause_sec =			sec_convert($TOTpause_sec,'H'); 
		$TOTtalk_sec =			sec_convert($TOTtalk_sec,'H'); 
		$TOTtalk_avg =			sec_convert($TOTtalk_avg,'H'); 
		$TOTqueue_seconds =		sec_convert($TOTqueue_seconds,'H'); 
		$TOTqueue_avg =			sec_convert($TOTqueue_avg,'H'); 
		$TOTmax_queue_seconds =	sec_convert($TOTmax_queue_seconds,'H'); 

		$i =					sprintf("%4s", $i);
		$TOTcalls_count =		sprintf("%6s", $TOTcalls_count);
		$TOTsystem_count =		sprintf("%6s", $TOTsystem_count);
		$TOTagent_count =		sprintf("%6s", $TOTagent_count);
		$TOTptp_count =			sprintf("%6s", $TOTptp_count);
		$TOTrtp_count =			sprintf("%6s", $TOTrtp_count);
		if ( ($TOTcalls_count < 1) or ($TOTna_count < 1) )
			{$TOTna_percent=0;}
		else
			{$TOTna_percent = ( ($TOTna_count / $TOTcalls_count) * 100);}
		$TOTna_percent =		sprintf("%6.2f",$TOTna_percent);
		$TOTna_count =			sprintf("%6s", $TOTna_count);
		$TOTanswer_count =		sprintf("%6s", $TOTanswer_count);
		$TOTagent_sec =			sprintf("%10s", $TOTagent_sec);
		$TOTpause_sec =			sprintf("%10s", $TOTpause_sec);
		$TOTtalk_sec =			sprintf("%9s", $TOTtalk_sec);
		$TOTtalk_avg =			sprintf("%7s", $TOTtalk_avg);
		$TOTqueue_seconds =		sprintf("%9s", $TOTqueue_seconds);
		$TOTqueue_avg =			sprintf("%7s", $TOTqueue_avg);
		$TOTmax_queue_seconds =	sprintf("%7s", $TOTmax_queue_seconds);
		if ( ($TOTcalls_count < 1) or ($TOTdrop_count < 1) )
			{$TOTdrop_percent=0;}
		else
			{$TOTdrop_percent = ( ($TOTdrop_count / $TOTcalls_count) * 100);}
		$TOTdrop_percent =		sprintf("%6.2f",$TOTdrop_percent);
		$TOTdrop_count =		sprintf("%6s", $TOTdrop_count);

		$ASCII_text .= "+------------------------------------------+--------+--------+--------+--------+--------+--------+--------+------------+------------+\n";
		$ASCII_text .= "| TOTALS       Campaigns: $i             | $TOTcalls_count | $TOTsystem_count | $TOTagent_count | $TOTptp_count | $TOTrtp_count | $TOTna_percent%| $TOTdrop_percent%| $TOTagent_sec | $TOTpause_sec |\n";
		$ASCII_text .= "+------------------------------------------+--------+--------+--------+--------+--------+--------+--------+------------+------------+\n";
		$CSV_main.="\"TOTALS       Campaigns: $i\",\"$TOTcalls_count\",\"$TOTsystem_count\",\"$TOTagent_count\",\"$TOTptp_count\",\"$TOTrtp_count\",\"$TOTna_percent%\",\"$TOTdrop_percent%\",\"$TOTagent_sec\",\"$TOTpause_sec\"\n";
		}

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

	for ($d=0; $d<count($graph_stats); $d++) {
		if ($d==0) {$class=" first";} else if (($d+1)==count($graph_stats)) {$class=" last";} else {$class="";}
		$CALLS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][1]/$max_calls)."' height='16' />".$graph_stats[$d][1]."</td></tr>";
		$SYSTEMRELEASE_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][2]/$max_system_release)."' height='16' />".$graph_stats[$d][2]."</td></tr>";
		$AGENTRELEASE_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][3]/$max_agent_release)."' height='16' />".$graph_stats[$d][3]."</td></tr>";
		$SALES_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][4]/$max_sales)."' height='16' />".$graph_stats[$d][4]."</td></tr>";
		$DNCS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][5]/$max_dncs)."' height='16' />".$graph_stats[$d][5]."</td></tr>";
		$NAS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][6]/$max_nas)."' height='16' />".$graph_stats[$d][6]."%</td></tr>";
		$DROPS_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][7]/$max_drops)."' height='16' />".$graph_stats[$d][7]."%</td></tr>";
		$LOGINTIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][8]/$max_login_time)."' height='16' />".sec_convert($graph_stats[$d][8], 'H')."</td></tr>";
		$PAUSETIME_graph.="  <tr><td class='chart_td$class'>".$graph_stats[$d][0]."</td><td nowrap class='chart_td value$class'><img src='images/bar.png' alt='' width='".round(400*$graph_stats[$d][9]/$max_pause_time)."' height='16' />".sec_convert($graph_stats[$d][9], 'H')."</td></tr>";
	}
	$CALLS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTcalls_count)."</th></tr></table>";
	$SYSTEMRELEASE_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTsystem_count)."</th></tr></table>";
	$AGENTRELEASE_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTagent_count)."</th></tr></table>";
	$SALES_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTptp_count)."</th></tr></table>";
	$DNCS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTrtp_count)."</th></tr></table>";
	$NAS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTna_percent)."%</th></tr></table>";
	$DROPS_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTdrop_percent)."%</th></tr></table>";
	$LOGINTIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTagent_sec)."</th></tr></table>";
	$PAUSETIME_graph.="<tr><th class='thgraph' scope='col'>TOTAL:</th><th class='thgraph' scope='col'>".trim($TOTpause_sec)."</th></tr></table>";
	$JS_onload.="\tDrawMultiCampaignGraph('CALLS', '1');\n"; 
	$JS_text.="function DrawMultiCampaignGraph(graph, th_id) {\n";
	$JS_text.="	var CALLS_graph=\"$CALLS_graph\";\n";
	$JS_text.="	var SYSTEMRELEASE_graph=\"$SYSTEMRELEASE_graph\";\n";
	$JS_text.="	var AGENTRELEASE_graph=\"$AGENTRELEASE_graph\";\n";
	$JS_text.="	var SALES_graph=\"$SALES_graph\";\n";
	$JS_text.="	var DNCS_graph=\"$DNCS_graph\";\n";
	$JS_text.="	var NAS_graph=\"$NAS_graph\";\n";
	$JS_text.="	var DROPS_graph=\"$DROPS_graph\";\n";
	$JS_text.="	var LOGINTIME_graph=\"$LOGINTIME_graph\";\n";
	$JS_text.="	var PAUSETIME_graph=\"$PAUSETIME_graph\";\n";
	$JS_text.="\n";
	$JS_text.="	for (var i=1; i<=9; i++) {\n";
	$JS_text.="		var cellID=\"campbreakdown\"+i;\n";
	$JS_text.="		document.getElementById(cellID).style.backgroundColor='#DDDDDD';\n";
	$JS_text.="	}\n";
	$JS_text.="	var cellID=\"campbreakdown\"+th_id;\n";
	$JS_text.="	document.getElementById(cellID).style.backgroundColor='#999999';\n";
	$JS_text.="	var graph_to_display=eval(graph+\"_graph\");\n";
	$JS_text.="	document.getElementById('multi_campaign_breakdown').innerHTML=graph_to_display;\n";
	$JS_text.="}\n";
	$GRAPH_text.=$GRAPH;

	if ($file_download>0) {
#		$CSV_report=fopen("AST_OUTBOUNDsummary_interval.csv", "w");
#		$CSV_text=preg_replace('/\s+,/', ',', $CSV_main.$CSV_subreports);
#		fwrite($CSV_report, $CSV_text);
#		fclose($CSV_report);
		$CSVfilename = "AST_OUTBOUNDsummary_interval$US$FILE_TIME.csv";
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
	} else {
		$JS_onload.="}\n";
		$JS_text.=$JS_onload;
		$JS_text.="</script>\n";
		if ($report_display_type=="HTML")
			{
			$MAIN.=$GRAPH_text;
			$SUBoutput.=$SUB_HTML_text;
			}
		else
			{
			$MAIN.=$ASCII_text;
			$SUBoutput.=$SUB_ASCII_text;
			}

		echo "$HEADER";
		echo $JS_text;
		require("admin_header.php");
		echo "$MAIN";
		echo "$SUBoutput";
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
