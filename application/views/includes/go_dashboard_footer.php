<?php
########################################################################################################
####  Name:             	go_dashboard_footer.php         	                            ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
$base = base_url();
$version = file_get_contents($base.'version.txt');
//echo $version;
?>

</br>
</br>
<!--

<div style="margin-left: 60px; position: fixed; bottom: 0px; background-color:  #fff; opacity: 1;">
<p style="color: #6a6363; font-size: 10px;" >
<b><a href='<? #echo $base; ?>index.php/go_site/credits'>GoAutoDial CE &reg; 3.0</a> Build.1366344000 | <a href='http://goautodial.com'>AGPLv2</a><br>
&copy; <a href='http://goautodial.com'>GoAutoDial Inc.</a> 2010-2014 All Rights Reserved.</p>
</div>
-->

<style>
img.grayscale,img.grayedout {
    filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale"); /* Firefox 10+ */
    filter: gray; /* IE6-9 */
    -webkit-filter: grayscale(100%); /* Chrome 19+ & Safari 6+ */
    -webkit-transition: all .6s ease; /* Fade to color for Chrome and Safari */
    -webkit-backface-visibility: hidden; /* Fix for transition flickering */
}

img.grayscale:hover {
    filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'1 0 0 0 0, 0 1 0 0 0, 0 0 1 0 0, 0 0 0 1 0\'/></filter></svg>#grayscale");
    -webkit-filter: grayscale(0%);
}
</style>

<!-- STICKIES -->
<div id="container" style="display:none;z-index:999;top:35px;">
	<div id="sticky">
		<a class="ui-notify-close ui-notify-cross" href="#">x</a>
		<h1>#{title}</h1>
		<p>#{text}</p>
	</div>

	<div id="themeroller">
		<a class="ui-notify-close ui-notify-cross" href="#">x</a>
		<h1>#{title}</h1>
		<p>#{text}</p>
	</div>
</div>
<!-- STICKIES -->



<script>
    

        
    $(window).scroll(function() {
    if ($(this).scrollTop()) {
        $('#footer').fadeIn();
    } else {
        $('#footer').fadeOut();
    }
});
    
  //  $(document).ready(function () {


    //if (window.innerHeight > 540) {
      //  $("#footer").hide();
    //}
    //else {

      //  $("#footer").show();
   // }

//});

 
    //    if ($(this).scrollDown()) {
      //  $('#footer').stop(true, true).fadeIn();
    //} else {
      //  $('#footer:hidden').stop(true, true).fadeOut();
   // }    
    



//$(window).scroll(function() {
//    if ($(this).scrollTop()) {
//        $('#footer:hidden').stop(true, true).fadeIn(5000);
//    } else {
//        $('#footer').stop(true, true).fadeOut(5000);
//    } 
//});



</script>

<div id="footer_logo" style="position:fixed;bottom:0px;right:5px;z-index:99999;text-align:right;">
<a href="http://www.facebook.com/goautodial" target="_blank"><img src="<?=$base?>img/images/facebook.png" class="grayscale" title="Facebook" style="border:0px;height:20px;" /></a> <a href="https://twitter.com/#!/goautodial" target="_blank"><img src="<?=$base?>img/images/twitter.png" class="grayscale" title="Twitter" style="border:0px;height:20px;" /></a> <a href="http://goautodial.com" target="_blank"><img src="<?=$base?>img/images/goautologo.png" class="grayscale" title="GoAutoDial" style="border:0px;height:20px;" /></a>&nbsp;
</div>

<!-- <div id="footer_logo" style="position:fixed;bottom:5px;right:5px;z-index:99999;text-align:right;">
<a href="http://www.facebook.com/goautodial" target="_blank"><img src="<?=$base?>img/images/facebook.png" class="grayscale" title="Facebook" style="border:0px;height:28px;" /></a> <a href="https://twitter.com/#!/goautodial" target="_blank"><img src="<?=$base?>img/images/twitter.png" class="grayscale" title="Twitter" style="border:0px;height:28px;" /></a> <a href="http://goautodial.com" target="_blank"><img src="<?=$base?>img/images/goautologo.png" class="grayscale" title="GoAutoDial" style="border:0px;height:28px;" /></a>&nbsp;
</div>-->

<div id="footer">
<!--
<p id="footer-left" class="alignleft"><span id="footer-thankyou">Thank you for supporting <a href="http://GoAutoDial.org/">GoAutoDial</a>!</span> | <a href="http://codex.GoAutoDial.org/">Documentation</a> | <a href="http://GoAutoDial.org/support/forum/4">Feedback</a></p>
<p id="footer-upgrade" class="alignright"><strong><a href="http://demo002.gopredictive.com/update-core.php">Get Version 3.1.3</a></strong></p>-->

<table width="100%" > 
	
			<tr><td align="left" style="color: #6a6363; font-size: 10px;"><b><a href='<? echo $base; ?>credits'>GoAdmin &reg; <?echo $version;?></a></b> | <a href='<? echo $base; ?>agplv2'>AGPLv2</a> | &copy; <a href='http://goautodial.com' target="_blank">GoAutoDial</a> 2010-<?=date("Y") ?> All Rights Reserved.</td></tr>
            		<!-- <tr><td align="left" style="color: #6a6363; font-size: 10px;">&copy; <a href='http://goautodial.com'>GoAutoDial Inc.</a> 2010-2014 All Rights Reserved.</td></tr>-->
	
	
	
    </table>
</div>
</body>
</html>
