<?php
############################################################################################
####  Name:             go_support.php                                                  ####
####  Type: 		ci model                                                        ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Christopher Lomuntad <info@goautodial.com>    ####
####  Modified by:                      - Franco E. Hora <info@goautodial.com>          ####
####  License:          AGPLv2                                                          ####
############################################################################################

class Go_support extends Model {
	


        function __construct()
	{
	    parent::Model();
	    $this->goautodialDB = $this->load->database('goautodialdb', true);
	    $this->asteriskDB = $this->load->database('dialerdb', true);
	    #$this->a2billingDB = $this->load->database('billingdb', true);
	}


        /*
         * get_info
         * get data from cc_card
         * @author : Franco E. Hora <info@goautodial.com>
         * @param  : $username > user account in a2billing
         */
        function get_info($username){
 
             if(!empty($username)){

                  $this->a2billingDB->where(array('username'=>$username));
                  $cc_card_info = $this->a2billingDB->get('cc_card');
                  if($cc_card_info->num_rows() > 0){

                       return $cc_card_info;   

                  } else {

                      die("Error : You are not registered yet ");

                  }

             } else {

             }

        }


	
	function go_curl_get_data()
	{

		$curlEmail = $this->uri->segment(3);
		$curldata = $this->curl->simple_get($this->config->item("freshdesk_url").'/helpdesk/tickets/user_ticket.xml',
                                                    array('email'=>$curlEmail),
                                                    array(CURLOPT_USERPWD =>$this->config->item("freshdesk_user").':'.$this->config->item("freshdesk_pass"),CURLOPT_SSL_VERIFYPEER => false));

		$xml = simplexml_load_string($curldata);
		$i = 0;
     
 
                if($xml === false){ # empty tickets

                      return array();

                }


		foreach ($xml->children() as $child)
		{
                     # helpdesk-tickets tag
			foreach ($child->children() as $subchild)
			{
				$testName = $subchild->getName();
				if ($testName == 'requester-id')
				{
                                    if($subchild != ''){
					$curldata = $this->go_curl_view_user($subchild);
					$data[$i]['requester-name'] = $curldata['name'];
                                    }else{
                                        $data[$i]['requester-id'] = $subchild; # if ever the subchild is empty
                                    }
				}
				
				if ($testName == 'responder-id')
				{
                                    if($subchild != ''){
					$curldata = $this->go_curl_view_user($subchild);
                                        if(!is_null($curldata)){
					    $data[$i]['responder-name'] = $curldata['name'];
                                        } else {

                                            $data[$i]['responder-name'] = "GoAutoDial Support Team";

                                        }
                                    }else{
                                        $data[$i]['responder-id'] = $subchild; # new created ticket view not yet responded by agent
                                    }
				}
				
				if ($testName == 'notes')
				{
                                        $ctr=0;
                                        $helpdesk_note = array();
					foreach ($subchild->children() as $apo)
					{
						$apoName = $apo->getName();
						if ($apoName == 'helpdesk-note')
						{
                                                    $theUser = $this->go_curl_view_user($apo->{'user-id'});
                                                    /*if((is_null($theUser) && $apo->private == "false") || (!is_null($theUser) && $apo->private == "true")){*/
                                                    if($apo->private == "false"){
							foreach ($apo->children() as $apoSaTuhod)
							{
								$apoSaTuhodName = $apoSaTuhod->getName();
                                                                if($apoSaTuhodName == 'user-id'){
                                                                    $bagongApoSaTuhod = $this->go_curl_view_user($apoSaTuhod);
								    $helpdesk_note[$ctr]['user-info'] = $bagongApoSaTuhod;
								    $helpdesk_note[$ctr][$apoSaTuhodName] = $apoSaTuhod;
                                                                }else{
								    $helpdesk_note[$ctr][$apoSaTuhodName] = $apoSaTuhod;
                                                                }
							}
                                                    }
						}
                                                $ctr++;
					}
                                        $data[$i][$testName][$apoName] = $helpdesk_note;
                                    
				}else{
                
				    $data[$i][$testName] = $subchild;
                                }
			}
			$i++;
		}

		return $data;


	}  # function end
	
	
	function go_curl_view_user($curlUID)
	{
     
		#$string = $this->curl->simple_get($this->config->item("freshdesk_url").'/contacts/'.$curlUID.'.xml',
		$string = $this->curl->simple_get($this->config->item("freshdesk_url").'/agents.xml',
                                                   array('query'=>"user_id is $curlUID"),
                                                    array(CURLOPT_USERPWD => $this->config->item("freshdesk_user").':'.$this->config->item("freshdesk_pass"), CURLOPT_SSL_VERIFYPEER => false));

		$xml = simplexml_load_string($string);
                if($xml != false){
		    foreach ($xml->children() as $child)
		    {
			$testName = $child->getName();
			$data[$testName] = $child;
		    }
                }
		
		return $data;
	}



        function helpdesknote($email,$displayid){
       
		$curldata = $this->curl->simple_get($this->config->item("freshdesk_url").'/helpdesk/tickets/'.$displayid.'.xml',
                                                    array('email'=>$email),
                                                    array(CURLOPT_USERPWD => $this->config->item("freshdesk_user").':'.$this->config->item("freshdesk_pass"),CURLOPT_SSL_VERIFYPEER => false));
		$xml = simplexml_load_string($curldata);

                foreach($xml->children() as $level1child){

                    $level1name = $level1child->getName();
                    if($level1name == 'display-id'){
                        $data[$level1name] = $level1child;
                    }

 		    if ($level1name == 'notes')
		    {
                          $ctr=0;
                          $helpdesk_note = array();
		          foreach ($level1child->children() as $apo)
			  {
			       $apoName = $apo->getName();
			       if ($apoName == 'helpdesk-note')
			       {
			           foreach ($apo->children() as $apoSaTuhod)
				   {
				        $apoSaTuhodName = $apoSaTuhod->getName();
                                        if($apoSaTuhodName == 'user-id'){
                                               $bagongApoSaTuhod = $this->go_curl_view_user($apoSaTuhod);
					       $helpdesk_note[$ctr]['user-info'] = $bagongApoSaTuhod;
					       $helpdesk_note[$ctr][$apoSaTuhodName] = $apoSaTuhod;
                                        }else{
				  	       $helpdesk_note[$ctr][$apoSaTuhodName] = $apoSaTuhod;
                                        }
				   }
				}
                                $ctr++;
		          }
                          $data[$level1name][$apoName] = $helpdesk_note;
                                    
		    }/*else{
                          $data[$level1name] = $level1child;
                    }*/
                }
                return $data;

        }

}
