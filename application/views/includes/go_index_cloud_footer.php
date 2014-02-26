<?php
########################################################################################################
####  Name:             	go_index_cloud_footer.php         	                            ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

// /*echo "<div id='footertext'>\n";
// echo "<p>" . $this->lang->line("ua_licence") . "</p>\n";
// echo "<p>" . anchor('http://goautodial.com', '&copy; 2010 GoAutoDial, Inc.', array('title' => 'GoAutoDial Inc.- Empowering The Next Generation Contact Centers', 'target' => '_blank')) . " | " . anchor('termsofuse.php', 'Terms of Use', array('title' => 'GoAutoDial - Terms Of Use', 'target' => '_blank')) . "</p>\n";
// echo "</div>\n";*/
// echo "</div>\n";
// echo "</div>\n";
// echo "<div id='popup1' class='popup_block'>\n";
// #$this->load->view('go_login_form');
// echo "<h1>" . $this->lang->line("ua_administrator_login") . "</h1>\n";
// echo form_open('', 'id="form_login"','class="form_login"' );
// echo form_input('user_name', '', 'id="user_name"');
// echo form_password('user_pass', '', 'id="user_pass"');
// echo form_submit('submit', 'Login');
// echo "<span id='messageid'></span>" . "<h3>" . $this->lang->line("ua_remember_me") . "  " . form_checkbox('remember_me', 1, true) . "</h3>\n";
// #echo anchor('go_login/signup', 'Create Account');
// echo form_close();
// echo "</div>\n";
// //echo "<script type='text/javascript' src=" . base_url() . "js/jquery-min.js></script>\n";
// echo "<script type='text/javascript'>\n";
// ?>
<!--// $(document).ready(function(){
// 
// 
// //$('#form_login').submit(function() {
//   //alert('Handler for .submit() called.');
//   //return false;
// //});
// 
// 
//   
// 
// 
// 
// 
// 
//                                                 $("form").submit(function() {
//                                                 
//                                                 
//     var name = $("input#user_name").val();  
//     var pass = $("input#user_pass").val();
// 
// 
//        // var dataString = 'user_pass=' + pass + ',' + 'user_name=' + name;
// 
// 
// dataString = $("#form_login").serialize();
// //alert(dataString);
//  
//          $.ajax({
//   type: "POST",  
//   url: "index.php/go_login/validate_credentials",  
//   data:  dataString,
//  
//     success: function(data){
//       var status = data + "!"; 
// 
//     $('#messageid').text(status).fadeIn('slow');
// 
//    
//    if (data=="Authenticated"){
// 
//     $('#messageid').text('Redirecting...').delay(1000).fadeIn('slow');
//     window.location = "index.php/go_site/go_dashboard";		
//    }
//    else
//    {
//      $('a.poplight[href=#?w=280]').click();
//      $('#messageid').text(status).delay(1000).fadeOut('slow');
// 
//    }
//    
//    
//    }
// 
// });     
// 
//          return false;
// 
//     });
//     
//          
// 
// 	$('a.poplight[href^=#]').click(function() {
// 		var popID = $(this).attr('rel'); //Get Popup Name
// 		var popURL = $(this).attr('href'); //Get Popup href to define size
// 				
// 		//Pull Query & Variables from href URL
// 		var query= popURL.split('?');
// 		var dim= query[1].split('&');
// 		var popWidth = dim[0].split('=')[1]; //Gets the first query string value
// 
// 		//Fade in the Popup and add close button
// 	                 
//                         var statusme =  $('#messageid').text();
// 
//                          if (statusme==""){
//                     $('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="<? echo base_url(); ?>img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>');
//         }
//         else
//         {
//         
//                     $('#' + popID).fadeIn().effect("shake", { times:2 }, 100).css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="<? echo base_url(); ?>img/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>');
// 
//         }
//                 
//                 
// 
// 		//Define margin for center alignment (vertical + horizontal) - we add 80 to the height/width to accomodate for the padding + border width defined in the css
// 		var popMargTop = ($('#' + popID).height() + 80) / 2;
// 		var popMargLeft = ($('#' + popID).width() + 80) / 2;
// 		
// 		//Apply Margin to Popup
// 		$('#' + popID).css({ 
// 			'margin-top' : -popMargTop,
// 			'margin-left' : -popMargLeft
// 		});
// 		
// 		//Fade in Background
// 		$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
// 		$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer 
//                 
// 	});
//         
//         
// 	
// 	//Close Popups and Fade Layer
// 	$('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...
// 	  	$('#fade , .popup_block').fadeOut(function() {
// 			$('#fade, a.close').remove();  
// 	
//         
//         
//                        $('#messageid').text("").show();
// 
//         
//         
//         }); //fade them both out
// 			
// 
//                                                         history.back(0);	
//                         });
//                         
//                         
// 
//                         
//                
// });-->
 <?
