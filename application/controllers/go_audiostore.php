<?php
############################################################################################
####  Name:             go_audiostore.php                                               ####
####  Type:             ci controller - administrator                                   ####
####  Version:          3.0                                                             ####
####  Build:            1366106153                                                      ####
####  Copyright:        GOAutoDial Inc. (c) 2011-2013 - <dev@goautodial.com>            ####
####  Written by:       Jerico James F. Milo                                            ####
####  License:          AGPLv2                                                          ####
############################################################################################

class Go_audiostore extends Controller{
    function __construct(){
        parent::Controller();
        $this->load->model('goaudiostore');
        $this->load->library(array('session','userhelper','commonhelper'));
	$this->lang->load('userauth', $this->session->userdata('ua_language'));
        $this->is_logged_in();
    }

    function index() {
        //load models
        $this->asteriskDB = $this->load->database('dialerdb',TRUE);
        //$this->a2billingDB =$this->load->database('billingdb',TRUE);
        $this->load->model('goaudiostore');
	//$this->load->model('go_reports');
        $this->load->model('go_dashboard');
        $groupId = $this->go_dashboard->go_get_groupid();

            if ($groupId == 'ADMIN' || $groupId == 'admin') {
		$ul='';
		} else {
		$allowedcampaign = $this->go_dashboard->go_getall_allowed_campaigns();
		$ul = " WHERE campaign_id IN ('$allowedcampaign') ";
		}

        $callfunc = $this->go_dashboard->go_get_userfulname();
        $data['userfulname'] = $callfunc;

	//$callfunc = $this->go_reports->go_get_userfulname();
	//$data['userfulname'] = $callfunc;
        $data['gouser'] = $this->session->userdata('user_name');
        $data['gopass'] = $this->session->userdata('user_pass');
        $data['theme'] = $this->session->userdata('go_theme');
        $data['adm']= 'wp-has-current-submenu';
        $data['hostp'] = $_SERVER['SERVER_ADDR'];
        $data['foldlink'] = '';
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';
        $data['bannertitle'] = $this->lang->line('go_voicefile_banner');
        $data['folded'] = 'folded';
        $data['go_main_content'] = 'go_audiostore/go_audiostore'; 
        $data['users'] = $users;
        $data['usergroups'] = $usergroups;
        $data['account'] = $account;
        $data['gowizard'] = "gowizard";
	$data['permissions'] = $this->commonhelper->getPermissions("voicefiles",$this->session->userdata("user_group"));

        $val = "";

	// for multi tenant  $RLIKEaccnt = "OR list_name RLIKE '$PHP_AUTH_USER'"; 
        // get functions 
        $userlevel = $this->goaudiostore->getuserlevel($data['gouser']);
        //$accounts = $this->goaudiostore->getaccounts($data['gouser']); // get account number

        $ingrouplists = $this->goaudiostore->getallingroup($accounts, $userlevel);
        $scriptlists = $this->goaudiostore->scriptlists($userlevel,$accounts);
        $ingrouppulldown = $this->goaudiostore->ingrouppulldown($userlevel,$accounts);
        $calltimespulldown = $this->goaudiostore->calltimespulldown($userlevel,$accounts);
        $callmenupulldown = $this->goaudiostore->getcallmenu($userlevel,$accounts);
        $groupaliaspulldown = $this->goaudiostore->groupalias();
        $campaigns = $this->goaudiostore->getcampaign($ul);
	
	//autocreate
	$stage = $this->input->post('stage');
	$audiofile = $this->input->post('audiofile');
	$accnt = $this->input->get('accnt');
	
 	     if($stage=='addLIST') {
       		$genlist = $this->goaudiostore->autogenlist($accnt);
       		$data['allmine'] = $genlist;
       		$this->load->view('go_list/go_db_query',$data);
	     }
	// end auto create

	    // insert post values 
	     $addSUBMIT = $this->input->post('addSUBMIT');
	     $list_id = $this->input->post('list_id');
	     $list_name = $this->input->post('list_name');
	     $list_description = $this->input->post('list_description');
	     $active = $this->input->post('active');
	     $campaign_ids = $this->input->post('campaign_ids');
	     
   	     $group_id = $this->input->post('group_id');
	     $group_name = $this->input->post('group_name');
	     $group_color = $this->input->post('group_color');
	     $active = $this->input->post('active');
	     $web_form_address = $this->input->post('web_form_address');	     
	     $voicemail_ext = $this->input->post('voicemail_ext');
	     $next_agent_call = $this->input->post('next_agent_call');
	     $fronter_display = $this->input->post('fronter_display');
	     $script_id = $this->input->post('script_id');
	     $get_call_launch = $this->input->post('get_call_launch');

	    $audiofile_name=$_FILES["audiofile"]['name'];
	    $WeBServeRRooT = '/var/lib/asterisk';
	    $sounds_web_directory = 'sounds';
	    $audiofile=$_FILES["audiofile"];
	    $audiofile_orig = $_FILES['audiofile']['name'];
	    $audiofile_dir = $_FILES['audiofile']['tmp_name'];
	    $server_name = getenv("SERVER_NAME");	     
	     
	    if ($stage == "upload") {
     		//$audiofile
     		$explodefile = explode(".",strtolower($audiofile_orig));
			
			$prefix = ($this->commonhelper->checkIfTenant($this->session->userdata('user_group'))) ? "go_".$this->session->userdata('user_group')."_" : "go_";
     		
        	if (preg_match("/\.wav$/i",$audiofile_orig)) {
        		
    		$audiofile_dir = preg_replace("/ /",'\ ',$audiofile_dir);
                $audiofile_dir = preg_replace("/@/",'\@',$audiofile_dir);
                $audiofile_name = preg_replace("/ /",'',"$prefix".$audiofile_name);
                $audiofile_name = preg_replace("/@/",'',$audiofile_name);
                
                copy($audiofile_dir, "$WeBServeRRooT/$sounds_web_directory/$audiofile_name");
                chmod("$WeBServeRRooT/$sounds_web_directory/$audiofile_name", 0766);

    		$stmt = "SELECT server_id,server_ip,active,server_description FROM servers";
      		$servers = $this->asteriskDB->query($stmt);
      		$row = $servers->result();

                foreach($row as $result){

      		    $server_ip = $result->server_ip; 
    		    $active = $result->active;
    		    $server_description = $result->server_description;

    		    #if(!preg_match('/dialer/i',$server_description) && $active == "Y"){
    		    if($active == "Y"){
		        exec('/usr/share/goautodial/goautodialc.pl "rsync -avz -e \"ssh -p222\" /var/lib/asterisk/sounds/'.$audiofile_name.' root@'.$server_ip.':/var/lib/asterisk/sounds"');
		    }           				
			
                }	
		$this->commonhelper->auditadmin('UPLOAD',"Uploaded a WAV file: $audiofile_name");
    		$stmt="UPDATE servers SET sounds_update='Y';";
		$this->asteriskDB->query($stmt);

                } else {
                	$data['uploadfail'] = "File type should be in wav format.";
                }
		}
	
		if($addSUBMIT) {		
        	 $message = $this->goaudiostore->insertingroup($accounts, $users, $group_id, $group_name, $group_color, $active, $web_form_address, $voicemail_ext, $next_agent_call, $fronter_display, $script_id, $get_call_launch);
        	 if($message !=null) {
        	 	print "<script type=\"text/javascript\">alert('GROUP NOT ADDED - Please go back and look at the data you entered');</script>";
        	 } else {
        	 	header("Location: #");
			 }        	 		      
		  }
		  // end insert
		  
		  // edit
		  $editSUBMIT = $this->input->post('editSUBMIT');
		  $editparam = $this->input->post('editparam');
		  $listidparam = $this->input->post('listidparam');
		  
		 // $editparam = $this->uri->segment(3);
		 // $listidparam = $this->uri->segment(4);
		  
		  if($editparam=='editLIST') {
				//$listvalues = $this->goaudiostore->geteditvalues($listidparam);
				//$data['listvalues'] = $listvalues;
				$this->load->view('go_list/go_db_query',$data);
		  }

		  $editlist = $this->input->post('editlist');
		  if($editlist=='editlist') {
					//die("milo");
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
					$this->goaudiostore->editvalues($listid,$listname,$campaign_id,$active,$list_description,$list_changedate,$list_lastcalldate,$reset_time,$agent_script_override,$campaign_cid_override,$am_message_exten_override,$drop_inbound_group_override,$xferconf_a_number,$xferconf_b_number,$xferconf_c_number,$xferconf_d_number,$xferconf_e_number,$web_form_address,$web_form_address_two);
					//$this->goaudiostore->geteditvalues($listidparam);
		  }
		  // end edit
		  
		  // load leads
		  
			// POST VALUES 			
			$phonedoces = $this->goaudiostore->getphonecodes();	
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
			$data['search'] =			$this->input->post('search_list');
	
        			// pass value to views milo //
        			$data['ingrouppulldown'] = $ingrouppulldown;
        			$data['calltimespulldown'] = $calltimespulldown;
					$data['callmenupulldown'] = $callmenupulldown;
					$data['groupaliaspulldown'] = $groupaliaspulldown;
					$data['ingrouplists'] = $ingrouplists;
					$data['scriptlists'] = $scriptlists;
					$data['campaigns'] = $campaigns;
					$data['accounts'] = $accounts;
					$data['agentranks'] = $agentranks;
					$data['phonedoces'] = $phonedoces;
					$clist = $this->goaudiostore->getcustomlist();
					$data['clist'] = $clist;		
					$voicefilestable = $this->goaudiostore->getvoicefilestable($data['gouser'],$data['search']);
					$data['voicefilestable'] = $voicefilestable;		
					$this->load->view('includes/go_dashboard_template.php',$data);		
	}
    
	function editview() {
		
		$this->load->view('go_audiostore/go_values',$data);
	}
	
	function editsubmit() {
		$this->load->view('go_audiostore/go_values',$data);
	}
	
	function checkagentrank(){
		$this->load->view('go_audiostore/go_values',$data);
	}

	function chooser() {
		$this->load->view('go_audiostore/go_chooser',$data);
	}
	
	function deletesubmit() {
		$this->load->view('go_list/go_values',$data);
	}

	function editcustomview() {
		$this->load->view('go_list/go_values',$data);
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
	$custeditview = $this->goaudiostore->custeditview($listidparam);
	$countfields = $this->goaudiostore->countfields($listidparam);
        $data['custeditview'] = $custeditview;
        $data['countfields'] = $countfields;
	$this->load->view('includes/go_dashboard_template.php',$data);	
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
}
