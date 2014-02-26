<?php
############################################################################################
####  Name:             go_freshdesk.php                                                ####
####  Type: 		ci views                                                        ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Christopher Lomuntad <chris@goautodial.com>   ####
####  License:          AGPLv2                                                          ####
############################################################################################
?>

<link href="<?=base_url()?>css/go_common_ce.css" rel="stylesheet" type="text/css"/>
<link href="<?=base_url()?>css/go_support/go_support_ce.css" rel="stylesheet" type="text/css"/>
<link href="<?=base_url()?>js/uploadify/uploadify.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="<?=base_url()?>js/go_common_ce.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/go_support/go_support.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/uploadify/jquery.uploadify-3.1.min.js"></script>

<script>

   <?php
         #require("/var/www/html/staticvars.php")
         $VARURL_HTTP = "https://{$_SERVER['HTTP_HOST']}/";
   ?>
   $(function(){

       $("#attachment").uploadify({
            'swf' : '<?=$VARURL_HTTP?>js/uploadify/uploadify.swf',
            'uploader' : '<?=$VARURL_HTTP?>js/uploadify/uploadify.php',
            'fileTypeExts' : "*.png; *.gif; *.csv",
            'fileSizeLimit': '100KB',
            'preventCaching' : false,
            'multi' : false,
            'removeCompleted':true,
            'formData' : {'account' : '<?=$account?>','session':'<?=$this->session->userdata('session_id')?>'}
       });

       $(".SnapABug_Button").hide();

   });

</script>

