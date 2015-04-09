<?php
############################################################################################
####  Name:             go_user_main_ce.php                                             ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Franco E. Hora                                                  ####
####  Modified by:      Christopher P. Lomuntad - 25042013                              ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base_path = base_url();
?>
<link rel="stylesheet" type="text/css" href="<?=$base_path?>css/go_user/go_user_ce.css" />
<script src="<?=$base_path?>js/jquery-validate/jquery.validate.min.js"></script>
<script src="<?=$base_path?>js/gopagination/gopagination.js" type="text/javascript"></script>
<script src="<?=$base_path?>js/gopagination/gopaginate.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=$base_path?>js/go_user/go_user_ce.js"></script>
<script type="text/javascript" src="<?=$base_path?>js/go_user/go_user_panel1_ce.js"></script>
<script>
$(function()
{
    $("#showAllLists").click(function()
    {
	location.href = '<? echo $base; ?>users';
    });
    
    $("#search_list_button").click(function()
    {
	var search = $("#search_list").val();
	search = search.replace(/\s/g,"%20");
	if (search.length > 2) {
	    $('#showAllLists').show();
	    $('#paginationLinks').load('<? echo $base; ?>index.php/go_user_ce/pagination/links/'+search);
	    $('#paginationInfo').load('<? echo $base; ?>index.php/go_user_ce/pagination/info/'+search);
	    $("#user-container").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	    $('#user-container').load('<? echo $base; ?>index.php/go_user_ce/index/search/1/'+search);
	} else {
	    alert("Please enter at least 3 characters to search.");
	}
    });
    
    $('#search_list').bind("keydown keypress", function(event)
    {
	if (event.type == "keydown") {
	    // For normal key press
	    if (event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
		|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
		|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
		return false;
	    
	    if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
		return false;
	    
	    if (!event.shiftKey && event.keyCode == 173)
		return false;
	} else {
	    // For ASCII Key Codes
	    if ((event.which > 32 && event.which < 48) || (event.which > 57 && event.which < 65)
		|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
		return false;
	}
	//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
	if (event.which == 13 && event.type == "keydown") {
	    var search = $("#search_list").val();
	    search = search.replace(/\s/g,"%20");
	    if (search.length > 2) {
		$('#showAllLists').show();
		$('#paginationLinks').load('<? echo $base; ?>index.php/go_user_ce/pagination/links/'+search);
		$('#paginationInfo').load('<? echo $base; ?>index.php/go_user_ce/pagination/info/'+search);
		$("#user-container").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#user-container').load('<? echo $base; ?>index.php/go_user_ce/index/search/1/'+search);
	    } else {
		alert("Please enter at least 3 characters to search.");
	    }
	}
    });
});
</script>
<style type="text/css">
#showAllLists {
    color: #F00;
    font-size: 10px;
    cursor: pointer;
}

#paginationLinks {
    padding-left: 10px;
}

.generate_phone_class {
    display: none;
}
</style>

<!--//layout for datepicker//-->
<!-- <script type="text/javascript" src="<?=$base_path?>js/datepicker/datepicker-modify.js"></script> 
<script type="text/javascript" src="<?=$base_path?>js/datepicker/eye.js"></script>
<script type="text/javascript" src="<?=$base_path?>js/datepicker/utils.js"></script>  
<script type="text/javascript" src="<?=$base_path?>js/datepicker/layout.js?ver=1.0.2"></script> -->