// echo "</script>\n";
// 
 ?>
 
<!--  <script type="text/javascript"> -->
 
 <?
//                 if ($log_status=='invalid'){
 ?>   
 
<!--  window.onload=function(){$('a.poplight[href=#?w=280]').click()}; -->

 <?
// }
 ?>
<!-- </script> -->
 
 <?
// echo "<div id='footer'><br>\n";
// echo "<p>" . $this->lang->line("ua_licence") . "</p>\n";
// echo "<p>" . anchor('http://goautodial.com', '&copy; 2010 GoAutoDial, Inc.', array('title' => 'GoAutoDial Inc.- Empowering The Next Generation Contact Centers', 'target' => '_blank')) . " | " . anchor('termsofuse.php', 'Terms of Use', array('title' => 'GoAutoDial - Terms Of Use', 'target' => '_blank')) . "</p>\n";
// echo "</div>\n";
// echo "</body>\n";
// echo "</html>\n";
?>

    <div class="content">
  	<!--	<ul>
         <li>
			<img src="img/images/twitter.png" width="20px" heigth="20px"><a href="#">Twitter</a></li>
         <li>
			<a href="#">Flickr</a></li>
         <li>
			<a href="#">Facebook</a></li>

         <li>
			<a href="#">RSS</a></li>
</ul>
-->
                        
			<a href="#" style="color:#ffffff;text-decoration:none;vertical-align:bottom;" title="Twitter"><img src="img/images/twitter.png" width="20px" heigth="20px" style="vertical-align:middle;"/> Twitter</a><br/>
			<a href="#" style="color:#ffffff;text-decoration:none" title="Facebook"><img src="img/images/facebook.png" width="20px" heigth="20px" style="vertical-align:middle"/> Facebook</a><br/>
			<a href="#" style="color:#ffffff;text-decoration:none" title="Linkedin"><img src="img/images/linkedin.png" width="20px" heigth="20px" style="vertical-align:middle"> Linkedin</a><br/>
			<a href="#" style="color:#ffffff;text-decoration:none" title="Tumblr"><img src="img/images/tumblr.png" width="20px" heigth="20px" style="vertical-align:middle"> Tumblr</a><br/>
<p>&nbsp;</p>
    </div>
</div><div class="block block-block" id="block-block-6">
	<div class="title">
    	<h3>Recent Posts</h3>
    </div>
    <div class="content">

		<ul>
<li>
			<a href="#">How to register</a></li>
<li>
			<a href="#">How to get started</a></li>
<li>
			<a href="#">How to log-on</a></li>
<li>
			<a href="#">How to make a call</a></li>

</ul>
<p>&nbsp;</p>
    </div>
</div><div class="block block-block" id="block-block-7">
	<div class="title">
    	<h3>Partners</h3>
    </div>
    <div class="content">
		<ul>
<li>

			<a href="#">Digium</a></li>
<li>
			<a href="#">Lumenvox</a></li>
<li>
			<a href="#">Serverloft</a></li>
<li>
			<a href="#">EasySpeedy</a></li>
</ul>
<p>&nbsp;</p>

    </div>
</div><div class="block block-block" id="block-block-8">
	<div class="title">
    	<h3>Links</h3>
    </div>
    <div class="content">
		<ul>
<li>
			<a href="#">General Guidelines</a></li>

<li>
			<a href="#">Dialer Guidelines</a></li>
<li>
			<a href="#">CallCenter Association</a></li>
<li>
			<a href="#">Dialer Association</a></li>
</ul>
<p>&nbsp;</p>
    </div>
