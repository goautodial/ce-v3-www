<?php
#############################################################################################
####  Name:             go_calltimes.php                                                 ####
####  Type:             ci views - calltimes main                                        ####
####  Version:          3.0                                                              ####
####  Build:            1373428800                                                       #### 
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community ####
####  Written by:       Christopher Lomuntad <community@goautodial.com>                  ####
####  License:          AGPLv2                                                           ####
#############################################################################################
$base = base_url();
?>
<script type="text/javascript" src="<? echo $base; ?>js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="<? echo $base; ?>js/ui/1.10.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="<? echo $base; ?>js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="<? echo $base; ?>js/jquery-ui-sliderAccess.js"></script>
<script src="<? echo $base; ?>js/jquery.notify.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jquery.tablePagination.0.5.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/tooltip/jquery.tipTip.js" type="text/javascript"></script>
<script src="<? echo $base; ?>js/jscolor/jscolor.js" type="text/javascript"></script>
<style type="text/css">
body, table, td, textarea, input, select, div, span{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:13px;
	font-stretch:normal;
}

body{height:90%;}

a:hover {
	/*color:#F00;*/
	cursor:pointer;
	text-decoration:none;
}

#selectAction,#selectStateAction {
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

/* Style for overlay and box */
#overlayCalltimes{
	background:transparent url(<?php echo $base; ?>img/images/go_list/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:100;
}

#fileOverlay,#overlay,#overlayInbound,#hopperOverlay{
	background:transparent url(<?php echo $base; ?>img/images/go_list/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:102;
}

#hopperOverlay{
	background:transparent url(<?php echo $base; ?>img/images/go_list/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:104;
}

#boxCalltimes{
	position:absolute;
	top:-2550px;
	left:14%;
	right:14%;
	background-color: #FFF;
	color:#7F7F7F;
	padding:20px;

	-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	z-index:101;
}

#fileBox,#box,#boxInbound,#hopperBox{
	position:absolute;
	top:-2550px;
	left:25%;
	right:25%;
	background-color: #FFF;
	color:#7F7F7F;
	padding:20px;

	-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	z-index:103;
}

#hopperBox{
	position:absolute;
	top:-2550px;
	left:25%;
	right:25%;
	background-color: #FFF;
	color:#7F7F7F;
	padding:20px;

	-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	z-index:105;
}

#closebox,#fileClosebox,#closeboxCalltimes,#closeboxInbound,#hopperClosebox{
	float:right;
	width:26px;
	height:26px;
	background:transparent url(<?php echo $base; ?>img/images/go_list/cancel.png) repeat top left;
	margin-top:-30px;
	margin-right:-30px;
	cursor:pointer;
}

#hopperClosebox{
	float:right;
	width:26px;
	height:26px;
	background:transparent url(<?php echo $base; ?>img/images/go_list/cancel.png) repeat top left;
	margin-top:-30px;
	margin-right:-30px;
	cursor:pointer;
}

#mainTable th,#stateTable th {
	text-align:left;
}

.advance_settings {
	background-color:#EFFBEF;
	display:none;
}



.menuOn{
	font-family:Verdana, Arial, Helvetica, sans-serif;
<?php
if (preg_match("/^Windows/",$userOS))
{
	echo "padding:6px 10px 7px 10px;";
}
else
{
	echo "padding:6px 10px 6px 10px;";
}
?>
	color:#777;
	font-size:13px;
	cursor:pointer;
	background-color:#FFF;
	border-right:#CCC 1px solid;
	color:#000;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.menuOff{
	font-family:Verdana, Arial, Helvetica, sans-serif;
<?php
if (preg_match("/^Windows/",$userOS))
{
	echo "padding:6px 10px 6px 10px;";
}
else
{
	echo "padding:6px 10px 5px 10px;";
}
?>
	color:#555;
	font-size:13px;
	cursor:pointer;
	background-color:#efefef;
	border-right:#CCC 1px solid;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.menuOff:hover{
	background-color:#dfdfdf;
	color:#000;
}

#account_info_status .table_container {
	margin:9px 0px 0px -10px;
	padding:7px 10px 0px 9px;
	border-top:#CCC 1px solid;
	width:100%;
}

.go_action_menu,.go_state_action_menu{
	z-index:999;
	position:absolute;
	top:188px;
	border:#CCC 1px solid;
	background-color:#FFF;
	display:none;
	cursor:pointer;
}

#go_action_menu ul, #go_state_action_menu ul, #go_camp_status_menu ul{
	list-style-type:none;
	padding: 1px;
	margin: 0px;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.go_action_submenu, .go_state_action_submenu, .go_camp_status_submenu{
	padding: 3px 10px 3px 5px;
	margin: 0px;
}

.rightdiv{
	cursor:pointer;
	color:#555;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.hideSpan {
	display:none;
}

/* Table Sorter */
table.tablesorter thead tr .header {
	cursor: pointer;
}