<div id='outbody' class="wrap">
    <div id="icon-user" class="icon32"></div>
    <div style="float: right;margin-top:15px;margin-right:25px;"><span id="showAllLists" style="display: none"><?php echo $goUsers_clearSearch  ?></span>&nbsp;<?=form_input('search_list',$search_list,'id="search_list" maxlength="100" placeholder="'.$goUsers_searchUsers.'"') ?>&nbsp;<img src="<?=base_url()."img/spotlight-black.png"; ?>" id="search_list_button" style="cursor: pointer;" /></div>
    <h2><?=$bannertitle?><img class="toolTip" src="/img/status_display_i.png" title="<? echo lang('go_UserTooltip');?>" style="margin-left:10px; cursor:default; width:15px;"></h2>
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
            <div class="postbox-container" style="width:99%;min-width:1200px;">
                    <!-- List holder-->
                    <!-- <div class="postbox minimum" style="width:99%;min-width:1230px;" > -->
                    <!--<div class="postbox minimum"  >-->
                        <div class="meta-box-sortables ui-sortables">
					<div id="account_info_status" class="postbox">

                        <!-- <div class="handlediv" title="Click to toggle">
                            <br>
                        </div> -->
                        <!--<div class="user-add">
                            <a href="javascript:void(0);" id="call-user-wizard">Add User</a>&nbsp;<?#=img('img/cross.png')?>
                        </div> -->
                       <!--  <div class="copy-user">&nbsp;Copy User</div> 
                        <div class="wizard-call-separator"><span>|</span></div> -->
			<?php
			$hideWizard = "";
			$permissions = $this->commonhelper->getPermissions("user",$this->session->userdata("user_group"));
			if($permissions->user_create == "N"){
			    $hideWizard = "display:none;";
			}
			?>
                        <div class="user-add rightdiv" id="call-user-wizard" style="color:#555555;<?=$hideWizard?>">
                            <?php echo $goUsers_addNewUser; ?><?#=img('img/cross.png')?>
                        </div>
                        <h3 class="hndle">
                            <span><span id="title_bar" /><?=$bannertitle?>&nbsp;Lists</span>
                                    <span class="postbox-title-action"></span>
                            </span>
                        </h3>
                            <div id="tab-nav" class="tab-nav">
                                <ul>
                                    <li><a href="<?=$base_path?>index.php/go_user_ce/index/<?=$user_group?>/<?=$page?>"><span><?php echo $goUsers_users; ?></span></a></li>
                                    <!-- <li><a href="userstatus"><span>User Status</span></a></li> -->
                                    <?if($user_level > 8){?>
                                    <!-- <li><a href="advancemodifyuser"><span>Advance</span></a></li> -->
                                    <?}?>
                                </ul>

                            <?php

				$total 	= $query->row()->ucnt;
				$rp 	= ($page=='ALL') ? $total : 25;
				$limit 	= 5;
				if (is_null($page) || $page < 1)
				    $page = 1;
				$pg 	= $this->commonhelper->paging($page,$rp,$total,$limit);
				$start	= (($page-1) * $rp);

				if ($pg['last'] > 1) {
				    $pagelinks  = '<div style="cursor: pointer;font-weight: bold;" id="paginationLinks">';
				    $pagelinks .= '<a title="'.$goUsers_tooltip_goToFirstPage.'" style="vertical-align:baseline;padding: 0px 2px;" onclick="gopage('.$pg['first'].')"><span><img src="'.base_url().'/img/first.gif"></span></a>';
				    $pagelinks .= '<a title="'.$goUsers_tooltip_goToPreviousPage.'" style="vertical-align:baseline;padding: 0px 2px;" onclick="gopage('.$pg['prev'].')"><span><img src="'.base_url().'/img/prev.gif"></span></a>';
							    
				    for ($i=$pg['start'];$i<=$pg['end'];$i++) { 
				       if ($i==$pg['page']) $current = 'color: #F00;cursor: default;'; else $current="";
				    
					$pagelinks .= '<a title="'.$goUsers_tooltip_goToPage.' '.$i.'" style="vertical-align:text-top;padding: 0px 2px;'.$current.'" onclick="gopage('.$i.')"><span>'.$i.'</span></a>';
							    
				    }
					    
				    //$pagelinks .= '<a title="View All Pages" style="vertical-align:text-top;padding: 0px 2px;" onclick="gopage(\'ALL\')"><span>ALL</span></a>';
				    $pagelinks .= '<a title="'.$goUsers_tooltip_goToNextPage.'" style="vertical-align:baseline;padding: 0px 2px;" onclick="gopage('.$pg['next'].')"><span><img src="'.base_url().'/img/next.gif"></span></a>';
				    $pagelinks .= '<a title="'.$goUsers_tooltip_goToLastPage.'" style="vertical-align:baseline;padding: 0px 2px;" onclick="gopage('.$pg['last'].')"><span><img src="'.base_url().'/img/last.gif"></span></a>';
				    $pagelinks .= '</div>';
				} else {
				    if ($rp > 25) {
					    $pagelinks  = '<div style="cursor: pointer;font-weight: bold;padding-top:10px;">';
					    $pagelinks .= '<a title="'.$goUsers_tooltip_backToPaginatedView.'" style="vertical-align:text-top;padding: 0px 2px;" onclick="gopage(1)"><span>BACK</span></a>';
					    $pagelinks .= '</div>';
				    } else {
					$pagelinks = '';
				    }
				}
				echo "<span style='float:right;padding-right:10px;' id='paginationInfo'>$goUsers_displaying {$pg['istart']} $goUsers_to {$pg['iend']} $goUsers_of {$pg['total']} $goUsers_users</span>";
                                echo $pagelinks;
                                echo '<form action="users" id="page_track" method="post">';
                                echo "<input type='hidden' name='page'>";
                                echo form_close();
                            ?>
                            <script>
                                  function gopage(num){
                                       $("input[name='page']").val(num);
                                       $("#page_track").submit();
                                  }
                            </script>
                            </div> 	
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<div class="overlay"></div>
<div class="overlay-leadinfo"></div>
    <div class="wizard-box">
        <div class='add-close'>&nbsp;</div>
        <br class="clear"/>
        <div class='wizard-breadcrumb'>
             <div class="wizard-title"><strong><?php echo $goUsers_wizard; ?> &raquo;<?php echo $goUsers_addNewUser; ?></strong></div>
             <?=img("img/step2of3-navigation-small.png")?>
             <br class="clear"/>
        </div>
        <div class="wizard-content">
             <div class="wizard-content-left"><?=img("img/step2-trans.png")?></div>
             <div class="wizard-content-right">
                  <?=form_open(null,'id="add-new-user"')?>
						<?php
						if ($this->commonhelper->checkIfTenant($user_group))
						{
							$hideDiv='style="display:none"';
						}
						?>
                        <div class="boxleftside" <?=$hideDiv ?>>
                             <span><strong><?php echo $goUsers_userGroup; ?><?php echo":" ?></strong></span>
                        </div>
                        <div class="boxrightside" <?=$hideDiv ?>> 
                             <?php #if($user_level < 9){?>
                                  <!-- <span><?#=$username?></span> -->
                             <?php #} else {
                                       $attr = 'id="accountNum" style="width:300px;"';
				       $accnt_group = (!$this->commonhelper->checkIfTenant($user_group)) ? "AGENTS" : "$user_group";
                                       echo form_dropdown('accountNum',$accnt_list,$accnt_group,$attr);
                                       echo "<script>var go_accounts = $foradd</script>";
                                   #}
                             ?>
                        </div><br class="clear" <?=$hideDiv ?>/>
                        <!-- <div class="boxleftside">
                            <span>User Group</span>
                        </div>
                        <div class="boxrightside">
                           <?php
                                #if($user_level < 9){
                           ?> 
                               <span><?#=$userfulname?></span>
                               <?#=form_hidden('hidcompany',$userfulname)?>
                           <?php
                                #}else{
                                #      echo "<span id='comp_name'>&nbsp;</span>";
                                #      echo form_hidden('hidcompany',null);
                                #}
                           ?>
                        </div><br class="clear"/> -->
                        <div class="boxleftside">
                           <span><strong><?php echo $goUsers_currentUsers; ?><?php echo":" ?></strong></span>
                        </div>
                        <div class="boxrightside">
                           <?php #if($user_level < 9){?> 
                                    <span>&nbsp;<?=$a2wizard?></span>
                                    <?=form_hidden(array('hidcount'=>$a2wizard))?>
                           <?php #}else{
                                 #     echo "<span id='count'>&nbsp;</span>";
                                 #     echo "<input type='hidden' value='' name='hidcount' id='hidcount'>";
                                 #}
                           ?>
                        </div><br class="clear"/>
                        <div class="boxleftside">
                            <span><strong><?php echo $goUsers_addSeats; ?><?php echo":" ?></strong></span>
                        </div>
                        <div class="boxrightside"> 
                            <?php
                               $usercount = array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,
                                                  11=>11,12=>12,13=>13,14=>14,15=>15,16=>16,17=>17,18=>18,19=>19,20=>20);
                            ?>
                            <?=form_dropdown('txtSeats',$usercount,1,'class="txtSeats" onchange="checkPhoneIfExist()"')?>
                            <?=form_hidden('skip','skip')?>
                        </div><br class="clear"/>
                        <div class="boxleftside">
                            <span><strong><?php echo $goUsers_generatePhoneLogins; ?>:</strong></span>
                        </div>
                        <div class="boxrightside">
                            <?=form_dropdown("generate_phone",array('No','Yes'),0,"class='generate_phone' onChange='generatePhone();'")?>
                        </div><br class="clear"/>
                        <div class="boxleftside generate_phone_class">
                            <span><strong><?php echo $goUsers_phoneLogin; ?></strong></span>
                        </div>
                        <div class="boxrightside generate_phone_class">
                            <?=form_input("start_phone_exten",null,"class='start_phone_exten' maxlength='10' size='12' onkeydown='digitsOnly(event)' onkeyup='checkPhoneIfExist()' placeholder='eg. 8001'")?>
			    &nbsp;<span class="eloading" style="font-size: 10px;"></span>
                        </div><br class="clear generate_phone_class"/>
                  <?=form_close();?>
             </div>
             <br class="clear"/>
        </div>
        <br class="clear"/>
        <div class="wizard-action">
              <a onclick="cancelWizard(this)"><?php echo $goUsers_cancel; ?></a>&nbsp;|&nbsp;
              <a onclick="next()"><?php echo $goUsers_next; ?></a>
        </div>
        <br class="clear"/>
    </div>
