<?
############################################################################################
####  Name:             go_load_styles.php                                              ####
####  Version:          3.0                                                             ####
####  Copyright:        GOAutoDial Inc. - Januarius Manipol <januarius@goautodial.com>  ####
####  License:          AGPLv2                                                          ####
############################################################################################

header("Content-type: text/css");

if (file_exists("/etc/goautodial.conf")) {
	$conf_path = "/etc/goautodial.conf";
} elseif (file_exists("{$_SERVER['DOCUMENT_ROOT']}/goautodial.conf")) {
	$conf_path = "{$_SERVER['DOCUMENT_ROOT']}/goautodial.conf";
} else {
	die ("ERROR: 'goautodial.conf' file not found.");
}

if ( file_exists($conf_path) )
        {
        $DBCagc = file($conf_path);
        foreach ($DBCagc as $DBCline)
                {
                $DBCline = preg_replace("/ |>|\n|\r|\t|\#.*|;.*|\[|\]/","",$DBCline);
                if (ereg("goautodialdbhostname", $DBCline))
                        {$GOdbHostname = $DBCline;   $GOdbHostname = preg_replace("/.*=/","",$GOdbHostname);}
                if (ereg("goautodialdbusername", $DBCline))
                        {$GOdbUsername = $DBCline;   $GOdbUsername = preg_replace("/.*=/","",$GOdbUsername);}
                if (ereg("goautodialdbpassword", $DBCline))
                        {$GOdbPassword = $DBCline;   $GOdbPassword = preg_replace("/.*=/","",$GOdbPassword);}
                if (ereg("goautodialdbdatabase", $DBCline))
                        {$GOdbDatabase = $DBCline;   $GOdbDatabase = preg_replace("/.*=/","",$GOdbDatabase);}

                }

        }
	
if (file_exists("/etc/astguiclient.conf")) {
        $conf_path = "/etc/astguiclient.conf";
} elseif (file_exists("{$_SERVER['DOCUMENT_ROOT']}/astguiclient.conf")) {
        $conf_path = "{$_SERVER['DOCUMENT_ROOT']}/astguiclient.conf";
} else {
        die ("ERROR: 'astguiclient.conf' file not found.");
}

if ( file_exists($conf_path) )
        {
        $DBCagc = file($conf_path);
        foreach ($DBCagc as $DBCline)
                {
                $DBCline = preg_replace("/ |>|\n|\r|\t|\#.*|;.*/","",$DBCline);
                if (ereg("^VARDB_port", $DBCline))
                        {$VARDB_port = $DBCline;   $VARDB_port = preg_replace("/.*=/","",$VARDB_port);}
                }
        }


$golink=mysql_connect("$GOdbHostname:$VARDB_port", "$GOdbUsername", "$GOdbPassword");
if (!$golink)
        {
        die('MySQL connect ERROR: ' . mysql_error());
        }
mysql_select_db("$GOdbDatabase");

$stmt = "SELECT theme_color FROM go_server_settings";
$rslt = mysql_query($stmt, $golink);
$row=mysql_fetch_row($rslt);

$THEMECOLOR = '#'.$row[0];

?>



@charset "UTF-8";

.postbox p,
.postbox ul,
.postbox ol,
.postbox blockquote,
#wp-version-message
    {
        font-size:11px;
    }

.edit-box
    {
        display:none;
    }
    
h3:hover .edit-box
    {
        display:inline;
    }

form .input-text-wrap
    {
        border-style:solid;border-width:1px;padding:2px 3px;border-color:#ccc;
    }

/* DASHBOARD TODAY CONTENT */
#dashboard_todays_status p.sub,
#dashboard_todays_status .table,
#dashboard_todays_status .widgets-content-text{margin:-12px;}
#dashboard_todays_status .inside{font-size:12px;padding-top:20px;}
#dashboard_todays_status p.sub{font-style:italic;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;padding:5px 10px 15px;color:#777;font-size:13px;position:absolute;top:-17px;left:15px;}
#dashboard_todays_status .table{margin:0 -9px;padding:0 10px;position:relative;}
#dashboard_todays_status .table_sales{float:left;border-top: #ececec 1px solid;width:45%;}
#dashboard_todays_status .table_calls{float:right;border-top:#ececec 1px solid;width:45%;}
#dashboard_todays_status .table_drops{float:right;border-top:#ececec 1px solid;width:45%;margin-top:35px;}
#dashboard_todays_status table td{padding:3px 0;white-space:nowrap;}
#dashboard_todays_status table tr.first td{border-top:none;}

#dashboard_todays_status td.b {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#dashboard_todays_status td.b a{font-size:60px; color: #464646}
#dashboard_todays_status td.b a:hover{color:red;}
#dashboard_todays_status td.b.red a{color: red}
#dashboard_todays_status td.b.green a{color: #648803}

#dashboard_todays_status td.o {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#dashboard_todays_status td.o a{font-size:60px; color: #464646}
#dashboard_todays_status td.o a:hover{color:red;}
#dashboard_todays_status td.o.orange a{color: #FF8000}
#dashboard_todays_status td.o.green a{color: #648803}

#dashboard_todays_status td.showhide {font-size:12px; font-style:italic; font-weight: bold; cursor: pointer; padding: 0 0px 0px 0;  color:#464646;}
#dashboard_todays_status td.showhide a{color: #464646; cursor: pointer;}
#dashboard_todays_status td.showhide a:hover{color:green; cursor: pointer;}

#dashboard_todays_status td.dp {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#dashboard_todays_status td.dp a{font-size:20px; color: #FF8000}
#dashboard_todays_status td.dp a:hover{color:#FF8000;}
#dashboard_todays_status td.dp.orange a{color: #FF8000}
#dashboard_todays_status td.dp.green a{color: green}

#dashboard_todays_status td.t {font-size:12px; padding: 0 0px 0px 0;  color:#777;}
#dashboard_todays_status td.t a{color: #777}
#dashboard_todays_status td.t a:hover{color:red;}
#dashboard_todays_status td.t.red a{color: red;}
#dashboard_todays_status td.t.green a{color: green;}
#dashboard_todays_status td.t.bold a{font-weight: bold;}

#dashboard_todays_status td.c {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#dashboard_todays_status td.c a{font-size:30px; color: #464646}
#dashboard_todays_status td.c a:hover{color:red;}
#dashboard_todays_status td.c.red a{color: red;}
#dashboard_todays_status td.c.green a{color: green;}

#dashboard_todays_status td.r {font-size:12px; padding: 0 0px 0px 0;  color:#777;}
#dashboard_todays_status td.r a{color: #777}
#dashboard_todays_status td.r a:hover{color:red;}
#dashboard_todays_status td.r.red a{color: red;}
#dashboard_todays_status td.r.green a{color: green;}

#dashboard_todays_status .spam{color:red;}
#dashboard_todays_status .waiting{color:#e66f00;}
#dashboard_todays_status .approved{color:green;}
#dashboard_todays_status .widgets-content-text {padding:12px 12px 12px;clear:left;}
#dashboard_todays_status .widgets-content-text .b{font-weight:bold;}
#dashboard_todays_status a.button{float:right;clear:right;position:relative;top:-5px;}

#dashboard_todays_status .totalcalls{float:right; position: relative; left: 0px; top: -200px;}
#dashboard_todays_status .totalcalls{font-size:60px; color: #464646}
#dashboard_todays_status .totalcalls a:hover{color:red;}

/* DASHBOARD AGENTS STATUS CONTENT */
#dashboard_agents_status p.sub,
#dashboard_agents_status p.subs,
#dashboard_agents_status .table,
#dashboard_agents_status .widgets-content-text{margin:-12px;}
#dashboard_agents_status .inside{font-size:12px;padding-top:20px;}
#dashboard_agents_status p.sub{font-style:italic;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;padding:5px 10px 15px;color:#777;font-size:13px;position:absolute;top:-17px;left:15px;}
#dashboard_agents_status p.subs{border-bottom: #ececec 1px solid; font-style:italic;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;padding:5px 5px 5px 10px;color:#777;font-size:13px;position:relative;top:20px;left:5px;}
#dashboard_agents_status .table{margin:0 -9px;padding:0 10px;position:relative;}
#dashboard_agents_status .table_agents{float:left;border-top: #ececec 1px solid;width:40%;}
#dashboard_agents_status .table_leads{float:right;border-top:#ececec 1px solid;width:54%;}
#dashboard_agents_status table td{padding:3px 0;white-space:nowrap;}
#dashboard_agents_status table tr.first td{border-top:none;}

#dashboard_agents_status td.b {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#dashboard_agents_status td.b a{font-size:60px; color: #464646}
#dashboard_agents_status td.b a:hover{color:red;}
#dashboard_agents_status td.b.red a{color: red}
#dashboard_agents_status td.b.green a{color: #648803}

#dashboard_agents_status td.t {font-size:12px; padding: 0 0px 0px 0;  color:#777;}
#dashboard_agents_status td.t a{color: #777}
#dashboard_agents_status td.t a:hover{color:red;}
#dashboard_agents_status td.t.red a{color: red}
#dashboard_agents_status td.t.green a{color: green}
#dashboard_agents_status td.t.bold a{font-weight: bold;}

#dashboard_agents_status td.c {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#dashboard_agents_status td.c a{font-size:30px; color: #464646}
#dashboard_agents_status td.c a:hover{color:red;}
#dashboard_agents_status td.c.red a{color: red}
#dashboard_agents_status td.c.green a{color: green}

#dashboard_agents_status td.dr {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#dashboard_agents_status td.dr a{font-size:20px; color: red}
#dashboard_agents_status td.dr a:hover{color:red;}
#dashboard_agents_status td.dr.red a{color: red}
#dashboard_agents_status td.dr.green a{color: green}

