<?php
############################################################################################
####  Name:             go_carrier_wizard_sippy.php                                           ####
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

.welcome-header{width:100%;text-align:center;}
.sales-email{float:right;text-align:left;font-size:12px;margin-top:5px; margin-right: 30px}
.signup60{cursor:pointer;}
span{padding:3px;}
</style>

<script>
  $(function(){
     $(".signup60").click(function(){
	    $('#overlayContent').empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>').fadeOut("slow").load('<? echo $base; ?>index.php/go_carriers_ce/go_carrier_sippy_display').fadeIn("slow");
	    $('#signupoverlayContent').empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>').fadeOut("slow").load('<? echo $base; ?>index.php/go_carriers_ce/go_carrier_sippy_display').fadeIn("slow");

     });

     $("#submit").click(function(){
	    $('#overlayContent').empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>').fadeOut("slow").load('<? echo $base; ?>index.php/go_carriers_ce/go_carrier_sippy_display').fadeIn("slow");
	    $('#signupoverlayContent').empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>').fadeOut("slow").load('<? echo $base; ?>index.php/go_carriers_ce/go_carrier_sippy_display').fadeIn("slow");
     });
  
     $("#cancel").click(function(){
	    $('#overlayContent').empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>').fadeOut("slow").load('<? echo $base; ?>index.php/go_carriers_ce/go_carrier_type').fadeIn("slow");
	    $('#signupoverlayContent').empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>').fadeOut("slow").load('<? echo $base; ?>index.php/go_carriers_ce/sippywelcome').fadeIn("slow");
             $('#signupBox').animate({'top':'-2550px'},500);
                $('#signupOverlay').fadeOut('slow');

     });


  });

</script>

<div style="float:right;" id="small_step_number"><!-- <img src="<?php echo $base; ?>img/step2of2-navigation-small.png" />--></div>
<!-- <div style="font-weight:bold;font-size:16px;color:#333;">Signup</div> -->
<br style="font-size:6px;" />
<!-- <hr style="border:#DFDFDF 1px solid;" /> -->

<table style="width:100%;">
	<tr>
		<!-- <td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step2-trans.png" /></div>
		</td> -->
		<td valign="top" >
                       <div class="welcome-header">
                          <span><? echo $this->lang->line("go_welcome_to"); ?></span><br class="clear"/><br class="clear"/>
                          <span><a href="http://justgovoip.com" target="_new"><img src="<?=$base?>img/justgovoip.png"></a></span><br class="clear"/><br class="clear"/>
                          <span><?=$VARCLOUDCOMPANY;?> <? echo $this->lang->line("go_ccc"); ?></span><br/>
                          <span align="center" style="padding-left: 50px;">

			<p style="width: 50%; padding-left: 185px; line-height: 17px;" align="justify"><?=$VARCLOUDCOMPANY;?> <? echo $this->lang->line("go_w1"); ?> <?=$VARCLOUDCOMPANY;?> <? echo $this->lang->line("go_w2"); ?> </p><br>
			<p style="width: 50%; padding-left: 185px; line-height: 17px;" align="justify"><? echo $this->lang->line("go_w3"); ?> <?=$VARCLOUDCOMPANY;?> <? echo $this->lang->line("go_w4"); ?>.</p>
                          </span><br /><br/>
                          <br/>
                          <span class="signup60"> <img src="<?=$base?>img/signup60free.png"></span><br/>
                          <span class="sales-email">**<? echo $this->lang->line("go_email"); ?> <a href="mailto:sales@justgovoip.com">sales@justgovoip.com</a> <? echo $this->lang->line("go_w5"); ?>.</span><br/>
                       </div>
		</td>
	</tr>
</table>
<hr style="border:#DFDFDF 1px solid;" />
<span id="saveButtons"><span id="cancel" style="white-space: nowrap;"><? echo $this->lang->line("go_cancel"); ?></span> | <span id="submit" style="white-space: nowrap;"><? echo $this->lang->line("go_next"); ?></span></span>
