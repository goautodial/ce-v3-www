<?php
############################################################################################
####  Name:             go_user_listall_ce.php                                          ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Franco E. Hora                                                  ####
####  Modified by:      Christopher P. Lomuntad - 25042013                              ####
####  License:          AGPLv2                                                          ####
############################################################################################
?>
<script src="<?=base_url()?>js/jquery-validate/jquery.validate.min.js"></script>
<script src="<?=base_url()?>js/go_common_ce.js"></script>
<script src="<?=base_url()?>js/go_user/go_user_ce.js"></script>
<script src="<?=base_url()?>js/go_user/go_user_body_ce.js"></script>
<script>
$(function(){
   //$("#user-list").tablePagination();
   if($("#user-list > tbody").children().size() > 0){

       <?if($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))){ ?>
                  $("#user-list").tablesorter({sortList:[[0,0]], headers: { 4: { sorter: false}}, headers: { 5: { sorter: false}}});
       <?php
           } else {
                echo   '$("#user-list").tablesorter({sortList:[[0,0]], headers: { 5: { sorter: false}}, headers: { 6: { sorter: false}}});';
           }
       ?>
   }
   
   if ('<?=$search_list ?>'.length > 0) {
	$("#search_list").val('<?=$search_list ?>');
   }
});
</script>

<div id="user-container">
<!--    <table class="tablesorter user-tbl" id="user-list" style="margin-top:1px;display:none;" >
         <thead>
         <tr class="user-hdr">
              <th class="user-tbl-cols " style='width:15%;margin-top:1px;'><span class='sorter'>AGENT ID<?#=img("js/tablesorter/themes/blue/bg.gif")?></span></th>
              <th class="user-tbl-cols " style='width:33%;margin-top:1px;'><span class='sorter'>AGENT NAME<?#=img("js/tablesorter/themes/blue/bg.gif")?></span></th>
              <th class="user-tbl-cols " style='width:10%;margin-top:1px;'><span class="sorter">LEVEL</span></th>
              <th class="user-tbl-cols " style='width:15%;margin-top:1px;'><span class="sorter">GROUP</span></th>
              <th class="user-tbl-cols user-tbl-cols-centered" style='width:8%;margin-top:1px;'>&nbsp;&nbsp;&nbsp;<span class="sorter">STATUS</span></th>
              <div class="user-tbl-cols <?#=($user_level>8?"user-tbl-cols-centered":"")?>"><strong>Links</strong></div> 
              <th class="user-tbl-cols user-tbl-cols-centered" style='width:14%;'>
                   <div class="user-cols-action-chkbx" style="margin-right: -34px;"><input type="checkbox" id="batch"/></div>
                   <div class="user-cols-action-lbl" style="white-space: nowrap; margin-right: 19px;margin-top:1px;"><span>ACTION &nbsp;<?=img("img/arrow_down.png")?></span></div>
              </th>
         </tr>
         </thead>
         <tbody>-->
         <?Php
             //$ctr=0;
             //foreach($users as $usersInfo){
             //    #if($user_level < 9){
             //         echo "<tr id='$usersInfo->user' class='user-tbl-rows ".(($ctr%2==0)?'user-odd':'user-even')."'>";
             //              echo "<td class='user-tbl-cols' style='width:16%;'><a class='action-id toolTip' title='Modify user $usersInfo->user' id='user-action-modify-$usersInfo->user' rel='$usersInfo->user_id'>$usersInfo->user</a></td>";
             //              echo "<td class='user-tbl-cols' style='width:34%;'><a class='action-id toolTip' title='Modify user $usersInfo->user' id='user-action-modify-$usersInfo->user' rel='$usersInfo->user_id'>$usersInfo->full_name</a></td>";
             //              echo "<td class='user-tbl-cols' style='width:9.5%;'>$usersInfo->user_level</td>";
             //              echo "<td class='user-tbl-cols' style='width:10%;'>$usersInfo->user_group</td>";
             //              echo "<td class='user-tbl-cols user-tbl-cols-centered' style='width:16.5%;'>".(($usersInfo->active=='Y')?"<span class='active'>ACTIVE</span>":"<span class='inactive'>&nbsp;&nbsp;&nbsp;&nbsp;INACTIVE</span>")."</td>";
             //              echo "<td class='user-tbl-cols user-tbl-cols-centered' style='width:14%;margin-top:2px;'>
             //                      <div class='user-cols-container'>
             //                            <div class='user-actions-cols' style='margin-top:-2px;margin-left:-1px;'>
             //                                <input type='checkbox' id='user-action-chkbx-$usersInfo->user' value='$usersInfo->user' rel='$usersInfo->user_id' />
             //                            </div>
             //                            <div class='user-actions-cols' style='padding-left:1px;'><a id='user-action-info-$usersInfo->user' rel='$usersInfo->user_id' title='Info user $usersInfo->user' class='toolTip'>".img("img/status_display_i.png")."</a>
             //                            </div>
             //                            <div class='user-actions-cols'>
             //                                <a id='user-action-delete-$usersInfo->user' rel='$usersInfo->user_id' title='Delete user $usersInfo->user' class='toolTip'>".img("img/delete.png")."</a>
             //                            </div>
             //                            <div class='user-actions-cols'>
             //                                <a id='user-action-modify-$usersInfo->user' rel='$usersInfo->user_id' title='Modify user $usersInfo->user' class='toolTip'>".img("img/edit.png")."</a>
             //                            </div>
             //                      </div>
             //                    </td>";
             //              echo "</tr>";
             //    $ctr++;
             //} 
         ?>
