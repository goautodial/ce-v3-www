<?php
# callbacks_bulk_change.php
# 
# Copyright (C) 2013  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES
# 120819-0119 - First build
# 130414-0021 - Added admin logging
#

header ("Content-type: text/html; charset=utf-8");

require("dbconnect.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["old_user"]))			{$old_user=$_GET["old_user"];}
	elseif (isset($_POST["old_user"]))	{$old_user=$_POST["old_user"];}
if (isset($_GET["new_user"]))			{$new_user=$_GET["new_user"];}
	elseif (isset($_POST["new_user"]))	{$new_user=$_POST["new_user"];}
if (isset($_GET["group"]))				{$group=$_GET["group"];}
	elseif (isset($_POST["group"]))		{$group=$_POST["group"];}
if (isset($_GET["stage"]))				{$stage=$_GET["stage"];}
	elseif (isset($_POST["stage"]))		{$stage=$_POST["stage"];}
if (isset($_GET["confirm_transfer"]))				{$confirm_transfer=$_GET["confirm_transfer"];}
	elseif (isset($_POST["confirm_transfer"]))		{$confirm_transfer=$_POST["confirm_transfer"];}
if (isset($_GET["DB"]))					{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))		{$DB=$_POST["DB"];}
if (isset($_GET["submit"]))				{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))	{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))				{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))	{$SUBMIT=$_POST["SUBMIT"];}

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,webroot_writable,outbound_autodial_active FROM system_settings;";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$qm_conf_ct = mysql_num_rows($rslt);
$i=0;
while ($i < $qm_conf_ct)
	{
	$row=mysql_fetch_row($rslt);
	$non_latin =					$row[0];
	$webroot_writable =				$row[1];
	$SSoutbound_autodial_active =	$row[2];
	$i++;
	}
##### END SETTINGS LOOKUP #####
###########################################

$PHP_AUTH_USER = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_USER);
$PHP_AUTH_PW = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_PW);

$StarTtimE = date("U");
$TODAY = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");
$ip = getenv("REMOTE_ADDR");

if (!isset($begin_date)) {$begin_date = $TODAY;}
if (!isset($end_date)) {$end_date = $TODAY;}

$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 7 and view_reports='1';";
if ($non_latin > 0) { $rslt=mysql_query("SET NAMES 'UTF8'");}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$auth=$row[0];

$fp = fopen ("./project_auth_entries.txt", "a");
$date = date("r");
$ip = getenv("REMOTE_ADDR");
$browser = getenv("HTTP_USER_AGENT");

if( (strlen($PHP_AUTH_USER)<2) or (strlen($PHP_AUTH_PW)<2) or (!$auth))
	{
	Header("WWW-Authenticate: Basic realm=\"VICI-PROJECTS\"");
	Header("HTTP/1.0 401 Unauthorized");
	echo "Invalid Username/Password: |$PHP_AUTH_USER|$PHP_AUTH_PW|\n";
	exit;
	}
else
	{
	if($auth>0)
		{
		$stmt="SELECT full_name,change_agent_campaign,modify_timeclock_log,user_group,user_level from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW'";
		$rslt=mysql_query($stmt, $link);
		$row=mysql_fetch_row($rslt);
		$LOGfullname =				$row[0];
		$change_agent_campaign =	$row[1];
		$modify_timeclock_log =		$row[2];
		$LOGuser_group =			$row[3];
		$user_level=$row[4];
		if ($user_level==9) 
			{
			$ul_clause="where user_level<=9";
			} else {
			$ul_clause="where user_level<$user_level";
			}
		if ($webroot_writable > 0)
			{
			fwrite ($fp, "VICIDIAL|GOOD|$date|$PHP_AUTH_USER|XXXX|$ip|$browser|$LOGfullname|\n");
			fclose($fp);
			}
		}
	else
		{
		if ($webroot_writable > 0)
			{
			fwrite ($fp, "VICIDIAL|FAIL|$date|$PHP_AUTH_USER|XXXX|$ip|$browser|\n");
			fclose($fp);
			}
		exit;
		}
	}

