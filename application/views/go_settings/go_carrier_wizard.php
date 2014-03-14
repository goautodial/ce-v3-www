<?php
########################################################################################################
####  Name:             	go_phone_wizard.php                                                 ####
####  Type:             	ci views for phones - administrator                                 ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Christopher Lomuntad					            ####
####  Modified by:       	Franco Hora					            	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

$base = base_url();
$NOW = date('Y-m-d');
$regstrChecked = FALSE;
$ipbasedChecked = TRUE;

$accountEntry = "[]\ndisallow=all\nallow=gsm\nallow=ulaw\ntype=friend\ndtmfmode=rfc2833\ncontext=trunkinbound\nqualify=yes\ninsecure=very\nnat=yes\nhost=";

$allowGSM = FALSE;
$allowULAW = FALSE;
$allowALAW = FALSE;
$allowG729 = FALSE;
$rfc2833 = FALSE;
$inband = FALSE;
$customDTMF = FALSE;
foreach(explode("\n",$accountEntry) as $line)
{
	list($var,$val) = explode("=",$line);
	
	if ($var=="allow")
	{
		if ($val=="gsm")
			$allowGSM = TRUE;
		if ($val=="ulaw")
			$allowULAW = TRUE;
		if ($val=="alaw")
			$allowALAW = TRUE;
		if ($val=="g729")
			$allowG729 = TRUE;
	}
	if ($var=="dtmfmode")
	{
		switch($val)
		{
			case "rfc2833":
				$rfc2833 = TRUE;
				break;
			case "inband":
				$inband = TRUE;
				break;
			default:
				$customDTMF = TRUE;
				$customDTMFVal = $val;
				break;
		}
	}
	if ($var=="username")
		$username = $val;
	if ($var=="secret")
		$secret = $val;
	if ($var=="host")
		$host = $val;
}

for ($i=0;$i<10;$i++)
{
	$randPrefix .= rand(0,9);
}
?>
<style type="text/css">
#server_ip{display:none}
#carrierTable input,
#carrierTable select,
#carrierTable textarea {
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

.processing{z-index:500;position:fixed;text-align:center;top:0;bottom:0;left:0;right:0;}
.processing img{margin-top:150px;opacity:0.8;}

#saveButtons span:hover{
	font-weight:bold;
}

::-webkit-input-placeholder { /* WebKit browsers */
    color:     #777;
	font-size: 12px;
}
:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
    color:     #777;
	font-size: 12px;
}
::-moz-placeholder { /* Mozilla Firefox 19+ */
    color:     #777;
	font-size: 12px;
}
:-ms-input-placeholder { /* Internet Explorer 10+ */
    color:     #777;
	font-size: 12px;
}
</style>