#dashboard_agents_status td.dp {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#dashboard_agents_status td.dp a{font-size:20px; color: #FF8000}
#dashboard_agents_status td.dp a:hover{color:#FF8000;}
#dashboard_agents_status td.dp.orange a{color: #FF8000}
#dashboard_agents_status td.dp.green a{color: green}

#dashboard_agents_status td.cr {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#dashboard_agents_status td.cr a{font-size:30px; color: red}
#dashboard_agents_status td.cr a:hover{color:red;}
#dashboard_agents_status td.cr.red a{color: red}
#dashboard_agents_status td.cr.green a{color: green}

#dashboard_agents_status td.r {font-size:12px; padding: 0 0px 0px 0;  color:#777;}
#dashboard_agents_status td.r a{color: #777}
#dashboard_agents_status td.r a:hover{color:red;}
#dashboard_agents_status td.r.red a{color: red}
#dashboard_agents_status td.r.green a{color: green}

#dashboard_agents_status td.showhide {font-size:12px; font-style:italic; font-weight: bold; cursor: pointer; padding: 0 0px 0px 0;  color:#464646;}
#dashboard_agents_status td.showhide a{color: #464646; cursor: pointer;}
#dashboard_agents_status td.showhide a:hover{color:green; cursor: pointer;}

#dashboard_agents_status .spam{color:red;}
#dashboard_agents_status .waiting{color:#e66f00;}
#dashboard_agents_status .approved{color:green;}
#dashboard_agents_status .widgets-content-text {padding:12px 12px 12px;clear:left;}
#dashboard_agents_status .widgets-content-text .b{font-weight:bold;}
#dashboard_agents_status a.button{float:right;clear:right;position:relative;top:-5px;}

#dashboard_agents_status .totalcalls{float:right; position: relative; left: 0px; top: -200px;}
#dashboard_agents_status .totalcalls{font-size:60px; color: #464646}
#dashboard_agents_status .totalcalls a:hover{color:red;}

/* DASHBOARD WIDGETS CONTENT */
#dashboard-widgets form .input-text-wrap input{border:0 none;outline:none;margin:0;padding:0;width:99%;color:#333;}form .textarea-wrap{border-style:solid;border-width:1px;padding:2px;border-color:#ccc;}
#dashboard-widgets form .textarea-wrap textarea{border:0 none;padding:0;outline:none;width:99%;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;}
#dashboard-widgets .postbox form .submit{float:none;margin:.5em 0 0;padding:0;border:none;}
#dashboard-widgets-wrap #dashboard-widgets .postbox form .submit input{margin:0;}
#dashboard-widgets-wrap #dashboard-widgets .postbox form .submit #publish{min-width:0;}div.postbox div.inside{margin:10px;position:relative;}
#dashboard-widgets a{text-decoration:none;}
#dashboard-widgets h3 a{text-decoration:underline;}
#dashboard-widgets h3 .postbox-title-action{position:absolute;right:30px;padding:0;}
#dashboard-widgets h4{font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:13px;margin:0 0 .2em;padding:0;}

/* DASHBOARD ANALYTICS CONTENT */
#dashboard_analytics p.sub,
#dashboard_analytics .table,
#dashboard_analytics .widgets-content-text{margin:-12px;}
#dashboard_analytics .inside{font-size:12px;padding-top:20px;}
#dashboard_analytics p.sub{font-style:italic;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;padding:5px 10px 15px;color:#777;font-size:13px;position:absolute;top:-17px;left:15px;}
#dashboard_analytics .table{margin:0 -9px;padding:0 10px;position:relative;}
#dashboard_analytics .table_sales{float:left;border-top: #ececec 1px solid;width:45%;}
#dashboard_analytics .table_calls{float:right;border-top:#ececec 1px solid;width:45%;}
#dashboard_analytics .table_drops{float:right;border-top:#ececec 1px solid;width:45%;}
#dashboard_analytics table td{padding:3px 0;white-space:nowrap;}
#dashboard_analytics table tr.first td{border-top:none;}
#dashboard_analytics td.b{padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#dashboard_analytics td.b a{font-size:18px;}
#dashboard_analytics td.b a:hover{color:#000;}
#dashboard_analytics .t{font-size:12px;padding-right:12px;padding-top:6px;color:#777;}
#dashboard_analytics .t a{white-space:nowrap;}
#dashboard_analytics .spam{color:red;}
#dashboard_analytics .waiting{color:#e66f00;}
#dashboard_analytics .approved{color:green;}
#dashboard_analytics .widgets-content-text{padding:8px 8px 12px;clear:left;}
#dashboard_analytics .widgets-content-text .b{font-weight:bold;}
#dashboard_analytics a.button{float:right;clear:right;position:relative;top:-5px;}

/* DASHBOARD TODAY CONTENT */
#dashboard_server_statistics .sub, #dashboard_cluster_status .sub
    {
        font-style:italic;
        font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
        color:#777;
        font-size:13px;
        margin-bottom: 5px;
        margin-top: 5px;
    }
    
#dashboard_server_statistics .separate, #dashboard_cluster_status .separate
{
    border-bottom:#ececec 1px solid;
    margin-bottom: 5px;
} 



#dashboard_server_statistics .tabhead, #dashboard_cluster_status .tabhead
{
    font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
    font-size:12px;
    font-weight: bold;
    color: #464646;
}

#dashboard_server_statistics .tabdata, #dashboard_cluster_status .tabdata
{
    font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
    font-size:10px;
    color: #464646;
}

/* DASHBOARD CONTROL PANEL */
#dashboard_controls div.sub, div.left_status
    {
        font-style:italic;
        font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
        color:#777;
        font-size:13px;
        margin-bottom: 5px;
        margin-top: 5px;
    }
    
#dashboard_controls .separate
{
    border-bottom:#ececec 1px solid;
    margin-bottom: 5px;
}

#dashboard_controls .sub{width:15%;float:left;text-align:left;padding:5px 0 0 35px;}
#dashboard_controls .left_status{float: left;width:75%;padding:5px;}
#dashboard_controls .left_status a{float: right;};

/* DASHBOARD BLOGS RSS CONTENT */
.rss_widget ul{margin:0;padding:0;list-style:none;}
.rss_widget ul li{line-height:1.5em;margin-bottom:12px;}
.rss_widget span.rss-date{margin-left:3px;}
.rss_widget cite{display:block;text-align:right;margin:0 0 1em;padding:0;}
.rss_widget cite:before{content:'\2014';}
a.rss_widget{font-size:16px;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;line-height:1.7em;}

.rss_widget
    {
        /*resize: both
        width: 100%;
        height: 400px;*/
        margin-right: 3px;
        overflow: hidden;
    }
    
.rss_widget:hover
    {
        overflow-y: auto;
    }


/* DASHBOARD PLUGINS CONTENT */
#dashboard_plugins h4{font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;}
#dashboard_plugins h5{font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:13px!important;margin:0;display:inline;line-height:1.4em;}
#dashboard_plugins h5 a{font-weight:normal;line-height:1.7em;}
#dashboard_plugins p{display:block;text-align:left;margin:0 0 1em;padding:0;}

html,
body,
div,
span,
applet,
object,
iframe,
h1,h2,h3,h4,h5,h6,
p,
blockquote,
pre,
a,
abbr,
acronym,
address,
big,
cite,
code,
del,
dfn,
em,
font,
img,
ins,
kbd,
q,
s,
samp,
small,
strike,
strong,
sub,
sup,
tt,
var,
b,
u,
i,
center,
dl,
dt,
dd,
ol,
ul,
li,
fieldset,
form,
label,
legend,
table,
caption,
tbody,
tfoot,
thead,
tr,
th,
td
    {
        margin:0;padding:0;border:0;outline:0;background:transparent;
    }
    
body
    {
        line-height:1;
    }

ol,ul
    {
        list-style:none;
    }

#wpwrap
    {
        height:auto;min-height:100%;width:100%;
    }

#wpcontent
    {
        height:100%;
    }
#wpcontent select{padding:2px; height:2em;font-size:11px;}
#wpcontent option{padding:2px;}

#wpbody
    {
        clear:both;margin-left:175px; margin-bottom: 5px;
    }

.folded #wpbody
    {
    margin-left:60px;
    }

#wpbody-content
    {
    float:left; width:100%; margin-top: 0px; 
    }

#wpbody-content .metabox-holder{padding-top:0px;}


#outer {height: 400px; overflow: hidden; position: relative;}
#outer[id] {display: table; position: static;}
		
#middle {position: absolute; top: 50%;} /* for explorer only*/
#middle[id] {display: table-cell; vertical-align: middle; width: 100%;}
		
#inner {position: relative; top: -50%} /* for explorer only */
/* optional: #inner[id] {position: static;} */


#adminmenu
    {
        position:fixed;
        /*position:relative;*/
        float:left;
        clear:left;
        /*width:145px;*/
        /*margin-top:95px;*/
        top: 40%;
        /*margin-right:2px;*/
        /*margin-bottom:15px;*/
        margin-left:-170px;
        padding:0;
        list-style:none;
    }

.folded #adminmenu
    {
        margin-left:-55px;
    }

.folded #adminmenu,
.folded #adminmenu li.menu-top
    {
        width:28px;
    }

.alignleft
{float:left;}

.alignright
{float:right;}

.textleft
{text-align:left;}

.textright
{text-align:right;}

.clear
{clear:both;}

.screen-reader-text,
.screen-reader-text span
{position:absolute;left:-1000em;height:1px;width:1px;overflow:hidden;}

.hidden,
.js .closed .inside,
.js .hide-if-js,
.no-js .hide-if-no-js
{display:none;}

input[type="text"],input[type="password"],textarea
{-moz-box-sizing:border-box;-webkit-box-sizing:border-box;-ms-box-sizing:border-box;box-sizing:border-box;}

input[type="checkbox"],input[type="radio"]
{vertical-align:middle;}

html,body
{height:100%;}

body,td,textarea,input,select
{font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:13px;}

