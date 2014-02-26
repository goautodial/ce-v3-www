<?php
########################################################################################################
####  Name:             	go_ajax.php                                                         ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Rodolfo Januarius T. Manipol                                        ####
####  Edited by:		GoAutoDial Development Team					    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_ajax extends Controller 
{
	function index()
	{
		#DATABASE REPORTS
		$this->load->model('go_dashboard');		
		$testme = $this->go_dashboard->go_outbound_today();
		$data['outbound_today'] = $testme;
	
		$testme = $this->go_dashboard->go_inbound_today();
		$data['inbound_today'] = $testme;

		$this->load->view('go_dashboard_sales', $data);
		#$this->load->view('go_admin');
	}
}