<script>
$(function()
{
	$('#carrier_id').keyup(function(e)
	{
		if ($(this).val().length > 2)
		{
			$('#carrier_name').css('border','solid 1px #DFDFDF');
		
			//$('#aloading').empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
			$('#aloading').load('<? echo $base; ?>index.php/go_carriers_ce/go_check_carrier/'+$(this).val());
		} else {
			$('#aloading').html("<small style=\"color:red;\">Minimum of 3 characters.</small>");
		}
	});
	
	$('#carrier_id,#carrier_name,#registration_string,#username,#secret,#host,#port,#iphost').keydown(function(event)
	{
		$(this).css('border','solid 1px #DFDFDF');
	});

    $('#carrier_id,#carrier_name,#carrier_description,#username,#secret,#host,#port,#iphost,#customDTMF').keydown(function(event)
	{
		//console.log(event.keyCode);
		if ((event.keyCode == 32 && ($(this).attr('id') != 'carrier_name' && $(this).attr('id') != 'carrier_description')) || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
			|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || (event.keyCode == 190 && (($(this).attr('id') != 'carrier_name' && $(this).attr('id') != 'carrier_description'
			&& $(this).attr('id') != 'host' && $(this).attr('id') != 'iphost' && $(this).attr('id') != 'secret' && $(this).attr('id') != 'ipsecret') || event.shiftKey))
			|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
			return false;
		
		if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
			return false;
		
		if (!event.shiftKey && event.keyCode == 173 && ($(this).attr('id') != 'host' && $(this).attr('id') != 'iphost'))
			return false;
		
		$(this).css('border','solid 1px #DFDFDF');
    });
	
	$("#port").keydown(function(event) {
        // Allow: backspace, delete, tab, escape, and enter
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }
    });
	
	$('#registration_string,#account_entry,#globals_string,#dialplan_entry').keydown(function(event)
	{
		if (event.keyCode == 222)
			return false;
	});

	
	$('#submit').click(function()
	{
		var isEmpty = 0;
		if ($('#carrier_id').val() === "" || $('#carrier_id').val().length < 3)
		{
			$('#carrier_id').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($('#carrier_name').val() === "")
		{
			$('#carrier_name').css('border','solid 1px red');
			isEmpty = 1;
		}
		
		if ($("#customProtocol").val()!=='---CUSTOM---')
		{
			if ($('.ip_based').is(":hidden"))
			{
				if ($('#username').val() === "")
				{
					$('#username').css('border','solid 1px red');
					isEmpty = 1;
				}
				
				if ($('#secret').val() === "")
				{
					$('#secret').css('border','solid 1px red');
					isEmpty = 1;
				}
				
				if ($('#host').val() === "")
				{
					$('#host').css('border','solid 1px red');
					isEmpty = 1;
				}
			
				if ($('#port').val() === "")
				{
					$('#port').css('border','solid 1px red');
					isEmpty = 1;
				}
			} else {
				if ($('#ipusername').val() === "")
				{
					$('#ipusername').css('border','solid 1px red');
					isEmpty = 1;
				}
				
				if ($('#ipsecret').val() === "")
				{
					$('#ipsecret').css('border','solid 1px red');
					isEmpty = 1;
				}
				
				if ($('#iphost').val() === "")
				{
					$('#iphost').css('border','solid 1px red');
					isEmpty = 1;
				}
			}
		}
		
		if ($('#aloading').html().match(/Not Available/))
		{
			alert("Carrier ID Not Available.");
			isEmpty = 1;
		}

		//if(/(208.43.27.84)|(dal.justgovoip.com)/g.test($("#registration_string").val())){
		//	$('#registration_string').css('border','solid 1px red');
		//	isEmpty = 1;
		//}
		if (!isEmpty)
		{
                        $("#box").append("<div class='processing'><img src='<?=$base?>img/goloading.gif' ></div>");
			var items = $('#carrierForm').serialize();

                        //if(!$("#servers_checkbox").prop("checked")){
                        //     items = items + "&server_ip=" + $("#server_ip").val();
                        //}

			$.post("<?=$base?>index.php/go_carriers_ce/go_carrier_wizard", { items: items, action: "add_new_carrier" },
			function(data){
				if (data=="SUCCESS")
				{
                                        $(".processing").remove();
					alert(data);
				
					$('#box').animate({'top':'-2550px'},500);
					$('#overlay').fadeOut('slow');
					
					location.reload();
				}
	
				if (data=="FAILED")
				{
					alert("A JustGoVoIP account already exist.");
					$('#registration_string').css('border','solid 1px red');
				}
			});
		}
	});
       $('#cancel').click(function(){
    	   $("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	   $('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_carriers_ce/go_carrier_type/').fadeIn("slow");
       });
	
	$("#username,#secret,#host,#port").keyup(function()
	{
		var regUser = $("#username").val();
		var regPass = $("#secret").val();
		var regHost = $("#host").val();
		var regPort = $("#port").val();
		
		$("#registration_string").val("register => "+regUser+":"+regPass+"@"+regHost+":"+regPort+"/"+regUser);
		
		if ($(this).attr('id')=="host")
			$("#iphost").val($(this).val());
		
		if ($(this).attr('id')=="username" || $(this).attr('id')=="secret")
			changeValue($(this).attr('id'),$(this).val(),"show");
		else
			changeValue($(this).attr('id'),$(this).val());
	});
	
	$("#iphost").keyup(function()
	{
		var cVars = $(this).attr('id');
		$("#host").val($(this).val());
		changeValue("host",$(this).val());
	});
	
	$("#carrier_id").keyup(function()
	{
		var dialEntry = [];
		var prefix = $("#dialprefix").val();
		
		if (prefix.length > 0 && $("#customProtocol").val() != "---CUSTOM---")
		{
			dialEntry[0] = "exten => _"+prefix+".,1,AGI(agi://127.0.0.1:4577/call_log)";
			dialEntry[1] = "exten => _"+prefix+".,2,Dial(SIP/${EXTEN:"+prefix.length+"}@"+$("#carrier_id").val()+",,tTo)";
			dialEntry[2] = "exten => _"+prefix+".,3,Hangup";
			
			$("#dialplan_entry").val(dialEntry.join("\n"));
			changeValue("header",$(this).val());
		}
	});
	
	$("#allow_gsm,#allow_ulaw,#allow_alaw,#allow_g729").click(function()
	{
		var cVars = $(this).val();
		cVars = cVars.split("_");
		if ($(this).is(":checked"))
		{
			changeValue(cVars[0],cVars[1],"show");
		} else {
			changeValue(cVars[0],cVars[1],"hide");
		}
	});
	
	$("#customDTMF").keyup(function()
	{
		changeValue("dtmfmode",$(this).val());
	});
	
	$("#allow_custom").click(function()
	{
		if ($(this).is(":checked"))
		{
			$("#customCodecs").show();
		} else {
			$("#customCodecs").hide();
		}
	});
	
	$("#customCodecs").focusout(function()
	{
		if ($(this).is(":visible"))
		{
			changeValue("allow",$(this).val(),"show");
		} else {
			changeValue("allow",$(this).val(),"hide");
		}
	});
	
	$('#customProtocol').change(function()
	{
		$("#protocol").val($(this).val());
		if ($(this).val() == "---CUSTOM---")
		{
			$(".advanceConfig").show();
			$(".basicConfig").hide();
			$("#protocol").show();
			
			if ($("input[name='reg_auth']:checked").val()=="regstring")
			{
				$(".reg_string").hide();
			} else {
				$(".ip_based").hide();
			}
			
			$("#dialplan_entry").val('');
			$("#account_entry_bak").val($("#account_entry").val());
			$("#account_entry").val('');
		} else {
			$(".advanceConfig").hide();
			$(".basicConfig").show();
			$("#protocol").hide();
			
			if ($("input[name='reg_auth']:checked").val()=="regstring")
			{
				$(".reg_string").show();
			} else {
				$(".ip_based").show();
			}
			
			if ($("#dialprefix").val() > 0 && ($(this).val()=='SIP' || $(this).val()=='IAX2'))
			{
				changeValue("header",$("#carrier_id").val());
				
				var dialEntry = [];
				var prefix = $("#dialprefix").val();
				dialEntry[0] = "exten => _"+prefix+".,1,AGI(agi://127.0.0.1:4577/call_log)";
				dialEntry[1] = "exten => _"+prefix+".,2,Dial("+$(this).val()+"/${EXTEN:"+prefix.length+"}@"+$("#carrier_id").val()+",,tTo)";
				dialEntry[2] = "exten => _"+prefix+".,3,Hangup";
				
				$("#dialplan_entry").val(dialEntry.join("\n"));
				$("#account_entry").val($("#account_entry_bak").val());
			}
		}
	});
});

function displayserver_ip(getObj){
    if(!$(getObj).prop("checked")){
        $("#server_ip").show();
    }else{
        $("#server_ip").hide();
    }
}

$(document).ready(function()
{
	var auth_type = '<?=$regstrChecked ?>';
	if (auth_type)
	{
		$(".reg_string").show();
		$(".ip_based").hide();
	} else {
		$(".reg_string").hide();
		$(".ip_based").show();
	}
	
	changeValue("header",$("#carrier_id").val());
	
	$("input[name='reg_auth']").change(function()
	{
		if ($(this).val() == 'regstring')
		{
			$(".reg_string").show();
			$(".ip_based").hide();
			var regUser = $("#username").val();
			var regPass = $("#secret").val();
			var regHost = $("#host").val();
			var regPort = $("#port").val();
			
			changeValue("username",$("#username").val(),"show");
			changeValue("secret",$("#secret").val(),"show");
			
			$("#registration_string").val("register => "+regUser+":"+regPass+"@"+regHost+":"+regPort+"/"+regUser);
		} else {
			$(".reg_string").hide();
			$(".ip_based").show();
			
			changeValue("username",$("#username").val(),"hide");
			changeValue("secret",$("#secret").val(),"hide");
			
			$("#registration_string").val('');
		}
	});
	
	$("input[name='dtmf_mode']").change(function()
	{
		if ($(this).val()=="custom")
			$("#customDTMF").show();
		else
			$("#customDTMF").hide();
		
		changeValue("dtmfmode",$(this).val());
	});
});

function changeValue(cVar,cVal,cOpt)
{
	//alert(cVar+":"+cVal);
	var tarea = $("#account_entry_bak").val();
	var tareaVal = tarea.split("\n");
	var protocol = $("#customProtocol").val();
	var tFinal = [];
	var textLine = '';
	var i = 0;
	var gsm = 0;
	var ulaw = 0;
	var alaw = 0;
	var g729 = 0;
	var uname = 0;
	var secret = 0;
	
	if (cOpt===undefined)
		cOpt="";
	
	while (i < tareaVal.length)
	{
		if (i==0)
		{
			if (cVar=="header")
				tFinal[i] = "["+cVal+"]";
			else
				tFinal[i] = tareaVal[i];
			
			if ($("#dialprefix").val() > 0)
			{
				var dialEntry = [];
				var prefix = $("#dialprefix").val()
				dialEntry[0] = "exten => _"+prefix+".,1,AGI(agi://127.0.0.1:4577/call_log)";
				dialEntry[1] = "exten => _"+prefix+".,2,Dial("+protocol+"/${EXTEN:"+prefix.length+"}@"+$("#carrier_id").val()+",,tTo)";
				dialEntry[2] = "exten => _"+prefix+".,3,Hangup";
				
				$("#dialplan_entry_bak").val(dialEntry.join("\n"));
				$("#dialplan_entry").val(dialEntry.join("\n"));
			}
		} else {
			line = tareaVal[i].split("=");
			
			if (line[0]=="allow")
			{
				if (cVal=="gsm" && cVal!=line[1])
					gsm++;
				if (cVal=="ulaw" && cVal!=line[1])
					ulaw++;
				if (cVal=="alaw" && cVal!=line[1])
					alaw++;
				if (cVal=="g729" && cVal!=line[1])
					g729++;
			}
			
			if (line[0]=="username")
				uname++;
				
			if (line[0]=="secret")
				secret++;
			
			if (line[0]==cVar && cOpt.length > 0)
			{
				if (cVar=="allow")
				{
					if (cVal==line[1])
					{
						if (cOpt=="show")
						{
							tFinal[i] = cVar+"="+cVal;
						}
					} else {
						tFinal[i] = line[0]+"="+line[1];
					}
				} else if (cVar=="username") {
					if (cOpt=="show")
						tFinal[i] = cVar+"="+cVal;
				} else if (cVar=="secret") {
					if (cOpt=="show")
						tFinal[i] = cVar+"="+cVal;
				}
			} else {
				if (line[0]==cVar)
					line[1]=cVal;
				
				tFinal[i] = line[0]+"="+line[1];
			}
		}
		i++;
	}

	if ((gsm > 0 || ulaw > 0 || alaw > 0 || g729 > 0 || uname < 1 || secret < 1) && cOpt=="show")
	{
		i++;
		tFinal[i] = cVar+"="+cVal;
	}
	
	tFinal = tFinal.join("\n").replace(/\n\n/g, "\n");
	$("#account_entry").val(tFinal);
	$("#account_entry_bak").val(tFinal);
}
</script>

<div style="float:right;" id="small_step_number"><img src="<?php echo $base; ?>img/step2of2-navigation-small.png" /></div>
<div style="font-weight:bold;font-size:16px;color:#333;">Carrier Wizard &raquo; Add New Carrier</div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />

<table style="width:100%;">
	<tr>
		<td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step2-trans.png" /></div>
		</td>
		<td valign="top">
            <span id="wizardContent" style="height:100px; padding-top:10px;">
				<form id="carrierForm" method="POST">
				<?=form_hidden('active','N') ?>
                <table id="carrierTable" style="width:100%;">
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        Carrier ID:
                        </td>
                        <td>
                        <?=form_input('carrier_id',null,'id="carrier_id" maxlength="15" size="15"') ?>
						<span id="aloading"></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        Carrier Name:
                        </td>
                        <td>
                        <?=form_input('carrier_name',null,'id="carrier_name" maxlength="50" size="25"') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;white-space:nowrap;">
                        Carrier Description:
                        </td>
                        <td>
                        <?=form_input('carrier_description',null,'id="carrier_description" maxlength="255" size="40"') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        User Group:
                        </td>
                        <td>
                        <?php
						$groupArray = array("---ALL---"=>"ALL USER GROUPS");
						foreach ($user_groups as $group)
						{
							$groupArray[$group->user_group] = "{$group->user_group} - {$group->group_name}";
						}
						echo form_dropdown('user_group',$groupArray,null,'id="user_group"');
						?>
                        </td>
                    </tr>
					<tr class="basicConfig">
						<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
						Authentication:
						</td>
						<td style="padding-left:5px;">
						<?=form_radio('reg_auth','ipbased',$ipbasedChecked,'id="reg_auth"'); ?> IP Based &nbsp; 
						<?=form_radio('reg_auth','regstring',$regstrChecked,'id="reg_auth"'); ?> Registration
						</td>
					</tr>
					<tr class="reg_string" style="display:none;">
						<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
						Username:
						</td>
						<td>
						<?=form_input('reg_user',null,'id="username" maxlength="25"'); ?>
						</td>
					</tr>
					<tr class="reg_string" style="display:none;">
						<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
						Password:
						</td>
						<td>
						<?=form_input('reg_pass',null,'id="secret" maxlength="25"'); ?>
						</td>
					</tr>
					<tr class="reg_string" style="display:none;">
						<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
						Servers IP/Host:
						</td>
						<td>
						<?=form_input('reg_host',null,'id="host" maxlength="255" size="40"'); ?>
						</td>
					</tr>
					<tr class="reg_string" style="display:none;">
						<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
						Port:
						</td>
						<td>
						<?=form_input('reg_port','5060','id="port" maxlength="10" size="10"'); ?>
						</td>
					</tr>
					<tr class="ip_based" style="display:none;">
						<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
						<!-- Server IP/Host: -->
                                                SIP Server:
						</td>
						<td>
						<?=form_input('ip_host',null,'id="iphost" maxlength="255" size="40"'); ?>
						</td>
					</tr>
                    <tr class="advanceConfig" style="display: none;">
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        Registration String:
                        </td>
                        <td>
                        <?=form_input('registration_string',null,'id="registration_string" maxlength="255" size="50"') ?>
                        </td>
                    </tr>
                    <tr style="display: none;">
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        Template ID:
                        </td>
                        <td>
                        <?php
						$tempArray = array(''=>'--NONE--');
						foreach ($templates as $temp)
						{
							$tempArray[$temp->id] = "{$temp->id} - {$temp->name}";
						}
						echo form_dropdown('template_id',$tempArray,null,'id="template_id"');
						?>
                        </td>
                    </tr>
					<tr class="basicConfig">
						<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
						Codecs:
						</td>
						<td style="padding-left:5px;white-space:nowrap;">
						<?=form_checkbox('allow_gsm','allow_gsm',$allowGSM,'id="allow_gsm"'); ?> GSM &nbsp; 
						<?=form_checkbox('allow_ulaw','allow_ulaw',$allowULAW,'id="allow_ulaw"'); ?> ULAW &nbsp; 
						<?=form_checkbox('allow_alaw','allow_alaw',$allowALAW,'id="allow_alaw"'); ?> ALAW &nbsp; 
						<?=form_checkbox('allow_g729','allow_g729',$allowG729,'id="allow_g729"'); ?> G729 &nbsp; 
						<br style="display: none;" />
						<?=form_checkbox('allow_custom','allow_custom',$allowCustom,'id="allow_custom" style="display: none;"'); ?><!-- Custom Codec(s) &nbsp; -->
						<?=form_input('customCodecs',$customCodecVal,'id="customCodecs" maxlength="20" size="30" style="display:none" placeholder="comma delimited eg. speex,g711"'); ?>
						</td>
					</tr>
					<tr class="basicConfig">
						<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
						DTMF Mode:
						</td>
						<td style="padding-left:5px;white-space:nowrap;">
						<?=form_radio('dtmf_mode','rfc2833',$rfc2833,'id="dtmf_mode"'); ?> RFC2833 &nbsp; 
						<?=form_radio('dtmf_mode','inband',$inband,'id="dtmf_mode"'); ?> Inband &nbsp; 
						<?=form_radio('dtmf_mode','custom',$customDTMF,'id="dtmf_mode"'); ?> Custom DTMF 
						<?=form_input('customDTMF',$customDTMFVal,'id="customDTMF" maxlength="20" size="16" style="display:none" placeholder="Enter Custom DTMF"'); ?>
						</td>
					</tr>
					<tr class="advanceConfig" style="display:none;">
						<td style="text-align:right;width:30%;height:10px;font-weight:bold;">
						Account Entry:
						</td>
						<td>
						<?php
						$options = array(
						  'name'        => 'account_entry',
						  'id'          => 'account_entry',
						  'value'       => "$accountEntry",
						  'cols'		=> '55',
						  'rows'        => '10',
						  'style'       => 'resize: none;',
						);
						echo form_textarea($options);
						?>
						</td>
					</tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        Protocol:
                        </td>
                        <td>
                        <?php
						$protocolCustom = array('SIP'=>'SIP','Zap'=>'Zap','IAX2'=>'IAX2','EXTERNAL'=>'EXTERNAL');
						$protocolArray = array('SIP'=>'SIP','IAX2'=>'IAX2','---CUSTOM---'=>'CUSTOM');
						echo form_dropdown('customProtocol',$protocolArray,null,'id="customProtocol"');
						echo form_dropdown('protocol',$protocolCustom,null,'id="protocol" style="display:none"');
						?>
                        </td>
                    </tr>
					<tr style="display: none;">
						<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
						Dial Prefix:
						</td>
						<td>
						<?=form_input('dialprefix',$randPrefix,'id="dialprefix" maxlength="15" size="20"'); ?>
						</td>
					</tr>
                    <tr class="advanceConfig" style="display: none;">
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        Globals String:
                        </td>
                        <td>
                        <?=form_input('globals_string',null,'id="globals_string" maxlength="255" size="50"') ?>
                        </td>
                    </tr>
					<tr class="advanceConfig" style="display:none;">
						<td style="text-align:right;width:30%;height:10px;font-weight:bold;">
						Dialplan Entry:
						</td>
						<td>
						<?php
						$options = array(
						  'name'        => 'dialplan_entry',
						  'id'          => 'dialplan_entry',
						  'value'       => "",
						  'cols'		=> '65',
						  'rows'        => '10',
						  'style'       => 'resize: none;',
						);
						echo form_textarea($options);
						?>
						</td>
					</tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        Server IP:
                        </td>
                        <td>
                        <?php
                                                $checkboxAtt = array('id'=>'servers_checkbox','value'=>'default','checked'=>true,'onchange'=>'displayserver_ip(this)');
                                                echo form_checkbox($checkboxAtt);
						$serverArray = array();
						foreach ($server_ips as $server)
						{
							$serverArray["{$server->server_ip}"] = "{$server->server_ip} - {$server->server_description}";
						}
						echo form_dropdown('server_ip',$serverArray,$_SERVER["SERVER_ADDR"],'id="server_ip"');
						?>
                        </td>
                    </tr>
                </table>
				</form>
            </span>
		</td>
	</tr>
</table>
<?php
$options = array(
  'name'        => 'account_entry_bak',
  'id'          => 'account_entry_bak',
  'value'       => "$accountEntry",
  'cols'		=> '55',
  'rows'        => '10',
  'style'       => 'resize: none;display:none;'
);
echo form_textarea($options);
?>
<hr style="border:#DFDFDF 1px solid;" />
<span id="saveButtons"><span id="cancel" style="white-space: nowrap;">Cancel</span> | <span id="submit" style="white-space: nowrap;">Submit</span></span>
