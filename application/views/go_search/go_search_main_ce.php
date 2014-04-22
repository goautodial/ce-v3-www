<?php
############################################################################################
####  Name:             go_user_main_ce.php                                             ####
####  Type:             ci view (template for users)                                    ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################

$base = base_url();
if ($permissions->recordings_display=="N") {
    echo "<script>\n";
    echo "alert('Error: You don\'t have permission to view this record(s)');\n";
    echo "window.location.replace('{$base}dashboard');";
    echo "</script>";
}
?>
<!--<style type="text/css">
    @import url("../../../css/go_search/go_search_ce.css");
</style> -->

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/go_common_ce.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/go_search/go_search_ce.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/tooltip/tipTip.css" />
<script src="<?=base_url()?>js/jquery-validate/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/go_common_ce.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/go_search/go_search_ce.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/go_search/go_search_panel1_ce.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/tooltip/jquery.tipTip.js"></script>

<script>
function gopage(page) {
    var search_query = '<?=$this->uri->segment(2) ?>';
    $("#user-container").empty().html('<center><img src="<?=base_url() ?>img/goloading.gif" /></center>');
    $.post('<?=base_url() ?>index.php/go_search_ce/index/<?=$user_group ?>/', { page: page, search: search_query }, function(data) {
        $("#user-container").empty().html(data);
    });
}
</script>

<div id='outbody' class="wrap">
    <div id='<?=$icon_s?>' class="icon32"></div>
    <h2><?=$bannertitle?></h2>
    <br><!-- spacer -->
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
            <div class="postbox-container minimum" style="width:99%;">
                <div class="meta-box-sortables ui-sortables">
                    <!-- List holder-->
                    <div class="postbox minimum">
                        <div class="user-add">
                             <a id="advance-filter">Search <?=img("img/spotlight-black.png")?></a>
                        </div>
                        <div class="hndle">
                            <span>
                                 &nbsp;Search Filters
                            </span>
                        </div>
                        <div class="inside inside-tab">
                            <?
                               echo "<input type='hidden' value='$search_key' id='search_leadid'>";
                               echo "<input type='hidden' value='$type' id='type'>";
                            ?>
                            <div id="tab-nav" class="tab-nav">
                                <ul>
                                    <li><a href="<?=base_url()?>index.php/go_search_ce/index/<?=$user_group?>"><span>Search Results</span></a></li>
                                    <!-- <li><a href="userstatus"><span>User Status</span></a></li> -->
                                    <?if($user_level > 8){?>
                                    <!-- <li><a href="advancemodifyuser"><span>Advance</span></a></li> -->
                                    <?}?>
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
<div class="overlay"></div><!--End of overlay-->
<br class="clear"/>
<div class="corner-all message-box">
   <div class="overlay-closer"></div>
   <div class="box-header"><strong>Advance Search</strong></div><br class="clear"/>
   <div class="boxleftside"><label><strong>Date:</strong></label></div>
   <div class="boxrightside">
     <div class="user-add">
       <div class="hovermenu" id="widgetField">
            <?php
                if(!empty($daterange)){
                   $date_val = explode(",",$daterange[1]);
                }
            ?>
            <div id="widgetDate"><span id="user-stat"><? echo (isset($date_val)?$date_val[0]:date('Y-m-d')); ?> to <? echo (isset($date_val)?$date_val[1]:date('Y-m-d')); ?></span></div>
            <a href="javascript:void(0);" id="daterange">Select date range</a>
       </div>
       <div id="widgetCalendar"></div> <!--//calendar layout//-->
     </div>
   </div>
   <div class="boxleftside"><strong>Phone:</strong></div>
   <div class="boxrightside"><?=form_input("phone",null,"id='phone' maxlength='11'")?></div><br/>
   <div class="boxleftside"><strong>Firstname:</strong></div>
   <div class="boxrightside"><?=form_input("first_name",null,"id='first_name'")?></div><br/>
   <div class="boxleftside"><strong>Lastname:</strong></div>
   <div class="boxrightside"><?=form_input("last_name",null,"id='last_name'")?></div><br/>
   <div class="boxleftside adv"><strong>Lead Id:</strong></div>
   <div class="boxrightside adv"><?=form_input("lead_id",null,"id='lead_id'")?></div><br/>
   <div class="boxleftside adv"><strong>Call Dispo:</strong></div>
   <div class="boxrightside adv"><?=form_dropdown("disposition",$dispos,null,"id='disposition'")?></div><br/>
   <div class="boxleftside adv"><strong>Agent:</strong></div>
   <div class="boxrightside adv"><?=form_dropdown("agent",$agents,null,"id='agent'")?></div><br/>
   <div class="boxleftside "><strong>Recording:</strong></div>
   <div class="boxrightside "><?=form_radio(array("name"=>"rec","value"=>'no',"id"=>'rec'))." No ".form_radio(array("name"=>"rec","value"=>'yes',"id"=>'rec'))." Yes ".form_radio(array("name"=>"rec","value"=>'all',"id"=>'rec','checked'=>true))." All"?></div><br class="clear"/>
   <div class="search-action"><a id="adv" onclick="adv();">Advance</a> | <a id="search" onclick="searchkeys()">Search</a></div>
   <br/>
