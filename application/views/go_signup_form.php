<?php
########################################################################################################
####  Name:             	go_signup_form.php                     	                            ####
####  Type:             	ci views - administrator/agent                                      ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$this->load->view('includes/go_header'); 
?>

<h1>Create an Account!</h1>
<fieldset>
<legend>Personal Information</legend>
<?php
   
echo form_open('go_login/create_member');

echo form_input('user_fname', set_value('user_fname', 'First Name'));
echo form_input('user_lname', set_value('user_lname', 'Last Name'));
echo form_input('user_email', set_value('user_email', 'Email Address'));
?>
</fieldset>

<fieldset>
<legend>Login Info</legend>
<?php
echo form_input('user_name', set_value('user_name', 'Username'));
echo form_input('user_pass', set_value('user_pass', 'Password'));
echo form_input('user_pass2', 'Password Confirm');

echo form_submit('submit', 'Create Acccount');
?>

<?php echo validation_errors('<p class="error">'); ?>
</fieldset>

<?php $this->load->view('includes/go_tut_info'); ?>

<?php $this->load->view('includes/go_footer'); ?>