<!--         </tbody>             
    </table>-->
    
<table id="user-list" class="tablesorter" border="0" cellpadding="1" cellspacing="0" style="width:99.98%;margin-top:6px;">
	<thead>
        <tr style="font-weight:bold;text-align: left;">
            <th style="width:12%">&nbsp;&nbsp;AGENT ID</th>
            <th>&nbsp;&nbsp;AGENT NAME <?=$search_list ?></th>
            <th>&nbsp;&nbsp;LEVEL</th>
			<?php
			if (!$this->commonhelper->checkIfTenant($user_group)){
			?>
            <th>&nbsp;&nbsp;GROUP</th>
			<?php
			}
			?>
            <th>&nbsp;&nbsp;STATUS</th>
            <th colspan="3" style="width:6%;text-align:center;" nowrap>
               <div class="user-cols-action-lbl" style="display: inline;cursor: pointer;">&nbsp;<span>ACTION &nbsp;<img src="<?php echo $base; ?>img/arrow_down.png" /></span>&nbsp;</div></th>
            <th style="width:2.2%;text-align:center;"><div class="user-cols-action-chkbx" style="display: inline"><input type="checkbox" id="batch" /></div></th>
        </tr>
    </thead>
    <tbody>
<?php
//width of action on windows is 6.45% and 2% on the checkbox on its side
   $x=0;
   foreach ($users as $row)
   {
      if ($row->user_level <= $user_level)
      {
         if ($x==0) {
            $bgcolor = "#E0F8E0";
            $x=1;
         } else {
            $bgcolor = "#EFFBEF";
            $x=0;
         }
 
         if ($row->active == 'Y')
         {
            $active = '<span style="color:green;font-weight:bold;">ACTIVE</span>';
         } else {
            $active = '<span style="color:#F00;font-weight:bold;">INACTIVE</span>';
         }
         
         echo "<tr style=\"background-color:$bgcolor;\" class='user-tbl-rows'>\n";
         echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<a class='action-id toolTip' style='cursor:pointer' title='Modify user $row->user' id='user-action-modify-".$row->user."' rel='$row->user_id'>".$row->user."</a></td>\n";
         echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;<a class='action-id toolTip' style='cursor:pointer' title='Modify user $row->user' id='user-action-modify-".$row->user."' rel='$row->user_id'>".str_replace("-","&#150;",$row->full_name)."</a></td>\n";
         echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$row->user_level."</td>\n";
		 if (!$this->commonhelper->checkIfTenant($user_group)){
			echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$row->user_group."</td>\n";
		 }
         echo "<td style=\"border-top:#D0D0D0 dashed 1px;\">&nbsp;&nbsp;".$active."</td>\n";
         echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><div class='user-actions-cols' style='display:inline;text-align:center;cursor:pointer;'><a id='user-action-modify-$row->user' rel='$row->user_id' title='Modify user $row->user' class='toolTip'><img src=\"{$base}img/edit.png\" style=\"cursor:pointer;width:12px;\" /></a></div></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><div class='user-actions-cols' style='display:inline;text-align:center;cursor:pointer;'><a id='user-action-delete-$row->user' rel='$row->user_id' title='Delete user $row->user' class='toolTip'><img src=\"{$base}img/delete.png\" style=\"cursor:pointer;width:12px;\" /></a></div></td><td align=\"center\" style=\"border-top:#D0D0D0 dashed 1px;\"><div class='user-actions-cols' style='display:inline;text-align:center;cursor:pointer;'><a id='user-action-info-".$row->user."' rel='$row->user_id' title='Info user $row->user' class='toolTip'><img src=\"{$base}img/status_display_i.png\" style=\"cursor:pointer;width:12px;\" /></a></div></td>\n";
         echo "<td style=\"border-top:#D0D0D0 dashed 1px;\" align=\"center\"><div class='user-cols-container' style='display:inline'><div class='user-actions-cols' style='display:inline'><input type='checkbox' id='user-action-chkbx-$row->user' value='$row->user' rel='$row->user_id' /></div></div></td>\n";
         echo "</tr>\n";
      }
   }
?>
	</tbody>
</table>
    
    <div class="user-batch-action user-cornerall2">
       <span style="width:100%"><a id="user-batch-activate">Enable Selected</a></span><br/>
       <span style="width:100%"><a id="user-batch-deactivate">Disable Selected</a></span><br/>
       <span style="width:100%"><a id="user-batch-delete">Delete Selected</a></span><br/>
    </div>
    <br class="clear"/>
</div>