body,textarea
{line-height:1.4em;}

input,select
{line-height:15px;}

p
{margin:1em 0;}

blockquote
{margin:1em;}

label
{cursor:pointer;}

li,dd
{margin-bottom:6px;}

p,li,dl,dd,dt
{line-height:140%;}

textarea,input,select
{margin:1px;padding:3px;}

h1{display:block;font-size:2em;font-weight:bold;margin:.67em 0;}
h2{display:block;font-size:1.5em;font-weight:bold;margin:.83em 0;}
h3{display:block;font-size:1.17em;font-weight:bold;margin:1em 0;}
h4{display:block;font-size:1em;font-weight:bold;margin:1.33em 0;}
h5{display:block;font-size:.83em;font-weight:bold;margin:1.67em 0;}
h6{display:block;font-size:.67em;font-weight:bold;margin:2.33em 0;}

ul.ul-disc{list-style:disc outside;}
ul.ul-square{list-style:square outside;}
ol.ol-decimal{list-style:decimal outside;}
ul.ul-disc,ul.ul-square,ol.ol-decimal{margin-left:1.8em;}
ul.ul-disc>li,ul.ul-square>li,ol.ol-decimal>li{margin:0 0 .5em;}

.wrap{margin:55px 0px 0 -5px;}
.wrap .updated,.wrap .error{margin:5px 0 0px;}
.wrap h2{font:italic normal normal 24px/29px Lucida Sans Unicode, Lucida Grande, sans-serif;margin:0;padding:0px 0px 10px 0;line-height:40px;text-shadow:rgba(255,255,255,1) 0 1px 0;}
.wrap h2.long-header{padding-right:0;}


content .metabox-holder{padding-top:0px;}



textarea,
input[type="text"],
input[type="password"],
input[type="file"],
input[type="button"],
input[type="submit"],
input[type="reset"],
select
{border-width:1px;border-style:solid;-moz-border-radius:4px;-khtml-border-radius:4px;-webkit-border-radius:4px;border-radius:4px;}

p,ul,ol,blockquote,input,select{font-size:12px;}

.submit input,
.button,
input.button,
.button-primary,
input.button-primary,
.button-secondary,
input.button-secondary,
.button-highlighted,
input.button-highlighted,
#postcustomstuff .submit input
{text-decoration:none;font-size:11px!important;line-height:13px;padding:3px 8px;cursor:pointer;border-width:1px;border-style:solid;-moz-border-radius:11px;-khtml-border-radius:11px;-webkit-border-radius:11px;border-radius:11px;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;-khtml-box-sizing:content-box;box-sizing:content-box;}

a.button,
a.button-primary,
a.button-secondary
{line-height:15px;padding:3px 10px;white-space:nowrap;-webkit-border-radius:10px;}

textarea.all-options,input.all-options{width:250px;}

#content{margin:0;width:100%;}

.zerosize
{height:0;width:0;margin:0;border:0;padding:0;overflow:hidden;position:absolute;}

* html #themeselect
{padding:0 3px;height:22px;}

.nav .button-secondary
{padding:2px 4px;}

a.page-numbers
{border-bottom-style:solid;border-bottom-width:2px;font-weight:bold;margin-right:1px;padding:0 2px;}

p.pagenav
{margin:0;display:inline;}

.pagenav span
{font-weight:bold;margin:0 6px;}

.row-title
{font-size:12px!important;font-weight:bold;}

.widefat .column-comment p
{margin:.6em 0;}

.column-author img,.column-username img
{float:left;margin-right:10px;margin-top:3px;}

.tablenav a.button-secondary{display:block;margin:3px 8px 0 0;}
.tablenav{clear:both;height:30px;margin:6px 0 4px;vertical-align:middle;}
.tablenav .tablenav-pages{float:right;display:block;cursor:default;height:30px;line-height:30px;font-size:11px;}
.tablenav .tablenav-pages a,.tablenav-pages span.current
    {
        text-decoration:none;
        border:none;
        padding:3px 6px;
        border-width:1px;
        border-style:solid;
        -moz-border-radius:5px;
        -khtml-border-radius:5px;
        -webkit-border-radius:5px;
        border-radius:5px;
    }
.tablenav .displaying-num{margin-right:10px;font-size:12px;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-style:italic;}
.tablenav .actions{padding:2px 8px 0 0;}td.media-icon{text-align:center;width:80px;padding-top:8px;padding-bottom:8px;}td.media-icon img{max-width:80px;max-height:60px;}



#middle-head {float: left; position: fixed; z-index:999; top: 0%;} /* for explorer only*/
#middle-head[id] {display: table-cell; vertical-align: top; width: 100%;}

#wphead{height:30px; width: 100%;}
#wphead a,#adminmenu a,#sidemenu a{text-decoration:none;}
#wphead h1{font:normal 22px verdana, Lucida Sans Unicode, Lucida Grande, sans-serif; padding:10px 8px 5px;margin:0;float:left;}
#wphead h1 a:hover{text-decoration:none;}
#wphead h1 a:hover #site-title,#wphead h1 a#privacy-on-link:hover{text-decoration:none;}

#header-logo{float:left; margin:1.5px 0 0 5px; border:none; height:28px; width: 99px;}

#wphead-info{margin:0px 0px 0 0px;  padding: 0px 15px 0px 0px;}




#user_info{float:right;font-size:12px;line-height:30px;height:30px;text-align: right;}

/*
#clockbox {float:right;font-size:12px;}
#clockbox {margin-top: 30px; margin-right: -220px; text-align: right;}
.chrome #clockbox {margin-top: 30px; margin-right: -220px; text-align: right;}
*/

#update-nag,.update-nag {line-height:19px;padding:5px 5px;font-size:12px;text-align:center;margin:0px 15px; border-width:1px;border-style:solid;border-top-width:0;border-top-style:none;-moz-border-radius:0 0 6px 6px;-webkit-border-bottom-right-radius:6px;-webkit-border-bottom-left-radius:6px;-khtml-border-bottom-right-radius:6px;-khtml-border-bottom-left-radius:6px;border-bottom-right-radius:6px;border-bottom-left-radius:6px;}

#screen-nag,.screen-nag {line-height:19px;padding:5px 5px;font-size:12px;text-align:center;margin:0px 15px; border: none;}


#adminmenu *{-webkit-user-select:none;-moz-user-select:none;-khtml-user-select:none;user-select:none;}
#adminmenu .wp-submenu{display:none;list-style:none;padding:0;margin:0;position:relative;z-index:2;border-width:1px 0 0;border-style:solid none none;}
#adminmenu .wp-submenu a{font:normal 11px/18px Lucida Sans Unicode, Lucida Grande, sans-serif; font-weight: normal;}
#adminmenu .wp-submenu li.current,#adminmenu .wp-submenu li.current a,#adminmenu .wp-submenu li.current a:hover{font-weight:bold;}
#adminmenu a.menu-top,#adminmenu .wp-submenu-head{font:normal 13px/18px Lucida Sans Unicode, Lucida Grande, sans-serif; font-weight: bold;}
#adminmenu div.wp-submenu-head{display:none;}
.folded #adminmenu div.wp-submenu-head,.folded #adminmenu li.wp-has-submenu div.sub-open{display:block;}
.folded #adminmenu a.menu-top,.folded #adminmenu .wp-submenu,.folded #adminmenu li.wp-menu-open .wp-submenu,.folded #adminmenu div.wp-menu-toggle{display:none;}
#adminmenu li.wp-menu-open .wp-submenu,.no-js #adminmenu .open-if-no-js .wp-submenu{display:block;}
#adminmenu div.wp-menu-image{float:left;width:28px;height:28px;}
#adminmenu li{margin:0;padding:0;cursor:pointer;}
#adminmenu a{display:block;line-height:18px;padding:1px 5px 3px;}
#adminmenu li.menu-top{min-height:26px;}
#adminmenu a.menu-top{line-height:18px;min-width:10em;padding:5px 5px;border-width:1px 1px 0;border-style:solid solid none;}
#adminmenu .wp-submenu a{margin:0;padding-left:12px;border-width:0 1px 0 0;border-style:none solid none none;}
#adminmenu .menu-top-last ul.wp-submenu{border-width:0 0 1px;border-style:none none solid;}
#adminmenu .wp-submenu li{padding:0;margin:0;}
.folded #adminmenu li.menu-top{width:28px;height:30px;overflow:hidden;border-width:1px 1px 0;border-style:solid solid none;}
#adminmenu .menu-top-first a.menu-top,.folded #adminmenu li.menu-top-first,#adminmenu .wp-submenu .wp-submenu-head{border-width:1px 1px 0;border-style:solid solid none;-moz-border-radius-topleft:6px;-moz-border-radius-topright:6px;-webkit-border-top-right-radius:6px;-webkit-border-top-left-radius:6px;-khtml-border-top-right-radius:6px;-khtml-border-top-left-radius:6px;border-top-right-radius:6px;border-top-left-radius:6px;}
#adminmenu .menu-top-last a.menu-top,.folded #adminmenu li.menu-top-last{border-width:1px;border-style:solid;-moz-border-radius-bottomleft:6px;-moz-border-radius-bottomright:6px;-webkit-border-bottom-right-radius:6px;-webkit-border-bottom-left-radius:6px;-khtml-border-bottom-right-radius:6px;-khtml-border-bottom-left-radius:6px;border-bottom-right-radius:6px;border-bottom-left-radius:6px;}
#adminmenu li.wp-menu-open a.menu-top-last{border-bottom:0 none;-moz-border-radius-bottomright:0;-moz-border-radius-bottomleft:0;-webkit-border-bottom-right-radius:0;-webkit-border-bottom-left-radius:0;-khtml-border-bottom-right-radius:0;-khtml-border-bottom-left-radius:0;border-bottom-right-radius:0;border-bottom-left-radius:0;}
#adminmenu .wp-menu-image img{float:left;padding:8px 6px 0;opacity:.6;filter:alpha(opacity=60);}
#adminmenu li.menu-top:hover .wp-menu-image img,#adminmenu li.wp-has-current-submenu .wp-menu-image img{opacity:1;filter:alpha(opacity=100);}
#adminmenu li.wp-menu-separator{height:21px;padding:0;margin:0;}
#adminmenu a.separator{cursor:w-resize;height:20px;padding:0;}
.folded #adminmenu a.separator{cursor:e-resize;}
#adminmenu .wp-menu-separator-last{height:10px;width:1px;}
#adminmenu .wp-submenu .wp-submenu-head{border-width:1px;border-style:solid;padding:6px 4px 6px 10px;cursor:default;}
.folded #adminmenu .wp-submenu{position:absolute;margin:-1px 0 0 28px;padding:0 8px 8px;z-index:999;border:0 none;}
.folded #adminmenu .wp-submenu ul{width:140px;border-width:0 0 1px;border-style:none none solid;}
.folded #adminmenu .wp-submenu li.wp-first-item{border-top:0 none;}
.folded #adminmenu .wp-submenu a{padding-left:10px;}
.folded #adminmenu a.wp-has-submenu{margin-left:40px;}
#adminmenu li.menu-top-last .wp-submenu ul{border-width:0 0 1px;border-style:none none solid;}
#adminmenu .wp-menu-toggle{width:22px;clear:right;float:right;margin:1px 0 0;height:27px;padding:1px 2px 0 0;cursor:default;}
#adminmenu .wp-menu-image a{height:24px;}
#adminmenu .wp-menu-image img{padding:6px 0 0 1px;}
#adminmenu #awaiting-mod,#adminmenu span.update-plugins,#sidemenu li a span.update-plugins{position:absolute;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:9px;line-height:17px;font-weight:bold;margin-top:1px;margin-left:7px;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;}
#adminmenu li #awaiting-mod span,#adminmenu li span.update-plugins span,#sidemenu li a span.update-plugins span{display:block;padding:0 6px;}
#adminmenu li span.count-0,#sidemenu li a .count-0{display:none;}

