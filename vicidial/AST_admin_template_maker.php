<?php
# AST_admin_template_maker.php - version 2.4
# 
# Copyright (C) 2012  Matt Florell,Joe Johnson <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES
# 120402-2132 - First Build
# 120529-1427 - Filename filter fix
#

require("dbconnect.php");

if (isset($_GET["DB"]))					{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))		{$DB=$_POST["DB"];}
if (isset($_GET["standard_fields_layout"]))				{$standard_fields_layout=$_GET["standard_fields_layout"];}
	elseif (isset($_POST["standard_fields_layout"]))		{$standard_fields_layout=$_POST["standard_fields_layout"];}
if (isset($_GET["custom_fields_layout"]))				{$custom_fields_layout=$_GET["custom_fields_layout"];}
	elseif (isset($_POST["custom_fields_layout"]))		{$custom_fields_layout=$_POST["custom_fields_layout"];}
if (isset($_GET["template_id"]))				{$template_id=$_GET["template_id"];}
	elseif (isset($_POST["template_id"]))		{$template_id=$_POST["template_id"];}
if (isset($_GET["template_name"]))				{$template_name=$_GET["template_name"];}
	elseif (isset($_POST["template_name"]))		{$template_name=$_POST["template_name"];}
if (isset($_GET["template_description"]))				{$template_description=$_GET["template_description"];}
	elseif (isset($_POST["template_description"]))		{$template_description=$_POST["template_description"];}
if (isset($_GET["file_format"]))				{$file_format=$_GET["file_format"];}
	elseif (isset($_POST["file_format"]))		{$file_format=$_POST["file_format"];}
if (isset($_GET["file_delimiter"]))				{$file_delimiter=$_GET["file_delimiter"];}
	elseif (isset($_POST["file_delimiter"]))		{$file_delimiter=$_POST["file_delimiter"];}
if (isset($_GET["template_list_id"]))				{$template_list_id=$_GET["template_list_id"];}
	elseif (isset($_POST["template_list_id"]))		{$template_list_id=$_POST["template_list_id"];}
if (isset($_GET["standard_fields_layout"]))				{$standard_fields_layout=$_GET["standard_fields_layout"];}
	elseif (isset($_POST["standard_fields_layout"]))		{$standard_fields_layout=$_POST["standard_fields_layout"];}
if (isset($_GET["custom_fields_layout"]))				{$custom_fields_layout=$_GET["custom_fields_layout"];}
	elseif (isset($_POST["custom_fields_layout"]))		{$custom_fields_layout=$_POST["custom_fields_layout"];}
if (isset($_GET["submit_template"]))				{$submit_template=$_GET["submit_template"];}
	elseif (isset($_POST["submit_template"]))		{$submit_template=$_POST["submit_template"];}
if (isset($_GET["delete_template"]))				{$delete_template=$_GET["delete_template"];}
	elseif (isset($_POST["delete_template"]))		{$delete_template=$_POST["delete_template"];}

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];

#$vicidial_list_fields = '|lead_id|vendor_lead_code|source_id|list_id|gmt_offset_now|called_since_last_reset|phone_code|phone_number|title|first_name|middle_initial|last_name|address1|address2|address3|city|state|province|postal_code|country_code|gender|date_of_birth|alt_phone|email|security_phrase|comments|called_count|last_local_call_time|rank|owner|entry_list_id|';
$vicidial_listloader_fields = '|vendor_lead_code|source_id|phone_code|phone_number|title|first_name|middle_initial|last_name|address1|address2|address3|city|state|province|postal_code|country_code|gender|date_of_birth|alt_phone|email|security_phrase|comments|rank|owner|';

