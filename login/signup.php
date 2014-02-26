<?php
########################################################################################################
####  Name:             	index.php   	                        	    	    	    ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Jerico James Milo	                                            ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$version = file_get_contents('../version.txt');

if ( file_exists("/var/www/html/goautodial.conf") )
        {
        $DBCagc = file("/var/www/html/goautodial.conf");
        foreach ($DBCagc as $DBCline)
                {
                $DBCline = preg_replace("/ |>|\n|\r|\t|\#.*|;.*/","",$DBCline);
                if (ereg("^VARSERVTYPE", $DBCline))
                        {$VARSERVTYPE = $DBCline;   $VARSERVTYPE = preg_replace("/.*=/","",$VARSERVTYPE);}
                }
        }



if($_SERVER['HTTPS']!='on')
{
?>
<script language="javascript">
window.location = "https://"+window.location.host+"/login/"
</script>
<?php
}


$msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
$firefox = strpos($_SERVER["HTTP_USER_AGENT"], 'Firefox') ? true : false;
$safari = strpos($_SERVER["HTTP_USER_AGENT"], 'Safari') ? true : false;
$chrome = strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? true : false;

$myurl = "https://".$_SERVER['SERVER_NAME']."/login/ieview.php";

if(!$firefox && !$chrome) {
	header("Location: $myurl") ;
}






?>

<html>
<head>
<title>GoAutoDial - Empowering the Next Generation Contact Centers</title>
<link rel="shortcut icon" href="../img/gologoico.ico" />
<meta http-equiv="Content-Type"sdf content="text/html; charset=utf-8">

<link rel="stylesheet" type="text/css" href="css/style.css">


<script type="text/javascript">
</script>

<script src="https://<? echo $_SERVER['HTTP_HOST']; ?>/js/jquery.main.js" type="text/javascript"></script>
<script src="https://<? echo $_SERVER['HTTP_HOST']; ?>/js/jquery-validate/jquery.validate.min.js" type="text/javascript"></script>
<!-- <script type="text/javascript" src="jquery-1.2.6.min.js"></script> -->

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
  			url: "https://<?=$_SERVER['SERVER_NAME'];?>/index.php/go_login/validate_credentials",  
  			data:  dataString,
    		
    		success: function(data){
      		
	   			if (data=="Authenticated"){
					$('#messageid').css("color","green");
					$('#messageid').text('Redirecting...').fadeIn('fast');
					window.location = "https://<?=$_SERVER['SERVER_NAME'];?>/dashboard";
	   			} else {
	   				$('#messageid').text(data).fadeIn('fast').fadeOut(3000);
				}
			}

		});     

            return false;

        });

    $("#signup").click(function(){

        $('#signupOverlay').fadeIn('fast');
        $('#signupBox').css({'width': '770px','margin-left': 'auto', 'margin-right': 'auto', 'padding-bottom': '10px'});
        $('#signupBox').animate({
        top: "70px",
        left: "14%",
        right: "14%"
        }, 500);
        $('#signupoverlayContent').fadeOut("slow").load('../index.php/go_carriers_ce/sippywelcome').fadeIn("slow");

    });

        $('#signupClosebox').click(function()
        {
                $('#signupBox').animate({'top':'-2550px'},500);
                $('#signupOverlay').fadeOut('slow');
                $("#box").empty();
        });

    });
 


</script>
<style>
#signup{cursor:pointer;}
#statusOverlay,#fileOverlay,#hopperOverlay{
        background:transparent url(<?=$_SERVER['SERVER_NAME']?>/img/overlay.png) repeat top left;
        position:fixed;
        top:0px;
        bottom:0px;
        left:0px;
        right:0px;
        z-index:102;
}

#signupOverlay{
        background:transparent url(https://<?php echo $_SERVER['SERVER_NAME'] ?>/img/overlay.png) repeat top left;
        position:fixed;
        top:0px;
        bottom:0px;
        left:0px;
        right:0px;
        z-index:102;
}


#box{
        position:absolute;
        top:-2550px;
        left:14%;
        right:14%;
        background-color: #FFF;
        color:#7F7F7F;
        padding:20px;

        -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
        z-index:101;
}

#signupBox{
        position:absolute;
        top:-2550px;
        left:30%;
        right:30%;
        background-color: #FFF;
        color:#7F7F7F;
        padding:20px;

        -webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
        z-index:103;
}

