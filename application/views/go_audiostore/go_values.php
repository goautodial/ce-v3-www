<?php
############################################################################################
####  Name:             go_values.php                                                   ####
####  Type: 		       ci model 																  ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Jerico James Milo <james@goautodial.com>      ####
####  License:          AGPLv2                                                          ####
############################################################################################

	$this->asteriskDB = $this->load->database('dialerdb',TRUE);
	//$this->a2billingDB =$this->load->database('billingdb',TRUE) ;
	$this->load->model('goingroup');



	//post variables
	$vicidial_list_fields = '|lead_id|vendor_lead_code|source_id|list_id|gmt_offset_now|called_since_last_r eset|phone_code|phone_number|title|first_name|middle_initial|last_name|address1|address2|address3|city|state|province|postal_code|country_code|gender|date_of_birth|alt_phone|email|security_phrase|comments|called_count|last_local_call_time|rank|owner|';
	$items = $this->input->post('items');
	$itemsumit = $this->input->post('itemsumit');
	$action = $this->input->post('action');
	$list_id = $this->input->post('listid');
	$field_label = $this->input->post('field_label');
	$field_rank = $this->input->post('field_rank');
	$field_order = $this->input->post('field_order');
	$field_name = $this->input->post('field_name');
	$name_position = $this->input->post('name_position');
	$field_description = $this->input->post('field_description');
	$field_type = $this->input->post('field_type');
	$field_options = $this->input->post('field_options');
	$multi_position = $this->input->post('multi_position');
	$field_size = $this->input->post('field_size');
	$field_max = $this->input->post('field_max');
	$field_default = $this->input->post('field_default');
	$field_required = $this->input->post('field_required');
	$field_id = $this->input->post('field_id');

/* start lists */
	if($action=="editlistfinal") {
		
		$itemsumitexplode = explode('&', $this->input->post('itemsumit'));
		$listid_data = split('=', $itemsumitexplode[2]);
		$listid_data = htmlspecialchars(urldecode($listid_data[1]));
		
		$webformdata = split('=', $itemsumitexplode[12]);
		$webformdata = htmlspecialchars(urldecode($webformdata[1]));
		$webformdata_field = split('=', $itemsumitexplode[12]);
		$webformdata_field = htmlspecialchars(urldecode($webformdata_field[0]));
		$reset_field = split('=', $itemsumitexplode[7]);
		$reset_field = htmlspecialchars(urldecode($reset_field[1]));
		$campaign_field = split('=', $itemsumitexplode[5]);
		$campaign_field = htmlspecialchars(urldecode($campaign_field[1]));
		
		
		if(is_null($sucesshint)) {
	 		
	 		for( $i = 3; $i < count( $itemsumitexplode ); $i++ ) {
	 			
	 			$itemsumitsplit = split('=', $itemsumitexplode[$i]);
	 			$showval = htmlspecialchars(urldecode($itemsumitsplit[0]));
				$datavals = htmlspecialchars(urldecode($itemsumitsplit[1]));
				$showval = str_replace(';', '',$showval);
				$finalvalues = "".$showval."='".$datavals."' "."";
				$squote = "'\". ";
				$equote = " .\"' ";
				
				if($showval=="web_form_address") {
					
					if(!is_null($webformdata)){
						$webset = "web_form_address = ". $this->asteriskDB->escape($webformdata);
						$finalvaluesweb = "".$webset;
						$querywebset = "UPDATE vicidial_inbound_groups set ".$finalvaluesweb." WHERE group_id='".$listid_data."';";
						$this->goingroup->editvalues($querywebset);
					}
					
				} else {
					   $query = "UPDATE vicidial_inbound_groups set ".$finalvalues." WHERE group_id='".$listid_data."';";
					   $this->goingroup->editvalues($query);	
				}

			} //end for
			
	/*			$SQLdate = date("Y-m-d H:i:s");
				$querydate="UPDATE vicidial_lists SET list_changedate='$SQLdate' WHERE list_id='$listid_data';";
				$this->golist->editvalues($querydate);
			*/
				$sucesshint = "SUCCESS";
		} 
			
			if($sucesshint=="FAILED") {
				echo "FAILED: Group I.D. $listid_data modified";
				$sucesshint = "FAILED";
			} else {
				echo "SUCCESS: Group I.D. $listid_data modified";
				$sucesshint = "";
			}
	}
	
	if($action=="deletelist") {
			$listiddel = $this->input->post('listid_delete');
			
			if(is_null($sucesshint)) {
				$querydelete = "DELETE FROM vicidial_lists WHERE list_id='$listiddel'; ";
				$this->golist->deletevalues($querydelete);
				$sucesshint = "SUCCESS";
			}
			
			if($sucesshint=="FAILED") {
				echo "FAILED: List I.D. $listid_data deleted";
				$sucesshint = "FAILED";
			} else {
				echo "SUCCESS: List I.D. $listid_data deleted";
				$sucesshint = "";
			}
	}
	
