<?php
########################################################################################################
####  Name:             	go_systemsettings.php                                               ####
####  Type:             	ci model - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Franco E. Hora                                                      ####
####  Modified by:      	GoAutoDial Development Team                                         ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

class Go_systemsettings extends Model{

    private $result = null;

    function __construct(){
        parent::Model();
        $this->asteriskDB = $this->load->database('dialerdb',TRUE);
        $this->goDB = $this->load->database('goautodialdb',TRUE);
    }

    function getsettings(){
        $this->result = $this->asteriskDB->get('system_settings');
    }

    function getserversettings(){
        $this->result = $this->goDB->get('go_server_settings');
    }

    function getservers(){
        $this->result = $this->asteriskDB->get('servers');
    }

    function getlanguages(){
        $this->result = $this->goDB->get('go_language');
    }

    function query_result($result=null){
        return $this->result;
    }

}

?>
