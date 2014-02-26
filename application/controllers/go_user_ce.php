<?php
####################################################################################################
####  Name:             	go_user_ce.php                                                      ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####                        <community@goautodial.com>                                          ####
####  Written by:       	Franco Hora                                                         ####
####  Edited by:            GoAutoDial Development Team                                         ####
####  License:          	AGPLv2                                                              ####
####################################################################################################

class Go_user_ce extends Controller{

    function __construct(){
        parent::Controller();
        $this->load->model(array('gouser','go_auth','go_access'));
        $this->load->library(array('session','userhelper','commonhelper'));
	$this->db = $this->load->database('dialerdb', true);
        $this->godb = $this->load->database('goautodialdb', true);
        $this->lang->load('userauth', $this->session->userdata('ua_language'));

        $this->load->helper(array('html'));
	$this->is_logged_in();
    }

    /*
     * index
     * what you will see ing ui
     * gather all data ng most process will be done here
     * author : Franco E. Hora <info@goautodial.com>
     */
    function index(){

        $permissions = $this->commonhelper->getPermissions("user",$this->session->userdata("user_group"));
        if($permissions->user_read == "N"){
            die("You don't have permission to view this record(s)");
        }
 
        # check if segment 3 has value
        # if so you are in portal thanks
        /*$account = $this->checkstate();

        if(!is_null($account)){
            $account = $account;
        }else{
            $account = null;
        }*/

        $page = $this->uri->segment(4);
	$search = $this->uri->segment(5);

        $account = $this->session->userdata('user_group');

        $users = $this->gouser->collectusers($account,$page,$search);
        #$usergroups = $this->gouser->getallusergroup($account);

        $data['user_group'] = $this->session->userdata('user_group');
        $data['user_level'] = $this->session->userdata('users_level');
        $data['go_main_content'] = 'go_dashboard';
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';


		$data['theme'] = $this->session->userdata('go_theme');
		$data['bannertitle'] = $this->lang->line('go_users_banner');
		$data['adm']= 'wp-has-current-submenu';
		$data['hostp'] = $_SERVER['SERVER_ADDR'];
		$data['folded'] = 'folded';
		$data['foldlink'] = '';
		$togglestatus = "1";
		$data['togglestatus'] = $togglestatus;		
	
        $data['users'] = $users;
        $data['usergroups'] = $usergroups;
        $data['account'] = $account;
        $data['gowizard'] = "gowizard";
        $data['base_url'] = $this->config->item('base_url');
	$data['page'] = $page;
	$data['search_list'] = $search;

 
        $this->load->view('go_user/go_user_listall_ce.php',$data);

    }

    /*
     * adduser
     * function to MANUALY save new user
     * author : Franco E. Hora <info@goautodial.com>
     */
    function adduser(){
 
        $data = $this->userhelper->postToarray($_POST); 
        $result = $this->gouser->insertuser('vicidial_users',$data);
    }

    /*
     * getaccountinfo
     * function to getaccount to display
     * author : Franco E. Hora <info@goautodial.com>
     */
    function getaccountinfo(){
        $account = $this->uri->segment(3);
        $getaccountinfo = $this->gouser->getallusergroup($account);
        echo json_encode($getaccountinfo);
    }


    /*
     * initwizard
     * display init resetwizard
     * @author: Franco E. Hora <info@goautodial.com>
     */
    function resetwizard(){
           
           $userlevel = $this->session->userdata('users_level');
           if(empty($userlevel)){
                die("Error: Session expired please re-login");
           }

           #if($userlevel > 8){
              $accounts = $this->gouser->retrievedata('vicidial_user_groups');
              if(!empty($accounts)){
                  foreach($accounts as $account){
                     $accnt[$account->user_group] = $account->group_name;
                  }
              }else{
                  $accnt = array();
              }
			  
			$this->gouser->asteriskDB->select('count(user_id) as lastnum');
			if (!$this->commonhelper->checkIfTenant($this->session->userdata('user_group')))
				$a2bwizardresult = $this->gouser->asteriskDB->get_where('vicidial_users',array('full_name NOT LIKE'=>'%Survey%','user !='=>'VDAD','vicidial_users.user !='=>'VDCL'));
			else
				$a2bwizardresult = $this->gouser->asteriskDB->get_where('vicidial_users',array('full_name NOT LIKE'=>'%Survey%','user !='=>'VDAD','vicidial_users.user !='=>'VDCL','user_group ='=>$this->session->userdata('user_group'),'user_level <'=>'4'));
			$wizardrow = $a2bwizardresult->result();
           #}
    
           $display =  form_open(null,"id='add-new-user'");
				  $accnt_group = 'AGENTS';
				  if ($this->commonhelper->checkIfTenant($this->session->userdata('user_group')))
				  {
					  $hideDiv = 'style="display:none;"';
					  $accnt_group = $this->session->userdata('user_group');
				  }
                  $display .= "<div class='boxleftside' $hideDiv><strong>User Group:</strong></div>";
                  #if($userlevel < 9){
                  #    $display .= "<div class='boxrightside'><span>".$_POST[0]['accountNum']."</span></div>";
                  #} else {
                      $display .= "<div class='boxrightside' $hideDiv><span>".form_dropdown('accountNum',$accnt,$accnt_group,"id='accountNum' ")."</span></div><br class='clear'/>";
                  #}
                  #$display .= "<div class='boxleftside'>User Group</div>";
                  #if($userlevel < 9){
                  #    $display .= "<div class='boxrightside'><span>".$_POST[0]['hidcompany']."</span>".form_hidden('hidcompany',$_POST[0]['hidcompany'])."</div>";
                  #}else{
                  #    $display .= "<div class='boxrightside'><span id='comp_name'>&nbsp;</span>".form_hidden('hidcompany',$_POST[0]['hidcompany'])."</div>";
                  #}
                  $display .= "<div class='boxleftside'><strong>Current Users:</strong></div>";
                  #if($userlevel < 9){
                      $display .= "<div class='boxrightside'><span>".$wizardrow[0]->lastnum."</span>".
                                              form_hidden(array('hidcount'=>$_POST[0]['hidcount']))."</div><br class='clear'/>";
                  #}else{
                  #    $display .= "<div class='boxrightside'><span id='count'>&nbsp;</pan>".
                  #                            form_hidden(array('hidcount'=>$_POST[0]['hidcount']))."</div>";
                  #}
                  $display .= "<div class='boxleftside'><strong>Additional Seats:</strong></div>";
                  $display .= "<div class='boxrightside'>".form_dropdown('txtSeats',array(1=>1,2=>2,3=>3,4=>4,5=>5,
                                                                                          6=>6,7=>7,8=>8,9=>9,10=>10,
                                                                                          11=>11,12=>12,13=>13,14=>14,15=>15,
                                                                                          16=>16,17=>17,18=>18,19=>19,20=>20),1,'class="txtSeats" onchange="checkPhoneIfExist()"').
                                            form_hidden('skip','skip')."</div><br class='clear'/>";
		  $display .= "<div class='boxleftside'><span><strong>Generate Phone Login(s):</strong></span></div>";
		  $display .= "<div class='boxrightside'>".form_dropdown('generate_phone',array('No','Yes'),0,"class='generate_phone' onChange='generatePhone();'")."</div><br class='clear'/>";
		  $display .= "<div class='boxleftside generate_phone_class'><span><strong>Phone Login:</strong></span></div>";
		  $display .= "<div class='boxrightside generate_phone_class'>".form_input("start_phone_exten",null,"class='start_phone_exten' maxlength='10' size='12' onkeydown='digitsOnly(event)' onkeyup='checkPhoneIfExist()' placeholder='eg. 8001'")."&nbsp;<span class='eloading' style='font-size: 10px;'></span></div><br class='clear generate_phone'/>";
           $display .= form_close();
           $wizardDisplay['display'] = array('breadcrumb'=>$this->config->item('base_url').'/img/step2of2-navigation-small.png',
                                             'content'=>array('left'=>$this->config->item('base_url').'/img/step2-trans.png',
                                                              'right'=>$display),
                                             'action'=>'<a onclick="cancelWizard(this)">Cancel</a>&nbsp;|&nbsp; <a onclick="next(this)">Next</a>');
           echo json_encode($wizardDisplay);
    }



