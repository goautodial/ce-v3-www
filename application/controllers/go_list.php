<?php
########################################################################################################
####  Name:             	go_list.php                                                         ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Jerico James Milo				            	    ####
####  Edited by:		GoAutoDial Development Team					    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_list extends Controller{
    function __construct(){
        parent::Controller();
        $this->load->model('golist');
        $this->load->model('go_dashboard');
        $this->asteriskDB = $this->load->database('dialerdb',TRUE); 
	$this->load->library(array('session','userhelper','commonhelper'));
	$this->load->library("pagination");
	$this->lang->load('userauth', $this->session->userdata('ua_language'));
	$this->is_logged_in();
    }

    function index() {

        /* load models */
        $this->asteriskDB = $this->load->database('dialerdb',TRUE);
        
        $this->load->model('golist');
        $this->load->model('go_dashboard');

        $groupId = $this->go_dashboard->go_get_groupid();

            if (!$this->commonhelper->checkIfTenant($groupId)) {
               $ul='';
            } else {
               $allowedcampaign = $this->go_dashboard->go_getall_allowed_campaigns();
               $ul = " WHERE campaign_id IN ('$allowedcampaign') ";
            }

                $callfunc = $this->go_dashboard->go_get_userfulname();
                $data['userfulname'] = $callfunc;


 //    $callfunc = $this->go_reports->go_get_userfulname();
 //    $data['userfulname'] = $callfunc;
        $data['gouser'] = $this->session->userdata('user_name');
        $data['gopass'] = $this->session->userdata('user_pass');
        $data['theme'] = $this->session->userdata('go_theme');
        $data['adm']= 'wp-has-current-submenu';
        $data['hostp'] = $_SERVER['SERVER_ADDR'];
        $data['foldlink'] = '';
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';
        $data['bannertitle'] = $this->lang->line('go_lists_banner');
        $data['folded'] = 'folded';
        $data['go_main_content'] = 'go_list/go_list'; 
        $data['users'] = $users;
        $data['usergroups'] = $usergroups;
        $data['account'] = $account;
        $data['gowizard'] = "gowizard";

        $val = "";

		 /* for multi tenant  $RLIKEaccnt = "OR list_name RLIKE '$PHP_AUTH_USER'"; */
        /* get functions */
        $userlevel = $this->golist->getuserlevel($data['gouser']);
        
        
	/* pagination */
	$search = $this->input->post('search_list');
	if (strlen($search) > 2) {
	    $typeofsearch = $this->input->post('typeofsearch');
	    if ($typeofsearch=="lists") {
	        $addedSQL = (strlen($ul) > 0) ? "$ul AND (list_id RLIKE '$search' OR list_name RLIKE '$search') " : " WHERE (list_id RLIKE '$search' OR list_name RLIKE '$search') ";
		$addedSQLcustom = $ul;
	    }
	    if ($typeofsearch=="custom") {
	        $addedSQLcustom = (strlen($ul) > 0) ? "$ul AND (vl.list_id RLIKE '$search' OR list_name RLIKE '$search') " : " WHERE (vl.list_id RLIKE '$search' OR list_name RLIKE '$search') ";
	        $addedSQL = $ul;
	    }
	    $data['search'] = $search;
	} else {
	    $addedSQL = $ul;
	    $addedSQLcustom = $ul;
	    $typeofsearch = $this->uri->segment(3);
	    if ($typeofsearch=="lists") {
		$page = $this->uri->segment(4);
	    }
	    if ($typeofsearch=="custom") {
		$page2 = $this->uri->segment(4);
	    }
	}

	$urllink = base_url() . "go_list/go_list";
	$baseURL = base_url();
        $total  = $this->golist->countlist($addedSQL);
	$rp     = ($this->uri->segment(3)=='ALL') ? $total : 25;
        $limit  = 5;
	if (is_null($page) || $page < 1)
	    $page = 1;
        $pg     = $this->commonhelper->paging($page,$rp,$total,$limit);
	$start = (($page-1) * $rp);
	if ($pg['last'] > 1) {
	    $pagelinks  = '<div style="cursor: pointer;font-weight: bold;">';
		    $pagelinks .= '<a href="'.$urllink.'/lists/'.$pg['first'].'#tabs-1" title="Go to First Page" style="vertical-align:baseline;padding: 0px 2px;" ><span><img src="'.base_url().'/img/first.gif"></span></a>';
		    $pagelinks .= '<a href="'.$urllink.'/lists/'.$pg['prev'].'#tabs-1" title="Go to Previous Page" style="vertical-align:baseline;padding: 0px 2px;" ><span><img src="'.base_url().'/img/prev.gif"></span></a>';

            for ($i=$pg['start'];$i<=$pg['end'];$i++) {
		if ($i==$pg['page']) $current = 'color: #F00;cursor: default;'; else $current="";
		    $pagelinks .= '<a href="'.$urllink.'/lists/'.$i.'#tabs-1" title="Go to Page '.$i.'" style="vertical-align:text-top;padding: 0px 2px;'.$current.'"><span>'.$i.'</span></a>';
	    }

		    //$pagelinks .= '<a href="'.$urllink.'/lists/ALL#tabs-1" title="View All Pages" style="vertical-align:text-top;padding: 0px 2px;"><span>ALL</span></a>';
		    $pagelinks .= '<a href="'.$urllink.'/lists/'.$pg['next'].'#tabs-1" title="Go to Next Page" style="vertical-align:baseline;padding: 0px 2px;"><span><img src="'.base_url().'/img/next.gif"></span></a>';
		    $pagelinks .= '<a href="'.$urllink.'/lists/'.$pg['last'].'#tabs-1" title="Go to Last Page" style="vertical-align:baseline;padding: 0px 2px;"><span><img src="'.base_url().'/img/last.gif"></span></a>';
	    $pagelinks .= '</div>';
	} else {
	    if ($rp > 25) {
		$pagelinks  = '<div style="cursor: pointer;font-weight: bold;">';
		$pagelinks .= '<a href="'.$baseURL.'go_list#tabs-1" title="Back to Paginated View" style="vertical-align:text-top;padding: 0px 2px;"><span>BACK</span></a>';
		$pagelinks .= '</div>';
	    } else {
		$pagelinks = "";
	    }
	}
	$pageinfo = "<span style='float:right;'>Displaying {$pg['istart']} to {$pg['iend']} of {$pg['total']} list ids</span>";
	
	#$ingrouplists = $this->goingroup->getallingroup($accounts, $userlevel,$rp,$start);
	$lists = $this->golist->getalllist($addedSQL,$allowedcampaign,$rp,$start);
	$listIDs = $this->golist->getalllist($addedSQL,$allowedcampaign,$rp,$start,true);
	
	//if($pg['istart']<0) {
	//    $pagelinks .= "Displaying {$pg['iend']} of {$pg['total']} Lists";
	//} else {
	//    $pagelinks .= "Displaying {$pg['istart']} to {$pg['iend']} of {$pg['total']} Lists";
	//}
	
	$data["pagelinks"]["lists"] = $pagelinks;
	$data["pageinfo"]["lists"] = $pageinfo;
	
	
        # global dispo
        $all_dispos = array(""=>"--- SELECT A DISPOSITION ---");
        $gl_dispos = $this->golist->asteriskDB->get('vicidial_statuses')->result();
        foreach($gl_dispos as $dispo){
            $all_dispos[$dispo->status] = "{$dispo->status} - {$dispo->status_name}";
        }
        $campaigns = $this->commonhelper->getallowablecampaign($this->session->userdata('user_group')); 
        if(!empty($campaigns)){
            $this->golist->asteriskDB->where_in("campaign_id",$campaigns);
            $camp_dispos = $this->golist->asteriskDB->get('vicidial_campaign_statuses')->result();
            if(!empty($camp_dispos)){
                foreach($camp_dispos as $dispo){
                   $all_dispos[$dispo->status] = "{$dispo->status} - {$dispo->status_name}";
                }
            }
        }
        $data['dispos'] = $all_dispos;
	
        
	/* end pagination */
	
	
	
        $campaigns = $this->golist->getcampaign($ul);
        //$accounts = $this->golist->getaccounts($data['gouser']);
        $systemlookup = $this->golist->systemsettingslookup();
        foreach($systemlookup as $sysinfo){
				$use_non_latin = $sysinfo->use_non_latin;
				$admin_web_directory = $sysinfo->admin_web_directory;
				$custom_fields_enabled = $sysinfo->custom_fields_enabled;
			}

	     /* autocreate */ 
			$stage = $this->input->get('stage');
			$accnt = $this->input->get('accnt');
			
			//foreach($accounts as $accountsinfo){
			//	$accnt = $accountsinfo->account_num;
			//}

 	     	
	    /*$genlist = $this->golist->autogenlist($accnt);
	    $data['allmine'] = $genlist;*/
	    //$this->load->view('go_list/go_db_query',$data);
	    //$this->load->view('includes/go_dashboard_template.php',$data);
	    $vicidial_list_fields = "|list_id|vendor_lead_code|source_id|phone_code|phone_number|title|first_name|middle_initial|last_name|address1|address2|address3|city|state|province|postal_code|country_code|gender|date_of_birth|alt_phone|email|security_phrase|comments|rank|owner|status|";

	     /* end auto create */

   	  /* insert */ 
	     $addSUBMIT = $this->input->post('addSUBMIT');
	     $list_id = $this->input->post('list_id');
	     $list_name = $this->input->post('list_name');
	     $list_description = $this->input->post('list_description');
	     $active = $this->input->post('active');
	     $campaign_ids = $this->input->post('campaign_ids');

        if($addSUBMIT) {
	     $campaign_id = $this->input->post('campaign_id');
        	
		      $message = $this->golist->insertlist($list_id,$list_name,$campaign_id,$active,$list_description);
                 if($message !=null) {
                        print "<script type=\"text/javascript\">alert('$message');</script>";
                 } else {
                        header("Location: #");
                         }

		  }
		  /* end insert */		  
		  
		  /* edit */
		  $editSUBMIT = $this->input->post('editSUBMIT');
		  $editparam = $this->input->post('editparam');
		  $listidparam = $this->input->post('listidparam');
		  
		 /* $editparam = $this->uri->segment(3);
		  $listidparam = $this->uri->segment(4);*/
		  
		  if($editparam=='editLIST') {
				//$listvalues = $this->golist->geteditvalues($listidparam);
				//$data['listvalues'] = $listvalues;
				
			//$this->load->view('go_list/go_db_query',$data);
		  }

		  $editlist = $this->input->post('editlist');
		  if($editlist=='editlist') {
				
					$listid = $this->input->post('list_id');
					$listname = $this->input->post('list_name');
					$campaign_id = $this->input->post('campaign_id');
					$active = $this->input->post('active');
					$list_description = $this->input->post('list_description');
					$list_changedate = $this->input->post('list_changedate');
					$list_lastcalldate = $this->input->post('list_lastcalldate');
					$reset_time = $this->input->post('reset_time');
					$agent_script_override = $this->input->post('agent_script_override');
					$campaign_cid_override = $this->input->post('campaign_cid_override');
					$am_message_exten_override = $this->input->post('am_message_exten_override');
					$drop_inbound_group_override = $this->input->post('drop_inbound_group_override');
					$xferconf_a_number = $this->input->post('xferconf_a_number');
					$xferconf_b_number = $this->input->post('xferconf_b_number');
					$xferconf_c_number = $this->input->post('xferconf_c_number');
					$xferconf_d_number = $this->input->post('xferconf_d_number');
					$xferconf_e_number = $this->input->post('xferconf_e_number');
					$web_form_address = $this->input->post('web_form_address');
					$web_form_address_two = $this->input->post('web_form_address_two');						  	
		  	
		 //$this->golist->editvalues($listid,$listname,$campaign_id,$active,$list_description,$list_changedate,$list_lastcalldate,$reset_time,$agent_script_override,$campaign_cid_override,$am_message_exten_override,$drop_inbound_group_override,$xferconf_a_number,$xferconf_b_number,$xferconf_c_number,$xferconf_d_number,$xferconf_e_number,$web_form_address,$web_form_address_two);
		 //$this->golist->geteditvalues($listidparam); 			
		  }
		  /* end edit */
		  
		  	/* load leads */
		  	
			/* POST VALUES */			
			$phonedoces = $this->golist->getphonecodes();	
			$leadsload = $this->input->post('leadsload');
			$lead_file = $this->input->post('leadfile');
			$list_id_override = $this->input->post('list_id_override');
			$phone_code_override = $this->input->post('phone_code_override');
			$dupcheck = $this->input->post('dupcheck');
			
			$data['leadsload'] = $leadsload;
			
			$vendor_lead_code_field =		$this->input->post('vendor_lead_code_field');
			$source_code_field =			$this->input->post('source_id_field');
			//$source_id=$source_code;
			$list_id_field =				$this->input->post('list_id_field');
			$gmt_offset =			'0';
			$called_since_last_reset='N';
			$phone_code_field =			eregi_replace("[^0-9]", "", $this->input->post('phone_code_field'));
			$phone_number_field =			eregi_replace("[^0-9]", "", $this->input->post('phone_number_field'));
			$title_field =				$this->input->post('title_field');
			$first_name_field =			$this->input->post('first_name_field');
			$middle_initial_field =		$this->input->post('middle_initial_field');
			$last_name_field =			$this->input->post('last_name_field');
			$address1_field =				$this->input->post('address1_field');
			$address2_field =				$this->input->post('address2_field');
			$address3_field =				$this->input->post('address3_field');
			$city_field =	$this->input->post('city_field');
			$state_field =				$this->input->post('state_field');
			$province_field =				$this->input->post('province_field');
			$postal_code_field =			$this->input->post('postal_code_field');
			$country_code_field =			$this->input->post('country_code_field');
			$gender_field =				$this->input->post('gender_field');
			$date_of_birth_field =		$this->input->post('date_of_birth_field');
			$alt_phone_field =			eregi_replace("[^0-9]", "", $this->input->post('alt_phone_field'));
			$email_field =				$this->input->post('email_field');
			$security_phrase_field =		$this->input->post('security_phrase_field');
			$comments_field =				trim($this->input->post('comments_field'));
			$rank_field =					$this->input->post('rank_field');
			$owner_field =				$this->input->post('owner_field');
			
			

			if($leadsload=="ok") {
				// Update time on vicidial_campaign
				$NOW=date("Y-m-d H:i:s");
				$listID = $this->input->post('list_id_override');
				$query = $this->asteriskDB->query("SELECT campaign_id FROM vicidial_lists WHERE list_id='$listID'");
				$campaign_id = $query->row()->campaign_id;
				$query = $this->asteriskDB->query("UPDATE vicidial_campaigns SET campaign_changedate='$NOW' WHERE campaign_id='$campaign_id'");

					$data['tabvalsel'] = "tabloadleads";
					//extraction
					$loginuser = $this->session->userdata('user_name');
					$leadfile_name = $this->input->post('leadfile_name');
					$phone_code_override = $this->input->post('phone_code_override');
					$leadfile = $this->input->POST('leadfile');
					$file_layout = $this->input->post('file_layout');
					$dupcheck = $this->input->post('dupcheck');
					$LF_path = $_FILES['leadfile']['tmp_name'];
					
					$leadfile = $_FILES["leadfile"];
					$lead_filename=$leadfile['name'];
						
					
					$data['dupcheck'] = $dupcheck;
					$data['leadfile'] = $leadfile;
					$data['leadfile_name'] = $leadfile_name;
					$data['list_id_override'] = $list_id_override;
					$data['phone_code_override'] = $phone_code_override;
					
					$systemlookup = $this->golist->systemsettingslookup();
					
					foreach($systemlookup as $sysinfo){
						$use_non_latin = $sysinfo->use_non_latin;
						$admin_web_directory = $sysinfo->admin_web_directory;
						$custom_fields_enabled = $sysinfo->custom_fields_enabled;
					}					
				
					$US='_';					
					$delim_set=0;
					$lead_filename=$leadfile['name'];
					$filenaming = explode(".", strtolower($lead_filename));
					$existingfile=array("csv","xls","xlsx","ods","sxc");
					$lead_filename = str_replace (" ", "", $lead_filename);
					$leadfile_name = $lead_filename;
					
					if (preg_match("/\.csv$|\.xls$|\.xlsx$|\.ods$|\.sxc$/i", $leadfile_name)) { 
						
						$leadfile_name = $lead_filename;
						copy($LF_path, "/tmp/$leadfile_name");
						//$new_filename = $loginuser."_".preg_replace("/\.csv$|\.xls$|\.xlsx$|\.ods$|\.sxc$/i", '.txt', $leadfile_name);
						$new_filename = $loginuser."_".preg_replace("/\.csv$|\.xls$|\.xlsx$|\.ods$|\.sxc$/i", '.txt', $leadfile_name);
						$WeBServeRRooT = $_SERVER['DOCUMENT_ROOT'];
						$admin_web_directory = "application/views/go_list";
						$convert_command = "$WeBServeRRooT/$admin_web_directory/sheet2tab.pl /tmp/$leadfile_name /tmp/$new_filename";
						
						passthru("$convert_command");
						$lead_file = "/tmp/$new_filename";
						$data['lead_file'] = $lead_file;
						

						if (preg_match("/\.csv$/i", $leadfile_name)) {$delim_name="CSV: Comma Separated Values";}
						if (preg_match("/\.xls$/i", $leadfile_name)) {$delim_name="XLS: MS Excel 2000-XP";}
						if (preg_match("/\.xlsx$/i", $leadfile_name)) {$delim_name="XLSX: MS Excel 2007+";}
						if (preg_match("/\.ods$/i", $leadfile_name)) {$delim_name="ODS: OpenOffice.org OpenDocument Spreadsheet";}
						if (preg_match("/\.sxc$/i", $leadfile_name)) {$delim_name="SXC: OpenOffice.org First Spreadsheet";}
						$delim_set=1;
						
						
					} else {
					
						//copy($LF_path, "/tmp/".$loginuser."_vicidial_temp_file.txt");
						//$lead_file = "/tmp/".$loginuser."_vicidial_temp_file.txt";
						copy($LF_path, "/tmp/".$loginuser."_".$leadfile_name.".txt");
                                                $lead_file = "/tmp/".$loginuser."_".$leadfile_name.".txt";

					}
					
					$file=fopen("$lead_file", "r");
					$buffer=fgets($file, 4096);
					$tab_count=substr_count($buffer, "\t");
					$pipe_count=substr_count($buffer, "|");

					if ($delim_set < 1) {
						if ($tab_count>$pipe_count) {
								$delim_name="tab-delimited";
						} else {
								$delim_name="pipe-delimited";
						}
					} 
		
					if ($tab_count>$pipe_count){
							$delimiter="\t";
					} else {
							$delimiter="|";
					}

					$field_check=explode($delimiter, $buffer);
					flush();
					$file=fopen("$lead_file", "r");
						$data['msg1'] = "<center><font face='arial, helvetica' size=3 color='#009900'><B>Processing $delim_name file...\n";
					
					if (strlen($list_id_override)>0) {
						$data['msg2'] = "<BR><BR>LIST ID OVERRIDE FOR THIS FILE: $list_id_override<BR><BR>";
					}
					
					$buffer=rtrim(fgets($file, 4096));
					$buffer=stripslashes($buffer);
					$row=explode($delimiter, eregi_replace("[\'\"]", "", $buffer));
					$data['fieldrow'] = $row;

								// Changed the $standard_SQL fields -- Chris Lomuntad <chris@goautodial.com>
                                //$standard_SQL = "list_id, phone_code, phone_number, first_name, middle_initial, last_name, address1, city, state, postal_code, alt_phone, email, comments";
				$standard_SQL = "list_id, vendor_lead_code, source_id, phone_code, phone_number, title, first_name, middle_initial, last_name, address1, address2, address3, city, state, province, postal_code, country_code, gender, date_of_birth, alt_phone, email, security_phrase, comments, rank, owner";
                                $table_SQL = "vicidial_list";

                                if ($custom_fields_enabled > 0)
                                {
                                        $query = $this->asteriskDB->query("SHOW TABLES LIKE 'custom_".$list_id_override."'");
                                        if ($query->num_rows() > 0)
                                        {
                                                $query = $this->asteriskDB->query("SELECT count(*) AS cnt FROM vicidial_lists_fields WHERE list_id='".$list_id_override."'");
                                                $fields_cnt = $query->row();
                                                if ($fields_cnt->cnt > 0)
                                                {
                                                        $custom_SQL='';
                                                        $query = $this->asteriskDB->query("SELECT field_id,field_label,field_name,field_description,field_rank,field_help,field_type,field_options,field_size,field_max,field_default,field_cost,field_required,multi_position,name_position,field_order from vicidial_lists_fields where list_id='".$list_id_override."' order by field_rank,field_order,field_label");
                
                                                        foreach ($query->result() as $i => $row)
                                                        {
                                                                $field_label[$i]        = $row->field_label;
                                                                $field_type[$i]         = $row->field_type;
                
                                                                if ( ($field_type[$i]!='DISPLAY') and ($field_type[$i]!='SCRIPT') )
                                                                {
                                                                        if (!preg_match("/\|$field_label[$i]\|/",$vicidial_list_fields))
                                                                        {
                                                                        $custom_SQL .= ",$field_label[$i]";
                                                                        }
                                                                }
                                                        }
                                                }
                                                $table_SQL = "vicidial_list, custom_".$list_id_override;
                                        }
                                }
                                $query = $this->asteriskDB->query("SELECT $standard_SQL $custom_SQL FROM $table_SQL limit 1");
                                $fields = $query->list_fields();

					$data['fields'] = $fields;
					$data['delim_name'] = $delim_name;
						 
			} // end if load leads
					
					
					
			if($leadsload=="okfinal") {
					$dupcheck = $this->input->post('dupcheck');
					$leadsload = $this->input->post('leadsload');
					$lead_file = $this->input->post('lead_file');
					$leadfile = $this->input->post('leadfile');
					$leadfile_name = $this->input->post('leadfile_name');
					$list_id_override = $this->input->post('list_id_override');
					$phone_code_override = $this->input->post('phone_code_override');
					
							
							flush();
							$total=0; $good=0; $bad=0; $dup=0; $post=0; $phone_list='';
				
							$file=fopen("$lead_file", "r");
				
							$buffer=fgets($file, 4096);
							$tab_count=substr_count($buffer, "\t");
							$pipe_count=substr_count($buffer, "|");
				
							if ($tab_count>$pipe_count) {$delimiter="\t";  $delim_name="tab";} else {$delimiter="|";  $delim_name="pipe";}
							$field_check=explode($delimiter, $buffer);
				
							 	if (count($field_check)>=2) {
									flush();
									$file=fopen("$lead_file", "r");
									//$data['processfile'] = "<center><font face='arial, helvetica' size=3 color='#009900'><B>Processing file...\n";
				
									if (strlen($list_id_override)>0) {
									//print "<BR><BR>LIST ID OVERRIDE FOR THIS FILE: $list_id_override<BR><BR>";
									}
				
									if (strlen($phone_code_override)>0) {
									//print "<BR><BR>PHONE CODE OVERRIDE FOR THIS FILE: $phone_code_override<BR><BR>";
									}
				
									$systemlookup = $this->golist->systemsettingslookup();
									foreach($systemlookup as $sysinfo){
										$use_non_latin = $sysinfo->use_non_latin;
										$admin_web_directory = $sysinfo->admin_web_directory;
										$custom_fields_enabled = $sysinfo->custom_fields_enabled;
									}
										
							
									if ($custom_fields_enabled > 0) {
											$tablecount_to_print=0;
											$fieldscount_to_print=0;
											$fields_to_print=0;
				
											$stmt="SHOW TABLES LIKE \"custom_$list_id_override\";";
											$rslt = $this->asteriskDB->query($stmt);
											$tablecount_to_print = $rslt->num_rows;
				
											if ($tablecount_to_print > 0) {
													$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$list_id_override';";
													$rslt = $this->asteriskDB->query($stmt);
													$fieldscount_to_print = $rslt->num_rows;
							
													if ($fieldscount_to_print > 0) {
														$stmt="SELECT field_label,field_type from vicidial_lists_fields where list_id='$list_id_override' order by field_rank,field_order,field_label;";
				
														$rslt = $this->asteriskDB->query($stmt);
														$fields_to_print = $rslt->num_rows;
				
														$fields_list='';
									
														foreach ($rslt->result() as $o => $rowx) {
															$A_field_label[$o] =	$rowx->field_label;
															$A_field_type[$o] =	$rowx->field_type;
															$A_field_value[$o] =	'';
														}
													}
											}
									}
										
									
									while (!feof($file)) {
											$record++;
											$buffer=rtrim(fgets($file, 4096));
											$buffer=stripslashes($buffer);
						
											if (strlen($buffer)>0) {
												$row=explode($delimiter, eregi_replace("[\'\"]", "", $buffer));
												$lrow=$row;
													
													$pulldate=date("Y-m-d H:i:s");
													$entry_date =			"$pulldate";
													$modify_date =			"";
													$status =				"NEW";
													$user ="";
													$vendor_lead_code =		$row[$vendor_lead_code_field];
													$source_code =			$row[$source_id_field];
													$source_id=$source_code;
													$list_id =				$row[$list_id_field];
													$gmt_offset =			'0';
													$called_since_last_reset='N';
													$phone_code =			eregi_replace("[^0-9]", "", $row[$phone_code_field]);
													$phone_number =			eregi_replace("[^0-9]", "", $row[$phone_number_field]);
													$title =				$row[$title_field];
													$first_name =			$row[$first_name_field];
													$middle_initial =		$row[$middle_initial_field];
													$last_name =			$row[$last_name_field];
													$address1 =				$row[$address1_field];
													$address2 =				$row[$address2_field];
													$address3 =				$row[$address3_field];
													$city =$row[$city_field];
													$state =				$row[$state_field];
													$province =				$row[$province_field];
													$postal_code =			$row[$postal_code_field];
													$country_code =			$row[$country_code_field];
													$gender =				$row[$gender_field];
													$date_of_birth =		$row[$date_of_birth_field];
													$alt_phone =			eregi_replace("[^0-9]", "", $row[$alt_phone_field]);
													$email =				$row[$email_field];
													$security_phrase =		$row[$security_phrase_field];
													$comments =				trim($row[$comments_field]);
													$rank =					$row[$rank_field];
													$owner =				$row[$owner_field];
													
													### REGEX to prevent weird characters from ending up in the fields
													$field_regx = "['\"`\\;]";
													
													
													
													# replace ' " ` \ ; with nothing
													$vendor_lead_code =		eregi_replace($field_regx, "", $vendor_lead_code);
													$source_code =			eregi_replace($field_regx, "", $source_code);
													$source_id = 			eregi_replace($field_regx, "", $source_id);
													$list_id =				eregi_replace($field_regx, "", $list_id);
													$phone_code =			eregi_replace($field_regx, "", $phone_code);
													$phone_number =			eregi_replace($field_regx, "", $phone_number);
													$title =				eregi_replace($field_regx, "", $title);
													$first_name =			eregi_replace($field_regx, "", $first_name);
													$middle_initial =		eregi_replace($field_regx, "", $middle_initial);
													$last_name =			eregi_replace($field_regx, "", $last_name);
													$address1 =				eregi_replace($field_regx, "", $address1);
													$address2 =				eregi_replace($field_regx, "", $address2);
													$address3 =				eregi_replace($field_regx, "", $address3);
													$city =					eregi_replace($field_regx, "", $city);
													$state =				eregi_replace($field_regx, "", $state);
													$province =				eregi_replace($field_regx, "", $province);
													$postal_code =			eregi_replace($field_regx, "", $postal_code);
													$country_code =			eregi_replace($field_regx, "", $country_code);
													$gender =				eregi_replace($field_regx, "", $gender);
													$date_of_birth =		eregi_replace($field_regx, "", $date_of_birth);
													$alt_phone =			eregi_replace($field_regx, "", $alt_phone);
													$email =				eregi_replace($field_regx, "", $email);
													$security_phrase =		eregi_replace($field_regx, "", $security_phrase);
													$comments =				eregi_replace($field_regx, "", $comments);
													$rank =					eregi_replace($field_regx, "", $rank);
													$owner =				eregi_replace($field_regx, "", $owner);
													
													$USarea = 			substr($phone_number, 0, 3);
													
													if (strlen($list_id_override)>0) {
														#	print "<BR><BR>LIST ID OVERRIDE FOR THIS FILE: $list_id_override<BR><BR>";
														$list_id = $list_id_override;
													}
													if (strlen($phone_code_override)>0) {
														$phone_code = $phone_code_override;
													}
													
														##### BEGIN custom fields columns list ###
														$custom_SQL='';
														if ($custom_fields_enabled > 0)
															{
															if ($tablecount_to_print > 0) 
																{
																if ($fieldscount_to_print > 0)
																	{
																	$o=0;
																	while ($fields_to_print > $o) 
																		{
																		$A_field_value[$o] =	'';
																		$field_name_id = $A_field_label[$o] . "_field";
										
																	#	if ($DB>0) {echo "$A_field_label[$o]|$A_field_type[$o]\n";}
										
																		if ( ($A_field_type[$o]!='DISPLAY') and ($A_field_type[$o]!='SCRIPT') )
																			{
																			if (!preg_match("/\|$A_field_label[$o]\|/",$vicidial_list_fields))
																				{
																				if (isset($_GET["$field_name_id"]))				{$form_field_value=$_GET["$field_name_id"];}
																					elseif (isset($_POST["$field_name_id"]))	{$form_field_value=$_POST["$field_name_id"];}
										
																				if ($form_field_value >= 0)
																					{
																					$A_field_value[$o] =	$row[$form_field_value];
																					# replace ' " ` \ ; with nothing
																					$A_field_value[$o] =	eregi_replace($field_regx, "", $A_field_value[$o]);
										
																					$custom_SQL .= "$A_field_label[$o]='$A_field_value[$o]',";
																					}
																				}
																			}
																		$o++;
																		}
																	}
																}
															}
														##### END custom fields columns list ###
										
														$custom_SQL = preg_replace("/,$/","",$custom_SQL);
													
													## checking duplicate portion
													
													##### Check for duplicate phone numbers in vicidial_list table for all lists in a campaign #####
													if ($dupcheck=='DUPCAMP') {
														
														$dup_lead=0;
														$dup_lists='';
														$stmt="select campaign_id from vicidial_lists where list_id='$list_id';";
														
														$rslt = $this->asteriskDB->query($stmt);
														$ci_recs = $rslt->num_rows;
														
														if ($ci_recs > 0) {
															$row = $rslt->row();
															$dup_camp = $row->campaign_id;
															$stmt="select list_id from vicidial_lists where campaign_id='$dup_camp';";
														
															$rslt = $this->asteriskDB->query($stmt);
															$li_recs = $rslt->num_rows;
															
															if ($li_recs > 0) 	{
																$L=0;
																foreach ($rslt->result() as $L => $row) {
																	$dup_lists .=	"'$row->list_id',";
																	$L++;
 																}
 															
																$dup_lists = eregi_replace(",$",'',$dup_lists);
									
																$stmt="select list_id from vicidial_list where phone_number='$phone_number' and list_id IN($dup_lists) limit 1;"; 
																$rslt = $this->asteriskDB->query($stmt);
																$pc_recs = $rslt->num_rows;
																		
																if ($pc_recs > 0) {
																	$dup_lead=1;
																	$row = $rslt->row();
																	$dup_lead_list = $row->list_id;
																
																}
																if ($dup_lead < 1) {
																	if (eregi("$phone_number$US$list_id",$phone_list))
																		{$dup_lead++; $dup++;}
																	}
																}
															}
													}
													
													
													##### Check for duplicate phone numbers in vicidial_list table entire database #####
													if (eregi("DUPSYS",$dupcheck)) {
														$dup_lead=0;
														$stmt="select list_id from vicidial_list where phone_number='$phone_number';";
														$rslt = $this->asteriskDB->query($stmt);
														$pc_recs = $rslt->num_rows;
														
														if ($pc_recs > 0) {
															$dup_lead=1;
															$row = $rslt->row();
															$dup_lead_list = $row->list_id;
														}
														
														if ($dup_lead < 1) {
															if (eregi("$phone_number$US$list_id",$phone_list))
																{$dup_lead++; $dup++;}
														}
													}	
															
													##### Check for duplicate phone numbers in vicidial_list table for one list_id #####
													if (eregi("DUPLIST",$dupcheck)) {
														$dup_lead=0;
														$stmt="select count(*) from vicidial_list where phone_number='$phone_number' and list_id='$list_id';";
														$rslt = $this->asteriskDB->query($stmt);
														$pc_recs = $rslt->num_rows;

														if ($pc_recs > 0) {
															$row = $rslt->row();
															$dup_lead = $row->list_id;
															$dup_lead_list =	$list_id;
															//die($dup_lead_list);
														}
														
														if ($dup_lead < 1) {
															if (eregi("$phone_number$US$list_id",$phone_list))
																{$dup_lead++; $dup++;}
														}
														
														
													}	
													
													##### Check for duplicate title and alt-phone in vicidial_list table for one list_id #####
													if (eregi("DUPTITLEALTPHONELIST",$dupcheck))
														{
														$dup_lead=0;
														$stmt="select count(*) from vicidial_list where title='$title' and alt_phone='$alt_phone' and list_id='$list_id';";
														$rslt = $this->asteriskDB->query($stmt);
														$pc_recs = $rslt->num_rows;
														if ($pc_recs > 0) {
															$row = $rslt->row();
															$dup_lead = $row->list_id;
															$dup_lead_list =	$list_id;
														}
														if ($dup_lead < 1) {
															if (eregi("$alt_phone$title$US$list_id",$phone_list))
																{$dup_lead++; $dup++;}
															}
														}
														
													 	##### Check for duplicate phone numbers in vicidial_list table entire database #####
														if (eregi("DUPTITLEALTPHONESYS",$dupcheck)) {
															$dup_lead=0;
															$stmt="select list_id from vicidial_list where title='$title' and alt_phone='$alt_phone';";
															$rslt = $this->asteriskDB->query($stmt);
															$pc_recs = $rslt->num_rows;
															if ($pc_recs > 0) {
																$dup_lead=1;
																$row = $rslt->row();
																$dup_lead_list = $row->list_id;
															}
															if ($dup_lead < 1) {
																if (eregi("$alt_phone$title$US$list_id",$phone_list))
																	{$dup_lead++; $dup++;}
																}
														} #end check dups  
														
														
														
												
												if ( (strlen($phone_number)>6) and ($dup_lead<1) and ($list_id >= 100 )) {		
														if (strlen($phone_code)<1) {$phone_code = '1';}
														if (eregi("TITLEALTPHONE",$dupcheck)) {
															$phone_list .= "$alt_phone$title$US$list_id|";
														} else {
															$phone_list .= "$phone_number$US$list_id|";
														}
								
														$gmt_offset = $this->lookup_gmt($phone_code,$USarea,$state,$LOCAL_GMT_OFF_STD,$Shour,$Smin,$Ssec,$Smon,$Smday,$Syear,$postalgmt,$postal_code,$owner);
														
								
														if (strlen($custom_SQL)>3) {
															$stmtZ = "INSERT INTO vicidial_list (lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id) values('','$entry_date','$modify_date','$status','$user','$vendor_lead_code','$source_id','$list_id','$gmt_offset','$called_since_last_reset','$phone_code','$phone_number','$title','$first_name','$middle_initial','$last_name','$address1','$address2','$address3','$city','$state','$province','$postal_code','$country_code','$gender','$date_of_birth','$alt_phone','$email','$security_phrase','$comments',0,'2008-01-01 00:00:00','$rank','$owner','$list_id');";
															
														$rslt = $this->asteriskDB->query($stmtZ);
														$lead_id = $this->asteriskDB->insert_id();
														$affected_rows = $this->asteriskDB->affected_rows();															
																
															$multistmt='';
									
															$custom_SQL_query = "INSERT INTO custom_$list_id_override SET lead_id='$lead_id',$custom_SQL;";
															$rslt = $this->asteriskDB->query($custom_SQL_query);

															} else {
														
																if ($multi_insert_counter > 8) {
																	### insert good record into vicidial_list table ###
																	$stmtZx = "INSERT INTO vicidial_list (lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id) values$multistmt('','$entry_date','$modify_date','$status','$user','$vendor_lead_code','$source_id','$list_id','$gmt_offset','$called_since_last_reset','$phone_code','$phone_number','$title','$first_name','$middle_initial','$last_name','$address1','$address2','$address3','$city','$state','$province','$postal_code','$country_code','$gender','$date_of_birth','$alt_phone','$email','$security_phrase','$comments',0,'2008-01-01 00:00:00','$rank','$owner','0');";
											
																	$rslt = $this->asteriskDB->query($stmtZx);
																	$multistmt='';
																	$multi_insert_counter=0;
																	
																} else {
																	
																	$multistmt .= "('','$entry_date','$modify_date','$status','$user','$vendor_lead_code','$source_id','$list_id','$gmt_offset','$called_since_last_reset','$phone_code','$phone_number','$title','$first_name','$middle_initial','$last_name','$address1','$address2','$address3','$city','$state','$province','$postal_code','$country_code','$gender','$date_of_birth','$alt_phone','$email','$security_phrase','$comments',0,'2008-01-01 00:00:00','$rank','$owner','0'),";
																	$multi_insert_counter++;
																	
																}
															}
														$good++;
													} else {
														
														if ($bad < 1000000)	{
															if ( $list_id < 100 ) {
																//print "<BR></b><font size=1 color=red>record $total BAD- PHONE : $phone_number ROW: |$lrow[0]| INVALID LIST ID</font><b>\n";
															} else {
																//print "<BR></b><font size=1 color=red>record $total BAD- PHONE: $phone_number ROW: |$lrow[0]| DUP: $dup_lead  $dup_lead_list</font><b>\n";
															}
														}
														$bad++;
													}
													
													$total++;
													
//													if ($total%100==0) {
															//print "<script language='JavaScript1.2'>ShowProgress($good, $bad, $total, $dup, $post)</script>";
															
		//													 echo "<script type=\"text/javascript\">";
		//													echo "alert('good leads '+$good+'Bad leads'+$bad+' total'+$total);";
		//													echo "</script>"; 
															
															usleep(1000);
															flush();
//													}							
													
													## end checking duplicate
				
											} //end buffer if
									} // end while
							$alltotal = $total - $bad;
							echo "<script type=\"text/javascript\">";
							echo "alert('Total uploaded leads: '+$alltotal+'.. BAD PHONE: '+$bad+'');";
							echo "window.location='go_list';";
							echo "</script>";        
									
									if ($multi_insert_counter!=0) {
											$stmtZ = "INSERT INTO vicidial_list (lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id) values".substr($multistmt, 0, -1).";";
											$rslt = $this->asteriskDB->query($stmtZ);
											
									}
									    	
                                                                       $this->commonhelper->auditadmin("UPLOAD","Leads Uploaded on List ID $list_id. Total uploaded leads: $alltotal BAD PHONE: $bad");
				    		} else {
				    		
							echo "<script type=\"text/javascript\">";
							echo "alert('<B>ERROR: The file does not have the required number of fields to process it.</B>');";
							echo "</script>";						
							
		
				    		}  //dulong dulo
					
					

					$this->extgetval($lead_file, $list_id_override, $phone_code_override, $dupcheck);
//					echo "<script type=\"text/javascript\">";
//echo "window.location='go_list/#tabs-3';";
//echo "</script>";        

					
			} //end okfinal
					//$valcheck = $this->leaddupcheck($dupcheck);	*/
			
								  

        			/* pass value to views */
					$data['lists'] = $lists;
					$data['listIDs'] = $listIDs;
					$data['campaigns'] = $campaigns;
					$data['accounts'] = $accounts;
					$data['phonedoces'] = $phonedoces;		

			
		        	/*$customlist="view";
		        
					if($customlist=="view") { 
							//CUSTOM LISTS VIEW					
							$dupcheck = $this->input->post('customlist');       	
				        	$clist = $this->golist->getcustomlist();
							$data['clist'] = $clist;
				        	$data['go_main_content'] = 'go_list/go_customlist';
				        	//$this->load->view('go_list/go_customlist',$data);
				        	$this->load->view('includes/go_dashboard_template.php',$data);
					} else {*/
							//LISTS VIEW
							//die($accnt."mio");
							//var_dump($this->session->userdata); die();
							$genvalue = $this->session->userdata("user_name");
						    $genlist = $this->golist->autogenlist($genvalue);
							$data['allmine'] = $genlist;
							$this->load->view('go_list/go_db_query',$data);
       					

							$total2  = $this->golist->countlist($addedSQLcustom,'custom');
							$rp2     = ($this->uri->segment(3)=='ALL') ? $total2 : 25;
							$limit2  = 5;
							if (is_null($page2) || $page2 < 1)
							    $page2 = 1;
							$pg2     = $this->commonhelper->paging($page2,$rp2,$total2,$limit2);
							$start2  = (($page2-1) * $rp2);
							if ($pg2['last'] > 1) {
							    $pagelinks  = '<div style="cursor: pointer;font-weight: bold;">';
							    $pagelinks .= '<a href="'.$urllink.'/custom/'.$pg2['first'].'#tabs-2" title="Go to First Page" style="vertical-align:baseline;padding: 0px 2px;" ><span><img src="'.base_url().'/img/first.gif"></span></a>';
							    $pagelinks .= '<a href="'.$urllink.'/custom/'.$pg2['prev'].'#tabs-2" title="Go to Previous Page" style="vertical-align:baseline;padding: 0px 2px;" ><span><img src="'.base_url().'/img/prev.gif"></span></a>';
						
							    for ($i=$pg2['start'];$i<=$pg2['end'];$i++) {
								if ($i==$pg2['page']) $current = 'color: #F00;cursor: default;'; else $current="";
								    $pagelinks .= '<a href="'.$urllink.'/custom/'.$i.'#tabs-2" title="Go to Page '.$i.'" style="vertical-align:text-top;padding: 0px 2px;'.$current.'"><span>'.$i.'</span></a>';
							    }
						
							    $pagelinks .= '<a href="'.$urllink.'/custom/ALL#tabs-2" title="View All Pages" style="vertical-align:text-top;padding: 0px 2px;"><span>ALL</span></a>';
							    $pagelinks .= '<a href="'.$urllink.'/custom/'.$pg2['next'].'#tabs-2" title="Go to Next Page" style="vertical-align:baseline;padding: 0px 2px;"><span><img src="'.base_url().'/img/next.gif"></span></a>';
							    $pagelinks .= '<a href="'.$urllink.'/custom/'.$pg2['last'].'#tabs-2" title="Go to Last Page" style="vertical-align:baseline;padding: 0px 2px;"><span><img src="'.base_url().'/img/last.gif"></span></a>';
							    $pagelinks .= '</div>';
							} else {
							    if ($rp2 > 25) {
								$pagelinks  = '<div style="cursor: pointer;font-weight: bold;">';
								$pagelinks .= '<a href="'.$baseURL.'go_list" title="Back to Paginated View" style="vertical-align:text-top;padding: 0px 2px;"><span>BACK</span></a>';
								$pagelinks .= '</div>';
							    } else {
								$pagelinks = "";
							    }
							}
							$pageinfo = "<span style='float:right;'>Displaying {$pg2['istart']} to {$pg2['iend']} of {$pg2['total']} custom fields</span>";
							
							$data["pagelinks"]["custom"] = $pagelinks;
							$data["pageinfo"]["custom"] = $pageinfo;
					
							$clist = $this->golist->getcustomlist($addedSQLcustom,$rp2,$start2);
							$data['clist'] = $clist;
							$dropactivecustom = $this->golist->getactivecustom();
							$data['dropactivecustom'] = $dropactivecustom;
				        	$this->load->view('includes/go_dashboard_template.php',$data);
				        	

       					
//					}
			
    }
    
	function editview() {
		$this->load->view('go_list/go_values',$data);
	}
	
	function editsubmit() {
		$this->load->view('go_list/go_values',$data);
	}
	
	function deletesubmit() {
		$this->load->view('go_list/go_values',$data);
	}

	function editcustomview() {
		$this->load->view('go_list/go_values',$data);
	}

	function copycustomview() {
		$this->load->view('go_list/go_values',$data);
	}

	function go_copycustomfield() { 
		$this->load->view('go_list/go_copycustomfield',$data);
	}

	function oncheangeselect() {
		
		$listidparam = $this->uri->segment(3);
		$countfields = $this->golist->countfields($listidparam);
        	$data['countfields'] = $countfields;
		echo "<select name='field_rank' id='field_rank'>";
		echo "<option></option>";
			for ($i=1; $i <=$countfields; $i++) {
                        	echo "<option value='$i'>".$i."</option>";
                        }
                	$orderplus = $countfields + 1;
		echo "<option value='$orderplus'>".$orderplus."</option>";
                echo "</select>";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "<label class='modify-value'>Order:</label>";
                echo "<select name='field_order' id='field_order'>";
		echo "<option></option>";
                        for ($i=1; $i <=$countfields; $i++) {
				echo "<option value='$i'>".$i."</option>";
  	             	}
  			$orderplus = $countfields + 1;
		echo "<option value='$orderplus'>".$orderplus."</option>";
		echo "</select>";


	}	
	
	function editcustomlist() {
		$listidparam = $this->uri->segment(3);
		$data['listidparam'] = $listidparam;
		$data['userfulname'] = $callfunc;
        $data['gouser'] = $this->session->userdata('user_name');
        $data['gopass'] = $this->session->userdata('user_pass');
        $data['theme'] = $this->session->userdata('go_theme');
        $data['rep']= 'wp-has-current-submenu';
        $data['hostp'] = $_SERVER['SERVER_ADDR'];
        $data['foldlink'] = '';
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';
        $data['bannertitle'] = 'Lists';
        $data['folded'] = 'folded';
        $data['go_main_content'] = 'go_list/go_customlist'; 
        $data['users'] = $users;
        $data['usergroups'] = $usergroups;
        $data['account'] = $account;
        $data['gowizard'] = "gowizard";


        $groupId = $this->go_dashboard->go_get_groupid();

           if ($groupId == 'ADMIN' || $groupId == 'admin') {
               $ul='';
            } else {
               $allowedcampaign = $this->go_dashboard->go_getall_allowed_campaigns();
               $ul = " WHERE campaign_id IN ('$allowedcampaign') ";
            }

                $callfunc = $this->go_dashboard->go_get_userfulname();
                $data['userfulname'] = $callfunc;

	 $lists = $this->golist->getalllist($ul,$allowedcampaign);
	$data['lists'] = $lists;


			$custeditview = $this->golist->custeditview($listidparam);
			$countfields = $this->golist->countfields($listidparam);
		

        $data['custeditview'] = $custeditview;
        $data['countfields'] = $countfields;
		 
		$this->load->view('includes/go_dashboard_template.php',$data);
		//$this->load->view('go_list/go_customlist',$data);
		
	}
	
	



	function lookup_gmt($phone_code,$USarea,$state,$LOCAL_GMT_OFF_STD,$Shour,$Smin,$Ssec,$Smon,$Smday,$Syear,$postalgmt,$postal_code,$owner)
	{
	global $link;

	$postalgmt_found=0;
	if ( (eregi("POSTAL",$postalgmt)) && (strlen($postal_code)>4) )
		{
		if (preg_match('/^1$/', $phone_code))
			{
			$stmt="select postal_code,state,GMT_offset,DST,DST_range,country,country_code from vicidial_postal_codes where country_code='$phone_code' and postal_code LIKE \"$postal_code%\";";
//			$rslt=mysql_query($stmt, $link);
//			$pc_recs = mysql_num_rows($rslt);
			$rslt = $this->asteriskDB->query($stmt);
			$pc_recs = $rslt->num_rows;
			if ($pc_recs > 0)
				{
				$row = $rslt->row();
				$gmt_offset = $row->GMT_offset;
				$gmt_offset = eregi_replace("\+","",$gmt_offset);
				$dst = $row->DST;
				$dst_range = $row->DST_range;
			
				$PC_processed++;
				$postalgmt_found++;
				$post++;
				}
			}
		}
	if ( ($postalgmt=="TZCODE") && (strlen($owner)>1) )
		{
		$dst_range='';
		$dst='N';
		$gmt_offset=0;

		$stmt="select GMT_offset from vicidial_phone_codes where tz_code='$owner' and country_code='$phone_code' limit 1;";

//		$rslt=mysql_query($stmt, $link);
//		$pc_recs = mysql_num_rows($rslt);

                $rslt = $this->asteriskDB->query($stmt);
                $pc_recs = $rslt->num_rows;

		if ($pc_recs > 0)
			{
			$row = $rslt->row();
			$gmt_offset =	$row->GMT_offset;	 
			$gmt_offset = eregi_replace("\+","",$gmt_offset);
			$PC_processed++;
			$postalgmt_found++;
			$post++;
			}

		$stmt = "select distinct DST_range from vicidial_phone_codes where tz_code='$owner' and country_code='$phone_code' order by DST_range desc limit 1;";
		$rslt = $this->asteriskDB->query($stmt);
                $pc_recs = $rslt->num_rows;
		
		//$rslt=mysql_query($stmt, $link);
		//$pc_recs = mysql_num_rows($rslt);
		if ($pc_recs > 0)
			{
			$row = $rslt->row();
			//$dst_range =	$row[0];
			$dst_range =	$row->DST_range;
			if (strlen($dst_range)>2) {$dst = 'Y';}
			}
		}

	if ($postalgmt_found < 1)
		{
		$PC_processed=0;
		### UNITED STATES ###
		if ($phone_code =='1')
			{
			$stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$phone_code' and areacode='$USarea';";
		//	$rslt=mysql_query($stmt, $link);
		//	$pc_recs = mysql_num_rows($rslt);
		$rslt = $this->asteriskDB->query($stmt);
                $pc_recs = $rslt->num_rows;
			if ($pc_recs > 0)
				{
			$row = $rslt->row();
				$gmt_offset =	$row->GMT_offset;	 $gmt_offset = eregi_replace("\+","",$gmt_offset);
				$dst =			$row->DST;
				$dst_range =	$row->DST_range;
				$PC_processed++;
				}
			}
		### MEXICO ###
		if ($phone_code =='52')
			{
			$stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$phone_code' and areacode='$USarea';";
		/*	$rslt=mysql_query($stmt, $link);
			$pc_recs = mysql_num_rows($rslt);*/
		$rslt = $this->asteriskDB->query($stmt);
                $pc_recs = $rslt->num_rows;
			if ($pc_recs > 0)
				{
			$row = $rslt->row();
				$gmt_offset =	$row->GMT_offset;	 $gmt_offset = eregi_replace("\+","",$gmt_offset);
				$dst =			$row->DST;
				$dst_range =	$row->DST_range;
				$PC_processed++;
				}
			}
		### AUSTRALIA ###
		if ($phone_code =='61')
			{
			$stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$phone_code' and state='$state';";
		//	$rslt=mysql_query($stmt, $link);
		//	$pc_recs = mysql_num_rows($rslt);
		$rslt = $this->asteriskDB->query($stmt);
                $pc_recs = $rslt->num_rows;
			if ($pc_recs > 0)
				{
			$row = $rslt->row();
				$gmt_offset =	$row->GMT_offset;	 $gmt_offset = eregi_replace("\+","",$gmt_offset);
				$dst =			$row->DST;
				$dst_range =	$row->DST_range;
				$PC_processed++;
				}
			}
		### ALL OTHER COUNTRY CODES ###
		if (!$PC_processed)
			{
			$PC_processed++;
			$stmt="select country_code,country,areacode,state,GMT_offset,DST,DST_range,geographic_description from vicidial_phone_codes where country_code='$phone_code';";
		//	$rslt=mysql_query($stmt, $link);
		//	$pc_recs = mysql_num_rows($rslt);
		$rslt = $this->asteriskDB->query($stmt);
                $pc_recs = $rslt->num_rows;
			if ($pc_recs > 0)
				{
			$row = $rslt->row();
				$gmt_offset =	$row->GMT_offset;	 $gmt_offset = eregi_replace("\+","",$gmt_offset);
				$dst =			$row->DST;
				$dst_range =	$row->DST_range;
				$PC_processed++;
				}
			}
		}

	### Find out if DST to raise the gmt offset ###
	$AC_GMT_diff = ($gmt_offset - $LOCAL_GMT_OFF_STD);
	$AC_localtime = mktime(($Shour + $AC_GMT_diff), $Smin, $Ssec, $Smon, $Smday, $Syear);
		$hour = date("H",$AC_localtime);
		$min = date("i",$AC_localtime);
		$sec = date("s",$AC_localtime);
		$mon = date("m",$AC_localtime);
		$mday = date("d",$AC_localtime);
		$wday = date("w",$AC_localtime);
		$year = date("Y",$AC_localtime);
	$dsec = ( ( ($hour * 3600) + ($min * 60) ) + $sec );

	$AC_processed=0;
	if ( (!$AC_processed) and ($dst_range == 'SSM-FSN') )
		{
		if ($DBX) {print "     Second Sunday March to First Sunday November\n";}
		#**********************************************************************
		# SSM-FSN
		#     This is returns 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect.
		#     Based on Second Sunday March to First Sunday November at 2 am.
		#     INPUTS:
		#       mm              INTEGER       Month.
		#       dd              INTEGER       Day of the month.
		#       ns              INTEGER       Seconds into the day.
		#       dow             INTEGER       Day of week (0=Sunday, to 6=Saturday)
		#     OPTIONAL INPUT:
		#       timezone        INTEGER       hour difference UTC - local standard time
		#                                      (DEFAULT is blank)
		#                                     make calculations based on UTC time, 
		#                                     which means shift at 10:00 UTC in April
		#                                     and 9:00 UTC in October
		#     OUTPUT: 
		#                       INTEGER       1 = DST, 0 = not DST
		#
		# S  M  T  W  T  F  S
		# 1  2  3  4  5  6  7
		# 8  9 10 11 12 13 14
		#15 16 17 18 19 20 21
		#22 23 24 25 26 27 28
		#29 30 31
		# 
		# S  M  T  W  T  F  S
		#    1  2  3  4  5  6
		# 7  8  9 10 11 12 13
		#14 15 16 17 18 19 20
		#21 22 23 24 25 26 27
		#28 29 30 31
		# 
		#**********************************************************************

			$USACAN_DST=0;
			$mm = $mon;
			$dd = $mday;
			$ns = $dsec;
			$dow= $wday;

			if ($mm < 3 || $mm > 11) {
			$USACAN_DST=0;   
			} elseif ($mm >= 4 and $mm <= 10) {
			$USACAN_DST=1;   
			} elseif ($mm == 3) {
			if ($dd > 13) {
				$USACAN_DST=1;   
			} elseif ($dd >= ($dow+8)) {
				if ($timezone) {
				if ($dow == 0 and $ns < (7200+$timezone*3600)) {
					$USACAN_DST=0;   
				} else {
					$USACAN_DST=1;   
				}
				} else {
				if ($dow == 0 and $ns < 7200) {
					$USACAN_DST=0;   
				} else {
					$USACAN_DST=1;   
				}
				}
			} else {
				$USACAN_DST=0;   
			}
			} elseif ($mm == 11) {
			if ($dd > 7) {
				$USACAN_DST=0;   
			} elseif ($dd < ($dow+1)) {
				$USACAN_DST=1;   
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (7200+($timezone-1)*3600)) {
					$USACAN_DST=1;   
				} else {
					$USACAN_DST=0;   
				}
				} else { # local time calculations
				if ($ns < 7200) {
					$USACAN_DST=1;   
				} else {
					$USACAN_DST=0;   
				}
				}
			} else {
				$USACAN_DST=0;   
			}
			} # end of month checks
		if ($DBX) {print "     DST: $USACAN_DST\n";}
		if ($USACAN_DST) {$gmt_offset++;}
		$AC_processed++;
		}

	if ( (!$AC_processed) and ($dst_range == 'FSA-LSO') )
		{
		if ($DBX) {print "     First Sunday April to Last Sunday October\n";}
		#**********************************************************************
		# FSA-LSO
		#     This is returns 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect.
		#     Based on first Sunday in April and last Sunday in October at 2 am.
		#**********************************************************************
			
			$USA_DST=0;
			$mm = $mon;
			$dd = $mday;
			$ns = $dsec;
			$dow= $wday;

			if ($mm < 4 || $mm > 10) {
			$USA_DST=0;
			} elseif ($mm >= 5 and $mm <= 9) {
			$USA_DST=1;
			} elseif ($mm == 4) {
			if ($dd > 7) {
				$USA_DST=1;
			} elseif ($dd >= ($dow+1)) {
				if ($timezone) {
				if ($dow == 0 and $ns < (7200+$timezone*3600)) {
					$USA_DST=0;
				} else {
					$USA_DST=1;
				}
				} else {
				if ($dow == 0 and $ns < 7200) {
					$USA_DST=0;
				} else {
					$USA_DST=1;
				}
				}
			} else {
				$USA_DST=0;
			}
			} elseif ($mm == 10) {
			if ($dd < 25) {
				$USA_DST=1;
			} elseif ($dd < ($dow+25)) {
				$USA_DST=1;
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (7200+($timezone-1)*3600)) {
					$USA_DST=1;
				} else {
					$USA_DST=0;
				}
				} else { # local time calculations
				if ($ns < 7200) {
					$USA_DST=1;
				} else {
					$USA_DST=0;
				}
				}
			} else {
				$USA_DST=0;
			}
			} # end of month checks

		if ($DBX) {print "     DST: $USA_DST\n";}
		if ($USA_DST) {$gmt_offset++;}
		$AC_processed++;
		}

	if ( (!$AC_processed) and ($dst_range == 'LSM-LSO') )
		{
		if ($DBX) {print "     Last Sunday March to Last Sunday October\n";}
		#**********************************************************************
		#     This is s 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect.
		#     Based on last Sunday in March and last Sunday in October at 1 am.
		#**********************************************************************
			
			$GBR_DST=0;
			$mm = $mon;
			$dd = $mday;
			$ns = $dsec;
			$dow= $wday;

			if ($mm < 3 || $mm > 10) {
			$GBR_DST=0;
			} elseif ($mm >= 4 and $mm <= 9) {
			$GBR_DST=1;
			} elseif ($mm == 3) {
			if ($dd < 25) {
				$GBR_DST=0;
			} elseif ($dd < ($dow+25)) {
				$GBR_DST=0;
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (3600+($timezone-1)*3600)) {
					$GBR_DST=0;
				} else {
					$GBR_DST=1;
				}
				} else { # local time calculations
				if ($ns < 3600) {
					$GBR_DST=0;
				} else {
					$GBR_DST=1;
				}
				}
			} else {
				$GBR_DST=1;
			}
			} elseif ($mm == 10) {
			if ($dd < 25) {
				$GBR_DST=1;
			} elseif ($dd < ($dow+25)) {
				$GBR_DST=1;
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (3600+($timezone-1)*3600)) {
					$GBR_DST=1;
				} else {
					$GBR_DST=0;
				}
				} else { # local time calculations
				if ($ns < 3600) {
					$GBR_DST=1;
				} else {
					$GBR_DST=0;
				}
				}
			} else {
				$GBR_DST=0;
			}
			} # end of month checks
			if ($DBX) {print "     DST: $GBR_DST\n";}
		if ($GBR_DST) {$gmt_offset++;}
		$AC_processed++;
		}
	if ( (!$AC_processed) and ($dst_range == 'LSO-LSM') )
		{
		if ($DBX) {print "     Last Sunday October to Last Sunday March\n";}
		#**********************************************************************
		#     This is s 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect.
		#     Based on last Sunday in October and last Sunday in March at 1 am.
		#**********************************************************************
			
			$AUS_DST=0;
			$mm = $mon;
			$dd = $mday;
			$ns = $dsec;
			$dow= $wday;

			if ($mm < 3 || $mm > 10) {
			$AUS_DST=1;
			} elseif ($mm >= 4 and $mm <= 9) {
			$AUS_DST=0;
			} elseif ($mm == 3) {
			if ($dd < 25) {
				$AUS_DST=1;
			} elseif ($dd < ($dow+25)) {
				$AUS_DST=1;
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (3600+($timezone-1)*3600)) {
					$AUS_DST=1;
				} else {
					$AUS_DST=0;
				}
				} else { # local time calculations
				if ($ns < 3600) {
					$AUS_DST=1;
				} else {
					$AUS_DST=0;
				}
				}
			} else {
				$AUS_DST=0;
			}
			} elseif ($mm == 10) {
			if ($dd < 25) {
				$AUS_DST=0;
			} elseif ($dd < ($dow+25)) {
				$AUS_DST=0;
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (3600+($timezone-1)*3600)) {
					$AUS_DST=0;
				} else {
					$AUS_DST=1;
				}
				} else { # local time calculations
				if ($ns < 3600) {
					$AUS_DST=0;
				} else {
					$AUS_DST=1;
				}
				}
			} else {
				$AUS_DST=1;
			}
			} # end of month checks						
		if ($DBX) {print "     DST: $AUS_DST\n";}
		if ($AUS_DST) {$gmt_offset++;}
		$AC_processed++;
		}

	if ( (!$AC_processed) and ($dst_range == 'FSO-LSM') )
		{
		if ($DBX) {print "     First Sunday October to Last Sunday March\n";}
		#**********************************************************************
		#   TASMANIA ONLY
		#     This is s 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect.
		#     Based on first Sunday in October and last Sunday in March at 1 am.
		#**********************************************************************
			
			$AUST_DST=0;
			$mm = $mon;
			$dd = $mday;
			$ns = $dsec;
			$dow= $wday;

			if ($mm < 3 || $mm > 10) {
			$AUST_DST=1;
			} elseif ($mm >= 4 and $mm <= 9) {
			$AUST_DST=0;
			} elseif ($mm == 3) {
			if ($dd < 25) {
				$AUST_DST=1;
			} elseif ($dd < ($dow+25)) {
				$AUST_DST=1;
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (3600+($timezone-1)*3600)) {
					$AUST_DST=1;
				} else {
					$AUST_DST=0;
				}
				} else { # local time calculations
				if ($ns < 3600) {
					$AUST_DST=1;
				} else {
					$AUST_DST=0;
				}
				}
			} else {
				$AUST_DST=0;
			}
			} elseif ($mm == 10) {
			if ($dd > 7) {
				$AUST_DST=1;
			} elseif ($dd >= ($dow+1)) {
				if ($timezone) {
				if ($dow == 0 and $ns < (7200+$timezone*3600)) {
					$AUST_DST=0;
				} else {
					$AUST_DST=1;
				}
				} else {
				if ($dow == 0 and $ns < 3600) {
					$AUST_DST=0;
				} else {
					$AUST_DST=1;
				}
				}
			} else {
				$AUST_DST=0;
			}
			} # end of month checks						
		if ($DBX) {print "     DST: $AUST_DST\n";}
		if ($AUST_DST) {$gmt_offset++;}
		$AC_processed++;
		}

	if ( (!$AC_processed) and ($dst_range == 'FSO-FSA') )
		{
		if ($DBX) {print "     Sunday in October to First Sunday in April\n";}
		#**********************************************************************
		# FSO-FSA
		#   2008+ AUSTRALIA ONLY (country code 61)
		#     This is returns 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect.
		#     Based on first Sunday in October and first Sunday in April at 1 am.
		#**********************************************************************
		
		$AUSE_DST=0;
		$mm = $mon;
		$dd = $mday;
		$ns = $dsec;
		$dow= $wday;

		if ($mm < 4 or $mm > 10) {
		$AUSE_DST=1;   
		} elseif ($mm >= 5 and $mm <= 9) {
		$AUSE_DST=0;   
		} elseif ($mm == 4) {
		if ($dd > 7) {
			$AUSE_DST=0;   
		} elseif ($dd >= ($dow+1)) {
			if ($timezone) {
			if ($dow == 0 and $ns < (3600+$timezone*3600)) {
				$AUSE_DST=1;   
			} else {
				$AUSE_DST=0;   
			}
			} else {
			if ($dow == 0 and $ns < 7200) {
				$AUSE_DST=1;   
			} else {
				$AUSE_DST=0;   
			}
			}
		} else {
			$AUSE_DST=1;   
		}
		} elseif ($mm == 10) {
		if ($dd >= 8) {
			$AUSE_DST=1;   
		} elseif ($dd >= ($dow+1)) {
			if ($timezone) {
			if ($dow == 0 and $ns < (7200+$timezone*3600)) {
				$AUSE_DST=0;   
			} else {
				$AUSE_DST=1;   
			}
			} else {
			if ($dow == 0 and $ns < 3600) {
				$AUSE_DST=0;   
			} else {
				$AUSE_DST=1;   
			}
			}
		} else {
			$AUSE_DST=0;   
		}
		} # end of month checks
		if ($DBX) {print "     DST: $AUSE_DST\n";}
		if ($AUSE_DST) {$gmt_offset++;}
		$AC_processed++;
		}

	if ( (!$AC_processed) and ($dst_range == 'FSO-TSM') )
		{
		if ($DBX) {print "     First Sunday October to Third Sunday March\n";}
		#**********************************************************************
		#     This is s 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect.
		#     Based on first Sunday in October and third Sunday in March at 1 am.
		#**********************************************************************
			
			$NZL_DST=0;
			$mm = $mon;
			$dd = $mday;
			$ns = $dsec;
			$dow= $wday;

			if ($mm < 3 || $mm > 10) {
			$NZL_DST=1;
			} elseif ($mm >= 4 and $mm <= 9) {
			$NZL_DST=0;
			} elseif ($mm == 3) {
			if ($dd < 14) {
				$NZL_DST=1;
			} elseif ($dd < ($dow+14)) {
				$NZL_DST=1;
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (3600+($timezone-1)*3600)) {
					$NZL_DST=1;
				} else {
					$NZL_DST=0;
				}
				} else { # local time calculations
				if ($ns < 3600) {
					$NZL_DST=1;
				} else {
					$NZL_DST=0;
				}
				}
			} else {
				$NZL_DST=0;
			}
			} elseif ($mm == 10) {
			if ($dd > 7) {
				$NZL_DST=1;
			} elseif ($dd >= ($dow+1)) {
				if ($timezone) {
				if ($dow == 0 and $ns < (7200+$timezone*3600)) {
					$NZL_DST=0;
				} else {
					$NZL_DST=1;
				}
				} else {
				if ($dow == 0 and $ns < 3600) {
					$NZL_DST=0;
				} else {
					$NZL_DST=1;
				}
				}
			} else {
				$NZL_DST=0;
			}
			} # end of month checks						
		if ($DBX) {print "     DST: $NZL_DST\n";}
		if ($NZL_DST) {$gmt_offset++;}
		$AC_processed++;
		}

	if ( (!$AC_processed) and ($dst_range == 'LSS-FSA') )
		{
		if ($DBX) {print "     Last Sunday in September to First Sunday in April\n";}
		#**********************************************************************
		# LSS-FSA
		#   2007+ NEW ZEALAND (country code 64)
		#     This is returns 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect.
		#     Based on last Sunday in September and first Sunday in April at 1 am.
		#**********************************************************************
		
		$NZLN_DST=0;
		$mm = $mon;
		$dd = $mday;
		$ns = $dsec;
		$dow= $wday;

		if ($mm < 4 || $mm > 9) {
		$NZLN_DST=1;   
		} elseif ($mm >= 5 && $mm <= 9) {
		$NZLN_DST=0;   
		} elseif ($mm == 4) {
		if ($dd > 7) {
			$NZLN_DST=0;   
		} elseif ($dd >= ($dow+1)) {
			if ($timezone) {
			if ($dow == 0 && $ns < (3600+$timezone*3600)) {
				$NZLN_DST=1;   
			} else {
				$NZLN_DST=0;   
			}
			} else {
			if ($dow == 0 && $ns < 7200) {
				$NZLN_DST=1;   
			} else {
				$NZLN_DST=0;   
			}
			}
		} else {
			$NZLN_DST=1;   
		}
		} elseif ($mm == 9) {
		if ($dd < 25) {
			$NZLN_DST=0;   
		} elseif ($dd < ($dow+25)) {
			$NZLN_DST=0;   
		} elseif ($dow == 0) {
			if ($timezone) { # UTC calculations
			if ($ns < (3600+($timezone-1)*3600)) {
				$NZLN_DST=0;   
			} else {
				$NZLN_DST=1;   
			}
			} else { # local time calculations
			if ($ns < 3600) {
				$NZLN_DST=0;   
			} else {
				$NZLN_DST=1;   
			}
			}
		} else {
			$NZLN_DST=1;   
		}
		} # end of month checks
		if ($DBX) {print "     DST: $NZLN_DST\n";}
		if ($NZLN_DST) {$gmt_offset++;}
		$AC_processed++;
		}

	if ( (!$AC_processed) and ($dst_range == 'TSO-LSF') )
		{
		if ($DBX) {print "     Third Sunday October to Last Sunday February\n";}
		#**********************************************************************
		# TSO-LSF
		#     This is returns 1 if Daylight Savings Time is in effect and 0 if 
		#       Standard time is in effect. Brazil
		#     Based on Third Sunday October to Last Sunday February at 1 am.
		#**********************************************************************
			
			$BZL_DST=0;
			$mm = $mon;
			$dd = $mday;
			$ns = $dsec;
			$dow= $wday;

			if ($mm < 2 || $mm > 10) {
			$BZL_DST=1;   
			} elseif ($mm >= 3 and $mm <= 9) {
			$BZL_DST=0;   
			} elseif ($mm == 2) {
			if ($dd < 22) {
				$BZL_DST=1;   
			} elseif ($dd < ($dow+22)) {
				$BZL_DST=1;   
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (3600+($timezone-1)*3600)) {
					$BZL_DST=1;   
				} else {
					$BZL_DST=0;   
				}
				} else { # local time calculations
				if ($ns < 3600) {
					$BZL_DST=1;   
				} else {
					$BZL_DST=0;   
				}
				}
			} else {
				$BZL_DST=0;   
			}
			} elseif ($mm == 10) {
			if ($dd < 22) {
				$BZL_DST=0;   
			} elseif ($dd < ($dow+22)) {
				$BZL_DST=0;   
			} elseif ($dow == 0) {
				if ($timezone) { # UTC calculations
				if ($ns < (3600+($timezone-1)*3600)) {
					$BZL_DST=0;   
				} else {
					$BZL_DST=1;   
				}
				} else { # local time calculations
				if ($ns < 3600) {
					$BZL_DST=0;   
				} else {
					$BZL_DST=1;   
				}
				}
			} else {
				$BZL_DST=1;   
			}
			} # end of month checks
		if ($DBX) {print "     DST: $BZL_DST\n";}
		if ($BZL_DST) {$gmt_offset++;}
		$AC_processed++;
		}

	if (!$AC_processed)
		{
		if ($DBX) {print "     No DST Method Found\n";}
		if ($DBX) {print "     DST: 0\n";}
		$AC_processed++;
		}

	return $gmt_offset;
	}
    
    
    
    
    
    
    
    
    function extgetval($lead_file, $list_id_override, $phone_code_override, $dupcheck) {
		
			flush();
			$total=0; $good=0; $bad=0; $dup=0; $post=0; $phone_list='';

			$file=fopen("$lead_file", "r");

			$buffer=fgets($file, 4096);
			$tab_count=substr_count($buffer, "\t");
			$pipe_count=substr_count($buffer, "|");

			if ($tab_count>$pipe_count) {$delimiter="\t";  $delim_name="tab";} else {$delimiter="|";  $delim_name="pipe";}
			$field_check=explode($delimiter, $buffer);

			 	if (count($field_check)>=2) {
					flush();
					$file=fopen("$lead_file", "r");
					//$data['processfile'] = "<center><font face='arial, helvetica' size=3 color='#009900'><B>Processing file...\n";

					if (strlen($list_id_override)>0) {
					//print "<BR><BR>LIST ID OVERRIDE FOR THIS FILE: $list_id_override<BR><BR>";
					}

					if (strlen($phone_code_override)>0) {
					//print "<BR><BR>PHONE CODE OVERRIDE FOR THIS FILE: $phone_code_override<BR><BR>";
					}

					$systemlookup = $this->golist->systemsettingslookup();
					foreach($systemlookup as $sysinfo){
						$use_non_latin = $sysinfo->use_non_latin;
						$admin_web_directory = $sysinfo->admin_web_directory;
						$custom_fields_enabled = $sysinfo->custom_fields_enabled;
					}
						
			
					if ($custom_fields_enabled > 0) {
							$tablecount_to_print=0;
							$fieldscount_to_print=0;
							$fields_to_print=0;

							$stmt="SHOW TABLES LIKE \"custom_$list_id_override\";";
							$rslt = $this->asteriskDB->query($stmt);
							$tablecount_to_print = $rslt->num_rows;

							if ($tablecount_to_print > 0) {
									$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$list_id_override';";
									$rslt = $this->asteriskDB->query($stmt);
									$fieldscount_to_print = $rslt->num_rows;
			
									if ($fieldscount_to_print > 0) {
										$stmt="SELECT field_label,field_type from vicidial_lists_fields where list_id='$list_id_override' order by field_rank,field_order,field_label;";

										$rslt = $this->asteriskDB->query($stmt);
										$fields_to_print = $rslt->num_rows;

										$fields_list='';
										$o=0;
					
										while ($fields_to_print > $o) {
											$rowx = $rslt->row();
											$A_field_label[$o] =	$rowx->field_label;
											$A_field_type[$o] =	$rowx->field_type;
											$A_field_value[$o] =	'';
											//var_dump($A_field_label[$o]);
										$o++;
										}
									}
							}
					}
						
					
					while (!feof($file)) {
							$record++;
							$buffer=rtrim(fgets($file, 4096));
							$buffer=stripslashes($buffer);
		
							if (strlen($buffer)>0) {
								$row=explode($delimiter, eregi_replace("[\'\"]", "", $buffer));
									
									$pulldate=date("Y-m-d H:i:s");
									$entry_date =			"$pulldate";
									$modify_date =			"";
									$status =				"NEW";
									$user ="";
									$vendor_lead_code =		$row[$vendor_lead_code_field];
									$source_code =			$row[$source_id_field];
									$source_id=$source_code;
									$list_id =				$row[$list_id_field];
									$gmt_offset =			'0';
									$called_since_last_reset='N';
									$phone_code =			eregi_replace("[^0-9]", "", $row[$phone_code_field]);
									$phone_number =			eregi_replace("[^0-9]", "", $row[$phone_number_field]);
									$title =				$row[$title_field];
									$first_name =			$row[$first_name_field];
									$middle_initial =		$row[$middle_initial_field];
									$last_name =			$row[$last_name_field];
									$address1 =				$row[$address1_field];
									$address2 =				$row[$address2_field];
									$address3 =				$row[$address3_field];
									$city =$row[$city_field];
									$state =				$row[$state_field];
									$province =				$row[$province_field];
									$postal_code =			$row[$postal_code_field];
									$country_code =			$row[$country_code_field];
									$gender =				$row[$gender_field];
									$date_of_birth =		$row[$date_of_birth_field];
									$alt_phone =			eregi_replace("[^0-9]", "", $row[$alt_phone_field]);
									$email =				$row[$email_field];
									$security_phrase =		$row[$security_phrase_field];
									$comments =				trim($row[$comments_field]);
									$rank =					$row[$rank_field];
									$owner =				$row[$owner_field];
									
									### REGEX to prevent weird characters from ending up in the fields
									$field_regx = "['\"`\\;]";
									
									
									
									# replace ' " ` \ ; with nothing
									$vendor_lead_code =		eregi_replace($field_regx, "", $vendor_lead_code);
									$source_code =			eregi_replace($field_regx, "", $source_code);
									$source_id = 			eregi_replace($field_regx, "", $source_id);
									$list_id =				eregi_replace($field_regx, "", $list_id);
									$phone_code =			eregi_replace($field_regx, "", $phone_code);
									$phone_number =			eregi_replace($field_regx, "", $phone_number);
									$title =				eregi_replace($field_regx, "", $title);
									$first_name =			eregi_replace($field_regx, "", $first_name);
									$middle_initial =		eregi_replace($field_regx, "", $middle_initial);
									$last_name =			eregi_replace($field_regx, "", $last_name);
									$address1 =				eregi_replace($field_regx, "", $address1);
									$address2 =				eregi_replace($field_regx, "", $address2);
									$address3 =				eregi_replace($field_regx, "", $address3);
									$city =					eregi_replace($field_regx, "", $city);
									$state =				eregi_replace($field_regx, "", $state);
									$province =				eregi_replace($field_regx, "", $province);
									$postal_code =			eregi_replace($field_regx, "", $postal_code);
									$country_code =			eregi_replace($field_regx, "", $country_code);
									$gender =				eregi_replace($field_regx, "", $gender);
									$date_of_birth =		eregi_replace($field_regx, "", $date_of_birth);
									$alt_phone =			eregi_replace($field_regx, "", $alt_phone);
									$email =				eregi_replace($field_regx, "", $email);
									$security_phrase =		eregi_replace($field_regx, "", $security_phrase);
									$comments =				eregi_replace($field_regx, "", $comments);
									$rank =					eregi_replace($field_regx, "", $rank);
									$owner =				eregi_replace($field_regx, "", $owner);
									
									$USarea = 			substr($phone_number, 0, 3);
									
									if (strlen($list_id_override)>0) {
										#	print "<BR><BR>LIST ID OVERRIDE FOR THIS FILE: $list_id_override<BR><BR>";
										$list_id = $list_id_override;
									}
									if (strlen($phone_code_override)>0) {
										$phone_code = $phone_code_override;
									}
									
										##### BEGIN custom fields columns list ###
										$custom_SQL='';
										if ($custom_fields_enabled > 0)
											{
											if ($tablecount_to_print > 0) 
												{
												if ($fieldscount_to_print > 0)
													{
													$o=0;
													while ($fields_to_print > $o) 
														{
														$A_field_value[$o] =	'';
														$field_name_id = $A_field_label[$o] . "_field";
						
													#	if ($DB>0) {echo "$A_field_label[$o]|$A_field_type[$o]\n";}
						
														if ( ($A_field_type[$o]!='DISPLAY') and ($A_field_type[$o]!='SCRIPT') )
															{
															if (!preg_match("/\|$A_field_label[$o]\|/",$vicidial_list_fields))
																{
																if (isset($_GET["$field_name_id"]))				{$form_field_value=$_GET["$field_name_id"];}
																	elseif (isset($_POST["$field_name_id"]))	{$form_field_value=$_POST["$field_name_id"];}
						
																if ($form_field_value >= 0)
																	{
																	$A_field_value[$o] =	$row[$form_field_value];
																	# replace ' " ` \ ; with nothing
																	$A_field_value[$o] =	eregi_replace($field_regx, "", $A_field_value[$o]);
						
																	$custom_SQL .= "$A_field_label[$o]='$A_field_value[$o]',";
																	}
																}
															}
														$o++;
														}
													}
												}
											}
										##### END custom fields columns list ###
						
										$custom_SQL = preg_replace("/,$/","",$custom_SQL);
									
									## checking duplicate portion
									
									##### Check for duplicate phone numbers in vicidial_list table for all lists in a campaign #####
									if ($dupcheck='DUPCAMP') {
										
										$dup_lead=0;
										$dup_lists='';
										$stmt="select campaign_id from vicidial_lists where list_id='$list_id';";
										$rslt = $this->asteriskDB->query($stmt);
										$ci_recs = $rslt->num_rows;
										
										if ($ci_recs > 0) {
											$row = $rslt->row();
											$dup_camp = $row->campaign_id;
											$stmt="select list_id from vicidial_lists where campaign_id='$dup_camp';";
											$rslt = $this->asteriskDB->query($stmt);
											$li_recs = $rslt->num_rows;
											
											if ($li_recs > 0) 	{
												$L=0;
												while ($li_recs > $L) {
													$row = $rslt->row();
													$dup_lists .=	"'$row->list_id',";
													$L++;
												}
												$dup_lists = eregi_replace(",$",'',$dup_lists);
					
												$stmt="select list_id from vicidial_list where phone_number='$phone_number' and list_id IN($dup_lists) limit 1;";
												$rslt = $this->asteriskDB->query($stmt);
												$pc_recs = $rslt->num_rows;
												//die($stmt.'-->'.$pc_recs);		
												if ($pc_recs > 0) {
													$dup_lead=1;
													$row = $rslt->row();
													$dup_lead_list = $row->list_id;
												
												}
												if ($dup_lead < 1) {
													if (eregi("$phone_number$US$list_id",$phone_list))
														{$dup_lead++; $dup++;}
													}
												}
											}
									}
									## end checking duplicate

							} //end buffer if
					} // end while
					
					    	
    		}  //dulong dulo
    } //end function
    
    
    function download(){
         $listidx = $this->uri->segment(3);
         if(!empty($listidx)){
                $date = date("Ymd");
		$time = date("His");
		$data["url"] = "LIST_{$listidx}_{$date}-{$time}.csv";
		$dlquery = $this->golist->dlquery($listidx);
		$rows = "";
		$headers = "";
 
		$cnt = 0;
		$x = 0;
                foreach($dlquery as $dlqueryinfo){
		    foreach($dlqueryinfo as $key => $value)
		    {
			if ($cnt < 1)
			    $headers .= ",$key";
			
			$rows .= "$value,";
		    }
		    $rows = "$rows\n";
		    $cnt++;
              	}
		
		$headers = trim($headers,",")."\n";
		$data["csv"] = "{$headers}{$rows}";
                $this->load->view("common_download",$data);
         }
    }

    
    function do_upload() {
		$config['upload_path'] = './uploads/';
		//$config['allowed_types'] = 'gif|jpg|png|csv';
		$config['allowed_types'] = 'csv';
		$config['max_size']	= '100';
		$config['max_width']  = '1024';
		$config['max_height']  = '768'; 

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());

			$this->load->view('upload_form', $error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());

			$this->load->view('upload_success', $data);
		}
	}

        function is_logged_in()
        {
                $is_logged_in = $this->session->userdata('is_logged_in');
                if(!isset($is_logged_in) || $is_logged_in != true)
                {
                        $base = base_url();
                        echo "<script>javascript: window.location = 'https://".$_SERVER['HTTP_HOST']."/login'</script>";
//                      echo "<script>javascript: window.location = '$base'</script>";
                        #echo 'You don\'t have permission to access this page. <a href="../go_index">Login</a>';
                        die();
                        #$this->load->view('go_login_form');
                }
        }
	
    function update_lead()
    {
	if (!empty($_POST))
	{
	    $leadid = $this->input->post('leadid');
	    foreach ($_POST as $key => $val)
	    {
		if ($key == "basic")
		{
		    $basic = explode("&",str_replace(";","",$val));
		    foreach ($basic as $bas)
		    {
			list($baskey,$basval) = explode("=",$bas);
			if (!empty($basval))
			{
			    $basval = str_replace("+"," ",$basval);
			    $basic_SQL .= " `$baskey` = '".$this->asteriskDB->escape_str($basval)."',";
			    $status = ($baskey=="status") ? "$basval" : "";
			}
		    }
		} else if ($key == "advance") {
		    $advance = explode("&",str_replace(";","",$val));
		    foreach ($advance as $adv)
		    {
			list($advkey,$advval) = explode("=",$adv);
			if (!empty($advval))
			{
			    if ($advkey=="status")
			    {
				$advance_SQL .= "`$advkey` = '".$this->asteriskDB->escape_str($advval)."'";
			    } else {
				if ($advkey=="modify_logs") { $modify_logs = $advval; }
				if ($advkey=="modify_agent_logs") { $modify_agent_logs = $advval; }
				if ($advkey=="modify_closer_logs") { $modify_closer_logs = $advval; }
				if ($advkey=="add_closer_record") { $add_closer_record = $advval; }
			    }
			}
		    }
		}
	    }
	    
	    if (empty($advance_SQL))
	    {
		$basic_SQL = rtrim($basic_SQL,',');
	    }
	    $query_SQL = "UPDATE vicidial_list SET $basic_SQL $advance_SQL WHERE lead_id='$leadid'";
	    $query = $this->asteriskDB->query($query_SQL);
	    
	    if ($this->asteriskDB->affected_rows())
	    {
		if (!empty($status)) {
		    if ($modify_logs) {
			$logs_query = "UPDATE vicidial_log set status='" . $this->asteriskDB->escape_str($status) . "' where lead_id='" . $this->asteriskDB->escape_str($leadid) . "' order by call_date desc limit 1";
		    }
		    if ($modify_closer_logs) {
			$closer_logs = "UPDATE vicidial_closer_log set status='" . $this->asteriskDB->escape_str($status) . "' where lead_id='" . $this->asteriskDB->escape_str($leadid) . "' order by call_date desc limit 1";
		    }
		    if ($modify_agent_logs) {
			$agent_logs = "UPDATE vicidial_agent_log set status='" . $this->asteriskDB->escape_str($status) . "' where lead_id='" . $this->asteriskDB->escape_str($lead_id) . "' order by agent_log_id desc limit 1";
		    }
		//    if ($add_closer_record) {
		//	$add_record = "INSERT INTO vicidial_closer_log (lead_id,list_id,campaign_id,call_date,start_epoch,end_epoch,length_in_sec,status,phone_code,phone_number,user,comments,processed) values('" . $this->asteriskDB->escape_str($lead_id) . "','" . $this->asteriskDB->escape_str($list_id) . "','" . $this->asteriskDB->escape_str($campaign_id) . "','" . $this->asteriskDB->escape_str($parked_time) . "','$NOW_TIME','$STARTtime','1','" . $this->asteriskDB->escape_str($status) . "','" . $this->asteriskDB->escape_str($phone_code) . "','" . $this->asteriskDB->escape_str($phone_number) . "','$PHP_AUTH_USER','" . $this->asteriskDB->escape_str($comments) . "','Y')";
		//    }
		}
		return "Success: Lead ID $leadid updated.";
		$this->commonhelper->auditadmin("UPDATE","Lead ID $leadid updated.",$query_SQL);
	    } else {
		return "Error: Lead ID $leadid not updated.";
	    }
	}
    }
}