if ($submit_template=="SUBMIT TEMPLATE" && $template_id && $template_name && $template_list_id && $standard_fields_layout) {
	$custom_table="custom_".$template_list_id;
	$ins_stmt="insert into vicidial_custom_leadloader_templates(template_id, template_name, template_description, list_id, standard_variables, custom_table, custom_variables) values('$template_id', '$template_name', '$template_description', '$template_list_id', '$standard_fields_layout', '$custom_table', '$custom_fields_layout')";
	$ins_rslt=mysql_query($ins_stmt, $link);
	if (mysql_affected_rows()>0) {
		$success_msg="NEW TEMPLATE CREATED SUCCESSFULLY";
		if (!$custom_fields_layout) {
			$success_msg.="<BR/>**NO CUSTOM FIELDS ASSIGNED**";
		}
	} else {
		$error_msg="TEMPLATE CREATION FAILED";
	}
} else if ($delete_template=="DELETE TEMPLATE" && $template_id) {
	$delete_stmt="delete from vicidial_custom_leadloader_templates where template_id='$template_id'";
	$delete_rslt=mysql_query($delete_stmt, $link);
}

$US='_';
#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,admin_web_directory,custom_fields_enabled,webroot_writable FROM system_settings;";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$qm_conf_ct = mysql_num_rows($rslt);
if ($qm_conf_ct > 0)
	{
	$row=mysql_fetch_row($rslt);
	$non_latin =				$row[0];
	$admin_web_directory =		$row[1];
	$custom_fields_enabled =	$row[2];
	$webroot_writable =			$row[3];
	}
##### END SETTINGS LOOKUP #####
###########################################

if ($non_latin < 1)
	{
	$PHP_AUTH_USER = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_USER);
	$PHP_AUTH_PW = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_PW);
	$list_id_override = ereg_replace("[^0-9]","",$list_id_override);
	}
else
	{
	$PHP_AUTH_PW = ereg_replace("'|\"|\\\\|;","",$PHP_AUTH_PW);
	$PHP_AUTH_USER = ereg_replace("'|\"|\\\\|;","",$PHP_AUTH_USER);
	}

$STARTtime = date("U");
$TODAY = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");
$FILE_datetime = $STARTtime;

$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 7;";
if ($DB) {echo "|$stmt|\n";}
if ($non_latin > 0) {$rslt=mysql_query("SET NAMES 'UTF8'");}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$auth=$row[0];

if ($webroot_writable > 0) {$fp = fopen ("./project_auth_entries.txt", "a");}
$date = date("r");
$ip = getenv("REMOTE_ADDR");
$browser = getenv("HTTP_USER_AGENT");

if( (strlen($PHP_AUTH_USER)<2) or (strlen($PHP_AUTH_PW)<2) or (!$auth))
	{
#    Header("WWW-Authenticate: Basic realm=\"VICIDIAL-LEAD-LOADER\"");
 #   Header("HTTP/1.0 401 Unauthorized");
  #  echo "Invalid Username/Password: |$PHP_AUTH_USER|$PHP_AUTH_PW|\n";
    exit;
	}
else
	{
	header ("Content-type: text/html; charset=utf-8");
	header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
	header ("Pragma: no-cache");                          // HTTP/1.0

	if($auth>0)
		{
		$office_no=strtoupper($PHP_AUTH_USER);
		$password=strtoupper($PHP_AUTH_PW);
		$stmt="SELECT load_leads,user_group from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW'";
		$rslt=mysql_query($stmt, $link);
		$row=mysql_fetch_row($rslt);
		$LOGload_leads =	$row[0];
		$LOGuser_group =	$row[1];

		if ($LOGload_leads < 1)
			{
			echo "You do not have permissions to load leads\n";
			exit;
			}
		if ($webroot_writable > 0) 
			{
			fwrite ($fp, "LIST_LOAD|GOOD|$date|$PHP_AUTH_USER|XXXX|$ip|$browser|$LOGfullname|\n");
			fclose($fp);
			}
		}
	else
		{
		if ($webroot_writable > 0) 
			{
			fwrite ($fp, "LIST_LOAD|FAIL|$date|$PHP_AUTH_USER|XXXX|$ip|$browser|\n");
			fclose($fp);
			}
		exit;
		}
	
	}

