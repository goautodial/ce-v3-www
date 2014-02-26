<?php
########################################################################################################
####  Name:             	go_dashboard_search.php                     	    		    ####
####  Type:             	ci views - administrator					    ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################

if ($go_user_datacount>0) {
echo '<ul>User Information</ul>';

foreach($go_user_dataval as $item):
            
            echo '<li onClick="fill(\''.$item->user.'\');" class="toolTip" title="'.$item->full_name.'">'.$item->user.'</li>';
            
endforeach;
           }
           
           
           
           
if ($go_liname_datacount>0) {
echo '<ul>Lead Information</ul>';

foreach($go_liname_dataval as $item):
            
            echo '<li onClick="fill(\''.$item->fullname.'\');">'.$item->fullname.'</li>';
            

endforeach;
           }           
           
           

if ($go_liphone_datacount>0) {
echo '<ul>Phone Number</ul>';

foreach($go_liphone_dataval as $item):
            
            echo '<li onClick="fill(\''.$item->phone.'\');">'.$item->phone.'</li>';
            
endforeach;
           }


           

?>	
