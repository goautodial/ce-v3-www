<?php

$localtimezone = date_default_timezone_get();

#echo "$localtimezone";


$time = new DateTime('now', new DateTimeZone($localtimezone));
$timeoffset = $time->format('P');
	//echo "$timeoffset";

//$timezone = new DateTimeZone("$localtimezone");
//$timeoffset   = $timezone->getOffset(new DateTime);
//	
//	echo "$timeoffset";





?>
