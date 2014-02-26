<?php 
# AST_carrier_log_report.php
# 
# Copyright (C) 2013  Joe Johnson, Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES
# 120210-2202 - First build
# 130413-2213 - Added SIP codes summary and fields, and report logging
# 130418-1048 - Changed how server list is generated
#

$startMS = microtime();

require("dbconnect.php");

$report_name='Carrier Log Report';

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["query_date"]))				{$query_date=$_GET["query_date"];}
	elseif (isset($_POST["query_date"]))	{$query_date=$_POST["query_date"];}
if (isset($_GET["query_date_D"]))			{$query_date_D=$_GET["query_date_D"];}
	elseif (isset($_POST["query_date_D"]))	{$query_date_D=$_POST["query_date_D"];}
if (isset($_GET["query_date_T"]))			{$query_date_T=$_GET["query_date_T"];}
	elseif (isset($_POST["query_date_T"]))	{$query_date_T=$_POST["query_date_T"];}
if (isset($_GET["server_ip"]))				{$server_ip=$_GET["server_ip"];}
	elseif (isset($_POST["server_ip"]))		{$server_ip=$_POST["server_ip"];}
if (isset($_GET["file_download"]))			{$file_download=$_GET["file_download"];}
	elseif (isset($_POST["file_download"]))	{$file_download=$_POST["file_download"];}
if (isset($_GET["lower_limit"]))			{$lower_limit=$_GET["lower_limit"];}
	elseif (isset($_POST["lower_limit"]))	{$lower_limit=$_POST["lower_limit"];}
if (isset($_GET["upper_limit"]))			{$upper_limit=$_GET["upper_limit"];}
	elseif (isset($_POST["upper_limit"]))	{$upper_limit=$_POST["upper_limit"];}
if (isset($_GET["DB"]))						{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))			{$DB=$_POST["DB"];}
if (isset($_GET["submit"]))					{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))		{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))					{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))		{$SUBMIT=$_POST["SUBMIT"];}

$PHP_AUTH_USER = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_USER);
$PHP_AUTH_PW = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_PW);

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

$NOW_DATE = date("Y-m-d");

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

$stmt="INSERT INTO vicidial_report_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name', url='$LOGfull_url';";
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


if (strlen($query_date_D) < 6) {$query_date_D = "00:00:00";}
if (strlen($query_date_T) < 6) {$query_date_T = "23:59:59";}
if (!isset($query_date)) {$query_date = $NOW_DATE;}

$server_ip_string='|';
$server_ip_ct = count($server_ip);
$i=0;
while($i < $server_ip_ct)
	{
	$server_ip_string .= "$server_ip[$i]|";
	$i++;
	}

$server_stmt="select server_ip,server_description from servers where active_asterisk_server='Y' order by server_ip asc";
$server_rslt=mysql_query($server_stmt, $link);
$servers_to_print=mysql_num_rows($server_rslt);
$i=0;
while ($i < $servers_to_print)
	{
	$row=mysql_fetch_row($server_rslt);
	$LISTserverIPs[$i] =		$row[0];
	$LISTserver_names[$i] =	$row[1];
	if (ereg("-ALL",$server_ip_string) )
		{
		$server_ip[$i] = $LISTserverIPs[$i];
		}
	$i++;
	}

$i=0;
$server_ips_string='|';
$server_ip_ct = count($server_ip);
while($i < $server_ip_ct)
	{
	if ( (strlen($server_ip[$i]) > 0) and (preg_match("/\|$server_ip[$i]\|/",$server_ip_string)) )
		{
		$server_ips_string .= "$server_ip[$i]|";
		$server_ip_SQL .= "'$server_ip[$i]',";
		$server_ipQS .= "&server_ip[]=$server_ip[$i]";
		}
	$i++;
	}

if ( (ereg("--ALL--",$server_ip_string) ) or ($server_ip_ct < 1) )
	{
	$server_ip_SQL = "";
	$server_rpt_string="- ALL servers ";
	if (ereg("--ALL--",$server_ip_string)) {$server_ipQS="&server_ip[]=--ALL--";}
	}
else
	{
	$server_ip_SQL = eregi_replace(",$",'',$server_ip_SQL);
	$server_ip_SQL = "and server_ip IN($server_ip_SQL)";
	$server_rpt_string="- server(s) ".preg_replace('/\|/', ", ", substr($server_ip_string, 1, -1));
	}
