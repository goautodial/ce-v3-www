<?Php
############################################################################################
####  Name:             userstatus.php                                                  ####
####  Type:             ci view user for advance modification of user                   ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
?>
<!-- <style type="text/css">
    @import url("../../../css/datepicker/datepicker.css");
</style> -->
<script src="../../../js/jquery.main.js" type="text/javascript"></script>
<script src="../../../js/jquery.ui.js" type="text/javascript"></script>
<script src="../../../js/go_user/go_user_ce.js"></script>

<!--//layout for datepicker//-->
<script type="text/javascript" src="../../../js/datepicker/datepicker-modify.js"></script> 
<script type="text/javascript" src="../../../js/datepicker/eye.js"></script>
<script type="text/javascript" src="../../../js/datepicker/utils.js"></script>  
<script type="text/javascript" src="../../../js/datepicker/layout.js?ver=1.0.2"></script>

<div id="modifyuser-container">
    <div class="leftside" width="100%">
        <div class="adv-user-status-client">
            <div class="adv-toggle"></div>
            <h3>User Status</h3>
            <div class="adv-user-detail user-corners">
                 <div class="userstatus-display">
                 <?Php
                      echo '<b>'.$userinfo[0]->user . " - " .$userinfo[0]->full_name . "&nbsp;&nbsp;&nbsp;Group: ".$userinfo[0]->user_group.'</b><br/><br/>';
                      echo "<div class='leftside'>Agent logged in at server</div><div class='rightside'> ". $liveagent[0]->server_ip."</div><br/>";
                      echo "<div class='leftside'>In session</div><div class='rightside'> ". $liveagent[0]->conf_exten."</div><br/>";
                      echo "<div class='leftside'>From phone</div><div class='rightside'> ". $liveagent[0]->extension."</div><br/>";
                      echo "<div class='leftside'>Agent is in campaign</div><div class='rightside'> ". $liveagent[0]->campaign_id."</div><br/>";
                      echo "<div class='leftside'>Status</div><div class='rightside'> ". $liveagent[0]->status."</div><br/>";
                      echo "<div class='leftside'>Hang-up last call at</div><div class='rightside'> ". $liveagent[0]->last_call_finish."</div><br/>";
                      echo "<div class='leftside'>Closer groups</div><div class='rightside'> ". $liveagent[0]->closer_campaigns."</div><br/>";
                 ?> 
                 </div>
                 <p id="<?=$userinfo[0]->user?>"><a href="javascript:void(0);" class="emergency">Emergency Logout</a></p>
                 <br class="clear"/>
            </div>
        </div>
        <div class="adv-user-stats">
            <div class="adv-toggle"></div>
            <h3>User Stats</h3>
            <div class="adv-user-detail user-corners">
                <div class="datepicker-container">
                    <div class="hovermenu" id="widgetField">
                        <span id="user-stat-<?=$userinfo[0]->user?>"><? echo date('Y-m-d'); ?> to <? echo date('Y-m-d'); ?></span>
                        <a href="javascript:void(0);" id="daterange">Select date range</a>
                    </div>
                    <div id="widgetCalendar"></div> <!--//calendar layout//-->
                </div>
                <br class="clear"/>
                <div class="stats-container">
                    <div class="kaliwa">
                        <div class="agent-talk-time user-cornerall">
                           <strong>Agent Talk Time and Status </strong>
                           <br class="clear"/>
                           <br class="clear"/>
                           <div class="time-status-tbl">
                               <div class="time-stat-hdr">
                                   <div class="cols">Status</div>
                                   <div class="cols">Count</div>
                                   <div class="cols">Hours:MM:SS</div>
                               </div>
                               <br class="clear"/> 
                               <div class="time-stat-content"></div>
                           </div>
                        </div>
                        <br class="greatespace"/>
                        <div class="agent-loginlogout-time user-cornerall">
                           <strong>Agent Login/Logout Time</strong>
                           <br class="clear"/>
                           <br class="clear"/>
                           <div class="time-loginlogout-tbl">
                               <div class="agent-loginlogout-hdr">
                                   <div class="cols">Event</div>
                                   <div class="cols">Date</div>
                                   <div class="cols">Campaign</div>
                                   <div class="cols">Group</div>
                                   <div class="cols">HH:MM:SS</div>
                                   <div class="cols">Session</div>
                                   <div class="cols">Server</div>
                                   <div class="cols">Phone</div>
                                   <div class="cols">Computer</div>
                               </div>
                               <br class="clear"/> 
                               <div class="time-loginlogout-content"></div>
                           </div>
                        </div>
                        <br class="greatespace"/>
                        <div class="agent-outbound-thistime user-cornerall">
                           <strong>Outbound Calls For This Time Period(1000 record limit)</strong>
                           <br class="clear"/>
                           <br class="clear"/>
                           <div class="outbound-thistime-tbl">
                               <div class="outbound-thistime-hdr">
                                   <div class="cols">Date/Time</div>
                                   <div class="cols">Length</div>
                                   <div class="cols">Status</div>
                                   <div class="cols">Phone</div>
                                   <div class="cols">Campaign</div>
                                   <div class="cols">Group</div>
                                   <div class="cols">List</div>
                                   <div class="cols">Lead</div>
                                   <div class="cols">Hangup Reason</div>
                               </div>
                               <br class="clear"/> 
                               <div class="outbound-thistime-content"></div>
                           </div>
                        </div>
                        <br class="greatespace"/>
                        <div class="agent-inbound-thistime user-cornerall">
                           <strong>Inbound/Closer Calls For This Time Period(1000 record limit)</strong>
                           <br class="clear"/>
                           <br class="clear"/>
                           <div class="inbound-thistime-tbl">
                               <div class="inbound-thistime-hdr">
                                   <div class="cols">Date/Time</div>
                                   <div class="cols">Length</div>
                                   <div class="cols">Status</div>
                                   <div class="cols">Phone</div>
                                   <div class="cols">Campaign</div>
                                   <div class="cols">Wait(s)</div>
                                   <div class="cols">Agent(s)</div>
                                   <div class="cols">List</div>
                                   <div class="cols">Lead</div>
                                   <div class="cols">Hangup Reason</div>
                               </div>
                               <br class="clear"/> 
                               <div class="inbound-thistime-content"></div>
                           </div>
                        </div>
                        <br class="greatespace"/>
                        <div class="agent-activity-thistime user-cornerall">
                           <strong>Agent Activity For This Time Period(1000 limit)</strong>
                           <br class="clear"/>
                           <br class="clear"/>
                           <div class="agent-activity-tbl">
                               <div class="agentactivity-thistime-hdr">
                                   <div class="cols">Date/Time</div>
                                   <div class="cols">Pause</div>
                                   <div class="cols">Wait</div>
                                   <div class="cols">Talk</div>
                                   <div class="cols">Dispo</div>
                                   <div class="cols">Dead</div>
                                   <div class="cols">Customer</div>
                                   <div class="cols">Status</div>
                                   <div class="cols">Lead</div>
                                   <div class="cols">Campaign</div>
                                   <div class="cols">Pause Code</div>
                               </div>
                               <br class="clear"/>
                               <div class="agent-activity-content"></div>
                           </div>
                        </div>
                        <br class="greatespace"/>
                        <div class="agent-recording-thistime user-cornerall">
                           <strong>Recording For This Time Period(1000 record limit)</strong>
                           <br class="clear"/>
                           <br class="clear"/>
                           <div class="recording-thistime-tbl">
                               <div class="recording-thistime-hdr">
                                   <div class="cols">Lead</div>
                                   <div class="cols">Date/Time</div>
                                   <div class="cols">Seconds</div>
                                   <div class="cols">RECID</div>
                                   <div class="cols">Filename</div>
                                   <div class="cols">Location</div>
                               </div>
                               <br class="clear"/> 
                               <div class="recording-thistime-content"></div>
                           </div>
                        </div>
                        <br class="greatespace"/>
                        <div class="agent-manualoutbound-thistime user-cornerall">
                           <strong>Manual Outbound Calls For This Time Period(1000 record limit)</strong>
                           <br class="clear"/>
                           <br class="clear"/>
                           <div class="manualoutbound-thistime-tbl">
                               <div class="manualoutbound-thistime-hdr">
                                   <div class="cols">Date/Time</div>
                                   <div class="cols">Call Type</div>
                                   <div class="cols">Server</div>
                                   <div class="cols">Phone</div>
                                   <div class="cols">Dialed</div>
                                   <div class="cols">Lead</div>
                                   <div class="cols">Caller Id</div>
                                   <div class="cols">Alias</div>
                                   <div class="cols">Preset</div>
                                   <div class="cols">C3HU</div>
                               </div>
                               <br class="clear"/> 
                               <div class="manualoutbound-thistime-content"></div>
                           </div>
                        </div>
                        <br class="greatespace"/>
                        <div class="agent-leadsearch-thistime user-cornerall">
                           <strong>Lead Searches For This Time Period(1000 record limit)</strong>
                           <br class="clear"/>
                           <br class="clear"/>
                           <div class="leadsearch-thistime-tbl">
                               <div class="leadsearch-thistime-hdr">
                                   <div class="cols">Date/Time</div>
                                   <div class="cols">Type</div>
                                   <div class="cols">Results</div>
                                   <div class="cols">Sec</div>
                                   <div class="cols">Query</div>
                               </div>
                               <br class="clear"/> 
                               <div class="leadsearch-thistime-content"></div>
                           </div>
                       </div>
                    </div>
                    <br class="clear"/>
                </div>
                <br class="clear"/>
            </div>
        </div>
        <br class="clear"/>
    </div> <!--// END OF div.leftside  //-->
    <br class="clear"/>
    <br class="clear"/>
    <br class="clear"/>
    <br class="clear"/>
    <br class="clear"/>
</div> <!--// modify mode  //-->
