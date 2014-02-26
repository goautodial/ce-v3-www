<?php
########################################################################################################
####  Name:             	go_moh_wizard.php                                                   ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                                 ####
####  Build:            	1375243200                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####      	                <community@goautodial.com>                                          ####
####  Written by:       	Christopher Lomuntad                                                ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$base = base_url();
$hideFromTenant = ($this->commonhelper->checkIfTenant($my_user_group)) ? "display:none;" : "";
?>
<script>
$(function()
{
	$('.toolTip').tipTip();
	
	// Submit Settings
	$('#submit').click(function()
	{
		var mohid = $("#moh_id").val();
		var isEmpty = 0;
		if (mohid.length < 3)
		{
			alert("MoH ID should not be empty or should\nbe at least 3 characters in length.");
			isEmpty = 1;
		}
		
		if ($('#mloading').html().match(/Not Available/))
		{
			alert("MoH ID Not Available.");
			isEmpty = 1;
		}
		
		if (!isEmpty) {
			var items = $("#modifyMoH").serialize();
			$.post("<?=$base?>index.php/go_moh_ce/go_moh_wizard", { items: items, action: "add" },
			function(data){
				if (data=="SUCCESS")
				{
					alert(data);
				
					$('#box').animate({'top':'-2550px'},500);
					$('#overlay').fadeOut('slow');
					
					location.reload();
				}
	
			});
		}
	});
	
	// Pagination
	$('#fileList').tablePagination({rowsPerPage: 10, optionsForRows: [10, 25, 50, 100, "ALL"]});

	// Table Sorter
	$("#fileList").tablesorter({sortList:[[0,0]], widgets: ['zebra']});

	// For Campaigns
	$('.selectAudio').click(function()
	{
		$('#fileOverlay').fadeIn('fast');
		$('#fileBox').css({'width': '300px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '20px'});
		$('#fileBox').animate({
			top: "60px"
		}, 500);

		$('#audioButtonClicked').text($(this).attr('id'));
	});

	$('#fileClosebox').click(function()
	{
		$('#fileBox').animate({'top':'-2550px'},500);
		$('#fileOverlay').fadeOut('slow');
	});
	
	$('#moh_id').keyup(function(event)
	{
		
		if (event.keyCode != 17)
		{
			if ($(this).val().length > 2)
			{
				$(this).css('border','solid 1px #DFDFDF');
				$('#mloading').load('<? echo $base; ?>index.php/go_moh_ce/go_check_moh/'+$(this).val());
			} else {
				$('#mloading').html("<small style=\"color:red;\">Minimum of 3 characters.</small>");
			}
		}
	});
	
	$('#moh_id,#moh_name').bind("keydown keypress", function(event)
	{
		//console.log(event.type + " -- " + event.altKey + " -- " + event.which);
		if (event.type == "keydown") {
			// For normal key press
			if ((event.keyCode == 32 && $(this).attr('id')!="moh_name") || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
				|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
				|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
				return false;
			
			if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
				return false;
			
			if (!event.shiftKey && event.keyCode == 173)
				return false;
		} else {
			// For ASCII Key Codes
			if ((event.which > 32 && event.which < 48) || (event.which == 32 && $(this).attr('id')!="moh_name") || (event.which > 57 && event.which < 65)
				|| (event.which > 90 && event.which < 94) || (event.which == 96) || (event.which > 122))
				return false;
		}
		
		$(this).css('border','solid 1px #DFDFDF');
	});
});
</script>
<style>
.buttons,.otherLinks {
	color:#7A9E22;
	cursor:pointer;
}

.buttons:hover{
	font-weight:bold;
}

#fileList th {
	text-align: left;
}

#saveButtons{
	float:right;
	width:150px;
	text-align:right;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

#saveButtons span{
	text-align:center;
	color:#7A9E22;
	cursor:pointer;
	width:40px;
}
</style>

<div style="float:right;" id="small_step_number"><img src="<?php echo $base; ?>img/step1-nav-small.png" /></div>
<div style="font-weight:bold;font-size:16px;color:#333;">Music on Hold Wizard &raquo; Add New Music on Hold</div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />

<table style="width:100%;">
	<tr>
		<td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step1-trans.png" /></div>
		</td>
		<td valign="top">
			<form id="modifyMoH" method="POST">
				<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:90%; color:#000; margin-left:auto; margin-right:auto;">
					<tr>
					<td style="text-align:right;" nowrap>Music on Hold ID:</td><td><?=form_input('moh_id',null,'id="moh_id" size="25" maxlength="100"') ?> <span id="mloading"></span></td>
				    </tr>
					<tr>
					<td style="text-align:right;" nowrap>Music on Hold Name:</td><td><?=form_input('moh_name',null,'id="moh_name" size="50" maxlength="100"') ?></td>
				    </tr>
					<tr>
					<td style="text-align:right;" nowrap>Status:</td><td><?=form_dropdown('active',array('N'=>'INACTIVE','Y'=>'ACTIVE'),null) ?></td>
				    </tr>
					<tr style="<?php echo $hideFromTenant; ?>">
					<td style="text-align:right;" nowrap>User Group:</td><td><?=form_dropdown('user_group',$user_group_array,$my_user_group,'style="width:300px;"') ?></td>
				    </tr>
					<tr>
					<td style="text-align:right;" nowrap>Random Order:</td><td><?=form_dropdown('random',array('N'=>'No','Y'=>'Yes'),null) ?></td>
				    </tr>
				</table>
			</form>
		</td>
	</tr>
</table>
<hr style="border:#DFDFDF 1px solid;" />
<span id="saveButtons"><span id="submit" style="white-space: nowrap;">Submit</span></span>
