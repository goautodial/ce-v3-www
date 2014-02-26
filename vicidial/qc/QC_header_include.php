<!-- QC NAVIGATION -->
<?php
# QC_header_include.php
# 
# Copyright (C) 2012  poundteam.com    LICENSE: AGPLv2
#
# This script is designed to display header contents for QC, contributed by poundteam.com
#
# changes:
# 121116-1327 - First build, added to vicidial codebase
#
	
if (($SSqc_features_active=='1') && ($qc_auth=='1')) { ?>

<TR>
    <TD <?php echo $qc_hh ?>>
        <a href="<?php echo $ADMIN ?>?ADD=100000000000000"><FONT FACE="ARIAL,HELVETICA" COLOR=<?php echo $qc_fc ?> SIZE=<?php echo $header_font_size ?>><?php echo $qc_bold ?> Quality Control </FONT></a>
    </TD>
</TR>
<?php
if (strlen($qc_hh) > 1) {
    ?>
<TR BGCOLOR=<?php echo $qc_color ?>>
    <TD ALIGN=LEFT> &nbsp;
        <a href="<?php echo $ADMIN ?>?ADD=100000000000000"><FONT FACE="ARIAL,HELVETICA" COLOR=BLACK SIZE=<?php echo $subheader_font_size ?>> Show QC Campaigns </FONT></a>
    </TD>
</TR>
<TR BGCOLOR=<?php echo $qc_color ?>>
    <TD ALIGN=LEFT> &nbsp;
        <a href="<?php echo $ADMIN ?>?ADD=100000000000000"><FONT FACE="ARIAL,HELVETICA" COLOR=BLACK SIZE=<?php echo $subheader_font_size ?>> Enter QC Queue </FONT></a>
    </TD>
</TR>
<TR BGCOLOR=<?php echo $qc_color ?>>
    <TD ALIGN=LEFT> &nbsp;
        <a href="<?php echo $ADMIN ?>?ADD=341111111111111"><FONT FACE="ARIAL,HELVETICA" COLOR=BLACK SIZE=<?php echo $subheader_font_size ?>> Modify QC Codes </FONT></a>
    </TD>
</TR>
    <?php }
}
?>


