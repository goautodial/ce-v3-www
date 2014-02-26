<?php 
# AST_VICIDIAL_hopperlist.php
# 
# Copyright (C) 2013  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES
#
# 60619-1654 - Added variable filtering to eliminate SQL injection attack threat
#            - Added required user/pass to gain access to this page
# 70115-1614 - Added ALT field for vicidial_hopper alt_dial column
# 71029-0852 - Added list_id to the output
# 71030-2118 - Added priority to display
# 90508-0644 - Changed to PHP long tags
# 91023-1540 - Changed to only show hopper status of READY
# 101111-1253 - Added source field
# 111103-1207 - Added admin_hide_phone_data and admin_hide_lead_data options
# 120210-1218 - Added vendor_lead_code to output
# 120221-0059 - Added User Group restriction settings
# 130414-0159 - Added report logging
#

$startMS = microtime();

$report_name='Hopper List Report';

require("dbconnect.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["DB"]))					{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))		{$DB=$_POST["DB"];}
if (isset($_GET["group"]))				{$group=$_GET["group"];}
	elseif (isset($_POST["group"]))		{$group=$_POST["group"];}
if (isset($_GET["submit"]))				{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))	{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))				{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))	{$SUBMIT=$_POST["SUBMIT"];}

$PHP_AUTH_USER = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_USER);
$PHP_AUTH_PW = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_PW);

$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 6 and view_reports='1' and modify_campaigns='1';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$auth=$row[0];

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

$stmt="SELECT full_name,user_group,admin_hide_lead_data,admin_hide_phone_data from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW'";
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$LOGfullname =				$row[0];
$LOGuser_group =			$row[1];
$LOGadmin_hide_lead_data =	$row[2];
$LOGadmin_hide_phone_data =	$row[3];

$NOW_DATE = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");
$STARTtime = date("U");
if (!isset($group)) {$group = '';}
if (!isset($query_date)) {$query_date = $NOW_DATE;}
if (!isset($server_ip)) {$server_ip = '10.10.10.15';}

$stmt="SELECT allowed_campaigns,allowed_reports,admin_viewable_groups,admin_viewable_call_times from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {$HTML_text.="|$stmt|\n";}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$LOGallowed_campaigns =			$row[0];
$LOGallowed_reports =			$row[1];
$LOGadmin_viewable_groups =		$row[2];
$LOGadmin_viewable_call_times =	$row[3];

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
if ($DB) {echo "$stmt\n";}
$campaigns_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $campaigns_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$campaign_id[$i] =$row[0];
	$campaign_name[$i] =$row[1];
	$i++;
	}
?>

<HTML>
<HEAD>
<STYLE type="text/css">
<!--
   .green {color: white; background-color: green}
   .red {color: white; background-color: red}
   .blue {color: white; background-color: blue}
   .purple {color: white; background-color: purple}
-->
 </STYLE>

<?php 
echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";
echo "<TITLE>Hopper List Report</TITLE></HEAD><BODY BGCOLOR=WHITE marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>\n";

	$short_header=1;

	require("admin_header.php");

echo "<TABLE CELLPADDING=4 CELLSPACING=0><TR><TD>";
echo "<FORM ACTION=\"$PHP_SELF\" METHOD=GET>\n";
echo "<SELECT SIZE=1 NAME=group>\n";
$o=0;
while ($campaigns_to_print > $o)
	{
	if ($campaign_id[$o] == $group) {echo "<option selected value=\"$campaign_id[$o]\">$campaign_id[$o] - $campaign_name[$o]</option>\n";}
	else {echo "<option value=\"$campaign_id[$o]\">$campaign_id[$o] - $campaign_name[$o]</option>\n";}
	$o++;
	}
echo "</SELECT>\n";
echo "<INPUT TYPE=SUBMIT NAME=SUBMIT VALUE=SUBMIT>\n";
echo " &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href=\"./admin.php?ADD=34&campaign_id=$group\">MODIFY</a> \n";
echo "</FORM>\n\n";

echo "<PRE><FONT SIZE=2>\n\n";


if (!$group)
	{
	echo "\n\n";
	echo "PLEASE SELECT A CAMPAIGN ABOVE AND CLICK SUBMIT\n";
	}

