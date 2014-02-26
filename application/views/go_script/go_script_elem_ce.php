<?php
############################################################################################
####  Name:             go_script_elem_ce.php                                           ####
####  Type:             ci view (template for users)                                    ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
?>
<script type="text/javascript" src="<?=base_url()?>js/jquery-validate/jquery.validate.min.js"></script>
<!-- <script type="text/javascript" src="<?=base_url()?>js/go_common_ce.js"></script> -->
<script type="text/javascript" src="<?=base_url()?>js/go_script/go_script_ce.js"></script>
<script>
   $(function(){
       //$("#script-table").tablePagination();
       if($("#script-table > tbody").children().size() > 0){
           $("#script-table").tablesorter({sortList:[[0,0]], headers: { 5: { sorter: false}, 6: {sorter:false}}});
       }
   });
</script>

<table class="tablesorter elem-tbl" id="script-table">
    <thead>
         <tr class="elem-tbl-rows">
           <th class="elem-tbl-cols sort">&nbsp;&nbsp;<span>SCRIPT ID<?#=img('js/tablesorter/themes/blue/bg.gif')?></span></th>
           <?php
           if($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))){
           ?>
           <th class="elem-tbl-cols sort"><span>SCRIPT NAME<?#=img('js/tablesorter/themes/blue/bg.gif')?></span></th>
           <th class="elem-tbl-cols elem-tbl-cols-centered sort"><span>&nbsp;<?#=img('js/tablesorter/themes/blue/bg.gif')?></span></th>
           <?php
           } else {
           ?>
           <th class="elem-tbl-cols sort" style="width:27.3%"><span>SCRIPT NAME<?#=img('js/tablesorter/themes/blue/bg.gif')?></span></th>
           <?php
           }
           ?>
           <th class="elem-tbl-cols elem-tbl-cols-centered sort"><span>STATUS</span></th>
           <th class="elem-tbl-cols elem-tbl-cols-centered sort"><span>TYPE</span></th>
           <?php
           if(!$this->commonhelper->checkIfTenant($this->session->userdata('user_group'))){
           ?>
           <th class="elem-tbl-cols sort" style="width:5.3%"><span>USER GROUP</span></th>
           <?php
           }
           ?>
           <th class="elem-tbl-cols elem-tbl-cols-centered">
                <div class="elem-cols-action-lbl">
                    <span>&nbsp;ACTION &nbsp;<?=img("img/arrow_down.png")?></span>
                </div>
           </th>
           <th class="elem-tbl-cols elem-tbl-cols-centered elem-tbl-cols-chkbox">
                <!-- <div class="elem-cols-action-chkbox"> -->
                    <input type="checkbox" id="batch-process"/>
                <!-- </div> -->
           </th>
         </tr>
    </thead>
    <tbody>
          <?php
            if($results->num_rows>0){
                 $ctr=0;
                 foreach($results->result() as $rows){
                     if(($ctr%2)==0){
                         $color = "elem-tbl-rows-odd";
                     }else{
                         $color = "elem-tbl-rows-even";
                     }
                     echo "<tr class='elem-tbl-rows $color' id='$rows->script_id'>";
                          echo "<td class='elem-tbl-cols scripts-action'>&nbsp;&nbsp;".(!empty($limeresult)?(($limeresult[$rows->script_id] > 0)?"<a id='$rows->script_id-advance'>$rows->script_id</a>":"<a id='$rows->script_id-default'>$rows->script_id</a>"): '')."</td>";
                          if($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) {
                             echo "<td class='elem-tbl-cols'>$rows->script_name</td>";
                             echo "<td class='elem-tbl-cols elem-tbl-cols-centered'>&nbsp;</td>";
                          } else {
                             echo "<td class='elem-tbl-cols' style='width:27.3%'>$rows->script_name</td>";
                          }
                          echo "<td class='elem-tbl-cols elem-tbl-cols-centered'>".$this->commonhelper->statusstringconvert($rows->active)."</td>";
                          echo "<td class='elem-tbl-cols elem-tbl-cols-centered scripts-action'>".(!empty($limeresult)?(($limeresult[$rows->script_id] > 0)?"Advance":"Default"): '')."</td>";
                          if(!$this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) {
                             echo "<td class='elem-tbl-cols' style='width:5.3%'>";
                             echo ($rows->user_group == "---ALL---") ? "ALL USER GROUPS" : $rows->user_group;
                             echo "</td>";
                          }
                          echo "<td class='elem-tbl-cols '>
                                   <div class='cols-action-container'>
                                       <div class='cols-action'><a class='toolTip' title='Info script $script_id'>".img(array('src'=>'img/status_display_i_grayed.png','width'=>'12px'))."</a></div>
                                       <div class='cols-action'><a id='$rows->script_id-delete' class='toolTip' title='Delete script $rows->script_id'>".img(array('src'=>'img/delete.png','width'=>'12px'))."</a></div>
                                       <div class='cols-action'><a id='$rows->script_id-update' class='toolTip' title='Modify script $rows->script_id'>".img(array('src'=>'img/edit.png','width'=>'12px'))."</a></div>
                                   </div>
                                </td>";
                          echo "<td class='elem-tbl-cols elem-tbl-cols-centered elem-tbl-cols-chkbox'>
                                   <!-- <div class='cols-action-container'> -->
                                       <div class='cols-action' style='padding:0;margin:0;'><input type='checkbox' class='batch' id='check-$rows->script_id'/></div>
                                   <!-- </div> -->
                                </td>";
                     echo "</tr>";
                     $ctr++;
                 }
            }
          ?>
    </tbody>
</table>
<?=$pageinfo ?>
<?=$pagelinks ?>
<div class="batch-actions scripts-cornerall2">
   <br class="clear"/>
   <span style="width:100%"><a id='active-selected'>Enable Selected</a></span><br class="clear"/><br class="clear"/><br class="clear"/><br class="clear"/>
   <span style="width:100%"><a id='deactivate-selected'>Disable Selected</a></span><br class="clear"/><br class="clear"/><br class="clear"/><br class="clear"/>
   <span style="width:100%"><a id='delete-selected'>Delete Selected</a></span><br class="clear"/><br class="clear"/><br class="clear"/>
</div>