if (strlen($server_ip_SQL)<3) {$server_ip_SQL="";}

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

$MAIN.="<TABLE CELLPADDING=4 CELLSPACING=0><TR><TD>";
$MAIN.="<FORM ACTION=\"$PHP_SELF\" METHOD=GET name=vicidial_report id=vicidial_report>\n";
$MAIN.="<TABLE BORDER=0 cellspacing=5 cellpadding=5><TR><TD VALIGN=TOP align=center>\n";
$MAIN.="<INPUT TYPE=HIDDEN NAME=DB VALUE=\"$DB\">\n";
$MAIN.="Date:\n";
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

$MAIN.="<BR><BR><INPUT TYPE=TEXT NAME=query_date_D SIZE=9 MAXLENGTH=8 VALUE=\"$query_date_D\">";

$MAIN.="<BR> to <BR><INPUT TYPE=TEXT NAME=query_date_T SIZE=9 MAXLENGTH=8 VALUE=\"$query_date_T\">";

$MAIN.="</TD><TD ROWSPAN=2 VALIGN=TOP>Server IP:<BR/>\n";
$MAIN.="<SELECT SIZE=5 NAME=server_ip[] multiple>\n";
if  (eregi("--ALL--",$server_ip_string))
	{$MAIN.="<option value=\"--ALL--\" selected>-- ALL SERVERS --</option>\n";}
else
	{$MAIN.="<option value=\"--ALL--\">-- ALL SERVERS --</option>\n";}
$o=0;
while ($servers_to_print > $o)
	{
	if (ereg("\|$LISTserverIPs[$o]\|",$server_ip_string)) 
		{$MAIN.="<option selected value=\"$LISTserverIPs[$o]\">$LISTserverIPs[$o] - $LISTserver_names[$o]</option>\n";}
	else
		{$MAIN.="<option value=\"$LISTserverIPs[$o]\">$LISTserverIPs[$o] - $LISTserver_names[$o]</option>\n";}
	$o++;
	}
