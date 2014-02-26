<?php
########################################################################################################
####  Name:             	go_sticky_warning.php                     	                    ####
####  Type:             	ci views - administrator/agent                                      ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
?>

<script type="text/javascript">


function create( template, vars, opts ){
	return $container.notify("create", template, vars, opts);
}

$(document).ready(function()
	{
            
        $("#container").effect("shake", { times:3 }, 200);
     
            
       if ( !$('#container').create ) {


                create("themeroller", { title:'Warning!', text:'Your server CPU utilization has reached more than 90% of your CPU resources!' });

		

     }

			

	
	// second
	var container = $("#container-bottom").notify({ stack:'above' });
	container.notify("create", { 
		title:'Look ma, two containers!', 
		text:'This container is positioned on the bottom of the screen.  Notifications will stack on top of each other with the <code>position</code> attribute set to <code>above</code>.' 
	},{ expires:false });
	
	container.notify("widget").find("input").bind("click", function(){
		container.notify("create", 1, { title:'Another Notification!', text:'The quick brown fox jumped over the lazy dog.' });
	});
});</script>