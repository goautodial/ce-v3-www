<?php
########################################################################################################
####  Name:             	go_carrier_wizard_sippy.php                                         ####
####  Type:             	ci views for phones - administrator                                 ####	
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

body{font-family: Verdana, Arial, Helvetica, Sans-serif;font-size:12px;}

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

/*
html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, 
blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, 
font, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, 
b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, 
caption, tbody, tfoot, thead, tr, th, td{
   background : none repeat scroll 0 0 transparent;
   border: none;
   margin: 0;
   outline: 0 none;
   padding: 3px;
}

span{padding:3px;
}*/
</style>

<script>
  $(function(){

       // submit
       $("#submit").click(function(){

           if($("#agree").prop("checked") === true){
                try{
                $("#sippy-signup").validate({
                    debug: true,
                    rules:{
                           email : {
                                      required: true,
                                      email: true
                                   },
                           company_name : {required: true,minlength:2},
                           first_name : {required: true,minlength:2},
                           last_name : {required: true,minlength:2},
                           street_addr : {required: true,minlength:2},
                           city: {required: true,minlength:2},
                           state: {required: true,minlength:2},
                           postal_code: {required:true,minlength:2},
                           phone: {required: true,number:true,maxlength:12}
                    },
                    messages : {
                                 company_name: {required:"* <? echo $this->lang->line("go_required"); ?>"},
                                 email: {required:"* <? echo $this->lang->line("go_required"); ?>",email:"<? echo $this->lang->line("go_valid_email"); ?>"},
                                 first_name: {required:"* <? echo $this->lang->line("go_required"); ?>"},
                                 last_name: {required:"* <? echo $this->lang->line("go_required"); ?>"},
                                 street_addr: {required:"* <? echo $this->lang->line("go_required"); ?>"},
                                 city: {required:"* <? echo $this->lang->line("go_required"); ?>"},
                                 state: {required:"* <? echo $this->lang->line("go_required"); ?>"},
                                 postal_code: {required:"* <? echo $this->lang->line("go_required"); ?>"},
                                 phone: {required:"* <? echo $this->lang->line("go_required"); ?>",maxlength:"<? echo $this->lang->line("go_max_12_char"); ?>"}
                    },
                    submitHandler: function(form){
                       if(this.valid()){
                           $("#box").append("<div class='processing'><img src='<?=$base?>img/goloading.gif'></div>"); 
                           $("#signupBox").append("<div class='processing'><img src='<?=$base?>img/goloading.gif'></div>"); 
                           if(window.location.pathname.substr(1).split("/")[0] === "carriers"){
                               setTimeout('$(".processing").remove();alert("<? echo $this->lang->line('go_err_long'); ?>");location.reload();',180000);
                           }
                           $.post(
                                  "<?=$base?>index.php/go_carriers_ce/sippy_register",
                                  $("#sippy-signup").serialize(),
                                  function(data){
                                      $(".processing").remove();
                                      alert(data);
                                      if(data.indexOf('Error') === -1){
					//sippyreload();
                                        if(window.location.pathname.substr(1).split("/")[0] === "carriers"){
                                           location.reload();
                                        }else{
					   $("#sippydiv").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
				   	   $('#sippydiv').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/sippyinfo').fadeIn("slow");  
					   $('#signupBox').animate({'top':'-2550px'},500,function(){
                          				$('#signupOverlay').fadeOut('fast');
							$('#signupOverlay').hide('fast');
                       			   });
                                        }
                                      }
                                  }
                           );
                       }
                    } 
                });


                $("#sippy-signup").submit();
                } catch(e){
                     console.log(e);
                     alert(e);
                }
         

                
   	        //$("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>');
           } else {
                alert("<? echo $this->lang->line("go_accept_tc"); ?>");
           }
       });
 
       // cancel 
       $('#cancel').click(function(){
   	   $("#overlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>').fadeOut("slow").load('<? echo $base; ?>index.php/go_carriers_ce/sippywelcome').fadeIn("slow");
   	   $("#signupoverlayContent").empty().html('<p align="center"><img src="<? echo $base; ?>img/goloading.gif" /></p>').fadeOut("slow").load('<? echo $base; ?>index.php/go_carriers_ce/sippywelcome').fadeIn("slow");
	   $("#sippydiv").empty().html('<img src="<? echo $base; ?>img/loading.gif" />');
	   $('#sippydiv').fadeOut("slow").load('<? echo $base; ?>index.php/go_site/sippyinfo').fadeIn("slow");  
       });

  });

</script>

<div style="float:right;" id="small_step_number"><!-- <img src="<?php echo $base; ?>img/step3-navigation-small.png" />--></div>
<div style="font-weight:bold;font-size:16px;color:#333;"><? echo $this->lang->line("go_sign_up"); ?></div>
<br style="font-size:6px;" />
<hr style="border:#DFDFDF 1px solid;" />

<table style="width:100%;">
	<tr>
		<!-- <td valign="top" style="width:20%">
			<div style="padding:0px 10px 0px 30px;" id="step_number"><img src="<?php echo $base; ?>img/step3-trans.png" /></div>
		</td>
-->
		<td valign="top" style="padding-left:100px;">
                    <form id="sippy-signup">
                    <table id="signup-table" width="100%">
			  <tr><td colspan="2" align="center" style=""><? echo $this->lang->line("go_fill_out"); ?>:</td></tr>
			  <tr><td colspan="2">&nbsp;&nbsp;</td></tr>
                          <tr><td><strong>* <? echo $this->lang->line("go_company"); ?>:</strong></td><td><?=form_input('company_name',null,'id="company_name"')?></td></tr>
                          <tr><td><strong>* <? echo $this->lang->line("go_first_name"); ?>:</strong></td><td><?=form_input('first_name',null,'id="first_name"')?></td></tr>
                          <tr><td><strong>* <? echo $this->lang->line("go_last_name"); ?>:</strong></td><td><?=form_input('last_name',null,'id="last_name"')?></td></tr>
                          <tr><td><strong>* <? echo $this->lang->line("go_address"); ?>:</strong></td><td><?=form_input('street_addr',null,'id="street_addr"')?></td></tr>
                          <tr><td><strong>* <? echo $this->lang->line("go_city"); ?>:</strong></td><td><?=form_input('city',null,'id="city"')?></td></tr>
                          <tr><td><strong>* <? echo $this->lang->line("go_state"); ?>:</strong></td><td><?=form_input('state',null,'id="state"')?></td></tr>
                          <tr><td><strong>* <? echo $this->lang->line("go_postal_code"); ?>:</strong></td><td><?=form_input('postal_code',null,'id="postal_code"')?></td></tr>
                          <tr><td><strong>* <? echo $this->lang->line("go_country"); ?>:</strong></td><td><?=form_dropdown('country',$country,'USA','id="country"')?></td></tr>
                          <tr><td><strong>* <? echo $this->lang->line("go_time_zone"); ?>:</strong></td><td><?=form_dropdown('i_time_zone',$tzs,331,'id="i_time_zone"')?></td></tr>
                          <tr><td><strong>* <? echo $this->lang->line("go_phone"); ?>:</strong></td><td><?=form_input('phone',null,'id="phone"')?></td></tr>
                          <tr><td>&nbsp;&nbsp;<strong><? echo $this->lang->line("go_mobile_phone"); ?>:</strong></td><td><?=form_input('alt_phone',null,'id="alt_phone"')?></td></tr>
                          <tr><td><strong>* <? echo $this->lang->line("go_email"); ?>:</strong></td><td><?=form_input('email',null,'id="email"')?></td></tr>
                          <tr><td>&nbsp;</td></tr>
                          <tr><td style="color:red;font-size:9px;font-style:italic">* <? echo $this->lang->line("go_required"); ?></td></tr>
                          <tr><td>&nbsp;</td></tr>
                          <tr><td colspan="2"><strong><? echo $this->lang->line("go_terms_and_condition"); ?>:</strong></td></tr>
                          <tr>
                              <td colspan="2">
                                  <br>
                                  <div class="boxviewnew" style="height:250px;overflow:scroll; text-align: justify;">

                                      <table cellpadding="0" cellspacing="0">
					<tr>
						<td><p style="font-size: 14px;">
						    <!--This site is owned and operated by Goautodial, Inc. ("we", "us", "our" or "GOautodial").
                                                    Goautodial, Inc. provides its services to you ("Customer", "you" or "end user")
                                                    subject to the following conditions:-->
						    <? echo $this->lang->line("go_tc1"); ?> <?=$VARCOMPANYSEC;?> <? echo $this->lang->line("go_tc2"); ?> "<?=$VARCOMPANYNAME;?>").
                                                    <?=$VARCOMPANYSEC;?><? echo $this->lang->line("go_tc3"); ?>
						    <br>

                                                    <? echo $this->lang->line("go_tc4"); ?>
                                                    <a href="http://reversephonelookuppages.com/" class="faqlinka" style="color: green;"><? echo $this->lang->line("go_tc5"); ?></a> <? echo $this->lang->line("go_tc6"); ?>,
