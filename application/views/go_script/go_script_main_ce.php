<?php
############################################################################################
####  Name:             go_script_main_ce.php                                           ####
####  Type:             ci view (template for users)                                    ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco Hora <info@goautodial.com>             ####
####  License:          AGPLv2                                                          ####
############################################################################################
?>

<link type="text/css" rel="stylesheet" href="<?=base_url()?>css/go_common_ce.css">
<link type="text/css" rel="stylesheet" href="<?=base_url()?>css/go_script/go_script_ce.css">
<script type="text/javascript" src="<?=base_url()?>js/go_common_ce.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/go_script/go_script_main_ce.js"></script>
<script>
$(function()
{
    $("#showAllLists").click(function()
    {
	location.href = '<? echo $base; ?>scripts';
    });
    
    $("#search_list_button").click(function()
    {
	var search = $("#search_list").val();
	search = search.replace(/\s/g,"%20");
	if (search.length > 2) {
	    $('#showAllLists').show();
	    $("#ui-tabs-1").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	    $('#ui-tabs-1').load('<? echo $base; ?>index.php/go_script_ce/collectscripts/1/'+search);
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
		$("#ui-tabs-1").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		$('#ui-tabs-1').load('<? echo $base; ?>index.php/go_script_ce/collectscripts/1/'+search);
	    } else {
		alert("Please enter at least 3 characters to search.");
	    }
	}
    }); 
});

function changePage(pagenum)
{
	var search = $("#search_list").val();
	search = search.replace(/\s/g,"%20");
	$("#ui-tabs-1").empty().html('<center><img src="<? echo $base; ?>img/goloading.gif" /></center>');
	$('#ui-tabs-1').load('<? echo $base; ?>index.php/go_script_ce/collectscripts/'+pagenum+'/'+search);
}
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
</style>

<div id='outbody' class="wrap">
    <div id="icon-script" class="icon32"></div>
    <div style="float: right;margin-top:15px;margin-right:25px;"><span id="showAllLists" style="display: none">[Clear Search]</span>&nbsp;<?=form_input('search_list',null,'id="search_list" maxlength="100" placeholder="Search '.$bannertitle.'"') ?>&nbsp;<img src="<?=base_url()."img/spotlight-black.png"; ?>" id="search_list_button" style="cursor: pointer;" /></div>
    <h2><?=$bannertitle?></h2>
    <div id="dashboard-widgets-wrap">
         <div id="dashboard-widgets" class="metabox-holder">
             <div class="postbox-container" style="width:99%">
                  <div class="meta-box-sortables ui-sortables">
                       <div class="postbox minimum">
                            <div class='script-add-container rightdiv'><a id="script-add">Add New Script <?#=img('img/cross.png')?></a></div>
                            <h3 class="hndle"><span><?=$bannertitle?>&nbsp;List</span></h3>
                            <div class="inside inside-tab">
                                  <div id="script-tab" class="tab-container">
                                          <ul>
                                               <li><a href="<?=base_url()?>index.php/go_script_ce/collectscripts"><span>Scripts</span></a></li>
                                               <li><a href="<?=base_url()?>index.php/go_script_ce/advance"><span>Advance</span></a></li>
                                          </ul> 
                                 </div> <!--End of script-container-->
                                 <br class="clear"/>
                            </div><!--End of inside-->
                       </div><!--End of postbox-->
                  </div><!--End of meta-box-sortables-->
             </div><!--End of postbox-container-->
         </div> <!--End of dashboard-widgets-->
    </div> <!--End of dashboard-widgets-wrap-->
</div> <!--End of content(outbody)-->
<div class="overlay">
    <div class="overlay-container"></div>
</div><!--End of overlay-->
<br class="clear"/>
<div class="edit-form corner-all">
   <div class="overlay-closer"></div>
   <div class="overlay-box-header">Modify Script</div><br class="clear"/>
   <?=form_open(null,'id="modify-script"')?>
      <div class="scripts-labels scripts-labels-resize">Script ID:</div>
      <div class="scripts-values scripts-values-resize" id="script_id"></div><br class="clear"/>
      <div class="scripts-labels scripts-labels-resize">Script Name:</div>
      <div class="scripts-values scripts-values-resize"><?=form_input('script_name',null,'id="script_name" maxlength="50" size="30"')?></div>
      <div class="scripts-labels scripts-labels-resize">Script Comments:</div>
      <div class="scripts-values scripts-values-resize"><?=form_input('script_comments',null,'id="script_comments" maxlength="255"')?></div>
      <div class="scripts-labels scripts-labels-resize"><span id="hide-for-download">Active:</span></div>
      <div class="scripts-values scripts-values-resize">
                  <?php
                         echo form_dropdown('active',array('Y'=>'Yes','N'=>'No'),null,'id="active"')."<a id='download_scriptreport'>download</a>";
                  ?>
      </div><br class="clear"/>
      <div class="scripts-labels scripts-labels-resize">Script Text:</div>
      <div class="scripts-values scripts-values-resize">
            <?php
                $attr = "id='selectField'";
                echo form_dropdown('selectField',$fields,null,$attr)."&nbsp;<a id='script-insert-field' onclick='updatetextarea(this)'>Insert</a><br class='clear'/>";
                echo form_textarea(array('id'=>"script_text",'cols'=>'50','rows'=>'10','name'=>'script_text'));
            ?>
            <a id='preview' class="preview">Preview</a>
      </div>
      <br class="clear" />
      <br class="clear" />
      <div class="script-form-action"><a id="script-update">Modify</a></div>
  <?=form_close()?>
</div>
<div class="script-preview-container corner-all">
     <div class="script-preview-close"></div>
     <div class="script-preview-display">
           <br/><span id="script-preview-id">Preview script:</span><br/><br/>
           <div class='script-preview-title'><span><strong id="script-preview-title"></strong></span></div>
           <div class="script-preview-script"></div>
     </div>
</div>
<br class="clear"/>
<div class="script-add-wizard-container corner-all">
     <div class="overlay-closer"></div>
     <div class="script-wizard-steps">
          <div class="script-wizard-breadcrumb">
                <div class="wizard-title"><strong>Script Wizard &raquo; Add New Script</strong></div>
                <?=img(array('src'=>'img/step-navigation-small.png','id'=>'script-wizard-breadcrumb'))?> 
                <br class="clear"/>
          </div>
          <div class="script-wizard-content">
                 <div class="scripts-labels"><?=img(array('src'=>'img/step1-trans.png','id'=>'wizard-step'))?></div>
                 <div class="scripts-values">
                     <div id="wizard-form-elems" class="scripts-values">
                     <?php
                         echo form_open(null,"id='script-add-wizard'");
                         #          if($user_level > 8){
                     ?>
                                    <!--   <div class="scripts-labels script-add-labels">Accounts</div>
                                       <div class="scripts-values script-add-values">
                                               <?php
                                                     #echo form_dropdown('accounts',$account,null,'id="accounts"');
                                               ?>
                                       </div> -->
                             <?php #}?>
                             <div class="scripts-labels script-add-labels"><strong>Script Type:</strong></div>
                             <div class="scripts-values script-add-values">
                                  <?php
                                         echo form_dropdown('script_type',array('default'=>'Default','advance'=>'Advance(limesurvey)'),null,'id="script_type"');
                                  ?>
                             </div>
                          <?=form_close()?>
                     </div>
                 </div>
                 <br class="clear"/>
          </div>
          <div class="script-wizard-action"><a id="step-next" rel="2" onclick="next(this)">Next</a></div>
     </div>
</div>
<br class="clear"/>
</div> <!-- wpwrap -->
<br/>