#footer
    {
   position:fixed;bottom:0px;z-index:1;text-align:left;
    font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
   
   width 100%;
   /* height: 35px; width: 1280px;padding: 5px 0px 0px 5px; margin-bottom: 0px; */
   height: 20px; width: 100%;padding: 5px 0px 0px 5px; margin-bottom: 0px; 
   
    }

.postbox{position:relative;min-width:255px;width:99.5%;}
#poststuff h2{margin-top:20px;font-size:1.5em;margin-bottom:15px;padding:0 0 3px;clear:left;}
.widget .widget-top,
.postbox h3{cursor:move;-webkit-user-select:none;-moz-user-select:none;-khtml-user-select:none;user-select:none;}
.postbox .hndle span{padding:6px 0;font-family:Verdana,Arial,Helvetica,sans-serif;}
.postbox .hndle{cursor:move;}
.hndle a{font-size:11px;font-weight:normal;}
.postbox div.hndle{padding:7px 9px;margin:0;line-height:1;font-size:12px;font-weight:bold;}
#dashboard-widgets .meta-box-sortables{margin:0 5px;}
.postbox .handlediv{float:right;width:23px;height:26px;}
.postbox .actiondiv{float:right;width:26px;height:26px; cursor: pointer;}
.postbox .widgetconfig{float:right;width:26px;height:26px; cursor: pointer;display:none;}
#poststuff h3,.metabox-holder h3{font-size:12px;font-weight:bold;padding:7px 9px;margin:0;line-height:1;}
.widget,.postbox,.stuffbox{
margin-bottom:20px;border-width:1px;border-style:solid;line-height:1;-moz-border-radius:3px;-khtml-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;
box-shadow: 0px 1.1px 0px #d8d8d8;
/* margin-bottom:20px;border-width:1px;border-style:solid;line-height:1;
box-shadow: 0px 0px 0px #888888; 
border-raduis: 6px 6px 6px 6px;
-moz-border-radius-bottomleft:4px;-webkit-border-bottom-left-radius:4px;-khtml-border-bottom-left-radius:4px;border-bottom-left-radius:4px;-moz-border-radius-bottomright:4px;-webkit-border-bottom-right-radius:4px;-khtml-border-bottom-right-radius:4px;border-bottom-right-radius:4px; */
}
.widget .widget-top,.postbox h3,.postbox h3,.stuffbox h3{-moz-border-radius:6px 6px 0 0;-webkit-border-top-right-radius:6px;-webkit-border-top-left-radius:6px;-khtml-border-top-right-radius:6px;-khtml-border-top-left-radius:6px;border-top-right-radius:6px;border-top-left-radius:6px;}
.postbox.closed h3{-moz-border-radius-bottomleft:4px;-webkit-border-bottom-left-radius:4px;-khtml-border-bottom-left-radius:4px;border-bottom-left-radius:4px;-moz-border-radius-bottomright:4px;-webkit-border-bottom-right-radius:4px;-khtml-border-bottom-right-radius:4px;border-bottom-right-radius:4px;}
.postbox table.form-table{margin-bottom:0;}
.postbox input[type="text"],.postbox textarea,.stuffbox input[type="text"],.stuffbox textarea{border-width:1px;border-style:solid;} 
.sortable-placeholder{border-width:1px;border-style:dashed;margin-bottom:20px;}

/* SCREEN OPTIONS BUTTON COLOR */

#spotlight-link-wrap {float:right;height:22px;padding:0;margin:0 0px 0 0;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;background:#e3e3e3;  }
#spotlight-wrap li{list-style-type:disc;margin-left:18px;}
#spotlight-wrap h5{margin:0px 0;font-size:13px;}
#spotlight-wrap
    {
        border-style:none solid solid;
        border-top:0 none;
        border-width:0 1px 1px;
        margin:0 0px;
        -moz-border-radius:0 0 0 4px;
        -webkit-border-bottom-left-radius:4px;
        -khtml-border-bottom-left-radius:4px;
        border-bottom-left-radius:4px;
    }


#spotlight-open-link-wrap a:link {
    float:right;
        height: 15px;
        width: 15px;
height:24px;padding:0;
margin:8px 5px 0 5px;
background-image:url(../img/spotlight.png);
    background-repeat:no-repeat;
}


#spotlight-open-link-wrap a:hover{
    float:right;
    height: 15px;
    width: 15px;
    height:24px;padding:0;
    margin:8px 5px 0 5px;
    background-image:url(../img/spotlight-hover.png);
    background-repeat:no-repeat;
}

#spotlight-open-wrap {float:right; height:28px; margin:0px -43px 0px 0px;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;


        background:-moz-linear-gradient(bottom,#F7FFE9,#CAD9B1);
        background:-webkit-gradient(linear,left bottom,left top,from(#CAD9B1),to(#F7FFE9));

 -moz-border-radius-bottomleft:2px;
 -moz-border-radius-bottomright:2px;
 -webkit-border-bottom-left-radius:2px;
 -webkit-border-bottom-right-radius:2px;
border: #B8C6A1 1px solid;
}


/* Notification */
#notification-open-link-wrap a:link {
    float:right;
        height: 18px;
        width: 18px;
height:24px;padding:0;
margin:6px 0 0 5px;
background-image:url(../../img/notification.png);
    background-repeat:no-repeat;
}


#notification-open-link-wrap a:hover{
    float:right;
    height: 18px;
    width: 18px;
    height:24px;padding:0;
    margin:6px 0 0 5px;
/*     background-image:url(../img/notification-hover.png); */
    background-repeat:no-repeat;
}

#notification-open-wrap {float:right; height:28px; margin:0px -43px 0px 0px;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;


        background:-moz-linear-gradient(bottom,#F7FFE9,#CAD9B1);
        background:-webkit-gradient(linear,left bottom,left top,from(#CAD9B1),to(#F7FFE9));

 -moz-border-radius-bottomleft:2px;
 -moz-border-radius-bottomright:2px;
 -webkit-border-bottom-left-radius:2px;
 -webkit-border-bottom-right-radius:2px;
border: #B8C6A1 1px solid;
}







#screen-meta .screen-reader-text{visibility:hidden;}
#contextual-help-link-wrap{float:right;height:22px;padding:0;margin:0 6px 0 0;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;background:#e3e3e3; border: #B8C6A1 1px solid; -moz-border-radius-bottomleft:3px;-moz-border-radius-bottomright:3px;-webkit-border-bottom-left-radius:3px;-webkit-border-bottom-right-radius:3px;}

#advanced-search-link-wrap{float:right;height:24px;padding:0;margin:0px 20px 0 20px;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;background:#e3e3e3; border: #B8C6A1 1px solid; -moz-border-radius-bottomleft:3px;-moz-border-radius-bottomright:3px;-webkit-border-bottom-left-radius:3px;-webkit-border-bottom-right-radius:3px;}
#quick-search-link-wrap {float:right;height:22px;padding:0;margin:0 6px 0 0;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;background:#e3e3e3;  }
#contextual-help-wrap li,#advanced-search-wrap li{list-style-type:disc;margin-left:18px;}
#quick-search-wrap li{list-style-type:disc;margin-left:18px;}

#screen-meta a.show-settings
    {
        
        text-decoration:none;
        z-index:1;
        padding:0 15px 0 5px;
        height:25px;
        line-height:25px;
        font-size:10px;
        display:block;
        background-repeat:no-repeat;
        background-position:right bottom;
    }
#screen-meta a.show-settings:hover{text-decoration:none;}
#screen-options-wrap h5,#contextual-help-wrap h5,#advanced-search-wrap h5{margin:0px 0;font-size:13px;}


#screen-options-link-wrap a:link{
    float: right;
    height: 18px;
    width: 18px;
    margin:6px 5px 0 0px;
    background-image:url(../img/settings.png);
    background-repeat:no-repeat;
}    
        
      
#screen-options-link-wrap a:hover{
    float: right;
    height: 18px;
    width: 18px;
    margin:6px 5px 0 0px;
    background-image:url(../img/settings-hover.png);
    background-repeat:no-repeat;
} 