$stmt="SELECT allowed_campaigns,allowed_reports,admin_viewable_groups,admin_viewable_call_times from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_query($stmt, $link);
$row=mysql_fetch_row($rslt);
$LOGallowed_campaigns =			$row[0];
$LOGallowed_reports =			$row[1];
$LOGadmin_viewable_groups =		$row[2];
$LOGadmin_viewable_call_times =	$row[3];

$camp_lists='';
$LOGallowed_campaignsSQL='';
$whereLOGallowed_campaignsSQL='';
if (!eregi("-ALL",$LOGallowed_campaigns))
	{
		echo "<BR/>**$LOGallowed_campaigns**";
	$rawLOGallowed_campaignsSQL = preg_replace("/ -/",'',$LOGallowed_campaigns);
	$rawLOGallowed_campaignsSQL = preg_replace("/ /","','",$rawLOGallowed_campaignsSQL);
	    echo "<BR/>##$rawLOGallowed_campaignsSQL##";
	$LOGallowed_campaignsSQL = "and campaign_id IN('$rawLOGallowed_campaignsSQL')";
	$whereLOGallowed_campaignsSQL = "where campaign_id IN('$rawLOGallowed_campaignsSQL')";
	}
$regexLOGallowed_campaigns = " $LOGallowed_campaigns ";

$script_name = getenv("SCRIPT_NAME");
$server_name = getenv("SERVER_NAME");
$server_port = getenv("SERVER_PORT");
if (eregi("443",$server_port)) {$HTTPprotocol = 'https://';}
	else {$HTTPprotocol = 'http://';}
$admDIR = "$HTTPprotocol$server_name$script_name";
$admDIR = eregi_replace('AST_admin_template_maker.php','',$admDIR);
$admDIR = "/vicidial/";
$admSCR = 'admin.php';
$NWB = " &nbsp; <a href=\"javascript:openNewWindow('$admDIR$admSCR?ADD=99999";
$NWE = "')\"><IMG SRC=\"help.gif\" WIDTH=20 HEIGHT=20 BORDER=0 ALT=\"HELP\" ALIGN=TOP></A>";

?>
<html>
<head>
</head>
<script language="Javascript">
var form_file_name='';

