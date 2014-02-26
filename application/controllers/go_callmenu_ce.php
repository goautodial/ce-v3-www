<?php
########################################################################################################
####  Name:             	go_callmenu_ce.php                                                  ####
####  Type:             	ci controller - administrator                                       ####	
####  Version:          	3.0                                                                 ####	   
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			            ####
####  Written by:       	Franco Hora				                            ####
####  Edited by:		GoAutoDial Development Team					    ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_callmenu_ce extends Controller{
      function __construct(){
          parent::Controller();
          $this->load->library(array('session','commonhelper'));
          $this->load->helper(array('date','file','form','html'));
	  $this->is_logged_in();
      }
      /*
       * index
       * display listing of all call menus
       * @author: Franco Hora <franco@goautodial.com>
       */
      function index(){
           $username = $this->session->userdata("user_name");
           $result = $this->commonhelper->simpleretrievedata('vicidial_call_menu',
                                                             "menu_id,menu_name,menu_prompt,menu_timeout",
                                                             null,
                                                             array(array('menu_id'=>$username)));
           $data['result'] = $result->result();
           $this->load->view('go_callmenu/go_callmenu_list_ce',$data);
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
