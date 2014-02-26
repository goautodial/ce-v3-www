<?php
############################################################################################
####  Name:             login.php                                                     	####
####  Type: 		       ci views 													####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Jerico James Milo <james@goautodial.com>      #### 
####  License:          AGPLv2                                                          ####
############################################################################################
?>
<HTML>
<HEAD>
<link rel="shortcut icon" href="images/favicon.ico">
<title>GoAutoDial Cloud Call Center  - Hosted Dialer Solution</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="https://<?=$_SERVER['SERVER_NAME'];?>/login/css/main.css" rel="stylesheet" type="text/css">
<link href="https://<?=$_SERVER['SERVER_NAME'];?>/login/css/menu.css" rel="stylesheet" type="text/css">
<link href="https://<?=$_SERVER['SERVER_NAME'];?>/login/css/style-def.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="jquery-1.2.6.min.js"></script>
<script type='text/javascript'>
$(document).ready(function(){
	
	$("form").submit(function() {
		
    	var name = $("input#user_name").val();  
    	var pass = $("input#user_pass").val();
    	
    	if(name=="" || name==null) {
    		alert("Please enter your username.");
    		$("#user_name").focus();
    		return false;	
    	}
    	
    	if(pass=="" || pass==null) {
    		alert("Please enter you password.");
    		$("#user_pass").focus();
    		return false;	
    	}
    	
    	
		dataString = $("#form_login").serialize();
		
		$.ajax({
  			type: "POST",  
  			url: "https://<?=$_SERVER['SERVER_NAME'];?>/go_ce/index.php/go_login/validate_credentials",  
  			data:  dataString,
    		
    		success: function(data){
      		var status = data + "!"; 

	    		
	
	   
	   			if (data=="Authenticated"){
	   				$('#messageid').css("color","green");
					$('#messageid').text('Redirecting...').fadeIn('fast');
	    			window.location = "https://<?=$_SERVER['SERVER_NAME'];?>/go_ce/index.php/dashboard";	
	   			} else {
	   				$('#messageid').text(status).fadeIn('fast');
	     			/*$('a.poplight[href=#?w=280]').click();
	     			$('#messageid').text(status).delay(1000).fadeOut('slow');*/
				}
			}

		});     

         return false;

    });
    });
    </script>
</HEAD>
<BODY>
	<div id="bggradient"></div>
		<div id="login-wrapper">
		
			<center>
				<form name="form_login" id="form_login" method="POST" action="http://192.168.100.112/go_ce/index.php">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tbody>
						<tr>
							<td style="font-size:18px;color:#fff;" nowrap="" valign="top">
							<strong>GoAutoDial Cloud</strong><br>
							<span style="font-size:12px;">Please login to your account.</span>
							</td>
							
							<td style="font-size:10px;color:#fff;" nowrap="" valign="top" align="right">
							<br>
							<img src="portal-padlock.png" style="vertical-align: baseline; float:left; margin-left: 180px; margin-top: -5px;">&nbsp;&nbsp;This website is protected by 256-bit SSL security<br>
							To sign-up <a href="https://<?=$_SERVER['SERVER_NAME']?>/go_ce/index.php/go_signup" style="color:#fff;font-weight:bold;"><u>Click here</u></a>.
							</td>
						</tr>
						</tbody>
					</table>
			
			<br><br><br>
		
					<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
						<tbody>
							<tr>
								<td class="loginTableLeft" valign="center" align="right">
								<img src="goautodial_logo.png" width="170">
								</td>
								
								<td vlign="center" bgcolor="#e9e9e9" align="left">
								<img src="portal-arrow.png" height="271" width="43">
								</td>
				
								<td class="loginTableRight" valign="middle">
									<table border="0" cellpadding="0" cellspacing="0">
										<tbody>
										<tr>
											<td>
											Username:<br>
											<input size="40" value="" id="user_name" name="user_name" class="form_input_button" type="text"><br>
											<br>
											Password:<br>
											<input size="40" value="" id="user_pass" name="user_pass" class="form_input_button" type="password"><br>
											<br>
											<input src="portal-login-button.png" title="LOGIN" style="vertical-align: middle;width:130px;height:40px;" type="image">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id='messageid' style="color: red;"></span>
											<div style="color:#BC2222;font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:bold;padding-left:10px;">

											</div><br>
											</td>
										</tr>
										</tbody>
									</table>
										
								</td>
							</tr>
						</tbody>
					</table>
			</form>
		</center>
	</div>
</BODY>
</HTML>
