<?php
//admin.php line 3160
//##QC
//require_once('qc/QC_admin_variables01.php');
# QC_admin_variables01.php
# 
# Copyright (C) 2012  poundteam.com    LICENSE: AGPLv2
#
# This script is designed to be used by admin.php with QC enabled, contributed by poundteam.com
#
# changes:
# 121116-1330 - First build, added to vicidial codebase
#

if ($ADD==100000000000000)		{$hh='qc';		echo "Quality Control";}
if ($ADD==881)                          {$hh='qc';		echo "Quality Control Campaign $campaign_id";}
