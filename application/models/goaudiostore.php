<?php
########################################################################################################
####  Name:             	goaudiostore.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Originated by:	        Rodolfo Januarius T. Manipol                                        ####
####  Written by:      		Jerico James Milo	                                    	    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################


class Goaudiostore extends Model {


     function __construct(){
         parent::Model();
         $this->asteriskDB = $this->load->database('dialerdb',TRUE);
         $this->goDB = $this->load->database('goautodialdb',TRUE);
         //$this->a2billingDB =$this->load->database('billingdb',TRUE) ;
     }
     
     function getuserlevel($uname=null) {

	      $query = $this->asteriskDB->query("SELECT user_id,user,pass,full_name,user_level FROM vicidial_users where user='$uname'");
	      $row = $query->row();
	      return $row->user_level;

     }
     
	 function getaccounts($accntnum=null) {
	 	
	 	  $stmt="SELECT account_num,company FROM a2billing_wizard WHERE account_num='$accntnum' ORDER BY company";
	 
	      $acctno = $this->asteriskDB->query($stmt);
	      $row = $acctno->row(); 
         return $row->account_num;
     }


     function getallingroup($accntnum=null,$userlevel=null) {

			
	     if ($userlevel < 9) {
			$uniqueid_status_prefixSQL = "WHERE uniqueid_status_prefix='$accntnum'";
	     }

        $stmt="SELECT group_id,group_name,queue_priority,active,call_time_id,group_color FROM vicidial_inbound_groups $uniqueid_status_prefixSQL ORDER BY group_id";

   	     $listall = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listall->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              } 
	      return $lists;
     }
     
     function scriptlists($userlevel,$account) {
     	
     	  if($userlevel > 8) {
     	  		$stmt = "SELECT vs.script_id,vs.script_name from vicidial_scripts AS vs, go_scripts AS gs WHERE vs.script_id=gs.script_id order by vs.script_id";
     	  } else {
     	  		$stmt="SELECT vs.script_id,vs.script_name from vicidial_scripts AS vs, go_scripts AS gs WHERE vs.script_id=gs.script_id AND gs.account_num='$account' order by vs.script_id";
     	  }
     	  
     	  $listall = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listall->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              } 
	      return $lists;
     }

     function getcampaign($wherecampaigns=null) {
	      
	      $stmt="SELECT campaign_id,campaign_name from vicidial_campaigns $wherecampaigns order by campaign_id";
	   
	      $campaignall = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($campaignall->result() as $info){
                  $campaigns[$ctr] = $info;
                  $ctr++;
              }

	      return $campaigns;
     }

     function insertingroup($accounts=null, $users=null, $group_id=null, $group_name=null, $group_color=null, $active=null, $web_form_address=null, $voicemail_ext=null, $next_agent_call=null, $fronter_display=null, $script_id=null, $get_call_launch=null) {
			
        $stmt = "SELECT value FROM vicidial_override_ids where id_table='vicidial_inbound_groups' and active='1';";
        $query = $this->asteriskDB->query($stmt);
        $voi_ct = $query->num_rows;
        
        if ($voi_ct > 0) {
        	$row = $query->row(); 
        	$group_id = ($row->value + 1);
        	$stmt = "UPDATE vicidial_override_ids SET value='$group_id' where id_table='vicidial_inbound_groups' and active='1';";
        	$this->asteriskDB->query($stmt);
        }
        
        $stmt="SELECT count(*) as vigcount from vicidial_inbound_groups where group_id='$group_id';";
        $query = $this->asteriskDB->query($stmt);
      	 $row = $query->row(); 
      	 
        if ($row->vigcount > 0) {
        		$message = "<br>GROUP NOT ADDED - there is already a group in the system with this ID\n";
        } else {
                $stmt="SELECT count(*) as vccampid from vicidial_campaigns where campaign_id='$group_id';";
		         $query = $this->asteriskDB->query($stmt);
		         $row = $query->row(); 
          
                if ($row->vccampid > 0) {
                		$message = "<br>GROUP NOT ADDED - there is already a campaign in the system with this ID\n";
                } else {
                        
                        if ( (strlen($group_id) < 2) or (strlen($group_name) < 2)  or (strlen($group_color) < 2) or (strlen($group_id) > 20) or (eregi(' ',$group_id)) or (eregi("\-",$group_id)) or (eregi("\+",$group_id)) ) {
                                
                                $message = "<br>GROUP NOT ADDED - Please go back and look at the data you entered\n <br>Group ID must be between 2 and 20 characters in length and contain no ' -+'.\n <br>Group name and group color must be at least 2 characters in length\n";
                                
							} else {

                                $groupcallt = $group_id.'_ingroup';
                                
                                $stmt="INSERT INTO vicidial_inbound_groups (group_id,group_name,group_color,active,web_form_address,voicemail_ext,next_agent_call,fronter_display,ingroup_script,get_call_launch,web_form_address_two,start_call_url,dispo_call_url,add_lead_url,uniqueid_status_prefix,call_time_id) values('$group_id','$group_name','$group_color','$active','" . $this->asteriskDB->escape($web_form_address) . "','$voicemail_ext','$next_agent_call','$fronter_display','$script_id','$get_call_launch','','','','','$accounts','$groupcallt');";
                                $query = $this->asteriskDB->query($stmt);
									 //die($stmt);
                                $suser = $users;

                                $stmtCT="INSERT INTO vicidial_call_times SET call_time_id='$groupcallt',call_time_name='$group_name',call_time_comments='$group_name',accountno='$accounts',ct_default_start='0', ct_default_stop='2400';";
                                $query = $this->asteriskDB->query($stmtCT);
									 //die($stmt);


                                $stmtA="INSERT INTO vicidial_campaign_stats (campaign_id) values('$group_id');";
                                $query = $this->asteriskDB->query($stmtA);
                                //die($stmt.'<br>'.$stmtCT.'<br>'.$stmtA);

                               /* ### LOG INSERTION Admin Log Table ###
                                $SQL_log = "$stmt|";
                                $SQL_log = ereg_replace(';','',$SQL_log);
                                $SQL_log = addslashes($SQL_log);
                                $stmt="INSERT INTO vicidial_admin_log set event_date='$SQLdate', user='$PHP_AUTH_USER', ip_address='$ip', event_section='INGROUPS', event_type='ADD', record_id='$group_id', event_code='ADMIN ADD INBOUND GROUP', event_sql=\"$SQL_log\", event_notes='';";
                                if ($DB) {echo "|$stmt|\n";}
                                $rslt=mysql_query($stmt, $link);*/
                                }
                        }
                }
                
			return $message;
        //$ADD=3111;

     }

	  function geteditvalues($group_id=null) {
	  		
	  		$stmt = "SELECT group_id,group_name,group_color,active,web_form_address,voicemail_ext,next_agent_call,fronter_display,ingroup_script,get_call_launch,xferconf_a_dtmf,xferconf_a_number,xferconf_b_dtmf,xferconf_b_number,drop_call_seconds,drop_action,drop_exten,call_time_id,after_hours_action,after_hours_message_filename,after_hours_exten,after_hours_voicemail,welcome_message_filename,moh_context,onhold_prompt_filename,prompt_interval,agent_alert_exten,agent_alert_delay,default_xfer_group,queue_priority,drop_inbound_group,ingroup_recording_override,ingroup_rec_filename,afterhours_xfer_group,qc_enabled,qc_statuses,qc_shift_id,qc_get_record_launch,qc_show_recording,qc_web_form_address,qc_script,play_place_in_line,play_estimate_hold_time,hold_time_option,hold_time_option_seconds,hold_time_option_exten,hold_time_option_voicemail,hold_time_option_xfer_group,hold_time_option_callback_filename,hold_time_option_callback_list_id,hold_recall_xfer_group,no_delay_call_route,play_welcome_message,answer_sec_pct_rt_stat_one,answer_sec_pct_rt_stat_two,default_group_alias,no_agent_no_queue,no_agent_action,no_agent_action_value,web_form_address_two,timer_action,timer_action_message,timer_action_seconds,start_call_url,dispo_call_url,xferconf_c_number,xferconf_d_number,xferconf_e_number,ignore_list_script_override,extension_appended_cidname,uniqueid_status_display,uniqueid_status_prefix,hold_time_option_minimum,hold_time_option_press_filename,hold_time_option_callmenu,onhold_prompt_no_block,onhold_prompt_seconds,hold_time_option_no_block,hold_time_option_prompt_seconds,hold_time_second_option,hold_time_third_option,wait_hold_option_priority,wait_time_option,wait_time_second_option,wait_time_third_option,wait_time_option_seconds,wait_time_option_exten,wait_time_option_voicemail,wait_time_option_xfer_group,wait_time_option_callmenu,wait_time_option_callback_filename,wait_time_option_callback_list_id,wait_time_option_press_filename,wait_time_option_no_block,wait_time_option_prompt_seconds,timer_action_destination,calculate_estimated_hold_seconds,add_lead_url,eht_minimum_prompt_filename, eht_minimum_prompt_no_block, eht_minimum_prompt_seconds,on_hook_ring_time from vicidial_inbound_groups where group_id='$group_id';";
	  		//echo $stmt;
			$listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }
              

	      return $lists;
	  		
	  }
	  
	  ##### get callmenu listings for dynamic pulldown
	  function getcallmenu($userlevel=null,$accounts=null) {
	  	 
			if ($userlevel < 9) {	   
		      $addedMenuSQL = "where menu_name LIKE '$accounts %'";
		    }
	      
	      $stmt="SELECT menu_id,menu_name from vicidial_call_menu $addedMenuSQL order by menu_id";
	      $listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }

	      return $lists;
	  }

	  function groupalias() {
	  	
	  		$stmt="SELECT group_alias_id,group_alias_name from groups_alias where active='Y' order by group_alias_id";
	      	$listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }

	      return $lists;
	  }
	  
     function ingrouppulldown($userlevel=null,$accounts=null) {

	   	if ($userlevel < 9) {
				$addedSQL = "and uniqueid_status_prefix='$accounts'";
		}
		
	      $stmt="SELECT group_id,group_name from vicidial_inbound_groups where group_id NOT IN('AGENTDIRECT') $addedSQL order by group_id";
	     	
	      $listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }

	      return $lists;
		     	
     }
     
     function calltimespulldown($userlevel=null,$accounts=null) {

        //if($userlevel > 8) {
        	$stmt="SELECT call_time_id,call_time_name,ct_default_start,ct_default_stop from vicidial_call_times order by call_time_id";
        //} else {
        //  $stmt="SELECT call_time_id,call_time_name,ct_default_start,ct_default_stop from vicidial_call_times where accountno='$accounts' order by call_time_id";	
        //}
          
	      $listval = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($listval->result() as $info){
                  $lists[$ctr] = $info;
                  $ctr++;
              }

	      return $lists;
     	
     }	  
     
     
     function agentranks($group_id=null) {
			
			$userlevel = $this->goingroup->getuserlevel($this->session->userdata('user_name'));
			$account = $this->goingroup->getaccounts($this->session->userdata('user_name')); // get account number

     		if ($userlevel < 9) {
	               $addedSQL = "and user_group='$accounts'";
	       }

          $stmt="SELECT user,full_name,closer_campaigns from vicidial_users where active='Y' $addedSQL order by user;";
          $listval = $this->asteriskDB->query($stmt);
		      
		      $users_to_print = $listval->num_rows;
		      $o = 0;
					     
		      foreach ($listval->result_array() as $row) {
		   		$o++;
		   		
							$camp_name[$ctr] = $row['user'];
							$ARIG_user[$o] =        $row['user'];
                        $ARIG_name[$o] =        $row['full_name'];
                        $ARIG_close[$o] =       $row['closer_campaigns'];
                        $ARIG_check[$o] =       '';
                        if (preg_match("/ $group_id /",$ARIG_close[$o]))
                                {$ARIG_check[$o] = ' CHECKED';}
            
             }
             
             $o=0;
             $ARIG_changenotes='';
             $stmtDlog='';
             
             while ($users_to_print > $o) {
             $o++;
                     $stmtx="SELECT group_rank,calls_today from vicidial_inbound_group_agents where group_id='$group_id' and user='$ARIG_user[$o]';";
						
                     $rsltx = $this->asteriskDB->query($stmtx);
                     $viga_to_print = $rsltx->num_rows;
                     
                     if ($viga_to_print > 0) {
                     			foreach ($rsltx->result_array() as $rowx) {
                              		$ARIG_rank[$o] =        $rowx[0];
                             		$ARIG_calls[$o] =       $rowx[1];

	                             		if($ARIG_calls[$o]==null){
												$ARIG_calls[$o]="0";                             			
	                             		}
	                             		
                             	}
						} else {
                             	$stmtD="INSERT INTO vicidial_inbound_group_agents set calls_today='0',group_rank='0',group_weight='0',user='$ARIG_user[$o]',group_id='$group_id';";
                             	$rslt=$this->asteriskDB->query($stmtD);
                             	$stmtDlog .= "$stmtD|";
                             	$ARIG_changenotes .= "added missing user to viga table $ARIG_user[$o]|";
                             	$ARIG_rank[$o] =        '0';
                             	$ARIG_calls[$o] =       '0';
						}
              }
              
              $users_output .= "<tr><td>USER</td><td>SELECTED</td><td> &nbsp; &nbsp; RANK</td><td> &nbsp; &nbsp; CALLS TODAY</td></tr>\n";	
              $checkbox_count=0;
              $o=0;
              while ($users_to_print > $o) {
              $o++;

                        if (eregi("1$|3$|5$|7$|9$", $o))
                                {$bgcolor='bgcolor="#cccccc"';}
                        else
                                {$bgcolor='bgcolor="#bcbcbc"';}

/*                        $checkbox_field="CHECK_$ARIG_user[$o]$US$group_id";
                        $rank_field="RANK_$ARIG_user[$o]$US$group_id";
*/							$checkbox_field="CHECK_$ARIG_user[$o]";
                        $rank_field="RANK_$ARIG_user[$o]";

                        $checkbox_list .= "|$checkbox_field";
                        $checkbox_count++;

                        $users_output .= "<tr $bgcolor><td><font size=1>$ARIG_user[$o] - $ARIG_name[$o]</td>\n";
                        $users_output .= "<td align=center><input type=checkbox name=\"$checkbox_field\" id=\"$checkbox_field\" value=\"YES\"$ARIG_check[$o]></td>\n";
                        $users_output .= "<td align=center><select size=1 name=$rank_field>\n";
                        $h="9";
                        while ($h>=-9)
                                {
                                $users_output .= "<option value=\"$h\"";
                                if ($h==$ARIG_rank[$o])
                                        {$users_output .= " SELECTED";}
                                $users_output .= ">$h</option>";
                                $h--;
                                }
                        $users_output .= "</select></td>\n";
                        $users_output .= "<td align=center><font size=1>$ARIG_calls[$o]</td></tr>\n";
                	
              }
				$users_output .= "<tr><td align=center colspan=4><input type=button name=submit value=SUBMIT onclick=\"checkdatas('$group_id');\"></td></tr>\n";
				
				return $users_output;     	
     }
	  
	  
	  
	  
	  
	  
	  
	  /* list functions */     
     
     

	   //autogen
     function autogenlist($accnt) {
	      
	      $stmt = "SELECT TRIM(allowed_campaigns) as trimallowedcamp  FROM vicidial_user_groups WHERE user_group='$accnt';";
	      $query = $this->asteriskDB->query($stmt);
	      $row = $query->row_array();	
	      $allowed_campaigns = $row['trimallowedcamp'];
	      $camp_list=str_replace(" ",",",str_replace(" -","",$allowed_campaigns));
         $allowed_campaigns=str_replace(" ","','",str_replace(" -","",$allowed_campaigns));
	

         $stmt="SELECT TRIM(campaign_name) as trimcampname FROM vicidial_campaigns WHERE campaign_id IN('$allowed_campaigns');";
	      $query = $this->asteriskDB->query($stmt);
	      $num = $query->num_rows;		

	      $ctr = 0;
	      foreach ($query->result_array() as $row) {
    			$camp_name[$ctr] = $row['trimcampname'];
				$ctr++;
	      }

	      $stmt="SELECT list_id FROM vicidial_lists WHERE campaign_id IN('$allowed_campaigns') order by list_id desc limit 1;";
	      $query = $this->asteriskDB->query($stmt);
	      $num = $query->num_rows;		
	      $lists = $query->row_array();
	      $listID = $lists['list_id'];
		
         $cnt=str_replace(ltrim(substr($accnt,0,5),"0")."1","",$listID);
    		$cnt=intval("$cnt");
    			if ($cnt < $num) {
        			$cnt = $num;
    			}
	       $camp_name = implode(",",$camp_name);
 			 $camps = "$cnt\n$camp_list\n".$camp_name;

	    return $camps;
     }
     
     function showtable($list_id_override) {
     		$tablecount_to_print=0;
			$fieldscount_to_print=0;
			$fields_to_print=0;
								
	  		$stmt="SHOW TABLES LIKE \"custom_$list_id_override\";";
			$syslook = $this->asteriskDB->query($stmt);
			$fieldscount_to_print = $syslook->num_rows;
			
				if ($fieldscount_to_print > 0) {
						$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$list_id_override';";
						$listcount = $this->asteriskDB->query($stmt);
						$fields_to_print = $listcount->num_rows;
						
						if ($fieldscount_to_print > 0) {
							$stmt="SELECT field_label,field_type from vicidial_lists_fields where list_id='$list_id_override' order by field_rank,field_order,field_label;";
							$labelcount = $this->asteriskDB->query($stmt);
							$fields_to_print = $labelcount->num_rows;
							$fields_list='';
							$o=0;
								foreach($labelcount->result() as $info){
                  			$A_field_label[$o] = $info->field_label;
                  			$A_field_type[$o] = $info->field_type;
                  			$A_field_value[$o] =	'';
                  			$o++;
             				} 

						}
				}
	  } // end function

	  

 	
	  function editvalues($query=null) {
	  		$this->asteriskDB->query($query);
	  }
	  
	  function deletevalues($query=null) {
	  		$this->asteriskDB->query($query);
	  }
	  
	  function resetleads($query=null) {
	  		$this->asteriskDB->query($query);
	  }
	  
	  function getphonecodes() {
	  	
	  		$stmt="select distinct country_code, country from vicidial_phone_codes;";
	  		$pcodes = $this->asteriskDB->query($stmt);
	  		$ctr = 0;
              foreach($pcodes->result() as $info){
                  $phonecodes[$ctr] = $info;
                  $ctr++;
              }

	      return $phonecodes;
	  		
	  }
	  
	  function systemsettingslookup() {

	  		$stmt = "SELECT use_non_latin,admin_web_directory,custom_fields_enabled FROM system_settings;";
	  		$syslook = $this->asteriskDB->query($stmt);
	  		$ctr = 0;
              foreach($syslook->result() as $info){
                  $syslookup[$ctr] = $info;
                  $ctr++;
              }

	      return $syslookup;
	  		
	  }
	  
	  function getlistme() {
			$fields_stmt = "SELECT vendor_lead_code, source_id, list_id, phone_code, phone_number, title, first_name, middle_initial, last_name, address1, address2, address3, city, state, province, postal_code, country_code, gender, date_of_birth, alt_phone, email, security_phrase, comments, rank, owner from vicidial_list limit 1";
			$fields = $this->asteriskDB->query($fields_stmt);
	  		$ctr = 0;
              foreach($fields->result() as $info){
                  $allfields[$ctr] = $info;
                  $ctr++;
              }

	      return $allfields;
	  }	  
	  


     function getinboundgrp($wherecampaigns=null) {
	     
	      $stmt="";
	      $inboundgrp = $this->asteriskDB->query($stmt);
              $ctr = 0;
              foreach($inboundgrp->result() as $info){
                  $inboundgrps[$ctr] = $info;
                  $ctr++;
              }

	      return $inboundgrps;
     }
     
     function getvoicefilestable($user,$search=null) {
     	

     	
     	        $stmt = "SELECT count(*) as countuser from vicidial_users where user='$user' and user_level > 6;";
     	       
        $rslt = $this->asteriskDB->query($stmt);
		 $row = $rslt->row();
        $allowed_user=$row->countuser;
        if ($allowed_user < 1) {
        	 
                $result = 'ERROR';
                $result_reason = "sounds_list USER DOES NOT HAVE PERMISSION TO VIEW SOUNDS LIST";
                echo "$result: $result_reason: |$user|$allowed_user|\n";
                $data = "$allowed_user";
                //api_log($link,$api_logging,$api_script,$user,$agent_user,$function,$value,$result,$result_reason,$source,$data);
                exit;
        } else {

                $server_name = getenv("SERVER_NAME");
                $server_port = getenv("SERVER_PORT");
                if (eregi("443",$server_port)) {$HTTPprotocol = 'https://';}
                  else {$HTTPprotocol = 'http://';}
                $admDIR = "$HTTPprotocol$server_name:$server_port";

                #############################################
                ##### START SYSTEM_SETTINGS LOOKUP #####
                $stmt = "SELECT use_non_latin,sounds_central_control_active,sounds_web_server,sounds_web_directory FROM system_settings;";
                $rslt = $this->asteriskDB->query($stmt);
                $ss_conf_ct = $rslt->num_rows;

                if ($ss_conf_ct > 0) {
                		$row = $rslt->row();
                		$non_latin =                            $row->use_non_latin;
                		$sounds_central_control_active =        $row->sounds_central_control_active;
                		$sounds_web_server =                    $row->sounds_web_server;
                		$sounds_web_directory =                 "sounds";

				  }
                ##### END SETTINGS LOOKUP #####
                ###########################################

                if ($sounds_central_control_active < 1) {
                        $result = 'ERROR';
                        $result_reason = "sounds_list CENTRAL SOUND CONTROL IS NOT ACTIVE";
                        //echo "$result: $result_reason: |$user|$sounds_central_control_active|\n";
                        echo "<center style=\"font-weight:bold\">$result: $result_reason</center>\n";
                        $data = "$sounds_central_control_active";
                        exit;
				  } else {
				  	
                        $i=0;
                        $filename_sort=$MT;
                        #$dirpath = "$WeBServeRRooT/$sounds_web_directory";
                        $dirpath = "/var/lib/asterisk/sounds/";
                        $dh = opendir($dirpath);

                       // if ($DB>0) {echo "DEBUG: sounds_list variables - $dirpath|$stage|$format\n";}
                        while (false !== ($file = readdir($dh)))
                                {
                                	
                                # Do not list subdirectories
                                if ( (!is_dir("$dirpath/$file")) and (preg_match('/\.wav$/', $file)) )
                                        {
                                        if ((!is_null($search) && strlen($search) > 0)) {
					     if (!preg_match("/$search/", $file))
						  continue;
					}
                                        if (file_exists("$dirpath/$file"))
                                                {
                                                //	'sample_prompt','date',30;
                                                $stage = "date";
                                                
                                                $file_names[$i] = $file; 
                                                $file_namesPROMPT[$i] = preg_replace("/\.wav$|\.gsm$/","",$file);
                                                $file_epoch[$i] = filemtime("$dirpath/$file");
                                                $file_dates[$i] = date ("Y-m-d H:i:s", filemtime("$dirpath/$file"));
                                                $file_sizes[$i] = filesize("$dirpath/$file");
                                                $file_sizesPAD[$i] = sprintf("[%020s]\n",filesize("$dirpath/$file"));
                                                if (eregi('date',$stage)) {$file_sort[$i] = $file_epoch[$i] . "----------" . $i;}
                                                if (eregi('name',$stage)) {$file_sort[$i] = $file_names[$i] . "----------" . $i;}
                                                if (eregi('size',$stage)) {$file_sort[$i] = $file_sizesPAD[$i] . "----------" . $i;}

                                                $i++;
                                                }
                                        }
                                }
                        closedir($dh);

                        if (eregi('date',$stage)) {rsort($file_sort);}
                        if (eregi('name',$stage)) {sort($file_sort);}
                        if (eregi('size',$stage)) {rsort($file_sort);}

                        sleep(1);

                        $k=0;
                        $sf=0;
																											  $field_HTML .= "<tbody>";
                        while($k < $i)
                                {

                                $file_split = explode('----------',$file_sort[$k]);
                                $m = $file_split[1];
                                
                                $NOWsize = filesize("$dirpath/$file_names[$m]");
                                //if ($DB>0) {echo "DEBUG: sounds_list variables - $file_sort[$k]|$size|$NOWsize|\n";
								// || $user=="0001"
							$format = "selectframe";
							if ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) {
							    $prefix = "go_".$this->session->userdata('user_group')."_";
							} else {
								$prefix = "go_";
							}
                                if ($file_sizes[$m] == $NOWsize && (preg_match("/^$prefix/",$file_names[$m])))
                                        {
                                        	
                                        //
					if($sounds_web_server == "") {
					     $sound_file = "$base$sounds_web_directory/{$file_names[$m]}";
					} else {
					     $sound_file = "https://$sounds_web_server/$sounds_web_directory/{$file_names[$m]}";
					}
					
                                        if (eregi('tab',$format))
                                                { $field_HTML .= "$k\t$file_names[$m]\t$file_dates[$m]\t$file_sizes[$m]\t$file_epoch[$m]\n";}
                                        if (eregi('link',$format))
                                                { $field_HTML .= "<a href=\"http://$sounds_web_server/$sounds_web_directory/$file_names[$m]\">$file_names[$m]</a><br>\n";}
                                        if (eregi('selectframe',$format))
                                                {

                                                if ($sf < 1)
                                                        {

                                                       // $field_HTML .= "\n";
                                                        //$field_HTML .= "<HTML><head><title>NON-AGENT API</title>\n";


                                                        //$field_HTML .= "<a href=\"javascript:close_file();\"><font size=1 face=\"Arial,Helvetica\">close frame</font></a>\n";
                                                       /* $field_HTML .= "<table id=\"audiolisttable\" class=\"tablesorter\" border=0 cellpadding=1 cellspacing=2 width=100% bgcolor=white ><thead>\n";
                                                        $field_HTML .= "<tr align=\"left\" class=\"nowrap\">";
                                                        $field_HTML .= "<th class=\"thheader\" align=\"center\">No</td>\n";
                                                        $field_HTML .= "<th class=\"thheader\" align=\"center\"><b>FILENAME</b></th>\n";
                                                        $field_HTML .= "<th class=\"thheader\" align=\"center\"><b>DATE</b></th>\n";
                                                        $field_HTML .= "<th class=\"thheader\" align=\"center\"><b>SIZE</b></th>\n";
                                                        $field_HTML .= "<th class=\"\" align=\"center\"><b>PLAY</b></th>\n";
                                                        $field_HTML .= "</tr></thead>\n";*/
                                                        }
                                                $sf++;

                                                $field_HTML .= "<tr align=left class=tr".alternator('1', '2').">";
                                                $field_HTML .= "<td align=\"center\">$sf</td>\n";
                                                $field_HTML .= "<td align=\"left\">".str_replace("-","&#150;",$file_names[$m])."</td>\n";
                                                $field_HTML .= "<td align=\"center\">".str_replace("-","&#150;",$file_dates[$m])."</td>\n";
                                                $field_HTML .= "<td align=\"center\">$file_sizes[$m]</td>\n";
                                                $field_HTML .= "<td align=\"center\"><a href=\"$sound_file\" target=\"_blank\"><img src=$base/img/play.png></a></td>\n";

                                               /*
                                                 $field_HTML .= "<audio id=\"audio1\" src=\"$admDIR/$sounds_web_directory/$file_names[$m]\" controls preload=\"auto\" autobuffer></audio> </td></tr>\n";*/
                                                $mislo .= "$file_dates[$m]";
                                                $filenames .= "$file_names[$m]<br>";
                                                $filesdates .= "$file_dates[$m]<br>";
												$filessizes .= "$file_sizes[$m]<br>";
                                                 //$itemsumitexplode = explode('.', $file_names[$m]);
                                                 //$soundfiles .= "<option value=$itemsumitexplode[0]> $itemsumitexplode[0] </option>";

                                                }
                                        }

                                $k++;
                                }
																															$field_HTML .= "</tr></tbody>\n";
   																							return $field_HTML;
                        
	                       
       			}
		}
		     	
     	
     }


     
     function getcustomlist(){

			$stmt="SELECT list_id,list_name,active,campaign_id from vicidial_lists $whereLOGallowed_campaignsSQL order by list_id;";
			
			$clist = $this->asteriskDB->query($stmt);
			$lists_to_print = $clist->num_rows;
         	$o = 0;
       		foreach($clist->result() as $info){
                  $clistss[$o] = $info;
                  $o++;
              }
			
				foreach($clistss as $clistInfo){
					 $A_list_id = $clistInfo->list_id;
					 $A_list_name = $clistInfo->list_name;
					 $A_active = $clistInfo->active;
					 $A_campaign_id = $clistInfo->campaign_id;
					 $stmt2 = "SELECT count(*) as countfields from vicidial_lists_fields where list_id='$A_list_id'";
					 $custlist = $this->asteriskDB->query($stmt2);
					 $o=0;
					
					foreach($custlist->result() as $info2) {
		            $clistss2[$o] = $info2;
		            $o++;
		          	}	

//		            $custall .="<br>";

              	 foreach($clistss2 as $clistInfo2) {
						$fieldscount = $clistInfo2->countfields;
						$custall .= "<tr align=left class=tr".alternator('1', '2').">";
						$custall .= "<td align=center>$A_list_id</td>";
						$custall .= "<td>$A_list_name</td>";
							
						if($A_active =="Y") {
							$A_active = "<b><font color=green>Active</font></b>";
						}	else {
							$A_active = "<b><font color=red>Inactive</font></b>";
						}	
									
						$custall .= "<td align=center>$A_active</td>";
						$custall .= "<td align=center>$A_campaign_id</td>";
						$custall .= "<td align=center>$fieldscount</td>";
						$custall .= "<td colspan=2 align=center><a href=\"http://".$_SERVER['SERVER_NAME']."/index.php/go_list/editcustomlist/$A_list_id\">[- modify fields -]</a></td></tr>\n";
					  }
				}

              return $custall;
     }
     
	  function custeditview($list_id) {

				$vicidial_list_fields = '|lead_id|vendor_lead_code|source_id|list_id|gmt_offset_now|called_since_last_reset|phone_code|phone_number|title|first_name|middle_initial|last_name|address1|address2|address3|city|state|province|postal_code|country_code|gender|date_of_birth|alt_phone|email|security_phrase|comments|called_count|last_local_call_time|rank|owner|';

			   $custom_records_count=0;
				$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
				$rslt = $this->asteriskDB->query($stmt);
				$tablecount_to_print = $rslt->num_rows;
				if ($tablecount_to_print > 0) 
					{$table_exists =	1;}
				if ($table_exists > 0){
					$stmt="SELECT count(*) as countcustom from custom_$list_id;";
					$rslt = $this->asteriskDB->query($stmt);
					$fieldscount_to_print = $rslt->num_rows;
					if ($fieldscount_to_print > 0) {
						$rowx=$rslt->row();
						$custom_records_count =	$rowx->countcustom;
						}
					}
				
		
			$stmt="SELECT field_id,field_label,field_name,field_description,field_rank,field_help,field_type,field_options,field_size,
				field_max,field_default,field_cost,field_required,multi_position,name_position,field_order from vicidial_lists_fields where list_id='$list_id' order by field_rank,field_order,field_label;";
				
		
			$rslt=$this->asteriskDB->query($stmt);
			$fields_to_print = $rslt->num_rows;
			$fields_list='';
			$o=0;

       		foreach($rslt->result() as $info){
                  $fieldsval[$o] = $info;
                  $o++;
              }
			
				
				
				$o++;
				$rank_select .= "<option>$o</option>";
				$last_rank = $o;

				
	
					### SUMMARY OF FIELDS ###
					$field_HTML .= "<br>";
					$field_HTML .= "<form name=\"formcustomview\" id=\"formcustomview\">";
					
					$field_HTML .= "<b>Summary of fields</b> <a id=\"activator\" class=\"activator\" style=\"margin-left: 38.3%;\" href=\"#\" onClick=\"viewadd('$list_id');\"> [- Create custom field -] </a> &nbsp; <a id=\"activator\" class=\"activator\" href=\"#\" onClick=\"customviews('$list_id');\"> [- View custom fields -] </a><a id=\"activator\" class=\"activator\" href=\"http://".$_SERVER['SERVER_NAME']."/index.php/go_list/go_list#tabs-2\">[- View custom listings -]</a></div>";
					//$field_HTML .= "<table width=\"100%\" class=\"tableedit\"><tr><td></td></tr></table>";
					//$field_HTML .= "<br>";
					
					$field_HTML .= "<center>";
					
					$field_HTML .= "<TABLE class=\"tableedit\" width=100%>\n";
					$field_HTML .=  "<tr><td>&nbsp;&nbsp;</td></tr>\n";
					$field_HTML .=  "<tr><td>&nbsp;&nbsp;</td></tr>\n";
					$field_HTML .=  "<TR>";
					$field_HTML .=  "<TD align=\"center\"><b>Rank</b></TD>";
					$field_HTML .=  "<TD align=\"center\"><b>Label</b></TD>";
					$field_HTML .=  "<TD align=\"center\"><b>Name</b></TD>";
					$field_HTML .=  "<TD align=\"center\"><b>Type</b></TD>";
					$field_HTML .=  "<TD align=\"center\"><b>Action</b></TD>\n";
					$field_HTML .=  "</TR>\n";
									
				
				
					$fieldcount = count($fieldsval);
					if($fieldcount > 0) {
					
					foreach($fieldsval as $fieldsvalues){
					
					$A_field_id =			$fieldsvalues->field_id;
					$A_field_label =		$fieldsvalues->field_label;
					$A_field_name =			$fieldsvalues->field_name;
					$A_field_description =	$fieldsvalues->field_description;
					$A_field_rank =			$fieldsvalues->field_rank;
					$A_field_help =			$fieldsvalues->field_help;
					$A_field_type =			$fieldsvalues->field_type;
					$A_field_options =		$fieldsvalues->field_options;
					$A_field_size =			$fieldsvalues->field_size;
					$A_field_max =			$fieldsvalues->field_max;
					$A_field_default =		$fieldsvalues->field_default;
					$A_field_cost =			$fieldsvalues->field_cost;
					$A_field_required =		$fieldsvalues->field_required;
					$A_multi_position =		$fieldsvalues->multi_position;
					$A_name_position =		$fieldsvalues->name_position;
					$A_field_order =		$fieldsvalues->field_order;
					$rank_select .= "<option>$o</option>";


					$field_HTML .= "<tr class=tr".alternator('1', '2').">";
					$field_HTML .=  "<td align=\"center\">$A_field_rank - $A_field_order &nbsp; &nbsp; </td>";
					$field_HTML .=  "<td align=\"center\">$A_field_label &nbsp; &nbsp; </td>";
					$field_HTML .=  "<td align=\"center\"> $A_field_name &nbsp; &nbsp; </td>";
					$field_HTML .=  "<td align=\"center\"> $A_field_type &nbsp; &nbsp; </td>";
					$field_HTML .=  "<td align=\"center\"><a id=\"activator\" class=\"activator\" href=\"#\"  onClick=\"custompostval('$A_field_id','$list_id');\"> &nbsp;&nbsp;[- modify -]&nbsp;&nbsp;</a><a id=\"activator\" class=\"activator\" href=\"#\" > [- delete -]&nbsp;&nbsp;</a> </td></tr>\n";
			
					$total_cost = ($total_cost + $A_field_cost);
					
					
					}
					}				
					
			

					$field_HTML .=  "</table></form></center><BR><BR>\n";

					return $field_HTML;
					
		}  // end function
		
		
		function customeditfield($fieldid,$listid) {

			$stmt = "SELECT `field_id`, `list_id`, `field_label`, `field_name`, `field_description`, `field_rank`, `field_help`, `field_type`, `field_options`, `field_size`, `field_max`, `field_default`, `field_cost`, `field_required`, `name_position`, `multi_position`, `field_order` FROM `vicidial_lists_fields` WHERE `field_id`='$fieldid' and `list_id` = '$listid' ";					
						
			$fields = $this->asteriskDB->query($stmt);
	  		$ctr = 0;
              foreach($fields->result() as $info){
                  $allfields[$ctr] = $info;
                  $ctr++;
              }

	      return $allfields;
			
		}
		
		function countfields($listid) {
			$stmt = "SELECT * FROM vicidial_lists_fields WHERE list_id='$listid'";
			$rslt = $this->asteriskDB->query($stmt);
			$countfields = $rslt->num_rows;
			
			return $countfields;
			
		}
		
		function customviewmodel($list_id) {
			
			$custom_records_count=0;
				$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
				$rslt = $this->asteriskDB->query($stmt);
				$tablecount_to_print = $rslt->num_rows;
				if ($tablecount_to_print > 0) 
					{$table_exists =	1;}
				if ($table_exists > 0){
					$stmt="SELECT count(*) as countcustom from custom_$list_id;";
					$rslt = $this->asteriskDB->query($stmt);
					$fieldscount_to_print = $rslt->num_rows;
					if ($fieldscount_to_print > 0) {
						$rowx=$rslt->row();
						$custom_records_count =	$rowx->countcustom;
						}
					}
				
		
			$stmt="SELECT field_id,field_label,field_name,field_description,field_rank,field_help,field_type,field_options,field_size,
				field_max,field_default,field_cost,field_required,multi_position,name_position,field_order from vicidial_lists_fields where list_id='$list_id' order by field_rank,field_order,field_label;";
				
		
			$rslt=$this->asteriskDB->query($stmt);
			$fields_to_print = $rslt->num_rows;
			$fields_list='';
			$o=0;

       		foreach($rslt->result() as $info){
                  $fieldsval[$o] = $info;
                  $o++;
              }	
              
              return $fieldsval;
              
		}   
		
     
}

?>