#quick-search-wrap h5{margin:8px 0;font-size:13px;}
#screen-options-wrap,#contextual-help-wrap,#advanced-search-wrap
    {
        border-style:none solid solid;
        border-top:0 none;
        border-width:0 1px 1px;
        margin:0 10px;
        padding:8px 12px 12px;
 -moz-border-radius-bottomleft:6px;
 -moz-border-radius-bottomright:6px;
 -webkit-border-bottom-left-radius:6px;
 -webkit-border-bottom-right-radius:6px;
    }

#quick-search-wrap
    {
        border-style:none solid solid;
        border-top:0 none;
        border-width:0 1px 1px;
        margin:0 15px;
        padding:8px 12px 12px;
        -moz-border-radius:0 0 0 4px;
        -webkit-border-bottom-left-radius:4px;
        -khtml-border-bottom-left-radius:4px;
        border-bottom-left-radius:4px;
    }

.metabox-prefs label{padding-right:15px;white-space:nowrap;line-height:30px;}
.metabox-prefs label input{margin:0 5px 0 2px;}
.metabox-prefs label a{display:none;}tr.inline-edit-row td{padding:0 .5em;}

#favorite-actions{float:right;margin:11px 12px 0;min-width:130px;position:relative;}
#favorite-first
    {
        -moz-border-radius:12px;
        -khtml-border-radius:12px;
        -webkit-border-radius:12px;
        border-radius:12px;
        line-height:15px;
        padding:3px 30px 4px 12px;
        border-width:1px;
        border-style:solid;
    }
#favorite-inside
    {
        margin:0;
        padding:2px 1px;
        border-width:1px;
        border-style:solid;
        position:absolute;
        z-index:11;
        display:none;
        -moz-border-radius:0 0 12px 12px;
        -webkit-border-bottom-right-radius:12px;
        -webkit-border-bottom-left-radius:12px;
        -khtml-border-bottom-right-radius:12px;
        -khtml-border-bottom-left-radius:12px;
        border-bottom-right-radius:12px;
        border-bottom-left-radius:12px;
    }
#favorite-actions a{display:block;text-decoration:none;font-size:11px;}
#favorite-inside a{padding:3px 5px 3px 10px;}
#favorite-toggle{height:22px;position:absolute;right:0;top:1px;width:28px;}
#favorite-actions .slide-down
    {
        -moz-border-radius:12px 12px 0 0;
        -webkit-border-bottom-right-radius:0;
        -webkit-border-bottom-left-radius:0;
        -khtml-border-bottom-right-radius:0;
        -khtml-border-bottom-left-radius:0;
        border-bottom-right-radius:0;
        border-bottom-left-radius:0;
        border-bottom:none;
    }

#poststuff .inside .the-tagcloud
    {
        margin:5px 0 10px;
        padding:8px;
        border-width:1px;
        border-style:solid;
        line-height:1.8em;
        word-spacing:3px;
        -moz-border-radius:6px;
        -khtml-border-radius:6px;
        -webkit-border-radius:6px;
        border-radius:6px;
    }

br.clear
{height:2px;line-height:2px;}

.icon32
{float:left;height:40px;margin:0px 12px 0 0px;width:40px;}

.postbox-container
{float:left;padding-right:0%;}

.postbox-container .meta-box-sortables
{min-height:300px;}

