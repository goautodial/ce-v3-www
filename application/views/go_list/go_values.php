<?php
############################################################################################
####  Name:             go_values.php                                                   ####
####  Type:             ci views - administrator                                        ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Jerico James F. Milo                                            ####
####  License:          AGPLv2                                                          ####
############################################################################################

	$this->asteriskDB = $this->load->database('dialerdb',TRUE);
	$this->customdialerdb = $this->load->database('customdialerdb',TRUE); 
	$this->load->model('golist');



	//post variables
	$vicidial_list_fields = '|lead_id|vendor_lead_code|source_id|list_id|gmt_offset_now|called_since_last_reset|phone_code|phone_number|title|first_name|middle_initial|last_name|address1|address2|address3|city|state|province|postal_code|country_code|gender|date_of_birth|alt_phone|email|security_phrase|comments|called_count|last_local_call_time|rank|owner|';
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
	$fakelblname = $this->input->post('fakelblname');

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
		$active_field = split('=', $itemsumitexplode[8]);
		$active_field = htmlspecialchars(urldecode($active_field[1]));
		
		if(is_null($sucesshint)) {
	 		
	 		for( $i = 3; $i < count( $itemsumitexplode ); $i++ ) {
	 			
	 			$itemsumitsplit = split('=', $itemsumitexplode[$i]);
	 			$showval = htmlspecialchars(urldecode($itemsumitsplit[0]));
				$datavals = htmlspecialchars(urldecode($itemsumitsplit[1]));
				$showval = str_replace(';', '',$showval);
				$finalvalues = "".$showval."='".mysql_real_escape_string($datavals)."' "."";
				$squote = "'\". ";
				$equote = " .\"' ";
				
				if($showval=="reset_list"){
					//reset leads;
					if($reset_field == "Y"){
						$queryreset = "UPDATE vicidial_list set called_since_last_reset='N' where list_id='$listid_data';";
						$hopperreset = "DELETE from vicidial_hopper where list_id='$listid_data' and campaign_id='$campaign_field';";
						$this->golist->resetleads($queryreset);
						$this->golist->resetleads($hopperreset);
					} 
				}
				
				if ($showval=="active") {
					if ($active_field == "Y") {
						$result = exec("/usr/share/goautodial/go_list_archiver.pl --listid={$listid_data} --action=activate --quiet");
					} else {
						$result = exec("/usr/share/goautodial/go_list_archiver.pl --listid={$listid_data} --action=deactivate --quiet");
					}
				}
				
				if($showval=="web_form_address") {
					
					if(!is_null($webformdata)){
						$webset = "web_form_address = ". $this->asteriskDB->escape($webformdata);
						$finalvaluesweb = "".$webset;
						$querywebset = "UPDATE vicidial_lists set ".$finalvaluesweb." WHERE list_id='".$listid_data."';";
						$this->golist->editvalues($querywebset);
					}
					
				} elseif($showval=="reset_list") {
						$resetlist = "";
						$resetlistvalue = "";
						$queryresetlist = "";
						//$this->golist->editvalues($querywebset);
						
				} else {
				   $query = "UPDATE vicidial_lists set ".$finalvalues." WHERE list_id='".$listid_data."';";
					$this->golist->editvalues($query);	
				}


			}
				$SQLdate = date("Y-m-d H:i:s");
				$querydate="UPDATE vicidial_lists SET list_changedate='$SQLdate' WHERE list_id='$listid_data';";
				$this->golist->editvalues($querydate);
                                $this->commonhelper->auditadmin("MODIFY","MODIFY LIST",$query);
				$sucesshint = "SUCCESS";
			} 
			
			if($sucesshint=="FAILED") {
				echo "FAILED: List I.D. $listid_data modified";
				$sucesshint = "FAILED";
			} else {
				echo "SUCCESS: List I.D. $listid_data modified";
				$sucesshint = "";
			}
	}
	
	if($action=="deletelist") {
			$listiddel = $this->input->post('listid_delete');
			
			if(is_null($sucesshint)) {
				$querydelete = "DELETE FROM vicidial_lists WHERE list_id='$listiddel'; ";
				$this->golist->deletevalues($querydelete);
				
				$querydeleteleads = "DELETE FROM vicidial_list WHERE list_id='$listiddel';";
				$this->golist->deletevalues($querydeleteleads);
		
                                $this->commonhelper->auditadmin("DELETE","DELETE LIST list_id=$listiddel");	
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
	
	if($action=="activatelist") {
			$listiddel = $this->input->post('listid_delete');
			
			if(is_null($sucesshint)) {
				$queryedit ="UPDATE vicidial_lists SET active = 'Y' WHERE list_id ='$listiddel'"; ;
				$this->golist->editvalues($queryedit);
                                $this->commonhelper->auditadmin("MODIFY","MODIFY LIST list_id=$listiddel");	
				$sucesshint = "SUCCESS";
			}
			
			if($sucesshint=="FAILED") {
				echo "FAILED: List I.D. $listid_data not activated";
				$sucesshint = "FAILED";
			} else {
				$result = exec("/usr/share/goautodial/go_list_archiver.pl --listid={$listiddel} --action=activate --quiet");
				echo "SUCCESS: List I.D. $listid_data activated";
				$sucesshint = "";
			}
	}
	
		if($action=="deactivatelist") {
			$listiddel = $this->input->post('listid_delete');
			
			if(is_null($sucesshint)) {
				$queryedit ="UPDATE vicidial_lists SET active = 'N' WHERE list_id ='$listiddel'"; ;
				$this->golist->editvalues($queryedit);
                                $this->commonhelper->auditadmin("MODIFY","MODIFY LIST list_id=$listiddel");	
				$sucesshint = "SUCCESS";
			}
			
			if($sucesshint=="FAILED") {
				echo "FAILED: List I.D. $listid_data not deactivated";
				$sucesshint = "FAILED";
			} else {
				$result = exec("/usr/share/goautodial/go_list_archiver.pl --listid={$listiddel} --action=deactivate --quiet");
				echo "SUCCESS: List I.D. $listid_data deactivated";
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
			$listvalues = $this->golist->geteditvalues($dataval);
			$statuses = $this->golist->getstatuses($dataval);
			$datatzones = $this->golist->gettzones($dataval);
			$globalstatus = $this->golist->globalstatus();
	
		   	$countme = count($listvalues);
		   	$countstatuses = count($statuses);
		   	$counttzones = count($datatzones);
		   	$globalstatuscount = count($globalstatus);
		   
				if($countme > 0) { 
				
					foreach($listvalues as $listvaluesInfo) {

							  		  		$elistid = $listvaluesInfo->list_id;
									  		$elistname = $listvaluesInfo->list_name;
									  		$ecampaign_id = $listvaluesInfo->campaign_id;
									  		$eactive = $listvaluesInfo->active;
									  		$elist_description = $listvaluesInfo->list_description;
									  		$elist_changedate = $listvaluesInfo->list_changedate;
									  		$elist_lastcalldate = $listvaluesInfo->list_lastcalldate;
									  		$ereset_time = $listvaluesInfo->reset_time;
									  		$eagent_script_override = $listvaluesInfo->agent_script_override;
									  		$ecampaign_cid_override = $listvaluesInfo->campaign_cid_override;
									  		$eam_message_exten_override = $listvaluesInfo->am_message_exten_override;
									  		$edrop_inbound_group_override = $listvaluesInfo->drop_inbound_group_override;
									  		$exferconf_a_number = $listvaluesInfo->xferconf_a_number;
									  		$exferconf_b_number = $listvaluesInfo->xferconf_b_number;
									  		$exferconf_c_number = $listvaluesInfo->xferconf_c_number;
									  		$exferconf_d_number = $listvaluesInfo->xferconf_d_number;
									  		$exferconf_e_number = $listvaluesInfo->xferconf_e_number;
									  		$eweb_form_address = $listvaluesInfo->web_form_address;
									  		$eweb_form_address_two = $listvaluesInfo->web_form_address_two; 

						}

				}
				
				if($globalstatuscount > 0) {
					foreach($globalstatus as $globalstatusInfo) {
						$globalstats = $globalstatusInfo->status;	
						$globalstatdata["$globalstats"] = $globalstatusInfo->status_name;
					}	
				}
					

				if($countstatuses > 0) { 
					
					$allstats .="<table id=\"statusid\" class=\"\" width=\"\" cellspacing=\"3\" cellpadding=\"4\" border=\"1\" style=\"display: none; width: 40%; \">";
					$allstats .="<tr align=\"left\" class=\"nowrap\">";
					$allstats .="<td class=\"thheader\"><b>STATUS</td>";
					$allstats .="<td class=\"thheader\"><b>DESCRIPTION</td>";
					$allstats .="<td class=\"thheader\" align=\"center\"><b>CALLED</td>";
					$allstats .="<td class=\"thheader\" align=\"center\"><b>NOT <br>CALLED</td>";
					$allstats .="<tr>";
					
					$lead_list['count'] = 0;
                			$lead_list['Y_count'] = 0;
                			$lead_list['N_count'] = 0;

					foreach($statuses as $statusesInfo) {
						$stats = $statusesInfo->stats;	
						$called_since_last_reset = $statusesInfo->called_since_last_reset;	
						$countvlists = $statusesInfo->countvlists;	
						
						$lead_list['count'] = ($lead_list['count'] + $countvlists);						
						
						if ($called_since_last_reset == 'N') {
                                			$since_reset = 'N';
                                			$since_resetX = 'Y';
                                		} else {
                                			$since_reset = 'Y';
                                			$since_resetX = 'N';
                                		} 
			
                        			$lead_list[$since_reset][$stats] = ($lead_list[$since_reset][$stats] + $countvlists);
                        			$lead_list[$since_reset.'_count'] = ($lead_list[$since_reset.'_count'] + $countvlists);
                        			
						#If opposite side is not set, it may not in the future so give it a value of zero
                        
						if (!isset($lead_list[$since_resetX][$stats])) {
                                			$lead_list[$since_resetX][$stats]=0;
                                		}
					}

					while (list($dispo,) = each($lead_list[$since_reset])) {
						$allstats .="<tr align=left class=tr".alternator('1', '2').">";
						$allstats .="<td>".$dispo."</td>";
						$allstats .="<td>".$globalstatdata[$dispo]."</td>";
						$allstats .="<td align=\"center\">".$lead_list['Y'][$dispo]."</td>";
						$allstats .="<td align=\"center\">".$lead_list['N'][$dispo]."</td>";
						$allstats .="</tr>";
					}
						$allstats .="<tr class=\"tr2\"><td colspan=2><b>SUBTOTALS<b></td><td align=\"center\"><b> <font color=\"green\"> ".$lead_list[Y_count]."</font></td><td align=\"center\"><b><font color=\"green\">".$lead_list[N_count]."</font></td></tr>";
						$allstats .="<tr class=\"tr1\"><td colspan=2 align=left><b>TOTAL</td><td colspan=2 align=center><font color=\"blue\"><b>".$lead_list[count]."</font></td></tr>";
						$allstats .="</table>";
				}
		
				if($counttzones > 0) {
					
					
					$allstats .="<br><br><center><b><a id=\"clickadvanceplustime\" style=\"cursor: pointer;\" onclick=\"$('#timezoneid').css('display', 'block'); $('#clickadvanceplustime').css('display', 'none'); $('#clickadvanceminustime').css('display', 'block');  \" title=\"Click to view reports\">[ + ] TIME ZONES WITHIN THIS LIST</a><a id=\"clickadvanceminustime\" style=\"cursor: pointer; display: none;\" onclick=\"$('#timezoneid').css('display', 'none'); $('#clickadvanceplustime').css('display', 'block'); $('#clickadvanceminustime').css('display', 'none');\" title=\"Click to view reports\">[ - ] TIME ZONES WITHIN THIS LIST</a></b></center>";
					$allstats .="<table id=\"timezoneid\" class=\"\" width=\"\" cellspacing=\"3\" cellpadding=\"4\" border=\"1\" style=\"display: none; width: 50%; \">";
					$allstats .="<tr align=\"left\" class=\"nowrap\" colspan=\"2\">";
					$allstats .="<td class=\"thheader\"><b>GMT OFFSET NOW (local time)</td>";
					$allstats .="<td class=\"thheader\" align=\"center\"><b>CALLED</td>";
					$allstats .="<td class=\"thheader\" align=\"center\"><b>NOT <br>CALLED</td>";
					$allstats .="<tr>";
		
					unset($lead_list);	
			                $plus='+';
                			$lead_list['count'] = 0;
                			$lead_list['Y_count'] = 0;
                			$lead_list['N_count'] = 0;
	
					foreach($datatzones as $datatzonesInfo) {
						
						$tgmt_offset_now = $datatzonesInfo->gmt_offset_now;
						$tcalled_since_last_reset = $datatzonesInfo->called_since_last_reset;
						$counttlist = $datatzonesInfo->counttlist;
						
			                        $lead_list['count'] = ($lead_list['count'] + $counttlist);
                        
						if ($tcalled_since_last_reset == 'N')
                                		{
                                		$since_reset = 'N';
                                		$since_resetX = 'Y';
                                		}
                        			else
                                		{
                                		$since_reset = 'Y';
                                		$since_resetX = 'N';
                                		}
                        
						$lead_list[$since_reset][$tgmt_offset_now] = ($lead_list[$since_reset][$tgmt_offset_now] + $counttlist);
                        			$lead_list[$since_reset.'_count'] = ($lead_list[$since_reset.'_count'] + $counttlist);
                        	
						#If opposite side is not set, it may not in the future so give it a value of zero
                        			if (!isset($lead_list[$since_resetX][$tgmt_offset_now]))
                                		{
                                		$lead_list[$since_resetX][$tgmt_offset_now]=0;
                                		}
						
					}
				
					while (list($tzone,) = each($lead_list[$since_reset]))
                                	{
                                		$LOCALzone=3600 * $tzone;
                                		$LOCALdate=gmdate("D M Y H:i", time() + $LOCALzone);

                                		if ($tzone >= 0) {$DISPtzone = "$plus$tzone";}
                                		else {$DISPtzone = "$tzone";}
					
						$allstats .="<tr align=left class=tr".alternator('1', '2').">";
						$allstats .="<td>&nbsp; &nbsp;".$DISPtzone." &nbsp; &nbsp; (".$LOCALdate.")</td>";
						$allstats .="<td align=\"center\">".$lead_list['Y'][$tzone]."</td>";
						$allstats .="<td align=\"center\">".$lead_list['N'][$tzone]."</td>";
						$allstats .="</tr>";

					}
						$allstats .="<tr class=\"tr2\"><td><b>SUBTOTALS<b></td><td align=\"center\"><b> <font color=\"green\"> ".$lead_list[Y_count]."</font></td><td align=\"center\"><b><font color=\"green\">".$lead_list[N_count]."</font></td></tr>";
						$allstats .="<tr class=\"tr1\"><td align=left><b>TOTAL</td><td colspan=2 align=center><font color=\"blue\"><b>".$lead_list[count]."</font></td></tr>";
				
					$allstats .="</table>";

				}
				
				
				
				echo $exferconf_a_number.'--'.$elistid.'--'.$elistname.'--'.$ecampaign_id.'--'.$eactive.'--'.$elist_description.'--'.$elist_changedate.'--'.$elist_lastcalldate.'--'.$ereset_time.'--'.$eagent_script_override.'--'.$ecampaign_cid_override.'--'.$eam_message_exten_override.'--'.$edrop_inbound_group_override.'--'.$exferconf_a_number.'--'.$exferconf_b_number.'--'.$exferconf_c_number.'--'.$exferconf_d_number.'--'.$exferconf_e_number.'--'.$eweb_form_address.'--'.$eweb_form_address_twoi.'##'.$allstats;
				
	}
	
	
	
	
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
			
			$viewall .= "<label class=\"modify-value\" style=\"margin-left: -50%; color: black; font-weight: bold;\"><b>Example custom form</b></lable>\n";
			if($countme > 0) {
					$viewall .= "<form action=$PHP_SELF method=POST name=form_custom_$listid id=form_custom_$listid>\n";
					
					$viewall .= "<center><TABLE class=\"tableedit\" style=\"border-top: 1px double; rgb(208,208,208);\" width=100%>\n";
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
			
						$field_options_array = explode("\n",$A_field_options);
						
						$field_options_count = count($field_options_array);
						$te=0;
						while ($te < $field_options_count)
							{
								if (preg_match("/,/",$field_options_array[$te])) {
								$field_selected='';
								$field_options_value_array = explode(",",$field_options_array[$te]);
							
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
				
									$field_HTML .= "<input type=$A_field_type name=$lblname id=$lblname value=\"$field_options_value_array[0]\" $field_selected> $field_options_value_array[1]\n";
								
									
									if ($A_multi_position=='VERTICAL') 
										{$field_HTML .= "<BR>\n";}
									}
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
					$viewall .= "</td></tr></table></form></center>\n";
					echo $viewall;
			}
		
	}
	
	#add
	if($action=="customadd") {
		
		//check if field is already exist
		$stmt="SELECT count(*) as countchecking from vicidial_lists_fields where list_id='$list_id' and field_label='$field_label'";
		$rslt=$this->customdialerdb->query($stmt);
		
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
				$rslt=$this->customdialerdb->query($stmt);
				$tablecount_to_print  = $rslt->num_rows();
			
				if ($tablecount_to_print > 0) {
					$table_exists =	1;
				}
				
				if ( ($field_type=='DISPLAY') or ($field_type=='SCRIPT') or (preg_match("/\|$field_label\|/",$vicidial_list_fields)) ){
					if ($table_exists < 1) {
							$field_sql = "CREATE TABLE custom_$list_id (lead_id INT(9) UNSIGNED PRIMARY KEY NOT NULL);";	
							$rslt=$this->customdialerdb->query($field_sql);			
					}
				}
				
				## add field 
					$table_exists=0;
					$stmt="SHOW TABLES LIKE \"custom_$list_id\";";
					$rslt=$this->customdialerdb->query($stmt);
					$tablecount_to_print  = $rslt->num_rows();
					
					if ($tablecount_to_print > 0) {
						$table_exists =	1;
					}
					
					if ($table_exists < 1){
						$field_sql .= "CREATE TABLE custom_$list_id (lead_id INT(9) UNSIGNED PRIMARY KEY NOT NULL, $field_label ";
					} else {
						$field_sql .= "ALTER TABLE custom_$list_id ADD $field_label ";
					}

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
						$fieldcatch = $field_options_ENUM;
						$field_cost = strlen($field_options_ENUM);
						if ($field_cost < 1) {$field_cost=1;};
						$field_sql .= "ENUM($field_options_ENUM) ";
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
					
					//var_dump($field_default); die();
					if ( (!empty($field_default) ) and ($field_type!='AREA') and ($field_type!='DATE') and ($field_type!='TIME') )
						{
							if($fieldcatch == "") {
								$field_sql .= "default '$field_default'";
							} else {
								$field_sql .= "default $fieldcatch";
							}
					}
						
					if ( empty($field_default) ) {
						
						$field_sql .="";  
					}
					
					//die($field_default."mIL");
			
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
							//die($stmtCUSTOM);
							$rslt=$this->customdialerdb->query($stmtCUSTOM);
							}
						}
								
					//insert
					$stmt="INSERT INTO vicidial_lists_fields set field_label='$field_label',field_name='$field_name',field_description='$field_description',field_rank='$field_rank', field_help='$field_help',field_type='$field_type',field_options='$field_options',field_size='$field_size',field_max='$field_max', field_default='$field_default',field_required='$field_required',field_cost='$field_cost',list_id='$list_id', multi_position='$multi_position',name_position='$name_position',field_order='$field_order';";	
					//echo $stmt;
					$rslt=$this->customdialerdb->query($stmt);				
                                        $this->commonhelper->auditadmin("ADD","ADD NEW CUSTOM FIELDS");

				
					}
			
				}
		}
		
		#edit
		if($action=='customeditsub') {
			
		if($fakelblname!=$field_label) {
		   #$field_sql .= "ALTER TABLE custom_$list_id CHANGE $fakelblname $field_label ";
		   $field_sql .= "ALTER TABLE custom_$list_id CHANGE $field_label $field_label "; 
		} else {
			$field_sql .= "ALTER TABLE custom_$list_id MODIFY $field_label ";
		}				
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
					
					/* $field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
					$field_sql .= "ENUM($field_options_ENUM) ";
					$field_cost = strlen($field_options_ENUM); */
					
					$field_options_ENUM = preg_replace("/.$/",'',$field_options_ENUM);
					
					$field_cost = strlen($field_options_ENUM);
					if ($field_cost < 1) {$field_cost=1;};
					$field_sql .= "ENUM($field_options_ENUM) ";
					
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
					
					/* if ( ($field_default == 'NULL') or ($field_type=='AREA') or ($field_type=='DATE') or ($field_type=='TIME') ) {
						$field_sql .= ";";
					} else {
						$field_sql .= "default milo '$field_default';";
					}*/
					
					if ( (!empty($field_default) ) and ($field_type!='AREA') and ($field_type!='DATE') and ($field_type!='TIME') )
						{ 
							if($fieldcatch == "") {
								//$field_sql .= "default '$field_default'";
								$field_sql .= ";";
							} else {
								//$field_sql .= "default $fieldcatch";
								if(empty($field_default)) {
									$field_sql .=";";
								} else { 
									$field_sql .= "default '$field_default' ";
								}
							}
					}
						
					if ( empty($field_default) ) {
						
						$field_sql .="";  
					}
	
					if ( ($field_type=='DISPLAY') or ($field_type=='SCRIPT') or (preg_match("/\|$field_label\|/",$vicidial_list_fields)) ) {
						echo "Non-DB $field_type field type, $field_label\n"; 
					} else {
						$stmtCUSTOM="$field_sql";
						//die($stmtCUSTOM);
						$rslt=$this->customdialerdb->query($stmtCUSTOM);	
					}
		
					

				
				$stmt="UPDATE vicidial_lists_fields set field_label='$field_label',field_name='$field_name',field_description='$field_description',field_rank='$field_rank',field_help='$field_help',field_type='$field_type',field_options='$field_options',field_size='$field_size',field_max='$field_max',field_default='$field_default',field_required='$field_required',field_cost='$field_cost',multi_position='$multi_position',name_position='$name_position',field_order='$field_order' where list_id='$list_id' and field_id='$field_id';";
				
					$rslt=$this->customdialerdb->query($stmt);	
                                $this->commonhelper->auditadmin("MODIFY","MODIFY CUSTOM FIELDS id $list_id and $field_id");
		}
		
		if($action=="customdelete") {
			$stmtCUSTOM="ALTER TABLE custom_$list_id DROP $field_label;";
			$rslt=$this->customdialerdb->query($stmtCUSTOM);
			$stmt="DELETE FROM vicidial_lists_fields WHERE field_label='$field_label' and field_id='$field_id' and list_id='$list_id' LIMIT 1;";		$rslt=$this->customdialerdb->query($stmt);
                        $this->commonhelper->auditadmin("DELETE","DELETE CUSTOM FIELD field_ld=$field_id and list_id=$list_id");
			
			echo "field deleted";
		}
		
		if($action=="custombatchdelete") {
			
			$stmt="SELECT list_id, field_label FROM vicidial_lists_fields where field_id='$field_id';";
		                $rslt=$this->customdialerdb->query($stmt);
				$fieldscount_to_print  = $rslt->num_rows();
                
				 if ($fieldscount_to_print > 0) {
                			foreach ($rslt->result() as $row) {
                   				$clist_id = $row->list_id;
						$cfield_label = $row->field_label;
					}
					
					$stmtCUSTOM="ALTER TABLE custom_$clist_id DROP $cfield_label;";
					$rslt=$this->customdialerdb->query($stmtCUSTOM);
					
					$stmtDEL="DELETE FROM vicidial_lists_fields WHERE field_label='$cfield_label' and field_id='$field_id' and list_id='$clist_id' LIMIT 1;";
					$rslt=	$this->customdialerdb->query($stmtDEL);
                        
					$this->commonhelper->auditadmin("DELETE","DELETE CUSTOM FIELD field_ld=$field_id and list_id=$clist_id"); 
			
					echo "field deleted"; 
				}
			
		}
		
	
		if($action=="copycustomlist") {
			 $vicidial_list_fields = '|lead_id|vendor_lead_code|source_id|list_id|gmt_offset_now|called_since_last_reset|phone_code|phone_number|title|first_name|middle_initial|last_name|address1|address2|address3|city|state|province|postal_code|country_code|gender|date_of_birth|alt_phone|email|security_phrase|comments|called_count|last_local_call_time|rank|owner|';
			
			$source_list_id = $this->input->post('source_list_id');
			$to_list_id = $this->input->post('to_list_id');
			$copy_option = $this->input->post('copy_option');
			$list_id = $to_list_id;

			if($list_id == "$source_list_id" ) {
				echo "ERROR: You cannot copy fields to the same list: $list_id|$source_list_id";
			} else {
			
				 $stmt="SELECT count(*) as sourcecountfields from vicidial_lists_fields where list_id='$source_list_id';";
		                 $rslt=$this->customdialerdb->query($stmt);
				 $fieldscount_to_print  = $rslt->num_rows();

				  if ($fieldscount_to_print > 0) {
                			foreach ($rslt->result() as $row) {
                   				$source_field_exists = $row->sourcecountfields;
                			}
				  }
		                $stmt="SELECT count(*) as tocountfields from vicidial_lists_fields where list_id='$list_id';";
		                $rslt=$this->customdialerdb->query($stmt);
				$fieldscount_to_print  = $rslt->num_rows();
                
				 if ($fieldscount_to_print > 0) {
                			foreach ($rslt->result() as $row) {
                   				$field_exists = $row->tocountfields;
                			}
                        	}
				
			        $stmt="SHOW TABLES LIKE \"custom_$list_id\";";
		                $rslt=$this->customdialerdb->query($stmt);
				$tablecount_to_print = $rslt->num_rows();

			        if ($tablecount_to_print > 0) {
					$table_exists = 1;
				}
		
	                	if ($source_field_exists < 1){
					echo "ERROR: Source list has no custom fields.";
				} else {
				
                        		##### REPLACE option #####
                        		if ($copy_option=='REPLACE') {
                        		
						if ($table_exists > 0) {
						
							$stmt="SELECT field_id,field_label from vicidial_lists_fields where list_id='$list_id' order by field_rank,field_order,field_label;";
							$rslt=$this->customdialerdb->query($stmt);
							$fields_to_print = $rslt->num_rows();
							$fields_list='';
							$o=0;
							while ($fields_to_print > $o) 
								{
								$rowx = $rslt->row();
								$A_field_id[$o] = $rowx->field_id;
								$A_field_label[$o] = $rowx->field_label;
								$o++;
								}

							$o=0;
							while ($fields_to_print > $o) 
								{
								### delete field function
								$this->golist->copydeletecustomfield($table_exists,$A_field_id[$o],$list_id,$A_field_label[$o],$A_field_name[$o],$A_field_description[$o],$A_field_rank[$o],$A_field_help[$o],$A_field_type[$o],$A_field_options[$o],$A_field_size[$o],$A_field_max[$o],$A_field_default[$o],$A_field_required[$o],$A_field_cost[$o],$A_multi_position[$o],$A_name_position[$o],$A_field_order[$o],$vicidial_list_fields);

								#echo "SUCCESS: Custom Field Deleted - $list_id|$A_field_label[$o]\n<BR>";
								$o++;
								}
                                		
						}
						
					$copy_option='APPEND';
					}
					##### END REPLACE option #####
					
					##### APPEND option #####
                        		if ($copy_option=='APPEND') {
						$stmt="SELECT field_id,field_label,field_name,field_description,field_rank,field_help,field_type,field_options,field_size,field_max,field_default,field_cost,field_required,multi_position,name_position,field_order from vicidial_lists_fields where list_id='$source_list_id' order by field_rank,field_order,field_label;";
						
						$rsltapps=$this->customdialerdb->query($stmt);
						$fields_to_print = $rsltapps->num_rows();
						
						$fields_list='';
						$o=0;
						foreach($rsltapps->result() as $rowxd) {
							$A_field_id[$o] =			$rowxd->field_id;
							$A_field_label[$o] =		$rowxd->field_label;
							$A_field_name[$o] =		$rowxd->field_name;
							$A_field_description[$o] =			$rowxd->field_description;				$A_field_rank[$o] =			$rowxd->field_rank;
							$A_field_help[$o] =			$rowxd->field_help;
							$A_field_type[$o] =			$rowxd->field_type;
							$A_field_options[$o] =		$rowxd->field_options;
							$A_field_size[$o] =			$rowxd->field_size;
							$A_field_max[$o] =			$rowxd->field_max;
							$A_field_default[$o] =		$rowxd->field_default;;
							$A_field_cost[$o] =		$rowxd->field_cost;
							$A_field_required[$o] =		$rowxd->field_required;
							$A_multi_position[$o] =		$rowxd->multi_position;
							$A_name_position[$o] =		$rowxd->name_position;
							$A_field_order[$o] =		$rowxd->field_order;
							
							$o++;
							$rank_select .= "<option>$o</option>";
							}

						$o=0;
						while ($fields_to_print > $o) 
							{
							$new_field_exists=0;
							if ($table_exists > 0)
								{
								$stmt="SELECT count(*) as cntflists  from vicidial_lists_fields where list_id='$list_id' and field_label='$A_field_label[$o]';";
								$rslt=$this->customdialerdb->query($stmt);
								$fieldscount_to_print = $rslt->num_rows();
								
								if ($fieldscount_to_print > 0) 
									{
									$rowx = $rslt->row();
									$new_field_exists = $rowx->cntflists;
									}
								}
								if ($new_field_exists < 1)
								{
								### add field function
								$this->golist->copyaddcustomfield($table_exists,$A_field_id[$o],$list_id,$A_field_label[$o],$A_field_name[$o],$A_field_description[$o],$A_field_rank[$o],$A_field_help[$o],$A_field_type[$o],$A_field_options[$o],$A_field_size[$o],$A_field_max[$o],$A_field_default[$o],$A_field_required[$o],$A_field_cost[$o],$A_multi_position[$o],$A_name_position[$o],$A_field_order[$o],$vicidial_list_fields);

								#echo "SUCCESS: Custom Field Added - $list_id|$A_field_label[$o]\n<BR>";

								if ($table_exists < 1) {$table_exists=1;}
								}
							$o++;
							}
						
					}
					##### END APPEND option #####
					
					##### UPDATE option #####
					if ($copy_option=='UPDATE') {
					    if ($table_exists < 1)
					    { 
					      echo "ERROR: Table does not exist custom_$list_id\n<BR>";
					      
					    } else {
					     
						$stmt="SELECT field_id,field_label,field_name,field_description,field_rank,field_help,field_type,field_options,field_size,field_max,field_default,field_cost,field_required,multi_position,name_position,field_order from vicidial_lists_fields where list_id='$source_list_id' order by field_rank,field_order,field_label;";
						
						$rsltupdate = $this->customdialerdb->query($stmt);
						$fields_to_print = $rsltupdate->num_rows();
						
						$fields_list='';
						$o=0;
						foreach($rsltupdate->result() as $rowxds) 
							{
														
							$A_field_id[$o] =			$rowxds->field_id;
							$A_field_label[$o] =		$rowxds->field_label;
							$A_field_name[$o] =		$rowxds->field_name;
							$A_field_description[$o] =			$rowxds->field_description;				$A_field_rank[$o] =			$rowxds->field_rank;
							$A_field_help[$o] =			$rowxds->field_help;
							$A_field_type[$o] =			$rowxds->field_type;
							$A_field_options[$o] =		$rowxds->field_options;
							$A_field_size[$o] =			$rowxds->field_size;
							$A_field_max[$o] =			$rowxds->field_max;
							$A_field_default[$o] =		$rowxds->field_default;;
							$A_field_cost[$o] =		$rowxds->field_cost;
							$A_field_required[$o] =		$rowxds->field_required;
							$A_multi_position[$o] =		$rowxds->multi_position;
							$A_name_position[$o] =		$rowxds->name_position;
							$A_field_order[$o] =		$rowxds->field_order;
							
							$o++;
							
							}

						$o=0;
						while ($fields_to_print > $o) 
							{
							$stmtfg="SELECT field_id from vicidial_lists_fields where list_id='$list_id' and field_label='$A_field_label[$o]';";
							
							$rsltfg=$this->customdialerdb->query($stmtfg);
							$fieldscount_to_print = $rsltfg->num_rows();
							
							if ($fieldscount_to_print > 0) 
								{
								
								$rowxf = $rsltfg->row();
								$current_field_id = $rowxf->field_id;

								### modify field function
								$this->golist->copymodifycustomfield($table_exists,$current_field_id,$list_id,$A_field_label[$o],$A_field_name[$o],$A_field_description[$o],$A_field_rank[$o],$A_field_help[$o],$A_field_type[$o],$A_field_options[$o],$A_field_size[$o],$A_field_max[$o],$A_field_default[$o],$A_field_required[$o],$A_field_cost[$o],$A_multi_position[$o],$A_name_position[$o],$A_field_order[$o],$vicidial_list_fields);

								
								}
							$o++;
							}
					    }
					  }
					
					#UPDATE
	
				}
			
			}
			//echo "success milo $souce_list_id $to_list_id $copy_option";
		}	
	
	
	
	
?>
