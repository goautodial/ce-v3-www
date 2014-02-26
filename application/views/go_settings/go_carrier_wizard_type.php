<?php
########################################################################################################
####  Name:             	go_carrier_wizard_type.php                                           ####
####  Type:             	ci views for carrier - administrator                                 ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Franco Hora					            	    ####
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

#carrier_type {cursor:pointer;}
</style>

<script>

    $("#carrier").click(function(){
        var $type = $('#carrier_type').val();
        if($type === "GOAUTODIAL"){
            $.post(
                   "<?=$base?>index.php/go_carriers_ce/checksippyavailable",
                   function(data){
                         if(data.indexOf("Error") === -1){
	                     $('#overlayContent').empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>').fadeOut("slow").load('<? echo $base; ?>index.php/go_carriers_ce/sippywelcome').fadeIn("slow");
                         }else{
                             alert(data);
                             $("#box").animate({top:-3000});
                             $("#overlay").fadeOut("fast");
                         }
                    }
            );
        }else if($type === 'COPY'){
	    $("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	    $('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_carriers_ce/go_copy_carrier').fadeIn("slow");
        } else {
	    $("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
	    $('#overlayContent').fadeOut("slow").load('<? echo $base; ?>index.php/go_carriers_ce/go_carrier_wizard').fadeIn("slow");
        } 
    });



</script>

<div style="float:right;" id="small_step_number"><img src="<?php echo $base; ?>img/step1of2-navigation-small.png" /></div>
<div style="font-weight:bold;font-size:16px;color:#333;">Carrier Wizard &raquo; Add New Carrier</div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />

<table style="width:100%;">
	<tr>
		<td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step1-trans.png" /></div>
		</td>
		<td valign="top" style="padding-left:150px;">
                       <label><strong>Carrier type:</strong><span> <?=form_dropdown('carrier_type',array('GOAUTODIAL'=>'GoAutoDial - JustGoVoIP','MANUAL'=>'Manual','COPY'=>'Copy Carrier'),null,'id="carrier_type"')?></span></label>
		</td>
	</tr>
</table>
<hr style="border:#DFDFDF 1px solid;" />
<span id="saveButtons"><span id="carrier" style="white-space: nowrap;">Next</span></span>