<!--                                                    you affirmatively accept the following conditions.
                                                    Continued use of the site and any of Goautodial's services constitutes
                                                    the affirmative agreement to these terms and conditions.<br>

                                                    Goautodial reserves the right to change the terms, conditions and notices under which the
                                                    Goautodial sites and services are offered,
                                                    including but not limited to the charges associated with the use of the Goautodial sites and services.
-->
                                                    <?=$VARCOMPANYNAME;?>'s <? echo $this->lang->line("go_tc7"); ?>
                                                    <br>
                                                    <?=$VARCOMPANYNAME;?> <? echo $this->lang->line("go_tc8"); ?>
                                                    <?=$VARCOMPANYNAME;?> <? echo $this->lang->line("go_tc9"); ?>
                                                    <?=$VARCOMPANYNAME;?><? echo $this->lang->line("go_tc10"); ?>
						    </p>
						</td>
					</tr>
					<tr><td><br><p style="font-size: 14px;"><b><? echo $this->lang->line("go_tc11"); ?></b></p></td></tr>
                                        <tr><td><p style="font-size: 14px;"><? echo $this->lang->line("go_tc12"); ?> <?=$VARCOMPANYNAME;?>'s <? echo $this->lang->line("go_tc13"); ?></p></td></tr>
                                        <tr><td><br><p style="font-size: 14px;"><b><? echo $this->lang->line("go_tc14"); ?></b></p></td></tr>
                                        <tr><td><p style="font-size: 14px;"><? echo $this->lang->line("go_tc144"); ?></p></td></tr>
                                        <tr><td><br><p style="font-size: 14px;"><b><? echo $this->lang->line("go_tc15"); ?></b></p></td></tr>
                                        <tr><td><p style="font-size: 14px;">3.1. <?=$VARCOMPANYNAME;?> <? echo $this->lang->line("go_tc16"); ?> <?=$VARCOMPANYNAME;?>, <? echo $this->lang->line("go_tc17"); ?> <?=$VARCOMPANYNAME;?> <? echo $this->lang->line("go_tc18"); ?></p></td></tr>
                                        <tr><td><p style="font-size: 14px;">3.2. <?=$VARCOMPANYNAME;?> <? echo $this->lang->line("go_tc19"); ?></p></td></tr>
                                        <tr><td><p style="font-size: 14px;">3.3. <?=$VARCOMPANYNAME;?><? echo $this->lang->line("go_tc200"); ?> <?=$VARCOMPANYNAME;?>.<? echo $this->lang->line("go_tc20"); ?> <?=$VARCOMPANYNAME;?>'s <? echo $this->lang->line("go_tc21"); ?></p></td></tr>
                                        <tr><td><p style="font-size: 14px;">3.4. <?=$VARCOMPANYNAME;?>'s<? echo $this->lang->line("go_tc22"); ?>  <?=$VARCOMPANYNAME;?><? echo $this->lang->line("go_tc23"); ?> </p></td></tr>
                                        <tr><td><p style="font-size: 14px;">3.5. <? echo $this->lang->line("go_tc24"); ?> <?=$VARCOMPANYNAME;?>'s <? echo $this->lang->line("go_tc255"); ?></p></td></tr>
                                        <tr><td><p style="font-size: 14px;">3.6. <? echo $this->lang->line("go_tc25"); ?></p></td></tr>
                                        <tr><td><p style="font-size: 14px;">3.7. <? echo $this->lang->line("go_tc26"); ?></p></td></tr>
                                        <tr><td><p style="font-size: 14px;">3.8. <?=$VARCOMPANYNAME;?> <? echo $this->lang->line("go_tc27"); ?><?=$VARCOMPANYNAME;?>'s<? echo $this->lang->line("go_tc28"); ?>  <?=$VARCOMPANYNAME;?> <? echo $this->lang->line("go_tc29"); ?></p></td></tr>
                                        <tr><td><p style="font-size: 14px;">3.9.<? echo $this->lang->line("go_tc29"); ?>  <?=$VARCOMPANYNAME;?> <? echo $this->lang->line("go_tc30"); ?></p></td>
                                        <tr><td><p style="font-size: 14px;">3.10 <? echo $this->lang->line("go_tc31"); ?></p></td>
                                        <tr><td><p style="font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.10.1 <? echo $this->lang->line("go_tc32"); ?></p></td>
                                        <tr><td><p style="font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.10.2 <? echo $this->lang->line("go_tc33"); ?> </p></td>
                                        <tr><td><p style="font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.10.3 <? echo $this->lang->line("go_tc34"); ?></p></td>
                                        <tr><td><p style="font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.10.4 <? echo $this->lang->line("go_tc35"); ?> </p></td></tr>
                                        <tr><td><br><p style="font-size: 14px;"><b>4. <? echo $this->lang->line("go_tc36"); ?></b></p></td></tr>
                                        <tr><td><p style="font-size: 14px;">4.1. <?=$VARCOMPANYNAME;?> <? echo $this->lang->line("go_tc37"); ?> <?=$VARCOMPANYNAME;?>'s <? echo $this->lang->line("go_tc38"); ?> <a href="<?=$VARCLOUDSUPPORTURL;?>" class="faqlinka" style="color: green;"><?=$VARCLOUDSUPPORTURL;?></a>.</p></td></tr>
                                        <tr><td><br><p style="font-size: 14px;"><b>4.2. <? echo $this->lang->line("go_tc39"); ?></b></p></td></tr>
                                        <tr><td><p style="font-size: 14px;">4.2.1. <?=$VARCOMPANYNAME;?>'s<? echo $this->lang->line("go_tc40"); ?> </p><td></tr>
                                        <tr><td><p style="font-size: 14px; margin-left: 20px;"><? echo $this->lang->line("go_tc41"); ?>
                                                                            </p></td></tr>^M
                                        <tr><td><br><p style="font-size: 14px;">4.2.2.<? echo $this->lang->line("go_tc42"); ?> </p></td></tr>
                                        <tr><td><br><p style="font-size: 14px;">4.2.3.<? echo $this->lang->line("go_tc43"); ?> <?=$VARCOMPANYNAME;?>'s<? echo $this->lang->line("go_tc44"); ?> </p></td></tr>
                                        <tr><td><br><p style="font-size: 14px;">4.2.4. <? echo $this->lang->line("go_tc45"); ?></p></td></tr>
                                        <tr><td><br><b><p style="font-size: 14px;">4.3. <? echo $this->lang->line("go_tc46"); ?></p></b></td></tr>
                                        <tr><td><p style="font-size: 14px;">4.3.1. <? echo $this->lang->line("go_tc47"); ?></p></td></tr>
                                        <tr><td><br><p style="font-size: 14px;">4.3.2. <? echo $this->lang->line("go_tc48"); ?></p></td></tr>
                                        <tr><td><br><b><p style="font-size: 14px;">5. <? echo $this->lang->line("go_tc49"); ?></p></b></td></tr>
                                        <tr><td><p style="font-size: 14px;">5.1. <? echo $this->lang->line("go_tc50"); ?></p></td></tr>
                                        <tr><td><br><p style="font-size: 14px;">5.2. <? echo $this->lang->line("go_tc51"); ?></p></td></tr>
                                        <tr><td><br><p style="font-size: 14px;">5.3. <? echo $this->lang->line("go_tc52"); ?></p></td></tr>
                                        <tr><td><br><p style="font-size: 14px;">5.4. <? echo $this->lang->line("go_tc53"); ?></p></td></tr>
                                        <tr><td><br><b><p style="font-size: 14px;">6. <? echo $this->lang->line("go_tc54"); ?></p></b></td></tr>
                                        <tr><td><p style="font-size: 14px;">6.1. <? echo $this->lang->line("go_tc55"); ?></p></td></tr>
                                        <tr><td><br><p style="font-size: 14px;"><b>7. <? echo $this->lang->line("go_tc56"); ?></b></p></td></tr>
                                        <tr><td><p style="font-size: 14px;">7.1. <? echo $this->lang->line("go_tc57"); ?> <a href="mailto:<?=$VARCLOUDVIOLATEEMAIL;?>"><?=$VARCLOUDVIOLATEEMAIL;?></a>. <?=$VARCOMPANYNAME;?> <? echo $this->lang->line("go_tc58"); ?> <?=$VARCOMPANYNAME;?> <? echo $this->lang->line("go_tc59"); ?></p></td></tr>
                                        <tr><td><br><b><p style="font-size: 14px;">8. <? echo $this->lang->line("go_tc60"); ?></p></b></td></tr>
                                        <tr><td><p style="font-size: 14px;">8.1 <? echo $this->lang->line("go_tc61"); ?> <?=$VARCOMPANYNAME;?><? echo $this->lang->line("go_tc62"); ?>  <?=$VARCOMPANYNAME;?>’s<? echo $this->lang->line("go_tc63"); ?> </p></td></tr>
                                        <tr><td><br><b><p style="font-size: 14px;">9. <? echo $this->lang->line("go_tc64"); ?></p></b></td></tr>
                                        <tr><td><p style="font-size: 14px;">9.1. <? echo $this->lang->line("go_tc65"); ?> <?=$VARCOMPANYSEC;?><? echo $this->lang->line("go_tc66"); ?>  <?=$VARCOMPANYSEC;?><? echo $this->lang->line("go_tc67"); ?> </p></td></tr>
                                        <tr><td><br><b><p style="font-size: 14px;">10. <? echo $this->lang->line("go_tc68"); ?></p></b></td></tr>
                                        <tr><td><p style="font-size: 14px;">10.1. <?=$VARCOMPANYNAME;?> <? echo $this->lang->line("go_tc69"); ?></p></td></tr>