    /*
     * userwizard
     * display wizard layouts
     * @author: Franco E. Hora <info@goautodial.com> 
     */
    function userwizard(){

         $username = $this->session->userdata('user_name');
         $userslevel = $this->session->userdata('users_level');
	 $usergroup = $this->session->userdata('user_group');
         if(empty($username)){
             #$this->commonhelper->deletesession($_SERVER['REMOTE_ADDR']);
             #die("Error: Session expired kindly re-login"); 
         }

         if(!empty($_POST)){
	    $f_phone = $_POST['start_phone_exten'];
	    $f_phoneEND = ($f_phone + $_POST['txtSeats']) - 1;
	    $query = $this->gouser->asteriskDB->query("SELECT * FROM phones WHERE extension BETWEEN '$f_phone' AND '$f_phoneEND'");
	    
	    if ($query->num_rows() < 1) {
               $this->gouser->asteriskDB->select('user as lastnum');
		if ($this->commonhelper->checkIfTenant($_POST['accountNum']))
		{
		    $this->gouser->asteriskDB->where('user_group =',$_POST['accountNum']);
		} else {
		    $this->gouser->asteriskDB->where_in('user_group',array('AGENTS','ADMIN','SUPERVISOR'));
		    $this->gouser->asteriskDB->like('user','agent','after');
		}
	       $this->gouser->asteriskDB->where('user_level !=','4');
	       $this->gouser->asteriskDB->where_not_in('user',array('VDAD','VDCL'));
               $this->gouser->asteriskDB->limit(1);
               $this->gouser->asteriskDB->order_by('user','desc'); 
               $a2bwizardresult = $this->gouser->asteriskDB->get_where('vicidial_users',array('user NOT LIKE'=>"%REMOTE%"));
               $wizardrow = $a2bwizardresult->result();

               # total of the agents       
               $totalagent = $_POST['hidcount'] + $_POST['txtSeats']; # total agent

               if($userslevel < 9){
                  # meaning you are in client mode
                  if($totalagent > 150){
                      die("Error: Only 150 agents are allowed.\n Please contact our Support Team to add more agents \n Thanks");;
                  }
               }
               #$campaigns = $this->commonhelper->getallowablecampaign($account_group,true,array('campaign_name NOT LIKE "%Survey%"'));
               $display =  form_open(null,"id='wizard-config'");
                              $user_level = $this->session->userdata('users_level');
                              if($user_level < 9){
                                  $readonly = "readonly='readonly'" ;
                              }else{
                                  $readonly = "";
                              }

                              //$lastnum = (substr($wizardrow[0]->lastnum,10)?substr($wizardrow[0]->lastnum,10):0);
			      if ($this->commonhelper->checkIfTenant($usergroup)) {
				   $lastnum = str_replace($usergroup,'',$wizardrow[0]->lastnum);
			      } else {
				   $accntNum = (!$this->commonhelper->checkIfTenant($_POST['accountNum'])) ? "agent" : $_POST['accountNum'];
				   $lastnum = str_replace($accntNum,'',$wizardrow[0]->lastnum);
			      }
                              for($i=1;$i<=$_POST['txtSeats'];$i++){
                                   $lastnum ++;
                                   if($lastnum < 10){
                                       $lastDigit = "00$lastnum";
                                   }elseif($lastnum < 100){
                                       $lastDigit = "0$lastnum";
                                   }else{
                                       $lastDigit = $lastnum; 
                                   }
                                   if($i > 1){
                                      $display .= "<br class='clear'/><div class='wizard-separator'>&nbsp;</div><br class='clear'/>";
                                   }
                                   $pass = $this->genpassword();
				    if (!$this->commonhelper->checkIfTenant($_POST['accountNum'])) {
					$userID = 'agent';
				    } else {
					$userID = "{$_POST['accountNum']}_";
				    }
				    $f_userID = str_replace("_","",$userID);
                                   $display .= "<div class='boxleftside'><strong>User Group:</strong></div>";
                                   $display .= "<div class='boxrightside'><span style='line-height:23px;'>".$_POST['accountNum']."</span>".form_hidden("group$i-accountNum",$_POST['accountNum'])."</div><br class='clear'/>";
                                   $display .= "<div class='boxleftside'><strong>User ID:</strong></div>";
                                   $display .= "<div class='boxrightside'>".form_input("group$i-user","{$f_userID}$lastDigit","id='group$i-user' maxlength='15' $readonly")."</div><br class='clear'/>";#"<input type='checkbox' checked='checked' onclick='formatuser(this)'></div><br class='clear'/>";
                                   $display .= "<div class='boxleftside'><strong>Password:</strong></div>";
                                   $display .= "<div class='boxrightside'>".form_input("group$i-pass",$this->commonhelper->get_system_settings('default_server_password'),"id='group$i-pass'")."</div><br class='clear'/>";
				   if ($_POST['generate_phone'])
				   {
					$display .= "<div class='boxleftside generate_phone'><strong>Phone Login:</strong></div>";
					$display .= "<div class='boxrightside generate_phone'><span style='line-height:23px;'>$f_phone</span>".form_hidden("group$i-phone_login","$f_phone","id='group$i-phone_login'")."</div><br class='clear'/>";
					$display .= "<div class='boxleftside generate_phone'><strong>Phone Password:</strong></div>";
					$display .= "<div class='boxrightside generate_phone'>".form_input("group$i-phone_pass",$this->commonhelper->get_system_settings('default_phone_login_password'),"id='group$i-phone_pass' maxlength='10' size='12' $readonly")."</div><br class='clear'/>";
				   }
                                   $display .= "<div class='boxleftside'><strong>Full Name:</strong></div>";
                                   $display .= "<div class='boxrightside'>".form_input("group$i-full_name",$_POST['hidcompany']."{$userID}$lastDigit ","id='group$i-full_name'")."</div><br class='clear'/>";
                                   //$display .= "<div class='boxleftside'><strong>Phone Login</strong></div>";
                                   //$display .= "<div class='boxrightside'>".form_input("group$i-phone_login","{$f_userID}$lastDigit","id='group$i-phone_login' readonly='readonly'")."</div>";
                                   $display .= "<div class='boxleftside'><strong>Active:</strong></div>";
                                   $display .= "<div class='boxrightside'>".form_dropdown("group$i-active",array('Y'=>'Yes','N'=>'No'),"id='group$i-active'")."</div><br class='clear'/>";
                                   if($_POST['txtSeats'] > 3){
                                        $savethisinfo["group$i-accountNum"] = $_POST['accountNum'];
                                        $savethisinfo["group$i-user"] = "{$f_userID}$lastDigit";
                                        $savethisinfo["group$i-pass"] = $this->commonhelper->get_system_settings('default_server_password');
                                        $savethisinfo["group$i-full_name"] = $_POST['hidcompany']."{$userID}$lastDigit ";
                                        $savethisinfo["group$i-phone_login"] = $f_phone;
                                        $savethisinfo["group$i-phone_pass"] = $this->commonhelper->get_system_settings('default_phone_login_password');
                                        $savethisinfo["group$i-active"] = "Y";
                                   }
				   $f_phone++;
                              }
                              # if adding new user is greater than 3 auto create data
                              if($_POST['txtSeats'] > 3){
                                  unset($_POST);
                                  $_POST = $savethisinfo;
                                  $this->autogenuser();
                                  exit;
                              }
               $display .= form_close();

               $wizardDisplay['prev_step'] = $_POST;
               $wizardDisplay['display'] = array('breadcrumb'=>$this->config->item("base_url").'/img/step3-navigation-small.png',
                                                 'content'=>array('left'=>$this->config->item('base_url')."/img/step3-trans.png",
                                                                  'right'=>$display),
                                                 'action'=>'<a onclick="resetwizard()">Back</a> | <a onclick="autogen()">Save</a>');
               echo json_encode($wizardDisplay);
	    } else {
		echo "Error: One or more of the phone login specified already exist.";
	    }
         }else{
              echo "Error: empty data";
         }
    }


    /*
     * autogenuser
     * automatic create user
     * @author: Franco E. Hora <info@goautodial.com>
     */

    function autogenuser(){


        $username = $this->session->userdata('user_name');
        $userlevel = $this->session->userdata('users_level');
        if(empty($username)){
            $this->commonhelper->deletesession($_SERVER['REMOTE_ADDR']);
            #die("Error: Session expired please relogin");
        }
 
        /*$useraccess = unserialize($this->session->userdata('useraccess'));
        if(!in_array('modify_users',$useraccess)){
              die("Error: You are not allowed to create New User");
        }*/


        # collect server info
        $this->gouser->asteriskDB->from('servers');
        #$this->gouser->asteriskDB->like(array('server_description'=>'MAIN DIALER'));
        $serverdata = $this->gouser->asteriskDB->get();
        if($serverdata->num_rows > 0){
             $serverinfo = $serverdata->result();
             $server_ip = $serverinfo[0]->server_ip;
        } else {
             die("Error: Empty server information");
        }
        if(array_key_exists("skip",$_POST)){

             # convert $_POST data to an array variable
             $submiteddata = $this->commonhelper->postToarray($_POST);

             # get data from a2billing_wizard for updates
             /*$a2bwizardresult = $this->gouser->asteriskDB->get_where('a2billing_wizard',array('account_num'=>$submiteddata['accountNum']));
             if($a2bwizardresult->num_rows() > 0){
                 $wizardrow = $a2bwizardresult->result();
                 $lastnum = $wizardrow[0]->lastnum;
             }else{
                 $lastnum = 0;
             }*/

             # total of the agents       
             #$totalagent = $submiteddata['hidcount'] + $submiteddata['txtSeats']; # total agent

             #if($userslevel < 9){
                  # meaning you are in client mode
             #     $processThis = 'if('.$totalagent.'<=50){ $issaved = $this->savingprocess($data);}';
             #     $processThis .= 'else{ die("Error: Only 50 agents are allowed.\n Please contact our Support Team to add more agents \n Thanks"); }';
             #}else{
                  # meaning you are in admin mode
                  $processThis = '$issaved = $this->savingprocess($data);';
             #}

             # loop for adding new user 
             for($i=1;$i<=$submiteddata['txtSeats'];$i++){
                 $lastnum++;
                 if($lastnum < 10){
                     $lastDigit = "00$lastnum";
                 }elseif($lastnum < 100){
                     $lastDigit = "0$lastnum";
                 }else{
                     $lastDigit = $lastnum; 
                 }
                 $submiteddata['lastDigit'] = $lastDigit;
                 $submiteddata['server_ip'] = $server_ip;
                 $data = $this->usersavedata($submiteddata);

                 if(!empty($data)){
                     eval($processThis); # saving part here
                     # update a2billing_wizard
	             #$this->gouser->asteriskDB->update('a2billing_wizard',array('num_seats'=>$totalagent,'lastnum'=>$lastnum),array('account_num'=>$submiteddata['accountNum']));
                     #$this->gouser->a2billingDB->update('cc_card',array('company_website'=>$totalagent),array('username'=>$submiteddata['accountNum']));
                     #$this->gouser->asteriskDB->update('a2billing_generate',array('status'=>'Y'));
                     $this->gouser->asteriskDB->update('servers',array('rebuild_conf_files'=>'Y'));
                 }else{
                     die("Error: Something went wrong to your data");
                 }
             }

             #$result = $this->reloadasterisk($issaved);
             if($result){
                die('Success: New User(s) successfully created');
             }else{
                die('Error: Something went wrong either on saving or on reloading asterisk');
             }

        } else {

            if(!empty($_POST)){

                 # group the created users
                 $data = array();
                 # loop for adding new user 
                 foreach($_POST as $key => $val){

                      $temp = $key;
                      $new_key = substr($temp,0,strpos($temp,"-"));
                      $data[$new_key][substr($key,strpos($temp,"-")+1)] = $val;

                 }

                 # process the data
                 foreach($data as $group){

                     # get data from a2billing_wizard for updates
		     $accntNum = (!$this->commonhelper->checkIfTenant($group['accountNum'])) ? "agent" : $group['accountNum'];
                     $this->gouser->asteriskDB->select('user as lastnum');
                     $this->gouser->asteriskDB->order_by('user','desc');
                     $a2bwizardresult = $this->gouser->asteriskDB->get_where('vicidial_users',array('full_name NOT LIKE'=>'%Survey%','user !='=>'VDAD','vicidial_users.user !='=>'VDCL','user_group'=>$accntNum));


                     if($a2bwizardresult->num_rows() > 0){
                         $wizardrow = $a2bwizardresult->result();
                         #$lastnum = ($wizardrow[0]->lastnum  - 1);
                         $lastnum = (substr($wizardrow[0]->lastnum,10)?substr($wizardrow[0]->lastnum,10):0);

                     }else{
                         $lastnum = 0;
                     }

                     $lastnum++;
                     if($lastnum < 10){
                         $lastDigit = "00$lastnum";
                     }elseif($lastnum < 100){
                         $lastDigit = "0$lastnum";
                     }else{
                         $lastDigit = $lastnum; 
                     }
                     $group['lastDigit'] = $lastDigit;
                     $group['server_ip'] = $server_ip;
                     /*if(!array_key_exists('user',$group)){
                         $group['user'] = "{$group['accountNum']}$lastDigit";
                     }*/
                     $newdata = $this->usersavedata($group);

                         #if($lastnum > 0){
                         #   var_dump($newdata); die('Error mabuhay');
                         #}
                     if(!empty($newdata)){
                         $isSaved = $this->savingprocess($newdata);
    	                 #$this->gouser->asteriskDB->update('a2billing_wizard',array('num_seats'=>$lastnum,'lastnum'=>$lastnum),array('account_num'=>$group['accountNum']));
                         #$this->gouser->a2billingDB->update('cc_card',array('company_website'=>$totalagent),array('username'=>$submiteddata['accountNum']));
                         #$this->gouser->asteriskDB->update('a2billing_generate',array('status'=>'Y'));
                         $this->gouser->asteriskDB->update('servers',array('rebuild_conf_files'=>'Y'));
                     } else {
                         die("Error: Something went wrong in saving data");
                     }
                 }
                 #$result = $this->reloadasterisk($isSaved);
                 /*if($result){
                     die('Success: New User(s) successfully created');
                 } else {
                     die("Error: Something went wrong in reloading asterisk or saving User");
                 }*/
                 die("Success: New User(s) successfully created");

            } else {
                die("Error: Empty raw data kindly check your data");
            }

        }


    }


