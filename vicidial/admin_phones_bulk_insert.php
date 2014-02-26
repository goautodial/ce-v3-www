<?php
# admin_phones_bulk_insert.php
# 
# Copyright (C) 2012  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# this screen will insert phones into your multi-server system with aliases
#
# changes:
# 101230-0501 - First Build
# 110712-0932 - Added extension suffix exception for non-SIP/IAX phones, added HELP
# 120104-2023 - Added webphone options
# 120209-1545 - Added phone context option
# 120223-2249 - Removed logging of good login passwords if webroot writable is enabled
# 120820-1026 - Added webphone option Y_API_LAUNCH
#

$admin_version = '2.6-6';
$build = '120820-1026';


require("dbconnect.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["DB"]))							{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))				{$DB=$_POST["DB"];}
if (isset($_GET["action"]))						{$action=$_GET["action"];}
	elseif (isset($_POST["action"]))			{$action=$_POST["action"];}
if (isset($_GET["servers"]))					{$servers=$_GET["servers"];}
	elseif (isset($_POST["servers"]))			{$servers=$_POST["servers"];}
if (isset($_GET["phones"]))						{$phones=$_GET["phones"];}
	elseif (isset($_POST["phones"]))			{$phones=$_POST["phones"];}
if (isset($_GET["conf_secret"]))				{$conf_secret=$_GET["conf_secret"];}
	elseif (isset($_POST["conf_secret"]))		{$conf_secret=$_POST["conf_secret"];}
if (isset($_GET["pass"]))						{$pass=$_GET["pass"];}
	elseif (isset($_POST["pass"]))				{$pass=$_POST["pass"];}
if (isset($_GET["alias_option"]))				{$alias_option=$_GET["alias_option"];}
	elseif (isset($_POST["alias_option"]))		{$alias_option=$_POST["alias_option"];}
if (isset($_GET["protocol"]))					{$protocol=$_GET["protocol"];}
	elseif (isset($_POST["protocol"]))			{$protocol=$_POST["protocol"];}
if (isset($_GET["local_gmt"]))					{$local_gmt=$_GET["local_gmt"];}
	elseif (isset($_POST["local_gmt"]))			{$local_gmt=$_POST["local_gmt"];}
if (isset($_GET["alias_suffix"]))				{$alias_suffix=$_GET["alias_suffix"];}
	elseif (isset($_POST["alias_suffix"]))		{$alias_suffix=$_POST["alias_suffix"];}
if (isset($_GET["SUBMIT"]))						{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))			{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["is_webphone"]))				{$is_webphone=$_GET["is_webphone"];}
	elseif (isset($_POST["is_webphone"]))		{$is_webphone=$_POST["is_webphone"];}
if (isset($_GET["webphone_dialpad"]))			{$webphone_dialpad=$_GET["webphone_dialpad"];}
	elseif (isset($_POST["webphone_dialpad"]))	{$webphone_dialpad=$_POST["webphone_dialpad"];}
if (isset($_GET["webphone_auto_answer"]))			{$webphone_auto_answer=$_GET["webphone_auto_answer"];}
	elseif (isset($_POST["webphone_auto_answer"]))	{$webphone_auto_answer=$_POST["webphone_auto_answer"];}
if (isset($_GET["use_external_server_ip"]))			{$use_external_server_ip=$_GET["use_external_server_ip"];}
	elseif (isset($_POST["use_external_server_ip"])){$use_external_server_ip=$_POST["use_external_server_ip"];}
if (isset($_GET["phone_context"]))				{$phone_context=$_GET["phone_context"];}
	elseif (isset($_POST["phone_context"]))		{$phone_context=$_POST["phone_context"];}


if (strlen($action) < 2)
	{$action = 'BLANK';}
if (strlen($DB) < 1)
	{$DB=0;}


