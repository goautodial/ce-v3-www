<?php
########################################################################################################
####  Name:             	go_accesss.php                           	                    ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:      		Franco Hora                                             	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################


class Go_access extends Model{



     function __construct(){
          parent::Model();
          $this->goautodialDB = $this->load->database('goautodialdb',TRUE);
     }


     function get_useraccess(){
          $this->goautodialDB->order_by("access_type","desc");
          return $this->goautodialDB->get('go_useraccess')->result();
     }


     function user_access($userinfo,&$permissions){
          $userpermissions = $this->get_useraccess();
     }

     function get_access($user){
          $this->goautodialDB->where('user',$user);
          $usersinfo = $this->goautodialDB->get('vicidial_users');
          $this->user_access($usersinfo->result(),$permit);
     }

  
     function get_all_access($id=null){
          if(!is_null($id)){
              $this->goautodialDB->where("id",$id);
          }
          return  $this->goautodialDB->get('user_access_group')->result(); 
     }

     function get_usergroup_permission_id($group_name){
           $this->goautodialDB->select("id");
           $this->goautodialDB->where("user_group",$group_name);
           return $this->goautodialDB->get('user_access_group')->result();
           
     }


     function create_new($post){
          if(!empty($post)){
               $group = $this->get_all_access($post['groups']);
             
               /**
                * set the post raw data to create
               */
               unset($post['groups']);
               $post['allow'] = $group[0]->allow;
               $post['block'] = $group[0]->block;

               $this->goautodialDB->insert('user_access_group',$post); 
          }else{
               die("Error: Empty raw data");
          }
     }


