<?php
########################################################################################################
####  Name:             	go_main_cloud_page.php	                     	                    ####
####  Type:             	ci views - administrator/agent                                      ####
####  Version:          	3.0                                                            	    ####
####  Build:            	1366344000                                                          ####
####  Copyright:        	GOAutoDial Inc. (c) 2011-2013 - GoAutoDial Open Source Community    ####
####			        <community@goautodial.com>            			   	    ####
####  Written by:	        Rodolfo Januarius T. Manipol                                        ####
####  License:          	AGPLv2                                                              ####
########################################################################################################
?>

<!--<div id="contents"></div>
<div id="helpbutton">		<a  href="http://goautodial.com/support-ticket/" title="Get Support!" target="_blank"></a></div>
<div id="welcomebutton">	<a  href="http://goautodial.com"  title="Welcome!" target="_blank"></a></div>
<div id="getstartedbutton">	<a  href="http://goautodial.com/wiki/getting-started-guide/" title="Getting Started Guide!" target="_blank"></a></div>
<div id="welcometitletext">
 <p><a  href="http://goautodial.com"  title="<? #echo $this->lang->line("ua_welcome"); ?>" target="_blank"><? #echo $this->lang->line("ua_welcome"); ?></a></p>
</div>
<div id="getstartedtitletext">
 <p><a  href="http://goautodial.com/wiki/getting-started-guide/" title="<? #echo $this->lang->line("ua_getstarted"); ?>" target="_blank"><? #echo $this->lang->line("ua_getstarted"); ?></a></p>
</div>
<div id="helptitletext">
 <p><a  href="http://goautodial.com/support-ticket/" title="<? #echo $this->lang->line("ua_needhelp"); ?>" target="_blank"><? #echo $this->lang->line("ua_needhelp"); ?></a></p>
</div>
<div id="welcomebox"></div>
<div id="getstartedbox"></div>
<div id="helpbox"></div>
<div id="welcomebodytext">
 <p><span  style="color:#404040;"><? #echo $this->lang->line("ua_welcomephrase"); ?></span><a href="http://goautodial.com" title="GoAutoDial Inc.- Empowering The Next Generation Contact Center" target="_blank"> http://goautodial.com</a>.</p>
</div>
<div id="getstartedbodytext">
 <p><? #echo $this->lang->line("ua_getstartedphrase"); ?></p>
</div>
<div id="helpbodytext">
 <p><? #echo $this->lang->line("ua_needhelpphrase"); ?></p>
</div>
<div id="bodytext">
 <p><? #echo $this->lang->line("ua_licence"); ?></p>
</div>-->


	<div class="min-width">
        <div id="main">
            <div id="header">

                <div class="head-row1">
                    <div class="col1">
                            <a href="#" title="Home"><img src="img/logo_transparent_small.png" alt="Home" class="logo" /></a>
                            <h1 class="site-name"><a href="#" title="Home">GoAutoDial - Hosted Dialer</a></h1>
                    </div>
                    <div class="col2">
                                                    
            <!-- YUI Menu div-->
            <div id="productsandservices" class="yuimenubar yuimenubarnav">

              <div class="bd">
                <ul  style="text-decoration:none" class="first-of-type">
                      <li  class="yuimenubaritem"><a href="#" class="yuimenubaritemlabel active">Home</a></li>
                      <li  class="yuimenubaritem"><a href="/agent" class="yuimenubaritemlabel" >Agent</a></li>
                      <li  class="yuimenubaritem"><a href="#?w=280" class="yuimenubaritemlabel" rel="popup1">Supervisor</a></li>
                      <li  class="yuimenubaritem"><a href="/portal" class="yuimenubaritemlabel">Sign-up</a></li>
                      <li  class="yuimenubaritem"><a href="#" class="yuimenubaritemlabel">About Us</a></li>
               </ul>
          </div>
       </div>                                             </div>
                    <div class="col3">
                    	<a href="#"><img src="img/images/bg-user.gif" alt="" title="" width="33" height="33" /></a>

                    </div>
                </div>
                                    <div class="head-row2" id="custom">
                    	<div class="col1">
                        	<div class="images" id="imgdisplay">
<!--                                 <div> -->
                                    <img src="img/images/goslide1.png" alt="" title="" width="513px" height="553" class="alignleft" />                                    
<!--                                     <img src="img/images/slogan.gif" alt="" title="" width="253" height="117" class="slider-slogan" /> -->
<!--                                     <a href="#" class="start"></a> -->

<!--                                 </div> -->
<!--                                 <div> -->
                                    <img src="img/images/goslide2.png" alt="" title="" width="513px" height="553" class="alignleft" />
<!--                                     <img src="img/images/slogan2.gif" alt="" title="" width="253" height="117" class="slider-slogan" /> -->
<!--                                     <a href="#" class="start"></a> -->
<!--                                 </div> -->
<!--                                 <div> -->
                                    <img src="img/images/goslide3.png" alt="" title="" width="513px" height="553" class="alignleft" />  
