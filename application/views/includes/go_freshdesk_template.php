<?php
############################################################################################
####  Name:             go_freshdesk_template.php                                         ####
####  Type: 		    ci views                            							####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Januarius Manipol <januarius@goautodial.com>  ####
####  License:          AGPLv2                                                          ####
############################################################################################

$this->load->view('includes/go_dashboard_header');
$this->load->view('includes/go_menu');
$this->load->view('go_support/'.$go_main_content);
$this->load->view('includes/go_dashboard_footer');

?>