<div class="overlay-modify">
</div>
    <div class="wizard-box-modify">
        <div class='add-close-modify'>&nbsp;</div>
        <br class="clear"/>
        <div class="box-header"><center><strong style="font-size:16px;"><?php echo $goUsers_modify; ":" ?> <span style="font-size:16px;" id="modify_users_id"></span></strong></center></div><br class="clear"/>
       
        <?=form_open(null,'id="form" class="edit-user"');?>
            <div class="boxleftside boxleftside-modify toolTip" title="<? echo lang('goUsers_agentandphoneTooltip'); ?>"><?php echo $goUsers_agentId;?><?php echo":" ?></div>
            <div class="boxrightside boxrightside-modify">&nbsp;<input type="hidden" id="users_id" name="users_id"><input type="hidden" id="vicidial_user_id" name="vicidial_user_id"></div><br class="clear">
            <div class="boxleftside boxleftside-modify"><?php echo $goUsers_password;?><?php echo":" ?></div>
            <div class="boxrightside boxrightside-modify"><?=form_input('pass',null,'id="pass" maxlength="20"')?></div><br class="clear">
            <div class="boxleftside boxleftside-modify"><?php echo $goUsers_fullName;?><?php echo":" ?></div>
            <div class="boxrightside boxrightside-modify"><?=form_input('full_name',null,'id="full_name" maxlength="50" size="30"')?></div><br class="clear">
            <div class="boxleftside boxleftside-modify toolTip" title="<? echo lang('goUsers_agentandphoneTooltip'); ?>"><?php echo $goUsers_phoneLogin;?><?php echo":" ?></div>
            <div class="boxrightside boxrightside-modify"><?=form_input('phone_login',null,'id="phone_login" maxlength="15" size="15"')?></div><br class="clear">
            <div class="boxleftside boxleftside-modify"><?php echo $goUsers_phonePassword;?><?php echo":" ?></div>
            <div class="boxrightside boxrightside-modify"><?=form_input('phone_pass',null,'id="phone_pass" maxlength="10" size="12"')?></div><br class="clear">
			<?php
			if ($this->commonhelper->checkIfTenant($user_group))
			{
				$hideDiv='style="display:none"';
			}
			?>
			<div class="boxleftside boxleftside-modify" <?=$hideDiv ?>>
				 <?php echo $goUsers_userGroup;?><?php echo":" ?>
			</div>
			<div class="boxrightside boxrightside-modify" <?=$hideDiv ?>> 
				<?php 
					 $attr = 'id="user_group"';
					 #echo form_dropdown('user_group',$accnt_list,null,$attr);
					 echo form_dropdown('user_group',$accnt_list,$user_group,$attr);
				?>
			</div><br class="clear" <?=$hideDiv ?>/>
            <div class="boxleftside boxleftside-modify"><?php echo $goUsers_active;?><?php echo ":" ?></div>
            <div class="boxrightside boxrightside-modify">
                 <?=form_dropdown('active',array('Y'=>'Yes','N'=>'No'),null,'id="active"')?>
            </div><br class="clear">
            <div class="boxleftside boxleftside-modify"><?php echo $goUsers_hotkeys;?><?php echo":" ?></div>
            <div class="boxrightside boxrightside-modify">
                 <?=form_dropdown('hotkeys_active',array('1'=>'Yes','0'=>'No'),null,'id="hotkeys_active"')?>
            </div><br class="clear">
	    <?php
	    $max_level = $user_level;
	    if ($user_level < 9) {
	       $hideLevels = "style='display:none'";
	    } else {
	       $hideLevels = "";
	//       if ($modify_same_level) {
	//	   $max_level = $user_level;
	//       } else {
	//	   $max_level = $user_level - 1;
	//       }
	    }
	    ?>
            <div class="boxleftside boxleftside-modify" <?=$hideLevels?>><?php echo $goUsers_userLevel;?><?php echo":" ?></div>
            <div class="boxrightside boxrightside-modify" <?=$hideLevels?>>
                 <?php
		 $levels = range(0,$max_level);unset($levels[0]);
		 ?>
                 <?=form_dropdown('user_level',$levels,null,'id="user_level"')?>
            </div><br class="clear" <?=$hideLevels?>>
	    <?php
	    if ($user_group === "ADMIN") {
	    ?>
            <div class="boxleftside boxleftside-modify"><?php echo $goUsers_modifySameUserLevel ?><?php echo":" ?></div>
            <div class="boxrightside boxrightside-modify">
		<?=form_dropdown('modify_same_user_level',array('1'=>'Yes','0'=>'No'),null,'id="modify_same_user_level"')?>
	    </div>
	    <?php
	    }
	    ?>
            <!-- <div class="boxleftside"><a class="useraccess-remote" id="advance-toggle">Advance [+] </a></div><br class="clear"/>
            <?#foreach($useraccess as $group => $access):?>
                <br class="clear"/><div class="boxleftside boxleftside-modify <?=substr($group,0,strpos($group," "))?>" style="padding:10px;"><?=$group?></div><br class="boxleftside-modify clear"/>
                <?php
                      #foreach($access as $cols => $name){
                      #    echo "<div class='boxleftside boxleftside-modify ".substr($group,0,strpos($group," "))."'>$name:</div>";
                      #    $tickbox = array('value'=>1,'name'=>$cols,'id'=>$cols);
                      #    echo "<div class='boxrightside boxrightside-modify boxrightside-modify-leftalign ".substr($group,0,strpos($group," "))."'>".form_checkbox($tickbox)."</div>";
                      #}
                ?>
            <?#endforeach;?> -->
        <?=form_close();?> 
        <br class="clear"/>
        <div class="quick-action-set">
            <a id="actions-update"><?php echo $goUsers_update; ?></a> 
        </div>
        <br class="clear"/>
    </div>
