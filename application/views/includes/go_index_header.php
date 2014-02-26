<?php
########################################################################################################
####  Name:             	go_index_header.php     	    	                            ####
####  Type:             	ci views - administrator                                            ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Originated by:	        Rodolfo Januarius T. Manipol                                        ####
####  Written by:	        Jerico James Milo	                                            ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
?>
<!--  Copyright:      GOAutoDial Inc. - Jerico James Milo <james@goautodial.com>  -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<head>

<title>GoAdmin &reg; 3.0</title>
<link rel="shortcut icon" href="<?php echo $base; ?>img/gologoico.ico" />
		<meta name="google-site-verification" content="hN64gcgof2IPEGgqv7aQoE_68CbVe3JN18Y1QDCt7bo" />
		<meta http-equiv="Content-type" content="application/xhtml+xml; charset=utf-8; charset=utf-8" />
		<meta name="author" content="JericoJamesMilo" />
		<meta name="copyright" content="<?php echo date('Y'); ?> GoAutoDial Inc." />
		<meta name="GoAutoDial" content="GoAutoDial Inc. http://www.goautodial.com" />
		<meta name="description" content="GoAutoDial" />
		<meta name="keywords" content="dialer, predictive dialer" />
		<meta name="robots" content="index,follow,noodp,noydir" />

		<script type="text/javascript">

			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-22226361-5']);
			_gaq.push(['_trackPageview']);
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();
				
		</script>
  
  <script type="text/javascript" src="<?=base_url()?>jsfirstpage/jquery.main.js"></script>
  <link href="<?=base_url()?>cssfirstpage/go_hosted_style.css" media="all" rel="stylesheet" type="text/css" />
  

		<style type="text/css">
		/* default close button positioned on upper right corner */
		#overlay2 {
			color:#efefef;
			height:450px;
			
		}
	

		#overlay {
			color:#efefef;
			height:450px;
			
		}
	
		div.contentWrap {
			height:1200px;
			overflow-y:auto;
		}
		
		div.contentWrap2 {
			height:390px;
			overflow-y:auto;
		}
		
		div.contentWrap3 {
			height:590px;
			overflow-y:auto;
		}
		
		</style>
	<script src="<?=base_url()?>jsfirstpage/jsslide/jquery-1.3.2.min.js" type="text/javascript"></script>
 	<script src="<?=base_url()?>jsfirstpage/callout/jquery.callout.js" type="text/javascript"></script>
 	<script src="<?=base_url()?>jsfirstpage/callout/jquery.callout-min.js" type="text/javascript"></script>
 	<script src="<?=base_url()?>jsfirstpage/jquery-overlay-tool.js" type="text/javascript" ></script>
 	<link href="<?=base_url()?>cssfirstpage/overlay-apple.css" media="all" rel="stylesheet" type="text/css" />

	<!--      START FADE IN FADE OUT     -->
	<script type="text/javascript" >

	function preload(arrayOfImages) {
    	$(arrayOfImages).each(function(){
        	$('<img/>')[0].src = this;
        
    	});
	}

   
	preload([
    'imgfirstpage/images/topbannerwithslogan.png',
    'imgfirstpage/images/topbannerwithslogan-hover.png',
    'imgfirstpage/images/midbannerwithslogan.png',
    'imgfirstpage/images/midbannerwithslogan-hover.png',
    'imgfirstpage/images/bottombannerwithslogan.png',
    'imgfirstpage/images/bottombannerwithslogan-hover.png',
    'imgfirstpage/images/white.png',
    'imgfirstpage/images/close.png',
    'imgfirstpage/images/white3.png'
	]);


		
});


</script>

</head>

<body id="body">

<!-- start live chat -->
<!--<script type="text/javascript" src="//asset0.zendesk.com/external/zenbox/v2.4/zenbox.js"></script>-->

<!--<style type="text/css" media="screen, projection">-->
<!--  @import url(//asset0.zendesk.com/external/zenbox/v2.4/zenbox.css);-->
<!--</style>-->

<!--<script type="text/javascript">-->
<!--document.write(unescape("%3Cscript src='" + ((document.location.protocol=="https:")?"https://snapabug.appspot.com":"http://www.snapengage.com") + -->
<!--           "/snapabug.js' type='text/javascript'%3E%3C/script%3E"));-->
<!--</script>-->

<!--<script type="text/javascript">-->
<!--SnapABug.addButton("d04093d4-3146-4fa7-811d-9475a3b6e94c","0","40%");-->
<!--</script>-->
<!-- end update script for live chat-->

<div id="go_menus" class="img-grd-nologo" style="position:fixed;top: 0px;width:100%;padding:3px 0px 1px 0px;z-index:999999;">
   <div style="float:right;padding-right:25%;text-align:center;white-space: nowrap; border: 0px red solid;" id="menuitems">
	<table>
		<tr>
			<td>
				<span class="go_menu_top" title="Admin Login"><a href="<?=base_url()?>login/" class="go_menu_list">Admin Login</a></span>
			</td>
			<td>
				<span class="go_menu_top" title="Agent Login">|<a href="<?=base_url()?>agent/agent.php?relogin=YES" class="go_menu_list">Agent Login</a></span>
			</td>
		</tr>
	</table>
    </div>
</div>
