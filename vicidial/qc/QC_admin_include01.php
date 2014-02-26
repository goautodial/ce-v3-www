<?php
# QC_admin_include01.php
# 
# Copyright (C) 2012  poundteam.com    LICENSE: AGPLv2
#
# This script is designed to be used by admin.php with QC enabled, contributed by poundteam.com
#
# changes:
# 121116-1334 - First build, added to vicidial codebase
#
//Line 28030 admin.php
######################
# ADD=100000000000000 display all qc campaigns
######################
if (($ADD==100000000000000) && ($qc_auth=='1')) {
    echo "<TABLE><TR><TD>\n";
    echo "<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2>";
//SELECT campaign_id,campaign_name  from vicidial_campaigns where active = 'Y' and qc_enabled='Y' order by campaign_name
    $stmt="SELECT campaign_id,campaign_name, qc_statuses from vicidial_campaigns where active = 'Y' and qc_enabled='Y' order by campaign_name";
//	echo $stmt="SELECT conf_exten,server_ip,extension from vicidial_conferences order by conf_exten";
    $rslt=mysql_query($stmt, $link);
    $vicidialconf_to_print = mysql_num_rows($rslt);

    echo "<br>VICIDIAL QUALITY LISTINGS:\n";
    echo "<center><TABLE width=$section_width cellspacing=0 cellpadding=1>\n";
    echo "<tr bgcolor=black>";
    echo "<td><font size=1 color=white align=left><B>CAMPAIGN ID</B></td>";
    echo "<td><font size=1 color=white><B>CAMPAIGN NAME</B></td>";
    echo "<td><font size=1 color=white><B>QC STATUSES</B></td>";
    echo "<td align=center><font size=1 color=white><B>MODIFY</B></td></tr>\n";

    $o=0;
    while ($vicidialconf_to_print > $o) {
        $row=mysql_fetch_row($rslt);
        if (eregi("1$|3$|5$|7$|9$", $o)) {
            $bgcolor='bgcolor="#B9CBFD"';
        }
        else {
            $bgcolor='bgcolor="#9BB9FB"';
        }
        echo "<tr $bgcolor><td><font size=1><a href=\"$PHP_SELF?ADD=881&campaign_id=$row[0]\">$row[0]</a></td>";
        echo "<td><font size=1> $row[1]</td>";
        echo "<td><font size=1> $row[2]</td>";
        echo "<td align=center><font size=1><a href=\"$PHP_SELF?ADD=31&campaign_id=$row[0]\">MODIFY</a></td></tr>\n";
        $o++;
    }

    echo "</TABLE></center>\n";
}
######################
# ADD=881 VIEW one qc campaign
######################
if (($ADD==881) && ($qc_auth=='1')) {
    echo "<TABLE><TR><TD>\n";
    echo "<FONT FACE=\"ARIAL,HELVETICA\" COLOR=BLACK SIZE=2>";
    $stmt="SELECT campaign_id,campaign_name, qc_statuses from vicidial_campaigns where active = 'Y' and qc_enabled='Y' and campaign_id='$campaign_id' limit 1";
    $rslt=mysql_query($stmt, $link);
    $vicidialconf_to_print = mysql_num_rows($rslt);
    $o=0;
    while ($vicidialconf_to_print > $o) {
        $row=mysql_fetch_row($rslt);
        $o++;
    }
    $qc_status_list=substr($row[2],0,strlen($row[2])-2);

    echo "<br>$campaign_id - {$row[1]} - Quality Control Queue<br>QC statuses: $qc_status_list\n";

    $qc_statuses=explode(' ',$qc_status_list);
        echo "<center><TABLE width=$section_width cellspacing=0 cellpadding=1>\n";
    foreach ( $qc_statuses as $qc_status ) {
        $stmt="SELECT lead_id,first_name,last_name,modify_date,user from vicidial_list inner join vicidial_lists on vicidial_list.list_id=vicidial_lists.list_id where campaign_id='$campaign_id' and status='$qc_status' order by status, modify_date";
        $rslt=mysql_query($stmt, $link);
        $vicidialconf_to_print = mysql_num_rows($rslt);
        $o=0;
        while ($vicidialconf_to_print > $o) {
            if($o==0){
                echo "<tr bgcolor=black>";
                echo "<td><font size=1 color=white align=left><B>$qc_status ($vicidialconf_to_print)</B></td>";
                echo "<td><font size=1 color=white align=left><B>LEAD ID</B></td>";
                echo "<td><font size=1 color=white><B>NAME</B></td>";
                echo "<td><font size=1 color=white><B>Last Modified</B></td>";
                echo "<td align=center><font size=1 color=white><B>UserID</B></td></tr>\n";
            }
            $row=mysql_fetch_row($rslt);
            if (eregi("1$|3$|5$|7$|9$", $o)) {
                $bgcolor='bgcolor="#B9CBFD"';
            }
            else {
                $bgcolor='bgcolor="#9BB9FB"';
            }
            echo "<tr $bgcolor><td><font size=1>&nbsp;</td>";
            echo "<td><font size=1> $row[0]</td>";
            $lead_name=trim($row[1].' '.$row[2]);
            $lead_nameENC=urlencode($lead_name);
            if (strlen($lead_name)<1) $lead_name='No Name';
            echo "<td><font size=1><a href=\"/vicidial/qc/qc_modify_lead.php?lead_id=$row[0]&campaign_id=$campaign_id&lead_name=$lead_nameENC\">$lead_name</a></td>";
            echo "<td><font size=1> $row[3]</td>";
            echo "<td align=center><font size=1><a href=\"$PHP_SELF?ADD=31&campaign_id=$row[4]\">$row[4]</a></td></tr>\n";
            $o++;
        }

    }
        echo "</TABLE></center>\n";
}
?>