</div>
<div class="corner-all message-box2">
   <div class="overlay-closer"></div>
   <div class="box-header"><strong>Lead Information</strong></div><br class="clear"/>
   <div class="boxleftside">Lead Id</div>
   <div class="boxrightside" id="leadinfo_lead_id">lead_id</div><br class="clear"/>
   <div class="boxleftside">List Id</div>
   <div class="boxrightside" id="leadinfo_list_id">list_id</div><br class="clear"/>
   <!--<div class="boxleftside">Status</div>
   <div class="boxrightside" id="leadinfo_status">status</div><br class="clear"/> -->
   <div class="boxleftside">Fullname</div>
   <div class="boxrightside" id="leadinfo_fullname">Fullname</div><br class="clear"/>
   <div class="boxleftside">Address</div>
   <div class="boxrightside" ><?=form_input('leadinfo_address1',null,'id="leadinfo_address1"')?></div><br class="clear"/>
   <div class="boxleftside">Phone Code</div>
   <div class="boxrightside" ><?=form_input('leadinfo_phone_code',null,'id="leadinfo_phone_code" size="10"')?></div><br class="clear"/>
   <div class="boxleftside">Phone Number</div>
   <div class="boxrightside" ><?=form_input('leadinfo_phone_number',null,'id="leadinfo_phone_number"')?></div><br class="clear"/>
   <div class="boxleftside">City</div>
   <div class="boxrightside"><?=form_input('leadinfo_city',null,'id="leadinfo_city"')?></div><br class="clear"/>
   <div class="boxleftside">State</div>
   <div class="boxrightside"><?=form_input('leadinfo_state',null,'id="leadinfo_state" size="2"')?></div><br class="clear"/>
   <div class="boxleftside">Zip Code</div>
   <div class="boxrightside"><?=form_input('leadinfo_postal_code',null,'id="leadinfo_postal_code"')?></div><br class="clear"/>
   <div class="boxleftside">Comments</div>
   <div class="boxrightside"><?=form_textarea(array('name'=>'leadinfo_comments','value'=>null,'id'=>"leadinfo_comments",'cols'=>"60",'rows'=>"5"))?></div><br class="clear"/>
   <div class="boxleftside invi-elem">Disposition</div>
   <div class="boxrightside invi-elem" >
        <div class="innerbox">Status</div><div class="innerbox vars"><?=form_dropdown("leadinfo_status",$status,null,"id='leadinfo_status'")?></div><br class="clear"/>
        <div class="innerbox">Modify vicidial log</div><div class="innerbox vars"><?=form_checkbox(array('id'=>'modify_log','value'=>'1','name'=>'modify_log','checked'=>true))?></div><br class="clear"/>
        <div class="innerbox">Modify agent log</div><div class="innerbox vars"><?=form_checkbox(array('id'=>'modify_agent_logs','value'=>'1','name'=>'modify_agent_logs','checked'=>true))?></div><br class="clear"/>
        <div class="innerbox">Modify closer log</div><div class="innerbox vars"><?=form_checkbox(array('id'=>'modify_closer_logs','value'=>'1','name'=>'modify_closer_logs'))?></div><br class="clear"/>
        <div class="innerbox">Add closer log record</div><div class="innerbox vars"><?=form_checkbox(array('id'=>'add_closer_record','value'=>'1','name'=>'add_closer_record'))?></div><br class="clear"/>
        <div class="inner-action"><a id="dispo_submit">Submit</a></div>
   </div><br class="clear"/>
   <div class="boxleftside invi-elem callback">Callback Details</div>
   <div class="boxrightside invi-elem callback" >
        <div class="innerbox innerbox-lbl useronly">Change to Anyone Callback</div><div class="innerbox innerbox-cont useronly"><?=form_button(array('name'=>"usertoany",'content'=>"Change",'value'=>'usertoany','id'=>'usertoany'))?></div><br class="clear"/>
        <div class="innerbox innerbox-lbl useronly anyone">New Callback Owner</div><div class="innerbox innerbox-cont useronly anyone"><?=form_dropdown("leadinfo_user",$all_agent,'',"id='leadinfo_user'")."&nbsp;".form_button(array('id'=>"touser",'content'=>"Change"))?></div><br class="clear"/>
        <div class="innerbox innerbox-lbl ">Schedule Callback Date</div>
        <div class="innerbox innerbox-cont ">
            <input type="hidden" id="callbackid" name="callbackid">
            <div><?
                   $months = array("01"=>"Jan",'02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May',
                                   '06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>"Sep",10=>'Oct',11=>'Nov',12=>'Dec');
                   $curryear = date("Y");
                   $days = array(1=>1,2,3,4,5,6,7,8,9,10,11,12,
                                 13,14,15,16,17,18,19,20,21,22,
                                 23,24,25,26,27,28,29,30,31);
                   $years = array($curryear - 1=>$curryear - 1,$curryear=>$curryear,$curryear + 1=>$curryear + 1);
                   $hour = array("00"=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04',
                                 '05'=>'05','06'=>'06','07'=>'07','08'=>'80','09'=>'09',
                                 '10'=>'10',11=>11,12=>12,13=>13,14=>14,15=>15,16=>16,
                                  17=>17,18=>18,19=>19,20=>20,21=>21,22=>22,23=>23,24=>24);
                   $min = array("00"=>'00','05'=>'05',10=>10,15=>15,20=>20,25=>25,30=>30,35=>35,40=>40,45=>45,50=>50,55=>55);
                   echo form_dropdown("appointment_year",$years,'',"id='appointment_year'")."-".
                        form_dropdown("appointment_month",$months,'',"id='appointment_month'")."-".
                        form_dropdown('appointment_day',$days,'','id="appointment_day"')."&nbsp;&nbsp;&nbsp;".
                        form_dropdown('appointment_hour',$hour,'','id="appointment_hour"').":".
                        form_dropdown('appointment_min',$min,'','id="appointment_min"');
                 ?>
            </div>
        </div><br class="clear"/>
        <div class="innerbox innerbox-lbl">Comment</div>
        <div class="innerbox">
           <div><?=form_textarea(array('name'=>'appointment_comments','value'=>null,'id'=>"appintment_comments",'cols'=>"30",'rows'=>"5"))?></div>
        </div><br class="clear"/>
        <div class="inner-action"><a id="callback_update">Update</a></div>
   </div><br class="clear"/>
   <!-- S t a r t -->
   <div class="collapse-anchor"><a id="log-collapse">Other Info [+]</a></div>
   <div id="collapsible" class="invi-elem"> 
   <div class="corner-all innerbox-tbl" id="calls-to-this-lead">
      <div class="user-tbl">
        <div class='innerbox-title'><strong>Calls to this Lead</strong></div>
        <div class="user-hdr">
             <div class="user-tbl-cols">Date / Time</div>
             <div class="user-tbl-cols user-tbl-cols-centered">Length</div>
             <div class="user-tbl-cols">Status</div>
             <div class="user-tbl-cols">TSR</div>
             <div class="user-tbl-cols">Campaign</div>
             <div class="user-tbl-cols">List</div>
             <div class="user-tbl-cols">Lead</div>
             <div class="user-tbl-cols">Hangup Reason</div>
             <div class="user-tbl-cols">Phone</div>
             <br class="clear"/>
        </div>
        <div class="user-tbl-container">&nbsp;</div>
      </div>
   </div><br class="clear"/>
   <br class="clear"/>
   <div class="corner-all innerbox-tbl" id="closer-records">
      <div class="user-tbl">
        <div class='innerbox-title'><strong>Closer Records for this Lead</strong></div>
        <div class="user-hdr">
             <div class="user-tbl-cols">Date / Time</div>
             <div class="user-tbl-cols user-tbl-cols-centered">Length</div>
             <div class="user-tbl-cols">Status</div>
             <div class="user-tbl-cols">TSR</div>
             <div class="user-tbl-cols">Campaign</div>
             <div class="user-tbl-cols">List</div>
             <div class="user-tbl-cols">Lead</div>
             <div class="user-tbl-cols">Wait</div>
             <div class="user-tbl-cols">Hangup Reason</div>
             <br class="clear"/>
        </div>
        <div class="user-tbl-container">&nbsp;</div>
      </div>
   </div><br class="clear"/>
   <br class="clear"/>
   <div class="corner-all innerbox-tbl" id="agent-log">
      <div class="user-tbl">
        <div class='innerbox-title'><strong>Agent Log Records for this Lead</strong></div>
        <div class="user-hdr">
             <div class="user-tbl-cols " style="width:18%">Date / Time</div>
             <div class="user-tbl-cols user-normalcols">Campaign</div>
             <div class="user-tbl-cols user-normalcols">TSR</div>
             <div class="user-tbl-cols user-smallcols">Pause</div>
             <div class="user-tbl-cols user-smallcols">Wait</div>
             <div class="user-tbl-cols user-smallcols">Talk</div>
             <div class="user-tbl-cols user-smallcols">Dispo</div>
             <div class="user-tbl-cols user-smallcols">Status</div>
             <div class="user-tbl-cols">Group</div>
             <div class="user-tbl-cols">Sub</div> 
             <br class="clear"/>
        </div>
        <div class="user-tbl-container">&nbsp;</div>
      </div>
   </div><br class="clear"/>
   <br class="clear"/>
   <div class="corner-all innerbox-tbl" id="recording">
      <div class="user-tbl">
        <div class='innerbox-title'><strong>Recordings for this Lead</strong></div>
        <div class="user-hdr">
             <div class="user-tbl-cols">Lead</div>
             <div class="user-tbl-cols">Date / Time</div>
             <div class="user-tbl-cols user-tbl-cols-centered">Seconds</div>
             <div class="user-tbl-cols">RecId</div>
             <div class="user-tbl-cols">Filename</div>
             <div class="user-tbl-cols">Location</div>
             <div class="user-tbl-cols">TSR</div>
             <br class="clear"/>
        </div>
        <div class="user-tbl-container">&nbsp;</div>
      </div>
   </div><br class="clear"/>
   <br class="clear"/>
   </div>
   <!-- E n d -->
   <div class="search-action"><a id="leadinfo_download">Download lead info</a></div>
</div>
</div> <!-- wpwrap -->
<br/>
