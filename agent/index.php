<?php
$msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
$firefox = strpos($_SERVER["HTTP_USER_AGENT"], 'Firefox') ? true : false;
$safari = strpos($_SERVER["HTTP_USER_AGENT"], 'Safari') ? true : false;
$chrome = strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? true : false;

$myurl = "https://".$_SERVER['SERVER_NAME']."/login/ieview.php";

if(!$firefox && !$chrome) {
	header("Location: $myurl") ;
} 
?>


<p><title>Goautodial Hosted Predictive Dialer</title></p>
<p><span style="font-size: 16px;"></span></p>
<p><meta content="1; url=agent.php?relogin=YES" http-equiv="REFRESH" /></p>



