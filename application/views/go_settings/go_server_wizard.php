<?php
############################################################################################
####  Name:             go_carrier_wizard.php                                           ####
####  Type: 		    ci views                                                        ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Christopher Lomuntad <chris@goautodial.com>   ####
####  License:          AGPLv2                                                          ####
############################################################################################
############################################################################################
#### WARNING/NOTICE: PRODUCTION                                                         ####
#### Current SVN Production                                                             ####
############################################################################################
$base = base_url();
$NOW = date('Y-m-d');
?>
<style type="text/css">
#serverTable input,
#serverTable select,
#serverTable textarea {
	/* border: 1px solid #999; */
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
.modify-value {
    font-weight: bold;
    color: #7f7f7f;
} 
</style>

<script>
$(function()
{
	$('#server_id').keyup(function(e)
	{
		if ($(this).val().length > 2)
		{
			$('#server_description').css('border','solid 1px #999');
			$('#server_ip').css('border','solid 1px #999');
		
			$('#aloading').empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
			$('#aloading').load('<? echo $base; ?>index.php/go_servers_ce/go_check_server/'+$(this).val()+'/'+$('#server_ip').val());
		} else {
			$('#aloading').html("<small style=\"color:red;\"><? echo $this->lang->line("go_min_3_char"); ?></small>");
		}
	});
	
	$('#server_id,#server_description,#server_ip').keydown(function(event)
	{
		//console.log($(this).attr('id'));
		if ((event.keyCode == 32 && $(this).attr('id') != 'server_description') || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
			|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || (event.keyCode == 190 && ($(this).attr('id') != 'server_ip' || event.shiftKey))
			|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
			return false;
		
		if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
			return false;
		
		if (!event.shiftKey && event.keyCode == 173)
			return false;
		
		$(this).css('border','solid 1px #999');
	});
	
	$('#submit').click(function()
	{
		var isEmpty = 0;
		if ($('#server_id').val() === "" || $('#server_id').val().length < 3)
		{
			$('#server_id').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#server_description').val() === "")
		{
			$('#server_description').css('border','solid 1px red');
			isEmpty = 1;
		}
		if ($('#server_ip').val() === "" || $('#server_ip').val().length < 7)
		{
			$('#server_ip').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#aloading').html().match(/Not Available/))
		{
			alert("<? echo $this->lang->line("go_server_id_navailable"); ?>.");
			isEmpty = 1;
		}
		
		if (!isEmpty)
		{
			var items = $('#serverForm').serialize();
			$.post("<?=$base?>index.php/go_servers_ce/go_server_wizard", { items: items, action: "add_new_server" },
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
	
	$.validator.addMethod('IP4Checker', function(value) {
	var ip = "^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$";
		return value.match(ip);
	}, ' <small style="color:red;"><? echo $this->lang->line("go_invalid_ip_add"); ?></small>');

	$('#serverForm').validate({
		rules: {
			server_ip: {
				IP4Checker: true
			}
		}
	});
});
</script>

<div style="float:right;" id="small_step_number"><img src="<?php echo $base; ?>img/step1-nav-small.png" /></div>
<div style="font-weight:bold;font-size:16px;color:#333;"><? echo $this->lang->line("go_server_wizard"); ?> &raquo; <? echo $this->lang->line("go_add_new_server"); ?></div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />

<table style="width:100%;">
	<tr>
		<td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step1-trans.png" /></div>
		</td>
		<td valign="top">
            <span id="wizardContent" style="height:100px; padding-top:10px;">
				<form id="serverForm" method="POST">
                <table id="serverTable" style="width:100%;">
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        <label class="modify-value"><? echo $this->lang->line("go_server_id"); ?>:</label>
                        </td>
                        <td>
                        <?=form_input('server_id',null,'id="server_id" maxlength="10" size="10"') ?>
						<span id="aloading"></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        <label class="modify-value"><? echo $this->lang->line("go_server_desc"); ?>:</label>
                        </td>
                        <td>
                        <?=form_input('server_description',null,'id="server_description" maxlength="255" size="30"') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value"><? echo $this->lang->line("go_server_ip"); ?>:</label>
                        </td>
                        <td>
                        <?=form_input('server_ip',null,'id="server_ip" maxlength="15" size="20"') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value"><? echo $this->lang->line("go_active"); ?>:</label>
                        </td>
                        <td>
                        <?=form_dropdown('active',array('Y'=>'Y','N'=>'N'),null) ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value"><? echo $this->lang->line("go_asterisk_version"); ?>:</label>
                        </td>
                        <td>
                        <?=form_input('asterisk_version',null,'id="asterisk_version" maxlength="20" size="20"') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value"><? echo $this->lang->line("go_user_group"); ?>:</label>
                        </td>
                        <td>
                        <?php
						$groupArray = array("---{$this->lang->line('go_all')}---"=> strtoupper($this->lang->line("go_all_user_groups")));
						foreach ($user_groups as $group)
						{
							$groupArray[$group->user_group] = "{$group->user_group} - {$group->group_name}";
						}
						echo form_dropdown('user_group',$groupArray,null,'id="user_group"');
						?>
                        </td>
                    </tr>
                </table>
				</form>
            </span>
		</td>
	</tr>
</table>
<hr style="border:#DFDFDF 1px solid;" />
<span id="saveButtons"><span id="submit" style="white-space: nowrap;"><? echo $this->lang->line("go_submit"); ?></span></span>
