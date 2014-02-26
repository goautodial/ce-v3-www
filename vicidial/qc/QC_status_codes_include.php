<?php
# QC_status_codes_include.php
# 
# Copyright (C) 2012  poundteam.com    LICENSE: AGPLv2
#
# This script is designed to display admin sections for QC functions, contributed by poundteam.com
#
# changes:
# 121116-1323 - First build, added to vicidial codebase
#

/* 
 * include at line 26437 replacing existing section entirely
//##QC
//require_once('qc/QC_status_codes_include.php');
*/
######################
# ADD=241111111111111 adds the new qc status code to the system
######################
//Moved to qc/QC_status_codes_include.php include file
if ($ADD==241111111111111)
	{
	echo "<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2>";
	$stmt="SELECT count(*) from vicidial_qc_codes where code='$code';";
	$rslt=mysql_query($stmt, $link);
	$row=mysql_fetch_row($rslt);
	if ($row[0] > 0)
		{echo "<br>QC STATUS CODE NOT ADDED - there is already a qc status code in the system with this name: $row[0]\n";}
	else
		{
		if ( (strlen($code) < 1) or (strlen($code_name) < 2) )
			{
			echo "<br>QC STATUS CODE NOT ADDED - Please go back and look at the data you entered\n";
			echo "<br>code must be between 1 and 8 characters in length\n";
			echo "<br>code name must be between 2 and 30 characters in length\n";
			}
		else
			{
			echo "<br><B>QC STATUS CODE ADDED: $code_name - $code</B>\n";
                        if (isset($_POST["qc_category"]))                    {$qc_category=$_POST["qc_category"];}

			$stmt="INSERT INTO vicidial_qc_codes (code,code_name,qc_result_type) values('$code','$code_name','$qc_category');";
			$rslt=mysql_query($stmt, $link);

			### LOG INSERTION Admin Log Table ###
			$SQL_log = "$stmt|";
			$SQL_log = ereg_replace(';','',$SQL_log);
			$SQL_log = addslashes($SQL_log);
			$stmt="INSERT INTO vicidial_admin_log set event_date='$SQLdate', user='$PHP_AUTH_USER', ip_address='$ip', event_section='QCSTATUSES', event_type='ADD', record_id='$code', event_code='ADMIN ADD QC STATUS', event_sql=\"$SQL_log\", event_notes='';";
			if ($DB) {echo "|$stmt|\n";}
			$rslt=mysql_query($stmt, $link);
			}
		}
	$ADD=341111111111111;
	}



######################
# ADD=341111111111111 modify vicidial QC status code
######################

if ($ADD==341111111111111)
	{
	if ( ($LOGmodify_servers==1) and ($SSqc_features_active > 0) )
		{
		if ( ($SSadmin_modify_refresh > 1) and ($modify_refresh_set < 1) )
			{
			$modify_url = "$PHP_SELF?ADD=341111111111111";
			$modify_footer_refresh=1;
			}
		echo "<TABLE><TR><TD>\n";
		echo "<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2>";

		echo "<br><center>\n";
		echo "<b>VICIDIAL QC STATUS CODES WITHIN THIS SYSTEM: &nbsp; $NWB#vicidial_qc_status_codes$NWE</b><br>\n";
		echo "<TABLE width=600 cellspacing=3>\n";
		echo "<tr><td>STATUS CODE</td><td>DESCRIPTION</td><td>QC CATEGORY</td><td>MODIFY/DELETE</td></tr>\n";

		##### go through each QC status code
		$stmt="SELECT count(*) from vicidial_qc_codes;";
		$rslt=mysql_query($stmt, $link);
		$rowx=mysql_fetch_row($rslt);
		if ($rowx[0] > 0)
			{
			$stmt="SELECT code,code_name,qc_result_type from vicidial_qc_codes order by code;";
			$rslt=mysql_query($stmt, $link);
			$statuses_to_print = mysql_num_rows($rslt);
			$o=0;
			while ($statuses_to_print > $o)
				{
				$rowx=mysql_fetch_row($rslt);
				$o++;

				if (eregi("1$|3$|5$|7$|9$", $o))
					{$bgcolor='bgcolor="#B9CBFD"';}
				else
					{$bgcolor='bgcolor="#9BB9FB"';}

				echo "<tr $bgcolor><td><form action=$PHP_SELF method=POST>\n";
				echo "<input type=hidden name=ADD value=441111111111111>\n";
				echo "<input type=hidden name=stage value=modify>\n";
				echo "<input type=hidden name=code value=\"$rowx[0]\">\n";
				echo "<font size=2><B>$rowx[0]</B></td>\n";
				echo "<td><input type=text name=code_name size=20 maxlength=30 value=\"$rowx[1]\"></td>\n";
				echo "<td><font size=2><B>$rowx[2]</B></td>\n";
				echo "<td align=center nowrap><font size=1><input type=submit name=submit value=MODIFY> &nbsp; &nbsp; &nbsp; &nbsp; \n";
				echo " &nbsp; \n";

				if (preg_match("/^B$|^NA$|^DNC$|^NA$|^DROP$|^INCALL$|^QUEUE$|^NEW$/i",$rowx[0]))
					{
					echo "<DEL>DELETE</DEL>\n";
					}
				else
					{
					echo "<a href=\"$PHP_SELF?ADD=441111111111111&code=$rowx[0]&stage=delete\">DELETE</a>\n";
					}
				echo "</form></td></tr>\n";
				}
			}
		echo "</table>\n";

		echo "<br>ADD NEW QC STATUS CODE<BR><form action=$PHP_SELF method=POST>\n";
		echo "<input type=hidden name=ADD value=241111111111111>\n";
		echo "Status: <input type=text name=code size=9 maxlength=8> &nbsp; \n";
		echo "Description: <input type=text name=code_name size=25 maxlength=30> \n";
                echo "QC Category: <select name=qc_category><option selected value=''>-Choose-</option><option value='PASS'>PASS</option><option value='FAIL'>FAIL</option><option value='CANCEL'>CANCEL</option><option value='COMMIT'>COMMIT</option><BR>\n";
		echo "<input type=submit name=submit value=ADD><BR>\n";

		echo "</FORM><br>\n";

		}
	else
		{
		echo "You do not have permission to view this page\n";
		exit;
		}
	}

?>
