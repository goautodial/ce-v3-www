<?php
########################################################################################################
####  Name:             	go_server_memory.php   	                        	            ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

$base = base_url();
$phpsysinfolink = $base. "application/views/phpsysinfo/memory.php";
?>
<div class="sub">Memory Usage</div>
<div class="separate"></div>
<?
require("$phpsysinfolink");
?>