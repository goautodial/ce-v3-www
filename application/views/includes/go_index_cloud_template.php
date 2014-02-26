<?php
########################################################################################################
####  Name:             	go_index_cloud_template.php         	                            ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

$this->load->view('includes/go_index_cloud_header'); 
$this->load->view($go_main_cloud_content);
$this->load->view('includes/go_index_cloud_footer');

?>