<div class="user-cornerall overlay-type">
    <div class='type-close'>&nbsp;</div>
    <br class="clear"/>
    <div class='wizard-breadcrumb'>
       <div class="wizard-title"><strong><?php echo $goUsers_wizard; ?></strong></div>
          <?=img("img/step1of2-navigation-small.png")?>
          <br class="clear"/>
       </div>
       <div class="wizard-content">
             <div class="wizard-content-left"><?=img("img/step1-trans.png")?></div>
             <div class="wizard-content-right">
                  <div class="boxleftside"><strong><?php echo $goUsers_wizardType; ?><?php echo ":" ?></strong></div>
                  <!-- <div class="boxrightside"><?#=form_dropdown('wizard_type',array('add'=>'Add New User','copy'=>'Copy User'),'add','id="wizard_type"')?></div> -->
                  <div class="boxrightside"><?=form_dropdown('wizard_type',array('add'=>'Add New User'),'add','id="wizard_type"')?></div>
             </div>
       </div>
        <br class="clear"/>
        <div class="wizard-action">
              <a onclick="showtype()"><?php echo $goUsers_next; ?></a>
        </div>
        <br class="clear"/>
</div>
<div class="overlay-info"></div>
    <div class="user-cornerall wizard-copy">
        <div class="copy-close">&nbsp;</div>
        <br class="clear"/>
        <div class='wizard-breadcrumb'>
             <div class="wizard-title"><strong><?php echo $goUsers_wizard; ?> &raquo; <?php echo $goUsers_copyUser; ?></strong></div>
             <?=img("img/step2of2-navigation-small.png")?>
             <br class="clear"/>
        </div>
        <div class="wizard-content">
             <div class="wizard-content-left"><?=img("img/step2-trans.png")?></div>
             <div class="wizard-content-right">
                <form id="copy-form">
                   <div class="copy-boxleftside"><strong><?php echo $goUsers_users; ?></strong></div><div class="copy-boxrightside"><?=form_dropdown('source_user',$users,null,'id="source_user"')?></div>
                   <!-- <div class="copy-boxleftside"><strong>User ID:</strong></div><div class="copy-boxrightside"><?#=form_input('user',null,'id="user" maxlength="20"')?></div> -->
                   <div class="copy-boxleftside"><strong><?php echo $goUsers_password; ?></strong></div><div class="copy-boxrightside"><?=form_input('pass',null,'id="pass" maxlength="20"')?></div>
                   <div class="copy-boxleftside"><strong><?php echo $goUsers_fullName; ?></strong></div><div class="copy-boxrightside"><?=form_input('full_name',null,'id="full_name" maxlength="50" size="30"')?></div>
                   <br class="clear"/>
                   <br class="clear"/>
                </form>
                <br class="clear"/>
             </div>
         </div>
         <br class="clear"/>
        <div class="wizard-action">
          <a onclick="cancelWizard(this)"><?php echo $goUsers_cancel; ?></a>&nbsp;|&nbsp;<a id="copy-proceed"><?php echo $goUsers_submit; ?></a>
        </div>
    </div>
    <div class="user-cornerall wizard-box-info">
         <div class='add-close-info'>&nbsp;</div>
         <div class="datepicker-container">
              <div class="hovermenu" id="widgetField">
                    <div id="widgetDate" class="toolTip" title="<? echo lang('go_widgetDateTooltip'); ?>"><span id="user-stat-<?=$userinfo[0]->user?>"><? echo date('Y-m-d'); ?> to <? echo date('Y-m-d'); ?></span></div>
                    <a href="javascript:void(0);" id="daterange"><?php echo $goUsers_selectDateRange; ?></a>
              </div>
              <div id="widgetCalendar"></div> <!--//calendar layout//-->
         </div>
         <br class="clear"/>
         <!-- <div class="adv-user-status-client">
             <div class="adv-toggle"></div>
             <h3>User Status</h3>
             <div class="adv-user-detail user-corners">
                  <div class="userstatus-display">
                  <?Php
                       #echo '<strong></strong><br/>';
                       #echo "<div class='leftside'>Agent logged in at server</div><div class='rightside' id='server_ip'> </div><br/>";
                       #echo "<div class='leftside'>In session</div><div class='rightside' id='conf_exten'> </div><br/>";
                       #echo "<div class='leftside'>From phone</div><div class='rightside' id='extension'> </div><br/>";
                       #echo "<div class='leftside'>Agent is in campaign</div><div class='rightside' id='campaign_id'> </div><br/>";
                       #echo "<div class='leftside'>Status</div><div class='rightside' id='status'> </div><br/>";
                       #echo "<div class='leftside'>Hang-up last call at</div><div class='rightside' id='last_call_finish'> </div><br/>";
                       #echo "<div class='leftside'>Closer groups</div><div class='rightside' id='close_campaigns'></div><br/>";
                  ?> 
                  </div>
                  <div id="<?#=$userinfo[0]->user?>" class="emergency-container"><a href="javascript:void(0);" class="emergency">Force Logout</a></div>
                  <br class="clear"/>
             </div>
         </div> -->
         <div class="agent-talk-time user-cornerall">
               <strong><?php echo $goUsers_agentTalkTimeAndStatus; ?></strong>
               <br class="clear"/>
                  <div class="userstatus-display">
                  <?Php
                       echo '<br/><strong></strong><br/>';
                       #echo "<div class='leftside'>Agent logged in at server</div><div class='rightside' id='server_ip'> </div><br/>";
                       #echo "<div class='leftside'>In session</div><div class='rightside' id='conf_exten'> </div><br/>";
                       echo "<div class='leftside'>$goUsers_phoneExtension:</div><div class='rightside' id='extension'> </div><br/>";
                       echo "<div class='leftside'>$goUsers_agentIsInCampaign:</div><div class='rightside' id='campaign_id'> </div><br/>";
                       echo "<div class='leftside'>$goUsers_status:</div><div class='rightside' id='status'> </div><br/>";
                       echo "<div class='leftside'>$goUsers_hangupLastCallAt:</div><div class='rightside' id='last_call_finish'> </div><br/>";
                       echo "<div class='leftside'>$goUsers_closerGroups:</div><div class='rightside' id='close_campaigns'></div><br/>";
                  ?> 
                  </div>
               <br class="clear"/>
               <div class="time-status-tbl">
                  <div class="time-stat-hdr">
                      <div class="cols"><?php echo $goUsers_status; ?></div>
                      <div class="cols"><?php echo $goUsers_count; ?></div>
                      <div class="cols">HH:MM:SS</div>
                  </div>
                  <br class="clear"/> 
                  <div class="time-stat-content"></div>
               </div>
         </div>
         <br class="clear"/>
         <div class="user-stats">
              <span><?php echo $goUsers_otherStaticsInfo; ?></span><br class="clear"/>
              <label><a id="agentloginlogout"><?php echo $goUsers_agentLoginLogout; ?></a></label>
              <label><a id="outboundthistime" class="toolTip" title="<? echo lang('go_inboundthistimeTooltip'); ?>"><?php echo $goUsers_outboundCallThisTime;?></a></label>
              <label><a id="inboundthistime"><?php echo $goUsers_inboundCloserCalls; ?></a></label>
              <label><a id="agentactivity"><?php echo $goUsers_agentActivity; ?></a></label>
              <label><a id="recordingthistime"><?php echo $goUsers_recording; ?></a></label>
              <label><a id="manualoutbound"><?php echo $goUsers_manualOutbound; ?></a></label>
              <label><a id="leadsearchthistime"><?php echo $goUsers_leadSearch; ?></a></label>
         </div>
         <br class="clear"/>
         <div id="<?=$userinfo[0]->user?>" class="emergency-container"><a href="javascript:void(0);" class="emergency toolTip" title="<? echo lang('goUsers_forceLogoutTooltip');?>"><?php echo $goUsers_forceLogout; ?></a></div>
         <br class="clear"/>
    </div>
         <div class="agent-loginlogout-time user-cornerall">
              <strong><?php echo $goUsers_agentLoginLogoutTime; ?></strong><a class="userstat-closer"><?php echo $goUsers_close; ?></a>
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
                   <div class="pager-container agent-loginlogout-pager">
                       <div class="totaltime">
                            <div class="labelcols"><?php echo $goUsers_totalCalls; ?></div>
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols"><?php echo $goUsers_disposition; ?></div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="spacercols">&nbsp;</div> 
                            <br class="clear"/>
                       </div>
                       <span class="pager-perpage"><?=form_dropdown('page-dropdown',array(25=>25,50=>50,100=>100,"all"=>"All"))?>&nbsp; <?php echo $goUsers_perPage; ?></span>
                       <span class="pager-paginater">
                           <?=img(array('id'=>"loginlogout-firstpage","src"=>'img/first.gif'))."&nbsp;".img(array("id"=>"loginlogout-prevpage","src"=>"img/prev.gif"))?>
                           <?="Page&nbsp;".form_input('loginlogout-currpage',1,'id="loginlogout-currpage" size="2" readonly')."&nbsp;of&nbsp;"."<span id='loginlogout-total-page'>&nbsp;</span>"?>
                           <?=img(array('id'=>"loginlogout-nextpage","src"=>'img/next.gif'))."&nbsp;".img(array("id"=>"loginlogout-lastpage","src"=>"img/last.gif"))?>
                       </span>
                  </div> 
              </div>
         </div>
         <div class="agent-outbound-thistime user-cornerall">
              <strong><?php echo $goUsers_outboundCallsForThisTimePeriod; ?></strong><a class="userstat-closer"><?php echo $goUsers_close; ?></a>
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
                   <!-- <div class="outbound-paginator"></div>  -->
                   <div class="pager-container outbound-thistime-pager">
                       <span class="pager-perpage"><?=form_dropdown('page-dropdown',array(25=>25,50=>50,100=>100,"all"=>"All"))?>&nbsp;<?php echo $goUsers_perPage; ?></span>
                       <span class="pager-paginater">
                           <?=img(array('id'=>"outbound-firstpage","src"=>'img/first.gif'))."&nbsp;".img(array("id"=>"outbound-prevpage","src"=>"img/prev.gif"))?>
                           <?="Page&nbsp;".form_input('outbound-currpage',1,'id="outbound-currpage" size="2" readonly')."&nbsp;of&nbsp;"."<span id='outbound-total-page'>&nbsp;</span>"?>
                           <?=img(array('id'=>"outbound-nextpage","src"=>'img/next.gif'))."&nbsp;".img(array("id"=>"outbound-lastpage","src"=>"img/last.gif"))?>
                       </span>
                   </div> 
              </div>
         </div>
         <div class="agent-inbound-thistime user-cornerall">
              <strong><?php echo $goUsers_inboundCloserCallsForThisTimePeriod; ?></strong><a class="userstat-closer"><?php echo $goUsers_close; ?></a>
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
                   <div class="pager-container inbound-thistime-pager">
                       <div class="totaltime">
                            <div class="labelcols"><?php echo $goUsers_totalCalls; ?></div>
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="totalcols">&nbsp;</div> 
                            <div class="spacercols">&nbsp;</div> 
                            <br class="clear"/>
                       </div>
                       <span class="pager-perpage"><?=form_dropdown('page-dropdown',array(25=>25,50=>50,100=>100,"all"=>"All"))?>&nbsp;<?php echo $goUsers_perPage; ?></span>
                       <span class="pager-paginater">
                           <?=img(array('id'=>"inbound-firstpage","src"=>'img/first.gif'))."&nbsp;".img(array("id"=>"inbound-prevpage","src"=>"img/prev.gif"))?>
                           <?="Page&nbsp;".form_input('inbound-currpage',1,'id="inbound-currpage" size="2" readonly')."&nbsp;of&nbsp;"."<span id='inbound-total-page'>&nbsp;</span>"?>
                           <?=img(array('id'=>"inbound-nextpage","src"=>'img/next.gif'))."&nbsp;".img(array("id"=>"inbound-lastpage","src"=>"img/last.gif"))?>
                       </span>
                   </div>
              </div>
         </div>
         <div class="agent-activity-thistime user-cornerall">
              <strong><?php echo $goUsers_agentActivityForThisTime; ?></strong><a class="userstat-closer"><?php echo $goUsers_close; ?></a>
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
                   <div class="pager-container agent-activity-pager">
                       <div class="totaltime">
                            <div class="labelcols"><?php echo $goUsers_totalCalls; ?></div>
                            <div class="totalcols totalpause">&nbsp;</div> 
                            <div class="totalcols totalwait">&nbsp;</div> 
                            <div class="totalcols totaltalk">&nbsp;</div> 
                            <div class="totalcols totaldispo">&nbsp;</div> 
                            <div class="totalcols totaldead">&nbsp;</div> 
                            <div class="totalcols totalcustomer">&nbsp;</div> 
                            <div class="spacercols">&nbsp;</div> 
                            <br class="clear"/>
                       </div>
                       <span class="pager-perpage"><?=form_dropdown('page-dropdown',array(25=>25,50=>50,100=>100,"all"=>"All"))?>&nbsp;<?php echo $goUsers_perPage; ?></span>
                       <span class="pager-paginater">
                           <?=img(array('id'=>"agentactivity-firstpage","src"=>'img/first.gif'))."&nbsp;".img(array("id"=>"agentactivity-prevpage","src"=>"img/prev.gif"))?>
                           <?="Page&nbsp;".form_input('agentactivity-currpage',1,'id="agentactivity-currpage" size="2" readonly')."&nbsp;of&nbsp;"."<span id='agentactivity-total-page'>&nbsp;</span>"?>
                           <?=img(array('id'=>"agentactivity-nextpage","src"=>'img/next.gif'))."&nbsp;".img(array("id"=>"agentactivity-lastpage","src"=>"img/last.gif"))?>
                       </span>
                   </div>
              </div>
         </div>
         <div class="agent-recording-thistime user-cornerall">
              <strong><?php echo $goUsers_recordingForThisTimePeriod; ?></strong><a class="userstat-closer"><?php echo $goUsers_close; ?></a>
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
                   <div class="pager-container recording-thistime-pager">
                       <span class="pager-perpage"><?=form_dropdown('page-dropdown',array(25=>25,50=>50,100=>100,"all"=>"All"))?>&nbsp;<?php echo $goUsers_perPage; ?></span>
                       <span class="pager-paginater">
                           <?=img(array('id'=>"recording-firstpage","src"=>'img/first.gif'))."&nbsp;".img(array("id"=>"recording-prevpage","src"=>"img/prev.gif"))?>
                           <?="Page&nbsp;".form_input('recording-currpage',1,'id="recording-currpage" size="2" readonly')."&nbsp;of&nbsp;"."<span id='recording-total-page'>&nbsp;</span>"?>
                           <?=img(array('id'=>"recording-nextpage","src"=>'img/next.gif'))."&nbsp;".img(array("id"=>"recording-lastpage","src"=>"img/last.gif"))?>
                       </span>
                   </div>
              </div>
         </div>
         <div class="agent-manualoutbound-thistime user-cornerall">
              <strong><?php echo $goUsers_manualOutboundCallsForThisTimePeriod; ?></strong><a class="userstat-closer"><?php echo $goUsers_close; ?></a>
              <br class="clear"/>
              <br class="clear"/>
              <div class="manualoutbound-thistime-tbl">
                   <div class="manualoutbound-thistime-hdr">
                        <div class="cols"><?php echo $goUsers_dateType; ?></div>
                        <div class="cols"><?php echo $goUsers_callType; ?></div>
                        <div class="cols"><?php echo $goUsers_server; ?></div>
                        <div class="cols"><?php echo $goUsers_phone; ?></div>
                        <!-- <div class="cols">Dialed</div> -->
                        <div class="cols"><?php echo $goUsers_lead; ?></div>
                        <div class="cols"><?php echo $goUsers_callerId; ?></div>
                        <!-- <div class="cols">Alias</div>
                        <div class="cols">Preset</div>
                        <div class="cols">C3HU</div> -->
                   </div>
                   <br class="clear"/> 
                   <div class="manualoutbound-thistime-content"></div>
                   <div class="pager-container manualoutbound-thistime-pager">
                       <span class="pager-perpage"><?=form_dropdown('page-dropdown',array(25=>25,50=>50,100=>100,"all"=>"All"))?>&nbsp;<?php echo $goUsers_perPage; ?></span>
                       <span class="pager-paginater">
                           <?=img(array('id'=>"manualoutbound-firstpage","src"=>'img/first.gif'))."&nbsp;".img(array("id"=>"manualoutbound-prevpage","src"=>"img/prev.gif"))?>
                           <?="Page&nbsp;".form_input('manualoutbound-currpage',1,'id="manualoutbound-currpage" size="2" readonly')."&nbsp;of&nbsp;"."<span id='manualoutbound-total-page'>&nbsp;</span>"?>
                           <?=img(array('id'=>"manualoutbound-nextpage","src"=>'img/next.gif'))."&nbsp;".img(array("id"=>"manualoutbound-lastpage","src"=>"img/last.gif"))?>
                       </span>
                   </div>
              </div>
         </div>
         <div class="agent-leadsearch-thistime user-cornerall">
              <strong><?php echo $goUsers_leadSearchesForThisTimePeriod; ?></strong><a class="userstat-closer"><?php echo $goUsers_close; ?></a>
              <br class="clear"/>
              <br class="clear"/>
              <div class="leadsearch-thistime-tbl">
                  <div class="leadsearch-thistime-hdr">
                       <div class="cols"><?php echo $goUsers_dateTime; ?></div>
                       <div class="cols"><?php echo $goUsers_type; ?></div>
                       <div class="cols"><?php echo $goUsers_results; ?></div>
                       <div class="cols"><?php echo $goUsers_seconds; ?></div>
                       <div class="cols"><?php echo $goUsers_query; ?></div>
                  </div>
                  <br class="clear"/> 
                  <div class="leadsearch-thistime-content"></div>
              </div>
         </div>
         <div class="leadinfo user-cornerall">
              <strong><?php echo $goUsers_leadInformation; ?></strong><a class="userstat-closer"><?php echo $goUsers_close; ?></a><br/>
              <div class="leadinfolabel"><?php echo $goUsers_leadId; ?></div>
              <div class="leadinfocont lead_id"></div>
              <div class="leadinfolabel"><?php echo $goUsers_listId; ?></div>
              <div class="leadinfocont list_id"></div>
              <div class="leadinfolabel"><?php echo $goUsers_address; ?></div>
              <div class="leadinfocont address1"></div>
              <div class="leadinfolabel"><?php echo $goUsers_phoneCode; ?></div>
              <div class="leadinfocont phone_code"></div>
              <div class="leadinfolabel"><?php echo $goUsers_phoneNumber; ?></div>
              <div class="leadinfocont phone_number"></div>
              <div class="leadinfolabel"><?php echo $goUsers_city; ?></div>
              <div class="leadinfocont city"></div>
              <div class="leadinfolabel"><?php echo $goUsers_state; ?></div>
              <div class="leadinfocont state"></div>
              <div class="leadinfolabel"><?php echo $goUsers_postalCode; ?></div>
              <div class="leadinfocont postal_code"></div>
              <div class="leadinfolabel"><?php echo $goUsers_comment; ?></div>
              <div class="leadinfocont comment"></div>
              <br class="clear"/>
         </div>
</div> <!-- wpwrap -->