.temp-border
{border:1px dotted #ccc;}



	.suggestionsBox {
float: right;
position: relative;
margin: 28px -272px 0px 0px;		width: 270px;
        background:#F7FFE9;
        -webkit-border-bottom-right-radius:12px;
        -webkit-border-bottom-left-radius:12px;
        -khtml-border-bottom-right-radius:12px;
        -khtml-border-bottom-left-radius:12px;
        border-bottom-right-radius:12px;
        border-bottom-left-radius:12px;
		border: 1px solid #dfdfdf;
         
         
         max-height: 500px;       
                
height: 100%;
overflow-y:auto;

overflow-x: hidden;

                
		color: #393939;
	}
	
        
        
	.suggestionList img{
		margin: 0px 0px 0px -10px;
		padding: 8px 0px 5px 0px;
                font-weight: bold;
	}	
	
		
	.suggestionList ul{
		margin: 0px;
		padding: 8px 0px 5px 0px;
                font-weight: bold;
	}
        
	.suggestionList {
		margin: 0px 20px;
		padding: 0px;
	}
	
	.suggestionList li {

		
		margin: 0px 0px 8px 23px;
		padding: 0px 0px 0px 0px;
		cursor: pointer;
	}
	
	.suggestionList li:hover {
		background-color: #D9E6C6;
	}
	
	
	
/* ACCOUNT INFO CONTENT */
#account_info_status p.sub,
#account_info_status p.submenu,
#account_info_status .table,
#account_info_status .widgets-content-text{margin:-12px;}
/* #account_info_status .inside{font-size:12px;padding-top:20px;} */
#account_info_status .inside{font-size:12px;}
#account_info_status p.sub{font-style:italic;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;padding:5px 10px 15px;color:#777;font-size:13px;position:absolute;top:-17px;left:15px;}
#account_info_status p.submenu{font-style:italic;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;padding:5px 10px 15px;color:#777;font-size:13px;position:absolute;top:5px;left:10px;}
#account_info_status .table{margin:0 -9px;padding:5px 10px;position:relative;}
#account_info_status .table_balance{float:left;border-top: #ececec 1px solid;width:45%;}
#account_info_status .table_account{float:right;border-top:#ececec 1px solid;width:45%;}
#account_info_status .table_info{float:left;border-top:#ececec 1px solid;width:45%;}
#account_info_status .table_payhistory{float:left;border-top:#ececec 0px solid;width:60%;}
#account_info_status .table_reports{float:left;border-top:#ececec 1px solid;width:100%;}
#account_info_status table td{padding:3px 0;white-space:nowrap;}
#account_info_status table tr.first td{border-top:none;}

#account_info_status td.b {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#account_info_status td.b a{font-size:60px; color: #464646}
#account_info_status td.b a:hover{color:red;}
#account_info_status td.b.red a{color: red}
#account_info_status td.b.green a{color: #648803}

#account_info_status td.o {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#account_info_status td.o a{font-size:60px; color: #464646}
#account_info_status td.o a:hover{color:red;}
#account_info_status td.o.orange a{color: #FF8000}
#account_info_status td.o.green a{color: #648803}

#account_info_status td.showhide {font-size:12px; font-style:italic; font-weight: bold; cursor: pointer; padding: 0 0px 0px 0;  color:#464646;}
#account_info_status td.showhide a{color: #464646; cursor: pointer;}
#account_info_status td.showhide a:hover{color:green; cursor: pointer;}

#account_info_status td.dp {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#account_info_status td.dp a{font-size:20px; color: #FF8000}
#account_info_status td.dp a:hover{color:#FF8000;}
#account_info_status td.dp.orange a{color: #FF8000}
#account_info_status td.dp.green a{color: green}

#account_info_status td.t {font-size:12px; padding: 0 0px 0px 0;  color:#777;}
#account_info_status td.t a{color: #777}
#account_info_status td.t a:hover{color:red;}
#account_info_status td.t.red a{color: red;}
#account_info_status td.t.green a{color: green;}
#account_info_status td.t.bold a{font-weight: bold;}

#account_info_status td.c {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#account_info_status td.c a{font-size:30px; color: #464646}
#account_info_status td.c a:hover{color:red;}
#account_info_status td.c.red a{color: red;}
#account_info_status td.c.green a{color: green;}

#account_info_status td.d {padding-left:6px;text-align:left;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#account_info_status td.d a{font-size:24px; color: #464646;}
#account_info_status td.d a:hover{color:red;}
#account_info_status td.d.red a{color: red;}
#account_info_status td.d.green a{color: green;}

#account_info_status td.e {padding-left:6px;text-align:left;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#account_info_status td.e a{font-size:18px; color: #464646}
#account_info_status td.e a:hover{color:red;}
#account_info_status td.e.red a{color: red;}
#account_info_status td.e.green a{color: green;}

#account_info_status td.f {font-size:12px; padding: 1px 0; padding-left:25px;}
#account_info_status td.f a{color: #464646}
#account_info_status td.f a:hover{color:red;}
#account_info_status td.f.red a{color: red;}
#account_info_status td.f.green a{color: green;}
#account_info_status table tr.first td.f {padding-top:5px;}

#account_info_status td.r {font-size:12px; padding: 0 0px 0px 0;  color:#777;}
#account_info_status td.r a{color: #777}
#account_info_status td.r a:hover{color:red;}
#account_info_status td.r.red a{color: red;}
#account_info_status td.r.green a{color: green;}

#account_info_status .spam{color:red;}
#account_info_status .waiting{color:#e66f00;}
#account_info_status .approved{color:green;}
#account_info_status .bold{font-weight:bold;}
#account_info_status .widgets-content-text {padding:12px 12px 12px;clear:left;}
#account_info_status .widgets-content-text .b{font-weight:bold;}
#account_info_status a.button{float:right;clear:right;position:relative;top:-5px;}

#account_info_status .totalcalls{float:right; position: relative; left: 0px; top: -200px;}
#account_info_status .totalcalls{font-size:60px; color: #464646}
#account_info_status .totalcalls a:hover{color:red;}


/* AGENTS & PHONES INFO CONTENT */
#agents_phones_logins p.sub,
#agents_phones_logins .table,
#agents_phones_logins .widgets-content-text{margin:-12px;}
#agents_phones_logins .inside{font-size:12px;padding-top:20px;}
#agents_phones_logins p.sub{font-style:italic;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;padding:5px 10px 15px;color:#777;font-size:13px;position:absolute;top:-17px;left:15px;}
#agents_phones_logins .table{margin:0 -9px;padding:5px 10px;position:relative;}
#agents_phones_logins .table_num_seats{float:left;border-top: #ececec 1px solid;width:100%;}
#agents_phones_logins .table_url_resources{float:left;border-top:#ececec 1px solid;width:100%;}
#agents_phones_logins .table_logins{float:left;border-top:#ececec 1px solid;width:42%;}
#agents_phones_logins .table_phones{float:right;border-top:#ececec 1px solid;width:48%;}
#agents_phones_logins table td{padding:3px 0;white-space:nowrap;}
#agents_phones_logins table tr.first td{border-top:none;}

#agents_phones_logins td.b {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#agents_phones_logins td.b a{font-size:60px; color: #464646}
#agents_phones_logins td.b a:hover{color:red;}
#agents_phones_logins td.b.red a{color: red}
#agents_phones_logins td.b.green a{color: #648803}

#agents_phones_logins td.o {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#agents_phones_logins td.o a{font-size:60px; color: #464646}
#agents_phones_logins td.o a:hover{color:red;}
#agents_phones_logins td.o.orange a{color: #FF8000}
#agents_phones_logins td.o.green a{color: #648803}

#agents_phones_logins td.showhide {font-size:12px; font-style:italic; font-weight: bold; cursor: pointer; padding: 0 0px 0px 0;  color:#464646;}
#agents_phones_logins td.showhide a{color: #464646; cursor: pointer;}
#agents_phones_logins td.showhide a:hover{color:green; cursor: pointer;}

#agents_phones_logins td.dp {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#agents_phones_logins td.dp a{font-size:20px; color: #FF8000}
#agents_phones_logins td.dp a:hover{color:#FF8000;}
#agents_phones_logins td.dp.orange a{color: #FF8000}
#agents_phones_logins td.dp.green a{color: green}

#agents_phones_logins td.t {font-size:12px; padding: 0 0px 0px 0;  color:#777;}
#agents_phones_logins td.t a{color: #777}
#agents_phones_logins td.t a:hover{color:red;}
#agents_phones_logins td.t.red a{color: red;}
#agents_phones_logins td.t.green a{color: green;}
#agents_phones_logins td.t.bold a{font-weight: bold;}
#agents_phones_logins td.t.pbold {padding:3px 0;font-weight: bold;}

#agents_phones_logins td.c {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#agents_phones_logins td.c a{font-size:30px; color: #464646}
#agents_phones_logins td.c a:hover{color:red;}
#agents_phones_logins td.c.red a{color: red;}
#agents_phones_logins td.c.green a{color: green;}

#agents_phones_logins td.d {padding-left:6px;text-align:left;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#agents_phones_logins td.d a{font-size:24px; color: #464646;}
#agents_phones_logins td.d a:hover{color:red;}
#agents_phones_logins td.d.red a{color: red;}
#agents_phones_logins td.d.green a{color: green;}

#agents_phones_logins td.e {padding-left:6px;text-align:left;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#agents_phones_logins td.e a{font-size:18px; color: #464646}
#agents_phones_logins td.e a:hover{color:red;}
#agents_phones_logins td.e.red a{color: red;}
#agents_phones_logins td.e.green a{color: green;}

#agents_phones_logins td.f {font-size:12px; padding: 1px 0; padding-left:25px;}
#agents_phones_logins td.f a{color: #464646}
#agents_phones_logins td.f a:hover{color:red;}
#agents_phones_logins td.f.red a{color: red;}
#agents_phones_logins td.f.green a{color: green;}
#agents_phones_logins table tr.first td.f {padding-top:5px;}

#agents_phones_logins td.r {font-size:12px; padding: 0 0px 0px 0;  color:#777;}
#agents_phones_logins td.r a{color: #777}
#agents_phones_logins td.r a:hover{color:red;}
#agents_phones_logins td.r.red a{color: red;}
#agents_phones_logins td.r.green a{color: green;}

#agents_phones_logins .spam{color:red;}
#agents_phones_logins .waiting{color:#e66f00;}
#agents_phones_logins .approved{color:green;}
#agents_phones_logins .bold{font-weight:bold;}
#agents_phones_logins .widgets-content-text {padding:12px 12px 12px;clear:left;}
#agents_phones_logins .widgets-content-text .b{font-weight:bold;}
#agents_phones_logins a.button{float:right;clear:right;position:relative;top:-5px;}

#agents_phones_logins .totalcalls{float:right; position: relative; left: 0px; top: -200px;}
#agents_phones_logins .totalcalls{font-size:60px; color: #464646}
#agents_phones_logins .totalcalls a:hover{color:red;}
	



/* FRESHDESK CONTENT */
#freshdesk_widget p.sub,
#freshdesk_widget p.submenu,
#freshdesk_widget .table,
#freshdesk_widget .widgets-content-text{margin:-12px;}
#freshdesk_widget .inside{font-size:12px;padding-top:20px;}
#freshdesk_widget p.sub{font-style:italic;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;padding:5px 10px 15px;color:#777;font-size:13px;position:absolute;top:-17px;left:15px;}
#freshdesk_widget p.submenu{font-style:italic;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;padding:5px 10px 15px;color:#777;font-size:13px;position:absolute;top:5px;left:10px;}
#freshdesk_widget .table{margin:0 -9px;padding:5px 10px;position:relative;}
#freshdesk_widget .table_balance{float:left;border-top: #ececec 1px solid;width:45%;}
#freshdesk_widget .table_account{float:right;border-top:#ececec 1px solid;width:45%;}
#freshdesk_widget .table_info{float:left;border-top:#ececec 1px solid;width:45%;}
#freshdesk_widget .table_reports{float:left;border-top:#ececec 1px solid;width:100%;}
#freshdesk_widget .table_submitted_tickets{float:left;border-top:#ececec 1px solid;width:100%;}
#freshdesk_widget table {width:100%; border-spacing: 0px;}
#freshdesk_widget table td{padding:3px 0;white-space:nowrap;}
#freshdesk_widget table tr:hover{background-color:#e6f3f9;}
#freshdesk_widget table tr.first td{border-top:none;cursor: pointer;}

#freshdesk_widget td.b {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#freshdesk_widget td.b a{font-size:60px; color: #464646}
#freshdesk_widget td.b a:hover{color:red;}
#freshdesk_widget td.b.red a{color: red}
#freshdesk_widget td.b.green a{color: #648803}

#freshdesk_widget td.o {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#freshdesk_widget td.o a{font-size:60px; color: #464646}
#freshdesk_widget td.o a:hover{color:red;}
#freshdesk_widget td.o.orange a{color: #FF8000}
#freshdesk_widget td.o.green a{color: #648803}

#freshdesk_widget td.showhide {font-size:12px; font-style:italic; font-weight: bold; cursor: pointer; padding: 0 0px 0px 0;  color:#464646;}
#freshdesk_widget td.showhide a{color: #464646; cursor: pointer;}
#freshdesk_widget td.showhide a:hover{color:green; cursor: pointer;}

#freshdesk_widget td.dp {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#freshdesk_widget td.dp a{font-size:20px; color: #FF8000}
#freshdesk_widget td.dp a:hover{color:#FF8000;}
#freshdesk_widget td.dp.orange a{color: #FF8000}
#freshdesk_widget td.dp.green a{color: green}

#freshdesk_widget td.t {font-size:12px; padding: 0 0px 0px 0;  color:#777;}
#freshdesk_widget td.t a{color: #777}
#freshdesk_widget td.t a:hover{color:red;}
#freshdesk_widget td.t.red a{color: red;}
#freshdesk_widget td.t.green a{color: green;}
#freshdesk_widget td.t.bold a{font-weight: bold;}

#freshdesk_widget td.c {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#freshdesk_widget td.c a{font-size:30px; color: #464646}
#freshdesk_widget td.c a:hover{color:red;}
#freshdesk_widget td.c.red a{color: red;}
#freshdesk_widget td.c.green a{color: green;}

#freshdesk_widget td.d {padding-left:6px;text-align:left;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#freshdesk_widget td.d a{font-size:24px; color: #464646;}
#freshdesk_widget td.d a:hover{color:red;}
#freshdesk_widget td.d.red a{color: red;}
#freshdesk_widget td.d.green a{color: green;}

#freshdesk_widget td.e {padding-left:6px;text-align:left;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#freshdesk_widget td.e a{font-size:18px; color: #464646}
#freshdesk_widget td.e a:hover{color:red;}
#freshdesk_widget td.e.red a{color: red;}
#freshdesk_widget td.e.green a{color: green;}

#freshdesk_widget td.f {font-size:12px; padding: 1px 0; padding:5px 0 5px 25px; line-height: 135%; width: 50%;}
#freshdesk_widget td.f a{color: #464646}
#freshdesk_widget td.f a:hover{color:red;}
#freshdesk_widget td.f.red a{color: red;}
#freshdesk_widget td.f.green a{color: green;}
#freshdesk_widget table tr.first td.f {padding-top:5px;}

#freshdesk_widget td.g {font-size:16px; padding: 1px 0; padding:5px 0 5px 25px; line-height: 135%; width: 50%;}
#freshdesk_widget td.g a{color: #464646}
#freshdesk_widget td.g a:hover{color:red;}
#freshdesk_widget td.g.red a{color: red;}
#freshdesk_widget td.g.green a{color: green;}
#freshdesk_widget table tr.first td.f {padding-top:5px;}

#freshdesk_widget td.r {font-size:12px; padding: 0 0px 0px 0;  color:#777;}
#freshdesk_widget td.r a{color: #777}
#freshdesk_widget td.r a:hover{color:red;}
#freshdesk_widget td.r.red a{color: red;}
#freshdesk_widget td.r.green a{color: green;}

#freshdesk_widget .spam{color:red;}
#freshdesk_widget .waiting{color:#e66f00;}
#freshdesk_widget .approved{color:green;}
#freshdesk_widget .bold{font-weight:bold;}
#freshdesk_widget .widgets-content-text {padding:12px 12px 12px;clear:left;}
#freshdesk_widget .widgets-content-text .b{font-weight:bold;}
#freshdesk_widget a.button{float:right;clear:right;position:relative;top:-5px;}

#freshdesk_widget .totalcalls{float:right; position: relative; left: 0px; top: -200px;}
#freshdesk_widget .totalcalls{font-size:60px; color: #464646}
#freshdesk_widget .totalcalls a:hover{color:red;}


/* REALTIME MONITORING CONTENT */
#real_time_monitoring p.sub,
#real_time_monitoring p.submenu,
#real_time_monitoring .table,
#real_time_monitoring .widgets-content-text{margin:-12px;}
#real_time_monitoring .inside{font-size:12px;padding-top:20px;}
#real_time_monitoring p.sub{font-style:italic;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;padding:5px 10px 15px;color:#777;font-size:13px;position:absolute;top:-17px;left:15px;}
#real_time_monitoring p.submenu{font-style:italic;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;padding:5px 10px 15px;color:#777;font-size:13px;position:absolute;top:5px;left:10px;}
#real_time_monitoring .table{margin:0 -9px;padding:5px 10px;position:relative;}
#real_time_monitoring .table_balance{float:left;border-top: #ececec 1px solid;width:45%;}
#real_time_monitoring .table_account{float:right;border-top:#ececec 1px solid;width:45%;}
#real_time_monitoring .table_info{float:left;border-top:#ececec 1px solid;width:45%;}
#real_time_monitoring .table_reports{float:left;border-top:#ececec 1px solid;width:100%;}
#real_time_monitoring table td{padding:3px 0;white-space:nowrap;}
#real_time_monitoring table tr.first td{border-top:none;}

#real_time_monitoring td.b {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#real_time_monitoring td.b a{font-size:60px; color: #464646}
#real_time_monitoring td.b a:hover{color:red;}
#real_time_monitoring td.b.red a{color: red}
#real_time_monitoring td.b.green a{color: #648803}

#real_time_monitoring td.o {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#real_time_monitoring td.o a{font-size:60px; color: #464646}
#real_time_monitoring td.o a:hover{color:red;}
#real_time_monitoring td.o.orange a{color: #FF8000}
#real_time_monitoring td.o.green a{color: #648803}

#real_time_monitoring td.showhide {font-size:12px; font-style:italic; font-weight: bold; cursor: pointer; padding: 0 0px 0px 0;  color:#464646;}
#real_time_monitoring td.showhide a{color: #464646; cursor: pointer;}
#real_time_monitoring td.showhide a:hover{color:green; cursor: pointer;}

#real_time_monitoring td.dp {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#real_time_monitoring td.dp a{font-size:20px; color: #FF8000}
#real_time_monitoring td.dp a:hover{color:#FF8000;}
#real_time_monitoring td.dp.orange a{color: #FF8000}
#real_time_monitoring td.dp.green a{color: green}

#real_time_monitoring td.t {font-size:12px; padding: 0 0px 0px 0;  color:#777;}
#real_time_monitoring td.t a{color: #777}
#real_time_monitoring td.t a:hover{color:red;}
#real_time_monitoring td.t.red a{color: red;}
#real_time_monitoring td.t.green a{color: green;}
#real_time_monitoring td.t.bold a{font-weight: bold;}

#real_time_monitoring td.c {padding-right:6px;text-align:right;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#real_time_monitoring td.c a{font-size:30px; color: #464646}
#real_time_monitoring td.c a:hover{color:red;}
#real_time_monitoring td.c.red a{color: red;}
#real_time_monitoring td.c.green a{color: green;}

#real_time_monitoring td.d {padding-left:6px;text-align:left;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#real_time_monitoring td.d a{font-size:24px; color: #464646;}
#real_time_monitoring td.d a:hover{color:red;}
#real_time_monitoring td.d.red a{color: red;}
#real_time_monitoring td.d.green a{color: green;}

#real_time_monitoring td.e {padding-left:6px;text-align:left;font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;font-size:14px;width:1%;}
#real_time_monitoring td.e a{font-size:18px; color: #464646}
#real_time_monitoring td.e a:hover{color:red;}
#real_time_monitoring td.e.red a{color: red;}
#real_time_monitoring td.e.green a{color: green;}

#real_time_monitoring td.f {font-size:12px; padding: 1px 0; padding-left:25px;}
#real_time_monitoring td.f a{color: #464646}
#real_time_monitoring td.f a:hover{color:red;}
#real_time_monitoring td.f.red a{color: red;}
#real_time_monitoring td.f.green a{color: green;}
#real_time_monitoring table tr.first td.f {padding-top:5px;}

#real_time_monitoring td.r {font-size:12px; padding: 0 0px 0px 0;  color:#777;}
#real_time_monitoring td.r a{color: #777}
#real_time_monitoring td.r a:hover{color:red;}
#real_time_monitoring td.r.red a{color: red;}
#real_time_monitoring td.r.green a{color: green;}

#real_time_monitoring .spam{color:red;}
#real_time_monitoring .waiting{color:#e66f00;}
#real_time_monitoring .approved{color:green;}
#real_time_monitoring .bold{font-weight:bold;}
#real_time_monitoring .widgets-content-text {padding:12px 12px 12px;clear:left;}
#real_time_monitoring .widgets-content-text .b{font-weight:bold;}
#real_time_monitoring a.button{float:right;clear:right;position:relative;top:-5px;}

#real_time_monitoring .totalcalls{float:right; position: relative; left: 0px; top: -200px;}
#real_time_monitoring .totalcalls{font-size:60px; color: #464646}
#real_time_monitoring .totalcalls a:hover{color:red;}


#ticket-subject {padding: 10px 10px 10px 10px; }
#ticket.ticket-subject-odd{background-color:#E0F8E0;margin:10px 0;}
#ticket.ticket-subject-even{background-color:#EFFBEF;margin:10px 0;}
#ticket-subject h1{font-size:12px;margin:0;}
#ticket-subject .headerinfo{font-style:italic;margin:0;width:50%;float:left;}
#ticket-subject .headerasignee{font-style:italic;margin:0;text-align:right;width:50%;}
#ticket-subject .desc{width:100%;padding:10px;}
#ticket .desc p{margin:0 10px 0 10px;}
#ticket .close {display:none;}
#ticket-subject .headerholder{padding:0px;}
#ticket .conversation{
          width:90%;
          margin:20px 20px 10px 20px;
          border: 1px #DFDFDF solid;
          border-top-left-radius: 5px;
          border-top-right-radius: 5px;
          border-bottom-left-radius: 5px;
          border-bottom-right-radius: 5px;
          -moz-border-radius-topleft:5px;
          -moz-border-radius-topright:5px;
          -moz-border-radius-bottomleft:5px;
          -moz-border-radius-bottomright:5px;
          /*padding:10px 0 3px 0;*/
          padding:10px;
          background-color: #FFFFFF;
          box-shadow: 7px 7px 10px #DDDDDD;
       }
