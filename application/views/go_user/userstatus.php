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
                      echo "<div class='leftside'>".$goUsers_agentLoggedInAtServer."</div><div class='rightside'> ". $liveagent[0]->server_ip."</div><br/>";
                      echo "<div class='leftside'>".$goUsers_inSession."</div><div class='rightside'> ". $liveagent[0]->conf_exten."</div><br/>";
                      echo "<div class='leftside'>".$goUsers_fromPhone."</div><div class='rightside'> ". $liveagent[0]->extension."</div><br/>";
                      echo "<div class='leftside'>".$goUsers_agentIsInCampaign."</div><div class='rightside'> ". $liveagent[0]->campaign_id."</div><br/>";
                      echo "<div class='leftside'>".$goUsers_status."</div><div class='rightside'> ". $liveagent[0]->status."</div><br/>";
                      echo "<div class='leftside'>".$goUsers_hangupLastCallAt."</div><div class='rightside'> ". $liveagent[0]->last_call_finish."</div><br/>";
                      echo "<div class='leftside'>".$goUsers_closerGroups."</div><div class='rightside'> ". $liveagent[0]->closer_campaigns."</div><br/>";
                 ?> 
                 </div>
                 <p id="<?=$userinfo[0]->user?>"><a href="javascript:void(0);" class="emergency"><?php echo $goUsers_emergencyLogout; ?></a></p>
                 <br class="clear"/>
            </div>
        </div>
        <div class="adv-user-stats">
            <div class="adv-toggle"></div>
            <h3><?php echo $goUsers_userStatus; ?></h3>
            <div class="adv-user-detail user-corners">
                <div class="datepicker-container">
                    <div class="hovermenu" id="widgetField">
                        <span id="user-stat-<?=$userinfo[0]->user?>"><? echo date('Y-m-d'); ?> to <? echo date('Y-m-d'); ?></span>
                        <a href="javascript:void(0);" id="daterange"><?php echo $goUsers_selectDateRange; ?></a>
                    </div>
                    <div id="widgetCalendar"></div> <!--//calendar layout//-->
                </div>
                <br class="clear"/>
                <div class="stats-container">
                    <div class="kaliwa">
                        <div class="agent-talk-time user-cornerall">
                           <strong><?php echo $goUsers_agentTalkTimeAndStatus; ?> </strong>
                           <br class="clear"/>
                           <br class="clear"/>
                           <div class="time-status-tbl">
                               <div class="time-stat-hdr">
                                   <div class="cols"><?php echo $goUsers_status; ?></div>
                                   <div class="cols"><?php echo $goUsers_count; ?></div>
                                   <div class="cols">Hours:MM:SS</div>
                               </div>
                               <br class="clear"/> 
                               <div class="time-stat-content"></div>
                           </div>
                        </div>
                        <br class="greatespace"/>
                        <div class="agent-loginlogout-time user-cornerall">
                           <strong><?php echo $goUsers_agentLoginLogoutTime; ?></strong>
                           <br class="clear"/>
                           <br class="clear"/>
                           <div class="time-loginlogout-tbl">
                               <div class="agent-loginlogout-hdr">
                                   <div class="cols"><?php echo $goUsers_event; ?></div>
                                   <div class="cols"><?php echo $goUsers_date; ?></div>
                                   <div class="cols"><?php echo $goUsers_campaign; ?></div>
                                   <div class="cols"><?php echo $goUsers_group; ?></div>
                                   <div class="cols">HH:MM:SS</div>
                                   <div class="cols"><?php echo $goUsers_session; ?></div>
                                   <div class="cols"><?php echo $goUsers_server; ?></div>
                                   <div class="cols"><?php echo $goUsers_phone; ?></div>
                                   <div class="cols"><?php echo $goUsers_computer; ?></div>
                               </div>
                               <br class="clear"/> 
                               <div class="time-loginlogout-content"></div>
                           </div>
                        </div>
                        <br class="greatespace"/>
                        <div class="agent-outbound-thistime user-cornerall">
                           <strong><?php echo $goUsers_outboundCallsForThisTimePeriod1k; ?></strong>
                           <br class="clear"/>
                           <br class="clear"/>
                           <div class="outbound-thistime-tbl">
                               <div class="outbound-thistime-hdr">
                                   <div class="cols"><?php echo $goUsers_dateTime; ?></div>
                                   <div class="cols"><?php echo $goUsers_length; ?></div>
                                   <div class="cols"><?php echo $goUsers_status; ?></div>
                                   <div class="cols"><?php echo $goUsers_phone; ?></div>
                                   <div class="cols"><?php echo $goUsers_campaign; ?></div>
                                   <div class="cols"><?php echo $goUsers_group; ?></div>
                                   <div class="cols"><?php echo $goUsers_list; ?></div>
                                   <div class="cols"><?php echo $goUsers_lead; ?></div>
                                   <div class="cols"><?php echo $goUsers_hangupReason; ?></div>
                               </div>
                               <br class="clear"/> 
                               <div class="outbound-thistime-content"></div>
                           </div>
                        </div>
                        <br class="greatespace"/>
                        <div class="agent-inbound-thistime user-cornerall">
                           <strong><?php echo $goUsers_inboundCloserCallsForThisTimePeriod1k; ?></strong>
                           <br class="clear"/>
                           <br class="clear"/>
                           <div class="inbound-thistime-tbl">
                               <div class="inbound-thistime-hdr">
                                   <div class="cols"><?php echo $goUsers_dateTime; ?></div>
                                   <div class="cols"><?php echo $goUsers_length; ?></div>
                                   <div class="cols"><?php echo $goUsers_status; ?></div>
                                   <div class="cols"><?php echo $goUsers_phone; ?></div>
                                   <div class="cols"><?php echo $goUsers_campaign; ?></div>
                                   <div class="cols"><?php echo $goUsers_waits; ?></div>
                                   <div class="cols"><?php echo $goUsers_agents; ?></div>
                                   <div class="cols"><?php echo $goUsers_list; ?></div>
                                   <div class="cols"><?php echo $goUsers_lead; ?></div>
                                   <div class="cols"><?php echo $goUsers_hangupReason; ?></div>
                               </div>
                               <br class="clear"/> 
                               <div class="inbound-thistime-content"></div>
                           </div>
                        </div>
                        <br class="greatespace"/>
                        <div class="agent-activity-thistime user-cornerall">
                           <strong><?php echo $goUsers_agentActivityForThisTimePeriod1k ?></strong>
                           <br class="clear"/>
                           <br class="clear"/>
                           <div class="agent-activity-tbl">
                               <div class="agentactivity-thistime-hdr">
                                   <div class="cols"><?php echo $goUsers_dateTime; ?></div>
                                   <div class="cols"><?php echo $goUsers_pause; ?></div>
                                   <div class="cols"><?php echo $goUsers_wait; ?></div>
                                   <div class="cols"><?php echo $goUsers_talk; ?></div>
                                   <div class="cols"><?php echo $goUsers_disposition; ?></div>
                                   <div class="cols"><?php echo $goUsers_dead; ?></div>
                                   <div class="cols"><?php echo $goUsers_customer; ?></div>
                                   <div class="cols"><?php echo $goUsers_status; ?></div>
                                   <div class="cols"><?php echo $goUsers_lead; ?></div>
                                   <div class="cols"><?php echo $goUsers_campaign; ?></div>
                                   <div class="cols"><?php echo $goUsers_pauseCode; ?></div>
                               </div>
                               <br class="clear"/>
                               <div class="agent-activity-content"></div>
                           </div>
                        </div>
                        <br class="greatespace"/>
                        <div class="agent-recording-thistime user-cornerall">
                           <strong><?php echo $goUsers_recordingForThisTimePeriod; ?></strong>
                           <br class="clear"/>
                           <br class="clear"/>
                           <div class="recording-thistime-tbl">
                               <div class="recording-thistime-hdr">
                                   <div class="cols"><?php echo $goUsers_lead; ?></div>
                                   <div class="cols"><?php echo $goUsers_dateTime; ?></div>
                                   <div class="cols"><?php echo $goUsers_seconds; ?></div>
                                   <div class="cols"><?php echo $goUsers_recid; ?></div>
                                   <div class="cols"><?php echo $goUsers_filename; ?></div>
                                   <div class="cols"><?php echo $goUsers_location; ?></div>
                               </div>
                               <br class="clear"/> 
                               <div class="recording-thistime-content"></div>
                           </div>
                        </div>
                        <br class="greatespace"/>
                        <div class="agent-manualoutbound-thistime user-cornerall">
                           <strong><?php echo $goUsers_manualOutboundCallsForThisTimePeriod; ?></strong>
                           <br class="clear"/>
                           <br class="clear"/>
                           <div class="manualoutbound-thistime-tbl">
                               <div class="manualoutbound-thistime-hdr">
                                   <div class="cols"><?php echo $goUsers_dateTime; ?></div>
                                   <div class="cols"><?php echo $goUsers_callType; ?></div>
                                   <div class="cols"><?php echo $goUsers_server; ?></div>
                                   <div class="cols"><?php echo $goUsers_phone; ?></div>
                                   <div class="cols"><?php echo $goUsers_dialed; ?></div>
                                   <div class="cols"><?php echo $goUsers_lead; ?></div>
                                   <div class="cols"><?php echo $goUsers_callerId; ?></div>
                                   <div class="cols"><?php echo $goUsers_alias; ?></div>
                                   <div class="cols"><?php echo $goUsers_peset; ?></div>
                                   <div class="cols"><?php echo $goUsers_c3hu; ?></div>
                               </div>
                               <br class="clear"/> 
                               <div class="manualoutbound-thistime-content"></div>
                           </div>
                        </div>
                        <br class="greatespace"/>
                        <div class="agent-leadsearch-thistime user-cornerall">
                           <strong><?php echo $goUsers_leadSearchesForThisTimePeriod1k; ?></strong>
                           <br class="clear"/>
                           <br class="clear"/>
                           <div class="leadsearch-thistime-tbl">
                               <div class="leadsearch-thistime-hdr">
                                   <div class="cols"><?php echo $goUsers_dateTime; ?></div>
                                   <div class="cols"><?php echo $goUsers_type; ?></div>
                                   <div class="cols"><?php echo $goUsers_results ?></div>
                                   <div class="cols"><?php echo $goUsers_second; ?></div>
                                   <div class="cols"><?php echo $goUsers_query; ?></div>
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
