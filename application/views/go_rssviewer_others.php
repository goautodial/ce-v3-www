<?php
########################################################################################################
####  Name:             	go_rssviewer_others.php 	                    	            ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
?>

<ul>
<?
$i=0;
foreach($res_feed as $item):
		if ($i==2) break;
				echo "<li>";
				echo "<h4><a href='" .$item->get_link() . "' target='_blank'>";
				echo $item->get_title();
				echo "</a></h4>";
				
				$contents = $item->get_content();
				$hasBR = "";
				if (preg_match('<br />',$item->get_content())) {
					$contents = str_replace('<br />',"\n",$contents);
					$hasBR = "<br />";
				}
				$contentArray = explode("\n",$contents);
				$limit = 10;
				$more_info = "<a href='" .$item->get_link() . "' target='_blank' style='font-weight:bold;float:right;'>read more...</a><br />";
				if ($limit > count($contentArray))
				{
					$limit = count($contentArray);
				}
				
				for ($n=0;$n<$limit;$n++)
				{
					echo "" . $contentArray[$n] . "$hasBR\n";
				}
				echo "$more_info";
	echo "</li>";
$i++;
endforeach;
?>	
</ul>