#ticket .conversation .responder {width:100%;  } 
#ticket .conversation .response {width: 100%;margin:10px;  } 
 .goautodial {background-color: #D4F8E2 }
#ticket .ticket-action {padding: 20px 10px 30px 20px;}
#ticket .note-to-add {display: none;padding:0 10px 10px 30px}
#ticket .addnotebutton {padding:10px; text-align:right;}
#ticket .conversation-container {padding:0px;}
#ticket .conversation-container img{position: relative; left:20px;}


#quick-support-container {
   width : 100%;
   text-align:right;
}
#quick-support{
     position: fixed;   /*Panel will overlap  content */
     /*position: relative;*/   /*Panel will "push" the content down */
     /*width: 30%;
     margin-left: 70%;*/
     top: 31px;
     height:150px;
     width: 100%;
     margin-left: auto;
     margin-right: auto;
}

.call-quick-support{
    background: url(../js/slidingpanel/images/tab_b.png) repeat-x 0 0;                     
    height: 42px;                                                          
    position: relative;                                                    
    top: 0;                                                                    
    z-index: 10;
}

.call-quick-support ul.caller {                                                                
        display: block;                                                        
        position: relative;                                                    
        float: right;                                                          
        clear: right;                                                          
        height: 42px;                                                          
        width: auto;
        font-weight: bold;                                                     
        line-height: 42px;                                                     
        margin: 0;                                                             
        right: 150px;
        color: white;                                                          
        font-size: 80%;
        text-align: center;
}

.call-quick-support ul.caller li.left {
        background: url(../js/slidingpanel/images/tab_l.png) no-repeat left 0;
        height: 42px;
        width: 30px;
        padding: 0;
        margin: 0;
        display: block;
        float: left;
}

.call-quick-support ul.caller li.right {
        background: url(../js/slidingpanel/images/tab_r.png) no-repeat left 0;
        height: 42px;
        width: 30px;
        padding: 0;
        margin: 0;
        display: block;
        float: left;
}

.call-quick-support ul.caller li {
        text-align: left;
        padding: 0 6px;
        display: block;
        float: left;
        height: 42px;
        background: url(../js/slidingpanel/images/tab_m.png) repeat-x 0 0;
}

.call-quick-support ul.caller li a {
        color: #15ADFF;
}

.call-quick-support ul.caller li a:hover {
        color: white;
}