<!--
					<tr><td><p style="font-size: 14px;">1.1. When you visit Goautodial's websites or send Email to us, you are communicating with us electronically. You consent to receive communications from us electronically. We will communicate with you by Email or by posting notices on this site. You agree that all agreements, notices, disclosures and other communications that we provide to you electronically satisfy any legal requirement that such communications be in writing.</p></td></tr>
					<tr><td><br><p style="font-size: 14px;"><b>2. Trademarks and Copyright</b></p></td></tr>
					<tr><td><p style="font-size: 14px;">2.1. All content on this site, such as text, graphics, logos, button icons, images, trademarks or copyrights are the property of their respective owners. Nothing in this site should be construed as granting any right or license to use any Trademark without the written permission of its owner.</p></td></tr>
					<tr><td><br><p style="font-size: 14px;"><b>3. Services & Conditions</b></p></td></tr>
					<tr><td><p style="font-size: 14px;">3.1. Goautodial shall not be held liable for any delay or failure to provide service(s) at any time. In no event shall Goautodial, its officers, Directors, Employees, Shareholders, Affiliates, Agents or Providers who furnishes services to customer in connection with this agreement or the service be liable for any direct, incident, indirect, special, punitive, exemplary or consequential damages, including but not limited to loss of data, lost of revenue, profits or anticipated profits, or damages arising out of or in connection to the use or inability to use the service. The limitations set forth herein apply to the claimed founded in Breach of Contract, Breach of Warranty, Product Liability and any and all other liability and apply weather or not Goautodial was informed of the likely hood of any particular type of damage.</p></td></tr>
					<tr><td><p style="font-size: 14px;">3.2. Goautodial makes no warranties of any kind, written or implied, to the service in which it provides.</p></td></tr>
					<tr><td><p style="font-size: 14px;">3.3. Goautodial provides prepaid services only. You must keep a positive balance to retain services with Goautodial. You must pay all negative balances immediately. Customer agrees to keep a positive balance in customer's account at all times and agrees to pay the rate in which the customer signed up for any destinations. Customer agrees to pay any and all charges that customer incurs while using Goautodial's service.</p></td></tr>
					<tr><td><p style="font-size: 14px;">3.4. Goautodial's VOIP and Cloud services are not intended for use as a primary telephone source for business or residential users. Goautodial does not provide e911 service.</p></td></tr>
					<tr><td><p style="font-size: 14px;">3.5. All calls placed through Goautodial's VOIP network to US48 destinations are billed at 6 second increments unless otherwise stated.</p></td></tr>
					<tr><td><p style="font-size: 14px;">3.6. Customer agrees to the exclusive jurisdiction of the courts of Pasig City in the Republic of the Philippines for any and all legal matters.</p></td></tr>
					<tr><td><p style="font-size: 14px;">3.7. Violation of any state or federal laws or laws for any other competent jurisdiction may result in immediate account termination and/or disconnection of the offending service.</p></td></tr>
					<tr><td><p style="font-size: 14px;">3.8. Goautodial reserves the right to terminate service at any time with or without notice; especially if Customer is found to be in violation of Goautodial's Terms & Conditions. You agree that Goautodial shall not be liable to you or to any third party for any modification, suspension or discontinuance of service.</p></td></tr>
					<tr><td><p style="font-size: 14px;">3.9. Due to the nature of this industry and high credit card fraud rate, Goautodial reserves the right to request the following documentation for verification purposes; A copy of the credit card used to establish the account along with valid photo identification such as a Passport, Drivers License or other Government issued identification.</p></td>
					<tr><td><p style="font-size: 14px;">3.10 DID and TFN (Toll Free Numbers ) Services and Subscriptions Activation and Deactivation</p></td>
					<tr><td><p style="font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.10.1 DID/TFN monthly service fee shall be automatically deducted or debited from the customer's account balance or credits with or without prior notice; prior to activation of service its subscriptions agreement.</p></td>
					<tr><td><p style="font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.10.2 Auto-debit of monthly payment shall commence once DID/TFN has been activated. </p></td>
					<tr><td><p style="font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.10.3 Failure to pay the agreed DID/TFN monthly services and monthly subscription fee (having one [1] month unpaid bill) shall be subject to DID/TFN deactivation.</p></td>
					<tr><td><p style="font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.10.4 A maximum one 1 month grace period shall be given to the customer to settle his/her account before DID/TFN deactivation and/or deletion.</p></td></tr>
					<tr><td><br><p style="font-size: 14px;"><b>4. Technical Support</b></p></td></tr>
					<tr><td><p style="font-size: 14px;">4.1. Gautodial Technical Support is available Mondays to Fridays 09:00 to 24:00 24/5 EST, all support concerns should be filed at Goautodial's ticketing system <a href="http://support.goautodial.com/" class="faqlinka" style="color: green;">http://support.goautodial.com/</a>.</p></td></tr>
					<tr><td><br><p style="font-size: 14px;"><b>4.2. Monthly Technical Support</b></p></td></tr>
					<tr><td><p style="font-size: 14px;">4.2.1. Goautodial's monthly support subscriptions covers the configurations and troubleshooting for the following issues:</p><td></tr>
					<tr><td><p style="font-size: 14px; margin-left: 20px;">Campaigns – outbound, inbound and blended campaign creation and configurations
    									    Lists/Leads – creation of lists and loading of leads.
    									    Statuses/Dispositions configuration
    									    Call Times configuration
    									    IVR – Basic configuration (one level only)
    									    Basic tutorial for Campaign and Leads management.</p></td></tr>
					<tr><td><br><p style="font-size: 14px;">4.2.2. All advance configurations not listed above will be charged with the regular hourly support rate of $80 per hour.</p></td></tr>
					<tr><td><br><p style="font-size: 14px;">4.2.3. We provide limited support and provide samples configurations for IP Phones/Softphones. It is the end users responsibility to properly configure their workstations and devices for use with Goautodial's services.</p></td></tr>
					<tr><td><br><p style="font-size: 14px;">4.2.4. Leads Management, Campaign Management, Agent Monitoring and Reports Generation are end users responsibility.</p></td></tr>
					<tr><td><br><b><p style="font-size: 14px;">4.3. Emergency Technical Support</p></b></td></tr>
					<tr><td><p style="font-size: 14px;">4.3.1. Emergency technical support outside the regular coverage of Monday to Friday 9:00 to 24:00 EST will be charged $80 per hour.</p></td></tr>
					<tr><td><br><p style="font-size: 14px;">4.3.2. Emergency technical support for Weekend and Holidays will be charged $160 per hour.</p></td></tr>
					<tr><td><br><b><p style="font-size: 14px;">5. Refund Policy</p></b></td></tr>
					<tr><td><p style="font-size: 14px;">5.1. VoIP and Cloud Services: We offer full refunds on remaining pre-paid balance on VoIP and Cloud services upon request for all payments made within 7 days.</p></td></tr>
					<tr><td><br><p style="font-size: 14px;">5.2. Monthly Subscriptions: We do not offer refunds for monthly subscriptions such as Hosted Dialer, DID's or Toll Free numbers</p></td></tr>
					<tr><td><br><p style="font-size: 14px;">5.3. Prepaid Technical Support and Consulting Services: We offer refunds only if no technical support or consulting service and has been rendered.</p></td></tr>
					<tr><td><br><p style="font-size: 14px;">5.4. There will be no refunds for one-time/setup fees</p></td></tr>
					<tr><td><br><b><p style="font-size: 14px;">6. Site Policies, Modification & Severability</p></b></td></tr>
					<tr><td><p style="font-size: 14px;">6.1. We reserve the right to make changes to our site, policies, and these Terms & Conditions at any time. If any of these conditions shall be deemed invalid, void, or for any reason unenforceable, that condition shall be deemed severable and shall not affect the validity and enforceability of any remaining condition.</p></td></tr>
					<tr><td><br><p style="font-size: 14px;"><b>7. General Complaints</b></p></td></tr>
					<tr><td><p style="font-size: 14px;">7.1. Please send reports of activity in violation of these Terms & Conditions to info@goautodial.com. Goautodial will reasonably investigate incidents involving such violations. Goautodial may involve and will cooperate with law enforcement officials if any criminal activity is suspected. Violations may involve criminal and civil liability</p></td></tr>
					<tr><td><br><b><p style="font-size: 14px;">8. Paypal Payments</p></b></td></tr>
					<tr><td><p style="font-size: 14px;">8.1 In case of payment via PayPal.com, customer fully understands that there will be no tangible product shipping to any address. The customer understands that they are purchasing services for which GoAutoDial provides online Call History (CDR) for VOIP/Cloud usage and/or outbound/inbound reports for the Dialer. In case of PayPal disputes the customer agrees to abide by GoAutoDial’s online Call History (CDR) for VOIP/Cloud usage and/or outbound/inbound reports for delivered service totaling the PayPal.com payment.</p></td></tr>
					<tr><td><br><b><p style="font-size: 14px;">9. Limitation of Liabilities</p></b></td></tr>
					<tr><td><p style="font-size: 14px;">9.1. In no event shall GoAutoDial Inc be liable to any party for any direct, indirect, incidental, special, exemplary or consequential damages of any type whatsoever related to or arising from this website or any use of this website, or any site or resource linked to, referenced, or access throught this website, or for the use or downloading of, or access to, any materials, information, products, or services, including withouth limitation, any lost profits, business interruption, lost savings or loss of programs or other data, even if GoAutoDial INc. is expressly advised of the possiblity of such damages.</p></td></tr>
					<tr><td><br><b><p style="font-size: 14px;">10. Call Compliance</p></b></td></tr>
					<tr><td><p style="font-size: 14px;">10.1. GOautodial has full USA, UK and Canada regulatory compliance. Customer fully understands that it is their responsibility to follow these regulations. Failure to do so may result in immediate account suspension and/or disconnection.</p></td></tr>