</div>                </div>

            </div>
                <!--<div id="footer">
            <div class="foot">
                                    <span><p><span>GoAutoDial &copy; 2011 <a href="?q=node/60">Privacy policy</a>&nbsp;</span></p>
&nbsp;<!--{%FOOTER_LINK} --></span>
                            </div>
        </div>

        <!--Codded by Leo-->
    </div>
<script language="JavaScript">
$(function() {
	$("div.tabs").tabs(".img/images > div", {
		// enable "cross-fading" effect
		effect: 'fade',
		fadeOutSpeed: "slow",
		// start from the beginning after the last tab
		rotate: true
	// use the slideshow plugin. It accepts its own configuration
	}).slideshow();
});
</script>
<script type="text/javascript">
$(document).ready(function(){


//$('#form_login').submit(function() {
  //alert('Handler for .submit() called.');
  //return false;
//});



   $("#form_login").submit(function() {
                                    
       var name = $("input#user_name").val();  
       var pass = $("input#user_pass").val();

       // var dataString = 'user_pass=' + pass + ',' + 'user_name=' + name;

//        dataString = $("#form_login").serialize();
//          alert(dataString);

       $.ajax({
                 type: "POST",  
                 url: "../../go_ci/index.php/go_login/validate_credentials/"+name+"/"+pass,  
//                  data:  dataString,
                 success: function(data){

                          var status = data + "!"; 
//                           $('#messageid').text(status).fadeIn('slow');
                          if (data=="Authenticated"){
                             // $('#messageid').text('Redirecting...').delay(1000).fadeIn('slow');
                              window.location = "../../go_ci/index.php/go_site/go_dashboard";		
                          }
                          else
                          {
                             $('a.yuimenubaritemlabel[href=#?w=280]').click();
                             $('#messageid').text(status).delay(1000).fadeOut('slow');
                          }
                 }
        });     

         return false;

    });
    
         

    $('a.yuimenubaritemlabel[href^=#]').click(function() {

	var popID = $(this).attr('rel'); //Get Popup Name
	var popURL = $(this).attr('href'); //Get Popup href to define size
				
	//Pull Query & Variables from href URL
	var query= popURL.split('?');
	var dim= query[1].split('&');
	var popWidth = dim[0].split('=')[1]; //Gets the first query string value

	//Fade in the Popup and add close button
	                
        var statusme =  $('#messageid').text();

        if (statusme==""){
               $('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="<?=base_url()?>/img/images/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>');
        }
        else
        {
        
               $('#' + popID).fadeIn().effect("shake", { times:2 }, 100).css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="<? echo base_url(); ?>/img/images/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>');
        }
                
                

	//Define margin for center alignment (vertical + horizontal) - we add 80 to the height/width to accomodate for the padding + border width defined in the css
	var popMargTop = ($('#' + popID).height() + 80) / 2;
	var popMargLeft = ($('#' + popID).width() + 80) / 2;
	
	//Apply Margin to Popup
	$('#' + popID).css({ 
		'margin-top' : -popMargTop,
		'margin-left' : -popMargLeft
	});
		
	//Fade in Background
	$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
	$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer 
                
    });
        
        
	
	//Close Popups and Fade Layer
	$('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...
	  	$('#fade , .popup_block').fadeOut(function() {
			$('#fade, a.close').remove();  
                        $('#messageid').text("").show();
                }); //fade them both out
                history.back(0);	
        });
                        
                        

                        
               
});

</script>

<?php

 echo "<div id='popup1' class='popup_block'>\n";
 #$this->load->view('go_login_form');
 echo "<h1>Login</h1>\n";
 echo '<form id="form_login" method="post">';
 echo '<input type="text" name="user_name" id="user_name">';
 echo '<input type="password" name="user_pass" id="user_pass">';
 echo '<input type="submit"  value="Login">';
 echo "<span id='messageid'></span>";# . "<h3>" . $this->lang->line("ua_remember_me") . "  " . form_checkbox('remember_me', 1, true) . "</h3>\n";
 #echo anchor('go_login/signup', 'Create Account');
 echo "</form>";
 echo "</div>\n";
 echo "<div id='fade'></div>";
?>


</body>
</html>