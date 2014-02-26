<?php
############################################################################################
####  Name:             go_freshdesk_output.php                                         ####
####  Type: 		    ci views                                                    ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Januarius Manipol <info@goautodial.com>       ####
####                                      Christopher Lomuntad  <info@goautodial.com>   ####
####                                      Franco E. Hora  <info@goautodial.com>         ####
####  License:          AGPLv2                                                          ####
############################################################################################
?>

<!-- accordion -->
<script src="<?=base_url()?>js/go_support/go_support_bodyjs.js"></script>

<div id="tickets" class='head'>
    <?php

         echo '<a href="#" onclick="return SnapABug.startLink();" style="float:right;"><img src="https://snapabug.appspot.com/statusImage?w=d04093d4-3146-4fa7-811d-9475a3b6e94c" alt="Contact us" border="0"></a><br class="clear"/>';
         if(count($data) == 0){
            echo "<script>
                      $(function(){
                        wizard($('.support-wizard-container'),0,300,20); 
                      });
                  </script>";
            echo "<div style='float:left;width:50%;'><strong style='color:red;font-style:italic;'>No ticket(s) found!</strong></div>";
         }


         if(isset($_POST)){
             $statustype = $_POST['statustype'];
             if(!empty($statustype)){
                 if($statustype == 2){
                     $displaythis = array(2,3);
                 }elseif($statustype == 3){
                     $displaythis = array(4,5);
                 }else{
                     $displaythis = array(2,3,4,5);
                 }
             }else{
                 $displaythis = array(2,3,4,5);
             }
         }else{
             $displaythis = array(2,3,4,5);
         }

         if(empty($data)){
              exit; # escape the display of tickets
         }

         $ctr = 0;
         foreach($data as $ticket) { 

              #$update_time = (is_null($ticket['created-at'])?null:new DateTime($ticket['created-at']));
              switch($ticket['status']){
       
                   case 2:
                           $status = "Open"; 
                   break;
                   case 3:
                           $status = "Pending"; 
                   break;
                   case 4:
                           $status = "Resolved";
                   break;
                   case 5:
                           $status = "Closed";
                   break;
                   default:
                           $status = "Open";
                   break;
                  
              }


              if(in_array($ticket['status'],$displaythis)){

                  if($ctr%2 == 0){

                      $class = "ticket-subject-odd";

                  } else {

                      $class = "ticket-subject-even";

                  }

    ?>
                  <div id="ticket" name="ticket" class="postbox <?=$class?>">
                      <div id="ticket-subject" name="ticket-subject">
                          <div class="headerholder">
                              <div class="headerinfo">
	                          <h1>
                                      <a href="javascript:slideonlyone('<?=$ticket['display-id']?>');" >
                                          <?=$ticket['subject'].'&nbsp;(#'.$ticket['display-id'] . ')'?>
                                      </a>
                                  </h1>
                                  <br classs="clear"/><small><?php echo 'from '.$ticket['requester-name'] #. " last updated on " .(is_null($update_time)?"":$update_time->format('F d @ h:i A'))?></small>
                              </div>
                              <div style="float:left" class="headerasignee">
                                  <?if(array_key_exists('responder-name',$ticket)):?>
                                        Assigned to <strong align="right"><?php echo $ticket['responder-name']?></strong>
                                        <br class="clear">
                                        <br class="clear">
                                  <?endif?>
                                  <small>Status <?=$status?></small>
                              </div>
                              <br class="clear"/>
                          </div>
                      </div>
	              <div id="<?=$ticket['display-id']?>" name="desc" class="desc close">
		          <p>
                              <?=$ticket['description-html']?>
		          </p>
                          <div class="conversation-container" id="conv-container-<?=$ticket['display-id']?>">
                          <?php

                              if(array_key_exists('helpdesk-note',$ticket['notes'])){
                                  foreach($ticket['notes']['helpdesk-note'] as $conversation){
                                      if(!preg_match('/KHTML/',$conversation['body-html'])){
                                          if(preg_match('/Goautodial/i',$conversation['user-info']['name'])){
                                              $goautodial = 'goautodial';
                                          }else{
                                              $goautodial = "";
                                          } 
                          ?>
                                             <div class="conversation <?=$goautodial?>" >
                                                   <div class="responder "><?=$conversation['user-info']['name']?></div>
                                                   <div class="response ">
                                                        <?php echo $conversation['body-html']?>
                                                   </div>
                                              </div>
                          <?          }
                                  }
                              }
                          ?>
                          </div>
                          <div class="ticket-action">
                              <?if($ticket['status'] < 5){?>
                                  <!-- <input type="button" value="Close this ticket" id="close-ticket_<?=$ticket['display-id']?>" class="close-ticket"> -->
                                  <input type="button" value="Add note" id="addnote_<?=$ticket['display-id']?>" class="callnote">
                              <?}?>
                         </div>
                         <div class="note-to-add" id="note_<?=$ticket['display-id']?>">
                              <?=form_textarea(array('id'=>"client-note_".$ticket['display-id'],'cols'=>'100','name'=>'client-note_'.$ticket['display-id']));?>
                              <div class="addnotebutton">
                                   <input type="hidden" value="<?=$ticket['requester-id']?>" id="requester-id-<?=$ticket['display-id']?>">
                                   <input type="button" value="Add note" class="add-note" id="add-note-<?=$ticket['display-id']?>">
                              </div>
                         </div>
                         <br class="clear"/>
                        </div>
                   </div>
       <?php 
              }

              $ctr++;
          }
       ?>
</div>
