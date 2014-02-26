<?php
############################################################################################
####  Name:             go_account_info.php                                             ####
####  Type: 		    ci model                                                        ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Januarius Manipol <januarius@goautodial.com>  ####
####                                      Christopher Lomuntad <chris@goautodial.com>   ####
####  License:          AGPLv2                                                          ####
############################################################################################

class Go_account_info extends Model {
	
   function __construct()
	{
	    parent::Model();
	    $this->db = $this->load->database('dialerdb', true);
	    //$this->a2db = $this->load->database('billingdb', true);
	}

	function go_search_user()
	{
	   $queryString = $this->uri->segment(3);
	   #$query_date =  date('Y-m-d');
	   $this->db->cache_on();
	   $query = $this->db->query("SELECT user FROM vicidial_users_view WHERE user rlike '$queryString' order by user asc LIMIT 10");	 
	   $datacount = $query->num_rows();
	   $dataval   = $query->result();
	   $return['datacount']=$datacount;
	   $return['dataval']  =$dataval;
	   return $return; 
	}
	
	
	function go_search_list()
	{
	   $queryString = $this->uri->segment(3);
	   #$query_date =  date('Y-m-d');
	   $this->db->cache_on();
	   $query = $this->db->query("SELECT fullname FROM vicidial_list_view WHERE fullname rlike '$queryString' order by fullname asc  LIMIT 10");	 
	   $datacount = $query->num_rows();
	   $dataval   = $query->result();
	   $return['datacount']=$datacount;
	   $return['dataval']  =$dataval;
	   return $return; 
	}	
	

	function go_search_phone()
	{
	   $queryString = $this->uri->segment(3); 
	   #$query_date =  date('Y-m-d');
	   $this->db->cache_on();
	   $query = $this->db->query("SELECT phone_number FROM vicidial_list_view WHERE phone_number rlike '$queryString' order by phone_number asc LIMIT 10");	 
	   $datacount = $query->num_rows();
	   $dataval   = $query->result();
	   $return['datacount']=$datacount;
	   $return['dataval']  =$dataval;
	   return $return; 
	}
	
	function go_search_clear()
	{
		$this->db->cache_delete_all();
	}
	
	function go_check_balance()
	{
		$userID = $this->session->userdata('user_name');
		$query = $this->a2db->query("select credit,currency from cc_card where username='$userID'");
		$resultsu = $query->row();
		$credit = (isset($resultsu->credit)) ? $resultsu->credit : 0;
		$currency = (isset($resultsu->currency)) ? $resultsu->currency : "USD";
		$return['credit'] = number_format($credit / $this->go_get_currency($currency), 2);
		$return['currency'] = $resultsu->currency;
		
		$query = $this->a2db->query("SELECT rateinitial FROM cc_ratecard WHERE dialprefix='1' LIMIT 1;");
		$resultsu = $query->row();
		$return['rateinitial'] = $resultsu->rateinitial;
		
		return $return;
	}
	
	function go_check_info()
	{
		$userID = $this->session->userdata('user_name');
		$query = $this->a2db->query("select uipass,firstname,lastname,email,phone,address,city,state,zipcode,country,useralias,company_name from cc_card where username='$userID'");
		$resultsu = $query->row();
		$return['accnt_num'] = $userID;
		$return['accnt_pass'] = $resultsu->uipass;
		$return['firstname'] = $resultsu->firstname;
		$return['lastname'] = $resultsu->lastname;
		$return['username'] = $resultsu->useralias;
		$return['company'] = $resultsu->company_name;
		$return['email'] = $resultsu->email;
		$return['phone'] = $resultsu->phone;
		$return['address'] = $resultsu->address;
		$return['city'] = $resultsu->city;
		$return['state'] = $resultsu->state;
		$return['zipcode'] = $resultsu->zipcode;
		$return['country'] = $resultsu->country;
		return $return;
	}

	function go_payment_history()
        {
                $userID = $this->session->userdata('user_name');
		$stmt = "select id from cc_card where username='$userID'";
                $query = $this->a2db->query($stmt);
                $resultsu = $query->row();
		
		$stmtx ="SELECT date,payment,description FROM cc_logpayment WHERE card_id =  '$resultsu->id' ORDER BY date DESC;";
	  	$listall = $this->a2db->query($stmtx);
                              $ctr = 0;
                              foreach($listall->result() as $info){
                                  $lists[$ctr] = $info;
                                  $ctr++;
                              }
                $return['historydata'] = $lists; 
		return $return;
        }
	
	function go_get_num_seats()
	{
		$group = $this->go_get_groupid();
        if (!$this->commonhelper->checkIfTenant($group))
            $ul = '';
        else
            $ul = "and user_group='$group'";
     
		$query = $this->db->query("select count(user) as num_seats from vicidial_users where user_level < '4' and user NOT IN ('VDAD','VDCL') $ul");
		$resultsu = $query->row();
		$num_seats = $resultsu->num_seats;
		return $num_seats;
	}
	
	function go_get_logins()
	{
		$queryString = $this->uri->segment(3);
		$group = $this->go_get_groupid();
        if (!$this->commonhelper->checkIfTenant($group))
            $ul = '';
        else
            $ul = "AND vicidial_users.user_group='$group'";
            
		$this->db->cache_on();
		if ($queryString=='list_agents')
			$query = $this->db->query("SELECT user,pass,full_name,active FROM vicidial_users WHERE user_level='1' $ul AND user NOT IN ('VDAD','VDCL') ORDER BY user");
		else
			$query = $this->db->query("SELECT login,phones.pass,vicidial_users.active FROM phones,vicidial_users WHERE login=REPLACE(vicidial_users.phone_login,'_','') AND login!='' $ul AND user_level='1' and vicidial_users.user NOT IN ('VDAD','VDCL') ORDER BY login");

		$datacount = $query->num_rows();
		$dataval   = $query->result();
		$return['datacount']=$datacount;
		$return['dataval']  =$dataval;
		return $return;
	}
	
	function go_get_groupid()
	{
		$userID = $this->session->userdata('user_name');
	    $query = $this->db->query("select user_group from vicidial_users where user='$userID'"); 
	    $resultsu = $query->row();
	    $groupid = $resultsu->user_group;	 
	    return $groupid;
	}
	
	function go_get_currency($mycur)
	{
		$query = $this->a2db->query("select value from cc_currencies where currency='$mycur'");
		$resultsu = $query->row();
		$mycur = $resultsu->value;
		return $mycur;
	}

}
