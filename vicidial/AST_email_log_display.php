<?php
# AST_email_log_display - VICIDIAL administration page
#
# Copyright (C) 2013  Joe Johnson <freewermadmin@gmail.com>    LICENSE: AGPLv2
#
# This page displays emails from the log
#
# changes:
# 130221-2124 - First build
#

require("dbconnect.php");
require("functions.php");

if (isset($_GET["DB"]))				{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))	{$DB=$_POST["DB"];}
if (isset($_GET["email_row_id"]))	{$email_row_id=$_GET["email_row_id"];}
	elseif (isset($_POST["email_row_id"]))	{$email_row_id=$_POST["email_row_id"];}
if (isset($_GET["email_log_id"]))	{$email_log_id=$_GET["email_log_id"];}
	elseif (isset($_POST["email_log_id"]))	{$email_log_id=$_POST["email_log_id"];}

header ("Content-type: text/html; charset=utf-8");
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,timeclock_end_of_day,agentonly_callback_campaign_lock,custom_fields_enabled,allow_emails FROM system_settings;";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$qm_conf_ct = mysql_num_rows($rslt);
if ($qm_conf_ct > 0)
	{
	$row=mysql_fetch_row($rslt);
	$non_latin =							$row[0];
	$timeclock_end_of_day =					$row[1];
	$agentonly_callback_campaign_lock =		$row[2];
	$custom_fields_enabled =				$row[3];
	$allow_emails =				$row[4];
	}
##### END SETTINGS LOOKUP #####
###########################################

if ($allow_emails<1) 
	{
	echo "Your system does not have the email setting enabled\n";
	exit;
	}

if ($non_latin < 1)
	{
	$user=ereg_replace("[^-_0-9a-zA-Z]","",$user);
	$pass=ereg_replace("[^-_0-9a-zA-Z]","",$pass);
	}
else
	{
	$user = ereg_replace("'|\"|\\\\|;","",$user);
	$pass = ereg_replace("'|\"|\\\\|;","",$pass);
	}

if ($email_log_id) {
	$stmt="select * from vicidial_email_log where email_log_id='$email_log_id'";
	$rslt=mysql_query($stmt, $link);
} else if ($email_row_id) {
	$stmt="select * from vicidial_email_list where email_row_id='$email_row_id'";
	$rslt=mysql_query($stmt, $link);
}
	
if (mysql_num_rows($rslt)>0) {
	$row=mysql_fetch_array($rslt);
	$row["message"]=preg_replace('/\r|\n/', "<BR/>", $row["message"]);
	$EMAIL_form="<TABLE cellspacing=2 cellpadding=2 bgcolor='#CCCCCC' width='500'>\n";
	$EMAIL_form.="<tr bgcolor=white><td align='right' valign='top' width='100'>Date sent:</td><td align='left' valign='top' width='400'>$row[email_date]</td></tr>\n";
	$EMAIL_form.="<tr bgcolor=white><td align='right' valign='top' width='100'>Message:</td><td align='left' valign='top' width='400'>$row[message]</td></tr>\n";
	$EMAIL_form.="</table>";
}
?>

<html>
<head>
<title>VICIDIAL email frame</title>
</head>
<body topmargin=0 leftmargin=0>
<?php echo $EMAIL_form; ?>
</body>
</html>