else
	{
	echo "Live Current Hopper List                      $NOW_TIME\n";

	echo "\n";
	echo "---------- TOTALS\n";

	$stmt="select count(*) from vicidial_hopper where campaign_id='" . mysql_real_escape_string($group) . "' $LOGallowed_campaignsSQL;";
	$rslt=mysql_query($stmt, $link);
	if ($DB) {echo "$stmt\n";}
	$row=mysql_fetch_row($rslt);

	$TOTALcalls =	sprintf("%10s", $row[0]);

	echo "Total leads in hopper right now:       $TOTALcalls\n";


	##############################
	#########  LEAD STATS

	echo "\n";
	echo "---------- LEADS IN HOPPER\n";
	echo "+------+--------+-----------+------------+------------+-------+--------+-------+--------+-------+-------+----------------------+\n";
	echo "|ORDER |PRIORITY| LEAD ID   | LIST ID    | PHONE NUM  | STATE | STATUS | COUNT | GMT    | ALT   | SOURCE| VENDOR LEAD CODE     |\n";
	echo "+------+--------+-----------+------------+------------+-------+--------+-------+--------+-------+-------+----------------------+\n";

	$stmt="select vicidial_hopper.lead_id,phone_number,vicidial_hopper.state,vicidial_list.status,called_count,vicidial_hopper.gmt_offset_now,hopper_id,alt_dial,vicidial_hopper.list_id,vicidial_hopper.priority,vicidial_hopper.source,vicidial_hopper.vendor_lead_code from vicidial_hopper,vicidial_list where vicidial_hopper.campaign_id='" . mysql_real_escape_string($group) . "' and vicidial_hopper.status='READY' and vicidial_hopper.lead_id=vicidial_list.lead_id $LOGallowed_campaignsSQL order by priority desc,hopper_id limit 5000;";
	$rslt=mysql_query($stmt, $link);
	if ($DB) {echo "$stmt\n";}
	$users_to_print = mysql_num_rows($rslt);
	$i=0;
	while ($i < $users_to_print)
		{
		$row=mysql_fetch_row($rslt);

		if ($LOGadmin_hide_phone_data != '0')
			{
			if ($DB > 0) {echo "HIDEPHONEDATA|$row[1]|$LOGadmin_hide_phone_data|\n";}
			$phone_temp = $row[1];
			if (strlen($phone_temp) > 0)
				{
				if ($LOGadmin_hide_phone_data == '4_DIGITS')
					{$row[1] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);}
				elseif ($LOGadmin_hide_phone_data == '3_DIGITS')
					{$row[1] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);}
				elseif ($LOGadmin_hide_phone_data == '2_DIGITS')
					{$row[1] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);}
				else
					{$row[1] = preg_replace("/./",'X',$phone_temp);}
				}
			}
		if ($LOGadmin_hide_lead_data != '0')
			{
			if ($DB > 0) {echo "HIDELEADDATA|$row[2]|$LOGadmin_hide_lead_data|\n";}
			if (strlen($row[2]) > 0)
				{$state_temp = $row[2];   $row[2] = preg_replace("/./",'X',$state_temp);}
			if (strlen($row[11]) > 0)
				{$vlc_temp = $row[11];   $row[11] = preg_replace("/./",'X',$vlc_temp);}
			}

		$FMT_i =		sprintf("%-4s", $i);
		$lead_id =		sprintf("%-9s", $row[0]);
		$phone_number =	sprintf("%-10s", $row[1]);
		$state =		sprintf("%-5s", $row[2]);
		$status =		sprintf("%-6s", $row[3]);
		$count =		sprintf("%-5s", $row[4]);
		$gmt =			sprintf("%-6s", $row[5]);
		$hopper_id =	sprintf("%-6s", $row[6]);
		$alt_dial =		sprintf("%-5s", $row[7]);
		$list_id =		sprintf("%-10s", $row[8]);
		$priority =		sprintf("%-6s", $row[9]);
		$source =		sprintf("%-5s", $row[10]);
		$vendor_lead_code =	sprintf("%-20s", $row[11]);

		if ($DB) {echo "| $FMT_i | $priority | $lead_id | $list_id | $phone_number | $state | $status | $count | $gmt | $hopper_id |\n";}
		else {echo "| $FMT_i | $priority | $lead_id | $list_id | $phone_number | $state | $status | $count | $gmt | $alt_dial | $source | $vendor_lead_code |\n";}

		$i++;
		}

	echo "+------+--------+-----------+------------+------------+-------+--------+-------+--------+-------+-------+----------------------+\n";

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

Sources:
A = Auto-alt-dial
C = Scheduled Callbacks
N = Xth New lead order
P = Non-Agent API hopper load
Q = No-hopper queue insert
R = Recycled leads
S = Standard hopper load

</PRE>

</TD></TR></TABLE>

</BODY></HTML>