if ($non_latin < 1)
	{
	$PHP_AUTH_USER = ereg_replace("[^-_0-9a-zA-Z]","",$PHP_AUTH_USER);
	$PHP_AUTH_PW = ereg_replace("[^-_0-9a-zA-Z]","",$PHP_AUTH_PW);

	$servers = ereg_replace("'|\"|\\\\|;","",$servers);
	$phones = ereg_replace("'|\"|\\\\|;","",$phones);
	$action = ereg_replace("[^-_0-9a-zA-Z]","",$action);
	$conf_secret = ereg_replace("[^-_0-9a-zA-Z]","",$conf_secret);
	$pass = ereg_replace("[^-_0-9a-zA-Z]","",$pass);
	$alias_option = ereg_replace("[^-_0-9a-zA-Z]","",$alias_option);
	$alias_suffix = ereg_replace("[^0-9a-zA-Z]","",$alias_suffix);
	$protocol = ereg_replace("[^-_0-9a-zA-Z]","",$protocol);
	$local_gmt = ereg_replace("[^- \.\,\_0-9a-zA-Z]","",$local_gmt);
	$is_webphone = ereg_replace("[^-_0-9a-zA-Z]","",$is_webphone);
	$webphone_dialpad = ereg_replace("[^-_0-9a-zA-Z]","",$webphone_dialpad);
	$webphone_auto_answer = ereg_replace("[^NY]","",$webphone_auto_answer);
	$use_external_server_ip = ereg_replace("[^NY]","",$use_external_server_ip);
	$phone_context = ereg_replace("[^-\_0-9a-zA-Z]","",$phone_context);
	}	# end of non_latin
else
	{
	$PHP_AUTH_USER = ereg_replace("'|\"|\\\\|;","",$PHP_AUTH_USER);
	$PHP_AUTH_PW = ereg_replace("'|\"|\\\\|;","",$PHP_AUTH_PW);
	}

$STARTtime = date("U");
$TODAY = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,webroot_writable FROM system_settings;";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$ss_conf_ct = mysql_num_rows($rslt);
if ($ss_conf_ct > 0)
	{
	$row=mysql_fetch_row($rslt);
	$non_latin =					$row[0];
	$webroot_writable =				$row[1];
	}
##### END SETTINGS LOOKUP #####
###########################################


$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 7 and ast_delete_phones='1';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$auth=$row[0];

if ($webroot_writable > 0)
	{$fp = fopen ("./project_auth_entries.txt", "a");}

$date = date("r");
$ip = getenv("REMOTE_ADDR");
$browser = getenv("HTTP_USER_AGENT");
$user = $PHP_AUTH_USER;