#showAllLists {
	color: #F00;
	font-size: 10px;
	cursor: pointer;
}
</style>
<script>
$(function()
{
	$('.tabtoggle').click(function()
	{
		var request = $(this).attr('id');
		var currentTab = '';
		$('.tabtoggle').each(function() {
			currentTab = $(this).attr('id');
			if (request == $(this).attr('id')) {
				$(this).addClass('menuOn');
				$(this).removeClass('menuOff');
				$('#' + currentTab + '_div').show();
				$('#' + currentTab + '_add').show();
				$('#search_list').attr('placeholder','<? $this->lang->line("go_search"); ?> '+$('#' + currentTab).html());
				$('#request').html(request);
			} else {
				$(this).addClass('menuOff');
				$(this).removeClass('menuOn');
				$('#' + currentTab + '_div').hide();
				$('#' + currentTab + '_add').hide();
			}
		});
	});

	$('#closeboxCalltimes').click(function()
	{
		$('#boxCalltimes').animate({'top':'-2550px'},500,function() {
			$(this).hide();
			$("#overlayContentCalltimes").empty();
		});
		$('#overlayCalltimes').fadeOut('slow');
	});
	
	$("#showAllLists").click(function()
	{
		$(this).hide();
		$("#search_list").val('');
		//$("#table_container").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
		//$('#table_container').load('<? echo $base; ?>index.php/go_calltimes_ce/go_get_calltimes/');
		location.href = '<?=$base ?>calltimes';
	});
	
	$("#search_list_button").click(function()
	{
		$('#showAllLists').show();
		var search = $("#search_list").val();
		search = search.replace(/\s/g,"%20");
		var type = $("#request").html();
		if (search.length > 2) {
			$("#table_container").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
			$('#table_container').load('<? echo $base; ?>index.php/go_calltimes_ce/go_get_calltimes/search/'+type+'/1/'+search);
		} else {
			alert("<? echo $this->lang->line('go_entry_3_char_search'); ?>.");
		}
	});
	
	$('#search_list').bind("keydown keypress", function(event)
	{
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.type == "keydown") {
			// For normal key press
			if (event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
				|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
				|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
				return false;
			
			if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
				return false;
			
			//if (!event.shiftKey && event.keyCode == 173)
			//	return false;
		} else {
			// For ASCII Key Codes
			if ((event.which > 32 && event.which < 45) || (event.which > 45 && event.which < 48) || (event.which > 57 && event.which < 65)
				|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
				return false;
		}
		if (event.which == 13 && event.type == "keydown") {
			$('#showAllLists').show();
			var search = $("#search_list").val();
			search = search.replace(/\s/g,"%20");
			var type = $("#request").html();
			if (search.length > 2) {
				$("#table_container").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
				$('#table_container').load('<? echo $base; ?>index.php/go_calltimes_ce/go_get_calltimes/search/'+type+'/1/'+search);
			} else {
				alert("<? echo $this->lang->line('go_entry_3_char_search'); ?>.");
			}
		}
	});

	//$('#add_carrier').click(carriertype);
	
	// Tool Tip
	$(".toolTip").tipTip();
	
	$("#table_container").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#table_container').load('<? echo $base; ?>index.php/go_calltimes_ce/go_get_calltimes/');
});

function addNewCalltimes()
{
	$('#overlayCalltimes').fadeIn('fast');
	$('#boxCalltimes').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
	$('#boxCalltimes').animate({
		top: "70px"
	}, 500);

    $("#overlayContentCalltimes").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#overlayContentCalltimes').fadeOut("slow").load('<? echo $base; ?>index.php/go_calltimes_ce/go_calltimes_wizard/').fadeIn("slow");
}

function addNewStateCalltimes()
{
	$('#overlayCalltimes').fadeIn('fast');
	$('#boxCalltimes').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
	$('#boxCalltimes').animate({
		top: "70px"
	}, 500);

    $("#overlayContentCalltimes").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#overlayContentCalltimes').fadeOut("slow").load('<? echo $base; ?>index.php/go_calltimes_ce/go_state_calltimes_wizard/').fadeIn("slow");
}

function modify(calltime,type)
{
	$('#overlayCalltimes').fadeIn('fast');
	$('#boxCalltimes').css({'width': '760px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
	$('#boxCalltimes').animate({
		top: "70px"
	}, 500);
	
	if (type===undefined)
		type='';

	$("#overlayContentCalltimes").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#overlayContentCalltimes').fadeOut("slow").load('<? echo $base; ?>index.php/go_calltimes_ce/go_get_calltimes/view/'+calltime+'/'+type).fadeIn("slow");
}

function delCalltimes(calltime,type)
{
	//var r=confirm("Are you sure you want to delete '"+calltime+"' from the list?");
	var r=confirm("<? echo $this->lang->line("go_del_con"); ?> '"+calltime+"' <? echo $this->lang->line("go_frm_list"); ?>?");
	if (r)
	{
		if (type===undefined)
			type='';
		
		$.post("<?=$base?>index.php/go_calltimes_ce/go_get_calltimes/delete/"+calltime+"/"+type,
			function(data){
			if (data=="SUCCESS")
			{
				//alert("Success!\n\nCall time '"+calltime+"' has been deleted.");
                                alert("<? echo $this->lang->line("go_success"); ?>!\n\n<? echo $this->lang->line("go_call_time"); ?> '"+calltime+"' <? echo $this->lang->line("go_been_del"); ?>.");
				
				location.reload();
			} else {
				alert(data);
			}
	
		});
	}
}

function modifyCampaign(camp)
{
	$('#overlay').fadeIn('fast');
	$('#box').css({'width': '860px', 'left': '14%', 'right': '14%', 'margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
	$('#box').animate({
		top: "70px"
	}, 500);
	
	$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_get_settings/'+camp).fadeIn("slow");
}

function modifyInbound(groupid) {

	document.getElementById('showval').value=groupid;
	document.getElementById('showvaledit').value=groupid;

	$('#goLoading').show();
	$('#advanceid').hide();
	$('#clickadvanceplus').show();
	$('#clickadvanceminus').hide();
	$('#overlayInbound').fadeIn('fast',function(){
		$('#boxInbound').show('fast');
		$('#boxInbound').css({'width': '860px', 'left': '14%', 'right': '14%', 'margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
		$('#boxInbound').animate({'top':'2775px'},500);
	});
	 
	$('#closeboxInbound').click(function(){
		$('#boxInbound').animate({'top':'-2550px'},500,function(){
			$('#overlayInbound').fadeOut('fast');
			
		});
	});	
	var items = $('.showlistview').serialize();
	$.post("<?=$base?>index.php/go_ingroup/editview", { items: items, action: "editlist" },
	function(data){
		var datas = data.split("##");
		var i=0;
		var count_listid = datas.length;

		for (i=0;i<count_listid;i++) {
			if(datas[i]=="") {
				datas[i]=" ";
			}
				
			document.getElementById('egroup_id').innerHTML=datas[0];
			document.getElementById('egroup_name').value=datas[1];
			document.getElementById('egroup_color').value=datas[2];
			document.getElementById('escript_id').value=datas[8];
			document.getElementById('eactive').value=datas[3];
			document.getElementById('eweb_form_address').value=datas[4];
			document.getElementById('enext_agent_call').value=datas[6];
			document.getElementById('equeue_priority').value=datas[29];
			document.getElementById('eon_hook_ring_time').value=datas[101];
			document.getElementById('efronter_display').value=datas[7];
			document.getElementById('eget_call_launch').value=datas[9];
			document.getElementById('exferconf_a_dtmf').value=datas[10];
			document.getElementById('exferconf_a_number').value=datas[11];
			document.getElementById('exferconf_b_dtmf').value=datas[12];
			document.getElementById('exferconf_b_number').value=datas[13];
			document.getElementById('exferconf_c_number').value=datas[65];
			document.getElementById('exferconf_d_number').value=datas[66];
			document.getElementById('exferconf_e_number').value=datas[67];
			document.getElementById('etimer_action').value=datas[60];
			document.getElementById('etimer_action_message').value=datas[61];
			document.getElementById('etimer_action_seconds').value=datas[62];
			document.getElementById('etimer_action_destination').value=datas[95];
			document.getElementById('edrop_call_seconds').value=datas[14];
			document.getElementById('edrop_action').value=datas[15];
			document.getElementById('edrop_exten').value=datas[16];
			document.getElementById('evoicemail_ext').value=datas[5];
			document.getElementById('eafter_hours_action').value=datas[18];
			document.getElementById('eignore_list_script_override').value=datas[68]; 
			document.getElementById('after_hours_message_filename').value=datas[19];  
			document.getElementById('eafter_hours_exten').value=datas[20]; 
			document.getElementById('after_hours_voicemail').value=datas[21];  
			document.getElementById('eno_agent_no_queue').value=datas[56]; 
			document.getElementById('no_agent_action').value=datas[57]; 
			document.getElementById('welcome_message_filename').value=datas[22];  
			document.getElementById('eplay_welcome_message').value=datas[52];
			document.getElementById('moh_context').value=datas[23];      
			document.getElementById('onhold_prompt_filename').value=datas[24];
			document.getElementById('prompt_interval').value=datas[25];
			document.getElementById('onhold_prompt_no_block').value=datas[75];
			document.getElementById('onhold_prompt_seconds').value=datas[76];
			document.getElementById('play_place_in_line').value=datas[41];
			document.getElementById('play_estimate_hold_time').value=datas[42];
			document.getElementById('calculate_estimated_hold_seconds').value=datas[96];
			document.getElementById('eht_minimum_prompt_filename').value=datas[98];
			document.getElementById('eht_minimum_prompt_no_block').value=datas[99];
			document.getElementById('eht_minimum_prompt_seconds').value=datas[100];
			document.getElementById('wait_time_option').value=datas[82];
			document.getElementById('wait_time_second_option').value=datas[83];
			document.getElementById('wait_time_third_option').value=datas[84];
			document.getElementById('wait_time_option_seconds').value=datas[85];
			document.getElementById('wait_time_option_exten').value=datas[86];
			document.getElementById('wait_time_option_voicemail').value=datas[87];
			document.getElementById('wait_time_option_press_filename').value=datas[92];
			document.getElementById('wait_time_option_no_block').value=datas[93];
			document.getElementById('wait_time_option_prompt_seconds').value=datas[94];
			document.getElementById('wait_time_option_callback_filename').value=datas[90];
			document.getElementById('wait_time_option_callback_list_id').value=datas[91];
			document.getElementById('wait_hold_option_priority').value=datas[81];
			document.getElementById('hold_time_option').value=datas[43];
			document.getElementById('hold_time_second_option').value=datas[79];
			document.getElementById('hold_time_option_seconds').value = datas[44];
			document.getElementById('hold_time_option_minimum').value = datas[72];
			document.getElementById('hold_time_option_exten').value=datas[45];
			document.getElementById('hold_time_option_voicemail').value=datas[46];
			document.getElementById('hold_time_option_press_filename').value=datas[73];
			document.getElementById('hold_time_option_no_block').value=datas[77];
			document.getElementById('hold_time_option_prompt_seconds').value=datas[78];
			document.getElementById('hold_time_option_callback_filename').value=datas[48];
			document.getElementById('hold_time_option_callback_list_id').value=datas[49];
			document.getElementById('agent_alert_exten').value=datas[26];
			document.getElementById('agent_alert_delay').value=datas[27];
			document.getElementById('no_delay_call_route').value=datas[51];
			document.getElementById('ingroup_recording_override').value=datas[31];
			document.getElementById('ingroup_rec_filename').value =datas[32];
			document.getElementById('answer_sec_pct_rt_stat_one').value=datas[53];
			document.getElementById('answer_sec_pct_rt_stat_two').value=datas[54];
			document.getElementById('start_call_url').value=datas[63];
			document.getElementById('dispo_call_url').value=datas[64];
			document.getElementById('add_lead_url').value=datas[97];
			document.getElementById('extension_appended_cidname').value = datas[69];
			document.getElementById('uniqueid_status_display').value = datas[70];
			document.getElementById('uniqueid_status_prefix').value = datas[71];
			document.getElementById('edrop_inbound_group').value=datas[30];
			document.getElementById('ecall_time_id').value=datas[17];
			document.getElementById('afterhours_xfer_group').value=datas[33];
			document.getElementById('wait_time_option_callmenu').value=datas[89];
			document.getElementById('wait_time_option_xfer_group').value=datas[88];
			document.getElementById('hold_time_option_callmenu').value=datas[74];
			document.getElementById('hold_time_option_xfer_group').value=datas[47];
			document.getElementById('default_xfer_group').value=datas[28];
			document.getElementById('hold_recall_xfer_group').value=datas[50];
			document.getElementById('default_group_alias').value=datas[55];
		}
		
		$('#goLoading').hide();
	});
}

function editpost(groupID) {
	document.getElementById('showval').value=groupID;
	document.getElementById('showvaledit').value=groupID;

	var itemsumit = $('#edit_go_listfrm').serialize();
	$.post("<?=$base?>index.php/go_ingroup/editsubmit", { itemsumit: itemsumit, action: "editlistfinal" },
	function(data){
		var datas = data.split(":");
		var i=0;
		var count_listid = datas.length;
		
		for (i=0;i<count_listid;i++) {

				if(datas[i]=="") {
						datas[i]=" ";
				}
		}
		
		if(datas[0]=="SUCCESS") {
			alert("<? echo $this->lang->line("go_success_caps"); ?>");
			location.reload();
		}
		
		$('#closeboxInbound').click(function(){
			$('#boxInbound').animate({'top':'-2550px'},500,function(){
				$('#overlayInbound').fadeOut('fast');
			});
		});	
	});
}
  
function launch_chooser(fieldname,stage,vposition,defvalue) {
	$.post("<?=$base?>index.php/go_ingroup/chooser", { action: "sounds_list",user: "<?=$gouser?>", format: "selectframe", stage: stage, comments: fieldname, defaultvalue: defvalue  },
	function(data){
		document.getElementById("div"+fieldname).innerHTML=data;
		$('#tbl'+fieldname).show();
	});	
}

function setDivVal(divid,idval) {
	document.getElementById(divid).value=idval;
}
	
function launch_vm_chooser(fieldname,stage,vposition,defvalue) {
	$.post("<?=$base?>index.php/go_ingroup/chooser", { action: "vm_list",user: "<?=$gouser?>", format: "selectframe", stage: stage, comments: fieldname, defaultvalue: defvalue  },
	function(data){
		document.getElementById("div"+fieldname).innerHTML=data;
	});	
}

function launch_moh_chooser(fieldname,stage,vposition,defvalue) {
	$.post("<?=$base?>index.php/go_ingroup/chooser", { action: "moh_list",user: "<?=$gouser?>", format: "selectframe", stage: stage, comments: fieldname, defaultvalue: defvalue  },
	function(data){
		document.getElementById("div"+fieldname).innerHTML=data;
	});	
}

function closeme() {
	$('#boxInbound').animate({'top':'-2550px'},500,function(){
	$('#overlayInbound').fadeOut('fast');
	$('#boxInbound').hide('fast');
	});
}
</script>
<a name="top"></a>
<div id='outbody' class="wrap">
<div id="icon-calltimes" class="icon32">
</div>
<div style="float: right;margin-top:15px;margin-right:25px;"><span id="showAllLists" style="display: none">[<? echo $this->lang->line("go_clear_search"); ?>]</span>&nbsp;<?=form_input('search_list',null,'id="search_list" maxlength="100" size="25" placeholder="'.$this->lang->line("go_search").' '.$bannertitle.'"') ?>&nbsp;<img src="<?=base_url()."img/spotlight-black.png"; ?>" id="search_list_button" style="cursor: pointer;" /></div>
<h2><? echo $bannertitle; ?></h2>

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">

			<!-- START LEFT WIDGETS -->
			<div class="postbox-container" style="width:99%;min-width:1200px;">
				<div class="meta-box-sortables ui-sortable">

					<!-- GO WIDGET -->
					<div id="account_info_status" class="postbox">
						<div class="rightdiv toolTip" id="showList_add" onclick="addNewCalltimes()" title="<? echo $this->lang->line("go_add_new_call_time"); ?>">
                        	<? echo $this->lang->line("go_add_new_call_time"); ?> <img src="<?php echo $base; ?>img/cross.png" style="height:14px; vertical-align:middle;display:none;" />
						</div>
						<div class="rightdiv toolTip" style="display:none" onclick="addNewStateCalltimes()" id="showState_add" title="<? echo $this->lang->line("go_add_state_call_time"); ?>">
                        	<? echo $this->lang->line("go_add_state_call_time"); ?> <img src="<?php echo $base; ?>img/cross.png" style="height:14px; vertical-align:middle;display:none;" />
						</div>
						<div class="hndle">
							<span><span id="title_bar" />&nbsp;</span><!-- Title Bar -->
								<span class="postbox-title-action"></span>
							</span>
						</div>
						<div class="inside">

                            <div style="margin:<?php echo (preg_match("/^Windows/",$userOS)) ? "-23px" : "-22px"; ?> 0px -2px -10px;" id="request_tab"><span id="showList" class="tabtoggle menuOn"><? echo $this->lang->line("go_call_times"); ?></span><span id="showState" class="tabtoggle menuOff"><? echo $this->lang->line("go_state_call_times"); ?></span><span id="request" style="display:none;"><? echo $this->lang->line("go_show_list"); ?></span></div>

							<div id="table_container" class="table_container">
                            </div>

							<div class="widgets-content-text">
							<br class="clear">

								<br class="clear">
							<br class="clear">
							<!--
							<p>
								Configure this widget's (today's status) settings.
								<a href="" class="button">Configure</a>
							</p>
							<p>
								You are using GoAutoDial 3.0.1.
								<a href="" class="button">Update to 3.1.3</a>
							</p>
							-->
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- END LEFT WIDGETS -->

			<!-- START RIGHT WIDGETS -->
			<div class="postbox-container" style="width:49.5%;display:none;">
				<div class="meta-box-sortables ui-sortable">

				</div>
			</div>
			<!-- END RIGHT WIDGETS -->

			<div class="postbox-container" style="display:none;width:49.5%;">
				<div style="" id="column3-sortables" class="meta-box-sortables ui-sortable">
				</div>
			</div>
			<div class="postbox-container" style="display:none;width:49.5%;">
				<div style="" id="column4-sortables" class="meta-box-sortables ui-sortable">
				</div>
			</div>
		</div><!-- dashboard-widgets -->
	</div><!-- dashboard-widgets-wrap -->
</div><!-- wrap -->

<!-- Overlay1 -->
<div id="overlay" style="display:none;"></div>
<div id="box">
<a id="closebox" class="toolTip" title="<? echo strtoupper($this->lang->line("go_close")); ?>"></a>
<div id="overlayContent"></div>
</div>

<!-- Overlay2 -->
<div id="overlayCalltimes" style="display:none;"></div>
<div id="boxCalltimes">
<a id="closeboxCalltimes" class="toolTip" title="<? echo strtoupper($this->lang->line("go_close")); ?>"></a>
<div id="overlayContentCalltimes"></div>
</div>

<!-- Hopper Overlay -->
<div id="hopperOverlay" style="display:none;"></div>
<div id="hopperBox">
<a id="hopperClosebox" class="toolTip" title="<? echo strtoupper($this->lang->line("go_close")); ?>"></a>
<div id="hopperOverlayContent"></div>
</div>

<!-- File Overlay -->
<span id="audioButtonClicked" style="display:none;"></span>
<div id="fileOverlay" style="display:none;"></div>
<div id="fileBox" style="display:none">
<a id="fileClosebox" class="toolTip" title="<? echo strtoupper($this->lang->line("go_close")); ?>"></a>
<div id="fileOverlayContent">
<table style="width: 100%">
	<tr>
		<td><? echo $this->lang->line("go_list_files_upload"); ?>:</td>
	</tr>
<?php
$WeBServeRRooT = '/var/lib/asterisk';
$storage = 'sounds';
if(is_dir($WeBServeRRooT.'/'.$storage)){
	if ($handle = opendir($WeBServeRRooT.'/'.$storage)) {
		$ctr = 0;
		while (false !== ($file = readdir($handle))) {
			if($file != '..' && $file != "."){
				$filename =  substr($file,0,3);
				if($filename == "go_" || $filename == "GO_"){
					$fname = str_replace(".wav","",$file);
					if ($ctr % 2 == 0)
						echo "<tr>";
					$showfname = (strlen($fname) > 45) ? substr($fname,0,45)."..." : $fname;
					echo "<td style='white-space:nowrap;width:50%'>&raquo; <a id='$ctr' href='#' title='$fname'>$showfname</a>\n";
					echo "<script>
							$('#$ctr').click(function (){
								var input_id = $('#audioButtonClicked').text();
								$('input#'+input_id+'_afterhours_filename_override').val($('#$ctr').text());
							});
						</script>";
					echo "&nbsp;&nbsp;</td>";
					
					if ($ctr % 2 == 1)
						echo "</tr>\n";
					
					$ctr++;
				}
			}
		}
		closedir($handle);
	}
}
?>
</table>
</div>
</div>

<!-- Action Menu -->
<div id='go_action_menu' class='go_action_menu'>
<ul>
<li class="go_action_submenu" title="<? echo strtoupper($this->lang->line('go_activated_selected')); ?>" id="activate" style="display:none;"><? echo $this->lang->line("go_activated_selected"); ?></li>
<li class="go_action_submenu" title="<? echo strtoupper($this->lang->line('go_deactivate_Selected')); ?>" id="deactivate" style="display:none;"><? echo $this->lang->line("go_deactivate_Selected"); ?></li>
<li class="go_action_submenu" title="<? echo $this->lang->line("go_del_selected"); ?>" id="delete"><? echo $this->lang->line("go_del_selected"); ?></li>
</ul>
</div>

<!-- State Action Menu -->
<div id='go_state_action_menu' class='go_state_action_menu'>
<ul>
<li class="go_state_action_submenu" title="<? echo strtoupper($this->lang->line('go_activated_selected')); ?>" id="activate" style="display:none;"><? echo $this->lang->line("go_activate_selected"); ?></li>
<li class="go_state_action_submenu" title="<? echo strtoupper($this->lang->line('go_deactivate_Selected')); ?>" id="deactivate" style="display:none;"><? echo $this->lang->line("go_deactivate_selected"); ?></li>
<li class="go_state_action_submenu" title="<? echo $this->lang->line("go_del_selected"); ?>" id="delete"><? echo $this->lang->line("go_del_selected"); ?></li>
</ul>
</div>

<!-- edit -->
<div class="overlayInbound" id="overlayInbound" style="display:none;"></div>
<div class="boxInbound" id="boxInbound" style=" margin-top: -2700px;">
<center>
<a class="closeboxInbound" id="closeboxInbound" onclick="closeme();"></a>
<div style="z-index: 999;position: absolute;height: 95%;width:95%;background-color:#FFF;" id="goLoading"><center><br /><br /><br /><br /><br /><br /><br /><br /><img src="<? echo $base; ?>img/goloading.gif" /></center></div>
<input type="hidden" class="showlistview" name="showval" id="showval">
<form  method="POST" id="edit_go_listfrm" name="edit_go_listfrm">
	<input type="hidden" name="editlist" value="editlist">
	<input type="hidden" name="editval" id="editval">
	<input type="hidden" name="showvaledit" id="showvaledit" value="">
	<!--<input type="hidden" name="oldcampaignid" id="oldcampaignid" value="">-->

	
	<div id="listid_edit" align="left" class="title-header"> </div>
	<div align="center" class="title-header" style="font-size:16px;color:#000;font-weight:bold;"> <? echo strtoupper($this->lang->line("go_modify_inbound")); ?>: <label id="egroup_id"></label></div>
	<br>
	<div align="left">
	</div>  
	<div style="width: auto;">
	<?php
	
	## callmenu pulldown
	$countmenus = count($callmenupulldown);
	if($countmenus > 0){
		foreach($callmenupulldown as $callmenupulldownInfo){
			$menu_id = $callmenupulldownInfo->menu_id;
			$menu_name = $callmenupulldownInfo->menu_name;
			
			$Xmenuslist .= "<option ";
			$Xmenuslist .= "value=\"$menu_id\">$menu_id - $menu_name</option>\n";
		}
	}
	
	if ($Xmenus_selected < 1) 
	{
		$Xmenuslist .= "<option SELECTED value=\"---NONE---\">---{$this->lang->line("go_none")}---</option>\n";
	}
	
	## ingroup pulldown
	$Xgroups_menu='';
	$Xgroups_selected=0;
	$Dgroups_menu='';
	$Dgroups_selected=0;
	$Agroups_menu='';
	$Agroups_selected=0;
	$Hgroups_menu='';
	$Hgroups_selected=0;
	$Tgroups_menu='';
	$Tgroups_selected=0;

	foreach($ingrouppulldown as $ingrouppulldownInfo){
		$group_id = $ingrouppulldownInfo->group_id;
		$group_name = $ingrouppulldownInfo->group_name;
		
		$Xgroups_menu .= "<option ";
		$Dgroups_menu .= "<option ";
		$Agroups_menu .= "<option ";
		$Tgroups_menu .= "<option ";
		$Hgroups_menu .= "<option ";
		
		if ($default_xfer_group == "$group_id") 
			{
			$Xgroups_menu .= "SELECTED ";
			$Xgroups_selected++;
			}
		if ($drop_inbound_group == "$group_id") 
			{
			$Dgroups_menu .= "SELECTED ";
			$Dgroups_selected++;
			}
		if ($afterhours_xfer_group == "$group_id") 
			{
			$Agroups_menu .= "SELECTED ";
			$Agroups_selected++;
			}
		if ($hold_time_option_xfer_group == "$group_id") 
			{
			$Tgroups_menu .= "SELECTED ";
			$Tgroups_selected++;
			}
		if ($hold_recall_xfer_group == "$group_id") 
			{
			$Hgroups_menu .= "SELECTED ";
			$Hgroups_selected++;
			}
		$Xgroups_menu .= "value=\"$group_id\">$group_id - $group_name</option>\n";
		$Dgroups_menu .= "value=\"$group_id\">$group_id - $group_name</option>\n";
		$Agroups_menu .= "value=\"$group_id\">$group_id - $group_name</option>\n";
		$Tgroups_menu .= "value=\"$group_id\">$group_id - $group_name</option>\n";
		$Hgroups_menu .= "value=\"$group_id\">$group_id - $group_name</option>\n";
	} // end foreach
	
	if ($Xgroups_selected < 1) 
		{$Xgroups_menu .= "<option SELECTED value=\"---NONE---\">---{$this->lang->line("go_none")}---</option>\n";}
	else 
		{$Xgroups_menu .= "<option value=\"---NONE---\">---{$this->lang->line("go_none")}---</option>\n";}
	if ($Dgroups_selected < 1) 
		{$Dgroups_menu .= "<option SELECTED value=\"---NONE---\">---{$this->lang->line("go_none")}---</option>\n";}
	else 
		{$Dgroups_menu .= "<option value=\"---NONE---\">---{$this->lang->line("go_none")}---</option>\n";}
	if ($Agroups_selected < 1) 
		{$Agroups_menu .= "<option SELECTED value=\"---NONE---\">---{$this->lang->line("go_none")}---</option>\n";}
	else 
		{$Agroups_menu .= "<option value=\"---NONE---\">---{$this->lang->line("go_none")}---</option>\n";}
	if ($Tgroups_selected < 1) 
		{$Tgroups_menu .= "<option SELECTED value=\"---NONE---\">---{$this->lang->line("go_none")}---</option>\n";}
	else 
		{$Tgroups_menu .= "<option value=\"---NONE---\">---{$this->lang->line("go_none")}---</option>\n";}
	if ($Hgroups_selected < 1) 
		{$Hgroups_menu .= "<option SELECTED value=\"---NONE---\">---{$this->lang->line("go_none")}---</option>\n";}
	else 
		{$Hgroups_menu .= "<option value=\"---NONE---\">---{$this->lang->line("go_none")}---</option>\n";}
	
	echo "<center><TABLE width=95% class=\"tableeditingroup\" style=\"color:#000;\">\n";
	echo "<tr class=trview><td align=right>Description: </td><td align=left><input type=text name=group_name id=egroup_name size=30 maxlength=30 onkeydown=\"return isAlphaNumericwspace(event.keyCode);\" onkeyup=\"KeyUp(event.keyCode);\"></td></tr>\n";
	echo "<tr class=trview><td align=right>Color: </td><td align=left id=\"group_color_td\"><input class=color type=text name=group_color id=egroup_color size=7 maxlength=7></td></tr>\n";
	echo "<tr class=trview><td align=right>Active: </td><td align=left><select size=1 name=active id=eactive><option>Y</option><option>N</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Web Form: </td><td align=left><input type=text id=eweb_form_address name=web_form_address size=70 maxlength=500></td></tr>\n";
	echo "<tr class=trview><td align=right>Next Agent Call: </td><td align=left><select size=1 name=next_agent_call id=enext_agent_call><option >random</option><option>oldest_call_start</option><option>oldest_call_finish</option><option>overall_user_level</option><option>inbound_group_rank</option><option>campaign_rank</option><option>fewest_calls</option><option>fewest_calls_campaign</option><option>longest_wait_time</option><option>ring_all</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Queue Priority: </td><td align=left><select size=1 name=queue_priority id=equeue_priority>\n";
	$n=99;
	while ($n>=-99) {
		$dtl = 'Even';
		if ($n<0) {$dtl = 'Lower';}
		if ($n>0) {$dtl = 'Higher';}
		if ($n == $queue_priority)
				{echo "<option SELECTED value=\"$n\">$n - $dtl</option>\n";}
		else
				{echo "<option value=\"$n\">$n - $dtl</option>\n";}
		$n--;
	}
	echo "</select> </td></tr>\n";
	echo "<tr class=trview><td align=right>Fronter Display: </td><td align=left><select size=1 name=fronter_display id=efronter_display><option>Y</option><option>N</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Script: </td><td align=left>";
	$scripts_listS="<option value=\"\">NONE</option>\n";
	$countscipts = count($scriptlists);
	if($scriptlists > 0) {
		foreach($scriptlists as $scriptlistsInfo){
			$script_id = $scriptlistsInfo->script_id;
			$script_name = $scriptlistsInfo->script_name;
			$scripts_listS .= "<option value=\"$script_id\">$script_id - $script_name</option>\n";
			$scriptname_listS["$script_id"] = "$script_name";
			//echo '<option value="'.$script_id.'">'.$script_id.'---'.$script_name.'</option>';
		}
	}
	
	echo "<select size=\"1\" name=\"ingroup_script\" id=\"escript_id\">";
	echo "$scripts_listS";
	echo "</select></td></tr>";
	echo "<tr><td colspan=\"2\" align=\"left\">&nbsp;</td></tr>";
	echo "<tr><td colspan=\"2\" align=\"left\">";
	echo "<a id=\"clickadvanceplus\" style=\"cursor: pointer;\" onclick=\"$('#advanceid').css('display', 'block'); $('#clickadvanceplus').css('display', 'none'); $('#clickadvanceminus').css('display', 'block');  \"><font color=\"#7A9E22\" size=\"1px\">[ + ADVANCE SETTINGS ]</font></a><a id=\"clickadvanceminus\" style=\"cursor: pointer; display: none;\" onclick=\"$('#advanceid').css('display', 'none'); $('#clickadvanceplus').css('display', 'block'); $('#clickadvanceminus').css('display', 'none');\"><font color=\"#7A9E22\" size=\"1px\">[ - ADVANCE SETTINGS ]</font></a></td></tr>";
	echo "</table>";
	echo "<TABLE width=\"95%\" class=\"tableadvace\" id=\"advanceid\" style=\"color:#000;\">\n";
	echo "<tr class=trview><td align=right>On-Hook Ring Time: </td><td align=left><input type=text name=on_hook_ring_time id=eon_hook_ring_time size=5 maxlength=4 ></td></tr>\n";
	echo "<tr class=trview><td align=right>Ignore List Script Override: </td><td align=left><select size=1 name=ignore_list_script_override id=eignore_list_script_override><option>Y</option><option>N</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Get Call Launch: </td><td align=left> <select name=get_call_launch id=eget_call_launch><option selected=\"\">NONE</option><option>SCRIPT</option><option>WEBFORM</option><option>FORM</option></select></td></tr>";
	echo "<tr class=trview><td align=right>Transfer-Conf DTMF 1: </td><td align=left><input type=text name=xferconf_a_dtmf id=exferconf_a_dtmf size=20 maxlength=50></td></tr>\n";
	echo "<tr class=trview><td align=right>Transfer-Conf Number 1: </td><td align=left><input type=text name=xferconf_a_number id=exferconf_a_number size=20 maxlength=50></td></tr>\n";
	echo "<tr class=trview><td align=right>Transfer-Conf DTMF 2: </td><td align=left><input type=text name=xferconf_b_dtmf id=exferconf_b_dtmf size=20 maxlength=50></td></tr>\n";
	echo "<tr class=trview><td align=right>Transfer-Conf Number 2: </td><td align=left><input type=text name=xferconf_b_number id=exferconf_b_number size=20 maxlength=50></td></tr>\n";
	echo "<tr class=trview><td align=right>Transfer-Conf Number 3: </td><td align=left><input type=text name=xferconf_c_number id=exferconf_c_number size=20 maxlength=50></td></tr>\n";
	echo "<tr class=trview><td align=right>Transfer-Conf Number 4: </td><td align=left><input type=text name=xferconf_d_number id=exferconf_d_number size=20 maxlength=50></td></tr>\n";
	echo "<tr class=trview><td align=right>Transfer-Conf Number 5: </td><td align=left><input type=text name=xferconf_e_number id=exferconf_e_number size=20 maxlength=50></td></tr>\n";
	echo "<tr class=trview><td align=right>Timer Action: </td><td align=left><select size=1 name=timer_action id=etimer_action><option selected>NONE</option><option>D1_DIAL</option><option>D2_DIAL</option><option>D3_DIAL</option><option>D4_DIAL</option><option>D5_DIAL</option><option>MESSAGE_ONLY</option><option>WEBFORM</option><option>HANGUP</option><option>CALLMENU</option><option>EXTENSION</option><option>IN_GROUP</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Timer Action Message: </td><td align=left><input type=text name=timer_action_message id=etimer_action_message size=50 maxlength=255></td></tr>\n";
	echo "<tr class=trview><td align=right>Timer Action Seconds: </td><td align=left><input type=text name=timer_action_seconds id=etimer_action_seconds size=10 maxlength=10></td></tr>\n";
	echo "<tr class=trview><td align=right>Timer Action Destination: </td><td align=left><input type=text name=timer_action_destination id=etimer_action_destination size=25 maxlength=30></td></tr>\n";
	echo "<tr class=trview><td align=right>Drop Call Seconds: </td><td align=left><input type=text name=drop_call_seconds id=edrop_call_seconds size=5 maxlength=4></td></tr>\n";
	echo "<tr class=trview><td align=right>Drop Action: </td><td align=left><select size=1 name=drop_action id=edrop_action><option>HANGUP</option><option>MESSAGE</option><option>VOICEMAIL</option><option>IN_GROUP</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Drop Exten: </td><td align=left><input type=text name=drop_exten id=edrop_exten size=10 maxlength=20></td></tr>\n";
	echo "<tr class=trview><td align=right>Voicemail: </td><td align=left><input type=text name=voicemail_ext id=evoicemail_ext size=12 maxlength=10> <a href=\"javascript:launch_vm_chooser('evoicemail_ext','vm',500,document.getElementById('evoicemail_ext').value);\"><FONT color=\"blue\">[ Voicemail Chooser ]</a><div id=\"divevoicemail_ext\"></div></td></tr>\n";
	echo "<tr bgcolor=#99FFCC><td align=right>Drop Transfer Group: </td><td align=left><select size=1 name=drop_inbound_group id=edrop_inbound_group>";
	echo "$Dgroups_menu";
	echo "</select></td></tr>\n";
	echo "<tr class=trview><td align=right>Call Time: </td><td align=left><select size=1 name=call_time_id id=ecall_time_id>\n";

	foreach($calltimespulldown as $calltimespulldownInfo){
		$call_time_id = $calltimespulldownInfo->call_time_id;
		$call_time_name = $calltimespulldownInfo->call_time_name;
		$selected_time="selected";
		$call_times_list .= "<option value=\"$call_time_id\" $selected_time>$call_time_id - $call_time_name</option>\n";
	}
		  
	echo "$call_times_list";
	echo "<option selected value=\"$call_time_id\">$call_time_id - $call_timename_list[$call_time_id]</option>\n";
	echo "</select></td></tr>\n";
	echo "<tr class=trview><td align=right>After Hours Action: </td><td align=left><select size=1 name=after_hours_action id=eafter_hours_action><option>HANGUP</option><option>MESSAGE</option><option>EXTENSION</option><option>VOICEMAIL</option><option>IN_GROUP</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>After Hours Message Filename: </td><td align=left><input type=text name=after_hours_message_filename id=after_hours_message_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('after_hours_message_filename','date',600,document.getElementById('after_hours_message_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a><div id=\"divafter_hours_message_filename\"></div> </td></tr>\n";
	echo "<t><td align=right>After Hours Extension: </td><td align=left><input type=text name=after_hours_exten id=eafter_hours_exten size=10 maxlength=20></td></tr>\n";
	echo "<tr class=trview><td align=right>After Hours Voicemail: </td><td align=left><input type=text name=after_hours_voicemail id=after_hours_voicemail size=12 maxlength=10> <a href=\"javascript:launch_vm_chooser('after_hours_voicemail','vm',700,document.getElementById('after_hours_voicemail').value);\"><FONT color=\"blue\">[ Voicemail Chooser ]</font></a><div id=\"divafter_hours_voicemail\"></div></td></tr>\n";
	echo "<tr class=trview><td align=right>After Hours Transfer Group: </td><td align=left><select size=1 name=afterhours_xfer_group id=afterhours_xfer_group>";
	echo "<option value=\"\">--NONE--</option>\n";
	echo "$Agroups_menu";
	echo "</select></td></tr>\n";
	echo "<tr class=trview><td align=right>No Agents No Queueing: </td><td align=left><select size=1 name=no_agent_no_queue id=eno_agent_no_queue><option>Y</option><option>N</option><option>NO_PAUSED</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>No Agent No Queue Action: </td><td align=left><select size=1 name=no_agent_action id=no_agent_action onChange=\"dynamic_call_action('no_agent_action','$no_agent_action','$no_agent_action_value','600');\"><option>CALLMENU</option><option>INGROUP</option><option>DID</option><option>MESSAGE</option><option>EXTENSION</option><option>VOICEMAIL</option></select>\n";				
	echo "<tr class=trview><td align=right>Welcome Message Filename: </td><td align=left><input type=text name=welcome_message_filename id=welcome_message_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('welcome_message_filename','date',800,document.getElementById('welcome_message_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a> <div id=\"divwelcome_message_filename\"></div> </td></tr>\n";
	echo "<tr class=trview><td align=right>Play Welcome Message: </td><td align=left><select size=1 name=play_welcome_message id=eplay_welcome_message><option>ALWAYS</option><option>NEVER</option><option>IF_WAIT_ONLY</option><option>YES_UNLESS_NODELAY</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Music On Hold Context: </td><td align=left><input type=text name=moh_context id=moh_context size=30 maxlength=50> <a href=\"javascript:launch_moh_chooser('moh_context','moh',800,document.getElementById('moh_context').value);\"><FONT color=\"blue\">[ Moh Chooser ]</font></a> <div id=\"divmoh_context\"></div></td></tr>\n";
	echo "<tr class=trview><td align=right>On Hold Prompt Filename: </td><td align=left><input type=text name=onhold_prompt_filename id=onhold_prompt_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('onhold_prompt_filename','date',800,document.getElementById('onhold_prompt_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a> <div id=\"divonhold_prompt_filename\"></div></td></tr>\n";
	echo "<tr class=trview><td align=right>On Hold Prompt Interval: </td><td align=left><input type=text name=prompt_interval id=prompt_interval size=5 maxlength=5></td></tr>\n";
	echo "<tr class=trview><td align=right>On Hold Prompt No Block: </td><td align=left><select size=1 name=onhold_prompt_no_block id=onhold_prompt_no_block><option>N</option><option>Y</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>On Hold Prompt Seconds: </td><td align=left><input type=text name=onhold_prompt_seconds id=onhold_prompt_seconds size=5 maxlength=5></td></tr>\n";
	echo "<tr class=trview><td align=right>Play Place in Line: </td><td align=left><select size=1 name=play_place_in_line id=play_place_in_line><option>Y</option><option>N</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Play Estimated Hold Time: </td><td align=left><select size=1 name=play_estimate_hold_time id=play_estimate_hold_time><option>Y</option><option>N</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Calculate Estimated Hold Seconds: </td><td align=left><input type=text name=calculate_estimated_hold_seconds id=calculate_estimated_hold_seconds size=5 maxlength=5></td></tr>\n";
	echo "<tr class=trview><td align=right>Estimated Hold Time Minimum Filename: </td><td align=left><input type=text name=eht_minimum_prompt_filename id=eht_minimum_prompt_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('eht_minimum_prompt_filename','date',800,document.getElementById('eht_minimum_prompt_filename').value);\"><FONT color=\"blue\"> [ Audio Chooser ]</font></a> <div id=\"diveht_minimum_prompt_filename\"></div> </td></tr>\n";
	echo "<tr class=trview><td align=right>Estimated Hold Time Minimum Prompt No Block: </td><td align=left><select size=1 name=eht_minimum_prompt_no_block id=eht_minimum_prompt_no_block><option>N</option><option>Y</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Estimated Hold Time Minimum Prompt Seconds: </td><td align=left><input type=text name=eht_minimum_prompt_seconds id=eht_minimum_prompt_seconds size=5 maxlength=5></td></tr>\n";
	echo "<tr class=trview><td align=right>Wait Time Option: </td><td align=left><select size=1 name=wait_time_option id=wait_time_option><option>NONE</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Wait Time Second Option: </td><td align=left><select size=1 name=wait_time_second_option id=wait_time_second_option><option>NONE</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Wait Time Third Option: </td><td align=left><select size=1 name=wait_time_third_option id=wait_time_third_option><option>NONE</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Wait Time Option Seconds: </td><td align=left><input type=text name=wait_time_option_seconds id=wait_time_option_seconds size=5 maxlength=5></td></tr>\n";
	echo "<tr class=trview><td align=right>Wait Time Option Extension: </td><td align=left><input type=text name=wait_time_option_exten id=wait_time_option_exten size=20 maxlength=20></td></tr>\n";
	echo "<tr class=trview><td align=right>Wait Time Option Callmenu: </td><td align=left><select size=1 name=wait_time_option_callmenu id=wait_time_option_callmenu>\n";
	echo "<option value=\"\">--NONE--</option>\n";
	echo "$Xmenuslist";
	echo "</select></td></tr>\n";
	echo "<tr class=trview><td align=right>Wait Time Option Voicemail: </td><td align=left><input type=text name=wait_time_option_voicemail id=wait_time_option_voicemail size=12 maxlength=10> <a href=\"javascript:launch_vm_chooser('wait_time_option_voicemail','vm',1100,document.getElementById('wait_time_option_voicemail').value);\"><FONT color=\"blue\">[ Voicemail Chooser ]</font></a><div id=\"divwait_time_option_voicemail\"></div> </td></tr>\n";
	echo "<tr class=trview><td align=right>Wait Time Option Transfer In-Group: </td><td align=left><select size=1 name=wait_time_option_xfer_group id=wait_time_option_xfer_group>";
	echo "<option value=\"\">--NONE--</option>\n";
	echo "$Tgroups_menu";
	echo "</select></td></tr>\n";
	echo "<tr class=trview><td align=right>Wait Time Option Press Filename: </td><td align=left><input type=text name=wait_time_option_press_filename id=wait_time_option_press_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('wait_time_option_press_filename','date',1200,document.getElementById('wait_time_option_press_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a> <div id=\"divwait_time_option_press_filename\"></div> </td></tr>\n";
	echo "<tr class=trview><td align=right>Wait Time Option Press No Block: </td><td align=left><select size=1 name=wait_time_option_no_block id=wait_time_option_no_block><option>N</option><option>Y</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Wait Time Option Press Filename Seconds: </td><td align=left><input type=text name=wait_time_option_prompt_seconds id=wait_time_option_prompt_seconds size=5 maxlength=5></td></tr>\n";
	echo "<tr class=trview><td align=right>Wait Time Option After Press Filename: </td><td align=left><input type=text name=wait_time_option_callback_filename id=wait_time_option_callback_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('wait_time_option_callback_filename','date',1300,document.getElementById('wait_time_option_callback_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a> <div id=\"divwait_time_option_callback_filename\"></div></td></tr>\n";
	echo "<tr class=trview><td align=right>Wait Time Option Callback List ID: </td><td align=left><input type=text name=wait_time_option_callback_list_id id=wait_time_option_callback_list_id size=14 maxlength=14></td></tr>\n";
	echo "<tr class=trview><td align=right>Wait Hold Option Priority: </td><td align=left><select size=1 name=wait_hold_option_priority id=wait_hold_option_priority><option>WAIT</option><option>BOTH</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Estimated Hold Time Option: </td><td align=left><select size=1 name=hold_time_option id=hold_time_option><option>NONE</option><option>EXTENSION</option><option>CALL_MENU</option><option>VOICEMAIL</option><option>IN_GROUP</option><option>CALLERID_CALLBACK</option><option>DROP_ACTION</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Hold Time Second Option: </td><td align=left><select size=1 name=hold_time_second_option id=hold_time_second_option><option>NONE</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Hold Time Third Option: </td><td align=left><select size=1 name=hold_time_third_option><option>NONE</option><option>PRESS_STAY</option><option>PRESS_VMAIL</option><option>PRESS_EXTEN</option><option>PRESS_CALLMENU</option><option>PRESS_CID_CALLBACK</option><option>PRESS_INGROUP</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Hold Time Option Seconds: </td><td align=left><input type=text name=hold_time_option_seconds id=hold_time_option_seconds size=5 maxlength=5></td></tr>\n";
	echo "<tr class=trview><td align=right>Hold Time Option Minimum: </td><td align=left><input type=text name=hold_time_option_minimum id=hold_time_option_minimum size=5 maxlength=5></td></tr>\n";
	echo "<tr class=trview><td align=right>Hold Time Option Extension: </td><td align=left><input type=text name=hold_time_option_exten id=hold_time_option_exten size=20 maxlength=20></td></tr>\n";
	echo "<tr class=trview><td align=right>Hold Time Option Callmenu: </td><td align=left><select size=1 name=hold_time_option_callmenu id=hold_time_option_callmenu>\n";
	echo "<option value=\"\">--NONE--</option>\n";
	echo "$Xmenuslist";
	echo "</select></td></tr>\n";
	echo "<tr class=trview><td align=right>Hold Time Option Voicemail: </td><td align=left><input type=text name=hold_time_option_voicemail id=hold_time_option_voicemail size=12 maxlength=10> <a href=\"javascript:launch_vm_chooser('hold_time_option_voicemail','vm',1100,document.getElementById('hold_time_option_voicemail').value);\"><FONT color=\"blue\">[ Voicemail Chooser ]</font></a><div id=\"divhold_time_option_voicemail\"></div> </td></tr>\n";
	echo "<tr class=trview><td align=right>Hold Time Option Transfer In-Group: </td><td align=left><select size=1 name=hold_time_option_xfer_group id=hold_time_option_xfer_group>";
	echo "<option value=\"\">--NONE--</option>\n";
	echo "$Tgroups_menu";
	echo "</select></td></tr>\n";
	echo "<tr class=trview><td align=right>Hold Time Option Press Filename: </td><td align=left><input type=text name=hold_time_option_press_filename id=hold_time_option_press_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('hold_time_option_press_filename','date',1200,document.getElementById('hold_time_option_press_filename').value);\"><FONT color=\"blue\"><FONT color=\"blue\">[ Audio Chooser]</font></a> <div id=\"divhold_time_option_press_filename\"></div></td></tr>\n";
	echo "<tr class=trview><td align=right>Hold Time Option Press No Block: </td><td align=left><select size=1 name=hold_time_option_no_block id=hold_time_option_no_block><option>N</option><option>Y</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Hold Time Option Press Filename Seconds: </td><td align=left><input type=text name=hold_time_option_prompt_seconds id=hold_time_option_prompt_seconds size=5 maxlength=5></td></tr>\n";
	echo "<tr class=trview><td align=right>Hold Time Option After Press Filename: </td><td align=left><input type=text name=hold_time_option_callback_filename id=hold_time_option_callback_filename size=30 maxlength=255> <a href=\"javascript:launch_chooser('hold_time_option_callback_filename','date',1300,document.getElementById('hold_time_option_callback_filename').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a><div id=\"divhold_time_option_callback_filename\"></div> </td></tr>\n";
	echo "<tr class=trview><td align=right>Hold Time Option Callback List ID: </td><td align=left><input type=text name=hold_time_option_callback_list_id id=hold_time_option_callback_list_id size=14 maxlength=14></td></tr>\n";
	echo "<tr class=trview><td align=right>Agent Alert Filename: </td><td align=left><input type=text name=agent_alert_exten id=agent_alert_exten size=30 maxlength=100> <a href=\"javascript:launch_chooser('agent_alert_exten','date',1500,document.getElementById('agent_alert_exten').value);\"><FONT color=\"blue\">[ Audio Chooser ]</font></a> <div id=\"divagent_alert_exten\"></div></td></tr>\n";
	echo "<tr class=trview><td align=right>Agent Alert Delay: </td><td align=left><input type=text name=agent_alert_delay id=agent_alert_delay size=6 maxlength=6></td></tr>\n";
	echo "<tr class=trview><td align=right>Default Transfer Group: </td><td align=left><select size=1 name=default_xfer_group id=default_xfer_group>";
	echo "<option value=\"\">--NONE--</option>\n";
	echo "<option value=\"AGENTDIRECT\">AGENTDIRECT - Single Agent Direct Queue</option>";
	echo "$Xgroups_menu";
	echo "</select></td></tr>\n";
	
	echo "<tr><td align=right>Default Group Alias: </td><td align=left><select size=1 name=default_group_alias id=default_group_alias>";
	## group alias pulldown
	foreach($groupaliaspulldown as $groupaliaspulldownInfo) {
		$group_alias_id = $groupaliaspulldownInfo->group_alias_id;
		$group_alias_name = $groupaliaspulldownInfo->group_alias_name;

		$group_alias_menu .= "value=\"$group_alias_id\">$group_alias_id - $group_alias_name</option>\n";
	}

	echo "<option value=\"\">--NONE--</option>";
	echo "$group_alias_menu";
	echo "</select></td></tr>\n";
	echo "<tr class=trview><td align=right>Hold Recall Transfer In-Group: </td><td align=left><select size=1 name=hold_recall_xfer_group id=hold_recall_xfer_group>";
	echo "<option value=\"\">--NONE--</option>\n";
	echo "$Hgroups_menu";
	echo "</select></td></tr>\n";
	echo "<tr class=trview><td align=right>No Delay Call Route: </td><td align=left><select size=1 name=no_delay_call_route id=no_delay_call_route><option>Y</option><option>N</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>In-Group Recording Override: </td><td align=left><select size=1 name=ingroup_recording_override id=ingroup_recording_override><option>DISABLED</option><option>NEVER</option><option>ONDEMAND</option><option>ALLCALLS</option><option>ALLFORCE</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>In-Group Recording Filename: </td><td align=left><input type=text name=ingroup_rec_filename id=ingroup_rec_filename size=50 maxlength=50></td></tr>\n";
	echo "<tr class=trview><td align=right>Stats Percent of Calls Answered Within X seconds 1: </td><td align=left><input type=text name=answer_sec_pct_rt_stat_one id=answer_sec_pct_rt_stat_one size=5 maxlength=5 ></td></tr>\n";
	echo "<tr class=trview><td align=right>Stats Percent of Calls Answered Within X seconds 2: </td><td align=left><input type=text name=answer_sec_pct_rt_stat_two id=answer_sec_pct_rt_stat_two size=5 maxlength=5></td></tr>\n";
	echo "<tr class=trview><td align=right>Start Call URL: </td><td align=left><input type=text name=start_call_url id=start_call_url size=70 maxlength=2000 ></td></tr>\n";
	echo "<tr class=trview><td align=right>Dispo Call URL: </td><td align=left><input type=text name=dispo_call_url id=dispo_call_url size=70 maxlength=2000 ></td></tr>\n";
	echo "<tr class=trview><td align=right>Add Lead URL: </td><td align=left><input type=text name=add_lead_url id=add_lead_url size=70 maxlength=2000></td></tr>\n";
	echo "<tr class=trview><td align=right>Extension Append CID: </td><td align=left><select size=1 name=extension_appended_cidname id=extension_appended_cidname><option>Y</option><option>N</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Uniqueid Status Display: </td><td align=left><select size=1 name=uniqueid_status_display id=uniqueid_status_display><option>DISABLED</option><option>ENABLED</option><option>ENABLED_PREFIX</option><option>ENABLED_PRESERVE</option></select></td></tr>\n";
	echo "<tr class=trview><td align=right>Uniqueid Status Prefix: </td><td align=left><input type=text name=uniqueid_status_prefix id=uniqueid_status_prefix size=10 maxlength=50></td></tr>\n";
?>
</table>
<TABLE width="95%" class="tableeditingroup">
<tr>					
  <td colspan="2" align="right">
 <!-- <input type="button" name="editSUBMIT" value="MODIFY" onclick="editpost(document.getElementById('showvaledit').value);">-->

 <div style="border-top: 2px solid #DFDFDF;height:20px;vertical-align:middle; padding-top: 7px;" align="right">

  <a id="searchcallhistory" style="cursor: pointer;" onclick="editpost(document.getElementById('showvaledit').value);"><font color="#7A9E22"><? echo $this->lang->line("go_save_settings"); ?></font></a>
  </div>	
  </td>
</tr>
</table>
</form>		
</div>
</center>
</div>	
<!-- end edit -->

<!-- Debug-->
<div id="showDebug"></div>

<div id="hiddenToggle" style="visibility:hidden"></div>
<div id="wizardSpan" style="visibility:hidden">false</div>
<div class="clear"></div></div><!-- wpbody-content -->
<div class="clear"></div></div><!-- wpbody -->
<div class="clear"></div></div><!-- wpcontent -->
</div><!-- wpwrap -->
