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
                $pagetitle = $this->lang->line("go_atdr_download");
                break;
        case "agent_pdetail":
                $pagetitle = $this->lang->line("go_apdr_download");
                break;
        case "dispo":
                $pagetitle = $this->lang->line("go_dssr_download");
                break;
        case "sales_agent":
                $pagetitle = $this->lang->line("go_spar_download");
                break;
        case "sales_tracker":
                $pagetitle = $this->lang->line("go_str_download");
                break;
        case "inbound_report":
                $pagetitle = $this->lang->line("go_icr_download");
                break;
        case "call_export_report":
                $pagetitle = $this->lang->line("go_ecr_download");
                break;
        case "dashboard":
                $pagetitle = $this->lang->line("go_d_download");
                break;
        default:
                $pagetitle = $this->lang->line("go_sr_download");
}

$file_name = "$pagetitle.$dateNOW.csv";

if( ! empty($file_output))
{
    force_download($file_name, $file_output);
}
?>