function init() {
	document.getElementById("listloader_file_primer").onsubmit=function() {
		document.getElementById("listloader_file_primer").target = "file_holder";
		alert(document.getElementById("listloader_file_primer").target);
	}
}
function PrimeFile() {
	document.forms[0].submit();
}
function DisplayTemplateFields(list_id) {
	if (list_id!='') {var custom_fields_enabled=1;} else {var custom_fields_enabled=0;}
	var template_file_type=document.getElementById("template_file_type").value;
	var delimiter=document.getElementById("template_file_delimiter").value;
	var buffer=document.getElementById("template_file_buffer").value;

		var endstr=document.forms[0].sample_template_file.value.lastIndexOf('\\');
		if (endstr>-1) 
			{
			endstr++;
			var filename=document.forms[0].sample_template_file.value.substring(endstr);
			}
	
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	if (xmlhttp) { 
		var vs_query = "&custom_fields_enabled="+custom_fields_enabled+"&list_id="+list_id+"&delimiter="+delimiter+"&buffer="+buffer+"&sample_template_file_name="+form_file_name;
		xmlhttp.open('POST', 'leadloader_template_display.php'); 
		xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
		xmlhttp.send(vs_query); 
		xmlhttp.onreadystatechange = function() { 
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var StatSpanText = null;
				StatSpanText = xmlhttp.responseText;
				document.getElementById("field_display").innerHTML = StatSpanText;
			}
		}
		delete xmlhttp;
	}
}
function DrawTemplateStrings() {
	var vicidial_string="<?php echo $vicidial_listloader_fields; ?>";
    var standard_string = '';
    var custom_string = '';
    for (var i = 0; i<document.listloader_template_form.elements.length; i++) {
		if ((document.listloader_template_form.elements[i].type == 'select-one') && document.listloader_template_form.elements[i].name!="template_list_id") {
			var field_name=document.listloader_template_form.elements[i].name.replace("_field", "");
			var ptn="/\|"+field_name+"\|/gi";
			if (document.listloader_template_form.elements[i].selectedIndex != 0) {
				if (vicidial_string.match(ptn)) {
					standard_string += field_name +","+ document.listloader_template_form.elements[i].options[document.listloader_template_form.elements[i].selectedIndex].value + '|';
				} else {
					custom_string += field_name +","+ document.listloader_template_form.elements[i].options[document.listloader_template_form.elements[i].selectedIndex].value + '|';
				}
			}
		}
	}
	document.getElementById("standard_fields_layout").value=standard_string;
	document.getElementById("custom_fields_layout").value=custom_string;
}
function loadIFrame(form_action, field_value) {
	form_file_name = field_value;
	if (field_value=="") {
		document.getElementById('list_data_display').style.display = 'none'; 
		document.getElementById('list_data_display').style.visibility = 'hidden';
	} else {
		document.getElementById('list_data_display').style.display = 'block'; 
		document.getElementById('list_data_display').style.visibility = 'visible';
	}
	document.forms[0].action="leadloader_template_display.php?form_action="+form_action;
}
function checkForm(form_name) {
	var error_msg="The form cannot be submitted for the following reasons: \n";
	if (form_name.template_id.value=="") {error_msg+=" - Template ID is missing\n";}
	else if (form_name.template_id.value.length<2) {error_msg+=" - Template ID needs to be at least 2 characters long<\n";}
	if (form_name.template_name.value=="") {error_msg+=" - Template name is missing\n";}
	if (form_name.template_list_id.value=="") {error_msg+=" - List ID is missing\n";}
	if (form_name.standard_fields_layout.value=="" && form_name.custom_fields_layout.value=="") {error_msg+=" - There does not seem to be a layout\n";}
	if (error_msg.length>=80) {alert(error_msg); return false;} else {return true;}
}
</script>
<?php

function macfontfix($fontsize) 
	{
	$browser = getenv("HTTP_USER_AGENT");
	$pctype = explode("(", $browser);
	if (ereg("Mac",$pctype[1])) 
		{
		/* Browser is a Mac.  If not Netscape 6, raise fonts */
		$blownbrowser = explode('/', $browser);
		$ver = explode(' ', $blownbrowser[1]);
		$ver = $ver[0];
		if ($ver >= 5.0) return $fontsize; else return ($fontsize+2);
		} 
	else return $fontsize;	/* Browser is not a Mac - don't touch fonts */
	}

echo "<style type=\"text/css\">\n
<!--\n
.title {  font-family: Arial, Helvetica, sans-serif; font-size: ".macfontfix(18)."pt}\n
.standard {  font-family: Arial, Helvetica, sans-serif; font-size: ".macfontfix(10)."pt}\n
.small_standard {  font-family: Arial, Helvetica, sans-serif; font-size: ".macfontfix(8)."pt}\n
.tiny_standard {  font-family: Arial, Helvetica, sans-serif; font-size: ".macfontfix(6)."pt}\n
.standard_bold {  font-family: Arial, Helvetica, sans-serif; font-size: ".macfontfix(10)."pt; font-weight: bold}\n
.standard_header {  font-family: Arial, Helvetica, sans-serif; font-size: ".macfontfix(14)."pt; font-weight: bold}\n
.standard_bold_highlight {  font-family: Arial, Helvetica, sans-serif; font-size: ".macfontfix(10)."pt; font-weight: bold; color: white; BACKGROUND-COLOR: black}\n
.standard_bold_blue_highlight {  font-family: Arial, Helvetica, sans-serif; font-size: 10pt; font-weight: bold; BACKGROUND-COLOR: blue}\n
A.employee_standard {  font-family: garamond, sans-serif; font-size: ".macfontfix(10)."pt; font-style: normal; font-variant: normal; font-weight: bold; text-decoration: none}\n
.employee_standard {  font-family: garamond, sans-serif; font-size: ".macfontfix(10)."pt; font-weight: bold}\n
.employee_title {  font-family: Garamond, sans-serif; font-size: ".macfontfix(14)."pt; font-weight: bold}\n
\\\\-->\n
</style>\n";
?>
<script language="JavaScript1.2">
function openNewWindow(url) 
	{
	window.open (url,"",'width=700,height=300,scrollbars=yes,menubar=yes,address=yes');
	}
