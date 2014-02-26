<?php
########################################################################################################
####  Name:             	go_noadmin_footer.php            	                    	    ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

echo "<div id='footertext'>\n";
echo "<p>" . $this->lang->line("ua_licence") . "</p>\n";
echo "<p>" . anchor('http://goautodial.com', '&copy; 2010 GoAutoDial, Inc.', array('title' => 'GoAutoDial Inc.- Empowering The Next Generation Contact Centers', 'target' => '_blank')) . " | " . anchor('termsofuse.php', 'Terms of Use', array('title' => 'GoAutoDial - Terms Of Use', 'target' => '_blank')) . "</p>\n";
echo "</div>\n";
echo "</div>\n";
echo "<div id='popup1' class='popup_block'>\n";
echo "<h1>" . $this->lang->line("ua_administrator_login") . "</h1>\n";
echo form_open('go_login/validate_credentials');
echo form_input('user_name', '');
echo form_password('user_pass', '');
echo form_submit('submit', 'Login');
echo "<h3>" . $this->lang->line("ua_remember_me") . "  " . form_checkbox('remember_me', 1, true) . "</h3>\n";
#echo anchor('go_login/signup', 'Create Account');
echo form_close();
echo "</div>\n";
echo "<script type='text/javascript' src=" . base_url() . "/js/jquery-min.js></script>\n";
echo "<script type='text/javascript'>\n";
?>
$(document).ready(function(){

	$('a.poplight[href^=#]').click(function() {
		var popID = $(this).attr('rel'); //Get Popup Name
		var popURL = $(this).attr('href'); //Get Popup href to define size
				
		//Pull Query & Variables from href URL
		var query= popURL.split('?');
		var dim= query[1].split('&');
		var popWidth = dim[0].split('=')[1]; //Gets the first query string value

		//Fade in the Popup and add close button
		$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>');
		
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
		
		return false;
	});
	
	//Close Popups and Fade Layer
	$('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...
	  	$('#fade , .popup_block').fadeOut(function() {
			$('#fade, a.close').remove();  
	}); //fade them both out
		
		return false;
	});	
});
<?
echo "</script>\n"; 
echo "</body>\n";
echo "</html>\n";
?>