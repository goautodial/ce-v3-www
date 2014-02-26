<?php
############################################################################################
####  Name:             go_export_reports.php                                           ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Christopher P. Lomuntad                                         ####
####  License:          AGPLv2                                                          ####
############################################################################################
$base = base_url();
$dateNOW = date('Y-m-d');

switch ($pagetitle)
{
	case "agent_detail":
		$pagetitle = "Agent_Time_Detail_Report";
		break;
	case "agent_pdetail":
		$pagetitle = "Agent_Performance_Detail_Report";
		break;
	case "dispo":
		$pagetitle = "Dial_Statuses_Summary_Report";
		break;
	case "sales_agent":
		$pagetitle = "Sales_Per_Agent_Report";
		break;
	case "sales_tracker":
		$pagetitle = "Sales_Tracker_Report";
		break;
	case "inbound_report":
		$pagetitle = "Inbound_Call_Report";
		break;
	case "call_export_report":
		$pagetitle = "Export_Call_Report";
		break;
	case "dashboard":
		$pagetitle = "Dashboard";
		break;
	default:
		$pagetitle = "Statistical_Report";
}

$file_name = "$pagetitle.$dateNOW.csv";

if( ! empty($file_output))
{
    force_download($file_name, $file_output);
}
?>