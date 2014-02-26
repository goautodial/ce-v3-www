<?php
############################################################################################
####  Name:             go_user_main.php                                                ####
####  Type:             ci view (template for users)                                    ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
?>
<style type="text/css">
    @import url("../../../css/go_search/go_search.css");
</style>
<script src="../../../js/go_search/go_search.js"></script>
<div id='outbody' class="wrap">
    <div id=<?=$icon_s?> class="icon32"></div>
    <h3><?=$bannertitle?></h3>
    <br><!-- spacer -->
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
            <div class="postbox-container" style="width:100%;">
                <div class="meta-box-sortables ui-sortables">
                    <!-- List holder-->
                    <div class="postbox">
                        <div class="handlediv" title="Click to toggle">
                            <br>
                        </div>
                        <h3 class="hndle">
                            <span>
                                 <?=$bannertitle?>&nbsp;Lists
                            </span>
                        </h3>
                        <div class="inside inside-tab">
                            <div id="tab-nav" class="tab-nav">
                                <ul>
                                    <li><a href="groupbylist"><span>Group</span></a></li>
                                    <li><a href="ungrouplist"><span>Ungroup</span></a></li>
                                </ul>
                            </div>
                            <div class="clear">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div> <!-- wpwrap -->
