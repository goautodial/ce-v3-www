<?Php
############################################################################################
####  Name:             go_support.php                                                  ####
####  Type: 		ci controller 							####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Franco E. Hora <franco@goautodial.com>        ####
####  License:          AGPLv2                                                          ####
############################################################################################

class Go_support_ce extends Controller{


     function __construct(){
 
         parent::Controller();
         $this->load->library(array('session','curl','commonhelper'));
         $this->load->helper(array('date','file','form','download','html'));
         $this->load->model(array('go_support','go_carriers'));
	 $this->lang->load('userauth', $this->session->userdata('ua_language'));
	 $this->is_logged_in();

     }



     function index(){

                 ini_set("display_error",1);

                $username = $this->session->userdata('user_name');
                $usergroup = $this->session->userdata('user_group');
                #if(empty($username)){
                #    $this->commonhelper->deletesession($_SERVER['REMOTE_ADDR']);
                #}

                #$cc_card_info = $this->go_support->get_info($username);
         
                if($usergroup !== "ADMIN"){
                    #$justgovoipinfo = $this->go_carriers->get_sippy_info();
                    $account = $this->commonhelper->getAccountInfo('username',$username);

                    $getaccount['num_rows'] = 1;
                    $getaccount['email'] = $account->structmem('email')->me['string'];
                    $getaccount['fullname'] = $account->structmem('first_name')->me['string'] . " " . $account->structmem('last_name')->me['string'];
                    $getaccount['firstname'] = $account->structmem('first_name')->me['string'];
                    $getaccount['lastname'] = $account->structmem('last_name')->me['string'];
                    $getaccount['username'] = $account->structmem('username')->me['string'];
                    $getaccount = (object) $getaccount;


                } else {
                    $getaccount['num_rows'] = 1;
                    #$getaccount['email'] = "noc@goautodial.com";
                    $getaccount['email'] = "";
                    $getaccount['fullname'] = "GoAutoDial Noc";
                    $getaccount['firstname'] = "GoAuotoDial Noc";
                    $getaccount['lastname'] = " ";
                    $getaccount['username'] = $username;
                    $getaccount = (object) $getaccount;
                }


                if($getaccount->num_rows > 0){

                   #$cc_card = $cc_card_info->result();
                   #$data['acct_email'] = $cc_card[0]->email;
                   $data['acct_email'] = $getaccount->email;
                   $this->checkcreateuser($data['acct_email'],$getaccount->fullname);
		   $data['go_main_content'] = 'go_freshdesk';
		   $data['cssloader'] = 'go_dashboard_cssloader.php';
		   #$data['jsheaderloader'] = 'go_administrator_header_jsloader.php';
		   $data['jsheaderloader'] = 'go_dashboard_header_jsloader.php';
		   $data['jsbodyloader'] = 'go_freshdesk_body_jsloader.php';
		   $data['userfulname'] = $this->session->userdata('full_name');

 //		   $this->load->model('go_freshdesk');
//		   $callfunc = $this->go_freshdesk->go_get_userinfo();
//		   $data['gouser'] = $this->session->userdata('user_name');
//		   $data['gopass'] = $this->session->userdata('user_pass');
		
//		   $data['theme'] = $this->session->userdata('go_theme');


                   $data['cc_card'] = $getaccount;
		   $data['bannertitle'] = $this->lang->line('go_voicefile_banner');
		   $data['sup']= 'wp-has-current-submenu';
		   $data['hostp'] = $_SERVER['SERVER_ADDR'];
		   $data['folded'] = 'folded';
		   $data['foldlink'] = '';
                   $data['account'] = $this->session->userdata('user_group');

		   $this->load->view('includes/go_freshdesk_template', $data);


                } else {

                     die("Error : Are you a registered user?");

                }

     }


