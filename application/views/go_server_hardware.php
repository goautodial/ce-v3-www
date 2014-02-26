<?php
########################################################################################################
####  Name:             	go_server_hardware.php   	                    	            ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

$base = base_url();
$phpsysinfolink = $base. "/application/views/phpsysinfo/hardware.php";
?>
<div class="sub">Hardware</div>
<div class="separate"></div>
<?
require("$phpsysinfolink");
?>