$MAIN.="</SELECT></TD><TD ROWSPAN=2 VALIGN=middle align=center>\n";
$MAIN.="<INPUT TYPE=submit NAME=SUBMIT VALUE=SUBMIT><BR/><BR/>\n";
$MAIN.="</TD></TR></TABLE>\n";
if ($SUBMIT && $server_ip_ct>0) {
	$stmt="select hangup_cause, dialstatus, count(*) as ct From vicidial_carrier_log where call_date>='$query_date $query_date_D' and call_date<='$query_date $query_date_T' $server_ip_SQL group by hangup_cause, dialstatus order by hangup_cause, dialstatus";
	$rslt=mysql_query($stmt, $link);
	$MAIN.="<PRE><font size=2>\n";
	if ($DB) {$MAIN.=$stmt."\n";}
	if (mysql_num_rows($rslt)>0) {
		$MAIN.="--- DIAL STATUS BREAKDOWN FOR $query_date, $query_date_D TO $query_date_T $server_rpt_string\n";
		$MAIN.="+--------------+-------------+---------+\n";
		$MAIN.="| HANGUP CAUSE | DIAL STATUS |  COUNT  |\n";
		$MAIN.="+--------------+-------------+---------+\n";
		$total_count=0;
		while ($row=mysql_fetch_array($rslt)) {
			$MAIN.="| ".sprintf("%-13s", $row["hangup_cause"]);
			$MAIN.="| ".sprintf("%-12s", $row["dialstatus"]);
			$MAIN.="| ".sprintf("%-8s", $row["ct"]);
			$MAIN.="|\n";
			$total_count+=$row["ct"];
		}
		$MAIN.="+--------------+-------------+---------+\n";
		$MAIN.="|                      TOTAL | ".sprintf("%-8s", $total_count)."|\n";
		$MAIN.="+--------------+-------------+---------+\n\n";

		$stmt="select sip_hangup_cause,sip_hangup_reason,count(*) as ct From vicidial_carrier_log where call_date>='$query_date $query_date_D' and call_date<='$query_date $query_date_T' $server_ip_SQL group by sip_hangup_cause,sip_hangup_reason order by sip_hangup_cause,sip_hangup_reason";
		$rslt=mysql_query($stmt, $link);
		$MAIN.="<PRE><font size=2>\n";
		if ($DB) {$MAIN.=$stmt."\n";}
		if (mysql_num_rows($rslt)>0) {
			$MAIN.="--- SIP ERROR REASON BREAKDOWN FOR $query_date, $query_date_D TO $query_date_T $server_rpt_string\n";
			$MAIN.="+----------+--------------------------------+---------+\n";
			$MAIN.="| SIP CODE | SIP HANGUP REASON              |  COUNT  |\n";
			$MAIN.="+----------+--------------------------------+---------+\n";
			$total_count=0;
			while ($row=mysql_fetch_array($rslt)) {
				$MAIN.="| ".sprintf("%8s", $row["sip_hangup_cause"])." ";
				$MAIN.="| ".sprintf("%-31s", $row["sip_hangup_reason"]);
				$MAIN.="| ".sprintf("%-8s", $row["ct"]);
				$MAIN.="|\n";
				$total_count+=$row["ct"];
			}
			$MAIN.="+----------+--------------------------------+---------+\n";
			$MAIN.="|                                     TOTAL | ".sprintf("%-8s", $total_count)."|\n";
			$MAIN.="+-------------------------------------------+---------+\n\n\n";
		}

		$rpt_stmt="select vicidial_carrier_log.*, vicidial_log.phone_number from vicidial_carrier_log left join vicidial_log on vicidial_log.uniqueid=vicidial_carrier_log.uniqueid where vicidial_carrier_log.call_date>='$query_date $query_date_D' and vicidial_carrier_log.call_date<='$query_date $query_date_T' $server_ip_SQL order by vicidial_carrier_log.call_date asc";
		$rpt_rslt=mysql_query($rpt_stmt, $link);
		if ($DB) {$MAIN.=$rpt_stmt."\n";}

		if (!$lower_limit) {$lower_limit=1;}
		if ($lower_limit+999>=mysql_num_rows($rpt_rslt)) {$upper_limit=($lower_limit+mysql_num_rows($rpt_rslt)%1000)-1;} else {$upper_limit=$lower_limit+999;}
		
		$MAIN.="--- CARRIER LOG RECORDS FOR $query_date, $query_date_D TO $query_date_T $server_rpt_string, RECORDS #$lower_limit-$upper_limit               <a href=\"$PHP_SELF?SUBMIT=$SUBMIT&DB=$DB&type=$type&query_date=$query_date&query_date_D=$query_date_D&query_date_T=$query_date_T$server_ipQS&lower_limit=$lower_limit&upper_limit=$upper_limit&file_download=1\">[DOWNLOAD]</a>\n";
		$carrier_rpt.="+----------------------+---------------------+-----------------+-----------+--------------+-------------+------------------------------------------+-----------+---------------+----------+--------------------------------+--------------+\n";
		$carrier_rpt.="| UNIQUE ID            | CALL DATE           | SERVER IP       | LEAD ID   | HANGUP CAUSE | DIAL STATUS | CHANNEL                                  | DIAL TIME | ANSWERED TIME | SIP CODE | SIP HANGUP REASON              | PHONE NUMBER |\n";
		$carrier_rpt.="+----------------------+---------------------+-----------------+-----------+--------------+-------------+------------------------------------------+-----------+---------------+----------+--------------------------------+--------------+\n";
		$CSV_text="\"UNIQUE ID\",\"CALL DATE\",\"SERVER IP\",\"LEAD ID\",\"HANGUP CAUSE\",\"DIAL STATUS\",\"CHANNEL\",\"DIAL TIME\",\"ANSWERED TIME\",\"SIP CODE\",\"SIP HANGUP REASON\",\"PHONE NUMBER\"\n";

		for ($i=1; $i<=mysql_num_rows($rpt_rslt); $i++) {
			$row=mysql_fetch_array($rpt_rslt);
			$phone_number=""; $phone_note="";

			if (strlen($row["phone_number"])==0) {
				$stmt2="select phone_number, alt_phone, address3 from vicidial_list where lead_id='$row[lead_id]'";
				$rslt2=mysql_query($stmt2, $link);
				$channel=$row["channel"];
				while ($row2=mysql_fetch_array($rslt2)) {
					if (strlen($row2["alt_phone"])>=7 && preg_match("/$row2[alt_phone]/", $channel)) {$phone_number=$row2["alt_phone"]; $phone_note="ALT";}
					else if (strlen($row2["address3"])>=7 && preg_match("/$row2[address3]/", $channel)) {$phone_number=$row2["address3"]; $phone_note="ADDR3";}
					else if (strlen($row2["phone_number"])>=7 && preg_match("/$row2[phone_number]/", $channel)) {$phone_number=$row2["phone_number"]; $phone_note="*";}
				}
			} else {
				$phone_number=$row["phone_number"];
			}

			$CSV_text.="\"$row[uniqueid]\",\"$row[call_date]\",\"$row[server_ip]\",\"$row[lead_id]\",\"$row[hangup_cause]\",\"$row[dialstatus]\",\"$row[channel]\",\"$row[dial_time]\",\"$row[answered_time]\",\"$row[sip_hangup_cause]\",\"$row[sip_hangup_reason]\",\"$phone_number\"\n";
			if ($i>=$lower_limit && $i<=$upper_limit) {
				if (strlen($row["channel"])>37) {$row["channel"]=substr($row["channel"],0,37)."...";}
				$carrier_rpt.="| ".sprintf("%-21s", $row["uniqueid"]); 
				$carrier_rpt.="| ".sprintf("%-20s", $row["call_date"]); 
				$carrier_rpt.="| ".sprintf("%-16s", $row["server_ip"]); 
				$carrier_rpt.="| ".sprintf("%-10s", $row["lead_id"]); 
				$carrier_rpt.="| ".sprintf("%-13s", $row["hangup_cause"]); 
				$carrier_rpt.="| ".sprintf("%-12s", $row["dialstatus"]); 
				$carrier_rpt.="| ".sprintf("%-41s", $row["channel"]); 
				$carrier_rpt.="| ".sprintf("%-10s", $row["dial_time"]); 
				$carrier_rpt.="| ".sprintf("%-14s", $row["answered_time"]); 
				$carrier_rpt.="| ".sprintf("%8s", $row["sip_hangup_cause"])." "; 
				$carrier_rpt.="| ".sprintf("%-31s", $row["sip_hangup_reason"]); 
				$carrier_rpt.="| ".sprintf("%-13s", $phone_number)."|\n"; 
			}
		}
		$carrier_rpt.="+----------------------+---------------------+-----------------+-----------+--------------+-------------+------------------------------------------+-----------+---------------+----------+--------------------------------+--------------+\n";

		$carrier_rpt_hf="";
		$ll=$lower_limit-1000;
		if ($ll>=1) {
			$carrier_rpt_hf.="<a href=\"$PHP_SELF?SUBMIT=$SUBMIT&DB=$DB&type=$type&query_date=$query_date&query_date_D=$query_date_D&query_date_T=$query_date_T$server_ipQS&lower_limit=$ll\">[<<< PREV 1000 records]</a>";
		} else {
			$carrier_rpt_hf.=sprintf("%-23s", " ");
		}
		$carrier_rpt_hf.=sprintf("%-145s", " ");
		if (($lower_limit+1000)<mysql_num_rows($rpt_rslt)) {
			if ($upper_limit+1000>=mysql_num_rows($rpt_rslt)) {$max_limit=mysql_num_rows($rpt_rslt)-$upper_limit;} else {$max_limit=1000;}
			$carrier_rpt_hf.="<a href=\"$PHP_SELF?SUBMIT=$SUBMIT&DB=$DB&type=$type&query_date=$query_date&query_date_D=$query_date_D&query_date_T=$query_date_T$server_ipQS&lower_limit=".($lower_limit+1000)."\">[NEXT $max_limit records >>>]</a>";
		} else {
			$carrier_rpt_hf.=sprintf("%23s", " ");
		}
		$carrier_rpt_hf.="\n";
		$MAIN.=$carrier_rpt_hf.$carrier_rpt.$carrier_rpt_hf;
	} else {
		$MAIN.="*** NO RECORDS FOUND ***\n";
	}
	$MAIN.="</font></PRE>\n";

	$MAIN.="</form></BODY></HTML>\n";


}
	if ($file_download>0) {
		$FILE_TIME = date("Ymd-His");
		$CSVfilename = "AST_carrier_log_report_$US$FILE_TIME.csv";
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
