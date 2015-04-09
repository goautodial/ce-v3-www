<?php
############################################################################################
####  Name:             go_select_language.php                                          ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Christopher Lomuntad <chris@goautodial.com>   ####
####  License:          AGPLv2                                                          ####
############################################################################################

header("Content-type: text/css");
$doc_root = $_SERVER['DOCUMENT_ROOT'];

if (file_exists("/etc/goautodial.conf")) {
	$conf_path = "/etc/goautodial.conf";
} elseif (file_exists("{$doc_root}/goautodial.conf")) {
	$conf_path = "{$doc_root}/goautodial.conf";
} else {
	die ("ERROR: 'goautodial.conf' file not found.");
}

if ( file_exists($conf_path) )
        {
        $DBCagc = file($conf_path);
        foreach ($DBCagc as $DBCline)
                {
                $DBCline = preg_replace("/ |>|\n|\r|\t|\#.*|;.*/","",$DBCline);

                		if (ereg("^VARCOMPANYTHEMECOLOR", $DBCline))
                        {$VARCOMPANYTHEMECOLOR = $DBCline;   $VARCOMPANYTHEMECOLOR = preg_replace("/.*=/","",$VARCOMPANYTHEMECOLOR);}   
                }

        }

$THEMECOLOR = (preg_match('/\d/i',$VARCOMPANYTHEMECOLOR)) ? "#{$VARCOMPANYTHEMECOLOR}" : "$VARCOMPANYTHEMECOLOR";

?>

@charset "UTF-8";

.flags {float:left;margin: 0 6px 0 0;cursor:pointer;}

#flag-en_us
    {
    background:transparent url(../img/flags.png) no-repeat -216px -254px; width: 18px; height: 13px;
    }
    
#flag-es
    {
    background:transparent url(../img/flags.png) no-repeat -168px -69px; width: 18px; height: 13px;
    }
    
#flag-fil
    {
    background:transparent url(../img/flags.png) no-repeat -329px -185px; width: 18px; height: 13px;
    }
    
#flag-fr
    {
    background:transparent url(../img/flags.png) no-repeat -384px -69px; width: 18px; height: 13px;
    }
    
#flag-it
    {
    background:transparent url(../img/flags.png) no-repeat -216px -115px; width: 18px; height: 13px;
    }
