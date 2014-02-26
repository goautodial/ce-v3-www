<?php
# leadloader_template_display.php - version 2.4
# 
# Copyright (C) 2012  Matt Florell,Joe Johnson <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGES
# 120402-2238 - First Build
# 120525-1039 - Added uploaded filename filtering
# 120529-1345 - Filename filter fix
#

require("dbconnect.php");

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

if (isset($_GET["list_id"]))				{$list_id=$_GET["list_id"];}
	elseif (isset($_POST["list_id"]))		{$list_id=$_POST["list_id"];}
if (isset($_GET["custom_fields_enabled"]))				{$custom_fields_enabled=$_GET["custom_fields_enabled"];}
	elseif (isset($_POST["custom_fields_enabled"]))		{$custom_fields_enabled=$_POST["custom_fields_enabled"];}
$sample_template_file=$_FILES["sample_template_file"];
$LF_orig = $_FILES['sample_template_file']['name'];
$LF_path = $_FILES['sample_template_file']['tmp_name'];
if (isset($_GET["sample_template_file_name"]))			{$sample_template_file_name=$_GET["sample_template_file_name"];}
	elseif (isset($_POST["sample_template_file_name"]))	{$sample_template_file_name=$_POST["sample_template_file_name"];}
if (isset($_FILES["sample_template_file"]))				{$sample_template_file_name=$_FILES["sample_template_file"]['name'];}
if (isset($_GET["form_action"]))				{$form_action=$_GET["form_action"];}
	elseif (isset($_POST["form_action"]))		{$form_action=$_POST["form_action"];}
if (isset($_GET["delimiter"]))				{$delimiter=$_GET["delimiter"];}
	elseif (isset($_POST["delimiter"]))		{$delimiter=$_POST["delimiter"];}
if (isset($_GET["buffer"]))				{$buffer=$_GET["buffer"];}
	elseif (isset($_POST["buffer"]))		{$buffer=$_POST["buffer"];}

### REGEX to prevent weird characters from ending up in the fields
$field_regx = "['\"`\\;]";
if ( (preg_match("/;|:|\/|\^|\[|\]|\"|\'|\*/",$LF_orig)) or (preg_match("/;|:|\/|\^|\[|\]|\"|\'|\*/",$sample_template_file_name)) )
	{
	echo "ERROR: Invalid File Name: $LF_orig $sample_template_file_name\n";
	exit;
	}