<!--                                     <img src="img/images/slogan3.gif" alt="" title="" width="253" height="117" class="slider-slogan" /> -->
<!--                                     <a href="#" class="start"></a> -->

<!--                                 </div> -->
<!--                                 <div> -->
                                    <img src="img/images/goslide4.png" alt="" title="" width="513px" height="553" class="alignleft" />   
<!--                                     <img src="img/images/slogan4.gif" alt="" title="" width="253" height="117" class="slider-slogan" /> -->
<!--                                     <a href="#" class="start"></a> -->
<!--                                 </div> -->
<!--                                 <div> -->
                                    <img src="img/images/goslide5.png" alt="" title="" width="513px" height="553" class="alignleft" />   
<!--                                     <img src="img/images/slogan5.gif" alt="" title="" width="253" height="117" class="slider-slogan" /> -->
<!--                                     <a href="#" class="start"></a> -->
<!--                                     </div> -->
                            </div>
                            <div class="arrows">
                                <a class="backward" id="prev" href="#"></a>
                                <a class="forward" id="next"></a>
                            </div>
                            <div class="tabs" style="display:none;">
                                <a href="#"></a>
                                <a href="#"></a>

                                <a href="#"></a>
                                <a href="#"></a>
                                <a href="#"></a>
                            </div>
                        </div>
                        <div class="col2">
                            <a href="#"><img src="img/images/topbanner.png" alt="" title="" width="317" height="174" /></a><br /><br style="font-size:1px"/>
                            <a href="#"><img src="img/images/midbanner.png" alt="" title="" width="317" height="173" /></a><br /><br style="font-size:1px"/>
                            <a href="#"><img src="img/images/bottombanner.png" alt="" title="" width="317" height="173" /></a>

                        </div>
                    </div>
                            </div>
            <div id="cont">
                <div class="cont-inner">
                	
                                            <div id="right-col">
                            <div class="ind">
                                <div class="width">
                                    <div class="block block-block" id="block-block-2">

	<div class="title">
    	<h3>News</h3>
    </div>
    <div class="content">
		<div class="content">
<ul>
<li>
			<strong><span>09-20-2011</span></strong></li>
<li>

			We launched our hosted service today, offering&nbsp;Predictive dialer at a fixed cost pr. minute or pr. month.</li>
<li>
			<a href="#">Continue Reading</a></li>
<li>
			&nbsp;</li>
<li>
			<strong><span>09-23-2011</span></strong></li>
<li>
			We have added option for IVR menu in our hosted offering, the service can be configured through your browser.</li>

<li>
			<a href="#">Continue Reading</a></li>
</ul>
</div>
<p>&nbsp;</p>
    </div>
</div>                                </div>
                            </div>
                        </div>
                                        
                                            <div id="left-col">

                            <div class="ind">
                                <div class="width">
                                    <div class="block block-block" id="block-block-3">
	<div class="title">
    	<h3>Services</h3>
    </div>
    <div class="content">
		<div id="left-col">

<div class="ind">
<div class="width">
<div class="block block-block" id="block-block-15">
<div class="content">
<ul>
<li>
							<a href="#">Hosted Dialer</a></li>
<li>
							<a href="#">Hosted PBX</a></li>
<li>
							<a href="#">Hosted IVR</a></li>

<li>
							<a href="#">Script Management</a></li>
<li>
							<a href="#">Report Analysis</a></li>
<li>
							<a href="#">International DDI nr&#39;s</a></li>
<li>
							<a href="#">Premium Support</a></li>

</ul>
</div>
</div>
</div>
</div>
</div>
<div id="cont-col">
<div class="ind">
		&nbsp;</div>
</div>
    </div>
</div>                                </div>
                            </div>

                        </div>
                                        
                    <div id="cont-col">
                        <div class="ind">
                                    
							                                                                                                                                 
                                                        
                                                        
                                                            <div id="custom">
                                    <div class="ind">
										<div class="block block-block" id="block-block-4">
	<div class="title">
    	<h3>Welcome</h3>

    </div>
    <div class="content">
		<p><strong>GoAutoDial</strong> is an enterprise grade predictive dialer system. It contains all the components of a fully functional predictive dialer system.<br />
	&nbsp;<br />
	<strong>GoAutoDial</strong> is totally web based. Everything is done within your web browser, from database administration, dialer and PBX configuration - all in an&nbsp;intuitive&nbsp;web GUI.<br />

	&nbsp;<br />
	Your&nbsp;natural choice&nbsp;for a <strong>&quot;Turnkey&quot;</strong> predictive-dialer,<em><strong> </strong></em>either<em> on-premises </em>or as a <em>hosted service</em> in our data-centre.</p>

<p>&nbsp;</p>
    </div>
</div>                                    </div>
                                </div>
                                                        
                            <!-- start main content -->
                            
                        </div>

                    </div>
                </div>
                                                
            </div>
             
        </div>
                    <div id="bot-custom">
                <div class="ind">
                    <div class="block block-block" id="block-block-5">
	<div class="title">
    	<h3>Follow</h3>

    </div><br/>