</script>
<body BGCOLOR=WHITE marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>
<?php
$short_header=1;
require("admin_header.php");
?>
<BR/>
<table border="2" bordercolor="#000099" cellpadding="10" width="800" align="left">
<tr height="20"><th bgcolor="#000099"><font class="standard_bold" color="#FFFFFF">Listloader Custom Template Maker<?php echo "$NWB#vicidial_template_maker$NWE"; ?></font></th></tr>
<tr><td align="center" bgcolor="#CCFFFF">
<table border=0 cellpadding=15 cellspacing=0 width="90%" align="center" bgcolor="#D9E6FE">
<?php
if ($error_msg) {
	echo "<tr bgcolor='#990000'>";
	echo "<th colspan='2'><font color='#FFFFFF'>$error_msg</font></th>";
	echo "</tr>";
}
if ($success_msg) {
	echo "<tr bgcolor='#009900'>";
	echo "<th colspan='2'><font color='#FFFFFF'>$success_msg</font></th>";
	echo "</tr>";
}
?>
	<tr>
		<th width="50%"><font class="standard_bold">Create a new template</font></th>
		<th width="50%"><font class="standard_bold">Delete an existing template</font></th>
	<tr valign="top">
		<td align="left" width='50%'>
		<form id="listloader_file_primer" action="leadloader_template_display.php?form_action=prime_file" method="post" enctype="multipart/form-data" target="file_holder">
			<font class="standard">Sample file fitting template:<BR><font size="-2">(needed for field assignation)</font></font><?php echo "$NWB#vicidial_template_maker-create_template$NWE"; ?><BR><BR><input type=file name="sample_template_file" value="<?php echo $sample_template_file; ?>" onChange="loadIFrame('prime_file', this.value); this.form.submit();">
		</form>
		</td>
		<td align="left" width='50%'><form action="<?php echo $PHP_SELF; ?>" method="post"><font class="standard">Select template to delete:</font><?php echo "$NWB#vicidial_template_maker-delete_template$NWE"; ?><BR><select name="template_id" onChange="loadIFrame('hide_new_template_form', '')">
<?php
$template_stmt="select template_id, template_name from vicidial_custom_leadloader_templates order by template_id asc";
$template_rslt=mysql_query($template_stmt, $link);
if (mysql_num_rows($template_rslt)>0) {
	if ($update_template) {echo "<option value='$update_template' selected>$update_template</option>\n";} else {echo "<option value='' selected>--Choose an existing template--</option>\n";}
	while ($template_row=mysql_fetch_array($template_rslt)) {
		echo "<option value='$template_row[template_id]'>$template_row[template_id] - $template_row[template_name]</option>\n";
	}
} else {
	echo "<option value='' selected>--No templates exist--</option>\n";
}
?>
		</select><BR/><BR/><input type="submit" value="DELETE TEMPLATE" class="red_btn" name="delete_template">
		</form></td>
	</tr>
	<tr>
		<td align=left colspan="2"><font size=1> &nbsp; &nbsp; &nbsp; &nbsp; <a href="admin.php?ADD=100" target="_parent">BACK TO ADMIN</a> &nbsp; &nbsp; &nbsp; &nbsp; <a href="./admin_listloader_fourth_gen.php">Go to Lead Loader</a> &nbsp; &nbsp; </font></td>
	</tr>
	</tr>
