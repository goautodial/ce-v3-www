<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* MY_Session Class
*
* Extends the core CI_Session giving it the ability to create 
* session cookies that expire when the browser closes
*/
class MY_Session extends CI_Session {
    
    // ------------------------------------------------------------------------

    /**
     * Cookie Monster eats up the session cookie just before browser closes if he's awake!
     *
     * Okay, fine! It works by creating a cookie that tells CI_Session
     * to create session cookies that expire when the browser closes
     *
     * @access    public
     * @return    void
     */
    function cookie_monster($asleep)
    {
        $asleep ?
            setcookie($this->sess_cookie_name.'_cm', 'true', 0, $this->cookie_path, $this->cookie_domain, 0) :
                setcookie($this->sess_cookie_name.'_cm', 'false', 0, $this->cookie_path, $this->cookie_domain, 0);
        $this->sess_time_to_update = -1;
        $this->sess_update();
    }
    
    // ------------------------------------------------------------------------

    /**
     * Write the session cookie
     *
     * @access    public
     * @return    void
     */
    function _set_cookie($cookie_data = NULL)
    {
        if (is_null($cookie_data))
        {
            $cookie_data = $this->userdata;
        }

        // Serialize the userdata for the cookie
        $cookie_data = $this->_serialize($cookie_data);

        if ($this->sess_encrypt_cookie == TRUE)
        {
            $cookie_data = $this->CI->encrypt->encode($cookie_data);
        }
        else
        {
            // if encryption is not used, we provide an md5 hash to prevent userside tampering
            $cookie_data = $cookie_data.md5($cookie_data.$this->encryption_key);
        }
        
        // Set the cookie
        setcookie(
                    $this->sess_cookie_name,
                    $cookie_data,
                    // if cookie monster exist and is awake, generate a session cookie that expires on browser close
                    isset($_COOKIE[$this->sess_cookie_name.'_cm']) && $_COOKIE[$this->sess_cookie_name.'_cm'] == 'true' ? 0 : $this->sess_expiration + time(),
                    $this->cookie_path,
                    $this->cookie_domain,
                    0
                );
    }
    
}  