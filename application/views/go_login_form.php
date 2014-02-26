<?php 
########################################################################################################
####  Name:             	go_login_form.php                     	    		    	    ####
####  Type:             	ci views - administrator					    ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
?>
<div>
	<h1>ADMINISTRATOR LOGIN</h1>
    <?php 
	echo form_open('go_login/validate_credentials');
	echo form_input('user_name', '');
	echo form_password('user_pass', '');
	echo form_submit('submit', 'Login');

?>
		<h3>remember me <?echo form_checkbox('remember_me', '')?></h3>
<?
	#echo anchor('go_login/signup', 'Create Account');
	echo form_close();
	?>



</div>
 