     function user_templates($group){

            $useTemplate = array('user' => '','pass' => '','full_name' => '','user_level' => '1',
                                 'user_group' => '','phone_login' => '','phone_pass' => '',
                                 'delete_users' => '0','delete_user_groups' => '0','delete_lists' => '0',
                                 'delete_campaigns' => '0','delete_ingroups' => '0','delete_remote_agents' => '0',
                                 'load_leads' => '0','campaign_detail' => '0','ast_admin_access' => '0',
                                 'ast_delete_phones' => '0','delete_scripts' => '0','modify_leads' => '0',
                                 'hotkeys_active' => '1','change_agent_campaign' => '0','agent_choose_ingroups' => '1',
                                 'closer_campaigns' => '-','scheduled_callbacks' => '1','agentonly_callbacks' => '1',
                                 'agentcall_manual' => '1','vicidial_recording' => '1','vicidial_transfers' => '1',
                                 'delete_filters' => '0','alter_agent_interface_options' => '0','closer_default_blended' => '0',
                                 'delete_call_times' => '0','modify_call_times' => '0','modify_users' => '0','modify_campaigns' => '0',
                                 'modify_lists' => '0','modify_scripts' => '0','modify_filters' => '0',
                                 'modify_ingroups' => '0','modify_usergroups' => '0','modify_remoteagents' => '0',
                                 'modify_servers' => '0','view_reports' => '0','vicidial_recording_override' => 'DISABLED',
                                 'alter_custdata_override' => 'NOT_ACTIVE','qc_enabled' => '','qc_user_level' => '0',
                                 'qc_pass' => '','qc_finish' => '','qc_commit' => '','add_timeclock_log' => '0',
                                 'modify_timeclock_log' => '0','delete_timeclock_log' => '0',
                                 'alter_custphone_override' => 'NOT_ACTIVE','vdc_agent_api_access' => '0',
                                 'modify_inbound_dids' => '0','delete_inbound_dids' => '0','active' => 'Y',
                                 'alert_enabled' => '0','download_lists' => '0','agent_shift_enforcement_override' => 'DISABLED',
                                 'manager_shift_enforcement_override' => '0','shift_override_flag' => '0',
                                 'export_reports' => '0','delete_from_dnc' => '0','email' => '',
                                 'user_code' => '','territory' => '','allow_alerts' => '0',
                                 'agent_choose_territories' => '','custom_one' => '','custom_two' => '',
                                 'custom_three' => '','custom_four' => '','custom_five' => '',
                                 'voicemail_id' => '','agent_call_log_view_override' => 'DISABLED',
                                 'callcard_admin' => '0','agent_choose_blended' => '1','realtime_block_user_info' => '0',
                                 'custom_fields_modify' => '0','force_change_password' => 'N',
                                 'agent_lead_search_override' => 'NOT_ACTIVE','modify_shifts' => '0',
                                 'modify_phones' => '0','modify_carriers' => '0','modify_labels' => '0',
                                 'modify_statuses' => '0','modify_voicemail' => '0','modify_audiostore' => '0',
                                 'modify_moh' => '0','modify_tts' => '0','preset_contact_search' => 'NOT_ACTIVE',
                                 'modify_contacts' => '0','modify_same_user_level' => '1','admin_hide_lead_data' => '0',
                                 'admin_hide_phone_data' => '0');

        if($group == "ADMIN"){

                $useTemplate["user_level"]='9';$useTemplate["delete_users"]='1';$useTemplate["delete_user_groups"]='1';
                $useTemplate["delete_lists"]='1';$useTemplate["delete_campaigns"]='1';$useTemplate["delete_ingroups"]='1';
                $useTemplate["delete_remote_agents"]='1';$useTemplate["load_leads"]='1';$useTemplate["campaign_detail"]='1';
                $useTemplate["ast_admin_access"]='1';$useTemplate["ast_delete_phones"]='1';$useTemplate["delete_scripts"]='1';
                $useTemplate["modify_leads"]='1';$useTemplate["hotkeys_active"]='1';$useTemplate["change_agent_campaign"]='1';
                $useTemplate["agent_choose_ingroups"]='1';$useTemplate["closer_campaigns"]=" AGENTDIRECT -";
                $useTemplate["scheduled_callbacks"]='1';$useTemplate["agentonly_callbacks"]='1';$useTemplate["agentcall_manual"]='1';
                $useTemplate["vicidial_recording"]='1';$useTemplate["vicidial_transfers"]='1';$useTemplate["delete_filters"]='1';
                $useTemplate["alter_agent_interface_options"]='1';$useTemplate["closer_default_blended"]='1';
                $useTemplate["delete_call_times"]='1';$useTemplate["modify_call_times"]='1';$useTemplate["modify_users"]='1';
                $useTemplate["modify_campaigns"]='1';$useTemplate["modify_lists"]='1';$useTemplate["modify_scripts"]='1';
                $useTemplate["modify_filters"]='1';$useTemplate["modify_ingroups"]='1';$useTemplate["modify_usergroups"]='1';
                $useTemplate["modify_remoteagents"]='1';$useTemplate["modify_servers"]='1';$useTemplate["view_reports"]='1';
                $useTemplate["add_timeclock_log"]='1';$useTemplate["modify_timeclock_log"]='1';$useTemplate["delete_timeclock_log"]='1';
                $useTemplate["vdc_agent_api_access"]='1';$useTemplate["modify_inbound_dids"]='1';$useTemplate["delete_inbound_dids"]='1';
                $useTemplate["download_lists"]='1';$useTemplate["manager_shift_enforcement_override"]='1';
                $useTemplate["export_reports"]='1';$useTemplate["delete_from_dnc"]='1';$useTemplate["callcard_admin"]='1';
                $useTemplate["custom_fields_modify"]='1';
        }


        if($group == "SUPERVISOR"){

                $useTemplate["user_level"]='8';
                $useTemplate["load_leads"]='1';
                $useTemplate["modify_leads"]='1';
                $useTemplate["hotkeys_active"]='0';
                $useTemplate["agent_choose_ingroups"]='1';
                $useTemplate["closer_campaigns"]="";
                $useTemplate["delete_call_times"]='0';
                $useTemplate["modify_campaigns"]='1';
                $useTempalte["modify_lists"]='1';
                $useTemplate["modify_scripts"]='1';
                $useTemplate["view_reports"]='1';
                $useTemplate["download_lists"]='1';
                $useTemplate["export_reports"]='1';
        }

        return $useTemplate;

     }

     function go_check_access_exist($usergroup){
       
              $this->goautodialDB->where('user_group',$usergroup);
              $result = $this->goautodialDB->get('user_access_group');

              if($result->num_rows > 0){
                    return false;
              }
              return true;

     }

}


?>