    function usersavedata($postvars=array()){
        if(!empty($postvars)){

                 $pass = $this->genpassword();

            
             $userTemplate = $this->go_access->user_templates($postvars['accountNum']); 

             if($postvars['accountNum'] == "ADMIN"){

                 #$userTemplate['user'] = (array_key_exists('user',$postvars)?str_replace(" ","",$postvars['user']):"agent".$postvars['lastDigit']);
                 #$userTemplate['pass'] = (array_key_exists("pass",$postvars)?$postvars['pass']:$pass);
                 $userTemplate['user'] = $postvars['user'];
                 $userTemplate['pass'] = $postvars['pass'];
                 $userTemplate['user_group'] = $postvars['accountNum'];
                 $userTemplate['full_name'] = (array_key_exists('full_name',$postvars)?$postvars['full_name']:"agent1".$postvars['lastDigit']);

		 $varphone_login = $postvars['user'];
		 $newphone_login = str_replace("_","","$varphone_login");
		 
		 if (strlen($postvars['phone_login']) > 0) {
		    $userTemplate['phone_login'] = $postvars['phone_login'];
		    $userTemplate['phone_pass'] = $postvars['phone_pass'];
		 }
                 $userTemplate['agentonly_callbacks'] = '1';
                 $userTemplate['agentcall_manual'] = '1';
                 $userTemplate['active'] = $postvars['active'];
                 $userTemplate['condition'] = array('user'=>$userTemplate['user']);
                 $data['vicidial_users'] = $userTemplate;

             } elseif($postvars['accountNum'] == "SUPERVISOR"){

                 #$userTemplate['user'] = (array_key_exists('user',$postvars)?str_replace(" ","",$postvars['user']):"agent".$postvars['lastDigit']);
                 #$userTemplate['pass'] = (array_key_exists("pass",$postvars)?$postvars['pass']:$pass);
                 $userTemplate['user'] = $postvars['user'];
                 $userTemplate['pass'] = $postvars['pass'];
                 $userTemplate['user_group'] = $postvars['accountNum'];
                 $userTemplate['full_name'] = (array_key_exists('full_name',$postvars)?$postvars['full_name']:"agent2".$postvars['lastDigit']);
	
		 $varphone_login = $postvars['user'];
		 $newphone_login = str_replace("_","","$varphone_login");
		 
		 if (strlen($postvars['phone_login']) > 0) {
		    $userTemplate['phone_login'] = $postvars['phone_login'];
		    $userTemplate['phone_pass'] = $postvars['phone_pass'];
		 }
                 $userTemplate['agentonly_callbacks'] = '1';
                 $userTemplate['agentcall_manual'] = '1';
                 $userTemplate['active'] = $postvars['active'];
                 $userTemplate['condition'] = array('user'=>$userTemplate['user']);
                 $data['vicidial_users'] = $userTemplate;


             }else {
		 #more than 3 seats
                 #$data['vicidial_users']['user'] = (array_key_exists('user',$postvars)?$postvars['user']:"agentu".$postvars['lastDigit']);
                 #$data['vicidial_users']['pass'] = (array_key_exists("pass",$postvars)?$postvars['pass']:$pass);
                 #$data['vicidial_users']['full_name'] = (array_key_exists('full_name',$postvars)?$postvars['full_name']:"agent".$postvars['lastDigit']);
                 #$data['vicidial_users']['phone_login'] = (array_key_exists('phone_login',$postvars)?$newphone_login:substr($postvars['accountNum'],0,6).$postvars['lastDigit']);
                 #$data['vicidial_users']['phone_login'] = "";
                 #$data['vicidial_users']['phone_pass'] = "";
                 #$data['vicidial_users']['phone_pass'] = (array_key_exists('phone_pass',$postvars)?$postvars['phone_pass']:$pass);
		 $e_fullname = str_replace(" ","",$postvars['full_name']);
		 $final_uname = str_replace("_","",$e_fullname);
		 
                 #$data['vicidial_users']['user'] = (array_key_exists('full_name',$postvars)?str_replace(" ","",$postvars['full_name']):"agent".$postvars['lastDigit']);

		 #$data['vicidial_users']['user'] = (array_key_exists('full_name',$postvars)?$final_uname:"agent".$postvars['lastDigit']);
		 $data['vicidial_users']['user'] = $postvars['user'];
                 $data['vicidial_users']['pass'] = $postvars['pass'];
                 $data['vicidial_users']['user_group'] = $postvars['accountNum'];
	
		 $varfullname = $postvars['full_name'];
		 $newfullname = str_replace("_"," Agent ","$varfullname");

                 $data['vicidial_users']['full_name'] = (array_key_exists('full_name',$postvars)?$newfullname:"agent".$postvars['lastDigit']);
		 $query = $this->godb->query("SELECT group_level FROM user_access_group WHERE user_group='{$postvars['accountNum']}'");
                 $data['vicidial_users']['user_level'] = (($query->num_rows())?$query->row()->group_level:'1');
	
		 $varphone_login = $postvars['user'];
		 $newphone_login = str_replace("_","","$varphone_login");

		 if (strlen($postvars['phone_login']) > 0) {
		    $data['vicidial_users']['phone_login'] = $postvars['phone_login'];
		    $data['vicidial_users']['phone_pass'] = $postvars['phone_pass'];
		 }
                 $data['vicidial_users']['agentonly_callbacks'] = '1';
                 $data['vicidial_users']['agentcall_manual'] = '1';
                 $data['vicidial_users']['active'] = $postvars['active'];
                 $data['vicidial_users']['condition'] = array('user'=>$data['vicidial_users']['user']);
             }
	    
	    if (strlen($postvars['phone_login']) > 0) {
	     $VARSERVTYPE = $this->config->item('VARSERVTYPE');

	     $data['phones']['extension'] = $postvars['phone_login'];

//		 if($VARSERVTYPE=="public") {
//                 	$data['phones']['dialplan_number'] = '9999'.str_replace(" ","",$newphone_login);
//		 } else {
		    $data['phones']['dialplan_number'] = $postvars['phone_login'];
	     //}

	     $data['phones']['voicemail_id'] = $postvars['phone_login'];
	     $data['phones']['phone_ip'] = "";
	     $data['phones']['computer_ip'] = "";
	     $data['phones']['server_ip'] = $postvars['server_ip']; 
	     $data['phones']['login'] = $postvars['phone_login'];
	     $data['phones']['pass'] = $postvars['phone_pass'];
	     $data['phones']['status'] = "ACTIVE";
	     $data['phones']['active'] = "Y";
	     $data['phones']['phone_type'] = "";
	     $data['phones']['fullname']  = $postvars['phone_login'];
	     $data['phones']['company'] = $postvars['accountNum'];
	     $data['phones']['picture'] = "";
	     
//		 if($VARSERVTYPE=="public") {
//                   $data['phones']['protocol'] = 'EXTERNAL';
//		 } else {
	       $data['phones']['protocol'] = 'SIP';
	     //}

	     $data['phones']['local_gmt'] = '-5';
	     $data['phones']['outbound_cid'] = '0000000000';
	     $data['phones']['template_id'] = '--NONE--';
	     $conf_override="type=friend\nhost=dynamic\nsecret=".$data['vicidial_users']['phone_pass']
			   ."\ncanreinvite=no\ncontext=default\nqualify=yes\ndisallow=all\nallow=ulaw\nallow=g729\nallow=gsm\nqualify=yes";
	     $data['phones']['conf_override'] = $conf_override;
	     $data['phones']['user_group'] = $postvars['accountNum'];
	     $data['phones']['conf_secret'] = $postvars['phone_pass'];
	     $data['phones']['messages'] = '0';
	     $data['phones']['old_messages'] = '0';
	     $data['phones']['condition'] = array('extension'=>$postvars['phone_login']);
	    }
	    
             return $data;
        }else{
             return array();
        }
    }

    /*
     * savingprocess
     * collects all process to save new user
     * to use eval 
     * @author : Franco E. Hora <info@goautodial.com>
     */
    function savingprocess($data=null){


        if(!is_null($data)){


            $v_ucondition = $data['vicidial_users']['condition'];
            $phonescondition= $data['phones']['condition'];
            unset($data['vicidial_users']['condition']); # remove vicidial condition for saving
            unset($data['phones']['condition']); # remove phones condition for saving

            $result = $this->gouser->insertuser('vicidial_users',$data['vicidial_users'],$v_ucondition); #insert data to vicidial_users
            $this->gouser->goautodialDB->insert('go_login_type',array('account_num'=>$data['vicidial_users']['user'],'new_signup'=>'1'));
           
	    #milo
	     
            if($result){
		$details = "ADD new user vicidial_users values:";
		foreach($data['vicidial_users'] as $col => $val){
		    $details .= "$col = $val\n";
		}
		
		if (!is_null($phonescondition)) {
		    if($this->gouser->checkduplicate('phones',array($phonescondition))) {
    
			$result = $this->gouser->asteriskDB->insert('phones',$data['phones']);
    
			# let's write our text in conf
			if($result){
			    $texttowrite = '['.$data['phones']['extension'].']'."\n".$data['phones']['conf_override']."\n\n";
			    #$this->commonhelper->writetofile('/etc/asterisk/','sip-goautodial.conf',$texttowrite);
       
			    $details .= "ADD new phones on table phones values:";
			    foreach($data['phones'] as $col => $val){
				$details .= "$col = $val \n";
			    }
    
			    # update what must be updated 
			    #$this->gouser->asteriskDB->update('a2billing_wizard',array('num_seats')); 
			    return $reloadasterisk = true;
			}else{
			    //die("Something went wrong sorry");
			}
		    }
		}
		
		$query =null;
		foreach($this->gouser->asteriskDB->queries as $queries){
		   $query = $queries."\n";
		}

		$this->commonhelper->auditadmin("ADD",$details,$query);
            } 
	    #milo
         
        }else{
           die('There is no data to be process');
        }

    }

    function genpassword() {
        $length = 10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $string = '';    

        for ($p = 0; $p < $length; $p++) {
           $string .= $characters[mt_rand(0, strlen($characters))];
        }

        return $string;
    }


    /*
     * checkstate
     * function to determine hirarchy
     * @author : Franco E. Hora <info@goautodial.com>
     */
    function checkstate(){

        $account = $this->uri->segment(3);

        if(!is_bool($account)){
            $account = $account;
        }else{
            $account = null;
        }

        return $account;
         
    }