if( (strlen($PHP_AUTH_USER)<2) or (strlen($PHP_AUTH_PW)<2) or (!$auth))
	{
    Header("WWW-Authenticate: Basic realm=\"VICI-PROJECTS\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo "Invalid Username/Password: |$PHP_AUTH_USER|$PHP_AUTH_PW|\n";
    exit;
	}
else
	{
	if ($auth>0)
		{
		$office_no=strtoupper($PHP_AUTH_USER);
		$password=strtoupper($PHP_AUTH_PW);
		$stmt="SELECT full_name,ast_delete_phones,ast_admin_access,user_level from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW'";
		$rslt=mysql_query($stmt, $link);
		$row=mysql_fetch_row($rslt);
		$LOGfullname =				$row[0];
		$LOGast_delete_phones =		$row[1];
		$LOGast_admin_access =		$row[2];
		$LOGuser_level =			$row[3];

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
		}
	}

?>
<html>
<head>

<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<title>ADMINISTRATION: Phones Bulk Insert
<?php 

################################################################################
##### BEGIN help section
if ($action == "HELP")
	{
	?>
	</title>
	</head>
	<body bgcolor=white>
	<center>
	<TABLE WIDTH=98% BGCOLOR=#E6E6E6 cellpadding=2 cellspacing=4><TR><TD ALIGN=LEFT><FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2>
	<BR>
	<B>Bulk Phones Insert Help</B>
	<BR><BR>

	<A NAME="servers">
	<BR>
	 <B>Servers -</B> This is a list of the server IP addresses of existing servers in the system that you want the phone entries to be populated across. These must be valid IP addresses, they must be active in the system and you must put one per line in this text area input box.
	<BR><BR>

	<A NAME="phones">
	<BR>
	 <B>Phones -</B> This is a list of the phone extensions that you want to be created on all of the servers listed above. The phone extensions should be letters and numbers only, with no special characters and you must put one per line in this text area box.
	<BR><BR>

	<A NAME="registration_password">
	<BR>
	 <B>Registration Password -</B> This is the registration password that will be used for all of the phones that are created. For SIP and IAX2 protocol you should use a complex password for secutiry reasons.
	<BR><BR>

	<A NAME="login_password">
	<BR>
	 <B>Login Password -</B> This is the agent screen login password that will be used for all of the phones that are created.
	<BR><BR>

	<A NAME="create_alias">
	<BR>
	 <B>Create Alias Entries -</B> Setting this option to YES will create a phones alias entry for each of the extensions listed above that will tie all of the same extension entries on each server together allowing phone login load balancing to work in the agent interface.
	<BR><BR>

	<A NAME="alias_suffix">
	<BR>
	 <B>Alias Suffix -</B> This is the suffix that will be added to the extension to form the phone alias id. For example, if the extension is cc100 and the alias suffix is x, then the phone alias id used for that would be cc100x.
	<BR><BR>

	<A NAME="protocol">
	<BR>
	 <B>Client Protocol -</B> This is the phone protocol that will be used for all phones created when submitted. SIP and IAX2 are VOIP protocols that will create conf file entries to allow those phoens to register on the servers.
	<BR><BR>

	<A NAME="gmt">
	<BR>
	 <B>Local GMT -</B> This is the time zone that all of the phones will be created with.
	<BR><BR>

	<A NAME="phone_context">
	<BR>
	<B>Phone Context -</B> This is the dial plan context that this phone will use to dial out. If you are running a call center and you do not want your agents to be able to dial out outside of the ViciDial applicaiton for example, then you would set this field to a dialplan context that does not exist, something like agent-nodial. default is default.
	<BR><BR>

	<BR>
	<A NAME="is_webphone">
	<BR>
	<B>Set As Webphone -</B>  Setting this option to Y will attempt to load a web-based phone when the agent logs into their agent screen. Default is N. The Y_API_LAUNCH option can be used with the agent API to launch the webphone in a separate Iframe or window.

	<BR>
	<A NAME="webphone_dialpad">
	<BR>
	<B>Webphone Dialpad -</B>  This setting allows you to activate or deactivate the dialpad for this webphone. Default is Y for enabled. TOGGLE will allow the user to view and hide the dialpad by clicking a link. This feature is not available on all webphone versions. TOGGLE_OFF will default to not show the dialpad on first load, but will allow the user to show the dialpad by clicking on the dialpad link.

	<BR>
	<A NAME="webphone_auto_answer">
	<BR>
	<B>Webphone Auto-Answer -</B>  This setting allows the web phone to be set to automatically answer calls that come in by setting it to Y, or to have calls ring by setting it to N. Default is Y.

	<BR>
	<A NAME="use_external_server_ip">
	<BR>
	<B>Use External Server IP -</B>  If using as a web phone, you can set this to Y to use the servers External IP to register to instead of the Server IP. Default is empty.


	</TD></TR></TABLE>
	</BODY>
	</HTML>
	<?php
	exit;
	}
### END help section



##### BEGIN Set variables to make header show properly #####
$ADD =					'999998';
$hh =					'admin';
$LOGast_admin_access =	'1';
$SSoutbound_autodial_active = '1';
$ADMIN =				'admin.php';
$page_width='770';
$section_width='750';
$header_font_size='3';
$subheader_font_size='2';
$subcamp_font_size='2';
$header_selected_bold='<b>';
$header_nonselected_bold='';
$admin_color =		'#FFFF99';
$admin_font =		'BLACK';
$admin_color =		'#E6E6E6';
$subcamp_color =	'#C6C6C6';
##### END Set variables to make header show properly #####

require("admin_header.php");

if ( ($LOGast_admin_access < 1) or ($LOGuser_level < 8) )
	{
	echo "You are not authorized to view this section\n";
	exit;
	}


if ($DB > 0)
{
echo "$DB,$action,$servers,$phones,$conf_secret,$pass,$alias_option,$protocol,$logal_gmt,$alias_suffix\n<BR>";
}

$NWB = " &nbsp; <a href=\"javascript:openNewWindow('$PHP_SELF?action=HELP";
$NWE = "')\"><IMG SRC=\"help.gif\" WIDTH=20 HEIGHT=20 BORDER=0 ALT=\"HELP\" ALIGN=TOP></A>";




################################################################################
##### BEGIN blank add phones form
if ($action == "BLANK")
	{
	echo "<TABLE><TR><TD>\n";
	echo "<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2>";
	echo "<br>Add Multi-Server Phones Form<form action=$PHP_SELF method=POST>\n";
	echo "<input type=hidden name=DB value=\"$DB\">\n";
	echo "<input type=hidden name=action value=ADD_PHONES_SUBMIT>\n";
	echo "<center><TABLE width=$section_width cellspacing=3>\n";
	echo "<tr bgcolor=#B6D3FC><td align=right>Servers: <BR><BR> (one server_ip per line only)<BR></td><td align=left><TEXTAREA name=servers ROWS=10 COLS=20></TEXTAREA> $NWB#servers$NWE </td></tr>\n";
	echo "<tr bgcolor=#B6D3FC><td align=right>Phones: <BR><BR> (one extension per line only)<BR></td><td align=left><TEXTAREA name=phones ROWS=20 COLS=20></TEXTAREA> $NWB#phones$NWE </td></tr>\n";
	echo "<tr bgcolor=#B6D3FC><td align=right>Registration Password: </td><td align=left><input type=text name=conf_secret size=20 maxlength=20> $NWB#registration_password$NWE </td></tr>\n";
	echo "<tr bgcolor=#B6D3FC><td align=right>Login Password: </td><td align=left><input type=text name=pass size=20 maxlength=20> $NWB#login_password$NWE </td></tr>\n";
	echo "<tr bgcolor=#B6D3FC><td align=right>Create Alias Entries: </td><td align=left><select size=1 name=alias_option>\n";
	echo "<option selected>YES</option>";
	echo "<option>NO</option>";
	echo "</select> $NWB#create_alias$NWE </td></tr>\n";
	echo "<tr bgcolor=#B6D3FC><td align=right>Alias Suffix: </td><td align=left><input type=text name=alias_suffix size=2 maxlength=4> $NWB#alias_suffix$NWE </td></tr>\n";
	echo "<tr bgcolor=#B6D3FC><td align=right>Client Protocol: </td><td align=left><select size=1 name=protocol><option>SIP</option><option>Zap</option><option>IAX2</option><option>EXTERNAL</option></select> $NWB#protocol$NWE </td></tr>\n";
	echo "<tr bgcolor=#B6D3FC><td align=right>Local GMT: </td><td align=left><select size=1 name=local_gmt><option>12.75</option><option>12.00</option><option>11.00</option><option>10.00</option><option>9.50</option><option>9.00</option><option>8.00</option><option>7.00</option><option>6.50</option><option>6.00</option><option>5.75</option><option>5.50</option><option>5.00</option><option>4.50</option><option>4.00</option><option>3.50</option><option>3.00</option><option>2.00</option><option>1.00</option><option>0.00</option><option>-1.00</option><option>-2.00</option><option>-3.00</option><option>-3.50</option><option>-4.00</option><option selected>-5.00</option><option>-6.00</option><option>-7.00</option><option>-8.00</option><option>-9.00</option><option>-10.00</option><option>-11.00</option><option>-12.00</option></select> (Do NOT Adjust for DST) $NWB#gmt$NWE </td></tr>\n";
	echo "<tr bgcolor=#B6D3FC><td align=right>Phone Context: </td><td align=left><input type=text name=phone_context size=20 maxlength=20> $NWB#phone_context$NWE </td></tr>\n";
	echo "<tr bgcolor=#B6D3FC><td align=right>Set As Webphone: </td><td align=left><select size=1 name=is_webphone><option>Y</option><option selected>N</option><option>Y_API_LAUNCH</option></select>$NWB#is_webphone$NWE</td></tr>\n";
	echo "<tr bgcolor=#B6D3FC><td align=right>Webphone Dialpad: </td><td align=left><select size=1 name=webphone_dialpad><option selected>Y</option><option>N</option><option>TOGGLE</option><option>TOGGLE_OFF</option></select>$NWB#webphone_dialpad$NWE</td></tr>\n";
	echo "<tr bgcolor=#B6D3FC><td align=right>Webphone Auto-Answer: </td><td align=left><select size=1 name=webphone_auto_answer><option selected>Y</option><option>N</option></select>$NWB#webphone_auto_answer$NWE</td></tr>\n";
	echo "<tr bgcolor=#B6D3FC><td align=right>Use External Server IP: </td><td align=left><select size=1 name=use_external_server_ip><option>Y</option><option selected>N</option></select>$NWB#use_external_server_ip$NWE</td></tr>\n";
	echo "<tr bgcolor=#B6D3FC><td align=center colspan=2><input type=submit name=SUBMIT value=SUBMIT></td></tr>\n";
	echo "<tr bgcolor=#B6D3FC><td align=center colspan=2>NOTE: Submitting this form will NOT trigger a conf file rebuild</td></tr>\n";
	echo "</TABLE></center>\n";
	echo "</TD></TR></TABLE>\n";
	}
### END blank add phones form




################################################################################
##### BEGIN add phones submit
if ($action == "ADD_PHONES_SUBMIT")
	{
	$phones_inserted=0;
	$phone_alias_inserted=0;
	if (strlen($phones) > 2)
		{
		$PN = explode("\n",$phones);
		$PNct = count($PN);

		if (strlen($servers) > 6)
			{
			$SN = explode("\n",$servers);
			$SNct = count($SN);

			$s=0;
			while ($s < $SNct)
				{
				$SN[$s] = preg_replace('/\n|\r|\t| /','',$SN[$s]);
				$server_exists=0;
				$stmt="SELECT count(*) from servers where server_ip='$SN[$s]';";
				if ($DB>0) {echo "$stmt";}
				$rslt=mysql_query($stmt, $link);
				$servercount_to_print = mysql_num_rows($rslt);
				if ($servercount_to_print > 0) 
					{
					$rowx=mysql_fetch_row($rslt);
					$server_exists =	$rowx[0];
					}
				if ($server_exists > 0)
					{
					$p=0;
					while ($p < $PNct)
						{
						$PN[$p] = preg_replace('/\n|\r|\t| /','',$PN[$p]);
						$phone_exists=0;
						$stmt="SELECT count(*) from phones where server_ip='$SN[$s]' and extension='$PN[$p]';";
						if ($DB>0) {echo "$stmt";}
						$rslt=mysql_query($stmt, $link);
						$phonecount_to_print = mysql_num_rows($rslt);
						if ($phonecount_to_print > 0) 
							{
							$rowx=mysql_fetch_row($rslt);
							$phone_exists =	$rowx[0];
							}
						if ( ($phone_exists < 1) and (strlen($PN[$p]) > 1) )
							{
							if ($s < 1) {$dialplan_prefix='';   $login_suffix = 'a';}
							else {$dialplan_prefix = $s;}
							if ($s == '1') {$login_suffix = 'b';}
							if ($s == '2') {$login_suffix = 'c';}
							if ($s == '3') {$login_suffix = 'd';}
							if ($s == '4') {$login_suffix = 'e';}
							if ($s == '5') {$login_suffix = 'f';}
							if ($s == '6') {$login_suffix = 'g';}
							if ($s == '7') {$login_suffix = 'h';}
							if ($s == '8') {$login_suffix = 'i';}
							if ($s == '9') {$login_suffix = 'j';}
							if ($s == '10') {$login_suffix = 'k';}
							if ($s == '11') {$login_suffix = 'l';}
							if ($s == '12') {$login_suffix = 'm';}
							if ($s == '13') {$login_suffix = 'n';}
							if ($s == '14') {$login_suffix = 'o';}
							if ($s == '15') {$login_suffix = 'p';}
							if ($s == '16') {$login_suffix = 'q';}
							if ($s == '17') {$login_suffix = 'r';}
							if ($s == '18') {$login_suffix = 's';}
							if ($s == '19') {$login_suffix = 't';}
							if ($s == '20') {$login_suffix = 'u';}
							if ($s == '21') {$login_suffix = 'v';}
							if ($s == '22') {$login_suffix = 'w';}
							if ($s == '23') {$login_suffix = 'x';}
							if ($s == '24') {$login_suffix = 'y';}
							if ($s == '25') {$login_suffix = 'z';}
							if ($s == '26') {$login_suffix = 'aa';}
							if ($s == '27') {$login_suffix = 'ab';}
							if ($s == '28') {$login_suffix = 'ac';}
							if ($s == '29') {$login_suffix = 'ad';}
							if ($s == '30') {$login_suffix = 'ae';}
							if ($s == '31') {$login_suffix = 'af';}
							if ($s == '32') {$login_suffix = 'ag';}
							if ($s == '33') {$login_suffix = 'ah';}
							if ($s == '34') {$login_suffix = 'ai';}
							if ($s == '35') {$login_suffix = 'aj';}
							if ($s == '36') {$login_suffix = 'ak';}
							if ($s == '37') {$login_suffix = 'al';}
							if ($s == '38') {$login_suffix = 'am';}
							if ($s == '39') {$login_suffix = 'an';}
							if ($s == '40') {$login_suffix = 'ao';}
							if ($s == '41') {$login_suffix = 'ap';}
							if ($s == '42') {$login_suffix = 'aq';}
							if ($s == '43') {$login_suffix = 'ar';}
							if ($s == '44') {$login_suffix = 'as';}
							if ($s == '45') {$login_suffix = 'at';}
							if ($s == '46') {$login_suffix = 'au';}
							if ($s == '47') {$login_suffix = 'av';}
							if ($s == '48') {$login_suffix = 'aw';}
							if ($s >= 49) {$login_suffix = 'ax';}

							$extension =		$PN[$p];
							if ( ($protocol == 'SIP') or ($protocol == 'IAX2') )
								{$dialplan_number =	"$dialplan_prefix$PN[$p]";}
							else
								{$dialplan_number =	"$PN[$p]";}
							if (strlen($phone_context) < 1)
								{$phone_context = 'default';}
							$dialplan_number = preg_replace('/\D/', '', $dialplan_number);
							$voicemail_id =		$PN[$p];	$voicemail_id = preg_replace('/\D/', '', $voicemail_id);
							$phone_server_ip =	$SN[$s];
							$login =			"$PN[$p]$login_suffix";
							$phone_type =		"CCagent";
							$fullname =			"ext $PN[$p]";

							$stmt = "INSERT INTO phones (extension,dialplan_number,voicemail_id,server_ip,login,pass,status,active,phone_type,fullname,protocol,local_gmt,outbound_cid,conf_secret,is_webphone,webphone_dialpad,webphone_auto_answer,use_external_server_ip,phone_context) values('$extension','$dialplan_number','$voicemail_id','$phone_server_ip','$login','$pass','ACTIVE','Y','$phone_type','$fullname','$protocol','$local_gmt','0000000000','$conf_secret','$is_webphone','$webphone_dialpad','$webphone_auto_answer','$use_external_server_ip','$phone_context');";
							$rslt=mysql_query($stmt, $link);
							$affected_rows = mysql_affected_rows($link);
							if ($DB > 0) {echo "$s|$p|$SN[$s]|$PN[$p]|$affected_rows|$stmt\n<BR>";}

							if ($affected_rows > 0)
								{
								$phone_alias_entry[$p] .= "$login,";

								### LOG INSERTION Admin Log Table ###
								$SQL_log = "$stmt|";
								$SQL_log = ereg_replace(';','',$SQL_log);
								$SQL_log = addslashes($SQL_log);
								$stmt="INSERT INTO vicidial_admin_log set event_date='$SQLdate', user='$PHP_AUTH_USER', ip_address='$ip', event_section='PHONES', event_type='ADD', record_id='$PN[$p]', event_code='ADMIN BULK ADD PHONE', event_sql=\"$SQL_log\", event_notes='$SN[$s]|$PN[$p]';";
							#	if ($DB) {echo "|$stmt|\n";}
								$rslt=mysql_query($stmt, $link);

								$phones_inserted++;
								}
							else
								{echo "ERROR: Problem inserting phone:  $affected_rows|$stmt\n<BR>";}
							}
						else
							{echo "ERROR: Phone already exists:  $SN[$s]|$PN[$p]|$phone_exists\n<BR>";}
						$p++;
						}
					}
				else
					{echo "ERROR: Server does not exist: $SN[$s]|$server_exists\n<BR>";}
				$s++;
				}

			if ( ($phones_inserted > 0) and ($alias_option == 'YES') )
				{
				$p=0;
				while ($p < $PNct)
					{
					if ( (strlen($phone_alias_entry[$p]) > 1) and (strlen($PN[$p]) > 1) )
						{
						$phone_alias_entry[$p] = preg_replace('/,$/','',$phone_alias_entry[$p]);

						$stmt="INSERT INTO phones_alias (alias_id,alias_name,logins_list) values('$PN[$p]$alias_suffix','$PN[$p]','$phone_alias_entry[$p]');";
						$rslt=mysql_query($stmt, $link);
						$affected_rows = mysql_affected_rows($link);
						if ($DB > 0) {echo "$p|$phone_alias_entry[$p]|$PN[$p]|$affected_rows|$stmt\n<BR>";}

						if ($affected_rows > 0)
							{
							### LOG INSERTION Admin Log Table ###
							$SQL_log = "$stmt|";
							$SQL_log = ereg_replace(';','',$SQL_log);
							$SQL_log = addslashes($SQL_log);
							$stmt="INSERT INTO vicidial_admin_log set event_date='$SQLdate', user='$PHP_AUTH_USER', ip_address='$ip', event_section='PHONEALIASES', event_type='ADD', record_id='$alias_id', event_code='ADMIN ADD BULK PHONE ALIAS', event_sql=\"$SQL_log\", event_notes='';";
						#	if ($DB) {echo "|$stmt|\n";}
							$rslt=mysql_query($stmt, $link);

							$phone_alias_inserted++;
							}
						else
							{echo "ERROR: Problem inserting phone alias:  $affected_rows|$stmt\n<BR>";}
						}
					$p++;
					}
				}
			
			echo "Phones Inserted: $phones_inserted\n<BR>";
			echo "Phones Aliases Inserted: $phone_alias_inserted\n<BR>";
			echo "You now need to manually trigger a conf file rebuild from the System Settings screen\n<BR>";
			echo "<BR><a href=\"$PHP_SELF\">Start Over</a><BR>\n";
			}
		else
			{echo "ERROR: You must enter servers: $servers\n<BR>";}
		}
	else
		{echo "ERROR: You must enter extensions: $phones\n<BR>";}
	}
### END add phones submit







$ENDtime = date("U");
$RUNtime = ($ENDtime - $STARTtime);
echo "\n\n\n<br><br><br>\n<font size=1> runtime: $RUNtime seconds &nbsp; &nbsp; &nbsp; &nbsp; Version: $admin_version &nbsp; &nbsp; Build: $build</font>";

?>

</body>
</html>
