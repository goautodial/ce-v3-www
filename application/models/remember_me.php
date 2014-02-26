<?php
########################################################################################################
####  Name:             	remember_me.php                      	                            ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  Modified by:      	GoAutoDial Development Team                                         ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Remember_me extends Model {

	function Remember_me()
	{
		parent::Model();
		$this->obj =& get_instance();
		$this->obj->load->helper( array('cookie', 'date', 'security', 'string') );
	}

	// Test if latest schema
	function valid_db() 
	{
		return $this->db->table_exists('go_remember');
	}

	function addRememberMe ($username)
	{
		// start by removing any current cookie before the re-issue
		$this->removeRememberMe();
		$random_string = random_string('alnum', 128);
		$remember_me_info = array(
			   'username' => $username,
			   'usernamehash' => dohash($username),
			   'random_string' => $random_string,
			   'origin_time' => now(),
			   'ip_address' => $this->input->ip_address()
			);
			$this->db->insert('go_remember', $remember_me_info);
			set_cookie("ci_userhash", dohash($username), $this->config->item('remember_me_life'));
			set_cookie("ci_randomstring", $random_string, $this->config->item('remember_me_life'));
	}

	function removeRememberMe ()
	{
		$this->db->where('usernamehash', $this->input->cookie('ci_userhash', TRUE));
		$this->db->where('random_string', $this->input->cookie('ci_randomstring', TRUE));
		$this->db->where('ip_address', $this->input->ip_address());

		/*
		 * it is possible, although incredibly unprobable that the same user will have persistent
		 * cookies on more then 1 machine with the same randomly generated hash, so this simply
		 * ensures that only 1 is taken out.  If this ever happens, buy a lottery ticket ;)
		 */
		$this->db->limit(1); 

		$this->db->delete('go_remember'); 
		set_cookie("ci_userhash");
		set_cookie("ci_randomstring");
	}

	function checkRememberMe ()
	{
		$userhash = $this->input->cookie('ci_userhash', TRUE);
		$random_string = $this->input->cookie('ci_randomstring', TRUE);
		$ipaddress = $this->input->ip_address();
		if (isset($userhash) && isset($random_string)) {
			log_message('debug','Has Remember Me Cookie');

			// test if mini-app db schema installed
			if ( ! $this->valid_db() ) { return FALSE; }

			$this->db->where ('usernamehash', $userhash);
			$this->db->where ('random_string', $random_string);
			$this->db->where ('ip_address', $ipaddress);
			$result = $this->db->get('go_remember');

			if ($result != FALSE && $result->num_rows() > 0) {
				$result = $result->row();
				return $result->username;
			} else { return FALSE; }

		} else { return FALSE; }
	}

	function removeOldRememberMe ()
	{
		// $this->db->use_table('ci_remember_me_login');
		// not done yet
	}
}

?>