<?php
# QC_header_include01.php
# 
# Copyright (C) 2012  poundteam.com    LICENSE: AGPLv2
#
# This script is designed to display header contents for QC, contributed by poundteam.com
#
# changes:
# 121116-1326 - First build, added to vicidial codebase
#

if ($hh=='qc')
	{$qc_hh="bgcolor=\"$qc_color\""; $qc_fc="$qc_font"; $qc_bold="$header_selected_bold";}
	else {$qc_hh=''; $qc_fc='WHITE'; $qc_bold="$header_nonselected_bold";}

?>
