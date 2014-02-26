<?php
########################################################################################################
####  Name:             	go_reports_template.php   		      	                    ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

$this->load->view('includes/go_dashboard_header');
$this->load->view('includes/go_menu');
$this->load->view($go_main_content);
$this->load->view('includes/go_dashboard_footer');

?>