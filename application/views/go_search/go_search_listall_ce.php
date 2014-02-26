<!--<style>@import('../../../css/tooltip/tipTip.css');</style>
<script src="../../../js/tooltip/jquery.tipTip.js"></script> -->
<script src="../../../js/jquery-validate/jquery.validate.min.js"></script>
<script src="../../../js/go_common_ce.js"></script>
<script src="../../../js/go_search/go_search_ce.js"></script>

<script src="<?=base_url()?>js/jquery-validate/jquery.validate.min.js"></script>
<script src="<?=baes_url()?>js/go_common_ce.js"></script>
<script src="<?=base_url()?>js/go_search/go_search_ce.js"></script>
<!-- <div class="user-search-container">
    <div class="formsearch">
          <input type="text" name="user-search" id="user-search"><a href="javascript:void(0);" id="searchcall"><span class="search-spacer">&nbsp;</span></a>
    </div>
</div> -->
<br class="clear"/>
<div id="user-container">
    <div class="user-tbl" >
        <div class="user-hdr">
              <div class="user-tbl-cols "><strong class='sorter'>Agent ID<?#=img("js/tablesorter/themes/blue/bg.gif")?></strong></div>
              <div class="user-tbl-cols "><strong class='sorter'>Agent Name<?#=img("js/tablesorter/themes/blue/bg.gif")?></strong></div>
              <div class="user-tbl-cols user-tbl-cols-right"><strong>Status</strong></div>
              <!-- <div class="user-tbl-cols <?#=($user_level>8?"user-tbl-cols-centered":"")?>"><strong>Links</strong></div> -->
              <div class="user-tbl-cols user-tbl-cols-centered">
                   <div class="user-cols-action-chkbx"><input type="checkbox" id="batch" disabled="disabled"/></div>
                   <div class="user-cols-action-lbl"><strong>Actions&nbsp;<img src="../../../img/arrow_down.png"></strong></div>
              </div>
              <br class="clear"/>
        </div>
        <div class="user-tbl-container">
        <?Php
             $ctr=0;
             foreach($users as $usersInfo){
                 #if($user_level < 9){
                      echo "<div id='$usersInfo->user' class='user-tbl-rows ".(($ctr%2==0)?'user-odd':'user-even')."'>";
                           echo "<div class='user-tbl-cols'>$usersInfo->user</div>";
                           echo "<div class='user-tbl-cols'>$usersInfo->full_name</div>";
                           echo "<div class='user-tbl-cols user-tbl-cols-right'>".(($usersInfo->active=='Y')?"<span class='active'>Active</span>":"<span class='inactive'>Inactive</span>")."</div>";
                           #echo "<div class='user-tbl-cols ".($user_level>8?"user-tbl-cols-centered":"")."'><a id='user-status-$usersInfo->user'>Status</a>".($user_level>8?" | <a id='user-adv-$usersInfo->user'>Advance</a>":"")."</div>";
                           echo "<div class='user-tbl-cols'>
                                   <div class='user-cols-container'>
                                         <div class='user-actions-cols'>
                                             <input type='checkbox' id='user-action-chkbx-$usersInfo->user' value='$usersInfo->user' rel='$usersInfo->user_id' disabled='disabled'/>
                                         </div>
                                         <div class='user-actions-cols'>
                                             <a id='user-action-info-$usersInfo->user' rel='$usersInfo->user_id' title='info' class='toolTip'>".img("img/status_display_i.png")."</a>
                                         </div>
                                         <div class='user-actions-cols'>
                                             <a id='user-action-delete-$usersInfo->user' rel='$usersInfo->user_id' title='delete' class='toolTip'>".img("img/delete.png")."</a>
                                         </div>
                                         <div class='user-actions-cols'>
                                             <a id='user-action-modify-$usersInfo->user' rel='$usersInfo->user_id' title='modify' class='toolTip'>".img("img/edit.png")."</a>
                                         </div>
                                   </div>
                                 </div>";
                           echo "<br style='font-size:1.8em;'/>
                             </div>";
                 #}else{
                 #      if($group != $usersInfo->user_group ){
                 #      }else{
                 #          $group = $usersInfo->user_group;
                 #      }
                 #      var_dump($usersInfo); die; 
                 #}
                 $ctr++;
             } 
        ?>
        </div>
        <div class="pager-container">
            <span class="pager-perpage"><?=form_dropdown('page-dropdown',array(25=>25,50=>50,100=>100,"all"=>"All"))?>&nbsp;per page</span>
            <span class="pager-paginater">
                 <?=img(array('id'=>"pager-firstpage","src"=>'img/first.gif'))."&nbsp;".img(array("id"=>"pager-prevpage","src"=>"img/prev.gif"))?>
                 <?="Page&nbsp;".form_input('pager-currpage',1,'id="pager-currpage" size="2" readonly')."&nbsp;of&nbsp;"."<span id='pager-totalpage'>&nbsp;</span>"?>
                 <?=img(array('id'=>"pager-nextpage","src"=>'img/next.gif'))."&nbsp;".img(array("id"=>"pager-lastpage","src"=>"img/last.gif"))?>
            </span>
        </div>
        <br class="clear"/>
    </div>
    <div class="user-batch-action user-cornerall">
       <a id="user-batch-activate">Active Selected</a><br class="clear"/>
       <a id="user-batch-deactivate">Deactive Selected</a><br class="clear"/>
       <a id="user-batch-delete">Delete Selected</a><br class="clear"/>
    </div>
    <br class="clear"/>
</div>