    /*
     * reloadasterisk
     * function to reload asterisk
     * @author : Franco E. Hora <info@goautodial.com>
     * @param : $reload > check if 1 reload asterisk
     */
    function reloadasterisk($reload){

       if($reload){ 
         $collectserver = $this->gouser->asteriskDB->get('servers'); 
         $servers = $collectserver->result();
         $result = exec('ls -R /var/run | grep asterisk.ctl');
         if($result == 'asterisk.ctl'){
                $socket = fsockopen("127.0.0.1", $servers[0]->telnet_port, $errno, $errstr, $timeout);
		fputs($socket, "Action: Login\r\n");
		fputs($socket, "UserName: ".$servers[0]->ASTmgrUSERNAME."\r\n");
		fputs($socket, "Secret: ".$servers[0]->ASTmgrSECRET."\r\n\r\n");
		fputs($socket, "Action: Command\r\n");
		fputs($socket, "Command: reload\r\n\r\n");
		$wrets=fgets($socket,128);
                return true;
         }else{
              die("Can't reload asterisk sorry");
         }
       }else{
           die('Oh no something went wrong on reloading asterisk');
       }
         

    }


    /*
     * reloaduserdisplay
     * update users display
     * @author: Franco E. Hora
     */
    function reloaduserdisplay(){
        $account = $this->checkstate();
        var_dump($account);die;
    }


    /*
     * groupbylist
     * display all users by group 
     * @author: Franco E. Hora <info@goautodial.com>
     */
    function groupbylist(){

        $bygroup = $this->gouser->bygroup(); 
        #$bygroup = $this->gouser->bygroupghost();

        $data['go_main_content'] = 'go_dashboard';
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';
	
	$data['theme'] = $this->session->userdata('go_theme');
	$data['bannertitle'] = 'Users';
	$data['adm']= 'wp-has-current-submenu';
	$data['hostp'] = $_SERVER['SERVER_ADDR'];
	$data['folded'] = 'folded';
	$data['foldlink'] = '';
	$togglestatus = "1";
	$data['togglestatus'] = $togglestatus;
	
        $data['users'] = $users;
        $data['bygroup'] = $bygroup;


        $this->load->view('go_user/go_user_group',$data);
    }



    /*
     * ungrouplist
     * display all users under current owner ungroup
     * @author: Franco E. Hora <info@goautodial.com>
     */
    function ungrouplist(){

        $ungroup = $this->gouser->bygroup($this->session->userdata('user_name'));
        $data['go_main_content'] = 'go_dashboard';
        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';

	$data['theme'] = $this->session->userdata('go_theme');
	$data['bannertitle'] = 'Users';
	$data['adm']= 'wp-has-current-submenu';
	$data['hostp'] = $_SERVER['SERVER_ADDR'];
	$data['folded'] = 'folded';
	$data['foldlink'] = '';
	$togglestatus = "1";
	$data['togglestatus'] = $togglestatus;			
		
        $data['users'] = $users;
        $data['toungroup'] = $ungroup;
	$data['groupvalues'] = $groupvalues;

        $this->load->view('go_user/go_user_ungroup',$data);

    }


    /*
     * go_users
     * initial page display of users template
     * @author: Franco E. Hora
     */
    function go_users(){


        $username = $this->session->userdata('user_name');
        if(empty($username)){
           $this->commonhelper->deletesession($_SERVER['REMOTE_ADDR']);
        }

        $data['username'] = $username;
        $data['user_group'] = $this->session->userdata('user_group');
        $data['user_level'] = $this->session->userdata('users_level'); 
        #if($data['user_level'] < 9){
           //$data['a2wizard'] = $this->gouser->retrievedata('a2billing_wizard',array('account_num'=>$data['username']));
        #}else{
           $usergroups = $this->gouser->retrievedata('vicidial_user_groups');
           if(!empty($usergroups)){
               foreach($usergroups as $group){
                   $accnt[$group->user_group] = $group->group_name;
               }
               $data['accnt_list'] = $accnt;
               $data['foradd'] = json_encode($accounts);
           }else{
               $accnt = array();
           }
        #}

        # for hello at top right
        $userinfo = $this->gouser->retrievedata('vicidial_users',array('user'=>$data['username']));
        $data['userfulname'] = $userinfo[0]->full_name;
		if (!$this->commonhelper->checkIfTenant($this->session->userdata('user_group')))
	            $userinfo = $this->gouser->retrievedata('vicidial_users',array('full_name NOT LIKE'=>'%Survey%','user !='=>'VDAD','vicidial_users.user !='=>'VDCL'),'count(user_id) as usercount');
		else
		    $userinfo = $this->gouser->retrievedata('vicidial_users',array('full_name NOT LIKE'=>'%Survey%','user !='=>'VDAD','vicidial_users.user !='=>'VDCL','user_group ='=>$this->session->userdata('user_group'),'user_level <='=>'6'),'count(user_id) as usercount');
        $data['a2wizard'] = $userinfo[0]->usercount;

        $data['cssloader'] = 'go_dashboard_cssloader.php';
        $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
        $data['jsbodyloader'] = 'go_dashboard_body_jsloader.php';

	$data['theme'] = $this->session->userdata('go_theme');
	$data['bannertitle'] = 'Users';
	$data['adm']= 'wp-has-current-submenu';
	$data['hostp'] = $_SERVER['SERVER_ADDR'];
	$data['folded'] = 'folded';
	$data['foldlink'] = '';
	$togglestatus = "1";
	$data['togglestatus'] = $togglestatus;			

        #$data['go_main_content'] = 'go_user/go_user_main';
        $data['go_main_content'] = 'go_user/go_user_main_ce';
        #collectusers
		if (!$this->commonhelper->checkIfTenant($this->session->userdata('user_group')))
			$users = $this->gouser->retrievedata('vicidial_users',array('active'=>'Y','full_name NOT LIKE'=>'%Survey%'),'user_id,user,full_name',null,array(array('full_name'=>'asc')));
		else
			$users = $this->gouser->retrievedata('vicidial_users',array('active'=>'Y','full_name NOT LIKE'=>'%Survey%','user_group'=>$this->session->userdata('user_group')),'user_id,user,full_name',null,array(array('full_name'=>'asc')));
        if(!empty($users)){
            foreach($users as $user){
                $data['users'][$user->user] = "$user->user - $user->full_name";
            }
        }

        $useraccess = $this->gouser->retrievedata('go_useraccess',null,null,null,array(array('access_type'=>'desc')));
        foreach($useraccess as $access){
            $data['useraccess'][($access->access_type==1?"Admin Group":"Agent Group")][$access->vicidial_users_column_name] = $access->useraccess_name;
        }


        # pagination part
	if ($this->commonhelper->checkIfTenant($data['user_group'])) {
		 $addedSQL = "and user_group='{$data['user_group']}'";
	}else{
                 $addedSQL = "";
        }


        if(!is_null($_POST)){
            $page = $_POST['page'];
	    $search_list = $_POST['search_list'];
	    $searchSQL = '';
	    if (!is_null($search_list)) {
		$searchSQL = "AND user RLIKE '$search_list' OR full_name RLIKE '$search_list'";
	    }
        } 
       	if (is_null($page) || $page < 1) { $page = 1; }
	$data['query'] = $this->gouser->asteriskDB->query("SELECT count(*) as ucnt from vicidial_users where user_level != '4' and full_name NOT LIKE '%Survey%' and user NOT IN ('VDCL','VDAD') $addedSQL $searchSQL order by user;");
        $data['page'] = $page;
	$data['search_list'] = $search_list;
	

        $this->load->view('includes/go_dashboard_template.php',$data);
    }


    /*
     * collectuserinfo
     * collect user information in vicidial_users
     * @author : Franco E. Hora <info@goautodial.com>
     */
    function collectuserinfo(){
        $vicidial_user_id = $this->uri->segment(3);
        $info = $this->gouser->retrievedata('vicidial_users',
                                            array('user_id'=>$vicidial_user_id),
                                            null);

        $this->output->set_header('Content-Type: text/json');
        $this->output->set_header('Content-Type: application/json');
        $this->output->set_output(json_encode($info));
    }


    /*
     * updateuser
     * update vicidial_users and go_users table
     * @author : Franco E. Hora <info@goautodial.com>
     */
    function updateuser(){

        $username = $this->session->userdata('user_name');
        if(empty($username)){
            $this->commonhelper->deletesession($_SERVER['REMOTE_ADDR']);
            #die("Error: Session expired kindly relogin");
        }
        $access = unserialize($this->session->userdata('useraccess'));
        # set access here
        if(in_array('modify_users',str_replace(':','',$access))){
            $usersid = $this->uri->segment(4);
            $vicidial_userid = $this->uri->segment(3);
            if(!array_key_exists('users_id',$_POST)){
                $_POST['users_id'] = $vicidial_userid;
            }
            $fields = $this->userhelper->postcleanup($_POST,$vicidial_userid,array("-$usersid","$usersid-"));
          
               #var_dump($fields);die("Error");
 
            $result['result'] = $this->gouser->updateuser($fields);

            $this->load->view('go_user/json_text.php',$result);
        }else{
            die("Error: You are not allowed to modify user"); 
        }
    }

     /*
     * deleteuser
     * update vicidial_users and go_users table and set to active = N
     * @author : Franco E. Hora <info@goautodial.com>
     */  
    function deleteuser($passuserid=null){

        $username = $this->session->userdata('user_name');
        if(empty($username)){
            $this->commonhelper->deletesession($_SERVER['REMOTE_ADDR']);
            #die("Error: Session expired kindly relogin");
        }
        $access = unserialize($this->session->userdata('useraccess'));
	
        if(in_array('modify_users',$access)){
             $userid = (!is_null($passuserid)?$passuserid:$this->uri->segment(3));
             $vicidial_user = $this->uri->segment(4);
             #$result = $this->gouser->updateuser(array($userid=>array('active'=>'N','users_id'=>$vicidial_user))); 
             
	     $query = $this->db->query("SELECT phone_login FROM vicidial_users WHERE user_id='$userid';");
	     $phone_login = $query->row()->phone_login;
	     
	     $this->gouser->asteriskDB->where(array('user_id'=>$userid,'user !='=>'admin'));
             $this->gouser->asteriskDB->delete('vicidial_users');
	     
	     $delquery = "DELETE FROM phones WHERE extension='$phone_login';";
     	     $this->db->query($delquery);
             
	     $this->gouser->asteriskDB->where(array('user_id'=>$userid,'user !='=>'admin'));
             $this->gouser->asteriskDB->delete('vicidial_users');
	     
	     $this->commonhelper->auditadmin('DELETE',"DELETED user: {$passuserid}{$vicidial_user}");

             echo "Success: User(s) deleted";
        }else{
            die("Error: You are not allowed to disable this user");
        }

    }



    /*
     * advancemodifyuser
     * modify template
     * @author: Franco E. Hora
     */
    function advancemodifyuser(){
 
        # set user access here
        #if(){
            $data['info'] = $this->gouser->retrievedata('vicidial_users',array('user'=>$_POST['userid']));
            # collect vicidial_user_groups
            $viciusergroups = $this->gouser->retrievedata('vicidial_user_groups',array('user_group'=>$data['info'][0]->user_group));
            
            # retrieve groups now lets get the name of the access
            if(!empty($viciusergroups)){
               $data['usergroups']  = $viciusergroups;
            }else{
               die('Got problem in retrieving groups contact your administrator');
            }
            # set allowedaccess
            $allowedtouser = unserialize($this->session->userdata('useraccess'));
            $data['allowedaccess'] = $allowedtouser;
            $data['useraccess'] = $this->gouser->asteriskDB->get('go_useraccess')->result();

            $this->load->view('go_user/go_user_advance_ce',$data);
        #}else{
            #die("Permission denied to view page");
        #}
    }


