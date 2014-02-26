<?php
#############################################################################################
####  Name:             go_campaign_view.php                                             ####
####  Type: 		ci views - carrierr modify                                       ####
####  Version:          3.0                                                              ####
####  Build:            1366344000                                                       #### 
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community ####
####  Written by:       Christopher Lomuntad <community@goautodial.com>                  ####
####  Modified by:      Franco E. Hora <info@goautodial.com>                             ####
####  License:          AGPLv2                                                           ####
#############################################################################################
$base = base_url();

if (! $isAdvance)
	$isAdvance = 0;
if (! $isDefault)
	$isDefault = 0;

$regstrChecked = FALSE;
$ipbasedChecked = TRUE;
if (strlen($carrier_info->registration_string)>0)
	$regstrChecked = TRUE;
	
if ($regstrChecked)
	$ipbasedChecked = FALSE;
	
$reg_string = str_replace("register => ","",$carrier_info->registration_string);
$reg_string = explode("@",$reg_string);
$reg_auth = explode(":",$reg_string[0]);
$reg_creds = explode(":",$reg_string[1]);
$reg_port = explode("/",$reg_creds[1]);

if ($reg_port[0] < 1)
	$reg_port[0] = "5060";

$allowGSM = FALSE;
$allowULAW = FALSE;
$allowALAW = FALSE;
$allowG729 = FALSE;
$rfc2833 = FALSE;
$inband = FALSE;
$customDTMF = FALSE;
foreach(explode("\n",$carrier_info->account_entry) as $line)
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

if (strlen($reg_creds[0]) < 1 && strlen($host) > 0)
	$reg_creds[0] = $host;
if (strlen($reg_creds[0]) > 0 && strlen($host) < 1)
	$host = $reg_creds[0];

foreach(explode("\n",$carrier_info->dialplan_entry) as $line)
{
	$entry = str_replace("exten => _","",$line);
	$dialprefix = substr($entry,0,strpos($entry,"."));
	if (preg_match("/Dial/",$line))
	{
		$start = strpos($entry,"Dial")+5;
		$length = strpos($entry,"/")-$start;
		$protocol = substr($entry,$start,$length);
		$cstart = strpos($entry,"/")+1;
		$clength = (strpos($entry,"EXTEN")-2)-$cstart;
		$cPrefix = substr($entry,$cstart,$clength);
	}
	
	//if (preg_match("/Dial|AGI|Hangup/",$line))
	//{
	$isDefault++;
	//}
}

