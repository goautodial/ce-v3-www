<?php
####################################################################################################
####  Name:             	go_voicemail_wizard.php                                             ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Originated by:        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Christopher Lomuntad                                         	    ####
####  License:          	AGPLv2                                                              ####
####################################################################################################
$base = base_url();
$NOW = date('Y-m-d');
?>
<style type="text/css">
#voicemailTable input,
#voicemailTable select,
#voicemailTable textarea {
/*	border: 1px solid #999; */
}

#campTable td{
	padding:0px 5px 0px 5px;
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

#saveButtons span:hover{
	font-weight:bold;
}

::-webkit-input-placeholder { /* WebKit browsers */
    color:    #999;
}
:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
    color:    #999;
}
::-moz-placeholder { /* Mozilla Firefox 19+ */
    color:    #999;
}
:-ms-input-placeholder { /* Internet Explorer 10+ */
    color:    #999;
}
</style>

<script>
$(function()
{
	$('#voicemail_id').keyup(function(e)
	{
		if ($(this).val().length > 1)
		{
			$('#fullname').css('border','solid 1px #999');
			$('#voicemail_id').css('border','solid 1px #999');
		
			if(e.which === 32) 
				return false;
			
			//$('#aloading').empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
			$('#aloading').load('<? echo $base; ?>index.php/go_voicemail_ce/go_check_voicemail/'+$(this).val());
		} else {
			$('#aloading').html("<small style=\"color:red;\"><? echo $this->lang->line("go_min_2_numbers"); ?>.</small>");
		}
	});
	
	$('#voicemail_id,#fullname,#pass').keydown(function(event)
	{
		//console.log(event.keyCode);
		if (event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
			|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || event.keyCode == 190
			|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
			return false;
		
		if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
			return false;
		
		$(this).css('border','solid 1px #999');
		
		if ($(this).attr('id')=='pass')
		{
			if (event.keyCode == 32 || event.keyCode == 173)
				return false;
		}
		if ($(this).attr('id')=='voicemail_id')
		{
			if (event.keyCode == 32)
				return false;
			
			if (!event.shiftKey && event.keyCode == 173)
				return false;
			
			if (event.keyCode > 64 && event.keyCode < 91)
				return false;
		}
	});
	
	$('#submit').click(function()
	{
		var isEmpty = 0;
		if ($('#voicemail_id').val() === "" || $('#voicemail_id').val().length < 3)
		{
			$('#voicemail_id').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#fullname').val() === "")
		{
			$('#fullname').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#pass').val() === "")
		{
			$('#pass').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#aloading').html().match(/Not Available/))
		{
			alert("<? echo $this->lang->line("go_vm_box_navailable"); ?>.");
			isEmpty = 1;
		}
		
		if (!isEmpty)
		{
			var items = $('#voicemailForm').serialize();
			$.post("<?=$base?>index.php/go_voicemail_ce/go_voicemail_wizard", { items: items, action: "add_new_voicemail" },
			function(data){
				if (data=="SUCCESS")
				{
					alert("<? echo $this->lang->line("go_success_caps"); ?>");
				
					$('#box').animate({'top':'-2550px'},500);
					$('#overlay').fadeOut('slow');
					
					location.reload();
				}
	
			});
		}
	});
});
</script>

<div style="float:right;" id="small_step_number"><img src="<?php echo $base; ?>img/step1-nav-small.png" /></div>
<div style="font-weight:bold;font-size:16px;color:#333;"><? echo $this->lang->line("go_voicemail_wizard"); ?> &raquo; <? echo $this->lang->line("go_add_new_voicemail"); ?></div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />

<table style="width:100%;">
	<tr>
		<td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step1-trans.png" /></div>
		</td>
		<td valign="top">
            <span id="wizardContent" style="height:100px; padding-top:10px;">
				<form id="voicemailForm" method="POST">
                <table id="voicemailTable" style="width:100%;">
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        <? echo $this->lang->line("go_voicemail_id"); ?>:
                        </td>
                        <td>
                        <?=form_input('voicemail_id',null,'id="voicemail_id" maxlength="10" size="15"') ?>
                                                <span id="aloading"></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        <? echo $this->lang->line("go_pass"); ?>:
                        </td>
                        <td>
                        <?=form_input('pass',null,'id="pass" maxlength="10" size="12"') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                       <? echo $this->lang->line("go_name"); ?>:
                        </td>
                        <td>
                        <?=form_input('fullname',null,'id="fullname" maxlength="100" size="40"') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                       <? echo $this->lang->line("go_active"); ?>:
                        </td>
                        <td>
                        <?=form_dropdown('active',array('N'=>$this->lang->line("go_active_no"),'Y'=>$this->lang->line("go_active_yes")),null,'id="active"') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                       <? echo $this->lang->line("go_email"); ?>:
                        </td>
                        <td>
                        <?=form_input('email',null,'id="email" maxlength="100" size="40"') ?>
                        </td>
                    </tr>
                </table>
				</form>
            </span>
		</td>
	</tr>
</table>
<hr style="border:#DFDFDF 1px solid;" />
<span id="saveButtons"><span id="submit" style="white-space: nowrap;"><? echo $this->lang->line("go_submit"); ?> </span></span>