     /*
      * checkcreateuser
      * check and create user if not yet in freshdesk
      * @author : Franco E. Hora <info@goautodial.com>
      * @param  : $email > to check in freshdesk
      */
     function checkcreateuser($email,$name){

          if(!empty($email)){

                $user_check = $this->curl->simple_get($this->config->item("freshdesk_url")."/contacts.xml",
                                                          array('query'=>"email is $email"),
                                                          array(CURLOPT_USERPWD=>$this->config->item("freshdesk_user").":".$this->config->item("freshdesk_pass"),CURLOPT_SSL_VERIFYPEER=>false));


                $xml = simplexml_load_string($user_check);

                if(count($xml->children()) > 0){

                    $createUser = false;

                } else {

                    $createuser = true;

                }

                if($createUser){


                    $result = exec('/usr/share/goautodial/goautodialc.pl '.
                          "'curl -u ".$this->config->item("freshdesk_user").":".$this->config->item("freshdesk_pass")." -H ".
                          '"Content-Type: application/xml; charset=UTF-8" '.
                          '-d xml="<user><name>'.$name.'</name><email>'.$email.'</email></user>" '.
                          "-kX POST ".$this->config->item("freshdesk_sub_url")."/contacts.xml'"
                        );

                    if(preg_match("/error/",$result)){

                        die("Error: Something went wrong please contact your support");

                    }

                } 


                return true; # always return true to continue

          } else {

                die("Admin account? Please use our freshdesk");

          }

     }


     /*
       * newticket
       * use to create a new ticket in freshdesk 
       * @author : Franco E. Hora <info@goautodial.com>
       */
     function newticket(){

           #require("/var/www/html/staticvars.php"); 
           $direct_path = "/var/www/cloudhv/";
           if(!empty($_POST['accountNum'])){


               $handler = @opendir("{$direct_path}uploads");  
               $display_content = "";
               while($file = readdir($handler)){

                    if(($file != "." && $file != "..") && preg_match("/".$_POST['accountNum']."/",$file)){

                         $targetFile = "{$direct_path}uploads/$file";
                         $file_name = $file;
                         #$file_handler = fopen("{$direct_path}uploads/$file_name","r");
                         $attachment = base64_encode(file_get_contents($targetFile));
                         #fclose($file_handler);
                         unlink($targetFile);
                         $attachThis = '<attachments type=\"array\"><attachment><resource name=\"'.$file_name.'\" content-type=\"application/octet-stream\" type=\"file\"><![CDATA['.$attachment.']]></resource></attachment></attachments>'; 
                    }

               }
               closedir($handler);
           }


           $result = exec('/usr/share/goautodial/goautodialc.pl '.
                          "'curl -u ".$this->config->item("freshdesk_user").":".$this->config->item("freshdesk_pass")." -H ".
                          '"Content-Type: application/xml; charset=UTF-8" '.
                          '-d xml="<helpdesk-ticket><email>'.$_POST['accntemail'].'</email>'.$attachThis.'<subject>'.$_POST['subject']." [Account No. ".$_POST['accountNum'].']</subject><description>'.preg_replace('/(\n)/','&#10;',$_POST['description']).'</description></helpdesk-ticket>" '.
                          "-kX POST ".$this->config->item("freshdesk_sub_url")."/helpdesk/tickets.xml'");
           echo('New ticket submited');

        }


        /*
         * modifyticket
         * use to modify(add a note) a(in a) ticket on freshdesk
         * @author : Franco Hora <info@goautodial.com>
         */
        function modifyticket(){

             $result = exec('/usr/share/goautodial/goautodialc.pl ' .
                            "'curl -u ".$this->config->item("freshdesk_user").":".$this->config->item("freshdesk_pass")." -H ".
                            '"Content-Type: application/xml; charset=UTF-8" '.
                            '-d xml="<helpdesk-note><user_id>'.$_POST['requesterid'].'</user_id><body>'.$_POST['note'].'</body><private>false</private></helpdesk-note>" '.
                            "-kX POST ".$this->config->item("freshdesk_sub_url")."/helpdesk/tickets/".$_POST['id']."/notes.xml'"
                           );
             echo ('Added new note successful');
        }


        /*
         * updateconversation
         * function to update conversation under each ticket
         * @author: Franco Hora <info@goautodial.com>
         */
        function updateconversation(){
             $email = $this->uri->segment(3);
             $xml = $this->go_support->helpdesknote($email,$_POST['displayid']); 
             $data['xml'] = $xml;
             $this->load->view('go_support/conversation.php',$data); 
        }


	function go_curl_view_tickets()
	{

		$data['data'] = $this->go_support->go_curl_get_data();
		$this->load->view('go_support/go_curl_output', $data);
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


?>
