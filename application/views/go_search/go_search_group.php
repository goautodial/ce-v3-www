<?Php
############################################################################################
####  Name:             go_user_group.php                                               ####
####  Type:             ci view display users by group                                  ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
?>
<!-- <script src="../../../js/jquery-validate/jquery.validate.min.js"></script> -->
<script src="../../../js/go_search/go_search.js"></script>
<div class="group-container">
<?php 
    #foreach($bygroup as $groups){ 
    #var_dump($groups[$ctr]);echo '<br/>';
       if($bygroup[0]->users_level > 8){
           foreach($bygroup[1] as $groups){
           #echo $groups[0]->users_name.'<pre>';var_dump($groups[1]);die('</pre>');
?>
                 <div class="groups closed" id="groups-<?=$groups[0]->users_id?>">
                     <div class="group-toggle" id="toggle-<?=$groups[0]->users_id?>"></div>
                     <h3><?='('.$groups[0]->users_id.') '.$groups[0]->users_name?></h3>
                     <div class="groupsuser" id="<?=$groups[0]->users_id?>">
                         <?php
                               $this->userhelper->loopinglink($groups[1]);
                          ?>
                     </div>
                  </div> 
<?php
           }
       }else{
            #echo '<pre>';var_dump($bygroup);die('</pre>');
?>
                 <div class="groups closed" id="groups-AGENTS">
                     <div class="group-toggle" id="toggle-AGENTS"></div>
                     <h3>AGENTS</h3>
                     <div class="groupsuser" id="AGENTS">
                         <?php
                               echo "<ul>";
                               foreach($bygroup[1] as $user){
                                   if(is_array($user)){
                                       if($user[0]->users_level<7){
                                           echo "<li><a href='javascript:return false;' id='hierarchy-".$user[0]->users_id."'>(".$user[0]->users_id.")".$user[0]->users_name."</a></li>";
                                       }
                                   }else{
                                       echo "<li><a href='javascript:return false;' id='hierarchy-".$user->users_id."'>(".$user->users_id.")".$user->users_name."</a></li>";
                                   }
                               }
                               echo "</ul>";
                          ?>
                     </div>
                 </div>
                 <div class="groups closed" id="groups-SUPTLIT">
                     <div class="group-toggle" id="toggle-SUPTLIT"></div>
                     <h3>SUP/TL/IT</h3>
                     <div class="groupsuser" id="SUPTLIT">
                         <?php
                               echo "<ul>";
                               foreach($bygroup[1] as $user){
                                   if(is_array($user)){
                                       if($user[0]->users_level==7){
                                           echo "<li><a href='javascript:return false;' id='hierarchy-".$user[0]->users_id."'>(".$user[0]->users_id.")".$user[0]->users_name."</a></li>";
                                       }
                                   }else{
                                       echo "<li><a href='javascript:return false;' id='hierarchy-".$user->users_id."'>(".$user->users_id.")".$user->users_name."</a></li>";
                                   }
                               }
                               echo "</ul>";
                          ?>
                     </div>
                  </div>
<?php
       }
    #}
?>
</div>
