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
?>
<style type="text/css">
#phoneTable input,
#phoneTable select {
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

.modify-value {
    font-weight: bold;
    color: #7f7f7f;
}

.error{
    color:red;
}
</style>

<script>
$(function()
{

	/*$('input[id^="extension_"]').keyup(function(e)
	{
		if ($(this).val().length <= 15)
			$('#dialplan_number').val($(this).val());
		if ($(this).val().length <= 10)
			$('#voicemail_id').val($(this).val());
		if ($(this).val().length <= 15)
			$('#login').val($(this).val());
		
		$('#fullname').val($(this).val());
			
		if ($(this).val().length > 3)
		{
			$('#dialplan_number').css('border','solid 1px #999');
			$('#voicemail_id').css('border','solid 1px #999');
		
			$('#aloading').empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
			$('#aloading').load('<? echo $base; ?>index.php/go_phones_ce/go_check_phone/'+$(this).val());
		} else {
			$('#aloading').html("<small style=\"color:red;\">Minimum of 4 digits.</small>");
		}
	});*/
	
 
        $("#submit").click(function(){
            $("#phoneForm").validate({
                 submitHandler: function(form){

                     if(this.valid()){ 
                         $.post(
                                '<? echo $base; ?>index.php/go_phones_ce/autogensave',
                                $(form).serialize(),
                                function(data){
                                     if(data.indexOf("Success") !== -1){
                                          alert("Success: Phone saved");
                                          location.reload();
                                     }else if(data.indexOf("Error") !== -1){
                                          alert("Error: Error on saving phone");
                                     }
                                }
                         );
                     }

                 }
            });

            $("#phoneForm > table").find("input").each(function(){
                if($(this).parent().parent().css("display") !== "none"){
                   if($(this).attr('id').indexOf("pass") !== -1){
                       $(this).rules("add",{required:true,minlength:2,messages:{required:"* Required",minlength:"Minimum 2 character"}});
                   }else{
                       if($(this).attr('id').indexOf("extension") !== -1){
                           $(this).rules("add",{required:true,minlength:2,number:true,checkphones:true,messages:{required:"* Required",minlength:"Minimum 2 character"}});
                       }else{
                           $(this).rules("add",{required:true,minlength:2,number:true,messages:{required:"* Required",minlength:"Minimum 2 character"}});
                       }
                   }
                }
            });

            $.validator.addMethod('checkphones',
                                  function(val,elem){
                                        var respond = '';
                                        $.ajax({
                                              type:"POST",
                                              url:'<? echo $base; ?>index.php/go_phones_ce/go_check_phone/'+val,
                                              cache:false,
                                              async:false,
                                              success: function(result){
                                                   if(result.indexOf("Not Available") === -1){
                                                        respond = 1;
                                                   }
                                              }
                                        });
                                        return Boolean(respond);
                                  },"Not Available");


            $("#phoneForm").submit();
        });
 

        $("#cancel").click(function(){
	        $("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	        $('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_phones_ce/go_phone_wizard_multi/').fadeIn("slow");
        });
});
</script>

<div style="float:right;" id="small_step_number"><img src="<?php echo $base; ?>img/step2of2-nav-small.png" /></div>
<div style="font-weight:bold;font-size:16px;color:#333;">Phone Wizard &raquo; Add New Phone</div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />

<table style="width:100%;">
	<tr>
		<td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step2-trans.png" /></div>
		</td>
		<td valign="top" style="padding-left:20px;">
            <span id="wizardContent" style="height:100px; padding-top:10px;">
		<form id="phoneForm" method="POST">
                <?foreach($generate as $key => $gens){?>
		<input type="hidden" name="messages_<?=$key?>" value="0" />
		<input type="hidden" name="old_messages_<?=$key?>" value="0" />
                <table id="phoneTable" style="width:100%;">
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;white-space:nowrap;">
                        <label class="modify-value">Phone Extension/Login:</label>
                        </td>
                        <td>
                        <?=form_input('extension_'.$key,$gens["extension_$key"],'id="extension_'.$key.'" maxlength="20" size="20"') ?>&nbsp;
						<span id="aloading"></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        <label class="modify-value">Phone Login Password:</label>
                        </td>
                        <td>
                        <?=form_input('pass_'.$key,$gens["pass_$key"],'id="pass_'.$key.'" maxlength="10" size="10"') ?>
                        </td>
                    </tr>
                    <tr <?=($gens["protocol_$key"]=="EXTERNAL") ? "" : 'style="display:none"'; ?>>
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        <label class="modify-value">Dial Prefix:</label>
                        </td>
                        <td>
						<?php
						$dial_prefix = (strlen($gens["dial_prefix_$key"]) > 0) ? $gens["dial_prefix_$key"] : "9999";
						?>
                        <?=form_input('dial_prefix_'.$key,$dial_prefix,'id="dial_prefix_'.$key.'" maxlength="10" size="10"') ?>&nbsp;<font size="1" color="red">(numeric only)</font>&nbsp;
                        </td>
                    </tr>
                    <tr style="display:none;">
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        <label class="modify-value">Dial Plan Number:</label>
                        </td>
                        <td>
                        <?=form_input('dialplan_number_'.$key,$gens["extension_$key"],'id="dialplan_number_'.$key.'" maxlength="15" size="20"') ?>&nbsp;<font size="1" color="red">(numeric only)</font>&nbsp;
                        </td>
                    </tr>
                    <tr style="display:none;">
                        <td style="text-align:right;width:30%;height:10px;font-weight:bold;">
                        <label class="modify-value">Voicemail Box:</label>
                        </td>
                        <td>
                        <?=form_input('voicemail_id_'.$key,$gens["extension_$key"],'id="voicemail_id_'.$key.'" maxlength="10" size="15"') ?>&nbsp;<font size="1" color="red">(numeric only)</font>&nbsp;
                        </td>
                    </tr>
                    <tr style="display:none;">
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Outbound Caller ID:</label>
                        </td>
                        <td>
                        <?=form_input('outbound_cid_'.$key,null,'id="outbound_cid_'.$key.'" maxlength="20" size="15"') ?>&nbsp;<font size="1" color="red">(numeric only)</font>&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">User Group:</label>
                        </td>
                        <td>
                        <?php
						$groupArray = array("---ALL---"=>"ALL USER GROUPS");
						$currentGroup = $this->session->userdata('user_group');
						foreach ($user_groups as $group)
						{
							$groupArray["{$group->user_group}"] = "{$group->user_group} - {$group->group_name}";
						}
						echo form_dropdown('user_group_'.$key,$groupArray,$gens["user_group_$key"],'id="user_group_'.$key.'"');
						?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Server IP:</label>
                        </td>
                        <td>
                        <?php
						$serverArray = array();
						foreach ($server_ips as $server)
						{
							$serverArray["{$server->server_ip}"] = "{$server->server_ip} - {$server->server_description}";
						}
						ksort($serverArray);
						echo form_dropdown('server_ip_'.$key,$serverArray,$gens["server_ip_$key"],'id="server_ip_'.$key.'"');
						?>
                        </td>
                    </tr>
                    <tr class="advance_settings" style="display:none;">
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Agent Screen Login:</label>
                        </td>
                        <td>
                        <?=form_input('login_'.$key,null,'id="login_'.$key.'" maxlength="15" size="15"') ?>
                        </td>
                    </tr>
                    <tr class="advance_settings" style="display:none;">
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">SIP Registration Password:</label>
                        </td>
                        <td>
                        <?=form_input('conf_secret_'.$key,$gens["pass_$key"],'id="conf_secret_'.$key.'" maxlength="10" size="10"') ?>
                        </td>
                    </tr>
                    <tr style="display:none;">
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Status:</label>
                        </td>
                        <td>
                        <?php
						$statusArray = array('ACTIVE'=>'ACTIVE','SUSPENDED'=>'SUSPENDED','CLOSED'=>'CLOSED','PENDING'=>'PENDING','ADMIN'=>'ADMIN');
						echo form_dropdown('status_'.$key,$statusArray,null,'id="status_'.$key.'"');
						?>
                        </td>
                    </tr>
                    <tr style="display:none;">
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Active Account:</label>
                        </td>
                        <td>
                        <?php
						$activeArray = array('Y'=>'Y','N'=>'N');
						echo form_dropdown('active_'.$key,$activeArray,null,'id="active_'.$key.'"');
						?>
                        </td>
                    </tr>
                    <tr style="display: none;">
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Phone Type:</label>
                        </td>
                        <td>
                        <?=form_input('phone_type_'.$key,null,'id="phone_type_'.$key.'" maxlength="50" size="20"') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Fullname:</label>
                        </td>
                        <td>
                        <?=form_input('fullname_'.$key,$gens["fullname_$key"],'id="fullname_'.$key.'" maxlength="50" size="20"') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Client Protocol:</label>
                        </td>
                        <td>
                        <?php
						$protocolArray = array('EXTERNAL'=>'EXTERNAL','SIP'=>'SIP','IAX2'=>'IAX2');
						echo form_dropdown('protocol_'.$key,$protocolArray,$gens["protocol_$key"],'id="protocol_'.$key.'"');
						?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:30%;height:10px;">
                        <label class="modify-value">Local GMT:</label>
                        </td>
                        <td>
                        <?php
						$gmtArray = array(
							'12.75'=>'12.75','12.00'=>'12.00','11.00'=>'11.00','10.00'=>'10.00',
							'9.50'=>'9.50','9.00'=>'9.00','8.00'=>'8.00','7.00'=>'7.00',
							'6.50'=>'6.50','6.00'=>'6.00','5.75'=>'5.75','5.50'=>'5.50',
							'5.00'=>'5.00','4.50'=>'4.50','4.00'=>'4.00','3.50'=>'3.50',
							'3.00'=>'3.00','2.00'=>'2.00','1.00'=>'1.00','0.00'=>'0.00',
							'-1.00'=>'-1.00','-2.00'=>'-2.00','-3.00'=>'-3.00','-3.50'=>'-3.50',
							'-4.00'=>'-4.00','-5.00'=>'-5.00','-6.00'=>'-6.00','-7.00'=>'-7.00',
							'-8.00'=>'-8.00','-9.00'=>'-9.00','-10.00'=>'-10.00','-11.00'=>'-11.00','-12.00'=>'-12.00'
						);
						echo form_dropdown('local_gmt_'.$key,$gmtArray,$system_settings->default_local_gmt,'id="local_gmt_'.$key.'"');
						?>
						(Do NOT adjust for DST)
                        </td>
                    </tr>
                </table>
                <?
                    if(count($generate) > 1 && (($key+1)!=count($generate))){
                       echo "<div style='height:0;margin:5px 0 5px;border-top:1px dashed #dfdfdf;'>&nbsp;</div>";
                    }
                  } # end loop generate
                ?>
		</form>
            </span>
		</td>
	</tr>
</table>
<hr style="border:#DFDFDF 1px solid;" />
<span id="saveButtons"><span id="cancel" style="white-space: nowrap;">Back</span> | <span id="submit" style="white-space: nowrap;">Submit</span></span>
