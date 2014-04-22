<!--<style>@import('../../../css/tooltip/tipTip.css');</style>
<script src="../../../js/tooltip/jquery.tipTip.js"></script> -->
<!--<script src="../../../js/jquery-validate/jquery.validate.min.js"></script>
<script src="../../../js/go_common_ce.js"></script>
<script src="../../../js/go_search/go_search.js"></script>-->
<!-- <div class="user-search-container">
    <div class="formsearch">
          <input type="text" name="user-search" id="user-search"><a href="javascript:void(0);" id="searchcall"><span class="search-spacer">&nbsp;</span></a>
    </div>
</div> -->

<!-- <script src="<?=base_url()?>js/jquery-validate/jquery.validate.min.js"></script> -->
<script src="<?=base_url()?>js/go_common_ce.js"></script>
<script src="<?=base_url()?>js/go_search/go_search_ce.js"></script>
<script>
$(function(){
   //$("#search-list").tablePagination();
    $(".recordings").jmp3({
        showfilename: "false",
        backcolor: "00ff00",
        forecolor: "000000",
        showdownload: "true",
        volume: 100,
        width: 80
    });
});
<?php
    
      if(count($users) == 0 ){
                 echo '
                         $(function(){
                            wizard($(".message-box"));
                         });
                       ';
      } else {
                 echo '
                         $(function(){
                            closer($(".message-box"));
                         });
                       ';
                 echo '$("#search-list").tablesorter({sortList:[[0,0]], headers: { 7 : { sorter: false}}});';
      }
?>
</script>

<br class="clear"/>
<span class="recordings" style="display: none">sound.mp3</span>
<div id="user-container">
   <table class="tablesorder user-tbl" id="search-list">
        <thead>
            <tr class="user-hdr">
               <th class="user-tbl-cols "><span class='sorter'>LEAD ID<?#=img("js/tablesorter/themes/blue/bg.gif")?></span></th>
               <th class="user-tbl-cols "><span class='sorter'>PHONE<?#=img("js/tablesorter/themes/blue/bg.gif")?></span></th>
               <th class="user-tbl-cols "><span class='sorter'>CALL DATE<?#=img("js/tablesorter/themes/blue/bg.gif")?></span></th>
               <th class="user-tbl-cols "><span class='sorter'>&nbsp;&nbsp;Duration<?#=img("js/tablesorter/themes/blue/bg.gif")?></span></th>
               <th class="user-tbl-cols"><span class='sorter'>Agent<?#=img("js/tablesorter/themes/blue/bg.gif")?></span></th>
               <th class="user-tbl-cols  search-tbl-name"><span class='sorter'>CALL DISPOSITION<?#=img("js/tablesorter/themes/blue/bg.gif")?></span></th>
               <th class="user-tbl-cols user-tbl-cols-centered search-tbl-recording"><span class='sorter'>RECORDINGS<?#=img("js/tablesorter/themes/blue/bg.gif")?></strong></th>
               <th class="user-tbl-cols user-tbl-cols-centered search-tbl-defleted">
                   <div class="user-cols-action-chkbx"><input type="checkbox" id="batch" disabled="disabled"/></div>
                   <div class="user-cols-action-lbl"><span>&nbsp;&nbsp;ACTION&nbsp;&nbsp;&nbsp;</span></div>
               </th>              
            </tr>
        </thead>
        <tbody>
        <?Php
             if(count($users) == 0 ){
       
                   echo "<tr class='user-tbl-rows user-even'><td colspan='8'><strong style='color:red;font-style:italic;'>No record(s) found!</strong></td></tr>"; 
             }

             $ctr=0;
             foreach($users as $usersInfo){
                  $disposition = (strlen($usersInfo->disposition) > 0) ? $usersInfo->disposition : $disposition;
                  $disposition = (strlen($usersInfo->campaign_dispo) > 0) ? $usersInfo->campaign_dispo : $disposition;
                 #if($user_level < 9){
                      echo "<tr id='$usersInfo->lead_id' class='user-tbl-rows ".(($ctr%2==0)?'user-odd':'user-even')."'>";

                           echo "<td class='user-tbl-cols'><a class='search_leadinfo' id='".$ctr."'>$usersInfo->lead_id</a></td>";
                           echo "<td class='user-tbl-cols'>$usersInfo->phone</td>";
                           echo "<td class='user-tbl-cols'>$usersInfo->call_date</td>";
                           echo "<td class='user-tbl-cols'>&nbsp;&nbsp;".(($usersInfo->duration>0)?$usersInfo->duration:0)."</td>";

                           echo "<td class='user-tbl-cols'>".(strlen($usersInfo->agent) > 20 ? substr($usersInfo->agent,0,20)."..." : $usersInfo->agent)."</td>";
               
                           echo "<td class='user-tbl-cols search-tbl-name'>".((strlen($disposition) > 26)?substr($disposition,0,23)."...":$disposition)."</td>";
                           echo "<td class='user-tbl-cols user-tbl-cols-centered search-tbl-recording'>".(!is_null($usersInfo->location)?img("img/g_status_ok.png"):img("img/g_status_no.png"))."</td>";

                         # echo "<div class='user-tbl-cols ".($user_level>8?"user-tbl-cols-centered":"")."'><a id='user-status-$usersInfo->user'>Status</a>".($user_level>8?" | <a id='user-adv-$usersInfo->user'>Advance</a>":"")."</div>";
                         $image_properties = array(
                                    'src' => 'img/download.png',
                                    'style' => 'padding-top:1px'
                          );
                          echo "<td class='user-tbl-cols search-tbl-defleted'>
                                  <div class='user-cols-container'>
                                        <div class='user-actions-cols'>
                                            &nbsp;&nbsp;&nbsp;<input type='checkbox' id='user-action-chkbx-$usersInfo->lead_id' value='$usersInfo->user' rel='$usersInfo->lead_id' disabled='disabled'/>
                                        </div>
                                        <div class='user-actions-cols'>
                                            <a id='user-action-info-$usersInfo->lead_id' href='".base_url()."index.php/go_search_ce/download/$usersInfo->lead_id/rec/$usersInfo->recording_id' target='new' title='download' class='toolTip'>".img("img/download.png")."</a>
                                        </div>
                                        <div class='user-actions-cols' style='width:48%'>
                                            <span class='recordings' style='color:".(($ctr%2==0)?'#E0F8E0':'#EFFBEF')."'>".str_replace('http:','http:',$usersInfo->location)."</span>
                                        </div>
                                        <div class='user-actions-cols'>
                                          <!--  <a id='user-action-modify-$usersInfo->filename' rel='$usersInfo->filename' title='modify' class='toolTip'>".img("img/edit.png")."</a>-->
                                        </div>
                                  </div>
                                </td>";
                     echo "</tr>";
                 $ctr++;
             } 
        ?>
        </tbody>
   </table>
   <div style="padding-top:5px;">
      <span style="float: right;"><?=$pagedisplay ?></span>
      <span><?=$pagelinks ?></span>
   </div>
    <div id="download"></div>
    <div class="user-batch-action user-cornerall">
       <a id="user-batch-activate">Active Selected</a><br class="clear"/>
       <a id="user-batch-deactivate">Deactive Selected</a><br class="clear"/>
       <a id="user-batch-delete">Delete Selected</a><br class="clear"/>
    </div>
</div>