for ($i=0;$i<10;$i++)
{
	$randPrefix .= rand(0,9);
}
?>
<script>
$(function()
{
	var isAdvance = <?php echo $isAdvance; ?>;
	if (isAdvance)
	{
		$('.advance_settings').show();
		$('#advance_link').html('[ - ADVANCE SETTINGS ]');
		$('#isAdvance').val('1');
	}
	
	$('.toolTip').tipTip();

	$('#advance_link').click(function()
	{
		if ($('.advance_settings').is(':hidden'))
		{
			$('.advance_settings').show();
			$('#advance_link').html('[ - ADVANCE SETTINGS ]');
			$('#isAdvance').val('1');
		} else {
			$('.advance_settings').hide();
			$('#advance_link').html('[ + ADVANCE SETTINGS ]');
			$('#isAdvance').val('0');
		}
	});

	$('#advance_link').hover(function()
	{
		$(this).css('color','#F00');
	},
	function()
	{
		$(this).css('color','#000');
	});

        $("#registration_string").keydown(function(){
              $(this).css("border","solid 1px #DFDFDF");
        });
	
	// Submit Settings
	$('#saveSettings').click(function()
	{
		var isEmpty = 0;
		//if(/(208.43.27.84)|(dal.justgovoip.com)/g.test($("#registration_string").val())){
		//    $.ajax({
		//          url : "<?=$base?>index.php/go_carriers_ce/checkJustgovoip",
		//          type: "POST",
		//          data: {carrier:$("input[type='hidden']#carrier_id").val()},
		//          async: false,
		//          cache: false,
		//          success: function (data){
		//               if(data !== "1"){
		//                    $('#registration_string').css('border','solid 1px red');
		//                       isEmpty = 1;
		//               }
		//          }
		//    });
		// }
		
		if ($("#customDTMF").is(":visible") && $("#customDTMF").val()==="")
		{
			isEmpty = 1;
			$('#customDTMF').css('border','solid 1px red');
		}

		if(!isEmpty)
		{

                        $("#box").append("<div class='processing'><img src='<?=$base?>img/goloading.gif' ></divv>");
			var items = $('#modifyCarrier').serialize();
			$.post("<?=$base?>index.php/go_carriers_ce/go_carrier_wizard", { items: items, action: "modify_carrier" },
				function(data){
				if (data=="SUCCESS")
				{
                                        $(".processing").remove();
					alert("Success!\n\nPlease wait up to 60 seconds for the changes to take effect.");
					
					$('#box').animate({'top':'-2550px'},500);
					$('#overlay').fadeOut('slow');
					
					location.reload();
				} else {
					alert(data);
				}

			});
		}
	});
    
     $("#closebox2").click(function(){
         $("#box2").animate({top:-3000});
         $("#box").animate({top:70});
     });
	 
	$("#server_ip").change(function(){
		$('#server_ip option:selected').each(function()
		{
			var reg_string = $('#registration_string').val();
			var carrier_id = $('#carrier_id').val();
			var server_ip = $(this).val();
			$.post("<?=$base?>index.php/go_carriers_ce/go_check_carrier/"+carrier_id, { server_ip: server_ip, reg_string: reg_string },function(data){
				//alert(data);
				if (data.match(/Not Available/))
				{
					$('#server_ip').css('border','solid 1px red');
					$('#sloading').html('<small style="color:red;">Account entry already exist for this IP</small>');
				} else {
					$('#server_ip').css('border','solid 1px #999');
					$('#sloading').html('');
				}
			});
		});
	});
	
	$("#username,#secret,#host,#port").keyup(function()
	{
		var regUser = $("#username").val();
		var regPass = $("#secret").val();
		var regHost = $("#host").val();
		var regPort = $("#port").val();
		
		$("#registration_string").val("register => "+regUser+":"+regPass+"@"+regHost+":"+regPort+"/"+regUser);
		changeValue($(this).attr('id'),$(this).val());
	});
	
	$("#ipusername,#ipsecret,#iphost").keyup(function()
	{
		var cVars = $(this).attr('id');
	
		changeValue(cVars.replace("ip",""),$(this).val());
	});
	
	$("#dialprefix").keyup(function()
	{
		var dialEntry = [];
		var prefix = $("#dialprefix").val();
		
		if (prefix.length > 0)
		{
			dialEntry[0] = "exten => _"+prefix+".,1,AGI(agi://127.0.0.1:4577/call_log)";
			dialEntry[1] = "exten => _"+prefix+".,2,Dial(SIP/${EXTEN:"+prefix.length+"}@"+$("#carrier_id").val()+",,tTo)";
			dialEntry[2] = "exten => _"+prefix+".,3,Hangup";
		}
			
		$("#dialplan_entry").val(dialEntry.join("\n"));
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
		$('#customDTMF').css('border','solid 1px #DFDFDF');
		changeValue("dtmfmode",$(this).val());
	});
	
	if ($("#dialprefix").val()==="")
	{
		$("#dialprefix").val('<?=$randPrefix ?>');
	}
	
	$("#showAdvanceConfig").click(function()
	{
		var oldprotocol = '<?=$carrier_info->protocol ?>';
		if ($(".advanceConfig").is(":hidden"))
		{
			$(".advanceConfig").show();
			$(".basicConfig").hide();
			$(this).html("<pre style='display: inline;'>[-]</pre> ADVANCE CONFIGURATION");
			
			if ($("input[name='reg_auth']:checked").val()=="regstring")
			{
				$(".reg_string").hide();
			} else {
				$(".ip_based").hide();
			}
		} else {
			$(".advanceConfig").hide();
			$(".basicConfig").show();
			$("#protocol").val(oldprotocol);
			$(this).html("<pre style='display: inline;'>[+]</pre> ADVANCE CONFIGURATION");
			
			if ($("input[name='reg_auth']:checked").val()=="regstring")
			{
				$(".reg_string").show();
			} else {
				$(".ip_based").show();
			}
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
			$("#showAdvanceConfig").html("<pre style='display: inline;'>[-]</pre> ADVANCE CONFIGURATION");
			
			if ($("input[name='reg_auth']:checked").val()=="regstring")
			{
				$(".reg_string").hide();
			} else {
				$(".ip_based").hide();
			}
		} else {
			$(".advanceConfig").hide();
			$(".basicConfig").show();
			$("#protocol").hide();
			$("#showAdvanceConfig").html("<pre style='display: inline;'>[+]</pre> ADVANCE CONFIGURATION");
			
			if ($("input[name='reg_auth']:checked").val()=="regstring")
			{
				$(".reg_string").show();
			} else {
				$(".ip_based").show();
			}
			
			if (<?=strlen($dialprefix) ?> > 0 && ($(this).val()=='SIP' || $(this).val()=='IAX2'))
			{
				var dialEntry = [];
				if (<?=$isDefault ?> == 3) {
					var prefix = $("#dialprefix").val();
					var c_prefix = '<?=$cPrefix ?>';
					dialEntry[0] = "exten => _"+prefix+".,1,AGI(agi://127.0.0.1:4577/call_log)";
					dialEntry[1] = "exten => _"+prefix+".,2,Dial("+$(this).val()+"/"+c_prefix+"${EXTEN:"+prefix.length+"}@"+$("#carrier_id").val()+",,tTo)";
					dialEntry[2] = "exten => _"+prefix+".,3,Hangup";
				} else {
					var phpArray = <?php echo json_encode(explode("\n",$carrier_info->dialplan_entry)); ?>;
					$.each(phpArray, function(idx, val)
					{
						dialEntry[idx] = val;
					});
				}
				
				$("#dialplan_entry").val(dialEntry.join("\n"));
			}
		}
	});
});

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
	
	var protocol = '<?=$carrier_info->protocol ?>';
	if (protocol != 'SIP' && protocol != 'IAX2')
	{
		$("#customProtocol").val('---CUSTOM---');
		$("#protocol").show();
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

    $('#carrier_id,#carrier_name,#carrier_description,#username,#secret,#host,#port,#ipusername,#ipsecret,#iphost,#customDTMF').keydown(function(event)
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
});

