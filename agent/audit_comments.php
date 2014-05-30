<?php
# audit_comments.php
# 
# Copyright (C) 2012  poundteam.com    LICENSE: AGPLv2
#
# This script is designed to display QC audit comments, contributed by poundteam.com
#
# changes:
# 121116-1322 - First build, added to vicidial codebase
#

function audit_comments($lead_id,$list_id,$format,$user,$mel,$NOW_TIME,$link,$server_ip,$session_name,$one_mysql_log,$campaign) {
    $audit_comments_active=audit_comments_active($list_id,$format,$user,$mel,$NOW_TIME,$link,$server_ip,$session_name,$one_mysql_log);
    if ($audit_comments_active) {
        //Get comment from list
        $stmt="select comments from vicidial_list where lead_id='$lead_id' limit 1;";
        if ($format=='debug') {
            echo "\n<!-- $stmt -->";
        }
        $rslt=mysql_query($stmt, $link);
        if ($mel > 0) {
            mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'00142-AuditComments2',$user,$server_ip,$session_name,$one_mysql_log);
        }
        $row=mysql_fetch_row($rslt);
        if (strlen($row[0]) > 0) {
            $comment=$row[0];
            //Put comment in comment table
            $stmt="INSERT INTO vicidial_comments (lead_id,user_id,list_id,campaign_id,comment) VALUES ('$lead_id','$user','$list_id','$campaign','$comment');";
            if ($format=='debug') {
                echo "\n<!-- $stmt -->";
            }
            $rslt=mysql_query($stmt, $link);
            if ($mel > 0) {
                mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'00142-AuditComments3',$user,$server_ip,$session_name,$one_mysql_log);
            }
            $affected=mysql_affected_rows();
            if($affected>0) {
                $stmt="UPDATE vicidial_list set comments='' where lead_id='$lead_id';";
                if ($format=='debug') {
                    echo "\n<!-- $stmt -->";
                }
                $rslt=mysql_query($stmt, $link);
                if ($mel > 0) {
                    mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'00142-AuditComments4',$user,$server_ip,$session_name,$one_mysql_log);
                }
            } else {
                mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'00142-AuditCommentsERROR-Comment not moved',$user,$server_ip,$session_name,$one_mysql_log);
                echo "\n<!-- 00142-AuditCommentsERROR-Comment not moved -->";
            }
        }
    }
}
function audit_comments_active($list_id,$format,$user,$mel,$NOW_TIME,$link,$server_ip,$session_name,$one_mysql_log){
    $stmt="select count(audit_comments) from vicidial_lists_custom where list_id='$list_id' and audit_comments='1' limit 1;";
    if ($format=='debug') {
        echo "\n<!-- $stmt -->";
    }
    $rslt=mysql_query($stmt, $link);
    if ($mel > 0) {
        mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'00142-AuditComments5',$user,$server_ip,$session_name,$one_mysql_log);
    }
    $row=mysql_fetch_row($rslt);
    if ($row[0] == '1') {
        return true;
    } else {
        return false;
    }
}
function get_audited_comments($lead_id,$format,$user,$mel,$NOW_TIME,$link,$server_ip,$session_name,$one_mysql_log) {
    global $ACcount;
    global $ACcomments;
    $stmt="select user_id,comment from vicidial_comments where lead_id='$lead_id';";
    if ($mel > 0) {
        mysql_error_logging($NOW_TIME,$link,$mel,$stmt,"00142-65-AuditComments:$stmt LeadID: $lead_id,$format,$user,$mel,$NOW_TIME,$link,$server_ip,$session_name,$one_mysql_log",$user,$server_ip,$session_name,$one_mysql_log);
    }
    $rslt=mysql_query($stmt, $link);
    if ($mel > 0) {
        mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'00142-69-AuditComments',$user,$server_ip,$session_name,$one_mysql_log);
    }
    $ACcount=mysql_num_rows($rslt);
    mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'00142-72-AuditComments $ACcount='.$ACcount,$user,$server_ip,$session_name,$one_mysql_log);
    if($ACcount>0) {
        $i=0;
        while ($i < $ACcount) {
            $row=mysql_fetch_row($rslt);
            mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'00142-77-AuditComments UserID='.$row[0],$user,$server_ip,$session_name,$one_mysql_log);
            $ACcomments .=	"UserID: $row[0]\n";
            mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'00142-79-AuditComments Comment='.$row[1],$user,$server_ip,$session_name,$one_mysql_log);
            $ACcomments .=	$row[1];
            $ACcomments .=	"\n----------------------------------\n";
            $i++;
        }
        return true;
    } else {
        return false;
    }
}
?>
