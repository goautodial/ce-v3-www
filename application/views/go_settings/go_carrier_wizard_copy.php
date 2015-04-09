<?php
########################################################################################################
####  Name:             	go_carrier_wizard_copy.php                                                  ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Christopher Lomuntad				                    ####
####  Modified by:       	Franco E. Hora  				                    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$base = base_url();
$NOW = date('Y-m-d');
?>
<style type="text/css">
#carrierTable input,
#carrierTable select,
#carrierTable textarea {
	border: 1px solid #999;
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

#signup-table{width:90%;left:30px;}
.error{color:red;}

.processing{z-index:500;position:fixed;text-align:center;top:0;bottom:0;left:0;right:0;}
.processing img{margin-top:150px;opacity:0.8;}
</style>

<script type="text/javascript">

   $('#submit').click(function(){
       $("#copy_carrier").validate({
           rules:{
                   carrier_id : {required:true,duplicate: true,noSpace:true},
                   carrier_name : {required:true}
           },
           messages:{
                     carrier_id: {required:"* <? echo $this->lang->line("go_required"); ?>"},
                     carrier_name: {required:"* <? echo $this->lang->line("go_required"); ?>"}
           },
           submitHandler:function(form){

                if(this.valid()){
                     $.post(
                            "<?=$base?>index.php/go_carriers_ce/copycarrier",
                            $(form).serialize(),
                            function (data){
                                 alert(data);
                                 if(data.indexOf('Success') !== -1){
                                     location.reload();
                                 }
                            }
                     );
                }

           }
       });

       $.validator.addMethod('duplicate',
                             function(value,elem){
                                 var $respond = "";
                                 $.ajax({
                                        type: "POST",
                                        url:"<?=$base?>index.php/go_carriers_ce/duplicate",
                                        cache : false,
                                        async : false,
                                        data : {carrier_id : value, server_ip : $('#server_ip').val()},
                                        success: function(result){
                                            $respond = result;
                                        }
                                 });
                                 return Boolean($respond); 
                             },
                             "Entry exist"
                            );

       $.validator.addMethod('noSpace',
                             function(value,elem){
                                return this.optional(elem) || !/[ \t]/.test(value);
                             },
                             "<? echo $this->lang->line('go_space'); ?>");

       $("#copy_carrier").submit();

   });


   $('#cancel').click(function(){

       // cancel 
       $('#cancel').click(function(){
   	   $("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	   $('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_carriers_ce/go_carrier_type/').fadeIn("slow");
       });

   });


    $('#carrier_id,#carrier_name').keydown(function(event)
	{
		//console.log(event.keyCode);
		if ((event.keyCode == 32 && $(this).attr('id') != 'carrier_name') || event.keyCode == 222 || event.keyCode == 221 || event.keyCode == 220
			|| event.keyCode == 219 || event.keyCode == 192 || event.keyCode == 191 || (event.keyCode == 190 && ($(this).attr('id') != 'carrier_name' || event.shiftKey))
			|| event.keyCode == 188 || event.keyCode == 61 || event.keyCode == 59)
			return false;
		
		if (event.shiftKey && (event.keyCode > 47 && event.keyCode < 58))
			return false;
		
		if (!event.shiftKey && event.keyCode == 173)
			return false;
		
		$(this).css('border','solid 1px #999');
    });
	
	$('#carrier_id').keyup(function(e)
	{
		if ($(this).val().length > 2)
		{
			$('#aloading').load('<? echo $base; ?>index.php/go_carriers_ce/go_check_carrier/'+$(this).val());
		} else {
			$('#aloading').html("<small style=\"color:red;\"><? echo $this->lang->line("go_min_3_char"); ?></small>");
		}
	});
</script>

<div style="float:right;" id="small_step_number"><img src="<?php echo $base; ?>img/step2of2-navigation-small.png" /></div>
<div style="font-weight:bold;font-size:16px;color:#333;"><? echo $this->lang->line("go_carrier_wizard"); ?> &raquo; <? echo $this->lang->line("go_add_new_carrier"); ?></div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />

<table style="width:100%;">
	<tr>
		<td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step2-trans.png" /></div>
		</td>
		<td valign="top" style="padding-left:100px;">
                   <form id="copy_carrier" method="post">
                     <table width="100%">
                         <tr>
                             <td style="width: 100px;white-space: nowrap;"><strong><? echo $this->lang->line("go_carrier_id"); ?>:</strong></td><td><?=form_input('carrier_id',null,'id="carrier_id"')?> <span id="aloading"></span></td>
                         </tr>
                         <tr>
                             <td style="width: 100px;white-space: nowrap;"><strong><? echo $this->lang->line("go_carrier_name"); ?>:</strong></td><td><?=form_input('carrier_name',null,'id="carrier_name"')?></td>
                         </tr>
                         <tr>
                             <td style="width: 100px;white-space: nowrap;"><strong><? echo $this->lang->line("go_server_ip"); ?>:</strong></td><td><?=form_dropdown('server_ip',$server,null,'id="server_ip"')?></td>
                         </tr>
                         <tr>
                             <td style="width: 100px;white-space: nowrap;"><strong><? echo $this->lang->line("go_src_carrier"); ?>:</strong></td><td><?=form_dropdown('source_id',$carriers,null,'id="source_id"')?></td>
                         </tr>
                     </table>
                   </form>
		</td>
	</tr>
</table>
<hr style="border:#DFDFDF 1px solid;" />
<span id="saveButtons"><span id="cancel" style="white-space: nowrap;"><? echo $this->lang->line("go_cancel"); ?></span> | <span id="submit" style="white-space: nowrap;"><? echo $this->lang->line("go_submit"); ?></span></span>