function modifycamp(camp)
{
	/*$('#overlay').fadeIn('fast');
	$('#box').css({'width': '860px', 'margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px', 'position': 'absolute'});
	$('#box').animate({
		top: "70px"
	}, 500);*/

        $("#box").animate({top:-3000});
        $("#box2").animate({top:70});
	$("#overlayContent2").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	$('#overlayContent2').fadeOut("slow").load('<? echo $base; ?>index.php/go_campaign_ce/go_get_settings/'+camp).fadeIn("slow");
}

function changeValue(cVar,cVal,cOpt)
{
	//alert(cVar+":"+cVal);
	var tarea = $("#account_entry").text();
	var tareaVal = tarea.split("\n");
	var protocol = '<?=$protocol ?>';
	var c_prefix = '<?=$cPrefix ?>';
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
			
			if (<?=strlen($dialprefix) ?> > 0 && (protocol=='SIP' || protocol=='IAX2'))
			{
				var dialEntry = [];
				if (<?=$isDefault ?> == 3)
				{
					var prefix = $("#dialprefix").val();
					dialEntry[0] = "exten => _"+prefix+".,1,AGI(agi://127.0.0.1:4577/call_log)";
					dialEntry[1] = "exten => _"+prefix+".,2,Dial("+protocol+"/"+c_prefix+"${EXTEN:"+prefix.length+"}@"+$("#carrier_id").val()+",,tTo)";
					dialEntry[2] = "exten => _"+prefix+".,3,Hangup";
				} else {
					var phpArray = <?php echo json_encode(explode("\n",$carrier_info->dialplan_entry)); ?>;
					$.each(phpArray, function(idx, val)
					{
						dialEntry[idx] = val;
					});
				}
				
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
	$("#account_entry").text(tFinal);
}
</script>
<style>
.buttons {
	color:#7A9E22;
	cursor:pointer;
}

.buttons:hover{
	font-weight:bold;
}

.corner-all{
    border-width:1.5px;
    border-style:solid;
    border-color:#dfdfdf;
    -moz-border-radius:6px;
    -khtml-border-radius:6px;
    -webkit-border-radius:6px;
    border-radius:6px;
    padding:3px;
    background-color: #fff;
}

.campaigns{float:left;border:1px solid #D0D0D0;padding:5px;background-color:#D3FCD7;cursor:pointer;}
.processing{z-index:500;position:fixed;text-align:center;top:0;bottom:0;left:0;right:0;}
.processing img{margin-top:150px;opacity:0.8;}

</style>
<?php
switch ($type)
{
	case "modify":
		break;
	
	default:
?>
<div align="center" style="font-weight:bold; color:#333; font-size:16px;">MODIFY CARRIER: <?php echo "{$carrier_info->carrier_id}"; ?></div>
<br />
<form id="modifyCarrier" method="POST">
<table id="test" border=0 cellpadding="3" cellspacing="3" style="width:95%; color:#000; margin-left:auto; margin-right:auto;">
	<tr>
		<td style="text-align:right;width:25%;height:10px;font-weight:bold;">
		Carrier ID:
		</td>
		<td>
		&nbsp;<?=$carrier_info->carrier_id ?>
		<?#=form_hidden('carrier_id',$carrier_info->carrier_id) ?>
                <input type="hidden" id="carrier_id" value="<?=$carrier_info->carrier_id?>" name="carrier_id">
		<span id="aloading"></span>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:25%;height:10px;font-weight:bold;">
		Carrier Name:
		</td>
		<td>
		<?=form_input('carrier_name',$carrier_info->carrier_name,'id="carrier_name" maxlength="50" size="25"') ?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
		Carrier Description:
		</td>
		<td>
		<?=form_input('carrier_description',$carrier_info->carrier_description,'id="carrier_description" maxlength="255" size="40"') ?>
		</td>
	</tr>
	<!-- <tr>
		<td style="text-align:right;width:30%;height:10px;">
		User Group:
		</td>
		<td>
		<?php
		$groupArray = array("---ALL---"=>"ALL USER GROUPS");
		foreach ($user_groups as $group)
		{
			$groupArray[$group->user_group] = "{$group->user_group} - {$group->group_name}";
		}
		echo form_dropdown('user_group',$groupArray,$carrier_info->user_group,'id="user_group"');
		?>
		</td>
	</tr> -->
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
		<?=form_input('reg_user',$reg_auth[0],'id="username" maxlength="25"'); ?>
		</td>
	</tr>
	<tr class="reg_string" style="display:none;">
		<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
		Password:
		</td>
		<td>
		<?=form_input('reg_pass',$reg_auth[1],'id="secret" maxlength="25"'); ?>
		</td>
	</tr>
	<tr class="reg_string" style="display:none;">
		<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
		Server IP/Host:
		</td>
		<td>
		<?=form_input('reg_host',$reg_creds[0],'id="host" maxlength="255" size="40"'); ?>
		</td>
	</tr>
	<tr class="reg_string" style="display:none;">
		<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
		Port:
		</td>
		<td>
		<?=form_input('reg_port',$reg_port[0],'id="port" maxlength="10" size="10"'); ?>
		</td>
	</tr>
	<tr class="ip_based" style="display:none;">
		<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
		Server IP/Host:
		</td>
		<td>
		<?=form_input('ip_host',$host,'id="iphost" maxlength="255" size="40"'); ?>
		</td>
	</tr>
	<tr class="advanceConfig" style="display:none;">
		<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
		Registration String:
		</td>
		<td>
		<?=form_input('registration_string',$carrier_info->registration_string,'id="registration_string" maxlength="255" size="70"') ?>
		</td>
	</tr>
	<!-- <tr>
		<td style="text-align:right;width:30%;height:10px;">
		Template ID:
		</td>
		<td>
		<?php
		$tempArray = array(''=>'--NONE--');
		foreach ($templates as $temp)
		{
			$tempArray[$temp->id] = "{$temp->id} - {$temp->name}";
		}
		echo form_dropdown('template_id',$tempArray,$carrier_info->template_id,'id="template_id"');
		?>
		</td>
	</tr> -->
	<tr class="basicConfig">
		<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
		Codecs:
		</td>
		<td style="padding-left:5px;white-space:nowrap;">
		<?=form_checkbox('allow_gsm','allow_gsm',$allowGSM,'id="allow_gsm"'); ?> GSM &nbsp; 
		<?=form_checkbox('allow_ulaw','allow_ulaw',$allowULAW,'id="allow_ulaw"'); ?> ULAW &nbsp; 
		<?=form_checkbox('allow_alaw','allow_alaw',$allowALAW,'id="allow_alaw"'); ?> ALAW &nbsp; 
		<?=form_checkbox('allow_g729','allow_g729',$allowG729,'id="allow_g729"'); ?> G729 &nbsp; 
		</td>
	</tr>
	<tr class="basicConfig">
		<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
		DTMF Mode:
		</td>
		<td style="padding-left:5px;white-space:nowrap;">
		<?=form_radio('dtmf_mode','rfc2833',$rfc2833,'id="dtmf_mode"'); ?> RFC2833 &nbsp; 
		<?=form_radio('dtmf_mode','inband',$inband,'id="dtmf_mode"'); ?> Inband &nbsp; 
		<?=form_radio('dtmf_mode','custom',$customDTMF,'id="dtmf_mode"'); ?> Custom &nbsp;
		<?=form_input('customDTMF',$customDTMFVal,'id="customDTMF" maxlength="20" style="display:none" placeholder="Enter Custom DTMF"'); ?>
		</td>
	</tr>
	<tr class="advanceConfig" style="display:none;">
		<td style="text-align:right;width:25%;height:10px;font-weight:bold;">
		Account Entry:
		</td>
		<td>
		<?php
		$options = array(
		  'name'        => 'account_entry',
		  'id'          => 'account_entry',
		  'value'       => "{$carrier_info->account_entry}",
		  'cols'		=> '55',
		  'rows'        => '10',
		  'style'       => 'resize: none;',
		);
		echo form_textarea($options);
		?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:25%;height:10px;font-weight:bold;">
		Protocol:
		</td>
		<td>
		<?php
		$protocolCustom = array('SIP'=>'SIP','Zap'=>'DADHI','IAX2'=>'IAX2','EXTERNAL'=>'EXTERNAL');
		$protocolArray = array('SIP'=>'SIP','IAX2'=>'IAX2','---CUSTOM---'=>'CUSTOM');
		echo form_dropdown('customProtocol',$protocolArray,$carrier_info->protocol,'id="customProtocol"');
		echo form_dropdown('protocol',$protocolCustom,$carrier_info->protocol,'id="protocol" style="display:none"');
		?>
		</td>
	</tr>
	<tr class="advanceConfig" style="display:none">
		<td style="text-align:right;width:25%;height:10px;font-weight:bold;">
		Globals String:
		</td>
		<td>
		<?=form_input('globals_string',$carrier_info->globals_string,'id="globals_string" maxlength="255" size="50"') ?>
		</td>
	</tr>
	<tr style="display:none;">
		<td style="text-align:right;width:25%;height:10px;white-space:nowrap;font-weight:bold;">
		Dial Prefix:
		</td>
		<td>
		<?=form_input('dialprefix',$dialprefix,'id="dialprefix" maxlength="15" size="20"'); ?>
		</td>
	</tr>
	<tr class="advanceConfig" style="display:none;">
		<td style="text-align:right;width:25%;height:10px;font-weight:bold;">
		Dialplan Entry:
		</td>
		<td>
		<?php
		$options = array(
		  'name'        => 'dialplan_entry',
		  'id'          => 'dialplan_entry',
		  'value'       => "{$carrier_info->dialplan_entry}",
		  'cols'		=> '75',
		  'rows'        => '10',
		  'style'       => 'resize: none;',
		);
		echo form_textarea($options);
		?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:25%;height:10px;font-weight:bold;">
		Server IP:
		</td>
		<td>
		<?php
		$serverArray = array();
		foreach ($servers as $server)
		{
			$serverArray["{$server->server_ip}"] = "{$server->server_ip} - {$server->server_description}";
		}
		echo form_dropdown('server_ip',$serverArray,$carrier_info->server_ip,'id="server_ip" style="width:250px;"');
		?> 
		<span id="sloading"></span>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:25%;height:10px;font-weight:bold;">
		Active:
		</td>
		<td>
		<?php
		$activeArray = array('Y'=>'Y','N'=>'N');
		echo form_dropdown('active',$activeArray,$carrier_info->active,'id="active"');
		?>
		</td>
	</tr>
	<tr>
		<td style="text-align:right;width:25%;height:10px;vertical-align:top;font-weight:bold;">Campaigns:</td>
		<td >
			 <div>
			 <?php
				   if(!empty($campaigns)){
					   foreach($campaigns as $campaign){
							echo "<div class='corner-all campaigns' onclick=modifycamp('{$campaign->campaign_id}')>{$campaign->campaign_id} - {$campaign->campaign_name}</div><br style='font-size:10px;' /><br />";
					   }
				   } else {
							echo "<span>There are no campaigns using this carrier.</span>";
				   }
			 ?>
			 </div>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
    	<td><span id="showAdvanceConfig" style="float: left;white-space: nowrap;font-size:11px;color: #7A9E22;cursor: pointer;"><pre style="display: inline;">[+]</pre> ADVANCE CONFIGURATION</span><span id="advance_link" style="cursor:pointer;font-size:9px;display: none;">[ + ADVANCE SETTINGS ]</span><input type="hidden" id="isAdvance" value="0" /></td><td style="text-align:right;"><span id="saveSettings" class="buttons">SAVE SETTINGS</span><!--<input id="saveSettings" type="submit" value=" SAVE SETTINGS " style="cursor:pointer;" />--></td>
    </tr>
</table>
</form>
<?php
		break;
}
?>
<br style="font-size:9px;" />