/* end lists */
	
	if($action=="editlist") {

			$a = explode('&', $this->input->post('items'));
			$i = 0;
			$b = split('=', $a[0]);
			
			$showval = htmlspecialchars(urldecode($b[0]));
			$dataval = htmlspecialchars(urldecode($b[1]));
			
			$ingroupvalues = $this->goingroup->geteditvalues($dataval);
		
		   $countme = count($ingroupvalues);
			   
				if($countme > 0) { 
				
					foreach($ingroupvalues as $ingroupvaluesInfo) {
						
								  $group_id = $ingroupvaluesInfo->group_id;
								  $group_name =                           $ingroupvaluesInfo->group_name;
				                $group_color =                          $ingroupvaluesInfo->group_color;
				                $active =                                       $ingroupvaluesInfo->active;
				                $web_form_address =                     stripslashes($ingroupvaluesInfo->web_form_address);
				                $voicemail_ext =                        $ingroupvaluesInfo->voicemail_ext;
				                $next_agent_call =                      $ingroupvaluesInfo->next_agent_call;
				                $fronter_display =                      $ingroupvaluesInfo->fronter_display;
				                $script_id =                            $ingroupvaluesInfo->ingroup_script;
				                $get_call_launch =                      $ingroupvaluesInfo->get_call_launch;
				                $xferconf_a_dtmf =                      $ingroupvaluesInfo->xferconf_a_dtmf;
				                $xferconf_a_number =            $ingroupvaluesInfo->xferconf_a_number;
				                $xferconf_b_dtmf =                      $ingroupvaluesInfo->xferconf_b_dtmf;
				                $xferconf_b_number =            $ingroupvaluesInfo->xferconf_b_number;
				                $drop_call_seconds =            $ingroupvaluesInfo->drop_call_seconds;
				                $drop_action =                          $ingroupvaluesInfo->drop_action;
				                $drop_exten =                           $ingroupvaluesInfo->drop_exten;
				                $call_time_id =                         $ingroupvaluesInfo->call_time_id;
				                $after_hours_action =           $ingroupvaluesInfo->after_hours_action;
				                $after_hours_message_filename = $ingroupvaluesInfo->after_hours_message_filename;
				                $after_hours_exten =            $ingroupvaluesInfo->after_hours_exten;
				                $after_hours_voicemail =        $ingroupvaluesInfo->after_hours_voicemail;
				                $welcome_message_filename =     $ingroupvaluesInfo->welcome_message_filename;
				                $moh_context =                          $ingroupvaluesInfo->moh_context;
				                $onhold_prompt_filename =       $ingroupvaluesInfo->onhold_prompt_filename;
				                $prompt_interval =                      $ingroupvaluesInfo->prompt_interval;
				                $agent_alert_exten =            $ingroupvaluesInfo->agent_alert_exten;
				                $agent_alert_delay =            $ingroupvaluesInfo->agent_alert_delay;
				                $default_xfer_group =           $ingroupvaluesInfo->default_xfer_group;
				                $queue_priority =                       $ingroupvaluesInfo->queue_priority;
				                $drop_inbound_group =           $ingroupvaluesInfo->drop_inbound_group;
				                $ingroup_recording_override = $ingroupvaluesInfo->ingroup_recording_override;
				                $ingroup_rec_filename =         $ingroupvaluesInfo->ingroup_rec_filename;
				                $afterhours_xfer_group =        $ingroupvaluesInfo->afterhours_xfer_group;
				                $qc_enabled =                           $ingroupvaluesInfo->qc_enabled;
				                $qc_statuses =                          $ingroupvaluesInfo->qc_statuses;
				                $qc_shift_id =                          $ingroupvaluesInfo->qc_shift_id;
				                $qc_get_record_launch =         $ingroupvaluesInfo->qc_get_record_launch;
				                $qc_show_recording =            $ingroupvaluesInfo->qc_show_recording;
				                $qc_web_form_address =          stripslashes($ingroupvaluesInfo->qc_web_form_address);
				                $qc_script =                            $ingroupvaluesInfo->qc_script;
				                $play_place_in_line =           $ingroupvaluesInfo->play_place_in_line;
				                $play_estimate_hold_time =      $ingroupvaluesInfo->play_estimate_hold_time;
				                $hold_time_option =             $ingroupvaluesInfo->hold_time_option;
				                $hold_time_option_seconds = $ingroupvaluesInfo->hold_time_option_seconds;
				                $hold_time_option_exten =       $ingroupvaluesInfo->hold_time_option_exten;
				                $hold_time_option_voicemail =   $ingroupvaluesInfo->hold_time_option_voicemail;
				                $hold_time_option_xfer_group =  $ingroupvaluesInfo->hold_time_option_xfer_group;
				                $hold_time_option_callback_filename =   $ingroupvaluesInfo->hold_time_option_callback_filename;
				                $hold_time_option_callback_list_id =    $ingroupvaluesInfo->hold_time_option_callback_list_id;
				                $hold_recall_xfer_group =       $ingroupvaluesInfo->hold_recall_xfer_group;
				                $no_delay_call_route =          $ingroupvaluesInfo->no_delay_call_route;
				                $play_welcome_message =         $ingroupvaluesInfo->play_welcome_message;
				                $answer_sec_pct_rt_stat_one =   $ingroupvaluesInfo->answer_sec_pct_rt_stat_one;
				                $answer_sec_pct_rt_stat_two =   $ingroupvaluesInfo->answer_sec_pct_rt_stat_two;
				                $default_group_alias =          $ingroupvaluesInfo->default_group_alias;
				                $no_agent_no_queue =            $ingroupvaluesInfo->no_agent_no_queue;
				                $no_agent_action =                      $ingroupvaluesInfo->no_agent_action;
				                $no_agent_action_value =        $ingroupvaluesInfo->no_agent_action_value;
				                $web_form_address_two =         stripslashes($ingroupvaluesInfo->web_form_address_two);
				                $timer_action =                         $ingroupvaluesInfo->timer_action;
								  $timer_action_message =         $ingroupvaluesInfo->timer_action_message;
				                $timer_action_seconds =         $ingroupvaluesInfo->timer_action_seconds;
				                $start_call_url =                       $ingroupvaluesInfo->start_call_url;
				                $dispo_call_url =                       $ingroupvaluesInfo->dispo_call_url;
				                $xferconf_c_number =            $ingroupvaluesInfo->xferconf_c_number;
				                $xferconf_d_number =            $ingroupvaluesInfo->xferconf_d_number;
				                $xferconf_e_number =            $ingroupvaluesInfo->xferconf_e_number;
				                $ignore_list_script_override = $ingroupvaluesInfo->ignore_list_script_override;
				                $extension_appended_cidname = $ingroupvaluesInfo->extension_appended_cidname;
				                $uniqueid_status_display =      $ingroupvaluesInfo->uniqueid_status_display;
				                $uniqueid_status_prefix =       $ingroupvaluesInfo->uniqueid_status_prefix;
				                $hold_time_option_minimum = $ingroupvaluesInfo->hold_time_option_minimum;
				                $hold_time_option_press_filename = $ingroupvaluesInfo->hold_time_option_press_filename;
				                $hold_time_option_callmenu = $ingroupvaluesInfo->hold_time_option_callmenu;
				                $onhold_prompt_no_block =       $ingroupvaluesInfo->onhold_prompt_no_block;
				                $onhold_prompt_seconds =        $ingroupvaluesInfo->onhold_prompt_seconds;
				                $hold_time_option_no_block = $ingroupvaluesInfo->hold_time_option_no_block;
				                $hold_time_option_prompt_seconds =      $ingroupvaluesInfo->hold_time_option_prompt_seconds;
				                $hold_time_second_option =                      $ingroupvaluesInfo->hold_time_second_option;
				                $hold_time_third_option =                       $ingroupvaluesInfo->hold_time_third_option;
				                $wait_hold_option_priority =            $ingroupvaluesInfo->wait_hold_option_priority;
				                $wait_time_option =                                     $ingroupvaluesInfo->wait_time_option;
				                $wait_time_second_option =                      $ingroupvaluesInfo->wait_time_second_option;
				                $wait_time_third_option =                       $ingroupvaluesInfo->wait_time_third_option;
				                $wait_time_option_seconds =                     $ingroupvaluesInfo->wait_time_option_seconds;
				                $wait_time_option_exten =                       $ingroupvaluesInfo->wait_time_option_exten;
				                $wait_time_option_voicemail =           $ingroupvaluesInfo->wait_time_option_voicemail;
				                $wait_time_option_xfer_group =          $ingroupvaluesInfo->wait_time_option_xfer_group;
				                $wait_time_option_callmenu =            $ingroupvaluesInfo->wait_time_option_callmenu;
				                $wait_time_option_callback_filename =   $ingroupvaluesInfo->wait_time_option_callback_filename;
				                $wait_time_option_callback_list_id =    $ingroupvaluesInfo->wait_time_option_callback_list_id;
				                $wait_time_option_press_filename =      $ingroupvaluesInfo->wait_time_option_press_filename;
				                $wait_time_option_no_block =            $ingroupvaluesInfo->wait_time_option_no_block;
				                $wait_time_option_prompt_seconds =      $ingroupvaluesInfo->wait_time_option_prompt_seconds;
				                $timer_action_destination =                     $ingroupvaluesInfo->timer_action_destination;
				                $calculate_estimated_hold_seconds = $ingroupvaluesInfo->calculate_estimated_hold_seconds;
				                $add_lead_url =                                         $ingroupvaluesInfo->add_lead_url;
				                $eht_minimum_prompt_filename =          $ingroupvaluesInfo->eht_minimum_prompt_filename;
				                $eht_minimum_prompt_no_block =          $ingroupvaluesInfo->eht_minimum_prompt_no_block;
				                $eht_minimum_prompt_seconds =           $ingroupvaluesInfo->eht_minimum_prompt_seconds;
				                $on_hook_ring_time =                            $ingroupvaluesInfo->on_hook_ring_time;	
			
			 

						}
						
					    ### for agent ranks
		  				$agentranks = $this->goingroup->agentranks($group_id);

				}
				
				echo ucwords($group_id).'##'.$group_name.'##'.$group_color.'##'.$active.'##'.$web_form_address.'##'.$voicemail_ext.'##'.$next_agent_call.'##'.$fronter_display.'##'.$script_id.'##'.$get_call_launch.'##'.$xferconf_a_dtmf .'##'.$xferconf_a_number.'##'.$xferconf_b_dtmf.'##'.$xferconf_b_number.'##'.$drop_call_seconds.'##'.$drop_action.'##'.$drop_exten.'##'.$call_time_id.'##'.$after_hours_action.'##'.$after_hours_message_filename.'##'.$after_hours_exten.'##'.$after_hours_voicemail.'##'.$welcome_message_filename.'##'.$moh_context.'##'.$onhold_prompt_filename.'##'.$prompt_interval.'##'.$agent_alert_exten.'##'.$agent_alert_delay.'##'.$default_xfer_group.'##'.$queue_priority
.'##'.$drop_inbound_group.'##'.$ingroup_recording_override.'##'.$ingroup_rec_filename.'##'.$afterhours_xfer_group.'##'.$qc_enabled.'##'.$qc_statuses.'##'.$qc_shift_id.'##'.$qc_get_record_launch.'##'.$qc_show_recording.'##'.$qc_web_form_address.'##'.$qc_script.'##'.$play_place_in_line.'##'.$play_estimate_hold_time.'##'.$hold_time_option.'##'.$hold_time_option_seconds.'##'.$hold_time_option_exten.'##'.$hold_time_option_voicemail.'##'.$hold_time_option_xfer_group.'##'.$hold_time_option_callback_filename.'##'.$hold_time_option_callback_list_id.'##'.$hold_recall_xfer_group.'##'.$no_delay_call_route.'##'.$play_welcome_message.'##'.$answer_sec_pct_rt_stat_one.'##'.$answer_sec_pct_rt_stat_two.'##'.$default_group_alias.'##'.$no_agent_no_queue.'##'.$no_agent_action.'##'.$no_agent_action_value.'##'.$web_form_address_two.'##'.$timer_action.'##'.$timer_action_message.'##'.$timer_action_seconds.'##'.$start_call_url.'##'.$dispo_call_url.'##'.$xferconf_c_number.'##'.$xferconf_d_number.'##'.$xferconf_e_number.'##'.$ignore_list_script_override.'##'.$extension_appended_cidname.'##'.$uniqueid_status_display.'##'.$uniqueid_status_prefix.'##'.$hold_time_option_minimum.'##'.$hold_time_option_press_filename.'##'.$hold_time_option_callmenu.'##'.$onhold_prompt_no_block.'##'.$onhold_prompt_seconds.'##'.$hold_time_option_no_block.'##'.$hold_time_option_prompt_seconds.'##'.$hold_time_second_option.'##'.$hold_time_third_option.'##'.$wait_hold_option_priority.'##'.$wait_time_option.'##'.$wait_time_second_option.'##'.$wait_time_third_option.'##'.$wait_time_option_seconds.'##'.$wait_time_option_exten.'##'.$wait_time_option_voicemail.'##'.$wait_time_option_xfer_group.'##'.$wait_time_option_callmenu.'##'.$wait_time_option_callback_filename.'##'.$wait_time_option_callback_list_id.'##'.$wait_time_option_press_filename.'##'.$wait_time_option_no_block.'##'.$wait_time_option_prompt_seconds.'##'.$timer_action_destination.'##'.$calculate_estimated_hold_seconds.'##'.$add_lead_url.'##'.$eht_minimum_prompt_filename.'##'.$eht_minimum_prompt_no_block.'##'.$eht_minimum_prompt_seconds.'##'.$on_hook_ring_time.'##'.$agentranks; 					
				
				  

	}
	
	
	if($action=="getcheckagentrank") {
		
		$itemsumitexplode = explode('&', $this->input->post('itemrank'));
		$group_id = $this->input->post('idgroup');
		
		for( $i = 0; $i < count( $itemsumitexplode ); $i++ ) {
				$itemsumitsplit = split('=', $itemsumitexplode[$i]);
		 		$showval = htmlspecialchars(urldecode($itemsumitsplit[0]));
				$datavals = htmlspecialchars(urldecode($itemsumitsplit[1]));
				$finalvalues = $showval."||".$datavals.""; 
	
				if(preg_match("/CHECK/i", "$itemsumitexplode[$i]")) {
					$checked = $itemsumitexplode[$i]."\n";	
					$repcheck = str_replace("CHECK_", "", $checked);
					$user = str_replace("=YES", "", $repcheck);
					/*$ss="UPDATE vicidial_users set closer_campaigns='$group_id' where user='$user';";
					echo $ss;*/
				}
				
				if(preg_match("/RANK/i", "$itemsumitexplode[$i]")) {
					$itemsumitsplit1 = split('=', $itemsumitexplode[$i]);
					$datavals1 = htmlspecialchars(urldecode($itemsumitsplit1[1]));
					
					if($datavals1 != 0){
						$ranknotzero .= $itemsumitexplode[$i]."\n";
					}
				}
				
		}
		//echo $checked."\n".$ranknotzero."\n";
		

		
		$reprank = str_replace("RANK_", "", $ranknotzero);
		//echo $reprank;
		$itemsumitexplode = explode('=', $reprank);

		for( $i = 0; $i < count( $itemsumitexplode ); $i++ ) {
			//$reprank = split('=',$reprank[$i]);
			//echo $i;
			//echo $reprank[$i];
		}
		$time = $reprank;
		$length = strlen($$reprank);
		$characters = 2;
		$start = $length - $characters;
		$xreprank = substr($time , $start ,$characters);
//echo $user;

		/*$itemsumitsplit1 = split('=', $reprank[$i]);
		$xreprank = $reprank."=";
		$rank = str_replace($xreprank."=", "", $reprank);
		
		echo $rank;*/
		
		
/*
UPDATE vicidial_users set closer_campaigns='$group_id' where user='$user';<--A 
UPDATE vicidial_inbound_group_agents set group_rank='2',group_weight='2' where user='7245435501' and group_id='6098542657_16';<--C 
UPDATE vicidial_users set closer_campaigns=' 6098542657_16 - ' where user='7245436001';<--A 
UPDATE vicidial_inbound_group_agents set group_rank='1',group_weight='1' where user='7245436001' and group_id='6098542657_16';<--C 
*/	}
	
	
	
	
	if($action=="customedit") {

			$fieldid = $this->input->post('fieldid');
			$listid = $this->input->post('list_id');
			
			
			$customlistvalues = $this->golist->customeditfield($fieldid,$listid);
		
			$countme = count($customlistvalues);
		   
				if($countme > 0) { 
				
					foreach($customlistvalues as $customlistvaluesInfo) {
						 $field_id = $customlistvaluesInfo->field_id;
						 $list_id = $customlistvaluesInfo->list_id;
						 $field_label = $customlistvaluesInfo->field_label;
						 $field_name = $customlistvaluesInfo->field_name;
						 $field_description = $customlistvaluesInfo->field_description;
						 $field_rank = $customlistvaluesInfo->field_rank;
						 $field_help = $customlistvaluesInfo->field_help;
						 $field_type = $customlistvaluesInfo->field_type;
						 $field_options = $customlistvaluesInfo->field_options;
						 $field_size = $customlistvaluesInfo->field_size;
						 $field_max = $customlistvaluesInfo->field_max;
						 $field_default = $customlistvaluesInfo->field_default;
						 $field_cost = $customlistvaluesInfo->field_cost;
						 $field_required = $customlistvaluesInfo->field_required; 
						 $name_position = $customlistvaluesInfo->name_position;
						 $multi_position = $customlistvaluesInfo->multi_position;
						 $field_order = $customlistvaluesInfo->field_order;
						}
				}
		
			echo $field_id.'||'.$list_id.'||'.$field_label.'||'.$field_name.'||'.$field_description.'||'.$field_rank.'||'.$field_help.'||'.$field_type.'||'.$field_options.'||'.$field_size.'||'.$field_max.'||'.$field_default.'||'.$field_cost.'||'.$field_required.'||'.$name_position.'||'.$multi_position.'||'.$field_order; 
		
	}
	
	
	
	if($action=="customview") {
		
			$listid = $this->input->post('list_id');
			
			$customlistvalues = $this->golist->customviewmodel($listid);
		
			$countme = count($customlistvalues);
			
			$viewall .= "<label class=\"modify-value\" style=\"margin-left: -50%;\"><b>Example custom form</b></lable>\n";
			if($countme > 0) {
					$viewall .= "<form action=$PHP_SELF method=POST name=form_custom_$listid id=form_custom_$listid>\n";
					
					$viewall .= "<center><TABLE class=\"tableedit\" width=100%>\n";
					$last_field_rank=0;
					$o=0;	
							foreach($customlistvalues as $fieldsvalues){
							
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
							
								if ($last_field_rank=="$A_field_rank") {
									$viewall .= " &nbsp; &nbsp; &nbsp; &nbsp; ";
								} else {
									$viewall .= "</td></tr>\n";
									$viewall .= "<tr bgcolor=white><td align=";
								
									if ($A_name_position=='TOP') {
										$viewall .= "left colspan=2";
									} else {
										$viewall .= "right";
									}
								$viewall .= "><font size=2>";
								}	
								$viewall .= "<B>$A_field_name</B>";
								
								if ($A_name_position=='TOP') {
										$helpHTML .= "help+";
									if (strlen($A_field_help)<1){
										$helpHTML .= '';
									}
									$viewall .= " &nbsp; <span style=\"position:static;\" id=P_HELP_$A_field_label></span><span style=\"position:static;background:white;\" id=HELP_$A_field_label> &nbsp; $helpHTML</span><BR>";
								} else {
									if ($last_field_rank=="$A_field_rank") { 
										$viewall .= " &nbsp;";
									} else {
										$viewall .= "</td><td align=left><font size=2>";
									}
								}
								
								/* start fields */
								$field_HTML='';
								
								if ($A_field_type=='SELECT') {
									$field_HTML .= "<select size=1 name=$A_field_label id=$A_field_label>\n";
								}
								
								if ($A_field_type=='MULTI'){
									$field_HTML .= "<select MULTIPLE size=$A_field_size name=$A_field_label id=$A_field_label>\n";
								}
	
								/* data's */								
								if ( ($A_field_type=='SELECT') or ($A_field_type=='MULTI') or ($A_field_type=='RADIO') or ($A_field_type=='CHECKBOX') )
			{
			
						$field_options_array = explode(",",$A_field_options);
						
						$field_options_count = count($field_options_array);
						$te=0;
						while ($te < $field_options_count)
							{
								$field_selected='';
								$field_options_value_array = explode(' ',$field_options_array[$te]);
								
							
								if ( ($A_field_type=='SELECT') or ($A_field_type=='MULTI') )
									{
										
									if ($A_field_default == "$field_options_value_array[0]") {$field_selected = 'SELECTED';}
									$field_HTML .= "<option value=\"$field_options_value_array[0]\" $field_selected>$field_options_value_array[0]</option>\n";
									}
									
								if ( ($A_field_type=='RADIO') or ($A_field_type=='CHECKBOX') )
									{
										
									if ($A_multi_position=='VERTICAL') 
										{$field_HTML .= " &nbsp; ";}
									if ($A_field_default == "$field_options_value_array[0]") {$field_selected = 'CHECKED';}
							
									$lblname = $A_field_label.'[]';
				
									$field_HTML .= "<input type=$A_field_type name=$lblname id=$lblname value=\"$field_options_value_array[0]\" $field_selected> $field_options_value_array[0]\n";
								
									
									if ($A_multi_position=='VERTICAL') 
										{$field_HTML .= "<BR>\n";}
									}
							$te++;
							}
						}
			
			/* end datas */
			
							if ( ($A_field_type=='SELECT') or ($A_field_type=='MULTI') )
								{
								$field_HTML .= "</select>\n";
								}
							if ($A_field_type=='TEXT') 
								{
								if ($A_field_default=='NULL') {$A_field_default='';}
								$field_HTML .= "<input type=text size=$A_field_size maxlength=$A_field_max name=$A_field_label id=$A_field_label value=\"$A_field_default\">\n";
								}
							if ($A_field_type=='AREA') 
								{
								$field_HTML .= "<textarea name=$A_field_label id=$A_field_label ROWS=$A_field_max COLS=$A_field_size></textarea>";
								}
							if ($A_field_type=='DISPLAY')
								{
								if ($A_field_default=='NULL') {$A_field_default='';}
								$field_HTML .= "$A_field_default\n";
								}
							if ($A_field_type=='SCRIPT')
								{
								if ($A_field_default=='NULL') {$A_field_default='';}
								$field_HTML .= "$A_field_options\n";
								}
							if ($A_field_type=='DATE') 
								{
								if ( (strlen($A_field_default)<1) or ($A_field_default=='NULL') ) {$A_field_default=0;}
								$day_diff = $A_field_default;
								

								$default_date = date("Y-m-d", mktime(date("H"),date("i"),date("s"),date("m"),date("d")+$day_diff,date("Y")));

								$field_HTML .= "<input type=text size=11 maxlength=10 name=$A_field_label id=$A_field_label value=\"$default_date\">\n";
								/*$field_HTML .= "<script language=\"JavaScript\">\n";
								$field_HTML .= "var o_cal = new tcal ({\n";
								$field_HTML .= "	'formname': 'form_custom_$listid',\n";
								$field_HTML .= "	'controlname': '$A_field_label'});\n";
								$field_HTML .= "o_cal.a_tpl.yearscroll = false;\n";
								$field_HTML .= "</script>\n";*/
								$baseurl = base_url();
								$urlcalendar = $baseurl.'js/images/cal.gif';
								$field_HTML .= "<img id=\"$A_field_label\" name=\"$A_field_label\" src=\"$urlcalendar\">";
								
								}
							if ($A_field_type=='TIME') 
								{
								$minute_diff = $A_field_default;
								$default_time = date("H:i:s", mktime(date("H"),date("i")+$minute_diff,date("s"),date("m"),date("d"),date("Y")));
								$default_hour = date("H", mktime(date("H"),date("i")+$minute_diff,date("s"),date("m"),date("d"),date("Y")));
								$default_minute = date("i", mktime(date("H"),date("i")+$minute_diff,date("s"),date("m"),date("d"),date("Y")));
								$field_HTML .= "<input type=hidden name=$A_field_label id=$A_field_label value=\"$default_time\">";
								$field_HTML .= "<SELECT name=HOUR_$A_field_label id=HOUR_$A_field_label>";
								$field_HTML .= "<option>00</option>";
								$field_HTML .= "<option>01</option>";
								$field_HTML .= "<option>02</option>";
								$field_HTML .= "<option>03</option>";
								$field_HTML .= "<option>04</option>";
								$field_HTML .= "<option>05</option>";
								$field_HTML .= "<option>06</option>";
								$field_HTML .= "<option>07</option>";
								$field_HTML .= "<option>08</option>";
								$field_HTML .= "<option>09</option>";
								$field_HTML .= "<option>10</option>";
								$field_HTML .= "<option>11</option>";
								$field_HTML .= "<option>12</option>";
								$field_HTML .= "<option>13</option>";
								$field_HTML .= "<option>14</option>";
								$field_HTML .= "<option>15</option>";
								$field_HTML .= "<option>16</option>";
								$field_HTML .= "<option>17</option>";
								$field_HTML .= "<option>18</option>";
								$field_HTML .= "<option>19</option>";
								$field_HTML .= "<option>20</option>";
								$field_HTML .= "<option>21</option>";
								$field_HTML .= "<option>22</option>";
								$field_HTML .= "<option>23</option>";
								$field_HTML .= "<OPTION value=\"$default_hour\" selected>$default_hour</OPTION>";
								$field_HTML .= "</SELECT>";
								$field_HTML .= "<SELECT name=MINUTE_$A_field_label id=MINUTE_$A_field_label>";
								$field_HTML .= "<option>00</option>";
								$field_HTML .= "<option>05</option>";
								$field_HTML .= "<option>10</option>";
								$field_HTML .= "<option>15</option>";
								$field_HTML .= "<option>20</option>";
								$field_HTML .= "<option>25</option>";
								$field_HTML .= "<option>30</option>";
								$field_HTML .= "<option>35</option>";
								$field_HTML .= "<option>40</option>";
								$field_HTML .= "<option>45</option>";
								$field_HTML .= "<option>50</option>";
								$field_HTML .= "<option>55</option>";
								$field_HTML .= "<OPTION value=\"$default_minute\" selected>$default_minute</OPTION>";
								$field_HTML .= "</SELECT>";
								}								

								/* end fields */
								
								if ($A_name_position=='LEFT') {
										$helpHTML = "help+";
									if (strlen($A_field_help)<1) {
										$helpHTML = '';
									}
										$viewall .= " $field_HTML <span style=\"position:static;\" id=P_HELP_$A_field_label></span><span style=\"position:static;background:white;\" id=HELP_$A_field_label> &nbsp; $helpHTML</span>";
								} else {
									echo " $field_HTML\n";
								}								
								$last_field_rank=$A_field_rank;
								$o++;							
							}// end for
					$viewall .= "</td></tr></table></form></center><BR><BR>\n";
					echo $viewall;
			}
		
	}
	
	#add
	if($action=="customadd") {
		
		//check if field is already exist
		$stmt="SELECT count(*) as countchecking from vicidial_lists_fields where list_id='$list_id' and field_label='$field_label'";
		$rslt=$this->asteriskDB->query($stmt);
		
		foreach ($rslt->result() as $row) {
		   $fieldcheck = $row->countchecking;
		}
		
		if ( (strlen($field_label)<1) or (strlen($field_name)<2) or (strlen($field_size)<1) ) {
			echo "ERROR: You must enter a field label, field name and field size - $list_id | $field_label | $field_name | $field_size";
		} else {
			if($fieldcheck > 0) {
				echo "ERROR: Field already exists for this list - $list_id | $field_label";
			} else {

				$table_exists=0;
				$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
				$rslt=$this->asteriskDB->query($stmt);
				$tablecount_to_print  = $rslt->num_rows();
			
				if ($tablecount_to_print > 0) {
					$table_exists =	1;
				}
				
				if ( ($field_type=='DISPLAY') or ($field_type=='SCRIPT') or (preg_match("/\|$field_label\|/",$vicidial_list_fields)) ){
					if ($table_exists < 1) {
							$field_sql = "CREATE TABLE custom_$list_id (lead_id INT(9) UNSIGNED PRIMARY KEY NOT NULL);";	
							$rslt=$this->asteriskDB->query($field_sql);			
					}
				}
				
				## add field 
					$table_exists=0;
					$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
					$rslt=$this->asteriskDB->query($stmt);
					$tablecount_to_print  = $rslt->num_rows();
					
					if ($tablecount_to_print > 0) {
						$table_exists =	1;
					}
					
					if ($table_exists < 1)
						{$field_sql = "CREATE TABLE custom_$list_id (lead_id INT(9) UNSIGNED PRIMARY KEY NOT NULL, $field_label ";}
					else
						{$field_sql = "ALTER TABLE custom_$list_id ADD $field_label ";}

					/* type of field data */
		
					if ( ($field_type=='SELECT') or ($field_type=='RADIO') ) {
						$field_options_array = explode("\n",$field_options);
						$field_options_count = count($field_options_array);
						$te=0;
						while ($te < $field_options_count)
							{
							if (preg_match("/,/",$field_options_array[$te]))
								{
								$field_options_value_array = explode(",",$field_options_array[$te]);
								$field_options_ENUM .= str_replace(" ","_","'$field_options_value_array[0]',");
								}
							$te++;
							}
						$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
						$field_sql .= "ENUM($field_options_ENUM) ";
						$field_cost = strlen($field_options_ENUM);
					}	
					
					if ( ($field_type=='MULTI') or ($field_type=='CHECKBOX') )
					{
					$field_options_array = explode("\n",$field_options);
					$field_options_count = count($field_options_array);
					$te=0;
					while ($te < $field_options_count)
						{
						if (preg_match("/,/",$field_options_array[$te]))
							{
							$field_options_value_array = explode(",",$field_options_array[$te]);
							$field_options_ENUM .= str_replace(" ","_","'$field_options_value_array[0]',");
							}
						$te++;
						}
					$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
					$field_cost = strlen($field_options_ENUM);
					if ($field_cost < 1) {$field_cost=1;};
					$field_sql .= "VARCHAR($field_cost) ";
					}	
		
					if ($field_type=='TEXT') {
						if ($field_max < 1) {$field_max=1;};
						$field_sql .= "VARCHAR($field_max) ";
					}
					
					if ($field_type=='AREA') {
						$field_sql .= "TEXT ";
						$field_cost = 15;
					}
					
					if ($field_type=='DATE') {
						$field_sql .= "DATE ";
						$field_cost = 10;
					}
					
					if ($field_type=='TIME') {
						$field_sql .= "TIME ";
						$field_cost = 8;
					}
					
					/* end type of field data */
					
					
					if ( ($field_default != 'NULL') and ($field_type!='AREA') and ($field_type!='DATE') and ($field_type!='TIME') )
						{$field_sql .= "default '$field_default'";}
				
					if ($table_exists < 1)
						{$field_sql .= ");";}
					else
						{$field_sql .= ";";}
				
					if ( ($field_type=='DISPLAY') or ($field_type=='SCRIPT') or (preg_match("/\|$field_label\|/",$vicidial_list_fields)) )
						{
						if ($DB) {echo "Non-DB $field_type field type, $field_label\n";} 
						}
					else
						{
						if (strlen($copy_option) < 3)
							{
							$stmtCUSTOM="$field_sql";
							$rslt=$this->asteriskDB->query($stmtCUSTOM);
							}
						}
								
					//insert
					$stmt="INSERT INTO vicidial_lists_fields set field_label='$field_label',field_name='$field_name',field_description='$field_description',field_rank='$field_rank', field_help='$field_help',field_type='$field_type',field_options='$field_options',field_size='$field_size',field_max='$field_max', field_default='$field_default',field_required='$field_required',field_cost='$field_cost',list_id='$list_id', multi_position='$multi_position',name_position='$name_position',field_order='$field_order';";	
					//echo $stmt;
					$rslt=$this->asteriskDB->query($stmt);				

				
					}
			
				}
		}
		
		#edit
		if($action=='customeditsub') {
			
			$field_sql = "ALTER TABLE custom_$list_id MODIFY $field_label ";
			
			$field_options_ENUM='';
			$field_cost=1;
		
				if ( ($field_type=='SELECT') or ($field_type=='RADIO') ) {
					$field_options_array = explode("\n",$field_options);
					$field_options_count = count($field_options_array);
					$te=0;
					
				while ($te < $field_options_count) {
					if (preg_match("/,/",$field_options_array[$te]))
						{
						$field_options_value_array = explode(",",$field_options_array[$te]);
						$field_options_ENUM .= str_replace(" ","_","'$field_options_value_array[0]',");
						}
					$te++;
				}
				$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
				$field_sql .= "ENUM($field_options_ENUM) ";
				$field_cost = strlen($field_options_ENUM);
				}
	
				if ( ($field_type=='MULTI') or ($field_type=='CHECKBOX') ) {
					$field_options_array = explode("\n",$field_options);
					$field_options_count = count($field_options_array);
					$te=0;
					while ($te < $field_options_count) {
						if (preg_match("/,/",$field_options_array[$te]))
							{
							$field_options_value_array = explode(",",$field_options_array[$te]);
							$field_options_ENUM .= str_replace(" ","_","'$field_options_value_array[0]',");
							}
						$te++;
					}
					$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
					$field_cost = strlen($field_options_ENUM);
					$field_sql .= "VARCHAR($field_cost) ";
				}
	
				if ($field_type=='TEXT') {
					$field_sql .= "VARCHAR($field_max) ";
				}
				
				if ($field_type=='AREA') {
					$field_sql .= "TEXT ";
				}

				if ($field_type=='DATE') {
					$field_sql .= "DATE ";
				}

				if ($field_type=='TIME') {
					$field_sql .= "TIME ";
				}
				
				if ( ($field_default == 'NULL') or ($field_type=='AREA') or ($field_type=='DATE') or ($field_type=='TIME') ) {
					$field_sql .= ";";
				} else {
					$field_sql .= "default '$field_default';";
				}

				if ( ($field_type=='DISPLAY') or ($field_type=='SCRIPT') or (preg_match("/\|$field_label\|/",$vicidial_list_fields)) ) {
					echo "Non-DB $field_type field type, $field_label\n"; 
				} else {
					$stmtCUSTOM="$field_sql";
					
	//				echo $stmtCUSTOM;
					$rslt=$this->asteriskDB->query($stmtCUSTOM);	
					
				}

					$stmt="UPDATE vicidial_lists_fields set field_label='$field_label',field_name='$field_name',field_description='$field_description',field_rank='$field_rank',field_help='$field_help',field_type='$field_type',field_options='$field_options',field_size='$field_size',field_max='$field_max',field_default='$field_default',field_required='$field_required',field_cost='$field_cost',multi_position='$multi_position',name_position='$name_position',field_order='$field_order' where list_id='$list_id' and field_id='$field_id';";
					//echo $stmt;			
					$rslt=$this->asteriskDB->query($stmt);	

					### LOG INSERTION Admin Log Table ###
		/* 			$SQL_log = "$stmt|$stmtCUSTOM";
					$SQL_log = ereg_replace(';','',$SQL_log);
					$SQL_log = addslashes($SQL_log);
					$stmt="INSERT INTO vicidial_admin_log set event_date=NOW(), user='$user', ip_address='$ip', event_section='CUSTOM_FIELDS', event_type='MODIFY', record_id='$list_id', event_code='ADMIN MODIFY CUSTOM LIST FIELD', event_sql=\"$SQL_log\", event_notes='';";
	*/					
		}
		
		if($action=="customdelete") {
			if ( ($field_type=='DISPLAY') or ($field_type=='SCRIPT') or (preg_match("/\|$field_label\|/",$vicidial_list_fields)) )
		{
		if ($DB) {echo "Non-DB $field_type field type, $field_label\n";} 
		}
	else
		{
		$stmtCUSTOM="ALTER TABLE custom_$list_id DROP $field_label;";
	/*	$rsltCUSTOM=mysql_query($stmtCUSTOM, $linkCUSTOM);
		$table_update = mysql_affected_rows($linkCUSTOM);*/
		if ($DB) {echo "$table_update|$stmtCUSTOM\n";}
		if (!$rsltCUSTOM) {echo('Could not execute: ' . mysql_error());}
		}

	$stmt="DELETE FROM vicidial_lists_fields WHERE field_label='$field_label' and field_id='$field_id' and list_id='$list_id' LIMIT 1;";		
		}
		
	
	
	
	
	
	
?>