.call-quick-support .sep {color:#414141}

.call-quick-support a.support-open, .call-quick-support a.support-close {
        height: 20px;
        line-height: 20px !important;
        padding-left: 30px !important;
        cursor: pointer;
        display: block;
        width: 100px;
        position: relative;
        top: 11px;
}

.call-quick-support a.support-open {background: url(../js/slidingpanel/images/bt_open.png) no-repeat left 0;}
.call-quick-support a.support-close {background: url(../js/slidingpanel/images/bt_close.png) no-repeat left 0;}
.call-quick-support a:hover.support-open {background: url(../js/slidingpanel/images/bt_open.png) no-repeat left -19px;}
.call-quick-support a:hover.support-close {background: url(../js/slidingpanel/images/bt_close.png) no-repeat left -19px;}

#quick-support-detail {
        width: 100%;
        height: 240px;
        color: #999999;
        background: <?=$THEMECOLOR?>;
        overflow: hidden;
        position: relative;
        z-index: 10;
        display: none;
}

#quick-support-detail input{
   margin-top: 30px;
   margin-right: 50px;
}

#quick-support-detail textarea{
   margin-top: 2px;
   margin-right: 50px;
   resize:none;
}

#quick-support-detail .quick-button{
   margin-top: 2px;
}

/* For Go Reports */
.go_reports_menu{
	z-index:99;
	position:absolute;
	top:128px;
	left:65px;
	border:#CCC 1px solid;
	background-color:#FFF;
	display:none;
	cursor:pointer;
	/*height:184px;*/
}

#go_reports_menu ul{
	list-style-type:none;
	padding: 1px;
	margin: 0px;
}

.go_campaign_menu{
	z-index:99;
	position:absolute;
	top:158px;
	left:65px;
	border:#CCC 1px solid;
	background-color:#FFF;
	display:none;
	cursor:pointer;
}

.go_reports_submenu{
	padding: 3px 10px 3px 5px;
	margin: 0px;
}

.hovermenu{
	cursor:pointer;
	background:url('../img/down-arrow.png') no-repeat right center;
}

.tabmenu{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	padding:3px 20px 5px 10px;
	color:#777;
	font-size:11px;
	position:relative;
	top:-17px;
/* 	left:-6px; */
	cursor:pointer;
	border:#CCC 1px solid;
	line-height:1;
	-moz-border-radius:3px;
	-khtml-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;
	width:110px;
	background:url('../img/down-arrow.png') no-repeat 95% center;
}

.tabmenu:hover{
	background:url('../img/down-arrow.png') no-repeat 95% center, linear-gradient(bottom, #AAAAAA 0%, #EEEEEE 30%, #EEEEEE 70%, #AAAAAA 100%);
	background:url('../img/down-arrow.png') no-repeat 95% center, -o-linear-gradient(bottom, #AAAAAA 0%, #EEEEEE 30%, #EEEEEE 70%, #AAAAAA 100%);
	background:url('../img/down-arrow.png') no-repeat 95% center, -moz-linear-gradient(bottom, #AAAAAA 0%, #EEEEEE 30%, #EEEEEE 70%, #AAAAAA 100%);
	background:url('../img/down-arrow.png') no-repeat 95% center, -webkit-linear-gradient(bottom, #AAAAAA 0%, #EEEEEE 30%, #EEEEEE 70%, #AAAAAA 100%);
	background:url('../img/down-arrow.png') no-repeat 95% center, -ms-linear-gradient(bottom, #AAAAAA 0%, #EEEEEE 30%, #EEEEEE 70%, #AAAAAA 100%);
	background:url('../img/down-arrow.png') no-repeat 95% center, -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0, #AAAAAA),
		color-stop(0.3, #EEEEEE),
		color-stop(0.7, #EEEEEE),
		color-stop(1, #AAAAAA)
	);
}

.tabhover{
    font-weight: bold;
	/*background:linear-gradient(bottom, #AAAAAA 0%, #EEEEEE 30%, #EEEEEE 70%, #AAAAAA 100%);*/
	/*background:-o-linear-gradient(bottom, #AAAAAA 0%, #EEEEEE 30%, #EEEEEE 70%, #AAAAAA 100%);*/
	/*background:-moz-linear-gradient(bottom, #AAAAAA 0%, #EEEEEE 30%, #EEEEEE 70%, #AAAAAA 100%);*/
	/*background:-webkit-linear-gradient(bottom, #AAAAAA 0%, #EEEEEE 30%, #EEEEEE 70%, #AAAAAA 100%);*/
	/*background:-ms-linear-gradient(bottom, #AAAAAA 0%, #EEEEEE 30%, #EEEEEE 70%, #AAAAAA 100%);*/
	/*background:-webkit-gradient(*/
	/*	linear,*/
	/*	left bottom,*/
	/*	left top,*/
	/*	color-stop(0, #AAAAAA),*/
	/*	color-stop(0.3, #EEEEEE),*/
	/*	color-stop(0.7, #EEEEEE),*/
	/*	color-stop(1, #AAAAAA)*/
	/*);*/
}

.hourlytab{
	font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
	padding:3px 10px 4px 10px;
	color:#777;
	font-size:11px;
	top:-17px;
	cursor:pointer;
	border:#CCC 1px solid;
	line-height:1;
	-moz-border-radius:3px;
	-khtml-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;
}

.weeklytab{
	font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
	padding:3px 10px 4px 10px;
	color:#777;
	font-size:11px;
	top:-17px;
	cursor:pointer;
	border:#CCC 1px solid;
	line-height:1;
	-moz-border-radius:3px;
	-khtml-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;
}

.monthlytab{
	font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
	padding:3px 10px 4px 10px;
	color:#777;
	font-size:11px;
	cursor:pointer;
	border:#CCC 1px solid;
	line-height:1;
	-moz-border-radius:3px;
	-khtml-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;
}

.dailytab{
	font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
	padding:3px 10px 4px 10px;
	color:#777;
	font-size:11px;
	cursor:pointer;
	border:#CCC 1px solid;
	line-height:1;
	-moz-border-radius:3px;
	-khtml-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;
}

.outboundtab{
	font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
	padding:3px 10px 4px 10px;
	color:#777;
	font-size:11px;
	cursor:pointer;
	border:#CCC 1px solid;
	line-height:1;
	-moz-border-radius:3px;
	-khtml-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;
}

.inboundtab{
	font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
	padding:3px 10px 4px 10px;
	color:#777;
	font-size:11px;
	cursor:pointer;
	border:#CCC 1px solid;
	line-height:1;
	-moz-border-radius:3px;
	-khtml-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;
}

.exporttab{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	padding:5px 10px 4px 10px;
	color:#7A9E22;
	font-size:13px;
	cursor:pointer;
    float: right;
	/*border:#CCC 1px solid;*/
	line-height:1;
	/*-moz-border-radius:3px;*/
	/*-khtml-border-radius:3px;*/
	/*-webkit-border-radius:3px;*/
	/*border-radius:3px;*/
}

.menutab{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	padding:3px 10px 4px 10px;
	color:#777;
	font-size:11px;
	cursor:pointer;
	border:#CCC 1px solid;
	line-height:1;
	-moz-border-radius:3px;
	-khtml-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;
}

.midheader{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	padding:3px 10px 4px 10px;
	margin-bottom:3px;
	color:#777;
	font-size:11px;
	cursor:default;
	background-color:#EEE;
	border:#CCC 1px solid;
	line-height:1;
	-moz-border-radius:3px;
	-khtml-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;
}

.rightdiv{float:right;
          border:none;
          font-size:12px;
          font-weight:bold;
          padding:7px 9px;
          margin:0;
          line-height:1;
          z-index:10;
          position:relative;}


/* Table Pagination */
#tablePagination { 
	font-size: 0.8em; 
	padding: 0px 5px; 
	height: 20px
}

#tablePagination input, #tablePagination select {
	font-size: 11px;
}

#tablePagination_paginater { 
	margin-left: auto; 
	margin-right: auto;
}

#tablePagination img { 
	padding: 0px 3px;
	vertical-align:middle;
	cursor: pointer;
}

#tablePagination_perPage { 
	float: left;
}

#tablePagination_paginater { 
	float: right; 
}

#tablePagination_currPage {
	font-size: 11px;
	border:1px solid #cccccc;
	border-top-left-radius: 3px;
	border-bottom-left-radius: 3px;
	-moz-border-radius-topleft: 3px;
	-moz-border-radius-bottomleft: 3px;
	border-top-right-radius: 3px;
	border-bottom-right-radius: 3px;
	-moz-border-radius-topright: 3px;
	-moz-border-radius-bottomright: 3px;
	padding: 3px;
}



#tablePaginationpayment {
        font-size: 0.8em;
        padding: 0px 5px;
        height: 20px
}

#tablePaginationpayment input, #tablePaginationpayment select {
        font-size: 11px;
}

#tablePaginationpayment_paginater {
        margin-left: auto;
        margin-right: auto;
}       

#tablePaginationpayment img {
        padding: 0px 3px;
        vertical-align:middle;
        cursor: pointer;
}

#tablePaginationpayment_perPage {
        float: left; 
}       
        
#tablePaginationpayment_paginater {
        float: right;
}


/* Table Pagination */

/* css for timepicker */
.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
.ui-timepicker-div dl { text-align: left; }
.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
.ui-timepicker-div td { font-size: 90%; }
.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }

.ui-timepicker-rtl{ direction: rtl; }
.ui-timepicker-rtl dl { text-align: right; }
.ui-timepicker-rtl dl dd { margin: 0 65px 10px 10px; }

#ui-datepicker-div { display: none; }

/* CSS for Agent modify box */
#boxAgent{
	position:absolute;
	top:-2550px;
	left:30%;
	right:30%;
	background-color: #FFF;
	color:#7F7F7F;
	padding:20px;

	-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;border:1px solid #90B09F;
	z-index:999;
}

#closeboxAgent{
	float:right;
	width:26px;
	height:26px;
	background:transparent url(../img/cancel.png) repeat top left;
	margin-top:-30px;
	margin-right:-30px;
	cursor:pointer;
}

#overlayAgent{
	background:transparent url(../img/overlay.png) repeat top left;
	position:fixed;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	z-index:102;
}