#signupClosebox{
        float:right;
        width:26px;
        height:26px;
        background:transparent url(https://<?php echo $_SERVER['SERVER_NAME']; ?>/img/cancel.png) repeat top left;
        margin-top:-30px;
        margin-right:-30px;
        cursor:pointer;
}
</style>
</head>
<body class="bodybgback">

	<div class="bodyheader" style="line-height:16px;">
		 <span style="margin-left: 1%;">
			<a href="https://<?=$_SERVER['SERVER_NAME'];?>/" title="Home">
				<img src="smalllogo.png" border="0" style="padding-top:2px">
			</a>
		 </span>
		 <span style="margin-right: 1%; font-size: 13px; margin-top: 8px; float: right;">
				<a href="http://<?=$_SERVER['SERVER_NAME']?>/agent/" class="go_menu_list" style="color:#FFFFFF;text-decoration:none;"><b>Agent Login</b></a>
		 </span>
	</div>
<br><br><br><br>
<center>
<div id="signupOverlay" style="display:none;"></div>
<div id="signupBox">
<a id="signupClosebox" class="toolTip" title="CLOSE"></a>
<div id="signupoverlayContent"></div>
</div>

<form name="form_login" id="form_login" method="POST" action="http://<?=$_SERVER['SERVER_NAME']?>/index.php" class="curvebox" style="background-color: white;">
	<table align="center" border="0" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
        		<td valign="middle">
					<center>
						<table width="400px" cellpadding="0" cellspacing="0">
							<tr>
								<td align="left"><font size="1"> &nbsp; </font></td>
							</tr>
							<tr>
								<td align="center" style="font-family: Arial, Helvetica, sans-serif; font-size: 20px; padding-right:0px;">
									<img src="goautodial_logo.png" width="150px" >
								</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr>
								<td align="left" style="font-family: Arial, Helvetica, sans-serif; padding-left:35px;">
									Username:  
								</td>
							</tr>
							<tr>
								<td align="center">
									<input class="form_input_button" type="text" title="Your username" name="user_name" id="user_name" size="30">
								</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr>
								<td align="left" style="font-family: Verdana, Helvetica, sans-serif; padding-left:35px;">Password:  
								</td>
							</tr>
							<tr>
								<td align="center">
									<input class="form_input_button" type="password" title="Your password" name="user_pass" id="user_pass" size="30">
								</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
							<tr> 
								<td align="center">
					<input src="portal-login-button.png" title="LOGIN" style="vertical-align: middle;width:130px;height:40px;" type="image">
								</td>
							</tr>
							<tr><td style="font-size:8px;">&nbsp;</td></tr>
							<tr>
								<td align="center">&nbsp;<span id='messageid' style="color: red;"></span>&nbsp;</td>							
							</tr>
							</table>
					</center>
				</td>
			</tr>
    	</tbody>
    </table><br><br>
 
</form>
<div class="ce">
<?php
if($VARSERVTYPE=="public") {
?>
<font size="4">Sign-up <a id="signup"><u><font size="4" color="#428e00">here</font></u></a> for free 120 minutes</font>
<?php
}
?>
<br>
<a class="ce" href='../credits'>GoAdmin &reg; <?echo $version;?></a>

</div>
</center>

<div class='footer'>
   <table width="100%" >
				<tr><td align="center" style="color: #6a6363; font-size: 10px;">GoAutoDial CE 3.0 comes with no guarantees or warranties of any sorts, either written or implied. The Distribution is released as <a href='../gplv2'>GPLv2</a>. Individual packages in the distribution come with their own licences.</td></tr>
				<tr><td align="center" style="color: #6a6363; font-size: 10px;"><a href='http://goautodial.org'>GoAutoDial CE</a> &reg;, <a href='http://goautodial.com'>GoAutoDial</a> &reg;, <a href='http://justgocloud.com'>JustGoCloud</a> &reg; and <a href='http://justgovoip.com'>JustGoVoIP</a> &reg; are registered trademarks of GoAutoDial Inc.</td></tr>
            			<tr><td align="center" style="color: #6a6363; font-size: 10px;">&copy; GoAutoDial Inc. 2010-2013 All Rights Reserved.</td></tr>
    </table>
   
</div>
</body>


</html>
