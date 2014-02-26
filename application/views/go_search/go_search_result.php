
<script src="<?=base_url()?>js/jquery-validate/jquery.validate.min.js"></script>
<script src="<?=base_url()?>js/go_common_ce.js"></script>
<script src="<?=base_url()?>js/go_search/go_search_ce.js"></script>

<br class="clear"/>
<div id="user-container">
    <div class="user-tbl" >
        <div class="user-hdr">
           <?if(count($users) > 0){?>
              <div class="user-tbl-cols search_result_cols"><strong class='sorter'>Lead ID<?#=img("js/tablesorter/themes/blue/bg.gif")?></strong></div>
              <div class="user-tbl-cols search_result_cols"><strong class='sorter'>Status<?#=img("js/tablesorter/themes/blue/bg.gif")?></strong></div>
              <div class="user-tbl-cols search_result_cols"><strong class='sorter'>Vendor ID<?#=img("js/tablesorter/themes/blue/bg.gif")?></strong></div>
              <div class="user-tbl-cols search_result_cols"><strong class='sorter'>Last Agent<?#=img("js/tablesorter/themes/blue/bg.gif")?></strong></div>
              <div class="user-tbl-cols search_result_cols"><strong class='sorter'>List ID<?#=img("js/tablesorter/themes/blue/bg.gif")?></strong></div>
              <div class="user-tbl-cols search_result_cols"><strong class='sorter'>Phone<?#=img("js/tablesorter/themes/blue/bg.gif")?></strong></div>
              <div class="user-tbl-cols search_result_cols"><strong class='sorter'>Name<?#=img("js/tablesorter/themes/blue/bg.gif")?></strong></div>
              <div class="user-tbl-cols search_result_cols"><strong class='sorter'>City<?#=img("js/tablesorter/themes/blue/bg.gif")?></strong></div>
              <div class="user-tbl-cols search_result_cols"><strong class='sorter'>Security<?#=img("js/tablesorter/themes/blue/bg.gif")?></strong></div>
              <div class="user-tbl-cols search_result_cols"><strong class='sorter'>Last Call<?#=img("js/tablesorter/themes/blue/bg.gif")?></strong></div>
              <div class="user-tbl-cols user-tbl-cols-centered search_result_cols">
                   <div class="user-cols-action-chkbx"><input type="checkbox" id="batch" disabled="disabled"/></div>
                   <div class="user-cols-action-lbl"><strong>Actions&nbsp;<?=img("img/arrow_down.png")?></strong></div>
              </div>
            <?}?>
              <br class="clear"/>
        </div>
        
        <div class="user-tbl-container">
        
        <?Php
       
             if(count($users) == 0 ){
                 echo "<div class='user-tbl-rows user-even'><strong style='color:red;font-style:italic;'>No record(s) found!</strong></div>"; 
                 #echo "<div class='user-tbl-rows user-even' style='text-align:center;'><strong style='color:red;font-style:italic;'>No record(s) found!</strong></div>"; 
             }       
             $ctr=0;
             foreach($users as $usersInfo){
                 #if($user_level < 9){
                      echo "<div id='$usersInfo->lead_id' class='user-tbl-rows ".(($ctr%2==0)?'user-odd':'user-even')."'>";
                      
                           echo "<div class='user-tbl-cols search_result_cols'>$usersInfo->lead_id</div>";
                           echo "<div class='user-tbl-cols search_result_cols'>$usersInfo->status</div>";
                           echo "<div class='user-tbl-cols search_result_cols'>$usersInfo->vendor_id</div>";
                           echo "<div class='user-tbl-cols search_result_cols'>$usersInfo->last_agent</div>";
                           echo "<div class='user-tbl-cols search_result_cols'>$usersInfo->list_id</div>";
                           echo "<div class='user-tbl-cols search_result_cols'>$usersInfo->phone</div>";
                           echo "<div class='user-tbl-cols search_result_cols'>$usersInfo->name</div>";
                           echo "<div class='user-tbl-cols search_result_cols'>$usersInfo->city</div>";
                           echo "<div class='user-tbl-cols search_result_cols'>$usersInfo->security</div>";
                           echo "<div class='user-tbl-cols search_result_cols'>$usersInfo->last_call</div>";

                         # echo "<div class='user-tbl-cols ".($user_level>8?"user-tbl-cols-centered":"")."'><a id='user-status-$usersInfo->user'>Status</a>".($user_level>8?" | <a id='user-adv-$usersInfo->user'>Advance</a>":"")."</div>";
                          echo "<div class='user-tbl-cols search_result_cols'>
                                  <div class='user-cols-container'>
                                        <div class='user-actions-cols'>
                                            &nbsp;<input type='checkbox' id='user-action-chkbx-$usersInfo->lead_id' value='$usersInfo->user' rel='$usersInfo->lead_id' disabled='disabled'/>
                                        </div>
                                        <div class='user-actions-cols'>
                                            <a id='user-action-info-$usersInfo->lead_id' href='".base_url()."/index.php/go_search_ce/download/$usersInfo->lead_id' target='new' title='download' class='toolTip'>".img("img/status_display_i.png")."</a>
                                        </div>
                                        <div class='user-actions-cols'>
                                            <!--<a id='user-action-delete-$usersInfo->id' rel='$usersInfo->id' title='delete' class='toolTip'>".img("img/delete.png")."</a> -->
                                        </div>
                                        <div class='user-actions-cols'>
                                          <!--  <a id='user-action-modify-$usersInfo->filename' rel='$usersInfo->filename' title='modify' class='toolTip'>".img("img/edit.png")."</a>-->
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
        <?php if(count($users) > 25):?>
        <div class="pager-container">
            <span class="pager-perpage"><?=form_dropdown('page-dropdown',array(25=>25,50=>50,100=>100,"all"=>"All"))?>&nbsp;per page</span>
            <span class="pager-paginater">
                 <?=img(array('id'=>"pager-firstpage","src"=>'img/first.gif'))."&nbsp;".img(array("id"=>"pager-prevpage","src"=>"img/prev.gif"))?>
                 <?="Page&nbsp;".form_input('pager-currpage',1,'id="pager-currpage" size="2" readonly')."&nbsp;of&nbsp;"."<span id='pager-totalpage'>&nbsp;</span>"?>
                 <?=img(array('id'=>"pager-nextpage","src"=>'img/next.gif'))."&nbsp;".img(array("id"=>"pager-lastpage","src"=>"img/last.gif"))?>
            </span>
        </div>
        <?endif;?>
        <br class="clear"/>
    </div>
    <div id="download"></div>
    <div class="user-batch-action user-cornerall">
       <a id="user-batch-activate">Active Selected</a><br class="clear"/>
       <a id="user-batch-deactivate">Deactive Selected</a><br class="clear"/>
       <a id="user-batch-delete">Delete Selected</a><br class="clear"/>
    </div>
    <br class="clear"/>
</div>