$stmt="SELECT allowed_campaigns,allowed_reports,admin_viewable_groups,admin_viewable_call_times from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {$HTML_text.="|$stmt|\n";}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$LOGallowed_campaigns =			$row[0];
$LOGallowed_reports =			$row[1];
$LOGadmin_viewable_groups =		$row[2];
$LOGadmin_viewable_call_times =	$row[3];

$LOGadmin_viewable_groupsSQL='';
$whereLOGadmin_viewable_groupsSQL='';
if ( (!eregi("--ALL--",$LOGadmin_viewable_groups)) and (strlen($LOGadmin_viewable_groups) > 3) )
	{
	$rawLOGadmin_viewable_groupsSQL = preg_replace("/ -/",'',$LOGadmin_viewable_groups);
	$rawLOGadmin_viewable_groupsSQL = preg_replace("/ /","','",$rawLOGadmin_viewable_groupsSQL);
	$LOGadmin_viewable_groupsSQL = "and user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
	$whereLOGadmin_viewable_groupsSQL = "where user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
	}

$stmt="SELECT user_group,group_name from vicidial_user_groups $whereLOGadmin_viewable_groupsSQL order by user_group desc;";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$groups_to_print = mysql_num_rows($rslt);
$i=0;
while ($i < $groups_to_print)
	{
	$row=mysql_fetch_row($rslt);
	$groups[$i] =		$row[0];
	$group_names[$i] =	$row[1];
	$i++;
	}

if ($SUBMIT && $old_user && $new_user && $confirm_transfer) {
	$upd_stmt="UPDATE vicidial_callbacks set user='$new_user' where recipient='USERONLY' and status!='INACTIVE' and user='$old_user' $LOGadmin_viewable_groupsSQL";
	$upd_rslt=mysql_query($upd_stmt, $link);
	if ($DB) {echo "$upd_stmt\n";}

	### LOG INSERTION Admin Log Table ###
	$SQL_log = "$upd_stmt|";
	$SQL_log = ereg_replace(';','',$SQL_log);
	$SQL_log = addslashes($SQL_log);
	$stmt="INSERT INTO vicidial_admin_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$ip', event_section='USERS', event_type='MODIFY', record_id='$new_user', event_code='ADMIN CALLBACK BULK CHANGE', event_sql=\"$SQL_log\", event_notes='Old user: $old_user';";
	if ($DB) {echo "|$stmt|\n";}
	$rslt=mysql_query($stmt, $link);
}
?>
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<title>ADMINISTRATION: USERONLY Callbacks Transfer
<?php

##### BEGIN Set variables to make header show properly #####
$ADD =					'311111';
$hh =					'usergroups';
$LOGast_admin_access =	'1';
$ADMIN =				'admin.php';
$page_width='770';
$section_width='750';
$header_font_size='3';
$subheader_font_size='2';
$subcamp_font_size='2';
$header_selected_bold='<b>';
$header_nonselected_bold='';
$usergroups_color =		'#FFFF99';
$usergroups_font =		'BLACK';
$usergroups_color =		'#E6E6E6';
$subcamp_color =	'#C6C6C6';
##### END Set variables to make header show properly #####

require("admin_header.php");

?>

<CENTER>
<TABLE WIDTH=620 BGCOLOR=#D9E6FE cellpadding=2 cellspacing=0><TR BGCOLOR=#015B91><TD ALIGN=LEFT><FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B> &nbsp; USERONLY Callback Transfer</TD><TD ALIGN=RIGHT><FONT FACE="ARIAL,HELVETICA" COLOR=WHITE SIZE=2><B> &nbsp; </TD></TR>




<?php 

echo "<TR BGCOLOR=\"#F0F5FE\"><TD ALIGN=center COLSPAN=2><FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=3><B> &nbsp; \n";

