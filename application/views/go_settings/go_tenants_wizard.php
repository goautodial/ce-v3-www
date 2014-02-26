<?php
####################################################################################################
####  Name:             	go_tenants_wizard.php                      	                        ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Written by:      		Christopher Lomuntad                                         	    ####
####  License:          	AGPLv2                                                              ####
####################################################################################################
$base = base_url();
$NOW = date('Y-m-d');
?>
<style type="text/css">
#tenantTable input,
#tenantTable select,
#tenantTable textarea {
	 border: 1px solid #DFDFDF; 
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

#tenant_pass_span,#tenant_agent_pass_span{
    /*margin-left:5px;*/
	font-size:10px;
}

.short{
    color:#FF0000;
}
 
.weak{
    color:#E66C2C;
}
 
.good{
    color:#2D98F3;
}
 
.strong{
    color:#006400;
}
</style>

<script>
$(function()
{
	$('#tenant_admin').keyup(function(e)
	{
		if ($(this).val().length > 2)
		{
			//$('#aloading').empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
			$('#aloading').load('<? echo $base; ?>index.php/go_tenants_ce/go_check_tenant/'+$(this).val()+'/');
		} else {
			$('#aloading').html("<small style=\"color:red;\">Minimum of 3 characters.</small>");
		}
	});
	
	$('#tenant_name,#tenant_admin,#tenant_pass').keydown(function(event)
	{
		//console.log(event.keyCode);
		if ((event.keyCode == 32 && $(this).attr('id') != 'tenant_name') || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
			|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || (event.keyCode == 190 && ($(this).attr('id') != 'tenant_name' || event.shiftKey))
			|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
			return false;
		
		if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
			return false;
		
		if ((!event.shiftKey && event.keyCode == 173 && $(this).attr('id') != 'tenant_name'))
			return false;
		
		$(this).css('border','solid 1px #DFDFDF');
	});
	
	$('#submit').click(function()
	{
		var isEmpty = 0;
		var hasError = '';
		if ($('#tenant_id').val() === "" || $('#tenant_id').val().length < 3)
		{
			hasError = "Tenant ID too short.\n";
			$('#tenant_id').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#tenant_name').val() === "")
		{
			hasError += "Tenant name should not be empty.\n";
			$('#tenant_name').css('border','solid 1px red');
			isEmpty = 1;
		}
		if ($('#tenant_admin').val() === "" || $('#tenant_admin').val().length < 3)
		{
			hasError += "Admin login too short.\n";
			$('#tenant_admin').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#aloading').html().match(/Not Available/))
		{
			hasError += "Admin login not available.\n";
			isEmpty = 1;
		}
		
		if ($('#tenant_pass_span').html().match(/Too short/) || $('#tenant_pass_span').html().length < 1)
		{
			hasError += "Admin password too short.";
			$('#tenant_pass').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if (!isEmpty)
		{
			var items = $('#tenantForm').serialize();
			$.post("<?=$base?>index.php/go_tenants_ce/go_tenants_wizard", { items: items, action: "add_new_tenant" },
			function(data){
				if (data=="SUCCESS")
				{
					alert(data);
				
					$('#box').animate({'top':'-2550px'},500);
					$('#overlay').fadeOut('slow');
					
					location.reload();
				}
	
			});
		} else {
			alert("ERROR:\n\n"+hasError);
		}
	});
});

$(document).ready(function()
{
	$('#tenant_pass,#tenant_agent_pass').keyup(function(){
		var id = '#'+$(this).attr('id')+'_span';
        $(id).html(checkStrength(id,$(this).val()));
    });
});

function checkStrength(id,password)
{
	var strength = 0;
	if (password.length == 0) return '';
	if (password.length < 6)
	{
		$(id).removeClass();
		$(id).addClass('short');
		return 'Too short';
	}
	
	if (password.length > 7) strength += 1;
	if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1;
	if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1;
	if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1;
	if (password.length > 15) strength += 1;
	
	if (strength < 2 ) {
		$(id).removeClass();
		$(id).addClass('weak');
		return 'Weak';
	} else if (strength == 2 ) {
		$(id).removeClass();
		$(id).addClass('good');
		return 'Good';
	} else {
		$(id).removeClass();
		$(id).addClass('strong');
		return 'Strong';
	}
}
</script>

<div style="float:right;" id="small_step_number"><img src="<?php echo $base; ?>img/step1-nav-small.png" /></div>
<div style="font-weight:bold;font-size:16px;color:#333;">Multi-Tenant Wizard &raquo; Add New Tenant</div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />

<table style="width:100%;">
	<tr>
		<td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step1-trans.png" /></div>
		</td>
		<td valign="top">
            <span id="wizardContent" style="height:100px; padding-top:10px;">
				<form id="tenantForm" method="POST">
                <table id="tenantTable" style="width:100%;">
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        <label class="modify-value">Tenant ID:</label>
                        </td>
                        <td>
                        <?=form_input('tenant_id',$tenant_id,'id="tenant_id" maxlength="20" size="10" readonly="readonly"') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        <label class="modify-value">Tenant Name:</label>
                        </td>
                        <td>
                        <?=form_input('tenant_name',null,'id="tenant_name" maxlength="30" size="40"') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Admin Login:</label>
                        </td>
                        <td>
                        <?=form_input('tenant_admin',null,'id="tenant_admin" maxlength="20" size="20"') ?>
						<span id="aloading"></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Admin Password:</label>
                        </td>
                        <td>
                        <?=form_input('tenant_pass',null,'id="tenant_pass" maxlength="20" size="20"') ?>
                        <span id="tenant_pass_span"></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Group Template:</label>
                        </td>
                        <td>
                        <?=form_dropdown('group_template',$group_template,'TENANT') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Active:</label>
                        </td>
                        <td>
                        <?=form_dropdown('active',array('Y'=>'Yes','N'=>'No'),null) ?>
                        </td>
                    </tr>
                    <tr style="display:none;">
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Can Create Call Times:</label>
                        </td>
                        <td>
                        <?=form_dropdown('access_call_times',array('Y'=>'Yes','N'=>'No'),null) ?>
                        </td>
                    </tr>
                    <tr style="display:none;">
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Can Create Carriers:</label>
                        </td>
                        <td>
                        <?=form_dropdown('access_carriers',array('Y'=>'Yes','N'=>'No'),null) ?>
                        </td>
                    </tr>
                    <tr style="display:none;">
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Can Create Phones:</label>
                        </td>
                        <td>
                        <?=form_dropdown('access_phones',array('Y'=>'Yes','N'=>'No'),null) ?>
                        </td>
                    </tr>
                    <tr style="display:none;">
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Can Create Voicemails:</label>
                        </td>
                        <td>
                        <?=form_dropdown('access_voicemails',array('Y'=>'Yes','N'=>'No'),null) ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Agent/Phone Count:</label>
                        </td>
                        <td>
						<?php
						for ($i=1;$i<=20;$i++)
						{
							$agentCnt[$i] = $i;
						}
						echo form_dropdown('agent_count',$agentCnt,null);
						?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;white-space:nowrap;">
                        <label class="modify-value">Agent/Phone Default Password:</label>
                        </td>
                        <td>
                        <?=form_input('tenant_agent_pass','goautodial','id="tenant_agent_pass" maxlength="10" size="15"') ?>
                        <span id="tenant_agent_pass_span"></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Protocol:</label>
                        </td>
                        <td>
						<?php
						$protocolArray = array('EXTERNAL'=>'EXTERNAL','SIP'=>'SIP','IAX2'=>'IAX2');
						echo form_dropdown('protocol',$protocolArray,null,'id="protocol"');
						?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Server IP:</label>
                        </td>
                        <td>
						<?php
						foreach ($server_list as $server)
						{
							$servers[$server->server_ip] = "{$server->server_ip} - {$server->server_description}";
						}
						echo form_dropdown('server_ip',$servers,$_SERVER['SERVER_ADDR'],'id="server_ip"');
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
<span id="saveButtons"><span id="submit" style="white-space: nowrap;">Submit</span></span>