</table>
</form>
<BR/>
<iframe id="file_holder" style="visibility:hidden;display:none" name="file_holder"></iframe>
<span id="list_data_display" style="visibility:hidden;display:none">
<form id="listloader_template_form" name="listloader_template_form" action="<?php echo $PHP_SELF; ?>" method="post" enctype="multipart/form-data">
<table border=0 cellpadding=3 cellspacing=0 width="100%" align="center">
	<tr>
		<th colspan="2" bgcolor="#330099"><font class="standard" color="white">New template form</font></th>
	</tr>
	<tr bgcolor="#D9E6FE">
		<td align="right" width='25%'><font class="standard">Template ID:</font></td>
		<td align="left" width='75%'><input type='text' name='template_id' size='15' maxlength='20'><?php echo "$NWB#vicidial_template_maker-template_id$NWE"; ?></td>
	</tr>
	<tr bgcolor="#D9E6FE">
		<td align="right" width='25%'><font class="standard">Template Name:</font></td>
		<td align="left" width='75%'><input type='text' name='template_name' size='15' maxlength='30'><?php echo "$NWB#vicidial_template_maker-template_name$NWE"; ?></td>
	</tr>
	<tr bgcolor="#D9E6FE">
		<td align="right" width='25%'><font class="standard">Template Description:</font></td>
		<td align="left" width='75%'><input type='text' name='template_desc' size='50' maxlength='255'><?php echo "$NWB#vicidial_template_maker-template_description$NWE"; ?></td>
	</tr>
	<tr bgcolor="#D9E6FE">
		<td width='25%' align="right"><font class="standard">List ID template will load into:</font></td>
		<td width='75%'>
			<select id='template_list_id' name='template_list_id' onChange="DisplayTemplateFields(this.value)">
			<option value=''>--Select a list below--</option>
			<?php
			$stmt="SELECT list_id, list_name from vicidial_lists $whereLOGallowed_campaignsSQL order by list_id;";
			$rslt=mysql_query($stmt, $link);
			$num_rows = mysql_num_rows($rslt);

			$count=0;
			while ( $num_rows > $count ) 
				{
				$row = mysql_fetch_row($rslt);
				echo "\t\t\t<option value='$row[0]'>$row[0] - $row[1]</option>\n";
				$count++;
				}
			?>
			</select></font><?php echo "$NWB#vicidial_template_maker-list_id$NWE"; ?>
		</td>
	</tr>
	<tr bgcolor="#D9E6FE">
		<th colspan='2'><input type='submit' name='submit_template' onClick="return checkForm(this.form)" value='SUBMIT TEMPLATE'></th>
	</tr>
<tr bgcolor="#D9E6FE"><td align="center" colspan=2>
<table border=0 cellpadding=3 cellspacing=1 width="100%" align="center">
	<tr>
		<th colspan="2" bgcolor="#330099"><font class="standard" color="white">Assign columns to file fields<BR/><font size='-2'>(selecting a different list will reset columns)</font></font><?php echo "$NWB#vicidial_template_maker-assign_columns$NWE"; ?></th>
	</tr>
	<tr valign="top">
		<td border="1" align="center" bgcolor="#D9E6FE" width="50%"><font class="standard">Standard Field</font></td>
		<td border="0" align="center" bgcolor="#FED9D9" width="50%"><font class="standard">Custom Field</font></td>
	</tr>
	<tr valign="top">
		<td colspan="2" align="center">
		<span id="field_display" name="field_display"><font class="standard_bold" color='red'>**Select a list ID from the drop down menu above to show columns**</font></span>
		</td>
	</tr>
</table>
</tr></td>
</table>
<input type="hidden" id="template_file_type" name="template_file_type">
<input type="hidden" id="template_file_delimiter" name="template_file_delimiter">
<input type="hidden" id="template_file_buffer" name="template_file_buffer">
<input type="hidden" id="standard_fields_layout" name="standard_fields_layout">
<input type="hidden" id="custom_fields_layout" name="custom_fields_layout">
</form>
</span>
</body>
</html>