if ($form_action=="prime_file" && $sample_template_file_name) 
	{
	$delim_set=0;
	if (preg_match("/\.csv$|\.xls$|\.xlsx$|\.ods$|\.sxc$/i", $sample_template_file_name)) 
		{
		$sample_template_file_name = ereg_replace("[^-\.\_0-9a-zA-Z]","_",$sample_template_file_name);
		copy($LF_path, "/tmp/$sample_template_file_name");
		$new_filename = preg_replace("/\.csv$|\.xls$|\.xlsx$|\.ods$|\.sxc$/i", '.txt', $sample_template_file_name);
		$convert_command = "$WeBServeRRooT/$admin_web_directory/sheet2tab.pl /tmp/$sample_template_file_name /tmp/$new_filename";
		passthru("$convert_command");
		$lead_file = "/tmp/$new_filename";
		if ($DB > 0) {echo "|$convert_command|";}

		if (preg_match("/\.csv$/i", $sample_template_file_name)) {$delim_name="CSV: Comma Separated Values"; $template_file_type="CSV";}
		if (preg_match("/\.xls$/i", $sample_template_file_name)) {$delim_name="XLS: MS Excel 2000-XP"; $template_file_type="XLS";}
		if (preg_match("/\.xlsx$/i", $sample_template_file_name)) {$delim_name="XLSX: MS Excel 2007+"; $template_file_type="XLSX";}
		if (preg_match("/\.ods$/i", $sample_template_file_name)) {$delim_name="ODS: OpenOffice.org OpenDocument Spreadsheet"; $template_file_type="ODS";}
		if (preg_match("/\.sxc$/i", $sample_template_file_name)) {$delim_name="SXC: OpenOffice.org First Spreadsheet"; $template_file_type="SXC";}
		$delim_set=1;
		}
	else
		{
		copy($LF_path, "/tmp/vicidial_temp_file.txt");
		$lead_file = "/tmp/vicidial_temp_file.txt";
		$template_file_type="TXT";
		}
	$file=fopen("$lead_file", "r");
	if ($webroot_writable > 0)
		{$stmt_file=fopen("$WeBServeRRooT/$admin_web_directory/listloader_stmts.txt", "w");}

	$buffer=fgets($file, 4096);
	$buffer=eregi_replace("[\'\"\n]", "", $buffer);
	$tab_count=substr_count($buffer, "\t");
	$pipe_count=substr_count($buffer, "|");

	if ($delim_set < 1)
		{
		if ($tab_count>$pipe_count)
			{$delim_name="tab-delimited";} 
		else 
			{$delim_name="pipe-delimited";}
		} 
	if ($tab_count>$pipe_count)
		{$delimiter="\t";}
	else 
		{$delimiter="|";}
	echo "<script language='Javascript'>\n";
	echo "parent.document.getElementById(\"template_file_type\").value='$template_file_type';\n";
	echo "parent.document.getElementById(\"template_file_delimiter\").value='".urlencode($delimiter)."';\n";
	echo "parent.document.getElementById(\"template_file_buffer\").value='".urlencode($buffer)."';\n";
	echo "</script>";
	$field_check=explode($delimiter, $buffer);
	flush();
	}
else if ($form_action=="update_template") 
	{
	echo "Hi";
	} 