    /*
     * createusergroup
     * function to create a new group
     * @author : Franco E. Hora <info@goautodial.com>
     */
    function createusergroup(){
         # check session if still not expired
         # set user access
         # if(){
            $this->gouser->createusergroup($_POST['currentuser']); 
         # else{}
    }
  


    /*
     * saveusergroup
     * function to save group create and update
     * @author : Franco E. Hora <info@goautodial.com> 
     */
    function saveusergroup(){
         # check session if still not expired
         # set user access
         # if(){
              # vicidial_user_id of current user being modified
              $vicidial_user_id = $this->uri->segment(3);
              $newcreatedid = $this->uri->segment(4);
              if($newcreatedid != 'undefined'){
                  $postfields = $this->userhelper->postcleanup($_POST,$vicidial_user_id,array("-$newcreatedid","$newcreatedid-"));
                  $this->gouser->saveusergroup($postfields,$newcreatedid);
              }
         # else{}
    }

    /*
     * deleteitem
     * funciton to permanently delete group
     * @author: Franco E. Hora <info@goautodial.com>
     */
    function deleteitem(){
        $this->gouser->deleteitem('go_groupaccess','id ='.$_POST['groupid']);
    }


    /*
     * updategousers
     * funciton to update go_users table 
     * @author: Franco E. Hora <info@goautodial.com> 
     */
     function updategousers(){
         $vUserid = $this->uri->segment(3);
         if($vUserid != 'undefined'){
             $this->gouser->asteriskDB->trans_start();
                 $this->gouser->asteriskDB->where('vicidial_user_id',$vUserid);
                 $this->gouser->asteriskDB->update('go_users',$_POST);
             $this->gouser->asteriskDB->trans_complete();
             echo "User added to active group";
         }
     }

     /*
      * checkslave
      * check settings 
      */
     function getsettings(){
     
         $userlogged = $this->session->userdata('user_name');

         if(!is_null($userlogged) && !empty($userlogged)){
              $slavedbexist = $this->gouser->retrievedata('system_settings',array(),$fields);        
              return $slavedbexist;
         }else{
             die('Error no user is logged in');
         }
     }


     /*
      * userinfo
      * display user status only 
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function userinfo(){
         $username = $this->session->userdata('user_name');
         if(empty($username)){
              $this->commonhelper->deletesession($_SERVER['REMOTE_ADDR']);
         }
         if(!empty($username)){
             if(!empty($_POST['userid'])){
                $userinfo = $this->gouser->retrievedata('vicidial_users',array('user'=>$_POST['userid']));
                #search from vicidial_live_agent
                $fields = array('live_agent_id','user','server_ip','conf_exten','extension','status','lead_id','campaign_id',
                                'uniqueid','callerid','channel','random_id','last_call_time','last_update_time','last_call_finish',
                                'closer_campaigns','call_server_ip','user_level','comments','campaign_weight','calls_today','external_hangup',
                                'external_status','external_pause','external_dial','agent_log_id','last_state_change','agent_territories',
                                'outbound_autodial','manager_ingroup_set','external_igb_set_user');
                $liveagent = $this->gouser->retrievedata('vicidial_live_agents',array('user'=>$_POST['userid']),$fields);

                # if INCALL check if parked then override value of status
                if($liveagent[0]->status == 'INCALL'){
                   # check if you are in park or to change to dead
                   $ifparked = $this->gouser->asteriskDB->get_where('parked_channels',array('channel_group'=>$data['liveagent'][0]->callerid));
                   if($ifparked->num_rows > 0){
                       $liveagent[0]->status = 'PARK';
                   }else{
                       $ifVautocall = $this->gouser->asteriskDB->get_where('vicidial_auto_calls',array('callerid'=>$data['liveagent'][0]->callerid));
                       if($ifVautocall->num_rows == 0){
                           $liveagent[0]->status = 'DEAD';  
                       }
                   }
                }
                echo json_encode(array($userinfo,$liveagent));
             }else{
                die("Error: No user id passed");
             }
         }else{
             $this->commonhelper->deletesession($_SERVER['REMOTE_ADDR']);
             #die("Error: Session expired kindly re-login");
         }
     }


     /*
      * userstatus
      * function to display all status
      * @author : Franco E. Hora <info@goautodial.com>
      */
     function userstatus(){
         # user status first part
         # check if you still have session
         $userlogged = $this->session->userdata('user_name');
         if(isset($userlogged)){
             $araw = date('r');
             $userpass = $this->session->userdata('user_pass');
             $ip = $this->session->userdata('ip_address');
             $browser = $this->session->userdata('user_agent');
             $userfields = array('user','full_name','change_agent_campaign','modify_timeclock_log','user_level');
             $data['loggeduserinfo'] = $this->gouser->retrievedata('vicidial_users',array('user'=>$userlogged),$userfields);
             #$this->commonhelper->writetofile('/var/www/html/extrafiles/','project_auth_entries.txt',"VICIDIAL|GOOD|$araw|$userlogged|$userpass|$ip|$browser|".$data['loggeduserinfo'][0]->full_name."|\n"); 
         }else{
             # $this->commonhelper->writetofile('/var/www/html/extrafiles/','project_auth_entries.txt',"VICIDIAL|FAIL|$araw|$userlogged|$userpass|$ip|$browser|\n"); 
              die('destroy session and redirect to login');
         }
         #userinfo of the chosen user
         $data['userinfo'] = $this->gouser->retrievedata('vicidial_users',array('user'=>$_POST['userid']));

         #search from vicidial_live_agent
         $fields = array('live_agent_id','user','server_ip','conf_exten','extension','status','lead_id','campaign_id',
                      'uniqueid','callerid','channel','random_id','last_call_time','last_update_time','last_call_finish',
                      'closer_campaigns','call_server_ip','user_level','comments','campaign_weight','calls_today','external_hangup',
                      'external_status','external_pause','external_dial','agent_log_id','last_state_change','agent_territories',
                      'outbound_autodial','manager_ingroup_set','external_igb_set_user');
         $data['liveagent'] = $this->gouser->retrievedata('vicidial_live_agents',array('user'=>$_POST['userid']),$fields);

         # if INCALL check if parked then override value of status
         if($liveagent[0]->status == 'INCALL'){
             # check if you are in park or to change to dead
             $ifparked = $this->gouser->asteriskDB->get_where('parked_channels',array('channel_group'=>$data['liveagent'][0]->callerid));
             if($ifparked->num_rows > 0){
                 $data['liveagent'][0]->status = 'PARK';
             }else{
                $ifVautocall = $this->gouser->asteriskDB->get_where('vicidial_auto_calls',array('callerid'=>$data['liveagent'][0]->callerid));
                if($ifVautocall->num_rows == 0){
                    $data['liveagent'][0]->status = 'DEAD';  
                }
             }
         }
                  
         $this->gouser->asteriskDB->where(array('campaign_id'=>$data['liveagent'][0]->campaign_id));
         $data['campaigns'] = $this->gouser->asteriskDB->get('vicidial_campaigns')->result();
                 
         # welcome to the sencond part user stat oh yeah
         # check settings slave_db if exist
         #$slavedbexist = $this->getsettings();
         #if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
         #   $slaveExist = TRUE;
         #   $useThis = 'slaveDB'; 
         #}else{
            $slaveExist = '';
            $useThis = 'asteriskDB';
         #}
         $allowedcamp = $this->gouser->getallowedcampaign($data['userinfo'][0]->user_group,null,$slaveExist);
         $this->gouser->$useThis->select('allowed_reports');
         $allowedreports = $this->gouser->$useThis->get_where('vicidial_user_groups',array('user_group'=>$data['userinfo'][0]->user_group))->result();

         $this->load->view('go_user/userstatus',$data);
         
     }

     /*
      * agentTalkTimeStatus
      * collect agent Talk Time Status
      * @author : Franco E. Hora <info@goautodial.com>
      */
     function agentTalkTimeStatus(){
         if(!empty($_POST)){
             $datefrom = $_POST['dates'][0];
             $dateto = $_POST['dates'][1];
             if(!empty($_POST['user'])){
                 # welcome to the sencond part user stat oh yeah
                 # check settings slave_db if exist
                 #$slavedbexist = $this->getsettings();
                 #if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
                 #    $useThis = 'slaveDB'; 
                 #}else{
                     $useThis = 'asteriskDB';
                 #}
                 $this->gouser->$useThis->select('count(*) as rows,status, sum(length_in_sec) as length_in_sec');
                 $this->gouser->$useThis->where(array('user'=>$_POST['user']));
                 $this->gouser->$useThis->where("call_date BETWEEN '$datefrom 00:00:01' AND '$dateto 23:59:59'");
                 $this->gouser->$useThis->group_by('status');
                 $this->gouser->$useThis->order_by('status','asc');
                 $vicidial_log = $this->gouser->$useThis->get('vicidial_log')->result();
                 $ctr=0;
                 $totaltime = 0;
                 $totalcalls = 0;
                 if(!empty($vicidial_log)){
                     foreach($vicidial_log as $logs){
                         if(($ctr%2)!=0){
                             $rowclass = 'oddrow';
                         }else{
                             $rowclass = 'evenrow';
                         }
                         $totaltime += $logs->length_in_sec;
                         $totalcalls += $logs->rows;
                         echo "
                               <div class='$rowclass'>
                                   <div class='cols'>".$logs->status."</div>
                                   <div class='cols'>".$logs->rows."</div>
                                   <div class='cols'>".date('H:i:s',mktime(0,0,$logs->length_in_sec))."</div><br/>
                               </div>
                               <br class='spacer'/>";
                         $ctr++;
                     }
                     echo "<div class='totaltime'>
                               <div class='labelcols'>Total Calls</div>
                               <div class='totalcols'>$totalcalls</div>
                               <div class='totalcols'>".date("H:i:s",mktime(0,0,$totaltime))."</div><br/>
                           </div>";
                 }
             }
         }else{
         }
     }