### callbacks change form
echo "<form action=$PHP_SELF method=POST>\n";
echo "<input type=hidden name=DB value=\"$DB\">\n";
if ($SUBMIT && $old_user && $new_user && !$confirm_transfer) 
	{
	$stmt="select count(*) as ct from vicidial_callbacks where recipient='USERONLY' and status!='INACTIVE' and user='$old_user' $LOGadmin_viewable_groupsSQL";
	$rslt=mysql_query($stmt, $link);
	$row=mysql_fetch_array($rslt);
	$callback_ct=$row["ct"];

	$user_stmt="select full_name from vicidial_users $ul_clause and user='$old_user' $LOGadmin_viewable_groupsSQL";
	$user_rslt=mysql_query($user_stmt, $link);
	$user_row=mysql_fetch_array($user_rslt);
	$old_user_name=$user_row["full_name"];

	$user_stmt="select full_name from vicidial_users $ul_clause and user='$new_user' $LOGadmin_viewable_groupsSQL ";
	$user_rslt=mysql_query($user_stmt, $link);
	$user_row=mysql_fetch_array($user_rslt);
	$new_user_name=$user_row["full_name"];

	echo "<input type=hidden name=old_user value=\"$old_user\">\n";
	echo "<input type=hidden name=new_user value=\"$new_user\">\n";
	echo "You are about to transfer $callback_ct callbacks<BR>\n";
	echo "from user $old_user - $old_user_name<BR>\n";
	echo "to user $new_user - $new_user_name<BR><BR><BR>\n";
	echo "<a href='$PHP_SELF?DB=$DB&old_user=$old_user&new_user=$new_user&confirm_transfer=1&SUBMIT=1'>CLICK TO CONFIRM</a><BR><BR>";
	echo "<a href='$PHP_SELF?DB=$DB'>CLICK TO CANCEL</a><BR><BR>";
	} 
else 
	{
	$stmt="select user, count(*) as ct from vicidial_callbacks where recipient='USERONLY' and status!='INACTIVE' $LOGadmin_viewable_groupsSQL group by user order by user";
	$rslt=mysql_query($stmt, $link);
	if ($DB) {echo "$stmt\n";}
	# <tr><td align='right'>
	echo "Agents with callbacks:<BR><select name='old_user' size=5>\n";
	if (mysql_num_rows($rslt)>0) 
		{
		while ($row=mysql_fetch_array($rslt)) 
			{
			$user_stmt="select full_name from vicidial_users $ul_clause and user='$row[user]' $LOGadmin_viewable_groupsSQL";
			$user_rslt=mysql_query($user_stmt, $link);
			if (mysql_num_rows($user_rslt)>0) 
				{
				$user_row=mysql_fetch_array($user_rslt);
				echo "\t<option value='$row[user]'>$row[user] - $user_row[full_name] - ($row[ct] callbacks)</option>\n";
				}
			}
		} 
	else 
		{
		echo "\t<option value=''>**NO CALLBACKS**</option>\n";
		}
	echo "</select>";
	echo "<BR/><BR/><input type='submit' name='SUBMIT' value='  TRANSFER TO '><BR/><BR/>";

	$stmt="select user, full_name from vicidial_users $ul_clause $LOGadmin_viewable_groupsSQL order by user asc";
	if ($DB) {echo "$stmt\n";}
	$rslt=mysql_query($stmt, $link);
	echo "<select name='new_user' size=5>\n";
	while ($row=mysql_fetch_array($rslt)) 
		{
		echo "\t<option value='$row[user]'>$row[user] - $row[full_name]</option>\n";
		}
	echo "</select>\n";
	}
echo "</form>\n";

echo "\n<BR><BR><BR>";


$ENDtime = date("U");

$RUNtime = ($ENDtime - $StarTtimE);

echo "\n\n\n<br><br><br>\n\n";


echo "<font size=0>\n\n\n<br><br><br>\nscript runtime: $RUNtime seconds</font>";

echo "|$stage|$group|";

?>


</TD></TR><TABLE>
</body>
</html>

<?php
	
exit; 


?>
