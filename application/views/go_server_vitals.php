<?php
########################################################################################################
####  Name:             	go_server_vitals.php   	                        	            ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

$base = base_url();
$phpsysinfolink = $base. "application/views/phpsysinfo/vitals.php";
$vitals_file = $base. "application/views/phpsysinfo/vitals.st";
$vitals_now = file_get_contents($vitals_file);

if ($vitals_now == 1)
{
		$this->load->view('go_sticky_warning');
}

?>
<div class="sub">System Vitals</div>
<div class="separate"></div>
<?
require("$phpsysinfolink");
?>