     /*
      * agetnLoginLogouTime
      * get all login/logout time
      * @author : Franco E. Hora <info@goautodial.com>
      */
     function agentLoginLogoutTime(){
          if(!empty($_POST)){
              sleep(2);
              $datefrom = $_POST['dates'][0];
              $dateto =$_POST['dates'][1];
              if(!empty($_POST['user'])){
                   # start the query
                   #$slavedbexist = $this->getsettings();
                   #if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
                   #     $useThis = 'slaveDB'; 
                   #}else{
                        $useThis = 'asteriskDB';
                   #}
                   $fields = array('event','event_epoch','event_date','campaign_id','user_group','session_id','server_ip','extension','computer_ip'); 
                   $this->gouser->$useThis->select($fields); 
                   $this->gouser->$useThis->where(array('user'=>$_POST['user']));
                   $this->gouser->$useThis->where("event_date BETWEEN '$datefrom 00:00:01' AND '$dateto 23:59:59'");
                   $this->gouser->$useThis->order_by('event_date','asc');
                   $this->gouser->$useThis->limit(1000);
                   $loginlogout = $this->gouser->$useThis->get('vicidial_user_log')->result();

                   if(!empty($loginlogout)){
                       $ctr = 0; 
                       $totaltime = 0;
                       $starttime = 0;
                       foreach($loginlogout as $agentlogin){
                          if(($ctr%2)!=0){
                              $rowclass = 'oddrow';
                          }else{
                              $rowclass = 'evenrow';
                          }
                          if($agentlogin->event == "LOGOUT"){
                              $eventtime = ($agentlogin->event_epoch - $starttime);
                              $totaltime += $eventtime;
                          }else{
                              if($agentlogin->event == "LOGIN"){
                                  $starttime = $agentlogin->event_epoch;
                              }
                          }
                          echo "<div class='$rowclass'> 
                                    <div class='cols'>".$agentlogin->event."</div>
                                    <div class='cols'>$agentlogin->event_date</div>
                                    <div class='cols'>$agentlogin->campaign_id</div>
                                    <div class='cols'>$agentlogin->user_group</div>
                                    <div class='cols'>".(($agentlogin->event=='LOGIN')?'00:00:00': date('H:i:s',mktime(0,0,$eventtime)) )."</div>
                                    <div class='cols'>$agentlogin->session_id</div>
                                    <div class='cols'>$agentlogin->server_ip</div>
                                    <div class='cols'>$agentlogin->extension</div>
                                    <div class='cols'>$agentlogin->computer_ip</div>
                                    <br class='clear'/>
                                </div>
                                "; 
                           $ctr++;
                       }
                       /*echo "<div class='totaltime'>
                               <div class='labelcols'>Total Calls</div>
                               <div class='totalcols'>".date('H:i:s',mktime(0,0,$totaltime))."</div>
                               <div class='spacercols'>&nbsp;</div>
                           </div>";*/
                   }
              }
          }
     }

