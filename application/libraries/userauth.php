<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|-------------------------------------------------------------------------
| Code Igniter Mini-App, User Authentication Class
| Functionality is User Authenication and Session Validation
|
| Copyright (C) 2006  George Dunlop
| Website: http://mini-app.peccavi.com
|-------------------------------------------------------------------------
| Based on http://www.codeigniter.com/forums/viewthread/168/P15/
| By Craig Rodway, craig dot rodway at gmail dot com
|-------------------------------------------------------------------------
| 
| This library is free software; you can redistribute it and/or
| modify it under the terms of the GNU Lesser General Public
| License as published by the Free Software Foundation; either
| version 2.1 of the License, or (at your option) any later version.
| 
| This library is distributed in the hope that it will be useful,
| but WITHOUT ANY WARRANTY; without even the implied warranty of
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
| Lesser General Public License for more details.
| 
| You should have received a copy of the GNU Lesser General Public
| License along with this library; if not, write to the Free Software
| Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
| 
| Last changed:
| 11 Oct '06 George Dunlop, peccavio at peccavi dot com
|
*/

class Userauth {

	var $obj;

	function Userauth()
	{
		$this->obj =& get_instance();
		$this->obj->load->model('user_group_model', '', TRUE);
		$this->obj->load->model('remember_me', '', TRUE);
		$this->obj->load->library('ua_authorize');
		log_message('debug', 
			'User Authentication Class Initialised via '.get_class($this->obj));
	}

	// A function to validate db schema is current version
	// - used by action/login to determine if to run controllers/install.php
	function valid_db() 
	{
		return $this->obj->user_group_model->valid_db() 
			&& $this->obj->remember_me->valid_db();
	}
	
	/*
	* Deprecated functions, soon to disappear
	*/
	// Helper function to get this user's group memembership
	function groups_this_user() 
	{
		return $this->obj->user_group_model->groups_this_user();
	}

	function set_allow($allow)
	{
		$this->obj->authorize->set_allow($allow);
	}

	function set_deny($deny)
	{
		$this->obj->authorize->set_deny($deny);
	}

	/*
	* End of deprecated funtions
	*/

	/**
	* Test if user is logged in
	* Check it good in case session not validated
	*/
	function loggedin()
	{
		$session_username = $this->obj->session->userdata('username');
		$session_bool = $this->obj->session->userdata('loggedin');
		if((isset($session_username) && $session_username != '') 
				&& (isset($session_bool) && $session_bool == TRUE)){
			log_message('debug','Userauth:loggedin = '.$this->obj->session->userdata('username'));
			return TRUE;
		} else { 
			log_message('debug','Userauth:loggedin = false');
			return FALSE; 
		}
	}

	/**
	* Logout user and reset session data
	*/
	function logout()
	{
		log_message('debug','Userauth: Logout: '.$this->obj->session->userdata('username'));

		//remember_me used to figure if to expire inactive login
		$sessdata = array('username'=>'', 'loggedin' => FALSE );
		$this->obj->session->set_userdata($sessdata);
		$this->obj->remember_me->removeRememberMe();
	}

	/**
	* Try and validate a login and optionally set session data
	*
	* @param string $username Username to login
	* @param string $password Password to match user
	* @param bool $session (true) Set session data here. False to set your own
	*/
	function trylogin($username, $password, $session = true)
	{
		// make sure session will be seen as active at check()
		$this->obj->session->set_userdata('last_activity', time());

		if($username != '' && $password != ''){

			// Only continue if user and pass are supplied
			// SHA1 the password if it isn't already
			if(strlen($password) != 40){ $password = sha1($password); }
			if ( $this->obj->user_group_model->testLogin($username, $password) == TRUE ) {
				$this->obj->user_group_model->dateStampLogin($username);

				// Set session data array
				$sessdata = array( 'username' => $username, 'loggedin' => TRUE );
				// function param sets the session = true
				if($session == true){
					log_message('debug', "Userauth: trylogin: setting session data");
					log_message('debug', "Userauth: trylogin: "
										."Session: ".var_export($sessdata, TRUE));
					// Set the session
					$this->obj->session->set_userdata($sessdata);
					return TRUE;
				} else {
					// param to set the session = false: 
					// return the data only - without setting session
					return $sessdata;
				}
			} else { 
				log_message('debug', "Userauth: trylogin: no match in db for user / password");
				return FALSE; 
			}
		} else { 
			log_message('debug', "Userauth: trylogin: missing username or password");
			return FALSE; 
		}
	}

	/**
	* Check: Validates session, Authenicates user and 
	* checks if the user is allowed to view the page or not.
	*
	* @param string $message Message displayed if denied access
	* @param bool $ret TRUE:return bool. FALSE:die on false (denied)
	* @return bool TRUE if allowed. FALSE/die() if denied
	*/
	function check($message = NULL, $ret = FALSE)
	{
		log_message('debug', "Check function URI: ".$this->obj->uri->uri_string());
		
		// Initialize a cold start validation of session data
		if ( ! $this->obj->session->userdata('last_activity') ) {
			$sessdata = array('username'     =>'', 
							  'loggedin'     => FALSE,
							  );
			$this->obj->session->set_userdata($sessdata);
			log_message('debug', "Userauth: Check: Cold Start of session's userdata()");
		}

		// Expire an inactive login
		if ( $this->obj->session->userdata('loggedin') ) { 
			$expire_time = time() - $this->obj->config->item('login_expiration');
			if ( $this->obj->session->userdata('last_activity') <=  $expire_time) {
				log_message('debug', "Userauth: check: inactive login, expired");
				$this->logout();
			}
		}
		$this->obj->session->set_userdata('last_activity', time());

		// make sure we have a language

		if ($this->obj->config->item('ua_multi_language')) {
			$language = $this->obj->session->userdata('ua_language');
			if (empty($language)) {
				$language = $this->obj->lang_detect->language();
			}
		} else { $language = $this->obj->config->item('language'); }
		$this->obj->session->set_userdata('ua_language',$language);

		// check if "Remember Me" option is enabled and needed
		if ( ! $this->obj->session->userdata('loggedin') ) {
			$username = $this->obj->remember_me->checkRememberMe();
			if ( $username != FALSE ) { 
				$this->obj->user_group_model->dateStampLogin($username);
				log_message('debug', "Userauth: check: Remember Me, login");
				$sessdata = array('username' => $username, 'loggedin' => TRUE);
				$this->obj->session->set_userdata($sessdata);
				$this->obj->remember_me->addRememberMe($username);
			} 
		}
		// check permissions
		$username = $this->obj->session->userdata('username');
		$allow = $this->obj->authorize->permitted( $username );

		if($allow == FALSE){	// Access denied!
			log_message('info','Userauth: Access Denied for '.$username
									.' in: '.get_class($this->obj).'.');
			// Figure out the return
			if($ret == TRUE) { return FALSE; }
			else {									// Show a CI error msg
				show_error(($message) ? $message : 
					'You are not permitted to view this page.');
			}
		} else {
			log_message('info','Userauth: check: Access permitted for '
							.$username.' in: '.get_class($this->obj).'.');
			return TRUE;	// User is allowed, just carry on
		}
	}
}

?>