else
	{
	if ( (preg_match("/;|:|\/|\^|\[|\]|\"|\'|\*/",$LF_orig)) or (preg_match("/;|:|\/|\^|\[|\]|\"|\'|\*/",$sample_template_file_name)) )
		{
		echo "ERROR: Invalid File Name: $LF_orig $sample_template_file_name\n";
		exit;
		}
#	echo "<B>** $form_action</b>"; die;
$fields_stmt = "SELECT list_id, vendor_lead_code, source_id, phone_code, phone_number, title, first_name, middle_initial, last_name, address1, address2, address3, city, state, province, postal_code, country_code, gender, date_of_birth, alt_phone, email, security_phrase, comments, rank, owner from vicidial_list limit 1";

$vicidial_list_fields = '|lead_id|vendor_lead_code|source_id|list_id|gmt_offset_now|called_since_last_reset|phone_code|phone_number|title|first_name|middle_initial|last_name|address1|address2|address3|city|state|province|postal_code|country_code|gender|date_of_birth|alt_phone|email|security_phrase|comments|called_count|last_local_call_time|rank|owner|entry_list_id|';
# $vicidial_listloader_fields = '|vendor_lead_code|source_id|phone_code|phone_number|title|first_name|middle_initial|last_name|address1|address2|address3|city|state|province|postal_code|country_code|gender|date_of_birth|alt_phone|email|security_phrase|comments|rank|owner|';

$vl_fields_count=substr_count($vicidial_listloader_fields, "|")-1;

##### BEGIN custom fields columns list ###
if ($custom_fields_enabled > 0)
	{
	$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
	if ($DB>0) {echo "$stmt\n";}
	$rslt=mysql_query($stmt, $link);
	$tablecount_to_print = mysql_num_rows($rslt);
	if ($tablecount_to_print > 0) 
		{
		$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$list_id';";
		if ($DB>0) {echo "$stmt\n";}
		$rslt=mysql_query($stmt, $link);
		$fieldscount_to_print = mysql_num_rows($rslt);
		if ($fieldscount_to_print > 0) 
			{
			$rowx=mysql_fetch_row($rslt);
			$custom_records_count =	$rowx[0];

			$custom_SQL='';
			$stmt="SELECT field_id,field_label,field_name,field_description,field_rank,field_help,field_type,field_options,field_size,field_max,field_default,field_cost,field_required,multi_position,name_position,field_order from vicidial_lists_fields where list_id='$list_id' order by field_rank,field_order,field_label;";
			if ($DB>0) {echo "$stmt\n";}
			$rslt=mysql_query($stmt, $link);
			$fields_to_print = mysql_num_rows($rslt);
			$fields_list='';
			$o=0;
			while ($fields_to_print > $o) 
				{
				$rowx=mysql_fetch_row($rslt);
				$A_field_label[$o] =	$rowx[1];
				$A_field_type[$o] =		$rowx[6];

				if ($DB>0) {echo "$A_field_label[$o]|$A_field_type[$o]\n";}

				if ( ($A_field_type[$o]!='DISPLAY') and ($A_field_type[$o]!='SCRIPT') )
					{
					if (!preg_match("/\|$A_field_label[$o]\|/",$vicidial_list_fields))
						{
						$custom_SQL .= ",$A_field_label[$o]";
						}
					}
				$o++;
				}

			$fields_stmt = "SELECT list_id, vendor_lead_code, source_id, phone_code, phone_number, title, first_name, middle_initial, last_name, address1, address2, address3, city, state, province, postal_code, country_code, gender, date_of_birth, alt_phone, email, security_phrase, comments, rank, owner $custom_SQL from vicidial_list, custom_$list_id limit 1";

			}
		}
	}
##### END custom fields columns list ###

##### Same code from the listloader page.  Since this is a custom template, it's assumed the layout is "custom" instead of "standard" and follows the coding as such
#if (($sample_template_file) && ($LF_path))
if ($delimiter && $buffer)
	{
	$total=0; $good=0; $bad=0; $dup=0; $post=0; $phone_list='';
#	$buffer=rtrim(fgets($file, 4096));
#	$buffer=stripslashes($buffer);
#	print "<center><font face='arial, helvetica' size=3 color='#009900'><B>Processing $delim_name file...\n";
	$row=explode($delimiter, eregi_replace("[\'\"]", "", $buffer));
#	echo "delimiter: $delimiter<BR>$buffer<BR>";
} 
echo "<table border=0 width='100%' cellpadding=0 cellspacing=0>";
$rslt=mysql_query("$fields_stmt", $link);
$custom_fields_count=mysql_num_fields($rslt)-$vl_fields_count;
for ($i=0; $i<mysql_num_fields($rslt); $i++) 
	{
		if (preg_match('/'.mysql_field_name($rslt, $i).'/', $vicidial_list_fields)) {$bgcolor="#D9E6FE";} else {$bgcolor="#FED9D9";}

		echo "  <tr bgcolor='$bgcolor'>\r\n";
		echo "    <td align=right nowrap><font class=standard>".strtoupper(eregi_replace("_", " ", mysql_field_name($rslt, $i))).": </font></td>\r\n";
		if (mysql_field_name($rslt, $i)!="list_id") 
			{
			echo "    <td align=left><select name='$field_prefix".mysql_field_name($rslt, $i)."_field' onChange='DrawTemplateStrings()'>\r\n";
			echo "     <option value='-1'>(none)</option>\r\n";

			for ($j=0; $j<count($row); $j++) 
				{
				eregi_replace("\"", "", $row[$j]);
				echo "     <option value='$j'>\"$row[$j]\"</option>\r\n";
				}

			echo "    </select></td>\r\n";
			}
		else 
			{
			echo "    <td align=left>&nbsp;<font class='standard_bold'>$list_id<input type='hidden' name='".$field_prefix.$list_id."' value='$list_id'></font></td>\r\n";
			}
			echo "  </tr>\r\n";
	}
	echo "</table>";
}
?>