     /*
      * outboundThisTime
      * collect outbound call this time
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function outboundThisTime(){
        if(!empty($_POST)){
            #check user
            sleep(2);
            if(!empty($_POST['user'])){
                $datefrom = $_POST['dates'][0];
                $dateto =$_POST['dates'][1];
                #$slavedbexist = $this->getsettings();
                #if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
                #     $useThis = 'slaveDB'; 
                #}else{
                     $useThis = 'asteriskDB';
                #}
                $fields = array('uniqueid','lead_id','list_id','campaign_id','call_date','start_epoch',
                                'end_epoch','length_in_sec','status','phone_code','phone_number','user',
                                'comments','processed','user_group','term_reason','alt_dial'); 
                $this->gouser->$useThis->select($fields);
                $this->gouser->$useThis->where("call_date BETWEEN '$datefrom 00:00:01' AND '$dateto 23:59:59'");
                $this->gouser->$useThis->where(array('user'=>$_POST['user']));
                $this->gouser->$useThis->order_by('call_date','desc');
                $this->gouser->$useThis->limit(1000);
                $outboundcalls = $this->gouser->$useThis->get('vicidial_log')->result();
               
                if(!empty($outboundcalls)){
                    $ctr = 0;
                    foreach($outboundcalls as $outbounds){
                        if(($ctr%2)!=0){
                            $rowclass = 'oddrow';
                        }else{
                            $rowclass = 'evenrow';
                        }
                        echo "<div class='$rowclass'>
                                  <div class='cols'>$outbounds->call_date</div>
                                  <div class='cols'>".date("H:i:s",mktime(0,0,$outbounds->length_in_sec))."</div>
                                  <div class='cols'>$outbounds->status</div>
                                  <div class='cols'>$outbounds->phone_number</div>
                                  <div class='cols'>$outbounds->campaign_id</div>
                                  <div class='cols'>$outbounds->user_group</div>
                                  <div class='cols'>$outbounds->list_id</div>
                                  <div class='cols'><a id='$outbounds->lead_id' class='leadcall' onclick='leadinfo(this.id)'>$outbounds->lead_id</a></div>
                                  <div class='cols'>$outbounds->term_reason</div>
                                  <br class='clear'/>
                              </div>";
                        $ctr++;
                    }
                    echo "<br class='clear'/>";
                }
            }
        }
     }

     /*
      * inboundThisTime
      * collect inbound call this time
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function inboundThisTime(){
        if(!empty($_POST)){
            sleep(2);
            #check user
            if(!empty($_POST['user'])){
                $datefrom = $_POST['dates'][0];
                $dateto =$_POST['dates'][1];
                #$slavedbexist = $this->getsettings();
                #if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
                #     $useThis = 'slaveDB'; 
                #}else{
                     $useThis = 'asteriskDB';
                #}
                $fields = array('call_date','length_in_sec','status','phone_number','campaign_id','queue_seconds','list_id','lead_id','term_reason'); 
                $this->gouser->$useThis->select($fields);
                $this->gouser->$useThis->where("call_date BETWEEN '$datefrom 00:00:01' AND '$dateto 23:59:59'");
                $this->gouser->$useThis->where(array('user'=>$_POST['user']));
                $this->gouser->$useThis->order_by('call_date','desc');
                $this->gouser->$useThis->limit(1000);
                $inboundcalls = $this->gouser->$useThis->get('vicidial_closer_log')->result();
               
                if(!empty($inboundcalls)){
                    $ctr = 0;
                    foreach($inboundcalls as $inbounds){
                        if(($ctr%2)!=0){
                            $rowclass = 'oddrow';
                        }else{
                            $rowclass = 'evenrow';
                        }
                        $totalagents += $inbounds->length_in_sec;
                        $agentsec = ($inbounds->length_in_sec - $inbounds->queue_seconds);
                        $totalagentsec += $agentsec;
                        echo "<div class='$rowclass'>
                                  <div class='cols'>$inbounds->call_date</div>
                                  <div class='cols'>".date("H:i:s",mktime(0,0,$inbounds->length_in_sec))."</div>
                                  <div class='cols'>$inbounds->status</div>
                                  <div class='cols'>$inbounds->phone_number</div>
                                  <div class='cols'>$inbounds->campaign_id</div>
                                  <div class='cols'>$inbounds->queue_seconds</div>
                                  <div class='cols'>".date("H:i:s",mktime(0,0,$agentsec))."</div>
                                  <div class='cols'>$inbounds->list_id</div>
                                  <div class='cols'><a id='$inbounds->lead_id' class='leadcall' onclick='leadinfo(this.id)'>$inbounds->lead_id</a></div>
                                  <div class='cols'>$inbounds->term_reason</div>
                                  <br class='clear'/>
                              </div>
                              ";
                        $ctr++;
                    }
                    /*echo "<div class='totaltime'>
                              <div class='labelcols'>Total Calls</div>
                              <div class='totalcols'>".date("H:i:s",mktime(0,0,$totalagents))."</div>
                              <div class='spacercols'>&nbsp;</div>
                              <div class='totalcols'>".date("H:i:s",mktime(0,0,$totalagentsec))."</div>
                              <br/>
                          </div>";*/
                }
            }
        }
     }


     /*
      * recordingThisTime
      * collect recordings this time
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function recordingThisTime(){
        if(!empty($_POST)){
            sleep(2);
            #check user
            if(!empty($_POST['user'])){
                $datefrom = $_POST['dates'][0];
                $dateto =$_POST['dates'][1];
                #$slavedbexist = $this->getsettings();
                #if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
                #     $useThis = 'slaveDB'; 
                #}else{
                     $useThis = 'asteriskDB';
                #}
                #$fields = array('recording_id','channel','server_ip','extension','start_time','start_epoch','end_time','end_epoch','length_in_sec','length_in_min','filename','location','lead_id','user','vicidial_id'); 
                $fields = array('recording_id','channel','server_ip','extension','start_time','start_epoch','end_time','end_epoch','length_in_sec','length_in_min','filename','location','lead_id','user','vicidial_id'); 
                $this->gouser->$useThis->select($fields);
                $this->gouser->$useThis->where("start_time BETWEEN '$datefrom 00:00:01' AND '$dateto 23:59:59'");
                $this->gouser->$useThis->where(array('user'=>$_POST['user']));
                $this->gouser->$useThis->order_by('recording_id','desc');
                $this->gouser->$useThis->limit(1000);
                $recordings = $this->gouser->$useThis->get('recording_log')->result();
              
                if(!empty($recordings)){
                    $ctr = 0;
                    foreach($recordings as $record){
                        if(($ctr%2)!=0){
                            $rowclass = 'oddrow';
                        }else{
                            $rowclass = 'evenrow';
                        }
                        echo "<div class='$rowclass'>
                                  <div class='cols'><a id='$record->lead_id' class='leadcall' onclick='leadinfo(this.id)'>$record->lead_id</a></div>
                                  <div class='cols'>$record->start_time</div>
                                  <div class='cols'>".date("H:i:s",mktime(0,0,$record->length_in_sec))."</div>
                                  <div class='cols'>$record->recording_id</div>
                                  <div class='cols elipsis bubble toolTip' title='$record->filename'>".substr($record->filename,0,20)."...</div>
                                  <div class='cols elipsis toolTip' title='$record->location'><a href='$record->location' >".substr($record->location,0,20)."...</a></div>
                                  <br class='clear'/>
                              </div>
                              ";
                        $ctr++;
                    }
                }
            }
        }
     }

     /*
      * manualoutboundThisTime
      * collect manual outbound call this time
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function manualoutboundThisTime(){
        if(!empty($_POST)){
            sleep(2);
            #check user
            if(!empty($_POST['user'])){
                $datefrom = $_POST['dates'][0];
                $dateto =$_POST['dates'][1];
                #$slavedbexist = $this->getsettings();
                #if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
                #     $useThis = 'slaveDB'; 
                #}else{
                     $useThis = 'asteriskDB';
                #}
                $fields = array('call_date','call_type','server_ip','phone_number','number_dialed','lead_id','callerid','group_alias_id','preset_name','customer_hungup','customer_hungup_seconds'); 
                $this->gouser->$useThis->select($fields);
                $this->gouser->$useThis->where("call_date BETWEEN '$datefrom 00:00:01' AND '$dateto 23:59:59'");
                $this->gouser->$useThis->where(array('user'=>$_POST['user']));
                $this->gouser->$useThis->order_by('call_date','desc');
                $this->gouser->$useThis->limit(1000);
                $manualoutboundcalls = $this->gouser->$useThis->get('user_call_log')->result();

               
                if(!empty($manualoutboundcalls)){
                    $ctr = 0;
                    foreach($manualoutboundcalls as $manualoutbounds){
                        if(($ctr%2)!=0){
                            $rowclass = 'oddrow';
                        }else{
                            $rowclass = 'evenrow';
                        }
                        echo "<div class='$rowclass'>
                                  <div class='cols'>$manualoutbounds->call_date</div>
                                  <div class='cols'>$manualoutbounds->call_type</div>
                                  <div class='cols'>$manualoutbounds->server_ip</div>
                                  <div class='cols'>$manualoutbounds->phone_number</div>
                                  <!-- <div class='cols'>$manualoutbounds->number_dialed</div> -->
                                  <div class='cols'><a id='$manualoutbounds->lead_id' class='leadcall' onclick='leadinfo(this.id)'>$manualoutbounds->lead_id</a></div>
                                  <div class='cols'>$manualoutbounds->callerid</div>
                                  <!--<div class='cols'>$manualoutbounds->group_alias_id</div>
                                  <div class='cols'>$manualoutbounds->preset_name</div>
                                  <div class='cols'>$manualoutbounds->customer_hungup ".$manualoutbounds->customer_hungup_seconds."</div> -->
                                  <br class='clear'/>
                              </div>
                              ";
                        $ctr++;
                    }
                }
            }
        }
     }

     /*
      * leadsearchThisTime
      * collect lead search this time
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function leadsearchThisTime(){
        if(!empty($_POST)){
            sleep(2);
            #check user
            if(!empty($_POST['user'])){
                $datefrom = $_POST['dates'][0];
                $dateto =$_POST['dates'][1];
                #$slavedbexist = $this->getsettings();
                #if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
                #     $useThis = 'slaveDB'; 
                #}else{
                     $useThis = 'asteriskDB';
                #}
                $fields = array('event_date','source','results','seconds','search_query'); 
                $this->gouser->$useThis->select($fields);
                $this->gouser->$useThis->where("event_date BETWEEN '$datefrom 00:00:01' AND '$dateto 23:59:59'");
                $this->gouser->$useThis->where(array('user'=>$_POST['user']));
                $this->gouser->$useThis->order_by('event_date','desc');
                $this->gouser->$useThis->limit(1000);
                $leadsearches = $this->gouser->$useThis->get('vicidial_lead_search_log')->result();

               
                if(!empty($leadsearches)){
                    $ctr = 0;
                    foreach($leadsearches as $leadsearch){
                        if(($ctr%2)!=0){
                            $rowclass = 'oddrow';
                        }else{
                            $rowclass = 'evenrow';
                        }
                        $query = substr($leadsearch->search_query,strpos($leadsearch->search_query,"where")+6);
                        echo "<div class='$rowclass'>
                                  <div class='cols'>$leadsearch->event_date</div>
                                  <div class='cols'>$leadsearch->source</div>
                                  <div class='cols'>$leadsearch->results</div>
                                  <div class='cols'>$leadsearch->seconds</div>
                                  <div class='cols elipsis bubble toolTip' title='".$query."'>".$query."</div>
                                  <br class='clear'/>
                              </div>
                              ";
                        $ctr++;
                    }

                }
            }
        }
     }



     /*
      * agentActivity
      * function to collect agent activity
      * @author : Franco E. Hora <info@goautodial.com>
      */
     function agentActivity(){
         if(!empty($_POST)){
             sleep(2);
             if(!empty($_POST['user'])){
                 $datefrom = $_POST['dates'][0];
                 $dateto =$_POST['dates'][1];
                 #$slavedbexist = $this->getsettings();
                 #if(!empty($slavedbexist[0]->slave_db_server) && !is_null($slavedbexist[0]->slave_db_server)){
                 #     $useThis = 'slaveDB'; 
                 #}else{
                      $useThis = 'asteriskDB';
                 #}
                 $fields = array('event_time','lead_id','campaign_id','pause_sec','wait_sec','talk_sec','dispo_sec','dead_sec','status','sub_status','user_group'); 
                 $this->gouser->$useThis->select($fields);
                 $this->gouser->$useThis->where(array('user'=>$_POST['user']));
                 $this->gouser->$useThis->where("event_time BETWEEN '$datefrom 00:00:01' AND '$dateto 23:59:59'");
                 $this->gouser->$useThis->where("( (pause_sec > 0) or (wait_sec > 0) or (talk_sec > 0) or (dispo_sec > 0) )");
                 $this->gouser->$useThis->order_by('event_time','desc');
                 $this->gouser->$useThis->limit(1000);           
                 $agentactivities = $this->gouser->$useThis->get('vicidial_agent_log')->result();


                 if(!empty($agentactivities)){
                     foreach($agentactivities as $activity){
                         if(($ctr%2)!=0){
                             $rowclass = 'oddrow';
                         }else{
                             $rowclass = 'evenrow';
                         }

                             $totalpause += $activity->pause_sec;
                             $totalwait += $activity->wait_sec;
                             $totaltalk += $activity->talk_sec;
                             $totaldispo += $activity->dispo_sec;
                             $totaldead += $activity->dead_sec;
                             $customer_sec = $activity->talk_sec - $activity->dead_sec;
                             $totalcustomer += $customer_sec;
                         echo "<div class='$rowclass'>
                                   <div class='cols'>$activity->event_time</div>
                                   <div class='cols'>".date("H:i:s",mktime(0,0,$activity->pause_sec))."</div>
                                   <div class='cols'>".date("H:i:s",mktime(0,0,$activity->wait_sec))."</div>
                                   <div class='cols'>".date("H:i:s",mktime(0,0,$activity->talk_sec))."</div>
                                   <div class='cols'>".date("H:i:s",mktime(0,0,$activity->dispo_sec))."</div>
                                   <div class='cols'>".date("H:i:s",mktime(0,0,$activity->dead_sec))."</div>
                                   <div class='cols'>".date("H:i:s",mktime(0,0,$customer_sec))."</div>
                                   <div class='cols'>$activity->status</div>
                                   <div class='cols'><a id='$activity->lead_id' class='leadcall' onclick='leadinfo(this.id)'>$activity->lead_id</a></div>
                                   <div class='cols'>$activity->campaign_id</div>
                                   <div class='cols'>$activity->sub_status</div>
                                   <br class='clear'/>
                               </div>
                               ";
                        $ctr++;
                     }
                     /*echo "<div class='totaltime'>
                               <div class='labelcols'>Total Calls</div>
                               <div class='totalcols'>".date("H:i:s",mktime(0,0,$totalpause))."</div>
                               <div class='totalcols'>".date("H:i:s",mktime(0,0,$totalwait))."</div>
                               <div class='totalcols'>".date("H:i:s",mktime(0,0,$totaltalk))."</div>
                               <div class='totalcols'>".date("H:i:s",mktime(0,0,$totaldispo))."</div>
                               <div class='totalcols'>".date("H:i:s",mktime(0,0,$totaldead))."</div>
                               <div class='totalcols'>".date("H:i:s",mktime(0,0,$totalcustomer))."</div>
                               <div class='spacercols'>&nbsp;</div>
                               <br class='clear'/>
                           </div>";*/
                 }
             }
         }
     }


     /*
      * emergencylogout
      * function to logout the user
      * @author: Franco E. Hora <info@goautodial.com>
      */
     function emergencylogout(){

         $NOW_TIME = date("Y-m-d H:i:s");
         $thedate = date('U');
         $inactive_epoch = ($thedate - 60);
         $this->gouser->asteriskDB->select('user,campaign_id,UNIX_TIMESTAMP(last_update_time)as last_update_time');
         $Vliveagent = $this->gouser->asteriskDB->get_where('vicidial_live_agents',array('user'=>$_POST['user']))->result(); 
         if(!empty($Vliveagent)){
             #if($Vliveagent[0]->last_update_time > $inactive_epoch){
                 $fields = array('agent_log_id','user','server_ip','event_time','lead_id','campaign_id','pause_epoch','pause_sec',
                                 'wait_epoch','wait_sec','talk_epoch','talk_sec','dispo_epoch','dispo_sec','status','user_group',
                                 'comments','sub_status','dead_epoch','dead_sec');
                 $this->gouser->asteriskDB->select($fields);
                 $this->gouser->asteriskDB->where(array('user'=>$Vliveagent[0]->user));
                 $this->gouser->asteriskDB->order_by('agent_log_id','desc'); 
                 $this->gouser->asteriskDB->limit(1);
                 $agentlog = $this->gouser->asteriskDB->get('vicidial_agent_log');
                 #the result is 
                 $agents = $agentlog->result();
                 $this->gouser->asteriskDB->trans_start();
                     if($agentlog->num_rows > 0){

                         if($agents[0]->wait_epcoh < 1 || ($agents[0]->status == 'PAUSE' && $agents[0]->dispo_epoch < 1) ){
                             $agents[0]->pause_sec = (($thedate-$agents[0]->pause_epoch)+$agents[0]->pause_sec);
                             $updatefields = array('wait_epoch'=>$thedate,'pause_sec'=>$agents[0]->pause_sec);
                         }else{
                             if($agents[0]->talk_epoch < 1){
                                 $agents[0]->wait_sec = (($thedate-$agents[0]->wait_epoch) + $agents[0]->wait_sec);
                                 $updatefields = array('talk_epoch'=>$thedate,'wait_sec'=>$agents[0]->wait_sec);
                             }else{
                                 if(is_null($agents[0]->status) && $agents[0]->lead_id > 0){
                                     $this->gouser->asteriskDB->where(array('lead_id'=>$agents[0]->lead_id));
                                     $updatethis = array('status'=>'PU');
                                     $this->gouser->asteriskDB->update('vicidial_list',$updatethis);
                                 }
                                 if($agents[0]->dispo_epoch < 1){
                                     $agents[0]->talk_sec = ($thedate-$agents[0]->talk_epoch);
                                     $updatefields = array_merge(array('dispo_epoch'=>$thedate,'talk_sec'=>$agents[0]->talk_sec),$updatethis);
                                 }else{
                                     if($agents[0]->dispo_epoch < 1){
                                         $agents[0]->dispo_sec = ($thedate-$agents[0]->dispo_epoch);
                                         $updatefields = array('dispo_sec'=>$agents[0]->dispo_sec);
                                     }
                                 }
                             }
                         }
                         $this->gouser->asteriskDB->where(array('agent_log_id'=>$agents[0]->agent_log_id));
                         $this->gouser->asteriskDB->update('vicidial_agent_log',$updatefields);
                     }
                     $this->gouser->asteriskDB->delete('vicidial_live_agents',array('user'=>$agents[0]->user));
                     #agent session
		     $this->gouser->asteriskDB->delete('go_agent_sessions',array('sess_agent_user'=>$agents[0]->user));
                     
                     $valuetolog = array('user'=>$agents[0]->user,'event'=>'LOGOUT','campaign_id'=>$agents[0]->campaign_id,'event_date'=>$NOW_TIME,
                                         'event_epoch'=>$thedate,'user_group'=>$agents[0]->user_group);
                     # force logout the user
                     $this->commonhelper->auditlogs('vicidial_user_log',$valuetolog);
                     
                 $this->gouser->asteriskDB->trans_complete();
 
                 if($this->gouser->asteriskDB->trans_status !== false){
                     die('Emergency logout complete make sure agent browser is closed');
                 }else{
                     die('Problem in attempt to logout agent '.$agents[0]->user);
                 }
             #}
         }else{
	     $VliveagentSess = $this->gouser->asteriskDB->get_where('go_agent_sessions',array('sess_agent_user'=>$_POST['user']))->result();
	     if (!empty($VliveagentSess))
	     {
                 $this->gouser->asteriskDB->trans_start();
		     $this->gouser->asteriskDB->delete('go_agent_sessions',array('sess_agent_user'=>$_POST['user']));
                 $this->gouser->asteriskDB->trans_complete();
		 
                 if($this->gouser->asteriskDB->trans_status !== false){
                     die('Emergency logout complete make sure agent browser is closed');
                 }else{
                     die('Problem in attempt to logout agent '.$_POST['user']);
                 }
	     } else {
                 die("Agent ".$_POST['user']." is not logged in");
	     }
         }
     }



     /*
      * usersearch
      * search for users autocomplete callback
      * @author : Franco E. Hora <info@goautodial.com>
      */
     function usersearch(){

         $level = $this->session->userdata('users_level');
         $this->gouser->asteriskDB->select(array('full_name as label','user as value'));
         $this->gouser->asteriskDB->where("(user like '%".$_POST['term']."%' OR full_name like '%".$_POST['term']."%')");
         if($level < 9){
             $this->gouser->asteriskDB->where(array('user_group'=>$this->session->userdata('user_name')));
         }
         $users = $this->gouser->asteriskDB->get('vicidial_users')->result(); 

         $data['result'] = json_encode($users);
  
         $this->load->view('go_user/json_text.php',$data);

     }


     /*
      * batchupdate
      * update batch of users
      * @author : Franco E. Hora <info@goautodial.com>
      */
     function batchupdate(){
        $username = $this->session->userdata('user_name');
        if(empty($username)){
            $this->commonhelper->deletesession($_SERVER['REMOTE_ADDR']);
            #die("Error: Session expired kindly relogin");
        }
        $access = unserialize($this->session->userdata('useraccess'));
        # set access here
        #if(in_array('modify_users',$access)){
             if(!empty($_POST['users'])){
                  $users = json_decode($_POST['users']);
                  $ctr = 0;
                  $results = array();
                  foreach($users as $user){
                     if($user->action != "D"){
                         $result = $this->gouser->updateuser(array($user->user_id=>array('active'=>$user->action,'users_id'=>$user->user)));
                         if(preg_match("/Error/",$result)){
                             $result = "Error: Something went wrong while saving user : $user->user";
                         }
                     }else{
                         $this->deleteuser($user->user_id);
			 
                     }
                     $results[$ctr] = array("result"=>$result,"user"=>$user->user);
                     $ctr++;
                  }
                  #echo json_encode($results);
                  die("Success: Batch action complete");
             }else{
                die("Error: No users selected");
             }
       # }else{
            #die("Error:You are not allowed to modify this user");
        #}
     }

     function copyuser(){
         if(!empty($_POST)){
             $override = $this->gouser->retrievedata('vicidial_override_ids',
                                                     array('id_table'=>'vicidial_users'),
                                                     array('value'),
                                                     array(array('active'=>'1')));
             if(!empty($override)){
                 $this->gouser->asteriskDB->where('id_table','vicidial_users');
                 $this->gouser->asteriskDB->where('active','1');
                 $this->gouser->asteriskDB->update('vicidial_override_ids',array('value'=>$_POST['user']));
             }
             $userToCopy = $this->gouser->retrievedata('vicidial_users',array('user'=>$_POST['source_user']));
             if(!empty($userToCopy)){
        

                 $groupID = $this->session->userdata('user_group');
                 $totalAgents = $this->gouser->retrievedata('vicidial_users',array('user_group'=>$groupID));
                 $_POST['lastDigit'] = (count($totalAgents) - 1);
 
                 $lastDigit = $_POST['lastDigit']+1;
                 if($lastDigit < 100){
                     $lastDigit = "0$lastDigit";
                 }elseif($lastDigit < 10){
                     $lastDigit = "00$lastDigit";
                 }
                 # collected fields with values
                 $fields = get_object_vars($userToCopy[0]);  
                 # format the user adding prefix COPY
                 unset($fields['user_id']);
                 $fields['user'] = "$groupID$lastDigit";
                 $newUser = $fields['user'];
                 $fields['pass'] = $_POST['pass'];
                 $fields['full_name'] = (!empty($_POST['full_name'])?$_POST['full_name']:"agent".$lastDigit);
                 # end formating values



		 $VARSERVTYPE = $this->config->item('VARSERVTYPE');

                 $phones['extension'] = str_replace(" ","",$fields['user']);

		 if($VARSERVTYPE=="public") {
                 	$phones['dialplan_number'] = '9999'.str_replace(" ","",$fields['user']);
		 } else {
                 	$phones['dialplan_number'] = str_replace(" ","",$fields['user']);
		 }

                 $phones['voicemail_id'] = $fields['user'];
                 $phones['phone_ip'] = "";
                 $phones['computer_ip'] = "";
                 $phones['server_ip'] = ""; 
                 $phones['login'] = str_replace(" ","",$fields['user']);
                 $phones['pass'] = $fields['phone_pass'];
                 $phones['status'] = "ACTIVE";
                 $phones['active'] = "Y";
                 $phones['phone_type'] = "";
                 $phones['fullname']  = $fields['user'];
                 $phones['company'] = $fields['user_group'];
                 $phones['picture'] = "";
		 
		 if($VARSERVTYPE=="public") {
                   $phones['protocol'] = 'EXTERNAL';
		 } else {
                   $phones['protocol'] = 'SIP';
		 }

                 $phones['local_gmt'] = '-5';
                 $phones['outbound_cid'] = '0000000000';
                 $phones['template_id'] = '--NONE--';
                 $conf_override="type=friend\nhost=dynamic\nsecret=".$fields['phone_pass']
                               ."\ncanreinvite=no\ncontext=default\nqualify=yes\ndisallow=all\nallow=ulaw\nallow=g729\nallow=gsm\nqualify=yes";
                 $phones['conf_override'] = $conf_override;
                 $phones['user_group'] = $fields['user_group'];

                 # database intervensions starts here 
                 # insert in vicidial_users                 
                 $this->gouser->asteriskDB->insert("vicidial_users",$fields);
                 $this->gouser->goautodialDB->insert('go_login_type',array('account_num'=>$fields['user'],'new_signup'=>'1'));
                 $this->gouser->asteriskDB->insert('phones',$phones);

                 # insert in vicidial_inbound_group_agent
                 $viga = $this->gouser->retrievedata('vicidial_inbound_group_agents',
                                                     array('user'=>$_POST['user']),
                                                     array('group_id','group_rank','group_weight'));
                 #if(!empty($viga)){
                     $fields = get_object_vars($viga[0]);
                     $fields['user'] = $newUser;
                     $fields['calls_today'] = "0";
                     $this->gouser->asteriskDB->insert('vicidial_inbound_group_agents',$fields);
                 #}

                 # insert in vicidial_campaign_agents
                 $vca = $this->gouser->retrievedata('vicidial_campaign_agents',
                                                    array('user'=>$_POST['user']),
                                                    array('campaign_id','campaign_rank','campaign_weight'));
                 #if(!empty($vca)){
                     $fields = get_object_vars($vca[0]);
                     $fields['user'] = $newUser;
                     $fields['calls_today'] = "0";
                     $this->gouser->asteriskDB->insert('vicidial_campaign_agents',$fields);
                 #}
                 
                 $this->commonhelper->auditadmin("COPY","User {$_POST['user']} copied from {$_POST['source_user']}");
                 echo "Success: User copy complete";
             } #empty user 
         } # not empty $_POST
     }

     function duplicate(){

           $this->gouser->asteriskDB->where('user',$_POST['user']);
           $result = $this->gouser->asteriskDB->get('vicidial_users');
           if($result->num_rows() > 0){
                 echo "";
           } else {
                 echo "1";
           }
     }

    
     function CheckActionIfAllowed(){

          $actionType = $this->uri->segment(3);
          $permissions = $this->commonhelper->getPermissions("user",$this->session->userdata("user_group"));
	
          switch(strtolower($actionType)){
               case "create":
               case "update":
               case "delete":
                     $action = "user_".strtolower($actionType);
               break;
               default: 
                     die("Error: Unknown action");
               break;
          }

          if($permissions->$action == "N"){
             die("Error: You don't have permission to $actionType this record(s)");
          }

     }
    
    function pagination($type,$search)
    {
	$account = $this->session->userdata('user_group');

	if ($this->commonhelper->checkIfTenant($account)) {
		 $addedSQL = "and user_group='$account'";
	}else{
                 $addedSQL = "";
        }

	$searchSQL = '';
	if ((!is_null($search) && $search) || strlen($search) > 0) {
	    $searchSQL = "AND user RLIKE '$search' OR full_name RLIKE '$search'";
	}
	if (is_null($page) || $page < 1) { $page = 1; }
	$query = $this->gouser->asteriskDB->query("SELECT count(*) as ucnt from vicidial_users where active='Y' and user_level != '4' $addedSQL $searchSQL order by user;");
	$rp 	= 25;
	$total 	= $query->row()->ucnt;
	$limit 	= 5;
	$pg 	= $this->commonhelper->paging($page,$rp,$total,$limit);
	$start	= (($page-1) * $rp);
	
	if ($type=="links") {
	    if ($pg['last'] > 1) {
		$pagelinks  = '<a title="Go to First Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="gopage('.$pg['first'].')"><span><img src="'.base_url().'/img/first.gif"></span></a>';
		$pagelinks .= '<a title="Go to Previous Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="gopage('.$pg['prev'].')"><span><img src="'.base_url().'/img/prev.gif"></span></a>';
	
		for ($i=$pg['start'];$i<=$pg['end'];$i++) { 
		    if ($i==$pg['page']) $current = 'color: #F00;cursor: default;'; else $current="";
		
		    $pagelinks .= '<a title="Go to Page '.$i.'" style="vertical-align:text-top;padding: 0px 2px;'.$current.'" onclick="gopage('.$i.')"><span>'.$i.'</span></a>';
		}
		
		//$pagelinks .= '<a title="View All Pages" style="vertical-align:text-top;padding: 0px 2px;" onclick="gopage(\'ALL\')"><span>ALL</span></a>';
		$pagelinks .= '<a title="Go to Next Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="gopage('.$pg['next'].')"><span><img src="'.base_url().'/img/next.gif"></span></a>';
		$pagelinks .= '<a title="Go to Last Page" style="vertical-align:baseline;padding: 0px 2px;" onclick="gopage('.$pg['last'].')"><span><img src="'.base_url().'/img/last.gif"></span></a>';
	    } else {
		if ($rp > 25) {
		    $pagelinks  = '<div style="cursor: pointer;font-weight: bold;">';
		    $pagelinks .= '<a title="Back to Paginated View" style="vertical-align:text-top;padding: 0px 2px;" onclick="gopage(1)"><span>BACK</span></a>';
		    $pagelinks .= '</div>';
		} else {
		    $pagelinks = '';
		}
	    }
	} else {
	    $pagelinks = "Displaying {$pg['istart']} to {$pg['iend']} of {$pg['total']} users";
	}
	
	echo $pagelinks;
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
	
    function checkphone()
    {
        $postdata = $this->commonhelper->postToarray($_POST);
	$p_start = $postdata['phone'];
	$p_end = ($postdata['phone'] + $postdata['seat']) - 1;
	
	if (strlen($p_start) > 2) {
	    $query = $this->gouser->asteriskDB->query("SELECT * FROM phones WHERE extension BETWEEN '$p_start' AND '$p_end'");
	    if ($query->num_rows() > 0)
	    {
		$result = "<span style='color:red;font-size:10px;'>Not Available</span>";
	    } else {
		$result = "<span style='color:green;font-size:10px;'>Available</span>";
	    }
	} else {
	    $result = "<span style='color:red;font-size:10px;'>Minimum of 3 characters.</span>";
	}
	echo "$result";
    }

}
