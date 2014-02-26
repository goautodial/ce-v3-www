<?php
########################################################################################################
####  Name:             	go_rssviewer.php 	                    	                    ####
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
		if ($i==3) break;
				echo "<li class='rss_content_li'>";
				echo "<h4><a href='" .$item->get_link() . "' target='_blank'>";
				echo $item->get_title();
				echo "</a></h4>";


				if ($author = $item->get_author())
				{
					echo "by " . $author->get_name();
				}


                                #echo "" . $item->get_name();

				$contentArray = explode("\n",$item->get_content());
				$limit = 10;
				$more_info = "<a href='" .$item->get_link() . "' target='_blank' style='font-weight:bold;float:right;'>read more...</a><br />";
				if ($limit > count($contentArray))
				{
					$limit = count($contentArray);
				}
				
				for ($n=0;$n<$limit;$n++)
				{
					echo "" . $contentArray[$n] . "\n";
				}
				echo "<span style='display:block'>$more_info</span>";
				#echo "" . $item->get_name();
	echo "</li>";
$i++;
endforeach;
?>	
</ul>
<script>
$(function()
{
	$('.rss_content_li > p > img').each(function()
	{
		$(this).css({'height':'150px','cursor':'pointer'});
		$(this).attr('title','Click image to view');
		$(this).parent().css('display','inline');
		$(this).click(function()
		{
			window.open($(this).attr('src'));
		});
		//console.log($(this).parent());
	});
});
</script>