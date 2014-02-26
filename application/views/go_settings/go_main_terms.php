<?php
############################################################################################
####  Name:             go_main_gallery.php                                                ####
####  Type: 		ci views 							####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Jerico James Milo <james@goautodial.com>  ####
####  License:          AGPLv2                                                          ####
############################################################################################

?>
</body>
<head>
  <title>GoAutoDial - Hosted Dialer</title>
  <meta http-equiv="Content-Style-Type" content="text/css" />
	 <link href="<?=base_url()?>css/go_hosted_style.css" media="all" rel="stylesheet" type="text/css" />

<script type="text/javascript" >

	function goto(val) { 
	 
		if(val == 1) {
		 	window.open('http://cloud.goautodial.com')
		} else if(val == 2) {
		 window.open('http://eu.cloud.goautodial.com');
		} else {
			
			}
	
	}

</script>
<script type="text/javascript" src="//asset0.zendesk.com/external/zenbox/v2.4/zenbox.js"></script>
<style type="text/css" media="screen, projection">
  @import url(//asset0.zendesk.com/external/zenbox/v2.4/zenbox.css);
</style>
<script type="text/javascript" src="http://assets.freshdesk.com/widget/freshwidget.js"></script>
<!--<script type="text/javascript">
document.write(unescape("%3Cscript src='" + ((document.location.protocol=="https:")?"https://snapabug.appspot.com":"http://www.snapengage.com") + 
           "/snapabug.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
SnapABug.setButton("https://justgocloud.com/img/images/livechat.png");
SnapABug.setButtonEffect('-4px');
SnapABug.addButton("d04093d4-3146-4fa7-811d-9475a3b6e94c","0","40%");
</script>-->
</head>
  
<!--<body id="body">-->
<body>


<div id="go_menu" class="img-grd-nologo" style="position:fixed;top: 0px;width:100%;padding:0px 0px 0px 0px;z-index:999999;">
    <div style="float:right;width:56.5%;text-align:center;" id="menuitems">
    <table>
    <tr><td></td></tr>
    	<tr>
    		<td><span class="go_menu_top" title="Home"><a href="<?=base_url()?>" class="go_menu_list">Home</a></span></td>
			<td><span class="go_menu_top" title="Pricing"><a href="<?=base_url()?>index.php/pricing/pricingpage" class="go_menu_list">Pricing</a></span></td> 
			<td><span class="go_menu_top" title="FAQ"><a href="<?=base_url()?>index.php/faq/faqpage" class="go_menu_list">FAQ</a></span></td>   		
    		<td><span class="go_menu_top" title="Tutorials"><a href="<?=base_url()?>index.php/tutorials/tutorialspage" class="go_menu_list">Tutorials</a></span></td>
		 <td>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                          
                <span class="go_menu_top" title="Gallery"><a href="https://dal.justgovoip.com/account.php" class="go_menu_list">Login</a></span></td>
		</tr>
	</table>
    </div>
       
			
</div>


<!-- SLIDE PANEL -->

<div class="img-grd-nologo" style="border: 0px red solid;position:fixed;top: 0px;width:100%;padding:0px 0px 0px 0px;z-index:99999;">
<span class="go_menu_top" title="Home"><a href="<?=base_url()?>" class="go_menu_list">&nbsp;</a></span>
</div>


<div id="toppanel" style="position:fixed;top: 0px; z-index:9999; ">
        <div id="panel" style="">
                <div class="content clearfix">

                       <div class="left" style=" border-top: white 0px solid!important;">
                                <h1>Welcome to GoAutoDial Cloud</h1>
                                <h2>Portal</h2>
                                <p class="grey">Please enter your username and password.</p>
                                <h2>Agent</h2>
                                <p class="grey">Please enter your username, password and campaign credentials.</p>
                        </div>
<!--

                        <form name="supervisor" id="supervisor" action="https://dev.goautodial.com/supervisorlog/admin.php" method="post">
                        <?php
                                $sername= $_SERVER['SERVER_NAME'];
                                $hosts = gethostbynamel($sername);
                        ?>

                                <input type="hidden" name="servername" id="servername" value="<?=$hosts[0]?>">
                        <div class="left">
                        <p style="line-height:20%;">
                        <br />
                        <h1>Supervisor Login</h1>
                                        <label class="grey" for="log">Username:</label>
                                        <input class="field" type="text" name="gouser" id="gouser" value="" size="23" />
                                        <label class="grey" for="pwd">Password:</label>
                                        <input class="field" type="password" name="gopass" id="gopass" size="23" />
                                <div class="clear"></div>
                                        <label class="grey" for="supervisor"><input type="button" name="gosup" id="gosup" value="Login" class="" /></label>

                                <div id="loadingsupervisor" style="display:none;">
                                        <label class="grey" for="notice">Loading.. Please wait..</label>
                                </div>
                        </div>
                        </form> -->
                        <form name="portallog" id="portallog" action="https://<?=$_SERVER['SERVER_NAME']?>/portal/userinfo.php" method="post">
                                <input type="hidden" name="done" id="done" value="submit_log">
                                <input type="hidden" name="ui_language" id="ui_language" value="english">
                                <input type="hidden" name="ciauth" id="ciauth" value="ciauth">

                        <div class="left">
                                        <h1>Portal Login</h1>
                                        <label class="grey" for="log">Username:</label>
                                        <input class="field" type="text" name="pr_login" id="pr_login" value="" size="23" />
                                        <label class="grey" for="pwd">Password:</label>
                                        <input class="field" type="password" name="pr_password" id="pr_password" size="23" />
                                        <label class="grey" for="campaign"><input type="button" name="portsub" id="logsub" value="Login" class="" /></label>

                                <div id="loadingportal" style="display:none;">
                                        <label class="grey" for="notice">Loading.. Please wait..</label>
                                </div>
                        </div>
                        </form>

                <form name="vicidial_form" id="vicidial_form" action="https://<?=$_SERVER['SERVER_NAME']?>/agent/agent.php" method="post" class="clearfix">
                                <input type="hidden" name="DB" id="DB" value="" />
                                <input type="hidden" name="JS_browser_height" id="JS_browser_height" value="" />
                                <input type="hidden" name="JS_browser_width" id="JS_browser_width" value="" />
                                <input type="hidden" name="relogin" id="relogin" value="NO" />
                                <input type="hidden" name="phone_login" size="10" id="phone_login" maxlength="20" value="" />
                                <input type="hidden" name="phone_pass" size="10" id="phone_pass" maxlength="20" value="" />
                        <div class="left right">
                                        <h1>Agent Login</h1>
                                        <label class="grey" for="log">Username:</label>
                                        <input class="field" type="text" name="VD_login" id="VD_login" value="" size="23" />
                                        <label class="grey" for="pwd">Password:</label>
                                        <input class="field" type="password" name="VD_pass" id="VD_pass" size="23" />
                                        <label class="grey" for="campaign">Campaign:</label>
                                        <label class="grey" for="campaign"><span id="LogiNCamPaigns"><select size="1" class="" name="VD_campaign" id="VD_campaign" onFocus="login_allowable_campaigns(); document.getElementById('loadingagent').style.display = 'block';">
                                        <option value=""></option></select></span></label>

                                        <label class="grey" for="campaign"><span id="LogiNReseT"><input type="button" value="Refresh Campaign List" onClick="login_allowable_campaigns(); document.getElementById('loadingagent').style.display = 'block';" /></span>
                                        <input type="button" name="agentsub" id="logagent" value="Login" class="" /></label>
                                <div id="loadingagent" style="display:none;">
                                        <label class="grey" for="notice">Loading.. Please wait..</label>
                                </div>
                        </div>
                    </form>
                </div>
        </div>


<!--        <div class="tab" style="margin-right: -1300px;">

                <table width="50%">
                <tr>
                <td>
                <ul class="login">

                        <li class="left">&nbsp;</li>

                        <li id="toggle">
                                <div id="open" class="open">
                                Login
                                </div>
                                <div id="close" style="display: none;" class="close">
                                Close
                                </div>

                        </li>

                        <li class="right">&nbsp;</li>

                </ul>
                </td>
                <tr>
                </table>

        </div>-->

</div>

<!-- END SLIDE PANEL -->



	<div class="min-width">
        <div id="main">
            <div id="header">
					  <!-- <div class="head-row1" id="headerer" style="border: 1px red solid;">-->
                 <div style="float:right;position:fixed;top: 0px;padding-left:1%;z-index:99999999;border:0px blue solid;">
					   <a href="http://<?=$_SERVER['SERVER_NAME']?>" class="sociallinks"><img src="../../img/smalllogo.png" alt="" /></a>
    				  </div>
          			<!-- <br><br><br><br><br><br> -->
          			<!-- <div class="fontfaq" style=" position: absolute; padding-left:80px; z-index: 9999; border: 0px blue solid;" > -->
          			<div class="fontfaq" style=" position: absolute; padding-left:80px; border: 0px blue solid;" >
				<table border="0px" width="800px" style="text-align: justify;">
					<tr>
						<td align="center"><br><br>GoAutoDial VoIP Services Terms and Conditions<br></td>
					</tr>
					<tr>
						<td><p style="font-size: 14px;">
						    This site is owned and operated by Goautodial, Inc. ("we", "us", "our" or "Goautodial"). Goautodial, Inc. provides its services to you ("Customer", "you" or "end user") subject to the following conditions.<br><br>

If you visit or shop at our website or any other affiliated <a href="http://reversephonelookuppages.com/" class="faqlinka" style="color: green;">reverse phone lookup</a> websites, you affirmatively accept the following conditions. Continued use of the site and any of Goautodial's services constitutes the affirmative agreement to these terms and conditions.<br><br>

Goautodial reserves the right to change the terms, conditions and notices under which the Goautodial sites and services are offered, including but not limited to the charges associated with the use of the Goautodial sites and services.
						    </p>
						</td>
					</tr>
					<tr><td><br><p style="font-size: 14px;"><b>1. Electronic Communications</b></p></td></tr>
					<tr><td><p style="font-size: 14px;">1.1. When you visit Goautodial's websites or send Email to us, you are communicating with us electronically. You consent to receive communications from us electronically. We will communicate with you by Email or by posting notices on this site. You agree that all agreements, notices, disclosures and other communications that we provide to you electronically satisfy any legal requirement that such communications be in writing.</p></td></tr>
					<tr><td><br><p style="font-size: 14px;"><b>2. Trademarks and Copyright</b></p></td></tr>
					<tr><td><p style="font-size: 14px;">2.1. All content on this site, such as text, graphics, logos, button icons, images, trademarks or copyrights are the property of their respective owners. Nothing in this site should be construed as granting any right or license to use any Trademark without the written permission of its owner.</p></td></tr>
					<tr><td><br><p style="font-size: 14px;"><b>3. Services & Conditions</b></p></td></tr>
					<tr><td><p style="font-size: 14px;">3.1. Goautodial shall not be held liable for any delay or failure to provide service(s) at any time. In no event shall Goautodial, its officers, Directors, Employees, Shareholders, Affiliates, Agents or Providers who furnishes services to customer in connection with this agreement or the service be liable for any direct, incident, indirect, special, punitive, exemplary or consequential damages, including but not limited to loss of data, lost of revenue, profits or anticipated profits, or damages arising out of or in connection to the use or inability to use the service. The limitations set forth herein apply to the claimed founded in Breach of Contract, Breach of Warranty, Product Liability and any and all other liability and apply weather or not Goautodial was informed of the likely hood of any particular type of damage.</p><br></td></tr>
					<tr><td><p style="font-size: 14px;">3.2. Goautodial makes no warranties of any kind, written or implied, to the service in which it provides.</p><br></td></tr>
					<tr><td><p style="font-size: 14px;">3.3. Goautodial provides prepaid services only. You must keep a positive balance to retain services with Goautodial. You must pay all negative balances immediately. Customer agrees to keep a positive balance in customer's account at all times and agrees to pay the rate in which the customer signed up for any destinations. Customer agrees to pay any and all charges that customer incurs while using Goautodial's service.</p><br></td></tr>
					<tr><td><p style="font-size: 14px;">3.4. Goautodial's VOIP and Cloud services are not intended for use as a primary telephone source for business or residential users. Goautodial does not provide e911 service.</p><br></td></tr>
					<tr><td><p style="font-size: 14px;">3.5. All calls placed through Goautodial's VOIP network to US48 destinations are billed at 6 second increments unless otherwise stated.</p><br></td></tr>
					<tr><td><p style="font-size: 14px;">3.6. Customer agrees to the exclusive jurisdiction of the courts of Pasig City in the Republic of the Philippines for any and all legal matters.</p><br></td></tr>
					<tr><td><p style="font-size: 14px;">3.7. Violation of any state or federal laws or laws for any other competent jurisdiction may result in immediate account termination and/or disconnection of the offending service.</p><br></td></tr>
					<tr><td><p style="font-size: 14px;">3.8. Goautodial reserves the right to terminate service at any time with or without notice; especially if Customer is found to be in violation of Goautodial's Terms & Conditions. You agree that Goautodial shall not be liable to you or to any third party for any modification, suspension or discontinuance of service.</p><br></td></tr>
					<tr><td><p style="font-size: 14px;">3.9. Due to the nature of this industry and high credit card fraud rate, Goautodial reserves the right to request the following documentation for verification purposes; A copy of the credit card used to establish the account along with valid photo identification such as a Passport, Drivers License or other Government issued identification.</p><br></td>
					<tr><td><p style="font-size: 14px;">3.10 DID and TFN (Toll Free Numbers ) Services and Subscriptions Activation and Deactivation</p><br></td>
					<tr><td><p style="font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.10.1 DID/TFN monthly service fee shall be automatically deducted or debited from the customer's account balance or credits with or without prior notice; prior to activation of service its subscriptions agreement.</p><br></td>
					<tr><td><p style="font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.10.2 Auto-debit of monthly payment shall commence once DID/TFN has been activated. </p><br></td>
					<tr><td><p style="font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.10.3 Failure to pay the agreed DID/TFN monthly services and monthly subscription fee (having one [1] month unpaid bill) shall be subject to DID/TFN deactivation.</p><br></td>
					<tr><td><p style="font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.10.4 A maximum one 1 month grace period shall be given to the customer to settle his/her account before DID/TFN deactivation and/or deletion.</p><br></td>
					</tr>
					<tr><td><br><p style="font-size: 14px;"><b>4. Technical Support</b></p></td></tr>
					<tr><td><p style="font-size: 14px;">4.1. Gautodial Technical Support is available Mondays to Fridays 09:00 to 24:00 24/5 EST, all support concerns should be filed at Goautodial's ticketing system <a href="http://support.goautodial.com/" class="faqlinka" style="color: green;">http://support.goautodial.com/</a>.</p></td></tr>
					<tr><td><br><p style="font-size: 14px;"><b>4.2. Monthly Technical Support</b></p></td></tr>
					<tr><td><p style="font-size: 14px;">4.2.1. Goautodial's monthly support subscriptions covers the configurations and troubleshooting for the following issues:</p><td></tr>
					<tr><td><p style="font-size: 14px; margin-left: 20px;">Campaigns – outbound, inbound and blended campaign creation and configurations<br>
    									    Lists/Leads – creation of lists and loading of leads.<br>
    									    Statuses/Dispositions configuration<br>
    									    Call Times configuration<br>
    									    IVR – Basic configuration (one level only)<br>
    									    Basic tutorial for Campaign and Leads management.
</p></td></tr>
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
					<tr><td><p style="font-size: 14px;">7.1. Please send reports of activity in violation of these Terms & Conditions to cloud@goautodial.com. Goautodial will reasonably investigate incidents involving such violations. Goautodial may involve and will cooperate with law enforcement officials if any criminal activity is suspected. Violations may involve criminal and civil liability</p></td></tr>
					<tr><td><br><b><p style="font-size: 14px;">8. Paypal Payments</p></b></td></tr>
					<tr><td><p style="font-size: 14px;">8.1 In case of payment via PayPal.com, customer fully understands that there will be no tangible product shipping to any address. The customer understands that they are purchasing services for which GoAutoDial provides online Call History (CDR) for VOIP/Cloud usage and/or outbound/inbound reports for the Dialer. In case of PayPal disputes the customer agrees to abide by GoAutoDial’s online Call History (CDR) for VOIP/Cloud usage and/or outbound/inbound reports for delivered service totaling the PayPal.com payment.</p></td></tr>
					<tr><td><br><b><p style="font-size: 14px;">9. Limitation of Liabilities</p></b></td></tr>
					<tr><td><p style="font-size: 14px;">9.1. In no event shall GoAutoDial Inc be liable to any party for any direct, indirect, incidental, special, exemplary or consequential damages of any type whatsoever related to or arising from this website or any use of this website, or any site or resource linked to, referenced, or access throught this website, or for the use or downloading of, or access to, any materials, information, products, or services, including withouth limitation, any lost profits, business interruption, lost savings or loss of programs or other data, even if GoAutoDial INc. is expressly advised of the possiblity of such damages.</p></td></tr>
					<tr><td><br><b><p style="font-size: 14px;">10. Call Compliance</p></b></td></tr>
					<tr><td><p style="font-size: 14px;">10.1. GoAutoDial has full USA, UK and Canada regulatory compliance. Customer fully understands that it is their responsibility to follow these regulations. Failure to do so may result in immediate account suspension and/or disconnection.</p></td></tr>
					<!-- <tr><td><br><b><p style="font-size: 14px;">11. Beta Status</p></b></td></tr>
					<tr><td><p style="font-size: 14px;">11.1 The GoAutoDial cloud platform is currently in Beta status. New features for the cloud platform is still being implemented. Customer understands and accepts that they might encounter occasional glitches due to the Beta statu</p></td></tr>
					<tr><td></td></tr> -->
					<tr><td><br><a href="#" onclick="return SnapABug.startLink();">
<img src="https://snapabug.appspot.com/statusImage?w=d04093d4-3146-4fa7-811d-9475a3b6e94c" alt="Contact us" border="0"></a></td></tr>
				</table>
          			<!-- <br><br><br><br> -->
<!--           			<font style="font-size: 35px;font-family:UnDotum;"><a name="faqtop" class="faqlinka"></a><br><b>GoAutoDial Cloud Services Terms and Conditions</b></font><br>
          			&nbsp;&nbsp;&nbsp;<a href="#q1" class="faqlinka"><b>1. How do I get started?</a><br>
          			&nbsp;&nbsp;&nbsp;<a href="#q2" class="faqlinka">2. What mode of payments do you accept?</a><br>
          			&nbsp;&nbsp;&nbsp;<a href="#q3" class="faqlinka">3. What is 6/6 billing?</a><br>
          			&nbsp;&nbsp;&nbsp;<a href="#q4" class="faqlinka">4. Do I get charged for disconnected, busy, invalid calls?</a><br>
          			&nbsp;&nbsp;&nbsp;<a href="#q5" class="faqlinka">5. Do you offer discounts for large volumes?</a><br>
						&nbsp;&nbsp;&nbsp;<a href="#q6" class="faqlinka">6. Which VoIP codec should I use?</a><br>
						&nbsp;&nbsp;&nbsp;<a href="#q7" class="faqlinka">7. How many lines or channels can I have?</a><br>
						&nbsp;&nbsp;&nbsp;<a href="#q8" class="faqlinka">8. Do you allow multiple SIP registrations?</a><br>
						&nbsp;&nbsp;&nbsp;<a href="#q9" class="faqlinka">9. What platforms can be used with your VoIP service?</a></br>
						&nbsp;&nbsp;&nbsp;<a href="#q10" class="faqlinka">10. Do you provide incoming numbers for callbacks (DIDs and TFNs - toll free &nbsp;&nbsp;&nbsp;numbers)?</a></br>
						&nbsp;&nbsp;&nbsp;<a href="#q11" class="faqlinka">11. Do you offer reseller accounts?</a></br>
						&nbsp;&nbsp;&nbsp;<a href="#q12" class="faqlinka">12. I need technical support!</b></a>
						
						<br><br>
						<a name="q1" class="faqlinka"></a><br>
						&nbsp;&nbsp;&nbsp;<b>How do I get started?</b><br>
						<a name="q1" class="faqlinkhover"></a>
						<font style="font-family:UnDotum; font-size: 16px;	color: #4b4b4b;">
						  
          			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;You need to create you account by signing up here: 
          				<a href="https://<?=$_SERVER['SERVER_NAME']?>/signup.php" class="faqlinkhover" >https://<?=$_SERVER['SERVER_NAME']?>/signup.php</a>
          			</font><br>
						&nbsp;&nbsp;
						<font style="font-family:UnDotum; font-size: 13px;	color: #4b4b4b;"><b><a href="#faqtop" class="faqlinka">top</b></a></b></font><br>
          			
          			
          			<a name="q2" class="faqlinka"></a><br>
          			&nbsp;&nbsp;&nbsp;<b>What mode of payments do you accept?</b><br>
          			<a name="q2" class="faqlinkhover" style="padding-top:20px;"></a>
          			<font style="font-family:UnDotum; font-size: 16px;	color: #4b4b4b;">
          				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We accept Paypal and wire transfer.<br> 
          			</font>
						&nbsp;&nbsp;
						<font style="font-family:UnDotum; font-size: 13px;	color: #4b4b4b;"><b><a href="#faqtop" class="faqlinka">top</b></a></b></font><br>

						<a name="q3" class="faqlinka"></a><br>
						&nbsp;&nbsp;&nbsp;<b>What is 6/6 billing?</b><br>
          			<a name="q3" class="faqlinkhover" style="padding-top:20px;"></a>
          			<font style="font-family:UnDotum; font-size: 16px;	color: #4b4b4b;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A "6/6" billing indicates a 6 second minimum with subsequent 6 second increments. For example a 10 second call on 6/6 billing will 
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;be billed as 12 seconds; a 31 second call on 6/6 billing will be billed as 36 seconds and so on.
          				</font>
          			<br>
						&nbsp;&nbsp;
						<font style="font-family:UnDotum; font-size: 13px;	color: #4b4b4b;"><b><a href="#faqtop" class="faqlinka">top</b></a></b></font><br>

						<a name="q4" class="faqlinka"></a><br>
						&nbsp;&nbsp;&nbsp;<b>Do I get charged for disconnected, busy, invalid calls?</b><br>
          			<a name="q4" class="faqlinkhover" style="padding-top:20px;"></a>
          			<font style="font-family:UnDotum; font-size: 16px;	color: #4b4b4b;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Only answered calls are billed.
          				</font>
          			<br>
						&nbsp;&nbsp;
						<font style="font-family:UnDotum; font-size: 13px;	color: #4b4b4b;"><b><a href="#faqtop" class="faqlinka">top</b></a></b></font><br>


						<a name="q5" class="faqlinka"></a>
						<br>
						&nbsp;&nbsp;&nbsp;<b>Do you offer discounts for large volumes?</b><br>
          			<a name="q5" class="faqlinkhover" style="padding-top:20px;"></a>
							<font style="font-family:UnDotum; font-size: 16px;	color: #4b4b4b;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;For volume discounts to the USA (excluding Hawaii and Puerto Rico), Canada and UK (landlines only), we offer the following rates for 
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;the corresponding prepayment amounts:<br>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;US$500-above = US$0.01/minute<br>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;US$4,000-above = US$0.0085/minute
							</font>
          			<br>
						&nbsp;&nbsp;
						<font style="font-family:UnDotum; font-size: 13px;	color: #4b4b4b;"><b><a href="#faqtop" class="faqlinka">top</b></a></b></font><br>
          			
          			<a name="q6" class="faqlinka"></a>
          			<br>
          			&nbsp;&nbsp;&nbsp;<b>Which VoIP codec should I use?</b><br>
						<a name="q6" class="faqlinkhover" style="padding-top:20px;"></a>         			
          				<font style="font-family:UnDotum; font-size: 16px;	color: #4b4b4b;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Recommended Voip codecs are G.729 and G.711.
							</font>
						<br>
						&nbsp;&nbsp;
						<font style="font-family:UnDotum; font-size: 13px;	color: #4b4b4b;"><b><a href="#faqtop" class="faqlinka">top</b></a></b></font><br>
						
						<a name="q7" class="faqlinka"></a>
						<br>
						&nbsp;&nbsp;&nbsp;<b>How many lines or channels can I have?</b><br>
						<a name="q7" class="faqlinkhover" style="padding-top:20px;"></a>
						<font style="font-family:UnDotum; font-size: 16px;	color: #4b4b4b;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Up to 500 channels. However, the default is 20 channels. Please email us if you need more: <a href="mailto:info@justgovoip.com" class="maillink">info@justgovoip.com</a>.
							</font>
						<br>
						&nbsp;&nbsp;
						<font style="font-family:UnDotum; font-size: 13px;	color: #4b4b4b;"><b><a href="#faqtop" class="faqlinka">top</b></a></b></font><br>
						
						<a name="q8" class="faqlinka"></a><br>
						&nbsp;&nbsp;&nbsp;<b>Do you allow multiple SIP registrations?</b><br>				
						<a name="q8" class="faqlinkhover" style="padding-top:20px;"></a>
							<font style="font-family:UnDotum; font-size: 16px;	color: #4b4b4b;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No. We recommend IP based authentication when using multiple servers under 1 account
							</font>						
						<br>
						&nbsp;&nbsp;
						<font style="font-family:UnDotum; font-size: 13px;	color: #4b4b4b;"><b><a href="#faqtop" class="faqlinka">top</b></a></b></font><br>
						
						<a name="q9" class="faqlinka"></a><br>
						&nbsp;&nbsp;&nbsp;<b>What platforms can be used with your VoIP service?</b><br>						
						<a name="q9" class="faqlinkhover" style="padding-top:20px;"></a>												
							<font style="font-family:UnDotum; font-size: 16px;	color: #4b4b4b;">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a. GoAutoDial CE<br> 
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b. Vicidial based systems<br> 
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c. Asterisk based systems<br>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d. Softphones<br>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;e. Generic SIP based systems and devices
							</font><br>
							&nbsp;&nbsp;
							<font style="font-family:UnDotum; font-size: 13px;	color: #4b4b4b;"><b><a href="#faqtop" class="faqlinka">top</b></a></b></font><br>


						<a name="q10" class="faqlinka"></a><br>
                                                &nbsp;&nbsp;&nbsp;<b>Do you provide incoming numbers for callbacks (DIDs and TFNs - toll free numbers)?</b><br>                 
                                                <a name="q10" class="faqlinkhover" style="padding-top:20px;"></a>                                                                                                
                                                        <font style="font-family:UnDotum; font-size: 16px;      color: #4b4b4b;">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Yes (please see <a href="https://<?=$_SERVER['SERVER_NAME']?>/index.php/pricing/pricingpage" class="faqlinkhover" >https://<?=$_SERVER['SERVER_NAME']?>/index.php/pricing/pricingpage</a> for details). 
                                                        </font><br>
                                                        &nbsp;&nbsp;
                                                        <font style="font-family:UnDotum; font-size: 13px;      color: #4b4b4b;"><b><a href="#faqtop" class="faqlinka">top</b></a></b></font><br>
				
						<a name="q11" class="faqlinka"></a><br>
                                                &nbsp;&nbsp;&nbsp;<b>Do you offer reseller accounts?</b><br>              
                                                <a name="q11" class="faqlinkhover" style="padding-top:20px;"></a>                                                            
                                                        <font style="font-family:UnDotum; font-size: 16px;color: #4b4b4b;">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Yes we do. Please email us:  <a href="mailto:sales@justgovoip.com" class="maillink">sales@justgovoip.com</a> for more information. 
                                                        </font><br>
                                                        &nbsp;&nbsp;
                                                        <font style="font-family:UnDotum; font-size: 13px; color: #4b4b4b;"><b><a href="#faqtop" class="faqlinka">top</b></a></b></font><br>


<a name="q12" class="faqlinka"></a><br>
                                                &nbsp;&nbsp;&nbsp;<b>I need technical support!</b><br>              
                                                <a name="q12" class="faqlinkhover" style="padding-top:20px;"></a>                                                            
                                                        <font style="font-family:UnDotum; font-size: 16px;color: #4b4b4b;">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please file a ticket at <a href="https://goautodial.freshdesk.com" class="faqlinkhover" >https://goautodial.freshdesk.com</a>. Our support engineers will be with you as soon as possible 
                                                        </font><br>
                                                        &nbsp;&nbsp;
                                                        <font style="font-family:UnDotum; font-size: 13px; color: #4b4b4b;"><b><a href="#faqtop" class="faqlinka">top</b></a></b></font><br>


                                                <br>
                                                &nbsp;&nbsp;&nbsp;<a href="#" onClick="return SnapABug.startLink();">
                                  <img src="https://snapabug.appspot.com/statusImage?w=d04093d4-3146-4fa7-811d-9475a3b6e94c" alt="Contact us" border="0"></a>
						-->
						</div>
						
						
						
          			<!-- <div style="border: 0px red solid; padding-left:70px; ">
						<img src="<?=base_url()?>img/images/faqheader.png" /> 
						</div>     -->

                    <div class="head-row2" id="custom">
                    	<div class="col1">
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br>
<!-- start contents -->                     	
                            <div class="tabs" style="display:none;">
                              
                            </div>
                        </div>
								<!-- data banners green -->
                        <div id="divspace"></div>
                    </div>
                            </div>

        <div id="cont">
                <div class="cont-inner">
                	
                                            <div id="right-col">
                            <div class="ind">
                                <div class="width">
                                    <div class="block block-block" id="block-block-2">
<!-- data -->

</div>                                </div>
                            </div>
                        </div>
                                        
                                            <div id="left-col">

                            <div class="ind">
                                <div class="width">
                                    <div class="block block-block" id="block-block-3">
	<!-- data -->
	
</div>                                </div>
                            </div>

                        </div>
                                        
                    <div id="cont-col">
                        <div class="ind">
                             <div id="custom">
                                    <div class="ind"><br><br><br><br><br><br><br>
					  <div class="block block-block" id="block-block-4">
<!-- data -->	          
	                                       </div>
                                      </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>         
<!-- footer -->
