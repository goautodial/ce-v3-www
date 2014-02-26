<?php
//Line 1349 admin_modify_lead.php
# QC_admin_modify_lead_include.php
# 
# Copyright (C) 2012  poundteam.com    LICENSE: AGPLv2
#
# This script is designed to be used by admin.php with QC enabled, contributed by poundteam.com
#
# changes:
# 121116-1332 - First build, added to vicidial codebase
#
######################
# //Display link to QC if user has QC permissions
######################
require('QC_admin_include02.php');
if ($qcuser_level > 0)
        {
        echo "<br><br><a href=\"./qc/qc_modify_lead.php?lead_id=$lead_id\">Click here to QC Modify this lead</a>\n";
        }
?>