-->
                                      </table>
                                  </div>
                              </td>
                          </tr>
                          <tr><td colspan="2"><input type="checkbox" value="1" id="agree" name="agree"> <? echo $this->lang->line("go_tc70"); ?> <?=$VARCLOUDCOMPANY;?>'s <? echo $this->lang->line("go_terms_and_condition"); ?></td></tr>
                          <tr>
                              <td colspan="2" style="text-align:center;">
                                                                        <form method="GET">
                                                                        <img src="../sippysignup/captcha.php" id="captcha" />
                                                                        <!-- CHANGE TEXT LINK -->
                                                                        
                                                                        <a id="activator" class="activator" onclick="
                                                                            document.getElementById('captcha').src='../sippysignup/captcha.php?'+Math.random();
                                                                            document.getElementById('captcha-form').focus();"
                                                                            id="change-image"><br><br>
                                                                        <font color="blue" size="1px"><? echo $this->lang->line("go_tc71"); ?></font></a>
                                                                        <br>
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="captcha" id="captcha-form" size="10" autocomplete="off" />
                                                                        <font size="1px"><? echo $this->lang->line("go_tc72"); ?></font> 
                                                                        </form>
                              </td>
                          </tr>
                    </table> 
                    </form>
		</td>
	</tr>
</table>
<hr style="border:#DFDFDF 1px solid;" />
<span id="saveButtons"><span id="cancel" style="white-space: nowrap;"><? echo $this->lang->line("go_back"); ?></span> | <span id="submit" style="white-space: nowrap;"><? echo $this->lang->line("go_submit"); ?></span></span>
