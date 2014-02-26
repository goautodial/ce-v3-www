<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|-------------------------------------------------------------------------
| Code Igniter Mini-App, User Authorize Class
| Functionality is User Authorization
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

class Ua_authorize {

	// ACL variables
	var $allowed_users = array();	// Allowed Groups and users
	var $denied_users = array();	// Denied Groups and users
	var $allowed_set = FALSE;		// Has set_allowed() been used
	var $denied_set = FALSE;		// Has set_denied() been used
	var $allow = FALSE;				// Cuurent status of permission

	function Ua_authorize()
	{
		$this->obj =& get_instance();
	}

	function roleCheck($role = 'guest', $uri = '')
	{
		If ( $role != 'guest' ) {

			$list = 'ua_role_'.$role.'_allow';
			$list = $this->obj->config->item($list);
			if( !empty($list) ) { $this->set_allow($list); }

			$list = 'ua_role_'.$role.'_deny';
			$list = $this->obj->config->item($list);
			if( !empty($list) ) { $this->set_deny($list); }
		}
		$this->obj->session->set_flashdata('uri', $uri); 

		log_message('debug','roleCheck: Role = '.$role);

		if(!$this->obj->userauth->check('', TRUE)) { 
			redirect('user/auth_error'); 
		}
	}

	var $is_role = array();			// test a role only once per page

	function isRole($role = 'guest')
	{
		if ( $role == 'guest' ) { return TRUE; }

		// if userauth not installed, return false
		if ( ! $this->obj->userauth->valid_db() ) { return FALSE; }

		if ( $this->obj->userauth->loggedin() ) {
			if ( ! isset( $is_role[$role] )) {

				// reset ACL
				$allowed_set = $denied_set = $allow = FALSE;
				$allowed_users = '';
				$denied_users = '';

				$list = 'ua_role_'.$role.'_allow';
				$list = $this->obj->config->item($list);
				if( !empty($list) ) { $this->set_allow($list); }

				$list = 'ua_role_'.$role.'_deny';
				$list = $this->obj->config->item($list);
				if( !empty($list) ) { $this->set_deny($list); }

				$username = $this->obj->session->userdata('username');
				$is_role[$role] = $this->permitted( $username );
			}
			log_message('debug','ua_authorize:isRole: '.$role.' = '.$is_role[$role]);
			return $is_role[$role];
		} else {
			// if not logged in - no roles
			$is_role = '';
			return FALSE;
		}
	}

	/**
	* Put users into ALLOW ACL
	*
	* Calls the function set_allowdeny - shared code for allow/deny functions.
	*
	* @param string $allow Space-separated list of usernames/groupnames
	*/
	function set_allow($allow){
		$this->set_allowdeny($allow, $this->allowed_users);
		$this->allowed_set = true;
	}
	
	function get_allowed($sep = ' '){ 
		return implode($sep, $this->allowed_users); 
	}

	/**
	* Put users into DENY ACL
	*
	* Calls the function set_allowdeny - shared code for allow/deny functions.
	*
	* @param string $deny Space-separated list of usernames/groupnames
	*/
	function set_deny($deny){
		$this->set_allowdeny($deny, $this->denied_users);
		$this->denied_set = true;
	}
	
	function get_denied($sep = ' '){ 
		return implode($sep, $this->denied_users); 
	}

	function permitted($username) {
		/* Logic:
		* User sets denied list only: allow everyone, deny denied_users[]
		* User sets allowed list only: deny everyone, allow valid_users[]
		* User sets allowed and denied lists: deny denied_users[], allow allowed_users[]
		*/

		// User has set denied list: YES
		// User has set allowed list: NO
		if($this->denied_set == true && $this->allowed_set == false){

			// Allow everyone
			$allow = true;

			// Deny people in the denied list
			if(in_array($username, $this->denied_users)){ 
				$allow = false; 
			}
		}

		// User has set denied list: NO
		// User has set allowed list: YES
		else if($this->allowed_set == true && $this->denied_set == false){

			// Deny everyone
			$allow = false;		

			// Allow people in the allowed list
			if(in_array($username, $this->allowed_users)){ $allow = true; }
		}

		// User has set denied list: YES
		// User has set allowed list: YES
		else if($this->allowed_set == true && $this->denied_set == true) {

			// If user is in the deny list, deny=true, allow=false
			if(in_array($username, $this->denied_users)){ $deny = true; }

			// if not denied, check if allowed
			if(!$deny && in_array($username, $this->allowed_users)){ $allow = true; }
		}

		// The user is valid, there isn't any allow/deny lists
		else { $allow = true; }
		return $allow;
	}

	/**
	* Put users into appropriate ACL. Is called via set_allow()/set_deny()
	*
	* @param string $str Space-separated list of usernames/groupnames
	* @param array_ptr $acl Pointer to the array to update
	*/
	function set_allowdeny($str, &$acl){
		$arr = explode(' ', $str); // Split string by spaces
		foreach($arr as $item){

			// Check to see if this item is a group or a user
			$group = $this->isGroup($item);	
			if($group != false){							// It's a group!
				// Loop this group to get it's users
				$users = $this->obj->user_group_model->usersInGroup($group);
				// Add each user in the group to the valid_users list
				foreach($users as $user){ $acl[] = $user; } 
			} else {										// It's a user
				$acl[] = $item;								// Add the user to the list 
			}
		}
	}
	
	/**
	* Check to see if the supplied acl item is a group or not
	*
	* If the item begins with an @ symbol, then the item is a group (UNIX style)
	*
	* @param string $name Item you are checking
	* @return string/bool If the item is a group, the name (
	* without the @) is returned,
	* otherwise the return value is false
	*/
	function isGroup($name){
		if($name{0} == '@'){ 
			return substr($name, 1); 
		} else { return false; }
	}
}

?>