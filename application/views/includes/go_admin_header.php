<?php
########################################################################################################
####  Name:             	go_admin_header.php   	                        	    	    ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

$base = base_url();

if ( empty($folded) )
{	
	$folded='';
	$foldlink = '../go_site/fold_me';
}

$uname = $this->session->userdata('user_name');
//$is_logged_in = $this->session->userdata('is_logged_in');


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>GoAdmin &reg; 3.0</title>
<link rel="shortcut icon" href="<?php echo $base; ?>img/gologoico.ico" />


<?
require_once($cssloader);
require_once($jsheaderloader);
?>
</head>
<body style="cursor: auto;" class="wp-admin no-js js <? echo $folded; ?> index-php">

<?
require_once($jsbodyloader);
?>
<div id="wpwrap">
<div id="wpcontent">
<div id="wphead">		
<div id="header-logo">
<a href="http://demo002.gopredictive.com/" title="Visit Site"></a>
</div>

<h1 id="site-heading" class="long-title">
	
</h1>

<div id="wphead-info">
<div id="user_info">
<p>Hello, <a href="" title="Edit your profile"><? echo $uname ?></a> | <a href="../go_site/logout" title="Logout">Logout</a></p>
</div>


<div id="clockbox"></div>
<!--
<div id="favorite-actions"><div id="favorite-first"><a href="">Quick Links</a></div><div id="favorite-toggle"><br></div>
<div class="slideUp" style="width: 126px;" id="favorite-inside">
<div class="favorite-action"><a href="">Reports</a></div>
<div class="favorite-action"><a href="">GO Analytics</a></div>
<div class="favorite-action"><a href="">Campaigns</a></div>
<div class="favorite-action"><a href="">Lists</a></div>
<div class="favorite-action"><a href="">Users</a></div>
<div class="favorite-action"><a href="">vTigerCRM</a></div>
<div class="favorite-action"><a href="">phpMyAdmin</a></div>
<div class="favorite-action"><a href="">Support</a></div>
<div class="favorite-action"><a href="">VOIP Store</a></div>
</div></div>
-->
</div>
</div>