<script type="text/javascript">
document.write(unescape("%3Cscript src='" + ((document.location.protocol=="https:")?"https://snapabug.appspot.com":"http://www.snapengage.com") + 
           "/snapabug.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<script type="text/javascript">
/*SnapABug.setButton("https://justgocloud.com/img/images/livechat.png");
SnapABug.setButtonEffect('-4px');*/
SnapABug.addButton("d04093d4-3146-4fa7-811d-9475a3b6e94c","0","40%");
</script>

<div id='outbody' class="wrap">
<div id="icon-support" class="icon32"></div>
<h2><? echo $bannertitle; ?></h2>
<div id="dashboard-widgets-wrap">
     <div id="dashboard-widgets" class="metabox-holder">
          <div class="postbox-container support-container">
               <div class="meta-box-sortables ui-sortable">
                    <!-- Listings of all submitted tickets -->
                    <div id="freshdesk_widget" class="postbox minimum">
                         <div class='support-add-container'><a id="support-add">New Ticket <?#=img('img/cross.png')?></a></div>
                         <h3 class="hndle"><span>Submitted Tickets</span></h3>
                         <div class="inside">
                              <p class="submenu" align="center">
                                   <a class="submenu" href="javascript:filterticket('<?=(is_object($acct_email)?$acct_email->me['string']:$acct_email)?>',1);" rel="all">All Tickets</a> &nbsp;&diams;&nbsp; 
                                   <a class="submenu" href="javascript:filterticket('<?=(is_object($acct_email)?$acct_email->me['string']:$acct_email)?>',2)" rel="open_pending">Open Or Pending</a> &nbsp;&diams;&nbsp; 
                                   <a class="submenu" href="javascript:filterticket('<?=(is_object($acct_email)?$acct_email->me['string']:$acct_email)?>',3);" rel="resolved_closed">Resolved Or Closed</a>
                              </p>
                              <div class="table table_submitted_tickets" id="table_submitted_tickets"></div>
                              <br class="clear">
                         </div>
                    </div>
               </div>
          </div>
     </div><!-- dashboard-widgets -->
</div><!-- dashboard-widgets-wrap -->
<div class="overlay">
     <div class="overlay-container"></div>
</div>
<div class="corner-all support-wizard-container">
   <div class="overlay-closer"></div>
   <div class="wizard-holder">
     <div class="support-wizard-steps">
          <div class="support-wizard-breadcrumb">
              <div class="wizard-title">
                  <strong>Create New Ticket</strong>
              </div>
              <?=img(array('src'=>'img/step1-nav-small.png','id'=>'support-wizard-breadcrumb'))?>
              <br class="clear"/>
          </div>
          <div class="support-wizard-content">
                 <div class="support-labels re-width">
                    <?=img(array('src'=>'img/step1-trans.png','id'=>'wizard-step'))?>
                 </div>
                 <div class="support-values">
                      <?=form_open(null,"id='newticket' enctype='multipart/form-data' ")?>
                           <div class="support-labels support-labels-rewidth">VOIP/Cloud Account Number *</div>
                           <div class="support-values support-values-rewidth">
                                <?=form_input('accountNum',(is_object($cc_card->username)?$cc_card->username->me['string']:$cc_card->username),"id='accountNum' size='30' class='escape' readonly")?>
                           </div><br class="clear"/>
                           <div class="support-labels support-labels-rewidth">First Name *</div>
                           <div class="support-values support-values-rewidth">
                                <?=form_input('firstName',(is_object($cc_card->firstname)?$cc_card->firstname->me['string']:$cc_card->firstname),"id='firstName' size='30' class='escape'")?>
                           </div><br class="clear"/>
                           <div class="support-labels support-labels-rewidth">Last Name *</div>
                           <div class="support-values support-values-rewidth">
                                <?=form_input('lastName',(is_object($cc_card->lastname)?$cc_card->lastname->me['string']:$cc_card->lastname),"id='lastName' size='30' class='escape'")?>
                           </div><br class="clear"/>
                           <div class="support-labels support-labels-rewidth">Email *</div>
                           <div class="support-values support-values-rewidth">
                                <?=form_input('accntemail',(is_object($acct_email)?$acct_email->me['string']:$acct_email),"id='accntemail' size='30' class='escape' readonly")?>
                           </div><br class="clear"/>
                           <div class="support-labels support-labels-rewidth">Subject *</div>
                           <div class="support-values support-values-rewidth">
                                <?=form_input('subject',null,"id='subject' size='30'")?>
                           </div><br class="clear"/>
                           <div class="support-labels support-labels-rewidth">Type *</div>
                           <div class="support-values support-values-rewidth">
                                    <?php
                                      $type = array(
                                                    "Question's"=>"Question's",
                                                    "Dialer Issue"=>"Dialer Issue",
                                                    "Phone Registration"=>"Phone Registration",
                                                    "VOIP Issue"=>"VOIP Issue");
                                      echo form_dropdown('type',$type,null,"id='type'");
                                    ?>
                           </div><br class="clear"/>
                           <div class="support-labels support-labels-rewidth">Attachment</div>
                           <div class="support-values support-values-rewidth">
                                <input type="file" class="corner-all" name="attachment" id="attachment">
                           </div><br class="clear"/>
                           <!-- <div class="support-labels">Group *</div>
                           <div class="support-values">
                                  <?php
                                      /*$group = array(""=>"...",
                                                     '20572'=>'Paid Support',
                                                     "20551"=>"Question's",
                                                     "20549"=>"Dialer Issue",
                                                     "23822"=>"Phone Registration",
                                                    );
                                      echo form_dropdown('group',$group,null,"id='group'");*/
                                  ?>
                           </div><br class="clear"/> -->
                           <div class="support-labels support-labels-rewidth">Description *</div>
                           <div class="support-values support-values-rewidth">
                                <?=form_textarea(array("name"=>"description","id"=>"description","cols"=>"43","rows"=>'10'))?>
                           </div><br class="clear"/>
                      <?=form_close()?>
                 </div>
                 <br class="clear"/>
          </div>
          <br class="clear"/>
          <div class="support-wizard-action"><a id="step-next" rel="2" >Create</a></div>
     </div>
     <br class="clear"/>
   </div>
</div>

</div><!-- wrap -->
<?php
echo "<div id='popup_analytics_all' class='popup_block'>\n";
?>

			<div class="table go_dashboard_analytics_in_popup_cmonth_daily" id="go_dashboard_analytics_in_popup_cmonth_daily">
			</div>
	
			<div class="table go_dashboard_analytics_in_popup_weekly" id="go_dashboard_analytics_in_popup_weekly">
			</div>

			<div class="table go_dashboard_analytics_in_popup_hourly" id="go_dashboard_analytics_in_popup_hourly">
			</div>

<?
echo "</div>\n";
?>
<!-- 






                                  <div style="float:left;width:50%">

                                  <br class="clear">
                                  <div style="text-align:right;margin-top:10px;">
                                        <input type="submit" value="Submit" id="submit">
                                  </div>
-->




<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->
<div class="clear"></div></div><!-- wpcontent -->
</div><!-- wpwrap -->
