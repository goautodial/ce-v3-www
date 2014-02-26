<?php
########################################################################################################
####  Name:             	common_download.php                                                 ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  Modified by:      	Franco E. Hora                                                      ####
####                    	Jericho James Milo                                                  ####
####                            Chris Lomuntad                                                      ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

     $seg4 = $this->uri->segment(4);
     header("Cache-Control: public");
     if(empty($seg4)){
         header("Content-Type: application/mp3");
     } else {
         header("Content-Type: application/octet-stream");
     }
     header("Content-disposition: attachment; filename=\"$url\"");
     header("Content-Type: application/force-download; filename=\"$url\"");
     header("Content-Transfer-Encoding:binary");

     if(!empty($seg4)){
         readfile($url);
     } else {
         $csv=preg_replace('/ +\"/', '"', $csv);
         $csv=preg_replace('/\" +/', '"', $csv);
         echo $csv;
         exit;
     }

?>
